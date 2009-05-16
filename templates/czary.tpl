{strip}
<u>{$Usedspells}</u>:<br />
{$Battle}
{$Defence}

<br /><u>{$Spellbook}</u>:<br />
<b>-{$Bspells}:</b><ul>
{section name=spell1 loop=$Bname}
    <li> {$Bname[spell1]} (+{$Bpower[spell1]} x {$Bdamage}) [ <a href="czary.php?naucz={$Bid[spell1]}">{$Usethis}</a> ]</li>
{/section}
</ul>
<br /><b>-{$Dspells}:</b><ul>
{section name=spell2 loop=$Dname}
    <li> {$Dname[spell2]} (+{$Dpower[spell2]} x {$Ddefense}) [ <a href="czary.php?naucz={$Did[spell2]}">{$Usethis}</a> ]</li>
{/section}
</ul>
<br /><b>-{$Espells}:</b><ul>
{section name=spell3 loop=$Uname}
    <li> {$Uname[spell3]} ({$Ueffect[spell3]}) [ <a href="czary.php?cast={$Uid[spell3]}">{$Castthis}</a> ]</li>
{/section}
</ul>
{if $Cast != ""}
    <form method="post" action="czary.php?cast={$Cast}&amp;step=items">
    <input type="submit" value="{$Cast2}" /> {$Spell} {$Spellname} {$Ona} <select name="item">
    {section name=spell4 loop=$Itemname}
        <option value="{$Itemid[spell4]}">{$Itemname[spell4]} ({$Iamount}: {$Itemamount[spell4]})</option>
    {/section}
    </select>
    <input type="hidden" name="spell" value="{$Spellname}" /><br />
	</form>
    {$Message}
{/if}

{if $Learn > "0"}
    {$Youuse} {$Name}. (<a href="czary.php">{$Arefresh}</a>)
{/if}

{if $Deaktiv > "0"}
    <a href="czary.php">({$Arefresh})</a>
{/if}

{/strip}