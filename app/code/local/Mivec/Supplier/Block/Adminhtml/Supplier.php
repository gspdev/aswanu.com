<?php
class Mivec_Supplier_Block_Adminhtml_Supplier extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_constroller = "adminhtml_supplier";
        $this->_blockGroup = "supplier";
        $this->_headerText = "Supplier List";
        parent::__construct();
    }

}