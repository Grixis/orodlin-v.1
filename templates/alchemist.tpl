{strip}
{if $Alchemist == ''}
    <p>{$Awelcome}</p>
    <ul>
        <li><a href="alchemik.php?alchemik=przepisy">{$Arecipes}</a></li>
        <li><a href="alchemik.php?alchemik=pracownia">{$Amake}</a></li>
        <li><a href="alchemik.php?alchemik=astral">{$Aastral}</a></li>
    </ul>
{/if}

{if $Alchemist == "przepisy"}
    {if $Buy==''}
    {$Recipesinfo}
    <table>
        <tr>
            <th><u>{$Iname}</u></th>
            <th><u>{$Icost}</u></th>
            <th><u>{$Ilevel}</u></th>
            <th><u>{$Ioption}</u></th>
        </tr>
        {section name=alchemy loop=$Name}
        <tr>
            <td>{$Name[alchemy]}</td>
            <td>{$Cost[alchemy]}</td>
            <td>{$Level[alchemy]}</td>
            <td>- <a href="alchemik.php?alchemik=przepisy&amp;buy={$Planid[alchemy]}">{$Abuy}</a></td>
        </tr>
        {/section}
    </table>
    {else}
        {$Youpay} <b>{$Cost1}</b> {$Andbuy}: <b>{$Name1}</b>.
    {/if}


{/if}

{if $Alchemist == "pracownia"}
    {if $Make == 0}
        {$Alchemistinfo}
        <table width="100%">
        <tr>
        <td width="50%"><b><u>{$Rname}</u></b></td>
        <td width="10%"><b><u>{$Rlevel}</u></b></td>
        <td width="10%"><b><u>{$Rillani}</u></b></td>
        <td width="10%"><b><u>{$Rillanias}</u></b></td>
        <td width="10%"><b><u>{$Rnutari}</u></b></td>
        <td width="10%"><b><u>{$Rdynallca}</u></b></td>
        </tr>
        {section name=number loop=$Name}
            <tr>
            <td><a href="alchemik.php?alchemik=pracownia&amp;dalej={$Id[number]}">{$Name[number]}</a></td>
            <td align="center">{$Level[number]}</td>
            <td align="center">{$Illani[number]}</td>
            <td align="center">{$Illanias[number]}</td>
            <td align="center">{$Nutari[number]}</td>
            <td align="center">{$Dynallca[number]}</td>
            </tr>
        {/section}
        </table>
    {/if}
    {if $Next != 0}
        <form method="post" action="alchemik.php?alchemik=pracownia&amp;rob={$Id1}">
        {$Pstart} <b>{$Name1}</b> <input type="text" name="razy" size="5" /> {$Pamount}.
        <input type="submit" value="{$Amake}" /></form>
    {/if}
    {if $Make != 0}
        {$Youmake} <b>{$Name}</b> <b>{$Amount}</b> {$Pgain} <b>{$Exp}</b> {$Exp_and} <b>{$Ability}</b> {$Alchemylevel}<br />
    {/if}
{/if}

{if $Alchemist == 'astral'}
    {if isset($Astralinfo)}
        <p>{$Astralinfo}</p>
    {/if}
    <p>{$Message}</p>
    {if isset($Available)}
        {section name=astral loop=$Available}
            <p><b>{$Tname}:</b> {$Available[astral]}
            {section name=astral2 loop=$Resourcename}
                <br /><b>{$Resourcename[astral2]}:</b> {$Resourceamount[astral][astral2]}
            {/section}
            <form method="post" action="alchemik.php?alchemik=astral&amp;potion={$Compnumber[astral]}">
                <input type="submit" value="{$Abuild}" />
            </form></p>
        {/section}
    {/if}
{/if}

{if $Alchemist != ''}
<br /><br /><a href="alchemik.php">({$Back})</a>
{/if}

{/strip}