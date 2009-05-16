<?php
/**
 *   File functions:
 *   Class to get data about outposts, fight, collect taxes, equip veterans etc.
 *
 *   @name				 : outpost_class.php
 *   @copyright			: (C) 2007 Orodlin Team based on Vallheru Engine 1.3
 *   @author			   : eyescream <tduda@users.sourceforge.net>
 *   @version			  : 0.1
 *   @since				: 15.06.2007
 *
 */

// Published under GNU GPL 2 or later. See /install/README file for details.
// $Id$

// All database SELECT's assume that we're working in numeric fetch mode, for example ADODB_FETCH_NUMERIC is set.
/// TODO: migration to php5: public/private, static
/// TODO: Remove "outpost" field from outpost_monsters and outpost_veterans. Use id of player/owner. Same with outpost's id.

function buy($intPlayerId, $strPlayerClass, $intCost, &$objDb)
{
	$arrClasses = array('Wojownik', 'Barbarzyńca', 'Złodziej', 'Mag', 'Rzemieślnik');
	$arrBonusNames = array('`battack`, `bdefense`', '`battack`', '`blost`', '`bcost`', '`btax`');
	$arrBonusValues = array( '3, 2', '5', '5', '5', '5');
	$intKey = array_search($strPlayerClass, $arrClasses);
	$objDb -> Execute('UPDATE `players` SET `credits`=`credits`-'.$intCost.' WHERE `id`='.$intPlayerId);
	$objDb -> Execute('INSERT INTO `outposts` (`owner`, '.$arrBonusNames[$intKey].') VALUES('.$intPlayerId.', '.$arrBonusValues[$intKey].')');
}

class Outpost
{
	var $id;
	var $ownerId;
	var $size;
	var $gold;
	var $morale;
	var $fatigue;
	var $turns;	 // Number of attack points, can be used to attack or get taxes.
	var $attacks;   // Up to 3 attacks on the outpost per reset.

	var $warriors;
	var $archers;
	var $catapults;
	var $barricades;
	var $lairs;	 // Number of lairs for beasts (Familiars from core.php)
	var $barracks;  // Number of barracks for veterans (troops with equipment).

	var $bonusAttack;   // Leadership bonuses.
	var $bonusDefense;
	var $bonusTax;
	var $bonusGold; // Pay less for keeping the troops.
	var $bonusLost; // Lose less troops in fights.

	var $db;	// reference to database object.

// Variables used only during fight. Should be private.
	var $arrMonsters;
	var $arrVeterans;
	var $attack;
	var $defense;
	var $enemyMoraleMod;	// Negative morale for enemy troops, based on outpost's monsters.
	var $lostWarriors = 0;
	var $lostArchers = 0;
	var $lostCatapults = 0;
	var $lostBarricades = 0;
	var $intGainGold = 0;
	var $fltGainLeadership = 0;
	var $arrDeadMon;		// ID's and names of dead monsters and veterans.
	var $arrDeadVet;

/**
* Class constructor - get data from database and write it to variables
*/
	 function Outpost($intId, & $objDb)
	{
		$this -> db = $objDb;
		$strQuery = 'SELECT `id`, `owner`, `size`, `gold`, `morale`, `fatigue`, `turns`, `attacks`, `warriors`, `archers`, `catapults`, `barricades`, `fence`, `barracks`, `battack`, `bdefense`, `btax`, `bcost`, `blost` FROM `outposts` WHERE `owner`='.$intId;
		$arrStats = $objDb -> GetRow($strQuery);
		if (!count($arrStats))
			return;
		$this -> id = $arrStats[0];
		$this -> ownerId = $intId;
		$this -> size = $arrStats[2];
		$this -> gold = $arrStats[3];
		$this -> morale = $arrStats[4];
		$this -> fatigue = $arrStats[5];
		$this -> turns = $arrStats[6];
		$this -> attacks = $arrStats[7];
		$this -> warriors = $arrStats[8];
		$this -> archers = $arrStats[9];
		$this -> catapults = $arrStats[10];
		$this -> barricades = $arrStats[11];
		$this -> lairs = $arrStats[12];
		$this -> barracks = $arrStats[13];
		$this -> bonusAttack = $arrStats[14];
		$this -> bonusDefense = $arrStats[15];
		$this -> bonusTax = $arrStats[16];
		$this -> bonusGold = $arrStats[17];
		$this -> bonusLost = $arrStats[18];
	}

