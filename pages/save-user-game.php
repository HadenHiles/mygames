<?php
$relative_path = '../';
require_once($relative_path . 'auth/authenticate.php');

if (!authUser()) {
    header('location: ' . $relative_path . 'pages/login.php');
}
require_once('../db/db-connect.php');
$connect = connection();

$user_id = $_SESSION['user_id'];
$last_game = "SELECT id FROM games ORDER BY id DESC LIMIT 1";
$last_game_cmd = $connect->prepare($last_game);
$last_game_cmd->execute();
$last_game_result = $last_game_cmd->fetchAll();
foreach($last_game_result as $last_game) {
    $last_game_id = $last_game['id'];
}
$sql_user_game = "INSERT INTO user_games VALUES(:user_id, :last_game_id)";
$user_game_cmd = $connect->prepare($sql_user_game);
$user_game_cmd->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$user_game_cmd->bindParam(':last_game_id', $last_game_id, PDO::PARAM_INT);
try {
    $user_game_cmd->execute();
    header('location: favorites.php');
} catch(PDOException $e) {
    header('location: favorites.php');
}