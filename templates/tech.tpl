{strip}
{if $View == ""}
<p>{$smarty.const.WELCOME}</p>
<ul{if $Graphstyle == "Y"} class="sword"{/if}>
	<li><a href="bugtrack.php">{$smarty.const.BUGTRACK}</a></li>
	<li><a href="tech.php?view=bugreport">{$smarty.const.BUGREPORT}</a></li>
</ul>
{/if}

{if $smarty.get.view == "bugreport"}
    {if $smarty.get.step != ""}
		{if $Bug[6] == "0"}
			<form method="post" action="">
				<input type="hidden" name="programmer" value=1 />
				<input type="submit" value="{$smarty.const.BUG_REPAIR}" />
			</form>
			{$BugMessage}
		{else}
			<form method="post" action="">
				<input type="hidden" name="programmer" value=0 />
				<input type="submit" value="{$smarty.const.BUG_LEAVE}" />
			</form>
			{$BugMessage}
			<p>{$smarty.const.BUG_PROGRAMMER} <b>{$Programmer}</b></p>
		{/if}
        <b>{$smarty.const.BUG_NAME}:</b> {$Bug[2]}<br />
        <b>{$smarty.const.BUG_TYPE}:</b> {$BugType}<br />
        <b>{$smarty.const.BUG_LOC}:</b> {$Bug[4]}<br />
        <b>{$smarty.const.BUG_DESC}:</b> {$Bug[5]}<br />
        <form method="post" action="tech.php?view=bugreport&amp;step={$smarty.get.step}" onsubmit="if (this.bugcomment.value=='') {literal}{{/literal} return confirm('{$smarty.const.EMPTY_COMMENT}') } else {literal}{{/literal} return true; }">
            <b>{$smarty.const.BUG_ACTIONS}:</b> <select name="actions">
                {section name=k loop=$Options}
                    <option value="{$Actions[k]}">{$Options[k]}</option>
                {/section}
            </select><br />
            <b>{$smarty.const.BUG_COMMENT}:</b> <textarea name="bugcomment" rows="5" cols="50"></textarea><br /><br />
            <input type="submit" value="{$smarty.const.A_MAKE}" />
        </form><br />
		<a href="tech.php?view=bugreport" >{$smarty.const.A_BACK}</a>
    {else}
        <table align="center" width="100%">
            <tr>
                <th width="5%">{$smarty.const.BUG_ID}</th>
                <th width="10%">{$smarty.const.BUG_REPORTER}</th>
                <th width="15%">{$smarty.const.BUG_TYPE}</th>
                <th width="30%">{$smarty.const.BUG_LOC}</th>
                <th width="40%">{$smarty.const.BUG_NAME}</th>
            </tr>
			{section name=k loop=$Bugs}
			<tr {if $Bugs[k][5] != "0"}class="bugrepair"{/if}>
				<td width="5%" align="center"><a {if $Bugs[k][5] != "0"}class="bugrepair" {/if}href="tech.php?view=bugreport&amp;step={$Bugs[k][0]}">{$Bugs[k][0]}</td>
				<td width="10%" align="center">{$Bugs[k][1]}</td>
				<td width="15%" align="center">{if $Bugs[k][3] == 'text'}{$smarty.const.BUG_TEXT}{else}{$smarty.const.BUG_CODE}{/if}</td>
				<td width="30%" align="center">{$Bugs[k][4]|wordwrap:25:"\n":true}</td>
				<td width="40%" align="center">{$Bugs[k][2]|wordwrap:25:"\n":true}</td>
			</tr>
			{/section}
		</table>
    {/if}
{/if}


{/strip}
