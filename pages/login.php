<!DOCTYPE html>
<html>
<head>
    <title>Login | My Games</title>
    <meta charset="utf-8" />
    <link href="../css/fonts.css" type="text/css" rel="stylesheet" />
    <link href="../css/styles.css" type="text/css" rel="stylesheet" />
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
                <a class="form_button" onclick="$('#login_form').submit()">Login</a>
                <p class="centerText">Don't have an account? <a href="sign-up.php">Join</a></p>
            </fieldset>
        </form>
    </div>
</div>
<footer>
    <div class="footer_content">
        <p>Copyright &copy 2014</p>
        <a href="http://haden.moonrockfamily.ca"><img src="../images/logos/stamp-light-bevel.png" alt="HH" class="stamp" /></a>
    </div>
</footer>
<script src="../js/lib/jquery-2.1.3.min.js"></script>
<script src="../js/main.js"></script>
</body>
</html>