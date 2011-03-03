<?php
/**
 *   File functions:
 *   Admin panel
 *
 *   @name                 : admin.php
 *   @copyright            : (C) 2004,2005,2006,2007 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @author               : eyescream <tduda@users.sourceforge.net>
 *   @version              : 1.4
 *   @since                : 19.04.2007
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

$title = 'Panel Administracyjny';
require_once('includes/head.php');

/**
* Get the localization for game
*/
require_once('languages/'.$player -> lang.'/admin.php');

if ($player -> rank != 'Admin')
{
    error (NOT_ADMIN);
}

$smarty -> assign('Message', '');

/**
 * Functions from includes/admin dir
 */
if (isset($_GET['view']))
{
    $arrView = array('takeaway', 'clearc', 'czat', 'jail', 'innarchive', 'banmail', 'addtext', 'changenick', 'addreps');
    $intKey = array_search($_GET['view'], $arrView);
    if ($intKey !== false)
    {
        require_once('includes/admin/'.$arrView[$intKey].'.php');
    }
}


/**
 * Reported bugs
 */
if (isset($_GET['view']) && $_GET['view'] == 'bugreport')
{
    /**
     * Bugs list
     */
    if (!isset($_GET['step']))
    {
		$oldFetchMode = $db -> SetFetchMode(ADODB_FETCH_NUM);
		$arrBugs = $db -> GetAll('SELECT `id`, `sender`, `title`, `type`, `location`, `programmer_id` FROM `bugreport` WHERE `resolution`=0 ORDER BY `id`');
		$db -> SetFetchMode($oldFetchMode);
        $smarty -> assign_by_ref('Bugs', $arrBugs);
    }
    /**
     * Edit bug
     */
        else
    {
        if (!preg_match("#^[1-9][0-9]*$#", $_GET['step']))
        {
            error(ERROR);
        }
		$oldFetchMode = $db -> SetFetchMode(ADODB_FETCH_NUM);
        $arrBug = $db -> GetRow('SELECT `id`, `sender`, `title`, `type`, `location`, `desc`, `programmer_id` FROM `bugreport` WHERE `id`='.$_GET['step']);
		if (empty($arrBug))
        {
            error(ERROR);
        }
        require_once('includes/bbcode.php');
        $arrBug[5] = htmltobbcode($arrBug[5]);
        $arrActions = array('fixed', 'notbug', 'workforme', 'moreinfo', 'duplicate');
		$arrProgrammerName = $db -> GetRow('SELECT `user` FROM `players` WHERE `id`='.$arrBug[6]);
		$db -> SetFetchMode($oldFetchMode);
		if (!empty($arrProgrammerName))
		{
			$smarty -> assign_by_ref('Programmer', $arrProgrammerName[0]);
		}
		$strType = $arrBug[3] == 'text' ? BUG_TEXT : BUG_CODE;
		$smarty -> assign_by_ref('Options', $arrOptions);
        $smarty -> assign_by_ref('Actions', $arrActions);
		$smarty -> assign_by_ref('Bug', $arrBug);
		$smarty -> assign_by_ref('BugType', $strType);
		$smarty -> assign('BugMessage', '');
		if (isset($_POST['programmer']) && $_POST['programmer'] == 1)
		{
			$db -> Execute('UPDATE `bugreport` SET `programmer_id`='.$player -> id.' WHERE `id`='.$_GET['step']);
			$smarty -> assign('BugMessage', BUG_TAKEN.'<br /><br />');
		}
		if (isset($_POST['programmer']) && $_POST['programmer'] == 0)
		{
			$db -> Execute('UPDATE `bugreport` SET `programmer_id`=0 WHERE `id`='.$_GET['step']);
			$smarty -> assign('BugMessage', BUG_LEFT.'<br />');
		}
        /**
         * Set bug status
         */
        if (isset($_POST['actions']))
        {
            if (!in_array($_POST['actions'], $arrActions))
            {
                error(ERROR);
            }
            $strInfo = YOUR_BUG.$arrBug[2].B_ID.$_GET['step'];
            $strDate = $db -> DBDate($newdate);
            $intKey = array_search($_POST['actions'], $arrActions);
            switch ($intKey) {
                case 0 :
                    $strInfo = $strInfo.HAS_FIXED;
                    $strMessage = HAS_FIXED2;
                    $strAuthor = '<b><a href="view.php?view='.$player -> id.'">'.$player -> user."</a></b>, ID <b>".$player -> id.'</b>';
                    $strDesc = T_BUG.$strType."): ".$arrBug[2]. REPORTED_BY.$arrBug[1];
                    $db -> Execute("INSERT INTO `changelog` (`author`, `location`, `text`, `date`, `lang`) VALUES('".$strAuthor."', '".$arrBug[4]."', '".$strDesc."', ".$strDate.", '".$player -> lang."')");
                    break;
                case 1 :
                    $strInfo = $strInfo.NOT_BUG3;
                    $strMessage = NOT_BUG2;
                    break;
                case 2 :
                    $strInfo = $strInfo.WORK_FOR_ME2;
                    $strMessage = WORK_FOR_ME3;
                    break;
                case 3 :
                    $strInfo = $strInfo.MORE_INFO2;
                    $strMessage = MORE_INFO3;
                    break;
                case 4 :
                    $strInfo = $strInfo.BUG_DOUBLE2;
                    $strMessage = BUG_DOUBLE3;
            }
            $db -> Execute("DELETE FROM `bugreport` WHERE `id`=".$_GET['step']);
            if (isset($_POST['bugcomment']) && !empty($_POST['bugcomment']))
            {
                $strInfo = $strInfo." <b>".BUG_COMMENT.":</b> ".$_POST['bugcomment'];
            }
            $db -> Execute("INSERT INTO `log` (`owner`, `log`, `czas`) VALUES(".$arrBug[1].", '".$strInfo."', ".$strDate.")");
            error($strMessage);
        }
    }
}

/**
 * Add player to quest
 */
if (isset($_GET['view']) && $_GET['view'] == 'playerquest')
{
    $smarty -> assign(array("Addplayer" => ADD_PLAYER,
                            "Toquest" => TO_QUEST,
                            "Aadd" => A_ADD));
    if (isset($_GET['step']) && $_GET['step'] == 'add')
    {
        if (empty($_POST['pid']) || empty($_POST['qid']))
        {
            error(EMPTY_FIELDS);
        }
        $db -> Execute("DELETE FROM `questaction` WHERE player=".$_POST['pid']);
        $db -> Execute("INSERT INTO `questaction` (`player`, `quest`, `action`) VALUES(".$_POST['pid'].", ".$_POST['qid'].", 'start')");
        $db -> Execute("UPDATE `players` SET `miejsce`='Podróż' WHERE id=".$_POST['pid']);
        $smarty -> assign("Message", YOU_ADD);
    }
}

/**
 * Add info about changes in game
 */
if (isset($_GET['view']) && $_GET['view'] == 'changelog')
{
    if ($player -> id != 1)
    {
        error(ONLY_MAIN);
    }
    $smarty -> assign(array("Changeinfo" => CHANGE_INFO,
                            "Changelocation" => CHANGE_LOCATION,
                            "Changetext" => CHANGE_TEXT,
                            "Aadd" => A_ADD));
    if (isset($_GET['step']) && $_GET['step'] == 'add')
    {
        if (empty($_POST['location']) || empty($_POST['changetext']))
        {
            error(EMPTY_FIELDS);
        }
        $strDate = $db -> DBDate($newdate);
        $strAuthor = '<b><a href="view.php?view='.$player -> id.'">'.$player -> user."</a></b>, ID <b>".$player -> id.'</b>';
        require_once('includes/bbcode.php');
        $strText = bbcodetohtml($_POST['changetext']);
        $db -> Execute("INSERT INTO `changelog` (`author`, `location`, `text`, `date`, `lang`) VALUES('".$strAuthor."', '".$_POST['location']."', '".$strText."', ".$strDate.", '".$player -> lang."')");
        $smarty -> assign("Message", CHANGE_ADDED);
    }
}

