<?php

class LCB_Security_Block_Adminhtml_Request_Rule_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('lcbSecurityRequestRuleGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->addExportType('*/*/exportCsv', Mage::helper('lcb_security')->__('CSV'));
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('lcb_security/request_rule')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $helper = Mage::helper('lcb_security');

        $this->addColumn('entity_id', array(
            'header' => $helper->__('ID'),
            'align'  => 'right',
            'width'  => '50px',
            'index'  => 'entity_id',
        ));

        $this->addColumn('path', array(
            'header' => $helper->__('Path'),
            'index'  => 'path',
        ));

        $this->addColumn('requests_per_hour', array(
            'header' => $helper->__('Requests per hour'),
            'index'  => 'requests_per_hour',
            'width'  => '120px',
        ));

        return parent::_prepareColumns();
    }

    /**
     * @param Varien_Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}
