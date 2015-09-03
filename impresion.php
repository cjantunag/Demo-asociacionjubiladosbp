<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}
// Parametros: V_idimpreso, V_tabimpreso, V_impreso

//  Tablas impresos: idimpreso int(11) NOT NULL auto_increment, tipoimpreso varchar(60)
//  impreso varchar(60), tabla varchar(60), eval text
//  tipodoc varchar(6), contenido text, documentos text

$TMP=ob_get_contents();
ob_end_clean();
ob_start();
$TMP="";
$TMP_lang=$HTTP_SESSION_VARS["SESSION_lang"];
if ($V_impreso!="") {
	if (file_exists(_DEF_DIRBASE."files/".$dbname."/".$V_impreso)) $TMP_file="files/".$dbname."/".$V_impreso;
	else if (file_exists(_DEF_DIRBASE.$V_impreso)) $TMP_file=$V_impreso;
	else die("ERR: No existe fichero "._DEF_DIRBASE.$V_impreso);
	$RAD_TMP_row[tipodoc]=substr($TMP_file,strlen($TMP_file)-3);
	//$fp = fopen($TMP_file, "r");
	//$RAD_TMP_row[contenido] = fread($fp, filesize($TMP_file));
	//fclose($fp);
} else if ($V_tabimpreso=="GIE_manuales") {
       	$TMP_cmd="SELECT * FROM GIE_manuales WHERE idmanual='$V_idimpreso'";
       	$TMP_result=sql_query($TMP_cmd,$RAD_dbi);
       	$RAD_TMP_row=sql_fetch_array($TMP_result,$RAD_dbi);
	if ($RAD_TMP_row["docs_".$TMP_lang]!="") $RAD_TMP_row[docs]=$RAD_TMP_row["docs_".$TMP_lang];
	$TMP_file=RAD_primerFich($RAD_TMP_row[docs]);
	$TMP_file = "files/".$dbname."/".$TMP_file;
	$RAD_TMP_row[tipodoc]=substr($TMP_file,strlen($TMP_file)-3);
	//$fp = fopen($TMP_file, "r");
	//$RAD_TMP_row[contenido] = fread($fp, filesize($TMP_file));
	//fclose($fp);
} else { 
       	$TMP_cmd="SELECT * FROM impresos WHERE idimpreso='$V_idimpreso'";
       	$TMP_result=sql_query($TMP_cmd,$RAD_dbi);
       	$RAD_TMP_row=sql_fetch_array($TMP_result,$RAD_dbi);
	if ($RAD_TMP_row["documentos_".$TMP_lang]!="") $RAD_TMP_row["documentos"]=$RAD_TMP_row["documentos_".$TMP_lang];
	$TMP_file=RAD_primerFich($RAD_TMP_row[documentos]);
	$TMP_file = "files/".$dbname."/".$TMP_file;
}
if ($RAD_TMP_row["eval_".$TMP_lang]!="") $RAD_TMP_row["eval"]=$RAD_TMP_row["eval_".$TMP_lang];
if ($RAD_TMP_row["contenido_".$TMP_lang]!="") $RAD_TMP_row["contenido"]=$RAD_TMP_row["contenido_".$TMP_lang];
if (trim($RAD_TMP_row["eval"])!="") eval($RAD_TMP_row["eval"]);
$TMP_contenido="";
if ($V_returnimpreso=="") {
	if (strtoupper(trim($RAD_TMP_row[tipodoc]))=="RTF") {
		if (strtoupper($V_save)!="PDF") header('Content-Type: application/rtf');
	} else if (strtoupper(trim($RAD_TMP_row[tipodoc]))=="ODT") {
		if (strtoupper($V_save)!="PDF") header('Content-Type: application/vnd.oasis.opendocument.text;');
	} else if (strtoupper(trim($RAD_TMP_row[tipodoc]))=="DOC") {
		if (strtoupper($V_save)!="PDF") header('Content-Type: application/doc');
	} else if (strtoupper(trim($RAD_TMP_row[tipodoc]))=="PDF") {
		$disposition="inline";
		header('Content-Type: application/pdf');
	} else if (strtoupper(trim($RAD_TMP_row[tipodoc]))=="HTM" || strtoupper(trim($RAD_TMP_row[tipodoc]))=="HTML" || strtoupper(trim($RAD_TMP_row[tipodoc]))=="TML") {
		$disposition="inline";
		if (strtoupper($V_save)!="PDF") header("Content-Type: text/html; charset=iso-8859-1\n");
	} else if (strtoupper(trim($RAD_TMP_row[tipodoc]))=="CSV") {
		if (strtoupper($V_save)!="PDF") header("Content-Type: application/csv\n");
	} else if (strtoupper(trim($RAD_TMP_row[tipodoc]))=="XML") {
		$fp = fopen($TMP_file, "r");
		$RAD_TMP_row[contenido] = fread($fp, filesize($TMP_file));
		fclose($fp);
		if (strtoupper($V_save)!="PDF") header("Content-Type: text/xml\n");
	} else if (strtoupper(trim($RAD_TMP_row[tipodoc]))=="TXT") {
		if (strtoupper($V_save)!="PDF") header("Content-Type: text/plain; charset=iso-8859-1\n");
	} else if (strtoupper(trim($RAD_TMP_row[tipodoc]))=="PNG") {
		$disposition="inline";
		if (strtoupper($V_save)!="PDF") header("Content-Type: image/png");
	} else if (strtoupper(trim($RAD_TMP_row[tipodoc]))=="GIF") {
		$disposition="inline";
		if (strtoupper($V_save)!="PDF") header("Content-Type: image/gif");
	} else if (strtoupper(trim($RAD_TMP_row[tipodoc]))=="JPG" || strtoupper(trim($RAD_TMP_row[tipodoc]))=="JPEG" || strtoupper(trim($RAD_TMP_row[tipodoc]))=="PEG") {
		$disposition="inline";
		if (strtoupper($V_save)!="PDF") header("Content-Type: image/jpg");
	} else {
		if (strtoupper($V_save)!="PDF") header("Content-Type: application/force-download");
//		header('Content-Type: application/octet-stream\n');
//		header("Content-Type: ".$downloadtype);
//		header("Content-Type: ".$mimeFile);
	}
}

