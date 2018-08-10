<?php
require 'config.php';
define("__DATA_ENTITY_TYPE_ID__" , 4);
define("__DATA_STORE_ID__" , 0);

$baseCode = Mage::app()->getBaseCurrencyCode();
$allowedCurrencies = Mage::getModel('directory/currency')->getConfigAllowCurrencies();
$rates = Mage::getModel('directory/currency')->getCurrencyRates($baseCode, "SEK");
//print_r($rates);exit;

if ($content = getCsvContent(__DATA_PATH__ . __FILE_SOURCE_EXPORT__)) {
    $i=0;
    foreach ($content as $row) {
        $_productId = $row[0];
        $_sku = $row[1];
        //price
        $_price = number_format((getPrice($_productId) / $rates['SEK']) , 2);
        if (!empty($_price) && updatePrice($_productId , $_price)) {
            echo $_sku  . " update succeed<br>";
            usleep(10);
        }
        $i++;
    }
}

function getPrice($_entityId)
{
    $sql = "SELECT value FROM " . __TABLE_PRODUCT_DECIMAL__
        ." WHERE entity_id=" . $_entityId
        ." AND attribute_id=" . __ATTR_PRODUCT_PRICE__;
    return db()->fetchOne($sql);
}

function updatePrice($_entityId , $_price)
{
    $sql = "UPDATE ".__TABLE_PRODUCT_DECIMAL__." SET `value`=" . $_price
        ." WHERE entity_id=" . $_entityId
        ." AND attribute_id=" . __ATTR_PRODUCT_PRICE__;
    //echo $sql;exit;

    try {
        if (db()->query($sql)) {
            return true;
        }
    } catch (Exception $e) {
        die ($e->getCode() . ":" . $e->getMessage());
    }
}