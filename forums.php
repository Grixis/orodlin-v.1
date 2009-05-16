<?php
/**
 *   File functions:
 *   Forums in game
 *
 *   @name                 : forums.php
 *   @copyright            : (C) 2004,2005,2006 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @author               : mori <ziniquel@users.sourceforge.net>
 *   @author               : eyescream <tduda@users.sourceforge.net>
 *   @author               : Erechail <kuba.stasiak at gmail.com>
 *   @version              : 1.4a
 *   @since                : 17.07.2007
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

$title = 'Forum';
require_once('includes/head.php');

function FormatDate ($milis)
{
	return Date ("y/m/d H:i:s", $milis);
}

/**
* Get the localization for game
*/
require_once('languages/'.$player -> lang.'/forums.php');

/**
* Category list
*/
if (isset ($_GET['view']) && $_GET['view'] == 'categories')
{
    if (isset($_GET['sweep']) && ereg('^[1-9][0-9]*$', $_GET['sweep']))
    {
        if (!isset($_GET['step']))
        {
            $smarty -> assign(array('Sweep' => $_GET['sweep'],
                                    'Fquestion' => YOU_SURE,
                                    'Ayes' => A_YES));
        }
        else
            if ($player-> rank =='Admin' || $player -> rank == 'Staff')
        {
            $objTest = $db -> Execute('SELECT `id`,`perm_write`  FROM `categories` WHERE `id`='.$_GET['sweep']);
            if (strpos($objTest -> fields['perm_write'], 'All') === false && strpos($objTest -> fields['perm_write'], 'Staff') === false && $player -> rank != 'Admin')
            {
                error(NO_PERM);
            }
                else
            {
                $objTopics = $db -> Execute('SELECT `id` FROM `topics` WHERE `cat_id`='.$_GET['sweep'].' AND `sticky`=\'N\'');
                while (!$objTopics -> EOF)
                {
                    $db -> Execute('DELETE FROM `replies` WHERE `topic_id`='.$objTopics -> fields['id']);
                    $objTopics -> MoveNext();
                }
                $db -> Execute('DELETE FROM `topics` WHERE `cat_id`='.$_GET['sweep'].' AND `sticky`=\'N\'');
                $smarty -> assign ('Message',YOU_SWEEPED);
                $smarty -> display('error1.tpl');
            }
            $objTest -> Close();
        }
    }
    /**
     * Display categories viewable for all
     */
    $cat = $db -> Execute('SELECT `id`, `name`, `desc` FROM `categories` WHERE `perm_visit` LIKE \'All;\' AND `lang`=\''.$player -> lang.'\' OR `lang`=\''.$player -> seclang.'\' ORDER BY `id` ASC');
    $arrid = array();
    $arrname = array();
    $arrtopics = array();
    $arrdesc = array();
    $i = 0;
    while (!$cat -> EOF)
    {
        $query = $db -> Execute('SELECT count(*) FROM `topics` WHERE `cat_id`='.$cat -> fields['id']);
        $arrtopics[$i] = $query -> fields['count(*)'];
        $query -> Close();
        $arrid[$i] = $cat -> fields['id'];
        $arrname[$i] = $cat -> fields['name'];
        $arrdesc[$i] = $cat -> fields['desc'];
        $cat -> MoveNext();
        $i ++;
    }
    $cat -> Close();
    /**
     * Display categories with permission to view
     */
    $strPermission = ($player -> rank == 'Admin') ? '%' : $player -> rank;
    $cat = $db -> Execute('SELECT `id`, `name`, `desc` FROM categories WHERE `perm_visit` LIKE \'%'.$strPermission.'%\' AND `lang`=\''.$player -> lang.'\' OR `lang`=\''.$player -> seclang.'\' ORDER BY `id` ASC');
    while (!$cat -> EOF)
    {
        if (in_array($cat -> fields['id'], $arrid))
        {
            $cat -> MoveNext();
            continue;
        }
        $query = $db -> Execute('SELECT count(*) FROM `topics` WHERE `cat_id`='.$cat -> fields['id']);
        $arrtopics[$i] = $query -> fields['count(*)'];
        $query -> Close();
        $arrid[$i] = $cat -> fields['id'];
        $arrname[$i] = $cat -> fields['name'];
        $arrdesc[$i] = $cat -> fields['desc'];
        $cat -> MoveNext();
        $i ++;
    }
    $cat -> Close();
    $smarty -> assign(array('Id' => $arrid,
                            'Name' => $arrname,
                            'Topics1' => $arrtopics,
                            'Description' => $arrdesc,
                            'Tcategory' => T_CATEGORY,
                            'Ttopics' => T_TOPICS,
                            'ASweep' => A_SWEEP));
}

