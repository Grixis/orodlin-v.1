<?php
/**
 *   File functions:
 *   Polish language for core arena
 *
 *   @name                      : core.php	
 *   @copyright                 : (C) 2004,2005,2006,2007 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author                    : thindil <thindil@users.sourceforge.net>
 *   @author                    : eyescream <tduda@users.sourceforge.net>
 *   @author                    : Marek 'marq' Chodor <marek.chodor@gmail.com>
 *   @version                   : 1.3
 *   @since                     : 31.08.2007
 *
 */

//
//
//	   This program is free software; you can redistribute it and/or modify
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

require_once("languages/".$player -> lang."/corenames.php");

if (!defined('ERROR')) define('ERROR','Zapomnij o tym!');
if (!defined('BACK')) define('BACK','Wróć');

if ($player -> corepass != 'Y') {
	define('NO_MONEY', 'Licencja kosztuje 500 sztuk złota - których akurat nie masz przy sobie. Proszę, wróć kiedy będziesz miał odpowiednią sumę.');
	define('YES_LICENSE', 'Świetnie - masz już Licencję na Chowańca. Kliknij <a href="core.php">tutaj</a> aby kontynuować.');
	define('COREPASS_INFO', 'Witaj na Polanie Chowańców. Jest to miescje, gdzie trzymane są zwierzęta występujące na Orodlinie. Normalnie poluje się na nie jako na zwierzynę łowną, ale tutaj są trzymane pod strażą. Jeżeli kupisz Licencje na Chowańca, będziesz mógł złapać i trenować własnego chowańca.');
	define('A_YES', 'Jasne, kupuję.');
	define('A_NO', 'Nie...');
	define('HAVE_MONEY', 'Masz 500 sztuk złota - dlaczego nie kupisz licencji? To otworzy Ci kolejne miejsce w mieście.');
	}
else if (!isset($_GET['view'])) {

	if ($player -> location == 'Altara')
		define('CORE_MAIN', 'Witaj w Sektorze! Widzę, że masz swoją licencję... w porządku, baw się dobrze.');
	else
		define('CORE_MAIN', 'Opis polany chowańców. Co robisz?');

	define('A_MY_CORE', 'Moje Chowańce');
	define('A_ARENA', 'Arena Chowańców');
	define('A_TRAIN', 'Sala Treningowa chowańców');
	define('A_MARKET', 'Sklep z Chowańcami');
	define('A_SEARCH', 'Szukaj');
	define('A_BREED', 'Zagroda Chowańców');
	define('A_MONUMENTS', 'Najlepsze chowańce');
	
	}
