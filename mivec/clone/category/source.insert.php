<?php
//insert data from g-sp_se;
require 'config.php';


$_status = FALSE;
if ($_categoryId = getCategoryIds()) {

    //main table
    $_table['source'] = __DB_SOURCE__ . ".";
    $_table['target'] = __DB_TARGET__ . ".";
    $_mainTable['fields'] = array("entity_id" , "entity_type_id" , "attribute_set_id" , "parent_id" , "created_at" , "updated_at" , "`path`" , "`position`" , "`level`" , "children_count");
    $_mainTable['table']['source'] = $_table['source'].__TABLE_CATEGORY__;
    $_mainTable['table']['target'] = $_table['target'].__TABLE_CATEGORY__;
    $_status = insertDataToTable($_categoryId , $_mainTable['fields'] , $_mainTable['table']['source'] , $_mainTable['table']['target']);

    //int
    $_intTable['fields'] = array("entity_type_id" , "attribute_id" , "store_id" , "entity_id" , "`value`");
    $_intTable['table']['source'] = $_table['source'].__TABLE_CATEGORY_INT__;
    $_intTable['table']['target'] = $_table['target'].__TABLE_CATEGORY_INT__;
    $_status = insertDataToTable($_categoryId , $_intTable['fields'] , $_intTable['table']['source'] , $_intTable['table']['target']);

    //varchar
    $_varcharTable['fields'] = $_intTable['fields'];
    $_varcharTable['table']['source'] = $_table['source'].__TABLE_CATEGORY_VARCHAR__;
    $_varcharTable['table']['target'] = $_table['target'].__TABLE_CATEGORY_VARCHAR__;
    $_status = insertDataToTable($_categoryId , $_varcharTable['fields'] , $_varcharTable['table']['source'] , $_varcharTable['table']['target']);

    //text
    $_textTable['fields'] = $_intTable['fields'];
    $_textTable['table']['source'] = $_table['source'].__TABLE_CATEGORY_TEXT__;
    $_textTable['table']['target'] = $_table['target'].__TABLE_CATEGORY_TEXT__;
    $_status = insertDataToTable($_categoryId , $_textTable['fields'] , $_textTable['table']['source'] , $_textTable['table']['target']);

    if ($_status) {
        print_r($_categoryId) . " was success to insert.";
    }
}

function insertDataToTable(array $_categoryIds, array $_fields , $_source , $_target)
{
    if (is_array($_categoryIds)) {
        //main table
        $sql = "INSERT INTO $_target (".arrayToStr($_fields).")
        SELECT ".arrayToStr($_fields)." FROM " . $_source
        . " WHERE entity_id IN(" . arrayToStr($_categoryIds).")";

        try {
            if (db()->query($sql)) {
                return true;
            }
        } catch (Exception $e) {
            die ($e->getCode() . ":" . $e->getMessage());
        }
    }
}