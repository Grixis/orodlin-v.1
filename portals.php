<?php
/**
 *   File functions:
 *   Astral plans
 *
 *   @name                 : portals.php                            
 *   @copyright            : (C) 2006 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @version              : 1.2
 *   @since                : 28.06.2006
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
// $Id: portals.php 398 2006-06-28 15:56:19Z thindil $

$title = "Astralny plan";
require_once("includes/head.php");

/**
* Get the localization for game
*/
require_once("languages/".$player -> lang."/portals.php");

if (!isset($_GET['step']) || !ereg("^[0-6]*$", $_GET['step']))
{
    error(ERROR." (<a href=\"city.php\">".BACK."</a>)");
}

if ($player -> location != 'Altara' && $player -> location != 'Astralny plan')
{
    error(ERROR." (<a href=\"city.php\">".BACK."</a>)");
}

if ($player -> energy < 1)
{
    error(ERROR." (<a href=\"city.php\">".BACK."</a>)");
}

if (!isset($_GET['go']))
{
    if ($player -> hp <= 0)
    {
        $db -> Execute("UPDATE `players` SET `miejsce`='Altara' WHERE `id`=".$player -> id);
        error(ERROR." (<a href=\"city.php\">".BACK."</a>)");
    }
}

/**
 * Function to count astral plans
 */
function coutastralplans($intPlayerid, $strPlanname)
{
    global $db;

    $objAmount = $db -> Execute("SELECT `amount` FROM `astral_plans` WHERE `owner`=".$intPlayerid." AND `name`='".$strPlanname."' AND `location`='V'") or die($db -> ErrorMsg());
    if ($objAmount -> fields['amount'] == 1)
    {
        $db -> Execute("DELETE FROM `astral_plans` WHERE `owner`=".$intPlayerid." AND `name`='".$strPlanname."' AND `location`='V'");
    }
        else
    {
        $db -> Execute("UPDATE `astral_plans` SET `amount`=`amount`-1 WHERE `owner`=".$intPlayerid." AND `name`='".$strPlanname."' AND `location`='V'");
    }
}

$intNumber2 = $_GET['step'] + 1;
$strName = "M".$intNumber2;
$objPlan = $db -> Execute("SELECT `amount` FROM `astral_plans` WHERE `owner`=".$player -> id." AND `name`='".$strName."' AND `location`='V'");
if (!$objPlan -> fields['amount'])
{
    error(NO_MAP." (<a href=\"city.php\">".BACK."</a>)");
}
$objPlan -> Close();

$arrMonsters = array(MONSTER1, MONSTER2, MONSTER3, MONSTER4, MONSTER5, MONSTER6, MONSTER7);

/**
 * Main menu
 */
if (!isset($_GET['go']))
{
    $arrPlans = array(PLAN1, PLAN2, PLAN3, PLAN4, PLAN5, PLAN6, PLAN7);
    $smarty -> assign(array("Desc1" => DESC1,
                            "Desc2" => DESC2,
                            "Desc3" => DESC3,
                            "Planname" => $arrPlans[$_GET['step']],
                            "Monstername" => $arrMonsters[$_GET['step']],
                            "Afight" => A_FIGHT));
    $db -> Execute("UPDATE `players` SET `fight`=".$intNumber2.", `miejsce`='Astralny plan' WHERE `id`=".$player -> id);
    $_GET['go'] = '';
}

/**
 * Fight with monster
 */