/**
* Topic list
*/
if (isset($_GET['topics']))
{
    if (!ereg("^[1-9][0-9]*$", $_GET['topics']))
    {
        error(ERROR);
    }
    /**
     * Check for permissions
     */
    if ($player -> rank != 'Admin')
    {
        $objPerm = $db -> Execute('SELECT `perm_visit` FROM `categories` WHERE id='.$_GET['topics']);
        if ($objPerm -> fields['perm_visit'] != 'All;')
        {
            $intPerm = strpos($objPerm -> fields['perm_visit'], $player -> rank);
            if ($intPerm === false)
            {
                error(NO_PERM);
            }
        }
        $objPerm -> Close();
    }

		/*
			 Extract category name
			 */

		$objCatName = $db->Execute ('SELECT name FROM categories WHERE id=\''.$_GET['topics'].'\'');
		$CatName = $objCatName->fields ['name'];

    /**
    * Show new topic and replies on forums
    */
    if (!isset($_SESSION['forums']))
    {
        $objLasttime = $db -> Execute('SELECT `forum_time` FROM `players` WHERE `id`='.$player -> id);
        $_SESSION['forums'] = $objLasttime -> fields['forum_time'];
        $objLasttime -> Close();
        $db -> Execute('UPDATE `players` SET `forum_time`='.(time()).' WHERE id='.$player -> id);
    }

    /**
     * Select sticky threads
     */
    $topic = $db -> Execute('SELECT `w_time`, `id`, `topic`, `starter`, `gracz` FROM `topics` WHERE `sticky`=\'Y\' AND `cat_id`='.$_GET['topics'].' AND `lang`=\''.$player -> lang.'\' OR `lang`=\''.$player -> seclang.'\' ORDER BY `id` ASC');
    $arrid = array();
    $arrtopic = array();
    $arrstarter = array();
	$arrStarterID = array ();
    $arrreplies = array();
    $arrNewtopic = array();
	$arrDates = array ();
    $i = 0;
    while (!$topic -> EOF)
    {
        $arrNewtopic[$i] = ($topic -> fields['w_time'] > $_SESSION['forums']) ? 'Y' : 'N';
        $query = $db -> Execute('SELECT `w_time` FROM `replies` WHERE `topic_id`='.$topic -> fields['id']);
        if ($arrNewtopic[$i] == 'N')
        {
            while (!$query -> EOF)
            {
                if ($query -> fields['w_time'] > $_SESSION['forums'])
                {
                    $arrNewtopic[$i] = 'Y';
                    break;
                }
                $query -> MoveNext();
            }
        }
        $replies = $query -> RecordCount();
        $query -> Close();
        $arrid[$i] = $topic -> fields['id'];
        $arrtopic[$i] = "<b>".$topic -> fields['topic']."</b>";
		//$arrtopic[$i] = $topic->fields['topic'];

		//TODO:
		/*
		   Przejściowe, jak poznikają tematy z dodanymi w treści datami to się usunie. :)
		   */
		$arrtopic[$i] = preg_replace ('/^<b>[0-9][0-9]-[01][0-9]-[0-3][0-9]<\/b>/', '', $arrtopic[$i]);			
		//end TODO


		$arrDates[$i] = FormatDate ($topic->fields['w_time']);

        $arrstarter[$i] = $topic -> fields['starter'];
		$arrStarterID[$i] = $topic->fields['gracz'];
        $arrreplies[$i] = $replies;
        $topic -> MoveNext();
        $i = $i + 1;
    }
    $topic -> Close();

    /**
     * Select normal threads
     */
    $topic = $db -> Execute('SELECT `w_time`, `id`, `topic`, `starter`, `gracz` FROM `topics` WHERE `sticky`=\'N\' AND `cat_id`='.$_GET['topics'].' AND `lang`=\''.$player -> lang.'\' OR `lang`=\''.$player -> seclang.'\' ORDER BY `id` ASC');
    while (!$topic -> EOF)
    {
        $arrNewtopic[$i] = ($topic -> fields['w_time'] > $_SESSION['forums']) ? 'Y' : 'N';
        $query = $db -> Execute('SELECT `w_time` FROM `replies` WHERE `topic_id`='.$topic -> fields['id']);
        if ($arrNewtopic[$i] == 'N')
        {
            while (!$query -> EOF)
            {
                if ($query -> fields['w_time'] > $_SESSION['forums'])
                {
                    $arrNewtopic[$i] = 'Y';
                    break;
                }
                $query -> MoveNext();
            }
        }
        $replies = $query -> RecordCount();
        $query -> Close();
        $arrid[$i] = $topic -> fields['id'];
        $arrtopic[$i] = $topic -> fields['topic'];

		//TODO:
		/*
		   Przejściowe, jak poznikają tematy z dodanymi w treści datami to się usunie. :)
		   */
		$arrtopic[$i] = preg_replace ('/^<b>[0-9][0-9]-[01][0-9]-[0-3][0-9]<\/b>/', '', $arrtopic[$i]);			
		
		$arrDates[$i] = FormatDate ($topic->fields['w_time']);


        $arrstarter[$i] = $topic -> fields['starter'];
		$arrStarterID[$i] = $topic->fields['gracz'];
        $arrreplies[$i] = $replies;
        $topic -> MoveNext();
        $i = $i + 1;
    }
    $topic -> Close();
    $smarty -> assign(array('Category' => $_GET['topics'],
				'CategoryName' => $CatName,
        'Id' => $arrid,
        'Topic1' => $arrtopic,
        'Starter1' => $arrstarter,
		'StarterID'	=>	$arrStarterID,
        'Replies1' => $arrreplies,
		'Dates'	=>	$arrDates,
		'Tcategory' => T_CATEGORY,
        'Ttopic' => T_TOPIC,
        'Tauthor' => T_AUTHOR,
        'Treplies' => T_REPLIES,
        'Addtopic' => ADD_TOPIC,
        'Ttext' => T_TEXT,
        'Aback' => A_BACK,
        'Tocategories' => TO_CATEGORIES,
        'Asearch' => A_SEARCH,
        'Tword' => T_WORD,
        'Tsticky' => T_STICKY,
        'Newtopic' => $arrNewtopic));
}

