<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

//---------------------------------------------------------------------------------------------------------------	
function creahtacces(){
	global $dbname,$RAD_dbi;
	if ($dbname=="") $dbname=_DEF_dbname;
		
	$cont = 0;

	$TMP_sel = "SELECT * FROM proyectoscategorias";
	$TMP_res = sql_query($TMP_sel, $RAD_dbi);
	while ($TMP_row = sql_fetch_array($TMP_res, $RAD_dbi)) {
		$url_real = "index.php?V_dir=MSC&V_mod=showproyecto&idproyectocategoria=".$TMP_row[idproyectocategoria]."&newlang=spanish";
		$url_seo = trim($TMP_row[url_seo]);
		if ($url_seo=="") continue;
		$A_url_seo[$cont]=$url_seo;
		$A_url_real[$cont]=$url_real;
		$cont++;
		$url_real = "index.php?V_dir=MSC&V_mod=showproyecto&idproyectocategoria=".$TMP_row[idproyectocategoria]."&newlang=galician";
		$url_seo = trim($TMP_row[url_seo_galician]);
		if ($url_seo=="") continue;
		$A_url_seo[$cont]=$url_seo;
		$A_url_real[$cont]=$url_real;
		$cont++;
	}

	$TMP_sel = "SELECT * FROM proyectos";
	$TMP_res = sql_query($TMP_sel, $RAD_dbi);
	while ($TMP_row = sql_fetch_array($TMP_res, $RAD_dbi)) {
		$url_real = "index.php?V_dir=MSC&V_mod=showproyecto&idproyecto=".$TMP_row[idproyecto]."&newlang=spanish";
		$url_seo = trim($TMP_row[url_seo]);
		if ($url_seo=="") continue;
		$A_url_seo[$cont]=$url_seo;
		$A_url_real[$cont]=$url_real;
		$cont++;
		$url_real = "index.php?V_dir=MSC&V_mod=showproyecto&idproyecto=".$TMP_row[idproyecto]."&newlang=galician";
		$url_seo = trim($TMP_row[url_seo_galician]);
		if ($url_seo=="") continue;
		$A_url_seo[$cont]=$url_seo;
		$A_url_real[$cont]=$url_real;
		$cont++;
	}

	$TMP_sel = "SELECT * FROM acciones";
	$TMP_res = sql_query($TMP_sel, $RAD_dbi);
	while ($TMP_row = sql_fetch_array($TMP_res, $RAD_dbi)) {
		$url_real = "index.php?V_dir=MSC&V_mod=showaccion&idaccion=".$TMP_row[idaccion]."&newlang=spanish";
		$url_seo = trim($TMP_row[url_seo]);
		if ($url_seo=="") continue;
		$A_url_seo[$cont]=$url_seo;
		$A_url_real[$cont]=$url_real;
		$cont++;
		$url_real = "index.php?V_dir=MSC&V_mod=showaccion&idaccion=".$TMP_row[idaccion]."&newlang=galician";
		$url_seo = trim($TMP_row[url_seo_galician]);
		if ($url_seo=="") continue;
		$A_url_seo[$cont]=$url_seo;
		$A_url_real[$cont]=$url_real;
		$cont++;
	}
	
	$TMP_sel = "SELECT * FROM modulos WHERE activo=1";

	$TMP_sel = "SELECT * FROM articulos";
	$TMP_res = sql_query($TMP_sel, $RAD_dbi);
	while ($TMP_row = sql_fetch_array($TMP_res, $RAD_dbi)) {
		$url_real = "index.php?V_dir=MSC&V_mod=showart&id=".$TMP_row[id]."&newlang=spanish";
		$url_seo = trim($TMP_row[url_seo]);
		if ($url_seo=="") continue;
		$A_url_seo[$cont]=$url_seo;
		$A_url_real[$cont]=$url_real;
		$cont++;
		$url_real = "index.php?V_dir=MSC&V_mod=showart&id=".$TMP_row[id]."&newlang=galician";
		$url_seo = trim($TMP_row[url_seo_galician]);
		if ($url_seo=="") continue;
		$A_url_seo[$cont]=$url_seo;
		$A_url_real[$cont]=$url_real;
		$cont++;
	}
	
	$TMP_sel = "SELECT * FROM modulos WHERE activo=1";
	$TMP_res = sql_query($TMP_sel, $RAD_dbi);
	while ($TMP_row = sql_fetch_array($TMP_res, $RAD_dbi)) {
		$url_real = "index.php?V_dir=".$TMP_row[directorio]."&V_mod=".$TMP_row[fichero]."&V_idmod=".$TMP_row[idmodulo]."&newlang=spanish";
		$url_seo = trim($TMP_row[url_seo]);
		if ($url_seo=="") continue;
		$A_url_seo[$cont]=$url_seo;
		$A_url_real[$cont]=$url_real;
		$cont++;
		$url_real = "index.php?V_dir=".$TMP_row[directorio]."&V_mod=".$TMP_row[fichero]."&V_idmod=".$TMP_row[idmodulo]."&newlang=galicia";
		$url_seo = trim($TMP_row[url_seo_galician]);
		if ($url_seo=="") continue;
		$A_url_seo[$cont]=$url_seo;
		$A_url_real[$cont]=$url_real;
		$cont++;
	}

	if ($cont==0) return ("No hay URL Seo para crear");
	// Reescribe el fichero .htacces manteniendo la configuracion hasta el ##MODREWRITE##
	$vlineas = file(".htaccess");
	unlink(".htaccess");
	$htaccess = fopen(".htaccess","w");
	$haylinmodrewrite=false;
	$haylinrewriteengine=false;
	foreach ($vlineas as $sLinea)  { 
		//echo $sLinea.'<br />';
		if (eregi("RewriteEngine on",$sLinea)) {
			$haylinrewriteengine=true;
		}
		if (eregi("##MODREWRITE",$sLinea)) {
			$haylinmodrewrite=true;
		}
		if ($haylinmodrewrite==false) {
			fputs($htaccess,$sLinea);
		}
	}
		
	if ($haylinrewriteengine==false) fputs($htaccess, "\nRewriteEngine on\n");
	fputs($htaccess, "##MODREWRITE (don't edit after this line)##\n");
	$TMP_cont=0;
	foreach ($A_url_seo as $TMP_idx=>$TMP_url_seo) { 
		$TMP_cont++;
		$TMP_url_seo=utf8_encode(str_replace(" ","_",$TMP_url_seo));
		$TMP_url_real=$A_url_real[$TMP_idx];
		$RewriteRule = 'RewriteRule ^'.$TMP_url_seo.'$ '.$TMP_url_real.' [L,QSA]';
		fputs($htaccess,$RewriteRule."\n");
	}
	fputs($htaccess, "\n");
	fclose($htaccess);		
	return ("$TMP_cont urls.<br>htaccess creado correctamente");
}
?>
