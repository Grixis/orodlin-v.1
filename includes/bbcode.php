<?php
/**
 *   File functions:
 *   Convert HTML to BBcode or BBcode to HTML
 *
 *   @name                 : bbcode.php
 *   @copyright            : (C) 2004,2005,2006 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @version              : 1.2
 *   @since                : 26.09.2006
 *
 */

//
//
//       This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; either version 2 of the License, or
//   (at your option) any later version.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
//
// $Id: bbcode.php 628 2006-09-26 11:18:02Z thindil $

/**
* Function to replace bad words with flower tags
*/
function censorship($text)
{
    global $db;
    $arrBadWords = $db -> GetCol('SELECT `bword` FROM `bad_words`');
    $k = (empty($arrBadWords)) ? 0 : count($arrBadWords);
    for ($i = 0; $i < $k; $i++)
    {
        $arrBwords[$i] = "#[^ ]*".$arrBadWords[$i]."[^ ]*#si";
    }
    $text = str_replace("\n", " \n ", $text);
    if (!empty($arrBwords)) $text = preg_replace($arrBwords, '[kwiatek]', $text);
    $text = str_replace(" \n ", "\n", $text);
    return $text;
}

/**
* Function convert bbcode tags [b] on HTML <b>, add smiles, new lines, quotes
*/
function bbcodetohtml($text)
{
	/**
    * Delete HTML tags from text
    */
    $text = htmlspecialchars($text);

    /**
     * Replace bbcode tags
     */
    $arrBBon = array('[b]', '[i]', '[u]', '[center]', '[quote]');
    $arrBBoff = array('[/b]', '[/i]', '[/u]', '[/center]', '[/quote]');
    $arrHtmlon = array("<b>", "<i>", "<u>", "<center>", "<br />Cytat:<br /><i>");
    $arrHtmloff = array("</b>", "</i>", "</u>", "</center>", "&nbsp;</i>");
    $arrRegex = array("#\[b\](.*?)\[\/b\]#si", "#\[i\](.*?)\[\/i\]#si", "#\[u\](.*?)\[\/u\]#si", "#\[center\]([^\*]*?)\[\/center\]#si", "#\[quote\](.*?)\[\/quote\]#si");
    for ($j = 0; $j < 5; $j++)
    {
        $intTest = preg_match_all($arrRegex[$j], $text, $arrText, PREG_PATTERN_ORDER);
        if ($intTest)
        {
            $i = 0;
            foreach($arrText[1] as $strText)
            {
                $text = str_replace($arrText[0][$i], $arrHtmlon[$j].$strText.$arrHtmloff[$j], $text);
                $i ++;
            }
        }
        $text = str_replace($arrBBon[$j], "", $text);
        $text = str_replace($arrBBoff[$j], "", $text);
    }
    /**
    * Change \n on <br />
    */
    $text = nl2br($text);
    /**
    * Add smiles
    */
    $text = str_replace(":)","<img src=\"images/smileys/blue/smile.png\" title=\"uśmiech\" />", $text);
    $text = str_replace(":-)","<img src=\"images/smileys/blue/smile.png\" title=\"uśmiech\" />", $text);

    $text = str_replace(":D","<img src=\"images/smileys/blue/grins.png\" title=\"śmiech\" />", $text);
    $text = str_replace(":d",'<img src=\"images/smileys/blue/grins.png\" title=\"śmiech\" />', $text);
    $text = str_replace(":-D","<img src=\"images/smileys/blue/grins.png\" title=\"śmiech\" />", $text);
    $text = str_replace(":-d","<img src=\"images/smileys/blue/grins.png\" title=\"śmiech\" />", $text);
    $text = str_replace(":>","<img src=\"images/smileys/blue/grins.png\" title=\"śmiech\" />", $text);
    $text = str_replace(":->","<img src=\"images/smileys/blue/grins.png\" title=\"śmiech\" />", $text);
    $text = str_replace(":&gt;","<img src=\"images/smileys/blue/grins.png\" title=\"śmiech\" />", $text);
    $text = str_replace(":-&gt;","<img src=\"images/smileys/blue/grins.png\" title=\"śmiech\" />", $text);

    $text = str_replace(":]","<img src=\"images/smileys/blue/cheesy.png\" title=\"kontent\" />", $text);
    $text = str_replace(":-]","<img src=\"images/smileys/blue/cheesy.png\" title=\"kontent\" />", $text);

    $text = str_replace(";)","<img src=\"images/smileys/blue/wink.png\" title=\"żart\" />", $text);
    $text = str_replace(";-)","<img src=\"images/smileys/blue/wink.png\" title=\"żart\" />", $text);

    $text = str_replace(":p","<img src=\"images/smileys/blue/razz.png\" title=\"jęzor\" />", $text);
    $text = str_replace(":P","<img src=\"images/smileys/blue/razz.png\" title=\"jęzor\" />", $text);
    $text = str_replace(":-p","<img src=\"images/smileys/blue/razz.png\" title=\"jęzor\" />", $text);
    $text = str_replace(":-P","<img src=\"images/smileys/blue/razz.png\" title=\"jęzor\" />", $text);

    $text = str_replace(":(","<img src=\"images/smileys/blue/sad.png\" title=\"smutny\" />", $text);
    $text = str_replace(":-(","<img src=\"images/smileys/blue/sad.png\" title=\"smutny\" />", $text);

    $text = str_replace(":\'-(","<img src=\"images/smileys/blue/cry.png\" title=\"płacz\" />", $text);
    $text = str_replace(":\'(","<img src=\"images/smileys/blue/cry.png\" title=\"płacz\" />", $text);
    $text = str_replace(";-(","<img src=\"images/smileys/blue/cry.png\" title=\"płacz\" />", $text);
    $text = str_replace(";(","<img src=\"images/smileys/blue/cry.png\" title=\"płacz\" />", $text);

    $text = str_replace(":o)","<img src=\"images/smileys/blue/clown.png\" title=\"klaun\" />", $text);
    $text = str_replace(":*)","<img src=\"images/smileys/blue/clown.png\" title=\"klaun\" />", $text);

    $text = str_replace(":-/","<img src=\"images/smileys/blue/slash.png\" title=\"yyy?\" />", $text);
    $text = preg_replace("/:\/([^\/])/","<img src=\"images/smileys/blue/slash.png\" title=\"yyy?\" />\\1", $text);

    $text = str_replace(":-|","<img src=\"images/smileys/blue/slash2.png\" title=\"sceptyczny\" />", $text);
    $text = str_replace(":|","<img src=\"images/smileys/blue/slash2.png\" title=\"sceptyczny\" />", $text);

    $text = str_replace(":o","<img src=\"images/smileys/blue/shocked.png\" title=\"zdziwiony\" />", $text);
    $text = str_replace("8-(","<img src=\"images/smileys/blue/shocked.png\" title=\"zdziwiony\" />", $text);
    $text = str_replace("8(","<img src=\"images/smileys/blue/shocked.png\" title=\"zdziwiony\" />", $text);

    $text = str_replace("B-)","<img src=\"images/smileys/blue/cool.png\" title=\"cool\" />", $text);
    $text = str_replace("B)","<img src=\"images/smileys/blue/cool.png\" title=\"cool\" />", $text);
    $text = str_replace("8-)","<img src=\"images/smileys/blue/cool.png\" title=\"cool\" />", $text);
    $text = str_replace("8)","<img src=\"images/smileys/blue/cool.png\" title=\"cool\" />", $text);

    $text = str_replace(":m","<img src=\"images/smileys/blue/mad.png\" title=\"wściekły\" />", $text);

    $text = str_replace(";>","<img src=\"images/smileys/blue/smartass.png\" title=\"mądrala\" />", $text);
    $text = str_replace(";&gt;","<img src=\"images/smileys/blue/smartass.png\" title=\"mądrala\" />", $text);

    $text = str_replace(":%","<img src=\"images/smileys/blue/shame.png\" title=\"wstyd\" />", $text);

    $text = str_replace("<piwo>","<img src=\"images/smileys/blue/scoop.png\" title=\"kufel\" />", $text);
    $text = str_replace("&lt;piwo&gt;","<img src=\"images/smileys/blue/scoop.png\" title=\"kufel\" />", $text);

    $text = str_replace(":[","<img src=\"images/smileys/blue/mad2.png\" title=\"zły\" />", $text);

    $text = str_replace(">;p","<img src=\"images/smileys/blue/squirrel.png\" title=\"wiewiór\" />", $text);
    $text = str_replace("&gt;;p","<img src=\"images/smileys/blue/squirrel.png\" title=\"wiewiór\" />", $text);

    $text = str_replace(":*","<img src=\"images/smileys/blue/cmok.png\" title=\"cmok\" />", $text);

    $text = str_replace(":E","<img src=\"images/smileys/blue/oldschool.png\" title=\"oldschool\" />", $text);

    $text = str_replace("<3","<img src=\"images/smileys/blue/heart2.png\" title=\"serduszko\" />", $text);
    $text = str_replace("&lt;3","<img src=\"images/smileys/blue/heart2.png\" title=\"serduszko\" />", $text);

    $linkpatterns = array('#(http|https|ftp):\/\/[a-zA-Z0-9\-.]+\.[a-zA-Z]+(:[0-9]{1,5})?(\/[^<\n\s]*)?#',
                          '#([^\/]{2})(www\.[a-zA-Z0-9\-.]+\.[a-zA-Z]{1,4}(:[0-9]{1,5})?(\/[^<\n\s]*)?)#');
    $linkchanges = array('<a href="\0" target="_blank">\0</a>',
                         '\1<a href="http://\2" target="_blank">\2</a>');
    $text = preg_replace($linkpatterns, $linkchanges, $text);
    // Add "climate" to things written between asterisks (*text*)
    $text = preg_replace("#\*[^\*]*\*#si", ' <span style="color: #C0C0C0;"> \\0 </span> ', $text);
    /*
    * Colors in text
    */
    $text = preg_replace("#\[color=([\#a-f0-9A-F]*?)\](.*?)\[/color\]#si", "<span style=\"color:\\1\">\\2</span>", $text);

    // s - to accept also newline characters
    /**    $text = str_replace("&nbsp;</i>", "[/quote]", $text);

    * Return converted text
    */
    return $text;
}

