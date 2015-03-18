<div id="fb-root"></div>
<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=429904563844523&version=v2.0";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
<?
if(isset($_REQUEST['name'])) {
    $search = $_REQUEST['name'];
}
require_once($relative_path . 'auth/authenticate.php');

require_once($relative_path . 'db/db-connect.php');
$connect = connection();

?>
<div class="header" id="header">
    <div class="logo">
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
    <div class="top_menu">
        <ul>
            <div class="bring_forward">
                <?
                if(authUser()) {
                    if(authSuper()) {
                        ?>
                        <li><a href="<?=$relative_path?>pages/approve.php">Approve <i class="fa fa-check-circle"></i></a></li>
                    <?
                    }
                    ?>
                    <li><a href="<?=$relative_path?>pages/logout.php">Logout <i class="fa fa-sign-out"></i></a></li>
                    <?
                } else {
                    ?>
                    <li><a href="<?=$relative_path?>pages/join.php">Join <i class="fa fa-gamepad"></i></a></li>
                    <li><a href="<?=$relative_path?>pages/login.php">Login <i class="fa fa-sign-in"></i></a></li>
                <?
                }
                ?>
            </div>
            <div class="share">
                <li class="tell_friends">Tell Your Friends!</li>
                <li><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http://mygames.moonrockfamily.ca/" title="Share MyGames On Facebook"><i class="fa fa-facebook-square"></i></a></li>
                <li><a target="_blank" href="https://twitter.com/home?status=I%20finally%20found%20a%20website%20where%20I%20can%20play%20ALL%20of%20my%20favourite%20flash%20games!%20Join%20MyGames%20for%20free%20today!%20http://mygames.moonrockfamily.ca/" title="Share MyGames On Twitter"><i class="fa fa-twitter-square"></i></a></li>
                <li><a target="_blank" href="https://plus.google.com/share?url=http://mygames.moonrockfamily.ca/" title="Share MyGames On Google +"><i class="fa fa-google-plus-square"></i></a></li>
            </div>
        </ul>
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