/**
* View topic
*/
if (isset($_GET['topic']))
{
    if (!ereg("^[1-9][0-9]*$", $_GET['topic']))
    {
        error(ERROR);
    }
    if (isset($_GET['quote']))
    {
        if (!ereg("^[1-9][0-9]*$", $_GET['quote']))
        {
            error(ERROR);
        }
    }
    $topicinfo = $db -> Execute('SELECT * FROM `topics` WHERE `id`='.$_GET['topic']);
    if (!$topicinfo -> fields['id'])
    {
        error (NO_TOPIC);
    }
    /**
     * Check for permissions
     */
    if ($player -> rank != 'Admin')
    {
        $objPerm = $db -> Execute('SELECT `perm_visit` FROM `categories` WHERE `id`='.$topicinfo -> fields['cat_id']);
        if ($objPerm -> fields['perm_visit'] != 'All;')
        {
            $intPerm = strpos($objPerm -> fields['perm_visit'], $player -> rank);
            if ($intPerm === false)
            {
                error(NO_PERM);
            }
        }
        $objPerm -> Close();
    }
    $strStickyaction = ($topicinfo -> fields['sticky'] == 'N') ? ' (<a href="forums.php?sticky='.$topicinfo -> fields['id'].'&amp;action=Y">'.A_STICKY.'</a>)' : ' (<a href="forums.php?sticky='.$topicinfo -> fields['id'].'&amp;action=N">'.A_UNSTICKY.'</a>)';
    $smarty -> assign ('Action', ($player -> rank == 'Admin' || $player -> rank == 'Staff') ? ' (<a href="forums.php?kasuj1='.$topicinfo -> fields['id'].'">'.A_DELETE.'</a>)'.$strStickyaction : '' );

    $text1 = wordwrap($topicinfo -> fields['body'],45,"\n",1);
    $strReplytext = isset($_GET['quotet']) ? '[quote]'.$text1.'[/quote]' : R_TEXT;
    $reply = $db -> Execute('SELECT * FROM `replies` WHERE `topic_id`='.$topicinfo -> fields['id'].' ORDER BY `id` ASC');
    $arrstarter = array();
    $arrplayerid = array();
    $arrtext = array();
    $arraction = array();
    $arrRid = array();
	$arrDates = array ();

	/*
	   Extract category name.
	  */

	$objCatName = $db->Execute ('SELECT name FROM categories WHERE id=\''.$topicinfo->fields['cat_id'].'\'');
	$CatName = $objCatName->fields ['name'];


	//TODO:
	/*
	   Przejściowe, jak poznikają tematy z dodanymi w treści datami to się usunie. :)
	   */
	$topicinfo->fields['topic'] = preg_replace ('/^<b>[0-9][0-9]-[01][0-9]-[0-3][0-9]<\/b>/', '', $topicinfo->fields['topic']);
	$TopicDate = FormatDate ($topicinfo->fields['w_time']);

    $i = 0;
    while (!$reply -> EOF)
    {
        $arrstarter[$i] = $reply -> fields['starter'];
        $arrplayerid[$i] = $reply -> fields['gracz'];
        $arraction[$i] = ($player -> rank == 'Admin' || $player -> rank == 'Staff') ? '(<a href="forums.php?kasuj='.$reply -> fields['id'].'">'.A_DELETE.'</a>)' : '';
        $text = wordwrap($reply -> fields['body'],45,"\n",1);

		//TODO: wywalić później, jw.
		$text = preg_replace ('/^<b>[0-9][0-9]-[01][0-9]-[0-3][0-9]<\/b>/', '', $text);

        if (isset($_GET['quote']) && $_GET['quote'] == $reply -> fields['id'])
        {
            $strText = preg_replace("/[0-9][0-9]-[0-9][0-9]-[0-9][0-9]/", "", $reply -> fields['body']);
            $strText = str_replace("<b></b><br />", "", $strText);
            $strReplytext = '[quote]'.$strText.'[/quote]';
        }
        $arrtext[$i] = $text;
        $arrRid[$i] = $reply -> fields['id'];
		$arrDates[$i] = FormatDate ($reply->fields['w_time']);
        $reply -> MoveNext();
        $i = $i + 1;
    }
    $reply -> Close();
    $smarty -> assign(array('Topic2' => $topicinfo -> fields['topic'],
        'Starter' => $topicinfo -> fields['starter'],
        'Playerid' => $topicinfo -> fields['gracz'],
        'Category' => $topicinfo -> fields['cat_id'],
		'TopicDate'	=>	$TopicDate,
		'CategoryName'	=>	$CatName,
		'Tcategory'	=>	T_CATEGORY,
		'Tocategories'	=>	TO_CATEGORIES,
		'Totopics'	=>	TO_TOPICS,
		'or'	=>	A_OR,
		'Dates'	=>	$arrDates,
        'Ttext' => $text1,
        'Rstarter' => $arrstarter,
        'Rplayerid' => $arrplayerid,
        'Rtext' => $arrtext,
        'Action2' => $arraction,
        'Id' => $topicinfo -> fields['id'],
        'Rid' => $arrRid,
        'Writeby' => WRITE_BY,
        'Wid' => W_ID,
        'Areply' => A_REPLY,
        'Rtext2' => $strReplytext,
        'Aback' => A_BACK,
        'Aquote' => A_QUOTE,
        'Write' => WRITE));
    $topicinfo -> Close();
}

