<?php
/**
 *   File functions:
 *   Polish language for updates
 *
 *   @name                 : updates.php                            
 *   @copyright            : (C) 2004-2005 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @version              : 1.0
 *   @since                : 20.12.2005
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

define('A_CHANGE', 'Zmień');
define('T_DAY', ' dnia ');
define('T_DAY2', ' dnia <b>');
define('WRITE_BY', 'napisana przez');
define('A_LAST10', 'ostatnie 10 wieści');
define('ERROR', 'Zapomnij o tym!');
define('A_COMMENTS', 'Komentarze');
define('NO_TEXT', 'Nie ma takiego tekstu.');

if (isset($_GET['step']) && $_GET['step'] == 'comments')
{
    define('C_ADDED', 'Komentarz dodany!');
    define('C_DELETED', 'Komentarz skasowany!');
    define('NO_COMMENTS', 'Nie ma jeszcze komentarzy do tego tekstu!');
    define('A_DELETE', 'Skasuj');
    define('ADD_COMMENT', 'Dodaj komentarz');
    define('A_ADD', 'Dodaj');
    define('NO_PERM', 'Nie masz prawa przebywać tutaj!');
    define('A_BACK', 'Wróć');
    define('WRITED', 'napisał(a)');
}

if ($player -> logins < 5)
{
    define('NEW_PLAYER', '<p style="text-align: justify">Witaj w naszym świecie!<br />Ponieważ jesteś tu nowy, powinieneś przeczytać poniższe informacje.</p><p style="text-align: justify">Do grania w naszym świecie potrzebna jest Ci własna postać, którą wybrać możesz w <a href="card.php">Kreatorze postaci</a>. Bez wybranej postaci niemożliwe jest komfortowe granie, ponieważ nie masz dostępu do większości lokacji. Większość informacji na temat Królestwa '.$gamename.' znajdziesz w dziale <a href="help.php">Pomoc</a> lub w odpowiedziach na najczęściej zadawane pytania - <a href="faq.php" target="_blank">FAQ</a>. Jeżeli do tej pory nie zapoznałeś się z prawami naszego Królestwa, przejdź do <a href="court.php">Gmachu Sądu</a>. A jeśli wziąż masz jakieś pytania co do gry - możesz je zadać któremuś z Władców lub Książąt na pocztę, ewentualnie napisać swoje pytanie na <a href="forums.php?view=categories">Forum</a> lub zadać je innym graczom w <a href="chat.php">Karczmie</a>.</p>');
}
?>
