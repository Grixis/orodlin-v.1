<?php
/**
 *   File functions:
 *   Main site of game
 *
 *   @name                 : index.php
 *   @copyright            : (C) 2004,2005,2006 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @version              : 1.3
 *   @since                : 22.11.2006
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
// $Id: index.php 835 2006-11-22 17:40:22Z thindil $

require_once ('includes/config.php');
if (!$gamename)
{
    $host = $_SERVER['HTTP_HOST'];
    $path = str_replace("index.php","",$_SERVER['PHP_SELF']);
    $address = "http://".$host.$path."install/install.php";
    $meta = "<META HTTP-EQUIV=\"REFRESH\" CONTENT=\"0; URL=".$address."\">";
    print "<html><head>".$meta."</head><body></body></html>";
    exit;
}
    else
{

	require_once ('includes/main/base.php');

	require_once ('includes/getlang.php');
	GetLang ();
	GetLoc ('mainpage');
	GetLoc ('index');

	GameCloseRoutine ();
    
 
	require_once ('includes/main/counter.php');
	require_once ('includes/main/record.php');
	require_once ('includes/main/online.php');
        require_once ('includes/main/usersever.php');

	require_once ('includes/right.php');

    /**
    * Main Page
    */
    if (!isset ($_GET['step']))
    {
        $uquery = $db -> SelectLimit("SELECT * FROM updates WHERE lang='".$strTranslation."' ORDER BY id DESC", 1);
        $update = "<center><b>".$uquery -> fields['title']."</b> ".WRITE_BY." <b>".$uquery -> fields['starter']."</b>".$time."...</center>\"".$uquery -> fields['updates']."\"."; 
        $uquery -> Close();

        $adminmail1 = str_replace("@","[at]",$adminmail);

        $objCodexdate = $db -> Execute("SELECT `date` FROM `court` WHERE `title`='".CODEX." ".$gamename."'");

        $smarty->assign( array ("Update" => $update,
                                "Adminname" => $adminname,
                                "Adminmail" => $adminmail,
                                "Adminmail1" => $adminmail1,
                                "Codexdate" => $objCodexdate -> fields['date'],
                                "Pagetitle" => WELCOME));
        $smarty->display('index.tpl');
        $objCodexdate -> Close();
    }

    /**
    * Game rules
    */
    if (isset ($_GET['step']) && $_GET['step'] == 'rules')
    {
        $objRules = $db -> Execute("SELECT body FROM court WHERE title='".CODEX." ".$gamename."'");
        $smarty -> assign(array("Rules2" => $objRules -> fields['body'],
                                "Pagetitle" => RULES));
        $smarty -> display('rules.tpl');
        $objRules -> Close();
    }

    /**
    * Password reminder
    */
    if (isset($_GET['step']) && $_GET['step'] == 'lostpasswd')
    {
        $strMessage = '';
        if (isset($_GET['action']) && $_GET['action'] == 'haslo')
        {
            if (!$_POST['email'])
            {
                $smarty -> assign ("Error", ERROR_MAIL);
                $smarty -> display ('error.tpl');
                exit;
            }
            $_POST['email'] =  strip_tags($_POST['email']);
            $query = $db -> Execute("SELECT `id` FROM `players` WHERE `email`='".$_POST['email']."'");
            $intId = $query -> fields['id'];
            $query -> Close();

            if (!$intId)
            {
                $smarty -> assign ("Error", ERROR_NOEMAIL);
                $smarty -> display ('error.tpl');
                exit;
            }
            $new_pass = substr(md5(uniqid(rand(), true)), 3, 9);
            $intNumber = substr(md5(uniqid(rand(), true)), 3, 9);
            $strLink = $gameadress."/index.php?step=lostpasswd&code=".$intNumber."&email=".$_POST['email'];
            $adress = $_POST['email'];
            $message = MESSAGE_PART1." ".$gamename.".".MESSAGE_PART2." \n".$new_pass."\n ".MESSAGE_PART3."\n ".$strLink."\n".MESSAGE_PART4." ".$gamename."\n".$adminname;
            $subject = MESSAGE_SUBJECT." ".$gamename;
            require_once('mailer/mailerconfig.php');
            if (!$mail -> Send())
            {
                $smarty -> assign ("Error", MESSAGE_NOT_SEND." ".$mail -> ErrorInfo);
                $smarty -> display ('error.tpl');
                exit;
            }
            $strPass = md5($new_pass);
            $db -> Execute("INSERT INTO `lost_pass` (`number`, `email`, `newpass`, `id`) VALUES('".$intNumber."', '".$_POST['email']."', '".$strPass."', ".$intId.")") or $db -> ErrorMsg();
        }

        /**
         * Write new password to database
         */
        if (isset($_GET['code']) && isset($_GET['email']))
        {
            $strEmail =  strip_tags($_GET['email']);
            $strCode =  strip_tags($_GET['code']);
            if (empty($strCode) || empty($strEmail))
            {
                $smarty -> assign ("Error", ERROR);
                $smarty -> display ('error.tpl');
                exit;
            }
            $objTest = $db -> Execute("SELECT `newpass`, `id` FROM `lost_pass` WHERE `number`='".$strCode."' AND `email`='".$strEmail."'");
            if (!$objTest -> fields['newpass'])
            {
                $smarty -> assign ("Error", ERROR);
                $smarty -> display ('error.tpl');
                exit;
            }
            $db -> Execute("UPDATE `players` SET `pass`='".$objTest -> fields['newpass']."' WHERE `email`='".$strEmail."' AND `id`=".$objTest -> fields['id']);
            $db -> Execute("DELETE FROM `lost_pass` WHERE `number`='".$strCode."' AND `email`='".$strEmail."' AND `id`=".$objTest -> fields['id']);
            $objTest -> Close();
            $strMessage = PASS_CHANGED;
        }

        /**
        * Initializantion of variable
        */
        if (!isset($_GET['action']))
        {
            $_GET['action'] = '';
        }
        $smarty -> assign(array("Action" => $_GET['action'],
                                "Message" => $strMessage,
                                "Pagetitle" => LOST_PASSWORD));
        $smarty -> display ('passwd.tpl');
    }

    /**
     * Write new email to database
     */
    if (isset($_GET['code']) && isset($_GET['email']) && (isset($_GET['step']) && $_GET['step'] == 'newemail'))
    {
        $strEmail =  strip_tags($_GET['email']);
        $strCode =  strip_tags($_GET['code']);
        if (empty($strCode) || empty($strEmail))
        {
            $smarty -> assign ("Error", ERROR);
            $smarty -> display ('error.tpl');
            exit;
        }
        $objTest = $db -> Execute("SELECT `email`, `id` FROM `lost_pass` WHERE `number`='".$strCode."' AND `newemail`='".$strEmail."'");
        if (!$objTest -> fields['email'])
        {
            $smarty -> assign ("Error", ERROR);
            $smarty -> display ('error.tpl');
            exit;
        }
        $db -> Execute("UPDATE `players` SET `email`='".$strEmail."' WHERE `email`='".$objTest -> fields['email']."' AND `id`=".$objTest -> fields['id']);
        $db -> Execute("DELETE FROM `lost_pass` WHERE `number`='".$strCode."' AND `newemail`='".$strEmail."' AND `id`=".$objTest -> fields['id']);
        $objTest -> Close();
        $smarty -> assign(array("Error" => MAIL_CHANGED));
        $smarty -> display('error.tpl');
        exit;
    }

    $db -> Close();
}
?>
