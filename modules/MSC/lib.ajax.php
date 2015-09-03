<?php 
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

global $TMP_secu;

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

include_once("modules/".$V_dir."/func.IBAN.php");

echo "\n\n\n";
include_once("images/xajax/xajax.inc.php");
$xajax = new xajax();
//$xajax->setCharEncoding("iso-8859-1");
$xajax->setCharEncoding("utf-8");
$xajax->registerFunction("checkCaptcha");
$xajax->registerFunction("checkUser");
$xajax->registerFunction("checkBanco");
$xajax->registerFunction("checkIBAN");
$xajax->registerFunction("checkSWIFT");
$xajax->processRequests();
$xajax->printJavascript();
echo "\n\n\n";

//-----------------------------------------------------------------------------------------------------------------------------
//if (!function_exists("checkIBAN")) {
        function checkIBAN($IBAN) {
		global $RAD_dbi;
                ob_end_clean();
                $objResponse = new xajaxResponse();
                $objResponse->outputEntitiesOn();
		$IBAN=str_replace(" ","",$IBAN);
		$IBAN=str_replace("-","",$IBAN);

		$dc=substr($IBAN,12,2);
		$calcdc=CalcularDC(substr($IBAN,4));
		if ($calcdc!=$dc && $dc!="") {
                	$TMP_script="cuentavalidIBAN(false);";
			$TMP_script.="document.F.IBANnok.style.display='block'; document.F.IBANok.style.display='none';";
		} else if (!validaIBAN($IBAN)) {
                	$TMP_script="cuentavalidIBAN(false);";
			$TMP_script.="document.F.IBANnok.style.display='block'; document.F.IBANok.style.display='none';";
		} else {
                	$TMP_script="cuentavalidIBAN(true); ";
			$TMP_script.="document.F.IBANok.style.display='block'; document.F.IBANnok.style.display='none';";
		}

		$objResponse->addScript($TMP_script);
                return $objResponse;
	}
//}
//-----------------------------------------------------------------------------------------------------------------------------
//if (!function_exists("checkSWIFT")) {
        function checkSWIFT($SWIFT) {
		global $RAD_dbi;
                ob_end_clean();
                $objResponse = new xajaxResponse();
                $objResponse->outputEntitiesOn();

		if (strlen($SWIFT)<4) {
                	$TMP_script="cuentavalidSWIFT(false); ";
			$TMP_script.="document.F.SWIFTnok.style.display='block'; document.F.SWIFTok.style.display='none';";
		} else {
                	$TMP_script="cuentavalidSWIFT(true); ";
			$TMP_script.="document.F.SWIFTok.style.display='block'; document.F.SWIFTnok.style.display='none';";
		}

		$objResponse->addScript($TMP_script);
                return $objResponse;
	}
//}
//-----------------------------------------------------------------------------------------------------------------------------
//if (!function_exists("checkBanco")) {
        function checkBanco($banco,$oficina,$dc,$cuenta) {
		global $RAD_dbi;
                ob_end_clean();
                $objResponse = new xajaxResponse();
                $objResponse->outputEntitiesOn();

		$calcdc=CalcularDC($banco.$oficina.$dc.$cuenta);
		if ($calcdc==$dc && $dc!="") {
                	$TMP_script="cuentavalid(true); ";
			$TMP_script.="document.F.bancook.style.display='block'; document.F.banconok.style.display='none';";
		} else {
                	$TMP_script="cuentavalid(false); ";
			$TMP_script.="document.F.banconok.style.display='block'; document.F.bancook.style.display='none';";
		}

		$objResponse->addScript($TMP_script);
                return $objResponse;
	}
