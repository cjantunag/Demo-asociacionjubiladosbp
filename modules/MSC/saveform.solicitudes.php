<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");
// Manda correo a solicitante de solicitudes

global $RAD_dbi, $PHP_SELF, $V_dir, $dbname, $idemail, $_REQUEST;

// debe crear un usuario en htpasswd y mandarlo por correo
$_REQUEST[email]=trim(str_replace("'","",$_REQUEST[email]));

$msg=RAD_lookup("articulos","contenido","id",$idemail);
$asunto=RAD_lookup("articulos","tema","id",$idemail);
$asunto="Datos de Acceso a Mas Social CONECTA y Proximos Pasos";
$TMP_clave=rand(10000,99999);

foreach($_REQUEST as $TMP_k=>$TMP_v) {
//   echo $TMP_k."=".$TMP_v."<br>";
   if (is_array($TMP_v)) {
	$TMP_vv="";
	foreach($TMP_v as $TMP_k2=>$TMP_v2) {
		if ($TMP_vv!="") $TMP_vv.=",";
		$TMP_vv.=$TMP_v2;
	}
	$_REQUEST[$TMP_k]=$TMP_v;
   }
}

sql_query("UPDATE solicitudes set clave='$TMP_clave' where email='".$_REQUEST[email]."'", $RAD_dbi);
system("/usr/bin/htpasswd -b /aplica/MASSOCIALCONECTA/privado/htpasswdMSC ".$_REQUEST[email]." ".$TMP_clave);

$msg=str_replace("<!-- nombre -->",$_REQUEST[contacto],$msg);
$msg=str_replace("<!-- usuario -->",trim($_REQUEST[email]),$msg);

$msg=str_replace("<!-- clave -->",$TMP_clave,$msg);
include_once("modules/".$V_dir."/lib.Email.php");
F_SendMail(_DEF_ADMINMAIL,_DEF_ADMINMAILNAME,$_REQUEST[email], "", $asunto, $msg, $msg, "", true);
return "";
?>
