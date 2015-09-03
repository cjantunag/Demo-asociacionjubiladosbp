<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

// Muestra los iconos de los menus en la Portada. Se debe mostrar ARRIBA para que recoja titulo de cualquier modulo. 
// Si solo se pone en el Centro solo muestra los iconos de menus para navegar hasta el modulo

global $PHP_SELF, $V_mod, $V_idmod, $RAD_dbi, $blocksoff, $SESSION_blocks_left, $SESSION_blocks_right, $dbname, $content, $V_idgrp;
$content="";

if ($dbname=="") $dbname=_DEF_dbname;

$blocksoff="x"; // en modo icono para tablet no deberia mostrar mas bloques que este
$SESSION_blocks_left="0"; $SESSION_blocks_right="0";

$TMP_theme=getSessionVar("SESSION_theme");
$TMP_lang=getSessionVar("SESSION_lang");

$TMP_URL="index.php?";
foreach($_REQUEST as $TMP_k=>$TMP_v) if ($TMP_v!="" && $TMP_k!="V_idgrp") $TMP_URL.=$TMP_k."=".$TMP_v."&amp;";

if ($V_idmod>0) { // pone el titulo y recoge el modulo padre
	$TMP_res=sql_query("SELECT * FROM modulos WHERE idmodulo='$V_idmod'", $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
        if (trim($TMP_row["grupomenu_".$TMP_lang])!="") $TMP_row[grupomenu]=$TMP_row["grupomenu_".$TMP_lang];
        if (trim($TMP_row["literalmenu_".$TMP_lang])!="") $TMP_row[literalmenu]=$TMP_row["literalmenu_".$TMP_lang];
	setSessionVar("SESSION_pagetitle",$title.". ".$TMP_row[grupomenu].". ".$TMP_row[literalmenu]);
	echo "
<script>
//document.getElementById('title').innerHTML+='. ".$TMP_row[grupomenu].". ".$TMP_row[literalmenu]."';
</script>
";
	return;
}

if ($V_idgrp>0) { // pone el titulo y recoge el modulo padre (FALTA: lo del Titulo mejor llevarlo al tema)
	$TMP_res=sql_query("SELECT * FROM modulos WHERE idmodulo='$V_idgrp'", $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
        if (trim($TMP_row["grupomenu_".$TMP_lang])!="") $TMP_row[grupomenu]=$TMP_row["grupomenu_".$TMP_lang];
	//setSessionVar("SESSION_pagetitle",$title.". ***++++** ".$TMP_row[grupomenu],0);

	echo "
<script>
//document.title='".$TMP_row[grupomenu].". '+document.title;
//document.getElementById('title').innerHTML+='. ".$TMP_row[grupomenu]."';
</script>
";
	if (trim($TMP_row[literalmenu])=="") $TMP_sql="SELECT * FROM modulos WHERE grupomenu=".converttosql($TMP_row[grupomenu]);
	else $TMP_sql="SELECT * FROM modulos WHERE grupomenu=".converttosql($TMP_row[literalmenu]);
} else {
	$TMP_sql="SELECT * FROM modulos WHERE (grupomenu IS NOT NULL AND grupomenu!='') and ((literalmenu is null or literalmenu='')or(literalmenu=grupomenu))";
}

if ($V_idmod>0 || ($V_mod!=""&&$V_mod!=" x")) return;
if (ereg("mapaweb.php",$PHP_SELF)) return;

global $RAD_device;
if ($RAD_device=="") $RAD_device=getSessionVar("SESSION_device");
$TMP_res=sql_query($TMP_sql." order by orden", $RAD_dbi);
while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
	if (!ereg(",".$RAD_device.",",",".$TMP_row[device].",") && $TMP_row[device]!="" && $RAD_device!="") continue;
        if ($TMP_row["activo"]=="0" || $TMP_row["visible"]=="0") continue;
        if (trim($TMP_row["icono_".$TMP_lang])!="") $TMP_row[icono]=$TMP_row["icono_".$TMP_lang];
        if (trim($TMP_row["grupomenu_".$TMP_lang])!="") $TMP_row[grupomenu]=$TMP_row["grupomenu_".$TMP_lang];
        if (trim($TMP_row["literalmenu_".$TMP_lang])!="") $TMP_row[literalmenu]=$TMP_row["literalmenu_".$TMP_lang];
        if ($V_idgrp>0 && $TMP_row["literalmenu"]=="") continue;
        if ($V_idgrp>0) $TMP_menu=$TMP_row[literalmenu];
        else $TMP_menu=$TMP_row[grupomenu];
	$TMP_nombregrupo=strip_tags(strtolower(str_replace(" ","",RAD_cambiaAcentos(html_entity_decode($TMP_row[grupomenu])))));
	$TMP_nombreitem=strip_tags(strtolower(str_replace(" ","",RAD_cambiaAcentos(html_entity_decode($TMP_row[literalmenu])))));
	$TMP_nombreitem=ereg_replace("[^[:alnum:]]", "", $TMP_nombreitem);
	$TMP_icon="";
	if (RAD_primerFich($TMP_row[icono])!="" && file_exists("files/".$dbname."/".RAD_primerFich($TMP_row[icono]))) $TMP_icon="files/".$dbname."/".RAD_primerFich($TMP_row[icono]);
	if ($TMP_icon=="" && !$V_idgrp>0) {
		if (file_exists("themes/".$TMP_theme."/icons/".$TMP_nombregrupo.".png")) $TMP_icon="themes/".$TMP_theme."/icons/".$TMP_nombregrupo.".png";
	}
	if ($TMP_icon=="" && $V_idgrp>0) {
		if (file_exists("themes/".$TMP_theme."/icons/".$TMP_nombreitem.".png")) $TMP_icon="themes/".$TMP_theme."/icons/".$TMP_nombreitem.".png";
	}
	$TMP_idgrp=htmlentities($TMP_row[idmodulo]);

	if (trim($TMP_row[_DBF_M_GROUPMENU])=="") continue;
	//if (trim($TMP_row2[_DBF_M_ITEMMENU])=="") continue;
	if ($TMP_row2[_DBF_M_VISIBLE]==='N' || $TMP_row[_DBF_M_VISIBLE]==='0') continue;
	if ($TMP_row2[_DBF_M_ACTIVE]==='N' || $TMP_row[_DBF_M_ACTIVE]==='0') continue;
	global $dbname, $SESSION_SID;
	if ($dbname!="" && $dbname!=_DEF_dbname) $xdbname="&dbname=$dbname";
	else $xdbname="";
	if (_DEF_INDEX!="" && _DEF_INDEX!="_DEF_INDEX") $TMP_INDEX=_DEF_INDEX;
	else $TMP_INDEX="index.php";
	if (is_user() && _DEF_ADD_URLTIME=="1") $TMP_time="&time=".time();
	$TMP_fichero=$TMP_row[_DBF_M_FILE];
	$TMP_enlace=$TMP_row[_DBF_M_LINK];
	$TMP_idmod=$TMP_row[_DBF_M_IDMODULE]; 
	$TMP_dir=$TMP_row[_DBF_M_DIR];
	if (!is_modulepermitted($TMP_idmod,$TMP_dir,$TMP_fichero)) continue;
	if (trim($TMP_row[url_seo])!="") {
		if (_DEF_DIR_ROOT!="" && _DEF_DIR_ROOT!="_DEF_DIR_ROT" && $TMP_row[url_seo]!="") $TMP_row[url_seo]=_DEF_DIR_ROOT.$TMP_row[url_seo];
		$TMP_URLprg=trim($TMP_row[url_seo]);
	} else if ($TMP_fichero!="") {
		$TMP_URLprg=$TMP_INDEX."?".$SESSION_SID."V_dir=$TMP_dir&amp;V_idmod=$TMP_idmod&amp;V_mod=".$TMP_fichero.$xdbname.$TMP_time;
	} else if ($TMP_enlace!="") {
		$TMP_URLprg=$TMP_INDEX."?".$SESSION_SID.$TMP_enlace.$xdbname.$TMP_time;
	} else {
		$TMP_URLprg=$TMP_INDEX."?".$SESSION_SID.$xdbname.$TMP_time."&amp;V_idgrp=".$TMP_idgrp;
	}

	$content.="<div class='menuicon' style='display:inline-block; margin:0px; padding:20px;'>";
	$content.="<a href='".$TMP_URLprg."'>";
	if ($TMP_icon!="") $content.="<img class='iconmenu' src='".$TMP_icon."' border=0 title='".strip_tags($TMP_menu)."'>";
	$content.="<br>".$TMP_menu;
	$content.="</a></div>\n";
	$content.="\n<!-- ".$TMP_nombreitem." -->";
}
if ($content!="") $content="<div class='grpmenuicon' style='margin:10px; text-align:center;'>\n".$content."</div>\n";
return;
?>
