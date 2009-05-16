<?php
/**
 *   File functions:
 *   Polish language for monuments
 *
 *   @name                 : monuments.php
 *   @copyright            : (C) 2004,2005,2006 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @author               : eyescream <tduda@users.sourceforge.net>
 *   @version              : 1.3
 *   @since                : 14.11.2006
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
// $Id: monuments.php 822 2006-11-16 15:00:03Z thindil $

define('ERROR', 'Zapomnij o tym!');
define('M_NAME', 'Imię (ID)');
define('A_PLAYER_RANKING', 'Ranking mieszkańców (zaawansowany)');
define('A_LAND_STATS', 'Statystyki ogólne krainy');
define('GEN_TIME', 'Uzbrojony po zęby gwardzista bez przerwy pilnuje wielkiej kryształowej tablicy, na której widnieją osiągniecia najlepszych spośród mieszkańców Królestwa. Co godzinę jeden z arcymagów wyłania się z eterycznego portalu i za pomocą czaru uaktualnia dane prezentowane na magicznym artefakcie. Mag jest stary i zmęczony, następnym razem pojawi się dopiero około  ');

$arrGroups= array('Ranking mieszkańców (prosty)',
                  'Posągi statystyk',
                  'Posągi umiejętności bojowych',
                  'Posągi umiejętności rzemieślniczych');

$arrTitles = array(array('Najwyższy poziom', 'Najwięcej zwycięstw', 'Najwięcej złota (sakiewka + bank)'),
                   array('Najwięcej Siły', 'Najwięcej Wytrzymałości', 'Najwięcej Inteligencji', 'Najwięcej Siły Woli', 'Najwięcej Szybkości', 'Najwięcej Zręczności'),
                   array('Najwyższa Walka bronią białą', 'Najwyższe Strzelectwo', 'Najwyższe Rzucanie czarów', 'Najwięcej uników', 'Najwyższe Dowodzenie'),
                   array('Najwyższe Kowalstwo', 'Najwyższe Stolarstwo', 'Najwyższa Alchemia', 'Najwyższe Zielarstwo', 'Najwyższe Jubilerstwo', 'Najwyższa Hodowla', 'Najwyższe Górnictwo', 'Najwyższe Drwalnictwo', 'Najwyższe Hutnictwo'));

$arrDescriptions = array(array(LEVEL, 'Zwycięstw', 'Złoto'),
                         array('Siła', 'Wytrzymałość', 'Inteligencja', 'Siła Woli', 'Szybkość', 'Zręczność'),
                         array('Walka bronią białą', 'Strzelectwo', 'Rzucanie czarów', 'Uniki', 'Dowodzenie'),
                         array('Kowalstwo', 'Stolarstwo', 'Alchemia', 'Zielarstwo', 'Jubilerstwo', 'Hodowla', 'Górnictwo', 'Drwalnictwo', 'Hutnictwo'));
?>
