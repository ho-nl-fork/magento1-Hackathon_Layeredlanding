<?php
 
class Hackathon_Layeredlanding_Model_Options_Sortby
    extends Mage_Catalog_Model_Category_Attribute_Source_Sortby
{

    public function toOptionArray() {
        $sortByOptions = $this->getAllOptions();
        array_unshift($sortByOptions, array(
            'value' => '',
            'label' => Mage::helper('layeredlanding')->__('-- Allow any --')
        ));
        return $sortByOptions;
	}
}
