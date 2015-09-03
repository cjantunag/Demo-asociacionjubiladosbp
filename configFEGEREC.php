<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}
session_start();

global $HTTP_SESSION_VARS, $PHP_SELF, $V_dir, $_SESSION;
if(!isset($HTTP_SESSION_VARS)) $HTTP_SESSION_VARS=& $_SESSION;
if (count($_SESSION)>0) foreach ($_SESSION as $TMP_key=>$TMP_val) { $HTTP_SESSION_VARS[$TMP_key]=$TMP_val; }
$TMP_theme=$HTTP_SESSION_VARS["SESSION_theme"];

//define(_DEF_html5,"1"); // use HTML5 fields
if (eregi("Chrom",$_SERVER[HTTP_USER_AGENT])) define(_DEF_html5,"1"); // use HTML5 fields

define('_DEF_DIR_ROOT',"");

global $RAD_decimalsep, $RAD_milsep, $RAD_rellenaDecimales;
$RAD_decimalsep=",";
$RAD_milsep=".";
$RAD_rellenaDecimales="*";
$RAD_plistnonull="x";

define(_DEF_TypeDate, "number");

//define(_DEF_dbreadonly,"1");	// 0=read/write DB, 1=read only DB

######################################################################
# Config (constants, not variables than could be modified)
# _DEF_dbhost:  SQL Database Hostname
# _DEF_dbuname: SQL Username
# _DEF_dbpass:  SQL Password
# _DEF_dbname:  SQL Database Name
# _DEF_dbtype:  Your Database Server type. Supported servers are:
#               MySQL, mSQL, PostgreSQL, PostgreSQL_local, ODBC,
#               ODBC_Adabas, Interbase, and Sybase (case sensitive)
######################################################################
define(_DEF_dbhost,"localhost");
define(_DEF_dbuname,"root");
define(_DEF_dbpass,"");
define(_DEF_dbname,"FEGEREC");
define(_DEF_dbtype,"MySQL");
define(_DEF_appname,"MASSOCIAL");
define(_DEF_TABLE_PREFIX,"GIE_");
define(_DEF_POPUP_MARGIN,"SUBMODAL");
define(_NOSHOW_RESTRICTEDAREA,"0");
define(_DEF_MaxRegsPrint,"1000");
define(_DEF_PRINT_SHORTCUT,"1");
define(_DEF_VALIDATE_URL,"0");
define(_DEF_INDEX,"/index.php");
define(_CHARSET,"UTF-8");
//define(_CHARSET,"ISO-8859-1"); // UTF-8 ISO-8859-1
define(_DEF_maxInactivityTime,"36000"); // maxInactivityTime in seconds (10 hours)
if ($V_mod=="" && $V_idmod=="") define("_DEF_noCalendar","x"); // quita javascript de calendario de portada para que funcione fullcalendar en portada
if ($V_mod=="fullcalendarmod") define("_DEF_noCalendar","x"); // quita javascript de calendario de portada para que funcione fullcalendar en portada
######################################################################
# General Site Configuration
# _DEF_SITENAME:  Your Site Name
# _DEF_THEME:     Default Theme for your site (See /themes directory for the complete list, case sensitive!)
# _DEF_URL:       Complete URL for your site (Do not put / at end)
# _DEF_ADMINMAIL: Site Administrator's Email
# _DEF_FOOT:      Messages for all footer pages (Can include HTML code)
# _DEF_DIRBASE:	The base directory where RAD resides
######################################################################
define(_DEF_DOCTYPE,'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">');
//define(_DEF_DOCTYPE,'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">');
define(_DEF_HTML,"<html xmlns=\"http://www.w3.org/1999/xhtml\">\n");
if ($HTTP_SESSION_VARS["SESSION_user"]!="") {
	define(_DEF_noHeadJavascript,""); // elimina el codigo javascript de RAD
} else {
	define(_DEF_noHeadJavascript,"x"); // elimina el codigo javascript de RAD
}

