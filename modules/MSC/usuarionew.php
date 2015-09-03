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

newuser();

include_once("footer.php");
return;
//------------------------------------------------------------------------------------------------------------------------
function newuser() {
	global $RAD_dbi, $idart;

	$TMP_lang=getSessionVar("SESSION_lang");

	$TMP_res=sql_query("SELECT * FROM articulos WHERE id='$idart'", $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
	if (trim($TMP_row["contenido_".$TMP_lang])!="") $TMP_row[contenido]=$TMP_row["contenido_".$TMP_lang];
	global $_SESSION;
	foreach($_SESSION as $TMP_k=>$TMP_v) {
		if (substr($TMP_k,0,14)=="SESSION_flogin") {
			$TMP_k=substr($TMP_k,15);
			//echo $TMP_k."=".$TMP_v."<br>";
			if ($TMP_k=="observaciones") $TMP_row[contenido]=str_replace('</textarea>',$TMP_v.'</textarea>',$TMP_row[contenido]);
			$TMP_row[contenido]=str_replace('name="'.$TMP_k.'"','name="'.$TMP_k.'" value="'.$TMP_v.'"',$TMP_row[contenido]);
			$TMP_row[contenido]=str_replace('<! '.$TMP_k.' >',''.$TMP_v.'',$TMP_row[contenido]);
		}
	}
	if ($_REQUEST[conf]=="") {
		echo str_replace("< script","<script",$TMP_row[contenido]);
	} else {
		foreach($_REQUEST as $TMP_k=>$TMP_v) setSessionVar("SESSION_flogin_".$TMP_k,$TMP_v);
		$TMP_cont=0;
		$TMP_res=sql_query("SELECT * FROM usuarios where usuario=".converttosql($_REQUEST[nombre]), $RAD_dbi);
		$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
		if ($TMP_row[idusuario]>0) {
			echo _DEF_NLSUsuarioYaExiste;
		} else { // Se crea Usuario si no existen
			$TMP_res=sql_query("SELECT * FROM usuarios WHERE usuario=".converttosql($_REQUEST[email]), $RAD_dbi);
			$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
			if ($TMP_row[idusuario]>0) {
				echo _DEF_NLSUsuarioYaExiste;
			} else {
				$TMP_clave=rand(10000,99999);
				$cmd="INSERT INTO usuarios SET usuario=".converttosql($_REQUEST[email]).", nombre=".converttosql($_REQUEST[nombre]).", email=".converttosql($_REQUEST[email]).", telefono=".converttosql($_REQUEST[telefono]).", clave='$TMP_clave', fechaalta='".date("Y-m-d")."', portada='', perfil=',cli,' ";
				sql_query($cmd, $RAD_dbi);
				$mailheaders = "From: "._DEF_ADMINMAIL." \n";
				//$msg="Se ha creado tu Usuario con Codigo ".$_REQUEST[email]." y Clave ".$TMP_clave;
				//mail($_REQUEST[email], "Su Usuario para "._DEF_URL, $msg, $mailheaders);
				$msg=_DEF_NLSCrea1.$_REQUEST[email]." "._DEF_NLSCrea2.$TMP_clave."\n\n";
				$msg.=_DEF_NLSCreaCuerpo;

				$mailheaders.="Mime-Version: 1.0 \r\n";
				$mailheaders.="Content-Type: text/plain; charset=ISO-8859-1\r\n";
				//$mailheaders.="Content-Type: text/plain;charset=utf-8\r\n";
				//$mailheaders.="Content-Transfer-Encoding: 8bit\r\n";

				//$mailheaders=$mailheaders."Mime-Version: 1.0\n";
				//$mailheaders=$mailheaders."Content-Type: text/html\n";
				//$msg=str_replace("\n","<br>\n",htmlentities($msg));

				mail($_REQUEST[email], _DEF_NLSCreaAsunto, $msg, $mailheaders);
				//echo "<b>Usuario creado. Se envian los datos de acceso a tu E-mail.</b>";
				echo _DEF_NLSCrea6;
			}
		}
		enviaForm();
	}
	echo "</ul>";
}
?>
