<?php
ini_set("short_open_tag", 1);

ob_start();
//error_reporting (E_ERROR);

require_once("mainfile.php");
if ($V_idmod=="" && $V_mod=="") $V_index = 1;
if ($V_dir==" x") { $V_idmod=""; $V_mod=""; $V_dir=""; }
require_once("functions.php");
$SESSION_transtime=RAD_microtime();

$xdbname="";
if ($dbname!="" && $dbname!=_DEF_dbname) $xdbname="&dbname=$dbname";
$SESSION_oldtheme=getSessionVar("SESSION_theme");

// search modules at user home page
$TMP_homepage=homepageUser();
//if ($TMP_homepage!="" && $V_mod!="goto") {
if ($TMP_homepage!="" && $V_mod=="" && $V_idmod=="") {
	Header("Location: ".$TMP_homepage.$SESSION_SID.$xdbname);
	exit;
}
if (($V_dir!=""||$V_idmod!="") && $V_mod!="goto") {
	$V_index = 0;
	include_once("modules.php");
	exit;
}
// search modules at home page
if ($V_mod=="" && $V_dir=="") {
	$V_dir=" x";
	$V_mod=" x";
}
$TMP_oldfooteroff=$footeroff;
include_once("header.php");
if (_DEF_COLUMNS>1) echo "<table cellpadding=0 cellspacing=0 width=100% border=0>";
$headeroff="x";
$footeroff="x";
//$TMP_resultIndex = sql_query("select "._DBF_M_IDMODULE.", "._DBF_M_DIR.", "._DBF_M_FILE.", "._DBF_M_PARAMETERS." from "._DBT_MODULES." WHERE ("._DBF_M_HOME."='1' OR "._DBF_M_HOME."='S') order by "._DBF_M_WEIGHT." ASC", $RAD_dbi);
$TMP_resultIndex = sql_query("select * from "._DBT_MODULES." WHERE "._DBF_M_HOME."='1' order by "._DBF_M_WEIGHT." ASC", $RAD_dbi);

$TMP_cont_home_modules=0;

