<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}
//---------------------------------------------------------------------------
//------------------------- Header Application Page
//---------------------------------------------------------------------------
if ((file_exists("modules/$V_dir/head.app.php"))&&$headeroff==""&&$func!="login") include_once ("modules/$V_dir/head.app.php");
?>
