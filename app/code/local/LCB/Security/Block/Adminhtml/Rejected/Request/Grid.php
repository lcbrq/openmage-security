<?php

class LCB_Security_Block_Adminhtml_Rejected_Request_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('lcb_security_rejected_request_grid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->addExportType('*/*/exportCsv', $this->__('CSV'));
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('lcb_security/rejectedRequest')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header' => $this->__('ID'),
            'index'  => 'entity_id',
            'type'   => 'number',
            'width'  => '60px',
        ));

        $this->addColumn('created_at', array(
            'header' => $this->__('Created'),
            'index'  => 'created_at',
            'type'   => 'datetime',
            'width'  => '170px',
        ));

        $this->addColumn('remote_addr', array(
            'header' => $this->__('IP'),
            'index'  => 'remote_addr',
            'width'  => '130px',
        ));

        $this->addColumn('matched_word', array(
            'header' => $this->__('Matched Word'),
            'index'  => 'matched_word',
            'width'  => '160px',
        ));

        $this->addColumn('matched_input', array(
            'header'   => Mage::helper('lcb_security')->__('Matched Input'),
            'index'    => 'post_body',
            'renderer' => 'lcb_security/adminhtml_rejected_request_renderer_matchedInput',
            'filter'   => false,
            'sortable' => false,
            'width'    => '350px',
        ));

        $this->addColumn('request_uri', array(
            'header' => $this->__('URI'),
            'index'  => 'request_uri',
        ));
        $this->addColumn('action', array(
            'header'   => $this->__('Operacja'),
            'type'     => 'action',
            'getter'   => 'getId',
            'actions'  => array(
                array(
                    'caption' => $this->__('Usuń'),
                    'url'     => array('base' => 'lcb_security/rejectedRequest/delete'),
                    'field'   => 'id',
                    'confirm' => $this->__('Are you sure you to delete it?'),
                ),
            ),
            'filter'   => false,
            'sortable' => false,
            'width'    => '80px',
            'is_system'=> true,
        ));


        return parent::_prepareColumns();
    }
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('ids');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'   => $this->__('Usuń'),
            'url'     => $this->getUrl('lcb_security/rejectedRequest/massDelete'),
            'confirm' => $this->__('Are you sure you to delete the selected?'),
        ));

        return $this;
    }
    public function getRowUrl($row)
    {
        return '';
    }
}
