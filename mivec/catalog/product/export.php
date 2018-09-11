<?php
require 'config.php';

$file = "product_mobile_parts.csv";
$fp = fopen(__DATA_PATH__ . $file , "wb");
fputcsv($fp , array(
    "id" , "sku" , "name"
));

/*$_collection = Mage::getModel("catalog/product")
    ->getCollection()
    ->addAttributeToSelect(array("entity_id","sku" , "name"));
    //->addAttributeToFilter("status" , 1);*/

$sql = "SELECT a.entity_id,a.sku,b.category_id FROM " . __TABLE_PRODUCT__ . " a"
    ." LEFT JOIN " . __TABLE_CATEGORY_PRODUCT__ . " b"
    ." ON (a.entity_id=b.product_id)"
    ." WHERE category_id IN (".arrayToStr(getPartsCategory()).")"
    ." ORDER BY entity_id DESC";

echo $sql;

if ($row = db()->fetchAll($sql)) {
    foreach ($row as $rs) {
        $_productId = $rs["entity_id"];
        $_product = Mage::getModel("catalog/product")->load($_productId);

        $data = array($_productId , $_product->getSku() , $_product->getName());
        if (fputcsv($fp , $data)) {
            echo $data[1] . " was export succeed<br>";
            usleep(10);
        }
    }
}

fclose($fp);


/*
 * @param $type  mobile or tablet
 */

function getPartsCategory($type = "")
{
    $parent_id = 90;
    if ($type == 'tablet') {
        $parent_id = 92;
    }

    $sql = "SELECT * FROM " . __TABLE_CATEGORY__
        ." WHERE `path` LIKE '%$parent_id%'"
        ." AND `level` > 2";

    if ($row = db()->fetchAll($sql)) {
        $data = array();
        foreach ($row as $rs) {
            $data[] = $rs['entity_id'];
        }
        return $data;
    }
}