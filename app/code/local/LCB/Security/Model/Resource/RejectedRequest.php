<?php

class LCB_Security_Model_Resource_RejectedRequest extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('lcb_security/rejectedRequest', 'entity_id');
    }
}
