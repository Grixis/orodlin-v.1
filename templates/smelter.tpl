{if $Step == ""}
		{$smarty.const.MENU_INFO}<br /><br />
		{* {$Menuinfo}<br /><br /> *}
		- <a href="smelter.php?step=wytop">{$smarty.const.MENU_WYTOP}</a><br />
		- <a href="smelter.php?step=przetop">{$smarty.const.MENU_PRZETOP}</a><br />
	{if $Class == 'Rzemieślnik'}
		- <a href="smelter.php?step=wzmocnij">{$smarty.const.MENU_WZMOCNIJ}</a><br />
	{/if}
{/if}

{if $Step == "wzmocnij"}
		{$smarty.const.WZMOCNIJ_OPIS}
		{if $Message!=""}
			<b>{$Message}</b><br /><br />
		{/if}			
		<form method="post" action="smelter.php?step=wzmocnij">
		{if $Bweapons}
			<br /><u>{$smarty.const.IN_BACKPACK}{$smarty.const.WEAPONS}</u>:<br />
		{section name=item1 loop=$Bweapons}		
			{$Bweapons[item1]}<br/>
		{/section}
		{/if}	
		{if $Bhelmets}
			<br /><u>{$smarty.const.IN_BACKPACK}{$smarty.const.HELMETS}</u>:<br />
		{section name=item2 loop=$Bhelmets}
			{$Bhelmets[item2]}<br/>
		{/section}
		{/if}	
		{if $Barmors}
			<br /><u>{$smarty.const.IN_BACKPACK}{$smarty.const.ARMORS}</u>:<br />
		{section name=item3 loop=$Barmors}
			{$Barmors[item3]}<br/>
		{/section}
		{/if}
		{if $Bshields}
			<br /><u>{$smarty.const.IN_BACKPACK}{$smarty.const.SHIELDS}</u>:<br />
		{section name=item4 loop=$Bshields}
			{$Bshields[item4]}<br/>
		{/section}
		{/if}
				
		{if $Blegs}
			<br /><u>{$smarty.const.IN_BACKPACK}{$smarty.const.LEGS2}</u>:<br />
		{section name=item5 loop=$Blegs}
			{$Blegs[item5]}<br/>
		{/section}
		{/if}
		 <select name="typ">
			<option value="a">{$smarty.const.ADAMANTYT}</option>
			<option value="k">{$smarty.const.KRYSZTAL}</option>
			<option value="m">{$smarty.const.METEORYT}</option>
		</select>			
		<input type="submit" value="{$smarty.const.A_WZMOCNIJ}"/>
		</form>
{/if}

