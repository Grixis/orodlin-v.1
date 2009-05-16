<?php
$small = 'a-zążźćęśńłó';
$big = 'A-ZĄŻŹĆĘŚŃŁÓ';


function ValidNick ($n) {
	global $big;
	global $small;

	$word = "[$big][$small]*'?[$small]+";

	$match = "/^$word( $word)*\$/u";
	return preg_match ($match, $n);
}
?>
