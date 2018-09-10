<?php
class Mivec_Supplier_Block_Adminhtml_Supplier_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('supplier_form', array('legend' => 'View / Edit Supplier'));

        $formData = Mage::registry("supplier_data")->getData();
        //print_r($formData);

        $fieldset->addField('company_name', 'link', array(
            'label'     => 'Company name',
            'name'		=> 'company_name',
            'class'     => 'required-entry',
            'required'	=> true,
            'value'		=> Mage::registry("supplier_data")->getData("company_name"),
        ));

        $market = Mage::helper("supplier/market")->getMarkets();
        $fieldset->addField("market" , "select" , array(
            "label" => "Market",
            "name"  => "market",
            "class" => "required-entry",
            "values"    => $market,
            "value" => Mage::registry("supplier_data")->getData("market")
        ));

        $fieldset->addField("merchant_no" , "link" , array(
            "label"     => "Merchant NO.",
            "name"      => "merchant_no",
            "value"     => Mage::registry("supplier_data")->getData("merchant_no")
        ));

        $fieldset->addField("contacter" , "link" , array(
            "label"     => "Contacter",
            "name"      => "contacter",
            "value"     => Mage::registry("supplier_data")->getData("contacter")
        ));

        $fieldset->addField("mobilephone" , "link" , array(
            "label"     => "Mobile Phone",
            "name"      => "mobilephone",
            "value"     => Mage::registry("supplier_data")->getData("mobilephone")
        ));

        $fieldset->addField("wechat" , "link" , array(
            "label"     => "Wechat ID",
            "name"      => "wechat",
            "value"     => Mage::registry("supplier_data")->getData("wechat")
        ));

        $fieldset->addField("created_at" , "link" , array(
            "label"     => "Register Date",
            "value"     => Mage::registry("supplier_data")->getData("created_at")
        ));

        $isValidate = Mivec_Supplier_Model_Supplier::getIsValidate();
        $fieldset->addField("is_validate" , "select"  , array(
            "label"     => "Is Validate",
            "name"      => "is_validate",
            "options"    => $isValidate,
            "value"     => Mage::registry("supplier_data")->getData("is_validate")
        ));

        $fieldset->addField("main_products" , "link" , array(
            "label"     => "Main Products",
            "name"      => "main_product",
            "class"     => "",
            "value"     => Mage::registry("supplier_data")->getData("main_product")
        ));

        $fieldset->addField("info" , "link" , array(
            "label"     => "Other Info.",
            "name"      => "info",
            "class"     => "",
            "value"     => Mage::registry("supplier_data")->getData("info")
        ));

        if ( Mage::getSingleton('adminhtml/session')->getSupplierData() )
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getSupplierData());
            Mage::getSingleton('adminhtml/session')->getSupplierData(null);
        } elseif ( Mage::registry('supplier_data') ) {
            $form->setValues(Mage::registry('supplier_data')->getData());
        }
        return parent::_prepareForm();
    }
}

/*
             Array
(
    [id] => 1
    [customer_id] => 82
    [company_name] => Powerdreams LLC
    [market] => 5
    [merchant_no] => #139
    [main_products] => LCD
    [contacter] =>
    [mobilephone] => 18555555
    [wechat] => mivtec
    [created_at] => 2018-09-01
    [is_validate] => 1
    [info] =>
)

 * */