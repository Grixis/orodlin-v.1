<?php
/**
 *   File functions:
 *   Account options - change avatar, email, password and nick
 *
 *   @name				 : account.php
 *   @copyright			: (C) 2004,2005,2006,2007 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author			   : thindil <thindil@users.sourceforge.net>
 *   @author			   : Erechail	<kuba.stasiak at gmail.com>
 *   @author			   : eyescream	<tduda@users.sourceforge.net>
 *   @version			  : 1.3a
 *   @since				: 8.10.2007
 *
 */

//
//
//	   This program is free software; you can redistribute it and/or modify
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

$title = 'Opcje konta';
require_once("includes/head.php");

/**
* Get the localization for game
*/
require_once("languages/".$player -> lang."/account.php");

/**
* Assign variable to template
*/
$smarty -> assign("Avatar", '');

/**
 * Links
 */

if (isset($_GET['view']) && $_GET['view'] == 'links')
{
	$objLinks = $db -> Execute("SELECT `id`, `file`, `text` FROM `links` WHERE `owner`=".$player -> id." ORDER BY `id` ASC");
	$arrId = array(0);
	$arrFile = array();
	$arrText = array();
	$i = 0;
	while (!$objLinks -> EOF)
	{
		$arrId[$i] = $objLinks -> fields['id'];
		$arrFile[$i] = $objLinks -> fields['file'];
		$arrText[$i] = $objLinks -> fields['text'];
		$i ++;
		$objLinks -> MoveNext();
	}
	$objLinks -> Close();
	if (!isset($_GET['lid']))
	{
		$strFormaction = A_ADD;
		$intLinkid = 0;
	}
		else
	{
	   if (!ereg("^[0-9]*$", $_GET['lid']))
	   {
		   error(ERROR);
	   }
	   if ($_GET['lid'] == 0)
	   {
		   $strFormaction = A_ADD;
	   }
		   else
	   {
		   $strFormaction = A_EDIT;
	   }
	   $intLinkid = $_GET['lid'];
	}
	$smarty -> assign(array("Linksinfo" => LINKS_INFO,
							"Tfile" => FILENAME,
							"Tname" => T_NAME,
							"Tactions" => T_ACTIONS,
							"Adelete" => A_DELETE,
							"Aedit" => A_EDIT,
							"Aform" => $strFormaction,
							"Linksid" => $arrId,
							"Linksfile" => $arrFile,
							"Linkstext" => $arrText,
							"Linkid" => $intLinkid,
							"Linkfile" => '',
							"Linkname" => ''));

	/**
	 * Add/edit links
	 */
	if (isset($_GET['step']) && $_GET['step'] == 'edit')
	{
		if (!isset($_GET['action']) && $_GET['lid'] > 0)
		{
			$objLink = $db -> Execute("SELECT `id`, `file`, `text` FROM `links` WHERE `id`=".$_GET['lid']." AND `owner`=".$player -> id);
			if (!$objLink -> fields['id'])
			{
				error(NOT_YOUR);
			}
			$smarty -> assign(array("Linkfile" => $objLink -> fields['file'],
									"Linkname" => $objLink -> fields['text']));
			$objLink -> Close();
		}
		if (isset($_GET['action']) && $_GET['action'] == 'change')
		{
			$strFile = strip_tags($_POST['linkadress']);
			$strText = strip_tags($_POST['linkname']);
			if (empty($strFile) || empty($strText))
			{
				error(EMPTY_FIELDS);
			}
			$arrForbidden = array('config.php', 'session.php', 'reset.php', 'resets.php', 'quest', 'portal');
			foreach ($arrForbidden as $strForbidden)
			{
				$intPos = strpos($strFile, $strForbidden);
				if ($intPos !== false)
				{
					error(ERROR);
				}
			}
			if ($_GET['lid'] > 0)
			{
				$db -> Execute("UPDATE `links` SET `file`='".$strFile."', `text`='".$strText."' WHERE `id`=".$_GET['lid']." AND `owner`=".$player -> id);
				$strMessage = YOU_CHANGE;
			}
				else
			{
				$db -> Execute("INSERT INTO `links` (`owner`, `file`, `text`) VALUES(".$player -> id.", '".$strFile."', '".$strText."')");
				$strMessage = YOU_ADD;
			}
			error($strMessage);
		}
	}

	/**
	 * Delete links
	 */
	if (isset($_GET['step']) && $_GET['step'] == 'delete')
	{
		$objLink = $db -> Execute("SELECT `id` FROM `links` WHERE `id`=".$_GET['lid']." AND `owner`=".$player -> id);
		if (!$objLink -> fields['id'])
		{
			error(NOT_YOUR);
		}
		$objLink -> Close();
		$db -> Execute("DELETE FROM `links` WHERE `id`=".$_GET['lid']." AND `owner`=".$player -> id);
		error(LINK_DELETED);
	}
}

