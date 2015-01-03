<?
    //get the db info
    require_once('config.php');

    //connect to the db
    function connection() {
        try {
            $connect = new PDO("mysql:host=sql302.byethost24.com; dbname=".dbName, dbUser, dbPassword);
            $connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $connectError = 'There was an error with connecting to the database.';
            define('errorMsg', $connectError);
            define('pdoError', $e);
            mail("hgameinc@gmail.com", "MyGames - Sql Error", $connectError . "\r\nError: " . $e , "From:support@mygames.ca");
            throw $e;
        }
        return $connect;
    }
?>