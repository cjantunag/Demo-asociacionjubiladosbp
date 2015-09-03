<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");
// Crea contacto nuevo a partir de email (si no existe ya)

global $RAD_dbi, $dbname, $blocksoff, $op;

$_REQUEST[email]=trim($_REQUEST[email]);

$TMP_ses_code=getSessionVar("securimage_code_value");
$TMP_req_code=$_REQUEST["captcha_code"];
if (strtoupper($TMP_req_code)!=strtoupper($TMP_ses_code) && $TMP_ses_code!="") {
	foreach($_REQUEST as $TMP_k=>$TMP_v) {
		if ($TMP_k=="captcha_code") error(_DEF_NLSSecImgErr);
	}
}

if ($dbname=="") $dbname=_DEF_dbname;

include_once("header.php");

if ($id!="") {
	$TMP_res=sql_query("SELECT * FROM GIE_contactos where uniqid=".converttosql($id), $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
	if ($TMP_row[idcontacto]>0) {
		sql_query("UPDATE GIE_contactos set recibirboletin='1' where uniqid=".converttosql($id), $RAD_dbi);
		echo "<b>"._DEF_NLSContactoActivo."</b>";
	}
} else {
	newcontact();
}

include_once("footer.php");
return;
//------------------------------------------------------------------------------------------------------------------------
function newcontact() {
	global $RAD_dbi, $PHP_SELF, $V_dir, $idart;

	$TMP_lang=getSessionVar("SESSION_lang");

	$TMP_res=sql_query("SELECT * FROM articulos WHERE id='$idart'", $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
	if (trim($TMP_row["contenido_".$TMP_lang])!="") $TMP_row[contenido]=$TMP_row["contenido_".$TMP_lang];
	$TMP_cont=0;
	$TMP_uniqid=uniqid();
	$TMP_res=sql_query("SELECT * FROM GIE_contactos where email=".converttosql($_REQUEST[email]), $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
	if ($TMP_row[idcontacto]>0) {
		echo "<b>"._DEF_NLSContactoYaExiste."</b>";
	} else { // Se crea Contacto
		$cmd="INSERT INTO GIE_contactos SET uniqid='$TMP_uniqid', recibirboletin='2', nombre=".converttosql($_REQUEST[email]).", email=".converttosql($_REQUEST[email]).", fechaalta='".date("Y-m-d")."'";
		sql_query($cmd, $RAD_dbi);
		$msg=_DEF_NLSCreaContacto1."\n\n".$_REQUEST[email]."\n\n";
		echo "<b>".str_replace("\n","<br>",$msg._DEF_NLSCreaContactoCuerpo)."</b>";
		$msg.=_DEF_NLSCreaContactoActiva._DEF_URL.$PHP_SELF."?V_dir=MSC&V_mod=contactonuevo&lang=".$TMP_lang."&id=".$TMP_uniqid."\n";
		$msg.=_DEF_NLSCreaContactoCuerpo;
		$msg=str_replace("\n","<br>",$msg);

		include_once("modules/".$V_dir."/lib.Email.php");
		//F_SendMail($from, $fromname, $to, $bcc, $subject, $body, $altbody="", $adjuntos="", $html=false);
		F_SendMail(_DEF_ADMINMAIL,_DEF_ADMINMAILNAME,$_REQUEST[email], "", _DEF_NLSCreaContactoAsunto, $msg, "", "", true);
	}
}
?>
