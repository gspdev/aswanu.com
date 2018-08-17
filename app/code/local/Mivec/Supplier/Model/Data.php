<?php
class Mivec_Sullpier_Model_Data extends Mage_Core_Model_Abstract
{
    const SUPPLIER_VALIDATE = 1;
    const SUPPLIER_UNVALIDATE = 0;

    static function getIsValidate()
    {
        return array(
            SUPPLIER_UNVALIDATE   => 'No',
            SUPPLIER_VALIDATE   => 'Yes'
        );
    }

}