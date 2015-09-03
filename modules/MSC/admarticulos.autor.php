<?
if (eregi(basename(__FILE__), $PHP_SELF))  die ("Security Error ...");

global $owner;
if ($owner!="") {
	global $HTTP_SESSION_VARS;
	$TMP_user=trim(base64_decode($HTTP_SESSION_VARS["SESSION_user"]));
	$fields[$findex[autor]]->vdefault=$TMP_user;
	$fields[$findex[autor]]->readonly=true;
	$fields[$findex[autor]]->dtype="stand";
}

return "";
?>
