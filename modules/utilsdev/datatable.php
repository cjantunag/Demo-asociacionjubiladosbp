<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: ../../index.php");
    die();
}

include_once("header.php");
global $dbname, $RAD_dbi, $PHPSESSID;

OpenTable();
echo "<table cellpadding=0 cellspacing=0 border=0>";

if ($op=="" && $defaultop!="") $op=$defaultop;

if ($_REQUEST["data"]!="") $_REQUEST["data"]=str_replace("'","`",$_REQUEST["data"]);

if ($op=="") {
	echo "<tr><td width=10%></td><td><br><li><a href='".$PHP_SELF."?V_dir=$V_dir&V_mod=$V_mod&op=backupAPP".$URLROI.$SESSION_SID."'>Copia de Aplicacion y Datos (BACKUP)</a></td></tr>";
	echo "<tr><td width=10%></td><td><br><li><a href='".$PHP_SELF."?V_dir=$V_dir&V_mod=$V_mod&op=datatable".$URLROI.$SESSION_SID."'>Datos de Tablas</a></td></tr>"; 
	echo "<tr><td></td><td><br><li><a href='".$PHP_SELF."?V_dir=$V_dir&V_mod=$V_mod&op=backupdb".$URLROI.$SESSION_SID."'>Duplicado de Base de Datos</a><br>"; 
	$TMP_cont=0;
	$TMP_result=sql_query("SHOW DATABASES", $RAD_dbi);
	while($TMP_row = sql_fetch_array($TMP_result, $RAD_dbi)) {
		if (substr($TMP_row[0],0,strlen(_DEF_dbname))==_DEF_dbname && $TMP_row[0]!=_DEF_dbname) {
			$TMP_db=substr($TMP_row[0],strlen(_DEF_dbname)+1);
			if (substr($TMP_db,0,1)=="0") $TMP_db="20".$TMP_db;
			$TMP_db=str_replace("x","",$TMP_db);
			$TMP_cont++;
			if (substr($TMP_db,6,2)>0 && substr($TMP_db,4,2)>0 && substr($TMP_db,0,4)>0) {
				$TMP_dbs.=substr($TMP_db,6,2)."-".substr($TMP_db,4,2)."-".substr($TMP_db,0,4)." ".substr($TMP_db,8,2).":".substr($TMP_db,10,2).":".substr($TMP_db,12,2)."<br>";
			} else {
				$TMP_dbs.=$TMP_db."<br>";
			}
		}
	}
	if ($TMP_cont>0) echo "<ul>Base de Datos duplicada en:<ul>".$TMP_dbs."</ul></ul>"; 
	else echo "<br>"; 
	echo "</td></tr>"; 
	echo "<tr><td width=10%></td><td><li><a href='".$PHP_SELF."?V_dir=$V_dir&V_mod=$V_mod&op=consultalog".$URLROI.$SESSION_SID."'>Consulta Operaciones de Datos por Fecha (Log)</a><br><br><br></td></tr>"; 
} else if ($op=="backupdb" && $confirm=="") {
	if ($dbname=="") $dbname=_DEF_dbname;
	echo "<tr><td width=10%></td><td><br><b>Duplicado de Base de Datos</b><br><br></td></tr>"; 
	echo "<FORM ACTION=".$PHP_SELF." METHOD=GET NAME=F><input type=hidden name=PHPSESSID value='$PHPSESSID'>".$FORMROI."<input type=hidden name=V_dir value='$V_dir'><input type=hidden name=V_mod value='$V_mod'><input type=hidden name=op value='backupdb'>"; 
	echo "<tr><td></td><td><center><input type=submit name=confirm value=' Confirmar '>&nbsp;<input type=button onClick='javascript:window.history.back();' value=' Cancelar '></center></td></tr>"; 
} else if ($op=="backupdb" && $confirm!="") {
	if ($dbname=="") $dbname=_DEF_dbname;
	$targetdbname=$dbname."_".substr(date("Y"),2,2).date("md")."x".date("His");
	echo "<tr><td width=10%></td><td><br><b>Duplicando de Base de Datos <u>$dbname</u> como <u>$targetdbname</u></b><br>Espere un momento por favor...<br></td></tr>"; 
	flush();
	RAD_backupDB($dbname,$targetdbname);
	echo "<tr><td width=10%></td><td><br><b>Duplicado Finalizado </b><br><br></td></tr>"; 
	flush();
} else if ($op=="backupAPP") {
	if ($dbname=="") $dbname=_DEF_dbname;
	$targetdbname=$dbname."_".substr(date("Y"),2,2).date("md")."x".date("His");
	echo "<tr><td width=10%></td><td><br><b>Copia de Aplicacion y Datos (BACKUP) <u>$dbname</u></b></td></tr>"; 
	flush();
	if ($confirm=="") {
		echo "<tr><td width=10%></td><td><br><br>Selecciona base de datos:<br><ul>"; 
		echo "<FORM NAME=F ACTION='$PHP_SELF'>";
		foreach($_REQUEST as $TMP_k=>$TMP_v) if ($TMP_k!="confirm" && $TMP_k!="dbname") echo "<input type=hidden name='$TMP_k' value='$TMP_v'>";
		echo "<input checked type=checkbox name=dbname value='"._DEF_dbname."'> "._DEF_dbname."<br>";
		$TMP_result=sql_query("SHOW DATABASES", $RAD_dbi);
		while($TMP_row = sql_fetch_array($TMP_result, $RAD_dbi)) {
		   if (substr($TMP_row[0],0,strlen(_DEF_dbname))==_DEF_dbname && $TMP_row[0]!=_DEF_dbname) {
			echo "<input type=checkbox name=dbname value='".$TMP_row[0]."'> ".$TMP_row[0]."</br>";
		   }
		}
		echo "</ul>"; 
		echo "<br></td></tr>";
		echo "<tr><td></td><td><center><input type=submit name=confirm value=' Confirmar '>&nbsp;<input type=button onClick='javascript:window.history.back();' value=' Cancelar '></center><br>(este proceso puede tardar varios minutos)<br><br></td></tr>"; 
	} else {
		backupAPP($dbname);
		echo "<tr><td width=10%></td><td><br><b>Fichero Copia (BACKUP) generado: <a href='files/tmp/".$dbname.".tgz'>".$dbname.".tgz (pinchar para descargar)</a></b><br><br></td></tr>"; 
	}
	flush();
} else if ($op=="consultalog" && $confirm=="") {
	checkLog();
} else if ($op=="consultalog" && $confirm!="") {
	consultaLog();
} else if ($op=="datatable" || $table=="") {
	checkTable();
} else if ($op!="" && $table!="" && $numfields=="") {
	checkFields();
} else if ($op=="edit" || $op=="new") {
	neweditTable();
} else if ($op=="save" || $op=="savenew") {
	saveTable();
} else {
	echo "******* ".$op.".";
}