/**
 * Bugtrack
 */
if (isset($_GET['view']) && $_GET['view'] == 'bugtrack')
{
	$objBugs = $db -> Execute("SELECT `id`, `title`, `type`, `location` FROM `bugreport` WHERE `resolution`=0 ORDER BY `id` ASC");
	$arrId = array();
	$arrTitle = array();
	$arrType = array();
	$arrLocation = array();
	$i = 0;
	while (!$objBugs -> EOF)
	{
		$arrId[$i] = $objBugs -> fields['id'];
		$arrTitle[$i] = $objBugs -> fields['title'];
		$arrLocation[$i] = $objBugs -> fields['location'];
		if ($objBugs -> fields['type'] == 'text')
		{
			$arrType[$i] = BUG_TEXT;
		}
			else
		{
			$arrType[$i] = BUG_CODE;
		}
		$i++;
		$objBugs -> MoveNext();
	}
	$objBugs -> Close();
	if ($player ->rank != 'Admin')
	{
		require_once('includes/bbcode.php');
		$arrTitle = censorship($arrTitle);
		$arrLocation = censorship($arrLocation);
	}
	$smarty -> assign(array("Bugtype" => BUG_TYPE,
							"Bugloc" => BUG_LOC,
							"Bugid" => BUG_ID,
							"Bugname" => BUG_NAME,
							"Bugtrackinfo" => BUGTRACK_INFO,
							"Bugstype" => $arrType,
							"Bugsloc" => $arrLocation,
							"Bugsid" => $arrId,
							"Bugsname" => $arrTitle));
}

/**
 * Bug report
 */
if (isset($_GET['view']) && $_GET['view'] == 'bugreport')
{
	$smarty -> assign(array("Bugtype" => BUG_TYPE,
							"Bugtext" => BUG_TEXT,
							"Bugcode" => BUG_CODE,
							"Bugloc" => BUG_LOC,
							"Bugdesc" => BUG_DESC,
							"Areport" => A_REPORT,
							"Bugname" => BUG_NAME,
							"Buginfo" => BUG_INFO));
	/**
	 * Report bug
	 */
	if (isset($_GET['step']) && $_GET['step'] == 'report')
	{
		$arrFields = array($_POST['bugtitle'], $_POST['type'], $_POST['location'], $_POST['desc']);
		require_once('includes/bbcode.php');
		foreach ($arrFields as $strField)
		{
			$strField = strip_tags($strField);
			$strField = bbcodetohtml($strField);
			if (!ereg("[[:graph:]]", $strField))
			{
				error(EMPTY_FIELDS);
			}
		}
		if (!in_array($arrFields[1], array('text', 'code')))
		{
			error(ERROR);
		}
		$intDesc = strlen($arrFields[3]);
		if ($intDesc < 100)
		{
			error(TOO_SHORT);
		}
		$db -> Execute("INSERT INTO `bugreport` (`sender`, `title`, `type`, `location`, `desc`) VALUES(".$player -> id.", '".$arrFields[0]."', '".$arrFields[1]."', '".$arrFields[2]."', '".$arrFields[3]."')");
		error(B_REPORTED);
	}
}

