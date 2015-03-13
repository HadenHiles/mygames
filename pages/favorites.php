<?php
$relative_path = '../';
require_once($relative_path . 'auth/authenticate.php');

if (!authUser()) {
    header('location: ' .$relative_path . 'pages/login.php');
} else {
    require_once($relative_path . 'db/db-connect.php');
    $connect = connection();
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <title>My Games</title>
            <meta charset="utf-8" />
            <? include($relative_path . 'includes/stylesheets.php') ?>
        </head>
        <body>
            <? include($relative_path . 'templates/header.php'); ?>
            <div id="swap-able-content">
                <div class="content">
                    <h1 style="text-align: center;">My Favorites</h1>
                    <?
                    $tag = $_REQUEST['category'];
                    $name = $_REQUEST['name'];

                    //Get games specific to user
                    $user_id = $_SESSION['user_id'];

                    $sql = "SELECT id, name, img, description FROM games JOIN user_games ON id = user_games.game_id WHERE user_id = $user_id";
                    $queryParams = [];
                    if (empty($tag) || empty($name)){
                        $sql = "SELECT id, name, img, description FROM games JOIN user_games ON id = user_games.game_id WHERE user_id = $user_id";
                    }
                    if (!empty($tag)) {
                        $sql .= " AND category LIKE CONCAT('%', :tag, '%')";
                        $queryParams[":tag"] = $tag;
                    }
                    if (!empty($name)) {
                        $queryParams[':name'] = $name;
                        if (!empty($tag)) {
                            $sql .= " AND ";
                        } else {
                            $sql .= " AND ";
                        }
                        $sql .= "(name LIKE CONCAT('%', :name, '%') OR category LIKE CONCAT('%', :name1, '%'))";
                        $queryParams[':name1'] = $name;
                    }
                    $sql .= " ORDER BY name";

                    try {
                        $stmt = $connect->prepare($sql);
                        $stmt->execute($queryParams);
                        $result = $stmt->fetchAll();
                        //show the results of the tag filter
                        if ($result) {
                            ?>
                            <ul id="da-thumbs" class="da-thumbs game_list_container">
                                <li style="border-color: rgba(0, 0, 0, 0); background: none;">
                                    <a href="<?=$relative_path?>pages/add-game.php">
                                        <span style="width: 100%; text-align: center"><i class="fa fa-plus large_add"></i></span>
                                    </a>
                                </li>
                                <?
                                foreach($result as $row) {
                                    ?>
                                    <li>
                                        <a href="<?=$relative_path?>pages/play.php?id=<?=$row['id']?>">
                                            <?
                                            if(file_exists("../". $row['img'])) {
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
                                                <span><?=$row['name']?></span>
                                                <br>
                                                <strong class="fav red">
                                                    <i class="fa fa-heart icon<?=$row['id']?>" game_id="<?=$row['id']?>" user_id="<?=$$_SESSION['user_id']?>" style="margin-left: -55px;"></i>
                                                </strong>
                                                <i class="fa fa-edit" id="edit_game<?=$row['id']?>"></i>
                                            </div>
                                            <script src="<?=$relative_path?>js/lib/jquery-2.1.3.min.js"></script>
                                            <script type="text/javascript">
                                                $(document).ready(function() {
                                                    $('#edit_game<?=$row['id']?>').on('click', function(e) {
                                                        e.preventDefault();
                                                        window.location = location.protocol + '//' + location.host + '/pages/edit-game.php' + '?id=' + <?=$row['id']?>;
                                                    });
                                                });
                                            </script>
                                            <!--                            <h3 class="game_name">--><?//=$row['name']?><!--</h3>-->
                                            <!--                            <p class="game_description">--><?//=$row['description']?><!--</p>-->
                                        </a>
                                    </li>
                                <?
                                }
                                ?>
                            </ul>
                        <?
                        } else {
                            ?>
                            <p class='text-center'>You haven't starred any games yet!</p>
                        <?
                        }
                    } catch(PDOException $e) {
                        $selectError = "There was an error retrieving your games from the system. Please try again later.";
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
<?
}
?>