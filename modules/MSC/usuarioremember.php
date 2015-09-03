<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");
// Funciones de Acceso de Usuario. Requiere idart como parametro

global $RAD_dbi, $dbname, $blocksoff, $op;

$_REQUEST[email]=trim($_REQUEST[email]);

$TMP_ses_code=getSessionVar("securimage_code_value");
$TMP_req_code=$_REQUEST["captcha_code"];
if (strtoupper($TMP_req_code)!=strtoupper($TMP_ses_code) && $TMP_ses_code!="") {
	foreach($_REQUEST as $TMP_k=>$TMP_v) {
		if ($TMP_k=="captcha_code") error("Codigo de seguridad Incorrecto. Repita la operacion");
	}
}

if ($dbname=="") $dbname=_DEF_dbname;

include_once("header.php");

remember();

include_once("footer.php");
return;
//------------------------------------------------------------------------------------------------------------------------
function remember() {
	global $RAD_dbi, $V_dir, $idart;

	$TMP_lang=getSessionVar("SESSION_lang");

	if ($_REQUEST[conf]=="") {
		$TMP_res=sql_query("SELECT * FROM articulos WHERE id='$idart'", $RAD_dbi);
		$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
		if (trim($TMP_row["contenido_".$TMP_lang])!="") $TMP_row[contenido]=$TMP_row["contenido_".$TMP_lang];
		echo str_replace("< script","<script",$TMP_row[contenido]);
	} else {
		$TMP_res=sql_query("SELECT * FROM usuarios WHERE usuario=".converttosql($_REQUEST[email]), $RAD_dbi);
		$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
		if (!$TMP_row[idusuario]>0) {
			//$TMP_res=sql_query("SELECT * FROM usuarios WHERE email=".converttosql($_REQUEST[email]), $RAD_dbi);
			//$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
		}
		$subject=_DEF_NLSCreaClave;
		if ($TMP_row[idusuario]>0) {
			$TMP_clave=rand(10000,99999);
			$cmd="UPDATE usuarios SET clave='".md5($TMP_clave)."' WHERE usuario=".converttosql($_REQUEST[email])." or email=".converttosql($_REQUEST[email]);
			sql_query($cmd, $RAD_dbi);
			$msg=_DEF_NLSCrea4." ".$_REQUEST[email]." "._DEF_NLSCrea2." ".$TMP_clave;

			echo _DEF_NLSCrea5." ".$_REQUEST[email];
			//echo "<b>Nueva Clave creada. Se envian los datos de acceso a tu E-mail.</b>";
			include_once("modules/".$V_dir."/lib.Email.php");
			//F_SendMail($from, $fromname, $to, $bcc, $subject, $body, $altbody="", $adjuntos="", $html=false);
			F_SendMail(_DEF_ADMINMAIL, "", $_REQUEST[email], "", $subject, $msg, "", "", true);
		} else {
			$subject=_DEF_NLSRecuerdaClave;
			$cmd="SELECT * from GIE_contactos where email=".converttosql($_REQUEST[email])."";
			$TMP_res=sql_query($cmd, $RAD_dbi);
			$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
			$TMP_clave=$TMP_row[clave];
			if (!$TMP_row[idcontacto]>0) {
				echo _DEF_NLS_UsuarioNoExiste." ".$_REQUEST[email];
			} else {
				$msg=_DEF_NLSRecuerda4." ".$_REQUEST[email]." "._DEF_NLSRecuerda2." ".$TMP_clave;
				echo _DEF_NLSRecuerda5." ".$_REQUEST[email];
				include_once("modules/".$V_dir."/lib.Email.php");
				F_SendMail(_DEF_ADMINMAIL, "", $_REQUEST[email], "", $subject, $msg, "", "", true);
			}
                }
	}
	echo "</ul>";
}
?>
