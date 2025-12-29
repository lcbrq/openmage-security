<?php

class LCB_Security_Block_Adminhtml_Rejected_Request extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_rejected_request';
        $this->_blockGroup = 'lcb_security';
        $this->_headerText = Mage::helper('lcb_security')->__('Rejected Posts');
        parent::__construct();
        $this->_removeButton('add');
    }
}
