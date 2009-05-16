<?php
/**
 *   File functions:
 *   Outposts - a game in game.
 *
 *   @name				 : outposts.php
 *   @copyright			: (C) 2004,2005,2006,2007 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author			   : thindil <thindil@users.sourceforge.net>
 *   @author			   : eyescream <tduda@users.sourceforge.net>
 *   @version			  : 1.4
 *   @since				: 19.07.2007
 *
 */

// Published under GNU GPL 2 or later. See /install/README file for details.
// $Id$

$title = 'Strażnica';
/**
 * If it's first click on outpost link or outpost just has been bought: prepare AJAX-powered page backbone (tabs).
 * Else get only the really required information (without Navigation menu etc).
 */
require_once(empty($_GET) || isset($_GET['action'])? 'includes/head.php' : 'includes/minihead.php');
require_once('languages/'.$player -> lang.'/outposts.php');

/**
 * Check basic errors.
 */
if($player -> location != 'Altara' && $player -> location != 'Ardulith')
	error (NOT_IN_CITY);
if ($player -> hp <= 0 && !isset($_GET['listing']))
	error(YOU_DEAD.' (<a href="city.php">'.BACK.'</a>)');
if ($player -> race == '' || $player -> clas == '')
	error(YOU_UNFINISHED.' (<a href="city.php">'.BACK.'</a>)');

$db -> SetFetchMode(ADODB_FETCH_NUM);
require_once('class/outpost_class.php');
require_once('includes/security.php');

/**
* Get information about outposts from database
*/
$out = & new Outpost($player -> id, $db);
/**
* If player doesn't have a outpost - give him ability to buy one.
*/
if (!$out -> id && !isset ($_GET['action']))
	error(NO_OUTPOST);
if (isset ($_GET['action']) && $_GET['action'] == 'buy')
{
	if ($out -> id)
		error (YOU_BUY);
	if ($player -> credits < 500)
		error(NO_MONEY);
	buy($player -> id, $player -> clas, 500, $db);
}

/**
* Add gold to outpost or take it from outpost
*/
if (isset ($_GET['view']) && $_GET['view'] == 'gold')
{
   /**
	* Get gold from outpost
	*/
	if (isset($_POST['zeton']))
	{
		if (!uint32($_POST['zeton']))
			error(ERROR);
		$smarty -> assign ('Message', YOU_CHANGE.$_POST['zeton'].GOLD_ON.($out -> goldBalance(-$_POST['zeton'])).GOLD_ON2);
	}
	/**
	* Add gold to outpost
	*/
	if (isset($_POST['sztuki']))
	{
		if (!uint32($_POST['sztuki']))
			error(ERROR);
		$out -> goldBalance($_POST['sztuki'], $player -> credits);
		$smarty -> assign ('Message', YOU_ADD.' '.$_POST['sztuki'].' '.TO_OUT);
	}
	$smarty -> assign(array('Treasury' => $out -> gold,
							'GoldInHand' => $player -> credits));
}

/**
* Informations about outpost and gaining leadership ability
*/
if (isset ($_GET['view']) && $_GET['view'] == 'my')
{
	/**
	* Gain leadership bonus
	*/
	$arrSQLNames = array('battack', 'bdefense', 'btax', 'blost', 'bcost');
	if (isset($_GET['type']) && in_array($_GET['type'], $arrSQLNames) && isset($_GET['bonus']) && strictInt($_GET['bonus']) && $_GET['bonus'] > 0 && $out -> bonusAttack + $out -> bonusDefense + $out -> bonusTax + $out -> bonusLost + $out -> bonusGold + $_GET['bonus'] - 5 <= $player -> leadership)
	{
		if ($_GET['type'] == 'bcost' && $out -> bonusGold > 49 || $_GET['type'] == 'blost' && $out -> bonusLost > 49)
			error(MAX_LEVEL);
		$db -> Execute('UPDATE `outposts` SET `'.$_GET['type'].'`=`'.$_GET['type'].'`+'.$_GET['bonus'].' WHERE `id`='.$out -> id);
		$arrNames = array('bonusAttack', 'bonusDefense', 'bonusTax', 'bonusLost', 'bonusGold');
		$out -> $arrNames[array_search($_GET['type'], $arrSQLNames)] += $_GET['bonus'];
	}
	if ($out -> morale > 49)
		$strMorale = MORALE1;
	elseif ($out -> morale > -49)
		$strMorale = MORALE2;
	else
		$strMorale = MORALE3;
	$arrInfo = & $out -> info();
	$smarty -> assign ( array('User' => $player -> user,
							  'Size' => $out -> size,
							  'Turns' => $out -> turns,
							  'Gold' => $out -> gold,
							  'Warriors' => $out -> warriors,
							  'Barricades' => $out -> barricades,
							  'Archers' => $out -> archers,
							  'Catapults' => $out -> catapults,
							  'Maxtroops' => $arrInfo[0],
							  'Maxequip' => $arrInfo[1],
							  'Cost' => $arrInfo[7],
							  'Attack' => $out -> bonusAttack,
							  'Defense' => $out -> bonusDefense,
							  'Tax' => $out -> bonusTax,
							  'Lost' => $out -> bonusLost,
							  'Bcost' => $out -> bonusGold,
							  'Fence' => $out -> lairs,
							  'Maxfence' => $arrInfo[2],
							  'Monsters' => $arrInfo[4],
							  'Barracks' => $out -> barracks,
							  'Maxbarracks' => $arrInfo[3],
							  'Veterans' => $arrInfo[5],
							  'Maxveterans' => $out -> barracks - count($arrInfo[5]),
							  'Fatigue' => $arrInfo[6],
							  'Morale' => $out -> morale,
							  'Moralename' => $strMorale,
							  'Leadership' => $player -> leadership));
}

