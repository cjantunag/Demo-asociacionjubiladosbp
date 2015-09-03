<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");
global $RAD_dbi, $V_prevfunc, $V_dir;

if (getSessionVar("SESSION_lang")=="galician") {
	$EXTRA[profesion]="profesiones:profesion:profesion_galician"; // para evitar traducir a gallego el campo clave grabado en la tabla, y que se pueda buscar y encontrar por el valor en castellano
	$fields[$findex[profesion]]->extra="profesiones:profesion:profesion_galician";
}

//sql_query("SET NAMES utf8 COLLATE utf8_spanish_ci", $RAD_dbi);
sql_query("SET NAMES utf8", $RAD_dbi);

if (!is_admin()) $RAD_menudelete="x";

/////foreach($_REQUEST as $TMP_k=>$TMP_v) if (!is_array($TMP_k) && !is_array($TMP_v)) $_REQUEST[$TMP_k]=utf8_encode($TMP_v);

if ($tablename=="proyectoscategorias" || $tablename=="proyectos" || $tablename=="acciones" || $tablename=="modulos" || $tablename=="articulos" || $tablename=="GIE_articulosfamilias") {
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
	RAD_addField(4,"url_seo","canbenull=true,name=url_seo,title='URL Amigable',length=255,ilength=40,type=string,dtype=stand,nonew=false,noedit=false,noupdate=false,noinsert=false,nodetail=false,noinsert=false,noprint=false,browsable=true,orderby=true");
  }
}

if ($op=="genhtaccess") {
	echo "<br><ul><h1> Genera htaccess</h1><br>Crea lineas MODREWRITE de URL SEO. Resultado:<ul><br><b>";
	include_once("modules/".$V_dir."/lib.creahtaccess.php");
	$TMP_res=creahtacces();
	die($TMP_res);
}
if ($V_prevfunc=="update" || $V_prevfunc=="insert") {
	$TMP_urlseo=RAD_lookup($tablename,"url_seo",$idname0,$par0);
	if ($TMP_urlseo!="") {
		include_once("modules/".$V_dir."/lib.creahtaccess.php");
		$TMP_res=creahtacces();
		//alert("Regenera htaccess");
	}
}
?>
