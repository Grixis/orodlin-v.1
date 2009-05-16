{strip}
{if isset($Data)}
<p>{$smarty.const.INFO}</p>
<table cellpadding="4px">
    <tr>
        <th>{$smarty.const.PL_ID}<a href="{$TopLink}orderby=id&amp;order=DESC"><img src="" alt="img_Malejąco" title="Sortuj malejąco" /></a><a href="{$TopLink}orderby=id&amp;order=ASC"><img src="" alt="img_Rosnąco" title="Sortuj rosnąco" /></a></th>
        <th>{$smarty.const.PL_NAME}<a href="{$TopLink}orderby=user&amp;order=DESC"><img src="" alt="img_Malejąco" title="Sortuj malejąco" /></a><a href="{$TopLink}orderby=user&amp;order=ASC"><img src="" alt="img_Rosnąco" title="Sortuj rosnąco" /></a></th>
        <th>{$smarty.const.PL_RANK}<a href="{$TopLink}orderby=rank&amp;order=DESC"><img src="" alt="img_Malejąco" title="Sortuj malejąco" /></a><a href="{$TopLink}orderby=rank&amp;order=ASC"><img src="" alt="img_Rosnąco" title="Sortuj rosnąco" /></a></th>
        <th>{$smarty.const.PL_RACE}<a href="{$TopLink}orderby=rasa&amp;order=DESC"><img src="" alt="img_Malejąco" title="Sortuj malejąco" /></a><a href="{$TopLink}orderby=rasa&amp;order=ASC"><img src="" alt="img_Rosnąco" title="Sortuj rosnąco" /></a></th>
        <th>{$smarty.const.PL_LEVEL}<a href="{$TopLink}orderby=level&amp;order=DESC"><img src="" alt="img_Malejąco" title="Sortuj malejąco" /></a><a href="{$TopLink}orderby=level&amp;order=ASC"><img src="" alt="img_Rosnąco" title="Sortuj rosnąco" /></a></th>
        {if $Rank == 'Admin' || $Rank == 'Staff'}
        <th>{$smarty.const.PL_IP}<a href="{$TopLink}orderby=ip&amp;order=DESC"><img src="" alt="img_Malejąco" title="Sortuj malejąco" /></a><a href="{$TopLink}orderby=ip&amp;order=ASC"><img src="" alt="img_Rosnąco" title="Sortuj rosnąco" /></a></th>
        {/if}
    </tr>
    {section name=i loop=$Data}
    <tr>
        <td>{$Data[i][0]}</td>
        <td><a href="view.php?view={$Data[i][0]}">{$Data[i][1]}</a></td>
        <td>{$Data[i][2]}</td>
        <td>{$Data[i][3]}</td>
        <td>{$Data[i][4]}</td>
        {if $Rank == 'Admin' || $Rank == 'Staff'}
        <td>{$Data[i][6]}</td>
        {/if}
    </tr>
    {/section}
</table>
{* Display pagination links except "current page", which will be normal number, not a link. *}
{if !empty($Pagelinks)}
	<div id="memberlist2" class="center">
    {section name=i loop=$Pagelinks}
        {if $Current!= $smarty.section.i.iteration} {$Pagelinks[i]} {else} {$Current} {/if}
	{if ($smarty.section.i.iteration%20)==0}<br />{/if}
    {/section}
	</div>
{/if}
{else}
    <p>{$smarty.const.NO_RESULTS}</p>
{/if}
<form method="get" action="memberlist.php">
<fieldset><legend>{$smarty.const.SEARCH_OPTIONS}</legend>
    <ul>
        <li>{$smarty.const.SEARCH_BY_NAME}: <input type="text" name="name" /></li>
        <li>{$smarty.const.SEARCH_BY_ID}: <input type="text" name="id" /></li>
        {if $Rank == 'Admin' || $Rank == 'Staff'}
        <li>{$smarty.const.SEARCH_BY_IP}: <input type="text" name="ip" /></li>
        {/if}
    </ul>
    <input type="submit" value="{$smarty.const.A_SEARCH}" />
</fieldset>
</form>
{/strip}
