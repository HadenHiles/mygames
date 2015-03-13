<?php
    $relative_path = '../';
    require_once($relative_path . 'auth/authenticate.php');

    if (!authUser()) {
        header('location: ' .$relative_path . 'pages/login.php');
    }
    require_once('../db/db-connect.php');
    $connect = connection();

    if($_REQUEST['game_url'] && $_REQUEST['title'] && $_REQUEST['image_url']) {
        $title = $_REQUEST['title'];
        $image_url = $_REQUEST['image_url'];
        $game_url = $_REQUEST['game_url'];
        $image_url = preg_replace('/..\//', '/', $image_url, 1);
        $user_id = $_SESSION['user_id'];

        $sql = "INSERT INTO games (name, url, img, approved) VALUES('$title', '$game_url', '$image_url', '0')";
        $result = $connect->prepare($sql);
        $result->execute();

        $last_game = "SELECT id FROM games WHERE url = '$game_url'";
        $last_game_stmt = $connect->prepare($last_game);
        $last_game_stmt->execute();
        $last_game_result = $last_game_stmt->fetchAll();
        foreach($last_game_result as $last_game) {
            $last_game_id = $last_game['id'];
        }
        $sql_user_game = "INSERT INTO user_games VALUES($user_id, $last_game_id)";
        $user_game_stmt = $connect->prepare($sql_user_game);
        $user_game_stmt->execute();

        header('location: favorites.php');
    }