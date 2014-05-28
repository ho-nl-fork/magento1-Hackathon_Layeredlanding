<?php

class Hackathon_Layeredlanding_Block_Adminhtml_Layeredlanding_Edit_Renderer_Attributes
 extends Mage_Adminhtml_Block_Widget
 implements Varien_Data_Form_Element_Renderer_Interface
{

    /**
     * Initialize block
     */
    public function __construct()
    {
        $this->setTemplate('layeredlanding/attributes.phtml');
    }

    /**
     * Render HTML
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);
        return $this->toHtml();
    }
	
	/**
     * Get select options for attributes dropdown
     *
     * @param int $active_id
     * @return string
	 */ 
	public function getAttributeOptions($active_id = 0)
	{
		$attributes = Mage::getModel('layeredlanding/options_attributes')->toOptionArray();

        $html = '';
		
		if (!$active_id) $html = '<option value="">'.Mage::helper('adminhtml')->__('-- Please Select --').'</option>'; // no selection yet

		foreach ($attributes as $attribute)
		{
			$selected = ((int)$attribute['value'] == $active_id) ? 'selected ' : '' ;
			
			$html .= '<option ' . $selected . 'value="' . $attribute['value'] . '">' . $attribute['label'] . '</option>';
		}
		
		return $html;
	}


    /**
     * Get select options for values dropdown
     *
     * @param int $attributeId
     * @param int $optionId
     * @param     $inputName
     * @return string
     */
	public function getValueOptions($attributeId = 0, $optionId = 0, $inputName)
	{
        $storeIds = Mage::registry('layeredlanding_data')->getStoreId();

		return Mage::getModel('layeredlanding/attributes')->getGridOptionsHtml($attributeId, $storeIds, $optionId, $inputName);
	}
}