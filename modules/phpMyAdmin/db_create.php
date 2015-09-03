<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

require("modules/$V_dir/header.inc.php");

// RAD $result = mysql_query("CREATE DATABASE ".$db) or mysql_die();
// RAD $message = "$strDatabase $strHasBeenCreated";

$message = "$strDatabase $db $strNo $strHasBeenCreated.<font color=darkred> <u>$strAccessDenied</u></font>";

require("modules/$V_dir/db_details.php");

?>
