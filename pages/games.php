<?
$relative_path = '../';

require_once($relative_path . 'db/db-connect.php');
$connect = connection();

require_once($relative_path . 'auth/authenticate.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Games | My Games</title>
        <? include($relative_path . 'includes/stylesheets.php') ?>
    </head>
    <body>
        <? include($relative_path . 'templates/header.php'); ?>
        <div id="swap-able-content">
            <div class="content">
                <?
                $tag = $_REQUEST['category'];
                $name = $_REQUEST['name'];

                $sql = "SELECT id, name, img, description FROM games";
                $queryParams = [];
                if (empty($tag) || empty($name)){
                    $sql = "SELECT id, name, img, description FROM games";
                }
                if (!empty($tag)) {
                    $sql .= " WHERE category LIKE CONCAT('%', :tag, '%')";
                    $queryParams[":tag"] = $tag;
                }
                if (!empty($name)) {
                    $queryParams[':name'] = $name;
                    if (!empty($tag)) {
                        $sql .= " AND ";
                    } else {
                        $sql .= " WHERE ";
                    }
                    $sql .= "(name LIKE CONCAT('%', :name, '%') OR category LIKE CONCAT('%', :name1, '%'))";
                    $queryParams[':name1'] = $name;
                }
                if(!empty($tag) || !empty($name)) {
                    $sql .= " AND approved = 1";
                } else {
                    $sql .= " WHERE approved = 1";
                }
                $sql .= " ORDER BY name";

                try {
                    $stmt = $connect->prepare($sql);
                    $stmt->execute($queryParams);
                    $result = $stmt->fetchAll();
                    //show the results of the tag filter
                    if ($result) {
                        ?>
                        <div id="game_manager">
                            <ul id="da-thumbs" class="da-thumbs game_list_container">
                            <?
                            foreach($result as $row) {
                                ?>
                                <li>
                                    <a href="<?=$relative_path?>pages/play.php?id=<?=$row['id']?>">
                                        <?
                                        if(file_exists($relative_path . $row['img'])) {
                                            ?>
                                            <img src="<?=$relative_path?><?=$row['img']?>" alt="<?=$row['name']?> image" />
                                        <?
                                        } else {
                                            ?>
                                            <img src="<?=$relative_path?>images/no-image.jpg" alt="No image" />
                                        <?
                                        }
                                        ?>
                                        <div>
                                            <span>
                                                <?=$row['name']?>
                                            </span>
                                            <?
                                            if(authUser()) {
                                                $game_id = $row['id'];
                                                session_start();
                                                $user_id = $_SESSION['user_id'];
                                                $sql_check = "SELECT game_id FROM user_games WHERE game_id = :game_id AND user_id = :user_id";
                                                $check_result = $connect->prepare($sql_check);
                                                $check_result->bindParam(':game_id', $game_id, PDO::PARAM_INT);
                                                $check_result->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                                                $check_result->execute();
                                                if($check_result->rowCount() == 0) {
                                                    if(authAdmin()) {
                                                        ?>
                                                        <strong class="fav">
                                                            <i class="fa fa-heart-o icon<?=$game_id?>" game_id="<?=$game_id?>" user_id="<?=$user_id?>" style="margin-left: -55px;"></i>
                                                        </strong>
                                                        <i class="fa fa-edit normal" style="margin: 0px 0px 0px 15px;" id="modify_game" type="edit-game" game_id="<?=$game_id?>"></i>
                                                        <strong class="normal">
                                                            <i class="fa fa-trash normal right" style="margin-top: -2px;" id="modify_game" type="delete-game" game_id="<?=$game_id?>"></i>
                                                        </strong>
                                                    <?
                                                    } else {
                                                        ?>
                                                        <strong class="fav">
                                                            <i class="fa fa-heart-o icon<?=$game_id?>" game_id="<?=$game_id?>" user_id="<?=$user_id?>"></i>
                                                        </strong>
                                                        <?
                                                    }
                                                } else {
                                                    if(authAdmin()) {
                                                        ?>
                                                        <strong class="fav">
                                                            <i class="fa fa-heart icon<?=$game_id?>" game_id="<?=$game_id?>" user_id="<?=$user_id?>" style="margin-left: -55px;"></i>
                                                        </strong>
                                                        <i class="fa fa-edit normal" style="margin: 0px 0px 0px 15px;" id="modify_game" type="edit-game" game_id="<?=$game_id?>"></i>
                                                        <strong class="normal">
                                                            <i class="fa fa-trash normal right" style="margin-top: -2px;" id="modify_game" type="delete-game" game_id="<?=$game_id?>"></i>
                                                        </strong>
                                                    <?
                                                    } else {
                                                        ?>
                                                        <strong class="fav">
                                                            <i class="fa fa-heart icon<?=$game_id?>" game_id="<?=$game_id?>" user_id="<?=$user_id?>"></i>
                                                        </strong>
                                                        <?
                                                    }
                                                }
                                            }
                                            ?>
                                        </div>
                                    </a>
                                </li>
                            <?
                            }
                            ?>
                        </ul>
                        </div>
                        <?
                    } else {
                        ?>
                        <h2 style="text-align: center; margin-top: 50px;">No Games Were Found :(</h2>
                    <?
                    }
                } catch(PDOException $e) {
                    $selectError = "There was an error retrieving the list of games from the system. Please try again later.";
                    mail("hgameinc@gmail.com", "HGame - Sql Error", $selectError . "\r\nError: " . $e , "from:support@mygames.ca");
                    ?>
                    <?=$selectError?>
                <?
                }
                ?>
            </div>
        </div>
        <? include($relative_path . 'templates/footer.php'); ?>
    </body>
</html>