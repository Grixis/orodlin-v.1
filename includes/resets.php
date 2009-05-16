<?php
/**
 *   File functions:
 *   Resets in game - (mainreset) main reset and (smallreset) other resets
 *
 *   @name				 : resets.php
 *   @copyright			: (C) 2004,2005,2006,2007 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author			   : thindil <thindil@users.sourceforge.net>
 *   @author			   : eyescream <tduda@users.sourceforge.net>
 *   @version			  : 1.4
 *   @since				: 17.07.2007
 *
 */

// Published under GNU GPL 2 or later. See /install/README file for details.
// $Id$

/// TODO: Warehouse pricing. Language files - rings, dwarven treasury, outpost problems.

/**
 * Check available languages
 */
$path = 'languages/';
$dir = opendir($path);
$arrLanguage = array();
$i = 0;
while ($file = readdir($dir))
	if (!ereg(".htm*$", $file))
		if (!ereg("\.$", $file))
			$arrLanguage[$i++] = $file;
closedir($dir);

$oldFetchMode = $db -> SetFetchMode(ADODB_FETCH_NUM);

function start()  /// Things that take place during both resets.
{
	global $db;
	global $arrLanguage;
	// Protect from database inconsistency (transation-like lock).
	$db -> Execute('UPDATE `settings` SET `value`=\'N\' WHERE `setting`=\'open\'');
	$db -> Execute('UPDATE `settings` SET `value`=\'Wykonywanie resetu\' WHERE `setting`=\'close_reason\'');
	// Delete inkeeper's gossips about players.
	$db -> Execute('TRUNCATE TABLE `events`');
	// Grow herbs.
	$db -> Execute('UPDATE `farm` SET `age`=`age`+1');
	$db -> Execute('DELETE FROM `farm` WHERE `age`>26');
	// Produce weak potions in alchemist shop.
	$db -> Execute('UPDATE `potions` SET `amount`=`amount`+'.rand(1,50).' WHERE `owner`=0 AND `status`=\'S\'');
	/**
	 * Players' restoration - resurrection, hit points and mana regeneration (only Mage class) etc.
	 * 1) Add bonus to mana from items (rings with intelligence or willpower bonus and mage robes) and check duration of antidotes.
	 */
	$arrStats = $db -> GetAll('SELECT `id`, `klasa`, `inteli`+`wisdom`, `antidote_d`, `antidote_n`, `antidote_i`, `resurect` FROM `players`');
	for ($i = 0, $intMax = count($arrStats); $i < $intMax; ++$i)
	{
		if( $arrStats[$i][1] == 'Mag')
		{
			$arrRings = $db -> GetAll('SELECT `power`, `name` FROM `equipment` WHERE `owner`='.$arrStats[$i][0].' AND `type`=\'I\' AND `status`=\'E\'');
			for ($j = 0, $intMax2 = count($arrRings); $j < $intMax2; ++$j)
				if( strpos($arrRings[$j][1], R_INT) || strpos($arrRings[$j][1], R_WIS))
					$arrStats[$i][2] += $arrRings[$j][0];
			$arrCape = $db -> GetRow('SELECT `power` FROM `equipment` WHERE `owner`='.$arrStats[$i][0].' AND `type`=\'C\' AND `status`=\'E\'');
			if (!empty($arrCape))
				$arrStats[$i][2] *= 1 + $arrCape[0] / 100;
		}
		$db -> Execute('UPDATE `players` SET `pm`='.$arrStats[$i][2].', `antidote_d`='.(int)max(--$arrStats[$i][3], 0).',		`antidote_n`='.(int)max(--$arrStats[$i][4], 0).', `antidote_i`='.(int)max(--$arrStats[$i][5], 0).', `resurect`='.(int)max(--$arrStats[$i][6], 0).' WHERE `id`='.$arrStats[$i][0]);
	}
	unset($arrStats, $arrRings, $arrCape);

	/// 2) Give energy, health, crime points etc.
	$db -> Execute('UPDATE `players` SET `energy`=`energy`+`max_energy`, `hp`=`max_hp`, `bridge`=\'N\' WHERE `miejsce`!=\'Lochy\' AND `freeze`=0 AND `rasa`!=\'\' AND `klasa`!=\'\'');
	$db -> Execute('UPDATE `players` SET `hp`=`hp`+`blessval` WHERE `bless`=\'hp\'');
	
	$db -> Execute('UPDATE `players` SET `energy`=63*`max_energy` WHERE `energy` > 63 * `max_energy` AND `max_energy`!=0');
	$db -> Execute('UPDATE `players` SET `crime`=`crime`+1, `astralcrime`=\'Y\' WHERE `klasa`=\'Złodziej\' AND `freeze`=0');
	$db -> Execute('UPDATE `players` SET `pw`=`pw`+2 WHERE `deity`!=\'\'');
	// Various other actions.
	$db -> Execute('UPDATE `houses` SET `points`=`points`+2, `rest_owner`=0, `rest_locator`=0');
	$db -> Execute('UPDATE `tribes` SET `atak`=\'N\'');
	$db -> Execute('UPDATE `outposts` SET `turns`=`turns`+2, `fatigue`=100, `attacks`=0');
	$db -> Execute('UPDATE `outposts` SET `turns`=`turns`+1 WHERE `size`>49 AND `warriors`+`archers` > `size` / 10');
	$db -> Execute('UPDATE `outposts` SET `turns`=`turns`+1 WHERE `size`>99 AND `warriors`+`archers` > `size` / 4');
	$db -> Execute('UPDATE `outposts` SET `turns`=`turns`+1 WHERE `size`>149 AND `warriors`+`archers` > `size` / 2');

	// Jail - count duration and free prisoners.
	$db -> Execute('UPDATE `jail` SET `duration`=`duration`-1');
	$arrJail = $db -> GetAll('SELECT `prisoner` FROM `jail` WHERE `duration`=0');
	for ($i = 0, $intMax = count($arrJail); $i < $intMax; ++$i)
		$db -> Execute('UPDATE `players` SET `miejsce`=\'Altara\' WHERE `id`='.$arrJail[$i][0]);
	$db -> Execute('DELETE FROM `jail` WHERE `duration`=0');
	unset($arrJail);
	// Chat - decrement ban duration and unban after the specified time.
	$db -> Execute('UPDATE `chat_config` SET `resets`=`resets`-1');
	$db -> Execute('DELETE FROM `chat_config` WHERE `resets`=0');
	// Add rings in jeweller's shop.
	$arrRings = $db -> GetAll('SELECT `id`, `amount` FROM `rings` WHERE `amount`<28');
	for ($i = 0, $intMax = count($arrRings); $i < $intMax; ++$i)
		$db -> Execute('UPDATE `rings` SET `amount`='.min($arrRings[$i][1] + rand(1,4), 28).' WHERE `id`='.$arrRings[$i][0]);
	unset($arrRings);

	// Repay deposits in dwarven treasury.
	$db -> Execute('UPDATE `vault` SET `time`=`time`-1');
	$arrDeposit = $db -> GetAll('SELECT `id`, `owner`, `amount`, `type` FROM `vault` WHERE `time`=0');
	$strDate = $db -> DBDate(date("y-m-d H:i:s"));
	for ($i = 0, $intMax = count($arrDeposit); $i < $intMax; $i++)
	{
		$rand = rand(1,40);
		$strQuery = 'INSERT INTO `log` (`owner`, `log`, `czas`) VALUES('.$arrDeposit[$i][1].', \'Zakończył się termin lokaty jaką założono w Skarbcu Krasnoludów. ';
		if ($rand == 1)
		{
			$amount = ceil($arrDeposit[$i][2] / 2);
			$db -> Execute($strQuery.'Niestety! Inwestycje krasnali nie były trafione - tracisz połowę swojego wkładu! Na twoje konto w banku przekazano: '.$amount.' złota.\', '.$strDate.')');
		}
		elseif (($rand >= 2) && ($rand <= 4))
		{
			$amount = $arrDeposit[$i][2];
			$db -> Execute($strQuery.'Niestety! Inwestycje krasnali nie były trafione - nie zarobiłeś ani sztuki złota! Na twoje konto w banku przekazano: '.$amount.' złota.\', '.$strDate.')');
		}
		else
		{
			$amount = ceil($arrDeposit[$i][2] * (1 + $arrDeposit[$i][3] / 100));
			$db -> Execute($strQuery.'Inwestycje krasnali były bardzo dobre! Procent zysku wynosił tyle ile obiecali. Na twoje konto w banku przekazano: '.$amount.' złota.\', '.$strDate.')');
		}
		$db -> Execute('UPDATE `players` SET `bank`=`bank`+'.$amount.' WHERE `id`='.$arrDeposit[$i][1]);
	}
	$db -> Execute('DELETE FROM `vault` WHERE `time`=0');
	unset($arrDeposit);

	//cores
	$db -> Execute('UPDATE `coresplayers` SET `fights` = 0');
}

