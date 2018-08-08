<?php
require 'config.php';
if ($content = getCsvContent(__DATA_PATH__ . __FILE_OCCUPIED__)) {
    $fp = fopen(__DATA_PATH__ . __FILE_BACKUP__ , "wb");
    $header =
        array(
            "id" , "sku" ,"entity_type_id" ,"attribute_set_id", "name" , "weight" , "status" , "visibility" , "part_type"
            ,"price" , "tax" , "category_ids" , "media" ,"short_desc", "desc" , "meta_title" , "meta_keyword" , "meta_desc"
        );
    fputcsv($fp , $header);

    foreach ($content as $row) {
        $_productId = $row[3];
        $_sku = $row[4];
        $_categoryIds = $row[5];

        if (!empty($_productId)) {
            //get product
            $_productData = getProductData($_productId , "name");
            //print_r($_productData);exit;
            $data['id'] = $_productId;
            $data['sku'] = $_productData['sku'];
            $data['entity_type_id'] = $_productData['entity_type_id'];
            $data['attribute_set_id'] = $_productData['attribute_set_id'];
            $data['name'] = $_productData['value'];

            //weight
            $data['weight'] = getProductData($_productId , "weight")['value'];
            //status
            $data['status'] = getProductData($_productId , "status")['value'];
            //visibility
            $data['visibility'] = getProductData($_productId , "visibility")['value'];
            $data['part_type'] = getProductData($_productId , "part_type")['value'];
            $data['price'] = getProductData($_productId,'price')['value'];
            $data['tax'] = getProductData($_productId , "tax")["value"];
            //category_ids
            $data['category_ids'] = arrayToStr(getAssociationCategoryIds($_productId) , ";");
            //media
            $data['media'] = arrayToStr(getProductMedia($_productId) , ";");

            //desc
            $data['short_desc'] = getProductData($_productId , "short_desc")['value'];
            $data['desc'] = getProductData($_productId , "desc")['value'];

            //meta data
            $data['meta_title'] = getProductData($_productId , "meta_title")["value"];
            $data['meta_keyword'] = getProductData($_productId , "meta_keyword")["value"];
            $data['meta_desc'] = getProductData($_productId , "meta_desc")["value"];

            if (fputcsv($fp , $data)) {
                echo $data['sku'] . " was success to export.<br>";
                usleep(10);
            }
        }
    }
    fclose($fp);
}