<?
require_once('../auth/authenticate.php');

if (!authUser()) {
    header('location: login.php');
} else {
    ?>
    <div class="content">
    <?
    require_once('../db/db-connect.php');
    $connect = connection();

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
                <?
                foreach($result as $row) {
                    ?>
                    <li>
                        <a href="pages/play.php?id=<?=$row['id']?>">
                            <?
                            if(file_exists("../". $row['img'])) {
                                ?>
                                <img src="<?=$row['img']?>" alt="<?=$row['name']?> image" />
                            <?
                            } else {
                                ?>
                                <img src="../images/no-image.jpg" alt="No image" />
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
}
?>
</div>
<!--Script for directional hover-->
<script src="../js/lib/modernizr.custom.97074.js"></script>
<script type="text/javascript" src="../js/lib/jquery.hoverdir.js"></script>
<script type="text/javascript">
    $(function() {
        $(' #da-thumbs > li ').each( function() { $(this).hoverdir({
            hoverDelay : 55
        }); } );
    });
</script>