<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

global $RAD_dbi, $V_dir;

include_once("header.php");

if ($tipo=="D") echo "<h1>Documentos</h1>";
if ($tipo=="V") echo "<h1>Videos</h1>";
if ($tipo=="F") echo "<h1>Galer&iacute;a de Fotos</h1>";
if ($tipo=="W") echo "<h1>Enlaces</h1>";


	$TMP_res=sql_query("SELECT * FROM ficheros WHERE tipo='".$tipo."'", $RAD_dbi);
	while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {

	}

include_once("footer.php");
?>