	function goldBalance($intAmount, $intPlayerGold = 0)
	{
		if ($intAmount > 0)	// Deposit gold to outpost.
		{
			$intGold = $intAmount = min($intAmount, $intPlayerGold);
			$this -> gold += $intAmount;
			$deposit = true;
		}
		else	// Withdraw gold from outpost with 50% penalty.
		{
			$intAmount *= -1;
			$intAmount = min($intAmount, $this -> gold);
			$intGold = floor($intAmount / 2);
			$this -> gold -= $intAmount;
			$deposit = false;
		}
		$this -> db -> Execute('UPDATE `players` SET `credits`=`credits`'.($deposit ? '-' : '+').$intGold.' WHERE `id`='.$this -> ownerId);
		$this -> db -> Execute('UPDATE `outposts` SET `gold`='.$this -> gold.' WHERE `owner`='.$this -> ownerId);
		return $intGold;
	}

	 function &info()
	{
		$arrInfo = array();
		$arrInfo[] = $this -> size * 20 - $this -> warriors - $this -> archers; // No. of troops that can be recruited
		$arrInfo[] = $this -> size * 10 - $this -> catapults - $this -> barricades; // No. of machines...
		$arrInfo[] = floor($this -> size / 4) - $this -> barracks;  // No. of beast lairs that can be built.
		$arrInfo[] = floor($this -> size / 4) - $this -> lairs;	 // No. of veteran's barracks...

		$arrInfo[] = & $this -> getBeasts(true);
		$arrInfo[] = & $this -> getVeterans(true);
		$arrInfo[] = 100 - $this -> fatigue;

		// Daily costs.
		$arrInfo[] = 3 * (3 * ($this -> warriors + $this -> archers) + $this -> catapults) + $this -> barricades + ($arrInfo[4][0] + $arrInfo[5][0]) * 50;
		$arrInfo[7] = round($arrInfo[7] *( 1 - $this -> bonusGold / 100));
		return $arrInfo;
	}

	function &getBeasts($blnCompact = false) // outpost's monsters. If "true", gets only general data (for "outposts.php?view=my").
	{
		if ($blnCompact)
		{
			$arrResult = $this -> db -> GetRow('SELECT count(*), SUM(`power`), SUM(`defense`), SUM(`moralemod`) FROM `outpost_monsters` WHERE `outpost`='.$this -> id);
			if (empty($arrResult))
				$arrResult = array(0,0,0,0);
			return $arrResult;
		}
		return $this -> db -> GetAll('SELECT `id`, `name`, `power`, `defense`, `moralemod` FROM `outpost_monsters` WHERE `outpost`='.$this -> id);
	}

	function addBeast($intId, &$arrMonsters, &$arrCores, &$arrNames)
	{
		if (count($arrMonsters) > $this -> lairs)
			return NO_LAIR;
		$arrTest = $this -> db -> GetRow('SELECT `id`, `base`, `petname`, `attack`/4+`speed`/10, `defence`/4+`speed`/10, `owner` FROM `coresplayers` WHERE `id`='.$intId);
		if ($arrTest[5] != $this -> ownerId)
			return NOT_YOUR;
		if (100 * $arrTest[1] > $this -> gold)
			return NO_MONEY;
		$strName = $arrNames[$arrTest[1]].' '.$arrTest[2];
		settype($arrTest[3], 'int');
		settype($arrTest[4], 'int');
		$this -> db -> Execute('INSERT INTO `outpost_monsters` (`outpost`, `name`, `power`, `defense`, `moralemod`) VALUES('.$this -> id.',\''.$strName.'\','.$arrTest[3].','.$arrTest[4].','.$arrTest[1].')');
		$this -> db -> Execute('DELETE FROM `coresplayers` WHERE `id`='.$intId);
		$this -> gold -= 100 * $arrTest[1];
		--$this -> lairs;
		$this -> writeData();
		// update arrays. Better to do it here than waste additional database queries.
		$arrMonsters[] = array($arrTest[0], $strName, $arrTest[3], $arrTest[4]);
		for ($i = 0, $max = count($arrCores); $i < $max; $i++)
			if ($arrCores[$i][0] == $intId)
			{
				array_splice($arrCores, $i, 1);
				break;
			}
		return YOU_ADD.$strName.WITH_P.$arrTest[3].AND_D.$arrTest[4].IT_COST1.($arrTest[1] * 100).IT_COST2.AND_MODIFIED_MORALE_BY.$arrTest[1].'.';
	}

