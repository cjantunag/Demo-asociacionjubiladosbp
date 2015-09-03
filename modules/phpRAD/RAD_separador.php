<?php
if (eregi(basename(__FILE__), $PHP_SELF))  die ("Security Error ...");

// Campo Function que devuelve HTML de Titulos para separar bloques en new/edit/detail
$TMP = "";
if ($func=="edit" || $func=="new" || $func=="detail") {
	$TMP_numcol=0;
	$TMP_colspan=2;
	if (($func=="edit" || $func=="new")  &&  $V_colsedit>1) $TMP_colspan=$V_colsedit*2;
	if ($func=="detail" &&  $V_colsdetail>1) $TMP_colspan=$V_colsdetail*2;
	return "<tr class='detail'><td class='detailtit' colspan='$TMP_colspan' valign='bottom'><u><b><center>".$fields[$i]->title."</center></b></u></td></tr>";
}
return "";
?>
