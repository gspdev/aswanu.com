<?php
class Mivec_Supplier_Block_Adminhtml_Quote_Renderer_Product_Name extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $_product = Mage::getModel("catalog/product")->load($row->getProductId());
        $html = "";
        if ($_product->getId()) {
            $html .= $_product->getName();
        }
        return $html;
    }
}