/**
* Select game localization
*/
if (isset ($_GET['view']) && $_GET['view'] == "lang")
{
	/**
	* Check avaible languages
	*/
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

	/**
	* Show select menu
	*/
	$smarty -> assign(array("Langinfo" => LANG_INFO,
							"Flang" => F_LANG,
							"Slang" => S_LANG,
							"Aselect" => A_SELECT,
							"Lang" => $arrLanguage));

	/**
	* Write selected information to database
	*/
	if (isset ($_GET['step']) && $_GET['step'] == 'lang')
	{
		if (!isset($_POST['mainlang']) || !isset($_POST['seclang']))
		{
			error(EMPTY_FIELDS);
		}
		if (!in_array($_POST['mainlang'], $arrLanguage) || !in_array($_POST['seclang'], $arrLanguage))
		{
			error(ERROR);
		}
		$db -> Execute("UPDATE players SET lang='".$_POST['mainlang']."' WHERE id=".$player -> id);
		$strMessage = YOU_SELECT.$_POST['mainlang'];
		if ($_POST['seclang'] != $_POST['mainlang'] || isset($player -> seclang))
		{
			$db -> Execute("UPDATE players SET seclang='".$_POST['seclang']."' WHERE id=".$player -> id);
			$strMessage = $strMessage.AND_SECOND.$_POST['seclang'];
		}
		$strMessage = $strMessage." <a href=\"account.php\">".A_REFRESH."</a>";
		$smarty -> assign("Message", $strMessage);
	}

}

/**
 * Display info about changes in game
 */
if (isset($_GET['view']) && $_GET['view'] == 'changes')
{
	$objChanges = $db -> SelectLimit("SELECT `author`, `location`, `text`, `date` FROM `changelog` WHERE `lang`='".$player -> lang."' ORDER BY `id` DESC", 30);
	$i = 0;
	$arrAuthor = array();
	$arrDate = array();
	$arrText = array();
	$arrLocation = array();
	while (!$objChanges -> EOF)
	{
		$arrAuthor[$i] = $objChanges -> fields['author'];
		$arrDate[$i] = $objChanges -> fields['date'];
		$arrLocation[$i] = $objChanges -> fields['location'];
		$arrText[$i] = $objChanges -> fields['text'];
		$i ++;
		$objChanges -> MoveNext();
	}
	$objChanges -> Close();
	$smarty -> assign(array("Changesinfo" => CHANGES_INFO,
							"Changeloc" => CHANGE_LOC,
							"Changeauthor" => $arrAuthor,
							"Changedate" => $arrDate,
							"Changelocation" => $arrLocation,
							"Changetext" => $arrText));
}

/**
* Additional options
*/
if (isset($_GET['view']) && $_GET['view'] == 'options')
{
	if ($player -> battleloga)
	{
		$strChecked = 'checked';
	}
		else
	{
		$strChecked = '';
	}
	if ($player -> battlelogd)
	{
		$strChecked2 = 'checked';
	}
		else
	{
		$strChecked2 = '';
	}
	if ($player -> graphbar == 'Y')
	{
		$strChecked3 = 'checked';
	}
		else
	{
		$strChecked3 = '';
	}
	if ($player -> overlib)
	{
		$strChecked4 = 'checked';
	}
		else
	{
		$strChecked4 = '';
	}
	if ($player -> loginfo)
	{
		$strChecked5 = 'checked';
	}
		else
	{
		$strChecked5 = '';
	}
	if ($player -> mailinfo)
	{
		$strChecked6 = 'checked';
	}
		else
	{
		$strChecked6 = '';
	}
	$smarty -> assign(array("Toptions" => T_OPTIONS,
							"Anext" => A_NEXT,
							"Checked" => $strChecked,
							"Checked2" => $strChecked2,
							"Checked3" => $strChecked3,
							"Checked4" => $strChecked4,
							"Checked5" => $strChecked5,
							"Checked6" => $strChecked6));
	if (isset($_GET['step']) && $_GET['step'] == 'options')
	{
		if (isset($_POST['battleloga']))
		{
			$db -> Execute("UPDATE `players` SET `battleloga`=1 WHERE `id`=".$player -> id);
		}
			else
		{
			$db -> Execute("UPDATE `players` SET `battleloga`=0 WHERE `id`=".$player -> id);
		}
		if (isset($_POST['battlelogd']))
		{
			$db -> Execute("UPDATE `players` SET `battlelogd`=1 WHERE `id`=".$player -> id);
		}
			else
		{
			$db -> Execute("UPDATE `players` SET `battlelogd`=0 WHERE `id`=".$player -> id);
		}
		if (isset($_POST['graphbar']))
		{
			$db -> Execute("UPDATE `players` SET `graphbar`='Y' WHERE `id`=".$player -> id);
		}
			else
		{
			$db -> Execute("UPDATE `players` SET `graphbar`='N' WHERE `id`=".$player -> id);
		}
		if (isset($_POST['overlib']))
		{
			$db -> Execute("UPDATE `players` SET `overlib`=1 WHERE `id`=".$player -> id);
		}
			else
		{
			$db -> Execute("UPDATE `players` SET `overlib`=0 WHERE `id`=".$player -> id);
		}
		if (isset($_POST['loginfo']))
		{
			$db -> Execute("UPDATE `players` SET `loginfo`=1 WHERE `id`=".$player -> id);
		}
			else
		{
			$db -> Execute("UPDATE `players` SET `loginfo`=0 WHERE `id`=".$player -> id);
		}
		if (isset($_POST['mailinfo']))
		{
			$db -> Execute("UPDATE `players` SET `mailinfo`=1 WHERE `id`=".$player -> id);
		}
			else
		{
			$db -> Execute("UPDATE `players` SET `mailinfo`=0 WHERE `id`=".$player -> id);
		}
		$smarty -> assign("Message", A_SAVED);
	}
}

