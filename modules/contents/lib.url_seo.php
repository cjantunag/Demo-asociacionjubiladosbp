<?php
	if (eregi(basename(__FILE__), $PHP_SELF))  die ("Security Error ...");
	
	include_once("modules/contents/lib.url_seo_articulos.php");
	include_once("modules/contents/lib.url_seo_modulos.php");
	include_once("modules/contents/lib.url_seo.functions.php");
	
	
	function loadurls($load="false"){
		global $dbname,$RAD_dbi;
		if ($dbname=="") $dbname=_DEF_dbname;		
		
		$cmd = "TRUNCATE urls_amigables";		
		sql_query($cmd, $RAD_dbi);
		
		$contarts = loadurlsart($load);
		$contmods = loadurlsmod($load);
		
		return($contarts + $contmods);
	}
	
	function loadurlsart($load="false"){
		global $dbname,$RAD_dbi;
		if ($dbname=="") $dbname=_DEF_dbname;
		
		$cont = 0;		
		$cadsample_patron = '&<!--param-->=$<!--paramvalue-->';
		$TMP_sel = "SELECT * FROM articulos";
		$TMP_res = sql_query($TMP_sel, $RAD_dbi);
		while ($TMP_row = sql_fetch_array($TMP_res, $RAD_dbi)) {
			$TMP_sel_param = "SELECT * FROM param_url_amigables WHERE tabla='articulos' AND idtabla='id' AND idtablavalor=".$TMP_row['id'];
			$TMP_res_param = sql_query($TMP_sel_param, $RAD_dbi);
			$TMP_row_param = sql_fetch_array($TMP_res_param, $RAD_dbi);
			$param_var = "";			
			if ($TMP_row_param['nom_pat_rewrite'] != "") {				
				$A_param=explode(";",$TMP_row_param['nom_pat_rewrite']);				
				for ($i = 0; $i < count($A_param); $i++) {					
					$param_var .= $cadsample_patron;
					$param_var = str_replace("<!--param-->",$A_param[$i],$param_var);
					$param_var = str_replace("<!--paramvalue-->",$i+1,$param_var);					
				}
			}		
			$url_real = "index.php?V_dir=contents&V_mod=articulos&id=".$TMP_row['id'].$param_var;
			$url_amigable = obtener_url_seo_articulo($TMP_row['id'],$load);
			//Hay que eliminar el primer caracter que es una /
			$url_amigable = substr($url_amigable,1);
			$cmd = "INSERT INTO urls_amigables (idpagina, urlreal, urlamigable, activa) VALUES (".$TMP_row['id'].", '$url_real', '$url_amigable', 0) ";
			sql_query($cmd, $RAD_dbi);			
			$cont++;
		}		
		return $cont;
	}
	
	
	function loadurlsmod($load="false"){		
		global $dbname,$RAD_dbi;
		if ($dbname=="") $dbname=_DEF_dbname;
		
		$cont = 0;		
		$TMP_sel = "SELECT * FROM modulos WHERE activo=1";
		$TMP_res = sql_query($TMP_sel, $RAD_dbi);
		while ($TMP_row = sql_fetch_array($TMP_res, $RAD_dbi)) {
			list($amigable,$url_amigable,$url_real) = obtener_url_seo_modulo_id($TMP_row['idmodulo'],$load);
			if ($amigable == true ) {
				//Hay que eliminar el primer caracter que es una /
				$url_amigable = substr($url_amigable,1);
				$cmd = "INSERT INTO urls_amigables (idpagina, urlreal, urlamigable, activa) VALUES (".$TMP_row['idmodulo'].", '$url_real', '$url_amigable', 0) ";
				sql_query($cmd, $RAD_dbi);			
				$cont++;
			}
		}
		return($cont);
	}
	
	function generatehtacces(){
		global $dbname,$RAD_dbi;
		if ($dbname=="") $dbname=_DEF_dbname;
		
		//Recoger el fichero .htacces para reescribirlo, manteniendo la configuracion hasta el ##MODREWRITE##
		$vlineas = file(".htaccess");		
		$htaccess = fopen(".htaccess","w"); 		
		foreach ($vlineas as $sLinea)  { 
			//echo $sLinea.'<br />';
			if (eregi(substr($sLinea,0,14),"##MODREWRITE##")) {
				fputs($htaccess,$sLinea);				
				fputs($htaccess, "\r\n");				
				break;				
			} else 	fputs($htaccess,$sLinea);			
		}
		
		$TMP_sel = "SELECT * FROM urls_amigables";
		$TMP_res = sql_query($TMP_sel, $RAD_dbi);
		while($TMP_row = sql_fetch_array($TMP_res, $RAD_dbi)) {
			$RewriteRule = 'RewriteRule ^'.$TMP_row['urlamigable'].'$ '.$TMP_row['urlreal'].' [L,NC]';
			fputs($htaccess,$RewriteRule);
			
			fputs($htaccess, "\n");
			$cmd = "UPDATE urls_amigables SET activa=1 WHERE id=".$TMP_row['id'];
			sql_query($cmd, $RAD_dbi);
		}
		fclose($htaccess);		
		return ("<p>Proceso terminado correctamente</p>");
	}
?>