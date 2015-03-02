<?php

/**
 * Bb extension for Magento - fully suport - easy to use
 *
 *
 * @category   Bb
 * @package    Bb_Slider
 * @copyright  Copyright (C) 2014 Bb (http://www.babarus.ro/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *
 * @author     George Babarus <george.babarus@gmail.com>
 *
 */
class Bb_Core_Adminhtml_BbModulesController extends Mage_Adminhtml_Controller_Action
{

    /**
     * put some init stuff here
     */
    public function init()
    {


    }


    /**
     * help user to use bb products
     */
    public function productsAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('bb_products');
        $this->renderLayout();
    }


    /**
     * help user to use bb products
     */
    public function helpAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('bb_products');

        $url = sprintf('http://docs.babarus.ro/help-documents/products/%s/help.html',$this->getRequest()->getParam('module'));
        $this->getLayout()->getBlock('bb_help')->setData('url', $url);
        $this->renderLayout();
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('bb_slider');
    }

}