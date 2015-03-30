<?
$relative_path = '../';
require_once($relative_path . 'auth/authenticate.php');

//get the id from the url
$id = $_REQUEST['id'];
if(empty($id) || !isset($id)) {
    header('location:' . $relative_path . 'pages/games.php');
}
require_once($relative_path . 'db/db-connect.php');
$connect = connection();


$prep_name = $connect->prepare("SELECT name FROM games WHERE id = :game_id");
$prep_name->bindParam(':game_id', $id, PDO::PARAM_INT);
$prep_name->execute();
$game_name_result = $prep_name->fetchAll();
foreach ($game_name_result as $row) {
    $page_name = $row['name'];
}

$update_count = $connect->prepare("UPDATE games SET play_count = play_count + 1 WHERE id = :id");
$update_count->bindParam(':id', $id, PDO::PARAM_INT);
$update_count->execute();
?>
<!DOCTYPE html>
<html>
    <head>
        <? include($relative_path . 'includes/stylesheets.php') ?>
    </head>
    <body>
        <? include($relative_path . 'templates/header.php'); ?>
        <div id="swap-able-content">
            <div class="content">
                <?
                //connect to the database
                require_once($relative_path . 'db/db-connect.php');
                $connect = connection();

                //retrieve the user from the session
                session_start();
                if(isset($_SESSION['user_id'])) {
                    $user_id = $_SESSION['user_id'];
                }
                if (isset($_REQUEST['description'])) {
                    $description = $_REQUEST['description'];
                }
                if (isset($_REQUEST['gameUrl'])) {
                    $gameUrl = $_REQUEST['gameUrl'];
                }

                //set up the sql select statements
                $prep = $connect->prepare("SELECT id, name, url, img, description FROM games WHERE id = :id");
                //	$sqlSelect = "SELECT id FROM users WHERE admin = TRUE";
                //	$stmt = $connect->prepare($sqlSelect);

                //handle any pdo query errors
                try {
                    //execute the sql and provide the prepared statement with the appropriate parameters
                    $prep->bindParam(':id', $id, PDO::PARAM_INT);
                    $prep->execute();
                    //		$stmt->execute();
                    //get the value of the sqlSelect
                    //		$permissionResult = $stmt->fetchAll();
                }	catch (PDOException $e) {
                    $selectError = 'There was an error loading content. Feel free to try another game.';
                    mail("hgameinc@gmail.com", "HGame - Sql Error", $selectError . "\r\nError: " . $e , "from:support@hgame.ca");
                    echo $selectError;
                }
                //retrieve all of the rows returned from the sql select and store them in a variable
                $result = $prep->fetchAll();
                //get the number of results from the prep query
                $count = $prep->rowCount();

                //display the according flash game in an embed tag format
                foreach ($result as $row) {
                    $game_id = $row['id'];
                    $gameName = $row['name'];
                    $gameUrl = $row['url'];
                    $description = $row['description'];
                    $image = $row['img'];
                    ?>
                    <div class="game_container">
                        <div class="game_actions_wrapper">
                            <h1 class="game_name"><?=$row['name']?></h1>
                            <div class="game_play_actions">
                                <div id="game_manager">
                                    <?
                                    if(authUser()) {
                                        $sql_check = "SELECT game_id FROM user_games WHERE game_id = :game_id AND user_id = :user_id";
                                        $check_result = $connect->prepare($sql_check);
                                        $check_result->bindParam(':game_id', $game_id, PDO::PARAM_INT);
                                        $check_result->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                                        $check_result->execute();
                                        if($check_result->rowCount() == 0) {
                                            ?>
                                            <strong class="fav">
                                                <a href=""><i class="fa fa-heart-o icon<?=$game_id?>" game_id="<?=$game_id?>" user_id="<?=$user_id?>"></i></a>
                                            </strong>
                                            <?
                                        } else {
                                            ?>
                                            <strong class="fav">
                                                <a href=""><i class="fa fa-heart icon<?=$game_id?>" game_id="<?=$game_id?>" user_id="<?=$user_id?>"></i></a>
                                            </strong>
                                            <?
                                        }
                                    }
                                    if(authAdmin()) {
                                        ?>
                                        <a href=""><i class="fa fa-edit" id="modify_game" type="edit-game" game_id="<?=$row['id']?>"></i></a>
                                        <?
                                    }
                                    ?>
                                    <a href="" class="full_screen"><i class="fa fa-expand"></i></a>
                                </div>
                            </div>
        <!--                    <div class="game_description">--><?//=$description?><!--</div>-->
                        </div>
                        <object class="flash_object" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab" width="700" height="550">
                            <param name="allowFullScreen" value="true" />
                            <embed class="game" src="<?=$row['url']?>" width="800" height="650" allowFullScreen="true" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/go/getflashplayer" />
                        </object>
                    </div>
                <?
                }

                //disconnect from the db
                if ($connect) {
                    $connect = null;
                }
                $current_link = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
                ?>
                <div class="facebook_share">
                    <p style="text-align: left; float: left;">Share <?=$gameName?> with your friends!</p>
                    <div style="margin: 15px 0px 5px 10px; float: left;"
                         class="fb-like"
                         data-share="true"
                         data-width="100"
                         data-show-faces="true">
                    </div>
                </div>
                <div style="margin-left: 80px;" class="fb-comments" data-href="<?=$current_link?>" data-width="800" data-numposts="10" data-colorscheme="dark" data-order-by="time"></div>
            </div>
        </div>
        <? include($relative_path . 'templates/footer.php'); ?>
    </body>
</html>