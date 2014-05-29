<?php

class Hackathon_Layeredlanding_Model_Options_Landingpages
{
    public function toOptionArray()
    {
		$collection = Mage::getModel('layeredlanding/layeredlanding')->getCollection()
			->addFieldToSelect('layeredlanding_id')
			->addFieldToSelect('page_title');
        $collection->walk('afterload');
		
		$options = array();
		foreach ($collection as $item) {
			$stores = array();
			foreach ($item->getData('store_id') as $store) {
				$stores[] = Mage::app()->getStore($store)->getName();
			}

			$options[] = array(
                'value' => $item->getData('layeredlanding_id'),
                'label' => $item->getData('page_title').' ('.implode(', ', $stores).')'
            );
		}
		
		return $options;
    }
}
