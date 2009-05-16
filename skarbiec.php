<?php
/**
 *   File functions:
 *   skarbiec.php - deposits
 *
 *   @name                 : skarbiec.php
 *   @copyright            : Orodlin Team based on Gamers-Fusion ver 2.5
 *   @author               : Mzaszatko
 *   @version              : 1.3
 *   @since                : 28.04.2007
 *
 */

$title = 'Krasnoludzki Bank';
require_once('includes/head.php');
require_once('languages/'.$player -> lang.'/skarbiec.php');

/**
* Get the localization for game
*/
if ($player -> location != 'Ardulith')
{
    error (ERROR);
}
/**
* Assign variables to template
*/
$smarty -> assign(array('Description' => DESCRIPTION,
                        'Addday' => ADDDAY,
                        'Addweek' => ADDWEEK,
                        'Return' => CITYRETURN,
                        'Deposits' => DEPOSITS));

/**
* Add deposit for day
*/

if (isset ($_GET['akcja']) && $_GET['akcja'] == 'addday')
{
    $smarty -> assign(array('Daddday' => D_ADDDAY,
                            'Dpayday' => D_PAYDAY));

        if (isset ($_GET['step']) && $_GET['step'] == 'payday')
        {
                integercheck($_POST['amount']);
                if (!isset($_POST['amount']))
                {
                error(D_EMPTY);
                }
                if (!ereg("^[0-9]*$", $_POST['amount']))
                {
                error(D_EMPTYMONEY);
                }
                if ($_POST['amount'] > $player -> credits)
                {
                error(D_NOMONEY);
                }

                $db -> Execute("INSERT INTO `vault` (`owner`, `time`, `amount`, `type`) VALUES (".$player -> id.", '7',".$_POST['amount'].", '1')");
                $db -> Execute("UPDATE `players` SET `credits` = `credits` -".$_POST['amount']." WHERE `id`=".$player -> id);
                $smarty -> assign(array('Ile' => $_POST['amount'],
                                        'Dyoupay' => D_YOUPAY,
                                        'Dyoupaygold' => D_YOUPAYGOLD,
                                        'Dreturn' => D_RETURN,
                                        'Ile' => $_POST['amount']));
        }
}
/**
* Add deposit for week
*/
if (isset ($_GET['akcja']) && $_GET['akcja'] == 'addweek')
{
    $smarty -> assign(array('Waddweek' => W_ADDWEEK,
                            'Wpayweek' => W_PAYWEEK));

        if (isset ($_GET['step']) && $_GET['step'] == 'payweek')
        {
                integercheck($_POST['amount']);
                if (!isset($_POST['amount']))
                {
                error(W_EMPTY);
                }
                if (!ereg("^[0-9]*$", $_POST['amount']))
                {
                error(W_EMPTYMONEY);
                }
                if ($_POST['amount'] > $player -> credits)
                {
                error(W_NOMONEY);
                }

                $db -> Execute("INSERT INTO `vault` (`owner`, `time`, `amount`, `type`) VALUES(".$player -> id.", '45',".$_POST['amount'].", '10')");
                $db -> Execute("UPDATE `players` SET `credits`=`credits`-".$_POST['amount']." WHERE `id`=".$player -> id);
                $smarty -> assign(array('Wyoupay' => W_YOUPAY,
                                        'Wreturn' => W_RETURN,
                                        'Wyoupaygold' => W_YOUPAYGOLD,
                                        'Ile' => $_POST['amount']));
        }
}

/**
* Players deposits
*/

if (isset ($_GET['akcja']) && $_GET['akcja'] == 'deposits')
{
    $deposit = $db -> Execute('SELECT * FROM `vault` WHERE `owner`='.$player ->id.' AND `time`!= 0 ORDER BY `time` asc');
    $arrid = array();
    $arrtime = array();
    $arramount = array();
    $arrtype = array();
    $i = 0;
        while (!$deposit -> EOF)
        {
            $arrid[$i] = $deposit -> fields['id'];
            $arrtime[$i] = $deposit -> fields['time'];
            $arramount[$i] = $deposit -> fields['amount'];
            $arrtype[$i] = $deposit -> fields['type'];
            $deposit -> MoveNext();
            $i = $i + 1;
        }
     $deposit -> Close();
     $smarty -> assign(array('Id' => $arrid,
                             'Amount' => $arramount,
                             'Type' => $arrtype,
                             'Time' => $arrtime,
                             'Mdescription' => M_DESCRIPTION,
                             'Mreturn' => M_RETURN,
                             'Mamount' => M_AMOUNT,
                             'Mtype' => M_TYPE,
                             'Mtime' => M_TIME));
}


/**
* Initialization of variables
*/

if (!isset($_GET['akcja']))
{
    $_GET['akcja'] = '';
}
if (!isset($_GET['step']))
{
    $_GET['step'] = '';
}

/**
* Assing variables and display page
*/

$smarty -> assign ( array("Akcja" => $_GET['akcja'], "Step" => $_GET['step']));
$smarty -> display ('skarbiec.tpl');

require_once("includes/foot.php");
?>
