{strip}
<b><u>{$Trest}</u></b><br /> <br />
{$Restinfo} <b>{$Manainfo}</b>{$Restinfo1}<b>{$Manarest}</b> {$Restinfo2}<br /><br />
<form method="post" action="rest.php?akcja=all">
{$Iwant} <input type="text" size="3" name="amount" value="{$Restcount}"/> {$Rmana}. <input type="submit" value="{$Arest}" />
</form><br /> <br />
<a href="stats.php">{$Aback}</a>
{/strip}