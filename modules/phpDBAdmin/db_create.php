<?
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

require("modules/$V_dir/header.inc.php");

// RAD $result = sql_query("CREATE DATABASE ".$db);
// RAD $message = "$strDatabase $strHasBeenCreated";

$message = "$strDatabase $db $strNo $strHasBeenCreated.<font color=darkred> <u>$strAccessDenied</u></font>";

require("modules/$V_dir/db_details.php");

?>
