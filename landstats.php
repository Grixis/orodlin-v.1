<?php
/**
 *   File functions:
 *   Land stats
 */
//
// $Id$

$title = 'Statystyki ogólne krainy';
$title1 = $title;
require('includes/head.php');

/**
* Set the language
*/
require('languages/'.$player -> lang.'/landstats.php');

if ($player -> location != 'Altara' && $player -> location != 'Ardulith')
{
    error (ERROR);
}

$smarty -> cache_dir = 'cache/';
$smarty -> caching = 2;
$smarty -> cache_lifetime = 3600;

function countPercentage (&$arrCountPcts, &$arrPlayersAmount)
{
	for ($i = 0, $k = count($arrCountPcts); $i < $k; $i++)
	{
		$arrCountPcts[$i][2] = round($arrCountPcts[$i][1] / $arrPlayersAmount[0] * 100, 2);
	}
	return $arrCountPcts;
}

$oldFetchMode = $db -> SetFetchMode(ADODB_FETCH_NUM);
$arrPlayersAmount = $db -> GetRow('SELECT count(id) FROM `players`');
$arrAll[0] = $db -> GetAll('SELECT `gender`, count(*) FROM `players` GROUP BY `gender` ORDER BY count(*) DESC');
$arrAll[1] = $db -> GetAll('SELECT `rasa`, count(*) FROM `players` GROUP BY `rasa` ORDER BY count(*) DESC');
$arrAll[2] = $db -> GetAll('SELECT `klasa`, count(*) FROM `players` GROUP BY `klasa` ORDER BY count(*) DESC');
$arrAll[3] = $db -> GetAll('SELECT `deity`, count(*) FROM `players` GROUP BY `deity` ORDER BY count(*) DESC');

countPercentage($arrAll[0], $arrPlayersAmount);
countPercentage($arrAll[1], $arrPlayersAmount);
countPercentage($arrAll[2], $arrPlayersAmount);
countPercentage($arrAll[3], $arrPlayersAmount);

for ($i = 0; $i < 4; $i++)
{
	if ($arrAll[0][$i][0] == 'M')
	{
		$arrAll[0][$i][0] = 'Mężczyzna';
	}
	elseif ($arrAll[0][$i][0] == 'F')
	{
		$arrAll[0][$i][0] = 'Kobieta';
	}
}

$strPre = 'Brak wybrane';
$arrPost = array('j płci', 'j rasy', 'j klasy', 'go bóstwa');

for ($i = 0; $i < 4; $i++)
{
	for ($k = 0, $l = count($arrAll[$i]); $k < $l; $k++)
	{
		$strNotChosen = $strPre.$arrPost[$i];
		if ($arrAll[$i][$k][0] == '')
		{
			$arrAll[$i][$k][0] = $strNotChosen;
		}
	}
}

$smarty -> assign_by_ref('Desc', $arrDesc);
$smarty -> assign_by_ref('Tables', $arrTableNames);
$smarty -> assign_by_ref('Players', $arrPlayersAmount[0]);
$smarty -> assign_by_ref('Stats', $arrAll);

$db -> SetFetchMode($oldFetchMode);

$smarty -> display('landstats.tpl');
$smarty -> caching = 0;

require('includes/foot.php');
?>
