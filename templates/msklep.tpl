{strip}
{if $Buy == ""}
    <br />{$Pwelcome}<br /><br />
    <table width="100%">
    <tr><td width="45%"><b><u>{$Nname}</u></b></td>
    <td width="25%"><b><u>{$Nefect}</u></b></td>
    <td width="10%"><b><u>{$Ncost}</u></b></td>
    <td width="10%"><b><u>{$Namount}</u></b></td>
    <td width="10%"><b><u>{$Poption}</u></b></td></tr>
    {section name=msklep loop=$Pid}
        <tr>
		    <td>{$Pname[msklep]}({$Npower}:{$Ppower[msklep]})</td>
            <td>{$Pefect[msklep]}</td>
            <td>{$Pcost[msklep]}</td>
            <td>{$Pamount[msklep]}</td>
            <td>- <a href="msklep.php?buy={$Pid[msklep]}">{$Abuy}</a></td>
        </tr>
    {/section}
    </table>
{/if}

{if $Buy != ""}
    <br /><form method="post" action="msklep.php?buy={$Pid}&amp;step=buy">
    <input type="submit" value="{$Abuy}" /> <input type="text" name="amount" value="1" size="5" /> {$Pamount} {$Name}</form>
{/if}
{/strip}