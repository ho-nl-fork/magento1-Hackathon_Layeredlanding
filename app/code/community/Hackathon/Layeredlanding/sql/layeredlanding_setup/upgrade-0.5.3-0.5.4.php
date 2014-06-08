<?php
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->getConnection()->addColumn($installer->getTable('layeredlanding'), 'order', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'comment' => 'Order',
    'length' => 4,
    'unsigned' => true,
    'nullable' => true,
    'default' => null
));

$installer->endSetup();
