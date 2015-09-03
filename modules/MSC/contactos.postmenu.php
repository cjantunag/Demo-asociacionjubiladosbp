<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

global $RAD_dbi;

$TMP="";

if ($func=="" || $func=="browse" || $func=="search" || $func=="detail") {
//	$TMP.=RAD_menu_off("<a href='javascript:RAD_OpenW(\"index.php?V_dir=$V_dir&V_mod=$V_mod&V_idmod=$V_idmod&func=none&op=map&blocksoff=x&menuoff=x&headeroff=$headeroff&footeroff=$footeroff&par0=$par0&par1=$par1&par2=$par2$SESSION_SID\",1200,900);'> <img src='images/mapa.gif' valign=2 alt='Mapa de Situacion de Todos los Domicilios con Coordenadas de Longitud y Latitud' title='Mapa de Situacion de Todos los Domicilios con Coordenadas de Longitud y Latitud' border=0> Mapa de Situaci&oacute;n</a>");
	$TMP.=RAD_menu_off("<a target=_blank href='index.php?V_dir=$V_dir&V_mod=$V_mod&V_idmod=$V_idmod&func=none&op=label&par0=$par0&blocksoff=x&menuoff=&headeroff=x&footeroff=x$SESSION_SID'> <img src='images/print.png' valign=2 alt='Listado de Etiquetas con Direcciones' title='Listado de Etiquetas con Direcciones' border=0> Etiquetas</a>");
}

return $TMP;
?>
