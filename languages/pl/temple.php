<?php
/**
 *   File functions:
 *   Polish language for temple
 *
 *   @name                 : temple.php
 *   @copyright            : (C) 2004,2005,2006 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @author               : Zamareth <zamareth@users.sourceforge.net>
 *   @version              : 1.4
 *   @since                : 13.09.2007
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
// $Id: temple.php 678 2006-10-06 11:29:24Z thindil $

define('ERROR', 'Zapomnij o tym!');
define('YOU_DEAD', 'Ponieważ jesteś martwy, nie możesz tutaj wejść!');
define('NO_ENERGY', 'Nie masz tyle energii!');

define('TEMPLE_INFO', $player -> location == 'Altara' ? '<p>Witaj w świątyni. Możesz tutaj modlić się do ' : '<p>Witaj w świątyni. Możesz tutaj modlić się do ');
define('TEMPLE_INFO2', 'Aby Twoja modlitwa została wysłuchana, musisz posiadać odpowiednią ilość Punktów Wiary. Punkty zdobywasz służąc w świątyni. To czym Ciebie obdaruje bóg zależy tylko od niego.</p>');

define('A_WORK', 'Pracuj dla świątyni');
define('A_PRAY', 'Módl się do boga');
define('A_BOOK', 'Podejdź do księgi');
define('A_PANTHEON', 'Panteon '.$gamename);

if (isset($_GET['view'])) {
switch ($_GET['view'])
{
    case 'service':
        define('YOU_WORK', 'Pracowałeś przez pewien czas dla świątyni i zdobywasz ');
        define('YOU_WORK2', ' Punkt(ów) Wiary');
        define('TEMPLE_INFO_W', 'Pracując dla świątyni, sprawiasz, że');
        define('TEMPLE_INFO2_W', 'spogląda na ciebie przychylniejszym okiem. Za każde 0,5 energii zdobywasz 1 Punkt Wiary. Czy chcesz służyć w świątyni?');
        define('I_WANT', 'Chcę uzyskać');
        define('T_AMOUNT', 'punktów wiary');
        define('A_WORK_2', 'Pracuj');
        define('NO_DEITY', 'Nie możesz pracować w świątyni ponieważ jesteś ateistą!');
        break;
    case 'prayer':
        define('CHOOSE_PRAYER', 'Wybierz żarliwość (koszt w pkt. energii) Twojej modlitwy:');
        define('NO_PW', 'Nie masz Punktów Wiary aby się modlić!');
        define('YOU_PRAY', 'Modliłeś się przez pewien czas do ');
        define('BUT_FAIL', '. Niestety został(a) głuchy(a) na twe prośby.');
        define('NO_DEITY', 'Nie możesz modlić się w świątyni ponieważ jesteś ateistą!');
        define('NO_RACE', 'Nie możesz modlić się w świątyni ponieważ nie wybrałeś jeszcze rasy!');
        define('YOU_HAVE', 'Posiadasz już błogosławieństwo od boga!');
        define('P_SUCCESS', ', który(a) okazał(a) się zadowolony(a) z twej pobożności. Otrzymujesz błogosławieństwo do ');
        define('P_DEAD', ' lecz on(a) okazał(a) się nieprzychylny(a). Twe modły rozdrażniły bóstwo. Nagle posąg ');
        define('P_DEAD2', ' wystrzelił w Twoją stronę błyskawicę, która uśmierciła Cię na miejscu....');
		define('PERM_BONUS', 'Otrzymałeś trwałą premię +1 do ');
        define('AGI', 'Zręczności');
        define('STR', 'Siły');
        define('INTELI', 'Inteligencji');
        define('WIS', 'Siły Woli');
        define('SPE', 'Szybkości');
        define('CON', 'Wytrzymałości');
		define('HITPTS', 'Punktów Życia');
        define('SMI', 'Kowalstwa');
        define('ALC', 'Alchemii');
        define('FLE', 'Stolarstwa');
        define('WEA', 'Walki bronią');
        define('SHO', 'Strzelectwa');
        define('DOD', 'Uników');
        define('CAS', 'Rzucania czarów');
        define('BRE', 'Hodowli');
        define('MINI', 'Górnictwa');
        define('LUMBER', 'Drwalnictwa');
        define('HERBS', 'Zielarstwa');
        define('ENERGY_PTS2', 'pkt energii');
        define('PRAY1', 'Pacierz');
        define('PRAY2', 'Modlitwa');
        define('PRAY3', 'Psalm');
        define('PRAY4', 'Adoracja');
		define('PRAY5', 'Trwały bonus +1 do cechy (100 PW) / umiejętności (300 PW)');
        define('BLESS_FOR', 'Proś o błogosławieństwo do (w nawiasie koszt w pkt. wiary): ');
        define('JEWEL', 'Jubilerstwa');
		define('METAL', 'Hutnictwa');
        break;
    case 'book':
        define('NEXT_PAGE', 'Następna strona');
        define('BOOK_INFO', '<p>Podchodzisz bliżej do cokołu i uważnie przyglądasz się znalezisku. Zdziwiony jesteś iż dopiero teraz ją zauważyłeś. Księga wygląda na starą, jej okładka wykonana jest z brązowej skróry, na grzbiecie książki widzisz nie znany sobie symbol. Zaciekawiony ostrożnie otwierasz księgę...</p><br />');
        $arrText = array('');
        break;
    case 'pantheon':
        $arrNames = array('Tartus', 'Anea', 'Meltis', 'Dintel', 'Yala', 'Unrod', 'Luzubal', 'Gulgard', 'Artis');
        $arrDesc = array('Opis', 'Opis', 'Opis', 'Opis', 'Opis', 'Opis', 'Opis', 'Opis', 'Opis');
        break;
}
}
?>
