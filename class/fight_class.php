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


class Fighter
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
    var $battleloga;
    var $battlelogd;
    var $bless;
    var $blessval;

    var $hitmodificator;
    var $missmodificator;
    var $attackspeed;
    var $criticalhit;
    var $criticalstat;
    var $misspell;
    var $damage;
    var $mindamage;
    var $maxdamage;
    var $defence;
    var $poisonpower;
    var $poisontype;
    var $noweapon;
    var $noarrows;
    var $usespell;
    var $usedefspell;
    var $usesword;
    var $usebow;
    var $actionpoints;
    var $equipment;
    var $spell;
    var $attcost;
    var $misscost;
    var $gainmiss;
    var $gainattack;
    var $gainshoot;
    var $gainmagic;
    var $gaingold;
    var $cumulateexp;
    var $gainexp;
    var $lostequip;
    var $loststat;
    var $lost;

/**
* Class constructor - get data from database and write it to variables
*/
    function Fighter($intId)
    {// przygotowanie do odpalenia stats() na przeciwniku
        global $db;
//inicjalizacja standardowych wartosci z bazy
         $stats = $db -> Execute('SELECT `id`, `user`, `level`, `tribe`, `credits`, `miejsce`, `hp`, `pm`, `exp`, `age`, `rasa`, `klasa`, `immu`, `strength`, `agility`, `szyb`, `wytrz`, `inteli`, `wisdom`, `atak`, `unik`, `magia`, `shoot`, `maps`, `rest`, `fight`, `antidote_d`, `antidote_n`, `antidote_i`, `bless`, `blessval`, `battleloga`, `battlelogd` FROM `players` WHERE `id`='.$intId);

	$this -> lostequip = array(0,0,0,0,0,0,0);
	$this -> cumulateexp = 0;

	if ($stats -> fields['id']) {
        	$this -> id = (int) $stats -> fields['id'];
        	$this -> user = (string) $stats -> fields['user'];
	        $this -> level = (int) $stats -> fields['level'];
        	$this -> tribe = (int) $stats -> fields['tribe'];
	        $this -> credits = (int) $stats -> fields['credits'];
        	$this -> location = (string) $stats -> fields['miejsce'];
	        $this -> hp = (int) $stats -> fields['hp'];
        	$this -> mana = (int) $stats -> fields['pm'];
	        $this -> exp = (int) $stats -> fields['exp'];
        	$this -> age = (int) $stats -> fields['age'];
	        $this -> race = (string) $stats -> fields['rasa'];
        	$this -> clas = (string) $stats -> fields['klasa'];
	        $this -> immunited = (string) $stats -> fields['immu'];
        	$this -> strength = (float) $stats -> fields['strength'];
	        $this -> agility = (float) $stats -> fields['agility'];
        	$this -> speed = (float) $stats -> fields['szyb'];
	        $this -> cond = (float) $stats -> fields['wytrz'];
        	$this -> inteli = (float) $stats -> fields['inteli'];
	        $this -> wisdom = (float) $stats -> fields['wisdom'];
        	$this -> attack = (float) $stats -> fields['atak'];
	        $this -> miss = (float) $stats -> fields['unik'];
        	$this -> magic = (float) $stats -> fields['magia'];
	        $this -> shoot = (float) $stats -> fields['shoot'];
        	$this -> maps = (int) $stats -> fields['maps'];
	        $this -> rest = (string) $stats -> fields['rest'];
	        $this -> antidote_d =  (!empty($stats -> fields['antidote_d'])) ? (int) $stats -> fields['antidote_d']{0} : '';
        	$this -> antidote_n =  (!empty($stats -> fields['antidote_n'])) ? (int) $stats -> fields['antidote_n']{0} : '';
	        $this -> antidote_i = (!empty($stats -> fields['antidote_i'])) ? (int) $stats -> fields['antidote_i']{0} : '';
		$this -> bless = (string) $stats -> fields['bless'];
        	$this -> blessval = (int) $stats -> fields['blessval'];
        	$this -> battleloga = $stats -> fields['battleloga'];
        	$this -> battlelogd = $stats -> fields['battlelogd'];

        	$this -> fight = $stats -> fields['fight'];
	//pobranie danych o ekwipunku
        	$arrEquip = $this -> equipment();
	//czry maga
		$arrSpell = $this -> spells();

		$this -> equipment = $arrEquip;
		$this -> spell = $arrSpell;

		$this -> setBless();
		$this -> setRingBonus($arrEquip);
		$this -> actionpoints = $this -> cond;
		$this -> setDamage();
	        $this -> setDefence($arrEquip,$arrSpell);
        	$this -> setCriticalHit($arrEquip,$arrSpell);
	        $this -> setHitModificator($arrEquip,$arrSpell);
        	$this -> setMissModificator($arrEquip);
	        $this -> setAttackSpeed($arrEquip);


	        if ($this -> clas == 'Mag' && $arrSpell[0][0]) {
			$this -> setMisspell($arrSpell);
			}

		$stats -> Close();
		}


    }

