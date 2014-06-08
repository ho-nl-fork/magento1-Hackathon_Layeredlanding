<?php
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->getConnection()->addColumn($installer->getTable('layeredlanding'), 'sort_by', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'comment' => 'Sort By',
    'length' => Mage_Eav_Model_Entity_Attribute::ATTRIBUTE_CODE_MAX_LENGTH,
    'unsigned' => true,
    'nullable' => true,
    'default' => null
));

$installer->getConnection()->addColumn($installer->getTable('layeredlanding'), 'limit', array(
    'type' => Varien_Db_Ddl_Table::TYPE_SMALLINT,
    'comment' => 'Limit',
    'unsigned' => true,
    'nullable' => true,
    'default' => null
));

$installer->endSetup();
