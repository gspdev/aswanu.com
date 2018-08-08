<?php
require dirname(dirname(__FILE__)) . "/config.php";
define("__DATA_PATH__" , dirname(__FILE__) . "/data/");

define("__PARENT_CATEGORY_ID__" , 500);

define("__FILE_OCCUPIED__" , "occupied.csv");
define("__FILE_BACKUP__" , "backup.category.csv");

function getCategoryData($_id , $_attributeId = "", $_database)
{
    $sql = "
        SELECT A.*,B.* FROM $_database ." .__TABLE_CATEGORY__." A
            LEFT JOIN $_database . ".__TABLE_CATEGORY_VARCHAR__." B
            ON A.entity_id = B.entity_id
        WHERE A.entity_id = $_id
    ";

    if (!empty($_attributeId)) {
        $sql .= " AND b.attribute_id=" . $_attributeId;
        return db()->fetchRow($sql);
    }

    return db()->fetchAll($sql);
}


function getCategoryMetaData($_categoryId , $_type)
{
    $_attributeId = "";
    $_table = "";
    switch($_type) {
        case "title" :
            $_attributeId = __ATTR_CATEGORY_TITLE__;
            $_table = __TABLE_CATEGORY_VARCHAR__;
            break;
        case "url_key" :
            $_attributeId = __ATTR_CATEGORY_URL_KEY__;
            $_table = __TABLE_CATEGORY_VARCHAR__;
            break;
        case "desc" :
            $_attributeId = __ATTR_CATEGORY_DESC__;
            $_table = __TABLE_CATEGORY_TEXT__;
            break;
        case "meta_title":
            $_attributeId = __ATTR_CATEGORY_META_TITLE__;
            $_table = __TABLE_CATEGORY_VARCHAR__;
            break;
        case "meta_keywords":
            $_attributeId = __ATTR_CATEGORY_META_KEYWORD__;
            $_table = __TABLE_CATEGORY_TEXT__;
            break;
        case "meta_desc":
            $_attributeId = __ATTR_CATEGORY_META_DESC__;
            $_table = __TABLE_CATEGORY_TEXT__;
            break;
    }

    $sql = "SELECT * FROM `$_table`
        WHERE attribute_id = $_attributeId
            AND entity_id = $_categoryId";
    return db()->fetchRow($sql);
}

function getAssociateProductIds($_categoryId)
{
    $sql = "SELECT * FROM " . __TABLE_CATEGORY_PRODUCT__
        ." WHERE category_id = " . $_categoryId;
    if ($row = db()->fetchAll($sql)) {
        $data = array();
        foreach ($row as $rs) {
            $data[] = $rs['product_id'];
        }
        return $data;
    }
}