echo "</table>";
CloseTable();

include_once("footer.php");
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function backupAPP() {
global $dbname;
	$lista="files/".$dbname." ";
	$fp = opendir(".");
	while ($fn=readdir($fp)) {
		if ($fn=="" || $fn=="." || $fn==".." || $fn=="files") continue;
		if (is_dir($fn)) {
			$lista.=$fn."/* ";
			continue;
		}
		$ext=strtolower(strrchr($fn,"."));
		$A_ext[$ext]="*";
	}
	if (count($A_ext)>0) foreach($A_ext as $ext=>$TMP_nada) if ($ext!="") $lista.="*".$ext." ";

	$fp = opendir("files");
	while ($fn=readdir($fp)) {
		if ($fn=="" || $fn=="." || $fn=="..") continue;
		if (is_dir($fn) && $fn==$dbname) {
			$lista.="files/".$fn." ";
			continue;
		}
		$ext=strtolower(strrchr($fn,"."));
		$A2_ext[$ext]="*";
	}
	if (count($A2_ext)>0) foreach($A2_ext as $ext=>$TMP_nada) if ($ext!="") $lista.="files/*".$ext." ";
	$cmd="mysqldump  --user="._DEF_dbuname." --password="._DEF_dbpass." $dbname > ".$dbname.".sql ; tar cfz files/tmp/".$dbname.".tgz $lista ; rm ".$dbname.".sql ";
	system($cmd);
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function checkLog() {
global $PHP_SELF, $V_dir, $V_mod, $RAD_dbi, $op, $PHPSESSID;

echo "<form name=F action=$PHP_SELF method=post><input type=hidden name=PHPSESSID value='$PHPSESSID'><input type=hidden name=V_dir value='$V_dir'><input type=hidden name=V_mod value='$V_mod'>\n";
echo "<center><table border=0 cellpadding=0 cellspacing=0>\n";

echo "<tr><td colspan=2 align=center><b>Consulta Operaciones de Datos por Fecha (Log)</b></td></tr>\n";

echo "<tr><td colspan=2>&nbsp;</td></tr>\n";

echo "<tr><td align=right> "._DEF_NLSTableName.": </td><td><select name=table><option value=''></option>\n";
$cmdSQL="SHOW TABLES";
$result = sql_query($cmdSQL, $RAD_dbi);
while($row = sql_fetch_array($result,$RAD_dbi)) {
	$TMP_opt.="<option value='".$row[0]."'".$TMP_selected."> ".$row[0]." </option>\n";
}
echo $TMP_opt."</select> (si no selecciona Tabla se mostrarán datos de todos)</td></tr>\n";

echo "<tr><td colspan=2>&nbsp;</td></tr>\n";
echo "<tr><td align=right> Comando: </td><td><select name=comando><option value=''></option>\n";
echo "<option value='INSERT'> INSERT </option>\n<option value='UPDATE'> UPDATE </option>\n<option value='DELETE'> DELETE </option>\n";
echo "</select></td></tr>\n";

if ($fechadesde=="") $fechadesde="0";
if ($fechahasta=="") $fechahasta="0";
$TMP_selectfechadesde=RAD_editfield("fechadesde", "date", 10, 10, "", "", true, $fechadesde, "");
$TMP_selectfechahasta=RAD_editfield("fechahasta", "date", 10, 10, "", "", true, $fechahasta, "");
echo "<tr><td colspan=2>&nbsp;</td></tr>\n";
echo "<tr><td align=right> Fecha Desde: </td><td>".$TMP_selectfechadesde."</td></tr>";
echo "<tr><td colspan=2>&nbsp;</td></tr>\n";
echo "<tr><td align=right> Fecha Hasta: </td><td>".$TMP_selectfechahasta."</td></tr>";

echo "</table><br><input type=submit value='"._DEF_NLSAccept."'>&nbsp;&nbsp;&nbsp;\n";
echo "<input type=button name=cancel value='"._DEF_NLSCancelString."' onClick='javascript:window.close();'>\n";
echo "<input type=hidden name=op value='$op'><input type=hidden name=confirm value='X'><br>";

echo "</center></form>\n";
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function consultaLog() {
global $PHP_SELF, $V_dir, $V_mod, $RAD_dbi, $op, $fechadesde_year, $fechadesde_month, $fechadesde_day, $fechahasta_year, $fechahasta_month, $fechahasta_day, $table, $comando;

	$fechadesde_year=trim($fechadesde_year);
	$fechadesde_month=trim($fechadesde_month);
	$fechadesde_day=trim($fechadesde_day);
	if (strlen($fechadesde_day)<2) $fechadesde_day="0".$fechadesde_day;
	if (strlen($fechadesde_month)<2) $fechadesde_month="0".$fechadesde_month;
	if ($fechadesde_year>1900) $fechadesde=$fechadesde_year."-".$fechadesde_month."-".$fechadesde_day;
	if (trim($fechadesde)!="") $TMP_fechadesdetime=strtotime($fechadesde)-1;

	$fechahasta_year=trim($fechahasta_year);
	$fechahasta_month=trim($fechahasta_month);
	$fechahasta_day=trim($fechahasta_day);
	if (strlen($fechahasta_day)<2) $fechahasta_day="0".$fechahasta_day;
	if (strlen($fechahasta_month)<2) $fechahasta_month="0".$fechahasta_month;
	if ($fechahasta_year>1900) $fechahasta=$fechahasta_year."-".$fechahasta_month."-".$fechahasta_day;
	if (trim($fechahasta)!="") $TMP_fechahastatime=strtotime($fechahasta)+1;

	if (trim($table)!="") {
		echo " Tabla: <b>".trim($table)."</b><br>";
	}
	if (trim($comando)!="") {
		echo " Comando: <b>".trim($comando)."</b><br>";
	}
	if (trim($fechadesde)!="") {
		$TMP_fechadesdetime=strtotime($fechadesde);
		echo " Desde Fecha: <b>".trim($fechadesde)."</b><br>";
	}
	if (trim($fechahasta)!="") {
		$TMP_fechahastatime=strtotime($fechahasta);
		echo " Hasta Fecha <b>".trim($fechahasta)."</b><br>";
	}

	if (!($p=popen("ls -latr "._DEF_DIRBASE."privado/"._DEF_dbname."*log.sql* 2>&1","r"))) {
		echo "No hay ficheros de Log en  "._DEF_DIRBASE."privado/.<br>";
		return;
	} 
	echo "<pre>\n";
	while (!feof($p)) {
		$filename=fgets($p,1000);
		$filename=str_replace("\n", "", $filename);
		$filename=str_replace("\r", "", $filename);
		if ($filename=="") continue;
		if (ereg("No such file or directory",$filename)) {
			echo "<center><b><br>No hay ficheros de Log en  "._DEF_DIRBASE."privado/.</b></center><br>";
			continue;
		}
		$Afilename=explode(" ",$filename);
		if (count($Afilename)>1) $filename=$Afilename[count($Afilename)-1];
		$fp = fopen($filename,"r");
		$file_array=fstat($fp);
		fclose($fp);
		$completo="";
		if ($TMP_fechadesdetime=="" && $TMP_fechahastatime=="") $completo="X";
		if ($TMP_fechadesdetime>0 && $TMP_fechadesdetime<$file_array["atime"] && $TMP_fechahastatime>0 && $TMP_fechahastatime>$file_array["mtime"]) $completo="X";

		if ($TMP_fechadesdetime>0 && $TMP_fechadesdetime>$file_array["mtime"]) continue;
		if ($TMP_fechahastatime>0 && $TMP_fechahastatime<$file_array["atime"]) continue;
		$TMP_FICHERO="# Fichero (".$filename.") con tiempo desde ".$file_array["atime"]." (".date("Y-m-d H:i:s",$file_array["atime"]).") hasta ".$file_array["mtime"]."(".date("Y-m-d H:i:s",$file_array["mtime"]).")";
		$fp = fopen($filename,"r");
		$TMP_comandosql="";
		while (!feof($fp)) {
			//$line = fread($fp,9999);
			$line = fgets($fp);
			if (substr($line,0,2)=="# ") { // comentario de comando
				$line=str_replace("  "," ",$line);
				$A_items=explode(" ",$line); 
				if ($table!="" && $comando!="") {
					if (ereg(" ".$table." ",substr($TMP_comandosql,0,100)) && eregi($comando." ",substr($TMP_comandosql,0,100))) $TMP_encontrado=true;
					else $TMP_encontrado=false;
					//if (ereg(" ".$table." ",$TMP_comandosql) && eregi($comando." ",$TMP_comandosql)) $TMP_encontrado=true;
				}
				if ($table!="" && $comando=="") {
					if (ereg(" ".$table." ",substr($TMP_comandosql,0,100))) $TMP_encontrado=true;
					else $TMP_encontrado=false;
				}
				if ($table=="" && $comando!="") {
					if (eregi($comando." ",substr($TMP_comandosql,0,100))) $TMP_encontrado=true;
					else $TMP_encontrado=false;
				}
				if ($comando=="" && $table=="") { // aqui se podrian comprobar tipos de query, usuarios, ....
					$TMP_encontrado=true;
				}
				if ($TMP_encontrado==true) {
					if ($TMP_FICHERO!="") echo "\n".$TMP_FICHERO."\n";
					$TMP_FICHERO="";
					echo $line;
					echo $TMP_comandosql;
				}
				$TMP_comandosql="";
			} else {
				$TMP_comandosql.=$line;
				$TMP_encontrado=false;
			}
		}
		fclose($fp);
	}
	echo "\n</pre>\n";
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function checkTable() {
global $PHP_SELF, $V_dir, $V_mod, $RAD_dbi, $PHPSESSID;

echo "<form name=F action=$PHP_SELF method=get><input type=hidden name=PHPSESSID value='$PHPSESSID'><input type=hidden name=V_dir value='$V_dir'><input type=hidden name=V_mod value='$V_mod'>\n";
echo "<center><table border=0 cellpadding=0 cellspacing=0>\n";

echo "<tr><td colspan=2 align=center><b>"._DEF_NLSStringEdit." "._DEF_NLSTable." </b></td></tr>\n";

echo "<tr><td colspan=2>&nbsp;</td></tr>\n";

echo "<tr><td align=right> "._DEF_NLSTableName.": </td><td><select name=table>\n";
$cmdSQL="SHOW TABLES";
$result = sql_query($cmdSQL, $RAD_dbi);
while($row = sql_fetch_array($result,$RAD_dbi)) {
	$TMP_opt.="<option value='".$row[0]."'".$TMP_selected."> ".$row[0]." </option>\n";
}
echo $TMP_opt."</select></td></tr>\n";

echo "<tr><td colspan=2>&nbsp;</td></tr>\n";

echo "<tr><td>&nbsp;</td><td><input type=radio name=op value='edit' checked> "._DEF_NLSStringEdit."<br><input type=radio name=op value='new'> "._DEF_NLSStringNew."</td></tr>";

echo "</table><br><input type=submit value='"._DEF_NLSAccept."'>&nbsp;&nbsp;&nbsp;\n";
echo "<input type=button name=cancel value='"._DEF_NLSCancelString."' onClick='javascript:window.close();'>\n";
echo "<br>";

echo "</center></form>\n";
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function checkFields() {
global $PHP_SELF, $V_dir, $V_mod, $RAD_dbi, $op, $table, $PHPSESSID;

echo "<form name=F action=$PHP_SELF method=get><input type=hidden name=PHPSESSID value='$PHPSESSID'><input type=hidden name=V_dir value='$V_dir'><input type=hidden name=V_mod value='$V_mod'>\n";
echo "<center><table border=0 cellpadding=0 cellspacing=0>\n";

if ($op=="edit") echo "<tr><td colspan=2 align=center><b>"._DEF_NLSStringEdit." "._DEF_NLSTable." </b></td></tr><input type=hidden name=op value='$op'>\n";
else if ($op=="new") echo "<tr><td colspan=2 align=center><b>"._DEF_NLSStringNew." "._DEF_NLSTable." </b></td></tr><input type=hidden name=op value='$op'>\n";

echo "<tr><td colspan=2>&nbsp;</td></tr>\n";

echo "<tr><td align=right> "._DEF_NLSTableName.": </td><td><b> $table </b><input type=hidden name=table value='$table'></td></tr>\n";
echo "<tr><td colspan=2>&nbsp;</td></tr>\n";

echo "<tr><td align=right> Enviar: </td><td> <input type=checkbox name=send></td></tr>\n";
echo "<tr><td colspan=2>&nbsp;</td></tr>\n";

echo "<tr><td align=right> Cabecera: </td><td> <input type=checkbox name=head></td></tr>\n";
echo "<tr><td colspan=2>&nbsp;</td></tr>\n";

echo "<tr><td align=right> Separador: </td><td> <input type=text size=3 name=separator value=','></td></tr>\n";
echo "<tr><td colspan=2>&nbsp;</td></tr>\n";


echo "<tr><td align=right valign=top> "._DEF_NLSField."(s): </td><td> \n";
$cmdSQL="DESCRIBE ".$table;
$result = sql_query($cmdSQL, $RAD_dbi);
$i=1;
while($row = sql_fetch_array($result,$RAD_dbi)) {
	$TMP_opt.="<b> $i </b> <input type=checkbox name=field".$i." value='".$row[0]."' checked> <input name=num".$i." type=text size=2 value='$i'> ".$row[0]." (".$row[1].") <br>\n";
	$i++;
}
echo $TMP_opt."</td></tr><input type=hidden name=numfields value='$i'>\n";

echo "<tr><td colspan=2>&nbsp;</td></tr>\n";


echo "</table><br><input type=submit value='"._DEF_NLSAccept."'>&nbsp;&nbsp;&nbsp;\n";
echo "<input type=button name=cancel value='"._DEF_NLSCancelString."' onClick='javascript:window.close();'>\n";
echo "<br>";

echo "</center></form>\n";
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function neweditTable() {
global $PHP_SELF, $V_dir, $V_mod, $RAD_dbi, $op, $table, $numfields, $separator, $PHPSESSID;

echo "<form name=F action=$PHP_SELF method=post><input type=hidden name=PHPSESSID value='$PHPSESSID'><input type=hidden name=V_dir value='$V_dir'><input type=hidden name=V_mod value='$V_mod'>\n";
echo "<center><table border=0 cellpadding=0 cellspacing=0><input type=hidden name=numfields value='$numfields'>\n";

if ($op=="edit") echo "<tr><td colspan=2 align=center><b>"._DEF_NLSStringEdit." "._DEF_NLSTable." </b></td></tr><input type=hidden name=op value='save'>\n";
else if ($op=="new") echo "<tr><td colspan=2 align=center><b>"._DEF_NLSStringNew." "._DEF_NLSTable." </b></td></tr><input type=hidden name=op value='savenew'>\n";

echo "<tr><td colspan=2>&nbsp;</td></tr>\n";

echo "<tr><td align=right> "._DEF_NLSTableName.": </td><td><b> $table </b><input type=hidden name=table value='$table'></td></tr>\n";
echo "<tr><td colspan=2>&nbsp;</td></tr>\n";

echo "<tr><td align=right> Separador: </td><td> <input type=text size=3 name=separator value='$separator'></td></tr>\n";
echo "<tr><td colspan=2>&nbsp;</td></tr>\n";

echo "<tr><td align=right>"._DEF_NLSField."(s): </td><td>&nbsp;</td></tr><tr><td colspan=2>";
$sqlfields=0;

for ($i=1;$i<$numfields;$i++) {
	global ${"field".$i}, ${"num".$i};
	if (${"field".$i}!="") $TMP_FIELD[${"num".$i}]=${"field".$i};
}

for ($i=1;$i<$numfields;$i++) {
	if ($TMP_FIELD[$i]!="") {
		echo "<input type=hidden name=field".$i." value='".$TMP_FIELD[$i]."'>";
		if ($sqlfields>0) {
			$cab.=$separator;
			$sql.=",";
			echo $separator;
		}
		echo $TMP_FIELD[$i];
		$cab.=$TMP_FIELD[$i];
		$sql.=$TMP_FIELD[$i];
		$sqlfields++;
	}
}
if ($_REQUEST['head']=="on") $TMP_data=$cab."\n";
$cmdSQL="SELECT ".$sql." FROM ".$table;
$result = sql_query($cmdSQL, $RAD_dbi);
while($row = sql_fetch_array($result,$RAD_dbi)) {
	for ($i=0;$i<$sqlfields;$i++) {
		if ($i>0)$TMP_data.=$separator;
		$TMP_data.=$row[$i];
	}
	//$TMP_data.=$separator."\n";
        $TMP_data.="\n";
}

echo "</td></tr>";
if ($op=="new") $TMP_data="";

echo "<tr><td colspan=2><textarea nowrap name=data rows=40 cols=120>".$TMP_data."</textarea></td></tr>";


echo "</table><br><input type=submit value='"._DEF_NLSAccept."'>&nbsp;&nbsp;&nbsp;\n";
echo "<input type=button name=cancel value='"._DEF_NLSCancelString."' onClick='javascript:window.close();'>\n";
echo "<br>";

echo "</center></form>\n";

}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function saveTable() {
global $PHP_SELF, $V_dir, $V_mod, $RAD_dbi, $op, $table, $numfields, $_REQUEST, $separator, $confirm, $PHPSESSID;

echo "<center><table border=0 cellpadding=0 cellspacing=0>\n";
echo "<tr><td colspan=2 align=center><b>"._DEF_NLSSave." "._DEF_NLSTable." </b></td></tr>\n";
echo "<tr><td align=right> "._DEF_NLSTableName.": </td><td><b> $table </b></td></tr>\n";

echo "<tr><td align=right>"._DEF_NLSField."(s): </td><td>&nbsp;</td></tr><tr><td colspan=2>";
$sqlfields=0;
for ($i=0;$i<$numfields;$i++) {
	global ${"field".$i};
	if (${"field".$i}!="") {
		if ($sqlfields>0) {
			$cab.=$separator;
			$sql.=",";
			echo $separator;
		}
		echo ${"field".$i};
		$cab.=${"field".$i};
		$sql.=${"field".$i};
		$SQLFIELD[$sqlfields]=${"field".$i};
		$sqlfields++;
	}
}
$TMP_data=$cab."\n";
$cmdSQL="SELECT ".$sql." FROM ".$table;
$result = sql_query($cmdSQL, $RAD_dbi);
while($row = sql_fetch_array($result,$RAD_dbi)) {
	for ($i=0;$i<$sqlfields;$i++) {
		if ($i>0)$TMP_data.=$separator;
		$TMP_data.=$row[$i];
	}
	$TMP_data.=$separator."\n";
}

echo "</td></tr>";

$TMP_data="";
$REGS=explode("\n",$_REQUEST[data]);
if (count($REGS)>0) {
	for ($i=0;$i<count($REGS);$i++) {
		$REGS[$i]=str_replace("\n","",$REGS[$i]);
		$REGS[$i]=str_replace("\r","",$REGS[$i]);
		$VALUES=array();
		$VALUES=explode($separator,$REGS[$i].$separator);
		//if (count($VALUES)>=$sqlfields) {
			$TMP_data.="INSERT INTO ".$table." SET ";
			for ($j=0;$j<$sqlfields;$j++) {
				if ($j>0) $TMP_data.=",";
				$TMP_data.=$SQLFIELD[$j]."='".$VALUES[$j]."'";
			}
			$TMP_data.=";\n";
		//}
	}
}
if ($op=="save") $TMP_data="DELETE FROM ".$table.";\n".$TMP_data;

echo "<tr><td colspan=2><pre>".$TMP_data."</pre></td></tr>";
echo "</table><br>";
echo "</center>\n";

if ($confirm=="") {
	echo "<form name=F action=$PHP_SELF method=post><input type=hidden name=PHPSESSID value='$PHPSESSID'><input type=hidden name=V_dir value='$V_dir'><input type=hidden name=V_mod value='$V_mod'>\n";
	foreach ($_REQUEST as $field=>$value) {
		echo "<input type=hidden name=$field value='$value'>";
	}
	echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;<input type=submit name=confirm value='"._DEF_NLSAccept."'><br>\n";
	echo "</form>\n";
} else {
	RAD_printLog($TMP_data);
//	$result = sql_query($TMP_data, $RAD_dbi);
	$REGS=explode("\n",$TMP_data);
	if (count($REGS)>0) {
		$TMP_sql="";
		for ($i=0;$i<count($REGS);$i++) {
			$TMP_sql.=$REGS[$i];
			if (substr($TMP_sql,strlen($TMP_sql)-1,1)==";") {
				$result = sql_query($TMP_sql, $RAD_dbi);
				if (sql_error($RAD_dbi)) echo $TMP_sql."<br>".sql_error($RAD_dbi)."<br>";
				else $TMP_sql="";
			}
		}
	}
	echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;<b>"._DEF_NLSSaved."</b><br>\n";
}

}
?>
