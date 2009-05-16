<?php
/**
 *   File functions:
 *   Lumbermill - making arrows and bows
 *
 *   @name                 : lumbermill.php
 *   @copyright            : (C) 2004,2005,2006,2007 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @author               : eyescream <tduda@users.sourceforge.net>
 *   @version              : 1.4
 *   @since                : 23.03.2007
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
// $Id$

$title='Tartak';
require_once('includes/head.php');
require_once('includes/checkexp.php');
require_once('includes/artisan.php');

/**
* Get the localization for game
*/
require_once('languages/'.$player -> lang.'/lumbermill.php');

if ($player -> location != 'Altara')
{
    error (ERROR);
}

/**
 * Function to create items
 */
function createitem()
{
    global $db;
    global $player;
    global $arrItem;
    global $intAbility;
    global $intItems;
    global $intGainexp;
    global $intChance;
    global $intCost;
    global $arrMaxbonus;
    global $intKey;

    if (rand(1,100) <= $intChance)
    {
        $strName = $arrItem['name'];
        $intPower = $arrItem['power'];
        $intSpeed = $arrItem['szyb'];
        $intDur = $arrItem['wt'];
        $blnSpecial = false;
        if (floor( rand(1,100) - $intAbility / 100) < 21)     // special item! Fletcher skill increases the chance
        {
            $intRoll3 = rand(1, 101);
            $intItembonus = rand(ceil($player -> fletcher/2), ceil($player -> fletcher)); // includes bonuses (pure fletchery, gnome, artisan, bless + now gained fletchery)!
            if ($intRoll3 < 34 && $arrItem['type'] == 'R')  // dragon arrows, the only special item for non-artisans
            {
                $intPowerbonus = $intItembonus + $player -> strength / 50;
                $intPower += $intPowerbonus > $arrItem['level'] * 10 ? $arrItem['level'] * 10 : $intPowerbonus;
                if ($player -> clas == ARTISAN)
                	$intPower += $player -> strength / 20;
                $strName = DRAGON2.$arrItem['name'];
                $blnSpecial = true;
            }
            if( $player -> clas == ARTISAN && $intRoll3 > 33)
            {
                if ($intRoll3 < 67 && $arrItem['type'] == 'B')
                {   // elven bows
                    $intAgibonus = $intItembonus + ($player -> agility / 50);
                    $intSpeed += $intAgibonus > $arrItem['szyb'] * 10 ? $arrItem['szyb'] * 10 : $intAgibonus;
                    $intSpeed += $player -> agility / 20;
                    $strName = ELFS1.$arrItem['name'];
                    $blnSpecial = true;
                }
                if ($intRoll3 > 66)
                {
                    if ($arrItem['type'] == 'B')    // dwarven bow
                    {
                        $intDurbonus = $intItembonus + ($player -> inteli / 50);
                        $strName = DWARFS1.$arrItem['name'];
                        $intDurbonus = $intDurbonus > $arrItem['wt'] * 10 ? $arrItem['wt'] * 10 : $intDurbonus;
                        $intDurbonus += $player -> inteli / 20;
                    }
                        else
                    {
                        $intDurbonus = min( rand(1, ceil($player -> fletcher / 10)), 100);  // more arrows than usual
                    }
                    $intDur += $intDurbonus;
                    $blnSpecial = true;
                }
            }
        }
        $intPower = round($intPower);
        $intSpeed = round($intSpeed);
        $intDur = round($intDur);
        $intGainexp += $arrItem['level'] * ($blnSpecial ? 100 + $player -> fletcher / 10 : 1);
        $intItems ++;
        $intAbility = round($intAbility + $arrItem['level'] / 100,3);
        if ($arrItem['type'] == 'B')
        {
            $arrRepair = array(2, 8, 32, 128, 512);
            $intRepaircost = $arrItem['level'] * $arrRepair[$intKey];
            $test = $db -> Execute('SELECT `id` FROM `equipment` WHERE `owner`='.$player -> id.' AND `name`=\''.$strName.'\' AND `wt`='.$intDur.' AND `type`=\'B\' AND `status`=\'U\' AND `power`='.$intPower.' AND `zr`=0 AND `szyb`='.$intSpeed.' AND `maxwt`='.$intDur.' AND `poison`=0 AND `cost`='.$intCost);
            if (!$test -> fields['id'])
            {
                $db -> Execute('INSERT INTO `equipment` (`owner`, `name`, `power`, `type`, `cost`, `wt`, `minlev`, `maxwt`, `amount`, `magic`, `szyb`, `twohand`, `repair`) VALUES('.$player -> id.', \''.$strName.'\', '.$intPower.', \'B\', '.$intCost.', '.$intDur.', '.$arrItem['level'].', '.$intDur.', 1, \'N\',  '.$intSpeed.',\'Y\', '.$intRepaircost.')');
            }
                else
            {
                $db -> Execute('UPDATE `equipment` SET `amount`=`amount`+1 WHERE `id`='.$test -> fields['id']);
            }
            $test -> Close();
        }
            else
        {
            $test = $db -> Execute('SELECT `id` FROM `equipment` WHERE `owner`='.$player -> id.' AND name=\''.$strName.'\' AND `power`='.$intPower.' AND `status`=\'U\' AND `cost`='.$intCost);
            if (!$test -> fields['id'])
            {
                $db -> Execute('INSERT INTO `equipment` (`owner`, `name`, `power`, `type`, `cost`, `status`, `minlev`, `wt`) VALUES('.$player -> id.', \''.$strName.'\', '.$intPower.', \'R\', '.$intCost.', \'U\', '.$arrItem['level'].', '.$intDur.')');
            }
                 else
            {
                $db -> Execute('UPDATE `equipment` SET `wt`=`wt`+'.$intDur.' WHERE `id`='.$test -> fields['id']);
            }
            $test -> Close();
        }
    }
        else
    {
        $intAbility += 0.005 * $arrItem['level'];
    }
}

