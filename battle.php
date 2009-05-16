<?php
/**
 *   File functions:
 *   Battle Arena - figth between players and player vs monsters
 *
 *   @name                 : battle.php
 *   @copyright            : (C) 2004,2005,2006 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @author               : Marek 'marq' Chodor <marek.chodor@gmail.com>
 *   @author               : eyescream <tduda@users.sourceforge.net>
 *   @version              : 1.4a
 *   @since                : 26.08.2007
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
// $Id: battle.php 727 2006-10-16 15:48:33Z thindil $

$title = "Arena Walk";
require_once("includes/head.php");
require_once("includes/funkcje.php");
require_once("class/fight_class.php");
require_once("class/monster_class.php");
require_once('includes/security.php');

/**
* Get the localization for game
*/
require_once("languages/".$player -> lang."/battle.php");
require_once("languages/".$player -> lang."/funkcje.php");

global $runda;
global $number;
global $newdate;
global $smarty;
global $db;

if (!isset($_GET['action']) && !isset($_GET['battle']))
{
    $smarty -> assign(array("Battleinfo" => BATTLE_INFO,
                            "Ashowalive" => A_SHOW_ALIVE,
                            "Ashowlevel" => A_SHOW_LEVEL,
                            "Ashowmonster" => A_SHOW_MONSTER));
}

/**
* Show players on this same level which have a player
*/
if (isset($_GET['action']) && $_GET['action'] == 'showalive')
{
    $elist = $db -> SelectLimit("SELECT id, user, rank, tribe FROM players WHERE level=".$player -> level." AND hp>0 AND miejsce='".$player -> location."' AND id!=".$player -> id." AND immu='N' AND rasa!='' AND klasa!='' AND rest='N' AND freeze=0 AND age>2", 50);
    $arrid = array();
    $arrname = array();
    $arrrank = array();
    $arrtribe = array();
    $i = 0;
    while (!$elist -> EOF)
    {
        if ($elist -> fields['rank'] == 'Admin')
        {
            $arrrank[$i] = R_ADMIN;
        }
            elseif ($elist -> fields['rank'] == 'Staff')
        {
            $arrrank[$i] = R_STAFF;
        }
            elseif ($elist -> fields['rank'] == 'Member')
        {
            $arrrank[$i] = R_MEMBER;
        }
            else
        {
            $arrrank[$i] = $elist -> fields['rank'];
        }
        $arrid[$i] = $elist -> fields['id'];
        $arrname[$i] = $elist -> fields['user'];
        $arrtribe[$i] = $elist -> fields['tribe'];
        $elist -> MoveNext();
        $i = $i + 1;
    }
    $elist -> Close();
    $smarty -> assign ( array("Level" => $player -> level,
                              "Enemyid" => $arrid,
                              "Enemyname" => $arrname,
                              "Enemytribe" => $arrtribe,
                              "Enemyrank" => $arrrank,
                              "Lid" => L_ID,
                              "Showinfo" => SHOW_INFO,
                              "Lname" => L_NAME,
                              "Lrank" => L_RANK,
                              "Lclan" => L_CLAN,
                              "Loption" => L_OPTION,
                              "Aattack" => A_ATTACK,
                              "Orback" => OR_BACK,
                              "Bback" => B_BACK));
}

