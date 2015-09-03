<?
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: ../../index.php");
    die();
}

if ($V_submod=="genmod") {
        include_once("modules/".$V_dir."/genmod.php");
        return;
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
		"plistdbm" => "Pick-List+DB(M)",
		"plistdbtree" => "Pick-List+DB Tree",
		"plistdbmtree" => "Pick-List+DB(M) Tree",
		"popupdb" => "Pop Up+DB",
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
		include($DIRBASE.$modulesdir."/$project_file");
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

	echo " <A HREF=\"#\" onClick=\"window.open('modules/$V_dir/help.$language.php','help','toolbars=no,menubar=yes,scrollbars=yes,resizable=yes,width=700,height=500');\"><img border=0 src='images/info.gif'> "._DEF_NLSHelp."</A></b> | ";
	if ($addtablefields=="") echo "<A HREF='".$REQUEST_URI."&addtablefields=X$SESSION_SID'>Agrega todos los campos de la tabla</A> ";
	else echo "<A HREF='".$PHP_SELF."?V_dir=$V_dir&V_mod=$V_mod&V_submod=$V_submod&modulesdir=$modulesdir&project_file=$project_file$SESSION_SID'>No agrega todos los campos de la tabla</A> ";

 
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
		if ($dbname!="") $db -> Database = $dbname;
		else $db -> Database = _DEF_dbname;
		$db -> Type = $dbtype;
		$db -> Debug = false;
		$tableinfo = $db->metadata($tablename);
		include_once("modules/".$V_dir."/phplibsup.php");
		$RAD_dbinfo = $$dbtype;
		for ($i = 0; $i < count($tableinfo); $i++) { 
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
		    }
	  	}
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
			<INPUT TYPE="HIDDEN" NAME="hostname" VALUE="<? echo $hostname; ?>">
			<INPUT TYPE="HIDDEN" NAME="modulesdir" VALUE="<? echo $modulesdir; ?>">
			<INPUT TYPE="HIDDEN" NAME="dbusername" VALUE="<? echo $dbusername; ?>">
			<INPUT TYPE="HIDDEN" NAME="dbpassword" VALUE="<? echo $dbpassword; ?>">
			<INPUT TYPE="HIDDEN" NAME="GEN_dbtype" VALUE="<? echo $dbtype; ?>">
			<INPUT TYPE="HIDDEN" NAME="numf" VALUE="<? echo $numf; ?>">
			<CENTER>
				<TABLE cellspacing=0 cellpadding=0>
						    <INPUT TYPE=HIDDEN NAME=coment VALUE='<?urlencode($coment)?>'>
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
	if ($title =="") $title=ucwords($filename);
//	echo "</SELECT></TD></TR>";
	echo "<INPUT TYPE=HIDDEN NAME='GEN_themefile' VALUE='$tfile'>";

//	echo "<TR><TD align=right valign=top><B>"._DEF_NLSDatabaseName." : </B></TD>";
//	echo "<TD><INPUT NAME='V0_dbname' SIZE='15' VALUE='".$dbname."'></TD></TR>";
	echo "<INPUT NAME='V0_dbname' TYPE=HIDDEN VALUE=''></TD></TR>";
