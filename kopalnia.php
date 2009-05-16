<?php
/**
 *   File functions:
 *   Mines in moutains
 *
 *   @name                 : kopalnia.php
 *   @copyright            : (C) 2004,2005,2006 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @author               : Zamareth <zamareth@users.sourceforge.net>
 *   @version              : 1.3
 *   @since                : 19.09.2007
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
// $Id: kopalnia.php 760 2006-10-24 12:09:02Z thindil $

$title = 'Kopalnia';
require_once('includes/head.php');
require_once('includes/checkexp.php');


/**
* Get the localization for game
*/
require_once('languages/'.$player -> lang.'/kopalnia.php');

if ($player -> location != 'Góry')
{
    error(ERROR);
}

/**
 * Dig for minerals
 */
if (isset($_GET['action']) && $_GET['action'] == 'dig')
{
    if (!isset($_POST['amount']) || !preg_match("/^[1-9][0-9]*$/", $_POST['amount']))
    {
        error(ERROR);
    }
    integercheck($_POST['amount']);
    if ($player -> hp < 1)
    {
        error(YOU_DEAD." (<a href=\"gory.php\">".BACK."</a>)");
    }
    if ($player -> energy < $_POST['amount'])
    {
        error(NO_ENERGY." (<a href=\"gory.php\">".BACK."</a>)");
    }

    /**
     * Count bonus to ability
     */
    require_once('includes/abilitybonus.php');
    $fltAbility = abilitybonus('mining');

    $fltGainAbility = 0;
    $arrMinerals = array(0, 0);
    $arrGold = array(0, 0);
    $strInfo = '';
    $intGainExp = 0;
    $intLostHPSum = 0;
    
    for ($i = 0; $i < $_POST['amount']; $i++)
    {
        $intRoll = rand(1, 10);
        if ($intRoll > 4 && $intRoll < 10)
        {
            $fltGainAbility += 0.1;
        }
        if ($intRoll == 5)
        {
            $intAmount = max(1, ceil((rand(1, 20) * 1 / 8) * (1 + ($fltAbility + $fltGainAbility) / 20)));
            $intGainExp += ceil($player -> level / 3);
            $arrMinerals[0] += $intAmount; // crystal
        }
        if ($intRoll == 6)
        {
            $intAmount = max(1, ceil((rand(1, 20) * 1 / 5) * (1 + ($fltAbility  + $fltGainAbility) / 20)));
            $intGainExp += ceil($player -> level / 3);
            $arrMinerals[1] += $intAmount; // adamantium
        }
        if ($intRoll == 7 || $intRoll == 8)
        {
            $intAmount = max(1, ceil((rand(1, 20) * 1 / 3) * (1 + ($fltAbility + $fltGainAbility) / 20)));
            $intGainExp += ceil($player -> level / 3);
            $arrGold[1] += $intAmount; // platinum
        }
        if ($intRoll == 9)
        {
            $intAmount = max(1, ceil(rand(50, 200) * (1 + ($fltAbility + $fltGainAbility) / 20)));
            $arrGold[0] += $intAmount; // gold
        }
        if ($intRoll == 10)
        {
            $intLostHP = rand(1, 100);
            if ($intLostHP < 51)
            {
                $intLostHPSum += $intLostHP;
            }
            if ($intLostHPSum > $player -> hp - 1)
            {
				$intLostHPSum = min($intLostHPSum, $player -> hp);
                $strInfo = '<br />'.DEAD_MAN.'. (<a href="gory.php">'.BACK.'</a>)';
                break;
            }
        }
        if ($intLostHPSum > 0 )
        {
        $strInfo = M_LOST_HP1.$intLostHPSum.M_LOST_HP2;
        }
    }
	$oldFetchMode = $db -> SetFetchMode(ADODB_FETCH_NUM);
	$arrBless = $db -> GetRow('SELECT `bless`, `blessval` FROM `players` WHERE `id`='.$player -> id);
	if (isset($arrBless) && $arrBless[0] == 'hp' && $intLostHPSum < $arrBless[1])
	{
		$intLostHPSum = $arrBless[1];
	}
    $intMinSum = array_sum($arrMinerals);
    $intGoldSum = array_sum($arrGold);
    if ($intMinSum)
    {
        $arrMinCheck = $db -> GetRow('SELECT `adamantium`, `crystal`, `owner` FROM `minerals` WHERE `owner`='.$player -> id);
        $db -> SetFetchMode($oldFetchMode);
		if (empty($arrMinCheck))
        {
            $db -> Execute('INSERT INTO `minerals` (`owner`, `crystal`, `adamantium`) VALUES('.$player -> id.', '.$arrMinerals[0].', '.$arrMinerals[1].')');
        }
        else
        {
            $db -> Execute('UPDATE `minerals` SET `crystal`=`crystal`+'.$arrMinerals[0].', `adamantium`=`adamantium`+'.$arrMinerals[1].' WHERE `owner`='.$player -> id);
        }
    }
    $strFind = YOU_GO.$i.T_AMOUNT2;
    if ($intGoldSum || $intMinSum)
    {
        $strFind = $strFind.YOU_FIND;
        if ($arrMinerals[0])
        {
            $strFind .= $arrMinerals[0].T_CRYSTALS;
        }
        if ($arrMinerals[1])
        {
            $strFind .= $arrMinerals[1].T_ADAMANTIUM;
        }
        if ($arrGold[1])
        {
            $strFind .= $arrGold[1].T_MITHRIL;
        }
        if ($arrGold[0])
        {
            $strFind .= T_GOLD.$arrGold[0].T_GOLD2;
        }
        $strFind .= $fltGainAbility.T_ABILITY.$intGainExp.T_GAIN_EXP;
    }
    if (!$fltGainAbility && $strInfo == '')
    {
        $strFind .= T_NOTHING;
    }
    $strFind .= $strInfo;
	$strBless = isset($arrBless) && $arrBless[0] == 'hp' ? ', `bless`=\'\', `blessval`=0' : '';
    $db -> Execute('UPDATE `players` SET `credits`=`credits`+'.$arrGold[0].', `platinum`=`platinum`+'.$arrGold[1].', `hp`=`hp`-'.$intLostHPSum.$strBless.', `energy`=`energy`-'.$i.' WHERE `id`='.$player -> id);
    $smarty -> assign('Youfind', $strFind);
    checkexp ($player -> exp, $intGainExp, $player -> level, $player -> race, $player -> user, $player -> id, 0, 0, $player -> id, 'mining', $fltGainAbility);

}

/**
* Initialization of variables
*/
if (!isset($_GET['action']))
{
    $_GET['action'] = '';
}

/**
* Assign variables to template and display page
*/
$smarty -> assign('Health', isset($intLostHPSum) ? $player -> hp - $intLostHPSum : $player -> hp);
$smarty -> display ('kopalnia.tpl');

require_once('includes/foot.php');
?>
