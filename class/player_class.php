<?php
/**
 *   File functions:
 *   Class with information about player and making some things with player (e.g. atributes in array)
 *
 *   @name                 : player_class.php
 *   @copyright            : (C) 2004,2005,2006,2007 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @version              : 1.3
 *   @since                : 28.02.2006
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
// $Id: player_class.php 905 2007-02-28 21:33:05Z thindil $

    function logIn($strEmail, $strPass, $pllimit) ///TODO: make it public static on php5 server
    {
        global $db;
        global $smarty;
        if (!$strEmail || !$strPass)
        {
            $smarty -> assign ('Error', EMPTY_LOGIN);
            $smarty -> display ('error.tpl');
            exit;
        }
        // Attempt to get player data.
        $strEmail = $db -> qstr($strEmail, get_magic_quotes_gpc());
        $query = $db -> Execute('SELECT `id`, `user`, `email`, `rank`, `freeze`, `fight` FROM `players` WHERE `email`='.$strEmail.' AND `pass`=\''.$strPass.'\'') or die($db -> ErrorMsg());
        if ($query -> RecordCount() < 1)
        {
            $smarty -> assign ('Error', E_LOGIN);
            $smarty -> display ('error.tpl');
            exit;
        }
        if (!$query -> fields['id'])
        {
            $smarty -> assign ('Error', E_PLAYER);
            $smarty -> display ('error.tpl');
            exit;
        }
        if ($query -> fields['freeze'])
        {
            $smarty -> assign('Error', ACCOUNT_BLOCKED.$query -> fields['freeze'].ACCOUNT_DAYS);
            $smarty -> display ('error.tpl');
            exit;
        }
        // Check for bans.
        $objBan = $db -> Execute('SELECT `type` FROM `ban` WHERE `type`=\'IP\' AND `amount`=\''.$_SERVER['REMOTE_ADDR'].'\' OR `type`=\'ID\' AND `amount`='.$query -> fields['id'].' OR `type`=\'nick\' AND `amount`=\''.$query -> fields['user'].'\' OR `type`=\'mailadres\' AND `amount`='.$strEmail);
        if ($objBan -> fields['type'])
        {
            $objBan -> Close();
            $smarty -> assign ('Error', BANNED);
            $smarty -> display ('error.tpl');
            exit;
        }
        if( $query -> fields['rank'] != 'Admin' && $query -> fields['rank'] != 'Staff')
        {   // More players in game than it was intended.
            $objQuery = $db -> Execute('SELECT count(*) FROM `players` WHERE `rank`!=\'Admin\' AND rank!=\'Staff\' AND `lpv`>='.(time() - 180));
            if ($objQuery -> fields['count(*)'] >= 120)
            {
                $smarty -> assign('Error', MAX_PLAYERS);
                $smarty -> display('error.tpl');
                exit;
            }
            $objQuery -> Close();
        }
        $strSql = 'UPDATE `players` SET `logins`=`logins`+1, `rest`=\'N\' ';
        if ($query -> fields['fight'])
        {
            $strSql .= ',`hp`=0, `fight`=0';
		    if (!isset($_SESSION['amount']))
		        $_SESSION['amount'] = 1;
		    for ($k = 0; $k < $_SESSION['amount']; $k++)
		        unset($_SESSION['mon'.$k]['id'],$_SESSION['mon'.$k]['user'],$_SESSION['mon'.$k]['hp']);
		    unset($_SESSION['exhaust'], $_SESSION['round'], $_SESSION['points'], $_SESSION['amount'], $_SESSION['gainmiss'], $_SESSION['gainattack'], $_SESSION['gainshoot'], $_SESSION['gainmagic']);
        }
        $db -> Execute($strSql.' WHERE `id`='.$query -> fields['id']);
        $query -> Close();
        return $query -> fields['id'];
    }

class Player
{
    var $user;
    var $id;
    var $level;
    var $exp;
    var $hp;
    var $max_hp;
    var $mana;
    var $energy;
    var $max_energy;
    var $credits;
    var $bank;
    var $platinum;
    var $tribe;
    var $rank;
    var $location;
    var $ap;
    var $race;
    var $clas;
    var $agility;
    var $strength;
    var $inteli;
    var $pw;
    var $wins;
    var $losses;
    var $lastkilled;
    var $lastkilledby;
    var $age;
    var $logins;
    var $smith;
    var $attack;
    var $miss;
    var $magic;
    var $ip;
    var $speed;
    var $cond;
    var $alchemy;
    var $gg;
    var $avatar;
    var $wisdom;
    var $shoot;
    var $tribe_rank;
    var $fletcher;
    var $immunited;
    var $corepass;
    var $trains;
    var $fight;
    var $deity;
    var $maps;
    var $rest;
    var $page;
    var $profile;
    var $crime;
    var $gender;
    var $style;
    var $leadership;
    var $graphic;
    var $lang;
    var $seclang;
    var $antidote_d;
    var $antidote_n;
    var $antidote_i;
    var $resurect;
    var $breeding;
    var $battleloga;
    var $battlelogd;
    var $poll;
    var $mining;
    var $lumberjack;
    var $herbalist;
    var $jeweller;
    var $graphbar;
    var $hutnictwo;

    var $bless;
    var $blessval;
    var $overlib;
    var $mailinfo;
    var $loginfo;

/**
* Class constructor - get data from database and write it to variables
*/
    function Player($strEmail, $strTitle)
    {   /// TODO: zmienić nazwy pól w bazie i w klasie
        /// TODO: mithril, graficzny, seclang
        /// Intelligence and wisom - for mana stat/bar (cape bonus)
        /// Dirtyhack: fight
        global $db;
        $db -> Execute('UPDATE `players` SET `lpv`='.time().', `ip`=\''.$_SERVER['REMOTE_ADDR'].'\', `page`=\''.$strTitle.'\' WHERE `email`=\''.$strEmail.'\'');
        $stats = $db -> Execute('SELECT `id`, `user`, `lang`, `rank`, `level`, `exp`, `energy`, `max_energy`, `hp`, `max_hp`, `pm`, `credits`, `bank`, `platinum`, `tribe`, `tribe_rank`, `miejsce`, `style`, `graphic`, `graphbar`, `graphstyle`, `inteli`, `wisdom`, `fight`, `rasa`, `klasa`, `deity`, `gender`, `overlib`, `mailinfo`, `loginfo` FROM `players` WHERE `email`=\''.$strEmail.'\'');
        $this -> id = $stats -> fields['id'];
        $this -> user = $stats -> fields['user'];
        $this -> lang = $stats -> fields['lang'];
        $this -> rank = $stats -> fields['rank'];
        $this -> level = $stats -> fields['level'];
        $this -> exp = $stats -> fields['exp'];
        $this -> energy = $stats -> fields['energy'];
        $this -> max_energy = $stats -> fields['max_energy'];
        $this -> hp = $stats -> fields['hp'];
        $this -> max_hp = $stats -> fields['max_hp'];
        $this -> mana = $stats -> fields['pm'];
        $this -> credits = $stats -> fields['credits'];
        $this -> bank = $stats -> fields['bank'];
        $this -> platinum = $stats -> fields['platinum'];
        $this -> tribe = $stats -> fields['tribe'];
        $this -> tribe_rank = $stats -> fields['tribe_rank'];
        $this -> location = $stats -> fields['miejsce'];
        $this -> page = $strTitle;
        $this -> style = $stats -> fields['style'];
        $this -> graphbar = $stats -> fields['graphbar'];
		$this -> graphic = $stats -> fields['graphic'];
        $this -> graphstyle = $stats -> fields['graphstyle'];

        $this -> inteli = $stats -> fields['inteli'];
        $this -> wisdom = $stats -> fields['wisdom'];
        $this -> fight = $stats -> fields['fight'];

        $this -> race = $stats -> fields['rasa'];
        $this -> clas = $stats -> fields['klasa'];
        $this -> deity = $stats -> fields['deity'];

        $this -> gender = $stats -> fields['gender'];
        $this -> overlib = $stats -> fields['overlib'];
        $this -> mailinfo = $stats -> fields['mailinfo'];
        $this -> loginfo = $stats -> fields['loginfo'];

        $stats -> Close();
    }

    function artisandata()
    {//race class, 6 cech (minus int i sw), ability, fletcher, alchemia, jeweller, platinum
        global $db;
        $stats = $db -> Execute('SELECT `rasa`, `klasa`, `gender`, `platinum`, `strength`, `agility`, `szyb`, `wytrz`, `ability`, `fletcher`, `alchemia`, `jeweller`, `hutnictwo` FROM `players` WHERE `id`='.$this -> id);
        $this -> race = $stats -> fields['rasa'];
        $this -> clas = $stats -> fields['klasa'];
        $this -> gender = $stats -> fields['gender'];
        $this -> platinum = $stats -> fields['platinum'];
        $this -> strength = $stats -> fields['strength'];
        $this -> agility = $stats -> fields['agility'];
        $this -> speed = $stats -> fields['szyb'];
        $this -> cond = $stats -> fields['wytrz'];
        $this -> smith = $stats -> fields['ability'];
        $this -> fletcher = $stats -> fields['fletcher'];
        $this -> alchemy = $stats -> fields['alchemia'];
        $this -> jeweller = $stats -> fields['jeweller'];
        $this -> hutnictwo = $stats -> fields['hutnictwo'];
        $stats -> Close();
    }

    function thiefdata()
    {//głównie złodziejstwo, ale przydaje się i innym a select jest dość mały
        global $db;
        $stats = $db -> Execute('SELECT `rasa`, `klasa`, `gender`, `agility`, `crime`, `astralcrime`, `immu` FROM `players` WHERE `id`='.$this -> id);
        $this -> race = $stats -> fields['rasa'];
        $this -> clas = $stats -> fields['klasa'];
        $this -> gender = $stats -> fields['gender'];
        $this -> agility = $stats -> fields['agility'];
        $this -> immunited = $stats -> fields['immu'];
        $this -> crime = $stats -> fields['crime'];
        $this -> astralcrime = $stats -> fields['astralcrime'];
        $stats -> Close();
    }

    function templedata()
    {
        global $db;
        $stats = $db -> Execute('SELECT `rasa`, `klasa`, `gender`, `deity`, `pw`, `bless` FROM `players` WHERE `id`='.$this -> id);
        $this -> race = $stats -> fields['rasa'];
        $this -> clas = $stats -> fields['klasa'];
        $this -> gender = $stats -> fields['gender'];
        $this -> deity = $stats -> fields['deity'];
        $this -> faith = $stats -> fields['pw'];
        $this -> bless = $stats -> fields['bless'];
        $stats -> Close();
    }

    function languagedata()
    {//różne pierdółki, głównie język dodatkowy
        global $db;
        $stats = $db -> Execute('SELECT `rasa`, `klasa`, `gender`, `seclang`, `poll`, `logins` FROM `players` WHERE `id`='.$this -> id);
        $this -> race = $stats -> fields['rasa'];
        $this -> clas = $stats -> fields['klasa'];
        $this -> gender = $stats -> fields['gender'];
        $this -> seclang = $stats -> fields['seclang'];
        $this -> poll = $stats -> fields['poll'];
        $this -> logins = $stats -> fields['logins'];
        $stats -> Close();
    }

    function raredata()
    {// Do rzadko używanych plików. Jest tu mithril więc kiedyś trza będzie rozbić na mniejsze funkcyjki. Ogólnie - śmietnik
        global $db;
        $stats = $db -> Execute('SELECT `rasa`, `klasa`, `gender`, `platinum`, `szyb`, `breeding`, `leadership`, `mining`, `lumberjack`, `herbalist`, `corepass`, `logins`, `maps`, `crime`, `resurect` FROM `players` WHERE `id`='.$this -> id);
        global $db;
        $this -> race = $stats -> fields['rasa'];
        $this -> clas = $stats -> fields['klasa'];
        $this -> gender = $stats -> fields['gender'];
        $this -> platinum = $stats -> fields['platinum'];
        $this -> speed = $stats -> fields['szyb'];
        $this -> breeding = $stats -> fields['breeding'];
        $this -> leadership = $stats -> fields['leadership'];
        $this -> mining = $stats -> fields['mining'];
        $this -> lumberjack = $stats -> fields['lumberjack'];
        $this -> herbalist = $stats -> fields['herbalist'];
        $this -> corepass = $stats -> fields['corepass'];
        $this -> logins = $stats -> fields['logins'];
        $this -> maps = $stats -> fields['maps'];
        $this -> crime = $stats -> fields['crime'];
        $this -> resurect = $stats -> fields['resurect'];
        $stats -> Close();
    }

    function battledata()
    {// przygotowanie do odpalenia stats() na bieżącym graczu
// Dodane blessy!
        global $db;
        $stats = $db -> Execute('SELECT `age`, `rasa`, `klasa`, `gender`, `immu`, `strength`, `agility`, `szyb`, `wytrz`, `atak`, `unik`, `magia`, `shoot`, `maps`, `antidote_d`, `antidote_n`, `antidote_i`, `battleloga`, `battlelogd`, `bless`, `blessval` FROM `players` WHERE `id`='.$this -> id);
        $this -> age = $stats -> fields['age'];
        $this -> race = $stats -> fields['rasa'];
        $this -> clas = $stats -> fields['klasa'];
        $this -> gender = $stats -> fields['gender'];
        $this -> immunited = $stats -> fields['immu'];
        $this -> strength = $stats -> fields['strength'];
        $this -> agility = $stats -> fields['agility'];
        $this -> speed = $stats -> fields['szyb'];
        $this -> cond = $stats -> fields['wytrz'];
        $this -> attack = $stats -> fields['atak'];
        $this -> miss = $stats -> fields['unik'];
        $this -> magic = $stats -> fields['magia'];
        $this -> shoot = $stats -> fields['shoot'];
        $this -> maps = $stats -> fields['maps'];
        $this -> antidote_d = (!empty($stats -> fields['antidote_d'])) ? $stats -> fields['antidote_d']{0} : '';
        $this -> antidote_n = (!empty($stats -> fields['antidote_n'])) ? $stats -> fields['antidote_n']{0} : '';
        $this -> antidote_i = (!empty($stats -> fields['antidote_i'])) ? $stats -> fields['antidote_i']{0} : '';
        $this -> battleloga = $stats -> fields['battleloga'];
        $this -> battlelogd = $stats -> fields['battlelogd'];
        $this -> bless = $stats -> fields['bless'];
        $this -> blessval = $stats -> fields['blessval'];
        $stats -> Close();
    }

    function accountdata()
    {//"Opcje konta"
        global $db;
        $stats = $db -> Execute('SELECT `seclang`, `battleloga`, `battlelogd`, `rasa`, `klasa`, `gender`, `immu`, `avatar`, `profile`, `opis` FROM `players` WHERE `id`='.$this -> id);

        $this -> seclang = $stats -> fields['seclang'];
        $this -> battleloga = $stats -> fields['battleloga'];
        $this -> battlelogd = $stats -> fields['battlelogd'];
        $this -> race = $stats -> fields['rasa'];
        $this -> clas = $stats -> fields['klasa'];
        $this -> gender = $stats -> fields['gender'];
        $this -> immunited = $stats -> fields['immu'];
        $this -> avatar = $stats -> fields['avatar'];
        $this -> profile = $stats -> fields['profile'];
        $this -> opis = $stats -> fields['opis'];
        $stats -> Close();
    }

    function statistics()
    {   /// TODO: po zaoraniu battle - zmiana nazwy? Albo battle niech używa. Nazwy w bazie
        global $db;
        $stats = $db -> Execute('SELECT `ap`, `rasa`, `klasa`, `age`, `logins`, `ip`, `strength`, `agility`, `szyb`, `wytrz`, `inteli`, `wisdom`, `ability`, `fletcher`, `mining`, `lumberjack`, `herbalist`, `alchemia`, `breeding`, `jeweller`, `atak`, `shoot`, `magia`, `crime`, `unik`, `leadership`, `avatar`, `gg`, `gender`, `deity`, `pw`, `wins`, `losses`, `lastkilled`, `lastkilledby`, `antidote_d`, `antidote_n`, `antidote_i`, `resurect`, `refs`, `tribe_rank`, `hutnictwo` FROM `players` WHERE `id`='.$this -> id);
        $this -> ap = $stats -> fields['ap'];
        $this -> race = $stats -> fields['rasa'];
        $this -> clas = $stats -> fields['klasa'];
        $this -> age = $stats -> fields['age'];
        $this -> logins = $stats -> fields['logins'];
        $this -> ip = $stats -> fields['ip'];
        $this -> strength = $stats -> fields['strength'];
        $this -> agility = $stats -> fields['agility'];
        $this -> speed = $stats -> fields['szyb'];
        $this -> cond = $stats -> fields['wytrz'];
        $this -> inteli = $stats -> fields['inteli'];
        $this -> wisdom = $stats -> fields['wisdom'];
        $this -> smith = $stats -> fields['ability'];
        $this -> fletcher = $stats -> fields['fletcher'];
        $this -> mining = $stats -> fields['mining'];
        $this -> lumberjack = $stats -> fields['lumberjack'];
        $this -> alchemy = $stats -> fields['alchemia'];
        $this -> herbalist = $stats -> fields['herbalist'];
        $this -> breeding = $stats -> fields['breeding'];
        $this -> jeweller = $stats -> fields['jeweller'];
        $this -> attack = $stats -> fields['atak'];
        $this -> shoot = $stats -> fields['shoot'];
        $this -> magic = $stats -> fields['magia'];
        $this -> crime = $stats -> fields['crime'];
        $this -> miss = $stats -> fields['unik'];
        $this -> leadership = $stats -> fields['leadership'];
        $this -> avatar = $stats -> fields['avatar'];
        $this -> gg = $stats -> fields['gg'];
        $this -> gender = $stats -> fields['gender'];
        $this -> deity = $stats -> fields['deity'];
        $this -> faith = $stats -> fields['pw'];
        $this -> wins = $stats -> fields['wins'];
        $this -> losses = $stats -> fields['losses'];
        $this -> lastkilled = $stats -> fields['lastkilled'];
        $this -> lastkilledby = $stats -> fields['lastkilledby'];
        $this -> antidote_d = (!empty($stats -> fields['antidote_d'])) ? $stats -> fields['antidote_d']{0} : '';
        $this -> antidote_n = (!empty($stats -> fields['antidote_n'])) ? $stats -> fields['antidote_n']{0} : '';
        $this -> antidote_i = (!empty($stats -> fields['antidote_i'])) ? $stats -> fields['antidote_i']{0} : '';
        $this -> resurect = $stats -> fields['resurect'];
        $this -> refs = $stats -> fields['refs'];
        $this -> tribe_rank = $stats -> fields['tribe_rank'];
        $this -> hutnictwo = $stats -> fields['hutnictwo'];
        $stats -> Close();
    }

