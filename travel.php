<?php
/**
 *   File functions:
 *   Travel to other locations and magic portals.
 *
 *   @name                 : travel.php
 *   @copyright            : (C) 2004,2005,2006 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @author               : eyescream <tduda@users.sourceforge.net>
 *   @version              : 1.4
 *   @since                : 28.03.2007
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

$title='Stajnie';
require_once ('includes/head.php');
require_once ('includes/funkcje.php');
require_once ('includes/turnfight.php');

require_once('class/monster_class.php');
require_once('class/fight_class.php');

/**
* Initialization of variable
*/
if (!isset($_GET['akcja']))
{
    $_GET['akcja'] = '';
}
if (!isset($_GET['action']))
{
    $_GET['action'] = '';
}

/**
* Get the localization for game
*/
require_once ('languages/'.$player -> lang.'/travel.php');

if ($player -> location == L_DUNGEON)
{
    error (ERROR);
}
if ($player -> hp == 0)
{
    error (YOU_DEAD);
}
/**
* Assign variables to template
*/
$smarty -> assign(array('Portal' => '',
                        'Maps' => ''));


/**
* Function to start travel (walk or caravan), fight in random encounter and arrive on place.
* $intEnergyCost - cost of travel in energy.
* $intGoldCost - cost of travel in gold.
* $strDestination - where should player be after succesful travel.
*/

/**
* Check if player have not traveled yet (needed in card.php) 
*/
if ($_GET['akcja'] != '' && $_GET['step'] != '')
{
	$Query = $db -> Execute('SELECT `changed_loc` FROM `players` WHERE `id`='.$player -> id);
	if ($Query -> fields['changed_loc'] == "N" || $Query -> fields['changed_loc'] == "")
	{
       		$db -> Execute('UPDATE `players` SET `changed_loc`="Y" WHERE `id`='.$player -> id);
	}
}

