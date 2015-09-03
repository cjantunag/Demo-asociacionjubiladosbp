<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

ini_set("safe_mode", 0);

include_once ("modules/".$V_dir."/dbsql.php");
include_once ("modules/".$V_dir."/defaults.php");
include_once ("modules/".$V_dir."/phplibsup.php");

if (!isset($browsetype)) $browsetype="";
if (!isset($V_rowsinsert)) $V_rowsinsert="";
if (!isset($V_rowsinsertend)) $V_rowsinsertend="";
if (!isset($RAD)) $RAD="";
if (!isset($defaultfilter)) $defaultfilter="";
if (!isset($defaultfunc)) $defaultfunc="";
if (!isset($numf)) $numf="";
if (!isset($rows_limit)) $rows_limit="";
if (!isset($V_colsedit)) $V_colsedit=1;
if (!isset($V_colsdetail)) $V_colsdetail=1;
if (!isset($tableConfig)) $tableConfig="";
if (!isset($fieldConfig)) $fieldConfig="";
if (!isset($tableUser)) $tableUser="";
if (!isset($fieldUser)) $fieldUser="";
if (!isset($fieldPass)) $fieldPass="";
if (!isset($fieldPass)) $fieldPass="";

//$modulesdir="modules/".$modulesdirform;
if ($modulesdirform != "") $modulesdir = $modulesdirform;
$dbtype=strtolower($dbtype);
$themefile=$GEN_themefile;

$RAD_dbinfo = $$dbtype;
		$db = new DBSql;
		$db -> Host = $hostname;
		$db -> User = $dbusername;
		$db -> Password = $dbpassword;
		$db -> Database = $dbname;
		$db -> Type = $dbtype;
		$db -> Debug = false;

include_once ("header.php");
OpenTable();

?>
<IMG BORDER=0 SRC="modules/<?=$V_dir?>/logo.gif" ALT="RAD">
<HR noshade size=1>
<? 

//---------------------------------------------------------------------------------------------------------------
function split2array($f, $what) {
	$s = explode(",", $what);
	for ($i = 0; $i < count($s); $i++) {
		$x = explode(":",$s[$i]);
		fputs($f,"    \"" . $x[0] . "\" => \"" . $x[1] ."\"");
		fputs($f, ($i == (count($s)-1))?"\n":",\n");
	}
}
//---------------------------------------------------------------------------------------------------------------
function iorr($f, $source, $dest = "", $otype = "include") {
	global $modulesdir, $modulename, $cmask, $db, $RAD_dbinfo, $tableinfo;
	$dest3 = $source;
	if ($dest == "") {
		$dest = basename($source);
	}
	$source="modules/".$source;
	if ($otype == "include") {
	 	fputs($f,"<? ### BEGIN $source ### ?>\n");
		$sf = fopen($source, "r");
		while( $line = fgets($sf, 1000)) {
			fputs($f,$line);
		}
		fclose($sf);
		fputs($f,"<? ### END $source ### ?>\n");
	} else if ($otype == "copy" || $otype == "fcopy") {
		if (!ereg("^/", $dest)) {
			$tfname = "$modulesdir/$dest";
		} else {
    			$dest2 = basename($source);
			$tfname = $modulesdir."/".$dest2;
//			$tfname = $dest;
		}
		if (file_exists($tfname)) {
			if (!is_file($tfname)) $otype = "fcopy";
		} else {
			$otype = "fcopy";
		}
		if ($otype == "copy") {
			if (!copy($source, $tfname)) {
				echo "No se pudo copiar \"$source\" como \"$tfname\" ! Proceso Cancelado.";
				exit;
			}
			chmod($tfname, $cmask);
		}
//		$dest2 = basename($source);
		$dest2 = basename($tfname);
		if ($otype == "copy") 
	    	    fputs($f, "<? include(\$RAD_DirBase.\"$dest2\"); ?>\n");
		else 
		    fputs($f, "<? include(\"modules/$dest3\"); ?>\n");
	} else if ($otype == "fcopyind") {
		if (!ereg("^/", $dest)) {
			$tfname = $modulesdir."/".$modulename."_".$dest;
		} else {
    			$dest2 = basename($source);
			$tfname = $modulesdir."/".$modulename."_".$dest2;
//			$tfname = $dest;
		}
		if (!copy($source, $tfname)) {
			echo "No se pudo copiar \"$source\" como \"$tfname\" !";
//			exit;
		}
		chmod($tfname, $cmask);
		$dest2 = basename($tfname);
		fputs($f, "<? include(\$RAD_DirBase.\"$dest2\"); ?>\n");
	}
}
//---------------------------------------------------------------------------------------------------------------
$menubrowse ="on";
$menuminisearch ="on";
$menudetail ="on";
$menuprint  ="on";
$menunew    ="on";
$menuedit   ="on";
$menudelete ="on";
$menureport ="on";
$menusearch ="on";

$menujump   ="";
$menubletter="";
$menubackup ="";
$menusend="";

$logsql ="on";
if ($security=="") $security=$defaultsecurity;

