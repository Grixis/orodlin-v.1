<?php
/**
 *   File functions:
 *   General functions related to craftmanship - smithy, fletchery, alchemy and jewellery production.
 *
 *   @name                 : artisan.php
 *   @copyright            : (C) 2007 Orodlin Team based on Vallheru Engine 1.3
 *   @author               : eyescream <tduda@users.sourceforge.net>
 *   @version              : 1.4
 *   @since                : 22.03.2007
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
* Get the localization for game
*/
require_once('languages/'.$player -> lang.'/artisan.php');

/**
* Show item plans in shop.
* $strTable - name of database array (mill, smith, jeweller, alchemy_mill)
* $intOwner - use 0 to get from shop, else display player's plans
* $strLang - player & item language. For jewellers used as player's class!!!
* $charType - item type, used in "smith"

Fields common in all arrays: id owner name cost level lang
mill i smith:       type    amount  twohand
jeweller:           type    bonus
alchemy_mill:       illani  illanias    nutari  status  dynallca
*/
function showplans ($strTable, $intOwner=0, $strLang = 'pl', $charType ='')
{
    global $db;
    global $smarty;
    switch ($strTable)
    {
        case 'mill':
        case 'smith':
            $objPlans = ($charType !='' && $strTable == 'smith') ? $db -> Execute('SELECT `id`, `name`, `cost`, `level`, `amount` FROM `'.$strTable.'` WHERE `owner`='.$intOwner.' AND `lang`=\''.$strLang.'\' AND `type`=\''.$charType.'\' ORDER BY `level` ASC') : $db -> Execute('SELECT `id`, `name`, `cost`, `level`, `amount` FROM `'.$strTable.'` WHERE `owner`='.$intOwner.' AND `lang`=\''.$strLang.'\' ORDER BY `level` ASC');
            break;
        case 'alchemy_mill':
            $objPlans = $db -> Execute('SELECT `id`, `name`, `cost`, `level` FROM `alchemy_mill` WHERE `status`=\'S\' AND `owner`='.$intOwner.' AND `lang`=\''.$strLang.'\' ORDER BY `cost` ASC');
            break;
        case 'jeweller':
            $objPlans = $db -> Execute('SELECT `id`, `name`, `cost`, `level` FROM `jeweller` WHERE '.($strLang!=ARTISAN ? '`id`=1' : '`owner`='.$intOwner));
            break;
        default:
            error (ERROR);
    }
    $arrname = array();
    $arrcost = array();
    $arrlevel = array();
    $arrid = array();
    $i = 0;
    while (!$objPlans -> EOF)
    {
        $arrname[$i] = $objPlans -> fields['name'];
        $arrcost[$i] = ($intOwner > 0 ? $objPlans -> fields['amount'] : $objPlans -> fields['cost']);
        $arrlevel[$i] = $objPlans -> fields['level'];
        $arrid[$i] = $objPlans -> fields['id'];
        $objPlans -> MoveNext();
        ++$i;
    }
    $objPlans -> Close();
    $strResourceName = I_COST;  // what name must be displayed. Buy plans - money. Use plans - wood, herb, etc.
    $arrResNames = array('mill' => I_LUMBER, 'smith' => I_BARS);
    if ($intOwner > 0)
    {
        $strResourceName = $arrResNames[$strTable];
    }
    $smarty -> assign(array('Name' => $arrname,
                            'Cost' => $arrcost,
                            'Level' => $arrlevel,
                            'Planid' => $arrid,
                            'Iname' => I_NAME,
                            'Icost' => $strResourceName,
                            'Ilevel' => I_LEVEL,
                            'Ioption' => I_OPTION,
                            'Hereis' => ($intOwner > 0) ? CREATION_INFO : HERE_IS,
                            'Abuy' => A_BUY));
}

function showunfinished ($strTable, $intOwner)
{
    global $db;
    global $smarty;
    switch ($strTable)
    {
        case 'mill_work':
        case 'smith_work':
            $objMaked = $db -> Execute('SELECT `id`, `name`, `n_energy`, `u_energy` FROM '.$strTable.' WHERE `owner`='.$intOwner);
            break;
        //case 'alchemy_mill':

           // break;
        //case 'jeweller':

            //break;
        default:
            error (ERROR);
    }
    if ($objMaked -> fields['id'])
    {
        $smarty -> assign(array('ContinueId' => $objMaked -> fields['id'],
                                'ContinueName' => $objMaked -> fields['name'],
                                'ContinuePercent' => round(($objMaked -> fields['u_energy'] / $objMaked -> fields['n_energy']) * 100, 0),
                                'ContinueNeed' => $objMaked -> fields['n_energy'] - $objMaked -> fields['u_energy'],
                                'Info2' => INFO2,
                                'Ipercent' => I_PERCENT,
                                'Ienergy' => I_ENERGY));
    }
}


