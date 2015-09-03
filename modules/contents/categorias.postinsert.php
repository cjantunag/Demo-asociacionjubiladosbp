<?
if (eregi(basename(__FILE__), $PHP_SELF))  die ("Security Error ...");

global $RAD_dbi;

if (_DEF_appname == "FGSC") { // La constante _DEF_appname está definida en el config.php
	//Ponemos el idpadre en la categoría nueva
	$res = sql_query("SELECT id FROM categorias WHERE categoria = '".$categoriapadre."'",$RAD_dbi);
	$fila = sql_fetch_array($res,$RAD_dbi);
	$idpadre = $fila[0];
	sql_query("UPDATE categorias SET idpadre = ".$idpadre." WHERE id = ".sql_insert_id($RAD_dbi),$RAD_dbi);
}

return "";
?>

