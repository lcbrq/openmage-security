<?php

class LCB_Security_Block_Adminhtml_Request_Rule extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_request_rule';
        $this->_blockGroup = 'lcb_security';
        $this->_headerText = Mage::helper('lcb_security')->__('Request Rules');
        $this->_addButtonLabel = Mage::helper('lcb_security')->__('Add New Rule');

        parent::__construct();
    }
}
