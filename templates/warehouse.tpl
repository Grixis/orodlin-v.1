{strip}
{if empty($smarty.get.action)}
    <p>{$smarty.const.WAREHOUSE_INFO}</p>
    {$Caravaninfo}
    <table cellspacing="10px">
        <tr>
            <th>{$smarty.const.T_MIN}</th>
            <th>{$smarty.const.T_COST}</th>
            <th align="center">{$smarty.const.T_AMOUNT}</th>
            <th colspan="2" align="center">{$smarty.const.T_ACTION}</th>
            <th align="center">{$smarty.const.T_HISTORY}</th>
        </tr>
        {section name=i loop=$Items max=18}
            <tr>
                <td>{$ItemNames[i]}</td>
                <td align="right">{$Items[i][0]} / {$Items[i][1]}</td>
                <td align="right">{$Items[i][2]}</td>
                <td><a href="warehouse.php?action=sell&amp;item={$smarty.section.i.index}">{$smarty.const.A_SELL}</a></td>
                <td><a href="warehouse.php?action=buy&amp;item={$smarty.section.i.index}">{$smarty.const.A_BUY}</a></td>
                <td align="center"><a href="warehouse.php?action=history&amp;item={$smarty.section.i.index}">{if $Graphstyle == "Y"}<img src="" alt="img_{$smarty.const.A_HISTORY}" title="{$smarty.const.A_HISTORY}" />{else}{$smarty.const.A_HISTORY}{/if}</a></td>
            </tr>
        {/section}
    </table>
    <br /><br />
    <table cellspacing="10px">
        <tr>
            <th>{$smarty.const.T_HERB}</th>
            <th>{$smarty.const.T_COST}</th>
            <th align="center">{$smarty.const.T_AMOUNT}</th>
            <th colspan="2" align="center">{$smarty.const.T_ACTION}</th>
            <th align="center">{$smarty.const.T_HISTORY}</th>
        </tr>
        {section name=i loop=$Items start=18}
            <tr>
                <td>{$ItemNames[i]}</td>
                <td align="right">{$Items[i][0]} / {$Items[i][1]}</td>
                <td align="right">{$Items[i][2]}</td>
                <td><a href="warehouse.php?action=sell&amp;item={$smarty.section.i.index}">{$smarty.const.A_SELL}</a></td>
                <td><a href="warehouse.php?action=buy&amp;item={$smarty.section.i.index}">{$smarty.const.A_BUY}</a></td>
                <td align="center"><a href="warehouse.php?action=history&amp;item={$smarty.section.i.index}">{if $Graphstyle == "Y"}<img src="" alt="img_{$smarty.const.A_HISTORY}" title="{$smarty.const.A_HISTORY}" />{else}{$smarty.const.A_HISTORY}{/if}</a></td>
            </tr>
        {/section}
    </table>
{/if}

{if isset($smarty.get.action)&&$smarty.get.action == 'sell'}
    <p>{$smarty.const.WAREHOUSE_INFO2}<br /></p>
    <form method="post" action="">
        <input type="submit" value="{$smarty.const.A_SELL}" /> <input type="text" name="amount" size="5" />{$smarty.const.AMOUNT}{$Name}{$smarty.const.YOU_HAVE}{$Amount}{$smarty.const.AMOUNT}{$Name}.
    </form>
{/if}
{if isset($smarty.get.action)&&$smarty.get.action == 'buy'}
    <p>{$smarty.const.WAREHOUSE_INFO3}<br /></p>
    <form method="post" action="">
        <input type="submit" value="{$smarty.const.A_BUY}" /> <input type="text" name="amount" size="5" />{$smarty.const.AMOUNT}{$Name} {$smarty.const.EACH_FOR} {$Price}) - {$smarty.const.W_AMOUNT}{$Amount}{$smarty.const.AMOUNT}{$Name}.
    </form>
{/if}

{if isset($smarty.get.action)&&$smarty.get.action == 'history'}
    <p>{$smarty.const.INFO1}<b>{$Name}</b>{$smarty.const.INFO2}</p>
    <div class="overflow">{if !empty($Data)}
{* Table looks very weird, but it's because it must be displayed horizontally.*}
        <table cellpadding="3px">
        {section name=i loop=$Data[0]}
            <tr valign="bottom" align="center">
{*Display row headers.*}
                <th style="font-size:smaller">{$Headers[i]}</th>
                {section name=j loop=$Data}
                <td style="font-size:smaller"
                    {if $smarty.section.i.first}
{*Display bars and buy/sell amounts. The <td> tag isn't closed on purpose! Other <td>'s have colspan=2'*}
                        ><div style="background-color: blue; max-width:12px; min-height:{$Data[j][0]*400/$Max|string_format:"%d"}px"></div>{$Data[j][0]}
                        </td><td style="font-size:smaller">
                        <div style="background-color: yellow; max-width:12px; min-height:{$Data[j][1]*400/$Max|string_format:"%d"}px"></div>{$Data[j][1]}
                    {else}
                        colspan="2">
                        {if $smarty.section.i.index==1}
{*Display day number.*}
                            {if $smarty.section.j.last}
                                {$smarty.const.TODAY}
                            {else}
                                -{$smarty.section.j.total-$smarty.section.j.rownum}
                            {/if}
                        {else}
{*Display normal data: amount and price.*}
                            {$Data[j][i]}
                        {/if}
                    {/if}
                </td>
                {/section}
            </tr>
        {/section}
        </table>
    {else}
        <p>{$smarty.const.NO_INFO}</p>
    {/if}
</div>
{/if}

{if isset($Message)}
    <p>{$Message}</p>
{/if}

{if isset($smarty.get.action)}
    <p><a href="warehouse.php">{$smarty.const.A_BACK}</a></p>
{/if}
{/strip}
