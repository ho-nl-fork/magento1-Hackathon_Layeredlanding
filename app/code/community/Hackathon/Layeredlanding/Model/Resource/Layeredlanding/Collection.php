<?php
 
class Hackathon_Layeredlanding_Model_Resource_Layeredlanding_Collection
    extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('layeredlanding/layeredlanding');
    }
//
//    /**
//     * Perform operations after collection load
//     *
//     * @return Mage_Cms_Model_Resource_Page_Collection
//     */
//    protected function _afterLoad()
//    {
//        if ($this->_previewFlag) {
//            $items = $this->getColumnValues('page_id');
//            $connection = $this->getConnection();
//            if (count($items)) {
//                $select = $connection->select()
//                        ->from(array('cps'=>$this->getTable('cms/page_store')))
//                        ->where('cps.page_id IN (?)', $items);
//
//                if ($result = $connection->fetchPairs($select)) {
//                    foreach ($this as $item) {
//                        if (!isset($result[$item->getData('page_id')])) {
//                            continue;
//                        }
//                        if ($result[$item->getData('page_id')] == 0) {
//                            $stores = Mage::app()->getStores(false, true);
//                            $storeId = current($stores)->getId();
//                            $storeCode = key($stores);
//                        } else {
//                            $storeId = $result[$item->getData('page_id')];
//                            $storeCode = Mage::app()->getStore($storeId)->getCode();
//                        }
//                        $item->setData('_first_store_id', $storeId);
//                        $item->setData('store_code', $storeCode);
//                    }
//                }
//            }
//        }
//
//        return parent::_afterLoad();
//    }

    /**
     * Add filter by store
     *
     * @param int|Mage_Core_Model_Store $store
     * @param bool $withAdmin
     * @return Mage_Cms_Model_Resource_Page_Collection
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        if (!$this->getFlag('store_filter_added')) {
            if ($store instanceof Mage_Core_Model_Store) {
                $store = array($store->getId());
            }

            if (!is_array($store)) {
                $store = array($store);
            }

            if ($withAdmin) {
                $store[] = Mage_Core_Model_App::ADMIN_STORE_ID;
            }

            $this->addFilter('store_id', array('in' => $store), 'public');
        }
        return $this;
    }

    /**
     * Join store relation table if there is store filter
     */
    protected function _renderFiltersBefore()
    {
        if ($this->getFilter('store_id')) {
            $this->getSelect()->join(
                array('store_table' => $this->getTable('layeredlanding/store')),
                'main_table.layeredlanding_id = store_table.layeredlanding_id',
                array()
            )->group('main_table.layeredlanding_id');

            /*
             * Allow analytic functions usage because of one field grouping
             */
            $this->_useAnalyticFunction = true;
        }
        return parent::_renderFiltersBefore();
    }


    /**
     * Get SQL for get record count.
     * Extra GROUP BY strip added.
     *
     * @return Varien_Db_Select
     */
    public function getSelectCountSql()
    {
        $countSelect = parent::getSelectCountSql();

        $countSelect->reset(Zend_Db_Select::GROUP);

        return $countSelect;
    }
}
