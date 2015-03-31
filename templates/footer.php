<?
if(!authUser()) {
    ?>
    <div class="modal fade" id="join-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="margin: 0; border-bottom: none;">
                    <button class="close modal_close" data-dismiss="modal" type="button">&times;</button>
                    <!--                            <h4 class="modal-title" id="avatar-modal-label" style="color: #000;">Find Out What You're Missing!</h4>-->
                </div>
                <div class="modal-body" style="margin-top: -20px;">
                    <div class="avatar-body" style="text-align: center;">
                        <h2>With MyGames you can add and manage YOUR OWN favorite flash games!</h2>
                        <h3><a class="form_button modal_button" style="margin: 10px auto; float: none;" href="<?=$relative_path?>pages/join.php">Join Now</a> to find out what you're missing!</h3>
                        <div style="margin: 25px 0px;"></div>
                        <div id="vimeoWrap">
                            <iframe src="https://player.vimeo.com/video/123472626" width="800" height="450" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <h3 style="float: left; margin-top: 12px;">Already have an account? <a href="<?=$relative_path?>pages/login.php">Login</a></h3>
                    <button class="form_button modal_button dark modal_close" data-dismiss="modal" type="button">Close</button>
                </div>
            </div>
        </div>
    </div>
    <?
}
?>
<footer>
    <div class="footer_content">
        <p>Copyright &copy <?=date('Y')?></p>
        <a href="http://haden.moonrockfamily.ca"><img src="<?=$relative_path?>images/logos/stamp-light-bevel.png" alt="HH" class="stamp" /></a>
    </div>
</footer>
<? include($relative_path . 'includes/scripts.php'); ?>
<?
if(!authUser()) {
    ?>
    <!--Vimeo js-->
    <script type="text/javascript" src="<?=$relative_path?>js/lib/froogaloop.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#header').on('click', '.modal_launch_button', function(e) {
                e.preventDefault();
                $('#join-modal').modal('show');
            });
            if($('#vimeoWrap').length > 0) {
                $('#join-modal').on('click', '.modal_close', function() {
                    vimeoWrap = $('#vimeoWrap');
                    vimeoWrap.html( vimeoWrap.html() );
                    $('#join-modal').modal('hide');
                });
            }
        });
    </script>
    <?
}
?>