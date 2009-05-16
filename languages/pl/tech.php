<?php

define ('WELCOME', 'Witaj w panelu administratora technicznego. Co chcesz zrobić?');
define ('BUGTRACK', 'Bugtrack');
define ('BUGREPORT', 'Zgłoszone błędy');
define ('NOT_TECH', 'Nie jesteś adminem technicznym!');
define ('A_MAKE', 'Zrób');
define ('A_BACK', 'Wróć');
define ('ERROR', 'Zapomnij o tym!');

if (isset($_GET['view']) && $_GET['view'] == 'bugreport')
{
	define('BUG_REPAIR', 'Zajmij się tym błędem');
	define('BUG_LEAVE', 'Porzuć to zadanie');
	define('BUG_PROGRAMMER', 'Ten błąd stara się naprawić:');
	define('BUG_TAKEN', 'Zajęłeś się tym błędem');
	define('BUG_LEFT', 'Zrezygnowałeś z naprawy tego błędu');
    define('BUG_TYPE', 'Rodzaj błędu');
    define('BUG_TEXT', 'Literówka');
    define('BUG_CODE', 'Błąd w grze');
    define('BUG_LOC', 'Lokacja');
    define('BUG_NAME', 'Tytuł zgłoszenia');
    define('BUG_ID', 'Numer');
    define('BUG_REPORTER', 'Zgłaszający');
    define('BUG_DESC', 'Opis');
    define('BUG_ACTIONS', 'Ustaw jako');
	$arrOptions = array('Naprawiony', 'To nie jest błąd', 'U mnie działa', 'Wymaga więcej informacji', 'Duplikat');
    define('YOUR_BUG', 'Twoje zgłoszenie błędu: <b>');
    define('B_ID', '</b> ID: <b>');
    define('NOT_BUG3', '</b> nie jest błędem.');
    define('HAS_FIXED', '</b> zostało naprawione.');
    define('MORE_INFO2', '</b> wymaga więcej informacji, aby mogło zostać naprawione.');
    define('WORK_FOR_ME2', '</b> zostało odrzucone. <b>Przyczyna:</b> u mnie działa poprawnie.');
    define('BUG_DOUBLE2', '</b> zostało odrzucone. <b>Przyczyna:</b> wcześniej ktoś zgłosił już ten błąd.');
    define('NOT_BUG2', 'Oznaczyłeś ten błąd jako nieprawidłowy.');
    define('HAS_FIXED2', 'Oznaczyłeś ten błąd jako naprawiony.');
    define('WORK_FOR_ME3', 'Oznaczyłeś ten błąd jako pomyłkę (u mnie działa).');
    define('MORE_INFO3', 'Oznaczyłeś ten błąd jako nienaprawialny (wymaga więcej informacji).');
    define('BUG_DOUBLE3', 'Oznaczyłeś ten błąd jako duplikat innego błędu.');
    define('T_BUG', 'Naprawiony błąd (');
    define('REPORTED_BY', ' zgłoszony przez ID: ');
    define('BUG_COMMENT', 'Komentarz');
	define('EMPTY_COMMENT', 'Nie podałeś komentarza. Na pewno oznaczyć?');
}

?>
