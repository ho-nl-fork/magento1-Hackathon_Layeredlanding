<?php
 
class Hackathon_Layeredlanding_Model_Options_Pagetype
{
    const TYPE_CATEGORY = 'category';
    const TYPE_SEARCH   = 'search';

    public function toOptionArray() {
        $options = array(
            array('value' => '',   'label' => Mage::helper('catalog')->__('-- Please Select --')),
            array('value'=> self::TYPE_CATEGORY, 'label' => Mage::helper('adminhtml')->__('Category')),
            array('value'=> self::TYPE_SEARCH, 'label' => Mage::helper('adminhtml')->__('Search')),
        );
        return $options;
	}
}
