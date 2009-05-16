<?php
/**
 *   Huta - wytapianie sztabek, przetapianie ekwipunku, wzmacnianie ekwipunku
 *
 *   @name                 : smelter.php                            
 *   @copyright            : (C) Orodlin
 *   @author               : mzah <s.paweska@gmail.com>
 *   @version              : preAlfa
 *   @since                : 26.08.2007
 *
 */

class Huta {
	var $step='';
	var $action='';

	/**
* Konstruktor
*/
	function Huta() {
		global $player;
		if ((isset($_GET['step']) && !ereg("^[a-zA-Z0-9]", $_GET['step'])) || (isset($_GET['action']) &&  !ereg("^[a-zA-Z0-9]", $_GET['action'])))
		{
			error (ERROR);
		}
		if (!isset($_GET['step'])) {
			$this->step='';
		} else {
			$this->step=$_GET['step'];
			if ($player -> hp <= 0) error(YOU_DEAD);
		}
		if (isset($_GET['action'])) {
			$this->action=$_GET['action'];
		} elseif (isset($_POST['action'])) {
			$this->action=$_POST['action'];
		} else {
			$this->action='';
		}
	}

	/**
* Sprawdza z jakiego materialu jest przedmiot
*/	
	function sztabka ($typ, $maxwt)
	{
		$sztabka='steel';
		if ($typ=='A' || $typ='W') {
			$arrWt=array(640,320,160,80);
		}
		else {
			$arrWt=array(320,160,80,40);
		}
		if ($maxwt<$arrWt[3]) {
			$sztabka='copper';
		} elseif ($maxwt<$arrWt[2]) {
			$sztabka='bronze';
		} elseif ($maxwt<$arrWt[1]) {
			$sztabka='brass';
		} elseif ($maxwt<$arrWt[0]) {
			$sztabka='iron';
		}
		return $sztabka;
	}
	/**
* Function to show poisoned equipment.
*/	
	function showpoisoned ( $intPoisonPower, $strPoisonType)
	{
		$strPoisonInfo ='';
		if ($intPoisonPower > 0)
		{
			switch ($strPoisonType)
			{
				case 'D':
					$strPoisonInfo = '( '.POISON_TYPE.' Dynallca: +'.$intPoisonPower.') ';
					break;
				case 'N':
					$strPoisonInfo = '( '.POISON_TYPE.' Nutari: +'.$intPoisonPower.') ';
					break;
				default:
					$strPoisonInfo = '( '.POISON_TYPE.' Illani: +'.$intPoisonPower.') ';
			}
		}
		return $strPoisonInfo;
	}
	/**
 * Przedmioty w plecaku (bez szat, rozdzek i tych gratow, ktore nosimy na sobie)
 */
	function backpack ($playerid)
	{
		global $smarty;
		global $db;

		if ($this -> step == 'wzmocnij') {
			$arm = $db -> Execute('SELECT * FROM `equipment` WHERE `owner`='.$playerid.' AND `type`!=\'C\' AND `type`!=\'T\' AND `status`!=\'E\' AND `wzmocnienie`!=\'Y\'');
		} else {
			$arm = $db -> Execute('SELECT * FROM `equipment` WHERE `owner`='.$playerid.' AND `type`!=\'C\' AND `type`!=\'T\' AND `status`!=\'E\'');
		}

		$arrshow = array();

		while (!$arm -> EOF)
		{
			if ($arm -> fields['zr'] < 0) {
				$arm -> fields['zr'] = str_replace('-','',$arm -> fields['zr']);
				$agility = '(+'.$arm -> fields['zr'].' '.EQUIP_AGI.')';
			} elseif ($arm -> fields['zr'] > 0) {
				$agility = '(-'.$arm -> fields['zr'].'% '.EQUIP_AGI.')';
			} elseif ($arm -> fields['zr'] == 0) {
				$agility = '';
			}
			if ($arm -> fields['szyb'] > 0 && $arm -> fields['type'] != 'A')
			{
				$speed = '(+'.$arm -> fields['szyb'].' '.EQUIP_SPEED.')';
			} else {
				$speed = '';
			}
			$strWarn = ($arm -> fields['wt'] < 11) ? '<blink>' : '';
			$strWarn1 =($arm -> fields['wt'] < 11) ? '</blink>' : '';
			$strPoisonType ='';
			$arrshow[$arm->fields['type']][] = '<input type="radio" name="action" value="'.$arm -> fields['id'].'">'.$strWarn.'<b>('.AMOUNT.': '.$arm -> fields['amount'].')</b> '.$arm -> fields['name'].' (+'.$arm -> fields['power'].')'.$this->showpoisoned($arm -> fields['poison'], $arm -> fields['ptype']).' '.$agility.$speed.' ('.$arm -> fields['wt'].'/'.$arm -> fields['maxwt'].' '.DURABILITY.')'.$strWarn1.'</input>';
			$arm -> MoveNext();
		}
		$arrTyp = array('W', 'H', 'A', 'S', 'C', 'L');
		foreach ($arrTyp AS $typ) {
			if (!empty($arrshow[$typ])) {
				switch ($typ) {
					case 'W':
						$smartyname='Bweapons';
						break;
					case 'H':
						$smartyname='Bhelmets';
						break;
					case 'A':
						$smartyname='Barmors';
						break;
					case 'S':
						$smartyname='Bshields';
						break;
					case 'L':
						$smartyname='Blegs';
						break;
				}
				$smarty -> assign ($smartyname, $arrshow[$typ]);
			}
		}
	}
	/**
 * Przetapianie ekwipunku (jeden typ {nagole/helmy/itd})       //wiekszosc wzorow w f-cji do pojedynczego przetapiania przedmiotow
 */
	function przetop_wszystkie () {
		global $smarty;
		global $db;
		global $player;

		$smarty -> assign(array(
		'Bweapons' => '',
		'Bhelmets' => '',
		'Barmors' => '',
		'Bshields' => '',
		'Blegs' => '',
		'Message' => ''));

		if (!empty($this->action) && $this->action!='') {

			$player->artisandata();
			$arm = $db -> Execute('SELECT * FROM `equipment` WHERE `type`=\''.$this->action.'\' AND status!=\'E\' AND `owner`='.$player->id);
			if (!$arm -> fields['id']) {
				error (NO_ARM);
			}

			$arrPoziom=array(1,3,5,10,15,20,25,30,40,50,60,70,80,90,100);
			$arrSztabki=array(1,4,8,20,36,60,86,120,200,300,420,560,720,900,1100);
			$arrWspSurowca = array(6, 10, 14, 17, 20);

			$wegiel=0;
			$energia=0;

			$objSmelter = $db -> Execute('SELECT `level` FROM `smelter` WHERE `owner`='.$player -> id); // sprawdzmy lvl huty (czy jest wystarczajacy do przetopienia)

			if (!isset($objSmelter -> fields['level'])) {
				$intSmelterlevel = 0;
			} else {
				$intSmelterlevel = $objSmelter -> fields['level'];
			}

			while (!$arm -> EOF)     //PHP4 ssie while=>foreach
			{
				$intKey = array_search($arm -> fields['minlev'], $arrPoziom);
				$testSztabka=$this->sztabka ($arm -> fields['type'], $arm -> fields['maxwt']);
				switch ($testSztabka) {
					case 'copper':
						$wegiel+=1;
						$hutaTest=1;
						break;
					case 'bronze':
						$wegiel+=2;
						$hutaTest=2;
						break;
					case 'brass':
						$wegiel+=3;
						$hutaTest=3;
						break;
					case 'iron':
						$wegiel+=4;
						$hutaTest=4;
						break;
					case 'steel':
						$wegiel+=5;
						$hutaTest=5;
						break;
				}
				$typeModificator = $hutaTest;
				$wegiel*=$arm->fields['amount'];

				$armEnergia=0.2 * $typeModificator * $arm -> fields['minlev'] * $arm -> fields['amount'];
				if ($arm -> fields['type'] == 'A') $armEnergia *= 2;
				$energia+=$armEnergia;

				if($hutaTest>$intSmelterlevel) { //sprawdza lvl huty
					error(HUTA_LVL);
				}
				$arm -> MoveNext();
			}
			if ($energia>$player->energy) {
				error(NO_ENERGY);
			}

			$objMineral=$db -> Execute('SELECT `coal` FROM `minerals` WHERE `owner`='.$player->id);
			if ($objMineral->fields['coal']<$wegiel) {
				error (N0_COAL);
			}

			$arrMaterial=array('copper','bronze','brass','iron','steel');
			$sumaOdzyskaneSztabki = array(0,0,0,0,0);

			$hutnictwo=0;
			$intPd = 0;
			$arm = $db -> Execute('SELECT * FROM `equipment` WHERE `type`=\''.$this->action.'\' AND status!=\'E\' AND `owner`='.$player->id);
			while (!$arm -> EOF)     //PHP4 ssie while=>foreach
			{
				$intKeySztabka = array_search($this->sztabka ($arm -> fields['type'], $arm -> fields['maxwt']), $arrMaterial);  //sprawdzmy jaki material

				$udaneProby=0;
				for ($i=0;$i<$arm->fields['amount'];$i++) {
					$szansaPrzetopienia=50+$player->hutnictwo-$arm->fields['minlev']*8*$arrWspSurowca[$intKeySztabka];
					if ($szansaPrzetopienia>95) {
						$szansaPrzetopienia=95;
					}
					$rand=rand(1,100);
					if ($szansaPrzetopienia>$rand) {
						$udaneProby++;
					}
				}

				$intKey = array_search($arm -> fields['minlev'], $arrPoziom);

				if ($arm -> fields['type']=='A') {
					$sztabki=$udaneProby*$arrSztabki[$intKey]*2;
				} else {
					$sztabki=$udaneProby*$arrSztabki[$intKey];
				}

				//$intOdzyskaneSztabki=ceil[(Hutnictwo/wspolczynnik_surowca)*10] % surowcow potrzebnych do wykonania danego przedmiotu
				$intOdzyskaneSztabki=ceil($player->hutnictwo/$arrWspSurowca[$intKey]*10);

				//(maksymalna ilosc odzywkanych surowcow to 90%)
				if ($intOdzyskaneSztabki>$sztabki*.9) {
					$intOdzyskaneSztabki=ceil($sztabki*.9);
				}
				$sumaOdzyskaneSztabki[$intKeySztabka]+=$intOdzyskaneSztabki;

			//za kazde udane przetopienie gracz dostaje:
				$intPd += ceil($arm -> fields['minlev'] * $arm -> fields['amount'] * ($intKeySztabka+1) / 2);

				$hutnictwo += $arm -> fields['amount'] / 100;
				$arm -> MoveNext();
			}

			$arrTekst = array(SZTABKA1, SZTABKA2, SZTABKA3, SZTABKA4, SZTABKA5);

			$i=0;
			$strSql = '';
			$strMessage='';
			foreach ($sumaOdzyskaneSztabki AS $OdzyskaneSztabki) {   //sklejamy sql'a do zabrania mineralow
				if ($OdzyskaneSztabki>0) {
					if ($strSql == '') {
						$strSql.=$arrMaterial[$i].'='.$arrMaterial[$i].'+'.$OdzyskaneSztabki;
					} else {
						$strSql.=', '.$arrMaterial[$i].'='.$arrMaterial[$i].'+'.$OdzyskaneSztabki;
					}
					$strMessage.=$OdzyskaneSztabki.$arrTekst[$i];
				}
				$i++;
			}

			checkexp($player -> exp, $intPd, $player -> level, $player -> race, $player -> user, $player -> id, 0, 0, $player -> id, 'hutnictwo', $hutnictwo);
			$smarty -> assign('Message', PRZETOP.$strMessage.$hutnictwo.PRZETOP2.$intPd.PRZETOP3.$energia.ENERGY);

			$db -> Execute('UPDATE `minerals` SET '.$strSql.' WHERE `owner`='.$player -> id);
			$db -> Execute('DELETE FROM `equipment` WHERE `type`=\''.$this->action.'\' AND `owner`='.$player->id);
			$db -> Execute('UPDATE `players` SET `energy`=`energy`-'.$energia.' WHERE `id`='.$player -> id);
		}
		$this->backpack ($player -> id);
	}


