<?php

class LCB_Security_Block_Adminhtml_Stopword extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_stopword';
        $this->_blockGroup = 'lcb_security';
        $this->_headerText = Mage::helper('lcb_security')->__('Stop Words');
        parent::__construct();
    }
}
