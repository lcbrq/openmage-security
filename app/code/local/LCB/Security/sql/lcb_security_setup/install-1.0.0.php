<?php

/** @var Mage_Core_Model_Resource_Setup $installer */
$installer = $this;

$installer->startSetup();

$conn  = $installer->getConnection();
$table = $installer->getTable('lcb_security_post_request');

if (!$conn->isTableExists($table)) {
    $tableDefinition = $conn
        ->newTable($table)
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
            'source_ip',
            Varien_Db_Ddl_Table::TYPE_VARCHAR,
            45,
            array(
                'nullable' => false,
            ),
            'Source IP'
        )
        ->addColumn(
            'requests_count',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'nullable' => false,
                'default'  => 0,
            ),
            'Requests Count'
        )
        ->addColumn(
            'updated_at',
            Varien_Db_Ddl_Table::TYPE_DATETIME,
            null,
            array(
                'nullable' => false,
            ),
            'Last Update Time'
        )
        ->addIndex(
            $installer->getIdxName(
                'lcb_security_post_request',
                array('source_ip'),
                Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
            ),
            array('source_ip'),
            array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX)
        );

    $conn->createTable($tableDefinition);
}

$installer->endSetup();
