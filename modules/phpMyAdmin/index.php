<?php
//if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");
if (eregi("modules/", $PHP_SELF)) die ("Security Error ...");

global $db;

if (substr($db,0,strlen(_DEF_dbname))==_DEF_dbname) $db=$db;
else $db=_DEF_dbname;	// only RAD's database
include_once("modules/$V_dir/lib.inc.php");
include_once("modules/$V_dir/lib.tbl.php");
if (substr($db,0,strlen(_DEF_dbname))==_DEF_dbname) $db=$db;
else $db=_DEF_dbname;	// only RAD's database

if ($func =="db_dump") {
	include("modules/$V_dir/db_dump.php");
	exit;
}
if ($func =="tbl_dump") {
	include("modules/$V_dir/tbl_dump.php");
	exit;
}

include_once("header.php");
?>

<table border="0" cellpadding=0 cellspacing=0 width=100%><tr>
<? 
if ($func !="main") {
	echo "<td width=150 bgcolor=#fafafa valign=top>\n";
	include("modules/$V_dir/left.php"); 
	echo "</td>\n";
}
?>
<td valign=top>
<?
if ($func =="sql") include("modules/$V_dir/sql.php");
elseif ($func =="db_backup") include("modules/$V_dir/db_backup.php");
elseif ($func =="db_create") include("modules/$V_dir/db_create.php");
elseif ($func =="db_dump") include("modules/$V_dir/db_dump.php");
elseif ($func =="db_readdump") include("modules/$V_dir/db_readdump.php");
elseif ($func =="db_printview") include("modules/$V_dir/db_printview.php");
elseif ($func =="ldi_check") include("modules/$V_dir/ldi_check.php");
elseif ($func =="ldi_table") include("modules/$V_dir/ldi_table.php");
elseif ($func =="tbl_replace") tbl_replace();
elseif ($func =="tbl_rename") tbl_rename();
elseif ($func =="tbl_addfield") include("modules/$V_dir/tbl_addfield.php");
elseif ($func =="tbl_alter") include("modules/$V_dir/tbl_alter.php");
elseif ($func =="tbl_change") include("modules/$V_dir/tbl_change.php");
elseif ($func =="tbl_copy") include("modules/$V_dir/tbl_copy.php");
elseif ($func =="tbl_create") include("modules/$V_dir/tbl_create.php");
elseif ($func =="tbl_dump") include("modules/$V_dir/tbl_dump.php");
elseif ($func =="tbl_printview") include("modules/$V_dir/tbl_printview.php");
elseif ($func =="tbl_qbe") include("modules/$V_dir/tbl_qbe.php");
elseif ($func =="tbl_select") tbl_select();

if ($func =="tbl_relations") include("modules/$V_dir/tbl_relations.php");

if ($func =="tbl_properties") include("modules/$V_dir/tbl_properties.php");
elseif ($func =="db_details") include("modules/$V_dir/db_details.php");
elseif ($func =="main" || $func=="") include("modules/$V_dir/main.php"); 
?>

</td></tr></table>
<?
if ($dbname!="" && substr($dbname,0,strlen(_DEF_dbname))==_DEF_dbname) {
        $RAD_dbi = sql_connect(_DEF_dbhost, _DEF_dbuname, _DEF_dbpass, $dbname);
} else {
        $RAD_dbi = sql_connect(_DEF_dbhost, _DEF_dbuname, _DEF_dbpass, _DEF_dbname);
}
include_once("footer.php");
?>
