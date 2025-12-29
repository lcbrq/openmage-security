<?php

class LCB_Security_Block_Adminhtml_Stopword_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('lcb_security_stopword_grid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->addExportType('*/*/exportCsv', $this->__('CSV'));
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('lcb_security/stopword')->getCollection();
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

        $this->addColumn('word', array(
            'header' => $this->__('Word'),
            'index'  => 'word',
        ));

        $this->addColumn('is_active', array(
            'header'  => $this->__('Active'),
            'index'   => 'is_active',
            'type'    => 'options',
            'options' => array(0 => $this->__('No'), 1 => $this->__('Yes')),
            'width'   => '80px',
        ));

        $this->addColumn('created_at', array(
            'header' => $this->__('Created'),
            'index'  => 'created_at',
            'type'   => 'datetime',
        ));
        $this->addColumn('action', array(
            'header'   => $this->__('Operacja'),
            'type'     => 'action',
            'getter'   => 'getId',
            'actions'  => array(
                array(
                    'caption' => $this->__('Usuń'),
                    'url'     => array('base' => 'lcb_security/stopword/delete'),
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
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('ids');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'   => $this->__('Usuń'),
            'url'     => $this->getUrl('lcb_security/stopword/massDelete'),
            'confirm' => $this->__('Are you sure you to delete the selected?'),
        ));

        return $this;
    }
}
