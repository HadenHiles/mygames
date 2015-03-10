<?
if($_REQUEST['game_url'] && $_REQUEST['title'] && $_REQUEST['image_url']) {
    $game_url = $_REQUEST['game_url'];
    echo $game_url . '<br />';
    $title = $_REQUEST['title'];
    echo $title . '<br />';
    $image_url = $_REQUEST['image_url'];
    echo '<img src="' . $image_url . '"/>';
}