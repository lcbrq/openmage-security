<?php

class LCB_Security_Model_Resource_Request_Post_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('lcb_security/request_post');
    }
}
