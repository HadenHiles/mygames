<?
if(isset($_REQUEST['name'])) {
    $search = $_REQUEST['name'];
}
require_once($relative_path . 'auth/authenticate.php');

require_once($relative_path . 'db/db-connect.php');
$connect = connection();

?>
<div class="top_menu">
    <ul>
        <?
        if(authUser()) {
            $user_id = $_SESSION['user_id'];
            $sql = "SELECT name FROM users WHERE id = $user_id";
            $stmt = $connect->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll();
            foreach($result as $row) {
                $name = $row['name'];
            }
            ?>
            <li><a href="<?=$relative_path?>pages/logout.php" class="last">LOGOUT <i class="fa fa-sign-out"></i></a></li>
            <?
            if(authSuper()) {
                ?>
                <li><a href="<?=$relative_path?>pages/approve.php">Approve <i class="fa fa-check-circle"></i></a></li>
            <?
            }
        } else {
            ?>
            <li><a href="<?=$relative_path?>pages/login.php" class="last">LOGIN <i class="fa fa-sign-in"></i></a></li>
            <li><a href="<?=$relative_path?>pages/join.php">JOIN <i class="fa fa-gamepad"></i></a></li>
            <?
        }
        ?>
    </ul>
</div>
<div class="header" id="header">
    <div class="logo">
        <?
        if(authUser()) {
            ?>
            <a href="<?=$relative_path?>pages/favorites.php" title="home">
                <div class="welcome">
                    <h1><?=$name?>'s</h1>
                </div>
                <span class="strikethrough">_____</span>
                <img src="<?=$relative_path?>images/logos/logo2.png" />
            </a>
            <?
        } else {
            ?>
            <a href="<?=$relative_path?>index.php" title="home">
                <img src="<?=$relative_path?>images/logos/logo2.png" />
            </a>
            <?
        }
        ?>
    </div>
    <div class="search">
        <div id="search_sticky">
            <i class="fa fa-search"></i>
            <input type="text" name="search" placeholder="Search" id="search" value="<?=$search?>" />
            <a id="submit_search" class="go" href="">Go</a>
        </div>
    </div>
    <nav>
        <?
        if (authUser()) {
            ?>
            <a href="<?=$relative_path?>pages/games.php" class="button first">ALL</a>
            <a href="<?=$relative_path?>pages/favorites.php" class="button second">FAVORITES</a>
            <a href="<?=$relative_path?>pages/add-game.php" class="button third">ADD</a>
            <?
        }
        ?>
    </nav>
</div>