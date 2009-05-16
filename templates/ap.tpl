{strip}
{if $smarty.get.step == ''}
   {$smarty.const.AP_INFO} <b>{$Ap}</b> {$smarty.const.AP}.<br /><br />
    <form method="post" action="ap.php?step=add">
    <table width="50%">
    {section name=k loop=$StatSumm}
        <tr><td> +{$StatSumm[k]} {$StatDesc[k]} {$smarty.const.PER_AP}</td><td><input type="text" name="{$StatNames[k]}" size="5" value="0" /></td></tr>
    {/section}
    </table>
    <input type="submit" value="{$smarty.const.A_ADD}" /></form><br />
    {if $Age < 4}{$smarty.const.GET_BACK_DESC}<br /> <a href="ap.php?step=reassign"> {$smarty.const.GET_BACK_LINK} </a>{/if}
{/if}

{if $smarty.get.step == 'add'}
    {$smarty.const.YOU_GET}: <br />
    {section name=stats loop=$Amount}
        <b>{$Amount[stats]}</b> {$Name[stats]}<br />
    {/section}
    <a href="stats.php">{$smarty.const.CLICK}</a> {$smarty.const.FOR_A}.
{/if}

{if $smarty.get.step == 'reassign'}
    {$smarty.const.RE_INFO}
    <form method="post" action="">
        <input type="submit" name="answer" value="{$smarty.const.A_YES}" />
    </form>
    <form method="post" action="ap.php">
        <input type="submit" name="answer" value="{$smarty.const.A_NO}" />
    </form>
    {if $smarty.post.answer == "Tak"}
        {$smarty.const.GOT_BACK_PRE} {$Diff} {$smarty.const.GOT_BACK_MID} {$Amount} {$smarty.const.GOT_BACK_POST} <a href="ap.php"> {$smarty.const.BACK} </a>
    {/if}
{/if}
{/strip}
