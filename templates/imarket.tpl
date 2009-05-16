{strip}
{if $View == "" && $Remowe == "" && $Buy == ""}
    {$Minfo}.<br />
    <ul>
    <li><a href="{$SCRIPT_NAME}?view=market&amp;lista=id&amp;limit=0">{$Aview}</a></li>
    <li><a href="{$SCRIPT_NAME}?view=szukaj">{$Asearch}</a></li>
    <li><a href="{$SCRIPT_NAME}?view=add">{$Aadd}</a></li>
    <li><a href="{$SCRIPT_NAME}?view=del">{$Adelete}</a></li>
    <li><a href="{$SCRIPT_NAME}?view=all&amp;limit=0">{$Alist}</a></li>
    </ul>
    (<a href="market.php">{$Aback2}</a>)
{/if}

{if $View == "szukaj"}
    <br /><center>{$Sinfo} <a href="imarket.php">{$Aback}</a><br />
    <form method="post" action="imarket.php?view=market&amp;limit=0&amp;lista=name"><table>



    <tr><td colspan="2" align="left">{$Item}:</td><td>  
<select name="szukany">
<option value="W" > Broń
<option value="A" > Zbroja
<option value="L" > Nagolenniki
<option value="H" > Hełm
<option value="S" > Tarcza
<option value="B" > Łuk
<option value="R" > Strzały
<option value="C" > Szata
<option value="T" > Ró&#380;d&#380;ka
</select> 
</td></tr>
    <tr><td colspan="2" align="left">Minimalny poziom: </td><td> <input type="text" name="minlev"/></td> </tr>
    <tr><td colspan="2" align="left">Maksymalny poziom: </td><td> <input type="text" name="maxlev" /> </td> </tr>
    <tr><td colspan="2" align="left">Makasymalna cena: </td><td> <input type="text" name="maxcena" /></td> </tr>
    <tr><td colspan="2" align="left">Min premia do siły: </td><td> <input type="text" name="minsila" /></td> </tr>
    <tr><td colspan="2" align="left">Min premia do szybkości: </td><td> <input type="text" name="minszyb" /></td></tr>
    <tr><td colspan="2" align="left">Min premia do zręczności: </td><td> <input type="text" name="minzr" /></td></tr>
    <tr><td colspan="2" align="left"><input type="submit" value="{$Asearch}" /></td></tr>
    </table></form>
	</center>
{/if}

{if $View == "market"}
    {$Viewinfo} <a href="imarket.php">{$Aback}</a>.<br /><br />
    <table>
    <tr>
    <td width="100"><a href="imarket.php?view=market&amp;lista=name&amp;limit=0" title="{$smarty.const.T_NAME}"><b><u>{$smarty.const.T_NAME}</u></b></a></td>
    <td width="100"><a href="imarket.php?view=market&amp;lista=power&amp;limit=0" title="{$smarty.const.T_POWER}"><b><u>{$smarty.const.T_POWER}</u></b></a></td>
    <td width="100"><a href="imarket.php?view=market&amp;lista=wt&amp;limit=0" title="{$smarty.const.T_DUR}"><b><u>{$smarty.const.T_DUR}</u></b></a></td>
    <td width="100"><a href="imarket.php?view=market&amp;lista=szyb&amp;limit=0" title="{$smarty.const.T_SPEED}"><b><u>{$smarty.const.T_SPEED}</u></b></a></td>
    <td width="100"><a href="imarket.php?view=market&amp;lista=zr&amp;limit=0" title="{$smarty.const.T_AGI}"><b><u>{$smarty.const.T_AGI}</u></b></a></td>
    <td width="50"><a href="imarket.php?view=market&amp;lista=minlev&amp;limit=0" title="{$smarty.const.T_LEVEL}"><b><u>{$smarty.const.T_LEVEL}</u></b></a></td>
    <td width="50"><a href="imarket.php?view=market&amp;lista=amount&amp;limit=0" title="{$smarty.const.T_AMOUNT}"><b><u>{$smarty.const.T_AMOUNT}</u></b></a></td>
    <td width="100"><a href="imarket.php?view=market&amp;lista=cost&amp;limit=0" title="{$smarty.const.T_COST}"><b><u>{$smarty.const.T_COST}</u></b></a></td>
    <td width="50"><a href="imarket.php?view=market&amp;lista=owner&amp;limit=0" title="{$smarty.const.T_SELLER}"><b><u>{$smarty.const.T_SELLER}</u></b></a></td>
    <td width="100"><b><u>{$smarty.const.T_OPTIONS}</u></b></td>
    </tr>
    {section name=item loop=$ArrItems}
        <tr>
        <td>{$ArrItems[item].name}</td>
        <td align="center">{$ArrItems[item].power}</td>
        <td align="center">{$ArrItems[item].wt}/{$ArrItems[item].maxwt}</td>
        <td align="center">{$ArrItems[item].szyb}</td>
        <td align="center">{if $ArrItems[item].zr > 0}-{$ArrItems[item].zr}%{elseif $ArrItems[item].zr == 0}0{else}+{math equation="x * y" x=$ArrItems[item].zr y=-1}{/if}</td>
    <td align="center">{$ArrItems[item].minlev}</td>
        <td align="center">{$ArrItems[item].amount}</td>
        <td>{$ArrItems[item].cost}</td>
        <td><a href="view.php?view={$ArrItems[item].owner}">{$ArrItems[item].user}</a></td>
        <td>{if $ArrItems[item].owner == $Id}<a href="imarket.php?wyc={$ArrItems[item].id}">{$smarty.const.A_DELETE}</a>{else}<a href="imarket.php?buy={$ArrItems[item].id}">{$smarty.const.A_BUY}</a>{/if}</td></tr>
    {/section}
    </table>
    {$Previous}{$Next}
{/if}

