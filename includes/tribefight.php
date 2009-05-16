<?php
/**
 *   File functions:
 *   Tribes fight
 *
 *   @name                 : tribefight.php
 *   @copyright            : (C) 2006 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @author               : eyescream <tduda@users.sourceforge.net>
 *   @version              : 1.4
 *   @since                : 25.08.2007
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

function getAstralComponent(&$objWinner, &$objLoser, $blnComplete = true)
{
    global $db, $strComponent, $strComponent2;
    static $arrCompnames = array(array(MAP1, MAP2, MAP3, MAP4, MAP5, MAP6, MAP7),
                          array(PLAN1, PLAN2, PLAN3, PLAN4, PLAN5),
                          array(RECIPE1, RECIPE2, RECIPE3, RECIPE4, RECIPE5),
                          array(FORMULA1, FORMULA2, FORMULA3, FORMULA4, FORMULA5),
                          array(COMP1, COMP2, COMP3, COMP4, COMP5, COMP6, COMP7),
                          array(CONST1, CONST2, CONST3, CONST4, CONST5),
                          array(POTION1, POTION2, POTION3, POTION4, POTION5),
                          array(JEWELLERY1, JEWELLERY2, JEWELLERY3, JEWELLERY4, JEWELLERY5));
    static $arrNames = array('M', 'P', 'R', 'Y', 'C', 'O', 'T', 'J'); // first 4 are partial components.
    $i = $blnComplete ? 4 : 0;
    $intRoll = rand(1, $blnComplete ? 5 : 20);
    if ($intRoll == 1)
    {
        $arrAmount = $db -> GetRow('SELECT count(*) FROM `astral` WHERE `owner`='.$objLoser -> fields['id'].' AND `location`=\'C\' AND (`type` LIKE \''.$arrNames[$i].'%\' OR `type` LIKE \''.$arrNames[$i+1].'%\' OR `type` LIKE \''.$arrNames[$i+2].'%\' OR `type` LIKE \''.$arrNames[$i+3].'%\')');
        if ($arrAmount['count(*)'])
        {
            $objAstral = $db -> SelectLimit('SELECT `type`, `number`, `amount` FROM `astral` WHERE `owner`='.$objLoser -> fields['id'].' AND `location`=\'C\' AND (`type` LIKE \''.$arrNames[$i].'%\' OR `type` LIKE \''.$arrNames[$i+1].'%\' OR `type` LIKE \''.$arrNames[$i+2].'%\' OR `type` LIKE \''.$arrNames[$i+3].'%\')', 1, rand(1, $arrAmount['count(*)']) - 1);

            $strName = $objAstral -> fields['type']{0};
            $intKey = array_search($strName, $arrNames);
            $intKey2 = (int)$objAstral -> fields['type']{1};
            $strCompname = $arrCompnames[$intKey][$intKey2];
            $strType = $intKey < 4 ? PIECE : COMPONENT;

            $arrTest = $db -> GetRow('SELECT `amount` FROM `astral` WHERE `owner`='.$objWinner -> fields['id'].' AND `type`=\''.$objAstral -> fields['type'].'\' AND `number`='.$objAstral -> fields['number'].' AND `location`=\'C\'');
            if (empty($arrTest))
            {
                $db -> Execute("INSERT INTO `astral` (`owner`, `type`, `number`, `amount`, `location`) VALUES(".$objWinner -> fields['id'].", '".$objAstral -> fields['type']."', ".$objAstral -> fields['number'].", 1, 'C')");
            }
            else
            {
               $db -> Execute("UPDATE `astral` SET `amount`=`amount`+1 WHERE `owner`=".$objWinner -> fields['id']." AND `type`='".$objAstral -> fields['type']."' AND `number`=".$objAstral -> fields['number']." AND `location`='C'");
            }
            if ($objAstral -> fields['amount'] == 1)
            {
                $db -> Execute("DELETE FROM `astral` WHERE `owner`=".$objLoser -> fields['id']." AND `type`='".$objAstral -> fields['type']."' AND `number`=".$objAstral -> fields['number']." AND `location`='C'");
            }
            else
            {
                $db -> Execute("UPDATE `astral` SET `amount`=`amount`-1 WHERE `owner`=".$objLoser -> fields['id']." AND `type`='".$objAstral -> fields['type']."' AND `number`=".$objAstral -> fields['number']." AND `location`='C'");
            }
            $objAstral -> Close();
            if ($strComponent == '')
            {
                $strComponent2 = WIN_COMPONENT2;
                $strComponent = WIN_COMPONENT;
            }
            else
            {
                $strComponent2 .= COMP_AND;
                $strComponent .= COMP_AND;
            }
            $strComponent2 .= $strType.$strCompname."</b>";
            $strComponent .= $strType.$strCompname."</b>";
        }
    }
}

if ($player -> id != $mytribe -> fields['owner'] && !$perm -> fields['attack'])
{
    error(NO_PERM2);
}
if ($mytribe -> fields['atak'] == 'Y')
{
    error(ONLY_ONE);
}
$klan1 = $db -> Execute("SELECT `id`, `name` FROM `tribes` WHERE `id`!=".$mytribe -> fields['id']);
$arrlink = array();
$i = 0;
while (!$klan1 -> EOF)
{
    $arrlink[$i] = "<a href=\"tribes.php?view=my&amp;step=owner&amp;step2=walka&amp;atak=".$klan1 -> fields['id']."\">".A_ATTACK.$klan1 -> fields['name']."<br /></a>";
    $klan1 -> MoveNext();
    $i ++;
}
$klan1 -> Close();
$smarty -> assign(array("Link" => $arrlink,
                        "Attack" => '',
                        "Selecttribe" => SELECT_TRIBE));
/**
 * Confirmation of attack
 */
