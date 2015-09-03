<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: ../index.php");
    die();
}
if ($func!="detail" || $V_lap!="Usuarios") return "";

global $RAD_dbi, $SESSION_SID;

	$TMP="<tr><td class=detailtit> Usuarios : </td><td class=detail>";
	$TMP_URL=$PHP_SELF."?V_dir=$V_dir&V_mod=usuarios&func=detail".$SESSION_SID."&headeroff=x&footeroff=x&par0=";
	$TMP_result = sql_query("SELECT * FROM usuarios WHERE perfil LIKE '%,".$par0.",%' ORDER BY usuario", $RAD_dbi);
	while($TMP_row = sql_fetch_array($TMP_result, $RAD_dbi)) {
	    $TMP.="<a target=_blank href='".$TMP_URL.$TMP_row[idusuario]."'>".$TMP_row[usuario]." ".$TMP_row[nombre]."<br>";
	}
	$TMP.="</td></tr>";

	return $TMP;
?>
