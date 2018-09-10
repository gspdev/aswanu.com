<?php
class Mivec_Supplier_Helper_Market extends Mage_Core_Helper_Abstract
{
    public function getMarketCollection($_key = "", $_value = "")
    {
        $_collection = Mage::getModel('supplier/market')
            ->getCollection();

        if (is_array($_key)) {
            foreach ($_key as $i => $_field) {
                $_collection->addAttributeToFilter($_field , array("eq" => $_value[$i]));
            }
        }

        return $_collection;
    }

    public function getMarkets($_key = "", $_value = "")
    {
        if ($_collection = $this->getMarketCollection($_key , $_value)) {
            $data = array();
            foreach ($_collection->getItems() as $_item) {
                $data[$_item->getId()] = $_item->getName();
            }
            return $data;
        }
    }
}