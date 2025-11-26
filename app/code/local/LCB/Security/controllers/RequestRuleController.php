<?php

class LCB_Security_RequestRuleController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('system/lcb_security/request_rules');
        $this->_title($this->__('Security Rules'));
        $this->_addContent(
            $this->getLayout()->createBlock('lcb_security/adminhtml_request_rule')
        );
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $id    = $this->getRequest()->getParam('id');
        $model = Mage::getModel('lcb_security/request_rule');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError($this->__('Rule does not exist.'));
                return $this->_redirect('*/*/');
            }
        }

        Mage::register('current_rule', $model);

        $this->loadLayout();
        $this->_setActiveMenu('system/lcb_security/request_rules');
        $this->_title($this->__('Security Rules'));
        $this->_addContent(
            $this->getLayout()->createBlock('lcb_security/adminhtml_request_rule_edit')
        );

        $this->renderLayout();
    }

    public function deleteAction()
    {
        $id = $this->getRequest()->getParam('id');

        if (!$id) {
            Mage::getSingleton('adminhtml/session')->addError(
                $this->__('Missing rule ID.')
            );
            return $this->_redirect('*/*/');
        }

        try {
            /** @var LCB_Security_Model_Request_Rule $model */
            $model = Mage::getModel('lcb_security/request_rule')->load($id);

            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(
                    $this->__('Rule no longer exists.')
                );
                return $this->_redirect('*/*/');
            }

            $model->delete();

            Mage::getSingleton('adminhtml/session')->addSuccess(
                $this->__('Rule has been deleted.')
            );
            return $this->_redirect('*/*/');
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            return $this->_redirect('*/*/edit', array('id' => $id));
        }
    }

    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            /** @var LCB_Security_Model_Rule $model */
            $model = Mage::getModel('lcb_security/request_rule');

            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $model->load($id);
            }

            $model->addData($data);

            try {
                $now = Mage::getSingleton('core/date')->gmtDate('Y-m-d H:i:s');

                if (!$model->getId()) {
                    $model->setCreatedAt($now);
                }
                $model->setUpdatedAt($now);

                $model->save();

                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Rule has been saved.'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    return $this->_redirect('*/*/edit', array('id' => $model->getId()));
                }

                return $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);

                if ($id) {
                    return $this->_redirect('*/*/edit', array('id' => $id));
                } else {
                    return $this->_redirect('*/*/new');
                }
            }
        }

        Mage::getSingleton('adminhtml/session')->addError($this->__('No data to save.'));
        $this->_redirect('*/*/');
    }

    /**
     * @inheritDoc
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')
            ->isAllowed('system/lcb_security/request_rules');
    }
}
