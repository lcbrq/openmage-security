<?php

/** @var Mage_Core_Model_Resource_Setup $installer */
$installer = $this;

$installer->startSetup();

$connection = $installer->getConnection();

$requestPostTable = $installer->getTable('lcb_security/request_post');

if (!$connection->isTableExists($requestPostTable)) {
    $requestPostTableDefinition = $connection
        ->newTable($requestPostTable)
        ->addColumn(
            'entity_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary'  => true,
            ),
            'ID'
        )
        ->addColumn(
            'ip',
            Varien_Db_Ddl_Table::TYPE_VARCHAR,
            45,
            array(
                'nullable' => false,
            ),
            'IP'
        )
        ->addColumn(
            'path',
            Varien_Db_Ddl_Table::TYPE_TEXT,
            255,
            array(
                'nullable' => false,
            ),
            'Path'
        )
        ->addColumn(
            'count',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'nullable' => false,
                'default'  => 0,
            ),
            'Count'
        )
        ->addColumn(
            'updated_at',
            Varien_Db_Ddl_Table::TYPE_DATETIME,
            null,
            array(
                'nullable' => false,
            ),
            'Updated At'
        )
        ->addColumn(
            'created_at',
            Varien_Db_Ddl_Table::TYPE_DATETIME,
            null,
            array(
                'nullable' => false,
            ),
            'Created At'
        )
        ->addIndex(
            $installer->getIdxName(
                'lcb_security_request_post',
                array('ip'),
                Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
            ),
            array('ip'),
            array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX)
        );

    $connection->createTable($requestPostTableDefinition);
}

$requestRule = $installer->getTable('lcb_security/request_rule');

if (!$connection->isTableExists($requestRule)) {
    $requestRuleTableDefinition = $connection
        ->newTable($requestRule)
        ->addColumn(
            'entity_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary'  => true,
            ),
            'ID'
        )
        ->addColumn(
            'path',
            Varien_Db_Ddl_Table::TYPE_TEXT,
            255,
            array(
                'nullable' => false,
            ),
            'Path'
        )
        ->addColumn(
            'requests_per_hour',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'unsigned' => true,
                'nullable' => false,
                'default'  => 10,
            ),
            'Requests per hour'
        )
        ->addColumn(
            'created_at',
            Varien_Db_Ddl_Table::TYPE_DATETIME,
            null,
            array(
                'nullable' => true,
            ),
            'Created At'
        )
        ->addColumn(
            'updated_at',
            Varien_Db_Ddl_Table::TYPE_DATETIME,
            null,
            array(
                'nullable' => true,
            ),
            'Updated At'
        )
        ->addIndex(
            $installer->getIdxName(
                'lcb_security/request_rule',
                array('path'),
                Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
            ),
            array('path'),
            array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
        )
        ->setComment('LCB Security Request Rules');

    $connection->createTable($requestRuleTableDefinition);
}


$installer->endSetup();