if (isset($_GET['go']) && $_GET['go'] == 'fight')
{
    $objFight = $db -> Execute("SELECT `fight`, `hp` FROM `players` WHERE `id`=".$player -> id);

    /**
     * Fight with monster
     */
    if ($objFight -> fields['fight'] != 9999)
    {
        if (!$objFight -> fields['fight'])
        {
            error(ERROR." (<a href=\"city.php\">".BACK."</a>)");
        }
        require_once ("includes/funkcje.php");
        require_once("includes/turnfight.php");

	require_once('class/monster_class.php');
	require_once('class/fight_class.php');
        $arrPower = array(7000, 8000, 9000, 10500, 12000, 15000, 19000);
        $arrAgility = array(7000, 8000, 9000, 10500, 12000, 15000, 19000);
        $arrSpeed = array(7000, 8000, 9000, 10500, 12000, 15000, 19000);
        $arrConstitution = array(7000, 8000, 9000, 10500, 12000, 15000, 19000);
        $arrWisdom = array(7000, 8000, 9000, 10500, 12000, 15000, 19000);
        $arrInteligence = array(7000, 8000, 9000, 10500, 12000, 15000, 19000);
        $arrHitpoints = array(15000, 20000, 25000, 30000, 40000, 50000, 60000);
        $arrLevel = array(40, 60, 80, 100, 150, 200, 250);
        $arrExp1 = array(500, 750, 1000, 1250, 1500, 1750, 2000);
        $arrExp2 = array(600, 900, 1250, 1500, 1750, 2000, 2500);
        $arrBonus = array(1, 1.5, 2, 2.5, 3, 3.5, 4);
        $intNumber = $objFight -> fields['fight'] - 1;

	if (!isset($_SESSION['amount'])) {
		$_SESSION['amount'] = 1;
		$_SESSION['mon0']['id'] = 9999;
		$_SESSION['mon0']['user'] = $arrMonsters[$intNumber];
		$_SESSION['mon0']['level'] = $arrLevel[$intNumber];
		$_SESSION['mon0']['credits'] = 0;
		$_SESSION['mon0']['exp'] = rand($arrExp1[$intNumber],$arrExp2[$intNumber]);
		$_SESSION['mon0']['actionpoints'] = $arrConstitution[$intNumber];
		$_SESSION['mon0']['defence'] = $arrConstitution[$intNumber];
		$_SESSION['mon0']['hp'] = $arrHitpoints[$intNumber];
		$_SESSION['mon0']['damage'] = $arrPower[$intNumber];
		$_SESSION['mon0']['hitmodificator'] = $arrAgility[$intNumber];
		$_SESSION['mon0']['missmodificator'] = $arrAgility[$intNumber];
		$_SESSION['mon0']['attackspeed'] = $arrSpeed[$intNumber];

		$monster = new Monster(999,1,0);
		$attacker = new Fighter($player -> id);

		for ($k = 0; $k < $_SESSION['amount']; $k++) {
			//each monster identifier
			$strIndex = 'mon'.$k;
			$_SESSION[$strIndex]['id'] = $monster -> id;
			//each monster hit points
			$_SESSION[$strIndex]['hp'] = $monster -> hp;
			//each monster action points
			if ($attacker -> speed > $monster -> attackspeed) {
				$_SESSION[$strIndex]['ap'] = 1;
				}
			else {
				$_SESSION[$strIndex]['ap'] = floor($monster -> attackspeed / $attacker -> speed);
				if ($_SESSION[$strIndex]['ap'] > 5) {
					$_SESSION[$strIndex]['ap'] = 5;
					}
				}
			$tmpActionArr[$k][0] = $monster -> attackspeed;
			$tmpActionArr[$k][1] = $k;
			}
		$tmpActionArr[$k][0] = $attacker -> speed;
		$tmpActionArr[$k][1] = -1;

		/**
		* function to compare elements of actionArr
		*/
		function aacmp($a,$b) {
			if ($a[0] == $b[0]) return 0;
			return ($a[0] > $b[0]) ? -1 : 1;
			}

		usort($tmpActionArr,"aacmp");
		for ($k = 0; $k <= $_SESSION['amount']; $k++) {
			$actionArr[$k] = $tmpActionArr[$k][1];
			}
		$_SESSION['actionArr'] = $actionArr;
		$_SESSION['exhaust']=0;
		if ($attacker -> speed > $monster -> attackspeed) {
			$_SESSION['points'] = floor($attacker -> speed / $monster -> attackspeed);
			if ($_SESSION['points'] > 5) {
				$_SESSION['points'] = 5;
				}
			}
		else {
			$_SESSION['points'] = 1;
			}
		$_SESSION['round']=0;
		}
        $strAdress = "portals.php?step=".$intNumber."&amp;go=fight";
        turnfight($strAdress);

/*        $enemy = array('name' => $arrMonsters[$intNumber], 
                       'strength' => $arrPower[$intNumber], 
                       'agility' => $arrAgility[$intNumber], 
                       'hp' => $arrHitpoints[$intNumber], 
                       'level' => $arrLevel[$intNumber], 
                       'endurance' => $arrConstitution[$intNumber], 
                       'speed' => $arrSpeed[$intNumber], 
                       'exp1' => $arrExp1[$intNumber], 
                       'exp2' => $arrExp2[$intNumber]);

        $arrehp = array();
        $strAdress = "portals.php?step=".$intNumber."&amp;go=fight";
        $span = ($enemy['level'] / $player -> level);
        if ($span > 2) 
        {
            $span = 2;
        }
        $intExpgain = ceil(rand($enemy['exp1'],$enemy['exp2']) * $span);
        if (!isset ($_POST['action'])) 
        {
            turnfight($intExpgain, 0, '', $strAdress);
        } 
            else 
        {
            turnfight($intExpgain, 0, $_POST['action'], $strAdress);
        }*/
    }
    
    $objFight = $db -> Execute("SELECT `fight`, `hp` FROM `players` WHERE `id`=".$player -> id);

    /**
     * Win fight and search for components
     */
    if ($objFight -> fields['hp'] > 0 && $objFight -> fields['fight'] == 0 && $_SESSION['result'] == 'win') 
    {
        $arrChanges = array(90, 85, 80, 75, 70, 65, 60);
        $intRoll = rand(1, 100);
        if ($intRoll <= $arrChanges[$intNumber])
        {
            $strFindcomponent = COMPONENT;
            $strType = "C".$intNumber;
            $objTest = $db -> Execute("SELECT `amount` FROM `astral` WHERE `owner`=".$player -> id." AND `type`='".$strType."' AND `location`='V'") or die($db -> ErrorMsg());
            if ($objTest -> fields['amount'])
            {
                $db -> Execute("UPDATE `astral` SET `amount`=`amount`+1 WHERE `owner`=".$player -> id." AND `type`='".$strType."' AND `location`='V'") or die($db -> ErrorMsg());
            }
                else
            {
                $db -> Execute("INSERT INTO `astral` (`owner`, `type`) VALUES(".$player -> id.", '".$strType."')") or die($db -> ErrorMsg());
            }
            $objTest -> Close();
        }
            else
        {
            $strFindcomponent = NOTHING;
        }
        $objItem = $db -> Execute("SELECT `id` FROM `equipment` WHERE `owner`=".$player -> id." AND `type`='W' AND status='E'");
        if ($objItem -> fields['id'])
        {
            $strSkill = 'atak';
        }
            else
        {
            $objItem2 = $db -> Execute("SELECT `id` FROM `equipment` WHERE `owner`=".$player ->id." AND `type`='B' AND status='E'");
            if ($objItem2 -> fields['id'])
            {
                $strSkill = 'shoot';
            }
                else
            {
                $strSkill = 'magia';
            }
        }
        $objItem -> Close();
        $db -> Execute("UPDATE `players` SET `energy`=`energy`-1, `miejsce`='Altara', `".$strSkill."`=`".$strSkill."`+".$arrBonus[$intNumber]." WHERE `id`=".$player -> id);
        coutastralplans($player -> id, $strName);
        error(YOU_WIN.$strFindcomponent);
    }
	if (isset($_SESSION['result'])) unset($_SESSION['result']);

    /**
     * Lost fight
     */
    if ($objFight -> fields['fight'] == 0 && $objFight -> fields['hp'] == 0) 
    {
        $db -> Execute("UPDATE `players` SET `energy`=`energy`-1, `miejsce`='Altara' WHERE `id`=".$player -> id);
        coutastralplans($player -> id, $strName);
        error(YOU_LOST2);
    }

    /**
     * Escape from fight
     */
    if ($objFight -> fields['fight'] == 9999) 
    {
        $db -> Execute("UPDATE `players` SET `energy`=`energy`-1, `miejsce`='Altara' WHERE `id`=".$player -> id);
        coutastralplans($player -> id, $strName);
        error(YOU_ESCAPE);
    }
    $objFight -> Close();
}

/**
* Assign variables to template and display page
*/
$smarty -> assign(array("Go" => $_GET['go'],
                        "Step" => $_GET['step']));
$smarty -> display ('portals.tpl');

require_once("includes/foot.php");
?>
