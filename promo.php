<?php
/**
 *   File functions:
 *   Promotion materials and graphics
 *
 *   @name                 : promo.php
 *   @copyright            : (C) 207 Orodlin Team based on Gamers-Fusion ver 2.5 and Vallheru Engine
 *   @author               : lethal2 (l3thal2[at]gmail.com)
 *   @version              : 
 *   @since                : 18.05.2007
 *
//
// $Id: index.php 835 2006-11-22 17:40:22Z thindil $

*/
require_once ('includes/config.php');
require_once ('includes/main/base.php');
require_once ('includes/getlang.php');
GetLang ();
GetLoc ('mainpage');
GetLoc ('promo');

GameCloseRoutine ();

require_once ('includes/main/record.php');
require_once ('includes/main/counter.php');
require_once ('includes/main/online.php');
require_once ('includes/main/usersever.php');
require_once ('includes/right.php');



        $smarty -> assign( array ('Pagetitle' => PROMO_TITLE));
        $smarty -> display('promo.tpl');
    $db -> Close();
?>