/**
* Add topic
*/
if (isset ($_GET['action']) && $_GET['action'] == 'addtopic')
{
    if (empty ($_POST['title2']) || empty ($_POST['body']))
    {
        error (EMPTY_FIELDS);
    }
    /**
     * Check for permissions
     */
    if ($player -> rank != 'Admin')
    {
        $objPerm = $db -> Execute('SELECT `perm_write` FROM `categories` WHERE `id`='.$_POST['catid']);
        if ($objPerm -> fields['perm_write'] != 'All;')
        {
            $intPerm = strpos($objPerm -> fields['perm_write'], $player -> rank);
            if ($intPerm === false)
            {
                error(NO_PERM2);
            }
        }
        $objPerm -> Close();
    }
    if (isset($_POST['sticky']))
    {
        if ($player -> rank != 'Admin' && $player -> rank != 'Staff')
        {
            error(NO_PERM3);
        }
        $strSticky = 'Y';
    }
        else
    {
        $strSticky = 'N';
    }
    $_POST['title2'] = strip_tags($_POST['title2']);
    require_once('includes/bbcode.php');
    $_POST['body'] = censorship($_POST['body']);
    $_POST['body'] = bbcodetohtml($_POST['body']);
    $_POST['title2'] = censorship($_POST['title2']);
    //$_POST['title2'] = '<b>'.$data.'</b> '.$_POST['title2']; !!!
    $strBody = $db -> qstr($_POST['body'], get_magic_quotes_gpc());
    $strTitle = $db -> qstr($_POST['title2'], get_magic_quotes_gpc());
    $db -> Execute('INSERT INTO `topics` (`topic`, `body`, `starter`, `gracz`, `cat_id`, `w_time`, `sticky`) VALUES('.$strTitle.', '.$strBody.', \''.$player -> user.'\', '.$player -> id.', '.$_POST['catid'].', '.(time()).', \''.$strSticky.'\')') or die('Could not add topic.');
    error (TOPIC_ADD.' <a href="forums.php?topics='.$_POST['catid'].'">'.TO_BACK);
}

