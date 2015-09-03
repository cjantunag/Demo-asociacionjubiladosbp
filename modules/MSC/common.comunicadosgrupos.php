<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

if (($func=="insert"||$func=="update") && trim($V0_contenido)=="" && $V0_idcomunicadotipo>0) {
	$TMP_res=sql_query("SELECT * FROM GIE_comunicadostipos WHERE idcomunicadotipo='$V0_idcomunicadotipo'", $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
	RAD_setField("contenido",$TMP_row[carta]);
}

return "";
?>
