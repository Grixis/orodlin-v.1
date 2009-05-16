{strip}
{if ($Location == "Altara" || $Location == "Ardulith") && $Prisoner == ""}
    {$Jailinfo}<br /><br /><br />
    {if $Number > "0"}
        {section name=jail loop=$Name}
        <table width="100%">
            <tr><td width="40%"><b>{$Pname}:</b> </td><td width="70%"> <a href="view.php?view={$Id[jail]}">{$Name[jail]}</a></td></tr>
            <tr><td><b>{$Pid}:</b> </td><td> {$Id[jail]}</td></tr>
            <tr><td><b>{$Pdate}:</b> </td><td> {$Date[jail]}</td></tr>
            <tr><td><b>{$Preason}:</b> </td><td> {$Verdict[jail]}</td></tr>
            <tr><td><b>{$Pduration}:</b> </td><td> {$Duration[jail]} ({$Duration2[jail]} {$Pduration2})</td></tr>
            <tr><td><b>{$Pcost}:</b> </td><td> <a href=jail.php?prisoner={$Jailid[jail]}>{$Cost[jail]} {$Goldcoins}</a></td></tr>
        </table><br /><br />
        {/section}
    {/if}
    {if $Number == "0"}
        <center>{$Noprisoners}</center>
    {/if}
{/if}

{if $Location == "Lochy"}
    {$Youare}<br />
    <b>{$Pdate}:</b> {$Date}<br />
    <b>{$Pduration}:</b> {$Duration} ({$Duration2} {$Pduration2})<br />
    <b>{$Preason}:</b> {$Verdict}<br />
    <b>{$Pcost}:</b> {$Cost}<br />
{/if}

{if $Prisoner != ""}
    {if $Step == ""}
        {$Youwant}<b>{$Prisonername}</b>?
        <form method="post" action="jail.php?prisoner={$Prisoner}&step=confirm">
            <input type="submit" value="{$Ayes}" />
        </form>
        <br />
        (<a href="jail.php">{$Aback}</a>)
    {/if}
{/if}

{/strip}