	/**
 * Przetapianie ekwipunku
 */
	function przetop () {
		global $smarty;
		global $db;
		global $player;
		$smarty -> assign(array(
		'Bweapons' => '',
		'Bhelmets' => '',
		'Barmors' => '',
		'Bshields' => '',
		'Blegs' => '',
		'Message' => ''));

		if (!empty($this->action) && $this->action>0) {
			$amount = (int)$_POST['amount'];
			if (!isset($amount) || $amount == '' || $amount <= 0) error(ERROR);

			$player->artisandata();

			$arm = $db -> Execute("SELECT * FROM `equipment` WHERE `id`=".(int)$this->action);
			if (!$arm -> fields['id']) {
				error(NO_ARM);
			}
			if ($arm->fields['owner']!=$player->id) {
				error(OWNER);
			}

			if ($amount > $arm -> fields['amount']) {
				$amount = $arm -> fields['amount'];
			}
			/*
			Przetopienie wymaga takze wegla do rozgrzenia pieca wg przelicznika
			- sztabka miedzi potrzebuje 1sz wegla
			- sztabka brazu potrzebuje 2sz wegla
			- sztabka mosiadzu potrzebuje 3sz wegla
			- sztabka zelaza potrzebuje 4sz wegla
			- sztabka stali potrzebuje 5sz wegla
			*/

			$testSztabka=$this->sztabka ($arm->fields['type'], $arm->fields['maxwt']);
			switch ($testSztabka) {
				case 'copper':
					$wegiel=1;
					break;
				case 'bronze':
					$wegiel=2;
					break;
				case 'brass':
					$wegiel=3;
					break;
				case 'iron':
					$wegiel=4;
					break;
				case 'steel':
					$wegiel=5;
					break;
			}
			$typeModificator = $wegiel;

			$objMineral=$db -> Execute('SELECT `coal` FROM `minerals` WHERE `owner`='.$player->id);

			if ($objMineral->fields['coal']<$wegiel*$amount) {
				error (NO_COAL);
			}

			$objSmelter = $db -> Execute('SELECT `level` FROM `smelter` WHERE `owner`='.$player -> id); // sprawdzmy lvl huty (czy jest wystarczajacy do przetopienia)

			if (!isset($objSmelter -> fields['level'])) {
				$intSmelterlevel = 0;
			} else {
				$intSmelterlevel = $objSmelter -> fields['level'];
			}

			if($typeModificator>$intSmelterlevel) {
				error(HUTA_LVL);
			}
			$wegiel*=$amount;


			//przy udanym przetopieniu hutnik bedzie odzyskiwal:
			//ceil[(Hutnictwo/wspolczynnik_surowca)*10] % surowcow potrzebnych do wykonania danego przedmiotu (maksymalna ilosc odzywkanych surowcow to 90%)
			$arrPoziom=array(1,3,5,10,15,20,25,30,40,50,60,70,80,90,100);
			$arrSztabki=array(1,4,8,20,36,60,86,120,200,300,420,560,720,900,1100);

			$intKey = array_search($arm -> fields['minlev'], $arrPoziom);

			//Energia potrzebna to przetopienia bedzie zalezec od ekwiwalentu ilosci sztabek potrzebnych na wykonanie danego przedmiotu. (naliczanie zuzycia energii analogicznie jak przy wytapianiu)   -> to zdanie z projektu ;)
			$energia = 0.2 * $typeModificator * $arm -> fields['minlev'] * $amount;
			if ($arm -> fields['type'] == 'A') $energia *= 2;
			if ($energia>$player->energy) {
				error(NO_ENERGY);
			}

			//prawd. przetopienia: 50+hutnictwo - level_przedmiotu*8*wspołczynnik_surowca
			$arrMaterial=array('copper','bronze','brass','iron','steel');
			$arrWspSurowca = array(6, 10, 14, 17, 20);
			$intKey = array_search($this->sztabka ($arm -> fields['type'], $arm -> fields['maxwt']), $arrMaterial);  //sprawdzmy jaki material

			$udaneProby=0;  //rand dla kazdego przedmiotu a nie calosci zeby nie bylo tak ze jeden rand nam spiepszy 2k sztyletow itd
			for ($i=0;$i<$amount;$i++) {
				$szansaPrzetopienia=50+$player->hutnictwo-$arm->fields['minlev']*8*$arrWspSurowca[$intKey];
				if ($szansaPrzetopienia>95) {
					$szansaPrzetopienia=95;
				}
				$rand=rand(1,100);
				if ($szansaPrzetopienia>$rand) {
					$udaneProby++;
				}
			}

			if ($arm -> fields['type']=='A') {  //za pancerze odzyskujemy x2 sztabek
				$sztabki=$udaneProby*$arrSztabki[$intKey]*2;
			} else {
				$sztabki=$udaneProby*$arrSztabki[$intKey];
			}

			//$intOdzyskaneSztabki=ceil[(Hutnictwo/wspolczynnik_surowca)*10] % surowcow potrzebnych do wykonania danego przedmiotu
			$intOdzyskaneSztabki=ceil($player->hutnictwo/$arrWspSurowca[$intKey]*10);


			//(maksymalna ilosc odzywkanych surowcow to 90%)
			if ($intOdzyskaneSztabki>$sztabki*.9) {
				$intOdzyskaneSztabki=ceil($sztabki*.9);
			}

			//za kazde udane przetopienie gracz dostaje:
			$intPd = ceil($arm -> fields['minlev'] * $amount * $typeModificator / 2);

			//hutnictwo -> nie bylo w projekcie -> umiejka i za udane i nieudane proby
			$hutnictwo = $amount / 100;

			$arrTekst = array(SZTABKA1, SZTABKA2, SZTABKA3, SZTABKA4, SZTABKA5);

			checkexp($player -> exp, $intPd, $player -> level, $player -> race, $player -> user, $player -> id, 0, 0, $player -> id, 'hutnictwo', $hutnictwo);
			$smarty -> assign('Message', PRZETOP.$intOdzyskaneSztabki.$arrTekst[$intKey].$intPd.PRZETOP2.$hutnictwo.PRZETOP3.$energia.ENERGY);

			$db -> Execute("UPDATE `minerals` SET ".$testSztabka."=".$testSztabka."+".$intOdzyskaneSztabki." WHERE `owner`=".$player -> id);
			if ($amount == $arm -> fields['amount']) {
				$db -> Execute('DELETE FROM `equipment` WHERE `id`='.$arm -> fields['id']);
			} else {
				$db -> Execute('UPDATE `equipment` SET `amount` = `amount` - '.$amount.' WHERE `id`='.$arm -> fields['id']);
			}
			$db -> Execute('UPDATE `players` SET `energy`=`energy`-'.$energia.' WHERE `id`='.$player -> id);

		}
		$this->backpack ($player -> id);
	}
	/**
 * Wzmacnianie ekwipunku
 */
	function wzmocnij () {
		global $smarty;
		global $db;
		global $player;

		if ($player -> clas != 'Rzemieślnik') {
			error(KLASA);
		}
		$player->artisandata();

		$smarty -> assign(array(
		'Bweapons' => '',
		'Bhelmets' => '',
		'Barmors' => '',
		'Bshields' => '',
		'Blegs' => '',
		'PrzedmiotId' => $this->action,
		'Message' => ''));
		if (!empty($this->action) && $this->action>0) {

			if (isset($_POST['typ'])) {
				$arm = $db -> Execute('SELECT * FROM `equipment` WHERE `id`='.(int)$this->action);
				if (empty($arm)) {
					error (NO_ARM);
				}
				if ($arm->fields['owner']!=$player->id) {
					error(OWNER);
				}
				if ($arm->fields['minlev'] > $player -> energy) {
					error(NO_ENERGY);
				}
				if ($arm->fields['wzmocnienie']=='Y') {   //dodac do equip!
					error(WZMOCNIONY);
				}
				//siła wzmocnienia:
				//Hutnictwo/20*i, gdzie i=1 dla adamantytu i krysztalu oraz i=2 dla meteorytu
				switch ($_POST['typ']) {
					case 'a':
						$mineralKosztN=3;
						$iModifier = 1;
						$mineral='adamantium';
						break;
					case 'k':
						$mineralKosztN=3;
						$iModifier = 1;
						$mineral='crystal';
						break;
					case 'm':
						$mineralKosztN=2;
						$iModifier = 2;
						$mineral='meteor';
						break;
					default:
						error(ERROR);
						break;
				}
				if ($arm->fields['type']=='A') {
					$mineralKoszt = $arm -> fields['minlev'] * $mineralKosztN * 2;
				} else {
					$mineralKoszt = $arm -> fields['minlev'] * $mineralKosztN;
				}
				$objMineral=$db -> Execute('SELECT '.$mineral.' FROM `minerals` WHERE `owner`='.$player->id);
				if ($objMineral -> fields[$mineral] < $mineralKoszt) {
					error (NO_MINERALS);
				}

				$arrMaterial=array('copper','bronze','brass','iron','steel');
				$arrModyfikator=array(2,5,8,10,15);
				$intKey = array_search($this->sztabka ($arm -> fields['type'], $arm -> fields['maxwt']), $arrMaterial);

				///prawd. wzmocnienia stopu:
				//rand(Hutnictwo/100, Hutnictwo/(2*N)) - gdzie N= 2,5,8,10,15 dla kolejno (miedz, braz, mosiadz zelazo, stal),
				//$szansa=rand($player->hutnictwo/100,$player->hutnictwo/(2*$arrModyfikator[$intKey]));
				//zmieniony na:
				$szansa=rand($player->hutnictwo/(50*$iModifier),$player->hutnictwo/(5*$arrModyfikator[$intKey]*$iModifier));

				// maksymalne prawd. to 80%
				if ($szansa>80) {
					$szansa=80;
				}
				$szansaTest=rand(0,100);
				$db -> Execute('UPDATE `minerals` SET '.$mineral.'='.$mineral.'-'.$mineralKoszt.' WHERE `owner`='.$player->id);
				$pd=0;
				if ($szansa>$szansaTest) {
					$pd=ceil($arrModyfikator[$intKey] * $arm -> fields['minlev'] / 2);
					$silaWzmocnienia = ceil($player -> hutnictwo / (20 * $iModifier));

					//maks bonusu 10*lvl
					if ($silaWzmocnienia > 10 * $arm->fields['minlev']) {
						$silaWzmocnienia = 10 * $arm->fields['minlev'];
					}
					$name = $arm -> fields['name'].' (W)';
					$statsSql = '';
					if ($arm -> fields['amount']==1) {
						if ($_POST['typ'] == 'a' || $_POST['typ'] == 'm') {
							$statsSql .= '`power`=`power`+'.$silaWzmocnienia.', ';
						}
						if ($_POST['typ'] == 'k' || $_POST['typ'] == 'm') {
							$statsSql .= '`zr`=`zr`-'.$silaWzmocnienia.', ';
						}
						$sql = "UPDATE `equipment` SET ".$statsSql."`name` = '".$name."', `wzmocnienie` = 'Y' WHERE `id` = ".((int)$this->action);
						$db -> Execute($sql);
					} else {
						$db -> Execute('UPDATE `equipment` SET `amount`=`amount`-1 WHERE `id`='.(int)$this->action);
						switch ($_POST['typ']) {
							case 'a':
								$power = $arm -> fields['power'] + $silaWzmocnienia;
								$db -> Execute('INSERT INTO `equipment` (`owner`, `name`, `power`, `type`, `cost`, `zr`, `wt`, `minlev`, `maxwt`, `amount`, `magic`, `poison`, `szyb`, `ptype`, `twohand`, `wzmocnienie`, `repair`) VALUES('.$player -> id.', \''.$name.'\', '.$power.', \''.$arm -> fields['type'].'\', '.$arm -> fields['cost'].', '.$arm -> fields['zr'].', '.$arm -> fields['wt'].', '.$arm -> fields['minlev'].', '.$arm -> fields['maxwt'].', 1, \'N\', '.$arm -> fields['poison'].','.$arm -> fields['szyb'].', \''.$arm -> fields['ptype'].'\', \''.$arm -> fields['twohand'].'\', \'Y\','.$arm -> fields['repair'].')');
								break;
							case 'k':
								$zr = $arm -> fields['zr'] - $silaWzmocnienia;
								$db -> Execute('INSERT INTO `equipment` (`owner`, `name`, `power`, `type`, `cost`, `zr`, `wt`, `minlev`, `maxwt`, `amount`, `magic`, `poison`, `szyb`, `ptype`, `twohand`, `wzmocnienie`, `repair`) VALUES('.$player -> id.', \''.$name.'\', '.$arm -> fields['power'].', \''.$arm -> fields['type'].'\', '.$arm -> fields['cost'].', '.$zr.', '.$arm -> fields['wt'].', '.$arm -> fields['minlev'].', '.$arm -> fields['maxwt'].', 1, \'N\', '.$arm -> fields['poison'].','.$arm -> fields['szyb'].', \''.$arm -> fields['ptype'].'\', \''.$arm -> fields['twohand'].'\', \'Y\','.$arm -> fields['repair'].')');
								break;
							case 'm':
								$power = $arm -> fields['power'] + $silaWzmocnienia;
								$zr = $arm -> fields['zr'] - $silaWzmocnienia;
								$db -> Execute('INSERT INTO `equipment` (`owner`, `name`, `power`, `type`, `cost`, `zr`, `wt`, `minlev`, `maxwt`, `amount`, `magic`, `poison`, `szyb`, `ptype`, `twohand`, `wzmocnienie`, `repair`) VALUES('.$player -> id.', \''.$name.'\', '.$power.', \''.$arm -> fields['type'].'\', '.$arm -> fields['cost'].', '.$zr.', '.$arm -> fields['wt'].', '.$arm -> fields['minlev'].', '.$arm -> fields['maxwt'].', 1, \'N\', '.$arm -> fields['poison'].','.$arm -> fields['szyb'].', \''.$arm -> fields['ptype'].'\', \''.$arm -> fields['twohand'].'\', \'Y\','.$arm -> fields['repair'].')');
								break;;
						}
					}

					$hutnictwo = $arm -> fields['minlev'] / 50;
					
					switch ($_POST['typ']) {
						case 'a':
							$smarty -> assign('Message', SUKCES.$silaWzmocnienia.SUKCESA.'<br/>'.YOU_GAINED.$hutnictwo.SMELTER_ABILITY.$pd.EXPERIENCE);
							break;
						case 'k':
							$smarty -> assign('Message', SUKCES.$silaWzmocnienia.SUKCESK.'<br/>'.YOU_GAINED.$hutnictwo.SMELTER_ABILITY.$pd.EXPERIENCE);
							break;
						case 'm':
							$smarty -> assign('Message', SUKCES.$silaWzmocnienia.SUKCESM.'<br/>'.YOU_GAINED.$hutnictwo.SMELTER_ABILITY.$pd.EXPERIENCE);
							break;;
					}
				} else {
					$smarty -> assign('Message', PORAZKA);
					if ($arm -> fields['amount']==1) {
						$db -> Execute('DELETE FROM `equipment` WHERE `id`='.(int)$this->action);
					} else {
						$db -> Execute('UPDATE `equipment` SET `amount`=`amount`-1 WHERE `id`='.(int)$this->action);
					}
					$hutnictwo = $arm -> fields['minlev'] / 100;
				}

				checkexp($player -> exp, $pd, $player -> level, $player -> race, $player -> user, $player -> id, 0, 0, $player -> id, 'hutnictwo', $hutnictwo);
				$db -> Execute('UPDATE `players` SET `energy`=`energy`-'.($arm -> fields['minlev']).' WHERE `id`='.$player -> id);
			}
		}
		$this -> backpack($player -> id);
	}

