<?php
/**
 *   File functions:
 *   Show Best Players in Game
 */

$title = 'Ranking Graczy';
require_once('includes/head.php');

/**
* Get the localization for game
*/
require('languages/'.$player -> lang.'/ranking.php');

if ($player -> location != 'Altara' && $player -> location != 'Ardulith')
{
    error (ERROR);
}

$smarty -> cache_dir = 'cache/';
$smarty -> caching = 2;
$smarty -> cache_lifetime = 3600;   // Ranking will be refreshed after 1 hour from first viewing.

if (!$smarty -> is_cached('ranking.tpl'))
{
    $oldFetchMode = $db -> SetFetchMode(ADODB_FETCH_NUM);
/// TODO: Po wywaleniu pola z bazy danych warto zmienić to "r" w zapytaniu na "ranking". W *.tpl-ce zmieniłem komórki tabeli <td> nagłówkowe na <th> i wyciepałem większość stylowania. Aranwe tam miał ze Sleszem coś fajnego dla tabel przygotować. Wiem, że chudo wygląda, ale może to puści na 2 kolumny albo coś... Widzę że muszę jakiś tutorialowy artykuł przygotować z GetAll i FETCH_NUM, bo z użyciem cieńko :)
    $arrStats = $db -> GetAll('SELECT `id`, `user`, `level`, (`agility` + `strength` + `inteli` + `wisdom` + `szyb` + `wytrz` + `alchemia`)/20 + (`mining` + `lumberjack`)/10 + (`jeweller` + `fletcher` + `ability` + `herbalist` + `unik` + `magia` + `shoot` + `atak`)/5 + (`level` + `breeding`) * 2  + `leadership` AS `r` FROM `players` ORDER BY `r` DESC LIMIT 50');

    $smarty -> assign_by_ref('arrStats', $arrStats);
    $db -> SetFetchMode($oldFetchMode);
    $smarty -> display('ranking.tpl');
    unset($arrStats);
}
else
{
    $smarty -> display('ranking.tpl');
}

$smarty -> caching = 0;
require_once('includes/foot.php');
?>
