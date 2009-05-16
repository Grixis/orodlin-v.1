{strip}
{include file="head.tpl"}
{if $Step == ""}
    <div id="content">
        <div class="imgtitle"><img src="" alt="img_Prolog" /></div>
		<hr/>
		<div class="text">{$smarty.const.PROLOG}</div>
		<hr/>
		
		<div class="imgtitle"><img src="" alt="img_Prolog" />
		</div><hr/>		
		<div class="text">
                    {$smarty.const.WHAT_IS} {$Gamename}? {$smarty.const.DESCRIPTION} <a href="index.php?step=rules">{$smarty.const.CODEX} {$Gamename}</a> {$smarty.const.CODEX2}{$Codexdate}
                    {$smarty.const.ADMIN} {$Adminname} <a href="mailto:{$Adminmail}">{$Adminmail1}</a></div><br />
                        <hr/><br /><br />
                    <div class="newsimg"></div><br />
                    <div class="news">{$Update}</div><br /><hr/>
    </div>

<!-- <a href="index.php?lang=pl"><img src="" title="img_Polski" border="0" /></a> 
<a href="index.php?lang=en"><img src="" title="img_English" border="0" /></a> -->

{/if}
{include file=right.tpl}
{include file=foot.tpl}
{/strip}
