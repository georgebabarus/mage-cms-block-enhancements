<?php
/**
 * Zitec extension for Magento
 *
 * Long description of this file (if any...)
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade
 * the Bb CmsBlock module to newer versions in the future.
 * If you wish to customize the Bb CmsBlock module for your needs
 * please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Bb
 * @package    Bb_CmsBlock
 * @copyright  Copyright (C) 2014 
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Short description of the class
 *
 * Long description of the class (if any...)
 *
 * @category   Bb
 * @package    Bb_CmsBlock
 * @subpackage Block
 * @author     George Babarus <george.babarus@gmail.com>
 */
class Bb_CmsBlock_Block_Block extends Mage_Cms_Block_Block
{
    /**
     * Prepare Content HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        $blockId = $this->getBlockId();

        Mage::getSingleton('core/session', array('name' => 'adminhtml'))->start();
        $adminLoggedIn = Mage::getSingleton('admin/session', array('name' => 'adminhtml'))->isLoggedIn();
        $html = '';
        $developerMode = Mage::getIsDeveloperMode();

        if ($blockId) {
            $block = Mage::getModel('cms/block')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($blockId);
            if ($adminLoggedIn || $developerMode){
                $url = Mage::helper("adminhtml")->getUrl("adminhtml/cms_block/edit/",array('block_id'=>$block->getId()));
                $html .= '<span class="cms-block-highlight"><a target="_blank" href="'.$url.'">highlight</a>';
            }

            if ($block->getIsActive()) {
                /* @var $helper Mage_Cms_Helper_Data */
                $helper = Mage::helper('cms');
                $processor = $helper->getBlockTemplateProcessor();
                $html = $processor->filter($block->getContent());
                $this->addModelTags($block);
            }
        }
        if ($adminLoggedIn || $developerMode){
            $html = '</span>';
        }
        Mage::getSingleton('core/session', array('name' => 'frontend'))->start();
        return $html;
    }
}
