<?php
require dirname(dirname(__FILE__)) . "/config.php";

define("__DATA_PATH__" , dirname(__FILE__) . "/data/");

define("__FILE_SOURCE_EXPORT__" , "source.product.csv");

define("__FILE_OCCUPIED__" , "occupied.csv");
define("__FILE_BACKUP__" , "backup.product.csv");


define("__DATA_PRODUCT_VISIBILITY__" , 4);
define("__DATA_PRODUCT_STOCK_QTY__" , 1000);
define("__DATA_PRODUCT_STOCK_STATUS__" , 1);
define("__DATA_PRODUCT_MEDIA_ATTRIBUTE_ID__" , 88);

function hasProduct($_entityId , $_database = __DB_TARGET__)
{
    $sql = "SELECT COUNT(*) FROM $_database." .__TABLE_PRODUCT__
        ." WHERE entity_id=" . $_entityId;

    return db()->fetchOne($sql);
}

function getProduct($_entityId , $_database)
{
    $sql = "SELECT * FROM `$_database`." . __TABLE_CATALOG_PRODUCT__ . "
        WHERE 1
        AND `entity_id` = " . $_entityId;
    return db()->fetchRow($sql);
}

function setProductTableAndAttributeId($_type)
{
    switch($_type) {
        case "name":
            $sets['table'] = __TABLE_PRODUCT_VARCHAR__;
            $sets['attribute_id'] = __ATTR_PRODUCT_NAME__;
            break;
        case "desc":
            $sets['table'] = __TABLE_PRODUCT_TEXT__;
            $sets['attribute_id'] = __ATTR_PRODUCT_DESC__;
            break;
        case "short_desc":
            $sets['table'] = __TABLE_PRODUCT_TEXT__;
            $sets['attribute_id'] = __ATTR_PRODUCT_SHORT_DESC__;
            break;
        case "meta_title":
            $sets['table'] = __TABLE_PRODUCT_VARCHAR__;
            $sets['attribute_id'] = __ATTR_PRODUCT_META_TITLE__;
            break;
        case "meta_keyword":
            $sets['table'] = __TABLE_PRODUCT_TEXT__;
            $sets['attribute_id'] = __ATTR_PRODUCT_META_KEYWORD__;
            break;
        case "meta_desc":
            $sets['table'] = __TABLE_PRODUCT_VARCHAR__;
            $sets['attribute_id'] = __ATTR_PRODUCT_META_DESC__;
            break;
        case "weight":
            $sets['table'] = __TABLE_PRODUCT_DECIMAL__;
            $sets['attribute_id'] = __ATTR_PRODUCT_WEIGHT__;
            break;
        case "status":
            $sets['table'] = __TABLE_PRODUCT_INT__;
            $sets['attribute_id'] = __ATTR_PRODUCT_STATUS__;
            break;
        case "visibility":
            $sets['table'] = __TABLE_PRODUCT_INT__;
            $sets['attribute_id'] = __ATTR_PRODUCT_VISIBILITY__;
            break;
        case "part_type":
            $sets['table'] = __TABLE_PRODUCT_INT__;
            $sets['attribute_id'] = __ATTR_PRODUCT_MOBILE_PART_TYPE__;
            break;
        case "price":
            $sets['table'] = __TABLE_PRODUCT_DECIMAL__;
            $sets['attribute_id'] = __ATTR_PRODUCT_PRICE__;
            break;
        case "tax":
            $sets['table'] = __TABLE_PRODUCT_INT__;
            $sets['attribute_id'] = __ATTR_PRODUCT_TAX__;
            break;

        case "media":
            $sets['table'] = __TABLE_PRODUCT_MEDIA__;
            $sets['attribute_id'] = __ATTR_PRODUCT_MEDIA__;
            break;
    }
    return $sets;
}

function getProductData($_entityId , $_type , $_database = __DB_TARGET__)
{
    $_sets = setProductTableAndAttributeId($_type);
    $sql = "SELECT a.*,b.*
        FROM $_database." . __TABLE_PRODUCT__ . " a
            LEFT JOIN $_database." . $_sets['table'] . " b
            ON (a.entity_id = b.entity_id)
        WHERE 1 AND `attribute_id` = ".$_sets['attribute_id']
            ." AND a.entity_id = $_entityId";

    return db()->fetchRow($sql);
}

