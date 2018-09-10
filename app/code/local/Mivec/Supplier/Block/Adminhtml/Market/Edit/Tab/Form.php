<?php
class Mivec_Supplier_Block_Adminhtml_Market_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('market_form', array('legend' => 'Edit Market'));

        $formData = Mage::registry("market_data")->getData();
        //print_r($formData);

        $fieldset->addField('name', 'text', array(
            'label'     => 'Name',
            'name'		=> 'name',
            'class'     => 'required-entry',
            'required'	=> true,
            'value'		=> Mage::registry("market_data")->getData("name"),
        ));

        if ( Mage::getSingleton('adminhtml/session')->getMarketData() )
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getMarketData());
            Mage::getSingleton('adminhtml/session')->getMarketData(null);
        } elseif ( Mage::registry('market_data') ) {
            $form->setValues(Mage::registry('market_data')->getData());
        }
        return parent::_prepareForm();
    }
}