<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");
// Graba datos de formulario en tabla
// El formulario de entrada es el articulo "idin" y el mostrado a la salida es "idout". La tabla es "tabla"

global $V_mod, $RAD_dbi, $dbname, $blocksoff, $op, $V_dir, $V_idmod, $id;
if ($dbname=="") $dbname=_DEF_dbname;

$TMP_lang=getSessionVar("SESSION_lang");

$TMP_ses_code=getSessionVar("securimage_code_value");
$TMP_req_code=$_REQUEST["captcha_code"];
if (strtoupper($TMP_req_code)!=strtoupper($TMP_ses_code) && $TMP_ses_code!="") {
	foreach($_REQUEST as $TMP_k=>$TMP_v) {
		if ($TMP_k=="captcha_code") error(_DEF_NLSSecImgErr);
	}
}

//foreach($_REQUEST as $TMP_k=>$TMP_v) echo $TMP_k."=".$TMP_v."<br>";
//die();

global $TMP_secu;
include_once("modules/".$V_dir."/lib.secu.php");

if (file_exists("modules/".$V_dir."/common.app.php")) include ("modules/".$V_dir."/common.app.php");

$V_mod="articulos";

if ($dbname=="") $dbname=_DEF_dbname;

include_once("header.php");

if ($save=="" && $op=="" && $acepto=="") {
	$id=$idin;
} else {
	//foreach($_REQUEST as $TMP_k=>$TMP_v) echo $TMP_k."=".$TMP_v."<br>";
	$id=$idout;
	include_once("modules/".$V_dir."/lib.savereg.php");
	savereg($tabla,$_REQUEST[asunto]);
}

$TMP_optsano="";
$anohoy=date("Y");
for($ki=$anohoy-18; $ki>$anohoy-100; $ki--) {
	$TMP_optsano.="<option value='".$ki."'>".$ki."</option>";
}

$TMP_optsproy="";
$TMP_optsproyall="";
$TMP_optsproyvol="";
$TMP_res=sql_query("SELECT * FROM proyectos order by proyecto", $RAD_dbi);
while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
	$TMP_proyecto=$TMP_row[proyecto];
	foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
	$TMP_optsproyall.="<option value='".str_replace("'","`",$TMP_proyecto)."'>".$TMP_row[proyecto]."</option>";
	if ($TMP_row[aceptavoluntarios]=="1") $TMP_optsproyvol.="<option value='".str_replace("'","`",$TMP_proyecto)."'>".$TMP_row[proyecto]."</option>";
	if ($TMP_row[aceptadonaciones]=="1") $TMP_optsproy.="<option value='".str_replace("'","`",$TMP_proyecto)."'>".$TMP_row[proyecto]."</option>";
}

$TMP_optsprov="";
$TMP_res=sql_query("SELECT * FROM provincias order by provincia", $RAD_dbi);
while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
	$TMP_provincia=$TMP_row[provincia];
	foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
	$TMP_optsprov.="<option value='".str_replace("'","`",$TMP_provincia)."'>".$TMP_row[provincia]."</option>";
}

$TMP_optspais="";
$TMP_res=sql_query("SELECT * FROM paises order by pais", $RAD_dbi);
while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
	$TMP_pais=$TMP_row[pais];
	foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
	if (strtolower(substr($TMP_row[pais],0,4))=="espa") $TMP_optspais.="<option selected value='".str_replace("'","`",$TMP_pais)."'>".$TMP_row[pais]."</option>";
	else $TMP_optspais.="<option value='".str_replace("'","`",$TMP_pais)."'>".$TMP_row[pais]."</option>";
}

$TMP_res=sql_query("SELECT * FROM articulos where id='$id'", $RAD_dbi);
$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
$TMP_row[contenido]=str_replace("<!-- securimage -->",$TMP_secu,$TMP_row[contenido]);
$TMP_row[contenido]=str_replace("<!-- nombre -->",$_REQUEST[nombre],$TMP_row[contenido]);
$TMP_row[contenido]=str_replace("<!-- V_idmod -->",$V_idmod,$TMP_row[contenido]);
$TMP_row[contenido]=str_replace("<!-- optsprov -->",$TMP_optsprov,$TMP_row[contenido]);
$TMP_row[contenido]=str_replace("<!-- optsano -->",$TMP_optsano,$TMP_row[contenido]);
$TMP_row[contenido]=str_replace("<!-- optsproyall -->",$TMP_optsproyall,$TMP_row[contenido]);
$TMP_row[contenido]=str_replace("<!-- optsproyvol -->",$TMP_optsproyvol,$TMP_row[contenido]);
$TMP_row[contenido]=str_replace("<!-- optsproy -->",$TMP_optsproy,$TMP_row[contenido]);
$TMP_row[contenido]=str_replace("<!-- optspais -->",$TMP_optspais,$TMP_row[contenido]);

echo $TMP_row[contenido];

include_once("modules/".$V_dir."/lib.ajax.php");

include_once("footer.php");
return;
?>
