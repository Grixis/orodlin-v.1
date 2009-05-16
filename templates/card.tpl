{strip}
{if !$Action}
	{$smarty.const.C_WELCOME}
	{if $Gender == '' && $Race == '' && $Class == ''}
		{$smarty.const.C_START}
	{else}
	{if $Gender != '' && $Race != '' && $Class != '' && $Deity != '' && $Changed_loc == 'Y'}
		{$smarty.const.C_YHAVE}
	{/if}
		{if $Gender == ''}
			{$smarty.const.C_G}
		{/if}
		{if $Race == ''}
			{$smarty.const.C_R}
		{else}
			{if $Class == ''}
				{$smarty.const.C_C}
			{/if}
		{/if}
		{if $Deity == ''}
			{$smarty.const.C_D}
		{/if}
		{if $Changed_loc != 'Y'}
			{$smarty.const.C_P}
		{/if}
	{/if}
{/if}
{if $Action == 'gender'}
	{$smarty.const.C_GENDER}
	<form method="post" action="card.php?action=gender">
		<select name="gender">
			<option value="M">{$smarty.const.C_MALE}</option>
			<option value="F">{$smarty.const.C_FEMALE}</option>
		</select><br />
		<input type="submit" value="{$smarty.const.C_SELECT}" />
	</form>
	<br />&raquo; <a href="card.php">{$smarty.const.C_BACK}</a>
{/if}
{if $Action == 'race'}
	{if $Select == ''}
		{$smarty.const.C_RACE}
		<ul{if $Graphstyle == "Y"} class="sword"{/if}>
		{section name=k loop=$Races}
			<li><a href="card.php?action=race&amp;select={$RaceNames[k]}">{$Races[k]}</a></li>
		{/section}
		</ul>
	{else}
		<div class="center">
		{section name=k loop=$Races}
			&raquo;<a{if $Select == $RaceNames[k]} style="text-decoration: underline"{/if} href="card.php?action=race&amp;select={$RaceNames[k]}">{$Races[k]}</a>&nbsp;
		{/section}<br /><img src="images/races/{$Select}_f.jpg" alt="img_Rasa_kobieta " /><img src="images/races/{$Select}_m.jpg" alt="img_Rasa_mezczyzna" />
		</div>
		{$smarty.const.C_RACE_INFO}
		<form method="post" action="card.php?action=race&amp;select={$Select}&amp;step=mark">
			<input type="submit" value="{$smarty.const.C_SELECT}" />
		</form>
	{/if}
	<br />&raquo; <a href="card.php">{$smarty.const.C_BACK}</a>
{/if}
{if $Action == 'class'}
	{if $Select == ''}
		{$smarty.const.C_CLASS}
		<ul{if $Graphstyle == "Y"} class="sword"{/if}>
		{section name=k loop=$Classes}
			<li><a href="card.php?action=class&amp;select={$ClassNames[k]}">{$Classes[k]}</a></li>
		{/section}
		</ul>
	{else}
		<div class="center">
		{section name=k loop=$Classes}
			&raquo;<a{if $Select == $ClassNames[k]} style="text-decoration: underline"{/if} href="card.php?action=class&amp;select={$ClassNames[k]}">{$Classes[k]}</a>&nbsp;
		{/section}
		</div>
		{$smarty.const.C_CLASS_INFO}
		<form method="post" action="card.php?action=class&amp;select={$Select}&amp;step=mark">
			<input type="submit" value="{$smarty.const.C_SELECT}" />
		</form>
	{/if}
	<br />&raquo; <a href="card.php">{$smarty.const.C_BACK}</a>
{/if}

{if $Action == 'deity'}
	{if $Select == ''}
		{if $Step == "change"}
			{if $Step2 == "confirm"}
				{$smarty.const.YOU_CHANGE} {$Pdeity}{$smarty.const.YOU_MAY} <a href="card.php?action=deity">{$smarty.const.A_SELECT2}</a> {$smarty.const.T_DEITY}<br />
			{else}
{$smarty.const.CHANGE} {$Ccost} {$smarty.const.CHANGE2}<br />
		<ul{if $Graphstyle == "Y"} class="sword"{/if}>
		<li><a href="card.php?action=deity&amp;step=change&amp;step2=confirm">{$smarty.const.C_YES}</a></li>
		<li><a href="card.php">{$smarty.const.C_NO}</a></li>
		</ul>
			{/if}
		{else}
		{$smarty.const.C_DEITY}
		<ul{if $Graphstyle == "Y"} class="sword"{/if}>
		{section name=deity loop=$GodName}
			<li><a href="card.php?action=deity&amp;select={$GodOption[deity]}">{$GodName[deity]}</a></li>
		{/section}
		</ul>
		&raquo; <a href="card.php?action=place">{$smarty.const.C_ATHEIST}</a>
		{/if}
	{else}
		<div class="center">
		{section name=deity loop=$GodName}
			&raquo;<a{if $Select == $GodOption[deity]} style="text-decoration: underline"{/if} href="card.php?action=deity&amp;select={$GodOption[deity]}">{$GodName[deity]}</a>&nbsp;
		{/section}
		</div>
		{$smarty.const.C_DEITY_INFO}
		<form method="post" action="card.php?action=deity&amp;select={$Select}&amp;step=mark">
			<input type="submit" value="{$smarty.const.C_SELECT}" />
		</form>
	{/if}
	<br />&raquo; <a href="card.php">{$smarty.const.C_BACK}</a>
{/if}

{if $Action == 'place'}
	{if $Select == ''}
		{$smarty.const.C_PLACE}
		<ul{if $Graphstyle == "Y"} class="sword"{/if}>
			{$City1}
			{$City2}
		</ul>
	{else}
		<div class="center">
		{section name=i loop=$Places}
			&raquo;<a{if $Select == $Places[i]} style="text-decoration: underline"{/if} href="card.php?action=place&amp;select={$Places[i]}">{$PlacesNames[i]}</a>&nbsp;
		{/section}
		</div>
		{$smarty.const.C_PLACE_INFO}
		<form method="post" action="card.php?action=place&amp;select={$Select}&amp;step=mark">
			<input type="submit" value="{$smarty.const.C_SELECT}" />
		</form>
	{/if}
	<br />&raquo; <a href="card.php">{$smarty.const.C_BACK}</a>
{/if}

{/strip}
