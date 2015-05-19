<?
$relative_path = '../';
$page_name = 'Edit Game';
require_once($relative_path . 'auth/authenticate.php');

if (!authAdmin()) {
    header('location: ' . $relative_path . 'pages/login.php');
}

$id = $_REQUEST['id'];
$user_id = $_SESSION['user_id'];

require_once($relative_path . 'db/db-connect.php');
$connect = connection();

$sql = "SELECT name, img, description FROM games WHERE games.id = :game_id";
$stmt = $connect->prepare($sql);
$stmt->bindParam(':game_id', $id, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll();
foreach($result as $row) {
    $title = $row['name'];
    $img = $relative_path . $row['img'];
    $description = $row['description'];
}
if(!file_exists($img)) {
    $img = $relative_path . 'images/no-image.jpg';
}
?>
<!DOCTYPE html>
<html>
<head>
    <script src="<?=$relative_path?>js/lib/jquery-2.1.3.min.js"></script>
    <script src="<?=$relative_path?>bower_components/platform/platform.js"></script>

    <!--Polymer imports-->
    <link rel="import" href="<?=$relative_path?>bower_components/core-ajax/core-ajax.html">
    <link rel="import" href="<?=$relative_path?>bower_components/paper-input/paper-input.html">
    <link rel="import" href="<?=$relative_path?>bower_components/paper-fab/paper-fab.html">
    <link rel="import" href="<?=$relative_path?>bower_components/paper-input-decorator/paper-input-decorator.html">

    <!--Flash game element imports-->
    <link rel="import" href="flash-game-element.html">
    <link rel="import" href="<?=$relative_path?>bower_components/core-selector/core-selector.html">
    <link rel="import" href="<?=$relative_path?>bower_components/core-layout/core-layout.html">

    <!--Cropper imports-->
    <link rel="import" href="cropper-import.html">

    <? include($relative_path . 'includes/stylesheets.php') ?>
</head>
<body>
    <? include($relative_path . 'templates/header.php'); ?>
    <div class="stable-height">
        <div id="swap-able-content">
            <div class="content" style="position: relative;">
                <h1 style="text-align: center;">Edit Game</h1>
                <game-scraper-element></game-scraper-element>
                <polymer-element name="game-scraper-element" attributes="url">
                    <template>
                        <style type="text/css">
                            .flash_element_container {
                                float: right;
                                width: 600px;
                                border: 4px solid #000;
                            }
                            .flash_element_container.error {
                                border: 4px solid #d34336;
                            }
                            paper-input {
                                width: 100%;
                            }
                            paper-input-decorator {
                                width: 100%;
                            }
                            paper-input-decorator /deep/ .label-text,
                            paper-input-decorator /deep/ .error {
                                /* inline label,  floating label, error message and error icon color when the input is unfocused */
                                color: #fff !important;
                            }

                            paper-input-decorator /deep/ ::-webkit-input-placeholder {
                                /* platform specific rules for placeholder text */
                                color: #A8ADB2 !important;
                            }
                            paper-input-decorator /deep/ ::-moz-placeholder {
                                color: #fff !important;
                            }
                            paper-input-decorator /deep/ :-ms-input-placeholder {
                                color: #A8ADB2 !important;
                            }

                            paper-input-decorator /deep/ .unfocused-underline {
                                /* line color when the input is unfocused */
                                background-color: #fff !important;
                            }

                            paper-input-decorator[focused] /deep/ .floating-label .label-text {
                                /* floating label color when the input is focused */
                                color: #278BF6;
                            }

                            paper-input-decorator /deep/ .focused-underline {
                                /* line color when the input is focused */
                                background-color: #278BF6;
                            }

                            paper-input-decorator.invalid[focused] /deep/ .floated-label .label-text,
                            paper-input-decorator[focused] /deep/ .error {
                                /* floating label, error message nad error icon color when the input is invalid and focused */
                                color: #d34336;
                            }

                            paper-input-decorator.invalid /deep/ .focused-underline {
                                /* line and color when the input is invalid and focused */
                                background-color: #d34336;
                            }
                                /* FORM STYLES */
                            .form_container {
                                color: #fff;
                            }
                            .form_container .message_error {
                                font-size: 12px;
                                color: #d34336;
                                margin-left: -60px;
                            }
                            .form_container fieldset {
                                width: 23%;
                                margin: 0px 2% 0px 0px;
                                border: none;
                                float: left;
                            }
                            .form_container fieldset div.input_wrapper {
                                width: 100%;
                                float: left;
                            }
                            input:-webkit-autofill, #inputId:-webkit-autofill, input:-webkit-autofill {
                                -webkit-box-shadow:0 0 0 50px #141414 inset;
                                color: #fff !important;
                                -webkit-text-fill-color: #fff;
                            }
                            .form_container fieldset input.input {
                                width: 100%;
                                color: #fff !important;
                                font-size: 16px;
                                border: 2px solid #000;
                                outline: none;
                                margin: 5px 0px;
                                padding: 10px;
                                background: #222222 !important;
                            }
                            .form_container fieldset p {
                                font-size: 14px;
                                width: 100%;
                                float: left;
                                padding: 0px 2px;
                                margin-bottom: 0px;
                                text-align: center;
                            }
                            .form_button {
                                padding: 15px;
                                width: 100%;
                                float: left;
                                color: #fff;
                                background: #278BF6;
                                border: 2px solid #278BF6;
                                border-radius: 5px;
                                -moz-border-radius: 5px;
                                -webkit-border-radius: 5px;
                                -ms-border-radius: 5px;
                                -o-border-radius: 5px;
                                font: bold 30px 'CounterStrikeRegular', 'Trebuchet Ms', helvetica;
                            }
                            .form_button:hover {
                                /* BUTTON STYLING */
                                cursor: pointer;
                                cursor: hand;
                                background: #1B61AC;
                                border-color: #1B61AC;
                            }
                            .form_button.modal_button {
                                padding: 15px;
                                width: 25%;
                                float: right;
                                margin: 0px 5px;
                            }
                            .form_button.modal_button.dark {
                                color: #fff;
                                background: #222222;
                                border: 2px solid #222222;
                            }
                            .form_button.modal_button.dark:hover {
                                color: #fff;
                                background: #000;
                                border: 2px solid #000;
                            }

                            /* POPUP */
                            .popup {
                                position: relative;
                                left: 240px;
                                top: 55px;
                                width: 205px;
                                height: 65px;
                                padding: 5px;
                                background: #000;
                                -webkit-border-radius: 10px;
                                -moz-border-radius: 10px;
                                border-radius: 10px;
                                border: #d34336 solid 4px;
                            }
                            .popup p {
                                position: absolute;
                                width: auto !important;
                                color: #fff;
                            }
                            .popup:after {
                                content: '';
                                position: absolute;
                                border-style: solid;
                                border-width: 15px 25px 15px 0;
                                border-color: transparent #000;
                                display: block;
                                width: 0;
                                z-index: 1;
                                left: -25px;
                                top: 30px;
                            }
                            .popup:before {
                                content: '';
                                position: absolute;
                                border-style: solid;
                                border-width: 18px 28px 18px 0;
                                border-color: transparent #d34336;
                                display: block;
                                width: 0;
                                z-index: 0;
                                left: -32px;
                                top: 27px;
                            }
                            .game_select_popup {
                                position: absolute;
                                left: 120px;
                                width: 205px;
                                height: 50px;
                                padding: 5px;
                                background: #000;
                                -webkit-border-radius: 10px;
                                -moz-border-radius: 10px;
                                border-radius: 10px;
                                border: #d34336 solid 4px;
                            }

                            .game_select_popup:after {
                                content: '';
                                position: absolute;
                                border-style: solid;
                                border-width: 15px 0 15px 25px;
                                border-color: transparent #000;
                                display: block;
                                width: 0;
                                z-index: 1;
                                right: -25px;
                                top: 16px;
                            }

                            .game_select_popup:before {
                                content: '';
                                position: absolute;
                                border-style: solid;
                                border-width: 18px 0 18px 28px;
                                border-color: transparent #d34336;
                                display: block;
                                width: 0;
                                z-index: 0;
                                right: -32px;
                                top: 13px;
                            }
                            .hide {
                                display:none;
                            }
                        </style>
                        <!--Cropper styles-->
                        <link rel="stylesheet" href="<?=$relative_path?>bootstrap-3.2.0/css/bootstrap-modal.css">
                        <link href="<?=$relative_path?>cropper/css/cropper.min.css" rel="stylesheet">
                        <link href="<?=$relative_path?>cropper/css/crop-avatar.css" rel="stylesheet">
                        <div class="crop-avatar" id="cropAvatar">
                            <!-- Current avatar -->
                            <div class="avatar-view" id="current_image">
                                <img src="{{imageUrl}}" alt="No Image">
                            </div>
                            <!-- Cropping modal -->
                            <div class="modal fade" id="avatar-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="avatar-form" action="<?=$relative_path?>cropper/crop-game-image.php" enctype="multipart/form-data" method="post">
                                            <div class="modal-header">
                                                <button class="close" data-dismiss="modal" type="button">&times;</button>
                                                <h4 class="modal-title" id="avatar-modal-label" style="color: #000;">Upload An Image For Your Game</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="avatar-body">
                                                    <!-- Upload image and data -->
                                                    <div class="avatar-upload">
                                                        <input class="avatar-src" name="avatar_src" type="hidden">
                                                        <input class="avatar-data" name="avatar_data" type="hidden">
                                                        <label for="avatarInput" style="color: #000;">Upload Image</label>
                                                        <input class="avatar-input" id="avatarInput" name="avatar_file" type="file">
                                                    </div>
                                                    <!-- Crop and preview -->
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="avatar-wrapper"></div>
                                                        </div>
