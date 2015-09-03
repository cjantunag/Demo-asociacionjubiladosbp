<?php
global $PHPSESSID, $PHP_SELF;
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

if(!isset($message)) {
    include_once("modules/$V_dir/header.inc.php");
} else {
    show_message($message);
}

include_once("modules/$V_dir/lib.inc.php");

$tables = mysql_list_tables($db);
$num_tables = @mysql_num_rows($tables);

if($num_tables == 0) {
    echo $strNoTablesFound;
} else {
    $i = 0;

    echo "<table border=$cfgBorder>\n";
    echo "<th>".strtoupper($strTable)."</th>";
    echo "<th colspan=6>$strAction</th>";
    echo "<th>$strRecords</th>";
    while($i < $num_tables) {
        $table = mysql_tablename($tables, $i);
        $query = "server=$server&db=$db&table=$table";
        $bgcolor = $cfgBgcolorOne;
        $i % 2  ? 0: $bgcolor = $cfgBgcolorTwo;
        ?>
         <tr bgcolor="<?php echo $bgcolor;?>">
           <td class=data><b><?php echo $table;?></b></td>
           <td><a href="?V_dir=<?=$V_dir?>&V_mod=<?=$V_mod?>&func=sql&<?php echo $query.$SESSION_SID;?>&sql_query=<?php echo urlencode("SELECT * FROM $table");?>&pos=0"><?php echo $strBrowse; ?></a></td>
           <td><a href="?V_dir=<?=$V_dir?>&V_mod=<?=$V_mod?>&func=tbl_select&<?php echo $query.$SESSION_SID;?>"><?php echo $strSelect; ?></a></td>
           <td><a href="?V_dir=<?=$V_dir?>&V_mod=<?=$V_mod?>&func=tbl_change&<?php echo $query.$SESSION_SID;?>"><?php echo $strInsert; ?></a></td>
           <td><a href="?V_dir=<?=$V_dir?>&V_mod=<?=$V_mod?>&func=tbl_properties&<?php echo $query.$SESSION_SID;?>"><?php echo $strProperties; ?></a></td>
           <td><a href="?V_dir=<?=$V_dir?>&V_mod=<?=$V_mod?>&func=sql&<?php echo $query.$SESSION_SID;?>&subfunc=db_details&sql_query=<?php echo urlencode("DROP TABLE $table");?>&zero_rows=<?php echo urlencode($strTable." ".$table." ".$strHasBeenDropped);?>"><?php echo $strDrop; ?></a></td>
           <td><a href="?V_dir=<?=$V_dir?>&V_mod=<?=$V_mod?>&func=sql&<?php echo $query.$SESSION_SID;?>&sql_query=<?php echo urlencode("DELETE FROM $table");?>&zero_rows=<?php echo urlencode($strTable." ".$table." ".$strHasBeenEmptied);?>"><?php echo $strEmpty; ?></a></td>
           <td align="right">&nbsp;<?php count_records($db,$table) ?></td>
         </tr>
        <?php
        $i++;
    }

    echo "</table>\n";
}
$query = "server=$server&db=$db";
?>
<hr>
<ul>
<li><a href="?V_dir=<?=$V_dir?>&V_mod=<?=$V_mod?>&func=db_printview&<?php echo $query.$SESSION_SID;?>"><?php echo $strPrintView;?></a>
<li>
<form method="post" action="index.php" enctype="multipart/form-data">
<input type="hidden" name="PHPSESSID" value="<?=$PHPSESSID?>">
<input type="hidden" name="V_dir" value="<?=$V_dir?>">
<input type="hidden" name="V_mod" value="<?=$V_mod?>">
<input type="hidden" name="func" value="db_readdump">
<input type="hidden" name="server" value="<?php echo $server;?>">
<input type="hidden" name="pos" value="0">
<input type="hidden" name="db" value="<?php echo $db;?>">
<input type="hidden" name="zero_rows" value="<?php echo $strSuccess; ?>">
<?php echo $strRunSQLQuery.$db." ".show_docu("manual_Reference.html#Select");?>:<br>
<textarea name="sql_query" cols="40" rows="3" wrap="VIRTUAL" style="width: <?php
echo $cfgMaxInputsize;?>"></textarea><br>
<?php echo "<i>$strOr</i> $strLocationTextfile";?>:<br>
<input type="file" name="sql_file"><br>
<input ACCESSKEY='S' TITLE='ALT+S' type="submit" name="SQL" value="<?php echo $strGo; ?>">
</form>
<li><a href="?V_dir=<?=$V_dir?>&V_mod=<?=$V_mod?>&func=tbl_qbe&<?php echo $query.$SESSION_SID;?>"><?php echo $strQBE;?></a>
<li><form method="post" action="index.php"><?php echo $strViewDumpDB;?><br>
<input type="hidden" name="PHPSESSID" value="<?=$PHPSESSID?>">
<input type="hidden" name="V_dir" value="<?=$V_dir?>">
<input type="hidden" name="V_mod" value="<?=$V_mod?>">
<input type="hidden" name="func" value="db_dump">
<table>
    <tr>
        <td>
            <input type="radio" name="what" value="structure" checked><?php echo $strStrucOnly;?>
        </td>
        <td>
            <input type="checkbox" name="drop" value="1"><?php echo $strStrucDrop;?>
        </td>
        <td colspan="2">
            <input type="submit" value="<?php echo $strGo;?>">
        </td>
    </tr>
    <tr>
        <td>
            <input type="radio" name="what" value="data"><?php echo $strStrucData;?><br>
            <input type="radio" name="what" value="data1"><?php echo $strStruc1Data;?>
        </td>
        <td>
            <input type="checkbox" name="asfile" value="sendit"><?php echo $strSend;?>
        </td>
    </tr>
    <tr>
        <td>
        </td>
        <td>
           <input type="checkbox" name="showcolumns" value="yes"><?php echo $strCompleteInserts; ?>
        </td>
    </tr>