if (!isset($_GET['step3']) && isset($_GET['atak']))
{
    if (!ereg("^[1-9][0-9]*$", $_GET['atak']))
    {
        error(ERROR);
    }
    $objEntribe = $db -> Execute("SELECT `id`, `name` FROM `tribes` WHERE `id`=".$_GET['atak']);
    if (!$objEntribe -> fields['id'])
    {
        error(ERROR);
    }
    $smarty -> assign(array("Youwant" => YOU_WANT,
                            "Entribe" => $objEntribe -> fields['name'],
                            "Ayes" => YES,
                            "Attack" => $_GET['atak']));
    $objEntribe -> Close();
}
if (isset($_GET['atak']) && (isset($_GET['step3']) && $_GET['step3'] == 'confirm'))
{
    if (!ereg("^[1-9][0-9]*$", $_GET['atak']))
    {
        error(ERROR);
    }
    $objTest = $db -> Execute("SELECT `id` FROM `tribes` WHERE `id`=".$_GET['atak']);
    if (!$objTest -> fields['id'])
    {
        error(ERROR);
    }
    $objTest -> Close();
    $matak = 0;
    $eobrona = 0;
    $db -> Execute("UPDATE `tribes` SET `atak`='Y' WHERE `id`=".$mytribe -> fields['id']);
    $mcechy = $db -> Execute("SELECT `klasa`, `strength`, `inteli`, `agility` FROM `players` WHERE `tribe`=".$mytribe -> fields['id']);
    while (!$mcechy -> EOF)
    {
        if ($mcechy -> fields['klasa'] != 'Mag')
        {
            $matak = ($matak + $mcechy -> fields['strength']);
        }
        if ($mcechy -> fields['klasa'] == 'Mag')
        {
            $matak = ($matak + $mcechy -> fields['inteli']);
        }
        $mcechy -> MoveNext();
    }
    $mcechy -> Close();
    $matak = $matak + $mytribe -> fields['zolnierze'];
    $ecechy = $db -> Execute("SELECT `klasa`, `strength`, `inteli`, `agility` FROM `players` WHERE `tribe`=".$_GET['atak']);
    while (!$ecechy -> EOF)
    {
        $eobrona = ($eobrona + $ecechy -> fields['agility']);
        $ecechy -> MoveNext();
    }
    $ecechy -> Close();
    $klan = $db -> Execute("SELECT `id`, `name`, `owner`, `forty`, `credits`, `platinum` FROM `tribes` WHERE `id`=".$_GET['atak']);
    $eobrona = $eobrona + $klan -> fields['forty'];
    $arrRoll = array();
    for ($i = 0; $i < 2; $i++)
    {
        $arrRoll[$i] = rand(1,1000);
    }
    $matak = ($matak + $arrRoll[0]);
    $eobrona = ($eobrona + $arrRoll[1]);
    $smarty -> assign("Victory", '');
    $strComponent = '';
    $strComponent2 = '';
    /**
     * If attacker win
     */
    if ($matak >= $eobrona)
    {
        if ($klan -> fields['credits'] > 0)
        {
            $gzloto = ceil($klan -> fields['credits'] / 10);
        }
            else
        {
            $gzloto = 0;
        }
        if ($klan -> fields['platinum'] > 0)
        {
            $gmith = ceil($klan -> fields['platinum'] / 10);
        }
            else
        {
            $gmith = 0;
        }

        /**
         * Get astral components. 5% chance for complete one and 20% for partial.
         */
        getAstralComponent($mytribe, $klan, true);
        getAstralComponent($mytribe, $klan, false);
        require_once('includes/checkastral.php');
        checkastral($mytribe -> fields['id']);
        checkastral($klan -> fields['id']);

        $smarty -> assign(array("Myname" => $mytribe -> fields['name'],
                                "Ename" => $klan -> fields['name'],
                                "Gold" => $gzloto,
                                "Mithril" => $gmith,
                                "Victory" => "My",
                                "Youwin" => YOU_WIN,
                                "Youwin2" => YOU_WIN2,
                                "Youwin3" => YOU_WIN3,
                                "Goldcoins" => GOLD_COINS,
                                "Mithrilcoins" => MITHRIL_COINS,
                                "Astral" => $strComponent));
        $db -> Execute("UPDATE `tribes` SET `credits`=`credits`+".$gzloto.", `platinum`=`platinum`+".$gmith.", `wygr`=`wygr`+1 WHERE `id`=".$mytribe -> fields['id']);
        $db -> Execute("UPDATE `tribes` SET `credits`=`credits`-".$gzloto.", `platinum`=`platinum`-".$gmith.", `przeg`=`przeg`+1 WHERE `id`=".$klan -> fields['id']);
        $strDate = $db -> DBDate($newdate);
        $db -> Execute("INSERT INTO `log` (`owner`, `log`, `czas`) VALUES(".$klan -> fields['owner'].", '".L_TRIBE.'<b><a href="tribes.php?view=view&amp;id='.$mytribe -> fields['id'].'">'.$mytribe -> fields['name'].'</a></b>'.ATTACK_YOU.$gzloto.GOLD_COINS.$gmith.MITHRIL_COINS.$strComponent2."', ".$strDate.")");
        $objPerm = $db -> Execute("SELECT `player` FROM `tribe_perm` WHERE `tribe`=".$klan -> fields['id']." AND `attack`=1");
        while (!$objPerm -> EOF)
        {
            $db -> Execute("INSERT INTO `log` (`owner`, `log`, `czas`) VALUES(".$objPerm -> fields['player'].", '".L_TRIBE.'<a href="tribes.php?view=view&amp;id='.$mytribe -> fields['id'].'">'.$mytribe -> fields['name'].'</a>'.ATTACK_YOU.$gzloto.GOLD_COINS.$gmith.MITHRIL_COINS.$strComponent2."', ".$strDate.")");
            $objPerm -> MoveNext();
        }
        $objPerm -> Close();
    }

    /**
     * If defender win
     */
        else
    {
        if ($mytribe -> fields['credits'] > 0)
        {
            $gzloto = ceil($mytribe -> fields['credits'] / 10);
        }
            else
        {
            $gzloto = 0;
        }
        if ($mytribe -> fields['platinum'] > 0)
        {
            $gmith = ceil($mytribe -> fields['platinum'] / 10);
        }
            else
        {
            $gmith = 0;
        }
        /**
         * Get astral components. 5% chance for complete one and 20% for partial.
         */
        getAstralComponent($klan, $mytribe, true);
        getAstralComponent($klan, $mytribe, false);
        require_once('includes/checkastral.php');
        checkastral($mytribe -> fields['id']);
        checkastral($klan -> fields['id']);

        $smarty -> assign(array("Ename" => $klan -> fields['name'],
                                "Gold" => $gzloto,
                                "Mithril" => $gmith,
                                "Victory" => "Enemy",
                                "Ewin" => YOU_WIN,
                                "Ewin2" => ENEMY_WIN2,
                                "Ewin3" => ENEMY_WIN3,
                                "Goldcoins" => GOLD_COINS,
                                "Mithrilcoins" => MITHRIL_COINS,
                                "Astral" => $strComponent2));
        $strDate = $db -> DBDate($newdate);
        $db -> Execute("INSERT INTO `log` (`owner`, `log`, `czas`) VALUES(".$klan -> fields['owner'].", '".L_TRIBE.'<b><a href="tribes.php?view=view&amp;id='.$mytribe -> fields['id'].'">'.$mytribe -> fields['name'].'</a></b>'.ATTACK_YOU2.$gzloto.GOLD_COINS.$gmith.MITHRIL_COINS.$strComponent."', ".$strDate.")");
        $objPerm = $db -> Execute("SELECT `player` FROM `tribe_perm` WHERE `tribe`=".$klan -> fields['id']." AND `attack`=1");
        while (!$objPerm -> EOF)
        {
            $db -> Execute("INSERT INTO `log` (`owner`, `log`, `czas`) VALUES(".$objPerm -> fields['player'].", '".L_TRIBE.'<b><a href="tribes.php?view=view&amp;id='.$mytribe -> fields['id'].'">'.$mytribe -> fields['name'].'</a></b>'.ATTACK_YOU2.$gzloto.GOLD_COINS.$gmith.MITHRIL_COINS.$strComponent."', ".$strDate.")");
            $objPerm -> MoveNext();
        }
        $objPerm -> Close();
        $db -> Execute("UPDATE `tribes` SET `credits`=`credits`-".$gzloto.", `platinum`=`platinum`-".$gmith.", `przeg`=`przeg`+1 WHERE `id`=".$mytribe -> fields['id']);
        $db -> Execute("UPDATE `tribes` SET `credits`=`credits`+".$gzloto.", `platinum`=`platinum`+".$gmith.", `wygr`=`wygr`+1 WHERE `id`=".$klan -> fields['id']);
    }
}
?>
