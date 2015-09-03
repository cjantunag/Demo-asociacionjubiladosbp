<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");
global $RAD_dbi;

$TMP_url_seo=false;
if ($tablename!="") {
	$TMP_res=sql_query("SELECT * FROM ".$tablename." LIMIT 0,1", $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
	foreach($TMP_row as $TMP_k=>$TMP_v) {
		if ($TMP_k=="0") continue;
		if ($TMP_k=="url_seo") $TMP_url_seo=true;
	}
}
if($TMP_url_seo==true) {
	RAD_addField(4,"url_seo","canbenull=true,name=url_seo,title='URL Seo',length=255,ilength=40,type=string,dtype=stand,nonew=false,noedit=false,noupdate=false,noinsert=false,nodetail=false,noinsert=false,noprint=false,browsable=true,orderby=true");
}

if ($op=="genhtaccess") {
	if (file_exists("modules/$V_dir/common.app."._DEF_appname.".php")) include_once ("modules/$V_dir/common.app."._DEF_appname.".php");
	echo "<br><ul><h1> Genera htaccess</h1><br>Crea lineas MODREWRITE de URL SEO. Resultado:<ul><br><b>";
	include_once("modules/".$V_dir."/lib.url_seo.creahtaccess.php");
	$TMP_res=creahtacces();
	die($TMP_res);
}

$_REQUEST["id"]=addslashes($_REQUEST["id"]); // evita SQL injection en where de query de contenido
$id=addslashes($id);

if (($V_mod=="editwork")&&($func=="insert"||$func=="update")) $V0_idpadre=$V0_newidpadre;
if (!isset($RAD_dbi)) $RAD_dbi = sql_connect(_DEF_dbhost, _DEF_dbuname, _DEF_dbpass, $dbname);

if ($V_mod=="editcontents" || $V_mod=="editnews" || $V_mod=="editwork" || $V_mod=="editworkfolder" || $V_mod=="editforum" || $V_mod=="editdict" || $V_mod=="edithelp") {
	$TMP_autor=trim(base64_decode($HTTP_SESSION_VARS["SESSION_user"]));
	$fields[$findex[autor]]->vdefault=$TMP_autor;
	if (!is_admin()) {
		if ($defaultfilter!="") $defaultfilter.=" AND ";
		$defaultfilter.="(autor='' OR autor IS NULL OR autor='$TMP_autor')";
	}
}

if (($V_mod=="contents" || $V_mod=="news" || $V_mod=="work" || $V_mod=="forum" || $V_mod=="dict" || $V_mod=="help")&&($idcat=="")) {
    if ($asignatura!="") {
	$URLROI.="&asignatura=$asignatura&type=$type";
	$tabURLROI.="&asignatura=$asignatura&type=$type";
	$FORMROI.="<input type=hidden name=asignatura value='$asignatura'><input type=hidden name=type value='$type'>";
	$TMP_result = sql_query("SELECT * FROM categorias WHERE otrosperfiles LIKE '%,as:".$asignatura.",%' AND categoria='$type'", $RAD_dbi);
	$TMP_row = sql_fetch_array($TMP_result, $RAD_dbi);
	$idcat=$TMP_row[id];
    }
    if ($cursillo!="" && $idcat=="") {
	$URLROI.="&cursillo=$cursillo&type=$type";
	$tabURLROI.="&cursillo=$cursillo&type=$type";
	$FORMROI.="<input type=hidden name=cursillo value='$cursillo'><input type=hidden name=type value='$type'>";
	$TMP_result = sql_query("SELECT * FROM categorias WHERE otrosperfiles LIKE '%,cu:".$cursillo.",%' AND categoria='$type'", $RAD_dbi);
	$TMP_row = sql_fetch_array($TMP_result, $RAD_dbi);
	$idcat=$TMP_row[id];
    }
    if ($otrosperf!="" && $idcat=="") {
	$TMP_result = sql_query("SELECT * FROM categorias WHERE otrosperfiles LIKE '%,".$otrosperf.",%' AND categoria='$type'", $RAD_dbi);
	$TMP_row = sql_fetch_array($TMP_result, $RAD_dbi);
	$idcat=$TMP_row[id];
    }
}

if ($tablename == "categorias") {
	$TMP_autor=trim(base64_decode($HTTP_SESSION_VARS["SESSION_user"]));
	$TMP_userprofile=trim(getSessionVar("SESSION_profiles"));
	$defaultfilter=" (";
//	if ($TMP_autor!="") $defaultfilter.=" otrosperfiles LIKE '%,".$TMP_autor.",%' OR ";
//	$defaultfilter.=" otrosperfiles='' OR otrosperfiles IS NULL) AND (";
	if ($otrosperf!="") { 
		$defaultfilter.=" otrosperfiles LIKE '%,".$otrosperf.",%' OR ";
		$defaultfilter.=" otrosperfiles='' OR otrosperfiles IS NULL) AND (";
	}
	$params = explode(",", $TMP_userprofile);
	if ($TMP_userprofile!="") {
		if (count($params)>0) {
			foreach ($params as $ky=>$val) {
				$val=str_replace(",", "", $val);
				if ($val!="") $defaultfilter.=" perfiles LIKE '%".$val."%' OR ";
			}
		} else {
			$defaultfilter.=" perfiles LIKE '%".$TMP_userprofile."%' OR ";
		}
	}
	$defaultfilter.=" perfiles='' OR perfiles IS NULL)";
}

if ($V_mod=="contents" || $V_mod=="news" || $V_mod=="work" || $V_mod=="forum" || $V_mod=="dict" || $V_mod=="help") {
	$TMP_autor=trim(base64_decode($HTTP_SESSION_VARS["SESSION_user"]));
	$TMP_userprofile=trim(getSessionVar("SESSION_profiles"));
	$defaultfiltercat=" (";
//	if ($TMP_autor!="") $defaultfiltercat.=" otrosperfiles LIKE '%,".$TMP_autor.",%' OR ";
//	$defaultfiltercat.=" otrosperfiles='' OR otrosperfiles IS NULL) AND (";
//	if ($otrosperf!="") {
//		$defaultfiltercat.=" otrosperfiles LIKE '%,".$otrosperf.",%' OR ";
//		$defaultfiltercat.=" otrosperfiles='' OR otrosperfiles IS NULL) AND (";
//	}
	$params = explode(",", $TMP_userprofile);
	if ($TMP_userprofile!="") {
		if (count($params)>0) {
			foreach ($params as $ky=>$val) {
				$val=str_replace(",", "", $val);
				if ($val!="") $defaultfiltercat.=" perfiles LIKE '%".$val."%' OR ";
			}
		} else {
			$defaultfiltercat.=" perfiles LIKE '%".$TMP_userprofile."%' OR ";
		}
	}
	$defaultfiltercat.=" perfiles='' OR perfiles IS NULL)";
	if ($type!="") $defaultfiltercat.=" AND categoria='$type'";
}

if (($V_mod=="news" || $V_mod=="forum")&&($idcat=="")&&($otrosperf!="")) {
 	$defaultfiltercat=" otrosperfiles LIKE '%,".$otrosperf.",%' AND categoria='".$V_mod."'";
//	$TMP_result = sql_query("SELECT * FROM categorias WHERE ".$defaultfiltercat, $RAD_dbi);
//	$TMP_row = sql_fetch_array($TMP_result, $RAD_dbi);
//	$idcat=$TMP_row[id];
//	$defaultfiltercat="";
}


if ($V_mod=="editbolsa") {
	if ($idcat=="") {
		$TMP_result = sql_query("SELECT * FROM categorias WHERE categoria='bolsa'", $RAD_dbi);
		$TMP_row = sql_fetch_array($TMP_result, $RAD_dbi);
		$idcat=$TMP_row[id];
		$TMP_defaultfilter=" idcat='$idcat'";
		if ($defaultfilter=="") $defaultfilter=$TMP_defaultfilter;
		else $defaultfilter.=" AND ".$TMP_defaultfilter;
	}
}


?>