/**
* Account freeze
*/
if (isset($_GET['view']) && $_GET['view'] == 'freeze')
{
	$smarty -> assign(array("Freezeinfo" => FREEZE_INFO,
							"Howmany" => HOW_MANY,
							"Afreeze2" => A_FREEZE2));
	if (isset($_GET['step']) && $_GET['step'] == 'freeze')
	{
		if (!ereg("^[1-9][0-9]*$", $_POST['amount']))
		{
			error(ERROR);
		}
		if ($_POST['amount'] > 21)
		{
			error(TOO_MUCH);
		}
		$db -> Execute("UPDATE players SET freeze=".$_POST['amount']." WHERE id=".$player -> id);
		$smarty -> assign("Message", YOU_BLOCK.$_POST['amount'].NOW_EXIT);
		session_unset();
		session_destroy();
	}
}

/**
* Assign and discard immunity
*/
if (isset ($_GET['view']) && $_GET['view'] == 'immu')
{
	if (!$player -> clas)
		{
			error (NO_CLASS);
		}
	$immuInfo = $player -> clas != 'Rzemieślnik' ? IMMU_INFO1 : IMMU_INFO2;
	$showOption = $player -> clas != 'Rzemieślnik' ? 'N' : 'Y';
	$smarty -> assign_by_ref('Immuinfo', $immuInfo);
	$smarty -> assign_by_ref('Showoption', $showOption);
	$smarty -> assign_by_ref('Immunity', $player -> immunited);

	if (isset ($_GET['step']) && $_GET['step'] == 'take')
	{
		if ($player -> immunited == 'Y')
		{
			error (YOU_HAVE);
		}
		if ($player -> clas != 'Rzemieślnik')
		{
			error (ONLY_FOR_ARTISAN);
		}
		$db -> Execute('UPDATE `players` SET `immu`=\'Y\' WHERE `id`='.$player -> id);
	}

	if (isset ($_GET['step']) && $_GET['step'] == 'discard')
	{
		if ($player -> immunited == 'N')
		{
			error (NO_IMMU);
		}
		$db -> Execute('UPDATE `players` SET `immu`=\'N\', `strength`=`strength` * 0.75, `agility`=`agility` * 0.75, `szyb`=`szyb` * 0.75, `wytrz`=`wytrz` * 0.75, `inteli`=`inteli` * 0.75, `wisdom`=`wisdom` * 0.75 WHERE `id`='.$player -> id);
	}
}

/**
* Player reset
*/
if (isset ($_GET['view']) && $_GET['view'] == "reset")
{
	$smarty -> assign(array("Resetinfo" => RESET_INFO,
							"Yes" => YES,
							"No" => NO));
	if (isset ($_GET['step']) && $_GET['step'] == 'make')
	{
		$code = rand(1,1000000);
		$message = MESSAGE1." ".$gamename." (".$player -> user." ".ID.": ".$player -> id.") ".MESSAGE2." ".$gameadress."/preset.php?id=".$player -> id."&code=".$code." ".MESSAGE4." ".$gameadress."/preset.php?id=".$player -> id." ".MESSAGE4." ".$adminname;
		$adress = $_SESSION['email'];
		$subject = MSG_TITLE." ".$gamename;
		require_once('mailer/mailerconfig.php');
		if (!$mail -> Send())
		{
		   error(E_MAIL.":<br /> ".$mail -> ErrorInfo);
		}
		$db -> Execute("INSERT INTO reset (player, code) VALUES(".$player -> id.",".$code.")") or error(E_DB);
		$smarty -> assign("Resetselect", RESET_SELECT);
	}
}

