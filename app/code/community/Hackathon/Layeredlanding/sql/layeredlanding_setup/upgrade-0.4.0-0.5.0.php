<?php
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

/**
 * Create table 'layeredlanding/category'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('layeredlanding/category'))
    ->addColumn('layeredlanding_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        'primary'   => true,
        ), 'Layered Landing ID')
    ->addColumn('category_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        'primary'   => true,
        ), 'Category ID')
    ->addIndex($installer->getIdxName('layeredlanding/category', array('category_id')),
        array('category_id'))
    ->addForeignKey(
        $installer->getFkName('layeredlanding/category', 'layeredlanding_id', 'layeredlanding/layeredlanding', 'layeredlanding_id'),
        'layeredlanding_id', $installer->getTable('layeredlanding/layeredlanding'), 'layeredlanding_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey(
        $installer->getFkName('layeredlanding/category', 'category_id', 'catalog/category', 'entity_id'),
        'category_id', $installer->getTable('catalog/category'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)

    ->setComment('Category to Layered Landing Linkage Table');
$installer->getConnection()->createTable($table);

/**
 * Create table 'layeredlanding/store'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('layeredlanding/store'))
    ->addColumn('layeredlanding_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        'primary'   => true,
        ), 'Layered Landing ID')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        'primary'   => true,
        ), 'Store ID')
    ->addIndex($installer->getIdxName('layeredlanding/store', array('store_id')),
        array('store_id'))
    ->addForeignKey(
        $installer->getFkName('layeredlanding/store', 'layeredlanding_id', 'layeredlanding/layeredlanding', 'layeredlanding_id'),
        'layeredlanding_id', $installer->getTable('layeredlanding/layeredlanding'), 'layeredlanding_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey(
        $installer->getFkName('layeredlanding/store', 'store_id', 'core/store', 'store_id'),
        'store_id', $installer->getTable('core/store'), 'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Store to Layered Landing Linkage Table');
$installer->getConnection()->createTable($table);


/**
 * Data Migration
 */
$layeredlandingCollection = Mage::getResourceModel('layeredlanding/layeredlanding_collection');
$allStoreIds = Mage::getResourceModel('core/store_collection')->getAllIds();
$storeData = array();
$categoryData = array();
foreach ($layeredlandingCollection as $layeredlanding) {
    /** @var Hackathon_Layeredlanding_Model_Layeredlanding $layeredlanding */
    $layeredlandingId = $layeredlanding->getId();

    $storeIds = $layeredlanding->getStoreIds() ?: $allStoreIds;
    if ($storeIds) {
        $storeIdArray = is_array($storeIds) ? $storeIds : explode(',', $storeIds);
        foreach ($storeIdArray as $storeId) {
            $storeData[] = array('layeredlanding_id' => (int) $layeredlandingId, 'store_id' => (int) $storeId);
        }
    }

    $categoryIds = $layeredlanding->getCategoryIds();
    if ($categoryIds) {
        $categoryIdArray = explode(',', $categoryIds);
        foreach ($categoryIdArray as $categoryId) {
            $categoryData[] = array('layeredlanding_id' => (int) $layeredlandingId, 'category_id' => (int) $categoryId);
        }
    }
}

$installer->getConnection()->insertMultiple($installer->getTable('layeredlanding/store'), $storeData);
$installer->getConnection()->insertMultiple($installer->getTable('layeredlanding/category'), $categoryData);

/**
 * Drop Columns
 */
$installer->getConnection()->dropColumn($installer->getTable('layeredlanding/layeredlanding'), 'store_ids');
$installer->getConnection()->dropColumn($installer->getTable('layeredlanding/layeredlanding'), 'category_ids');

$installer->endSetup();
