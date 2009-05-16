<?php
/*
 *  @author: Jakub Stasiak <kuba.stasiak at gmail.com>
 */

function u2i ($str)
{
//	return iconv ('utf-8', 'iso8859-2', $str);
	return $str;
}

/*
function g ($str) // taki wrapper, później się da die albo error w przypadku stringa niezgodnego z konwencją
{
	return addslashes ($str);
}
*/

function Usage ()
{
	global $DefaultFont;
	global $DefaultSize;
	$font = $DefaultFont;
	$size = $DefaultSize;

	$img = ImageCreate (500, 60);

	$bg		=	ImageColorAllocate ($img, 255, 255, 255);
	$red	=	ImageColorAllocate ($img, 255, 0, 0);

	ImageTTFText ($img, $size*1.2, 0, 5, 10+$size*1.2, $red, $font, u2i (SIGN_INVALID_PARAMS_NOTIFY));
	ImageTTFText ($img, $size*1.2, 0, 5, 15 + 2*$size*1.2, $red, $font, u2i (SIGN_VALID_PARAMS_EXAMPLE));
	ImageTTFText ($img, $size*1.2, 0, 5, 20 + 3*$size*1.2, $red, $font, u2i (STH));

	ImageJPEG ($img, '', 60);
	exit;
}

require_once ('includes/config.php');

require_once ('includes/getlang.php');
GetLang ();
GetLoc ('sign');

$DefaultSize = 9;
$DefaultFont = './fonts/Vera.ttf';


header ('Content-type: image/jpeg');

if (
		!isset ($_GET['id']) or !is_numeric ($_GET['id'])
		or $_GET['id'] < 1
		or !isset ($_GET['type']) or !is_numeric ($_GET['type'])
		or $_GET['type'] < 1 or $_GET['type'] > 3
	) {
	Usage ();
}


if(time() - @filectime('images/sigs/cache/'.$_GET['id'].'-'.$_GET['type'].'.jpg') > 43200)
	{
	$id = $_GET['id'];
	$type = $_GET['type'];
	
	$res = $db->getRow ('SELECT p.user, p.level, p.rasa, p.klasa, p.age, t.type FROM players p LEFT JOIN team t ON t.gameid=p.id WHERE p.id=\''.$id.'\'');

	if (empty($res['level'])) {
		Usage ();
	}
	if(!empty($res['type']))
	{
		$stype = $type.$res['type'];	
	}
	else
	{
		$stype = $type;
	}

	$nick = $res['user'];
	$class = $res['klasa'];
	$race = $res['rasa'];
	$age = $res['age'];
	$level = $res['level'];
	
	$nickstr = $nick.' ('.$id.')';
	$racestr = $race.' '.$class;
	$levelstr = LEVEL.' '.$level.'. '.AGE.' '.$age.'.';
	$smallstr = $nick.' ('.$id.')';
	
	
	$basepath = 'images/sigs';
	$img = ImageCreateFromJpeg($basepath.'/'.$stype.'.jpg');
	$white = ImageColorAllocate ($img, 255, 255, 255);
	$black = ImageColorAllocate ($img, 0, 0, 0);
	
	
	
	switch ($type) {
		//TODO: jeszcze jakiś mały userbarek by pasowało zrobić, ale to w wolnej chwili
		/*case '4':
			ImageString ($img, $size, 5, 5, u2i ($smallstr), $white);
			break; */
		case '1':
		case '2':
		case '3':
			$font = $DefaultFont;
			$size = $DefaultSize;
			ImageTTFText ($img, $size, 0, 11, 11+$size, $white, $font, u2i ($nickstr));
			ImageTTFText ($img, $size, 0, 11, 45+$size, $white, $font, u2i ($racestr));
			ImageTTFText ($img, $size, 0, 11, 79+$size, $white, $font, u2i ($levelstr));
	}
	
	ImageJPEG ($img, 'images/sigs/cache/'.$_GET['id'].'-'.$_GET['type'].'.jpg', 90);
}
virtual('images/sigs/cache/'.$_GET['id'].'-'.$_GET['type'].'.jpg');
?>
