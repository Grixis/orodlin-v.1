<?php
/**
 *   File functions:
 *   Main file of game, compress site, get information about player from database and more
 *
 *   @name                 : head.php
 *   @copyright            : (C) 2004,2005,2006,2007 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @author               : eyescream <tduda@users.sourceforge.net>
 *   @author               : Marek 'marq' Chodor <marek.chodor@gmail.com>
 *   @version              : 1.4
 *   @since                : 24.08.2007
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

$start_time = microtime();
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
// obsługa tagów :)
require_once ('tags.php');


/**
*Name of requested file. (Needed in help.php)
*/

$FileName = explode('.', basename ($_SERVER['REQUEST_URI']));
$FileName = $FileName[0];
$smarty->assign ('FileName', $FileName);


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
        $referer = (isset($arrMatch[1])) ? addslashes($arrMatch[1]) : '';
    }
	else $referer = '';

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
* Login to game and set session variables
*/
if (isset ($_POST['pass']) && $title == 'Wieści')
{
    logIn($_POST['email'], md5($_POST['pass']), $_SERVER['REMOTE_ADDR'], strip_tags($title), $pllimit);
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['pass'] = md5($_POST['pass']);
    /**
    * Total visits counter
    */
    include('counter/licznik.php');
    $plik=fopen("counter/licznik.php","w+");
    fputs($plik,'<?php $ilosc='.(++$ilosc).' ?>');
    fclose($plik);
    /**
    * Count today visits
    */
    include("counter/d_licznik.php");
    $plik=fopen("counter/d_licznik.php","w+");
    fputs($plik,'<?php $ilosc='.(++$ilosc).' ?>');
    fclose($plik);

    /**
    * Count active players
    */
    $arrTest = $db -> GetRow('SELECT count(*) FROM `players` WHERE `lpv`>='.(time() - 180));
    $intNumo2 = $arrTest['count(*)'] +1;

    /**
    * Do we have new record?
    */
    $date1 = date('Y-m-d');
    $date2 = date('H:i:s');
    include("counter/record.php");
    if ($record <= $intNumo2)
    {
        $plik=fopen("counter/record.php","w+");
        fputs($plik,'<?php $record="'.$intNumo2.'"; $when1="'.$date1.'"; $when2="'.$date2.'"; ?>');
        fclose($plik);
    }
}

/**
* End session
*/
if (empty($_SESSION['email']) || empty($_SESSION['pass']))
{
    $smarty -> assign ('Error', E_SESSIONS);
    $smarty -> display ('error.tpl');
    exit;
}

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
* Get the localization for game
*/
require_once('languages/'.$player -> lang.'/head1.php');
$expn = round(pow($player -> level,2)*log(exp(1)+($player -> level-1)/100)*50);
$pct = round($player -> exp / $expn * 100);

/**
 * Graph bars and magicians.
 */
if ($player -> graphbar == 'Y')
{
    $intExpperc = min($pct, 100);
    $intPerhealth = min(round($player -> hp / $player -> max_hp * 100), 100);
    if ($player -> clas == 'Mag')
    {
        $cape = $db -> GetRow('SELECT `power` FROM `equipment` WHERE `owner`='.$player -> id.' AND `type`=\'C\' AND `status`=\'E\'');
	if (!isset($cape['power'])) $cape['power'] = 0;
        $maxmana = ($player -> inteli + $player -> wisdom) * (1 + $cape['power'] / 100);
        $intPermana = min(round(($player -> mana / $maxmana) * 100), 100);
        $smarty -> assign(array('Manaper' => $intPermana,
                                'Barsize2' => $intPermana * 0.9,
                                'Vial3' => (100 - $intPermana) * 0.9));
    }
    $smarty -> assign(array('Barsize' => $intPerhealth * 0.9,
                            'Healthper' => $intPerhealth,
                            'Expper' => $intExpperc * 0.9,
                            'Vial' => (100 - $intExpperc) * 0.9,
                            'Vial2' => (100 - $intPerhealth) * 0.9));
}

/**
 * Avatar
 */
$smarty -> assign(array('Avatar' => ''));

$plik = 'avatars/'.$player -> avatar;
if (is_file($plik))
{
    require_once('includes/avatars.php');
    $arrImage = scaleavatar($plik);
    $smarty -> assign(array("Avatar" => $plik,
							"A_width" => $arrImage[0],
							"A_height" => $arrImage[1]));
}

/**
 * Style rotation depending on location.
 */
if ($player -> style == 'orodlin.css')
{
    switch ($player -> location)
    {
        case 'Altara': $player -> style = 'default/orodlin.m.css';
            break;
        case 'Ardulith': $player -> style = 'default/orodlin.a.css';
            break;
        case 'Góry': $player -> style = 'default/orodlin.g.css';
            break;
        case 'Las': $player -> style = 'default/orodlin.l.css';
            break;
    }
}

