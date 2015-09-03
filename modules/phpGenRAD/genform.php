<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

if ($V_mod=="genform") {
	$V_mod="indexRAD";
	$V_submod="genform";
}

if (!isset($logsql)) $logsql="on";

if ($modulesdir!="") $TMP_modulesdir=$modulesdir;
//$dbname="";

	$types_arr = array(
		"string" => "String",
		"num" => "Numeric",
		"blob" => "BLOB",
		"enum" => "Enum",
		"date" => "Date",
		"datetime" => "DateTime",
		"time" => "Time",
		"dateint" => "DateInt",
		"datetimeint" => "DateTimeInt",
		"timeint" => "TimeInt",
		"password" => "password",
		"crypt" => "crypt",
		"md5" => "MD5",
		"base64" => "Base 64",
		"auto_increment" => "Auto_Inc.",
		"uniqid" => "UniqID",
		"function" => "Function"
	);
	$type2dtype_arr = array(
		"string" => "stand",
		"num" => "num",
		"blob" => "text",
		"date" => "date",
		"datetext" => "datetex",
		"datetime" => "datetime",
		"dateint" => "dateint",
		"datetimeint" => "datetimeint",
		"timeint" => "timeint",
		"time" => "time",
		"enum" => "plist",
		"password" => "password",
		"auto_increment" => "auto_increment",
		"uniqid" => "uniqid",
		"function" => "Function",
		"function" => "GetURL"
	);
	$dtypes_arr = array(
		"stand" => "Input",
		"standR" => "Input (Readonly)",
		"standD" => "Input (Disabled)",
		"num" => "Numeric",
		"password" => "Password",
		"plist" => "Pick-List",
		"plistm" => "Pick-List(M)",
        "plistdb" => "Pick-List+DB",
        "plistdbajax" => "Pick-List+DB AJAX",
		"plistdbm" => "Pick-List+DB(M)",
		"plistdbtree" => "Pick-List+DB Tree",
		"plistdbmtree" => "Pick-List+DB(M) Tree",
		"inputdbajax" => "Input+DB AJAX",
        "popupdb" => "Pop Up+DB",
        "popupdbajax" => "Pop Up+DB AJAX",
		"popupdbtree" => "Pop Up+DB Tree",
		"popupdbm" => "Pop Up+DB(M)",
		"fpopupdb" => "Input+Pop Up+DB",
		"bpopupdb" => "Button+Pop Up+DB",
		"fbpopupdb" => "Inp+But+Pop Up+DB",
		"popupdbSFF" => "Pop Up+DB SFF",
		"rlist" => "Radio-List",
		"rlistdb" => "Radio-List+DB",
		"date" => "Date",
		"datetext" => "Date (Text)",
		"dateint" => "DateInt",
		"dateinttext" => "DateInt (Text)",
		"datetime" => "DateTime",
		"datetimetext" => "DateTime (Text)",
		"datetimeint" => "DateTimeInt",
		"datetimeinttext" => "DateTimeInt (Text)",
		"time" => "Time",
		"timetext" => "Time (Text)",
		"timeora" => "Time (Oracle)",
		"timeint" => "TimeInt",
		"timeinttext" => "TimeInt (Text)",
		"checkbox" => "Checkbox",
		"checkboxm" => "Checkbox(M)",
		"checkboxdbm" => "Checkbox+DB(M)",
		"text" => "Text",
		"texthtml" => "Text HTML",
		"file" => "Upload file",
		"image" => "Upload image",
		"email" => "Email",
		"http" => "URL",
		"function" => "Function",
		"geturl" => "GetURL",
		"auto_increment" => "None",
		"hidden" => "Hidden"
	);
	$files = array(
		"JS"       => $SOURCEDIR."/RAD_js.php",
		"GENUTIL"   => $SOURCEDIR."/RAD_common.php",
		"SQL"       => $SOURCEDIR."/RAD_sql.php",
		"DELETE"    => $SOURCEDIR."/RAD_delete.php",
		"UPDATE"    => $SOURCEDIR."/RAD_update.php",
		"INSERT"    => $SOURCEDIR."/RAD_insert.php",
		"ERROR"     => $SOURCEDIR."/RAD_error.php",
		"HEADER"    => $SOURCEDIR."/RAD_head.php",
		"MENU"      => $SOURCEDIR."/RAD_menu.php",
		"BACKUP"    => $SOURCEDIR."/RAD_backup.php",
		"BROWSE"    => $SOURCEDIR."/RAD_browse.php",
		"DRECORD"   => $SOURCEDIR."/RAD_detail.php",
		"EFORM"     => $SOURCEDIR."/RAD_edit.php",
		"SFORM"     => $SOURCEDIR."/RAD_search.php",
		"FOOTER"    => $SOURCEDIR."/RAD_foot.php"
	);
	$comments = array(
		"JS"   => "JavaScript",
		"GENUTIL"   => "Common",
		"SQL"       => "SQL",
		"DELETE"    => "Delete",
		"UPDATE"    => "Update",
		"INSERT"    => "Insert",
		"ERROR"     => "Error",
		"HEADER"    => "Header",
		"MENU"      => "Menu",
		"BACKUP"    => "Backup",
		"BROWSE"    => "Browse",
		"DRECORD"   => "Detail",
		"EFORM"     => "Edit",
		"SFORM"     => "Search Form",
		"FOOTER"    => "Footer"
	);
	$otype = array(
		"fcopy" => "Include as common file",
		"copy" => "Copy as common file",
		"fcopyind" => "Copy as unique file",
		"include" => "Include inside module"
	);

include_once ("header.php");
OpenTable();

include_once ("modules/".$V_dir."/defaults.php");

if ($addmodfields!="") {
	if ($conf=="") {
		$modulesdir=$TMP_modulesdir;
		if ($modulesdirform!="") $modulesdir=$modulesdirform;
		selectmodfields();
		CloseTable();
		include_once ("footer.php");
		return;
	} else {
		$modulesdir=$TMP_modulesdir;
		if ($modulesdirform!="") $modulesdir=$modulesdirform;
		addmodfields();
	}
}

include_once ("modules/".$V_dir."/dbsql.php");
$dbtype=strtolower($dbtype);

set_magic_quotes_runtime (0);

$modulesdir=$TMP_modulesdir;
if ($modulesdirform!="") $modulesdir=$modulesdirform;