/*
funkcja zapisuje wart. do bazy danych
*/
    function savetodb($enemy)
    {
	global $db;
	$miss = $this -> miss + $this -> gainmiss;
	$attack = $this -> attack + $this -> gainattack;
	$shoot = $this -> shoot + $this -> gainshoot;
	$magic = $this -> magic + $this -> gainmagic;
	$gold = $this -> credits + $this -> gaingold;
	if ($this -> mana < 0) $this -> mana = 0;
	if ($this -> hp < 0) $this -> hp = 0;

	if (!isset($enemy -> id)) {
		$db -> Execute('UPDATE `players` SET `bless`=\'\', `blessval`=0, `hp`='.$this->hp.', `pm`='.$this->mana.', `credits`='.$gold.', `atak`='.$attack.', `unik`='.$miss.', `magia`='.$magic.', `shoot`='.$shoot.' WHERE `id`='.$this->id);
		if ($this -> lost > 0) {
			$stats = array('strength','agility','inteli','wytrz','szyb','wisdom');
			$db -> Execute('UPDATE `players` SET `'.$stats[$this->loststat].'`=`'.$stats[$this->loststat].'`-'.$this->lost.' WHERE `id`='.$this->id);
			}
		}
	else {
		if ($enemy -> hp > 0 && $this -> hp > 0) {
			$db -> Execute('UPDATE `players` SET `bless`=\'\', `blessval`=0, `hp`='.$this->hp.', `pm`='.$this->mana.', `atak`='.$attack.', `unik`='.$miss.', `magia`='.$magic.', `shoot`='.$shoot.' WHERE `id`='.$this->id);
			}
		else if($this -> hp > 0) {
			$db -> Execute('UPDATE `players` SET `bless`=\'\', `blessval`=0, `wins`=`wins`+1, `lastkilled`=\'<a href="view.php?view='.$enemy->id.'">'.$enemy->user.'</a>\', `hp`='.$this->hp.', `pm`='.$this->mana.', `credits`='.$gold.', `atak`='.$attack.', `unik`='.$miss.', `magia`='.$magic.', `shoot`='.$shoot.' WHERE `id`='.$this->id);
			}
		else {
			$stats = array('strength','agility','inteli','wytrz','szyb','wisdom');
			$sql = ($this -> lost > 0) ? ', `'.$stats[$this->loststat].'`=`'.$stats[$this->loststat].'`-'.$this->lost : '';
			$db -> Execute('UPDATE `players` SET `bless`=\'\', `blessval`=0, `losses`=`losses`+1, `lastkilledby`=\'<a href="view.php?view='.$enemy->id.'">'.$enemy->user.'</a>\', `hp`='.$this->hp.', `pm`='.$this->mana.', `credits`='.$gold.', `atak`='.$attack.', `unik`='.$miss.', `magia`='.$magic.', `shoot`='.$shoot.$sql.' WHERE `id`='.$this->id);
			}
		}

    }

/*
funkcja resetuje dotychczas wyliczone wartosci po walce, jezeli walczymy wiele razy
*/
    function resetAll()
    {
	$this -> poisontype = '';
	$this -> noweapon=0;
	$this -> noarrows=0;
	$this -> usespell=0;
	$this -> usedefspell=0;
	$this -> usesword=0;
	$this -> usebow=0;
	$this -> actionpoints = $this -> cond;
	$this -> attcost=1;
	$this -> misscost=1;
	$this -> miss += $this -> gainmiss;
	$this -> gainmiss=0;
	$this -> attack += $this -> gainattack;
	$this -> gainattack=0;
	$this -> shoot += $this -> gainshoot;
	$this -> gainshoot=0;
	$this -> magic += $this -> gainmagic;
	$this -> gainmagic=0;
	$this -> credits += $this -> gaingold;
	$this -> gaingold=0;
	$this -> cumulateexp += $this -> gainexp;
	$this -> gainexp=0;
	$this -> loststat='';
	$this -> lost=0;

	$this -> unsetBless();
	$this -> setDamage();
	$this -> setDefence($this -> equipment,$this -> spell);
	$this -> setCriticalHit($this -> equipment,$this -> spell);
	$this -> setHitModificator($this -> equipment,$this -> spell);
	$this -> setMissModificator($this -> equipment);
	$this -> setAttackSpeed($this -> equipment);

	if ($this -> clas == 'Mag' && $this -> spell[0][0]) {
		$this -> setMisspell($this -> spell);
		}
    }

