<?php
/**
 *   File functions:
 *   Polish language for warehouse
 *
 *   @name                 : warehouse.php
 *   @copyright            : (C) 2004,2005,2006 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @author               : eyescream <tduda@users.sourceforge.net>
 *   @version              : 1.4
 *   @since                : 18.07.2007
 *
 */

// Published under GNU GPL 2 or later. See /install/README file for details.
// $Id$

define('ERROR', 'Zapomnij o tym!');
define('A_SELL', 'Sprzedaj');
define('A_BUY', 'Kup');
define('A_HISTORY', 'Historia');
define('A_BACK', 'Wróć');
if ($player -> location != 'Ardulith')
    define('WAREHOUSE_INFO', 'Wchodzisz do Magazynu Królewskiego. Oto ceny obowiązujące dzisiaj:</i>');
else
    define('WAREHOUSE_INFO', 'Wchodzisz do Magazynu Królewskiego. Oto ceny obowiązujące dzisiaj:');
define('WAREHOUSE_INFO2', 'Co możesz nam zaoferować?');
define('WAREHOUSE_INFO3', 'Co chcesz kupić?');

if (!isset($_GET['action']))
{
    $arrItemNames = array('Ruda miedzi', 'Ruda cynku', 'Ruda cyny', 'Ruda żelaza', 'Sztabki miedzi', 'Sztabki brązu', 'Sztabki mosiądzu', 'Sztabki żelaza', 'Sztabki stali', 'Bryły węgla', 'Bryły adamantium', 'Kawałki meteorytu', 'Kryształy', 'Drewno sosnowe', 'Drewno z leszczyny', 'Drewno cisowe', 'Drewno z wiązu', 'Mithril', 'Illani', 'Illanias', 'Nutari', 'Dynallca', 'Nasiona Illani', 'Nasiona Illanias', 'Nasiona Nutari', 'Nasiona Dynallca');

    define('T_MIN', 'Minerał');
    define('T_HERB', 'Zioło');
    define('T_COST', 'Skup / Sprzedaż');
    define('T_ACTION', 'Akcje');
    define('T_AMOUNT', 'Ilość');
    define('T_HISTORY', 'Historia');
    define('CARAVAN_VISIT', '<p>Dzisiaj odwiedziła magazyn karawana z dalekich krain wykupując co nieco minerałów.<br /></p>');
}

if (isset($_GET['action']))
{
    $arrItemNames = array('rudy miedzi', 'rudy cynku', 'rudy cyny', 'rudy żelaza', 'sztabek miedzi', 'sztabek brązu', 'sztabek mosiądzu', 'sztabek żelaza', 'sztabek stali', 'brył węgla', 'brył adamantium', 'kawałków meteorytu', 'kryształów', 'drewna sosnowego', 'drewna z leszczyny', 'drewna cisowego', 'drewna z wiązu', 'mithrilu', 'illani', 'illanias', 'nutari', 'dynallca', 'nasion illani', 'nasiona illanias', 'nasion nutari', 'nasion dynallca');
    if ($_GET['action'] == 'sell' || $_GET['action'] == 'buy')
    {
        define('YOU_SELL', 'Sprzedałeś ');
        define('AMOUNT', ' sztuk ');
        define('EACH_FOR', '(cena za sztukę: ');
        define('FOR_A', ' za ');
        define('GOLD_COINS', ' sztuk złota.');
        define('YOU_HAVE', ' spośród posiadanych ');
        define('W_AMOUNT', ' w magazynie znajduje się ');
        define('NO_MONEY', 'Nie masz tylu sztuk złota przy sobie!');
        define('YOU_BUY', 'Kupiłeś ');
        if ($_GET['action'] == 'sell')
            define('NO_AMOUNT', 'Nie masz tyle sztuk ');
        else
            define('NO_AMOUNT', 'Nie ma tyle sztuk ');
    }
    if ($_GET['action'] == 'history')
    {
        define('INFO1', 'Poniższy "wykres" przedstawia historię zmian dostępnej ilości i cen skupu ');
        define('INFO2', ' w ciągu ostatnich dni. Podana "cena" obowiązywała w danym dniu, ale "ilość" to stan magazynu po tym dniu, przed resetem głównym (lub stan bieżący w przypadku dnia dzisiejszego).');
        $arrHeaders = array('<p style="color:blue">Skup</p><p style="color:yellow">Sprzedaż</p>', 'Dzień', 'Ilość', 'Cena');
        define('NO_INFO', 'Brak danych! Wróć za parę dni.');
        define('TODAY', 'Dziś');
    }
}


?>
