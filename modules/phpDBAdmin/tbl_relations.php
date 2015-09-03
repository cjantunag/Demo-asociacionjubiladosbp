<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

   global $PHPSESSID, $SESSION_SID, $RAD_dbi, $PHP_SELF, $func, $db, $table, $V0_field, $V0_val;

   include_once("modules/$V_dir/header.inc.php");

   $RAD_dbi = sql_connect(_DEF_dbhost, _DEF_dbuname, _DEF_dbpass, $db);

   $TMP_URL=$PHP_SELF."?";
   foreach ($_REQUEST as $TMP_k=>$TMP_v) if ($TMP_k!="table" && $TMP_k!="V0_field" && $TMP_k!="val") $TMP_URL.="&".$TMP_k."=".$TMP_v;

   echo "<h2><center>$db ".$table." ";
   if ($V0_field!="__") echo $V0_field." ".$V0_val;
   echo "</h1></center>";
   if ($table=="") {
	$result = sql_query('SHOW TABLES', $RAD_dbi);
	while($TMP_row=sql_fetch_array($result, $RAD_dbi)) {
		echo "<li><a target=_blank href='".$TMP_URL."&table=".$TMP_row[0]."&V0_field=__'>Contenido</a>&nbsp;|&nbsp;";
		echo "<a target=_blank href='".$TMP_URL."&table=".$TMP_row[0]."'>Estructura</a>&nbsp;&nbsp;".$TMP_row[0]."</li>";
		$TMP_cont++;
	}
   } else if ($V0_field=="__" || $V0_val!="") {
	if ($V0_val!="") {
		if ($V0_val=="NULL") $result = sql_query("SELECT COUNT(*) FROM ".$table." WHERE ".$V0_field."='' OR ".$V0_field." IS NULL",$RAD_dbi);
		else $result = sql_query("SELECT COUNT(*) FROM ".$table." WHERE ".$V0_field."='".$V0_val."'",$RAD_dbi);
	} else $result = sql_query("SELECT COUNT(*) FROM ".$table,$RAD_dbi);
	$row = sql_fetch_array($result, $RAD_dbi);
	$num = $row[0];
	echo "&nbsp;&nbsp;Num. Reg. = ".$num;
	if ($V0_field!="__") echo ". $V0_field = ".$V0_val;
	else if ($V0_val!="") echo ". Val = ".$V0_val;
	if ($V0_val=="") $result = sql_query("SELECT * FROM ".$table." LIMIT 0,100",$RAD_dbi);
	else if ($V0_val=="NULL") $result = sql_query("SELECT * FROM ".$table." WHERE ".$V0_field."='' OR ".$V0_field." IS NULL LIMIT 0,100",$RAD_dbi);
	else $result = sql_query("SELECT * FROM ".$table." WHERE ".$V0_field."='".$V0_val."' LIMIT 0,100",$RAD_dbi);
	$cont=0;
	echo "<table class=browse>";
	while($row = sql_fetch_assoc($result, $RAD_dbi)) {
		if ($cont==0) {
			echo "<tr class=browse>";
			foreach ($row as $TMP_field=>$TMP_val) {
				echo "<th class=browse>".$TMP_field."</th>";
			}
			echo "</tr>";
		}
		echo "<tr class=browse>";
		if ($RAD_classrow=="class=row1") $RAD_classrow="class=row2";
		else $RAD_classrow="class=row1";
		$TMP_numf=0;
		foreach ($row as $TMP_field=>$TMP_val) {
			// if ($TMP_numf==0) $TMP_link="<a target=_blank href='".$TMP_URL."&table=".$table."&V0_field=".$TMP_field."'>";
			echo "<td $RAD_classrow>".$TMP_link.$TMP_val."</a></td>";
			$TMP_numf++;
		}
		echo "</tr>";
		$cont++;
    	}
	echo "</table>";
   } else if ($V0_field=="") {
	$result = sql_query('SHOW TABLES', $RAD_dbi);
	while($TMP_row=sql_fetch_array($result, $RAD_dbi)) {
		$result2 = sql_query("SHOW FIELDS FROM ".$TMP_row[0],$RAD_dbi) or die("2. "._DEF_NLSError." ".$TMP_row[0].": ".sql_error($result));
		while($TMP_row2=sql_fetch_array($result2, $RAD_dbi)) {
			$A_fieldstables[$TMP_row2[0]].=$TMP_row[0]."\n";
		}
	}
	$result = sql_query("SELECT COUNT(*) FROM ".$table,$RAD_dbi) or die("2. "._DEF_NLSError." ".$table.": ".sql_error($result));
	$row = sql_fetch_array($result, $RAD_dbi);
	$num = $row[0];
	echo "&nbsp;&nbsp;<a target=_blank href='".$TMP_URL."&table=".$table."&V0_field=__'>Num. Reg. = ".$num."<ga>";
	$result = sql_query("SHOW FIELDS FROM ".$table,$RAD_dbi) or die("2. "._DEF_NLSError." ".$table.": ".sql_error($result));
	$cont=0;
	echo "<table class=browse>";
	while($row = sql_fetch_assoc($result, $RAD_dbi)) {
		if ($cont==0) {
			echo "<tr class=browse>";
			foreach ($row as $TMP_field=>$TMP_val) {
				echo "<th class=browse>".$TMP_field."</th>";
			}
			echo "<th class=browse>Relations</th>";
			echo "</tr>";
		}
		echo "<tr class=browse>";
		$TMP_numf=0;
		if ($RAD_classrow=="class=row1") $RAD_classrow="class=row2";
		else $RAD_classrow="class=row1";
		foreach ($row as $TMP_field=>$TMP_val) {
			if ($TMP_numf==0) {
				$TMP_link="<a target=_blank href='".$TMP_URL."&table=".$table."&V0_field=".$TMP_val."'>";
				$result2 = sql_query("SELECT DISTINCT($TMP_val) FROM ".$table."",$RAD_dbi);
				$num="[".sql_num_rows($result2, $RAD_dbi)."]";
			} else {
				$TMP_link="";
				$num="";
			}
			echo "<td $RAD_classrow>".$TMP_link.$TMP_val."</a> $num </td>";
			$TMP_numf++;
		}
		echo "<td $RAD_classrow>";
		$A_info=explode("\n",$A_fieldstables[$row["Field"]]."\n");
		foreach ($A_info as $TMP_cont=>$TMP_table) {
			if (trim($TMP_table)=="") continue;
			if (trim($TMP_table)==$table) continue;
			$TMP_linktab="<a target=_blank href='".$TMP_URL."&table=".$TMP_table."'>";
			$TMP_linkcontenido="<a target=_blank href='".$TMP_URL."&table=".$TMP_table."&V0_field=__'>";
			$TMP_linkdistinct="<a target=_blank href='".$TMP_URL."&table=".$TMP_table."&V0_field=".$row["Field"]."'>";
			echo $TMP_linktab."Structure</a>&nbsp;|&nbsp;".$TMP_linkdistinct."Distinct</a>&nbsp;|&nbsp;".$TMP_linkcontenido."Regs</a>&nbsp;&nbsp;".$TMP_table."<br>";
		}
		echo "</td></tr>";
		$cont++;
    	}
	echo "</table>";
   } else if ($V0_field!="" && $V0_val=="") {
	$result = sql_query("SELECT DISTINCT($V0_field) FROM ".$table." ORDER BY $V0_field",$RAD_dbi) or die("2. "._DEF_NLSError." ".$table.": ".sql_error($result));
	$num = sql_num_rows($result, $RAD_dbi);
	echo "&nbsp;&nbsp;Num. Reg. Distinct =".$num;
	$cont=0;
	echo "<table class=browse>";
	while($row = sql_fetch_assoc($result, $RAD_dbi)) {
		if ($cont==0) {
			echo "<tr class=browse>";
			foreach ($row as $TMP_field=>$TMP_val) {
				echo "<th class=browse>".$TMP_field."</th>";
			}
			echo "</tr>";
		}
		echo "<tr class=browse>";
		$TMP_numf=0;
		if ($RAD_classrow=="class=row1") $RAD_classrow="class=row2";
		else $RAD_classrow="class=row1";
		foreach ($row as $TMP_field=>$TMP_val) {
			$TMP_cmdSQL2 = "SELECT count(*) FROM ".$table." where ".$V0_field."='".$TMP_val."'";
			if ($TMP_val=="") $TMP_cmdSQL2 = "SELECT count(*) FROM ".$table." where ".$V0_field."='' OR ".$V0_field." IS NULL";
			$result2 = sql_query($TMP_cmdSQL2,$RAD_dbi);
			$row2 = sql_fetch_row($result2, $RAD_dbi);
			$num2 = $row2[0];
			if ($TMP_val=="") $TMP_val="NULL";
			if ($TMP_numf==0) $TMP_link="<a target=_blank href='".$TMP_URL."&table=".$table."&V0_field=".$TMP_field."&V0_val=".urlencode($TMP_val)."'>";
			echo "<td $RAD_classrow>".$TMP_link.$TMP_val."</a> [".$num2."]</td>";
			$TMP_numf++;
		}
		echo "</tr>";
		$cont++;
    	}
	echo "</table>";
   }

   require_once("modules/$V_dir/footer.inc.php");
?>
