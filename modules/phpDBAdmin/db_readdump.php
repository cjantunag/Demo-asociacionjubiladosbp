<?
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

@set_time_limit(10000);

function split_sql($sql) {
	global $RAD_dbi;
    $sql = trim($sql);
    $sql = ereg_replace("#[^\n]*\n", "", $sql);
    $buffer = array();
    $ret = array();
    $in_string = false;

    for($i=0; $i<strlen($sql)-1; $i++) {
        if($sql[$i] == ";" && !$in_string) {
            $ret[] = substr($sql, 0, $i);
            $sql = substr($sql, $i + 1);
            $i = 0;
        }

        if($in_string && ($sql[$i] == $in_string) && $buffer[0] != "\\") {
             $in_string = false;
        } elseif(!$in_string && ($sql[$i] == "\"" || $sql[$i] == "'") && (!isset($buffer[0]) || $buffer[0] != "\\")) {
             $in_string = $sql[$i];
        }

        if(isset($buffer[1])) $buffer[0] = $buffer[1];

        $buffer[1] = $sql[$i];
    }

    if(!empty($sql)) $ret[] = $sql;
    return($ret);
}

if(!empty($sql_file) && $sql_file != "none" && ereg("^php[0-9A-Za-z_.-]+$", basename($sql_file)))
    $sql_query = addslashes(fread(fopen($sql_file, "r"), filesize($sql_file)));

$pieces  = split_sql($sql_query);

if (count($pieces) == 1 && !empty($pieces[0])) {
   $sql_query = trim($pieces[0]);
   include ("modules/$V_dir/sql.php");
   exit;
}

include("modules/$V_dir/header.inc.php");
for ($i=0; $i<count($pieces); $i++) {
    $pieces[$i] = stripslashes(trim($pieces[$i]));
    if(!empty($pieces[$i]) && $pieces[$i] != "#")
        $result = sql_query ($pieces[$i], $RAD_dbi);
}

$sql_query = stripslashes($sql_query);
$message = $strSuccess;

include("modules/$V_dir/db_details.php");

?>