//}
//-----------------------------------------------------------------------------------------------------------------------------
//if (!function_exists("checkUser")) {
        function checkUser($email,$clave) {
		global $RAD_dbi, $V_mod;
                ob_end_clean();
                $objResponse = new xajaxResponse();
                $objResponse->outputEntitiesOn();
		$A_tablas=array("donativos","firmas","socios","voluntarios");
		//$A_tablas=array("firmas");
		$TMP_cont=0; $TMP_script=""; $TMP_vals="";
		$cmd="SELECT * from GIE_contactos where email=".converttosql($email)." and clave=".converttosql($clave);
		$TMP_res=sql_query($cmd, $RAD_dbi);
		$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
		foreach($TMP_row as $TMP_idx=>$TMP_val) {
			if ($TMP_idx==$TMP_k) {
				$TMP_k++;
				continue;
			}
			$A_f[$TMP_idx]=$TMP_val;
		}
		$A_lc=",poblacion,pais,telefono,sexo,idioma,domiciliacion,recibiravisos,recibirmemoria,recibirboletin,entidad,cuenta,dc,oficina,razon,tipoidentificacion,identificacion,condicion,profesion,dianacimiento,mesnacimiento,anonacimiento,cif,email,nombre,apellidos,tipovia,via,piso,codpostal,provincia,recibiravisos,piso,recibirmemoria,recibirinfo,recibirboletin,";
		$A_lc=",poblacion,pais,telefono,sexo,idioma,domiciliacion,razon,cif,email,nombre,apellidos,tipovia,via,piso,codpostal,provincia,piso,";
		$A_lc=",razon,cif,email,nombre,apellidos,tipovia,via,piso,codpostal,provincia,piso,";
		if (count($A_f)>0) foreach($A_f as $TMP_idx=>$TMP_val) {
			if ($TMP_idx=="particular") continue;
			if ($TMP_idx=="idaccion") continue;
			if ($TMP_idx=="op") continue;
			if ($V_mod=="cesta"&&$TMP_idx=="domiciliacion") {
				if ($TMP_val=="domiciliacion") $TMP_val="tarjeta";
				//if ($TMP_val=="paypal") $TMP_script.="\ndocument.F.domiciliacion[0].checked=true;\n";
				//if ($TMP_val=="tarjeta") $TMP_script.="\ndocument.F.domiciliacion[1].checked=true;\n";
			}
			if ($V_mod=="cesta"&&$TMP_idx=="domiciliacion") continue;
			if ($V_mod=="cesta"&&$TMP_idx=="recibirinfo") continue;
			//if (!ereg(",".$TMP_idx.",",$A_lc)) continue;
			$TMP_vals.=$TMP_idx."=";
			$TMP_vals.=$TMP_val.".";
//if ($TMP_idx!="cantidad") continue;
			if ($TMP_idx=="provincia") {
				$TMP_cont=0;
				$TMP_res2=sql_query("SELECT * FROM provincias order by provincia", $RAD_dbi);
				while($TMP_row2=sql_fetch_array($TMP_res2, $RAD_dbi)) {
					$TMP_cont++;
					if ($TMP_row2[provincia]==$TMP_val) break;
				}
				$TMP_script.="\nif (document.F.".$TMP_idx.") document.F.".$TMP_idx.".selectedIndex=".$TMP_cont.";\n";
				continue;
			}
			$TMP_script.="\nif (document.F.".$TMP_idx.") {\n ";
			$TMP_script.="if (document.F.".$TMP_idx.".type=='hidden') document.F.".$TMP_idx.".value='".$TMP_val."'; ";
			$TMP_script.="if (document.F.".$TMP_idx.".type=='text') document.F.".$TMP_idx.".value='".$TMP_val."'; ";
			$TMP_script.="if (document.F.".$TMP_idx.".type=='password') document.F.".$TMP_idx.".value='".$TMP_val."'; ";
			if ($TMP_val=="1") $TMP_script.="if (document.F.".$TMP_idx.".type=='checkbox') document.F.".$TMP_idx.".checked=true;\n";
			else $TMP_script.="if (document.F.".$TMP_idx.".type=='checkbox') document.F.".$TMP_idx.".checked=false;\n";
			if (substr($TMP_val,0,4)=="Espa") $TMP_script.="if (document.F.".$TMP_idx.".type=='select-one') document.F.".$TMP_idx.".selectedIndex=63;\n";
			else if ($TMP_idx!="email") $TMP_script.="if (document.F.".$TMP_idx.".type=='select-one') for(i=0; i<document.F.".$TMP_idx.".length; i++) { if(document.F.".$TMP_idx."[i].value=='".$TMP_val."') document.F.".$TMP_idx.".selectedIndex=i; }\n";
			$TMP_script.="if (document.F.".$TMP_idx.".length>1) for(i=0; i<document.F.".$TMP_idx.".length; i++) { if(document.F.".$TMP_idx."[i].value=='".$TMP_val."') document.F.".$TMP_idx."[i].checked=true; }\n";
			//else if ($TMP_idx!="email") $TMP_script.="if (document.F.".$TMP_idx.".type=='select-one') for(i=0; i<document.F.".$TMP_idx.".length; i++) { if(document.F.".$TMP_idx."[i].value=='".$TMP_val."') document.F.".$TMP_idx."[i].selected=true; }\n";
			if ($TMP_idx=="pais") $TMP_script.="checkF('pais',document.F.pais[document.F.pais.selectedIndex].value);\n";
			if ($TMP_idx=="domiciliacion") $TMP_script.="if (document.F.".$TMP_idx.".type!='hidden') checkF('domiciliacion','".$TMP_val."');\n";
			if ($TMP_idx=="cuenta") $TMP_script.="xajax_checkBanco(document.F.entidad.value,document.F.oficina.value,document.F.dc.value,document.F.cuenta.value);\n";
			$TMP_script.=" }\n";
		}
                if ($TMP_row[email]=="") {
			$TMP_sqls=str_replace("'","",$cmd);
			//$objResponse->addScript("alert('".$TMP_sqls."');");
			$objResponse->addScript("alert('"._DEF_NLSNoUser."');");
		} else {
			$TMP_script=UTF_to_Unicode($TMP_script);
			//$objResponse->addScript("alert('".$V_mod."*".$TMP_vals."');");
			$objResponse->addScript($TMP_script);
			$TMP_script=str_replace("'","`",$TMP_script);
			$TMP_script=str_replace("\n","   ",$TMP_script);
			//$objResponse->addScript("alert('".$TMP_script."');");
		}
                return $objResponse;
	}
