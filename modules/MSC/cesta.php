<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

	global $RAD_dbi, $V_dir, $PHPSESSID, $op, $idventa, $idses, $id, $V0_ud, $totid, $TMP_IVA, $op2;

	$TMP_dirtheme=getSessionVar("SESSION_theme");

	if (file_exists("modules/".$V_dir."/common.app.php")) include_once ("modules/".$V_dir."/common.app.php");
	if (_DEF_appname!="" && _DEF_appname!="_DEF_appname") if (file_exists("modules/$V_dir/common.app."._DEF_appname.".php")) include_once ("modules/$V_dir/common.app."._DEF_appname.".php");
	if (_DEF_appinstance!="" && _DEF_appinstance!="_DEF_appinstance") if (file_exists("modules/$V_dir/common.app."._DEF_appinstance.".php")) include_once ("modules/$V_dir/common.app."._DEF_appinstance.".php");

	include_once("modules/".$V_dir."/lib.Email.php");

	if ($op=="OK" || $op=="KO" || $op=="NOTIF") {
		//$headeroff="x";
		//$footeroff="x";
		//$blocksoff="x";
	}

	$TMP_idventa=""; // inicializa variable
	if ($idventa!="") $idventa=$idventa*1;

// OJO: Se comprueba que el GET se hace desde CECA/Redsys/Sermepa

//--------------------------------------------------------------------------------------------------------------
if ($op=="OK"||$op=="NOTIF"||$op=="KO") {
  if ($NODEBUG=="") {
	$TMP_deb="";
	$DEBUG_vars = Array('GET', 'POST', 'COOKIE', 'SERVER', 'ENV', 'REQUEST', 'SESSION');
	for($i=0; $i<sizeof($DEBUG_vars); $i++) {
		global ${"HTTP_{$DEBUG_vars[$i]}_VARS"};
		if(is_array(${"HTTP_{$DEBUG_vars[$i]}_VARS"})) {
			$TMP_deb.="<! ".$DEBUG_vars[$i]." >\n";
			foreach(${"HTTP_{$DEBUG_vars[$i]}_VARS"} as $var=>$value) {
				if(!is_array($var)) {
					$TMP_deb.="<! ".$var."=".$value." >\n";
				} else {
					foreach($var as $var2=>$value2) {
						if (is_array($var2)) {
							$TMP_deb.="<! ".$var."[";
							$TMP_deb.=print_r($var2,true);
							$TMP_deb.="]=".$value." >\n";
						} else {
							$TMP_deb.="<! ".$var."[".$var2."]=".$value." >\n";
						}
					}
				}
			}
		}
	}
	$tmpFile="files/tmp/cesta.".uniqid("");
	$fp = fopen($tmpFile,"w");
	fwrite($fp,$TMP_deb);
	fclose($fp);
  }
}

//--------------------------------------------------------------------------------------------------------------
$TMP_print="<script language='JavaScript'>
function doPrint(){
 document.getElementById('noprint').style.display='none'
 window.print()
 document.getElementById('noprint').style.display='inline'
}
</script>
<div id=noprint>
<form name='PRN'>
<input type='button' value='Imprimir' name='BPRN' onclick='doPrint();'>
</form>
</div>
";

$TMP_lang=getSessionVar("SESSION_lang");
//--------------------------------------------------------------------------------------------------------------
if (substr(getenv("REMOTE_ADDR"),0,9)=="80.68.128" || substr(getenv("REMOTE_ADDR"),0,9)=="80.68.129" || substr(getenv("REMOTE_ADDR"),0,9)=="80.68.130" || substr(getenv("REMOTE_ADDR"),0,9)=="80.68.131") { // CECA
	$cmdSQL2 = "";
	if ($Codigo_pedido=="") {
		if (substr(strtoupper($_REQUEST["Descripcion"]),0,8)=="DONATIVO") {
			$Codigo_pedido="D".$Num_operacion;
		} else {
			$Codigo_pedido="V".$Num_operacion;
		}
	}
	if (($op=="OK"||$op=="NOTIF") && $Codigo_pedido!="") { // si el mensaje de NOTIF procede de pasarela de CECA
		if (substr($Codigo_pedido,0,1)=="V") {
			$idventa=substr($Codigo_pedido,1);
			$cmdSQL2 = "UPDATE GIE_ventas SET cobrado='1',fechacobro='".date("Y-m-d H:i:s")."' WHERE idventa='$idventa'";
			$TMP_result2 = sql_query($cmdSQL2, $RAD_dbi);
		}
		if (substr($Codigo_pedido,0,1)=="D") {
			$iddonativo=substr($Codigo_pedido,1);
			$cmdSQL2 = "UPDATE donativos SET cobrado='1',fechacobro='".date("Y-m-d H:i:s")."' WHERE iddonativo='$iddonativo'";
			$TMP_result2 = sql_query($cmdSQL2, $RAD_dbi);
		}
	}
	$tmpFile="files/tmp/CECA.".uniqid("");
	$fp = fopen($tmpFile,"w");
	fwrite($fp,$cmdSQL2."\n".$TMP_deb);
	fclose($fp);
	return "OK";
}
if (($op=="OK"||$op=="NOTIF") && $idventa>0) { // el mensaje de NOTIF solo puede proceder de la pasarela de pagos (confirmacion del pago)
	$TMP_idventa=$idventa;
	$TMP_result = sql_query("SELECT * FROM GIE_ventas WHERE idventa='$idventa' AND (sesioncesta='$idses' OR sesioncesta LIKE '$idses%')", $RAD_dbi);
	$TMP_row = sql_fetch_array($TMP_result, $RAD_dbi);
	foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
	if ($TMP_row[idventa]!=$idventa) die("Error Pedido Web $idventa inexistente");
	if ($op=="NOTIF" && $_REQUEST["Ds_Response"]=="0000") { // Pago CECA/Redsys/Sermepa/Paypal
  		if (getenv("REMOTE_ADDR")!="195.76.9.187" && getenv("REMOTE_ADDR")!="193.16.243.13"
		 && getenv("REMOTE_ADDR")!="195.76.9.117" && getenv("REMOTE_ADDR")!="195.76.9.149"
		 && getenv("REMOTE_ADDR")!="193.16.243.173" && getenv("REMOTE_ADDR")!="195.76.9.222"
		 && getenv("REMOTE_ADDR")!="sis-t.sermepa.es" && getenv("REMOTE_ADDR")!="sis.sermepa.es" 
		 && getenv("REMOTE_ADDR")!="www.paypal.com" && getenv("REMOTE_ADDR")!="www.sandbox.paypal.com" 
		 && getenv("REMOTE_ADDR")!="23.54.82.234" && getenv("REMOTE_ADDR")!="173.0.82.77" 
		 && getenv("REMOTE_ADDR")!="sis-t.redsys.es" && getenv("REMOTE_ADDR")!="sis.redsys.es" ) {
			die(_DEF_NLSCestaErr1." <! ".getenv("REMOTE_ADDR")." ".$op." >");
		}
		//$sesioncesta=$TMP_row[sesioncesta]." ".uniqid(); // se le cambia sesion para que no se vuelva a tocar
		$cmdSQL2 = "UPDATE GIE_ventas SET sesioncesta='$sesioncesta', cobrado='1',fechacobro='".date("Y-m-d H:i:s")."' WHERE idventa='$idventa'";
		$cmdSQL2 = "UPDATE GIE_ventas SET cobrado='1',fechacobro='".date("Y-m-d H:i:s")."' WHERE idventa='$idventa'";
		$TMP_result2 = sql_query($cmdSQL2, $RAD_dbi);
		$TMP_row[cobrado]="1";
	}
	$TMP_result = sql_query("SELECT * FROM GIE_ventas WHERE idventa='$idventa' AND (sesioncesta='$idses' OR sesioncesta LIKE '$idses%')", $RAD_dbi);
	$TMP_row = sql_fetch_array($TMP_result, $RAD_dbi);
	foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
	//$op="show";
	$TMP_resultv = sql_query("SELECT * FROM usuarios WHERE usuario='".$TMP_row[refcliente]."'", $RAD_dbi);
	$TMP_rowv = sql_fetch_array($TMP_resultv, $RAD_dbi);
	if ($TMP_row[email]!="") $TMP_rowv[email]=$TMP_row[email];
	$TMP_content=modCesta($TMP_idventa,"show");
	$TMP_content.=formasEnvioCesta($TMP_idventa,"show");
	$TMP_content.=formDatos("show");
	if ($TMP_row[enviadoemail]!="1" && $op=="NOTIF" && $_POST["Ds_Response"]=="0000") {
		F_SendMail(_DEF_ADMINMAIL,_DEF_ADMINMAILNAME,$TMP_rowv[email],"",_DEF_NLSCestaPedPagEn._DEF_URL, $TMP_content, "", "", true); // from,to
		sleep(1);
		F_SendMail(_DEF_ADMINMAIL,_DEF_ADMINMAILNAME,_DEF_ADMINMAIL,"",_DEF_NLSCestaCorreoPedPagEn._DEF_URL.". "._DEF_NLSCestaIdPedido."=".$TMP_row[idventa], $TMP_content, "", "", true); // from,to
		$cmdSQL2 = "UPDATE GIE_ventas SET enviadoemail='1' WHERE idventa='$idventa'";
		$TMP_result2 = sql_query($cmdSQL2, $RAD_dbi);
	}
	include_once ("header.php");
	echo "<h1>"._DEF_NLSCesta."</h1>";
	OpenTable();
	//if ($TMP_row[cobrado]=="1") echo "<h3>"._DEF_NLSCestaPedidoPagadoTit."</h3><br />".$TMP_content."\n<script>\nwindow.print();\n</script>\n";
	if ($TMP_row[cobrado]=="1") {
		echo "<h3>"._DEF_NLSCestaPedidoPagadoTit."</h3><br />".$TMP_content."\n".$TMP_print;		
		$TMP_total=RAD_lookup("GIE_ventas","total","idventa",$idventa);
	} else {
		F_SendMail(_DEF_ADMINMAIL,_DEF_ADMINMAILNAME,_DEF_ADMINMAIL,"",_DEF_NLSCestaErr2."=".$TMP_idventa, $TMP_content, "", "", true); // from,to
		echo _DEF_NLSCestaErr3.$TMP_content."\n".$TMP_print;
	}
	CloseTable();	
	
	include_once("modules/".$V_dir."/lib.ajax.php");
	include_once ("footer.php"); 
	return;
}

//--------------------------------------------------------------------------------------------------------------
if (($op=="KO"||$op=="print") && $idventa>0) {
	//$TMP_result = sql_query("SELECT * FROM GIE_ventas WHERE idventa='$idventa' AND sesioncesta='$idses'", $RAD_dbi);
	$TMP_result = sql_query("SELECT * FROM GIE_ventas WHERE idventa='$idventa' AND (sesioncesta='$idses' OR sesioncesta LIKE '$idses%')", $RAD_dbi);
	$TMP_row = sql_fetch_array($TMP_result, $RAD_dbi);
	foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
	if ($TMP_row[idventa]!=$idventa) die("Error Pedido Web $idventa inexistente");
	$TMP_idventa=$idventa;
	//$op="show";
	$TMP_resultv = sql_query("SELECT * FROM usuarios WHERE usuario='".$TMP_row[refcliente]."'", $RAD_dbi);
	$TMP_rowv = sql_fetch_array($TMP_resultv, $RAD_dbi);
	if ($TMP_row[email]!="") $TMP_rowv[email]=$TMP_row[email];
	$TMP_content=modCesta($TMP_idventa,"show");
	$TMP_content.=formasEnvioCesta($TMP_idventa,"show");
	$TMP_content.=formDatos("show");
	//F_SendMail($from,"",$to,$bcc,$subject,$body,$altbody,$adjuntos="",$html=true);
	if ($TMP_row[cobrado]=="1") $TMP_pagado=_DEF_NLSCestaPedPagEn;
	else $TMP_pagado=_DEF_NLSCestaPedNoPagEn;
	if ($op=="KO") F_SendMail(_DEF_ADMINMAIL,_DEF_ADMINMAILNAME,$TMP_rowv[email],"",$TMP_pagado._DEF_URL, $TMP_content, "", "", true); // from,to
	include_once ("header.php");
	echo "<h1>"._DEF_NLSCesta."</h1>";
	OpenTable();
	echo "<h3>";
	if ($TMP_row[cobrado]=="1") echo _DEF_NLSCestaPedidoPagadoTit;
	else echo _DEF_NLSCestaPedidoNoPagadoTit;
	echo "</h3><br />".$TMP_content.$TMP_print;
	CloseTable();
	include_once("modules/".$V_dir."/lib.ajax.php");
	include_once ("footer.php"); 
	return;
}

//--------------------------------------------------------------------------------------------------------------
if ($op=="list") {
	include_once ("header.php");
	echo "<h1>"._DEF_NLSCesta."</h1>";
	OpenTable();
	echo listaPedidos();
	//if ($idventa>0) {
	//	$TMP_result = sql_query("SELECT * FROM GIE_ventas WHERE idventa='$idventa' AND sesioncesta='$idses'", $RAD_dbi);
	//	$TMP_row = sql_fetch_array($TMP_result, $RAD_dbi);
	//	echo showCesta($idventa,"show");
	//}
	CloseTable();
	include_once("modules/".$V_dir."/lib.ajax.php");
	include_once ("footer.php"); 
	return "";
}

