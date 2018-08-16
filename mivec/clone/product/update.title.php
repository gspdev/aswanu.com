<?php
require 'config.php';

define("__PREFIX__" , "For ");

if ($content = getCsvContent(__DATA_PATH__ . __FILE_SOURCE_EXPORT__)) {
    foreach ($content as $row) {
        $entity_id = $row[0];
        $_sku = $row[1];

        if (updateProductName($entity_id)) {
            echo $_sku . " update name succeed<br>";
            usleep(10);
        }
    }
}


function updateProductName($_entityId)
{
    $sql = "SELECT * FROM " . __TABLE_PRODUCT_VARCHAR__ . " WHERE attribute_id=" . __ATTR_PRODUCT_NAME__
        ." AND entity_id=" . $_entityId;

    $return = false;
    if ($row = db()->fetchRow($sql)) {
        $param = "value_id=" . $row['value_id'];
        $data['value'] = __PREFIX__ . $row["value"];
        try {
            if (db()->update(__TABLE_PRODUCT_VARCHAR__,$data , $param)) {
                $return = true;
            }
        } catch (Exception $e) {
            die ($e->getCode() . " " . $e->getMessage());
        }
    }
    return $return;
}