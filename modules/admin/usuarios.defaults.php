<?
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}

$TMP="";

if ($func=="detail") {
    $TMP.= "<tr><td colspan=2 class=detail> <b>".$db->Record[usuario]."</b></td></tr>";
    if ($V_lap=="Datos") {
	$TMP.= "<tr><td colspan=2 class=detailtit><a href=\"index.php?V_dir=coremods&V_mod=usercontrol&uname=".$db->Record[usuario]."&V_op=login&V_nomd5=x&pass=".$db->Record[clave]."\"> ===> Entrar como <b>".$db->Record[usuario]."</b> </a> ";
	$TMP.= " | <a target=_blank href=\"index.php?V_dir=coremods&V_mod=usercontrol&uname=".$db->Record[usuario]."&V_op=logout&usession=$usession&headeroff=x&footeroff=x\"> ===> Cerrar conexi&oacute;n de <b>".$db->Record[usuario]."</b> </a> </td></tr>";
    }
}

if ($func=="insert" || $func=="update") {
//	$V0_usuario=strtolower($V0_usuario);
	$V0_usuario=str_replace(" ", "", $V0_usuario);
	$V0_usuario=str_replace("=", "", $V0_usuario);
	$V0_usuario=str_replace(":", "", $V0_usuario);
	$V0_usuario=str_replace(",", "", $V0_usuario);
	$usuario=$V0_usuario;

//	$V0_clave=strtolower($V0_clave);
	$clave=$V0_clave;
}


if ($func=="none") {
	$TMP_res=sql_query("SELECT * FROM estadisticas WHERE usuario!='' AND usuario IS NOT NULL", $RAD_dbi);
	while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
		$TMP_usu=$TMP_row[usuario];
		$TMP_fecha=date("Y-m",$TMP_row[tiempoinicio]);
		if ($A_cont[$TMP_usu][$TMP_fecha]=="") $A_cont[$TMP_usu][$TMP_fecha]=0;
		$A_cont[$TMP_usu][$TMP_fecha]++;
		$A_usu[$TMP_usu]="x";
	}
	if (count($A_usu)>0) {
		echo "<center><br><h1>Informe Estadistico de Visitas de Usuarios</h1><table class=browse>";
		echo "<tr><th class=browse>Usuario</th><th class=browse>Fecha (Mes)</th><th class=browse>Visitas</th></tr>";
		ksort($A_usu);
		foreach($A_usu as $TMP_usu=>$TMP_nada) {
			ksort($A_cont[$TMP_usu]);
			$TMP_span=count($A_cont[$TMP_usu]);
			$TMP_numfila=0;
			foreach($A_cont[$TMP_usu] as  $TMP_fecha=>$TMP_cont) {
				$TMP_numfila++;
				if ($TMP_class=="class=row2") $TMP_class="class=row1";
				else $TMP_class="class=row2";
				echo "<tr>";
				//if ($TMP_numfila==1) echo "<td $TMP_class rowspan='$TMP_span'><b>".$TMP_usu."</b></td>";
				if ($TMP_numfila==1) echo "<td $TMP_class><b>".$TMP_usu."</b></td>";
				else echo "<td $TMP_class></td>";
				echo "<td $TMP_class>".$TMP_fecha."</td>";
				echo "<td $TMP_class>".$TMP_cont."</td>";
				echo "</tr>";
			}
		}
		echo "</table></center><br>";
		if ($V_typePrint=="" && $subop=="") {
			$TMP_linkprint=$PHP_SELF."?";
			foreach($_REQUEST as $TMP_k=>$TMP_v) $TMP_linkprint.=$TMP_k."=".$TMP_v."&";
			echo "<ul><a href='".$TMP_linkprint."&subop=print'><img src='images/print.gif' alt='Imprimir' title='Imprimir'></a> | <a href='".$TMP_linkprint."&subop=csv&V_typePrint=csv&V_save=x'><img src='images/xls.gif' alt='Hoja de Calculo' title='Hoja de Calculo'></a>";
		}
		if ($subop=="print") echo "\n<script>\nwindow.print();\n</script>\n";
	}
}

return $TMP;
?>