/**
 * Display players logs
 */
if (isset($_GET['view']) && $_GET['view'] == 'logs')
{
    if (!isset($_GET['limit']))
    {
        $_GET['limit'] = 0;
    }
    $objAmount = $db -> Execute("SELECT count(*) FROM `logs`");
    $intAmount = $objAmount -> fields['count(*)'];
    $objAmount -> Close();
    if (!$intAmount || $_GET['limit'] > $intAmount)
    {
        error(NO_LOGS);
    }
    $objLogs = $db -> SelectLimit("SELECT `owner`, `log` FROM `logs`", 50, $_GET['limit']);
    $arrOwner = array();
    $arrLog = array();
    $i = 0;
    while (!$objLogs -> EOF)
    {
        $arrOwner[$i] = $objLogs -> fields['owner'];
        $arrLog[$i] = $objLogs -> fields['log'];
        $i++;
        $objLogs -> MoveNext();
    }
    $objLogs -> Close();
    if ($_GET['limit'] >= 50)
    {
        $intLimit = $_GET['limit'] - 50;
        $strPrevious = "<a href=\"admin.php?view=logs&amp;limit=".$intLimit."\">".A_PREVIOUS."</a>";
    }
        else
    {
        $strPrevious = '';
    }
    $intLimit = $_GET['limit'] + 50;
    if ($intLimit < $intAmount && $intAmount > 50)
    {
        $strNext = "<a href=\"admin.php?view=logs&amp;limit=".$intLimit."\">".A_NEXT."</a>";
    }
        else
    {
        $strNext = '';
    }
    $smarty -> assign(array("Logsinfo" => LOGS_INFO,
                            "Lowner" => L_OWNER,
                            "Ltext" => L_TEXT,
                            "Lclear" => L_CLEAR,
                            "Aowner" => $arrOwner,
                            "Alog" => $arrLog,
                            "Aprevious" => $strPrevious,
                            "Anext" => $strNext));
    /**
     * Clear logs
     */
    if (isset($_GET['step']) && $_GET['step'] == 'clear')
    {
        $db -> Execute("TRUNCATE TABLE `logs`") or die($db -> ErrorMsg());
        $smarty -> assign("Message", LOGS_CLEARED);
    }
}

/**
 * Edit meta informations
 */
if (isset($_GET['view']) && $_GET['view'] == 'meta')
{
    if ($player -> id != 1)
    {
        error(ONLY_MAIN);
    }
    $smarty -> assign(array("Metainfo" => META_INFO,
                            "Metakey" => META_KEY,
                            "Metadesc" => META_DESC,
                            "Aadd" => A_ADD));
    /**
     * Change meta info
     */
    if (isset($_GET['step']) && $_GET['step'] == 'modify')
    {
        $db -> Execute("UPDATE `settings` SET `value`='".$_POST['metakey']."' WHERE `setting`='metakeywords'");
        $db -> Execute("UPDATE `settings` SET `value`='".$_POST['metadesc']."' WHERE `setting`='metadescr'");
        $smarty -> assign("Message", META_UPGRADE);
    }
}

/**
 * Add/Modify forum categories
 */
if (isset($_GET['view']) && $_GET['view'] == 'forums')
{
    $path = 'languages/';
    $dir = opendir($path);
    $arrLanguage = array();
    $i = 0;
    while ($file = readdir($dir))
    {
        if (!ereg(".htm*$", $file))
        {
            if (!ereg("\.$", $file))
            {
                $arrLanguage[$i] = $file;
                $i = $i + 1;
            }
        }
    }
    closedir($dir);
    $objCatforum = $db -> Execute("SELECT id, name FROM categories");
    $i = 0;
    $arrId = array();
    $arrName = array();
    while (!$objCatforum -> EOF)
    {
        $arrId[$i] = $objCatforum -> fields['id'];
        $arrName[$i] = $objCatforum -> fields['name'];
        $objCatforum -> MoveNext();
        $i++;
    }
    $objCatforum -> Close();
    $arrOptionw = array('All;', 'Staff;', 'Sędzia;', 'Kanclerz_Sądu;', 'Marszałek Rady;', 'Poseł;', 'Prawnik;', 'Ławnik;', 'Prokurator;');
    $arrOptionv = array('1All;', '1Staff;', '1Sędzia;', '1Kanclerz_Sądu;', '1Marszałek Rady;', '1Poseł;', '1Prawnik;', '1Ławnik;', '1Prokurator;');
    $arrOptionname = array(T_ALL, T_STAFF, T_JUDGE, T_JUDGE2, T_COUNT, T_COUNT2, T_LAWYER, T_JUDGE3, T_PROCURATOR);
    $arrLangsel = array('', '');
    $arrOptionwsel = array('', '', '', '', '', '', '', '', '');
    $arrOptionvsel = array('', '', '', '', '', '', '', '', '');
    $smarty -> assign(array("Catlist" => CAT_LIST,
                            "Aadd" => A_ADD,
                            "Tname" => T_NAME,
                            "Tdesc" => T_DESC,
                            "Tlang" => T_LANG,
                            "Twrite" => T_WRITE,
                            "Tvisit" => T_VISIT,
                            "Tcatdesc" => '',
                            "Tcatname" => '',
                            "Catid2" => $i + 2,
                            "Catid" => $arrId,
                            "Catname" => $arrName,
                            "Catlang" => $arrLanguage,
                            "Toptionw" => $arrOptionw,
                            "Toptionv" => $arrOptionv,
                            "Toptionname" => $arrOptionname,
                            "Tlangsel" => $arrLangsel,
                            "Toptionwsel" => $arrOptionwsel,
                            "Toptionvsel" => $arrOptionvsel));
    /**
     * When category is selected
     */
    if (isset($_GET['id']) && !isset($_GET['step']))
    {
        $objCategory = $db -> Execute("SELECT * FROM categories WHERE id=".$_GET['id']);
        $i = 0;
        foreach ($arrLanguage as $strLanguage)
        {
            if ($strLanguage == $objCategory -> fields['lang'])
            {
                $arrLangsel[$i] = 'selected';
                break;
            }
            $i++;
        }
        $i = 0;
        foreach ($arrOptionw as $strOptionw)
        {
            $strOptionw = str_replace("_", " ", $strOptionw);
            $intFind = strpos($objCategory -> fields['perm_write'], $strOptionw);
            if ($intFind !== false)
            {
                $arrOptionwsel[$i] = 'checked';
            }
            $i++;
        }
        $i = 0;
        foreach ($arrOptionw as $strOptionv)
        {
            $strOptionv = str_replace("_", " ", $strOptionv);
            $intFind = strpos($objCategory -> fields['perm_visit'], $strOptionv);
            if ($intFind !== false)
            {
                $arrOptionvsel[$i] = 'checked';
            }
            $i++;
        }
        $smarty -> assign(array("Catid2" => $_GET['id'],
                                "Tcatdesc" => $objCategory -> fields['desc'],
                                "Tcatname" => $objCategory -> fields['name'],
                                "Tlangsel" => $arrLangsel,
                                "Toptionwsel" => $arrOptionwsel,
                                "Toptionvsel" => $arrOptionvsel));
        $objCategory -> Close();
    }
    /**
     * Edit/add category
     */
    if (isset($_GET['step']) && $_GET['step'] == 'add')
    {
        $strPermwrite = '';
        foreach ($arrOptionw as $strOptionw)
        {
            if (isset($_POST[$strOptionw]))
            {
                $strOptionw = str_replace("_", " ", $strOptionw);
                $strPermwrite = $strPermwrite.$strOptionw;
            }
        }
        $strPermvisit = '';
        $i = 0;
        foreach ($arrOptionv as $strOptionv)
        {
            if (isset($_POST[$strOptionv]))
            {
                $strOption = str_replace("_", " ", $arrOptionw[$i]);
                $strPermvisit = $strPermvisit.$strOption;
            }
            $i++;
        }
        $objTest = $db -> Execute("SELECT id FROM categories WHERE id=".$_GET['id']);
        if ($objTest -> fields['id'])
        {
            $db -> Execute("UPDATE categories SET `name`='".$_POST['catname']."', `desc`='".$_POST['catdesc']."', `lang`='".$_POST['catlang']."', `perm_write`='".$strPermwrite."', `perm_visit`='".$strPermvisit."' WHERE id=".$_GET['id']) or die($db -> ErrorMsg());
            $smarty -> assign("Message", CATEGORY_MODIFIED);
        }
        else
        {
            $db -> Execute("INSERT INTO categories (`name`, `desc`, `lang`, `perm_write`, `perm_visit`) VALUES('".$_POST['catname']."', '".$_POST['catdesc']."', '".$_POST['catlang']."', '".$strPermwrite."', '".$strPermvisit."')") or die($db -> ErrorMsg());
            $smarty -> assign("Message", CATEGORY_ADDED);
        }
        $objTest -> Close();
    }
}

