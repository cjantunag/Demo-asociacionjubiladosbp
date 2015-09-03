<?php
	if (eregi(basename(__FILE__), $PHP_SELF))  die ("Security Error ...");
	
	include_once("modules/contents/lib.url_seo.functions.php");
	
	function obtener_url_seo_articulo($TMP_id,$load="false"){
		global $RAD_dbi, $SESSION_SID;
		
		if (defined('_DEF_DIR_ROOT')) $dir_root = _DEF_DIR_ROOT;
		else $dir_root = "";
		
		$array_subniveles = array();
		$subniveles = "";
		
		$url_seo = "";		
		
		if ((_DEF_MODREWRITE == "" || _DEF_MODREWRITE == "_DEF_MODREWRITE") && $load=="false") $url_seo = $dir_root."index.php?V_dir=contents&amp;V_mod=articulos&amp;id=".$TMP_id.$SESSION_SID;
		else {
			$TMP_sel = "SELECT * FROM articulos WHERE id=$TMP_id";
			$TMP_res = sql_query($TMP_sel, $RAD_dbi);
			$TMP_row = sql_fetch_array($TMP_res, $RAD_dbi);
			
			//Recoger posibles parametros opciones con su formato adecuado
			$modif_url_amigable = "";
			$TMP_sel_param = "SELECT * FROM param_url_amigables WHERE tabla='articulos' AND idtabla='id' AND idtablavalor=$TMP_id";
			$TMP_res_param = sql_query($TMP_sel_param, $RAD_dbi);
			$TMP_row_param = sql_fetch_array($TMP_res_param, $RAD_dbi);
			if ($TMP_row_param['parametros'] != '' ) {
				if ($TMP_row_param['formato'] == '_') {
					$separador_pagina = true;
					$modif_url_amigable='_'.$TMP_row_param["patron_rewrite"];
				}
				else if ($TMP_row_param['formato'] == '/') {
					$separador_pagina = false;
					$modif_url_amigable=$TMP_row_param["nom_pat_rewrite"].'/'.$TMP_row_param["patron_rewrite"];
				}
				
			}
			
			if($TMP_row["urlamigable"] != "") $url_seo = $dir_root.$TMP_row["urlamigable"];
			else {
				$pagina = limpiarURL($TMP_row["nombre"]);			
				//Recogemos el nombre de la seccion y recorremos hasta su nivel.			
				if ($TMP_row['idseccion'] != "") {
					$TMP_sel_seccion = "SELECT * FROM articulossecciones WHERE id=".$TMP_row['idseccion'];
					$TMP_res_seccion = sql_query($TMP_sel_seccion, $RAD_dbi);
					$TMP_row_seccion = sql_fetch_array($TMP_res_seccion, $RAD_dbi);			
					$seccion = limpiarURL($TMP_row_seccion["nombre"]);
				} else $seccion = "";
				
				while ($TMP_row["idartpadre"] !="") {
					$TMP_sel = "SELECT * FROM articulos WHERE id=".$TMP_row["idartpadre"];
					$TMP_res = sql_query($TMP_sel, $RAD_dbi);
					$TMP_row = sql_fetch_array($TMP_res, $RAD_dbi);
					$array_subniveles[] = limpiarURL($TMP_row["nombre"]);
				}			
				//Al terminar con el bucle tengo en el array la estructura inversa de navegacion
				$count = count($array_subniveles);
				for ($i = $count-1; $i >= 0; $i--) {
					$subniveles .= $array_subniveles[$i]._DEF_MODREWRITE_SEP;
				}
				//Ruta final es: seccion/subniveles.../pagina.html
				if ($modif_url_amigable != "") {
					if ($separador_pagina == true) $pagina .= $modif_url_amigable;
					else $pagina = $modif_url_amigable._DEF_MODREWRITE_SEP.$pagina;
				}
				if ($seccion != "" ) $url_seo = $dir_root.$seccion._DEF_MODREWRITE_SEP.$subniveles.$pagina.".htm";
				else $url_seo = $dir_root.$subniveles.$pagina.".htm";
			}		
		}
		return $url_seo;
	}
	
	
	function obtener_url_articulo_sectores($TMP_id, $TMP_idsector){
		
		if (defined('_DEF_DIR_ROOT')) $dir_root = _DEF_DIR_ROOT;
		else $dir_root = "";
		
		global $RAD_dbi, $SESSION_SID;
		$array_subniveles = array();
		$subniveles = "";
		$url_seo = "";
		if (_DEF_MODREWRITE == "" || _DEF_MODREWRITE == "_DEF_MODREWRITE") $url_seo = $dir_root."index.php?V_dir=contents&amp;V_mod=articulos&amp;id=".$TMP_id.'&amp;idsector='.$TMP_idsector.$SESSION_SID;
		else {
			$TMP_sel = "SELECT * FROM articulos WHERE id=$TMP_id";
			$TMP_res = sql_query($TMP_sel, $RAD_dbi);
			$TMP_row = sql_fetch_array($TMP_res, $RAD_dbi);
			
			//Recoger posibles parametros opciones con su formato adecuado
			$modif_url_amigable = '_'.$TMP_idsector;
			$pagina = limpiarURL($TMP_row["nombre"]);			
			//Recogemos el nombre de la seccion y recorremos hasta su nivel.			
			if ($TMP_row['idseccion'] != "") {
				$TMP_sel_seccion = "SELECT * FROM articulossecciones WHERE id=".$TMP_row['idseccion'];
				$TMP_res_seccion = sql_query($TMP_sel_seccion, $RAD_dbi);
				$TMP_row_seccion = sql_fetch_array($TMP_res_seccion, $RAD_dbi);			
				$seccion = limpiarURL($TMP_row_seccion["nombre"]);
			} else $seccion = "";
			
			while ($TMP_row["idartpadre"] !="") {
				$TMP_sel = "SELECT * FROM articulos WHERE id=".$TMP_row["idartpadre"];
				$TMP_res = sql_query($TMP_sel, $RAD_dbi);
				$TMP_row = sql_fetch_array($TMP_res, $RAD_dbi);
				$array_subniveles[] = limpiarURL($TMP_row["nombre"]);
			}			
			//Al terminar con el bucle tengo en el array la estructura inversa de navegacion
			$count = count($array_subniveles);
			for ($i = $count-1; $i >= 0; $i--) {
				$subniveles .= $array_subniveles._DEF_MODREWRITE_SEP;
			}
			
			$pagina .= $modif_url_amigable;						
			if ($seccion != "" ) $url_seo = $dir_root.$seccion._DEF_MODREWRITE_SEP.$subniveles.$pagina.".htm";
			else $url_seo = $dir_root.$subniveles.$pagina.".htm";
		}
		return $url_seo;
	}
	
	function obtener_url_articulo_idfinal($TMP_id, $TMP_idnom, $TMP_idvalue) {
		
		if (defined('_DEF_DIR_ROOT')) $dir_root = _DEF_DIR_ROOT;
		else $dir_root = "";
		
		global $RAD_dbi, $SESSION_SID;
		$array_subniveles = array();
		$subniveles = "";
		$url_seo = "";
		if (_DEF_MODREWRITE == "" || _DEF_MODREWRITE == "_DEF_MODREWRITE") $url_seo = $dir_root."index.php?V_dir=contents&amp;V_mod=articulos&amp;id=".$TMP_id.'&amp;'.$TMP_idnom.'='.$TMP_idvalue.$SESSION_SID;
		else {
			$TMP_sel = "SELECT * FROM articulos WHERE id=$TMP_id";
			$TMP_res = sql_query($TMP_sel, $RAD_dbi);
			$TMP_row = sql_fetch_array($TMP_res, $RAD_dbi);
			
			//Recoger posibles parametros opciones con su formato adecuado
			$modif_url_amigable = '_'.$TMP_idvalue;
			$pagina = limpiarURL($TMP_row["nombre"]);			
			//Recogemos el nombre de la seccion y recorremos hasta su nivel.			
			if ($TMP_row['idseccion'] != "") {
				$TMP_sel_seccion = "SELECT * FROM articulossecciones WHERE id=".$TMP_row['idseccion'];
				$TMP_res_seccion = sql_query($TMP_sel_seccion, $RAD_dbi);
				$TMP_row_seccion = sql_fetch_array($TMP_res_seccion, $RAD_dbi);			
				$seccion = limpiarURL($TMP_row_seccion["nombre"]);
			} else $seccion = "";
			
			while ($TMP_row["idartpadre"] !="") {
				$TMP_sel = "SELECT * FROM articulos WHERE id=".$TMP_row["idartpadre"];
				$TMP_res = sql_query($TMP_sel, $RAD_dbi);
				$TMP_row = sql_fetch_array($TMP_res, $RAD_dbi);
				$array_subniveles[] = limpiarURL($TMP_row["nombre"]);
			}			
			//Al terminar con el bucle tengo en el array la estructura inversa de navegacion
			$count = count($array_subniveles);
			for ($i = $count-1; $i >= 0; $i--) {
				$subniveles .= $array_subniveles._DEF_MODREWRITE_SEP;
			}
			
			$pagina .= $modif_url_amigable;						
			if ($seccion != "" ) $url_seo = $dir_root.$seccion._DEF_MODREWRITE_SEP.$subniveles.$pagina.".htm";
			else $url_seo = $dir_root.$subniveles.$pagina.".htm";
		}
		return $url_seo;
	}
	
	
?>