{strip}
<p>{$smarty.const.LNEXT} <b>{$smarty.now+3600|date_format:"%H:%M"}</b></p>
<p>{$smarty.const.LPOPULATION} {$Players}</p>
<p>{$smarty.const.LDISTRIBUTION}</p>
<ul>
{section name=k loop=$Desc}
	<li>{$Desc[k]}</li>
	<table style="border: 1px solid #636f6b;" cellspacing="0" cellpadding="2">
		<tr>
			<th>{$smarty.const.LCOUNTER}</th>
			<th width="160px;">{$Tables[k]}</th>
			<th>{$smarty.const.LAMOUNT}</th>
			<th>{$smarty.const.LPERCENTAGE}</th>
		</tr>
		{section name=i loop=$Stats[k]}
		<tr class="{cycle values="mon1,mon2"}">
			<td>{$smarty.section.i.iteration}.</td>
			<td>{$Stats[k][i][0]}</td>
			<td style="text-align:right">{$Stats[k][i][1]}</td>
			<td style="text-align:right">{$Stats[k][i][2]|string_format:"%.2f"}</td>
		</tr>
		{/section}
	</table><br />
{/section}
</ul>
{/strip}
