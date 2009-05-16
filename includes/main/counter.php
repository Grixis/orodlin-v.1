<?php


/*licznik*/
    include("counter/licznik.php");
    $akt = $ilosc;
    include("counter/d_licznik.php");
    $akt2 = $ilosc;
/*kuniec licznika*/

$smarty->assign(array('Logcount' => $akt,
	'Logcountday' => $akt2));

?>