	function &getFamiliars(&$arrNames) // normal player's familiars, to be added to outpost
	{
		$arrCores = $this -> db -> GetAll('SELECT `id`, `base`*100, `petname`, `attack`/4+`speed`/10, `defence`/4+`speed`/10 FROM `coresplayers` WHERE `owner`='.$this -> ownerId.' ORDER BY `base`');
		for ($i =0, $max = count($arrCores); $i < $max; ++$i)
			$arrCores[$i][] = $arrNames[$arrCores[$i][1]/100];
		return $arrCores;
	}

	function &getVeterans($blnCompact = false)   //If "true", gets only general data (for "outposts.php?view=my").
	{
		if ($blnCompact)
		{
			$arrResult = $this -> db -> GetRow('SELECT count(*), SUM(`wpower` + `bpower` + `r1power` +`r2power`), SUM(`apower` + `hpower` + `lpower` + `spower` + `r1power` + `r2power`) FROM `outpost_veterans` WHERE `outpost`='.$this -> id);
			if (empty($arrResult))
				$arrResult = array(0,0,0);
			return $arrResult;
		}
		return $this -> db -> GetAll('SELECT `id`, `name`, (`wpower` + `bpower` + `r1power` +`r2power`), (`apower` + `hpower` + `lpower` + `spower` + `r1power` + `r2power`) FROM `outpost_veterans` WHERE `outpost`='.$this -> id);
	}

	function &getVeteran($intId)   //Gets detailed info about one veteran.
	{
		$arrResult = $this -> db -> GetRow('SELECT `id`, `name`, `weapon`, `wpower`, `bow`, `bpower`, `armor`, `apower`, `shield`, `spower`, `helm`, `hpower`, `legs`, `lpower`, `ring1`, `r1power`, `ring2`, `r2power` FROM `outpost_veterans` WHERE `id`='.$intId.' AND `outpost`='.$this -> id);
		if(empty($arrResult))
			error(VETERAN_ERROR);
		return $arrResult;
	}

	 function getTaxes($intTimes)
	{
		$intMax = 5 + round(log($this -> size, 5));
		$intTimes = min($intTimes, $this -> turns);
		for ($i = 0, $intGaingold = 0; $i < $intTimes; ++$i)
			$intGaingold += rand(2,$intMax);

		$intGaingold *= ($this -> warriors + $this -> archers) * (1 + $this -> bonusTax / 100);
		$this -> gold += round($intGaingold);
		$this -> fatigue = min($this -> fatigue + 10 * $intTimes, 100); ///TODO: ??
		$this -> db -> Execute('UPDATE `outposts` SET `fatigue`='.$this -> fatigue.', `gold`='.$this -> gold.', `turns`='.($this -> turns -= $intTimes).'  WHERE `id`='.$this -> id);
		return $intGaingold;
	}

	function &getEquipment($charType)
	{
		return $this -> db -> GetAll('SELECT `id`, `name`, '.($charType == 'B' ? '`szyb`' : '`power`').' FROM `equipment` WHERE `owner`='.$this -> ownerId.' AND `type`=\''.$charType.'\' AND `status`=\'U\'');
	}

	function stats()
	{
		static $intRound = 0;
		if (!$intRound) // Get info from database only in first round.
		{
			$this -> arrMonsters = $this -> getBeasts();
			$this -> arrVeterans = $this -> getVeterans();
		}
		// Count base values.
		$this -> attack = $this -> archers + ($this -> warriors + $this -> catapults) * 3;
		$this -> defense = $this -> warriors + ($this -> archers + $this -> barricades) * 3;
		// Add power and defense of beasts and veterans.
		// Most effective way would be to SUM() in SQL, but we need to know ID's and names for losing purposes.
		for($i = 0, $intMax = count($this -> arrMonsters); $i < $intMax; $i++)
		{
			$this -> attack += $this -> arrMonsters[$i][2];
			$this -> defense += $this -> arrMonsters[$i][3];
			$this -> enemyMoraleMod += $this -> arrMonsters[$i][4];
		}
		for($i = 0, $intMax = count($this -> arrVeterans); $i < $intMax; $i++)
		{
			$this -> attack += $this -> arrVeterans[$i][2];
			$this -> defense += $this -> arrVeterans[$i][3];
		}
		// Add random bonus and skill modifier.
		$bonus = rand(-5,5);
		$this -> attack *= 1 + ($bonus + $this -> bonusAttack) / 100;
		$this -> defense *= 1 + ($bonus + $this -> bonusDefense) / 100;
		++$intRound;
	}

