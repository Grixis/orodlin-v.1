<?php
/**
 *   File functions:
 *   Function to fight PvP
 *
 *   @name                 : battle.php
 *   @copyright            : (C) 2006,2007 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @author               : eyescream <tduda@users.sourceforge.net>
 *   @author               : Marek 'marq' Chodor <marek.chodor@gmail.com>
 *   @version              : 1.3
 *   @since                : 02.06.2007
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
// $Id: battle.php 905 2007-02-28 21:33:05Z thindil $

require_once("languages/".$player -> lang."/battle.php");

function doattack(&$attacker, &$defender)
{
	global $title;
	$strMessage = '';
	$eor = 0;
	$damage = $attacker -> damage;

	if ($attacker -> clas != 'Monster' && ($attacker -> noweapon || $attacker -> usebow)) {
		$damage += rand($attacker -> mindamage , $attacker -> maxdamage);
		}
	if ($attacker -> clas == 'Barbarzyńca' && $defender -> usedefspell) {
		$damage *= 1.5;
		}
	$damage += rand(1 , 10 * $attacker -> level);
//krytyk
	if ($attacker -> clas != 'Monster' && $title !='Portal') {
		$rzut = rand(1,1000)/10;
		if ($rzut < $attacker -> criticalhit && !$attacker -> noweapon) {
			$rzut = rand(1,100);
			if ($rzut < 60) {
				$damage += 2 * $attacker -> criticalstat;
				}
			else if ($rzut < 90) {
				$damage += 2 * $attacker -> criticalstat + $attacker -> damage;
				}
			else if ($rzut < 100) {
				$damage += $defender -> defence;
				}
			else {
			//one final shot
				if ($defender -> clas == 'Monster') {
					$defender -> hp -= $defender -> basehp;
					}
				else {
					$defender -> hp = 0;
					}
				if ($attacker -> usespell) {
					$strMessage .= '<b>'.$attacker -> user.'</b> '.P_ATTACK.' <b>'.$defender -> user.'</b> '.AND_KILL2.' ('.$defender -> hp.' '.HP_LEFT.')<br />';
					}
				else {
					$strMessage .= '<b>'.$attacker -> user.'</b> '.P_ATTACK.' <b>'.$defender -> user.'</b> '.AND_KILL.' ('.$defender -> hp.' '.HP_LEFT.')<br />';
					}
				if ($attacker -> usesword) {
				//gain attack
					$attacker -> gainattack += 0.01;
				}
				else if ($attacker -> usebow) {
				//gain shoot
					$attacker -> gainshoot += 0.01;
				}
				else if ($attacker -> usespell) {
				//gain magic
					$attacker -> gainmagic += 0.01;
				}
				$eor=1;
				}
			}
		}
//pech maga
	if (!$eor && $attacker -> usespell) {
		$rzut = rand(1,100);
		if ($rzut < $attacker -> misspell) {
			$rzut = rand(1,10);
			if ($rzut <= 70) {
				$attacker -> mana -= $attacker -> usespell;
				$strMessage .= '<b>'.$attacker -> user.'</b> '.YOU_MISS1.' <b>'.$attacker -> usespell.'</b> '.MANA.'.<br />';
				}
			else if ($rzut <= 90) {
				$attacker -> mana = 0;
				$strMessage .= '<b>'.$attacker -> user.'</b> '.YOU_MISS2.'.<br />';
				}
			else {
			//hit self
				$strMessage .= '<b>'.$attacker -> user.'</b> '.YOU_MISS3.' '.$attacker -> damage.' '.HP.'!<br />';
				$attacker -> hp -= $attacker -> damage;
				}
			$eor=1;
			}
		}
if (!$eor) {
	//trucizny
		if ($attacker -> poisontype == 'D' && (!isset($defender -> antidote_d) || $defender -> antidote_d == 0)) {
			$damage += $attacker -> poisonpower;
			}
		if ($attacker -> poisontype == 'I' && (!isset($defender -> antidote_i) || $defender -> antidote_i == 0)) {
			$damage += $attacker -> poisonpower;
			}
	//opponent defence
		if ($defender -> actionpoints > 0) {
			if ($defender -> clas == 'Barbarzyńca' && $attacker -> usespell) {
				$damage -= $defender -> defence * 1.5;
				}
			else {
				$damage -= $defender -> defence;
				}
			$damage -= rand(1 , 10 * $defender -> level);
			}
		$damage = (int) $damage;
		if ($damage < 1) {
			$damage = 1;
			}
		if ($defender -> clas == 'Monster' && $damage > $defender -> basehp) {
			$damage = $defender -> basehp;
			}
	//hit chance
		$chance = 100 * $attacker -> hitmodificator / ($attacker -> hitmodificator + $defender -> missmodificator);
		if ($chance < 10) {
			$chance = 10;
			}
		if ($chance > 90) {
			$chance = 90;
			}
		if ($defender -> actionpoints <= 0) {
			$chance = 90;
			}
		$rzut = rand(1,100);
		if ($rzut < $chance) {
		//trafienie
			//trutka z nutari
			if ($attacker -> poisontype == 'N' && (!isset($defender -> antidote_n) || $defender -> antidote_n == 0)) {
				$defender -> mana -= $attacker -> poisonpower;
				}
			$defender -> hp -= $damage;
			$strMessage .= '<b>'.$attacker -> user.'</b> '.P_ATTACK.' <b>'.$defender -> user.'</b> '.B_DAMAGE.' <b>'.$damage.'</b> '.DAMAGE.'! ('.$defender -> hp.' '.LEFT.')<br />';
		//lost mana by defender
			if ($defender -> usedefspell) {
				$defender -> mana -= ceil( ($defender -> usedefspell / 2) + ($defender -> magic / 50) );
				if ($defender -> mana <= 0) {
					if ($defender -> usespell) {
						$defender -> setDamage($defender -> equipment,$defender -> spell);
						}
					$defender -> setDefence($defender -> equipment,$defender -> spell);
					}
				}
		//lost sword
			if ($attacker -> usesword) {
			//gain attack
				if ($damage > 1) $attacker -> gainattack += 0.01;
				$attacker -> equipment[0][6]--;
				$attacker -> lostequip[0]++;
				if ($attacker -> equipment[0][6] <= 0) {
					$attacker -> setDamage($attacker -> equipment,$attacker -> spell);
					}
				//wytarcie trutki
				if ($attacker -> poisontype && rand(1,10) == 1) {
					$attacker -> poisontype = '';
					}
				}
			if ($attacker -> usebow) {
			//gain shoot
				if ($damage > 1) $attacker -> gainshoot += 0.01;
				}
			if ($attacker -> usespell) {
			//gain magic
				if ($damage > 1) $attacker -> gainmagic += 0.01;
				}
		//lost armors
			$rzut = rand(1,5);
			if ($rzut>1 && isset($defender -> equipment) && $defender -> equipment[$rzut][6] > 0) {
				$defender -> equipment[$rzut][6]--;
				$defender -> lostequip[$rzut]++;
				if ($defender -> equipment[$rzut][6] <= 0) {
					$defender -> setDefence($defender -> equipment,$defender -> spell);
					}
				}
			}
		else {
		//unik
			$strMessage .= '<b>'.$defender -> user.'</b> '.P_DODGE.' <b>'.$attacker -> user.'</b><br />';
		//gain miss
			if ($defender -> clas != 'Monster' && $defender -> actionpoints > 0) {
				$defender -> gainmiss++;
				}
		//zmecznie z uniku
			$defender -> actionpoints -= $defender -> misscost;
			if (isset($_SESSION['exhaust']) && $defender -> clas != 'Monster') {
				$_SESSION['exhaust'] += $defender -> misscost;
				}
		}
	}

//zmecznie z ataku
	$attacker -> actionpoints -= $attacker -> attcost;
	if (isset($_SESSION['exhaust']) && $attacker -> clas != 'Monster') {
		$_SESSION['exhaust'] += $attacker -> attcost;
		}
//lost mana
	if ($attacker -> usespell) {
		$attacker -> mana -= ceil( ($attacker -> usespell / 2) + ($attacker -> magic / 50) );
		if ($attacker -> mana <= 0) {
			$attacker -> setDamage($attacker -> equipment,$attacker -> spell);
			}
		}
//lost arrow
	if ($attacker -> usebow) {
		$attacker -> equipment[6][6]--;
		$attacker -> lostequip[6]++;
		$attacker -> equipment[1][6]--;
		$attacker -> lostequip[1]++;
		if ($attacker -> equipment[6][6] <= 0 || $attacker -> equipment[1][6] <= 0) {
			$attacker -> setDamage($attacker -> equipment,$attacker -> spell);
			}
		}
	return $strMessage;
}

/**
* Function for fight player vs player
* zwraca stringa z przebiegiem walki
*/
function dofight(&$attacker, &$defender)
{
	global $smarty;

	$strMessage = '';
	$round = 0;
	$repeat = floor($attacker -> attackspeed / $defender -> attackspeed);
	if ($repeat>5) {
		$repeat = 5;
		}
	
	while ($round<24 && $attacker -> hp > 0 && $defender -> hp > 0) {
//$attacker action
		$opponents = 1;
		if ($attacker -> clas == 'Monster') {
			$opponents = ceil($attacker -> hp / $attacker -> basehp);
			}
		if ($opponents < 1) {
			$opponents = 1;
			}
		for ($i=0; $i<($repeat*$opponents) && $attacker -> hp > 0 && $defender -> hp > 0 && $attacker -> actionpoints > 0; $i++) {
			$strMessage .= doattack($attacker, $defender);
			}


		$opponents = 1;
		if ($defender -> clas == 'Monster') {
			$opponents = ceil($defender -> hp / $defender -> basehp);
			}
		if ($opponents < 1) {
			$opponents = 1;
			}
//$defender action
		for ($i=0; $i<$opponents && $attacker -> hp > 0 && $defender -> hp > 0 && $defender -> actionpoints > 0; $i++) {
			$strMessage .= doattack($defender, $attacker);
			}
		$round++;
		}
	return $strMessage;
}