function travel ($intEnergyCost, $intGoldCost, $strDestination)
{
    global $player;
    global $smarty;
    global $enemy;
    global $db;

    $fight = $db -> Execute('SELECT `fight` FROM `players` WHERE `id`='.$player -> id);
    if ((rand(1,5) < 5 && $fight -> fields['fight'] == 0)) // No bandits.
    {
        $db -> Execute('UPDATE `players` SET `energy`=`energy`-'.$intEnergyCost.', `credits`=`credits`-'.$intGoldCost.', `miejsce`=\''.$strDestination.'\' WHERE `id`='.$player -> id); //Pay and finish travel.
        $smarty -> assign('Message', '<p>'.MESSAGE1.'</p><p>'.YOU_REACH.'</p>');
        $smarty -> display('error1.tpl');
    }
        else
    {
        if ($player -> energy < $intEnergyCost + 1) // Too exhausted to both fight the bandit and arrive on place.
        {
            banditrobbery (MESSAGE2, $player -> location); // Instant death.
        }
        else    // Begin fight.
        {
            $smarty -> assign ('Message', MESSAGE3);
            $smarty -> display ('error1.tpl');
/*            $arrbandit = array ();
            for ($i=0;$i<8;$i++)
            {
                $arrbandit[$i] = rand(1,500);
            }*/
	if (!isset($_SESSION['amount'])) {
		$db -> Execute('UPDATE `players` SET `fight`=99999, `miejsce`=\''.L_TRAVEL.'\' WHERE `id`='.$player -> id);

		$_SESSION['amount'] = 1;
		$_SESSION['mon0']['id'] = 99999;
		$_SESSION['mon0']['user'] = 'Bandyta';
		$_SESSION['mon0']['level'] = 1;
		$_SESSION['mon0']['credits'] = rand(1,500);
		$_SESSION['mon0']['exp'] = rand(rand(1,500),rand(1,500));
		$_SESSION['mon0']['actionpoints'] = rand(1,500);
		$_SESSION['mon0']['defence'] = $_SESSION['mon0']['actionpoints'];
		$_SESSION['mon0']['hp'] = $_SESSION['mon0']['actionpoints'];
		$_SESSION['mon0']['damage'] = rand(1,500);
		$_SESSION['mon0']['hitmodificator'] = rand(1,500);
		$_SESSION['mon0']['missmodificator'] = $_SESSION['mon0']['hitmodificator'];
		$_SESSION['mon0']['attackspeed'] = rand(1,500);

		$monster = new Monster($player -> fight,1,0);
		$attacker = new Fighter($player -> id);

		for ($k = 0; $k < $_SESSION['amount']; $k++) {
			//each monster identifier
			$strIndex = 'mon'.$k;
			$_SESSION[$strIndex]['id'] = $monster -> id;
			//each monster hit points
//			$strIndex = 'monhp'.$k;
			$_SESSION[$strIndex]['hp'] = $monster -> hp;
			//each monster action points
//			$strIndex = 'monap'.$k;
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
//             $enemy = array('name' => 'Bandyta',
//                            'strength' => $arrbandit[0],
//                            'agility' => $arrbandit[1],
//                            'hp' => $arrbandit[2],
//                            'level' => $arrbandit[3],
//                            'endurance' => $arrbandit[6],
//                            'speed' => $arrbandit[7],
//                            'exp1' => $arrbandit[4],
//                            'exp2' => $arrbandit[5]);

/**
* Secure the fight (player will eventually meet the monster, and his location prevents him from cheating).
* Store the backup of original location (if player dies in fight, he will be transported back).
* Results: player won the fight and logged out before clicking the final link - all is ok.
* Player met the bandit and logged out - after log in he's dead and in location "TRAVEL". After next reset he can continue.
*/
            if (!isset ($_SESSION['originalLocation']) && $player -> location != L_TRAVEL)
            {
                $_SESSION['originalLocation'] = $player -> location;
            }
            
            turnfight ('travel.php?'.$_SERVER['QUERY_STRING']);
/*            if (!isset ($_POST['action']))
            {
                turnfight ($arrbandit[4],$arrbandit[5],'','travel.php?'.$_SERVER['QUERY_STRING']);
            }
                else
            {
                turnfight ($arrbandit[4],$arrbandit[5],$_POST['action'],'travel.php?'.$_SERVER['QUERY_STRING']);
            }*/
            $myhp = $db -> Execute('SELECT `hp`, `fight` FROM `players` WHERE `id`='.$player -> id); // After each fight turn get current info about player.
            if ($myhp -> fields['fight'] == 0)
            {
                if ($myhp -> fields['hp'] == 0) // Lost fight.
                {
                    banditrobbery (MESSAGE4, $_SESSION['originalLocation']);
                    $_SESSION['originalLocation'] = NULL;
                    unset($_SESSION['originalLocation']);
// 			$db -> Execute('UPDATE `players` SET `miejsce`=\'Altara\'');
                }
                    else
                {
                    $db -> Execute('UPDATE `players` SET `energy`=`energy`-'.($intEnergyCost+1).', `credits`=`credits`-'.$intGoldCost.', `miejsce`=\''.$strDestination.'\' WHERE `id`='.$player -> id); //Pay for travel (-fight energy) and finish.
                    $smarty -> assign('Message', '<p>'.MESSAGE5.'</p><p>'.YOU_REACH.'</p>');
                    $smarty -> display('error1.tpl');
                }
            }
            $myhp -> Close();
        }
    }
    $fight -> Close();
}

/**
* Compute amount of lost gold and platinum. Take them from player and kill him. Show him a way to resurrect.
* $strBeginningText - text saying whether we are dead because of not having enough energy, or because we lost fight.
* $strReturnLocation - where dead player should be going to (back where he came from).
*/
function banditrobbery ($strBeginningText, $strReturnLocation)
{
    global $player;
    global $db;
    global $smarty;

	if (!isset($strReturnLocation)) $strReturnLocation='Altara';

    $intLostGold = max(ceil($player -> credits * rand(5,15) / 100), 0);
    $intLostPlatinum = ceil($player -> platinum * rand(5,15) / 100);
    $db -> Execute('UPDATE `players` SET `miejsce`=\''.$strReturnLocation.'\', `hp`=0, `energy`=`energy`-1, `credits`=`credits` -'.$intLostGold.', `platinum`=`platinum` - '.$intLostPlatinum.' WHERE `id`='.$player -> id);
    $strHospital = '<p>'.A_HOSPITAL1;
    switch ($strReturnLocation)
    {
        case L_FOREST:
            $strHospital .= '<a href="las.php?action=hermit">'.A_HOSPITAL2.'</a>';
            break;
        case L_MOUNTAINS:
            $strHospital .= '<a href="gory.php?action=hermit">'.A_HOSPITAL2.'</a>';
            break;
        default:
            $strHospital .= '<a href="hospital.php">'.A_HOSPITAL2.'</a>';
    }
    $smarty -> assign('Message', $strBeginningText.$intLostGold.G_COINS.$intLostPlatinum.PLATINUM.$strHospital.'</p>');
    $smarty -> display('error1.tpl');
}

// Check if hero's reward item was already given.
$objItem = $db -> Execute('SELECT `value` FROM `settings` WHERE `setting`=\'item\'');

if ($_GET['akcja'] == '' && $_GET['action'] == '') // Display 'Stables' links.
{
    switch ($player -> location)
    {
        case L_CAPITOL:       // Capitol city - able to go through Portal or to Astral Plans.
            if ($player -> maps > 9  &&  !$objItem -> fields['value'] && $player -> rank != RANK_HERO)
            {
                $smarty -> assign(array('Maps' => 1,
                                        'Portal1' => PORTAL1,
                                        'Ayes' => YES,
                                        'Ano' => NO));
            }
            /**
            * Portals to astral plans
            */
            $arrPlans = array(MAP1, MAP2, MAP3, MAP4, MAP5, MAP6, MAP7);
            $objPlans = $db -> Execute('SELECT `name` FROM `astral_plans` WHERE `owner`='.$player -> id.' AND `name` LIKE \'M%\' AND `location`=\'V\'');
            $arrName = array('');
            $arrLink = array();
            $i = 0;
            while (!$objPlans -> EOF)
            {
                $intNumber = (int)str_replace('M', '', $objPlans -> fields['name']) -1;
                $arrName[$i] = $arrPlans[$intNumber];
                $arrLink[$i] = $intNumber;
                $i++;
                $objPlans -> MoveNext();
            }
            $objPlans -> Close();

            $smarty -> assign(array('Stablesinfo' => STABLES_INFO,
                                    'Amountains' => A_MOUNTAINS,
                                    'Aforest' => A_FOREST,
                                    'Tportals' => $arrName,
                                    'Tporlink' => $arrLink,
                                    'Tporinfo' => T_PORTALS,
                                    'Aelfcity' => A_ELFCITY));
            break;
        case L_ELVEN_CITY:
            $smarty -> assign(array('Stablesinfo' => STABLES_INFO,
                                    'Aforest' => A_FOREST));
            break;
        case L_FOREST:
            $smarty -> assign(array('Outside' => OUTSIDE,
                                    'Aelfcity' => A_ELFCITY,
                                    'Amountains' => A_MOUNTAINS));
            break;
        case L_MOUNTAINS:
            $smarty -> assign(array('Outside' => OUTSIDE,
                                    'Aforest' => A_FOREST,
                                    'Aelfcity' => A_ELFCITY));
            break;
    }
    $smarty -> assign('ACapitol', A_CAPITOL);
}

if ($_GET['action'] != '')
{
	switch ($player -> location)
	{
		case L_CAPITOL:
			if ($_GET['action'] == 'powrot')
			{
				error(YOU_IN);
			}
			break;
		case L_ELVEN_CITY:
			if ($_GET['action'] == 'city2')
			{
				error(YOU_IN);
			}
			if ($_GET['action'] == 'gory')
			{
				error(MNT_FREE);
			}
			break;
		case L_FOREST:
			if ($_GET['action'] == 'las')
			{
				error(YOU_IN);
			}
			break;
		case L_MOUNTAINS:
			if ($_GET['action'] == 'gory')
			{
				error(YOU_IN);
			}
			if ($_GET['action'] == 'city2')
			{
				error(CITY_FREE);
			}
			break;
	}

		$baseEnergyCosts = array('walk' => 5,
			'caravan' => 1,
			'teleport' => 0);
		$baseGoldCosts = array('walk' => 0,
			'caravan' => 1000,
			'teleport' => 5000);

	if ($_GET['akcja'] == '')
	{
		switch ($_GET['action'])
		{
			case 'gory':
                		if ($player -> location == L_CAPITOL)
                		{
					$baseEnergyCosts = array('walk' => 4,
						'caravan' => 1,
						'teleport' => 0);
					$baseGoldCosts = array('walk' => 0,
						'caravan' => 800,
						'teleport' => 5000);
                		}
           			break;

			case 'las':
				if ($player -> location == L_CAPITOL)
                		{
					$baseEnergyCosts = array('walk' => 2.5,
						'caravan' => 1,
						'teleport' => 0);
					$baseGoldCosts = array('walk' => 0,
						'caravan' => 500,
						'teleport' => 5000);
                		}
                		if ($player -> location == L_ELVEN_CITY)
                		{
					$baseEnergyCosts = array('walk' => 6,
						'caravan' => 1,
						'teleport' => 0);
					$baseGoldCosts = array('walk' => 0,
						'caravan' => 1200,
						'teleport' => 5000);
                		}
				break;

        		case 'city2':
                		if ($player -> location == L_FOREST)
                		{
					$baseEnergyCosts = array('walk' => 6,
						'caravan' => 1,
						'teleport' => 0);
					$baseGoldCosts = array('walk' => 0,
						'caravan' => 1200,
						'teleport' => 5000);
                		}
            			break;

        		case 'powrot':
                		if ($player -> location == L_FOREST)
                		{
					$baseEnergyCosts = array('walk' => 2.5,
						'caravan' => 1,
						'teleport' => 0);
					$baseGoldCosts = array('walk' => 0,
						'caravan' => 500,
						'teleport' => 5000);
                		}
                		if ($player -> location == L_MOUNTAINS)
                		{
					$baseEnergyCosts = array('walk' => 4,
						'caravan' => 1,
						'teleport' => 0);
					$baseGoldCosts = array('walk' => 0,
						'caravan' => 800,
						'teleport' => 5000);
                		}
            			break;
		}

	$smarty -> assign(array('Acaravan' => A_CARAVAN." (".$baseGoldCosts['caravan']." ".A_COST.", ".$baseEnergyCosts['caravan']." ".A_ENERGY1.")",
		'Awalk' => A_WALK." (".$baseEnergyCosts['walk']." ".A_ENERGY2.")",
		'Ateleport' => A_TELEPORT." (".$baseGoldCosts['teleport']." ".A_COST.")",
		'Aback' => A_BACK));
	}
}

if ($_GET['akcja'] != '' && $player -> location == L_CAPITOL && !$objItem -> fields['value'] && $player -> maps >= 10 && $player -> rank != RANK_HERO)
{
    if ($_GET['akcja'] == 'tak' )
    {
        $db -> Execute('UPDATE `players` SET `miejsce`=\''.L_PORTAL.'\', `maps` = `maps` - 10 WHERE `id`='.$player -> id);
        $smarty -> assign(array('Portal' => 'Y',
                                'Portal2' => PORTAL2));
    }
    if ($_GET['akcja'] == 'nie')
    {
        $smarty -> assign(array('Portal' => 'N',
                                'Portal3' => PORTAL3));
    }
}
$objItem -> Close();

if ($_GET['akcja'] != '' && $_GET['akcja'] !== 'tak' && $_GET['akcja'] !== 'nie')
{
/**
* Travel to places other than portals - error checking.
*/
    if ( $_GET['step'] !=='walk' &&  $_GET['step'] !=='caravan' && $_GET['step'] !=='teleport')
    {
        error (ERROR);
    }
    $arrLocation = array(L_CAPITOL, L_ELVEN_CITY, L_TRAVEL, L_MOUNTAINS, L_FOREST);
    if (!in_array($player -> location, $arrLocation))
    {
        error (ERROR);
    }
    $arrEnergyCosts = array('walk' => 5,
                            'caravan' => 1,
                            'teleport' => 0);
    $arrGoldCosts = array('walk' => 0,
                          'caravan' => 1000,
                          'teleport' => 5000);
    $intEnergy = $arrEnergyCosts[$_GET['step']];    // Assign basic travel costs.
    $intGold = $arrGoldCosts[$_GET['step']];

/**
* Set travel cost based on current place, destination and travel method.
*/
    switch ($_GET['akcja'])
    {
/**
* Travel to mountains.
*/
        case 'gory':
            if ($player -> location == L_ELVEN_CITY)
            {
                 error (ERROR);
            }
            if( $_GET['step'] == 'teleport')
            {
                $db -> Execute('UPDATE `players` SET `credits`=`credits`-'.$arrGoldCosts['teleport'].', `miejsce`=\''.L_MOUNTAINS.'\' WHERE `id`='.$player -> id);
                error (YOU_REACH);
            }
                else
            {
                if ($player -> location == L_CAPITOL)
                {
			$intEnergy *= 0.8;
			$intGold *= 0.8;
			if ($_GET['step'] == 'caravan')
			{
				$intEnergy = 1;
			}
                }
    		if ($player -> energy < $intEnergy)
    		{
        		error (NO_ENERGY);
    		}
    		if ($player -> credits < $intGold && $_GET['step'] != 'walk') // Criminals with fines still can walk.
    		{
       		error (NO_MONEY);
    		}
                travel ($intEnergy, $intGold, L_MOUNTAINS);
            }
            break;
/**
* Travel to forest.
*/
        case 'las':
            if( $_GET['step'] == 'teleport')
            {
                $db -> Execute('UPDATE `players` SET `credits`=`credits`-'.$arrGoldCosts['teleport'].', `miejsce`=\''.L_FOREST.'\' WHERE `id`='.$player -> id);
                error (YOU_REACH);
            }
                else
            {
                if ($player -> location == L_CAPITOL)
                {
			$intEnergy *= 0.5;
			$intGold *= 0.5;
			if ($_GET['step'] == 'caravan')
			{
				$intEnergy = 1;
			}
                }
                if ($player -> location == L_ELVEN_CITY)
                {
			$intEnergy *= 1.2;
			$intGold *= 1.2;
			if ($_GET['step'] == 'caravan')
			{
				$intEnergy = 1;
			}
                }
    		if ($player -> energy < $intEnergy)
    		{
        		error (NO_ENERGY);
    		}
    		if ($player -> credits < $intGold && $_GET['step'] != 'walk') // Criminals with fines still can walk.
    		{
       		error (NO_MONEY);
    		}
                travel ($intEnergy, $intGold, L_FOREST);
            }
            break;
/**
* Travel to Agarakar (in code marked as Elven City).
*/
        case 'city2':
            if ($player -> location == L_MOUNTAINS)
            {
                 error (ERROR);
            }
            if ( $_GET['step'] == 'teleport')
            {
                $db -> Execute('UPDATE `players` SET `credits`=`credits`-'.$arrGoldCosts['teleport'].', `miejsce`=\''.L_ELVEN_CITY.'\' WHERE `id`='.$player -> id);
                error (YOU_REACH);
            }
                else
            {
                if ($player -> location == L_FOREST)
                {
			$intEnergy *= 1.2;
			$intGold *= 1.2;
			if ($_GET['step'] == 'caravan')
			{
				$intEnergy = 1;
			}
                }
    		if ($player -> energy < $intEnergy)
    		{
        		error (NO_ENERGY);
    		}
    		if ($player -> credits < $intGold && $_GET['step'] != 'walk') // Criminals with fines still can walk.
    		{
       		error (NO_MONEY);
    		}
                travel ($intEnergy, $intGold, L_ELVEN_CITY);
            }
            break;
/**
* Travel to Capitol, general 'back' action.
*/
        case 'powrot':
            if( $_GET['step'] == 'teleport')
            {
                $db -> Execute('UPDATE `players` SET `credits`=`credits`-'.$arrGoldCosts['teleport'].',  `miejsce`=\''.
                L_CAPITOL.'\' WHERE `id`='.$player -> id);
                error (YOU_REACH);
            }
                else
            {
                if ($player -> location == L_FOREST)
                {
			$intEnergy *= 0.5;
			$intGold *= 0.5;
			if ($_GET['step'] == 'caravan')
			{
				$intEnergy = 1;
			}
                }
                if ($player -> location == L_MOUNTAINS)
                {
			$intEnergy *= 0.8;
			$intGold *= 0.8;
			if ($_GET['step'] == 'caravan')
			{
				$intEnergy = 1;
			}
                }
    		if ($player -> energy < $intEnergy)
    		{
        		error (NO_ENERGY);
    		}
    		if ($player -> credits < $intGold && $_GET['step'] != 'walk') // Criminals with fines still can walk.
    		{
       		error (NO_MONEY);
    		}
                travel ($intEnergy, $intGold, L_CAPITOL);
            }
	}
}


/**
* Assign variables to template and display page
*/
$smarty -> assign ( array('Action' => $_GET['akcja'],
                          'Action2' => $_GET['action'],
                          'Location' => $player -> location,
                          'LCapitol' => L_CAPITOL,
                          'LElvenCity' => L_ELVEN_CITY,
                          'LMountains' => L_MOUNTAINS,
                          'LForest' => L_FOREST,
                          'LTravel' => L_TRAVEL));
$smarty -> display('travel.tpl');

require_once("includes/foot.php");
?>