	function attack(&$objEnemyOut)
	{
		// Logarithm of the sum of armies size.
		$fltLog = max(log($this -> warriors + $this -> archers + $this -> catapults + count($this -> arrMonsters) + count($this -> arrVeterans) + $objEnemyOut -> warriors + $objEnemyOut -> archers + $objEnemyOut -> catapults + $objEnemyOut -> barricades + count($objEnemyOut -> arrMonsters) + count($objEnemyOut -> arrVeterans)),0);
		// Divider used in few equations.
		$fltDivider = max($this -> attack + $objEnemyOut -> defense,1);

		// Attacker losses (per cent): 1,5 plus value based on armies.
		$fltX = $objEnemyOut -> defense / $fltDivider * 1.5 * $fltLog;
		$fltAttackerLosses = rand($fltX, 2 * $fltX) + 1.5 - $this -> bonusLost;

		// Defender losses (per cent).
		$fltX = $this -> attack / $fltDivider * 1.5 * $fltLog;
		$fltDefenderLosses = rand($fltX, 2 * $fltX) - $objEnemyOut -> bonusLost;

		// Add morale modifiers to attack and defense.
		$this -> moraleModifier($objEnemyOut);
		$objEnemyOut -> moraleModifier($this);
		// Decide who won. Loser gets additional 5% losses.
		if($this -> attack > $objEnemyOut -> defense)
		{
			$fltDefenderLosses += 5;
			$winner = &$this;
			$loser = &$objEnemyOut;
		}
		else
		{
			$fltAttackerLosses += 5;
			$winner = &$objEnemyOut;
			$loser = &$this;
		}
		$fltDefenderLosses = max($fltDefenderLosses / 100, 0);
		$fltAttackerLosses = max($fltAttackerLosses / 100, 0);
		// Compute army losses, defender cannot lose more than attacker. This is to prevent having huge losses with big outpost against level 1 newbies (for example we both lose 5%, but for me it is 500 soldiers and for him - one man).
		$this -> lostWarriors = round($this -> warriors * $fltAttackerLosses);
		$this -> lostArchers = round($this -> archers * $fltAttackerLosses);
		$this -> lostCatapults = round($this -> catapults * $fltAttackerLosses);
		$objEnemyOut -> lostWarriors = round($objEnemyOut -> warriors * $fltDefenderLosses);
		$objEnemyOut -> lostArchers = round($objEnemyOut -> archers * $fltDefenderLosses);
		$objEnemyOut -> lostCatapults = round($objEnemyOut -> catapults * $fltDefenderLosses);
		$objEnemyOut -> lostBarricades = round($objEnemyOut -> barricades * $fltDefenderLosses);

		// Attack results.
		++$objEnemyOut -> attacks;
		--$this -> turns;
		$this -> fatigue -= ($this -> size < $objEnemyOut -> size) ? 10 : 15;
		if ($this -> fatigue < 20)
			$this -> fatigue = 20;
		// Lose/win gold, gain leadership ability and morale.
		$intGold = ($this -> lostWarriors + $this -> lostArchers + $this -> lostCatapults) * 10 + ($objEnemyOut -> lostWarriors + $objEnemyOut -> lostArchers + $objEnemyOut -> lostCatapults + $objEnemyOut -> lostBarricades) * 15;
//dow= (1-abs(atak-obrona)/(atak+obrona))*(ln[(obroncy+atakujacy)/5]+(lv_ob+lv_at)/50)*1/75
		$fltGainLeadership = max((1 - abs(($this -> attack - $objEnemyOut -> defense) / $fltDivider)) * ($fltLog / 5 + ($this -> size + $objEnemyOut -> size) / 50) / 75, 0.01);
		$loser -> intGaingold += -round($loser -> gold / 10);
		$winner -> intGaingold += +round($loser -> gold / 10 + $intGold);
		$loser -> gold *= 0.9;
		$winner -> gold += $winner -> intGaingold;
		$winner -> morale += 1.5;
		--$loser -> morale;
		$winner -> fltGainLeadership += $fltGainLeadership;
		$loser -> fltGainLeadership += max($fltGainLeadership / 3, 0.01);

		// Apply army losses.
		$this -> warriors = max($this -> warriors - $this -> lostWarriors, 0);
		$this -> archers = max($this -> archers - $this ->lostArchers, 0);
		$this -> catapults = max($this -> catapults - $this ->lostCatapults, 0);
		$objEnemyOut -> warriors = max($objEnemyOut -> warriors - $objEnemyOut -> lostWarriors, 0);
		$objEnemyOut -> archers = max($objEnemyOut -> archers - $objEnemyOut -> lostArchers, 0);
		$objEnemyOut -> catapults = max($objEnemyOut -> catapults - $objEnemyOut -> lostCatapults, 0);
		$objEnemyOut -> barricades = max($objEnemyOut -> barricades - $objEnemyOut -> lostBarricades, 0);
	}

