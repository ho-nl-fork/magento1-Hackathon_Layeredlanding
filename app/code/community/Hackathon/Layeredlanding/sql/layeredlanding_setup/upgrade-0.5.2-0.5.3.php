<?php
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->getConnection()->addColumn($installer->getTable('layeredlanding'), 'list_mode', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'comment' => 'List Mode',
    'length' => 20,
    'unsigned' => true,
    'nullable' => true,
    'default' => null
));

$installer->endSetup();