{if $Step == "przetop" OR $Step == "przetop_wszystkie"}
		{$smarty.const.PRZETOP_OPIS}
		{if $Bweapons}
			<br /><u>{$smarty.const.IN_BACKPACK}{$smarty.const.WEAPONS}</u>:<br />
		<form method="post" action="smelter.php?step=przetop">
		{section name=item1 loop=$Bweapons}		
			{$Bweapons[item1]}<br/>
		{/section}
		<input type="submit" value="{$smarty.const.A_PRZETOP}"/>
		<input type="text" name="amount" size="4"> {$smarty.const.HOW_MANY}
		</form>
			<br />(<a href="smelter.php?step=przetop_wszystkie&action=W">{$smarty.const.A_PRZETOP_ALL}{$smarty.const.WEAPONS}</a>)<br />
		{/if}	
			
		{if $Bhelmets}
			<br /><u>{$smarty.const.IN_BACKPACK}{$smarty.const.HELMETS}</u>:<br />
		<form method="post" action="smelter.php?step=przetop">
		{section name=item2 loop=$Bhelmets}
			{$Bhelmets[item2]}<br/>
		{/section}
		<input type="submit" value="{$smarty.const.A_PRZETOP}"/>
		<input type="text" name="amount" size="4"> {$smarty.const.HOW_MANY}
		</form>
			<br />(<a href="smelter.php?step=przetop_wszystkie&action=H">{$smarty.const.A_PRZETOP_ALL}{$smarty.const.HELMETS}</a>)<br />
		{/if}	
			
		{if $Barmors}
			<br /><u>{$smarty.const.IN_BACKPACK}{$smarty.const.ARMORS}</u>:<br />
		<form method="post" action="smelter.php?step=przetop">
		{section name=item3 loop=$Barmors}
			{$Barmors[item3]}<br/>
		{/section}
		<input type="submit" value="{$smarty.const.A_PRZETOP}"/>
		<input type="text" name="amount" size="4"> {$smarty.const.HOW_MANY}
		</form>
			<br />(<a href="smelter.php?step=przetop_wszystkie&action=A">{$smarty.const.A_PRZETOP_ALL}{$smarty.const.ARMORS}</a>)<br />
		{/if}
				
		{if $Bshields}
			<br /><u>{$smarty.const.IN_BACKPACK}{$smarty.const.SHIELDS}</u>:<br />
		<form method="post" action="smelter.php?step=przetop">
		{section name=item4 loop=$Bshields}
			{$Bshields[item4]}<br/>
		{/section}
		<input type="submit" value="{$smarty.const.A_PRZETOP}"/>
		<input type="text" name="amount" size="4"> {$smarty.const.HOW_MANY}
		</form>
			<br />(<a href="smelter.php?step=przetop_wszystkie&action=S">{$smarty.const.A_PRZETOP_ALL}{$smarty.const.SHIELDS}</a>)<br />
		{/if}
				
		{if $Blegs}
			<br /><u>{$smarty.const.IN_BACKPACK}{$smarty.const.LEGS2}</u>:<br />
		<form method="post" action="smelter.php?step=przetop">
		{section name=item5 loop=$Blegs}
			{$Blegs[item5]}<br/>
		{/section}
		<input type="submit" value="{$smarty.const.A_PRZETOP}"/>
		<input type="text" name="amount" size="4"> {$smarty.const.HOW_MANY}
		</form>
			<br />(<a href="smelter.php?step=przetop_wszystkie&action=L">{$smarty.const.A_PRZETOP_ALL}{$smarty.const.LEGS2}</a>)<br />
		{/if}	
		<br /><br />{$Message}	
{/if}

{if $Step == "wytop"}
	{$smarty.const.SMELT_INFO}<br /><br />  
	{if $Smelterlevel < "5"}
		{$smarty.const.UPGRADE_INFO}<br />
		 <form method="post" action="smelter.php?step=wytop&amp;action=Y">
			{$Levelinfo}<br />
			<input type="submit" value="{$smarty.const.A_UPGRADE}" />
		 </form>
	{/if}
	{if $Smelterlevel == "5"}
		{$Levelinfo}<br />
	{/if}
	   
	{$smarty.const.YOU_HAVE}<br />
	{section name=mins loop=$Ilesurowce}
		<b>{$Ilesurowce[mins]}</b>{$Ilesurowcetekst[mins]}<br />
	{/section}<br /><br />	

	{if $Smelterlevel != "0"}
		{$smarty.const.YOU_MAY}<br />
		{section name=smelt loop=$Ilemax}
			<b>{$Ilemax[smelt]}</b>{$Ilemaxtekst[smelt]}<br />
		{/section}
		<br /><br />
	{/if}	
	
	{section name=smelt1 loop=$Asmelt}
		- <a href="smelter.php?step=wytop&amp;action={$Smeltaction[smelt1]}">{$Asmelt[smelt1]}</a><br />
	{/section}
	{if $Smelt != "" && $Action != "Y"}
		<br />
		<form method="post" action="smelter.php?step=wytop&amp;action={$Get}">
			<input type="submit" value="{$Asmelt2}" /><input type="text" name="amount" size="5" />{$Smeltm} 
		</form>
	{/if}
	<br />{$Message}
{/if}

{if $Step != ""}
	<br />(<a href="smelter.php">{$Aback}</a>)
{/if}
