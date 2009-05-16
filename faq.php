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
require_once ('includes/main/base.php');

require_once ('includes/getlang.php');
GetLang ();
GetLoc ('mainpage');
GetLoc ('faq');

GameCloseRoutine ();

require_once ('includes/main/record.php');
require_once ('includes/main/counter.php');
require_once ('includes/main/online.php');
require_once ('includes/main/usersever.php');

require_once ('includes/right.php');



        $titles = array(TOPIC1, TOPIC2, TOPIC3, TOPIC4, TOPIC5, TOPIC6, TOPIC7, TOPIC8, TOPIC9, TOPIC10, TOPIC11, TOPIC12, TOPIC13);
        $count_topics = count($titles);
        $half_number = ceil($count_topics / 2 );
        $counter = array();
        for ( $i=0; $i < $count_topics; $i++)
        {
    	    $counter[$i] = $i + 1 ;
        }
        $smarty -> assign( array ('Pagetitle' => FAQ_TITLE,
                                  'Titles' => $titles,
                                  'Counttopics' => $count_topics,
                                  'Halfnumber' => $half_number,
                                  'Counter' => $counter));
        $number_text = $_GET['view'];
        if (isset ($_GET['view']) && $_GET['view'] == $number_text)
        {
    	    $arrtexts = array(TEXT1, TEXT2, TEXT3, TEXT4, TEXT5, TEXT6, TEXT7, TEXT8, TEXT9, TEXT10,TEXT11, TEXT12, TEXT13);
      	    $smarty -> assign( array('Faq_text' => $arrtexts[$number_text-1]));  
        }
        $smarty -> display('faq.tpl');
    $db -> Close();
?>
