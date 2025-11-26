<?php

class LCB_Security_Model_Resource_Request_Rule extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('lcb_security/request_rule', 'entity_id');
    }
}
