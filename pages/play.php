<?
$relative_path = '../';

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
            $prep = $connect->prepare("SELECT name, url, img, description FROM games WHERE id = :id");
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
                    <h1 class="game_name"><?=$row['name']?></h1>
<!--                    <div class="game_description">--><?//=$description?><!--</div>-->
                    <p style="text-align: left; float: left; margin-left: 80px;">Share <?=$gameName?> with your friends!</p>
                    <div style="margin: 15px 0px 5px 10px; float: left;"
                        class="fb-like"
                        data-share="true"
                        data-width="100"
                        data-show-faces="true">
                    </div>
                    <object class="flash_object" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab" width="700" height="550">
                        <param name="allowFullScreen" value="true" />
                        <embed class="game" src="<?=$row['url']?>" width="800" height="650" allowFullScreen="true" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/go/getflashplayer" />
                    </object>
                </div>
            <?
            }
            //if the user is an admin then show them the ability to update games on the game page
            //	foreach ($permissionResult as $row) {
            //		if ($user_id == $row['id']) {
            //			//if the game exists in the db then show the update/delete options
            //			//otherwise, redirect to 404 page (prevents random values being added to url id)
            //			if ($count == 1){
            //				echo
            //				'<div style="width: 800px; margin: 0 auto;">'
            //				    . '<div style=" height: 15px;">'
            //					. '<p style="float: left;"><a id="toggle">Update ' . $gameName . '</a></p>';
            //					if (authAdmin()){
            //                        echo '<p style="float: right;"><a href="deleteGame.php?id=' . $id . '"onclick="return confirm(\'Are you sure you want to delete ' . $gameName . '?\');">Delete ' . $gameName . '</a></p>'
            //                            . '</div>';
            //                    }
            //				echo	'<div id="content" style="display: none;">'
            //						. '<form action="./updateGame.php" method="post" enctype=multipart/form-data>'
            //							. '<fieldset style="margin: 0 auto; width: 800px;">'
            //								. '<legend>Update ' . $gameName . '</legend>'
            //								. '<div style="width: 100%; float: left;">'
            //									. '<label class="label">Title:</label>'
            //									. '<input class="input" name="title" type="text" value="' . $gameName . '" maxlength="50" required autofocus />'
            //									. '<label class="label">Game Url:</label>'
            //                                    . '<input class="input" name="gameUrl" type="text" value="' . $gameUrl . '" maxlength="1000" required />'
            //									. '<label class="label">Photo:</label>'
            //									. '<img src="' . $image . '" alt="' . $gameName . '" height="80" class="currentGameImage" />'
            //									. '<label class="label">Replace Current Photo:</label>'
            //									. '<input class="input" name="image" type="file" />'
            //								. '</div>'
            //								. '<div style="width: 100%; float: left;">'
            //									. '<label for="description" class="label">Description:</label>'
            //									. '<textarea class="input" id="description" style="width: 300px; margin-left: -330px;" name="description" maxlength="195">' . $description . '</textarea>'
            //								. '</div>'
            //								. '<input type="hidden" name="gameId" value="' . $id . '" />'
            //								. '<input class="submitForm" id="submit" type="submit" value="Update" name="update" />'
            //							. '</fieldset>'
            //						. '</form>'
            //					. '</div>';
            //				echo '</div>'
            //				    . '<div style="width: 100%; border-bottom: 3px solid #e18728; margin: 20px 0 20px 0;"></div>';
            //			} else {
            //				//redirect if url isn't valid
            ////				header('location:index.php?action=404');
            //			}
            //		}
            //	}

            //disconnect from the db
            if ($connect) {
                $connect = null;
            }
            $current_link = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
            ?>
            <div style="margin-left: 80px;" class="fb-comments" data-href="<?=$current_link?>" data-width="800" data-numposts="10" data-colorscheme="dark" data-order-by="time"></div>
        </div>
        </div>
        <? include($relative_path . 'templates/footer.php'); ?>
    </body>
</html>