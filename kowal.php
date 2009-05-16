<?php
/**
 *   File functions:
 *   Blacksmith - making items - weapons, armors, shields, helmets, plate legs, arrowsheads
 *
 *   @name                 : kowal.php
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

$title='Kuźnia';
require_once('includes/head.php');
require_once('includes/checkexp.php');
require_once('includes/artisan.php');

/**
* Get the localization for game
*/
require_once('languages/'.$player -> lang.'/kowal.php');

if ($player -> location != 'Ardulith')
{
    error (ERROR);
}
if ($player -> hp <= 0)
{
    error (YOU_DEAD);
}

function special(&$intItemStat, $intItembonus, $fltPlayerStat, $intMax, $charAgility='')
{
    $intX = $intItembonus + $fltPlayerStat / 50;
    if($charAgility !='a')
        $intItemStat += $intX >  $intMax ? $intMax : $intX;
    else
        $intItemStat -= $intX >  $intMax ? $intMax : $intX;
}

/**
 * Function create items
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
    global $intExp;
    global $arrMaxbonus;
    global $intKey;
    global $intSmith;
	
    if (rand(1,100) <= $intChance)
    {
        $strName = $arrItem['name'];
        $intPower = $arrItem['power'];
        $intAgi = $arrItem['zr'];
        $intDur = $arrItem['wt'];
        $blnSpecial = false;
        if (floor( rand(1,100) - $intAbility / 100) < 21)   // special item! Smithy ('ability') skill increases the chance
        {
            $intRoll3 = rand(1, 100);
            $intItembonus = rand(ceil($intSmith/2), ceil($intSmith));
            if ($arrItem['type'] == 'A' || $arrItem['type'] == 'L')     // Dragon armor and plate legs -only special for non artisans.
            {
                if ($intRoll3 < 34)
                {
                    if ($arrItem['type'] == 'A')
                    {
                        $strName = DRAGON2.$arrItem['name'];
                        $intItembonus *= 2;
                    }
                        else
                    {
                        $strName = DRAGON3.$arrItem['name'];
                    }
                    special($intPower, $intItembonus, $player -> strength, $arrMaxbonus[$intKey] * $arrItem['power']);
                    if ($player -> clas == ARTISAN)
                    {
                    	$intPower += $player -> strength / 20;
                	}
                }
                if ($player -> clas == ARTISAN)
                {
                    if (($intRoll3 > 34 && $intRoll3 < 68)) //Dwarven armor
                    {
                        $strName = ($arrItem['type'] == 'A' ? DWARFS2 : DWARFS3).$arrItem['name'];
                        special($intDur, $intItembonus, $player -> inteli, $arrItem['wt'] * 10);
                        $intDur += $player -> inteli / 20;
                    }
                    if ($intRoll3 > 68)     // Elven armor/plate legs.
                    {
                        if ($arrItem['type'] == 'A')
                        {
                            $strName = ELFS2.$arrItem['name'];
                        }
                            else
                        {
                            $strName = ELFS3.$arrItem['name'];
                            $intItembonus = ceil($intItembonus / 2);
                        }
                        special($intAgi, $intItembonus, $player -> agility, $arrMaxbonus[$intKey] * $arrItem['zr'], 'a');
                        $intAgi -= $player -> agility / 20;
                    }
                    if ($intRoll3 == 34)    // 1% chance for special dragon (power+durability)
                    {
                        if ($arrItem['type'] == 'A')
                        {
                            $strName = DRAGON2.$arrItem['name'];
                            $intItembonus *= 2;
                        }
                            else
                        {
                            $strName = DRAGON3.$arrItem['name'];
                        }
                        special($intPower, $intItembonus, $player -> strength, $arrMaxbonus[$intKey] * $arrItem['power']);
                        special($intDur, $intItembonus, $player -> inteli, $arrItem['wt'] * 10);
                        $intDur += $player -> inteli / 20;
                        $intPower += $player -> strength / 20;
                    }
                    if ($intRoll3 == 68)    // 1% chance for elven + durability
                    {
                        if ($arrItem['type'] == 'A')
                        {
                            $strName = ELFS2.$arrItem['name'];
                            special($intAgi, $intItembonus, $player -> agility, $arrMaxbonus[$intKey] * $arrItem['zr'], 'a');
                        }
                        else
                        {
                            $strName = ELFS3.$arrItem['name'];
                            special($intAgi,ceil($intItembonus / 2), $player -> agility, $arrMaxbonus[$intKey] * $arrItem['zr'], 'a');
                        }
                        special($intDur, $intItembonus, $player -> inteli, $arrItem['wt'] * 10);
                        $intDur += $player -> inteli / 20;
                        $intAgi -= $player -> agility / 20;
                    }
                    $blnSpecial = true;
                }
            }
                else    // Weapons, helmets, shields
            {
                if ($intRoll3 < 51) // dragon
                {
                    $strName = ($arrItem['type'] == 'W' || $arrItem['type'] == 'H' ? DRAGON1 : DRAGON2).$arrItem['name'];
                    special($intPower, $intItembonus, $player -> strength, $arrMaxbonus[$intKey] * $arrItem['power']);
                    if ($player -> clas == ARTISAN)
                    {
                    	$intPower += $player -> strength / 20;
                	}
                    $blnSpecial = true;
                }
                if( $player -> clas == ARTISAN && $intRoll3 > 50)
                {
                    if ($intRoll3 == 51)    //dragon+durability
                    {
                        $strName = ($arrItem['type'] == 'W' || $arrItem['type'] == 'H' ? DRAGON1 : DRAGON2).$arrItem['name'];
                        special($intPower, $intItembonus, $player -> strength, $arrMaxbonus[$intKey] * $arrItem['power']);
                        special($intDur, $intItembonus, $player -> inteli, $arrItem['wt'] * 10);
                        $intDur += $player -> inteli / 20;
                        $intPower += $player -> strength / 20;
                    }
                    else     // dwarven
                    {
                        $strName = ($arrItem['type'] == 'W' || $arrItem['type'] == 'H' ? DWARFS1 : DWARFS2).$arrItem['name'];
                        special($intDur, $intItembonus, $player -> inteli, $arrItem['wt'] * 10);
                        $intDur += $player -> inteli / 20;
                    }
                    $blnSpecial = true;
                }
            }
        }
        $intGainexp += $intExp * $arrItem['level'] * ($blnSpecial ? 100 + $intAbility / 10 : 1);
        $intItems ++;
        $intAbility = round($intAbility + $arrItem['level'] / 100,3);
        if ($arrItem['type'] == 'A')
        {
            $intAbility = round($intAbility + $arrItem['level'] / 100,3);
        }
        $arrRepair = array(1, 4, 16, 64, 256);
        $intRepaircost = $arrItem['level'] * $arrRepair[$intKey];
        if ($arrItem['type'] == 'W' || $arrItem['type'] == 'A')
        {
            $intRepaircost *= 2;
        }

		$intPower = (int)$intPower;
		$intAgi = (int)$intAgi;
		$intDur = (int)$intDur;
		$intCost = (int)$intCost;
        $test = $db -> getRow('SELECT `id` FROM `equipment` WHERE `name`=\''.$strName.'\' AND `wt`='.$intDur.' AND `type`=\''.$arrItem['type'].'\' AND `status`=\'U\' AND `owner`='.$player -> id.' AND `power`='.$intPower.' AND `zr`='.$intAgi.' AND `szyb`='.$arrItem['szyb'].' AND `maxwt`='.$intDur.' AND `poison`=0 AND `cost`='.$intCost.' AND `minlev` = '.$arrItem['level']);
        if (empty($test))
        {
            $db -> Execute('INSERT INTO `equipment` (`owner`, `name`, `power`, `type`, `cost`, `zr`, `wt`, `minlev`, `maxwt`, `amount`, `magic`, `poison`, `szyb`, `twohand`, `repair`) VALUES('.$player -> id.', \''.$strName.'\', '.$intPower.', \''.$arrItem['type'].'\', '.$intCost.', '.$intAgi.', '.$intDur.', '.$arrItem['level'].', '.$intDur.', 1, \'N\', 0,'.$arrItem['szyb'].', \''.$arrItem['twohand'].'\', '.$intRepaircost.')');
        }
            else
        {
            $db -> Execute('UPDATE `equipment` SET `amount`=`amount`+1 WHERE `id`='.$test['id']);
        }
		unset($test);
    }
        else
    {
	    $intAbility += 0.005 * $arrItem['level'];
	    if ($arrItem['type'] == 'A')
	    {
		    $intAbility += 0.005 * $arrItem['level'];
	    }

    }
}

/**
* Buy plans of items
*/
if (isset ($_GET['kowal']) && $_GET['kowal'] == 'plany')
{
    $smarty -> assign(array('Hereis' => HERE_IS,
                            'Aplansw' => A_PLANS_W,
                            'Aplansa' => A_PLANS_A,
                            'Aplansh' => A_PLANS_H,
                            'Aplansl' => A_PLANS_L,
                            'Aplanss' => A_PLANS_S));
    /**
     * Show avaiable plans
     */
    if (isset($_GET['dalej']))
    {
        $arrType = array('W', 'A', 'H', 'L', 'S');
        if (!in_array($_GET['dalej'], $arrType))
        {
            error (ERROR);
        }
        showplans ('smith', 0, $player -> lang, $_GET['dalej']);
    }
    /**
     * Buy new plan
     */
    if (isset($_GET['buy']))
    {
        buyplan ('smith', $_GET['buy'], $player -> id, $player -> credits);
    }
}

