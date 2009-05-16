<?php
/**
 *   File functions:
 *   Polish language for outposts
 *
 *   @name				 : outposts.php
 *   @copyright			: (C) 2004,2005,2006,2007 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author			   : thindil <thindil@users.sourceforge.net>
 *   @author			   : eyescream <tduda@users.sourceforge.net>
 *   @version			  : 1.4
 *   @since				: 19.07.2007
 *
 */

// Published under GNU GPL 2 or later. See /install/README file for details.
// $Id$

define('NO_SCRIPT', 'Twoja przeglądarka ma wyłączony Javascript lub w ogóle go nie obsługuje. Strona nie będzie działać poprawnie.');
define('NOT_IN_CITY', 'Nie masz prawa tutaj przebywać');
define('YOU_DEAD', 'Nie możesz korzystać z Strażnicy ponieważ jesteś martwy!');
define('YOU_UNFINISHED', 'Nie możesz korzystać z Strażnicy ponieważ Twoja postać jest niedokończona. Wybierz rasę i/lub klasę postaci!');
define('GC', 'sz');
define('NO_OUTPOST', 'Nie masz dostępu do strażnicy! Za 500 sztuk złota możesz wykupić kawałek ziemi pod nią.<br /><ul{if $Graphstyle == "Y"} class="sword"{/if}><li><a href="outposts.php?action=buy">Kupuję</a></li><li><a href="city.php">Nie, dzięki</a></li></ul>');
define('ERROR', 'Zapomnij o tym!');

if (!isset($_GET['view']))
{
	define('A_MENU', 'Menu');
	define('A_MY', 'Kwatera Główna');
	define('A_TAX', 'Poborca');
	define('A_SHOP', 'Komnata Zbrojeń');
	define('A_GOLD', 'Skarbiec');
	define('A_ATTACK', 'Sztab');
	define('A_LIST', 'Wywiad');
	define('A_GUIDE', 'Dziedziniec');
	define('A_BEASTS', 'Legowiska');
	define('A_VETERANS', 'Kwatery');
}

if (isset ($_GET['view']) && $_GET['view'] == 'menu')
	define('OUT_INFO', '<p>Wstępny Opis Strażnicy</p>');

if (isset ($_GET['action']) && $_GET['action'] == 'buy')
{
	define('YOU_BUY', 'Posiadasz już strażnicę! Kliknij <b><a hrefoutposts.php">tutaj</a></b>, by ją obejrzeć.');
	define('NO_MONEY', 'Nie masz wystarczająco dużo pieniędzy aby zakupić ziemię pod strażnicę.');
	//define('GO_TO', 'Możesz już udać się to swojej strażnicy! Kliknij <b><a href="#remote-tab-2">tutaj</a></b> by ją obejrzeć.');
}

if (isset ($_GET['view']) && $_GET['view'] == 'gold')
{
	define('GOLD_INFO', '<p>Opis Skarbca Strażnicy.</p><p>Przelicznik w przypadku wybrania sztuk złota ze Strażnicy wynosi 2:1 (czyli za 2 sztuki złota ze strażnicy dostajesz 1 sztukę złota do ręki), natomiast w przypadku dotowania strażnicy przelicznik wynosi 1:1. Obecnie w strażnicy posiadasz</p>');
	define('GOLD_COINS', '<i>sztuk złota</i>');
	define('A_TAKE', 'Zabierz');
	define('A_ADD', 'Dodaj');
	define('FROM_OUT', 'sztuk złota ze strażnicy');
	define('TO_OUT', 'sztuk złota do strażnicy');
	define('NO_MONEY', 'Nie masz tyle sztuk złota!');
	define('YOU_ADD', 'Dodałeś ');
	if (isset ($_POST['zeton']))
	{
		define('YOU_CHANGE', 'Zamieniłeś <b>');
		define('GOLD_ON', '</b> sztuk złota ze strażnicy na <b>');
		define('GOLD_ON2', '</b> sztuk złota do ręki.');
	}
}

