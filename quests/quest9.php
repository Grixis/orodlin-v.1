   <?php
/**
*   File functions:
*   Quest in labirynth
*
*   @author               : Dellas <Pawel.Dudziec@gmail.com>
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
//

/**
* Assign variables to template
*/

$smarty -> assign(array("Start" => '',
     "End" => '',
     "Text" => '',
     "Box" => '',
     "Link" => '',
     "Answer" => ''));

require_once('class/quests_class.php');

/**
* Get the localization for game
*/

require_once("languages/".$player -> lang."/quest9.php");

$objAction = $db -> Execute("SELECT `action` FROM `questaction` WHERE `player`=".$player -> id." AND `quest`=9");
$objQuest = new Quests('grid.php', 9, $objAction -> fields['action']);

/**
* Check if player is on quest
*/

if (isset($_GET['step']) && $_GET['step'] == 'quest' && empty($objAction -> fields['action']))
{
    $db -> Execute("UPDATE `players` SET `miejsce`='Altara' WHERE `id`=".$player -> id);
    error(NO_QUEST);
}

/**
* Select texts from database based on players actions
*/

if (!$objAction -> fields['action'] || $objAction -> fields['action'] == 'start')
{
    $smarty -> assign("Link", "<br /><br />(<a href=\"grid.php?step=quest\">dalej</a>)");
}

if ($objAction -> fields['action'] == 'start')
{
    $objQuest -> Show('1');
    $smarty -> assign("Link", "<br /><br />(<a href=\"grid.php?step=quest\">idziesz dalej</a>)");
}

if ($objAction -> fields['action'] == '1')
{
    $objQuest -> Show('2');
    $objQuest -> Finish(0, 'Altara', 'Labirynt', 'grid.php');
}


$smarty -> display('quest.tpl');
?>

