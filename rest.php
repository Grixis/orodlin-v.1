<?php
/**
 *   File functions:
 *   Rest - regenerate mana for a energy
 *
 *   @name                 : rest.php                            
 *   @copyright            : (C) 2004,2005,2006 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @version              : 1.2
 *   @since                : 11.08.2006
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
// $Id: rest.php 566 2006-09-13 09:31:08Z thindil $

$title = "Odpoczynek"; 
require_once("includes/head.php");

/**
* Get the localization for game
*/
require_once("languages/".$player -> lang."/rest.php");


$arrEquip = $player -> equipment();
$arrRings = array(R_INT, R_WIS);
$arrStat = array('inteli', 'wisdom');
if ($arrEquip[9][0])
{
    $arrRingtype = explode(" ", $arrEquip[9][1]);
    $intAmount = count($arrRingtype) - 1;
    $intKey = array_search($arrRingtype[$intAmount], $arrRings);
    if ($intKey != NULL)
    {
        $strStat = $arrStat[$intKey];
        $player -> $strStat = $player -> $strStat + $arrEquip[9][2];
    }
}
if ($arrEquip[10][0])
{
    $arrRingtype = explode(" ", $arrEquip[10][1]);
    $intAmount = count($arrRingtype) - 1;
    $intKey = array_search($arrRingtype[$intAmount], $arrRings);
    if ($intKey != NULL)
    {
        $strStat = $arrStat[$intKey];
        $player -> $strStat = $player -> $strStat + $arrEquip[10][2];
    }
}
$maxmana = ($player -> inteli + $player -> wisdom);
$maxmana = $maxmana + (($arrEquip[8][2] / 100) * $maxmana);
$manainfo = round($maxmana - $player -> mana);
if ($manainfo < 1)
{
	$manainfo = 0;
}

$manarest = 5* $player -> level ;
$restcount = floor($manainfo / $manarest);
if ($restcount <1)
{
	$restcount = 0;
}

$smarty -> assign(array("Manarest" => $manarest,
	                        "Manainfo" => $manainfo,
	                        "Restcount" => $restcount,
	                        "Trest" => T_REST,
                            "Restinfo" => REST_INFO,
                            "Restinfo1" => REST_INFO1,
                            "Restinfo2" => REST_INFO2,
                            "Iwant" => I_WANT,
                            "Rmana" => R_MANA,
                            "Arest" => A_REST,
                            "Aback" => A_BACK));

$smarty -> display ('rest.tpl');

if (isset($_GET['akcja']) && $_GET['akcja'] == 'all') 
{
    if (!isset($_POST['amount']))
    {
        error(HOW_MANY);
    }
    if (!ereg("^[1-9][0-9]*$", $_POST['amount'])) 
    {
        error (ERROR);
    }
    integercheck($_POST['amount']);
    if (!isset($_POST['amount'])) 
    {
        error(ERROR);
    }
    if ($player -> energy < $_POST['amount']*0.1) 
    {
        error (NO_ENERGY);
    }
    if ($player -> hp <= 0) 
    {
        error (YOU_DEAD);
    }
    $zpm = ($_POST['amount']*$manarest);
    $energia = $_POST['amount']*0.1;
    if ( $manainfo == 0)
    {
    	$zpm =0;
    	$db -> Execute("UPDATE `players` SET `energy`=`energy`-".$energia." WHERE `id`=".$player -> id);
    	error (YOU_REST.$zpm.YOU_REST2.$energia.YOU_REST3);
    }
    else	
    {
    	if (($zpm + $player -> mana) >= $maxmana)
    	{
    	    $db -> Execute("UPDATE `players` SET `pm`=".$maxmana.", `energy`=`energy`-".$energia." WHERE `id`=".$player -> id);
      	    error (YOU_REST.$manainfo.YOU_REST2.$energia.YOU_REST3);
    	}
    	else
    	{	
    	    $db -> Execute("UPDATE `players` SET `pm`=`pm`+".$zpm.", `energy`=`energy`-".$energia." WHERE `id`=".$player -> id);
    	}
    error (YOU_REST.$zpm.YOU_REST2.$energia.YOU_REST3);
    }
}

require_once("includes/foot.php");
?>