/**
 * Lumberjack licenses
 */
if (isset($_GET['mill']) && $_GET['mill'] == 'licenses')
{
    $oldFetchMode = $db -> SetFetchMode(ADODB_FETCH_NUM);
    $arrLevel = $db -> GetRow('SELECT `level` FROM `lumberjack` WHERE owner='.$player -> id);
    $db -> SetFetchMode($oldFetchMode);
    $intLevel = empty($arrLevel) ? 0 : ++$arrLevel[0];
    if ($intLevel > 3)
    {
        error(NO_LICENSES);
    }
    if (!isset($_GET['step']) || $_GET['step'] != 'buy')
    {
        $_GET['step'] = '';
        $arrLicenses = array(LICENSE1, LICENSE2, LICENSE3, LICENSE4);
        $smarty -> assign('Alicense', $arrLicenses[$intLevel]);
    }
    else
    /**
     * Buy licenses
     */
    {
        $arrGold = array(1000, 2000, 10000, 50000);
        $arrMithril = array(0, 10, 50, 250);
        if ($player -> credits < $arrGold[$intLevel])
        {
            error(NO_MONEY);
        }
        if ($player -> platinum < $arrMithril[$intLevel])
        {
            error(NO_MITH);
        }
        $db -> Execute( !$intLevel ? 'INSERT INTO `lumberjack` (`owner`, `level`) VALUES('.$player -> id.', 0)' : 'UPDATE `lumberjack` SET `level`=`level`+1 WHERE `owner`='.$player -> id);
        $db -> Execute('UPDATE `players` SET `credits`=`credits`-'.$arrGold[$intLevel].', `platinum`=`platinum`-'.$arrMithril[$intLevel].' WHERE `id`='.$player -> id);
        $arrLicenses = array(LICENSE1, LICENSE2, LICENSE3, LICENSE4);
        $smarty -> assign('Message', YOU_BUY.$arrLicenses[$intLevel]);
    }
    $smarty -> assign('Step', $_GET['step']);
}

