<?php
/**
 *   File functions:
 *   Monuments - the best players in various fields.
 *
 *   @name                 : monuments.php
 *   @copyright            : (C) 2004,2005,2006, 2007 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @author               : eyescream <tduda@users.sourceforge.net>
 *   @version              : 1.4
 *   @since                : 17.05.2007
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

$title = 'Posągi';
require('includes/head.php');

/**
* Get the localization for game
*/
require('languages/'.$player -> lang.'/monuments.php');

if ($player -> location != 'Altara' && $player -> location != 'Ardulith')
{
    error (ERROR);
}

// Setup Smarty caching, normally this should go to includes/head.php
$smarty -> cache_dir = 'cache/';
$smarty -> caching = 2;
$smarty -> cache_lifetime = 3600;   // Monuments will be refreshed after 1 hour from first viewing.
// End of Smarty setup.

if (!$smarty -> is_cached('monuments.tpl'))
{
    // Store old fetch mode (probably ADODB_FETCH_ASSOC, defined in includes/config.php) and set new, a bit faster.
    $oldFetchMode = $db -> SetFetchMode(ADODB_FETCH_NUM);
    $arrMonuments = array(array('level', 'wins', 'credits`+`bank'),
                          array('strength', 'wytrz', 'inteli', 'wisdom', 'szyb', 'agility'),
                          array('atak', 'shoot', 'magia', 'unik', 'leadership'),
                          array('ability', 'fletcher', 'alchemia', 'herbalist', 'jeweller', 'breeding', 'mining', 'lumberjack', 'hutnictwo'));

    for ($i=0, $max1 = sizeof($arrMonuments), $arrResult = array(); $i<$max1; $i++)
    {
        for ($j=0, $max2 = sizeof($arrMonuments[$i]), $arrResult[$i] = array(); $j<$max2; $j++)
        {
            $arrResult[$i][$j] = $db -> GetAll('SELECT `id`, `user`, `'.$arrMonuments[$i][$j].'` FROM `players` ORDER BY `'.$arrMonuments[$i][$j].'` DESC LIMIT 5');
        }
    }
    // Restore old mode.
    $db -> SetFetchMode($oldFetchMode);
    // Assign by reference - less copying, less memory used.
    $smarty -> assign_by_ref('Groups', $arrGroups);
    $smarty -> assign_by_ref('Titles', $arrTitles);
    $smarty -> assign_by_ref('Descriptions', $arrDescriptions);
    $smarty -> assign_by_ref('Monuments', $arrResult);
    $smarty -> display ('monuments.tpl');
    // After this point, arrays aren't used and can be safely destroyed. Free memory!
    unset($arrGroups, $arrTitles, $arrDescriptions, $arrMonuments, $arrResult);
}
    else
{
    $smarty -> display ('monuments.tpl');
}
// Disable caching, to ensure that foot.php works in good, old, ineffective way.
$smarty -> caching = 0;
require('includes/foot.php');
?>
