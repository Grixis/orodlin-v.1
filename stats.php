<?php
/**
 *   File functions:
 *   Player statistics and general informations about account
 *
 *   @name                 : stats.php                            
 *   @copyright            : (C) 2004,2005,2006 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @author               : mori <ziniquel@users.sourceforge.net>
 *   @version              : 1.2
 *   @since                : 12.09.2006
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
// $Id: stats.php 566 2006-09-13 09:31:08Z thindil $

$title = "Statystyki";
require_once("includes/head.php");

/**
* Get the localization for game
*/
require_once("languages/".$player -> lang."/stats.php");

/**
* Assign variables to template
*/
$smarty -> assign(array("Crime" => ''));

if ($player -> ap > 0 || $player -> age < 4) 
{
    $smarty -> assign ("Ap", $player -> ap." (<a href=\"ap.php\">".A_USE."</a>)<br />");
} 
    else 
{
    $smarty -> assign ("Ap", $player -> ap."<br />");
}
if ($player -> race == '') 
{
    $smarty -> assign ("Race", '');
} 
    else 
{
    $smarty -> assign ("Race", $player -> race."<br />");
}
if ($player -> clas == '') 
{
    $smarty -> assign ("Clas", '');
} 
    else 
{
    $smarty -> assign ("Clas", $player -> clas."<br />");
}
if ($player -> gender == '') 
{
    $smarty -> assign ("Gender", '');
} 
    else 
{
    if ($player -> gender == 'M') 
    {
        $gender = GENDER_M;
    } 
        else 
    {
        $gender = GENDER_F;
    }
    $smarty -> assign ("Gender", $gender."<br />");
}
if ($player -> deity == '') 
{
    $smarty -> assign ("Deity", '<a href="card.php?action=deity">'.A_SELECT.'</a><br />');
} 
    else 
{
    $smarty -> assign ("Deity", $player -> deity." (<a href=\"card.php?action=deity\">".A_CHANGE."</a>)<br />");
}
if ($player -> faith - 100 > -1)
{
	$smarty -> assign("PW", $player -> faith.' <b>(<a href="temple.php?view=prayer">+</a>)</b><br />');
}
else
{
	$smarty -> assign("PW", $player -> faith.'<br />');
}
$rt = ($player -> wins + $player -> losses);

/**
 * Select player rank
 */
require_once('includes/ranks.php');
$strRank = selectrank($player -> rank, $player -> gender);

/**
 * Bonuses from equipment to stats
 */
require_once('includes/statsbonus.php');
$arrCurstats = statbonus();

/**
 * Bonus from bless
 */
$objBless = $db -> Execute("SELECT `bless`, `blessval` FROM `players` WHERE `id`=".$player -> id);
if (!empty($objBless -> fields['bless']))
{
    $arrBless = array('agility', 'strength', 'inteli', 'wisdom', 'szyb', 'wytrz', 'hp', 'ability', 'alchemia', 'fletcher', 'atak', 'shoot', 'unik', 'magia', 'breeding', 'mining', 'lumberjack', 'herbalist', 'jeweller', 'hutnictwo');
    $intKey = array_search($objBless -> fields['bless'], $arrBless);
    if ($intKey < 6)
    {
        $arrCurstats[$intKey] = $arrCurstats[$intKey] + $objBless -> fields['blessval'];
    }
    $arrPrays = array(AGI, STR, INTELI, WIS, SPE, CON, HITPTS, SMI, ALC, FLE, WEA, SHO, DOD, CAS, BRE, MINI, LUMBER, HERBS, JEWEL, METAL);
    $smarty -> assign(array("Blessfor" => BLESS_FOR,
                            "Pray" => "<br />".$arrPrays[$intKey],
                            "Blessval" => "(".$objBless -> fields['blessval'].")<br />"));
}
    else
{
    $smarty -> assign(array("Blessfor" => "",
                            "Pray" => "",
                            "Blessval" => ""));
}
$objBless -> Close();

$arrStats = array($player -> agility, $player -> strength, $player -> inteli, $player -> wisdom, $player -> speed, $player -> cond);
$arrCurstats2 = array();
$i = 0;
foreach ($arrCurstats as $fltStats)
{
    if ($fltStats != $arrStats[$i])
    {
        $arrCurstats2[$i] = "(".$fltStats.")<br />";
    }
        else
    {
        $arrCurstats2[$i] = "<br />";
    }
    $i++;
}
$arrStatstext = array(T_AGI, T_STR, T_INT, T_WIS, T_SPEED, T_CON);

/**
 * Name of player location
 */
$strLocation = $player -> location;
if ($player -> location == 'Altara')
{
    $strLocation = $city1;
}
if ($player -> location == 'Ardulith')
{
    $strLocation = $city2;
}
$strAntd = '';
$strAntn = '';
$strAnti = '';
$strResurect = '';
if ($player -> antidote_d > 0)
{
    $strAntd = DYNALCA_ANTIDOTE.$player -> antidote_d."<br/>";
}
if ($player -> antidote_n > 0)
{
    $strAntn = NUTARI_ANTIDOTE.$player -> antidote_n."<br/>";
}
if ($player -> antidote_i > 0)
{
    $strAnti = ILLANI_ANTIDOTE.$player -> antidote_i."<br/>";
}
if ($player -> resurect > 0)
{
    $strResurect = TIME_RESURECT.$player -> resurect."<br/>";
}

$ref = $db -> Execute("SELECT sum(points) as refs FROM reputation WHERE player_id=".$player -> id);
$ref = $ref -> fields['refs'];

