<?php
/**
 *   File functions:
 *   Polish language for account.php
 *
 *   @name                 : account.php
 *   @copyright            : (C) 2004,2005,2006 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @author               : Erechail <kuba.stasiak at gmail.com>
 *   @version              : 1.3a
 *   @since                : 17.07.2006
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
// $Id: account.php 879 2007-01-23 17:19:03Z thindil $

define('ERROR', 'Zapomnij o tym!');
define('EMPTY_FIELDS', 'Wypełnij wszystkie pola!');
define('WELCOME', 'Witaj w ustawieniach swojego konta. Co chcesz robić:');
define('A_NAME', 'Zmień imię');
define('A_PASS', 'Zmień hasło');
define('A_PROFILE', 'Edytuj profil');
define('A_EMAIL', 'Edytuj informacje kontaktowe');
define('A_AVATAR', 'Edytuj avatara');
define('A_RESET', 'Resetuj postać');
define('A_IMMU', 'Immunitet');
define('A_STYLE', 'Dostosuj wygląd gry');
define('A_LANG', 'Wybierz język gry');
define('CHANGE', 'Zmień');
define('A_FREEZE', 'Zamrożenie konta');
define('A_OPTIONS', 'Dodatkowe opcje');
define('A_CHANGES', 'Ostatnie zmiany');
define('A_BUGREPORT', 'Zgłoś błąd');
define('A_BUGTRACK', 'Lista błędów');
define('A_LINKS', 'Własne linki');
define('DESCRIPTION', 'Opis');
define('SIGNATURES', 'Sygnatury');

if (isset($_GET['view']) && $_GET['view'] == 'links')
{
    define('LINKS_INFO', 'Poniżej możesz dodać bądź edytować dodatkowe linki jakie mają pojawiać się w menu Nawigacja. Linki możesz dodawać albo tylko jako nazwa pliku (np "city.php") albo cały adres (np. "'.$gameadress.'/city.php").');
    define('FILENAME', 'Adres');
    define('T_NAME', 'Nazwa');
    define('T_ACTIONS', 'Akcje');
    define('A_DELETE', 'Skasuj');
    define('A_EDIT', 'Edytuj');
    define('A_ADD', 'Dodaj');
    define('NOT_YOUR', 'To nie jest twój link!');
    define('YOU_CHANGE', 'Zmieniłeś link w menu Nawigacja.');
    define('YOU_ADD', 'Dodałeś link do menu Nawigacja.');
    define('LINK_DELETED', 'Usunąłeś link z menu Nawigacja.');
}

if (isset($_GET['view']) && ($_GET['view'] == 'bugtrack' || $_GET['view'] == 'bugreport'))
{
    define('BUG_TYPE', 'Rodzaj błędu');
    define('BUG_TEXT', 'Literówka');
    define('BUG_CODE', 'Błąd w grze');
    define('BUG_LOC', 'Lokacja');
    define('BUG_NAME', 'Tytuł zgłoszenia');
}

if (isset($_GET['view']) && $_GET['view'] == 'bugtrack')
{
    define('BUGTRACK_INFO', 'Poniżej znajduje się lista zgłoszonych ale jeszcze nie naprawionych błędów.');
    define('BUG_ID', 'Numer');
}

if (isset($_GET['view']) && $_GET['view'] == 'bugreport')
{
    define('BUG_DESC', 'Opis błędu (minimum 100 znaków)');
    define('A_REPORT', 'Zgłoś');
    define('TOO_SHORT', 'Zgłoszenie jest zbyt krótkie!');
    define('B_REPORTED', 'Błąd zgłoszony.');
    define('BUG_INFO', 'Tutaj możesz zgłosić błąd w grze. Staraj się opisać dokładnie co się wydarzyło, co robiłeś zanim ten błąd wystąpił oraz jeżeli na ekranie pojawiły się jakieś informacje na ten temat, podaj je. Postaraj się nadać zgłoszeniu odpowiedni tytuł. Zgłoszenia typu \'Błąd\' bądź z niewiele mówiącym tytułem nie będą brane pod uwagę! Jeżeli nie wiesz co napisać skorzystaj z poradnika <a href="http://www.chiark.greenend.org.uk/~sgtatham/bugs-pl.html">Jak efektywnie zgłaszać błędy</a>');
}

if (isset($_GET['view']) && $_GET['view'] == 'changes')
{
    define('CHANGES_INFO', 'Poniżej zamieszczone są informacje na temat ostatnich 30 zmian dokonanych na '.$gamename.'.');
    define('CHANGE_LOC', 'Lokacja');
}

if (isset ($_GET['view']) && $_GET['view'] == 'options')
{
    define('T_OPTIONS', 'Tutaj możesz ustawić dodatkowe opcje Twojego konta.');
    define('T_BATTLELOGA', 'Wysyłanie przebiegu walki na pocztę jeśli atakujesz');
    define('T_BATTLELOGD', 'Wysyłanie przebiegu walki na pocztę jeśli jesteś atakowany');
    define('T_GRAPHBAR', 'Wyświetlanie graficznych pasków życia/zdrowia/doświadczenia');
    define('T_OVERLIB', 'Wyświetlanie overliba na liście graczy');
    define('T_LOGINFO', 'Wyskakujące powiadomienia o wpisach do dziennika (pop-up)');
    define('T_MAILINFO', 'Wyskakujące powiadomienia o poczcie (pop-up)');
    define('A_NEXT', 'Dalej');
    define('A_SAVED', 'Dodatkowe opcje zostały zapisane.');
}

if (isset ($_GET['view']) && $_GET['view'] == 'freeze')
{
    define('FREEZE_INFO', 'Tutaj możesz zamrozić swoje konto na określony czas. W tym czasie nie będziesz mógł wejść na nie, ale również nie będziesz mógł być okradziony czy też zaatakowany. Niestety, nie ma możliwości zamrożenia również Twojej strażnicy. Na zamrożone konto nie przychodzi energia co reset. Maksymalny czas blokady - 21 dni. Blokada rozpoczyna od zaraz.');
    define('HOW_MANY', 'Podaj na ile dni chcesz zablokować konto');
    define('A_FREEZE2', 'Zablokuj');
    define('TOO_MUCH', 'Podałeś zbyt długi okres blokady.');
    define('YOU_BLOCK', 'Zablokowałeś swoje konto na okres ');
    define('NOW_EXIT', ' dni. Ponieważ Twoje konto jest już zablokowane, zostałeś wylogowany z gry');
}

if (isset ($_GET['view']) && $_GET['view'] == 'lang')
{
    define('YOU_SELECT', 'Wybrałeś jako główny język ');
    define('AND_SECOND', ' a jako dodatkowy, język ');
    define('LANG_INFO', 'Tutaj możesz wybrać język w jakim będzie wyświetlane '.$gamename.'. Główny język sprawi iż cała gra będzie w tym języku. Język dodatkowy wpływa tylko na rozmowy w karczmie oraz na forum. Jeżeli nie chcesz ustawiać języka dodatkowego, po prostu wybierz tę samą opcję co w przypadku języka głównego.');
    define('F_LANG', 'Główny język:');
    define('S_LANG', 'Język dodatkowy:');
    define('A_SELECT', 'Wybierz');
    define('A_REFRESH', 'Odśwież');
}

if (isset($_GET['view']) && $_GET['view'] == 'immu')
{
	define('IMMU_INFO1', '<b>Immunitet</b> jest specjalną opieką, jaką Władcy otaczają rzemieślników wytwarzających swoje dobra na ziemiach Orodlinu. Rzemieślnicy, którzy zgodzą się na posiadanie takiej opieki nie mogą być atakowani przez innych mieszkańców, a sami mogą walczyć jedynie z potworami. Rezygnacja z ochrony Namiestników niesie ze sobą obniżenie statystyk postaci o 25%. <b>Jako, że obrałeś drogę walki lub złodziejstwa, nie możesz posiadać immunitetu</b>.');
	define('IMMU_INFO2', 'Jeśli nie chcesz, by w Twojej pracy przeszkadzali Ci mieszkańcy pragnący wyzwać Cię na pojedynek, Namiestnicy oferują Ci ochronę. Pod ich opieką żaden mieszkaniec nie napadnie Cię,  sam jednak będziesz  mógł walczyć jedynie z potworami. Zanim się zdecydujesz na ochronę władców pamiętaj, że rezygnacja z niej spowoduje bardzo znaczące osłabienie Twej postaci.');
	define('QUESTION1', 'Czy chcesz wziąć immunitet?');
	define('DISCARD_INFO', 'Posiadasz już immunitet. Teraz możesz go zdjąć, ale wiąże się to z utratą statystyk (-25% aktualnych wartości).');
	define('QUESTION2', 'Czy chcesz zdjąć immunitet?');
	define('YOU_HAVE', 'Posiadasz już immunitet!');
	define('NO_CLASS', 'Musisz najpierw wybrać klasę postaci!<ul><li><a href=account.php>Wróć</a></li><li><a href=klasa.php>Wybierz klasę</a></li></ul>');
	define('ONLY_FOR_ARTISAN', 'Nie jesteś rzemieślnikiem!');
	define('IMMU_SELECT', 'Od tej chwili posiadasz immunitet.');
	define('CLICK1', 'Kliknij');
	define('HERE', 'tutaj');
	define('CLICK2', ', aby wrócić od opcji konta.');
	define('NO_IMMU', 'Nie posiadasz immunitetu!');
	define('DISCARDED', 'Immunitet został zdjęty.');
	define('NO_CONFIRMATION', 'Ktoś chciał Cię zrobić w konia!');
}

if (isset ($_GET['view']) && $_GET['view'] == 'reset')
{
    define('RESET_INFO', 'Tutaj możesz zresetować swoją postać. Na Twój adres email zostanie wysłany specjalny link aktywacyjny. Dopiero po kliknięciu na niego Twoja postać zostanie zresetowana. Zostanie jej jedynie <b>id</b>, <b>imię</b>, <b>hasło</b>, <b>mail</b>, <b>profil</b>, <b>reputacja</b> oraz <b>wiek</b>. Czy chcesz zresetować postać');
    if (isset ($_GET['step']) && $_GET['step'] == 'make')
    {
        define('MESSAGE1', 'Dostałeś ten list ponieważ chciałeś zresetować postać. Jeżeli nadal pragniesz zresetować swoją postać na');
        define('ID', 'ID');
        define('MESSAGE2', 'wejdź w ten link');
        define('MESSAGE3', 'Jeżeli jednak nie chcesz resetować postaci (bądź ktoś inny za Ciebie zgłosił taką chęć) wejdź w ten link');
        define('MESSAGE4', 'Pozdrawiam');
        define('MSG_TITLE', 'Reset konta gracza na');
        define('E_MAIL', 'Wiadomość nie została wysłana. Błąd');
        define('E_DB', 'Nie mogę wykonać zapytania!');
        define('RESET_SELECT', 'Na Twoje konto pocztowe został wysłany mail z prośbą o potwierdzenie resetu postaci');
    }
}

if (isset($_GET['view']) && $_GET['view'] == 'avatar')
{
    define('REFRESH', 'Odśwież');
    define('AVATAR_INFO', 'Tutaj możesz zmienić swojego avatara. <b>Uwaga!</b> Jeżeli już posiadasz avatara, stary zostanie skasowany. Maksymalny rozmiar avatara to <b>10 kB</b>. Avatara możesz załadować tylko z własnego komputera. Musi on mieć rozszerzenie *.<b>jpg</b>, *.<b>jpeg</b>, *.<b>gif</b> lub *.<b>png</b>.');
    define('A_DELETE', 'Skasuj');
    define('FILE_NAME', 'Wyślij obraz');
    define('A_SELECT', 'Wyślij');
    if (isset($_GET['step']) && $_GET['step'] == 'usun')
    {
        define('E_DB', 'Nie mogę skasować!');
        define('DELETED', 'Avatar usunięty');
        define('NO_FILE', 'Nie ma takiego pliku!');
    }
    if (isset($_GET['step']) && $_GET['step'] == 'dodaj')
    {
        define('NO_NAME', 'Nie podałeś nazwy pliku!');
        define('BAD_TYPE', 'Zły typ pliku!');
        define('NOT_COPY', 'Nie skopiowano pliku!');
        define('LOADED', 'Avatar załadowany');
    }
}

if (isset($_GET['view']) && $_GET['view'] == 'name')
{
    define('MY_NAME', 'moje imię na');
    if (isset($_GET['step']) && $_GET['step'] == 'name')
    {
        define('EMPTY_NAME', 'Podałęś puste imię.');
        define('NAME_BLOCK', 'To imię jest już zajęte.');
        define ('INVALID_NICK', 'Nieprawidłowe imię. Dozwolone tylko znaki alfabetu łacińskiego, polskie znaki diakrytyczne, spacja i apostrof. Ponadto imię musi zaczynać się z wielkiej litery, a takowa może się znajdować jedynie na początku każdego członu imienia. (<a href="account.php?view=name">Wróć</a>)');
        define('YOU_CHANGE', 'Zmieniłeś imię na');
        define('LONG_NICK', 'Twoje imię jest za długie. Może mieć maksymalnie 15 znaków.');
    }
}

if (isset($_GET['view']) && $_GET['view'] == 'pass')
{
    define('PASS_INFO', 'Nie używaj HTML, ani pojedyńczego cudzysłowu. Nie próbuj go używać, będzie usunięty.');
    define('OLD_PASS', 'Obecne hasło');
    define('NEW_PASS', 'Nowe hasło');
	define('PASSWORD_MISMATCH', 'Niepoprawne stare hasło!');
    if (isset($_GET['step']) && $_GET['step'] == 'cp')
    {
        define('YOU_CHANGE', 'Zmieniłeś hasło z');
        define('ON', 'na');
    }
}

if (isset($_GET['view']) && $_GET['view'] == 'profile')
{
    define('PROFILE_INFO', 'Dodaj/Modyfikuj swój profil. Nie używaj HTML ani pojedyńczego cudzysłowu!');
    define('NEW_PROFILE', 'Nowy profil');
    define('NEW_PROFILE2', 'Twój nowy profil');
}

if (isset($_GET['view']) && $_GET['view'] == 'eci')
{
    define('OLD_EMAIL', 'Obecny adres e-mail');
    define('NEW_EMAIL', 'Nowy adres e-mail');
    define('NEW_GG', 'Identyfikator');
    define('COMM1', 'Gadu-Gadu');
    define('COMM2', 'Tlen');
    define('COMM3', 'Jabber');
    define('COMLINK1', 'gg:');
    define('COMLINK2', 'http://ludzie.tlen.pl/');
    define('COMLINK3', 'xmpp:');
    define('T_COMMUNICATOR', 'Komunikator');
    define('T_DELETE', 'Usuń');
    if (isset($_GET['step']) && $_GET['step'] == 'gg')
    {
        define('GG_BLOCK', 'Ktoś już posiada taki identyfikator.');
        define('E_DB', 'Nie mogę zapisać w bazie danych!');
        define('YOU_CHANGE', 'Zmieniłeś identyfikator komunikatora ');
        define('YOU_DELETE', 'Usunąłeś informację o komunikatorze.');
    }
    if (isset($_GET['step']) && $_GET['step'] == 'ce')
    {
        define('BAD_EMAIL', 'Nieprawidłowy adres email.');
        define('EMAIL_BLOCK', 'Ktoś już posiada taki adres email.');
        define('YOU_CHANGE', 'Zmieniasz adres email. Aby dokończyć procedurę, odbierz z nowego adresu wysłany do ciebie list a następnie kliknij w link umieszczony w mailu. Dopiero wtedy zostanie zmieniony ten adres mailowy.');
        define('MESSAGE_PART1', 'Dostałeś ten mail, ponieważ chciałeś zmienić hasło w');
        define('MESSAGE_PART2', 'Aby aktywować nowy adres email kliknij w link znajdujący się poniżej:');
        define('MESSAGE_PART3', 'Życzę miłej zabawy w');
        define('MESSAGE_SUBJECT', 'Zmiana adresu email na ');
        define('MESSAGE_NOT_SEND', 'Wiadomość nie została wysłana. Błąd:<br />');
    }
}

if (isset($_GET['view']) && $_GET['view'] == 'style')
{
    define('S_SELECT', 'Wybierz');
    define('TEXT_STYLE', 'tekstowy wygląd gry');
    define('GRAPH_TEXT', 'graficzny wygląd gry');
    define('YOU_CHANGE', 'Zmieniłeś wygląd gry');
    define('ERROR2', 'Błąd!');
    define('REFRESH', 'Odśwież');
    define('GRAPHLESS', 'Styl gry bez obrazków (polecane dla słabszych łączy)');
}
if (isset($_GET['view']) && $_GET['view'] == "description")
{
	define('PODPIS_TEXT', 'Tutaj możesz zmienić opis swojej postaci. Będzie widoczny w dwóch miejscach: w dymku po najechaniu kursorem na Twoją postać bądź na liście zalogowanych mieszkańców pod imieniem (operując linkiem "Pokaż/ukryj opisy"). Opis może mieć maksymalnie 25 znaków, reszta zostanie ucięta.');
	define('NEW_OPIS', 'Podaj swój nowy opis');
	if (isset($_GET['step']) && $_GET['step'] == "change")
	{
		define('NEW_OPIS2', 'Zmieniłeś poprzedni opis na');
	}
}

if (isset($_GET['view']) && $_GET['view'] == 'signatures')
{
    define('HEAD_TEXT', '<p>W tym miejscu możesz wybrać styl swojej spersonalizowanej sygnatury, którą możesz wstawić na ulubione forum czy stronę internetową. Jest to bardzo ciekawy sposób na informowanie innych o Twojej przygodzie w Królestwie Orodlin.</p><p>Oto dostępne sygnatury wraz z gotowym kodem do wklejenia:</p>');
}
?>
