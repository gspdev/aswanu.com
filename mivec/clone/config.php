<?php
require dirname(dirname(__FILE__)) . '/include/config.php';

$app = Mage::app();
$db = Mage::getSingleton('core/resource')
    ->getConnection('core_read');

define("__DB_SOURCE__" , "`g-sp_se`");
define("__DB_TARGET__" , "`aswanu_com`");

define("__CATEGORY_ID_EXCLUDE__" , 230); //exclude category id
define("__CATEGORY_LIMIT_LEVEL__" , 3); //permission to delete if level >

define("__DATA_CATEGORY_PARENT_ID__" , 500);

//declare constant variable tables of category
define("__TABLE_CATEGORY__" , "catalog_category_entity");
define("__TABLE_CATEGORY_DATE__" , "catalog_category_entity_datetime");
define("__TABLE_CATEGORY_DECIMAL__" , "catalog_category_entity_decimal");
define("__TABLE_CATEGORY_INT__" , "catalog_category_entity_int");
define("__TABLE_CATEGORY_TEXT__" , "catalog_category_entity_text");
define("__TABLE_CATEGORY_VARCHAR__" , "catalog_category_entity_varchar");

//association product
define("__TABLE_CATEGORY_PRODUCT__" , "catalog_category_product");
//define("__TABLE_CATEGORY_CATALOG_INDEX__" , "catalog_category_product_index");

//product's main info. include title,desc.,price,images and etc.
define("__TABLE_PRODUCT__" , "catalog_product_entity");
define("__TABLE_PRODUCT_DATE__" , "catalog_product_entity_datetime");
define("__TABLE_PRODUCT_DECIMAL__" , "catalog_product_entity_decimal");
define("__TABLE_PRODUCT_PRICE_GROUP__" , "catalog_product_entity_group_price");
define("__TABLE_PRODUCT_INT__" , "catalog_product_entity_int");
define("__TABLE_PRODUCT_MEDIA__" , "catalog_product_entity_media_gallery");
define("__TABLE_PRODUCT_MEDIA_VALUE__" , "catalog_product_entity_media_gallery_value");
define("__TABLE_PRODUCT_TEXT__" , "catalog_product_entity_text");
define("__TABLE_PRODUCT_VARCHAR__" , "catalog_product_entity_varchar");
define("__TABLE_PRODUCT_WEBSITE__"  ,"catalog_product_website");

//define("__TABLE_CATALOG_PRODUCT_INDEX_EAV__" , "catalog_product_index_eav");
//define("__TABLE_CATALOG_PRODUCT_INDEX_EAV_IDX__" , "catalog_product_index_eav_idx");

//maybe is index of product's price.
//define("__TABLE_CATALOG_PRODUCT_PRICE_INDEX__" , "catalog_product_index_price");


//stock status of product
define("__TABLE_PRODUCT_STOCK_ITEM__" , "cataloginventory_stock_item");
define("__TABLE_PRODUCT_STOCK_STATUS__" , "cataloginventory_stock_status");

//attribute category
define("__ATTR_CATEGORY_ACTIVE__" , 42);//INT
define("__ATTR_CATEGORY_SHOW_IN_MENU__" , 67); //INT  show in navigation
define("__ATTR_CATEGORY_TITLE__" , 41); //varchar
define("__ATTR_CATEGORY_URL_KEY__" , 43);//varchar
define("__ATTR_CATEGORY_META_TITLE__" , 46);//varchar
define("__ATTR_CATEGORY_DESC__" , 44); //text
define("__ATTR_CATEGORY_META_KEYWORD__" , 47);//text
define("__ATTR_CATEGORY_META_DESC__" , 48);//text


//attr product
define('__ATTR_PRODUCT_NAME__' , 71); //varchar
define('__ATTR_PRODUCT_DESC__' , 72); //text
define('__ATTR_PRODUCT_SHORT_DESC__' , 73); //text
define('__ATTR_PRODUCT_META_TITLE__' , 82); //varchar
define('__ATTR_PRODUCT_META_KEYWORD__' , 83); //text
define('__ATTR_PRODUCT_META_DESC__' , 84); //varchar
define("__ATTR_PRODUCT_WEIGHT__" , 80); //decimal
define("__ATTR_PRODUCT_STATUS__" , 96); //int
define("__ATTR_PRODUCT_VISIBILITY__" , 102); //int
define("__ATTR_PRODUCT_MOBILE_PART_TYPE__" , 166); //int
define("__ATTR_PRODUCT_PRICE__" , 75); //decimal
define("__ATTR_PRODUCT_TAX__" , 121); //int
define("__ATTR_PRODUCT_MEDIA__" , 88);

//images
define("__ATTR_PRODUCT_MEDIA_SMALL_IMG__" , 86);//varchar
define("__ATTR_PRODUCT_MEDIA_IMG__" , 85);
define("__ATTR_PRODUCT_MEDIA_THUMBNAIL__" , 87);

function getProductIds()
{
    if ($content = getCsvContent(__DATA_PATH__ . __FILE_OCCUPIED__)) {
        $data = array();
        foreach ($content as $row) {
            $data[] = $row[0];
        }
        return $data;
    }
}
//get category of need to clone
function getCategoryIds()
{
    $sql = "SELECT
          *
        FROM
          ".__DB_SOURCE__.".catalog_category_entity
        WHERE path LIKE CONCAT('%', ".__DATA_CATEGORY_PARENT_ID__.", '%')
          ORDER BY `level` ASC ;
    ";
    if ($row = db()->fetchAll($sql)) {
        $i=0;
        foreach ($row as $rs) {
            if ($rs['entity_id'] != __CATEGORY_ID_EXCLUDE__) {
                $_categoryId[] = $rs['entity_id'];
            }
            $i++;
        }
        return $_categoryId;
    }
}