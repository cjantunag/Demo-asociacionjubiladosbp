<?
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: ../../index.php");
    die();
}

include_once ("modules/".$V_dir."/defaults.php");

include_once ("header.php");
OpenTable();

include_once ("modules/".$V_dir."/dbsql.php");

$dbtype=strtolower($dbtype);

if ($func=="") {
	$func = "seltable";
	$func = "seldbtype";
	$hostname = _DEF_dbhost;
	$dbusername = _DEF_dbuname;
	$dbpassword = _DEF_dbpass;
	$dbtype = strtolower(_DEF_dbtype);
	global $dbname;
	if ($dbname=="") $dbname = _DEF_dbname;
}
if ($func == "confirm" && $tablename=="") $opt="all"; 

if ($opt=="all") {
	$literal=_DEF_NLSApplication;
	$genmodapp="genapp";
	$tablename="";
} else {
	$literal=_DEF_NLSModule;
	$genmodapp="genform";
}

?> 

<IMG BORDER=0 SRC="modules/<?=$V_dir?>/logo.gif" ALT="RAD">
<B><?=_DEF_NLSCreate." ".$literal." "._DEF_NLSDatabase?></B>
<HR noshade size=1>

<?
include_once("modules/".$V_dir."/phplibsup.php");
// default function name
if (!isset($func)) $func = "seldbtype";
	
