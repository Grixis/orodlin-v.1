{strip}
{if (($Item == "0" && $Location == 'Altara') OR ($Location == 'Ardulith' && ($Step == "" OR $Step=="list"))) AND $Step!="ogloszenie"}
{if $Graphstyle == "Y"}<div class="center">
	{if $Location == 'Altara'}
	<a><img id="meredithpicture" src="images/locations/meredith.jpg" alt="img_{$smarty.const.SHOW_IMAGE}" onclick="showHideImage('meredithpicture','images/locations/meredith.jpg')"/></a>
	{/if}
	{if $Location == 'Ardulith'}
	<a><img id="agarakarpicture" src="images/locations/agarakar.jpg" alt="img_{$smarty.const.SHOW_IMAGE}" onclick="showHideImage('agarakarpicture','images/locations/agarakar.jpg')"/></a>
	{/if}
</div>{/if}
    <div class="justify">{$smarty.const.CITY_INFO}</div><br />
    <table width="100%">
        {section name=row1 loop=$Titles}
            {if $smarty.section.row1.index == 0 || $smarty.section.row1.index == 3 || $smarty.section.row1.index == 6}
                <tr>
            {/if}
                <td width="33%" valign="top"><div class="citytitle">{$Titles[row1]}</div><ul{if $Graphstyle == "Y"} class="sword"{/if}>
                    {section name=locations loop=$Files[row1]}
                        <li><a href="{$Files[row1][locations]}">{$Names[row1][locations]}</a></li>
                    {/section}
                </ul></td>
            {if $smarty.section.row1.index == 2 || $smarty.section.row1.index == 5 || $smarty.section.row1.index == 7}
                </tr>
            {/if}
        {/section}
    </table>

	<hr />
	{if $Step == ""}
	<div class="center">
		<b><u>{$smarty.const.OGLOSZENIE}</u></b><br />
		<b>{$News.tytul}</b>{$smarty.const.AUTOR}<b>{$News.autor}</b>{if $Rank == "Admin" || $Rank == "Staff"} <a href="city.php?step=ogloszenie&amp;del={$News.id}" onclick="return confirm('{$smarty.const.ERR_CONFIRM}');" title="{$smarty.const.ERR_DEL}">{if $Graphstyle == "Y"}<img src="images/icons/delete.png" alt="_img{$smarty.const.ERR_DEL}" />{else}{$smarty.const.ERR_DEL}{/if}</a>{/if}<br /><br />
		"{$News.tresc}"<br /><br />
		<a href="city.php?step=ogloszenie">{$smarty.const.NOWE}</a><br /><br /><a href="city.php?step=list">{$smarty.const.LISTA}</a>
	</div>
	{elseif $Step == "list"}
	<div class="center">
		<b><u>{$smarty.const.OSTATNIE}</u></b>
		{section name=i loop=$ArrNews}
			<br /><b>{$ArrNews[i][2]}</b>{$smarty.const.AUTOR}<b>{$ArrNews[i][1]}</b>{if $Rank == "Admin" || $Rank == "Staff"} <a href="city.php?step=list&amp;del={$ArrNews[i][0]}" onclick="return confirm('{$smarty.const.ERR_CONFIRM}');" title="{$smarty.const.ERR_DEL}">{if $Graphstyle == "Y"}<img src="images/icons/delete.png" alt="_img{$smarty.const.ERR_DEL}" />{else}{$smarty.const.ERR_DEL}{/if}</a>{/if}<br /><br />
			"{$ArrNews[i][3]}".<br><br>
		{/section}
		<a href="city.php?step=ogloszenie">{$smarty.const.NOWE}</a>
	</div>
	{/if}
	<br />

{elseif $Step == "ogloszenie"}
	<div id="ann">
		{$smarty.const.OGLOSZENIE_INFO}<b>{$Price}</b><br /><br />
		<form method="post" action="city.php?step=dodaj">
			<label for="antitle">{$smarty.const.TYTUL}</label><input id="antitle" type="text" name="tytul"><br />
			<label for="ancontent">{$smarty.const.TEKST}</label><textarea rows="4" cols="40" id="ancontent" name="tresc"></textarea><br />
			<label for="ansubmit">{$smarty.const.WYSLIJ}:</label><input id="ansubmit" type="submit" value="{$smarty.const.WYSLIJ}">
		</form>
	</div>
{elseif $Item == "1"}
    {if $Step == ""}
        {$smarty.const.STAFF_QUEST}<br />
        <a href="city.php?step=give">{$smarty.const.SQ_BOX1}</a><br />
        <a href="city.php?step=take">{$smarty.const.SQ_BOX2}</a>
    {elseif $Step != ""}
        {$Staffquest}<br />
        <a href="temple.php?temp=book&amp;book=2">{$smarty.const.TEMPLE}</a>
    {/if}
{elseif $Location == 'Ardulith' && $Step == "gory"}
         {$smarty.const.GO_MOUNTAINS}
{else}
         Welcome, whatever you are.
{/if}
{/strip}