	function moraleModifier(&$objEnemy)
	{
		// +10 to my morale for every veteran, -"moralemod" to enemy morale from my beasts.
		$fltMorale = $this -> morale + 10 * count($this -> arrVeterans) - $objEnemy -> enemyMoraleMod;
		if ($fltMorale > 49)
			$intBonus = min(floor($fltMorale / 15) + 1, 80);
		elseif ($fltMorale < -49)
			$intBonus = max(floor($fltMorale / 15) - 1, -75);
		if (isset($intBonus))
		{
			$this -> attack *= 1 + $intBonus / 100;
			$this -> defense *= 1 + $intBonus / 100;
		}
		setType($this -> attack, 'int');
		setType($this -> defense, 'int');
	}

	function writeData($blnExtended = false) // if true, apply also leadership bonus
	{
		$this -> db -> Execute('UPDATE `outposts` SET `size`='.$this -> size.', `warriors`='.$this -> warriors.', `archers`='.$this -> archers.', `catapults`='.$this -> catapults.',`barricades`='.$this -> barricades.', `fatigue`='.$this -> fatigue.', `gold`='.$this -> gold.', `morale`='.$this -> morale.', `attacks`='.$this -> attacks.', `turns`='.$this -> turns.', `fence`='.$this -> lairs.', `barracks`='.$this -> barracks.' WHERE `id`='.$this -> id);
		if($blnExtended)
			$this -> db -> Execute('UPDATE `players` set `leadership`=`leadership`+'.$this -> fltGainLeadership.' WHERE `id`='.$this -> ownerId);
	}

	function lostspecials() // no more than 3 monsters and 3 veterans in one battle
	{
		for ($i = 0, $intBreak = 0, $this -> arrDeadMon = array(), $intMax = count($this -> arrMonsters); $i < $intMax; $i++)
		{
			if (rand(1,1000) == 1)
			{
				++$intBreak;
				$this -> arrDeadMon[] = $this -> arrMonsters[$i][1];	// Remember monster's name.
				$this -> enemyMoraleMod -= $this -> arrMonsters[$i][4]; // Not scary anymore.
				$this -> db -> Execute('DELETE FROM `outpost_monsters` WHERE `id`='.$this -> arrMonsters[$i][1]);
				// Remove this row from $arrMonsters without need to SELECT data again from database.
				array_splice($this -> arrMonsters, $i--, 1);
				--$intMax;  // $i has to be rewinded to previous position and number of array items must be updated.
			}
			if( $intBreak == 3)
				break;
		}
		for ($i = 0, $intBreak = 0, $this -> arrDeadVet = array(), $intMax = count($this -> arrVeterans); $i < $intMax; $i++)
		{
			if (rand(1,1000) == 1)
			{
				++$intBreak;
				$this -> arrDeadVet[] = $this -> arrVeterans[$i][1];
				$this -> db -> Execute('DELETE FROM `outpost_veterans` WHERE `id`='.$this -> arrVeterans[$i][1]);
				array_splice($this -> arrVeterans, $i--, 1);
				--$intMax;
			}
			if( $intBreak == 3)
				break;
		}
	}

