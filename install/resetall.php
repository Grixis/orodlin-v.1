<?php
/**
 *   File functions:
 *   Additional file - make new era in game. Before start this script - copy table players to table zapas
 *
 *   @name                 : resetall.php
 *   @copyright            : (C) 2004,2005,2006,2007 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @author               : eyescream <tduda@users.sourceforge.net>
 *   @version              : 1.4
 *   @since                : 16.10.2007
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

print 'Nie dotykac bez update pliku i konsultacji z Teamem. A jesli jakis "hakier" sie tu nam zaplatal to chyba Ci nie wyszlo';

//Należy poniższe 'exit' zakomentować TYLKO na czas dokonywanego resetu (dla bezpieczeństwa).
exit;

require_once('../includes/config.php');

print 'Pracować po kolei nad każdym punktem zakomentowując nieaktywne części skryptu. Za dużo się tu może posypać';


//	1. Stworzyc tabelke "zapas" o strukturze identycznej jak tabelka players.

$db -> Execute('DROP TABLE IF EXISTS `zapas`') or die($db -> ErrorMsg());

$db -> Execute("CREATE TABLE `zapas` (
	    `user` varchar(15) COLLATE utf8_polish_ci NOT NULL DEFAULT '',
		`email` varchar(60) COLLATE utf8_polish_ci NOT NULL DEFAULT '',
		`pass` varchar(32) COLLATE utf8_polish_ci NOT NULL DEFAULT '',
		`rank` varchar(20) COLLATE utf8_polish_ci NOT NULL DEFAULT 'Member',
		`age` int(11) NOT NULL default '1',
		`logins` int(11) NOT NULL default '0',
		`avatar` varchar(36) character set utf8 NOT NULL default '',
		`profile` text COLLATE utf8_polish_ci NOT NULL
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;") or die($db -> ErrorMsg());


//	1 a. Wrzucic w nia dane z players

$db -> Execute('INSERT INTO zapas( `user` , `email` , `pass` , `rank` , `age` , `logins` , `avatar` , `profile` )
	SELECT `user` , `email` , `pass` , `rank` , `age` , `logins` , `avatar` , `profile`
	FROM players
	ORDER BY `id`') or die($db -> ErrorMsg());


//	2. Stworzyc tabelke map_players do mapowania starych i nowych ID.

$db -> Execute('DROP TABLE IF EXISTS `map_players`') or die($db -> ErrorMsg());

$db -> Execute("CREATE TABLE `map_players` (
	  `old_id` smallint(5) unsigned NOT NULL default '1',
	  `id` smallint(5) unsigned NOT NULL auto_increment,
	  PRIMARY KEY  (`id`),
	  KEY `old_id` (`old_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;") or die($db -> ErrorMsg());

$db -> Execute('INSERT INTO map_players( old_id ) SELECT id FROM players ORDER BY id ASC') or die($db -> ErrorMsg());


//	3. Czyszczenie tabelek ktore przechodza.

$db -> Execute('TRUNCATE TABLE `aktywacja`');
$db -> Execute('TRUNCATE TABLE `amarket`');
$db -> Execute('TRUNCATE TABLE `astral`');
$db -> Execute('TRUNCATE TABLE `astral_bank`');
$db -> Execute('TRUNCATE TABLE `astral_machine`');
$db -> Execute('TRUNCATE TABLE `astral_plans`');
$db -> Execute('TRUNCATE TABLE `ban`');
$db -> Execute('TRUNCATE TABLE `ban_mail`');
$db -> Execute('TRUNCATE TABLE `bugreport`');
$db -> Execute('TRUNCATE TABLE `bugtrack`');
$db -> Execute('TRUNCATE TABLE `chat`');
$db -> Execute('TRUNCATE TABLE `chat_config`');
$db -> Execute('TRUNCATE TABLE `chat_users`');
$db -> Execute('TRUNCATE TABLE `clan_armies`');
$db -> Execute('TRUNCATE TABLE `clan_mercs_equipment`');
$db -> Execute('TRUNCATE TABLE `coresplayers`');
$db -> Execute('TRUNCATE TABLE `events`');
$db -> Execute('TRUNCATE TABLE `farm`');
$db -> Execute('TRUNCATE TABLE `farms`');
$db -> Execute('TRUNCATE TABLE `fightlogs`');
$db -> Execute('TRUNCATE TABLE `herbs`');
$db -> Execute('TRUNCATE TABLE `hmarket`');
$db -> Execute('TRUNCATE TABLE `houses`');
$db -> Execute('TRUNCATE TABLE `jail`');
$db -> Execute('TRUNCATE TABLE `jeweller_work`');
$db -> Execute('TRUNCATE TABLE `log`');
$db -> Execute('TRUNCATE TABLE `logs`');
$db -> Execute('TRUNCATE TABLE `lost_pass`');
$db -> Execute('TRUNCATE TABLE `lumberjack`');
$db -> Execute('TRUNCATE TABLE `mail`');
$db -> Execute('TRUNCATE TABLE `mill_work`');
$db -> Execute('TRUNCATE TABLE `minerals`');
$db -> Execute('TRUNCATE TABLE `mines`');
$db -> Execute('TRUNCATE TABLE `mines_search`');
$db -> Execute('TRUNCATE TABLE `notatnik`');
$db -> Execute('TRUNCATE TABLE `ogloszenia`');
$db -> Execute('TRUNCATE TABLE `outposts`');
$db -> Execute('TRUNCATE TABLE `outpost_monsters`');
$db -> Execute('TRUNCATE TABLE `outpost_veterans`');
$db -> Execute('TRUNCATE TABLE `players`');
$db -> Execute('TRUNCATE TABLE `pmarket`');
$db -> Execute('TRUNCATE TABLE `pools`');
$db -> Execute('TRUNCATE TABLE `pools_comments`');
$db -> Execute('TRUNCATE TABLE `reset`');
$db -> Execute('TRUNCATE TABLE `smelter`');
$db -> Execute('TRUNCATE TABLE `smith_work`');
$db -> Execute('TRUNCATE TABLE `trained_stats`');
$db -> Execute('TRUNCATE TABLE `tribe_mag`');
$db -> Execute('TRUNCATE TABLE `tribe_oczek`');
$db -> Execute('TRUNCATE TABLE `tribe_perm`');
$db -> Execute('TRUNCATE TABLE `tribe_rank`');
$db -> Execute('TRUNCATE TABLE `tribe_replies`');
$db -> Execute('TRUNCATE TABLE `tribe_topics`');
$db -> Execute('TRUNCATE TABLE `tribe_zbroj`');
$db -> Execute('TRUNCATE TABLE `tribes`');

$db -> Execute('TRUNCATE TABLE `updates`');
$db -> Execute('TRUNCATE TABLE `update_comments`');
$db -> Execute('TRUNCATE TABLE `vault`');
$db -> Execute('TRUNCATE TABLE `warehouse`');

$db -> Execute('DELETE FROM czary WHERE gracz >0');
$db -> Execute('DELETE FROM equipment WHERE owner >0');
$db -> Execute('DELETE FROM jeweller WHERE owner >0');
$db -> Execute('DELETE FROM mill WHERE owner >0');
$db -> Execute('DELETE FROM potions WHERE owner >0');
$db -> Execute('DELETE FROM smith WHERE owner >0');
$db -> Execute('DELETE FROM reputation WHERE player_id NOT IN (SELECT old_id FROM map_players)');

$db -> Execute('INSERT INTO herbs( id ) SELECT id FROM players');
$db -> Execute('INSERT INTO minerals( id, pine ) SELECT id, 50 FROM players');

$db -> Execute('UPDATE `settings` SET `value`=\'100000\' WHERE `setting`=\'monsterhp\'');
$db -> Execute('UPDATE `settings` SET `value`=\'\' WHERE `setting`=\'item\'');
$db -> Execute('UPDATE `settings` SET `value`=\'\' WHERE setting=\'player\'');
$db -> Execute('UPDATE `settings` SET `value`=\'\' WHERE `setting`=\'tribe\'');
$db -> Execute('UPDATE `settings` SET `value`=`value`+1 WHERE `setting`=\'age\'');
$db -> Execute('UPDATE `settings` SET `value`=\'1\' WHERE `setting`=\'day\'');
$db -> Execute('UPDATE `settings` set `value`=0 WHERE `setting`=\'copper\' OR `setting`=\'iron\' OR `setting`=\'coal\' OR `setting`=\'mithril\' OR `setting`=\'adamantium\' OR `setting`=\'meteor\' OR `setting`=\'crystal\' OR `setting`=\'illani\' OR `setting`=\'illanias\' OR `setting`=\'nutari\' OR `setting`=\'dynallca\' OR `setting`=\'copperore\' OR `setting`=\'zincore\' OR `setting`=\'tinore\' OR `setting`=\'ironore\' OR `setting`=\'bronze\' OR `setting`=\'brass\' OR `setting`=\'steel\' OR `setting`=\'pine\' OR `setting`=\'hazel\' OR `setting`=\'yew\' OR `setting`=\'elm\'');


//	3 a. Przepisanie wyczyszczonej tabeli players

$db -> Execute('INSERT INTO players( `user` , `email` , `pass` , `rank` , `age` , `logins` , `avatar` , `profile` ) 
	SELECT `user` , `email` , `pass` , `rank` , `age` , `logins` , `avatar` , `profile`
	FROM zapas') or die($db -> ErrorMsg());

//	4. Aktualizacje tabelek

//	UWAGA: Dellas sie upiera by w bibliotece ustawiac niebotycznie wysokie id ludziom ktorzy opuscili kraine... Nie wiem czy to sie zmieni (albo czy sie zmieni biblioteka). Przy przejsciu do ery 2 nie musialem
//	tego robic... skrypt aktualizacji ID po Thindilu jest na koncu tego pliku, ja na szczescie musialem tylko przenumerowac ID.

$tableList = array('links', 'reputation', 'library');
$columnNames = array('owner', 'player_id', 'author_id');

$mapID = $db -> GetAll('SELECT old_id, id FROM map_players ORDER BY id ASC');
for($i = 0;$i < 3; ++$i)
	for($j = 0; $j < sizeof($mapID); ++$j)
	{
		$string = 'UPDATE '.$tableList[$i].' SET '.$columnNames[$i].'='.$mapID[$j]['id'].' WHERE '.$columnNames[$i].'='.$mapID[$j]['old_id'];
		$db -> Execute($string) or die($db -> ErrorMsg());
	}


/**
* New id for authors in library
*/
/*$oldFetchMode = $db -> SetFetchMode(ADODB_FETCH_NUM);
$arrAuthor = $db -> GetAll('SELECT `author_id` FROM `library` GROUP BY `author_id`');
for ($i = 0, $k = count($arrAuthor); $i < $k; $i++)
{
    $arrOldId = $db -> GetRow('SELECT `user` FROM `zapas` WHERE `id`='.$arrAuthor[$i][0]);
    if (!empty($arrOldId))
    {
        $arrNewId = $db -> GetRow('SELECT `id` FROM `players` WHERE `user`=\''.$arrOldId[0].'\'');
        $db -> Execute('UPDATE `library` SET `author_id`='.$arrNewId[0].' WHERE `author_id`='.$arrAuthor[$i][0]);
    }
}
print 'Library ok<br />';
*/

print 'End of DB reset.';

?>
