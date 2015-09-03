<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}

global $A_RAD_ajaxRegisteredFunctions;
if (count($A_RAD_ajaxRegisteredFunctions)>0) {
	require_once("images/xajax/xajax.inc.php");
	$xajaxF = new xajax();
	foreach($A_RAD_ajaxRegisteredFunctions as $TM_k=>$TMP_func) {
		$xajaxF->registerFunction($TMP_func);
	}
	$xajaxF->setCharEncoding("iso-8859-1");
	//$xajax->setCharEncoding("utf-8");
	$xajaxF->processRequests();
	$xajaxF->printJavascript();
}

global $footeroff;
//if ((!isset($footeroff)) || ($footeroff=="")) foot();
//flush();
foot();
if ($V_typePrint!="" || $V_typeSend!="") {
	//$TMP=ob_get_contents();
	//ob_end_clean();
	// FALTA: recoger el contenido y filtrar URL para poner SEO en los enlaces antiguos sin SEO 
	//echo $TMP;
}
?>
