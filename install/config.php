<?php
require_once('adodb/adodb.inc.php');
$db = NewADOConnection('mysqlt');
//podajemy namiary na baz� danych: adres, user, haslo, nazwa
$db -> Connect("adres_bazy", "user_gry", "haslo", "nazwa_bazy_danych");
$db -> Execute("SET NAMES utf8");
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$ADODB_CACHE_DIR = 'cache';
$gamename= "Nazwa_gry";
$gamemail = "mejl_gry";
//tutaj musimy konicznie poda� adres pod kt�rym dost�pna b�dzie gra
//bez pocz�tkowego http:// i ko�cowego /
//zazwyczaj b�dzie to "localhost" lub "localhost/orodlin"
$gameadress = "sciezka do plikow";
$adminname = "";
$adminmail = "";
$city1 = "";
$city1a = "";
$city1b = "";
$city2 = "";
$pllimit = 50;
?>