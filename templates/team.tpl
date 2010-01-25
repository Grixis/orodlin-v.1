{strip}
{if $Internal != 1}
	{include file="head.tpl"}
	<div id="content">
<div class="imgtitle"><img src="" alt="img_{$smarty.const.TEAM}" /></div>
<hr />

{/if}

{if $Message}<b>{$Message}</b>{/if}

{if $Mode == 'view'}

	<p>{$smarty.const.TEAM_LIST}</p>
	{if $Admin == 1}
	<a href="team.php?mode=add{$AddrSufix}">{$smarty.const.TEAM_ADD}</a>
	<br />
	{/if}

	<br />
	{if $NoMembers == 1}
		{$smarty.const.TEAM_NO_MEMBERS}
	{else}

		{foreach from=$ViewData item=Member}
		{if {$Member.Avatar != ''}
			<img src="" alt="img_avatars" style="float: left; margin: 5px;" />
		{/if}

		{if $Admin == 1}
		<a href="team.php?mode=delete&amp;id={$Member.ID}{$AddrSufix}">{if $Graphstyle == "Y"}<img src="images/icons/delete.png" title="{$smarty.const.TEAM_DELETE}" alt="img_{$smarty.const.TEAM_DELETE}" />{else}{$smarty.const.TEAM_DELETE}{/if}</a> <a href="team.php?mode=modify&amp;id={$Member.ID}{$AddrSufix}">{if $Graphstyle == "Y"}<img src="images/icons/edit.png" title="img_{$smarty.const.TEAM_MODIFY}" alt="{$smarty.const.TEAM_MODIFY}" />{else}{$smarty.const.TEAM_MODIFY}{/if}</a>
		<br />
		{/if}
		{$smarty.const.TEAM_NAME}: <b>{$Member.Name}</b><br />
		{$smarty.const.TEAM_FUNCTION}: <b>{$Member.Function}</b><br />
		{$smarty.const.TEAM_GAMEID}: <b>{if $Internal == 1}<a href="view.php?view={$Member.GameID}">{$Member.GameID}</a>{else}{$Member.GameID}{/if}</b><br />
		{if $Member.Contact != ''}
			{$Member.Contact}<br />
		{/if}
		<br clear="left" />
		<div class="footimg"><img src="" alt="img_footer"/></div>
		{/foreach}
	{/if}

	
{/if}


{if $Mode == 'add' || $Mode == 'modify'}

<b>
{if $Mode == 'add'}
	{$smarty.const.TEAM_ADD}
{/if}
{if $Mode == 'modify'}
	{$smarty.const.TEAM_MODIFY}
{/if}
</b>

	<form action="team.php?mode={$Mode}&amp;step=write{$AddrSufix}" method="post">
	{if $Mode == 'modify'}
		<input type="hidden" name="id" value="{$ModifyID}" />
	{/if}
{$smarty.const.TEAM_NAME}: <input type="text" name="name" value="{if $Mode == 'modify'}{$ModifyName}{/if}" /><br />
{$smarty.const.TEAM_GAMEID}: <input type="text" name="gameid" value="{if $Mode == 'modify'}{$ModifyGameID}{/if}" /><br />
{$smarty.const.TEAM_FUNCTION}: <input type="text" name="function" value="{if $Mode == 'modify'}{$ModifyFunction}{/if}" /><br />
{$smarty.const.TEAM_TYPE}:
	<select name="type">
		{if $Mode == 'modify'}
		<option value="{$ModifyType}"></option>
		{/if}
		<option value="head">{$smarty.const.T_HEAD}</option>
		<option value="gfx">{$smarty.const.T_GFX}</option>
		<option value="php">{$smarty.const.T_PHP}</option>
	</select><br />
	<input type="reset" /><input type="submit">
	</form>

	<a href="team.php?{$AddrSufix}">{$smarty.const.BACK}</a>

{/if}

{if $Mode == 'delete'}
{$TeamDeleteConfirmation}?<br />
<ul{if $Graphstyle == "Y"} class="sword"{/if}>
<li><a href="team.php?mode=delete&amp;step=write&amp;id={$ID}{$AddrSufix}">{$smarty.const.YES}</a></li>
<li><a href="team.php?{$AddrSufix}">{$smarty.const.BACK}</a></li>
</ul>
{/if}


{if $Internal != 1}
	</div> <!--id=content-->
	{include file=right.tpl}
	{include file=foot.tpl}
{/if}
{/strip}