?>
<IMG BORDER=0 SRC="modules/<?=$V_dir?>/logo.gif" ALT="RAD">
<B><?=_DEF_NLSCreate."/"._DEF_NLSModify." "._DEF_NLSModule?></B>
<HR noshade size=1>
<b><A NAME="#"></A>
<?

	if ($numf > 0) { }
	else { $numf = 0; }
	if (!empty($project_file)) {
		$TMP_dbname=$dbname;
		$hostname="";
		$dbusername="";
		$dbpassword="";
		$dbtype="";
		include($DIRBASE.$modulesdir."/$project_file");
		$dbname=$TMP_dbname;
		if (!$fp=fopen($DIRBASE.$modulesdir."/$project_file","r")) die ("Error al abrir ".$DIRBASE.$modulesdir."/$project_file");
		while(!feof($fp)) {
		    $TMP_line=fgets($fp,64000);
		    if (substr(trim($TMP_line),0,6)=="\$title") break;
		    if ($TMP_inicio=="X") $coment.=substr($TMP_line,3);
		    if (substr(trim($TMP_line),0,2)=="<?") $TMP_inicio="X";
		}
		fclose($fp);
	}
	if ($numf==0 && $idx>0) $numf=$idx;

	echo "<form action='$PHP_SELF' name=F target=_blank method=get><input type=hidden name=menuoff value='x'><input type=hidden name=headeroff value='x'><input type=hidden name=footeroff value='x'>";
	echo "<input type=hidden name=V_dir value='$V_dir'><input type=hidden name=V_mod value='$V_mod'><input type=hidden name=V_submod value='fileedit'><input type=hidden name=PHPSESSID value=$PHPSESSID>";
	echo "<input type=hidden name=directory value='modules/".$modulesdir."'><input type=hidden name=subfunc value='browse'>";
	echo " <A HREF=\"#\" onClick=\"window.open('modules/$V_dir/help.$language.htm','help','toolbars=no,menubar=yes,scrollbars=yes,resizable=yes,width=700,height=500');\"><img border=0 src='images/info.gif'> "._DEF_NLSHelp."</A></b> | ";
	if ($addtablefields=="") echo "<A HREF='".$REQUEST_URI."&addtablefields=X$SESSION_SID'>Agrega todos los campos de la tabla</A> | ";
	else echo "<A HREF='".$PHP_SELF."?V_dir=$V_dir&V_mod=$V_mod&V_submod=$V_submod&modulesdir=$modulesdir&project_file=$project_file$SESSION_SID'>No agrega todos los campos de la tabla</A> | ";
    echo "<A HREF='".$REQUEST_URI."&addmodfields=X$SESSION_SID'>Agrega campos de otros modulos</A> &nbsp; | &nbsp; ";

	echo _DEF_NLSStringEdit." : <select name=filename onChange='javascript:form.submit();form.filename.selectedIndex=0;'><option value=''></option>";
	$TMP_existe=" ";
	if (file_exists("modules/".$modulesdir."/".$filename.".defaults.php")) $TMP_existe="*";
	echo "<option value='".$filename.".defaults.php'>".$TMP_existe."[D] ".$filename.".defaults.php</option>";
	if (_DEF_appname!="" && _DEF_appname!="_DEF_appname") {
		$TMP_existe=" ";
		if (file_exists("modules/".$modulesdir."/common.app."._DEF_appname.".php")) $TMP_existe="*";
		echo "<option value='common.app."._DEF_appname.".php'>".$TMP_existe."[C] common.app."._DEF_appname.".php</option>";
	}
	$TMP_existe=" ";
	if (file_exists("modules/".$modulesdir."/common.app.php")) $TMP_existe="*";
	echo "<option value='common.app.php'>".$TMP_existe."[C] common.app.php</option>";
	$TMP_existe=" ";
	if (file_exists("modules/".$modulesdir."/common.".$filename.".php")) $TMP_existe="*";
	echo "<option value='common.".$filename.".php'>".$TMP_existe."[C] common.".$filename.".php</option>";
	$TMP_existe=" ";
	if (file_exists("modules/".$modulesdir."/common.".$tablename.".php")) $TMP_existe="*";
	if ($filename!=$tablename) echo "<option value='common.".$tablename.".php'>".$TMP_existe."[C] common.".$tablename.".php</option>";
	$TMP_existe=" ";
	if (_DEF_appname!="" && _DEF_appname!="_DEF_appname") {
		$TMP_existe=" ";
		if (file_exists("modules/".$modulesdir."/common.".$filename."."._DEF_appname.".php")) $TMP_existe="*";
		echo "<option value='common.".$filename."."._DEF_appname.".php'>".$TMP_existe."[C] common.".$filename."."._DEF_appname.".php</option>";
		$TMP_existe=" ";
		if (file_exists("modules/".$modulesdir."/common.".$tablename."."._DEF_appname.".php")) $TMP_existe="*";
		if ($filename!=$tablename) echo "<option value='common.".$tablename."."._DEF_appname.".php'>".$TMP_existe."[C] common.".$tablename."."._DEF_appname.".php</option>";
	}
	if (file_exists("modules/".$modulesdir."/".$filename.".common.php")) {
		$TMP_existe="*";
		echo "<option value='".$filename.".common.php'>".$TMP_existe."[C] ".$filename.".common.php</option>";
	}
	if (file_exists("modules/".$modulesdir."/".$filename.".premenu.php")) $TMP_existe="*";
	echo "<option value='".$filename.".premenu.php'>".$TMP_existe."[D] ".$filename.".premenu.php</option>";
	$TMP_existe=" ";
        if (file_exists("modules/".$modulesdir."/".$filename.".postmenu.php")) $TMP_existe="*";
        echo "<option value='".$filename.".postmenu.php'>".$TMP_existe."[D] ".$filename.".postmenu.php</option>";
        $TMP_existe=" ";
        if (file_exists("modules/".$modulesdir."/".$filename.".submenu.php")) $TMP_existe="*";
        echo "<option value='".$filename.".submenu.php'>".$TMP_existe."[D] ".$filename.".submenu.php</option>";
        $TMP_existe=" ";
        if (file_exists("modules/".$modulesdir."/".$filename.".help.php")) $TMP_existe="*";
        echo "<option value='".$filename.".help.php'>".$TMP_existe."[D] ".$filename.".help.php</option>";
        $TMP_existe=" ";
	for ($i = 0; $i < $numf; $i++) {
		if ($TYPE[$i]=="function" && $DTYPE[$i]=="function" && $EXTRA[$i]!="RAD_subbrowse.php" && $EXTRA[$i]!="RAD_subbrowse") {
		    $TMP_existe=" ";
		    if (file_exists("modules/".$modulesdir."/".$EXTRA[$i])) $TMP_existe="*";
		    echo "<option value='".$EXTRA[$i]."'>".$TMP_existe."[F] ".$EXTRA[$i]."</option>";
		}
	}
	$TMP_existe=" ";
	if (file_exists("modules/".$modulesdir."/".$filename.".presql.php")) $TMP_existe="*";
	echo "<option value='".$filename.".presql.php'>".$TMP_existe."[T] ".$filename.".presql.php</option>";
	$TMP_existe=" ";
	if (file_exists("modules/".$modulesdir."/".$tablename.".presql.php")) $TMP_existe="*";
	echo "<option value='".$tablename.".presql.php'>".$TMP_existe."[T] ".$tablename.".presql.php</option>";
	$TMP_existe=" ";
	if (file_exists("modules/".$modulesdir."/".$filename.".postsql.php")) $TMP_existe="*";
	echo "<option value='".$filename.".postsql.php'>".$TMP_existe."[T] ".$filename.".postsql.php</option>";
	$TMP_existe=" ";
	if (file_exists("modules/".$modulesdir."/".$tablename.".postsql.php")) $TMP_existe="*";
	echo "<option value='".$tablename.".postsql.php'>".$TMP_existe."[T] ".$tablename.".postsql.php</option>";
	$TMP_existe=" ";
	
	if (file_exists("modules/".$modulesdir."/".$filename.".predelete.php")) $TMP_existe="*";
	echo "<option value='".$filename.".predelete.php'>".$TMP_existe."[T] ".$filename.".predelete.php</option>";
	$TMP_existe=" ";
	if (file_exists("modules/".$modulesdir."/".$tablename.".predelete.php")) $TMP_existe="*";
	echo "<option value='".$tablename.".predelete.php'>".$TMP_existe."[T] ".$tablename.".predelete.php</option>";
	$TMP_existe=" ";
	if (file_exists("modules/".$modulesdir."/".$filename.".postdelete.php")) $TMP_existe="*";
	echo "<option value='".$filename.".postdelete.php'>".$TMP_existe."[T] ".$filename.".postdelete.php</option>";
	$TMP_existe=" ";
	if (file_exists("modules/".$modulesdir."/".$tablename.".postdelete.php")) $TMP_existe="*";
	echo "<option value='".$tablename.".postdelete.php'>".$TMP_existe."[T] ".$tablename.".postdelete.php</option>";
	$TMP_existe=" ";
	if (file_exists("modules/".$modulesdir."/".$filename.".preinsert.php")) $TMP_existe="*";
	echo "<option value='".$filename.".preinsert.php'>".$TMP_existe."[T] ".$filename.".preinsert.php</option>";
	$TMP_existe=" ";
	if (file_exists("modules/".$modulesdir."/".$tablename.".preinsert.php")) $TMP_existe="*";
	echo "<option value='".$tablename.".preinsert.php'>".$TMP_existe."[T] ".$tablename.".preinsert.php</option>";
	$TMP_existe=" ";
	if (file_exists("modules/".$modulesdir."/".$filename.".postinsert.php")) $TMP_existe="*";
	echo "<option value='".$filename.".postinsert.php'>".$TMP_existe."[T] ".$filename.".postinsert.php</option>";
	$TMP_existe=" ";
	if (file_exists("modules/".$modulesdir."/".$tablename.".postinsert.php")) $TMP_existe="*";
	echo "<option value='".$tablename.".postinsert.php'>".$TMP_existe."[T] ".$tablename.".postinsert.php</option>";
	$TMP_existe=" ";
	if (file_exists("modules/".$modulesdir."/".$filename.".preupdate.php")) $TMP_existe="*";
	echo "<option value='".$filename.".preupdate.php'>".$TMP_existe."[T] ".$filename.".preupdate.php</option>";
	$TMP_existe=" ";
	if (file_exists("modules/".$modulesdir."/".$tablename.".preupdate.php")) $TMP_existe="*";
	echo "<option value='".$tablename.".preupdate.php'>".$TMP_existe."[T] ".$tablename.".preupdate.php</option>";
	$TMP_existe=" ";
	if (file_exists("modules/".$modulesdir."/".$filename.".postupdate.php")) $TMP_existe="*";
	echo "<option value='".$filename.".postupdate.php'>".$TMP_existe."[T] ".$filename.".postupdate.php</option>";
	$TMP_existe=" ";
	if (file_exists("modules/".$modulesdir."/".$tablename.".postupdate.php")) $TMP_existe="*";
	echo "<option value='".$tablename.".postupdate.php'>".$TMP_existe."[T] ".$tablename.".postupdate.php</option>";
	$TMP_existe=" ";

	echo "<option value='".$filename.".prj.php'>Codigo fuente del modulo PRJ</option>";
	echo "<option value='".$filename.".php'>Codigo fuente del modulo PHP</option>";
	echo "</select></form> ";
    echo "<A HREF='".$REQUEST_URI."&genmodmobile=X$SESSION_SID'>Genera m&oacute;dulo para versi&oacute;n m&oacute;vil</A> &nbsp;";

