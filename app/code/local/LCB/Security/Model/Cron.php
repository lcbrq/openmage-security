<?php

class LCB_Security_Model_Cron
{
    public function deleteOldRequests()
    {
        $dateModel = Mage::getSingleton('core/date');
        $threshold = $dateModel->gmtDate('Y-m-d H:i:s', time() - 3600);

        $resource = Mage::getSingleton('core/resource');
        $write = $resource->getConnection('core_write');

        $table = $resource->getTableName('lcb_security/request_post');
        $sql = "DELETE FROM {$table} WHERE updated_at < :threshold";

        try {
            $write->query($sql, ['threshold' => $threshold]);
        } catch (Exception $e) {
            Mage::logException($e);
        }

        return $this;
    }
}
