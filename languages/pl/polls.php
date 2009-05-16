<?php
/**
 *   File functions:
 *   Polish language for city polls
 *
 *   @name                 : polls.php
 *   @copyright            : (C) 2004,2005,2006 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @version              : 1.1
 *   @since                : 10.03.2006
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
// $Id: polls.php 566 2006-09-13 09:31:08Z thindil $

define('ERROR', 'Zapomnij o tym!');
define('T_VOTES', 'głosów');
define('A_BACK', 'Wróć');
define('SUM_VOTES', 'Głosowało');
define('T_MEMBERS', 'mieszkańców');
define('A_COMMENTS', 'Komentarze');

if (!isset($_GET['action']))
{
    if ($player -> location == 'Altara')
    {
        define('POLLS_INFO', '<p>Hala Zgromadzeń 1...</p>');
        define('POLLS_INFO2', '<p>Hala Zgromadzeń 2...</p>');
    }
        else
    {
        define('POLLS_INFO', '<p>Hala Zgromadzeń ...</p>');
    }
    define('NO_POLLS', 'Nie ma jeszcze ankiet');
    define('LAST_POLL', 'Oto ostatnia ankieta');
    define('A_SEND', 'Wyślij');
    define('A_LAST_10', 'Pokaż ostatnie 10 ankiet');
    define('POLL_DAYS', 'Ankieta potrwa jeszcze');
    define('T_DAYS', 'dni');
    define('POLL_END', 'Ankieta zakończona');
}

if (isset($_GET['action']) && $_GET['action'] == 'vote')
{
    define('VOTE_SUCC', 'Dziękujemy za oddanie głosu');
}

if (isset($_GET['action']) && $_GET['action'] == 'last')
{
    define('LAST_INFO', 'Oto lista ostatnich 10 ankiet');
}

if (isset($_GET['action']) && $_GET['action'] == 'comments')
{
    define('C_ADDED', 'Komentarz dodany!');
    define('C_DELETED', 'Komentarz skasowany!');
    define('NO_COMMENTS', 'Nie ma jeszcze komentarzy do tej ankiety!');
    define('A_DELETE', 'Skasuj');
    define('ADD_COMMENT', 'Dodaj komentarz');
    define('A_ADD', 'Dodaj');
    define('NO_PERM', 'Nie masz prawa przebywać tutaj!');
    define('EMPTY_FIELDS', 'Wypełnij wszystkie pola!');
    define('WRITED', 'napisał(a)');
    define('NO_TEXT', 'Nie ma takiej ankiety!');
}
?>
