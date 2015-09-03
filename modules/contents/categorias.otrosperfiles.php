<?
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}
$TMP="";
$TMP_autor= trim(base64_decode($HTTP_SESSION_VARS["SESSION_user"]));

$value=$db->Record[otrosperfiles];
if ($otrosperfiles!="") $TMP.= "<input type=hidden name=otrosperfiles value='".$otrosperfiles."'>";
if ($func=="new" || $func=="edit") {
	if ($otrosperfiles!="") $TMP.= "<input type=hidden name=V0_otrosperfiles value='".$otrosperfiles."'>";
	else {
	    $TMP.= "<TR><TD class=detailtit>Otros Perfiles:</TD><TD class=detail>";
	    $TMP.= "<input type=text name=V0_otrosperfiles SIZE=25 value='".$value."'></TD></TR>";
	}
}
if ($func == "detail") {
	$TMP.= "<TR><TD class=detailtit>".$fields[$findex[otrosperfiles]]->title.":</TD>\n";
}
if ($func == "browse" || $func=="detail") {
	$TMP.= "<TD class=detail>";
	$TMP.= $value."&nbsp;</TD>";
}
if ($func == "detail") {
	$TMP.= "</TR>\n";
}
if ($func=="insert") {
	if ($fieldnames != "") $fieldnames .= ",";
	$fieldnames .= "otrosperfiles";
	if ($fieldvalues != "") $fieldvalues .= ",";
	$fieldvalues .= tosql($V0_otrosperfiles);
}
if ($func=="update") {
	if ($fieldvalues != "") $fieldvalues .= ",";
	$fieldvalues .= "otrosperfiles=".tosql($V0_otrosperfiles);
}
return $TMP;
?>
