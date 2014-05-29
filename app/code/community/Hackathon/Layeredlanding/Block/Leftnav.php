<?php

class Hackathon_Layeredlanding_Block_Leftnav extends Mage_Core_Block_Template
{
	public function getPagesList()
	{
        /** @var $collection Hackathon_Layeredlanding_Model_Resource_Layeredlanding_Collection */
        $collection = Mage::getModel('layeredlanding/layeredlanding')->getCollection()
			->addFieldToSelect('page_title')
			->addFieldToSelect('page_url')
            ->addStoreFilter(Mage::app()->getStore());

		return $collection;
	}
}
