<nav>
    <a href="pages/games.php" class="dynamic button">PLAY</a>
    <?
    require_once('../auth/authenticate.php');
    if (authUser()) {
        ?>
        <a href="pages/mygames.php" class="dynamic button">MY GAMES</a>
        <a href="pages/logout.php" class="button">LOGOUT</a>
    <?
    }
    if(!authUser()) {
        ?>
        <a href="pages/mygames.php" class="button">LOGIN</a>
        <a href="pages/sign-up.php" class="button">JOIN</a>
    <?
    }
    ?>
</nav>