//--------------------------------------------------------------------------------------------------------------
if ($op=="" && $op2!="") { $op=$op2; $conf=""; $_REQUEST[conf]=""; }
if ($op=="") $op="showmod";
if ($V0_ud=="") $V0_ud="1";
	
// Comprueba para Caducar Cesta
	if ($idses=="") $TMP_result = sql_query("SELECT * FROM GIE_ventas WHERE ventatipo='VIN' AND (procesado='0' OR procesado IS NULL) AND sesioncesta='$PHPSESSID' ORDER BY fechaventa DESC", $RAD_dbi);
	else $TMP_result = sql_query("SELECT * FROM GIE_ventas WHERE ventatipo='VIN' AND (procesado='0' OR procesado IS NULL) AND (sesioncesta='$idses' OR sesioncesta LIKE '$idses%') ORDER BY fechaventa DESC", $RAD_dbi);
	while($TMP_row = sql_fetch_array($TMP_result, $RAD_dbi)) { 
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
		$TMP_fechaventa=strtotime($TMP_row[fechaventa]);
		if ($TMP_row[refcliente]!=base64_decode(getSessionVar("SESSION_user"))) {
			if ((time()-$TMP_fechaventa)>36000) { // Cesta caducada
				$cmdSQL2 = "UPDATE GIE_ventas SET procesado='X' WHERE idventa='".$TMP_row[idventa]."'";
				$TMP_result2 = sql_query($cmdSQL2, $RAD_dbi);
			}
		}
	}

