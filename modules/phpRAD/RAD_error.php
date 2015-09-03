<?
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}
//---------------------------------------------------------------------------
//------------------------- Error
//---------------------------------------------------------------------------
if ($func == "error") { 
	RAD_logError("ERR: ".$RAD_errorstr);
	echo "<font style='color:red; font-weight:bold;'>".$RAD_errorstr."</font>\n";
	//if (is_admin()) echo "<font style='color:red; font-weight:bold;'>".$RAD_errorstr."</font>\n";
	//else error("Error en datos (registro duplicado, datos erroneos, etc)");
} else if ($RAD_errorstr!="") {
	if ($RAD_errorstr != _DEF_NLSRecordInserted && $RAD_errorstr != _DEF_NLSRecordUpdated) RAD_logError("LOG: ".$RAD_errorstr);
	echo "\n<script type='text/javascript'>\n";
	if ($RAD_errorstr == _DEF_NLSRecordInserted || $RAD_errorstr == _DEF_NLSRecordUpdated) {
		$RAD_errorstr=str_replace("'","/'",$RAD_errorstr);
		echo "window.status='".$RAD_errorstr."';\n";
		status($RAD_errorstr);
	} else {
		$RAD_errorstr=str_replace("'","/'",$RAD_errorstr);
		echo "alert('".$RAD_errorstr."');\n";
	}
	echo "\n</script>\n";
}
$RAD_errorstr="";
?>
