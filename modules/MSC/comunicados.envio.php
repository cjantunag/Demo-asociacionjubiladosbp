<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");
// Envia Email de Comunicado par0

global $RAD_dbi;

$TMP="";

if (!$par0>0) return "";

$TMP_res=sql_query("SELECT * FROM GIE_comunicados WHERE idcomunicado='$par0'", $RAD_dbi);
$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);

$TMP="Envia Mensaje a ".$TMP_row[email]."<br>";

return $TMP;
?>
