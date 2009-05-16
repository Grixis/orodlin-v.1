<?php
/**
 *   File functions: Shows results history of fights PvP
 *   Game time
 *
 *   @name                 : fight_logs.php                            
 *   @copyright            : (C) 2004,2005,2006 Orodlin Team based on Vallheru Engine ver 1.3
 *   @author               : Dellas <Pawel.Dudziec@gmail.com>
 *   @version              : 1.3
 *   @since                : 21.03.2007
 *
 */

$title = "Historia walk";
require_once("includes/head.php");

global $player;

/**
* Get the localization for game
*/
require_once("languages/".$player -> lang."/fightlogs.php");

if (!isset($_GET['view']) ||!ereg("^[1-9][0-9]*$", $_GET['view']))
{
    $_GET['view'] = $player -> id;
}
$strInfo = ($_GET['view'] == $player ->id)? INFO : INFO1.' <a href="view.php?view='.$_GET['view'].'">'.INFO2.' '.$_GET['view'].'</a> '.INFO3;

$query = $db -> Execute("SELECT `logs` FROM `fight_logs` WHERE `owner`=".$_GET['view']);
$strLog = $query -> fields['logs'];

$arrLogs1 = array();
$arrText = array();
$arrDate = array();

$arrLogs1 = explode( "\n", $strLog );

foreach ($arrLogs1 as $key => $value)
{
    $arrTemp = explode("\t", $value);
    $arrDate[$key] = $arrTemp[0];
    $arrText[$key] = $arrTemp[1];
}

/**
* Assign variables to template and display page
*/
$smarty -> assign(array("Tinfo" => $strInfo,
                        "arrText" => $arrText,
                        "arrDate" => $arrDate));
$smarty -> display ('fightlogs.tpl');
require_once("includes/foot.php");
?>
