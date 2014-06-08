<?php
 
class Hackathon_Layeredlanding_Model_Options_Order
    extends Mage_Catalog_Model_Category_Attribute_Source_Sortby
{
    public function toOptionArray($mode = 'grid') {
        $options = array(
            array('value' => '',    'label' => Mage::helper('layeredlanding')->__('-- Allow any --')),
            array('value' => 'asc', 'label' => Mage::helper('layeredlanding')->__('Ascending')),
            array('value' => 'desc','label' => Mage::helper('layeredlanding')->__('Descending')),
        );
        return $options;
	}
}
