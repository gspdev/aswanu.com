<?php

class Cloudinary_Cloudinary_Model_Resource_Transformation_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('cloudinary_cloudinary/transformation');
    }
}
