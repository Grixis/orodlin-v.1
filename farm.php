<?php
/**
 *   File functions:
 *   Players farms - herbs
 *
 *   @name                 : farm.php                            
 *   @copyright            : (C) 2004,2005,2006 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @version              : 1.2
 *   @since                : 23.08.2006
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
// $Id: farm.php 566 2006-09-13 09:31:08Z thindil $

$title = "Farma";
require_once("includes/head.php");

/**
* Get the localization for game
*/
require_once("languages/".$player -> lang."/farm.php");

if ($player -> location != 'Altara' && $player -> location != 'Ardulith') 
{
    error(ERROR);
}

/**
 * Main menu
 */
if (!isset($_GET['step']))
{
    $smarty -> assign(array("Farminfo" => FARM_INFO,
                            "Aplantation" => A_PLANTATION,
                            "Ahouse" => A_HOUSE,
                            "Aencyclopedia" => A_ENCYCLOPEDIA));
    $_GET['step'] = '';
}

/**
 * Herbs info
 */
if (isset($_GET['step']) && $_GET['step'] == 'herbsinfo')
{
    $smarty -> assign(array("Herbsinfo" => HERBS_INFO,
                            "Ilaniinfo" => ILANI_INFO,
                            "Illaniasinfo" => ILLANIAS_INFO,
                            "Nutariinfo" => NUTARI_INFO,
                            "Dynallcainfo" => DYNALLCA_INFO));
}

/**
 * House of gardener
 */
if (isset($_GET['step']) && $_GET['step'] == 'house')
{
    $objHerbs = $db -> Execute("SELECT `illani`, `illanias`, `nutari`, `dynallca` FROM `herbs` WHERE `gracz`=".$player -> id);
    if (!$objHerbs -> fields['illani'] && !$objHerbs -> fields['illanias'] && !$objHerbs -> fields['nutari'] && !$objHerbs -> fields['dynallca'])
    {
        error(NO_HERBS);
    }
    $arrHerbs = array('illani', 'illanias', 'nutari', 'dynallca');
    $arrHerbsname = array(HERB1, HERB2, HERB3, HERB4);
    $arrHerbsaviable = array();
    $arrHerbsoption = array();
    $arrHerbsamount = array();
    $j = 0;
    for ($i = 0; $i < 4; $i++)
    {
        $strName = $arrHerbs[$i];
        if ($objHerbs -> fields[$strName])
        {
            $arrHerbsaviable[$j] = $arrHerbsname[$i];
            $arrHerbsamount[$j] = $objHerbs -> fields[$strName];
            $arrHerbsoption[$j] = $strName;
            $j++;
        }
    }
    if (!isset($_GET['action']))
    {
        $_GET['action'] = '';
    }

    /**
     * Dryings herbs
     */
    if (isset($_GET['action']) && $_GET['action'] == 'dry')
    {
        if (!ereg("^[1-9][0-9]*$", $_POST['amount'])) 
        {
            error(ERROR);
        }
        if (!in_array($_POST['herb'], $arrHerbs))
        {
            error(ERROR);
        }
        $intAmountherbs = 5 * $_POST['amount'];
        if ($intAmountherbs > $objHerbs -> fields[$_POST['herb']])
        {
            error(NO_HERB);
        }
        $intAmountenergy = $_POST['amount'] / 5;
        if ($intAmountenergy > $player -> energy)
        {
            error(NO_ENERGY);
        }
        if ($player -> hp < 1)
        {
            error(YOU_DEAD);
        }
        $intKey = array_search($_POST['herb'], $arrHerbs);
        $intAmountseeds = 0;
        for ($i = 0; $i < $_POST['amount']; $i++)
        {
            $intRoll = rand(1, 100);
            if ($intRoll > 5)
            {
                $intAmountseeds++;
            }
        }
        $intHerbalist = $intAmountseeds / 100;
        $arrSeeds = array('illani_seeds', 'illanias_seeds', 'nutari_seeds', 'dynallca_seeds');
        $db -> Execute("UPDATE `herbs` SET `".$arrHerbs[$intKey]."`=`".$arrHerbs[$intKey]."`-".$intAmountherbs.", `".$arrSeeds[$intKey]."`=`".$arrSeeds[$intKey]."`+".$intAmountseeds." WHERE `gracz`=".$player -> id);
        $db -> Execute("UPDATE `players` SET `energy`=`energy`-".$intAmountenergy.", herbalist=herbalist+".$intHerbalist." WHERE `id`=".$player -> id);
        $smarty -> assign("Message", YOU_MAKE.$intAmountherbs.T_HERB.$intAmountseeds.T_PACKS.$intHerbalist.T_ABILITY);
    }
    $smarty -> assign(array("Houseinfo" => HOUSE_INFO,
                            "Adry" => A_DRY,
                            "Tdry" => T_DRY,
                            "Tpack" => T_PACK,
                            "Tamount" => T_AMOUNT,
                            "Herbsname" => $arrHerbsaviable,
                            "Herbsamount" => $arrHerbsamount,
                            "Herbsoption" => $arrHerbsoption,
                            "Action" => $_GET['action']));
    $objHerbs -> Close();
}

