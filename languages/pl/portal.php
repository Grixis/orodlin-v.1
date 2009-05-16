<?php
/**
 *   File functions:
 *   Polish language for portal
 *
 *   @name                 : portal.php                            
 *   @copyright            : (C) 2004,2005,2006 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @version              : 1.2
 *   @since                : 26.07.2006
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
// $Id: portal.php 566 2006-09-13 09:31:08Z thindil $

define("YOU_DEAD", "Ponieważ jesteś martwy, twa dusza podąża z powrotem do szpitala w ".$city1a.". Kliknij <a href=hospital.php>tutaj</a>.");
define("A_HERE", "tutaj");

if (!isset ($_GET['action1']))
{
    define("PORTAL_TEXT", "Portal.... Co robisz?");
    define("A_FIGHT2", "Walczę");
    define("A_RETREAT", "Uciekam");
}

if (isset ($_GET['action1']) && $_GET['action1'] == 'retreat') 
{
    define("PORTAL_TEXT", "Uciekasz... Jesteś z powrotem w ".$city1a.". Owa podróż zmęczyła cię niesamowicie. Dlatego na razie musisz odpocząć. Kliknij");
}

if (isset ($_GET['action1']) && $_GET['action1'] == 'fight') 
{
    define("START_FIGHT", "Przygotowujesz się do walki<br />");
    define("MONSTER_NAME", "Strażnik Skarbu");
    define("NO_HP", "Nie masz wystarczająco dużo życia aby walczyć.");
    define("NO_ENERGY", "Nie masz wystarczająco dużo energii aby walczyć.");
    define("LOST_FIGHT2", "<br />Przegrywasz... Ponieważ jesteś martwy, twa dusza podąża z powrotem do szpitala w ".$city1a.". Kliknij <a href=hospital.php>tutaj</a>.");
    define("PORTAL_TEXT", "Zwycięztwo.. Udało ci się pokonać Strażnika, moje gratulacje.. Jaką nagrodę wybierasz dla siebie?");
    define("SWORD", "Miecz Vallheru");
    define("ARMOR", "Zbroja Światła");
    define("I_STAFF", "Różdżka Potęgi");
    define("CAPE", "Szata Pierwszych");
    if (isset ($_GET['step'])) 
    {
        define("ITEM_TAKE", "Już ktoś wziął przedmiot!");
        define("U_TITLE", "Nowy Bohater");
        define("U_TEXT", "I tak oto na ");
        define("U_TEXT2", " pojawił się kolejny bohater, który przeszedł cało i zdrowo tajemnicze miejsce.<br />Władze ");
        define("U_TEXT3", " pragną poinformować swoich poddanych iż dziś (");
        define("U_TEXT4", ") do panteonu bohaterów wstąpił(a) ");
        define("U_TEXT5", " o ID:");
        define("U_TEXT6", ".<br /> Cześć mu i chwała!");
        define("STEP_TEXT", " Powoliwracasz w kierunku portalu i przechodzisz przez niego. Po chwilowych zawrotach głowy znów słyszysz wokół siebie gwar rozmów różnych istot. Jesteś z powrotem w ".$city1a.". Rozglądając się dookoła dostrzegasz obok siebie zawiniątko. Kiedy je rozwijasz twoim oczom ukazuje się");
        define("T_GO", "Wejdź");
    }
}
?>
