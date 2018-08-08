<?php
require 'config.php';
/**
 *  create new product data from backup file.
 */

if ($content = getCsvContent(__DATA_PATH__ . __FILE_BACKUP__)) {

    $i = 0;
    foreach ($content as $row) {
        $status = FALSE;

        $_productId = $row[0];
        $_sku = $row[1];
        $_entity_type_id = $row[2];
        $_attribute_set_id = $row[3];
        $_name = $row[4];
        $_weight = $row[5];
        $_status = $row[6];
        $_visibility = __DATA_PRODUCT_VISIBILITY__;
        $_part_type = $row[8];
        $_price = $row[9];
        $_tax = $row[10];
        $_short_desc = $row[13];
        $_desc = $row[14];

        //meta
        $_meta_title = $row[15];
        $_meta_keyword = $row[116];
        $_meta_desc = $row[17];

        $_categoryIds = $row[11];
        $_media = $row[12];

        //main table
        $data['sku'] = $_sku;
        $data['entity_type_id'] = $_entity_type_id;
        $data['attribute_set_id'] = $_attribute_set_id;
        if ($_SESSION['product_id'] = setMainData($data)) {
            $_SESSION['entity_type_id'] = $data['entity_type_id'];
            //website
            setWebsite($_SESSION['product_id']);
            //name
            setAdditionalData($_name , "name");
            //weight
            setAdditionalData($_weight , "weight");
            //status
            setAdditionalData($_status , "status");
            //visibility
            setAdditionalData($_visibility , "visibility");
            //part_type
            setAdditionalData($_part_type , "part_type");
            //price
            setAdditionalData($_price , "price");
            //tax
            setAdditionalData($_tax , "tax");
            //desc
            setAdditionalData($_desc , "short_desc");
            setAdditionalData($_desc , "desc");

            //meta data
            setAdditionalData($_meta_title , "meta_title");
            setAdditionalData($_meta_keyword , "meta_keyword");
            setAdditionalData($_meta_desc , "meta_desc");

            //media
            setMedia($_SESSION['product_id'] , strToArray($_media , ";"));
            //association category
            setAssociationCategory($_SESSION['product_id'] , strToArray($_categoryIds , ";"));

            //stock
            setStock($_SESSION['product_id']);

            echo $_sku . " was success to generation.<br>";
            usleep(10);
            //exit;
        }
        $i++;
    }
}
