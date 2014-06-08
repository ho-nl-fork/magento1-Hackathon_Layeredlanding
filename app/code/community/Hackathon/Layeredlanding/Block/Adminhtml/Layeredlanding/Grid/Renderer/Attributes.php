<?php

class Hackathon_Layeredlanding_Block_Adminhtml_Layeredlanding_Grid_Renderer_Attributes
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /** @var Mage_Catalog_Model_Resource_Product_Attribute_Collection */
    protected $_layeredAttributes = null;

	public function render(Varien_Object $row)
	{
		$attributes = $row->getData($this->getColumn()->getIndex());
        $values = array();

        $layeredAttributes = $this->_getLayeredAttributes();

        foreach ($attributes as $attributeId => $valueId) {
            /** @var Mage_Catalog_Model_Resource_Eav_Attribute $attribute */
            $attribute = $layeredAttributes->getItemById($attributeId);
            $value = '';
            if ($attribute->usesSource()) {
                $value = $attribute->getSource()->getOptionText($valueId);
            }

            $values[] = sprintf('%s: %s', $attribute->getFrontendLabel(), $value);
        }
		
		return implode("<br />\n", $values);
	}


    /**
     * @return Mage_Catalog_Model_Resource_Product_Attribute_Collection
     */
    protected function _getLayeredAttributes() {
        if ($this->_layeredAttributes === null) {

            $this->_layeredAttributes = Mage::getResourceModel('catalog/product_attribute_collection');
            $this->_layeredAttributes->addIsFilterableFilter();
        }
        return $this->_layeredAttributes;
    }
}
