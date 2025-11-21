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
        $uri = $request->getPathInfo();

        /** @var LCB_Security_Model_Rule $rule */
        $rule = Mage::getModel('lcb_security/rule')->load($uri, 'url');

        if (!$rule->getId()) {
            Mage::helper('lcb_security')->log(
                sprintf('LCB_Security: no rule for URL %s, skipping', $uri)
            );
            return;
        }

        // RULE CHECK
        $maxRequestsInWindow = (int) $rule->getRequestsPerHour();
        if ($maxRequestsInWindow <= 0) {
            Mage::helper('lcb_security')->log(
                sprintf('LCB_Security: rule for %s has non-positive limit (%d), skipping', $uri, $maxRequestsInWindow)
            );
            return;
        }

        Mage::helper('lcb_security')->log(
            sprintf(
                'LCB_Security checkPostFlood: POST from %s to %s (limit %d req/h)',
                $ip,
                $uri,
                $maxRequestsInWindow
            )
        );

        /** @var LCB_Security_Model_Post_Request $postRequest */
        $postRequest = Mage::getModel('lcb_security/post_request')
            ->getCollection()
            ->addFieldToFilter('source_ip', $ip)
            ->addFieldToFilter('url', $uri)
            ->getFirstItem();

        /** @var Mage_Core_Model_Date $dateModel */
        $dateModel  = Mage::getSingleton('core/date');
        $nowTime    = $dateModel->gmtTimestamp();
        $nowString  = $dateModel->gmtDate('Y-m-d H:i:s');

        // BLOCK PER HOUR
        $blockWindowSeconds = 60 * 60;

        if (!$postRequest->getId()) {
            $postRequest
                ->setSourceIp($ip)
                ->setUrl($uri)
                ->setRequestsCount(1)
                ->setUpdatedAt($nowString)
                ->save();

            Mage::helper('lcb_security')->log(
                sprintf('LCB_Security NEW: IP %s, URL %s, count=1', $ip, $uri)
            );
            return;
        }

        $lastUpdated     = $postRequest->getUpdatedAt();
        $lastUpdatedTime = $lastUpdated ? strtotime($lastUpdated) : 0;
        $diff            = $nowTime - $lastUpdatedTime;

        if ($diff >= $blockWindowSeconds) {
            $postRequest
                ->setUrl($uri)
                ->setRequestsCount(1)
                ->setUpdatedAt($nowString)
                ->save();

            Mage::helper('lcb_security')->log(
                sprintf(
                    'LCB_Security RESET: IP %s, URL %s, lastUpdated=%s, diff=%ds',
                    $ip,
                    $uri,
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

        if ($newCount > $maxRequestsInWindow) {
            Mage::helper('lcb_security')->log(
                sprintf(
                    'LCB_Security BLOCK: IP %s, URL %s, count=%d (limit %d), lastUpdated=%s, diff=%ds',
                    $ip,
                    $uri,
                    $newCount,
                    $maxRequestsInWindow,
                    $lastUpdated,
                    $diff
                )
            );

            Mage::getSingleton('core/session')->addError(
                Mage::helper('core')->__(
                    'Too many requests from your IP. Please try again, after some time.'
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
                'LCB_Security ALLOW: IP %s, URL %s, count=%d/%d, lastUpdated=%s, diff=%ds',
                $ip,
                $uri,
                $newCount,
                $maxRequestsInWindow,
                $lastUpdated,
                $diff
            )
        );
    }
}
