{strip}
{if $Action == ""}
    {$Notesinfo}<br /><br />
    {section name=notes loop=$Noteid}
        <div class="overflow"><fieldset style="width:75%">
        <legend>{$Ntime}: {$Notetime[notes]}</legend>
        {$Notetext[notes]}<br/>
        (<a href="notatnik.php?akcja=skasuj&amp;nid={$Noteid[notes]}">{$Adelete}</a>) (<a href="notatnik.php?akcja=edit&amp;nid={$Noteid[notes]}">{$Aedit}</a>)
        </fieldset></div>
    {/section}
    <br /><br /><a href="notatnik.php?akcja=dodaj">{$Aadd}</a>
{/if}

{if $Action == "edit" || $Action == "dodaj"}
    <form method="post" action="notatnik.php?akcja={$Nlink}">
    {$Note}:<br />
    <textarea name="body" rows="15" cols="50">{$Ntext}</textarea><br />
    <input type="submit" value="{$Asave}" />
    </form>
{/if}
{/strip}
