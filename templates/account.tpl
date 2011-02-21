{strip}
{$Welcome}
<ul {if $Graphstyle=="Y"}class="sword"{/if}>
    {section name=menu loop=$Steps}
        <li><a href="account.php?view={$Steps[menu]}">{$Links[menu]}</a></li>
    {/section}
</ul>

{if $View == "links"}
    {$Linksinfo}<br />
    {if $Linksid[0] != ""}
        <table align="center">
            <tr>
                <td align="center"><b>{$Tfile}</b></td>
                <td align="center"><b>{$Tname}</b></td>
                <td><b>{$Tactions}</b><td>
            </tr>
            {section name=links loop=$Linksid}
                <tr>
                    <td>{$Linksfile[links]}</td>
                    <td>{$Linkstext[links]}</td>
                    <td>
                        <a href="account.php?view=links&amp;step=edit&amp;lid={$Linksid[links]}">{$Aedit}</a><br />
                        <a href="account.php?view=links&amp;step=delete&amp;lid={$Linksid[links]}">{$Adelete}</a>
                    </td>
                </tr>
            {/section}
        </table>
    {/if}<br /><br />
    <form method="post" action="account.php?view=links&amp;step=edit&amp;lid={$Linkid}&amp;action=change">
        {$Tfile}: <input type="text" name="linkadress" size="20" value="{$Linkfile}" /><br />
        {$Tname}: <input type="text" name="linkname" size="20" value="{$Linkname}" /><br />
        <input type="submit" value="{$Aform}" />
    </form>
{/if}

{if $View == "bugtrack"}
    {$Bugtrackinfo}<br /><br />
    <table align="center">
        <tr>
            <td><b>{$Bugid}</b></td>
            <td><b>{$Bugtype}</b></td>
            <td><b>{$Bugloc}</b></td>
            <td><b>{$Bugname}</b></td>
        </tr>
        {section name=bugtrack loop=$Bugsid}
            <tr>
                <td align="center">{$Bugsid[bugtrack]}</td>
                <td align="center">{$Bugstype[bugtrack]}</td>
                <td align="center">{$Bugsloc[bugtrack]}</td>
                <td align="center">{$Bugsname[bugtrack]}</td>
            </tr>
        {/section}
    </table>
{/if}

{if $View == "bugreport"}
    {$Buginfo}<br /><br />
    <form method="post" action="account.php?view=bugreport&amp;step=report">
        {$Bugname}: <input type="text" name="bugtitle" size="40" /><br /><br />
        {$Bugtype}: <select name="type">
            <option value="text">{$Bugtext}</option>
            <option value="code">{$Bugcode}</option>
        </select><br /><br />
        {$Bugloc}: <input type="text" name="location" size="40" /><br /><br />
        {$Bugdesc}: <textarea name="desc" rows="13" cols="50"></textarea><br /><br />
        <input type="submit" value="{$Areport}" />
    </form>
{/if}

{if $View == "changes"}
    {$Changesinfo}<br />
    <ul{if $Graphstyle == "Y"} class="sword"{/if}>
        {section name=changes loop=$Changedate}
        <li>{$Changedate[changes]} {$Changeauthor[changes]}<br />
            {$Changeloc}: {$Changelocation[changes]}<br />
            {$Changetext[changes]}<br /><br />
        </li>
        {/section}
    </ul>
{/if}

{if $View == "options"}
    {$Toptions}<br /><br />
    <form method="post" action="account.php?view=options&amp;step=options">
        <input type="checkbox" name="battleloga" {$Checked} /> {$smarty.const.T_BATTLELOGA}<br />
        <input type="checkbox" name="battlelogd" {$Checked2} /> {$smarty.const.T_BATTLELOGD}<br /><br />
        <input type="checkbox" name="graphbar" {$Checked3} /> {$smarty.const.T_GRAPHBAR}<br />
        <input type="checkbox" name="overlib" {$Checked4} /> {$smarty.const.T_OVERLIB}<br /><br />
        <input type="checkbox" name="loginfo" {$Checked5} /> {$smarty.const.T_LOGINFO}<br />
        <input type="checkbox" name="mailinfo" {$Checked6} /> {$smarty.const.T_MAILINFO}<br /><br />
        <input type="submit" value="{$Anext}" />
    </form>
    {if $Step == "options"}
        {$Message}
    {/if}
{/if}

{if $View == "freeze"}
    {$Freezeinfo}<br /><br />
    <form method="post" action="account.php?view=freeze&amp;step=freeze">
        {$Howmany}: <input type="tezt" name="amount" size="5" /><br />
        <input type="submit" value="{$Afreeze2}" />
    </form>
    {if $Step == "freeze"}
        {$Message}
    {/if}
{/if}

