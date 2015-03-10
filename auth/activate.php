<?
//connect to the database
require_once('../db/db-connect.php');
$connect = connection();

$key = $_REQUEST['key'];
$delta = 86400;

//find the user that needs to be activated
$sqlSelect = "SELECT timeStamp FROM users WHERE activation = :key";
$stmt = $connect->prepare($sqlSelect);
$stmt->bindParam(':key', $key);
$stmt->execute();
$result = $stmt->fetchAll();
foreach ($result as $row) {
    $timeStamp = $row['timeStamp'];
}

// Check to see if link has expired
if ($_SERVER["REQUEST_TIME"] - $timeStamp > $delta) {
    //redirect if it has
    header('location:../pages/sign-up.php');
}

//make sure the email is set and is properly formatted
if (isset($_REQUEST['email']) && preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/', $_REQUEST['email'])) {
    $email = $_REQUEST['email'];
}

//The MD5 Hash Activation key will always be 32 characters long
if (isset($_REQUEST['key']) && (strlen($_REQUEST['key']) == 32)) {
    $key = $_REQUEST['key'];
}

//if the username and email contain data, then try a sql update
if (isset($email) && isset($key)) {
    //Update the database to set the "activation" field to null (null is activated)
    $sqlUpdate = "UPDATE users SET activation = NULL WHERE(email = :email && activation= :key)LIMIT 1";
    $cmd = $connect ->prepare($sqlUpdate);
    $cmd ->bindParam(':email', $email, PDO::PARAM_STR, 50);
    $cmd ->bindParam(':key', $key, PDO::PARAM_STR, 50);
    try {
        $cmd ->execute();
    }	catch (PDOException $e) {
        $updateError = 'There was an error activating your account. Please try again.';
        echo $updateError;
    }

    //display a customized message if the activation update was successfull
    $count = $cmd ->rowCount();
    if ($count == 1) {
        header('location: ../pages/login.php?message=activated');
    }
    else{
        header('location: ../pages/login.php?message=activate_fail');
    }

    //disconnect from the db
    if ($connect) {
        $connect = null;
    }
}
else {
    echo 'Oops! Your account could not be activated. Please re-click the link or contact the system administrator: <a href="mail:hgameinc@gmail.com">hgameinc@gmail.com</a>.';
}
