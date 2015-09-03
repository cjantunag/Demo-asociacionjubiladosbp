<?
if (eregi(basename(__FILE__), $PHP_SELF))  die ("Security Error ...");

	$TMP_user= trim(base64_decode($HTTP_SESSION_VARS["SESSION_user"]));
	if (is_admin() && $owner=="") {
	} else {
		$menuedit=false;
		$menudelete=false;
		$defaultfilter=" usuario='".$TMP_user."'";
	}

return "";
?>