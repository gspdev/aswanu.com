<?php
require 'config.php';

//lookup occupied id of target category.
//$exportFile = "occupied.csv";
$fp = fopen(__DATA_PATH__ . __FILE_OCCUPIED__ , "wb");
$header = array("source_id" , "source_title" , "source_path" ,"source_level" , "target_id" , "target_title" , "target_path" , "target_level");
fputcsv($fp , $header);

$sql = "
    SELECT * FROM ".__DB_SOURCE__.".".__TABLE_CATEGORY__."
    WHERE 1
        AND `path` LIKE CONCAT('%', ".__PARENT_CATEGORY_ID__.", '%')
    ORDER BY `level` ASC ;
";

if ($row = db()->fetchAll($sql)) {
    foreach ($row as $rs) {
        $_source['id'] = $rs['entity_id'];
        $_source['path'] = $rs['path'];
        $_source['level'] = $rs['level'];
        $_source['path'] = $rs['path'];

        //get more data
        $sourceDate = getCategoryData($_source['id'] , __ATTR_CATEGORY_TITLE__ , __DB_SOURCE__);
        //print_r($sourceDate);exit;

        //target
        $targetDate = getCategoryData($_source['id'] , __ATTR_CATEGORY_TITLE__ , __DB_TARGET__);
        //$targetDate = $targetDate[0];

        $data = array(
            $_source['id'] , $sourceDate['value'] , $_source['path'],$_source['level'],
            $targetDate['entity_id'] , $targetDate['value'] , $targetDate['path'] , $targetDate['level']
        );
        if (fputcsv($fp , $data)) {
            echo $_source['id'] . " export success</br>";
            usleep(5);
        }
    }
}

fclose($fp);
