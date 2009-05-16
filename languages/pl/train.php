<?php
/**
 *   File functions:
 *   Polish language for school
 *
 *   @name                 : train.php                            
 *   @copyright            : (C) 2004,2005,2006 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @version              : 1.1
 *   @since                : 18.03.2006
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
// $Id: train.php 566 2006-09-13 09:31:08Z thindil $

define('ERROR', 'Niedozwolona akcja!');
define('YOU_DEAD', 'Nie możesz szkolić się, ponieważ jesteś martwy! <ul><li><a href=city.php>Wróć do miasta</a></li><li><a href=hospital.php>Wskrześ się</a></li></ul>');
define('NO_RACE', 'Nie możesz się szkolić ponieważ nie wybrałeś jeszcze rasy! <ul><li><a href=city.php>Wróć do miasta</a></li><li><a href=card.php?action=race>Wybierz rasę</a></li></ul>');
define('NO_CLASS', 'Nie możesz się szkolić ponieważ nie wybrałeś jeszcze klasy! <ul><li><a href=city.php>Wróć do miasta</a></li><li><a href=klasa.php>Wybierz klasę</a></li></ul>');
define('ENERGY', 'energii za 0,06');
$arrStatsDesc = array('Siły', 'Zręczności', 'Szybkości', 'Wytrzymałości', 'Inteligencji', 'Siły Woli');

if ($player -> location == 'Altara')
{
    define('TRAIN_INFO', 'Witaj w Akademii Umiejętności.'.$city1);
}
    else
{
    define('TRAIN_INFO', 'Witaj w Akademii Umiejętności.'.$city2);
}
define('I_WANT', 'Chcę trenować moją');
$arrTrainedStats = array('Siłę', 'Zręczność', 'Szybkość', 'Wytrzymałość', 'Inteligencję', 'Siłę Woli');
define('T_AMOUNT', 'razy');
define('A_TRAIN', 'Trenuj');

if (isset ($_GET['action']) && $_GET['action'] == 'train') 
{
    define('HOW_MANY', 'Podaj ile razy chcesz ćwiczyć!');
    define('NO_ENERGY', 'Nie masz wystarczającej ilości energii.');
    define('YOU_GAIN', 'Zyskujesz ');
    define('COST', 'Koszt treningu: ');
	define('WILL_GAIN', 'Zyskasz');
	define('ENERGY_COST', 'energii.');
}
?>