// Player class - because non-artisans can't buy all jeweller plans
function buyplan ($strTable, $intId, $intPlayerId, $intPlayerCash, $strPlayerClass = '')
{
    global $db;
    global $smarty;

    if (!ereg("^[1-9][0-9]*$", $intId) || ($strTable != 'mill' && $strTable != 'smith' && $strTable != 'jeweller' && $strTable != 'alchemy_mill'))
    {
        error (ERROR);
    }
    if ($strTable == 'jeweller' && $intId > 1 && $strPlayerClass != ARTISAN)
    {
        error(WRONG_CLASS);
    }
    $objPlan = $db -> Execute('SELECT * FROM `'.$strTable.'` WHERE `id`='.$intId);
    if (!$objPlan -> fields['id'])
    {
        error (NO_PLAN);
    }
    if ($objPlan -> fields['owner'])
    {
        error (NOT_FOR_SALE);
    }
    if ($objPlan -> fields['cost'] > $intPlayerCash)
    {
        error (NO_MONEY);
    }
    $objTest = $db -> Execute('SELECT `id` FROM `'.$strTable.'` WHERE `owner`='.$intPlayerId.' AND name=\''.$objPlan -> fields['name'].'\'');
    if ($objTest -> fields['id'])
    {
        error (YOU_HAVE);
    }
    if ($strTable == 'alchemy_mill' && $objPlan -> fields['status'] != 'S')
    {
        error (BAD_TYPE);
    }
    $objTest -> Close();
    switch ($strTable)
    {
        case 'mill':
        case 'smith':
            $db -> Execute('INSERT INTO `'.$strTable.'` (`owner`, `name`, `type`, `cost`, `amount`, `level`, `lang`) VALUES('.$intPlayerId.', \''.$objPlan -> fields['name'].'\', \''.$objPlan -> fields['type'].'\', '.$objPlan -> fields['cost'].', '.$objPlan -> fields['amount'].', '.$objPlan -> fields['level'].', \''.$objPlan -> fields['lang'].'\')');
            break;
        case 'alchemy_mill':
            $db -> Execute('INSERT INTO `alchemy_mill` (`owner`, `name`, `cost`, `status`, `level`, `illani`, `illanias`, `nutari`, `dynallca`) VALUES('.$intPlayerId.',\''.$objPlan -> fields['name'].'\','.$objPlan -> fields['cost'].',\'N\','.$objPlan -> fields['level'].','.$objPlan -> fields['illani'].','.$objPlan -> fields['illanias'].','.$objPlan -> fields['nutari'].','.$objPlan -> fields['dynallca'].')');
            break;
        case 'jeweller':
            $db -> Execute('INSERT INTO `jeweller` (`owner`, `name`, `type`, `cost`, `level`, `bonus`) VALUES('.$intPlayerId.', \''.$objPlan -> fields['name'].'\', \''.$objPlan -> fields['type'].'\', '.$objPlan -> fields['cost'].', '.$objPlan -> fields['level'].', '.$objPlan -> fields['bonus'].')');
            break;
    }
    $db -> Execute('UPDATE `players` SET `credits`=`credits`-'.$objPlan -> fields['cost'].' WHERE `id`='.$intPlayerId);
    $objPlan -> Close();
    $smarty -> assign(array('Cost1' => $objPlan -> fields['cost'],
                            'Name1' => $objPlan -> fields['name'],
                            'Youpay' => YOU_PAY,
                            'Andbuy' => AND_BUY));
}