/*
funkcja dodaje blogoslawienstwo ze swiatyni
*/
    function setBless()
    {
	$arrStat = array('agility', 'strength', 'inteli', 'wisdom', 'speed', 'cond', 'attack', 'shoot', 'miss', 'magic','hp');
	if ($this -> bless != '' && $this -> bless != null) {
		$arrBless = array('agility', 'strength', 'inteli', 'wisdom', 'szyb', 'wytrz', 'atak', 'shoot', 'unik', 'magia','hp');
		$intKey = array_search($this -> bless, $arrBless);
		$this -> $arrStat[$intKey] += $this -> blessval;
		}
    }

/*
funkcja zdejmuje blogoslawienstwo ze swiatyni
*/
    function unsetBless()
    {
	$arrStat = array('agility', 'strength', 'inteli', 'wisdom', 'speed', 'cond', 'attack', 'shoot', 'miss', 'magic','hp');
	if ($this -> bless != '' && $this -> bless != null) {
		$arrBless = array('agility', 'strength', 'inteli', 'wisdom', 'szyb', 'wytrz', 'atak', 'shoot', 'unik', 'magia','hp');
		$intKey = array_search($this -> bless, $arrBless);
		$this -> $arrStat[$intKey] -= $this -> blessval;
		$this -> bless = '';
		}
    }

/*
funkcja dodaje bonusy pierścieni
*/
    function setRingBonus($arrEquip)
    {
	$arrStat = array('agility', 'strength', 'inteli', 'wisdom', 'speed', 'cond');
	if ($arrEquip[9][2]) {
		$arrRings = array(AGILITY, STRENGTH, INTELIGENCE, R_WIS3, SPEED, CONDITION);
		$arrRingtype = explode(" ", $arrEquip[9][1]);
		$intAmount = count($arrRingtype) - 1;
		$intKey = array_search($arrRingtype[$intAmount], $arrRings);
		$this -> $arrStat[$intKey] += $arrEquip[9][2];
		}
	if ($arrEquip[10][2]) {
		$arrRings = array(AGILITY, STRENGTH, INTELIGENCE, R_WIS3, SPEED, CONDITION);
		$arrRingtype = explode(" ", $arrEquip[9][1]);
		$intAmount = count($arrRingtype) - 1;
		$intKey = array_search($arrRingtype[$intAmount], $arrRings);
		$this -> $arrStat[$intKey] += $arrEquip[9][2];
		}
	}

/*
funkcja odejmuje bonusy pierścieni
*/
    function unsetRingBonus($arrEquip)
    {
	$arrStat = array('agility', 'strength', 'inteli', 'wisdom', 'speed', 'cond');
	if ($arrEquip[9][2]) {
		$arrRings = array(AGILITY, STRENGTH, INTELIGENCE, R_WIS3, SPEED, CONDITION);
		$arrRingtype = explode(" ", $arrEquip[9][1]);
		$intAmount = count($arrRingtype) - 1;
		$intKey = array_search($arrRingtype[$intAmount], $arrRings);
		$this -> $arrStat[$intKey] -= $arrEquip[9][2];
		}
	if ($arrEquip[10][2]) {
		$arrRings = array(AGILITY, STRENGTH, INTELIGENCE, R_WIS3, SPEED, CONDITION);
		$arrRingtype = explode(" ", $arrEquip[9][1]);
		$intAmount = count($arrRingtype) - 1;
		$intKey = array_search($arrRingtype[$intAmount], $arrRings);
		$this -> $arrStat[$intKey] -= $arrEquip[9][2];
		}
	}



/*
funkcja liczy obrazenia bez broni
*/
    function setHandDamage()
    {
	$this -> usespell = 0;
	$this -> usesword = 0;
	$this -> usebow = 0;
	$this -> noweapon = 1;
	$this -> attcost = 1;
	$this -> damage = 0;
	$this -> mindamage = $this -> strength / 4;
	$this -> maxdamage = $this -> strength / 2;
    }

