<?

$TMP="";
if ($db->Record["paginasvistas"]>0) {
	$TMP_tiempomedio=$db->Record["tiempototal"]/$db->Record["paginasvistas"];
	$TMP_tiempomedio=(floor($TMP_tiempomedio*1000))/1000;
	$TMP_longmedia=$db->Record["longitudurl"]/$db->Record["paginasvistas"];
	$TMP_longmedia=floor($TMP_longmedia);
}

if ($func=="" || $func=="browse") $TMP="<TD $RAD_classrow>$TMP_tiempomedio seg./$TMP_longmedia bytes</TD>\n";

if ($func == "detail") {
	$TMP="<tr><td class=detailtit>Tiempo Medio:</td><td class=detail>$TMP_tiempomedio seg.</td>";
	$TMP="<tr><td class=detailtit>Tamaño Medio:</td><td class=detail>$TMP_longmedia bytes</td>";
}
return $TMP;

?>
