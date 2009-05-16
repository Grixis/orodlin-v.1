<?xml version="1.0" encoding="{$Charset}"?>
{strip}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>{$Gamename} :: {$Title}</title>
    <link type="text/css" rel="stylesheet" href="css/{$Style}" />
    {if $Graphstyle == 'Y'}
        {if $Graphic == 'default'}
            <link type="text/css" rel="stylesheet" href="css/main/{$Graphic}/layout.css" />
            <link type="text/css" rel="stylesheet" href="css/main/{$Graphic}/locations/{$LocStyle}" />
        {else}
            <link type="text/css" rel="stylesheet" href="css/main/{$Graphic}/style.css" />
        {/if}
    {else}
        <link type="text/css" rel="stylesheet" href="css/main/text.css" />
    {/if}
    {if $Title=='Strażnica'}
        <link type="text/css" rel="stylesheet" href="css/javascript/tabs.css" />
        <link type="text/css" rel="stylesheet" href="css/javascript/slider.css" />
        <link type="text/css" rel="stylesheet" href="css/javascript/dragdrop.css" />
    {/if}
    <link type="image/png" rel="shortcut icon" href="images/misc/favicon.ico" />
{*    <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>*}
    <script src="javascript/lib/jquery.pack.js" type="text/javascript"></script>
    {if $Title=='Strażnica'}
        <script src="javascript/lib/jquery_plugins/jquery.history_remote.pack.js" type="text/javascript"></script>
        <script src="javascript/lib/jquery_plugins/jquery.tabs.pack.js" type="text/javascript"></script>
        <script src="javascript/lib/jquery_plugins/interface.js" type="text/javascript"></script>
        <script src="javascript/lib/jquery_plugins/jquery.form.js" type="text/javascript"></script>
        <script src="javascript/outposts.js" type="text/javascript"></script>
        <script src="javascript/tabs.js" type="text/javascript"></script>
    {/if}
    <meta http-equiv="Content-Type" content="text/html; charset={$Charset}" />
<!--[if lt IE 7.]>
<script defer type="text/javascript" src="javascript/pngfix.js"></script>
<![endif]-->
    <script type="text/javascript">
    {literal}
        function showHideImage(id,imgSource)
        {
            img = document.getElementById(id);
            img.src = (img.src.match(imgSource) == null) ? imgSource : '';
        }
    {/literal}
    </script>
</head>
<body onload="window.status='{$Gamename}';
    {if $Title=='Tawerna'}document.getElementById('msg').focus();{/if}
    {if $Title=='Strażnica'}$('#tabcontainer').tabs({ldelim}fxFade: true, fxSpeed: 'fast', remote: true{rdelim});{/if}">
