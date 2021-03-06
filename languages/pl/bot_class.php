<?php
/**
 *   File functions:
 *   Polish language for bot class
 *
 *   @name                 : bot_class.php                            
 *   @copyright            : (C) 2004,2005,2006 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @version              : 1.0
 *   @since                : 28.01.2006
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
// $Id: bot_class.php 566 2006-09-13 09:31:08Z thindil $

$arrReflectionsorg = array('ciebie');
$arrReflectionsans = array('mnie');
$arrPatsreglang = array($strName." .+ dla [0-9]+$", 
                        $strName." .+ dla wszystkich!$",
                        $strName." jakieś wieści?",
                        $strName." potrzebuję .*",
                        $strName." jestem .*",
                        $strName." witaj",
                        $strName." czuję się .*",
                        $strName." jesteś .*",
                        $strName." nie mogę .*",
                        $strName." dlaczego .*",
                        $strName." dawaj .*",
                        $strName." daj .*",
                        $strName." o której.*reset?",
                        $strName." co sądzisz o .*",
                        $strName." tak");
$arrPatsanslang = array(array('Proszę %3 oto%1od %2.'), 
                        array('Uwaga %2 stawia wszystkim%1!'),
                        array('Oczywiście! %1',
                              'Nic ciekawego się nie wydarzyło ostatnio.'),
                        array('Dlaczego potrzebujesz %1?',
                              'Jesteś pewien że potrzebujesz %1?',
                              'Próbowałeś szukać %1 gdzieś indziej?'),
                        array('Dlaczego uważasz że jesteś %1?',
                              'Nie martw się, to przejdzie.',
                              'Jesteś pewny?'),
                        array('Miło znów ciebie widzieć.',
                              'Witaj podróżniku. Co podać?',
                              'A, znowu ty.'),
                        array('Dlaczego czujesz się %1?',
                              'Przeszkadza ci to?',
                              'Może chcesz coś mocniejszego dlatego że czujesz się %1?'),
                        array('Dlaczego uważasz że jestem %1?',
                              'A może tylko tak ci się wydaje?',
                              'Może i racja.'),
                        array('Dlaczego nie możesz %1?',
                              'Coś ci w tym przeszkadza?',
                              'Na pewno próbowałeś %1?'),
                        array('A dlaczego uważasz że nie?',
                              'Nie mam pojęcia dlaczego %1',
                              'Pewnie dlatego że to niemożliwe.'),
                        array('Może tak trochę grzeczniej?',
                              'Nie mam %1, jest tylko piwo.',
                              'A masz pieniądze na %1?'),
                        array('Dam %1 jak zapłacisz',
                              'Zaraz podam.',
                              'I co jeszcze?'),
                        array('Resety są o godzinach 8, 10, 12, 14, 16, 18, 20, 22, główny reset o godzinie 24.'),
                        array('Nie mam zdania.',
                              'Dlaczego pytasz mnie o %1?',
                              'Ciężko powiedzieć.'),
                        array('Cieszę się że się zgadzamy.',
                              'Dlaczego tak uważasz?'),
                        array('Nie rozumiem',
                              'Coś chciałeś?',
                              'Barnaba pokaż temu klientowi drzwi!',
                              'Hmm w jakim to języku?',
                              'Pytania są tendencyjne',
                              'Do mnie mówisz?'));
?>
