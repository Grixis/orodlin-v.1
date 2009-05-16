<?php
/**
 *   File functions:
 *   Warehouse - sell minerals and herbs, view history of transactions.
 *
 *   @name                 : warehouse.php
 *   @copyright            : (C) 2004,2005,2006 Vallheru Team based on Gamers-Fusion ver 2.5
 *   @author               : thindil <thindil@users.sourceforge.net>
 *   @author               : eyescream <tduda@users.sourceforge.net>
 *   @version              : 1.4
 *   @since                : 18.07.2007
 *
 */

// Published under GNU GPL 2 or later. See /install/README file for details.
// $Id$

/**
 * File uses selective Smarty caching.
 * The reason is that people tend to view the warehouse and compare prices with market, but they don't buy/sell as often.
 * Main page (view prices and amounts) and transactions history are cached.
 * Main page's $cache_id: 'Altara' or 'Ardulith' (different intro texts)
 * History page's group is 'h|[id]', where [id] = viewed item's number.
 *
 * It's meaningles to cache buying/selling page, it will change almost always. Even more: transactions clear the cache.
 *
 * Caching is silent - users don't feel that it works, unlike monuments or ranking. The only clue is faster loading.
 * Cache invalidates on main reset.
 */
$title = 'Królewski Skład';
require_once('includes/head.php');

/**
* Get the localization for game
*/
require_once('languages/'.$player -> lang.'/warehouse.php');

if ($player -> location != 'Altara' && $player -> location != 'Ardulith')
    error (ERROR);

/**
 * Function returns sell price, buy price and amount of mineral specified by index to $arrSQLNames array.
 * Additionally returns boolean indicating data source.
 */
function getWarehouseData($i)
{
    global $db;
    global $arrSQLNames;
    // Prices and amounts of items in first day of new era.
	$arrStartPrices = array(2,4,6,9,7,18,22,35,50,1,15,150,20,7,12,20,40,20,15,25,40,50,50,100,140,180);
	$arrStartAmount = array(50000,0,0,0,10000,0,0,0,0,100000,2000,500,2000,20000,0,0,0,10000,2000,2000,2000,2000,0,0,0,0);
	$arrTest = $db -> GetRow('SELECT `cost`, `amount` FROM `warehouse` WHERE `reset`=1 AND `mineral`=\''.$arrSQLNames[$i].'\'');
    if(empty($arrTest))   // No one bought/sold anything yet.
        return array($arrStartPrices[$i], 2 * $arrStartPrices[$i], $arrStartAmount[$i], false);
    return array(round($arrTest[0],1), round($arrTest[0] * 2,1), $arrTest[1], true);
}

/**
 * Function returns amount of mineral/herb owned by player.
 */
function getPlayersMineral($i)
{
    global $db;
    global $player;
    global $arrSQLNames;
    if ($i == 17)
        return $player -> platinum;
    $strName = $arrSQLNames[$i];
    if ($i < 17)
        $arrTest = $db -> GetRow('SELECT `'.$strName.'` FROM `minerals` WHERE `owner`='.$player -> id);
    else
        $arrTest = $db -> GetRow('SELECT `'.$strName.'` FROM `herbs` WHERE `gracz`='.$player -> id);
    return empty($arrTest) ? 0 : $arrTest[0];
}

$arrSQLNames = array('copperore', 'zincore', 'tinore', 'ironore', 'copper', 'bronze', 'brass', 'iron', 'steel', 'coal', 'adamantium', 'meteor', 'crystal', 'pine', 'hazel', 'yew', 'elm', 'mithril', 'illani', 'illanias', 'nutari', 'dynallca', 'illani_seeds', 'illanias_seeds', 'nutari_seeds', 'dynallca_seeds');
$db -> SetFetchMode(ADODB_FETCH_NUM);

