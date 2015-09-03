<?
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}
$TMP="";
if ($func=="detail") {
	if ($db->Record[usuario]!="") {
    	    $TMP.= "<tr><td colspan=2 class=detailtit><a href='index.php?V_dir=coremods&V_mod=statsreport&user=".$db->Record[usuario]."' target=_blank>ESTADISTICAS DEL USUARIO</a></td></tr>";
	}
	$TMP.= "<tr><td class=detailtit>URLs visitadas:</td><td class=detail>";
	if ($db->Record[urlsvisitadas]!="") $TMP_LINEAS=explode("\n",$db->Record[urlsvisitadas]);
	if (count($TMP_LINEAS)>0) {
	    for($ki=0; $ki<count($TMP_LINEAS); $ki++) {
		$TMP_trozos[1]="";
		if ($TMP_LINEAS[$ki]!="") $TMP_trozos=explode("?",$TMP_LINEAS[$ki]);
		if ($TMP_trozos[1]!="") {
		    $TMP_trozos[1]=RAD_delParamURL($TMP_trozos[1],"PHPSESSID");
	    	    $TMP.= "<a target=_blank href=\"index.php?".$TMP_trozos[1]."\" target=blank>".$TMP_trozos[1]."</a><br>";
		}
	    }
	}
	$TMP.= " </td></tr>";
}
return $TMP;
?>
