<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}
if (file_exists("modules/$V_dir/".$V_mod.".foot.php")) include_once ("modules/$V_dir/".$V_mod.".foot.php");
//---------------------------------------------------------------------------
//------------------------- Footer Application Page
//---------------------------------------------------------------------------
//    if (!$footeroff) { echo "<hr noshade size=1>\n"; }
// Limpia variables por si se ejecutan otros modulos a continuacion (por ejemplo en index)
	$orderby="";
	$defaultfilter="";
?>
