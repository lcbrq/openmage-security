<?php

class LCB_Security_StopwordController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('system/lcb_security/stopwords');
        $this->_title($this->__('Stop Words'));
        $this->_addContent(
            $this->getLayout()->createBlock('lcb_security/adminhtml_stopword')
        );
        $this->renderLayout();
    }

    /**
     * @inheritDoc
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * @return void
     */
    public function editAction()
    {
        $id = (int)$this->getRequest()->getParam('id');
        $model = Mage::getModel('lcb_security/stopword');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError($this->__('Record not found.'));
                return $this->_redirect('*/*/index');
            }
        }

        Mage::register('current_stopword', $model);

        $this->loadLayout();
        $this->_setActiveMenu('system/lcb_security/stopwords');
        $this->_title($this->__('Stop Words'));

        $this->_addContent($this->getLayout()->createBlock('lcb_security/adminhtml_stopword_edit'));
        $this->renderLayout();
    }

    /**
     * @return void
     */
    public function saveAction()
    {
        if (!$this->getRequest()->isPost()) {
            return $this->_redirect('*/*/index');
        }

        $id = (int)$this->getRequest()->getParam('id');
        $data = (array)$this->getRequest()->getPost();
        $model = Mage::getModel('lcb_security/stopword');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError($this->__('Record not found.'));
                return $this->_redirect('*/*/index');
            }
        }

        $word = isset($data['word']) ? trim((string)$data['word']) : '';
        $isActive = isset($data['is_active']) ? (int)$data['is_active'] : 1;

        if ($word === '') {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Word is required.'));
            Mage::getSingleton('adminhtml/session')->setFormData($data);
            return $this->_redirectReferer();
        }
        $word = mb_strtolower($word, 'UTF-8');

        try {
            $model->setWord($word);
            $model->setIsActive($isActive);

            if (!$model->getId()) {
                $model->setCreatedAt(now());
            }
            $model->setUpdatedAt(now());

            $model->save();

            Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Saved.'));
            Mage::getSingleton('adminhtml/session')->setFormData(false);

            return $this->_redirect('*/*/index');
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            Mage::getSingleton('adminhtml/session')->setFormData($data);
            return $this->_redirectReferer();
        }
    }

    /**
     * @return void
     */
    public function deleteAction()
    {
        $id = (int)$this->getRequest()->getParam('id');
        if (!$id) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Missing ID.'));
            return $this->_redirect('*/*/index');
        }

        try {
            $model = Mage::getModel('lcb_security/stopword')->load($id);
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

    /**
     * @return void
     */
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

                $model = Mage::getModel('lcb_security/stopword')->load($id);
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

    /**
     * @inheritDoc
     */
    public function exportCsvAction()
    {
        $fileName = 'security_stopwords.csv';
        $grid = $this->getLayout()->createBlock('lcb_security/adminhtml_stopword_grid');
        $content = $grid->getCsv();
        $content = str_replace(',', ';', $content);

        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * @inheritDoc
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('system/lcb_security/stopwords');
    }
}