</table>
<input type="hidden" name="server" value="<?php echo $server;?>">
<input type="hidden" name="db" value="<?php echo $db;?>">
</form>
<li>
<form method="post" action="index.php">
<input type="hidden" name="PHPSESSID" value="<?=$PHPSESSID?>">
<input type="hidden" name="V_dir" value="<?=$V_dir?>">
<input type="hidden" name="V_mod" value="<?=$V_mod?>">
<input type="hidden" name="func" value="tbl_create">
<input type="hidden" name="server" value="<?php echo $server;?>">
<input type="hidden" name="db" value="<?php echo $db;?>">
<?php echo $strCreateNewTable.$db;?>:<br>
<?php echo $strName.":"; ?> <input type="text" name="table"><br>
<?php echo $strFields.":"; ?> <input type="text" name="num_fields" size=2>
<input type="submit" value="<?php echo $strGo; ?>">
</form>

<li>
<a href="?V_dir=<?=$V_dir?>&V_mod=<?=$V_mod?>&func=sql&server=<?php echo $server.$SESSION_SID;?>&db=<?php echo $db;?>&sql_query=<?php echo urlencode("DROP DATABASE $db");?>&zero_rows=<?php echo urlencode($strDatabase." ".$db." ".$strHasBeenDropped);?>&subfunc=db_details"><?php echo $strDropDB." ".$db;?></a> <?php print show_docu("manual_Reference.html#Drop_database");?>

<li>
<form method="post" action="index.php">
<input type="hidden" name="PHPSESSID" value="<?=$PHPSESSID?>">
<input type="hidden" name="V_dir" value="<?=$V_dir?>">
<input type="hidden" name="V_mod" value="<?=$V_mod?>">
<input type="hidden" name="func" value="db_backup">
<input type="hidden" name="server" value="<?php echo $server;?>">
<input type="hidden" name="db" value="<?php echo $db;?>">
<?php echo $strBackup." ".$db;?>:<br>
<?php echo $strSufix.":"; ?> <input type="text" name="sufix">&nbsp;
<input type="submit" value="<?php echo $strGo; ?>">
</form>


</ul>
<?php

require_once("modules/$V_dir/footer.inc.php");
?>
