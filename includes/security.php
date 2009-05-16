<?php
/** Function to convert player input to positive int32 value (1 to 2147483647).
* Player can use: "., " (dot, comma, space) as a thousands separators for his convenience.
* Player can append 'k' and 'K' to multiply value by 1000, up to 3 letters.
* Test case and examples - run test() function.
* Usage: amount of gold or resources, used energy, player's id and his name written after it...
*/
function uint32(&$mixVal)
{
    // Drop separators.
    $mixVal = str_replace(array(".", ",", " "), "",$mixVal);
    // Count up to 3 'k' and 'K' letters at the end.
    $strSub = substr($mixVal, -3);
    $intNoOfKLetters = substr_count($strSub, 'k') + substr_count($strSub, 'K');
    // Convert to int
    settype($mixVal, 'integer');
    $mixVal *= pow(1000, $intNoOfKLetters);
    if($mixVal < 0)
        $mixVal = -$mixVal;
    if($mixVal > 2147483647)
        $mixVal = 2147483647;
    return $mixVal;
}

function uint32_test()
{
    error_reporting(E_ALL | E_STRICT);
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
}

/**
* Pure number. No separators etc.
* Usage: Things passed silently in forms and URIs. ID numbers: equipment, familiars, outposts, clans, market offers...
*/
function strictInt(&$mixVal)
{
    settype($mixVal, 'integer');
    if($mixVal < 0)
        $mixVal = -$mixVal;
    if($mixVal > 2147483647)
        $mixVal = 2147483647;
    return $mixVal;
}

/**
* Prepare string to be displayed as a valid XHTML. Strips tags,sets entities (& -> &amp; etc). Doesn't contain XML parser!
* Usage: display player names, descriptions etc. text that weren't mean to contain HTML formatting.
*/
function outString(&$strText)
{   // ENT_QUOTES: Convert ' to &apos; and " to &quot;
    $strText = htmlspecialchars(strip_tags($strText), ENT_QUOTES, 'UTF-8');
}

/**
* Prepare string to be added to database. Strips tags,sets entities (& -> &amp; etc), handles single and double quotes. Doesn't contain XML parser!
*/
function inString(&$strText)
{
    global $db;
    $strText = $db -> qstr(htmlspecialchars(strip_tags($strText), ENT_QUOTES, 'UTF-8'), get_magic_quotes_gpc());
}

/**
* Prepare input string to be used in SQL WHERE ... LIKE statement.
* Usage: search players, familiars by name, maybe forum permissions?.
*/
function sqlLikeString(&$strText)
{
    global $db;
    $strText = $db -> qstr(str_replace("*","%", strip_tags($strText)), get_magic_quotes_gpc());
    if ($strText == '\'\'')
        $strText = '';
}
?>
