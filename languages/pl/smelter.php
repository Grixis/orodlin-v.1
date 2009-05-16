<?php
/**
 *   File functions:
 *   Polish language for smelter
 *
 *   @name                 : smelter.php                            
 *   @copyright            : (C) Orodlin
 *   @author               : mzah <s.paweska@gmail.com>
 *   @version              : preAlfa
 *   @since                : 26.08.2007
 *
 */

define('ERROR', 'Zapomnij o tym!');
define('A_BACK', 'wróć');
define('IN_BACKPACK', 'Zapasowe ');
define('EQUIP_AGI', 'zr');
define('EQUIP_SPEED', 'szyb');
define('AMOUNT', 'Ilość');;
define('A_PRZETOP', 'przetop');
define('A_WZMOCNIJ', 'wzmocnij');
define('DURABILITY', 'wytrzymałości');
define('A_PRZETOP_ALL', 'przetop wszystkie zapasowe ');
define('WEAPON', 'Broń');
define('EMPTY_SLOT', 'brak');
define('HELMET', 'Hełm');
define('ARMOR', 'Zbroja');
define('SHIELD', 'Tarcza');
define('LEGS', 'Nagolenniki');;
define('POISON_TYPE', 'trucizna z');
define('POWER', 'moc');
define('NO_ITEMS', 'Nie masz takiego przedmiotu!\n');
define('NOT_YOUR', 'To nie twój przedmiot!\n');
define('ARR_AMOUNT', 'sztuk');
define('NO_ITEM', 'Nie masz takiego przedmiotu!');
define('WEAPONS', 'bronie');
define('HELMETS', 'hełmy');
define('ARMORS', 'zbroje');
define('LEGS2', 'nagolenniki');
define('SHIELDS', 'tarcze');
define('YOU_DEAD', 'Niby martwy, a taki ruchliwy zarazem. Mimo wszystko wydaje mi się, że w tym stanie za wiele nie zrobisz.');

if (!isset($_GET['step'])) {
	define('MENU_INFO', '<p>Opis huty</p>');
	define('MENU_WYTOP', 'Wytapiaj surowce');
	define('MENU_PRZETOP', 'Przetop ekwipunek');
	define('MENU_WZMOCNIJ', 'Wzmocnij ekwipunek');
}

if (isset($_GET['step']) && $_GET['step'] == 'przetop') {
	define('PRZETOP_OPIS', 'W tym miejscu możesz przetopić swój ekwipunek.');
	define('NO_ARM', 'Nie ma takiego przedmiotu!');
	define('OWNER', 'Nie jesteś właścicielem tego przedmiotu!');
	define('NO_COAL', 'Masz za mało węgla by rozgrzać piece!');
	define('SZTABKA1', ' sztabek miedzi, ');
	define('SZTABKA2', ' sztabek brązu, ');
	define('SZTABKA3', ' sztabek mosiądzu, ');
	define('SZTABKA4', ' sztabek żelaza, ');
	define('SZTABKA5', ' sztabek stali, ');
	define('PRZETOP', 'Uzyskałeś ');
	define('PRZETOP2', ' punktów doświadczenia, ');
	define('PRZETOP3', ' hutnictwa, co wymagało ');
	define('ENERGY', ' energii.');
	define('HUTA_LVL', ' Zbyt niski poziom huty.');
	define('HOW_MANY', ' sztuk.');
	if (!defined('NO_ENERGY'))
	{
		define('NO_ENERGY', 'Nie masz energii aby wytapiać surowce');
	}
}

if (isset($_GET['step']) && $_GET['step'] == 'przetop_wszystkie') {
	define('PRZETOP_OPIS', 'opisolodzy do dzieła!');
	define('NO_ARM', 'Nie ma takiego przedmiotu!');
	define('OWNER', 'Nie jesteś właścicielem tego przedmiotu!');
	if (isset($_GET['action'])) {
		define('NO_COAL', 'Masz za mało węgla by rozgrzać piece!');
		define('SZTABKA1', ' sztabek miedzi, ');
		define('SZTABKA2', ' sztabek brązu, ');
		define('SZTABKA3', ' sztabek mosiądzu, ');
		define('SZTABKA4', ' sztabek żelaza, ');
		define('SZTABKA5', ' sztabek stali, ');
		define('ENERGY', ' energii.');
		define('NO_ENERGY', 'Nie masz energii aby wytapiać surowce');
		define('PRZETOP', 'Uzyskałeś ');
		define('PRZETOP2', ' hutnictwa i ');
		define('PRZETOP3', ' punktów doświadczenia. Poświęciłeś ');
		define('YOU_DEAD', 'Nie możesz wytapiać sztabek ponieważ jesteś martwy');
		define('HUTA_LVL', ' Zbyt niski poziom huty.');
	}
}

