{strip}
{if !in_array($smarty.get.view, array('book','pantheon','service'))}
    {$smarty.const.TEMPLE_INFO}
    {if $Location == 'Altara'}
       {$God2}. {$smarty.const.TEMPLE_INFO2}
    {/if}
    <ul {if $Graphstyle=="Y"}class="sword"{/if}>
        <li><a href="temple.php?view=service">{$smarty.const.A_WORK}</a></li>
        <li><a href="temple.php?view=prayer">{$smarty.const.A_PRAY}</a></li>
        <li><a href="temple.php?view=book">{$smarty.const.A_BOOK}</a></li>
        <li><a href="temple.php?view=pantheon">{$smarty.const.A_PANTHEON}</a></li>
    </ul>
{/if}

{if isset($Message)}
    <p class="bless">{$Message}.</p>
{/if}

{if $smarty.get.view == 'service'}
    <p>{$smarty.const.TEMPLE_INFO_W} {$God} {$smarty.const.TEMPLE_INFO2_W}</p>
    <form method="post" action="temple.php?view=service">
    {$smarty.const.I_WANT} <input type="text" size="3" value="0" name="rep" /> {$smarty.const.T_AMOUNT}. <input type="submit" value="{$smarty.const.A_WORK_2}" />
    </form>
    <br />
    <a href="temple.php">{$smarty.const.BACK}</a>
{/if}

{if $smarty.get.view == 'prayer'}
    <div id="prayer"><form method="post" action="temple.php?view=prayer">
    <p>{$smarty.const.CHOOSE_PRAYER}</p>
        <input id="pr1" type="radio" name="praytype" value="1" checked="checked" /><label for="pr1">{$smarty.const.PRAY1} - (1 {$smarty.const.ENERGY_PTS2})</label><br/>
        <input id="pr2" type="radio" name="praytype" value="2" /><label for="pr2">{$smarty.const.PRAY2} - (2)</label><br/>
        <input id="pr3" type="radio" name="praytype" value="4" /><label for="pr3">{$smarty.const.PRAY3} - (4)</label><br/>
        <input id="pr4" type="radio" name="praytype" value="6" /><label for="pr4">{$smarty.const.PRAY4} - (6)</label><br/>
		<input id="pr5" type="radio" name="praytype" value="0" /><label for="pr5">{$smarty.const.PRAY5}</label><br/>
    
    
        <p>{$smarty.const.BLESS_FOR}</p>
        <div class="columnleft">{section name=temple loop=$Blessings}
			{if $Indices[temple] > 6}</div><div class="columnright">{/if}
            <input id="bl{$smarty.section.temple.iteration}" type="radio" name="pray" value="{$Indices[temple]}" {if $smarty.section.temple.first} checked="checked"{/if} /><label for="bl{$smarty.section.temple.iteration}">{$Blessings[temple][0]} ({$Blessings[temple][1]})</label><br/>
        {/section}</div>
        <br />
        <input type="submit" value="{$smarty.const.YES}" style="clear: both; display: block;" />
    </form></div>
{/if}

{if $smarty.get.view == 'book'}
    {if $Next == 0}
        {$smarty.const.BOOK_INFO}
    {/if}
    <i>{$Booktext}</i><br />
    {if $Next < 2}
        <a href="temple.php?view=book&amp;book={$Next+1}">{$smarty.const.NEXT_PAGE}</a>
    {/if}
{/if}

{if $smarty.get.view == 'pantheon'}
    {section name=pantheon loop=$Godnames}
        <fieldset style="width:90%">
            <legend><b>{$Godnames[pantheon]}</b></legend>
            {$Goddesc[pantheon]}
        </fieldset><br />
    {/section}
{/if}

{/strip}
