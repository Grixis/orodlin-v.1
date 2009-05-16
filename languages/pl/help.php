<?php
/**
 *   File functions:
 *   Polish language help
 *
 *   @name                 : help.php                            
 *   @copyright            : 
 *   @author               : 
 *   @version              : 
 *   @since                : 
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

if (!isset($Page) || empty($Page)) {
	define('INFO', 'Witaj w <b>Pomocy kontekstowej '.$gamename.'</b>. Dzęki niej możesz się dowiedzieć wielu przydatnych informacji o każdym miejscu, które odwiedzasz. Wystarczy kliknąć w ikonę <img src="" alt="img?help" width="13" height="14" /> znajdującą się po prawej stronie przy tytule każdej z lokacji, aby wyświetlić informacje o danym miejscu.');
}
else {
	switch ($Page) {
		default:
			define($Page, 'Przepraszamy, ale nie ma takiego miejsca lub pomoc nie jest jeszcze zdefiniowana.');
			break;


///////////////////////////////////////////////////////
//case 'xxx' gdzie xxx to nazwa pliku bez końcówki php!
///////////////////////////////////////////////////////
/////////////////////////PRZYKŁAD//////////////////////
///////////////////////////////////////////////////////
//		case 'help':
//			define($Page, '<p>Witaj w <a href="help.php">Pomocy kontekstowej '.$gamename.'</a>. Dzęki niej możesz się dowiedzieć wielu przydatnych informacji o każdym miejscu, które odwiedzasz. Wystarczy kliknąć w ikonę <img src="" alt="img?help" width="13" height="14" /> znajdującą się po prawej stronie przy tytule każdej z lokacji, aby wyświetlić informacje o danym miejscu.</p>');
//			break;
///////////////////////////////////////////////////////


		case 'help':
			define($Page, '<p>Witaj w <a href="help.php">Pomocy kontekstowej '.$gamename.'</a>. Dzęki niej możesz się dowiedzieć wielu przydatnych informacji o każdym miejscu, które odwiedzasz. Wystarczy kliknąć w ikonę <img src="" alt="img?help" width="13" height="14" /> znajdującą się po prawej stronie przy tytule każdej z lokacji, aby wyświetlić informacje o danym miejscu.</p>');
			break;
	}
}

?>
