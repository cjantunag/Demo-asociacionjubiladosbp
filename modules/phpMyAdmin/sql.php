<?php
global $PHPSESSID, $PHP_SELF, $SESSION_SID;
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

if(isset($subfunc) && $subfunc == "sql") {
    $page = "index.php?V_dir=$V_dir&V_mod=$V_mod&func=sql&server=$server&db=$db&table=$table&pos=$pos&sql_query=".urlencode($sql_query).$SESSION_SID;
} else if($subfunc == "") {
    $page = "index.php?V_dir=$V_dir&V_mod=$V_mod&server=$server&db=$db".$SESSION_SID;
}

// Go back to further page if table should not be dropped
if(isset($btnDrop) && $btnDrop == $strNo) {
    if ($page=="") $page=$PHP_SELF."?V_dir=$V_dir&V_mod=$V_mod&func=db_details&server=1";
    if(file_exists($subfunc.".php"))
	include("modules/$V_dir/".$subfunc.".php");
    else
	echo "<script type=text/javascript>\ndocument.location='$page';\n</script>\n";
    exit;
}

// Check if table should be dropped
$is_drop_sql_query = eregi("DROP +(TABLE|DATABASE)|ALTER TABLE +[[:alnum:]_]* +DROP|DELETE FROM", $sql_query); // Get word "drop"

if(!$cfgConfirm) $btnDrop = $strYes;

if($is_drop_sql_query && !isset($btnDrop)) {
    include("modules/$V_dir/header.inc.php");
    echo $strDoYouReally.urldecode(stripslashes($sql_query))."?<br>";
    ?>
    <form action="index.php" method="post" enctype="application/x-www-form-urlencoded">
    <input type="hidden" name="PHPSESSID" value="<?=$PHPSESSID?>">
    <input type="hidden" name="V_dir" value="<?=$V_dir?>">
    <input type="hidden" name="V_mod" value="<?=$V_mod?>">
    <input type="hidden" name="func" value="sql">
    <input type="hidden" name="sql_query" value="<?php echo urldecode(stripslashes($sql_query)); ?>">
    <input type="hidden" name="server" value="<?=$server?>">
    <input type="hidden" name="db" value="<?=$db?>">
    <input type="hidden" name="zero_rows" value="<?php echo isset($zero_rows) ? $zero_rows : "";?>">
    <input type="hidden" name="table" value="<?php echo isset($table) ? $table : "";?>">
    <input type="hidden" name="subfunc" value="<?php echo isset($subfunc) ? $subfunc : "";?>">
    <input type="hidden" name="reload" value="<?php echo isset($reload) ? $reload : "";?>">
    <input type="Submit" name="btnDrop" value="<?=$strYes?>">
    <input type="Submit" name="btnDrop" value="<?=$strNo?>">
    </form>
    <?php
// if table should be dropped or other queries should be perfomed
//elseif (!$is_drop_sql_query || $btnDrop == $strYes)
} else {
    $sql_query = isset($sql_query) ? stripslashes($sql_query) : '';
    $sql_order = isset($sql_order) ? stripslashes($sql_order) : '';
    if(isset($sessionMaxRows) )
        $cfgMaxRows = $sessionMaxRows;
    $sql_limit = (isset($pos) && eregi("^SELECT", $sql_query)) ? " LIMIT $pos, $cfgMaxRows" : '';
    $result = mysql_db_query($db, $sql_query.$sql_order.$sql_limit);
    // the same SELECT without LIMIT
    if(eregi("^SELECT", $sql_query)) {
        $OPresult = mysql_db_query($db, $sql_query.$sql_order);
        $SelectNumRows = @mysql_num_rows($OPresult);
    }

    if(!$result) {
        $error = $sql_query.$sql_order.$sql_limit.mysql_error();
        include("modules/$V_dir/header.inc.php");
        mysql_die($error);
    }

    $num_rows = @mysql_num_rows($result);

    if($num_rows < 1) {
        if(file_exists($subfunc.".php")) {
            include("modules/$V_dir/header.inc.php");
            if(isset($zero_rows) && !empty($zero_rows))
                $message = $zero_rows;
            else
                $message = $strEmptyResultSet;
            include("modules/$V_dir/".$subfunc.".php");
        } else {
            $message = $zero_rows;
		if ($page=="") $page=$PHP_SELF."?V_dir=$V_dir&V_mod=$V_mod&func=db_details&server=1";
		echo "<script type=text/javascript>\ndocument.location='$page';\n</script>\n";
        }
        exit;
    } else {
        include("modules/$V_dir/header.inc.php");
        display_table($result);
        if(!eregi("SHOW VARIABLES|SHOW PROCESSLIST|SHOW STATUS", $sql_query))
            echo "<p><a href=\"?V_dir=$V_dir&V_mod=$V_mod&func=tbl_change&server=$server&db=$db&table=$table&pos=$pos&subfunc=$subfunc&sql_query=".urlencode($sql_query).$SESSION_SID."\"> $strInsertNewRow</a></p>";
    }
}
require ("modules/$V_dir/footer.inc.php");
?>
