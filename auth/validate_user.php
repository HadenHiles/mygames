<?
//ensure that only one person can be logged in at a time
session_start();
session_unset();
session_destroy();

//store the posted login credentials into variables
$username = $_POST['username'];
//hash the password entered for comparison with the password in the db
$password = hash('sha512', $_POST['password']);

//connect to the database
require_once('../db/db-connect.php');
$connect = connection();

//set up and run the query
$sql = "SELECT id FROM users WHERE (username = :username AND password = :password) AND activation IS NULL";
$cmd = $connect -> prepare($sql);
//Add the parameter values for the admin query
$cmd -> bindParam(':username', $username, PDO::PARAM_STR, 50);
$cmd -> bindParam(':password', $password, PDO::PARAM_STR, 512);

//handle any pdo query errors
try {
    $cmd ->execute();
}	catch (PDOException $e) {
    header('location: ../pages/login.php?message=login_error');
}

//store the number of rows returned in a variable
$count = $cmd -> rowCount();

//check if any matches are found in the db
if ($count == 1) {
    //if we found matches, we need to determine and store the user's id
    foreach ($cmd as $row) {
        //access the existing session created automatically by the server
        session_start();
        //take the user's id from the database and store it in a session variable
        $_SESSION['user_id'] = $row['id'];
        //redirect the user
        header('Location:../index.php');
    }
}
else {
    header('location: ../pages/login.php?message=login_error');
}
//disconnect
if($connect){
    $connect = null;
}