if ($opt != "all") $tables[0]=$tablename;
else $tables = explode(",", $tablenames);
for ($k = 0; $k < count($tables); $k++) {
	$idnames="";
	$orderby="";
	if ($numf>0) {
		for ($i = 0; $i < $numf+1; $i++) { 
			${"NAME".$i}="";
			${"TITLE".$i} = "";
			${"ORDERBY".$i}="";
			${"TYPE".$i}="";
			${"DTYPE".$i}="";
			${"LENGTH".$i}="";
			${"ILENGTH".$i}="";
			${"FLAGS".$i}="";
			${"CANBENULL".$i}="";
			${"SEARCHABLE".$i}="";
			${"BROWSABLE".$i}="";
			${"BROWSEDIT".$i}="";
			${"FIELDEDIT".$i}="";
			${"READONLY".$i}="";
			${"NONEW".$i}="";
			${"NOEDIT".$i}="";
			${"NODETAIL".$i}="";
			${"NOINSERT".$i}="";
			${"NOUPDATE".$i}="";
			${"NOPRINT".$i}="";
			${"BROWSESEARCH".$i}="";
		}
	}
	if ($tables[$k]!="") {
		$tablename=$tables[$k];
		$modulename=$tablename;
	} else {	
		$tablename="";
		$modulename="";
		continue;
	}

	$tableinfo = $db->metadata($tablename);
	for ($i = 0; $i < count($tableinfo); $i++) { 
		if ($RAD_dbinfo -> isautoincrement($tableinfo[$i]["flags"])) $tableinfo[$i]["type"]="auto_increment";
		${"NAME".$i}=$tableinfo[$i]["name"];
		${"TITLE".$i} = ucwords(${"NAME".$i});
		${"ORDERBY".$i}=$tableinfo[$i]["name"];
		${"TYPE".$i}=$tableinfo[$i]["type"];
		${"DTYPE".$i}=$tableinfo[$i]["type"];
		${"LENGTH".$i}=$tableinfo[$i]["len"];
		${"ILENGTH".$i}=${"LENGTH".$i};
		if (${"TYPE".$i} == "int") {
			${"TYPE".$i}="string";
		}
		if (${"TYPE".$i} == "string") {
			${"DTYPE".$i}="stand";
		}
		if (${"TYPE".$i} == "blob") {
			${"TYPE".$i}="string";
			${"DTYPE".$i}="text";
		}
		if (${"LENGTH".$i}>$max_inputlength) ${"ILENGTH".$i}="$max_inputlength";
		if (${"LENGTH".$i}>1024) {
			${"ILENGTH".$i}="60x5";
			${"DTYPE".$i}="text";	
		}
		${"FLAGS".$i}=$tableinfo[$i]["flags"];
		if ($RAD_dbinfo -> canbenull($tableinfo[$i]["flags"])) ${"CANBENULL".$i}="on";
		else ${"CANBENULL".$i}="";
		${"SEARCHABLE".$i}="on";
		${"BROWSABLE".$i}="on";
		${"BROWSEDIT".$i}="";
		${"FIELDEDIT".$i}="";
		${"READONLY".$i}="";
		${"NONEW".$i}="";
		${"NOEDIT".$i}="";
		${"NODETAIL".$i}="";
		${"NOINSERT".$i}="";
		${"NOUPDATE".$i}="";
		${"NOPRINT".$i}="";
		${"BROWSESEARCH".$i}="";
	} 
	for ($i = 0; $i < count($tableinfo); $i++) {
		if ($RAD_dbinfo -> isprimarykey($tableinfo[$i]["flags"])) {
			$idnames.=$tableinfo[$i]["name"].",";
		}
	}
	if ($idnames =="") {
		//--- a table with no primary key, all fields be treated as primary
		for ($i = 0; $i < count($tableinfo); $i++) {
			$idnames.=$tableinfo[$i]["name"].",";
		}
	  }
	if (strlen($idnames)>0) $idnames=substr($idnames,0,strlen($idnames)-1);
	$numf=count($tableinfo)+1;

//*****************************************************************************
// PHP Generator
//*****************************************************************************
$title=ucwords($modulename);

if (empty($THEME_S)) $THEME_S=$D_THEME_S;
if (empty($THEME_D)) $THEME_D=$D_THEME_D;
if (empty($THEME_OT)) $THEME_OT=$D_THEME_OT;

if (empty($JS_S)) $JS_S=$D_JS_S;
if (empty($JS_D)) $JS_D=$D_JS_D;
if (empty($JS_OT)) $JS_OT=$D_JS_OT;

if (empty($GENUTIL_S)) $GENUTIL_S=$D_GENUTIL_S;
if (empty($GENUTIL_D)) $GENUTIL_D=$D_GENUTIL_D;
if (empty($GENUTIL_OT)) $GENUTIL_OT=$D_GENUTIL_OT;

if (empty($SQL_S)) $SQL_S=$D_SQL_S;
if (empty($SQL_D)) $SQL_D=$D_SQL_D;
if (empty($SQL_OT)) $SQL_OT=$D_SQL_OT;

if (empty($DELETE_S)) $DELETE_S=$D_DELETE_S;
if (empty($DELETE_D)) $DELETE_D=$D_DELETE_D;
if (empty($DELETE_OT)) $DELETE_OT=$D_DELETE_OT;

if (empty($UPDATE_S)) $UPDATE_S=$D_UPDATE_S;
if (empty($UPDATE_D)) $UPDATE_D=$D_UPDATE_D;
if (empty($UPDATE_OT)) $UPDATE_OT=$D_UPDATE_OT;

if (empty($INSERT_S)) $INSERT_S=$D_INSERT_S;
if (empty($INSERT_D)) $INSERT_D=$D_INSERT_D;
if (empty($INSERT_OT)) $INSERT_OT=$D_INSERT_OT;

if (empty($ERROR_S)) $ERROR_S=$D_ERROR_S;
if (empty($ERROR_D)) $ERROR_D=$D_ERROR_D;
if (empty($ERROR_OT)) $ERROR_OT=$D_ERROR_OT;

if (empty($MENU_S)) $MENU_S=$D_MENU_S;
if (empty($MENU_D)) $MENU_D=$D_MENU_D;
if (empty($MENU_OT)) $MENU_OT=$D_MENU_OT;

if (empty($BROWSE_S)) $BROWSE_S=$D_BROWSE_S;
if (empty($BROWSE_D)) $BROWSE_D=$D_BROWSE_D;
if (empty($BROWSE_OT)) $BROWSE_OT=$D_BROWSE_OT;

if (empty($BACKUP_S)) $BACKUP_S=$D_BACKUP_S;
if (empty($BACKUP_D)) $BACKUP_D=$D_BACKUP_D;
if (empty($BACKUP_OT)) $BACKUP_OT=$D_BACKUP_OT;

if (empty($DRECORD_S)) $DRECORD_S=$D_DRECORD_S;
if (empty($DRECORD_D)) $DRECORD_D=$D_DRECORD_D;
if (empty($DRECORD_OT)) $DRECORD_OT=$D_DRECORD_OT;

if (empty($EFORM_S)) $EFORM_S=$D_EFORM_S;
if (empty($EFORM_D)) $EFORM_D=$D_EFORM_D;
if (empty($EFORM_OT)) $EFORM_OT=$D_EFORM_OT;

if (empty($SFORM_S)) $SFORM_S=$D_SFORM_S;
if (empty($SFORM_D)) $SFORM_D=$D_SFORM_D;
if (empty($SFORM_OT)) $SFORM_OT=$D_SFORM_OT;

if ($rows_limit=="") $rows_limit=$limit_default;
if ($V_colsedit=="") $V_colsedit=1;
if ($V_colsdetail=="") $V_colsdetail=1;
if ($themefile =="") $themefile=$defaultthemefile;

if (empty($modulename)) { ?>
		<BR><B>Se debe especificar el Prefijo !<BR>
		<A HREF="javascript:window.history.back()">Volver</A>
<? exit;
}


$files = array("JS", "GENUTIL", "SQL", "DELETE", "UPDATE",
	"INSERT", "HEADER", "MENU", "ERROR", "BACKUP", "BROWSE", "DRECORD",
	"EFORM", "SFORM", "FOOTER");

$idname=explode(",",$idnames);

//***************************** Save project file (prj) *****************************
if(!file_exists("modules/".$modulesdir))
	mkdir ("modules/".$modulesdir, 0777); 

$f=0;

$f = fopen("modules/".$modulesdir."/".$modulename.".prj.php","w");
fputs($f,"<?\n");
fputs($f,"\t\$title = \"$title\";\n");
fputs($f,"\t\$themefile = \"$themefile\";\n");
fputs($f,"\t\$tableConfig = \"$tableConfig\";\n");
fputs($f,"\t\$fieldConfig = \"$fieldConfig\";\n");
fputs($f,"\t\$tableUser = \"$tableUser\";\n");
fputs($f,"\t\$fieldUser = \"$fieldUser\";\n");
fputs($f,"\t\$fieldPass = \"$fieldPass\";\n");
fputs($f,"\t\$modulesdir = \"$modulesdir\";\n");
fputs($f,"\t\$browsetype = \"$browsetype\";\n");
if ($V_rowsinsert!="") fputs($f,"\t\$V_rowsinsert = \"$V_rowsinsert\";\n");
if ($V_rowsinsertend!="") fputs($f,"\t\$V_rowsinsertend = \"$V_rowsinsertend\";\n");
if ($RAD !="") {
    fputs($f,"\t\$RAD_DirBase = \"modules/\".\$modulesdir.\"/\";\n");
//    fputs($f,"\t\$RAD_DirBase = \"modules/phpRAD/\";\n");
} else {
    fputs($f,"\t\$RAD_DirBase = \"\";\n");
}

if ($RAD !="") fputs($f,"\t\$RAD = true;\n");
else fputs($f,"\t\$RAD = false;\n");

if ($menuoff !="") fputs($f,"\t\$menuoff = \"x\";\n");
if ($headeroff !="") fputs($f,"\t\$headeroff = \"x\";\n");
if ($footeroff !="") fputs($f,"\t\$footeroff = \"x\";\n");

if ($menubrowse !="") fputs($f,"\t\$menubrowse = true;\n");
else fputs($f,"\t\$menubrowse = false;\n");
if ($menuminisearch !="") fputs($f,"\t\$menuminisearch = true;\n");
else fputs($f,"\t\$menuminisearch = false;\n");
if ($menubackup !="") fputs($f,"\t\$menubackup = true;\n");
else fputs($f,"\t\$menubackup = false;\n");
if ($menudetail !="") fputs($f,"\t\$menudetail = true;\n");
else fputs($f,"\t\$menudetail = false;\n");
if ($menusearch !="") fputs($f,"\t\$menusearch = true;\n");
else fputs($f,"\t\$menusearch = false;\n");
if ($menusend !="") fputs($f,"\t\$menusend = true;\n");
else fputs($f,"\t\$menusend = false;\n");
if ($menuprint !="") fputs($f,"\t\$menuprint = true;\n");
else fputs($f,"\t\$menuprint = false;\n");
if ($menunew !="") fputs($f,"\t\$menunew = true;\n");
else fputs($f,"\t\$menunew = false;\n");
if ($menuedit !="") fputs($f,"\t\$menuedit = true;\n");
else fputs($f,"\t\$menuedit = false;\n");
if ($menudelete !="") fputs($f,"\t\$menudelete = true;\n");
else fputs($f,"\t\$menudelete = false;\n");
if ($menureport !="") fputs($f,"\t\$menureport = true;\n");
else fputs($f,"\t\$menureport = false;\n");

if ($menujump !="") fputs($f,"\t\$menujump = true;\n");
else fputs($f,"\t\$menujump = false;\n");

if ($menubletter !="") fputs($f,"\t\$menubletter = true;\n");
else fputs($f,"\t\$menubletter = false;\n");

if ($logsql !="") fputs($f,"\t\$logsql = true;\n");
else fputs($f,"\t\$logsql = false;\n");

// fputs($f,"\t\$hostname = \"$hostname\";\n");
// fputs($f,"\t\$dbusername = \"$dbusername\";\n");
// fputs($f,"\t\$dbpassword = \"$dbpassword\";\n");
// fputs($f,"\t\$dbtype = \"$dbtype\";\n");

if ($dbname!="") fputs($f,"\t\$dbname = \"$dbname\";\n");
fputs($f,"\t\$tablename = \"$tablename\";\n");
fputs($f,"\t\$filename = \"$modulename\";\n");
fputs($f,"\t\$security = \"$security\";\n");
fputs($f,"\t\$tableConfig = \"$tableConfig\";\n");
fputs($f,"\t\$fieldConfig = \"$fieldConfig\";\n");
fputs($f,"\t\$tableUser = \"$tableUser\";\n");
fputs($f,"\t\$fieldUser = \"$fieldUser\";\n");
fputs($f,"\t\$fieldPass = \"$fieldPass\";\n");
fputs($f,"\t\$defaultfunc = \"$defaultfunc\";\n");
fputs($f,"\t\$rows_limit = $rows_limit;\n");
fputs($f,"\t\$V_colsedit = $V_colsedit;\n");
fputs($f,"\t\$V_colsdetail = $V_colsdetail;\n");
fputs($f,"\t\$idnames = \"$idnames\";\n");
for ($i = 0; $i < count($idname); $i++) { 
	if ($idname[$i] != "") {
		fputs($f,"\t\$idname$i = \"" . $idname[$i] . "\";\n");
	}
}

$j = 0;

fputs($f,"\t\$idx=0;\n");

$puntero=-1;
for ($ki = 0; $ki < $numf; $ki++) { 
  for ($i = 0; $i < $numf; $i++) { 
	if (!isset(${"FIELDORDER$i"})) ${"FIELDORDER$i"}="";
	if (!isset(${"EXTRA$i"})) ${"EXTRA$i"}="";
	if (!isset(${"VDEFAULT$i"})) ${"VDEFAULT$i"}="";
	if (!isset(${"LISTA$i"})) ${"LISTA$i"}="";
	if (!isset(${"VONCHANGE$i"})) ${"VONCHANGE$i"}="";
	if (!isset(${"COLUMN$i"})) ${"COLUMN$i"}="";
	if (!isset(${"HELP$i"})) ${"HELP$i"}="";
	if (!isset(${"FUNCNEW$i"})) ${"FUNCNEW$i"}="";
	if (!isset(${"OVERLAP$i"})) ${"OVERLAP$i"}="";
	if (!isset(${"V_COLSEDIT$i"})) ${"V_COLSEDIT$i"}="";
	if (!isset(${"V_COLSDETAIL$i"})) ${"V_COLSDETAIL$i"}="";
	if (!isset(${"COLEDIT$i"})) ${"COLEDIT$i"}="";
	if (!isset(${"ROWEDIT$i"})) ${"ROWEDIT$i"}="";
	if (!isset(${"COLDETAIL$i"})) ${"COLDETAIL$i"}="";
	if (!isset(${"ROWDETAIL$i"})) ${"ROWDETAIL$i"}="";
	if (!isset(${"NAME$i"})) ${"NAME$i"}="";
	if (${"FIELDORDER$i"} == $ki+1) {
	  if (${"NAME$i"} != "") {
		fputs($f,"\t\$TITLE[\$idx] = \"" . ${"TITLE$i"} . "\";\n");
		fputs($f,"\t\$NAME[\$idx] = \"" . ${"NAME$i"} . "\";\n");
		fputs($f,"\t\$LENGTH[\$idx] = \"" . ${"LENGTH$i"} . "\";\n");
		fputs($f,"\t\$ILENGTH[\$idx] = \"" . ${"ILENGTH$i"} . "\";\n");
		fputs($f,"\t\$ORDERBY[\$idx] = \"" . ${"ORDERBY$i"} . "\";\n");
		fputs($f,"\t\$CANBENULL[\$idx] = \"" . ${"CANBENULL$i"} . "\";\n");
		fputs($f,"\t\$TYPE[\$idx] = \"" . ${"TYPE$i"} . "\";\n");
		fputs($f,"\t\$DTYPE[\$idx] = \"" . ${"DTYPE$i"} . "\";\n");
		fputs($f,"\t\$EXTRA[\$idx] = \"" . ${"EXTRA$i"} . "\";\n");
		fputs($f,"\t\$VDEFAULT[\$idx] = \"" . ${"VDEFAULT$i"} . "\";\n");
		fputs($f,"\t\$LISTA[\$idx] = \"" . ${"LISTA$i"} . "\";\n");
		fputs($f,"\t\$VONCHANGE[\$idx] = \"" . ${"VONCHANGE$i"} . "\";\n");
		fputs($f,"\t\$COLUMN[\$idx] = \"" . ${"COLUMN$i"} . "\";\n");
		fputs($f,"\t\$HELP[\$idx] = \"" . ${"HELP$i"} . "\";\n");
		fputs($f,"\t\$FUNCNEW[\$idx] = \"" . ${"FUNCNEW$i"} . "\";\n");
		fputs($f,"\t\$OVERLAP[\$idx] = \"" . ${"OVERLAP$i"} . "\";\n");
		fputs($f,"\t\$SEARCHABLE[\$idx] = \"" . ${"SEARCHABLE$i"} . "\";\n");
		fputs($f,"\t\$BROWSABLE[\$idx] = \"" . ${"BROWSABLE$i"} . "\";\n");
		fputs($f,"\t\$BROWSEDIT[\$idx] = \"" . ${"BROWSEDIT$i"} . "\";\n");
		fputs($f,"\t\$FIELDEDIT[\$idx] = \"" . ${"FIELDEDIT$i"} . "\";\n");
		fputs($f,"\t\$READONLY[\$idx] = \"" . ${"READONLY$i"} . "\";\n");
		fputs($f,"\t\$NONEW[\$idx] = \"" . ${"NONEW$i"} . "\";\n");
		fputs($f,"\t\$NOEDIT[\$idx] = \"" . ${"NOEDIT$i"} . "\";\n");
		fputs($f,"\t\$NODETAIL[\$idx] = \"" . ${"NODETAIL$i"} . "\";\n");
		fputs($f,"\t\$NOINSERT[\$idx] = \"" . ${"NOINSERT$i"} . "\";\n");
		fputs($f,"\t\$NOUPDATE[\$idx] = \"" . ${"NOUPDATE$i"} . "\";\n");
		fputs($f,"\t\$NOPRINT[\$idx] = \"" . ${"NOPRINT$i"} . "\";\n");
		fputs($f,"\t\$BROWSESEARCH[\$idx] = \"" . ${"BROWSESEARCH$i"} . "\";\n");
		fputs($f,"\t\$V_COLSEDIT[\$idx] = \"" . ${"V_COLSEDIT$i"} . "\";\n");
		fputs($f,"\t\$V_COLSDETAIL[\$idx] = \"" . ${"V_COLSDETAIL$i"} . "\";\n");
		fputs($f,"\t\$COLEDIT[\$idx] = \"" . ${"COLEDIT$i"} . "\";\n");
		fputs($f,"\t\$ROWEDIT[\$idx] = \"" . ${"ROWEDIT$i"} . "\";\n");
		fputs($f,"\t\$COLDETAIL[\$idx] = \"" . ${"COLDETAIL$i"} . "\";\n");
		fputs($f,"\t\$ROWDETAIL[\$idx] = \"" . ${"ROWDETAIL$i"} . "\";\n");
		fputs($f,"\n\t\$idx++;\n");
		$j++;
		$i=$numf+1;
	  }
	}
  }
  if ($i<($numf+1)) {
    for ($i = ($puntero+1); $i < $numf; $i++) { 
	if ($orderby=="") {
    	    if (${"ORDERBY$i"} !="") $orderby=${"ORDERBY$i"};
	}
	if (!isset(${"FIELDORDER$i"})) ${"FIELDORDER$i"}="";
	if (!isset(${"EXTRA$i"})) ${"EXTRA$i"}="";
	if (!isset(${"VDEFAULT$i"})) ${"VDEFAULT$i"}="";
	if (!isset(${"LISTA$i"})) ${"LISTA$i"}="";
	if (!isset(${"VONCHANGE$i"})) ${"VONCHANGE$i"}="";
	if (!isset(${"COLUMN$i"})) ${"COLUMN$i"}="";
	if (!isset(${"HELP$i"})) ${"HELP$i"}="";
	if (!isset(${"FUNCNEW$i"})) ${"FUNCNEW$i"}="";
	if (!isset(${"OVERLAP$i"})) ${"OVERLAP$i"}="";
	if (!isset(${"V_COLSEDIT$i"})) ${"V_COLSEDIT$i"}="";
	if (!isset(${"V_COLSDETAIL$i"})) ${"V_COLSDETAIL$i"}="";
	if (!isset(${"COLEDIT$i"})) ${"COLEDIT$i"}="";
	if (!isset(${"ROWEDIT$i"})) ${"ROWEDIT$i"}="";
	if (!isset(${"COLDETAIL$i"})) ${"COLDETAIL$i"}="";
	if (!isset(${"ROWDETAIL$i"})) ${"ROWDETAIL$i"}="";
	if (!isset(${"NAME$i"})) ${"NAME$i"}="";
	if (${"FIELDORDER$i"} == "") {
	  if (${"NAME$i"} != "") {
		$puntero=$i;
		fputs($f,"\t\$TITLE[\$idx] = \"" . ${"TITLE$i"} . "\";\n");
		fputs($f,"\t\$NAME[\$idx] = \"" . ${"NAME$i"} . "\";\n");
		fputs($f,"\t\$LENGTH[\$idx] = \"" . ${"LENGTH$i"} . "\";\n");
		fputs($f,"\t\$ILENGTH[\$idx] = \"" . ${"ILENGTH$i"} . "\";\n");
		fputs($f,"\t\$ORDERBY[\$idx] = \"" . ${"ORDERBY$i"} . "\";\n");
		fputs($f,"\t\$CANBENULL[\$idx] = \"" . ${"CANBENULL$i"} . "\";\n");
		fputs($f,"\t\$TYPE[\$idx] = \"" . ${"TYPE$i"} . "\";\n");
		fputs($f,"\t\$DTYPE[\$idx] = \"" . ${"DTYPE$i"} . "\";\n");
		fputs($f,"\t\$EXTRA[\$idx] = \"" . ${"EXTRA$i"} . "\";\n");
		fputs($f,"\t\$VDEFAULT[\$idx] = \"" . ${"VDEFAULT$i"} . "\";\n");
		fputs($f,"\t\$LISTA[\$idx] = \"" . ${"LISTA$i"} . "\";\n");
		fputs($f,"\t\$VONCHANGE[\$idx] = \"" . ${"VONCHANGE$i"} . "\";\n");
		fputs($f,"\t\$COLUMN[\$idx] = \"" . ${"COLUMN$i"} . "\";\n");
		fputs($f,"\t\$HELP[\$idx] = \"" . ${"HELP$i"} . "\";\n");
		fputs($f,"\t\$FUNCNEW[\$idx] = \"" . ${"FUNCNEW$i"} . "\";\n");
		fputs($f,"\t\$OVERLAP[\$idx] = \"" . ${"OVERLAP$i"} . "\";\n");
		fputs($f,"\t\$SEARCHABLE[\$idx] = \"" . ${"SEARCHABLE$i"} . "\";\n");
		fputs($f,"\t\$BROWSABLE[\$idx] = \"" . ${"BROWSABLE$i"} . "\";\n");
		fputs($f,"\t\$BROWSEDIT[\$idx] = \"" . ${"BROWSEDIT$i"} . "\";\n");
		fputs($f,"\t\$FIELDEDIT[\$idx] = \"" . ${"FIELDEDIT$i"} . "\";\n");
		fputs($f,"\t\$READONLY[\$idx] = \"" . ${"READONLY$i"} . "\";\n");
		fputs($f,"\t\$NONEW[\$idx] = \"" . ${"NONEW$i"} . "\";\n");
		fputs($f,"\t\$NOEDIT[\$idx] = \"" . ${"NOEDIT$i"} . "\";\n");
		fputs($f,"\t\$NODETAIL[\$idx] = \"" . ${"NODETAIL$i"} . "\";\n");
		fputs($f,"\t\$NOINSERT[\$idx] = \"" . ${"NOINSERT$i"} . "\";\n");
		fputs($f,"\t\$NOUPDATE[\$idx] = \"" . ${"NOUPDATE$i"} . "\";\n");
		fputs($f,"\t\$NOPRINT[\$idx] = \"" . ${"NOPRINT$i"} . "\";\n");
		fputs($f,"\t\$BROWSESEARCH[\$idx] = \"" . ${"BROWSESEARCH$i"} . "\";\n");
		fputs($f,"\t\$V_COLSEDIT[\$idx] = \"" . ${"V_COLSEDIT$i"} . "\";\n");
		fputs($f,"\t\$V_COLSDETAIL[\$idx] = \"" . ${"V_COLSDETAIL$i"} . "\";\n");
		fputs($f,"\t\$COLEDIT[\$idx] = \"" . ${"COLEDIT$i"} . "\";\n");
		fputs($f,"\t\$ROWEDIT[\$idx] = \"" . ${"ROWEDIT$i"} . "\";\n");
		fputs($f,"\t\$COLDETAIL[\$idx] = \"" . ${"COLDETAIL$i"} . "\";\n");
		fputs($f,"\t\$ROWDETAIL[\$idx] = \"" . ${"ROWDETAIL$i"} . "\";\n");
		fputs($f,"\n\t\$idx++;\n");
		$j++;
		$i=$numf+1;
	  }
	}
    }
  }
}
fputs($f,"\t\$orderby = \"$orderby\";\n");
fputs($f,"\t\$defaultfilter = \"$defaultfilter\";\n");

while ($file = each($files)) {
	fputs($f,"\n");
	fputs($f,"\t\$".$file["value"]."_S = \"".${$file["value"]."_S"}."\";\n");
	fputs($f,"\t\$".$file["value"]."_D = \"".${$file["value"]."_D"}."\";\n");
	fputs($f,"\t\$".$file["value"]."_OT = \"".${$file["value"]."_OT"}."\";\n");
}
fputs($f,"\n");

fputs($f,"?>\n");
fclose($f);
chmod("modules/".$modulesdir."/".$modulename.".prj.php", $cmask); 

//***************************** Save module file (php) *****************************
if (!file_exists("modules/".$modulesdir))
   mkdir ("modules/".$modulesdir,0777);
$f = fopen("modules/".$modulesdir."/".$modulename.".php","w");

$today = Date("Y-m-d");
fputs($f,"<?php\n");
fputs($f,"/*** Build by RAD 2.00:  $today ***/\n\n");
if ($RAD!="") {
    fputs($f,"if (eregi(basename(__FILE__), \$PHP_SELF)) die (\"Security Error ...\");\n");
    fputs($f,"require_once(\"mainfile.php\");\n\n");
    fputs($f,"\tinclude_once (\"header.php\");\n\tif (\$func!=\"search_js\" && \$func!=\"none\") OpenTable();\n\n");
}

if ($menuoff !="") fputs($f,"\t\$menuoff = \"x\";\n");
if ($headeroff !="") fputs($f,"\t\$headeroff = \"x\";\n");
if ($footeroff !="") fputs($f,"\t\$footeroff = \"x\";\n");

if ($menubrowse !="") fputs($f,"\t\$menubrowse = true;\n");
else fputs($f,"\t\$menubrowse = false;\n");
if ($menuminisearch !="") fputs($f,"\t\$menuminisearch = true;\n");
else fputs($f,"\t\$menuminisearch = false;\n");
if ($menubackup !="") fputs($f,"\t\$menubackup = true;\n");
else fputs($f,"\t\$menubackup = false;\n");
if ($menudetail !="") fputs($f,"\t\$menudetail = true;\n");
else fputs($f,"\t\$menudetail = false;\n");
if ($menusearch !="") fputs($f,"\t\$menusearch = true;\n");
else fputs($f,"\t\$menusearch = false;\n");
if ($menusend !="") fputs($f,"\t\$menusend = true;\n");
else fputs($f,"\t\$menusend = false;\n");
if ($menuprint !="") fputs($f,"\t\$menuprint = true;\n");
else fputs($f,"\t\$menuprint = false;\n");
if ($menunew !="") fputs($f,"\t\$menunew = true;\n");
else fputs($f,"\t\$menunew = false;\n");
if ($menuedit !="") fputs($f,"\t\$menuedit = true;\n");
else fputs($f,"\t\$menuedit = false;\n");
if ($menudelete !="") fputs($f,"\t\$menudelete = true;\n");
else fputs($f,"\t\$menudelete = false;\n");
if ($menureport !="") fputs($f,"\t\$menureport = true;\n");
else fputs($f,"\t\$menureport = false;\n");
if ($menujump !="") fputs($f,"\t\$menujump = true;\n");
else fputs($f,"\t\$menujump = false;\n");
if ($menubletter !="") fputs($f,"\t\$menubletter = true;\n");
else fputs($f,"\t\$menubletter = false;\n");
if ($logsql !="") fputs($f,"\t\$logsql = true;\n");
else fputs($f,"\t\$logsql = false;\n");

$fcs = $f;

// fputs($fcs,"\t\$hostname = \"$hostname\";\n");
// fputs($fcs,"\t\$dbusername = \"$dbusername\";\n");
// fputs($fcs,"\t\$dbpassword = \"$dbpassword\";\n");
// fputs($fcs,"\t\$dbtype = \"$dbtype\";\n");
fputs($fcs,"\tif (!isset(\$dbname)) \$dbname = \"\";\n");
fputs($fcs,"\tif (\$dbname==\"\") \$dbname = \"$dbname\";\n");
if ($RAD !="") fputs($fcs,"\tif (\$dbname==\"\") \$dbname = _DEF_dbname;\n");
fputs($fcs,"?>\n");

$fis = $f;

fputs($fis,"<?\n");
fputs($fis,"\t\$title = \"$title\";\n");
fputs($fis,"\t\$tablename = \"$tablename\";\n");
fputs($fis,"\t\$security = \"$security\";\n");
fputs($fis,"\t\$tableConfig = \"$tableConfig\";\n");
fputs($fis,"\t\$fieldConfig = \"$fieldConfig\";\n");
fputs($fis,"\t\$tableUser = \"$tableUser\";\n");
fputs($fis,"\t\$fieldUser = \"$fieldUser\";\n");
fputs($fis,"\t\$fieldPass = \"$fieldPass\";\n");
fputs($fis,"\t\$defaultfunc = \"$defaultfunc\";\n");
fputs($fis,"\t\$modulesdir = \"$modulesdir\";\n");
fputs($fis,"\t\$browsetype = \"$browsetype\";\n");
if ($V_rowsinsert!="") fputs($fis,"\t\$V_rowsinsert = \"$V_rowsinsert\";\n");
if ($V_rowsinsertend!="") fputs($fis,"\t\$V_rowsinsertend = \"$V_rowsinsertend\";\n");
if ($RAD !="") {
    fputs($fis,"\t\$RAD_DirBase = \"modules/\".\$modulesdir.\"/\";\n");
//    fputs($fis,"\t\$RAD_DirBase = \"modules/phpRAD/\";\n");
} else {
    fputs($fis,"\t\$RAD_DirBase = \"\";\n");
}
fputs($fis,"\t\$tableConfig = \"$tableConfig\";\n");
fputs($fis,"\t\$fieldConfig = \"$fieldConfig\";\n");
fputs($fis,"\t\$tableUser = \"$tableUser\";\n");
fputs($fis,"\t\$fieldUser = \"$fieldUser\";\n");
fputs($fis,"\t\$fieldPass = \"$fieldPass\";\n");
fputs($fis,"\t\$idnames = \"$idnames\";\n");
for ($i = 0; $i < count($idname); $i++) { 
	if ($idname[$i] != "") {
		fputs($fis,"\t\$idname$i = \"" . $idname[$i] . "\";\n");
	}
}
fputs($fis,"\tif (empty(\$orderby)) \$orderby = \"$orderby\";\n");

fputs($fis,"\t\$rows_limit = $rows_limit;\n");
fputs($fis,"\t\$V_colsedit = $V_colsedit;\n");
fputs($fis,"\t\$V_colsdetail = $V_colsdetail;\n");
fputs($fis,"\t\$verbose_queries = false;\n");
fputs($fis,"\tif (!class_exists(\"finfo\")) { class finfo {\n");
fputs($fis,"\t\tvar \$name;\n");
fputs($fis,"\t\tvar \$title;\n");
fputs($fis,"\t\tvar \$length;\n");
fputs($fis,"\t\tvar \$ilength;\n");
fputs($fis,"\t\tvar \$type;\n");
fputs($fis,"\t\tvar \$dtype;\n");
fputs($fis,"\t\tvar \$extra;\n");
fputs($fis,"\t\tvar \$vdefault;\n");
fputs($fis,"\t\tvar \$lista;\n");
fputs($fis,"\t\tvar \$vonchange;\n");
fputs($fis,"\t\tvar \$column;\n");
fputs($fis,"\t\tvar \$help;\n");
fputs($fis,"\t\tvar \$funcnew;\n");
fputs($fis,"\t\tvar \$overlap;\n");
fputs($fis,"\t\tvar \$browsable;\n");
fputs($fis,"\t\tvar \$browsedit;\n");
fputs($fis,"\t\tvar \$fieldedit;\n");
fputs($fis,"\t\tvar \$readonly;\n");
fputs($fis,"\t\tvar \$nonew;\n");
fputs($fis,"\t\tvar \$noedit;\n");
fputs($fis,"\t\tvar \$nodetail;\n");
fputs($fis,"\t\tvar \$noinsert;\n");
fputs($fis,"\t\tvar \$noupdate;\n");
fputs($fis,"\t\tvar \$noprint;\n");
fputs($fis,"\t\tvar \$browsesearch;\n");
fputs($fis,"\t\tvar \$searchable;\n");
fputs($fis,"\t\tvar \$orderby;\n");
fputs($fis,"\t\tvar \$canbenull;\n");
fputs($fis,"\t\tvar \$V_colsedit;\n");
fputs($fis,"\t\tvar \$V_colsdetail;\n");
fputs($fis,"\t\tvar \$coledit;\n");
fputs($fis,"\t\tvar \$rowedit;\n");
fputs($fis,"\t\tvar \$coldetail;\n");
fputs($fis,"\t\tvar \$rowdetail;\n");
fputs($fis,"\t}; }\n");
fputs($fis,"\n");

//*** Insert arrays for fixed-value lists 
$realnumf = 0;
fputs($fis, "\t\$idx=0;");

$puntero=-1;
for ($ki = 0; $ki < $numf; $ki++) { 
  for ($i = 0; $i < $numf; $i++) { 
	if (${"FIELDORDER$i"} == $ki+1) {
	  if (${"NAME$i"} != "") {
		fputs($fis, "\n");
		fputs($fis, "\t\$fields[\$idx] = new finfo;\n");
		fputs($fis, "\t\$findex[\"".${"NAME$i"}."\"] = \$idx;\n");
		fputs($fis, "\t\$fields[\$idx] -> name = \"".${"NAME$i"}."\";\n");
		if (substr(${"TITLE$i"},0,1)=="_") { fputs($fis, "\t\$fields[\$idx] -> title = ".${"TITLE$i"}.";\n");
		} else { fputs($fis, "\t\$fields[\$idx] -> title = \"".${"TITLE$i"}."\";\n"); }

		fputs($fis, "\t\$fields[\$idx] -> length = \"".(${"LENGTH$i"} != "" ? ${"LENGTH$i"}: "0")."\";\n");
		fputs($fis, "\t\$fields[\$idx] -> ilength = \"".(${"ILENGTH$i"} != "" ? ${"ILENGTH$i"} : "0")."\";\n");
		fputs($fis, "\t\$fields[\$idx] -> type = \"".${"TYPE$i"}."\";\n");
		fputs($fis, "\t\$fields[\$idx] -> dtype = \"".${"DTYPE$i"}."\";\n");
		fputs($fis, "\t\$fields[\$idx] -> extra = \"".${"EXTRA$i"}."\";\n");
		fputs($fis, "\t\$fields[\$idx] -> vdefault = \"".${"VDEFAULT$i"}."\";\n");
		fputs($fis, "\t\$fields[\$idx] -> lista = \"".${"LISTA$i"}."\";\n");
		fputs($fis, "\t\$fields[\$idx] -> vonchange = \"".${"VONCHANGE$i"}."\";\n");
		fputs($fis, "\t\$fields[\$idx] -> column = \"".${"COLUMN$i"}."\";\n");
		fputs($fis, "\t\$fields[\$idx] -> help = \"".${"HELP$i"}."\";\n");
		fputs($fis, "\t\$fields[\$idx] -> funcnew = \"".${"FUNCNEW$i"}."\";\n");
		fputs($fis, "\t\$fields[\$idx] -> overlap = \"".${"OVERLAP$i"}."\";\n");
		fputs($fis, "\t\$fields[\$idx] -> browsable = ".(${"BROWSABLE$i"} != "" ? "true" : "false").";\n");
		fputs($fis, "\t\$fields[\$idx] -> browsedit = ".(${"BROWSEDIT$i"} != "" ? "true" : "false").";\n");
		fputs($fis, "\t\$fields[\$idx] -> fieldedit = ".(${"FIELDEDIT$i"} != "" ? "true" : "false").";\n");
		fputs($fis, "\t\$fields[\$idx] -> readonly = ".(${"READONLY$i"} != "" ? "true" : "false").";\n");
		fputs($fis, "\t\$fields[\$idx] -> nonew = ".(${"NONEW$i"} != "" ? "true" : "false").";\n");
		fputs($fis, "\t\$fields[\$idx] -> noedit = ".(${"NOEDIT$i"} != "" ? "true" : "false").";\n");
		fputs($fis, "\t\$fields[\$idx] -> nodetail = ".(${"NODETAIL$i"} != "" ? "true" : "false").";\n");
		fputs($fis, "\t\$fields[\$idx] -> noinsert = ".(${"NOINSERT$i"} != "" ? "true" : "false").";\n");
		fputs($fis, "\t\$fields[\$idx] -> noupdate = ".(${"NOUPDATE$i"} != "" ? "true" : "false").";\n");
		fputs($fis, "\t\$fields[\$idx] -> noprint = ".(${"NOPRINT$i"} != "" ? "true" : "false").";\n");
		fputs($fis, "\t\$fields[\$idx] -> browsesearch = ".(${"BROWSESEARCH$i"} != "" ? "true" : "false").";\n");
		fputs($fis, "\t\$fields[\$idx] -> searchable = ".(${"SEARCHABLE$i"} != "" ? "true" : "false").";\n");
		fputs($fis, "\t\$fields[\$idx] -> orderby = ".(${"ORDERBY$i"} != "" ? "true" : "false").";\n");
		fputs($fis, "\t\$fields[\$idx] -> canbenull = ".(${"CANBENULL$i"} != "" ? "true" : "false").";\n");
		fputs($fis, "\t\$fields[\$idx] -> V_colsedit = \"".${"V_COLSEDIT$i"}."\";\n");
		fputs($fis, "\t\$fields[\$idx] -> V_colsdetail = \"".${"V_COLSDETAIL$i"}."\";\n");
		fputs($fis, "\t\$fields[\$idx] -> coledit = \"".${"COLEDIT$i"}."\";\n");
		fputs($fis, "\t\$fields[\$idx] -> rowedit = \"".${"ROWEDIT$i"}."\";\n");
		fputs($fis, "\t\$fields[\$idx] -> coldetail = \"".${"COLDETAIL$i"}."\";\n");
		fputs($fis, "\t\$fields[\$idx] -> rowdetail = \"".${"ROWDETAIL$i"}."\";\n");
		$realnumf++;
		fputs($fis, "\t\$idx++;\n");
		$j++;
		$i=$numf+1;
	  }
	}
  }
  if ($i<($numf+1)) {
    for ($i = ($puntero+1); $i < $numf; $i++) { 
	if ($orderby=="") {
    	    if (${"ORDERBY$i"} !="") $orderby=${"ORDERBY$i"};
	}
	if (${"FIELDORDER$i"} == "") {
	  if (${"NAME$i"} != "") {
		$puntero=$i;
		fputs($fis, "\n");
		fputs($fis, "\t\$fields[\$idx] = new finfo;\n");
		fputs($fis, "\t\$findex[\"".${"NAME$i"}."\"] = \$idx;\n");
		fputs($fis, "\t\$fields[\$idx] -> name = \"".${"NAME$i"}."\";\n");
		if (substr(${"TITLE$i"},0,1)=="_") { fputs($fis, "\t\$fields[\$idx] -> title = ".${"TITLE$i"}.";\n");
		} else { fputs($fis, "\t\$fields[\$idx] -> title = \"".${"TITLE$i"}."\";\n"); }
		fputs($fis, "\t\$fields[\$idx] -> length = \"".(${"LENGTH$i"} != "" ? ${"LENGTH$i"}: "0")."\";\n");
		fputs($fis, "\t\$fields[\$idx] -> ilength = \"".(${"ILENGTH$i"} != "" ? ${"ILENGTH$i"} : "0")."\";\n");
		fputs($fis, "\t\$fields[\$idx] -> type = \"".${"TYPE$i"}."\";\n");
		fputs($fis, "\t\$fields[\$idx] -> dtype = \"".${"DTYPE$i"}."\";\n");
		fputs($fis, "\t\$fields[\$idx] -> extra = \"".${"EXTRA$i"}."\";\n");
		fputs($fis, "\t\$fields[\$idx] -> vdefault = \"".${"VDEFAULT$i"}."\";\n");
		fputs($fis, "\t\$fields[\$idx] -> lista = \"".${"LISTA$i"}."\";\n");
		fputs($fis, "\t\$fields[\$idx] -> vonchange = \"".${"VONCHANGE$i"}."\";\n");
		fputs($fis, "\t\$fields[\$idx] -> column = \"".${"COLUMN$i"}."\";\n");
		fputs($fis, "\t\$fields[\$idx] -> help = \"".${"HELP$i"}."\";\n");
		fputs($fis, "\t\$fields[\$idx] -> funcnew = \"".${"FUNCNEW$i"}."\";\n");
		fputs($fis, "\t\$fields[\$idx] -> overlap = \"".${"OVERLAP$i"}."\";\n");
		fputs($fis, "\t\$fields[\$idx] -> browsable = ".(${"BROWSABLE$i"} != "" ? "true" : "false").";\n");
		fputs($fis, "\t\$fields[\$idx] -> browsedit = ".(${"BROWSEDIT$i"} != "" ? "true" : "false").";\n");
		fputs($fis, "\t\$fields[\$idx] -> fieldedit = ".(${"FIELDEDIT$i"} != "" ? "true" : "false").";\n");
		fputs($fis, "\t\$fields[\$idx] -> readonly = ".(${"READONLY$i"} != "" ? "true" : "false").";\n");
		fputs($fis, "\t\$fields[\$idx] -> nonew = ".(${"NONEW$i"} != "" ? "true" : "false").";\n");
		fputs($fis, "\t\$fields[\$idx] -> noedit = ".(${"NOEDIT$i"} != "" ? "true" : "false").";\n");
		fputs($fis, "\t\$fields[\$idx] -> nodetail = ".(${"NODETAIL$i"} != "" ? "true" : "false").";\n");
		fputs($fis, "\t\$fields[\$idx] -> noinsert = ".(${"NOINSERT$i"} != "" ? "true" : "false").";\n");
		fputs($fis, "\t\$fields[\$idx] -> noupdate = ".(${"NOUPDATE$i"} != "" ? "true" : "false").";\n");
		fputs($fis, "\t\$fields[\$idx] -> noprint = ".(${"NOPRINT$i"} != "" ? "true" : "false").";\n");
		fputs($fis, "\t\$fields[\$idx] -> browsesearch = ".(${"BROWSESEARCH$i"} != "" ? "true" : "false").";\n");
		fputs($fis, "\t\$fields[\$idx] -> searchable = ".(${"SEARCHABLE$i"} != "" ? "true" : "false").";\n");
		fputs($fis, "\t\$fields[\$idx] -> orderby = ".(${"ORDERBY$i"} != "" ? "true" : "false").";\n");
		fputs($fis, "\t\$fields[\$idx] -> canbenull = ".(${"CANBENULL$i"} != "" ? "true" : "false").";\n");
		fputs($fis, "\t\$fields[\$idx] -> V_colsedit = \"".${"V_COLSEDIT$i"}."\";\n");
		fputs($fis, "\t\$fields[\$idx] -> V_colsdetail = \"".${"V_COLSDETAIL$i"}."\";\n");
		fputs($fis, "\t\$fields[\$idx] -> coledit = \"".${"COLEDIT$i"}."\";\n");
		fputs($fis, "\t\$fields[\$idx] -> rowedit = \"".${"ROWEDIT$i"}."\";\n");
		fputs($fis, "\t\$fields[\$idx] -> coldetail = \"".${"COLDETAIL$i"}."\";\n");
		fputs($fis, "\t\$fields[\$idx] -> rowdetail = \"".${"ROWDETAIL$i"}."\";\n");
		$realnumf++;
		fputs($fis, "\t\$idx++;\n");
		$j++;
		$i=$numf+1;
	  }
	}
    }
  }
}

fputs($fis,"\n\t\$numf = \$idx;\n");
fputs($f,"\t\$defaultfilter = \"$defaultfilter\";\n");
fputs($fis,"?>\n");

iorr($f, $GENUTIL_S, $GENUTIL_D, $GENUTIL_OT);
if ($RAD !="") {
    fputs($f,"<?  \$TMP_module_name = basename(dirname(__FILE__));\n");
    fputs($f,"  \$TMP_filelang = get_lang(\$TMP_module_name);\n");
    fputs($f,"  if (\$TMP_filelang!=\"\") include(\$TMP_filelang); ?>\n");
}
iorr($f, $SQL_S, $SQL_D, $SQL_OT);
iorr($f, $DELETE_S, $DELETE_D, $DELETE_OT);
iorr($f, $UPDATE_S, $UPDATE_D, $UPDATE_OT);
iorr($f, $INSERT_S, $INSERT_D, $INSERT_OT);
if ($RAD=="") {
	fputs($f,"<HTML>\n");
	fputs($f,"<HEAD>\n");
	fputs($f,"<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; "."charset=<? echo _DEF_NLSCharset; ?>\">\n");
	fputs($f,"<TITLE><? echo \$title; ?></TITLE>\n");
}

iorr($f, "$themedir/$themefile", $themefile, "fcopy");
iorr($f, $JS_S, $JS_D, $JS_OT);
if ($RAD=="") {
	fputs($f,"</HEAD>\n");
	fputs($f,"<? echo \$body_tag,\"\\n\"; ?>\n");
}
iorr($f, $HEADER_S, $HEADER_D, $HEADER_OT);
iorr($f, $SQL_S, $SQL_D, $SQL_OT);
iorr($f, $MENU_S, $MENU_D, $MENU_OT);
iorr($f, $BACKUP_S, $BACKUP_D, $BACKUP_OT);
iorr($f, $BROWSE_S, $BROWSE_D, $BROWSE_OT);
iorr($f, $DRECORD_S, $DRECORD_D, $DRECORD_OT);
iorr($f, $EFORM_S, $EFORM_D, $EFORM_OT);
iorr($f, $SFORM_S, $SFORM_D, $SFORM_OT);
iorr($f, $ERROR_S, $ERROR_D, $ERROR_OT);
iorr($f, $FOOTER_S, $FOOTER_D, $FOOTER_OT);
if ($RAD!="") fputs($f,"<? if (\$func!=\"search_js\" && \$func!=\"none\") CloseTable();\n   include_once (\"footer.php\"); ?>\n");
fclose($f);
chmod("modules/".$modulesdir."/".$modulename.".php", $cmask); 
?>

	<?=_DEF_NLSModule." "._DEF_NLSSaved?> <A TARGET=_blank HREF="<? echo "index.php?V_dir=$modulesdir&V_mod=".$modulename.$SESSION_SID; ?>"><B><? echo "$modulesdir/".$modulename.".php"; ?></B></A><BR>
	<?=_DEF_NLSProject." "._DEF_NLSSaved?> <a href="index.php?V_dir=<?=$V_dir?>&V_mod=indexRAD&V_submod=genform&modulesdir=<?=$modulesdir?>&project_file=<?=$modulename?>.prj.php<?=$SESSION_SID?>"><? echo "$modulesdir/$modulename.prj.php"; ?></a><BR><BR>
	<a href="index.php?V_dir=admin&V_mod=modulos&func=new<?=$SESSION_SID?>">Activar <?=_DEF_NLSModule?></a><BR><BR>
	<HR NOSHADE SIZE=1>

<?
}

