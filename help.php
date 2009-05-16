<?php
/**
 *   File functions:
 *   History of world and help to game
 *
 *   @name                 : help.php
 *   @copyright            :
 *   @author               :
 *   @version              :
 *   @since                :
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
//

$title = "Pomoc";
require_once("includes/head.php");

/**
* Get the localization for game
*/

$Page = isset($_GET['page']) ? $_GET['page'] : '';

require_once('languages/'.$player->lang.'/help.php');

/**
* Assign variables to template and display page
*/
$smarty -> assign(array('Page' => $Page,
			'BackLocation' => $_SERVER['HTTP_REFERER']));

$smarty -> display ('help.tpl');

require_once("includes/foot.php");
?>
