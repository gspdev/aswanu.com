<?php
class Mivec_Supplier_Model_Mysql4_Market extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init("supplier/market" , "id");
    }
}