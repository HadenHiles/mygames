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
    session_start();
    $user_id = $_SESSION['user_id'];
    $authorizeAdmin = false;
    //handle any pdo query errors
    try {
        //connect to the database
        require_once('../db/db-connect.php');
        $connect = connection();
        if (isset($user_id)) {
            //set up the query
            $sqlSelect = "SELECT admin FROM users WHERE id = :user_id";
            //prepare the sql
            $stmt = $connect->prepare($sqlSelect);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            //run the sql
            $stmt->execute();
            $permissionResult = $stmt->fetchAll();
            if($stmt->rowCount() > 0) {
                foreach($permissionResult as $row) {
                    if($row['admin'] == 1) {
                        $authorizeAdmin = true;
                    } else {
                        $authorizeAdmin = false;
                    }
                }
            }
        } else {
            $authorizeAdmin = false;
        }
    } catch (PDOException $pe) {
        $authorizeAdmin = false;
    }
    return $authorizeAdmin;
}

function authSuper() {
    session_start();
    $user_id = $_SESSION['user_id'];
    $authorizeSuper = false;
    //handle any pdo query errors
    try {
        //connect to the database
        require_once('../db/db-connect.php');
        $connect = connection();
        if (isset($user_id)) {
            //set up the query
            $sqlSelect = "SELECT super FROM users WHERE id = :user_id";
            //prepare the sql
            $stmt = $connect->prepare($sqlSelect);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            //run the sql
            $stmt->execute();
            $permissionResult = $stmt->fetchAll();
            if($stmt->rowCount() > 0) {
                foreach($permissionResult as $row) {
                    if($row['super'] == 1) {
                        $authorizeSuper = true;
                    } else {
                        $authorizeSuper = false;
                    }
                }
            }
        } else {
            $authorizeSuper = false;
        }
    } catch (PDOException $pe) {
        $authorizeSuper = false;
    }
    return $authorizeSuper;
}