define(_DEF_SITENAMESHORT,"FEGEREC");
define(_DEF_THEME,"MSC");
define(_DEF_COLUMNS,"1");
if ($HTTPS_SERVER[HTTPS]!="") $TMP_URL="https://";
else $TMP_URL="http://";
$TMP_URL.=$GLOBALS["HTTP_HOST"].dirname($PHP_SELF);
if (dirname($PHP_SELF)!="/") $TMP_URL.="/";
define(_DEF_URL,$TMP_URL);
define(_DEF_URL_SUBBROWSE,_DEF_URL);
define(_DEF_DIRBASE,"/aplica/FEGEREC/");
define(_DEF_ADMINMAIL,"webfegerec@gmail.com");
define(_DEF_ADMINMAILNAME,"Fegerec");
//define(_DEF_ADMINMAIL,"jtaibos@gmail.com");
if ($newlang!="") $HTTP_SESSION_VARS["SESSION_lang"]=$newlang;
if ($HTTP_SESSION_VARS["SESSION_lang"]=="galician" || $HTTP_SESSION_VARS["SESSION_lang"]=="" || $newlang=="galician") {
	define(_DEF_SITENAME,"Federaci&oacute;n Galega de Enfermidades Raras e Cr&oacute;nicas");
	define(_DEF_FOOT,'      <ul>
        <li class="logo"> <a href="#"><img src="themes/MSC/cierre_fegerec.gif" width="271" height="68" /></a>
          <p>Centro Municipal de Enfermos Cr&oacute;nicos <br />
                Domingo Garc&iacute;a Sabell <br />
                Praza Esteban Lareo Bloque 17 Soto   |   15008 A Coru&ntilde;a<br />
            Tel.: 981 234 651 - 691 011 855   |   correo-e: <a href="mailto:fegerec@cofc.es">fegerec@cofc.es</a></p>
        </li>
        <li class="promoven">
          <p>PROMOVEN:</p>
          <a href="http://massocial.fbarrie.org" target=blank><img src="themes/MSC/cierre_masocial.gif" width="116" height="83" /></a><a href="http://www.fundacionbarrie.org" target=_blank><img src="themes/MSC/cierre_barrie.gif" width="143" height="83" /></a><a href="http://www.pwc.es/es/fundacion/index.jhtml" target=_blank><img src="themes/MSC/cierre_pwc.gif" width="108" height="83" /></a> </li>
        <li class="colabora">
          <p>SOCIO TECNOLOXICO:</p>
          <a href="http://www.edisa.com" target=_blank><img src="themes/MSC/cierre_edisa.gif" width="145" height="83" /></a> </li>
        <li class="aviso">
          <p><a href="index.php?V_dir=MSC&V_mod=mapaweb" rel="creditos">MAPA WEB</a> <br />
            <a href="index.php?V_dir=MSC&V_mod=showart&id=53" rel="creditos">ACCESIBILIDADE WEB</a> <br />
            <a href="index.php?V_dir=MSC&V_mod=showart&id=52" rel="aviso_legal">AVISO LEGAL</a></p>
        </li>
      </ul>
');
} else {
	define(_DEF_SITENAME,"Federaci&oacute;n Gallega de Enfermedades Raras y Cr&oacute;nicas");
	define(_DEF_FOOT,'      <ul>
        <li class="logo"> <a href="#"><img src="themes/MSC/cierre_fegerec.gif" width="271" height="68" /></a>
          <p>Centro Municipal de Enfermos Cr&oacute;nicos <br />
                Domingo Garc&iacute;a Sabell <br />
                Plaza Esteban Lareo Bloque 17 S&oacute;tano   |   15008 A Coru&ntilde;a<br />
            Tel.: 981 234 651 - 691 011 855   |   correo-e: <a href="mailto:fegerec@cofc.es">fegerec@cofc.es</a></p>
        </li>
        <li class="promoven">
          <p>PROMUEVEN:</p>
          <a href="http://massocial.fbarrie.org" target=blank><img src="themes/MSC/cierre_masocial.gif" width="116" height="83" /></a><a href="http://www.fundacionbarrie.org" target=_blank><img src="themes/MSC/cierre_barrie.gif" width="143" height="83" /></a><a href="http://www.pwc.es/es/fundacion/index.jhtml" target=_blank><img src="themes/MSC/cierre_pwc.gif" width="108" height="83" /></a> </li>
        <li class="colabora">
          <p>SOCIO TECNOLOGICO:</p>
          <a href="http://www.edisa.com" target=_blank><img src="themes/MSC/cierre_edisa.gif" width="145" height="83" /></a> </li>
        <li class="aviso">
          <p><a href="index.php?V_dir=MSC&V_mod=mapaweb" rel="creditos">MAPA WEB</a> <br />
            <a href="index.php?V_dir=MSC&V_mod=showart&id=53" rel="creditos">ACCESIBILIDAD WEB</a> <br />
            <a href="index.php?V_dir=MSC&V_mod=showart&id=52" rel="aviso_legal">AVISO LEGAL</a></p>
        </li>
      </ul>
');
}
######################################################################
# Site Language Preferences
# _DEF_LANGUAGE: Language of your site (You need to have lang-xxxxxx.php file for your selected language in the /language directory of your site)
# _DEF_LOCALE:   Locale configuration to correctly display date with your country format. (See /usr/share/locale)
######################################################################
define(_DEF_LANGUAGE,"spanish");
define(_DEF_LOCALE,"es_ES");
define(_DEF_DBLANGUAGES,"spanish,galician");
define(_DEF_ALLCOLUMNSDBLANGUAGES,"2");
######################################################################
define(_DEF_LEVEL_STAT,"2");
if ($SQL_DEBUG!="") define(_SQL_DEBUG,"1");
else define(_SQL_DEBUG,"0");
define(_DEBUG,"0");
define(_DEF_VERSION_RAD,"1.00");
define(_DEF_SQLDELAY_DEBUG,"1.1"); // time execution to debug delay
######################################################################
# Email Server Configuration
######################################################################
define(_DEF_EMAIL_SERVER,"");		// Email Server
define(_DEF_POP_SERVER,"");		// POP3 or IMAP Server
define(_DEF_EMAIL_SERVER_TYPE,"POP3");		// POP3 or IMAP
######################################################################
# Calendars Hours
######################################################################
define(_DEF_CAL_INI_HOUR,"9");
define(_DEF_CAL_LAST_HOUR,"18");
define(_DEF_CAL_MINUTES,"30"); // 5,10,15,20,30,60
######################################################################
# Application Config
######################################################################
//include_once("config.DESCARGA.php"); // incluir para descargar toda la web con wget o similar
######################################################################
# Vars Config
######################################################################
$RAD_noSeconds="1"; // 1=don't show/edit seconds of time fields
$RAD_gapMinutes="5"; // x=minute's interval of time fields
######################################################################

$RAD_CKEditor="1";
$RAD_NOcheckAutSQLDelete="";


define(_DEF_FACEBOOK,"https://www.facebook.com/FEGEREC");
define(_DEF_TWITTER,"https://twitter.com/FEGEREC");
define(_DEF_YOUTUBE,"http://www.youtube.com/user/FEGEREC");

######################################################################
## Datos TPV Virtual
######################################################################
define(_DEF_TPV_URLNOTIF,"http://fegerec.edisaopensource.es/");

//define(_DEF_TPV_URLPAGO,"https://sis.sermepa.es/sis/realizarPago"); // real
define(_DEF_TPV_URLPAGO,"https://sis-t.redsys.es:25443/sis/realizarPago"); // test

//define(_DEF_TPV_CLAVE_SECRETA,"1VT67949114Q8527"); // real 
define(_DEF_TPV_CLAVE_SECRETA,"qwertyasdf0123456789"); // test

define(_DEF_TPV_MERCHANT_CODE,"322008111");
define(_DEF_TPV_TERMINAL,"1");
define(_DEF_TPV_TRANSACTION_TYPE,"0");

//      Numero de tarjeta Test: 4548812049400004
//      Caducidad: 12/12
//      Codigo CVV2: 123
//      Codigo CIP: 123456 

######################################################################
## Datos TPV PayPal
######################################################################
define(_DEF_PAYPALBUSINESS,"fegerec@gmail.com"); // ID=56N5TZV6ZPQJN

?>
