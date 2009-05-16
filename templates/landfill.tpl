{strip}
{if $Action == ""}
   <p>{$Landinfo} <b>{$Gold}</b> {$Landinfo2}</p>
    <form method="post" action="landfill.php?action=work">
    <input type="submit" value="{$Awork}" /> <input type="text" name="amount" value="1" size="5" /> {$Times}</form>
{/if}
{if $Action == "work"}
    <p>{$Inwork} <b>{$Amount}</b> {$Inwork2} <b>{$Gain}</b> {$Goldcoins}</p>
    (<a href="landfill.php">{$Aback}</a>)
{/if}
{/strip}
