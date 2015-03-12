<?
$relative_path = '../';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Games | My Games</title>
        <meta charset="utf-8" />
        <? include($relative_path . 'includes/stylesheets.php') ?>
    </head>
    <body>
        <? include($relative_path . 'templates/header.php'); ?>
        <div id="swap-able-content">
            <div class="content">
                <?
                require_once($relative_path . 'db/db-connect.php');
                $connect = connection();

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
                                        <div><span><?=$row['name']?></span></div>
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
                        <p class='text-center'>There were no matches for your search. Please try again.</p>
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