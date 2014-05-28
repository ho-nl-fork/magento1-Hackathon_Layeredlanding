<?php

class Hackathon_Layeredlanding_Block_Adminhtml_Layeredlanding_Edit_Tab_Conditions
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        if (Mage::getSingleton('adminhtml/session')->getLayeredlandingData()) {
            $data = Mage::getSingleton('adminhtml/session')->getLayeredlandingData();
            Mage::getSingleton('adminhtml/session')->setLayeredlandingData(null);
        } elseif (Mage::registry('layeredlanding_data')) {
            $data = Mage::registry('layeredlanding_data')->getData();
        }
		
        $fieldset = $form->addFieldset('layeredlanding_form', array(
			'legend' => Mage::helper('layeredlanding')->__('Landing Page Conditions'),
			'class' => 'fieldset-wide'
		));

        /** @var Hackathon_Layeredlanding_Block_Adminhtml_Layeredlanding_Edit_Renderer_Categories $categoriesRenderer */
        $categoriesRenderer = $this->getLayout()->createBlock('layeredlanding/adminhtml_layeredlanding_edit_renderer_categories');

        $categoryField = $fieldset->addField('category_ids', 'text', array(
			'label' => Mage::helper('layeredlanding')->__('Categories'),
			'class' => 'required-entry',
			'required' => true,
			'name' => 'category_ids',
			'onchange' => '_estimate_product_count();',
		));

        $jsObject = $categoriesRenderer->getJsFormObject();
        $script = <<<JS
<script>
$jsObject = {};
var categoryInput = $('category_ids');
$jsObject.updateElement = categoryInput;
</script>
JS;

        if (isset($data['category_ids'])) {
            $categoriesRenderer->setCategoryIds(explode(',', $data['category_ids']));
        }

        $categoryField->setData('after_element_html',
            $script . $categoriesRenderer->toHtml());

        /**
         * Check is single store mode
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $field = $fieldset->addField('store_ids', 'multiselect', array(
				'name' => 'store_ids',
				'label' => Mage::helper('cms')->__('Store View'),
				'title' => Mage::helper('cms')->__('Store View'),
				'required' => true,
				'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
				'onchange' => '_estimate_product_count();',
			));
			
            $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
            $field->setRenderer($renderer);
        } else {
            $fieldset->addField('store_ids', 'hidden', array(
				'name' => 'store_ids',
				'value' => Mage::app()->getStore(true)->getId(),
				'onchange' => '_estimate_product_count();',
			));
        }

        $fieldset->addField('attributes', 'text', array(
			'name' => 'attributes',
			'label' => Mage::helper('layeredlanding')->__('Attributes'),
			'required' => false,
		))->setRenderer(
            $this->getLayout()->createBlock('layeredlanding/adminhtml_layeredlanding_edit_renderer_attributes')
        );

        if (isset($data['store_ids'])) {
            $data['store_ids'] = explode(',', $data['store_ids']);
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
        return Mage::helper('layeredlanding')->__('Conditions');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('layeredlanding')->__('Conditions');
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