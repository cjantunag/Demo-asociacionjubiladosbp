<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");
// Modulo de control del formulario de donativos
// El formulario de entrada es el articulo "idin" y el mostrado a la salida es "idout"

global $V_mod, $RAD_dbi, $dbname, $blocksoff, $op, $V_dir, $V_idmod;
if ($dbname=="") $dbname=_DEF_dbname;

        if (file_exists("modules/".$V_dir."/common.app.php")) include_once ("modules/".$V_dir."/common.app.php");
        if (_DEF_appname!="" && _DEF_appname!="_DEF_appname") if (file_exists("modules/$V_dir/common.app."._DEF_appname.".php")) include_once ("modules/$V_dir/common.app."._DEF_appname.".php");
        if (_DEF_appinstance!="" && _DEF_appinstance!="_DEF_appinstance") if (file_exists("modules/$V_dir/common.app."._DEF_appinstance.".php")) include_once ("modules/$V_dir/common.app."._DEF_appinstance.".php");

//--------------------------------------------------------------------------------------------------------------
if ($op=="print"||$op=="OK"||$op=="NOTIF"||$op=="KO") {
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
	$tmpFile="files/tmp/donat.".uniqid("");
	$fp = fopen($tmpFile,"w");
	fwrite($fp,$TMP_deb);
	fclose($fp);
    }
}

//--------------------------------------------------------------------------------------------------------------
if ($op=="print" || $op=="OK" || $op=="KO" || $op=="NOTIF") {
	$headeroff="x";
	$footeroff="x";
	$blocksoff="x";
	confirmarpago();
	return;
}

$TMP_content="";
$_REQUEST[email]=trim($_REQUEST[email]);

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

$V_mod="articulos";

include_once("header.php");

//if ($email!="" && $clave!="") {
//	identifica();
//	$id=$idin;
//} else if ($save=="" && $op=="" && $conf=="") {
if ($save=="" && $op=="" && $conf=="" && $acepto=="") {
	//foreach($_REQUEST as $TMP_k=>$TMP_v) echo $TMP_k."=".$TMP_v."<br>";
	$id=$idin;
	if ($tipo=="entidad") $id=$identidad;
} else {
	//foreach($_REQUEST as $TMP_k=>$TMP_v) echo $TMP_k."=".$TMP_v."<br>";
	$id=$idout;
	include_once("modules/".$V_dir."/lib.savereg.php");
	global $iddonativo;
	$iddonativo=savereg("donativos","Donacion");
	if ($_REQUEST[domiciliacion]=="tarjeta") {
		$TMP_content=formPagoTPV($iddonativo);
		echo $TMP_content;
		include_once("footer.php");
		return;
	}
	if ($_REQUEST[domiciliacion]=="paypal") {
		$TMP_content=formPagoPaypal($iddonativo);
		echo $TMP_content;
		include_once("footer.php");
		return;
	}
}


$TMP_optsano="";
$anohoy=date("Y");
for($ki=$anohoy-18; $ki>$anohoy-100; $ki--) {
	$TMP_optsano.="<option value='".$ki."'>".$ki."</option>";
}

$TMP_Gfecha=RAD_gregorianDate(date("Y-m-d"));
$TMP_optsproy="";
$TMP_res=sql_query("SELECT * FROM proyectos order by proyecto", $RAD_dbi);
while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
	$TMP_proyecto=$TMP_row[proyecto];
	foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
	$TMP_Gfechafin=RAD_gregorianDate($TMP_row[fechafin]);
	if (!$TMP_Gfechafin>0) $TMP_activo="1";
	else if ($TMP_Gfechafin>$TMP_Gfecha) $TMP_activo="1";
	else continue;
	$TMP_optsproy.="<option value='".str_replace("'","`",$TMP_proyecto)."'>".$TMP_row[proyecto]."</option>";
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

$TMP_optsprofs="";
$TMP_res=sql_query("SELECT * FROM profesiones order by profesion", $RAD_dbi);
while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
	$TMP_profesion=$TMP_row[profesion];
	foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
	$TMP_optsprofs.="<option value='".str_replace("'","`",$TMP_profesion)."'>".$TMP_row[profesion]."</option>";
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
$TMP_row[contenido]=str_replace("<!-- optsprofs -->",$TMP_optsprofs,$TMP_row[contenido]);

echo $TMP_row[contenido];

include_once("modules/".$V_dir."/lib.ajax.php");

