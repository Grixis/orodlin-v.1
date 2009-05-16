<?php
/**
 *   File functions:
 *   Change players' nicknames, their profiles and ranks.
 *
 *   @name                 : changenick.php
 *   @copyright            : (C) 2007 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : eyescream <tduda@users.sourceforge.net>
 *   @version              : 1.4
 *   @since                : 18.04.2007
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

$smarty -> assign(array('Info' => INFO,
                        'Changename1' => CHANGE_NAME1,
                        'Changename2' => CHANGE_NAME2,
                        'Changereason' => CHANGE_REASON,
                        'Defaultname' => DEFAULT_NAME,
                        'Save' => SAVE,
                        'Reason1' => DEFAULT1,
                        'Editprofile' => EDIT_PROFILE,
                        'Edit' => EDIT,
                        'Saveprofile' => SAVE_PROFILE,
                        'Reason2' => DEFAULT2,
                        'Reason3' =>DEFAULT3,
                        'Setid' => SET_ID,
                        'Newrank' => NEW_RANK,
                        'RMember' => R_MEMBER,
                        'RBeggar' => R_BEGGAR,
                        'RVillain' => R_VILLAIN,
                        'DescMember' => DESC_MEMBER,
                        'DescBeggar' => DESC_BEGGAR,
                        'DescVillain' => DESC_VILLAIN));

if (isset($_GET['action']))
{
/**
* General error protection.
*/
    if (!ereg('^[1-9][0-9]*$', $_POST['id']))
    {
         error (ERROR);
    }
    $objTest = $db -> Execute('SELECT count(*) FROM `players` WHERE `id`='.$_POST['id']);
    if (!$objTest -> fields['count(*)'] || $objTest -> fields['count(*)'] != 1)
    {
        error (INVALID_ID);
    }
    $objTest -> Close();
    if(isset($_POST['reason']))
    {
        $_POST['reason'] = str_replace("'","&apos;",strip_tags($_POST['reason']));
    }
/**
* Change nickname.
* Two (and more) players can have same nick (unlike account options). This allows to use "default nick".
*/
    if ($_GET['action'] == 'nick')
    {
        if (empty ($_POST['name']))
        {
            error (EMPTY_NAME);
        }
        $_POST['name'] = str_replace("'",'',strip_tags($_POST['name']));
        if ($_POST['name'] == 'Admin' || $_POST['name'] == 'Staff' || empty($_POST['name']) || !ereg('[[:graph:]]', $_POST['name']))
        {
            error (ERROR);
        }
        $db -> Execute('UPDATE `players` SET `user`=\''.$_POST['name'].'\' WHERE `id`='.$_POST['id']);
        $db -> Execute('INSERT INTO `log` (`owner`, `log`, `czas`) VALUES('.$_POST['id'].',\''.YOUR_NICK_WAS_CHANGED_BY.' <b><a href="view.php?view='.$player -> id .'">'.$player -> user.'</a></b>, ID <b>'.$player -> id.'</b>. '.CHANGE_REASON.':  '.$_POST['reason'].'\','.$db -> DBDate($newdate).')');
        error (YOU_CHANGE.' '.$_POST['id'].' '.CHANGE_NAME2.' <b>'.$_POST['name'].'</b>.');
    }
    if ($_GET['action'] == 'profile')
    {
        $objProfile = $db -> Execute('SELECT `user`, `profile` from `players` WHERE `id`='.$_POST['id']);
        require_once('includes/bbcode.php');
        $strProfile = htmltobbcode($objProfile -> fields['profile']);
        $smarty -> assign(array('Currentinfo' => CURRENTINFO,
                                'Victimname' => $objProfile -> fields['user'],
                                'VictimID' => $_POST['id'],
                                'Current' => $objProfile -> fields['profile'],
                                'Editable' =>$strProfile));
        if (!empty($_POST['profile']))
        {
            $_POST['profile'] = bbcodetohtml($_POST['profile']);
            $strProfile = $db -> qstr($_POST['profile'], get_magic_quotes_gpc());
            $db -> Execute('UPDATE `players` SET `profile` = '.$strProfile.' WHERE `id` = '.$_POST['id']);
            $db -> Execute('INSERT INTO `log` (`owner`, `log`, `czas`) VALUES('.$_POST['id'].',\''.YOUR_PROFILE_WAS_CHANGED_BY.' <b><a href="view.php?view='.$player -> id .'">'.$player -> user.'</a></b>, ID <b>'.$player -> id.'</b>. '.CHANGE_REASON.':  '.$_POST['reason'].'\','.$db -> DBDate($newdate).')');
            error (YOU_CHANGED_PROFILE.' <b><a href="view.php?view='.$_POST['id'].'">'.$objProfile -> fields['user'].'</a></b>, ID <b>'.$_POST['id'].'</b>.');
        }
    }
    if ($_GET['action'] == 'rank' && $_POST['id'] != 1)
    {
        if ($_POST['rank'] != R_MEMBER && $_POST['rank'] != R_BEGGAR && $_POST['rank'] != 'Barbarzyńca')
        {
            error (WRONG_RANK);
        }
        $objTest2 =$db -> Execute('SELECT `rank` FROM `players` WHERE `id`='.$_POST['id']);
        if ($objTest2 -> fields['rank'] != R_MEMBER && $objTest2 -> fields['rank'] != R_BEGGAR && $objTest2 -> fields['rank'] != R_VILLAIN)
        {
            error (WRONG_PLAYER);
        }
        $strRank = $db -> qstr($_POST['rank'], get_magic_quotes_gpc());
        $db -> Execute('UPDATE `players` SET `rank`='.$strRank.' WHERE `id`='.$_POST['id']);
        $arrDescriptions = array(R_MEMBER => DESC_MEMBER,
                                 R_BEGGAR => DESC_BEGGAR,
                                 R_VILLAIN => DESC_VILLAIN);
        error (YOU_ADD_R.' '.$_POST['id'].' '.NEW_RANK.' '.$arrDescriptions[$_POST['rank']].'.');
    }
}
?>
