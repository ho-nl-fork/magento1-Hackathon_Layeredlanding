<?php
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->getConnection()->addColumn($installer->getTable('layeredlanding'), 'page_type', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'comment' => 'Page Type',
    'length' => 30,
    'nullable' => true,
    'default' => null
));

$installer->getConnection()->dropIndex($installer->getTable('layeredlanding'), 'uk_url_store');

$installer->getConnection()->addIndex(
    $installer->getTable('layeredlanding'),
    $installer->getIdxName($installer->getTable('layeredlanding'), array('page_url')),
    array('page_url')
);


$installer->getConnection()->addColumn($installer->getTable('layeredlanding'), 'search_query', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'comment' => 'Search Query',
    'length' => 255,
    'nullable' => true,
    'default' => null,
));

$installer->endSetup();
