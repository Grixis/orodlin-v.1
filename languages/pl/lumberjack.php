<?php
/**
 *   File functions:
 *   Polish language for chop trees
 *
 *   @name                 : lumberjack.php                            
 *   @copyright            : (C) 2004,2005,2006 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @version              : 1.3
 *   @since                : 04.10.2006
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
// $Id: lumberjack.php 665 2006-10-04 11:51:45Z thindil $

define('ERROR', 'Zapomnij o tym!');
define('YOU_DEAD', 'Nie możesz wyrąbywać drewna, ponieważ jesteś martwy! (<a href="las.php">Wróć<a/>)');
define('NO_LICENSE', 'Nie masz licencji na wyrąb drewna w lesie! (<a href="las.php">Wróć</a>)');
define('A_BACK', 'Wróć');
$arrLumberKinds = array('sosnowym', 'leszczynowym', 'cisowym', 'wiązowym');

if (isset ($_GET['action']) && $_GET['action'] == 'chop')
{
    define('NO_ENERGY', 'Nie masz tyle energii!');
    define('YOU_GO', 'Przeznaczyłeś na wyrąb lasu ');
    define('T_ENERGY2', ' energii.<br />');
    define('T_PINE', ' sztuk drewna sosnowego<br />');
    define('T_HAZEL', ' sztuk drewna z leszczyny<br />');
    define('T_YEW', ' sztuk drewna cisowego<br />');
    define('T_ELM', ' sztuk drewna z wiązu<br />');
    define('T_GOLD', ' sztuk złota<br />');
    define('T_ABILITY', ' poziomu w umiejętności Drwalnictwo oraz <br />');
    define('T_GAIN_EXP', ' punktów doświadczenia.<br />');
    define('TREE_STOMP', 'Podczas wyrębu przewróciło się na Ciebie drzewo. ');
    define('YOU_LUCK', 'Na szczęście zdążyłeś uniknąć przygniecenia, jednak przerwało to na moment Twoją pracę.');
    define('YOU_UNLUCK', 'Niestety nie udało Ci się go uniknąć. Spadając na Ciebie zadało Tobie ');
    define('T_HITS', ' obrażeń.');
    define('NOTHING', 'Niestety nic nie znalazłeś.<br />');
    define('YOU_FIND', 'Zdobyłeś:<br />');
    define('DEAD_MAN', 'Podczas rąbania drewna potknąłeś się i spadłeś na trzonek swojej siekiery. Straciłeś wszystkie punkty życia.');
}
    else
{
    define('YOU_WANT', 'Na jakim rodzaju drewna chcesz się skupić?');
    define('A_CHOP', 'Przeznacz');
    define('ON_CHOP', 'na wyrąb drewna');
    define('T_ENERGY', 'energii.');
}
?>
