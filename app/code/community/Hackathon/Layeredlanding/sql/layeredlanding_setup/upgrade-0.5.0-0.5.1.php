<?php
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->getConnection()->addIndex(
    $installer->getTable('layeredlanding/attributes'),
    $installer->getIdxName('layeredlanding/attributes', array('layeredlanding_id', 'attribute_id', 'value')),
    array('layeredlanding_id', 'attribute_id', 'value')
);
$installer->getConnection()->addForeignKey(
    $installer->getFkName('layeredlanding/attributes', 'layeredlanding_id', 'layeredlanding/layeredlanding', 'layeredlanding_id'),
    $installer->getTable('layeredlanding/attributes'),
    'layeredlanding_id', $installer->getTable('layeredlanding/layeredlanding'), 'layeredlanding_id',
    Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE
);
$installer->getConnection()->changeColumn(
    $installer->getTable('layeredlanding/attributes'),
    'attribute_id', 'attribute_id',
    'SMALLINT(5) UNSIGNED'
);
$installer->getConnection()->addForeignKey(
    $installer->getFkName('layeredlanding/attributes', 'attribute_id', 'eav/attribute', 'attribute_id'),
    $installer->getTable('layeredlanding/attributes'),
    'attribute_id', $installer->getTable('eav/attribute'), 'attribute_id',
    Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE
);

$installer->endSetup();
