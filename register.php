<?php
/**
 *   File functions:
 *   Register new players
 *
 *   @name                 : register.php                            
 *   @copyright            : (C) 2005,2006 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @version              : 1.2
 *   @since                : 14.07.2006
 *
 */

//
//
//       This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; either version 2 of the License, or
//   (at your option) any later version.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
//
// $Id: register.php 479 2006-07-14 18:57:16Z thindil $

require_once ('includes/main/base.php');
require_once ('includes/sessions.php');
require_once ('includes/getlang.php');
GetLang ();
GetLoc ('mainpage');
GetLoc ('register');

RegistrationCloseRoutine ();


require_once ('includes/main/online.php');
require_once ('includes/main/record.php');
require_once ('includes/main/counter.php');
require_once ('includes/main/usersever.php');

require_once ('includes/right.php');

$smarty -> assign(array("Pagetitle" => REGISTER));

if (!isset($_GET['action']))
{
    $chars = 'acefghijkmrstvwxyz1234578';
    $charcount = strlen($chars) - 1;
    $code = '';
    for ($i=0;$i<10;$i++)
    {
        $code .= $chars[rand(0,$charcount)];
    }
    $_SESSION['imagecode'] = $code;
    $smarty -> assign(array("Lang" => $arrLanguage));
}

if (isset ($_GET['action']) && $_GET['action'] == 'register') 
{
/**
* Check imagecode
*/
    if (!isset($_SESSION['imagecode']) || !isset($_POST['imagecode']) || $_POST['imagecode'] != $_SESSION['imagecode']) 
    {
        $smarty -> assign ("Error", ERROR_IMAGECODE.RET);
        $smarty -> display ('error.tpl');
        exit;
    }
    unset($_SESSION['imagecode']);

/**
* Check for empty fields
*/
    if (!$_POST['user'] || !$_POST['email'] || !$_POST['vemail'] || !$_POST['pass'] ) 
    {
        $smarty -> assign ("Error", EMPTY_FIELDS);
        $smarty -> display ('error.tpl');
        exit;
    }
    
/**
* Email adress validation
*/       
    require_once('includes/verifymail.php');
    if (MailVal($_POST['email'], 2)) 
    {
        $smarty -> assign ("Error", BAD_EMAIL);
        $smarty -> display ('error.tpl');
        exit;
    }
    require_once('includes/verifypass.php');
    verifypass($_POST['pass'],'register');

/**
* Check nick
*/
	if (strlen($_POST['user']) > 15)
	{
        $smarty -> assign ("Error", LONG_NICK);
        $smarty -> display ('error.tpl');
        exit;
	}

    $strUser = $db -> qstr($_POST['user'], get_magic_quotes_gpc());
    $query = $db -> Execute("SELECT id FROM players WHERE user=".$strUser);
    $dupe1 = $query -> RecordCount();
    $query -> Close();  
    if ($dupe1 > 0) 
    {
        $smarty -> assign ("Error", BAD_NICK);
        $smarty -> display ('error.tpl');
        exit;
    }

	require_once ('includes/limits.php');
	if (!ValidNick (stripslashes ($_POST['user']))) {
		$smarty->assign ('Error', INVALID_NICK);
		$smarty->display ('error.tpl');
		exit;
	}

/**
* Check mail adress in database
*/   
  
   $strEmail = $db -> qstr($_POST['email'], get_magic_quotes_gpc());
   $query = $db -> Execute("SELECT id FROM players WHERE email=".$strEmail);
   $dupe2 = $query -> RecordCount();
   $query -> Close();


    $NumberActSameMail = 0;
    $ListActSameMail = $db->Execute ("SELECT id FROM aktywacja WHERE email=".$strEmail);
    if (!empty($ListActSameMail))
	{
    	$NumberActSameMail = $ListActSameMail -> RecordCount();
        $ListActSameMail -> Close();
	}


   if ($dupe2 > 0 or $NumberActSameMail > 0)
   {
       $smarty -> assign ("Error", EMAIL_HAVE);
       $smarty -> display ('error.tpl');
       exit;
   }

/**
* Check email adress writed on registration
*/ 
    if ($_POST['email'] != $_POST['vemail']) 
    {
        $smarty -> assign ("Error", EMAIL_MISS);
        $smarty -> display ('error.tpl');
        exit;
    }
    
    $_POST['lang'] = strip_tags($_POST['lang']);
    if (!in_array($_POST['lang'], $arrLanguage))
    {
        exit;
    }
    $_POST['user'] = strip_tags($_POST['user']);
    $strUser = $db -> qstr($_POST['user'], get_magic_quotes_gpc());
    $_POST['email'] = strip_tags($_POST['email']);
    $strEmail = $db -> qstr($_POST['email'], get_magic_quotes_gpc());
    $_POST['pass'] = strip_tags($_POST['pass']);
    $aktw = rand(1,10000000);
    $data = date("y-m-d");
    $strDate = $db -> DBDate($data);
    $ip = $HTTP_SERVER_VARS['REMOTE_ADDR'];
    $message = WELCOME_TO." ".$gamename.YOUR_LINK."  ".$gameadress.ACTIV_LINK.$aktw.NICE_PLAYING." ".$gamename.". ".$adminname;
    $adress = $_POST['email'];
    $subject = SUBJECT." ".$gamename;
    require_once('mailer/mailerconfig.php');
    if (!$mail -> Send()) 
    {
        $smarty -> assign("Error", EMAIL_ERROR.$mail -> ErrorInfo);
        $smarty -> display('error.tpl');
        exit;
    }
    $strPass = MD5($_POST['pass']);
    $db -> Execute("INSERT INTO aktywacja (user, email, pass, aktyw, data, ip, lang) VALUES(".$strUser.", ".$strEmail.", '".$strPass."', ".$aktw.", ".$strDate." , '".$ip."' ,'".$_POST['lang']."')") or die($db -> ErrorMsg());

    /**
    * Players they`ve ever been registered there
    */ 
    include("counter/usersever.php");
    $plik=fopen("counter/usersever.php","w+");
    fputs($plik,'<?php $ilosc='.(++$ilosc).' ?>');
    fclose($plik);
}

/**
* Initialization of variable
*/
if (!isset($_GET['action'])) 
{
    $_GET['action'] = '';
}

/**
* Assign variables and display page
*/
$smarty -> assign(array("Action" => $_GET['action'], "Meta" => ''));
$smarty -> display('register.tpl');

$db -> Close();
?>
