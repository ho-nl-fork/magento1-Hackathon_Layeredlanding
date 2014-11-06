<?php


/**
 * @method string getShowAll()
 * @method string getTitle()
 * @method string getLandingpageIds()
 */
class Hackathon_Layeredlanding_Block_Widget_List
    extends Mage_Core_Block_Template
    implements Mage_Widget_Block_Interface
{

	public function getLandingpageCollection()
	{
        $collection = Mage::getModel('layeredlanding/layeredlanding')->getCollection()
            ->addStoreFilter(Mage::app()->getStore())
            ->addOrder('layeredlanding_id');

        if (! $this->getShowAll()) {
            $collection->addFieldToFilter(
                '`main_table`.`layeredlanding_id`',
                array('in' => explode(',', $this->getLandingpageIds()))
            );
        }

        return $collection;
	}
}
