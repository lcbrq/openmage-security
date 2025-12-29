<?php

class LCB_Security_RejectedRequestController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('system/lcb_security/rejected_requests');
        $this->_title($this->__('Rejected Posts'));
        $this->_addContent(
            $this->getLayout()->createBlock('lcb_security/adminhtml_rejected_request')
        );
        $this->renderLayout();
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('system/lcb_security/rejected_requests');
    }

    public function deleteAction()
    {
        $id = (int)$this->getRequest()->getParam('id');
        if (!$id) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Missing ID.'));
            return $this->_redirect('*/*/index');
        }

        try {
            $model = Mage::getModel('lcb_security/rejectedRequest')->load($id);
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError($this->__('Record not found.'));
                return $this->_redirect('*/*/index');
            }

            $model->delete();
            Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Deleted.'));
        } catch (Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

        return $this->_redirect('*/*/index');
    }

    public function massDeleteAction()
    {
        $ids = $this->getRequest()->getParam('ids');
        if (!is_array($ids) || empty($ids)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select records.'));
            return $this->_redirect('*/*/index');
        }

        try {
            $deleted = 0;
            foreach ($ids as $id) {
                $id = (int)$id;
                if (!$id) {
                    continue;
                }

                $model = Mage::getModel('lcb_security/rejectedRequest')->load($id);
                if ($model->getId()) {
                    $model->delete();
                    $deleted++;
                }
            }

            Mage::getSingleton('adminhtml/session')->addSuccess(
                $this->__('%d record(s) deleted.', $deleted)
            );
        } catch (Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

        return $this->_redirect('*/*/index');
    }
    public function exportCsvAction()
    {
        $fileName = 'security_rejected_posts.csv';
        $grid = $this->getLayout()->createBlock('lcb_security/adminhtml_rejected_request_grid');
        $content = $grid->getCsv();
        $content = str_replace(',', ';', $content);
        $this->_prepareDownloadResponse($fileName, $content);
    }
}