//}
//-----------------------------------------------------------------------------------------------------------------------------
//if (!function_exists("checkCaptcha")) {
        function checkCaptcha($captcha_code) {
		$TMP_dirtheme=getSessionVar("SESSION_theme");

                $TMP_ses_code=getSessionVar("securimage_code_value");
                if (strtoupper($captcha_code)!=strtoupper($TMP_ses_code) && $TMP_ses_code!="") {
                        $TMP_result="0";
                        $TMP_msg=' <img src="themes/'.$TMP_dirtheme.'/ico_error.gif" style="vertical-align:-2px;">';
                } else {
                        $TMP_result="1";
                        $TMP_msg=' <img src="themes/'.$TMP_dirtheme.'/ico_ok.gif" style="vertical-align:-2px;">';
                }

                ob_end_clean();
                $objResponse = new xajaxResponse();
                $objResponse->outputEntitiesOn();
                $objResponse->addScript("captchavalid('".$TMP_result."');");
                $objResponse->addAssign("idcaptchahelp","innerHTML",$TMP_msg);
                return $objResponse;
        }
//}

//-----------------------------------------------------------------------------------------------------------------------------
function CalcularDC($CCC) {
	if ($CCC=="") return "";
//	echo "CCC=$CCC.<br>\n";
	$tmp=substr($CCC,0,8);
	$PrimerDC=substr($tmp,7,1)*6+substr($tmp,6,1)*3+substr($tmp,5,1)*7+substr($tmp,4,1)*9+substr($tmp,3,1)*10+
		substr($tmp,2,1)*5+substr($tmp,1,1)*8+substr($tmp,0,1)*4;
	$PrimerDC=(($PrimerDC-(floor($PrimerDC/11))*11)-11)*(-1);
	if ($PrimerDC==11) $PrimerDC=0;
	if ($PrimerDC==10) $PrimerDC=1;
	$tmp=substr($CCC,10,10);
	$SegundoDC=substr($tmp,9,1)*6+substr($tmp,8,1)*3+substr($tmp,7,1)*7+substr($tmp,6,1)*9+substr($tmp,5,1)*10+
		substr($tmp,4,1)*5+substr($tmp,3,1)*8+substr($tmp,2,1)*4+substr($tmp,1,1)*2+substr($tmp,0,1)*1;
	$SegundoDC=(($SegundoDC-(floor($SegundoDC/11))*11)-11)*(-1);
	if ($SegundoDC==11) $SegundoDC=0;
	if ($SegundoDC==10) $SegundoDC=1;
//	echo "<b>DC=".$PrimerDC.$SegundoDC."</b><br>\n";
	return $PrimerDC.$SegundoDC;
}

//-----------------------------------------------------------------------------------------------------------------------------
function UTF_to_Unicode($input) {
/* 
&		\u0022
<		\u003C
>		\u003E
&nbsp;	\u00A0
“		\u0022
‘		\u0027
©		\u00A9
€		\u20AC
*/
	$A_x=array(":"=>"\u003A", "?"=>"\u003F", "¿"=>"\u00BF", "ª"=>"\u00AA", "º"=>"\u00BA", "Á"=>"\u00C1", "á"=>"\u00E1", "É"=>"\u00C9", "é"=>"\u00E9", "Í"=>"\u00CD", "í"=>"\u00ED", "Ó"=>"\u00D3", "ó"=>"\u00F3", "Ú"=>"\u00DA", "ú"=>"\u00FA", "Ü"=>"\u00DC", "ü"=>"\u00FC", "Ñ"=>"\u00D1", "ñ"=>"\u00F1" );
	//$input=htmlspecialchars_decode($input);
	foreach($A_x as $TMP_char=>$TMP_uni) {
		$input=str_replace($TMP_char, $TMP_uni, $input);
		//$input=str_replace(utf8_decode($TMP_char), $TMP_uni, $input);
	}
	return $input;
}

?>
