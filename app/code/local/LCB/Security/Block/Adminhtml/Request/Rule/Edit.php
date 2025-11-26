<?php

class LCB_Security_Block_Adminhtml_Request_Rule_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_objectId   = 'id';
        $this->_blockGroup = 'lcb_security';
        $this->_controller = 'adminhtml_request_rule';
        parent::__construct();

        $this->_updateButton('save', 'label', Mage::helper('lcb_security')->__('Save Rule'));
        $this->_updateButton('delete', 'label', Mage::helper('lcb_security')->__('Delete Rule'));
    }

    public function getHeaderText()
    {
        $model = Mage::registry('current_rule');

        if ($model && $model->getId()) {
            return Mage::helper('lcb_security')->__(
                'Edit Rule "%s"',
                $this->escapeHtml($model->getUrl())
            );
        }
        return Mage::helper('lcb_security')->__('New Rule');
    }
}
