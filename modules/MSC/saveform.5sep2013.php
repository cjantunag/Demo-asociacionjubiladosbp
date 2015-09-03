<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");
// Graba datos de formulario en tabla
// El formulario de entrada es el articulo "idin" y el mostrado a la salida es "idout". La tabla es "tabla"

global $RAD_dbi, $dbname, $blocksoff, $op, $V_dir, $V_idmod, $id;
if ($dbname=="") $dbname=_DEF_dbname;

$TMP_lang=getSessionVar("SESSION_lang");

$TMP_ses_code=getSessionVar("securimage_code_value");
$TMP_req_code=$_REQUEST["captcha_code"];
if (strtoupper($TMP_req_code)!=strtoupper($TMP_ses_code) && $TMP_ses_code!="") {
	foreach($_REQUEST as $TMP_k=>$TMP_v) {
		if ($TMP_k=="captcha_code") error(_DEF_NLSSecImgErr);
	}
}

$TMP_dirtheme=getSessionVar("SESSION_theme");
if (file_exists("files/"._DEF_dbname."/reload.png")) $TMP_ico="files/"._DEF_dbname."/reload.png";
else if (file_exists("themes/".$TMP_dirtheme."/reload.png")) $TMP_ico="themes/".$TMP_dirtheme."/reload.png";
else if (file_exists("files/"._DEF_dbname."/reload.gif")) $TMP_ico="files/"._DEF_dbname."/reload.gif";
else if (file_exists("themes/".$TMP_dirtheme."/reload.gif")) $TMP_ico="themes/".$TMP_dirtheme."/reload.gif";
else $TMP_ico="images/reload.gif";

$TMP_secu="
<img id='captcha' src='modules/securimage/securimage_show.php' alt='Imagen CAPTCHA' style='vertical-align:middle;'>
<a href='#' onclick=\"document.getElementById('captcha').src='modules/securimage/securimage_show.php?'+Math.random();return false\"><img src='".$TMP_ico."' title='Recarga Imagen' alt='Recarga Imagen' border=0 style='vertical-align:middle;'></a>
<input type='text' name='captcha_code' size='10' maxlength='6' onBlur='xajax_checkCaptcha(this.value);'> <font style='vertical-align:middle;' id='idcaptchahelp'> &nbsp; </font>
";

if ($dbname=="") $dbname=_DEF_dbname;

include_once("header.php");

if ($save=="" && $op=="" && $acepto=="") {
	$id=$idin;
} else {
	//foreach($_REQUEST as $TMP_k=>$TMP_v) echo $TMP_k."=".$TMP_v."<br>";
	$id=$idout;
	include_once("modules/".$V_dir."/lib.savereg.php");
	savereg($tabla,$_REQUEST[asunto]);
}

$TMP_res=sql_query("SELECT * FROM articulos where id='$id'", $RAD_dbi);
$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
$TMP_row[contenido]=str_replace("<!-- securimage -->",$TMP_secu,$TMP_row[contenido]);
$TMP_row[contenido]=str_replace("<!-- nombre -->",$_REQUEST[nombre],$TMP_row[contenido]);
$TMP_row[contenido]=str_replace("<!-- V_idmod -->",$V_idmod,$TMP_row[contenido]);
echo $TMP_row[contenido];

echo "\n\n\n";
include_once("images/xajax/xajax.inc.php");
$xajax = new xajax();
$xajax->registerFunction("checkCaptcha");
$xajax->processRequests();
$xajax->printJavascript();
echo "\n\n\n";

include_once("footer.php");
return;
//-----------------------------------------------------------------------------------------------------------------------------
//if (!function_exists("checkCaptcha")) { 
	function checkCaptcha($captcha_code) {

		$TMP_ses_code=getSessionVar("securimage_code_value");
		if (strtoupper($captcha_code)!=strtoupper($TMP_ses_code) && $TMP_ses_code!="") {
			$TMP_result="0";
			$TMP_msg=' <img src="themes/MSC/ico_error.gif" style="vertical-align:-2px;">';
		} else {
			$TMP_result="1";
			$TMP_msg=' <img src="themes/MSC/ico_ok.gif" style="vertical-align:-2px;">';
		}

		ob_end_clean();
		$objResponse = new xajaxResponse();
		$objResponse->outputEntitiesOn();
		$objResponse->addScript("captchavalid('".$TMP_result."');");
		$objResponse->addAssign("idcaptchahelp","innerHTML",$TMP_msg);
		return $objResponse;
	}
//}
?>