if ($player -> graphic == 'default')
{
    switch ($player -> location)
    {
        default: $LocStyle = 'default.css';
            break;
        case 'Altara': $LocStyle = 'meredith.css';
            break;
        case 'Ardulith': $LocStyle = 'agarakar.css';
            break;
        case 'Góry': $LocStyle = 'gory.css';
            break;
        case 'Las': $LocStyle = 'las.css';
            break;
        case 'Lochy': $LocStyle = 'jail.css';
            break;
    }
    $smarty -> assign('LocStyle', $LocStyle);
}

$smarty -> assign(array('Time' => $time,
                        'Title' => $title1,
                        'Name' =>  getTaggedPlayerName ($player -> user, $player -> tribe, $player -> tribe_rank),
                        'Id' => $player -> id,
                        'Level' => $player -> level,
                        'Exp' => $player -> exp,
                        'Expneed' => $expn,
                        'Percent' => $pct,
                        'Health' => $player -> hp,
                        'Maxhealth' => $player -> max_hp,
                        'Mana' => $player -> mana,
                        'Showmana' => $player -> clas == 'Mag' ? 'Y' : 'N',
                        'Energy' => $player -> energy,
                        'Maxenergy' => $player -> max_energy,
                        'Gold' => $player -> credits,
                        'Bank' => $player -> bank,
                        'Style' => $player -> style,
                        'Graphbar' => $player -> graphbar,
                        'Graphic' => $player -> graphic,
                        'Graphstyle' => $player -> graphstyle,
                        'Hospital' => '',
                        'Battle' => '',
                        'Tribe' => '',
                        'Lbank' => '',
                        'Special' => '',
                        'Tforum' => '',
                        'Location' => '',
                        'Spells' => $player -> clas == 'Mag' ? '<li><a href="czary.php">'.SPELLS_BOOK.'</a></li>' : '',
                        'Stephead' => ''));

if (strpos($_SERVER['PHP_SELF'], 'newspaper.php') && isset($_GET['step']))
    $smarty -> assign('Stephead', $_GET['step']);
else
{
    $arrLinks = $db -> GetAll('SELECT `file`, `text` FROM `links` WHERE `owner`='.$player -> id.' ORDER BY `id` ASC');
    $smarty -> assign_by_ref('ArrLinks', $arrLinks);
}

switch($player -> location)
{
    case 'Altara':
    case 'Ardulith':
$smarty -> assign('QLocation', CITY);
        if ($player -> hp > 0)
        {
            $arrHospass = $db -> GetRow('SELECT `hospass` FROM `tribes` WHERE `id`='.$player -> tribe.' AND `hospass`=\'Y\'');
            $healneed = max(($player -> max_hp - $player -> hp) * 3, 0);
            if (!empty($arrHospass))
                $healneed = ceil($healneed / 2);
        }
        else
            $healneed = 75 * $player -> level;
        $smarty -> assign(array('Location' => '<li><a href="city.php">'.CITY.'</a></li>',
                                'Battle' => '<li><a href="battle.php">'.B_ARENA.'</a></li>',
                                'Hospital' => '<li><a href="hospital.php">'.HOSPITAL.'</a> ['.$healneed.' sz]</li>',
                                'Lbank' => '<li><a href="bank.php">'.BANK.'</a></li>'));
        if ($player -> tribe)
		{
			$smarty -> assign('Tribe','<li><a href="tribes.php?view=my">'.MY_TRIBE.'</a></li>');
			$smarty -> assign('Tforum','<li><a href="tforums.php?view=topics">'.T_FORUM.'</a></li>');
		}
        break;
    case 'Podróż':
        $smarty -> assign('QLocation', RETURN_TO);
        $test = $db -> GetRow('SELECT `quest` FROM `questaction` WHERE `player`='.$player -> id.' AND `action`!=\'end\'');
        if ($test['quest'] != 0)
        {
            $qlocation = $db -> GetRow('SELECT `location` FROM `quests` WHERE `qid`='.$test['quest']);
            $smarty -> assign('Location', '<li><a href="'.$qlocation['location'].'?step=quest">'.RETURN_TO.'</a></li>');
        }
            else
        {
            if (!isset($_GET['step']))
                $_GET['step'] = 'caravan';
            $smarty -> assign('Location', '<li><a href="travel.php?akcja='.$_GET['akcja'].'&amp;step='.$_GET['step'].'">'.RETURN_TO2.'</a></li>');
        }
        break;
    case 'Góry':
        $smarty -> assign(array('QLocation' => MOUNTAINS,
                                'Location' => '<li><a href="'.($player -> fight == 0 ? 'gory.php' : 'explore.php?akcja=gory').'">'.MOUNTAINS.'</a></li>'));
        if ($player -> tribe)
		{
			$smarty -> assign('Tforum','<li><a href="tforums.php?view=topics">'.T_FORUM.'</a></li>');
		}
        break;
    case 'Las':
        $smarty -> assign(array('QLocation' => FOREST,
                                'Location' => '<li><a href="'.($player -> fight == 0 ? 'las.php' : 'explore.php?akcja=las').'">'.FOREST.'</a></li>'));
        if ($player -> tribe)
		{
			$smarty -> assign('Tforum','<li><a href="tforums.php?view=topics">'.T_FORUM.'</a></li>');
		}
        break;
    case 'Lochy':
        $smarty -> assign(array('QLocation' => JAIL,
                                'Location' => '<li><a href="jail.php">'.JAIL.'</a></li>'));
        if ($player -> tribe)
		{
			$smarty -> assign('Tforum','<li><a href="tforums.php?view=topics">'.T_FORUM.'</a></li>');
		}
        break;
    case 'Portal':
        $smarty -> assign(array('QLocation' => PORTAL,
                                'Location' => '<li><a href="portal.php">'.PORTAL.'</a></li>'));
        break;
    case 'Astralny plan':
        $smarty -> assign(array('QLocation' => ASTRAL_PLAN,
                                'Location' => '<li><a href="portals.php?step='.$player -> fight.'">'.ASTRAL_PLAN.'</a></li>'));
        break;
}

