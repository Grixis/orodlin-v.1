{strip}
{include file="head.tpl"}
<!-- <a href="register.php?lang=pl"><img src="" title="Polski" border="0" alt="img_polish" /></a>
<a href="register.php?lang=en"><img src="" title="English" border="0" alt="img_english" /></a><br /><br /> -->

{if $Action == ""}
<div id="content">
    <div class="imgtitle"><img src="" alt="img_Rejestracja" /></div>
        <hr/>
		<div class="text">
                {$smarty.const.DESCRIPTION2}</div><hr/>
				<div class="text">{$smarty.const.DESCRIPTION}<div class="regico"><img src="" alt="img_info" /></div>{$smarty.const.INFO}<b>{$Players}</b> {$smarty.const.REGISTERED}.

                <div id="reg">
                    <form method="post" action="register.php?action=register">
                        <div class="register">{$smarty.const.NICK} <input type="text" name="user" /></div>
                        <div class="register">{$smarty.const.EMAIL}: <input type="text" name="email" /></div>
                        <div class="register">{$smarty.const.CONF_EMAIL} <input type="text" name="vemail" /></div>
                        <div class="register">{$smarty.const.PASSWORD}: <input type="password" name="pass" /></div>
                        {$smarty.const.T_LANG} <select name="lang">
                        {section name=register loop=$Lang}
                            <option value="{$Lang[register]}">{$Lang[register]}</option>
                        {/section}
                        </select>
                        <div class="register">{$smarty.const.IMAGE_CODE} <img src="codeimage.php" alt="img_codeimage"/> <input type="text" name="imagecode" /></div>
                        <div class="register"><input type="submit" value="{$smarty.const.REGISTER}" /></div>
                    </form>
                </div></div><br />
                <hr/>
                <div class="text"><div class="title2">{$smarty.const.SHORT_RULES}</div>
                    <ol>
                        <li>{$smarty.const.RULE1}</li>
                        <li>{$smarty.const.RULE2}</li>
                        <li>{$smarty.const.RULE3}</li>
                        <li>{$smarty.const.RULE4}</li>
                        <li>{$smarty.const.RULE5}</li>
                        <li>{$smarty.const.RULE6}</li>
                        <li>{$smarty.const.RULE7}</li>
                        <li>{$smarty.const.RULE8}</li>
                    </ol>
{/if}

{if $Action == "register"}
    <div id="content"><div class="center">{$smarty.const.REGISTER_SUCCESS}
{/if}
    </div>
</div>
{include file=right.tpl}
{include file=foot.tpl}
{/strip}