/**
 * Plantation
 */
if (isset($_GET['step']) && $_GET['step'] == 'plantation')
{
    $objPlantation = $db -> Execute("SELECT id, owner, lands, glasshouse, irrigation, creeper FROM farms WHERE owner=".$player -> id." AND place=".$db->Quote($player -> location));
    $intMithFarmCost = 15;
    if (!isset($_GET['action']))
    {
        if (!$objPlantation -> fields['lands'])
        {
            $smarty -> assign("Aupgrade", NO_PLANT1.CITY.NO_PLANT2.$intMithFarmCost.NO_PLANT3);
            $intLandsamount = '';
        }
            else
        {
            $smarty -> assign(array("Aupgrade" => A_UPGRADE,
                                    "Asow" => A_SOW,
                                    "Achop" => A_CHOP));
            $intLandsamount = $objPlantation -> fields['lands'];
        }
        $smarty -> assign("Farminfo", FARM_INFO1.CITY.FARM_INFO2);
        $_GET['action'] = '';
    }

    /**
     * Upgrade plantation
     */
    if (isset($_GET['action']) && $_GET['action'] == 'upgrade')
    {
        if (!$objPlantation -> fields['lands'])
        {
	        $intMithcost = $intMithFarmCost;
            $intLandsamount = '';
            $intGlassAmount = '';
            $intIrrigationAmount = '';
            $intCreeperAmount = '';
        }
            else
        {
	        $smarty->assign(array('noMithril' => ''));
	        
			$intLandsamount = $objPlantation -> fields['lands'];
			$intMithcost = $intMithFarmCost * $objPlantation -> fields['lands'];
			$fltDelta = $intMithcost*$intMithcost + ($intMithFarmCost / 4 + 2 * $player -> platinum - $intMithcost) * $intMithFarmCost; 
			$intMaxLand = floor(($intMithFarmCost / 2 - $intMithcost + sqrt($fltDelta)) /  $intMithFarmCost);
			$arrFarmLevels = array();
			$intMithSum = 0;
			for ($i=1; $i<=$intMaxLand; $i++)
	        {
		        $intMithSum += ($i + $intLandsamount - 1) * $intMithFarmCost;
		        $arrFarmLevels[$i] = ($i + $intLandsamount).': '.$intMithSum.T_MITH;
	        }
	        if ($intMaxLand < 1) $smarty->assign('noMithril', NO_MITH);
			$intGlassAmount = $objPlantation -> fields['glasshouse'];
			$intIrrigationAmount = $objPlantation -> fields['irrigation'];
			$intCreeperAmount = $objPlantation -> fields['creeper'];
			$intPine = $db -> getOne("SELECT `pine` FROM `minerals` WHERE owner=".$player -> id);
			$intMaxGold = floor($player -> credits / 1000);
			$intMaxPine = floor($intPine / 10);
			$intMaxGold = ($intMaxGold > $intMaxPine) ? $intMaxPine : $intMaxGold;
			if ($intGlassAmount != $intLandsamount)
			{
				$intMaxLevel = ($intMaxGold > $intLandsamount - $intGlassAmount) ? $intLandsamount - $intGlassAmount : $intMaxGold;
				for ($i=1;$i<=$intMaxLevel;$i++)
				{
					$arrGlassLevels[$i] = ($i + $intGlassAmount).': '.(1000 * $i).T_GOLDCOINS.(10*$i).PINE_PIECES;
				}
				$smarty->assign('noGlass', 0);
			}
			else $smarty->assign('noGlass', 1);
			if ($intIrrigationAmount != $intLandsamount)
			{
				$intMaxLevel = ($intMaxGold > $intLandsamount - $intIrrigationAmount) ? $intLandsamount - $intIrrigationAmount : $intMaxGold;
				for ($i=1;$i<=$intMaxLevel;$i++)
				{
					$arrIrrigationLevels[$i] = ($i + $intIrrigationAmount).': '.(1000 * $i).T_GOLDCOINS.(10*$i).PINE_PIECES;
				}
				$smarty->assign('noIrrigation', 0);
			}
			else $smarty->assign('noIrrigation', 1);
			if ($intCreeperAmount != $intLandsamount)
			{
				$intMaxLevel = ($intMaxGold > $intLandsamount - $intCreeperAmount) ? $intLandsamount - $intCreeperAmount : $intMaxGold;
				for ($i=1;$i<=$intMaxLevel;$i++)
				{
					$arrCreeperLevels[$i] = ($i + $intCreeperAmount).': '.(1000 * $i).T_GOLDCOINS.(10*$i).PINE_PIECES;
				}
				$smarty->assign('noCreeper', 0);
			}
			else $smarty->assign('noCreeper', 1);
			$smarty->assign(array('noCreeperSpace' => 'Brak miejsca na kolejne konstrukcje na pnącza',
								  'noIrrigationSpace' => 'Brak miejsca na kolejne systemy nawadniające',
								  'noGlassSpace' => 'Brak miejsca na kolejne szklarnie'));
            $smarty->assign_by_ref('FarmLevels', $arrFarmLevels);
            $smarty->assign_by_ref('GlassLevels', $arrGlassLevels);
            $smarty->assign_by_ref('IrrigationLevels', $arrIrrigationLevels);
            $smarty->assign_by_ref('CreeperLevels', $arrCreeperLevels);
            $smarty -> assign(array("Buyglass" => BUY_GLASS,
                                    "Buyirrigation" => BUY_IRRIGATION,
                                    "Buycreeper" => BUY_CREEPER));
        }
        $smarty -> assign(array("Tmith" => T_MITH,
                                "Tgoldcoins" => T_GOLDCOINS,
                                "Upgradeinfo" => UPGRADE_INFO,
                                "Buyland" => BUY_LAND,
                                "Field" => FIELD,
                                "Glass" => GLASS,
                                "Irrigation" => IRRIGATION,
                                "Creeper" => CREEPER,
                                "Buylandcost" => $intMithcost,
                                "Message" => ''));

        /**
         * Buy land, glasshouse, irrigation etc
         */
        if (isset($_GET['buy']))
        {
            $arrBuy = array('L', 'G', 'I', 'C');
            if (!in_array($_GET['buy'], $arrBuy))
            {
                error(ERROR);
            }
            $intAmount = 1;
            if (isset($_POST['amount'])) $intAmount = (int) $_POST['amount'];
            $intField = $intLandsamount;
            $intGlass = $intGlassAmount;
            $intIrrigation = $intIrrigationAmount;
            $intCreeper = $intCreeperAmount;
            if ($_GET['buy'] == 'L')
            {
	            (isset($_POST['amount'])) ? $intMithAmount = (2 * $intLandsamount + $intAmount - 1 ) * $intMithFarmCost * $intAmount / 2 : $intMithAmount = $intMithFarmCost;
                if ($player -> platinum < $intMithAmount)
                {
                    error(NO_MITH);
                }
                if (!$objPlantation -> fields['lands'])
                {
                    $db -> Execute("INSERT INTO farms(owner, lands, place) VALUES(".$player -> id.", 1, '".$player -> location."')");
                }
                    else
                {
                    $db -> Execute("UPDATE farms SET lands=lands+".$intAmount." WHERE id=".$objPlantation -> fields['id']);
                }
               	$db -> Execute("UPDATE players SET platinum=platinum-".$intMithAmount." WHERE id=".$player -> id);
               	($intAmount > 1) ? $strBuyitem = BUYING_LANDS : $strBuyitem = BUYING_LAND;
               	$intField += $intAmount;
				$strMith = $intMithAmount.T_MITH.".<br>";
				$strOther = '';
            }
            else
            {
				$arrItems = array('glasshouse', 'irrigation', 'creeper');
				$intKey = array_search($_GET['buy'], $arrBuy) - 1;
				if (($player -> credits < $intAmount * 1000) || ($intPine < $intAmount * 10)) error(NO_MONEY);
                if ($objPlantation -> fields['lands'] == $objPlantation -> fields[$arrItems[$intKey]]) error(NO_LANDS);
                $db -> Execute("UPDATE farms SET ".$arrItems[$intKey]."=".$arrItems[$intKey]."+".$intAmount." WHERE owner=".$player -> id." AND place=".$db->Quote($player -> location)) or die($db -> ErrorMsg());
                $db -> Execute("UPDATE players SET credits=credits-".($intAmount * 1000)." WHERE id=".$player -> id);
                $db -> Execute("UPDATE minerals SET pine=pine-".($intAmount * 10)." WHERE owner=".$player -> id);
                switch ($_GET['buy']) 
                {
    				case 'G':
        				$intGlass +=  $_POST['amount'];
        				break;
    				case 'I':
        				$intIrrigation +=  $_POST['amount'];
        				break;
    				case 'C':
        				$intCreeper +=  $_POST['amount'];
        				break;
				}
                $arrText = array(BUYING_GLASS, BUYING_IRRIGATION, BUYING_CREEPER);
                $strBuyitem = $arrText[$intKey];
                $strOther = ($intAmount * 1000).T_GOLDCOINS.($intAmount * 10).PINE_PIECES.".<br>";
                $strMith = '';
            }
            $smarty -> assign("Message", YOU_BUY.$strBuyitem."<br>".YOU_PAID.": ".$strMith.$strOther.A_LANDS.$intField."<br>".A_GLASS.$intGlass."<br>".A_IRRIGATION.$intIrrigation."<br>".A_CREEPER.$intCreeper."<br><a href=\"farm.php?step=plantation&action=upgrade\">".A_REFRESH."</a><br><br>");
        }
    }

    /**
     * Sow herbs
     */
    if (isset($_GET['action']) && $_GET['action'] == 'sow')
    {
        if (!$objPlantation -> fields['lands'])
        {
            error(NO_FARM);
        }
        $objUsedlands = $db -> Execute("SELECT `amount` FROM `farm` WHERE `owner`=".$player -> id." AND place=".$db->Quote($player -> location));
        $intUsedlands = 0;
        while (!$objUsedlands -> EOF)
        {
            $intUsedlands = $intUsedlands + $objUsedlands -> fields['amount'];
            $objUsedlands -> MoveNext();
        }
        $objUsedlands -> Close();
        if ($objPlantation -> fields['lands'] == $intUsedlands)
        {
            error(NO_LAND);
        }
        $objSeeds = $db -> Execute("SELECT illani_seeds, illanias_seeds, nutari_seeds, dynallca_seeds FROM herbs WHERE gracz=".$player -> id);
        if (!$objSeeds -> fields['illani_seeds'] && !$objSeeds -> fields['illanias_seeds'] && !$objSeeds -> fields['nutari_seeds'] && !$objSeeds -> fields['dynallca_seeds'])
        {
            error(NO_SEEDS);
        }
        $arrSeeds = array('illani_seeds', 'illanias_seeds', 'nutari_seeds', 'dynallca_seeds');
        $arrSeedsname = array(HERB1, HERB2, HERB3, HERB4);
        $arrSeedsaviable = array();
        $arrSeedsoption = array();
        $arrSeedsamount = array();
        $j = 0;
        for ($i = 0; $i < 4; $i++)
        {
            $strName = $arrSeeds[$i];
            if ($objSeeds -> fields[$strName])
            {
                $arrSeedsaviable[$j] = $arrSeedsname[$i];
                $arrSeedsamount[$j] = $objSeeds -> fields[$strName];
                $arrSeedsoption[$j] = $strName;
                $j++;
            }
        }
        $intLandsamount = $objPlantation -> fields['lands'];
        $intFreelands = $intLandsamount - $intUsedlands;
        $smarty -> assign(array("Sawinfo" => SAW_INFO,
        						"FarmInfo" => FARMINFO.CITY,
                                "Ilands" => I_LANDS,
                                "Iglass" => I_GLASS,
                                "Iirrigation" => I_IRRIGATION,
                                "Icreeper" => I_CREEPER,
                                "Asaw" => A_SAW,
                                "Tlands" => T_LANDS.CITY,
                                "Tamount" => T_AMOUNT,
                                "Ifreelands" => FREE_LANDS,
                                "Freelands" => $intFreelands,
                                "Seedsname" => $arrSeedsaviable,
                                "Seedsamount" => $arrSeedsamount,
                                "Seedsoption" => $arrSeedsoption,
                                "Glasshouse" => $objPlantation -> fields['glasshouse'],
                                "Irrigation" => $objPlantation -> fields['irrigation'],
                                "Creeper" => $objPlantation -> fields['creeper'],
                                "Refresh" => '',
                                "Message" => ''));

        /**
         * Start sow herbs
         */
        if (isset($_GET['step2']) && $_GET['step2'] == 'next')
        {
            if (!in_array($_POST['seeds'], $arrSeeds))
            {
                error(ERROR);
            }
            if (!ereg("^[1-9][0-9]*$", $_POST['amount'])) 
            {
                error(ERROR);
            }
            if ($_POST['amount'] > $intFreelands)
            {
                error(NO_FREE);
            }
            if ($player -> hp < 1)
            {
                error(YOU_DEAD);
            }
            $intEnergy = $_POST['amount'] / 5 ; // also for ability increase
            if ($intEnergy > $player -> energy)
            {
                error(NO_ENERGY);
            }
            $intKey = array_search($_POST['seeds'], $arrSeeds);
            if ($_POST['amount'] > $objSeeds -> fields[$arrSeeds[$intKey]])
            {
                error(NO_SEED);
            }
            $arrHerbsname = array('illani', 'illanias', 'nutari', 'dynallca');
            if ($intKey)
            {
                $arrItems = array(0, $objPlantation -> fields['glasshouse'], $objPlantation -> fields['irrigation'], $objPlantation -> fields['creeper']);
                $objFarm = $db -> Execute("SELECT amount, name FROM farm WHERE owner=".$player -> id." AND place=".$db->Quote($player -> location));
                $arrNeeditems = array(0, 0, 0, 0);
                while (!$objFarm -> EOF)
                {
                    $intKey2 = array_search($objFarm -> fields['name'], $arrHerbsname);
                    if ($intKey2 == 1)
                    {
                        $arrNeeditems[1] = $arrNeeditems[1] + $objFarm -> fields['amount'];
                    }
                        elseif ($intKey2 == 2)
                    {
                        $arrNeeditems[1] = $arrNeeditems[1] + $objFarm -> fields['amount'];
                        $arrNeeditems[2] = $arrNeeditems[2] + $objFarm -> fields['amount'];
                    }
                        elseif ($intKey2 == 3)
                    {
                        $arrNeeditems[1] = $arrNeeditems[1] + $objFarm -> fields['amount'];
                        $arrNeeditems[2] = $arrNeeditems[2] + $objFarm -> fields['amount'];
                        $arrNeeditems[3] = $arrNeeditems[3] + $objFarm -> fields['amount'];
                    }
                    $objFarm -> MoveNext();
                }
                $objFarm -> Close();
                for ($i = 1; $i <= $intKey; $i++)
                {
                    $arrNeeditems[$i] = $_POST['amount'] + $arrNeeditems[$i];
                    if (!$arrItems[$i] || $arrItems[$i] < $arrNeeditems[$i])
                    {
                        error(NO_ITEMS);
                    }
                }
            }
            $db -> Execute("UPDATE herbs SET ".$arrSeeds[$intKey]."=".$arrSeeds[$intKey]."-".$_POST['amount']." WHERE gracz=".$player -> id);
            $farmId = $db -> GetOne("SELECT id FROM farm WHERE owner=".$player -> id." AND place='".$player -> location."' AND age=0 AND name=".$db->Quote($arrHerbsname[$intKey]));
            if ($farmId)
            {
	            $db -> Execute("UPDATE farm SET amount=amount+".$_POST['amount']." WHERE id=".$farmId);
            }
            else
            {
            	$db -> Execute("INSERT INTO farm (owner, amount, name, age, place) VALUES(".$player -> id.", ".$_POST['amount'].", '".$arrHerbsname[$intKey]."', 0, '$player->location')");
        	}
            $db -> Execute("UPDATE players SET energy=energy-".$intEnergy.", herbalist=herbalist+".$intEnergy." WHERE id=".$player -> id);
            $smarty -> assign("Refresh", A_REFRESH);
            $smarty -> assign("Message", YOU_SAW.$_POST['amount'].T_LANDS2.$arrHerbsname[$intKey].YOU_GAIN.$intEnergy.T_ABILITY);
        }
    }

    /**
     * Gather herbs
     */
    if (isset($_GET['action']) && $_GET['action'] == 'chop')
    {
        if (!$objPlantation -> fields['lands'])
        {
            error(NO_FARM);
        }
        $objHerbs = $db -> Execute("SELECT id, amount, name, age FROM farm WHERE owner=".$player -> id." AND place=".$db->Quote($player -> location));
        if (!$objHerbs -> fields['id'])
        {
            error(NO_HERBS);
        }
        $intLandsamount = $objPlantation -> fields['lands'];
        $arrHerbsname = array();
        $arrHerbsid = array();
        $arrHerbsamount = array();
        $arrHerbsage = array();
        $i = 0;
        while (!$objHerbs -> EOF)
        {
            $arrHerbsname[$i] = $objHerbs -> fields['name'];
            $arrHerbsid[$i] = $objHerbs -> fields['id'];
            $arrHerbsamount[$i] = $objHerbs -> fields['amount'];
            $arrHerbsage[$i] = $objHerbs -> fields['age'];
            $i++;
            $objHerbs -> MoveNext();
        }
        $objHerbs -> Close();
        $smarty -> assign(array("Chopinfo" => CHOP_INFO,
                                "Tamount" => T_AMOUNT,
                                "Tage" => T_AGE,
                                "FarmInfo" => FARMINFO.CITY,
                                "Message" => '',
                                "Herbsname" => $arrHerbsname,
                                "Herbsid" => $arrHerbsid,
                                "Herbsamount" => $arrHerbsamount,
                                "Herbsage" => $arrHerbsage,
                                "Herbid" => 0));
        /**
         * Start gather
         */
        if (isset($_GET['id']))
        {
            if (!ereg("^[1-9][0-9]*$", $_GET['id'])) 
            {
                error(ERROR);
            }
            $objHerb = $db -> Execute("SELECT `owner`, `amount`, `name`, `age` FROM `farm` WHERE `id`=".$_GET['id']);
            if ($objHerb -> fields['owner'] != $player -> id)
            {
                error(NOT_YOUR);
            }
            $smarty -> assign(array("Herbid" => $_GET['id'],
                                    "Herbname" => $objHerb -> fields['name'],
                                    "Agather" => A_GATHER,
                                    "Froma" => FROM_A,
                                    "Tlands3" => T_LANDS3));
            /**
             * Gather some herbs
             */
            if (isset($_GET['step2']) && $_GET['step2'] == 'next')
            {
                if (!ereg("^[1-9][0-9]*$", $_POST['amount'])) 
                {
                    error(ERROR);
                }
                if (!$objHerb -> fields['age'])
                {
                    error(TOO_YOUNG);
                }
                if ($objHerb -> fields['amount'] < $_POST['amount'])
                {
                    error(TO_MUCH);
                }
                if ($player -> hp < 1)
                {
                    error(YOU_DEAD);
                }
                $intEnergy = $_POST['amount'] / 5;
                if ($intEnergy > $player -> energy)
                {
                    error(NO_ENERGY);
                }
                $arrAge = array(0, 1, 1, 2, 2, 3, 3, 4, 4, 5, 5, 6, 6, 7, 7, 8, 8, 9, 10, 10, 10, 9, 8, 8, 7, 7, 6, 6, 5, 5, 4, 4, 3, 3, 2, 2, 1, 1);
                $arrHerbname = array('illani', 'illanias', 'nutari', 'dynallca');
                $arrHerbmodif = array("Altara" => array(1, 3.5, 2, 6.5), "Ardulith" => array(3, 1.5, 6, 2.5));
                $intKey = array_search($objHerb -> fields['name'], $arrHerbname);
                $intRoll = rand(-15, 15) / 100;
                $intKey2 = $objHerb -> fields['age'] - 1;
				if ($objHerb -> fields['age'] > 3)
				{
				    $intAbility = $intEnergy;
				}
				    else
				{
				   $intAbility = 0;
				}

                /**
                 * Add bonuses to ability
                 */
                require_once('includes/abilitybonus.php');
                $player -> herbalist = abilitybonus('herbalist');
				$fltSum = $player -> herbalist + ($_POST['amount'] - 1) * 0.1; 
				$intAmount = floor(($arrAge[$intKey2] * $_POST['amount'] / $arrHerbmodif[$player -> location][$intKey]) * (1 +  $fltSum / 400));
                $intAmount = floor($intAmount + ($intAmount * $intRoll));
                if ($intAmount < 0)
                {
                    $intAmount = 0;
                }
                if ($_POST['amount'] < $objHerb -> fields['amount'])
                {
                    $db -> Execute("UPDATE `farm` SET `amount`=`amount`-".$_POST['amount']." WHERE `id`=".$_GET['id']);
                }
                    else
                {
                    $db -> Execute("DELETE FROM `farm` WHERE `id`=".$_GET['id']);
                }
                $db -> Execute("UPDATE `herbs` SET `".$arrHerbname[$intKey]."`=`".$arrHerbname[$intKey]."`+".$intAmount." WHERE `gracz`=".$player -> id);
                $db -> Execute("UPDATE `players` SET `energy`=`energy`-".$intEnergy.", `herbalist`=`herbalist`+".$intAbility." WHERE `id`=".$player -> id);
                $smarty -> assign("Message", YOU_GATHER.$intAmount.T_AMOUNT2.$arrHerbname[$intKey].T_FARM.$intAbility.T_ABILITY);
            }
            $objHerb -> Close();
        }
    }

    $smarty -> assign(array("Action" => $_GET['action'],
                            "Lands" => $intLandsamount));
    if ($objPlantation -> fields['id'])
    {
        $objPlantation -> Close();
    }
}

/**
* Assign variable to template and display page
*/
$smarty -> assign(array("Step" => $_GET['step'],
                        "Aback" => A_BACK));
$smarty -> display ('farm.tpl');

require_once("includes/foot.php");

?>
