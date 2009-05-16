{if $smarty.get.id}
    {$smarty.const.PLAYER_REPUTATION}
    <ul>{section name=i loop=$Reps}
        <li>{$smarty.const.T_ADDED} {$Reps[i].points} {$smarty.const.T_POINTS} {$smarty.const.T_FOR_A} {$Reps[i].description} ({$Reps[i].date})</li>
    {/section}
    </ul>
    <a href="reputation.php">{$smarty.const.T_LIST_BACK}</a> | <a href="view.php?view={$id}">{$smarty.const.T_PROFILE_BACK}</a>
{else}
    <p>{$smarty.const.T_REPUTATION_DESC}</p>
    <p>{$smarty.const.T_DONATORS_LINK}</p>
    <p><a href="alley.php">{$smarty.const.T_ALLEY}</a></p>

    <ol>{section name=i loop=$Top}
        <li><a href="view.php?view={$Top[i].player_id}">{$Top[i].user}</a> - {$Top[i].id} {$Top[i].points} {$smarty.const.T_POINTS} (<a href="reputation.php?id={$Top[i].player_id}">{$smarty.const.SHOW_LIST}</a>)</li>
    {/section}</ol>
{/if}