	function battlereport($blnDefender = true)  // Return info about lost armies as <ul><li>... html. If "true", add also amount of lost barricades.
	{
		$strResult = '<ul><li>'.$this -> lostWarriors.' '.SOLDIERS.'</li><li>'.$this -> lostArchers.' '.ARCHERS.'</li><li>'.$this -> lostCatapults.' '.MACHINES.'</li>';
		if ($blnDefender)
			$strResult .= '<li>'.$this -> lostBarricades.' '.FORTS.'</li>';
		if (!empty($this -> arrDeadMon))
		{
			$strResult .= '<li>'.BEASTS.': '.implode($this -> arrDeadMon, ", ").'</li>';
			unset($this -> arrDeadMon);
		}
		if (!empty($this -> arrDeadVet))
		{
			$strResult .= '<li>'.VETERANS.': '.implode($this -> arrDeadVet, ", ").'</li>';
			unset($this -> arrDeadVet);
		}
		return $strResult.'</ul>';
	}

/**
* Function to check what player can build with his current resources.
* Possible improvements: increase outpost's size, add beast's lair, add veteran's barrack.
*
* $strImprovement - what should be checked ('size', 'barracks' or 'lairs')
* $intArg1 - first mineral required to improve outpost (size - platinum, lairs and barracks - meteor)
* $intArg2 - second mineral (size requires pine, lairs - crystal, barracks - adamantium)
* Function returns how many additional levels/lairs/barracks can be build.
*/
	function checkresources($strImprovement, $intArg1, $intArg2)
	{
		if ($strImprovement == 'size')
		{
// Compute how much gold, pine and platinum can be spent.
// Each level of outpost costs: (current_level + 2) * 250 gold, (current_level) pine and 10 platinum.
			$intDelta = 4 * $this -> size * ($this -> size + 3) + 9 + 4 * $this -> gold / 125;
			$intMaxGold = floor ((sqrt ($intDelta) - 3) / 2 - $this -> size);
			$intMaxPlatinum = floor ($intArg1 / 10);
			$intDelta = 4 * $this -> size * ($this -> size - 1) + 1 + 8 * $intArg2;
			$intMaxPine = floor ((sqrt ($intDelta) + 1 - 2 * $this -> size) / 2);
			return min($intMaxGold, $intMaxPlatinum, $intMaxPine);
		}
		if (($strImprovement != 'lairs') && ($strImprovement != 'barracks'))	// invalid argument
			return 0;
// Each lair costs: (no_of_lairs + 2) * 50 gold, (no_of_lairs + 1) * 5 crystal and (no_of_lairs + 1) meteor.
// Each barrack costs: (no_of_barracks + 2) * 50 gold, (no_of_barracks + 1) * 5 adamantium and (no_of_barracks + 1) meteor.
// Number of barracks + number of lairs must be less or equal to outpost's size / 4.
		$intDelta = 4 * $this -> $strImprovement * ($this -> $strImprovement + 3) + 9 + 4 * $this -> gold / 25;
		$intMaxGold = floor ((sqrt ($intDelta) - 3) / 2 - $this -> $strImprovement);
		$intDelta = 4 * $this -> $strImprovement * ($this -> $strImprovement + 1) + 1 + 8 * $intArg1;
		$intMaxMeteor = floor ((sqrt ($intDelta) + 1 - 2 * $this -> $strImprovement) / 2);
		$intMaxArg2 = min ($intMaxMeteor, $intArg2);
		$intMaxNumber = floor ( $this -> size / 4) - $this -> lairs - $this -> barracks;
		return min($intMaxGold, $intMaxMeteor, $intMaxArg2, $intMaxNumber);
	}
/**
* Function to upgrade outpost - arguments as in checkresources(). Additionally "amount" appears.
*/
	function upgrade($strImprovement, $intN, &$intArg1, &$intArg2)
	{
		if ($strImprovement == 'size')
		{
			$intNeededGold = 125 * $intN * (2 * $this -> size + $intN + 3);
			$intNeededPine = $intN * ($this -> size + ($intN - 1) / 2);
			$intNeededPlatinum = 10 * $intN;
			if (($intNeededGold > $this -> gold) || ($intNeededPine > $intArg2) || ($intNeededPlatinum > $intArg1))
				return NO_MONEY.' '.YOU_NEED.': '.$intNeededGold.' '.GOLD_COINS.', '.$intNeededPlatinum.' '.PLATINUM_PIECES.', '.$intNeededPine.' '.PINE_PIECES;
			$this -> size += $intN;
			$intArg1 -= $intNeededPlatinum;
			$this -> db -> Execute('UPDATE `players` SET `platinum`='.$intArg1.' WHERE `id`='.$this -> ownerId);
			$intArg2 -= $intNeededPine;
			$this -> db -> Execute('UPDATE `minerals` SET `pine`='.$intArg2.' WHERE `owner`='.$this -> ownerId);
			$strInfo = YOU_ADD.' <b>'.$this -> size.'</b> (+'.$intN.'). '.YOU_PAID.': '.$intNeededGold.' '.GOLD_COINS.', '.$intNeededPlatinum.' '.PLATINUM_PIECES.', '.$intNeededPine.' '.PINE_PIECES.'.';
		}
		else
		{
			if (floor($this -> size /4) - $this -> lairs - $this -> barracks < 1)
				return MAX_FENCES_AND_BARRACKS;
			$intNeededGold = 25 * $intN * (2 * $this -> $strImprovement + $intN + 3);
			$intNeededMeteor = $intN * (2 * $this -> $strImprovement + $intN + 1) / 2;
			if (($intNeededGold > $this -> gold) || ($intNeededMeteor > $intArg1) || ($intNeededMeteor * 5 > $intArg2))
				return NO_MONEY.' '.YOU_NEED.': '.$intNeededGold.' '.GOLD_COINS.', '.$intNeededMeteor.' '.METEOR_PIECES.', '.($intNeededMeteor * 5).' '.($strImprovement == 'lairs' ? CRYSTAL_PIECES : ADAMANTIUM_PIECES);
			if ($strImprovement == 'lairs')
			{
				$strBuilding = 'fence';
				$strMineral = 'crystal';
				$this -> lairs += $intN;
			}
			elseif ($strImprovement == 'barracks')
			{
				$strBuilding = 'barrack';
				$strMineral = 'adamantium';
				$this -> barracks += $intN;
			}
			else
				return '';
			$this -> db -> Execute('UPDATE `minerals` SET `meteor`=`meteor`-'.$intNeededMeteor.', `'.$strMineral.'`=`'.$strMineral.'`-'.($intNeededMeteor * 5).' WHERE `owner`='.$this -> ownerId);
			$strInfo = YOU_ADD.' <b>'.$this -> $strImprovement.'</b> (+'.$intN.'). '.YOU_PAID.': '.$intNeededGold.' '.GOLD_COINS.', '.$intNeededMeteor.' '.METEOR_PIECES.', '.$intNeededMeteor.' '.($strImprovement == 'lairs' ? CRYSTAL_PIECES : ADAMANTIUM_PIECES).'.';
		}
		$this -> gold -= $intNeededGold;
		$this -> writeData();
		return $strInfo.REFRESH;
	}

/**
* Function to buy outpost's army.
* Expected input array must contain values saying how much warriors,archers,barracks,catapults buy, on indices 0 to 3.
*/
	function buyArmy(&$arrInput)
	{
		$intSum = 0;
		$arrNames = array('warriors', 'archers', 'barricades', 'catapults');
		$maxtroops = $this -> size * 20 - $this -> warriors - $this -> archers;
		for($i = 0; $i < 2; ++$i)
		{
			$arrInput[$i] = min($arrInput[$i], $maxtroops, floor($this -> gold / 25));
			$maxtroops = max($maxtroops - $arrInput[$i], 0);
			$this -> $arrNames[$i] += $arrInput[$i];
			$this -> gold -= $arrInput[$i] * 25;
		}
		$maxequips = $this -> size * 10 - $this -> barricades - $this -> catapults;
		for($i = 2; $i < 4; ++$i)
		{
			$arrInput[$i] = min($arrInput[$i], $maxequips, floor($this -> gold / 35));
			$maxequips = max($maxequips - $arrInput[$i], 0);
			$this -> $arrNames[$i] += $arrInput[$i];
			$this -> gold -= $arrInput[$i] * 35;
		}
		$intSum = ($arrInput[0] + $arrInput[1]) * 25 + ($arrInput[2] + $arrInput[3]) * 35;
		$this -> writeData();
		return $intSum;
	}

