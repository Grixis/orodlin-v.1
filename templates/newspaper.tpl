{strip}
{if $Step == "" && $Read == "" && $Comments == "" && $Step3 == ""}
    {$Paperinfo} {$Gamename} {$Paperinfo2}<br />
    {$Paperinfo3}<br /><br /><br />

		<ul {if $Graphstyle=="Y"}class="sword"{/if}>
                <li><a href="newspaper.php?step=new&page=contents">{$Anewpaper}</a></li>
                <li><a href="newspaper.php?step=archive">{$Aarchive}</a></li>
                <li><a href="newspaper.php?step=mail">{$Aredmail}</a></li><br />
                {if $Rank == "Admin" || $Rank == "Redaktor"}
                    <li><a href="newspaper.php?step=redaction">{$Aredaction}</a></li>
                {/if}
		</ul>
{/if}

{if $Article != ""}
<div class="art">
<div class="arthead">{$Ttitle} <span class="arttitle">{$Arttitle}</span> ({$Artauthor}) {if $Step3 == "S" && ($Rank == "Admin" || $Rank == "Redaktor")} (<a href="newspaper.php?step=redaction&amp;step3=edit&amp;edit={$Artid}">{$Aedit}</a>) (<a href="newspaper.php?step=redaction&amp;step3=delete&amp;del={$Artid}">{$Adelete}</a>){/if}
</div>
<div class="artbody">{$Artbody}</div>
{if $Step3 == ""} <div class="comment"><a href="newspaper.php?{$Newslink}&amp;comments={$Artid}">{$Acomment}</a> ({$Twrite} {$Artcomments} {$Tcomments})</div>{/if}</div>
{/if}

{if $Comments != ""}
    {if $Amount == "0"}
        {$Nocomments}
    {/if}
    {if $Amount > "0"}
        <div class="comments">{section name=newspaper3 loop=$Tauthor}
            <div class="overflow"><b>{$Tauthor[newspaper3]}</b> {if $Tdate[newspaper3] != ""} ({$Tdate[newspaper3]}) {/if}{$Wrote}: {if $Rank == "Admin" || $Rank == "Redaktor"} (<a href="newspaper.php?comments={$Comments}&amp;action=delete&amp;cid={$Cid[newspaper3]}">{$Adelete}</a>){/if}<br />
            {$Tbody[newspaper3]}</div><br />
        {/section}</div>
    {/if}
    <br /><br />
    <div class="addcomment"><form method="post" action="newspaper.php?comments={$Comments}&amp;action=add">
        {$Addcomment}:<br /><textarea name="body" rows="10" cols="50"></textarea><br />
        <input type="hidden" name="tid" value="{$Comments}" />
        <input type="submit" value="{$Aadd}" />
    </form></div><br />
{/if}

{if $Step == "new" || $Read != "" || $Step3 == 'S'}
    {if $Page == "contents"}
    <div class="readinfo">{$Readinfo}</div><br />
        <div class="paperbodycolumnleft">
            {section name=sec1 loop=$Arttypes max=6}
                {if $Artidm[sec1][0] != "0"}
                    <div class="sec"><div class="secname">{$Secnames[sec1]}</div>
                    <div class="article">
                        {section name=art1 loop=$Artidm[sec1]}
                            "<span class="articletitle"><a href="newspaper.php?{$Newslink}&amp;article={$Artidm[sec1][art1]}">{$Arttitlem[sec1][art1]}</a></span>" <span class="articlefooter">({$Aauthor}: {$Artauthorm[sec1][art1]}) ({$Acomments}: <a href="newspaper.php?step=new&amp;comments={$Artidm[sec1][art1]}">{$Artcoment[sec1][art1]}</a>)</span> <br />
                        {/section}
                        </div></div>
                    {/if}
                {/section}
            </div>
        	<div class="paperbodycolumnright">
                {section name=sec2 loop=$Arttypes start=6}
                    {if $Artidm[sec2][0] != "0"}
                        <div class="sec"><div class="secname">{$Secnames[sec2]}</div>
                        <div class="article">
                        {section name=art1 loop=$Artidm[sec2]}
                            "<span class="articletitle"><a href="newspaper.php?{$Newslink}&amp;article={$Artidm[sec2][art1]}">{$Arttitlem[sec2][art1]}</a></span>" <span class="articlefooter">({$Aauthor}: {$Artauthorm[sec2][art1]})  ({$Acomments}: <a href="newspaper.php?step=new&amp;comments={$Artidm[sec2][art1]}">{$Artcoment[sec2][art1]}</a>)</span> <br />
                        {/section}
                        </div></div>
                    {/if}
                {/section}
            </div>
    {/if}


    {if $Step3 == "S" && $Article == ""}
        <div class="paperfooter"><form method="post" action="newspaper.php?step=redaction&amp;step3=release">
            <input type="submit" value="{$Apublic}" />
        </form></div><br />
    {/if}
	<div class="paperfooter">
    <br />{if $Pageid != "" || $Pageid2 != ""}

