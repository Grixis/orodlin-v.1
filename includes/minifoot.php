<?php
/**
 *   File functions:
 *   Minimalistic version of /includes/foot.php. Designed to use with AJAX-powered pages.
 *
 *   @name                 : minifoot.php
 *   @copyright            : (C) 2007 Orodlin Team based on Vallheru 1.3
 *   @author               : eyescream <tduda@users.sourceforge.net>
 *   @version              : 0.1
 *   @since                : 28.07.2007
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
// $Id$

$db -> LogSQL(false);
$db -> Execute('TRUNCATE TABLE `adodb_logsql`');

if (!isset($do_gzip_compress))
    $do_gzip_compress = false;
if ($do_gzip_compress)
{
    //
    // Borrowed from php.net!
    //
    $gzip_contents = ob_get_contents();
    ob_end_clean();
    $gzip_size = strlen($gzip_contents);
    $gzip_crc = crc32($gzip_contents);
    $gzip_contents = gzcompress($gzip_contents, 9);
    $gzip_contents = substr($gzip_contents, 0, strlen($gzip_contents) - 4);

    echo '\x1f\x8b\x08\x00\x00\x00\x00\x00'.$gzip_contents.pack('V', $gzip_crc).pack('V', $gzip_size);
}
$db -> Close();
session_write_close();
?>