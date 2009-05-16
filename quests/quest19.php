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

require_once("languages/".$player -> lang."/quest19.php");

$objAction = $db -> Execute("SELECT `action` FROM `questaction` WHERE `player`=".$player -> id." AND `quest`=19");
$objQuest = new Quests('grid.php', 19, $objAction -> fields['action']);

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
    $smarty -> assign("Link", "<br /><br />(<a href=\"grid.php?step=quest\">dalej</a>)");
}
if ($objAction -> fields['action'] == '2')
{
    $objQuest -> Show('3');
    $smarty -> assign("Link", "<br /><br />(<a href=\"grid.php?step=quest\">dalej</a>)");
}
if ($objAction -> fields['action'] == '3')
{
    $xxx = rand(350,1000);
    if ($player -> speed + $player -> cond +$player -> strength >= $xxx)
    {
        $objQuest -> Show('4.1');
        $smarty -> assign("Link", "<br /><br />(<a href=\"grid.php?step=quest\">dalej</a>)");
     }
     else
     {
        $objQuest -> Show ('4.2');
        $smarty -> assign("Link","<br /><br />(<a href=\"grid.php?step=quest\">dalej</a>)");
     }
}
if ($objAction -> fields['action'] == '4.1')
{
    $xx = rand(30,150);
    $db -> Execute("UPDATE players SET hp=hp - $xx WHERE id=".$player -> id);
    $smarty -> assign("Text", 'Straciłeś '.$xx.' punktów życia. <br /> <br />');
    $objQuest -> Finish(0, 'Altara', 'Kanały', 'grid.php');
}
if ($objAction -> fields['action'] == '4.2')
{
    $db -> Execute("UPDATE players SET hp=0 WHERE id=".$player -> id);
    $smarty -> assign("Text", 'Tracisz wszystkie punkty życia. <br /> <br />');
    $objQuest -> Finish(0, 'Altara', 'Kanały', 'grid.php');
}

$smarty -> display('quest.tpl');
?>
