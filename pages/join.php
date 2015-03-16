<?
$relative_path = '../';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Join | My Games</title>
    <meta charset="utf-8" />
    <? include($relative_path . 'includes/stylesheets.php') ?>
</head>
<body>
<div class="header" id="header">
    <div class="logo">
        <a href="/" title="home"><img src="<?=$relative_path?>images/logos/logo2.png" /></a>
    </div>
</div>
<div class="stable-height">
    <div id="swap-able-content">
        <form id="join_form" class="form_container" method="post" action="<?=$relative_path?>auth/save_user.php">
            <?
            $message = $_REQUEST['message'];
            $name = $_REQUEST['name'];
            $username = $_REQUEST['un'];
            if($message == "pwc_error") {
                ?>
                <div class="message_error hide">Passwords do not match, please try again :)</div>
            <?
            } else if($message == "email_error") {
                ?>
                <div class="message_error hide">The email you entered is invalid! Please try again :)</div>
            <?
            } else if($message == "email_empty") {
                ?>
                <div class="message_error hide">You must Enter an Email!</div>
            <?
            } else if($message == "password_error") {
                ?>
                <div class="message_error hide">The passwords you entered do not match! Please try again :)</div>
            <?
            } else if($message == "name_error") {
                ?>
                <div class="message_error hide">You must enter your name!</div>
            <?
            } else if($message == "signup_error") {
                ?>
                <div class="message_error hide">You could not be registered at this time :( We apologize for the inconvenience.</div>
            <?
            } else if($message == "signup_success") {
                ?>
                <div class="message_success hide">An activation email has been sent to <?=$username?>.  Thank you for joining MyGames! :)</div>
            <?
            } else if($message == "un_taken") {
                ?>
                <div class="message_error hide">The email <?=$username?> is already in use. Please use a different email :)</div>
            <?
            }
            ?>
            <?
            if($message != "signup_success") {
                ?>
                <fieldset>
                    <div class="input_wrapper">
                        <input class="input" name="first_name" type="text" placeholder="First Name*" value="<?=$name?>" required autofocus />
                    </div>
                    <div class="input_wrapper">
                        <input class="input" name="username" type="email" placeholder="Email*" value="<?=$username?>" required />
                    </div>
                    <div class="input_wrapper">
                        <input class="input" name="password" type="password" placeholder="Password*" required />
                    </div>
                    <div class="input_wrapper">
                        <input class="input" name="confirm_password" type="password" placeholder="Confirm Password*" required />
                    </div>
                    <a class="form_button" onclick="$('#join_form').submit()">Join</a>
                    <p class="centerText">Already have an account? <a href="login.php">Login</a></p>
                </fieldset>
                <?
            }
            ?>
        </form>
    </div>
</div>
<? include($relative_path . 'templates/footer.php'); ?>
<script src="../js/lib/jquery-2.1.3.min.js"></script>
<script src="../js/main.js"></script>
</body>
</html>