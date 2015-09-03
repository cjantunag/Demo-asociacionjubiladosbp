<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

// -----------------------------------------------------------------
function tbl_select() {
	global $PHPSESSID, $PHP_SELF, $param, $db, $table, $V_dir, $V_mod, $func, $strSelectFields, $strDisplay, $strLimitNumRows, $strAddSearchConditions;
	global $strDoAQuery, $strField, $strType, $strValue, $strGo, $cfgMaxRows, $sessionMaxRows, $SESSION_SID;
if(!isset($param) || $param[0] == "") {
   require_once("modules/$V_dir/header.inc.php");
   $num_fields=0;
   $fld_results = sql_list_fields($table, $RAD_dbi);
   while($row=sql_fetch_array($fld_results, $RAD_dbi)) { if ($row[0]==$A_fields[($num_fields-1)]) continue; $A_fields[$num_fields]=$row[0]; $num_fields++; }

   if (!$num_fields>0) {
      die(sql_error());
   } else {
     ?>
       <form method="GET" ACTION="index.php">
       <input type="hidden" name="PHPSESSID" value="<?=$PHPSESSID?>">
       <input type="hidden" name="V_dir" value="<?=$V_dir?>">
       <input type="hidden" name="V_mod" value="<?=$V_mod?>">
       <input type="hidden" name="func" value="tbl_select">
       <input type="hidden" name="db" value="<?php echo $db;?>">
       <input type="hidden" name="table" value="<?php echo $table;?>">
       <?php echo $strSelectFields; ?><br>
       <select multiple NAME="param[]" size="10">
       <?php
	for ($i=0 ; $i<$num_fields; $i++) {
        $field = $A_fields[$i];
            if($i >= 0)
             echo "<option value=$field selected>$field</option>\n";
            else
             echo "<option value=$field>$field</option>\n";
           }
       ?>

       </select><br>
       <ul><li><?php echo $strDisplay; ?> <input type="text" size=4 name = "sessionMaxRows" value=<?php echo $cfgMaxRows; ?>>
                <?php echo $strLimitNumRows; ?>
       <li><?php echo $strAddSearchConditions; ?><br>
       <input type="text" name="where"> <?php print show_docu("manual_Reference.html#Functions");?><br>

   <br>
   <li><?php echo $strDoAQuery; ?><br>
   <table border="<?php echo $cfgBorder;?>">
   <tr>
   <th><?php echo $strField; ?></th>
   <th><?php echo $strType; ?></th>
   <th><?php echo $strValue; ?></th>
   </tr>
   <?php
   $num_fields=0;
   $fld_results = sql_list_fields($table, $RAD_dbi);
   while($row=sql_fetch_array($fld_results, $RAD_dbi)) { 
	if ($row[0]==$A_fields[($num_fields-1)]) continue;
	if ($row["COLUMN_NAME"]!="") $A_fields[$num_fields]=$row["COLUMN_NAME"];
	$A_fields[$num_fields]=$row["Field"];
	if ($row["DATA_TYPE"]!="") $A_field_type[$num_fields]=$row["DATA_TYPE"];
	else $A_field_type[$num_fields]=$row["Type"];
	if ($row["DATA_LENGTH"]!="") $A_field_len[$num_fields]=$row["DATA_LENGTH"];
	else $A_field_len[$num_fields]=$row["Length"];
	if ($row["DATA_DEFAULT"]!="") $A_field_default=$row["DATA_DEFAULT"];
	else $A_field_default=$row["Default"];
	$num_fields++;
   }
   for ($i=0;$i<$num_fields;$i++) {
       $field = $A_fields[$i];;
       $type = $A_field_type[$i];
       $len = $A_field_len[$i];
       $bgcolor = $cfgBgcolorOne;
       $i % 2  ? 0: $bgcolor = $cfgBgcolorTwo;
       echo "<tr bgcolor=".$bgcolor.">";
       echo "<td>$field</td>";
       echo "<td>$type</td>";
       echo "<td><input type=text name=fields[] style=\"width: ".$cfgMaxInputsize."\" maxlength=".$len."></td>\n";
       echo "<input type=hidden name=names[] value=\"$field\">\n";
       echo "<input type=hidden name=types[] value=\"$type\">\n";
       echo "</tr>";
   }
   echo "</table><br>";
?>

       <input ACCESSKEY='S' TITLE='ALT+S' name="SUBMIT" value="<?php echo $strGo; ?>" type="SUBMIT">
     </form></ul>

  <?php
   }
   require_once("modules/$V_dir/footer.inc.php");
} else {
       $sql_query="SELECT $param[0]";
       $i=0;
       $c=count($param);
       while($i < $c)
         {
           if($i>0) $sql_query .= ",$param[$i]";
           $i++;
         }
       $sql_query .= " from $table";
       if ($where != "") {
		$sql_query .= " where $where";
       } else {
//		$sql_query .= " where 1";
		$sql_query .= " ";
       for ($i=0;$i<count($fields);$i++)
           {
        if (!empty($fields) && $fields[$i] != "") {
            $quot="";
               if ($types[$i]=="string"||$types[$i]=="blob") {
                $quot="\"";
                $cmp="like";
            } elseif($types[$i]=="date"||$types[$i]=="time") {
                $quot="\"";
                $cmp="=";
            } else {
                $cmp="=";
                $quot="";
                if (substr($fields[$i],0,1)=="<" || substr($fields[$i],0,1)==">") $cmp="";
            }
            $sql_query .= " and $names[$i] $cmp $quot$fields[$i]$quot";
        }
    }
       }
       echo "<script type=text/javascript>\ndocument.location='index.php?V_dir=$V_dir&V_mod=$V_mod&func=sql&sql_query=".urlencode($sql_query).$SESSION_SID."&subfunc=db_details&db=$db&table=$table&pos=0&sessionMaxRows=$sessionMaxRows';\n</script>\n";
}

}
// -----------------------------------------------------------------
function tbl_replace() {
	global $PHPSESSID, $PHP_SELF, $param, $db, $table, $V_dir, $V_mod, $func, $fields, $funcs, $primary_key, $pos, $subfunc, $sql_query;
	global $strModifications, $PHP_SELF, $SESSION_SID, $RAD_dbi;

if($sql_query == "") $sql_query="SELECT * FROM ".$table;
$page = "index.php?V_dir=$V_dir&V_mod=$V_mod&func=sql&subfunc=$subfunc&db=$db&table=$table&pos=$pos&sql_query=".urlencode($sql_query).$SESSION_SID;

reset($fields);
reset($funcs);

if(isset($primary_key)) {
    $primary_key = stripslashes($primary_key);
    $valuelist = '';
    while(list($key, $val) = each($fields)) {
        switch (strtolower($val)) {
	        case 'null':
			break;
	        case '$set$':
			$f = "field_$key";
			$val = "'".($$f?implode(',',$$f):'')."'";
			break;
	        default:
			if(get_magic_quotes_gpc()) $val = stripslashes ($val);
			$val = str_replace("'","''",$val);
			$val = str_replace("\\","\\\\",$val);
			$val = "'$val'";
			break;
	  }
        if(empty($funcs[$key]))
            $valuelist .= "$key = $val, ";
        else
            $valuelist .= "$key = $funcs[$key]($val), ";
    }
    $valuelist = ereg_replace(', $', '', $valuelist);
    $query = "UPDATE $table SET $valuelist WHERE $primary_key";
} else {
    $fieldlist = '';
    $valuelist = '';
    while(list($key, $val) = each($fields)) {
        $fieldlist .= "$key, ";
        switch (strtolower($val)) {
	        case 'null':
		        break;
	        case '$set$':
		        $f = "field_$key";
		        $val = "'".($$f?implode(',',$$f):'')."'";
		        break;
	        default:
                $val = "'$val'";
		        break;
        }
        if(empty($funcs[$key]))
            $valuelist .= "$val, ";
        else
            $valuelist .= "$funcs[$key]($val), ";
    }
    $fieldlist = ereg_replace(', $', '', $fieldlist);
    $valuelist = ereg_replace(', $', '', $valuelist);
    $query = "INSERT INTO $table ($fieldlist) VALUES ($valuelist)";
}

$sql_query = $query;
$result = sql_query($query, $RAD_dbi);

if(!$result) {
    $error = sql_error();
    include_once("modules/$V_dir/header.inc.php");
    die($error);
} else {
    if(file_exists($func.".php")) {
        include_once("modules/$V_dir/header.inc.php");
        $message = $strModifications;
        include("modules/$V_dir/".$func.".php");
    }
    else echo "<script type=text/javascript>\n document.location='$page';\n</script>\n";
    exit;
}

}
// -----------------------------------------------------------------
function tbl_rename() {
	global $PHPSESSID, $PHP_SELF, $param, $db, $table, $V_dir, $V_mod, $func;
	global $new_name, $strRenameTableOK, $SESSION_SID, $RAD_dbi;

$old_name = $table;
$table = $new_name;
require_once("modules/$V_dir/header.inc.php");

$result = sql_query("ALTER TABLE $old_name RENAME $new_name", $RAD_dbi);
$table = $old_name;
eval("\$message =  \"$strRenameTableOK\";");
$table = $new_name;

$func="tbl_properties";
//include_once("modules/$V_dir/tbl_properties.php");
}
?>
