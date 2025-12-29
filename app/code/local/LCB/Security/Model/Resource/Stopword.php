<?php

class LCB_Security_Model_Resource_Stopword extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('lcb_security/stopword', 'entity_id');
    }
}
