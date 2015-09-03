<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: ../index.php");
    die();
}
    $content = "";
    $title = "";

    if (!RAD_existTable(_DBT_STATS)) return;

    if (getenv("REMOTE_ADDR")==getenv("SERVER_ADDR") && $subbrowseSID!="") return "";

global $RAD_dbi, $HTTP_SESSION_VARS, $HTTP_GET_VARS, $HTTP_POST_VARS, $HTTP_COOKIE_VARS;

    $TMP_url="";
//    $TMP_url=getenv("REQUEST_URI");
    if (count($HTTP_GET_VARS)>0) {
	foreach ($HTTP_GET_VARS as $key=>$val) {
		if (is_array($key) || is_array($val)) continue;
		if ($key!="") $key=urlencode($key);
		if (strlen($val)>25) $val=substr($val,0,25)."...";
		if ($val!="") $val=urlencode($val);
		$TMP_url.="&".$key."=".$val;
	}
    }
    if (count($HTTP_POST_VARS)>0) {
	foreach ($HTTP_POST_VARS as $key=>$val) {
		if (is_array($key) || is_array($val)) continue;
		if ($key!="") $key=urlencode($key);
		if (strlen($val)>25) $val=substr($val,0,25)."...";
		if ($val!="") $val=urlencode($val);
		$TMP_url.="&".$key."=".$val;
	}
    }
    $TMP_level_stat=_DEF_LEVEL_STAT;

    if (eregi("bot", getenv("HTTP_USER_AGENT"))) return ""; // Ignore robots
// Get the Web Browser
    $TMP_browser=getenv("HTTP_USER_AGENT");
    if((ereg("Nav", getenv("HTTP_USER_AGENT"))) || (ereg("Gold", getenv("HTTP_USER_AGENT"))) || (ereg("X11", getenv("HTTP_USER_AGENT"))) || (ereg("Mozilla", getenv("HTTP_USER_AGENT"))) || (ereg("Netscape", getenv("HTTP_USER_AGENT"))) AND (!ereg("MSIE", getenv("HTTP_USER_AGENT")) AND (!ereg("Konqueror", getenv("HTTP_USER_AGENT"))))) $TMP_browsertype = "Netscape";
    elseif(ereg("Opera", getenv("HTTP_USER_AGENT"))) $TMP_browsertype = "Opera";
    elseif(ereg("MSIE", getenv("HTTP_USER_AGENT"))) $TMP_browsertype = "MSIE";
    elseif(ereg("Lynx", getenv("HTTP_USER_AGENT"))) $TMP_browsertype = "Lynx";
    elseif(ereg("WebTV", getenv("HTTP_USER_AGENT"))) $TMP_browsertype = "WebTV";
    elseif(ereg("Konqueror", getenv("HTTP_USER_AGENT"))) $TMP_browsertype = "Konqueror";
    elseif((eregi("bot", getenv("HTTP_USER_AGENT"))) || (ereg("Google", getenv("HTTP_USER_AGENT"))) || (ereg("Slurp", getenv("HTTP_USER_AGENT"))) || (ereg("Scooter", getenv("HTTP_USER_AGENT"))) || (eregi("Spider", getenv("HTTP_USER_AGENT"))) || (eregi("Infoseek", getenv("HTTP_USER_AGENT")))) $TMP_browsertype = "Bot";
    else $TMP_browsertype = "Other";

// Get the Operating System data
    if(ereg("Win", getenv("HTTP_USER_AGENT"))) $TMP_os = "Windows";
    elseif((ereg("Mac", getenv("HTTP_USER_AGENT"))) || (ereg("PPC", getenv("HTTP_USER_AGENT")))) $TMP_os = "Mac";
    elseif(ereg("Linux", getenv("HTTP_USER_AGENT"))) $TMP_os = "Linux";
    elseif(ereg("FreeBSD", getenv("HTTP_USER_AGENT"))) $TMP_os = "FreeBSD";
    elseif(ereg("SunOS", getenv("HTTP_USER_AGENT"))) $TMP_os = "SunOS";
    elseif(ereg("IRIX", getenv("HTTP_USER_AGENT"))) $TMP_os = "IRIX";
    elseif(ereg("BeOS", getenv("HTTP_USER_AGENT"))) $TMP_os = "BeOS";
    elseif(ereg("OS/2", getenv("HTTP_USER_AGENT"))) $TMP_os = "OS/2";
    elseif(ereg("AIX", getenv("HTTP_USER_AGENT"))) $TMP_os = "AIX";
    else $TMP_os = "Other";

