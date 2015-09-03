<?php
//error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_DEPRECATED);
ini_set('display_errors','On');

global $PHP_SELF;
if ($_REQUEST["PHP_SELF"]!="") $_REQUEST["PHP_SELF"]="";

if ($PHP_SELF=="") $PHP_SELF=getenv("SCRIPT_NAME");
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}
global $name, $file, $V_dir, $V_mod;

if (defined('_DEF_DIR_ROOT')) $dir_root = _DEF_DIR_ROOT;
else $dir_root = "";

require_once("functions.php");

global $_SESSION, $HTTP_SESSION_VARS;
if (!isset($_SESSION) && !isset($HTTP_SESSION_VARS)) { session_start(); }

setSessionVar("SESSION_pagetitle","",0);
setSessionVar("SESSION_keywords","",0);
setSessionVar("SESSION_description","",0);

if(!isset($HTTP_SESSION_VARS)) $HTTP_SESSION_VARS=& $_SESSION;
if (count($_SESSION)>0) foreach ($_SESSION as $TMP_key=>$TMP_val) { $HTTP_SESSION_VARS[$TMP_key]=$TMP_val; }

if (isset($_REQUEST["RAD_cfg"])) setSessionVar("SESSION_cfg",$_REQUEST["RAD_cfg"],0);
if (getSessionVar("SESSION_cfg")=="") $TMP_cfg=$RAD_cfg;
else $TMP_cfg=getSessionVar("SESSION_cfg");

if (eregi("google",getenv("HTTP_USER_AGENT")) || eregi("robot",getenv("HTTP_USER_AGENT"))) {
   if ($V_mod=="showcalendar") die(); // no muestra calendarios a robots
   if ($V_mod=="showrecimg") die(); // no muestra imagenes de recursos a robots
}

if ($V_dir=="" && $name!="") $V_dir=$name;
if ($V_mod=="" && $file!="") $V_mod=$file;
if ($V_idmod=="" && $idmod!="") $V_idmod=$idmod;
if ($V_dir!="" && $name=="") $name=$V_dir;
if ($V_mod!="" && $file=="") $file=$V_mod;
if ($V_idmod!="" && $idmod=="") $idmod=$V_idmod;

if(!isset($HTTP_SERVER_VARS)) $HTTP_SERVER_VARS=& $_SERVER;
if(!isset($HTTP_GET_VARS)) $HTTP_GET_VARS=& $_GET;
if(!isset($HTTP_POST_VARS)) $HTTP_POST_VARS=& $_POST;
if(!isset($HTTP_COOKIE_VARS)) $HTTP_COOKIE_VARS=& $_COOKIES;
if(!isset($HTTP_POST_FILES)) $HTTP_POST_FILES=& $_FILES;
if(!isset($HTTP_ENV_VARS)) $HTTP_ENV_VARS=& $_ENV;

foreach ($_SERVER as $TMP_key=>$TMP_val) {
   if (!is_array($TMP_val)) {
      if (get_magic_quotes_gpc()) ${$TMP_key} = stripslashes ($TMP_val);
      else ${$TMP_key} = $TMP_val;
      global ${$TMP_key};
   }
}
if (count($_REQUEST)>0) {
  foreach ($_REQUEST as $TMP_key=>$TMP_val) { // copia campos date5 de html5 a campos no html5 de RAD
	if(substr($TMP_key,strlen($TMP_key)-6)=="_date5") {
		$TMP_key2=substr($TMP_key,0,strlen($TMP_key)-1);
		$_REQUEST[$TMP_key2]=substr($_REQUEST[$TMP_key],8,2).substr($_REQUEST[$TMP_key],5,2).substr($_REQUEST[$TMP_key],0,4);
		//if ($_REQUEST[$TMP_key2]=="") $_REQUEST[$TMP_key2]=substr($_REQUEST[$TMP_key],8,2).substr($_REQUEST[$TMP_key],5,2).substr($_REQUEST[$TMP_key],0,4);
	}
  }
  foreach ($_REQUEST as $TMP_key=>$TMP_val) {
   if (!is_array($TMP_val)) {
      if ($V_mod!="filemanager" && $V_mod!="filemanager.php" && $V_mod!="admarticulos" && $V_mod!="personal" && $V_mod!="discovirtual" && $V_dir!="phpMyAdmin" && $V_dir!="phpGenRAD" && $V_dir!="admin" && $V_dir!="utilsdev") {
         if (!is_admin() && !is_editor()) {
/*
            $TMP_val=eregi_replace("<script", "< script", $TMP_val); // Para evitar Cross Site Scripting en modulos no filemanager de admin
            $TMP_val=eregi_replace("<applet", "< applet", $TMP_val); // Para evitar Cross Site Scripting en modulos no filemanager de admin
            $TMP_val=eregi_replace("<object", "< object", $TMP_val); // Para evitar Cross Site Scripting en modulos no filemanager de admin
            $TMP_val=eregi_replace("<embed", "< embed", $TMP_val); // Para evitar Cross Site Scripting en modulos no filemanager de admin
            $TMP_val=eregi_replace("<img", "< img", $TMP_val); // Para evitar Cross Site Scripting en modulos no filemanager de admin
            $TMP_val=eregi_replace("<input", "< input", $TMP_val); // Para evitar Cross Site Scripting en modulos no filemanager de admin
            $TMP_val=eregi_replace("<form", "< form", $TMP_val); // Para evitar Cross Site Scripting en modulos no filemanager de admin
            $TMP_val=eregi_replace("<fram", "< fram", $TMP_val); // Para evitar Cross Site Scripting en modulos no filemanager de admin
*/
            $TMP_val=eregi_replace("<", "< ", $TMP_val); // Para evitar Cross Site Scripting en modulos no filemanager de admin
            $TMP_val=eregi_replace("<  ", "< ", $TMP_val); // Para evitar Cross Site Scripting en modulos no filemanager de admin
            $TMP_val=eregi_replace("< !", "<!", $TMP_val); // Para evitar Cross Site Scripting en modulos no filemanager de admin
            $TMP_val = eregi_replace("javascript:", "javascript :", $TMP_val); // Para evitar Cross Site Scripting en modulos no filemanager de admin
         }
         $_REQUEST[$TMP_key]=$TMP_val;
      }
      if (get_magic_quotes_gpc()) ${$TMP_key} = stripslashes ($TMP_val);
      else ${$TMP_key} = $TMP_val;
      global ${$TMP_key};
   } else {
      foreach ($TMP_val as $TMP_key2=>$TMP_val2) {
         if (get_magic_quotes_gpc()) $TMP_val2 = stripslashes ($TMP_val2);
         ${$TMP_key}[$TMP_key2] = $TMP_val2;
         global ${$TMP_key};
      }
   }
  }
}
$V_idmod=str_replace("'","",$V_idmod);
$V_mod=str_replace("'","",$V_mod);
$V_dir=str_replace("'","",$V_dir);

////if (count($HTTP_GET_VARS)>0) {
////  foreach ($HTTP_GET_VARS as $secvalue) {
////     if (eregi("<[^>]*script*\"?[^>]*>", $secvalue)) {
////        error (_SECURITY_ERROR."(2a)");
////     }
////  }
////  foreach ($HTTP_GET_VARS as $TMP_key=>$TMP_val) {
////     if (!is_array($TMP_val)) {
////        if(get_magic_quotes_gpc()) {
////           $HTTP_GET_VARS[$TMP_key] = stripslashes ($TMP_val);
////           ${$TMP_key} = stripslashes ($TMP_val);
////        }
////     }
////  }
////}
////if (count($HTTP_POST_VARS)>0) {
// foreach ($HTTP_POST_VARS as $secvalue) {
//    if (eregi("<[^>]*script*\"?[^>]*>", $secvalue)) {
//       error (_SECURITY_ERROR."(2b)");
//    }
// }
////  foreach ($HTTP_POST_VARS as $TMP_key=>$TMP_val) {
////     if (!is_array($TMP_val)) {
////        if(get_magic_quotes_gpc()) {
////           $HTTP_POST_VARS[$TMP_key] = stripslashes ($TMP_val);
////           ${$TMP_key} = stripslashes ($TMP_val);
////        }
////     }
////  }
////}

global $RAD_device;
if ($RAD_device!="") {
	setSessionVar("SESSION_device",$RAD_device,0);
} else {
	if (RAD_isMobile()) setSessionVar("SESSION_device","M",0);
	$RAD_device=getSessionVar("SESSION_device");
	if ($RAD_device=="") {
		setSessionVar("SESSION_device","N",0);                  // navegador por defecto
		$RAD_device=getSessionVar("SESSION_device");
	}
}

if (file_exists("config".$TMP_cfg.".php")) include_once("config".$TMP_cfg.".php");
else if (file_exists("config".$GLOBALS["HTTP_HOST"].".php")) include_once("config".$GLOBALS["HTTP_HOST"].".php");
else RAD_die("Falta fichero de configuracion...");

