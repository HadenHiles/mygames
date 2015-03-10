<?php
require_once('db/db-connect.php');
$connect = connection();

//$files = glob('images/game-images/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
$files = glob('images/game-images/*', GLOB_BRACE);

foreach($files as $file) {
    $sql = "SELECT img FROM games WHERE img LIKE '%$file'";
    $result = $connect->prepare($sql);
    $result->execute();
    $result_count = $result->rowCount();
    if($result_count == 0) {
        unlink($file);
    }
}