if (isset($_GET['step']) && $_GET['step'] == 'wzmocnij') {
	define('WZMOCNIJ_OPIS', 'Wzmocnienie przedmiotów:<br /><br />
- adamantyt - wzmacnia przedmiot czyniąc go smoczym<br />
- kryształ - wzmacnia przedmiot czyniąc go elfim<br />
- meteoryt - wzmacnia przedmiot łączac w sobie moc adamantytu i kryształu (smoczy i elfi)<br /><br />');
	define('W_WZMOCNIJ', ' wybrany przedmiot');
	define('ADAMANTYT', 'Wybierz adamantyt');
	define('KRYSZTAL', 'Wybierz kryształ');
	define('METEORYT', 'Wybierz meteoryt');
	define('NO_ARM', 'Nie ma takiego przedmiotu!');
	define('OWNER', 'Nie jesteś właścicielem tego przedmiotu!');
	define('NO_MINERALS', 'Nie masz minerałów potrzebnych do wzmocnienia tego przedmiotu!');
	define('WZMOCNIONY', 'Przedmiot był już wzmacniany!');
	define('SUKCES', 'Udało Ci się wzmocnić przedmiot o ');
	define('SUKCESA', ' siły.');
	define('SUKCESK', ' zręczności.');
	define('SUKCESM', ' siły i zręczności.');
	define('PORAZKA', 'Niestety nie udało Ci się wzmocnić przedmiotu i uległ on zniszczeniu.');
	define('KLASA', 'Tylko rzemieślnik może wzmacniać przedmioty.');
	define('YOU_GAINED', 'Zdobyłeś ');
	define('SMELTER_ABILITY', ' hutnictwa i ');
	define('EXPERIENCE', ' doświadczenia.');
}

if (isset($_GET['step']) && $_GET['step'] == 'wytop') {
	define('NO_UPGRADE', 'Nie możesz więcej rozbudowywać huty!');
	define('LEVEL0', 'Poziom 1 - wytapianie miedzi (1000 sztuk złota)');
	define('LEVEL1', 'Poziom 2 - wytapianie miedzi, brązu (5000 sztuk złota)');
	define('LEVEL2', 'Poziom 3 - wytapianie miedzi, brązu, mosiądzu (20000 sztuk złota)');
	define('LEVEL3', 'Poziom 4 - wytapianie miedzi, brązu, mosiadzu, żelaza (60000 sztuk złota)');
	define('LEVEL4', 'Poziom 5 - wytapianie miedzi, brązu, mosiądzu, żelaza, stali (120000 sztuk złota)');
	define('UPGRADE_INFO', 'Możesz ulepszyć swoją hutę. Każdy nowy poziom huty pozwala ci wytapiać kolejne surowce.');
	define('A_UPGRADE', 'Ulepsz');
	define('NO_MONEY', 'Nie masz tyle sztuk złota!');
	define('YOU_UPGRADE', '<b>Rozbudowałeś swoją hutę.</b>');
	define('SMELT_INFO', 'Tutaj możesz wytapiać rudy minerałów w celu zdobycia surowców potrzebnych do wytwarzania przedmiotów.');
	define('SMELT1', 'Wytapiaj miedź (2 rudy miedzi + 1 bryła węgla)');
	define('SMELT2', 'Wytapiaj brąz (1 ruda miedzi + 1 ruda cyny + 2 bryły węgla)');
	define('SMELT3', 'Wytapiaj mosiądz (2 rudy miedzi + 1 ruda cynku + 2 bryły węgla)');
	define('SMELT4', 'Wytapiaj żelazo (2 rudy żelaza + 3 bryły węgla)');
	define('SMELT5', 'Wytapiaj stal (3 rudy żelaza + 7 brył węgla)');
	define('ENERGY_PER_TRY', ' energii na sztabkę.');
	define('YOU_MAY', 'Maksymalna ilość sztabek jaką możesz wytopić:');
	define('SMELTM1', ' dla sztabek miedzi, ');
	define('SMELTM2', ' dla sztabek brązu, ');
	define('SMELTM3', ' dla sztabek mosiądzu, ');
	define('SMELTM4', ' dla sztabek żelaza, ');
	define('SMELTM5', ' dla sztabek stali, ');
	define('YOU_HAVE', 'Posiadasz:');
	define('MIN1', ' rud miedzi, ');
	define('MIN2', ' brył węgla, ');
	define('MIN3', ' rud cyny, ');
	define('MIN4', ' rud cynku, ');
	define('MIN5', ' rud żelaza.');
	if (isset($_GET['action'])) {
		define('NO_MINERALS', 'Nie masz minerałów potrzebnych do wytapiania tego surowca!');
		define('SMELTMS1', ' sztabek miedzi.');
		define('SMELTMS2', ' sztabki brązu za ');
		define('SMELTMS3', ' sztabki mosiądzu za ');
		define('SMELTMS4', ' sztabki żelaza za ');
		define('SMELTMS5', ' sztabki stali za ');
		define('SMELTME1', ' sztabek miedzi');
		define('SMELTME2', ' sztabek brązu');
		define('SMELTME3', ' sztabek mosiądzu');
		define('SMELTME4', ' sztabek żelaza');
		define('SMELTME5', ' sztabek stali');
		define('A_SMELT', 'Wytop');
		define('NO_ENERGY', 'Nie masz energii aby wytapiać surowce');
		define('YOU_SMELT', 'Uzyskałeś');
		define('YOU_SMELT2', ' hutnictwa i ');
		define('YOU_SMELT3', ' punktów doświadczenia.');
		if (!defined('YOU_DEAD'))
		{
			define('YOU_DEAD', 'Nie możesz wytapiać sztabek ponieważ jesteś martwy');
		}
	}
}
?>
