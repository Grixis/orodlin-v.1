<?php
/**
 *   File functions:
 *   Polish language for ap.php
 *
 *   @name                 : ap.php                            
 *   @copyright            : (C) 2004-2005 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @version              : 0.8 beta
 *   @since                : 05.03.2005
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

define('NO_CLASS', 'Nie można rozdysponowywać AP bez wybranej rasy i klasy! (<a href="stats.php">Wróć</a>) ');
define('NO_AP3', 'Nie posiadasz żadnych AP do rozdysponowania! (<a href="stats.php">Wróć</a>)');
if (isset ($_GET['step']) && $_GET['step'] == 'add') 
{
    define('EMPTY_FIELDS', 'Wypełnij wszystkie pola!');
    define('ERROR', 'Niedozwolona operacja!');
    define('NO_AP', 'Nie posiadasz tylu Astralnych Punktów!');
    define('NO_AP2', 'Nie zostały przydzielone żadne Astralne Punkty!');
    define('A_STRENGTH', 'Siły');
    define('A_AGILITY', 'Zręczności');
    define('A_INTELIGENCE', 'Inteligencji');
    define('A_WISDOM', 'Siły Woli');
    define('A_SPEED', 'Szybkości');
    define('A_CONDITION', 'Wytrzymałości');
    define('YOU_GET', 'Zyskujesz');
    define('CLICK', 'Powrót');
    define('FOR_A', ' do statystyk');
}
if (!isset ($_GET['step']))
{
    define('AP_INFO', 'Tutaj możesz użyć AP do zwiększenia swoich statystyk. Wpisz w odpowiednie pola ilości AP, jakie chcesz przeznaczyć na rozwinięcie poszczególnych cech. Aktualnie posiadasz');
    define('AP', 'AP');
    define('A_ADD', 'Dodaj');
    define('PER_AP', 'za 1 AP');
    $arrStatDesc = array('Siły', 'Zręczności', 'Szybkości', 'Wytrzymałości', 'Inteligencji', 'Siły Woli');
    define('GET_BACK_DESC', 'Możesz także odzyskać użyte do tej pory AP i przydzielić je ponownie.');
    define('GET_BACK_LINK', 'Odzyskaj AP');
}
if (isset ($_GET['step']) && $_GET['step'] == 'reassign')
{
    define('TOO_OLD', 'Wyrosłeś z tej zabawki!');
    define('MAX_AP', 'Nie możesz odzyskać więcej AP!');
    define('RE_INFO', 'Możesz teraz odzyskać użyte do tej pory AP lub powrócić do przydzielania AP. <br /> <b>Pamiętaj!</b> Przywrócenie AP wiąże się z utratą cech otrzymanych przez przydzielnie tych punktów.<br /><br />');
    define('A_YES', 'Tak');
    define('A_NO', 'Nie');
    define('GOT_BACK_PRE', 'Odzyskałeś(aś)');
    define('GOT_BACK_MID', 'Astralnych Punktów. Posiadasz teraz łącznie');
    define('GOT_BACK_POST', 'AP, które możesz ponownie przydzielić do wybranych cech.');
}
?>