/**
* Add new plans in mill
*/
if (isset ($_GET['view']) && $_GET['view'] == 'mill')
{
    $smarty -> assign(array("Sname" => S_NAME,
        "Scost" => S_COST,
        "Samount" => S_AMOUNT,
        "Slevel" => S_LEVEL,
        "Stype" => S_TYPE,
        "Sbow" => S_BOW,
        "Sarrow" => S_ARROWS,
        "Aadd" => A_ADD));
    if (isset ($_GET['step']) && $_GET['step'] == 'mill')
    {
        if (!$_POST['nazwa'] || !$_POST['cena'] || !$_POST['poziom'])
        {
            error (EMPTY_FIELDS);
        }
        if ($_POST['type'] == 'B')
        {
            $strTwohand = 'Y';
        }
            else
        {
            $strTwohand = 'N';
        }
        $strName = $db -> qstr($_POST['nazwa'], get_magic_quotes_gpc());
        $db -> Execute("INSERT INTO mill (name, cost, level, amount, type, twohand) VALUES(".$strName.", ".$_POST['cena'].", ".$_POST['poziom'].", ".$_POST['amount'].", '".$_POST['type']."', '".$strTwohand."')");
    }
}

/**
 * Add player to list of donators
 */
if (isset($_GET['view']) && $_GET['view'] == 'donator')
{
    if (!isset($_GET['step']))
    {
        $smarty -> assign(array("Donatorinfo" => DONATOR_INFO,
                                "Pname" => P_NAME,
                                "Aadd" => A_ADD));
    }
    if (isset($_GET['step']) && $_GET['step'] == 'add')
    {
        if (empty($_POST['plname']))
        {
            error(ERROR);
        }
        $strName = $db -> qstr($_POST['plname'], get_magic_quotes_gpc());
        $db -> Execute("INSERT INTO donators (name) VALUES('".$_POST['plname']."')");
        $smarty -> assign("Message", YOU_ADD.$_POST['plname'].TO_DONATORS);
    }
}

/**
* Edit monsters
*/
if (isset($_GET['view']) && $_GET['view'] == 'monster2')
{
    if (!isset($_GET['step']))
    {
        $objMonsters = $db -> Execute("SELECT id, name FROM monsters");
        $arrMonsters = array();
        $arrMid = array();
        $i = 0;
        while (!$objMonsters -> EOF)
        {
            $arrMonsters[$i] = $objMonsters -> fields['name'];
            $arrMid[$i] = $objMonsters -> fields['id'];
            $i++ ;
            $objMonsters -> MoveNext();
        }
        $objMonsters -> Close();
        $smarty -> assign(array("Mname" => M_NAME,
            "Names" => $arrMonsters,
            "Mid" => $arrMid,
            "Anext" => A_NEXT));
    }
    if (isset($_GET['step']) && $_GET['step'] == 'next')
    {
        if (!ereg("^[1-9][0-9]*$", $_POST['mid']))
        {
            error(ERROR);
        }
        $objMonster = $db -> Execute("SELECT * FROM monsters WHERE id=".$_POST['mid']);
        $smarty -> assign(array("Mname" => $objMonster -> fields['name'],
            "Mlvl" => $objMonster -> fields['level'],
            "Mhp" => $objMonster -> fields['hp'],
            "Magility" => $objMonster -> fields['agility'],
            "Mstrength" => $objMonster -> fields['strength'],
            "Mspeed" => $objMonster -> fields['speed'],
            "Mendurance" => $objMonster -> fields['endurance'],
            "Mcredits1" => $objMonster -> fields['credits1'],
            "Mcredits2" => $objMonster -> fields['credits2'],
            "Mexp1" => $objMonster -> fields['exp1'],
            "Mexp2" => $objMonster -> fields['exp2'],
            "Mlocation" => $objMonster -> fields['location'],
            "Tmname" => M_NAME,
            "Tmlevel" => M_LEVEL,
            "Tmhp" => M_HP,
            "Tmagi" => M_AGI,
            "Tmpower" => M_POWER,
            "Tmspeed" => M_SPEED,
            "Tmcond" => M_COND,
            "Tmmingold" => M_MIN_GOLD,
            "Tmmaxgold" => M_MAX_GOLD,
            "Tmminexp" => M_MIN_EXP,
            "Tmmaxexp" => M_MAX_EXP,
            "Tmlocation" => M_LOCATION,
            "Aedit" => A_EDIT,
            "Mid" => $_POST['mid']));
        $objMonster -> Close();
    }
    if (isset($_GET['step']) && $_GET['step'] == 'monster')
    {
        if (!$_POST['name'] || !$_POST['level'] || !$_POST['hp'] || !$_POST['agility'] || !$_POST['strength'] || !$_POST['credits1'] || !$_POST['credits2'] || !$_POST['exp1'] || !$_POST['exp2'] || !$_POST['speed'] || !$_POST['endurance']|| !$_POST['location'])
        {
            error (EMPTY_FIELDS);
        }
        $strName = $db -> qstr($_POST['name'], get_magic_quotes_gpc());
        $strLocation = $db -> qstr($_POST['location'], get_magic_quotes_gpc());
        $db -> Execute("UPDATE monsters SET name=".$strName.", level=".$_POST['level'].", hp=".$_POST['hp'].", agility=".$_POST['agility'].", strength=".$_POST['strength'].", credits1=".$_POST['credits1'].", credits2=".$_POST['credits2'].", exp1=".$_POST['exp1'].", exp2=".$_POST['exp2'].", speed=".$_POST['speed'].", endurance=".$_POST['endurance'].", location=".$strLocation." WHERE id=".$_POST['mid']);
        $smarty -> assign("Message", YOU_EDIT.$_POST['name']);
    }
}

/**
* Release player from jail
*/
if (isset($_GET['view']) && $_GET['view'] == 'jailbreak')
{
    if (!isset($_GET['step']))
    {
        $smarty -> assign(array("Afree" => A_FREE,
            "Jailid" => JAIL_ID));
    }
    if (isset($_GET['step']) && $_GET['step'] == 'next')
    {
        if (!ereg("^[1-9][0-9]*$", $_POST['jid']))
        {
            error(ERROR);
        }
        $objPrisoner = $db -> Execute("SELECT prisoner FROM jail WHERE prisoner=".$_POST['jid']);
        if (!$objPrisoner -> fields['prisoner'])
        {
            error(NO_PLAYER2);
        }
        $objPrisoner -> Close();
        $db -> Execute("DELETE FROM jail WHERE prisoner=".$_POST['jid']);
        $db -> Execute("UPDATE players SET miejsce='Altara' WHERE id=".$_POST['jid']);
        $smarty -> assign("Message", T_MESSAGE.$_POST['jid']);
    }
}


