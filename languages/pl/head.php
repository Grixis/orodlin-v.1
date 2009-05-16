<?php
/**
 *   File functions:
 *   Polish language for site header
 *
 *   @name                 : head.php
 *   @copyright            : (C) 2004,2005,2006 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @version              : 1.1
 *   @since                : 08.03.2006
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

define('CHARSET', 'utf-8');
define('E_ERRORS', 'Nastąpił błąd krytyczny i gra nie zadziałała poprawnie. Informacja o tym błędzie już została wysłana do władców.');

if (isset($title) && $title == 'Wieści')
{
    $title1 = 'Wieści';
    define('EMPTY_LOGIN', 'Proszę wypełnić wszystkie pola.');
    define('BANNED', 'Ponieważ zostałeś zbanowany, nie masz dostępu do gry. Wróć do <a href="index.php">strony głównej</a>.');
    define('E_LOGIN', 'Błąd przy logowaniu. Jeżeli nie jesteś zarejestrowany, zarejestruj się. W przeciwnym wypadku, sprawdź pisownię i spróbuj jeszcze raz. Wróć do <a href="index.php">strony głównej</a>.');
    define('MAX_PLAYERS', 'Przykro mi, ale ze względu na duże obciążenie serwera w grze może przebywać maksymalnie 120 graczy. Proszę spróbuj później lub wróć do <a href="index.php">strony głównej</a>.');
    define('ACCOUNT_BLOCKED', 'Nie możesz wejść do gry, ponieważ Twoje konto jest nieaktywne jeszcze przez ');
    define('ACCOUNT_DAYS', ' dni. Wróć do <a href="index.php">strony głównej</a>.');
}

define('E_SESSIONS', 'Sesja zakończona. Wróć do <a href="index.php">strony głównej</a>.');
define('E_PLAYER', 'Nie ma takiego gracza.');
define('REASON', 'Przyczyna wyłączenia gry:');
define('BACK', 'Wróć');
define('TEAM', 'Team Orodlin');
define('UNFINISHED', 'Twoja postać jest niedokończona. Do właściwego grania w naszym świecie musisz mieć wybraną <i>płeć</i>, <i>rasę</i> i <i>klasę</i>, inaczej większość z lokacji dostępnych w <i>Królestwie Orodlin</i> będzie niedostępnych.<p>&raquo; <a href="card.php">Przejdź do kreatora postaci</a></p>');
define('RET_SELF', 0);
define('RET_SELF', 1);
define('SHOW_IMAGE', 'kliknij aby wyświetlić obraz');
?>
