<?php
/**
 *   File functions:
 *   Player card
 *
 *   @name                 : card.php
 *   @copyright            : (C) 207 Orodlin Team based on Gamers-Fusion ver 2.5 and Vallheru Engine
 *   @author               : Marek Stasiak (l3thal2@gmail.com)
 *   @version              : 
 *   @since                : 
 *
//
// $Id: index.php 835 2006-11-22 17:40:22Z thindil $
*/
$title1 = "Kreator postaci"; //cos dziwnego z tym jest
$title = "Kreator postaci"; //raz title1 raz title
require_once ('includes/getlang.php');
GetLang ();
GetLoc ('card');
require_once('includes/head.php');

if (!isset($_GET['action']))
{
	$_GET['action'] = '';
}
if (!isset($_GET['select']))
{
	$_GET['select'] = '';
}
if (!isset($_GET['step']))
{
	$_GET['step'] = '';
}
if (!isset($_GET['step2']))
{
	$_GET['step2'] = '';
}

$Query = $db -> Execute('SELECT `changed_loc` FROM `players` WHERE `id`='.$player -> id);
$Changed_loc = $Query -> fields['changed_loc'];

if ($_GET['action'] == 'gender')
{
	if ($player -> gender != '')
	{
		error(C_BADCHOICE);
	}
	if (isset($_POST['gender']) && ($_POST['gender'] == 'M' || $_POST['gender'] == 'F'))
	{
        	$db -> Execute("UPDATE `players` SET `gender`='".$_POST['gender']."' WHERE `id`=".$player -> id);
		error(C_GSELECTED);
	}
}

if ($_GET['action'] == 'race')
{
	if ($player -> race != '')
	{
		error(C_BADCHOICE);
	}
	if ($player -> gender == '')
	{
		error(C_NOGENDER);
	}
	$arrRaceNames = array('human', 'elf', 'dwarf', 'hobbit', 'reptilion', 'gnome');
	$arrRaces = array('Człowiek', 'Elf', 'Krasnolud', 'Hobbit', 'Jaszczuroczłek', 'Gnom');
	$smarty -> assign_by_ref('RaceNames', $arrRaceNames);
	$smarty -> assign_by_ref('Races', $arrRaces);
	if (isset($_GET['select']) && $_GET['select'] != '' && isset ($_GET['step']) && $_GET['step'] == 'mark') 
	{
		$intKey = array_search($_GET['select'], $arrRaceNames);
                $avQuery = $db -> Execute('SELECT `avatar` FROM `players` WHERE `id`='.$player -> id);
		$avatar = $avQuery -> fields['avatar'];
		if (ereg('default', $avatar))
		{
			$gnd = strtolower($player -> gender);
			$newavatar = "default/".$arrRaceNames[$intKey]."_".$gnd.".jpg";
       			$db -> Execute('UPDATE `players` SET `rasa`=\''.$arrRaces[$intKey].'\', `avatar`=\''.$newavatar.'\' WHERE `id`='.$player -> id);
		}
		else
		{
       		$db -> Execute('UPDATE `players` SET `rasa`=\''.$arrRaces[$intKey].'\' WHERE `id`='.$player -> id);
		}
		error (C_RSELECTED);	
    	}
}

if ($_GET['action'] == 'class')
{
	if ($player -> clas != '')
	{
		error(C_BADCHOICE);
	}
	if ($player -> race == '')
	{
		error(C_NORACE);
	}
	$arrClassNames = array('warrior', 'mage', 'barbarian', 'thief', 'artisan');
	$arrClasses = array('Wojownik', 'Mag', 'Barbarzyńca', 'Złodziej', 'Rzemieślnik');
    	$smarty -> assign_by_ref('ClassNames', $arrClassNames);
    	$smarty -> assign_by_ref('Classes', $arrClasses);
	if (isset($_GET['select']) && $_GET['select'] != '' && isset ($_GET['step']) && $_GET['step'] == 'mark') 
    	{
		$intKey = array_search($_GET['select'], $arrClassNames);
		$intEnergy = $arrClassNames[$intKey] == 'artisan' ? 10 : 7;
		$db -> Execute('UPDATE `players` SET `max_energy`='.$intEnergy.', `klasa`=\''.$arrClasses[$intKey].'\' WHERE `id`='.$player -> id);
		if ($player -> deity != '')
		{
			error (C_CS);
		}
		else
		{
			error (C_CSELECTED);
		}
	}
}