<div class="prevnext">
<form method="post" action="newspaper.php?{$Newslink}&amp;article={$Pageid2}">{$Previous}</form>
<form method="post" action="newspaper.php?{$Newslink}&amp;article={$Pageid}">{$Next}</form>
</div>
    {/if}<br />
<a href="newspaper.php?{$Newslink}&amp;page=contents">{$Acontents}</a><br />
<a href="newspaper.php">{$Aend}</a></div><br />
{/if}

{if $Step == "archive"}
    {$Archiveinfo}<br /><br />
    {if $Paperid > "0"}
	<ul class="sword">
        {section name=newspaper2 loop=$Paperid}
            <li><a href="newspaper.php?read={$Paperid[newspaper2]}&amp;page=contents">{$Anumber} {$Paperid[newspaper2]}</a></li>
        {/section}
	</ul>
    {/if}
{/if}

{if $Step == 'redaction'}
    {if $Step3 == ""}
        {$Redactioninfo} {$Gamename}.<br /><br />
        	<ul class="sword">
                    <li><a href="newspaper.php?step=redaction&amp;step3=S&amp;page=contents">{$Ashow}</a></li>
                    <li><a href="newspaper.php?step=redaction&amp;step3=R">{$Aredaction}</a></li>
		</ul>
    {/if}
    {if $Step3 == "edit" || $Step3 == "R"}
        {$Youedit}:<br />
        {$Showmail}<br />
        <form method="post" action="newspaper.php?step=redaction&amp;{if $Step3 == 'edit'}step3=edit&amp;edit={$Edit}{/if}{if $Step3 == "R"}step3=R{/if}">
            {$Mailtype}: <select name="mail">
                {section name=edit loop=$Arttypes}
                <option value="{$Arttypes[edit]}"{if $Mtype == "$Arttypes[edit]"} selected {/if}>{$Sectionnames[edit]}</option>
                {/section}
            </select><br />
            {$Ttitle} <input type="text" name="mtitle" value="{$Mtitle}" /><br />
            <textarea name="mbody" rows="20" cols="65">{$Mbody}</textarea><br />
            <input type="submit" value="{$Ashow}" name="show" /> <input type="submit" name="sendmail" value="{$Asend}" />
        </form>
    {/if}
    {$Message}
{/if}

{if $Step == "mail"}
    {$Mailinfo}<br />
    {$Showmail}<br />
    <form method="post" action="newspaper.php?step=mail&amp;step3=add">
        {$Mailtype}: <select name="mail">
            <option value="M" {if $Mtype == "M"} selected {/if}>{$Anews}</option>
            <option value="N" {if $Mtype == "N"} selected {/if}>{$Anews2}</option>
            <option value="O" {if $Mtype == "O"} selected {/if}>{$Acourt}</option>
            <option value="R" {if $Mtype == "R"} selected {/if}>{$Aroyal}</option>
            {if $Rank == "Admin"}
                <option value="K" {if $Mtype == "K"} selected {/if}>{$Aking}</option>
            {/if}
            <option value="C" {if $Mtype == "C"} selected {/if}>{$Achronicle}</option>
            <option value="S" {if $Mtype == "S"} selected {/if}>{$Asensations}</option>
            <option value="H" {if $Mtype == "H"} selected {/if}>{$Ahumor}</option>
            <option value="I" {if $Mtype == "I"} selected {/if}>{$Ainter}</option>
            <option value="A" {if $Mtype == "A"} selected {/if}>{$Anews3}</option>
            <option value="P" {if $Mtype == "P"} selected {/if}>{$Apoetry}</option>
        </select><br />
        {$Ttitle} <input type="text" name="mtitCle" value="{$Mtitle}" /><br />
        <textarea name="mbody" rows="20" cols="65">{$Mbody}</textarea><br />
        <input type="submit" value="{$Ashow}" name="show" /> <input type="submit" name="sendmail" value="{$Asend}" />
    </form>
    {$Message}
{/if}

{if (($Step != "" && $Step != "new") || ($Comments != "" && $Step == "") || $Step3 != "") && $Article == ""}
    <br /><a href="newspaper.php">{$Aback}</a>
{/if}
{/strip}
