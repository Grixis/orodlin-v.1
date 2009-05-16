{strip}
{if !isset($Page) || empty($Page)}
<p>{$smarty.const.INFO}</p>
{else}
<p>{$smarty.const.$Page}</p>
{/if}
<a href="{$BackLocation}">{$smarty.const.BACK}</a>
{/strip}
