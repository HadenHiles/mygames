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
            <div class="content">

            </div>
        </div>
        <? include($relative_path . 'templates/footer.php'); ?>
    </body>
</html>