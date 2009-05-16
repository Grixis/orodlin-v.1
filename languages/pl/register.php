<?php
/**
 *   File functions:
 *   Polish language for registration page
 *
 *   @name                 : register.php                            
 *   @copyright            : (C) 2004,2005,2006 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @version              : 1.2
 *   @since                : 14.07.2006
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
// $Id: register.php 474 2006-07-14 13:32:11Z thindil $

define('REASON', 'Przyczyna wyłączenia gry');

if (!isset($_GET['action']))
{
    define('DESCRIPTION', '<p>Zarejestruj się w grze. To nic nie kosztuje. Po rejestracji na twoje konto email zostanie wysłany specjalny link aktywacyjny. <b>Uwaga!</b> Jeżeli korzystasz z konta na Interii lub Tlenie - sprawdź czy nie masz ustawionego filtru anty-spamowego. Ponieważ mail jest wysyłany programowo, nie ręcznie - jest traktowany jako spam i może nigdy nie dojść do ciebie! Konta założone na Wirtualnej Polsce w ogóle nie przyjmują takich maili. Dlatego aby założyć konto w grze, nie używaj konta WP.</p>');
    define('INFO', '<p><b>Hasło</b> musi składać się z co najmniej 5 znaków z czego musi być co najmniej jedna wielka litera oraz cyfra.</p>
<p><b>Imię</b> postaci musi składać się z co najmniej 3 znaków przy czym każdy wyraz musi zaczynać się z wielkiej litery. Dozwolone są jedynie znaki alfabetu łacińskiego razem z polskimi znakami diakrytycznymi + spacja + apostrof.</p>
<p>Mamy obecnie ');
    define('DESCRIPTION2', '<p>Przed Tobą znajdują się Wrota Orodlinu – magiczny portal, który przeniesie Cię w świat magii i miecza, w którym rządzą inne prawa natury, gdzie czuć namacalny wpływ bóstw często wpływających na życie mieszkańców Orodlinu.</p>
<p>Widzisz uzbrojonych strażników pilnujących wejścia do portalu. Gdy już przekroczysz jego próg, nie będzie odwrotu . Pamiętaj – to od Twoich czynów będzie zależeć jaka drogą będziesz podążał. Masz możliwość zostać sławnym magiem, wojownikiem, rzemieślnikiem czy złodziejem, a jeśli Twoje czyny będą godne miana bohatera, zostaniesz na trwałe zapisany w Kronikach Orodlinu.</p>');
    define('NICK', 'Pseudonim:');
    define('CONF_EMAIL', 'Potwierdź email:');
    define('REGISTER', 'Zarejestruj');
    define('SHORT_RULES', 'Krótki spis zasad w grze:');
    define('T_LANG', 'Wybierz język gry:');
    define('RULE1', 'W grze obowiązuje netykieta - w wielkim skrócie - nie rób drugiemu co Tobie nie miłe.');
    define('RULE2', 'Wielokrotne ataki na jednego gracza w ciągu kilku minut - czyli zwykłe nękanie - są karane.');
    define('RULE3', 'Wykorzystywanie błędów w grze do zdobycia przewagi nad innymi kończy się najczęściej skasowaniem postaci. Natomiast pomoc w ich znalezieniu może zostać nagrodzona przyznaniem specjalnej rangi.');
    define('RULE4', 'W sprawie jakichkolwiek naruszeń prawa możesz zgłaszać to do książąt - oni najczęściej również wymierzają kary.');
    define('RULE5', 'Jeżeli nie zgadzasz się z karą, możesz zawsze decyzję zaskarżyć do Sądu Najwyższego Orodlin - jego siedziba znajduje się na forum zewnętrznym (link podany jest u góry).');
    define('RULE6', 'Zabrania się posiadania więcej niż 1 konta na osobę.');
    define('RULE7', 'Więcej informacji na ten temat znajdziesz <a href="index.php?step=rules">tutaj</a>.');
    define('RULE8', 'Pamiętaj, jeżeli chcesz grać w tę grę, musisz zaakceptować zasady w niej obowiązujące.');
    define('IMAGE_CODE', 'Przepisz kod z obrazka (od lewej do prawej):');
}
    else
{
    define('ERROR_IMAGECODE', '<span style="text-decoration:blink;">Niepoprawny kod z obrazka</span><br/>');
	define ('RET', 'Wróć do <a href="register.php">rejestracji</a>.');

    define('EMPTY_FIELDS', 'Musisz wypełnić wszystkie pola. '.RET);
    define('BAD_EMAIL', 'Nieprawidłowy adres email.');
    define('USED_NICK', 'Ktoś już wybrał taki pseudonim. '.RET);
    define ('INVALID_NICK', 'Nieprawidłowy pseudonim. '.RET);
    define('LONG_NICK', 'Twoje imię jest za długie. Może mieć maksymalnie 15 znaków. '.RET);
    define ('BAD_NICK', 'Ktoś już posiada taki pseudonim. '.RET);
    define('EMAIL_HAVE', 'Ktoś już posiada taki adres mailowy. '.RET);
    define('EMAIL_MISS', 'Zły adres email. '.RET);
    define('WELCOME_TO', 'Witaj w');
    define('YOUR_LINK', '. Twój link aktywacyjny to:');
    define('ACTIV_LINK', '/aktywacja.php?kod=');
    define('NICE_PLAYING', ' Życzę miłej zabawy w');
    define('SUBJECT', 'Rejestracja na');
    define('EMAIL_ERROR', 'Wiadomość nie została wysłana. Błąd:<br /> ');
    define('REGISTER_FAILED', 'Nie mogę zarejestrować. '.RET);
    define('REGISTER_SUCCESS', 'Jesteś już zarejestrowany. Sprawdź swoją skrzynkę pocztową.');
}
?>
