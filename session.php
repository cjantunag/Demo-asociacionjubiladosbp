<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}

////////////////////////////////////////////////////////////////////////
if (!function_exists("session_is_registered")) {
    function session_is_registered($var) {
	global $HTTP_SESSION_VARS, $_SESSION;
	if (isset($_SESSION['$var'])) return true;
	else if (isset($HTTP_SESSION_VARS['$var'])) return true;
	return false;
    }
}

if (!defined("_DEF_maxInactivityTime")) define(_DEF_maxInactivityTime,"36000"); // maxInactivityTime in seconds (1 hour)
else if (_DEF_maxInactivityTime=='') define(_DEF_maxInactivityTime,"36000"); // maxInactivityTime in seconds (1 hour)

//define(_DEF_maxInactivityTime,"99999999"); // No maxInactivityTime

global $HTTP_COOKIE_VARS, $_COOKIE, $HTTP_SESSION_VARS, $_SESSION, $RAD_cfg, $RAD_newtheme, $SESSION_theme;
ini_set('session.bug_compat_42', 0);
//ini_set("suhosin.simulation","1");

IPControl();

////////////////////////////////////////////////////
// Session Control
////////////////////////////////////////////////////
if ($SESSION_type=="") $SESSION_type="cookie";
//$SESSION_type="url";

if (_DEF_SEC_LEVEL!="" && _DEF_SEC_LEVEL!="_DEF_SEC_LEVEL") $SESSION_seclevel=_DEF_SEC_LEVEL;
else $SESSION_seclevel="High";
if (_DEF_maxInactivityTime=="" || _DEF_maxInactivityTime=="_DEF_maxInactivityTime") $SESSION_maxInactivityTime=36000; // 600 minutes Inactivity timeout for user sessions
else $SESSION_maxInactivityTime=_DEF_maxInactivityTime;
//$SESSION_maxInactivityTime=99999999; // No Inactivity timeout for user sessions


////////////////////////////////////////////////////
switch ($SESSION_seclevel) {
        case 'High':
            $TMP_lifetime = 0;		// Session lasts duration of browser
            break;
        case 'Medium':            
            $TMP_lifetime = 7 * 86400;	// Session lasts seven of days
            break;
        case 'Low':
            $TMP_lifetime = 788940000;	// Session lasts unlimited (currently set to 25 years)
            break;
        default:
            $TMP_lifetime = 0;		// Session lasts duration of browser
            break;
}
if($_REQUEST['PHPSESSID']!="") session_id($_REQUEST['PHPSESSID']);
if ($subbrowseSID!="") {
	if (session_id()!="" && session_id()!=$subbrowseSID) session_destroy();
	if (session_id()!=$subbrowseSID) {
		$PHPSESSID=$subbrowseSID;
		$HTTP_COOKIE_VARS["PHPSESSID"]=$subbrowseSID;
		$_COOKIE["PHPSESSID"]=$subbrowseSID;
		session_id($PHPSESSID);
		session_set_save_handler ("RAD_open_session", "RAD_close_session", "RAD_read_session", "RAD_write_session", "RAD_destroy_session", "RAD_gc_session");
	}
	session_start();
}

ini_set("register_globals", 1);
ini_set("session.bug_compat_42", 0);
ini_set('session.cookie_lifetime', $TMP_lifetime);
ini_set('session.cookie_path', dirname($PHP_SELF));
//ini_set('session.cookie_path', $GLOBALS["SERVER_PORT"].dirname($PHP_SELF));

//if ($RAD_cfg!="") ini_set('session.cookie_path', $RAD_cfg);
//else ini_set('session.cookie_path', $HTTP_SESSION_VARS["SESSION_cfg"]); 
ini_set("session.name", "PHPSESSID");
ini_set('session.auto_start', 0);	// Auto-start session
ini_set("session.gc_maxlifetime", $SESSION_maxInactivityTime); // Inactivity timeout for user sessions

ini_set("magic_quotes_gpc", 1);
ini_set("magic_quotes_runtime", 0);

