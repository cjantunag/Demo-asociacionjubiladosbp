<?php
global $PHPSESSID, $PHP_SELF;
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

if(isset($db)) {
    echo "<h1> $strDatabase $db";
    if(isset($table)) {
        echo " - $strTable $table";
    }
    echo "</h1>";
}
?>