/////////////////////////////////////////////////////////////////////////
// form for basic input: database type, hostname, dbusername, dbpassword
if ($func == "seldbtype") {
?>		
		<?=_DEF_NLSDatabase?> 1/5<BR>
		<FORM NAME="seldb" ACTION="<? echo $PHP_SELF; ?>" METHOD="POST">
<INPUT TYPE="HIDDEN" NAME="PHPSESSID" VALUE="<? echo $PHPSESSID; ?>">
<INPUT TYPE="HIDDEN" NAME="V_dir" VALUE="<? echo $V_dir; ?>">
<INPUT TYPE="HIDDEN" NAME="V_mod" VALUE="<? echo $V_mod; ?>">
<INPUT TYPE="HIDDEN" NAME="V_submod" VALUE="<? echo $V_submod; ?>">
		<INPUT TYPE="HIDDEN" NAME="func" VALUE="seldb">
		<INPUT TYPE="HIDDEN" NAME="opt" VALUE="<?=$opt?>">
		<CENTER>
		<TABLE BORDER="0" CELLPADDING="6">
		    <TR>
			<TD><b><?=_DEF_NLSServerDatabase?> : </b></TD>
			<TD><INPUT TYPE="TEXT" NAME="hostname" VALUE="<? echo $defaulthostname; ?>"></TD>
		    </TR>
		    <TR>
			<TD><b><?=_DEF_NLSUserDatabase?> : </b></TD>
			<TD><INPUT TYPE="TEXT" NAME="dbusername" VALUE="<? echo $defaultdbusername; ?>"></TD>
		    </TR>
		    <TR>
			<TD><b><?=_DEF_NLSPasswordDatabase?> : </b></TD>
			<TD><INPUT TYPE="PASSWORD" NAME="dbpassword" VALUE="<? echo $defaultdbpassword; ?>"></TD>
		    </TR>
		    <TR>
			<TD><b><?=_DEF_NLSDatabaseType?> : </b></TD>
			<TD><SELECT NAME="dbtype">
<?
			for ($i = 0; $i < count($SUPPORTED_SQL); $i++) { 
			    echo "<OPTION VALUE='".$SUPPORTED_SQL[$i]."'";
			    if ($SUPPORTED_SQL[$i] == $defaultdbtype) echo " SELECTED";
			    $obj = ${$SUPPORTED_SQL[$i]};
			    echo ">".$obj->name."</OPTION>\n"; 
			}
?>
				</SELECT><BR></TD>
		    </TR>
		    <TR>
			<TD COLSPAN="2" ALIGN="CENTER">
				<INPUT TYPE="SUBMIT" VALUE="<?=_DEF_NLSAccept?>">&nbsp;&nbsp;
				<INPUT TYPE="RESET" VALUE="<?=_DEF_NLSReset?>">
			</TD>
		    </TR>
		</TABLE>
		</CENTER>
		</FORM>
<?	
}
/////////////////////////////////////////////////////////////////////////
// form for database name input
if ($func == "seldb") {
		$RAD_dbinfo = $$dbtype;
?>
		<?=_DEF_NLSDatabase?> 2/5<BR>
		<FORM ACTION="<? echo $PHP_SELF ?>" METHOD="POST">
<INPUT TYPE="HIDDEN" NAME="PHPSESSID" VALUE="<? echo $PHPSESSID; ?>">
<INPUT TYPE="HIDDEN" NAME="V_dir" VALUE="<? echo $V_dir; ?>">
<INPUT TYPE="HIDDEN" NAME="V_mod" VALUE="<? echo $V_mod; ?>">
<INPUT TYPE="HIDDEN" NAME="V_submod" VALUE="<? echo $V_submod; ?>">
		<INPUT TYPE="HIDDEN" NAME="func" VALUE="seltable">
		<INPUT TYPE="HIDDEN" NAME="opt" VALUE="<?=$opt?>">
		<INPUT TYPE="HIDDEN" NAME="hostname" VALUE="<? echo $hostname; ?>">
		<INPUT TYPE="HIDDEN" NAME="dbusername" VALUE="<? echo $dbusername; ?>">
		<INPUT TYPE="HIDDEN" NAME="dbpassword" VALUE="<? echo $dbpassword; ?>">
		<INPUT TYPE="HIDDEN" NAME="dbtype" VALUE="<? echo $dbtype; ?>">
		<CENTER>
		<TABLE BORDER="0" CELLPADDING="6">
			<TR><TD><b><?=_DEF_NLSServerDatabase?> : </b></TD><TD><? echo $hostname; ?></TD></TR>
			<TR><TD><b><?=_DEF_NLSUserDatabase?> : </b></TD><TD><? echo $dbusername; ?></TD></TR>
			<TR><TD><b><?=_DEF_NLSDatabaseType?> : </b></TD><TD><? echo $dbtype; ?></TD></TR>
			<TR><TD><b><?=_DEF_NLSDatabaseName?> : </b></TD><TD>
<?		if ($RAD_dbinfo -> sup_dblist == "yes") {
		    $dbs = $RAD_dbinfo -> list_databases($hostname, $dbusername, $dbpassword);
		    echo "<SELECT NAME='dbname'>\n";
		    for ($i = 0; $i < count($dbs); $i++) { 
			echo "<OPTION VALUE='".$dbs[$i]."'>".$dbs[$i]."</OPTION>\n";
		    } 
		    echo "</SELECT>\n";
		} else {
		    echo "<INPUT TYPE='TEXT' NAME='dbname'>\n";
		} 
?>
			</TD></TR>
			<TR><TD COLSPAN="2" ALIGN="CENTER">
			    <INPUT TYPE="SUBMIT" VALUE="<?=_DEF_NLSAccept?>">&nbsp;&nbsp;
			    <INPUT TYPE="RESET" VALUE="<?=_DEF_NLSReset?>"></TD></TR>
		</TABLE>
		</CENTER>
		</FORM>
<?
}
/////////////////////////////////////////////////////////////////////////
if ($func == "seltable" || $func == "confirm" || $func == "confirmed") {
		$db = new DBSql;
		$db -> Host = $hostname;
		$db -> User = $dbusername;
		$db -> Password = $dbpassword;
		$db -> Database = $dbname;
		$db -> Type = $dbtype;
		$db -> Debug = false;
}
/////////////////////////////////////////////////////////////////////////
// Print form for table name input 
if ($func == "seltable") {
		$RAD_dbinfo = $$dbtype;
?>
		<?=_DEF_NLSDatabase?> 3/5<BR>
		<FORM ACTION="<? echo $PHP_SELF ?>" METHOD="POST">
<INPUT TYPE="HIDDEN" NAME="PHPSESSID" VALUE="<? echo $PHPSESSID; ?>">
<INPUT TYPE="HIDDEN" NAME="V_dir" VALUE="<? echo $V_dir; ?>">
<INPUT TYPE="HIDDEN" NAME="V_mod" VALUE="<? echo $V_mod; ?>">
<INPUT TYPE="HIDDEN" NAME="V_submod" VALUE="<? echo $V_submod; ?>">
		<INPUT TYPE="HIDDEN" NAME="func" VALUE="confirm">
		<INPUT TYPE="HIDDEN" NAME="opt" VALUE="<?=$opt?>">
		<INPUT TYPE="HIDDEN" NAME="hostname" VALUE="<? echo $hostname; ?>">
		<INPUT TYPE="HIDDEN" NAME="dbusername" VALUE="<? echo $dbusername; ?>">
		<INPUT TYPE="HIDDEN" NAME="dbpassword" VALUE="<? echo $dbpassword; ?>">
		<INPUT TYPE="HIDDEN" NAME="dbtype" VALUE="<? echo $dbtype; ?>">
		<INPUT TYPE="HIDDEN" NAME="dbname" VALUE="<? echo $dbname; ?>">
		<CENTER>
		<TABLE BORDER="0" CELLPADDING="2" CELLSPACING="0">
			<TR><TD><b><?=_DEF_NLSServerDatabase?> : </b></TD><TD><? echo $hostname; ?></TD></TR>
			<TR><TD><b><?=_DEF_NLSUserDatabase?> : </b></TD><TD><? echo $dbusername; ?></TD></TR>
			<TR><TD><b><?=_DEF_NLSDatabaseType?> : </b></TD><TD><? echo $dbtype; ?></TD></TR>
			<TR><TD><b><?=_DEF_NLSDatabaseName?> : </b></TD><TD><? echo $dbname; ?></TD></TR>
<?
		$tablenames="";
	        if ($opt != "all") {
		    echo "<TR><TD><b>"._DEF_NLSTableName." : </b></TD><TD>\n";
		    if ($RAD_dbinfo -> sup_tablelist == "yes") {
			$tables = $db -> table_names();
			echo "<SELECT NAME='tablename'>\n";
			echo "<OPTION VALUE=''>... todas</OPTION>\n";
			for ($i = 0; $i < count($tables); $i++) { 
			    echo "<OPTION VALUE='".$tables[$i]["table_name"]."'>".$tables[$i]["table_name"]."</OPTION>\n";
			    echo $tables[$i]["table_name"];
			}
			echo "</SELECT>\n";
			for ($i = 0; $i < count($tables); $i++) $tablenames.=$tables[$i]["table_name"].",";
			echo "<INPUT TYPE='HIDDEN' NAME='tablenames' value='$tablenames'>\n";
    		    } else { 
			echo "<INPUT TYPE='TEXT' NAME='tablename'>\n";
    		    }
		    echo "</TD></TR>\n";
		} else {
		    echo "<TR><TD><b>"._DEF_NLSTableName." : </b></TD><TD>\n";
		    if ($RAD_dbinfo -> sup_tablelist == "yes") {
			$tables = $db -> table_names();
			for ($i = 0; $i < count($tables); $i++) { 
				$tablenames.=$tables[$i]["table_name"].",";
				echo $tables[$i]["table_name"]."<br>"; 
			}
			echo "<INPUT TYPE='HIDDEN' NAME='tablenames' value='$tablenames'>\n";
		    } else { 
		    	echo "<INPUT TYPE='TEXTAREA' NAME='tablenames'>\n";
		    } 
		    echo "</TD></TR>\n";
		} 
?>
		<TR><TD><b><?=_DEF_NLSModule." "._DEF_NLSDirectory?> : </b></TD>
		    <TD><? echo $DIRBASE; ?><INPUT NAME="modulesdirform" SIZE="15" VALUE="<? echo $dbname; ?>"></TD></TR>
			<INPUT TYPE=HIDDEN NAME='tableConfig' VALUE=''>
			<INPUT TYPE=HIDDEN NAME='security' VALUE=''>
			<INPUT TYPE=HIDDEN NAME='tableUser' VALUE=''>
			<INPUT TYPE=HIDDEN NAME='fieldUser' VALUE=''>
			<INPUT TYPE=HIDDEN NAME='fieldPass' VALUE=''>
			<INPUT TYPE=HIDDEN NAME='RAD' VALUE='x'>
		<TR><TD COLSPAN="2" ALIGN="CENTER">
		    <INPUT TYPE="SUBMIT" VALUE="<?=_DEF_NLSAccept?>">&nbsp;&nbsp;
		    <INPUT TYPE="RESET" VALUE="<?=_DEF_NLSReset?>"></TD></TR>
    	    </TABLE>
	    </CENTER>
	    </FORM>
<?
}
/////////////////////////////////////////////////////////////////////////
if ($func == "confirm") { 
	echo _DEF_NLSDatabase." 4/5<BR><center><TABLE border=0><TR><TD>";
	if ($opt != "all" && $tablename!="") $tables[0]=$tablename;
	else $tables = explode(",", $tablenames);
	for ($k = 0; $k < count($tables); $k++) {
	if ($tables[$k]!="") $tablename=$tables[$k];
?>
		<TABLE border=1>
		    <TR>
			<TH COLSPAN=4><?=$tablename?></TH>
		    </TR>
		    <TR>
			<TH><?=_DEF_NLSName?></TH>
			<TH><?=_DEF_NLSType?></TH>
			<TH><?=_DEF_NLSLength?></TH>
			<TH><?=_DEF_NLSValue?></TH>
		    </TR>
<?		$tableinfo = $db->metadata($tablename);
		for ($i = 0; $i < count($tableinfo); $i++) { ?>
		    <TR>
			<TD><? echo $tableinfo[$i]["name"]; ?></TD>
			<TD><? echo $tableinfo[$i]["type"]; ?></TD>
			<TD><? echo $tableinfo[$i]["len"]; ?></TD>
			<TD><? echo $tableinfo[$i]["flags"]; ?></TD>
		    </TR>
<?		} 
		echo "</TABLE><BR>";
	}
	if (!isset($tableConfig)) $tableConfig="";
	if (!isset($fieldConfig)) $fieldConfig="";
	if (!isset($tableUser)) $tableUser="";
	if (!isset($fieldUser)) $fieldUser="";
	if (!isset($fieldPass)) $fieldPass="";
	if (!isset($RAD)) $RAD="";
	if (!isset($browsetype)) $browsetype="";
?>
		<FORM ACTION="index.php" METHOD="POST">
<INPUT TYPE="HIDDEN" NAME="PHPSESSID" VALUE="<? echo $PHPSESSID; ?>">
<INPUT TYPE="HIDDEN" NAME="V_dir" VALUE="<? echo $V_dir; ?>">
<INPUT TYPE="HIDDEN" NAME="V_mod" VALUE="<? echo $V_mod; ?>">
<INPUT TYPE="HIDDEN" NAME="V_submod" VALUE="<? echo $genmodapp; ?>">
			<INPUT TYPE="HIDDEN" NAME="func" VALUE="confirmed">
			<INPUT TYPE="HIDDEN" NAME="opt" VALUE="<?=$opt?>">
			<INPUT TYPE="HIDDEN" NAME="GEN_themefile" VALUE="<?=$themefile?>">
			<INPUT TYPE="HIDDEN" NAME="hostname" VALUE="<? echo $hostname; ?>">
			<INPUT TYPE="HIDDEN" NAME="dbusername" VALUE="<? echo $dbusername; ?>">
			<INPUT TYPE="HIDDEN" NAME="dbpassword" VALUE="<? echo $dbpassword; ?>">
			<INPUT TYPE="HIDDEN" NAME="dbtype" VALUE="<? echo $dbtype; ?>">
			<INPUT TYPE="HIDDEN" NAME="dbname" VALUE="<? echo $dbname; ?>">
			<INPUT TYPE="HIDDEN" NAME="tablename" VALUE="<? echo $tablename; ?>">
			<INPUT TYPE="HIDDEN" NAME="tablenames" VALUE="<? echo $tablenames; ?>">
			<INPUT TYPE="HIDDEN" NAME="modulesdirform" VALUE="<? echo $modulesdirform; ?>">
			<INPUT TYPE="HIDDEN" NAME="tableConfig" VALUE="<? echo $tableConfig; ?>">
			<INPUT TYPE="HIDDEN" NAME="fieldConfig" VALUE="<? echo $fieldConfig; ?>">
			<INPUT TYPE="HIDDEN" NAME="tableUser" VALUE="<? echo $tableUser; ?>">
			<INPUT TYPE="HIDDEN" NAME="fieldUser" VALUE="<? echo $fieldUser; ?>">
			<INPUT TYPE="HIDDEN" NAME="fieldPass" VALUE="<? echo $fieldPass; ?>">
			<INPUT TYPE="HIDDEN" NAME="security" VALUE="<? echo $security; ?>">
			<INPUT TYPE="HIDDEN" NAME="browsetype" VALUE="<? echo $browsetype; ?>">
			<INPUT TYPE="HIDDEN" NAME="RAD" VALUE="<? echo $RAD; ?>">
<?
	$idnames="";
	if ($opt !="all") {
		$RAD_dbinfo = $$dbtype;
		$tableinfo = $db->metadata($tablename);
		for ($i = 0; $i < count($tableinfo); $i++) { 
			if ($RAD_dbinfo -> isautoincrement($tableinfo[$i]["flags"])) $tableinfo[$i]["type"]="auto_increment";
?>
			<INPUT TYPE="HIDDEN" NAME="NAME<? echo $i; ?>" VALUE="<? echo $tableinfo[$i]["name"];?>">
			<INPUT TYPE="HIDDEN" NAME="ORDERBY<? echo $i; ?>" VALUE="<? echo $tableinfo[$i]["name"];?>">
			<INPUT TYPE="HIDDEN" NAME="LENGTH<? echo $i; ?>" VALUE="<? echo $tableinfo[$i]["len"];?>">
			<INPUT TYPE="HIDDEN" NAME="TYPE<? echo $i; ?>" VALUE="<? echo $tableinfo[$i]["type"];?>">
			<INPUT TYPE="HIDDEN" NAME="DTYPE<? echo $i; ?>" VALUE="<? echo $tableinfo[$i]["type"];?>">
			<INPUT TYPE="HIDDEN" NAME="NONEW<? echo $i; ?>" VALUE="">
			<INPUT TYPE="HIDDEN" NAME="CANBENULL<? echo $i; ?>" VALUE="<?
				echo ($RAD_dbinfo -> canbenull($tableinfo[$i]["flags"]) ? "on" : ""); ?>">
			<INPUT TYPE="HIDDEN" NAME="FLAGS<? echo $i; ?>" VALUE="<? echo $tableinfo[$i]["flags"];?>">
<?	  	}
		for ($i = 0; $i < count($tableinfo); $i++) {
			if ($RAD_dbinfo -> isprimarykey($tableinfo[$i]["flags"])) {
				$primary_key[$i] = $tableinfo[$i]["name"];
				$idnames.=$primary_key[$i].",";
			}
		}
		if ($idnames =="") {
			// all fields be treated as primary in a table with no primary key 
			for ($i = 0; $i < count($tableinfo); $i++) {
				$idnames.=$tableinfo[$i]["name"].",";
			}
		}
		if (strlen($idnames)>0) $idnames=substr($idnames,0,strlen($idnames)-1);
	}
	echo "</TD></TR><TR><TD ALIGN=CENTER><INPUT TYPE=HIDDEN NAME=idnames VALUE='$idnames'><INPUT TYPE='SUBMIT' NAME='ACCEPT' VALUE='"._DEF_NLSCreate." $literal'>&nbsp;
	</FORM>
	</TD></TR></TABLE></center>";
}
/////////////////////////////////////////////////////////////////////////
if ($func == "confirmed") { 
	echo _DEF_NLSDatabase." 5/5<BR><center><TABLE border=0><TR><TD>";
	if ($opt != "all" && $tablename!="") $tables[0]=$tablename;
	else $tables = explode(",", $tablenames);
	for ($k = 0; $k < count($tables); $k++) { // look database keys
	    if ($tables[$k]!="") $tablename=$tables[$k];
	    else continue;
	    $RAD_dbinfo = $$dbtype;
	    $tableinfo = $db->metadata($tablename);
	    for ($i = 0; $i < count($tableinfo); $i++) {
	        foreach($tableinfo[$i] as $key=>$val) { 
		    if (trim($key)!="") $TYPES[$key]=$key;
		    if ($key=="name") {
			if ($FIELDS[$val]!="") $FIELDS[$val].="|"; 
			$FIELDS[$val].=$tablename; 
		    }
	        }
	    }
	}
	foreach($FIELDS as $key=>$val) { 
	    $TMP_f=explode("|",$val);
	    if (count($TMP_f)<2) continue; // field unique
	    $TMP_key=$key;
	    foreach($TMP_f as $idx=>$tablefield) {
		$TMP_content.="<TR>\n";
	        $TMP_content.="<TD>$TMP_key</TD>\n"; $TMP_key="";
		$TMP_content.="<TD><input type=radio name='key_".$tablefield."_".$key."'> ".$tablefield."</TD>\n"; 
		$TMP_content.="<TD><input type=checkbox name=foreignkey_".$tablefield."_".$key."> ".$tablefield."</TD>\n"; 
		$TMP_content.="</TR>\n";
	    }
	}
echo $TMP_content;

	for ($k = 0; $k < count($tables); $k++) { // calculate model
	    if ($tables[$k]!="") $tablename=$tables[$k];
	    else continue;
	    $content.="<DATATABLE ".$tablename.">\n";
	    $RAD_dbinfo = $$dbtype;
	    $tableinfo = $db->metadata($tablename);
	    for ($i = 0; $i < count($tableinfo); $i++) {
		$content.="	<COLUMNTABLE ".$tableinfo[$i]["name"].">\n";
	        foreach($tableinfo[$i] as $key=>$val) { 
		    if ($key!="name"&&$key!="table"&&$val!="") $content.="		<".strtoupper($key)." ".$val.">\n";
		    if ($key=="name") {
			if ($FIELDS[$val]!="") $FIELDS[$val].="|"; 
			$FIELDS[$val].=$tablename; 
		    }
	        }
		$content.="	</COLUMNTABLE>\n";
	    }
	    $content.="</DATATABLE>\n";
	}
	foreach($FIELDS as $key=>$val) { 
	    $TMP_f=explode("|",$val);
	    if (count($TMP_f)<2) continue; // field unique
	    foreach($TMP_f as $idx=>$tablefield) {
		echo $key."=>".$tablefield."<br>\n"; 
	    }
	}
//	echo "</TR></TABLE></TR></TABLE><pre>".htmlentities($content)."</pre>";
//	$fp=fopen("modules/phpGenRAD/$dbname.model.xml","w") {
//	    fwrite($content,$fp);
//	}
//	fclose($fp);

	for ($k = 0; $k < count($tables); $k++) {
	    if ($tables[$k]!="") $tablename=$tables[$k];
	    else continue;
	    //echo $k." -> ".$tablename."<br>";
	    $RAD_dbinfo = $$dbtype;
	    $tableinfo = $db->metadata($tablename);
?>
		<TABLE border=1>
		    <TR>
			<TH COLSPAN=4><?=$tablename?></TH>
		    </TR>
		    <TR>
<?			foreach($TYPES as $key=>$val) { if ($key!="table") echo "<TH>".strtoupper($key)."</TH>\n"; } ?>
		    </TR>
<?
		$tableinfo = $db->metadata($tablename);
		for ($i = 0; $i < count($tableinfo); $i++) { 
		    echo "<TR>";
		    foreach($TYPES as $key=>$val) { if ($key!="table") echo "<TD>".$tableinfo[$i][$key]."</TH>\n"; }
		    echo "</TR>\n";
		}
		echo "</TABLE><BR>";
	}
	if (!isset($tableConfig)) $tableConfig="";
	if (!isset($fieldConfig)) $fieldConfig="";
	if (!isset($tableUser)) $tableUser="";
	if (!isset($fieldUser)) $fieldUser="";
	if (!isset($fieldPass)) $fieldPass="";
	if (!isset($RAD)) $RAD="";
	if (!isset($browsetype)) $browsetype="";
?>
		<FORM ACTION="index.php" METHOD="POST">
<INPUT TYPE="HIDDEN" NAME="PHPSESSID" VALUE="<? echo $PHPSESSID; ?>">
<INPUT TYPE="HIDDEN" NAME="V_dir" VALUE="<? echo $V_dir; ?>">
<INPUT TYPE="HIDDEN" NAME="V_mod" VALUE="<? echo $V_mod; ?>">
<INPUT TYPE="HIDDEN" NAME="V_submod" VALUE="<? echo $genmodapp; ?>">
			<INPUT TYPE="HIDDEN" NAME="func" VALUE="confirmed">
			<INPUT TYPE="HIDDEN" NAME="opt" VALUE="<?=$opt?>">
			<INPUT TYPE="HIDDEN" NAME="GEN_themefile" VALUE="<?=$themefile?>">
			<INPUT TYPE="HIDDEN" NAME="hostname" VALUE="<? echo $hostname; ?>">
			<INPUT TYPE="HIDDEN" NAME="dbusername" VALUE="<? echo $dbusername; ?>">
			<INPUT TYPE="HIDDEN" NAME="dbpassword" VALUE="<? echo $dbpassword; ?>">
			<INPUT TYPE="HIDDEN" NAME="dbtype" VALUE="<? echo $dbtype; ?>">
			<INPUT TYPE="HIDDEN" NAME="dbname" VALUE="<? echo $dbname; ?>">
			<INPUT TYPE="HIDDEN" NAME="tablename" VALUE="<? echo $tablename; ?>">
			<INPUT TYPE="HIDDEN" NAME="tablenames" VALUE="<? echo $tablenames; ?>">
			<INPUT TYPE="HIDDEN" NAME="modulesdirform" VALUE="<? echo $modulesdirform; ?>">
			<INPUT TYPE="HIDDEN" NAME="tableConfig" VALUE="<? echo $tableConfig; ?>">
			<INPUT TYPE="HIDDEN" NAME="fieldConfig" VALUE="<? echo $fieldConfig; ?>">
			<INPUT TYPE="HIDDEN" NAME="tableUser" VALUE="<? echo $tableUser; ?>">
			<INPUT TYPE="HIDDEN" NAME="fieldUser" VALUE="<? echo $fieldUser; ?>">
			<INPUT TYPE="HIDDEN" NAME="fieldPass" VALUE="<? echo $fieldPass; ?>">
			<INPUT TYPE="HIDDEN" NAME="security" VALUE="<? echo $security; ?>">
			<INPUT TYPE="HIDDEN" NAME="browsetype" VALUE="<? echo $browsetype; ?>">
			<INPUT TYPE="HIDDEN" NAME="RAD" VALUE="<? echo $RAD; ?>">
<?
	$idnames="";
	if ($opt !="all") {
		$RAD_dbinfo = $$dbtype;
		$tableinfo = $db->metadata($tablename);
		for ($i = 0; $i < count($tableinfo); $i++) { 
			if ($RAD_dbinfo -> isautoincrement($tableinfo[$i]["flags"])) $tableinfo[$i]["type"]="auto_increment";
?>
			<INPUT TYPE="HIDDEN" NAME="NAME<? echo $i; ?>" VALUE="<? echo $tableinfo[$i]["name"];?>">
			<INPUT TYPE="HIDDEN" NAME="ORDERBY<? echo $i; ?>" VALUE="<? echo $tableinfo[$i]["name"];?>">
			<INPUT TYPE="HIDDEN" NAME="LENGTH<? echo $i; ?>" VALUE="<? echo $tableinfo[$i]["len"];?>">
			<INPUT TYPE="HIDDEN" NAME="TYPE<? echo $i; ?>" VALUE="<? echo $tableinfo[$i]["type"];?>">
			<INPUT TYPE="HIDDEN" NAME="DTYPE<? echo $i; ?>" VALUE="<? echo $tableinfo[$i]["type"];?>">
			<INPUT TYPE="HIDDEN" NAME="NONEW<? echo $i; ?>" VALUE="">
			<INPUT TYPE="HIDDEN" NAME="CANBENULL<? echo $i; ?>" VALUE="<?
				echo ($RAD_dbinfo -> canbenull($tableinfo[$i]["flags"]) ? "on" : ""); ?>">
			<INPUT TYPE="HIDDEN" NAME="FLAGS<? echo $i; ?>" VALUE="<? echo $tableinfo[$i]["flags"];?>">
<?	  	}
		for ($i = 0; $i < count($tableinfo); $i++) {
			if ($RAD_dbinfo -> isprimarykey($tableinfo[$i]["flags"])) {
				$primary_key[$i] = $tableinfo[$i]["name"];
				$idnames.=$primary_key[$i].",";
			}
		}
		if ($idnames =="") {
			// all fields be treated as primary in a table with no primary key 
			for ($i = 0; $i < count($tableinfo); $i++) {
				$idnames.=$tableinfo[$i]["name"].",";
			}
		}
		if (strlen($idnames)>0) $idnames=substr($idnames,0,strlen($idnames)-1);
	}
	echo "</TD></TR><TR><TD ALIGN=CENTER><INPUT TYPE=HIDDEN NAME=idnames VALUE='$idnames'><INPUT TYPE='SUBMIT' NAME='ACCEPT' VALUE='"._DEF_NLSCreate." $literal'>&nbsp;
	</FORM>
	</TD></TR></TABLE></center>";
}
/////////////////////////////////////////////////////////////////////////

CloseTable();
include_once ("footer.php");

?>
