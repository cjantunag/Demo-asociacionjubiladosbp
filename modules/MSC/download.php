<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

// descarga ficheros
global $dbname;
if ($dbname=="") $dbname=_DEF_dbname;

if (!isset($_REQUEST['f']) || empty($_REQUEST['f'])) return;
//if (ereg("\.\.",$_REQUEST['f'])) return;

//Utilizamos basename por seguridad, devuelve el 
//nombre del archivo eliminando cualquier ruta. 
$archivo = basename($_REQUEST['f']);

$ruta="files/".$dbname."/".$_REQUEST['f'];

if (is_file($ruta)) {
	header('Content-Type: application/force-download');
	header('Content-Disposition: attachment; filename='.$archivo);
	header('Content-Transfer-Encoding: binary');
	header('Content-Length: '.filesize($ruta));
	readfile($ruta);
}
?>
