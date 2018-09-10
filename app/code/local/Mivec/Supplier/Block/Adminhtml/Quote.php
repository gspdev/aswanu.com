<?php
class Mivec_Supplier_Block_Adminhtml_Quote extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = "adminhtml_quote";
        $this->_blockGroup = "supplier";
        $this->_headerText = "Product Quotes";
        parent::__construct();
    }
}