/*
funkcja liczy obrazenia od broni bialej
*/
    function setWeaponDamage()
    {
	if ($this -> equipment[0][6]) {
		$this -> usesword = $this -> equipment[0][0];
		$this -> attcost = $this -> equipment[0][4];
		if ($this -> equipment[0][3]) {
			$this -> poisonpower = $this -> equipment[0][8];
			$this -> poisontype = $this -> equipment[0][3];
			}
		$this -> damage = $this -> strength + $this -> equipment[0][2];
//bonusy klasowe
		if ($this -> clas == 'Wojownik' || $this -> clas == 'Barbarzyńca') {
			$this -> damage += $this -> level;
			}
		else if ($this -> clas == 'Rzemieślnik') {
			$this -> damage -= ($this -> damage)/4;
			}
		}
	else {
		$this -> damage = 0;
		}
    }

/*
funkcja liczy obrazenia od luku
*/
    function setBowDamage()
    {
	if ($this -> equipment[1][6]) {
		if($this -> equipment[6][6]) {
			$this -> usebow = $this -> equipment[6][0];
			$this -> attcost = $this -> equipment[1][4];
			if ($this -> equipment[6][3]) {
				$this -> poisonpower = $this -> equipment[6][8];
				$this -> poisontype = $this -> equipment[6][3];
				}
			$this -> damage = $this -> equipment[1][2] + $this -> equipment[6][2];
			$this -> mindamage = $this -> agility / 2;
			$this -> maxdamage = $this -> strength * 2;
//bonusy klasowe
			if ($this -> clas == 'Wojownik' || $this -> clas == 'Barbarzyńca') {
				$this -> damage += $this -> level;
				}
			if ($this -> clas == 'Rzemieślnik') {
				$this -> damage -= ($this -> damage)/4;
				}
			}
		else {
			$this -> noarrows = 1;
			$this -> damage = 0;
			}
		}
	else {
		$this -> damage = 0;
		}
    }

/*
funkcja liczy obrazenia od luku
*/
    function setMagicDamage()
    {
	if ($this -> spell[0][0] && $this -> mana > 0) {
		$this -> attcost = 0;
		$this -> usespell = $this -> spell[0][1];
		$this -> damage = $this -> inteli * $this -> spell[0][0];
//premia rozdzki
		if ($this -> equipment[7][0]) {
			$intN = 6 - (int)($this -> equipment[7][4] / 20);
			$this -> damage += (10/$intN) * $this -> level * rand(1,$intN);
			}
//kary z pancerzy
		for ($i=2;$i<=5;$i++) {
			if ($this -> equipment[$i][6]) {
				$this -> damage *= 1 - $this -> equipment[$i][4] / 100;
				}
			}
		}
	else {
		$this -> damage = 0;
		}
    }

/*
funkcja ustawia wartosc sily ataku
funkcja ustawia wartosc modyfikatorow trucizn
*/
    function setDamage()
    {
	$this -> damage = 0;
//bron biala
	if ($this -> equipment[0][6]) {
		$this -> setWeaponDamage();
		}
//luk + strzaly
	else if ($this -> equipment[1][6]) {
		$this -> setBowDamage();
		}
//czar
	else if ($this -> spell[0][0] && $this -> mana > 0) {
		$this -> setMagicDamage();
		}
//klepiemy na piesci
	if ($this -> damage == 0) {
		$this -> setHandDamage();
		}
    }

/*
funkcja ustawia wartosc sily obrony
*/
    function setDefence($arrEquip,$arrSpell)
    {
	$this -> defence = $this -> cond;
	$this -> misscost = 1;
//czar maga
	if ($arrSpell[1][0] && $this -> mana > 0) {
		$this -> usedefspell = $arrSpell[1][1];
		$this -> defence += $this -> wisdom * $arrSpell[1][0];
	//premia rozdzki
		if ($arrEquip[7][0]) {
			$intN = 6 - (int)($arrEquip[7][4] / 20);
			$this -> defence += (10/$intN) * $this -> level * rand(1,$intN);
			}
	//kary z pancerzy
		for ($i=2;$i<=5;$i++) {
			if ($arrEquip[$i][6]) {
				$this -> defence *= 1 - $arrEquip[$i][4] / 100;
				}
			}
		}
//bonus z pancerzy
	for ($i=2;$i<=5;$i++) {
		if ($arrEquip[$i][6]) {
			$this -> defence += $arrEquip[$i][2];
			if ($i==3) {
				$this -> misscost = $arrEquip[3][4];
				}
			}
		}
//bonusy klasowe
	if ($this -> clas == 'Wojownik') {
		$this -> defence += $this -> level;
		}
	if ($this -> clas == 'Barbarzyńca') {
		$this -> defence += $this -> level;
		}
	if ($this -> clas == 'Rzemieślnik') {
		$this -> defence -= ($this -> defence)/4;
		}
    }