/**
* Buy plans of items
*/
if (isset ($_GET['mill']) && $_GET['mill'] == 'plany')
{
    if (!isset($_GET['buy']))
    {
        showplans ('mill', 0, $player -> lang);
    }
    else
    {
        buyplan ('mill', $_GET['buy'], $player -> id, $player -> credits);
    }
}

/**
* Make items
*/
if (isset ($_GET['mill']) && $_GET['mill'] == 'mill')
{
    if (!isset($_GET['rob']) && !isset($_GET['konty']))
    {
        showplans ('mill', $player -> id, $player -> lang);
        showunfinished('mill_work', $player -> id);
    }
        else
    {
        $arrEquip = $player -> equipment();
        $arrRings = array(R_AGI, R_STR, R_INT);
        $arrStat = array('agility', 'strength', 'inteli');
        if ($arrEquip[9][2])
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
        if ($arrEquip[10][2])
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
    }
    if (isset($_GET['ko']) || isset($_GET['dalej']))
    {
        if ($player -> hp == 0)
        {
            error (YOU_DEAD);
        }
        $intItemId = isset($_GET['ko']) ? $_GET['ko'] : $_GET['dalej'];
        if (!ereg("^[1-9][0-9]*$", $intItemId))
        {
            error (ERROR);
        }
        integercheck($intItemId);
    }
    if (isset($_GET['ko']))
    {
        $objMaked = $db -> Execute('SELECT `name` FROM `mill_work` WHERE `id`='.$_GET['ko']);
        $smarty -> assign(array('Id' => $_GET['ko'],
                                'Name' => $objMaked -> fields['name'],
                                'Assignen' => ASSIGN_EN,
                                'Menergy' => M_ENERGY,
                                'Amake' => A_MAKE));
        $objMaked -> Close();
    }
    if (isset($_GET['dalej']))
    {
        $objLumber = $db -> Execute("SELECT name, type FROM mill WHERE id=".$_GET['dalej']);
        $smarty -> assign(array('Id' => $_GET['dalej'],
                                'Name' => $objLumber -> fields['name'],
                                'Type' => $objLumber -> fields['type'],
                                'Lhazel' => L_HAZEL,
                                'Lyew' => L_YEW,
                                'Lelm' => L_ELM,
                                'Lharder' => L_HARDER,
                                'Lcomposite' => L_COMPOSITE,
                                'Assignen' => ASSIGN_EN,
                                'Menergy' => M_ENERGY,
                                'Selectarr' => SELECT_ARR,
                                'Amake' => A_MAKE));
        $objLumber -> Close();
    }
    /**
    * Continue making items
    */
    if (isset($_GET['konty']))
    {
        if (!ereg("^[1-9][0-9]*$", $_GET['konty']))
        {
            error (ERROR);
        }
        $objWork = $db -> Execute('SELECT * FROM `mill_work` WHERE `id`='.$_GET['konty']);
        $objLumber = $db -> Execute('SELECT `name`, `type`, `cost`, `amount`, `level`, `twohand` FROM `mill` WHERE owner='.$player -> id.' AND name=\''.$objWork -> fields['name'].'\'');
        if (!ereg("^[1-9][0-9]*$", $_POST['razy']))
        {
            error(ERROR);
        }
        if ($player -> energy < $_POST['razy'])
        {
            error(NO_ENERGY);
        }
        $need = ($objWork -> fields['n_energy'] - $objWork -> fields['u_energy']);
		// Cut too big energy usage.
        if ($_POST['razy'] > $need)
        {
            $_POST['razy'] = $need;
        }
        if ($objWork -> fields['owner'] != $player -> id)
        {
            error (NO_ITEM);
        }

        /**
         * Add bonuses to ability
         */
        require_once('includes/abilitybonus.php');
        $player -> fletcher = abilitybonus('fletcher');

        /**
         * Count item attributes
         */
        if ($objLumber -> fields['type'] == 'B')
        {
            $intPower = 0;
            $arrMineral = array('H', 'Y', 'E', 'A', 'C');
            $intKey = array_search($objWork -> fields['mineral'], $arrMineral);
            $arrMaxdur = array(40, 80, 160, 320, 640);
            $intMaxdur = $arrMaxdur[$intKey];
            $arrBowname = array(L_HAZEL, L_YEW, L_ELM, L_HARDER, L_COMPOSITE);
            $strName = $objLumber -> fields['name'].' '.$arrBowname[$intKey];
            $arrMaxbonus = array(6, 10, 14, 17, 20);
            $intModif = $arrMaxbonus[$intKey];
            $intSpeed = $objLumber -> fields['level'] * $intModif;
        }
        else
        {
            $intPower = $objLumber -> fields['level'];
            $intSpeed = 0;
            $intMaxdur = 100;
            $strName = $objLumber -> fields['name'];
            $intModif = 0;
        }
        $intCost = ($objLumber -> fields['type'] == 'B') ? $objLumber -> fields['amount'] * $intModif * 3 : $objLumber -> fields['level'] * 5;
        $arrItem = array('power' => $intPower,
                         'wt' => $intMaxdur,
                         'name' => $strName,
                         'type' => $objLumber -> fields['type'],
                         'level' => $objLumber -> fields['level'],
                         'szyb' => $intSpeed,
                         'zr' => 0,
                         'cost' => $intCost,
                         'twohand' => $objLumber -> fields['twohand']);
        $intItems = 0;
        $intGainexp = 0;
        $intAbility = 0;
        $intChance = (50 - $intModif) * $player -> fletcher / $objLumber -> fields['level'];
        if ($intChance > 95)
        {
            $intChance = 95;
        }
        if ($_POST['razy'] == $need)
        {
            createitem();
            if ($intItems)
            {
                if ($player -> clas == ARTISAN)
                {
                    $intGainexp *= 2;
                    $intAbility *= 2;
                }
                $intGainexp = ceil($intGainexp);
                $smarty -> assign ('Message', YOU_MAKE.$arrItem['name'].AND_GAIN2.$intGainexp.AND_EXP2.$intAbility.IN_MILL);
            }
                else
            {
                $intAbility = round($intAbility*100, 0)/100;
                $intGainexp = 0;
                $smarty -> assign ('Message', YOU_TRY.$arrItem['name'].BUT_FAIL.$intAbility.IN_MILL);
            }
            $db -> Execute('DELETE FROM `mill_work` WHERE `owner`='.$player -> id);
            checkexp($player -> exp, $intGainexp, $player -> level, $player -> race, $player -> user, $player -> id, 0, 0, $player -> id, 'fletcher', $intAbility);
        }
            else
        {
            $uenergia = $_POST['razy'] + $objWork -> fields['u_energy'];
            $procent = round(($uenergia / $objWork -> fields['n_energy']) * 100);
            $need = $objWork -> fields['n_energy'] - $uenergia;
            $smarty -> assign ('Message', YOU_WORK.$arrItem['name'].NEXT_EN.$_POST['razy'].NOW_IS.$procent.YOU_NEED2.$need.M_ENERGY);
            $db -> Execute('UPDATE `mill_work` SET `u_energy`=`u_energy`+'.$_POST['razy'].' WHERE owner='.$player -> id);
        }
        $db -> Execute('UPDATE `players` SET `energy`=`energy`-'.$_POST['razy'].' WHERE `id`='.$player -> id);
    }
    /**
    * Start making items
    */
    if (isset($_GET['rob']))
    {
        if (!ereg("^[1-9][0-9]*$", $_GET['rob']))
        {
            error(ERROR);
        }
        if (!isset($_POST['razy']))
        {
            error(HOW_MANY);
        }
        if (!ereg("^[1-9][0-9]*$", $_POST['razy']))
        {
            error(ERROR);
        }
        $objTest = $db -> Execute('SELECT `id` FROM `mill_work` WHERE `owner`='.$player -> id);
        if ($objTest -> fields['id'])
        {
            error(YOU_MAKE2);
        }
        $objTest -> Close();
        $objLumber = $db -> Execute('SELECT * FROM `mill` WHERE `id`='.$_GET['rob']);
        if ($objLumber -> fields['type'] == 'B')
        {
            $arrMineral = array('H', 'Y', 'E', 'A', 'C');
            if (!in_array($_POST['lumber'], $arrMineral))
            {
                error(ERROR);
            }
        }
		// Cut used energy to acceptable maximum.
		if ($_POST['razy'] > $player -> energy)
		{
			$_POST['razy'] = $player -> energy;
		}
		// Cut energy to multiplication of item's level, so exceeding energy won't be lost.
        if ($_POST['razy'] % $objLumber -> fields['level'] != 0)
        {
            $_POST['razy'] = $objLumber -> fields['level'] * (int)($_POST['razy'] / $objLumber -> fields['level']);
        }
        if ($player -> energy < $objLumber -> fields['level'])
        {
            error(NO_ENERGY);
        }
        if ($objLumber -> fields['owner'] != $player -> id)
        {
            error(NO_PLANS);
        }
        $intAmount = floor($_POST['razy'] / $objLumber -> fields['level']);
        $intNeedmineral = $intAmount * $objLumber -> fields['amount'];
        if ($objLumber -> fields['type'] == 'R')
        {
            $arrNeedmineral = array('pine');
        }
            else
        {
            $intKey = array_search($_POST['lumber'], $arrMineral);
            if ($intKey < 3)
            {
                $arrMinerals = array('hazel', 'yew', 'elm');
                $arrNeedmineral = array($arrMinerals[$intKey]);
            }
                elseif ($intKey == 3)
            {
                $arrNeedmineral = array('hazel', 'elm');
            }
                elseif ($intKey == 4)
            {
                $arrNeedmineral = array('pine', 'hazel', 'yew', 'elm');
            }
        }
        foreach ($arrNeedmineral as $strNeedmineral)
        {
            $objMineral = $db -> Execute('SELECT '.$strNeedmineral.' FROM `minerals` WHERE `owner`='.$player -> id);
            if ($objMineral -> fields[$strNeedmineral] < $intNeedmineral)
            {
                error(NO_MAT);
            }
            $objMineral -> Close();
        }
        /**
         * Add bonuses to ability
         */
        require_once('includes/abilitybonus.php');
        $player -> fletcher = abilitybonus('fletcher');

        /**
         * Count item attributes
         */
        if ($objLumber -> fields['type'] == 'B')
        {
            $intPower = 0;
            $intSpeed = $objLumber -> fields['level'];
            $arrMaxdur = array(40, 80, 160, 320, 640);
            $intMaxdur = $arrMaxdur[$intKey];
            $arrBowname = array(L_HAZEL, L_YEW, L_ELM, L_HARDER, L_COMPOSITE);
            $strName = $objLumber -> fields['name']." ".$arrBowname[$intKey];
            $arrMaxbonus = array(6, 10, 14, 17, 20);
            $intModif = $arrMaxbonus[$intKey];
            // Item cost: No of metal bars required to create one item * factor based on wood type.
            $intCost = $objLumber -> fields['amount'] * $intModif * 3;
        }
            else
        {
            $intPower = $objLumber -> fields['level'];
            $intSpeed = 0;
            $intMaxdur = 100;
            $strName = $objLumber -> fields['name'];
            $intCost = $objLumber -> fields['level'] * 5;
            $intModif = 0;
        }
        $arrItem = array('power' => $intPower,
                         'wt' => $intMaxdur,
                         'name' => $strName,
                         'type' => $objLumber -> fields['type'],
                         'level' => $objLumber -> fields['level'],
                         'szyb' => $intSpeed,
                         'zr' => 0,
                         'cost' => $intCost,
                         'twohand' => $objLumber -> fields['twohand']);
        $intItems = 0;
        $intGainexp = 0;
        $intAbility = 0;
        $intChance = (50 - $intModif) * $player -> fletcher / $objLumber -> fields['level'];
        if ($intChance > 95)
        {
            $intChance = 95;
        }
        if ($intAmount > 0)
        {
            for ($i = 1; $i <= $intAmount; $i++)
            {
                createitem();
            }
            if ($player -> clas == ARTISAN)
            {
                $intGainexp = $intGainexp * 2;
                $intAbility = $intAbility * 2;
            }

            $intAbility = round($intAbility*100, 0)/100;
            $intAbility = $intAbility ? $intAbility : 0.01;
            $intGainexp = ceil($intGainexp);
            $smarty -> assign ('Message', YOU_MAKE.$arrItem['name'].'</b> <b>'.$intItems.AND_GAIN2.$intGainexp.AND_EXP2.$intAbility.IN_MILL);
            checkexp($player -> exp, $intGainexp, $player -> level, $player -> race, $player -> user, $player -> id, 0, 0, $player -> id,'fletcher',$intAbility);
        }
            else
        {
            $procent = round(($_POST['razy'] / $arrItem['level']) * 100);
            $need = ($objLumber -> fields['level'] - $_POST['razy']);
            $smarty -> assign ('Message', YOU_WORK.$arrItem['name'].YOU_USE.$_POST['razy'].AND_MAKE.$procent.TO_END.$need.M_ENERGY);
            if ($objLumber -> fields['type'] == 'R')
            {
                $db -> Execute('INSERT INTO `mill_work` (`owner`, `name`, `u_energy`, `n_energy`) VALUES('.$player -> id.', \''.$objLumber -> fields['name'].'\', '.$_POST['razy'].', '.$arrItem['level'].')');
            }
                else
            {
                $db -> Execute('INSERT INTO `mill_work` (`owner`, `name`, `u_energy`, `n_energy`, `mineral`) VALUES('.$player -> id.', \''.$objLumber -> fields['name'].'\', '.$_POST['razy'].', '.$arrItem['level'].', \''.$_POST['lumber'].'\')');
            }
        }
        foreach ($arrNeedmineral as $strNeedmineral)
        {
            $objMineral = $db -> Execute('UPDATE `minerals` SET '.$strNeedmineral.'='.$strNeedmineral.'-'.$intNeedmineral.' WHERE `owner`='.$player -> id);
        }
        $db -> Execute('UPDATE `players` SET `energy`=`energy`-'.$_POST['razy'].' WHERE `id`='.$player -> id);
    }
}

