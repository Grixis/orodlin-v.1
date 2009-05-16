<?php
/**
 *   File functions:
 *   Temple
 *
 *   @name                 : temple.php
 *   @copyright            : (C) 2004, 2005, 2006 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @author               : eyescream <tduda@users.sourceforge.net>
 *   @author               : Zamareth <zamareth@users.sourceforge.net>
 *   @version              : 1.4
 *   @since                : 13.09.2007
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

$title = 'Świątynia';
require_once('includes/head.php');
require_once('includes/security.php');

/**
* Get the localization for game
*/
require_once('languages/'.$player -> lang.'/temple.php');

if ($player -> location != 'Altara' && $player -> location != 'Ardulith')
{
    error (ERROR);
}

// Polish grammar.
$strGod = $player -> deity;
if (in_array($player -> deity, array('Tartus', 'Unrod', 'Luzubal', 'Gulgard')))
{
    $strGod .='a';
}
if ($player -> deity == 'Anea')
{
	$strGod = 'Anei';
}
if ($player -> deity == 'Yala')
{
	$strGod = 'Yali';
}
if (isset ($_GET['view']))
{
/**
* Check common errors.
*/
    if ($_GET['view'] == 'service' || $_GET['view'] == 'prayer')
    {
        if ($player -> deity == '')
        {
            error (NO_DEITY);
        }
        if ($player -> hp == 0)
        {
            error (YOU_DEAD);
        }
    }
    switch($_GET['view'])
    {
        case 'service':     /// Work in temple to gain faith points.
            if (isset($_POST['rep']))
            {
                $intNum = uint32($_POST['rep']);
                if ($player -> energy < $intNum / 2)
                {
                    error (NO_ENERGY);
                }
                $smarty -> assign ('Message', YOU_WORK.$intNum.YOU_WORK2);
                $db -> Execute('UPDATE `players` SET `energy`=`energy`-'.($intNum / 2).', `pw`=`pw`+'.$intNum.' WHERE `id`='.$player -> id);
            }
            break;
        case 'book':        /// Read the story.
            $intKey = isset($_GET['book']) ? $_GET['book'] : 0;
            if( $intKey > 2)
            {
                $intKey = 2;
            }
            $smarty -> assign(array('Booktext' => $arrText[$intKey],
                                    'Next' => $intKey));
            unset($arrText);
            break;
        case 'pantheon':    ///View game pantheon.
            $smarty -> assign_by_ref('Godnames', $arrNames);
            $smarty -> assign_by_ref('Goddesc', $arrDesc);
            break;
        case 'prayer':      ///Pray to god.
            if ($player -> race =='')
            {
                error(NO_RACE);
            }
			if ($player -> faith < 1)
			{
				error (NO_PW);
			}
            // All possible blessings.
			//TODO: dodac hutnictwo, szpiegostwo gdy pojawia sie w grze
            $arrBless = array('agility', 'strength', 'inteli', 'wisdom', 'szyb', 'wytrz', 'hp', 'ability', 'alchemia', 'fletcher', 'atak', 'shoot', 'unik', 'magia', 'breeding', 'mining', 'lumberjack', 'herbalist', 'jeweller', 'hutnictwo');
            $arrBlessNames = array(AGI, STR, INTELI, WIS, SPE, CON, HITPTS, SMI, ALC, FLE, WEA, SHO, DOD, CAS, BRE, MINI, LUMBER, HERBS, JEWEL, METAL);

            // Costs of praying in faith points for each race. See $arrBless for values matching.
            $arrRaces = array('Człowiek', 'Elf', 'Krasnolud', 'Hobbit', 'Jaszczuroczłek', 'Gnom');
            $arrFaithCosts = array(array('10', '10', '10', '10', '10', '10', '10', '10', '10', '10', '10', '10', '10', '10', '10', '10', '10', '10', '10', '10'),
								   array('7', '15', '10', '10', '7', '15', '15', '15', '7', '7', '15', '7', '10', '7', '7', '15', '7', '7', '10', '15'),
								   array('15', '7', '10', '10', '15', '7', '7', '7', '15', '10', '7', '15', '10', '15', '15', '7', '15', '15', '7', '7'),
								   array('7', '15', '10', '10', '7', '10', '10', '10', '15', '10', '10', '10', '10', '7', '7', '10', '10', '7', '10', '15'),
								   array('7', '7', '15', '15', '7', '7', '7', '10', '10', '10', '10', '10', '10', '15', '15', '15', '15', '15', '15', '10'),
								   array('10', '15', '10', '15', '15', '10', '10', '7', '7', '7', '15', '15', '15', '15', '7', '7', '15', '7', '7', '7'));
			$intRaceKey = array_search($player -> race, $arrRaces);
			// Note: All gods can bless stats (0-5). Heluvald and Daeraell have the same bonuses.
			$arrGods = array('Tartus', 'Anea', 'Meltis', 'Dintel', 'Yala', 'Unrod', 'Luzubal', 'Gulgard', 'Artis');
			$intGodKey = array_search($player -> deity, $arrGods);
			$arrPossibleBlessings = array(array(1, 2, 10, 13, 19),
										  array(0, 3, 12, 13),//szpiegostwo
										  array(1, 5, 15, 16, 17),
										  array(3, 6, 8, 14, 17),
										  array(1, 2, 7, 9, 18),
										  array(0, 5, 9, 11),//, Złodziejstwo),
										  array(2, 4, 10, 11),//wywiad
										  array(3, 5, 7, 15, 19),
										  array(0, 4, 8, 18));//Złodziejstwo
			foreach ($arrPossibleBlessings[$intGodKey] as $Key)
			{
				$arrBlessings[] = array($arrBlessNames[$Key], $arrFaithCosts[$intRaceKey][$Key]);
			}
			$smarty -> assign_by_ref('Blessings', $arrBlessings);
			$smarty -> assign_by_ref('Indices', $arrPossibleBlessings[$intGodKey]);
			if (isset($_POST['pray']))
			{
				$intNumber = uint32($_POST['pray']);
				// Check if someone tampered with form: wrong energy cost, wrong blessing number passed.
				if (!preg_match("/^[01246]$/", $_POST['praytype']) || !in_array($intNumber, $arrPossibleBlessings[$intGodKey]))
				{
					error(ERROR);
				}
				if ($_POST['praytype'] == 0)
				{
					$intPermCost = $intNumber < 7 ? 100 : 300;
					if ($player -> faith - $intPermCost < 0)
					{
						error(NO_PW);
					}
					$strQuery = 'UPDATE `players` SET `'.$arrBless[$intNumber].'`=`'.$arrBless[$intNumber].'`+1, ';
					if ($_POST['pray'] == 5 || $_POST['pray'] == 6)
					{
						$strQuery .= '`max_hp`=`max_hp`+1, ';
					}
					$strQuery .= '`pw`=`pw`-'.$intPermCost.' WHERE `id`='.$player -> id;
					$db -> Execute($strQuery);
					$strMessage = PERM_BONUS.$arrBlessNames[$intNumber];
					$smarty -> assign('Message', $strMessage);
					break;
				}
				if ($player -> bless !='')
				{
					error(YOU_HAVE);
				}
				if ($player -> energy < $_POST['praytype'])
				{
					error(NO_ENERGY);
				}
				if ($player -> faith < $arrFaithCosts[$intRaceKey][$intNumber])
				{
					error (NO_PW);
				}
				$strMessage = YOU_PRAY.$strGod;
				$strQuery = 'UPDATE `players` SET `pw`=`pw`-'.$arrFaithCosts[$intRaceKey][$intNumber].', `energy`=`energy`-'.$_POST['praytype'];
				switch (rand(1, 10))
				{
					case 10: // god's wrath
						$strMessage .= P_DEAD.$player -> deity.P_DEAD2;
						$strQuery .= ', `hp`=0';
						break;
					case 9: // nothing
						$strMessage .= BUT_FAIL;
						break;
					default:    // success
						$intBonus = $_POST['praytype'] * $player -> level;
						// Blessings to skills are reduced.
						if ($intNumber > 5)
						{
							$intBonus /= 10;
						}
						$strQuery .= ', `bless`=\''.$arrBless[$intNumber].'\', `blessval`='.$intBonus;
						$strQuery .= $intNumber == 6 ? ', `hp`=`hp`+'.$intBonus : '';
						$strMessage .= P_SUCCESS.$arrBlessNames[$intNumber];
				}
				$smarty -> assign('Message', $strMessage);
				$db -> Execute($strQuery.' WHERE `id`='.$player -> id);
			}
			break;
		default:
			error(ERROR);
	}
}
else
{
    $_GET['view'] = '';
}

/**
* Assign variables to template and display page
*/
$smarty -> assign(array('God' => $player -> deity,
						'God2' => $strGod,
						'Location' => $player -> location));
$smarty -> display('temple.tpl');

// After displaying delete arrays that aren't used anymore.
if (isset($arrNames))
{
    unset($arrNames,$arrDesc);
}
if (isset($arrBless))
{
    unset($arrBless, $arrBlessNames, $arrRaces, $arrFaithCosts, $arrGods, $arrPossibleBlessings, $arrBlessings);
}
require_once('includes/foot.php');
?>

