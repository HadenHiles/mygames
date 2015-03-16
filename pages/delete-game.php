<?php
$relative_path = '../';
require_once($relative_path . 'auth/authenticate.php');

if (!authUser() || !authAdmin() || !authSuper()) {
    header('location: ' . $relative_path . 'pages/login.php');
}
require_once('../db/db-connect.php');
$connect = connection();

if(!empty($_POST['id']) && isset($_POST['id'])) {
    $id = $_POST['id'];
    session_start();
    $user_id = $_SESSION['user_id'];

    $approved_check = "SELECT approved FROM games WHERE id = :approved_game_id AND approved = 0";
    $approved_check_cmd = $connect->prepare($approved_check);
    $approved_check_cmd->bindParam(':approved_game_id', $id, PDO::PARAM_INT);
    try {
        $approved_check_cmd->execute();
    } catch(PDOException $e2) {
        header('location: favorites.php');
    }
    foreach($approved_check as $row) {
        if($row['approved'] == 0) {
            $approved = false;
        } else {
            $approved = true;
        }
    }

    if(authSuper()) {
        $delete = "DELETE FROM games WHERE id = :id";
        $delete_from_user_games = "DELETE FROM user_games WHERE game_id = :game_id";
        $remove_from_user_games_cmd = $connect->prepare($delete_from_user_games);
        $remove_from_user_games_cmd->bindParam(':game_id', $id, PDO::PARAM_INT);
        try {
            $remove_from_user_games_cmd->execute();
        } catch(PDOException $e1) {
            header('location: favorites.php');
        }
    } else if(authAdmin()) {
        if(!$approved) {
            $delete = "UPDATE games SET approved = 0 WHERE id = :id";
        } else {
            $remove_admin_game = "DELETE FROM user_games WHERE game_id = :admins_game_id AND user_id = :admins_id";
            $remove_admin_game_cmd = $connect->prepare($remove_admin_game);
            $remove_admin_game_cmd->bindParam(':admins_game_id', $id, PDO::PARAM_INT);
            $remove_admin_game_cmd->bindParam(':admins_id', $user_id, PDO::PARAM_INT);
            try {
                $remove_admin_game_cmd->execute();
            } catch(PDOException $e3) {
                header('location: favorites.php');
            }

            $remove_unapproved_admin_game = "DELETE FROM games WHERE id = :delete_game_id AND approved = 0";
            $remove_unapproved_admin_game_cmd = $connect->prepare($remove_unapproved_admin_game);
            $remove_unapproved_admin_game_cmd->bindParam(':delete_game_id', $id, PDO::PARAM_INT);
            try {
                $remove_unapproved_admin_game_cmd->execute();
            } catch(PDOException $e4) {
                header('location: favorites.php');
            }
        }
    } else if(authUser()) {
        if(!$approved) {
            $remove_user_game = "DELETE FROM user_games WHERE game_id = :users_game_id AND user_id = :users_id";
            $remove_user_game_cmd = $connect->prepare($remove_user_game);
            $remove_user_game_cmd->bindParam(':users_game_id', $id, PDO::PARAM_INT);
            $remove_user_game_cmd->bindParam(':users_id', $user_id, PDO::PARAM_INT);
            try {
                $remove_user_game_cmd->execute();
            } catch(PDOException $e3) {
                header('location: favorites.php');
            }

            $remove_unapproved_game = "DELETE FROM games WHERE id = :delete_game_id AND approved = 0";
            $remove_unapproved_game_cmd = $connect->prepare($remove_unapproved_game);
            $remove_unapproved_game_cmd->bindParam(':delete_game_id', $id, PDO::PARAM_INT);
            try {
                $remove_unapproved_game_cmd->execute();
            } catch(PDOException $e4) {
                header('location: favorites.php');
            }
        }
    }

    if(authSuper() || (!$approved && authAdmin())) {
        $cmd = $connect->prepare($delete);
        $cmd->bindParam(':id', $id, PDO::PARAM_INT);
        try {
            $cmd->execute();
        } catch(PDOException $e5) {
            header('location: favorites.php');
        }
    }

    header('location: favorites.php');
} else {
    header('location: favorites.php');
}