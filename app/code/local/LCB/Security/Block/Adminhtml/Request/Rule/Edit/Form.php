<?php

class LCB_Security_Block_Adminhtml_Request_Rule_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        /** @var LCB_Security_Model_Rule $model */
        $model = Mage::registry('current_rule');

        $form = new Varien_Data_Form(array(
            'id'      => 'edit_form',
            'action'  => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method'  => 'post',
        ));

        $form->setUseContainer(true);
        $this->setForm($form);

        $fieldset = $form->addFieldset(
            'base_fieldset',
            array('legend' => Mage::helper('lcb_security')->__('General'))
        );

        if ($model && $model->getId()) {
            $fieldset->addField('entity_id', 'hidden', array(
                'name' => 'entity_id',
            ));
        }

        $fieldset->addField('path', 'text', array(
            'name'     => 'path',
            'label'    => Mage::helper('lcb_security')->__('Path'),
            'title'    => Mage::helper('lcb_security')->__('Path'),
            'required' => true,
        ));

        $fieldset->addField('requests_per_hour', 'text', array(
            'name'     => 'requests_per_hour',
            'label'    => Mage::helper('lcb_security')->__('Requests per hour'),
            'title'    => Mage::helper('lcb_security')->__('Requests per hour'),
            'class'    => 'validate-digits',
            'required' => true,
        ));

        if ($model) {
            $form->setValues($model->getData());
        }

        return parent::_prepareForm();
    }
}
