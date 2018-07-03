<?php
require 'config.php';

//found out & export that product name contain `LCD` or `display`

$_keywords = "LCD";
//values LCD&display=1000,back cover=999,battery=998,flex=997
$_value = 1000;

/*$collection = Mage::getModel("catalog/product")
    ->getCollection()
    ->addAttributeToSelect(
        array("entity_id" , "sku" , "name")
    )
    ->addAttributeToFilter("name" , array("like"    => "%$_keywords%"))
    ->setOrder("entity_id" , "DESC");
echo $collection->count();*/

$sql = "
SELECT a.entity_id,a.sku,b.`value` AS `name` 
    FROM catalog_product_entity a LEFT JOIN catalog_product_entity_varchar b 
    ON(a.`entity_id`=b.entity_id)
    WHERE attribute_id=71
    AND (`value` LIKE '%$_keywords%')"
;
echo $sql;


if ($row = db()->fetchAll($sql)) {
        $_index = 0;
        foreach ($row as $rs) {
            $_id = $rs['entity_id'];
            $_sku = $rs['sku'];

            try {
                if (updateSort($_id , $_value)) {
                    echo $_sku . " update succeed</p>";
                    usleep(10);
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            }

            $index++;
        }
}

function updateSort($entityId , $_value)
{
    $sql = "REPLACE INTO " . __TABLE_ATTRIBUTE_INT__
        ." (`value`,entity_id,attribute_id)"
        ." VALUES ($_value , $entityId , ".__ATTR_PRODUCT_SORT_ID__.")"
    ;

    return db()->query($sql);
}

function getSortIfExist($entityId)
{
    $sql = "SELECT (*) FROM " . __TABLE_ATTRIBUTE_INT__
        . " WHERE attribute_id=" . __ATTR_PRODUCT_SORT_ID__
        . " AND entity_id=" . $entityId;

    return db()->fetchOne($sql);
}