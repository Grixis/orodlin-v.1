{strip}
{if $Health != "0" && $smarty.get.action == "" && $smarty.get.action2 == ""}
{if $Graphstyle == "Y"}<div class="center">
	<a><img id="forestpicture" src="" alt="img_{$smarty.const.SHOW_IMAGE}" onclick="showHideImage('forestpicture','images/locations/forest.jpg')" /></a>
</div>{/if}
    {$smarty.const.FOREST_INFO}<br />
    <ul>
        <li><a href="lumberjack.php">{$smarty.const.A_LUMBERJACK}</a></li>
        <li><a href="explore.php">{$smarty.const.A_EXPLORE}</a></li>
        <li><a href="travel.php">{$smarty.const.A_TRAVEL}</a></li>
    </ul><br/>
{/if}

{if $Health == "0" && $smarty.get.action == ""}
    {$smarty.const.YOU_DEAD}.<br />
    - <a href="las.php?action=back">{$smarty.const.BACK_TO}</a><br />
    - <a href="las.php?action=hermit">{$smarty.const.STAY_HERE}</a>
{/if}

{if $smarty.get.action == "hermit" && $smarty.get.action2 == ""}
    {$smarty.const.HERMIT}<br /><br />
    <i>{$smarty.const.HERMIT2}</i><br />
    - <a href="las.php?action=hermit&amp;action2=resurect">{$smarty.const.A_RESURECT}</a> ({$smarty.const.T_GOLD} {$Goldneed} {$smarty.const.GOLD_COINS})<br />
    - <a href="las.php?action=hermit&amp;action2=wait">{$smarty.const.A_WAIT}</a> ({$Waittime})
{/if}

{if $smarty.get.action2 == "resurect"}
    {$smarty.const.RES1}<br /><br />
    <i>{$smarty.const.RES2}</i><br /><br />
    {$smarty.const.RES3}<br />
{/if}

{$Message}

{if $smarty.get.action2 != ""}
    <br /><a href="las.php">{$smarty.const.BACK}</a>
{/if}
{/strip}
