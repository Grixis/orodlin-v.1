{strip}
{if $View == "" && $Read == "" && $Write == "" && $Delete == "" && $Send == "" && $Step == "" && $Block == ""}
    <p>{$smarty.const.MAIL_INFO}</p>
    <ul{if $Graphstyle == "Y"} class="sword"{/if}>
        <li> <a href="mail.php?view=inbox">{$smarty.const.A_INBOX}</a></li>
        <li> <a href="mail.php?view=zapis">{$smarty.const.A_SAVED}</a></li>
        <li> <a href="mail.php?view=send">{$smarty.const.A_OUTBOX}</a></li>
        <li> <a href="mail.php?view=write">{$smarty.const.A_WRITE}</a></li>
        <li> <a href="mail.php?view=blocks">{$smarty.const.A_BLOCK_LIST}</a></li>
    </ul><br />
    {if $Senderid}
        <p>{$smarty.const.UNREAD_MAIL}</p>
        <table width="100%">
            <tr>
                <td width="25%"><b><u>{$smarty.const.FROM}</u></b></td>
                <td width="7%"><b><u>{$smarty.const.S_ID}</u></b></td>
                <td width="48%"><b><u>{$smarty.const.M_TITLE}</u></b></td>
                <td width="20%"><b><u>{$smarty.const.M_READ}</u></b></td>
            </tr>
            {section name=mail loop=$Sender}
            <tr>
                <td><a href="view.php?view={$Senderid[mail]}">{$Sender[mail]}</a></td>
                <td>{$Senderid[mail]}</td>
                <td>{$Subject[mail]}</td>
                <td>- <a href="mail.php?read={$Mailid[mail]}&amp;option=c">{$smarty.const.A_READ_C}</a><blink> !!</blink><br />
                - <a href="mail.php?read={$Mailid[mail]}&amp;option=u">{$smarty.const.A_READ_U}</a><blink> !!</blink></td>
            </tr>
            {/section}
        </table>
    {/if}
{/if}

{if $View == "inbox"}
    <form method="post" action="mail.php?step=mail&amp;box=I">
    <table width="100%">
    <tr>
        <td width="5%"></td>
        <td width="20%"><b><u>{$smarty.const.FROM}</u></b></td>
        <td width="7%"><b><u>{$smarty.const.S_ID}</u></b></td>
        <td width="48%"><b><u>{$smarty.const.M_TITLE}</u></b></td>
        <td width="20%"><b><u>{$smarty.const.M_READ}</u></b></td>
    </tr>
    {section name=mail loop=$Sender}
    <tr>
        <td><input type="checkbox" name="{$Mailid[mail]}" /></td>
        <td><a href="view.php?view={$Senderid[mail]}">{$Sender[mail]}</a></td>
        <td>{$Senderid[mail]}</td>
        <td>{$Subject[mail]}</td>
        <td>- <a href="mail.php?read={$Mailid[mail]}&amp;option=c">{$smarty.const.A_READ_C}</a>{if $Mread[mail] == "Y"} <blink>!!</blink> {/if}<br />
        - <a href="mail.php?read={$Mailid[mail]}&amp;option=u">{$smarty.const.A_READ_U}</a>{if $Mread[mail] == "Y"} <blink>!!</blink> {/if}</td>
    </tr>
    {/section}
    </table><br />
    <input type="submit" value="{$smarty.const.A_DELETE_S}" name="delete" /> <input type="submit" name="write" value="{$smarty.const.A_SAVE_S}" /> <input type="submit" name="read2" value="{$smarty.const.A_READ2}" /> <input type="submit" name="unread" value="{$smarty.const.A_UNREAD}" /><br />
    </form>
    <form method="post" action="mail.php?step=deleteold&amp;box=I">
        <input type="submit" value="{$smarty.const.A_DELETE_OLD}" /> <select name="oldtime">
            <option value="7">{$smarty.const.A_WEEK}</option>
            <option value="14">{$smarty.const.A_2WEEK}</option>
            <option value="30">{$smarty.const.A_MONTH}</option>
        </select>
    </form>
    <br />[<a href="mail.php?view=zapis">{$smarty.const.A_SAVED}</a>]
    [<a href="mail.php?view=inbox&amp;step=clear">{$smarty.const.A_CLEAR}</a>]
    [<a href="mail.php?view=write">{$smarty.const.A_WRITE}</a>]
{/if}

{if $View == "zapis"}
    <form method="post" action="mail.php?step=mail&amp;box=W">
    <table width="100%">
    <tr>
        <td width="5%"></td>
        <td width="20%"><b><u>{$smarty.const.FROM}</u></b></td>
        <td width="7%"><b><u>{$smarty.const.S_ID}</u></b></td>
        <td width="48%"><b><u>{$smarty.const.M_TITLE}</u></b></td>
        <td width="20%"><b><u>{$smarty.const.M_READ}</u></b></td>
    </tr>
    {section name=mail1 loop=$Sender}
    <tr>
        <td><input type="checkbox" name="{$Mailid[mail1]}" /></td>
        <td><a href="view.php?view={$Senderid[mail1]}">{$Sender[mail1]}</a></td>
        <td>{$Senderid[mail1]}</td>
        <td>{$Subject[mail1]}</td>
        <td>- <a href="mail.php?read={$Mailid[mail1]}&amp;option=c">{$smarty.const.A_READ_C}</a><br />
        - <a href="mail.php?read={$Mailid[mail1]}&amp;option=u">{$smarty.const.A_READ_U}</a></td>
    </tr>
    {/section}
    </table><br />
    <input type="submit" value="{$smarty.const.A_DELETE_S}" name="delete" /><br />
    </form>
    <form method="post" action="mail.php?step=deleteold&amp;box=W">
        <input type="submit" value="{$smarty.const.A_DELETE_OLD}" /> <select name="oldtime">
            <option value="7">{$smarty.const.A_WEEK}</option>
            <option value="14">{$smarty.const.A_2WEEK}</option>
            <option value="30">{$smarty.const.A_MONTH}</option>
        </select>
    </form>
    <br />[<a href="mail.php?view=inbox">{$smarty.const.A_INBOX}</a>]
    [<a href="mail.php?view=zapis&amp;step=clear">{$smarty.const.A_CLEAR}</a>]
    [<a href="mail.php?view=write">{$smarty.const.A_WRITE}</a>]
{/if}

