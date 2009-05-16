<?php
/**
 *   File functions:
 *   Library with players texts
 *
 *   @name                 : library.php
 *   @copyright            : (C) 2004,2005,2006, 2007 Orodlin Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @author               : Zamareth <zamareth@gmail.com>
 *   @version              : 1.3
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
// $Id: library.php 566 2006-09-13 09:31:08Z thindil $

$title = 'Biblioteka';
require_once('includes/head.php');

/**
* Get the localization for game
*/
require_once('languages/'.$player -> lang.'/library.php');

if ($player -> location != 'Altara' && $player -> location != 'Ardulith')
{
    error (ERROR);
}

$strQuery = 'lang=\''.$player -> lang.'\'';
if ($player -> lang != $player -> seclang)
{
    $strQuery .= ' OR lang=\''.$player -> seclang.'\'';
}

function polishgrammar($intNumber)
{
    if( $intNumber == 1)
    {
        return TEXT1;
    }
    if( $intNumber > 4 && $intNumber < 22)
    {
        return TEXT3;
    }
    if( ($intNumber % 10 > 1) && ($intNumber % 10 < 5))
    {
        return TEXT2;
    }
    return TEXT3;
}
/**
* Main menu
*/
if (!isset($_GET['step']))
{
    $oldFetchMode = $db -> SetFetchMode(ADODB_FETCH_NUM);
    $arrTextNot = $db -> GetRow('SELECT count(id) FROM `library` WHERE `added`=\'N\' AND '.$strQuery);
    $arrTales = $db -> GetRow('SELECT count(id) FROM `library` WHERE `type`=\'tale\' AND `added`=\'Y\' AND '.$strQuery);
    $arrPoetry = $db -> GetRow('SELECT count(id) FROM `library` WHERE `type`=\'poetry\' AND `added`=\'Y\' AND '.$strQuery);
    $db -> SetFetchMode($oldFetchMode);
    $strWait = WAIT1;
    if($arrTextNot[0] > 1 && $arrTextNot[0] < 5 || $arrTextNot[0] > 20 & ($arrTextNot[0] % 10 > 1 && $arrTextNot[0] % 10< 5) )
    {
        $strWait = WAIT2;
    }
    $intTextIn = $arrTales[0] + $arrPoetry[0];
    $smarty -> assign('TextNot', $arrTextNot[0].' '.polishgrammar($arrTextNot[0]));
    $smarty -> assign_by_ref ('TextIn', $intTextIn);
    $smarty -> assign_by_ref ('AmountTales', $arrTales[0]);
    $smarty -> assign_by_ref ('AmountPoetry', $arrPoetry[0]);
    $smarty -> assign('Welcome3', polishgrammar($intTextIn).', '.WELCOME3.' '.$strWait);
    $smarty -> assign('Tales_Text', polishgrammar($arrTales[0]));
    $smarty -> assign('Poetry_Text', polishgrammar($arrPoetry[0]));
    unset($arrTales, $arrPoetry, $arrTextNot);
}
/**
* Add text to library (simple user)
*/
if (isset($_GET['step']) && $_GET['step'] == 'add')
{
    $path = 'languages/';
    $dir = opendir($path);
    $arrLanguage = array();
    while ($file = readdir($dir))
    {
        if (preg_match("/\b[a-z]{2}\b/", $file))
        {
            $arrLanguage[] = $file;
        }
    }
    closedir($dir);
    $arrType = array(T_TYPE1, T_TYPE2);
    $smarty -> assign_by_ref ('Llang', $arrLanguage);

    if (isset($_GET['step2']))
    {
        if (!in_array($_POST['type'], $arrType))
        {
            error(ERROR);
        }
        if (empty($_POST['ttitle']) || empty($_POST['body']))
        {
            error(EMPTY_FIELDS);
        }
        $strType = $_POST['type'] == T_TYPE1 ? 'tale' : 'poetry';
        require_once('includes/bbcode.php');
        $_POST['body'] = censorship($_POST['body']);
        $_POST['body'] = bbcodetohtml($_POST['body']);
        $_POST['ttitle'] = censorship($_POST['ttitle']);
        $strTitle = $db -> qstr($_POST['ttitle'], get_magic_quotes_gpc());
        $strBody = $db -> qstr($_POST['body'], get_magic_quotes_gpc());
        $db -> Execute('INSERT INTO `library` (`title`, `body`, `type`, `lang`, `author`, `author_id`) VALUES('.$strTitle.', '.$strBody.', \''.$strType.'\', \''.$_POST['lang'].'\', \''.$player -> user.'\', '.$player -> id.')');
        error(YOU_ADD);
    }
}

