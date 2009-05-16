<?php
/**
 *   File functions:
 *   Logout with rest for players without houses
 *
 *   @name				 : poorhouse.php
 *   @copyright		   : (C) 2004,2005,2006,2007 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author				: Tril <tril@faerun.com.pl>
 *   @version			  : 1.0
 *   @since				 : 19.09.2007
 *
 */

// Published under GNU GPL 2 or later. See /install/README file for details.
// $Id$
$title = 'Schronisko';
/**
* Logout with rest
*/
if(isset($_GET['did']) && preg_match('/[1-9][0-9]*/', $_GET['did'])) {

	require_once 'includes/sessions.php';
	require_once 'includes/config.php';

	// End session
	if(empty($_SESSION['email']))
	{
		date_default_timezone_set('Europe/Warsaw');
		/**
		* Check avaible languages
		*/
		$dir = opendir('languages/');
		$arrLanguage = array();
		$i = 0;
		while ($file = readdir($dir))
		{
			if (!preg_match("/.htm*$/i", $file) && !preg_match("/\.$/i", $file))
			{
				$arrLanguage[$i] = $file;
				++$i;
			}
		}
		closedir($dir);
		/**
		* Get the localization for game
		*/
		$strLanguage = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
		foreach ($arrLanguage as $strTrans)
		{
			if (preg_match('/^'.$strTrans.'/i', $strLanguage))
			{
				$strTranslation = $strTrans;
				break;
			}
		}
		if (!isset($strTranslation))
		{
			$strTranslation = 'pl';
		}
		require_once("languages/".$strTranslation."/poorhouse.php");
		require_once('libs/Smarty.class.php');
		$smarty = new Smarty;
		$smarty -> compile_check = true;
		$smarty -> assign ("Error", E_SESSIONS);
		$smarty -> display ('error.tpl');
		exit;
	}

	$stat = $db -> GetRow("SELECT `id`, `level`, `credits`, `lang` FROM `players` WHERE `email`='".$_SESSION['email']."'");
	// Id's don't match
	if ($stat['id'] != $_GET['did'])
	{
		require_once 'includes/head.php';
		require_once 'languages/'.$player -> lang.'/poorhouse.php';
		error(ERROR);
	}
	// Player hasn't enough cash
	if ($stat['credits'] < $stat['level']*50)
	{
		require_once 'includes/head.php';
		require_once 'languages/'.$player -> lang.'/poorhouse.php';
		error(NO_CASH);
	}
	// All ok, destroy session, set rest, display logout.tpl
	$db -> Execute('UPDATE `players` SET `rest`=\'Y\', `lpv`=`lpv`-180, `credits`=`credits`-'.(50*$stat['level']).' WHERE `id`='.$stat['id']);
	session_unset();
	session_destroy();
	date_default_timezone_set('Europe/Warsaw');
	require_once "languages/".$stat['lang']."/logout.php";
	require_once('libs/Smarty.class.php');
	$smarty = new Smarty;
	$smarty -> compile_check = true;
	$smarty -> assign("Gamename", $gamename);
	$smarty -> display ('logout.tpl');
}
/**
* View main page
*/
	else
{
	require_once 'includes/head.php';
	require_once 'languages/'.$player -> lang.'/poorhouse.php';
	$smarty -> display('poorhouse.tpl');
	require_once 'includes/foot.php';
}
?>