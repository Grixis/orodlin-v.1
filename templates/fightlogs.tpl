{strip}
<p>{$Tinfo}</p><br />

{section name=i loop=$arrText}
    {if $arrText[i] !=''}
        <b>{$smarty.section.i.iteration}</b> {$arrDate[i]} {$arrText[i]}<br/>
        {if $smarty.section.i.last}
           
        {elseif $smarty.section.i.iteration %10 == 0}
            <br />
        {/if}
    {/if}
{/section}
{/strip}