{if $View == "lang"}
    {$Langinfo}<br /><br />
    <form method="post" action="account.php?view=lang&amp;step=lang">
    {$Flang} <select name="mainlang">
    {section name=account2 loop=$Lang}
        <option value="{$Lang[account2]}">{$Lang[account2]}</option>
    {/section}
    </select><br />
    {$Slang} <select name="seclang">
    {section name=account3 loop=$Lang}
        <option value="{$Lang[account3]}">{$Lang[account3]}</option>
    {/section}
    </select><br />
    <input type="submit" value="{$Aselect}" />
    </form>
    {if $Step == "lang"}
        {$Message}
    {/if}
{/if}

{if $View == 'immu'}
	{$Immuinfo}
	{if $Showoption == 'Y'}
		{if $Immunity == 'N'}
			{$smarty.const.QUESTION1}<br />
			<form method="post" action="account.php?view=immu&amp;step=take">
			<input type="submit" value="{$smarty.const.YES}" /> </form>
			<form method="post" action="account.php">
			<input type="submit" value="{$smarty.const.NO}" /> </form>
		{/if}
		{if $Immunity == 'Y'}
			{$smarty.const.DISCARD_INFO} {$smarty.const.QUESTION2}<br />
			<form method="post" action="account.php?view=immu&amp;step=discard">
			<input type="submit" value="{$smarty.const.YES}" /> </form>
			<form method="post" action="account.php">
			<input type="submit" value="{$smarty.const.NO}" /> </form>
		{/if}
	{/if}
	{if $Step == 'take'}
		<br />{$smarty.const.IMMU_SELECT} {$smarty.const.CLICK1} <a href="account.php">{$smarty.const.HERE}</a>{$smarty.const.CLICK2}
	{/if}
	{if $Step == 'discard'}
		<br />{$smarty.const.DISCARDED} {$smarty.const.CLICK1} <a href="account.php">{$smarty.const.HERE}</a>{$smarty.const.CLICK2}
	{/if}
{/if}

{if $View == "reset"}
    {$Resetinfo}?
    <ul{if $Graphstyle == "Y"} class="sword"{/if}>
    <li><a href="account.php?view=reset&amp;step=make">{$Yes}</a></li>
    <li><a href="account.php">{$No}</a></li>
    </ul>
    {if $Step == "make"}
        {$Resetselect}<br />
    {/if}
{/if}

{if $View == "avatar"}
    {$Avatarinfo}<br /><br />
    {if $Avatar != ""}
    <center><br /><br /><img alt="img_avatar" src="{$Avatar}">
      <form action="account.php?view=avatar&amp;step=usun" method="post">
    <input type="hidden" name="av" value="{$Value}" />
    <input type="submit" value="{$Delete}" /></form></center>
    {/if}
    <form enctype="multipart/form-data" action="account.php?view=avatar&amp;step=dodaj" method="post">
    <input type="hidden" name="MAX_FILE_SIZE" value="10240" />
    {$Afilename}: <input name="plik" type="file" /><br />
    <input type="submit" value="{$Aselect}" /></form>
{/if}

{if $View == "name"}
    <form method="post" action="account.php?view=name&amp;step=name">
    <input type="submit" value="{$Change}" /> {$Myname} <input type="text" name="name" value="{$OldNick}"/>
    </form>
{/if}

{if $View == "pass"}
    {$smarty.const.PASS_INFO}<br />
    <form method="post" action="account.php?view=pass&amp;step=cp">
    <table>
    <tr><td>{$smarty.const.OLD_PASS}:</td><td><input type="password" name="cp" /></td></tr>
    <tr><td>{$smarty.const.NEW_PASS}:</td><td><input type="password" name="np" /></td></tr>
    <tr><td colspan=2 align=center><input type="submit" value="{$smarty.const.CHANGE}" /></td></tr>
    </table>
    </form>
{/if}

{if $View == "profile"}
    {if $Step == ""}
    {$Profileinfo}<br /><br />
    <form method="post" action="account.php?view=profile&amp;step=profile">
    <table align="center">
    <tr><td align="center">{$Newprofile}:<br /> <textarea name="newprofile" id="profile" rows="20" cols="65">{$Editable}</textarea></td></tr>
    <tr><td colspan="2" align="center"><input type="submit" value="{$Change}" /></td></tr>
    </table>
    </form>
    {/if}
    {if $Step == "profile"}
    <table align="center">
    <tr><td>{$Newprofile2}:</td><td></td></tr>
    <tr><td><div class="overflow">{$Profile}</div></td><tr>
    </table>
    {/if}
{/if}

