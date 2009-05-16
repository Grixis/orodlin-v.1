<?php
/**
 *   File functions:
 *   Activation account
 *
 *   @name                 : aktywacja.php
 *   @copyright            : (C) 2004,2005,2006 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @author               : eyescream <tduda@users.sourceforge.net>
 *   @version              : 1.4
 *   @since                : 24.08.2007
 *
 */

// Published under GNU GPL 2 or later. See /install/README file for details.
// $Id$

require_once ('includes/main/base.php');
require_once ('includes/getlang.php');
GetLang();
GetLoc('mainpage');
GetLoc ('aktywacja');

require_once ('includes/main/counter.php');
require_once ('includes/main/record.php');
require_once ('includes/main/usersever.php');
require_once ('includes/right.php');
require_once ('includes/security.php');


if (isset($_GET['kod']) && strictInt($_GET['kod']))
{
    $arrAct = $db -> GetRow('SELECT * FROM `aktywacja` WHERE `aktyw`='.$_GET['kod']);
    if (!isset($arrAct['lang']))
        GetLoc('aktywacja');
    else
        GetLoc('aktywacja', $arrAct['lang']);

    if (!empty($arrAct))
    {
        $db -> Execute('INSERT INTO `players` (`user`, `email`, `pass`, `lang`, `ip`, `profile`) VALUES(\''.$arrAct['user'].'\',\''.$arrAct['email'].'\',\''.$arrAct['pass'].'\',\''.$arrAct['lang'].'\', \''.$arrAct['ip'].'\', \'\')');
        $arrID = $db -> GetRow('SELECT `id` FROM `players` WHERE `user`=\''.$arrAct['user'].'\'');
		$db -> Execute('INSERT INTO `minerals` (`owner`, `pine`) VALUES ('.$arrID['id'].', 50)');
		$db -> Execute('DELETE FROM `aktywacja` WHERE `aktyw`='.$_GET['kod']);
        require_once ('includes/main/online.php');
        $smarty -> display ('activ.tpl');
    }
}
else
{
    $smarty -> assign ('Error', ERROR);
    $smarty -> display ('error.tpl');
    exit;
}

$db -> Close();
?>
