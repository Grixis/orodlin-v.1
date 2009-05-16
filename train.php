<?php
/**
 *   Funkcje pliku:
 *   School - train stats
 *
 *   @name                 : train.php                            
 *   @copyright            : (C) 2004,2005,2006,2007 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @version              : 1.7
 *   @since                : 04.03.5007
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
// $Id: train.php 931 2007-03-04 12:08:56Z thindil $

$title = 'Szkolenie';
require_once('includes/head.php');

/**
* Get the localization for game
*/
require_once('languages/'.$player -> lang.'/train.php');

if ($player -> location != 'Altara' && $player -> location != 'Ardulith') 
{
    error (ERROR);
}
if ($player -> hp == 0) 
{
	error (YOU_DEAD);
}
if (!$player -> race)
{
	error(NO_RACE);
}
if (!$player -> clas) 
{
	error(NO_CLASS);
}

$arrRaces = array('Człowiek', 'Elf', 'Krasnolud', 'Hobbit', 'Jaszczuroczłek', 'Gnom');
$arrRaceCost = array(array(.7, .7, .7, .7),
					array(.9, .5, .5, .9),
					array(.5, .9, .9, .5),
					array(.9, .5, .9, .5),
					array(.5, .9, .5, .9),
					array(.9, .7, .9, .7));
$arrClasses = array('Wojownik', 'Mag', 'Barbarzyńca', 'Złodziej', 'Rzemieślnik');
$arrClassCost = array(array(.9, .9), array(.5, .5), array(.7, .7), array(.7, .7), array(.7, .7));

$smarty -> assign_by_ref('StatsDesc', $arrStatsDesc);

$intRaceKey = array_search($player -> race, $arrRaces);
$intClassKey = array_search($player -> clas, $arrClasses);

$arrPlayerCost = array_merge($arrRaceCost[$intRaceKey], $arrClassCost[$intClassKey]);

if ($player -> race == 'Gnom')
{
	$arrPlayerCost[5] = .9;
}

unset($arrRaceCost, $arrRaces, $arrClasses, $arrClassCost, $intRaceKey, $intClassKey);
$smarty -> assign_by_ref('PlayerCost', $arrPlayerCost);
$arrStats = array('strength', 'agility', 'szyb', 'wytrz', 'inteli', 'wisdom');
$smarty -> assign_by_ref('StatOptions', $arrStats);
$smarty -> assign_by_ref('TrainedStats', $arrTrainedStats);

if (isset ($_GET['action']) && $_GET['action'] == 'train') 
{
    if (!isset($_POST['rep']))
    {
        error(HOW_MANY);
    }
    if (!ereg("^[1-9][0-9]*$", $_POST['rep'])) 
    {
        error (ERROR);
    }
    if (!in_array($_POST['train'], $arrStats)) 
    {
        error (ERROR);
    }
	$intPlayerKey = array_search($_POST['train'], $arrStats);
	$repeat = $_POST['rep'] * $arrPlayerCost[$intPlayerKey];
    $gain = $_POST['rep'] * .060;
    $repeat = round($repeat, 1);
    unset($arrPlayerCost);
	if ($repeat > $player -> energy) 
    {
        error (NO_ENERGY);
    }
	$smarty -> assign_by_ref('Train', $_POST['train']);
	$smarty -> assign_by_ref('Rep', $_POST['rep']);
	$smarty -> assign_by_ref('energyCost', $repeat);
	$smarty -> assign_by_ref('gainedStat', $gain);
	$smarty -> assign_by_ref('gainedStatName', $arrStatsDesc[$intPlayerKey]);

    if (isset($_GET['step']) && $_GET['step'] == 'next')
    {
		if ($_POST['train'] == 'wytrz') 
        {
            $intCondition = floor($player -> cond + $gain);
            if ($intCondition > $player -> cond)
            {
				$intGainedHP = $intCondition - floor($player -> cond);
                $db -> Execute('UPDATE `players` SET `max_hp`=`max_hp`+'.$intGainedHP.' WHERE `id`='.$player -> id);
            } 
        }
        $db -> Execute('UPDATE `players` SET `energy`=`energy`-'.$repeat.', '.$_POST['train'].'='.$_POST['train'].'+'.$gain.' WHERE `id`='.$player -> id);
        $arrTrained = $db -> GetRow('SELECT `id` FROM `trained_stats` WHERE `id`='.$player -> id);
        if (!isset($arrTrained['id']))
        {
            $db -> Execute('INSERT INTO `trained_stats` (`'.$_POST['train'].'`, `id`) VALUES ('.$gain.', '.$player -> id.')');
        }
        else
        {
            $db -> Execute('UPDATE `trained_stats` SET '.$_POST['train'].'='.$_POST['train'].'+'.$gain.'WHERE `id`='.$player -> id);
        }
        error (YOU_GAIN.'<b>'.$gain.'</b> '.$arrStatsDesc[$intPlayerKey].'.');
    }
}
unset($repeat, $gain, $intPlayerKey, $arrStats, $arrStatsDesc, $intCondition);
/**
* Initialization ov variables
*/
if (!isset($_GET['action']))
{
    $_GET['action'] = '';
}
if (!isset($_GET['step']))
{
    $_GET['step'] = '';
}

/**
* Assign variables to template and display page
*/
$smarty -> assign_by_ref('Action', $_GET['action']);
$smarty -> display ('train.tpl');

require_once('includes/foot.php');
?>