/**
* Add text to library (librarian)
*/
if (isset($_GET['step']) && $_GET['step'] == 'addtext')
{
    if ($player -> rank != 'Bibliotekarz' && $player -> rank != 'Admin')
    {
        error(NO_PERM);
    }
    $oldFetchMode = $db -> SetFetchMode(ADODB_FETCH_NUM);
    $arrText = $db -> GetAll('SELECT `id`, `title`, `author`, `author_id` FROM `library` WHERE added=\'N\' AND '.$strQuery);
    $db -> SetFetchMode($oldFetchMode);
    $smarty -> assign_by_ref ('TextList', $arrText);
    unset($arrText);
    /**
    * Modify text
    */
    if (isset($_GET['action']) && $_GET['action'] == 'modify')
    {
        if (!preg_match("/^[1-9][0-9]*$/", $_GET['text']))
        {
            error(ERROR);
        }
        $oldFetchMode = $db -> SetFetchMode(ADODB_FETCH_NUM);
        $arrText = $db -> GetRow('SELECT `id`, `title`, `body`, `type` FROM `library` WHERE `id`='.$_GET['text']);
        $db -> SetFetchMode($oldFetchMode);
        require_once('includes/bbcode.php');
        $arrText[2] = htmltobbcode($arrText[2]);
        $smarty -> assign_by_ref ('TextToModify', $arrText);
        if (isset($_POST['tid']))
        {
            if (!preg_match("/^[1-9][0-9]*$/", $_POST['tid']))
            {
                error(ERROR);
            }
            if (empty($_POST['ttitle']) || empty($_POST['body']))
            {
                error(EMPTY_FIELDS);
            }
            $strType = $_POST['type'] == T_TYPE1 ? 'tale' : 'poetry';
            $_POST['body'] = bbcodetohtml($_POST['body']);
            $strTitle = $db -> qstr($_POST['ttitle'], get_magic_quotes_gpc());
            $strBody = $db -> qstr($_POST['body'], get_magic_quotes_gpc());
            $db -> Execute('UPDATE `library` SET `title`='.$strTitle.', `body`='.$strBody.', `type`=\''.$strType.'\' WHERE `id`='.$_POST['tid']);
            error (MODIFIED);
        }
    }
    /**
    * Add or delete texts in library
    */
    if (isset($_GET['action']) && ($_GET['action'] == 'add' || $_GET['action'] == 'delete'))
    {
        if (!preg_match("/^[1-9][0-9]*$/", $_GET['text']))
        {
            error(ERROR);
        }
        $arrText = $db -> GetRow('SELECT `id` FROM `library` WHERE `id`='.$_GET['text']);
        if (!$arrText['id'])
        {
            error(NO_TEXT);
        }
        if ($_GET['action'] == 'add')
        {
            $db -> Execute('UPDATE `library` SET `added`=\'Y\' WHERE `id`='.$_GET['text']);
            error(ADDED);
        }
            else
        {
            $db -> Execute('DELETE FROM `library` WHERE `id`='.$_GET['text']);
            error(DELETED);
        }
    }
}

