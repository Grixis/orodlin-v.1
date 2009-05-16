<?php
/**
 *   File functions:
 *   Show game news
 *
 *   @name				 : news.php
 *   @copyright			: (C) 2007 Ordlin Team based on Vallheru ver 1.3
 *   @author			   : thindil <thindil@users.sourceforge.net>
 *   @author			   : eyescream <tduda@users.sourceforge.net>
 *   @version			  : 0.1
 *   @since				: 13.10.2007
 *
 */

//
//
//   This program is free software; you can redistribute it and/or modify
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

$title = 'Miejskie Plotki';
require_once('includes/head.php');

/**
* Get the localization for game
*/
require_once('languages/'.$player -> lang.'/news.php');
$db->SetFetchMode(ADODB_FETCH_NUM);
/**
* Display one news
*/
if (!isset ($_GET['view']))
{
	$upd = $db -> GetRow('SELECT `id`, `title`, `starter`, `news` FROM `news` WHERE `lang`=\''.$player -> lang.'\' AND `added`=\'Y\' AND `show`=\'Y\' ORDER BY `id` DESC');
	if (isset($upd[0]))
	{
		$arrQuery = $db -> GetRow('SELECT count(*) FROM `news_comments` WHERE `newsid`='.$upd[0]);
		$smarty -> assign(array('Title1' => $upd[1],
								'Starter' => $upd[2],
								'News' => $upd[3],
								'Comments' => $arrQuery[0],
								'Newsid' => $upd[0]));
	}
	$arrWaiting = $db -> GetRow('SELECT count(*) FROM `news` WHERE `lang`=\''.$player -> lang.'\' AND `added`=\'N\'');
	$arrAccepted = $db -> GetRow('SELECT count(*) FROM `news` WHERE `lang`=\''.$player -> lang.'\' AND `show`=\'N\'');
	$smarty -> assign(array('Accepted' => $arrAccepted[0],
							'Waiting' => $arrWaiting[0]));
}
else
{
/**
* Display last 10 news
*/
	$upd = $db -> GetAll('SELECT `id`, `title`, `starter`, `news` FROM `news` WHERE `lang`=\''.$player -> lang.'\' AND `added`=\'Y\' AND `show`=\'Y\'  ORDER BY `id` DESC LIMIT 10');
	for($i = 0, $max = sizeof($upd); $i < $max; ++$i)
	{
		$arrComments = $db -> GetRow('SELECT count(*) FROM `news_comments` WHERE `newsid`='.$upd[$i][0]);
		$upd[$i][] = $arrComments[0];
	}
	$smarty -> assign_by_ref('LastNews', $upd);
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
		displaycomments($_GET['text'], 'news', 'news_comments', 'newsid');
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
		addcomments($_POST['tid'], 'news_comments', 'newsid');
	}

	/**
	* Delete comment
	*/
	if (isset($_GET['action']) && $_GET['action'] == 'delete')
	{
		deletecomments('news_comments');
	}
}

/**
* Add news (simple user)
*/
if (isset($_GET['step']) && $_GET['step'] == 'add')
{
	$path = 'languages/';
	$dir = opendir($path);
	$arrLanguage = array();
	while ($file = readdir($dir))
	{
		if (!ereg(".htm*$", $file) && !ereg("\.$", $file))
		{
			$arrLanguage[] = $file;
		}
	}
	closedir($dir);

	$smarty -> assign('Llang', $arrLanguage);

	if (!empty($_POST['ttitle']) && !empty($_POST['body']))
	{
		require_once('includes/bbcode.php');
		$_POST['body'] = censorship($_POST['body']);
		$_POST['body'] = bbcodetohtml($_POST['body']);
		$_POST['ttitle'] = censorship($_POST['ttitle']);
		$strAuthor = $player -> user.' ('.$player -> id.')';
		$strBody = $db -> qstr($_POST['body'], get_magic_quotes_gpc());
		$strTitle = $db -> qstr($_POST['ttitle'], get_magic_quotes_gpc());
		$db -> Execute('INSERT INTO `news` (`title`, `news`, `added`, `lang`, `starter`) VALUES('.$strTitle.', '.$strBody.', \'N\', \''.$_POST['lang'].'\', \''.$strAuthor.'\')');
		error(YOU_ADD);
	}
}

/**
* Initialization of variable
*/
if (!isset($_GET['view']))
{
	$_GET['view'] = '';
}
if (!isset($_GET['step']))
{
	$_GET['step'] = '';
}
if (!isset($_GET['text']))
{
	$_GET['text'] = '';
}

/**
* Assign variables to template and display page
*/
$smarty -> assign (array('View' => $_GET['view'],
						 'Step' => $_GET['step'],
						 'Rank' => $player -> rank,
						 'Text' => $_GET['text']));
$smarty -> display ('news.tpl');

require_once('includes/foot.php');
?>
