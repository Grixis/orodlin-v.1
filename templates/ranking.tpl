{strip}
<p>{$smarty.const.RNEXT} <b>{$smarty.now+3600|date_format:"%H:%M"}</b></p>
<br />
<table style="border: 1px solid #636f6b;" cellspacing="0" cellpadding="2">
    <tr>
        <th>{$smarty.const.RCOUNTER}</th>
        <th width="150px;">{$smarty.const.RPLAYER}</th>
        <th>{$smarty.const.RLEVEL}</th>
        <th>{$smarty.const.RPOINTS}</th>
    </tr>
    {section name=i loop=$arrStats}
    <tr {if $smarty.section.i.iteration < 4}bgcolor="#646464"{/if}>
        <td>{$smarty.section.i.iteration}.</td>
        <td><a href="view.php?view={$arrStats[i][0]}">{$arrStats[i][1]}</a> ({$arrStats[i][0]})</td>
        <td style="text-align:right">{$arrStats[i][2]}</td>
        <td style="text-align:right"><span style="color: orange;">{$arrStats[i][3]|string_format:"%.2f"}</span></td>
    </tr>
    {/section}
</table>
{/strip}