<?php
require 'config.php';

/**
 *  create new category data from backup file.
 */
define("__DATA_ENTITY_TYPE_ID__" , 3);
define("__DATA_ATTRIBUTE_SET_ID__" , 3);
define("__DATA_STORE_ID__" , 0);

define("__VAR_SEPARATOR__" , "/");

if ($content = getCsvContent(__DATA_PATH__ . __FILE_BACKUP__)) {
    $i = 0;
    foreach ($content as $rs) {
        $_categoryId = $rs[0];
        $title = $rs[1] . "-mivec";
        $parent_id  = $rs[2];
        $path = $rs[3];
        $level = $rs[4];
        $url_key = $rs[5];
        $desc = $rs[6];

        $meta['title'] = $rs[7];
        $meta['keywords'] = $rs[8];
        $meta['desc'] = $rs[9];
        $productIds = $rs[10];

        //main table
        $data["parent_id"] = $parent_id;
        $data['level'] = $level;
        if ($_SESSION['category_id'] = insertMainTable($data)) {

            //update path
            $pathData['path'] = replacePath($path , $_SESSION['category_id']);
            updatePath($_SESSION['category_id'] , $pathData);

            //set as active
            setActive($_SESSION['category_id'] , "active");
            setActive($_SESSION['category_id'] , "show");

            /* additional info */
            $titleData['value'] = $title;
            $titleData['entity_type_id'] = __DATA_ENTITY_TYPE_ID__;
            $titleData['attribute_id'] = __ATTR_CATEGORY_TITLE__;
            $titleData['store_id'] = __DATA_STORE_ID__;
            $titleData['entity_id'] = $_SESSION['category_id'];
            updateAdditionalInfo($titleData , "title");

            //url
            $urlData['value'] = $url_key;
            $urlData['entity_type_id'] = __DATA_ENTITY_TYPE_ID__;
            $urlData['attribute_id'] = __ATTR_CATEGORY_URL_KEY__;
            $urlData['store_id'] = __DATA_STORE_ID__;
            $urlData['entity_id'] = $_SESSION['category_id'];
            updateAdditionalInfo($urlData , "url_key");

            //desc
            $descData['value'] = $desc;
            $descData['entity_type_id'] = __DATA_ENTITY_TYPE_ID__;
            $descData['attribute_id'] = __ATTR_CATEGORY_DESC__;
            $descData['store_id'] = __DATA_STORE_ID__;
            $descData['entity_id'] = $_SESSION['category_id'];
            updateAdditionalInfo($descData , "desc");

            //meta
            $metaTitle['value'] = $meta['title'];
            $metaTitle['entity_type_id'] = __DATA_ENTITY_TYPE_ID__;
            $metaTitle['attribute_id'] = __ATTR_CATEGORY_META_TITLE__;
            $metaTitle['store_id'] = __DATA_STORE_ID__;
            $metaTitle['entity_id'] = $_SESSION['category_id'];
            updateAdditionalInfo($metaTitle , "meta_title");

            //meta keywords
            $metaKeywords['value'] = $meta['keywords'];
            $metaKeywords['entity_type_id'] = __DATA_ENTITY_TYPE_ID__;
            $metaKeywords['attribute_id'] = __ATTR_CATEGORY_META_KEYWORD__;
            $metaKeywords['store_id'] = __DATA_STORE_ID__;
            $metaKeywords['entity_id'] = $_SESSION['category_id'];
            updateAdditionalInfo($metaKeywords , "meta_keywords");

            //meta desc
            $metaDesc['value'] = $meta['desc'];
            $metaDesc['entity_type_id'] = __DATA_ENTITY_TYPE_ID__;
            $metaDesc['attribute_id'] = __ATTR_CATEGORY_META_DESC__;
            $metaDesc['store_id'] = __DATA_STORE_ID__;
            $metaDesc['entity_id'] = $_SESSION['category_id'];
            updateAdditionalInfo($metaDesc , "meta_desc");

            //association products
            if (!empty($productIds)) {
                updateAssociationProduct($_SESSION['category_id'] , strToArray($productIds , __VAR_SEPARATOR__));
            }

            echo $_SESSION['category_id'] . " was successfully create <br>";
            usleep(10);
        }

        //exit;
        $i++;
    }
}
function replacePath($_path , $_categoryId)
{
    //update path value
    $arr = strToArray($_path , __VAR_SEPARATOR__);
    $arr[count($arr) - 1] = $_categoryId;
    return arrayToStr($arr , __VAR_SEPARATOR__);
}

function insertMainTable($data)
{
    //$data['entity_id'] = $_categoryId;
    $data['entity_type_id'] = __DATA_ENTITY_TYPE_ID__;
    $data['attribute_set_id'] = __DATA_ATTRIBUTE_SET_ID__;
    $data['created_at'] = date("Y-m-d H:i:s");
    $data['position'] = 1;

    try {
        if (db()->insert(__TABLE_CATEGORY__ , $data)) {
            return db()->lastInsertId();
        }
    } catch (Exception $e) {
        die ($e->getMessage());
    }
}

function updatePath($_categoryId , $data)
{
    //update `path` value in main table
    if (db()->update(__TABLE_CATEGORY__ , $data , "entity_id=" . $_categoryId)) {
        return true;
    }
    return false;
}

function updateAssociationProduct($_categoryId , array $_productIds)
{
    if (is_array($_productIds)) {
        $_status = false;
        foreach ($_productIds as $_productId) {
            //insert table
            $data['category_id'] = $_categoryId;
            $data['product_id'] = $_productId;
            $data['position'] = 1;
            try {
                if (db()->insert(__TABLE_CATEGORY_PRODUCT__ , $data)) {
                    $_status = true;
                }
            } catch (Exception $e) {
                die ($e->getCode() . ":" . $e->getMessage());
            }
        }
        return $_status;
    }
}

function setActive($_categoryId , $_type)
{
    $data['value'] = 1;
    $data['entity_id'] = $_categoryId;
    $data['store_id'] = __DATA_STORE_ID__;
    $data['entity_type_id'] = __DATA_ENTITY_TYPE_ID__;

    if ($_type == "active") {
        $data['attribute_id'] = __ATTR_CATEGORY_ACTIVE__;
    }
    if ($_type == 'show') {
        $data['attribute_id'] = __ATTR_CATEGORY_SHOW_IN_MENU__;
    }

    try {
        db()->insert(__TABLE_CATEGORY_INT__ , $data);
    } catch (Exception $e) {
        die ($e->getCode() . " : " . $e->getMessage());
    }
}

function updateAdditionalInfo($data , $_type)
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
    try {
        if (db()->insert($_table , $data)) {
            return true;
        }
    } catch (Exception $e) {
        die ($e->getMessage());
    }
    return false;
}