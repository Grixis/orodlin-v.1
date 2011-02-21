{strip}
<center><b><u>{$User}</u></b> ({$Id})</center><br />
<center><img src="{$ViewerAvatar}" alt="" /></center><br />
{if $ViewerRank == 'Admin' || $ViewerRank == 'Staff'}
    <p>{$Timeinfo} {$Time}<br />{$PlayerIP}<a href="memberlist.php?ip={$IP}">{$IP}</a></p>
{/if}
{$GG}
{$JABBER}
{$TLEN}
{$SKYPE}

<b>{$Tfreezed}</b>
{$smarty.const.T_LANG}: {$Lang}<br />
{$Tseclang}
{$smarty.const.T_RANK}: {$Rank}<br />
{$smarty.const.T_LOCATION}: {$Location}<br />
{$Immu}
{$smarty.const.T_LAST_SEEN}: {$Page}<br />
{$smarty.const.T_AGE}: {$Age}<br />
{$smarty.const.T_RACE}: {$Race}<br />
{$smarty.const.T_CLASS2}: {$Clas}<br />
{$Gender}
{$Deity}
{$smarty.const.T_LEVEL}: {$Level}<br />
{$smarty.const.T_STATUS}: {$Status}
{$Clan}
{$smarty.const.T_MAX_HP}: {$Maxhp}<br /><br />
<b><a href="fightlogs.php?view={$Id}">{$smarty.const.T_FIGHTS}</a>:</b> {$Wins}/{$Losses} {$Fratio}<br />
{$smarty.const.T_LAST_KILL}: {$Lastkilled}<br />
{$smarty.const.T_LAST_KILLED}: {$Lastkilledby}<br />
<a href="reputation.php?id={$Id}">{$smarty.const.T_REFS}</a>: {$Refs}<br />
<ul>
    {$Attack}
    {$Mail}
    {$Crime}
    {$Crime2}
</ul>
<div class="overflow"><fieldset><legend><b>{$smarty.const.T_PROFILE}:</b></legend>{$Profile}</fieldset></div>
{if $Attack != "" || $Mail != "" || $Crime != ""}
    {$smarty.const.T_OPTIONS}:<br />
    <ul>
    {$Attack}
    {$Mail}
    {$Crime}
    {$Crime2}
    </ul>
{/if}
{/strip}
