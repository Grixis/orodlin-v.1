<?php

require_once ('includes/config.php');
require_once ('libs/Smarty.class.php');

$smarty = new Smarty;
$smarty->compile_check = true;

$smarty->assign(array(
	'Gamename'		=>	$gamename,
	'Gameadress'	=>	$gameadress,
	'Meta'			=>	''
));

function GameCloseRoutine ()
{
	global $db;
	global $smarty;

    $objOpengame = $db -> Execute("SELECT value FROM settings WHERE setting='open'") or die($db -> ErrorMsg());
    /**
    * When game is close
    */
    if ($objOpengame -> fields['value'] == 'N')
    {
        $objReason = $db -> Execute("SELECT value FROM settings WHERE setting='close_reason'");
        $smarty -> assign ("Error", REASON.":<br />".$objReason -> fields['value']);
        $objReason -> Close();
        $smarty -> display ('error.tpl');
        exit;
    }
    $objOpengame -> Close();
}

function	RegistrationCloseRoutine ()
{
	global $db;
	global $smarty;

	$objOpenreg = $db -> Execute("SELECT value FROM settings WHERE setting='register'");
	/**
	* When registration is closed
	*/
	if ($objOpenreg -> fields['value'] == 'N') 
	{
		$objReason = $db -> Execute("SELECT value FROM settings WHERE setting='close_register'");
		$smarty -> assign ("Error", REASON.":<br />".$objReason -> fields['value']);
		$objReason -> Close();
		$smarty -> display ('error.tpl');
		exit;
	}
	$objOpenreg -> Close();
}
?>
