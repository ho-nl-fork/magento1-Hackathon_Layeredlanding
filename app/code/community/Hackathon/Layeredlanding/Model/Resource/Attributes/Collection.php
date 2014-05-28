<?php
 
class Hackathon_Layeredlanding_Model_Resource_Attributes_Collection
    extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct()
    {
        //parent::__construct();
        $this->_init('layeredlanding/attributes');
    }
}
