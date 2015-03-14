<?
if(isset($_REQUEST['name'])) {
    $search = $_REQUEST['name'];
}
require_once($relative_path . 'auth/authenticate.php');

require_once($relative_path . 'db/db-connect.php');
$connect = connection();
if(authUser()) {
    ?>
    <div class="top_menu">
        <?
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT name FROM users WHERE id = $user_id";
        $stmt = $connect->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        foreach($result as $row) {
            $name = $row['name'];
        }
        ?>
        <h4 style="float: left; color: #fff; margin-top: 24px;">Welcome, <?=$name?>!</h4>
        <ul>
            <li><a href="<?=$relative_path?>pages/logout.php">LOGOUT <i class="fa fa-sign-out"></i></a></li>
        </ul>
    </div>
    <?
}
?>
<div class="header" id="header">
    <div class="logo">
        <a href="<?=$relative_path?>index.php" title="home"><img src="<?=$relative_path?>images/logos/logo2.png" /></a>
    </div>
    <div class="search">
        <i class="fa fa-search"></i>
        <input type="text" name="search" placeholder="Search" id="search" value="<?=$search?>" />
        <a id="submit_search" class="go" href="">Go</a>
    </div>
    <nav>
        <a href="<?=$relative_path?>pages/games.php" class="button first">PLAY</a>
        <?
        if (authUser()) {
            ?>
            <a href="<?=$relative_path?>pages/favorites.php" class="button second">FAVORITES</a>
            <a href="<?=$relative_path?>pages/add-game.php" class="button third">ADD</a>
        <?
        }
        if(!authUser()) {
            ?>
            <a href="<?=$relative_path?>pages/favorites.php" class="button second">LOGIN</a>
            <a href="<?=$relative_path?>pages/sign-up.php" class="button third">JOIN</a>
        <?
        }
        ?>
    </nav>
</div>