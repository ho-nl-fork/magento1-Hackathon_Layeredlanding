<?php
 
class Hackathon_Layeredlanding_Model_Resource_Layeredlanding_Collection
    extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('layeredlanding/layeredlanding');
    }


    /**
     * @return $this
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        $this->getSelect()->group('main_table.layeredlanding_id');
        return $this;
    }

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
     * @return $this
     */
    public function addDisplayLayeredNavigationFilter() {
        $this->addFieldToFilter('display_layered_navigation', array('eq' => 1));
        return $this;
    }

    /**
     * @return $this
     */
    public function addDisplayInTopNavigationFilter() {
        $this->addFieldToFilter('display_in_top_navigation', array('eq' => 1));
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

    /**
     * @param $sortBy
     * @return $this
     */
    public function addSortByFilter($sortBy)
    {
        if (is_null($sortBy)) {
            $this->addFieldToFilter('`main_table`.`sort_by`', array('null' => true));
        } else {
            $this->addFieldToFilter('`main_table`.`sort_by`', array(array('null' => true), array('eq' => $sortBy)));
        }
        return $this;
    }


    /**
     * @param $limit
     * @return $this
     */
    public function addLimitFilter($limit)
    {
        if (is_null($limit)) {
            $this->addFieldToFilter('`main_table`.`limit`', array('null' => true));
        } else {
            $this->addFieldToFilter('`main_table`.`limit`', array(array('null' => true), array('eq' => $limit)));
        }
        return $this;
    }


    /**
     * @param $mode
     * @return $this
     */
    public function addListModeFilter($mode)
    {
        if (is_null($mode)) {
            $this->addFieldToFilter('`main_table`.`list_mode`', array('null' => true));
        } else {
            $this->addFieldToFilter('`main_table`.`list_mode`', array(array('null' => true), array('eq' => $mode)));
        }
        return $this;
    }


    /**
     * @param $order
     * @return $this
     */
    public function addOrderFilter($order)
    {
        if (is_null($order)) {
            $this->addFieldToFilter('`main_table`.`order`', array('null' => true));
        } else {
            $this->addFieldToFilter('`main_table`.`order`', array(array('null' => true), array('eq' => $order)));
        }
        return $this;
    }

}