include_once("footer.php");
return;
//------------------------------------------------------------------------------------------------------------------------
function identifica() {
	global $RAD_dbi, $V_dir, $clave, $email;

	$TMP_lang=getSessionVar("SESSION_lang");

	$TMP_res=sql_query("SELECT * FROM usuarios WHERE usuario=".converttosql($email), $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
	if (!$TMP_row[idusuario]>0 || $clave=="") {
		return(_INVALID_USER);
	}
	if($TMP_row[clave]==$clave || $TMP_row[clave]==md5($clave) || crypt($clave,$TMP_row[clave])==$TMP_row[clave]) {
		return("OK");
	} else {
		return(_INVALID_USER);
	}
}
//------------------------------------------------------------------------------------------------------------------------
function confirmarpago() {
global $RAD_dbi, $V_dir, $PHPSESSID, $op, $iddonativo, $idses;

	include_once("modules/".$V_dir."/lib.Email.php");

	if ($iddonativo!="") $iddonativo=$iddonativo*1;

	// Se comprueba que el GET se hace desde CECA/Redsys/Sermepa/Paypal

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

  //--------------------------------------------------------------------------------------------------------------
  if (($op=="OK"||$op=="NOTIF") && $iddonativo>0) { // mensaje NOTIF solo puede proceder de la pasarela de pagos (confirmacion del pago)
	$TMP_result = sql_query("SELECT * FROM donativos WHERE iddonativo='$iddonativo' and uniqid='$idses'", $RAD_dbi);
	//$TMP_result = sql_query("SELECT * FROM donativos WHERE iddonativo='$iddonativo'", $RAD_dbi);
	$TMP_row = sql_fetch_array($TMP_result, $RAD_dbi);
	if ($TMP_row[iddonativo]!=$iddonativo) die("Error Donativo $iddonativo"); // PRUEBA
	// Comprobar que quien submite este form es CECA/Redsys/Sermepa
	if ($op=="NOTIF" && $_POST["Ds_Response"]=="0000") {
     		if (getenv("REMOTE_ADDR")!="195.76.9.187" && getenv("REMOTE_ADDR")!="193.16.243.13"
		  && getenv("REMOTE_ADDR")!="195.76.9.117" && getenv("REMOTE_ADDR")!="195.76.9.149"
		  && getenv("REMOTE_ADDR")!="193.16.243.173" && getenv("REMOTE_ADDR")!="195.76.9.222"
		  && getenv("REMOTE_ADDR")!="sis-t.sermepa.es" && getenv("REMOTE_ADDR")!="sis.sermepa.es"
		  && getenv("REMOTE_ADDR")!="www.paypal.com" && getenv("REMOTE_ADDR")!="www.sandbox.paypal.com"
		  && getenv("REMOTE_ADDR")!="23.54.82.234" && getenv("REMOTE_ADDR")!="173.0.82.77"
		  && getenv("REMOTE_ADDR")!="sis-t.sermepa.es" && getenv("REMOTE_ADDR")!="sis.sermepa.es" 
		  && getenv("REMOTE_ADDR")!="sis-t.redsys.es" && getenv("REMOTE_ADDR")!="sis.redsys.es" ) {
			die(_DEF_NLSCestaErr1." <! ".getenv("REMOTE_ADDR")." ".$op." >");
		}
		//$sesion=uniqid();
		//$cmdSQL2 = "UPDATE donativos SET uniqid='$sesion', cobrado='1',fechacobro='".date("Y-m-d H:i:s")."' WHERE iddonativo='$iddonativo'";
		$cmdSQL2 = "UPDATE donativos SET cobrado='1',fechacobro='".date("Y-m-d H:i:s")."' WHERE iddonativo='$iddonativo'";
		$TMP_result2 = sql_query($cmdSQL2, $RAD_dbi);
	}
	$TMP_result=sql_query("SELECT * FROM donativos WHERE iddonativo='$iddonativo'", $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
	$TMP_content.=showDonativo($iddonativo);
	//F_SendMail(_DEF_ADMINMAIL,_DEF_ADMINMAILNAME,"jtaibos@gmail.com","","Donativo Ingresado en "._DEF_URL, $TMP_content, "", "", true); // from,to PRUEBA
	if ($TMP_row[enviadoemail]!="1" && $op=="NOTIF" && $_POST["Ds_Response"]=="0000") {
		F_SendMail(_DEF_ADMINMAIL,_DEF_ADMINMAILNAME,$TMP_row[email],"","Donativo Ingresado en "._DEF_URL, $TMP_content, "", "", true); // from,to
		$cmdSQL2 = "UPDATE donativos SET enviadoemail='1' WHERE iddonativo='$iddonativo'";
		$TMP_result2 = sql_query($cmdSQL2, $RAD_dbi);
		sleep(1);
		F_SendMail(_DEF_ADMINMAIL,_DEF_ADMINMAILNAME,_DEF_ADMINMAIL,"","Enviado correo a cliente de Donativo Ingresado en "._DEF_URL.". Id.Donativo=".$TMP_row[iddonativo], $TMP_content, "", "", true); // from,to
	}
	global $blocksoff;
	$blocksoff="x";
	include_once ("header.php");
	echo "<h1>"._DEF_NLSDonativo."</h1>";
	OpenTable();
	if ($TMP_row[cobrado]=="1") {
		echo "<h3>DONATIVO INGRESADO</h3><br />".$TMP_content."\n".$TMP_print;		
		$TMP_total=$TMP_row[cantidad];
		if (!is_numeric($TMP_total)) $TMP_total=$TMP_row[otracantidad];
		$TMP_total=str_replace(",",".",$TMP_total);
		$TMP_total=$TMP_total*1;
	} else {
		F_SendMail(_DEF_ADMINMAIL,_DEF_ADMINMAILNAME,_DEF_ADMINMAIL,"","Mensaje a Cliente de Error en Ingreso de Donativo Id.Donativo Web=".$iddonativo, $TMP_content, "", "", true); // from,to
		echo "<h3>ERROR EN INGRESO DE DONATIVO</h3><br />Ponte en contacto con nosotros para verificar si tu Donativo ha sido ingresado<br>".$TMP_content."\n".$TMP_print;
	}
	CloseTable();	
	include_once("modules/".$V_dir."/lib.ajax.php");
	include_once ("footer.php"); 
	return;
  }

  //--------------------------------------------------------------------------------------------------------------
  if (($op=="KO"||$op=="print") && $iddonativo>0) {
	$TMP_result = sql_query("SELECT * FROM donativos WHERE iddonativo='$iddonativo' and uniqid='$idses'", $RAD_dbi);
	//$TMP_result = sql_query("SELECT * FROM donativos WHERE iddonativo='$iddonativo'", $RAD_dbi);
	$TMP_row = sql_fetch_array($TMP_result, $RAD_dbi);
	if ($TMP_row[iddonativo]!=$iddonativo) die("Error Donativo $iddonativo");
	$TMP_content.=showDonativo($iddonativo);
	if ($TMP_row[cobrado]=="1") $TMP_pagado="Ingresado";
	else $TMP_pagado="NO Ingresado";
	if ($op=="KO") F_SendMail(_DEF_ADMINMAIL,_DEF_ADMINMAILNAME,$TMP_row[email],"","Donativo ".$TMP_pagado." en "._DEF_URL, $TMP_content, "", "", true); // from,to
	global $blocksoff;
	$blocksoff="x";
	include_once ("header.php");
	echo "<h1>"._DEF_NLSDonativo."</h1>";
	OpenTable();
	echo "<h3>DONATIVO ";
	if ($TMP_row[cobrado]=="1") echo "INGRESADO";
	else echo "NO INGRESADO";
	echo "</h3><br />".$TMP_content.$TMP_print;
	CloseTable();
	include_once("modules/".$V_dir."/lib.ajax.php");
	include_once ("footer.php"); 
	return;
  }
}
//--------------------------------------------------------------------------------------------------------------
function showDonativo($TMP_iddonativo) {
global $SESSION_SID, $PHP_SELF, $V_dir, $V_mod, $RAD_dbi, $PHPSESSID, $V0_nombre, $V0_direccion, $V0_email, $V0_telefono, $V0_observaciones;

	$TMP_res=sql_query("SELECT * FROM donativos WHERE iddonativo='$TMP_iddonativo'", $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);

	$TMP_res2=sql_query("SELECT * FROM proyectos where proyecto='".$TMP_row[proyecto]."'", $RAD_dbi);
	$TMP_row2=sql_fetch_array($TMP_res2, $RAD_dbi);
	foreach($TMP_row2 as $TMP_k=>$TMP_v) if ($TMP_row2[$TMP_k."_".$TMP_lang]!="") $TMP_row2[$TMP_k]=$TMP_row2[$TMP_k."_".$TMP_lang];

	$TMP="<table class=browse>";
	if ($TMP_row[razon]!="") $TMP.="<tr><td class=detailtit>Raz&oacute;n Social:</td><td class=detail>".$TMP_row[razon]."</td></tr>";
	if ($TMP_row[cif]!="") $TMP.="<tr><td class=detailtit>CIF:</td><td class=detail>".$TMP_row[cif]."</td></tr>";
	$TMP.="<tr><td class=detailtit>Nombre:</td><td class=detail>".$TMP_row[nombre]." ".$TMP_row[apellidos]."</td></tr>";
	$TMP.="<tr><td class=detailtit>Email:</td><td class=detail>".$TMP_row[email]."</td></tr>";
	$TMP.="<tr><td class=detailtit>Direcci&oacute;n:</td><td class=detail>".$TMP_row[tipovia]." ".$TMP_row[via]." ".$TMP_row[piso]."</td></tr>";
	$TMP.="<tr><td class=detailtit>Cod. Postal:</td><td class=detail>".$TMP_row[codpostal]."</td></tr>";
	$TMP.="<tr><td class=detailtit>Poblaci&oacute;n:</td><td class=detail>".$TMP_row[poblacion]."</td></tr>";
	$TMP.="<tr><td class=detailtit>Provincia:</td><td class=detail>".$TMP_row[provincia]."</td></tr>";
	$TMP.="<tr><td class=detailtit>Pa&iacute;s:</td><td class=detail>".$TMP_row[pais]."</td></tr>";
	$TMP.="<tr><td class=detailtit>Cod. Postal:</td><td class=detail>".$TMP_row[codpostal]."</td></tr>";
	$TMP_total=$TMP_row[cantidad];
	if (!is_numeric($TMP_total)) $TMP_total=$TMP_row[otracantidad];
	$TMP_total=str_replace(",",".",$TMP_total);
	$TMP_total=$TMP_total*1;
	$TMP.="<tr><td class=detailtit>Cantidad:</td><td class=detail>".RAD_numero($TMP_total,2)." &euro;</td></tr>";
	$TMP.="<tr><td class=detailtit>Forma de Pago:</td><td class=detail>".ucfirst($TMP_row[domiciliacion])."</td></tr>";
	$TMP.="</table>";
	return $TMP;
}
//--------------------------------------------------------------------------------------------------------------
function formPagoTPV($TMP_iddonativo) {
global $blocksoff, $SESSION_SID, $PHP_SELF, $V_dir, $V_mod, $RAD_dbi, $PHPSESSID, $V0_nombre, $V0_direccion, $V0_email, $V0_telefono, $V0_observaciones;

	if (_DEF_TPV_URLNOTIF=="") {
		die("<b><blink>Posibilidad de Pago DESACTIVADA</b></blink>");
		return "";
	}

	$TMP_res=sql_query("SELECT * FROM donativos WHERE iddonativo='$TMP_iddonativo'", $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);

	//if ($TMP_row[uniqid]=="") $TMP_row[uniqid]=$TMP_row[iddonativo]."_0";
	if ($TMP_row[uniqid]=="") $TMP_row[uniqid]=uniqid()."_0";
	$A_x=explode("_",$TMP_row[uniqid]);
	$A_x[1]++;
	$TMP_row[uniqid]=$A_x[0]."_".$A_x[1]; // incrementa el numero de version
	$Merchant_Order=$TMP_row[iddonativo];
	if (strlen($Merchant_Order)<4) $Merchant_Order="000".$Merchant_Order;
	$Merchant_OrderCECA=$A_x[1]."0".$Merchant_Order;
	$Merchant_OrderCECA=$TMP_row[iddonativo];
	$Merchant_Order.="_D".$A_x[1];
	sql_query("UPDATE donativos SET uniqid='".$TMP_row[uniqid]."' WHERE iddonativo='$TMP_iddonativo'", $RAD_dbi);

	$TMP_total=$TMP_row[cantidad];
	if (!is_numeric($TMP_total)) $TMP_total=$TMP_row[otracantidad];
	$TMP_total=str_replace(",",".",$TMP_total);
	$TMP_total=floor($TMP_total*100);

	$TMP_URLOK=_DEF_TPV_URLNOTIF."index.php?V_dir=$V_dir&V_mod=donar&iddonativo=$TMP_iddonativo&idses=".$TMP_row[uniqid]."&op=OK&PHPSESSID=$PHPSESSID";
	$TMP_URLKO=_DEF_TPV_URLNOTIF."index.php?V_dir=$V_dir&V_mod=donar&iddonativo=$TMP_iddonativo&idses=".$TMP_row[uniqid]."&op=KO&PHPSESSID=$PHPSESSID";
	$TMP_URLNOTIF=_DEF_TPV_URLNOTIF."index.php?V_dir=$V_dir&V_mod=donar&iddonativo=$TMP_iddonativo&idses=".$TMP_row[uniqid]."&op=NOTIF&PHPSESSID=$PHPSESSID";

	$TMP_URL_PAGO=_DEF_TPV_URLPAGO;
	$CLAVE_SECRETA=_DEF_TPV_CLAVE_SECRETA;
	$MERCHANT_CODE=_DEF_TPV_MERCHANT_CODE;
	$TERMINAL=_DEF_TPV_TERMINAL;
	$TRANSACTION_TYPE=_DEF_TPV_TRANSACTION_TYPE;
	$EXPONENTE="2";

	$MONEDA="978";

	if (strlen($TMP_iddonativo)<4) $TMP_iddonativo="0000".$TMP_iddonativo;

	// $MerchantSignature = SHA-1(Merchant_Amount + Merchant_Order + MerchantCode + Merchant_Currency + TRANSACTION_TYPE + URLNOTIF + CLAVE SECRETA)
	$MerchantSignature=strtoupper(sha1($TMP_total.$Merchant_Order.$MERCHANT_CODE.$MONEDA.$TRANSACTION_TYPE.$TMP_URLNOTIF.$CLAVE_SECRETA));

	//include_once("modules/".$V_dir."/sha_1.php");
	//$MerchantSignature2=strtoupper(sha_1($TMP_total.$TMP_iddonativo.$MERCHANT_CODE.$MONEDA.$CLAVE_SECRETA));

	if (_DEF_TPV_ACQUIRER_BIN!="" && _DEF_TPV_ACQUIRER_BIN!="_DEF_TPV_ACQUIRER_BIN") { // TPV Ceca
		$cadena=$CLAVE_SECRETA.$MERCHANT_CODE._DEF_TPV_ACQUIRER_BIN.$TERMINAL.$Merchant_OrderCECA.$TMP_total.$MONEDA.$EXPONENTE."SHA1".$TMP_URLOK.$TMP_URLKO;
		//Clave_encriptacion+MerchantID+AcquirerBIN+TerminalID+Num_operacion+Importe+TipoMoneda+Exponente+CifradoCadena SHA1+URL_OK+URL_NOK)
		$MerchantSignature=sha1($cadena);
		$TMP=_DEF_NLSDonativoConectantoSermepa.'
		<form target=_blank method="post" action="'.$TMP_URL_PAGO.'" name="pago" enctype="application/x-www-form-urlencoded">
		<input type="hidden" name="MerchantID" value="'.$MERCHANT_CODE.'">
		<input type="hidden" name="AcquirerBIN" value="'._DEF_TPV_ACQUIRER_BIN.'">
		<input type="hidden" name="TerminalID" value="'.$TERMINAL.'">
		<input type="hidden" name="Num_operacion" value="'.$Merchant_OrderCECA.'">
		<input type="hidden" name="Importe" value="'.$TMP_total.'">
		<input type="hidden" name="TipoMoneda" value="'.$MONEDA.'">
		<input type="hidden" name="Exponente" value="2">
		<input type="hidden" name="Idioma" value="1">
		<input type="hidden" name="Codigo_pedido" value="D'.$TMP_row[iddonativo].'">
		<input type="hidden" name="Pago_soportado" value="SSL">
		<input type="hidden" name="Firma" value="'.$MerchantSignature.'">
		<input type="hidden" name="URL_OK" value="'.$TMP_URLOK.'">
		<input type="hidden" name="URL_NOK" value="'.$TMP_URLKO.'">
		<input type="hidden" name="Descripcion" value="Donativo OnLine. '.cambiaAcentos(html_entity_decode(_DEF_SITENAME,ENT_NOQUOTES,"utf-8")).'">
		<input type="hidden" name="Cifrado" value="SHA1">
		';
	} else { // TPV Redsys
		$TMP=_DEF_NLSDonativoConectantoSermepa.'
		<form target=_blank method="post" action="'.$TMP_URL_PAGO.'" name="pago" enctype="application/x-www-form-urlencoded">
		<input type="hidden" name="Ds_Merchant_Amount" value="'.$TMP_total.'">
		<input type="hidden" name="Ds_Merchant_Currency" value="'.$MONEDA.'">
		<input type="hidden" name="Ds_Merchant_Order" value="'.$Merchant_Order.'">
		<input type="hidden" name="Ds_Merchant_MerchantName" value="'.cambiaAcentos(html_entity_decode(_DEF_SITENAME,ENT_NOQUOTES,"utf-8")).'">
		<input type="hidden" name="Ds_Merchant_ConsumerLanguage" value="001">
		<input type="hidden" name="Ds_Merchant_ProductDescription" value="Donativo OnLine. '.cambiaAcentos(html_entity_decode(_DEF_SITENAME,ENT_NOQUOTES,"utf-8")).'">
		<input type="hidden" name="Ds_Merchant_Titular" value="'.cambiaAcentos($TMP_row[nombre]." ".$TMP_row[apellidos].$TMP_row[razon]).'">

		<input type="hidden" name="Ds_Merchant_TransactionType" value="'.$TRANSACTION_TYPE.'">
		<input type="hidden" name="Ds_Merchant_MerchantCode" value="'.$MERCHANT_CODE.'">
		<input type="hidden" name="Ds_Merchant_MerchantURL" value="'.$TMP_URLNOTIF.'">
		<input type="hidden" name="Ds_Merchant_UrlOK" value="'.$TMP_URLOK.'">
		<input type="hidden" name="Ds_Merchant_UrlKO" value="'.$TMP_URLKO.'">
		<input type="hidden" name="Ds_Merchant_Terminal" value="'.$TERMINAL.'">
		<input type="hidden" name="Ds_Merchant_MerchantSignature" value="'.$MerchantSignature.'">
		<input type="hidden" name="charset" value="utf8">
		';
	}


	$TMP.='
		<input type="submit" class="boton" name="Envia" value=" Ingreso por Tarjeta ">
		</form>
<script>
document.forms.pago.submit();
</script>

';
	$blockoff="x";

	return $TMP;
}
//-------------------------------------------------------------------------------
function formPagoPaypal($TMP_iddonativo) {
// Formulario para pago por Paypal
global $RAD_dbi;

/* ---------------------------------------------------------------------------------------------------------
  Hay que cambiar direccion del formulario de pruebas es https://www.sandbox.paypal.com/cgi-bin/webscr 
     pero en pagos reales deberemos indicar https://www.paypal.com/cgi-bin/webscr
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

	$TMP_res=sql_query("SELECT * FROM donativos WHERE iddonativo='$TMP_iddonativo'", $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
	$TMP_total=$TMP_row[cantidad];
	if (!is_numeric($TMP_total)) $TMP_total=$TMP_row[otracantidad];
	$TMP_total=str_replace(",",".",$TMP_total);
	$TMP_total=$TMP_total*1;

	$TMP_dirtheme=getSessionVar("SESSION_theme");

$TMP="
<br><br><h2>Conectando con Paypal ...... </h2>
<form target=_blank name='formTpv' method='post' action='https://www.sandbox.paypal.com/cgi-bin/webscr'>
<input name='cmd' type='hidden' value='_cart'> 
<input name='upload' type='hidden' value='1'>
<input name='business' type='hidden' value='"._DEF_PAYPALBUSINESS."'>
<input name='shopping_url' type='hidden' value='"._DEF_URL."'>
<input name='currency_code' type='hidden' value='EUR'>
<input name='return' type='hidden' value='"._DEF_URL."index.php?V_dir=MSC&V_mod=donar&op=OK&iddonativo=".$TMP_iddonativo."&idses=".$TMP_row[uniqid]."&PHPSESSID=".$PHPSESSID."'>
<input name='cancel_return' type='hidden' value='"._DEF_URL."index.php?V_dir=MSC&V_mod=donar&op=KO&iddonativo=".$TMP_iddonativo."&idses=".$TMP_row[uniqid]."&PHPSESSID=".$PHPSESSID."'>
<input name='notify_url' type='hidden' value='"._DEF_URL."index.php?V_dir=MSC&V_mod=donar&op=NOTIF&iddonativo=".$TMP_iddonativo."&idses=".$TMP_row[uniqid]."&PHPSESSID=".$PHPSESSID."'>
<input name='rm' type='hidden' value='2'>
<input type='hidden' name='image_url' value='"._DEF_URL."themes/".$TMP_dirtheme."/logo.png'>
<input name='item_number_1' type='hidden' value='".$iddonativo."'>
<input name='item_name_1' type='hidden' value='Donativo'>
<input name='amount_1' type='hidden' value='".$TMP_total."'>
<input name='quantity_1' type='hidden' value='1'>
<input type='submit' name='Envia' value=' Ingreso por Paypal '>
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
