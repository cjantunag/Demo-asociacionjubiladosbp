<?
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}
global $HTTP_SESSION_VARS;

$TMP="";
$TMP_user=trim(base64_decode($HTTP_SESSION_VARS["SESSION_user"]));

if ($func=="update" && $V0_id>0) {
	//foreach($_REQUEST as $TMP_key=>$TMP_val) echo $TMP_key."=".substr($TMP_val,0,10)."<br>";
	$result = sql_query("select * from articulos where id=$V0_id", $RAD_dbi);
	$TMP_row = sql_fetch_array($result, $RAD_dbi);
	$oldvisible=$TMP_row[visible];
	$oldpublico=$TMP_row[publico];
	if (($oldvisible=="0"||$oldpublico=="0"||$oldvisible==""||$oldpublico=="") && ($V0_publico!="0" && $V0_visible!="0")) {
		$TMP_mensaje="El usuario $TMP_user ha modificado el articulo con Titulo '$V0_nombre' como visible y publico.\nPara verlo se puede visitar ";
		$TMP_mensaje.="<a href='"._DEF_URL."index.php?V_dir=contents&V_mod=articulos&id= $id'>";
		$TMP_mensaje.=_DEF_URL."index.php?V_dir=contents&V_mod=articulos&id= $id</a>\n";
		$TMP_mensaje=str_replace("'","\"",$TMP_mensaje);
		RAD_sendMail(_DEF_ADMINMAIL,"","","","Articulo puesto como Visible y Publico",$TMP_mensaje,"");
	}
}
if ($func=="insert") {
	//foreach($_REQUEST as $TMP_key=>$TMP_val) echo $TMP_key."=".substr($TMP_val,0,10)."<br>";
	if ($V0_publico!="0" && $V0_visible!="0") {
		$TMP_mensaje="El usuario $TMP_user ha creado el articulo con Titulo '$V0_nombre' como visible y publico.\n";
		$TMP_mensaje=str_replace("'","\"",$TMP_mensaje);
		RAD_sendMail(_DEF_ADMINMAIL,"","","","Articulo creado como Visible y Publico",$TMP_mensaje,"");
	}
}

return $TMP;
?>
