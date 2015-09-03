<?php
/////////////////////////////////////////////////////////////////////////////////////
function RAD_sendSave() {
    global $RAD_tabla_direcciones, $_SERVER, $PHP_SELF, $HTTP_GET_VARS, $HTTP_SESSION_VARS, $tablename, $V_mod, $V_dir, $RAD_dbi, $func, $doc, $dbname, $bodyoff, $op, $V_faval, $V_fa, $V_faorder, $V_faorderDESC, $V_subfunc, $subfunc, $RAD_sendTo, $RAD_sendSubject, $RAD_sendBody;

    if ($op=="addr") {
	$TMP_URLaddr=RAD_delParamURL($_SERVER[QUERY_STRING],"V_faorder");
	$TMP_URLaddr=$PHP_SELF."?".RAD_delParamURL($TMP_URLaddr,"V_faorderDESC");
	echo "<br><table class=browse><tr><th class=browse>";
	if ($V_faorder!="nombre") echo "<a href=\"".$TMP_URLaddr."&V_faorder=nombre\">";
	else if ($V_faorderDESC!="") echo "<a href=\"".$TMP_URLaddr."&V_faorder=nombre\"><img src='images/down.gif' border=0> ";
	else echo "<a href=\"".$TMP_URLaddr."&V_faorder=nombre&V_faorderDESC=x\"><img src='images/up.gif' border=0> ";
	echo "Nombre</a></th><th class=browse>";
	if ($V_faorder!="email") echo "<a href=\"".$TMP_URLaddr."&V_faorder=email\">";
	else if ($V_faorderDESC!="") echo "<a href=\"".$TMP_URLaddr."&V_faorder=email\"><img src='images/down.gif' border=0> ";
	else echo "<a href=\"".$TMP_URLaddr."&V_faorder=email&V_faorderDESC=x\"><img src='images/up.gif' border=0> ";
	echo "Correo Electronico</a></th><th class=browse>";
	if ($V_faorder!="empresa") echo "<a href=\"".$TMP_URLaddr."&V_faorder=empresa\">";
	else if ($V_faorderDESC!="") echo "<a href=\"".$TMP_URLaddr."&V_faorder=empresa\"><img src='images/down.gif' border=0> ";
	else echo "<a href=\"".$TMP_URLaddr."&V_faorder=empresa&V_faorderDESC=x\"><img src='images/up.gif' border=0> ";
	echo "Empresa</a></th></tr>\n";
	if ($RAD_tabla_direcciones=="GIE_entidades") {
		$cmd="SELECT * FROM GIE_entidades where (email!='' and email is not null)";
		if ($V_faval!="") $cmd.=" and (entidad like '%".$V_faval."' or email like '%".$V_faval."%')";
		if ($V_faorder=="nombre") $cmd.=" ORDER BY entidad";
		if ($V_faorder=="email") $cmd.=" ORDER BY email";
		if ($V_faorder=="empresa") $cmd.=" ORDER BY entidad";
		if ($V_faorderDESC!="") $cmd.=" DESC";
	} else {
		$cmd="SELECT * FROM direcciones";
		if ($V_faval!="") $cmd.=" where nombre like '%".$V_faval."' or email like '%".$faval."%'";
		if ($V_faorder=="nombre") $cmd.=" ORDER BY nombre";
		if ($V_faorder=="email") $cmd.=" ORDER BY email";
		if ($V_faorder=="empresa") $cmd.=" ORDER BY empresa";
		if ($V_faorderDESC!="") $cmd.=" DESC";
	}
	$result=sql_query($cmd, $RAD_dbi);
	while($TMP_row=sql_fetch_array($result, $RAD_dbi)) {
		if ($RAD_tabla_direcciones=="GIE_entidades") {
			$TMP_row[nombre]=$TMP_row[entidad];
			if ($TMP_row[representante]!="") {
				$TMP_row[nombre]=$TMP_row[representante];
				$TMP_row[empresa]=$TMP_row[entidad];
			}
		}
		if ($RAD_classrow=="class=row1") $RAD_classrow="class=row2";
		else $RAD_classrow="class=row1";
		$TMP_email=$TMP_row[email];
		if (_DEF_POPUP_MARGIN=="SUBMODAL") $TMP_javascript="javascript:parent.document.F2.".$V_fa.".value=\"".$TMP_email."\";RAD_CloseW(false)";
		else $TMP_javascript="javascript:window.opener.document.F2.".$V_fa.".value=\"".$TMP_email."\";window.close()";
		echo "<tr><td $RAD_classrow><a href='$TMP_javascript'>".$TMP_row[nombre]." </a></td><td $RAD_classrow><a href='$TMP_javascript'>".$TMP_row[email]." </a></td><td $RAD_classrow><a href='$TMP_javascript'>".$TMP_row[empresa]." </a></td></tr>\n";
	}
	echo "\n</table>\n";
	return "";
    }
    $TMP_URLaddr=$_SERVER[REQUEST_URI]."&op=addr";
    $_SERVER[QUERY_STRING]=str_replace("subfunc=sendlist","subfunc=list",$_SERVER[QUERY_STRING]);
    $_SERVER[QUERY_STRING]=str_replace("V_subfunc=sendlist","V_subfunc=list",$_SERVER[QUERY_STRING]);

    if (count($HTTP_GET_VARS)>0) {
    	    foreach ($HTTP_GET_VARS as $TMP_key=>$TMP_val) {
		if (!is_array($TMP_val)) {
			if(get_magic_quotes_gpc()) { 
				$HTTP_GET_VARS[$TMP_key] = stripslashes ($TMP_val);
				$TMP_val = stripslashes ($TMP_val);
			}
//			if ($TMP_key=="subfunc") $TMP_content.="<input type=hidden name='subfunc' value='list'>\n";
			if ($TMP_key=="subfunc" && $subfunc=="sendlist") $TMP_content.="<input type=hidden name='subfunc' value=''>\n";
			else if ($TMP_key=="V_subfunc" && $V_subfunc=="sendlist") $TMP_content.="<input type=hidden name='V_subfunc' value=''>\n";
		        else $TMP_content.="<input type=hidden name='".$TMP_key."' value='".$TMP_val."'>\n";
		}
	    }
    }

    if ($func!="none" || $doc=="") {
	$menuoff="x";
	$func="none";
	$bodyoff="x";
	include_once("modules/$V_dir/".$V_mod.".php");
	echo "<br>";
        OpenTable();
	echo "<form action='".$PHP_SELF."' method=get name=F1>\n";
	///////foreach($_REQUEST as $TMP_k=>$TMP_v) if ($TMP_v!="" && !in_array($TMP_k,array("V_to","V_from"))) echo "<input type=hidden name=$TMP_k value='$TMP_v'>".$TMP_k."=".$TMP_v."<br>";
    } else {
	echo "<br>";
	OpenTable();
    }
    echo $TMP_content;

    $TMP_V_from=$HTTP_SESSION_VARS["SESSION_U_email"];
    if (_DEF_EMAIL_SERVER!="" && $TMP_V_from=="") $TMP_V_from=base64_decode($HTTP_SESSION_VARS["SESSION_user"])."@"._DEF_EMAIL_SERVER;
    if ($func!="none" || $doc=="") {
/*
	echo "</td><td class=borde><img src='images/save_as.gif' border=0> <b>Guardar Como</b></td></tr>";
	//echo "<tr><td align=right><b>Guardar como : </b></td><td><input type=radio name=V_typePrint value=PDF> Fichero PDF | <input type=radio name=V_typePrint value=HTML checked> Fichero HTML | <input type=radio name=V_typePrint value=CSV> Fichero CSV";
	echo "<tr><td align=right><b>Guardar como : </b></td><td> <input type=radio name=V_typePrint value=HTML checked> Fichero HTML | <input type=radio name=V_typePrint value=CSV> Fichero CSV";
	$TMP_cont=0;
	if (RAD_existTable("impresos") && $tablename!="") {
	    $TMP_result = sql_query("SELECT * FROM impresos WHERE tabla='$tablename'", $RAD_dbi);
    	    while($TMP_row=sql_fetch_array($TMP_result, $RAD_dbi)) {
		$TMP_cont++;
		$TMP_impreso.="<option value='".$TMP_row[idimpreso]."'>".$TMP_row[impreso]."</option>";
	    }
	    if ($TMP_cont>0) {
		echo " | <input type=radio name=V_typePrint value='impreso'> Tipo: <select name=V_idimpreso onChange='javascript:if(document.F1.V_idimpreso.selectedIndex>0) document.F1.V_typePrint[3].checked=true;'><option value=''></option>".$TMP_impreso."</select>";
	    }
	}
	echo "</td></tr><tr><th colspan=2><br><center><input type=hidden value='' name=V_send><input type=submit name=V_save value=' Guardar '></center><br></th></tr></form>\n";
	CloseTable();
	echo "<br>";
	OpenTable();
*/
	echo "</td><td class=borde><img src='images/mail.gif' border=0><b> Enviar registro como Fichero en Email</b></td></tr>";
    } else {
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "</td><td class=borde><b>Enviar Fichero</b></td></tr>";
    }
    echo "\n</form>\n<form action='".$PHP_SELF."' method=get name=F2>\n";
    echo "<tr><td align=right><b>De : </b></td><td><input type=text size=40 name=V_from value='".$TMP_V_from."'></td></tr>";
    echo "<tr><td align=right><b>Para : </b></td><td><input type=text size=40 name=V_to value='".$RAD_sendTo."'> <a href='javascript:RAD_OpenW(\"".$TMP_URLaddr."&V_fa=V_to&V_faval=\"+document.F2.V_to.value,600,600)'><img src='images/direccion.gif' border=0></a></td></tr>";
    echo $TMP_content;
    ////echo "<tr><td align=right><b>CC : </b></td><td><input type=text size=80 name=V_cc> <a href='javascript:RAD_OpenW(\"".$TMP_URLaddr."&V_fa=V_cc\",400,400)'><img src='images/direccion.gif' border=0></a></td></tr>";
    ////echo "<tr><td align=right><b>BCC : </b></td><td><input type=text size=80 name=V_bcc> <a href='javascript:RAD_OpenW(\"".$TMP_URLaddr."&V_fa=V_bcc\",400,400)'><img src='images/direccion.gif' border=0></a></td></tr>";

    if ($func!="none" || $doc=="") {
	//echo "<tr><td align=right nowrap><b>Enviar como : </b></td><td><input type=radio name=V_typeSend value=PDF> Fichero PDF | <input type=radio name=V_typeSend value=HTML checked> Fichero HTML | <input type=radio name=V_typeSend value=CSV> Fichero CSV";
	echo "<tr><td align=right nowrap><b>Enviar como : </b></td><td> <input type=radio name=V_typeSend value=HTML checked> Fichero HTML | <input type=radio name=V_typeSend value=CSV> Fichero CSV";
	if ($TMP_cont>0) {
    	    //echo " | <input type=radio name=V_typePrint value='impreso'> Tipo: <select name=V_idimpreso onChange='javascript:if(document.F2.V_idimpreso.selectedIndex>0) document.F2.V_typePrint[3].checked=true;'><option value=''></option>".$TMP_impreso."</select>";
	}
    } else {
	echo "<input type=hidden name=V_typeSend value='FILE'>";
	echo "<tr><td align=right><b>Enviar Fichero : </b></td><td><input type=hidden name=V_doc value='".$doc."'><a href='$doc' target=_blank>".substr($doc,7+strlen($dbname))."</a></td></tr>";
    }

    echo "</td></tr><tr><td align=right><b>Asunto : </b></td><td><input type=text size=80 name=V_asunto value='".$RAD_sendSubject."'></td></tr>";
    echo "<tr><td align=right><b>Mensaje : </b></td><td><textarea cols=90 rows=10 name=V_mensaje>".$RAD_sendBody."</textarea></td></tr>";
    echo "<tr><th colspan=2><br><center><input type=hidden value='' name=V_save><input type=submit name=V_send value=' Enviar '></center></th></tr>";
    echo "<tr><td colspan=2 align=right><a href='javascript:";
    if (_DEF_POPUP_MARGIN=="SUBMODAL") echo "closePop();";
    else echo "window.close();";
    echo "'><img src='images/close.gif' border=0> Cerrar</a> </td></tr>";
    echo "</form>";
    CloseTable();
}
?>
