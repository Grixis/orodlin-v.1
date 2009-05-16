<?php
/**
 *   File functions:
 *   Add reputation for players
 *
 *   @name                 : addreps.php                            
 *   @copyright            : (C) 2007 Orodlin Team based on Vallheru and Gamers-Fusion ver 2.5
 *   @author               : Klaus Korner <albitos.snape@gmail.com>
 *   @version              : 1.3
 *   @since                : 03.09.2007r.
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
// $Id:
require_once('includes/security.php');
if(isset($_POST['id']))
{
    $objCheck = $db -> Execute('SELECT count(*) as c FROM players WHERE id='.(int)$_POST['id']);
    if($objCheck -> fields['c'] == 0)
    {
        error(NO_PLAYER, RET_ARG, 'admin.php?view=addreps');
    }
    if(empty($_POST['description']) || empty($_POST['amount']))
    {
        error(EMPTY_FIELDS, RET_ARG, 'admin.php?view=addreps');
    }
    $strDate = $db -> DBDate(time());    
    outString($_POST['description']);
    $db -> Execute("INSERT INTO `log` (`owner`,`log`,`czas`) VALUES (".(int)$_POST['id'].", '".RECEIVE.(int)$_POST['amount'].REPUTATION_POINTS."<br />".$_POST['description']."',".$strDate.")") or die($db -> ErrorMsg());
    $db -> Execute('INSERT INTO reputation(player_id, points, description, date) VALUES('.(int)$_POST['id'].', '.uint32($_POST['amount']).', \''.$_POST['description'].'\', NOW())');
    error(T_ADDED, RET_SELF);
}

?>