require_once("session.php");
if (file_exists("tables".$TMP_cfg.".php")) require_once("tables".$TMP_cfg.".php");
else require_once("tables.php");
require_once("blocks.php");
require_once("sqlDB.php");

if (_DEF_MAX_EXEC_TIME!="" && _DEF_MAX_EXEC_TIME!="_DEF_MAX_EXEC_TIME") set_time_limit(_DEF_MAX_EXEC_TIME);

//global $_FILES;
if (count($HTTP_POST_FILES)>0) {
   foreach ($HTTP_POST_FILES as $TMP_key=>$TMP_val) {
      if (is_array($TMP_val)) {
         foreach ($TMP_val as $TMP_key2=>$TMP_val2) {
            if (get_magic_quotes_gpc()) $TMP_val2 = stripslashes ($TMP_val2);
            if (trim($TMP_val2=="")) continue;
            global ${$TMP_key."_".$TMP_key2};
            ${$TMP_key."_".$TMP_key2}=$TMP_val2;
            if ($TMP_key2=="error" && $TMP_val2>0 && $TMP_val2!=4) {
               error("Error al crear fichero en el servidor ($TMP_val2).");
            }
            if ($TMP_key2=="tmp_name") {
               global ${$TMP_key};
               ${$TMP_key}=$TMP_val2;
            }
         }
      }
   }
}
////if (count($_FILES)>0) {
////  foreach ($_FILES as $TMP_key=>$TMP_val) {
////     if (is_array($TMP_val)) {
////        foreach ($TMP_val as $TMP_key2=>$TMP_val2) {
////           global ${$TMP_key."_".$TMP_key2};
////           ${$TMP_key."_".$TMP_key2}=$TMP_val2;
////           if ($TMP_key2=="error" && $TMP_val2>0) {
////              error("Error al crear fichero en el servidor ($TMP_val2).");
////           }
////           if ($TMP_key2=="tmp_name") {
////              global ${$TMP_key};
////              ${$TMP_key}=$TMP_val2;
////           }
////        }
////     }
////  }
////}

if (_DEF_dbhost=="") {
    Header("Location: install.php");
    die();
}
if ($HTTPS_SERVER[HTTPS]!="" || $_SERVER[HTTPS]!="")
        $TMP_URL="https://".$GLOBALS["HTTP_HOST"];
else
        $TMP_URL="http://".$GLOBALS["HTTP_HOST"];
if (dirname($PHP_SELF)=="\\") $TMP_URL.="/";
else $TMP_URL.=dirname($PHP_SELF);
if (dirname($PHP_SELF)!="/" && dirname($PHP_SELF)!="\\") $TMP_URL.="/";
if (_DEF_VALIDATE_URL=="0") define(_DEF_URL,$TMP_URL);
if (_DEF_VALIDATE_URL!="0" && substr($TMP_URL,0,strlen(_DEF_URL))!=_DEF_URL  && substr($TMP_URL,0,strlen(_DEF_URL))!=_DEF_URL_SUBBROWSE && $GLOBALS["HTTP_HOST"]!="" && $V_op!="logout" && $op!="logout" && V_dir!="coremods" && $V_mod!="usercontrol") {
// URL hijacking
   echo "\n<script>\nparent.location.href='"._DEF_URL."index.php?rs=invalidURL&RAD_cfg=".$TMP_cfg."&".urlencode($TMP_URL).urlencode(_DEF_URL)."';\n</script>\n";
// echo "\n<script>\nparent.location.href='"._DEF_URL."index.php?V_dir=coremods&V_mod=usercontrol&V_op=logout&rs=invalidURL&RAD_cfg=".$TMP_cfg."&".urlencode($TMP_URL).urlencode(_DEF_URL)."';\n</script>\n";
// header("Location: "._DEF_URL."index.php?V_dir=coremods&V_mod=usercontrol&V_op=logout&RAD_cfg=".$TMP_cfg);
//        Header("Location: "._DEF_URL."index.php");
        RAD_die("URL incorrecta...");
}

if ($dbname!="" && substr($dbname,0,strlen(_DEF_dbname))!=_DEF_dbname) RAD_die("Base de datos no permitida... $dbname ("._DEF_dbname.")");
if ($dbname!="" && substr($dbname,0,strlen(_DEF_dbname))==_DEF_dbname) {
   if ($dbhost!="") {
      if (!$RAD_dbi = sql_connect($dbhost, _DEF_dbuname, _DEF_dbpass, $dbname)) {
         $RAD_dbi = sql_connect(_DEF_dbhost, _DEF_dbuname, _DEF_dbpass, $dbname);
      }
   } else $RAD_dbi = sql_connect(_DEF_dbhost, _DEF_dbuname, _DEF_dbpass, $dbname);
   if (file_exists("trigger.".$dbname.".php")) include_once("trigger.".$dbname.".php");
   if (file_exists("tables.".$dbname.".php")) include_once("tables.".$dbname.".php");
} else {
   if ($dbhost!="") {
      if (!$RAD_dbi = sql_connect($dbhost, _DEF_dbuname, _DEF_dbpass, _DEF_dbname)) {
         $RAD_dbi = sql_connect(_DEF_dbhost, _DEF_dbuname, _DEF_dbpass, _DEF_dbname);
      }
   } else $RAD_dbi = sql_connect(_DEF_dbhost, _DEF_dbuname, _DEF_dbpass, _DEF_dbname);
   if (file_exists("trigger."._DEF_dbname.".php")) include_once("trigger."._DEF_dbname.".php");
   if (file_exists("tables."._DEF_dbname.".php")) include_once("tables."._DEF_dbname.".php");
}
if (_DEF_dbname2!="" && _DEF_dbname2!="_DEF_dbname2") {
   if ($dbname2!="" && substr($dbname2,0,strlen(_DEF_dbname2))!=_DEF_dbname2) RAD_die("Base de datos no permitida... $dbname2 ("._DEF_dbname2.")");
   if ($dbname2!="" && substr($dbname2,0,strlen(_DEF_dbname2))==_DEF_dbname2) {
	if ($dbhost2!="") {
		if (!$RAD_dbi2 = sql_connect($dbhost2, _DEF_dbuname2, _DEF_dbpass2, $dbname2)) {
			$RAD_dbi2 = sql_connect(_DEF_dbhost2, _DEF_dbuname2, _DEF_dbpass2, $dbname2);
		}
	} else $RAD_dbi2 = sql_connect(_DEF_dbhost2, _DEF_dbuname2, _DEF_dbpass2, $dbname2);
	if (file_exists("trigger.".$dbname2.".php")) include_once("trigger.".$dbname2.".php");
	if (file_exists("tables.".$dbname2.".php")) include_once("tables.".$dbname2.".php");
} else {
	if ($dbhost2!="") {
		if (!$RAD_dbi2 = sql_connect($dbhost2, _DEF_dbuname2, _DEF_dbpass2, _DEF_dbname2)) {
			$RAD_dbi2 = sql_connect(_DEF_dbhost2, _DEF_dbuname2, _DEF_dbpass2, _DEF_dbname2);
		}
	} else $RAD_dbi2 = sql_connect(_DEF_dbhost2, _DEF_dbuname2, _DEF_dbpass2, _DEF_dbname2);
	if (file_exists("trigger."._DEF_dbname2.".php")) include_once("trigger."._DEF_dbname2.".php");
	if (file_exists("tables."._DEF_dbname2.".php")) include_once("tables."._DEF_dbname2.".php");
   }
}

$TMP_themeSel = getSessionVar("SESSION_theme");
if ($TMP_themeSel=="") {
	$TMP_themeSel=_DEF_THEME;
	setSessionVar("SESSION_theme",$TMP_themeSel,0);
}

if (isset($newlang)) {
   $locale="";
   if ($newlang=="spanish") $locale="es";
   if ($newlang=="galician") $locale="gl";
   if ($newlang=="catalan") $locale="ca";
   if ($newlang=="french") $locale="fr";
   if ($newlang=="english") $locale="en";
   if ($locale!=="") setSessionVar("SESSION_locale",$locale,0);
   if ($newlang=="default") $newlang=_DEF_LANGUAGE;
   if (file_exists("themes/".$TMP_themeSel."/lang-$newlang.php")) {
      setSessionVar("SESSION_lang",$newlang,0);
   } else if (file_exists("language/lang-$newlang.php")) {
      setSessionVar("SESSION_lang",$newlang,0);
   }
}
if (getSessionVar("SESSION_lang")=="") {
      setSessionVar("SESSION_lang",_DEF_LANGUAGE,0);
}
if (file_exists("themes/".$TMP_themeSel."/lang-".getSessionVar("SESSION_lang").".php")) {
      include_once("themes/".$TMP_themeSel."/lang-".getSessionVar("SESSION_lang").".php");
}
if (file_exists("language/lang-".getSessionVar("SESSION_lang").".php")) {
   include_once("language/lang-".getSessionVar("SESSION_lang").".php");
}
if (file_exists("blocks/language/lang-".getSessionVar("SESSION_lang").".php")) {
      include_once("blocks/language/lang-".getSessionVar("SESSION_lang").".php");
}
if (file_exists("modules/".$V_dir."/language/lang-".getSessionVar("SESSION_lang").".php")) {
        include("modules/".$V_dir."/language/lang-".getSessionVar("SESSION_lang").".php");
}

