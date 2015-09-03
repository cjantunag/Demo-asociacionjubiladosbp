<?
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");
global $PHPSESSID;

if(isset($result)) $num_fields=get_num_fields($table);
else $num_fields=1;

?>

<form method="post" action="index.php">
<input type="hidden" name="PHPSESSID" value="<?=$PHPSESSID?>">
<input type="hidden" name="V_dir" value="<?=$V_dir?>">
<input type="hidden" name="V_mod" value="<?=$V_mod?>">
<input type="hidden" name="func" value="<?=$action?>">
<input type="hidden" name="db" value="<?=$db?>">
<input type="hidden" name="table" value="<?=$table?>">
<?php
if($action == "tbl_create") {
	echo "<input type=hidden name=subfunc value='tbl_properties'>";
} elseif($action == "tbl_addfield") {
	echo '<input type="hidden" name="after_field" value="'.$after_field."\">\n";
}
?>
<table border=<?php echo $cfgBorder;?>>
<tr>
<th><?php echo $strField; ?></th>
<th><?php echo $strType; ?></th>
<th><?php echo $strLengthSet; ?></th>
<th><?php echo $strAttr; ?></th>
<th><?php echo $strNull; ?></th>
<th><?php echo $strDefault; ?></th>
<th><?php echo $strExtra; ?></th>

<?php
if($action == "tbl_create" || $action == "tbl_addfield")
{
    ?>
    <th><?php echo $strPrimary; ?></th>
    <th><?php echo $strIndex; ?></th>
    <th><?php echo $strUnique; ?></th>
    <?php
}
?>
</tr>