/**
* Get tax from villages
*/
if (isset ($_GET['view']) && $_GET['view'] == 'taxes')
{
	if ($out -> turns < 1)
		error(NO_EN_AP);
	if (!($out -> warriors + $out -> archers))
		error(NO_SOLDIERS);
	if (isset($_POST['amount']))
	{
		if (uint32($_POST['amount']) < 1)
		   error (ERROR);
		$smarty -> assign ('Message', YOU_ARMY.$_POST['amount'].TIMES_FOR.($out -> getTaxes($_POST['amount'])).GOLD_COINS);
	}
	$smarty -> assign ('AttackPoints', $out -> turns);
}

/**
* Outposts list.
* Result array is unified no mather the search method (level, owner's name, ID or tribe) and contains in each row:
* size, number of attacks, owner ID, owner name.
*/
if (isset ($_GET['view']) && $_GET['view'] == 'listing')
{
	$arrMax = $db -> GetRow('SELECT MAX(size) from `outposts`');
	$smarty -> assign('MaxLevel', $arrMax[0]);
	if (isset($_POST['slevel']) && isset($_POST['elevel']))
	{
		if (!strictInt($_POST['slevel']) || !strictInt($_POST['elevel']) || ($_POST['slevel'] > $_POST['elevel']))
			error (ERROR);
		$arrOutpost = $db -> GetAll('SELECT `size`, `attacks`, `owner` FROM `outposts` WHERE `size`>='.$_POST['slevel'].' AND `size`<='.$_POST['elevel'].' AND `id`!='.$out -> id.' ORDER BY `size` DESC');
		for ($i = 0, $intMax = count($arrOutpost); $i < $intMax; $i++)
		{
			$arrTemp = $db -> GetRow('SELECT `user` FROM `players` WHERE `id`='.$arrOutpost[$i][2]);
			$arrOutpost[$i][3] = $arrTemp[0];
		}
	}
	elseif (isset($_POST['id']) && strictInt($_POST['id']) && $_POST['id'] != 0 && $_POST['id'] != $player -> id)
	{
		$arrOutpost =  $db -> GetAll('SELECT `size`, `attacks` FROM `outposts` WHERE `owner`='.$_POST['id'].' ORDER BY `size` DESC');
		if (!empty($arrOutpost[0]))
		{
			$arrOutpost[0][2] = $_POST['id'];
			$arrTemp = $db -> GetRow('SELECT `user` FROM `players` WHERE `id`='.$_POST['id']);
			$arrOutpost[0][3] = $arrTemp[0];
		}
		else
			unset($arrOutpost);
	}
	elseif (isset($_POST['searched']))
	{
		sqlLikeString($_POST['searched']);
		$arrTest = $db -> GetAll('SELECT `id`, `user` FROM `players` WHERE `user` LIKE '.$_POST['searched']);
		for ($i = 0, $j = 0, $intMax = count($arrTest), $arrOutpost = array(); $i < $intMax; $i++)
		{
			if ($arrTest[$i][0] != $player -> id)
			{
				$arrTemp = $db -> GetRow('SELECT `size`, `attacks` FROM `outposts` WHERE `owner`='.$arrTest[$i][0]);
				if (isset($arrTemp[0]))
				{
					$arrOutpost[$j][0] = $arrTemp[0];
					$arrOutpost[$j][1] = $arrTemp[1];
					$arrOutpost[$j][2] = $arrTest[$i][0];
					$arrOutpost[$j++][3] = $arrTest[$i][1];
				}
			}
		}
	}
	elseif(isset($_POST['tribe']))
	{
		sqlLikeString($_POST['tribe']);
		$arrTest = $db -> GetAll('SELECT `id` FROM `tribes` WHERE `name` LIKE '.$_POST['tribe']);
		for ($i = 0, $j=0, $intMax1 = count($arrTest), $arrOutpost = array(); $i < $intMax1; $i++)
		{
			$arrTest2 = $db -> GetAll('SELECT `id`, `user` FROM `players` WHERE `tribe`='.$arrTest[$i][0]);
			for ($k=0, $intMax2 = count($arrTest2); $k < $intMax2; $k++)
			{
				if ($arrTest2[$k][0] != $player -> id)
				{
					$arrTemp = $db -> GetRow('SELECT `size`, `attacks` FROM `outposts` WHERE `owner`='.$arrTest2[$k][0]);
					if (isset($arrTemp[0]))
					{
						$arrOutpost[$j][0] = $arrTemp[0];
						$arrOutpost[$j][1] = $arrTemp[1];
						$arrOutpost[$j][2] = $arrTest2[$k][0];
						$arrOutpost[$j++][3] = $arrTest2[$k][1];
					}
				}
			}
		}
	}
	if(isset($arrOutpost))
	{
		$smarty -> assign_by_ref('Outposts', $arrOutpost);
		$smarty -> assign('MinSize', round($out -> size / 2) - 1);
	}
}