	/**
 * Wytapianie sztabek
 */
	function wytop () {
		global $smarty;
		global $db;
		global $player;

		$objSmelter = $db -> Execute('SELECT level FROM smelter WHERE owner='.$player -> id); // sprawdzmy lvl huty

		if (!isset($objSmelter -> fields['level'])) {
			$intSmelterlevel = 0;
			$strLevel = LEVEL0;
		} else {
			$intSmelterlevel = $objSmelter -> fields['level'];
			$strLevel='';
			switch ($objSmelter -> fields['level']) {
				case 1:
					$strLevel = LEVEL1;
					break;
				case 2:
					$strLevel = LEVEL2;
					break;
				case 3:
					$strLevel = LEVEL3;
					break;
				case 4:
					$strLevel = LEVEL4;
					break;
			}
		}
		if ($this->action=='Y') { //ulepszmy hute
			if ($objSmelter -> fields['level'] == 5) {
				error(NO_UPGRADE);
			}
			$arrCost = array(1000, 5000, 20000, 60000, 120000);
			if ($player -> credits < $arrCost[$intSmelterlevel]) {
				error(NO_MONEY);
			}
			if ($intSmelterlevel == 0) {
				$db -> Execute('INSERT INTO `smelter` (`owner`, `level`) VALUES('.$player -> id.', 1)');
			} else {
				$db -> Execute('UPDATE `smelter` SET `level`=`level`+1 WHERE `owner`='.$player -> id);
			}
			$db -> Execute('UPDATE `players` SET `credits`=`credits`-'.$arrCost[$intSmelterlevel].' WHERE `id`='.$player -> id);
			error(YOU_UPGRADE);
		} else {
			$smarty -> assign('MessageUpdate', '');
		}

		$player->artisandata();

		$arrAction = array('copper', 'bronze', 'brass', 'iron', 'steel');     //przytnijmy akcje w zaleznosci od tego jaki jest lvl huty
		$arrAction = array_slice($arrAction, 0, $objSmelter -> fields['level']);

		$arrSmelt = array(SMELT1, SMELT2, SMELT3, SMELT4, SMELT5);
		$arrSmelt = array_slice($arrSmelt, 0, $objSmelter -> fields['level']);

		$objTest = $db -> Execute('SELECT copperore, coal, tinore, zincore, ironore FROM minerals WHERE owner='.$player -> id);
		$arrTestName = array('copperore', 'coal', 'tinore', 'zincore', 'ironore');

		$i=0;
		foreach ($arrTestName AS $array) {
			$arrTest[$i]=$objTest->fields[$array];
			$i++;
		}

		$arrSmeltAmount = array(        //koszt w mineralach dla poszczegolnych sztabek
		array(2, 1, 0, 0, 0),
		array(1, 2, 1, 0, 0),
		array(2, 2, 0, 1, 0),
		array(0, 3, 0, 0, 2),
		array(0, 7, 0, 0, 3));

		$arrWspSurowca = array(6, 10, 14, 17, 20);

		//ilosc wytopionych sztabek= [10*(1/(i*4) + hutnictwo/(50+30*i))] za 1 energii
		//gdize i=1,3,5,7,9 kolejno dla miedzi, brazu, mosiadzu, zelaza i stali
		$arrWspI = array(1, 3, 5, 7, 9);

		$j = 0;
		foreach ($arrSmeltAmount AS $array) {  //ile mozna maksymalnie przeznaczyc energii na wytop
			unset($ileMax);
			$i=0;
			$energiiNaSztabke[$j] = round(1 / (10 * ( 0.25 / $arrWspI[$j] + $player -> hutnictwo / (50 + 30 * $arrWspI[$j]) ) ) , 2);
			foreach ($array AS $test) {
				if($test != 0) {
					$ile=floor( $arrTest[$i] / $test );
					if(!isset($ileMax)) {
						$ileMax=$ile;
					} elseif($ile<$ileMax) {
						$ileMax=$ile;
					}
				}
				$i++;
			}
			if (isset($ileMax) && floor($player ->  energy / $energiiNaSztabke[$j]) < $ileMax) {
				$ileMax = floor($player ->  energy / $energiiNaSztabke[$j]);
			}
			$arrIleMax[] = (!isset($ileMax)) ? 0 : $ileMax;
			$j++;
		}

		$arrIleMaxTekst = array(SMELTM1, SMELTM2, SMELTM3, SMELTM4, SMELTM5);
		for ($i=0;$i<5;$i++) {
			$arrIleMaxTekst[$i] .= $energiiNaSztabke[$i].ENERGY_PER_TRY;
		}
		$arrIleMaxTekst = array_slice($arrIleMaxTekst, 0, $objSmelter -> fields['level']);
		$arrIleMax = array_slice($arrIleMax, 0, $objSmelter -> fields['level']);


		$arrIleSurowceTekst = array(MIN1, MIN2, MIN3, MIN4, MIN5);
		$smarty -> assign(array(
		'Action'=> $this->action,
		'Ilesurowce' => $arrTest,
		'Levelinfo' => $strLevel,
		'Smelterlevel' => $intSmelterlevel,
		'Ilesurowcetekst' => $arrIleSurowceTekst,
		'Ilemaxtekst' => $arrIleMaxTekst,
		'Ilemax' => $arrIleMax,
		'Asmelt' => $arrSmelt,
		'Smeltaction' => $arrAction,
		'Message' => ''));


		if (isset($_GET['action']) && $this->action!='Y') //Wybrano jaka sztabka
		{
			if (!in_array($_GET['action'], $arrAction)) {
				error(ERROR);
			}
			if ($player -> hp < 1) {
				error(YOU_DEAD);
			}
			$intKey = array_search($_GET['action'], $arrAction);
			$arrSmeltmineral = array(SMELTME1, SMELTME2, SMELTME3, SMELTME4, SMELTME5);
			$smarty -> assign(array('Asmelt2' => A_SMELT,
			'Smeltm' => $arrSmeltmineral[$intKey],
			'Get' => $this->action,
			)
			);

			if (isset($_POST['amount'])) // Ile energii
			{
				if (!ereg('^[1-9][0-9]*$', $_POST['amount'])) {
					error(ERROR);
				}
				if ($_POST['amount'] > $arrIleMax[$intKey])
				{
					error(NO_MINERALS);
				}

				$triesAmount = (int)$_POST['amount'];
				$energiiNaSztabke = round(1 / (10 * ( 0.25 / $arrWspI[$intKey] + $player -> hutnictwo / (50 + 30 * $arrWspI[$intKey]) ) ), 2);
				$neededEnergy = $triesAmount * $energiiNaSztabke;
				if ($player->energy < $neededEnergy)
				{
					error(NO_ENERGY);
				}

				//pradopodobienstwo ze z z danej liczby mozliwych wytopionych sztabek beda udane:
				//prawd. wytopienia 1 sztabki = ceil(100+hutnictwo -8*współczynnik surowca)% [maksylanie jest 95% szansy na udany wytop]
				$udaneProby=0;
				for ($i=0;$i<$triesAmount;$i++) {
					$szansaWytopienia=floor(100+$player->hutnictwo-8*$arrWspSurowca[$intKey]);
					if ($szansaWytopienia>95) {
						$szansaWytopienia=95;
					}
					$rand=rand(1,100);
					if ($szansaWytopienia>$rand) {
						$udaneProby++;
					}
				}


				//Pozyskanie PD na wytop
				//PD= liczba wytopionych_sztabek*(i+1)
				//gdzie i=1,3,5,7,9 bo kolejne sztabki
				$pd=$udaneProby*($arrWspI[$intKey]+1);

				$i=0;
				$strSql = '';
				$arrKosztMineraly=$arrSmeltAmount[$intKey];
				foreach ($arrKosztMineraly as $KosztMineraly) {   //sklejamy sql'a do zabrania mineralow
					if ($KosztMineraly>0) {
						$strSql.=', '.$arrTestName[$i].'='.$arrTestName[$i].'-'.($KosztMineraly * $triesAmount);
					}
					$i++;
				}

				//zdobyte hutnictwo
				$hutnictwo = (float)((int)$neededEnergy) / 100;

				$arrTekst = array(SMELTME1, SMELTME2, SMELTME3, SMELTME4, SMELTME5);
				checkexp($player -> exp, $pd, $player -> level, $player -> race, $player -> user, $player -> id, 0, 0, $player -> id, 'hutnictwo', $hutnictwo);

				$db -> Execute('UPDATE `minerals` SET '.$_GET['action'].'='.$_GET['action'].'+'.$udaneProby.$strSql.' WHERE `owner`='.$player -> id);
				$db -> Execute('UPDATE `players` SET `energy`=`energy`-'.$neededEnergy.' WHERE `id`='.$player -> id);
				$smarty -> assign('Message', YOU_SMELT.' '.$udaneProby.' '.$arrTekst[$intKey].', '.$hutnictwo.YOU_SMELT2.$pd.YOU_SMELT3);
			}
		}
	}
}


$title = 'Huta';
require_once('includes/head.php');
require_once('includes/checkexp.php');

/**
* Get the localization for game
*/
require_once('languages/'.$player->lang.'/smelter.php');

if ($player -> location != 'Altara' && $player -> location != 'Ardulith') {
	error (ERROR);
}

$Huta = new huta();
switch ($Huta->step) {
	case 'wytop':
		$Huta->wytop();
		break;
	case 'przetop':
		$Huta->przetop();
		break;
	case 'wzmocnij':
		$Huta->wzmocnij();
		break;
	case 'przetop_wszystkie':
		$Huta->przetop_wszystkie();
		break;
}

/**
* Assign variables to template and display page
*/
$smarty -> assign(array(
'Step' => $Huta->step,
'Aback' => A_BACK,
'Smelt' => $Huta->action,
'Class' => $player  -> clas)
);
$smarty -> display ('smelter.tpl');

require_once('includes/foot.php');
?>
