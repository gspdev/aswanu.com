<?php
require 'config.php';
if ($content = getCsvContent(__DATA_PATH__ . __FILE_BACKUP__)) {

    $i = 0;
    foreach ($content as $row) {
        $_productId = $row[0];
        $sql = "DELETE FROM " . __TABLE_PRODUCT__ . " WHERE entity_id=" . $_productId;
        if (db()->query($sql)) {
            echo $_productId . " was suceess deleted.<br>";
            usleep(10);
        }
        $i++;
    }
}
?>