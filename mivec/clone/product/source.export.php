<?php
require 'config.php';
if ($content = getCsvContent(__DATA_PATH__ . __FILE_OCCUPIED__)) {
    $fp = fopen(__DATA_PATH__ . __FILE_SOURCE_EXPORT__ , "wb");
    $header =
        array(
            "id" , "sku" ,"entity_type_id" ,"attribute_set_id", "name"
            ,"weight" , "status" , "visibility"
            ,"price" , "tax" , "category_ids" , "media"
            ,"short_desc", "desc"
        );
    fputcsv($fp , $header);

    foreach ($content as $row) {
        $_productId = $row[0];
        $_sku = $row[1];
        $_categoryIds = $row[2];

        if (!empty($_productId)) {
            //get product
            $_productData = getProductData($_productId , "name" , __DB_SOURCE__);
            //print_r($_productData);exit;
            $data['id'] = $_productId;
            $data['sku'] = $_productData['sku'];
            $data['entity_type_id'] = $_productData['entity_type_id'];
            $data['attribute_set_id'] = $_productData['attribute_set_id'];
            $data['name'] = $_productData['value'];

            //weight
            $data['weight'] = getProductData($_productId , "weight", __DB_SOURCE__)['value'];
            //status
            $data['status'] = getProductData($_productId , "status", __DB_SOURCE__)['value'];
            //visibility
            $data['visibility'] = getProductData($_productId , "visibility", __DB_SOURCE__)['value'];

            $data['price'] = getProductData($_productId,'price', __DB_SOURCE__)['value'];
            //$data['tax'] = getProductData($_productId , "tax", __DB_SOURCE__)["value"];
            $data['tax'] = 0;
            //category_ids
            $data['category_ids'] = arrayToStr(getAssociationCategoryIds($_productId, __DB_SOURCE__) , ";");
            //media
            $data['media'] = arrayToStr(getProductMedia($_productId , __DB_SOURCE__) , ";");
            //desc
            $data['short_desc'] = getProductData($_productId , "short_desc", __DB_SOURCE__)['value'];
            $data['desc'] = getProductData($_productId , "desc", __DB_SOURCE__)['value'];

            if (fputcsv($fp , $data)) {
                echo $data['sku'] . " was success to export.<br>";
                usleep(10);
            }
        }
    }
    fclose($fp);
}