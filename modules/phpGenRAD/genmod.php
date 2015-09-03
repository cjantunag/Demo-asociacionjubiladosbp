<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

//*****************************************************************************
// PHP Generator 
//*****************************************************************************

$modulesdir="modules/".$modulesdir;
$modulesdir_form = $modulesdir;
include_once ("modules/".$V_dir."/defaults.php");

include_once ("header.php");
OpenTable();

$dbname=$V0_dbname;

$defaultfilter=trim($defaultfilter);
if (eregi(" or ",$defaultfilter) && substr($defaultfilter,0,1)!="(") $defaultfilter="(".$defaultfilter.")";

$title=$GEN_title;
$dbtype=strtolower($GEN_dbtype);
$themefile=$GEN_themefile;

global $rows_limit, $V_colsedit, $V_colsdetail;
if ($rows_limit =="") $rows_limit=$limit_default;
if ($V_colsedit =="") $V_colsedit=1;
if ($V_colsdetail =="") $V_colsdetail=1;

?>

<IMG BORDER=0 SRC="modules/<?=$V_dir?>/logo.gif" ALT="RAD">
<HR noshade size=1>
<? 
if (empty($prefix)) { ?>
		<BR><B><?=_DEF_NLSError." "._DEF_NLSPage?><BR>
		<A HREF="javascript:window.history.back()">Volver</A>
<? exit;
}

if ($modulesdir_form != "") $modulesdir = $modulesdir_form;

$files = array("JS", "GENUTIL", "SQL", "DELETE", "UPDATE",
	"INSERT", "HEADER", "MENU", "ERROR", "BACKUP", "BROWSE", "DRECORD",
	"EFORM", "SFORM", "FOOTER");

$idname=explode(",",$idnames);

if(!file_exists($modulesdir)) mkdir ($modulesdir, 0777); // Project Directory

//-------------------------------- Create PROJECT FILE ------------------------
$TMP_modulesdir=substr($modulesdir,8);
$f = fopen($modulesdir."/".$prefix.".prj.php","w");
fputs($f,"<?\n");

if (trim($coment)!="") {
    $coment=str_replace("\r","",$coment);
    if (substr($coment,strlen($coment)-1)!="\n") $coment.="\n";
    $TMP_coment=explode("\n",$coment);
    $comentfinal="";
    foreach ($TMP_coment as $TMP_idx=>$TMP_line) {
	if (trim($TMP_line)!="" && substr(trim($TMP_line),0,2)!="//") $TMP_line="// ".$TMP_line;
	if (trim($TMP_line)!="") $comentfinal.=$TMP_line."\n";
    }
    fputs($f,$comentfinal);
}

fputs($f,"\t\$title = \"$title\";\n");
fputs($f,"\t\$themefile = \"$themefile\";\n");
fputs($f,"\t\$modulesdir = \"$TMP_modulesdir\";\n");
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
if ($menutree !="") fputs($f,"\t\$menutree = true;\n");
else fputs($f,"\t\$menutree = false;\n");
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
if ($menucalendar !="") fputs($f,"\t\$menucalendar = true;\n");
else fputs($f,"\t\$menucalendar = false;\n");

if ($browsetreefield !="") fputs($f,"\t\$browsetreefield = \"$browsetreefield\";\n");
if ($browsetreefieldparent !="") fputs($f,"\t\$browsetreefieldparent = \"$browsetreefieldparent\";\n");
if ($calendarfields !="") fputs($f,"\t\$calendarfields = \"$calendarfields\";\n");

if ($logsql !="") fputs($f,"\t\$logsql = true;\n");
else fputs($f,"\t\$logsql = false;\n");

////if ($hostname!="" && $hostname!=_DEF_dbhost) fputs($f,"\t\$hostname = \"$hostname\";\n");
////if ($dbusername!="" && $dbusername!=_DEF_dbuname) fputs($f,"\t\$dbusername = \"$dbusername\";\n");
////if ($dbpassword!="" && $dbpassword!=_DEF_dbpass) fputs($f,"\t\$dbpassword = \"$dbpassword\";\n");
if ($dbtype!="" && $dbtype!=strtolower(_DEF_dbtype)) fputs($f,"\t\$dbtype = \"$dbtype\";\n");
////if ($dbname!="") fputs($f,"\t\$dbname = \"$dbname\";\n");

