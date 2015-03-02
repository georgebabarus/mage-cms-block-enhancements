<?php

/**
 * Created by PhpStorm.
 * User: g
 * Date: 26.02.2015
 * Time: 22:35
 */
class Bb_CmsBlock_Adminhtml_Cms_Page_UpdateController extends Mage_Adminhtml_Controller_Action
{


    /**
     *
     */
    public function massUpdateSaveAction()
    {
        $params = Mage::app()->getRequest()->getParams();

        if (empty($params['page_ids'])) {
            $this->_redirect('*/cms_page/index');
            return;
        }
        $tempData = $this->getRequest()->getPost();
        $data     = array();
        foreach ($tempData as $key => $value) {
            if (!in_array($key, Mage::getModel('bb_cms/page')->fieldsUsedInMultiUpdate())) {
                continue;
            }

            if (empty($tempData[$key . '-checkbox']) || $tempData[$key . '-checkbox'] != '1') {
                continue;
            }
            $data[$key] = $value;
        }

        $pageIds = explode(',', $params['page_ids']);
        if (empty($pageIds)) {
            $this->_redirect('*/cms_page/index');
            return;

        }

        foreach ($pageIds as $id) {
            // init model and set data
            $model = Mage::getModel('cms/page')->load($id);
            if (!$model->getId() && $id) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('cms')->__('This page "%s" no longer exists.', $id));
            }

            foreach ($data as $key => $value) {
                $model->setData($key, $value);
            }

            // try to save it
            try {
                // save the data
                $model->save();

            } catch (Exception $e) {
                // display error message
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('cms')->__('Page %s error: %s', $id, $e->getMessage()));
            }
        }

        // display success message
        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('cms')->__('The save process has been finished'));

        $this->_redirect('*/cms_page/index');
    }


    public function massUpdateAction()
    {
        if (!$this->_validatePages()) {
            return;
        }

        $model = Mage::getModel('cms/page');
        Mage::register('cms_page',$model);
        $this->loadLayout();
        $this->renderLayout();
    }


    /**
     * Validate selection of pages for massupdate
     *
     * @return boolean
     */
    protected function _validatePages()
    {
        return true;
    }


    /**
     * remove multiple checked entries
     */
    public function massDeleteAction()
    {

        $entityIds = $this->getRequest()->getParam('page');
        if (!is_array($entityIds)) {
            $this->_getSession()->addError($this->__('Please select page(s).'));
        } else {
            if (!empty($entityIds)) {
                try {
                    foreach ($entityIds as $entryId) {
                        $entryObj = Mage::getModel('cms/page')->load($entryId);
                        Mage::dispatchEvent('bb_cms_controller_cms_page_delete', array('page' => $entryObj));
                        $entryObj->delete();
                    }
                    $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) have been deleted.', count($entityIds))
                    );
                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }
            }
        }
        $this->_redirect('*/cms_page/index');
    }


    /**
     *
     */
    public function massStatusAction()
    {
        $entityIds = (array)$this->getRequest()->getParam('page');
        $status    = (int)$this->getRequest()->getParam('status');

        $i = 0;
        foreach ($entityIds as $entryId) {
            $cmsSingleton = Mage::getModel('cms/page');
            try {
                $this->_validateMassStatus($entityIds, $status);

                $entryObj = $cmsSingleton->load($entryId);
                $entryObj->setData('is_active', $status);
                $entryObj->save();

                $i++;
            } catch (Mage_Core_Model_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()
                    ->addException($e, $this->__('An error occurred while updating the page(s) status.'));
            }
            unset($cmsSingleton);
        }

        if ($i > 0) {
            $this->_getSession()->addSuccess(
                $this->__('Total of %d record(s) have been updated.', $i)
            );
        }


        $this->_redirect('*/cms_page/index', array());

    }


    /**
     * Validate batch of entities before theirs status will be set
     *
     * @throws Mage_Core_Exception
     *
     * @param  array $entityIds
     * @param  int   $status
     *
     * @return void
     */
    public function _validateMassStatus(array $entityIds, $status)
    {

    }


}
