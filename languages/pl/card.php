<?php
/**
 *   File functions:
 *   Polish language for card.php 
 *
 *   @name                 : card.php                            
 *   @copyright            : (C) 2007 Team  based on Vallheru
 *   @author               : Marek Stasiak <l3thal2@gmail.com>
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
define('C_BACK', 'Wróć');
define('C_BADCHOICE', 'Zapomnij o tym!');
define('C_YHAVE', '&raquo; Twoja postać jest już stworzona.');
define('C_BGENDER', 'Zły wybór. <a href="card.php?action=start">Wybierz płeć</a> ponownie.');
define('C_NOGENDER', 'Nie masz jeszcze wybranej płci. <a href="card.php?action=gender">Wróć</a>, aby to zrobić.');
define('C_NORACE', 'Nie masz jeszcze wybranej rasy. <a href="card.php?action=race">Wróć</a>, aby to zrobić.');
define('C_WELCOME', '<p>Witaj w osobistym kreatorze postaci. Możesz tutaj wybrać wszystkie najważniejsze cechy swojej postaci, począwszy od <i>płci</i>, poprzez <i>rasę</i>, <i>klasę</i>, <i>wyznanie</i>, a także <i>miejsce zamieszkania</i>.</p>');
define('C_START', '<p>&raquo; <a href="card.php?action=gender">Rozpocznij tworzenie swojej postaci</a>.</p>');
define('C_DCHANGE', 'IV. Posiadasz już wyznanie. Możesz je jednak zmienić:<br /><br />&raquo; <a href="card.php?action=deity&amp;step=change">Zmień wyznanie</a>.');
define('C_YOUTHERE', '(tutaj się znajdujesz)');

define('C_G', '&raquo; <a href="card.php?action=gender">Wybierz płeć</a><br />');
define('C_R', '&raquo; <a href="card.php?action=race">Wybierz rasę</a><br />');
define('C_C', '&raquo; <a href="card.php?action=class">Wybierz klasę</a><br />');
define('C_D', '&raquo; <a href="card.php?action=deity">Wybierz wyznanie</a><br />');
define('C_P', '&raquo; <a href="card.php?action=place">Wybierz miejsce zamieszkania</a><br />');
define('C_GENDER', 'I. Wybierz płeć swojej postaci.');
define('C_RACE', 'II. Wybierz rasę swojej postaci.');
define('C_CLASS', 'III. Wybierz klasę swojej postaci.');
define('C_DEITY', 'IV. Wybierz wyznanie swojej postaci.');
define('C_PLACE', 'V. Wybierz miejsce zamieszkania.');
define('C_ATHEIST', 'Pozostań ateistą');
define('C_MALE', 'Mężczyzna');
define('C_FEMALE', 'Kobieta');
define('C_SELECT', 'Wybierz');
define('C_GSELECTED', 'Właśnie wybrałeś płeć swojej postaci. Teraz <a href="card.php?action=race">wybierz rasę</a>.');
define('C_RSELECTED', 'Właśnie wybrałeś rasę swojej postaci. Teraz <a href="card.php?action=class">wybierz klasę</a>.');
define('C_CSELECTED', 'Właśnie wybrałeś klasę swojej postaci. Teraz <a href="card.php?action=deity">wybierz wyznanie</a>.');
define('C_DSELECTED', 'Właśnie wybrałeś wyznanie swojej postaci. Teraz <a href="card.php?action=place">wybierz miejsce zamieszkania</a>.');
define('C_PSELECTED', 'Wybrałeś miejsce zamieszkania. Teraz możesz przejść do <a href="city.php">miasta</a>.');
define('C_CS', 'Właśnie wybrałeś klasę swojej postaci. Wróć do <a href="card.php">kreatora postaci</a>.');
define('C_DS', 'Właśnie wybrałeś wyznanie swojej postaci. Wróć do <a href="card.php">kreatora postaci</a>.');

define('C_YES', 'Tak');
define('C_NO', 'Nie');

if (isset($_GET['step']) && $_GET['step'] == 'change')
{
    define('CHANGE', 'Czy na pewno chcesz zmienić swoje obecne wyznanie? Zmiana będzie ciebie kosztować');
    define('CHANGE2', 'punktów wiary.');
    define('YOU_CHANGE', 'Zrezygnowałeś z wiary w');
    define('YOU_MAY', '. Teraz możesz ponownie');
    define('A_SELECT2', 'wybrać');
    define('T_DEITY', 'wyznawane bóstwo.');
}

if (isset($_GET['action']) && isset($_GET['select'])) 
{
	if ($_GET['action'] == 'race')
	{
		if ($_GET['select'] == 'human') 
		{
			define('C_RACE_INFO', '<p>Opis rasy 1 (Ludzie)</p>');
		}

		if ($_GET['select'] == 'elf') 
		{
			define('C_RACE_INFO', '<p>Opis rasy 2 (Elfy)</p>');
		}

		if ($_GET['select'] == 'dwarf') 
		{
			define('C_RACE_INFO', '<p>Opis rasy 3 (Krasnoludy)</p>');
		}

		if ($_GET['select'] == 'hobbit') 
		{
			define('C_RACE_INFO', '<p>Opis rasy 4 (Hobbici)</p>');
		}

		if ($_GET['select'] == 'reptilion') 
		{
			define('C_RACE_INFO', '<p>Opis rasy 5 (Jaszczuroludzie)</p>');
		}

		if ($_GET['select'] == 'gnome') 
		{
			define('C_RACE_INFO', '<p>Opis rasy 6 (Gnomy)</p');
		}
	}

	if ($_GET['action'] == 'class')
	{
		if ($_GET['select'] == 'warrior') 
		{
			define('C_CLASS_INFO', '<p>Opis klasy 1 (Wojownik).</p>');
		}

		if ($_GET['select'] == 'mage') 
		{
			define('C_CLASS_INFO', 'Opis klasy 2 (Mag).</p>');
		}

		if ($_GET['select'] == 'artisan') 
		{
			define('C_CLASS_INFO', 'Opis klasy 3 (Rzemieslnik).</p>');
		}

		if ($_GET['select'] == 'barbarian') 
		{
			define('C_CLASS_INFO', 'Opis klasy 4 (Barbarzyńca).</p>');
		}

		if ($_GET['select'] == 'thief') 
		{
			define('C_CLASS_INFO', 'Opis klasy 5 (Złodziej).</p>');
		}
	}

	if ($_GET['action'] == 'deity')
	{
		if ($_GET['select'] == 'tartus') 
		{
			define('C_DEITY_INFO', '<p>Opis Bóstwa 1 (Tartus)</p>');
		}

		if ($_GET['select'] == 'anea') 
		{
			define('C_DEITY_INFO', '<p>Opis Bóstwa 2 (Anea)</p>');
		}

		if ($_GET['select'] == 'meltis') 
		{
			define('C_DEITY_INFO', '<p>Opis Bóstwa 3 (Meltis)</p>');
		}

		if ($_GET['select'] == 'dintel')
		{
			define('C_DEITY_INFO', '<p>Opis Bóstwa 4 (Dintel)</p>');
		}

		if ($_GET['select'] == 'yala')
		{
			define('C_DEITY_INFO', '<p>Opis Bóstwa 5 (Yala)</p>');
		}

		if ($_GET['select'] == 'unrod')
		{
			define('C_DEITY_INFO', '<p>Opis Bóstwa 6 (Unrod)</p>');
		}

		if ($_GET['select'] == 'luzubal')
		{
			define('C_DEITY_INFO', '<p>Opis Bóstwa 7 (Luzubal)</p>');
		}

		if ($_GET['select'] == 'gulgard')
		{
			define('C_DEITY_INFO', '<p>Opis Bóstwa 8 (Gulgard)</p>');
		}

		if ($_GET['select'] == 'artis')
		{
			define('C_DEITY_INFO', '<p>Opis Bóstwa 9 (Artis)</p>');
		}
	}

	if ($_GET['action'] == 'place')
	{
		if ($_GET['select'] == 'meredith') 
		{
			define('C_PLACE_INFO', '<p>Opis Miasta 1 (Meridith)</p>');
		}

		if ($_GET['select'] == 'agarakar')
		{
			define('C_PLACE_INFO', '<p>Opis Miasta 2 (Agarakar)</p>');
		}
	}
}
?>
