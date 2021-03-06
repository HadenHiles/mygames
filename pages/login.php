<?
$relative_path = '../';
$page_name = 'Login';
require_once($relative_path . 'auth/authenticate.php');
?>
<!DOCTYPE html>
<html>
<head>
    <? include($relative_path . 'includes/stylesheets.php') ?>
</head>
<body>
<div class="header" id="header">
    <div class="logo">
        <a href="/" title="home"><img src="../images/logos/logo2.png" /></a>
    </div>
</div>
<div class="stable-height">
    <div id="swap-able-content">
        <form id="login_form" class="form_container" method="post" action="../auth/validate_user.php">
            <?
            $message = $_REQUEST['message'];
            if($message == "login_error") {
                ?>
                <div class="message_error hide">Invalid email/password, please try again :)</div>
                <?
            } else if($message == 'activated') {
                ?>
                <div class="message_success hide">Your account has been activated. You can log in now! :)</div>
                <?
            } else if($message == 'activate_fail') {
                ?>
                <div class="message_error hide">Your account was not activated :(. Please contact the <a href="mailto:hgameinc@gmail.com">administrator</a> or try again! :)</div>
            <?
            }
            ?>
            <fieldset>
                <div class="input_wrapper">
                    <input class="input" name="username" type="text" placeholder="Email*" required autofocus/>
                </div>
                <div class="input_wrapper">
                    <input class="input" name="password" type="password" placeholder="Password*" required/>
                </div>
                <a class="form_button" id="login_button">Login</a>
                <p class="centerText">Don't have an account? <a href="join.php">Join</a></p>
            </fieldset>
        </form>
    </div>
</div>
<? include($relative_path . 'templates/footer.php'); ?>
</body>
</html>