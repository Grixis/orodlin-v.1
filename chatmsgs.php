<?php
/**
 *   File functions:
 *   Show text in chat, send messages, color player names, ban/unban players in chat
 *
 *   @name                 : chatmsg.php
 *   @copyright            : (C) 2004,2005,2006 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @author               : eyescream <> 
 *   @author               : Klaus Korner <albitos.snape@gmail.com>
 *   @version              : 1.0
 *   @since                : 03.02.2006
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
// $Id: chatmsgs.php 566 2006-09-13 09:31:08Z thindil $
/**
* Initializing mian classes needed in AJAX response/request
*/
require_once('includes/ajaxinit.php');

/**
* Get the localization for game
*/
require_once('languages/'.$player['lang'].'/chatmsgs.php');
$aRights = array(
    'ban' => array('Admin', 'Staff'),
    'unban' => array('Admin', 'Staff'),
    'bot' => array('Admin', 'Staff'),
    'clear' => array('Admin', 'Staff')
);

switch($_GET['room'])
{
	case 'izba':
		$room = "'izba'";
		break;
	case 'piwnica':
		$room = "'piwnica'";
		break;
	default:
		$room = "'izba'";
		break;
}

/**
* Function to send chat message
*/
function send($sMsg, $sSender, $iSender, $sLang, $iOwner = 0)
{
    global $db, $room;
    require_once('includes/bbcode.php');

    $sSender = $db -> qstr($sSender);
    $sLang = $db -> qstr($sLang, get_magic_quotes_gpc());
    $iSender = (int)$iSender;
    $iOwner = (int)$iOwner;

    $sMsg = $db -> qstr(breakLongWords(censorship(bbcodetohtml($sMsg)), 30, "<br />\n"));
    
    $time = array_sum(explode(' ', microtime()));
    $db -> Execute('INSERT INTO chat(sender, text, senderid, ownerid, lang, time, room) VALUES('.$sSender.','.$sMsg.','.$iSender.','.$iOwner.','.$sLang.','.$time.', '.$room.')') or die($db -> ErrorMsg());
}

/*
* Function to send system message to one player
*/
function systemMsg($msg)
{
    global $player;
    send($msg, '', 0, $player['lang'], $player['id']);
    exit;
}

/*
* Function to check right for command (from config array)
*/
function checkRights($rank, $action)
{
    global $aRights;
    if(!in_array($rank, $aRights[$action]))
    {
        systemMsg(NO_PERM);
    }
}

/*
* Function to ban player
*/
function ban($id, $duration, $reason)
{
    global $db, $player;
    $user = $db -> Execute('SELECT user FROM players WHERE id='.(int)$id);
    $db -> Execute('INSERT INTO chat_config(gracz, resets) VALUES('.(int)$id.', '.(int)$duration.')');
    send(PLAYER.$user->fields['user'].KICKED.REASON.$reason, getColoredName(INNKEEPER3, 'bot'), $player['id'], $player['lang']);
}

function unban($id)
{   
    global $db;
    $db -> Execute('DELETE FROM chat_config WHERE gracz='.(int)$id);
    systemMsg("Gracz ID: $id został odbanowany");
}


/*
* Function to get collored player name
*/
function getColoredName($sName, $sRank)
{
    switch($sRank)
    {
      case 'Admin':
        $starter = '<span style="color: #0066cc;">'.$sName.'</span>';
        break;
      case 'Staff':
      case 'Karczmarka':
      case 'bot':
        $starter = '<span style="color: #108310;">'.$sName.'</span>';
        break;
      default:
        $starter = $sName;
    }
    return $starter;
}