if ($_GET['action'] == 'deity')
{
	if ($_GET['step'] == '' && $player -> deity != '')
	{
		error(C_DCHANGE);
	}
	$arrOptionName = array('tartus', 'anea', 'meltis', 'dintel', 'yala', 'unrod', 'luzubal', 'gulgard', 'artis');
	$arrGodNames = array('Tartus', 'Anea', 'Meltis', 'Dintel', 'Yala', 'Unrod', 'Luzubal', 'Gulgard', 'Artis');
	$arrGodNamesPL = array('Tartusa', 'Aneę', 'Meltis', 'Dintel', 'Yalę', 'Unroda', 'Luzubala', 'Gulgarda', 'Artis');
	if (isset($_GET['step']) && $_GET['step'] == 'change')
	{
		if ($player -> deity == '')
		{
			error(C_BADCHOICE);
		}
		$oldFetchMode = $db -> SetFetchMode(ADODB_FETCH_NUM);
		$arrPoints = $db -> GetRow('SELECT `changedeity` FROM `players` WHERE `id`='.$player -> id);
		$db -> SetFetchMode($oldFetchMode);
		$intCost = 100 * $arrPoints[0];
		if ($_GET['step2'] == '')
		{
			$smarty -> assign(array("Ccost" => $intCost));
		}
		else
		{
        		$intKey = array_search($player -> deity, $arrGodNames);
        		$db -> Execute('UPDATE `players` SET `deity`= null, `pw`=`pw`-'.$intCost.' WHERE `id`='.$player -> id);
        		$smarty -> assign('Pdeity', $arrGodNamesPL[$intKey]);
		}
	}
	$smarty -> assign_by_ref('GodName', $arrGodNames);
	$smarty -> assign_by_ref('GodOption', $arrOptionName);
	if (isset($_GET['select']) && $_GET['select'] != '' && isset ($_GET['step']) && $_GET['step'] == 'mark')
	{
		if ($player -> deity != '')
		{
			error(C_BADCHOICE);
		}
		$intKey = array_search($_GET['select'], $arrOptionName);
        	$db -> Execute('UPDATE `players` SET `deity`=\''.$arrGodNames[$intKey].'\', `changedeity`=`changedeity`+1 WHERE `id`='.$player -> id);
        	$smarty -> assign_by_ref('God', $arrGodNamesPL[$intKey]);
		if ($Changed_loc == "Y")
		{
			error (C_DS);
		}
		else
		{
			error (C_DSELECTED);
		}
	}
}

if ($_GET['action'] == 'place')
{
	if ($Changed_loc == "Y")
	{
		error(C_BADCHOICE);
	}
	else
	{
		if (isset($_GET['select']) && $_GET['select'] != '')
		{
			$Places = array('meredith', 'agarakar');
			$PlacesNames = array('Meredith', 'Agarakar');
			$PlacesNamesDb = array('Altara', 'Ardulith');
			$smarty -> assign(array('Places' => $Places,
						'PlacesNames' => $PlacesNames));

			if (isset ($_GET['step']) && $_GET['step'] == 'mark')
			{
				$intKey = array_search($_GET['select'], $Places);
        			$db -> Execute('UPDATE `players` SET `miejsce`=\''.$PlacesNamesDb[$intKey].'\', `changed_loc`="Y" WHERE `id`='.$player -> id);
				error (C_PSELECTED);
			}
		}
		if ($player -> location == 'Altara')
		{
			$smarty -> assign(array('City1' => '<li><a href="card.php?action=place&amp;select='.strtolower($city1).'">'.$city1.'</a> '.C_YOUTHERE.'</li>',
						'City2' => '<li><a href="card.php?action=place&amp;select='.strtolower($city2).'">'.$city2.'</a></li>'));
		}
		if ($player -> location == 'Ardulith')
		{
			$smarty -> assign(array('City1' => '<li><a href="card.php?action=place&amp;select='.strtolower($city1).'">'.$city1.'</a></li>',
						'City2' => '<li><a href="card.php?action=place&amp;select='.strtolower($city2).'">'.$city2.'</a> '.C_YOUTHERE.'</li>'));
		}
		if ($player -> location != 'Ardulith' && $player -> location != 'Altara')
		{
			$smarty -> assign(array('City1' => '<li><a href="card.php?action=place&amp;select='.strtolower($city1).'">'.$city1.'</a></li>',
						'City2' => '<li><a href="card.php?action=place&amp;select='.strtolower($city2).'">'.$city2.'</a></li>'));
		}
	}
}

$smarty -> assign(array('Action' => $_GET['action'],
			'Select' => $_GET['select'],
			'Step' => $_GET['step'],
			'Step2' => $_GET['step2'],
			'Gender' => $player -> gender,
			'Race' => $player -> race,
			'Class' => $player -> clas,
			'Deity' => $player -> deity,
			'Changed_loc' => $Changed_loc));

$smarty -> display ('card.tpl');
require_once("includes/foot.php");
$db -> Close();
?>
