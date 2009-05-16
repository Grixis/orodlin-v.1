<?php
/**
 *   File functions:
 *   Labyrynth in forrest city
 *
 *   @name                 : maze.php
 *   @copyright            : (C) 2004,2005,2006 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @version              : 1.3
 *   @since                : 30.10.2006
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
// $Id: maze.php 799 2006-10-30 14:42:24Z thindil $

define('NO_LIFE', 'Nie masz wystarczająco dużo życia aby walczyć.');
define('FIGHT2', '. Czy chcesz spróbować walki?');
define('FIGHT1', 'Nie możesz wędrować po opuszczonym szybie, ponieważ jesteś w trakcie walki!<br />Napotkałeś ');
define('Y_TURN_F', 'Tak (turowa walka)');
define('Y_NORM_F', 'Tak (szybka walka)');
define('YOU_MEET', 'Napotkałeś');
define('A_EXPLORE', 'Zwiedzaj dalej');

if (isset($_GET['step']) && $_GET['step'] == 'run')
{
    define('ESCAPE_SUCC', 'Udało ci się uciec przed');
    define('ESCAPE_SUCC2', 'Zdobywasz za to');
    define('ESCAPE_SUCC3', 'PD.');
    define('ESCAPE_FAIL', 'Nie udało ci się uciec przed');
    define('ESCAPE_FAIL2', 'Rozpoczyna się walka');
    define('R_SPE5', 'szybkości');
}

if (!isset($_GET['action']))
{
    define('MAZE_INFO', 'Opis Opusczonego szybu');
}
    else
{
	if(!defined('NO_ENERGY'))
    define('NO_ENERGY', 'Nie masz energii!!');
	if(!defined('YOU_DEAD'))
    	define('YOU_DEAD', 'Nie możesz zwiedzać, ponieważ jesteś martwy!');
    define('EMPTY_1', 'Nic ne znalazles 1');
    define('EMPTY_2', 'Nic ne znalazles 2');
    define('EMPTY_3', 'Nic ne znalazles 3');
    define('EMPTY_4', 'Nic ne znalazles 4');
    define('EMPTY_5', 'Nic ne znalazles 5');
    define('EMPTY_6', 'Nic ne znalazles 6');
    define('F_HERBS', 'Znalazłes zioła. Zdobyłeś ');
    define('I_AMOUNT', ' sztuk ');
    define('F_LUMBER', 'Znalazłes drewno. Zdobywasz ');
    define('T_PINE', ' drewna sosnowego.');
    define('T_HAZEL', ' drewna z leszczyny.');
    define('T_YEW', ' drewna cisowego.');
    define('T_ELM', ' drewna z wiązu.');
    define('F_MITHRIL', 'To mithril! Udaje Ci się odnaleźć ');
    define('M_AMOUNT', ' sztuk mithrilu. Zatrzymujesz je i kontynuujesz wędrówkę.');
    define('F_ENERGY', ' Odzyskujesz 1 energi.');
    define('F_STAFF', ' Twoją uwagę zwraca jedna smukły przedmiot leżący za księgą. Podnosisz go. To ');
    define('F_STAFF2', '! Postanawiasz wziąć ją ze sobą.');
    define('F_ASTRAL', 'Idziesz po labiryncie i napotykasz na stary wytarty kawałek papieru. Okazuje się że jest to ');
}
?>
