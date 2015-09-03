<?php
global $PHPSESSID, $PHP_SELF;
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

require_once("sqlDB.php");

require_once("modules/$V_dir/config.inc.php");

$dblist = array();
if (substr($db,0,strlen(_DEF_dbname))==_DEF_dbname) $dblist[0]=$db;
else $dblist[0] = _DEF_dbname;

// -----------------------------------------------------------------
function show_table_navigation($pos_next, $pos_prev, $dt_result) {
global $PHPSESSID, $SESSION_SID;
    global $pos, $cfgMaxRows, $db, $table, $sql_query, $sql_order, $sessionMaxRows, $SelectNumRows;
    global $strPos1, $strPrevious, $strShow, $strRowsFrom, $strEnd, $V_dir, $V_mod;

    ?>
     <!--  beginning of table navigation bar -->
     <table border=0><tr>
        <td>
        <form method=post
          onsubmit ="return <?php  echo ( $pos >= $cfgMaxRows ? "true" : "false" ); ?>"
          action="index.php">
<input type="hidden" name="PHPSESSID" value="<?=$PHPSESSID?>">
<input type="hidden" name="V_dir" value="<?=$V_dir?>">
<input type="hidden" name="V_mod" value="<?=$V_mod?>">
<input type="hidden" name="func" value="sql">
<input type="hidden" name="db" value="<?=$db?>">
<input type="hidden" name="table" value="<?=$table?>">
<input type="hidden" name="sql_order" value="<?=$sql_order?>">
<input type="hidden" name="sql_query" value="<?=$sql_query?>">
<input type="hidden" name="pos" value="0">
<input type="hidden" name="db" value="<?=$db?>">
<input type="hidden" name="sessionMaxRows" value="<?=$sessionMaxRows?>">
<input ACCESSKEY='S' TITLE='ALT+S' type=submit value="<?php echo $strPos1 . " &lt;&lt;" ; ?>" >
        </form>
        </td>
        <td>
        <form method=post onsubmit="return <?php  echo ( $pos >= $cfgMaxRows ? "true" : "false" ); ?>"
          action="index.php">
<input type="hidden" name="PHPSESSID" value="<?=$PHPSESSID?>">
<input type="hidden" name="V_dir" value="<?=$V_dir?>">
<input type="hidden" name="V_mod" value="<?=$V_mod?>">
<input type="hidden" name="func" value="sql">
<input type="hidden" name="db" value="<?=$db?>">
<input type="hidden" name="table" value="<?=$table?>">
<input type="hidden" name="sql_order" value="<?=$sql_order?>">
<input type="hidden" name="sql_query" value="<?=$sql_query?>">
<input type="hidden" name="pos" value="<?=$pos_prev?>">
<input type="hidden" name="sessionMaxRows" value="<?=$sessionMaxRows?>">
<input type=submit value="<?php echo $strPrevious ." &lt;" ; ?>"  >
        </form>
        </td>
    <td>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    </td>
    <td>
        <table><tr><td>
          <form method=post onsubmit="return isFormElementInRange;"
          action="index.php">
<input type="hidden" name="PHPSESSID" value="<?=$PHPSESSID?>">
<input type="hidden" name="V_dir" value="<?=$V_dir?>">
<input type="hidden" name="V_mod" value="<?=$V_mod?>">
<input type="hidden" name="func" value="sql">
<input type="hidden" name="db" value="<?=$db?>">
<input type="hidden" name="table" value="<?=$table?>">
<input type="hidden" name="sql_order" value="<?=$sql_order?>">
<input type="hidden" name="sql_query" value="<?=$sql_query?>">
<input type="hidden" name="sessionMaxRows" value="<?=$sessionMaxRows?>">
<input type=submit value="<?php echo "&gt; ". $strShow ; ?>"
                 onclick="checkFormElementInRange( this.form, 'pos', 0, <?php echo ( $SelectNumRows - 1 ); ?> )"
              >
              <input type="text" name="sessionMaxRows" size="3" value="<?php echo $sessionMaxRows ; ?>">
              <?php echo $strRowsFrom ?> 
<input name="pos" type="text" size="3" value="<?php echo ( $pos_next >= $SelectNumRows ? '' : $pos_next )  ; ?>">
          </form>
        </td></tr></table>
    </td>
    <td>
        <form method=post
          onsubmit="return <?php  echo
          ( isset($SelectNumRows) && $pos + $sessionMaxRows < $SelectNumRows && sql_num_rows($dt_result) >= $sessionMaxRows  ?
                    "true" : "false" ); ?>"
          action="index.php">
<input type="hidden" name="PHPSESSID" value="<?=$PHPSESSID?>">
<input type="hidden" name="V_dir" value="<?=$V_dir?>">
<input type="hidden" name="V_mod" value="<?=$V_mod?>">
<input type="hidden" name="func" value="sql">
<input type="hidden" name="db" value="<?=$db?>">
<input type="hidden" name="table" value="<?=$table?>">
<input type="hidden" name="sql_order" value="<?=$sql_order?>">
<input type="hidden" name="sql_query" value="<?=$sql_query?>">
<input type="hidden" name="pos" value="<?=$SelectNumRows - $sessionMaxRows?>">
<input type="hidden" name="sessionMaxRows" value="<?=$sessionMaxRows?>">
<input type=submit value="<?php echo "&gt;&gt; " . $strEnd  ; ?>"  >
        </form>
        </td>
    </tr></table>
    <!--  end of table navigation bar -->
    <?php
}

