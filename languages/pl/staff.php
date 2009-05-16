<?php
/**
 *   File functions:
 *   Polish language for staff panel
 *
 *   @name                 : staff.php
 *   @copyright            : (C) 2004,2005,2006,2007 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @version              : 1.4
 *   @since                : 18.04.2007
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

define('YOU_NOT', 'Nie jesteś Księciem');
define('ERROR', 'Zapomnij o tym!');
define('A_MAKE', 'Zrób');

if (!isset($_GET['view']))
{
    define('PANEL_INFO', 'Witaj w panelu administracyjnym. Co chcesz zrobić?');
    define('A_NEWS', 'Dodaj Plotkę');
    define('A_TAKE', 'Zabrać sztuki złota');
    define('A_CLEAR', 'Wyczyścić Czat');
    define('A_CHAT', 'Zablokuj/odblokuj wiadomości od gracza na czacie');
    define('A_JAIL', 'Wyślij gracza do więzienia');
    define('A_ADD_NEWS', 'Sprawdź oczekujące plotki');
    define('A_INNARCHIVE', 'Archiwum karczmy');
    define('A_BAN_MAIL', 'Zablokuj/Odblokuj wiadomości od gracza na poczcie');
    define('A_CHANGE_NICK', 'Zmień graczowi imię, rangę lub profil');
}

if (isset ($_GET['view']) && $_GET['view'] == 'banmail')
{
    define('BLOCK_LIST', 'Lista zablokowanych');
    define('A_BLOCK', 'Zablokuj');
    define('A_UNBLOCK', 'Odblokuj');
    define('MAIL_ID', 'ID');
    define('YOU_BLOCK', 'Zablokowałeś wysyłanie wiadomości na poczcie przez gracza');
    define('YOU_UNBLOCK', 'Odblokowałeś wysyłanie wiadomości na poczcie przez gracza');
}

if (isset($_GET['view']) && $_GET['view'] == 'innarchive')
{
    define('A_NEXT2', 'Kolejne wpisy');
    define('A_PREVIOUS', 'Poprzednie wpisy');
    define('C_ID', 'ID');
}

if (isset ($_GET['view']) && $_GET['view'] == 'jail')
{
    define('JAIL_ID', 'ID gracza');
    define('JAIL_REASON', 'Przyczyna');
    define('JAIL_TIME', 'Czas (w dniach)');
    define('JAIL_COST', 'Kaucja za uwolnienie');
    define('A_ADD', 'Dodaj');
    define('PLAYER_IN_JAIL', 'Ten gracz jest już w lochach!');
    define('PLAYER_JAIL', 'Gracz o ID: ');
    define('HAS_BEEN_J', 'został wtrącony do więzienia na');
    define('DAYS', 'dni za');
    define('HE_MAY', 'Może wyjść z więzienia za kaucją: ');
    define('YOU_SURE', 'Jesteś pewna/pewien, że chcesz się wrzucić do lochu?');
    define('A_YES', 'Tak');
}

if (isset ($_GET['view']) && $_GET['view'] == 'clearc')
{
    define('CHAT_PRUNE', 'Wyczyściłeś karczmę');
}

if (isset ($_GET['view']) && $_GET['view'] == 'takeaway')
{
    define('GOLD_TAKEN', 'sztuk złota zostało zabranych ID');
    define('TAKE_ID', 'ID');
    define('TAKE_AMOUNT', 'Ilość');
    define('A_TAKE_G', 'Zabierz');
    define('YOU_GET', 'Zostałeś ukarany za: ');
    define('T_AMOUNT', ' kwotą ');
    define('GOLD_COINS', ' sztuk złota. Karę wymierzył: ');
    define('T_REASON', 'Przyczyna:');
    define('T_INJURED', 'Poszkodowany (ID):');
    define('TAKE_INFO', 'Wymierz karę pieniężną:');
    define('T_PLAYER1', 'Gracz ');
    define('T_PLAYER2', ', ID ');
    define('HAS_TAKEN', ' został ukarany za: ');
    define('SANCTION_SET', ' Karę wymierzył: ');
}

if (isset ($_GET['view']) && $_GET['view'] == 'czat')
{
    define('YOU_BLOCK', 'Zablokowałeś wysyłanie wiadomości na czacie przez gracza ');
    define('YOU_UNBLOCK', 'Odblokowałeś wysyłanie wiadomości na czacie przez gracza ');
    define('BLOCK_LIST', 'Lista zablokowanych');
    define('T_ID', 'ID');
    define('A_BLOCK', 'Zablokuj');
    define('A_UNBLOCK', 'Odblokuj');
    define('T_DAYS', ' dni z przyczyny: ');
    define('YOU_BLOCK2', 'Masz zakaz wchodzenia do karczmy na ');
    define('BLOCK_BY', '. Zablokował ciebie: ');
    define('ON_A', 'na');
    define('UNBLOCK_BY', ' odblokował Ci możliwość wchodzenia do Karczmy.');
}

if (isset($_GET['view']) && $_GET['view'] == 'addtext')
{
    define('MODIFIED', 'Zmodyfikowano tekst');
    define('ADDED', 'Tekst dodany do Plotek!');
    define('DELETED', 'Tekst wykasowany!');
    define('ADMIN_INFO', 'Tutaj możesz dodawać, modyfikować oraz usuwać teksty będące na liście oczekujących. Pamiętaj jednak, że nie wolno Ci zmieniać treści pracy, a jedynie poprawiać w niej błędy!');
    define('ADMIN_INFO2', 'Modyfikuj - zobacz treść pracy i ewentualnie zmodyfikuj');
    define('ADMIN_INFO3', 'Dodaj - po prostu dodaje tekst od razu do Plotek bez czytania (nie będziesz pytany o potwierdzenie)');
    define('ADMIN_INFO4', 'Usuń - usuwa tekst z listy oczekujących do dodania do Plotek (nie będziesz pytany o potwierdzenie)');
    define('ADMIN_INFO5', 'Oto lista oczekujących tekstów');
    define('A_ADD', 'Dodaj');
    define('A_DELETE', 'Usuń');
    define('A_CHANGE', 'Zmień');
    define('A_MODIFY', 'Modyfikuj');
    define('T_AUTHOR', 'Autor');
    define('T_TITLE', 'Tytuł');
    define('T_BODY', 'Treść');
    define('YOUR_NEWS', 'Twoja plotka <b>');
    define('HAS_ADDED', ' </b>została zaakceptowana przez ');
    define('HAS_DELETED', ' </b>została odrzucona przez ');
    define('L_ID', ' ID ');
}

if (isset($_GET['view']) && $_GET['view'] == 'changenick')
{
    define('INFO', 'Możesz zmienić imię gracza lub jego profil. Gracz zostanie o tym poinformowany, powinieneś podać przyczynę (nieprawidłowy nick, obraźliwe treści, reklamowanie innych gier itd.). Edycja profilu obarczona jest standardowymi ograniczeniami (nie używać pojedynczego cudzysłowu, tekst &lt;HTML&gt; znika itd.)');
    define('CHANGE_NAME1', 'Zmień imię gracza o ID');
    define('CHANGE_NAME2', 'na');
    define('SAVE', 'Zapisz');
    define('INVALID_ID', 'Nie ma gracza o takim numerze ID!');
    define('EMPTY_NAME', 'Musisz podać imię!');
    define('DEFAULT_NAME', 'Zmień imię postaci');
    define('YOU_CHANGE', 'Zmieniłeś imię gracza o ID');
    define('YOUR_NICK_WAS_CHANGED_BY', 'Imię Twojej postaci zostało zmienione przez');
    define('EDIT_PROFILE', 'Edytuj profil gracza o ID');
    define('EDIT', 'Edytuj');
    define('CURRENTINFO', 'Obecny profil gracza ');
    define('CHANGE_REASON', 'Powód ingerencji');
    define('DEFAULT1','Niezgodność z obowiązującymi zasadami nazewnictwa postaci. Prosimy o zapoznanie się z Kodeksem '.$gamename.' i Ustawą o nazewnictwie postaci.');
    define('DEFAULT2','Umieszczenie w profilu nieuczciwej reklamy bez zezwolenia Władcy.');
    define('DEFAULT3','Umieszczenie w profilu słów wulgarnych lub powszechnie uznanych za obraźliwe.');
    define('YOU_CHANGED_PROFILE', 'Zmieniłeś profil gracza');
    define('YOUR_PROFILE_WAS_CHANGED_BY', 'Profil Twojej postaci został zmieniony przez');

    define('SET_ID', 'Ustaw graczowi o ID');
    define('YOU_ADD_R','Ustawiłeś ID');
    define('NEW_RANK', 'rangę');
    define('DESC_MEMBER', 'Mieszkaniec');   // rank descriptions
    define('DESC_BEGGAR', 'Żebrak');
    define('DESC_VILLAIN', 'Nikczemnik');
    define('R_MEMBER', 'Member');           // ranks written to database
    define('R_BEGGAR', 'Żebrak');
    define('R_VILLAIN', 'Barbarzyńca');
    define('WRONG_RANK', 'Nie masz uprawnień do nadania tej rangi!');
    define('WRONG_PLAYER', 'Nie możesz zmienić rangi temu graczowi!');
}
?>