/**
* Add reply
*/
if (isset($_GET['reply']))
{
    $query = $db -> Execute('SELECT `cat_id` FROM `topics` WHERE `id`='.$_GET['reply']);
    /**
     * Check for permissions
     */
    if ($player -> rank != 'Admin')
    {
        $objPerm = $db -> Execute('SELECT `perm_write` FROM `categories` WHERE `id`='.$query -> fields['cat_id']);
        if ($objPerm -> fields['perm_write'] != 'All;')
        {
            $intPerm = strpos($objPerm -> fields['perm_write'], $player -> rank);
            if ($intPerm === false)
            {
                error(NO_PERM2);
            }
        }
        $objPerm -> Close();
    }
    $exists = $query -> RecordCount();
    $intCatID = $query -> fields['cat_id'];
    $query -> Close();
    if ($exists <= 0)
    {
        error (NO_TOPIC);
    }
    if (empty ($_POST['rep']))
    {
        error (EMPTY_FIELDS);
    }
    require_once('includes/bbcode.php');
    $_POST['rep'] = censorship($_POST['rep']);
    $_POST['rep'] = bbcodetohtml($_POST['rep']);
    //$_POST['rep'] = '<b>'.$data.'</b><br />'.$_POST['rep']; !!! Tak!
    $strBody = $db -> qstr($_POST['rep'], get_magic_quotes_gpc());
    $db -> Execute('INSERT INTO `replies` (`starter`, `topic_id`, `body`, `gracz`, `w_time`) VALUES(\''.$player -> user.'\', '.$_GET['reply'].', '.$strBody.', '.$player -> id.', '.(time()).')');// or die('Could not add reply.');
    error (REPLY_ADD.' <a href="forums.php?topic='.$_GET['reply'].'">'.A_HERE.'</a> '.RETURN1.' <a href="forums.php?topics='.$intCatID.'">'.A_HERE.'</a> '.RETURN2);
}

/**
 * Sticky/Unsticky topics
 */
if (isset($_GET['sticky']))
{
    if ($player -> rank != 'Admin' && $player -> rank != 'Staff' || !ereg("^[1-9][0-9]*$", $_GET['sticky'] || $_GET['action'] != 'Y' && $_GET['action'] != 'N'))
    {
        error(ERROR);
    }
    $db -> Execute('UPDATE `topics` SET `sticky`=\''.$_GET['action'].'\' WHERE `id`='.$_GET['sticky']);
    error((($_GET['action'] == 'Y') ? YOU_STICKY : YOU_UNSTICKY).' <a href="forums.php?topic='.$_GET['sticky'].'">'.A_BACK.'</a>');
}

/**
* Delete post
*/
if (isset($_GET['kasuj']))
{
    if ($player -> rank != 'Admin' && $player -> rank != 'Staff')
    {
        error(ERROR);
    }
    if (!ereg("^[1-9][0-9]*$", $_GET['kasuj']))
    {
        error(ERROR);
    }
    $tid = $db -> Execute('SELECT `topic_id` FROM `replies` WHERE `id`='.$_GET['kasuj']);
    $db -> Execute('DELETE FROM `replies` WHERE `id`='.$_GET['kasuj']);
    error (POST_DEL.' <a href="forums.php?topic='.$tid -> fields['topic_id'].'">'.A_BACK.'</a>');
}

