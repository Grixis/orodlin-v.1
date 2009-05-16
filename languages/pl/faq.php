<?php
/**
 *   File functions:
 *   Main site of game
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

//
// $Id: index.php 355 2006-06-21 16:48:07Z thindil $

define('BACK', 'Wróć');

if (!isset($_GET['step']))
{
    define('FAQ_TITLE', 'FAQ - najczęściej zadawane pytania.');
    define('TOPIC1', 'Podstwowe informacje');
    define('TOPIC2', 'Problemy techniczne');
    if (isset ($_GET['view']))
    {
    	define('TEXT1', '');
		
    }
}

?>
