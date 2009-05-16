{strip}
{if $Action == "" && $Step == ""}
    <br />{$Mazeinfo}<br />
    <a href="maze.php?action=explore">{$Ayes}</a>
{/if}

{if $Action == "explore" && $Step == ""}
    {if $Roll < "49" || $Roll > "63"}
        <br />{$Message}<br /><br />
        {$Link}
    {/if}
    {if $Roll > "48" && $Roll < "64"}
        <br  />{$Youmeet} {$Name}{$Fight2}<br />
       <a href="maze.php?step=battle&amp;type=T">{$Yturnf}</a><br />
       <a href="maze.php?step=battle&amp;type=S">{$Ynormf}</a><br />
       <a href="maze.php?step=run">{$Ano}</a><br />
    {/if}
{/if}

{if $Step == "run"}
    {if $Health > "0" && ($Chance > "0" || $Chance2 < 3)}
        <br />{$Escapesucc} {$Ename}! {$Escapesucc2} {$Expgain} {$Escapesucc3}<br />
        <br />{$Link}
    {/if}
{/if}

{if $Step == "battle"}
    {$Link}
{/if}
{/strip}