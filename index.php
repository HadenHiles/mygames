<!DOCTYPE html>
<html>
<head>
    <title>My Games</title>
    <meta charset="utf-8" />
    <link href="css/fonts.css" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="css/direction-hover/direction-hover.css" />
    <link href="css/styles.css" type="text/css" rel="stylesheet" />
    <noscript><link rel="stylesheet" type="text/css" href="css/direction-hover/noJS.css"/></noscript>
</head>
<body>
    <div class="header" id="header">
        <?
        echo $_SESSION['dst_path'];
        ?>
        <div class="logo">
            <a href="pages/home.php" class="dynamic" title="home"><img src="images/logos/logo2.png" /></a>
        </div>
    </div>
    <div class="stable-height">
        <div id="swap-able-content">
            <nav>
                <a href="pages/games.php" class="dynamic button">PLAY</a>
                <?
                require_once('auth/authenticate.php');
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
        </div>
    </div>
    <footer>
        <div class="footer_content">
            <p>Copyright &copy <?=date('Y')?></p>
            <a href="http://haden.moonrockfamily.ca"><img src="images/logos/stamp-light-bevel.png" alt="HH" class="stamp" /></a>
        </div>
    </footer>
    <script src="js/lib/jquery-2.1.3.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>