<?php
class Mivec_Supplier_Block_Adminhtml_Quote_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('quote_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle('View Product Quote');
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_quote', array(
            'label'     => "View Quote",
            'content'   => $this->getLayout()->createBlock('supplier/adminhtml_quote_edit_tab_form')->toHtml(),
        ));


        return parent::_beforeToHtml();
    }
}