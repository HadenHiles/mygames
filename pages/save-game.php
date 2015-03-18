<?php
$relative_path = '../';
require_once($relative_path . 'auth/authenticate.php');

if (!authUser()) {
    header('location: ' .$relative_path . 'pages/login.php');
}
require_once('../db/db-connect.php');
$connect = connection();

if(!empty($_REQUEST['game_url']) && !empty($_REQUEST['title']) && !empty($_REQUEST['image_url'])) {
    $title = $_REQUEST['title'];
    $image_url = $_REQUEST['image_url'];
    $game_url = $_REQUEST['game_url'];
    if (strpos($game_url,'mygames.moonrockfamily.ca/proxy.php') !== false || strpos($game_url,'localhost/proxy.php') !== false) {
        require_once($relative_path . 'unproxify-url.php');
        $game_url = unproxifyUrl($game_url);
    }
    $image_url = preg_replace('(../)', '', $image_url, 1);
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO games (name, url, img, approved) VALUES(:title, :game_url, :image_url, 0)";
    $cmd = $connect->prepare($sql);
    $cmd->bindParam(':game_url', $game_url, PDO::PARAM_STR, 10000);
    $cmd->bindParam(':image_url', $image_url, PDO::PARAM_STR, 500);
    $cmd->bindParam(':title', $title, PDO::PARAM_STR, 212);
    try{
        $cmd->execute();
    } catch(PDOException $e) {
        header('location: favorites.php');
    }

    header('location: save-user-game.php');
} else {
    header('location: favorites.php');
}