<?php
/**
 *   File functions:
 *   Polish language for lumbermill
 *
 *   @name                 : lumbermill.php
 *   @copyright            : (C) 2004, 2005, 2006 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @version              : 1.2
 *   @since                : 22.09.2006
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
// $Id: lumbermill.php 604 2006-09-22 13:44:10Z thindil $

define('A_BACK', 'Wróć');
define('ERROR', 'Zapomnij o tym!');
define('NO_MITH', 'Nie masz tyle mithrilu.');


if (!isset($_GET['mill']))
{
    define('MILL_INFO', 'Witaj w tartaku. Tutaj możesz wyrabiać łuki oraz strzały. Aby móc je wykonywać musisz najpierw posiadać plany odpowiedniej rzeczy oraz odpowiednią ilość surowców.');
    define('A_PLANS', 'Kup plany przedmiotu');
    define('A_MILL', 'Idź do tartaku');
    define('A_LICENSES', 'Zakup licencje na wyrąb lasu');
    define('A_ASTRAL', 'Buduj astralną konstrukcję');
}
if (isset($_GET['mill']) && $_GET['mill'] == 'plany')
{
    define('HERE_IS', 'Witaj w sklepie dla stolarzy. Tutaj możesz kupić plany przedmiotów, które chcesz wykonywać. Aby kupić dany plan, musisz mieć przy sobie odpowiednią ilość sztuk złota');
}
if (isset($_GET['mill']) && $_GET['mill'] == 'licenses')
{
    define('NO_LICENSES', 'Nie możesz zakupić kolejnych licencji');
    if (!isset($_GET['step']))
    {
        define('LICENSE1', 'Licencja ucznia drwalskiego (wyrąb Sosen - 1000 sztuk złota)');
        define('LICENSE2', 'Licencja starszego ucznia (wyrąb Leszczyny - 2000 sztuk złota, 10 sztuk mithrilu)');
        define('LICENSE3', 'Licencja eksperta (wyrąb Cisów - 10 000 sztuk złota, 50 sztuk mithrilu)');
        define('LICENSE4', 'Licencja mistrza (wyrąb Wiązów - 50 000 sztuk złota, 250 sztuk mithrilu)');
    }
    if (isset($_GET['step']) && $_GET['step'] == 'buy')
    {
        define('LICENSE1', 'ucznia drwalskiego.');
        define('LICENSE2', 'starszego ucznia.');
        define('LICENSE3', 'eksperta.');
        define('LICENSE4', 'mistrza.');
        define('YOU_BUY', 'Kupiłeś licencję ');
    }
}

if (isset ($_GET['mill']) && $_GET['mill'] == 'mill')
{
    if (isset($_GET['ko']) || isset($_GET['dalej']))
    {
        define('YOU_DEAD', 'Nie możesz wykonywać przedmiotu ponieważ jesteś martwy!');
        define('ASSIGN_EN', 'Przeznacz na wykonanie');
        define('M_ENERGY', ' energii.');
        define('A_MAKE', 'Wykonaj');
        define('L_HAZEL', 'z leszczyny');
        define('L_YEW', 'z cisu');
        define('L_ELM', 'z wiązu');
        define('L_HARDER', 'wzmocniony');
        define('L_COMPOSITE', 'kompozytowy');
    }
    if (isset($_GET['konty']) || isset($_GET['rob']))
    {
        define('NO_ENERGY', 'Nie dość energii.');
        define('DRAGON2', 'Smocze ');
        define('ELFS1', 'Elfi ');
        define('DWARFS1', 'Krasnoludzki ');
        define('YOU_MAKE', 'Wykonałeś <b>');
        define('AND_EXP2', '</b> PD oraz <b>');
        define('IN_MILL', '</b> poziomu w umiejętności Stolarstwo.<br />');
        define('M_ENERGY', ' energii.');
        define('L_HAZEL', 'z leszczyny');
        define('L_YEW', 'z cisu');
        define('L_ELM', 'z wiązu');
        define('L_HARDER', 'wzmocniony');
        define('L_COMPOSITE', 'kompozytowy');
        define('R_AGI', 'zręczności');
        define('R_STR', 'siły');
        define('R_INT', 'inteligencji');
    }
    if (isset($_GET['konty']))
    {
        define('TOO_MUCH', 'Nie możesz przeznaczyć na przedmiot więcej energii niż trzeba!');
        define('NO_ITEM', 'Nie wykonujesz takiego przedmiotu!');
        define('AND_GAIN2', '</b>. Zdobywasz <b>');
        define('YOU_TRY', 'Próbowałeś wykonać <b>');
        define('BUT_FAIL', '</b>, niestety nie udało się. Zdobywasz <b>');
        define('YOU_WORK', 'Poświęciłeś na wykonanie ');
        define('NEXT_EN', ' kolejne ');
        define('NOW_IS', ' energii. Teraz jest on wykonany w ');
        define('YOU_NEED2', ' procentach. Aby go ukonczyć potrzebujesz ');
    }
    if (isset($_GET['rob']))
    {
        define('HOW_MANY', 'Podaj ile przedmiotów chcesz wykonać!');
        define('NO_MAT', 'Nie masz tylu materiałów!');
        define('NO_PLANS', 'Nie masz takiego planu');
        define('AND_GAIN2', '</b> razy. Zdobywasz <b>');
        define('YOU_WORK', 'Pracowałeś nad ');
        define('YOU_USE', ', zużywając ');
        define('AND_MAKE', ' energii i wykonałeś go w ');
        define('TO_END', ' procentach. Aby ukończyć przedmiot potrzebujesz jeszcze ');
        define('YOU_MAKE2', 'Nie możesz wykonywać nowego przedmiotu ponieważ pracujesz już nad jednym!');
    }
}
?>
