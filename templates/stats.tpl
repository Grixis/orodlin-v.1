{strip}
{$Statsinfo}<br /><br />
{$Avatar}<br />
{if $Action == "gender"}
    <form method="post" action="stats.php?action=gender&amp;step=gender">
    <select name="gender"><option value="M">{$Genderm}</option>
    <option value="F">{$Genderf}</option></select><br />
    <input type="submit" value="{$Aselect}" /></form>
{/if}
<table>
    <tr>
        <td width="50%" valign="top">
            <fieldset style="height:440px">
            <legend><b>{$Tstats}</b></legend>
            <table width="100%">
                <tr><td width="50%"><b>{$Tap}:</b></td><td width="50%"> {$Ap}</td></tr>
                <tr><td><b>{$Trace}:</b></td><td> {$Race}</td></tr>
                <tr><td><b>{$Tclass}:</b></td><td> {$Clas}</td></tr>
                <tr><td><b>{$Tdeity}:</b></td><td> {$Deity}</td></tr>
                <tr><td><b>{$Tgender}:</b></td><td> {$Gender}</td></tr>
                {section name=stats1 loop=$Tstats2}
                    <tr><td><b>{$Tstats2[stats1]}:</b></td><td> {$Stats[stats1]} {$Curstats[stats1]}</td></tr>
                {/section}
                <tr><td><b>{$Tmana}:</b></td><td> {$Mana} {$Rest}</td></tr>
                <tr><td><b>{$Tpw}:</b></td><td> {$PW}</td></tr>
                {if $Ant_d}
                    <tr><td>{$Ant_d}</td></tr>
                {/if}
                {if $Ant_n}
                    <tr><td>{$Ant_n}</td></tr>
                {/if}
                {if $Ant_i}
                    <tr><td>{$Ant_i}</td></tr>
                {/if}
                {if $Resurect}
                    <tr><td>{$Resurect}</td></tr>
                {/if}
                {if $Blessfor}
                    <tr><td><b>{$Blessfor}</b></td><td>{$Pray} <b>{$Blessval}</b></td></tr>
                {/if}
                <tr><td>{$Crime}</td></tr>
                <tr><td><b><a href="fightlogs.php">{$Tfights}</a>:</b></td><td> {$Total}</td></tr>
                <tr><td><b>{$Tlast}:</b></td><td> {$Lastkilled}</td></tr>
                <tr><td><b>{$Tlast2}:</b></td><td>  {$Lastkilledby}</td></tr>
            </table>
            </fieldset>
        </td>
        <td width="50%" valign="top">
            <fieldset style="height:440px">
            <legend><b>{$Tability}</b></legend>
            <table width="100%">
                <tr><td width="60%"><b>{$Tsmith}:</b></td><td width="40%"> {$Smith}</td></tr>
                <tr><td><b>{$Talchemy}:</b></td><td> {$Alchemy}</td></tr>
                <tr><td><b>{$Tlumber}:</b></td><td> {$Fletcher}</td></tr>
                <tr><td><b>{$Tjeweller}:</b></td><td> {$Jeweller}</td></tr>
                <tr><td><b>{$Thutnictwo}:</b></td><td> {$Hutnictwo}</td></tr>
                <tr><td><br/></td></tr>
                <tr><td><b>{$Tfight}:</b></td><td> {$Attack}</td></tr>
                <tr><td><b>{$Tshoot}:</b></td><td> {$Shoot}</td></tr>
                <tr><td><b>{$Tcast}:</b></td><td> {$Magic}</td></tr>
                <tr><td><b>{$Tdodge}:</b></td><td> {$Miss}</td></tr>
                <tr><td><b>{$Tleader}:</b></td><td> {$Leadership}</td></tr>
                <tr><td><br/></td></tr>
                <tr><td><b>{$Tmining}:</b></td><td> {$Mining}</td></tr>
                <tr><td><b>{$Tlumberjack}:</b></td><td> {$Lumberjack}</td></tr>
                <tr><td><b>{$Therbalist}:</b></td><td> {$Herbalist}</td></tr>
                <tr><td><br/></td></tr>
                <tr><td><b>{$Tbreeding}:</b></td><td> {$Breeding}</td></tr>
            </table>
            </fieldset>
        </td>
    </tr>
    <tr>
        <td colspan="2" align="center">
            <fieldset>
            <legend><b>{$Tinfo}</b></legend>
            <div style="text-align:left">
            <table>
                <tr><td><b>{$Trank}:</b></td><td> {$Rank}</td></tr>
                <tr><td><b>{$Tloc}:</b></td><td> {$Location}</td></tr>
                <tr><td><b>{$Tage}:</b></td><td> {$Age}</td></tr>
                <tr><td><b>{$Tlogins}:</b></td><td> {$Logins}</td></tr>
                <tr><td><b>{$Tip}:</b></td><td> {$Ip}</td></tr>
                <tr><td><b>{$Temail}:</b></td><td> {$Email}</td></tr>
                <tr><td>{$GG}</td></tr>
                <tr><td><b>{$Tclan}:</b></td><td> {$Tribe}</td></tr>
                <tr><td>{$Triberank}</td></tr>
                <tr><td><b>{$Reputation}:</b></td><td>{$Rep}</td></tr>
            </table>
            </div>
            </fieldset>
        </td>
    </tr>
</table>
{/strip}