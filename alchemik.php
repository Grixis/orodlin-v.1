<?php
/**
 *   File functions:
 *   Alchemy mill - making potions
 *
 *   @name                 : alchemik.php
 *   @copyright            : (C) 2004,2005,2006,2007 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @version              : 1.4
 *   @since                : 08.03.2007
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
// $Id: alchemik.php 949 2007-03-08 12:01:50Z thindil $

$title = 'Pracownia alchemiczna';
require_once('includes/head.php');
require_once('includes/checkexp.php');
require_once('includes/artisan.php');

/**
* Get the localization for game
*/
require_once('languages/'.$player -> lang.'/alchemik.php');

if ($player -> location != 'Altara' && $player -> location != 'Ardulith')
{
    error (ERROR);
}

/**
* Get amount of herbs from database
*/
$herb = $db -> Execute('SELECT `illani`, `illanias`, `nutari`, `dynallca` FROM `herbs` WHERE `gracz`='.$player -> id);

/**
* Assign variables to template
*/
if (!isset($_GET['alchemik']))
{
    $smarty -> assign(array('Awelcome' => WELCOME,
                            'Arecipes' => A_RECIPES,
                            'Amake' => A_MAKE,
                            'Aastral' => A_ASTRAL));
}

/**
* Buy receptures
*/
if (isset ($_GET['alchemik']) && $_GET['alchemik'] == 'przepisy')
{
    $smarty -> assign ('Recipesinfo', RECIPES_INFO);
    if (!isset($_GET['buy']))
    {
        showplans ('alchemy_mill', 0, $player -> lang);
    }
    else
    {
        buyplan ('alchemy_mill', $_GET['buy'], $player -> id, $player -> credits);
    }
}