?>
						<INPUT TYPE=HIDDEN NAME="tablename" SIZE="25" VALUE="<? echo $tablename; ?>">
					<TR>
						<TD align=right valign=top>
						<B><?=_DEF_NLSOption?> : </B></TD>
						<TD nowrap>
		<INPUT TYPE="HIDDEN" NAME="menubackup" <? if ($menubackup) echo "VALUE='x'"; ?>> 
		<INPUT TYPE="CHECKBOX" NAME="menubrowse" <? if ($menubrowse || !isset($menubrowse)) echo "CHECKED"; ?> VALUE="x">Browse. &nbsp; 
		<INPUT TYPE="CHECKBOX" NAME="menudetail" <? if ($menudetail || !isset($menudetail)) echo "CHECKED"; ?> VALUE="x">Detail. &nbsp; 
		<INPUT TYPE="CHECKBOX" NAME="menusearch" <? if ($menusearch || !isset($menusearch)) echo "CHECKED"; ?> VALUE="x">Search. &nbsp; 
		<INPUT TYPE="CHECKBOX" NAME="menuprint" <? if ($menuprint || !isset($menuprint)) echo "CHECKED"; ?> VALUE="x">Print. &nbsp; 
		<INPUT TYPE="CHECKBOX" NAME="menunew" <? if ($menunew || !isset($menunew)) echo "CHECKED"; ?> VALUE="x">New. &nbsp; 
		<INPUT TYPE="CHECKBOX" NAME="menuedit" <? if ($menuedit || !isset($menuedit)) echo "CHECKED"; ?> VALUE="x">Edit. &nbsp; 
		<INPUT TYPE="CHECKBOX" NAME="menudelete" <? if ($menudelete || !isset($menudelete)) echo "CHECKED"; ?> VALUE="x">Delete. &nbsp; 
		<INPUT TYPE="CHECKBOX" NAME="menujump" <? if ($menujump) echo "CHECKED"; ?> VALUE="x">Jump. &nbsp; 
		<INPUT TYPE="CHECKBOX" NAME="menusend" <? if ($menusend) echo "CHECKED"; ?> VALUE="x">Send. &nbsp; 
		<INPUT TYPE="CHECKBOX" NAME="menubletter" <? if ($menubletter) echo "CHECKED"; ?> VALUE="x">Letr. &nbsp; 
		<INPUT TYPE="CHECKBOX" NAME="menureport" <? if ($menureport || !isset($menureport)) echo "CHECKED"; ?> VALUE="x">Report. &nbsp; 
		<INPUT TYPE="CHECKBOX" NAME="menuoff" <? if ($menuoff!="") echo "CHECKED"; ?> VALUE="x">Menu OFF. &nbsp;
		<INPUT TYPE="CHECKBOX" NAME="menutree" <? if ($menutree) echo "CHECKED"; ?> VALUE="x">Tree.  
		<INPUT TYPE="HIDDEN" NAME="browsetreefield" VALUE="<?=$browsetreefield?>"> 
		<INPUT TYPE="HIDDEN" NAME="browsetreefieldparent" VALUE="<?=$browsetreefieldparent?>"> 
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
						<INPUT NAME=defaultfilter SIZE=50 VALUE="<? echo $defaultfilter; ?>"> / <b>ORDER BY : </b>
						<INPUT NAME=orderby SIZE=30 VALUE="<? echo $orderby; ?>">
						</TD>
					</TR>
					<TR>
						<TD align=right valign=top>
						<B><u><?=_DEF_NLSPage?></u> : </B></TD>
						<TD><INPUT NAME="prefix" SIZE='25' VALUE="<? echo $filename; ?>"> </TD>
					</TR>
					<TR>
						<TD align=right valign=top>
						<B><?=_DEF_NLSTitle?> : </B></TD>
						<TD><INPUT NAME="GEN_title" SIZE="80" VALUE="<? echo $title; ?>"></TD>
					</TR>
						<INPUT TYPE=HIDDEN NAME="modulesdir" SIZE="15" VALUE="<? echo $modulesdir; ?>"></TD>
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
						<INPUT TYPE=HIDDEN NAME=idnames SIZE=50 VALUE='<? echo $idnames; ?>'>
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
		</SELECT></TD>
					</TR>
						<INPUT TYPE=HIDDEN NAME="logsql" VALUE="x"><!INPUT TYPE="CHECKBOX" NAME="logsql" <? //if ($logsql) echo "CHECKED"; ?> VALUE="x"><INPUT TYPE="HIDDEN" NAME="verbose_queries" VALUE="<?=$verbose_queries?>">
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
	    if ($QUERY[$i] != "") ${"QUERY$i"} = $QUERY[$i];
	    if ($PARMLISTSFF[$i] != "") ${"PARMLISTSFF$i"} = $PARMLISTSFF[$i];
	    if ($SHOWLISTSFF[$i] != "") ${"SHOWLISTSFF$i"} = $SHOWLISTSFF[$i];
	    if ($RETURNLISTSFF[$i] != "") ${"RETURNLISTSFF$i"} = $RETURNLISTSFF[$i];
	    if ($VDEFAULT[$i] != "") ${"VDEFAULT$i"} = $VDEFAULT[$i];
	    if ($VONFOCUS[$i] != "") ${"VONFOCUS$i"} = $VONFOCUS[$i];
	    if ($VONCHANGE[$i] != "") ${"VONCHANGE$i"} = $VONCHANGE[$i];
	    if ($VONBLUR[$i] != "") ${"VONBLUR$i"} = $VONBLUR[$i];
	    if ($COLUMN[$i] != "") ${"COLUMN$i"} = $COLUMN[$i];
	    if ($HELP[$i] != "") ${"HELP$i"} = $HELP[$i];
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
	if ($numline%10 == 0) {
?>
					<TR>
						<TD></TD><TH VALIGN="BOTTOM"><?=_DEF_NLSOrder?></TH>
						<TH VALIGN="BOTTOM"><?=_DEF_NLSTitle?></TH>
						<TH VALIGN="BOTTOM"><?=_DEF_NLSField?></TH>
						<TH VALIGN="BOTTOM"><?=_DEF_NLSMax."<br>"._DEF_NLSLength?></TH>
						<TH VALIGN="BOTTOM"><?=_DEF_NLSInput."<br>"._DEF_NLSLength?></TH>
						<TD VALIGN="BOTTOM" ALIGN="CENTER"><IMG SRC="modules/<?=$V_dir?>/null.gif" ALT="Puesd estar vacio?"></TD>
						<TH VALIGN="BOTTOM"><?=_DEF_NLSField?><BR><?=_DEF_NLSType?></TH>
						<TH VALIGN="BOTTOM"><?=_DEF_NLSInput?><BR><?=_DEF_NLSType?></TH>
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
						<TH VALIGN="BOTTOM"><?=_DEF_NLSHelp?></TH>
					</TR>
<?
	}
	$numline++;
	$ucnm = ucwords(strtr(${"NAME$i"},"_-.","   "));
