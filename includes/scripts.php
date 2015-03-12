<script src="<?=$relative_path?>js/lib/jquery-2.1.3.min.js"></script>
<!--Script for directional hover-->
<script src="<?=$relative_path?>js/lib/modernizr.custom.97074.js"></script>
<script type="text/javascript" src="<?=$relative_path?>/js/lib/jquery.hoverdir.js"></script>
<script type="text/javascript">
    $(function() {
        $(' #da-thumbs > li ').each( function() { $(this).hoverdir({
            hoverDelay : 55
        }); } );
    });
</script>
<script src="<?=$relative_path?>js/main.js"></script>