//	echo "<A TARGET=_BLANK HREF='$PHP_SELF?menuoff=x&headeroff=x&footeroff=x&V_dir=phpGenRAD&V_submod=$V_submod&V_submod=fileedit&directory=modules/$modulesdir&filename=".$filename.".defaults.php&subfunc=browse".$SESSION_SID."'><img border=0 src='images/edit.gif' title=''>".$filename.".defaults.php</A> | ";
 
	for ($i = 0; $i < $numf; $i++) {
		if (${"NAME".$i} != "") $NAMEFIELD[${"NAME".$i}] = $i+1;
	}
	for ($i = 0; $i < $numf+50; $i++) {
		if ($NAME[$i] != "") ${"NAME".$i} = $NAME[$i];
		if ($NAME[$i] != "") $NAMEFIELD[$NAME[$i]] = $i+1;
		if (${"NAME".$i}!="" && $i>$numf) { $numf++; }
	}

	if ($addtablefields!="") {
		$dbtype=strtolower(_DEF_dbtype);
		$db = new DBSql;
		$db -> Host = _DEF_dbhost;
		$db -> User = _DEF_dbuname;
		$db -> Password = _DEF_dbpass;
		global $dbname;
		if ($dbname!="") $db -> Database = $dbname;
		else $db -> Database = _DEF_dbname;
		$db -> Type = $dbtype;
		$db -> Debug = false;

		include_once("modules/".$V_dir."/phplibsup.php");

		$FIELD_TAB=array();
		$tables = $db -> table_names();
		for ($i = 0; $i < count($tables); $i++) {
			$TMP_tablename=$tables[$i]["table_name"];
			$TMP_posguion=strpos($TMP_tablename,"_");
			if ($TMP_posguion>0) $TMP_tablemod=substr($TMP_tablename, $TMP_posguion+1);
			else $TMP_tablemod=$TMP_tablename;
			if (file_exists("modules/".$modulesdir."/".$TMP_tablename.".php")) $TMP_tablemod=$TMP_tablename;
			if (!file_exists("modules/".$modulesdir."/".$TMP_tablemod.".php")) $TMP_tablemod=$TMP_tablename;
//echo $TMP_tablename."=>".$modulesdir."/".$TMP_tablemod."<br>";

			$TMP_tableinfo = $db->metadata($TMP_tablename);
			$RAD_dbinfo = $$dbtype;
			if ($TMP_tablename==$tablename) continue;
			for ($j = 0; $j < count($TMP_tableinfo); $j++) {
				if ($RAD_dbinfo -> isautoincrement($TMP_tableinfo[$j]["flags"])) {
//echo $TMP_tablename." ".$TMP_tableinfo[$j]["name"]."<br>";
					$KEY_TAB[$TMP_tablename]=$TMP_tableinfo[$j]["name"];
					$TMP_tableinfo[$j]["type"]="auto_increment";
					$KEYF_field[$TMP_tableinfo[$j]["name"]].=$TMP_tablename.":".$TMP_tableinfo[$j]["name"].":".$TMP_tableinfo[($j+1)]["name"]; // foreign key relation
					$MOD_field[$TMP_tableinfo[$j]["name"]].=$TMP_tablemod;
					$KEYTAB_field[$TMP_tableinfo[$j]["name"]].=$TMP_tablename;
					$TMP_res=$db->query("SELECT count(*) FROM ".$TMP_tablename);
					$KEYF_numrows[$TMP_tableinfo[$j]["name"]]=$db->num_rows();
				}
				//$FIELDS_TABS[$TMP_tableinfo[$j]["name"]].=$TMP_tablename."|"; // fields relation
				$FIELDS_TABS[$TMP_tableinfo[$j]["name"]].=$TMP_tablemod."|"; // fields relation
			}
		}
//die("PRUEBA");
		$tableinfo = $db->metadata($tablename);
		$RAD_dbinfo = $$dbtype;
	  	$PRIMARY_KEY="";
		for ($i = 0; $i < count($tableinfo); $i++) { 
		    $FIELD_TAB[$tableinfo[$i]["name"]]="x";
	  	    if ($RAD_dbinfo -> isautoincrement($tableinfo[$i]["flags"])) $PRIMARY_KEY=$tableinfo[$i]["name"];
		    if ($NAMEFIELD[$tableinfo[$i]["name"]]=="") {
			if ($RAD_dbinfo -> isautoincrement($tableinfo[$i]["flags"])) $tableinfo[$i]["type"]="auto_increment";
			${"NAME".$numf}=$tableinfo[$i]["name"];
			${"ORDERBY".$numf}=$tableinfo[$i]["name"];
			${"LENGTH".$numf}=$tableinfo[$i]["len"];
			${"TYPE".$numf}=$tableinfo[$i]["type"];
			${"DTYPE".$numf}=$tableinfo[$i]["type"];
			${"CANBENULL".$numf}=$RAD_dbinfo -> canbenull($tableinfo[$i]["flags"]) ? "on" : "";
			if ($RAD_dbinfo -> isautoincrement($tableinfo[$i]["flags"])) ${"SEARCHABLE".$numf} = "";
			else ${"SEARCHABLE".$numf} = "on";
			${"FLAGS".$numf}=$tableinfo[$i]["flags"];
			$numf++;
		    	$NAMEFIELD[$tableinfo[$i]["name"]]=$numf;
		    }
		    $numthisfield=$NAMEFIELD[$tableinfo[$i]["name"]]-1;
		    if ($KEYF_field[$tableinfo[$i]["name"]]!="" && ${"EXTRA".$numthisfield}=="") { // foreign key relation
		    	${"EXTRA".$numthisfield}=$KEYF_field[$tableinfo[$i]["name"]];
		    	${"FUNCLINK".$numthisfield}=$KEYTAB_field[$tableinfo[$i]["name"]];
		    	${"FUNCLINK".$numthisfield}=$MOD_field[$tableinfo[$i]["name"]];
			if (!ereg("db",$DTYPE[$numthisfield])) $DTYPE[$numthisfield]="plistdb";
//echo $numthisfield."+".${"TYPE".$numthisfield}."*".$DTYPE[$numthisfield]."<br>";
		    }
	  	}
		foreach($FIELDS_TABS as $TMP_field=>$TMP_tabs) { // add relation tables
		    if ($TMP_field==$PRIMARY_KEY) {
			$A_tabs=explode("|",$TMP_tabs);
			foreach($A_tabs as $TMP_idx=>$TMP_tab) {
				if ($NAMEFIELD[$TMP_tab]!="") continue;
				if ($TMP_tab=="") continue;
				${"NAME".$numf}=$TMP_tab;
				${"LENGTH".$numf}="800";
				${"ILENGTH".$numf}="600";
				${"TYPE".$numf}="function";
				${"DTYPE".$numf}="function";
				${"EXTRA".$numf}="RAD_subbrowse.php";
				${"BROWSEDIT".$numf} = "on";
				${"VDEFAULT".$numf} = $PRIMARY_KEY;
				${"FUNCLINK".$numf}=$TMP_tab;
				//${"FUNCLINK".$numf}=$MOD_field[$TMP_tab];
				//${"VDEFAULT".$numf} = $KEY_TAB[$TMP_tab].":".$PRIMARY_KEY;
				$numf++;
		    		$NAMEFIELD[$TMP_tab]=$numf;
			}
		    }
		}
		$TMP_errores="";
		for ($i = 0; $i < $numf; $i++) { // add table relation foreign key
			$TMP_namefield=${"NAME".$i};
			$TMP_typefield=${"TYPE".$i};
			if ($TMP_typefield=="") $TMP_typefield=$TYPE[$i];
			if ($FIELD_TAB[$TMP_namefield]=="" && $TMP_typefield!="function") {
				$TMP_errores.=$TMP_namefield."<br>";
			}
		}
		if ($TMP_errores!="") echo "<h2>ERROR: Campos de datos que no existen en la Tabla y estan declarados en el modulo</h2><ul>".$TMP_errores."</ul>";
	}

	$numf=$numf+3;
	if (empty($project_file)) {
	    for ($i = 0; $i < $numf; $i++) {
		if (!empty(${"NAME$i"})) {
		    if (${"TYPE$i"}=="auto_increment") ${"SEARCHABLE$i"} = ${"BROWSABLE$i"} = "";
		    else ${"SEARCHABLE$i"} = ${"BROWSABLE$i"} = "on";
		    if (${"TYPE$i"}=="blob") {
			${"ILENGTH$i"} = "60x3";
			${"BROWSABLE$i"} = "";
		    }
		}
	    }
	}
?>
		<FORM ACTION="index.php" METHOD="POST" name=GEN>
			<INPUT TYPE="HIDDEN" NAME="PHPSESSID" VALUE="<? echo $PHPSESSID; ?>">
			<INPUT TYPE="HIDDEN" NAME="V_dir" VALUE="<? echo $V_dir; ?>">
			<INPUT TYPE="HIDDEN" NAME="V_mod" VALUE="<? echo $V_mod; ?>">
			<INPUT TYPE="HIDDEN" NAME="V_submod" VALUE="genmod">
			<INPUT TYPE="HIDDEN" NAME="modulesdir" VALUE="<? echo $modulesdir; ?>">
			<INPUT TYPE="HIDDEN" NAME="numf" VALUE="<? echo $numf; ?>">
			<CENTER>
				<TABLE cellspacing=0 cellpadding=0>
					<TR>
						<TD align=right valign=top width=10%>
						    <b> Comentarios : </b>
						</TD>
						<TD>
						    <TEXTAREA NAME=coment COLS=120 ROWS=8><?=htmlspecialchars($coment)?></TEXTAREA>
						</TD>
					</TR>

<?
//	echo "<TR><TD align=right valign=top><B>"._DEF_NLSThemeFile." : </B></TD><TD>";
//	echo "<SELECT NAME='GEN_themefile'>";
	$f = opendir("modules/$themedir");
	if ($GEN_themefile!="") { $tfile = $GEN_themefile; }
	else if ($themefile!="") { $tfile = $themefile; }
	else { $tfile = $defaultthemefile; }
	while ($fn=readdir($f)) {
		if (ereg("php$",$fn)) {
//			echo "<OPTION VALUE='$fn'";
//			if (ereg("^$tfile$",$fn)) echo " SELECTED";
//			echo ">$fn</OPTION>\n";
		}
	}
	if ($filename =="") $filename=$tablename;
	if ($_GET['genmodmobile']=="X" && !preg_match("/m./",$filename)) $filename="m.".$filename;
	if ($title =="") $title=ucwords($filename);
//	echo "</SELECT></TD></TR>";
	echo "<INPUT TYPE=HIDDEN NAME='GEN_themefile' VALUE='$tfile'>";

	//if ($dbname=="") $dbname=_DEF_dbname; 
	if ($dbname==_DEF_dbname) $dbname=""; 
	//echo "<TR><TD align=right valign=top><B>"._DEF_NLSDatabaseName." : </B></TD><TD>";
	echo "<INPUT TYPE=HIDDEN NAME='V0_dbname' SIZE='15' VALUE='".$dbname."'>";
	//if ($hostname=="") $hostname=_DEF_dbhost;
	//if ($dbusername=="") $dbusername=_DEF_dbuname;
	//if ($dbpassword=="") $dbpassword=_DEF_dbpass;
	//if ($dbtype=="") $dbtype=_DEF_dbtype;
	//echo " | <b>Hostname :</b>";
	echo "<INPUT TYPE='HIDDEN' SIZE=8 NAME='hostname' VALUE='".$hostname."'>";
	//echo " | <b>DBUsername :</b>";
	echo "<INPUT TYPE='HIDDEN' SIZE=6 NAME='dbusername' VALUE='".$dbusername."'>";
	//echo " | <b>DBPassword :</b>";
	echo "<INPUT TYPE='HIDDEN' SIZE=4 NAME='dbpassword' VALUE='".$dbpassword."'>";
	echo "<INPUT TYPE='HIDDEN' NAME='GEN_dbtype' VALUE='".$dbtype."'>";
//	echo "<INPUT TYPE="TEXT" NAME='V0_dbname' TYPE=HIDDEN VALUE=''></TD></TR>";
	//echo "</TD></TR>";
