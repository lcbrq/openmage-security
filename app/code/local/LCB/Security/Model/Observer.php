<?php

class LCB_Security_Model_Observer
{
    private $defaultRequestsPerHour = 10;

    /**
     * @param Varien_Event_Observer $observer
     * @return Mage_Core_Controller_Varien_Action
     */
    public function checkPostFlood(Varien_Event_Observer $observer)
    {
        if (!$action = $observer->getEvent()->getControllerAction()) {
            return;
        }

        $request = $action->getRequest();
        if (!$request || !$request->isPost()) {
            return;
        }

        if (!Mage::helper('lcb_security')->isRequestLimitEnabled()) {
            return;
        }

        $ip  = Mage::helper('core/http')->getRemoteAddr();
        $path = rtrim(ltrim((string) $request->getPathInfo(), '/'), '/');

        /** @var LCB_Security_Model_Rule $rule */
        $rule = Mage::getModel('lcb_security/request_rule')->load($path, 'path');
        $requestsPerHour = $rule->getRequestsPerHour() ?? $this->defaultRequestsPerHour;

        /** @var LCB_Security_Model_Request_Post $postRequest */
        $postRequest = Mage::getModel('lcb_security/request_post')
            ->getCollection()
            ->addFieldToFilter('ip', $ip)
            ->addFieldToFilter('path', $path)
            ->getFirstItem();

        $dateModel   = Mage::getSingleton('core/date');
        $date  = $dateModel->gmtDate('Y-m-d H:i:s');
        $now   = $dateModel->gmtTimestamp();

        $requestsCount = (int) $postRequest->getCount();

        if ($createdAt = $postRequest->getCreatedAt()) {
            if ($now - 3600 < strtotime($createdAt)) {
                if ($requestsCount > $requestsPerHour) {
                    return $this->throwTooManyRequestsException($action);
                }
            }
        }

        $customerId = Mage::getSingleton('customer/session')->getCustomer()->getId();
        $hasCaptcha = $request->getParam('g-recaptcha-response') ? 1 : 0;
        $hasTurnstile = $request->getParam('cf-turnstile-response') ? 1 : 0;

        $postRequest
            ->setCustomerId($customerId)
            ->setIp($ip)
            ->setPath($path)
            ->setCount($requestsCount + 1)
            ->setRecaptcha($hasCaptcha)
            ->setTurnstile($hasTurnstile)
            ->setUpdatedAt($date);

        if (!$postRequest->getCreatedAt()) {
            $postRequest->setCreatedAt($date);
        }

        try {
            $postRequest->save();
        } catch (Exception $e) {
            Mage::logException($e);
        }

        return $action;
    }

    /**
     * @param Mage_Core_Controller_Varien_Action $action
     * @return Mage_Core_Controller_Varien_Action
     */
    private function throwTooManyRequestsException($action)
    {
        $request  = $action->getRequest();
        $response = $action->getResponse();

        $message = Mage::helper('lcb_security')->__(
            'Too many requests from your IP. Please try again, after some time.'
        );

        if ($request->isXmlHttpRequest() || $request->getParam('isAjax')) {
            $response
                ->clearHeaders()
                ->setHeader('Content-Type', 'application/json', true)
                ->setHttpResponseCode(200)
                ->setBody(Mage::helper('core')->jsonEncode(array(
                    'success' => false,
                    'error'   => true,
                    'message' => $message,
                )));

            $action->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
            return;
        }

        Mage::getSingleton('core/session')->addError($message);

        $referer = $request->getServer('HTTP_REFERER');
        if (!$referer) {
            $referer = Mage::getUrl('');
        }

        $action->getResponse()->setRedirect($referer);
        $action->setFlag(
            '',
            Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH,
            true
        );

        return $action;
    }
    public function blockPostByStopwords(Varien_Event_Observer $observer)
    {
        $controller = $observer->getEvent()->getControllerAction();
        if (!$controller) {
            return;
        }

        if ($controller->getFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH)) {
            return;
        }

        $request = $controller->getRequest();

        if (!$request || !$request->isPost()) {
            return;
        }

        if (Mage::app()->getStore()->isAdmin() || $request->getRouteName() === 'admin') {
            return;
        }

        $raw  = (string)$request->getRawBody();
        $post = (array)$request->getPost();
        $text = $raw !== '' ? $raw : Zend_Json::encode($post);

        $matched = Mage::getModel('lcb_security/stopword')->findMatchedWordInText($text);
        if (!$matched) {
            return;
        }

        try {
            $payload = array(
                'post' => $post,
                'raw'  => $raw,
            );

            Mage::getModel('lcb_security/rejectedRequest')
                ->setCreatedAt(now())
                ->setRemoteAddr(Mage::helper('core/http')->getRemoteAddr())
                ->setRequestUri((string)$request->getRequestUri())
                ->setUserAgent((string)$request->getServer('HTTP_USER_AGENT'))
                ->setMatchedWord((string)$matched)
                ->setPostBody(Zend_Json::encode($payload))
                ->save();
        } catch (Exception $e) {
            Mage::logException($e);
        }

        Mage::getSingleton('core/session')->addError(
            Mage::helper('lcb_security')->__('Your message was blocked by spam protection.')
        );

        $referer = $request->getServer('HTTP_REFERER');
        $target  = $referer ? $referer : Mage::getBaseUrl();

        $controller->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
        $controller->getResponse()
            ->setRedirect($target)
            ->sendResponse();
    }
}