/*
* Function to wrap too long words (like wordwrap)
*/
function breakLongWords($str, $maxLength, $char){
    $wordEndChars = array(" ", "\n", "\r", "\f", "\v", "\0");
    $count = 0;
    $newStr = "";
    $openTag = false;
    for($i=0; $i<strlen($str); $i++){
        $newStr .= $str{$i};   
       
        if($str{$i} == "<"){
            $openTag = true;
            continue;
        }
        if(($openTag) && ($str{$i} == ">")){
            $openTag = false;
            continue;
        }
       
        if(!$openTag){
            if(!in_array($str{$i}, $wordEndChars)){//If not word ending char
                $count++;
                if($count==$maxLength){//if current word max length is reached
                    $newStr .= $char;//insert word break char
                    $count = 0;
                }
            }else{//Else char is word ending, reset word char count
                    $count = 0;
            }
        }
       
    }//End for   
    return $newStr;
}
/*
* Sending messages
*/
if(isset($_POST['msg']))
{
    $starter = getColoredName($player['user'], $player['rank']);
    if($_POST['msg'][0] == '!')
    {
        if(preg_match('#^!ban ([1-90-9]+) ([1-90-9]+) (.*)$#', $_POST['msg'], $x))
        {
            checkRights($player['rank'], 'ban');
            ban($x[1], $x[2], $x[3]);
        }
        elseif(preg_match('#^!unban ([1-90-9]+)$#', $_POST['msg'], $x))
        {
            checkRights($player['rank'], 'unban');
            unban($x[1]);
        }
        elseif(preg_match('#^!bot ([^ ]+) (.*)$#', $_POST['msg'], $x))
        {
            checkRights($player['rank'], 'bot');
            send($x[2], getColoredName($x[1], 'bot'), $player['id'], $player['lang']);
        }
        elseif(preg_match('#^!clear$#', $_POST['msg']))
        {
            checkRights($player['rank'], 'clear');
            $db -> Execute("DELETE FROM chat WHERE lang = '".$player['lang']."' AND room=".$room);
            send(CLEAR_TEXT, getColoredName(INNKEEPER4, 'bot'), $player['id'], $player['lang']);
        }
        elseif(preg_match("#^!w ([1-90-9]+) (.*)$#", $_POST['msg'], $x))
        {
            $user = $db -> getRow('SELECT user FROM players WHERE id='.(int)$x[1]);
            if(!isset($user['user'])) systemMsg(NO_USER);
            send($x[2], $starter, $player['id'], $player['lang'], $x[1]);
        }
        else
        {
            systemMsg(NO_COMMAND);
        }
    }
    else
    {
        $czat = $db -> Execute('SELECT `gracz` FROM `chat_config` WHERE `gracz`='.$player['id']) or die($db -> ErrorMsg()); //check for chat ban
        if ($czat -> fields['gracz'])
        {
            systemMsg (NO_PERM);
        }
        $czat -> Close();

        /**
        * Start innkeeper bot
        */
        require_once('class/bot_class.php');
        $objBot = new Bot($_POST['msg'], 'Karczmarzu');
        $blnOrder = false;
        $message = $_POST['msg'];
        if ($blnCheckbot = $objBot -> Checkbot())
        {
            $strAnswer = $objBot -> Botanswer();
            // player gives something to other player: Karczmarzu X Y Z for 1234
            //Search for numbers at the end of message. If numeric is found, it will be stored in $intReceiverId[0]
            if (preg_match("#\d+#",$_POST['msg'], $intReceiverId))
            {
				$user = $db -> Execute('SELECT `user` FROM `players` WHERE `id`='.$intReceiverId[0]);
                $message = preg_replace("#dla \d+$#", ' '.FOR_A.' '.str_replace("&#39;","'",$user -> fields['user']), $_POST['msg']);
                $user -> Close();
                $blnOrder = true;
            }
        }        
        $owner = 0;

        send($message, $starter, $player['id'], $player['lang']);
        $objInnkeeper = $db -> Execute('SELECT `user` FROM `players` WHERE `rank`=\'Karczmarka\' AND `page`=\'Chat\' AND `lpv`>='.(time() - 60));
        if (!$objInnkeeper -> fields['user'] && isset($strAnswer))
        {
            send($strAnswer, getColoredName(INNKEEPER2, 'bot'), 0, $player['lang']);
        }
        elseif ($blnCheckbot)
        {
            send(INNKEEPER_GONE.$objInnkeeper -> fields['user'].RULES,  getColoredName(INNKEEPER3, 'bot'), 0, $player['lang']);
        }
        $objInnkeeper -> Close();
    }
}
else
{
  $time = array_sum(explode(' ', microtime()));
  $oUserAction = $db -> Execute('SELECT time FROM chat_users WHERE userid='.$player['id'].' AND room='.$room) or die($db -> ErrorMsg());
  
  if(isset($_GET['visited']) && $_GET['visited'] == 1)
  {
    $iPlayerTime = $oUserAction -> fields['time'];
  }
  else
  {
    $iPlayerTime = 0;
  }
  $db -> Execute('INSERT INTO `chat_users`(`userid`, `time`, `room`) VALUES(?,?,?) ON DUPLICATE KEY UPDATE `time`='.$time.', `room`='.$room.'', array($player['id'],$time,$room));
	


/*
* Sending non-read messages
*/

$arrMsg = array();
$messages = $db -> SelectLimit('SELECT * FROM chat WHERE time >= '.$iPlayerTime.' AND (ownerid=0 OR ownerid='.$player['id'].' OR senderid='.$player['id'].') AND (lang=\''.$player['lang'].'\' OR lang=\'\') AND room = '.$room.' ORDER BY chat.id DESC', 20);

$i = 0;
while(!$messages -> EOF)
{
    $arrMsg[$i] = $messages -> fields;
    $arrMsg[$i]['date'] = date('d m Y\r. G:i:s', substr($messages -> fields['time'], 0, 10));
    if($messages -> fields['ownerid'] != 0)
    {
        $username = $db -> GetRow('SELECT user FROM players WHERE id='.$messages -> fields['ownerid']);
        $arrMsg[$i]['owner'] = $username['user'];
        $username = $db -> GetRow('SELECT gender FROM players WHERE id='.$messages -> fields['senderid']);
        $arrMsg[$i]['gender'] = $username['gender'];
    }
    $messages -> MoveNext();
    $i++;
}

$arrUser = array();
$users = $db -> Execute('SELECT cu.userid, p.user, p.rank FROM chat_users cu, players p WHERE cu.room='.$room.' AND cu.time > '.($time - 30).' AND cu.userid = p.id ORDER BY user') or die($db -> ErrorMsg());
while(!$users -> EOF)
{
    $arrUser[] = array(
        'userid' => $users -> fields['userid'],
        'user' => getColoredName($users -> fields['user'], $users -> fields['rank'])
    );
    $users -> MoveNext();
}
$users -> Close();

$num = $db -> Execute('SELECT COUNT(*) AS c FROM chat WHERE room='.$room.' AND (lang=\''.$player['lang'].'\' OR lang=\'\')') or die($db -> ErrorMsg());
$arrReply = array(
	'msg'   => array_reverse($arrMsg),
    'users' => $arrUser,
    'number' => $num -> fields['c']
);
}
require_once('includes/json.php');
$json =  new Services_JSON();
$smarty -> assign('Reply', $json -> encode($arrReply));

$smarty -> display('chatmsgs.tpl');


?>
