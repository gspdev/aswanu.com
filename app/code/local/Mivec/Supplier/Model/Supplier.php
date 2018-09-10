<?php
class Mivec_Supplier_Model_Supplier extends Mage_Core_Model_Abstract
{
    const SUPPLIER_VALIDATE = 1;
    const SUPPLIER_UNVALIDATED = 0;

    public function _construct()
    {
        parent::_construct();
        $this->_init("supplier/supplier");
    }

    static function getIsValidate()
    {
        return array(
            self::SUPPLIER_UNVALIDATED   => 'No',
            self::SUPPLIER_VALIDATE   => 'Yes'
        );
    }
}