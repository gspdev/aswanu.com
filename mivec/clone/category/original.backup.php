<?php
require 'config.php';

$fp = fopen(__DATA_PATH__ . __FILE_BACKUP__ , "wb");
$header = array("id" , "title" ,"parent_id","path" , "level" , "url_key" , "desc.", "meta_title","meta_keywords" ,"meta_desc.", "product_ids");
fputcsv($fp , $header);

if ($content = getCsvContent(__DATA_PATH__ . __FILE_OCCUPIED__)) {
    $i = 1;
    foreach ($content as $row) {
        $_categoryId = $row[4];
        $_title = $row[5];
        $_path = $row[6];
        $_level = $row[7];

        if (!empty($_categoryId)) {
            $data['id'] = $_categoryId;

            //get base info
            $_categoryData = getCategoryData($_categoryId ,__ATTR_CATEGORY_TITLE__, __DB_TARGET__);
            //print_r($_categoryData);exit;
            $data["title"] = $_categoryData['value'];
            $data['parent_id'] = $_categoryData['parent_id'];
            $data["path"]  = $row[6];
            $data['level'] = $row[7];

            //get more data
            $urlKey = getCategoryMetaData($_categoryId , "url_key");
            $data['url_key'] = $urlKey['value'];

            $desc = getCategoryMetaData($_categoryId , "desc");
            $data['desc'] = $desc['value'];

            $meta_title = getCategoryMetaData($_categoryId , "meta_title");
            $data['meta_title'] = $meta_title['value'];

            $meta_keywords = getCategoryMetaData($_categoryId , "meta_keywords");
            $data['meta_keywords'] = $meta_keywords['value'];

            $meta_desc = getCategoryMetaData($_categoryId , "meta_desc");
            $data['meta_desc'] = $meta_desc['value'];

            //associate product
            $assProduct = getAssociateProductIds($_categoryId);
            $data['product_ids'] = arrayToStr($assProduct , "/");

            if (fputcsv($fp , $data)) {
                echo "category : $_categoryId was export<br>";
                usleep(10);
            }
        }

        $i++;
    }
}

fclose($fp);