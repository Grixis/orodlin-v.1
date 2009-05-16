{strip}
{if $View == "" && $Step == ""}
    {$Newplayer}
    <div class="overflow"><b>{$Title1}</b> {$Writeby} <b>{$Starter}</b>{$Date}... {$Modtext}<br /><br />
    "{$Update}".</div>
    <a href="updates.php?step=comments&amp;text={$Updid}">{$Acomments}</a>: {$Comments}<br /><br />
    (<a href="updates.php?view=all">{$Alast10}</a>)
{/if}

{if $View == "all" && $Step == ""}
    {section name=upd loop=$Title1}
        <div class="overflow"><b>{$Title1[upd]}</b> {$Writeby} <b>{$Starter[upd]}</b>{$Date[upd]}... {$Modtext[upd]}<br /><br /> 
        "{$Update[upd]}"</div>
        <a href="updates.php?step=comments&amp;text={$Updid[upd]}">{$Acomments}</a>: {$Comments[upd]}<br /><br />
    {/section}
{/if}

{if $Step == "comments"}
    {if $Amount == "0"}
        <center>{$Nocomments}</center>
    {/if}
    {if $Amount > "0"}
        {section name=update loop=$Tauthor}
            <div class="overflow"><fieldset style="width:90%">
            <legend><b>{$Tauthor[update]}</b> {if $Tdate[update] != ""} ({$Tdate[update]}) {/if}{$Writed}: </legend>
            {if $Rank == "Admin" || $Rank == "Staff"} <center>(<a href="updates.php?step=comments&amp;action=delete&amp;cid={$Cid[update]}">{$Adelete}</a>) </center> {/if}<br />
            {$Tbody[update]}
            </fieldset></div>
        {/section}
    {/if}
    <br /><br /><center>
    <form method="post" action="updates.php?step=comments&amp;action=add">
        {$Addcomment}:<br /><textarea name="body" rows="15" cols="50"></textarea><br />
        <input type="hidden" name="tid" value="{$Text}" />
        <input type="submit" value="{$Aadd}" />
    </form>
    <br /></center>
    <a href="updates.php">{$Aback}</a>
{/if}
{/strip}