if ($SESSION_type=="cookie") {
	@ini_set('session.use_cookies', 1);	// Use cookie to store the session ID
	@ini_set('session.use_trans_sid', 0);	// Stop adding SID to URLs
} else {
	@ini_set("session.use_cookies", 0);
	@ini_set("session.use_trans_sid", 1);
}

if ($subbrowseSID=="") session_start();
if(!isset($HTTP_SESSION_VARS)) $HTTP_SESSION_VARS=& $_SESSION;
if ($HTTP_COOKIE_VARS["PHPSESSID_last"]=="" && $_COOKIE["PHPSESSID_last"] && $PHPSESSID!="") {
	setcookie("PHPSESSID_last", $PHPSESSID, time()+(60*60*24*365), dirname($PHP_SELF));
	//setcookie("PHPSESSID_last", $PHPSESSID, time()+(60*60*24*365), $GLOBALS["SERVER_PORT"].dirname($PHP_SELF));
	$HTTP_COOKIE_VARS["PHPSESSID_last"]=$PHPSESSID;
	$_COOKIE["PHPSESSID_last"]=$PHPSESSID;
}
if (($HTTP_COOKIE_VARS["PHPSESSID_last"]!=""||$_COOKIE["PHPSESSID_last"]!="") && $HTTP_SESSION_VARS["SESSION_remember"]!="") {
	$SESSION_maxInactivityTime=60*60*24*365; 
	ini_set("session.gc_maxlifetime", $SESSION_maxInactivityTime);
}

if ($SESSION_type=="cookie" && $HTTP_COOKIE_VARS["PHPSESSID"]=="" && $_COOKIE["PHPSESSID"]=="") { 
	if (eregi("google",getenv("HTTP_USER_AGENT")) || eregi("robot",getenv("HTTP_USER_AGENT"))) $SESSION_type="cookie"; 
	else $SESSION_type="url"; // it the browser don't support cookies then we use url-rewriting
}

if ($SESSION_type=="cookie") {
	@ini_set('session.use_cookies', 1);	// Use cookie to store the session ID
	@ini_set('session.use_trans_sid', 0);	// Stop adding SID to URLs
	if ($HTTP_COOKIE_VARS["PHPSESSID_last"]=="" && $_COOKIE["PHPSESSID_last"]=="" && $PHPSESSID!="") {
        	setcookie("PHPSESSID_last", $PHPSESSID, time()+(60*60*24*365), dirname($PHP_SELF));
        	//setcookie("PHPSESSID_last", $PHPSESSID, time()+(60*60*24*365), $GLOBALS["SERVER_PORT"].dirname($PHP_SELF));
	        $HTTP_COOKIE_VARS["PHPSESSID_last"]=$PHPSESSID;
	        $_COOKIE["PHPSESSID_last"]=$PHPSESSID;
	}
} else {
	@ini_set("session.use_cookies", 0);
	@ini_set("session.use_trans_sid", 1);
}

