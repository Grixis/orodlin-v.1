<?php
/**
 *   File functions:
 *   Polish language for alchemik.php
 *
 *   @name                 : alchemik.php
 *   @copyright            : (C) 2004,2005,2006 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @version              : 1.2
 *   @since                : 04.07.2006
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
// $Id: alchemik.php 425 2006-07-04 19:02:38Z thindil $

define('ERROR', 'Zapomnij o tym!');

if (!isset($_GET['alchemik']))
{
    define('WELCOME', 'Opis Budynku Alchemika');
    define('A_RECIPES', 'Kup przepis na miksturę');
    define('A_MAKE', 'Idź do pracowni');
    define('A_ASTRAL', 'Wykonaj astralną miksturę');
}

if (isset ($_GET['alchemik']) && $_GET['alchemik'] == 'przepisy')
{
    define('RECIPES_INFO', 'Witaj w sklepie dla alchemików. Tutaj możesz kupić przepisy mikstur, które chcesz wykonywać. Aby kupić dany przepis, musisz mieć przy sobie odpowiednią ilość sztuk złota. Oto lista dostępnych przepisów:');
}

if (isset ($_GET['alchemik']) && $_GET['alchemik'] == 'pracownia')
{
    define('NO_PLAN', 'Nie masz takiego planu!');
    if (!isset($_GET['rob']))
    {
        define('ALCHEMIST_INFO', 'Tutaj możesz wykonywać mikstury do których masz przepisy. Aby wykonać miksturę, musisz posiadać również odpowiednią ilość ziół. Każda próba kosztuje Ciebie 1 punkt energii. Nawet za nieudaną próbę dostajesz 0,01 do umiejętności.<br /> Oto lista mikstur, które możesz wykonywać:');
        define('R_NAME', 'Nazwa');
        define('R_LEVEL', 'Poziom');
        define('R_ILLANI', 'Illani');
        define('R_ILLANIAS', 'Illanias');
        define('R_NUTARI', 'Nutari');
        define('R_DYNALLCA', 'Dynallca');
    }
    if (isset($_GET['dalej']))
    {
        define('DEAD_PLAYER', 'Nie możesz wykonywać mikstur ponieważ jesteś martwy!');
        define('P_START', 'Spróbuj wykonać');
        define('P_AMOUNT', 'razy');
        define('A_MAKE', 'Wykonaj');
    }
    if (isset($_GET['rob']))
    {
        define('NO_HERBS', 'Nie masz tylu ziół!');
        define('NO_ENERGY', 'Nie masz tyle energii!');
        define('NO_RECIPE', 'Nie masz takiego przepisu');
        define('E_DB', 'Nie mogę odczytać z bazy danych!');
        define('E_DB2', 'Nie mogę dodać mikstury! ');
        define('YOU_MAKE', 'Wykonałeś');
        define('P_GAIN', 'razy. Zdobywasz');
        define('EXP_AND', 'PD oraz');
        define('ALCHEMY_LEVEL', 'poziomu w umiejętności Alchemia.');
    }
}
?>