/**
* Battle of outposts
*/
if (isset ($_GET['view']) && $_GET['view'] == 'battle')
{
	if (isset($_POST['oid']) && strictInt($_POST['oid']) && isset($_POST['amount']) && strictInt($_POST['amount']))
	{
		if ($out -> fatigue == 20)
			error(TOO_FAT);
		if ($out -> turns < $_POST['amount'])
			error (NO_AP);
		if ($_POST['oid'] == $out -> ownerId)
			error (ITS_YOUR);

		$myOut = &$out; // rename variable
		$enemyOut = & new Outpost($_POST['oid'], $db);
		if (!$enemyOut -> id)
			error (NO_OUT);
		$arrEnemy = $db -> getRow('SELECT `user`, `leadership` FROM `players` WHERE `id`='.$enemyOut -> ownerId);

		if ($_POST['amount'] > 3 || $enemyOut -> attacks > 2)
			error(TOO_MUCH_A);

		/**
		* Make few attacks
		*/
		$arrAttackerInfo = array(YOU_ATTACKED.' <a href="view.php?view='.$_POST['oid'].'">'.$arrEnemy[0].'</a> '.$_POST['amount'].' '.TIMES);
		$arrDefenderInfo = array(YOU_WERE_ATTACKED_BY.' <a href="view.php?view='.$player -> id.'">'.$player -> user.'</a> '.$_POST['amount'].' '.TIMES);
		for ($k = 0; $k < $_POST['amount']; ++$k)
		{
			/**
			* Count attack and defense values of outposts and perform one attack.
			*/
			$myOut -> stats();
			$enemyOut -> stats();
			$myOut -> attack($enemyOut);

			$arrAttackerInfo[] = '<b>'.($k+1).'</b> '.STATS_WERE.': '.$myOut -> attack.' '.ATTACK.' '.$enemyOut -> defense.' '.DEFENSE.' '.RESULT.' '.(($myOut -> attack > $enemyOut -> defense) ? YOU_WON : YOU_LOST);
			$arrDefenderInfo[] = '<b>'.($k+1).'</b> '.STATS_WERE.': '.$myOut -> attack.' '.ATTACK.' '.$enemyOut -> defense.' '.DEFENSE.' '.RESULT.' '.(($myOut -> attack > $enemyOut -> defense) ? YOU_LOST : YOU_WON);

			$myOut -> lostspecials();
			$enemyOut -> lostspecials();
			$strMy = $myOut -> battlereport(false);
			$strEnemy = $enemyOut -> battlereport(true);
			$arrAttackerInfo[] = YOU_LOSE.$strMy;
			$arrAttackerInfo[] = HE_LOSSES.$strEnemy;
			$arrDefenderInfo[] = YOU_LOSE.$strEnemy;
			$arrDefenderInfo[] = HE_LOSSES.$strMy;

			if ($myOut -> fatigue == 20)
			{
				$arrAttackerInfo[] = YOUR_ARMY_IS_TOO_TIRED;
				break;
			}
			if ($enemyOut -> attacks > 2)
			{
				$arrAttackerInfo[] = YOU_CANNOT_ATTACK_HIM_MORE;
				break;
			}
		}
		$arrAttackerInfo[] = (($myOut -> intGaingold > 0) ? WON_GOLD : LOST_GOLD).': '.abs($myOut -> intGaingold).'. '.GAIN_LEADERSHIP.': '.round($myOut -> fltGainLeadership, 2);
		$arrDefenderInfo[] = (($enemyOut -> intGaingold < 0) ? LOST_GOLD : WON_GOLD).': '.abs($enemyOut -> intGaingold).'. '.GAIN_LEADERSHIP.': '.round($enemyOut -> fltGainLeadership, 2);

		$myOut -> writedata(true);
		$enemyOut -> writedata(true);

		$db -> Execute('INSERT INTO `log` (`owner`, `log`, `czas`) VALUES('.$_POST['oid'].','.$db -> qstr(implode($arrDefenderInfo, "")).', '.$db -> DBDate($newdate).')');
		$smarty -> assign_by_ref('AttackerInfo', $arrAttackerInfo);
	}
}

