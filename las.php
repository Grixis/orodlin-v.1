<?php
/**
 *   File functions:
 *   Forest menu
 *
 *   @name                 : las.php
 *   @copyright            : (C) 2004,2005,2006 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @version              : 1.3
 *   @since                : 14.09.2007
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
// $Id: las.php 673 2006-10-05 15:32:49Z thindil $

$title = 'Las Krętych Ścieżek';
$title1 = $title;
require_once('includes/head.php');

/**
* Set the language
*/
require_once('languages/'.$player -> lang.'/las.php');

if($player -> location != 'Las')
{
    error (NOT_IN, RET_LOC);
}

$smarty -> assign('Message', '');

if ($player -> hp < 1)
{
    if (isset($_GET['action']) && $_GET['action'] == 'back')
    {
        $db -> Execute('UPDATE `players` SET `miejsce`=\'Altara\' WHERE `id`='.$player -> id);
        error (PL_DEAD.'<a href="hospital.php">'.A_HERE.'</a>.');
    }
    if (isset($_GET['action']) && $_GET['action'] == 'hermit')
    {
        $crneed = (75 * $player -> level);
        require_once('includes/counttime.php');
        $arrTime = counttime();
        $strTime = $arrTime[0].$arrTime[1];
        $smarty -> assign(array('Goldneed' => $crneed,
                                'Waittime' => $strTime));
        if (isset($_GET['action2']) && $_GET['action2'] == 'resurect')
        {
            require_once('includes/resurect.php');
            $smarty -> assign('Message', YOU_RES.$pdpr.LOST_EXP);
        }
        if (isset($_GET['action2']) && $_GET['action2'] == 'wait')
        {
            $smarty -> assign('Message', WAIT_INFO);
        }
    }
}

/**
* Initialization of variables
*/
if (!isset($_GET['action']))
{
    $_GET['action'] = '';
}
if (!isset($_GET['action2']))
{
    $_GET['action2'] = '';
}

/**
* Assign variables to template and display page
*/
$smarty -> assign('Health', $player -> hp);
$smarty -> display ('las.tpl');

require_once('includes/foot.php');
?>