// Setup Smarty caching.
if (!isset($_GET['action']) || $_GET['action']=='history')
{
    $smarty -> cache_dir = 'cache/';
    $smarty -> caching = 1;
}
// End of Smarty setup.
/**
* Main menu
*/
if (!isset($_GET['action']))
{
    if (!$smarty -> is_cached('warehouse.tpl', $player -> location))
    {
        for($i = 0, $arrItems=array(); $i < 26; ++$i)
            $arrItems[] = getWarehouseData($i);
        /// Info about caravan.
        $arrCaravan = $db -> GetRow('SELECT `value` FROM `settings` WHERE `setting`=\'caravan\'');
        $smarty -> assign_by_ref('Items', $arrItems);
        $smarty -> assign_by_ref('ItemNames', $arrItemNames);
        $smarty -> assign('Caravaninfo', $arrCaravan[0] == 'Y' ? CARAVAN_VISIT : '<br /><br />');
    }
    $smarty -> display('warehouse.tpl', $player -> location);
}
else
    require_once('includes/security.php');
/**
* Sell or buy herbs and minerals
*/
if (isset($_GET['action']) && ($_GET['action'] == 'sell' || $_GET['action'] == 'buy'))
{
    strictInt($_GET['item']);
    if ($_GET['item'] > 25)
        error(ERROR);

    // Prepare general variables that will handle database table differences.
    $strSQLname = $arrSQLNames[$_GET['item']];
    if ($_GET['item'] < 17)
    {
        $strTable = 'minerals';
        $strOwner = 'owner';
    }
    elseif ($_GET['item'] > 17)
    {
        $strTable = 'herbs';
        $strOwner = 'gracz';
    }
    elseif ($_GET['item'] == 17)
    {
        $strTable = 'players';
        $strOwner = 'id';
    }

    $arrInfo = getWarehouseData($_GET['item']);
    $intAmount = $_GET['action'] == 'sell' ? getPlayersMineral($_GET['item']) : $arrInfo[2];

    if (isset($_POST['amount']) && strictInt($_POST['amount']))
    {   // We don't display "no such amount" error, but try to buy as much as possible.
        if ($_POST['amount'] > $intAmount)
            $_POST['amount'] = $intAmount;

        $intGold = ceil($arrInfo[0] * $_POST['amount'] * ($_GET['action'] == 'buy' ? 2 : 1));
        if ($intGold > $player -> credits && $_GET['action'] == 'buy')
        {
            // Instead of error mesage "no money" we try to buy as much as possible.
            $_POST['amount'] = min($_POST['amount'], floor($player -> credits /$arrInfo[1]));
            $intGold = ceil($arrInfo[1] * $_POST['amount']);
        }
        // Give/take player's gold and platinum, if it was the transaction item.
        $db -> Execute('UPDATE `players` SET `credits`=`credits`'.($_GET['action'] == 'sell' ? '+' :
'-').$intGold.($_GET['item'] == 17 ? ',`platinum`=`platinum`'.($_GET['action'] == 'sell' ? '-' : '+').$_POST['amount'] : '').' WHERE `id`='.$player -> id);
        if ($arrInfo[3]) // Wa have already some transactions today with this item, so increment fiels only.
            $db -> Execute('UPDATE `warehouse` SET `'.$_GET['action'].'`=`'.$_GET['action'].'`+'.$_POST['amount'].', `amount`=`amount`'.($_GET['action'] == 'sell' ? '+' : '-').$_POST['amount'].' WHERE `reset`=1 AND `mineral`=\''.$strSQLname.'\'');
        else    // Else we add new transaction info. In normal circumstances this should only happen on day 1 of new era, since main resets perform these inserts and recompute the price.
            $db -> Execute('INSERT INTO `warehouse`(`reset`, `mineral`, `'.$_GET['action'].'`, `amount`, `cost`) VALUES (1,\''.$strSQLname.'\', '.$_POST['amount'].', '.($_GET['action'] == 'buy' ? $intAmount-$_POST['amount'] : $_POST['amount']).', '.$arrInfo[0].')');

        if ($_GET['action'] == 'sell')
        {   // Take player's herbs/minerals.
            $db -> Execute('UPDATE `'.$strTable.'` SET `'.$strSQLname.'`=`'.$strSQLname.'`-'.$_POST['amount'].' WHERE `'.$strOwner.'`='.$player -> id);
            $intAmount -= $_POST['amount'];
        }
        else
        {
            // Give herbs/minerals to player. Make new record or increment existing value.
            $arrTest = $db -> GetRow('SELECT `'.$strOwner.'` FROM `'.$strTable.'` WHERE `'.$strOwner.'`='.$player -> id);
            if (empty($arrTest))
                $db -> Execute('INSERT INTO `'.$strTable.'` (`'.$strOwner.'`, `'.$strSQLname.'`) VALUES('.$player -> id.','.$_POST['amount'].')');
            else
                $db -> Execute('UPDATE `'.$strTable.'` SET `'.$strSQLname.'`=`'.$strSQLname.'`+'.$_POST['amount'].' WHERE `'.$strOwner.'`='.$player -> id);
            $intAmount -=$_POST['amount'];
        }
        $smarty -> assign('Message', ($_GET['action'] == 'sell' ? YOU_SELL : YOU_BUY).$_POST['amount'].AMOUNT.$arrItemNames[$_GET['item']].FOR_A.$intGold.GOLD_COINS);
        // Clear cache of main page and corresponding mineral history.
		$smarty -> caching = true;
		if ($smarty -> is_cached('warehouse.tpl', 'Altara'))
			$smarty -> clear_cache('warehouse.tpl', 'Altara');
		if ($smarty -> is_cached('warehouse.tpl', 'Ardulith'))
        	$smarty -> clear_cache('warehouse.tpl', 'Ardulith');
		if ($smarty -> is_cached('warehouse.tpl', 'h|'.$_GET['item']))
        	$smarty -> clear_cache('warehouse.tpl', 'h|'.$_GET['item']);
		$smarty -> caching = false;
    }
    $smarty -> assign(array('Name' => $arrItemNames[$_GET['item']],
                            'Item' => $_GET['item'],
                            'Amount' => $intAmount,
                            'Price' => $arrInfo[$_GET['action'] == 'buy' ? 1 : 0]));
    $smarty -> display('warehouse.tpl');
}