/**
* Buy army and new buildings to outpost
*/
if (isset ($_GET['view']) && $_GET['view'] == 'shop')
{
	$arrMinerals = $db -> GetRow('SELECT `pine`, `crystal`, `adamantium`, `meteor` FROM `minerals` WHERE `owner`='.$player -> id);
	if(empty($arrMinerals))
		$arrMinerals = array(0,0,0,0);

	// Increase outpost's level.
	if (isset($_POST['level']) && strictInt($_POST['level']) > 0)
		$smarty -> assign( 'Message', $out -> upgrade('size', $_POST['level'], $player -> platinum, $arrMinerals[0]));
	// Compute max available level. If done after upgrading - will get new value, so no need to refresh page.
	$intMaxLevel = $out -> checkresources ('size', $player -> platinum, $arrMinerals[0]);

	if (isset($_POST['lairs']) && strictInt($_POST['lairs']) > 0)
		$smarty -> assign( 'Message', $out -> upgrade('lairs', $_POST['lairs'], $arrMinerals[3], $arrMinerals[1]));
	$intMaxLair = $out -> checkresources ('lairs', $arrMinerals[3], $arrMinerals[1]);

	if (isset($_POST['barracks']) && strictInt($_POST['barracks']) > 0)
		$smarty -> assign( 'Message', $out -> upgrade('barracks', $_POST['barracks'], $arrMinerals[3], $arrMinerals[2]));
	$intMaxBarracks = $out -> checkresources ('barracks', $arrMinerals[3], $arrMinerals[2]);

	if (isset($_POST['buy']))
	{
		$arrNames = array('warriors', 'archers', 'barricades', 'catapults');
		for($i = 0, $arrInput = array(); $i < 4; ++$i)
			$arrInput[$i] = (isset($_POST[$arrNames[$i]]) && uint32($_POST[$arrNames[$i]])) ? $_POST[$arrNames[$i]] : 0;
		$smarty -> assign('Message', YOU_HAVE_SPENT.($out -> buyArmy($arrInput)).GOLD_COINS.ON.': '.$arrInput[0].' '.WARRIORS.', '.$arrInput[1].' '.ARCHERS.', '.$arrInput[2].' '.BARRICADES.', '.$arrInput[3].' '.CATAPULTS.'.');
	}
	$maxtroops = floor(min(($out -> size * 20) - $out -> warriors - $out -> archers, $out -> gold / 25));
	$maxequips = floor(min(($out -> size * 10) - $out -> catapults - $out -> barricades, $out -> gold / 35));

	$smarty -> assign (array('Size' => $out -> size,
							 'Gold' => $out -> gold,
							 'BaseLairs' => $out -> lairs,
							 'BaseBarracks' => $out -> barracks,
							 'Platinum' => $player -> platinum,
							 'Pine' => $arrMinerals[0],
							 'Crystal' => $arrMinerals[1],
							 'Adamantium' => $arrMinerals[2],
							 'Meteor' => $arrMinerals[3],
							 'MaxPossibleLevel' => $intMaxLevel ? $intMaxLevel : 0,
							 'MaxPossibleLair' => $intMaxLair ? $intMaxLair : 0,
							 'MaxPossibleBarrack' => $intMaxBarracks ? $intMaxBarracks : 0,
							 'Maxtroops' => $maxtroops,
							 'Maxequips' => $maxequips));
}

