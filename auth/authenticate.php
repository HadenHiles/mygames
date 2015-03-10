<?
//create a function to authenticate the user
function authUser() {
    session_start();
    $authorizeUser = false;
    if (isset($_SESSION['user_id'])) {
        $authorizeUser = true;
    }
    return $authorizeUser;
}

//create a function to authenticate the admin
function authAdmin() {
    //handle any pdo query errors
    try {
        //connect to the database
        require_once('../db/db-connect.php');
        $connect = connection();
        //set up the users id to determine what permissions he/she has
        $id = $_SESSION['user_id'];
        //set up the query
        $sqlSelect = "SELECT id FROM users WHERE admin = TRUE";
        //prepare the sql
        $stmt = $connect->prepare($sqlSelect);
        //run the sql
        $stmt->execute();
        $permissionResult = $stmt->fetchAll();
    } catch (PDOException $pe) {
    }
    $authorizeAdmin = false;
    foreach ($permissionResult as $row) {
        if ($id == $row['id']) {
            $authorizeAdmin = true;
        }
    }
    return $authorizeAdmin;
}