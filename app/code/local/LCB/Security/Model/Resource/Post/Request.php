<?php

class LCB_Security_Model_Resource_Post_Request extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('lcb_security/post_request', 'entity_id');
    }
}
