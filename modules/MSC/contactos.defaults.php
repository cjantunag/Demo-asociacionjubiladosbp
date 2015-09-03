<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

$TMP="";

$fields[$findex["idusuario"]]->vdefault=getSessionVar("SESSION_U_idusuario");

if ($func=="detail" && $subfunc!="list") {
	$TMP.="<tr><td class=detail colspan=2><b>".$db->Record[nombre]."</b></td></tr>";
}

if ($func=="insert" && $V0_nombre!="") {
	$TMP_res=sql_query("SELECT * FROM GIE_contactos where nombre='$V0_nombre'",$RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_res,$RAD_dbi);
	if ($TMP_row[nombre]==$V0_nombre) alert("Ya existe Contacto: ".$V0_nombre);
}

//---------------------------------------------------------------------------------------------------------------------
// genera etiquetas con datos de formulario anterior
if ($op=="label") {
	include_once("modules/".$V_dir."/contactos.label.php");
}

return $TMP;
?>