////////////////////////////////////////////////////
// Cache Control
////////////////////////////////////////////////////
if ( (!(headers_sent())) && ($func!="print") && _DEF_expireCache=="") {
        header("Expires: Mon, 1 Jul 1970 00:00:00 GMT");
        header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
        header("Cache-Control: no-store, no-cache, private, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
}elseif (_DEF_cacheControl!="") {
    $TMP_expire=gmdate("D, d M Y H:i:s",strtotime(_DEF_expireCache, time()));
    header("Expires: ".$TMP_expire." GMT");
    header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
    header("Cache-Control: private");
    header("Pragma: cache");
}


if (!session_is_registered("SESSION_count")) {
	$SESSION_count=-1;
	setSessionVar("SESSION_count",$SESSION_count,0);
}

// reenter if there is no session variables saved
//if ((!isset($HTTP_SESSION_VARS["SESSION_count"])) || ($HTTP_SESSION_VARS["SESSION_count"]=="") ) {
//	header("Location: index.php?".SID);
//}
//if ((!isset($HTTP_SESSION_VARS["SESSION_count"])) || ($HTTP_SESSION_VARS["SESSION_count"]=="") ) {
//	alert(_SESSION_CREATE_ERROR);
//}

setSessionVar("SESSION_timeLastVisit",$SESSION_timeLastVisit,0);
$SESSION_count=$HTTP_SESSION_VARS["SESSION_count"]+1;
if ($SESSION_timeLastVisit>0 && $SESSION_user!="" && (time()-$SESSION_timeLastVisit)>$SESSION_maxInactivityTime && $op!="logout" && V_mod!="coremods" && $V_mod!="usercontrol") {
	$TMP_cfg = $HTTP_SESSION_VARS["SESSION_cfg"];
	header("Location: index.php?V_dir=coremods&V_mod=usercontrol&V_op=logout&rs=inactivtime&RAD_cfg=".$TMP_cfg);
	RAD_die("Fin de session por inactividad...");
}
if ($RAD_cfg!="" && (isset($HTTP_SESSION_VARS["SESSION_cfg"])) && $HTTP_SESSION_VARS["SESSION_cfg"]!=$RAD_cfg) {
        session_destroy();
	echo "<script type='text/javascript'>\nwindow.location='index.php?RAD_cfg=".$RAD_cfg."';\n</script>\n";
	RAD_die("Cambio de configuracion...");
}
if (!isset($HTTP_SESSION_VARS["SESSION_cfg"])) {
    $SESSION_cfg=$RAD_cfg;
    setSessionVar("SESSION_cfg",$SESSION_cfg,0);
}

$SESSION_timeLastVisit=time();

if ($RAD_newtheme!="") {
	$SESSION_theme=$RAD_newtheme;
	setSessionVar("SESSION_theme",$SESSION_theme,0);
}
//setSessionVar("SESSION_count",$SESSION_count,0);
if (!session_is_registered("SESSION_timeFirstVisit")) {
	$SESSION_timeFirstVisit=time();
	setSessionVar("SESSION_timeFirstVisit",$SESSION_timeFirstVisit,0);
	$SESSION_theme=getSessionVar("SESSION_theme");
	if ($SESSION_theme=="") $SESSION_theme=_DEF_THEME;
	if ($SESSION_theme=="_DEF_THEME") $SESSION_theme="";
	if ($SESSION_theme!="") setSessionVar("SESSION_theme",$SESSION_theme,0);
} else if ($SESSION_theme=="") $SESSION_theme=getSessionVar("SESSION_theme");


setSessionVar("SESSION_DataArray",$SESSION_DataArray,0);
if (!is_array($SESSION_DataArray)) $SESSION_DataArray=array();
// Example: $SESSION_DataArray{"$SESSION_count"}=time();


global $SESSION_blocks_right, $SESSION_blocks_left, $SESSION_SID;
$SESSION_blocks_right=1;
$SESSION_blocks_left=1;

if ($SESSION_type=="url" || $subbrowseSID!="") {
	if (session_id()!="") {
	    $SESSION_SID="&amp;PHPSESSID=".session_id()."&amp;";
	    $PHPSESSID=session_id();
	} else if ($PHPSESSID!="") {
	    $SESSION_SID="&amp;PHPSESSID=".$PHPSESSID."&amp;";
	} else $SESSION_SID="";
	//} else $SESSION_SID="&amp;";
} else { 
	$PHPSESSID=session_id();
	$SESSION_SID="";
	//$SESSION_SID="&";
}

if (!session_is_registered("SESSION_REMOTE_ADDR")) {
	$SESSION_REMOTE_ADDR=getenv("REMOTE_ADDR");
	setSessionVar("SESSION_REMOTE_ADDR",$SESSION_REMOTE_ADDR,0);
	$SESSION_HTTP_REFERER = getenv("HTTP_REFERER");
	setSessionVar("SESSION_HTTP_REFERER",$SESSION_HTTP_REFERER,0);
	$SESSION_HTTP_HOST = $GLOBALS["HTTP_HOST"];
	setSessionVar("SESSION_HTTP_HOST",$SESSION_HTTP_HOST,0);
}
if ($SESSION_REMOTE_ADDR=="") $SESSION_REMOTE_ADDR=getenv("REMOTE_ADDR");
if ($SESSION_REMOTE_ADDR!=getenv("REMOTE_ADDR") && getenv("REMOTE_ADDR")!=getenv("HTTP_HOST") && getenv("REMOTE_ADDR")!=getenv("SERVER_ADDR") && $op!="logout" && V_mod!="coremods" && $V_mod!="usercontrol") {
// hijacking deny
	$TMP_err="REMOTE_ADDR=".getenv("REMOTE_ADDR")."&SESSION_REMOTE_ADDR=".$SESSION_REMOTE_ADDR;
//xxxx	header("Location: index.php?V_dir=coremods&V_mod=usercontrol&V_op=logout&rs=incorrectIP&RAD_cfg=".$TMP_cfg."&".$TMP_err);
}
if ($SESSION_HTTP_HOST!="" && $subbrowseSID=="" && $SESSION_HTTP_HOST!=$GLOBALS["HTTP_HOST"] && $op!="logout" && V_mod!="coremods" && $V_mod!="usercontrol" && $subbrowseSID=="" && $op!="NOTIF") {
	header("Location: index.php?V_dir=coremods&V_mod=usercontrol&V_op=logout&rs=incorrectHTTP_HOST&".urlencode($SESSION_HTTP_HOST)."&".urlencode($GLOBALS["HTTP_HOST"])."&RAD_cfg=".$TMP_cfg);
	RAD_die("Nombre Servidor incorrecto...");
}
$TMP_url=getenv("HTTP_REFERER");
if (strpos($TMP_url,"@")>0) {
	if (strpos($TMP_url,"@")<strpos($TMP_url,"?")) {
// spoofing deny
//		Header("Location: index.php");
    	    RAD_die("URL incorrecta...");
	}
}
////////////////////////////////////////////////////////////////////////
function RAD_open_session ($save_path, $session_name) {
	global $SESSION_save_path, $SESSION_session_name;
	if ($save_path=="") $save_path="/tmp";
	$SESSION_save_path = $save_path;
	$SESSION_session_name = $session_name;
	return(true);
}
function RAD_read_session ($id) {
	global $SESSION_save_path, $SESSION_session_name;
	$sess_file = "$SESSION_save_path/sess_$id";
	if ($fp = @fopen($sess_file, "r")) {
		$sess_data = fread($fp, filesize($sess_file));
		return($sess_data);
	} else {
		$TMP_err="Error on open : $sess_file";
		RAD_logError("ERR: ".$TMP_err);
		echo $TMP_err."<br>";
		return("");
	}
}
function RAD_write_session ($id, $sess_data) { return true; }
function RAD_close_session() { return(true); }
function RAD_destroy_session ($id) { return true; }
function RAD_gc_session ($maxlifetime) { return true; }
////////////////////////////////////////////////////////////////////////
function IPControl() {


////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
// Direcciones IP no permitidas o con redireccion a otra pagina
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////

$IP_NOT_AUTH["191.257.1.1"]="http://www.google.com/";


////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
    $TMP_IP=getenv("REMOTE_ADDR");
    $TMP_CLIENT_IP=getenv("HTTP_CLIENT_IP");

    if ($IP_NOT_AUTH[$TMP_IP]!="") {
	if (strlen($IP_NOT_AUTH[$TMP_IP])>1) Header("Location: ".$IP_NOT_AUTH[$TMP_IP]);
	RAD_die("IP no autorizada...");
    }
    if ($IP_NOT_AUTH[$TMP_CLIENT_IP]!="") {
	if (strlen($IP_NOT_AUTH[$TMP_CLIENT_IP])>1) Header("Location: ".$IP_NOT_AUTH[$TMP_CLIENT_IP]);
	RAD_die("IP no autorizada...");
    }
}
?>
