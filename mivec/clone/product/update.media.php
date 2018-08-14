<?php
require 'config.php';
Zend_Loader::loadClass("Mivec_Http_Client");
define("__DATA_ENTITY_TYPE_ID__" , 4);
define("__DATA_STORE_ID__" , 0);

//download product's image from g-sp.se
define("__SEPARATOR_DIR__" , "/");
define("__SEPARATOR_IMG__" , ";");

define("__WEB_SOURCE__" , "http://gsp.ixsbn.com/");
define("__PREFIX_DIR_MEDIA__" ,
    arrayToStr(array("catalog" , "product") , DS)
);
define("__SOURCE_URL_MEDIA__" , __WEB_SOURCE__ . Mage_Core_Model_Store::URL_TYPE_MEDIA . __SEPARATOR_DIR__
    . str_replace(DS , __SEPARATOR_DIR__ , __PREFIX_DIR_MEDIA__)
);
define("__DIR_MEDIA_PRODUCT__" , Mage::getBaseDir("media")
    . DS
    . __PREFIX_DIR_MEDIA__
);

if ($content = getCsvContent(__DATA_PATH__ . __FILE_SOURCE_EXPORT__)) {
    $i = 1;
    foreach ($content as $row) {
        $_productId = $row[0];
        $_sku = $row[1];
        $_mediaValue = $row[11];

/*        if (getProductImage($_mediaValue)) {
            echo $_sku . " was success to save image</br>";
            usleep(5);
        }*/

        if (updateMediaPosition($_productId)) {
            echo $_sku . " Update image position succeed<br>";
            usleep(10);
        }

/*        if (setDefaultImg($_productId)) {
            echo $_sku . " set image succeed<br>";
            usleep(5);
        }*/

        //if ($i == 5) break;
        $i++;
    }
}

function updateMediaPosition($_entityId)
{
    $sql = "SELECT a.value_id,a.entity_id,b.position FROM " .
        __TABLE_PRODUCT_MEDIA__ . " a LEFT JOIN " . __TABLE_PRODUCT_MEDIA_VALUE__ . " b"
        ." ON (a.value_id = b.value_id) "
        ." WHERE a.entity_id=$_entityId";

    if ($row = db()->fetchAll($sql)) {
        $_status = false;
        $i=1;
        foreach ($row as $rs) {
            //update
            $data['value_id'] = $rs['value_id'];
            $data['store_id'] = __DATA_STORE_ID__;
            $data["position"] = $i;

            $sql = "REPLACE INTO " . __TABLE_PRODUCT_MEDIA_VALUE__
                 . "(`value_id`,store_id,`position`) VALUES
                 (".$data['value_id'].",".$data['store_id'].",".$data['position'].")";

            if (db()->query($sql)) {
                $_status = true;
            }
            $i++;
        }
        return $_status;
    }
}

function setDefaultImg($_entityId)
{
    $_attributes = array(
        "small_img" => __ATTR_PRODUCT_MEDIA_SMALL_IMG__,
        "image"     => __ATTR_PRODUCT_MEDIA_IMG__,
        "thumbnail" => __ATTR_PRODUCT_MEDIA_THUMBNAIL__
    );

    $sql = "SELECT * FROM " . __TABLE_PRODUCT_MEDIA__ . "
        WHERE entity_id = $_entityId
        ORDER BY `value_id` DESC";

    $_status = false;
    if ($row = db()->fetchRow($sql)) {
        $_img = $row['value'];
        //update
        foreach ($_attributes as $_attribute) {
            $sql = "SELECT COUNT(*) FROM " . __TABLE_PRODUCT_VARCHAR__
            ." WHERE attribute_id=" . $_attribute
            ." AND entity_id=" . $_entityId;

            if (db()->fetchOne($sql) == 0) {
                //insert
                $data = array(
                    "entity_type_id" => __DATA_ENTITY_TYPE_ID__,
                    "attribute_id"   => $_attribute,
                    "store_id"       => __DATA_STORE_ID__,
                    "entity_id"      => $_entityId,
                    "value"          => $_img,
                );

                try {
                    $_status = db()->insert(__TABLE_PRODUCT_VARCHAR__ , $data);
                } catch (Exception $e){
                    die ($e->getCode() . $e->getMessage());
                }
            }
        }
        return $_status;
    }
}

function getProductImage($_image)
{
    $httpClient = New Mivec_Http_Client();
    $_status = false;
    $imgData = array();
    if (!empty($_image)) {
        if (strpos($_image , __SEPARATOR_IMG__) !== false) {
            $_imageArr = strToArray($_image , __SEPARATOR_IMG__);
            foreach ($_imageArr as $_img) {
                $imgData[] = $_img;
            }
        } else {
            $imgData[] = $_image;
        }

        foreach ($imgData as $img) {
            $_source = __SOURCE_URL_MEDIA__ . $img;
            $_target = __DIR_MEDIA_PRODUCT__ . $img;

            $dir['target'] = __DIR_MEDIA_PRODUCT__. DS . getDirByImage($img);
            //mkdir
            multiDir($dir['target']);

            echo $_source . ":";
            if ($httpClient->request($_source)->saveToImage($_target)) {
                $_status = true;
            }
        }

        return $_status;
    /*
        $_split = strToarray($_image , __SEPARATOR_DIR__);
        $data["dir"] = $_split[1] . __SEPARATOR_DIR__ . $_split[2] . __SEPARATOR_DIR__;
        $data['file'] = $_split[count($_split) - 1];
        $data['img'] = $_image;
    */
    }
}
?>