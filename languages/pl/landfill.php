<?php
/**
 *   File functions:
 *   Polish language for landfill
 *
 *   @name                 : landfill.php                            
 *   @copyright            : (C) 2004,2005,2006 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @version              : 1.1
 *   @since                : 28.03.2006
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
// $Id: landfill.php 127 2006-04-12 18:32:13Z thindil $

define('ERROR', 'Zapomnij o tym');
define('GOLD_COINS', 'sztuk złota.');
define('YOU_DEAD', 'Nie możesz pracować, ponieważ jesteś martwy!');

if (isset($_GET['action'])) 
{
    define('NO_AMOUNT', 'Podaj ile czasu chcesz spędzić pracując!');
    define('NO_ENERGY', 'Nie masz tyle energii aby pracować.');
    define('IN_WORK', 'Podczas pracy zużyłeś');
    define('IN_WORK2', 'punkt(ów) energii i zarobiłeś');
    define('A_BACK', 'Wróć');
}
    else
{
	if ($player -> location == 'Altara')
	{
        	define('LAND_INFO', 'Opis Pracy w '.$city1);
	}
	if ($player -> location == 'Ardulith')
	{
        	define('LAND_INFO', 'Opis Pracy w '.$city2);
	}


    define('A_WORK', 'Pracuj');
    define('TIMES', 'razy.');
}
?>
