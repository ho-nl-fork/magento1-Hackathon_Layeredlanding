<?php
 
class Hackathon_Layeredlanding_Model_Options_Limit
{
    public function toOptionArray($mode = 'grid') {
        $options = array(array(
            'value' => '',
            'label' => Mage::helper('layeredlanding')->__('-- Allow any --')
        ));
        $perPageValues = Mage::getStoreConfig(sprintf('catalog/frontend/%s_per_page_values', $mode));
        foreach (explode(',', $perPageValues) as $perPageValue) {
            $options[] = array('value' => $perPageValue, 'label' => $perPageValue);
        }
        return $options;
	}
}
