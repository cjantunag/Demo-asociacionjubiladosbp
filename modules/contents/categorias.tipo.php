<?
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}
$TMP="";

global $RAD_dbi,$numrow;
if ($func=="browse") {
	$TMP="<td $RAD_classrow>";
	$par0=$db->Record[id];
}

if ($func=="detail") {
	$TMP="<tr><td class=detailtit>Contenido:</td><td class=detail>";
}

if ($func=="detail" || $func=="browse") {
	$TMP_result = sql_query("select * from categorias WHERE id='".$par0."'", $RAD_dbi);
	$TMP_cat = sql_fetch_array($TMP_result, $RAD_dbi);
	if ($TMP_cat[categoria]=="help" || $TMP_cat[categoria]=="book" || $TMP_cat[categoria]=="dict" || $TMP_cat[categoria]=="forum" || $TMP_cat[categoria]=="news") {
		$TMP_result = sql_query("select count(*) from contenidos WHERE idcat='".$par0."'", $RAD_dbi);
		$TMP_row = sql_fetch_array($TMP_result, $RAD_dbi);
		$V_roi=urlencode("idcat='".$par0."'");
		$TMP.="<a href='index.php?V_dir=$V_dir&V_mod=contents&type=".$TMP_cat[categoria]."&idcat=".$par0."&PHPSESSID=$PHPSESSID&headeroff=x&footeroff=x' target=_blank> ".$TMP_row[0]." registro(s) </a>";
	} else if ($TMP_cat[categoria]=="work") {
		$TMP_result = sql_query("select count(*) from contenidospriv WHERE idcat='".$par0."'", $RAD_dbi);
		$TMP_row = sql_fetch_array($TMP_result, $RAD_dbi);
		$TMP.="<a href='index.php?V_dir=$V_dir&V_mod=work&idcat=".$par0."&PHPSESSID=$PHPSESSID&headeroff=x&footeroff=x' target=_blank> ".$TMP_row[0]." registro(s) </a>";
	} else if ($TMP_cat[categoria]=="mesg") {
		$TMP_result = sql_query("select count(*) from contenidospriv WHERE idcat='".$par0."'", $RAD_dbi);
		$TMP_row = sql_fetch_array($TMP_result, $RAD_dbi);
		$TMP.="<a href='index.php?V_dir=$V_dir&V_mod=mesg&idcat=".$par0."&PHPSESSID=$PHPSESSID&headeroff=x&footeroff=x' target=_blank> ".$TMP_row[0]." registro(s) </a>";
	} else {
		$TMP_result = sql_query("select count(*) from contenidos WHERE idcat='".$par0."'", $RAD_dbi);
		$TMP_row = sql_fetch_array($TMP_result, $RAD_dbi);
		$V_roi=urlencode("idcat='".$par0."'");
		$TMP.="<a href='index.php?V_dir=$V_dir&V_mod=contents&type=".$TMP_cat[categoria]."&idcat=".$par0."&PHPSESSID=$PHPSESSID&headeroff=x&footeroff=x' target=_blank> ".$TMP_row[0]." registro(s) </a>";
	}
}

if ($func=="detail" && $TMP!="") {
	$TMP.=" </td></tr>";
}
if ($func=="browse") {
	$TMP.=" </td>";
}

return $TMP;
?>