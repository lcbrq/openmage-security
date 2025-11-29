<?php

class LCB_Security_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @var string
     */
    public const XPATH_REQUEST_LIMIT_ENABLED = 'lcb_security/request_limit/enabled';

    /**
     * @return bool
     */
    public function isRequestLimitEnabled()
    {
        return Mage::getStoreConfigFlag(self::XPATH_REQUEST_LIMIT_ENABLED, $storeId);
    }
}
