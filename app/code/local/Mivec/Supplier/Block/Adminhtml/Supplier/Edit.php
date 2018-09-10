<?php
class Mivec_Supplier_Block_Adminhtml_Supplier_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function _construct()
    {
        parent::_construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'supplier';
        $this->_controller = 'adminhtml_supplier';

        $this->_updateButton('save', 'label', 'Save');
        $this->_updateButton('delete', 'label', 'Delete');

        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if(Mage::registry('supplier_data') && Mage::registry('supplier_data')->getId() ) {
            return Mage::helper('supplier')->__("Edit Supplier '%s'" , "");
        } else {
            return "";
        }
    }
}