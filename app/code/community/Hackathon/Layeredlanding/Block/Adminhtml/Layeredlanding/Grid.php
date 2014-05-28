<?php
 
class Hackathon_Layeredlanding_Block_Adminhtml_Layeredlanding_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('layeredlandingGrid');
        // This is the primary key of the database
        $this->setDefaultSort('layeredlanding_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }
 
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('layeredlanding/layeredlanding')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
 
    protected function _prepareColumns()
    {
        $this->addColumn('layeredlanding_id', array(
            'header'    => Mage::helper('layeredlanding')->__('ID'),
            'align'     => 'right',
            'width'     => '50px',
            'index'     => 'layeredlanding_id',
        ));
 
        $this->addColumn('title', array(
            'header'    => Mage::helper('layeredlanding')->__('Title'),
            'align'     => 'left',
            'index'     => 'page_title',
        ));

        $this->addColumn('category_id', array(
            'header'    => Mage::helper('layeredlanding')->__('Categories'),
            'align'     => 'left',
            'index'     => 'category_id',
            'width'     => '200px',
            'filter'    => false,
            'sortable'  => false,
			'renderer'  => 'layeredlanding/adminhtml_layeredlanding_grid_renderer_category',
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'        => Mage::helper('cms')->__('Store View'),
                'index'         => 'store_id',
                'type'          => 'store',
                'store_all'     => true,
                'store_view'    => true,
                'sortable'      => false,
                'filter_condition_callback'
                                => array($this, '_filterStoreCondition'),
            ));
        }
 
        return parent::_prepareColumns();
    }

    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }

        $this->getCollection()->addStoreFilter($value);
    }
 
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
 
    public function getGridUrl()
    {
      return $this->getUrl('*/*/grid', array('_current'=>true));
    }
 
 
}