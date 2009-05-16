{strip}
{if $View == "categories"}
{if isset($Sweep) && $Sweep > 0}
        {$Fquestion}<br />
        <a href="forums.php?view=categories&amp;sweep={$Sweep}&amp;step=Y">{$Ayes}</a><br />
    {else}
        <table><tr><td><b><u>{$Tcategory}</u></b></td><td><b><u>{$Ttopics}</u></b></td></tr>
        {section name=number loop=$Name}
            <tr>
                <td><a href="forums.php?topics={$Id[number]}"><u><span class="forum"><b>{$Name[number]}</b></span></u></a></td>
                <td><a href="forums.php?topics={$Id[number]}"><span class="forum">{$Topics1[number]}</span></a></td>
            </tr>
            <tr>
                <td><a href="forums.php?topics={$Id[number]}"><i><span class="forum">{$Description[number]}</span></i></a></td>
            </tr>
            {if $Rank == 'Admin' || $Rank == 'Staff'}
                <tr>
                    <td><a href="forums.php?view=categories&amp;sweep={$Id[number]}">{$ASweep}</a></td>
                </tr>
            {/if}
            <tr>
                <td colspan="2"><hr /></td>
            </tr>
        {/section}
        </table>
    {/if}
{/if}

{if $Topics != ""}
		<h2>{$Tcategory}: {$CategoryName}</h2>
    <a href="forums.php?view=categories">{$Aback}</a> {$Tocategories}.<br /><br />
    <form method="post" action="forums.php?action=search">
        <input type="submit" value="{$Asearch}" /> {$Tword}: <input type="text" name="search" />
        <input type="hidden" name="catid" value="{$Category}" />
    </form>
    <table width="100%"><tr><td width="65%"><u><b>{$Ttopic}</b></u></td><td width="25%"><u><b>{$Tauthor}</b></u></td><td width="10%"><b><u>{$Treplies}</u></b></td></tr>
    {section name=number1 loop=$Topic1}
        <tr>
        <td><div class="overflow">{if $Newtopic[number1] == "Y"}<blink><a href="forums.php?topic={$Id[number1]}#last">N</a></blink> {/if}<a href="forums.php?topic={$Id[number1]}"><span class="forum">{$Dates[number1]} {$Topic1[number1]}</span></a></div></td>
        <td valign="top"><a href="view.php?view={$StarterID[number1]}">{$Starter1[number1]}</a></td>
        <td valign="top">{$Replies1[number1]}</td>
        </tr>
    {/section}
    </table>
    <form method="post" action="forums.php?action=addtopic">
        {$Addtopic}:<br />
        <input type="text" name="title2" value="Temat" size="60%" /><br />
        <textarea name="body" cols="60%" rows="15">{$Ttext}</textarea><br />
        <input type="hidden" name="catid" value="{$Category}" />
        {if $Rank == "Admin" || $Rank == "Staff"}
            <input type="checkbox" name="sticky" />{$Tsticky}<br />
        {/if}
        <input type="submit" value="{$Addtopic}" />
    </form><br /><br />
    <a href="forums.php?view=categories">{$Aback}</a> {$Tocategories}.
{/if}

{if $Topic != ""}
<h2>{$Tcategory}: {$CategoryName}</h2>
{$Aback} <a href="forums.php?topics={$Category}">{$Totopics}</a> {$or} <a href="forums.php?view=categories">{$Tocategories}</a>.<br /><br />

    <br />
    <center>
    <div class="overflow"><a name="top"><b>{$TopicDate} {$Topic2}</b></a></div>
    <div class="overflow"><fieldset style="width:90%">
    <legend> {$Writeby} <a href="view.php?view={$Playerid}">{$Starter}</a> {$Wid} {$Playerid}:</legend>
    (<a href="forums.php?topics={$Category}">{$Aback}</a>) (<a href="forums.php?topic={$Topic}&amp;quotet=Y#last">{$Aquote}</a>) {$Action}<br/><br/>
    <div style="text-align:left">{$Ttext}</div>
    </fieldset></div>
    {section name=number2 loop=$Rtext}
        <div class="overflow"><fieldset style="width:90%">
        <legend><a name="p{$smarty.section.number2.index+1}" href="#p{$smarty.section.number2.index+1}">({$smarty.section.number2.index+1})</a> <b> {$Dates[number2]} <a href="view.php?view={$Rplayerid[number2]}">{$Rstarter[number2]}</a></b> {$Wid} {$Rplayerid[number2]} {$Write}: </legend>
        {if $smarty.section.number2.last}
            <a name="last"/ >
        {/if}
        (<a href="forums.php?topics={$Category}">{$Aback}</a>) (<a href="forums.php?topic={$Topic}&amp;quote={$Rid[number2]}#last">{$Aquote}</a>) {$Action2[number2]}
        <a href="#top"><img src="" alt="img_Na górę" /></a>
        <div style="text-align:left">{$Rtext[number2]}</div>
        </fieldset></div>
    {/section}
    <form method="post" action="forums.php?reply={$Id}">
    {$Areply}:<br />
    <textarea name="rep" cols="75" rows="15">{$Rtext2}</textarea><br />
    <input type="submit" value="{$Areply}" />
    </form>
    </center>

{/if}

{if $Action3 == "search"}
    {if $Amount == "0"}
        <br /><br /><center>{$Nosearch}</center><br />
    {/if}
    {if $Amount > "0"}
        {$Youfind}:<br /><br />
        {section name=number3 loop=$Ttopic}
            <a href="forums.php?topic={$Tid[number3]}">{$Ttopic[number3]}</a><br />
        {/section}
    {/if}
    <br /><br /><a href="forums.php?topics={$Category}">{$Aback}</a>
{/if}
{/strip}