/**
* Delete topic
*/
if (isset($_GET['kasuj1']))
{
    if ($player -> rank != 'Admin' && $player -> rank != 'Staff')
    {
        error(ERROR);
    }
    if (!ereg("^[1-9][0-9]*$", $_GET['kasuj1']))
    {
        error(ERROR);
    }
    $cid = $db -> Execute('SELECT `cat_id` FROM `topics` WHERE `id`='.$_GET['kasuj1']);
    $db -> Execute('DELETE FROM `replies` WHERE `topic_id`='.$_GET['kasuj1']);
    $db -> Execute('DELETE FROM `topics` WHERE `id`='.$_GET['kasuj1']);
    error (TOPIC_DEL.' <a href="forums.php?topics='.$cid -> fields['cat_id'].'">'.A_BACK.'</a>');
}

/**
* Search words
*/
if (isset($_GET['action']) && $_GET['action'] == 'search')
{
    if (empty($_POST['search']))
    {
        error(EMPTY_FIELDS);
    }
    if (!ereg("^[1-9][0-9]*$", $_POST['catid']))
    {
        error(ERROR);
    }
    $strSearch = strip_tags($_POST['search']);

    /**
    * Search string in topics
    */
    $objResult = $db -> Execute('SELECT `id` FROM `topics` WHERE `cat_id`='.$_POST['catid'].' AND `topic` LIKE \'%'.$strSearch.'%\' OR `body` LIKE \'%'.$strSearch.'%\'');
    $arrResult = array();
    $i = 0;
    while (!$objResult -> EOF)
    {
        $arrResult[$i] = $objResult -> fields['id'];
        $i = $i + 1;
        $objResult -> MoveNext();
    }
    $objResult -> Close();

    /**
    * Search string in replies
    */
    $objTopics = $db -> Execute('SELECT `id` FROM `topics` WHERE `cat_id`='.$_POST['catid']);
    $intTest = 0;
    while (!$objTopics -> EOF)
    {
        $objResult2 = $db -> Execute('SELECT `topic_id` FROM `replies` WHERE `topic_id`='.$objTopics -> fields['id'].' AND body LIKE \'%'.$strSearch.'%\'');
        foreach ($arrResult as $intResult)
        {
            if ($intResult == $objResult2 -> fields['topic_id'])
            {
                $intTest = 1;
                break;
            }
        }
        if (!$intTest && $objResult2 -> fields['topic_id'])
        {
            $arrResult[$i] = $objResult2 -> fields['topic_id'];
            $i = $i + 1;
            $intTest = 0;
        }
        $objResult2 -> Close();
        $objTopics -> MoveNext();
    }
    $objTopics -> Close();

    /**
    * Display search result
    */
    $arrTopic = array();
    $arrId = array();
    $i = 0;
    foreach ($arrResult as $intResult)
    {
        $objTopic = $db -> Execute('SELECT `id`, `topic`, `cat_id` FROM `topics` WHERE `id`='.$intResult);
        $objPerm = $db -> Execute('SELECT `perm_visit` FROM `categories` WHERE `id`='.$objTopic -> fields['cat_id']);
        if ($objPerm -> fields['perm_visit'] != 'All;' && $player -> rank != 'Admin')
        {
            $intPerm = strpos($objPerm -> fields['perm_visit'], $player -> rank);
            if ($intPerm === false)
            {
                continue;
            }
        }
        $objPerm -> Close();
        $arrTopic[$i] = $objTopic -> fields['topic'];
        $arrId[$i] = $objTopic -> fields['id'];
        $i = $i + 1;
        $objTopic -> Close();
    }
    $smarty -> assign(array('Category' => $_POST['catid'],
        'Aback' => A_BACK,
        'Amount' => $i,
        'Ttopic' => $arrTopic,
        'Tid' => $arrId,
        'Nosearch' => NO_SEARCH,
        'Youfind' => YOU_FIND));
}

/**
* Initialization of variables
*/
if (!isset($_GET['topics']))
{
    $_GET['topics'] = '';
}

if (!isset($_GET['topic']))
{
    $_GET['topic'] = '';
}

if (!isset($_GET['view']))
{
    $_GET['view'] = '';
}

if (!isset($_GET['action']))
{
    $_GET['action'] = '';
}

/**
* Assign variables to template and display page
*/
$smarty -> assign(array('View' => $_GET['view'],
    'Topics' => $_GET['topics'],
    'Topic' => $_GET['topic'],
    'Action3' => $_GET['action'],
    'Rank' => $player -> rank));
$smarty -> display ('forums.tpl');

require_once("includes/foot.php");
?>
