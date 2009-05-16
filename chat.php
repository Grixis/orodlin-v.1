<?php
/**
 *   File functions:
 *   Main file of chat - bot Innkeeper and private talk to other players
 *
 *   @name                 : chat.php
 *   @copyright            : (C) 2004,2005,2006, 2007 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @author               : eyescream <tduda@users.sourceforge.net>
 *   @version              : 1.3
 *   @since                : 17.04.2007
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

$title = 'Tawerna';
$title1 = $title;
require_once('includes/head.php');

/**
* Get the localization for game
*/
require_once('languages/'.$player -> lang.'/chat.php');

if (!isset($_GET['room'])) $_GET['room'] = 'izba';
$smarty -> assign(array(
				'Room' => $_GET['room'],
				'Playerid' => $player -> id,
				'Gender' => $player -> gender
				));
$smarty -> display ('chat.tpl');

require_once('includes/foot.php');
?>
