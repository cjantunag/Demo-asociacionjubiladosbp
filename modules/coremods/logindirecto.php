<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}
// Requiere como parametro la lista de usuarios en $user=Pedro|pedro,Juan|usuariojuan....
include_once("header.php");
$TMP="";

if (is_user()) return; 

if ($idcentro>0) {
	setSessionVar("SESSION_idcentro",$idcentro,0);
}
$TMP_idcentro=getSessionVar("SESSION_idcentro");
$TMP_centro=getSessionVar("SESSION_centro");
if ($TMP_idcentro>0 && $TMP_centro=="") {
	$TMP_centro=RAD_lookup("GIE_centros","centro","idcentro",$TMP_idcentro);
	setSessionVar("SESSION_centro",$TMP_centro,"0");
}

if (!$TMP_idcentro>0) {
	$TMP_res=sql_query("SELECT * FROM GIE_centros order by idcentro", $RAD_dbi);
	while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
		$TMP.= "<a style='width:150px;' data-role='button' data-inline='true' href=\"index.php?idcentro=".$TMP_row[idcentro]."\"><br><b>".$TMP_row[centro]."<br><br></b></a> ";
	}
} else {
	$A_x=explode(",",$user.",");
	foreach($A_x as $TMP_idx=>$TMP_par) {
		$A_z=explode("|",$TMP_par);
		$TMP_nombre=$A_z[0]; $TMP_user=$A_z[1];
		$A_nombre[$TMP_user]=$TMP_nombre;
	}
	$TMP_res=sql_query("SELECT * FROM usuarios order by usuario", $RAD_dbi);
	while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
		if ($A_nombre[$TMP_row[usuario]]=="") continue;
		$TMP_theme=getSessionVar("SESSION_theme");
		$TMP.= "<a style='width:150px;' data-role='button' data-inline='true' href=\"index.php?V_dir=coremods&V_mod=usercontrol&uname=".$TMP_row[usuario]."&V_op=login&V_nomd5=x&RAD_newtheme=$TMP_theme&pass=".$TMP_row[clave]."\"><br><b>".$A_nombre[$TMP_row[usuario]]."<br><br></b></a> ";
	}
}

echo "<center>".$TMP."</center>";
include_once("footer.php");
?>