/// Podział zgodny z pomysłem na forum... Na razie zostawiony odłogiem
/*    function description($intId)
    {   global $db;
/// TODO: Dorobić żeby pokazywał cudzy profil. Immun. Profil wywalić :>
        $stats = $db -> Execute('SELECT `age`, `gender`, `gg`, `avatar`, `wins`, `losses`, `lastkilled`, `lastkilledby`, `tribe_rank`, `immu`, `profile` FROM `players` WHERE `id`='.$intId);
        $this -> age = $stats -> fields['age'];
        $this -> gender = $stats -> fields['gender'];
        $this -> gg = $stats -> fields['gg'];
        $this -> avatar = $stats -> fields['avatar'];
        $this -> wins = $stats -> fields['wins'];
        $this -> losses = $stats -> fields['losses'];
        $this -> lastkilled = $stats -> fields['lastkilled'];
        $this -> lastkilledby = $stats -> fields['lastkilledby'];
        $this -> tribe_rank = $stats -> fields['tribe_rank'];
        $this -> fletcher = $stats -> fields['fletcher'];
        $this -> immunited = $stats -> fields['immu'];
        $this -> profile = $stats -> fields['profile'];
        $stats -> Close();
    }
    function rare()
    {   global $db;
        /// TODO: Immun
        $stats = $db -> Execute('SELECT `corepass`, `battleloga`, `battlelogd`, `deity`, `deity_change`, `pw`, `bless`, `blessval`, `maps`, `bridge`, `temp`, `rest`, `houserest`, `poll`, `crime`, `crime_astral`, `forum_time`, `tforum_time` FROM `players` WHERE `id`='.$this -> id);
        $this -> pw = $stats -> fields['pw'];
        $this -> corepass = $stats -> fields['corepass'];
        $this -> trains = $stats -> fields['trains'];
        $this -> deity = $stats -> fields['deity'];
        $this -> maps = $stats -> fields['maps'];
        $this -> rest = $stats -> fields['rest'];
        $this -> crime = $stats -> fields['crime'];
        $this -> battleloga = $stats -> fields['battleloga'];
        $this -> battlelogd = $stats -> fields['battlelogd'];
        $this -> poll = $stats -> fields['poll'];
        $stats -> Close();
    }
*/

    /**
     * Function return values of selected atributes in array
     * Używana tylko w walce, zeby miała sens - wcześniej musi być pobranie statów.
     */
    function stats($stats)
    {
        $arrstats = array();
        foreach ($stats as $value)
        {
            $arrstats[$value] = $this -> $value;
        }
        return $arrstats;
    }

    /**
     * Function return values of equiped items
     */
    function equipment()
    {
        global $db;

        $arrEquip = array(array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
                          array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
                          array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
                          array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
                          array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
                          array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
                          array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
                          array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
                          array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
                          array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
                          array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0));
        $arrEquiptype = array('W', 'B', 'H', 'A', 'L', 'S', 'R', 'T', 'C', 'I');
        $objEquip = $db -> Execute('SELECT `id`, `name`, `power`, `type`, `minlev`, `zr`, `wt`, `szyb`, `poison`, `ptype`, `maxwt` FROM `equipment` WHERE `owner`='.$this -> id.' AND status=\'E\'');
        while (!$objEquip -> EOF)
        {
            $intKey = array_search($objEquip -> fields['type'], $arrEquiptype);
            if ($arrEquip[9][0] && $objEquip -> fields['id'] != $arrEquip[9][0] && $objEquip -> fields['type'] == 'I')
            {
                $intKey = 10;
            }
            $arrEquip[$intKey][0] = $objEquip -> fields['id'];
            $arrEquip[$intKey][1] = $objEquip -> fields['name'];
            $arrEquip[$intKey][2] = $objEquip -> fields['power'];
            $arrEquip[$intKey][3] = $objEquip -> fields['ptype'];
            $arrEquip[$intKey][4] = $objEquip -> fields['minlev'];
            $arrEquip[$intKey][5] = $objEquip -> fields['zr'];
            $arrEquip[$intKey][6] = $objEquip -> fields['wt'];
            $arrEquip[$intKey][7] = $objEquip -> fields['szyb'];
            $arrEquip[$intKey][8] = $objEquip -> fields['poison'];
            $arrEquip[$intKey][9] = $objEquip -> fields['maxwt'];
            $objEquip -> MoveNext();
        }
        $objEquip -> Close();
        return $arrEquip;
    }
}