//---------------------------------------------------------------------------------------------------------------
for ($k = 0; $k < $numf; $k++) {
	${"TITLE$k"}="";
	${"NAME$k"}="";
	${"VNEW$k"}="";
	${"SHOW$k"}="";
}
$directoryHead=$modulesdir;
$filename="head.app";
if ($opt != "all") $tables[0]=$tablename;
else $tables = explode(",", $tablenames);
for ($k = 0; $k < count($tables); $k++) {
	$idnames="";
	if ($tables[$k]!="") {
		$tablename=$tables[$k];
	} else {	
		$tablename="";
		continue;
	}
	${"TITLE$k"}=ucwords($tablename);
	${"NAME$k"}=$tablename.".php";
	${"VNEW$k"}="";
	${"SHOW$k"}="on";
}
$numh=count($tables)+1;
//*****************************************************************************
// Head Generator
//*****************************************************************************

$f = fopen("modules/".$directoryHead."/".$filename.".hpj.php","w");
fputs($f,"<?\n");

$j = 0;

fputs($f,"\t\$idx=0;\n");

$puntero=-1;
for ($ki = 0; $ki < $numh+5; $ki++) { 
  for ($i = 0; $i < $numh+5; $i++) { 
	if (!isset(${"ORDER$i"})) ${"ORDER$i"}="";
	if (!isset(${"NAME$i"})) ${"NAME$i"}="";
	if (${"ORDER$i"} == $ki+1) {
	  if (${"NAME$i"} != "") {
		fputs($f,"\t\$TITLE[\$idx] = \"" . ${"TITLE$i"} . "\";\n");
		fputs($f,"\t\$NAME[\$idx] = \"" . ${"NAME$i"} . "\";\n");
		fputs($f,"\t\$VNEW[\$idx] = \"" . ${"VNEW$i"} . "\";\n");
		fputs($f,"\t\$SHOW[\$idx] = \"" . ${"SHOW$i"} . "\";\n");
		fputs($f,"\n\t\$idx++;\n");
		$j++;
		$i=$numh+6;
	  }
	}
  }
  if ($i<($numh+6)) {
    for ($i = ($puntero+1); $i < $numh; $i++) { 
	if (!isset(${"ORDER$i"})) ${"ORDER$i"}="";
	if (!isset(${"NAME$i"})) ${"NAME$i"}="";
	if (${"ORDER$i"} == "") {
	  if (${"NAME$i"} != "") {
		$puntero=$i;
		fputs($f,"\t\$TITLE[\$idx] = \"" . ${"TITLE$i"} . "\";\n");
		fputs($f,"\t\$NAME[\$idx] = \"" . ${"NAME$i"} . "\";\n");
		fputs($f,"\t\$VNEW[\$idx] = \"" . ${"VNEW$i"} . "\";\n");
		fputs($f,"\t\$SHOW[\$idx] = \"" . ${"SHOW$i"} . "\";\n");
		fputs($f,"\n\t\$idx++;\n");
		$j++;
		$i=$numh+1;
	  }
	}
    }
  }
}

