{strip}
{if $Smith == ''}
    <p>{$Smithinfo}</p>
    <ul{if $Graphstyle == "Y"} class="sword"{/if}>
        <li><a href="kowal.php?kowal=plany">{$Aplans}</a></li>
        <li><a href="kowal.php?kowal=kuznia">{$Asmith}</a></li>
        <li><a href="kowal.php?kowal=astral">{$Aastral}</a></li>
    </ul>
{/if}

{if $Smith == "plany"}
    {$Hereis}<br />
    <ul{if $Graphstyle == "Y"} class="sword"{/if}>
    <li><a href="kowal.php?kowal=plany&amp;dalej=W">{$Aplansw}</a></li>
    <li><a href="kowal.php?kowal=plany&amp;dalej=A">{$Aplansa}</a></li>
    <li><a href="kowal.php?kowal=plany&amp;dalej=S">{$Aplanss}</a></li>
    <li><a href="kowal.php?kowal=plany&amp;dalej=H">{$Aplansh}</a></li>
    <li><a href="kowal.php?kowal=plany&amp;dalej=L">{$Aplansl}</a></li>
    </ul>
    {if $Next != ""}
        <table>
        <tr>
            <th width="50%"><u>{$Iname}</u></th>
            <th><u>{$Icost}</u></th>
            <th><u>{$Ilevel}</u></th>
            <th><u>{$Ioption}</u></th>
        </tr>
        {section name=smith loop=$Name}
        <tr>
            <td>{$Name[smith]}</td>
            <td>{$Cost[smith]}</td>
            <td>{$Level[smith]}</td>
            <td>- <a href="kowal.php?kowal=plany&amp;buy={$Planid[smith]}">{$Abuy}</a></td>
        </tr>
        {/section}
        </table>
    {/if}
    {if $Buy != ""}
        {$Youpay} <b>{$Cost1}</b> {$Andbuy}: <b>{$Name1}</b>.
    {/if}
{/if}

{if $Smith == 'kuznia'}
    {if $Make == '' && $Continue == ''}
        {$Hereis}
        <ul{if $Graphstyle == "Y"} class="sword"{/if}>
            <li><a href="kowal.php?kowal=kuznia&amp;type=W">{$Amakew}</a></li>
            <li><a href="kowal.php?kowal=kuznia&amp;type=A">{$Amakea}</a></li>
            <li><a href="kowal.php?kowal=kuznia&amp;type=S">{$Amakes}</a></li>
            <li><a href="kowal.php?kowal=kuznia&amp;type=H">{$Amakeh}</a></li>
            <li><a href="kowal.php?kowal=kuznia&amp;type=L">{$Amakel}</a></li>
        </ul>
        {if isset($Info2)}
            <p>{$Info2} <a href="kowal.php?kowal=kuznia&amp;ko={$ContinueId}">{$ContinueName}</a><br />
               {$Ipercent}: {$ContinuePercent}<br />
                {$Ienergy}: {$ContinueNeed}
            </p>
        {/if}
        {if $Type !=''}
        <table>
            <tr>
               <th><u>{$Iname}</u></th>
               <th><u>{$Ilevel}</u></th>
               <th><u>{$Icost}</u></th>
            </tr>
            {section name=mill loop=$Name}
             <tr>
                <td><a href="kowal.php?kowal=kuznia&amp;dalej={$Planid[mill]}">{$Name[mill]}</a></td>                <td>{$Level[mill]}</td>
                <td>{$Cost[mill]}</td>
             </tr>
             {/section}
        </table>
        {/if}
    {/if}
    {if $Cont != "" || $Next != ""}
        <form method="post" action="{$Link}">
            {$Assignen} <b>{$Name}</b> <input type="text" name="razy" size="5" />{$Senergy}
            <input type="submit" value="{$Amake}" />{if $Next != ""} <b>{$Name}</b> <select name="mineral">
                <option value="copper">{$Mcopper}</option>
                <option value="bronze">{$Mbronze}</option>
                <option value="brass">{$Mbrass}</option>
                <option value="iron">{$Miron}</option>
                <option value="steel">{$Msteel}</option>
            </select>{/if}
        </form>
    {/if}
    {if $Continue != ""}
        {$Message}
    {/if}
    {if $Make != ""}
        {$Message}
    {/if}
{/if}

{if $Smith == 'astral'}
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
            <form method="post" action="kowal.php?kowal=astral&amp;component={$Compnumber[astral]}">
                <input type="submit" value="{$Abuild}" />
            </form></p>
        {/section}
    {/if}
{/if}

{if $Smith != ''}
    <br /><a href="kowal.php">({$Aback})</a>
{/if}

{/strip}
