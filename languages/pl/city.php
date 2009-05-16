<?php
/**
 *   File functions:
 *   Polish language for city
 *
 *   @name                 : city.php
 *   @copyright            : (C) 2004,2005,2006 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @author               : Marek Stasiak <l3thal2@gmail.com>
 *   @version              : 1.2
 *   @since                : 12.10.2006
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
// $Id: city.php 704 2006-10-12 16:21:03Z thindil $

define('NO_CITY', 'Nie znajdujesz się w mieście');
define('OGLOSZENIE', 'Najnowsze ogłoszenie:');
define('AUTOR', ' napisane przez ');
define('NOWE', 'Dodaj nowe');
define('LISTA', '10 ostatnich');
define('OSTATNIE', 'Ostatnie ogłoszenia:');
define('OGLOSZENIE_INFO', 'Tutaj możesz dodać ogłoszenie, które będzie widoczne dla wszystkich graczy znajdujących się w mieście. Długość ogłoszenia: 255 znaków. Cena: ');
define('TYTUL', 'Tytuł:');
define('TEKST', 'Tekst:');
define('WYSLIJ', 'Wyślij');
define('ERR_FIELDS', 'Wypełnij wszystkie pola!');
define('ERR_GOLD', 'Nie masz tyle złota!');
define('ERR_ADDED', 'Ogłoszenie zostało dodane');
define('ERR_DEL', 'Usuń');
define('ERR_CONFIRM', 'Czy na pewno chcesz usunąć to ogłoszenie?');
define('PERMISSION', 'Nie masz do tego prawa!');
define('NO_ANN', 'Nie zaznaczono ogłoszenia do usunięcia!');
define('DELETED', 'Ogłoszenie zostało usunięte.');


if ($player -> location == 'Altara') {
	$arrTitles = array('DZIELNICA PORTOWA', 'DZIELNICA MIESZKALNA', 'TWIERDZA', 'CENTRUM', 'PCHLI TARG', 'PAŁAC', 'BRAMY MIASTA', 'PODGRODZIE');
	$arrNames = array(array('Kanały', 'Plotki', 'Tawerna', 'Przytułek', 'Oczyszczanie miasta'),
	array('Domy', 'Klany', 'Gazeta', 'Magiczna Wieża', 'Pracownia alchemiczna', 'Świątynia'),
	array('Arena Walk', 'Trening', 'Lochy', 'Królewski Skład'),
	array('Posągi', 'Spis mieszkańców', 'Galeria Bohaterów', 'Zegar słoneczny', 'Aleja Zasłużonych'),
	array('Zbrojmistrz', 'Płatnerz', 'Fleczer', 'Apteka', 'Jubiler', 'Targowisko'),
	array('Wieści', 'Biblioteka', 'Gmach Sądu', 'Hala zgromadzeń', 'Sala audiencyjna'),
	array('Stajnia'),
	array('Strażnica', 'Farma', 'Polana Chowańców', 'Tartak')
	);
	define('CITY_INFO', '<p>Opis Miasta '.$city1a.'</p>');
	define('STAFF_QUEST', 'Idąc ulicami '.$city1b.' czujesz się tak, jakbyś po raz pierwszy oglądał jej mury. Wydawała się promienieć pięknem... Zaśmiałeś się w duchu-po tych upiornych labiryntach wszystko byłoby wspaniałe. Wdychane świeże, poranne powietrze przyjemnie łaskotało płuca wywiewając z nich resztki zatęchłego odoru ziemi i wilgoci. Tak... Właśnie tak czuje się Bohater!<br /><br />Co dalej, Bohaterze?<br />');
	define('SQ_BOX1', 'kierujesz się do wieży Nubii, by oddać jej różdżkę');
	define('SQ_BOX2', 'zatrzymujesz różdżkę dla siebie');
	define('STAFF_QUEST2', 'Zmęczony i senny próbowałeś skupić myśli na tym, jak znaleźć drogę do domu, ale dalsza wędrówka wydała się wręcz niemożliwa. Karczma! Tak, to dobry wybór.<br />Gdy wszedłeś w jej progi, powitał cię chytry wzrok, grubego hobbita z namaszczeniem wycierającego drewniany kontuar. Zmierzył cię od stóp do głów i syknął przez zęby:<br />-Wynocha oberwańcu! Wykąp się, bo muchy padają od twojego zapaszku!<br />No tak... Po raz pierwszy od wyjścia z labiryntu spojrzałeś na siebie i widok nie był zachwycający: oberwane, brudne i cuchnące stęchlizną szatki, ziemia, pot i krew zakrzepłe na twarzy. Nie dziwota, że karczmarz nie chciał u siebie takiego gościa. Jednak jest coś, co zmiękczy jego miłość do higieny.<br />-Mam złoto. Chętnie kupię twoją wannę i pokój na kilka dni. Muszę porządnie wypocząć...<br />Gromki rechot hobbita przerwał twoje słowa:<br />-Złoto nie wynagrodzi mi wypłoszonych smrodem klientów! Musiałbyś mi dać coś cenniejszego, niż stu z nich!<br />-Coś cennego powiadasz...? Hmmm... A co dostanę w zamian za diamentową różdżkę...?<br /><br />Zdobywasz 10 000 sztuk złota');
	define('STAFF_QUEST1', 'Choć zmęczenie szarpało w dół twe powieki powiedziałeś sobie, że jeszcze dziś musisz dokończyć to, co zacząłeś, dlatego kierujesz swe kroki do Północnej Wieży Nubii. Ciekawość nie daje ci spokoju- co raz to inne wizje nasuwa ci umysł i wyobraźnia, lecz to właśnie dzięki jej bogactwu droga mija szybko i sprawnie.<br />Po chwili stajesz przed drzwiami wieży i sięgasz do kołatki.<br />-Czekałam na ciebie - słyszysz za sobą. Znasz ten głos. Słyszałeś go wielokrotnie w labiryncie. Gdy odwracasz głowę, widzisz Nubię siedzącą u stóp posągu Anariel. Ich podobieństwo jest uderzające...! Teraz rozumiesz, co miał na myśli Neh nazywając ją żywą boginią. Cóż teraz powiedzieć? Stoisz oszołomiony, nie mogąc wykrztusić słowa.<br />-Wspaniale rozwiązałeś zagadkę. Byłam tam z tobą myślami i śledziłam każdy ruch. Tylu wojowników zginęło podczas tej misji... Byli tacy nierozważni... -westchnęła cicho, po czym w zamyśleniu chwyciła cię za rękę i poprowadziła za sobą do świątyni.');
	define('ITEM', 'Różdżka Nubii');


} elseif ($player -> location == 'Ardulith') {
	$arrTitles = array('PODZIEMIA', 'JASKINIA MIESZKALNA', 'TARG', 'ZAMEK NAMIESTNIKA', 'KOSZARY', 'BRAMY MIASTA');
	$arrNames = array(array('Huta', 'Kuźnia', 'Kopalnia', 'Opuszczony szyb', 'Jubiler', 'Podziemna wartownia'),
	array('Domy', 'Klany', 'Schronisko', 'Plotki', 'Tawerna'),
	array('Rynek', 'Królewski Skład', 'Płatnerz', 'Zbrojmistrz', 'Apteka', 'Krasnoludzki Bank', 'Fleczer'),
	array('Wieści','Świątynia', 'Redakcja gazety', 'Biblioteka', 'Gmach Sądu', 'Posągi', 'Spis mieszkańców', 'Zegar miejski', 'Hala zgromadzeń', 'Sala audiencyjna'),
	array('Arena Walk', 'Lochy', 'Trening', 'Polana Chowańców'),
	array('Strażnica', 'Farma', 'Stajnia', 'Kopalnia w górach')
	);
	define('CITY_INFO', '<p>Opis Miasta '.$city2.'</p>');
	define('GO_MOUNTAINS', '<p>Opis Przejscia w góry</p><p>Kliknij <a href="gory.php">tutaj</a>.</p>');

}
?>