?>
					<TR>
						<TD align=right valign=top>
						<B><?=_DEF_NLSTableName?> : </B></TD>
						<TD><INPUT TYPE="TEXT" NAME="tablename" SIZE="25" VALUE="<? echo $tablename; ?>"></TD>
					</TR>
					<TR>
						<TD align=right valign=top>
						<B><?=_DEF_NLSOption?> : </B></TD>
						<TD nowrap>
		<INPUT TYPE="CHECKBOX" NAME="menubackup" <? if ($menubackup) echo "CHECKED"; ?> VALUE="x">Backup. &nbsp; 
		<INPUT TYPE="CHECKBOX" NAME="menubrowse" <? if ($menubrowse || !isset($menubrowse)) echo "CHECKED"; ?> VALUE="x">Browse. &nbsp; 
		<INPUT TYPE="CHECKBOX" NAME="menuminisearch" <? if ($menuminisearch) echo "CHECKED"; ?> VALUE="x">Minisearch. &nbsp; 
		<INPUT TYPE="CHECKBOX" NAME="menudetail" <? if ($menudetail || !isset($menudetail)) echo "CHECKED"; ?> VALUE="x">Detail. &nbsp; 
		<INPUT TYPE="CHECKBOX" NAME="menusearch" <? if ($menusearch || !isset($menusearch)) echo "CHECKED"; ?> VALUE="x">Search. &nbsp; 
		<INPUT TYPE="CHECKBOX" NAME="menuprint" <? if (($menuprint || !isset($menuprint)) && $_GET['genmodmobile']!="X") echo "CHECKED"; ?> VALUE="x">Print. &nbsp; 
		<INPUT TYPE="CHECKBOX" NAME="menunew" <? if ($menunew || !isset($menunew)) echo "CHECKED"; ?> VALUE="x">New. &nbsp; 
		<INPUT TYPE="CHECKBOX" NAME="menuedit" <? if ($menuedit || !isset($menuedit)) echo "CHECKED"; ?> VALUE="x">Edit. &nbsp; 
		<INPUT TYPE="CHECKBOX" NAME="menudelete" <? if ($menudelete || !isset($menudelete)) echo "CHECKED"; ?> VALUE="x">Delete. &nbsp; 
		<INPUT TYPE="CHECKBOX" NAME="menujump" <? if ($menujump && $_GET['genmodmobile']!="X") echo "CHECKED"; ?> VALUE="x">Jump. &nbsp; 
		<INPUT TYPE="CHECKBOX" NAME="menusend" <? if ($menusend && $_GET['genmodmobile']!="X") echo "CHECKED"; ?> VALUE="x">Send. &nbsp; 
		<INPUT TYPE="CHECKBOX" NAME="menubletter" <? if ($menubletter && $_GET['genmodmobile']!="X") echo "CHECKED"; ?> VALUE="x">Letr. &nbsp; 
		<INPUT TYPE="CHECKBOX" NAME="menureport" <? if (($menureport || !isset($menureport)) && $_GET['genmodmobile']!="X") echo "CHECKED"; ?> VALUE="x">Report. &nbsp; 
		<INPUT TYPE="CHECKBOX" NAME="menuoff" <? if ($menuoff!="") echo "CHECKED"; ?> VALUE="x">Menu OFF. &nbsp; <br>
		<INPUT TYPE="CHECKBOX" NAME="menutree" <? if ($menutree) echo "CHECKED"; ?> VALUE="x">Tree. &nbsp; 
		Field Id Tree:<INPUT TYPE="TEXT" NAME="browsetreefield" SIZE=10 VALUE="<?=$browsetreefield?>">&nbsp; 
		Field Parent Id Tree:<INPUT TYPE="TEXT" NAME="browsetreefieldparent" SIZE=10 VALUE="<?=$browsetreefieldparent?>">&nbsp;|
		<INPUT TYPE="CHECKBOX" NAME="menucalendar" <? if ($menucalendar) echo "CHECKED"; ?> VALUE="x">Calendar. &nbsp; 
		Calendar's Fields:<INPUT TYPE="TEXT" NAME="calendarfields" VALUE="<?=$calendarfields?>">&nbsp; 
						</TD>
					</TR>
					<TR>
						<TD align=right valign=top nowrap>
						<B><?=_DEF_NLSBrowseType?> : </B></TD>
						<TD>
						    <INPUT TYPE="RADIO" NAME="browsetype" <? if ($browsetype=="") echo "CHECKED"; ?> VALUE=""> Normal | 
						    <INPUT TYPE="RADIO" NAME="browsetype" <? if ($browsetype=="line") echo "CHECKED"; ?> VALUE="line"> Line | 
						    <INPUT TYPE="RADIO" NAME="browsetype" <? if ($browsetype=="editline") echo "CHECKED"; ?> VALUE="editline"> Editline | 
						    <INPUT TYPE="RADIO" NAME="browsetype" <? if ($browsetype=="deleteline") echo "CHECKED"; ?> VALUE="deleteline"> Deleteline | 
						    <INPUT TYPE="RADIO" NAME="browsetype" <? if ($browsetype=="insert") echo "CHECKED"; ?> VALUE="insert"> Insert 
						    (en el fondo <INPUT TYPE="checkbox" NAME="V_rowsinsertend" <? if ($V_rowsinsertend!="") echo "CHECKED"; ?> VALUE="x"> | 
						    lineas <INPUT TYPE="text" SIZE=2 NAME="V_rowsinsert" VALUE="<?=$V_rowsinsert?>">) | 
                                                    <INPUT TYPE="RADIO" NAME="browsetype" <? if ($browsetype=="dinamicinsert") echo "CHECKED"; ?> VALUE="dinamicinsert"> Insert dinamico 
						</TD>
					</TR>
					<TR>
						<TD align=right valign=top>
						<B><?=_DEF_NLSFilter." "._DEF_NLSTable?> : </B></TD>
						<TD>
						<INPUT TYPE=TEXT NAME=defaultfilter SIZE=50 VALUE="<? echo $defaultfilter; ?>"> / <b>ORDER BY : </b>
						<INPUT TYPE=TEXT NAME=orderby SIZE=30 VALUE="<? echo $orderby; ?>">
						</TD>
					</TR>
					<TR>
						<TD align=right valign=top>
						<B><u><?=_DEF_NLSPage?></u> : </B></TD>
						<TD><INPUT TYPE="TEXT" NAME="prefix" SIZE='25' VALUE="<? echo $filename; ?>"> </TD>
					</TR>
					<TR>
						<TD align=right valign=top>
						<B><?=_DEF_NLSTitle?> : </B></TD>
						<TD><INPUT TYPE="TEXT" NAME="GEN_title" SIZE="80" VALUE="<? echo $title; ?>"></TD>
					</TR>
					<TR>
						<TD align=right valign=top>
						<B><u><?=_DEF_NLSDirectory?></u> : </B></TD>
						<TD><? echo $DIRBASE; ?><INPUT TYPE="TEXT" NAME="modulesdir" SIZE="15" VALUE="<? echo $modulesdir; ?>"></TD>
					</TR>
					<TR>
						<TD align=right valign=top>
						<B><?=_DEF_NLSMax." "._DEF_NLSRow."(s) "._DEF_NLSStringBrowse?> : </B></TD>
						<TD><INPUT TYPE=TEXT NAME="rows_limit" SIZE="8" VALUE="<?=$rows_limit?>"></TD>
					</TR>
					<TR>
						<TD align=right valign=top>
						<B><?="Columnas "._DEF_NLSStringDetail." / "._DEF_NLSStringEdit?> : </B></TD>
						<TD><INPUT TYPE=TEXT NAME="V_colsdetail" SIZE="5" VALUE="<?=$V_colsdetail?>">
						/ <INPUT TYPE=TEXT NAME="V_colsedit" SIZE="5" VALUE="<?=$V_colsedit?>"></TD>
					</TR>
					<TR>
						<TD align=right valign=top>
						<B><?=_DEF_NLSKey." "._DEF_NLSField."(s)"?> : </B></TD>
						<TD>
						<INPUT TYPE=TEXT NAME=idnames SIZE=50 VALUE='<? echo $idnames; ?>'>
						</TD>
					</TR>
<INPUT TYPE=HIDDEN NAME="RAD" VALUE="x">
<INPUT TYPE=HIDDEN NAME='security' VALUE=0>

					<! TR><! TD align=right valign=top><! B><! RAD : ><! /B><! /TD>
						<! TD><! INPUT TYPE="CHECKBOX" NAME="RAD" <? if ($RAD) echo "CHECKED"; ?> VALUE="x"><! /TD><! /TR>
					<! TR><! TD align=right valign=top><! B><! <?=_DEF_NLSSecurity?> : ><! /B><! /TD>
						<! TD><! SELECT NAME='security' SINGLE>
<! OPTION VALUE=0<? if($security=="0") echo " SELECTED"; ?>><! /OPTION>
<! OPTION VALUE=1<? if($security=="1") echo " SELECTED"; ?>><! User Control><! /OPTION>
<! OPTION VALUE=2<? if($security=="2") echo " SELECTED"; ?>><! User and Table Control><! /OPTION><! /SELECT><! /TD><! /TR>
					<TR>
						<TD align=right valign=top>
						<B><?=_DEF_NLSFirst." "._DEF_NLSOption?> : </B></TD>
						<TD><SELECT NAME='defaultfunc' SINGLE>
<OPTION VALUE=></OPTION>
<OPTION VALUE='browse'<? if($defaultfunc=="browse") echo " SELECTED"; ?>>Browse</OPTION>
<OPTION VALUE='browsetree'<? if($defaultfunc=="browsetree") echo " SELECTED"; ?>>Tree</OPTION>
<OPTION VALUE='detail'<? if($defaultfunc=="detail") echo " SELECTED"; ?>>Detail</OPTION>
<OPTION VALUE='searchform'<? if($defaultfunc=="searchform") echo " SELECTED"; ?>>Search</OPTION>
<OPTION VALUE='print'<? if($defaultfunc=="print") echo " SELECTED"; ?>>Print</OPTION>
<OPTION VALUE='new'<? if($defaultfunc=="new") echo " SELECTED"; ?>>New</OPTION>
<OPTION VALUE='edit'<? if($defaultfunc=="edit") echo " SELECTED"; ?>>Edit</OPTION>
<OPTION VALUE='delete'<? if($defaultfunc=="delete") echo " SELECTED"; ?>>Delete</OPTION>
<OPTION VALUE='browsecalendar'<? if($defaultfunc=="calendar") echo " SELECTED"; ?>>Calendar</OPTION>
		</SELECT></TD>
					</TR>
					<TR>
						<TD align=right valign=top>
						<B><?=_DEF_NLSWrite?> Log SQL : </B></TD>
						<TD><INPUT TYPE=HIDDEN NAME="logsql" VALUE="x"><img src='images/checked.gif' border=0><!INPUT TYPE="CHECKBOX" NAME="logsql" <? //if ($logsql) echo "CHECKED"; ?> VALUE="x"> | Muestra SQL (DEBUG) : <INPUT TYPE="CHECKBOX" NAME="verbose_queries"></TD>
					</TR>
				</TABLE>
<hr noshade size=1>
				<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0">
