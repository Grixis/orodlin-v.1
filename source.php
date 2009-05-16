<?php

/**
 We want to exclude some files from viewing.
 */
$excludedRegexps = array (
    "^source.php$",
    "config.php$",
    "sessions.php$",   // sessions data, deprecated; remove
    "\.\.",            // anything above current directory
    "^\/",             // path starting at root dir
);

$filename = $_GET ['file'];
foreach ($excludedRegexps as $regexp)
{
    if (preg_match ("/".$regexp."/", $filename) == 1)
    {
        print ("Get out, scum!");
        exit;
    }
}

// Here we go :)
highlight_file ($filename);

?>