/**
* Display texts in library
*/
if (isset($_GET['step']) && ($_GET['step'] == 'tales' || $_GET['step'] == 'poetry'))
{
    if ($_GET['step'] == 'tales')
    {
        $strType = 'tale';
        $strInfo = T_INFO1;
    }
    else
    {
        $strType = 'poetry';
        $strInfo = T_INFO2;
    }
    $oldFetchMode = $db -> SetFetchMode(ADODB_FETCH_NUM);
    $arrAmount = $db -> GetRow('SELECT count(id) FROM `library` WHERE `type`=\''.$strType.'\' AND `added`=\'Y\' AND '.$strQuery);
    $db -> SetFetchMode($oldFetchMode);
    $smarty -> assign_by_ref ('TextAmount', $arrAmount[0]);
    $smarty -> assign_by_ref ('TextType', $strInfo);
    unset($arrAmount);
    /**
    * Display sorted texts
    */
    if (isset($_POST['sort']) && $_POST['sort'] != 'author')
    {
        $arrSort = array('title', 'id');
        if (!in_array($_POST['sort'], $arrSort))
        {
            error(ERROR);
        }
        $oldFetchMode = $db -> SetFetchMode(ADODB_FETCH_NUM);
        $arrList = $db -> GetAll('SELECT `id`, `title`, `author`, `author_id` FROM `library` WHERE `added`=\'Y\' AND `type`=\''.$strType.'\' AND '.$strQuery.' ORDER BY '.$_POST['sort'].' DESC') or error($db -> ErrorMsg());
        for($i = 0, $k = count($arrList); $i < $k; $i++)
        {
            $arrCommentAmount = $db -> GetRow('SELECT count(id) FROM `lib_comments` WHERE `textid`='.$arrList[$i][0]);
            $arrComments[] = $arrCommentAmount[0];
        }
        $db -> SetFetchMode($oldFetchMode);
        $smarty -> assign_by_ref ('TextList', $arrList);
        $smarty -> assign_by_ref ('Comments', $arrComments);
        unset($arrList, $arrComments);
    }
    /**
    * Display authors list
    */
    if (!isset($_GET['author']) && (!isset($_POST['sort']) || (isset($_POST['sort']) && $_POST['sort'] == 'author')))
    {
        $oldFetchMode = $db -> SetFetchMode(ADODB_FETCH_NUM);
        $arrAuthor = $db -> GetAll('SELECT DISTINCT `author`, `author_id`, count(id) FROM `library` WHERE `added`=\'Y\' AND `type`=\''.$strType.'\' AND '.$strQuery.' GROUP BY `author_id`') or error($db -> ErrorMsg());
        $db -> SetFetchMode($oldFetchMode);
        $smarty -> assign_by_ref ('Tauthor', $arrAuthor);
        unset($arrAuthor);
    }
    /**
    * Display texts selected author
    */
    if (isset($_GET['author']))
    {
        if (!preg_match("/^[1-9][0-9]*$/", $_GET['author']))
        {
            error(ERROR);
        }
        $oldFetchMode = $db -> SetFetchMode(ADODB_FETCH_NUM);
        $arrAuthor = $db -> GetAll('SELECT DISTINCT `author`, `author_id` FROM `library` WHERE `author_id`='.$_GET['author']) or ($db -> ErrorMsg());
        for ($i = 0, $k = count($arrAuthor); $i < $k; $i++)
        {
            $arrTexts[] = $arrList = $db -> GetAll('SELECT `id`, `title` FROM `library` WHERE `added`=\'Y\' AND `type`=\''.$strType.'\' AND `author`=\''.$arrAuthor[$i][0].'\' AND '.$strQuery.' ORDER BY `id` DESC') or error($db -> ErrorMsg());
            for ($m = 0, $n = count($arrList); $m < $n; $m++)
            {
                $arrComments[] = $arrAmount = $db -> GetRow('SELECT count(id) FROM `lib_comments` WHERE `textid`='.$arrList[$m][0]);
            }
        }
        $db -> SetFetchMode($oldFetchMode);
        $smarty -> assign_by_ref ('AuthorList', $arrAuthor);
        $smarty -> assign_by_ref ('TextsList', $arrTexts);
        $smarty -> assign_by_ref ('CommentsAmount', $arrComments);
        unset($arrAuthors, $arrTexts, $arrComments);
    }
    /**
    * Display selected text
    */
    if (isset($_GET['text']))
    {
        if (!preg_match("/^[1-9][0-9]*$/", $_GET['text']))
        {
            error(ERROR);
        }
        $oldFetchMode = $db -> SetFetchMode(ADODB_FETCH_NUM);
        $arrText = $db -> GetRow('SELECT `id`, `title`, `body`, `author`, `author_id` FROM `library` WHERE id='.$_GET['text']);
        $db -> SetFetchMode($oldFetchMode);
        if (!$arrText[0])
        {
            error(NO_TEXT);
        }
        $smarty -> assign_by_ref ('TextData', $arrText);
        unset($arrText);
    }
}

/**
* Comments to text
*/
if (isset($_GET['step']) && $_GET['step'] == 'comments')
{
    $smarty -> assign('Amount', '');

    require_once('includes/comments.php');
    /**
    * Display comments
    */
    if (!isset($_GET['action']))
    {
        displaycomments($_GET['text'], 'library', 'lib_comments', 'textid');
        $smarty -> assign(array('Tauthor' => $arrAuthor,
            'Tbody' => $arrBody,
            'Amount' => $i,
            'Cid' => $arrId,
            'Tdate' => $arrDate));
    }

    /**
    * Add comment
    */
    if (isset($_GET['action']) && $_GET['action'] == 'add')
    {
        addcomments($_POST['tid'], 'lib_comments', 'textid');
        $smarty -> assign('Message', '<p>'.BACK_TO.' <a hfef="library.php?step=tales&text='.$_POST['tid'].'">'.A_TEXT.''.I_OR.' <a hfef="library.php?step=comments&text='.$_POST['tid'].'">'.ACOMMENTS.'</a>.</p>');
        $smarty -> display('error1.tpl');
    }

    /**
    * Delete comment
    */
    if (isset($_GET['action']) && $_GET['action'] == 'delete')
    {
        deletecomments('lib_comments');
    }
}

/**
* Initialization of variable
*/
if (!isset($_GET['step']))
{
    $_GET['step'] = '';
}
if (!isset($_GET['action']))
{
    $_GET['action'] = '';
}
if (!isset($_GET['text']))
{
    $_GET['text'] = '';
}
if (!isset($_GET['author']))
{
    $_GET['author'] = '';
}
if (!isset($_POST['sort']))
{
    $_POST['sort'] = '';
}

/**
* Assign variables to template and display page
*/
$smarty -> assign_by_ref ('Rank', $player -> rank);
$smarty -> assign_by_ref ('Step', $_GET['step']);
$smarty -> assign_by_ref ('Action', $_GET['action']);
$smarty -> assign_by_ref ('Text', $_GET['text']);
$smarty -> assign_by_ref ('Author', $_GET['author']);
$smarty -> assign_by_ref ('Sort', $_POST['sort']);
$smarty -> display ('library.tpl');

require_once('includes/foot.php');
?>
