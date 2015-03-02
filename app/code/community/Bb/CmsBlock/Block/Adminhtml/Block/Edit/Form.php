<?php


class  Bb_CmsBlock_Block_Adminhtml_Block_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{

    /**
     * Init form
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('block_form');
        $this->setTitle(Mage::helper('cms')->__('Block Information'));
    }

    /**
     * Load Wysiwyg on demand and Prepare layout
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }

    protected function _prepareForm()
    {
        $blockIds = Mage::app()->getRequest()->getParam('block');

        $form = new Varien_Data_Form(
            array('id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post')
        );

        $form->setHtmlIdPrefix('block_');

        $fieldset = $form->addFieldset('base_fieldset', array('legend' => Mage::helper('cms')->__('General Information'), 'class' => 'fieldset-wide'));

        if (!empty($blockIds)) {
            $fieldset->addField('block_ids', 'hidden', array(
                'name' => 'block_ids',
                'value'=> implode(',', $blockIds)
            ));
        }

        $fieldset->addField('title', 'text', array(
            'name'     => 'title',
            'label'    => Mage::helper('cms')->__('Block Title'),
            'title'    => Mage::helper('cms')->__('Block Title'),
            'required' => false,
        ));
        $fieldset->addField('title-checkbox', 'checkbox', array(
            'name'     => 'title-checkbox',
            'label'    => '',
            'title'    => '',
            'value'    =>  1,
            'after_element_html' => Mage::helper('cms')->__('Change'),
            'required' => false,
        ));


        /**
         * Check is single store mode
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $field    = $fieldset->addField('store_id', 'multiselect', array(
                'name'     => 'stores[]',
                'label'    => Mage::helper('cms')->__('Store View'),
                'title'    => Mage::helper('cms')->__('Store View'),
                'required' => false,
                'values'   => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            ));
            $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
            $field->setRenderer($renderer);
        } else {
            $fieldset->addField('store_id', 'hidden', array(
                'name'  => 'stores[]',
                'value' => Mage::app()->getStore(true)->getId()
            ));
        }
        $fieldset->addField('stores-checkbox', 'checkbox', array(
            'name'     => 'stores-checkbox',
            'label'    => '',
            'title'    => '',
            'value'    =>  1,
            'after_element_html' => Mage::helper('cms')->__('Change'),
            'required' => false,
        ));


        $fieldset->addField('is_active', 'select', array(
            'label'    => Mage::helper('cms')->__('Status'),
            'title'    => Mage::helper('cms')->__('Status'),
            'name'     => 'is_active',
            'required' => false,
            'options'  => array(
                '1' => Mage::helper('cms')->__('Enabled'),
                '0' => Mage::helper('cms')->__('Disabled'),
            ),
        ));
        $fieldset->addField('is_active-checkbox', 'checkbox', array(
            'name'     => 'is_active-checkbox',
            'label'    => '',
            'title'    => '',
            'value'    =>  1,
            'after_element_html' => Mage::helper('cms')->__('Change'),
            'required' => false,
        ));


        $fieldset->addField('content', 'editor', array(
            'name'     => 'content',
            'label'    => Mage::helper('cms')->__('Content'),
            'title'    => Mage::helper('cms')->__('Content'),
            'style'    => 'height:36em',
            'required' => false,
            'config'   => Mage::getSingleton('cms/wysiwyg_config')->getConfig()
        ));
        $fieldset->addField('content-checkbox', 'checkbox', array(
            'name'     => 'content-checkbox',
            'label'    => '',
            'title'    => '',
            'value'    =>  1,
            'after_element_html' => Mage::helper('cms')->__('Change'),
            'required' => false,
        ));

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

}