/**
* Add new poll
*/
if (isset($_GET['view']) && $_GET['view'] == 'poll')
{
    if (!isset($_GET['step']))
    {
        $smarty -> assign(array("Tamount" => T_AMOUNT,
            "Anext" => A_NEXT,
            "Tdays" => T_DAYS));
    }
    $smarty -> assign("Tquestion", T_QUESTION);
    /**
    * Add answers to poll
    */
    if (isset($_GET['step']) && $_GET['step'] == 'second')
    {
        if (empty($_POST['question']) || empty($_POST['amount']) || empty($_POST['days']))
        {
            error(EMPTY_FIELDS);
        }
        if (!ereg("^[1-9][0-9]*$", $_POST['amount']) || !ereg("^[1-9][0-9]*$", $_POST['days']))
        {
            error(ERROR);
        }
        $arrAnswers = array();
        for ($i = 0; $i < $_POST['amount']; $i++)
        {
            $arrAnswers[$i] = "answer".$i;
        }
        $objPollid = $db -> Execute("SELECT id FROM polls ORDER BY id DESC");
        if (!$objPollid -> fields['id'])
        {
            $intId = 1;
        }
            else
        {
            $intId = $objPollid -> fields['id'] + 1;
        }
        /**
        * Update amount of players
        */
        $objQuery = $db -> Execute("SELECT id FROM players");
        $intMembers = $objQuery -> RecordCount();
        $objQuery -> Close();
        $db -> Execute("UPDATE polls SET members=".$intMembers." WHERE id=".$objPollid -> fields['id']." AND votes=-1");
        $objPollid -> Close();
        $strQuestion = $db -> qstr($_POST['question'], get_magic_quotes_gpc());
        $db -> Execute("INSERT INTO polls (id, poll, votes, days) VALUES(".$intId.", ".$strQuestion.", -1, ".$_POST['days'].")") or $db -> ErrorMsg();
        /**
         * Add log about new poll
         */
        $playersList = $db -> Execute("SELECT id FROM players");
        while (!$playersList -> EOF)
        {
            $db -> Execute('INSERT INTO `log` (`owner`, `log`, `czas`) VALUES('.$playersList -> fields['id'].',\''.NEW_POLL_MESSANGE.'\','.$db -> DBDate($newdate).')');
            $playersList -> MoveNext();
        }
        $playersList -> Close();
        $smarty -> assign(array("Answers" => $arrAnswers,
            "Question" => $_POST['question'],
            "Amount" => $_POST['amount'],
            "Aadd" => A_ADD,
            "Tanswer" => T_ANSWER,
            "Llang" => $_POST['lang'],
            "Pollid" => $intId,
            "Adays" => $_POST['days']));
    }
    /**
    * Add poll
    */
    if (isset($_GET['step']) && $_GET['step'] == 'add')
    {
        for($i = 0; $i < $_POST['amount']; $i++)
        {
            $strName = "answer".$i;
            if (empty($_POST[$strName]))
            {
                error(EMPTY_FIELDS);
            }
            $strAnswer = $db -> qstr($_POST[$strName], get_magic_quotes_gpc());
            $db -> Execute("INSERT INTO polls (id, poll) VALUES(".$_POST['pid'].", ".$strAnswer.")");
        }
        $db -> Execute("UPDATE players SET poll='N'");
        $db -> Execute("UPDATE settings SET value='Y' WHERE setting='poll'");
        $smarty -> assign("Message", POLL_ADDED);
    }
}

/**
* Add new word to censorship
*/
if (isset($_GET['view']) && $_GET['view'] == 'censorship')
{
    /**
    * Bad words list
    */
    $objWords = $db -> Execute("SELECT * FROM bad_words");
    $arrWords = array();
    $i = 0;
    while (!$objWords -> EOF)
    {
        $arrWords[$i] = $objWords -> fields['bword'];
        $i = $i + 1;
        $objWords -> MoveNext();
    }
    $objWords -> Close();
    $smarty -> assign(array("Amake" => A_MAKE,
        "Words" => $arrWords,
        "Aadd" => A_ADD,
        "Adelete" => A_DELETE,
        "Tword" => T_WORD,
        "Wordslist" => WORDS_LIST));
    if (isset($_GET['step']) && $_GET['step'] == 'modify')
    {
        $strWord = $db -> qstr($_POST['bword'], get_magic_quotes_gpc());
        /**
        * Add word
        */
        if ($_POST['action'] == 'add')
        {
            $db -> Execute("INSERT INTO bad_words (bword) VALUES(".$strWord.")");
            $smarty -> assign("Message", YOU_ADD." <b>".$_POST['bword']."</b>. (<a href=\"admin.php?view=censorship\">".REFRESH."</a>)");
        }
        /**
        * Delete word
        */
        if ($_POST['action'] == 'delete')
        {
            $db -> Execute("DELETE FROM bad_words WHERE bword=".$strWord);
            $smarty -> assign("Message", YOU_DELETE." <b>".$_POST['bword']."</b>. (<a href=\"admin.php?view=censorship\">".REFRESH."</a>)");
        }
    }
}

/**
* Close registration new players
*/
if (isset($_GET['view']) && $_GET['view'] == 'register')
{
    $smarty -> assign(array("Gopen" => G_OPEN,
        "Gclose" => G_CLOSE,
        "Ifclose" => IF_CLOSE,
        "Amake" => A_MAKE));
    if (isset ($_GET['step']) && $_GET['step'] == 'close')
    {
        if ($_POST['close'] == 'close')
        {
            $db -> Execute("UPDATE settings SET value='N' WHERE setting='register'");
            $strReason = $db -> qstr($_POST['reason'], get_magic_quotes_gpc());
            $db -> Execute("UPDATE settings SET value=".$strReason." WHERE setting='close_register'");
            error (YOU_CLOSE);
        }
        if ($_POST['close'] == 'open')
        {
            $db -> Execute("UPDATE settings SET value='Y' WHERE setting='register'");
            $db -> Execute("UPDATE settings SET value='' WHERE setting='close_register'");
            error (YOU_OPEN);
        }
    }
}

/**
* Ban and unban players by IP, emali, nick or ID
*/
if (isset($_GET['view']) && $_GET['view'] == 'ban')
{
    $smarty -> assign(array("Banlist" => BAN_LIST,
        "Baninfo" => BAN_INFO,
        "Banvalue" => BAN_VALUE,
        "Banip" => BAN_IP,
        "Banemail" => BAN_EMAIL,
        "Bannick" => BAN_NICK,
        "Banid" => BAN_ID,
        "Abanpl" => A_BAN_PL,
        "Aunban" => A_UNBAN,
        "Anext" => A_NEXT,
        "Bantype" => BAN_TYPE,
        "Banval" => BAN_VAL,
        "Banned" => BANNED));
    /**
    * Banlist
    */
    $arrtype = array();
    $arramount = array();
    $i = 0;
    $ban = $db -> Execute("SELECT type, amount FROM ban");
    while (!$ban -> EOF)
    {
        $arrtype[$i] = $ban -> fields['type'];
        $arramount[$i] = $ban -> fields['amount'];
        $i = $i + 1;
        $ban -> MoveNext();
    }
    $ban -> Close();
    $smarty -> assign(array("Type" => $arrtype,
        "Amount" => $arramount));
    if (isset($_GET['step']) && $_GET['step'] == 'modify')
    {
        $strAmount = $db -> qstr($_POST['amount'], get_magic_quotes_gpc());
        /**
        * Ban player
        */
        if ($_POST['action'] == 'ban')
        {
            $db -> Execute("INSERT INTO ban (type, amount) VALUES('".$_POST['type']."', ".$strAmount.")");
            $smarty -> assign("Message", YOU_BAN." <b>".$_POST['type']."</b> ".$_POST['amount'].". (<a href=\"admin.php?view=ban\">".REFRESH."</a>)");
        }
        /**
        * Unban player
        */
        if ($_POST['action'] == 'unban')
        {
            $db -> Execute("DELETE FROM ban WHERE type='".$_POST['type']."' AND amount=".$strAmount);
            $smarty -> assign("Message", YOU_UNBAN." <b>".$_POST['type']."</b> ".$_POST['amount'].". (<a href=\"admin.php?view=ban\">".REFRESH."</a>)");
        }
    }
}

