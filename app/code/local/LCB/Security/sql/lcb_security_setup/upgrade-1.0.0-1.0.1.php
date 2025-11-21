<?php

/** @var Mage_Core_Model_Resource_Setup $installer */
$installer = $this;

$installer->startSetup();
$conn = $installer->getConnection();

$postRequestTable = $installer->getTable('lcb_security/post_request');

if ($conn->isTableExists($postRequestTable)) {
    if (!$conn->tableColumnExists($postRequestTable, 'url')) {
        $conn->addColumn(
            $postRequestTable,
            'url',
            array(
                'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
                'length'   => 255,
                'nullable' => true,
                'comment'  => 'Request URL',
            )
        );
    }
}

$ruleTable = $installer->getTable('lcb_security/rule');

if (!$conn->isTableExists($ruleTable)) {
    $table = $conn
        ->newTable($ruleTable)
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
            'url',
            Varien_Db_Ddl_Table::TYPE_TEXT,
            255,
            array(
                'nullable' => false,
            ),
            'URL pattern'
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
                'lcb_security/rule',
                array('url'),
                Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
            ),
            array('url'),
            array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
        )
        ->setComment('LCB Security Rules');

    $conn->createTable($table);
}

$installer->endSetup();
