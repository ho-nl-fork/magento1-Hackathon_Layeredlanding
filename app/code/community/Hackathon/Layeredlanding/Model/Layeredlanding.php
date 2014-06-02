<?php

/**
 * @method int getDisplayInTopNavigation()
 * @method Hackathon_Layeredlanding_Model_Layeredlanding setDisplayInTopNavigation(int $value)
 * @method string getCustomLayoutUpdate()
 * @method Hackathon_Layeredlanding_Model_Layeredlanding setCustomLayoutUpdate(string $value)
 * @method string getPageUrl()
 * @method Hackathon_Layeredlanding_Model_Layeredlanding setPageUrl(string $value)
 * @method string getMetaDescription()
 * @method Hackathon_Layeredlanding_Model_Layeredlanding setMetaDescription(string $value)
 * @method int getLayeredlandingId()
 * @method Hackathon_Layeredlanding_Model_Layeredlanding setLayeredlandingId(int $value)
 * @method string getMetaKeywords()
 * @method Hackathon_Layeredlanding_Model_Layeredlanding setMetaKeywords(string $value)
 * @method string getPageDescription()
 * @method Hackathon_Layeredlanding_Model_Layeredlanding setPageDescription(string $value)
 * @method string getCustomLayoutTemplate()
 * @method Hackathon_Layeredlanding_Model_Layeredlanding setCustomLayoutTemplate(string $value)
 * @method string getMetaTitle()
 * @method Hackathon_Layeredlanding_Model_Layeredlanding setMetaTitle(string $value)
 * @method int getDisplayLayeredNavigation()
 * @method Hackathon_Layeredlanding_Model_Layeredlanding setDisplayLayeredNavigation(int $value)
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
