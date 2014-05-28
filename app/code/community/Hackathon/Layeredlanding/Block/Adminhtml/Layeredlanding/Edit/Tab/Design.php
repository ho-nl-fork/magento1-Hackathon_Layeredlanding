<?php

class Hackathon_Layeredlanding_Block_Adminhtml_Layeredlanding_Edit_Tab_Design
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
		
        $fieldset = $form->addFieldset('layeredlanding_form', array(
			'legend' => Mage::helper('layeredlanding')->__('Landingpage Design'),
			'class' => 'fieldset-wide'
		));

        $fieldset->addField('display_layered_navigation', 'select', array(
			'label' => Mage::helper('layeredlanding')->__('Display Layered Navigation'),
			'class' => 'required-entry',
			'required' => true,
			'name' => 'display_layered_navigation',
			'values' => Mage::getSingleton('layeredlanding/options_boolean')->toOptionArray(),
		));

        $fieldset->addField('display_in_top_navigation', 'select', array(
			'label' => Mage::helper('layeredlanding')->__('Display in Top Navigation'),
			'class' => 'required-entry',
			'required' => true,
			'name' => 'display_in_top_navigation',
			'values' => Mage::getSingleton('layeredlanding/options_boolean')->toOptionArray(),
			'value' => '0',
		));

        $fieldset->addField('custom_layout_template', 'select', array(
			'label' => Mage::helper('layeredlanding')->__('Page Layout'),
			'required' => false,
			'name' => 'custom_layout_template',
			'values' => Mage::getSingleton('layeredlanding/options_layout')->toOptionArray(),
		));

        $fieldset->addField('custom_layout_update', 'textarea', array(
			'label' => Mage::helper('layeredlanding')->__('Custom Layout Update'),
			'required' => false,
			'name' => 'custom_layout_update',
			'style' => "width:500px;",
		));

        if (Mage::getSingleton('adminhtml/session')->getLayeredlandingData()) {
            $data = Mage::getSingleton('adminhtml/session')->getLayeredlandingData();
            Mage::getSingleton('adminhtml/session')->setLayeredlandingData(null);
        } elseif (Mage::registry('layeredlanding_data')) {
            $data = Mage::registry('layeredlanding_data')->getData();
        }

        $form->setValues($data);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper('layeredlanding')->__('Custom Design');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('layeredlanding')->__('Custom Design');
    }

    /**
     * Returns status flag about this tab can be shown or not
     *
     * @return true
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return true
     */
    public function isHidden()
    {
        return false;
    }
}