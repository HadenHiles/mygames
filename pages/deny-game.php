<?php
$relative_path = '../';
require_once($relative_path . 'auth/authenticate.php');

if (!authAdmin() || !authSuper()) {
    header('location: ' . $relative_path . 'pages/login.php');
}
require_once('../db/db-connect.php');
$connect = connection();

if(!empty($_POST['id']) && isset($_POST['id'])) {
    $id = $_POST['id'];
    $approve = "UPDATE games SET approved = 2 WHERE id = :id";
    $cmd = $connect->prepare($approve);
    $cmd->bindParam(':id', $id, PDO::PARAM_INT);
    try {
        $cmd->execute();
    } catch(PDOException $e) {
        header('location: favorites.php');
    }

    header('location: favorites.php');
} else {
    header('location: favorites.php');
}