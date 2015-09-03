<?php
if(!isset($HTTP_SERVER_VARS)) $HTTP_SERVER_VARS=& $_SERVER;
if(!isset($HTTP_GET_VARS)) $HTTP_GET_VARS=& $_GET;
if(!isset($HTTP_POST_VARS)) $HTTP_POST_VARS=& $_POST;
if(!isset($HTTP_COOKIE_VARS)) $HTTP_COOKIE_VARS=& $_COOKIES;
if(!isset($HTTP_POST_FILES)) $HTTP_POST_FILES=& $_FILES;
if(!isset($HTTP_ENV_VARS)) $HTTP_ENV_VARS=& $_ENV;
//if(!isset($HTTP_SESSION_VARS)) $HTTP_SESSION_VARS=& $_SESSION;
if (_DEBUG=="1") {
      $DEBUG_vars = Array('GET', 'POST', 'COOKIE', 'SERVER', 'ENV', 'REQUEST', 'SESSION');
      for($i=0; $i<sizeof($DEBUG_vars); $i++) {
              global ${"HTTP_{$DEBUG_vars[$i]}_VARS"};
	      echo "<h1>".$DEBUG_vars[$i]."</h1>\n";
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
      die("");
}

if (substr($REQUEST_URI,0,1)=="/") $REQUEST_URI=substr($REQUEST_URI,1);
global $V_dir, $V_mod, $id;
$pars=explode("-",$REQUEST_URI);
if (count($pars)>1) {
	$V_dir="contents";
	$V_mod=$pars[0];
	$pars2=explode(".",$pars[1].".");
	$id=$pars2[0];
	include_once("modules.php");
	return;
}

if (ereg(".xml",$REQUEST_URI)) $TMP_prog="xml.php";
else $TMP_prog="index.php";

$PHP_SELF=$HTTP_SERVER_VARS["PHP_SELF"];
$REQUEST_URI=substr($REQUEST_URI,strlen(dirname($PHP_SELF)));
if (ereg($TMP_prog,$PHP_SELF)) die("Error pagina no encontrada....");

$pars=explode("/",$REQUEST_URI);
if (count($pars)>2) {
	$V_dir=$pars[0];
	$V_mod=$pars[1];
	$pars[2]=str_replace(".html?","&",$pars[2]);
	$pars[2]=str_replace(".html","",$pars[2]);
	$pars[2]=str_replace(".htm?","&",$pars[2]);
	$pars[2]=str_replace(".htm","",$pars[2]);
	$pars[2]=str_replace("?","&",$pars[2]);
        $URL_rest="id=".$pars[2];
}

if ($PHPSESSID=="") $PHPSESSID=$HTTP_COOKIE_VARS["PHPSESSID"];
if ($HTTP_COOKIE_VARS["PHPSESSID"]!="") $PHPSESSID="";
if ($HTTPS_SERVER[HTTPS]!="" || $_SERVER[HTTPS]!="")
	$TMP_URL="https://".$HTTP_SERVER_VARS["HTTP_HOST"].dirname($PHP_SELF)."".$TMP_prog."?";
else
	$TMP_URL="http://".$HTTP_SERVER_VARS["HTTP_HOST"].dirname($PHP_SELF)."".$TMP_prog."?";
if ($V_dir!="") $TMP_URL.="V_dir=".$V_dir."&";
if ($V_mod!="") $TMP_URL.="V_mod=".$V_mod."&";
if ($PHPSESSID!="") $TMP_URL.="PHPSESSID=".$PHPSESSID."&";
// if ($URL_rest!="") $URL_rest="id=".str_replace("?","&",$URL_rest);

if ($TMP_prog=="xml.php")
	include_once("xml.php");
else 
	//echo "<script>\nalert('".$TMP_URL.$URL_rest."');\n</script>\n";
	echo "<script>\nwindow.location.href='".$TMP_URL.$URL_rest."';\n</script>\n";
?>
