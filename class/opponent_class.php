<?php
/**
 *   File functions:
 *   Class with information about attacked player
 *
 *   @name                 : opponent_class.php
 *   @copyright            : (C) 2004,2005,2006,2007 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @version              : 1.3
 *   @since                : 28.02.2006
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
// $Id: player_class.php 905 2007-02-28 21:33:05Z thindil $

class Opponent
{
    var $id;
    var $user;
    var $level;
    var $tribe;
    var $credits;
    var $location;
    var $hp;
    var $mana;
    var $exp;
    var $age;
    var $race;
    var $clas;
    var $immunited;
    var $strength;
    var $agility;
    var $speed;
    var $cond;
    var $inteli;
    var $wisdom;
    var $attack;
    var $miss;
    var $magic;
    var $shoot;
    var $maps;
    var $rest;
    var $fight;
    var $antidote_d;
    var $antidote_n;
    var $antidote_i;
    var $battlelog;
/**
* Class constructor - get data from database and write it to variables
*/
    function Opponent($intId)
    {// przygotowanie do odpalenia stats() na przeciwniku
        global $db;
        $stats = $db -> Execute('SELECT `id`, `user`, `level`, `tribe`, `credits`, `miejsce`, `hp`, `pm`, `exp`, `age`, `rasa`, `klasa`, `immu`, `strength`, `agility`, `szyb`, `wytrz`, `inteli`, `wisdom`, `atak`, `unik`, `magia`, `shoot`, `maps`, `rest`, `fight`, `antidote_d`, `antidote_n`, `antidote_i`, `battlelog` FROM `players` WHERE `id`='.$intId);
        $this -> id = $intId;
        $this -> user = $stats -> fields['user'];
        $this -> level = $stats -> fields['level'];
        $this -> tribe = $stats -> fields['tribe'];
        $this -> credits = $stats -> fields['credits'];
        $this -> location = $stats -> fields['miejsce'];
        $this -> hp = $stats -> fields['hp'];
        $this -> mana = $stats -> fields['pm'];
        $this -> exp = $stats -> fields['exp'];
        $this -> age = $stats -> fields['age'];
        $this -> race = $stats -> fields['rasa'];
        $this -> clas = $stats -> fields['klasa'];
        $this -> immunited = $stats -> fields['immu'];
        $this -> strength = $stats -> fields['strength'];
        $this -> agility = $stats -> fields['agility'];
        $this -> speed = $stats -> fields['szyb'];
        $this -> cond = $stats -> fields['wytrz'];
        $this -> inteli = $stats -> fields['inteli'];
        $this -> wisdom = $stats -> fields['wisdom'];
        $this -> attack = $stats -> fields['atak'];
        $this -> miss = $stats -> fields['unik'];
        $this -> magic = $stats -> fields['magia'];
        $this -> shoot = $stats -> fields['shoot'];
        $this -> maps = $stats -> fields['maps'];
        $this -> rest = $stats -> fields['rest'];
        $this -> fight = $stats -> fields['fight'];
        $this -> antidote_d = (!empty($stats -> fields['antidote_d'])) ? $stats -> fields['antidote_d']{0} : '';
        $this -> antidote_n = (!empty($stats -> fields['antidote_n'])) ? $stats -> fields['antidote_n']{0} : '';
        $this -> antidote_i = (!empty($stats -> fields['antidote_i'])) ? $stats -> fields['antidote_i']{0} : '';
        $this -> battlelog = $stats -> fields['battlelog'];
        $stats -> Close();
    }

    /**
     * Function return values of selected atributes in array
     * Używana tylko w walce, zeby miała sens - wcześniej musi być pobranie statów.
     */
    function stats($stats)
    {
        $arrstats = array();
        foreach ($stats as $value)
        {
            $arrstats[$value] = $this -> $value;
        }
        return $arrstats;
    }

    /**
     * Function return values of equiped items
     */
    function equipment()
    {
        global $db;

        $arrEquip = array(array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
                          array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
                          array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
                          array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
                          array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
                          array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
                          array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
                          array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
                          array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
                          array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
                          array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0));
        $arrEquiptype = array('W', 'B', 'H', 'A', 'L', 'S', 'R', 'T', 'C', 'I');
        $objEquip = $db -> Execute('SELECT `id`, `name`, `power`, `type`, `minlev`, `zr`, `wt`, `szyb`, `poison`, `ptype`, `maxwt` FROM `equipment` WHERE `owner`='.$this -> id.' AND status=\'E\'');
        while (!$objEquip -> EOF)
        {
            $intKey = array_search($objEquip -> fields['type'], $arrEquiptype);
            if ($arrEquip[9][0] && $objEquip -> fields['id'] != $arrEquip[9][0] && $objEquip -> fields['type'] == 'I')
            {
                $intKey = 10;
            }
            $arrEquip[$intKey][0] = $objEquip -> fields['id'];
            $arrEquip[$intKey][1] = $objEquip -> fields['name'];
            $arrEquip[$intKey][2] = $objEquip -> fields['power'];
            $arrEquip[$intKey][3] = $objEquip -> fields['ptype'];
            $arrEquip[$intKey][4] = $objEquip -> fields['minlev'];
            $arrEquip[$intKey][5] = $objEquip -> fields['zr'];
            $arrEquip[$intKey][6] = $objEquip -> fields['wt'];
            $arrEquip[$intKey][7] = $objEquip -> fields['szyb'];
            $arrEquip[$intKey][8] = $objEquip -> fields['poison'];
            $arrEquip[$intKey][9] = $objEquip -> fields['maxwt'];
            $objEquip -> MoveNext();
        }
        $objEquip -> Close();
        return $arrEquip;
    }
}
