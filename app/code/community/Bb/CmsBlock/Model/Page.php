<?php

/**
 * Created by PhpStorm.
 * User: g
 * Date: 26.02.2015
 * Time: 23:38
 */
class Bb_CmsBlock_Model_Page extends Mage_Core_Model_Abstract
{


    public static function getAvailableStatuses()
    {
        return array(
            0 => Mage::helper('cms')->__('Disabled'),
            1 => Mage::helper('cms')->__('Enabled')
        );
    }

    /**
     * retur fields can be updated in a multiple update action
     * @return array
     */
    public static function fieldsUsedInMultiUpdate()
    {
        return array(
            'title',
            'stores',
            'is_active',
            'content_heading',
            'content',
            'root_template',
            'layout_update_xml',
            'custom_theme_from',
            'custom_theme_to',
            'custom_theme',
            'custom_root_template',
            'custom_layout_update_xml',
            'meta_keywords',
            'meta_description',
        );

    }

} 