/**
* View details about outpost's monsters and hire new from Familiars' Glade (core.php).
*/
if (isset ($_GET['view']) && $_GET['view'] == 'beasts')
{
	$arrMonsters = $out -> getBeasts();
	$arrCores = $out -> getFamiliars($names); // $names - from language file.
	$intSum = count($arrMonsters);
	if (isset($_POST['id']))
		$smarty -> assign('Message', $out -> addBeast(strictInt($_POST['id']), $arrMonsters, $arrCores, $names));
	$smarty -> assign_by_ref('Monsters', $arrMonsters);
	$smarty -> assign_by_ref('Cores', $arrCores);
	$smarty -> assign(array('Lairs' => $out -> lairs,
							'Freelairs' => $out -> lairs - $intSum));
}

/**
* View details about outpost's veterans, hire new ones and equip them.
*/
if (isset ($_GET['view']) && $_GET['view'] == 'veterans')
{
/*  On first viewing player sees empty work area (<table> with images-dropzones), first tab of his equipment and possibly list of his veterans. Work area contains empty fields and zeroes in places for items ID's.
	To add new veteran player must click "send" button, possibly dropping first items on appropriate images.
	To edit a existing veteran, he must drag him on the work area. This generates a problem with item's which veteran already has. Their ID's are lost, so what should we use as a identifier? I decided to use:
	0 - to indicate that "this" item should be removed from veteran
	any non-zero value - to indicate ID of normal added item, from `equipment` database table
	2147483647 - max signed integer - to indicate "leave this part of veterans equipment as is"
	This way, whenever max int is changed, I know I must update accordingly, else it can be left alone.
*/
	$arrVeterans = $out -> getVeterans();   // id, name, power and defense of all veterans
	$arrVeteran = array(0, NEW_NAME, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0);  // detailed info about one (real data when he is edited, else dummy data)
	$intSum = count($arrVeterans);
	if (isset($_POST['vid']))
	{
		if ($_POST['vid'] == 0)
/// Add new veteran.
			$smarty -> assign('Message', $out -> addVeteran($arrVeterans));
	   elseif (count($_POST) == 1)
/// Get info about selected veteran for edition.
		{
			$arrVeteran = $out -> getVeteran(strictInt($_POST['vid']));
			for ($i = 2; $i < 17; $i += 2)
				if ($arrVeteran[$i] != '')
				{
					$arrVeteran[$i] .= ' <b>+'.$arrVeteran[$i+1].'</b>';  // add "power" to item's name
					$arrVeteran[$i+1] = 2147483647;					 // and set "don't change" information
				}
		}
		else
/// Finalize edition of selected veteran.
			$smarty -> assign('Message', $out -> modifyVeteran($arrVeterans));
	}
	$smarty -> assign_by_ref('Veterans', $arrVeterans);
	$smarty -> assign_by_ref('VetDetails', $arrVeteran);
	$smarty -> assign(array('Barracks' => $out -> barracks,
							'Freebarracks' => $out -> barracks - $intSum));
}

/**
* Display player's equipment in tabs. Useful for veterans.
*/
if (isset ($_GET['view']) && $_GET['view'] == 'equip' && isset($_GET['type']) && in_array($_GET['type'], array('W', 'B', 'A', 'S', 'H', 'L', 'I')))
{
	$smarty -> assign_by_ref('Equipment', $out -> getEquipment($_GET['type']));
	$arrTypes = array('W' => 'sidearm',
					  'B' => 'bow',
					  'A' => 'armor',
					  'S' => 'shield',
					  'H' => 'helmet',
					  'L' => 'legs',
					  'I' => 'ring');
	$smarty -> assign('Type', $arrTypes[$_GET['type']]);
	unset($arrTypes);
}
/**
* Display page.
*/
$smarty -> display('outposts.tpl');
require_once(empty($_GET) || isset($_GET['action']) ? 'includes/foot.php' : 'includes/minifoot.php');
?>