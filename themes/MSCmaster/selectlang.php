<?php
	$TMP_form="<form name=L action='$PHP_SELF'>";
	foreach($_REQUEST as $TMP_k=>$TMP_v) if (substr($TMP_k,0,5)!="__utm" && $TMP_v!="" && $TMP_k!="newlang") $TMP_form.="<input type=hidden name='$TMP_k' value='$TMP_v'>";
        $TMP_form.="<select class='select' name='newlang' onchange='javascript:this.form.submit();'>";
	if ($HTTP_SESSION_VARS["SESSION_lang"]=="galician" || $newlang=="galician") {
        	$TMP_form.="  <option selected value='galician'>Galego</option>";
        	$TMP_form.="  <option value='spanish'>Espa&ntilde;ol</option>";
	} else {
        	$TMP_form.="  <option value='galician'>Galego</option>";
        	$TMP_form.="  <option selected value='spanish'>Espa&ntilde;ol</option>";
	}
        $TMP_form.="</select></form>";

	return $TMP_form;
?>
