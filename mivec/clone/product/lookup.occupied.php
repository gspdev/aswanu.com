<?php
require 'config.php';
/**
 * get product data from category/data/occupied.csv
 */
$fp = fopen(__DATA_PATH__ . __FILE_OCCUPIED__ , "wb");
$header = array("source_id" , "source_sku" , "source_category_id" , "target_id" , "target_sku" , "target_category_id");
fputcsv($fp , $header);

$_categoryIds = getCategoryIds();

$sql = "
    SELECT DISTINCT(product_id),category_id FROM ".__DB_SOURCE__ . ".". __TABLE_CATEGORY_PRODUCT__.
    " WHERE `category_id` IN (".arrayToStr($_categoryIds).")";

if ($row = db()->fetchAll($sql)) {
    foreach ($row as $rs) {
        $_categoryId = $rs['category_id'];
        $_productId = $rs['product_id'];

        //get source product data
        $_sourceData = getProductData($_productId ,"name", __DB_SOURCE__);

        //get target product data
        $_targetData = getProductData($_productId , "name" , __DB_TARGET__);
        $_targetData['category_id'] = getAssociationCategoryIds($_targetData['entity_id']);

        $data = array(
            $_productId , $_sourceData['sku'] , $_categoryId ,
            $_targetData['entity_id'] ,$_targetData['sku'] , arrayToStr($_targetData['category_id'] , "/")
        );
        //print_r($data);exit;

        if (fputcsv($fp , $data)) {
            echo $_productId . " export suscess<br>";
            usleep(5);
        }
    }
}
fclose($fp);

