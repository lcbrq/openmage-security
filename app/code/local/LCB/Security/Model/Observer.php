<?php

class LCB_Security_Model_Observer
{
    public function checkPostFlood(Varien_Event_Observer $observer)
    {
        $action = $observer->getEvent()->getControllerAction();
        if (!$action) {
            return;
        }

        $request = $action->getRequest();
        if (!$request || !$request->isPost()) {
            return;
        }

        $ip  = Mage::helper('core/http')->getRemoteAddr();
        $uri = $request->getRequestUri();

        Mage::helper('lcb_security')->log(
            sprintf('LCB_Security checkPostFlood: POST from %s to %s', $ip, $uri)
        );

        /** @var LCB_Security_Model_Post_Request $postRequest */
        $postRequest = Mage::getModel('lcb_security/post_request')->load($ip, 'source_ip');

        /** @var Mage_Core_Model_Date $dateModel */
        $dateModel  = Mage::getSingleton('core/date');
        $nowTime    = $dateModel->gmtTimestamp();
        $nowString  = $dateModel->gmtDate('Y-m-d H:i:s');

        $blockWindowSeconds   = 10 * 60;
        $maxRequestsInWindow  = 3;

        if (!$postRequest->getId()) {
            $postRequest
                ->setSourceIp($ip)
                ->setRequestsCount(1)
                ->setUpdatedAt($nowString)
                ->save();

            Mage::helper('lcb_security')->log(
                sprintf('LCB_Security NEW: IP %s, count=1', $ip)
            );
            return;
        }

        $lastUpdated     = $postRequest->getUpdatedAt();
        $lastUpdatedTime = $lastUpdated ? strtotime($lastUpdated) : 0;
        $diff            = $nowTime - $lastUpdatedTime;

        if ($diff >= $blockWindowSeconds) {
            $postRequest
                ->setRequestsCount(1)
                ->setUpdatedAt($nowString)
                ->save();

            Mage::helper('lcb_security')->log(
                sprintf(
                    'LCB_Security RESET: IP %s, lastUpdated=%s, diff=%ds',
                    $ip,
                    $lastUpdated,
                    $diff
                )
            );
            return;
        }

        $currentCount = (int) $postRequest->getRequestsCount();
        $newCount     = $currentCount + 1;

        $postRequest
            ->setRequestsCount($newCount)
            ->setUpdatedAt($nowString)
            ->save();

        if ($newCount >= $maxRequestsInWindow) {
            Mage::helper('lcb_security')->log(
                sprintf(
                    'LCB_Security BLOCK: IP %s, count=%d (limit %d), lastUpdated=%s, diff=%ds',
                    $ip,
                    $newCount,
                    $maxRequestsInWindow,
                    $lastUpdated,
                    $diff
                )
            );

            Mage::getSingleton('core/session')->addError(
                Mage::helper('core')->__(
                    'Too many requests from your IP. Please try again, after 10 minutes.'
                )
            );

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

            return;
        }

        Mage::helper('lcb_security')->log(
            sprintf(
                'LCB_Security ALLOW: IP %s, count=%d/%d, lastUpdated=%s, diff=%ds',
                $ip,
                $newCount,
                $maxRequestsInWindow,
                $lastUpdated,
                $diff
            )
        );
    }
}
