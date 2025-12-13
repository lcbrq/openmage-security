<?php

class LCB_Security_Block_Adminhtml_Request_Post_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('lcbSecurityRequestPostGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->addExportType('*/*/exportCsv', Mage::helper('lcb_security')->__('CSV'));
    }

    /**
     * @inheritDoc
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('lcb_security/request_post')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * @inheritDoc
     */
    protected function _prepareColumns()
    {
        $helper = Mage::helper('lcb_security');

        $this->addColumn('entity_id', array(
            'header' => $helper->__('ID'),
            'align'  => 'right',
            'width'  => '50px',
            'index'  => 'entity_id',
        ));

        $this->addColumn('customer_id', array(
            'header' => $helper->__('Customer Id'),
            'index'  => 'customer_id',
        ));

        $this->addColumn('ip', array(
            'header' => $helper->__('IP'),
            'index'  => 'ip',
        ));

        $this->addColumn('path', array(
            'header' => $helper->__('Path'),
            'index'  => 'path',
        ));

        $this->addColumn('count', array(
            'header' => $helper->__('Qty'),
            'index'  => 'count',
            'type'   => 'number',
        ));

        $this->addColumn('recaptcha', array(
            'header' => $helper->__('Recaptcha'),
            'index'  => 'recaptcha',
        ));

        $this->addColumn('turnstile', array(
            'header' => $helper->__('Turnstile'),
            'index'  => 'turnstile',
        ));

        return parent::_prepareColumns();
    }

    /**
     * @param Varien_Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return "#";
    }
}
