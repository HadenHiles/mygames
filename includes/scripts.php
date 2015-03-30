<script src="<?=$relative_path?>js/lib/jquery-2.1.3.min.js"></script>
<script src="<?=$relative_path?>js/lib/jquery-ui.min.js"></script>
<!--Script for directional hover-->
<script src="<?=$relative_path?>js/lib/modernizr.custom.97074.js"></script>
<script type="text/javascript" src="<?=$relative_path?>/js/lib/jquery.hoverdir.js"></script>
<!--Bootstrap js-->
<script type="text/javascript" src="<?=$relative_path?>bootstrap-3.2.0/js/bootstrap.min.js"></script>
<script type="text/javascript">
    $(function() {
        $(' #da-thumbs > li ').each( function() { $(this).hoverdir({
            hoverDelay : 55
        }); } );
    });
</script>
<script src="<?=$relative_path?>js/main.js"></script>
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-45303914-2', 'auto');
    ga('send', 'pageview');

</script>