/**
* Avatar options
*/
if (isset($_GET['view']) && $_GET['view'] == "avatar")
{
	$smarty -> assign(array("Avatarinfo" => AVATAR_INFO,
							"Delete" => A_DELETE,
							"Afilename" => FILE_NAME,
							"Aselect" => A_SELECT));
	$file = 'avatars/'.$player -> avatar;
	if (is_file($file))
	{
		$smarty -> assign (array ("Value" => $player -> avatar, "Avatar" => $file));
	}
	if (isset($_GET['step']) && $_GET['step'] == 'usun')
	{
		global $newdate;
		if ($_POST['av'] != $player -> avatar)
		{
			$db -> Execute("Insert into `mail` (`sender`,`senderid`,`owner`,`subject`,`body`,`date`) VALUES ('".($player -> user)."', ".($player -> id).", 1, 'wlasnie probowalem sie wlamac wpisujac ".($_SERVER['REQUEST_URI'])."', ".($db -> DBDate($newdate)).")");
			error(ERROR);
		}
	$plik = 'avatars/'.$_POST['av'];
		if (is_file($plik))
		{
			unlink($plik);
			$db -> Execute("UPDATE players SET avatar='' WHERE id=".$player -> id) or error(E_DB);
			error (DELETED.". <a href=\"account.php?view=avatar\">".REFRESH."</a><br />");
		}
			else
		{
			error (NO_FILE);
		}
	}
	if (isset($_GET['step']) && $_GET['step'] == 'dodaj')
	{
		if (!isset($_FILES['plik']['name']))
		{
			error(NO_NAME);
		}
		$plik = $_FILES['plik']['tmp_name'];
		$nazwa = $_FILES['plik']['name'];
		$typ = $_FILES['plik']['type'];
		if ($typ != 'image/pjpeg' && $typ != 'image/jpeg' && $typ != 'image/gif' && $typ != 'image/png')
		{
			error (BAD_TYPE);
		}
		if ($typ == 'image/pjpeg' || $typ == 'image/jpeg')
		{
			$liczba = rand(1,1000000);
			$newname = md5($liczba).'.jpg';
			$miejsce = 'avatars/'.$newname;
		}
		if ($typ == 'image/gif')
		{
			$liczba = rand(1,1000000);
			$newname = md5($liczba).'.gif';
			$miejsce = 'avatars/'.$newname;
		}
		if ($typ == 'image/png')
		{
			$liczba = rand(1,1000000);
			$newname = md5($liczba).'.png';
			$miejsce = 'avatars/'.$newname;
		}
		if (is_uploaded_file($plik))
		{
			if (!move_uploaded_file($plik,$miejsce))
			{
				error (NOT_COPY);
			}
		}
			else
		{
			error (ERROR);
		}
		$db -> Execute("UPDATE players SET avatar='".$newname."' WHERE id=".$player -> id) or error("nie mogÄ dodaÄ!");
		error (LOADED."! <a href=\"account.php?view=avatar\">".REFRESH."</a><br />");
	}
}

/**
* Change nick for player
*/
if (isset($_GET['view']) && $_GET['view'] == "name")
{
	$smarty -> assign(array('Change' => CHANGE,
							'Myname' => MY_NAME,
							'OldNick' => $player -> user));
	if (isset($_GET['step']) && $_GET['step'] == "name")
	{
		if (empty ($_POST['name']))
		{
			error (EMPTY_NAME);
		}
		if ($_POST['name'] == 'Admin' || $_POST['name'] == 'Staff' || empty($_POST['name']) || !ereg("[[:graph:]]", $_POST['name']))
		{
			error (ERROR);
		}
		$query = $db -> Execute("SELECT count(*) FROM `players` WHERE `user`='".$_POST['name']."'");
		$dupe = $query -> fields['count(*)'];
		$query -> Close();
		if ($dupe > 0)
		{
			error (NAME_BLOCK);
		}

	if (strlen($_POST['name']) > 15)
	{
		error (LONG_NICK);
	}

	require_once ('includes/limits.php');
	if (!ValidNick (stripslashes ($_POST['name']))) {
		error (INVALID_NICK);
	}
	else {
			$nameToWrite = str_replace('\'','&#39;',$_POST['name']);
			$db -> Execute("UPDATE `players` SET `user`='".$nameToWrite."' WHERE `id`=".$player -> id);
			error (YOU_CHANGE." <b>".stripslashes($_POST['name'])."</b>.");

	}
	}
}

