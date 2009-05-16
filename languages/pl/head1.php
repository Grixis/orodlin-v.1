<?php
/**
 *   File functions:
 *   Polish language for site header
 *
 *   @name                 : head1.php
 *   @copyright            : (C) 2004,2005,2006 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @author               : Marek 'marq' Chodor <marek.chodor@gmail.com>
 *   @version              : 1.3
 *   @since                : 24.08.2007
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
// $Id: head1.php 807 2006-11-03 14:33:17Z thindil $

if (isset($title) && !isset($title1))
{
    $arrTitle = array('Opcje konta', 'Dodaj Plotkę', 'Dodaj Wieść', 'Apteka', 'Pracownia alchemiczna',
                      'Ekwipunek', 'Panel Administracyjny', 'Dystrybucja AP', 'Płatnerz', 'Skarbiec', 'Arena Walk', 'Fleczer',
                      'Tawerna', 'Altara', 'Polana Chowańców', 'Księga czarów', 'Wybierz wyznanie', 'Poszukiwania', 'Farma',
                      'Forum',  'Kanały', 'Pomoc', 'Rynek ziół', 'Galeria Bohaterów', 'Uzdrowiciel',
                      'Domy graczy', 'Rynek z przedmiotami', 'Lochy', 'Wybierz klasę', 'Kuźnia',
                      'Oczyszczanie miasta', 'Dziennik', 'Tartak', 'Wiadomości', 'Targowisko',
                      'Lista mieszkańców', 'Rynek z miksturami', 'Posągi', 'Miejskie Plotki', 'Notatnik',
                      'Strażnica', 'Rynek minerałów', 'Portal', 'Wybierz rasę', 'Hala zgromadzeń', 'Odpoczynek',
                      'Panel Sędziego', 'Statystyki', 'Świątynia', 'Forum klanu', 'Zegar słoneczny', 'Szkolenie', 'Stajnie',
                      'Zbrojownia klanu', 'Klany', 'Magazyn klanu', 'Zobacz', 'Zbrojmistrz', 'Magiczna wieża', 'Zasoby',
                      'Gmach sądu', 'Biblioteka', 'Królewski Skład', 'Redakcja gazety', 'Mapa', 'Centrum Poleconych',
                      'Góry Migotliwe', 'Las Krętych Ścieżek', 'Wyrąb', 'Aleja zasłużonych', 'Astralny skarbiec', 'Astralny rynek',
                      'Spis książąt', 'Jubiler', 'Rynek jubilerski', 'Kopalnia', 'Kopalnie', 'Huta', 'Astralny plan', 'Warsztat jubilerski','Historia walk', 'Ranking Graczy', 'Krasnoludzki Bank', 'Reputacja', 'Statystyki ogólne krainy', 'Schronisko');
        if ($player -> location != 'Ardulith')
        {
            $arrTitle2 = $arrTitle;
            $arrTitle2[11] = 'Fleczer';
            $arrTitle2[13] = $city1;
            $arrTitle2[67] = 'Las Krętych Ścieżek';
            $arrTitle2[72] = 'Sala audiencyjna';
            $arrTitle2[85] = 'Schronisko dla ubogich';
        }
            else
        {
            $arrTitle2 = $arrTitle;
            $arrTitle2[11] = 'Fleczer';
            $arrTitle2[13] = $city2;
            $arrTitle2[20] = 'Opuszczony szyb';
            $arrTitle2[30] = 'Podziemna wartownia';
            $arrTitle2[44] = 'Hala Zgromadzeń';
            $arrTitle2[62] = 'Królewski Skład';
            $arrTitle2[67] = 'Las Krętych Ścieżek';
            $arrTitle2[72] = 'Korzeń przeznaczenia';
            $arrTitle2[85] = 'Przytułek';
        }
    $intKey = array_search($title, $arrTitle);
    $title1 = $arrTitle2[$intKey];
}

define('SPELLS_BOOK', 'Księga Czarów');

if ($player -> location == 'Altara')
{
    define('CITY', $city1);
    define('B_ARENA', 'Arena Walk');
    define('HOSPITAL', 'Uzdrowiciel');
    define('MY_TRIBE', 'Mój klan');
    define('BANK', 'Skarbiec');
}

if ($player -> location == 'Ardulith')
{
    define('CITY', $city2);
    define('B_ARENA', 'Arena Walk');
    define('HOSPITAL', 'Uzdrowiciel');
    define('MY_TRIBE', 'Mój klan');
    define('BANK', 'Skarbiec');
}

if ($player -> location == 'Podróż')
{
    define('RETURN_TO', 'Powrót do przygody');
    define('RETURN_TO2', 'Podróż');
}

if ($player -> location == 'Góry')
{
    define('MOUNTAINS', 'Góry Migotliwe');
}

if ($player -> location == 'Las')
{
    define('FOREST', 'Las Krętych Ścieżek');
}

if ($player -> location == 'Lochy')
{
    define('JAIL', 'Lochy');
}

if ($player -> location == 'Portal')
{
    define('PORTAL', 'Portal');
}

if ($player -> location == 'Astralny plan')
{
    define('ASTRAL_PLAN', 'Astralny plan');
}

if ($player -> tribe)
{
    define('T_FORUM', 'Forum klanu');
}

define('KING', 'Król');
define('PRINCE', 'Książę');
if (!defined('BACK')) define('BACK', 'Wróć');

if ($player -> rank == 'Sędzia')
{
    define('JUDGE', 'Sędzia');
}

define ('TECH_PANEL', 'Panel technicznego');

define('ESCAPE', 'Kiedy próbowałeś uciec przez płot areny, potwór dopadł ciebie. Poczułeś na karku jego oddech i to była ostatnia rzecz jaką pamiętasz');
define('GAME_TIME', 'Czas w grze');
define('LEVEL', 'Poziom');
define('EXP_PTS', 'PD');
define('HEALTH_PTS', 'Zdrowie');
define('MANA_PTS', 'Punkty Magii');
define('ENERGY_PTS', 'Energia');
define('GOLD_IN_HAND', 'Złoto');
define('GOLD_IN_BANK', 'Bank');
define('MITHRIL', 'Mithril');
define('VALLARS', 'Reputacja');
define('NAVIGATION', 'Nawigacja');
define('N_STATISTICS', 'Statystyki');
define('N_ITEMS', 'Zasoby');
define('N_EQUIPMENT', 'Ekwipunek');
define('N_LOG', 'Dziennik');
define('N_NOTES', 'Notatnik');
define('N_POST', 'Wiadomości');
define('N_FORUMS', 'Forum');
define('N_INN_I', 'Tawerna - izba');
define('N_INN_P', 'Piwnica Tawerny');
define('N_OPTIONS', 'Opcje konta');
define('N_LOGOUT', 'Wylogowanie');
define('N_HELP', 'Pomoc');
define('YES', 'Tak');
define('NO', 'Nie');
define('N_MAP', 'Mapa');
if (!defined('TEAM'))
{
	define('TEAM', 'Team Orodlin');
}
define('FAQ', 'FAQ');
?>
