<?php

class LCB_Security_Model_RejectedRequest extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('lcb_security/rejectedRequest');
    }
    public function logFromRequest($matchedWord, Mage_Core_Controller_Request_Http $request)
    {
        $post = $request->getPost();
        $raw  = (string)$request->getRawBody();
        $payload = array(
            'post' => $post,
            'raw'  => $raw,
        );

        $this->setCreatedAt(now());
        $this->setRemoteAddr(Mage::helper('core/http')->getRemoteAddr());
        $this->setRequestUri((string)$request->getRequestUri());
        $this->setUserAgent((string)$request->getServer('HTTP_USER_AGENT'));
        $this->setMatchedWord((string)$matchedWord);
        $this->setPostBody(Zend_Json::encode($payload));
        $this->save();

        return $this;
    }
}
