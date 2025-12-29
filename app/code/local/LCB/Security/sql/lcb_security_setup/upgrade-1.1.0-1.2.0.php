<?php

$this->startSetup();

$connection = $this->getConnection();

/**
 * Table: lcb_security_request_stopword
 */
$tableName = $this->getTable('lcb_security/stopword');
if (!$connection->isTableExists($tableName)) {
    $table = $connection
        ->newTable($tableName)
        ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, [
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary'  => true,
        ], 'Entity ID')
        ->addColumn('word', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, [
            'nullable' => false,
        ], 'Stop Word (store lowercase recommended)')
        ->addColumn('is_active', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, [
            'nullable' => false,
            'default'  => 1,
        ], 'Is Active')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_DATETIME, null, [
            'nullable' => false,
        ], 'Created At')
        ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_DATETIME, null, [
            'nullable' => true,
        ], 'Updated At')
        ->addIndex(
            $this->getIdxName('lcb_security/stopword', ['word'], Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE),
            ['word'],
            ['type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE]
        )
        ->setComment('LCB Security - Stop Words');

    $connection->createTable($table);
}

/**
 * Table: lcb_security_request_post_rejected
 */
$tableName = $this->getTable('lcb_security/rejectedRequest');
if (!$connection->isTableExists($tableName)) {
    $table = $connection
        ->newTable($tableName)
        ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, [
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary'  => true,
        ], 'Entity ID')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_DATETIME, null, [
            'nullable' => false,
        ], 'Created At')
        ->addColumn('remote_addr', Varien_Db_Ddl_Table::TYPE_VARCHAR, 45, [
            'nullable' => true,
        ], 'Remote Address')
        ->addColumn('request_uri', Varien_Db_Ddl_Table::TYPE_VARCHAR, 2048, [
            'nullable' => true,
        ], 'Request URI')
        ->addColumn('user_agent', Varien_Db_Ddl_Table::TYPE_VARCHAR, 1024, [
            'nullable' => true,
        ], 'User Agent')
        ->addColumn('matched_word', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, [
            'nullable' => true,
        ], 'Matched Stop Word')
        ->addColumn('post_body', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', [
            'nullable' => true,
        ], 'Serialized/JSON POST Body (raw content)')
        ->addIndex(
            $this->getIdxName('lcb_security/rejectedRequest', ['created_at']),
            ['created_at']
        )
        ->addIndex(
            $this->getIdxName('lcb_security/rejectedRequest', ['remote_addr']),
            ['remote_addr']
        )
        ->setComment('LCB Security - Rejected POST Requests');

    $connection->createTable($table);
}

$this->endSetup();
