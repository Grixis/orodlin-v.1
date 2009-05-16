{strip}
{* Copyright notice: Code for smart display of odd/even number of monuments is based on http://smarty.php.net/contribs/examples/dynamic_table_columns/table.tpl.txt *}

{* Display "Table of Contents". "ToC" links to monument groups, and the titles of groups link back to "ToC" *}
<div style="margin: 0 auto; width: 50%">
    <a name="toc" />
    <ol type="I">
    {section name=toc loop=$Groups}
        <li><a href="#group{$smarty.section.toc.index}">{$Groups[toc]}</a></li>
    {/section}
    </ol>
    <p><a href="ranking.php">{$smarty.const.A_PLAYER_RANKING}</a></p>
	<p><a href="landstats.php">{$smarty.const.A_LAND_STATS}</a></p>
</div>
<br />
<p>{$smarty.const.GEN_TIME} <b>{$smarty.now+3600|date_format:"%H:%M"}</b></p>
<br />
{* For each group title... *}
{section name=i loop=$Titles}
    <hr />
    <h4 align="center"><a name="group{$smarty.section.i.index}" href="#toc">{$Groups[i]}</a></h4>
{* ...display it. Delete this part if you don't want links. *}

{* Each monument group is a separate table. *}
    <table align="center">
    <tr>
    {section name=j loop=$Titles[i]}
        {if $smarty.section.j.last && ($smarty.section.j.iteration % 2 == 1)}
{* If its last element, it has to be aligned to center and cover ALL columns. *}
        <td colspan="2" align="center">
        {else}
        <td align="center">
        {/if}

{* !!! Display each monument - start. *}
        <fieldset style="width: 265px;">
            <legend align="center" class="monlegend">{$Titles[i][j]}</legend>
            <table width="265">
                <tr>
                    <td class="montitle">{$smarty.const.M_NAME}</td>
                    <td class="montitle">{$Descriptions[i][j]}</td>
                </tr>
                {section name=k loop=$Monuments[i][j]}
                    <tr class="{cycle values="mon1,mon2"}">
                        <td style="width: 70%;">
                            &raquo; <a href="view.php?view={$Monuments[i][j][k][0]}">{$Monuments[i][j][k][1]}</a> ({$Monuments[i][j][k][0]})
                        </td>
                        <td align="right">{$Monuments[i][j][k][2]}</td>
                    </tr>
                {/section}
            </table>
        </fieldset>
{* !!! Display each monument -stop. *}
        </td>
{* should we go to the next row? *}
        {if ! $smarty.section.j.last}
            {if !($smarty.section.j.rownum % 2)}
                </tr><tr>
            {/if}
        {else}
            </tr>
        {/if}
    {/section}
    </table>
{/section}
{/strip}