if (isset ($_GET['view']) && $_GET['view'] == 'my')
{
	define('WELCOME', '<i>Witaj w Kwaterze Głównej</i>');
	define('OUTPOST_INFO', 'Informacje o Strażnicy');
	define('LAND_A', 'Liczba ziem');
	define('AP', 'Punktów Ataku');
	define('T_SOLDIERS', 'Wojowników');
	define('T_ARCHERS', 'Łuczników');
	define('T_CATAPULTS', 'Katapult');
	define('T_FORTS', 'Barykad');
	define('T_LAIRS', 'Legowiska bestii');
	define('T_MONSTERS', 'Bestie');
	define('T_BARRACKS', 'Kwatery Weteranów');
	define('T_VETERANS', 'Weterani');
	define('T_FATIGUE', 'Zmęczenie Armii');
	define('T_MORALE', 'Morale Armii');
	define('T_COST', 'Koszt');
	define('T_BONUS', 'Premie dowódcy');
	define('T_ATTACK', 'Siła ataku');
	define('T_DEFENSE', 'Obrona');
	define('T_TAX', 'Wpływy z danin');
	define('T_LOSSES', 'Straty w bitwie');
	define('T_COST_B', 'Koszt utrzymania');
	define('MORALE1', 'Bojowe');
	define('MORALE2', 'Neutralne');
	define('MORALE3', 'Bunt');
	define('T_FREE', 'wolne');
	define('T_POWER', 'łączna siła');
	define('U_DEFENSE', 'obrona');
	define('T_MAX', 'maksymalnie');
	define('USED', 'zajęte');
	define('MORALE_MOD', 'kara morale przeciwnika');
	define('MORALE_MOD2', 'bonus morale Twoich wojsk');
	define('T_COST_G', 'sztuk złota na reset');
	define('A_ADD', 'Dodaj premię');
	define('TO', 'do');
	define('T_GOLD_COINS', 'Sztuk złota');
	if (isset($_POST['ability']))
	{
		define('NO_BONUS', 'Nie możesz podnieść jakiejkolwiek premii');
		define('MAX_LEVEL', 'Osiągnąłeś już maksymalny poziom tej premii');
		define('YOU_ADD', 'Premia dodana.');
	}
}

if (isset ($_GET['view']) && $_GET['view'] == 'taxes')
{
	define('TAX_INFO', '<p>Wszedłeś do  składziku Poborcy Podatkowego... </p>');
	define('A_SEND', 'Wyślij');
	define('SOLDIERS', 'żołnierzy');
	define('TIMES', 'razy do wiosek');
	define('NO_EN_AP', 'Nie masz ani jednego Punktu Ataku. Odczekaj trochę czasu.');
	define('NO_SOLDIERS', 'Nie masz żołnierzy aby zbierali podatki!');
	define('YOU_ARMY', 'Twoi żołnierze wyruszyli ');
	define('TIMES_FOR', ' razy na zbieranie danin z wiosek i zebrali w ten sposób ');
	define('GOLD_COINS', ' sztuk złota.');
}

if (isset ($_GET['view']) && $_GET['view'] == 'shop')
{
	define('SHOP_INFO', '<p> Komnata Zbrojeń. </p>');
	define('SHOP_INFO2', '<i>żołnierzy oraz</i>');
	define('SHOP_INFO3', '<i>katapult lub barykad.</i><br/>Posiadasz: ');
	define('OUTPOST_ON_LEVEL', 'strażnicę na poziomie');
	define('LAIRS', 'legowisk');
	define('BARRACKS', 'kwater');
	define('OUTPOST_DEVELOPMENT', 'Rozbudowa Strażnicy');
	define('LEVEL_INFO', 'Rozbuduj o');
	define('LEVELS_SPENDING', 'poziom(y), wydając:');
	define('LAIRS_SPENDING', 'Legowisk Bestii, wydając:');
	define('BARRACKS_SPENDING', 'Kwater(y) Weteranów, wydając:');
	define('A_BUY', 'Kup');
	define('SPENDING', 'wydając');
	define('MAX_FENCES_AND_BARRACKS', 'Posiadasz maksymalną ilość Kwater Weteranów i Legowisk Bestii. Zacznij od rozbudowy strażnicy');
	define('YOU_NEED', 'Potrzebujesz');
	define('YOU_PAID', 'Zapłaciłeś');
	if (isset($_POST['level']))
		define('YOU_ADD', 'Powiększyłeś swą Strażnicę do rozmiaru');
	elseif (isset($_POST['lairs']))
		define('YOU_ADD', 'Zwiększyłeś ilość Legowisk do');
		elseif (isset($_POST['barracks']))
			define('YOU_ADD', 'Zwiększyłeś ilość Kwater do');
	define('ARMY_DEVELOPMENT', 'Rozbudowa armii');
	define('B_SOLDIERS', 'piechurów (+3 atak, +1 obrona, 25 sztuk złota jeden).');
	define('B_ARCHERS', 'łuczników (+1 atak, +3 obrona, 25 sztuk złota jeden).');
	define('B_MACHINES', 'katapult (+3 atak, 35 sztuk złota jedna).');
	define('B_FORTS', 'barykad (+3 obrona, 35 sztuk złota jedna).');
	define('GOLD_COINS', ' złota');
	define('PLATINUM_PIECES', ' mithrilu');
	define('PINE_PIECES', ' sosny');
	define('CRYSTAL_PIECES', ' kryształów');
	define('ADAMANTIUM_PIECES', ' adamantium');
	define('METEOR_PIECES', ' meteorytu');
	define('NO_MONEY', 'Nie stać Cię na to.');
	define('WARRIORS', 'wojowników');
	define('ARCHERS', 'łuczników');
	define('BARRICADES', 'barykad');
	define('CATAPULTS', 'katapult');
	define('YOU_HAVE_SPENT', 'Dokupiłeś wojska do Strażnicy wydając ');
	define('ON', ' na');
	define('REFRESH', ' <a href="outposts.php">Odśwież</a>');
}