{if $View == "eci"}
    <form method="post" action="account.php?view=eci&amp;step=ce">
    <table>
    <tr><td>{$Oldemail}:</td><td><input type="text" name="ce" /></td></tr>
    <tr><td>{$Newemail}:</td><td><input type="text" name="ne" /></td></tr>
    <tr><td colspan="2" align="center"><input type="submit" value="{$Change}" /></td></tr>
    </table>
    </form>
    <form method="post" action="account.php?view=eci&amp;step=gg">
    <table>
    <tr><td>{$Tcommunicator}:</td><td><select name="communicator">
        {section name=acccom loop=$Tcom}
            <option value="{$Comm[acccom]}">{$Tcom[acccom]}</option>
        {/section}
    </select></td></tr>
    <tr><td>{$Newgg}:</td><td><input type="text" name="gg" /></td></tr>
    <tr><td colspan="2" align="center"><input type="submit" name="Change" value="{$Change}" />
	<input type="submit" name="Delete" value="{$Delete}" /></td></tr>
   </table>
   </form>
{/if}

{if $View == "style"}
    {if $Stylename != ''}
    <form method="post" action="account.php?view=style&amp;step=style">
    <table>
    <tr>
    <td><input type="submit" value="{$Sselect}" /> {$Textstyle}:</td>
    </tr>
    <tr>
    <td><select name="newstyle">
    {section name=account loop=$Stylename}
        <option value="{$Stylename[account]}">{$Stylename[account]}</option>
    {/section}
    </select>
    </table></form>
    <br /><br />
    {/if}
    {if $Layoutname != ''}
    <form method="post" action="account.php?view=style&amp;step=graph">
    <table>
    <tr>
    <td><input type="submit" value="{$Sselect}" /> {$Graph_text}:</td>
    </tr>
    <tr>
    <td>
    <select name="graphserver">
    {section name=account1 loop=$Layoutname}
        <option value="{$Layoutname[account1]}">{$Layoutname[account1]}</option>
    {/section}
    </select>
    </table></form>
    {/if}
    <br /><br />
    <form method="post" action="account.php?view=style&amp;step=graphstyle">
    <table>
    <tr><td><input type="checkbox" name="graphstyle" id="graphfree" {$Checked} /><label for="graphfree"> {$Graphless}</label></td></tr>
    <tr><td><input type="submit" value="{$Sselect}" /></td></tr>
    </table>
    </form>
    {if $Step == "style" || $Step == "graph" || $Step == "graphstyle"}
       {$Youchange}. (<a href="account.php">{$Refresh}</a>)
    {/if}
{/if}

{if $View == "description"}
    <form method="post" action="account.php?view=description&amp;step=change">
    <table>
    <tr><td>{$opistext}</td></tr>
    <tr><td align="center">{$Newpodpis}<br /> <textarea name="opis" id="opis" rows="2" cols="38">{$Opis}</textarea></td></tr>
    <tr><td colspan="2" align="center"><input type="submit" value="{$Change}" /></td></tr>
    </table>
    </form>
    {if $Step == "change"}
    <table>
    <tr><td>{$Newopis2}:</td><td></td></tr>
    <tr><td><b>{$opis}</b></td><tr>
    </table>
    {/if}
{/if}

{if $View == "signatures"}
    {$head_text}
<div id="signatures">
{section name=type loop=$Types}
    <div class="center"><a href="{$GameAddress}"><img class="sigs" src="http://{$GameAddress}/sign.php?type={$Types[type]}&amp;id={$pid}" alt="" /></a></div><br />
    <input id="{$Types[type]}-1" readonly="readonly" size="55" value="[URL={$GameAddress}][IMG]{$GameAddress}/sign.php?type={$Types[type]}&amp;id={$pid}[/IMG][/URL]" /><label for="{$Types[type]}-1">BBCode(1)</label><br />
    <input id="{$Types[type]}-2" readonly="readonly" size="55" value="[url={$GameAddress}][img={$GameAddress}/sign.php?type={$Types[type]}&amp;id={$pid}][/url]" /><label for="{$Types[type]}-2">BBCode(2)</label><br />
    <input id="{$Types[type]}-3" readonly="readonly" size="55" value="<a href=&quot;{$GameAddress}&quot;><img src=&quot;{$GameAddress}/sign.php?type={$Types[type]}&amp;id={$pid}&quot; border=&quot;0&quot; alt=&quot;&quot; /></a>" /><label for="{$Types[type]}-3">HTML</label><br />
    <input id="{$Types[type]}-4" readonly="readonly" size="55" value="{$GameAddress}/sign.php?type={$Types[type]}&amp;id={$pid}" /><label for="{$Types[type]}-4"></label for="{$Types[type]}-3">URL</label><br /><br />
{/section}
</div>
{/if}

{/strip}