else {
	if ($_GET['view'] == 'my') {
		define('MY_CORES_INFO', 'Tutaj jest lista wszystkich Chowańców jakie znalazłeś.');
		define('NORMAL_ARENA','Arena Zwykła');
		define('MAGIC_ARENA','Arena Magiczna');
		if (isset($_GET['id']) || isset($_POST['id'])) {
			if (isset($_GET['action']) && $_GET['action'] == 'changename') {
				define('NAME_CHANGED','Imię zostało zmienione');
				}
			else if (isset($_GET['action']) && $_GET['action'] == 'free') {
				define('FREE_INFO','Możesz uwolnić chowańca. Nie będziesz go mógł potem odzyskać.');
				define('CORE_FREE','Chowaniec został uwolniony.');
				}
			else if (isset($_GET['action']) && ($_GET['action'] == 'activate' || $_GET['action'] == 'deactivate')) {
				define('ACTIVATE_INFO','Możesz zapisać chowańca na arenę aby mógł walczyć z chowańcami innych graczy.');
				define('WRONG_STATUS','Możesz zapisać na arenę tylko odpoczywające chowańce.');
				define('IS_SEAD','Możesz zapisać na arenę tylko żywe chowańce.');
				define('DEACTIVATE_INFO','Możesz wykreślić chowańca z areny.');
				define('WRONG_STATUS2','Chowaniec nie jest zapisany na arenę.');
				define('YOU_PAY','Musisz zapłacić za to');
				define('PLATINUM','sztuk mithrilu');
				define('ADAMANTIUM','bryłek adamantytu');
				define('CRYSTAL','kawałków kryształu');
				define('NO_MONEY','Nie stać Cię na to.');
				define('CORE_ACTIVATED','Zapisałeś swojego chowańca na arenę.');
				define('CORE_DEACTIVATED','Wypisałeś swojego chowańca z areny.');
				}
			else if (isset($_GET['action']) && $_GET['action'] == 'sell') {
				define('SELL_INFO','Możesz wystawić chowańca na sprzedaż. Podaj cenę:');
				define('BAD_PRIZE','Cena musi być liczbą naturalną dodatnią.');
				define('IS_DEAD','Nie możesz sprzedać martwego chowańca.');
				define('YOU_SOLD','Wystawiłeś chowańca na sprzedaż.');
				define('WRONG_STATUS','Nie możesz sprzedać aktywnego chowańca. Najpierw zabierz go z areny.');
				}
			else if (isset($_GET['action']) && $_GET['action'] == 'unsell') {
				define('YOU_UNSOLD','Wycofałeś ofertę sprzedaży chowańca.');
				define('NOT_FORSALE','Ten chowaniec nie jest na sprzedaż.');
				}
			else if (isset($_GET['action']) && $_GET['action'] == 'pass') {
				define('NO_PLAYER','Nie ma takiego gracza.');
				define('NO_SELF','Czy aby na pewno chcesz przekazać chowańca samemu sobie?');
				define('PASS_INFO','Możesz przekazać chowańca innemu graczowi. Podaj jego numer ID:');
				define('YOU_PASSED','Przekazałeś chowańca');
				define('TO_PLAYER','graczowi');
				define('YOU_GOT','Dostałeś chowańca');
				define('FROM_PLAYER','od gracza');
				define('WRONG_STATUS','Nie możesz przekazać aktywnego chowańca, bądź chowańca wystawionego na sprzedaż.');
				}
			else if (isset($_GET['action']) && ($_GET['action'] == 'heal' || $_GET['action'] == 'resurrect')) {
				define('NO_NEED_HEAL','Chowaniec nie potrzebuje uzdrowienia.');
				define('NO_NEED_RES','Chowaniec nie potrzebuje wskrzeszenia.');
				define('NEED_RESURRECT','Chowaniec potrzebuje wskrzeszenia, a nie leczenia.');
				define('HEAL_COST','Za uleczenie chowańca zapłacisz');
				define('RESURRECT_COST','Za wskrzeszenie chowańca zapłacisz');
				define('PLATINUM','sztuk mithrilu');
				define('ADAMANTIUM','bryłek adamantytu');
				define('CRYSTAL','kawałków kryształu');
				define('NO_MONEY','Nie stać Cię na uleczenie chowańca.');
				define('NO_MONEY2','Nie stać Cię na wskrzeszenie chowańca.');
				define('CORE_HEALED','Twój chowaniec jest uleczony.');
				define('CORE_RESURRECTED','Twój chowaniec został wskrzeszony.');
				}
			define('NOT_YOURS','To nie Twój chowaniec!');
			define('NO_CORE','Nie ma takiego chowańca!');
			define('C_NAME','Imię');
			define('A_HEAL','ulecz');
			define('A_FREE','uwolnij');
			define('A_PASS','przekaż');
			define('A_RESURRECT','wskrześ');
			define('A_SELL','sprzedaj');
			define('A_UNSELL','wycofaj ofertę');
			define('A_ACTIVATE','zapisz na arenę');
			define('A_DEACTIVATE','wycofaj z areny');
			define('CHANGE_NAME','zmień imię');
			define('C_SPECIES','Gatunek');
			define('C_GENDER','Płeć');
			define('GOLD_PIECES','sztuk złota');
			define('GENDER_MALE','Samiec');
			define('GENDER_FEMALE','Samica');
			define('C_HP','Punkty życia');
			define('C_DEAD','Martwy');
			define('C_ATTACK','Atak');
			define('C_DEFENCE','Obrona');
			define('C_SPEED','Szybkość');
			define('C_TEXT','Opis');
			define('C_WINS','Zwycięstw');
			define('C_LOSES','Porażek');
			define('C_AGE','Wiek');
			define('C_MATURE','dojrzały');
			define('C_IMMATURE','młody');
			define('C_ATUT','Atuty');
			define('C_STATUS','Status');
			define('C_ARENA','Arena');
			define('DAYS_TO_BREED','dni do możliwości rozrodu');
			define('ARENA_Z','Zwykła');
			define('ARENA_M','Magiczna');
			define('ACTIVE','Gotowy do walki');
			define('NONACTIVE','Odpoczywa');
			define('FORSALE','Wystawiony na sprzedaż');
			$description = array('',
				'Łasica - opis w przygotowaniu',
				'Sokół - opis w przygotowaniu',
				'Zmutowany żądlak - opis w przygotowaniu',
				'Jeleń - opis w przygotowaniu',
				'Dzik - opis w przygotowaniu',
				'Pustynny wąż - opis w przygotowaniu',
				'Wielki orzeł - opis w przygotowaniu',
				'Wilk - opis w przygotowaniu',
				'Wielki skorpion - opis w przygotowaniu',
				'Bizon - opis w przygotowaniu',
				'Olbrzymi jaszczur - opis w przygotowaniu',
				'Niedźwiedź - opis w przygotowaniu',
				'Tygrys - opis w przygotowaniu',
				'Nosorożec - opis w przygotowaniu',
				'Olifant - opis w przygotowaniu',
				'Harpia - opis w przygotowaniu',
				'Pegaz - opis w przygotowaniu',
				'Minotaur - opis w przygotowaniu',
				'Centaur - opis w przygotowaniu',
				'Jednorożec - opis w przygotowaniu',
				'Gorgona - opis w przygotowaniu',
				'Gryf - opis w przygotowaniu',
				'Astralny Wojownik - opis w przygotowaniu',
				'Feniks - opis w przygotowaniu',
				'Mantikora - opis w przygotowaniu',
				'Behemot - opis w przygotowaniu',
				'Hydra - opis w przygotowaniu',
				'Zielony smok - opis w przygotowaniu',
				'Czerwony smok - opis w przygotowaniu',
				'Czarny smok - opis w przygotowaniu'
				);

			}
		}
	else if ($_GET['view'] == 'search') {
		define('SEARCH_INFO','Stoisz przed ołtarzem przywoływania. Jeśli chcesz, możesz złożyć ofiarę bożkom lasu z 5 bryłek adamantytu, 3 kawałków kryształu, 1 bryłki meterorytu. Skup się i wypowiedz uważnie inskrypcję zapisaną na ołtarzu a może uda Ci się przywołać chowańca. Na każdą próbę musisz poświęcić 1 energii.');
		define('YOU_CAN','Możesz spróbować przywołać chowańca');
		define('TIMES','razy.');
		define('NO_ENERGY','Nie masz wystarczającej ilości energii do przywołania chowańca.');
		define('NO_MINERALS','Nie masz wystarczającej ilości minerałów do przywołania chowańca.');
		define('NO_CORES','Nie udało Ci się przywołać żadnego chowańca.');
		define('SUMMON','przywołuj');
		define('YOU_SUMMON','Przywołałeś');
		define('YOU_GAINED','Zdobyłeś');
		define('EXPERIENCE','punktów doświadczenia');
		}
	else if ($_GET['view'] == 'market') {
		define('MARKET_INFO','Opis sklepu z chowańcami.');
		define('A_BUY','Kup');
		define('C_ATTACK','Atak');
		define('C_DEFENCE','Obrona');
		define('C_SPEED','Szybkość');
		define('C_HP','Punkty życia');
		define('C_GENDER','Płeć');
		define('GOLD_PIECES','sztuk złota');
		define('SELL_TEXT','Wystaw chowańca na sprzedaż:');
		define('GENDER_MALE','Samiec');
		define('GENDER_FEMALE','Samica');
		define('A_SELL','sprzedaj');
		if (isset($_GET['buy'])) {
			define('NOT_FOR_SALE','Ten chowaniec nie jest na sprzedaż.');
			define('NO_MONEY','Nie stać Cię na zakup tego chowańca. Wróć gdy zdobędziesz więcej pieniędzy.');
			define('YOU_BOUGHT','Kupiłeś chowańca. Został on przetransportowany na Twoją polanę.');
			define('BOUGHT','kupił chowańca');
			define('YOU_GOT','Dostałeś');
			define('TO_BANK','do banku.');
			define('IS_YOURS','Gratuluję! Kupiłeś chowańca od samego siebie. Wpłaciłeś już pieniądze na swoje konto bankowe, a Twój chowaniec został przetransportowany z Twojej polany na Twoją polanę. I czego Ci oczy wyłażą? Myślisz, że nie mam nic lepszego do roboty? Zajmij się czymś pożytecznym, a nie porządnym ludziom głowę zawracasz. I to już! Bo psem poszczuję.');
			}
		}
	else if ($_GET['view'] == 'arena') {
		define('ARENA_INFO','Opis Areny chowańców.');
		define('ARENA_Z','Arena Zwykła');
		define('ARENA_M','Arena Magiczna');
		define('ARENA_Z_INFO','Zwykła arena....');
		define('ARENA_M_INFO','Arena Magiczna.... ');
		define('NO_ENERGY','Nie masz wystarczająco energii, żeby walczyć (każda walka kosztuje Ciebie 0.5 energii).');
		if (isset($_GET['arena'])) {
			define('WRONG_ARENA','Jakiej areny szukasz?');
			define('NO_ACTIVE_CORE','Nie masz chowańców zapisanych na tę arenę.');
			define('NO_OPPONENTS','Nie ma żadnych przeciwników na tej arenie.');
			define('CHOOSE_YOUR','Wybierz swojego chowańca do walki:');
			define('CHOOSE_OPPONENT','Wybierz przeciwnika:');
			define('DO_FIGHT','Walcz');
			}
		if (isset($_GET['action']) && $_GET['action'] == 'fight') {
			define('NO_CORE','Nie masz takiego chowańca.');
			define('NO_OPPONENT','Nie ma takiego przeciwnika.');
			define('YOURS_EXHAUSTED','Twój chowaniec jest zbyt zmęczony, by dalej walczyć.');
			define('OPPONENTS_EXHAUSTED','Chowaniec przeciwnika jest zbyt zmęczony, by dalej walczyć.');
			define('NOT_YOURS','To nie Twój chowaniec!');
			define('YOURS_IS_DEAD','Twój chowaniec jest martwy.');
			define('OPPONENTS_IS_DEAD','Wrogi chowaniec jest martwy.');
			define('YOUR_OWN','Twoje chowańce nie mogą walczyć przeciw sobie.');
			define('DIFFERENT_ARENA','Chowańce z innych aren nie mogą walczyć przeciwko sobie.');
			define('VERSUS',':: przeciwko ::');
			define('ATTACKS','atakuje');
			define('AND_HITS','i zadaje');
			define('DAMAGES','obrażeń');
			define('HP_LEFT','zostało');
			define('YOU_WON','Twój chowaniec zwycięża.');
			define('YOU_DRAW','Walka została nierozstrzygnięta.');
			define('YOU_LOST','Twój chowaniec przegrywa.');
			define('YOU_GOT','Zdobywasz');
			define('GOLD_PIECES','sztuk złota');
			define('PLATINUM',' sztuk mithrilu');
			define('CORE_M','Chowaniec');
			define('CORE_B','chowańca');
			define('ATTACKED','zaatakował');
			}
		}
	else if ($_GET['view'] == 'train') {
		define('TRAIN_INFO','Witaj na sali treningowej. Pokaż nam swojego chowańca, a my już dobierzemy dla niego specjalny zestaw ćwiczeń.');
		define('TRAIN_TEXT','Wybierz chowańca do treningu:');
		define('A_TRAIN','trenuj');
		if (isset($_POST['id'])) {
			define('NOT_YOURS','To nie Twój chowaniec!');
			define('WRONG_STATUS','Nie możesz trenować chowańców wystawionych na sprzedaż lub na arenę.');
			define('FOR_ONE_TRAIN','Za każdy udany trening zapłacisz');
			define('PLATINUM','sztuk mithrilu');
			define('ADAMANTIUM','bryłek adamantytu');
			define('CRYSTAL','kawałków kryształu');
			define('ENERGY','energii');
			define('YOU_CAN_TRAIN','Maksymalnie możesz trenować chowańca');
			define('TIMES','razy');
			define('C_ATTACK','Atak');
			define('C_DEFENCE','Obrona');
			define('C_SPEED','Szybkość');
			define('WRONG_STAT','Co Ty właściwie chcesz trenować?');
			define('NO_MINERALS','Nie stać Cię na taki trening.');
			define('NO_ENERGY','Nie masz wystarczająco energii.');
			define('YOU_TRAINED','Wytrenowałeś');
			define('YOU_GOT','Zdobyłeś');
			define('BREEDING','Umiejętności hodowla');
			define('EXPERIENCE','Punktów doświadczenia');
			define('T_ATTACK','siły ataku');
			define('T_DEFENCE','obrony');
			define('T_SPEED','szybkości');
			define('YOU_PAID','Wydałeś');
			}
		}
	else if ($_GET['view'] == 'breed') {
		define('BREED_INFO','Ładny zakątek, co nie? To właśnie tu możesz przyprowadzić swoje chowańce, by spróbować je rozmnożyć. Mamy pełen wybór afrodyzjaków dostosowanych do każdego gatunku. To gdzie są nasi zakochani?');
		define('CROSS_CORE','Skrzyżuj chowańca');
		define('WITH_CORE','z chowańcem');
		define('A_CROSS','skrzyżuj');
		define('NO_MALE_CORE','Nie masz żadnego samca nadającego się do rozrodu');
		define('NO_FEMALE_CORE','Nie masz żadnej samicy nadającej się do rozrodu');
		if (isset($_POST['maleid']) && isset($_POST['femaleid'])) {
			define('NOT_YOURS','To nie Twój chowaniec!');
			define('WRONG_STATUS','Nie możesz skrzyżować chowańców wystawionych na sprzedaż lub na arenę.');
			define('WRONG_GENDER','Natura tego tak nie zaprojektowała');
			define('TOO_YOUNG','Czy ten chowaniec aby nie jest za młody?');
			define('NEED_REST','Spokojnie ogierze, chowańce też muszą odpocząć.');
			define('IS_DEAD','Zwłoki mi tu przynosisz?');
			define('WRONG_SPECIES','Laboratorium genetyczne drugie drzwi na prawo doktorze Frankenstein.');
			define('C_ATTACK','Atak');
			define('C_DEFENCE','Obrona');
			define('C_SPEED','Szybkość');
			define('C_HP','Punkty życia');
			define('NO_MINERALS','Nie stać Cię na to. Wróć gdy zdobędziesz minerały.');
			define('YOU_PAY','Będzie cię to kosztować');
			define('YOU_NEED','Potrzebujesz na to');
			define('PLATINUM','sztuk mithrilu');
			define('ADAMANTIUM','bryłek adamantytu');
			define('CRYSTAL','kawałków kryształu');
			define('METEOR','kawałków meteorytu');
			define('ENERGY','energii');
			define('NO_ENERGY','Nie masz wystarczajaco energii');
			define('SUCCESS','Udało Ci się skrzyżować chowańce');
			define('LOSE','Nie udało Ci się skrzyżować chowańców');
			define('YOU_GOT','Zdobyłeś');
			define('BREEDING','Umiejętności hodowla');
			define('EXPERIENCE','Punktów doświadczenia');
			}
		}
	else if ($_GET['view'] == 'monuments') {
		define('NAME','Chowaniec');
		define('WINS','Zwycięstw');
		define('USER','Właściciel');
		define('NORMAL_TITLE','Arena Zwykła');
		define('MAGIC_TITLE','Arena Magiczna');
		}
	}


?>
