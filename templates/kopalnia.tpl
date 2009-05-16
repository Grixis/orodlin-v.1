{strip}
{if $smarty.get.action == "dig"}
    {$Youfind}<br /><br />
{/if}

{if $Health > "0"}
    {$smarty.const.MINES_INFO}
    <form method="post" action="kopalnia.php?action=dig">
        <input type="submit" value="{$smarty.const.A_SEARCH}" /> {$smarty.const.T_MINERALS} <input type="text" name="amount" size="5" value="0" /> {$smarty.const.T_AMOUNT}
    </form><br />
    - <a href="gory.php">{$smarty.const.NO}</a><br />
{/if}
{/strip}
