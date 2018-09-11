<?php
require dirname(dirname(__FILE__)) . '/config.php';

define("__DATA_PATH__" , dirname(__FILE__) . "/data/");

$app = Mage::app();
$db = Mage::getSingleton('core/resource')
        ->getConnection('core_read');


define("__TABLE_CATEGORY__" , "catalog_category_entity");
define("__TABLE_CATEGORY_PRODUCT__" , "catalog_category_product");
define("__TABLE_PRODUCT__" , "catalog_product_entity");
