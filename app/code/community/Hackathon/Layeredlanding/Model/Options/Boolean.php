<?php



/**
 * @deprecated, can be replaced by a normal yesno
 * Class Hackathon_Layeredlanding_Model_Options_Boolean
 */
class Hackathon_Layeredlanding_Model_Options_Boolean extends Mage_Page_Model_Source_Layout
{

    public function toOptionArray($withEmpty = false)
    {
        $options = array(
			array('value'=>'1', 'label'=>Mage::helper('layeredlanding')->__('Yes')),
			array('value'=>'0', 'label'=>Mage::helper('layeredlanding')->__('No'))
		);

        return $options;
    }
}
