<?php
require 'config.php';

/**
 * delete specific data from csv file
 */

if ($content = getCsvContent(__DATA_PATH__ . __FILE_BACKUP__)) {
    foreach ($content as $rs) {
        $_categoryId = $rs[0];
        $_level = $rs[4];

        if ($_level > __CATEGORY_LIMIT_LEVEL__) {
            deleteCategory($_categoryId);
            usleep(10);
        }
    }
}

function deleteCategory($_categoryId) {
    try {
        if (db()->delete(__TABLE_CATEGORY__ , "entity_id=$_categoryId")) {
            echo $_categoryId . " was delete.<br>";
        }
    } catch (Exception $e) {
        die ($e->getMessage());
    }
}