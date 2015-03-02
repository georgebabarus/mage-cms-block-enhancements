<?php
/**
 * Created by PhpStorm.
 * User: g
 * Date: 26.02.2015
 * Time: 22:10
 */

class Bb_CmsBlock_Block_Adminhtml_Page_Grid extends Mage_Adminhtml_Block_Cms_Page_Grid
{


    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('page');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'=> Mage::helper('cms')->__('Delete'),
            'url'  => $this->getUrl('*/cms_page_update/massDelete'),
            'confirm' => Mage::helper('cms')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('cms/page')->getAvailableStatuses();
        //array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
            'label'=> Mage::helper('catalog')->__('Change status'),
            'url'  => $this->getUrl('*/cms_page_update/massStatus', array('_current'=>true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('cms')->__('Status'),
                    'values' => $statuses
                )
            )
        ));

        if (Mage::getSingleton('admin/session')->isAllowed('cms/mass_update')){
            $this->getMassactionBlock()->addItem('attributes', array(
                'label' => Mage::helper('cms')->__('Update multiple entries'),
                'url'   => $this->getUrl('*/cms_page_update/massUpdate', array('_current'=>true))
            ));
        }

        Mage::dispatchEvent('adminhtml_cms_page_grid_prepare_massaction', array('block' => $this));
        return $this;
    }



}