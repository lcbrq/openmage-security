<?php

class LCB_Security_Block_Adminhtml_Stopword_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_objectId   = 'id';
        $this->_blockGroup = 'lcb_security';
        $this->_controller = 'adminhtml_stopword';
        $this->_updateButton('save', 'label', Mage::helper('lcb_security')->__('Save'));
        $this->_updateButton('delete', 'label', Mage::helper('lcb_security')->__('Delete'));
    }

    public function getHeaderText()
    {
        $model = Mage::registry('current_stopword');
        if ($model && $model->getId()) {
            return Mage::helper('lcb_security')->__('Edit Stop Word');
        }
        return Mage::helper('lcb_security')->__('New Stop Word');
    }
}