/**
 * Make astral constructions
 */
if (isset($_GET['mill']) && $_GET['mill'] == 'astral')
{
    if( !isset($_GET['component'] ))
    {
        makeastral1();
    }
    else
    {
        makeastral1('fletcher', $_GET['component']);
    }
}

/**
* Initialization of variables
*/
if (!isset($_GET['mill']))
{
    $_GET['mill'] = '';
    $smarty -> assign(array('Millinfo' => MILL_INFO,
                            'Aplans' => A_PLANS,
                            'Amill' => A_MILL,
                            'Alicenses' => A_LICENSES,
                            'Aastral' => A_ASTRAL));
}

if (!isset($_GET['buy']))
{
    $_GET['buy'] = '';
}
if (!isset($_GET['rob']))
{
    $_GET['rob'] = '';
}
if (!isset($_GET['konty']))
{
    $_GET['konty'] = '';
}
if (!isset($_GET['ko']))
{
    $_GET['ko'] = '';
}
if (!isset($_GET['dalej']))
{
    $_GET['dalej'] = '';
}

/**
* Assign variables to template and display page
*/
$smarty -> assign(array('Mill' => $_GET['mill'],
                        'Buy' => $_GET['buy'],
                        'Make' => $_GET['rob'],
                        'Continue' => $_GET['konty'],
                        'Cont' => $_GET['ko'],
                        'Next' => $_GET['dalej'],
                        'Aback' => A_BACK));
$smarty -> display ('lumbermill.tpl');

require_once('includes/foot.php');
?>
