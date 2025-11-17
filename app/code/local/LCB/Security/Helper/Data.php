<?php

class LCB_Security_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @param string $text
     * @return void
     */
    public function log($text)
    {
        Mage::log(
            $text,
            null,
            'lcb_security.log',
            true
        );
    }
}
