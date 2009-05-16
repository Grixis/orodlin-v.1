<?php
/**
 *   File functions:
 *   Reputation preview page.
 *
 *   @name                 : help.php                            
 *   @copyright            : 
 *   @author               : albitos.snape@gmail.com
 *   @version              : 1.0
 *   @since                : 03.09.2007
 *
 */

//
//
//       This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; either version 2 of the License, or
//   (aty our option) any later version.
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
// 

$title = "Reputacja";
require_once("includes/head.php");

/**
* Get the localization for game
*/
require_once("languages/".$player -> lang."/reputation.php");


if(!isset($_GET['id']) || empty($_GET['id']))
{
    $aTop = $db -> getAll('SELECT sum(reputation.points ) as points , players.user, reputation.player_id
                            FROM reputation
                            LEFT JOIN players ON players.id = reputation.player_id
                            GROUP BY reputation.player_id ORDER BY points DESC');
    
    $smarty -> assign('Top', $aTop);
}
else
{
    $aData = $db -> getAll('SELECT * FROM reputation WHERE player_id = '.(int)$_GET['id']);
    $smarty -> assign('id', (int)$_GET['id']);    
    $smarty -> assign('Reps', $aData);
}
$smarty -> display('reputation.tpl');
require_once("includes/foot.php");
?>
