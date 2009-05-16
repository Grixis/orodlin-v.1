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

require_once("languages/".$player -> lang."/quest20.php");

$objAction = $db -> Execute("SELECT `action` FROM `questaction` WHERE `player`=".$player -> id." AND `quest`=20");
$objQuest = new Quests('grid.php', 20, $objAction -> fields['action']);

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

if (!$objAction -> fields['action'] || $objAction -> fields['action'] == 'start' &&  !isset($_POST['box1']))
{
    $objQuest -> Box('1');
}
if ((isset($_POST['box1']) && $_POST['box1'] == 1) ) 
{
    $smarty -> assign("Start", "");
    $objQuest -> Show('1.1');
    $objQuest -> Resign();
}
if ((isset($_POST['box1']) && $_POST['box1'] == 2) ) 
{
    $smarty -> assign("Start", ""); 
    $objQuest -> Show('1.2');
    $smarty -> assign("Link", "<br /><br />(<a href=\"grid.php?step=quest\">dalej</a>)");
}
if ($objAction -> fields['action'] == '1.2')
{
    $objQuest -> Show('2');
    $smarty -> assign("Link", "<br /><br />(<a href=\"grid.php?step=quest\">dalej</a>)");
}
if ($objAction -> fields['action'] == '2')
{
    $objQuest -> Show('3');
    $smarty -> assign("Link", "<br /><br />(<a href=\"grid.php?step=quest\">dalej</a>)");
}
if ($objAction -> fields['action'] == '3' || ( !isset($_POST['box2']) && $objAction -> fields['action'] == '4'))
{
$objQuest -> Show('4');
$objQuest -> Box('2');
}
if ((isset($_POST['box2']) && $_POST['box2'] == 1) ) 
{
	$xx = rand(1,2);
    $objQuest -> Show('4.1.1');
    $objQuest -> Finish($xx, 'Altara', 'Labirynt', 'grid.php');
}
if ((isset($_POST['box2']) && $_POST['box2'] == 2) ) 
{
    $objQuest -> Show('4.2.1');
    $smarty -> assign("Link", "<br /><br />(<a href=\"grid.php?step=quest\">dalej</a>)");
}
if ((isset($_POST['box2']) && $_POST['box2'] == 3) ) 
{
    $objQuest -> Show('4.3.1');
    $smarty -> assign("Link", "<br /><br />(<a href=\"grid.php?step=quest\">dalej</a>)");
}
if ((isset($_POST['box2']) && $_POST['box2'] == 4) ) 
{
    $objQuest -> Show('4.4.1');
    $smarty -> assign(array("Box" => '', "Link" => "<br /><br />(<a href=\"grid.php?step=quest\">".A_FIGHT2."</a>)"));
    $db -> Execute("UPDATE players SET fight=41 WHERE id=".$player -> id);
}
if ($objAction -> fields['action'] == '4.2.1')
{
	$xx = rand(1,3);
	$objQuest -> Show('4.2.2');
    $objQuest -> Finish($xx, 'Altara', 'Meredith', 'city.php');
}
if ($objAction -> fields['action'] == '4.3.1')
{
	$xx = rand(1,3);
	$objQuest -> Show('4.2.2');
    $objQuest -> Finish($xx, 'Altara', 'Meredith', 'city.php');
}
if ($objAction -> fields['action'] == '4.4.1')
{
    $_POST['razy'] = 1;
    $objQuest -> Battle('grid.php?step=quest');
    $objFight = $db -> Execute("SELECT fight FROM players WHERE id=".$player -> id);            
    if ($objFight -> fields['fight'] == 0) 
    {
        $objHealth = $db -> Execute("SELECT hp FROM players WHERE id=".$player -> id);
        if ($objHealth -> fields['hp'] <= 0) 
        {
            $objQuest -> Show('lost1');
            $smarty -> assign(array("Link" => "<br /><br />(<a href=\"grid.php?step=quest\">".A_NEXT2."</a>)", "Box" => ''));
        } 
            elseif ($_POST['action'] != 'escape') 
        {
            $objQuest -> Show('win1');
            $smarty -> assign(array("Link" => "<br /><br />(<a href=\"grid.php?step=quest\">".A_NEXT2."</a>)", "Box" => ''));
        } 
            elseif ($_POST['action'] == 'escape') 
        {
            $objQuest -> Show('escape1');
            $smarty -> assign(array("Link" => "<br /><br />(<a href=\"grid.php?step=quest\">".A_NEXT2."</a>)", "Box" => ''));
        }
        $objHealth -> Close();
    } 
    $objFight -> Close();      
}
if ($objAction -> fields['action'] == 'lost1')
{
	$objQuest -> Show('lost1a');
    $objQuest -> Finish(0, 'Altara', 'Meredith', 'city.php');
}
if ($objAction -> fields['action'] == 'escape1')
{
	$xx = rand(3,5);
	$objQuest -> Show('escape1a');
    $objQuest -> Finish($xx, 'Altara', 'Kanały', 'grid.php');
}
if ($objAction -> fields['action'] == 'win1')
{
	$objQuest -> Show('win1a');
    $smarty -> assign("Link", "<br /><br />(<a href=\"grid.php?step=quest\">dalej</a>)");
}
if ($objAction -> fields['action'] == 'win1a' || ( !isset($_POST['box3']) && $objAction -> fields['action'] == '5'))
{
	$objQuest -> Show('5');
    $objQuest -> Box('3');
}
if ((isset($_POST['box3']) && $_POST['box3'] == 1) ) 
{
    $objQuest -> Show('5.1.1');
    $smarty -> assign("Link", "<br /><br />(<a href=\"grid.php?step=quest\">dalej</a>)");
}
if ((isset($_POST['box3']) && $_POST['box3'] == 2) ) 
{
    $objQuest -> Show('5.2.1');
    $smarty -> assign("Link", "<br /><br />(<a href=\"grid.php?step=quest\">dalej</a>)");
}
if ((isset($_POST['box3']) && $_POST['box3'] == 3) ) 
{
    $objQuest -> Show('5.3.1');
    $smarty -> assign("Link", "<br /><br />(<a href=\"grid.php?step=quest\">dalej</a>)");
}
if ($objAction -> fields['action'] == '5.1.1')
{	
	$objQuest -> Show('5.1.2');
	$smarty -> assign("Link", "<br /><br />(<a href=\"grid.php?step=quest\">dalej</a>)");
}
if ($objAction -> fields['action'] == '5.1.2')
{
    $xxx = rand(1,3);
    $xx = rand(10,15);
    $db -> Execute("UPDATE players SET ap=ap+$xxx WHERE id=".$player -> id);
	$smarty -> assign("Text", "<br /><br />Otrzymujesz $xxx punktów astralne. <br /> ");
    $objQuest -> Finish($xx, 'Altara', 'Kanały', 'grid.php');
}
if ($objAction -> fields['action'] == '5.2.1')
{
	$objQuest -> Show('5.2.2');
	$smarty -> assign("Link", "<br /><br />(<a href=\"grid.php?step=quest\">dalej</a>)");
}
if ($objAction -> fields['action'] == '5.2.2')
{
    $xxx = rand(30,50);
    $xx = rand(10,15);
    $db -> Execute("UPDATE players SET hp=hp+$xxx WHERE id=".$player -> id);
	$smarty -> assign("Text", "<br /><br />Otrzymujesz $xxx punktów życia. <br /> ");
    $objQuest -> Finish($xx, 'Altara', 'Kanały', 'grid.php');
}
if ($objAction -> fields['action'] == '5.3.1')
{
	$objQuest -> Show('5.3.2');
	$smarty -> assign("Link", "<br /><br />(<a href=\"grid.php?step=quest\">dalej</a>)");
}
if ($objAction -> fields['action'] == '5.3.2')
{
	$xxxx = rand(500,1500);
    $xxx = rand(30000,50000);
    $xx = rand(10,15);
    $db -> Execute("UPDATE players SET credits=credits+$xxx, platinum=platinum+$xxxx WHERE id=".$player -> id);
	$smarty -> assign("Text", "<br /><br />Otrzymujesz $xxx sztuk złota i $xxxx bryłek mithrillu. <br /> ");
    $objQuest -> Finish($xx, 'Altara', 'Kanały', 'grid.php');
}

$smarty -> display('quest.tpl');
?>