if (isset ($_GET['view']) && $_GET['view'] == 'listing')
{
	define('LISTING_INFO', '<p>Wywiad...</p>');
	define('A_SHOW', 'Pokaż');
	define('A_SEARCH', 'Szukaj');
	define('FROM_L', 'strażnice od <b>rozmiaru</b>');
	define('TO_L', 'do');
	define('OUT_ID', 'ID Strażnicy');
	define('OUT_SIZE', 'Rozmiar strażnicy');
	define('OUT_OWNER', 'Właściciel');
	define('OUT_ATTACK', 'Atakować?');
	define('A_ATTACK', 'Atak');
	define('SEARCH_BY_NAME', 'Strażnic graczy o <b>imieniu</b> (jeżeli nie znasz jego dokładnego imienia, użyj znaku * zamiast liter):');
	define('SEARCH_BY_ID', 'Strażnicę gracza o <b>ID</b>:');
	define('SEARCH_BY_TRIBE', 'Strażnic należących do <b>członków klanu</b>:');
}

if (isset ($_GET['view']) && $_GET['view'] == 'battle')
{
	define('BATTLE_INFO', '<p>Sztab Generalny...</p>');
	define('OUT_ID', 'ID właściciela Strażnicy');
	define('AMOUNT_A', 'Ilość ataków');
	define('A_ATTACK', 'Atak');
	define('TOO_FAT', 'Twoja armia jest zbyt zmęczona by atakować!');
	define('NO_AP', 'Nie masz wystarczającej ilości punktów ataku.');
	define('ITS_YOUR', 'Nie możesz zaatakować własnej Strażnicy.');
	define('NO_OUT', 'Ten gracz nie posiada Strażnicy.');
	define('TOO_MUCH_A', 'Jedna strażnica może być zaatakowana tylko 3 razy na reset!');

	define('YOU_ATTACKED', 'Zaatakowałeś Strażnicę gracza');
	define('YOU_WERE_ATTACKED_BY', 'Twoja Strażnica została zaatakowana przez wojska gracza');
	define('TIMES', 'raz(y).');
	define('STATS_WERE', 'Statystyki');
	define('ATTACK', 'ataku kontra');
	define('DEFENSE', 'obrony.');
	define('RESULT', 'Wynik potyczki:');
	define('YOU_WON', 'Wygrałeś!');
	define('YOU_LOST', 'Przegrałeś!');
	define('WON_GOLD', 'Zdobyte złoto');
	define('LOST_GOLD', 'Stracone złoto');
	define('GAIN_LEADERSHIP', 'Twoja umiejętność dowodzenia rośnie o');

	define('YOUR_ARMY_IS_TOO_TIRED', 'Twoja armia jest zbyt zmęczona aby mogła atakować dalej!');
	define('YOU_CANNOT_ATTACK_HIM_MORE', 'Nie możesz więcej atakować tej strażnicy! Musisz poczekać do kolejnego resetu.');

	define('YOU_LOSE', 'Twoja armia traci: ');
	define('HE_LOSSES', 'Straty przeciwnika: ');
	define('SOLDIERS', 'piechurów');
	define('ARCHERS', 'łuczników');
	define('MACHINES', 'katapult');
	define('FORTS', 'barykad');
	define('BEASTS', 'Bestie');
	define('VETERANS', 'Weterani');
}