/*
funkcja ustawia wartosc modyfikatora trafienia krytycznego
zmienilem wzor na sqrt(statytstyki)
*/
    function setCriticalHit($arrEquip,$arrSpell)
    {
	$this -> criticalstat = 0;
//bron biala
	if ($arrEquip[0][0]) {
		$this -> criticalstat = $this -> attack;
		}
//luk + strzaly
	else if ($arrEquip[1][0] && $arrEquip[6][0]) {
		$this -> criticalstat = $this -> shoot;
		}
//czar
	else if ($arrSpell[0][0]) {
		$this -> criticalstat = $this -> magic;
		}
	$this -> criticalhit = sqrt($this -> criticalstat) / 2;
	if ($this -> criticalhit > 50) {
		$this -> criticalhit = 50;
		}
	if ($this -> criticalhit < 0) {
		$this -> criticalhit = 0;
		}
    }


/*
funkcja ustawia wartosc modyfikatora trafienia
*/
    function setHitModificator($arrEquip,$arrSpell)
    {
	$hm = $this -> agility;
//premie sprzetu
	for ($i=3;$i<=5;$i++) {
		if ($arrEquip[$i][6]) {
			$hm -= ($arrEquip[$i][5] > -1) ? $hm * $arrEquip[$i][5] / 100 : $arrEquip[$i][5];
			}
		}

//bron biala
	if ($arrEquip[0][6]) {
		$hm += $this -> attack;
		}
//luk + strzaly
	else if ($arrEquip[1][6] && $arrEquip[6][6]) {
		$hm += $this -> shoot;
		}
//czar
	else if ($arrSpell[0][0] && $this -> mana > 0) {
		$hm += $this -> magic;
		}
//bonusy klasowe
	if ($this -> clas == 'Wojownik') {
		$hm += $this -> level;
		}
	if ($this -> clas == 'Barbarzyńca') {
		$hm += $this -> level;
		}

	$this -> hitmodificator = $hm;
    }


/*
funkcja ustawia wartosc modyfikatora uniku
*/
    function setMissModificator($arrEquip)
    {
	$mm = $this -> agility;
//premie sprzetu
	for ($i=3;$i<=5;$i++) {
		if ($arrEquip[$i][0]) {
			$mm -= ($arrEquip[$i][5] > -1) ? $mm * $arrEquip[$i][5] / 100 : $arrEquip[$i][5];
			}
		}
	if ($arrEquip[0][0]) {
		$mm += $this -> attack;
		}
	$mm += $this -> miss;
//bonusy klasowe
	if ($this -> clas == 'Wojownik') {
		$mm += $this -> level;
		}
	if ($this -> clas == 'Barbarzyńca') {
		$mm += $this -> level;
		}

	$this -> missmodificator = $mm;
    }

/*
funkcja ustawia wartosc modyfikatora trafienia
*/
    function setAttackSpeed($arrEquip)
    {
	$asm = $this -> speed;
//premie sprzetu
	for ($i=1;$i<=2;$i++) {
		if ($arrEquip[$i][0]) {
			$asm += $arrEquip[$i][7];
			}
		}

	$this -> attackspeed = $asm;
    }

/*
funkcja ustawiajaca szanse na wybuch czaru
zmienilem minimalna szanse na 3[%]
*/
    function setMisspell($arrSpell)
    {
	$this -> misspell = $arrSpell[0][1] - $this -> magic;
	if ($this -> misspell < 3) {
		$this -> misspell = 3;
		}
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
    /**
     * Function return values of equiped spells
     */
    function spells()
    {
        global $db;

        $arrSpell = array(array(0, 0),
                          array(0, 0));
        $arrSpelltype = array('B', 'O');
        $objSpell = $db -> Execute('SELECT `obr`, `poziom`, `typ` FROM `czary` WHERE `status`=\'E\' AND `gracz`='.$this -> id);
        while (!$objSpell -> EOF)
        {
            $intKey = array_search($objSpell -> fields['typ'], $arrSpelltype);
            $arrSpell[$intKey][0] = $objSpell -> fields['obr'];
            $arrSpell[$intKey][1] = $objSpell -> fields['poziom'];
            $objSpell -> MoveNext();
        }
        $objSpell -> Close();
        return $arrSpell;
    }

}