function getProductMedia($_productId , $_database = __DB_TARGET__)
{
    if (!empty($_productId)) {
        $sql = "SELECT * FROM $_database." . __TABLE_PRODUCT_MEDIA__
            ." WHERE entity_id = " . $_productId;
        if ($row = db()->fetchAll($sql)) {
            $data = array();
            foreach ($row as $rs) {
                $data[] = $rs['value'];
            }
            return $data;
        }
        return false;
    }
}

function getAssociationCategoryIds($_productId , $_database = __DB_TARGET__)
{
    if (!empty($_productId)) {
        $sql = "
            SELECT * FROM $_database.".__TABLE_CATEGORY_PRODUCT__."
            WHERE product_id = $_productId
        ";

        if ($row = db()->fetchAll($sql)) {
            $data = array();
            foreach ($row as $rs) {
                $data[] = $rs['category_id'];
            }
            return $data;
        }
        return false;
    }
}

function setMainData($data)
{
    $data['type_id'] = 'simple';
    $data['has_options'] = 0;
    $data['required_options'] = 0;
    $data['created_at'] = date("Y-m-d H:i:s");
    try {
        if (db()->insert(__TABLE_PRODUCT__ , $data)) {
            return db()->lastInsertId();
        }
    } catch (Exception $e) {
        die ($e->getMessage());
    }
}

function setAdditionalData($_value , $_type , $_productId = "")
{
    $_sets = setProductTableAndAttributeId($_type);

    $data['entity_id'] = !empty($_SESSION['product_id']) ? $_SESSION['product_id'] : $_productId;
    $data['entity_type_id'] = $_SESSION['entity_type_id'];
    $data['attribute_id'] = $_sets['attribute_id'];
    $data['store_id'] = 0;
    $data['value'] = $_value;

    try {
        if (db()->insert($_sets['table'] , $data)) {
            unset($data);
            return true;
        }
    } catch (Exception $e) {
        die ($e->getTrace() . $e->getCode() . ":" . $e->getMessage());
    }
    return false;
}

function setAssociationCategory($_productId , array $_categoryIds)
{
    $i = 0;
    $_status = FALSE;
    foreach ($_categoryIds as $_categoryId) {
        $data['position'] = 1;
        $data['product_id'] = $_productId;
        $data['category_id'] = $_categoryId;
        try {
            if (db()->insert(__TABLE_CATEGORY_PRODUCT__ , $data)) {
                $_status = TRUE;
            }
        } catch (Exception $e) {
            die ($e->getCode() . ":" . $e->getMessage());
        }
        $i++;
    }
    return $_status;
}

function setStock($_productId)
{
    $data['product_id'] = $_productId;
    $data['stock_id'] = 1;
    $data['qty'] = __DATA_PRODUCT_STOCK_QTY__;
    $data['is_in_stock'] = 1;
    try {
        if (db()->insert(__TABLE_PRODUCT_STOCK_ITEM__ , $data)) {
            //stock status
            $statusData['product_id'] = $_productId;
            $statusData['website_id'] = 1;
            $statusData['stock_id'] = 1;
            $statusData['qty'] = __DATA_PRODUCT_STOCK_QTY__;
            $statusData['stock_status'] = __DATA_PRODUCT_STOCK_STATUS__;
            return db()->insert(__TABLE_PRODUCT_STOCK_STATUS__ , $statusData);
        }
    } catch (Exception $e) {
        die ($e->getCode() . ":" . $e->getMessage());
    }
}

function setMedia($_productId , array $_medias)
{
    $i = 0;
    $_status = FALSE;
    foreach ($_medias as $_media) {
        $data['attribute_id'] = __DATA_PRODUCT_MEDIA_ATTRIBUTE_ID__;
        $data['entity_id'] = $_productId;
        $data['value'] = $_media;

        try {
            if (db()->insert(__TABLE_PRODUCT_MEDIA__ , $data)) {
                $_status = TRUE;
            }
        } catch (Exception $e) {
            die($e->getCode() . ":" . $e->getMessage());
        }
        $i++;
    }
    return $_status;
}

function setWebsite($_productId)
{
    $data['product_id'] = $_productId;
    $data['website_id'] = 1;
    if (db()->insert(__TABLE_PRODUCT_WEBSITE__ , $data)) {
        return true;
    }
    return false;
}