<?php
class Mivec_Supplier_Block_Adminhtml_Quote_Renderer_Product_Sku extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $_product = Mage::getModel("catalog/product")->load($row->getProductId());
        $html = "";
        if ($_product->getId()) {
            $html .= '<a title="view product" href="'.$this->getUrl('admin/catalog_product/edit/', array('id' => $_product->getId())).'">'.$_product->getSku().'</a>';
        }
        return $html;
    }
}