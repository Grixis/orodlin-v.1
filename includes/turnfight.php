<?php
/**
 *   File functions:
 *   Turn fight players vs monsters
 *
 *   @name                 : turnfight.php                            
 *   @copyright            : (C) 2004,2005,2006,2007 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @author               : Marek 'marq' Chodor <marek.chodor@gmail.com>
 *   @version              : 1.5
 *   @since                : 24.08.2007
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
// $Id: turnfight.php 951 2007-03-08 12:02:52Z thindil $

/**
* Get the localization for game
*/
require_once("languages/".$player -> lang."/turnfight.php");
require_once("includes/battle.php");

function savehptodb($hp)
{
	global $db;
	global $player;
	if ($hp < 0) $hp = 0;
	$db -> Execute('UPDATE `players` SET `hp`='.$hp.' WHERE `id`='.$player->id);
}

function savemanatodb($mana)
{
	global $db;
	global $player;
	if ($mana < 0) $mana = 0;
	$db -> Execute('UPDATE `players` SET `pm`='.$mana.' WHERE `id`='.$player->id);
}

/**
* Main turnfight function
*/
function turnfight($addres)
{
	global $player;
	global $smarty;
	global $db;
	global $title;

	$ucieczka = 0;

	$strMessage = '';

	if (!isset($_SESSION['gainmagic'])) $_SESSION['gainmagic'] = 0;
	if (!isset($_SESSION['gainshoot'])) $_SESSION['gainshoot'] = 0;
	if (!isset($_SESSION['gainattack'])) $_SESSION['gainattack'] = 0;
	if (!isset($_SESSION['gainmiss'])) $_SESSION['gainmiss'] = 0;

	$fight = $db -> Execute("SELECT fight FROM players WHERE id=".$player -> id);
	if ($fight -> fields['fight'] == 0 && $title = 'Arena Walk') {
		error (NO_ENEMY);
		}
	$fight -> Close();

//losowanie przeciwnika w górach/lesie
	if (!isset($_SESSION['amount'])) {
		$_SESSION['amount']=1;
		$location = $db -> Execute("SELECT miejsce FROM players WHERE id=".$player -> id);
		if ($location -> fields['miejsce'] == 'Góry') {
			$arrmonsters = array(2,3,6,7,16,17,22,23);
			}
		if ($location -> fields['miejsce'] == 'Las') {
			$arrmonsters = array(1,4,11,13,14,19,22,28);
			}
		if (!isset($arrmonsters)) {
			error(ERROR);
			}
		$location -> Close();
		$rzut = rand(0,7);
		$monster = new Monster($arrmonsters[$rzut],1);
		$attacker = new Fighter($player -> id);
		$_SESSION['mon0']['id'] = $monster -> id;
		$_SESSION['mon0']['hp'] = $monster -> hp;
		if ($attacker -> speed > $monster -> attackspeed) {
			$_SESSION['mon0']['ap'] = 1;
			$_SESSION['points'] = floor($attacker -> speed / $monster -> attackspeed);
			if ($_SESSION['points'] > 5) {
				$_SESSION['points'] = 5;
				}
			$actionArr[0] = -1;
			$actionArr[1] = 0;
			}
		else {
			$_SESSION['points'] = 1;
			$_SESSION['mon0']['ap'] = floor($monster -> speed / $attacker -> attackspeed);
			if ($_SESSION['mon0']['ap'] > 5) {
				$_SESSION['mon0']['ap'] = 5;
				}
			$actionArr[0] = 0;
			$actionArr[1] = -1;
			}
		$_SESSION['actionArr'] = $actionArr;
		$_SESSION['exhaust'] = 0;
		}

	require_once('includes/funkcje.php');
	require_once('includes/functions.php');

//zmiana kolczanu
	if (isset($_POST['action']) && $_POST['action'] == 'use' && $_SESSION['tpoints'] > 0) {
		$_SESSION['tpoints'] = $_SESSION['tpoints'] - 1;
		if (!isset($_POST['arrows'])) {
			$_POST['arrows'] = 0;
			}
		equip ($_POST['arrows']);
		}
//zmiana broni
	if (isset($_POST['action']) && $_POST['action'] == 'weapons' && $_SESSION['tpoints'] > 1) {
		$_SESSION['tpoints'] = $_SESSION['tpoints'] - 2;
		if (!isset($_POST['weapon'])) {
			$_POST['weapon'] = 0;
			}
		equip ($_POST['weapon']);
		}
//wypicie mikstury
	if (isset($_POST['action']) && $_POST['action'] == 'drink' && $_SESSION['tpoints'] > 0) {
		$_SESSION['tpoints'] = $_SESSION['tpoints'] - 1;
		drink ($_POST['potion2']);
		$objMana = $db -> Execute("SELECT `pm` FROM `players` WHERE `id`=".$player -> id);
		$player -> mana = $objMana -> fields['pm'];
		$objMana -> Close();
		}
//ucieczka
	if (isset($_POST['action']) && $_POST['action'] == 'escape') {
		for ($k=0;$k<=$_SESSION['amount'];$k++) {
			$t = $_SESSION['actionArr'][$k];
			if ($t != -1 && isset($_SESSION['mon'.$t]['hp']) && $_SESSION['mon'.$t]['hp'] > 0) break;
			}
		$monster = new Monster($_SESSION['mon'.$t]['id'],1,$t);
		$chance = (rand(1, $player -> level * 100) + $player -> speed - $monster -> hitmodificator);
		$intChance2 = rand(1, 10);
		if (($intChance2 == 1) || ($chance > 0)) {
			$expgain = ceil($monster -> exp / 100);
			if ($_SESSION['round'] == 0)
				$expgain = ceil($monster -> level /10);
			$smarty -> assign ("Message", ESCAPE_SUCC." ".$monster -> user." ".YOU_GAIN1." ".$expgain." ".EXP_PTS."<br /></li></ul>");
			$smarty -> display ('error1.tpl');
			checkexp($player -> exp,$expgain,$player -> level,$player -> race,$player -> user,$player -> id,0,0,$player -> id,'',0);
			$ucieczka = 1;
			}
		else {
			$smarty -> assign ("Message", "<br />".ESCAPE_FAIL." ".$monster -> user."!<br/>");
			$smarty -> display ('error1.tpl');
			$_SESSION['tpoints'] = 0;
			}
		}
//atak czarem
	if (isset($_POST['action']) && $_POST['action'] == 'cast' && $_SESSION['tpoints'] > 0) {
		$_SESSION['tpoints'] = $_SESSION['tpoints'] - 1;
		if (!isset($_POST['castspell'])) {
			$_POST['castspell'] = 0;
			}
		if (!isset($_POST['monster'])) {
			$_POST['monster'] = 0;
			}
		$defender = new Monster($_SESSION['mon'.($_POST['monster'])]['id'],1,$_POST['monster']);
		$defender -> hp = $_SESSION['mon'.($_POST['monster'])]['hp'];
		$attacker = new Fighter($player -> id);
		$attacker -> actionpoints -= $_SESSION['exhaust'];
		$attacker -> hp = $player -> hp;
		$attacker -> mana = $player -> mana;
		$objSpell = $db -> Execute('SELECT `obr`, `poziom` FROM `czary` WHERE `id`='.$_POST['castspell'].' AND `gracz`='.$player -> id);
		$attacker -> spell[0][0] = $objSpell -> fields['obr'];
		$attacker -> spell[0][1] = $objSpell -> fields['poziom'];
		$objSpell -> Close();
		$attacker -> setMagicDamage();
		if ($attacker -> damage == 0) $attacker -> setHandDamage();
		$strMessage = doattack($attacker,$defender);
		$smarty -> assign('Message',$strMessage);
		$smarty -> display('error1.tpl');
		$player -> hp = $attacker -> hp;
		$player -> mana = $attacker -> mana;
		$_SESSION['mon'.($_POST['monster'])]['hp'] = $defender -> hp;
		$_SESSION['gainmagic'] += $attacker -> gainmagic;
		}
//atak defensywny
	if (isset($_POST['action']) && $_POST['action'] == 'dattack' && $_SESSION['tpoints'] > 0) {
		$_SESSION['tpoints'] = $_SESSION['tpoints'] - 1;
		if (!isset($_POST['monster'])) {
			$_POST['monster'] = 0;
			}
		$defender = new Monster($_SESSION['mon'.($_POST['monster'])]['id'],1,$_POST['monster']);
		$defender -> hp = $_SESSION['mon'.($_POST['monster'])]['hp'];
		$attacker = new Fighter($player -> id);
		$attacker -> actionpoints -= $_SESSION['exhaust'];
		$attacker -> setWeaponDamage();
		if ($attacker -> damage == 0) $attacker -> setBowDamage();
		if ($attacker -> damage == 0) $attacker -> setHandDamage();
		$attacker -> damage -= $attacker -> damage * 0.15;
		$attacker -> mindamage -= $attacker -> mindamage * 0.15;
		$attacker -> maxdamage -= $attacker -> maxdamage * 0.15;
		$attacker -> hitmodificator -= $attacker -> hitmodificator * 0.1;
		$strMessage = doattack($attacker,$defender);
		$smarty -> assign('Message',$strMessage);
		$smarty -> display('error1.tpl');
		$strMessage = lostitem($attacker);
		$smarty -> assign('Message',$strMessage);
		$smarty -> display('error1.tpl');
		$_SESSION['mon'.($_POST['monster'])]['hp'] = $defender -> hp;
		$_SESSION['gainshoot'] += $attacker -> gainshoot;
		$_SESSION['gainattack'] += $attacker -> gainattack;
		$_SESSION['tattack']='d';
		}
//atak agresywny
	if (isset($_POST['action']) && $_POST['action'] == 'aattack' && $_SESSION['tpoints'] > 0) {
		$_SESSION['tpoints'] = $_SESSION['tpoints'] - 1;
		if (!isset($_POST['monster'])) {
			$_POST['monster'] = 0;
			}
		$defender = new Monster($_SESSION['mon'.($_POST['monster'])]['id'],1,$_POST['monster']);
		$defender -> hp = $_SESSION['mon'.($_POST['monster'])]['hp'];
		$attacker = new Fighter($player -> id);
		$attacker -> actionpoints -= $_SESSION['exhaust'];
		$attacker -> setWeaponDamage();
		if ($attacker -> damage == 0) $attacker -> setBowDamage();
		if ($attacker -> damage == 0) $attacker -> setHandDamage();
		$attacker -> damage += $attacker -> damage * 0.15;
		$attacker -> mindamage += $attacker -> mindamage * 0.15;
		$attacker -> maxdamage += $attacker -> maxdamage * 0.15;
		$attacker -> hitmodificator += $attacker -> hitmodificator * 0.1;
		$strMessage = doattack($attacker,$defender);
		$smarty -> assign('Message',$strMessage);
		$smarty -> display('error1.tpl');
		$strMessage = lostitem($attacker);
		$smarty -> assign('Message',$strMessage);
		$smarty -> display('error1.tpl');
		$_SESSION['mon'.($_POST['monster'])]['hp'] = $defender -> hp;
		$_SESSION['gainshoot'] += $attacker -> gainshoot;
		$_SESSION['gainattack'] += $attacker -> gainattack;
		$_SESSION['tattack']='a';
		}
//zwykly atak
	if (isset($_POST['action']) && $_POST['action'] == 'nattack' && $_SESSION['tpoints'] > 0) {
		$_SESSION['tpoints'] = $_SESSION['tpoints'] - 1;
		if (!isset($_POST['monster'])) {
			$_POST['monster'] = 0;
			}
		$defender = new Monster($_SESSION['mon'.($_POST['monster'])]['id'],1,$_POST['monster']);
		$defender -> hp = $_SESSION['mon'.($_POST['monster'])]['hp'];
		$attacker = new Fighter($player -> id);
		$attacker -> actionpoints -= $_SESSION['exhaust'];
		$attacker -> setWeaponDamage();
		if ($attacker -> damage == 0) $attacker -> setBowDamage();
		if ($attacker -> damage == 0) $attacker -> setHandDamage();
		$strMessage = doattack($attacker,$defender);
		$smarty -> assign('Message',$strMessage);
		$smarty -> display('error1.tpl');
		$strMessage = lostitem($attacker);
		$smarty -> assign('Message',$strMessage);
		$smarty -> display('error1.tpl');
		$_SESSION['mon'.($_POST['monster'])]['hp'] = $defender -> hp;
		$_SESSION['gainshoot'] += $attacker -> gainshoot;
		$_SESSION['gainattack'] += $attacker -> gainattack;
		unset($_SESSION['tattack']);
		}
//szal bojowy
	if (isset($_POST['action']) && $_POST['action'] == 'battack' && $_SESSION['tpoints'] > 1) {
		$_SESSION['tpoints'] = $_SESSION['tpoints'] - 2;
		if (!isset($_POST['monster'])) {
			$_POST['monster'] = 0;
			}
		$defender = new Monster($_SESSION['mon'.($_POST['monster'])]['id'],1,$_POST['monster']);
		$defender -> hp = $_SESSION['mon'.($_POST['monster'])]['hp'];
		$attacker = new Fighter($player -> id);
		$attacker -> actionpoints -= $_SESSION['exhaust'];
		$attacker -> setWeaponDamage();
		if ($attacker -> damage == 0) $attacker -> setBowDamage();
		if ($attacker -> damage == 0) $attacker -> setHandDamage();
		$attacker -> damage += $attacker -> damage;
		$attacker -> mindamage += $attacker -> mindamage;
		$attacker -> maxdamage += $attacker -> maxdamage;
		$strMessage = doattack($attacker,$defender);
		$smarty -> assign('Message',$strMessage);
		$smarty -> display('error1.tpl');
		$strMessage = lostitem($attacker);
		$smarty -> assign('Message',$strMessage);
		$smarty -> display('error1.tpl');
		$_SESSION['mon'.($_POST['monster'])]['hp'] = $defender -> hp;
		$_SESSION['gainshoot'] += $attacker -> gainshoot;
		$_SESSION['gainattack'] += $attacker -> gainattack;
		$_SESSION['tattack']='b';
		}
//odpoczynek
	if (isset($_POST['action']) && $_POST['action'] == 'rest' && $_SESSION['tpoints'] > 0) {
		$_SESSION['tpoints'] = 0;
		$smarty -> assign ("Message", "<br />".REST_SUCC."<br />");
		$smarty -> display ('error1.tpl');
		$attacker = new Fighter($player->id);
		$rest = ($attacker -> cond / 10);
		$_SESSION['exhaust'] -= $rest;
		if ($_SESSION['exhaust'] < 0) $_SESSION['exhaust'] = 0;
		}


//check if there are any ennemies left
	$temp = 0;
	for ($k=0;$k<$_SESSION['amount'];$k++) {
// 		$strIndex = "monhp".$k;
		if ($_SESSION['mon'.$k]['hp'] > 0) {
			$temp = $temp + 1;
			break;
			}
		}

//walka
	while (($_SESSION['round'] < 24 || $title == 'Portal') && $player -> hp > 0 && $temp > 0 && $ucieczka == 0) {
		if(!isset($_SESSION['tpoints'])) $_SESSION['tpoints'] = $_SESSION['points'];
		for ($k=0;$k<=$_SESSION['amount'];$k++) {
			$t = $_SESSION['actionArr'][$k];
			if(!isset($_SESSION['tmonap'.$t]) && isset($_SESSION['mon'.$t]['ap'])) $_SESSION['tmonap'.$t] = $_SESSION['mon'.$t]['ap'];
		//players move
			if ($t == -1 && $_SESSION['tpoints'] > 0 && $player -> hp > 0) {
				fightmenu($addres);
				savehptodb($player -> hp);
				savemanatodb($player -> mana);
				return;
				}
		//monsters move
			else if ($t != -1 && $_SESSION['tmonap'.$t] > 0 && $_SESSION['mon'.$t]['hp'] > 0 && (!isset($_SESSION['hp']) || $_SESSION['hp']>0) ) {
				$defender = new Fighter($player -> id);
				$defender -> actionpoints -= $_SESSION['exhaust'];
				$defender -> hp = $player -> hp;
				$defender -> mana = $player -> mana;
				$monster = new Monster($_SESSION['mon'.$t]['id'],1,$t);
				for ($i=0;$i<$_SESSION['tmonap'.$t];$i++) {
					if ($defender -> hp > 0) {
						if (isset($_SESSION['tattack'])) {
							switch ($_SESSION['tattack']) {
								case 'a':
									$defender -> defence -= $defender -> defence * 0.15;
									$defender -> missmodificator -= $defender -> missmodificator * 0.1;
									break;
								case 'd':
									$defender -> defence += $defender -> defence * 0.15;
									$defender -> missmodificator += $defender -> missmodificator * 0.1;
									break;
								case 'b':
									$defender -> defence = 0;
								}
							unset($_SESSION['tattack']);
							}
						$strMessage = doattack($monster,$defender);
						$smarty -> assign('Message',$strMessage);
						$smarty -> display('error1.tpl');
						if ($defender -> hp < 0) $defender -> hp = 0;
						$player -> hp = $defender -> hp;
						$player -> mana = $defender -> mana;
						$strMessage = lostitem($defender);
						if ($strMessage != '') {
							$smarty -> assign('Message',$strMessage);
							$smarty -> display('error1.tpl');
							}
						}
					}
				$_SESSION['gainmiss'] += $defender -> gainmiss;
				$_SESSION['tmonap'.$t] = 0;
				}
			}
		unset($_SESSION['tpoints']);
		for($i=0;$i<$_SESSION['amount'];$i++) {
			unset($_SESSION['tmonap'.$i]);
			}
		$_SESSION['round']++;
		}

//koniec walki

//check if there are any ennemies left
	$temp = 0;
	for ($k=0;$k<$_SESSION['amount'];$k++) {
		if ($_SESSION['mon'.$k]['hp'] > 0) {
			$temp = $temp + 1;
			break;
			}
		}

	$db -> Execute("UPDATE `players` SET `fight`=0 WHERE `id`=".$player -> id);
	$fighter = new Fighter($player -> id);
	$fighter -> hp = $player -> hp;
	$fighter -> mana = $player -> mana;

	$strMessage = YOU_FIGHT_WITH.'<ul>';
	for($k=0;$k<$_SESSION['amount'];$k++) {
		$strMessage .= '<li>'.($_SESSION['mon'.$k]['user']).'</li>';
		}
	$strMessage .= '</ul>';
	if ($fighter -> hp > 0 && $temp == 0) {
		$strMessage .= YOU_WIN.'<br/>';
		$_SESSION['result'] = 'win';
		}
	else if ($fighter -> hp <= 0) {
		$strMessage .= YOU_LOSE_WITH.'<br/>';
		}
	else {
		$strMessage .= YOU_DRAW.'<br/>';
		}
	$smarty -> assign ('Message', $strMessage);
	$smarty -> display ('error1.tpl');

	$fighter -> gainmiss = $_SESSION['gainmiss'];
	unset($_SESSION['gainmiss']);
	$fighter -> gainattack = $_SESSION['gainattack'];
	unset($_SESSION['gainattack']);
	$fighter -> gainshoot = $_SESSION['gainshoot'];
	unset($_SESSION['gainshoot']);
	$fighter -> gainmagic = $_SESSION['gainmagic'];
	unset($_SESSION['gainmagic']);

	$strMessage = '';
	$strMessage .= gainability($fighter);
	if ($strMessage != '') {
		$strMessage = '<br /><b>'.YOU_REWARD.':</b><br /> '.$strMessage;
		$smarty -> assign ('Message', $strMessage);
		$smarty -> display ('error1.tpl');
		}

	$span2 = ($_SESSION['amount'] + 37) / 38;

	for($k=0;$k<$_SESSION['amount'];$k++) {
		if ($fighter -> hp > 0 && $temp == 0) {
			$monster = new Monster($_SESSION['mon'.$k]['id'],1,$k);
			$fighter -> gaingold += ceil($monster -> credits);
			$span = ($monster -> level / $fighter -> level);
			if ($span > 2) {
				$span = 2;
				}
			$baseexp = ceil($span * $span2 * $monster -> exp);
			$fighter -> gainexp += $baseexp;
			}
		unset($_SESSION['mon'.$k]['id']);
		unset($_SESSION['mon'.$k]['hp']);
		unset($_SESSION['mon'.$k]['ap']);
		unset($_SESSION['mon'.$k]['user']);
		unset($_SESSION['mon'.$k]);
		unset($_SESSION['tmonap'.$k]);
		}

	$strMessage = '';
	if ($fighter -> hp > 0) {
		if ($temp == 0) $strMessage = '<br /><b>'.$fighter -> user.'</b> '.HE_GET.' <b>'.$fighter -> gainexp.'</b> '.EXPERIENCE.' '.ORAZ.' <b>'.$fighter -> gaingold.'</b> '.GOLD_COINS.'<br />';
	}
	else if ($title == 'Arena Walk') {
		$strMessage = '<br/>'.LOST_FIGHT_MON.'<br/>';
	}
	$strMessage .= lostring($attacker);
	$smarty -> assign ('Message', $strMessage);
	$smarty -> display ('error1.tpl');

	unset($_SESSION['amount']);
	unset($_SESSION['points']);
	unset($_SESSION['tpoints']);
	unset($_SESSION['round']);
	unset($_SESSION['exhaust']);

	if ($fighter -> gainexp > 0 && $ucieczka == 0) {
		require_once('includes/checkexp.php');
		checkexp ($fighter -> exp, $fighter -> gainexp, $fighter -> level, $fighter -> race, $fighter -> user, $fighter -> id, 0, 0, $fighter -> id,'',0);
		}

	if ($title !== 'Arena Walk' && $fighter -> hp == 0) {
		loststat($fighter);
		if ($fighter -> lost > 0) {
			$lostArray = array(STRENGTH,AGILITY,INTELIGENCE,CONDITION,SPEED,WISDOM);
			$strMessage = '<br/>'.YOU_LOST.' '.$fighter -> lost.' '.$lostArray[$fighter -> loststat].'<br/>';
			$smarty -> assign ('Message', $strMessage);
			$smarty -> display ('error1.tpl');
			}
		}

	$fighter -> unsetBless();
	$fighter -> savetodb(0);

	$FileName = explode('?', basename ($_SERVER['REQUEST_URI']));
	if ($FileName[0] != 'explore.php') {
		$smarty -> assign('Message','<a href="'.$FileName[0].'">'.BACK.'</a>');
		$smarty -> display('error1.tpl');
		}

}