/**
* Delete players which not login long than 21 days
*/
if (isset($_GET['view']) && $_GET['view'] == 'delplayers')
{
    if ($player -> id != 1) {
        error(ONLY_MAIN);
    }
    $curenttime = time();
    $lpv = $curenttime - 1900800;
    $oldFetchMode = $db -> SetFetchMode(ADODB_FETCH_NUM);
    $arrDelete = $db -> GetAll('SELECT `id`, `avatar` FROM `players` WHERE `age`>21 AND `lpv`<'.$lpv);
    $number = count($arrDelete);
    for ($i = 0; $i < $number; $i++)
    {
        $db -> Execute("DELETE FROM `players` WHERE `id`=".$arrDelete[$i][0]);
        $db -> Execute("DELETE FROM `core` WHERE `owner`=".$arrDelete[$i][0]);
        $db -> Execute("DELETE FROM `core_market` WHERE `seller`=".$arrDelete[$i][0]);
        $db -> Execute("DELETE FROM `equipment` WHERE `owner`=".$arrDelete[$i][0]);
        $db -> Execute("DELETE FROM `smith` WHERE `owner`=".$arrDelete[$i][0]);
        $db -> Execute("DELETE FROM `log` WHERE `owner`=".$arrDelete[$i][0]);
        $db -> Execute("DELETE FROM `mail` WHERE `owner`=".$arrDelete[$i][0]);
        $arrOutId = $db -> GetRow("SELECT `id` FROM `outposts` WHERE `owner`=".$arrDelete[$i][0]);
        if (!empty($arrOutId))
        {
            $db -> Execute("DELETE FROM `outpost_mosters` WHERE `outpost`=".$arrOutId[0]);
            $db -> Execute("DELETE FROM `outpost_veterans` WHERE `outpost`=".$arrOutId[0]);
        }
        $db -> Execute("DELETE FROM `outposts` WHERE `owner`=".$arrDelete[$i][0]);
        $db -> Execute("DELETE FROM `pmarket` WHERE `seller`=".$arrDelete[$i][0]);
        $db -> Execute("DELETE FROM `hmarket` WHERE `seller`=".$arrDelete[$i][0]);
        $db -> Execute("DELETE FROM `potions` WHERE `owner`=".$arrDelete[$i][0]);
        $db -> Execute("DELETE FROM `herbs` WHERE `gracz`=".$arrDelete[$i][0]);
        $db -> Execute("DELETE FROM `minerals` WHERE `owner`=".$arrDelete[$i][0]);
        $db -> Execute("DELETE FROM `alchemy_mill` WHERE `owner`=".$arrDelete[$i][0]);
        $db -> Execute("DELETE FROM `czary` WHERE `gracz`=".$arrDelete[$i][0]);
        $db -> Execute("DELETE FROM `smith_work` WHERE `owner`=".$arrDelete[$i][0]);
        $db -> Execute("DELETE FROM `notatnik` WHERE `gracz`=".$arrDelete[$i][0]);
        $db -> Execute("DELETE FROM `tribe_oczek` WHERE `gracz`=".$arrDelete[$i][0]);
        $arrHouse = $db -> GetRow('SELECT `locator` FROM `houses` WHERE `owner`='.$arrDelete[$i][0]);
        if (!empty($arrHouse))
        {
            $db -> Execute('UPDATE `houses` SET `owner`='.$arrHouse[0].', `locator`=0 WHERE `owner`='.$arrDelete[$i][0]) or $db -> ErrorMsg();
        }
            else
        {
            $db -> Execute("DELETE FROM `houses` WHERE `owner`=".$arrDelete[$i][0]);
        }
        $db -> Execute("DELETE FROM `farms` WHERE `owner`=".$arrDelete[$i][0]);
        $db -> Execute("DELETE FROM `farm` WHERE `owner`=".$arrDelete[$i][0]);
        $db -> Execute("DELETE FROM `jail` WHERE `prisoner`=".$arrDelete[$i][0]);
        $db -> Execute("DELETE FROM `mill_work` WHERE `owner`=".$arrDelete[$i][0]);
        $db -> Execute("DELETE FROM `mill` WHERE `owner`=".$arrDelete[$i][0]);
        $db -> Execute("DELETE FROM `questaction` WHERE `player`=".$arrDelete[$i][0]);
        $db -> Execute("DELETE FROM `amarket` WHERE `seller`=".$arrDelete[$i][0]);
        $db -> Execute("DELETE FROM `astral` WHERE `owner`=".$arrDelete[$i][0]." AND `location`='V'");
        $db -> Execute("DELETE FROM `astral_bank` WHERE `owner`=".$arrDelete[$i][0]." AND `location`='V'");
        $db -> Execute("DELETE FROM `astral_plans` WHERE `owner`=".$arrDelete[$i][0]." AND `location`='V'");
        $db -> Execute("DELETE FROM `lost_pass` WHERE `id`=".$arrDelete[$i][0]);
        $db -> Execute("DELETE FROM `ban` WHERE `type`='ID' AND `amount`='".$arrDelete[$i][0]."'");
        $db -> Execute("DELETE FROM `jeweller` WHERE `owner`=".$arrDelete[$i][0]);
        $db -> Execute("DELETE FROM `jeweller_work` WHERE `owner`=".$arrDelete[$i][0]);
        $db -> Execute("DELETE FROM `ban_mail` WHERE `id`=".$arrDelete[$i][0]);
        $db -> Execute("DELETE FROM `links` WHERE `owner`=".$arrDelete[$i][0]);
        $strFile = 'avatars/'.$arrDelete[$i][1];
        if (is_file($strFile))
        {
            unlink($strFile);
        }
        $arrLibrary = $db -> GetRow('SELECT `author_id` FROM `library` WHERE `author_id`='.$arrDelete[$i][0]);
        if (!empty($arrLibrary))
        {
            $arrOldId = $db -> GetRow('SELECT max(author_id) FROM `library`');
            if ($arrOldId[0] < 1000000)
            {
                $db -> Execute('UPDATE `library` SET `author_id`=1000000 WHERE `author_id`='.$arrDelete[$i][0]) or $db -> ErrorMsg();
            }
            else
            {
                $db -> Execute('UPDATE `library` SET `author_id`='.++$arrOldId[0].' WHERE `author_id`='.$arrDelete[$i][0]) or $db -> ErrorMsg();
            }
        }
    }
    $arrDelete = $db -> GetAll('SELECT `id` FROM `players` WHERE `age`>3 AND `lpv`=0');
    $number2 = count($arrDelete);
    for ($i = 0; $i < $number2; $i++)
    {
        $db -> Execute('DELETE FROM `players` WHERE `id`='.$arrDelete[$i][0]);
    }
    $db -> SetFetchMode($oldFetchMode);
    $smarty -> assign ('Message', YOU_DELETE.' '.$number.' '.INACTIVE.', '.$number2.' '.NEVER_LOGGED.'.');
}

/**
* Send email to all players
*/
if (isset ($_GET['view']) && $_GET['view'] == 'mail')
{
    $smarty -> assign(array("Mailinfo" => MAIL_INFO,
        "Asend" => A_SEND));
    if (isset ($_GET['step']) && $_GET['step'] == 'send')
    {
        $mail1 = $db -> Execute("SELECT email FROM players");
        $adress = '';
        $message = $_POST['message'];
        require_once('mailer/mailerconfig.php');
        while (!$mail1 -> EOF)
        {
            $mail -> AddAddress($mail1 -> fields['email']);
            require_once("languages/".$player -> lang."/admin1.php");
            $subject = M_SUBJECT." ".$gamename;
            if (!$mail -> Send())
            {
                error(M_ERROR."<br /> ".$mail -> ErrorInfo);
            }
            $mail1 -> MoveNext();
            $mail->ClearAddresses();
        }
        $mail1 -> Close();
        error (M_SEND);
    }
}

