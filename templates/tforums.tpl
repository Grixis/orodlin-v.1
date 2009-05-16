{strip}
{if $View == "topics"}
    <form method="post" action="tforums.php?action=search">
        <input type="submit" value="{$Asearch}" /> {$Tword}: <input type="text" name="search" />
    </form>
    <table width="100%">
    <tr>
    <td width="70%"><u><b>{$Ttopic}</b></u></td>
    <td width="20%"><u><b>{$Tauthor}</b></u></td>
    <td width="10%"><u><b>{$Treplies}</b></u></td>
    </tr>
    {section name=tforums loop=$Topic}
        <tr>
        <td><div class="overflow">{if $Newtopic[tforums] == "Y"}<blink>N</blink> {/if}<a href="tforums.php?topic={$Topicid[tforums]}"><span class="forum">{$Topic[tforums]}</span></a></div></td>
        <td>{$Starter[tforums]}</td>
        <td>{$Replies[tforums]}</td>
        </tr>
    {/section}
    </table>
    <form method="post" action="tforums.php?action=addtopic">
    {$Addtopic}:<br /><input type="text" name="title2" value="" size="60%" /><br />
    <textarea name="body" cols="60%" rows="15">{$Ttext}</textarea><br />
    {$Sticky}
    <input type="submit" value="{$Addtopic}" /></form>
{/if}

{if $Topics != ""}
    <br />
    <center>
    <div class="overflow"><b>{$Topic}</b></div>
    <div class="overflow"><fieldset style="width:90%">
    <legend>{$Writeby} {$Starter}{if $Starterid > "0"} ID: {$Starterid}{/if} </legend>
    (<a href="tforums.php?view=topics">{$Aback}</a>) (<a href="tforums.php?topic={$Topics}&amp;quotet=Y">{$Aquote}</a>) {$Delete}<br/><br/>
    <div style="text-align:left">{$Topictext}</div>
    </fieldset></div>
    {section name=tforums1 loop=$Reptext}
        <fieldset style="width:90%">
        <legend><b>{$Repstarter[tforums1]}</b>{if $Repstarterid[tforums1] > "0"} ID: {$Repstarterid[tforums1]}{/if} {$Write}...</legend>
        (<a href="tforums.php?view=topics">{$Aback}</a>) (<a href="tforums.php?topic={$Topics}&amp;quote={$Rid[tforums1]}">{$Aquote}</a>) {$Action[tforums1]}
        <div style="text-align:left">{$Reptext[tforums1]}</div>
        </fieldset><br />
    {/section}
    <form method="post" action="tforums.php?reply={$Id}">
    {$Areply}:<br />
    <textarea name="rep" cols="75%" rows="15">{$Rtext}</textarea><br />
    <input type="submit" value="{$Areply}" /></form>
    </center>
{/if}

{if $Action2 == "search"}
    {if $Amount == "0"}
        <br /><br /><center>{$Nosearch}</center><br />
    {/if}
    {if $Amount > "0"}
        {$Youfind}:<br /><br />
        {section name=number3 loop=$Ttopic}
            <a href="tforums.php?topic={$Tid[number3]}">{$Ttopic[number3]}</a><br />
        {/section}
    {/if}
    <br /><br /><a href="tforums.php?view=topics">{$Aback}</a>
{/if}
{/strip}
