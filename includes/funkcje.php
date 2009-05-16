<?php
/**
 *   File functions:
 *   Functions to fight in PvP and fast fight PvM
 *
 *   @name                 : funkcje.php
 *   @copyright            : (C) 2004,2005,2006,2007 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
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
// $Id: funkcje.php 905 2007-02-28 21:33:05Z thindil $

require_once ('includes/checkexp.php');

/**
* Get the localization for game
*/
require_once('languages/'.$player -> lang.'/funkcje.php');

/**
* Function count lost stats in battle
*/
function loststat(&$fighter) {
	$fighter -> lost = $fighter -> level * 0.01;
	$fighter -> unsetBless();
	$fighter -> unsetRingBonus($fighter -> equipment);
	$arrStats = array(0,1,2,3,4,5);
	while (!empty($arrStats)) {
		$arrLength = count($arrStats) - 1;
		$rzut = rand(0,$arrLength);
		switch ($rzut) {
			case 0: $stat = $fighter -> strength; break;
			case 1: $stat = $fighter -> agility; break;
			case 2: $stat = $fighter -> inteli; break;
			case 3: $stat = $fighter -> cond; break;
			case 4: $stat = $fighter -> speed; break;
			case 5: $stat = $fighter -> wisdom; break;
			}
		if ($stat == 1) {
			$arrStats = array_merge(array_slice($arrStats, 0, $rzut), ($rzut != $arrLength) ? array_slice($arrStats, $rzut+1) : array());
			continue;
			}
		if ($stat - $fighter -> lost < 1) {
			$fighter -> lost = $stat - 1;
			}
		$fighter -> loststat = $arrStats[$rzut];
		break;
		}
	if (empty($arrStats)) $fighter -> lost = 0;
	}

/**
* Function prepare string to display gained abilities in fight
*/
function gainability (&$fighter)
{
	$strInfo = '';

	if ($fighter -> gainmiss > 0) {
		$i=1;
		$j=0;
		$gunik=0;
		while ($i+$j <= $fighter -> gainmiss) {
			$gunik+= 0.01;
			if ($j < $i) $j = $i + $j;
			else $i = $i + $j;
			}
		$fighter -> gainmiss = $gunik;
		$strInfo .= '<b>'.$gunik.'</b> '.ABILITY.' '.DODGE.'<br />';
		}
	if ($fighter -> gainattack > 0) {
		$strInfo .= '<b>'.$fighter -> gainattack.'</b> '.ABILITY.' '.A_FIGHT.'<br />';
		}
	if ($fighter -> gainshoot > 0) {
		$strInfo .= '<b>'.$fighter -> gainshoot.'</b> '.ABILITY.' '.SHOOTING.'<br />';
		}
	if ($fighter -> gainmagic > 0) {
		$strInfo .= '<b>'.$fighter -> gainmagic.'</b> '.ABILITY.' '.C_SPELL.'<br />';
		}
	return $strInfo;
}

/**
* Function count damage of weapons and armors in fight
*/
function lostitem(&$fighter)
{
	global $db;
	$strInfo = '';
	$strSQL = '';

	for($i=0;$i<=6;$i++){
		if ($fighter -> lostequip[$i]) {
			if ($fighter -> equipment[$i][6] > 0) {
				$strSQL = 'UPDATE `equipment` SET `wt` = `wt` - '.$fighter -> lostequip[$i].' WHERE `id` = '.$fighter -> equipment[$i][0].';';
				$db -> Execute($strSQL);
				switch ($i) {
					case 0: $strInfo .= YOU_WEAPON.' '.LOST1.' '.$fighter -> lostequip[$i].' '.DURABILITY.'<br />'; break;
					case 1: $strInfo .= YOU_WEAPON.' '.LOST1.' '.$fighter -> lostequip[$i].' '.DURABILITY.'<br />'; break;
					case 2: $strInfo .= YOU_HELMET.' '.LOST1.' '.$fighter -> lostequip[$i].' '.DURABILITY.'<br />'; break;
					case 3: $strInfo .= YOU_ARMOR.' '.LOST1.' '.$fighter -> lostequip[$i].' '.DURABILITY.'<br />'; break;
					case 4: $strInfo .= YOU_LEGS.' '.LOST1.' '.$fighter -> lostequip[$i].' '.DURABILITY.'<br />'; break;
					case 5: $strInfo .= YOU_SHIELD.' '.LOST1.' '.$fighter -> lostequip[$i].' '.DURABILITY.'<br />'; break;
					case 6: $strInfo .= LOST.' '.$fighter -> lostequip[$i].' '.ARROWS.'<br />'; break;
					}
				}
			else {
				$strSQL = 'DELETE FROM `equipment` WHERE `id` = '.$fighter -> equipment[$i][0].';';
				$db -> Execute($strSQL);
				switch ($i) {
					case 0: $strInfo .= YOU_WEAPON.' '.HAS_BEEN1.' '.IS_BROKEN.'<br />'; break;
					case 1: $strInfo .= YOU_WEAPON.' '.HAS_BEEN1.' '.IS_BROKEN.'<br />'; break;
					case 2: $strInfo .= YOU_HELMET.' '.HAS_BEEN1.' '.IS_BROKEN.'<br />'; break;
					case 3: $strInfo .= YOU_ARMOR.' '.HAS_BEEN1.' '.IS_BROKEN.'<br />'; break;
					case 4: $strInfo .= YOU_LEGS.' '.HAS_BEEN2.' '.IS_BROKEN.'<br />'; break;
					case 5: $strInfo .= YOU_SHIELD.' '.HAS_BEEN1.' '.IS_BROKEN.'<br />'; break;
					case 6: $strInfo .= YOU_QUIVER.' '.IS_EMPTY.'<br />'; break;
					}
				}
			}
		}
	$fighter -> lostequip = array(0,0,0,0,0,0,0);
//wytarcie trutki
	if ($fighter -> equipment[0][6] > 0 && $fighter -> equipment[0][8] > 0 && $fighter -> poisontype == '') {
		$newname = str_replace("Zatruty ", "",$fighter -> equipment[0][1]);
		$strSQL = 'UPDATE `equipment` SET `name` = \''.$newname.'\', `ptype` = \'\', poison = 0 WHERE `id` = '.$fighter -> equipment[0][0].';';
		$db -> Execute($strSQL);
		}
	return '<br />'.$strInfo.'<br />';
}


/**
* Function check possibility of destroying rings in fight (by Alw)
*/
function lostring(&$fighter)
{
	global $db;
	$strInfo = '';
	for ($i=9;$i<11;$i++)
	{
		if (isset($fighter -> equipment[$i][0]) && $fighter -> equipment[$i][0])
		{
			$intRatio = 1;
			if (substr_count($fighter -> equipment[$i][1], R_GOD) || substr_count($fighter -> equipment[$i][1], R_GNOME) || substr_count($fighter -> equipment[$i][1], R_DWARF) || substr_count($fighter -> equipment[$i][1], R_ELF))
				$intRatio = 2;
			$intThrow = rand(1, $intRatio * 1000 *  $fighter -> equipment[$i][4]);
			if ($intThrow == 1)
			{
				$db->Execute('DELETE FROM `equipment` WHERE `id` = '.$fighter -> equipment[$i][0]);
				$strInfo .= YOU_RING.' '.HAS_BEEN1.' '.IS_BROKEN.'<br />';
			}
		}
	}
	if ($strInfo != "")
		$strInfo = '<br />'.$strInfo.'<br />';
	return $strInfo;
}
?>
