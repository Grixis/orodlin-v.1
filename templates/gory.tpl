{strip}
{if $Health != "0" && $Action == ""}
{if $Graphstyle == "Y"}<div class="center">
	<a><img id="mountainspicture" src="images/locations/mountains.jpg" alt="img_{$smarty.const.SHOW_IMAGE}" onclick="showHideImage('mountainspicture','images/locations/mountains.jpg')" /></a>
</div>{/if}
    {$Mountinfo}
    <ul>
    <li><a href="kopalnia.php">{$Amine}</a></li>
    <li><a href="explore.php">{$Asearch}</a></li>
    <li><a href="gory.php?action2=city">{$Acity}</a></li>
    <li><a href="travel.php">{$Atravel}</a></li>
    </ul>
{/if}

{if $Health == "0" && $Action == ""}
    {$Youdead}.
    <ul>
    <li> <a href="gory.php?action=back">{$Backto}</a></li>
    <li> <a href="gory.php?action=hermit">{$Stayhere}</a></li>
    </ul>
{/if}

{if $Action == "hermit" && $Action2 == ""}
    {$Hermit}<br />
    <i>{$Hermit2}</i><ul>
    <li> <a href="gory.php?action=hermit&amp;action2=resurect">{$Aresurect}</a> ({$Tgold} {$Goldneed} {$Goldcoins})</li>
    <li> <a href="gory.php?action=hermit&amp;action2=wait">{$Await}</a> ({$Waittime}) </li>
    </ul>
{/if}

{if $Action2 == "resurect"}
    {$Res1}<br />
    <i>{$Res2}</i><br />
    {$Res3}
{/if}

{$Message}

{if $Action2 != ""}
    <br /><p><a href="gory.php">{$Aback}</a></p>
{/if}
{/strip}
