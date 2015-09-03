<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: ../../index.php");
    die();
}


if ($dbtype=="") $dbtype = strtolower(_DEF_dbtype);

if (strtolower($dbtype)=="oracle") include_once ("modules/".$V_dir."/dborasql.php");
else include_once ("modules/".$V_dir."/dbmysql.php");

?>
