<?php
class Mivec_Supplier_Block_Adminhtml_Quote_Renderer_Supplier extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $_supplier = Mage::getModel("supplier/supplier")->load($row->getSupplierId());
        $html = "";
        if ($_supplier->getId()) {
            $html .= '<a title="view Supplier" href="'.$this->getUrl('supplier/adminhtml_supplier/edit', array('id' => $_supplier->getId())).'">'.$_supplier->getCompanyName().'</a>';
        }
        return $html;
    }
}