if (isset($_GET['view']) && $_GET['view'] == 'beasts')
{
	require_once('languages/'.$player -> lang.'/corenames.php');

	define('BEASTS_INFO', '<p>Legowiska Bestii ...</p>');
	define('YOU_HAVE', 'Posiadasz ');
	define('YOU_DONT_HAVE', 'Nie posiadasz ');
	define('FREE_LAIRS', 'wolnych Legowisk Bestii');
	define('BEASTS_IN', 'Lista Bestii w Strażnicy:');
	define('NO_BEASTS_IN', 'Nie posiadasz żadnych Bestii w Strażnicy');
	define('CORES', 'Lista Chowańców na Polanie:');
	define('NO_CORES', 'Nie posiadasz żadnych Chowańców');
	define('NAME', 'Imię');
	define('TYPE', 'Typ');
	define('ATTACK', 'Atak');
	define('DEFENCE', 'Obrona');
	if (isset($_POST['id']))
	{
		define('NO_LAIR', 'Nie masz legowisk na kolejne bestie!');
		define('NOT_YOUR', 'To nie Twój chowaniec!');
		define('NO_MONEY', 'Nie masz dość pieniędzy!');
		define('YOU_ADD', 'Dodałeś bestię: <b>');
		define('WITH_P', '</b> o sile ');
		define('AND_D', ' i obronie ');
		define('IT_COST1', '. Wydałeś na to ');
		define('IT_COST2', ' sztuk złota. ');
		define('AND_MODIFIED_MORALE_BY', 'Bestia redukuje morale przeciwnika o ');
	}
}

if (isset($_GET['view']) && $_GET['view'] == 'veterans')
{
	define('VETERANS_INFO', '<p>Kwatery Weteranów ...</p>');
	define('WORK_AREA', 'Poniżej widzisz obszar roboczy. By dodać nowego weterana, przeciągaj elementy ekwipunku, na koniec wpisz imię i kliknij "Wyślij". By edytować ekwipunek już dodanego weterana, przeciągnij go z listy.');
	define('YOU_HAVE', 'Posiadasz ');
	define('YOU_DONT_HAVE', 'Nie posiadasz ');
	define('FREE_BARRACKS', 'wolnych Kwater Weteranów');
	define('VETERANS_IN', 'Lista Weteranów:');
	define('NO_VETERANS_IN', 'Nie posiadasz żadnych Weteranów');
	define('NEW_NAME', 'Nowy Weteran');
	define('A_SEND', 'Wyślij');
	define('A_CLEAR', 'Wyczyść');
	define('A_EDIT', 'Zakończ edycję');
	define('NAME', 'Imię');
	define('TYPE', 'Typ');
	define('ITEM_NAME', 'Nazwa przedmiotu');
	define('ATTACK', 'Atak');
	define('DEFENCE', 'Obrona');

	define('WEAPONS', 'Broń');
	define('BOWS', 'Łuk');
	define('ARMORS', 'Zbroja');
	define('SHIELDS', 'Tarcza');
	define('HELMETS', 'Hełm');
	define('PLATE_LEGS', 'Nagolenniki');
	define('RINGS', 'Pierścienie');

	define('NO_MONEY', 'Nie masz dość pieniędzy!');
	define('STRANGE_ITEM_TYPE', 'Podejrzany typ przedmiotu! Jeśli nic nie kombinowałeś z HTMLem strony, zgłoś problem administratorowi.');
	define('EQUIPMENT_ERROR', 'Coś jest nie tak. To nie Twój przedmiot, taki w ogóle nie istnieje, nie zgadza się typ lub nie znajduje się w plecaku. Jeśli nic nie kombinowałeś z HTMLem strony, zgłoś problem administratorowi.');
	define('VETERAN_ERROR', 'Coś jest nie tak. To nie Twój weteran lub taki w ogóle nie istnieje. Jeśli nic nie kombinowałeś z HTMLem strony, zgłoś problem administratorowi.');
	define('SUCCESS', 'Zmodyfikowałeś ekwipunek weterana.');
	define('YOU_BOUGHT', 'Dodałeś do Strażnicy weterana za 1000 sztuk złota.');

}

if (isset ($_GET['view']) && $_GET['view'] == 'equip')
{
	define('WEAPONS', 'Broń');
	define('BOWS', 'Łuk');
	define('ARMORS', 'Zbroja');
	define('SHIELDS', 'Tarcza');
	define('HELMETS', 'Hełm');
	define('PLATE_LEGS', 'Nagolenniki');
	define('RINGS', 'Pierścienie');
}
if (isset ($_GET['view']) && $_GET['view'] == 'guide')
{
	define('INFO1', '<p>Dzuedziniec...</p>');
}
?>