	function addVeteran(&$arrVeterans)
	{
		if ($this -> gold < 1000)
			return NO_MONEY;
		if ($this -> barracks < count($arrVeterans) + 1)
			return YOU_DONT_HAVE.FREE_BARRACKS;
		// For error checks and database select.
		$arrKeys = array('W','B','A','S','H','L','I');
		$arrNames = array(WEAPONS,BOWS,ARMORS,SHIELDS,HELMETS,PLATE_LEGS,RINGS);

		// arrays determined by outpost_veterans table structure.
		$arrKeys2 = array('W','B','A','S','H','L','I1', 'I2');
		$arrSQLItemNames = array('weapon','bow', 'armor','shield', 'helm', 'legs', 'ring1', 'ring2');
		$arrSQLItemPowers = array('wpower','bpower', 'apower','spower', 'hpower', 'lpower', 'r1power', 'r2power');

		$strQueryNames = 'INSERT INTO `outpost_veterans`(`name`, `outpost`';
		inString($_POST['name']);
		$strQueryValues = ') VALUES('.$_POST['name'].', '.$this -> id;
		foreach ($_POST as $key => $value)
		{
			if ($key == 'name' || $key == 'vid')
				continue;
			$newKey = $key{0};			  // to get rid of rings: I1, I2
			if (!in_array($newKey, $arrKeys))
				return STRANGE_ITEM_TYPE.':"'.$newKey.'"';
			if (strictInt($value))
			{
				$arrTest =  $this -> db -> GetRow('SELECT `name`, '.($key == 'B' ? '`szyb`' : '`power`').', `amount` FROM `equipment` WHERE `id`='.$value.' AND `owner`='.$this -> ownerId.' AND `type`=\''.$newKey.'\' AND `status`=\'U\'');
				if(empty($arrTest))
					return $arrNames[array_search($newKey, $arrKeys)].' ('.$value.'): '.EQUIPMENT_ERROR;
				// Build next part of final query string.
				$i = array_search($key, $arrKeys2);
				$strQueryNames .= ',`'.$arrSQLItemNames[$i].'`, `'.$arrSQLItemPowers[$i].'`';
				inString($arrTest[0]);
				$strQueryValues .= ', '.$arrTest[0].', '.$arrTest[1];
				// If it's last piece - delete it.
				if ($arrTest[2] == 1)
					$this -> db -> Execute('DELETE FROM `equipment` WHERE `id`='.$value);
				else
					$this -> db -> Execute('UPDATE `equipment` SET `amount`=`amount`-1 WHERE `id`='.$value);
			}
		}
		$this -> db -> Execute($strQueryNames.$strQueryValues.');');
		$this -> gold -= 1000;
		$arrVeterans[] = $this -> db -> GetRow('SELECT `id`, `name`, (`wpower` + `bpower` + `r1power` +`r2power`), (`apower` + `hpower` + `lpower` + `spower` + `r1power` + `r2power`) FROM `outpost_veterans` WHERE `outpost`='.$this -> id.' ORDER BY `id` DESC LIMIT 1');
		$this -> writeData();
		return SUCCESS;
	}

