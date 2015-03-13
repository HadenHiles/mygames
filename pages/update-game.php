<?php
    $relative_path = '../';
    require_once($relative_path . 'auth/authenticate.php');

    if (!authUser()) {
        header('location: ' .$relative_path . 'pages/login.php');
    }
    require_once($relative_path . 'db/db-connect.php');
    $connect = connection();

    if($_REQUEST['title'] && $_REQUEST['image_url']) {
        $game_id = $_REQUEST['game_id'];
        $title = $_REQUEST['title'];
        $image_url = $_REQUEST['image_url'];
        $game_url = $_REQUEST['game_url'];
        $image_url = preg_replace('/..\//', '/', $image_url, 1);
        $user_id = $_SESSION['user_id'];
        if($_REQUEST['game_url']) {
            $sql = "UPDATE games SET name = '$title', url = '$game_url', img = '$image_url' WHERE id = $game_id";
        } else {
            $sql = "UPDATE games SET name = '$title', img = '$image_url' WHERE id = $game_id";
        }
        $result = $connect->prepare($sql);
        $result->execute();

        header('location: favorites.php');
    } else {
        header('location: favorites.php');
    }