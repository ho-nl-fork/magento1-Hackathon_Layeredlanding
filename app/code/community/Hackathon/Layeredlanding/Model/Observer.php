<?php

class Hackathon_Layeredlanding_Model_Observer extends Mage_Core_Model_Abstract
{
    public function addLayeredRouter($observer)
    {
        $front = $observer->getEvent()->getFront();

        $router = new Hackathon_Layeredlanding_Controller_Router();
        $front->addRouter('hackathon_layeredlanding', $router);
    }
	
	public function setCategoryData($observer)
	{
        /** @var Hackathon_Layeredlanding_Model_Layeredlanding $landingpage */
		$landingpage = $this->_getLandingpage();

		if (! $landingpage) {
            return $this;
        }

        /** @var Mage_Catalog_Model_Category $category */
		$category = $observer->getCategory();
//        $category->setIsReadonly(true);

        $category->setOrigName($category->getName());
		$category->setName($landingpage->getPageTitle());
		$category->setDescription($landingpage->getPageDescription());
        $category->setMetaTitle($landingpage->getMetaTitle());
        $category->setMetaDescription($landingpage->getMetaDescription());
        $category->setMetaKeywords($landingpage->getMetaKeywords());
        $category->setLandingPage($landingpage);
	}


    /**
     * Add data to head block and add breadcrumbs
     * @param $observer
     */
    public function controllerActionLayoutGenerateBlocksAfter($observer)
    {
        /* @var $layout Mage_Core_Model_Layout */
        $layout = $observer->getEvent()->getData('layout');

        if ($breadcrumbsBlock = $layout->getBlock('breadcrumbs')) {
            /** @var $breadcrumbsBlock Mage_Page_Block_Html_Breadcrumbs */

            if (! $breadcrumbsBlock) {
                return $this;
            }

            $landingpage = $this->_getLandingpage();
            if (! $landingpage) {
                return $this;
            }

            /** @var Mage_Catalog_Model_Category $currentCategory */
            $currentCategory = Mage::registry('current_category');

            //rewrite the category crumb, so it has a url.
            $breadcrumbsBlock->addCrumb('category'.$currentCategory->getId(), array(
                'label' => $currentCategory->getOrigName(),
                'link'  => $currentCategory->getUrl()
            ));

            $breadcrumbsBlock->addCrumb($landingpage->getPageTitle(), array(
                'label' => $landingpage->getPageTitle()
            ));
        }

        if ($head = $layout->getBlock('head')) {
            /** @var $head Mage_Page_Block_Html_Head */

            $landingpage = $this->_getLandingpage();
            if (! $landingpage) {
                return $this;
            }

            $this->_removeHeadItemsByType($head, 'link_rel', 'rel="canonical"');
            $head->addLinkRel('canonical', $landingpage->getUrl());
        }
    }


    /**
     * @param Mage_Page_Block_Html_Head $head
     * @param string $type
     * @param bool|string $params
     */
    protected function _removeHeadItemsByType($head, $type, $params = false) {
        $data = $head->getData();
        $data = isset($data['items']) ? $data['items'] : array();
        foreach (array_keys($data) as $key) {
            if ((strpos($key, $type.'/') === 0)) {
                if ($params) {
                    if (isset($data[$key]['params'])) {
                        if ($params == $data[$key]['params']) {
                            unset($data[$key]);
                        }
                    }
                }
                else {
                    unset($data[$key]);
                }
            }
        }
        $head->setData('items', $data);
    }


    public function pageBlockHtmlTopmenuGethtmlBefore($observer)
    {
        /** @var $menu Varien_Data_Tree_Node */
        $menu = $observer->getMenu();

        $collection = Mage::getModel('layeredlanding/layeredlanding')->getCollection()
            ->addFieldToFilter('display_in_top_navigation', 1)
            ->addStoreFilter();

        $hasActiveEntry = false;

        /** @var $landingpage Hackathon_Layeredlanding_Model_Layeredlanding */
        foreach ($collection as $landingpage)
        {
            $isActive = Mage::app()->getRequest()->getAlias(Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS) == $landingpage->getPageUrl();

            $newNodeData = array(
                'id' => 'layered-landing-'.$landingpage->getId(),
                'name' => $landingpage->getPageTitle(),
                'url' => $landingpage->getUrl(),
                'is_active' => $isActive,
                'is_landingpage' => true
            );

            $newNode = new Varien_Data_Tree_Node($newNodeData, 'id', new Varien_Data_Tree);
            $menu->addChild($newNode);

            $hasActiveEntry = $hasActiveEntry || $isActive;
        }

        if ($hasActiveEntry) {
            foreach ($menu->getChildren() as $child) {
                if (!$child->getIsLandingpage()) {
                    $child->setIsActive(false);
                }
            }
        }
    }


    /**
     * @todo what does this do?
     * @param $observer
     */
    public function catalogControllerCategoryInitAfter($observer)
    {
        $landingpage = $this->_getLandingpage();
        if (! $landingpage) {
            return;
        }

        Mage::unregister('current_entity_key');
        Mage::register('current_entity_key', 'landingpage-'.$landingpage->getId());
    }


    /**
     * @return Hackathon_Layeredlanding_Model_Layeredlanding|null
     */
    protected function _getLandingpage()
    {
        $landingPage = Mage::registry('current_landingpage');

        if (! is_null($landingPage)) {
            return $landingPage;
        }

        $categoryId = Mage::app()->getRequest()->getParam('id');
        if (! $categoryId) {
            return null;
        }

        $layeredlandingCollection = $this->_loadLandingPage();
        Mage::register('current_landingpage', $layeredlandingCollection);

        return Mage::registry('current_landingpage');
    }


    protected function _loadLandingPage() {
        $request = Mage::app()->getRequest();

        /** @var Hackathon_Layeredlanding_Model_Resource_Layeredlanding_Collection $layeredlandingCollection */
        $layeredlandingCollection = Mage::getResourceModel('layeredlanding/layeredlanding_collection');
        $layeredlandingCollection
            ->addDisplayLayeredNavigationFilter()
            ->addStoreFilter()
            ->addCategoryFilter($request->getParam('id'))
            ->setPageSize(1);

        /** @var Mage_Catalog_Block_Product_List_Toolbar $toolbar */
        $toolbar = Mage::app()->getLayout()->createBlock('catalog/product_list_toolbar');

        $layeredlandingCollection
            ->addSortByFilter($toolbar->getCurrentOrder())
            ->addLimitFilter($toolbar->getLimit())
            ->addListModeFilter($toolbar->getCurrentMode())
            ->addOrderFilter($toolbar->getCurrentDirection());

        //@todo do we have this data cached somewhere, move somewhere?
        $layeredAttributes = Mage::getResourceModel('catalog/product_attribute_collection');
        $layeredAttributes->addIsFilterableFilter();
        $select  = $layeredAttributes->getSelect()->reset('columns')->columns(array('attribute_id', 'attribute_code'));
        $attributes = $layeredAttributes->getConnection()->fetchPairs($select);

        foreach ($attributes as $attributeId => $attributeCode) {
            //we also filter the NULL values, so that it filters strict enough
            $value = $request->getParam($attributeCode, null);
            $layeredlandingCollection->addAttributeFilter($attributeId, $value);
        }

        if ($layeredlandingCollection->count()) {
            $layeredlandingCollection->walk('afterload');

            return $layeredlandingCollection->getFirstItem();
        } else {
            return false;
        }
    }
}
