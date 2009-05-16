<?php
/**
 *   File functions:
 *   Minimalistic version of /includes/head.php. Designed to use with AJAX-powered pages.
 *
 *   @name                 : minihead.php
 *   @copyright            : (C) 2007 Orodlin Team based on Vallheru 1.3
 *   @author               : eyescream <tduda@users.sourceforge.net>
 *   @version              : 0.1
 *   @since                : 28.07.2007
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
// $Id$
/**
* GZIP compression
*/

date_default_timezone_set ("Europe/Warsaw");
$do_gzip_compress = FALSE;
$compress = FALSE;
$phpver = phpversion();
$useragent = (isset($_SERVER["HTTP_USER_AGENT"]) ) ? $_SERVER["HTTP_USER_AGENT"] : $HTTP_USER_AGENT;
if ($phpver >= '4.0.4pl1' && (strstr($useragent,'compatible') || strstr($useragent,'Gecko') || strstr ($useragent, 'Opera')))
{
    if (extension_loaded('zlib'))
    {
        $compress = TRUE;
        ob_start('ob_gzhandler');
    }
}
    elseif ($phpver > '4.0')
{
    if (strstr($HTTP_SERVER_VARS['HTTP_ACCEPT_ENCODING'], 'gzip'))
        if (extension_loaded('zlib'))
        {
            $do_gzip_compress = $compress = TRUE;
            ob_start();
            ob_implicit_flush(0);
            header('Content-Encoding: gzip');
        }
}


/**
* Check available languages
*/
/**
* Check available languages
*/
$path = 'languages/';
$dir = opendir($path);
$arrLanguage = array();
$i = 0;
while ($file = readdir($dir))
    if (!ereg("(.htm*)|(\.)$", $file))
        $arrLanguage[$i++] = $file;
closedir($dir);

/**
* Get the localization for game
*/
$strTranslation = in_array($_SERVER['HTTP_ACCEPT_LANGUAGE'], $arrLanguage) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : 'pl';
require_once('languages/'.$strTranslation.'/head.php');
require_once('includes/sessions.php');
require_once 'libs/Smarty.class.php';
require_once ('includes/config.php');
require_once('class/player_class.php');

$smarty = new Smarty;
$smarty-> compile_check = true;
$db -> LogSQL();
$smarty -> template_dir = './templates/';
$smarty -> compile_dir = './templates_c/';
$smarty -> assign(array('Gamename' => $gamename, 'Meta' => '', 'GameAddress' => $gameadress));
/**
* Errors reporting level
*/
error_reporting(E_ALL);

/**
* function to catch errors and write it to bugtrack
*/
function catcherror($errortype, $errorinfo, $errorfile, $errorline)
{
    global $db, $smarty;
    $file = explode("/", $errorfile);
    preg_match('/([^\/]+)$/', $errorfile, $arrMatch);
    $file = addslashes($arrMatch[1]);
    $info = addslashes($errorinfo);
    if (isset($_SERVER['HTTP_REFERER']))
    {
        preg_match('/([^\/]+)$/', $_SERVER['HTTP_REFERER'], $arrMatch);
        $referer = addslashes($arrMatch[1]);
    }
    else
        $referer = '';
    $oldFetchMode = $db -> SetFetchMode(ADODB_FETCH_NUM);
    $arrTest = $db -> GetRow('SELECT `id` FROM `bugtrack` WHERE `file`=\''.$file.'\' AND `line`='.$errorline.' AND `info`=\''.$info.'\' AND `type`='.$errortype.' AND `referer`=\''.$referer.'\'');
    if (!empty($arrTest))
        $db -> Execute('UPDATE `bugtrack` SET `amount`=`amount`+1 WHERE `id`='.$arrTest[0]);
    else
        $db -> Execute('INSERT INTO `bugtrack` (`type`, `info`, `file`, `line`, `referer`) VALUES('.$errortype.', \''.$info.'\', \''.$file.'\', '.$errorline.', \''.$referer.'\')');
    $db -> SetFetchMode($oldFetchMode);
    if ($errortype == E_USER_ERROR || $errortype == E_ERROR)
    {
        $smarty -> assign('Message', E_ERRORS);
        $smarty -> display('error1.tpl');
        exit;
    }
}


