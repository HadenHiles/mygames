<?
if(isset($_REQUEST['name'])) {
    $search = $_REQUEST['name'];
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
        require_once($relative_path . 'auth/authenticate.php');
        if (authUser()) {
            ?>
            <a href="<?=$relative_path?>pages/favorites.php" class="button second" style="margin-left: 55px;">FAVORITES</a>
            <a href="<?=$relative_path?>pages/logout.php" class="button third">LOGOUT</a>
        <?
        }
        if(!authUser()) {
            ?>
            <a href="<?=$relative_path?>pages/favorites.php" class="button second" style="margin-left: 55px;">LOGIN</a>
            <a href="<?=$relative_path?>pages/sign-up.php" class="button third">JOIN</a>
        <?
        }
        ?>
    </nav>
</div>