/**
* Making potions
*/
if (isset ($_GET['alchemik']) && $_GET['alchemik'] == 'pracownia')
{
    if (!isset($_GET['rob']))
    {
        $arrname = array();
        $arrlevel = array();
        $arrid = array();
        $arrillani = array();
        $arrillanias = array();
        $arrnutari = array();
        $arrdynallca = array();
        $i = 0;
        $kuznia = $db -> Execute('SELECT * FROM `alchemy_mill` WHERE `status`=\'N\' AND `owner`='.$player -> id.' ORDER BY `level` ASC');
        while (!$kuznia -> EOF)
        {
            $arrname[$i] = $kuznia -> fields['name'];
            $arrlevel[$i] = $kuznia -> fields['level'];
            $arrid[$i] = $kuznia -> fields['id'];
            $arrillani[$i] = $kuznia -> fields['illani'];
            $arrillanias[$i] = $kuznia -> fields['illanias'];
            $arrnutari[$i] = $kuznia -> fields['nutari'];
            $arrdynallca[$i] = $kuznia -> fields['dynallca'];
            $i = $i + 1;
            $kuznia -> MoveNext();
        }
        $kuznia -> Close();
        $smarty -> assign (array('Name' => $arrname,
                                 'Level' => $arrlevel,
                                 'Id' => $arrid,
                                 'Illani' => $arrillani,
                                 'Illanias' => $arrillanias,
                                 'Nutari' => $arrnutari,
                                 'Dynallca' => $arrdynallca,
                                 'Alchemistinfo' => ALCHEMIST_INFO,
                                 'Rname' => R_NAME,
                                 'Rlevel' => R_LEVEL,
                                 'Rillani' => R_ILLANI,
                                 'Rillanias' => R_ILLANIAS,
                                 'Rnutari' => R_NUTARI,
                                 'Rdynallca' => R_DYNALLCA));
    }
    if (isset($_GET['dalej']))
    {
        if ($player -> hp == 0)
        {
            error (DEAD_PLAYER);
        }
        if (!ereg("^[1-9][0-9]*$", $_GET['dalej']))
        {
            error (ERROR);
        }
        $kuznia = $db -> Execute('SELECT `name` FROM `alchemy_mill` WHERE `id`='.$_GET['dalej']);
        $smarty -> assign (array ('Name1' => $kuznia -> fields['name'],
                                  'Id1' => $_GET['dalej'],
                                  'Pstart' => P_START,
                                  'Pamount' => P_AMOUNT,
                                  'Amake' => A_MAKE));
        $kuznia -> Close();
    }
    if (isset($_GET['rob']))
    {
        if (!isset($_POST['razy']) || !ereg("^[1-9][0-9]*$", $_GET['rob']) || !ereg("^[1-9][0-9]*$", $_POST['razy']))
        {
            error (ERROR);
        }
        $kuznia = $db -> Execute('SELECT * FROM `alchemy_mill` WHERE `id`='.$_GET['rob']);
        $rillani = ($_POST['razy'] * $kuznia -> fields['illani']);
        $rillanias = ($_POST['razy'] * $kuznia -> fields['illanias']);
        $rnutari = ($_POST['razy'] * $kuznia -> fields['nutari']);
        $rdynallca = ($_POST['razy'] * $kuznia -> fields['dynallca']);
        if ($herb -> fields['illani'] < $rillani || $herb -> fields['illanias'] < $rillanias || $herb -> fields['nutari'] < $rnutari || $herb -> fields['dynallca'] < $rdynallca)
        {
            error (NO_HERBS);
        }
        $fltEnergy = $_POST['razy'];
        if ($kuznia -> fields['level'] > 1)
        {
            $fltEnergy = $fltEnergy + (($kuznia -> fields['level'] * 0.2) * $_POST['razy']);
        }
        if ($player -> energy < $fltEnergy)
        {
            error (NO_ENERGY);
        }
        if ($kuznia -> fields['owner'] != $player -> id)
        {
            error (NO_RECIPE);
        }

        /**
         * Add bonuses to ability
         */
        require_once('includes/abilitybonus.php');
        $player -> alchemy = abilitybonus('alchemy');

        $rprzedmiot = 0;
        $rpd = 0;
        $rum = 0;
        $objItem = $db -> Execute('SELECT `efect`, `type`, `power` FROM `potions` WHERE `name`=\''.$kuznia -> fields['name'].'\' AND `owner`=0');
        $objItem2 = $db -> Execute ('SELECT `level` FROM `alchemy_mill` WHERE `name`=\''.$kuznia -> fields['name'].'\' AND `owner`=0');

        /**
         * Start making potions
         */
        for ($i = 1; $i <= $_POST['razy']; $i++)
        {
            if ($objItem -> fields['type'] == 'M')
            {
                $fltStat = $player -> wisdom/10;
            }
            if ($objItem -> fields['type'] == 'H')
            {
                $fltStat = $player -> inteli/10;
            }
            if ($objItem -> fields['type'] == 'P')
            {
                $fltStat = (min($player -> wisdom, $player -> inteli) + $player -> agility) / 10;
            }
            if ($objItem -> fields['type'] == 'A')
            {
                $fltStat = (min($player -> wisdom, $player -> inteli) + $player -> speed) / 10;
            }
            $intChance = (($player -> level - $objItem2 -> fields['level'])* 5) + ($player -> alchemy / 3) + $fltStat;
            if ( $intChance < 0)
            {
                $intChance = 0;
            }
            $intRoll = rand(1, 100);
            $intTmpamount = 0;
            while ($intRoll < $intChance)
            {
                $rprzedmiot ++;
                $intTmpamount ++;
                $intChance = $intChance - 50;
            }
            if ($intTmpamount)
            {
                $intRoll2 = rand(1,100);
                $strName = $kuznia -> fields['name'];
                $intPower = $objItem -> fields['power'];
                $intMaxpower = $intPower;
                if ($player -> clas == 'Rzemieślnik' && $intRoll2 > 89 && $objItem -> fields['type'] != 'A')
                {
                    if ($objItem -> fields['type'] != 'P')
                    {
                        $intMaxpower = $objItem -> fields['power'] * 2;
                        $intPower = ceil($objItem -> fields['power'] + $player -> alchemy);
                    }
                        else
                    {
                        $intMaxpower = $kuznia -> fields['level'] * 4;
                        $intPower = ceil($player -> alchemy / 2);
                    }
                    $strName = $kuznia -> fields['name']." (S)";
                    $rpd = ($rpd + ($kuznia -> fields['level'] * 10));
                    if ($intTmpamount > 1)
                    {
                        $rpd = ($rpd + ((($kuznia -> fields['level'] * 10) / 100) * (10 * ($intTmpamount - 1))));
                    }
                }
                    else
                {
                    $rpd = ($rpd + $kuznia -> fields['level']);
                    if ($intTmpamount > 1)
                    {
                        $rpd = ($rpd + (($kuznia -> fields['level'] / 100) * (10 * ($intTmpamount - 1))));
                    }
                    if ($objItem -> fields['type'] == 'P')
                    {
                        $intMaxpower = $kuznia -> fields['level'] * 2;
                        $intPower = ceil($player -> alchemy / 2);
                    }
                }
            }
                else
            {
                $rpd ++;
                if ($objItem -> fields['type'] != 'P')
                {
                    $intMaxpower = $objItem -> fields['power'];
                    $intPower = ceil($player -> alchemy);
                }
                    else
                {
                    $intMaxpower = $kuznia -> fields['level'];
                    $intPower = ceil($player -> alchemy / 2);
                }
                $strName = $kuznia -> fields['name']." (K)";
                $intTmpamount = 1;
                $rprzedmiot ++;
            }
            if ($intPower > $intMaxpower)
            {
                $intPower = $intMaxpower;
            }
            $test = $db -> Execute('SELECT `id` FROM `potions` WHERE `name`=\''.$strName.'\' AND `owner`='.$player -> id.' AND `status`=\'K\' AND `power`='.$intPower) or die('błąd');
            if (!$test -> fields['id'])
            {
                $db -> Execute('INSERT INTO potions (`owner`, `name`, `efect`, `power`, `amount`, `status`, `type`) VALUES('.$player -> id.', \''.$strName.'\', \''.$objItem -> fields['efect'].'\', '.$intPower.', '.$intTmpamount.', \'K\', \''.$objItem -> fields['type'].'\')');
            }
                else
            {
                $db -> Execute('UPDATE `potions` SET `amount`=`amount`+'.$intTmpamount.' WHERE `id`='.$test -> fields['id']);
            }
            $test -> Close();
            $intTmpamount = 0;
        }
        $rum = ($fltEnergy * 0.01);
        if ($player -> clas == 'Rzemieślnik')
        {
            $rpd = $rpd * 2;
            $rum = $rum * 2;
        }
        $smarty -> assign(array ('Name' => $kuznia -> fields['name'],
                                 'Amount' => $rprzedmiot,
                                 'Exp' => $rpd,
                                 'Ability' => $rum,
                                 'Youmake' => YOU_MAKE,
                                 'Pgain' => P_GAIN,
                                 'Exp_and' => EXP_AND,
                                 'Alchemylevel' => ALCHEMY_LEVEL));
        $kuznia -> Close();
        checkexp($player -> exp, $rpd, $player -> level, $player -> race, $player -> user, $player -> id, 0, 0, $player -> id, 'alchemia', $rum);
        $db -> Execute('UPDATE `herbs` SET `illani`=`illani`-'.$rillani.', `illanias`=`illanias`-'.$rillanias.', `nutari`=`nutari`-'.$rnutari.', `dynallca`=`dynallca`-'.$rdynallca.' WHERE `gracz`='.$player -> id);
        $db -> Execute('UPDATE `players` SET `energy`=`energy`-'.$fltEnergy.' WHERE `id`='.$player -> id);
    }
}

/**
 * Make astral potions
 */
if (isset($_GET['alchemik']) && $_GET['alchemik'] == 'astral')
{
    if( !isset($_GET['potion'] ))
    {
        makeastral2();
    }
    else
    {
        makeastral2($_GET['potion']);
    }
}

$herb -> Close();

/**
* Initialization of variables
*/
if (!isset($_GET['alchemik']))
{
    $_GET['alchemik'] = '';
}
if (!isset($_GET['buy']))
{
    $_GET['buy'] = '';
}
if (!isset($_GET['rob']))
{
    $_GET['rob'] = '';
}
if (!isset($_GET['dalej']))
{
    $_GET['dalej'] = '';
}

/**
* Assing variables and display page
*/
$smarty -> assign (array ('Alchemist' => $_GET['alchemik'],
    'Buy' => $_GET['buy'],
    'Make' => $_GET['rob'],
    'Back' => BACK,
    'Next' => $_GET['dalej']));
$smarty -> display ('alchemist.tpl');

require_once('includes/foot.php');
?>
