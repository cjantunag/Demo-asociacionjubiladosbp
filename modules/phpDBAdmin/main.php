<?
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

global $PHPSESSID;

if (!isset($message)) 
   include("modules/$V_dir/header.inc.php");
else
   show_message($message);

?>

<?php
	echo "<b>SQL $strRunning " . _DEF_dbhost;
	echo "</b><br>\n";

if (isset($mode) && ($mode == "reload")) {
     $result = sql_query("FLUSH PRIVILEGES");
     if ($result != 0) {
       echo "<b>$strSQLReloaded</b>";
     } else {
       echo "<b>$strReloadFailed</b>";
     }
}

echo "<ul>";
?>
</ul>
<?
if(!get_magic_quotes_gpc()) print($strEnableMagicQuotes);
require ("modules/$V_dir/footer.inc.php");
?>
