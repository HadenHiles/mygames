<?
$relative_path = '../';
require_once($relative_path . 'auth/authenticate.php');

if (!authUser()) {
    header('location: login.php');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Game | My Games</title>
    <meta charset="utf-8" />

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
                <h1 style="text-align: center;">Add Game</h1>
                <game-scraper-element></game-scraper-element>
                <polymer-element name="game-scraper-element" attributes="url">
                    <template>
                        <style type="text/css">
                            .flash_element_container {
                                width: 72%;
                                float: right;
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
                                color: #E60000;
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
                                background: #141414 !important;
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
                                background: #141414;
                                border: 2px solid #141414;
                            }
                            .form_button.modal_button.dark:hover {
                                color: #fff;
                                background: #000;
                                border: 2px solid #141414;
                            }
                        </style>
                        <!--Cropper styles-->
                        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
                        <link href="<?=$relative_path?>cropper/css/cropper.min.css" rel="stylesheet">
                        <link href="<?=$relative_path?>cropper/css/crop-avatar.css" rel="stylesheet">
                        <div class="crop-avatar" id="cropAvatar">
                            <!-- Current avatar -->
                            <div class="avatar-view" title="Upload an Image">
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
                        <form id="add_game" class="form_container" method="post" action="save-game.php" style="width: 100%;">
                            <div class="flash_element_container">
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
                                        <input is="core-input" placeholder="Game Title" aria-label="Game Title" name="title" required="required" />
                                    </paper-input-decorator>
                                </div>
                                <input type="hidden" name="game_url" value="{{game_url}}" required="required" />
                                <div class="input_wrapper third">
                                    <paper-input floatingLabel label="URL of Game Page" type="url" pattern="https?://.+" error="Input is not a URL!"
                                                 value="{{url}}"></paper-input>
                                </div>
                                <div class="input_wrapper third">
                                    <input type="submit" value="Add Game" class="form_button" />
                                </div>
                                <input id="image_url" type="hidden" name="image_url" value="{{imageUrl}}" />
                                <core-ajax id="ajax" auto
                                           url="{{proxifyUrl(url)}}"
                                           handleAs="document"
                                           on-core-response="{{handleResponse}}">
                                </core-ajax>
                            </fieldset>
                        </form>
                    </template>
                    <script>
                        (function() {
                            var gameUrls = [];
                            Polymer('selected-element');
                            Polymer('game-scraper-element', {
                                domReady: function () {
                                    //					this.url = location.href;
                                    //					this.sourceDocument = "todd really does rock; says Haden!";
                                    var example = new CropAvatar($(this.$.cropAvatar));
                                    this.imageUrl = '<?=$relative_path?>images/upload-image.jpg';

                                    var me = this;
                                    $(window).on('avatar_src_change', function(e, url) {
                                        me.imageUrl = url;
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
                                            unproxifiedUrl = atob(urlMatch[1]);
                                        }
                                    }
                                    return unproxifiedUrl;
                                },
                                handleResponse: function (e) {
                                    this.sourceDocument = e.detail.response;
                                },
                                elementSelector : 'object embed[src], object[data], param[value]',
                                gameUrls : function(document) {
                                    var jDoc = $(document);
                                    var originalUrl;
                                    var element = this;
                                    // Find game urls in html element src and data attributes.
                                    jDoc.find(this.elementSelector).each(function (e) {
                                        originalUrl = $(this).attr('src') || $(this).attr('data');
                                        if(originalUrl) {
                                            gameUrls.push(element.unproxifyUrl(originalUrl));
                                            console.log(gameUrls[-1]);
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
            <p>Copyright &copy <? date('Y')?> </p>
            <a href="http://haden.moonrockfamily.ca"><img src="<?=$relative_path?>images/logos/stamp-light-bevel.png" alt="HH" class="stamp" /></a>
        </div>
    </footer>
    <script src="<?=$relative_path?>js/main.js"></script>
</body>
</html>