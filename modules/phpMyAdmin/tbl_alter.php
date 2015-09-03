<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

global $submit, $query, $field_orig, $field_name, $field_type, $field_length, $field_default, $field_primary;
global $field_index, $field_unique, $field_attribute, $field_null, $field_extra;

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
   $result = mysql_db_query($db, "ALTER TABLE $table CHANGE $query") or mysql_die();
   $message = "$strTable $table $strHasBeenAltered";
   include("modules/$V_dir/tbl_properties.php");
   exit;
} else {
    $result = mysql_db_query($db, "SHOW FIELDS FROM $table LIKE '$field'") or mysql_die();
    $num_fields = mysql_num_rows($result);
    $action = "tbl_alter";
    include("modules/$V_dir/tbl_properties.inc.php");
}

require_once("modules/$V_dir/footer.inc.php");
?>
