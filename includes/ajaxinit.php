<?php
header('Content-Type: text/json; charset=utf-8');

require_once('includes/sessions.php');
require_once('includes/config.php');
require_once('libs/Smarty.class.php');
require_once('class/player_class.php');

$smarty = new Smarty;
$smarty -> compile_check = true;

$strEmail = $db -> qstr($_SESSION['email'],get_magic_quotes_gpc());
$player = $db -> getRow("SELECT `id`, `user`, `rank`, `lang`  FROM `players` WHERE `email`=".$strEmail);
$db -> Execute("UPDATE `players` SET `lpv`=".time().", `page`='Tawerna' WHERE `email`=".$strEmail);
?>