<!--                                                        <div class="col-md-3">-->
<!--                                                            <div class="avatar-preview preview-lg"></div>-->
<!--                                                            <div class="avatar-preview preview-md"></div>-->
<!--                                                            <div class="avatar-preview preview-sm"></div>-->
<!--                                                        </div>-->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="form_button modal_button" type="submit">Save</button>
                                                <button class="form_button modal_button dark" data-dismiss="modal" type="button">Close</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="loading" aria-label="Loading" role="img" tabindex="-1"></div>
                        </div>
                        <form id="edit_game" class="form_container" method="post" action="update-game.php" style="width: 100%;">
                            <div class="flash_element_container">
                                <div class="game_select_popup hide" id="game_select_popup">
                                    <p>You must select your game!</p>
                                </div>
                                <core-selector selected="{{game_url}}" valueattr="url">
                                    <template repeat="{{gameUrl in gameUrls(sourceDocument)}}">
                                        <style type="text/css">
                                            .game_item {
                                                float: left;
                                                padding: 5px;
                                                margin: 5px;
                                                color: #fff;
                                                background: #141414;
                                                border: 5px solid #141414;
                                                border-radius: 5px;
                                                -moz-border-radius: 5px;
                                                -webkit-border-radius: 5px;
                                                -ms-border-radius: 5px;
                                                -o-border-radius: 5px;
                                            }
                                            .game_item:hover {
                                                border: 5px solid #1B61AC;
                                            }
                                            .game_item.core-selected {
                                                background: #000;
                                                border: 5px solid #278BF6;
                                            }
                                            div.core-selected:after {
                                                padding: 10px;
                                                margin: 10px;
                                            }
                                            .flash_overlay {
                                                position: absolute;
                                                z-index: 1;
                                                width: 270px;
                                                height: 248px;
                                                margin: -255px 0px 0px -2px;
                                            }
                                        </style>
                                        <div class="game_item" url="{{gameUrl}}">
                                            <flash-game-element url="{{gameUrl}}" width="270"></flash-game-element>
                                            <div class="flash_overlay"></div>
                                        </div>
                                    </template>
                                </core-selector>
                            </div>
                            <fieldset>
                                <!--<div class="input_wrapper third">-->
                                    <!--<input placeholder="Game Title" name="name" class="input" />-->
                                <!--</div>-->
                                <!--<div class="input_wrapper third">-->
                                    <!--<input placeholder="Paste URL of Game Page" type="url" class="input" value="{{url}}" />-->
                                <!--</div>-->
                                <!--<input type="hidden" name="game_url" value="{{game_url}}" class="input" />-->
                                <div class="input_wrapper third">
                                    <paper-input-decorator label="Game Title" floatinglabel="" layout="" vertical="" error="A title is Required!">
                                        <input is="core-input" placeholder="Game Title" aria-label="Game Title" name="title" required="required" value="<?=$title?>" />
                                    </paper-input-decorator>
                                </div>
                                <input name="game_url" type="hidden" value="{{game_url}}" required="required" />
                                <div class="input_wrapper third">
                                    <paper-input floatingLabel label="URL where Game is Played" type="url" pattern="(https?://.+)?" error="Input is not a URL!"
                                                 value="{{url}}" id="provided_url"></paper-input>
                                </div>
                                <div class="popup hide" id="url_popup">
                                    <p>You must enter the URL where your game is played!</p>
                                </div>
                                <div class="input_wrapper third">
                                    <input type="submit" value="Edit Game" class="form_button" />
                                </div>
                                <input id="image_url" type="hidden" name="image_url" value="{{imageUrl}}" />
                                <input type="hidden" name="game_id" value="<?=$id?>" />
                                <core-ajax id="ajax" auto
                                           url="{{proxifyUrl(url)}}"
                                           handleAs="document"
                                           on-core-response="{{handleResponse}}">
                                </core-ajax>
                            </fieldset>
                        </form>
                    </template>
                    <script type="text/javascript" src="<?=$relative_path?>js/lib/jquery-ui.min.js"></script>
                    <script>
                        (function() {
                            var gameUrls = [];
                            var gameSelected = false;

                            Polymer('selected-element');
                            Polymer('game-scraper-element', {
                                domReady: function () {
                                    var shadowRoot = this.shadowRoot || this;

                                    var example = new CropAvatar($(this.$.cropAvatar));
                                    this.imageUrl = '<?=$img?>';

                                    var me = this;
                                    $(window).on('avatar_src_change', function(e, url) {
                                        me.imageUrl = url;
                                    });

                                    var gameSelectPopup = $(shadowRoot.querySelector('#game_select_popup'));
                                    var flashElement = $(shadowRoot.querySelector('.flash_element_container'));
                                    $('game-scraper-element').on('core-activate', function() {
                                        gameSelected = true;
                                        if(gameSelectPopup.css('display') == 'block') {
                                            gameSelectPopup.toggle('drop', {direction: 'right'}, 200);
                                        }
                                        flashElement.removeClass('error');
                                    });

                                    var editGameForm = $(shadowRoot.querySelector('#edit_game'));
                                    var providedUrlInput = $(shadowRoot.querySelector('#provided_url'));
                                    var providedUrl = this.url;
                                    var urlPopup = $(shadowRoot.querySelector('.popup#url_popup'));
                                    editGameForm.on('submit', function(e) {
                                        if(providedUrl != '' && !gameSelected) {
                                            e.preventDefault();
                                            if(gameSelectPopup.css('display') == 'none') {
                                                gameSelectPopup.toggle('drop', {direction: 'right'}, 200);
                                            }
                                            flashElement.addClass('error');
                                        } else {
                                            editGameForm.submit();
                                        }
                                    });
                                    var me = this;
                                    providedUrlInput.on('change', function() {
                                        if(urlPopup.css('display') == 'block') {
                                            urlPopup.toggle('drop', {direction: 'left'}, 200);
                                        }
                                        providedUrl = me.url;
                                    });
                                },

                                proxifyUrl : function(url){
                                    var proxyUrl = "";
                                    if(url !== undefined && url != ""){
                                        proxyUrl = location.origin + "/proxy.php?q=" + btoa(url);
                                        gameUrls.push(url);
                                    }
                                    return proxyUrl;
                                },
                                unproxifyUrl : function(url) {
                                    var unproxifiedUrl = "";
                                    if(url !== undefined && url != ""){
                                        urlMatch = url.match(/.*\?q=([^&]*)&?.*/i);
                                        if (urlMatch && urlMatch[1]) {
                                            try {
                                                unproxifiedUrl = atob(urlMatch[1]);
                                            } catch (exception) {
                                                unproxifiedUrl = url;
                                            }
                                        }
                                    }
                                    return unproxifiedUrl;
                                },
                                handleResponse: function (e) {
                                    this.sourceDocument = e.detail.response;
                                },
                                elementSelector : 'object embed[src], object[data], param[value], div[data-src]',
                                elementAttributes : ['data-src', 'src', 'data', 'value'],
                                gameUrls : function(document) {
                                    var jDoc = $(document);
                                    var originalUrl;
                                    var element = this;
                                    // Find game urls in html element src and data attributes.
                                    jDoc.find(this.elementSelector).each(function (key, el) {
                                        for(var idx = 0; idx < element.elementAttributes.length; idx++) {
                                            originalUrl = $(el).attr(element.elementAttributes[idx]);
                                            if(originalUrl) {
                                                gameUrls.push(element.unproxifyUrl(originalUrl));
                                                console.log(gameUrls[-1]);
                                            }
                                        }
                                    });

                                    // Find game urls in script element source.
                                    var rawMatches;
                                    var cleansedMatch;
                                    jDoc.find('script').each(function () {
                                        rawMatches = element.getMatches(this.textContent, /(['|"])([http|\/\/].*\.swf.*)(['|"])/igm, 2);
                                        $(rawMatches).each( function () {
                                            cleansedMatch = this.replace(/"|'/g,'');
                                            var end = cleansedMatch.indexOf(',');
                                            if (end == -1) end = cleansedMatch.length;
                                            cleansedMatch = cleansedMatch.substr(0, end);
                                            gameUrls.push(element.makeAbsoluteUrl(cleansedMatch));
                                        });
                                    });
                                    return this.unique(gameUrls);
                                },
                                unique : function(array) {
                                    return array.filter(function(item, itemIndex) {
                                        return (array.indexOf(item) == itemIndex)
                                    });
                                },
                                makeAbsoluteUrl : function(originalUrl) {
                                    var absoluteUrl;
                                    if (/^http/i.test(originalUrl)) {
                                        absoluteUrl = originalUrl;
                                    } else if (/^\/\//i.test(originalUrl)) {
                                        absoluteUrl = location.protocol + originalUrl;
                                    } else if (/^\/[^\/]/i.test(originalUrl)) {
                                        absoluteUrl = location.protocol + '//' + location.host + originalUrl;
                                    } else {
                                        absoluteUrl = location.href.substr(0, location.href.lastIndexOf("/") + 1) + originalUrl;
                                    }
                                    return absoluteUrl;
                                },
                                getMatches : function(string, regex, capturingGroupIndex) {
                                    capturingGroupIndex || (capturingGroupIndex = 1); // default to the first capturing group
                                    var matches = [];
                                    var match;
                                    while (match = regex.exec(string)) {
                                        matches.push(match[capturingGroupIndex]);
                                    }
                                    return matches;
                                }
                            });
                        })();
                    </script>
                </polymer-element>
            </div>
        </div>
    </div>
    <footer>
        <div class="footer_content">
            <p>Copyright &copy <?=date('Y')?></p>
            <a href="http://haden.moonrockfamily.ca"><img src="<?=$relative_path?>images/logos/stamp-light-bevel.png" alt="HH" class="stamp" /></a>
        </div>
    </footer>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-45303914-2', 'auto');
        ga('send', 'pageview');

    </script>
    <script src="<?=$relative_path?>js/main.js"></script>
</body>
</html>