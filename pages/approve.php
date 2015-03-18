<?php
$relative_path = '../';
$page_name = 'Approve';
require_once($relative_path . 'auth/authenticate.php');

if (!authSuper()) {
    header('location: ' .$relative_path . 'pages/favorites.php');
}
require_once($relative_path . 'db/db-connect.php');
$connect = connection();
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
                $tag = $_REQUEST['category'];
                $name = $_REQUEST['name'];

                //Get games specific to user
                session_start();
                $user_id = $_SESSION['user_id'];

                $sql = "SELECT id, name, img, description, approved FROM games WHERE approved = 0";
                $queryParams = [];
                if (empty($tag) || empty($name)){
                    $sql = "SELECT id, name, img, description, approved FROM games WHERE approved = 0";
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
                        <h1 style="text-align: center;">Approve</h1>
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
                                                <span><?=$row['name']?></span>
                                                <i class="fa fa-thumbs-down normal" style="margin: 0px 0px 0px 15px;" id="modify_game" type="deny-game" game_id="<?=$row['id']?>"></i>
                                                <strong class="normal">
                                                    <i class="fa fa-thumbs-up normal right" style="margin-top: -2px;" id="modify_game" type="approve-game" game_id="<?=$row['id']?>"></i>
                                                </strong>
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
                        <h2 style="text-align: center; margin-top: 50px;">No Games Are In Need Of Approval :)</h2>
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