fputs($f,"\t\$tablename = \"$tablename\";\n");
fputs($f,"\t\$filename = \"$prefix\";\n");
fputs($f,"\t\$security = \"$security\";\n");
if ($tableConfig!="") fputs($f,"\t\$tableConfig = \"$tableConfig\";\n");
if ($fieldConfig!="") fputs($f,"\t\$fieldConfig = \"$fieldConfig\";\n");
if ($tableUser!="") fputs($f,"\t\$tableUser = \"$tableUser\";\n");
if ($fieldUser!="") fputs($f,"\t\$fieldUser = \"$fieldUser\";\n");
if ($fieldPass!="") fputs($f,"\t\$fieldPass = \"$fieldPass\";\n");
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
for ($i = 0; $i < $numf+3; $i++) {  // quita comillas para evitar errores PHP
	${"TITLE".$i}=str_replace('"','\"',${"TITLE".$i});
	${"DESCRIPTION$i"}=str_replace('"','\"',${"DESCRIPTION$i"});
	${"HELP$i"}=str_replace('"','\"',${"HELP$i"});
	${"EXTRA$i"}=str_replace('"','\"',${"EXTRA$i"});
	${"VDEFAULT$i"}=str_replace('"','\"',${"VDEFAULT$i"});
	${"VONFOCUS$i"}=str_replace('"','\"',${"VONFOCUS$i"});
	${"VONCHANGE$i"}=str_replace('"','\"',${"VONCHANGE$i"});
	${"VONBLUR$i"}=str_replace('"','\"',${"VONBLUR$i"});
}
fputs($f,"\t\$idx=0;\n");
$puntero=-1;
for ($ki = 0; $ki < $numf+3; $ki++) { 
  for ($i = 0; $i < $numf+3; $i++) { 
    	if (${"ORDERBY$i"}==" ") ${"ORDERBY$i"}=${"NAME$i"};
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
		fputs($f,"\t\$VONFOCUS[\$idx] = \"" . ${"VONFOCUS$i"} . "\";\n");
		fputs($f,"\t\$VONCHANGE[\$idx] = \"" . ${"VONCHANGE$i"} . "\";\n");
		fputs($f,"\t\$VONBLUR[\$idx] = \"" . ${"VONBLUR$i"} . "\";\n");
		fputs($f,"\t\$COLUMN[\$idx] = \"" . ${"COLUMN$i"} . "\";\n");
		fputs($f,"\t\$HELP[\$idx] = \"" . ${"HELP$i"} . "\";\n");
		fputs($f,"\t\$DESCRIPTION[\$idx] = \"" . ${"DESCRIPTION$i"} . "\";\n");
		fputs($f,"\t\$FUNCNEW[\$idx] = \"" . ${"FUNCNEW$i"} . "\";\n");
		fputs($f,"\t\$FUNCLINK[\$idx] = \"" . ${"FUNCLINK$i"} . "\";\n");
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
		$idx++;
		$j++;
		$i=$numf+4;
	  }
	}
  }
  if ($i<($numf+4)) {
    for ($i = ($puntero+1); $i < $numf; $i++) { 
    	if (${"ORDERBY$i"}==" ") ${"ORDERBY$i"}=${"NAME$i"};
	if ($orderby=="") {
    	    if (${"ORDERBY$i"} !="") $orderby=${"ORDERBY$i"};
	}
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
		fputs($f,"\t\$VONFOCUS[\$idx] = \"" . ${"VONFOCUS$i"} . "\";\n");
		fputs($f,"\t\$VONCHANGE[\$idx] = \"" . ${"VONCHANGE$i"} . "\";\n");
		fputs($f,"\t\$VONBLUR[\$idx] = \"" . ${"VONBLUR$i"} . "\";\n");
		fputs($f,"\t\$COLUMN[\$idx] = \"" . ${"COLUMN$i"} . "\";\n");
		fputs($f,"\t\$HELP[\$idx] = \"" . ${"HELP$i"} . "\";\n");
		fputs($f,"\t\$DESCRIPTION[\$idx] = \"" . ${"DESCRIPTION$i"} . "\";\n");
		fputs($f,"\t\$FUNCNEW[\$idx] = \"" . ${"FUNCNEW$i"} . "\";\n");
		fputs($f,"\t\$FUNCLINK[\$idx] = \"" . ${"FUNCLINK$i"} . "\";\n");
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
		$idx++;
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
chmod($modulesdir."/".$prefix.".prj.php", $cmask); 

//-------------------------------- Create MODULE FILE ------------------------

$f = fopen($modulesdir."/".$prefix.".php","w");
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
if ($menutree !="") fputs($f,"\t\$menutree = true;\n");
else fputs($f,"\t\$menutree = false;\n");
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
if ($menucalendar !="") fputs($f,"\t\$menucalendar = true;\n");
else fputs($f,"\t\$menucalendar = false;\n");

if ($browsetreefield !="") fputs($f,"\t\$browsetreefield = \"$browsetreefield\";\n");
if ($browsetreefieldparent !="") fputs($f,"\t\$browsetreefieldparent = \"$browsetreefieldparent\";\n");
if ($calendarfields !="") fputs($f,"\t\$calendarfields = \"$calendarfields\";\n");

if ($logsql !="") fputs($f,"\t\$logsql = true;\n");
else fputs($f,"\t\$logsql = false;\n");

$fcs=$f;

////if ($hostname!="" && $hostname!=_DEF_dbhost) fputs($fcs,"\t\$hostname = \"$hostname\";\n");
////if ($dbusername!="" && $dbusername!=_DEF_dbuname) fputs($fcs,"\t\$dbusername = \"$dbusername\";\n");
////if ($dbpassword!="" && $dbpassword!=_DEF_dbpass) fputs($fcs,"\t\$dbpassword = \"$dbpassword\";\n");
// if ($dbtype!="" && $dbtype!=strtolower(_DEF_dbtype)) fputs($fcs,"\t\$dbtype = \"$dbtype\";\n");
// fputs($fcs,"\t\$hostname = \"$hostname\";\n");
// fputs($fcs,"\t\$dbusername = \"$dbusername\";\n");
// fputs($fcs,"\t\$dbpassword = \"$dbpassword\";\n");
fputs($fcs,"\tif (!isset(\$dbname)) \$dbname = \"\";\n");
////fputs($fcs,"\tif (\$dbname==\"\") \$dbname = \"$dbname\";\n");
if ($RAD !="") fputs($fcs,"\tif (\$dbname==\"\") \$dbname = _DEF_dbname;\n");

$fis=$f;

$TMP_modulesdir=substr($modulesdir,8);
fputs($fis,"\t\$title = \"$title\";\n");
fputs($fis,"\t\$tablename = \"$tablename\";\n");
fputs($fis,"\t\$security = \"$security\";\n");
if ($tableConfig!="") fputs($fis,"\t\$tableConfig = \"$tableConfig\";\n");
if ($fieldConfig!="") fputs($fis,"\t\$fieldConfig = \"$fieldConfig\";\n");
if ($tableUser!="") fputs($fis,"\t\$tableUser = \"$tableUser\";\n");
if ($fieldUser!="") fputs($fis,"\t\$fieldUser = \"$fieldUser\";\n");
if ($fieldPass!="") fputs($fis,"\t\$fieldPass = \"$fieldPass\";\n");
fputs($fis,"\t\$defaultfunc = \"$defaultfunc\";\n");
fputs($fis,"\t\$modulesdir = \"$TMP_modulesdir\";\n");
fputs($fis,"\t\$browsetype = \"$browsetype\";\n");
if ($V_rowsinsert!="") fputs($fis,"\t\$V_rowsinsert = \"$V_rowsinsert\";\n");
if ($V_rowsinsertend!="") fputs($fis,"\t\$V_rowsinsertend = \"$V_rowsinsertend\";\n");
if ($RAD !="") {
    fputs($fis,"\t\$RAD_DirBase = \"modules/\".\$modulesdir.\"/\";\n");
//    fputs($fis,"\t\$RAD_DirBase = \"modules/phpRAD/\";\n");
} else {
    fputs($fis,"\t\$RAD_DirBase = \"\";\n");
}

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
if ($verbose_queries!="") fputs($fis,"\t\$verbose_queries = true;\n");
else fputs($fis,"\t\$verbose_queries = false;\n");
fputs($fis,"\n\tif (!class_exists(\"finfo\")) { class finfo {\n");
fputs($fis,"\t\tvar \$name;");
fputs($fis," var \$title;");
fputs($fis," var \$length;");
fputs($fis," var \$ilength;");
fputs($fis," var \$type;");
fputs($fis," var \$dtype;");
fputs($fis," var \$extra;");
fputs($fis," var \$vdefault;");
fputs($fis," var \$lista;");
fputs($fis," var \$vonchange;");
fputs($fis," var \$vonblur;");
fputs($fis," var \$column;");
fputs($fis," var \$help;");
fputs($fis," var \$funcnew;");
fputs($fis," var \$overlap;");
fputs($fis," var \$browsable;");
fputs($fis," var \$browsedit;");
fputs($fis," var \$fieldedit;");
fputs($fis," var \$readonly;");
fputs($fis," var \$nonew;");
fputs($fis," var \$noedit;");
fputs($fis," var \$nodetail;");
fputs($fis," var \$noinsert;");
fputs($fis," var \$noupdate;");
fputs($fis," var \$noprint;");
fputs($fis," var \$browsesearch;");
fputs($fis," var \$searchable;");
fputs($fis," var \$orderby;");
fputs($fis," var \$canbenull;");
fputs($fis," var \$V_colsedit;");
fputs($fis," var \$V_colsdetail;");
fputs($fis," var \$coledit;");
fputs($fis," var \$rowedit;");
fputs($fis," var \$coldetail;");
fputs($fis," var \$rowdetail;\n");
fputs($fis,"\t}; }\n");
fputs($fis,"\n");

/*** Insert arrays for fixed-value lists ***/
$realnumf = 0;
fputs($fis, "\t\$idx=0;");

$puntero=-1;
for ($ki = 0; $ki < $numf+3; $ki++) { 
  for ($i = 0; $i < $numf+3; $i++) { 
	if (${"FIELDORDER$i"} == $ki+1) {
	  if (${"NAME$i"} != "") {
		fputs($fis, "\n");
		fputs($fis, "\t\$fields[\$idx] = new finfo;\n");
		fputs($fis, "\t\$findex[\"".${"NAME$i"}."\"]=\$idx;");
		fputs($fis, " \$fields[\$idx]->name=\"".${"NAME$i"}."\";");
		if (substr(${"TITLE$i"},0,1)=="_") { fputs($fis, " \$fields[\$idx]->title = ".${"TITLE$i"}.";");
		} else { fputs($fis, " \$fields[\$idx]->title=\"".${"TITLE$i"}."\";"); }
		fputs($fis, " \$fields[\$idx]->length=\"".(${"LENGTH$i"} != "" ? ${"LENGTH$i"}: "0")."\";");
		fputs($fis, " \$fields[\$idx]->ilength=\"".(${"ILENGTH$i"} != "" ? ${"ILENGTH$i"} : "0")."\";");
		fputs($fis, " \$fields[\$idx]->type=\"".${"TYPE$i"}."\";");
		fputs($fis, " \$fields[\$idx]->dtype=\"".${"DTYPE$i"}."\";");
		fputs($fis, " \$fields[\$idx]->extra=\"".${"EXTRA$i"}."\";");
		fputs($fis, " \$fields[\$idx]->vdefault=\"".${"VDEFAULT$i"}."\";");
		fputs($fis, " \$fields[\$idx]->lista=\"".${"LISTA$i"}."\";");
		fputs($fis, " \$fields[\$idx]->vonfocus=\"".${"VONFOCUS$i"}."\";");
		fputs($fis, " \$fields[\$idx]->vonchange=\"".${"VONCHANGE$i"}."\";");
		fputs($fis, " \$fields[\$idx]->vonblur=\"".${"VONBLUR$i"}."\";");
		fputs($fis, " \$fields[\$idx]->column=\"".${"COLUMN$i"}."\";");
		fputs($fis, " \$fields[\$idx]->help=\"".${"HELP$i"}."\";");
		fputs($fis, " \$fields[\$idx]->funcnew=\"".${"FUNCNEW$i"}."\";");
		fputs($fis, " \$fields[\$idx]->funclink=\"".${"FUNCLINK$i"}."\";");
		fputs($fis, " \$fields[\$idx]->overlap=\"".${"OVERLAP$i"}."\";");
		fputs($fis, " \$fields[\$idx]->browsable=".(${"BROWSABLE$i"} != "" ? "true" : "false").";");
		fputs($fis, " \$fields[\$idx]->browsedit=".(${"BROWSEDIT$i"} != "" ? "true" : "false").";");
		fputs($fis, " \$fields[\$idx]->fieldedit=".(${"FIELDEDIT$i"} != "" ? "true" : "false").";");
		fputs($fis, " \$fields[\$idx]->readonly=".(${"READONLY$i"} != "" ? "true" : "false").";");
		fputs($fis, " \$fields[\$idx]->nonew=".(${"NONEW$i"} != "" ? "true" : "false").";");
		fputs($fis, " \$fields[\$idx]->noedit=".(${"NOEDIT$i"} != "" ? "true" : "false").";");
		fputs($fis, " \$fields[\$idx]->nodetail=".(${"NODETAIL$i"} != "" ? "true" : "false").";");
		fputs($fis, " \$fields[\$idx]->noinsert=".(${"NOINSERT$i"} != "" ? "true" : "false").";");
		fputs($fis, " \$fields[\$idx]->noupdate=".(${"NOUPDATE$i"} != "" ? "true" : "false").";");
		fputs($fis, " \$fields[\$idx]->noprint=".(${"NOPRINT$i"} != "" ? "true" : "false").";");
		fputs($fis, " \$fields[\$idx]->browsesearch=".(${"BROWSESEARCH$i"} != "" ? "true" : "false").";");
		fputs($fis, " \$fields[\$idx]->searchable=".(${"SEARCHABLE$i"} != "" ? "true" : "false").";");
		fputs($fis, " \$fields[\$idx]->orderby=".(${"ORDERBY$i"} != "" ? "true" : "false").";");
		fputs($fis, " \$fields[\$idx]->canbenull=".(${"CANBENULL$i"} != "" ? "true" : "false").";");
		fputs($fis, " \$fields[\$idx]->V_colsedit=\"".${"V_COLSEDIT$i"}."\";");
		fputs($fis, " \$fields[\$idx]->V_colsdetail=\"".${"V_COLSDETAIL$i"}."\";");
		fputs($fis, " \$fields[\$idx]->coledit=\"".${"COLEDIT$i"}."\";");
		fputs($fis, " \$fields[\$idx]->rowedit=\"".${"ROWEDIT$i"}."\";");
		fputs($fis, " \$fields[\$idx]->coldetail=\"".${"COLDETAIL$i"}."\";");
		fputs($fis, " \$fields[\$idx]->rowdetail=\"".${"ROWDETAIL$i"}."\";\n");
		$realnumf++;
		fputs($fis, "\t\$idx++;\n");
		$idx++;
		$j++;
		$i=$numf+4;
	  }
	}
  }
  if ($i<($numf+4)) {
    for ($i = ($puntero+1); $i < $numf; $i++) { 
    	if (${"ORDERBY$i"}==" ") ${"ORDERBY$i"}=${"NAME$i"};
	if ($orderby=="") {
    	    if (${"ORDERBY$i"} !="") $orderby=${"ORDERBY$i"};
	}
	if (${"FIELDORDER$i"} == "") {
	  if (${"NAME$i"} != "") {
		$puntero=$i;
		fputs($fis, "\n");
		fputs($fis, "\t\$fields[\$idx] = new finfo;\n");

                fputs($fis, "\t\$findex[\"".${"NAME$i"}."\"]=\$idx;");
                fputs($fis, " \$fields[\$idx]->name=\"".${"NAME$i"}."\";");
                if (substr(${"TITLE$i"},0,1)=="_") { fputs($fis, " \$fields[\$idx]->title = ".${"TITLE$i"}.";");
                } else { fputs($fis, " \$fields[\$idx]->title=\"".${"TITLE$i"}."\";"); }
                fputs($fis, " \$fields[\$idx]->length=\"".(${"LENGTH$i"} != "" ? ${"LENGTH$i"}: "0")."\";");
                fputs($fis, " \$fields[\$idx]->ilength=\"".(${"ILENGTH$i"} != "" ? ${"ILENGTH$i"} : "0")."\";");
                fputs($fis, " \$fields[\$idx]->type=\"".${"TYPE$i"}."\";");
                fputs($fis, " \$fields[\$idx]->dtype=\"".${"DTYPE$i"}."\";");
                fputs($fis, " \$fields[\$idx]->extra=\"".${"EXTRA$i"}."\";");
                fputs($fis, " \$fields[\$idx]->vdefault=\"".${"VDEFAULT$i"}."\";");
		fputs($fis, " \$fields[\$idx]->lista=\"".${"LISTA$i"}."\";");
                fputs($fis, " \$fields[\$idx]->vonfocus=\"".${"VONFOCUS$i"}."\";");
                fputs($fis, " \$fields[\$idx]->vonchange=\"".${"VONCHANGE$i"}."\";");
                fputs($fis, " \$fields[\$idx]->vonblur=\"".${"VONBLUR$i"}."\";");
                fputs($fis, " \$fields[\$idx]->column=\"".${"COLUMN$i"}."\";");
                fputs($fis, " \$fields[\$idx]->help=\"".${"HELP$i"}."\";");
                fputs($fis, " \$fields[\$idx]->funcnew=\"".${"FUNCNEW$i"}."\";");
                fputs($fis, " \$fields[\$idx]->funclink=\"".${"FUNCLINK$i"}."\";");
                fputs($fis, " \$fields[\$idx]->overlap=\"".${"OVERLAP$i"}."\";");
                fputs($fis, " \$fields[\$idx]->browsable=".(${"BROWSABLE$i"} != "" ? "true" : "false").";");
                fputs($fis, " \$fields[\$idx]->browsedit=".(${"BROWSEDIT$i"} != "" ? "true" : "false").";");
                fputs($fis, " \$fields[\$idx]->fieldedit=".(${"FIELDEDIT$i"} != "" ? "true" : "false").";");
                fputs($fis, " \$fields[\$idx]->readonly=".(${"READONLY$i"} != "" ? "true" : "false").";");
                fputs($fis, " \$fields[\$idx]->nonew=".(${"NONEW$i"} != "" ? "true" : "false").";");
                fputs($fis, " \$fields[\$idx]->noedit=".(${"NOEDIT$i"} != "" ? "true" : "false").";");
                fputs($fis, " \$fields[\$idx]->nodetail=".(${"NODETAIL$i"} != "" ? "true" : "false").";");
                fputs($fis, " \$fields[\$idx]->noinsert=".(${"NOINSERT$i"} != "" ? "true" : "false").";");
                fputs($fis, " \$fields[\$idx]->noupdate=".(${"NOUPDATE$i"} != "" ? "true" : "false").";");
                fputs($fis, " \$fields[\$idx]->noprint=".(${"NOPRINT$i"} != "" ? "true" : "false").";");
                fputs($fis, " \$fields[\$idx]->browsesearch=".(${"BROWSESEARCH$i"} != "" ? "true" : "false").";");
                fputs($fis, " \$fields[\$idx]->searchable=".(${"SEARCHABLE$i"} != "" ? "true" : "false").";");
                fputs($fis, " \$fields[\$idx]->orderby=".(${"ORDERBY$i"} != "" ? "true" : "false").";");
                fputs($fis, " \$fields[\$idx]->canbenull=".(${"CANBENULL$i"} != "" ? "true" : "false").";");
                fputs($fis, " \$fields[\$idx]->V_colsedit=\"".${"V_COLSEDIT$i"}."\";");
                fputs($fis, " \$fields[\$idx]->V_colsdetail=\"".${"V_COLSDETAIL$i"}."\";");
                fputs($fis, " \$fields[\$idx]->coledit=\"".${"COLEDIT$i"}."\";");
                fputs($fis, " \$fields[\$idx]->rowedit=\"".${"ROWEDIT$i"}."\";");
                fputs($fis, " \$fields[\$idx]->coldetail=\"".${"COLDETAIL$i"}."\";");
                fputs($fis, " \$fields[\$idx]->rowdetail=\"".${"ROWDETAIL$i"}."\";\n");
		$realnumf++;
		fputs($fis, "\t\$idx++;\n");
		$idx++;
		$j++;
		$i=$numf+1;
	  }
	}
    }
  }
}

fputs($fis,"\n\t\$numf = \$idx;\n");
fputs($fis,"\t\$defaultfilter = \"$defaultfilter\";\n");
fputs($fis,"\n");

fputs($f, " include(\"modules/".$GENUTIL_S."\");\n");
fputs($f,"\n \$TMP_module_name = basename(dirname(__FILE__));\n");
fputs($f," \$TMP_filelang = get_lang(\$TMP_module_name);\n");
fputs($f," if (\$TMP_filelang!=\"\") include(\$TMP_filelang);\n\n");

fputs($f, " include(\"modules/".$SQL_S."\");\n");
fputs($f, " include(\"modules/".$DELETE_S."\");\n");
fputs($f, " include(\"modules/".$UPDATE_S."\");\n");
fputs($f, " include(\"modules/".$INSERT_S."\");\n");

fputs($f, " include(\"modules/".$themedir."/".$themefile."\");\n");

fputs($f, " include(\"modules/".$JS_S."\");\n");
fputs($f, " include(\"modules/".$HEADER_S."\");\n");
fputs($f, " include(\"modules/".$SQL_S."\");\n");
fputs($f, " include(\"modules/".$MENU_S."\");\n");
fputs($f, " include(\"modules/".$BACKUP_S."\");\n");
fputs($f, " include(\"modules/".$BROWSE_S."\");\n");
fputs($f, " include(\"modules/".$DRECORD_S."\");\n");
fputs($f, " include(\"modules/".$EFORM_S."\");\n");
fputs($f, " include(\"modules/".$SFORM_S."\");\n");
fputs($f, " include(\"modules/".$ERROR_S."\");\n");
fputs($f, " include(\"modules/".$FOOTER_S."\");\n");
fputs($f,"\n if (\$func!=\"search_js\" && \$func!=\"none\") CloseTable();\n include_once (\"footer.php\"); \n?>\n");
fclose($f);
chmod($modulesdir."/".$prefix.".php", $cmask); 
?>
	<?=_DEF_NLSModule." "._DEF_NLSSaved?> <A TARGET=_blank HREF="<? echo "index.php?$SESSION_SID&V_dir=$TMP_modulesdir&V_mod=".$prefix.""; ?>"><B><? echo "$modulesdir/".$prefix.".php"; ?></B></A><BR>
	<?=_DEF_NLSProject." "._DEF_NLSSaved?> <a href="index.php?<?=$SESSION_SID?>V_dir=<?=$V_dir?>&V_mod=<?=$V_mod?>&V_submod=genform&modulesdir=<?=$TMP_modulesdir?>&project_file=<?=$prefix?>.prj.php"><? echo "$modulesdir/$prefix.prj.php"; ?></a><BR>
	<BR>
	<a href="index.php?V_dir=admin&V_mod=modulos&func=new<?=$SESSION_SID?>">Activar <?=_DEF_NLSModule." '".$prefix?>'</a><BR><BR>

<?
//*****************************************************************************

CloseTable();
include_once ("footer.php");

?>
