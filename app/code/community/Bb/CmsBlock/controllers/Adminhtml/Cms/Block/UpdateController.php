<?php

/**
 * Created by PhpStorm.
 * User: g
 * Date: 26.02.2015
 * Time: 22:35
 */
class Bb_CmsBlock_Adminhtml_Cms_Block_UpdateController extends Mage_Adminhtml_Controller_Action
{


    /**
     *
     */
    public function massUpdateSaveAction()
    {
        $params = Mage::app()->getRequest()->getParams();
        if (empty($params['block_ids'])) {
            $this->_redirect('*/cms_block/index');

            return;

        }
        $tempData = $this->getRequest()->getPost();
        $data     = array();
        foreach ($tempData as $key => $value) {
            if (!in_array($key, Mage::getModel('bb_cms/block')->fieldsUsedInMultiUpdate())) {
                continue;
            }

            if (empty($tempData[$key . '-checkbox']) || $tempData[$key . '-checkbox'] != '1') {
                continue;
            }
            $data[$key] = $value;
        }

        $blockIds = explode(',', $params['block_ids']);
        if (empty($blockIds)) {
            $this->_redirect('*/cms_block/index');

            return;

        }
        if (count($blockIds)) {
            foreach ($blockIds as $id) {
                // init model and set data
                $model = Mage::getModel('cms/block')->load($id);
                if (!$model->getId() && $id) {
                    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('cms')->__('This block "%s" no longer exists.', $id));
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
                    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('cms')->__('Block %s error: %s', $id, $e->getMessage()));
                }
            }
        }

        // display success message
        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('cms')->__('The save process has been finished'));

        $this->_redirect('*/cms_block/index');
    }


    public function massUpdateAction()
    {
        if (!$this->_validateBlocks()) {
            return;
        }

        $this->loadLayout();
        $this->renderLayout();
    }


    /**
     * Validate selection of blocks for massupdate
     *
     * @return boolean
     */
    protected function _validateBlocks()
    {
        return true;
    }


    /**
     * remove multiple checked entries
     */
    public function massDeleteAction()
    {

        $entityIds = $this->getRequest()->getParam('block');
        if (!is_array($entityIds)) {
            $this->_getSession()->addError($this->__('Please select block(s).'));
        } else {
            if (!empty($entityIds)) {
                try {
                    foreach ($entityIds as $entryId) {
                        $entryObj = Mage::getModel('cms/block')->load($entryId);
                        Mage::dispatchEvent('bb_cms_controller_cms_block_delete', array('block' => $entryObj));
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
        $this->_redirect('*/cms_block/index');
    }


    /**
     *
     */
    public function massStatusAction()
    {
        $entityIds = (array)$this->getRequest()->getParam('block');
        $status    = (int)$this->getRequest()->getParam('status');

        $i = 0;
        foreach ($entityIds as $entryId) {
            $cmsSingleton = Mage::getModel('cms/block');
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
                    ->addException($e, $this->__('An error occurred while updating the block(s) status.'));
            }
            unset($cmsSingleton);
        }

        if ($i > 0) {
            $this->_getSession()->addSuccess(
                $this->__('Total of %d record(s) have been updated.', $i)
            );
        }


        $this->_redirect('*/cms_block/index', array());

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