?>
					<TR>
						<TD><a href='javascript:cpFieldsForm(<? echo "\"$i\"";?>);showLayer("field");'>&nbsp;</a></TD>
						<TD nowrap align=right><? echo ($i+1); ?> <INPUT ID="FIELDORDER<? echo $i; ?>" NAME="FIELDORDER<? echo $i; ?>" SIZE="2"></TD>
						<TD>
							<INPUT ID="TITLE<? echo $i; ?>" NAME="TITLE<? echo $i; ?>" SIZE="8" 
							  VALUE="<? echo (${"TITLE$i"} != "" ? ${"TITLE$i"} : 
							  	$ucnm); ?>">
						</TD>
						<TD>
							<INPUT ID="NAME<? echo $i; ?>" NAME="NAME<? echo $i; ?>" SIZE="6"
								VALUE="<? echo ${"NAME$i"}; ?>">
						</TD>
						<TD>
							<INPUT ID="LENGTH<? echo $i; ?>" NAME="LENGTH<? echo $i; ?>" SIZE="2"  
								VALUE="<? echo ${"LENGTH$i"}; ?>">
						</TD>
						<TD>
							<INPUT ID="ILENGTH<? echo $i; ?>" NAME="ILENGTH<? echo $i; ?>" SIZE="2" 
								VALUE="<? if (${"LENGTH$i"} != "") {
									echo (${"ILENGTH$i"} != "" ? ${"ILENGTH$i"} :
										min((int)${"LENGTH$i"},$max_inputlength));
								} ?>">
						</TD>
							<INPUT TYPE="HIDDEN" ID="ORDERBY<? echo $i; ?>" NAME="ORDERBY<? echo $i; ?>" VALUE="<? if (${"ORDERBY$i"} != "") echo ${"NAME$i"}; ?>">
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
    array2select($dtypes_arr,${"DTYPE$i"},8);
