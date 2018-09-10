<?php
class Mivec_Supplier_Block_Adminhtml_Quote_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('quote_form', array('legend' => 'View / Edit Supplier'));

        $formData = Mage::registry("quote_data")->getData();
        //print_r($formData);

        //get supplier info
        $supplier = Mage::helper("supplier/supplier")->getSuppliers(
            array("id") , array(Mage::registry("quote_data")->getData("supplier_id"))
        );
        $fieldset->addField('company_name', 'link', array(
            'label'     => 'Company name',
            'name'		=> 'company_name',
            'required'	=> true,
            'value'		=> $supplier[0]["company_name"],
        ));

        //product data
        $_product = Mage::getModel("catalog/product")->load(Mage::registry("quote_data")->getProductId());
        $fieldset->addField("sku" , "link" , array(
            "label" => "SKU",
            "value" => $_product->getSku()
        ));


        if (Mage::getSingleton('adminhtml/session')->getQuoteData() )
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getQuoteData());
            Mage::getSingleton('adminhtml/session')->getSupplierData(null);
        } elseif ( Mage::registry('quote_data') ) {
            $form->setValues(Mage::registry('quote_data')->getData());
        }
        return parent::_prepareForm();
    }
}
