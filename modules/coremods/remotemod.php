<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: ../../index.php");
    die();
}
	include ("header.php");
global $url, $REQUEST_URI, $PHPSESSID;
//---------------------------------------------------------------------------
$TMP_user=base64_decode($HTTP_SESSION_VARS["SESSION_user"]);
$TMP_pass=base64_decode($HTTP_SESSION_VARS["SESSION_pass"]);

//---------------------------------------------------------------------------
// Register session on remote server
$TMP_URL=$URL."?V_dir=coremods&V_mod=usercontrol&uname=$TMP_user&V_op=login&pass=$TMP_pass&PHPSESSID=$PHPSESSID";
$TMP_file = fopen($TMP_URL, "r");
if (!$TMP_file) die("Error getting : ".$TMP_URL);
$TMP_content = "";
while (!feof($TMP_file)) {
	$TMP_line = fgets($TMP_file, 512000);
//	$TMP_content = $TMP_content.$TMP_line;
}
fclose($TMP_file);
//---------------------------------------------------------------------------
// Get remote URL with PARS urldecoded
$TMP_URL=$URL.$ACTION."?".urldecode($PARS)."&PHPSESSID=".$PHPSESSID;

if ($NEW!="") {
	$TMP_content = "\n\n<script type=text/javascript>\nRAD_OpenW('".$TMP_URL."',,);\n</script>\n\n";
} else {
	$TMP_file = fopen($TMP_URL, "r");
	if (!$TMP_file) die("Error getting : ".$TMP_URL);
	$TMP_content = "";
	while (!feof($TMP_file)) {
		$TMP_line = fgets($TMP_file, 512000);
		$TMP_content = $TMP_content.$TMP_line;
	}
	fclose($TMP_file);
//	$TMP_content = str_replace("function RAD_OpenW","function SubRAD_OpenW",$TMP_content);
//	$TMP_content = str_replace("javascript:RAD_OpenW","javascript:SubRAD_OpenW",$TMP_content);
//	$TMP_content = str_replace("function openW","function SubopenW",$TMP_content);
//	$TMP_content = str_replace("javascript:openW","javascript:SubopenW",$TMP_content);
//	$TMP_content = str_replace("</body>","",$TMP_content);
//	$TMP_content = str_replace("</BODY>","",$TMP_content);
//	$TMP_content = str_replace("</html>","",$TMP_content);
//	$TMP_content = str_replace("</HTML>","",$TMP_content);
//	$TMP_content = str_replace("HREF=\"/","TARGET=_blank HREF=\"/",$TMP_content);
//	$TMP_content = str_replace("menuoff=x&orderby","menuoff=&subfunc=browse&orderby",$TMP_content);
}

echo $TMP_content;

	include ("footer.php"); 
?>
