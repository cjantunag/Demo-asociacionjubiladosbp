<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}

if (file_exists($RAD_DirBase.$V_mod.".".$func.".tpl")) {
	require 'templates/libs/Smarty.class.php';
	$template = new Smarty;
	$template->compile_check = true;
	$template->debugging = false;
	foreach ($HTTP_GET_VARS as $TMP_key=>$TMP_val) {
		if (!is_array($TMP_val)) {
			$template->assign($TMP_key,$TMP_val);
		}
	}
	$template->display("../".$RAD_DirBase.$V_mod.".".$func.".tpl");
}
//---------------------------------------------------------------------------
//------------------------- Footer Application Page
//---------------------------------------------------------------------------
//    if (!$footeroff) { echo "<hr noshade size=1>\n"; }
?>
