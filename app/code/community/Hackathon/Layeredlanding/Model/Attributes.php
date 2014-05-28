<?php
 
class Hackathon_Layeredlanding_Model_Attributes extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('layeredlanding/attributes');
    }
	
	public function getGridOptionsHtml($attributeId = 0, $storeId = 0, $optionId = 0, $inputName)
	{
		$attribute = Mage::getModel('eav/entity_attribute')->load((int)$attributeId);
		
        if ($attribute->getId() && in_array($attribute->getData('frontend_input'), array('select','multiselect')))
		{
            $optionCollection = Mage::getResourceModel('eav/entity_attribute_option_collection');
            $optionCollection->setAttributeFilter($attributeId);
            $optionCollection->setStoreFilter(0);
            $options = $optionCollection->toOptionArray();
			
            $html = '<select name="'.$inputName.'" class="input-select attribute-value" onchange="_estimate_product_count();">';
            $html .= '<option value="">'.Mage::helper('adminhtml')->__('-- Please Select --').'</option>';

            foreach ($options as $option)
            {
				$selected = ((int)$option['value'] == (int)$optionId) ? 'selected ' : '' ;
                $html .= '<option '.$selected.'value="' . $option['value'] . '">' . $option['label'] . '</option>';
            }
			
            return $html . '</select>';
        }
		else
		{
			return '<input type="text" name="'.$inputName.'" onchange="_estimate_product_count();" class="input-text required-input attribute-value" value="'.$optionId.'"/>';
		}
	}
}