fputs($f,"\n\t\$numh = \$idx;\n");

fputs($f,"?>\n");
fclose($f);
chmod("modules/".$directoryHead."/" . $filename. ".hpj.php", $cmask); 


//*************** Generate head
$f = fopen("modules/".$directoryHead."/".$filename.".php","w");
$today = Date("Y-m-d");
fputs($f,"<?\n");
fputs($f,"/*** Build by RAD "._DEF_VERSION_RAD.":  $today ***/\n\n");

fputs($f,"\tclass hinfo {\n");
fputs($f,"\t\tvar \$name;\n");
fputs($f,"\t\tvar \$title;\n");
fputs($f,"\t\tvar \$vnew;\n");
fputs($f,"\t\tvar \$show;\n");
fputs($f,"\t};\n");
fputs($f,"\n");

/*** Insert arrays for fixed-value lists ***/
$realnumh = 0;
fputs($f, "\t\$idx=0;");

$puntero=-1;
for ($ki = 0; $ki < $numh+5; $ki++) { 
  for ($i = 0; $i < $numh+5; $i++) { 
	if (${"ORDER$i"} == $ki+1) {
	  if (${"NAME$i"} != "") {
		fputs($f, "\n");
		fputs($f, "\t\$heads[\$idx] = new hinfo;\n");
		fputs($f, "\t\$hindex[\"".${"NAME$i"}."\"] = \$idx;\n");
		fputs($f, "\t\$heads[\$idx] -> name = \"".${"NAME$i"}."\";\n");
		if (substr(${"TITLE$i"},0,1)=="_") { fputs($f, "\t\$heads[\$idx] -> title = ".${"TITLE$i"}.";\n");
		} else { fputs($f, "\t\$heads[\$idx] -> title = \"".${"TITLE$i"}."\";\n"); }
		fputs($f, "\t\$heads[\$idx] -> vnew = ".(${"VNEW$i"} != "" ? "true" : "false").";\n");
		fputs($f, "\t\$heads[\$idx] -> show = ".(${"SHOW$i"} != "" ? "true" : "false").";\n");
		$realnumh++;
		fputs($f, "\t\$idx++;\n");
		$j++;
		$i=$numh+6;
	  }
	}
  }
  if ($i<($numh+6)) {
    for ($i = ($puntero+1); $i < $numh; $i++) { 
	if (${"ORDER$i"} == "") {
	  if (${"NAME$i"} != "") {
		$puntero=$i;
		fputs($f, "\n");
		fputs($f, "\t\$heads[\$idx] = new hinfo;\n");
		fputs($f, "\t\$hindex[\"".${"NAME$i"}."\"] = \$idx;\n");
		fputs($f, "\t\$heads[\$idx] -> name = \"".${"NAME$i"}."\";\n");
		if (substr(${"TITLE$i"},0,1)=="_") { fputs($f, "\t\$heads[\$idx] -> title = \"".${"TITLE$i"}."\";\n");
		} else { fputs($f, "\t\$heads[\$idx] -> title = \"".${"TITLE$i"}."\";\n"); }
		fputs($f, "\t\$heads[\$idx] -> vnew = ".(${"VNEW$i"} != "" ? "true" : "false").";\n");
		fputs($f, "\t\$heads[\$idx] -> show = ".(${"SHOW$i"} != "" ? "true" : "false").";\n");
		$realnumh++;
		fputs($f, "\t\$idx++;\n");
		$j++;
		$i=$numh+1;
	  }
	}
    }
  }
}