<?
	$numline = 0;
	for ($i = 0; $i < $numf; $i++) {
	    if ($TITLE[$i] != "") ${"TITLE$i"} = $TITLE[$i];
	    if ($NAME[$i] != "") ${"NAME$i"} = $NAME[$i];
	    if ($LENGTH[$i] != "") ${"LENGTH$i"} = $LENGTH[$i];
	    if ($ILENGTH[$i] != "") ${"ILENGTH$i"} = $ILENGTH[$i];
	    if ($ORDERBY[$i] != "") ${"ORDERBY$i"} = $ORDERBY[$i];
	    if ($CANBENULL[$i] != "") ${"CANBENULL$i"} = $CANBENULL[$i];
	    if ($TYPE[$i] != "") ${"TYPE$i"} = $TYPE[$i];
	    if ($DTYPE[$i] != "") ${"DTYPE$i"} = $DTYPE[$i];
	    if ($EXTRA[$i] != "") ${"EXTRA$i"} = $EXTRA[$i];
	    if ($VDEFAULT[$i] != "") ${"VDEFAULT$i"} = $VDEFAULT[$i];
	    if ($LISTA[$i] != "") ${"LISTA$i"} = $LISTA[$i];
	    if ($VONFOCUS[$i] != "") ${"VONFOCUS$i"} = $VONFOCUS[$i];
	    if ($VONCHANGE[$i] != "") ${"VONCHANGE$i"} = $VONCHANGE[$i];
	    if ($VONBLUR[$i] != "") ${"VONBLUR$i"} = $VONBLUR[$i];
	    if ($COLUMN[$i] != "") ${"COLUMN$i"} = $COLUMN[$i];
	    if ($HELP[$i] != "") ${"HELP$i"} = trim($HELP[$i]);
	    if ($DESCRIPTION[$i] != "") ${"DESCRIPTION$i"} = $DESCRIPTION[$i];
	    if ($FUNCNEW[$i] != "") ${"FUNCNEW$i"} = $FUNCNEW[$i];
	    if ($FUNCLINK[$i] != "") ${"FUNCLINK$i"} = $FUNCLINK[$i];
	    if ($OVERLAP[$i] != "") ${"OVERLAP$i"} = $OVERLAP[$i];
	    if ($SEARCHABLE[$i] != "") ${"SEARCHABLE$i"} = $SEARCHABLE[$i];
	    if ($BROWSABLE[$i] != "") ${"BROWSABLE$i"} = $BROWSABLE[$i];
	    if ($BROWSEDIT[$i] != "") ${"BROWSEDIT$i"} = $BROWSEDIT[$i];
	    if ($FIELDEDIT[$i] != "") ${"FIELDEDIT$i"} = $FIELDEDIT[$i];
	    if ($READONLY[$i] != "") ${"READONLY$i"} = $READONLY[$i];
	    if ($NONEW[$i] != "") ${"NONEW$i"} = $NONEW[$i];
	    if ($NOEDIT[$i] != "") ${"NOEDIT$i"} = $NOEDIT[$i];
	    if ($NODETAIL[$i] != "") ${"NODETAIL$i"} = $NODETAIL[$i];
	    if ($NOINSERT[$i] != "") ${"NOINSERT$i"} = $NOINSERT[$i];
	    if ($NOUPDATE[$i] != "") ${"NOUPDATE$i"} = $NOUPDATE[$i];
	    if ($NOPRINT[$i] != "") ${"NOPRINT$i"} = $NOPRINT[$i];
	    if ($BROWSESEARCH[$i] != "") ${"BROWSESEARCH$i"} = $BROWSESEARCH[$i];
	    if ($COLEDIT[$i] != "") ${"COLEDIT$i"} = $COLEDIT[$i];
	    if ($ROWEDIT[$i] != "") ${"ROWEDIT$i"} = $ROWEDIT[$i];
	    if ($COLDETAIL[$i] != "") ${"COLDETAIL$i"} = $COLDETAIL[$i];
	    if ($ROWDETAIL[$i] != "") ${"ROWDETAIL$i"} = $ROWDETAIL[$i];
	    if ($V_COLSEDIT[$i] != "") ${"V_COLSEDIT$i"} = $V_COLSEDIT[$i];
	    if ($V_COLSDETAIL[$i] != "") ${"V_COLSDETAIL$i"} = $V_COLSDETAIL[$i];
	    if ($_GET['genmodmobile']=="X" && preg_match("/RAD_subbrowse/",${"EXTRA$i"})) continue;
	if ($numline%10 == 0) {
?>
					<TR>
						<TD></TD><TH VALIGN="BOTTOM"><?=_DEF_NLSOrder?></TH>
						<TH VALIGN="BOTTOM"><?=_DEF_NLSTitle?></TH>
						<TH VALIGN="BOTTOM"><?=_DEF_NLSField?></TH>
						<TH VALIGN="BOTTOM"><?=_DEF_NLSMax."<br>"._DEF_NLSLength?></TH>
						<TH VALIGN="BOTTOM"><?=_DEF_NLSInput."<br>"._DEF_NLSLength?></TH>
						<TD VALIGN="BOTTOM" ALIGN="CENTER"><IMG SRC="modules/<?=$V_dir?>/order.gif" ALT="Ordenado por"></TD>
						<TD VALIGN="BOTTOM" ALIGN="CENTER"><IMG SRC="modules/<?=$V_dir?>/null.gif" ALT="Puesd estar vacio?"></TD>
						<TH VALIGN="BOTTOM"><?=_DEF_NLSField?><BR><?=_DEF_NLSType?></TH>
						<TH VALIGN="BOTTOM"><?=_DEF_NLSInput?><BR><?=_DEF_NLSType?></TH>
						<TH VALIGN="BOTTOM"><?=_DEF_NLSExtra?><BR><?=_DEF_NLSData?></TH>
						<TD VALIGN="BOTTOM" ALIGN="CENTER"><IMG SRC="modules/<?=$V_dir?>/search.gif" ALT="Buscar por"></TD>
						<TD VALIGN="BOTTOM" ALIGN="CENTER"><IMG SRC="modules/<?=$V_dir?>/visible.gif" ALT="Visualizable"></TD>
						<TD VALIGN="BOTTOM" ALIGN="CENTER"><IMG SRC="modules/<?=$V_dir?>/brwedit.gif" ALT="Editable en Listado"></TD>
						<TD VALIGN="BOTTOM" ALIGN="CENTER"><IMG SRC="modules/<?=$V_dir?>/fieldedit.gif" ALT="Editable en Ventana"></TD>
						<TD VALIGN="BOTTOM" ALIGN="CENTER"><IMG SRC="modules/<?=$V_dir?>/ronly.gif" ALT="No Editable"></TD>
						<TD VALIGN="BOTTOM" ALIGN="CENTER"><IMG SRC="modules/<?=$V_dir?>/nonew.gif" ALT="Nuevo"></TD>
						<TD VALIGN="BOTTOM" ALIGN="CENTER"><IMG SRC="modules/<?=$V_dir?>/nodetail.gif" ALT="Detalle"></TD>
						<TD VALIGN="BOTTOM" ALIGN="CENTER"><IMG SRC="modules/<?=$V_dir?>/noedit.gif" ALT="Edita"></TD>
						<TD VALIGN="BOTTOM" ALIGN="CENTER"><IMG SRC="modules/<?=$V_dir?>/noins.gif" ALT="Inserta"></TD>
						<TD VALIGN="BOTTOM" ALIGN="CENTER"><IMG SRC="modules/<?=$V_dir?>/noupd.gif" ALT="Modifica"></TD>
						<TD VALIGN="BOTTOM" ALIGN="CENTER"><IMG SRC="modules/<?=$V_dir?>/noprint.gif" ALT="Imprime"></TD>
						<TD VALIGN="BOTTOM" ALIGN="CENTER"><IMG SRC="modules/<?=$V_dir?>/brwsrch.gif" ALT="Busca en lista"></TD>
						<TH VALIGN="BOTTOM"><?=_DEF_NLSOverlap?></TH>
						<TH VALIGN="BOTTOM">Val.<BR>Def.</TH>
						<TH VALIGN="BOTTOM">LoV</TH>
						<TH VALIGN="BOTTOM">onfocus<BR>Func.</TH>
						<TH VALIGN="BOTTOM">onchange<BR>Func.</TH>
						<TH VALIGN="BOTTOM">onblur<BR>Func.</TH>
						<TH VALIGN="BOTTOM"><?=_DEF_NLSHelp?></TH>
						<TH VALIGN="BOTTOM">Modulo<br>Enlace</TH>
						<TH VALIGN="BOTTOM">Modulo<br><?=_DEF_NLSStringNew?></TH>
					</TR>
<?
	}
	$numline++;
	$ucnm = ucwords(strtr(${"NAME$i"},"_-.","   "));
?>
					<TR>
						<TD><a href='javascript:cpFieldsForm(<? echo "\"$i\"";?>);showLayer("field");'><img src='images/lupa.gif' border=0></a></TD>
						<TD nowrap align=right><? echo ($i+1); ?> <INPUT TYPE="TEXT" ID="FIELDORDER<? echo $i; ?>" NAME="FIELDORDER<? echo $i; ?>" SIZE="1"></TD>
						<TD>
							<INPUT TYPE="TEXT" ID="TITLE<? echo $i; ?>" NAME="TITLE<? echo $i; ?>" SIZE="8" 
							  VALUE="<? echo (${"TITLE$i"} != "" ? htmlspecialchars(${"TITLE$i"}) : 
							  	$ucnm); ?>">
						</TD>
						<TD>
							<INPUT TYPE="TEXT" ID="NAME<? echo $i; ?>" NAME="NAME<? echo $i; ?>" SIZE="6"
								VALUE="<? echo ${"NAME$i"}; ?>">
						</TD>
						<TD>
							<INPUT TYPE="TEXT" ID="LENGTH<? echo $i; ?>" NAME="LENGTH<? echo $i; ?>" SIZE="2"  
								VALUE="<? echo ${"LENGTH$i"}; ?>">
						</TD>
						<TD>
							<INPUT TYPE="TEXT" ID="ILENGTH<? echo $i; ?>" NAME="ILENGTH<? echo $i; ?>" SIZE="2" 
								VALUE="<? if (${"LENGTH$i"} != "") {
									echo (${"ILENGTH$i"} != "" ? ${"ILENGTH$i"} :
										min((int)${"LENGTH$i"},$max_inputlength));
								} ?>">
						</TD>
						<TD WIDTH="25" BGCOLOR="#EEFFAA" ALIGN="CENTER">
							<INPUT TYPE="CHECKBOX" ID="ORDERBY<? echo $i; ?>" NAME="ORDERBY<? echo $i; ?>" VALUE="<? if (${"NAME$i"}!="") echo ${"NAME$i"}; else echo " "; ?>" <? 
								if (${"ORDERBY$i"} != "") echo " CHECKED";
								 ?>>
						</TD>
						<TD WIDTH="25" BGCOLOR="#AAFFEE" ALIGN="CENTER">
							<INPUT TYPE="CHECKBOX" ID="CANBENULL<? echo $i; ?>" NAME="CANBENULL<? echo $i; ?>" <? 
								if (${"CANBENULL$i"} == "on") echo " CHECKED"; ?>>
						</TD>
						<TD WIDTH="25" BGCOLOR="#EEFFAA" ALIGN="CENTER">
							<SELECT ID="TYPE<? echo $i; ?>" NAME="TYPE<? echo $i; ?>">
<? 
	$type = ${"TYPE$i"};
	$dtype = $type2dtype_arr[$type];
	array2select($types_arr,$type,8);
?>
							</SELECT>
						</TD>
						<TD WIDTH="60" BGCOLOR="#AAFFEE" ALIGN="CENTER">
							<SELECT ID="DTYPE<? echo $i; ?>" NAME="DTYPE<? echo $i; ?>">
<?
    if ($_GET['genmodmobile']=="X") {
        if (preg_match("/popup/",${"DTYPE$i"})) ${"DTYPE$i"}=str_replace("popup","plist",${"DTYPE$i"});
        elseif (${"DTYPE$i"}=="date") ${"DTYPE$i"}="datetext";
        elseif (${"DTYPE$i"}=="datetime") ${"DTYPE$i"}="datetimetext";
    }
    array2select($dtypes_arr,${"DTYPE$i"},8);
