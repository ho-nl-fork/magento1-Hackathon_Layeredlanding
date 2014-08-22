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
		));

        $pageType = $fieldset->addField('page_type', 'select', array(
            'label' => Mage::helper('layeredlanding')->__('Page Type'),
            'values' => Mage::getSingleton('layeredlanding/options_pagetype')->toOptionArray(),
            'name' => 'page_type',
            'value' => Hackathon_Layeredlanding_Model_Options_Pagetype::TYPE_CATEGORY
        ));

        $keyword = $fieldset->addField('search_query', 'text', array(
            'label' => Mage::helper('layeredlanding')->__('Search Query'),
            'name' => 'search_query',
        ));

        $sortBy = $fieldset->addField('sort_by', 'select', array(
			'label' => Mage::helper('catalog')->__('Sort By'),
            'values' => Mage::getSingleton('layeredlanding/options_sortby')->toOptionArray(),
			'name' => 'sort_by',
		));

        $order = $fieldset->addField('order', 'select', array(
			'label' => Mage::helper('catalog')->__('Order'),
            'values' => Mage::getSingleton('layeredlanding/options_order')->toOptionArray(),
			'name' => 'order',
		));

        $listMode = $fieldset->addField('list_mode', 'select', array(
			'label' => Mage::helper('catalog')->__('List Mode'),
            'values' => Mage::getSingleton('layeredlanding/options_listMode')->toOptionArray(),
			'name' => 'list_mode',
		));

        $limitGrid = $fieldset->addField('limit_grid', 'select', array(
			'label' => Mage::helper('catalog')->__('Limit'),
            'values' => Mage::getSingleton('layeredlanding/options_limit')->toOptionArray('grid'),
			'name' => 'limit_grid',
		));

        $limitList = $fieldset->addField('limit_list', 'select', array(
			'label' => Mage::helper('catalog')->__('Limit'),
            'values' => Mage::getSingleton('layeredlanding/options_limit')->toOptionArray('list'),
			'name' => 'limit_list',
		));

        /** @var Mage_Adminhtml_Block_Widget_Form_Element_Dependence $formElementDependence */
        $formElementDependence = $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence');
        $this->setChild('form_after', $formElementDependence);
        $formElementDependence
            ->addFieldMap($listMode->getHtmlId(), $listMode->getName())
            ->addFieldMap($limitGrid->getHtmlId(), $limitGrid->getName())
            ->addFieldMap($limitList->getHtmlId(), $limitList->getName())
            ->addFieldMap($sortBy->getHtmlId(), $sortBy->getName())
            ->addFieldMap($order->getHtmlId(), $order->getName())
            ->addFieldMap($pageType->getHtmlId(), $pageType->getName())
            ->addFieldMap($keyword->getHtmlId(), $keyword->getName())
            ->addFieldDependence(
                $limitGrid->getName(),
                $listMode->getName(),
                'grid'
            )
            ->addFieldDependence(
                $limitList->getName(),
                $listMode->getName(),
                'list'
            )
            ->addFieldDependence(
                $keyword->getName(),
                $pageType->getName(),
                Hackathon_Layeredlanding_Model_Options_Pagetype::TYPE_SEARCH
            )
            ->addFieldDependence(
                $order->getName(),
                $sortBy->getName(),
                Mage::getSingleton('layeredlanding/options_sortby')->getOptionValues()
            );


        /** @var Hackathon_Layeredlanding_Block_Adminhtml_Layeredlanding_Edit_Renderer_Categories $categoriesRenderer */
        $categoriesRenderer = $this->getLayout()->createBlock('layeredlanding/adminhtml_layeredlanding_edit_renderer_categories');

        $categoryField = $fieldset->addField('category_id', 'text', array(
			'label' => Mage::helper('layeredlanding')->__('Categories'),
			'class' => 'required-entry',
			'required' => true,
			'name' => 'category_id',
			'onchange' => '_estimate_product_count();',
		));

        $jsObject = $categoriesRenderer->getJsFormObject();
        $script = <<<JS
<script>
$jsObject = {};
var categoryInput = $('category_id')
categoryInput.setAttribute('type','hidden');;
$jsObject.updateElement = categoryInput;
</script>
JS;

        if (isset($data['category_id'])) {
            $categoriesRenderer->setCategoryIds($data['category_id']);
            $data['category_id'] = implode(',', $data['category_id']);
        }
        if (isset($data['layeredlanding_id'])) {
            $categoriesRenderer->setLayeredlandingId($data['layeredlanding_id']);
        }

        $categoryField->setData('after_element_html',
            $script . $categoriesRenderer->toHtml());

        /**
         * Check is single store mode
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $field = $fieldset->addField('store_id', 'multiselect', array(
				'name' => 'store_id',
				'label' => Mage::helper('cms')->__('Store View'),
				'title' => Mage::helper('cms')->__('Store View'),
				'required' => true,
				'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
				'value' => isset($data['store_id']) ? $data['store_id'] : array() ,
				'onchange' => '_estimate_product_count();',
			));
			
            $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
            $field->setRenderer($renderer);
        } else {
            $fieldset->addField('store_id', 'hidden', array(
				'name' => 'store_id',
				'value' => Mage::app()->getStore()->getId(),
				'onchange' => '_estimate_product_count();',
			));
        }
        unset($data['store_id']);

        $fieldset->addField('attributes', 'text', array(
			'name' => 'attributes',
			'label' => Mage::helper('layeredlanding')->__('Attributes'),
			'required' => false,
		))->setRenderer(
            $this->getLayout()->createBlock('layeredlanding/adminhtml_layeredlanding_edit_renderer_attributes')
        );

        if (isset($data['list_mode'])) {
            $data['limit_'.$data['list_mode']] = $data['limit'];
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