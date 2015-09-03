<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

// El proposito de este fichero es trucar el RAD_insert.php para que crea que en el modulo hay un subbrowse y asi no cierre la ventana
// popup del navegador, pero en realidad el modulo no tiene subbrowse, tiene llamada a una funcion que hace que no interese que se cierre
// Simplemente :=)
return "";
?>