if (count($HTTP_GET_VARS)>0) {
    foreach ($HTTP_GET_VARS as $TMP_key=>$TMP_val) {
   if (!is_array($TMP_val)) RAD_verifyContent($TMP_val);
    }
}
if (count($HTTP_POST_VARS)>0) {
    foreach ($HTTP_POST_VARS as $TMP_key=>$TMP_val) {
   if (!is_array($TMP_val)) RAD_verifyContent($TMP_val);
    }
}

if (isset($_REQUEST["captcha_code"])) { // comprobacion de captcha con campo entrada captcha_code de formularios con securimage
	$TMP_ses_code=getSessionVar("securimage_code_value");
	$TMP_req_code=$_REQUEST["captcha_code"];
	if (strtoupper($TMP_req_code)!=strtoupper($TMP_ses_code) && $TMP_ses_code!="") {
		error("Error: Codigo de Imagen incorrecto");
	}
}

require_once("compatibility.php");

$URLROI="";
$FORMROI="";
//if ($V_idmod!="") {
// $URLROI.="&V_idmod=".urlencode($V_idmod);
// $FORMROI.="<input type=hidden name=V_idmod value='".$V_idmod."'>";
//}
if ($headeroff!="") {
   $URLROI.="&headeroff=".urlencode($headeroff);
   $FORMROI.="<input type=hidden name=headeroff value='".$headeroff."'>";
}
if ($footeroff!="") {
   $URLROI.="&footeroff=".urlencode($footeroff);
   $FORMROI.="<input type=hidden name=footeroff value='".$footeroff."'>";
}
//if (_DEF_LEVEL_STAT>0 && $subbrowseSID=="") include("blocks/stat.php");
if (_DEF_LEVEL_STAT>0) include("blocks/stat.php");
/////////////////////////////////////////////////////////////////////////////
function get_lang($module) {
   if (file_exists("modules/$module/lang-".getSessionVar("SESSION_lang").".php")) {
      include_once("modules/$module/lang-".getSessionVar("SESSION_lang").".php");
      return "modules/$module/lang-".getSessionVar("SESSION_lang").".php";
   } else {
      if (file_exists("modules/$module/language/lang-".getSessionVar("SESSION_lang").".php")) {
         include_once("modules/$module/language/lang-".getSessionVar("SESSION_lang").".php");
         return "modules/$module/language/lang-".getSessionVar("SESSION_lang").".php";
      } else {
         if (file_exists("modules/$module/language/lang-"._DEF_LANGUAGE.".php")) {
            include_once("modules/$module/language/lang-"._DEF_LANGUAGE.".php");
            return "modules/$module/language/lang-"._DEF_LANGUAGE.".php";
         }
      }
   }
   return "";
}
/////////////////////////////////////////////////////////////////////////////
function title($text) {
   OpenTable();
   echo "<center><b>$text</b></center>";
   CloseTable();
// echo "<br>";
}
/////////////////////////////////////////////////////////////////////////////
function formatTimestamp($time) {
    global $datetime;
    setlocale ("LC_TIME", _DEF_LOCALE);
    ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $time, $datetime);
    $datetime = strftime(""._DATESTRING."", mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]));
    $datetime = ucfirst($datetime);
    return($datetime);
}
/////////////////////////////////////////////////////////////////////////////
function error($msg) {
	global $subfunc;
	RAD_logError("ERR: ".$msg);
	if ($msg=="") return;
	$msg=str_replace("'","\'",$msg);
	$msg=str_replace("\r","",$msg);
	$msg=str_replace("\n","\\n",$msg);
	$msgHTML=str_replace("\\n","<br>",$msg);
	$msg=RAD_UTF_to_Unicode($msg);
	echo "
<script type='text/javascript'>
alert('$msg.');
";
	//if ($subfunc!="") echo "RAD_CloseW(true);";
	//else echo "window.history.back();";
	echo "window.history.back();";
	echo "
</script>
$msgHTML <br>
<a href='index.php'>"._HOME."</a><br>";
   die;
}
/////////////////////////////////////////////////////////////////////////////
function alert($msg) {
   if ($msg=="") return;
   RAD_logError("ALERT: ".$msg);
   $msg=str_replace("'","\'",$msg);
   $msg=str_replace("\r","",$msg);
   $msg=str_replace("\n","\\n",$msg);
   $msg=RAD_UTF_to_Unicode($msg);
   echo "\n<script type='text/javascript'>\nalert('$msg.');\n</script>\n";
   //echo "$msg <br>\n<a href='index.php'>"._HOME."</a>\n";
}
/////////////////////////////////////////////////////////////////////////////
function status($msg) {
   global $RAD_statustimeout;
   if ($msg=="") return;
   RAD_logError("STATUS: ".$msg);
   if ($RAD_statustimeout>0) $TMP_statustimeout=$RAD_statustimeout;
   else $TMP_statustimeout="6000";
   $msg=str_replace("'","\'",$msg);
   $msg=RAD_UTF_to_Unicode($msg);
   echo "\n<script type='text/javascript'>\nwindow.status='$msg.';\n</script>\n";
   echo "\n<script type='text/javascript'>\ndocument.getElementById(\"RAD_status\").innerHTML=document.getElementById(\"RAD_status\").innerHTML+'$msg.';\nsetTimeout('document.getElementById(\"RAD_status\").innerHTML=\"\"',".$TMP_statustimeout.");\n</script>\n";
}
/////////////////////////////////////////////////////////////////////////////
function is_modulepermitted($TMP_idmod, $TMP_dir, $TMP_file) {
	global $RAD_dbi;
	if ($TMP_idmod=="" && $TMP_file=="") return false;
	if (ereg(",".$TMP_idmod.",",getSessionVar("SESSION_idmods"))) return true;
	if ($TMP_idmod!="") {
		$TMP_query="select "._DBF_M_IDMODULE.", "._DBF_M_DIR.", "._DBF_M_ACTIVE.", "._DBF_M_PROFILES.", "._DBF_M_PUBLIC." from "._DBT_MODULES." where "._DBF_M_IDMODULE."='$TMP_idmod'";
	} else if ($TMP_file!="") {
		$TMP_query="select "._DBF_M_IDMODULE.", "._DBF_M_DIR.", "._DBF_M_ACTIVE.", "._DBF_M_PROFILES.", "._DBF_M_PUBLIC." from "._DBT_MODULES." where "._DBF_M_DIR."='$TMP_dir' AND "._DBF_M_FILE."='$TMP_file'";
	} else {
		$TMP_query="select "._DBF_M_IDMODULE.", "._DBF_M_DIR.", "._DBF_M_ACTIVE.", "._DBF_M_PROFILES.", "._DBF_M_PUBLIC." from "._DBT_MODULES." where "._DBF_M_DIR."='$TMP_dir'";
	}
	$TMP_result=sql_query($TMP_query,$RAD_dbi);
	while($TMP_row = sql_fetch_array($TMP_result, $RAD_dbi)) {
		$TMP_idmodDB=$TMP_row[0];
		$TMP_dir=$TMP_row[1];
		if ($TMP_row[2]=='S') $TMP_row[2]="1";
		if ($TMP_row[2]=='N') $TMP_row[2]="0";
		$TMP_mod_active=$TMP_row[2];
		$TMP_profiles=$TMP_row[3];
		$TMP_public=$TMP_row[4];
		if (ereg(",".$TMP_idmodDB.",",getSessionVar("SESSION_idmods"))) return true;
		if ($TMP_mod_active!="0") {
			$TMP_permitido=verifyProfile($TMP_profiles,$TMP_public);
			if ($TMP_permitido==true) return true;
		}
	}
	if ($TMP_mod_active=="0") return false;
	if ($TMP_dir=="") return false;
	//return verifyProfile($TMP_profiles,$TMP_public);
	return false;
}
/////////////////////////////////////////////////////////////////////////////
function is_artpermitted($TMP_idart, $TMP_user=0, $TMP_profiles=0) {
    global $RAD_dbi;

    if ($TMP_profiles==0) $TMP_profiles=getSessionVar("SESSION_profiles");

    $TMP_q="SELECT * FROM articulos WHERE id='".$TMP_idart."'";
    $TMP_result=sql_query("SELECT * FROM articulos WHERE id='".$TMP_idart."'",$RAD_dbi);
    $TMP_art=sql_fetch_array($TMP_result,$RAD_dbi);
    if ($TMP_art[publico]==1) {
      return true;
    }else{
      if ($TMP_art[perfiles]!="") {
        if ($TMP_user!==0) {
          $TMP_profiles=RAD_lookup("usuarios","perfil","usuario",$TMP_user);
          $TMP_profiles=explode(",,",trim($TMP_profile, ","));
          foreach ($TMP_profiles as $k => $TMP_profile) {
            if (ereg($TMP_profile,$TMP_art[perfiles])) return true;
          }
          return false;
        }
        if($TMP_profiles!==0) {
          $TMP_profiles=explode(",,",trim($TMP_profiles, ",,"));
          foreach ($TMP_profiles as $k => $TMP_profile) {
            if (ereg($TMP_profile,$TMP_art[perfiles])) return true;
          }
          return false;
        }
      }else{
        return true;
      }
    }
    return true;
}
/////////////////////////////////////////////////////////////////////////////
function verifyProfile($TMP_profile,$TMP_public) {

   $TMP_userprofile=getSessionVar("SESSION_profiles");

//die("is_modpermit=".$TMP_idmod."_".$TMP_dir."/".$TMP_file."*".getSessionVar("SESSION_idmods")."+".getSessionVar("SESSION_profiles"));
	if ($TMP_profile=="" && $TMP_public!="0")  return true;
	if ($TMP_userprofile=="") {
	    if ($TMP_public=="1" || $TMP_public=='S') {
		return true;
	    } else {
		return false;
	    }
	}
	$arrP = explode(",", $TMP_profile);
	$arrU = explode(",", $TMP_userprofile);
	for ($ki = 0; $ki < count($arrP); $ki++) {
		for ($kj = 0; $kj < count($arrU); $kj++) {
			if ($arrU[$kj]!="" && $arrP[$ki]!="" && $arrU[$kj]== $arrP[$ki]) return true;
		}
	}
	return false;
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////
function homepageUser() {
   global $RAD_dbi;
   $TMP_userprofile=getSessionVar("SESSION_profiles");
   if ($TMP_userprofile=="") return "";
   $TMP_profile = explode(",", $TMP_userprofile);
   for ($ki = 0; $ki < count($TMP_profile); $ki++) {
      if ($TMP_profile[$ki]!="") {
         $result=sql_query("select * from "._DBT_PROFILES." where "._DBF_P_IDPROFILE."='".$TMP_profile[$ki]."'", $RAD_dbi);
         $TMP_row=sql_fetch_array($result, $RAD_dbi);
         if ($TMP_row[_DBF_P_HOME]!="") return $TMP_row[_DBF_P_HOME];
      }
   }
   return "";
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function getTheme() {
   global $RAD_theme, $RAD_newtheme, $SESSION_theme;
   if ($RAD_theme!="") {
       if($TMP_file=@opendir("themes/$RAD_theme")) {
      //setSessionVar("SESSION_theme",$RAD_theme,0);
      include_once("themes/$RAD_theme/theme.php");
           return;
       }
   }
   if ($RAD_newtheme!="") {
       if($TMP_file=@opendir("themes/$RAD_newtheme")) {
      //setSessionVar("SESSION_theme",$RAD_newtheme,0);
      include_once("themes/$RAD_newtheme/theme.php");
           return;
       }
   }
   if ($SESSION_theme!="") {
       if($TMP_file=@opendir("themes/$SESSION_theme")) {
      include_once("themes/$SESSION_theme/theme.php");
           return;
       }
   }
   if(is_user()) {
      $TMP_themeSel = getSessionVar("SESSION_theme");
      if ($TMP_themeSel == "") $TMP_themeSel = _DEF_THEME;
      if(!$TMP_file=@opendir("themes/$TMP_themeSel")) {
         $TMP_theme = _DEF_THEME;
      } else {
         $TMP_theme = $TMP_themeSel;
      }
   } else {
      $TMP_theme = _DEF_THEME;
   }
   if ($TMP_theme == "_DEF_THEME") return;
   include_once("themes/$TMP_theme/theme.php");
   setSessionVar("SESSION_theme",$TMP_theme,0);
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function head() {
   global $RAD_device, $RAD_milsep, $RAD_decimalsep, $RAD_headHTML, $RAD_CKEditor, $PHP_SELF, $bodyoff, $headeroff, $menuoff, $SESSION_theme, $subbrowseSID, $func, $V_typePrint, $V_typeSend, $V_typeZIP, $V_idmod, $V_dir, $subfunc, $V_index, $V_mod, $RAD_OpenW_width, $RAD_OpenW_height, $RAD_OpenW_scrollbars, $RAD_nodiv, $func, $subfunc;	

   if ($RAD_OpenW_width=="") $RAD_OpenW_width="400";
   if ($RAD_OpenW_height=="") $RAD_OpenW_height="500";
   if (defined('_DEF_DIR_ROOT')) $dir_root = _DEF_DIR_ROOT;
   else $dir_root = "";

   if ($RAD_CKEditor=="" && _DEF__CKEditor!="" && _DEF_CKEditor!="_DEF_CKEditor") $RAD_CKEditor=_DEF_CKEditor;
 
   if ($V_typeZIP!='') { // Crear un zip, por rapidez lo hago aqui para no cargar mas cosas
       global $V_value;
       if (trim($V_value)!='' && is_modulepermitted($V_idmod,$V_dir,$V_mod)) {
		if (RAD_doZIP($V_value)) die;
		alert("Error al crear fichero zip");
       }
       else alert("Modulo no permitido");
       echo "<script>window.close();</script>";
       die;
   }
   if ($subbrowseSID!="" || $bodyoff!="") return;

   if ($SESSION_theme=="") $SESSION_theme=getSessionVar("SESSION_theme");
   $SESSION_pagetitle=getSessionVar("SESSION_pagetitle");
   $SESSION_description=getSessionVar("SESSION_description");
   $SESSION_keywords=getSessionVar("SESSION_keywords");
   $SESSION_addmetas=getSessionVar("SESSION_addmetas");
   if ($SESSION_theme=="") $SESSION_theme = _DEF_THEME;
   if ($SESSION_theme=="_DEF_THEME") $SESSION_theme = "";
   if (_DEF_DOCTYPE!="" && _DEF_DOCTYPE!="_DEF_DOCTYPE") {
	if (trim(_DEF_DOCTYPE)!="") echo _DEF_DOCTYPE."\n";
   } else {
	//echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">";
	if (eregi("MSIE", getenv("HTTP_USER_AGENT"))) {
		echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd\">\n";
	} else {
		echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
	}
   }

   if (_DEF_HTML!="" && _DEF_HTML!="_DEF_HTML") echo _DEF_HTML;
   else echo "<html>\n";
   echo "<head>\n";
   if (_DEF_noHeadTitle!="x"){
      if ($SESSION_pagetitle!="") echo "<title>$SESSION_pagetitle</title>\n";
      else echo "<title>"._DEF_SITENAME."</title>\n";
   }
// Meta Tags
   //echo "<meta http-equiv=\"Content-Type\" content=\"application/xhtml+xml; charset='._CHARSET.'\" />\n";
   if (_CHARSET!="" && _CHARSET!="_CHARSET") echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset="._CHARSET."\" />\n";
   if (_DEF_noHeadMeta!="x"){
    if(ereg("MSIE 9.0", $_SERVER["HTTP_USER_AGENT"])) echo "<meta http-equiv='X-UA-Compatible' content='IE=8' />\n";
    if ($RAD_device=="M"||$RAD_device=="T") echo "<meta name='viewport' content='width=device-width, initial-scale=1' />\n";
    //echo "<meta http-equiv=\"expires\" content=\"0\" />\n";
    //echo "<meta name=\"resource-type\" content=\"document\" />\n";
    //echo "<meta name=\"distribution\" content=\"global\" />\n";
    echo "<meta name=\"author\" content=\""._DEF_SITENAME."\" />\n";
    //echo "<meta name=\"copyright\" content=\"Copyright (c) ".date("Y")." by "._DEF_SITENAME."\" />\n";
    if ($SESSION_keywords!="") echo "<meta name=\"description\" content=\"".$SESSION_keywords."\" />\n";
    else if (_DEF_KEYWORDS!="" && _DEF_KEYWORDS!="_DEF_KEYWORDS") echo "<meta name=\"keywords\" content=\""._DEF_KEYWORDS."\" />\n";
    else echo "<META NAME=\"KEYWORDS\" CONTENT=\"groupware, workgroup, email, messages, resources, news, technology, headlines, Linux, Windows, Software, Free, Community, Forums, Bulletin, Boards, PHP, Survey, Comments, Portal, Open, Open Source, OpenSource, Free Software, GNU, GPL, License, Unix, MySQL, SQL, Intranet, Database, Web Site, Weblog\">\n";
    if ($SESSION_description!="") echo "<meta name=\"description\" content=\"".$SESSION_description."\" />\n";
    else if (_DEF_DESCRIPTION!="" && _DEF_DESCRIPTION!="_DEF_DESCRIPTION") echo "<meta name=\"description\" content=\""._DEF_DESCRIPTION."\" />\n";
    else echo "<META NAME=\"DESCRIPTION\" CONTENT=\""._DEF_SITENAME."\" />\n";
    if (_DEF_FAVICON!="" && _DEF_FAVICON!="_DEF_FAVICON") echo "<link REL='SHORTCUT ICON' HREF='"._DEF_FAVICON."'>\n";
    echo "<meta name=\"robots\" content=\"index, follow\" />\n";
    echo "<meta name=\"revisit-after\" content=\"1 days\" />\n";
    echo "<meta name=\"rating\" content=\"general\" />\n";
   }
   if ($SESSION_addmetas!="") echo $SESSION_addmetas;
   if (_DEF_noHeadCSS!="x" || (isset($headeroff)) && ($headeroff=="x")){
    $TMP_filecss="themes/$SESSION_theme/style.php";
    if (!file_exists($TMP_filecss)) {
      $TMP_filecss="themes/$SESSION_theme/style.css";
    }
    if (!file_exists($TMP_filecss)) {
      $TMP_filecss="themes/$SESSION_theme/style/style.css";
    }
    if ($V_typePrint=="" && $V_typeSend=="" && $subfunc!="report") {
      if (file_exists($TMP_filecss)) {
         echo "<link rel=\"Stylesheet\" href=\"".$dir_root."$TMP_filecss\" type=\"text/css\" />\n";
      }
    } else {
       echo "\n<style type='text/css'>\n";
       if ($TMP_filecss=="themes/$SESSION_theme/style.php") include($TMP_filecss);
       else @readfile($TMP_filecss);
       echo "\n</style>\n";
    }
   }
   if ($RAD_OpenW_scrollbars=="") $RAD_OpenW_scrollbars="yes";
   if (_DEF_noHeadJavascript!="x" || (isset($headeroff)) && ($headeroff=="x")){
	if ($RAD_CKEditor!="") echo "<script type='text/javascript' src='".$dir_root."images/ckeditor/ckeditor.js'></script>\n";
	else echo "<script type='text/javascript' src='".$dir_root."templates/fckeditor.js'></script>\n";

	//if (_DEF_POPUP_MARGIN=="SUBMODAL" && $V_typePrint=="" && $V_typeSend=="" && $subfunc!="report") {
	if (_DEF_POPUP_MARGIN=="SUBMODAL" && $V_typePrint=="" && $V_typeSend=="") {
		$RAD_nodiv="x";
		echo "<link rel='stylesheet' type='text/css' href='".$dir_root."images/submodal/submodal.css'/>
<script type='text/javascript' src='".$dir_root."images/submodal/common.js'></script>
<script type='text/javascript' src='".$dir_root."images/submodal/submodal.js'></script>
\n";
        }

	echo "<script type='text/javascript'>
	/* <![CDATA[ */
	var selFieldName='';
	var lastField=0;
	function RAD_setselFieldName(val) { selFieldName=val; }
	function RAD_focusNextField(seln) {
		if (seln!='') selFieldName=seln;
		var selFieldName2='';
		theForm='F';
		if (selFieldName=='') return;
		if (selFieldName.substr(0,2)=='VI') {
			tempdata=selFieldName.split('_');
			prefix=tempdata[0];
			theForm=theForm+prefix.replace('VI','');
		}
		if (!document[theForm]) return;
		objForm=document[theForm];
		eval('tipovar=objForm.'+selFieldName+'.type;');
		if(tipovar=='text') eval('objForm.'+selFieldName+'.blur();');
		for(var i=0;i<objForm.elements.length; i++) {
			if (objForm[i].name==selFieldName) {
				if (i<(objForm.elements.length-1)) if (objForm[i+1].name==selFieldName+'_literal') i++;
				if (selFieldName2=='') for (var j=i+1; j<objForm.elements.length; j++) { if (objForm[j].name.length>0 && objForm[j].type!='hidden' && (objForm[j].type!='button'||(objForm[j].type=='button'&&objForm[j].name=='Save'))) { k=j; selFieldName2=objForm[j].name; j=objForm.elements.length; } }
				if (selFieldName2=='') for (var j=0; j<i; j++) { if (objForm[j].name.length>0 && objForm[j].type!='hidden' && (objForm[j].type!='button'||(objForm[j].type=='button'&&objForm[j].name=='Save'))) { k=j; selFieldName2=objForm[j].name; j=objForm.elements.length; } }
				if (selFieldName2=='') for (var j=0; j<i; j++) { if (objForm[j].name.length>0 && objForm[j].type!='radio' && objForm[j].type!='hidden') { k=j; selFieldName2=objForm[j].name; j=objForm.elements.length; } }
				if (selFieldName2.length>0) if (selFieldName2==selFieldName) selFieldName2='';
				if (selFieldName2.length>0 && objForm[k].type=='radio') eval('objForm.'+selFieldName2+'[0].focus();');
				else if (selFieldName2.length>0 && objForm[k].type=='select-multiple') eval('objForm.ID_'+selFieldName2.substring(0,selFieldName2.length-2)+'.focus();');
				else if (selFieldName2.length>0) { eval('objForm.'+selFieldName2+'.focus();'); if (objForm[k].type=='text') eval('objForm.'+selFieldName2+'.select();'); }
				if (selFieldName2.length>0) selFieldName=selFieldName2;
				i=objForm.elements.length;
			}
		}
	}";
	
	echo "getDimensions = function(oElement) {
            var x = 0;  //Desplazamiento lateral del elemento
            var y = 0;  //Desplazamiento vertical del elemento
            var w = 0;  //Ancho del elemento
            var h = 0;  //Altura del elemento
            var sx = 0; //Deplazamiento lateral del scroll
            var sy = 0; //Deplazamiento vertical del scroll
            var ww = 0; //Ancho de la ventana
            var wh = 0; //Alto de la ventana
            if (document.getBoxObjectFor) { // Mozilla
                var oBox = document.getBoxObjectFor(oElement);
                x   = oBox.x-1;
                w   = oBox.width;
                y   = oBox.y-1;
                h   = oBox.height;
            }
            else if (oElement.getBoundingClientRect) { // IE
                var oRect = oElement.getBoundingClientRect();
                x   = oRect.left-2;
                w   = oElement.clientWidth;
                y   = oRect.top-2;
                h   = oElement.clientHeight;
            }

            if(typeof(window.pageYOffset)=='number') {
                sy=window.pageYOffset;
                sx=window.pageXOffset;
            }
            else {
                sy=document.documentElement.scrollTop;
                sx=document.documentElement.scrollLeft;
            }
            
            ww=window.innerWidth;
            wh=window.innerHeight;
            return {x: x, y: y, w: w, h: h, sx: sx, sy: sy, ww: ww, wh: wh};
        }";
	
	echo "

function RAD_focusField(field) {
	if (field=='') field=selFieldName;
	setTimeout('document.F.'+field+'.focus();',100);
}
var RAD_numdosel=new Array();
RAD_numdosel['x']=0;
function RAD_dosel(searchfield,search,res,param) {
   f1=search;
   f2=res;
   f3=search;
   eval('var disf=document.forms.F.B_'+searchfield+'.disabled');
   if (disf) return;
   eval('var readf=document.forms.F.'+searchfield+'.readOnly');
   if (readf) return;
   RAD_numdosel[''+searchfield]++; 
   setTimeout('RAD_numdosel[searchfield]--',1000); 
   if (RAD_numdosel[searchfield]>1) return;
   if (selFieldName!=searchfield && selFieldName!=search.name && selFieldName!=res.name) { 
       if ((RAD_numdosel[searchfield]%2)==0) {
           RAD_setselFieldName(searchfield);
           eval('document.forms.F.'+searchfield+'.focus()');
       } else return;
   }
   eval('val=document.F.'+searchfield+'.value');
   x=".$RAD_OpenW_width.";
   y=".$RAD_OpenW_height.";
   if (window.screen){ if (x>screen.width) x=(screen.width-400)/2; if (y>screen.height) y=screen.height-400; if (x<60) { if (60>screen.width-100) x=screen.width-100; else x=60; } }
   searchpopup = RAD_OpenW(\"".$PHP_SELF."?func=search_js&dbname=".$dbname."&searchfield=\"+escape(searchfield)+\"&searchval=\"+escape(search.value)+\"&param=\"+escape(param)+\"&vlit=\"+escape(res.value)+\"&headeroff=x&footeroff=x&V_dir=".$V_dir."&V_idmod=".$V_idmod."&V_mod=".$V_mod."&V_roi=".urlencode($V_roi)."&PHPSESSID=".$PHPSESSID."\",x,y);
   if (window.focus) { if (searchpopup) searchpopup.focus(); }
    for (var i=0; i<document.F.elements.length; i++) {
      if (document.F[i] == res) {
        for (var j=i+1; j<document.F.elements.length; j++) {
//          if (document.F[j].type=='text' || document.F[j].type=='radio') {
          if (document.F[j].name.length>0) {
             f3=document.F[j];
             j=document.F.elements.length+1;
          }
        }
      }
   }
}
";
	if ($RAD_decimalsep!="" || $RAD_milsep!="") {
		echo "
function RAD_convertToNum(val,extra) {
 var res=''; var entero=0; var decimal=0; var numpuntos=0; var numcifras=0; var numdecimal=0;
 var extra = new String(extra);
 splitExtra=extra.split(',');
 var entero = new Number(splitExtra[0]);
 var decimal = new Number(splitExtra[1]);
 entero = entero - decimal;
 for(k=0; k<val.length; k++){
  car=val.substring(k,k+1);
  if(k==(val.length-1)) {
        if(car==',') car='".$RAD_decimalsep."';
        if(car=='.') car='".$RAD_decimalsep."';
  }
  if(car=='".$RAD_milsep."') continue;
  if(car==',') car='".$RAD_decimalsep."';
  if(car=='.') car='".$RAD_decimalsep."';
  if(car=='-'&&numcifras==0&&numpuntos==0) res=res+car;
  if(car=='".$RAD_decimalsep."'&&numpuntos==0 && decimal>0) { res=res+car; numpuntos++; }
  if (numcifras>=entero && numpuntos==0) { res=res; break; }
  if (numpuntos>0) {
      resSplit = res.split('".$RAD_decimalsep."');
      numdecimal=resSplit[1].length;
  }
  if (numdecimal>=decimal && numpuntos==1) { res=res; break; }
  if(car=='0'||car=='1'||car=='2'||car=='3'||car=='4'||car=='5'||car=='6'||car=='7'||car=='8'||car=='9') { res=res+car; numcifras++; }
 }
 return RAD_addMilSep(res);
}
function RAD_addMilSep(nStr) {
	nStr += '';
	x = nStr.split('".$RAD_decimalsep."');
	x1 = x[0];
	x2 = x.length > 1 ? '".$RAD_decimalsep."' + x[1] : '';
";
		if ($RAD_milsep!="") echo "
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + '".$RAD_milsep."' + '$2');
	}
";
		echo "
	return x1 + x2;
}
";
	} else echo "
function RAD_convertToNum(val,extra) {
 var res=''; var entero=0; var decimal=0; var numpuntos=0; var numcifras=0; var numdecimal=0;
 var extra = new String(extra);
 splitExtra=extra.split(',');
 var entero = new Number(splitExtra[0]);
 var decimal = new Number(splitExtra[1]);
 entero = entero - decimal;
 for(k=0; k<val.length; k++){
  car=val.substring(k,k+1);
  if(car==',') car='.';
  if(car=='-'&&numcifras==0&&numpuntos==0) res=res+car;
  if(car=='.'&&numpuntos==0 && decimal>0) { res=res+car; numpuntos++; }
  if (numcifras>=entero && numpuntos==0) { res=res; break; }
  if (numpuntos>0) {
      resSplit = res.split('.');
      numdecimal=resSplit[1].length;
  }
  if (numdecimal>=decimal && numpuntos==1) { res=res; break; }
  if(car=='0'||car=='1'||car=='2'||car=='3'||car=='4'||car=='5'||car=='6'||car=='7'||car=='8'||car=='9') { res=res+car; numcifras++; }
 }
 return res;
}
";
   echo "
function RAD_jsstring2num(valor) {
";
   if ($RAD_decimalsep=="") $TMP_decimalsep=".";
   else $TMP_decimalsep=$RAD_decimalsep;
   if ($RAD_milsep!="") echo "
   while (valor.toString().indexOf('".$RAD_milsep."')!= -1) valor=valor.replace('".$RAD_milsep."','');
";
   echo "
   valor=valor.replace('".$TMP_decimalsep."','.');
   if (valor=='') valor='0';
   else valor=parseFloat(valor).toFixed(2);
   if (valor=='NaN') valor='0';
   return parseFloat(valor);
   //return Math.round(parseFloat(valor)*100)/100;
   //return parseFloat(parseFloat(valor).toFixed(2));
}
function RAD_jsnum2string(valor) {
   var valors=parseFloat(valor).toFixed(2);
   valors=RAD_addMilSep(valors.replace('.','".$TMP_decimalsep."'));
   return valors;
}
";

	echo "
function RAD_validaEntero(val) {
 if (val=='-' || val=='-.' || val=='.') val = '';
 return val;
}
function RAD_rellenaDecimales(val,extra) {
 var res=''; var entero=0; var decimal=0; var numpuntos=0; var numcifras=0; var numdecimal=0;
 var extra = new String(extra);
 splitExtra=extra.split(',');
 var numdecimals = new Number(splitExtra[1]);
 var valnum = new String(val);
";
	if ($RAD_decimalsep!="" || $RAD_milsep!="") echo "
 splitExtra=valnum.split('".$RAD_decimalsep."');
 var entero = new String(splitExtra[0]);
 if (entero=='') entero='0';
 var decimal = new String(splitExtra[1]);
 if (entero=='undefined') entero='0';
 if (decimal=='undefined') decimal='';
 while (decimal.length < numdecimals) decimal='0'+decimal;
 if (numdecimals>0) val=entero+'".$RAD_decimalsep."'+decimal;
 else val=entero;
";
	else echo "
 splitExtra=valnum.split('.');
 var entero = splitExtra[0];
 if (entero=='') entero='0';
 var decimal = splitExtra[1];
 while (decimal.length < numdecimals) decimal='0'+decimal;
 if (numdecimals>0) val=entero+'.'+decimal;
 else val=entero;
";
	echo "
 return val;
}
function RAD_simulateMouse(element, eventName) { // 'MouseEvents': /^(?:click|dblclick|mouse(?:down|up|over|move|out))$/
    var oEvent = null;
    if (document.createEvent) {
        oEvent = document.createEvent('MouseEvents');
        oEvent.initMouseEvent(eventName, true, true, document.defaultView, 0, 0, 0, 0, 0, false, false, false, false, 0, element);
        element.dispatchEvent(oEvent);
    } else {
        var options; options.clientX = 0; options.clientY = 0;
        var evt = document.createEventObject();
        oEvent = extend(evt, options);
        element.fireEvent('on' + eventName, oEvent);
    }
    return element;
}
function RAD_controlaPorcentaje(elem) {
	if ((parseFloat(elem.value)>100) || (parseFloat(elem.value)<0)) {
		alert('Valor no valido para ese campo');
		elem.value='';
		setTimeout('document.forms.F.'+elem.name+'.focus();',1);
		return false;
	}
	return true;
}
function RAD_controlaFecha(elem) {
	theSufixes=new Array('year','month','day');
	theArr=elem.name.split('_');
	thePrefix=theArr[0];
	theElem=theArr[theArr.length-1];
	theName=elem.name.replace('_'+theElem,'');
	theData=new Array();

	entero=true;
	for (i=0;i<theSufixes.length;i++) {
		if (elem.form[theName+'_'+theSufixes[i]].value=='') entero=false;
		else theData[i]=parseInt(elem.form[theName+'_'+theSufixes[i]].value);
	}
	if (entero) {
		dayobj = new Date(theData[0],theData[1]-1,theData[2]);
		if ((dayobj.getMonth()+1!=theData[1])||(dayobj.getDate()!=theData[2])||(dayobj.getFullYear()!=theData[0])){
			elem.form[theName+'_day'].value--;
			if (elem.form[theName+'_day'].value>0) RAD_controlaFecha(elem);
		}
	}
}
function RAD_jsnull() { return; }
function RAD_OpenW(pagina,x,y) {
   if (document.F) {
	objForm=document.F;
	if (objForm.elements) for(var i=0;i<objForm.elements.length; i++) {
		if (objForm[i].value!='' && objForm[i].name.substring(0,3)=='V0_') {
			if (objForm[i].value.length<15) pagina+='&P'+objForm[i].name+'='+escape(objForm[i].value).substring(0,15);
		}
	}
   }
   if (String(x)=='undefined') x=0;
   if (String(y)=='undefined') y=0;
   x=Math.abs(x);
   y=Math.abs(y);
   if (x==1 && y==1) {
	maximized=true;
	x=0; y=0;
   }
   else maximized=false;
";
if (_DEF_POPUP_MARGIN!="" && _DEF_POPUP_MARGIN!="_DEF_POPUP_MARGIN") {
    if (_DEF_POPUP_MARGIN=="SUBMODAL") {
	echo "   if (x>screen.width) x=screen.width;
                 if (x==0) x=screen.width/2;
                 if (y>screen.height) {
                     y=screen.height-50;
                 }
		 if (y==0) y=screen.height/2;
                 //if(pagina.indexOf('&subbrowse=')<0 && pagina.indexOf('&subbrowseSID=')<0) {
                 if(pagina.indexOf('&subbrowseSID=')<0) {
		     if (pagina.indexOf('func=search_js')<0) scroll(0,0);
	             showPopWin(pagina, x, y, null,true,maximized);
	             return;
                 }
        ";
    //} else if (_DEF_POPUP_MARGIN>0) {
//x=x-120;
//if (y>screen.height/2) y=screen.height/2;
    }
    if (_DEF_POPUP_MARGIN>0) {
        echo "  if (x=='0') x=screen.width-"._DEF_POPUP_MARGIN.";\n  if (y=='0') y=screen.height-"._DEF_POPUP_MARGIN.";";
    } else if (_DEF_POPUP_MARGIN=="0" || _DEF_POPUP_MARGIN=="SUBMODAL") {
        echo "  if (x=='0') x=screen.availWidth;\n  if (y=='0') y=screen.availHeight-60;";
    } else {
	echo "  if (document.getElementById) {\n	RAD_showL(pagina,x,y);\n	return;\n  }\n";
        echo "  if (x=='0') x=screen.availWidth;\n  if (y=='0') y=screen.availHeight;";
    }
} else {
        echo "  if (x=='0') x=screen.width-50;\n  if (y=='0') y=screen.height-50;";
}
echo "
// x=screen.width/2;
// y=screen.height/2;
  if(window.screen){
    var Left=(screen.width-x)/2;
    var Top=(screen.height-y)/2;
    pos=',left='+Left+',top='+Top;
//    pos=',screenX='+Left+',screenY='+Top;
  }
  params='width='+x+',height='+y+',dependent=yes,alwaysRaised=yes,resizable=".$RAD_OpenW_scrollbars.",scrollbars=yes,status=no,toolbar=no,menubar=no,titlebar=no'+pos;
  var wh=window.open(pagina,'_blank',params);
  //return wh;
}";
if (_DEF_POPUP_MARGIN=="SUBMODAL" && $func!="search_js") echo "
function closePop() {
        parent.hidePopWin();
        return true;
}
";
echo "
function RAD_CloseW(reloadparent){
";
if (_DEF_POPUP_MARGIN=="SUBMODAL") {
	echo " if (parent) {\n";
	echo "  if (reloadparent!=false) parent.location.href=window.top.location.href;\n";
	echo "  parent.hidePopWin();\n";
	echo " } else {\n";
	echo "  if (reloadparent!=false) window.top.location.href=window.top.location.href;\n";
	echo "  window.top.hidePopWin();\n";
	echo " }\n";
} else {
	echo " if (reloadparent!=false) {\n";
	echo "  if (window.opener) {\n";
	echo "    var urlOpener=window.opener.location.href;\n";
	echo "    if (urlOpener.indexOf('#')>0) { urlOpener=urlOpener.substr(0,urlOpener.indexOf('#')); }\n";
	echo "    window.opener.location.href=urlOpener;\n";
	echo "  } else { top.location.href=top.location.href; }\n";
	echo " }\n";
	echo " if (window.opener) window.close();\n";
	echo " else top.RAD_hideL();\n";
}
echo "
}
function RAD_getScrollY() {
    var sy = 0;
    if (document.documentElement && document.documentElement.scrollTop)
	sy = document.documentElement.scrollTop;
    else if (document.body && document.body.scrollTop)
	sy = document.body.scrollTop;
    else if (window.pageYOffset)
	sy = window.pageYOffset;
    else if (window.scrollY)
	sy = window.scrollY;
    return sy;
}
function RAD_showL(url,x,y){
   if (String(x)=='undefined') x=0;
   if (String(y)=='undefined') y=0;
   x=Math.abs(x);
   y=Math.abs(y);
";
if (_DEF_POPUP_MARGIN!="" && _DEF_POPUP_MARGIN!="_DEF_POPUP_MARGIN") {
    if (_DEF_POPUP_MARGIN>0) {
	echo "  if (x=='0') x=screen.width-"._DEF_POPUP_MARGIN.";\n  if (y=='0') y=screen.height-"._DEF_POPUP_MARGIN.";";
    } else {
	echo "  if (x=='0') x=screen.availWidth;\n  if (y=='0') y=screen.availHeight;";
    }
} else {
	echo "  if (x=='0') x=screen.width-50;\n  if (y=='0') y=screen.height-50;";
}
echo "
   y=y+15;
   if (x>screen.width) x=screen.width;
   if (y>screen.height) y=screen.height;
   var Left=0; var Top=0;
   if(window.screen){
	Left=(screen.availWidth-x)/2;
	Top=(screen.availHeight-y)/2-screen.availTop-20;
    }
    sy=RAD_getScrollY();
    Top=Top+sy;
    //Top=sy+10;
    Top=Math.floor(Top);
    Left=Math.floor(Left);
    if (document.getElementById) {
	//document.body.style.opacity=0.2;
	//document.body.style.backgroundImage='url(images/black.gif)';
	document.getElementById('RAD_X').style.opacity = 0.2;
	document.getElementById('RAD_X').style.backgroundImage = 'url(images/black.gif)';
	//document.body.style.display='none';
	document.getElementById('RAD_L').style.visibility = 'visible';
	document.getElementById('RAD_L').style.display = 'block';
	document.getElementById('RAD_L').style.opacity = 1;
	document.getElementById('RAD_IFL').style.opacity = 1;
	document.getElementById('RAD_L').style.backgroundImage = '';
	document.getElementById('RAD_IFL').style.backgroundImage = '';
	if (x>0) {
		document.getElementById('RAD_L').style.width = x;
		document.getElementById('RAD_IFL').style.width = x;
	}
	if (y>0) {
		document.getElementById('RAD_L').style.height = y;
		document.getElementById('RAD_IFL').style.height = y-16;
	}
	document.getElementById('RAD_L').style.left = Left;
	document.getElementById('RAD_L').style.top = Top;
	document.getElementById('RAD_IFL').src=url+'&RAD_L=x';
    } else {
	alert('Esta facilidad solo funciona en navegadores que soportan DOOM.');
    }
}
function RAD_hideL(){
    document.body.style.opacity=1;
    document.body.style.backgroundImage='';
    document.getElementById('RAD_X').style.opacity = 1;
    document.getElementById('RAD_X').style.backgroundImage = '';
    if (document.getElementById) {
	document.getElementById('RAD_L').innerHTML = '<div align=right><a href=\"javascript:RAD_hideL();\"><img border=0 src=\"".$dir_root."images/close.gif\">"._DEF_NLSClose."</a> </div><iframe width=\"100%\" height=\"80%\" id=\"RAD_IFL\" name=\"RAD_IFL\" src=\"\" marginheight=0 marginwidth=0 frameborder=0></iframe>';
	document.getElementById('RAD_L').style.visibility = 'hidden';
	document.getElementById('RAD_L').style.display = 'none';
	document.getElementById('RAD_L').style.left = '25%';
	document.getElementById('RAD_L').style.top = '25%';
	document.getElementById('RAD_L').style.width = '50%';
	document.getElementById('RAD_L').style.height = '50%';
    } else {
	alert('Esta facilidad solo funciona en navegadores que soportan DOOM.');
    }
}
function RAD_ajaxIncludeURL(url) {
// include a page inside RAD_ajaxIncludeURL('file.htm')
   var page_request = false;
   if (window.XMLHttpRequest) {
      page_request = new XMLHttpRequest();
   } else if (window.ActiveXObject){ // if IE
      try { page_request = new ActiveXObject('Msxml2.XMLHTTP'); }
      catch (e) {
         try { page_request = new ActiveXObject('Microsoft.XMLHTTP'); }
         catch (e){}
      }
   } else return false;
   page_request.open('GET', url, false);
   page_request.send(null);
   if (window.location.href.indexOf('http')==-1 || page_request.status==200) document.write(page_request.responseText);
}
function RAD_ajaxIncludeURLID(url,id) {
// include a page inside RAD_ajaxIncludeURL('file.htm','ajax_id')
   var page_request = false;
   if (window.XMLHttpRequest) {
	  page_request = new XMLHttpRequest();
   } else if (window.ActiveXObject){ // if IE
	  try { page_request = new ActiveXObject('Msxml2.XMLHTTP'); }
	  catch (e) {
	 try { page_request = new ActiveXObject('Microsoft.XMLHTTP'); }
	 catch (e){}
	  }
   } else return false;
   page_request.open('GET', url, false);
   page_request.send(null);
   if (window.location.href.indexOf('http')==-1 || page_request.status==200) document.getElementById(id).innerHTML = page_request.responseText;	   
   //if (window.location.href.indexOf('http')==-1 || page_request.status==200) document.write(page_request.responseText);
}
/* ]]> */
</script>
";
}

// self.moveTo(0,0);self.resizeTo(screen.availWidth,screen.availHeight);setInterval("x()",10);setInterval("y()",500000);self.focus();
//function x(){}
//function y(){self.focus()};
   if (_DEF_noCalendar!="x" && _DEF_noHeadMeta!="x" || (isset($headeroff)) && ($headeroff=="x")){
     if (($V_idmod!="" || $V_dir!="")&&($subfunc!="report")) echo "\n<script type='text/javascript' src='".$dir_root."images/calendar/calendar.js'></script>\n";
   }
   echo $RAD_headHTML;
// if ($func!="search_js") echo "\n</head>\n\n";
   if (_DEF_noHeadTitle!="x" && _DEF_noHeadMeta!="x" && _DEF_noHeadJavascript!="x" && _DEF_noHeadCSS!="x" && $V_idmod=="" && $V_dir=="") echo "\n</head>\n\n";
   if ((!isset($headeroff)) || ($headeroff=="")) {
      themeheader();
      blocks("header");
//    if (eregi("index.php",$PHP_SELF)) blocks("center");
      if ($V_index==1) blocks("center");
   } else {
/*
      if (_DEF_noHeadCSS=="x" || (isset($headeroff)) && ($headeroff=="x")){
	   $TMP_filecss="themes/$SESSION_theme/style.php";
	   if (!file_exists($TMP_filecss)) {
	      $TMP_filecss="themes/$SESSION_theme/style.css";
	   }
	   if (!file_exists($TMP_filecss)) {
	      $TMP_filecss="themes/$SESSION_theme/style/style.css";
	   }
	   if ($V_typePrint=="" && $V_typeSend=="" && $subfunc!="report") {
	      if (file_exists($TMP_filecss)) {
	         echo "<link rel=\"StyleSheet\" href=\"".$dir_root."$TMP_filecss\" type=\"text/css\" />\n";
	      }
	   } else {
	       echo "\n<style type='text/css'>\n";
	       if ($TMP_filecss=="themes/$SESSION_theme/style.php") include($TMP_filecss);
	       else @readfile($TMP_filecss);
	       echo "\n</style>\n";
	   }
      }
*/
      $TMP_DEF_HEAD="";
      if (_DEF_HEAD!="" && _DEF_HEAD!="_DEF_HEAD") $TMP_DEF_HEAD=_DEF_HEAD;
//    if ($func!="search_js") echo "\n<body LEFTMARGIN=0 TOPMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0>\n\n";
      if (($subfunc=="browse"||$subfunc=="search_js") && $headeroff!="" && $menuoff!="") echo "\n<! body >\n";
      else if ($V_idmod=="" && $V_dir=="") echo "\n<body LEFTMARGIN=0 TOPMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0>\n\n".$TMP_DEF_HEAD;
      else if ($func=="help" || $subfunc=="help") echo "\n<body OnBlur='javascript:window.close();' LEFTMARGIN=0 TOPMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0>\n\n".$TMP_DEF_HEAD;
      else if ($func!="search_js") echo "\n</head>\n<body LEFTMARGIN=0 TOPMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0>\n\n".$TMP_DEF_HEAD;
   }
   if (($V_idmod!="" || $V_dir!="")&&($subfunc!="report")&&($V_typePrint=="")) echo "\n<noscript>Se requiere Javascript para las funciones especiales.</noscript>\n";

   if ($V_typePrint=="" && $V_typeSend=="" && $subfunc!="report" && $RAD_nodiv=="" && _DEF_RAD_nodiv=="") {
      echo "\n<div id='RAD_L' style='background-color:white; border:solid 1px; border-color:navy; padding:0; position:absolute; left:25%; top:25%; width:50%; height:50%; visibility: hidden; display:none;'>
<div align=right><a href=\"javascript:RAD_hideL();\"><img border=0 src='".$dir_root."images/close.gif'>"._DEF_NLSClose."</a> </div><iframe width='100%' height='80%' id='RAD_IFL' name='RAD_IFL' src='' marginheight='1' marginwidth='1' frameborder='0'></iframe></div>\n";
		echo "<div id='RAD_X'>\n";
   }
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function footmsg() {
   //echo "<center><font class=\"tiny\">"._DEF_FOOT."</font></center>\n";
   echo "\n"._DEF_FOOT."\n";
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function foot() {
global $bodyoff, $footeroff, $footer, $PHPSESSID, $SESSION_type, $SESSION_count, $HTTP_COOKIE_VARS, $subbrowseSID, $RAD_nodiv;

   //Grabamos el registro de log en caso de existir _DEF_dblogname.
   //Lo hacemos al final para no modificar los valores de sql_insert_id()
   if (_DEF_dblogname!="" && _DEF_dblogname!="_DEF_dblogname") RAD_grabaLog("","","","","","","",1);

   if ($subbrowseSID!="" || $bodyoff!="") return;
   if (_DEBUG || $footer=="DEBUG") {
      echo "SESSION_type=".$SESSION_type."<br>\n";
      echo "<a href=\"$PHP_SELF\">SID=".SID."<br>PHPSESSID=".$PHPSESSID."<br>SESSION_count=".$SESSION_count."<br>COOKIE=".$HTTP_COOKIE_VARS[PHPSESSID]."</a><br>\n";
      $DEBUG_vars = Array('GET', 'POST', 'COOKIE', 'SERVER', 'ENV', 'REQUEST', 'SESSION');
      for($i=0; $i<sizeof($DEBUG_vars); $i++) {
              global ${"HTTP_{$DEBUG_vars[$i]}_VARS"};
              if(is_array(${"HTTP_{$DEBUG_vars[$i]}_VARS"})) {
echo "<hr>".$DEBUG_vars[$i]."<br>";
                      foreach(${"HTTP_{$DEBUG_vars[$i]}_VARS"} as $var=>$value) {
                              if(!is_array($var)) {
                  echo $var."=".$value."<br>\n";
                              } else {
                                     foreach($var as $var2=>$value2) {
                     if (is_array($var2)) {
                        echo $var."[";
                        print_r($var2);
                        echo "]=".$value."<br>\n";
                     } else { echo $var."[".$var2."]=".$value."<br>\n"; }
                                      }
               }
                      }
              }
      }
   }
   if ($V_typePrint=="" && $V_typeSend=="" && $subfunc!="report" && $RAD_nodiv=="" && _DEF_RAD_nodiv=="") echo "</div>"; // RAD_X
   if ((!isset($footeroff)) || ($footeroff=="")) {
      themefooter();
      blocks("footer");
   }
   if (_DEF_COD_ANALYTICS!="" && _DEF_COD_ANALYTICS!="_DEF_COD_ANALYTICS") googleAnalytics(_DEF_COD_ANALYTICS);
   if (_DEF_noEtiquetasCierre!="x"){
   echo "</body>\n";
   echo "</html>";
  }
}

/////////////////////////////////////////////////////////////////////////////////////
function RAD_verifyContent($cmdSQL) {
// verificar palabras soeces
    if (_DEF_NoCheckBadWords==true) return $cmdSQL;
    $TMP_prohibidas = fBadWords();
    $cadenaSQL=$cmdSQL;
    $cadenaSQL=strtolower($cadenaSQL);
    $cadenaSQL=str_replace(".", "", $cadenaSQL);
    $cadenaSQL=str_replace("\n", "", $cadenaSQL);
    $cadenaSQL=str_replace("\r", "", $cadenaSQL);
    $cadenaSQL=str_replace(" ", "", $cadenaSQL);
    $cadenaSQL=str_replace(",", "", $cadenaSQL);
    $cadenaSQL=str_replace(";", "", $cadenaSQL);
    $cadenaSQL=str_replace("(", "", $cadenaSQL);
    $cadenaSQL=str_replace(")", "", $cadenaSQL);
    $cadenaSQL=str_replace("]", "", $cadenaSQL);
    $cadenaSQL=str_replace("[", "", $cadenaSQL);
    $cadenaSQL=str_replace("{", "", $cadenaSQL);
    $cadenaSQL=str_replace("}", "", $cadenaSQL);
    $cadenaSQL=str_replace("+", "", $cadenaSQL);
    $cadenaSQL=str_replace("-", "", $cadenaSQL);
    $cadenaSQL=str_replace("*", "", $cadenaSQL);
    $cadenaSQL=str_replace("/", "", $cadenaSQL);
    $cadenaSQL=str_replace("\\", "", $cadenaSQL);
    $cadenaSQL=str_replace(":", "", $cadenaSQL);
    $cadenaSQL=str_replace("'", "", $cadenaSQL);
    $cadenaSQL=str_replace("\"", "", $cadenaSQL);
    $cadenaSQL=str_replace("`", "", $cadenaSQL);
    $cadenaSQL=str_replace("!", "", $cadenaSQL);
    $cadenaSQL=str_replace("?", "", $cadenaSQL);
    $cadenaSQL=str_replace("&", "", $cadenaSQL);
    $cadenaSQL=str_replace("=", "", $cadenaSQL);
    $cadenaSQL=str_replace("%", "", $cadenaSQL);
    $cadenaSQL=str_replace("^", "", $cadenaSQL);
    $cadenaSQL=str_replace("@", "", $cadenaSQL);
    $cadenaSQL=str_replace("#", "", $cadenaSQL);
    $cadenaSQL=str_replace("|", "", $cadenaSQL);
    $cadenaSQL=str_replace("?", "", $cadenaSQL);
    $cadenaSQL=str_replace("&#189;", "", $cadenaSQL);
    $cadenaSQL=str_replace("&#189;", "", $cadenaSQL);
    $cadenaSQL=str_replace("<", "", $cadenaSQL);
    $cadenaSQL=str_replace(">", "", $cadenaSQL);
    foreach($TMP_prohibidas as $TMP=>$palabrasoez) {
   if (strpos($cadenaSQL,$palabrasoez)>-1) {
       echo "<script type='text/javascript'>\nalert('"._NOT_BAD_WORDS."');\n</script>";
//     die;
   }
    }
    return $cmdSQL;
}
/////////////////////////////////////////////////////////////////////////////////////
function fBadWords() {
return array(" puta","mierda","co&#189;o","polla","poya",
   "conho","....otro.tacos.....");
}

?>
