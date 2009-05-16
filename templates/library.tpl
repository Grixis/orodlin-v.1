{strip}
{if $Step == ""}
    <p>{$smarty.const.WELCOME}</p>
    <p>{$smarty.const.WELCOME1}</p>
    <p>{$smarty.const.WELCOME2} {$TextIn} {$Welcome3} {$TextNot}.</p>
    <ul>
        <li><a href="library.php?step=tales">{$smarty.const.A_TALES}</a> ({$AmountTales} {$Tales_Text})</li>
        <li><a href="library.php?step=poetry">{$smarty.const.A_POETRY}</a> ({$AmountPoetry} {$Poetry_Text})</li>
        <li><a href="library.php?step=rules">{$smarty.const.A_RULES}</a></li>
        <li><a href="library.php?step=add">{$smarty.const.A_ADD_TEXT}</a></li>
        {if $Rank == 'Admin' || $Rank == 'Bibliotekarz'}
            <li><a href="library.php?step=addtext">{$smarty.const.A_ADMIN}</a></li>
        {/if}
{/if}

{if $Step == "add"}
    {$smarty.const.ADD_INFO}<br />
    <form method="post" action="library.php?step=add&amp;step2=add">
        {$smarty.const.T_LANG}: <select name="lang">
            {section name=library loop=$Llang}
                <option value="{$Llang[library]}">{$Llang[library]}</option>
            {/section}
        </select><br />
        {$smarty.const.T_TYPE}: 
        <select name="type">
            <option value="{$smarty.const.T_TYPE1}">{$smarty.const.T_TYPE1}</option>
            <option value="{$smarty.const.T_TYPE2}">{$smarty.const.T_TYPE2}</option>
        </select><br />
        {$smarty.const.T_TITLE}: <input type="text" size="90" name="ttitle" /><br />
        {$smarty.const.T_BODY}: <br /><textarea name="body" rows="30" cols="90"></textarea><br />
        <input type="submit" value="{$smarty.const.A_ADD}" />
    </form>
{/if}

{if $Step == "addtext" && $Action != "modify"}
    {$smarty.const.ADMIN_INFO}<br />
    {$smarty.const.ADMIN_INFO2}<br />
    {$smarty.const.ADMIN_INFO3}<br />
    {$smarty.const.ADMIN_INFO4}<br /><br />
    {$smarty.const.ADMIN_INFO5}:
    <table width="100%">
        {section name=k loop=$TextList}
            <tr>
                <td>{$TextList[k][1]} ({$smarty.const.T_AUTHOR}: {$TextList[k][2]} {$smarty.const.ID} {$TextList[k][3]})</td>
                <td><a href="library.php?step=addtext&amp;action=modify&amp;text={$TextList[k][0]}">{$smarty.const.A_MODIFY}</a></td>
                <td><a href="library.php?step=addtext&amp;action=add&amp;text={$TextList[k][0]}">{$smarty.const.A_ADD}</a></td>
                <td><a href="library.php?step=addtext&amp;action=delete&amp;text={$TextList[k][0]}">{$smarty.const.A_DELETE}</a></td>
            </tr>
        {/section}
    </table>
{/if}

