<?
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

require_once("modules/$V_dir/header.inc.php");
global $PHPSESSID, $RAD_dbi;

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

if (strtolower(_DEF_dbtype)=="oracle") $table_def=sql_query("DESCRIBE ".$table, $RAD_dbi);
else $table_def=sql_query("SHOW FIELDS FROM ".$table, $RAD_dbi);

if(isset($primary_key)) {
    $primary_key = stripslashes($primary_key);
    $result = sql_query("SELECT * FROM $table WHERE $primary_key", $RAD_dbi);
    $row = sql_fetch_array($result);
} else {
    if (strtolower(_DEF_dbtype)=="oracle") $result = sql_query("SELECT * FROM $table", $RAD_dbi);
    else $result = sql_query("SELECT * FROM $table LIMIT 1", $RAD_dbi);
}

?>
<form method="post" action="index.php">
<input type="hidden" name="PHPSESSID" value="<?=$PHPSESSID?>">
<input type="hidden" name="V_dir" value="<?=$V_dir?>">
<input type="hidden" name="V_mod" value="<?=$V_mod?>">
<input type="hidden" name="func" value="tbl_replace">
<input type="hidden" name="db" value="<?=$db?>">
<input type="hidden" name="table" value="<?=$table?>">
<input type="hidden" name="subfunc" value="<?=$subfunc?>">
<input type="hidden" name="sql_query" value="<?php echo isset($sql_query) ? $sql_query : "";?>">
<input type="hidden" name="pos" value="<?php echo isset($pos) ? $pos : 0;?>">
<?php

if(isset($primary_key))
    echo '<input type="hidden" name="primary_key" value="' . htmlspecialchars($primary_key) . '">' . "\n";
?>
<table border="<?php echo $cfgBorder;?>">
<tr>
<th><?php echo $strField; ?></th>
<th><?php echo $strType; ?></th>
<th><?php echo $strFunction; ?></th>
<th><?php echo $strValue; ?></th>
</tr>
<?php

for($i=0;$i<$num_fields;$i++) {
    $field = $A_fields[$i];
    if(($A_field_type == "datetime") AND ($row[$field] == ""))
        $row[$field] = date("Y-m-d H:i:s", time());
    $len = $A_field_len[$i];

    $bgcolor = $cfgBgcolorOne;
    $i % 2  ? 0: $bgcolor = $cfgBgcolorTwo;
    echo "<tr bgcolor=".$bgcolor.">\n";
    echo "<td>$field</td>\n";
    switch (ereg_replace("\\(.*", "", $iA_field_type))
    {
        case "set":
            $type = "set";
            break;
        case "enum":
            $type = "enum";
            break;
        default:
            $type = $A_field_typei[$i];
            break;
    }
    echo "<td>$type</td>\n";
    echo "<td><select name=\"funcs[$field]\"><option>\n";
    for($j=0; $j<count($cfgFunctions); $j++)
        echo "<option>$cfgFunctions[$j]\n";
    echo "</select></td>\n";
    if(isset($row) && isset($row[$field]))
    {
        $special_chars = htmlspecialchars($row[$field]);
        $data = $row[$field];
    }
    else
    {
        $data = $special_chars = "";
    }

    if(strstr($A_field_type[$i], "text"))
    {
        echo "<td><textarea name=fields[$field] style=\"width:$cfgMaxInputsize;\" rows=5>$special_chars</textarea></td>\n";
    }
    elseif(strstr($A_field_type[$i], "enum"))
    {
        $set = str_replace("enum(", "", $A_field_type[$i]);
        $set = ereg_replace("\\)$", "", $set);

        $set = split_string($set, ",");
        echo "<td><select name=fields[$field]>\n";
        echo "<option value=\"\">\n";
        for($j=0; $j<count($set);$j++)
        {
            echo '<option value="'.substr($set[$j], 1, -1).'"';
            if($data == substr($set[$j], 1, -1) || ($data == "" && substr($set[$j], 1, -1) == $A_field_default[$i]))
                echo " selected";
            echo ">".htmlspecialchars(substr($set[$j], 1, -1))."\n";
        }
        echo "</select></td>";
    }
    elseif(strstr($A_field_type[$i], "set"))
    {
        $set = str_replace("set(", "", $A_field_type[$i]);
        $set = ereg_replace("\)$", "", $set);

        $set = split_string($set, ",");
        for($vals = explode(",", $data); list($t, $k) = each($vals);)
            $vset[$k] = 1;
        $size = min(4, count($set));
        echo "<td><input type=\"hidden\" name=\"fields[$field]\" value=\"\$set\$\">";
        echo "<select name=field_${field}[] size=$size multiple>\n";
        for($j=0; $j<count($set);$j++)
        {
            echo '<option value="'.htmlspecialchars(substr($set[$j], 1, -1)).'"';
            if($vset[substr($set[$j], 1, -1)])
                echo " selected";
            echo ">".htmlspecialchars(substr($set[$j], 1, -1))."\n";
        }
        echo "</select></td>";
    }
    else
    {
        echo "<td><input type=text name=fields[$field] value=\"".$special_chars."\" style=\"width:$cfgMaxInputsize;\" maxlength=$len></td>";
    }
    echo "</tr>\n";
}

echo "</table>";

?>
  <p>
  <input ACCESSKEY='S' TITLE='ALT+S' type="submit" value="<?php echo $strSave; ?>">
  </form>

<?php
require_once("modules/$V_dir/footer.inc.php");
?>