/**
* Add question on bridge of death
*/
if (isset ($_GET['view']) && $_GET['view'] == 'bridge')
{
    $smarty -> assign(array("Bquestion" => B_QUESTION,
        "Banswer" => B_ANSWER,
        "Aadd" => A_ADD));
    if (isset ($_GET['step']) && $_GET['step'] == 'add')
    {
        $strQuestion = $db -> qstr($_POST['question'], get_magic_quotes_gpc());
        $strAnswer = $db -> qstr($_POST['answer'], get_magic_quotes_gpc());
        $db -> Execute("INSERT INTO bridge (question, answer) VALUES(".$strQuestion.", ".$strAnswer.")") or error (E_DB);
        error (YOU_ADD_Q." <b>".$_POST['question']."</b> ".WITH_A." <b>".$_POST['answer']);
    }
}

/**
* Delete player
*/
if (isset ($_GET['view']) && $_GET['view'] == 'del')
{
    $smarty -> assign(array("Deleteid" => DELETE_ID,
                            "Adeletepl" => A_DELETE_PL));
    if (isset ($_GET['step']) && $_GET['step'] == 'del')
    {
        if ($_POST['did'] != 1)
        {
            $oldFetchMode = $db -> SetFetchMode(ADODB_FETCH_NUM);
            $arrAvatar = $db -> GetRow("SELECT `avatar` FROM `players` WHERE `id`=".$_POST['did']);
            if (!empty($arrAvatar))
            {
                $strFile = 'avatars/'.$arrAvatar[0];
                if (is_file($strFile))
                {
                    unlink($strFile);
                }
            }
            $db -> Execute("DELETE FROM `players` WHERE `id`=".$_POST['did']);
            $db -> Execute("DELETE FROM `core` WHERE `owner`=".$_POST['did']);
            $db -> Execute("DELETE FROM `core_market` WHERE `seller`=".$_POST['did']);
            $db -> Execute("DELETE FROM `equipment` WHERE `owner`=".$_POST['did']);
            $db -> Execute("DELETE FROM `smith` WHERE `owner`=".$_POST['did']);
            $db -> Execute("DELETE FROM `log` WHERE `owner`=".$_POST['did']);
            $db -> Execute("DELETE FROM `mail` WHERE `owner`=".$_POST['did']);
            $arrOutId = $db -> GetRow("SELECT `id` FROM `outposts` WHERE `owner`=".$_POST['did']);
            if (!empty($arrOutId))
            {
                $db -> Execute("DELETE FROM `outpost_mosters` WHERE `outpost`=".$arrOutId[0]);
                $db -> Execute("DELETE FROM `outpost_veterans` WHERE `outpost`=".$arrOutId[0]);
            }
            $db -> Execute("DELETE FROM `outposts` WHERE `owner`=".$_POST['did']);
            $db -> Execute("DELETE FROM `pmarket` WHERE `seller`=".$_POST['did']);
            $db -> Execute("DELETE FROM `hmarket` WHERE `seller`=".$_POST['did']);
            $db -> Execute("DELETE FROM `potions` WHERE `owner`=".$_POST['did']);
            $db -> Execute("DELETE FROM `herbs` WHERE `gracz`=".$_POST['did']);
            $db -> Execute("DELETE FROM `minerals` WHERE `owner`=".$_POST['did']);
            $db -> Execute("DELETE FROM `alchemy_mill` WHERE `owner`=".$_POST['did']);
            $db -> Execute("DELETE FROM `czary` WHERE `gracz`=".$_POST['did']);
            $db -> Execute("DELETE FROM `smith_work` WHERE `owner`=".$_POST['did']);
            $db -> Execute("DELETE FROM `notatnik` WHERE `gracz`=".$_POST['did']);
            $db -> Execute("DELETE FROM `tribe_oczek` WHERE `gracz`=".$_POST['did']);
            $arrHouse = $db -> GetRow("SELECT `locator` FROM `houses` WHERE `owner`=".$_POST['did']);
            if (!empty($arrHouse))
            {
                $db -> Execute("UPDATE `houses` SET `owner`=".$arrHouse[0].", `locator`=0 WHERE `owner`=".$_POST['did']) or $db -> ErrorMsg();
            }
                else
            {
                $db -> Execute("DELETE FROM `houses` WHERE `owner`=".$_POST['did']);
            }
            $db -> Execute("DELETE FROM `farms` WHERE `owner`=".$_POST['did']);
            $db -> Execute("DELETE FROM `farm` WHERE `owner`=".$_POST['did']);
            $db -> Execute("DELETE FROM `jail` WHERE `prisoner`=".$_POST['did']);
            $db -> Execute("DELETE FROM `mill_work` WHERE `owner`=".$_POST['did']);
            $db -> Execute("DELETE FROM `mill` WHERE `owner`=".$_POST['did']);
            $db -> Execute("DELETE FROM `questaction` WHERE `player`=".$_POST['did']);
            $db -> Execute("DELETE FROM `amarket` WHERE `seller`=".$_POST['did']);
            $db -> Execute("DELETE FROM `astral` WHERE `owner`=".$_POST['did']." AND `location`='V'");
            $db -> Execute("DELETE FROM `astral_bank` WHERE `owner`=".$_POST['did']." AND `location`='V'");
            $db -> Execute("DELETE FROM `astral_plans` WHERE `owner`=".$_POST['did']." AND `location`='V'");
            $db -> Execute("DELETE FROM `lost_pass` WHERE `id`=".$_POST['did']);
            $db -> Execute("DELETE FROM `ban` WHERE `type`='ID' AND `amount`='".$_POST['did']."'");
            $db -> Execute("DELETE FROM `jeweller` WHERE `owner`=".$_POST['did']);
            $db -> Execute("DELETE FROM `jeweller_work` WHERE `owner`=".$_POST['did']);
            $db -> Execute("DELETE FROM `ban_mail` WHERE `id`=".$_POST['did']);
            $db -> Execute("DELETE FROM `links` WHERE `owner`=".$_POST['did']);
            $arrLibrary = $db -> GetRow('SELECT `author_id` FROM `library` WHERE `author_id`='.$_POST['did']);
            if (!empty($arrLibrary))
            {
                $arrOldId = $db -> GetRow('SELECT max(author_id) FROM `library`');
                if ($arrOldId[0] < 1000000)
                {
                    $db -> Execute('UPDATE `library` SET `author_id`=1000000 WHERE `author_id`='.$_POST['did']) or $db -> ErrorMsg();
                }
                else
                {
                    $db -> Execute('UPDATE `library` SET `author_id`='.++$arrOldId[0].' WHERE `author_id`='.$_POST['did']) or $db -> ErrorMsg();
                }
            }
            $db -> SetFetchMode($oldFetchMode);
            $smarty -> assign ("Message", YOU_DELETE2." ".$_POST['did']);
        }
            else
        {
            $smarty -> assign ("Message", YOU_NOT_D);
        }
    }
}

/**
* Change player rank
*/
if (isset ($_GET['view']) && $_GET['view'] == 'add')
{
    $smarty -> assign(array("Addid" => ADD_ID,
        "Newrank" => NEW_RANK,
        "Rmember" => R_MEMBER,
        "Rking" => R_KING,
        "Rstaff" => R_STAFF,
        "Rjudge" => R_JUDGE,
        "Rjudge2" => R_JUDGE2,
        "Rjudge3" => R_JUDGE3,
        "Rlawyer" => R_LAWYER,
        "Rbeggar" => R_BEGGAR,
        "Rbarbarian" => R_BARBARIAN,
        "Rscribe" => R_SCRIBE,
        "Rknight" => R_KNIGHT,
        "Rlady" => R_LADY,
        "Rcount" => R_COUNT,
        "Rcount2" => R_COUNT2,
        "Rredactor" => R_REDACTOR,
        "Rinnkeeper" => R_INNKEEPER,
        "Rprocurator" => R_PROCURATOR,
        "Aadd" => A_ADD));
    if (isset ($_GET['step']) && $_GET['step'] == 'add')
    {
        if ($_POST['aid'] != 1)
        {
            $strRank = $db -> qstr($_POST['rank'], get_magic_quotes_gpc());
            $db -> Execute("UPDATE `players` SET `rank`=".$strRank." WHERE `id`=".$_POST['aid']);
            error (YOU_ADD_R." ".$_POST['aid']." ".NEW_RANK." ".$_POST['rank'].".");
        }
    }
}