?>
							</SELECT>
						</TD>
							<INPUT TYPE=HIDDEN WIDTH="35" SIZE="20" ID="EXTRA<? echo $i; ?>" NAME="EXTRA<? echo $i; ?>"
							 VALUE="<? echo ${"EXTRA$i"}; ?>">
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
							<INPUT WIDTH="35" SIZE="10" ID="OVERLAP<? echo $i; ?>" NAME="OVERLAP<? echo $i; ?>"
							 VALUE="<? echo ${"OVERLAP$i"}; ?>">
						</TD>
						<TD BGCOLOR="#EEFFAA" ALIGN="CENTER">
							<INPUT WIDTH="35" SIZE="6" ID="VDEFAULT<? echo $i; ?>" NAME="VDEFAULT<? echo $i; ?>"
							 VALUE="<? echo ${"VDEFAULT$i"}; ?>">
						</TD>
							<INPUT TYPE=HIDDEN WIDTH="35" SIZE="6" ID="VONFOCUS<? echo $i; ?>" NAME="VONFOCUS<? echo $i; ?>"
							<INPUT TYPE=HIDDEN WIDTH="35" SIZE="6" ID="VONCHANGE<? echo $i; ?>" NAME="VONCHANGE<? echo $i; ?>"
							 VALUE="<? echo ${"VONCHANGE$i"}; ?>">
 							<INPUT TYPE=HIDDEN WIDTH="35" SIZE="6" ID="VONBLUR<? echo $i; ?>" NAME="VONBLUR<? echo $i; ?>"
 							 VALUE="<? echo ${"VONBLUR$i"}; ?>">
						<TD BGCOLOR="#EEFFAA" ALIGN="CENTER">
							<INPUT SIZE="20" SIZE="6" ID="HELP<? echo $i; ?>" NAME="HELP<? echo $i; ?>"
							 VALUE="<? echo ${"HELP$i"}; ?>">
							<INPUT TYPE=HIDDEN ID="DESCRIPTION<? echo $i; ?>" NAME="DESCRIPTION<? echo $i; ?>"
							 VALUE="<? echo ${"DESCRIPTION$i"}; ?>">
						</TD>
							<INPUT TYPE=HIDDEN WIDTH="35" SIZE="6" ID="FUNCNEW<? echo $i; ?>" NAME="FUNCNEW<? echo $i; ?>"
							 VALUE="<? echo ${"FUNCNEW$i"}; ?>">
							<INPUT TYPE=HIDDEN WIDTH="35" SIZE="6" ID="FUNCLINK<? echo $i; ?>" NAME="FUNCLINK<? echo $i; ?>"
							 VALUE="<? echo ${"FUNCLINK$i"}; ?>">
							<INPUT TYPE=HIDDEN WIDTH="35" SIZE="10" ID="QUERY<? echo $i; ?>" NAME="QUERY<? echo $i; ?>"
							 VALUE="<? echo ${"QUERY$i"}; ?>">
							<INPUT WIDTH="35" SIZE="3" ID="COLEDIT<? echo $i; ?>" NAME="COLEDIT<? echo $i; ?>"
							 TYPE=HIDDEN VALUE="<? echo ${"COLEDIT$i"}; ?>">
							<INPUT WIDTH="35" SIZE="3" ID="V_COLSEDIT<? echo $i; ?>" NAME="V_COLSEDIT<? echo $i; ?>"
							 TYPE=HIDDEN VALUE="<? echo ${"V_COLSEDIT$i"}; ?>">
							<INPUT TYPE=HIDDEN WIDTH="35" SIZE="5" ID="PARMLISTSFF<? echo $i; ?>" NAME="PARMLISTSFF<? echo $i; ?>"
							 VALUE="<? echo ${"PARMLISTSFF$i"}; ?>">
							<INPUT WIDTH="35" SIZE="3" ID="ROWEDIT<? echo $i; ?>" NAME="ROWEDIT<? echo $i; ?>"
							 TYPE=HIDDEN VALUE="<? echo ${"ROWEDIT$i"}; ?>">
							<INPUT TYPE=HIDDEN WIDTH="35" SIZE="5" ID="SHOWLISTSFF<? echo $i; ?>" NAME="SHOWLISTSFF<? echo $i; ?>"
							 VALUE="<? echo ${"SHOWLISTSFF$i"}; ?>">
							<INPUT WIDTH="35" SIZE="3" ID="COLDETAIL<? echo $i; ?>" NAME="COLDETAIL<? echo $i; ?>"
							 TYPE=HIDDEN VALUE="<? echo ${"COLDETAIL$i"}; ?>">
							<INPUT WIDTH="35" SIZE="3" ID="V_COLSDETAIL<? echo $i; ?>" NAME="V_COLSDETAIL<? echo $i; ?>"
							 TYPE=HIDDEN VALUE="<? echo ${"V_COLSDETAIL$i"}; ?>">
							<INPUT TYPE=HIDDEN WIDTH="35" SIZE="5" ID="RETURNLISTSFF<? echo $i; ?>" NAME="RETURNLISTSFF<? echo $i; ?>"
							 VALUE="<? echo ${"RETURNLISTSFF$i"}; ?>">
							<INPUT WIDTH="35" SIZE="3" ID="ROWDETAIL<? echo $i; ?>" NAME="ROWDETAIL<? echo $i; ?>"
							 TYPE=HIDDEN VALUE="<? echo ${"ROWDETAIL$i"}; ?>">
					</TR>
