<?php
class Mivec_Supplier_Block_Adminhtml_Market_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('market_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle('Edit Market');
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => "Edit Market",
            'content'   => $this->getLayout()->createBlock('supplier/adminhtml_market_edit_tab_form')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}