while($TMP_row=sql_fetch_array($TMP_resultIndex, $RAD_dbi)) {
	if (!ereg(",".$RAD_device.",",",".$TMP_row[device].",") && $TMP_row[device]!="" && $RAD_device!="") continue;
	$TMP_idmod=$TMP_row[_DBF_M_IDMODULE];
	$TMP_name=$TMP_row[_DBF_M_DIR];
	$TMP_file=$TMP_row[_DBF_M_FILE];
	$TMP_params=$TMP_row[_DBF_M_PARAMETERS];
	//if (!is_modulepermitted($TMP_idmod, $TMP_name, $TMP_file)&&!is_admin()) continue;
	if (!is_modulepermitted($TMP_idmod, $TMP_name, $TMP_file)) {
            if ($xajax!='') {
                ob_end_clean();
                $sContentHeader = "Content-type: text/xml;";
                header($sContentHeader);
                $TMP_msg=_ACCESSDENIED.'<br>'._MODULEUSERS;
                $TMP_msg=str_replace('<br>',"\n",$TMP_msg);
                echo '<?xml version="1.0" encoding="utf-8" ?><xjx><cmd n="al"><![CDATA['.$TMP_msg.']]></cmd></xjx>';
                die;
            }
            continue;
        }
	if (_DEF_COLUMNS>1) {
		if ($TMP_cont_home_modules==0) echo "<tr><td valign=top width=50%>";
		else if (($TMP_cont_home_modules%_DEF_COLUMNS)==(_DEF_COLUMNS-1)) echo "</td><td valign=top width=50%>";
		else echo "</td></tr><tr><td valign=top width=50%>";
	}
	$cache=""; $cachefile=""; // clean cache params
	if ($TMP_params!="") {
		$TMP_par=explode("&",$TMP_params);
		if (count($TMP_par)==0) $TMP_par[0]=$TMP_params;
		if (count($TMP_par)>0) {
			for ($kki = 0; $kki < count($TMP_par); $kki++) {
				$TMP_var=explode("=",$TMP_par[$kki]);
				if ($TMP_var[0]!="") {
					${$TMP_var[0]}=$TMP_var[1];
				}
			}
		}
	}

	if (!isset($TMP_file)) { $TMP_file="index"; }
	if (ereg("\.\.",$TMP_name) || ereg("\.\.",$TMP_file)) {
		error(_SECURITY_ERROR."(1)");
	} else {
		$TMP_modfile="modules/$TMP_name/$TMP_file.php";
		if (!file_exists($TMP_modfile)) {
			$TMP_modfile="modules/$TMP_name/$TMP_file";
			if (!file_exists($TMP_modfile)) {
				$TMP_modfile="";
				OpenTable();
				echo "<center>"._HOMEPROBLEMUSER."</center>";
				CloseTable();
			}
		}
		if ($TMP_modfile!="") {
			$V_dir=$TMP_name;
			$V_mod=$TMP_file;
			$V_idmod=$TMP_idmod;
			$RAD_retrievedcache=false; $RAD_CONTENT_MOD="";
			if ($cache>0 && $cachefile!="" && $nocache=="") { // Falta comprobar nocache
				if ($dbname!="") $TMP_dbname=$dbname;
				else $TMP_dbname=_DEF_dbname;
				$TMP_filemodcache="files/".$TMP_dbname."/".$cachefile.".htm";  // Cache filename
				if (file_exists($TMP_filemodcache)) {
					$TMP_dif=time()-filemtime($TMP_filemodcache);
					if ($TMP_dif<$cache) {
						if ($fp=@fopen($TMP_filemodcache,"r")) {
							/*$content="\n<! cached from $TMP_filemodcache >\n";*/
							$RAD_retrievedcache=true;
							while(!feof($fp)) { $RAD_CONTENT_MOD.=fgets($fp,4096); }
							fclose($fp);
							echo $RAD_CONTENT_MOD;
						}
					} else {
						unlink($TMP_filemodcache);
					}
				}
				if ($RAD_retrievedcache==false) {
					$RAD_CONTENT_PREFIX=ob_get_contents();
					ob_end_clean();
					ob_start();
				}
			}
			if (ereg("\.\.",$TMP_modfile) || ereg(":",$TMP_modfile)) {
				$TMP_err=_SECURITY_ERROR."(3) [$TMP_modfile]";
				RAD_logError("ERR: ".$TMP_err);
				error($TMP_err);
			}
			$TMP_modfile=str_replace(":","",$TMP_modfile);
			$TMP_modfile=str_replace("..","",$TMP_modfile);
			global $bodyoff;
			$bodyoff="x";
			/*if ($RAD_retrievedcache==false) echo "<! $TMP_modfile $TMP_params >";*/
			if ($RAD_retrievedcache==false) include($TMP_modfile);
			if ($cache>0 && $cachefile!="" && $nocache=="" && $RAD_retrievedcache==false) {
				$RAD_CONTENT_MOD=ob_get_contents();
				ob_end_clean();
				ob_start();
				if ($dbname!="") $TMP_dbname=$dbname;
				else $TMP_dbname=_DEF_dbname;
				$TMP_filemodcache="files/".$TMP_dbname."/".$cachefile.".htm";  // Cache filename
				if ($fp=@fopen($TMP_filemodcache,"w")) { fwrite($fp,$RAD_CONTENT_MOD); fclose($fp); }
				echo $RAD_CONTENT_PREFIX.$RAD_CONTENT_MOD;
			}
			$bodyoff=$TMP_bodyoff;
			$TMP_cont_home_modules++;
		}
	}
}
if (_DEF_COLUMNS>1) {
	if ($TMP_cont_home_modules==0) echo "</table>";
	else if (($TMP_cont_home_modules%_DEF_COLUMNS)==(_DEF_COLUMNS-1)) echo "</td><td></td></tr></table>";
	else echo "</td></tr></table>";
}
//if (!is_user()) {
//    $TMP=include_once("login.php");
//    echo $TMP;
//}
$footeroff=$TMP_oldfooteroff;
include("footer.php");

$TMP=ob_get_contents();
ob_end_clean();

ob_start("ob_gzhandler");
//if (_CHARSET=="ISO-8859-1") $TMP=utf8_decode($TMP);
//if (_CHARSET=="UTF-8") $TMP=utf8_encode($TMP);
echo $TMP;
ob_end_flush();
?>
