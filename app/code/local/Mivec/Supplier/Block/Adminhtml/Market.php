<?php
class Mivec_Supplier_Block_Adminhtml_Market extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = "adminhtml_market";
        $this->_blockGroup = "supplier";
        $this->_headerText = "Market List";
        parent::__construct();
    }
}