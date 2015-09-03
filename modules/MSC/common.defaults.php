<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");
global $RAD_dbi, $V_prevfunc;

if ($op=="genhtaccess") {
	echo "<br><ul><h1> Genera htaccess</h1><br>Crea lineas MODREWRITE de URL SEO. Resultado:<ul><br><b>";
	$TMP_res=creahtacces();
	die($TMP_res);
}

$fields[$findex["url_seo"]]->help="<br> URL Amigable (Seo) debe tener un nombre sencillo (no se deben utilizar caracteres especiales). Por ejemplo proyecto_fisio.html";
$fields[$findex["url_seo_galician"]]->help="<br> URL Amigable (Seo) debe ter un nome sinxelo (non se deben utilizar caracteres especiais). Por exemplo proxecto_fisio.html";

?>
