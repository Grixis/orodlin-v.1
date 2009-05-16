{strip}
{if $Step == ""}
    {$Jewellerinfo}<br /><br />
    <ul{if $Graphstyle == "Y"} class="sword"{/if}>
        <li><a href="jeweller.php?step=plans">{$Aplans}</a></li>
        <li><a href="jeweller.php?step=make">{$Aring}</a></li>
        {if $Playerclass == "Rzemieślnik"}
        <li><a href="jeweller.php?step=make2">{$Amakering}</a></li>
        <li><a href="jeweller.php?step=make3">{$Amakering2}</a></li>
        {/if}
        <li><a href="jeweller.php?step=astral">{$Aastral}</a></li>
    </ul>
{/if}

{if $Step == "plans"}
    {if $Buy == ""}
        {$Hereis}
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
            <td>- <a href="jeweller.php?step=plans&amp;buy={$Planid[mill]}">{$Abuy}</a></td>
        </tr>
        {/section}
        </table>
    {else}
        {$Youpay} <b>{$Cost1}</b> {$Andbuy}: <b>{$Name1}</b>.
    {/if}
{/if}

{if $Step == "make"}
    {$Ringinfo}<br /><br />
    <form method="post" action="jeweller.php?step=make&amp;make=Y">
        <input type="submit" value="{$Amake}" /> <input type="text" name="amount" size="5" /> {$Ramount}
    </form>
{/if}

{if $Step == "make2"}
    {if $Action == ""}
        {$Ringinfo}<br /><br />
        {if $Maked == ""}
            <table>
                <tr>
                    <td><b><u>{$Tname}</u></b></td>
                    <td><b><u>{$Tlevel}</u></b></td>
                    <td><b><u>{$Tadam}</u></b></td>
                    <td><b><u>{$Tcryst}</u></b></td>
                    <td><b><u>{$Tmeteor}</u></b></td>
                    <td><b><u>{$Tenergy}</u></b></td>
                    <td><b><u>{$Tbonus}</u></b></td>
                    <td><b><u>{$Tchange}</u></b></td>
                </tr>
                {section name=rings loop=$Rid}
                <tr>
                    <td><a href="jeweller.php?step=make2&amp;make={$Rid[rings]}">{$Rname[rings]}</a></td>
                    <td align="center">{$Rlevel[rings]}</td>
                    <td align="center">{$Radam[rings]}</td>
                    <td align="center">{$Rcryst[rings]}</td>
                    <td align="center">{$Rmeteor[rings]}</td>
                    <td align="center">{$Renergy[rings]}</td>
                    <td align="center">{$Rbonus[rings]}</td>
                    <td align="center">{$Rchange[rings]}</td>
                </tr>
                {/section}
            </table>
            {if $Make != ""}
                <br /><br />
                <form method="post" action="jeweller.php?step=make2&amp;action=create">
                    <input type="submit" value="{$Youmake}" /> <b>{$Rname2}</b>{if $Change == "Y"} {$Withbon} <select name="bonus">
                    {section name=make loop=$Rbonus2}
                        <option value="{$Rbonus2[make]}">{$Rbonus2[make]}</option>
                    {/section}
                    </select>{/if} {$Ramount} <input type="text" name="amount" size="5" value="0"/> {$Tenergy3}
                    <input type="hidden" value="{$Make}" name="make" />
                </form>
            {/if}
        {else}
            <table>
                <tr>
                    <td><b><u>{$Tname}</u></b></td>
                    <td><b><u>{$Tenergy}</u></b></td>
                    <td><b><u>{$Tenergy2}</u></b></td>
                </tr>
                <tr>
                    <td>{$Rname}</td>
                    <td align="center">{$Renergy}</td>
                    <td align="center">{$Renergy2}</td>
                </tr>
            </table>
            <br /><br />
            <form method="post" action="jeweller.php?step=make2&amp;action=continue">
                <input type="submit" value="{$Youcontinue}" /> <b>{$Rname}</b> {$Ramount} <input type="text" name="amount" size="5" value="0"/> {$Tenergy3}
                <input type="hidden" value="{$Maked}" name="make" />
            </form>
        {/if}
    {/if}
{/if}

{if $Step == "make3"}
    {$Ringinfo}<br /><br />
    {if $Maked == ""}
        <table align="center">
            <tr>
                <td><b><u>{$Tname}</u></b></td>
                <td><b><u>{$Tlevel}</u></b></td>
                <td><b><u>{$Tmeteor}</u></b></td>
                <td><b><u>{$Tenergy}</u></b></td>
            </tr>
            {section name=rings2 loop=$Rname}
            <tr>
                <td>{$Rname[rings2]}</td>
                <td align="center">{$Rlevel[rings2]}</td>
                <td align="center">{$Rmeteor[rings2]}</td>
                <td align="center">{$Renergy[rings2]}</td>
            </tr>
            {/section}
        </table>
        <br /><br />
        <form method="post" action="jeweller.php?step=make3&amp;action=create">
            <input type="submit" value="{$Amake}" /> <select name="rings">
            {section name=rings3 loop=$Rid2}
                <option value="{$Rid2[rings3]}">{$Rname2[rings3]} +{$Rpower[rings3]} {$Ramount3} {$Ramount2[rings3]}</option>
            {/section}
            </select> {$Onspecial} {$Ramount4} <input type="text" name="amount" value="0" size="5" /> {$Renergy2}
        </form>
    {else}
        <table>
            <tr>
                <td><b><u>{$Tname}</u></b></td>
                <td><b><u>{$Tenergy}</u></b></td>
                <td><b><u>{$Tenergy2}</u></b></td>
            </tr>
            <tr>
                <td>{$Rname}</td>
                <td align="center">{$Renergy}</td>
                <td align="center">{$Renergy2}</td>
            </tr>
        </table>
        <br /><br />
        <form method="post" action="jeweller.php?step=make3&amp;action=continue">
            <input type="submit" value="{$Youcontinue}" /> <b>{$Rname}</b> {$Ramount} <input type="text" name="amount" size="5" value="0"/> {$Tenergy3}
            <input type="hidden" value="{$Maked}" name="make" />
        </form>
    {/if}
{/if}

{if $Step == 'astral'}
    {if isset($Astralinfo)}
        <p>{$Astralinfo}</p>
    {/if}
    {if isset($Available)}
        {section name=astral loop=$Available}
            <p><b>{$Tname}:</b> {$Available[astral]}
            {section name=astral2 loop=$Resourcename}
                <br /><b>{$Resourcename[astral2]}:</b> {$Resourceamount[astral][astral2]}
            {/section}
            <form method="post" action="jeweller.php?step=astral&amp;component={$Compnumber[astral]}">
                <input type="submit" value="{$Abuild}" />
            </form></p>
        {/section}
    {/if}
{/if}

{if $Step != ''}
    <p>{$Message}</p>
    <p><a href="jeweller.php">{$Aback}</a></p>
{/if}
{/strip}