// Referer
    $TMP_referer = getenv("HTTP_REFERER");
    if ($TMP_referer=="" OR eregi("^unknown", $TMP_referer) OR eregi("\[unknown", $TMP_referer) OR substr("$TMP_referer",0,strlen(_DEF_URL))==_DEF_URL OR eregi("^bookmark",$TMP_referer)) {
        $TMP_referer="";
    }

    $TMP_IP=getenv("REMOTE_ADDR");
    $TMP_CLIENT_IP=getenv("HTTP_CLIENT_IP");
    if ($TMP_CLIENT_IP!="") $TMP_IP=$TMP_CLIENT_IP."/".$TMP_IP;

    if ($HTTP_SESSION_VARS["SESSION_user"]!="") $TMP_username=base64_decode($HTTP_SESSION_VARS["SESSION_user"]);
    else $TMP_username="";

    $TMP_session=session_id();
    $TMP_current_date = getdate();
    if($TMP_current_date["hours"]<10) $TMP_current_date["hours"]="0".$TMP_current_date["hours"];
    $TMP_hour=$TMP_current_date["hours"];
    $TMP_mday=$TMP_current_date["mday"];
    $TMP_wday=$TMP_current_date["wday"];
    $TMP_year=$TMP_current_date["year"];
    $TMP_month=$TMP_current_date["mon"];
    $TMP_numtime=time();

    $TMP_result=sql_query("SELECT * FROM "._DBT_STATS." WHERE "._DBF_S_SESSION."='$TMP_session'",$RAD_dbi);
    $TMP_row = sql_fetch_array($TMP_result, $RAD_dbi);
    if ($TMP_row[_DBF_S_SESSION]!=$TMP_session) {
	if ($HTTP_COOKIE_VARS["PHPSESSID_last"]!="" && $HTTP_COOKIE_VARS["PHPSESSID"]!=$HTTP_COOKIE_VARS["PHPSESSID_last"]) $TMP_session_last=$HTTP_COOKIE_VARS["PHPSESSID_last"]."\n";
        if(_DEF_dbreadonly!="1") sql_query("INSERT INTO "._DBT_STATS." SET "._DBF_S_USER."='$TMP_username', "._DBF_S_SESSION."='$TMP_session', "._DBF_S_REFERER."='$TMP_referer', "._DBF_S_IP."='$TMP_IP', "._DBF_S_OS."='$TMP_os', tipobrowser='$TMP_browsertype', "._DBF_S_BROWSER."='$TMP_browser', "._DBF_S_HITS."='1', "._DBF_S_YEAR."='$TMP_year', "._DBF_S_MONTH."='$TMP_month', "._DBF_S_DAY."='$TMP_mday', "._DBF_S_WDAY."='$TMP_wday', "._DBF_S_HOUR."='$TMP_hour', "._DBF_S_INITIME."='$TMP_numtime', "._DBF_S_ENDTIME."='$TMP_numtime', "._DBF_S_URLS."='session_last=$TMP_session_last\n'",$RAD_dbi);
    } else {
	$TMP_row[_DBF_S_HITS]++;
	$TMP_row[_DBF_S_URLS]=$TMP_row[_DBF_S_URLS]."?".$TMP_url."\n";
	if ($TMP_level_stat=="2") 
    	    $TMP_update.=_DBF_S_USER."='$TMP_username', "._DBF_S_ENDTIME."='$TMP_numtime', "._DBF_S_HITS."='".$TMP_row[_DBF_S_HITS]."', "._DBF_S_URLS."='".$TMP_row[_DBF_S_URLS]."'";
	else if ($TMP_level_stat=="1") 
    	    $TMP_update.=_DBF_S_USER."='$TMP_username', "._DBF_S_ENDTIME."='$TMP_numtime', "._DBF_S_HITS."='".$TMP_row[_DBF_S_HITS]."'";
	else
    	    $TMP_update.=_DBF_S_USER."='$TMP_username', "._DBF_S_ENDTIME."='$TMP_numtime', "._DBF_S_HITS."='".$TMP_row[_DBF_S_HITS]."'";
	if(_DEF_dbreadonly!="1") sql_query("UPDATE "._DBT_STATS." SET ".$TMP_update." WHERE "._DBF_S_ID."='".$TMP_row[_DBF_S_ID]."'", $RAD_dbi);
    }

    if (_DEBUG) {
	$content .= "<center>STAT</center>";
        $content .= "$TMP_username<br>$TMP_session<br>$TMP_referer<br>$TMP_os<br>$TMP_browser<br>$TMP_IP<br>$TMP_year<br>$TMP_month<br>$TMP_mday<br>$TMP_hour<br>";
    }
?>
