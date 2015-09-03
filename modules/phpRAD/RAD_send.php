<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}
global $SESSION_SID;
//---------------------------------------------------------------------------
//------------------------- Send! AJAX!
//---------------------------------------------------------------------------
if ($subfunc=="sendlist" && $RAD_sendfunc=="RAD_sendSave") {
	include("modules/phpRAD/RAD_sendSave.php");
	RAD_sendSave();
} else if ($subfunc=="sendlist" || $V_subfunc=="sendlist") {
        require_once("images/xajax/xajax.inc.php");

	function sendMail($fromemail,$fromname,$to,$cc,$bcc,$asunto,$precuerpo,$cuerpo,$adjuntos) {
		global $fields,$findex,$RAD_dbi,$title,$tablename,$idname0,$dbname,$par0,$idpersona,$db;
		ob_end_clean();
		
		include_once("modules/phpmailer/class.phpmailer.php");
		$mail=new PHPMailer();
		$mail->LE = "\n";
		$mail->CharSet = "utf-8";
		$mail->isMail();

		$TMP_cmd=
		$mail->From = $fromemail;
		$mail->FromName = $fromname;
		$mail->Subject=$asunto;
		
		if ($precuerpo!='') {
			$cuerpo="-------------------------------------<br><br>".str_replace("\n","<br>",htmlentities(utf8_decode($precuerpo)))."<br><br>-------------------------------------<br><br>".str_replace("\n","<br>",htmlentities(utf8_decode($cuerpo)));
		}
		else $cuerpo=str_replace("\n","<br>",htmlentities(utf8_decode($cuerpo)));
		
		$mail->MsgHTML($cuerpo);

		// TO
		foreach (explode(",",$to) as $todir) {
			$todirtrim=trim($todir);
			if ($todirtrim!='')
				$mail->AddAddress($todirtrim,$todirtrim);
		}
		
		// CC
		foreach (explode(",",$cc) as $ccdir) {
			$ccdirtrim=trim($ccdir);
			if ($ccdirtrim!='')
				$mail->AddCC($ccdirtrim,$ccdirtrim);
		}

		// BCC
		foreach (explode(",",$bcc) as $bccdir) {
			$bccdirtrim=trim($bccdir);
			if ($bccdirtrim!='')
				$mail->AddBCC($bccdirtrim,$bccdirtrim);
		}

		// ATTACH
		foreach (explode("\n",$adjuntos) as $adj) {
			if (trim($adj)!='') {
				$TMP_arr=explode("/",$adj);
				$TMP_arr2=explode(".",$TMP_arr[1]);
				unset($TMP_arr2[0]);
				unset($TMP_arr2[1]);
				$nombrefich=implode(".",$TMP_arr2);
				$mail->AddAttachment(_DEF_DIRBASE."/files/".$dbname."/".$adj,$nombrefich);
			}
		}


		// ENVIO
		$objResponse = new xajaxResponse();
		$objResponse->outputEntitiesOn();
		$enviado=false;
		
		if (trim($asunto)=='') {
			$objResponse->addAlert("Por favor introduzca un asunto en el mensaje!");
			return $objResponse;
		}
		
		if(!$mail->Send())
			$aviso="NO SE HA PODIDO ENVIAR EL E-MAIL";
		else {
			$aviso="E-MAIL ENVIADO CORRECTAMENTE";
			$enviado=true;
		}

		// GUARDAR EN GIE_incidencias
		if ($idname0!='id') {
			$TMP_cmd="SHOW COLUMNS FROM GIE_incidencias WHERE field LIKE '$idname0'";
			$TMP_res=sql_query($TMP_cmd,$RAD_dbi);
			if (sql_num_rows($TMP_res,$RAD_dbi)>0) {
				$TMP_cmdINS="INSERT INTO GIE_incidencias SET $idname0='$par0', idpersona='$idpersona', descripcion='$asunto', medionotificacion='0', 
					observaciones=".converttosql(str_replace("<br>","\n",$cuerpo)).", documentos=".converttosql($adjuntos).", fecha_alta='".date("Y-m-d")."', hora_alta='".date("H:i:s")."'";
					
				if ($db->Record[idcliente]>0 && !ereg("idcliente",$TMP_cmdINS)) $TMP_cmdINS.=", idcliente='".$db->Record[idcliente]."'";
				if ($db->Record[idproveedor]>0 && !ereg("idproveedor",$TMP_cmdINS)) $TMP_cmdINS.=", idproveedor='".$db->Record[idproveedor]."'";
				
				$TMP_dirs=$to."\n".$cc."\n".$bcc;
				$TMP_cmdINS.=", direccion=".converttosql($TMP_dirs)."";
				
				$contactos=array();
				foreach (explode("\n",$TMP_dirs) as $dir) {
					if (trim($dir)=='') continue;
					
					if ($RAD_tabla_direcciones!="") $TMP_cmdZ="SELECT * FROM ".$RAD_tabla_direcciones." WHERE email='$dir'";
					else $TMP_cmdZ="SELECT * FROM direcciones WHERE email='$dir'";
					$TMP_resZ=sql_query($TMP_cmdZ,$RAD_dbi);
					while ($TMP_rowZ=sql_fetch_array($TMP_resZ,$RAD_dbi)) {
						if ($RAD_tabla_direcciones=="GIE_entidades") $contactos[]=$TMP_rowZ[identidad];
						else $contactos[]=$TMP_rowZ[id];
					}
				}
				if (count($contactos)>0) {
					$TMP_cmdINS.=", personacontacto=',".implode(",,",$contactos).",'";
				}
				
				sql_query($TMP_cmdINS,$RAD_dbi);
			}
		}


		$objResponse->addAlert($aviso);
		if ($enviado) {
			if (_DEF_POPUP_MARGIN=="SUBMODAL") $objResponse->addScript("closePop();");
			else $objResponse->addScript("window.close();");
		}
		return $objResponse;
	}
	
	///// PROCESO QUE CARGA DATOS
	$TMPSET=ob_get_contents();
	ob_end_clean();
	ob_start();
	$menuoff="x";
	$func="edit";
	$bodyoff="x";
	include_once("modules/$V_dir/".$V_mod.".php");
	ob_end_clean();
	ob_start();
	echo $TMPSET;
	$func="detail";
	/// JUGANDO CON OB

	$TMP_V_from=$HTTP_SESSION_VARS["SESSION_U_email"];
	$TMP_user=base64_decode(getSessionVar("SESSION_user"));
	$TMP_idusuario=base64_decode(getSessionVar("SESSION_U_idusuario"));
	if ($TMP_user!='') {
		$TMP_cmdp="SELECT * FROM GIE_personal WHERE idusuario='$TMP_idusuario'";
		$TMP_resp=sql_query($TMP_cmdp,$RAD_dbi);
		while ($TMP_rowp=sql_fetch_array($TMP_resp,$RAD_dbi)) {
			$TMP_V_fromname=$TMP_rowp[nombre]." ".$TMP_rowp[apellidos];
			if (trim($TMP_V_from)=='') $TMP_V_from=$TMP_rowp[email];
			$idpersona=$TMP_rowp[idpersona];
		}
		if ($TMP_V_fromname=='') $TMP_V_fromname=$TMP_V_from;
	}
	if (trim($TMP_V_from)=='') {
		echo "Necesita tener configurada una direccion de correo en su usuario para usar esta funcionalidad.";
		die;
	}
	
	$xajax = new xajax();
	$xajax->registerFunction("sendMail");
	$xajax->processRequests();
	$xajax->printJavascript();
	
	/************************** DIRECCIONES EMAIL */
	
	if ($RAD_tabla_direcciones!="") $TMP_cmd="SHOW COLUMNS FROM ".$RAD_tabla_direcciones;
	else $TMP_cmd="SHOW COLUMNS FROM direcciones";
	$TMP_res=sql_query($TMP_cmd,$RAD_dbi);
	while ($TMP_row=sql_fetch_array($TMP_res,$RAD_dbi)) {
		$CAMPOSDIR[]=$TMP_row[0];
	}
	
	$TMP_divdir="<div id='direcciones' style='display:none;background-color:white;height:600px;width:850px;overflow:scroll;position:absolute;border:2px solid black; left:50px;'>
		<a href='javascript:hideDir();' style='float:right'><img src='images/close.gif'> Cerrar</a>
		<div style='clear:left;width:400px;float:left;'><h3>Personas de Contacto Vinculadas</h3><ul>";
	
	$ARRORDEN=array("idcliente","idproveedor");
	$fieldsel="";
	foreach ($ARRORDEN as $tmpidfield) {
		if ($db->Record[$tmpidfield]>0) {
			$fieldsel=$tmpidfield;
			$ARRFIELDSEL["title"]=$fields[$findex[$fieldsel]]->title;
			$ARRFIELDSEL["value"]=RAD_showfield($fields[$findex[$fieldsel]]->dtype,$fields[$findex[$fieldsel]]->extra,$db->Record[$fieldsel]);
			$TMP_divdir.="<h4>Filtrado por <i>$ARRFIELDSEL[title]</i> = $ARRFIELDSEL[value]</h4>";
			if ($RAD_tabla_direcciones=="GIE_entidades") $TMP_cmd="SELECT * FROM GIE_entidades WHERE $fieldsel='".$db->Record[$fieldsel]."' AND email!='' ORDER BY entidad ASC";
			else $TMP_cmd="SELECT * FROM direcciones WHERE $fieldsel='".$db->Record[$fieldsel]."' AND email!='' ORDER BY nombre ASC";
			$TMP_res=sql_query($TMP_cmd,$RAD_dbi);
			while ($TMP_row=sql_fetch_array($TMP_res,$RAD_dbi)) {
				if ($RAD_tabla_direcciones=="GIE_entidades") $TMP_row[nombre]=$TMP_row[entidad];
				$TMP_divdir.="<li><a href='javascript:dir_sel(\"$TMP_row[email]\");'>$TMP_row[nombre] &lt;".$TMP_row[email]."&gt; </a></li>";
			}
			$ARRFIELD[]=$ARRFIELDSEL;
		}
	}
	
	$TMP_divdir.="</div><div style='width:400px;float:right;'><h3>Personas de Contacto Empresa</h3><ul>";
	
	$TMP_cmd="SELECT * FROM GIE_personal WHERE email!=''";
	$TMP_res=sql_query($TMP_cmd,$RAD_dbi);
	while ($TMP_row=sql_fetch_array($TMP_res,$RAD_dbi)) {
		$TMP_divdir.="<li><a href='javascript:dir_sel(\"$TMP_row[email]\");'>$TMP_row[nombre] $TMP_row[apellidos] &lt;".$TMP_row[email]."&gt; </a></li>";
	}
	
	$TMP_divdir.="</div></div>";
	
	echo "<script>var destfield='';
		function dir_sel(email) {
			if (destfield!='') {
				elemento=document.forms.F_SEND[destfield];
				if (elemento.value!='') elemento.value=elemento.value + ', ';
				elemento.value=elemento.value + email;
			}
			hideDir();
		}
		function hideDir() {
			document.getElementById('direcciones').style.display='none';
		}
		function showDir(field) {
			destfield=field;
			document.getElementById('direcciones').style.display='block';
		}
		</script>".
		$TMP_divdir;

	/************************** CAMPOS DEL REGISTRO */
	
	$TMP_divfie="<div id='campos' style='display:none;background-color:white;height:600px;width:800px;overflow:scroll;position:absolute;border:2px solid black; left:50px;'>
		<a href='javascript:hideFields();' style='float:right'><img src='images/close.gif'> Cerrar</a>
		<h3>Campos a A&ntilde;adir al mensaje - $title</h3><table class='detail'>";
	
	foreach ($findex as $tmpfname=>$tmpf) {
		if ($fields[$tmpf]->nodetail==true) continue;
		if (in_array($fields[$tmpf]->dtype,array("function","file","image","","auto_increment"))) {
			if (in_array($tmpfname,array("codproyecto","numalbaran")) || ($fields[$tmpf]->dtype=="auto_increment" && $fields[$tmpf]->browsable==true)) {
				$tmpval=RAD_showfield("stand","",$db->Record[$tmpfname]);
			}
			else continue;
		}
		else {
			$tmpval=RAD_showfield($fields[$tmpf]->dtype,$fields[$tmpf]->extra,$db->Record[$tmpfname]);
		}
		$tmptitle=$fields[$tmpf]->title;
		
		if (ereg("<a",$tmpval)) {
			$TMP_arr=explode("<a",$tmpval);
			$tmpval=$TMP_arr[0];
		}
		$tmpstr=rawurlencode(str_replace("<br>"," ",$tmptitle).": ".str_replace("\n"," ",$tmpval));
		
		$TMP_divfie.="<tr><td class='detail'><a href='javascript:str_sel(\"$tmpstr\");'><img src='images/nolines_plus.gif'></a></td><td class='detailtit'>".$tmptitle."</td><td class='detail'>".$tmpval."</td></tr>";
	}
	
	$TMP_divfie.="</table></div>";
	echo "<script>
		function str_sel(string) {
			elemento=document.forms.F_SEND.V_premensaje;
			if (elemento.value!='') elemento.value=elemento.value + '\\n';
			elemento.value=elemento.value + unescape(string);
			
			hideFields();
		}
		function hideFields() {
			document.getElementById('campos').style.display='none';
		}
		function showFields() {
			document.getElementById('campos').style.display='block';
		}
		</script>".
		$TMP_divfie;

	/************************** ADJUNTOS */
	
	$TMP_divatt="<div id='attach' style='display:none;background-color:white;height:600px;width:1000px;overflow:scroll;position:absolute;border:2px solid black; left:50px;'>
		<a href='javascript:hideAttach();' style='float:right'><img src='images/close.gif'> Cerrar</a>
		<h3>Ficheros a Adjuntar - $title</h3><table class='detail'>";
	
	foreach ($findex as $tmpfname=>$tmpf) {
		if (in_array($fields[$tmpf]->dtype,array("file","image"))) {
			$tmptitle=$fields[$tmpf]->title;
			$TMP_divatt.="<tr><td class='detailtit'>$tmptitle</td><td class='detail'><ul style='list-style:none;padding-left:0px;'>";
			foreach (explode("\n",$db->Record[$tmpfname]) as $linea) {
				if (trim($linea)=='') continue;
				$linea=trim($linea);
				$TMP_divatt.="<li><a href='files/".$dbname."/".$linea."' target='_BLANK'> $linea </a><br><a href='javascript:att_sel(\"".rawurlencode($linea)."\");'><img src='images/nolines_plus.gif'> Añadir</a><br><br></li>";
			}
			$TMP_divarr.="</ul>";
		}
	}
	
	$TMP_divatt.="</table></div>";
	echo "<script>
		function att_sel(string) {
			elemento=document.forms.F_SEND.V_adjuntos;
			if (elemento.value!='') elemento.value=elemento.value + '\\n';
			elemento.value=elemento.value + unescape(string);
			
			hideAttach();
		}
		function hideAttach() {
			document.getElementById('attach').style.display='none';
		}
		function showAttach() {
			document.getElementById('attach').style.display='block';
		}
		function clearText(textarea) {
			document.getElementById(textarea).value='';
                }
		</script>".
		$TMP_divatt;

	/************************** FORMULARIO ENVIO */

   OpenTable();
   
   echo "</td><td class=borde><b>Enviar Informacion del modulo: $title</b></td></tr>";
echo "<tr><td colspan=2 align=right><a href='javascript:";
   if (_DEF_POPUP_MARGIN=="SUBMODAL") echo "closePop();";
   else echo "window.close();";
   echo "'><img src='images/close.gif' border=0> Cerrar</a> </td></tr>";
    echo "\n<form action='".$PHP_SELF."' method=get name=F_SEND>\n";
    foreach ($ARRFIELD as $ARRFIELDSEL) {
	echo "<tr><td align=right ><b>".$ARRFIELDSEL[title]." : </b></td><td>".$ARRFIELDSEL[value]."</td></tr>";
    }
    echo "<tr><td align=right><b>De : </b></td><td><input type=text size=40 name=V_from value='".$TMP_V_from."' readonly><input type=hidden name=V_from_name value='".$TMP_V_fromname."'></td></tr>";
    echo "<tr><td align=right><b>Para : </b></td><td><input type=text size=80 name=V_to> <a href='javascript:showDir(\"V_to\");'><img src='images/direccion.gif' border=0></a></td></tr>";
    //echo $TMP_content;
    echo "<tr><td align=right><b>Copia : </b></td><td><input type=text size=80 name=V_cc> <a href='javascript:showDir(\"V_cc\");'><img src='images/direccion.gif' border=0></a></td></tr>";
    echo "<tr><td align=right nowrap><b>Copia Oculta : </b></td><td><input type=text size=80 name=V_bcc value='".$TMP_V_from."'> <a href='javascript:showDir(\"V_bcc\");'><img src='images/direccion.gif' border=0></a></td></tr>";


    echo "</td></tr><tr><td colspan=2 heigh='20'>&nbsp;</td></tr><tr><td align=right><b>Asunto : </b></td><td><input type=text size=80 name=V_asunto><span style='color:red'><b>*</b></span></td></tr>";
    echo "<tr><td align=right><b>Cabecera : </b></td><td align=left><a href='javascript:showFields();' style='float:left;'><img src='images/nolines_plus.gif'>Añadir Campos</a><a href='javascript:clearText(\"V_premensaje\");' style='float:left;'><img src='images/nolines_minus.gif'>Vaciar</a><br /><br /><textarea cols=80 rows=3 name=V_premensaje id=V_premensaje disabled></textarea></td></tr>";
    echo "<tr><td align=right><b>Adjuntos : </b></td><td><a href='javascript:showAttach();' style='float:left;'><img src='images/nolines_plus.gif'>Añadir Adjuntos</a><a href='javascript:clearText(\"V_adjuntos\");' style='float:left;'><img src='images/nolines_minus.gif'>Vaciar</a><br /><br /><textarea cols=80 rows=3 name=V_adjuntos id=V_adjuntos disabled></textarea></td></tr>";
    echo "<tr><td align=right><b>Mensaje : </b></td><td valign=top><textarea cols=80 rows=10 name=V_mensaje></textarea></td></tr>";
    
    echo "<script> function enviaForm() {
	xajax_sendMail(document.forms.F_SEND.V_from.value,
		document.forms.F_SEND.V_from_name.value,
		document.forms.F_SEND.V_to.value,
		document.forms.F_SEND.V_cc.value,
		document.forms.F_SEND.V_bcc.value,
		document.forms.F_SEND.V_asunto.value,
		document.forms.F_SEND.V_premensaje.value,
		document.forms.F_SEND.V_mensaje.value,
		document.forms.F_SEND.V_adjuntos.value);
    }
    </script>";

    echo "<tr><th colspan=2><br><center><input type=hidden value='' name=V_save><input type=button name=V_send value=' Enviar ' onclick='enviaForm();'></center></th></tr>";
    
    echo "</form>";
    CloseTable();
}
?>
