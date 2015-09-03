<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}
global $footeroff;
//if ((!isset($footeroff)) || ($footeroff=="")) foot();
//flush();
foot();

$TMP=ob_get_contents();
ob_end_clean();
ob_start("ob_gzhandler");
$TMP=str_replace("modules.php?V_dir=contents&V_mod=articulos&id=","articulo_",$TMP);
$TMP=str_replace("index.php?V_dir=contents&V_mod=articulos&id=","articulo_",$TMP);
echo $TMP;
//ob_end_flush();
?>