	function modifyVeteran(&$arrVeterans)
	{
		$blnMy = false;
		for ($i = 0, $max = count($arrVeterans);$i < $max; $i++)
			if ($arrVeterans[$i][0] == $_POST['vid'])
			{
				$blnMy = true;
				break;
			}
		if (!$blnMy)
			return VETERAN_ERROR;
		// For error checks and database select.
		$arrKeys = array('W','B','A','S','H','L','I');
		$arrNames = array(WEAPONS,BOWS,ARMORS,SHIELDS,HELMETS,PLATE_LEGS,RINGS);

		// arrays determined by outpost_veterans table structure.
		$arrKeys2 = array('W','B','A','S','H','L','I1', 'I2');
		$arrSQLItemNames = array('weapon','bow', 'armor','shield', 'helm', 'legs', 'ring1', 'ring2');
		$arrSQLItemPowers = array('wpower','bpower', 'apower','spower', 'hpower', 'lpower', 'r1power', 'r2power');
		inString($_POST['name']);
		$strQuery = 'UPDATE `outpost_veterans` SET `name`='.$_POST['name'];

		foreach ($_POST as $key => $value)
		{
			strictInt($value);
			if ($key == 'name' || $key == 'vid' || $value == 2147483647)
				continue;
			$newKey = $key{0};			  // to get rid of rings: I1, I2
			if (!in_array($newKey, $arrKeys))
				return STRANGE_ITEM_TYPE.':"'.$newKey.'"';
			$i = array_search($key, $arrKeys2);
			if (!$value)	// deleting
				$strQuery .= ',`'.$arrSQLItemNames[$i].'`=\'\', `'.$arrSQLItemPowers[$i].'`=0';
			else
			{
				$arrTest =  $this -> db -> GetRow('SELECT `name`, '.($key == 'B' ? '`szyb`' : '`power`').', `amount` FROM `equipment` WHERE `id`='.$value.' AND `owner`='.$this -> ownerId.' AND `type`=\''.$newKey.'\' AND `status`=\'U\'');
				if(empty($arrTest))
					return $arrNames[array_search($newKey, $arrKeys)].' ('.$value.'): '.EQUIPMENT_ERROR;
				inString($arrTest[0]);
				$strQuery .= ',`'.$arrSQLItemNames[$i].'`='.$arrTest[0].', `'.$arrSQLItemPowers[$i].'`='.$arrTest[1];
				// If it's last piece - delete it.
				if ($arrTest[2] == 1)
					$this -> db -> Execute('DELETE FROM `equipment` WHERE `id`='.$value);
				else
					$this -> db -> Execute('UPDATE `equipment` SET `amount`=`amount`-1 WHERE `id`='.$value);
			}
		}
		$this -> db -> Execute($strQuery.' WHERE `id`='.$_POST['vid']);
		return SUCCESS;
	}
}