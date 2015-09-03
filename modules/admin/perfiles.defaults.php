<?
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}
$TMP="";
if ($func=="detail") {
	$TMP.= "<tr><td colspan=2 class=detail>&nbsp;<b>".$db->Record[literal]."</b></td></tr>";
}
return $TMP;
?>
