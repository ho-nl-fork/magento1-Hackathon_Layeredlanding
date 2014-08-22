<?php

/**
 * @method string getLimit()
 * @method Hackathon_Layeredlanding_Model_Layeredlanding setLimit(string $value)
 * @method string getPageUrl()
 * @method Hackathon_Layeredlanding_Model_Layeredlanding setPageUrl(string $value)
 * @method string getCustomLayoutUpdate()
 * @method Hackathon_Layeredlanding_Model_Layeredlanding setCustomLayoutUpdate(string $value)
 * @met hod int getLayeredlandingId()
 * @method Hackathon_Layeredlanding_Model_Layeredlanding setLayeredlandingId(int $value)
 * @method string getMetaKeywords()
 * @method Hackathon_Layeredlanding_Model_Layeredlanding setMetaKeywords(string $value)
 * @method string getSortBy()
 * @method Hackathon_Layeredlanding_Model_Layeredlanding setSortBy(string $value)
 * @method string getCustomLayoutTemplate()
 * @method Hackathon_Layeredlanding_Model_Layeredlanding setCustomLayoutTemplate(string $value)
 * @method int getDisplayInTopNavigation()
 * @method Hackathon_Layeredlanding_Model_Layeredlanding setDisplayInTopNavigation(int $value)
 * @method string getMetaDescription()
 * @method Hackathon_Layeredlanding_Model_Layeredlanding setMetaDescription(string $value)
 * @method string getOrder()
 * @method Hackathon_Layeredlanding_Model_Layeredlanding setOrder(string $value)
 * @method string getPageDescription()
 * @method Hackathon_Layeredlanding_Model_Layeredlanding setPageDescription(string $value)
 * @method string getMetaTitle()
 * @method Hackathon_Layeredlanding_Model_Layeredlanding setMetaTitle(string $value)
 * @method int getDisplayLayeredNavigation()
 * @method Hackathon_Layeredlanding_Model_Layeredlanding setDisplayLayeredNavigation(int $value)
 * @method string getListMode()
 * @method Hackathon_Layeredlanding_Model_Layeredlanding setListMode(string $value)
 * @method string getPageTitle()
 * @method Hackathon_Layeredlanding_Model_Layeredlanding setPageTitle(string $value)
 */
class Hackathon_Layeredlanding_Model_Layeredlanding extends Mage_Core_Model_Abstract
{
    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'layered_landing';


    public function _construct()
    {
        parent::_construct();
        $this->_init('layeredlanding/layeredlanding');
    }
	
	public function getAttributes()
	{
		return Mage::getModel('layeredlanding/attributes')->getCollection()
					->addFieldToFilter('layeredlanding_id', $this->getId());
	}

    protected function _beforeSave() {
        $this->_preparePageUrl();
        if ($url = $this->getPageUrl()) {

            $collection = Mage::getModel('core/url_rewrite')
                ->getCollection()
                ->addFieldToFilter('request_path', array('eq' => $url));

            if ($collection->getSize() > 0) {
                Mage::throwException(Mage::helper('layeredlanding')->__("URL-Key already used by product or category"));
            }

            $collection = Mage::getModel('cms/page')
                ->getCollection()
                ->addFieldToFilter('identifier', array('eq' => str_replace('.html', '', $url)));

            if ($collection->getSize() > 0) {
                Mage::throwException(Mage::helper('layeredlanding')->__("URL-key already used by CMS page"));
            }
        }

        return parent::_beforeSave();
    }

    protected function _preparePageUrl() {
        $url = $this->getPageUrl();
        if (($pos = strpos($url, '.')) !== false) {
            $suffix = substr($url, strpos($url, '.'));
            $url = substr($url, 0, $pos);
        } else {
            $suffix = '';
        };

        $urlParts = explode('/', $url);
        $category = Mage::getSingleton('catalog/category');
        foreach ($urlParts as $key => $part) {
            $urlParts[$key] = $category->formatUrlKey($part);
        }
        $url = implode('/', $urlParts).$suffix;

        $this->setPageUrl($url);
    }


    /**
     * @todo add check if
     * @return Mage_Core_Model_Abstract|void
     */
    protected function _afterSave() {
        parent::_afterSave();
        $cache = Mage::app()->getCache();
        $cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('TOPMENU'));
    }


    /**
     * @todo Loads a new model instance, should just add the data to the current model and be
     *       moved to the resource model.
     * @param $url
     * @return $this
     */
    public function loadByUrl($url)
    {
        /** @var Hackathon_Layeredlanding_Model_Resource_Layeredlanding_Collection $layeredlandingCollection */
        $layeredlandingCollection = $this->getCollection();
        $layeredlandingCollection->addFieldToFilter('page_url', $url);
        $layeredlandingCollection->addStoreFilter(Mage::app()->getStore());
        $layeredlandingCollection->setPageSize(1);

        $layeredlandingCollection->walk('afterload');

        return $layeredlandingCollection->getFirstItem();
    }

    public function getUrl()
    {
        return Mage::getUrl($this->getPageUrl());
    }
}
