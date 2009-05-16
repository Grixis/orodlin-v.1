<?php
/**
 *   File functions:
 *   Hospital - heal and resurrect players
 *
 *   @name                 : hospital.php                            
 *   @copyright            : (C) 2004,2005,2006,2007 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @version              : 1.3
 *   @since                : 24.02.2007
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
// $Id: hospital.php 898 2007-02-24 21:24:28Z thindil $

$title = "Uzdrowiciel";
require_once("includes/head.php");

/**
* Get the localization for game
*/
require_once("languages/".$player -> lang."/hospital.php");

if ($player -> location != 'Altara' && $player -> location != 'Ardulith') 
{
    error(ERROR);
}

$mytribe = $db -> Execute("SELECT `hospass` FROM `tribes` WHERE `id`=".$player -> tribe);
$resurect_time = $db -> Execute("SELECT `resurect` FROM `players` WHERE `id`=".$player -> id);
//$crneed = ($player -> max_hp - $player -> hp) * 3;
if (!isset ($_GET['action'])) 
{
    if ($player -> hp == $player -> max_hp) 
    {
        error(STOP_WASTE." (<a href=\"city.php\">".BACK."</a>)");
    }
    $smarty -> assign ("hpneeded",($player -> max_hp - $player -> hp));
    $smarty -> assign ("sure","");
    $smarty -> assign ("Costtext",(COSTTEXT." 3 ".GOLD_COINS2));
    if ($player -> hp > 0) 
    {
        $crneed = ($player -> max_hp - $player -> hp) * 3;
		$basecrneed = 3;
        if ($player -> tribe > 0) 
        {
            if ($mytribe -> fields['hospass'] == "Y" && $player -> hp > 0) 
            {
                $crneed = ceil($crneed / 2);
				$basecrneed = 1.5;
                $smarty -> assign ("Costtext",(COSTTEXT." 1.5 ".GOLD_COINS2));
                $smarty -> assign ("sure",SURE_IT);
//                error(COULD_YOU." <a href=hospital.php?action=heal>".A_HEAL."</a>?<br />".SURE_IT.$crneed.GOLD_COINS);
            }
//            $smarty -> assign ("Need",$crneed);
        }
        if ($crneed < 0)
        {
            $crneed = 0;
			$maxpoints = 0;
        }
		else
		{
			$maxpoints = floor($player -> credits / $basecrneed);
		}
        if ($maxpoints < 1) 
        {
            error(NO_MONEY2.$crneed.GOLD_COINS2." (<a href=\"city.php\">".BACK."</a>)");
        }
		if ($maxpoints > ($player -> max_hp - $player -> hp)) $maxpoints = ($player -> max_hp - $player -> hp);
        $smarty -> assign ("Need",$crneed);
		$smarty -> assign ("Maxpoints",$maxpoints);
    }
    if ($player -> hp <= 0) 
    {
    	$crneed = (75 * $player -> level);
    	if ($resurect_time -> fields['resurect'] > 0)
    	{
    		$been_resurect=BEEN_UNDER_RESURECT_POTION;
    	}
        else
        {
            if ($crneed > $player -> credits) 
            {
                error(NO_MONEY.$crneed.GOLD_COINS2." (<a href=\"city.php\">".BACK."</a>)");
                
            }
       }
       $smarty -> assign ("Need",$crneed);
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'heal') 
{
    if ($player -> hp <= 0) 
    {
        error(BAD_ACTION);
    }
    $crneed = ($player -> max_hp - $player -> hp) * 3;
    if ($mytribe -> fields['hospass'] == "Y")
    {
        $crneed = ceil($crneed / 2);
    }
    if ($crneed < 0)
    {
        $crneed = 0;
    }
    if ($crneed > $player -> credits) 
    {
        error(NO_MONEY.$crneed.GOLD_COINS." (<a href=\"city.php\">".BACK."</a>)");
    }
    $db -> Execute("UPDATE `players` SET `hp`=`max_hp`, `credits`=`credits`-".$crneed." WHERE `id`=".$player -> id);
    error(YOU_HEALED." (<a href=\"city.php\">".BACK."</a>)");
}
if (isset($_GET['action']) && $_GET['action'] == 'pheal') 
{
    if ($player -> hp <= 0) 
    {
        error(BAD_ACTION);
    }
    $hpoints = (int)$_POST["amount"];
    if (!isset($hpoints) || $hpoints=="" || $hpoints > ($player -> max_hp - $player -> hp) )
    {
        error();
    }
    $crneed = $hpoints * 3;
    if ($mytribe -> fields['hospass'] == "Y")
    {
        $crneed = ceil($crneed / 2);
    }
    if ($crneed < 0)
    {
        $crneed = 0;
    }
    if ($crneed > $player -> credits) 
    {
        error(NO_MONEY.$crneed.GOLD_COINS." (<a href=\"city.php\">".BACK."</a>)");
    }
    $db -> Execute("UPDATE `players` SET `hp`=`hp`+".$hpoints.", `credits`=`credits`-".$crneed." WHERE `id`=".$player -> id);
    error(YOU_HEALED2." ".$hpoints." ".HPOINTS." (<a href=\"city.php\">".BACK."</a>)");
}
if($mytribe)
{
    $mytribe -> Close();
}

if (isset($_GET['action']) && $_GET['action'] == 'ressurect') 
{
    require_once('includes/resurect.php');
}

/**
* Initialization of variables
*/
if (!isset($_GET['action'])) 
{
    $_GET['action'] = '';
    $smarty -> assign(array("Resurect" => $resurect_time -> fields['resurect'],
    	                    "Ayes" => YES,
                            "Couldyou" => COULD_YOU,
                            "Couldyou2" => COULD_YOU2,
                            "Itcost" => IT_COST,
                            "Itcost2" => IT_COST2,
                            "Goldcoins" => GOLD_COINS2,
                            "Aheal" => A_HEAL,
                            "Iwant" => IWANT,
                            "hpoints" => HPOINTS,
                            "Been_under" => BEEN_UNDER_RESURECT_POTION));
}

/**
* Assign variables to template and display page
*/
$smarty -> assign("Action", $_GET['action']);
$smarty -> display('hospital.tpl');

require_once("includes/foot.php");
?>
