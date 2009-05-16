<?php
/**
 *   File functions:
 *   Check for gaining level by player when he gain experience
 *
 *   @name                  : checkexp.php
 *   @copyright           : (C) 2004,2005,2006,2007 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @author               : Zamareth <zamareth@gmail.com>
 *   @version              : 1.4
 *   @since                 : 31.08.2007
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
// $Id: checkexp.php 131 2006-04-12 18:36:19Z thindil $

/**
* Get the localization for game
*/
require_once('languages/'.$player -> lang.'/checkexp.php');

/**
* Function check for gaining level by player
*/
function checkexp ($currentExp,$gainedExp,$currentLevel,$race,$user,$eid,$enemyid,$enemyuser,$playerid,$skill,$amount) 
{
    global $player;
    global $db;
    global $smarty;
    global $newdate;
    $intAP = 0;
    $intHP = 0;
    $fltEnergy = 0;
    $intGainedLevel = 0;
    $intTempExp = ($currentExp + $gainedExp);
    $arrRaces = array('Człowiek', 'Elf', 'Krasnolud', 'Hobbit', 'Jaszczuroczłek', 'Gnom');
    $arrHP = array(5, 4, 6, 3, 5, 3);
    $intKey = array_search($race, $arrRaces);
    $fltEnergyAmount = $player -> clas == 'Rzemieślnik' ? 0.5 : 0.4;

    $intLevelExp = round(pow($currentLevel, 2) * log(exp(1) + (($currentLevel - 1) / 100)) * 50);
    while($intTempExp >= $intLevelExp)
    {
        $intAP += 10 + floor(.35 * $currentLevel);
        $intTempExp -= $intLevelExp;
        $intHP += $arrHP[$intKey];
        $fltEnergy += $fltEnergyAmount;
        $currentLevel++;
        $intGainedLevel++;
        $intLevelExp = round(pow($currentLevel, 2) * log(exp(1) + (($currentLevel - 1) / 100)) * 50);
    }
    unset($arrRaces, $arrHP);
    if($player -> clas =='Rzemieślnik')
    {
        $intAP = floor($intAP / 2);
    }
 
    if ($intGainedLevel > 0) 
    {
        if ($playerid == $eid) 
        {
            $strText = '<br /><b>'.YOU_GAIN.'</b> '.$user.'.<br />'. $intGainedLevel.' '.LEVELS.',<br />'.$intAP.' '.AP.',<br />'.$intHP.' '.HIT_POINTS.',<br />'.$fltEnergy.' '.GAIN_ENERGY.'.<br />';
            print $strText;
        }
        $strDBQuery = 'UPDATE `players` SET `exp`='.$intTempExp.', `level`=`level`+'.$intGainedLevel.', `ap`=`ap`+'.$intAP.', `max_hp`=`max_hp`+'.$intHP.', `max_energy`=`max_energy`+'.$fltEnergy;
        if ($enemyid != 0) 
        {
            $strDate = $db -> DBDate($newdate);
            $db -> Execute('INSERT INTO `log` (`owner`, `log`, `czas`) VALUES('.$eid.',\''.IN_FIGHT.' <b>'.$enemyuser.' ID: '.$enemyid.'</b>, '.YOU_GAIN2.'\', '.$strDate.')');
        }
    } 
        else 
    {
        $strDBQuery = 'UPDATE `players` SET `exp`='.$intTempExp;
    }
    if ($amount > 0) 
    {
        $strDBQuery .= ', `'.$skill.'`=`'.$skill.'`+'.$amount;
    }
    $strDBQuery .= ' WHERE `id`='.$eid;
    $db -> Execute($strDBQuery);
}
?>