?>
							</SELECT>
						</TD>
						<TD BGCOLOR="#EEFFAA" ALIGN="CENTER">
							<INPUT TYPE="TEXT" WIDTH="35" SIZE="18" ID="EXTRA<? echo $i; ?>" NAME="EXTRA<? echo $i; ?>"
							 VALUE="<? echo htmlspecialchars(${"EXTRA$i"}); ?>">
						</TD>
						<TD WIDTH="25" BGCOLOR="#AAFFEE" ALIGN="CENTER">
							<INPUT TYPE="CHECKBOX" ID="SEARCHABLE<? echo $i; ?>" NAME="SEARCHABLE<? echo $i; ?>"
							 <? echo (${"SEARCHABLE$i"} == "on" ? " CHECKED" : ""); ?>>
						</TD>
						<TD WIDTH="25" BGCOLOR="#EEFFAA" ALIGN="CENTER">
							<INPUT TYPE="CHECKBOX" ID="BROWSABLE<? echo $i; ?>" NAME="BROWSABLE<? echo $i; ?>"
							<? echo (${"BROWSABLE$i"} == "on" ? " CHECKED" : ""); ?>>
						</TD> 
						<TD WIDTH="25" BGCOLOR="#EEFFAA" ALIGN="CENTER">
							<INPUT TYPE="CHECKBOX" ID="BROWSEDIT<? echo $i; ?>" NAME="BROWSEDIT<? echo $i; ?>"
							<? echo (${"BROWSEDIT$i"} == "on" ? " CHECKED" : ""); ?>>
						</TD> 
						<TD WIDTH="25" BGCOLOR="#EEFFAA" ALIGN="CENTER">
							<INPUT TYPE="CHECKBOX" ID="FIELDEDIT<? echo $i; ?>" NAME="FIELDEDIT<? echo $i; ?>"
							<? echo (${"FIELDEDIT$i"} == "on" ? " CHECKED" : ""); ?>>
						</TD> 
						<TD WIDTH="25" BGCOLOR="#EEFFAA" ALIGN="CENTER">
							<INPUT TYPE="CHECKBOX" ID="READONLY<? echo $i; ?>" NAME="READONLY<? echo $i; ?>"
							<? echo (${"READONLY$i"} == "on" ? " CHECKED" : ""); ?>>
						</TD> 
						<TD WIDTH="25" BGCOLOR="#EEFFAA" ALIGN="CENTER">
							<INPUT TYPE="CHECKBOX" ID="NONEW<? echo $i; ?>" NAME="NONEW<? echo $i; ?>"
							<? echo (${"NONEW$i"} == "on" ? " CHECKED" : ""); ?>>
						</TD> 
						<TD WIDTH="25" BGCOLOR="#EEFFAA" ALIGN="CENTER">
							<INPUT TYPE="CHECKBOX" ID="NODETAIL<? echo $i; ?>" NAME="NODETAIL<? echo $i; ?>"
							<? echo (${"NODETAIL$i"} == "on" ? " CHECKED" : ""); ?>>
						</TD> 
						<TD WIDTH="25" BGCOLOR="#EEFFAA" ALIGN="CENTER">
							<INPUT TYPE="CHECKBOX" ID="NOEDIT<? echo $i; ?>" NAME="NOEDIT<? echo $i; ?>"
							<? echo (${"NOEDIT$i"} == "on" ? " CHECKED" : ""); ?>>
						</TD> 
						<TD WIDTH="25" BGCOLOR="#EEFFAA" ALIGN="CENTER">
							<INPUT TYPE="CHECKBOX" ID="NOINSERT<? echo $i; ?>" NAME="NOINSERT<? echo $i; ?>"
							<? echo (${"NOINSERT$i"} == "on" ? " CHECKED" : ""); ?>>
						</TD> 
						<TD WIDTH="25" BGCOLOR="#EEFFAA" ALIGN="CENTER">
							<INPUT TYPE="CHECKBOX" ID="NOUPDATE<? echo $i; ?>" NAME="NOUPDATE<? echo $i; ?>"
							<? echo (${"NOUPDATE$i"} == "on" ? " CHECKED" : ""); ?>>
						</TD> 
						<TD WIDTH="25" BGCOLOR="#EEFFAA" ALIGN="CENTER">
							<INPUT TYPE="CHECKBOX" ID="NOPRINT<? echo $i; ?>" NAME="NOPRINT<? echo $i; ?>"
							<? echo (${"NOPRINT$i"} == "on" ? " CHECKED" : ""); ?>>
						</TD> 
						<TD WIDTH="25" BGCOLOR="#EEFFAA" ALIGN="CENTER">
							<INPUT TYPE="CHECKBOX" ID="BROWSESEARCH<? echo $i; ?>" NAME="BROWSESEARCH<? echo $i; ?>"
							<? echo (${"BROWSESEARCH$i"} == "on" ? " CHECKED" : ""); ?>>
						</TD> 
						<TD BGCOLOR="#EEFFAA" ALIGN="CENTER">
							<INPUT TYPE="TEXT" WIDTH="35" SIZE="6" ID="OVERLAP<? echo $i; ?>" NAME="OVERLAP<? echo $i; ?>"
							 VALUE="<? echo ${"OVERLAP$i"}; ?>">
						</TD>
						<TD BGCOLOR="#EEFFAA" ALIGN="CENTER">
							<INPUT TYPE="TEXT" WIDTH="35" SIZE="5" ID="VDEFAULT<? echo $i; ?>" NAME="VDEFAULT<? echo $i; ?>"
							 VALUE="<? echo htmlspecialchars(${"VDEFAULT$i"}); ?>">
						</TD>
						<TD BGCOLOR="#EEFFAA" ALIGN="CENTER">
							<INPUT TYPE="TEXT" WIDTH="35" SIZE="5" ID="LISTA<? echo $i; ?>" NAME="LISTA<? echo $i; ?>"
							 VALUE="<? echo ${"LISTA$i"}; ?>">
						</TD>
						<TD BGCOLOR="#EEFFAA" ALIGN="CENTER">
							<INPUT TYPE="TEXT" WIDTH="35" SIZE="5" ID="VONFOCUS<? echo $i; ?>" NAME="VONFOCUS<? echo $i; ?>"
							 VALUE="<? echo htmlspecialchars(${"VONFOCUS$i"}); ?>">
						</TD>
						<TD BGCOLOR="#EEFFAA" ALIGN="CENTER">
							<INPUT TYPE="TEXT" WIDTH="35" SIZE="5" ID="VONCHANGE<? echo $i; ?>" NAME="VONCHANGE<? echo $i; ?>"
							 VALUE="<? echo htmlspecialchars(${"VONCHANGE$i"}); ?>">
						</TD>
						<TD BGCOLOR="#EEFFAA" ALIGN="CENTER">
 							<INPUT TYPE="TEXT" WIDTH="35" SIZE="5" ID="VONBLUR<? echo $i; ?>" NAME="VONBLUR<? echo $i; ?>"
 							 VALUE="<? echo htmlspecialchars(${"VONBLUR$i"}); ?>">
						</TD>
						<TD BGCOLOR="#EEFFAA" ALIGN="CENTER">
							<INPUT TYPE="TEXT" WIDTH="35" SIZE="5" ID="HELP<? echo $i; ?>" NAME="HELP<? echo $i; ?>"
							 VALUE="<? echo htmlspecialchars(${"HELP$i"}); ?>">
							<INPUT TYPE=HIDDEN ID="DESCRIPTION<? echo $i; ?>" NAME="DESCRIPTION<? echo $i; ?>"
							 VALUE="<? echo htmlspecialchars(${"DESCRIPTION$i"}); ?>">
						</TD>
						<TD BGCOLOR="#EEFFAA" ALIGN="CENTER">
							<INPUT TYPE="TEXT" WIDTH="35" SIZE="5" ID="FUNCLINK<? echo $i; ?>" NAME="FUNCLINK<? echo $i; ?>"
							 VALUE="<? echo ${"FUNCLINK$i"}; ?>">
						</TD>
						<TD BGCOLOR="#EEFFAA" ALIGN="CENTER">
							<INPUT TYPE="TEXT" WIDTH="35" SIZE="5" ID="FUNCNEW<? echo $i; ?>" NAME="FUNCNEW<? echo $i; ?>"
							 VALUE="<? echo ${"FUNCNEW$i"}; ?>">
							<INPUT ID="COLEDIT<? echo $i; ?>" NAME="COLEDIT<? echo $i; ?>"
							 TYPE=HIDDEN VALUE="<? echo ${"COLEDIT$i"}; ?>">
							<INPUT ID="V_COLSEDIT<? echo $i; ?>" NAME="V_COLSEDIT<? echo $i; ?>"
							 TYPE=HIDDEN VALUE="<? echo ${"V_COLSEDIT$i"}; ?>">
							<INPUT ID="ROWEDIT<? echo $i; ?>" NAME="ROWEDIT<? echo $i; ?>"
							 TYPE=HIDDEN VALUE="<? echo ${"ROWEDIT$i"}; ?>">
							<INPUT ID="COLDETAIL<? echo $i; ?>" NAME="COLDETAIL<? echo $i; ?>"
							 TYPE=HIDDEN VALUE="<? echo ${"COLDETAIL$i"}; ?>">
							<INPUT ID="V_COLSDETAIL<? echo $i; ?>" NAME="V_COLSDETAIL<? echo $i; ?>"
							 TYPE=HIDDEN VALUE="<? echo ${"V_COLSDETAIL$i"}; ?>">
							<INPUT ID="ROWDETAIL<? echo $i; ?>" NAME="ROWDETAIL<? echo $i; ?>"
							 TYPE=HIDDEN VALUE="<? echo ${"ROWDETAIL$i"}; ?>">
						</TD>
					</TR>
<?
	}
?>
				</TABLE><hr noshade size=1>
				<TABLE cellspacing=0 cellpadding=0>
					<TR>
						<TH><?=_DEF_NLSOption?></TH>
						<TH><?=_DEF_NLSFile?></TH>
						<TH></TH>
						<TH><?=_DEF_NLSType?></TH>
					</TR>