/**
* set catching errors
*/
set_error_handler('catcherror');

$smarty -> assign('Charset', CHARSET);

/**
* End session
*/
if (empty($_SESSION['email']) || empty($_SESSION['pass']))
{
    $smarty -> assign ('Error', E_SESSIONS);
    $smarty -> display ('error.tpl');
    exit;
}
$time = date("H:i:s");
$data = date("y-m-d");
$newdate = $data.' '.$time;

$time = date('H:i:s');
$data = date('y-m-d');
$newdate = $data.' '.$time;

$player = new Player($_SESSION['email'], strip_tags($title));

$arrURI = explode('.',basename($_SERVER['REQUEST_URI']));
if (in_array($arrURI[0], array('alchemik', 'grid', 'jeweller', 'kowal', 'lumbermill')))
    $player -> artisandata();
if (in_array($arrURI[0], array('amarket', 'armor', 'bows', 'czary', 'equip', 'jeweller', 'klasa', 'kowal', 'lumbermill', 'portal', 'rasa', 'tribes', 'wieza', 'view')))
    $player -> thiefdata();
if ($arrURI[0] == 'deity' || $arrURI[0]== 'temple')
    $player -> templedata();
if (in_array($arrURI[0], array('admin', 'city', 'court', 'forums', 'library', 'news', 'newspaper', 'polls', 'updates', 'staff')))
    $player -> languagedata();
if (in_array($arrURI[0], array('bank', 'core', 'explore', 'farm', 'house', 'kopalnia', 'lumberjack','market', 'maze', 'mines', 'outposts', 'pmarket', 'travel', 'warehouse', 'zloto')))
    $player -> raredata();
if ($arrURI[0] == 'ap' || $arrURI[0] == 'stats')
    $player -> statistics();
if ($arrURI[0] == 'battle' || $arrURI[0] == 'train')
    $player -> battledata();
if ($arrURI[0] == 'account')
    $player -> accountdata();

// Check if game isn't closed for "reset" reasons - only near every odd hour (0,2,4,6,8 etc.).
$intTime = time() % 7200;
if ($intTime < 60 || $intTime > 7140)
{
    $arrOpen = $db -> GetRow('SELECT `value` FROM `settings` WHERE `setting`=\'open\'');
    if ($arrOpen['value'] == 'N' && $player -> rank != 'Admin')
    {
        $arrReason = $db -> GetRow('SELECT `value` FROM `settings` WHERE `setting`=\'close_reason\'');
        $smarty -> assign ('Error', REASON.'<br />'.$arrReason['value']);
        $smarty -> display ('error.tpl');
        exit;
    }
}

/**
 * GetLocHref() - Returns pure uri for current location.
*/
function GetLocHref()
{
    global $player;
    switch ($player->location)
    {
        case 'Altara':
        case 'Ardulith':
            return 'city.php';
        case 'Góry':
            return 'gory.php';
        case 'Las':
            return 'las.php';
        case 'Lochy':
            return 'jail.php';
        case 'Portal':
            return 'portal.php';
        case 'Podróż':
            return 'city.php';
        case 'Portal':
            return 'portals.php';
    }
}

/**
* Function which exit from script when error is reported
*/
function error($text, $ref = '', $href = '')
{
    global $smarty;
    global $db;
    global $start_time;
    global $player;
    global $numquery;
    global $compress;
    global $sqltime;
    global $phptime;
    global $gamename;

    if (strpos($text, '<a href') === false)
    {
        if ($ref == '' || $ref == RET_SELF)
            $text .= ' (<a href="'.$_SERVER['PHP_SELF'].'">'.BACK.'</a>)';
        elseif ($ref == RET_LOC)
            $text .= ' (<a href="'.GetLocHref().'">'.BACK.'</a>)';
            else //$ref == RET_ARG
            $text .= ' (<a href="'.$href.'">'.BACK.'</a>)';
    }
    $smarty -> assign('Message', $text);
    $smarty -> display ('error1.tpl');
    require_once('includes/minifoot.php');
    exit;
}
?>
