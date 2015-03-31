<?
if(isset($page_name)) {
    ?>
    <title>My Games | <?=$page_name?></title>
    <?
} else {
    ?>
    <title>My Games</title>
    <?
}
?>
<?
if(!authUser()) {
    ?>
    <link rel="stylesheet" href="<?=$relative_path?>bootstrap-3.2.0/css/bootstrap-modal.css">
    <?
}
?>
<link href="<?=$relative_path?>css/fonts.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="<?=$relative_path?>css/direction-hover/direction-hover.css" />
<link href="<?=$relative_path?>css/styles.css" type="text/css" rel="stylesheet" />
<noscript><link rel="stylesheet" type="text/css" href="<?=$relative_path?>css/direction-hover/noJS.css"/></noscript>
<link rel="apple-touch-icon" href="<?=$relative_path?>images/apple-touch-icon.png" />
<link rel="shortcut icon" href="<?=$relative_path?>images/favicon.ico" />
<meta charset="utf-8" />
<meta name="description" content="One website, all your flash games. Add, manage, and play games. All using our intuitive and personalized interface.">
<meta name="robots" content="index,follow">
<meta name="keywords" content="mygames, my games, one website for all of your flash games, games, flash games, online games, save flash games, save games, play, play games, add flash games, play flash games, manage flash games, manage games, add games, edit games, edit flash games" />