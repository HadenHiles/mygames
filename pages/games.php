<div class="content">
    <?
    require_once('../db/db-connect.php');
    $connect = connection();

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
    $sql .= " ORDER BY name";

    try {
        $stmt = $connect->prepare($sql);
        $stmt->execute($queryParams);
        $result = $stmt->fetchAll();
        //show the results of the tag filter
        if ($result) {
            foreach($result as $row) {
                ?>
                <article class="game-collumns">
                    <figure>
                        <a href="./play.php?id=<?=$row['id']?>">
                            <img src="<?=$row['img']?>" alt="<?=$row['name']?> image" height="150" />
                            <figcaption class="gameName"><?=$row['name']?></figcaption>
                            <p class="gameDescription"><?=$row['description']?></p>
                        </a>
                    </figure>
                </article>
                <?
            }
        } else {
            ?>
            <p class='text-center'>There were no matches for your search. Please try again.</p>
            <?
        }
    } catch(PDOException $e) {
        $selectError = "There was an error retrieving the list of games from the system. Please try again later.";
        mail("hgameinc@gmail.com", "HGame - Sql Error", $selectError . "\r\nError: " . $e , "from:support@hgame.ca");
        ?>
        <?=$selectError?>
        <?
    }
    ?>
</div>