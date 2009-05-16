{strip}
{include file="head.tpl"}
{if $View == ""}
    <div id="content">
        <div class="imgtitle"><img src="" alt="img_Promocja gry" /></div><hr/><br /><br />
<div class="center">
<hr/>
<div class="text">{$smarty.const.PROMO_TEXT}</div>
<div class="promo">
<div class="center">
<b>Antypix</b> (80x15px):<br />
<a href="{$Gameadress}"><img class="promo" src="" alt="img_antypix"/></a></div>

<input id="1" readonly="readonly" size="50" value="[URL={$Gameadress}][IMG]{$Gameadress}/images/promo/antypix.jpg[/IMG][/URL]" /><label for="1">BBCode (1)</label><br />
    <input id="2" readonly="readonly" size="50" value="[url={$Gameadress}][img={$Gameadress}/images/promo/antypix.jpg][/url]" /><label for="2">BBCode (2)</label><br />
    <input id="3" readonly="readonly" size="50" value="<a href=&quot;{$Gameadress}&quot;><img src=""&quot;&quot; border=&quot;0&quot; alt=&quot;img_promocja&quot; /></a>" /><label for="3">HTML</label><br />
    <input id="4" readonly="readonly" size="50" value="{$Gameadress}/images/promo/antypix.jpg" /><label for="4">URL</label><br /><br />

<div class="center">
<b>Button</b> (100x30px):<br />
<a href="{$Gameadress}"><img class="promo" src="" alt="img_button"/></a></div>

<input id="11" readonly="readonly" size="50" value="[URL={$Gameadress}][IMG]{$Gameadress}/images/promo/button.jpg[/IMG][/URL]" /><label for="11">BBCode (1)</label><br />
    <input id="12" readonly="readonly" size="50" value="[url={$Gameadress}][img={$Gameadress}/images/promo/button.jpg][/url]" /><label for="12">BBCode (2)</label><br />
    <input id="13" readonly="readonly" size="50" value="<a href=&quot;{$Gameadress}&quot;><img src=&quot;&quot; border=&quot;0&quot; alt=&quot;img_button&quot; /></a>" /><label for="13">HTML</label><br />
    <input id="14" readonly="readonly" size="50" value="{$Gameadress}/images/promo/button.jpg" /><label for="14">URL</label><br /><br />

<div class="center">
<b>Userbar</b> (350x19px):<br />
<a href="{$Gameadress}"><img class="promo" src="" alt="img_userbar"/></a></div>

<input id="21" readonly="readonly" size="50" value="[URL={$Gameadress}][IMG]{$Gameadress}/images/promo/userbar.jpg[/IMG][/URL]" /><label for="21">BBCode (1)</label><br />
    <input id="22" readonly="readonly" size="50" value="[url={$Gameadress}][img={$Gameadress}/images/promo/userbar.jpg][/url]" /><label for="22">BBCode (2)</label><br />
    <input id="23" readonly="readonly" size="50" value="<a href=&quot;{$Gameadress}&quot;><img src=&quot;&quot; border=&quot;0&quot; alt=&quot;img_userbar&quot; /></a>" /><label for="23">HTML</label><br />
    <input id="24" readonly="readonly" size="50" value="{$Gameadress}/images/promo/userbar.jpg" /><label for="24">URL</label><br /><br />

<div class="center">
<b>Banner</b> (468x60px):<br />
<a href="{$Gameadress}"><img class="promo" src="" alt="img_baner"/></a></div>

<input id="31" readonly="readonly" size="50" value="[URL={$Gameadress}][IMG]{$Gameadress}/images/promo/banner.jpg[/IMG][/URL]" /><label for="31">BBCode (1)</label><br />
    <input id="32" readonly="readonly" size="50" value="[url={$Gameadress}][img={$Gameadress}/images/promo/banner.jpg][/url]" /><label for="32">BBCode (2)</label><br />
    <input id="33" readonly="readonly" size="50" value="<a href=&quot;{$Gameadress}&quot;><img src=&quot;&quot; border=&quot;0&quot; alt=&quot;img_banner&quot; /></a>" /><label for="33">HTML</label><br />
    <input id="34" readonly="readonly" size="50" value="{$Gameadress}/images/promo/banner.jpg" /><label for="34">URL</label><br /><br />
</div>
<hr />
    </div>


<!-- <a href="index.php?lang=pl"><img src="" title="Polski" border="0" alt="img_polish" /></a> 
<a href="index.php?lang=en"><img src="" title="English" border="0" alt="img_english"/></a> -->

{/if}

{include file=right.tpl}
{include file=foot.tpl}
{/strip}