/**
* Display history of changes for selected herb/mineral.
*/
if (isset($_GET['action']) && $_GET['action'] == 'history' && isset($_GET['item']))
{
    strictInt($_GET['item']);
    if($_GET['item'] > 25)
        error(ERROR);
    if (!$smarty -> is_cached('warehouse.tpl', 'h|'.$_GET['item']))
    {
        // Get biggest value of buys/sales, so all other can be scaled appropriately (percentages, fit to selected height).
        $arrMaximums = $db -> GetRow('SELECT MAX(`buy`), MAX(`sell`) FROM `warehouse` WHERE `mineral`=\''.$arrSQLNames[$_GET['item']].'\'');
        // Get data for each day.
        $arrData = $db -> GetAll('SELECT `sell`, `buy`, `amount`, `cost` FROM `warehouse` WHERE `mineral`=\''.$arrSQLNames[$_GET['item']].'\' ORDER BY `reset` DESC');
        $smarty -> assign_by_ref('Data', $arrData);
        $smarty -> assign_by_ref('Headers', $arrHeaders);
        $smarty -> assign(array('Name' => $arrItemNames[$_GET['item']],
                                'Max' => max($arrMaximums[0], $arrMaximums[1], 1)));
    }
    $smarty -> display('warehouse.tpl', 'h|'.$_GET['item']);
}

// Disable caching, to ensure that foot.php works in good, old, ineffective way.
$smarty -> caching = 0;
require_once('includes/foot.php');
?>
