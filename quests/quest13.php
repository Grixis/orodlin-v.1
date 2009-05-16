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

require_once("languages/".$player -> lang."/quest13.php");

$objAction = $db -> Execute("SELECT `action` FROM `questaction` WHERE `player`=".$player -> id." AND `quest`=13");
$objQuest = new Quests('grid.php', 13, $objAction -> fields['action']);

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
    $smarty -> assign("Link", "<br /><br />(<a href=\"grid.php?step=quest\">idziesz dalej</a>)");
}

if ($objAction -> fields['action'] == '2')
{
    $objQuest -> Show('3');
    $smarty -> assign("Link", "<br /><br />(<a href=\"grid.php?step=quest\">dalej</a>)");
}

if ($objAction -> fields['action'] == '3')
{
    $objQuest -> Show('4');
    $smarty -> assign("Link", "<br /><br />(<a href=\"grid.php?step=quest\">dalej</a>)");
}

if ($objAction -> fields['action'] == '4')
{
    $xxx = rand(500,700);
    if ($player -> speed + $player -> cond >= $xxx)
    {
        $xx = rand(20,40) / 100 * $player -> credits ;
        $db -> Execute("UPDATE players SET credits=credits -$xx WHERE id=".$player -> id);
        $objQuest -> Show('4.1');
        $smarty -> assign("Link", "<br /><br />(<a href=\"grid.php?step=quest\">dalej</a>) <br /> <br /> Tracisz $xx złota w sakiewce");
     }
     else
     {
        $objQuest -> Show ('4.2');
        $smarty -> assign("Link","<br /><br />(<a href=\"grid.php?step=quest\">dalej</a>)");
     }
}

if ($objAction -> fields['action'] == '4.1')
{
    $xx = rand(1,2);
    $objQuest -> Show('4.1.1');
    $objQuest -> Finish($xx, 'Altara', 'Meredith', 'city.php');
}

if ($objAction -> fields['action'] == '4.2')
{
    $objQuest -> Show('4.3');
    $smarty -> assign(array("Box" => '', "Link" => "<br /><br />(<a href=\"grid.php?step=quest\">".A_FIGHT2."</a>)"));
    $db -> Execute("UPDATE players SET fight=14 WHERE id=".$player -> id);
}

if ($objAction -> fields['action'] == '4.3')
{
    $_POST['razy'] = 2;
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

if ($objAction -> fields['action'] == 'escape1')
{
    $xx = rand(1,2);
    $objQuest -> Show('escape1a');
    $objQuest -> Finish($xx, 'Altara', 'Meredith', 'city.php');
}

if ($objAction -> fields['action'] == 'lost1')
{
    $objQuest -> Show('lost1a');
    $smarty -> assign("Link", "<br /><br />(<a href=\"grid.php?step=quest\">dalej</a>)");

}
if ($objAction -> fields['action'] == 'lost1a')
{
    $xx = rand(1,2)*0.1;
    $db -> Execute("UPDATE players SET max_energy=max_energy - $xx WHERE id=".$player -> id);
    $smarty -> assign("Text","Straciles $xx maksymalnej energii." );
    $objQuest -> Finish(0, 'Altara', 'Szpital', 'hospital.php');
}

if ($objAction -> fields['action'] == 'win1')
{
    $objQuest -> Show('5');
    $smarty -> assign(array("Box" => '', "Link" => "<br /><br />(<a href=\"grid.php?step=quest\">".A_FIGHT2."</a>)"));
    $db -> Execute("UPDATE players SET fight=18 WHERE id=".$player -> id);
}

if ($objAction -> fields['action'] == '5')
{
    $_POST['razy'] = 1;
    $objQuest -> Battle('grid.php?step=quest');
    $objFight = $db -> Execute("SELECT fight FROM players WHERE id=".$player -> id);
    if ($objFight -> fields['fight'] == 0)
    {
        $objHealth = $db -> Execute("SELECT hp FROM players WHERE id=".$player -> id);
        if ($objHealth -> fields['hp'] <= 0)
        {
            $objQuest -> Show('lost2');
            $smarty -> assign(array("Link" => "<br /><br />(<a href=\"grid.php?step=quest\">".A_NEXT2."</a>)", "Box" => ''));
        }
            elseif ($_POST['action'] != 'escape')
        {
            $objQuest -> Show('win2');
            $smarty -> assign(array("Link" => "<br /><br />(<a href=\"grid.php?step=quest\">".A_NEXT2."</a>)", "Box" => ''));
        }
            elseif ($_POST['action'] == 'escape')
        {
            $objQuest -> Show('escape2');
            $smarty -> assign(array("Link" => "<br /><br />(<a href=\"grid.php?step=quest\">".A_NEXT2."</a>)", "Box" => ''));
        }
        $objHealth -> Close();
    }
    $objFight -> Close();
}

if ($objAction -> fields['action'] == 'escape2')
{
    $xx = rand(2,4);
    $objQuest -> Show('escape2a');
    $objQuest -> Finish($xx, 'Altara', 'Meredith', 'city.php');
}

if ($objAction -> fields['action'] == 'lost2')
{
    $objQuest -> Show('lost2a');
    $smarty -> assign("Link", "<br /><br />(<a href=\"grid.php?step=quest\">dalej</a>)");

}
if ($objAction -> fields['action'] == 'lost2a')
{
    $xx = rand(1,2)*0.1;
    $db -> Execute("UPDATE players SET max_energy=max_energy - $xx WHERE id=".$player -> id);
    $smarty -> assign("Text","Straciles $xx maksymalnej energii." );
    $objQuest -> Finish(0, 'Altara', 'Szpital', 'hospital.php');
}

if ($objAction -> fields['action'] == 'win2')
{
    $objQuest -> Show('6');
    $smarty -> assign("Link", "<br /><br />(<a href=\"grid.php?step=quest\">dalej</a>)");
}

if ($objAction -> fields['action'] == '6')
{
    $xx = rand(1000,3000);
    $xxx = rand(30,50);
    $xxxx = rand(5,10);
    $db -> Execute("UPDATE players SET credits=credits + $xx WHERE id=".$player -> id);
    $db -> Execute("UPDATE players SET platinum=platinum + $xxx WHERE id=".$player -> id);
    $smarty -> assign("Text", "<br /><br /> Otrzymujesz $xx złota <br /> Otrzymujesz $xxx mithrillu <br /><br />");
    $objQuest -> Finish($xxxx, 'Altara', 'KanaĹ‚y', 'grid.php');
}

$smarty -> display('quest.tpl');
?>