<?
	}
?>
				</TABLE><hr noshade size=1>
				<TABLE cellspacing=0 cellpadding=0>
<?		
	while ($TMP_file = each($files)) {
?>
							<INPUT TYPE=HIDDEN NAME="<? echo $TMP_file["key"]."_S"; ?>"
								VALUE="<? 
echo (${$TMP_file["key"]."_S"} != "" ? ${$TMP_file["key"]."_S"} : (${"D_".$TMP_file["key"]."_S"} != "" ? ${"D_".$TMP_file["key"]."_S"} : $TMP_file["value"]));
?>">
							<INPUT TYPE=HIDDEN NAME="<? echo $TMP_file["key"]."_D"; ?>"
								VALUE="<? 
echo (${$TMP_file["key"]."_D"} != "" ? ${$TMP_file["key"]."_D"} : (${"D_".$TMP_file["key"]."_D"} != "" ? ${"D_".$TMP_file["key"]."_D"} : basename($TMP_file["value"])));
?>">
							<INPUT TYPE=HIDDEN NAME="<? echo $TMP_file["key"]."_OT"; ?>" VALUE='<?
echo ${$TMP_file["key"]."_OT"} != "" ? ${$TMP_file["key"]."_OT"} : ${"D_".$TMP_file["key"]."_OT"}; 
echo "'>\n";
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
		</FORM>


<script>
var numvar;
var nameFields=new Array ("FIELDORDER", "TITLE", "NAME", "LENGTH", "ILENGTH", "TYPE", "DTYPE", "ORDERBY", "CANBENULL", "EXTRA", "OVERLAP", "SEARCHABLE", "VDEFAULT", "BROWSABLE", "VONFOCUS", "VONCHANGE", "VONBLUR", "BROWSEDIT", "HELP", "DESCRIPTION", "FIELDEDIT", "FUNCNEW", "FUNCLINK", "READONLY", "QUERY", "PARMLISTSFF", "NONEW", "SHOWLISTSFF", "NODETAIL", "RETURNLISTSFF", "NOEDIT", "COLEDIT", "NOINSERT", "ROWEDIT", "NOUPDATE", "COLDETAIL", "NOPRINT", "ROWDETAIL", "BROWSESEARCH", "V_COLSEDIT", "V_COLSDETAIL");

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
    <TD align=right><a href='javascript:cpFieldsLayer();hideLayer("field");'><img src='images/save_as.gif' border=0> Guardar</A> | 
    <a href='javascript:hideLayer("field");'><img src='images/logout.gif' border=0> Cerrar</A> </TD></TR>

<TR><TD align=right>Orden:</TD><TD><INPUT ID="FIELDORDER" NAME="FIELDORDER" SIZE="3"></TD>
    <TD colspan=2></TD></TR>

<TR><TD align=right>Titulo Campo:</TD><TD><INPUT ID="TITLE" NAME="TITLE" SIZE="35"></TD>
    <TD align=right>Nombre Campo BD:</TD><TD><INPUT ID="NAME" NAME="NAME" SIZE="25"></TD></TR>

<TR><TD align=right>Long. Campo:</TD><TD><INPUT ID="LENGTH" NAME="LENGTH" SIZE="5"></TD>
    <TD align=right>Long. Entrada:</TD><TD><INPUT ID="ILENGTH" NAME="ILENGTH" SIZE="5"></TD></TR>

<TR><TD align=right>Tipo Campo BD:</TD><TD><SELECT ID="TYPE" NAME="TYPE">
<? $type = ${"TYPE$i"}; $dtype = $type2dtype_arr[$type]; array2select($types_arr,$type,8); ?>
    </SELECT></TD>
    <TD align=right>Permitir Ordenar:</TD><TD><INPUT TYPE="CHECKBOX" ID="ORDERBY" NAME="ORDERBY"></TD></TR>

<TR><TD align=right>Campo Formulario:</TD><TD><SELECT ID="DTYPE" NAME="DTYPE">
<? array2select($dtypes_arr,${"DTYPE$i"},8); ?>
    </SELECT></TD>
    <TD align=right>Permitir Nulo:</TD><TD><INPUT TYPE="CHECKBOX" ID="CANBENULL" NAME="CANBENULL"></TD></TR>

<TR><TD align=right>Datos Extra:</TD><TD COLSPAN=3><INPUT SIZE="100" ID="EXTRA" NAME="EXTRA"></TD></TR>

<TR><TD align=right>Pesta&ntilde;a:</TD><TD><INPUT SIZE="40" ID="OVERLAP" NAME="OVERLAP"></TD>
    <TD align=right>Permite Busquedas:</TD><TD><INPUT TYPE="CHECKBOX" ID="SEARCHABLE" NAME="SEARCHABLE"></TD></TR>
<TR><TD align=right>Valor por Defecto:</TD><TD><INPUT SIZE="40" ID="VDEFAULT" NAME="VDEFAULT"></TD>
    <TD align=right>Visible en Listado:</TD><TD><INPUT TYPE="CHECKBOX" ID="BROWSABLE" NAME="BROWSABLE"></TD></TR>
<TR><TD align=right>Javascript OnChange:</TD><TD><INPUT SIZE="40" ID="VONCHANGE" NAME="VONCHANGE"></TD>
    <TD align=right>Editable en Listado:</TD><TD><INPUT TYPE="CHECKBOX" ID="BROWSEDIT" NAME="BROWSEDIT"></TD></TR>

<TR><TD align=right>Javascript OnBlur:</TD><TD><INPUT SIZE="40" ID="VONBLUR" NAME="VONBLUR"></TD>
    <TD align=right>Editable en Detalle:</TD><TD><INPUT TYPE="CHECKBOX" ID="FIELDEDIT" NAME="FIELDEDIT"></TD></TR>

<TR><TD align=right>Query:</TD><TD colspan=3><INPUT SIZE="100" ID="QUERY" NAME="QUERY"></TD></TR>

<TR><TD align=right>Modulo para Nuevo:</TD><TD><INPUT SIZE="30" ID="FUNCNEW" NAME="FUNCNEW"></TD>
    <TD align=right>Solo Lectura:</TD><TD><INPUT TYPE="CHECKBOX" ID="READONLY" NAME="READONLY"></TD></TR>

<TR><TD align=right>Modulo para Enlace:</TD><TD><INPUT SIZE="30" ID="FUNCLINK" NAME="FUNCLINK"></TD>
    <TD align=right>No permite Nuevo:</TD><TD><INPUT TYPE="CHECKBOX" ID="NONEW" NAME="NONEW"></TD</TR>

<TR><TD align=right>Parametros a Popup:</TD><TD><INPUT SIZE="40" ID="PARMLISTSFF" NAME="PARMLISTSFF"></TD>
    <TD align=right>No muestra Detalle:</TD><TD><INPUT TYPE="CHECKBOX" ID="NODETAIL" NAME="NODETAIL"></TD></TR>

<TR><TD align=right>Campos a ver en Popup:</TD><TD><INPUT SIZE="40" ID="SHOWLISTSFF" NAME="SHOWLISTSFF"></TD>
    <TD align=right>No permite Editar:</TD><TD><INPUT TYPE="CHECKBOX" ID="NOEDIT" NAME="NOEDIT"></TD></TR>

<TR><TD align=right>Campos Retorno Popup:</TD><TD><INPUT SIZE="40" ID="RETURNLISTSFF" NAME="RETURNLISTSFF"></TD>
    <TD align=right>No se Inserta:</TD><TD><INPUT TYPE="CHECKBOX" ID="NOINSERT" NAME="NOINSERT"></TD></TR>

<TR><TD align=right>Posicion en Editar:</TD><TD>[FILA]<INPUT SIZE="3" ID="ROWEDIT" NAME="ROWEDIT">/[COLUMNA]<INPUT SIZE="3" ID="COLEDIT" NAME="COLEDIT"></TD>
    <TD align=right>No se Modifica:</TD><TD><INPUT TYPE="CHECKBOX" ID="NOUPDATE" NAME="NOUPDATE"></TD></TR>

<TR><TD align=right>Posicion en Detalle:</TD><TD>[FILA]<INPUT SIZE="3" ID="ROWDETAIL" NAME="ROWDETAIL">/[COLUMNA]<INPUT SIZE="3" ID="COLDETAIL" NAME="COLDETAIL"></TD>
    <TD align=right>No se Imprime:</TD><TD><INPUT TYPE="CHECKBOX" ID="NOPRINT" NAME="NOPRINT"></TD></TR>

<TR><TD align=right>Columnas en Editar:</TD><TD><INPUT SIZE="3" ID="V_COLSEDIT" NAME="V_COLSEDIT"></TD>
    <TD align=right>Busqueda en Listado:</TD><TD><INPUT TYPE="CHECKBOX" ID="BROWSESEARCH" NAME="BROWSESEARCH"></TD></TR>

<TR><TD align=right>Columnas en Detalle:</TD><TD><INPUT SIZE="3" ID="V_COLSDETAIL" NAME="V_COLSDETAIL"></TD>
    <TD align=right>Texto de Ayuda:</TD><TD><INPUT SIZE="40" ID="HELP" NAME="HELP"></TD></TR>
<TR><TD align=right valign=top>Descripcion:</TD><TD COLSPAN=3><TEXTAREA COLS=120 ROWS=6 ID="DESCRIPTION" NAME="DESCRIPTION"></TEXTAREA></TD></TR>

</table>
</div>

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
?>
