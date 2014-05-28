<?php
 
class Hackathon_Layeredlanding_Model_Resource_Attributes
    extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {   
        $this->_init('layeredlanding/attributes', 'layeredlanding_attributes_id');
    }
}
