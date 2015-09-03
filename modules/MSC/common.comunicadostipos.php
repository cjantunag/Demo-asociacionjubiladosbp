<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

if ($idproyecto>0 && $func=="new") {
	$fields[$findex[idproyecto]]->vdefault=$idproyecto;
	$fields[$findex[idproyecto]]->readonly=true;
}


return "";
?>