/**
* Prune forums
*/
if (isset ($_GET['view']) && $_GET['view'] == 'clearf')
{
    if (!isset($_GET['step']))
    {
        $smarty -> assign(array("Fquestion" => F_QUESTION,
            "Ayes" => YES));
    }
    if (isset($_GET['step']) && $_GET['step'] == 'Y')
    {
        $db -> Execute("DELETE FROM topics");
        $db -> Execute("DELETE FROM replies");
        error (FORUM_PRUNE);
    }
}

/**
* Add new items
*/
if (isset ($_GET['view']) && $_GET['view'] == 'equipment')
{
    $smarty -> assign(array("Itemname" => ITEM_NAME,
        "Hasa" => HAS_A,
        "Iweapon" => I_WEAPON,
        "Iarmor" => I_ARMOR,
        "Ihelmet" => I_HELMET,
        "Ilegs" => I_LEGS,
        "Ibow" => I_BOW,
        "Ishield" => I_SHIELD,
        "Iarrows" => I_ARROWS,
        "Istaff" => I_STAFF,
        "Icape" => I_CAPE,
        "Aadd" => A_ADD,
        "Iwith" => I_WITH,
        "Ipower" => I_POWER,
        "Icost" => I_COST,
        "Iminlev" => I_MIN_LEV,
        "Iagi" => I_AGI,
        "Ispeed" => I_SPEED,
        "Irepair" => I_REPAIR,
        "Idur" => I_DUR));
    if (isset ($_GET['step']) && $_GET['step'] == 'add')
    {
        if (empty ($_POST['name']) || empty ($_POST['cost']))
        {
            error (EMPTY_FIELDS);
        }
        if (empty($_POST['zr']))
        {
            $_POST['zr'] = 0;
        }
        if (empty($_POST['szyb']))
        {
            $_POST['szyb'] = 0;
        }
        $strName = $db -> qstr($_POST['name'], get_magic_quotes_gpc());
        if ($_POST['type'] != 'B' && $_POST['type'] != 'R' && $_POST['type'] != 'T' && $_POST['type'] != 'C')
        {
            $sql = "INSERT INTO equipment ( id , owner , name , power , status , type , cost , minlev, zr, szyb, wt, maxwt, repair ) VALUES ( '', '0', ".$strName.", '".$_POST['power']."', 'S', '".$_POST['type']."', '".$_POST['cost']."', '".$_POST['minlev']."', '".$_POST['zr']."', '".$_POST['szyb']."', '".$_POST['maxwt']."', '".$_POST['maxwt']."', ".$_POST['repair']." )";
        }
        if ($_POST['type'] == 'B' || $_POST['type'] == 'R')
        {
            $sql = "INSERT INTO bows (name, power, type, cost, minlev, zr, szyb, maxwt, repair) VALUES(".$strName.", '".$_POST['power']."', '".$_POST['type']."', '".$_POST['cost']."', '".$_POST['minlev']."', '".$_POST['zr']."', '".$_POST['szyb']."', '".$_POST['maxwt']."', ".$_POST['repair'].")";
        }
        if ($_POST['type'] == 'T' || $_POST['type'] == 'C')
        {
            $sql = "INSERT INTO mage_items (id, name, power, type, cost, minlev) VALUES('',".$strName.", '".$_POST['power']."', '".$_POST['type']."', '".$_POST['cost']."', '".$_POST['minlev']."')";
        }
        $db -> Execute($sql) or die($db -> ErrorMsg());
        error (YOU_ADD_ITEM." ".$_POST['name']." ".HAS_A." ".$_POST['type']." ".POWER." ".$_POST['power']." ".COST." ".$_POST['cost']." ".MIN_LEVEL." ".$_POST['minlev']." ".ITEM_LEVEL." ".$_POST['zr']." % ".ITEM_SPEED." ".$_POST['zr']." % ".ITEM_DUR." ".$_POST['maxwt']." .");
    }
}

/**
* Player donation
*/
if (isset ($_GET['view']) && $_GET['view'] == 'donate')
{
	$resources = array ('credits', 'platinum', 'copperore', 'zincore', 'tinore', 'ironore', 'coal',

			'copper', 'bronze', 'brass', 'iron', 'steel', 'pine', 'hazel', 'yew', 'elm', 'crystal', 'adamantium', 'meteor');

	$resources_names = array (CREDITS, strtolower (PLATINUM), COPPERORE, ZINCORE, TINORE, IRONORE, COAL,
			COPPER, BRONZE, BRASS, IRON, STEEL, PINE, HAZEL, YEW, ELM, CRYSTAL, ADAMANTIUM, METEOR);

    $smarty -> assign(array("Donateid" => DONATE_ID,
        "Donateamount" => AMOUNT,
        "Adonate" => A_DONATE,
		'Resources' => $resources,
		'ResourcesNames' => $resources_names,
		));

    if (isset ($_GET['step']) && $_GET['step'] == 'donated')
    {
		is_numeric ($_POST['amount']) or error (ERROR);
		preg_match ('/^[a-z]*$/', $_POST['what']) or error (ERROR);

		if ($_POST['what'] == 'credits' or $_POST['what'] == 'platinum') {
			$table = 'players';
			$id = 'id';
		}
		else {
			$table = 'minerals';
			$id = 'owner';
		}

        $_POST['id'] = (int)$_POST['id'];
        $sql = 'SELECT '.$id.' FROM '.$table.' WHERE '.$id.'='.$_POST['id'];
        $check = $db -> getOne($sql);
        if(!empty($check))
        {
            $db -> Execute('UPDATE '.$table.' SET '.$_POST['what'].'='.$_POST['what'].'+'.$_POST['amount'].' WHERE '.$id.'='.$_POST['id']);
        }
        else
        {
            $db -> Execute('INSERT INTO '.$table.'('.$id.', '.$_POST['what'].') VALUES('.$_POST['id'].', '.$_POST['amount'].')') or die($db -> ErrorMsg());
        }
        error (YOU_SEND_M);
    }
}

/**
* Add new monsters
*/
if (isset ($_GET['view']) && $_GET['view'] == 'monster')
{
    $smarty -> assign(array("Mname" => M_NAME,
        "Mlevel" => M_LEVEL,
        "Mhp" => M_HP,
        "Magi" => M_AGI,
        "Mpower" => M_POWER,
        "Mspeed" => M_SPEED,
        "Mcond" => M_COND,
        "Mmingold" => M_MIN_GOLD,
        "Mmaxgold" => M_MAX_GOLD,
        "Mminexp" => M_MIN_EXP,
        "Mmaxexp" => M_MAX_EXP,
        "Aadd" => A_ADD,
        "Mlocation" => M_LOCATION,
        "Mcity1" => M_CITY1,
        "Mcity2" => M_CITY2,
        "Mcity3" => M_CITY3));
    if (isset ($_GET['step']) && $_GET['step'] == 'monster')
    {
        if (!$_POST['nazwa'] || !$_POST['poziom'] || !$_POST['pz'] || !$_POST['zr'] || !$_POST['sila'] || !$_POST['minzl'] || !$_POST['maxzl'] || !$_POST['minpd'] || !$_POST['maxpd'] || !$_POST['speed'] || !$_POST['endurance'])
        {
            error (EMPTY_FIELDS);
        }
        $strName = $db -> qstr($_POST['nazwa'], get_magic_quotes_gpc());
        $db -> Execute("INSERT INTO monsters (name, level, hp, agility, strength, credits1, credits2, exp1, exp2, speed, endurance, location) VALUES(".$strName.", ".$_POST['poziom'].", ".$_POST['pz'].", ".$_POST['zr'].", ".$_POST['sila'].", ".$_POST['minzl'].", ".$_POST['maxzl'].", ".$_POST['minpd'].", ".$_POST['maxpd'].", ".$_POST['speed'].", ".$_POST['endurance'].", '".$_POST['location']."')");
    }
}

