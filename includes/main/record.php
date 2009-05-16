<?php

/**
* Do we have new record?
*/
include("counter/record.php");
$smarty -> assign(array("numRecord" => $record,
					"When1" => $when1,
					"When2" => $when2));

?>
