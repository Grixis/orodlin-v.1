{strip}
{if $Buy == 0}
   <br />{$Armorinfo}
    <ul>
    <li><a href="armor.php?dalej=A">{$Aarmors}</a></li>
    <li><a href="armor.php?dalej=H">{$Ahelmets}</a></li>
    <li><a href="armor.php?dalej=L">{$Alegs}</a></li>
    <li><a href="armor.php?dalej=S">{$Ashields}</a></li>
    </ul>
    {if $Next != ''}
        <table width="100%">
        <tr>
        <td width="30%"><b><u>{$Iname}</u></b></td>
        <td width="20%"><b><u>{$Iefect}</u></b></td>
        <td width="10%"><b><u>{$Idur}</u></b></td>
        <td width="12%"><b><u>{$Iagi}</u></b></td>
        <td width="12%"><b><u>{$Icost}</u></b></td>
        <td width="8%"><b><u>{$Ilevel}</u></b></td>
        <td width="8%"><b><u>{$Ioption}</u></b></td>
        </tr>
        {section name=number loop=$Name}
            <tr>
            <td>{$Name[number]}</td>
            <td>+{$Power[number]} Obrona</td>
            <td>{$Durability[number]}</td>
            <td>{$Agility[number]} %</td>
            <td>{$Cost[number]}</td>
            <td>{$Level[number]}</td>
            <td>- <a href="armor.php?buy={$Id[number]}">{$Abuy}</a>{if $Crime > "0"}<br /><a href="armor.php?steal={$Id[number]}">{$Asteal}</a>{/if}</td>
            </tr>
        {/section}
        </table>
    {/if}
{/if}

{if $Buy != 0}
    <br />{$Youpay} <b>{$Cost}</b> {$Andbuy} <b>{$Name} {$Ipower} + {$Power}</b>
	<br/><a href="armor.php">{$smarty.const.BACK}</a>
{/if}

{/strip}