$smarty -> assign(array("Stats" => $arrStats,
                        "Curstats" => $arrCurstats2,
                        "Tstats2" => $arrStatstext,
                        "Mana" =>  $player -> mana, 
                        "Location" => $strLocation."<br />", 
                        "Age" => $player -> age."<br />", 
                        "Logins" => $player -> logins."<br />", 
                        "Ip" => $player -> ip."<br />", 
                        "Email" => $_SESSION['email']."<br />", 
                        "Smith" => $player -> smith."<br />", 
                        "Alchemy" => $player -> alchemy."<br />", 
                        "Fletcher" => $player -> fletcher."<br />", 
                        "Attack" => $player -> attack."<br />", 
                        "Shoot" => $player -> shoot."<br />", 
                        "Miss" => $player -> miss."<br />", 
                        "Magic" => $player -> magic."<br />",
                        "Total" => $player -> wins."/".$player -> losses."/".$rt."<br />", 
                        "Lastkilled" => $player -> lastkilled."<br />", 
                        "Lastkilledby" => $player -> lastkilledby,
                        "Leadership" => $player -> leadership."<br />",
                        "Rank" => $strRank."<br />",
                        "Breeding" => $player -> breeding."<br />",
                        "Mining" => $player -> mining."<br />",
                        "Lumberjack" => $player -> lumberjack."<br />",
                        "Herbalist" => $player -> herbalist."<br />",
                        "Jeweller" => $player -> jeweller."<br />",
                        "Hutnictwo" => $player -> hutnictwo."<br />",
                        "Ant_d" => $strAntd,
                        "Ant_n" => $strAntn,
                        "Ant_i" => $strAnti,
                        "Resurect" => $strResurect,
                        "Rep" => $ref,
                        "Reputation" => REPUT_TXT,
                        "Statsinfo" => STATS_INFO,
                        "Tstats" => T_STATS,
                        "Tinfo" => T_INFO,
                        "Trank" => T_RANK,
                        "Tloc" => T_LOC,
                        "Tlogins" => T_LOGINS,
                        "Tage" => T_AGE,
                        "Tip" => T_IP,
                        "Temail" => T_EMAIL,
                        "Tclan" => T_CLAN,
                        "Tability" => T_ABILITY,
                        "Tsmith" => T_SMITH,
                        "Talchemy" => T_ALCHEMY,
                        "Tlumber" => T_LUMBER,
                        "Tfight" => T_FIGHT,
                        "Tshoot" => T_SHOOT,
                        "Tdodge" => T_DODGE,
                        "Tcast" => T_CAST,
                        "Tleader" => T_LEADER,
                        "Tap" => T_AP,
                        "Trace" => T_RACE,
                        "Tclass" => T_CLASS2,
                        "Tdeity" => T_DEITY,
                        "Tgender" => T_GENDER,
                        "Tmana" => T_MANA,
                        "Tpw" => T_PW,
                        "Tfights" => T_FIGHTS,
                        "Tlast" => T_LAST,
                        "Tlast2" => T_LAST2,
                        "Tbreeding" => T_BREEDING,
                        "Tmining" => T_MINING,
                        "Tlumberjack" => T_LUMBERJACK,
                        "Therbalist" => T_HERBALIST,
                        "Tjeweller" => T_JEWELLER,
                        "Thutnictwo" => T_HUTNICTWO));
$cape = $db -> Execute("SELECT `power` FROM `equipment` WHERE `owner`=".$player -> id." AND `type`='C' AND `status`='E'");
$maxmana = ($player -> inteli + $player -> wisdom);
$maxmana = $maxmana + (($cape -> fields['power'] / 100) * $maxmana);
$cape -> Close();
if ($player -> mana < $maxmana) 
{
    $smarty -> assign ("Rest", "[<a href=\"rest.php\">".A_REST."</a>]<br />");
} 
    else 
{
    $smarty -> assign ("Rest", "<br />");
}
if ($player -> clas == "Złodziej") 
{
    $smarty -> assign ("Crime", "<b>".CRIME_T."</b></td><td>".$player -> crime."<br />");
}

if (!empty($player-> gg)) 
{
    $smarty -> assign ("GG", "<b>".GG_NUM."</b> ".$player -> gg."<br />");
} 
    else 
{
    $smarty -> assign ("GG", "");
}
$tribe = $db -> Execute("SELECT `name` FROM `tribes` WHERE id=".$player -> tribe);
if ($tribe -> fields['name']) 
{
    $smarty -> assign(array("Tribe" => "<a href=\"tribes.php?view=my\">".$tribe -> fields['name']."</a><br />",
                            "Triberank" => "<b>".TRIBE_RANK."</b> ".$player -> tribe_rank."<br />"));
} 
    else 
{
    $smarty -> assign(array("Tribe" => NOTHING."<br />", 
                            "Triberank" => ""));
}
$tribe -> Close();

/**
* Select gender
*/
if (isset ($_GET['action']) && $_GET['action'] == 'gender') 
{
    $smarty -> assign(array("Genderm" => GENDER_M,
                            "Genderf" => GENDER_F,
                            "Aselect" => A_SELECT));
    if ($player -> gender) 
    {
        error (YOU_HAVE);
    }
    if (isset ($_GET['step']) && $_GET['step'] == 'gender') 
    {
        if (!isset($_POST['gender']))
        {
            error(NO_GENDER);
        }
        $db -> Execute("UPDATE `players` SET `gender`='".$_POST['gender']."' WHERE `id`=".$player -> id);
        error (YOU_SELECT);
    }
}

/**
* Initialization of variable
*/
if (!isset($_GET['action'])) 
{
    $_GET['action'] = '';
}

/**
* Assign variable and display page
*/
$smarty -> assign ("Action", $_GET['action']);
$smarty -> display ('stats.tpl');

require_once("includes/foot.php");
?>