// -----------------------------------------------------------------
function load_javascript () {
    echo "
<script language=\"javascript\">
<!--
var isFormElementInRange;
function checkFormElementInRange (fform, name, min, max ) {
    isFormElementInRange  =  true;
    var val = parseInt( eval( \"fform.\" + name + \".value\"  ));
    if(isNaN(val)) {
        isFormElementInRange = false;
        return false;
    }
    if (val < min || val > max )  {
        alert( val +\" is not a valid row number!\" );
        isFormElementInRange = false;
        eval( \"fform.\"+ name + \".focus()\");
        eval( \"fform.\"+ name + \".select()\");
    }else {
    eval( \"fform.\"+ name + \".value = val\" );
    }
    return true;
}       
//-->
</script>\n";
}

// -----------------------------------------------------------------
function display_table ($dt_result) {
    global $cfgBorder, $cfgBgcolorOne, $cfgBgcolorTwo, $cfgMaxRows, $pos, $db, $table, $sql_query, $sql_order, $cfgOrder, $cfgShowBlob;
    global $strShowingRecords,$strSelectNumRows,$SelectNumRows,$strTotal,$strEdit,$strPrevious,$strNext,$strDelete,$strDeleted,$strPos1,$strEnd;
    global $sessionMaxRows, $strGo, $strShow, $strRowsFrom, $pos_next, $SESSION_SID, $V_dir, $V_mod, $RAD_dbi;

    $cfgMaxRows = isset($sessionMaxRows) ? $sessionMaxRows : $cfgMaxRows;
    $sessionMaxRows = isset($sessionMaxRows) ? $sessionMaxRows : $cfgMaxRows;

    load_javascript();

    $primary = false;
    if(!empty($table) && !empty($db))
    {
        $result = sql_query("SELECT COUNT(*) as total FROM $table", $RAD_dbi);
        $row = sql_fetch_array($result, $RAD_dbi);
        $total = $row["total"];
    }

    if(!isset($pos))
        $pos = 0;
    $pos_next = $pos + $cfgMaxRows;
    $pos_prev = $pos - $cfgMaxRows;

    if(isset($total) && $total>1)
    {
        if(isset($SelectNumRows) && $SelectNumRows!=$total)
            $selectstring = ", $SelectNumRows $strSelectNumRows";
        else
            $selectstring = "";
    $se = isset($se) ? $se : "";
    $lastShownRec = $pos_next  - 1;
    show_message("$strShowingRecords $pos - $lastShownRec  ($se$total $strTotal$selectstring)");
    }
    else
    {
        show_message($GLOBALS["strSQLQuery"]);
    }
    show_table_navigation($pos_next, $pos_prev, $dt_result);
    ?>

    <table border="<?php echo $cfgBorder;?>">

    <tr>
    <?php
    if($sql_query == "SHOW STATUS" || $sql_query == "SHOW VARIABLES") {
    } elseif($sql_query == "SHOW PROCESSLIST") {
	echo "<td>&nbsp;</td>\n";
    } else {
	echo "<td>&nbsp;</td><td>&nbsp;</td>";
    }
    $TMP_cont=0;
    if (strtolower(_DEF_dbtype)=="oracle") $res=sql_query("select COLUMN_NAME,DATA_TYPE,DATA_LENGTH,DATA_DEFAULT from all_tab_columns where table_name='".$table."' order by column_id", $RAD_dbi);
    else $res=sql_query("SHOW FIELDS FROM ".$table, $RAD_dbi);
    $num_fields=0;
    while($row = sql_fetch_array($res, $RAD_dbi)) {
        if ($row["COLUMN_NAME"]==$A_fields[($num_fields-1)]) continue;
        if ($row["COLUMN_NAME"]!="") $row["Field"]=$row["COLUMN_NAME"];
        if ($row["DATA_TYPE"]!="") $row["Type"]=$row["DATA_TYPE"];
        if ($row["DATA_LENGTH"]!="") $row["Length"]=$row["DATA_LENGTH"];
        if ($row["DATA_DEFAULT"]!="") $row["Default"]=$row["DATA_DEFAULT"];
        $A_fields[$num_fields]=$row["Field"];
        $num_fields++;
	$TMP_cont++;
        if($TMP_cont==1) {
            $sort_order=urlencode(" order by ".$field[0]." $cfgOrder");
            echo "<th>";
            if(!eregi("SHOW VARIABLES|SHOW PROCESSLIST|SHOW STATUS", $sql_query))
                echo "<A HREF=\"?V_dir=$V_dir&V_mod=$V_mod&func=sql&db=$db&pos=$pos&sql_query=".urlencode($sql_query)."&sql_order=$sort_order&table=$table$SESSION_SID\">";
            echo $field[0];
            if(!eregi("SHOW VARIABLES|SHOW PROCESSLIST|SHOW STATUS", $sql_query))
                echo "</a>";
            echo "</th>\n";
        }
        else
        {
            echo "<th>".$field[0]."</th>";
        }
    }
    echo "</tr>\n";
    $foo = 0;
    $A_showed[strtolower($TMP_k)]= array();
    while($row = sql_fetch_array($dt_result, $RAD_dbi)) {
	if ($foo>=$cfgMaxRows) break;
	if (strtolower(_DEF_dbtype)=="oracle" && $foo==0) {
		echo "<tr><td></td><td></td>\n"; 
		$TMP_contfield=0;
		foreach($row as $TMP_k=>$TMP_v) {
			if ($TMP_k==$TMP_contfield) {
				$TMP_contfield++;
				continue;
			}
			if ($A_showed[strtolower($TMP_k)]!="") continue;
			$A_showed[strtolower($TMP_k)]="x";
			echo "<th>".$TMP_k."</th>";
		}
		echo "</tr>\n"; 
	}
        $primary_key = "";
        $uva_nonprimary_condition = "";
        $bgcolor = $cfgBgcolorOne;
        $foo % 2  ? 0: $bgcolor = $cfgBgcolorTwo;
        echo "<tr bgcolor=$bgcolor>";
//////
        for($i=0; $i<sql_num_fields($dt_result); $i++) {
            if(!isset($row[$i])) $row[$i] = '';
            if (strtolower(_DEF_dbtype)!="oracle") $primary = sql_fetch_field($dt_result,$i);
            if($primary->numeric == 1) {
                if($sql_query == "SHOW PROCESSLIST") $Id = $row[$i];
            }
            if($primary->primary_key > 0) $primary_key .= " $primary->name = '".addslashes($row[$i])."' AND";
            //begin correction uva 19991216 pt. 2 ---------------------------
            //see pt. 1, above, for description of change
            $uva_nonprimary_condition .= " $primary->name = '".addslashes($row[$i])."' AND";
            //end correction uva 19991216 pt. 2 -----------------------------
        }
        //begin correction uva 19991216 pt. 3 ---------------------------
        //see pt. 1, above, for description of change
        //prefer primary keys for condition, but use conjunction of all values if no primary key
        if($primary_key) //use differently and include else
            $uva_condition = $primary_key;
        else
            $uva_condition = $uva_nonprimary_condition;

        //   { code no longer conditional on $primary_key
        //   $primary_key replaced with $uva_condition below
        $uva_condition = urlencode(ereg_replace("AND$", "", $uva_condition));
        $query = "&db=$db&table=$table&pos=$pos";
        if($sql_query == "SHOW STATUS" || $sql_query == "SHOW VARIABLES") {
        } elseif($sql_query == "SHOW PROCESSLIST") {
            echo "<td align=right><a href='?V_dir=$V_dir&V_mod=$V_mod&func=sql&db=sql&sql_query=".urlencode("KILL $Id").$SESSION_SID."'>KILL</a></td>\n";
	  } else {
        	echo "<td><a href=\"?V_dir=$V_dir&V_mod=$V_mod&func=tbl_change&primary_key=$uva_condition&$query&sql_query=".urlencode($sql_query).$SESSION_SID."\">".$strEdit."</a></td>";
        	echo "<td><a href=\"?V_dir=$V_dir&V_mod=$V_mod&func=sql&sql_query=".urlencode("DELETE FROM $table WHERE ").$uva_condition."&$query&".urlencode("?$query&sql_query=$sql_query")."zero_rows=".urlencode($strDeleted).$SESSION_SID."\">".$strDelete."</a></td>";
        	//   } code no longer condition on $primary_key
        	//end correction uva 19991216 pt. 3 -----------------------------
	  }
//////



        for($i=0; $i<sql_num_fields($dt_result); $i++) {
            if(!isset($row[$i])) $row[$i] = '';
            if (strtolower(_DEF_dbtype)!="oracle") $primary = sql_fetch_field($dt_result,$i);
            if($primary->numeric == 1) {
                echo "<td align=right>&nbsp;$row[$i]&nbsp;</td>\n";
                if($sql_query == "SHOW PROCESSLIST") $Id = $row[$i];
            } elseif($cfgShowBlob == false && eregi("BLOB", $primary->type)) {
                echo "<td align=right>&nbsp;[BLOB]&nbsp;</td>\n";
            } else {
                echo "<td>&nbsp;".htmlspecialchars($row[$i])."&nbsp;</td>\n";
            }
            if($primary->primary_key > 0) $primary_key .= " $primary->name = '".addslashes($row[$i])."' AND";
            //begin correction uva 19991216 pt. 2 ---------------------------
            //see pt. 1, above, for description of change
            $uva_nonprimary_condition .= " $primary->name = '".addslashes($row[$i])."' AND";
            //end correction uva 19991216 pt. 2 -----------------------------
        }
        echo "</tr>\n";
        $foo++;
    }
    echo "</table>\n";

    show_table_navigation($pos_next, $pos_prev, $dt_result);
}//display_table

// Return $table's CREATE definition
// Returns a string containing the CREATE statement on success
function get_table_def($db, $table, $crlf) {
    global $drop, $RAD_dbi;

    $schema_create = "";
    if(!empty($drop))
        $schema_create .= "DROP TABLE IF EXISTS $table;$crlf";
    $schema_create .= "CREATE TABLE $table ($crlf";
    if (strtolower(_DEF_dbtype)=="oracle") $res=sql_query("select COLUMN_NAME,DATA_TYPE,DATA_LENGTH,DATA_DEFAULT from all_tab_columns where table_name='".$table."' order by column_id", $RAD_dbi);
    else $res=sql_query("SHOW FIELDS FROM ".$table, $RAD_dbi);
    $num_fields=0;
    while($row = sql_fetch_array($res, $RAD_dbi)) {
	if ($row["COLUMN_NAME"]==$A_fields[($num_fields-1)]) continue;
	if ($row["COLUMN_NAME"]!="") $row["Field"]=$row["COLUMN_NAME"];
	if ($row["DATA_TYPE"]!="") $row["Type"]=$row["DATA_TYPE"];
	if ($row["DATA_LENGTH"]!="") $row["Length"]=$row["DATA_LENGTH"];
	if ($row["DATA_DEFAULT"]!="") $row["Default"]=$row["DATA_DEFAULT"];
	$A_fields[$num_fields]=$row["Field"];
	$num_fields++;
        $schema_create .= "   $row[Field] $row[Type]";
        if(isset($row["Default"]) && (!empty($row["Default"]) || $row["Default"] == "0"))
            $schema_create .= " DEFAULT '$row[Default]'";
        if($row["Null"] != "YES")
            $schema_create .= " NOT NULL";
        if($row["Extra"] != "")
            $schema_create .= " $row[Extra]";
        $schema_create .= ",$crlf";
    }
    $schema_create = ereg_replace(",".$crlf."$", "", $schema_create);
    $result = sql_query("SHOW KEYS FROM $table", $RAD_dbi);
    while($row = sql_fetch_array($result, $RAD_dbi))
    {
        $kname=$row['Key_name'];
        if(($kname != "PRIMARY") && ($row['Non_unique'] == 0))
            $kname="UNIQUE|$kname";
         if(!isset($index[$kname]))
             $index[$kname] = array();
         $index[$kname][] = $row['Column_name'];
    }

    while(list($x, $columns) = @each($index))
    {
         $schema_create .= ",$crlf";
         if($x == "PRIMARY")
             $schema_create .= "   PRIMARY KEY (" . implode($columns, ", ") . ")";
         elseif (substr($x,0,6) == "UNIQUE")
            $schema_create .= "   UNIQUE ".substr($x,7)." (" . implode($columns, ", ") . ")";
         else
            $schema_create .= "   KEY $x (" . implode($columns, ", ") . ")";
    }

    $schema_create .= "$crlf)";
    return (stripslashes($schema_create));
}

// Get the content of $table as a series of INSERT statements.
// After every row, a custom callback function $handler gets called.
// $handler must accept one parameter ($sql_insert);
function get_table_content($db, $table, $handler, $what) {
	global $RAD_dbi;

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

    $result = sql_query("SELECT * FROM $table", $RAD_dbi);
    $i = 0;
    while($row = sql_fetch_array($result, $RAD_dbi)) {
	if ($what=="data1" && $i>0) return (true);
        set_time_limit(60); // HaRa
        $table_list = "(";
        for($j=0; $j<$num_fields;$j++)
            $table_list .= $A_fields[$j].", ";
        $table_list = substr($table_list,0,-2);
        $table_list .= ")";
        if(isset($GLOBALS["showcolumns"]))
            $schema_insert = "INSERT INTO $table $table_list VALUES (";
        else
            $schema_insert = "INSERT INTO $table VALUES (";
        for($j=0; $j<$num_fields;$j++) {
            if(!isset($row[$j]))
                $schema_insert .= " NULL,";
            elseif($row[$j] != "")
                $schema_insert .= " '".addslashes($row[$j])."',";
            else
                $schema_insert .= " '',";
        }
        $schema_insert = ereg_replace(",$", "", $schema_insert);
        $schema_insert .= ")";
        $handler(trim($schema_insert));
        $i++;
    }
    return (true);
}

function count_records ($db,$table) {
	global $RAD_dbi;
    $result = sql_query("select count(*) from $table", $RAD_dbi);
    $row = sql_fetch_array($result,$RAD_dbi);
    echo $row[0];
}

// Get the content of $table as a CSV output.
// $sep contains the separation string.
// After every row, a custom callback function $handler gets called.
// $handler must accept one parameter ($sql_insert);
function get_table_csv($db, $table, $sep, $handler) {
	global $RAD_dbi;
    $result = sql_query("SELECT * FROM $table", $RAD_dbi);
    $i = 0;
    while($row = sql_fetch_array($result, $RAD_dbi)) {
        set_time_limit(60); // HaRa
        $schema_insert = "";
        for($j=0; $j<sql_num_fields($result);$j++)
        {
            if(!isset($row[$j]))
                $schema_insert .= "NULL".$sep;
            elseif ($row[$j] != "")
                $schema_insert .= "$row[$j]".$sep;
            else
                $schema_insert .= "".$sep;
        }
        $schema_insert = str_replace($sep."$", "", $schema_insert);
        $handler(trim($schema_insert));
        $i++;
    }
    return (true);
}

function show_docu($link) {
    global $cfgManualBase, $strDocu;

    if(!empty($cfgManualBase))
        return("[<a href=\"$cfgManualBase/$link\">$strDocu</a>]");
}


function show_message($message) {
    if(!empty($GLOBALS['reload']) && ($GLOBALS['reload'] == "true")) {
        // Reload the navigation frame via JavaScript
        ?>
        <script language="JavaScript1.2">
        parent.location.reload();
        </script>
        <?php
    }
    ?>
     <table border="<?php echo $GLOBALS['cfgBorder'];?>">
      <tr>
       <td bgcolor="<?php echo $GLOBALS['cfgThBgcolor'];?>">
       <b><?php echo $message;?><b><br>
       </td>
      </tr>
    <?php
    if($GLOBALS['cfgShowSQL'] == true && !empty($GLOBALS['sql_query']))
    {
        ?>
        <tr>
        <td bgcolor="<?php echo $GLOBALS['cfgBgcolorOne'];?>">
        <?php echo $GLOBALS['strSQLQuery'].":\n<br>", nl2br($GLOBALS['sql_query']);
        if (isset($GLOBALS["sql_order"])) echo " $GLOBALS[sql_order]";
        if (strtolower(_DEF_dbtype)!="oracle" && isset($GLOBALS["pos"])) echo " LIMIT $GLOBALS[pos], $GLOBALS[cfgMaxRows]";?>
        </td>
        </tr>
        <?php
    }
    ?>
    </table>
    <?php
}

function split_string($sql, $delimiter) {
    $sql = trim($sql);
    $buffer = array();
    $ret = array();
    $in_string = false;

    for($i=0; $i<strlen($sql); $i++)
    {
        if($sql[$i] == $delimiter && !$in_string)
        {
            $ret[] = substr($sql, 0, $i);
            $sql = substr($sql, $i + 1);
            $i = 0;
        }

        if($in_string && ($sql[$i] == $in_string) && $buffer[0] != "\\")
        {
             $in_string = false;
        }
        elseif(!$in_string && ($sql[$i] == "\"" || $sql[$i] == "'") && (!isset($buffer[0]) || $buffer[0] != "\\"))
        {
             $in_string = $sql[$i];
        }
        if(isset($buffer[1]))
            $buffer[0] = $buffer[1];
        $buffer[1] = $sql[$i];
     }

    if (!empty($sql))
    {
        $ret[] = $sql;
    }

    return($ret);
}
?>
