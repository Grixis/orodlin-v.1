{strip}
{if $smarty.get.action == ""}
    {if $Health > "0"}
        <form method="post" action="lumberjack.php?action=chop">
        {$smarty.const.YOU_WANT} 
            <select name="kind">
            {section name=k loop=$LumberKinds max=$Limit+1}
                <option value="{$smarty.section.k.index}">{$LumberKinds[k]}</option>
            {/section}
            </select><br /><br />
            <input type="submit" value="{$smarty.const.A_CHOP}" /> {$smarty.const.ON_CHOP}
            <input type="text" name="amount" size="5"> {$smarty.const.T_ENERGY}
        </form>
    {/if}
    <a href="las.php">{$smarty.const.A_BACK}</a><br />
{/if}

{if $smarty.get.action == "chop"}
    {$Message}<br /><br />
    {if $Health > "0"}
        <a href="lumberjack.php">{$smarty.const.A_BACK}</a><br />
    {else}
        <a href="las.php">{$smarty.const.A_BACK}</a><br />
    {/if}
{/if}
{/strip}