<?php
class Mivec_Supplier_Block_Adminhtml_Quote_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function _construct()
    {
        parent::_construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'supplier';
        $this->_controller = 'adminhtml_quote';

        //$this->_updateButton('save', 'label', 'Save');
        //$this->_updateButton('delete', 'label', 'Delete');
    }

    public function getHeaderText()
    {
        if(Mage::registry('quote_data') && Mage::registry('quote_data')->getId() ) {
            return Mage::helper('supplier')->__("View Quote '%s'" , "");
        } else {
            return "";
        }
    }
}