if (trim($RAD_TMP_row[contenido])!="") {
	$TMP_contenido=$RAD_TMP_row[contenido];
	if (strtoupper(trim($RAD_TMP_row[tipodoc]))=="XML") {
		$TMP_contenido=str_replace("<","&_1_;",$TMP_contenido);
		$TMP_contenido=str_replace(">","&_2_;",$TMP_contenido);
		$TMP_contenido=str_replace("&lt;","<",$TMP_contenido);
		$TMP_contenido=str_replace("&gt;",">",$TMP_contenido);
	}
	$TMP_contenido=str_replace("\"","\\\"",$TMP_contenido);
	eval("\$TMP_resultado=\"".$TMP_contenido."\";");  // evalua, aunque se podria reemplazar mejor
	if (strtoupper(trim($RAD_TMP_row[tipodoc]))=="XML") {
		$TMP_resultado=str_replace(">","&gt;",$TMP_resultado);
		$TMP_resultado=str_replace("<","&lt;",$TMP_resultado);
		$TMP_resultado=str_replace("&_2_;",">",$TMP_resultado);
		$TMP_resultado=str_replace("&_1_;","<",$TMP_resultado);
	}
	echo $TMP_resultado;
} else {
	if ($TMP_file!="") {
		if (strtoupper(trim($RAD_TMP_row[tipodoc]))=="ODT") { // si es Open document hay que deszipear y evaluar el contenido XML
			$TMP_name=uniqid("");
			$TMP_dir="/tmp/".$TMP_name;
			mkdir($TMP_dir,0777);
			$cmd1="cd $TMP_dir; unzip "._DEF_DIRBASE.$TMP_file." 2>&1 1>/dev/null";
			@system($cmd1);

			$TMP_file=$TMP_dir."/styles.xml";
			$fp = fopen($TMP_file, "r");
			$TMP_content = fread($fp, filesize($TMP_file)); //  lee para reemplazar o evaluar $TMP_content
			fclose($fp);
			$TMP_content=RAD_sustituyeVarPHP($TMP_content); // de momento solo se sustituyen los mayor?=$var?menor
			$TMP_content2=str_replace("\"","\\\"",$TMP_content);
			eval("\$TMP_content3=\"".$TMP_content2."\";");  // evalua, aunque se podria reemplazar mejor
			if ($TMP_content3!="") $TMP_content=$TMP_content3;
			//if ($TMP_content3!="") $TMP_content=utf8_encode($TMP_content3);
			if ($debug!="") die($TMP_content);
			$fp = fopen($TMP_file,"w");
			fwrite($fp,$TMP_content);
			fclose($fp);

			$TMP_file=$TMP_dir."/content.xml";
			
			/************************ MOD TABLAS */

			$D_dom=DOMDocument::load($TMP_file);
			$changed=false;
			foreach ($D_dom->getElementsByTagName("table") as $D_table) {
				
				$TMP_attr=$D_table->getAttribute("name");
				if (ereg("REPETIR-",$TMP_attr)) {
					// OK TABLA A REPETIR
					//>Buscar la columna en la que empezar con el tema, esto evita replicar cabeceras de tablas y tal
					
					$TMP_arr=explode("-",$TMP_attr);
					$TMP_indice=$TMP_arr[1];
					if ($TMP_indice>0) {
						$TMP_cont=1;
						foreach ($D_table->getElementsByTagName("table-row") as $D_row) {
							if ($TMP_cont>=$TMP_indice) { // Solo evaluar a partir del indice!!
								//Buscar las variables!!
								$VBLES=array();
								foreach ($D_row->getElementsByTagName("table-cell") as $D_cell) {
									if (ereg('<\?=',$D_cell->textContent)) {
										// Hay variable
										ereg('=\$([^@ ]*)\?',$D_cell->textContent,$D_results);
										$VBLES[]=$D_results[1];
									}
								}

								// Saber cuantas veces  esas variables tienen ese valor
								$TMP_cont2=0;
								$TMP_cont3=0;
								while ($TMP_cont2==$TMP_cont3) {
									foreach ($VBLES as $vble) {
										if (${$vble.($TMP_cont2+1)}!='') {
											$TMP_cont2++;
											break;
										}
									}
									$TMP_cont3++;
								}
								$clonado=false; 
								// Clonar y poner valores
								for ($k=0;$k<$TMP_cont2;$k++) { // Los valores de variables que hay
									$clonado=true;
									$D_row2=$D_row->cloneNode(true); // Creo una nueva fila, el true es pa que clone los hijos tb
									$D_table->appendChild($D_row2);
									$changed=true;
									// Buscando celdas
									foreach ($D_row2->getElementsByTagName("table-cell") as $D_cell) {
										// Buscando tags text dentro de la celda
										foreach ($D_cell->childNodes as $D_tag) {
											$TMP_str=$D_tag->textContent;
											// Reemplazar nombres de variables
											foreach ($VBLES as $vble) {
												$TMP_str=str_replace($vble,$vble.($k+1),$TMP_str);
											}
											// Limpiar nodo y cargarle un nodo de texto
											while ($D_tag->firstChild!=null){
												$D_tag->removeChild($D_tag->firstChild);
											}
											$D_text=$D_dom->createTextNode($TMP_str);
											$D_tag->appendChild($D_text);
										}
									}
									
								}
								
								if ($clonado) {
									$D_table->removeChild($D_row); // El patron lo quitamos de aqui, si no sobraria una fila al principio de la tabla
									break;
								}
								
								
							}
							$TMP_cont++; // El de la tabla
						}
					}
				
				}
			}
			// Si hubo cambios reescribimos el xml temporal
			if ($changed)
				$D_dom->save($TMP_file);
				
			/* EOF MOD TABLAS**************************/
			
			
			$fp = fopen($TMP_file, "r");
			$TMP_content = fread($fp, filesize($TMP_file)); //  lee para reemplazar o evaluar $TMP_content
			fclose($fp);
			$TMP_content=RAD_sustituyeVarPHP($TMP_content); // de momento solo se sustituyen los mayor?=$var?menor
			$TMP_content2=str_replace("\"","\\\"",$TMP_content);
			eval("\$TMP_content3=\"".$TMP_content2."\";");  // evalua, aunque se podria reemplazar mejor
			if ($TMP_content3!="") $TMP_content=$TMP_content3;
			//if ($TMP_content3!="") $TMP_content=utf8_encode($TMP_content3);
			if ($debug!="") die($TMP_content);
			$fp = fopen($TMP_file,"w");
			fwrite($fp,$TMP_content);
			fclose($fp);

			$cmd2="cd $TMP_dir; zip -r ".$TMP_dir.".odt * 2>&1 1>/dev/null";
			@system($cmd2);

			if (strtoupper($V_save)=="PDF") {
				$cmd3="cd privado; python DocumentConverter.py ".$TMP_dir.".odt ".$TMP_dir.".pdf";
	 			@system($cmd3);
				$fp = fopen($TMP_dir.".pdf", "r");
				$TMP_content = fread($fp, filesize($TMP_dir.".pdf"));
				fclose($fp);
				$cmd3="cd /tmp; rm -rf $TMP_dir ".$TMP_dir.".odt ".$TMP_dir.".pdf";
	 			@system($cmd3);
				$TMP_nameext=$TMP_name.".pdf";
			} else {
				$fp = fopen($TMP_dir.".odt", "r");
				$TMP_content = fread($fp, filesize($TMP_dir.".odt"));
				fclose($fp);
				$cmd3="cd /tmp; rm -rf $TMP_dir ".$TMP_dir.".odt";
	 			@system($cmd3);
				$TMP_nameext=$TMP_name.".odt";
			}
			if ($V_returnimpreso!="" || $V_send!="") {
				$fp = fopen("/tmp/".$TMP_nameext,"w");
				fwrite($fp, $TMP_content);
				fclose($fp);
				if ($V_returnimpreso!="") return "/tmp/".$TMP_nameext;
				RAD_sendMail($V_to,$V_cc,$V_bcc,$V_from,$V_asunto,$V_mensaje,"/tmp/".$TMP_nameext);
				echo "<script>\nalert('"._DEF_NLSMessageSent."');\nwindow.close();\n</script>\n";
			}
			if (strtoupper($V_save)=="PDF") {
				header('Content-Type: application/pdf');
				header("Content-Disposition: inline; filename=".$TMP_name.".pdf\n");
				echo $TMP_content;
				die();
			} else if ($V_save!="") {
				header("Content-Disposition: inline; filename=".$TMP_name.".odt\n");
				echo $TMP_content;
				die();
			} else {
				echo $TMP_content;
			}
		} else {
			include($TMP_file); // se incluye, aunque lo mejor seria reemplazar
		}
	}
}
////////////////////////////////////////////////////////////////////
	$TMP=ob_get_contents();
	ob_end_clean();
	ob_start();
	//set_time_limit(0);
	$TMP_namefich=substr(basename($TMP_file),0,strlen(basename($TMP_file))-4);
	if (basename($TMP_file)!="") $impresoFile=$TMP_namefich.".".uniqid("").".".strtolower($RAD_TMP_row[tipodoc]);
	else $impresoFile=uniqid("").".".strtolower($RAD_TMP_row[tipodoc]);
	$fp = fopen("/tmp/".$impresoFile,"w");
	fwrite($fp,$TMP);
	fclose($fp);
	if ($V_returnimpreso!="") {
		return "/tmp/".$impresoFile;
	} else if ($V_save!="") {
		session_cache_limiter("");
		header("Pragma: public");
		//header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		//header("Cache-control: private\n",false);
		//header("Expires: Mon, 1 Feb 1997 00:00:00 GMT");
		header('Cache-Control: maxage=3600'); // For IE
		header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
		header("Content-Type: application/".strtolower($RAD_TMP_row[tipodoc])."\n");
		//header("Content-Type: application/x-download");
		//header("Content-Type: application:binary;");
		header("Content-transfer-encoding: binary\n");
		header("Content-Length: ".@filesize("/tmp/".$impresoFile));
		if ($disposition=="") $disposition="attachment";
		header("Content-Disposition: $disposition; filename=".basename($impresoFile)."\n");
		@readfile("/tmp/".$impresoFile) or die("File /tmp/".$impresoFile." not found...");
	} else if ($V_send!="") {
		RAD_sendMail($V_to,$V_cc,$V_bcc,$V_from,$V_asunto,$V_mensaje,$impresoFile);
		echo "<script>\nalert('"._DEF_NLSMessageSent."');\nwindow.close();\n</script>\n";
	}
return;
?>
