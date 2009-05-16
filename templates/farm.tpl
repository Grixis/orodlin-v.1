{*{strip}*}
{if $Step == ""}
    {$Farminfo}<ul>
    <li> <a href="farm.php?step=plantation">{$Aplantation}</a></li>
    <li> <a href="farm.php?step=house">{$Ahouse}</a></li>
    <li> <a href="farm.php?step=herbsinfo">{$Aencyclopedia}</a></li>
    </ul>
{/if}

{if $Step == "herbsinfo"}
    <b>{$Herbsinfo}</b><br /><br />
    <ul>
        <li>{$Ilaniinfo}<br /><br /></li>
        <li>{$Illaniasinfo}<br /><br /></li>
        <li>{$Nutariinfo}<br /><br /></li>
        <li>{$Dynallcainfo}<br /><br /></li>
    </ul><br />
{/if}

{if $Step == "house"}
    {$Houseinfo}<br /><br />
    <form method="post" action="farm.php?step=house&amp;action=dry">
        <input type="submit" value="{$Adry}" /> <select name="herb">
            {section name=farm loop=$Herbsname}
                <option value="{$Herbsoption[farm]}">{$Herbsname[farm]} {$Tamount} {$Herbsamount[farm]}</option>
            {/section}
        </select> {$Tdry} <input type="text" name="amount" size="5" /> {$Tpack}.
    </form>
    {if $Action == "dry"}
        <br />{$Message}
    {/if}
{/if}

{if $Step == "plantation"}
    {if $Action == ""}
        {$Farminfo}<br /><br />
        <a href="farm.php?step=plantation&amp;action=upgrade">{$Aupgrade}</a><br />
        {if $Lands != ""}
            <a href="farm.php?step=plantation&amp;action=sow">{$Asow}</a><br />
            <a href="farm.php?step=plantation&amp;action=chop">{$Achop}</a>
        {/if}
    {/if}
    {if $Action == "upgrade"}
    	{$Message}
		<fieldset><legend>{$Upgradeinfo}</legend>
		{if $Lands == ""}
			<a href="farm.php?step=plantation&amp;action=upgrade&amp;buy=L">{$Buyland} - {$Buylandcost} {$Tmith}</a>
		{else}
			<table>
				<tr>
				{if $noMithril}
				    <td colspan="2">{$noMithril}</td>
    	    	{else}
					<td width="180px;">{$Buyland}</td>
					<td>
						<form method="post" action="farm.php?step=plantation&amp;action=upgrade&amp;buy=L">
            				<input type="submit" value="{$Field}"/> {html_options name=amount options=$FarmLevels}
            			</form>
            		</td>
            	{/if}
            	</tr>
    	    	<tr>
    	    	{if $noGlass}
    	    		<td colspan="2">{$noGlassSpace}</td>
    	    	{else}
    	    		<td width="180px;">{$Buyglass}</td>
    	    		<td>
    	    			<form method="post" action="farm.php?step=plantation&amp;action=upgrade&amp;buy=G">
            				<input type="submit" value="{$Glass}"/> {html_options name=amount options=$GlassLevels}
            			</form>
            		</td>
    	    	{/if}
    	    	</tr>
    	    	<tr>
    	    	{if $noIrrigation}
    	    		<td colspan="2">{$noIrrigationSpace}</td>
    	    	{else}
    	    		<td width="180px;">{$Buyirrigation}
					<td>
						<form method="post" action="farm.php?step=plantation&amp;action=upgrade&amp;buy=I">
            		 		<input type="submit" value="{$Irrigation}"/> {html_options name=amount options=$IrrigationLevels}
            			</form>
            		</td>
    	    	{/if}
    	    	</tr>
    	    	<tr>
    	    	{if $noCreeper}
    	    		<td colspan="2">{$noCreeperSpace}</td>
    	    	{else}
    	    		<td width="180px;">{$Buycreeper}</td>
					<td>
						<form method="post" action="farm.php?step=plantation&amp;action=upgrade&amp;buy=C">
            		 		<input type="submit" value="{$Creeper}"/> {html_options name=amount options=$CreeperLevels}
            			</form>
            		</td>
    	    	{/if}
    	    	</tr>
    	    
    	    </table>
		{/if}
        </fieldset>
    {/if}
    {if $Action == "sow"}
        {$Sawinfo}<br /><br />
        <fieldset>
        	<legend>{$FarmInfo}</legend>
    	    {$Ilands} <b>{$Lands}</b><br />
	        {$Ifreelands} <b>{$Freelands}</b><br />
        	{$Iglass} <b>{$Glasshouse}</b><br />
    	    {$Iirrigation} <b>{$Irrigation}</b><br />
	        {$Icreeper} <b>{$Creeper}</b>
        </fieldset>
        <br>
        <form method="post" action="farm.php?step=plantation&amp;action=sow&amp;step2=next">
            <input type="submit" value="{$Asaw}" /> <input type="text" name="amount" size="5" /> {$Tlands} <select name="seeds">
                {section name=farm2 loop=$Seedsname}
                    <option value="{$Seedsoption[farm2]}">{$Seedsname[farm2]} {$Tamount} {$Seedsamount[farm2]}</option>
                {/section}
            </select>
        </form><br />
        {$Message}<br>
        <a href="farm.php?step=plantation&action=sow">{$Refresh}</a>
    {/if}
    {if $Action == "chop"}
        {$Chopinfo}<br /><br />
        <fieldset>
        	<legend>{$FarmInfo}</legend>
        	{section name=farm3 loop=$Herbsname}
            	- <a href="farm.php?step=plantation&amp;action=chop&amp;id={$Herbsid[farm3]}">{$Herbsname[farm3]}</a> {$Tamount} {$Herbsamount[farm3]} {$Tage} {$Herbsage[farm3]}<br />
        	{/section}
        </fieldset>
        {if $Herbid != "0"}
            <br /><br /><br />
            <form method="post" action="farm.php?step=plantation&amp;action=chop&amp;id={$Herbid}&amp;step2=next">
                <input type="submit" value="{$Agather}" /> {$Herbname} {$Froma} <input type="text" name="amount" size="5" /> {$Tlands3}
            </form>
        {/if}
        {$Message}
    {/if}
{/if}

{if $Step != ""}
    <br /><br />(<a href="farm.php">{$Aback}</a>)
{/if}
{*{/strip}*}