$arrQuery = $db -> GetRow('SELECT count(*) FROM `chat_users` WHERE `room`=\'izba\' AND `time`>='.(time() - 30));
$smarty -> assign ('PlayersI', $arrQuery['count(*)']);
$arrQuery = $db -> GetRow('SELECT count(*) FROM `chat_users` WHERE `room`=\'piwnica\' AND `time`>='.(time() - 30));
$smarty -> assign ('PlayersP', $arrQuery['count(*)']);

if ($player -> rank == 'Admin')
    $smarty -> assign ('Special', '<ul><li><a href="admin.php">'.KING.'</a></li></ul>');
if ($player -> rank == 'Staff')
    $smarty -> assign ('Special', '<ul><li><a href="staff.php">'.PRINCE.'</a></li></ul>');
if ($player -> rank == 'Sędzia')
    $smarty -> assign ('Special', '<ul><li><a href="sedzia.php">'.JUDGE.'</a></li></ul>');
if ($player -> rank == 'Techniczny')
    $smarty -> assign ('Special', '<ul><li><a href="tech.php">'.TECH_PANEL.'</a></li></ul>');


$smarty -> display ('header.tpl');

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
    require_once('includes/foot.php');
    exit;
}

/**
 * Function check for integer overflow in forms
 */
function integercheck($strField)
{
    if (strlen($strField) > 11)
        error(ERROR);
}

/**
* Delete session when player escape from fight
*/
$arrTitle = array('Arena Walk', 'Labirynt', 'Portal', 'Astralny plan', 'Opuszczony szyb', 'Poszukiwania');
$arrLocations = array('Altara', 'Ardulith', 'Portal', 'Astralny plan', 'Góry', 'Las');
if ($player -> fight != 0 && (!in_array($title, $arrTitle)) && (in_array($player -> location, $arrLocations)))
{
    $db -> Execute('UPDATE `players` SET `hp`=0, `fight`=0, `bless`=\'\', `blessval`=0 WHERE `id`='.$player -> id) or die($db -> ErrorMsg());
    if (!isset($_SESSION['amount']))
        $_SESSION['amount'] = 1;
    for ($k = 0; $k < $_SESSION['amount']; $k++)
        unset($_SESSION['mon'.$k]['id'],$_SESSION['mon'.$k]['user'],$_SESSION['mon'.$k]['hp']);
    unset($_SESSION['exhaust'], $_SESSION['round'], $_SESSION['points'], $_SESSION['amount'], $_SESSION['gainmiss'], $_SESSION['gainattack'], $_SESSION['gainshoot'], $_SESSION['gainmagic']);

	error(ESCAPE);
}

/**
* Delete sessions variables when player exit forums
*/
if (isset($_SESSION['forums']) && !ereg('forums.php', $_SERVER['PHP_SELF']))
    unset($_SESSION['forums']);
if (isset($_SESSION['tforums']) && !ereg('tforums.php', $_SERVER['PHP_SELF']))
    unset($_SESSION['tforums']);

/**
* Display some informations when player race, class, gender is not set
*/
if (($FileName != 'card' && $FileName != 'updates' && $FileName != 'stats' && $FileName != 'view' && $FileName != 'bugtrack' && $FileName != 'admin') && ($player -> race == '' || $player -> clas == '' || $player -> gender == '') && $player -> rank != 'Admin')
    error(UNFINISHED);

//searching a bug logs
if (1) {
	$logFile = fopen('ourlogs.txt','a');
	$logString = '';
	foreach ($_GET as $foo) {
		if (preg_match("#update#i",$foo)) $logString =  'ERROR ';
	}
	foreach ($_POST as $foo) {
		if (preg_match("#update#i",$foo)) $logString =  'ERROR ';
	}
	if (isset($_POST['pass'])) $_POST['pass'] = 'XXX';
	if (isset($_POST['profile'])) {
		$tmpProfile = $_POST['profile'];
		$_POST['profile'] = 'PROFIL';
	}
	$logString .= Date ("y/m/d H:i:s", time()) . ' id:'. $player -> id . ' :: ' . ($_SERVER['REQUEST_URI']) . ' ' . str_replace('\n',' ' ,print_r($_POST, 1));
	fwrite($logFile, $logString);
	fclose($logFile);
	if (isset($tmpProfile)) {
		$_POST['profile'] = $tmpProfile;
		unset($_POST['profile']);
	}
}
?>
