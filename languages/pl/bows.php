<?php
/**
 *   File functions:
 *   Polish language for fletcher shop
 *
 *   @name                 : bows.php                            
 *   @copyright            : (C) 2004,2005,2006 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @version              : 1.1
 *   @since                : 28.03.2006
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
// $Id: bows.php 127 2006-04-12 18:32:13Z thindil $

define('ERROR', 'Zapomnij o tym!');
if (isset ($_GET['buy']) || isset($_GET['step'])) 
{
    define('NO_ITEM', 'Nie ma takiego przedmiotu');
    define('NO_MONEY', 'Nie stać cię!');
    define('E_DB', 'nie mogę dodać');
    define('YOU_BUY', 'Zapłaciłeś');
    define('GOLD_COINS', 'sztuk złota, ale teraz masz nowy');
    define('DAMAGE', 'do Obrażeń.');
    define('WITH', 'z');
}
    else
{
    if ($player -> location != 'Ardulith')
    {
        define('SHOP_INFO', 'Opis Felczera');
        define('SHOP_INFO2', '<br> Opis Felczera 2');
    }
        else
    {
        define('SHOP_INFO', 'Opis Felczera');
    }
    define('I_NAME', 'Nazwa');
    define('I_DUR', 'Wt.');
    define('I_EFECT', 'Efekt');
    define('I_COST', 'Cena');
    define('I_LEVEL', 'Wymagany poziom');
    define('I_SPEED', 'Szyb');
    define('I_OPTION', 'Opcje');
    define('A_BUY', 'Kup');
    define('A_STEAL', 'Kradzież');
}

if (isset($_GET['arrows']))
{
    define('T_ARROWS', 'kołczanów');
    define('T_AMOUNT', 'sztuk złota każdy lub');
    define('FOR_A', 'za');
    define('T_AMOUNT2', 'sztuk');
    define('T_AMOUNT3', 'sztuk złota każda.');
}
?>