/**
* Function to show complete plans and craft astral items (for blacksmiths and flechers).
* $strSkill - to gain ability
* $planId - which item will be crafted (if any; If not present - only display)
*/
function makeastral1($strSkill ='', $planId = 0)
{
    global $player;
    $arrResources = array(ADAMANTIUM, CRYSTAL, METEOR, PINE, HAZEL, YEW, ELM, COPPER, BRONZE, BRASS, IRON, STEEL, COAL, MITHRIL, ENERGY_PTS);
    $arrAmount = array(array(2500, 1250, 250, 6000, 4000, 1500, 1000, 3000, 2000, 1000, 750, 500, 10000, 4000, 50),
                       array(4000, 2000, 300, 8000, 5500, 2500, 1500, 5000, 3000, 2000, 1000, 750, 15000, 5000, 75),
                       array(6500, 2500, 400, 12000, 7000, 4000, 2000, 7000, 4000, 3000, 1500, 1000, 20000, 6000, 100),
                       array(8000, 4000, 500, 17000, 8500, 5500, 2500, 9000, 5000, 4000, 2000, 1250, 25000, 7000, 125),
                       array(10000, 5000, 600, 20000, 10000, 7000, 3000, 11000, 6000, 5000, 2500, 1500, 30000, 8000, 150));
    $arrNames = array(CONST1, CONST2, CONST3, CONST4, CONST5);

    if(!ereg("^[1-5]*$", $planId))
    {
        astralshow('P', $player -> id, $arrResources, $arrAmount, $arrNames);
    }
    else
    {
        $arrSqlResources = array('adamantium', 'crystal', 'meteor', 'pine', 'hazel', 'yew', 'elm', 'copper', 'bronze', 'brass', 'iron', 'steel', 'coal');
        $arrExp = array(2000, 3000, 4000, 5000, 7000);
        astralmake($planId, 'P', 'O', $player -> smith + $player -> fletcher, $strSkill, $arrResources, $arrAmount, $arrNames, $arrSqlResources, $arrExp);
    }
}

/**
* Function to show complete plans and craft astral items (for alchemists).
* $planId - which item will be crafted (if any; If not present - only display)
*/
function makeastral2($planId = 0)
{
    global $player;
    $arrHerbs = array(HERB1, HERB2, HERB3, HERB4, ENERGY_PTS);
    $arrAmount = array(array(3000, 1000, 2000, 1000, 50),
                       array(5000, 2500, 3500, 1500, 75),
                       array(7000, 3500, 5000, 2000, 100),
                       array(9000, 4500, 6500, 2500, 125),
                       array(12000, 6000, 8000, 3000, 150));
    $arrNames = array(POTION1, POTION2, POTION3, POTION4, POTION5);
    if(!ereg("^[1-5]*$", $planId))
    {
        astralshow('R', $player -> id, $arrHerbs, $arrAmount, $arrNames);
    }
    else
    {
        $arrSqlHerbs = array('illani', 'nutari', 'illanias', 'dynallca');
        $arrExp = array(2000, 3000, 4000, 5000, 7000);
        astralmake($planId, 'R', 'T', $player -> alchemy, 'alchemia', $arrHerbs, $arrAmount, $arrNames, $arrSqlHerbs, $arrExp);
    }
}

/**
* Function to show complete plans and craft astral items (for jewellers).
* $planId - which item will be crafted (if any; If not present - only display)
*/
function makeastral3($planId = 0)
{
    global $player;
    $arrResources = array(ADAMANTIUM, CRYSTAL, METEOR, COPPER, BRONZE, BRASS, IRON, STEEL, COAL, MITHRIL, ENERGY_PTS);
    $arrAmount = array(array(4000, 2000, 400, 3000, 2000, 1000, 750, 500, 10000, 4000, 50),
                       array(7000, 3500, 750, 5000, 3000, 2000, 1000, 750, 15000, 5000, 75),
                       array(10000, 5000, 1000, 7000, 5000, 3500, 1500, 1000, 20000, 6000, 100),
                       array(15000, 7500, 1500, 10000, 7000, 5000, 2500, 1750, 25000, 7000, 125),
                       array(20000, 10000, 2000, 15000, 10000, 7500, 4000, 2000, 30000, 8000, 150));
    $arrNames = array(JEWELLERY1, JEWELLERY2, JEWELLERY3, JEWELLERY4, JEWELLERY5);

    if(!ereg("^[1-5]*$", $planId))
    {
        astralshow('Y', $player -> id, $arrResources, $arrAmount, $arrNames);
    }
    else
    {
        $arrSqlResources = array('adamantium', 'crystal', 'meteor', 'copper', 'bronze', 'brass', 'iron', 'steel', 'coal');
        $arrExp = array(2000, 3000, 4000, 5000, 7000);
        astralmake($planId, 'Y', 'J', $player -> jeweller, 'jeweller', $arrResources, $arrAmount, $arrNames, $arrSqlResources, $arrExp);
    }
}
/**
* General "show astral items" function.
* $strPlan -plan type: P (smith+fletcher),
* $intId - player's id,
* $arrResources - names of used resources. Number of columns must match with arrAmount, last 2 must be platinum and energy. Const,
* $arrAmount - amount of resources required. Must contain platinum and energy cost as last 2 columns. Const,
* $arrNames - names of astral constructions. Number of columns must match no of rows in arrAmount. Const.
*/
function astralshow($strPlan, $intId, &$arrResources, &$arrAmount, &$arrNames)
{
    global $db;
    global $smarty;
    /// Error checking.
    $objAstral = $db -> Execute('SELECT `name` FROM `astral_plans` WHERE `owner`='.$intId.' AND `name` LIKE \''.$strPlan.'%\' AND `location`=\'V\'');
    if (!$objAstral -> fields['name'])
    {
        error(NO_ASTRAL_PLANS);
    }
    $arrAvailable = array();
    $arrAmount2 = array();
    $arrNumber = array();
    $i = 0;
    while (!$objAstral -> EOF)
    {
        $intKey = (int) substr ($objAstral -> fields['name'], 1);
        $arrNumber[$i] = $intKey;
        $intKey--;
        $arrAvailable[$i] = $arrNames[$intKey];
        $arrAmount2[$i] = $arrAmount[$intKey];
        $i++;
        $objAstral -> MoveNext();
    }
    $objAstral -> Close();
    $smarty -> assign(array('Astralinfo' => ASTRAL_INFO,
                            'Available' => $arrAvailable,
                            'Resourcename' => $arrResources,
                            'Resourceamount' => $arrAmount2,
                            'Compnumber' => $arrNumber,
                            'Abuild' => A_BUILD,
                            'Tname' => T_NAME,
                            'Message' => ''));
}

