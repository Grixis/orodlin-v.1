<?php
error_reporting(E_ALL | E_STRICT);

require_once('includes/security.php');
print '<h1>Test zabezpieczen przed "hakierami" + ulatwienia</h1><ul><li>Akceptuje k i K jako mnozniki tysieczne</li><li>Ignoruje spacje, kropki, przecinki (mozna wstawiac zeby wygodniej pisac np "123 000")</li><li>Maksimum to 2 miliardy z hakiem: 2<sup>31</sup>-1 (tyle ile miesci np sakiewka)</li><li>Jesli pojawia się z tyłu coś prócz liczb, to "K" powinny konczyc liczbe, nie liczac spacji. Jak tam bedzie cos wiecej, moze nie dzialac zgodnie z oczekiwaniami</ul>';

$arrPass = array( '', '0', ' 1 ', '1K', ' 123k k   ', '2kkk', '5kkkkk', '1111111111111111111111111111111111111111111', '11 12 333', '12 3.456,78', '-7', '-999999999999999999999');
$arrFail = array( '3.14abc', '1 (&&Dellas&&)', '1k 17', 'abcd','1 kk (syf jakis) k');
$arrReason = array( 'Przeczyta 314 i sypnie na nie-cyfrze', 'To samo, zignoruje', 'Po skasowaniu spacji dostajemy 1k17. A poniewasz szuka "k" na trzech ostatnich pozycjach, to znajdzie i przemnozy przez tysiac', 'Zupelnie brak liczb','liczy "k" od konca, wiec te z przodu sa ignorowane i tysiac, nie milion wyjdzie');

print '<h2>Normalniejsze zastosowania:</h2>';
for($i = 0, $max = count($arrPass); $i < $max; $i++)
    print '\''.$arrPass[$i].'\' -> '.uint32($arrPass[$i]).'<br/>';

print '<h2>Dziwne, kombinowane:</h2>';
for($i = 0, $max = count($arrFail); $i < $max; $i++)
    print '\''.$arrFail[$i].'\' -> '.uint32($arrFail[$i]).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>'.$arrReason[$i].'</i><br/>';
?>