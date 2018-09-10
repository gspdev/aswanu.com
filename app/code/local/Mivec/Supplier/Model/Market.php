<?php
class Mivec_Supplier_Model_Market extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init("supplier/market");
    }
}