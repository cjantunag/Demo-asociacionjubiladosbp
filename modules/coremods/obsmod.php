<?
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");
// Muestra las Observaciones de un Modulo. Permite generar una pagina de Portada componiendola con fragmentos de varios modulos

include_once("header.php");

global $RAD_dbi;

$TMP_lang=getSessionVar("SESSION_lang");

$TMP_res=sql_query("SELECT * FROM modulos WHERE idmodulo='$V_idmod'", $RAD_dbi);
$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
if (trim($TMP_row["observaciones_".$TMP_lang])!="") $TMP_row[observaciones]=$TMP_row["observaciones_".$TMP_lang];

echo $TMP_row[observaciones];

include_once("footer.php");
?>
