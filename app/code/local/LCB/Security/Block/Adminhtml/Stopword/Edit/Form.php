<?php

class LCB_Security_Block_Adminhtml_Stopword_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $model = Mage::registry('current_stopword');

        $form = new Varien_Data_Form(array(
            'id'     => 'edit_form',
            'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method' => 'post',
        ));
        $form->setUseContainer(true);

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend' => Mage::helper('lcb_security')->__('Stop Word'),
        ));

        $fieldset->addField('word', 'text', array(
            'name'     => 'word',
            'label'    => Mage::helper('lcb_security')->__('Word'),
            'required' => true,
        ));

        $fieldset->addField('is_active', 'select', array(
            'name'   => 'is_active',
            'label'  => Mage::helper('lcb_security')->__('Active'),
            'values' => array(
                array('value' => 1, 'label' => Mage::helper('lcb_security')->__('Yes')),
                array('value' => 0, 'label' => Mage::helper('lcb_security')->__('No')),
            ),
        ));

        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $form->setValues($data);
        } elseif ($model) {
            $form->setValues($model->getData());
        }

        $this->setForm($form);
        return parent::_prepareForm();
    }
}
