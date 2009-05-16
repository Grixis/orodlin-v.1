<?php
require_once('adodb/adodb.inc.php');
$db = NewADOConnection('mysqlt');
//podajemy namiary na bazê danych: adres, user, haslo, nazwa
$db -> Connect("adres_bazy", "user_gry", "haslo", "nazwa_bazy_danych");
$db -> Execute("SET NAMES utf8");
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$ADODB_CACHE_DIR = 'cache';
$gamename= "Nazwa_gry";
$gamemail = "mejl_gry";
//tutaj musimy konicznie podaæ adres pod którym dostêpna bêdzie gra
//bez pocz¹tkowego http:// i koñcowego /
//zazwyczaj bêdzie to "localhost" lub "localhost/orodlin"
$gameadress = "sciezka do plikow";
$adminname = "";
$adminmail = "";
$city1 = "";
$city1a = "";
$city1b = "";
$city2 = "";
$pllimit = 50;
?>