/**
* Function convert HTML tags <b> etc on BBcode [b] and replace smiles and quotes
*/
function htmltobbcode($text)
{
    /**
    * Bold font
    */
    $text = str_replace("<b>","[b]",$text);
    $text = str_replace("</b>","[/b]",$text);
    /**
    * Italic font
    */
    $text = str_replace("<i>","[i]",$text);
    $text = str_replace("</i>","[/i]",$text);
    /**
    * Underline
    */
    $text = str_replace("<u>","[u]",$text);
    $text = str_replace("</u>","[/u]",$text);
    /**
    * Replace smiles
    */
    $text = str_replace("<img src=\"images/smileys/blue/smile.png\" title=\"uśmiech\" />",":)", $text);
    $text = str_replace("<img src=\"images/smileys/blue/grins.png\" title=\"śmiech\" />",":D", $text);
    $text = str_replace("<img src=\"images/smileys/blue/cheesy.png\" title=\"kontent\" />",":]", $text);
    $text = str_replace("<img src=\"images/smileys/blue/wink.png\" title=\"żart\" />",";)", $text);
    $text = str_replace("<img src=\"images/smileys/blue/razz.png\" title=\"jęzor\" />",":P", $text);
    $text = str_replace("<img src=\"images/smileys/blue/sad.png\" title=\"smutny\" />",":(", $text);
    $text = str_replace("<img src=\"images/smileys/blue/cry.png\" title=\"płacz\" />",";(", $text);
    $text = str_replace("<img src=\"images/smileys/blue/clown.png\" title=\"klaun\" />",":o)", $text);
    $text = str_replace("<img src=\"images/smileys/blue/slash.png\" title=\"yyy?\" />",":/", $text);
    $text = str_replace("<img src=\"images/smileys/blue/slash2.png\" title=\"sceptyczny\" />",":|", $text);
    $text = str_replace("<img src=\"images/smileys/blue/shocked.png\" title=\"zdziwiony\" />",":o", $text);
    $text = str_replace("<img src=\"images/smileys/blue/cool.png\" title=\"cool\" />","8)", $text);
    $text = str_replace("<img src=\"images/smileys/blue/mad.png\" title=\"wściekły\" />",":m", $text);
    $text = str_replace("<img src=\"images/smileys/blue/smartass.png\" title=\"mądrala\" />",";>", $text);
    $text = str_replace("<img src=\"images/smileys/blue/shame.png\" title=\"wstyd\" />",":%", $text);
    $text = str_replace("<img src=\"images/smileys/blue/scoop.png\" title=\"kufel\" />","c[]", $text);
    $text = str_replace("<img src=\"images/smileys/blue/mad2.png\" title=\"zły\" />",":[", $text);
    $text = str_replace("<img src=\"images/smileys/blue/squirrel.png\" title=\"wiewiór\" />",">;p", $text);
    $text = str_replace("<img src=\"images/smileys/blue/cmok.png\" title=\"cmok\" />",":*", $text);
    $text = str_replace("<img src=\"images/smileys/blue/oldschool.png\" title=\"oldschool\" />",":E", $text);
    $text = str_replace("<img src=\"images/smileys/blue/heart2.png\" title=\"serduszko\" />","<3", $text);
    /**
     * Center text
     */
    $text = str_replace("<center>", "[center]", $text);
    $text = str_replace("</center>", "[/center]", $text);
    /**
     * Quote text
     */
    $text = str_replace("<br />Cytat:<br /><i>", "[quote]", $text);
    $text = str_replace("&nbsp;</i>", "[/quote]", $text);
    /*
    * Text Colors
    */
    $text = preg_replace('#\<span style="color:([\#a-f0-9A-F]*?)"\>(.*?)\</span\>#si', "[color=\\1]\\2[/color]", $text);
    /**
    * Delete HTML tags
    */
    $text = strip_tags($text);
    /**
    * Return converted text
    */
    return $text;
}

?>
