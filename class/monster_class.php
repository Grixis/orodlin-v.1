<?php
/**
 *   File functions:
 *   Class with information about fighting player
 *
 *   @name                 : opponent_class.php
 *   @copyright            : (C) 2004,2005,2006,2007 Orodlin Team based on Vallheru
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @author               : Marek 'marq' Chodor <marek.chodor@gmail.com>
 *   @version              : 1.4a
 *   @since                : 31.05.2007
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


class Monster
{
    var $id;
    var $user;
    var $level;
    var $credits;
    var $credits1;
    var $credits2;
    var $exp;
    var $exp1;
    var $exp2;
    var $hp;
    var $mana;
    var $clas = 'Monster';
    var $strength;
    var $agility;
    var $speed;
    var $cond;
    var $basehp;
    var $amount;
    
    var $hitmodificator;
    var $missmodificator;
    var $attackspeed;
    var $damage;
    var $defence;
    var $poisontype = '';
    var $noweapon=1;
    var $noarrows=0;
    var $usespell=0;
    var $usedefspell=0;
    var $usesword=0;
    var $usebow=0;
    var $criticalhit=0;
    var $criticalstat=0;


    var $attcost=1;
    var $misscost=1;
    
/**
* Class constructor - get data from database and write it to variables
*/
    function Monster($intId,$amount,$nr)
    {
	global $db;
//dane z sesji
	if (isset($_SESSION['amount']) && $_SESSION['amount'] > 0 && isset($_SESSION['mon'.$nr]['user'])) {
		$this -> id = $_SESSION['mon'.$nr]['id'];
		$this -> user = $_SESSION['mon'.$nr]['user'];
		$this -> level = $_SESSION['mon'.$nr]['level'];
		$this -> credits = $_SESSION['mon'.$nr]['credits'];
		$this -> exp = $_SESSION['mon'.$nr]['exp'];
		$this -> actionpoints = $_SESSION['mon'.$nr]['actionpoints'];
		$this -> defence = $_SESSION['mon'.$nr]['defence'];
		$this -> damage = $_SESSION['mon'.$nr]['damage'];
		$this -> hitmodificator = $_SESSION['mon'.$nr]['hitmodificator'];
		$this -> missmodificator = $_SESSION['mon'.$nr]['missmodificator'];
		$this -> attackspeed = $_SESSION['mon'.$nr]['attackspeed'];
		$this -> amount = 1;
		$this -> basehp = $_SESSION['mon'.$nr]['hp'];
		$this -> hp = $_SESSION['mon'.$nr]['hp'];
		}
	else {
//inicjalizacja standardowych wartosci z bazy
		$stats = $db -> Execute('SELECT `id`, `name`, `level`, `credits1`, `credits2`, `hp`, `exp1`, `exp2`, `strength`, `agility`, `speed`, `endurance` FROM `monsters` WHERE `id`='.$intId);

		if ($stats -> fields['id']) {
			$this -> amount = $amount;
			$this -> id = (int) $stats -> fields['id'];
			$this -> user = (string) $stats -> fields['name'];
			$this -> level = (int) $stats -> fields['level'];
			$this -> credits1 = (int) $stats -> fields['credits1'];
			$this -> credits2 = (int) $stats -> fields['credits2'];
			$this -> basehp = (int) $stats -> fields['hp'];
			$this -> exp1 = (int) $stats -> fields['exp1'];
			$this -> exp2 = (int) $stats -> fields['exp2'];
			
			$this -> strength = (float) $stats -> fields['strength'];
			$this -> agility = (float) $stats -> fields['agility'];
			$this -> speed = (float) $stats -> fields['speed'];
			$this -> cond = (float) $stats -> fields['endurance'];

			$this -> hp = $this -> basehp * $this -> amount;
			$this -> mana = 0;

			$this -> credits = (int) rand($this -> credits1, $this -> credits2);
			$this -> exp = rand($this -> exp1,$this -> exp2);
		
			$rzut = (rand(0,20)-10)/100 + 1;
			$this -> actionpoints = $this -> cond * $rzut;
			$this -> defence = $this -> cond * $rzut;
			$rzut = (rand(0,20)-10)/100 + 1;
			$this -> damage = $this -> strength * $rzut;
			$rzut = (rand(0,20)-10)/100 + 1;
			$this -> hitmodificator = $this -> agility * $rzut;
			$this -> missmodificator = $this -> agility * $rzut;
			$rzut = (rand(0,20)-10)/100 + 1;
			$this -> attackspeed = $this -> speed * $rzut;
			$stats -> Close();
			}

		if (isset($_SESSION['amount'])) {
			$_SESSION['mon'.$nr]['id'] = $this -> id;
			$_SESSION['mon'.$nr]['user'] = $this -> user;
			$_SESSION['mon'.$nr]['level'] = $this -> level;
			$_SESSION['mon'.$nr]['credits'] = $this -> credits;
			$_SESSION['mon'.$nr]['exp'] = $this -> exp;
			$_SESSION['mon'.$nr]['actionpoints'] = $this -> actionpoints;
			$_SESSION['mon'.$nr]['defence'] = $this -> defence;
			$_SESSION['mon'.$nr]['damage'] = $this -> damage;
			$_SESSION['mon'.$nr]['hitmodificator'] = $this -> hitmodificator;
			$_SESSION['mon'.$nr]['missmodificator'] = $this -> missmodificator;
			$_SESSION['mon'.$nr]['attackspeed'] = $this -> attackspeed;
			}

		}
    }

    function resetAll()
    {
	$this -> hp = $this -> basehp * $this -> amount;
	$this -> mana = 0;
	$this -> credits = rand($this -> credits1, $this -> credits2);
	$this -> exp = rand($this -> exp1,$this -> exp2);
	$rzut = (rand(0,20)-10)/100 + 1;
	$this -> actionpoints = $this -> cond * $rzut;
	$this -> defence = $this -> cond * $rzut;
	$rzut = (rand(0,20)-10)/100 + 1;
	$this -> damage = $this -> strength * $rzut;
	$rzut = (rand(0,20)-10)/100 + 1;
	$this -> hitmodificator = $this -> agility * $rzut;
	$this -> missmodificator = $this -> agility * $rzut;
	$rzut = (rand(0,20)-10)/100 + 1;
	$this -> attackspeed = $this -> speed * $rzut;
    }

}