/**
* Change password to account
*/
if (isset($_GET['view']) && $_GET['view'] == "pass")
{
	if (isset($_GET['step']) && $_GET['step'] == "cp")
	{
		if (empty ($_POST['np']) || empty($_POST['cp']))
		{
			error (EMPTY_FIELDS);
		}
		$strTmp = md5($_POST['cp']);
		if ($strTmp != $_SESSION['pass'])
		{
			error (PASSWORD_MISMATCH);
		}
		require_once('includes/verifypass.php');
		$_POST['np'] = str_replace("'","",strip_tags($_POST['np']));
		verifypass($_POST['np'],'account');
		$db -> Execute('UPDATE `players` SET `pass`=MD5(\''.$_POST['np'].'\') WHERE `id`='.$player -> id);
		$_SESSION['pass'] = md5($_POST['np']);
		error (YOU_CHANGE." ".$_POST['cp']." ".ON." ".$_POST['np']);
	}
}

/**
* Profile edit
*/
if (isset($_GET['view']) && $_GET['view'] == "profile")
{
	require_once('includes/bbcode.php');
	$profile = htmltobbcode($player -> profile);
	$smarty -> assign(array("Profileinfo" => PROFILE_INFO,
							"Newprofile" => NEW_PROFILE,
							"Editable" => $profile,
							"Change" => CHANGE));
	if (isset($_GET['step']) && $_GET['step'] == 'profile')
	{
		if (empty($_POST['newprofile']))
		{
			error (EMPTY_FIELDS);
		}
		require_once('includes/bbcode.php');
		$_POST['newprofile'] = censorship($_POST['newprofile']);
		$_POST['newprofile'] = bbcodetohtml($_POST['newprofile']);
		$strEditable = htmltobbcode($_POST['newprofile']);
		$strProfile = $db -> qstr($_POST['newprofile'], get_magic_quotes_gpc());
		$db -> Execute("UPDATE players SET profile = ".$strProfile." WHERE id = '".$player -> id."'");
		$smarty -> assign (array("Profile" => $_POST['newprofile'],
								 "Editable" => $strEditable,
								 "Newprofile2" => NEW_PROFILE2));
	}
}

