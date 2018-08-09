<?php
require 'config.php';
Zend_Loader::loadClass("Mivec_Http_Client");

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
        $_sku = $row[1];
        $_mediaValue = $row[11];

        if (getProductImage($_mediaValue)) {
            echo $_sku . " was success to save image</br>";
            usleep(5);
        }

        //if ($i == 5) break;
        $i++;
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