// Busca cesta (no procesada) de esta sesion
	if ($idses=="") $TMP_result = sql_query("SELECT * FROM GIE_ventas WHERE ventatipo='VIN' AND (cobrado='0' OR cobrado='' OR cobrado IS NULL) AND (procesado='1' OR procesado='0' OR procesado IS NULL) AND sesioncesta='$PHPSESSID' ORDER BY fechaventa DESC", $RAD_dbi);
	else $TMP_result = sql_query("SELECT * FROM GIE_ventas WHERE ventatipo='VIN' AND (cobrado='0' OR cobrado='' OR cobrado IS NULL) AND (procesado='1' OR procesado='0' OR procesado IS NULL) AND (sesioncesta='$idses' OR sesioncesta LIKE '$idses%') ORDER BY fechaventa DESC", $RAD_dbi);
	//$TMP_result = sql_query("SELECT * FROM GIE_ventas WHERE ventatipo='VIN' AND sesioncesta='$PHPSESSID' ORDER BY fechaventa DESC", $RAD_dbi);
	$TMP_row = sql_fetch_array($TMP_result, $RAD_dbi);
	foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
	$TMP_idventa=$TMP_row[idventa];
	if (is_user() && $TMP_idventa>0 && $TMP_row[refcliente]!=base64_decode(getSessionVar("SESSION_user"))) { // cambio de usuario
		$TMP_res2=sql_query("SELECT * FROM usuarios WHERE usuario='".base64_decode(getSessionVar("SESSION_user"))."'", $RAD_dbi);
		$TMP_row2 = sql_fetch_array($TMP_res2, $RAD_dbi);
		$TMP_vals=", nombrecliente=".converttosql($TMP_row2[nombre]).", direccion=".converttosql($TMP_row2[domicilio]).", email=".converttosql($TMP_row2[email]).", telefono=".converttosql($TMP_row2[telefono]).", observaciones2='', codprovincia=".converttosql($TMP_row2[provincia]).", localidad=".converttosql($TMP_row2[localidad]).", codpostal=".converttosql($TMP_row2[codpostal])."";
		$TMP_cmd="UPDATE GIE_ventas SET refcliente='".base64_decode(getSessionVar("SESSION_user"))."'".$TMP_vals." WHERE idventa='$TMP_idventa'";
		//$TMP_cmd="UPDATE GIE_ventas SET refcliente='".base64_decode(getSessionVar("SESSION_user"))."' WHERE idventa='$TMP_idventa'";
		sql_query($TMP_cmd, $RAD_dbi);
		if ($TMP_row[refcliente]!="") { // si la cesta anterior era de otro usuario la vacia y la limpia
			$cmdSQL2 = "UPDATE GIE_ventasdetalle sET idventa='0' WHERE idventa='$TMP_idventa'"; // quita articulos de cesta al cambiar de usuario
			$TMP_result2 = sql_query($cmdSQL2, $RAD_dbi);
			$cmdSQL2 = "UPDATE GIE_ventas sET  nombrecliente='',direccion='',email='',telefono='',observaciones2='', formaenvio='', gastosenvio='' WHERE idventa='$TMP_idventa'";
			$TMP_result2 = sql_query($cmdSQL2, $RAD_dbi);
		}
	}
	if (!is_user() && $TMP_idventa>0 && $TMP_row[refcliente]!="" && $TMP_row[cobrado]!="1") { // usuario que sale, se limpia su cesta
		$TMP_res2=sql_query("SELECT * FROM usuarios WHERE usuario='".base64_decode(getSessionVar("SESSION_user"))."'", $RAD_dbi);
		$TMP_row2 = sql_fetch_array($TMP_res2, $RAD_dbi);
		$TMP_vals=", nombrecliente=".converttosql($TMP_row2[nombre]).", direccion=".converttosql($TMP_row2[domicilio]).", email=".converttosql($TMP_row2[email]).", telefono=".converttosql($TMP_row2[telefono]).", observaciones2='', codprovincia=".converttosql($TMP_row2[provincia]).", localidad=".converttosql($TMP_row2[localidad]).", codpostal=".converttosql($TMP_row2[codpostal])."";
		$TMP_cmd="UPDATE GIE_ventas SET refcliente='".base64_decode(getSessionVar("SESSION_user"))."'".$TMP_vals." WHERE idventa='$TMP_idventa'";
		//$TMP_cmd="UPDATE GIE_ventas SET refcliente='".base64_decode(getSessionVar("SESSION_user"))."' WHERE idventa='$TMP_idventa'";
		sql_query($TMP_cmd, $RAD_dbi);
		$cmdSQL2 = "UPDATE GIE_ventasdetalle SeT idventa='0' WHERE idventa='$TMP_idventa'"; // quita articulos de cesta al cambiar de usuario
		$TMP_result2 = sql_query($cmdSQL2, $RAD_dbi);
	}
	if ($TMP_idventa>0 && $TMP_row[formaenvio]!=$_REQUEST[V0_formaenvio] && $_REQUEST[V0_formaenvio]>0) {
		$TMP_result2 = sql_query("SELECT * FROM GIE_formasenvio WHERE id=".converttosql($_REQUEST[V0_formaenvio]), $RAD_dbi);
		$TMP_row2 = sql_fetch_array($TMP_result2, $RAD_dbi);
		$TMP_cmd="uPDATE GIE_ventas SeT formaenvio=".converttosql($_REQUEST[V0_formaenvio]).", gastosenvio='".$TMP_row2[precio]."' WHERE idventa='$TMP_idventa'";
		sql_query($TMP_cmd, $RAD_dbi);
	}
	if ($op=="borrar" && $TMP_idventa>0) {
		$cmdSQL2 = "UPDATE GIE_ventasdetalle SET idventa='0' WHERE idventa='$TMP_idventa'";
		$TMP_result2 = sql_query($cmdSQL2, $RAD_dbi);
		$cmdSQL2 = "UPDATE GIE_ventas SET refcliente='', sesioncesta='x' WHERE idventa='$TMP_idventa'";
		$TMP_result2 = sql_query($cmdSQL2, $RAD_dbi);
		$TMP_idventa=0;
		$op="showmod";
	}
	if ($op=="update" || $op=="add") {
		$TMP_result2 = sql_query("SELECT * FROM GIE_ventas WHERE idventa='".$TMP_idventa."'", $RAD_dbi);
		$TMP_row2 = sql_fetch_array($TMP_result2, $RAD_dbi);
		//if ($TMP_row2[cobrado]=="1") {
		//	alert(_DEF_NLSCestaErr4);
		//	$op="showmod";
		//}
	}
	if ($op=="update") {
		if ($TMP_idventa=="") {
			alert(_DEF_NLSCestaErr5);
			return "";
		}
		//////$codigodescuento=RAD_lookup("GIE_ventas","codigodescuento","idventa",$TMP_idventa);
		if ($totid>0) for ($i=0; $i<$totid; $i++) {
			global ${"id".$i}, ${"uds".$i}, ${"olduds".$i}, ${"del".$i};
			if (${"del".$i}!="") {
				$cmdSQL2 = "UPDATE GIE_ventasdetalle SET idventa='0' WHERE idventadetalle='".${"id".$i}."' AND idventa='$TMP_idventa'";
				$TMP_result2 = sql_query($cmdSQL2, $RAD_dbi);
			}
			${"uds".$i}=str_replace(",",".",${"uds".$i});
			if (!is_numeric(${"uds".$i})) ${"uds".$i}=1;
			if (${"uds".$i}<0.01) ${"uds".$i}=1;
			if (${"uds".$i}!=${"olduds".$i}) {
				$TMP_unidades=${"uds".$i};
				$TMP_result2 = sql_query("SELECT * FROM GIE_ventasdetalle WHERE idventadetalle='".${"id".$i}."' AND idventa='$TMP_idventa'", $RAD_dbi);
				$TMP_row2 = sql_fetch_array($TMP_result2, $RAD_dbi);
				$TMP_cantidadminima=RAD_lookup("GIE_articulos","cantidadminima","idarticulo",$TMP_row2[idarticulo]);
				if ($TMP_cantidadminima>0 && $TMP_cantidadminima>$TMP_unidades) $TMP_unidades=$TMP_cantidadminima;
				$TMP_precio=$TMP_row2[precio];
				$TMP_descuentoporc=consultaDescuento($TMP_row2[idarticulo],$codigodescuento); // Consulta el descuento del Articulo/Familia/Usuario
				if ($TMP_descuentoporc!=0) $TMP_total=round(($TMP_row2[precio]*(100-$TMP_descuentoporc)/100)*$TMP_unidades,2);
				else $TMP_total=$TMP_row2[precio]*$TMP_unidades;
				$cmdSQL2 = "UPDATE GIE_ventasdetalle SET descuentoporc='$TMP_descuentoporc', total='$TMP_total', precio='$TMP_precio', impuestos='$TMP_impuestos', unidades='$TMP_unidades' WHERE idventadetalle='".${"id".$i}."' AND idventa='$TMP_idventa'";
				$TMP_result2 = sql_query($cmdSQL2, $RAD_dbi);
			}
			recalculaTotalOperacion("venta",$TMP_idventa);
		}
		$op="showmod";
	}

	if ($op=="showmod") $TMP_content=modCesta($TMP_idventa,$op);
	if ($op=="show") $TMP_content=modCesta($TMP_idventa,$op);
	if ($op=="add") {
		$TMP_result3 = sql_query("SELECT * FROM GIE_articulos WHERE idarticulo='".$id."'", $RAD_dbi);
		$TMP_row3 = sql_fetch_array($TMP_result3, $RAD_dbi);
		foreach($TMP_row3 as $TMP_k=>$TMP_v) if ($TMP_row3[$TMP_k."_".$TMP_lang]!="") $TMP_row3[$TMP_k]=$TMP_row3[$TMP_k."_".$TMP_lang];
		if ($TMP_row3[muestratienda]!="1") error(_DEF_NLSCestaErr6);

		$TMP_content=addCesta($TMP_idventa,$id,$V0_ud);
		echo "<script>\ndocument.location.href=document.referrer;\n</script>\n";
//		$TMP_content.=modCesta($TMP_idventa,"showmod");
	}
	if ($op=="savedatos") {
		$TMP_content.=grabaDatosForm($TMP_idventa);
		$op="pago";
	}
	$TMP_res=sql_query("select count(*) from GIE_formasenvio", $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
	$TMP_numformasenvio=$TMP_row[0];
	if ($op=="datos" && !$TMP_row[formaenvio]>0 && $TMP_numformasenvio>0) {
		$TMP_res2=sql_query("SELECT * FROM GIE_ventasdetalle WHERE idventa='$TMP_idventa'", $RAD_dbi);
		$TMP_row2=sql_fetch_array($TMP_res2, $RAD_dbi);
		if ($TMP_row2[idventadetalle]>0 && $TMP_idventa>0) {
			alert("Debe seleccionar Forma de Envio antes de Pagar");
			$op="get";
			$TMP_content=modCesta($TMP_idventa,"show");			
			////$TMP_content=modCesta($TMP_idventa,"pago");			
			////$TMP_content.=formasEnvioCesta($TMP_idventa,"showmod");
		} else {
			echo "\n<script>\ndocument.location.href='index.php?V_dir=$V_dir&V_mod=$V_mod&op=showmod';\n</script>\n";
			die("");
		}
	}
	if ($op=="pago"||$op=="pagar"||$op=="pagarpaypal") {
		recalculaTotalOperacion("venta",$TMP_idventa);
		$TMP_res=sql_query("SELECT * FROM GIE_ventas WHERE idventa='$TMP_idventa'", $RAD_dbi);
		$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
	}
	if (($op=="pago"||$op=="pagar"||$op=="pagarpaypal") && !$TMP_row[formaenvio]>0 && $TMP_numformasenvio>0) {
		$TMP_res2=sql_query("SELECT * FROM GIE_ventasdetalle WHERE idventa='$TMP_idventa'", $RAD_dbi);
		$TMP_row2=sql_fetch_array($TMP_res2, $RAD_dbi);
		if ($TMP_row2[idventadetalle]>0 && $TMP_idventa>0) {
			alert(_DEF_NLSCestaErr7);
			$op="datos";
		} else {
			echo "\n<script>\ndocument.location.href='index.php?V_dir=$V_dir&V_mod=$V_mod&op=showmod';\n</script>\n";
			die("");
		}
	}
	if ($op=="get" || $op=="pago" || $op=="pagar" || $op=="pagarpaypal" || $op=="datos") {
		recalculaTotalOperacion("venta",$TMP_idventa);
		$TMP_res2=sql_query("SELECT * FROM GIE_ventasdetalle WHERE idventa='$TMP_idventa'", $RAD_dbi);
		$TMP_row2=sql_fetch_array($TMP_res2, $RAD_dbi);
		if ($TMP_row2[idventadetalle]>0) {
			//$TMP_content=modCesta($TMP_idventa,"show");			
			if ($op=="get") {
				$TMP_content=modCesta($TMP_idventa,"realizar_pedido");			
				$TMP_content.=formasEnvioCesta($TMP_idventa,"showmod");
			}
		} else {
			echo "\n<script>\ndocument.location.href='index.php?V_dir=$V_dir&V_mod=$V_mod&op=showmod';\n</script>\n";
			die("");
		}
	}
	if ($op=="datos") {
		$TMP_content=misDatosForm();
	}
	if ($op=="pago") {
		//$TMP_content.=modCesta($TMP_idventa,"show");
		$TMP_content.=modCesta($TMP_idventa,"pago");
		$TMP_content.=formasEnvioCesta($TMP_idventa,"show");
		$TMP_content.=formDatos("show");
		if ($TMP_row[cobrado]!="1") {
			$TMP_hidden="";
			foreach($_REQUEST as $TMP_k=>$TMP_v) if ($TMP_k!="captcha_code" && substr($TMP_k,0,2)!="V0" && $TMP_k!="op") $TMP_hidden.="<input type=hidden name=$TMP_k value='".$TMP_v."'>";
			$TMP_content.="<br /><div align=right><table border=0 cellspacing=5><tr>";
			if ($_REQUEST["domiciliacion"]=="tarjeta") $TMP_content.="<td><form name=P action='$PHP_SELF' target=_blank>".$TMP_hidden."<input type=hidden name=op value='pagar'><input type='button' class='boton' onclick='javascript:document.forms.P.submit();' value='"._DEF_NLSCestaPagarTarjeta."'></form></td>\n";
			if ($_REQUEST["domiciliacion"]=="paypal") $TMP_content.="<td><form name=PP action='$PHP_SELF' target=_blank>".$TMP_hidden."<input type=hidden name=op value='pagarpaypal'><input type='button' class='boton' onclick='javascript:document.forms.PP.submit();' value='"._DEF_NLSCestaPagarPaypal."'></form><td>\n";
			$TMP_content.="<td><form name=B action='$PHP_SELF'>".$TMP_hidden."<input type=hidden name=op value='borrar'><input type='button' onclick='javascript:if(confirm(\""._DEF_NLSCestaPregBorr."\"))document.forms.B.submit();' class='boton' value='"._DEF_NLSCestaBorrarPedido."'></form></td></tr></table></div>\n";
			$TMP_content.="<div align=right><img src='themes/".$TMP_dirtheme."/visa.gif'> <img src='themes/".$TMP_dirtheme."/mastercard.gif'>&nbsp;</div>";
		} else {
			$TMP_content.="<center><b>"._DEF_NLSCestaMsg1."</b></center>";
		}
		//$TMP_content.="<script>\nwindow.print();\n</script>\n";
	}
	if ($op=="pagar"||$op=="pagarpaypal") {
                $TMP_res2=sql_query("SELECT * FROM GIE_ventas WHERE idventa='".$TMP_idventa."'", $RAD_dbi);
                $TMP_row2=sql_fetch_array($TMP_res2, $RAD_dbi);
                $TMP_resusu=sql_query("SELECT * FROM usuarios WHERE usuario='".$TMP_row2[refcliente]."'", $RAD_dbi);
                $TMP_rowusu=sql_fetch_array($TMP_resusu, $RAD_dbi);
		if ($op!="error") {
                	$TMP_resusu=sql_query("SELECT * FROM usuarios WHERE usuario='".$TMP_row[refcliente]."'", $RAD_dbi);
                	$TMP_rowusu=sql_fetch_array($TMP_resusu, $RAD_dbi);

			$TMP_content="";
			$TMP_content.="<br /><center><blink><b>"._DEF_NLSCestaMsg2."</b></blink></center><br />";
			$TMP_content.=modCesta($TMP_idventa,"show");
			$TMP_content.=formasEnvioCesta($TMP_idventa,"show");
			$TMP_content.=formDatos("show");
			if ($op=="pagar") $TMP_content.=formPagoTPV($TMP_idventa);
			else if ($op=="pagarpaypal") $TMP_content.=formPagoPaypal($TMP_idventa);
		}
	}

	include_once ("header.php");
	echo "<h1>"._DEF_NLSCesta."</h1>";
	OpenTable();
	if ($op=="showmod" || $op=="get" || $op=="datos" || $op=="pago") echo "".menu($op,$TMP_idventa)."";
	echo $TMP_content.$TMP_texto_error;
	if ($TMP_idventa=="") echo "<br /><br /><center><b>"._DEF_NLSCesta_Nohay."</b></center>";
	CloseTable();
	include_once("modules/".$V_dir."/lib.ajax.php");
	include_once ("footer.php"); 

//----------------------------------------------------------------------------------------
function listaPedidos() {
        global $SESSION_SID, $PHP_SELF, $V_dir, $RAD_dbi, $PHPSESSID, $V_mod, $TMP_IVA, $headeroff, $menuoff, $footeroff, $idventa, $idses;

	if (!is_user()) return "";
	$TMP="<h1>"._DEF_NLSCestaMisPedidos."</h1><ul><table class=browse><tr><th class=browse>"._DEF_NLSCestaIdPedido."</th><th class=browse>"._DATE."</th><th class=browse>"._DEF_NLSCestaTotal."</th><th class=browse>"._DEF_NLSCestaEstado."</th></tr>";
	$TMP_user=base64_decode(getSessionVar("SESSION_user"));
	$cmdSQL = "SELECT * FROM GIE_ventas WHERE procesado!='0' AND refcliente='".base64_decode(getSessionVar("SESSION_user"))."'";
	$cmdSQL = "SELECT * FROM GIE_ventas WHERE refcliente='".base64_decode(getSessionVar("SESSION_user"))."' ORDER BY idventa DESC";
	$TMP_result = sql_query($cmdSQL, $RAD_dbi);
	while($TMP_row=sql_fetch_array($TMP_result, $RAD_dbi)) {
		if ($TMP_class == "class=row1") $TMP_class = "class=row2";
		else $TMP_class = "class=row1";
		if ($TMP_row[procesado]=="0") {
			$TMP_link="<a href='index.php?V_dir=$V_dir&V_mod=$V_mod&idventa=".$TMP_row[idventa]."&op=showmod&idses=".$TMP_row[sesioncesta]."'>";
			$TMP_row[procesado]=_DEF_NLSCestaSinFormalizar;
		} else if ($TMP_row[cobrado]=="1") {
			$TMP_link="<a target=_blank href='index.php?V_dir=$V_dir&V_mod=$V_mod&idventa=".$TMP_row[idventa]."&op=print&idses=".$TMP_row[sesioncesta]."'>";
			$TMP_row[procesado]=_DEF_NLSCestaPagado;
		} else {
			$TMP_link="<a href='index.php?V_dir=$V_dir&V_mod=$V_mod&idventa=".$TMP_row[idventa]."&op=pago&idses=".$TMP_row[sesioncesta]."'>";
			$TMP_row[procesado]=_DEF_NLSCestaNoPagado;
		}
		if ($TMP_row[sesioncesta]==$PHPSESSID) {
			$TMP_link="<a href='index.php?V_dir=$V_dir&V_mod=$V_mod'>";
			$TMP_row[procesado]=_DEF_NLSCestaCarritoActual;
		}
		$TMP.="<tr><td ".$TMP_class.">".$TMP_link.$TMP_row[idventa]."</a></td><td ".$TMP_class.">".$TMP_link.RAD_showDateTime($TMP_row[fechaventa])."</a></td>";
		$TMP.="<td ".$TMP_class."><b>".$TMP_link.RAD_numero($TMP_row[total],2)." &euro;</a></b></td>";
		$TMP.="<td ".$TMP_class."><b>".$TMP_link.$TMP_row[procesado]."</a></b></td></tr>";
		if ($idventa>0 && $idventa==$TMP_row[idventa]) {
			$TMP_result2 = sql_query("SELECT * FROM GIE_ventas WHERE idventa='$idventa' AND sesioncesta='$idses'", $RAD_dbi);
			$TMP_row2 = sql_fetch_array($TMP_result2, $RAD_dbi);
			if ($TMP_idventa!=$TMP_row2[idventa]) $op="show"; // si no es la venta de la sesion fuerza consulta
			//$op="show";
			$TMP_idventa=$TMP_row2[idventa];
			//$TMP.="<tr><td ".$TMP_class.">&nbsp;</td><td ".$TMP_class." colspan=3>";
			//$TMP.=showCesta($TMP_idventa,"show");
			$TMP.="<tr><td ".$TMP_class." colspan=4>";
			$TMP.=modCesta($TMP_idventa,"show");
			$TMP.="</td></tr>";
		}
	}
	$TMP.="</table></ul>";
	return $TMP;
}
//----------------------------------------------------------------------------------------
function menu($op,$TMP_idventa) {
	global $RAD_dbi, $V_dir, $V_mod;

	$TMP_result2 = sql_query("SELECT * FROM GIE_ventas WHERE idventa='$TMP_idventa'", $RAD_dbi);
	$TMP_row2 = sql_fetch_array($TMP_result2, $RAD_dbi);
	$TMP_formaenvio=$TMP_row2[formaenvio];
	$TMP_result3 = sql_query("SELECT * FROM GIE_ventasdetalle WHERE idventa='$TMP_idventa'", $RAD_dbi);
	$TMP_row3 = sql_fetch_array($TMP_result3, $RAD_dbi);
	$TMP_iddetalle=$TMP_row3[idventadetalle];

	$TMP_URL="index.php?V_dir=$V_dir&V_mod=$V_mod&op=";
	$TMP='<div align=left><table border=0 cellspacing=0><tr>';
	if ($op=="show"||$op=="showmod") $TMP.='<td><h2>1 Cesta</h2></td>';
	else $TMP.='<td><a href="'.$TMP_URL.'showmod"><h3>1 Cesta</h3></a></td>';
	$TMP.='<td><h3>&nbsp; &gt; &nbsp;</h3></td>';

	if ($TMP_iddetalle>0) {
		if ($op=="datos") $TMP.='<td><h2>2 Datos</h2></a></td>';
		else $TMP.='<td><a href="'.$TMP_URL.'datos"><h3>2 Datos</h3></a></td>';
	} else {
		$TMP.='<td><h3>2 Datos</h3></td>';
	}
	$TMP.='<td><h3>&nbsp; &gt; &nbsp;</h3></td>';

	if ($TMP_iddetalle>0) {
		if ($op=="pago") $TMP.='<td><h2>3 Pago</h2></td>';
		else $TMP.='<td><a href="'.$TMP_URL.'pago"><h3>3 Pago</h3></a></td>';
	} else {
		$TMP.='<td><h3>3 Pago</h3></td>';
	}
	$TMP.='</tr></table></div>';
	return $TMP;
}
//----------------------------------------------------------------------------------------
function modCesta($TMP_idventa, $op) {
        global $SESSION_SID, $idout, $dbname, $PHP_SELF, $V_dir, $V_mod, $RAD_dbi, $PHPSESSID, $headeroff, $menuoff, $footeroff;

	$TMP_lang=getSessionVar("SESSION_lang");
	$TMP_dirtheme=getSessionVar("SESSION_theme");
	
	if ($dbname=="") $dbname=_DEF_dbname;
	if ($TMP_idventa=="") return "";

	$TMP_content="";
	if ($op=="showmod") {
		$TMP_content.="<form name=CESTA action='$PHP_SELF' method=post><input type=hidden name=V_dir value='$V_dir'><input type=hidden name=V_mod value='$V_mod'>";
		$TMP_content.="<input type=hidden name=op value='update'><input type=hidden name=PHPSESSID value='$PHPSESSID'>";
		$TMP_content.="<input type=hidden name=headeroff value='$headeroff'><input type=hidden name=menuoff value='$menuoff'><input type=hidden name=footeroff value='$footeroff'>";
	}
	if ($op=="showmod") $TMP_eliminar=_DEF_NLSCestaEliminar;	
	else $TMP_eliminar="";	
	
	//Paso 2 realizar pedido -> mostrar texto correspondiente
	if($op=="realizar_pedido") {
		$TMP_res=sql_query("select count(*) from GIE_formasenvio", $RAD_dbi);
		$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
		if ($TMP_row[0]>0) $TMP_content="<div>"._DEF_NLSCestaMsg3."</div>";
	}
	
	//Paso  4 Pago-> mostrar texto correspondiente
	if($op=="pago") {
		$id=$idout; // cabecera con informacion para pago
		$TMP_res=sql_query("SELECT * FROM articulos where id='$id'", $RAD_dbi);
		$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
		$TMP_row[contenido]=str_replace("<!-- securimage -->",$TMP_secu,$TMP_row[contenido]);
		$TMP_row[contenido]=str_replace("<!-- nombre -->",$_REQUEST[nombre],$TMP_row[contenido]);
		$TMP_content=$TMP_row[contenido];
	}
	
	$TMP_content.='
<table class=browse>
<tr>
<th class=browse>'.$TMP_eliminar.'</th>
<th class=browse>'._DEF_NLSCestaFoto.'</th>
<th class=browse>'._DEF_NLSCestaArt.'</th>
<th class=browse>'._DEF_NLSCestaCantidad.'</th>
<th class=browse nowrap>'._DEF_NLSCestaSubtotal.'</th>
</tr>';
	$TMP_result2 = sql_query("SELECT * FROM GIE_ventasdetalle WHERE idventa='".$TMP_idventa."'", $RAD_dbi);
	$TMP_cont=0;
	$TMP_total=0;
	while($TMP_row2 = sql_fetch_array($TMP_result2, $RAD_dbi)) {
		$TMP_result3 = sql_query("SELECT * FROM GIE_articulos WHERE idarticulo='".$TMP_row2[idarticulo]."'", $RAD_dbi);
		$TMP_row3 = sql_fetch_array($TMP_result3, $RAD_dbi);
		foreach($TMP_row3 as $TMP_k=>$TMP_v) if ($TMP_row3[$TMP_k."_".$TMP_lang]!="") $TMP_row3[$TMP_k]=$TMP_row3[$TMP_k."_".$TMP_lang];
		$TMP_foto=$TMP_row3["icono"];
		$TMP_unidad=$TMP_row3["unidad"];
		$TMP_cantidadminima=$TMP_row3["cantidadminima"];
		//$TMP_foto=RAD_lookup("GIE_articulos","foto","idarticulo",$TMP_row2["idarticulo"]);
		$ICONO=RAD_primerFich($TMP_foto);
		if (trim($ICONO)!="" && file_exists("files/".$dbname."/".$ICONO)) $TMP_foto=RAD_resizeImg("files/".$dbname."/".$ICONO,80,80);
		else $TMP_foto="images/tr.gif";
		if ($TMP_class=="class=row1") $TMP_class="class=row2";
		else $TMP_class="class=row1";
		$TMP_articulo=RAD_lookup("GIE_articulos","articulo","idarticulo",$TMP_row2["idarticulo"]);
		if (strlen($TMP_articulo)>20) $TMP_articulobreve=substr($TMP_articulo,0,17)."...";
		else $TMP_articulobreve=$TMP_articulo;
		if ($op=="showmod") $TMP_linkDel="<a href='index.php?V_dir=".$V_dir."&V_mod=".$V_mod."&op=update&del".$TMP_cont."=x&id".$TMP_cont."=".$TMP_row2["idventadetalle"]."&totid=".($TMP_cont+1)."'><img src='/themes/".$TMP_dirtheme."/ico_elimina.gif' width=18 height=18 border=0></a>";
		else $TMP_linkDel="";
		$TMP_linkArt="index.php?V_dir=".$V_dir."&V_mod=showarticulo&id=".$TMP_row2["idarticulo"];				
		if ($TMP_row3[url_seo]=="") $TMP_row3[url_seo]=$TMP_row3[articulo];			
		$TMP_content.='<tr>
			<td '.$TMP_class.' style="text-align:center; vertical-align:middle;">'.$TMP_linkDel.'</td>
			<td '.$TMP_class.'><a target=_blank href="'.linkFamArt($TMP_row3[idfamilia],$TMP_row3[idarticulo],$TMP_row3[url_seo]).'"><img src="'.$TMP_foto.'" alt="" border="0"></a></td>
			<td '.$TMP_class.'><a target=_blank href="'.linkFamArt($TMP_row3[idfamilia],$TMP_row3[idarticulo],$TMP_row3[url_seo]).'" class="producto">'.$TMP_articulo.'</a></td>
			<td '.$TMP_class.' style="text-align:center;">';
		if ($op=="showmod") {
			$TMP_content.='<input type="text" size="6" maxlength="8" value="'.$TMP_row2[unidades].'" name="uds'.$TMP_cont.'" onchange="javascript:document.forms.CESTA.submit();">';
			if ($TMP_unidad!="") $TMP_content.=' '.$TMP_unidad;
			if ($TMP_cantidadminima>0) $TMP_content.="<br />m&iacute;nimo: ".$TMP_cantidadminima;
		} else {
			$TMP_content.=''.$TMP_row2[unidades].'';
			if ($TMP_unidad!="") $TMP_content.=' '.$TMP_unidad;
		}
		if ($TMP_row2[descuentoporc]>0) $TMP_descuento=round($TMP_row2[descuentoporc],2)."%";
		else if ($TMP_row2[descuento]>0) $TMP_descuento=RAD_numero($TMP_row2[descuento],2);
		else $TMP_descuento="0";
		$TMP_content.=' &nbsp;</td>
<td '.$TMP_class.' style="text-align:right;">'.RAD_numero($TMP_row2[total],2).' &euro; &nbsp;</td>
</tr>
';
		$TMP_content.="<input type=hidden name='id".$TMP_cont."' value='".$TMP_row2[idventadetalle]."'><input type=hidden name='olduds".$TMP_cont."' value='".$TMP_row2[unidades]."'>";
		$TMP_cont++;
		$TMP_total+=$TMP_row2[total];
	}
	if ($TMP_cont>0) $TMP_content.='
<tr>
<td width="50" class=cesta bgcolor="#CDCDCD">&nbsp;</th>
<td width="124" class=cesta bgcolor="#CDCDCD">&nbsp;</th>
<td width="110" class=cesta bgcolor="#CDCDCD">&nbsp;</th>
<th width="80" class=browse>'._DEF_NLSCestaTotal.':</th>
<th width="110" class=browse style="text-align:right;" nowrap>'.RAD_numero($TMP_total,2).' &euro; &nbsp;</th>
</tr>
<input type=hidden name="totid" value="'.$TMP_cont.'">
';
	$TMP_content.="</table>";

	if ($op=="showmod") {
		$TMP_content.='<table align=right><tr>
<td>
<input name="save" type="button" onclick="javascript:document.location.href=\'index.php?V_dir='.$V_dir.'&V_mod=tienda\';" class="boton" id="button" value="'._DEF_NLSCestaSEGUIRCOMPRANDO.'"></td>
<td>
<input name="save" type="button" onclick="javascript:document.location.href=\'index.php?V_dir='.$V_dir.'&V_mod='.$V_mod.'&op=datos\';" class="boton" id="button" value="'._DEF_NLSCestaREALIZARPEDIDO.'"></td>
</tr></table>
</form>
';
	}

	if ($TMP_cont==0) $TMP_content="<br /><br /><center><b>"._DEF_NLSCesta_Nohay."</b></center>";

	return $TMP_content;
}
//----------------------------------------------------------------------------------------
function formasEnvioCesta($TMP_idventa, $op) {
        global $SESSION_SID, $PHP_SELF, $V_dir, $V_mod, $RAD_dbi, $PHPSESSID, $TMP_IVA, $headeroff, $menuoff, $footeroff;
	$TMP_result2 = sql_query("SELECT * FROM GIE_ventas WHERE idventa='".$TMP_idventa."'", $RAD_dbi);
	$TMP_row2 = sql_fetch_array($TMP_result2, $RAD_dbi);
	$TMP_total=$TMP_row2[total];

	$TMP_formasenvio=""; $TMP_selformasenvio=""; $TMP_hidden="";
	$TMP_res3=sql_query("SELECT * FROM GIE_formasenvio", $RAD_dbi);
	while($TMP_row3=sql_fetch_array($TMP_res3,$RAD_dbi)) {
		if ($TMP_row2[total]>$TMP_row3[aplicablehasta] && $TMP_row3[aplicablehasta]>0) continue;
		if ($TMP_row2[total]<$TMP_row3[aplicabledesde] && $TMP_row3[aplicabledesde]>0) continue;
		if ($TMP_row3[id]==$TMP_row2[formaenvio]) $TMP_checked="checked";
		else $TMP_checked="";
		if ($op=="showmod") {
			if ($TMP_row3[precio]>0) $texto_precio_envio=$TMP_row3[precio]." &euro;";
			else $texto_precio_envio=_DEF_NLSCestaEnvioGratuito;
			$TMP_formasenvio.="<input $TMP_checked type=radio name=V0_formaenvio onchange='javascript:document.forms.F.submit();' value='".$TMP_row3[id]."'><span class=\"text_cesta_negrita\"> ".$TMP_row3[detalle].".</span><span class=\"text_cesta_azul\"> ".$texto_precio_envio."</span><br />";
		} else {
			if ($TMP_checked=="checked") {
				$TMP_formasenvio=$TMP_row3[detalle];
				$TMP_precioformaenvio="<span class='precio'>".RAD_numero($TMP_row3[precio],2)." &euro;</span>";
			}
		}
		if ($TMP_row2[formaenvio]==$TMP_row3[id]) $TMP_total+=$TMP_row3[precio];
	}
	if ($op=="showmod") {
		$TMP_selformasenvio="Seleccione Forma de Env&iacute;o:";
		$TMP_hidden="\n\n<form action='".$PHP_SELF."' method=POST name=F>\n";
		foreach($_REQUEST as $TMP_k=>$TMP_v) if (substr($TMP_k,0,2)!="V0") $TMP_hidden.="<input type=hidden name=$TMP_k value=$TMP_v>\n";
	} else {
		$TMP_selformasenvio=_DEF_NLSCestaFormaEnvio.":";
	}
	$TMP_content=$TMP_hidden;
	if ($TMP_formasenvio!="") $TMP_content.='
<table class="cesta" width="520">
<tr>
<td width="2" class="cesta"></td>
<td colspan=2 class="cesta" width="156" align="center"><span class="text_cesta_rojo">'.$TMP_selformasenvio.'</span></td>
<td colspan=2 class="cesta" width="300" align="left">'.$TMP_formasenvio.'</td>
<td class="cesta" width="30" align="right">'.$TMP_precioformaenvio.'<br /></td>
<td width="12" class="cesta"></td>
</tr>
<tr>
<td width="2" class="cesta"> </td>
<td width="50" class="cesta" bgcolor="#CDCDCD">&nbsp;</td>
<td width="124" class="cesta" bgcolor="#CDCDCD">&nbsp;</td>
<td width="110" class="cesta" bgcolor="#CDCDCD">&nbsp;</td>
<td width="80" class="cesta" nowrap bgcolor="#CDCDCD"><span class="text"><b>'._DEF_NLSCestaNeto.':</b></span></td>
<td width="50" class="cesta" bgcolor="#CDCDCD" align="right"><span class="precio">'.RAD_numero($TMP_total,2).' &euro;</span></td>
<td width="12" class="cesta"> </td>
</tr>
</table>
';
	if ($op=="showmod") {
		$TMP_content.='</form>';
	}
	$TMP_content.='<center>';

	return $TMP_content;
}
//----------------------------------------------------------------------------------------
function showCesta($TMP_idventa, $op) {
        global $SESSION_SID, $PHP_SELF, $V_dir, $V_mod, $RAD_dbi, $PHPSESSID, $TMP_IVA, $headeroff, $menuoff, $footeroff;

	if ($TMP_idventa=="") return "";

	if ($op=="showmod") {
		$TMP_content="<form action='$PHP_SELF' method=post><input type=hidden name=V_dir value='MSC'><input type=hidden name=V_mod value='cesta'>";
		$TMP_content.="<input type=hidden name=op value='update'><input type=hidden name=PHPSESSID value='$PHPSESSID'>";
		$TMP_content.="<input type=hidden name=headeroff value='$headeroff'><input type=hidden name=menuoff value='$menuoff'><input type=hidden name=footeroff value='$footeroff'>";
		$TMP_content.="<TABLE class=browse><tr><th class=browse>"._DEF_NLSCestaEliminar."</th><th class=browse>"._DEF_NLSCestaCantidad."</th><th class=browse>"._DEF_NLSCestaArt."</th><th nowrap class=browse>"._DEF_NLSCestaPrecioUd."</th><th class=browse>"._DEF_NLSCestaTotal."</th></tr>\n";
	} else if ($op=="show") {
		$TMP_content.="<TABLE class=browse><tr><th class=browse>"._DEF_NLSCestaCantidad."</th><th class=browse>"._DEF_NLSCestaArt."</th><th nowrap class=browse>"._DEF_NLSCestaPrecioUd."</th><th class=browse>"._DEF_NLSCestaTotal."</th></tr>\n";
	} else {
		$TMP_content="<TABLE class=browse><tr><th class=browse align=right><a href='modules.php?".$SESSION_SID."&V_dir=MSC&V_mod=cesta'><small>".$TMP_row[fechaventa]."<small></th></tr>";
	}

	$TMP_result2 = sql_query("SELECT * FROM GIE_ventasdetalle WHERE idventa='".$TMP_idventa."'", $RAD_dbi);
	$TMP_cont=0;
	$TMP_total=0;
	while($TMP_row2 = sql_fetch_array($TMP_result2, $RAD_dbi)) {
                $n = $TMP_cont % 2 + 1;
                if ($n==1) $RAD_classrow = "class=row1";
                else $RAD_classrow = "class=row2";
		$TMP_articulo=RAD_lookup("GIE_articulos","articulo","idarticulo",$TMP_row2["idarticulo"]);
		if (strlen($TMP_articulo)>20) $TMP_articulobreve=substr($TMP_articulo,0,17)."...";
		else $TMP_articulobreve=$TMP_articulo;
		if ($op=="showmod") $TMP_content.="<tr><td $RAD_classrow align=center><input type=checkbox name='del".$TMP_cont."' value='on'></td><td $RAD_classrow>&nbsp;<input type=hidden name='id".$TMP_cont."' value='".$TMP_row2[idventadetalle]."'><input size=6 type=text name='uds".$TMP_cont."' value='".$TMP_row2[unidades]."'><input type=hidden name='olduds".$TMP_cont."' value='".$TMP_row2[unidades]."'> </td><td $RAD_classrow>".$TMP_articulo."</td><td align=right $RAD_classrow>".$TMP_row2[precio]."</td><td align=right $RAD_classrow>".RAD_numero($TMP_row2[total])."</td></tr>";
		else if ($op=="show") $TMP_content.="<tr><td $RAD_classrow>&nbsp;".$TMP_row2[unidades]." </td><td $RAD_classrow>".$TMP_articulo."</td><td align=right $RAD_classrow>".$TMP_row2[precio]."</td><td align=right $RAD_classrow>".RAD_numero($TMP_row2[total])."</td></tr>";
		else $TMP_content.="<tr><td $RAD_classrow> <a href='modules.php?".$SESSION_SID."V_dir=MSC&V_mod=cesta'>- ".$TMP_articulobreve."</a></td></tr>";
		$TMP_cont++;
		$TMP_total+=$TMP_row2[total];
		if ($TMP_IVA!=0 && $TMP_row2[total]!=0) $TMP_totalIVA=floor($TMP_row2[total]*$TMP_IVA+0.5)/100;
		else $TMP_totalIVA=0;
		$TMP_sumaIVA+=$TMP_totalIVA;
		$TMP_tconIVA=$TMP_tconIVA+$TMP_totalIVA+$TMP_row2[total];
	}
	if ($op=="showmod") {
		if ($TMP_cont>0) $TMP_content.="<tr><th class=browse colspan=3><input type=hidden name='totid' value='$TMP_cont'><input type=submit value='"._DEF_NLSSave."'></th><th class=browse align=right>"._DEF_NLSCestaTotal." : </th><th class=browse align=right>".RAD_numero($TMP_total)." </th></tr></form></p>";
		else $TMP_content.="<tr><th class=browse colspan=3></th><th class=browse align=right>"._DEF_NLSCestaTotal." : </th><th class=browse> align=right>".RAD_numero($TMP_total)." </th></tr>";
		//$TMP_content.="<tr><th class=browse colspan=3></th><th class=browse align=right>IVA ($TMP_IVA%): </th><th class=browse>".RAD_numero($TMP_sumaIVA,2)." </th></tr>";
		//$TMP_content.="<tr><th class=browse colspan=3></th><th class=browse align=right>Total con IVA: </th><th class=browse>".RAD_numero($TMP_tconIVA,2)." &euro;</th></tr>";
	} else if ($op=="show") {
		if ($TMP_cont>0) $TMP_content.="<tr><th class=browse colspan=2></th><th class=browse align=right>"._DEF_NLSCestaTotal." : </th><th class=browse align=right>".RAD_numero($TMP_total,2)." </th></tr></form></p>";
		else $TMP_content.="<tr><th class=browse colspan=2><th class=browse align=right>Total : </th>".RAD_numero($TMP_total,2)." </th></tr>";
		//$TMP_content.="<tr><th class=browse colspan=2></th><th class=browse align=right>IVA ($TMP_IVA%): </th><th class=browse>".RAD_numero($TMP_sumaIVA,2)." </th></tr>";
		//$TMP_content.="<tr><th class=browse colspan=2></th><th class=browse align=right>Total con IVA: </th><th class=browse>".RAD_numero($TMP_tconIVA,2)." &euro;</th></tr>";
	}
	$TMP_content.="</TABLE>";
	//if ($op=="showmod") $TMP_content.="<br /><a href='modules.php?".$SESSION_SID."V_dir=MSC&V_mod=cesta&op=get'>Hacer Pedido</a>";
	if ($op=="showmod") $TMP_content.="<br /><a href='modules.php?".$SESSION_SID."V_dir=MSC&V_mod=cesta&op=datos'>"._DEF_NLSCestaHacerPedido."</a>";
	if ($op=="show" || $op=="showmod") $TMP_content.="";

	if ($TMP_cont==0) $TMP_content="<br /><br /><center><b>"._DEF_NLSCesta_Nohay."</b></center>";


	return $TMP_content;
}
//----------------------------------------------------------------------------------------
function addCesta($TMP_idventa, $id, $unidades) {
global $SESSION_SID, $PHP_SELF, $V_dir, $RAD_dbi, $headeroff, $footeroff, $menuoff, $PHPSESSID, $TMP_IVA; 

	$TMP_observaciones=getenv("REMOTE_ADDR")."\n".getenv("HTTP_USER_AGENT")."\n".getenv("HTTP_COOKIE")."\n";
	$TMP_observaciones=str_replace("'"," ",$TMP_observaciones);

	if ($TMP_idventa=="") {
// El descuento solo se pone en cada linea. No en la cabecera
		$cmdSQL2 = "INSERT INTO GIE_ventas SET procesado='0', sesioncesta='$PHPSESSID', ventatipo='VIN', concepto='Venta Internet', impuestos='$TMP_IVA', fechaventa='".date("Y-m-d G:i:s")."', refcliente='".base64_decode(getSessionVar("SESSION_user"))."', observaciones='$TMP_observaciones'";
		$TMP_result2 = sql_query($cmdSQL2, $RAD_dbi);
		$TMP_idventa=sql_insert_id($RAD_dbi);
	}
	if ($TMP_idventa=="") {
		alert("Error al crear la cesta");
		return "";
	}
	/////$codigodescuento=RAD_lookup("GIE_ventas","codigodescuento","idventa",$TMP_idventa);
	$TMP_res=sql_query("SELECT * FROM GIE_ventasdetalle WHERE idventa='$TMP_idventa' AND idarticulo='$id'", $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
	
	if ($TMP_row[idventadetalle]>0) {
		sql_query("UPDATE GIE_ventasdetalle SET unidades=unidades+".$unidades." WHERE idventadetalle='".$TMP_row[idventadetalle]."'", $RAD_dbi);
		recalculaTotalOperacion("venta",$TMP_idventa);
		return "";
	}

	$TMP_res=sql_query("SELECT * FROM GIE_articulos WHERE idarticulo='$id'", $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
	$TMP_articulo=$TMP_row[articulo];
	$TMP_cantidadminima=$TMP_row["cantidadminima"];
	//$TMP_cantidadminima=RAD_lookup("GIE_articulos","cantidadminima","idarticulo",$id);
	if ($TMP_cantidadminima>0 && $TMP_cantidadminima>$unidades) $unidades=$TMP_cantidadminima;	

	/*$TMP_precio=$TMP_row[preciooferta];
	if (!$TMP_precio>0) $TMP_precio=$TMP_row[totalventa];*/
	if ($TMP_row[preciooferta]>0) $TMP_precio=$TMP_row[preciooferta];
	else $TMP_precio=$TMP_row[totalventa];	

// Recoge descuento general/articulo/familia/usuario/codigodescuento. Si se le aplica, se aplica el mayor
	$TMP_descuentoporc=consultaDescuento($id,$codigodescuento); // Consulta el descuento del Articulo/Familia/Usuario
	if ($TMP_descuentoporc!=0) $TMP_total=round(($TMP_precio*(100-$TMP_descuentoporc)/100)*$unidades,2);
	else $TMP_total=$TMP_precio*$unidades;
/* por el momento no hay informacion de IVA, ni base imponible
	$TMP_IVA=$TMP_row[iva];
	if ($TMP_IVA!=0 && $TMP_row[precioventa]!=0) $TMP_impuestos=floor($TMP_IVA*$TMP_total+0.5)/100;
	else $TMP_impuestos=0;
	//$TMP_total=$TMP_precio+$TMP_impuestos;
*/
	$cmdSQL2 = "INSERT INTO GIE_ventasdetalle SET idventa='$TMP_idventa', idarticulo='$id', unidades='".$unidades."', baseimponible='$TMP_precio', precio='$TMP_precio', descuentoporc='$TMP_descuentoporc', impuestos='$TMP_impuestos', total='$TMP_total'";
	$TMP_result2 = sql_query($cmdSQL2, $RAD_dbi);
	$TMP_idventadetalle=sql_insert_id($RAD_dbi);

	recalculaTotalOperacion("venta",$TMP_idventa);

	//if ($TMP_idventadetalle>0) alert("Agregado $TMP_articulo a la cesta");
	if (!$TMP_idventadetalle>0) alert(_DEF_NLSCestaErrAdd.$TMP_articulo);
	return "";
}
//----------------------------------------------------------------------------------------
function formDatos($op) {
global $SESSION_SID, $PHP_SELF, $V_dir, $V_mod, $RAD_dbi, $PHPSESSID, $TMP_idventa;
// Pide/muestra datos de cliente y pedido

	$TMP_user=base64_decode(getSessionVar("SESSION_user"));
	$TMP_res=sql_query("SELECT * FROM usuarios WHERE usuario='".$TMP_user."'", $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
	$TMP_res2=sql_query("SELECT * FROM GIE_ventas WHERE idventa='".$TMP_idventa."'", $RAD_dbi);
	$TMP_row2=sql_fetch_array($TMP_res2, $RAD_dbi);
	if ($TMP_row2[nombrecliente]!="") $TMP_row[nombre]=$TMP_row2[nombrecliente];
	if ($TMP_row2[direccion]!="") $TMP_row[domicilio]=$TMP_row2[direccion];
	if ($TMP_row2[email]!="") $TMP_row[email]=$TMP_row2[email];
	if ($TMP_row2[telefono]!="") $TMP_row[telefono]=$TMP_row2[telefono];
	if ($TMP_row2[localidad]!="") $TMP_row[localidad]=$TMP_row2[localidad];
	if ($TMP_row2[codpostal]!="") $TMP_row[codpostal]=$TMP_row2[codpostal];

	$TMP_content.="<script LANGUAGE='JAVASCRIPT'>\nvar varField=new Array();\nvar nameField=new Array();\nvar lastField=0;\n";
	$TMP_content.="function RAD_checkFields2() {\n  var df2=document.F2;\n  p='';\n  for(i=0; i<varField.length; i++) {\n	eval('p=df2.'+varField[i]+'.value');\n";
	$TMP_content.="	if (p=='') { \n		alert('El campo '+nameField[i]+' es obligatorio.');\n		return;\n	}\n  }\n  df2.submit();\n  return;\n}\n</script>\n";
	
	if ($op!="show") {
		$TMP_content.="<form name=F2 action='$PHP_SELF' method=post><input type=hidden name=V_dir value='$V_dir'><input type=hidden name=V_mod value='$V_mod'>";
		$TMP_content.="<input type=hidden name=op value='savedatos'><input type=hidden name=PHPSESSID value='$PHPSESSID'>";
	}
	$TMP_content.="\n<br><center><h1>"._DEF_NLSCestaDatosEnvio."</h1><center><TABLE border=0 width=60%>";
	if ($op=="show") $TMP_content.="<tr><td class=detailtit>"._DEF_NLSCestaNumPedidoWeb.":</td><td class=detail><b>".$TMP_idventa."</b></td></tr>";
	$TMP_content.="<tr><td class=detailtit>"._DEF_NLSCestaNombre.":</td><td class=detail>";
	if ($op=="show") $TMP_content.=$TMP_row[nombre];
	else $TMP_content.="<input type=text name=V0_nombre size=40 value='".$TMP_row[nombre]."'>";
	$TMP_content.="</td></tr><tr><td class=detailtit>"._DEF_NLSCestaPais.":</td><td class=detail>";
	if ($op=="show") {
		if ($TMP_row2[pais]!="") $TMP_content.=$TMP_row2[pais];
		else $TMP_content.=RAD_lookup("paises","pais","codpais",$TMP_row2[codpais]);
	} else {
		$TMP_content.="\n<select name=V0_codpais>";
		$TMP_resp=sql_query("SELECT * FROM paises ORDER BY pais", $RAD_dbi);
		while($TMP_rowp=sql_fetch_array($TMP_resp, $RAD_dbi)) {
			if ($TMP_rowp[codpais]==$TMP_row2[codpais]) $TMP_selected="SELECTED";
			else if ($TMP_rowp[pais]==$TMP_row2[pais]) $TMP_selected="SELECTED";
			else $TMP_selected="";
			$TMP_content.="<option value='".$TMP_rowp[codpais]."' $TMP_selected>".$TMP_rowp[pais]."</option>";
		}
		$TMP_content.="</select>\n";
	}
	$TMP_content.="</td></tr><tr><td class=detailtit>"._DEF_NLSCestaProvincia.":</td><td class=detail>";
	if ($op=="show") {
		if ($TMP_row2[provincia]!="") $TMP_content.=$TMP_row2[provincia];
		else $TMP_content.=RAD_lookup("provincias","provincia","codprovincia",$TMP_row2[codprovincia]);
	} else {
		$TMP_content.="\n<select name=V0_codprovincia>";
		$TMP_resp=sql_query("SELECT * FROM provincias ORDER BY provincia", $RAD_dbi);
		while($TMP_rowp=sql_fetch_array($TMP_resp, $RAD_dbi)) {
			if ($TMP_rowp[codprovincia]==$TMP_row2[codprovincia]) $TMP_selected="SELECTED";
			else if ($TMP_rowp[provincia]==$TMP_row2[provincia]) $TMP_selected="SELECTED";
			else $TMP_selected="";
			$TMP_content.="<option value='".$TMP_rowp[codprovincia]."' $TMP_selected>".$TMP_rowp[provincia]."</option>";
		}
		$TMP_content.="</select>\n";
	}
	$TMP_content.="</td></tr><tr><td class=detailtit>"._DEF_NLSCestaLocalidad.":</td><td class=detail>";
	if ($op=="show") $TMP_content.=str_replace("\n","<br />",$TMP_row[localidad]);
	else $TMP_content.="<input type=text name=V0_localidad size=30 value=\"".$TMP_row[localidad]."\">";
	$TMP_content.="</td></tr><tr><td class=detailtit>"._DEF_NLSCestaCodPostal.":</td><td class=detail>";
	if ($op=="show") $TMP_content.=str_replace("\n","<br />",$TMP_row[codpostal]);
	else $TMP_content.="<input type=text name=V0_codpostal size=30 value=\"".$TMP_row[codpostal]."\">";
	$TMP_content.="</td></tr><tr><td class=detailtit>"._DEF_NLSCestaDireccion.":</td><td class=detail>";
	if ($op=="show") $TMP_content.=str_replace("\n","<br />",$TMP_row[domicilio]);
	else $TMP_content.="<TEXTAREA name=V0_direccion COLS=40 ROWS=3>".$TMP_row[domicilio]."</TEXTAREA>";
	$TMP_content.="</td></tr><tr><td class=detailtit>"._DEF_NLSCestaTelefono.":</td><td class=detail>";
	if ($op=="show") $TMP_content.=$TMP_row[telefono];
	else $TMP_content.="<input class='input_gris' type=text name=V0_telefono size=20 value='".$TMP_row[telefono]."'>";
	$TMP_content.="</td></tr><tr><td class=detailtit>"._DEF_NLSCestaEmail.":</td><td class=detail>";
	if ($op=="show") $TMP_content.=$TMP_row[email]."</td></tr>";
	else $TMP_content.="<input class='input_gris' type=text name=V0_email size=20 value='".$TMP_row[email]."'></td></tr>";
	if ($TMP_row2[observaciones2]!="") {
		$TMP_content.="<tr><td class=detailtit> Comentarios sobre tu pedido:</td><td class=detail>";
		if ($op=="show") $TMP_content.=str_replace("\n","<br />",$TMP_row2[observaciones2])."</td></tr>";
		else $TMP_content.="<TEXTAREA name=V0_observaciones COLS=40 ROWS=3>".$TMP_row2[observaciones2]."</TEXTAREA><br /><span class=\"text_cesta_observ\">Utilice este espacio para indicarnos el d&iacute;a de entrega, o cualquier otro comentario que desee</span></td></tr>";
	}
	if ($op!="show") {
		$TMP_content.="</table>";
		$TMP_content.="<br />Continuar con el proceso de compra, en el siguiente paso le ofrecemos un resumen de todos los datos y la opci&oacute;n de pago disponible.
<input name='save' type='button' onclick='javascript:RAD_checkFields2();' class='boton' id='button' value='SIGUIENTE PASO'>
</form>";
	} else {
		$TMP_content.="</table>";
	}
	
	$TMP_content.="\n<script>\nvarField[lastField]='V0_nombre';\nnameField[lastField]='Nombre';\nlastField++;\n</script>\n";
	$TMP_content.="\n<script>\nvarField[lastField]='V0_direccion';\nnameField[lastField]='Direccion';\nlastField++;\n</script>\n";
	$TMP_content.="\n<script>\nvarField[lastField]='V0_codpostal';\nnameField[lastField]='Codigo Postal';\nlastField++;\n</script>\n";	
	
	return $TMP_content;
}
//----------------------------------------------------------------------------------------
function recalculaTotalOperacion($tipo,$id) {
global $RAD_dbi, $db, $V_mod;
	if ($id=="") return;
	///$codigodescuento=RAD_lookup("GIE_ventas","codigodescuento","idventa",$id);
	$TMP_result = sql_query("SELECT * FROM GIE_".$tipo."sdetalle WHERE id".$tipo."='".$id."'", $RAD_dbi);
	$totalFac=0;
	while($TMP_row = sql_fetch_array($TMP_result, $RAD_dbi)) {
		$TMP_result2 = sql_query("SELECT * FROM GIE_articulos WHERE idarticulo='".$TMP_row[idarticulo]."'", $RAD_dbi);
		$TMP_row2 = sql_fetch_array($TMP_result2, $RAD_dbi);
		if ($TMP_row2[preciooferta]>0) $TMP_precio=$TMP_row2[preciooferta];
		else $TMP_precio=$TMP_row2[totalventa];	

// Recoge descuento general/articulo/familia/usuario/codigodescuento. Si se le aplica, se aplica el mayor
		$TMP_descuentoporc=consultaDescuento($TMP_row[idarticulo],$codigodescuento); // Consulta el descuento del Articulo/Familia/Usuario
		if ($TMP_descuentoporc!=0) $TMP_total=round(($TMP_precio*(100-$TMP_descuentoporc)/100)*$TMP_row[unidades],2);
		else $TMP_total=$TMP_precio*$TMP_row[unidades];
/* por el momento no hay informacion de IVA, ni base imponible
		$TMP_IVA=$TMP_row[iva];
		if ($TMP_IVA!=0 && $TMP_row[precioventa]!=0) $TMP_impuestos=floor($TMP_IVA*$TMP_total+0.5)/100;
		else $TMP_impuestos=0;
		//$TMP_total=$TMP_precio+$TMP_impuestos;
*/

		if ($TMP_precio!=$TMP_row[precio] || $TMP_total!=$TMP_row[total]) {
			$cmdSQL2 = "UPDATE GIE_".$tipo."sdetalle SET precio='$TMP_precio', descuentoporc='$TMP_descuentoporc', total='$TMP_total' WHERE idventadetalle='".$TMP_row[idventadetalle]."'";
			//$cmdSQL2 = ", baseimponible='$TMP_precio', impuestos='$TMP_impuestos'";
			sql_query($cmdSQL2, $RAD_dbi);
			//alert("Cambia precio de ".$TMP_row[precio]." a $TMP_precio ");
			$TMP_row[precio]=$TMP_precio;
		}
		if ($TMP_row[descuentoporc]!=0) $TMP_total=round(($TMP_row[precio]*(100-$TMP_row[descuentoporc])/100)*$TMP_row[unidades],2);
		else $TMP_total=$TMP_row[precio]*$TMP_row[unidades];
	
		if ($TMP_row[total]!=$TMP_total) {
			$cmdSQL2 = "UPDATE GIE_".$tipo."sdetalle SET total='$TMP_total' WHERE idventadetalle='".$TMP_row[idventadetalle]."'";
			sql_query($cmdSQL2, $RAD_dbi);
			$TMP_row[total]=$TMP_total;
		}
		$totalFac+=$TMP_row[total];
	}
	$TMP_result = sql_query("SELECT * FROM GIE_".$tipo."s WHERE id".$tipo."='$id'", $RAD_dbi);
	$TMP_row1 = sql_fetch_array($TMP_result, $RAD_dbi);
	$TMP_formaenvio=$TMP_row1[formaenvio];
	if ($totalFac!=$TMP_row1[total]) {
		$TMP_upd="UPDATE GIE_".$tipo."s SET total='$totalFac' WHERE id".$tipo."='".$id."'";
		sql_query($TMP_upd,$RAD_dbi);
	}
	// segun el total la forma de envio seleccionada puede ser incorrecta ahora

	if ($TMP_row1[codprovincia]=="" && $TMP_formaenvio>0) {
		///////sql_query("UPDATE GIE_".$tipo."s SET formaenvio='', gastosenvio='' WHERE id".$tipo."='".$id."'", $RAD_dbi);
	}
	$TMP_permitidaformaenvio=false;
	$TMP_res3=sql_query("SELECT * FROM GIE_formasenvio", $RAD_dbi);
	while($TMP_row3=sql_fetch_array($TMP_res3,$RAD_dbi)) {
		if ($totalFac>$TMP_row3[aplicablehasta] && $TMP_row3[aplicablehasta]>0) continue;
		if ($totalFac<$TMP_row3[aplicabledesde] && $TMP_row3[aplicabledesde]>0) continue;
// la primera que es permitida ya la admite.
		if ($TMP_row1[codprovincia]!="" &&$TMP_formaenvio!=$TMP_row3[id] && ereg(",".$TMP_row1[codprovincia].",",",".$TMP_row3[codprovincias].",")) {
			//alert("Provincia de Pedido=".$TMP_row1[codprovincia].".Provincias de Forma de Envio=".$TMP_row3[codprovincias]);
			sql_query("UPDATE GIE_".$tipo."s SET formaenvio='".$TMP_row3[id]."', gastosenvio='".$TMP_row3[precio]."' WHERE id".$tipo."='".$id."'", $RAD_dbi);
		} 
		if ($TMP_row3[id]==$TMP_formaenvio) $TMP_permitidaformaenvio=true;
	}
	if (($TMP_permitidaformaenvio==false)&&($TMP_row1[formaenvio]>0 || $TMP_row1[gastosenvio]>0)) sql_query("UPDATE GIE_".$tipo."s SET formaenvio='', gastosenvio='' WHERE id".$tipo."='".$id."'", $RAD_dbi);
}
//////////////////////////////////////////////////////////////////////////////////
function consultaDescuento($idarticulo,$codigodescuento) {
global $RAD_dbi, $db, $V_mod;
	$TMP_descuentoporc=0;
	///$TMP_result = sql_query("SELECT * FROM GIE_articulosprecios WHERE fechavalidez_desde<='".date("Y-m-d")."' AND fechavalidez_hasta>='".date("Y-m-d")."'", $RAD_dbi);
	//Obtener la familia raiz, ya que se pueden definir descuentos a ese nivel.
  if ($idarticulo>0) {
    $idfamilia=RAD_lookup("GIE_articulos","idfamilia","idarticulo",$idarticulo);
    $idfamilia_padre=RAD_lookup("GIE_articulosfamilias","idfamiliapadre","idfamilia",$idfamilia);
  } else {
    $idfamilia=RAD_lookup("GIE_articulos","idfamilia","idarticulo",$idarticulo);
    $idfamilia_padre=RAD_lookup("GIE_articulosfamilias","idfamiliapadre","idfamilia",$idfamilia);
  }
	$idusuario=getSessionVar("SESSION_U_idusuario");
	//$codigodescuento=getSessionVar("SESSION_codigodescuento");
	while($TMP_row = sql_fetch_array($TMP_result, $RAD_dbi)) {
		if (trim($TMP_row[codigodescuento])!="" && trim($TMP_row[codigodescuento])!=trim($codigodescuento)) continue;
		if (trim($TMP_row[codigodescuento])!="" && trim($TMP_row[codigodescuento])==trim($codigodescuento)) {
			if ($TMP_row[idarticulo]>0 && $TMP_row[idarticulo]!=$idarticulo) continue;
			//if ($TMP_row[idfamilia]>0 && $TMP_row[idfamilia]!=$idfamilia) continue;
      if ($TMP_row[idfamilia]>0 && $TMP_row[idfamilia]!=$idfamilia && $TMP_row[idfamilia]!=$idfamilia_padre) continue;
			if ($TMP_row[idusuario]>0 && $TMP_row[idusuario]!=$idusuario) continue;
			if ($TMP_descuentoporc==0) $TMP_descuentoporc=$TMP_row[descuentoporcentaje];
			if ($TMP_row[descuentoporcentaje]>$TMP_descuentoporc) $TMP_descuentoporc=$TMP_row[descuentoporcentaje];
			continue;
		}
		if ($TMP_row[idarticulo]>0 && $TMP_row[idarticulo]!=$idarticulo) continue;
		//if ($TMP_row[idfamilia]>0 && $TMP_row[idfamilia]!=$idfamilia) continue;
    if ($TMP_row[idfamilia]>0 && $TMP_row[idfamilia]!=$idfamilia && $TMP_row[idfamilia]!=$idfamilia_padre) continue;
		if ($TMP_row[idusuario]>0 && $TMP_row[idusuario]!=$idusuario) continue;
		if ($TMP_descuentoporc==0) $TMP_descuentoporc=$TMP_row[descuentoporcentaje];
		if ($TMP_row[descuentoporcentaje]>$TMP_descuentoporc) $TMP_descuentoporc=$TMP_row[descuentoporcentaje];
	}
	//if ($TMP_descuentoporc!=0) alert("Porcentaje encontrado=".$TMP_descuentoporc);
	return $TMP_descuentoporc;
}
//----------------------------------------------------------------------------------------
function linkFamArt($TMP_idfam,$TMP_idart,$TMP_lit) {
        global $PHP_SELF, $V_dir, $V_mod, $headeroff, $footeroff, $menuoff, $blocksoff, $SEO_showfamilia, $SESSION_SID;
        if ($PHP_SELF=="") $PHP_SELF=_DEF_INDEX;
	$V_mod2="tienda";
        //$TMP_link=$PHP_SELF.'?V_dir='.$V_dir.'&amp;V_mod='.$V_mod2;
        $TMP_link=_DEF_URL.$PHP_SELF.'?V_dir='.$V_dir.'&amp;V_mod='.$V_mod2;
        if ($TMP_idfam>0) $TMP_link.='&amp;idfam='.$TMP_idfam;
        if ($TMP_idart>0) $TMP_link.='&amp;id='.$TMP_idart;
        if ($SESSION_SID!="&" && $SESSION_SID!="&amp;") $TMP_link.=$SESSION_SID;
        $TMP_lit=str_replace(" ","-",$TMP_lit);

        //if ($headeroff!="") $TMP_link.='&amp;headeroff='.$headeroff;
        //if ($footeroff!="") $TMP_link.='&amp;footeroff='.$footeroff;
        //if ($menuoff!="") $TMP_link.='&amp;menuoff='.$menuoff;
        //if ($blocksoff!="") $TMP_link.='&amp;blocksoff='.$blocksoff;
        return $TMP_link;
}
//----------------------------------------------------------------------------------------
function misDatosForm() {
 global $SESSION_SID, $dbname, $PHP_SELF, $V_dir, $V_mod, $V_idmod, $RAD_dbi, $PHPSESSID, $headeroff, $menuoff, $footeroff, $idin, $idout, $blocksoff, $op, $id;
 // El formulario de entrada es el articulo "idin" y el mostrado a la salida es "idout". La tabla es "tabla"

//$idin Pagina que contiene el Formulario de alta de datos del Pedido y el Usuario Registrado

if ($dbname=="") $dbname=_DEF_dbname;
$TMP_lang=getSessionVar("SESSION_lang");

$TMP_ses_code=getSessionVar("securimage_code_value");
$TMP_req_code=$_REQUEST["captcha_code"];
if (strtoupper($TMP_req_code)!=strtoupper($TMP_ses_code) && $TMP_ses_code!="") {
	foreach($_REQUEST as $TMP_k=>$TMP_v) {
		if ($TMP_k=="captcha_code") error(_DEF_NLSSecImgErr);
	}
}

global $TMP_secu;
include_once("modules/".$V_dir."/lib.secu.php");

$id=$idin;

$TMP_optsano="";
$anohoy=date("Y");
for($ki=$anohoy-18; $ki>$anohoy-100; $ki--) {
	$TMP_optsano.="<option value='".$ki."'>".$ki."</option>";
}

$TMP_optsproy="";
$TMP_optsproyall="";
$TMP_optsproyvol="";
$TMP_res=sql_query("SELECT * FROM proyectos order by proyecto", $RAD_dbi);
while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
	$TMP_proyecto=$TMP_row[proyecto];
	foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
	$TMP_optsproyall.="<option value='".str_replace("'","`",$TMP_proyecto)."'>".$TMP_row[proyecto]."</option>";
	if ($TMP_row[aceptavoluntarios]=="1") $TMP_optsproyvol.="<option value='".str_replace("'","`",$TMP_proyecto)."'>".$TMP_row[proyecto]."</option>";
	if ($TMP_row[aceptadonaciones]=="1") $TMP_optsproy.="<option value='".str_replace("'","`",$TMP_proyecto)."'>".$TMP_row[proyecto]."</option>";
}

$TMP_optsprov="";
$TMP_res=sql_query("SELECT * FROM provincias order by provincia", $RAD_dbi);
while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
	$TMP_provincia=$TMP_row[provincia];
	foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
	$TMP_optsprov.="<option value='".str_replace("'","`",$TMP_provincia)."'>".$TMP_row[provincia]."</option>";
}

$TMP_optspais="";
$TMP_res=sql_query("SELECT * FROM paises order by pais", $RAD_dbi);
while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
	$TMP_pais=$TMP_row[pais];
	foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
	if (strtolower(substr($TMP_row[pais],0,4))=="espa") $TMP_optspais.="<option selected value='".str_replace("'","`",$TMP_pais)."'>".$TMP_row[pais]."</option>";
	else $TMP_optspais.="<option value='".str_replace("'","`",$TMP_pais)."'>".$TMP_row[pais]."</option>";
}

$TMP_res=sql_query("SELECT * FROM articulos where id='$id'", $RAD_dbi);
$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
$TMP_row[contenido]=str_replace("<!-- securimage -->",$TMP_secu,$TMP_row[contenido]);
$TMP_row[contenido]=str_replace("<!-- nombre -->",$_REQUEST[nombre],$TMP_row[contenido]);
$TMP_row[contenido]=str_replace("<!-- V_idmod -->",$V_idmod,$TMP_row[contenido]);
$TMP_row[contenido]=str_replace("<!-- optsprov -->",$TMP_optsprov,$TMP_row[contenido]);
$TMP_row[contenido]=str_replace("<!-- optsano -->",$TMP_optsano,$TMP_row[contenido]);
$TMP_row[contenido]=str_replace("<!-- optsproyall -->",$TMP_optsproyall,$TMP_row[contenido]);
$TMP_row[contenido]=str_replace("<!-- optsproyvol -->",$TMP_optsproyvol,$TMP_row[contenido]);
$TMP_row[contenido]=str_replace("<!-- optsproy -->",$TMP_optsproy,$TMP_row[contenido]);
$TMP_row[contenido]=str_replace("<!-- optspais -->",$TMP_optspais,$TMP_row[contenido]);

return $TMP_row[contenido];
}
//----------------------------------------------------------------------------------------
function grabaDatosForm($TMP_idventa) {
global $idout, $SESSION_SID, $PHP_SELF, $V_dir, $RAD_dbi, $PHPSESSID, $V0_nombre, $V0_direccion, $V0_email, $V0_telefono, $V0_codprovincia, $V0_codpostal, $V0_localidad, $V0_codigodescuento, $V0_observaciones;
// Graba los datos de cliente, y cambia estado de pedido

	$TMP_content="<h3>"._DEF_NLSCestaPendPago."</h3>";;
	$V0_email=strtolower(str_replace(" ","",$V0_email));
	//$V0_telefono=preg_replace("/[^0-9]/","", $V0_telefono);

// Falta si el usuario tiene un Codigo de Descuento, o por usuario
	$V0_nombre=$_REQUEST["nombre"]." ".$_REQUEST["apellidos"];
	$V0_direccion=$_REQUEST["tipovia"]." ".$_REQUEST["via"]." ".$_REQUEST["piso"];
	$V0_localidad=$_REQUEST["poblacion"];
	$V0_codpostal=$_REQUEST["codpostal"];
	$V0_email=$_REQUEST["email"];
	$V0_telefono=$_REQUEST["telefono"];
	$V0_observaciones=$_REQUEST["observaciones"];
	//////$V0_codprovincia=substr($V0_codpostal,0,2);

	//$cmdSQL2 = "UpDATE GIE_ventas SeT nombrecliente=".converttosql($V0_nombre).", direccion=".converttosql($V0_direccion).", email=".converttosql($V0_email).", telefono=".converttosql($V0_telefono).", codigodescuento=".converttosql($V0_codigodescuento).", observaciones2=".converttosql($V0_observaciones).", codprovincia=".converttosql($V0_codprovincia).", localidad=".converttosql($V0_localidad).", codpostal=".converttosql($V0_codpostal).", procesado='1', cobrado='0', enviado='0' WHERE idventa='$TMP_idventa'";
	$cmdSQL2 = "UPDaTE GIE_ventas SeT nombrecliente=".converttosql($V0_nombre).", direccion=".converttosql($V0_direccion).", email=".converttosql($V0_email).", telefono=".converttosql($V0_telefono).", observaciones2=".converttosql($V0_observaciones).", codprovincia=".converttosql($V0_codprovincia).", localidad=".converttosql($V0_localidad).", codpostal=".converttosql($V0_codpostal).", procesado='1', cobrado='0', enviado='0' WHERE idventa='$TMP_idventa'";
	$TMP_result2 = sql_query($cmdSQL2, $RAD_dbi);

	setSessionVar("SESSION_codigodescuento",$V0_codigodescuento,0);

	recalculaTotalOperacion("venta",$TMP_idventa);

	//foreach($_REQUEST as $TMP_k=>$TMP_v) echo $TMP_k."=".$TMP_v."<br>";
	$id=$idout;
	$id="";
	include_once("modules/".$V_dir."/lib.savereg.php");
	global $idventa;
	$idventa=$TMP_idventa;
	savereg("GIE_ventas",$_REQUEST[asunto]);

	return $TMP_content;
}
//----------------------------------------------------------------------------------------
function formPagoTPV($TMP_idventa) {
global $SESSION_SID, $PHP_SELF, $V_dir, $V_mod, $RAD_dbi, $PHPSESSID, $V0_nombre, $V0_direccion, $V0_email, $V0_telefono, $V0_observaciones;

	if (_DEF_TPV_URLNOTIF=="") {
		die("<b><blink>"._DEF_NLSCestaErr8."</b></blink>");
		return "";
	}

	$TMP_res=sql_query("SELECT * FROM GIE_ventas WHERE idventa='$TMP_idventa'", $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);

	if ($TMP_row[uniqidventa]=="") $TMP_row[uniqidventa]=$TMP_row[idventa]."_0";
	$A_x=explode("_",$TMP_row[uniqidventa]);
	$A_x[1]++;
	$Merchant_Order=$TMP_row[idventa];
	if (strlen($Merchant_Order)<4) $Merchant_Order="0000".$Merchant_Order;
	$Merchant_OrderCECA=$A_x[1]."0".$Merchant_Order;
	$Merchant_OrderCECA=$TMP_row[idventa];
	$Merchant_Order.="_".$A_x[1];
	sql_query("UPDATE GIE_ventas SET uniqidventa='".$Merchant_Order."' WHERE idventa='$TMP_idventa'", $RAD_dbi);

	$TMP_total=($TMP_row[total]+$TMP_row[gastosenvio])*100;

	$TMP_URLOK=_DEF_TPV_URLNOTIF."index.php?V_dir=$V_dir&V_mod=$V_mod&idventa=$TMP_idventa&idses=".$TMP_row[sesioncesta]."&op=OK&PHPSESSID=$PHPSESSID";
	$TMP_URLKO=_DEF_TPV_URLNOTIF."index.php?V_dir=$V_dir&V_mod=$V_mod&idventa=$TMP_idventa&idses=".$TMP_row[sesioncesta]."&op=KO&PHPSESSID=$PHPSESSID";
	$TMP_URLNOTIF=_DEF_TPV_URLNOTIF."index.php?V_dir=$V_dir&V_mod=$V_mod&idventa=$TMP_idventa&idses=".$TMP_row[sesioncesta]."&op=NOTIF&PHPSESSID=$PHPSESSID";

	$TMP_URL_PAGO=_DEF_TPV_URLPAGO;
	$CLAVE_SECRETA=_DEF_TPV_CLAVE_SECRETA;
	$MERCHANT_CODE=_DEF_TPV_MERCHANT_CODE;
	$TERMINAL=_DEF_TPV_TERMINAL;
	$TRANSACTION_TYPE=_DEF_TPV_TRANSACTION_TYPE;

	$MONEDA="978";
	$EXPONENTE="2";

	if (strlen($TMP_idventa)<4) $TMP_idventa="0000".$TMP_idventa;

	// $MerchantSignature = SHA-1(Merchant_Amount + Merchant_Order + MerchantCode + Merchant_Currency + TRANSACTION_TYPE + URLNOTIF + CLAVE SECRETA)
	$MerchantSignature=strtoupper(sha1($TMP_total.$Merchant_Order.$MERCHANT_CODE.$MONEDA.$TRANSACTION_TYPE.$TMP_URLNOTIF.$CLAVE_SECRETA));

	if (_DEF_TPV_ACQUIRER_BIN!="" && _DEF_TPV_ACQUIRER_BIN!="_DEF_TPV_ACQUIRER_BIN") { // TPV CECA
		$cadena=$CLAVE_SECRETA.$MERCHANT_CODE._DEF_TPV_ACQUIRER_BIN.$TERMINAL.$Merchant_OrderCECA.$TMP_total.$MONEDA.$EXPONENTE."SHA1".$TMP_URLOK.$TMP_URLKO;
		//Clave_encriptacion+MerchantID+AcquirerBIN+TerminalID+Num_operacion+Importe+TipoMoneda+Exponente+CifradoCadena SHA1+URL_OK+URL_NOK)
		$MerchantSignature=sha1($cadena);
		$TMP='<form method="post" action="'.$TMP_URL_PAGO.'" name="pago" enctype="application/x-www-form-urlencoded">
		<input type="hidden" name="MerchantID" value="'.$MERCHANT_CODE.'">
		<input type="hidden" name="AcquirerBIN" value="'._DEF_TPV_ACQUIRER_BIN.'">
		<input type="hidden" name="TerminalID" value="'.$TERMINAL.'">
		<input type="hidden" name="Num_operacion" value="'.$Merchant_OrderCECA.'">
		<input type="hidden" name="Importe" value="'.$TMP_total.'">
		<input type="hidden" name="TipoMoneda" value="'.$MONEDA.'">
		<input type="hidden" name="Exponente" value="2">
		<input type="hidden" name="Idioma" value="1">
		<input type="hidden" name="Codigo_pedido" value="V'.$TMP_row[idventa].'">
		<input type="hidden" name="Pago_soportado" value="SSL">
		<input type="hidden" name="Firma" value="'.$MerchantSignature.'">
		<input type="hidden" name="URL_OK" value="'.$TMP_URLOK.'">
		<input type="hidden" name="URL_NOK" value="'.$TMP_URLKO.'">
		<input type="hidden" name="Descripcion" value="Pedido OnLine. '.cambiaAcentos(html_entity_decode(_DEF_SITENAME,ENT_NOQUOTES,"utf-8")).'">
		<input type="hidden" name="Cifrado" value="SHA1">';
	} else { // TPV Redsys
		$TMP='<form method="post" action="'.$TMP_URL_PAGO.'" name="pago" enctype="application/x-www-form-urlencoded">
		<input type="hidden" name="Ds_Merchant_Amount" value="'.$TMP_total.'">
		<input type="hidden" name="Ds_Merchant_Currency" value="'.$MONEDA.'">
		<input type="hidden" name="Ds_Merchant_Order" value="'.$Merchant_Order.'">
		<input type="hidden" name="Ds_Merchant_MerchantName" value="'.cambiaAcentos(html_entity_decode(_DEF_SITENAME,ENT_NOQUOTES,"utf-8")).'">
		<input type="hidden" name="Ds_Merchant_ConsumerLanguage" value="001">
		<input type="hidden" name="Ds_Merchant_ProductDescription" value="Pedido OnLine. '.cambiaAcentos(html_entity_decode(_DEF_SITENAME,ENT_NOQUOTES,"utf-8")).'">
		<input type="hidden" name="Ds_Merchant_Titular" value="'.cambiaAcentos($TMP_row[nombrecliente]).'">

		<input type="hidden" name="Ds_Merchant_TransactionType" value="'.$TRANSACTION_TYPE.'">
		<input type="hidden" name="Ds_Merchant_MerchantCode" value="'.$MERCHANT_CODE.'">
		<input type="hidden" name="Ds_Merchant_MerchantURL" value="'.$TMP_URLNOTIF.'">
		<input type="hidden" name="Ds_Merchant_UrlOK" value="'.$TMP_URLOK.'">
		<input type="hidden" name="Ds_Merchant_UrlKO" value="'.$TMP_URLKO.'">
		<input type="hidden" name="Ds_Merchant_Terminal" value="'.$TERMINAL.'">
		<input type="hidden" name="Ds_Merchant_MerchantSignature" value="'.$MerchantSignature.'">
		<input type="hidden" name="charset" value="utf8">';
	}

	//include_once("modules/".$V_dir."/sha_1.php");
	//$MerchantSignature2=strtoupper(sha_1($TMP_total.$TMP_idventa.$MERCHANT_CODE.$MONEDA.$CLAVE_SECRETA));
	
	$TMP.='
		<input type="submit" name="Envia" value=" Pago Tarjeta ">
		</form>
<br><br><h2>'._DEF_NLSCestaMsg2.'</h2>
<script>
document.forms.pago.submit();
</script>

';

	return $TMP;
}
//-------------------------------------------------------------------------------
function formPagoPaypal($TMP_idventa) {
// Formulario para pago de cesta por Paypal
global $RAD_dbi;

/* ---------------------------------------------------------------------------------------------------------
  Hay que cambiar direccion del formulario de pruebas es https://www.sandbox.paypal.com/cgi-bin/webscr 
     pero en ventas reales deberemos indicar https://www.paypal.com/cgi-bin/webscr
     tambien hay que cambiar:  shopping_url, return, cancel_return, notify_url, business
 VARIABLES PAYPAL UTILIZADAS
 cmd > indica el tipo de fichero que va a recoger PayPal 
	_cart : varios items
	_donations : donaciones
	_xclick : boton de compra
 business > indica el identificador del negocio registrado en paypal. Ejemplo : buyer_1265883185_biz@gmail.com
 shopping_url > la direccion de nuestra tienda online . Ejemplo : http://www.xxxxx.com
 currency_code > el tipo de moneda (USD , EUR ...)
 return > sera el enlace de vuelta a nuestro negocio que ofrece paypal
 notify_url > es donde recogeremos el estado del pago y un gran numeros de variables con informacion adicional (paypal_ipn.php)
 rm > metodo a utilizar para enviar la informacion de vuelta a nuestro sitio. RM=1 enviada por GET , RM=2 informacion enviada por POST
 item_number_X > identificador del producto
 item_name_X > nombre del producto
 amount_X > precio del producto
 quantity_X > cantidad del producto
Otros Parametros:
<!-- PayPal Configuration -->
<input type="hidden" name="image_url" value="<? echo "$paypal[site_url]$paypal[image_url]"; ?>"> // 150x50
<input type="hidden" name="lc" value="<?=$paypal[lc]?>">
<input type="hidden" name="bn" value="<?=$paypal[bn]?>">
<input type="hidden" name="cbt" value="<?=$paypal[continue_button_text]?>">
<!-- Payment Page Information -->
<input type="hidden" name="no_shipping" value="<?=$paypal[display_shipping_address]?>">
<input type="hidden" name="no_note" value="<?=$paypal[display_comment]?>">
<input type="hidden" name="cn" value="<?=$paypal[comment_header]?>">
<input type="hidden" name="cs" value="<?=$paypal[background_color]?>">
<!-- Product Information -->
<input type="hidden" name="item_name" value="<?=$paypal[item_name]?>">
<input type="hidden" name="amount" value="<?=$paypal[amount]?>">
<input type="hidden" name="quantity" value="<?=$paypal[quantity]?>">
<input type="hidden" name="item_number" value="<?=$paypal[item_number]?>">
<input type="hidden" name="undefined_quantity" value="<?=$paypal[edit_quantity]?>">
<input type="hidden" name="on0" value="<?=$paypal[on0]?>">
<input type="hidden" name="os0" value="<?=$paypal[os0]?>">
<input type="hidden" name="on1" value="<?=$paypal[on1]?>">
<input type="hidden" name="os1" value="<?=$paypal[os1]?>">
<!-- Shipping and Misc Information -->
<input type="hidden" name="shipping" value="<?=$paypal[shipping_amount]?>">
<input type="hidden" name="shipping2" value="<?=$paypal[shipping_amount_per_item]?>">
<input type="hidden" name="handling" value="<?=$paypal[handling_amount]?>">
<input type="hidden" name="tax" value="<?=$paypal[tax]?>">
<input type="hidden" name="custom" value="<?=$paypal[custom_field]?>">
<input type="hidden" name="invoice" value="<?=$paypal[invoice]?>">
<!-- Customer Information -->
<input type="hidden" name="first_name" value="<?=$paypal[firstname]?>">
<input type="hidden" name="last_name" value="<?=$paypal[lastname]?>">
<input type="hidden" name="address1" value="<?=$paypal[address1]?>">
<input type="hidden" name="address2" value="<?=$paypal[address2]?>">
<input type="hidden" name="city" value="<?=$paypal[city]?>">
<input type="hidden" name="state" value="<?=$paypal[state]?>">
<input type="hidden" name="zip" value="<?=$paypal[zip]?>">
<input type="hidden" name="email" value="<?=$paypal[email]?>">
<input type="hidden" name="night_phone_a" value="<?=$paypal[phone_1]?>">
<input type="hidden" name="night_phone_b" value="<?=$paypal[phone_2]?>">
<input type="hidden" name="night_phone_c" value="<?=$paypal[phone_3]?>">
----------------------------------------------------------------------------------------------------- */

$TMP_dirtheme=getSessionVar("SESSION_theme");
$TMP="
<br><br><h2>"._DEF_NLSCestaMsg2."</h2>
<form name='formTpv' method='post' action='https://www.sandbox.paypal.com/cgi-bin/webscr'>
<input name='cmd' type='hidden' value='_cart'> 
<input name='upload' type='hidden' value='1'>
<input name='business' type='hidden' value='"._DEF_PAYPALBUSINESS."'>
<input name='shopping_url' type='hidden' value='"._DEF_URL."'>
<input name='currency_code' type='hidden' value='EUR'>
<input name='return' type='hidden' value='"._DEF_URL."index.php?V_dir=MSC&V_mod=cesta&idventa=".$TMP_idventa."&idses=".$TMP_row[sesioncesta]."&op=OK&PHPSESSID=$PHPSESSID'>
<input name='cancel_return' type='hidden' value='"._DEF_URL."index.php?V_dir=MSC&V_mod=cesta&idventa=".$TMP_idventa."&idses=".$TMP_row[sesioncesta]."&op=KO&PHPSESSID=$PHPSESSID'>
<input name='notify_url' type='hidden' value='"._DEF_URL."index.php?V_dir=MSC&V_mod=cesta&idventa=".$TMP_idventa."&idses=".$TMP_row[sesioncesta]."&op=NOTIF&PHPSESSID=$PHPSESSID'>
<input name='rm' type='hidden' value='2'>
<input type='hidden' name='image_url' value='"._DEF_URL."themes/".$TMP_dirtheme."/logo.png'>
<input type='submit' name='Envia' value=' Pago Paypal '>
";

	// productos del carro de la compra
	$contador = 0;
	$TMP_res=sql_query("SELECT * FROM GIE_ventasdetalle where idventa='".$TMP_idventa."'", $RAD_dbi);
	while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
		$contador++; //Numero de item que despues usaremos en el atribute name de los inputs 
		$id = $TMP_row['idventadetalle'];
		$producto = RAD_lookup("GIE_articulos","articulo","idarticulo",$TMP_row['idarticulo']);
		$producto = substr(str_replace("'","`",$producto),0,40);
		$precio = $TMP_row['precio'];
		$x = $TMP_row['unidades']*1;
		$TMP.="
			<input name='item_number_".$contador."' type='hidden' value='".$id."'>
			<input name='item_name_".$contador."' type='hidden' value='".$producto."'> 
			<input name='amount_".$contador."' type='hidden' value='".$precio."'> 
			<input name='quantity_".$contador."' type='hidden' value='".$x."'> 
		";
	}

$TMP.="
</form>
<script type='text/javascript'>
document.formTpv.submit();
</script>
";

	return $TMP;
}
/////////////////////////////////////////////////////////////////////////////////////
function cambiaAcentos($cadena) {
    $cadena=str_replace("Á", "A", $cadena);
    $cadena=str_replace("á", "a", $cadena);
    $cadena=str_replace("É", "E", $cadena);
    $cadena=str_replace("é", "e", $cadena);
    $cadena=str_replace("Í", "I", $cadena);
    $cadena=str_replace("í", "i", $cadena);
    $cadena=str_replace("Ó", "O", $cadena);
    $cadena=str_replace("ó", "o", $cadena);
    $cadena=str_replace("Ú", "U", $cadena);
    $cadena=str_replace("ú", "u", $cadena);
    $cadena=str_replace("Ñ", "N", $cadena);
    $cadena=str_replace("ñ", "n", $cadena);
    $cadena=str_replace(utf8_encode("Á"), "A", $cadena);
    $cadena=str_replace(utf8_encode("á"), "a", $cadena);
    $cadena=str_replace(utf8_encode("É"), "E", $cadena);
    $cadena=str_replace(utf8_encode("é"), "e", $cadena);
    $cadena=str_replace(utf8_encode("Í"), "I", $cadena);
    $cadena=str_replace(utf8_encode("í"), "i", $cadena);
    $cadena=str_replace(utf8_encode("Ó"), "O", $cadena);
    $cadena=str_replace(utf8_encode("ó"), "o", $cadena);
    $cadena=str_replace(utf8_encode("Ú"), "U", $cadena);
    $cadena=str_replace(utf8_encode("ú"), "u", $cadena);
    $cadena=str_replace(utf8_encode("Ñ"), "N", $cadena);
    $cadena=str_replace(utf8_encode("ñ"), "n", $cadena);
    return $cadena;
}
?>
