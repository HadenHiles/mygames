<?php
    require_once('../db/db-connect.php');
    $connect = connection();

    if($_REQUEST['game_url'] && $_REQUEST['title'] && $_REQUEST['image_url']) {
        $title = $_REQUEST['title'];
        $image_url = $_REQUEST['image_url'];
        $game_url = $_REQUEST['game_url'];
        $image_url = preg_replace('/..\//', '/', $image_url, 1);

        $sql = "INSERT INTO games (name, url, img, approved) VALUES('$title', '$game_url', '$image_url', '0')";
        $result = $connect->prepare($sql);
        try {
            $result->execute();
        } catch(ErrorException $e) {

        }
        header('location: games.php');
    }