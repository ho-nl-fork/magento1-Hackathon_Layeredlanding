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
		$category->setName($landingpage->getPageTitle());
		$category->setDescription($landingpage->getPageDescription());
        $category->setMetaTitle($landingpage->getMetaTitle());
        $category->setMetaDescription($landingpage->getMetaDescription());
        $category->setMetaKeywords($landingpage->getMetaKeywords());
        $category->setRequestPath($landingpage->getPageUrl());
	}


    /**
     * Add data to head block and add breadcrumbs
     * @param $observer
     */
    public function coreBlockAbstractPrepareLayoutAfter($observer)
    {
        /** @var $block Mage_Catalog_Block_Category_View */
        $block = $observer->getBlock();

        if ($block instanceof Mage_Catalog_Block_Breadcrumbs) {
            /** @var $landingpage Hackathon_Layeredlanding_Model_Layeredlanding */
            $landingpage = Mage::registry('current_landingpage');

            /** @var $breadcrumbsBlock Mage_Page_Block_Html_Breadcrumbs */
            if (($breadcrumbsBlock = $block->getLayout()->getBlock('breadcrumbs')) && $landingpage) {
                $breadcrumbsBlock->addCrumb($landingpage->getPageTitle(), array(
                    'label' => $landingpage->getPageTitle()
                ));
            }

        }
    }


    /**
     * @todo move to model
     * @param $observer
     * @throws Exception
     */
    public function layeredLandingSaveBefore($observer)
    {
        /** @var $obj Hackathon_Layeredlanding_Model_Layeredlanding */
        $obj = $observer->getDataObject();

        if ($url = $obj->getPageUrl()) {
            $collection = Mage::getModel('core/url_rewrite')
                ->getCollection()
                ->addFieldToFilter('request_path', array('eq' => $url));

            if ($collection->getSize() > 0) {
                throw new Exception("Url already used by product or category");
            }

            $collection = Mage::getModel('cms/page')
                ->getCollection()
                ->addFieldToFilter('identifier', array('eq' => str_replace('.html', '', $url)));

            if ($collection->getSize() > 0) {
                throw new Exception("Url already used by CMS page");
            }
        }
    }

    public function layeredLandingSaveAfter($observer)
    {
        $cache = Mage::app()->getCache();
        $cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('TOPMENU'));
    }

    public function pageBlockHtmlTopmenuGethtmlBefore($observer)
    {
        /** @var $menu Varien_Data_Tree_Node */
        $menu = $observer->getMenu();

        $collection = Mage::getModel('layeredlanding/layeredlanding')
            ->getCollection()
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
    protected function _getLandingpage() {
        $landingPage = Mage::registry('current_landingpage');

        if (! is_null($landingPage)) {
            return $landingPage;
        }

        $request = Mage::app()->getRequest();

        $categoryId = $request->getParam('id');
        if (! $categoryId) {
            return null;
        }

        //Build query to select the correct layeredlanding collection for the current category
        $layeredlandingCollection = Mage::getResourceModel('layeredlanding/layeredlanding_collection');
        $layeredlandingCollection->addStoreFilter();
        $layeredlandingCollection->addCategoryFilter($categoryId);
        $layeredlandingCollection->setPageSize(1);

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

            /** @var Hackathon_Layeredlanding_Model_Layeredlanding $layeredlanding */
            $layeredlanding = $layeredlandingCollection->getFirstItem();
            Mage::register('current_landingpage', $layeredlanding);
        } else {
            Mage::register('current_landingpage', false);
        }

        return Mage::registry('current_landingpage');
    }
}
