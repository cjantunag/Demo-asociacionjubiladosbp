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
	echo "<br><ul><h1> Genera htaccess</h1><br>Crea lineas MODREWRITE de URL SEO. Resultado:<ul><br><b>";
	include_once("modules/".$V_dir."/lib.url_seo.creahtaccess.php");
	$TMP_res=creahtacces();
	die($TMP_res);
}

?>
