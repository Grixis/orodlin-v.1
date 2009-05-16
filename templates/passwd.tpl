{strip}
{include file="head.tpl"}

<!-- <a href="index.php?lang=pl&amp;step=lostpasswd"><img src="" title="Polski" border="0" alt="img_polish" /></a> 
<a href="index.php?lang=en&amp;step=lostpasswd"><img src="" title="English" border="0" alt="img_english" /></a><br /><br /> -->

{if $Action != "haslo" && $Message == ""}
<div id="content">
    <div class="imgtitle"><img src="" alt="img_Zapomniałem hasła" /></div>
        <img class="footer" src="" alt="img_Footer" />
            <div class="text">{$smarty.const.LOST_PASSWORD2}
                <form method="post" action="index.php?step=lostpasswd&amp;action=haslo">
                    {$smarty.const.EMAIL}:<input type="text" name="email" /><br />
                    <input type="submit" value="{$smarty.const.SEND}" />
                </form>
            </div><br />
        <hr />
{/if}

{if $Action == "haslo"}
    <div id="content"><div class="center">{$smarty.const.SUCCESS}</div>
{/if}

{if $Message != ""}
    <div id="content"><div class="center">{$Message}</div>
{/if}
</div>
{include file=right.tpl}
{include file="foot.tpl"}
{/strip}
