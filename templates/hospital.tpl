{strip}
{if $Action == ""}
    {if $Health > "0"}
        {if $Maxpoints >= $hpneeded}
			{$Couldyou} <a href="hospital.php?action=heal">{$Aheal}</a>?
    	    <br />
        	{$sure} {$Itcost} <b>{$Need}</b> {$Goldcoins}
		{else}
			{$smarty.const.NO_MONEY2}{$Need}{$smarty.const.GOLD_COINS2}
		{/if}
		{if $Maxpoints > 0}
			<br /><br />
    	    {$Costtext}
        	<form method="post" action="hospital.php?action=pheal">
	        {$Iwant} <input type="text" size="3" name="amount" value="{$Maxpoints}"/> {$hpoints}. <input type="submit" value="{$Aheal}" />
    	    </form><br /> <br />
		{/if}
    {/if}
    {if $Health <= "0"}
        {if $Resurect > 0}
            <br /> {$Been_under} <a href="hospital.php?action=ressurect">{$Ayes}</a>
        {else}
            {$Couldyou2}
            <br />{$Itcost2} <b>{$Need}</b> {$Goldcoins}<br />
            <a href="hospital.php?action=ressurect">{$Ayes}</a>
        {/if}
    {/if}
{/if}

{/strip}
