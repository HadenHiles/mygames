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
    $image_url = preg_replace('(../)', '', $image_url, 1);
    session_start();
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO games (name, url, img, approved) VALUES(:title, :game_url, :image_url, 0)";
    $cmd = $connect->prepare($sql);
    $cmd->bindParam(':game_url', $game_url, PDO::PARAM_STR, 1000);
    $cmd->bindParam(':image_url', $image_url, PDO::PARAM_STR, 500);
    $cmd->bindParam(':title', $title, PDO::PARAM_STR, 212);
    $cmd->execute();

    $last_game = "SELECT id FROM games WHERE url = :game_url1";
    $last_game_cmd = $connect->prepare($last_game);
    $last_game_cmd -> bindParam(':game_url1', $game_url, PDO::PARAM_STR, 600);
    $last_game_cmd->execute();
    $last_game_result = $last_game_cmd->fetchAll();
    foreach($last_game_result as $last_game) {
        $last_game_id = $last_game['id'];
    }
    $sql_user_game = "INSERT INTO user_games VALUES(:user_id, :last_game_id)";
    $user_game_cmd = $connect->prepare($sql_user_game);
    $user_game_cmd->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $user_game_cmd->bindParam(':last_game_id', $last_game_id, PDO::PARAM_INT);
    $user_game_cmd->execute();

    header('location: favorites.php');
} else {
    header('location: favorites.php');
}