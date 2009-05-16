<?php
function LastRegistered()
{
// Tu był static. To nie działa tak jak myslisz. Zmienna zosaje statyczna, spoko... ale tak długo póki PHP działa na danym skrypcie, tzn. do momentu wyświetlenia foot.tpl. Naciąłem się na to kiedyś przy "keszowaniu" posągów.
// Zamiana tabulatora na 4 spacje!
    global $db;
    $oldMode = $db -> SetFetchMode(ADODB_FETCH_ASSOC);  // Tymczasowo.
    $q = $db->Execute ('SELECT `id`, `user` FROM `players` ORDER BY `id` DESC LIMIT 1');    // Gdzie `? Dodałem
    $last['id'] = $q->fields['id'];
    $last['name'] = $q->fields['user'];
    $q->Close();    // Close nie close
    $db -> SetFetchMode($oldMode);  // Tymczas. Na wszelki wypadek bo pewnie
    return $last;
}

// Po mojemu, jesli to juz musi byc funkcja. Zakłada "feczmod" ADODB_FETCH_NUM. Zastosowane w includes/foot.php
function eyeLastRegistered()
{
    global $db;
    return $db->GetRow('SELECT `id`, `user` FROM `players` ORDER BY `id` DESC LIMIT 1');
}

?>
