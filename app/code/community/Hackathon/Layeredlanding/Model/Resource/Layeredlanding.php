<?php
 
class Hackathon_Layeredlanding_Model_Resource_Layeredlanding
    extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {   
        $this->_init('layeredlanding/layeredlanding', 'layeredlanding_id');
    }

    /**
     * Assign page to store views
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Mage_Cms_Model_Resource_Page
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $this->_saveStoreIds($object);
        $this->_saveCategoryIds($object);

        return parent::_afterSave($object);
    }

    protected function _saveStoreIds(Mage_Core_Model_Abstract $object) {
        $oldStores = $this->lookupStoreIds($object->getId());
        $newStores = (array)$object->getStoreId();

        if (empty($newStores) || reset($newStores) == 0) {
            $newStores = array_keys(Mage::app()->getStores());
        }

        $table  = $this->getTable('layeredlanding/store');
        $insert = array_diff($newStores, $oldStores);
        $delete = array_diff($oldStores, $newStores);

        if ($delete) {
            $where = array(
                'layeredlanding_id = ?'     => (int) $object->getId(),
                'store_id IN (?)' => $delete
            );

            $this->_getWriteAdapter()->delete($table, $where);
        }

        if ($insert) {
            $data = array();

            foreach ($insert as $storeId) {
                $data[] = array(
                    'layeredlanding_id'  => (int) $object->getId(),
                    'store_id' => (int) $storeId
                );
            }

            $this->_getWriteAdapter()->insertMultiple($table, $data);
        }

        return $this;
    }

    protected function _saveCategoryIds(Mage_Core_Model_Abstract $object) {
        $oldCategories = $this->lookupCategoryIds($object->getId());
        $newCategories = (array) $object->getCategoryId();

        if (empty($newCategories)) {
            $newStores = (array) $oldCategories;
        }

        $table  = $this->getTable('layeredlanding/category');
        $insert = array_diff($newCategories, $oldCategories);
        $delete = array_diff($oldCategories, $newCategories);

        if ($delete) {
            $where = array(
                'layeredlanding_id = ?'     => (int) $object->getId(),
                'category_id IN (?)' => $delete
            );

            $this->_getWriteAdapter()->delete($table, $where);
        }

        if ($insert) {
            $data = array();

            foreach ($insert as $storeId) {
                $data[] = array(
                    'layeredlanding_id'  => (int) $object->getId(),
                    'category_id' => (int) $storeId
                );
            }

            $this->_getWriteAdapter()->insertMultiple($table, $data);
        }

        return $this;
    }


    /**
     * Perform operations after object load
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Mage_Cms_Model_Resource_Page
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        if ($object->getId()) {
            $stores = $this->lookupStoreIds($object->getId());
            $object->setData('store_id', $stores);

            $categories = $this->lookupCategoryIds($object->getId());
            $object->setData('category_id', $categories);
        }

        return parent::_afterLoad($object);
    }


    /**
     * Get store ids to which specified item is assigned
     *
     * @param $layeredlandingId
     * @return array
     */
    public function lookupStoreIds($layeredlandingId)
    {
        $adapter = $this->_getReadAdapter();

        $select  = $adapter->select()
            ->from($this->getTable('layeredlanding/store'), 'store_id')
            ->where('layeredlanding_id = ?',(int)$layeredlandingId);

        return $adapter->fetchCol($select);
    }

    /**
     * Get store ids to which specified item is assigned
     *
     * @param $layeredlandingId
     * @return array
     */
    public function lookupCategoryIds($layeredlandingId)
    {
        $adapter = $this->_getReadAdapter();

        $select  = $adapter->select()
            ->from($this->getTable('layeredlanding/category'), 'category_id')
            ->where('layeredlanding_id = ?',(int)$layeredlandingId);

        return $adapter->fetchCol($select);
    }
}