{if $View == "send"}
    <form method="post" action="mail.php?step=mail&amp;box=S">
    <table width="100%">
    <tr>
        <td width="5%"></td>
        <td width="10%"><b><u>{$smarty.const.S_TO}</u></b></td></td>
        <td width="65%"><b><u>{$smarty.const.M_TITLE}</u></b></td>
        <td width="20%"></td>
    </tr>
    {section name=mail2 loop=$Send1}
        <tr>
        <td><input type="checkbox" name="{$Mailid[mail2]}" /></td>
        <td><a href="view.php?view={$Send1[mail2]}">{$Send1[mail2]}</a></td>
        <td>{$Subject[mail2]}</td>
        <td>- <a href="mail.php?read={$Mailid[mail2]}&amp;option=u">{$smarty.const.M_READ}</a></td>
        </tr>
    {/section}
    </table><br />
    <input type="submit" value="{$smarty.const.A_DELETE_S}" name="delete" /> <input type="submit" name="write" value="{$smarty.const.A_SAVE_S}" /><br />
    </form>
    <form method="post" action="mail.php?step=deleteold&amp;box=S">
        <input type="submit" value="{$smarty.const.A_DELETE_OLD}" /> <select name="oldtime">
            <option value="7">{$smarty.const.A_WEEK}</option>
            <option value="14">{$smarty.const.A_2WEEK}</option>
            <option value="30">{$smarty.const.A_MONTH}</option>
        </select>
    </form>
    <br />[<a href="mail.php">{$smarty.const.A_BACK}</a>]
    [<a href="mail.php?view=send&amp;step=clear">{$smarty.const.A_CLEAR}</a>]
    [<a href="mail.php?view=write">{$smarty.const.A_WRITE}</a>]
{/if}

{if $View == "write"}
    [<a href="mail.php?view=inbox">{$smarty.const.A_INBOX}</a>]<br /><br />
    <table>
    <form method="post" action="mail.php?view=write&amp;step=send">
    <tr><td>{$smarty.const.S_TO}:</td><td><input type="text" name="to" value="{$To}" /></td></tr>
    <tr><td>{$smarty.const.M_TITLE}:</td><td><input type="text" name="subject" size="55" value="{$Reply}" /></td></tr>
    <tr><td valign="top">{$smarty.const.M_BODY}:</td><td><textarea name="body" rows="13" cols="55">{$Body}</textarea></td></tr>
    <tr><td></td><td align="center"><input type="submit" value="{$smarty.const.A_SEND}" /></td></tr>
    </form></table>
{/if}

{if $Read != ""}
    {$Tday} <b><a href="view.php?view={$Senderid}">{$Sender}</a></b> {$smarty.const.T_WRITE}... "{$Body}"<br /><br />
    [<a href="mail.php?view=inbox">{$smarty.const.A_INBOX}</a>]
    [<a href="mail.php?zapisz={$Mailid}">{$smarty.const.A_SAVE}</a>]
    [<a href="mail.php?kasuj={$Mailid}">{$smarty.const.A_DELETE}</a>]
    [<a href="mail.php?view=write">{$smarty.const.A_WRITE}</a>]
    [<a href="mail.php?view=write&amp;to={$Senderid}&amp;re={if $AddReplyPrefix == 1}{$smarty.const.REPLY_PREFIX}{/if}{$Subject}&amp;id={$Mailid}">{$smarty.const.A_REPLY}</a>]
    [<a href="mail.php?send={$Mailid}">{$smarty.const.A_SEND}</a>]
    [<a href="mail.php?block={$Senderid}">{$smarty.const.A_BLOCK}</a>]
{/if}

{if $Send != ""}
    <form method="post" action="mail.php?send&amp;step=send">
    {$smarty.const.SEND_THIS}: <select name="staff">
    {section name=mail3 loop=$Name}
        <option value="{$Staffid[mail3]}">{$Name[mail3]}</option>
    {/section}
    </select><br />
    <input type="hidden" name="mid" value="{$Send}" />
    <input type="submit" value="{$smarty.const.A_SEND}" /></form>
{/if}

{if $View == "blocks"}
    {if $Blockid[0] != ""}
        <form method="post" action="mail.php?view=blocks&amp;step=unblock">
            <table>
                <tr>
                    {section name=blocks loop=$Blockid}
                        <td><input type="checkbox" name="{$Blockid[blocks]}" /></td>
                        <td>{$Blockname[blocks]} ID: {$Blockid[blocks]}</td>
                    {/section}
                </tr>
            </table>
            <input type="submit" value="{$smarty.const.A_UNBLOCK}" />
        </form>
    {else}
        <br />{$smarty.const.NO_BANNED}
    {/if}
    <br /><br />(<a href="mail.php">{$smarty.const.A_BACK}</a>)
{/if}
{/strip}
