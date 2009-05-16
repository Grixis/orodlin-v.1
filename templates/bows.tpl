{strip}
{if $Buy == 0 && $Step == ""}
    {if $Location == "Altara"}
        <br />{$Shopinfo} {$Archername} {$Shopinfo2}<br /><br />
    {/if}
    {if $Location == "Ardulith"}
        <br />{$Shopinfo}<br /><br />
    {/if}
    {if $Arrows > 0}
        {if $Step == ""}
            <form method="post" action="bows.php?arrows={$Arrowsid}&amp;step=buy">
                <input type="submit" value="{$Abuy}" /> <input type="text" name="arrows1" size="5" value="0" /> {$Tarrows} <b>{$Arrowsname}</b> {$Fora} <b>{$Arrowscost}</b> {$Tamount} <input type="text" name="arrows2" size="5" value="0" /> {$Tamount2} <b>{$Arrowsname}</b> {$Fora} <b>{$Arrowscost2}</b> {$Tamount3}
            </form>
        {/if}
    {/if}   
    <table width="100%">
    <tr>
    <td width="30%"><b><u>{$Iname}</u></b></td>
    <td width="16%"><b><u>{$Iefect}</u></b></td>
    <td width="16%"><b><u>{$Ispeed}</u></b></td>
    <td width="10%"><b><u>{$Idur}</u></b></td>
    <td width="12%"><b><u>{$Icost}</u></b></td>
    <td width="8%"><b><u>{$Ilevel}</u></b></td>
    <td width="8%"><b><u>{$Ioption}</u></b></td>
    </tr>
    {section name=item loop=$Level}
        <tr>
        <td>{$Name[item]}</td>
        <td>+{$Power[item]} Atak</td>
        <td>+{$Speed[item]}</td>
        <td>{$Durability[item]}</td>
        <td>{$Cost[item]}</td>
        <td>{$Level[item]}</td>
        <td>- <a href="bows.php?{$Tlink[item]}{$Itemid[item]}">{$Abuy}</a>{if $Crime > "0"}<br /><a href="bows.php?steal={$Itemid[item]}">{$Asteal}</a>{/if}</td>
        </tr>
    {/section}
    </table>
{/if}

{if $Buy > 0 || $Step == "buy"}
    <br />{$Youbuy} <b>{$Cost}</b> {$Goldcoins} {$Tamount4} <b>{$Name}</b> {$With} <b>+{$Power}</b> {$Damage}
	<br/><a href="bows.php">{$smarty.const.BACK}</a>
{/if}
{/strip}