if (isset ($_GET['action']) && $_GET['action'] == 'levellist')
{
    $smarty -> assign(array(
                            "Showall" => SHOW_ALL,
                            "Tolevel" => TO_LEVEL,
                            "Ago" => A_GO));
    if (isset($_GET['step']) && $_GET['step'] == 'go')
    {
        if (!isset($_POST['slevel']))
        {
            error(S_LEVEL);
        }
        if (!isset($_POST['elevel']))
        {
            error(E_LEVEL);
        }
        if (!strictInt($_POST['slevel']) || !strictInt($_POST['elevel']))
        {
            error(ERROR);
        }
        $elist = $db -> SelectLimit("SELECT id, user, rank, tribe FROM players WHERE level>=".$_POST['slevel']." AND level<=".$_POST['elevel']." AND hp>0 AND miejsce='".$player -> location."' AND id!=".$player -> id." AND immu='N' AND rasa!='' AND klasa!='' AND rest='N' AND freeze=0 AND age>2", 50);
        $arrid = array();
        $arrname = array();
        $arrrank = array();
        $arrtribe = array();
        $i = 0;
        while (!$elist -> EOF)
        {
            if ($elist -> fields['rank'] == 'Admin')
            {
                $arrrank[$i] = R_ADMIN;
            }
                elseif ($elist -> fields['rank'] == 'Staff')
            {
                $arrrank[$i] = R_STAFF;
            }
                elseif ($elist -> fields['rank'] == 'Member')
            {
                $arrrank[$i] = R_MEMBER;
            }
                else
            {
                $arrrank[$i] = $elist -> fields['rank'];
            }
            $arrid[$i] = $elist -> fields['id'];
            $arrname[$i] = $elist -> fields['user'];
            $arrtribe[$i] = $elist -> fields['tribe'];
            $elist -> MoveNext();
            $i = $i + 1;
        }
        $elist -> Close();
        $smarty -> assign (array("Enemyid" => $arrid,
                                 "Enemyname" => $arrname,
                                 "Enemytribe" => $arrtribe,
                                 "Enemyrank" => $arrrank,
                                 "Lid" => L_ID,
                                 "Lname" => L_NAME,
                                 "Lrank" => L_RANK,
                                 "Lclan" => L_CLAN,
                                 "Loption" => L_OPTION,
                                 "Aattack" => A_ATTACK));
    }
}

/**
* Start battle
*/

if (isset($_GET['battle']))
{
// Protection from attacks immediately after reset.
// Will work only with resets at full hour, need to be adapted otherwise.
    $arrResets = array( 0,8,10,12,14,16,18,20,22,24);
    $intTimestamp = time();  // current time (Unix timestamp)
    $year = date("Y");
    $month = date("m");
    $day = date("d");
    $hour = date("H");
    $blnTest = true;
    for($i = 0; $i < 9; $i++) // count to "number of resets" -1 !
        if( $arrResets[$i] <= $hour && $hour < $arrResets[$i+1]) // find between which resets we are
        {// reset gap was found
            $start = mktime ($arrResets[$i],0,0,$month,$day,$year);
            $stop = mktime ($arrResets[$i],5,0,$month,$day,$year);
            if( $intTimestamp >= $start && $intTimestamp < $stop)
                error(TOO_SOON.' '.$arrResets[$i].':'.'05.');
            break;
        }

    global $runda;
    global $number;
    global $newdate;
    global $smarty;
    global $db;
    
    
    if (!strictInt($_GET['battle']))
    {
        error(ERROR);
    }


    $attacker = new Fighter($player -> id);
    $defender = new Fighter($_GET['battle']);


    if (!$defender -> id)
    {
        error(NO_PLAYER);
    }
    if ($defender -> id == $attacker -> id)
    {
        error(SELF_ATTACK);
    }
    if ($defender -> hp <= 0)
    {
        error($defender -> user." ".IS_DEAD);
    }
    if ($attacker -> hp <= 0)
    {
        error(YOU_DEAD);
    }
    if ($player -> energy < 1)
    {
        error(NO_ENERGY);
    }
    if ($defender -> tribe == $attacker -> tribe && $defender -> tribe > 0)
    {
        error(YOUR_CLAN);
    }
    if ($attacker -> age < 3)
    {
        error(TOO_YOUNG);
    }
    if ($defender -> age < 3)
    {
        error(TOO_YOUNG2);
    }
    if ($attacker -> clas == '')
    {
        error(NO_CLASS);
    }
    if ($defender -> clas == '')
    {
        error(NO_CLASS2);
    }
    if ($attacker -> noarrows)
    {
        error(NO_ARROWS);
    }
    if (($attacker -> clas == 'Wojownik' || $attacker -> clas == 'Rzemieślnik' || $attacker -> clas == 'Barbarzyńca' || $attacker -> clas == 'Złodziej') && $attacker -> usespell)
    {
        error(BAD_CLASS);
    }

    $span =  ($attacker -> level - $defender -> level);
    if ($span > 0)
    {
        error(TOO_LOW);
    }
    if ($attacker -> immunited == 'Y')
    {
        error(IMMUNITED);
    }
    if ($defender -> immunited == 'Y')
    {
        error(IMMUNITED2);
    }
    if ($attacker -> clas == 'Mag' && $player -> mana == 0 && $attacker -> usespell)
    {
        error(NO_MANA);
    }
    if ($player -> location != $defender -> location)
    {
        error(BAD_LOCATION);
    }
    if ($defender -> rest == 'Y')
    {
        error(PLAYER_R);
    }
    if ($defender -> fight != 0)
    {
        error(PLAYER_F);
    }

    $smarty -> assign (array("Enemyname" => $defender -> user,
                             "Versus" => VERSUS,
                             "Action" => ''));
    $db -> Execute("UPDATE `players` SET `energy`=`energy`-1 WHERE `id`=".$player -> id);
    $smarty -> display ('battle.tpl');
    require_once('includes/battle.php');

    pvpfight($attacker,$defender);
    require_once("includes/foot.php");
    exit;
}