function stop()
{
	global $db;
	// Free database lock set in start().
	$db -> Execute('UPDATE `settings` SET `value`=\'Y\' WHERE `setting`=\'open\'');
	$db -> Execute('UPDATE `settings` SET `value`=\'\' WHERE `setting`=\'close_reason\'');
}

/**
* Main reset of game
**/
function mainreset()
{
	global $db;
	global $arrLanguage;

	start();
	/// Add player's age. Decrement account freezing count (timeshift must be greater than 180, which is limit for "active players, currently logged in").
	$db -> Execute('UPDATE `players` SET `age`=`age`+1, `houserest`=\'N\'');
	$db -> Execute('UPDATE `players` SET `freeze`=`freeze`-1, `lpv`='.(time() - 200).' WHERE `freeze`>0');

	/// Pay for troops in outposts.
	$arrCosts = $db -> GetAll('SELECT `id`, `warriors`, `catapults`, `barricades`, `archers`, `gold`, `bcost`, `size`, `owner` FROM `outposts`');
	$arrMultipliers = array(9,3,1,9);   // cost of one unit of troop type
	for ($i = 0, $intMax = count($arrCosts); $i < $intMax; ++$i)
	{
		$cost = 3 * (($arrCosts[$i][1] + $arrCosts[$i][4]) * 3 + $arrCosts[$i][2]) + $arrCosts[$i][3];
		$result = $db -> GetRow('SELECT count(*) FROM `outpost_monsters` WHERE `outpost`='.$arrCosts[$i][0]);
		$cost += $result[0] * 50;
		$result = $db -> GetRow('SELECT count(*) FROM `outpost_veterans` WHERE `outpost`='.$arrCosts[$i][0]);
		$cost += $result[0] * 50;
		$cost *= 1 - $arrCosts[$i][6] / 100;	// Cost reduction based on bonus skill.
		if ($arrCosts[$i][5] >= $cost)		  // There is enough gold to pay in treasury.
			$tax = $cost;
		else
		{
			$tax = $arrCosts[$i][5];	// Take all gold...
			$cost -= $arrCosts[$i][5];  // ...and use it to pay as much as possible.
			// Dismissal order: warriors - catapults - barricades - archers.
			// Reason: lefts something for defense till the very end. Soldiers must stay in outpost or it's size degrades.
			for ($j = 0;$j < 4; ++$j)
			{
				if ($cost <= $arrCosts[$i][$j+1] * $arrMultipliers[$j]) // If remaining cost can be "paid" by part of "this" army...
				{
					$arrCosts[$i][$j+1] -= $cost / $arrMultipliers[$j]; // Units that can't be paid - leave.
					$cost = 0;
					break;
				}
				else
				{
					$arrCosts[$i][1] = 0;		   // All units of this type leave.
					$cost -= $arrCosts[$i][1] * $arrMultipliers[$j];
				}
			}
			$db -> Execute('INSERT INTO `log` (`owner`, `log`, `czas`) VALUES('.$arrCosts[$i][8].', \'Twoja strażnica niszczeje, a nie opłacani żołnierze opuszczają Cię!\', '.$db -> DBDate(date("y-m-d H:i:s")).')');
		}
		$strQuery = 'UPDATE `outposts` SET `warriors`='.$arrCosts[$i][1].', `archers`='.$arrCosts[$i][4].', `catapults`='.$arrCosts[$i][2].',`barricades`='.$arrCosts[$i][3].', `gold`=`gold`-'.$tax;
		if ($arrCosts[$i][1] + $arrCosts[$i][4] < 1)	// No soldiers left.
			$strQuery .= ' ,`size`='.--$arrCosts[$i][7];
		if(!$arrCosts[$i][7])   // Size = 0.
		{
			$db -> Execute('DELETE FROM `outposts` WHERE `id`='.$arrCosts[$i][0]);
			$db -> Execute('DELETE FROM `outpost_monster` WHERE `outpost`='.$arrCosts[$i][0]);
			$db -> Execute('DELETE FROM `outpost_veterans` WHERE `outpost`='.$arrCosts[$i][0]);
		}
		else
			$db -> Execute($strQuery.' WHERE `id`='.$arrCosts[$i][0]);
	}
	unset($arrCosts);

	/// Lenght of poll.
	$arrPolls = $db -> GetAll('SELECT `id`, `days` FROM `polls` WHERE `votes`=-1 AND `days`>1');
	for ($i = 0, $intMax = count($arrPolls); $i < $intMax; ++$i)
	{
		if($arrPolls[$i][1] == 1)
		{
			$db -> Execute('UPDATE `polls` SET `members`=(SELECT count(*) FROM `players`) WHERE `id`='.$arrPolls[$i][0]);
			$db -> Execute('UPDATE `settings` SET `value`=\'N\' WHERE `setting`=\'poll\'');
		}
	}
	$db -> Execute('UPDATE `polls` SET `days`=`days`-1 WHERE `days`>1');
	unset($arrPolls);
/**
 * Warehouse actions.
 * 1) Every 10th day there is a chance for caravan that buys goods.
 */
	$arrItems = array('copperore', 'zincore', 'tinore', 'ironore', 'copper', 'bronze', 'brass', 'iron', 'steel', 'coal', 'adamantium', 'meteor', 'crystal', 'pine', 'hazel', 'yew', 'elm', 'mithril', 'illani', 'illanias', 'nutari', 'dynallca', 'illani_seeds', 'illanias_seeds', 'nutari_seeds', 'dynallca_seeds');
	$arrTest = $db -> GetRow('SELECT `reset` FROM `warehouse` WHERE `reset`=10 LIMIT 1');
	if (!empty($arrTest))
	{
		$arrCaravanday = $db -> GetRow('SELECT `value` FROM `settings` WHERE `setting`=\'day\'');
		$strCaravan = 'N';
		if ($arrCaravanday[0] % 10 == 0 && rand(1, 10) == 10)
		{
			foreach ($arrItems as $strItem)
			{
				$intPercent = rand(1, 100) / 100;
				$db -> Execute('UPDATE `warehouse` SET `buy`=`buy`+`amount`*'.$intPercent.', `amount`=`amount`*'.(1 - $intPercent).' WHERE `mineral`=\''.$strItem.'\' AND `reset`=1');
			}
			$strCaravan = 'Y';
		}
		$db -> Execute('UPDATE `settings` SET `value`=\''.$strCaravan.'\' WHERE `setting`=\'caravan\'');
/**
 * 2) Count prices in warehouse.
 * Variant 1: when there's full warehouse history available.
 */
		$arrPrice = array(0.14, 0.13, 0.12, 0.11, 0.1, 0.1, 0.09, 0.08, 0.07, 0.06);
		for ($i = 0; $i < 26; ++$i)
		{
			$arrMineral = $db -> GetAll('SELECT `cost`, `sell`, `buy`, `amount` FROM `warehouse` WHERE `mineral`=\''.$arrItems[$i].'\' ORDER BY `reset` ASC');
			$intTotal = 0;
			for ($j = 0; $j < 10; ++$j)
				$intTotal += $arrMineral[$j][1] + $arrMineral[$j][2];
			if (!$intTotal)
				$intTotal = 1;
			$intPrice = 0;
			for ($j = 0; $j < 10; ++$j)
				$intPrice += (($arrMineral[$j][2] - $arrMineral[$j][1]) / $intTotal + 1) * $arrMineral[$j][0] * $arrPrice[$j];
			// If "today" amount == 0...
		   // echo('<p>'.$arrItems[$i].'. Cena wyliczana wzorkiem: '.$intPrice.'<br />');
			if ($arrMineral[0][3] < 1)
			{// ...find out how long the warehouse is empty, and increment price a bit more.
				$arrTest = $db -> GetRow('SELECT `value` FROM `settings` WHERE `setting`=\''.$arrItems[$i].'\'');
				if ($arrTest[0] > 4)
					$intPrice = $arrMineral[0][0] + pow(2, floor($arrTest[0] / 5) - 1);
			   // echo('Zero od '.$arrTest[0].' dni, po dodaniu bonusu: '.$intPrice);
				$db -> Execute('UPDATE `settings` SET `value`=`value`+1 WHERE `setting`=\''.$arrItems[$i].'\'');
			}
			else
				$db -> Execute('UPDATE `settings` SET `value`=0 WHERE `setting`=\''.$arrItems[$i].'\'');
			$db -> Execute('DELETE FROM `warehouse` WHERE `mineral`=\''.$arrItems[$i].'\' AND `reset`=10');
			$db -> Execute('UPDATE `warehouse` SET `reset`=`reset`+1 WHERE `mineral`=\''.$arrItems[$i].'\'');
			$db -> Execute('INSERT INTO `warehouse` (`reset`, `mineral`, `cost`, `amount`) VALUES (1, \''.$arrItems[$i].'\', '.$intPrice.', '.$arrMineral[0][3].')');
		   // echo '</p>';
		}
	}
/// Variant 2: Not enough data. Add new reset copying the amount from old one (if it's day 1 of new era, use base values).
	else
	{
	$arrStartPrices = array(2,4,6,9,7,18,22,35,50,1,15,150,20,7,12,20,40,20,15,25,40,50,50,100,140,180);
	$arrStartAmount = array(50000,0,0,0,10000,0,0,0,0,100000,2000,500,2000,20000,0,0,0,10000,2000,2000,2000,2000,0,0,0,0);
	$strQuery = 'INSERT INTO `warehouse` (`reset`, `mineral`, `cost`, `amount`) VALUES (1, \'';
		for ($i = 0; $i < 26; ++$i)
		{
			$arrReset = $db -> GetRow('SELECT `amount`, `cost` FROM `warehouse` WHERE `mineral`=\''.$arrItems[$i].'\' AND `reset`=1');
			if (!empty($arrReset))
			{
				$db -> Execute('UPDATE `warehouse` SET `reset`=`reset`+1 WHERE `mineral`=\''.$arrItems[$i].'\'');
				$db -> Execute($strQuery.$arrItems[$i].'\', '.$arrReset[1].', '.$arrReset[0].')');
			}
			else
				$db -> Execute($strQuery.$arrItems[$i].'\', '.$arrStartPrices[$i].', '.$arrStartAmount[$i].')');
		}
	}
	/// Add game age.
	$db -> Execute('UPDATE `settings` SET `value`=`value`+1 WHERE `setting`=\'day\'');
	/// Count amount of minerals in mines.
	$db -> Execute('UPDATE `mines_search` SET `days`=`days`-1');
	$arrSearchmin = $db -> GetAll('SELECT `player`, `mineral`, `searchdays` FROM `mines_search` WHERE `days`=0');
	$arrMinerals = array('coal' => 0.75,
						 'copper' => 1,
						 'zinc' => 2,
						 'tin' => 3,
						 'iron' => 4);
	$arrMaxDeposit = array(500,1500,3000);  // max amount of ore based on day 1,2 or 3.
	for ($i = 0, $intMax = count($arrSearchmin); $i < $intMax; ++$i)
	{
		$strMineral = $arrSearchmin[$i][1];
		$intAmount = ceil(rand(1, 10) * $arrMaxDeposit[$arrSearchmin[$i][2] - 1] / $arrMinerals[$strMineral]);
		$arrTest = $db -> GetRow('SELECT `owner` FROM `mines` WHERE `owner`='.$arrSearchmin[$i][0]);
		if (!empty($arrTest))
			$db -> Execute('UPDATE `mines` SET `'.$strMineral."`=`".$strMineral."`+".$intAmount." WHERE `owner`=".$arrSearchmin[$i][0]);
		else
			$db -> Execute('INSERT INTO `mines` (`owner`,`'.$strMineral.'`) VALUES('.$arrSearchmin[$i][0].', '.$intAmount.')');
	}
	$db -> Execute('DELETE FROM `mines_search` WHERE `days`=0');
	unset($arrSearchmin, $arrMinerals, $arrMaxDeposit, $arrTest);
	/// Show new news (player's gossips).
	$db -> Execute('UPDATE `news` SET `show`=\'Y\' WHERE `show`=\'N\' AND `added`=\'Y\' ORDER BY `id` ASC LIMIT 1');
	/// Check construction of Astral Machine.
	$arrTest = $db -> GetRow('SELECT `value` FROM `settings` WHERE `setting`=\'tribe\'');
	if ($arrTest[0] == '')
	{
		$arrAstral = $db -> GetAll('SELECT `owner`, `used`, `directed` FROM `astral_machine` WHERE `aviable`=\'Y\'');
		$blnBreak = false;
		for ($i = 0, $intMax = count($arrAstral); $i < $intMax; ++$i)
		{
			if ($arrAstral[$i][1] + $arrAstral[$i][2] >= 20000)
			{
				$arrName = $db -> GetRow('SELECT `name` FROM `tribes` WHERE `id`='.$arrAstral[$i][0]);
				$time = date("y-m-d H:i:s");
				foreach ($arrLanguage as $strLanguage)  // Prepare the news.
				{
					require_once('languages/'.$strLanguage.'/resets.php');
					$strDate = $db -> DBDate($time);
					$db -> Execute('INSERT INTO `updates` (`starter`, `title`, `updates`, `lang`, `time`) VALUES(\'(Herold)\',\''.U_TITLE.'\',\''.U_TEXT.$gamename.U_TEXT2.$gamename.U_TEXT3.$time.U_TEXT4.$arrName[0].U_TEXT5.'\',\''.$strLanguage.'\', '.$strDate.')');
				}
				$db -> Execute('UPDATE `settings` SET `value`='.$arrAstral[$i][0].' WHERE `setting`=\'tribe\'');

				$arrComponents = array('C', 'O', 'T');  // Delete components used to build AM.
				$arrAmount = array(array(8, 8, 6, 6, 4, 4, 2),
								   array(10, 8, 6, 4, 2),
								   array(10, 8, 6, 4, 2));
				for ($j = 0, $k=0; $j < 3; ++$j, $k=0)
					foreach ($arrAmount[$i] as $intAmount)
					{
						$strName = $arrComponents[$j].$k;
						$arrAmount = $db -> GetRow('SELECT `amount` FROM `astral` WHERE `owner`='.$arrAstral[$i][0].' AND `type`=\''.$strName.'\' AND `number`=0 AND `location`=\'C\'');
						if ($arrAmount[0] == $intAmount)
							$db -> Execute('DELETE FROM `astral` WHERE `owner`='.$arrAstral[$i][0].' AND `type`=\''.$strName.'\' AND `number`=0 AND `location`=\'C\'');
						else
							$db -> Execute('UPDATE `astral` SET `amount`=`amount`-'.$intAmount.' WHERE `owner`='.$arrAstral[$i][0].' AND `type`=\''.$strName.'\' AND `number`=0 AND `location`=\'C\'');
						$k++;
					}
				$blnBreak = true;
			}
			$db -> Execute('UPDATE `astral_machine` SET `used`='.$arrAstral[$i][1] + $arrAstral[$i][2].', `directed`=0 WHERE `owner`='.$arrAstral[$i][0]);
			if ($blnBreak)
				break;
		}
	}
	unset($arrTest, $arrAstral);
	stop();
	// Clear selected caches.
	require_once 'libs/Smarty.class.php';
	$smarty = new Smarty;
	$smarty -> template_dir = './templates/';
	$smarty -> compile_dir = './templates_c/';
	$smarty -> cache_dir = './cache/';
	$smarty -> clear_cache('warehouse.tpl', 'Altara');
	$smarty -> clear_cache('warehouse.tpl', 'Ardulith');
	for ($i=0; $i < 26; ++$i)
		$smarty -> clear_cache('warehouse.tpl', 'h|'.$i);

	//cores
	$db -> Execute('UPDATE `coresplayers` SET `age` = `age` + 1');
	$db -> Execute('UPDATE `coresplayers` SET  `rest` = `rest` - 1 WHERE `rest` > 0');

}

/**
* Other reset in this same day.
*/
function smallreset()
{
	start();
	stop();
}
?>
