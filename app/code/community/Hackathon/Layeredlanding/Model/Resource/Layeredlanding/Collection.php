<?php
 
class Hackathon_Layeredlanding_Model_Resource_Layeredlanding_Collection
    extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('layeredlanding/layeredlanding');
    }

    protected function _initSelect() {
        parent::_initSelect();
        $this->getSelect()->group('main_table.layeredlanding_id');
        return $this;
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
     * @return $this
     */
    public function addStoreFilter($store = null)
    {
        if (is_null($store)) {
            $store = Mage::app()->getStore();
        }

        if ($store instanceof Mage_Core_Model_Store) {
            $store = $store->getId();
        }


        $this->getSelect()->join(
            array('store_table' => $this->getTable('layeredlanding/store')),
            "`main_table`.`layeredlanding_id` = `store_table`.`layeredlanding_id` AND `store_table`.`store_id` = '$store'"
        );

        return $this;
    }


    /**
     * Add filter by category
     *
     * @param Mage_Catalog_Model_Category|int $category
     * @return $this
     */
    public function addCategoryFilter($category)
    {
        if ($category instanceof Mage_Catalog_Model_Category) {
            $category = $category->getId();
        }

        $this->getSelect()->join(
            array('category_table' => $this->getTable('layeredlanding/category')),
            "`main_table`.`layeredlanding_id` = `category_table`.`layeredlanding_id` AND `category_table`.`category_id` = $category",
            array()
        );

        return $this;
    }


    /**
     * Add filter by category
     *
     * @param $attribute
     * @param $value
     *
     * @internal param int|\Mage_Catalog_Model_Category $category
     * @return $this
     */
    public function addAttributeFilter($attribute, $value)
    {
        if ($attribute instanceof Mage_Eav_Model_attribute) {
            $attribute = $attribute->getId();
        }

        $tableAlias = 'attr_'.$attribute;
        $this->getSelect()->joinLeft(
            array($tableAlias => $this->getTable('layeredlanding/attributes')),
            "`main_table`.`layeredlanding_id` = `$tableAlias`.`layeredlanding_id` AND `$tableAlias`.`attribute_id` = $attribute",
            array()
        );

        if (! is_null($value)) {
            $this->getSelect()->where("`$tableAlias`.`value` = '$value'");
        } else {
            $this->getSelect()->where("`$tableAlias`.`value` IS NULL");
        }

        return $this;
    }

}
