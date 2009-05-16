<?php
$title = "Panel Admina Technicznego";
$title1 = $title;
require_once("includes/head.php");

/**
* Get the localization for game
*/
require_once("languages/".$player -> lang."/tech.php");

if ($player->rank != 'Techniczny') {
    error (NOT_TECH);
}

/**
 * Reported bugs
 */
if (isset($_GET['view']) && $_GET['view'] == 'bugreport')
{
    /**
     * Bugs list
     */
    if (!isset($_GET['step']))
    {
		$oldFetchMode = $db -> SetFetchMode(ADODB_FETCH_NUM);
		$arrBugs = $db -> GetAll('SELECT `id`, `sender`, `title`, `type`, `location`, `programmer_id` FROM `bugreport` WHERE `resolution`=0 ORDER BY `id`');
		$db -> SetFetchMode($oldFetchMode);
        $smarty -> assign_by_ref('Bugs', $arrBugs);
    }
    /**
     * Edit bug
     */
        else
    {
        if (!preg_match("#^[1-9][0-9]*$#", $_GET['step']))
        {
            error(ERROR);
        }
		$oldFetchMode = $db -> SetFetchMode(ADODB_FETCH_NUM);
        $arrBug = $db -> GetRow('SELECT `id`, `sender`, `title`, `type`, `location`, `desc`, `programmer_id` FROM `bugreport` WHERE `id`='.$_GET['step']);
		if (empty($arrBug))
        {
            error(ERROR);
        }
        require_once('includes/bbcode.php');
        $arrBug[5] = htmltobbcode($arrBug[5]);
        $arrActions = array('fixed', 'notbug', 'workforme', 'moreinfo', 'duplicate');
		$arrProgrammerName = $db -> GetRow('SELECT `user` FROM `players` WHERE `id`='.$arrBug[6]);
		$db -> SetFetchMode($oldFetchMode);
		if (!empty($arrProgrammerName))
		{
			$smarty -> assign_by_ref('Programmer', $arrProgrammerName[0]);
		}
		$strType = $arrBug[3] == 'text' ? BUG_TEXT : BUG_CODE;
		$smarty -> assign_by_ref('Options', $arrOptions);
        $smarty -> assign_by_ref('Actions', $arrActions);
		$smarty -> assign_by_ref('Bug', $arrBug);
		$smarty -> assign_by_ref('BugType', $strType);
		$smarty -> assign('BugMessage', '');
		if (isset($_POST['programmer']) && $_POST['programmer'] == 1)
		{
			$db -> Execute('UPDATE `bugreport` SET `programmer_id`='.$player -> id.' WHERE `id`='.$_GET['step']);
			$smarty -> assign('BugMessage', BUG_TAKEN.'<br /><br />');
		}
		if (isset($_POST['programmer']) && $_POST['programmer'] == 0)
		{
			$db -> Execute('UPDATE `bugreport` SET `programmer_id`=0 WHERE `id`='.$_GET['step']);
			$smarty -> assign('BugMessage', BUG_LEFT.'<br />');
		}
        /**
         * Set bug status
         */
        if (isset($_POST['actions']))
        {
            if (!in_array($_POST['actions'], $arrActions))
            {
                error(ERROR);
            }
            $strInfo = YOUR_BUG.$arrBug[2].B_ID.$_GET['step'];
            $strDate = $db -> DBDate($newdate);
            $intKey = array_search($_POST['actions'], $arrActions);
            switch ($intKey) {
                case 0 :
                    $strInfo = $strInfo.HAS_FIXED;
                    $strMessage = HAS_FIXED2;
                    $strAuthor = '<b><a href="view.php?view='.$player -> id.'">'.$player -> user."</a></b>, ID <b>".$player -> id.'</b>';
                    $strDesc = T_BUG.$strType."): ".$arrBug[2]. REPORTED_BY.$arrBug[1];
                    $db -> Execute("INSERT INTO `changelog` (`author`, `location`, `text`, `date`, `lang`) VALUES('".$strAuthor."', '".$arrBug[4]."', '".$strDesc."', ".$strDate.", '".$player -> lang."')");
                    break;
                case 1 :
                    $strInfo = $strInfo.NOT_BUG3;
                    $strMessage = NOT_BUG2;
                    break;
                case 2 :
                    $strInfo = $strInfo.WORK_FOR_ME2;
                    $strMessage = WORK_FOR_ME3;
                    break;
                case 3 :
                    $strInfo = $strInfo.MORE_INFO2;
                    $strMessage = MORE_INFO3;
                    break;
                case 4 :
                    $strInfo = $strInfo.BUG_DOUBLE2;
                    $strMessage = BUG_DOUBLE3;
            }
            $db -> Execute("DELETE FROM `bugreport` WHERE `id`=".$_GET['step']);
            if (isset($_POST['bugcomment']) && !empty($_POST['bugcomment']))
            {
                $strInfo = $strInfo." <b>".BUG_COMMENT.":</b> ".$_POST['bugcomment'];
            }
            $db -> Execute("INSERT INTO `log` (`owner`, `log`, `czas`) VALUES(".$arrBug[1].", '".$strInfo."', ".$strDate.")");
            error($strMessage);
        }
    }
}

if (!isset($_GET['view']))
	{
	$_GET['view'] = '';
	}
if (!isset($_GET['step']))
	{
	$_GET['step'] = '';
	}

/**
* Assign variables and display page
*/
$smarty -> assign(array('View' => $_GET['view'],
                        'Step' => $_GET['step']));
$smarty -> display ('tech.tpl');

require_once("includes/foot.php");
?>
