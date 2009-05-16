<?php
/**
 *   File functions:
 *   Polish language for farms
 *
 *   @name                 : farm.php                            
 *   @copyright            : (C) 2004,2005,2006 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @version              : 1.2
 *   @since                : 23.08.2006
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
// $Id: farm.php 566 2006-09-13 09:31:08Z thindil $

define('ERROR', 'Zapomnij o tym!');
define('A_BACK', 'Wróć');
define('T_AMOUNT', 'ilość:');
define('NO_ENERGY', 'Nie masz tyle energii!');
define('A_REFRESH', 'Odśwież');

if (!isset($_GET['step']))
{
    define('FARM_INFO', 'Witaj na farmach. Możesz tutaj hodować zioła z których później wyrabia się mikstury.');
    define('A_PLANTATION', 'Plantacja');
    define('A_HOUSE', 'Chatka ogrodnika');
    define('A_ENCYCLOPEDIA', 'Encyklopedia roślin');
}

if (isset($_GET['step']) && $_GET['step'] == 'herbsinfo')
{
    define('HERBS_INFO', 'Encyklopedia roślin');
    define('ILANI_INFO', '<b>Zioło 1 (Illani)</b>Opis...');
    define('ILLANIAS_INFO', '<b>Zioło 2 (Illanias)</b> Opis...');
    define('NUTARI_INFO', '<b>Zioło 3 (Nutari)</b> Opis...');
    define('DYNALLCA_INFO', '<b>Zioło 4 (Dynallca)</b>Opis...');
}

if (isset($_GET['step']) && $_GET['step'] == 'house')
{
    define('HOUSE_INFO', 'Opis Chatki Ogrodnika');
    define('A_DRY', 'Wysusz');
    define('T_DRY', 'aby otrzymać');
    define('T_PACK', 'paczek nasion');
    define('HERB1', 'Zioło 1 (Illani)');
    define('HERB2', 'Zioło 2 (Illanias)');
    define('HERB3', 'Zioło 3 (Nutari)');
    define('HERB4', 'Zioło 4 (Dynallca)');
    define('NO_HERBS', 'Nie masz ziół do suszenia!');
    if (isset($_GET['action']) && $_GET['action'] == 'dry')
    {
        define('NO_HERB', 'Nie masz takiej ilości ziół!');
        define('YOU_DEAD', 'Nie możesz suszyć ziół ponieważ jesteś martwy!');
        define('YOU_MAKE', 'Wysuszyłeś <b>');
        define('T_HERB', '</b> sztuk ziół i otrzymaleś w zamian <b>');
        define('T_PACKS', '</b> paczek nasion oraz <b>');
        define('T_ABILITY', '</b> punktów do umiejętności zielarstwo.');
    }
}

if (isset($_GET['step']) && $_GET['step'] == 'plantation')
{
    define('FARM_INFO1', 'Witaj na plantacji w ');
    define('FARM_INFO2', '. Tutaj możesz hodować różne zioła.');
    define('NO_PLANT1', 'Nie masz jeszcze plantacji w ');
    define('NO_PLANT2', ' - kup ziemię pod nią za ');
    define('NO_PLANT3', ' sztuk mithrilu');
    define('A_UPGRADE', 'Rozbuduj plantację');
    define('A_SOW', 'Idź zasiać zioła');
    define('A_CHOP', 'Zbieraj zioła');
    define('FARMINFO', 'Farma w ');
    if (isset($_GET['action']) && $_GET['action'] == 'upgrade')
    {
        define('BUY_LAND', 'Zakup obszar ziemi');
        define('FIELD', 'Pole');
        define('GLASS', 'Szklarnia');
        define('IRRIGATION', 'Nawadnianie');
        define('CREEPER', 'Konstrukcje');
        define('T_MITH', ' sztuk mithrilu');
        define('BUY_GLASS', 'Zakup szklarnię');
        define('BUY_IRRIGATION', 'Zakup system nawadniania');
        define('BUY_CREEPER', 'Zakup konstrukcji na pnącza');
        define('T_GOLDCOINS', ' sztuk złota, ');
        define('PINE_PIECES', ' sosny');
        define('UPGRADE_INFO', 'Tutaj możesz dokupywać ziemie oraz wyposażenie do swojej farmy');
        define('NO_MITH', 'Brak wystarczającej ilości mithrilu!');
        if (isset($_GET['buy']))
        {
            define('BUYING_LAND', ' nowy obszar ziemi do swojej farmy.');
            define('BUYING_LANDS', ' nowe obszary ziemi do swojej farmy.');
            define('A_LANDS', ' Obecna ilość obszarów farmy: ');
            define('A_GLASS', ' Obecna ilość szklarni: ');
            define('A_IRRIGATION', ' Obecna ilość systemów nawadniających: ');
            define('A_CREEPER', ' Obecna ilość konstrukcji na pnącza: ');
            define('YOU_PAID', 'Zapłaciłeś');
            define('YOU_BUY', 'Dokupiłeś');
            define('NO_MONEY', 'Nie masz tylu pieniędzy przy sobie aby dokonać zakupu.');
            define('NO_LANDS', 'Nie masz miejsca aby dokupić kolejne ulepszenia do farmy.');
            define('BUYING_GLASS', ' nową szklarnię do swojej farmy.');
            define('BUYING_IRRIGATION', ' nowy system nawadniający do swojej farmy.');
            define('BUYING_CREEPER', ' nową konstrukcję na pnącza do swojej farmy.');
        }
    }
    if (isset($_GET['action']) && $_GET['action'] == 'sow')
    {
        define('SAW_INFO', 'Tutaj możesz zasiewać swoją farmę. Jedna paczka nasion starcza na zasianie 1 obszaru. Aby móc hodować odpowiednie zioła, musisz również posiadać odpowiednie wyposażenie. Listę wymaganych rzeczy możesz znaleźć w Encyklopedii roślin w poszczególnych opisach. Zasianie jednego obszaru ziemii kosztuje 0.2 energii, w zamian dostajesz 0.2 do umiejętności Zielarstwo.');
        define('A_SAW', 'Zasiej');
        define('T_LANDS', 'obszarów farmy ');
        define('HERB1', 'Zioło 1 (Illani)');
    	define('HERB2', 'Zioło 2 (Illanias)');
    	define('HERB3', 'Zioło 3 (Nutari)');
    	define('HERB4', 'Zioło 4 (Dynallca)');
        define('I_LANDS', 'Obszarów farmy:');
        define('I_GLASS', 'Szklarni:');
        define('I_IRRIGATION', 'Systemów nawadniających:');
        define('I_CREEPER', 'Konstrukcji na pnącza:');
        define('NO_FARM', 'Nie masz farmy aby siać zioła');
        define('NO_SEEDS', 'Nie masz nasion aby hodować zioła!');
        define('NO_LAND', 'Nie masz wolnych obszarów na farmie!');
        define('FREE_LANDS', 'Wolnych obszarów:');
        if (isset($_GET['step2']) && $_GET['step2'] == 'next')
        {
            define('NO_FREE', 'Nie masz tyle wolnych obszarów!');
            define('NO_SEED', 'Nie masz takiej ilości nasion!');
            define('YOU_DEAD', 'Nie możesz zasiać ziół ponieważ jesteś martwy!');
            define('YOU_SAW', 'Zasiałeś <b>');
            define('T_LANDS2', '</b> obszarów farmy ziołem ');
            define('YOU_GAIN', '. Zdobyłeś <b> ');
            define('T_ABILITY', '</b> poziomów w umiejętności Zielarstwo.');
            define('NO_ITEMS', 'Nie masz odpowiedniego wyposażenia na farmie aby hodować ten typ ziół!');
        }
    }
    if (isset($_GET['action']) && $_GET['action'] == 'chop')
    {
        define('CHOP_INFO', 'Tutaj możesz zbierać zioła które wcześniej zasiałeś na swojej farmie. Zebranie ziół z jednego pola kosztuje 0.2 energii. W zamian otrzymujesz 0.2 do umiejętności Zielarstwo. Zioła możesz zbierać już po jednym resecie od zasiania. Im dłużej będziesz je hodował tym więcej możesz ich zebrać. Jednak jeżeli zbyt długo będą hodowane, po prostu zwiędną. Poniżej znajduje się lista obecnie hodowanych na farmie ziół.');
        define('NO_HERBS', 'Nie hodujesz jakichkolwiek ziół!');
        define('T_AGE', 'wiek:');
        define('NO_FARM', 'Nie masz farmy aby zbierać zioła');
        if (isset($_GET['id']))
        {
            define('A_GATHER', 'Zbieraj');
            define('FROM_A', 'z');
            define('T_LANDS3', 'obszarów farmy.');
            define('NOT_YOUR', 'Nie możesz zbierać cudzych ziół!');
            define('TOO_YOUNG', 'Te zioła jeszcze nie wyrosły!');
            define('TO_MUCH', 'Nie możesz zbierać ziół z większej ilości obszarów niż zasiałeś!');
            define('YOU_DEAD', 'Nie możesz zbierać ziół ponieważ jesteś martwy!');
            define('YOU_GATHER', 'Zebrałeś <b>');
            define('T_AMOUNT2', '</b> sztuk ');
            define('T_FARM', ' z farmy. W zamian zdobyłeś ');
            define('T_ABILITY', ' poziomów w umiejętności Zielarstwo');
        }
    }
}
?>
