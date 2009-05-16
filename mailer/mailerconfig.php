<?php
require_once('class.phpmailer.php');
$mail = new PHPMailer();
$mail -> PluginDir = "./mailer/";
$mail -> SetLanguage("pl", "./mailer/language/");
$mail -> CharSet = "utf-8";
$mail -> Sender = $gamemail;
$mail -> IsMail();
$mail -> From = $gamemail;
$mail -> FromName = $gamename;
$mail -> AddAddress($adress);
$mail -> WordWrap = 50;
$mail -> Subject = $subject;
$mail -> Body = $message;

?>