<?php

class LCB_Security_Model_Resource_Request_Rule_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('lcb_security/request_rule');
    }
}