<?		
	while ($TMP_file = each($files)) {
?>
					<TR>
						<TD><? echo $comments[$TMP_file["key"]]; ?></TD>
						<TD>
							<INPUT TYPE="TEXT" NAME="<? echo $TMP_file["key"]."_S"; ?>"
								VALUE="<? 
                                    if ($_GET['genmodmobile']=="X") {
                                        if (${$TMP_file["key"]."_S"} != "") {
                                            $TMP_mFile=str_replace(".php",".m.php",${$TMP_file["key"]."_S"});
                                        }elseif(${"D_".$TMP_file["key"]."_S"} != ""){
                                            $TMP_mFile=str_replace(".php",".m.php",${"D_".$TMP_file["key"]."_S"});
                                        }else{
                                            $TMP_mFile=str_replace(".php",".m.php",$TMP_file["value"]);
                                        }
                                        if (file_exists("modules/".$TMP_mFile)) echo $TMP_mFile;
                                        else echo (${$TMP_file["key"]."_S"} != "" ? ${$TMP_file["key"]."_S"} : (${"D_".$TMP_file["key"]."_S"} != "" ? ${"D_".$TMP_file["key"]."_S"} : $TMP_file["value"]));
                                    } else {
                                        echo (${$TMP_file["key"]."_S"} != "" ? ${$TMP_file["key"]."_S"} : (${"D_".$TMP_file["key"]."_S"} != "" ? ${"D_".$TMP_file["key"]."_S"} : $TMP_file["value"]));
                                    }
?>">
						</TD>
						<TD>
							<INPUT TYPE=HIDDEN NAME="<? echo $TMP_file["key"]."_D"; ?>"
								VALUE="<? 
echo (${$TMP_file["key"]."_D"} != "" ? ${$TMP_file["key"]."_D"} : (${"D_".$TMP_file["key"]."_D"} != "" ? ${"D_".$TMP_file["key"]."_D"} : basename($TMP_file["value"])));
?>">
						</TD>
						<TD>
							<SELECT NAME="<? echo $TMP_file["key"]."_OT"; ?>">
<?
    array2select($otype, (${$TMP_file["key"]."_OT"} != "" ? ${$TMP_file["key"]."_OT"} : ${"D_".$TMP_file["key"]."_OT"}), 8); 
?>
							</SELECT>
						</TD>
					</TR>
<?
	}
?>
					<TR>
						<TD COLSPAN="4" ALIGN="CENTER">
							<INPUT TYPE="SUBMIT" ACCESSKEY='S' TITLE='ALT+S' VALUE="<?=_DEF_NLSCreate." "._DEF_NLSModule?>">
							&nbsp;&nbsp;<INPUT TYPE="RESET" VALUE="<?=_DEF_NLSReset?>">
						</TD>
					</TR>
				</TABLE>
			</CENTER>
<?
	if ($V_submit!="") echo "\n<script>\ndocument.GEN.submit();\n</script>\n";
?>
		<!!!/FORM>


<script>
var numvar;
var nameFields=new Array ("FIELDORDER", "TITLE", "NAME", "LENGTH", "ILENGTH", "TYPE", "DTYPE", "ORDERBY", "CANBENULL", "EXTRA", "OVERLAP", "SEARCHABLE", "VDEFAULT", "LISTA", "BROWSABLE", "VONFOCUS", "VONCHANGE", "VONBLUR", "BROWSEDIT", "HELP", "DESCRIPTION", "FIELDEDIT", "FUNCNEW", "FUNCLINK", "READONLY", "NONEW", "NODETAIL", "NOEDIT", "COLEDIT", "NOINSERT", "ROWEDIT", "NOUPDATE", "COLDETAIL", "NOPRINT", "ROWDETAIL", "BROWSESEARCH", "V_COLSEDIT", "V_COLSDETAIL");

function cpFieldsForm(number){
    numvar=number;
    if (document.getElementById) {
	for (i=0; i<nameFields.length; i++) {
		if (document.getElementById(nameFields[i]+numvar).type=='checkbox') 
		    document.getElementById(nameFields[i]).checked=document.getElementById(nameFields[i]+number).checked;
		else document.getElementById(nameFields[i]).value=document.getElementById(nameFields[i]+number).value;
	}
    }
}
function cpFieldsLayer(){
    if (document.getElementById) {
	for (i=0; i<nameFields.length; i++) {
		if (document.getElementById(nameFields[i]).type=='checkbox') 
		    document.getElementById(nameFields[i]+numvar).checked=document.getElementById(nameFields[i]).checked;
		else document.getElementById(nameFields[i]+numvar).value=document.getElementById(nameFields[i]).value;
	}
    }
}
function getScrollY() {
    var sy = 0;
    if (document.documentElement && document.documentElement.scrollTop)
	sy = document.documentElement.scrollTop;
    else if (document.body && document.body.scrollTop) 
	sy = document.body.scrollTop; 
    else if (window.pageYOffset)
	sy = window.pageYOffset;
    else if (window.scrollY)
	sy = window.scrollY;
    return sy;
}
function showLayer(layerName){
    hideLayer(layerName);
    var y=getScrollY();
    if (document.getElementById) {
	document.getElementById(layerName).style.top = y+10;
	document.getElementById(layerName).style.visibility = "visible";
    } else {
	alert("Esta facilidad solo funciona en navegadores que soportan DOOM.");
    }
}
function hideLayer(layerName){
    if (document.getElementById) {
	document.getElementById(layerName).style.visibility = "hidden";
    }else{
	alert("Esta facilidad solo funciona en navegadores que soportan DOOM.");
    }
}
</script>							
<div id="field" style="background-color:white; border:solid 2px; border-color:navy; padding:20; position:absolute; left:5%; top:5; width:90%; height:95%; z-index:7; visibility: hidden;">
<table>

<TR><TD></TD><TD colspan=2><b>Descripcion detallada de Campo</b><br></TD>
    <TD align=right><a accesskey='G' title='ALT+G' href='javascript:cpFieldsLayer();hideLayer("field");'><img src='images/save_as.gif' border=0> Guardar</A> | 
    <a accesskey='X' title='ALT+X' href='javascript:hideLayer("field");'><img src='images/logout.gif' border=0> Cerrar</A> </TD></TR>

<TR><TD align=right>Orden:</TD><TD><INPUT TYPE="TEXT" ID="FIELDORDER" NAME="FIELDORDER" SIZE="3"></TD>
    <TD colspan=2><input type=button onclick='document.GEN.SEARCHABLE.checked=false;document.GEN.BROWSABLE.checked=false;document.GEN.NONEW.checked=true;;document.GEN.NOEDIT.checked=true;;document.GEN.NODETAIL.checked=true;;document.GEN.NOINSERT.checked=true;;document.GEN.NOUPDATE.checked=true;;document.GEN.NOPRINT.checked=true;' value=' Ocultar Campo '></TD></TR>

<TR><TD align=right>Titulo Campo:</TD><TD><INPUT TYPE="TEXT" ID="TITLE" NAME="TITLE" SIZE="35"></TD>
    <TD align=right>Nombre Campo BD:</TD><TD><INPUT TYPE="TEXT" ID="NAME" NAME="NAME" SIZE="25"></TD></TR>

<TR><TD align=right>Long. Campo:</TD><TD><INPUT TYPE="TEXT" ID="LENGTH" NAME="LENGTH" SIZE="5"></TD>
    <TD align=right>Long. Entrada:</TD><TD><INPUT TYPE="TEXT" ID="ILENGTH" NAME="ILENGTH" SIZE="5"></TD></TR>

<TR><TD align=right>Tipo Campo BD:</TD><TD><SELECT ID="TYPE" NAME="TYPE">
<? $type = ${"TYPE$i"}; $dtype = $type2dtype_arr[$type]; array2select($types_arr,$type,8); ?>
    </SELECT></TD>
    <TD align=right>Permitir Ordenar:</TD><TD><INPUT TYPE="CHECKBOX" ID="ORDERBY" NAME="ORDERBY"></TD></TR>

<TR><TD align=right>Campo Formulario:</TD><TD><SELECT ID="DTYPE" NAME="DTYPE">
<? array2select($dtypes_arr,${"DTYPE$i"},8); ?>
    </SELECT></TD>
    <TD align=right>Permitir Nulo:</TD><TD><INPUT TYPE="CHECKBOX" ID="CANBENULL" NAME="CANBENULL"></TD></TR>

<TR><TD align=right>Datos Extra:</TD><TD COLSPAN=3><INPUT TYPE="TEXT" SIZE="100" ID="EXTRA" NAME="EXTRA"></TD></TR>

<TR><TD align=right>Pesta&ntilde;a:</TD><TD><INPUT TYPE="TEXT" SIZE="40" ID="OVERLAP" NAME="OVERLAP"></TD>
    <TD align=right>Permite Busquedas:</TD><TD><INPUT TYPE="CHECKBOX" ID="SEARCHABLE" NAME="SEARCHABLE"></TD></TR>
    
<TR><TD align=right>Valor por Defecto:</TD><TD><INPUT TYPE="TEXT" SIZE="40" ID="VDEFAULT" NAME="VDEFAULT"></TD>
    <TD align=right>Visible en Listado:</TD><TD><INPUT TYPE="CHECKBOX" ID="BROWSABLE" NAME="BROWSABLE"></TD></TR>
    
<TR><TD align=right>Lista de Valores (LIBRA):</TD><TD><INPUT TYPE="TEXT" SIZE="40" ID="LISTA" NAME="LISTA"></TD>
<TD align=right>Editable en Listado:</TD><TD><INPUT TYPE="CHECKBOX" ID="BROWSEDIT" NAME="BROWSEDIT"></TD>
</TR>

<TR><TD align=right>Javascript OnFocus:</TD><TD><INPUT TYPE="TEXT" SIZE="40" ID="VONFOCUS" NAME="VONFOCUS"></TD>
    <TD align=right>Editable en Detalle:</TD><TD><INPUT TYPE="CHECKBOX" ID="FIELDEDIT" NAME="FIELDEDIT"></TD></TR>

<TR><TD align=right>Javascript OnChange:</TD><TD><INPUT TYPE="TEXT" SIZE="40" ID="VONCHANGE" NAME="VONCHANGE"></TD>
    <TD align=right>Solo Lectura:</TD><TD><INPUT TYPE="CHECKBOX" ID="READONLY" NAME="READONLY"></TD></TR>

<TR><TD align=right>Javascript OnBlur:</TD><TD><INPUT TYPE="TEXT" SIZE="40" ID="VONBLUR" NAME="VONBLUR"></TD>
    <TD align=right>No permite Nuevo:</TD><TD><INPUT TYPE="CHECKBOX" ID="NONEW" NAME="NONEW"></TD></TR>

<TR><TD align=right>Modulo para Nuevo:</TD><TD><INPUT TYPE="TEXT" SIZE="30" ID="FUNCNEW" NAME="FUNCNEW"></TD>
    <TD align=right>No muestra Detalle:</TD><TD><INPUT TYPE="CHECKBOX" ID="NODETAIL" NAME="NODETAIL"></TD></TR>

<TR><TD align=right>Modulo para Enlace:</TD><TD><INPUT TYPE="TEXT" SIZE="30" ID="FUNCLINK" NAME="FUNCLINK"></TD>
    <TD align=right>No permite Editar:</TD><TD><INPUT TYPE="CHECKBOX" ID="NOEDIT" NAME="NOEDIT"></TD></TR>