/**
* Menu turn fight
*/
function fightmenu($addres)
{
    global $player;
    global $smarty;
    global $db;
    global $title;

    if ($title != 'Portal') {
        $smarty -> assign('Roundlimit',' / 24');
        }

	$attacker = new Fighter($player->id);

    $smarty -> assign(array("Round" => ($_SESSION['round']+1), 
                            "Points" => $_SESSION['tpoints'], 
                            "Mana" => $player -> mana, 
                            "HP" => $player -> hp, 
                            "Exhaust" => $_SESSION['exhaust'], 
                            "Cond" => $attacker -> cond, 
                            "Adres" => $addres,
                            "Fround" => F_ROUND,
                            "Actionpts" => ACTION_PTS,
                            "Lifepts" => LIFE_PTS,
                            "Manapts" => MANA_PTS,
                            "Exhausted" => EXHAUSTED,
                            "Quiver" => '',
                            "Arramount" => ''));


    if ($attacker -> equipment[6][0])
    {
        $smarty -> assign(array("Quiver" => QUIVER,
                                "Arramount" => $attacker -> equipment[6][6]));
    }
    if ($attacker -> equipment[0][0] || $attacker -> equipment[1][0]) 
    {
        if ($player -> clas != 'Rzemieślnik')
        {
            $smarty -> assign(array("Dattack" => "<input type=\"radio\" name=\"action\" value=\"dattack\"> ".D_ATTACK."<br /><br />",
                                    "Nattack" => "<input type=\"radio\" name=\"action\" value=\"nattack\" checked> ".N_ATTACK."<br /><br />",
                                    "Aattack" => "<input type=\"radio\" name=\"action\" value=\"aattack\"> ".A_ATTACK."<br /><br />"));
        }
            else
        {
            $smarty -> assign(array("Dattack" => '',
                                    "Nattack" => "<input type=\"radio\" name=\"action\" value=\"nattack\" checked> ".N_ATTACK."<br /><br />",
                                    "Aattack" => ''));
        }
    } 
        else 
    {
        $smarty -> assign(array("Dattack" => '', 
                                "Nattack" => "<input type=\"radio\" name=\"action\" value=\"nattack\" checked> ".N_ATTACK."<br /><br />",
//                                "Nattack" => '', 
                                "Aattack" => ''));
    }
    $smarty -> display ('turnfight.tpl');
    if ($_SESSION['amount'] > 0) 
    {
        $smarty -> assign ("Message", ATTACK_MONSTER.": <select name=\"monster\">");
        $smarty -> display ('error1.tpl');
        $strSelect = 'selected';
        for ($i=0;$i<$_SESSION['amount'];$i++) 
        {
//             $strIndex = "mon".$i;
//             $strIndexHP = "monhp".$i;
            if ($_SESSION['mon'.$i]['hp'] > 0) 
            {
		$monster = new Monster($_SESSION['mon'.$i]['id'],1,$i);
                $ename = $monster -> user;
                $smarty -> assign ("Message", "<option value=\"".$i."\" ".$strSelect.">".$ename." ".($i+1)." ".LIFE.": ".($_SESSION['mon'.$i]['hp'])."</option>");
                $strSelect = '';
                $smarty -> display ('error1.tpl');
            }
        }
        $smarty -> assign ("Message", "</select><br /><br />");
        $smarty -> display ('error1.tpl');
    }
    if ($player -> clas == 'Mag') 
    {
        $smarty -> assign ("Message", "<input type=\"radio\" name=\"action\" value=\"cast\"> ".SPELL_ATTACK." <select name=\"castspell\">");
        $smarty -> display ('error1.tpl');
        $arrspell = $db -> Execute("SELECT * FROM czary WHERE gracz=".$player -> id." AND typ='B'");
        while (!$arrspell -> EOF) 
        {
            $smarty -> assign ("Message", "<option value=".$arrspell -> fields['id'].">".$arrspell -> fields['nazwa']." ".POWER.": ".$arrspell -> fields['obr']."</option>");
            $smarty -> display ('error1.tpl');
            $arrspell -> MoveNext();
        }
        $smarty -> assign ("Message", "</select><br /><br />");
        $smarty -> display ('error1.tpl');
        $arrspell -> Close();
    }
    $arrpotion1 = $db -> Execute("SELECT * FROM potions WHERE owner=".$player -> id." AND status='K' AND  type!='P'");
    if (!empty($arrpotion1 -> fields['id'])) 
    {
        $smarty -> assign ("Message", "<input type=\"radio\" name=\"action\" value=\"drink\"> ".DRINK_POTION." <select name=\"potion2\">");
        $smarty -> display ('error1.tpl');
        while (!$arrpotion1 -> EOF) 
        {
            $smarty -> assign ("Message", "<option value=".$arrpotion1 -> fields['id'].">".$arrpotion1 -> fields['name']." ".POWER.": ".$arrpotion1 -> fields['power']." ".AMOUNT.": ".$arrpotion1 -> fields['amount']."</option>");
            $smarty -> display ('error1.tpl');
            $arrpotion1 -> MoveNext();
        }
        $smarty -> assign ("Message", "</select><br /><br />");
        $smarty -> display ('error1.tpl');
    }
    $arrpotion1 -> Close();
    if ($attacker -> equipment[1][0]) 
    {
        $arrarrows = $db -> Execute("SELECT * FROM equipment WHERE owner=".$player -> id." AND type='R' AND status='U'");
        $intTest = $arrarrows -> RecordCount();
        if ($intTest)
        {
            $smarty -> assign ("Message", "<input type=\"radio\" name=\"action\" value=\"use\"> ".NEW_QUIVER." <select name=\"arrows\">");
            $smarty -> display ('error1.tpl');
            while (!$arrarrows -> EOF) 
            {
                $smarty -> assign ("Message", "<option value=".$arrarrows -> fields['id'].">".$arrarrows -> fields['name']." ".POWER2.": ".$arrarrows -> fields['power']." ".AMOUNT.": ".$arrarrows -> fields['wt']."</option>");
                $smarty -> display ('error1.tpl');
                $arrarrows -> MoveNext();
            }
            $smarty -> assign ("Message", "</select><br /><br />");
            $smarty -> display ('error1.tpl');
        }
        $arrarrows -> Close();
    }
    if ($_SESSION['tpoints'] > 1) 
    {
        $arrwep = $db -> Execute("SELECT * FROM equipment WHERE owner=".$player -> id." AND type='W' AND status='U'");
        $smarty -> assign ("Message", "<input type=\"radio\" name=\"action\" value=\"weapons\"> ".CHANGE_WEAPON." <select name=\"weapon\">");
        $smarty -> display ('error1.tpl');
        while (!$arrwep -> EOF) 
        {
            $smarty -> assign ("Message", "<option value=".$arrwep -> fields['id'].">".$arrwep -> fields['name']." ".POWER2.": ".$arrwep -> fields['power']."</option>");
            $smarty -> display ('error1.tpl');
            $arrwep -> MoveNext();
        }
        $arrwep -> Close();
        $arrwep1 = $db -> Execute("SELECT * FROM equipment WHERE owner=".$player -> id." AND type='B' AND status='U'");
        while (!$arrwep1 -> EOF) 
        {
            $smarty -> assign ("Message", "<option value=".$arrwep1 -> fields['id'].">".$arrwep1 -> fields['name']." ".POWER2.": ".$arrwep1 -> fields['power']."</option>");
            $smarty -> display ('error1.tpl');
            $arrwep1 -> MoveNext();
        }
        $arrwep1 -> Close();
        $smarty -> assign ("Message", "</select><br /><br />");
        $smarty -> display ('error1.tpl');
        if (($player -> clas == 'Wojownik' || $player -> clas == 'Barbarzyńca') && $attacker -> equipment[0][0]) 
        {
            $smarty -> assign ("Message", "<input type=\"radio\" name=\"action\" value=\"battack\"> ".BATTLE_RAGE."<br /><br />");
            $smarty -> display ('error1.tpl');
        }
//doladowanie czaru 
        /*if ($player -> clas == 'Mag') 
        {
            $arrspell = $db -> Execute("SELECT * FROM czary WHERE gracz=".$player -> id." AND typ='B'");
            $smarty -> assign ("Message", "<input type=\"radio\" name=\"action\" value=\"bspell\"> ".SPELL_BURST." ".$player -> level." ".POWER3.")<select name=\"bspellboost\">");
            $smarty -> display ('error1.tpl');
            while (!$arrspell -> EOF) 
            {
                $smarty -> assign ("Message", "<option value=".$arrspell -> fields['id'].">".$arrspell -> fields['nazwa']." ".POWER.": ".$arrspell -> fields['obr']."</option>");
                $smarty -> display ('error1.tpl');
                $arrspell -> MoveNext();
            }
            $arrspell -> Close();
            $smarty -> assign ("Message", "</select> <input type=\"text\" name=\"power\" size=\"5\" value=\"0\"><br /><br /><input type=\"radio\" name=\"action\" value=\"dspell\"> ".SPELL_BURST2." ".$player -> level." ".POWER3.") <input type=\"text\" name=\"power1\" size=\"5\" value=\"0\"><br /><br />");
            $smarty -> display ('error1.tpl');
        }*/
    }
    $rest = ($attacker -> cond / 10);
    $smarty -> assign(array("Rest" => $rest, 
                            "Aescape" => A_ESCAPE,
                            "Arest" => A_REST,
                            "Aexhaust" => EXHAUST,
                            "Next" => S_FIGHT));
    $smarty -> display('turnfight1.tpl');
}

?>