/**
* Making items
*/
if (isset ($_GET['kowal']) && $_GET['kowal'] == 'kuznia')
{
    if (!isset($_GET['rob']) && !isset($_GET['konty']))
    {
        $smarty -> assign(array('Hereis' => SMITH_INFO,
                                'Amakew' => A_MAKE_W,
                                'Amakea' => A_MAKE_A,
                                'Amakeh' => A_MAKE_H,
                                'Amakel' => A_MAKE_L,
                                'Amakes' => A_MAKE_S,
                                'Amaker' => A_MAKE_R));
        if (isset($_GET['type']))
        {
            $arrType = array('W', 'A', 'H', 'L', 'S');
            if (!in_array($_GET['type'], $arrType))
            {
                error (ERROR);
            }
            showplans ('smith', $player -> id, $player -> lang, $_GET['type']);
        }
        showunfinished('smith_work', $player -> id);
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
    if (isset($_GET['ko']))
    {
        if ($player -> hp == 0)
        {
            error (YOU_DEAD);
        }
        if (!ereg("^[1-9][0-9]*$", $_GET['ko']))
        {
            error (ERROR);
        }
        $objMaked = $db -> Execute('SELECT `name` FROM `smith_work` WHERE `id`='.$_GET['ko']);
        $smarty -> assign(array('Link' => 'kowal.php?kowal=kuznia&konty='.$_GET['ko'],
                                'Name' => $objMaked -> fields['name'],
                                'Assignen' => ASSIGN_EN,
                                'Senergy' => S_ENERGY,
                                'Amake' => A_MAKE));
        $objMaked -> Close();
    }
    if (isset($_GET['dalej']))
    {
        if ($player -> hp == 0)
        {
            error (YOU_DEAD);
        }
        if (!ereg("^[1-9][0-9]*$", $_GET['dalej']))
        {
            error (ERROR);
        }
        $objSmith = $db -> Execute('SELECT `name` FROM `smith` WHERE `id`='.$_GET['dalej']);
        $smarty -> assign(array('Link' => 'kowal.php?kowal=kuznia&rob='.$_GET['dalej'],
                                'Name' => $objSmith -> fields['name'],
                                'Assignen' => ASSIGN_EN,
                                'Senergy' => S_ENERGY,
                                'Amake' => A_MAKE,
                                'Mcopper' => M_COPPER,
                                'Mbronze' => M_BRONZE,
                                'Mbrass' => M_BRASS,
                                'Miron' => M_IRON,
                                'Msteel' => M_STEEL));
        $objSmith -> Close();
    }
    /**
     * Continue making items
     */
    if (isset($_GET['konty']))
    {
        if (!ereg("^[1-9][0-9]*$", $_GET['konty']) || !ereg("^[1-9][0-9]*$", $_POST['razy']))
        {
            error (ERROR);
        }
        $objWork = $db -> Execute('SELECT * FROM `smith_work` WHERE `id`='.$_GET['konty']);
        $objSmith = $db -> Execute('SELECT `name`, `type`, `cost`, `amount`, `level`, `twohand` FROM `smith` WHERE owner='.$player -> id.' AND name=\''.$objWork -> fields['name'].'\'');
        if ($player -> energy < $_POST['razy'])
        {
            error (NO_ENERGY);
        }
        $intNeed = ($objWork -> fields['n_energy'] - $objWork -> fields['u_energy']);
        if ($_POST['razy'] > $intNeed)
        {
            error (TOO_MUCH);
        }
        if ($objWork -> fields['owner'] != $player -> id)
        {
            error (NO_ITEM);
        }

        /**
         * Add bonuses to ability
         */
        require_once('includes/abilitybonus.php');
        $intSmith = abilitybonus('ability');

        $intItems = 0;
        $intGainexp = 0;
        $intAbility = 0;
        $arrMineral = array('copper', 'bronze', 'brass', 'iron', 'steel');
        $intKey = array_search($objWork -> fields['mineral'], $arrMineral);
        $arrMaxbonus = array(6, 10, 14, 17, 20);
        // Item cost: No of metal bars required to create one item * factor based on metal type.
        $intCost = $objSmith -> fields['amount'] * $arrMaxbonus[$intKey] * 3;
        $arrName = array(M_COPPER, M_BRONZE, M_BRASS, M_IRON, M_STEEL);
        if ($objSmith -> fields['type'] == 'W' || $objSmith -> fields['type'] == 'A')
        {
            $arrDur = array(40, 80, 160, 320, 640);
        }
            else
        {
            $arrDur = array(20, 40, 80, 160, 320);
        }
        $intPower = $objSmith -> fields['level'];
        if ($objSmith -> fields['type'] == 'A')
        {
            $intPower *= 3;
            $intAgility = floor($objSmith -> fields['level'] / 2);
            $intExp = 2;
        }
            elseif ($objSmith -> fields['type'] == 'L')
        {
            $intAgility = floor($objSmith -> fields['level'] / 5);
            $intExp = 1;
        }
            else
        {
            $intAgility = 0;
            $intExp = 1;
        }
        $strName = $objSmith -> fields['name']." ".$arrName[$intKey];
        $arrItem = array('power' => $intPower,
                         'wt' => $arrDur[$intKey],
                         'name' => $strName,
                         'type' => $objSmith -> fields['type'],
                         'level' => $objSmith -> fields['level'],
                         'szyb' => 0,
                         'zr' => $intAgility,
                         'cost' => $intCost,
                         'twohand' => $objSmith -> fields['twohand']);
        if ($player -> clas == ARTISAN)
        {
            $intExp *= 2;
        }
        $intChance = (50 - $arrMaxbonus[$intKey]) * $intSmith / $objSmith -> fields['level'];
        if ($intChance > 95)
        {
            $intChance = 95;
        }
        if ($_POST['razy'] == $intNeed)
        {
            createitem();
            if ($player -> clas == ARTISAN)
            {
                $intAbility *= 2;
            }
            $intGainexp = ceil($intGainexp);
            $intAbility = round($intAbility*100, 0)/100;
            if ($intItems)
            {
                $smarty -> assign ('Message', YOU_MAKE.$strName.AND_GAIN2.$intGainexp.AND_EXP2.$intAbility.IN_SMITH);
            }
                else
            {
                $intGainexp = 0;
                $smarty -> assign ('Message', YOU_TRY.$strName.BUT_FAIL.$intAbility.IN_SMITH);
            }
            $db -> Execute('DELETE FROM `smith_work` WHERE `owner`='.$player -> id);
            checkexp($player -> exp, $intGainexp, $player -> level, $player -> race, $player -> user, $player -> id, 0, 0, $player -> id, 'ability', $intAbility);
        }
            else
        {
            $uenergia = ($_POST['razy'] + $objWork -> fields['u_energy']);
            $intEnergy = $objSmith -> fields['level'];
            if ($objSmith -> fields['type'] == 'A')
            {
                $intEnergy *= 2;
            }
            $procent = round(($uenergia / $intEnergy) * 100);
            $need = $objWork -> fields['n_energy'] - $uenergia;
            $db -> Execute('UPDATE `smith_work` SET `u_energy`=`u_energy`+'.$_POST['razy'].' WHERE `owner`='.$player -> id);
            $smarty -> assign ('Message', YOU_WORK.$strName.NEXT_EN.$_POST['razy'].NOW_IS.$procent.YOU_NEED2.$need.S_ENERGY);
        }
        $db -> Execute('UPDATE `players` SET `energy`=`energy`-'.$_POST['razy'].' WHERE `id`='.$player -> id);
    }
    /**
     * Start making items
     */
    if (isset($_GET['rob']))
    {
        if (!ereg("^[1-9][0-9]*$", $_GET['rob']) || !isset($_POST['mineral']))
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
        $arrMineral = array('copper', 'bronze', 'brass', 'iron', 'steel');
        if (!in_array($_POST['mineral'], $arrMineral))
        {
            error(ERROR);
        }
        $objTest = $db -> Execute('SELECT `id` FROM `smith_work` WHERE `owner`='.$player -> id);
        if ($objTest -> fields['id'])
        {
            error(YOU_MAKE2);
        }
        $objTest -> Close();
        $objSmith = $db -> Execute('SELECT `owner`, `name`, `type`, `cost`, `amount`, `level`, `twohand` FROM `smith` WHERE id='.$_GET['rob']);
        $objMineral = $db -> Execute('SELECT `'.$_POST['mineral'].'` FROM `minerals` WHERE `owner`='.$player -> id);
        $strMineral = $_POST['mineral'];
        $intAmount = floor($_POST['razy'] / $objSmith -> fields['level']);
        $intEnergy = $objSmith -> fields['level'];
        $intEnergy2 = $intAmount * $objSmith -> fields['level'];
        if ($objSmith -> fields['type'] == 'A')
        {
            $intAmount = floor($intAmount/2);
            $intEnergy *= 2;
        }
        $intAmineral = $objSmith -> fields['amount'] * ($intAmount ? $intAmount : 1);
        if ($intAmineral > $objMineral -> fields[$strMineral])
        {
            error (NO_MAT);
        }
        if ($player -> energy < $_POST['razy'])
        {
            error (NO_ENERGY);
        }
        if ($objSmith -> fields['owner'] != $player -> id)
        {
            error (NO_PLANS);
        }

        /**
         * Add bonuses to ability
         */
        require_once('includes/abilitybonus.php');
        $intSmith = abilitybonus('smith');

        $intItems = 0;
        $intGainexp = 0;
        $intAbility = 0;
        $intKey = array_search($_POST['mineral'], $arrMineral);
        $arrMaxbonus = array(6, 10, 14, 17, 20);
        // Item cost: No of metal bars required to create one item * factor based on metal type.
        $intCost = $objSmith -> fields['amount'] * $arrMaxbonus[$intKey] * 3;
        $arrName = array(M_COPPER, M_BRONZE, M_BRASS, M_IRON, M_STEEL);
        if ($objSmith -> fields['type'] == 'W' || $objSmith -> fields['type'] == 'A')
        {
            $arrDur = array(40, 80, 160, 320, 640);
        }
            else
        {
            $arrDur = array(20, 40, 80, 160, 320);
        }
        $intPower = $objSmith -> fields['level'];
        if ($objSmith -> fields['type'] == 'A')
        {
            $intPower *= 3;
            $intAgility = floor($objSmith -> fields['level'] / 2);
            $intExp = 2;
        }
            elseif ($objSmith -> fields['type'] == 'L')
        {
            $intAgility = floor($objSmith -> fields['level'] / 5);
            $intExp = 1;
        }
            else
        {
            $intAgility = 0;
            $intExp = 1;
        }
        $strName = $objSmith -> fields['name'].' '.$arrName[$intKey];
        $arrItem = array('power' => $intPower,
                         'wt' => $arrDur[$intKey],
                         'name' => $strName,
                         'type' => $objSmith -> fields['type'],
                         'level' => $objSmith -> fields['level'],
                         'szyb' => 0,
                         'zr' => $intAgility,
                         'cost' => $intCost,
                         'twohand' => $objSmith -> fields['twohand']);
        if ($player -> clas == ARTISAN)
        {
            $intExp *= 2;
        }
        $intChance = (50 - $arrMaxbonus[$intKey]) * $intSmith / $objSmith -> fields['level'];
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
            $intGainexp = ceil($intGainexp);
            if ($player -> clas == ARTISAN)
            {
                $intAbility *= 2;
            }
            $intAbility = round($intAbility*100, 0)/100;
            $intAbility = $intAbility ? $intAbility : 0.01;  
            $smarty -> assign ('Message', YOU_MAKE.$objSmith -> fields['name'].'</b> <b>'.$intItems.AND_GAIN2.$intGainexp.AND_EXP2.$intAbility.IN_SMITH);
            checkexp($player -> exp, $intGainexp, $player -> level, $player -> race, $player -> user, $player -> id, 0, 0, $player -> id, 'ability', $intAbility);
        }
            else
        {
            $procent = round(($_POST['razy'] / $intEnergy) * 100);
            $need = ($intEnergy - $_POST['razy']);
            $intEnergy2 = $_POST['razy'];
            $db -> Execute('INSERT INTO `smith_work` (`owner`, `name`, `u_energy`, `n_energy`, `mineral`) VALUES('.$player -> id.', \''.$objSmith -> fields['name'].'\', '.$_POST['razy'].', '.$intEnergy.', \''.$_POST['mineral'].'\')');
            $smarty -> assign ('Message', YOU_WORK.$objSmith -> fields['name'].YOU_USE.$_POST['razy'].AND_MAKE.$procent.TO_END.$need.S_ENERGY);
        }
        $db -> Execute('UPDATE `minerals` SET `'.$_POST['mineral'].'`=`'.$_POST['mineral'].'`-'.$intAmineral.' WHERE `owner`='.$player -> id);
        $db -> Execute('UPDATE `players` SET `energy`=`energy`-'.$intEnergy2.' WHERE `id`='.$player -> id);
    }
}

