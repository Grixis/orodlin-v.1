{strip}
{if $View == ""}
    {$Panelinfo}
    <ul>
    <li><A href="addnews.php">{$Anews}</a></li>
    <li><a href="staff.php?view=takeaway">{$Atake}</a></li>
    <li><a href="staff.php?view=clearc">{$Aclear}</a></li>
    <li><a href="staff.php?view=czat">{$Achat}</a></li>
    <li><a href="staff.php?view=jail">{$Ajail}</a></li>
    <li><a href="staff.php?view=addtext">{$Aaddnews}</a></li>
    <li><a href="staff.php?view=innarchive">{$Ainnarchive}</a></li>
    <li><a href="staff.php?view=banmail">{$Abanmail}</a></li>
    <li><a href="staff.php?view=changenick">{$Achangenick}</a></li>
    </ul>
{/if}

{if $View == "banmail"}
    {$Blocklist}<br />
    {section name=banmail loop=$List1}
        ID {$List1[banmail]}<br />
    {/section}
    <form method="post" action="staff.php?view=banmail&amp;step=mail">
    <select name="mail">
        <option value="blok">{$Ablock}</option>
        <option value="odblok">{$Aunblock}</option>
    </select>
    {$Mailid} <input type="text" name="mail_id" size="5" /><br />
    <input type="submit" value="{$Amake}" /></form>
{/if}

{if $View == "innarchive"}
    {if $Text[0] != ""}
        {section name=player loop=$Text}
            <b>{$Author[player]} {$Cid}:{$Senderid[player]}</b>: {$Text[player]}<br />
        {/section}
    {/if}
    {$Previous} {$Next}
{/if}

{if $View == "jail"}
    {if isset($Confirm)}
        {$Fquestion}<br />
        <form method="post" action="staff.php?view=jail&amp;step=add&amp;confirm=Y">
        <input type="hidden" name="prisoner" value="{$Prisoner}" />
        <input type="hidden" name="verdict" value="{$Verdict}" />
        <input type="hidden" name="time"  value="{$Duration}" />
        <input type="hidden" name="cost"  value="{$Fine}" />
        <input type="submit" value="{$Ayes}" /></form>
    {else}
        <form method="post" action="staff.php?view=jail&amp;step=add">
        {$Jailid}: <input type="text" name="prisoner" /><br />
        {$Jailreason}: <textarea name="verdict"></textarea><br />
        {$Jailtime}: <input type="text" name="time" /><br />
        {$Jailcost}: <input type="text" name="cost" /><br />
        <input type="submit" value="{$Aadd}" /></form>
    {/if}
{/if}

{if $View == "takeaway"}
    {$Takeinfo}<br />
    <form method="post" action="staff.php?view=takeaway&amp;step=takenaway">
    <table>
        <tr>
            <td>{$Takeid}:</td><td><input type="text" name="id" size="5" /></td>
        </tr>
        <tr>
            <td>{$Takeamount}:</td><td><input type="text" name="taken" /></td>
        </tr>
        <tr>
            <td>{$Treason}</td><td><textarea name="verdict"></textarea></td>
        </tr>
        <tr>
            <td>{$Tinjured}</td><td><input type="text" name="id2" size="5" /></td>
        </tr>
        <tr>
            <td colspan="2" align="center"><input type="submit" value="{$Atakeg}" /></td>
        </tr>
    </table>
    </form>
{/if}

{if $View == "czat"}
    {$Blocklist}<br />
    {section name=player loop=$List1}
        ID {$List1[player]}<br />
    {/section}
    <form method="post" action="staff.php?view=czat&amp;step=czat">
    <select name="czat">
    <option value="blok">{$Ablock}</option>
    <option value="odblok">{$Aunblock}</option></select>
    {$Tid} <input type="text" name="czat_id" size="5" /> {$Ona} <input type="text" size="5" name="duration" value="1" />{$Tdays}<br />
    <textarea name="verdict"></textarea><br />
     <input type="submit" value="{$Amake}" />
    </form>
{/if}

{if $View == "addtext"}
    {$Admininfo}<br />
    {$Admininfo2}<br />
    {$Admininfo3}<br />
    {$Admininfo4}<br /><br />
    {$Admininfo5}:
    <table width="100%">
        {section name=staff2 loop=$Ttitle}
            <tr>
                <td>{$Ttitle[staff2]} ({$Tauthor2}: {$Tauthor[staff2]})</td>
                <td><a href="staff.php?view=addtext&amp;action=modify&amp;text={$Tid[staff2]}">{$Amodify}</a></td>
                <td><a href="staff.php?view=addtext&amp;action=add&amp;text={$Tid[staff2]}">{$Aadd}</a></td>
                <td><a href="staff.php?view=addtext&amp;action=delete&amp;text={$Tid[staff2]}">{$Adelete}</a></td>
            </tr>
        {/section}
    </table>
{/if}

{if $Action == "modify"}
<form method="post" action="staff.php?view=addtext&amp;action=modify&amp;text={$Tid}">
    {$Ttitle2}: <input type="text" name="ttitle" value="{$Ttitle}" /><br />
    {$Tbody2}: <br /><textarea name="body" rows="30" cols="60">{$Tbody}</textarea><br />
    <input type="hidden" name="tid" value="{$Tid}" />
    <input type="submit" value="{$Achange}" />
</form>
{/if}

{if $View == "changenick"}
    {if !$Action}
    <p>{$Info}</p>
        <form method="post" action="staff.php?view=changenick&amp;action=nick">
            {$Changename1} <input type="text" name="id" size="5" /> {$Changename2} <input type="text" name="name" size="20" value="{$Defaultname}" /><br />
            {$Changereason}: <br /><textarea name="reason" rows="3" cols="40">{$Reason1}</textarea><br />
        <input type="submit" value="{$Save}" />
        </form>
    <br /><br />
        <form method="post" action="staff.php?view=changenick&amp;action=profile">
            {$Editprofile} <input type="text" name="id" size="5" /><br />
            <input type="submit" value="{$Edit}" />
        </form>
    <br /><br />
        <form method="post" action="staff.php?view=changenick&amp;action=rank">
            {$Setid} <input type="text" name="id" size="5" /> {$Newrank}
            <select name="rank">
                <option value="{$RMember}">{$DescMember}</option>
                <option value="{$RBeggar}">{$DescBeggar}</option>
                <option value="{$RVillain}">{$DescVillain}</option>
            </select>
            <input type="submit" value="{$Save}" />
        </form>
    {else}
        <p>{$Currentinfo} <b><a href="view.php?view={$VictimID}">{$Victimname}</a></b>:</p>
        <p>{$Current}</p>
        <form method="post" action="staff.php?view=changenick&amp;action=profile&amp;action2=edit">
            <textarea name="editedprofile" rows="15" cols="50">{$Editable}</textarea>
            <p>{$Changereason}:</p><textarea name="reason" rows="5" cols="50">{$Reason2} {$Reason3}</textarea><br />
            <input type="hidden" name="id" value="{$VictimID}" />
            <input type="submit" value="{$Save}" />
        </form>
    {/if}
{/if}
{/strip}
