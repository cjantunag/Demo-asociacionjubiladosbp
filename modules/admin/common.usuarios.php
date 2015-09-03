<?
if (eregi(basename(__FILE__), $PHP_SELF))  die ("Security Error ...");

if (($func=="detail"||$func=="edit") && $par0!="") {
	$TMP_id=RAD_lookup("usuarios","idusuario","idusuario",$par0);
	if (!$TMP_id>0) $TMP_id=RAD_lookup("usuarios","idusuario","usuario",$par0);
	if ($TMP_id>0) $par0=$TMP_id;
}

return "";
?>