/**
* Figth with monsters
*/
if (isset ($_GET['action']) && $_GET['action'] == 'monster')
{
    if ($player -> location == 'Lochy')
    {
        error(ERROR);
    }
    if (!isset($_GET['fight']) && !isset($_GET['fight1']))
    {
        $monster = $db -> Execute("SELECT `id`, `name`, `level`, `hp` FROM `monsters` WHERE `location`='".$player -> location."' ORDER BY `level` ASC");
        $arrid = array();
        $arrname = array();
        $arrlevel = array();
        $arrhp = array();
        $i = 0;
        while (!$monster -> EOF)
        {
            $arrid[$i] = $monster -> fields['id'];
            $arrname[$i] = $monster -> fields['name'];
            $arrlevel[$i] = $monster -> fields['level'];
            $arrhp[$i] = $monster -> fields['hp'];
            $monster -> MoveNext();
            $i = $i + 1;
        }
        $monster -> Close();
        $smarty -> assign (array("Enemyid" => $arrid,
                                 "Enemyname" => $arrname,
                                 "Enemylevel" => $arrlevel,
                                 "Enemyhp" => $arrhp,
                                 "Monsterinfo" => MONSTER_INFO,
                                 "Mname" => M_NAME,
                                 "Mlevel" => M_LEVEL,
                                 "Mhealth" => M_HEALTH,
                                 "Mfast" => M_FAST,
                                 "Mturn" => M_TURN,
                                 "Abattle" => A_BATTLE,
                                 "Orback2" => OR_BACK2,
                                 "Bback2" => B_BACK2));
    }

        //
    // Monsters info
    //
    if (isset ($_GET['view']))
    {
        if (!strictInt($_GET['view']))
            {
                error(ERROR);
            }
            $monsters = $db -> Execute("SELECT `id`, `name`, `level`, `hp`, `description`, `avatar` FROM `monsters` WHERE `id`=".$_GET['view']);
            if (!$monsters -> fields['id'])
            {
                error(NO_CLAN);
            }
            $plik = 'images/beasts/'.$monsters -> fields['avatar'];
            if (is_file($plik))
            {
                $arrImageparams = getimagesize($plik);
                if ($arrImageparams[0] > 200)
                {
                    $arrImageparams[0] = 200;
                }
                if ($arrImageparams[1] > 150)
                {
                    $arrImageparams[1] = 150;
                }
                $smarty -> assign ('Avatar', '<center><img src="'.$plik.'" width="'.$arrImageparams[0].'" height="'.$arrImageparams[1].'" /></center><br />');
            }
            else
            {
                $smarty -> assign ('Avatar', '<center><img src="images\beasts\default.jpg" width="'.$arrImageparams[0].'" height="'.$arrImageparams[1].'" /></center><br />');
            }
            $smarty -> assign(array('Id' => $monsters -> fields['id'],
            	                    'Name' => $monsters -> fields['name'],
                                    'Level' => $monsters -> fields['level'],
                                    'Health' => $monsters -> fields['hp'],
                                    'Description' => $monsters -> fields['description'],
                                    'Tdescription' => T_DESCRIPTION,
                                    'Thp' => T_HP,
                                    'Tlevel' => T_LEVEL,
                                    'Msz_walka' => M_SZ_WALKA,
                                    'Mt_walka' => M_T_WALKA,
                                    'Aback' => A_BACK,));
    }

    if (isset($_GET['dalej']) || isset($_GET['next']))
    {
        $smarty -> assign(array("Abattle2" => A_BATTLE2,
                                "Witha" => WITH_A,
                                "Nend" => N_END));
    }
    if (isset($_GET['dalej']))
    {

        if (!strictInt($_GET['dalej']))
        {
            error(ERROR);
        }
        $en = $db -> Execute("SELECT id, name, location FROM monsters WHERE id=".$_GET['dalej']);
        if ($en -> fields['location'] != $player -> location)
        {
            error(ERROR);
        }
        $smarty -> assign ( array("Id" => $en -> fields['id'],
                                  "Name" => $en -> fields['name'],
                                  "Mtimes" => M_TIMES));
        $en -> Close();
    }
    if (isset ($_GET['next']))
    {
        if (!strictInt($_GET['next']))
        {
            error(ERROR);
        }
        $en = $db -> Execute("SELECT id, name, location FROM monsters WHERE id=".$_GET['next']);
        if ($en -> fields['location'] != $player -> location)
        {
            error(ERROR);
        }
        $smarty -> assign ( array("Id" => $en -> fields['id'],
                                  "Name" => $en -> fields['name']));
    }
    //
    //Turn fight with monsters
    //
    if (isset($_GET['fight1']))
    {
        global $arrehp;
        global $newdate;
        require_once("includes/turnfight.php");
        if (isset ($_POST['write']) && $_POST['write'] == 'Y') {
		$_POST['razy'] = (int)$_POST['razy'];
		$_SESSION['amount'] = $_POST['razy'];
		if (!isset($_POST['razy']) || $_POST['razy'] == '') {
			error(ERROR);
			}
		if (!strictInt($_GET['fight1']) || !strictInt($_POST['razy'])) {
			error(NO_ID);
			}
		if ($player -> hp <= 0) {
			error(NO_HP);
			}
		if ($_POST['razy'] > 20) {
			error(TOO_MUCH_MONSTERS);
			}
		if ($player -> energy < $_POST['razy'] && !isset($_POST['action'])) {
			error(NO_ENERGY2);
			}
		$attacker = new Fighter($player->id);
		$monster = new Monster((int)$_GET['fight1'],1,0);
		if (!$monster -> id) {
			error(NO_MONSTER);
			}
		if ($player -> clas == '') {
			error(NO_CLASS3);
			}
		$db -> Execute('UPDATE `players` SET `fight`='.$monster -> id.', `energy` = `energy` - '.$_POST['razy'].' WHERE `id`='.$player -> id);
		$_POST['write'] = 'N';

//prepare session variables for monsters and player
		for ($k = 0; $k < $_SESSION['amount']; $k++) {
			//each monster identifier
//			$strIndex = 'mon'.$k;
			$_SESSION['mon'.$k]['id'] = $monster -> id;
			//each monster hit points
// 			$strIndex = 'monhp'.$k;
			$_SESSION['mon'.$k]['hp'] = $monster -> hp;
			//each monster action points
// 			$strIndex = 'monap'.$k;
			if ($attacker -> speed > $monster -> attackspeed) {
				$_SESSION['mon'.$k]['ap'] = 1;
				}
			else {
				$_SESSION['mon'.$k]['ap'] = floor($monster -> attackspeed / $attacker -> speed);
				if ($_SESSION['mon'.$k]['ap'] > 5) {
					$_SESSION['mon'.$k]['ap'] = 5;
					}
				}
			$tmpActionArr[$k][0] = $monster -> speed;
			$tmpActionArr[$k][1] = $k;
			}
		$tmpActionArr[$k][0] = $attacker -> speed;
		$tmpActionArr[$k][1] = -1;

		/**
		* function to compare elements of actionArr
		*/
		function aacmp($a,$b) {
			if ($a[0] == $b[0]) return 0;
			return ($a[0] > $b[0]) ? -1 : 1;
			}

		usort($tmpActionArr,"aacmp");
		for ($k = 0; $k <= $_SESSION['amount']; $k++) {
			$actionArr[$k] = $tmpActionArr[$k][1];
			}
		$_SESSION['actionArr'] = $actionArr;
		$_SESSION['exhaust']=0;
		if ($attacker -> speed > $monster -> speed) {
			$_SESSION['points'] = floor($attacker -> speed / $monster -> speed);
			if ($_SESSION['points'] > 5) {
				$_SESSION['points'] = 5;
				}
			}
		else {
			$_SESSION['points'] = 1;
			}
		$_SESSION['round']=0;
		}
	if (isset($monster -> id)) $oppid = $monster -> id;
	else $oppid = (int)$_GET['fight1'];

	turnfight("battle.php?action=monster&fight1=".$oppid);
	if (isset($_SESSION['result'])) unset($_SESSION['result']);
	require_once("includes/foot.php");
	exit;

    }

    //
    //Fast fight with monsters
    //
    if (isset($_GET['fight']))
    {
        global $newdate;
	global $db;

	require_once('includes/battle.php');

        if (!strictInt($_GET['fight']))
        {
            error(ERROR);
        }
        if (!isset($_POST['razy']))
        {
            $_POST['razy'] = 1;
        }
        if (!strictInt($_POST['razy']))
        {
            error(ERROR);
        }
        if (!isset($_POST['times']))
        {
            error(ERROR);
        }
        if (!strictInt($_POST['times']))
        {
            error(ERROR);
        }
        if (isset($_SESSION['amount']))
        {
            error(ERROR);
        }
        if ($player -> hp <= 0)
        {
            error(NO_HP);
        }
        if ($_POST['razy'] > 20)
        {
            error(TOO_MUCH_MONSTERS);
        }
        $lostenergy = $_POST['razy'] * $_POST['times'];
        if ($player -> energy < $lostenergy)
        {
            error(NO_ENERGY2);
        }

	$attacker = new Fighter($player->id);
	$monster = new Monster((int)$_GET['fight'],$_POST['razy'],0);

        if (!$monster -> id)
        {
            error(NO_MONSTER);
        }
        if ($attacker -> clas == '')
        {
            error(NO_CLASS3);
        }

	pvmfastfight($attacker,$monster,$_POST['times'],$_POST['razy']);
	require_once("includes/foot.php");
	exit;
    }
}

/**
* Initialization of variables
*/
if (!isset($_GET['battle']))
{
    $_GET['battle'] = '';
}

if (!isset($_GET['step']))
{
    $_GET['step'] = '';
}

if (!isset($_GET['fight']))
{
    $_GET['fight'] = '';
}

if (!isset($_GET['fight1']))
{
    $_GET['fight1'] = '';
}

if (!isset($_GET['dalej']))
{
    $_GET['dalej'] = '';
}

if (!isset($_GET['next']))
{
    $_GET['next'] = '';
}

if (!isset($_GET['action']))
{
    $_GET['action'] = '';
}
if (!isset($_GET['view']))
{
    $_GET['view'] = '';
}

/**
* Assign variables and display page
*/
$smarty -> assign (array("Action" => $_GET['action'],
                         "Battle" => $_GET['battle'],
                         "Step" => $_GET['step'],
                         "Fight" => $_GET['fight'],
                         "Fight1" => $_GET['fight1'],
                         "Dalej" => $_GET['dalej'],
                         "Next" => $_GET['next'],
                         "View" => $_GET['view']));
$smarty -> display ('battle.tpl');

require_once("includes/foot.php");
?>
