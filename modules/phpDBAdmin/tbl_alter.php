<?
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

global $submit, $query, $field_orig, $field_name, $field_type, $field_length, $field_default, $field_primary;
global $field_index, $field_unique, $field_attribute, $field_null, $field_extra, $RAD_dbi;

require_once("modules/$V_dir/header.inc.php");

if(isset($submit)) {
    if(!isset($query)) 
        $query = "";
    $query .= " $field_orig[0] $field_name[0] $field_type[0] ";
    if($field_length[0] != "")
        $query .= "($field_length[0]) ";
    if($field_attribute[0] != "")
        $query .= "$field_attribute[0] ";
    if($field_default[0] != "")
        $query .= "DEFAULT '$field_default[0]' ";

   $query .= "$field_null[0] $field_extra[0]";
   $query = stripslashes($query);
   $sql_query = "ALTER TABLE $table CHANGE $query";
   $result = sql_query("ALTER TABLE $table CHANGE $query", $RAD_dbi);
   $message = "$strTable $table $strHasBeenAltered";
   include("modules/$V_dir/tbl_properties.php");
   exit;
} else {
    if (strtolower(_DEF_dbtype)=="oracle") $result=sql_query("select COLUMN_NAME,DATA_TYPE,DATA_LENGTH,DATA_DEFAULT from all_tab_columns where table_name='".$table."' order by column_id", $RAD_dbi);
    else $result=sql_query("SHOW FIELDS FROM ".$table, $RAD_dbi);
    $action = "tbl_alter";
    include("modules/$V_dir/tbl_properties.inc.php");
}

require_once("modules/$V_dir/footer.inc.php");
?>
