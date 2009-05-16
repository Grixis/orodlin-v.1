<?xml version="1.0" encoding="{$smarty.const.CHARSET}"?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$Gamename} :: {$Pagetitle}</title>
<link type="text/css" rel="Stylesheet" href="main.css" />
<link type="image/png" rel="shortcut icon" href="images/misc/favicon.ico" />
<meta http-equiv="Content-Type" content="text/html; charset={$smarty.const.CHARSET}" />
<link rel="alternate" type="application/xml" title="RSS" href="{$Gameadress}/rss.php" />
{$Meta}
<meta http-equiv="Content-Language" content="pl" />
{if $Metakeywords != ""}
    <meta name="keywords" content="{$Metakeywords}" />
{/if}
{if $Metadescription != ""}
    <meta name="description" content="{$Metadescription}" />
{/if}
<!--[if lt IE 7.]>
<script defer type="text/javascript" src="javascript/pngfix.js"></script>
<![endif]-->
</head>
<body>
<div id="ads"><script type="text/javascript"><!--
google_ad_client = "pub-6335658040478102";
google_alternate_color = "000000";
google_ad_width = 120;
google_ad_height = 240;
google_ad_format = "120x240_as";
google_ad_type = "text_image";
google_ad_channel = "";
google_color_border = "000000";
google_color_bg = "000000";
google_color_link = "E5C39A";
google_color_text = "E4C49B";
google_color_url = "DAAB71";
google_ui_features = "rc:0";
//-->
</script>
<script type="text/javascript"
 src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script></div>
{strip}
<div id="container">
    <div id="logo">
        <a href="index.php" title="Orodlin">
            <img src="" alt="img_logo" />
        </a>
    </div>
    <div id="left"><div class="topmenu"></div><div class="submenu">
                <div class="imghead"><img src="" alt="img_Menu" width="58" height="18" /></div>
                    <ul id="menu"><li><a href="index.php">{$smarty.const.WELCOME}</a></li>
                                  <li><a href="register.php">{$smarty.const.REGISTER}</a></li>
                                  <li><a href="index.php?step=rules">{$smarty.const.RULES}</a></li>
                                  <li><a href="faq.php">{$smarty.const.FAQ}</a></li>
                                  <li><a href="team.php">{$smarty.const.TEAM}</a></li>
                                  <li><a class="forum" href="http://orodlin.webhost.pl/forum/">{$smarty.const.FORUMS}</a></li>
                                  <li><a href="promo.php">{$smarty.const.PROMO}</a></li>
                                  <li><a href="donate.php">{$smarty.const.DONATE}</a></li>
                    </ul>
            </div><div class="bottommenu"></div><div class="topmenu"></div><div class="submenu">
                <div class="imghead"><img src="" alt="img_Wejdź" width="58" height="17" /></div>
                    <div class="center"><form method="post" action="updates.php">
                        <img src="" class="img_logicon" alt="" width="11" height="11" />{$smarty.const.EMAIL}: <input type="text" name="email" size="17" /><br />
                        <img src="" class="img_logicon" alt="" width="11" height="11" />{$smarty.const.PASSWORD}: <input type="password" name="pass" size="17" /><br /><br />
                        <input type="submit" value="{$smarty.const.LOGIN}" /><br /><br />
                    </form>
                <div class="lostpasswd">
                    <a href="index.php?step=lostpasswd">{$smarty.const.LOST_PASSWORD}</a>
                </div></div>
            </div><div class="bottommenu"></div><div class="topmenu"></div><div class="submenu">
                <div class="imghead"><img src="" alt="img_Statystyki" width="91" height="19" /></div>
                    {$smarty.const.CURRENT_TIME}: <b>{$Time}</b><br /><br />
                    {$smarty.const.VISIT}: {$Logcount}<br />
                    {$smarty.const.DAILY}: {$Logcountday}<br /><br />
                    {$smarty.const.IN_GAME}: <b>{$Online}</b><br />
                    {$smarty.const.ACTIVE}: <b>{$Players}</b><br />
                    {$smarty.const.PLAYERS_EVER}: <b>{$Usersever}</b><br />
                    <br/>
                    {$smarty.const.LAST_REGISTERED_PRE} <b>{$LastName}</b> ({$LastID}).<br/>
                    <br />{$smarty.const.RECORD}: <strong>{$numRecord}</strong> {$smarty.const.DAY} {$When1} {$smarty.const.HOUR} {$When2}.
            </div><div class="bottommenu"></div>
                <div class="button"><a href="rss.php" title="RSS 2.0"><img src="" alt="RSS 2.0" /></a></div></div>
{/strip}
