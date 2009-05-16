<?php
/**
 *   File functions:
 *   Show text in chat, send messages, color player names, ban/unban players in chat
 *
 *   @name                 : chatmsg.php
 *   @copyright            : (C) 2004,2005,2006 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : Marek Chosor <marek.chodor@gmail.com>
 *   @version              : 1.0
 *   @since                : 27.09.2007
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

//ajax initialization
header('Content-Type: text/json; charset=utf-8');

require_once('includes/sessions.php');
require_once('includes/config.php');
require_once('libs/Smarty.class.php');
require_once('class/player_class.php');

$smarty = new Smarty;
$smarty -> compile_check = true;

require_once('includes/franks.php');
require_once('includes/tags.php');

//db fetch mode NUM
$db -> SetFetchMode(ADODB_FETCH_NUM);

$strEmail = $db -> qstr($_SESSION['email'],get_magic_quotes_gpc());
$player = $db -> getRow("SELECT `id`, `overlib`, `lang` FROM `players` WHERE `email`=".$strEmail);
$db -> Execute("UPDATE `players` SET `lpv`=".time()." WHERE `email`=".$strEmail);

if (isset($_GET['opis']))
{
	$_SESSION['opisinlist'] = $_GET['opis'];
}
$limit = ($_SESSION['opisinlist']) ? 15 : 30;

//page to display
if (!isset($_GET['page']))
{
	$_GET['page'] = 0;
}
$_SESSION['page'] = (int)$_GET['page'];
$page = (int)$_GET['page'] * $limit;

$sql = "SELECT `players`.`id`, `user`, `rank`, `tribe`, `tribe_rank`, `opis`, `gender`";
if ($player[1])
{
	$sql .= ", sum(`reputation`.`points`), `miejsce`, `page`, `rasa`, `hp`";
}
$sql2 = " FROM `players`";
$sql3 = " LEFT JOIN `reputation` ON `reputation`.`player_id` = `players`.`id`";
$sql4 = " WHERE `players`.`lpv` >=".(time() - 180);
$sql5 = " GROUP BY `players`.`id` ORDER BY `players`.`id` ASC LIMIT ".$page.",".$limit;

$plOnline = $db -> getRow("SELECT count(*) ".$sql2.$sql4) or die($db -> ErrorMsg());
$playersOnline = (int)$plOnline[0];

$logcount = $db -> GetRow('SELECT count(*) FROM `log` WHERE `owner`='.$player[0].' AND `unread`=\'F\'');
$logcount2 = empty($logcount) ? 0 : $logcount[0];

$mailcount = $db -> GetRow('SELECT count(*) FROM `mail` WHERE `owner`='.$player[0].' AND `zapis`=\'N\' AND `unread`=\'F\' AND `send`=0');
$mailcount2 = empty($mailcount) ? 0 : $mailcount[0];

$arrPlayers = $db -> getAll($sql.$sql2.$sql3.$sql4.$sql5);
$arrRankImages = array(
						'Admin' => 'admin',
						'Staff' => 'ksiaze',
						'Sędzia' => 'sedzia',
						'Ławnik' => 'lawnik',
						'Prawnik' => 'prawnik',
						'Bibliotekarz' => 'bibliotekarz',
						'Rycerz' => 'rycerz',
						'Dama' => 'dama',
						'Marszałek Rady' => 'marszalek',
						'Redaktor' => 'redaktor',
						'Karczmarka' => 'karczmarz',
						'Techniczny' => 'techniczny',
						'Hero' => 'hero',
						'Prokurator' => 'prokurator');


for ($i = 0, $intMax = count($arrPlayers), $arrPlayers2 = array(); $i < $intMax; $i++)
{
	// If user is on page city.php, determine city's name.
	if ($arrPlayers[$i][9] =='Altara')
		$arrPlayers[$i][9] = ($arrPlayers[$i][8] =='Altara') ? 'Meredith' : 'Agarakar';
	elseif( $arrPlayers[$i][9] =='Chat')
		$arrPlayers[$i][9] = 'Tawerna';

	$rankName = footselectrank($arrPlayers[$i][2], $arrPlayers[$i][6],$player[2]);
	$rankIcon = array_key_exists($arrPlayers[$i][2], $arrRankImages) ? '<img src="images/ranks/'.$arrRankImages[$arrPlayers[$i][2]].'.png" title="'.$rankName.'" alt="'.$rankName.'" /> ' : '';

	$playerName = getTaggedPlayerName ($arrPlayers[$i][1], $arrPlayers[$i][3], $arrPlayers[$i][4]);

	//id
	$arrPlayers2[$i]['id'] = $arrPlayers[$i][0];
	//name
	$arrPlayers2[$i]['name'] = $playerName;
	//opis
	$arrPlayers2[$i]['opis'] = $arrPlayers[$i][5];
	//rankIcon
	$arrPlayers2[$i]['rankIcon'] = $rankIcon;
	//rankName
	$arrPlayers2[$i]['rankName'] = $rankName;
	if ($player[1])
	{
		//reputation
		$arrPlayers2[$i]['reputation'] = (empty($arrPlayers[$i][7])) ? 0 : $arrPlayers[$i][7];
		//place
		$arrPlayers2[$i]['place'] = $arrPlayers[$i][9];
		//rasa
		$arrPlayers2[$i]['rasa'] = $arrPlayers[$i][10];
		//hp
		$arrPlayers2[$i]['hp'] = ($arrPlayers[$i][11] > 0)? 1 : 0;
		//gender
		$arrPlayers2[$i]['gender'] = $arrPlayers[$i][6];
	}
}
unset($arrPlayers, $arrRankImages);

$arrReply = array(
	'players' => $arrPlayers2,
	'max' => $playersOnline,
	'logc' => $logcount2,
	'mailc' => $mailcount2
);

require_once('includes/json.php');
$jsonlist =  new Services_JSON();
$smarty -> assign('Reply', $jsonlist -> encode($arrReply));
$smarty -> display('onlinelist.tpl');

?>
