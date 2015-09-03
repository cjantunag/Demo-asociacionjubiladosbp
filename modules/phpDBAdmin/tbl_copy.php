<?
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

require_once("modules/$V_dir/header.inc.php");
global $RAD_dbi;

function m_handler($sql_insert) {
    global $table, $db, $new_name, $RAD_dbi;
    
    $sql_insert = ereg_replace("INSERT INTO $table", "INSERT INTO $new_name", $sql_insert);
    $result = sql_query($sql_insert, $RAD_dbi);
    $sql_query = $sql_insert;
}

$sql_structure = get_table_def($db, $table, "m_handler", $what);
$sql_structure = ereg_replace("CREATE TABLE $table", "CREATE TABLE $new_name", $sql_structure);
die($db.$table.$sql_structure);

$result = sql_query($sql_structure, $RAD_dbi);
$sql_query .= "\n$sql_structure";

if($what == "data" || $what == "data1") get_table_content($db, $table, "m_handler", $what);

eval("\$message = \"$strCopyTableOK\";");

include("modules/$V_dir/db_details.php");
?>
