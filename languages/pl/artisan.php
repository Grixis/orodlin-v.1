<?php
/**
 *   File functions:
 *   Polish language for includes/artisan.php
 *
*   @name                 : artisan.php
 *   @copyright            : (C) 2007 Orodlin Team based on Vallheru Engine 1.3
 *   @author               : eyescream <tduda@users.sourceforge.net>
 *   @version              : 1.4
 *   @since                : 22.03.2007
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
// $Id$

define('YOU_HAVE_LOST_A_PLAN', 'Podczas próby wykonania komponentu plan uległ zniszczeniu!');
define('ARTISAN', 'Rzemieślnik');

    define('I_NAME', 'Nazwa');
    define('I_COST', 'Cena');
    define('I_LUMBER', 'Sztuk drewna');
    define('I_BARS', 'Sztabek');
    define('I_LEVEL', 'Poziom');
    define('I_OPTION', 'Opcje');
    define('A_BUY', 'Kup');
    define('NO_MONEY', 'Nie stać cię!');
    define('YOU_PAY', 'Zapłaciłeś');
    define('NOT_FOR_SALE', 'Tutaj tego nie sprzedasz.');
    define('CREATION_INFO', 'Tutaj możesz wykonywać przedmioty, których plany posiadasz. Aby wykonać przedmiot, musisz posiadać również odpowiednią ilość surowców. Każda próba kosztuje Cię tyle energii jaki jest poziom przedmiotu. Nawet za nieudaną próbę dostajesz 0,01 do umiejętności.');
    define('INFO', 'Oto lista przedmiotów, które możesz wykonywać. Jeżeli nie masz tyle energii aby wykonać ów przedmiot, możesz po prostu wykonywać go po kawałku');

    define('INFO2', 'Obecnie wykonujesz');
    define('I_PERCENT', 'Wykonany(w %)');
    define('I_ENERGY', 'Potrzeba jeszcze');
    define('I_ENERGY2', 'energii');

if ( (isset ($_GET['kowal']) && $_GET['kowal'] == 'plany') || (isset ($_GET['mill']) && $_GET['mill'] == 'plany') || isset($_GET['step']) && $_GET['step'] == 'plans')
{
    define('YOU_HAVE', 'Masz już taki plan!');
    define('NO_PLAN', 'Nie ma takiego planu.');
    define('AND_BUY', 'sztuk złota, i kupiłeś za to nowy plan przedmiotu');
    define('WRONG_CLASS', 'Tylko rzemieślnik może kupić ten plan!'); // for jewellers
}

if (isset ($_GET['alchemik']) && $_GET['alchemik'] == 'przepisy' && isset($_GET['buy']))
{
    define('YOU_HAVE', 'Masz już taki przepis!');
    define('NO_PLAN', 'Nie ma takiego przepisu. Wróć do <a href=\'alchemik.php?alchemik=przepisy\'>sklepu</a>.');
    define('AND_BUY', 'sztuk złota, i kupiłeś za to nowy przepis na');
}

if ( (isset($_GET['kowal']) && $_GET['kowal'] == 'astral') || (isset($_GET['mill']) && $_GET['mill'] == 'astral') || (isset($_GET['alchemik']) && $_GET['alchemik'] == 'astral') || (isset($_GET['step']) && $_GET['step'] == 'astral'))
{
    if ( (isset($_GET['kowal']) && $_GET['kowal'] == 'astral') || (isset($_GET['mill']) && $_GET['mill'] == 'astral') ||  (isset($_GET['step']) && $_GET['step'] == 'astral'))
    {
        define('ADAMANTIUM', 'Brył adamantium');
        define('CRYSTAL', 'Kryształów');
        define('METEOR', 'Kawałków meteorytu');
        define('PINE', 'Drewna sosnowego');
        define('YEW', 'Drewna z leszczyny');
        define('HAZEL', 'Drewna cisowego');
        define('ELM', 'Drewna z wiązu');
        define('COPPER', 'Sztabek miedzi');
        define('BRONZE', 'Sztabek brązu');
        define('BRASS', 'Sztabek mosiądzu');
        define('IRON', 'Sztabek żelaza');
        define('STEEL', 'Sztabek stali');
        define('COAL', 'Brył węgla');

        define('ASTRAL_INFO', 'Tutaj możesz wykonywać różne astralne konstrukcje, jeśli tylko posiadasz przy sobie ich kompletne plany.');
        define('CONST1', 'Astralny komponent');
        define('CONST2', 'Gwiezdny portal');
        define('CONST3', 'Świetlny obelisk');
        define('CONST4', 'Płomienny znicz');
        define('CONST5', 'Srebrzysta fontanna');

        define('JEWELLERY1', 'Bransoleta Świtu');
        define('JEWELLERY2', 'Naszyjnik Prawdy');
        define('JEWELLERY3', 'Berło Mądrości');
        define('JEWELLERY4', 'Lśniący Diadem');
        define('JEWELLERY5', 'Pierścień Przeznaczenia');
        define('A_BUILD', 'Buduj');
    }
    if ( isset($_GET['alchemik']) && $_GET['alchemik'] == 'astral')
    {
        define('HERB1', 'Illani');
        define('HERB2', 'Nutari');
        define('HERB3', 'Illanias');
        define('HERB4', 'Dynalca');

        define('ASTRAL_INFO', 'Tutaj możesz wykonywać różne astralne mikstury, jeśli tylko posiadasz przy sobie ich kompletne plany.');
        define('POTION1', 'Magiczna esensja');
        define('POTION2', 'Gwiezdna maść');
        define('POTION3', 'Eliksir Illuminati');
        define('POTION4', 'Astralne medium');
        define('POTION5', 'Magiczny absynt');
        define('A_BUILD', 'Warz');
    }
    define('NO_ASTRAL_PLANS', 'Nie posiadasz żadnych kompletnych planów przedmiotów, jakie mógłbyś tu wykonywać!');
    define('T_NAME', 'Nazwa');
    define('NO_AMOUNT', 'Nie masz takiej ilości ');
    define('NO_MITH', 'Nie masz takiej ilości mithrilu');
    define('NO_ENERGY', 'Nie masz takiej ilości energii');
    define('YOU_MAKE', 'Wykonałeś ');
    define('YOU_GAIN11', '! Zdobywasz ');
    define('YOU_GAIN12', ' punktów doświadczenia oraz ');
    define('YOU_GAIN13', ' poziom(y) w umiejętności kowalstwo. ');
    define('YOU_FAIL', 'Próbowałeś wykonać ');
    define('YOU_FAIL2',', niestety nie udało się.');
    define('YOU_USE', 'Zużyłeś na to:<br />');
}