/**
* Email and comunicators edit
*/
if (isset($_GET['view']) && $_GET['view'] == 'eci')
{
	$arrComname = array(COMM1, COMM2, COMM3, COMM4);
	$arrComlink = array(COMLINK1, COMLINK2, COMLINK3, COMLINK4);
	$smarty -> assign(array("Oldemail" => OLD_EMAIL,
							"Newemail" => NEW_EMAIL,
							"Newgg" => NEW_GG,
							"Change" => CHANGE,
							"Delete" => DELETE,
							"Tcommunicator" => T_COMMUNICATOR,
							"Tcom" => $arrComname,
							"Comm" => $arrComlink));

	/**
	 * Change communicator
	 */
	if (isset($_GET['step']) && $_GET['step'] == "gg")
	{
		$_POST['gg'] = str_replace("'", "", strip_tags($_POST['gg']));
		$intKey = array_search($_POST['communicator'], $arrComlink);
		
		if($_POST['Change'])
		{
			if ($intKey === 0)
			{
				if (!ereg("^[1-9][0-9]*$", $_POST['gg']))
				{
					error(ERROR);
				}
			}
			
			if (empty($_POST['gg']))
			{
				error(EMPTY_FIELDS);
			}
			$strCom = $arrComname[$intKey].": <a href=\"".$_POST['communicator'].$_POST['gg']."\">".$_POST['gg']."</a>";
			$query= $db -> Execute("SELECT count(*) FROM `players` WHERE `gg`='".$strCom."'");
			$dupe = $query -> fields['count(*)'];
			$query -> Close();
			if ($dupe > 0)
			{
				error(GG_BLOCK);
			}
			
			$db -> Execute("UPDATE `players` SET `".$arrComname[$intKey]."`='".$strCom."' WHERE `id`=".$player -> id) or error(E_DB);
			error(YOU_CHANGE.$arrComname[$intKey].".");
				
			
		}
		else if ($_POST['Delete'])
		{
		
		$db -> Execute("UPDATE `players` SET `".$arrComname[$intKey]."`='' WHERE `id`=".$player -> id) or error(E_DB);
		error(YOU_DELETE);
		}
		
	}
	if (isset($_GET['step']) && $_GET['step'] == "delete")
	{
		$_POST['gg'] = str_replace("'", "", strip_tags($_POST['gg']));
		$intKey = array_search($_POST['communicator'], $arrComlink);
		
		$db -> Execute("UPDATE `players` SET `".$arrComname[$intKey]."`='jabberaa' WHERE `id`=".$player -> id) or error(E_DB);
		error(YOU_DELETE);
	}

	/**
	 * Change email
	 */
	if (isset($_GET['step']) && $_GET['step'] == "ce")
	{
		if (empty($_POST["ne"]) || empty($_POST['ce']))
		{
			error(EMPTY_FIELDS);
		}
		$_POST['ne'] = strip_tags($_POST['ne']);
		$_POST['ce'] = strip_tags($_POST['ce']);
		require_once('includes/verifymail.php');
		if (MailVal($_POST['ne'], 2))
		{
			error(BAD_EMAIL);
		}
		$query = $db -> Execute("SELECT `id` FROM `players` WHERE `email`='".$_POST['ne']."'");
		if ($query -> fields['id'])
		{
			error(EMAIL_BLOCK);
		}
		$query -> Close();
		$intNumber = substr(md5(uniqid(rand(), true)), 3, 9);
		$strLink = $gameadress."/index.php?step=newemail&code=".$intNumber."&email=".$_POST['ne'];
		$adress = $_POST['ne'];
		$message = MESSAGE_PART1.$gamename."\n".MESSAGE_PART2."\n".$strLink."\n".MESSAGE_PART3." ".$gamename."\n".$adminname;
		$subject = MESSAGE_SUBJECT.$gamename;
		require_once('mailer/mailerconfig.php');
		if (!$mail -> Send())
		{
			$smarty -> assign ("Error", MESSAGE_NOT_SEND." ".$mail -> ErrorInfo);
			$smarty -> display ('error.tpl');
			exit;
		}
		$db -> Execute("INSERT INTO `lost_pass` (`number`, `email`, `id`, `newemail`) VALUES('".$intNumber."', '".$_POST['ce']."', ".$player -> id.", '".$_POST['ne']."')") or $db -> ErrorMsg();
		error(YOU_CHANGE);
	}
}

/**
* Graphic style change
*/
if (isset($_GET['view']) && $_GET['view'] == 'style')
{
	/**
	* Text style choice
	*/
	$path = 'css/';
	$dir = opendir($path);
	$arrname = array();
	$i = 0;
	while ($file = readdir($dir))
	{
		if (ereg(".css$", $file))
		{
			$arrname[$i] = $file;
			$i = $i + 1;
		}
	}
	if ($i == 0) $arrname = '';
	closedir($dir);
	/**
	* Check avaible layouts
	*/
	$path = 'css/main/';
	$dir = opendir($path);
	$arrname1 = array();
	$i = 0;
	while ($file = readdir($dir))
	{
		if (!ereg(".htm*$", $file) && !ereg(".tpl*$", $file))
		{
			if (!ereg("\..*$", $file))
			{
				$arrname1[$i] = $file;
				$i = $i + 1;
			}
		}
	}
	if ($i == 0) $arrname1 = '';
	closedir($dir);
	/**
	* Assign variables to template
	*/
	$smarty -> assign(array("Sselect" => S_SELECT,
							"Textstyle" => TEXT_STYLE,
							"Graph_text" => GRAPH_TEXT,
							"Youchange" => YOU_CHANGE,
							"Stylename" => $arrname,
							"Refresh" => REFRESH,
							'Graphless' => GRAPHLESS,
							"Layoutname" => $arrname1));
	/**
	* If player choice text style
	*/
	if (isset($_GET['step']) && $_GET['step'] == 'style')
	{
		if (!isset($_POST['newstyle']))
		{
			error(ERROR);
		}
		$_POST['newstyle'] = strip_tags($_POST['newstyle']);
		$strNewStyle = $db -> qstr($_POST['newstyle'], get_magic_quotes_gpc());
		$db -> Execute("UPDATE players SET style=".$strNewStyle." where id=".$player -> id);
	}
	/**
	* If player choice graphic layout
	*/
	if (isset($_GET['step']) && $_GET['step'] == 'graph')
	{
		if (!isset($_POST['graphserver']))
		{
			error(ERROR);
		}
		$_POST['graphserver'] = strip_tags($_POST['graphserver']);
		$strNewGraphStyle = $db -> qstr($_POST['graphserver'], get_magic_quotes_gpc());
		$db -> Execute("UPDATE players SET graphic=".$strNewGraphStyle.", graphstyle='Y' WHERE id=".$player -> id) or error(ERROR2.$path." ".$player -> id);
	}

	/**
	* Game without graphics
	*/
	if ($player -> graphstyle == 'Y')
	{
		$strChecked = '';
	}
		else
	{
		$strChecked = 'checked';
	}
	$smarty -> assign(array("Checked" => $strChecked));


	if (isset($_GET['step']) && $_GET['step'] == 'graphstyle')
	{
		if (isset($_POST['graphstyle']))
		{
			$db -> Execute("UPDATE `players` SET `graphstyle`='N' WHERE `id`=".$player -> id);
		}
			else
		{
			$db -> Execute("UPDATE `players` SET `graphstyle`='Y' WHERE `id`=".$player -> id);
		}
	}
}