function pvpfight(&$attacker,&$defender)
{
	global $db;
	global $player;
	global $smarty;
	global $newdate;

	$strMessage = '';

//fight
	if ($defender -> attackspeed > $attacker -> attackspeed) {
		$strMessage .= dofight($defender, $attacker);
		}
	else {
		$strMessage .= dofight($attacker, $defender);
		}

	$mailMessage = '<br /><b>'.$attacker -> user.' '.VERSUS.' '.$defender -> user.'</b><br />'.$strMessage;

	$strMessage .= '<br /><b>'.YOU_FIGHT_WITH.' '.$defender -> user.' ';
	if ($attacker -> hp > 0 && $defender -> hp > 0) {
		$strMessage .= YOU_DRAW.'</b><br />';
		$mailMessage .= '<br /><b>'.L_BATTLE.'!</b>';
		}
	else if ($attacker -> hp > 0) {
		$strMessage .= YOU_WIN.'</b><br />';
		$mailMessage .= '<br /><b>'.$attacker -> user.' '.B_WIN.'!</b>';
		}
	else {
		$strMessage .= YOU_LOSE_WITH.'</b><br />';
		$mailMessage .= '<br /><b>'.$defender -> user.' '.B_WIN.'!</b>';
		}

	$smarty -> assign ('Message', $strMessage);
	$smarty -> display ('error1.tpl');
	$strMessage = '';

//count gained credits and experience and loststat

	$lostArray = array(STRENGTH,AGILITY,INTELIGENCE,CONDITION,SPEED,WISDOM);

	$strMap = '';

	if ($attacker -> hp > 0 && $defender -> hp <= 0) {
		loststat($defender);
		$ggold = $defender -> credits / 10;
		$ggold = (($ggold > 0)? $ggold: 0);
		$attacker -> gaingold = $ggold;
		$defender -> gaingold = - $ggold;
		$attacker -> gainexp = rand(5,10) * $defender -> level;
		if (rand (1,20) == 20 && $defender -> maps > 0) {
			$attacker -> maps++;
			$defender -> maps--;
			$strMap = AND_MAP;
			}
		$strMessage .= '<br /><b>'.$attacker -> user.'</b> '.HE_GET.' <b>'.$attacker -> gainexp.'</b> '.EXPERIENCE.' <b>'.$attacker -> gaingold.'</b> '.GOLD_COINS.' '.$strMap.'<br />';
		}
	else if ($attacker -> hp <= 0 && $defender -> hp > 0) {
		loststat($attacker);
		$ggold = $attacker -> credits / 10;
		$ggold = (($ggold > 0)? $ggold: 0);
		$defender -> gaingold = (int) $ggold;
		$attacker -> gaingold = - (int) $ggold;
		$defender -> gainexp = rand(5,10) * $attacker -> level;
		if (rand (1,20) == 20 && $attacker -> maps > 0) {
			$defender -> maps++;
			$attacker -> maps--;
			$strMap = AND_MAP;
			}
		$strMessage .= '<br /><b>'.$defender -> user.'</b> '.HE_GET.' <b>'.$defender -> gainexp.'</b> '.EXPERIENCE.' <b>'.$defender -> gaingold.'</b> '.GOLD_COINS.' '.$strMap.'<br />';
		if ($attacker -> lost > 0) {
			$strMessage .= YOU_LOST.' '.$attacker -> lost.' '.$lostArray[$attacker -> loststat].'<br/>';
			}
		}

//prepare for writing data to db
	$attacker -> hp = (($attacker -> hp >= 0)? $attacker -> hp : 0);
	$attacker -> mana = (($attacker -> mana >= 0)? $attacker -> mana : 0);
	$attacker -> unsetBless();
	$defender -> hp = (($defender -> hp >= 0)? $defender -> hp : 0);
	$defender -> mana = (($defender -> mana >= 0)? $defender -> mana : 0);
	$defender -> unsetBless();


	$strMessage .= '<br /><b>'.YOU_REWARD.':</b><br /> ';
	$strMessage .= gainability($attacker);
	gainability($defender);

	$strMessage .= lostitem($attacker);
	$strMessage .= lostring($attacker);
	lostitem($defender);
	lostring($defender);

	$smarty -> assign ('Message', $strMessage);
	$smarty -> display ('error1.tpl');

//herold
	$strDate = $db -> DBDate($newdate);
	if ($attacker -> battleloga) {
		$strSubject = T_SUBJECT.$defender -> user.T_SUB_ID.$defender -> id;
		$db -> Execute("INSERT INTO `mail` (`sender`, `senderid`, `owner`, `subject`, `body`, `date`) VALUES('".T_SENDER."','0',".$attacker -> id.",'".$strSubject."','".$mailMessage."', ".$strDate.")");
		}
	if ($defender -> battlelogd) {
		$strSubject = T_SUBJECT.$attacker -> user.T_SUB_ID.$attacker -> id;
		$db -> Execute("INSERT INTO `mail` (`sender`, `senderid`, `owner`, `subject`, `body`, `date`) VALUES('".T_SENDER."','0',".$defender -> id.",'".$strSubject."','".$mailMessage."', ".$strDate.")");
		}
//dziennik & fightlogs
	$query = $db -> Execute("SELECT `logs` FROM `fight_logs` WHERE `owner`=".$attacker -> id);
	$strLogAtt = $query -> fields['logs'];
	$preSqlAtt = (($strLogAtt == '') ? 'INSERT INTO `fight_logs` (`logs`,`owner`) VALUES (\'' : 'UPDATE `fight_logs` SET `logs`=\'');
	$midSqlAtt = (($strLogAtt == '') ? '\',' : '\' WHERE `owner`=');
	$postSqlAtt = (($strLogAtt == '') ? ');' : ';');
	$strLogAtt = (($strLogAtt == '') ? $strLogAtt : $strLogAtt.'\n');

	$query = $db -> Execute("SELECT `logs` FROM `fight_logs` WHERE `owner`=".$defender -> id);
	$strLogDef = $query -> fields['logs'];
	$preSqlDef = (($strLogDef == '') ? 'INSERT INTO `fight_logs` (`logs`,`owner`) VALUES (\'' : 'UPDATE `fight_logs` SET `logs`=\'');
	$midSqlDef = (($strLogDef == '') ? '\',' : '\' WHERE `owner`=');
	$postSqlDef = (($strLogDef == '') ? ');' : ';');
	$strLogDef = (($strLogDef == '') ? $strLogDef : $strLogDef.'\n');

	$strLogBase = date("y-m-d").'\t';
	if ($attacker -> hp > 0 && $defender -> hp > 0) {
		$attacktext = YOU_ATTACK_BUT.' '.L_BATTLE2.' <b><a href="view.php?view='.$defender -> id.'">'.$defender -> user.'</a></b>';
		$defendtext = YOU_ATTACKED_BUT.' '.L_BATTLE2.' <b><a href="view.php?view='.$attacker -> id.'">'.$attacker -> user.'</a></b> ';

        	$strLogAtt .= $strLogBase.YOU_ATTACKED_PLAYER.' <b><a href="view.php?view='.$defender -> id.'">'.$defender -> user.'</a></b> '.YOU_DRAW;
        	$strLogDef .= $strLogBase.'<b><a href="view.php?view='.$attacker -> id.'">'.$attacker -> user.'</b></a> '.YOU_ATTACKED_BY.' '.YOU_DRAW;
		}
	else if ($attacker -> hp > 0) {
		$attacktext = YOU_ATTACK_AND.' '.YOU_DEFEAT.' <b><a href="view.php?view='.$defender -> id.'">'.$defender -> user.'</a></b> '.YOU_REWARD.' <b>'.$attacker -> gainexp.'</b> '.EXPERIENCE.' '.ORAZ.' <b>'.$attacker -> gaingold.'</b> '.GOLD_COINS;
		$defendtext = YOU_ATTACKED.' '.YOU_LOSE.' <b><a href="view.php?view='.$attacker -> id.'">'.$attacker -> user.'</a></b> '.YOU_LOST.' <b>'.$attacker -> gaingold.'</b> '.GOLD_COINS;
		if ($defender -> lost > 0) {
			$defendtext .= ' '.ORAZ.' <b>'.$defender -> lost.'</b> '.$lostArray[$defender -> loststat];
			}
        	$strLogAtt .= $strLogBase.YOU_ATTACKED_PLAYER.' <b><a href="view.php?view='.$defender -> id.'">'.$defender -> user.'</a></b> '.YOU_WIN;
        	$strLogDef .= $strLogBase.'<b><a href="view.php?view='.$attacker -> id.'">'.$attacker -> user.'</b></a> '.YOU_ATTACKED_BY.' '.YOU_LOSE_WITH;
		}
	else {
		$attacktext = YOU_ATTACK_AND.' '.YOU_LOSE.' <b><a href="view.php?view='.$defender -> id.'">'.$defender -> user.'</a></b> '.YOU_LOST.' <b>'.$defender -> gaingold.'</b> '.GOLD_COINS;
		if ($attacker -> lost > 0) {
			$attacktext .= ' '.ORAZ.' <b>'.$attacker -> lost.'</b> '.$lostArray[$attacker -> loststat];
			}
		$defendtext = YOU_ATTACKED.' '.YOU_DEFEAT.' <b><a href="view.php?view='.$attacker -> id.'">'.$attacker -> user.'</a></b> '.YOU_REWARD.' <b>'.$defender -> gainexp.'</b> '.EXPERIENCE.' '.ORAZ.' <b>'.$defender -> gaingold.'</b> '.GOLD_COINS;

        	$strLogAtt .= $strLogBase.YOU_ATTACKED_PLAYER.' <b><a href="view.php?view='.$defender -> id.'">'.$defender -> user.'</a></b> '.YOU_LOSE_WITH;
        	$strLogDef .= $strLogBase.'<b><a href="view.php?view='.$attacker -> id.'">'.$attacker -> user.'</b></a> '.YOU_ATTACKED_BY.' '.YOU_WIN;
		}

	$db -> Execute($preSqlAtt.$strLogAtt.$midSqlAtt.$attacker -> id.$postSqlAtt);
	$db -> Execute($preSqlDef.$strLogDef.$midSqlDef.$defender -> id.$postSqlDef);

	$db -> Execute('INSERT INTO `log` (`owner`, `log`, `czas`) VALUES('.$attacker -> id.', \''.$attacktext.'\', '.$strDate.');');
	$db -> Execute('INSERT INTO `log` (`owner`, `log`, `czas`) VALUES('.$defender -> id.', \''.$defendtext.'\', '.$strDate.');');

	require_once('includes/checkexp.php');

	if ($attacker -> gainexp > 0) {
		checkexp ($attacker -> exp, $attacker -> gainexp, $attacker -> level, $attacker -> race, $attacker -> user, $attacker -> id, $defender -> id, $defender -> user, $attacker -> id,'',0);
		}
	else if ($defender -> gainexp > 0) {
		checkexp ($defender -> exp, $defender -> gainexp, $defender -> level, $defender -> race, $defender -> user, $defender -> id, $attacker -> id, $attacker -> user, $attacker -> id,'',0);
		}

	$attacker -> savetodb($defender);
	$defender -> savetodb($attacker);

	$smarty -> assign('Message','<a href="battle.php">'.BACK.'</a>');
	$smarty -> display('error1.tpl');
}

