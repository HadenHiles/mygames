<?php
$relative_path = '../';
require_once($relative_path . 'auth/authenticate.php');

if (!authUser()) {
    header('location: ' .$relative_path . 'pages/login.php');
} else {
    require_once($relative_path . 'db/db-connect.php');
    $connect = connection();

    //Get passed through variables
    session_start();
    $user_id = $_SESSION['user_id'];
    $game_id = $_REQUEST['game_id'];

    $sql_check = "SELECT game_id FROM user_games WHERE game_id = '$game_id' AND user_id = '$user_id'";
    $result = $connect->prepare($sql_check);
    $result->execute();
    if($result->rowCount() == 0) {
        $sql = "INSERT INTO user_games VALUES('$user_id', '$game_id')";
        $result = $connect->prepare($sql);
        try {
            $result->execute();
        } catch(ErrorException $e) {

        }
        header('location: ' . $relative_path . 'index.php');
    } else {
        $sql_delete = "DELETE FROM user_games WHERE game_id = '$game_id' AND user_id = '$user_id'";
        $user_games_result = $connect->prepare($sql_delete);
        try {
            $user_games_result->execute();
        } catch(ErrorException $e) {

        }
    }
}