/**
* Add new plans in smith
*/
if (isset ($_GET['view']) && $_GET['view'] == 'kowal')
{
    $smarty -> assign(array("Sname" => S_NAME,
        "Scost" => S_COST,
        "Samount" => S_AMOUNT,
        "Stwohand" => S_TWOHAND,
        "Ayes" => YES,
        "Ano" => NO,
        "Slevel" => S_LEVEL,
        "Stype" => S_TYPE,
        "Sweapon" => S_WEAPON,
        "Sarmor" => S_ARMOR,
        "Shelmet" => S_HELMET,
        "Sshield" => S_SHIELD,
        "Slegs" => S_LEGS,
        "Stwohand" => S_TWOHAND,
        "Aadd" => A_ADD));
    if (isset ($_GET['step']) && $_GET['step'] == 'kowal')
    {
        if (!$_POST['nazwa'] || !$_POST['cena'] || !$_POST['poziom'])
        {
            error (EMPTY_FIELDS);
        }
        $strName = $db -> qstr($_POST['nazwa'], get_magic_quotes_gpc());
        $db -> Execute("INSERT INTO smith (name, cost, level, amount, type, twohand) VALUES(".$strName.", ".$_POST['cena'].", ".$_POST['poziom'].", ".$_POST['amount'].", '".$_POST['type']."', '".$_POST['twohand']."')");
    }
}

/**
* Send message to all players
*/
if (isset ($_GET['view']) && $_GET['view'] == 'poczta')
{
    $smarty -> assign(array("Pmsubject" => PM_SUBJECT,
        "Pmbody" => PM_BODY,
        "Asend" => A_SEND));
    if (isset ($_GET['step']) && $_GET['step'] == 'send')
    {
        if (empty ($_POST['body']) || empty($_POST['subject']))
        {
            error (EMPTY_FIELDS);
        }
        $_POST['subject'] = strip_tags($_POST['subject']);
        $_POST['body'] = strip_tags($_POST['body']);
        $strSubject = $db -> qstr($_POST['subject'], get_magic_quotes_gpc());
        $strBody = $db -> qstr($_POST['body'], get_magic_quotes_gpc());
        $strDate = $db -> DBDate($newdate);
        $odbio = $db -> Execute("SELECT id FROM players");
        $gracze = 0;
        while (!$odbio -> EOF)
        {
            $db -> Execute("INSERT INTO mail (sender, senderid, owner, subject, body, date) VALUES('".$player -> user."','".$player -> id."',".$odbio -> fields['id'].", ".$strSubject.", ".$strBody.", ".$strDate.")") or error(E_DB);
            $gracze = $gracze + 1;
            $odbio -> MoveNext();
        }
        $odbio -> Close();
        error (YOU_SEND_PM." ".$gracze." ".PLAYERS_A);
    }
}

/**
* Add new spells
*/
if (isset ($_GET['view']) && $_GET['view'] == 'czary')
{
    $smarty -> assign(array("Spellname" => SPELL_NAME,
       "Swith" => S_WITH,
       "Sbattle" => S_BATTLE,
       "Sdefense" => S_DEFENSE,
       "Scost" => S_COST,
       "Spower" => S_POWER,
       "Sminlev" => S_MIN_LEV,
       "Hasas" => HAS_A_S,
       "Aadd" => A_ADD));
    if (isset ($_GET['step']) && $_GET['step'] == 'add')
    {
        if (empty($_POST['name']) || empty($_POST['power']) || empty($_POST['cost']) || empty($_POST['minlev']))
        {
            error (EMPTY_FIELDS);
        }
        $strName = $db -> qstr($_POST['name'], get_magic_quotes_gpc());
        $db -> Execute("INSERT INTO czary (nazwa, cena, poziom, typ, obr) VALUES(".$strName.", ".$_POST['cost'].", ".$_POST['minlev'].", '".$_POST['type']."', ".$_POST['power'].")");
        error (YOU_ADD_SPELL." ".$_POST['name']." ".HAS_A_S." ".$_POST['type']." ".POWER_S." ".$_POST['power']." ".COST." ".$_POST['cost']." ".MIN_LEV_S." ".$_POST['minlev']);
    }
}

/**
* Close/open game
*/
if (isset ($_GET['view']) && $_GET['view'] == 'close')
{
    $smarty -> assign(array("Gopen" => G_OPEN,
        "Gclose" => G_CLOSE,
        "Ifclose" => IF_CLOSE,
        "Amake" => A_MAKE));
    if (isset ($_GET['step']) && $_GET['step'] == 'close')
    {
        if ($_POST['close'] == 'close')
        {
            $strReason = $db -> qstr($_POST['reason'], get_magic_quotes_gpc());
            $db -> Execute("UPDATE settings SET value='N' WHERE setting='open'");
            $db -> Execute("UPDATE settings SET value=".$strReason." WHERE setting='close_reason'");
            error (YOU_CLOSE);
        }
        if ($_POST['close'] == 'open')
        {
            $db -> Execute("UPDATE settings SET value='Y' WHERE setting='open'");
            $db -> Execute("UPDATE settings SET value='' WHERE setting='close_reason'");
            error (YOU_OPEN);
        }
    }
}

/**
* Initialization of variables
*/
if (!isset($_GET['view']))
{
    $_GET['view'] = '';
    $arrTitles = array(ACCEPT, BAN, MODERATOR, PLAYERS, GAME, ADMINISTRATION);
    $arrOptions = array(array('addupdate.php', 'admin.php?view=addtext', 'addnews.php', 'admin.php?view=poll'),
                        array('logs', 'ban', 'del', 'delplayers'),
                        array('czat', 'banmail', 'clearf', 'forums', 'innarchive', 'clearc', 'censorship'),
                        array('addreps','donate', 'takeaway', 'jail', 'jailbreak', 'playerquest', 'changenick', 'add', 'donator', 'poczta', 'mail'),
                        array('equipment', 'monster', 'monster2', 'kowal', 'czary', 'mill'),
                        array('admin.php?view=censorship', 'bugtrack.php', 'admin.php?view=bugreport', 'admin.php?view=changelog', 'admin.php?view=register', 'admin.php?view=close'));
    $arrDescriptions = array(array (A_ADDUPDATE, A_ADD_NEWS, A_ADDNEWS, A_POLL),
                             array(A_LOGS, A_BAN, A_DELETE, A_DEL_PLAYERS),
                             array(A_CHAT_BAN, A_BAN_MAIL, A_FORUM_P, A_FORUMS, A_INNARCHIVE, A_CHAT_P, A_CENSORSHIP),
                             array (A_REPUTATION,A_DONATION, A_TAKE, A_JAIL, A_JAILBREAK, A_PLAYERQUEST, A_CHANGE_NICK, A_RANK, A_DONATOR, A_PM, A_MAIL),
                             array(A_EQUIP, A_MONSTERS, A_MONSTER2, A_SMITH, A_SPELLS, A_MILL),
                             array(A_META, A_BUGTRACK, A_BUG_REPORT, A_CHANGELOG, A_REGISTER, A_CLOSE));
    $smarty -> assign(array('Awelcome' => A_WELCOME,
                            'Titles' => $arrTitles,
                            'Options' => $arrOptions,
                            'Descriptions' => $arrDescriptions));
}
    else
{
    $smarty -> assign('Aback', A_BACK);
}

if (!isset($_GET['step']))
{
    $_GET['step'] = '';
}

if (!isset($_GET['action']))
{
    $_GET['action'] = '';
}

/**
* Assign variables and display page
*/
$smarty -> assign(array('View' => $_GET['view'],
                        'Step' => $_GET['step'],
                        'Action' => $_GET['action']));
$smarty -> display('admin.tpl');

require_once('includes/foot.php');

?>
