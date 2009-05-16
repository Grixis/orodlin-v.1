{strip}
{include file="head.tpl"}
{if $View == ""}
    <div id="content">
        <div class="imgtitle"><img src="" alt="img_FAQ - najczęściej zadawane pytania" /></div><hr/><div class="text"><br />
                <div class="left_text">
                    <ul class="sword"> 
                {section name=sec1 loop=$Halfnumber start=0}
                    <li><a name="top" href="faq.php?view={$Counter[sec1]}">{$Titles[sec1]}</a></li>
                {/section}</ul>
                </div>
                <div class="right_text"> 
                    <ul class="sword"> 
                {section name=sec2 loop=$Counttopics start=$Halfnumber}
                    <li><a href="faq.php?view={$Counter[sec2]}">{$Titles[sec2]}</a></li>
                {/section}</ul>
                </div></div>
				<br clear="left"/>
            <hr/>
			<div class="text">{$Faq_text}</div>
            <hr/>
            <a href="#top">{$smarty.const.BACK}</a><br /><br />
    </div>

<!-- <a href="index.php?lang=pl"><img src="" title="img_Polski" border="0" /></a> 
<a href="index.php?lang=en"><img src="" title="img_English" border="0" /></a> -->

{/if}

{include file=right.tpl}
{include file=foot.tpl}
{/strip}
