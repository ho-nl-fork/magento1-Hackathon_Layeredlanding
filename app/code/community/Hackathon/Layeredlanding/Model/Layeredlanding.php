<?php
 
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
        return Mage::getUrl().$this->getPageUrl();
    }
}
