<?php
 
class Hackathon_Layeredlanding_Model_Options_ListMode
{
    public function toOptionArray() {
        $options = array(
            array('value' => '',   'label' => Mage::helper('layeredlanding')->__('-- Allow any --')),
            array('value'=>'grid', 'label' => Mage::helper('adminhtml')->__('Grid Mode')),
            array('value'=>'list', 'label' => Mage::helper('adminhtml')->__('List Mode')),
        );
        return $options;
	}
}
