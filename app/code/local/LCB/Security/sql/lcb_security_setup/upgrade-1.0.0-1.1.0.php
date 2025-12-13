<?php

$installer = $this;
$installer->startSetup();

$connection = $installer->getConnection();
$tableName = $installer->getTable('lcb_security/request_post');

$connection->addColumn(
    $tableName,
    'customer_id',
    array(
        'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'nullable' => true,
        'unsigned' => true,
        'comment' => 'Customer Id',
        'after' => 'entity_id',
    )
);

$connection->addColumn(
    $tableName,
    'turnstile',
    array(
        'type' => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'length' => 1,
        'nullable' => true,
        'unsigned' => true,
        'comment' => 'Has Tunstile',
        'after' => 'count',
    )
);

$connection->addColumn(
    $tableName,
    'recaptcha',
    array(
        'type' => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'length' => 1,
        'nullable' => true,
        'unsigned' => true,
        'comment' => 'Has Recaptcha',
        'after' => 'count',
    )
);

$installer->endSetup();