<TR><TD align=right>Posicion en Editar:</TD><TD>[FILA]<INPUT TYPE="TEXT" SIZE="3" ID="ROWEDIT" NAME="ROWEDIT">/[COLUMNA]<INPUT TYPE="TEXT" SIZE="3" ID="COLEDIT" NAME="COLEDIT"></TD>
    <TD align=right>No se Inserta:</TD><TD><INPUT TYPE="CHECKBOX" ID="NOINSERT" NAME="NOINSERT"></TD></TR>

<TR><TD align=right>Posicion en Detalle:</TD><TD>[FILA]<INPUT TYPE="TEXT" SIZE="3" ID="ROWDETAIL" NAME="ROWDETAIL">/[COLUMNA]<INPUT TYPE="TEXT" SIZE="3" ID="COLDETAIL" NAME="COLDETAIL"></TD>
    <TD align=right>No se Modifica:</TD><TD><INPUT TYPE="CHECKBOX" ID="NOUPDATE" NAME="NOUPDATE"></TD></TR>

<TR><TD align=right>Columnas en Detalle:</TD><TD><INPUT TYPE="TEXT" SIZE="3" ID="V_COLSDETAIL" NAME="V_COLSDETAIL"></TD>
    <TD align=right>No se Imprime:</TD><TD><INPUT TYPE="CHECKBOX" ID="NOPRINT" NAME="NOPRINT"></TD></TR>

<TR><TD align=right>Columnas en Editar:</TD><TD><INPUT TYPE="TEXT" SIZE="3" ID="V_COLSEDIT" NAME="V_COLSEDIT"></TD>
    <TD align=right>Busqueda en Listado:</TD><TD><INPUT TYPE="CHECKBOX" ID="BROWSESEARCH" NAME="BROWSESEARCH"></TD></TR>

<TR><TD align=right>Texto de Ayuda:</TD><TD colspan=3><INPUT TYPE="TEXT" SIZE="80" ID="HELP" NAME="HELP"></TD>

<TR>
    </TR>


<TR><TD align=right valign=top>Descripcion:</TD><TD COLSPAN=3><TEXTAREA COLS=120 ROWS=6 ID="DESCRIPTION" NAME="DESCRIPTION"></TEXTAREA></TD></TR>

</table>
</div>
</FORM>

<?
CloseTable();
include_once ("footer.php");
/////////////////////////////////////////////////////////////////////////////////////////////////
function array2select($arr, $sel = "", $indent=3) {
	for ($i=0; $i < $indent; $i++) { $indenttab .= "\t"; }
	reset($arr);
	while ( list( $key, $val ) = each($arr)) {
		echo "$indenttab<OPTION VALUE=\"$key\"";
		if($sel && $key == $sel) echo " SELECTED";
		echo ">$val</OPTION>\n";
	}
}
/////////////////////////////////////////////////////////////////////////////////////////////////
function selectmodfields(){
global $PHP_SELF, $SID, $V_dir, $V_mod, $V_submod, $modulesdir, $project_file, $DIRBASE, $NAME, $idx, $tablename;

	include($DIRBASE.$modulesdir."/$project_file");
	$tablaprj=$tablename;
	for($ki=0; $ki<$idx; $ki++) $findex["".$NAME[$ki]]=$ki;

	$cmd="cd modules/".$modulesdir."; grep tablename *prj.php | grep ".$tablaprj;
	if (!($p=popen("$cmd 2>&1","r"))) return false;
	$result="";
	while (!feof($p)) { $result.=fgets($p,1000); }

	echo '<script>
var estado=new Array();
var altura=new Array();
function chgC(num) {
	if (estado[num]=="h") {
		showLayer(num);
		estado[num]="s";
	} else {
		hideLayer(num);
		estado[num]="h";
	}
}
function showLayer(num){
    layerName="C"+num;
    //var y=getScrollY();
    document.getElementById("img_"+num).src="images/nolines_minus.gif";
    if (document.getElementById) {
	//document.getElementById(layerName).style.top = y+10;
	document.getElementById(layerName).style.height=altura[num];
	document.getElementById(layerName).style.visibility = "visible";
    } else {
	alert("Esta facilidad solo funciona en navegadores que soportan DOOM.");
    }
}
function hideLayer(num){
    layerName="C"+num;
    document.getElementById("img_"+num).src="images/nolines_plus.gif";
    if (document.getElementById) {
	//altura[num]=document.getElementById(layerName).style.height;
	altura[num]=document.getElementById(layerName).offsetHeight;
	document.getElementById(layerName).style.visibility = "hidden";
	document.getElementById(layerName).style.height=0;
    }else{
	alert("Esta facilidad solo funciona en navegadores que soportan DOOM.");
    }
}
</script>
';

	echo "<form action='$PHP_SELF' method=GET>\n";
	foreach($_REQUEST as $TMP_k=>$TMP_v) echo "<input type=hidden name=$TMP_k value='$TMP_v'>\n";
	echo "<center><b><u>Agrega Campos a $project_file</u></b></center>Selecciona los campos a agregar:<ul>";
	$A_lines=explode("\n",$result."\n");
	$TMP_cont=0;
	$TMP_numfile=1;
	foreach($A_lines as $TMP_idx=>$TMP_line) {
		if (trim($TMP_line)=="") continue;
		$A_prj=explode(":",trim($TMP_line).":");
		$TMP_prj=$A_prj[0];
		$TMP_lineaphp=$A_prj[1];
		if (trim($TMP_lineaphp)=="") continue;
		eval($TMP_lineaphp);
		if ($TMP_prj==$project_file) continue;
		if ($tablaprj==$tablename) {
			$cmd="cd modules/".$modulesdir."; grep title ".$TMP_prj."";
			if (!($p=popen("$cmd 2>&1","r"))) return false;
			$result="";
			while (!feof($p)) { $result.=fgets($p,1000); }
			eval($result);
			$TMP_url=$PHP_SELF."?V_dir=".$V_dir."&V_mod=".$V_mod."&V_submod=genform&modulesdir=".$modulesdir."&project_file=".$TMP_prj.$SID;
			$TMP_icon="<img id='img_$TMP_numfile' src='images/nolines_minus.gif' style='cursor:pointer' onclick=\"javascript:chgC('$TMP_numfile');\">";
			echo "<li> ".$TMP_icon."".$title." (<a target=_blank href='$TMP_url'>$TMP_prj</a>) <div id='C".$TMP_numfile."'><ul>";
			$cmd="cd modules/".$modulesdir."; grep TITLE ".$TMP_prj."";
			if (!($p=popen("$cmd 2>&1","r"))) return false;
			$result="";
			while (!feof($p)) { $result.=fgets($p,1000); }
			$A_linesTitle=explode("\n",$result."\n");
			$cmd="cd modules/".$modulesdir."; grep NAME ".$TMP_prj."";
			if (!($p=popen("$cmd 2>&1","r"))) return false;
			$result="";
			while (!feof($p)) { $result.=fgets($p,1000); }
			$A_lines2=explode("\n",$result."\n");
			$TMP_num=-1;
			foreach($A_lines2 as $TMP_idx2=>$TMP_line2) {
				if (trim($TMP_line2)=="") continue;
				$TMP_line2=str_replace("\$NAME[\$idx]", "\$FNAME", $TMP_line2);
				eval($TMP_line2);
				$TMP_lineTitle=str_replace("\$TITLE[\$idx]", "\$FTITLE", $A_linesTitle[$TMP_idx2]);
				eval($TMP_lineTitle);
				$TMP_num++;
				if (isset($findex["".$FNAME])) continue;
				echo "<input type=checkbox name='ADD".$TMP_cont."' value='".$TMP_num." ".$TMP_prj."'> ".$FTITLE." (".$FNAME.")<br>";
				$TMP_cont++;
			}
			echo "</ul></div>";
			$TMP_numfile++;
		}
	}
	echo "<br><input type=hidden name=NUMADD value='$TMP_cont'><input type=submit name='conf' value=' AGREGAR CAMPOS '> &nbsp; &nbsp; <input type=button onclick='javascript:history.back();' value=' CANCELAR '></form>";
	echo "</ul>";
}
/////////////////////////////////////////////////////////////////////////////////////////////////
function addmodfields(){
global $DIRBASE, $PHP_SELF, $SID, $V_dir, $V_mod, $V_submod, $modulesdir, $project_file, $DIRBASE, $NAME, $idx, $tablename, $NUMADD;
	for($ki=0; $ki<$NUMADD; $ki++) {
		global ${"ADD".$ki};
		if (${"ADD".$ki}!="") {
			$A_tmp=explode(" ",${"ADD".$ki});
			$TMP_prj=$A_tmp[1]; $TMP_numfield=$A_tmp[0];
			$LISTA[$TMP_prj].=$TMP_numfield.","; 	// recoge la lista de Campos por prj
		} 
	}
	if (count($LISTA)==0) {
		alert("No se agrega ningun campo de otro modulo");
		return;
	}
	$TMP_fileprj=$DIRBASE.$modulesdir."/".$project_file;
	global $cabeza,$cola;
	$F=readprj($TMP_fileprj);
	$cabezaPRJ=$cabeza;
	$colaPRJ=$cola;
	foreach($LISTA as $TMP_prj=>$TMP_fields) {
		$TMP_file=$DIRBASE.$modulesdir."/".$TMP_prj;
		$A_tmp=explode(",",$TMP_fields.",");
		$F=readprj($TMP_file);
		foreach($A_tmp as $TMP_idx=>$TMP_numf) {
			if (!$TMP_numf>0) continue;
			$cabezaPRJ.=$F[$TMP_numf]."\n\t\$idx++;\n";
		}
	}
	$fp = fopen($TMP_fileprj, "w");
	fwrite($fp,$cabezaPRJ.$colaPRJ);
	fclose($fp);
}
/////////////////////////////////////////////////////////////////////////////////////////////////
function readprj($TMP_file) {
	global $cabeza,$cola;
	$fp = fopen($TMP_file, "r");
	$TMP_data = fread($fp, filesize($TMP_file));
	fclose($fp);
	$A_lines=explode("\n",$TMP_data."\n");
	$TMP_numf=0;
	$F=array();
	$campos=false;
	$escola=false; $cola=""; $cabeza="";
	foreach($A_lines as $TMP_idx=>$TMP_line) {
		if (ereg("orderby",$TMP_line)) {
			$campos=false; // Fin de campos
			$escola=true;
		}
		if ($escola==false) $cabeza.=$TMP_line."\n";
		else $cola.=$TMP_line."\n";
		if (substr(trim($TMP_line),1,3)=="idx") {
			if ($campos==false) {
				$campos=true; // A partir de aqui comienzan los campos
			} else {
				$TMP_numf++;
			}
			continue; // Esta linea se ignora
		}
		if ($campos==true) $F[$TMP_numf].=$TMP_line."\n";
	}
	return $F;
}
?>