{if $View == "add"}
    {$Addinfo} <a href="imarket.php">{$smarty.const.A_BACK}</a>.<br /><br />
    <form method="post" action="imarket.php?view=add&amp;step=add"><table>
    <tr><td colspan="2">
    {$Item}: <select name="przedmiot">
    {section name=item1 loop=$Name}
        <option value="{$Itemid[item1]}">{$Name[item1]} ({$Iamount}: {$Amount[item1]}) ({if $Itempower[item1] != 0} +{$Itempower[item1]} {/if} {if $Itemagi[item1] != 0} {$Iagi} {$Itemagi[item1]} {/if} {if $Itemspeed[item1] != 0} {$Ispe} +{$Itemspeed[item1]} {/if})</option>
    {/section}</select></td></tr>
    <tr><td>{$Iamount2}:</td><td><input type="text" name="amount" /></td></tr>
    <tr><td>{$Icost}:</td><td><input type="text" name="cost" /></td></tr>
    <tr><td colspan="2" align="center"><input type="submit" value="{$Aadd}" /></td></tr>
    </table></form>
{/if}

{if $Buy != ""}
    {$Buyinfo} <a href="imarket.php">{$Aback}</a>.<br /><br />
    <b>{$Item}:</b> {$Name} <br />
    <b>{$Ipower}:</b> {$Power} <br />
    {if $Agi != "0"}
        <b>{$Iagi}:</b> {$Agi} <br />
    {/if}
    {if $Speed != "0"}
        <b>{$Ispeed}:</b> {$Speed} <br />
    {/if}
    {if $Type != "R" && $Type != "S" && $Type != "Z" && $Type != "G"}
        <b>{$Idur}:</b> {$Dur}/{$MaxDur} <br />
    {/if}
    {if $Type == "R"}
        <b>{$Aamount}:</b> {$Dur} <br />
    {/if}
    {if $Type == "G"}
        <b>{$Hamount}:</b> {$Dur} <br />
    {/if}
    <b>{$Oamount}:</b> {$Amount1} <br />
    <b>{$Icost}:</b> {$Cost} <br />
    <b>{$Iseller}:</b> <a href="view.php?view={$Sid}">{$Seller}</a> <br /><br />
    <form method="post" action="imarket.php?buy={$Itemid}&amp;step=buy"><table>
    <tr><td>{$Bamount}:</td><td><input type="text" name="amount" /></td></tr>
    <tr><td colspan="2" align="center"><input type="submit" value="{$Abuy}" /></td></tr>
    </table></form>
{/if}

{if $View == "all"}
    {$Listinfo}<br />
    <table>
    <tr>
    <td><b><u>{$Iname}</u></b></td><td><b><u>{$Iamount}</u></b></td><td align="center"><b><u>{$Iaction}</u></b></td>
    </tr>
    {section name=all loop=$Name}
        <tr>
        <td>{$Name[all]}</td>
        <td align="center">{$Amount[all]}</td>
        <td><form method="post" action="imarket.php?view=market&amp;limit=0&amp;lista=id">
            <input type="hidden" name="szukany" value="{$Name[all]}" />
            <input type="submit" value="{$Ashow}" /></form>
        </td>
        </tr>
    {/section}
    </table>
    {$Tlinks}
{/if}

{$Message}
{/strip}
