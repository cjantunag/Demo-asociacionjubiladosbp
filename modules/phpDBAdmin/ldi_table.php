<?
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");
global $PHPSESSID;

// This file inserts a textfile into a table

require("modules/$V_dir/header.inc.php");

$tables = sql_list_tables($db);
$num_tables=0;
while($row=sql_fetch_array($tables, $RAD_dbi)) { $A_tables[$num_tables]=$row[0]; $num_tables++; }
?>

<form action="index.php" method="post"  enctype="multipart/form-data">
<input type="hidden" name="PHPSESSID" value="<?=$PHPSESSID?>">
<input type="hidden" name="V_dir" value="<?=$V_dir?>">
<input type="hidden" name="V_mod" value="<?=$V_mod?>">
<input type="hidden" name="func" value="ldi_check">
<input type="hidden" name="subfunc" value="tbl_properties">
<table border="1">
<tr>
	<td><?php echo $strLocationTextfile; ?></td>
	<td colspan=2><input type="file" name="textfile"></td>
</tr>
<tr>
	<td><?php echo $strReplaceTable; ?></td>
	<td><input type="checkbox" name="replace" value="REPLACE"><?php echo $strReplace; ?></td>
	<td><?php echo $strTheContents; ?></td>
</tr>	
<tr>
	<td><?php echo $strFields; ?><br><?php echo $strTerminatedBy; ?></td>
	<td><input type="text" name="field_terminater" size="2" maxlength="2" value=";"></td>
	<td><?php echo $strTheTerminator; ?></td>
</tr>
<tr>
	<td><?php echo $strFields; ?><br><?php echo $strEnclosedBy; ?></td>
	<td><input type="text" name="enclosed" size="1" maxlength="1" value="&quot;">
		<input type="Checkbox" name="enclose_option" value="OPTIONALLY"><?php echo $strOptionally; ?>
	</td>
	<td><?php echo $strOftenQuotation; ?></td>
</tr>
<tr>
	<td><?php echo $strFields; ?><br><?php echo $strEscapedBy; ?></td>
	<td><input type="text" name="escaped" size="2" maxlength="2" value="\\"></td>
	<td><?php echo $strOptionalControls; ?></td>
</tr>
<tr>
	<td><?php echo $strLines; ?><br><?php echo $strTerminatedBy; ?></td>
	<td><input type="text" name="line_terminator" size="8" maxlength="8" value="\n"></td>
	<td><?php echo $strCarriage; ?><br><?php echo $strLineFeed; ?></td>
</tr>
<tr>
	<td><?php echo $strColumnNames; ?></td>
	<td><input type="text" name="column_name"></td>
	<td><?php echo $strIfYouWish; ?></td>
</tr>
<tr>
	<td colspan="3" align="center"><?php print show_docu("manual_Reference.html#Load");?></td>
</tr>
<tr>
	<td colspan="3" align="center">
	<input type="Hidden" name="db" value="<?php echo $db; ?>">
	<input type="Hidden" name="table" value="<?php echo $table; ?>">
	<input type="Hidden" name="zero_rows" value="<?php echo $strTheContent; ?>">
	<input type="Hidden" name="into_table" value="<?php echo $table; ?>">
	<input ACCESSKEY='S' TITLE='ALT+S' type="Submit" name="btnLDI" value=" <?php echo $strSubmit; ?> ">&nbsp;&nbsp;
	<input type="Reset" value=" <?php echo $strReset; ?> ">
	</td>
</tr>
</table>

</form>

<?php

require ("modules/$V_dir/footer.inc.php");
?>