<div id="container">
    <div id="top"></div>
    {if $Stephead != "new"}
        <div id="leftbar"><div class="topmenu"></div><div class="submenu">
                    <div id="stats">
                {if $Graphstyle == "Y" && $Graphic == "default"}<div class="imghead"><img src="" alt="img_Statystyki" width="91" height="19" /></div>{else}<div class="txtheader">{$smarty.const.N_STATISTICS}</div>{/if}
                            <center><b>{$Name}</b> ({$Id})</center><br />
                            <div class="menuleft">{if $Graphstyle == "Y"}<img src="" alt="img_level" class="vmiddle" width="16" height="16" />{/if}<b>{$smarty.const.LEVEL}:</b> {$Level}</div>
                            <div class="menuleft">{if $Graphstyle == "Y"}<img src="" alt="img_exp" class="vmiddle" width="16" height="16" />{/if}<b>{$smarty.const.EXP_PTS}:</b> {$Exp}/{$Expneed} ({$Percent}%)</div>
                            {if $Graphbar == "Y"}
                                <img src="includes/graphbar.php?statusbar=exp" height="4" width="{$Expper}%" alt="{$smarty.const.EXP_PTS}" title="{$smarty.const.EXP_PTS}: {$Percent}%" style="margin-top: 2px; margin-bottom: 2px; border-style: outset; border-right: 0px;" border="0" /><img src="" height="4" width="{$Vial}%" alt="img_{$smarty.const.EXP_PTS}" title="{$smarty.const.EXP_PTS}: {$Percent}%" style="margin-top: 2px; margin-bottom: 2px; border-style: outset; border-left: 0px;" border="0" /><br />
                            {/if}
                            <div class="menuleft">{if $Graphstyle == "Y"}<img src="" alt="img_health" class="vmiddle" width="16" height="16" />{/if}<b>{$smarty.const.HEALTH_PTS}:</b> {$Health}/{$Maxhealth}</div>
                            {if $Graphbar == "Y"}
                                <img src="includes/graphbar.php?statusbar=health" height="4" width="{$Barsize}%" alt="{$smarty.const.HEALTH_PTS}" title="{$smarty.const.HEALTH_PTS}: {$Healthper}%" style="margin-top: 2px; margin-bottom: 2px; border-style: outset; border-right: 0px;" border="0" /><img src="includes/graphbar2.php" height="4" width="{$Vial2}%" alt="{$smarty.const.HEALTH_PTS}" title="{$smarty.const.HEALTH_PTS}: {$Healthper}%" style="margin-top: 2px; margin-bottom: 2px; border-style: outset; border-left: 0px;" border="0" /><br />
                            {/if}
                            {if $Spells !=''}
                                <div class="menuleft">{if $Graphstyle == "Y"}<img src="" alt="img_mana" class="vmiddle" width="16" height="16" />{/if}<b>{$smarty.const.MANA_PTS}:</b> {$Mana} </div>
                                {if $Graphbar == "Y"}
                                    <img src="includes/graphbar.php?statusbar=mana" height="4" width="{$Barsize2}%" alt="{$smarty.const.MANA_PTS}" title="{$smarty.const.MANA_PTS}: {$Manaper}%" style="margin-top: 2px; margin-bottom: 2px; border-style: outset; border-right: 0px;" border="0" /><img src="includes/graphbar2.php" height="4" width="{$Vial3}%" alt="{$smarty.const.MANA_PTS}" title="{$smarty.const.MANA_PTS}: {$Manaper}%" style="margin-top: 2px; margin-bottom: 2px; border-style: outset; border-left: 0px;" border="0" /><br />
                                {/if}
                            {/if}
                            <div class="menuleft">{if $Graphstyle == "Y"}<img src="" alt="img_energy" class="vmiddle" width="16" height="16" />{/if}<b>{$smarty.const.ENERGY_PTS}:</b> {$Energy}/{$Maxenergy}/{$Maxenergy*63}</div><br />
                            <div class="menuleft">{if $Graphstyle == "Y"}<img src="" alt="img_gold" class="vmiddle" width="16" height="16" />{/if}<b>{$smarty.const.GOLD_IN_HAND}:</b> {$Gold}</div>
                            <div class="menuleft">{if $Graphstyle == "Y"}<img src="" alt="img_bank" class="vmiddle" width="16" height="16" />{/if}<b>{$smarty.const.GOLD_IN_BANK}:</b> {$Bank}</div>
                    </div>
                </div><div class="bottommenu"></div><div class="topmenu"></div><div class="submenu">
                    <div id="navigation">
                {if $Graphstyle == "Y" && $Graphic == "default"}<div class="imghead"><img src="" alt="img_Nawigacja" width="95" height="21" /></div>{else}<div class="txtheader">{$smarty.const.NAVIGATION}</div>{/if}
                        <ul>
                            <li><a href="stats.php">{$smarty.const.N_STATISTICS}</a></li>
                            <li><a href="zloto.php">{$smarty.const.N_ITEMS}</a></li>
                            <li><a href="equip.php">{$smarty.const.N_EQUIPMENT}</a></li>
                            {$Spells}
                        </ul>
                        <ul>
                            {$Location}
                            {$Lbank}
                            {$Battle}
                            {$Hospital}
                            {$Tribe}
                        </ul>
                        <ul>
                            <li><a href="log.php">{$smarty.const.N_LOG}</a> [<span id="logcount"></span>] </li>
                            <li><a href="mail.php">{$smarty.const.N_POST}</a> [<span id="mailcount"></span>] </li>
                            <li><a href="notatnik.php">{$smarty.const.N_NOTES}</a></li>
                            <li><a href="forums.php?view=categories">{$smarty.const.N_FORUMS}</a></li>
                            {$Tforum}
                            <li><a href="chat.php?room=izba">{$smarty.const.N_INN_I}</a> [{$PlayersI}]</li>
                            <li><a href="chat.php?room=piwnica">{$smarty.const.N_INN_P}</a> [{$PlayersP}]</li>
                        </ul>
                        {if isset ($ArrLinks[0])}
                            <ul>
                                {foreach from=$ArrLinks item=i}
                                <li><a href="{$i.file}">{$i.text}</a></li>
                                {/foreach}
                            </ul>
                        {/if}
                        <ul>
                            <li><a target="blank" href="faq.php">{$smarty.const.FAQ}</a></li>
                            <li><a href="help.php">{$smarty.const.N_HELP}</a></li>
                            <li><a href="team.php?int=1">{$smarty.const.TEAM}</a></li>
                            <li><a href="map.php">{$smarty.const.N_MAP}</a></li>
                        </ul>
                        <ul>
                            <li><a href="account.php">{$smarty.const.N_OPTIONS}</a></li>
                            <li><a href="logout.php?did={$Id}">{$smarty.const.N_LOGOUT}</a></li>
                        </ul>
                            {$Special}
                    </div>
                </div><div class="bottommenu"></div></div>
        <div id="content">
    {/if}
    {if $Stephead == "new"}
        <div id="newspaper">
    {/if}
            <div class="bar-header">
                {$Title} <a class="help" href="help.php?page={$FileName}"><img src="" alt="img_?" title="Co to za miejsce?" width="13" height="14" /></a>
            </div>
{/strip}
