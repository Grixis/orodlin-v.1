<?php
require_once('includes/tags.php');
//Tutaj liczenie graczy, godzina i data wejścia itp.

    $time = date("H:i:s");
    $hour = explode(":", $time);
    $newhour = $hour[0] + 0;
    if ($newhour > 23)
    {
        $newhour = $newhour - 24;
    }
    $arrtime = array($newhour, $hour[1], $hour[2]);
    $newtime = implode(":",$arrtime);

    $query = $db -> Execute("SELECT count(*) FROM `players`");
    $nump = $query -> fields['count(*)'];
    $query -> Close();

    $span = (time() - 180);
    $objQuery = $db -> Execute("SELECT count(*) FROM `players` WHERE `lpv`>=".$span);
    $objQuery -> Close();


    $objMetakey = $db -> Execute("SELECT `value` FROM `settings` WHERE `setting`='metakeywords'");
    $objMetadesc = $db -> Execute("SELECT `value` FROM `settings` WHERE `setting`='metadescr'");


	 $smarty->assign(array("Time" => $newtime,
                          "Players" => $nump,
                          "Online" => $intNumo,
                          "Metakeywords" => $objMetakey -> fields['value'],
                          "Metadescription" => $objMetadesc -> fields['value']));
    $objMetakey -> Close();
    $objMetadesc -> Close();

//^^ tymczasowo tutaj, później się jakoś bardziej logicznie nazwie/rozstawi


/*
*Część odpowiedzialna za wyświetlenie graczy online na stronie rejestracji
*/
$span = (time() - 180);
$objQuery = $db -> Execute("SELECT `id`, `user`, `tribe`, `tribe_rank`, `rank` FROM `players` WHERE `lpv`>=".$span." ORDER BY `id` ASC");

$arrRankImages = array('Admin' => 'admin',
                       'Staff' => 'ksiaze',
                       'Sędzia' => 'sedzia',
                       'Ławnik' => 'lawnik',
                       'Prawnik' => 'prawnik',
                       'Bibliotekarz' => 'bibliotekarz',
                       'Rycerz' => 'rycerz',
                       'Dama' => 'dama',
                       'Marszałek Rady' => 'marszalek',
                       'Redaktor' => 'redaktor',
                       'Karczmarka' => 'karczmarz',
                       'Techniczny' => 'techniczny',
			'Hero' => 'hero',
			'Prokurator' => 'prokurator');
$intNumo = 0;
$arrplayers = array();
while (!$objQuery -> EOF) 
{
	$rankIcon = array_key_exists($objQuery -> fields['rank'], $arrRankImages) ? '<img src="images/ranks/'.$arrRankImages[$objQuery -> fields['rank']].'.png" alt="img_ranks" /> ' : '';
	$arrplayers[$intNumo] = $rankIcon.getTaggedPlayerName ($objQuery -> fields['user'], $objQuery -> fields['tribe'], $objQuery -> fields['tribe_rank'])." (".$objQuery -> fields['id'].")<br />";
	$intNumo ++;
	$objQuery -> MoveNext();
}
$smarty -> assign (array (
	//'Players' => $intPlayers, 
    'Online' => $intNumo, 
    'List' => $arrplayers));

?>
