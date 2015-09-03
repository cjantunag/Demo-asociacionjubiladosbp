<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: ../../index.php");
    die();
}

// Permite crear ficheros de Idiomas partiendo de otro, recogiendo variables de Modulos. Comparar varios ficheros de Idiomaas.
// Parametros: automaticTranslation
global $automaticTranslation;

include_once("header.php");

OpenTable();

echo "<center><br><b>"._DEF_NLSFile." "._DEF_NLSLanguage."</b><br><br>\n";
echo "<hr noshade size=1 width=100%>\n";

if ($dbsource!="") {
	translateDB($dbsource);
} else if ($opcreate!="") {
	createLangDirFile();
} else if ($opcheck!="") {
	checkLangFiles();
} else if ($opsave!="") {
	saveLangFiles();
} else if ($opedit!="" || $opshow!="" || $opcopy!="") {
	editLangFiles();
} else {
	formInput();
}

CloseTable();

include_once("footer.php");
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function formInput() {
global $PHP_SELF, $V_dir, $V_mod, $langdir;

if ($langdir=="") $langdir="language";
echo "<form action=$PHP_SELF method=post><input type=hidden name=V_dir value='$V_dir'><input type=hidden name=V_mod value='$V_mod'>\n";
echo "<center><table width=80% border=0 cellpadding=0 cellspacing=0>\n";

echo "<tr><td align=left> <b>1.</b> "._DEF_NLSDirectory." [<b>".$langdir."</b>] : </td><td><select onchange='javascript:submit();' name=langdir> \n";
$f = opendir(_DEF_DIRBASE."modules/");
$TMP_opt="";
if ($langdir=="language") $TMP_opt="<option value='language' SELECTED> language </option>\n";
else $TMP_opt="<option value='language'> language </option>\n";
if ($langdir=="blocks/language") $TMP_opt.="<option value='blocks/language' SELECTED> blocks/language </option>\n";
else $TMP_opt.="<option value='blocks/language'> blocks/language </option>\n";
while ($fn=readdir($f)) {
	if ($fn=="" || $fn=="." || $fn=="..") continue;
	if (!is_dir(_DEF_DIRBASE."modules/".$fn)) continue;
	if ($langdir=="modules/".$fn."/language") $TMP_opt.="<option value='modules/".$fn."/language' SELECTED> modules/".$fn."/language </option>\n";
	else $TMP_opt.="<option value='modules/".$fn."/language'> modules/".$fn."/language </option>\n";
}
echo $TMP_opt."</select></td><td><input type=checkbox value='x' name='automaticTranslation'> Traducci&oacute;n Autom&aacute;tica/Automatic Translation </td></tr>\n";
closedir($f);

echo "<tr><td colspan=3 align=center><hr noshade size=1 width=100%></td></tr>\n";

echo "<tr><td align=left> <b>2.A.</b> "._DEF_NLSCreate." "._DEF_NLSFile." "._DEF_NLSLanguage."</td>\n";
echo "<td><select name=langcreate>\n";
$f = opendir(_DEF_DIRBASE."language/"); // recoge los ficheros base
$TMP_opt="";
$TMP_cont=0;
while ($fn=readdir($f)) {
	if ($fn =="") continue;
	if (is_dir(_DEF_DIRBASE."language/".$fn)) continue;
	if (substr($fn,0,5)!="lang-") continue;
	$lang=substr($fn,5);
	$A_langs[$TMP_cont]=substr($lang,0,strlen($lang)-4);
	$TMP_cont++;
}
sort($A_langs);
foreach ($A_langs as $TMP_cont=>$TMP_lang) {
	if($TMP_lang==_DEF_LANGUAGE) $TMP_selected=" SELECTED";
	else $TMP_selected="";
	$TMP_opt.="<option value='".$TMP_lang."'".$TMP_selected."> ".$TMP_lang." </option>\n";
}
echo $TMP_opt."</select></td><td><input type=checkbox value='x' name='unopormodulo'> Un fichero de idioma por m&oacute;dulo</td></tr>\n";
closedir($f);
echo "<tr><td colspan=3 align=center><br><input type=submit name=opcreate value='"._DEF_NLSCreate."'><br></td></tr>\n";

/*
echo "<tr><td colspan=2 align=center><hr noshade size=1 width=100%></td></tr>\n";
echo "<tr><td colspan=2 align=left><b>2.B.</b> "._DEF_NLSCopyString." "._DEF_NLSFile." "._DEF_NLSLanguage."</td></tr>\n";
echo "<tr><td align=right> "._DEF_NLSSource." "._DEF_NLSFile." "._DEF_NLSLanguage.": </td><td><select name=file1>\n";
$f = opendir(_DEF_DIRBASE.$langdir);
$TMP_opt="";
while ($fn=readdir($f)) {
	if ($fn =="") continue;
	if (is_dir(_DEF_DIRBASE.$langdir.$fn)) continue;
	if (substr($fn,0,5)!="lang-") continue;
	$lang=substr($fn,5);
	$lang=substr($lang,0,strlen($lang)-4);
	if($lang==_DEF_LANGUAGE) $TMP_selected=" SELECTED";
	else $TMP_selected="";
	$TMP_opt.="<option value='$lang'".$TMP_selected."> ".$lang." </option>\n";
}
echo $TMP_opt."</select></td></tr>\n";
closedir($f);
echo "<tr><td align=right> "._DEF_NLSTarget." "._DEF_NLSFile." "._DEF_NLSLanguage.": </td><td><select name=file2><option value='' selected> ..."._DEF_NLSNewString." </option>\n";
$f = opendir(_DEF_DIRBASE."language/");
$TMP_opt="";
while ($fn=readdir($f)) {
	if ($fn =="") continue;
	if (is_dir(_DEF_DIRBASE."language/".$fn)) continue;
	if (substr($fn,0,5)!="lang-") continue;
	$lang=substr($fn,5);
	$lang=substr($lang,0,strlen($lang)-4);
	$TMP_opt.="<option value='$lang'> ".$lang." </option>";
}
echo $TMP_opt."</select></td></tr>\n";
closedir($f);
echo "<tr><td colspan=2 align=center><br><input type=submit name=opcopy value='"._DEF_NLSCopyString."'></td></tr>\n";
*/

$TMP_opc2B="<tr><td colspan=3 align=center><hr noshade size=1 width=100%></td></tr>\n";
//$TMP_opc2B.="<tr><td colspan=2 align=left><b>2.C.</b> "._DEF_NLSCheck."/"._DEF_NLSStringEdit." "._DEF_NLSFile." "._DEF_NLSLanguage."</td></tr>\n";
$TMP_opc2B.="<tr><td align=left><b>2.B.</b> "._DEF_NLSCopyString."/"._DEF_NLSEditString." "._DEF_NLSFile." "._DEF_NLSLanguage."</td></tr>\n";
$f = opendir(_DEF_DIRBASE.$langdir);
$cont=0;
$TMP_opc2B.="<tr><td><input type=checkbox value='x' name='new' checked> "._DEF_NLSNewString." </td>\n";
$cont=1; $contl=0;
while ($fn=readdir($f)) {
	if ($fn =="") continue;
	if (is_dir(_DEF_DIRBASE.$langdir.$fn)) continue;
	if (substr($fn,0,5)!="lang-") continue;

	$lang=substr($fn,5);
	$lang=substr($lang,0,strlen($lang)-4);

	if ($cont==0) $TMP_opc2B.="<tr>\n";
	$TMP_opc2B.="<td><input type=checkbox value='x' name='$lang'> ".$lang." </td>\n";

	$cont++;
	$contl++;
	if ($cont>2) $cont=0;
	if ($cont==0) $TMP_opc2B.="</tr>\n";
}
if ($cont==1) $TMP_opc2B.="<td>&nbsp;</td><td>&nbsp;</td></tr>";
if ($cont==2) $TMP_opc2B.="<td>&nbsp;</td></tr>";
closedir($f);
//$TMP_opc2B.="<tr><td colspan=2 align=center><br><input type=submit name=opcheck value='"._DEF_NLSCheck."'>&nbsp;&nbsp;<input type=submit name=opedit value='"._DEF_NLSCopyString."/"._DEF_NLSEditString."'></td></tr>\n";
$TMP_opc2B.="<tr><td colspan=2 align=center><br><input type=submit name=opedit value='"._DEF_NLSCopyString."/"._DEF_NLSEditString."'></td></tr>\n";
if ($contl>0) echo $TMP_opc2B;

echo "</table>\n";
echo "</center></form>\n";
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function saveLangFiles() {
global $PHP_SELF, $Afield, $LangAfield, $_REQUEST, $langdir;

  echo "<form action=$PHP_SELF method=post><input type=hidden name=V_dir value='$V_dir'><input type=hidden name=V_mod value='$V_mod'><input type=hidden name=langdir value='$langdir'>\n";
  foreach ($_REQUEST as $key=>$val) {
	if (substr($key,0,4)=="file" && $key!="file") {
		$numfile=substr($key,4);
		if ($val!="") $files[$numfile]=$val;
	}
	if (substr($key,0,4)=="name") {
		$numvar=substr($key,4);
		if ($val!="") $fields[$numvar]=$val;
	}
  }
  foreach ($_REQUEST as $key=>$val) {
	if (substr($key,0,3)=="val") {
		$conts=substr($key,3);
		$cont=explode("_",$conts);
		$numfile=$cont[0];
		$numvar=$cont[1];
		$TMPfile=$files[$numfile];
		$field=$fields[$numvar];
		//if ($val!="") {
			$LangAfield[$TMPfile."_".$field]=$val;
//echo "<b>".$TMPfile."_".$field."</b>=".urlencode($val).".<br>";
			//$contenido[$TMPfile].="define(\"".$field."\",\"".$val."\");\n";
		//}
	}
  }
  foreach ($files as $num=>$TMPfile) {
	$TMP_contenido=$contenido[$TMPfile];
	$TMP_contenido=str_replace("<","&lt;",$TMP);
//	echo "<pre>".$TMP."</pre>";
	if ($TMPfile!="") saveOneLangFile($TMPfile, $TMP_contenido);
	echo "<b>$TMPfile "._DEF_NLSSaved."</b><br>\n";
  }

  //echo " <input type=button name=cancel value='"._DEF_NLSCancelString."' onClick='javascript:history.go(-2);'>\n";
  echo "</form>\n";
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function checkLangFiles() {
global $PHP_SELF, $Afield, $LangAfield, $_REQUEST, $langdir;

$f = opendir(_DEF_DIRBASE.$langdir);
$TMP_opt="";
while ($fn=readdir($f)) {
	if ($fn =="") continue;
	if (is_dir(_DEF_DIRBASE.$langdir."/".$fn)) continue;
	if (substr($fn,0,5)!="lang-") continue;

	$lang=substr($fn,5);
	$lang=substr($lang,0,strlen($lang)-4);
	$lang2=str_replace(".","_",$lang);
	if ($_REQUEST[$lang]=="" && $_REQUEST[$lang2]=="") continue;
	readLangFile($lang);
	$Langs[$lang]="*";
}


echo "<table border=1 cellpadding=0 cellspacing=3 bordercolor=navy bgcolor=white>";
echo "<tr><td colspan=2></td>";
foreach ($Langs as $lang=>$val) {
	echo "<th>$lang</th>\n";
}
echo "</tr>";
foreach ($Afield as $key=>$val) {
	$cmd="cd "._DEF_DIRBASE.$langdir.";grep -lr ".$key." "._DEF_DIRBASE." | grep -v 'language/'";
	$result=process($cmd);
	if ($result=="") $result="<font color=darkred>[unused]</font>";
	else $result="";
//	else $result="<pre>".$result."</pre>";
	echo "<tr><td>".$key."</td>";
	echo "<td>".$result."&nbsp;</td>";
	foreach ($Langs as $lang=>$val) {
		echo "<td>";
		if ($LangAfield[$lang."_".$key]=="") echo "<b>*</b>";
		else if ($lang==_DEF_LANGUAGE) echo "<b>".$LangAfield[$lang."_".$key]."</b>";
		else echo $LangAfield[$lang."_".$key];
		echo "</td>";
	}
	echo "</tr>";
}
echo "</table>";

}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function editLangFiles() {
global $PHP_SELF, $V_dir, $V_mod, $Afield, $LangAfield, $_REQUEST, $file1, $file2, $langdir;

echo "<form action=$PHP_SELF method=post><input type=hidden name=V_dir value='$V_dir'><input type=hidden name=V_mod value='$V_mod'><input type=hidden name=langdir value='$langdir'>\n";

if ($file1!="") $_REQUEST[$file1]="x";
if ($file2!="") $_REQUEST[$file2]="x";

$f = opendir(_DEF_DIRBASE.$langdir."/");
$TMP_opt="";
while ($fn=readdir($f)) {
	if ($fn =="") continue;
	if (is_dir(_DEF_DIRBASE.$langdir."/".$fn)) continue;
	if (substr($fn,0,5)!="lang-") continue;

	$lang=substr($fn,5);
	$lang=substr($lang,0,strlen($lang)-4);
	$lang2=str_replace(".","_",$lang);
	if ($_REQUEST[$lang]=="" && $_REQUEST[$lang2]=="") continue;
	readLangFile($lang);
	$Langs[$lang]="*";
}
if ($_REQUEST["new"]!="") $Langs["new"]="*";

echo "<br><center>"._DEF_NLSDirectory.": <b>$langdir</b><br><br>";

echo "<table border=1 cellpadding=0 cellspacing=3 bordercolor=navy bgcolor=white>";
echo "<tr><th>"._DEF_NLSField."</th>";
$cont=0;
foreach ($Langs as $lang=>$val) {
	$cont++;
	if ($lang=="new") echo "<th>"._DEF_NLSNewString.":<input type=text size=25 name=file$cont></th>\n";
	else echo "<th>$lang<input type=hidden name=file$cont value='$lang'></th>\n";
}
echo "</tr>";

$cont=0;
foreach ($Afield as $key=>$val) {
	echo "<tr>";
	$cont++;
	$contLang=0;
	echo "<td><input type=text name='name$cont' value='$key' size=15></td>";
	foreach ($Langs as $lang=>$val) {
		$contLang++;
		if ($LangAfield[$lang."_".$key]=="") {
			foreach ($Langs as $lang2=>$val2) {
				if ($lang2==$lang) continue;
				$TMP_trad2=$LangAfield[$lang2."_".$key];
				if ($TMP_trad2=="") continue;
				$TMP_trad=translateLang($lang2,$lang,$TMP_trad2);
				$LangAfield[$lang."_".$key]=$TMP_trad;
				//die("Traduce $key de $lang2 a $lang con valor: $TMP_trad2 y obtiene $TMP_trad"); 
			}
		}
		echo "<td><input type=text name='val".$contLang."_".$cont."' value=\"".HTML_Value($LangAfield[$lang."_".$key])."\" size=40></td>\n";
	}
	echo "</tr>";
}

for ($ki = 1; $ki < 10; $ki++) {
	$cont++;
	$contLang=0;
	echo "<tr><td><input type=text name='name$cont' value='' size=15></td>";
	foreach ($Langs as $lang=>$val) {
		$contLang++;
		echo "<td><input type=text name='val".$contLang."_".$cont."' value='' size=40></td>";
	}
}
echo "</table>";

echo "</ul><center><input accesskey='S' type=submit name=opsave value='"._DEF_NLSSave."'>&nbsp;&nbsp;&nbsp;\n";
echo "<input type=button name=cancel value='"._DEF_NLSCancelString."' onClick='javascript:history.go(-1);'></center>\n";

echo "</form>\n</center>";
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function readLangFile($TMPfile) {
global $Afield, $LangAfield, $langdir;
  if (file_exists(_DEF_DIRBASE.$langdir."/lang-".$TMPfile.".php")) {
	$fp = fopen(_DEF_DIRBASE.$langdir."/lang-".$TMPfile.".php","r"); 
	while (!feof($fp)) {
		$line = fgets($fp,9999);
		if (substr(trim($line),0,6)!="define") continue;

		$poscoma=strpos($line,",");
		$poscomilla=strpos($line,"'");
		$poscomilla2=strpos($line,'"');
		if ($poscomilla=="") $poscomilla=$poscomilla2;
		if ($poscomilla2>0 && $poscomilla2<$poscomilla) $poscomilla=$poscomilla2;
		$key=substr($line,$poscomilla+1,$poscoma-2-$poscomilla);

		$sufix=trim(substr($line,$poscoma));
		$poscomilla=strpos($sufix,"'");
		$poscomilla2=strpos($sufix,'"');
		if ($poscomilla=="") $poscomilla=$poscomilla2;
		if ($poscomilla2>0 && $poscomilla2<$poscomilla) $poscomilla=$poscomilla2;
		$poscomillar=strrpos($sufix,"'");
		$poscomillar2=strrpos($sufix,'"');
		if ($poscomillar=="") $poscomillar=$poscomillar2;
		if ($poscomillar2>0 && $poscomillar2>$poscomillar) $poscomillar=$poscomillar2;
		$val=substr($sufix,$poscomilla+1,$poscomillar-$poscomilla-1);
//echo $poscomilla." ".$poscomillar." ".htmlspecialchars($sufix)."<br>";
//echo "<b>".$TMPfile."_".$key."</b>=".htmlspecialchars($val)."<br>";
		$LangAfield[$TMPfile."_".$key]=$val;
		$Afield[$key]="*";
	}
	fclose($fp);

  }
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function saveOneLangFile($TMPfile, $content) {
global $langdir, $LangAfield;

  $TMPfiledir=_DEF_DIRBASE.$langdir."/lang-".$TMPfile.".php";
  $lines="";
  if (file_exists($TMPfiledir)) {
	chmod($TMPfiledir, 0666);
	$fp = fopen($TMPfiledir,"r"); 
	while (!feof($fp)) {
		$line=fgets($fp,9999);
		if (substr(trim($line),0,6)=="define") {
			$poscoma=strpos($line,",");
			$poscomilla=strpos($line,"'");
			$poscomilla2=strpos($line,'"');
			if ($poscomilla=="") $poscomilla=$poscomilla2;
			if ($poscomilla2>0 && $poscomilla2<$poscomilla) $poscomilla=$poscomilla2;
			$key=substr($line,$poscomilla+1,$poscoma-2-$poscomilla);

			$sufix=trim(substr($line,$poscoma));
			$poscomilla=strpos($sufix,"'");
			$poscomilla2=strpos($sufix,'"');
			if ($poscomilla=="") $poscomilla=$poscomilla2;
			if ($poscomilla2>0 && $poscomilla2<$poscomilla) $poscomilla=$poscomilla2;
			$poscomillar=strrpos($sufix,"'");
			$poscomillar2=strrpos($sufix,'"');
			if ($poscomillar=="") $poscomillar=$poscomillar2;
			if ($poscomillar2>0 && $poscomillar2>$poscomillar) $poscomillar=$poscomillar2;
			$val=substr($sufix,$poscomilla+1,$poscomillar-$poscomilla-1);
//echo $poscomilla." ".$poscomillar." ".htmlspecialchars($sufix)."<br>";
//echo "<b>".$TMPfile."_".$key."</b>=".htmlspecialchars($val)."<br>";
			//if ($key!="") {
			if ($key!="" && isset($LangAfield[$TMPfile."_".$key])) {
				if ($LangAfield[$TMPfile."_".$key]!="") $line="define(\"".$key."\",\"".$LangAfield[$TMPfile."_".$key]."\");\n";
				else $line="";
				$LangAfield[$TMPfile."_".$key]="";
			}
		}
		if (substr(trim($line),0,1)=='?' && substr(trim($line),1,1)=='>') $line="";
		$lines.=$line;
		//$pos=strpos($line, "DO NOT EDIT THIS LINE");
		//if ($pos>0) break;
	}
	fclose($fp);
  }
  //$lines.="\n".$content;
  foreach($LangAfield as $TMP_k=>$TMP_v) {
	if ($TMP_v=="") continue;
	$TMP_prefix=substr($TMP_k,0,strlen($TMPfile));
	if ($TMP_prefix!=$TMPfile) continue;
	$TMP_sufix=substr($TMP_k,strlen($TMPfile)+1);
//echo "<b>** ".$TMP_prefix." == ".$TMP_sufix."</b>=".$TMP_v."<br>";
	if ($lines=="") {
		$lines.="<?php\n";
	}
	$lines.="define(\"".$TMP_sufix."\",\"".$TMP_v."\");\n";
  }
//  echo ("<br><b>$TMPfiledir</b><br>".$lines."<br><br>"); // PRUEBA
  $f = fopen($TMPfiledir,"w");
  fputs($f,$lines."?".">\n");
  fclose($f);
  chmod($TMPfile, 0666);

  return;
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function process($command) {
	if (!($p=popen("($command)2>&1","r"))) {
		echo "Error al procesar comando ($command).";
		die;
	}
	while (!feof($p)) {
		$line=fgets($p,1000);
		$result.=$line;
	}
	pclose($p);
	return $result;
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function createLangDirFile() {
global $PHP_SELF, $V_dir, $V_mod, $langdir, $langcreate, $Langs, $contfields, $fields, $findex, $langdir, $unopormodulo;

set_time_limit(3600);
$TMPfile = _DEF_DIRBASE.$langdir."/lang-".$langcreate.".php"; 
$TMPdirfile = _DEF_DIRBASE.$langdir; 
if ($contfields>0) {
	$ZONE_CONTENT_USER=false;
	$CONTENT_LANG="<"."?\n\nglobal \$titleNLS, \$RAD_NLS, \$fields, \$findex, \$V_mod;\n\n";
	$CONTENT_USER="";
	if ($fp = @fopen($TMPfile,"r")) {
	    while (!feof($fp)) {
		$line=fgets($fp,64000);
		if ($ZONE_CONTENT_USER==true) 
		    $CONTENT_USER.=$line;
		if (trim($line)=="// EDIT AFTER THIS LINE") $ZONE_CONTENT_USER=true;
	    }
	    fclose($fp);
	}
	if ($CONTENT_USER=="") $CONTENT_USER="\n?".">\n";
	for ($ki=0; $ki<$contfields; $ki++) {
		global ${"name".$ki}, ${"val1_".$ki};
		if (${"name".$ki}!="" && trim(${"val1_".$ki})!="") 
		    $CONTENT_LANG.="\$fields[\$findex['".${"name".$ki}."']]->title=\"".${"val1_".$ki}."\";\n";
		global ${"valh1_".$ki};
		if (${"name".$ki}!="" && trim(${"valh1_".$ki})!="") 
		    $CONTENT_LANG.="\$fields[\$findex['".${"name".$ki}."']]->help=\"".${"valh1_".$ki}."\";\n";
		global ${"valtit1_".$ki};
		if (${"name".$ki}!="" && trim(${"valtit1_".$ki})!="") 
		    $CONTENT_LANG.="if (\$V_mod==\"".${"name".$ki}."\") \$titleNLS=\"".${"valtit1_".$ki}."\";\n";
		global ${"valNLS1_".$ki};
		if (${"name".$ki}!="" && trim(${"valNLS1_".$ki})!="") 
		    $CONTENT_LANG.="\$RAD_NLS[\"".${"name".$ki}."\"]=\"".${"valNLS1_".$ki}."\";\n";
	}
	$CONTENT_LANG.="\n// EDIT AFTER THIS LINE\n";
	if (!file_exists($TMPdirfile)) mkdir($TMPdirfile,0777);
        $fp = fopen($TMPfile,"w");
        fputs($fp,$CONTENT_LANG.$CONTENT_USER."\n?".">");
	fclose($fp);
	chmod($TMPfile, 0666);
die("PRUEBA");
}

$Langs[$langcreate]=$langcreate;

echo "<center><form action=$PHP_SELF method=post><input type=hidden name=V_dir value='$V_dir'><input type=hidden name=V_mod value='$V_mod'><input type=hidden name=langdir value='$langdir'>\n";
echo "<input type=hidden name=langcreate value='$langcreate'>\n";
echo "<br><b>"._DEF_NLSCreate." ".$langdir." ".$langcreate."</b><br><br>\n";

global $fields, $findex;
if (count($findex)>0) {
    foreach ($findex as $key=>$val) {
	unset($findex[$key]);
    }
}

// Lee todos los prj para recoger su title y sus fields[findex[...]]->name, overlap, title y help
// y se almacenan el titleorig[nombreprj] y fields[findex[...]]*orig
$langdirmod=substr($langdir,0,strlen($langdir)-9); // quita /language
$f = opendir(_DEF_DIRBASE.$langdirmod."/");
$TMP_opt="";
$TMP_cont=0;
while ($fn=readdir($f)) {
	if ($fn=="") continue;
	if (is_dir(_DEF_DIRBASE.$langdirmod."/".$fn)) continue;
	if (substr($fn,strlen($fn)-8)!=".prj.php") continue;
	$fp = fopen(_DEF_DIRBASE.$langdirmod."/".$fn,"r"); 
//echo $fn."<br>";
	$mod=substr($fn,0,strlen($fn)-8);
	$TMPfilemod=_DEF_DIRBASE.$langdirmod."/language/lang-".$langcreate.".".$mod.".php"; 
	$TMPdirfilemod=_DEF_DIRBASE.$langdirmod."/language";
	if ($unopormodulo!="") {
		if (file_exists($TMPfilemod)) continue;
	}
	$A_OVERLAP=array();
	$CONTENT_LANG="<"."?\n\nglobal \$titleNLS, \$RAD_NLS, \$fields, \$findex, \$V_mod;\n\n";
	while (!feof($fp)) {
	    $line=trim(fgets($fp,9999));
	    if (substr($line,1,5)=="title") {  // titulo del prj
		$titleorig[$mod]=substr($line,10,strlen($line)-12);
		if ($unopormodulo!="") {
			$TMP_trans=translateLang(_DEF_LANGUAGE,$langcreate,$titleorig[$mod]);
			$CONTENT_LANG.="if (\$V_mod==\"".$mod."\") \$titleNLS=\"".$TMP_trans."\";\n";
		}
	    }
	    if (substr($line,1,5)=="TITLE") {  // en los prj primero esta TITLE despues NAME, HELP y despues OVERLAP
		$TITLE=substr($line,16,strlen($line)-18);
		if ($unopormodulo!="" && $TITLE!="") {
			$TITLE_trans=translateLang(_DEF_LANGUAGE,$langcreate,$TITLE);
		}
	    }
	    if (substr($line,1,4)=="NAME") {
		$NAME=substr($line,15,strlen($line)-17);
		$CONTENT_LANG.="\$fields[\$findex['".$NAME."']]->title=\"".$TITLE_trans."\";\n";
		if ($findex[$NAME]=="") {
		    $findex[$NAME]=$TMP_cont;
		    $TMP_cont++;
		    if ($TITLE!="") $fields[$findex[$NAME]]->titleorig=$TITLE;
//echo $NAME."=".$findex[$NAME].".---> T=".$TITLE."<br>";
		}
	    }
	    if (substr($line,1,4)=="HELP") {
		$HELP=substr($line,15,strlen($line)-17);
		$HELP=str_replace("<","&lt;",$HELP);
		if ($HELP!="") $fields[$findex[$NAME]]->helporig=$HELP;
		if ($unopormodulo!="" && $HELP!="") {
			$TMP_trans=translateLang(_DEF_LANGUAGE,$langcreate,$HELP);
			$CONTENT_LANG.="\$fields[\$findex['".$NAME."']]->help=\"".$TMP_trans."\";\n";
		}
//echo "\n".$NAME."=".$findex[$NAME].".---> H=".$fields[$findex[$NAME]]->helporig."\n".$HELP."\n<br>\n";
	    }
	    if (substr($line,1,7)=="OVERLAP") {
		$OVERLAP=substr($line,18,strlen($line)-20);
		$OVERLAP=str_replace("<","&lt;",$OVERLAP);
		if ($OVERLAP!="") $fields[$findex[$NAME]]->overlaporig=$OVERLAP;
		if ($unopormodulo!="" && $OVERLAP!="" && $A_OVERLAP[$OVERLAP]=="") {
			$A_OVERLAP[$OVERLAP]="x";
			$TMP_trans=translateLang(_DEF_LANGUAGE,$langcreate,$OVERLAP);
			$CONTENT_LANG.="\$RAD_NLS[\"".$OVERLAP."\"]=\"".$TMP_trans."\";\n";
		}
//echo "\n".$NAME."=".$findex[$NAME].".---> O=".$fields[$findex[$NAME]]->overlaporig."\n".$OVERLAP."\n<br>\n";
	    }
	}
	fclose($fp);
	if ($unopormodulo!="") {
		$CONTENT_LANG.="\n// EDIT AFTER THIS LINE\n\n?".">";
		if (!file_exists($TMPdirfilemod)) mkdir($TMPdirfilemod,0777);
		if (!file_exists($TMPfilemod)) {
			echo "[DEBUG] Crea <b>".$TMPfilemod."</b><br>"; 
        		$fp = fopen($TMPfilemod,"w");
        		fputs($fp,$CONTENT_LANG);
			fclose($fp);
			chmod($TMPfilemod, 0666);
		}
	}
	//die("[DEBUG] crea solo uno cada vez. ".$TMPfilemod); 
}

if (file_exists($langdir."/lang-".$langcreate.".php")) {
    // Se lee para buscar los title de los modulos
    $fp = fopen(_DEF_DIRBASE.$langdir."/lang-".$langcreate.".php","r"); 
    while (!feof($fp)) {
	$line=trim(fgets($fp,9999));
	$A_tmp=explode("define(",$line);
	if (count($A_tmp)>1) {
		$poscoma=strpos($line,",");
		$poscomilla=strpos($line,"'");
		$poscomilla2=strpos($line,'"');
		if ($poscomilla=="") $poscomilla=$poscomilla2;
		if ($poscomilla2>0 && $poscomilla2<$poscomilla) $poscomilla=$poscomilla2;
		$key=substr($line,$poscomilla+1,$poscoma-2-$poscomilla);

		$sufix=trim(substr($line,$poscoma));
		$poscomilla=strpos($sufix,"'");
		$poscomilla2=strpos($sufix,'"');
		if ($poscomilla=="") $poscomilla=$poscomilla2;
		if ($poscomilla2>0 && $poscomilla2<$poscomilla) $poscomilla=$poscomilla2;
		$poscomillar=strrpos($sufix,"'");
		$poscomillar2=strrpos($sufix,'"');
		if ($poscomillar=="") $poscomillar=$poscomillar2;
		if ($poscomillar2>0 && $poscomillar2>$poscomillar) $poscomillar=$poscomillar2;
		$val=substr($sufix,$poscomilla+1,$poscomillar-$poscomilla-1);

		$TMP_mod=$key; $TMP_title=$val;
	    //$TMP_mod=substr($A_tmp[1],1,strpos($A_tmp[1],",")-2);
	    //$TMP_title=substr($A_tmp[1],strpos($A_tmp[1],",")+2);
	    //$TMP_title=trim(substr($TMP_title,0,strrpos($TMP_title,");")));
	    //$TMP_title=substr($TMP_title,0,strlen($TMP_title)-1);
	    $defineorig[$TMP_mod]=$TMP_title;
	    $define[$TMP_mod]=$TMP_title;
	}
    }
    fclose($fp);
//    include($langdir."/lang-".$langcreate.".php");
}

echo "<table width=90% class=browse>";
echo "<tr><th class=browse>"._DEF_NLSField."</th>";
if (count($titleorig)>0 || count($findex)>0) {
	echo "<th class=browse>"._DEF_NLSSource."</th>";
} else {
	echo "<th class=browse></th>";
}
$cont=0;
foreach ($Langs as $lang=>$val) {
	$cont++;
	echo "<th class=browse>$lang<input type=hidden name=file$cont value='$lang'></th>\n";
}
echo "</tr>";

$cont=0;

if (count($defineorig)>0) {
    foreach ($defineorig as $key=>$val) {
	echo "<tr>";
	$cont++;
	$contLang=0;
	echo "<td><input type=hidden name='name$cont' value='$key' size=15>$key</td>";
	echo "<td>".$val."</td>";
	foreach ($Langs as $lang=>$val_lang) {
		$contLang++;
		if ($define[$key]=="") $define[$key]=translateLang(_DEF_LANGUAGE,$langcreate,$val);
		$TMP_value=HTML_Value($define[$key]);
		echo "<td><input type=text size=80 name='val".$contLang."_".$cont."' value=\"".$TMP_value."\"></td>\n";
	}
	echo "</tr>";
    }
    echo "<tr><td colspan=3><br></td></tr>";
}
if (count($titleorig)>0) {
    foreach ($titleorig as $key=>$val) {
	echo "<tr>";
	$cont++;
	$contLang=0;
	echo "<td><input type=hidden name='name$cont' value='$key' size=15>$key</td>";
	echo "<td>".$val."</td>";
	foreach ($Langs as $lang=>$val_lang) {
		$contLang++;
		if ($title[$key]=="") $title[$key]=translateLang(_DEF_LANGUAGE,$langcreate,$val);
		$TMP_value=HTML_Value($title[$key]);
		echo "<td><input type=text size=80 name='valtit".$contLang."_".$cont."' value=\"".$TMP_value."\"></td>\n";
	}
	echo "</tr>";
    }
    echo "<tr><td colspan=3><br></td></tr>";
}
if (count($findex)>0) {
    foreach ($findex as $key=>$val) {
	echo "<tr>";
	$cont++;
	$contLang=0;
	echo "<td><input type=hidden name='name$cont' value='$key' size=15>$key</td>";
	echo "<td>".$fields[$val]->titleorig."</td>";
	foreach ($Langs as $lang=>$val_lang) {
		$contLang++;
		if ($fields[$val]->title=="") $fields[$val]->title=translateLang(_DEF_LANGUAGE,$langcreate,$fields[$val]->titleorig);
		$TMP_value=HTML_Value($fields[$val]->title);
		echo "<td><input type=text size=80 name='val".$contLang."_".$cont."' value=\"".$TMP_value."\"></td>\n";
	}
	echo "</tr>";
        if ($fields[$val]->helporig!="") {
    	    echo "<tr>";
	    $contLang=0;
	    echo "<td>$key <img src='images/help2.png' border=0></td>";
	    echo "<td>".$fields[$val]->helporig."</td>";
	    foreach ($Langs as $lang=>$val_lang) {
		$contLang++;
		if ($fields[$val]->help=="") $fields[$val]->help=translateLang(_DEF_LANGUAGE,$langcreate,$fields[$val]->helporig);
		$TMP_value=HTML_Value($fields[$val]->help);
		echo "<td><input type=text size=40 name='valh".$contLang."_".$cont."' value=\"".$TMP_value."\"></td>\n";
	    }
	    echo "</tr>";
	}
        if ($fields[$val]->overlaporig!="" && $SHOWED[htmlentities($fields[$val]->overlaporig)]=="") {
    	    echo "<tr>";
	    $contLang=0;
	    echo "<td>$key <img src='images/info2.gif' border=0></td>";
	    echo "<td>".$fields[$val]->overlaporig."</td>";
	    foreach ($Langs as $lang=>$val_lang) {
		$contLang++;
		$TMP_overlap=$RAD_NLS[$fields[$val]->overlaporig];
		if ($TMP_overlap=="") $TMP_overlap=translateLang(_DEF_LANGUAGE,$langcreate,$fields[$val]->overlaporig);
		echo "<input type=hidden name='name$cont' value='".$fields[$val]->overlaporig."'>";
		$TMP_value=HTML_Value($TMP_overlap);
		echo "<td><input type=text size=20 name='valNLS".$contLang."_".$cont."' value=\"".$TMP_value."\"></td>\n";
		$SHOWED[htmlentities($fields[$val]->overlaporig)]="x";
	    }
	    echo "</tr>";
	}
    }
}

for ($ki = 1; $ki < 5; $ki++) {
	$cont++;
	$contLang=0;
	echo "<tr><td><input type=text name='name$cont' value='' size=15></td><td></td>";
	foreach ($Langs as $lang=>$val) {
		$contLang++;
		echo "<td><input type=text size=40 name='val".$contLang."_".$cont."' value=''></td>";
	}
}
echo "</table>";
$cont++;

echo "<input type=hidden name='contfields' value='$cont'>";
echo "<input accesskey='S' type=submit name=opcreate value='"._DEF_NLSSave."'>&nbsp;&nbsp;&nbsp;\n";
echo "<input type=reset name=cancel value='"._DEF_NLSReset."'></center>\n";

echo "</form>\n";
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function translateLang($langorig,$langdest,$word) {
// Traduce palabra/frase con comando (p.e. i2e) o URL de Internet (p.e. translate.google.com)
global $automaticTranslation;
if ($automaticTranslation=="") return "";
if (trim($word)=="") return "";
//$word=str_replace(":","_",$word);
$wordt="";
if($langorig=="spanish" && $langdest=="english") {
	$command="i2e-cli -r ".$word;
	if (!($p=popen("($command)2>&1","r"))) echo "Error al procesar comando ($command).";
	while (!feof($p)) {
		$line=fgets($p,1000);
		$A_result=explode(":",$line);
		if (count($A_result)>0) if (strtolower(trim($A_result[1]))==strtolower(trim($word))) $wordt=trim($A_result[0]);
	}
	pclose($p);
	if ($wordt=="") {
		$command="i2e-cli -r -e ".$word;
		if (!($p=popen("($command)2>&1","r"))) echo "Error al procesar comando ($command).";
		while (!feof($p)) {
			$line=fgets($p,1000);
			$A_result=explode(":",$line);
			if (count($A_result)>0) if (strtolower(trim($A_result[1]))==strtolower(trim($word))) $wordt=trim($A_result[0]);
		}
		pclose($p);
	}
}
$A_g=array();
$A_g["spanish"]="es";
$A_g["brazilian"]="pt";
$A_g["galician"]="gl";
if ($wordt=="") {
    $langorig=$A_g[$langorig];
    $langdest=$A_g[$langdest];
    $word=html_entity_decode($word);
    $command="wget -O files/tmp/translate.tmp --no-cache --user-agent='Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.0.2) Gecko/20021120 Netscape/7.01'--no-cache --user-agent='Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.0.2) Gecko/20021120 Netscape/7.01'--no-cache --user-agent='Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.0.2) Gecko/20021120 Netscape/7.01'--no-cache --user-agent='Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.0.2) Gecko/20021120 Netscape/7.01'--no-cache --user-agent='Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.0.2) Gecko/20021120 Netscape/7.01'--no-cache --user-agent='Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.0.2) Gecko/20021120 Netscape/7.01' 'http://translate.google.com/translate_t?langpair=".$langorig."|".$langdest."&text=".urlencode($word)."'";
    if (!($p=popen("($command)2>&1","r"))) echo "Error al procesar comando ($command).";
    while (!feof($p)) { $line=fgets($p,1000); }
    pclose($p);
    $fp = fopen("files/tmp/translate.tmp","r"); 
    $resultado="";
    while (!feof($fp)) {
	$line=trim(fgets($fp,9999));
	$resultado.=$line;
    }
    fclose($fp);
    unlink("files/tmp/translate.tmp");
    $A_res=explode("result_box",$resultado);
    if (count($A_res)>0) {
	$res2=$A_res[1];
	$A_res2=explode("</span>",$res2);
        if (count($A_res2)>0) {
    		$A_res3=explode(">",$A_res2[0]);
        	if (count($A_res3)>0) {
			$TMP_idx=count($A_res3)-1;
			$wordt=$A_res3[$TMP_idx];
		} else {
			$wordt=$A_res2[0];
		}
	}
    }
}
$wordt=htmlentities(utf8_decode($wordt));
//echo "[DEBUG] Traduce de $langorig a $langdest la palabra $word con resultado ".trim($wordt).".<br>".$command."<br>";
return trim($wordt);
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function HTML_Value($TMP_value) {
	return htmlspecialchars($TMP_value);
	return htmlentities($TMP_value);
	return urlencode($TMP_value);

	if (ereg("'",$TMP_value)) $TMP_value='"'.$TMP_value.'"';
	else $TMP_value="'".$TMP_value."'";
	return $TMP_value;
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function translateDB($TMP_dbsource) {
	global $RAD_dbi;

	$f = opendir(_DEF_DIRBASE."language");
	while ($fn=readdir($f)) {
		if ($fn =="") continue;
		if (is_dir(_DEF_DIRBASE.$langdir.$fn)) continue;
		if (substr($fn,0,5)!="lang-") continue;
		$lang=substr($fn,5);
		$lang=substr($lang,0,strlen($lang)-4);
		$A_lang[$lang]="x";
	}
	echo "</ul><br>";

	$TMP_res=sql_query("SHOW TABLES", $RAD_dbi);
	while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
		$TMP_table=$TMP_row[0];
		$TMP_res2=sql_query("SHOW COLUMNS FROM $TMP_table", $RAD_dbi);
		while($TMP_row2=sql_fetch_array($TMP_res2, $RAD_dbi)) {
			$A_tok=array();
			$A_tok=explode("_",$TMP_row2[0]."_");
			foreach($A_tok as $TMP_idx=>$TMP_val) {
				if ($A_lang[$TMP_val]!="") translateFieldDB($TMP_dbsource,$TMP_table,$TMP_row2[0],_DEF_LANGUAGE,$TMP_val); // Campo de Idioma
			}
		}
	}
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function translateFieldDB($TMP_dbsource,$TMP_tab,$TMP_field,$TMP_langorig,$TMP_langdest) {
	global $RAD_dbi;

	$TMP_fieldorig=str_replace("_".$TMP_langdest,"",$TMP_field);


	echo "DB=$TMP_dbsource.Tab=$TMP_tab.FieldOrig=$TMP_fieldorig.FieldDest=$TMP_field.LangOrig=$TMP_langorig.LangDest=$TMP_langdest<br>";

	$TMP_res=sql_query("SELECT * FROM $TMP_tab", $RAD_dbi);
	while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
		$word=$TMP_row[$TMP_fieldorig];
		$TMP_trans="";
		if (trim($word)!="") {
			echo "Trans ".$word." ".$TMP_trans."<br>";
			//xxxxxxxxxx$TMP_trans=translateLang($TMP_langorig,$TMP_langdest,$word);
			if (trim($TMP_trans)!="") {
				$cmd="UPDATE ".$TMP_tab." SET ".$TMP_field."='".converttosql($TMP_trans)." where ".$TMP_fieldorig."=".converttosql($word);
				//xxxxxxxxxsql_query($cmd, $RAD_dbi);
				echo " ==> ".$TMP_trans." ".$cmd."<br>";
			}
		}
	}
}
?>
