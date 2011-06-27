{strip}
{if $Corepass != "Y"}
	{$smarty.const.COREPASS_INFO}
	{if $Gold >= "500"}
		<br/><br/>{$smarty.const.HAVE_MONEY}
		<ul>
		<li><a href="core.php?answer=yes">{$smarty.const.A_YES}</a></li>
		<li><a href="city.php">{$smarty.const.A_NO}</a></li>
		</ul>
	{else}
	<br/><br/>{$smarty.const.NO_MONEY}
	{/if}
{else}
	{if $view == "none"}
		{$smarty.const.CORE_MAIN}
		<ul>
			<li><a href="core.php?view=my">{$smarty.const.A_MY_CORE}</a></li>
			<li><a href="core.php?view=arena">{$smarty.const.A_ARENA}</a></li>
			<li><a href="core.php?view=train">{$smarty.const.A_TRAIN}</a></li>
			<li><a href="core.php?view=market">{$smarty.const.A_MARKET}</a></li>
			<li><a href="core.php?view=search">{$smarty.const.A_SEARCH}</a></li>
			<li><a href="core.php?view=breed">{$smarty.const.A_BREED}</a></li>
			<li><a href="core.php?view=monuments">{$smarty.const.A_MONUMENTS}</a></li>
		</ul>
		<br/>
		<a href="city.php">{$Back}</a>
	{elseif $view == "my"}
		{$smarty.const.MY_CORES_INFO}
		<table width="90%">
		<tr><td width="50%" valign="top">
		<fieldset style="overflow:auto;">
			<legend><b>{$smarty.const.NORMAL_ARENA}</b></legend>
			<ul>
			{section name=foo loop=$Normalnamelist}
				<li>
					<a href="core.php?view=my&amp;id={$Normalidlist[foo]}">{$Normalnamelist[foo]}</a> [{$Normalstatuslist[foo]}]
				</li>
			{/section}
			</ul>
		</fieldset>
		</td><td width="50%" valign="top">
		<fieldset style="overflow:auto;">
			<legend><b>{$smarty.const.MAGIC_ARENA}</b></legend>
			<ul>
			{section name=foo loop=$Magicnamelist}
				<li>
					<a href="core.php?view=my&amp;id={$Magicidlist[foo]}">{$Magicnamelist[foo]}</a> [{$Magicstatuslist[foo]}]
				</li>
			{/section}
			</ul>
		</fieldset>
		</td></tr>
		</table>
		<br/><br/>
		<a href="core.php">{$smarty.const.BACK}</a>
	{elseif $view == "display"}
		<center>
			<span style="font-size:14px; font-weight:bold">{$Ctitle}</span> <br/>
			<img src="images/pets/{$Image}" alt="{$Ctitle}"/>
		</center>
		{if $action == "changename"}
			<br/><form method="post" action="core.php?view=my&amp;id={$Coreid}&amp;action=changename">
			<input type="text" name="corename" size="20"/>
			<input type="submit" value="{$smarty.const.CHANGE_NAME}"/>
			</form>
		{elseif $action == "heal" && $Healinfo != ""}
			<br/>
			{$Healinfo} <br/>
			{if $Nomoney == ""}
			<form method="post" action="core.php?view=my&amp;id={$Coreid}&amp;action=heal">
			<input type="hidden" name="heal" value="Y"/>
			<input type="submit" value="{$smarty.const.A_HEAL}"/>
			</form>
			{else}
				{$Nomoney}<br/>
			{/if}
			<br/>
		{elseif $action == "resurrect" && $Resinfo != ""}
			<br/>
			{$Resinfo} <br/>
			{if $Nomoney == ""}
			<form method="post" action="core.php?view=my&amp;id={$Coreid}&amp;action=resurrect">
			<input type="hidden" name="resurrect" value="Y"/>
			<input type="submit" value="{$smarty.const.A_RESURRECT}"/>
			</form>
			{else}
				{$Nomoney}<br/>
			{/if}
			<br/>
		{elseif $action == "free"}
			<br/>
			{$smarty.const.FREE_INFO}
			<form method="post" action="core.php?view=my&amp;id={$Coreid}&amp;action=free">
			<input type="hidden" name="free" value="Y"/>
			<input type="submit" value="{$smarty.const.A_FREE}"/>
			</form>
			<br/>
		{elseif $action == "pass"}
			<br />
			{$smarty.const.PASS_INFO}
			<br /><form method="post" action="core.php?view=my&amp;id={$Coreid}&amp;action=pass">
			<input type="text" name="pid" size="5" />
			<input type="submit" value="{$smarty.const.A_PASS}"/>
			</form>
			<br />
		{elseif $action == "sell" && $Sellinfo != ""}
			<br/>
			{$Sellinfo}
			<br/><form method="post" action="core.php?view=my&amp;id={$Coreid}&amp;action=sell">
			<input type="text" name="prize" size="5"/>
			<input type="submit" value="{$smarty.const.A_SELL}"/>
			</form>
			<br/>
		{elseif $action == "activate" && $Activateinfo != ""}
			<br/>
			{$Activateinfo}<br/>
			{$Cost}<br/>
			{if $Nomoney == ""}
				<form method="post" action="core.php?view=my&amp;id={$Coreid}&amp;action=activate">
				<input type="hidden" name="activate" value="Y"/>
				<input type="submit" value="{$smarty.const.A_ACTIVATE}"/>
				</form>
			{else}
				{$Nomoney}<br/>
			{/if}
			<br/>
		{elseif $action == "deactivate" && $Deactivateinfo != ""}
			<br/>
			{$Deactivateinfo}<br/>
			{$Cost}<br/>
			{if $Nomoney == ""}
				<form method="post" action="core.php?view=my&amp;id={$Coreid}&amp;action=deactivate">
				<input type="hidden" name="deactivate" value="Y"/>
				<input type="submit" value="{$smarty.const.A_DEACTIVATE}"/>
				</form>
			{else}
				{$Nomoney}<br/>
			{/if}
			<br/>
		{/if}
		<table width="80%">
			<tr><td width="30%">{$smarty.const.C_NAME}:</td><td width="70%">{$Name} (<a href="core.php?view=my&amp;id={$Coreid}&amp;action=changename">{$smarty.const.CHANGE_NAME}</a>)</td></tr>
			<tr><td>{$smarty.const.C_SPECIES}:</td><td>{$Species}</td></tr>
			<tr><td>{$smarty.const.C_GENDER}:</td><td>{$Gender}</td></tr>
			<tr><td>{$smarty.const.C_STATUS}:</td><td>{$Status}</td></tr>
			<tr><td>{$smarty.const.C_HP}:</td><td>{$Hp} / {$Maxhp}
			{if $Hp > 0 && $Maxhp > $Hp}
				&nbsp;(<a href="core.php?view=my&amp;id={$Coreid}&amp;action=heal">{$smarty.const.A_HEAL}</a>)
			{elseif $Hp <= 0}
				&nbsp;(<a href="core.php?view=my&amp;id={$Coreid}&amp;action=resurrect">{$smarty.const.A_RESURRECT}</a>)
			{/if}
			</td></tr>
			<tr><td>{$smarty.const.C_ATTACK}:</td><td>{$Attack}</td></tr>
			<tr><td>{$smarty.const.C_DEFENCE}:</td><td>{$Defence}</td></tr>
			<tr><td>{$smarty.const.C_SPEED}:</td><td>{$Speed}</td></tr>
			<tr><td>{$smarty.const.C_ARENA}:</td><td>{$Arena}</td></tr>
			<tr><td>{$smarty.const.C_ATUT}:</td><td>{$Atut}</td></tr>
			<tr><td>{$smarty.const.C_AGE}:</td>
			<td>
				{$Age}
				{if $Rest > 0}
				&nbsp;({$Rest} {$smarty.const.DAYS_TO_BREED})
				{/if}
			</td></tr>
			<tr><td>{$smarty.const.C_WINS}:</td><td>{$Wins}</td></tr>
			<tr><td>{$smarty.const.C_LOSES}:</td><td>{$Loses}</td></tr>
			<tr><td>(<a href="core.php?view=my&amp;id={$Coreid}&amp;action=free">{$smarty.const.A_FREE}</a>)</td>
			<td>
				{if $Statuscode == 'N'}
				(<a href="core.php?view=my&amp;id={$Coreid}&amp;action=pass">{$smarty.const.A_PASS}</a>)
				{/if}
			</td></tr>
			<tr>
				<td>
				{if $Statuscode == 'N' && $Hp > 0}
					(<a href="core.php?view=my&amp;id={$Coreid}&amp;action=sell">{$smarty.const.A_SELL}</a>)
				{/if}
				</td>
				<td>
				{if $Statuscode == 'S'}
					(<a href="core.php?view=my&amp;id={$Coreid}&amp;action=unsell">{$smarty.const.A_UNSELL}</a>)
				{/if}
				</td>
			</tr>
			<tr>
				<td>
				{if $Statuscode == 'N' && $Hp > 0}
					(<a href="core.php?view=my&amp;id={$Coreid}&amp;action=activate">{$smarty.const.A_ACTIVATE}</a>)
				{/if}
				</td>
				<td>
				{if $Statuscode == 'A'}
					(<a href="core.php?view=my&amp;id={$Coreid}&amp;action=deactivate">{$smarty.const.A_DEACTIVATE}</a>)
				{/if}
				</td>
			</tr>
		</table>
		<br/>
		{$smarty.const.C_TEXT}:
		<p class="artbody">{$Text}</p>
		<br/>
		<a href="core.php">{$smarty.const.BACK}</a>
	{elseif $view == "search"}
		{if $Searchinfo != ""}
			{$Searchinfo}<br/><br/>
			{$smarty.const.YOU_CAN} {$Maxtries} {$smarty.const.TIMES}
			{if $Maxtries > 0}
				<br/><br/>
				<form method="post" action="core.php?view=search">
				<input type="text" name="times" size="5"/>
				<input type="submit" value="{$smarty.const.SUMMON}"/>
				</form>
			{/if}
		{elseif $Nocores != ""}
			{$Nocores}	
		{else}
			{$smarty.const.YOU_SUMMON}:
			<ul>{$List}</ul>
			{if $Gainexp > 0}
			{$smarty.const.YOU_GAINED} <b>{$Gainexp}</b> {$smarty.const.EXPERIENCE}
			{/if}
		{/if}
		<br/><br/>
		<a href="core.php">{$smarty.const.BACK}</a>
	{elseif $view == "market"}
		{$smarty.const.MARKET_INFO}
		<br/>
		{if $SellNamelist != ""}
			<br/>
			{$smarty.const.SELL_TEXT}
			<form method="post" action="core.php?view=my&amp;action=sell">
			<select name="id">
			{section name=foo loop=$SellNamelist}
				<option value="{$SellIdlist[foo]}">
					{$SellNamelist[foo]} :: {$SellAttacklist[foo]}, {$SellDefencelist[foo]}, {$SellSpeedlist[foo]} :: {$SellHplist[foo]} :: {$SellGenderlist[foo]}
				</option>
			{/section}
			</select>
			<br/>
			<input type="text" name="prize" size="5" />
			<input type="submit" value="{$smarty.const.A_SELL}" />
			</form>
		{/if}
		{if $Namelist != ""}
		<ul>
		{section name=foo loop=$Namelist}
			<li>
			<a href="core.php?view=market&amp;buy={$Idlist[foo]}">{$smarty.const.A_BUY} - {$Prizelist[foo]} {$smarty.const.GOLD_PIECES}</a>
			&nbsp; <b> {$Namelist[foo]} </b> - <a href="view.php?view={$Owneridlist[foo]}">{$Ownerlist[foo]}</a>
			<br/>
			{$smarty.const.C_ATTACK}: <b>{$Attacklist[foo]}</b>, {$smarty.const.C_DEFENCE}: <b>{$Defencelist[foo]}</b>, {$smarty.const.C_SPEED}: <b>{$Speedlist[foo]}</b>, {$smarty.const.C_HP}: <b>{$Hplist[foo]}</b>, {$smarty.const.C_GENDER}: <b>{$Genderlist[foo]}</b>.
			</li>
		{/section}
		</ul>
		{/if}
		<br/><br/>
		<a href="core.php">{$smarty.const.BACK}</a>
	{elseif $view == "arena"}
		{if $Arena == ""}
			{if $Fight == ""}
				{$smarty.const.ARENA_INFO}
				<br/><br/>
				<ul>
				<li><a href="core.php?view=arena&amp;arena=Z">{$smarty.const.ARENA_Z}</a></li>
				<li><a href="core.php?view=arena&amp;arena=M">{$smarty.const.ARENA_M}</a></li>
				</ul>
			{else}
				<b>{$Fight}</b><br/><br/>
				{$Log}<br/><br/>
				{$Result}<br/>
				{$Reward}
			{/if}
		{else}
			{if $Arena == 'Z'}
				{$smarty.const.ARENA_Z_INFO}
			{else}
				{$smarty.const.ARENA_M_INFO}
			{/if}
			<br/><br/>
			<form method="post" action="core.php?view=arena&amp;action=fight">
			{$smarty.const.CHOOSE_YOUR}<br/>
			<select name="my">
			{section name=foo loop=$Namelist}
				<option value="{$Idlist[foo]}">
				{$Namelist[foo]} :: {$Attacklist[foo]}, {$Defencelist[foo]}, {$Speedlist[foo]} :: {$Hplist[foo]}
				</option>
			{/section}
			</select>
			<br/><br/>
			{$smarty.const.CHOOSE_OPPONENT}<br/>
			<select name="opponent">
			{section name=foo loop=$ONamelist}
				<option value="{$OIdlist[foo]}">
				{$ONamelist[foo]} :: {$OOwnerlist[foo]} ({$OOwneridlist[foo]})
				</option>
			{/section}
			</select>
			<br/><br/>
			<input type="submit" value="{$smarty.const.DO_FIGHT}"/>
			</form>
		{/if}
		<br/><br/>
		<a href="core.php">{$smarty.const.BACK}</a>
	{elseif $view == "train"}
		{$smarty.const.TRAIN_INFO}
		<br/>
		{if $Namelist != ""}
			<br/>
			{$smarty.const.TRAIN_TEXT}
			<form method="post" action="core.php?view=train">
			<select name="id">
			{section name=foo loop=$Namelist}
				<option value="{$Idlist[foo]}">
					{$Namelist[foo]} :: {$Attacklist[foo]}, {$Defencelist[foo]}, {$Speedlist[foo]} :: {$Hplist[foo]}
				</option>
			{/section}
			</select>
			<br/>
			<input type="submit" value="{$smarty.const.A_TRAIN}" />
			</form>
		{elseif $Cost != ""}
			<br/>
			{$Cost}
			<br/>
			{$smarty.const.YOU_CAN_TRAIN} {$Corename} <b>{$Maxtries}</b> {$smarty.const.TIMES}
			<br/>
			<form method="POST" action="core.php?view=train">
			<input type="hidden" name="id" value="{$Coreid}"/>
			<input type="submit" value="{$smarty.const.A_TRAIN}"/>
			<select name="stat">
				<option value="A">{$smarty.const.C_ATTACK}</option>
				<option value="D">{$smarty.const.C_DEFENCE}</option>
				<option value="S">{$smarty.const.C_SPEED}</option>
			</select>
			<input time="text" name="times"/> {$smarty.const.TIMES}
			</form>
		{elseif $Trained == "Y"}
			<br/>
			{$smarty.const.YOU_TRAINED} <b>{$Gainstat}</b> {$Stat} <br/>
			{if $Gainbreed > 0}
				{$smarty.const.YOU_GOT} <b>{$Gainbreed}</b> {$smarty.const.BREEDING}
				{if $GainExp > 0}
				, <b>{$GainExp}</b> {$smarty.const.EXPERIENCE}
				{/if}
			{/if}
			<br/><br/>
			{$smarty.const.YOU_PAID}
			{if $Adamantium > 0}&nbsp;<b>{$Adamantium}</b> {$smarty.const.ADAMANTIUM}{/if}
			{if $Crystal > 0}&nbsp;<b>{$Crystal}</b> {$smarty.const.CRYSTAL}{/if}
			{if $Platinum > 0}&nbsp;<b>{$Platinum}</b> {$smarty.const.PLATINUM}{/if}
			{if $Energy > 0}&nbsp;<b>{$Energy}</b> {$smarty.const.ENERGY}{/if}
		{/if}
		<br/><br/>
		<a href="core.php">{$smarty.const.BACK}</a>
	{elseif $view == "breed"}
		{$smarty.const.BREED_INFO}<br/><br/>
		{if $Result == "" && $Choosen == ""}
			<form method="post" action="core.php?view=breed">
			{$smarty.const.CROSS_CORE}<br/>
			<select name="maleid">
				{section name=foo loop=$MaleNamelist}
				<option value="{$MaleIdlist[foo]}">
					{$MaleNamelist[foo]} :: {$MaleAttacklist[foo]}, {$MaleDefencelist[foo]}, {$MaleSpeedlist[foo]} :: {$MaleHplist[foo]}
				</option>
				{/section}
			</select><br/>
			{$smarty.const.WITH_CORE}<br/>
			<select name="femaleid">
				{section name=foo loop=$FemaleNamelist}
				<option value="{$FemaleIdlist[foo]}">
					{$FemaleNamelist[foo]} :: {$FemaleAttacklist[foo]}, {$FemaleDefencelist[foo]}, {$FemaleSpeedlist[foo]} :: {$FemaleHplist[foo]}
				</option>
				{/section}

			</select><br/>
			<input type="submit" value="{$smarty.const.A_CROSS}"/>
			</form>
		{elseif $Result == ""}
			{$smarty.const.CROSS_CORE}<br/>
			<b>{$Malename}</b> <br/> {$smarty.const.C_ATTACK} <b>{$Maleattack}</b>, {$smarty.const.C_DEFENCE} <b>{$Maledefence}</b>, {$smarty.const.C_SPEED} <b>{$Malespeed}</b> <br/> {$smarty.const.C_HP} <b>{$Malehp}</b> <br/>
			<br/>{$smarty.const.WITH_CORE}<br/>
			<b>{$Femalename}</b> <br/> {$smarty.const.C_ATTACK} <b>{$Femaleattack}</b>, {$smarty.const.C_DEFENCE} <b>{$Femaledefence}</b>, {$smarty.const.C_SPEED} <b>{$Femalespeed}</b> <br/> {$smarty.const.C_HP} <b>{$Femalehp}</b> <br/><br/>
			{$smarty.const.YOU_PAY} <b>{$Platinumcost}</b> {$smarty.const.PLATINUM} <b>{$Adamantiumcost}</b> {$smarty.const.ADAMANTIUM} <b>{$Crystalcost}</b> {$smarty.const.CRYSTAL}
			{if $Meteorcost > 0}
			&nbsp;<b>{$Meteorcost}</b> {$smarty.const.METEOR}
			{/if}<br/>
			{$smarty.const.YOU_NEED} <b>{$Energycost}</b> {$smarty.const.ENERGY}<br/>
			{if $Nominerals == ""}
				{if $Noenergy == ""}
					<form method="post" action="core.php?view=breed">
					<input type="hidden" name="maleid" value="{$Maleid}"/>
					<input type="hidden" name="femaleid" value="{$Femaleid}"/>
					<input type="hidden" name="cross" value="Y"/>
					<input type="submit" value="{$smarty.const.A_CROSS}"/>
					</form>
				{else}
					<br/>{$Noenergy}
				{/if}
			{else}
				<br/>{$Nominerals}
			{/if}
		{else}
			{$Result}<br/><br/>
			{$smarty.const.YOU_GOT} <b>{$Gainbreed}</b> {$smarty.const.BREEDING}
			{if $GainExp > 0}
			, <b>{$GainExp}</b> {$smarty.const.EXPERIENCE}
			{/if}
		{/if}
		<br/>
		<br/><br/>
		<a href="core.php">{$smarty.const.BACK}</a>
	{elseif $view == "monuments"}
		{if $Normalcores != ""}
		<fieldset style="width: 70%; margin:auto;">
			<legend align="center" class="monlegend">{$smarty.const.NORMAL_TITLE}</legend>
			<table width="100%">
				<tr>
					<th width="50%" class="montitle">{$smarty.const.NAME}</th>
					<th width="30%" class="montitle">{$smarty.const.USER}</th>
					<th width="20%" class="montitle">{$smarty.const.WINS}</th>
				</tr>
				{section name=foo loop=$Normalname}
				<tr class="{cycle values="mon1,mon2"}">
					<td>{$Normalname[foo]}</td>
					<td><a href="view.php?view={$Normalid[foo]}">{$Normaluser[foo]}</a></td>
					<td align="right">{$Normalwins[foo]}</td>
				</tr>
				{/section}
			</table>
		</fieldset>
		{/if}
		<br/>
		{if $Magiccores != ""}
		<fieldset style="width: 70%; margin:auto;">
			<legend align="center" class="monlegend">{$smarty.const.MAGIC_TITLE}</legend>
			<table width="100%">
				<tr>
					<th width="50%" class="montitle">{$smarty.const.NAME}</th>
					<th width="30%" class="montitle">{$smarty.const.USER}</th>
					<th width="20%" class="montitle">{$smarty.const.WINS}</th>
				</tr>
				{section name=foo loop=$Magicname}
				<tr class="{cycle values="mon1,mon2"}">
					<td>{$Magicname[foo]}</td>
					<td><a href="view.php?view={$Normalid[foo]}">{$Magicuser[foo]}</a></td>
					<td align="right">{$Magicwins[foo]}</td>
				</tr>
				{/section}
			</table>
		</fieldset>
		{/if}
		<br/><br/>
		<a href="core.php">{$smarty.const.BACK}</a>
	{/if}
{/if}
{/strip}
