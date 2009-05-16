{strip}
{if $Action == "" && $Battle == ""}
    <p>{$Battleinfo}</p>
    <ul{if $Graphstyle == "Y"} class="sword"{/if}>
    <li><a href="battle.php?action=levellist">{$Ashowlevel}</a></li>
    <li><a href="battle.php?action=showalive">{$Ashowalive}</a></li>
    <li><a href="battle.php?action=monster">{$Ashowmonster}</a></li>
    </ul>
{/if}

{if $Action == "showalive"}
    {$Showinfo} {$Level}...<br /><br />
    <table width="100%">
    <tr>
    <td width="10%"><b><u>{$Lid}</u></b></td>
    <td width="45%"><b><u>{$Lname}</u></b></td>
    <td width="25%"><b><u>{$Lrank}</u></b></td>
    <td width="10%"><b><u>{$Lclan}</u></b></td>
    <td width="10%"><b><u>{$Loption}</u></b></td>
    </tr>
    {section name=player loop=$Enemyid}
        <tr>
        <td>{$Enemyid[player]}</td>
        <td><a href="view.php?view={$Enemyid[player]}">{$Enemyname[player]}</a></td>
        <td>{$Enemyrank[player]}</td>
        <td>{$Enemytribe[player]}</td>
        <td>- <A href="battle.php?battle={$Enemyid[player]}">{$Aattack}</a></td>
        </tr>
    {/section}
    </table><br />{$Orback} <a href="battle.php">{$Bback}</a>.
{/if}

{if $Action == "levellist"}
    <form method="post" action="battle.php?action=levellist&amp;step=go">
    {$Showall} <input type="text" name="slevel" size="5" /> {$Tolevel} <input type="text" name="elevel" size="5" />
    <input type="submit" value="{$Ago}" /></form>
    {if $Step == "go"}
    <table width="100%">
        <tr>
        <td width="10%"><b><u>{$Lid}</u></b></td>
        <td width="45%"><b><u>{$Lname}</u></b></td>
        <td width="25%"><b><u>{$Lrank}</u></b></td>
        <td width="10%"><b><u>{$Lclan}</u></b></td>
        <td width="10%"><b><u>{$Loption}</u></b></td>
        </tr>
        {section name=player loop=$Enemyid}
            <tr>
            <td>{$Enemyid[player]}</td>
            <td><a href="view.php?view={$Enemyid[player]}">{$Enemyname[player]}</a></td>
            <td>{$Enemyrank[player]}</td>
            <td>{$Enemytribe[player]}</td>
            <td>- <A href="battle.php?battle={$Enemyid[player]}">{$Aattack}</a></td>
            </tr>
        {/section}
        </table>
    {/if}
{/if}

{if $Battle > "0"}
    <b><u>{$Name} {$Versus} {$Enemyname}</u></b><br />
{/if}

{if $Action == "monster"}
    {if $Action == "monster" && $View > 0}
        <br /><br />{$Avatar} <br />
        <center><u><b>{$Name}</b></u></center> <br /><br />
        <b>{$Tlevel}</b>:  {$Level}<br />
        <b>{$Thp}</b>:  {$Health} <br />
        <b>{$Tdescription}</b>: <br /><br />
        {$Description}<br /><br />
        <a href="battle.php?action=monster&amp;next={$Id}">({$Mt_walka})</a>
        <a href="battle.php?action=monster&amp;dalej={$Id}">({$Msz_walka})</a>
        <a href="battle.php?action=monster">({$Aback})</a><br />
    {else}
        {if !$Fight && !$Fight1}
            {$Monsterinfo}
            <br /><br />
            {if $Dalej > 0}
                <form method="post" action="battle.php?action=monster&amp;fight={$Id}">
                <input type="submit" value="{$Abattle2}" /> {$Witha} <input type="text" size="5" name="razy" value="1" /> {$Name}{$Nend}
                <input type="text" size="5" name="times" value="1" /> {$Mtimes}</form>
            {/if}
            {if $Next > 0}
                <form method="post" action="battle.php?action=monster&amp;fight1={$Id}">
                <input type="submit" value="{$Abattle2}" /> {$Witha} <input type="text" size="5" name="razy" value="1" /> {$Name}{$Nend}
                <input type="hidden" name="write" value="Y" />
                </form>
            {/if}
            <table width="100%">
            <tr>
            <td width="50%"><b><u>{$Mname}</u></b></td>
            <td width="12%"><b><u>{$Mlevel}</u></b></td>
            <td width="12%"><b><u>{$Mhealth}</u></b></td>
            <td width="13%"><b><u>{$Mturn}</u></b></td>
            <td width="13%"><b><u>{$Mfast}</u></b></td>
            </tr>
            {section name=monster loop=$Enemyid}
                <tr>
                <td><a href="battle.php?action=monster&view={$Enemyid[monster]}">{$Enemyname[monster]}</a></td>
                <td>{$Enemylevel[monster]}</td>
                <td>{$Enemyhp[monster]}</td>
                <td><a href="battle.php?action=monster&amp;next={$Enemyid[monster]}">{$Abattle}</a></td>
                <td><a href="battle.php?action=monster&amp;dalej={$Enemyid[monster]}">{$Abattle}</a></td>
                </tr>
            {/section}
            </table><br />{$Orback2} <a href="battle.php">{$Bback2}</a>.
        {/if}
    {/if}
{/if}
{/strip}
