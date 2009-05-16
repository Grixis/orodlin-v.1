{strip}
{$Hofinfo}<br /><br />
{$Message}
{section name=hof loop=$Hero}
  <fieldset style="width: 400px"><legend>{$Hero[hof]}</legend>
    <table width="400" align="center">
    <tr><td width="65%"><b>{$Hname}:</b></td><td width="35%"> {$Hero[hof]}</td></tr>
    <tr><td><b>{$Holdid}:</b></td><td> {$Oldid[hof]}</td></tr>
    <tr><td><b>{$Hid}:</b></td><td> {$Heroid[hof]}</td></tr>
    <tr><td><b>{$Hrace}</b></td><td> {$Herorace[hof]}</td></tr>
    </table>
  </fieldset><br/>
{/section}
Pamiętaj, iż pewnego dnia i twój pomnik może znaleźć się w tym gmachu. A więc przyszli bohaterowie - do dzieła!
{/strip}