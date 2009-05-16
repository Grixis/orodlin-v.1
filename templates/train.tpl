{strip}
{if $Action == ''}
    {$smarty.const.TRAIN_INFO}<br /><ul>
    {section name=k loop=$StatsDesc}
	<li>{$PlayerCost[k]} {$smarty.const.ENERGY} {$StatsDesc[k]}</li>
	{/section}</ul>
    <form method='post' action='train.php?action=train'>
    {$smarty.const.I_WANT} <select name='train'>
		{section name=j loop=$StatOptions}
			<option value={$StatOptions[j]}>{$TrainedStats[j]}</option>
		{/section}
   </select> 
    <input type='text' size='4' value='0' name='rep' /> {$smarty.const.T_AMOUNT}. <input type='submit' value='{$smarty.const.A_TRAIN}' />
    </form>
{/if}
{if $Action == 'train'}
	{$smarty.const.COST} <b>{$energyCost}</b> {$smarty.const.ENERGY_COST}<br />
	{$smarty.const.WILL_GAIN} <b>{$gainedStat}</b> {$gainedStatName}.
    <form method='post' action='train.php?action=train&amp;step=next'>
        <input type='hidden' name='train' value='{$Train}' />
        <input type='hidden' name='rep' value='{$Rep}' />
        <input type='submit' value='{$smarty.const.A_TRAIN}' />
    </form>
{/if}
{/strip}