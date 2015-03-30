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

session_start();
if(empty($_SESSION['games_played'])) {
    $_SESSION['games_played'] = 1;
}

$prep_name = $connect->prepare("SELECT name FROM games WHERE id = :game_id");
$prep_name->bindParam(':game_id', $id, PDO::PARAM_INT);
$prep_name->execute();
$game_name_result = $prep_name->fetchAll();
foreach ($game_name_result as $row) {
    $page_name = $row['name'];
}
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="<?=$relative_path?>bootstrap-3.2.0/css/bootstrap-modal.css">
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
                                    if(authAdmin()) {
                                        ?>
                                        <a href=""><i class="fa fa-edit normal" style="font-size: 34px; margin: 0px 4px 0px 0px; position: relative; top: 1px;" id="modify_game" type="edit-game" game_id="<?=$row['id']?>"></i></a>
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
        <?
        if($_SESSION['games_played'] == 2 || ($_SESSION['games_played'] % 4 == 0 && !authUser())) {
            ?>
            <div class="modal fade" id="join-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header" style="margin: 0; border-bottom: none;">
                            <button class="close modal_close" data-dismiss="modal" type="button">&times;</button>
<!--                            <h4 class="modal-title" id="avatar-modal-label" style="color: #000;">Find Out What You're Missing!</h4>-->
                        </div>
                        <div class="modal-body" style="margin-top: -20px;">
                            <div class="avatar-body" style="text-align: center;">
                                <h2>With MyGames you can add and manage YOUR OWN favorite flash games!</h2>
                                <h3><a href="<?=$relative_path?>pages/join.php">Join now</a> to find out what you're missing!</h3>
                                <div id="vimeoWrap">
                                    <iframe src="https://player.vimeo.com/video/123472626" width="800" height="450" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="form_button modal_button dark modal_close" data-dismiss="modal" type="button">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!--Vimeo js-->
            <script type="text/javascript" src="https://raw.github.com/vimeo/player-api/master/javascript/froogaloop.js"></script>
            <script type="text/javascript">
                $(document).ready(function() {
                    if($('#vimeoWrap').length > 0) {
                        $('#join-modal').on('click', '.modal_close', function() {
                            vimeoWrap = $('#vimeoWrap');
                            vimeoWrap.html( vimeoWrap.html() );
                            $('#join-modal').modal('hide');
                        });
                    }
                });
            </script>
            <?
        }
        ?>
    </body>
</html>
<?
$_SESSION['games_played']++;
?>