<?php
class Mivec_Supplier_Block_Adminhtml_Supplier_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('supplier_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle('View/Edit Supplier');
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_supplier', array(
            'label'     => "Supplier",
            'content'   => $this->getLayout()->createBlock('supplier/adminhtml_supplier_edit_tab_form')->toHtml(),
        ));

        $this->addTab('form_quote', array(
            'label'     => "Quotes",
            'content'   => $this->getLayout()->createBlock('supplier/adminhtml_quote_grid')->toHtml(),
        ));


        return parent::_beforeToHtml();
    }
}