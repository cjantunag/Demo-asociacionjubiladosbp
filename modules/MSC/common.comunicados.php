<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

global $RAD_dbi, $V_dir;

if (($func=="insert"||$func=="update") && trim($V0_contenido)=="" && $V0_idcomunicadotipo>0) {
	$TMP_res=sql_query("SELECT * FROM GIE_comunicadostipos WHERE idcomunicadotipo='$V0_idcomunicadotipo'", $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
	if ($V0_identidad>0 || $V0_idcontacto>0) {
		include_once("modules/".$V_dir."/comunicados.traducecontenido.php");
		$TMP_row[carta]=comunicadosTraduceContenido($TMP_row[carta], $V0_identidad, $V0_idcontacto, "");
	}
	RAD_setField("contenido",$TMP_row[carta]);
}

return "";
?>