/**
* General "make astral items" function.
* $planid -id of item plan
* $strPlan - plan type: P (smith+fletcher)
* $strPlan2 - finished item type: O
* $fltSkill - used skill
* $strSkill - to what skill add bonus (if success)
* $arrResources - names of lost resources. Const,
* $arrAmount - amount of resources required,
* $arrNames - names of astral constructions. Const.
* $arrSqlResources - $arrResources mapped to database names. Const,
* $arrExp - experience gain (lower bound). Const,
*/
function astralmake($planId, $strPlan, $strPlan2, $fltSkill, $strSkill, &$arrResources, &$arrAmount, &$arrNames, &$arrSqlResources, &$arrExp)
{
    global $db;
    global $smarty;
    global $player;
    /// Error checking.
    $objAstral = $db -> Execute('SELECT `amount` FROM `astral_plans` WHERE `owner`='.$player -> id.' AND `name`=\''.$strPlan.$planId.'\' AND `location`=\'V\'');
    if (!$objAstral -> fields['amount'])
    {
        error(NO_PLAN);
    }
    $intKey = $planId - 1;
    $objMinerals = $strSkill != 'alchemia' ? $db -> Execute('SELECT `'.implode("`, `", $arrSqlResources).'` FROM `minerals` WHERE `owner`='.$player -> id) : $db -> Execute('SELECT `'.implode("`, `", $arrSqlResources).'` FROM `herbs` WHERE `owner`='.$player -> id);
    $intCountMinerals = count($arrSqlResources);
    $intStop = $intCountMinerals + (($strSkill != 'alchemia') ? 1 : 0); // alchemy array doesn't have platinum
    for ($i = 0; $i< $intCountMinerals; $i++)
    {
        if ($objMinerals -> fields[$arrSqlResources[$i]] < $arrAmount[$intKey][$i])
        {
            error(NO_AMOUNT.$arrResources[$i]);
        }
    }
    if ($strSkill != 'alchemia' && $player -> platinum < $arrAmount[$intKey][$intCountMinerals])
    {
        error(NO_MITH);
    }
    if ($player -> energy < $arrAmount[$intKey][$intStop])
    {
        error(NO_ENERGY);
    }
    /// Make the item.
    $intChance = min( floor($fltSkill * (5 - $intKey) /20 ), 95);   /// max chance = 95%, later astrals are more difficult
    if (rand(1, 100) <= $intChance) /// success
    {
        $objTest = $db -> Execute('SELECT `amount` FROM `astral` WHERE `owner`='.$player -> id.' AND `type`=\''.$strPlan2.$intKey.'\' AND `number`=0 AND `location`=\'V\'');
        if (!$objTest -> fields['amount'])
        {
            $db -> Execute('INSERT INTO `astral` (`owner`, `type`, `number`, `amount`, `location`) VALUES('.$player -> id.', \''.$strPlan2.$intKey.'\', 0, 1, \'V\')');
        }
            else
        {
            $db -> Execute('UPDATE `astral` SET `amount`=`amount`+1 WHERE `owner`='.$player -> id.' AND `type`=\''.$strPlan2.$intKey.'\' AND `number`=0 AND `location`=\'V\'');
        }
        $objTest -> Close();
        $intGainexp = rand(1, 1000) + $arrExp[$intKey];
        $arrAbility = array(1, 1.5, 2, 2.5, 3);
        checkexp($player -> exp, $intGainexp, $player -> level, $player -> race, $player -> user, $player -> id, 0, 0, $player -> id, $strSkill, $arrAbility[$intKey]);
        $strMessage = YOU_MAKE.$arrNames[$intKey].YOU_GAIN11.$intGainexp.YOU_GAIN12.$arrAbility[$intKey].YOU_GAIN13.YOU_USE;
    }
    else    ///failure. No ability bonus, no experience. Determine how many resources were wasted.
    {
        $intRoll = rand(1, 20);
        $fltBonus = ($intRoll > 5) ? ( ($intRoll > 11) ? 0.33 : 0.25 ) : ( ($intRoll == 1 ) ? 0 : 0.2 );
        if ($player -> clas == ARTISAN)
        {
            $fltBonus *= 2;
        }
        for ($i = 0; $i < $intStop; $i++)
        {
            $arrAmount[$intKey][$i] = ceil($arrAmount[$intKey][$i] * $fltBonus);
        }
        $strMessage = YOU_FAIL.$arrNames[$intKey].YOU_FAIL2.YOU_USE;
    }
    $strTakeMinerals = $strSkill !='alchemia' ? 'UPDATE `minerals` SET ' : 'UPDATE `herbs` SET ';
    /// Take minerals
    for ($i = 0; $i < $intStop; $i++)
    {
        $strMessage.= $arrResources[$i].': '.$arrAmount[$intKey][$i].'<br />';
        if ($i < $intCountMinerals)
        {
            $strTakeMinerals.= '`'.$arrSqlResources[$i].'`=`'.$arrSqlResources[$i].'`-'.$arrAmount[$intKey][$i];
            if ($i !=$intCountMinerals - 1)
            {
                $strTakeMinerals.=', ';
            }
        }
    }
    /// Lose astral plans (no matter the result of attempt)
    if (rand(1,20) == 1)
    {
        if( --$objAstral -> fields['amount'] < 1)
        {
            $db -> Execute( 'DELETE from `astral_plans` WHERE `owner`='.$player -> id.' AND `name`=\''.$strPlan.$planId.'\' AND `location`=\'V\'');
        }
        else
        {
            $db -> Execute( 'UPDATE `astral_plans` SET `amount`='.$objAstral -> fields['amount'].' WHERE `owner`='.$player -> id.' AND `name`=\''.$strPlan.$planId.'\' AND `location`=\'V\'');
        }
         $strMessage.='<p>'.YOU_HAVE_LOST_A_PLAN.'</p>';
    }
    $smarty -> assign('Message', $strMessage);
    $objAstral -> Close();
    if( $strSkill != 'alchemia')
    {
        $db -> Execute('UPDATE `players` SET `platinum`=`platinum`-'.$arrAmount[$intKey][$intCountMinerals].', `energy`=`energy`-'.$arrAmount[$intKey][$intCountMinerals + 1].' WHERE `id`='.$player -> id);
    }
    else
    {
        $db -> Execute('UPDATE `players` SET `energy`=`energy`-'.$arrAmount[$intKey][$intCountMinerals].' WHERE `id`='.$player -> id);
    }
    $db -> Execute($strTakeMinerals.' WHERE `owner`='.$player -> id);
}

/**
* Function to compute gained skill after item creation attempt.
* $strClass - Player's class, artisans have bonus to gained skill.
* $intSuccess - Indicates how many items were succesfully created.
* $intFailure - How many attempts were failures.
*/
function gainedskill($strClass, $intSuccess, $intFailure)
{
    if ($strClass == ARTISAN)
    {
        return floor( 0.01 * ($intFailure + 2 * $intSuccess));
    }
    $fltX = floor (0.5 *($intFailure + 2 * $intSuccess))/100;
    return ($fltX == 0.005) ? 0 : $fltX;
}

?>