<?php

class Hackathon_Layeredlanding_Block_Adminhtml_Layeredlanding_Grid_Renderer_Category
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row)
	{
		$value = $row->getData($this->getColumn()->getIndex());
		
		$categoryCollection = Mage::getModel('catalog/category')->getCollection();
        $categoryCollection->addIdFilter($value);
        $categoryCollection->addAttributeToSelect('name');

		return implode(', ', $categoryCollection->getColumnValues('name'));
	}
}
