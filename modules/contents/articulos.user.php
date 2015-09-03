<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");
// Muestra un Articulo u otro dependiendo de si es Usuario o No iduser o idnouser

include_once("header.php");

global $RAD_dbi, $dbname;
if ($dbname=="") $dbname=_DEF_dbname;


if (is_user()) $id=$iduser;
else $id=$idnouser;

$TMP_res=sql_query("SELECT * FROM articulos WHERE id='$id'", $RAD_dbi);
$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
echo str_replace("< script","<script",$TMP_row[contenido]);

include_once("footer.php");

//--------------------------------------------------------------------------------------------------------------------------
function enviaForm() {
	global $nombrecontrato, $destinatario, $email, $idmunicipio, $subject;

	$to = _DEF_ADMINMAIL;
	if ($destinatario!="") $to = $destinatario._DEF_MAILBASE;
	if ($idmunicipio>0) {
		$TMP_result=sql_query("SELECT * FROM EYD_oficinas WHERE idmunicipio='".$idmunicipio."'",$RAD_dbi);
		$TMP_row=sql_fetch_array($TMP_result);
		if (trim($TMP_row["email"])!="") $to=trim($TMP_row[email]);
	}
//	if ($email!="") $mailheaders = "From: ".$email." <".$email."> \n";
	if ($email!="") $mailheaders = "From: ".$email." \n";
	else $mailheaders = "From: ".$to." \n";

	if ($subject=="") $subject="MailForm "._DEF_URL;
	if ($_REQUEST["op"]=="cambiodomic") $subject="Cambio de Domiciliacion";
	if ($_REQUEST["op"]=="ultimalectura") $subject="Notificacion Ultima Lectura";
	$msg="FECHA = ".date("Y-m-d").".\r\n";
	$msg.="DATOS_CONTRATO = ".$nombrecontrato.".\r\n";
	foreach ($_REQUEST as $TMP_key=>$TMP_val) {
		if ($TMP_key=="V_dir"||$TMP_key=="V_mod"||$TMP_key=="submit"||$TMP_key=="blocksoff") continue;
		if ($TMP_key=="V_idmod"||$TMP_key=="conf"||$TMP_key=="op") continue;
		$msg.=strtoupper($TMP_key)." = ".$TMP_val.".\r\n";
	}
	mail($to, $subject, $msg, $mailheaders);
	echo "<br><br><b><center>"._DEF_NLSMessageSent."<BR>"._DEF_NLSThanks." </center></b><br>";
}
?>
