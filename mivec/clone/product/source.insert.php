<?php
require 'config.php';

/*
 * copy product data from g-sp_se; ids in BACKUP_FILE
 * */
$_table['source'] = __DB_SOURCE__ . ".";
$_table['target'] = __DB_TARGET__ . ".";


if ($content = getCsvContent(__DATA_PATH__ . __FILE_SOURCE_EXPORT__)) {

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
        $_price = $row[8];
        $_tax = $row[9];
        $_categoryIds = $row[10];
        $_media = $row[11];
        $_short_desc = $row[12];
        $_desc = $row[13];

        $data['entity_id'] = $_productId;
        $data['sku'] = $_sku;
        $data['entity_type_id'] = $_entity_type_id;
        $data['attribute_set_id'] = $_attribute_set_id;
        if (!hasProduct($_productId)) {
            if (setMainData($data)) {
                $_SESSION['entity_type_id'] = $data['entity_type_id'];
                //name
                setAdditionalData($_name , "name" , $_productId);
                //weight
                setAdditionalData($_weight , "weight" , $_productId);
                //status
                setAdditionalData($_status , "status" , $_productId);
                //visibility
                setAdditionalData($_visibility , "visibility" , $_productId);
                //price
                setAdditionalData($_price , "price" , $_productId);
                //tax
                setAdditionalData($_tax , "tax" , $_productId);
                //desc
                setAdditionalData($_desc , "short_desc" , $_productId);
                setAdditionalData($_desc , "desc" , $_productId);

                //media
                setMedia($_productId , strToArray($_media , ";"));
                //association category
                setAssociationCategory($_productId , strToArray($_categoryIds , ";"));

                //stock
                setStock($_productId);
                //website
                setWebsite($_productId);

                echo $_sku . " was success to generation.<br>";
                usleep(10);
            }
            $i++;
        }
    }
}


/**
if ($_productIds = getProductIds()) {
    //$_productIds = arrayToStr($_productIds);
    $i = 1;
    foreach ($_productIds as $_productId) {
        $_mainTable['fields'] = array("entity_id" , "entity_type_id" , "attribute_set_id" , "type_id" , "sku" , "has_options" , "required_options" , "created_at" , "updated_at");
        $_mainTable['table']['source'] = $_table['source'] . __TABLE_PRODUCT__;
        $_mainTable['table']['target'] = $_table['target'] . __TABLE_PRODUCT__;
        insertDataToTable($_productId , $_mainTable['fields'] , $_mainTable['table']['source'] , $_mainTable['table']['target']);

        //int table
        $_addiTable['fields'] = array("value_id" , "entity_type_id" , "attribute_id" , "store_id" , "entity_id" , "value");
        $_intTable['table']['source'] = $_table['source'] . __TABLE_PRODUCT_INT__;
        $_intTable['table']['target'] = $_table['target'] . __TABLE_PRODUCT_INT__;
        insertDataToTable($_productId , $_addiTable['fields'] , $_intTable['table']['source'] , $_intTable['table']['target']);

        //decimal table
        $_decimalTable['table']['source'] = $_table['source'] . __TABLE_PRODUCT_DECIMAL__;
        $_decimalTable['table']['target'] = $_table['target'] . __TABLE_PRODUCT_DECIMAL__;
        insertDataToTable($_productId , $_addiTable['fields'] , $_decimalTable['table']['source'] , $_decimalTable['table']['target']);

        //varchar table
        $_varcharTable['table']['source'] = $_table['source'] . __TABLE_PRODUCT_VARCHAR__;
        $_varcharTable['table']['target'] = $_table['target'] . __TABLE_PRODUCT_VARCHAR__;
        insertDataToTable($_productId , $_addiTable['fields'] , $_varcharTable['table']['source'] , $_varcharTable['table']['target']);

        //text table
        $_textTable['table']['source'] = $_table['source'] . __TABLE_PRODUCT_TEXT__;
        $_textTable['table']['target'] = $_table['target'] . __TABLE_PRODUCT_TEXT__;
        insertDataToTable($_productId , $_addiTable['fields'] , $_textTable['table']['source'] , $_textTable['table']['target']);

        //media
        $_mediaTable['fields'] = array("value_id" , "attribute_id" , "entity_id" , "value");
        $_mediaTable['table']["source"] = $_table["source"] . __TABLE_PRODUCT_MEDIA__;
        $_mediaTable['table']["target"] = $_table["target"] . __TABLE_PRODUCT_MEDIA__;
        insertDataToTable($_productId , $_mediaTable['fields'] , $_mediaTable['table']['source'] , $_mediaTable['table']['target']);

        //set association category
        $_assocCategoryTable['fields'] = array("category_id" , "entity_id" , "'position'");
        $_assocCategoryTable["table"]["source"] = $_table["source"] . __TABLE_CATEGORY_PRODUCT__;
        $_assocCategoryTable["table"]["source"] = $_table["target"] . __TABLE_CATEGORY_PRODUCT__;
        insertDataToTable($_productId , $_assocCategoryTable['fields'] , $_assocCategoryTable['table']['source'] , $_assocCategoryTable['table']['target']);

        //stock
        //setStock($_productId);
        //website
        //setWebsite($_productId);

        usleep(10);
        $i++;
        exit;
    }
}

function insertDataToTable($_productId, array $_fields , $_source , $_target)
{
    $sql = "INSERT INTO $_target (".arrayToStr($_fields).")
    SELECT ".arrayToStr($_fields)." FROM " . $_source
        . " WHERE entity_id = $_productId;";
    echo $sql . "</p>";

    try {
        //if (db()->query($sql)) {
            return true;
        }
    } catch (Exception $e) {
        die ($e->getCode() . ":" . $e->getMessage());
    }
}
*/
