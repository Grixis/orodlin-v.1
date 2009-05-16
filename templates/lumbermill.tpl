{strip}
{if $Mill == ''}
    <p>{$Millinfo}</p>
    <ul>
        <li><a href="lumbermill.php?mill=plany">{$Aplans}</a></li>
        <li><a href="lumbermill.php?mill=mill">{$Amill}</a></li>
        <li><a href="lumbermill.php?mill=licenses">{$Alicenses}</a></li>
        <li><a href="lumbermill.php?mill=astral">{$Aastral}</a></li>
    </ul>
{/if}

{if $Mill == 'licenses'}
     {if $Step == 'buy'}
        {$Message}
    {else}
        <a href="lumbermill.php?mill=licenses&amp;step=buy">{$Alicense}</a>
    {/if}
{/if}

{if $Mill == 'plany'}
    {if $Buy == ''}
        {$Hereis}:
        <table>
        <tr>
            <th width="50%"><u>{$Iname}</u></th>
            <th><u>{$Icost}</u></th>
            <th><u>{$Ilevel}</u></th>
            <th><u>{$Ioption}</u></th>
        </tr>
        {section name=mill loop=$Name}
        <tr>
            <td>{$Name[mill]}</td>
            <td>{$Cost[mill]}</td>
            <td>{$Level[mill]}</td>
            <td>- <a href="lumbermill.php?mill=plany&amp;buy={$Planid[mill]}">{$Abuy}</a></td>
        </tr>
        {/section}
        </table>
    {else}
        {$Youpay} <b>{$Cost1}</b> {$Andbuy}: <b>{$Name1}</b>.
    {/if}
{/if}

{if $Mill == 'mill'}
    {if $Make == '' && $Continue == ''}
        {$Hereis}
        {if isset($Info2)}
            <p>{$Info2}:</p>
            <p>{$Iname}: <a href="lumbermill.php?mill=mill&amp;ko={$ContinueId}">{$ContinueName}</a><br />
                {$Ipercent}: {$ContinuePercent}<br />
                {$Ienergy}: {$ContinueNeed}
            </p>
        {/if}
        <table>
            <tr>
               <th><u>{$Iname}</u></th>
               <th><u>{$Ilevel}</u></th>
               <th><u>{$Icost}</u></th>
            </tr>
            {section name=mill loop=$Name}
             <tr>
                <td><a href="lumbermill.php?mill=mill&amp;dalej={$Planid[mill]}">{$Name[mill]}</a></td>
                <td>{$Level[mill]}</td>
                <td>{$Cost[mill]}</td>
             </tr>
             {/section}
        </table>
        {if $Cont != ''}
            <form method="post" action="lumbermill.php?mill=mill&amp;konty={$Id}">
            {$Assignen} <b>{$Name}</b> <input type="text" name="razy" size="5" /> {$Menergy}
            <input type="submit" value="{$Amake}" /></form>
        {/if}
        {if $Next != ''}
            <form method="post" action="lumbermill.php?mill=mill&amp;rob={$Id}">
                {$Assignen} <b>{$Name}</b> <input type="text" name="razy" size="5" /> {$Menergy}
                <input type=submit value="{$Amake}" />
                {if $Type == "B"}
                    <select name="lumber">
                        <option value="H">{$Lhazel}</option>
                        <option value="Y">{$Lyew}</option>
                        <option value="E">{$Lelm}</option>
                        <option value="A">{$Lharder}</option>
                        <option value="C">{$Lcomposite}</option>
                    </select>
                {/if}
            </form>
        {/if}
    {/if}
    {if $Continue != '' || $Make !=''}
        {$Message}
    {/if}
{/if}

{if $Mill == 'astral'}
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
            <form method="post" action="lumbermill.php?mill=astral&amp;component={$Compnumber[astral]}">
                <input type="submit" value="{$Abuild}" />
            </form></p>
        {/section}
    {/if}
{/if}

{if $Mill!=''}
    <p><a href="lumbermill.php">({$Aback})</a></p>
{/if}
{/strip}