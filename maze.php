<?php
/**
 *   File functions:
 *   Labyrynth in forrest city
 *
 *   @name                 : maze.php                            
 *   @copyright            : (C) 2004,2005,2006 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @version              : 1.3
 *   @since                : 31.10.2006
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
// $Id: maze.php 804 2006-10-31 14:12:31Z thindil $

$title = "Opuszczony szyb";
$title1 = $title;
require_once("includes/head.php");
require_once("includes/funkcje.php");
require_once("includes/turnfight.php");

/**
* Get the localization for game
*/
require_once("languages/".$player -> lang."/maze.php");

if ($player -> location != 'Ardulith') 
{
    error (ERROR);
}

/**
* Function to fight with monsters
*/
function battle($type,$adress) 
{
	require_once('class/monster_class.php');
	require_once('class/fight_class.php');
    global $player;
    global $smarty;
    global $enemy;
    global $arrehp;
    global $db;
    if ($player -> hp <= 0) 
    {
        error (NO_LIFE);
    }
    $enemy1 = $db -> Execute("SELECT * FROM monsters WHERE id=".$player -> fight);
    $span = ($enemy1 -> fields['level'] / $player -> level);
    if ($span > 2) 
    {
        $span = 2;
    }
    $expgain = ceil(rand($enemy1 -> fields['exp1'],$enemy1 -> fields['exp2'])  * $span);
    $goldgain = ceil(rand($enemy1 -> fields['credits1'],$enemy1 -> fields['credits2']) * $span);
    $enemy = array("strength" => $enemy1 -> fields['strength'], 
                   "agility" => $enemy1 -> fields['agility'], 
                   "speed" => $enemy1 -> fields['speed'], 
                   "endurance" => $enemy1 -> fields['endurance'], 
                   "hp" => $enemy1 -> fields['hp'], 
                   "name" => $enemy1 -> fields['name'], 
                   "id" => $enemy1 -> fields['id'], 
                   "exp1" => $enemy1 -> fields['exp1'], 
                   "exp2" => $enemy1 -> fields['exp2'],  
                   "level" => $enemy1 -> fields['level']);
    if ($type == 'T') 
    {
        if (!isset ($_POST['action'])) 
        {
//prepare session variables for monsters and player
		$monster = new Monster($player -> fight,1,0);
		$attacker = new Fighter($player -> id);
		$_SESSION['amount'] = 1;
		for ($k = 0; $k < $_SESSION['amount']; $k++) {
			//each monster identifier
			$strIndex = 'mon'.$k;
			$_SESSION[$strIndex]['id'] = $monster -> id;
			//each monster hit points
			$_SESSION[$strIndex]['hp'] = $monster -> hp;
			//each monster action points
			if ($attacker -> speed > $monster -> attackspeed) {
				$_SESSION[$strIndex]['ap'] = 1;
				}
			else {
				$_SESSION[$strIndex]['ap'] = floor($monster -> attackspeed / $attacker -> speed);
				if ($_SESSION[$strIndex]['ap'] > 5) {
					$_SESSION[$strIndex]['ap'] = 5;
					}
				}
			$tmpActionArr[$k][0] = $monster -> attackspeed;
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
		if ($attacker -> speed > $monster -> attackspeed) {
			$_SESSION['points'] = floor($attacker -> speed / $monster -> attackspeed);
			if ($_SESSION['points'] > 5) {
				$_SESSION['points'] = 5;
				}
			}
		else {
			$_SESSION['points'] = 1;
			}
		$_SESSION['round']=0;
		}
        turnfight ($adress);
		if (isset($_SESSION['result'])) unset($_SESSION['result']);
    } 
        else 
    {
	$monster = new Monster($player -> fight,1,0);
	$attacker = new Fighter($player -> id);
        pvmfastfight ($attacker,$monster,1,1);
    }
    $fight = $db -> Execute("SELECT fight FROM players WHERE id=".$player -> id);
    if ($fight -> fields['fight'] == 0) 
    {
        $player -> energy = $player -> energy - 1;
        if ($player -> energy < 0) 
        {
            $player -> energy = 0;
        }
        $db -> Execute("UPDATE players SET energy=".$player -> energy." WHERE id=".$player -> id);
        $smarty -> assign ("Link", "<br /><br /><a href=\"maze.php?action=explore\">".A_EXPLORE."</a><br />");
    }
        else
    {
        $smarty -> assign("Link", '');
    }
    $fight -> Close();
    $enemy1 -> Close();
}

/**
* If player not escape - start fight
*/
if (isset($_GET['step']) && $_GET['step'] == 'battle') 
{
    if (!isset ($_GET['type'])) 
    {
        $type = 'T';
    } 
        else 
    {
        $type = $_GET['type'];
    }
    battle($type,'maze.php?step=battle');
}

/**
* If player escape
*/
if (isset($_GET['step']) && $_GET['step'] == 'run') 
{
    $enemy = $db -> Execute("SELECT speed, name, exp1, exp2, id FROM monsters WHERE id=".$player -> fight);
	if (empty($enemy -> fields))
	{
		error(ERROR);
	}
    /**
     * Add bonus from rings
     */
    $arrEquip = $player -> equipment();
    if ($arrEquip[9][2])
    {
        $arrRingtype = explode(" ", $arrEquip[9][1]);
        $intAmount = count($arrRingtype) - 1;
        if ($arrRingtype[$intAmount] == R_SPE5)
        {
            $player -> speed = $player -> speed + $arrEquip[9][2];
        }
    } 
    if ($arrEquip[10][2])
    {
        $arrRingtype = explode(" ", $arrEquip[10][1]);
        $intAmount = count($arrRingtype) - 1;
        if ($arrRingtype[$intAmount] == R_SPE5)
        {
            $player -> speed = $player -> speed + $arrEquip[10][2];
        }
    } 
        $chance = (rand(1, $player -> level * 100) + $player -> speed - $enemy -> fields['speed']);
        $intChance2 = rand(1, 10);
        $smarty -> assign ("Chance", $chance);
    if (($intChance2 < 3) || ($chance > 0))
    {
	    $intExpGain = 2;
	    $intExpLvl = ceil($enemy -> fields['level']/10);
	    if ($intExpGain < $intExpLvl)
	    	$intExpGain = $intExpLvl;
        $smarty -> assign(array("Ename" => $enemy -> fields['name'], 
                                "Expgain" => $intExpGain,
                                "Escapesucc" => ESCAPE_SUCC,
                                "Escapesucc2" => ESCAPE_SUCC2,
                                "Escapesucc3" => ESCAPE_SUCC3));
        checkexp($player -> exp,$intExpGain,$player -> level,$player -> race,$player -> user,$player -> id,0,0,$player -> id,'',0);
        $db -> Execute("UPDATE players SET fight=0 WHERE id=".$player -> id);
    } 
        else 
    {
        $strMessage = ESCAPE_FAIL." ".$enemy -> fields['name']." ".ESCAPE_FAIL2.".<br />";
        $smarty -> assign ("Message", $strMessage);
        $smarty -> display ('error1.tpl');
        battle('T','maze.php?step=battle');
    }
    $hp = $db -> Execute("SELECT hp FROM players WHERE id=".$player -> id);
    $smarty -> assign ("Health", $hp -> fields['hp']);
    if ($hp -> fields['hp'] > 0) 
    {
        $smarty -> assign("Link", "<a href=\"maze.php?action=explore\">".A_EXPLORE."</a>");
    }
    $hp -> Close();
}

if (!isset($_GET['action']))
{
    $smarty -> assign(array("Mazeinfo" => MAZE_INFO,
        "Ayes" => YES));
    $_GET['action'] = '';
}

if (isset($_GET['action']) && $_GET['action'] == 'explore')
{
    if ($player -> energy < 0.3)
    {
        error(NO_ENERGY);
    }
    if ($player -> hp <= 0)
    {
        error(YOU_DEAD);
    }
    if (!empty($player -> fight)) 
    {
        $enemy = $db -> Execute("SELECT name FROM monsters WHERE id=".$player -> fight);
        error (FIGHT1.$enemy -> fields['name'].FIGHT2."<br />
                   <a href=maze.php?step=battle&type=T>".Y_TURN_F."</a><br />
                   <a href=maze.php?step=battle&type=N>".Y_NORM_F."</a><br />
               <a href=maze.php?step=run>".NO."</a><br />");
        $enemy -> Close();
    }
    $db -> Execute("UPDATE players SET energy=energy-.3 WHERE id=".$player -> id);
    $intRoll = rand(1, 100);
    $smarty -> assign(array("Link" => "<a href=\"maze.php?action=explore\">".A_EXPLORE."</a>",
        "Roll" => $intRoll));
    if ($intRoll < 49)
    {
        $smarty -> assign("Message", EMPTY_1);
    }
    if ($intRoll > 48 && $intRoll < 64)
    {
        $intRoll2 = rand(1,5);
        if ($inRoll2 < 5 )
        {
        $arrMonsters = array(2, 4, 12, 14, 17, 69, 74, 79, 84);
        $intRoll3 = rand(0, 8);
        }
        if ($intRoll2 == 5)
        {
          	$arrMonsters = array(32, 41, 53, 88, 92, 102, 116);
            $intRoll3 = rand(0, 6);
        }
        $enemy = $db -> Execute("SELECT `name`, `id` FROM `monsters` WHERE id=".$arrMonsters[$intRoll3]);
        $db -> Execute("UPDATE `players` SET `fight`=".$enemy -> fields['id']." WHERE `id`=".$player -> id);
        $smarty -> assign (array("Name" => $enemy -> fields['name'],
                                 "Youmeet" => YOU_MEET,
                                 "Fight2" => FIGHT2,
                                 "Yturnf" => Y_TURN_F,
                                 "Ynormf" => Y_NORM_F,
                                 "Ano" => NO));
    }
    if ($intRoll > 63 && $intRoll < 70)
    {
        $objHerb = $db -> Execute("SELECT gracz FROM herbs WHERE gracz=".$player -> id);
        $arrHerbs = array('illani', 'illanias', 'nutari', 'dynallca');
        $intRoll2 = rand(0, 3);
        $intAmount = rand(1, 10);
        if (!$objHerb -> fields['gracz']) 
        {
            $db -> Execute("INSERT INTO herbs (gracz, ".$arrHerbs[$intRoll2].") VALUES(".$player -> id.",".$intAmount.")");
        } 
            else 
        {
            $db -> Execute("UPDATE herbs SET ".$arrHerbs[$intRoll2]."=".$arrHerbs[$intRoll2]."+".$intAmount." WHERE gracz=".$player -> id);
        }
        $objHerb -> Close();
        $smarty -> assign("Message", F_HERBS.$intAmount.I_AMOUNT.$arrHerbs[$intRoll2]);
    }
    if ($intRoll > 69 && $intRoll < 76)
    {
        $objLumber = $db -> Execute("SELECT owner FROM minerals WHERE owner=".$player -> id);
        $intAmount = rand(1, 10);
        $arrLumber = array('pine', 'hazel', 'yew', 'elm');
        $arrType = array(T_PINE, T_HAZEL, T_YEW, T_ELM);
        $intRoll2 = rand(0, 3);
        if (!$objLumber -> fields['owner']) 
        {
            $db -> Execute("INSERT INTO minerals (owner, ".$arrLumber[$intRoll2].") VALUES(".$player -> id.",".$intAmount.")");
        } 
            else 
        {
            $db -> Execute("UPDATE minerals SET ".$arrLumber[$intRoll2]."=".$arrLumber[$intRoll2]."+".$intAmount." WHERE owner=".$player -> id);
        }
        $objLumber -> Close();
        $smarty -> assign("Message", F_LUMBER.$intAmount.I_AMOUNT.$arrType[$intRoll2]);
    }
    if ($intRoll > 75 && $intRoll < 81)
    {
        $intRoll2 = rand(1,5);
        $db -> Execute("UPDATE players SET platinum=platinum+".$intRoll2." WHERE id=".$player -> id);
        $smarty -> assign("Message", F_MITHRIL.$intRoll2.M_AMOUNT);
    }
    if ($intRoll > 80 && $intRoll < 86)
    {
        $smarty -> assign("Message", F_ENERGY);
        $db -> Execute("UPDATE players SET energy=energy+1 WHERE id=".$player -> id);
    }
    if ($intRoll > 85 && $intRoll < 89)
    {
        $smarty -> assign("Message", F_ENERGY);
        $db -> Execute("UPDATE players SET energy=energy+1 WHERE id=".$player -> id);
    }
    if ($intRoll > 88 && $intRoll < 91)
    {
        $intRoll2 = rand(1, 10);
        if ($intRoll2 < 6)
        {
            $intRoll3 = rand(1,3);
            $strSymbol = '<';
            if ($intRoll3 == 1)
            {
                $strType = 'B';
            }
            if ($intRoll3 == 2)
            {
                $strType = 'O';
            }
            if ($intRoll3 == 3)
            {
                $strType = 'U';
            }
        }
        if ($intRoll2 > 5 && $intRoll2 < 9)
        {
            $intRoll3 = rand(1,3);
            $strSymbol = '=';
            if ($intRoll3 == 1)
            {
                $strType = 'B';
            }
            if ($intRoll3 == 2)
            {
                $strType = 'O';
            }
            if ($intRoll3 == 3)
            {
                $strType = 'U';
            }
        }
        if ($intRoll2 < 9)
        {
            $smarty -> assign("Message", EMPTY_3); // previous: find spells
        }
        if ($intRoll2 > 8)
        {
            $smarty -> assign("Message", EMPTY_4);
        }
    }
    if ($intRoll == 91)
    {
        $intRoll2 = rand(1, 10);
        if ($intRoll2 < 6)
        {
            $strSymbol = '<';
        }
        if ($intRoll2 > 5 || $intRoll2 < 9)
        {
            $strSymbol = '=';
        }
        if ($intRoll2 < 9)
        {
            $smarty -> assign("Message", EMPTY_6);  // prevoius: find bow/arrow plans
        }
        if ($intRoll2 > 8)
        {
            $smarty -> assign("Message", EMPTY_1);
        }
    }
    if ($intRoll == 92)
    {
        $intRoll2 = rand(1, 10);
        if ($intRoll2 < 6)
        {
            $strSymbol = '<';
        }
        if ($intRoll2 == 6 || $intRoll2 == 7)
        {
            $strSymbol = '=';
        }
        if ($intRoll2 == 8)
        {
            $strSymbol = '>';
        }
        if ($intRoll2 < 9)
        {
            $smarty -> assign("Message", EMPTY_3);  // previous: find alchemy recipes
        }
        if ($intRoll2 > 8)
        {
            $smarty -> assign("Message", EMPTY_4);
        }
    }
    if ($intRoll == 93)
    {
        $intRoll2 = rand(1, 10);
        if ($intRoll2 < 6)
        {
            $strSymbol = '<';
        }
        if ($intRoll2 == 6 || $intRoll2 == 7)
        {
            $strSymbol = '=';
        }
        if ($intRoll2 == 8)
        {
            $strSymbol = '>';
        }
        if ($intRoll2 < 9)
        {
            $smarty -> assign("Message", EMPTY_5); // previous: find mage staffs (wands)
        }
        if ($intRoll2 > 8)
        {
            $smarty -> assign("Message", EMPTY_1);
        }
    }
    if ($intRoll == 94)
    {
        $intRoll2 = rand(1, 10);
        if ($intRoll2 < 6)
        {
            $strSymbol = '<';
        }
        if ($intRoll2 == 6 || $intRoll2 == 7)
        {
            $strSymbol = '=';
        }
        if ($intRoll2 == 8)
        {
            $strSymbol = '>';
        }
        if ($intRoll2 < 9)
        {
            $smarty -> assign("Message", EMPTY_3);  // previous: find mage clothes (robes)
        }
        if ($intRoll2 > 8)
        {
            $smarty -> assign("Message", EMPTY_4);
        }
    }
    if ($intRoll == 95)
    {
        $intRoll2 = rand(1, 10);
        if ($intRoll2 < 6)
        {
            $strSymbol = '<';
        }
        if ($intRoll2 == 6 || $intRoll2 == 7)
        {
            $strSymbol = '=';
        }
        if ($intRoll2 == 8)
        {
            $strSymbol = '>';
        }
        if ($intRoll2 < 9)
        {
            $smarty -> assign("Message", EMPTY_6);  // previous: find bows
        }
        if ($intRoll2 > 8)
        {
            $smarty -> assign("Message", EMPTY_1);
        }
    }
    /**
     * Find astral components
     */
    if ($intRoll == 96 || $intRoll == 97)
    {
        require_once('includes/findastral.php');
        $strResult = findastral(5);
        if ($strResult != false)
        {
            $smarty -> assign("Message", F_ASTRAL.$strResult);
        }
            else
        {
            $smarty -> assign("Message", EMPTY_1);
        }
    }
    if (0 && $intRoll > 97) //no quest available for now
    {
        $aviable = $db -> Execute("SELECT qid FROM quests WHERE location='maze.php' AND name='start'");
        $number = $aviable -> RecordCount();
        if ($number > 0) 
        {
            $arramount = array();
            $i = 0;
            while (!$aviable -> EOF) 
            {
                $query = $db -> Execute("SELECT id FROM questaction WHERE quest=".$aviable -> fields['qid']." AND player=".$player -> id);
                if (empty($query -> fields['id'])) 
                {
                    $arramount[$i] = $aviable -> fields['qid'];
                    $i = $i + 1;
                }
                $query -> Close();
                $aviable -> MoveNext();
            }
            $i = $i - 1;
            if ($i >= 0) 
            {
                $roll = rand(0,$i);
                $name = "quest".$arramount[$roll].".php";
                require_once("quests/".$name);
            } 
                else 
            {
                $smarty -> assign("Message", EMPTY_2);
            }
        }
            else
        {
            $smarty -> assign("Message", EMPTY_3);
        }
        $aviable -> Close();
    }
}
//no quests
if (0 && isset($_GET['step']) && $_GET['step'] == 'quest') 
{
    $query = $db -> Execute("SELECT quest FROM questaction WHERE player=".$player -> id." AND action!='end'");
    $name = "quest".$query -> fields['quest'].".php";
    if (!empty($query -> fields['quest'])) 
    {   
        require_once("quests/".$name);
    }
    $query -> Close();    
}

/**
* Initialization of variable
*/
if (!isset($_GET['step']))
{
    $_GET['step'] = '';
}

/**
* Assign variables to template and display page
*/
$smarty -> assign(array("Action" => $_GET['action'],
                        "Step" => $_GET['step']));
$smarty -> display ('maze.tpl');

require_once("includes/foot.php");
?>
