<?php
/**
 *   File functions:
 *   Frequently asked 
 *
 *   @name                 : faq.php
 *   @copyright            : (C) 207 Orodlin Team based on Gamers-Fusion ver 2.5 and Vallheru Engine
 *   @author               : 
 *   @version              : 
 *   @since                : 18.05.2007
 *
//
// $Id: index.php 835 2006-11-22 17:40:22Z thindil $

*/
require_once ('includes/getlang.php');
GetLang ();
GetLoc ('team');
$title1 = 'Team Orodlin';
$title = 'Team Orodlin';

if ($_GET['int'] != '1') { // external call
	require_once ('includes/main/base.php');


	GetLoc ('mainpage');
	GameCloseRoutine ();

	require_once ('includes/main/record.php');
	require_once ('includes/main/counter.php');
	require_once ('includes/main/online.php');
        require_once ('includes/main/usersever.php');

	require_once ('includes/right.php');
	$smarty->assign (array ('Pagetitle'=>TEAM));
}
else {// internal call
	require_once('includes/head.php');

}


class Team
{
	private $db;
	private $tpl;
	public $ViewData;
	public $Message;
	public $Mode;
	public $Internal;

	private function Insert ($_what, $_table)
	{
		if (!is_array ($_what)) {
			throw new Exception  ('array required as parameter');
		}

		$keys = '';
		$values = '';

		$db = $this->db;

		foreach ($_what as $key => $value) {
			$keys .= '`'.$key . '`, ';
			$values .= '\''.$value . '\', ';
		}
		
		$keys = preg_replace ('/, $/', '', $keys);
		$values = preg_replace ('/, $/', '', $values);

		$db->Execute ('INSERT INTO `' . $_table . '` (' . $keys . ') VALUES (' . $values . ');');
	}

	public function __construct ()
	{
		global $smarty;
		global $db;
		$this->tpl = &$smarty;
		$this->db = &$db;

		global $_GET;
		global $_POST;

		if (!isset ($_GET['mode'])) {
			$_GET['mode'] = 'view';
		}
		if (!isset ($_GET['step'])) {
			$_GET['step'] = '';
		}

		$this->Step = $_GET['step'];
		$this->Mode = $_GET['mode'];
		$this->Message = '';

		if (!isset ($_GET['int']) or $_GET['int'] != 1) {
			$this->Internal = 0;
			$this->tpl->assign (array ('AddrSufix' => ''));
		}
		else {
			$this->Internal = 1;
			$this->tpl->assign (array ('AddrSufix' => '&amp;int=1'));
		}


		$this->Admin = 0;
		if ($this->Internal == 1) {
			global $player;
			if ($player -> rank == "Admin" || $player -> rank == 'Staff') {
				$this->Admin = 1;
			}
		}

		$this->tpl->assign (array ('Internal' => $this->Internal,
					'Admin' => $this->Admin));

		switch ($_GET['mode']) {
			case 'view': $this->ViewRoutine (); break;
			case 'add': $this->AddRoutine (); break;
			case 'modify': $this->ModifyRoutine (); break;
			case 'delete': $this->DeleteRoutine (); break;
		}

		$this->tpl->assign (array ('Mode' => $this->Mode, 'Step' => $this->Step, 'Message' => $this->Message));
	}
	
	private function ViewRoutine ()
	{
		global $_GET;
		try {
			$this->ViewData = $this->RetrieveViewData ();
			$this->tpl->assign (array ('ViewData' => $this->ViewData,
						'NoMembers' => 0));
		}
		catch (Exception $e) {
			$this->tpl->assign (array ('NoMembers' => 1));
		}
		$this->Mode = 'view';
	}