function pvmfastfight(&$attacker,&$monster,$times,$razy)
{
	global $db;
	global $player;
	global $smarty;
	global $title;

	$strMessage = '';

	for ($j=1; ($j<=$times && $attacker -> hp > 0); $j++) {
		if ($monster -> attackspeed > $attacker -> attackspeed) {
			$strMessage = dofight($monster,$attacker);
			}
		else {
			$strMessage = dofight($attacker,$monster);
			}
		if ($attacker -> hp <= 0) {
			$strMessage .= '<br/>'.LOST_FIGHT_MON.'<br/>';
			}

		if($times == 1) {
			$strMessage .= '<br /><b>'.YOU_FIGHT_WITH.' '.$razy.' '.$monster -> user.' ';
			}
		else {
			$strMessage = '<br /><b>'.YOU_FIGHT_WITH.' '.$razy.' '.$monster -> user.' ';
			}
		if ($attacker -> hp > 0 && $monster -> hp > 0) {
			$strMessage .= YOU_DRAW.'</b><br />';
			}
		else if ($attacker -> hp > 0) {
			$strMessage .= YOU_WIN.'</b><br />';
			}
		else {
			$strMessage .= YOU_LOSE_WITH.'</b><br />';
			}

//count gained credits and experience and loststat
		if ($attacker -> hp > 0 && $monster -> hp <= 0) {
			$attacker -> gaingold = ceil($monster -> credits * $razy);
			$span = ($monster -> level / $attacker -> level);
			$span2 = ($razy + 37) / 38;
			if ($span > 2) {
				$span = 2;
				}
			$baseexp = $span * $monster -> exp;
			$attacker -> gainexp = ceil($razy * $span2 * $baseexp);
			$strMessage .= '<br /><b>'.$attacker -> user.'</b> '.HE_GET.' <b>'.$attacker -> gainexp.'</b> '.EXPERIENCE.' '.ORAZ.' <b>'.$attacker -> gaingold.'</b> '.GOLD_COINS.'<br />';
			}

		$attacker -> unsetBless();

		$strMessage .= '<br /><b>'.YOU_REWARD.':</b><br /> ';
		$strMessage .= gainability($attacker);
		$strMessage .= lostring($attacker);
		$smarty -> assign ('Message', $strMessage);
		$smarty -> display ('error1.tpl');
		
		if ($attacker -> hp < 0) {
			$attacker -> hp = 0;
			}
		else {
			$attacker -> resetAll();
			$monster -> resetAll();
			}
		}
	$db -> Execute('UPDATE `players` SET `energy` = `energy` - '.(($j-1)*$razy).' WHERE `id` = '.$attacker -> id);
	if (($attacker -> gainexp + $attacker -> cumulateexp) > 0) {
		require_once('includes/checkexp.php');
		checkexp ($attacker -> exp, ($attacker -> gainexp + $attacker -> cumulateexp), $attacker -> level, $attacker -> race, $attacker -> user, $attacker -> id, 0, 0, $attacker -> id,'',0);
		}
	$strMessage = lostitem($attacker);
	$smarty -> assign ('Message', $strMessage);
	$smarty -> display ('error1.tpl');

	if ($title != 'Arena Walk') {
		$db -> Execute("UPDATE `players` SET `fight`=0 WHERE `id`=".$player -> id);
		if ($attacker -> hp <= 0) {
			$lostArray = array(STRENGTH,AGILITY,INTELIGENCE,CONDITION,SPEED,WISDOM);
			loststat($attacker);
			if ($attacker -> lost > 0) {
				$strMessage = '<br/>'.YOU_LOST.' '.$attacker -> lost.' '.$lostArray[$attacker -> loststat].'<br/>';
				$smarty -> assign ('Message', $strMessage);
				$smarty -> display ('error1.tpl');
				}
			}
		}

	$attacker -> savetodb(0);

	$FileName = explode('?', basename ($_SERVER['REQUEST_URI']));
	if ($FileName[0] != 'explore.php') {
		$smarty -> assign('Message','<a href="'.$FileName[0].'">'.BACK.'</a>');
		$smarty -> display('error1.tpl');
		}

}

?>
