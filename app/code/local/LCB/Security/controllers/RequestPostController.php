<?php

class LCB_Security_RequestPostController extends Mage_Adminhtml_Controller_Action
{
    /**
     * @inheritDoc
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('system/lcb_security/request_post');
        $this->_title($this->__('Security Rules'));
        $this->_addContent(
            $this->getLayout()->createBlock('lcb_security/adminhtml_request_post_grid')
        );
        $this->renderLayout();
    }

    /**
     * @inheritDoc
     */
    public function exportCsvAction()
    {
        $fileName = 'post_requests.csv';
        $grid = $this->getLayout()->createBlock('lcb_security/adminhtml_request_post_grid');
        $content = $grid->getCsv();
        $content = str_replace(',', ';', $content);
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * @inheritDoc
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')
            ->isAllowed('system/lcb_security/request_post');
    }
}