/**
 * Make astral constructions
 */
if (isset($_GET['kowal']) && $_GET['kowal'] == 'astral')
{
    if( !isset($_GET['component'] ))
    {
        makeastral1();
    }
    else
    {
        makeastral1('ability', $_GET['component']);
    }
}

/**
* Initialization of variables
*/
if (!isset($_GET['kowal']))
{
    $_GET['kowal'] = '';
    $smarty -> assign(array('Smithinfo' => SMITH_INFO,
                            'Aplans' => A_PLANS,
                            'Asmith' => A_SMITH,
                            'Aastral' => A_ASTRAL));
}
    else
{
    $smarty -> assign('Aback', A_BACK);
}
if (!isset($_GET['dalej']))
{
    $_GET['dalej'] = '';
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
if (!isset($_GET['type']))
{
    $_GET['type'] = '';
}
if (!isset($_GET['ko']))
{
    $_GET['ko'] = '';
}

/**
* Assign variables to template and display page
*/
$smarty -> assign(array('Smith' => $_GET['kowal'],
                        'Next' => $_GET['dalej'],
                        'Buy' => $_GET['buy'],
                        'Make' => $_GET['rob'],
                        'Continue' => $_GET['konty'],
                        'Type' => $_GET['type'],
                        'Cont' => $_GET['ko']));
$smarty -> display ('kowal.tpl');

require_once('includes/foot.php');
?>
