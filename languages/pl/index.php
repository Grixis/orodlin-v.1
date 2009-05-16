<?php
/**
 *   File functions:
 *   Polish language for main site index.php
 *
 *   @name                 : index.php                            
 *   @copyright            : (C) 2004,2005,2006 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @version              : 1.1
 *   @since                : 21.06.2006
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
// $Id: index.php 355 2006-06-21 16:48:07Z thindil $

define('ADMIN', 'Władca');
define('REASON', 'Przyczyna wyłączenia gry');

if (!isset($_GET['step']))
{
    define('PROLOG_TITLE', 'Tytuł Prologu');
    define('PROLOG', '<p>Prolog...</p>');
    define('WHAT_IS', '<p>Co to jest');
    define('DESCRIPTION', 'Krótki opis gry.</p>');
    define('CODEX2', '(aby nie obce były Ci zasady panujące w grze). Życzę dobrej zabawy.</p>');
    define('DAY', 'dnia');
    define('WRITE_BY', 'napisana przez');
}

if (isset($_GET['step']) && $_GET['step'] == 'lostpasswd')
{
    if (isset($_GET['action']) && $_GET['action'] == 'haslo')
    {
        define('ERROR_MAIL', 'Podaj adres email.');
        define('ERROR_NOEMAIL', 'Nie ma takiego maila w bazie danych.');
        define('MESSAGE_PART1', 'Dostałeś ten mail, ponieważ chciałeś zmienić hasło w');
        define('MESSAGE_PART2', 'Twoje nowe hasło do konta to');
        define('MESSAGE_PART3', 'Aby aktywować nowe hasło kliknij w link znajdujący się poniżej:');
        define('MESSAGE_PART4', 'Zmień je jak tylko wejdziesz do gry. Życzę miłej zabawy w');
        define('MESSAGE_SUBJECT', 'Przypomnienie hasła na');
        define('MESSAGE_NOT_SEND', 'Wiadomość nie została wysłana. Błąd:<br />');
        define('SUCCESS', 'Mail z hasłem oraz linkiem aktywującym został wysłany na podany adres e-mail. Musisz jeszcze aktywować nowe hasło');
    }
        else
    {
        define('LOST_PASSWORD2', '<p>Jeżeli zapomniałeś hasła do swojej postaci, wpisz tutaj swój adres email. Jednak ze względu na to, że hasła w bazie danych są kodowane, niemożliwe jest odzyskanie twojego starego hasła. Dlatego dostaniesz nowe hasło. Jeżeli twoje konto istnieje, informacja o haśle zostanie wysłana pod podany mail. <b>Uwaga!</b> jeżeli masz na swoim koncie włączony filtr anty-spamowy, wyłącz go przed wysłaniem maila, inaczej informacja o haśle nie dojdzie do ciebie!</p>');
        define('SEND', 'Wyślij');
        define('PASS_CHANGED', 'Hasło do konta zostało zmienione.');
        define('ERROR', 'Zapomnij o tym!');
    }
}

if (isset($_GET['step']) && $_GET['step'] == 'rules')
{
}

if (isset($_GET['step']) && $_GET['step'] == 'newemail')
{
    define('ERROR', 'Zapomnij o tym!');
    define('MAIL_CHANGED', 'Adres email został zmieniony. Zaloguj się teraz do gry korzystając z nowego adresu email.');
}
?>
