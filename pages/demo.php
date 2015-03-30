<?
$relative_path = '../';
$page_name = 'Demo';
require_once($relative_path . 'db/db-connect.php');
$connect = connection();

require_once($relative_path . 'auth/authenticate.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <? include($relative_path . 'includes/stylesheets.php') ?>
    </head>
    <body>
        <? include($relative_path . 'templates/header.php'); ?>
        <div id="swap-able-content">
            <div class="content" style="text-align: center;">
                <h1>Welcome to MyGames!</h1>
                <h3>With your account you can add and manage YOUR OWN favorite flash games!</h3>
                <p>Watch this quick video to get started!</p>
                <iframe src="https://player.vimeo.com/video/123472626" width="800" height="450" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
            </div>
        </div>
        <? include($relative_path . 'templates/footer.php'); ?>
    </body>
</html>