{if $Step == 'tales' || $Step == 'poetry'}
    {if $TextAmount == "0" && $Text == ""}
        <br /><br /><center> {$smarty.const.NO_ITEMS} {$TextType} {$smarty.const.IN_LIB}</center>
    {/if}
    {if $TextAmount > "0" && $Text == ""}
        {$smarty.const.LIST_INFO} {$TextType} {$smarty.const.LIST_INFO2}<br />
        {if $Author == ""}
            {$smarty.const.SORT_INFO}:
            <form method="post" action="library.php?step={$Step}">
                <select name="sort">
                    <option value="author">{$smarty.const.O_AUTHOR}</option>
                    <option value="id">{$smarty.const.O_DATE}</option>
                    <option value="title">{$smarty.const.O_TITLE}</option>
                </select><br />
                <input type="submit" value="{$smarty.const.A_SORT}" />
            </form>
        {/if}
        {if $Author == "" && ($Sort == "author" || $Sort == "")}
            <ul>
                {section name=library6 loop=$Tauthor}
                    <li><a href="library.php?step={$Step}&amp;author={$Tauthor[library6][1]}">{$Tauthor[library6][0]}</a><br />
                    {$TextType}: {$Tauthor[library6][2]}<br /><br /></li>
                {/section}
            </ul>
        {/if}
        {if $Sort != "" && $Sort != "author"}
            <ul>
                {section name=library4 loop=$TextList}
                    <li><a href="library.php?step={$Step}&amp;text={$TextList[library4][0]}">{$TextList[library4][1]}</a><br />
                    {$smarty.const.T_AUTHOR}: {if $TextList[library4][3] < 1000000}<a href="view.php?view={$TextList[library4][3]}">{$TextList[library4][2]}</a>{else}<b>{$TextList[library4][2]}</b> {$smarty.const.LEFT}{/if}<br />
                    <a href="library.php?step=comments&amp;text={$TextList[library4][0]}">{$smarty.const.T_COMMENTS}</a>: {$Comments[library4]}<br /><br /></li>
                {/section}
            </ul>
        {/if}
        {if $Author != ""}
            <ul>
            {section name=a loop=$AuthorList}
                <li>{$smarty.const.T_AUTHOR}: {if $AuthorList[a][1] < 1000000} <a href="view.php?view={$AuthorList[a][1]}">{$AuthorList[a][0]}</a>{else}<b>{$AuthorList[a][0]}</b> {$smarty.const.LEFT}{/if}<br />
                <ul>
                {section name=b loop=$TextsList[a]}
                    <li><a href="library.php?step={$Step}&amp;text={$TextsList[a][b][0]}">{$TextsList[a][b][1]}</a><br />
                    <a href="library.php?step=comments&amp;text={$TextsList[a][b][0]}">{$smarty.const.T_COMMENTS}</a>: {$CommentsAmount[b][0]}<br /><br /></li>
                {/section}
                </ul>
            {/section}
            </ul>
        {/if}
    {/if}
    {if $Text != ""}
        {if $Rank == "Admin" || $Rank == "Bibliotekarz"}
            (<a href="library.php?step=addtext&amp;action=modify&amp;text={$TextData[0]}">{$smarty.const.A_MODIFY}</a>)<br />
        {/if}
        <b>{$smarty.const.T_TITLE}</b>: {$TextData[1]}<br />
        <b>{$smarty.const.T_AUTHOR}</b>: {if $TextData[4] < 1000000}<a href="view.php?view={$TextData[4]}">{$TextData[3]}</a>{else}<b>{$TextData[3]}</b> {$smarty.const.LEFT}{/if}<br />
        <b>{$smarty.const.T_BODY}</b>:<br />
        {$TextData[2]}<br /><br />
        <a href="library.php?step=comments&amp;text={$TextData[0]}">{$smarty.const.T_COMMENTS2}</a>
    {/if}
{/if}

{if $Step == "comments"}
    {if $Amount == "0"}
        <center>{$smarty.const.NO_COMMENTS}</center>
    {/if}
    {if $Amount > "0"}
        {section name=library5 loop=$Tauthor}
            <b>{$Tauthor[library5]}</b> {if $Tdate[library5] != ""} ({$Tdate[library5]}) {/if}{$smarty.const.WRITED}: {if $Rank == "Admin" || $Rank == "Bibliotekarz"} (<a href="library.php?step=comments&amp;action=delete&amp;cid={$Cid[library5]}">{$smarty.const.A_DELETE}</a>) {/if}<br />
            <div class="overflow">{$Tbody[library5]}</div><br />
        {/section}
    {/if}
    <br /><br /><center>
    <form method="post" action="library.php?step=comments&amp;action=add">
        {$smarty.const.ADD_COMMENT}:<br /><textarea name="body" rows="20" cols="50"></textarea><br />
        <input type="hidden" name="tid" value="{$Text}" />
        <input type="submit" value="{$smarty.const.A_ADD}" />
    </form></center>
{/if}

{if $Step == "rules"}
    {$smarty.const.RULES}
{/if}

{if $Action == "modify"}
    <form method="post" action="library.php?step=addtext&amp;action=modify&amp;text={$TextToModify[0]}">
        <b>{$smarty.const.T_TYPE}</b>: <br />
        <select name="type">
            <option value="{$smarty.const.T_TYPE1}" {if $TextToModify[3] == "tale"} selected="true" {/if}>{$smarty.const.T_TYPE1}</option>
            <option value="{$smarty.const.T_TYPE2}" {if $TextToModify[3] == "poetry"} selected="true" {/if}>{$smarty.const.T_TYPE2}</option>
        </select>
        <input type="hidden" name="tid" value="{$TextToModify[0]}" /><br />
        <b>{$smarty.const.T_TITLE}</b>: <input type="text" size="90" name="ttitle" value="{$TextToModify[1]}" /><br />
        <b>{$smarty.const.T_BODY}</b>: <br /><textarea name="body" rows="30" cols="90">{$TextToModify[2]}</textarea><br />
        <input type="hidden" name="tid" value="{$TextToModify[0]}" />
        <input type="submit" value="{$smarty.const.A_CHANGE}" />
    </form><br />
    <a href="library.php?step=addtext">{$smarty.const.A_BACK}</a> {$smarty.const.WAIT_LIST}
{/if}

{if $Step != ""}
    <br /><br /><a href="library.php">{$smarty.const.A_BACK}</a>
{/if}
{/strip}
