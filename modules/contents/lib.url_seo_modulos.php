<?php
	if (eregi(basename(__FILE__), $PHP_SELF))  die ("Security Error ...");
	
	include_once("modules/contents/lib.url_seo.functions.php");
	
	function obtener_url_seo_modulo_link($link_default){
		global $RAD_dbi, $SESSION_SID;
		
		if (defined('_DEF_DIR_ROOT')) $dir_root = _DEF_DIR_ROOT;
		else $dir_root = "";		
		
		if (_DEF_MODREWRITE == "" || _DEF_MODREWRITE == "_DEF_MODREWRITE") $url_seo = $dir_root.$link_default;
		else {
		
			$array_subniveles = array();
			$subniveles = "";
		
			$link_parametros = str_replace('index.php?','',$link_default);
			$link_parametros = str_replace('index.php?','',$link_parametros);
			$A_parametros = explode("&amp;",$link_parametros);				
			for($i=0; $i<count($A_parametros); $i++){
				$A_parametros[$i] = str_replace('&','',$A_parametros[$i]);
				$A_param = explode("=",$A_parametros[$i]);				
				if($A_param[0]=='V_dir') $TMP_dir = $A_param[1];
				if($A_param[0]=='V_mod') $TMP_mod = $A_param[1];
				if($A_param[0]=='V_idmod') $TMP_idmod = $A_param[1];		
			}			
			//Construir la url amigable de los módulos
			if ($TMP_idmod != "") $TMP_sel="SELECT * FROM modulos WHERE idmodulo=$TMP_idmod";
			else $TMP_sel="SELECT * FROM modulos WHERE directorio = '$TMP_dir' AND fichero='$TMP_mod'";			
			$TMP_res = sql_query($TMP_sel,$RAD_dbi);
			while ($TMP_row= sql_fetch_array($TMP_res,$RAD_dbi)) {
				if ($TMP_row['urlamigable'] != "") $url_seo = $dir_root.$TMP_row["urlamigable"];
				else {					
					//Obtener si se puede la url a partir del grupo menu e item menu
					if ( ($TMP_row["literalmenu"] != "") && ($TMP_row["grupomenu"] != "") ) {
						$pagina=limpiarURL($TMP_row["literalmenu"]);						
						//$array_subniveles[] = limpiarURL($TMP_row["grupomenu"]);
						while($TMP_row['literalmenu'] != "") {
							$itempadre = $TMP_row["grupomenu"];
							$array_subniveles[] = limpiarURL($TMP_row["grupomenu"]);
							$TMP_sel = "SELECT * FROM modulos WHERE (grupomenu='$itempadre' OR literalmenu='$itempadre') AND fichero='' ";
							$TMP_res = sql_query($TMP_sel,$RAD_dbi);
							$TMP_row = sql_fetch_array($TMP_res, $RAD_dbi);
						}
						//Al terminar con el bucle tengo en el array la estructura inversa de navegacion
						$count = count($array_subniveles);
						for ($i = $count-1; $i >= 0; $i--) {
							$subniveles .= $array_subniveles[$i]._DEF_MODREWRITE_SEP;
						}
						$url_seo=$dir_root.$subniveles.$pagina.".htm";
					} else {
						$url_seo = $dir_root.$link_default;
					}
				}			
				break;//Si hay varias entradas del mismo módulo y estamos en el caso de que no tenemos el idmod nos quedamos con la primera de ellas
			}
			
		}		
		//$url_seo = $dir_root.$link_default;		
		return $url_seo;
	}
	
	
	function obtener_url_seo_modulo_id($TMP_id, $load="false"){
		global $RAD_dbi, $SESSION_SID;
		
		$amigable = false;
		
		if (defined('_DEF_DIR_ROOT')) $dir_root = _DEF_DIR_ROOT;
		else $dir_root = "";		
		
		
		$TMP_sel = "SELECT * FROM modulos WHERE idmodulo = $TMP_id";
		$TMP_res = sql_query($TMP_sel, $RAD_dbi);
		$TMP_row = sql_fetch_array($TMP_res, $RAD_dbi);
		
		$link_default  = 'index.php?V_dir='.$TMP_row['directorio'].'&V_mod='.$TMP_row['fichero'].'&V_idmod='.$TMP_row['idmodulo'];
		
		if ((_DEF_MODREWRITE == "" || _DEF_MODREWRITE == "_DEF_MODREWRITE") && $load=="false") $url_seo = $dir_root.$link_default;
		else {		
			$array_subniveles = array();
			$subniveles = "";
			//Obtener la url amigable del módulo			
			if ($TMP_row['urlamigable'] != "") {
				$url_seo = $dir_root.$TMP_row["urlamigable"];
				$amigable = true;
			}
			else {					
				//Obtener si se puede la url a partir del grupo menu e item menu
				if ( ($TMP_row["literalmenu"] != "") && ($TMP_row["grupomenu"] != "") ) {
					$pagina=limpiarURL($TMP_row["literalmenu"]);
					//$array_subniveles[] = limpiarURL($TMP_row["grupomenu"]);
					while($TMP_row['literalmenu'] != "") {
						$itempadre = $TMP_row["grupomenu"];
						$array_subniveles[] = limpiarURL($TMP_row["grupomenu"]);
						$TMP_sel = "SELECT * FROM modulos WHERE (grupomenu='$itempadre' OR literalmenu='$itempadre') AND fichero='' ";
						$TMP_res = sql_query($TMP_sel,$RAD_dbi);
						$TMP_row = sql_fetch_array($TMP_res, $RAD_dbi);
					}
					//Al terminar con el bucle tengo en el array la estructura inversa de navegacion
					$count = count($array_subniveles);
					for ($i = $count-1; $i >= 0; $i--) {
						$subniveles .= $array_subniveles[$i]._DEF_MODREWRITE_SEP;
					}
					$url_seo=$dir_root.$subniveles.$pagina.".htm";
					$amigable = true;
				} else {
					$url_seo = $dir_root.$link_default;
				}
			}
		}		
		return array($amigable,$url_seo,$link_default);
	}
	
	
?>