/**
* Player descriptions
*/
if (isset($_GET['view']) && $_GET['view'] == "description")
{
	require_once('includes/bbcode.php');
	$opis = htmltobbcode($player -> opis);
	$smarty -> assign(array("opistext" => PODPIS_TEXT,
							"Newpodpis" => NEW_OPIS,
							"Opis" => $opis,
							"Change" => CHANGE));
	if (isset($_GET['step']) && $_GET['step'] == "change")
	{
		$_POST['opis'] = str_replace("'","",strip_tags($_POST['opis']));
		$_POST['opis'] = str_replace("\'","",$_POST['opis']);
		$_POST['opis'] = str_replace("'","",$_POST['opis']);
		//$_POST['opis'] = htmlspecialchars($_POST['opis']);
		$_POST['opis'] = str_replace("&apos;","",$_POST['opis']);
		$_POST['opis'] = str_replace("&#39;","",$_POST['opis']);
		$_POST['opis'] = str_replace("&#x27;","",$_POST['opis']);
		$strPodpis = $db -> qstr($_POST['opis'], get_magic_quotes_gpc());
		$db -> Execute("UPDATE `players` SET `opis` = ".strip_tags($strPodpis)." WHERE `id` = '".$player -> id."'");
		$smarty -> assign (array("opis" => $_POST['opis'],
			"Newopis2" => NEW_OPIS2));
	}
}

/**
* Personalized signatures
*/
if (isset($_GET['view']) && $_GET['view'] == "signatures")
{
	$Types = array ();
	$TypesCnt = 3;

	for ($i = 0; $i < $TypesCnt; $i++) {
		$Types[$i] = $i+1;
	}

$smarty -> assign (array('Types'	=> $Types,
			'head_text' => HEAD_TEXT,
			 'pid' => $player -> id));
}

/**
* Initialization of variables
*/
if (!isset($_GET['view']))
{
	$_GET['view'] = '';
}
if (!isset($_GET['step']))
{
	$_GET['step'] = '';
}

/**
* Assign variables and display page
*/
$arrStep = array('name', 'pass', 'profile', 'eci', 'avatar', 'reset', 'immu', 'style', 'lang', 'freeze', 'options', 'changes', 'bugreport', 'bugtrack', 'links', 'description', 'signatures');
$arrLinks = array(A_NAME, A_PASS, A_PROFILE, A_EMAIL, A_AVATAR, A_RESET, A_IMMU, A_STYLE, A_LANG, A_FREEZE, A_OPTIONS, A_CHANGES, A_BUGREPORT, A_BUGTRACK, A_LINKS, DESCRIPTION, SIGNATURES);
$smarty -> assign (array ("View" => $_GET['view'],
						  "Step" => $_GET['step'],
						  "Welcome" => WELCOME,
						  "Steps" => $arrStep,
						  "Links" => $arrLinks));
$smarty -> display('account.tpl');

require_once("includes/foot.php");
?>