	private function ModifyRoutine ()
	{
		if ($this->Admin != 1) {
			die (DONT_FUCK_WITH_ME);
		}
		global $_GET;
		global $_POST;

		$this->Mode = 'modify';
		if ($_GET['step'] != 'write') {
			if (!is_numeric ($_GET['id'])) {
				die (DONT_FUCK_WITH_ME);
			}

			$obj = $this->db->execute ('SELECT * FROM team WHERE `tid`=\''.$_GET['id'].'\';');
			$Name = $obj->fields['name'];
			$GameID = $obj->fields['gameid'];
			$Function = $obj->fields['function'];
			$Type = $obj->fields['type'];
			$obj->Close ();

			$this->tpl->assign (array ('ModifyID'=>$_GET['id'], 'ModifyName'=>$Name, 'ModifyGameID'=>$GameID, 'ModifyFunction'=>$Function, 'ModifyType'=>$Type));

		}
		else { // step == write
			if (!is_numeric ($_POST['id']) or !is_numeric ($_POST['gameid'])) {
				die (DONT_FUCK_WITH_ME);
				}

			$name = addslashes ($_POST['name']);
			$function = addslashes ($_POST['function']);
			$id = $_POST['id'];
			$gameid = $_POST['gameid'];
			$type = addslashes($_POST['type']);

			$obj = $this->db->Execute ('SELECT `avatar`, `gg` FROM players WHERE `id`=\''.$gameid.'\';');


			$avatarpath = $obj->fields['avatar'];
			$contact = $obj->fields['gg'];
			$obj->Close ();

			$this->db->Execute ('UPDATE `team` SET `name`=\''.$name.'\', `gameid`=\''.$gameid.'\', `function`=\''.$function.'\', `avatar`=\''.$avatarpath.'\', `contact`=\''.$contact.'\', `type`=\''.$type.'\'  WHERE `tid`=\''.$id.'\' LIMIT 1;');

			$this->Message = TEAM_MODIFY_SUCCESS;
			$this->ViewRoutine ();
		}

	}

	private function AddRoutine ()
	{
		if ($this->Admin != 1) {
			die (DONT_FUCK_WITH_ME);
		}

		global $_GET;
		global $_POST;

		if ($_GET['step'] != 'write') {
			$this->Mode = $_GET['mode'];
		}
		else { //$_GET['step'] == 'write'
			$this->WriteNewMember ($_POST['name'], $_POST['gameid'], $_POST['function'], $_POST['type']);
			$this->Message = TEAM_ADD_SUCCESS;
			$this->ViewRoutine ();
		}
	}

	private function DeleteRoutine ()
	{
		global $_GET;
		if (!is_numeric ($_GET['id'])) {
			die (DONT_FUCK_WITH_ME);
		}

		if ($_GET['step'] != 'write') {
			$this->Mode = 'delete';
			$this->tpl->Assign (array ('ID'=>$_GET['id'], 'TeamDeleteConfirmation'=>TEAM_DELETE_CONFIRMATION));
		}
		else {
			$this->db->Execute ('DELETE FROM `team` WHERE `tid`=\''.$_GET['id'].'\';');
			$this->Message = TEAM_DELETE_SUCCESS;
			$this->ViewRoutine ();
			}
	}

	private function WriteNewMember ($_name, $_gameid, $_function, $_type)
	{
		if (!isset ($_name) || !isset ($_gameid) || !is_numeric ($_gameid) || !isset ($_function) || !isset($_type)) {
			die (DONT_FUCK_WITH_ME);
		}

		$name = addslashes ($_name);
		$function = addslashes ($_function);
		$type = addslashes($_type);
/*		$obj = $this->db->Execute ('SELECT `avatar`, `gg` FROM players WHERE `id`=\''.$_gameid.'\';');


		$avatarpath = $obj->fields['avatar'];
		$contact = $obj->fields['gg'];
		$obj->Close ();*/

		$obj = $this->Insert (array ('tid'=>'NULL', 'gameid'=>$_gameid, 'name'=>$name, 'function'=>$function, 'type'=>$type), 'team');

	}


	private function RetrieveViewData ()
	{
		$data = array ();
		$obj = $this->db->execute ('SELECT `tid`, `gameid`, `name`, `function`, `players`.`gg` as `contact`, `players`.`avatar` FROM `team`, `players` WHERE `gameid` = `players`.`id`');
		if ($obj == false) {
			die ($this->db->GetError ());
		}
		
		if ($obj->EOF) {
			throw new Exception (); 
		}

		$i = 0;
		while (!$obj->EOF) {
			if ($obj->fields['contact'] == '0') 
			{
				$obj->fields['contact'] = '';
			}
			$data[$i++] = array ('ID' => $obj->fields['tid'], 'GameID' => $obj->fields['gameid'], 'Name' => stripslashes($obj->fields['name']), 'Function' => stripslashes($obj->fields['function']), 'Avatar'=>$obj->fields['avatar'], 'Contact'=>$obj->fields['contact']);
			$obj->MoveNext ();
		}
		$obj->close ();

		return $data;
	}

	public function Display ()
	{
		$this->tpl-> display('team.tpl');
	}

};

$t = new Team ();
$t->Display ();

if ($_GET['int'] == 1) {
	require_once("includes/foot.php");
}
$db -> Close();

?>
