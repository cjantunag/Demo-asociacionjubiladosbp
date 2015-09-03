<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}

if ($V_mod=="sendfile") $V_mod="editfile";
if ($V_mod=="sendbolsa") $V_mod="editbolsa";
if ($V_mod=="sendlink") $V_mod="editlink";
if ($V_mod=="sendcontent") $V_mod="editcontent";
if ($V_mod=="sendmesg") $V_mod="editmesg";
if ($V_mod=="sendforum") $V_mod="editforum";
if ($V_mod=="sendnews") $V_mod="editnews";
if ($V_mod=="sendwork") $V_mod="editwork";
if ($V_mod=="senddict") $V_mod="editdict";
if ($V_mod=="sendworkfolder") $V_mod="editworkfolder";

if (eregi("google",getenv("HTTP_USER_AGENT")) && $V_mod=="showcalendar") die();
if (eregi("yahoo",getenv("HTTP_USER_AGENT")) && $V_mod=="showcalendar") die();
 
//if (file_exists("modules/".$V_dir."/language/lang-".$HTTP_SESSION_VARS["SESSION_lang"].".php")) {
//	$title=""; $titleNLS="";
//	include("modules/".$V_dir."/language/lang-".$HTTP_SESSION_VARS["SESSION_lang"].".php");
//	$titleNLS=$title;
//}

if ($dbusername=="") $dbusername=_DEF_dbuname;
if ($dbpassword=="") $dbpassword=_DEF_dbpass;
if ($hostname=="") $hostname=_DEF_dbhost;

?>
