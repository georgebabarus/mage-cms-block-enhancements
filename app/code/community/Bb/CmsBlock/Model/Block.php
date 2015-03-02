<?php

/**
 * Created by PhpStorm.
 * User: g
 * Date: 26.02.2015
 * Time: 23:38
 */
class Bb_CmsBlock_Model_Block extends Mage_Core_Model_Abstract
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
            'is_active',
            'content',
            'stores',
        );
    }



} 