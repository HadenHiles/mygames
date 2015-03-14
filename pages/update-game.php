<?php
    $relative_path = '../';
    require_once($relative_path . 'auth/authenticate.php');

    if (!authUser()) {
        header('location: ' . $relative_path . 'pages/login.php');
    }
    require_once($relative_path . 'db/db-connect.php');
    $connect = connection();

    if($_REQUEST['title'] && $_REQUEST['image_url']) {
        $game_id = $_REQUEST['game_id'];
        $title = $_REQUEST['title'];
        $image_url = $_REQUEST['image_url'];
        $game_url = $_REQUEST['game_url'];
        $image_url = preg_replace('(../)', '', $image_url, 1);
        $user_id = $_SESSION['user_id'];
        if(!empty($_REQUEST['game_url'])) {
            $sql = "UPDATE games SET name = '$title', url = :game_url, img = :image_url, approved = 0 WHERE id = :game_id";
        } else if($_REQUEST['image_url'] != '../images/no-image.jpg' && $_REQUEST['image_url'] != '') {
            $sql = "UPDATE games SET name = :title, img = :image_url, approved = 0 WHERE id = :game_id";
        } else {
            $sql = "UPDATE games SET name = :title, approved = 0 WHERE id = :game_id";
        }
        $cmd = $connect->prepare($sql);
        //Add the parameter values for the admin query
        $cmd -> bindParam(':game_id', $game_id, PDO::PARAM_INT);
        if(!empty($_REQUEST['game_url'])) {
            $cmd -> bindParam(':game_url', $game_url, PDO::PARAM_STR, 600);
        }
        $cmd -> bindParam(':image_url', $image_url, PDO::PARAM_STR, 500);
        $cmd -> bindParam(':title', $title, PDO::PARAM_STR, 212);
        try {
            $cmd->execute();
        }	catch (PDOException $e) {
            header('location: ' . $relative_path . 'pages/favorites.php');
        }

        header('location: favorites.php');
    } else {
        header('location: favorites.php');
    }