<?php
require_once ('last.php');
$last = LastRegistered ();

$smarty->assign (array ('LastID' => $last['id'],
                        'LastName' => $last['name']));
?>
