<?php
require_once('includes/sessions.php');
$strText = $_SESSION['imagecode'];
header ('Content-type: image/jpeg');

$imgImage = ImageCreate(440, 100);
$black = ImageColorAllocate($imgImage, 0, 0, 0);
ImageFill($imgImage, 0, 0, $black);

for ($i=0;$i<20;$i++)
{
	imageLine(
		$imgImage,
		rand(1,439),
		rand(1,99),
		rand(1,439),
		rand(1,99),
		ImageColorAllocate($imgImage, rand(50,100), rand(50,100), rand(50,100)));
}

$x = rand(20,220);
$y = rand(25,75);
$rot = rand(-40,40);
for ($i=0;$i<10;$i++)
{
$t = ($i-1 >=0 && ($strText[$i-1] == 'm' || $strText[$i-1] == 'w')) ? 4 : 0;
	imageTTFText(
		$imgImage,
		rand(23,24),
		$rot + rand(-10,10),
		$x+$i*20+$t,
		rand($y,$y+15),
		ImageColorAllocate($imgImage, rand(120,255), rand(120,255), rand(120,255)),
		'./fonts/Vera.ttf',
		$strText[$i]);
}

ImageJpeg($imgImage);

ImageDestroy($imgImage);
?>