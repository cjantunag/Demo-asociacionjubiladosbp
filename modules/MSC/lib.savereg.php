<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");
// Graba datos de formulario en tabla

//------------------------------------------------------------------------------------------------------------------------
function savereg($tabla, $asunto) {
	global $RAD_dbi, $PHP_SELF, $V_dir, $dbname, $idventa;

	$idregcreado="";
	if ($asunto=="") $asunto="Consulta recibida en la Pagina WEB";

	$TMP_lang=getSessionVar("SESSION_lang");

	foreach($_REQUEST as $TMP_k=>$TMP_v) {
	   if (is_array($TMP_v)) {
		$TMP_vv="";
		foreach($TMP_v as $TMP_k2=>$TMP_v2) {
			if ($TMP_vv!="") $TMP_vv.=",";
			$TMP_vv.=$TMP_v2;
		}
		$_REQUEST[$TMP_k]=$TMP_vv;
	   }
	}

if(!isset($HTTP_POST_FILES)) $HTTP_POST_FILES=& $_FILES;
if (count($HTTP_POST_FILES)>0) {
   foreach ($HTTP_POST_FILES as $TMP_key=>$TMP_val) {
      if (is_array($TMP_val)) {
         $fname=$TMP_key;
         foreach ($TMP_val as $TMP_key2=>$TMP_val2) {
            if (get_magic_quotes_gpc()) $TMP_val2 = stripslashes ($TMP_val2);
            if (trim($TMP_val2=="")) continue;
            global ${$TMP_key."_".$TMP_key2};
            ${$TMP_key."_".$TMP_key2}=$TMP_val2;
            if ($TMP_key2=="error" && $TMP_val2>0 && $TMP_val2!=4) {
               error("Error al crear fichero en el servidor ($TMP_val2).");
            }
            if ($TMP_key2=="tmp_name") {
               global ${$TMP_key};
               ${$TMP_key}=$TMP_val2;
            }
         }
         if ($fname!="" && ${"".$fname."_tmp_name"}!="") {
		${"".$fname."_name"}=RAD_nameSecure(${$fname."_name"});
		list($TMP_fich,$nameFich)=RAD_nameDownload(${"".$fname."_name"});
		if (!copy(${"".$fname."_tmp_name"},$TMP_fich)) {
			RAD_logError("ERR: INSERT (copy) "._DEF_NLSError." ".$nameFich.".");
			//echo "Error copy ".$TMP_fich.".<br>";
		}
		chmod($TMP_fich,0644);
		//echo "Imagen ".${"".$fname."_tmp_name"}." copiada como ".$TMP_fich.".<br>";
		$_REQUEST[$fname]=substr($TMP_fich,strlen("files/".$dbname."/"))."\n";
         }
      }
   }
}

	$TMP_res=sql_list_fields($tabla, $RAD_dbi);
	while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
		$TMP_dbfield=$TMP_row["Field"];
		if ($TMP_dbfield=="fechaalta") $_REQUEST["fechaalta"]=date("Y-m-d H:i:s");
		if ($_REQUEST[$TMP_dbfield]!="") $cmd.=", ".$TMP_dbfield."=".converttosql($_REQUEST[$TMP_dbfield]);
	}
	if ($cmd!="") {
		$TMP_saved=getSessionVar("SESSION_saved_".$_REQUEST["captcha_code"]);
		if ($TMP_saved!="" && $_REQUEST["captcha_code"]!="") {
			if (is_admin()) alert("Formulario ya procesado ...");
			else error("Formulario ya procesado ...");
			return "";
		}
		if ($tabla=="firmas") sql_query("UPDATE acciones set numfirmas=numfirmas+1 where idaccion='".$_REQUEST[idaccion]."'", $RAD_dbi);
		if ($tabla=="GIE_ventas") {
			$cmd="UpDaTe ".$tabla." SeT ".substr($cmd,1)." WHERE idventa='".$idventa."'";
			sql_query($cmd, $RAD_dbi);
		} else {
			$cmd="INSERT INTO ".$tabla." SET ".substr($cmd,1);
			sql_query($cmd, $RAD_dbi);
			$idregcreado=sql_insert_id($RAD_dbi);
		}
		$msg="";
		//$msg.="".$_REQUEST[email]."\n\n";
		foreach($_REQUEST as $TMP_k=>$TMP_v) {
			if (is_array($TMP_v)) continue;
			if ($TMP_k=="captcha_code") continue;
			if ($TMP_k=="acepto") continue;
			if ($TMP_k=="button") continue;
			if ($TMP_k=="asunto") continue;
			if ($TMP_k=="save") continue;
			if ($TMP_k=="op") continue;
			if ($TMP_k=="idaccion") {
				$TMP_k="Accion";
				$TMP_v=RAD_lookup("acciones","accion","idaccion",$TMP_v);
			}
			if ($TMP_k=="fechaalta") continue;
			if (substr($TMP_k,0,2)=="V_") continue;
			if (substr($TMP_k,0,5)=="clave") continue;
			if (substr($TMP_k,0,8)=="xrecibir") continue;
			if (substr($TMP_k,0,1)=="_") continue;
			if (substr($TMP_k,0,3)=="PHP") continue;
			$msg.=ucwords($TMP_k)." = ".$TMP_v."\n";
		}
		$cmd="";
		$TMP_res=sql_list_fields("GIE_contactos", $RAD_dbi);
		while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
			$TMP_dbfield=$TMP_row["Field"];
			if ($TMP_dbfield=="fechaalta") $_REQUEST["fechaalta"]=date("Y-m-d H:i:s");
			$TMP_cambiado=false;
			foreach($_REQUEST as $TMP_k=>$TMP_v) {
				if ($TMP_k==$TMP_dbfield) {
					$cmd.=", ".$TMP_dbfield."=".converttosql($TMP_v);
					$TMP_cambiado=true;
				}
			}
			if ($TMP_cambiado==false && $_REQUEST["x".$TMP_dbfield]!="") $cmd.=", ".$TMP_dbfield."=".converttosql("");
			//if ($_REQUEST[$TMP_dbfield]!="") $cmd.=", ".$TMP_dbfield."=".converttosql($_REQUEST[$TMP_dbfield]);
		}
		$cmd=substr($cmd,1);
		if ($tabla!="consultas") {
			$TMP_res=sql_query("select * from GIE_contactos where email=".converttosql($_REQUEST[email]), $RAD_dbi);
			$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
			if ($TMP_row[idcontacto]>0) $cmd="UpdATE GIE_contactos SET ".$cmd." where email=".converttosql($_REQUEST[email]);
			else $cmd="INSERT INTO GIE_contactos SET ".$cmd;
			if ($tabla!="testimonios") sql_query($cmd, $RAD_dbi);
		}
		setSessionVar("SESSION_saved_".$_REQUEST["captcha_code"],"*");
		//$msg.="\n".$cmd."\n";
		$msg=str_replace("\n","<br>",$msg);
		if ($tabla!="GIE_ventas") { // del formulario de datos de compra no se envia correo
			include_once("modules/".$V_dir."/lib.Email.php");
			//F_SendMail($from, $fromname, $to, $bcc, $subject, $body, $altbody="", $adjuntos="", $html=false);
			//F_SendMail(_DEF_ADMINMAIL, _DEF_ADMINMAILNAME, $_REQUEST[email], "", $asunto, $msg, "", "", true);
			//if (is_admin()) echo "PRUEBA: Envia correo a "._DEF_ADMINMAIL." con asunto=".$asunto.". Y texto=".$msg."<br>";
			F_SendMail(_DEF_ADMINMAIL,_DEF_ADMINMAILNAME,_DEF_ADMINMAIL, "", $asunto, $msg, "", "", true);
		}
		if (file_exists("modules/".$V_dir."/saveform.".$tabla.".php")) include_once ("modules/".$V_dir."/saveform.".$tabla.".php");
	}
	return $idregcreado;
}
?>