<?php
$k=0;
for($i=0 ; $i<$num_fields; $i++) {
    if(isset($result))
        $row = sql_fetch_array($result);
        if ($field!="" && $row["COLUMN_NAME"]!=$field) continue;
        if ($row["COLUMN_NAME"]==$A_fields[($k-1)]) continue; // oracle
        if ($row["COLUMN_NAME"]!="") $row["Field"]=$row["COLUMN_NAME"];
        if ($row["DATA_TYPE"]!="") $row["Type"]=$row["DATA_TYPE"];
        if ($row["DATA_LENGTH"]!="") $row["Length"]=$row["DATA_LENGTH"];
        if ($row["DATA_DEFAULT"]!="") $row["Default"]=$row["DATA_DEFAULT"];
        $A_fields[$k]=$row["Field"];
	$k++;

    $bgcolor = $cfgBgcolorOne;
    $i % 2  ? 0: $bgcolor = $cfgBgcolorTwo;
    ?>
    <tr bgcolor="<?php echo $bgcolor;?>">
    <td>
      <input type="text" name="field_name[]" size="10" value="<?php if(isset($row) && isset($row["Field"])) echo $row["Field"];?>">
      <input type="hidden" name="field_orig[]" value="<?php if(isset($row) && isset($row["Field"])) echo $row["Field"];?>"></td>
    <td><select name="field_type[]">
    <?php
    $row["Type"] = empty($row["Type"]) ? '' : $row["Type"];
    $Type = stripslashes($row["Type"]);
    $Type = eregi_replace("BINARY", "", $Type);
    $Type = eregi_replace("ZEROFILL", "", $Type);
    $Type = eregi_replace("UNSIGNED", "", $Type);
//  $Length = eregi_replace("$Type|\\(|\\)", "", $Type);
//  Strange: $Type would always match $Type, so replaced with version below:
//  I Huneke (Logica) 29 September 1999
    #$Length = eregi_replace("\\(|\\)", "", $Type);
    $Length = $Type;
    $Type = eregi_replace("\\(.*\\)", "", $Type);
    $Type = chop($Type);
//  Add test to ensure we're not trying to replace blank with blank
//  I Huneke (Logica) 29 September 1999
    if(!empty($Type))
    {
        $Length = eregi_replace("^$Type\(", "", $Length);
        $Length = eregi_replace("\)$", "", trim($Length));
    }
    $Length = htmlspecialchars(chop($Length));
    if($Length == $Type)
        $Length = "";
    for($j=0;$j<count($cfgColumnTypes);$j++)
    {
        echo "<option value=$cfgColumnTypes[$j]";
        if(strtoupper($Type) == strtoupper($cfgColumnTypes[$j]))
            echo " selected";
        echo ">$cfgColumnTypes[$j]</option>\n";
    }
    ?>
     </select>
    </td>
    <td><input type="text" name="field_length[]" size="8" value="<?php echo $Length;?>"></td>
    <td><select name="field_attribute[]">
    <?php
    $binary   = eregi("BINARY", $row["Type"], $test_attribute1);
    $unsigned = eregi("UNSIGNED", $row["Type"], $test_attribute2);
    $zerofill = eregi("ZEROFILL", $row["Type"], $test_attribute3);
    $strAttribute = "";
    if($binary)
        $strAttribute="BINARY";
    if($unsigned)
        $strAttribute="UNSIGNED";
    if($zerofill)
        $strAttribute="UNSIGNED ZEROFILL";
    for($j=0;$j<count($cfgAttributeTypes);$j++)
    {
        echo "<option value=\"$cfgAttributeTypes[$j]\"";
        if(strtoupper($strAttribute) == strtoupper($cfgAttributeTypes[$j]))
            echo " selected";
        echo ">$cfgAttributeTypes[$j]</option>\n";
    }
    ?>
    </select></td>
    <td><select name="field_null[]">
    <?php
    if(!isset($row) || !isset($row["Null"]) || $row["Null"] == "")
    {
        ?>
        <option value=" not null">not null</option>
        <option value="">null</option>
        <?php
    }
    else
    {
        ?>
        <option value="">null</option>
        <option value="not null">not null</option>
        <?php
    }
    ?>
    </select></td>
    <td><input type="text" name="field_default[]" size="8" value="<?php if(isset($row) && isset($row["Default"])) echo $row["Default"];?>"></td>
    <td><select name="field_extra[]">
    <?php
    if(!isset($row) || !isset($row["Extra"]) || $row["Extra"] == "")
    {
        ?>
        <option value=""></option>
        <option value="AUTO_INCREMENT">auto_increment</option>
        <?php
    }
    else
    {
        ?>
        <option value="AUTO_INCREMENT">auto_increment</option>
        <option value=""></option>
        <?php
    }
    ?>
    </select></td>

    <?php
    if($action == "tbl_create" || $action == "tbl_addfield")
    {
        ?>
        <td align="center">
        <input type="checkbox" name="field_primary[]" value="<?php echo $i;?>"
        <?php
        if(isset($row) && isset($row["Key"]) && $row["Key"] == "PRI")
            echo "checked";
        ?>
        >
        </td>
        <td align="center">
        <input type="checkbox" name="field_index[]" value="<?php echo $i;?>"
        <?php
        if(isset($row) && isset($row["Key"]) && $row["Key"] == "MUL")
            echo "checked";
        ?>
        >
        </td>
        <td align="center">
            <input type="checkbox" name="field_unique[]" value="<?php echo $i;?>"
        <?php
        if(isset($row) && isset($row["Key"]) && $row["Key"] == "UNI")
            echo "checked";
        ?>
        >
        </td>
        <?php
    }
    ?>
    </tr>
    <?php
}
?>
</table>
<p>
<input ACCESSKEY='S' TITLE='ALT+S' type="submit" name="submit" value="<?php echo $strSave;?>">
</p>
</form>
<center><?php print show_docu("manual_Reference.html#Create_table");?></center>

<?
//-------------------------------------------------------------------
function get_num_fields($table) {
	global $RAD_dbi;

	$num_fields=0;
	$TMP_res=sql_list_fields($table,$RAD_dbi);
	while($TMP_row=sql_fetch_array($TMP_res,$RAD_dbi)) {
		if ($TMP_row[0]==$A_fields[($num_fields-1)]) continue;
		$A_fields[$num_fields]=$TMP_row[0];
		$num_fields++;
	}
	return $num_fields;
}
?>