fputs($f,"\n\t\$numh = \$idx;\n\n");

fputs($f,"\$numPag=\$hindex[basename(\$PHP_SELF)];\n");
fputs($f,"if (\$heads[\$numPag] -> show && \$menuoff ==\"\") {\n");
fputs($f,"  echo \"<TABLE class=menu>\\n<TR>\"; \n");
fputs($f,"  for (\$ki = 0; \$ki < \$numh; \$ki++) { \n");
fputs($f,"	if (basename(\$PHP_SELF) == \$heads[\$ki]->name) {\n");
fputs($f,"		echo \"<TD WIDTH=80 class=menuon align=center>\".\$heads[\$ki]->title.\"</TD>\\n\";\n");
fputs($f,"	} else {\n");
fputs($f,"		if (\$heads[\$ki]->vnew==true) \$TARGET=\" TARGET=_blank\";\n");
fputs($f,"		else \$TARGET=\"\";\n");
fputs($f,"		echo \"<TD WIDTH=80 class=menuoff align=center><A HREF=\\\"\".\$heads[\$ki]->name.\"?\$tabURLROI\\\"\$TARGET>\".\$heads[\$ki]->title.\"</A></TD>\\n\";\n");
fputs($f,"	}\n");
fputs($f,"  }\n");
fputs($f,"  echo \"</TR>\\n</TABLE>\\n\";\n");
fputs($f,"}\n");

fputs($f,"?>\n");


fclose($f);
chmod("modules/".$directoryHead."/".$filename.".php", $cmask); 

?>
	<?=_DEF_NLSMenu." "._DEF_NLSSaved?> <A TARGET=_blank HREF="index.php?V_dir=<?=$V_dir?>&V_mod=indexRAD&V_submod=fileedit&directory=modules/<?=$directoryHead?>&filename=<?=$filename?>.php<?=$SESSION_SID?>"><B><? echo "$directoryHead/".$filename.".php"; ?></B></A><BR>
	<?=_DEF_NLSProject." "._DEF_NLSSaved?> <A TARGET=_blank HREF="index.php?V_dir=<?=$V_dir?>&V_mod=indexRAD&V_submod=fileedit&directory=modules/<?=$directoryHead?>&filename=<?=$filename?>.hpj.php<?=$SESSION_SID?>"><B><? echo "$directoryHead/".$filename.".hpj.php"; ?></B></A><BR>
	<BR>

<?
//---------------------------------------------------------------------------------------------------------------
CloseTable();
include_once ("footer.php");

?>
