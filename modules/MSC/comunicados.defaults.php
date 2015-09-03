<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

// informaEmail($idpersona,$asunto,$cuerpo) 
// sendOP($id) 
// sendIDOP($id)


$TMP="";

if ($op=="envio") {
	$TMP_result=sql_query("SELECT * FROM GIE_comunicados WHERE idcomunicado='$par0'",$RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_result,$RAD_dbi);
	echo include_once("modules/".$V_dir."/comunicados.envio.php");
	echo "Funcion AUN NO IMPLEMENTADA.<br>Mensaje ".$TMP_row[asunto]."<br><br>";
	if (_DEF_POPUP_MARGIN=="SUBMODAL") echo "<a href='javascript:closePop();'>Cerrar ventana</a>";
	else echo "<a href='javascript:window.close();'>Cerrar ventana</a>";
}

if ($func=="insert") {
    if ($V0_idtipo=="0") {
        $consulta=sql_query("SELECT MAX(numeroentrada) FROM GIE_comunicados",$RAD_dbi);
        $TMP_rownumero = sql_fetch_row($consulta, $RAD_dbi);
        $TMP_rownumero[0]++;
        $V0_numeroentrada=$TMP_rownumero[0];
        $V0_numerosalida="";
    } else {
        $consulta=sql_query("SELECT MAX(numerosalida) FROM GIE_comunicados",$RAD_dbi);
        $TMP_rownumero = sql_fetch_row($consulta, $RAD_dbi);
        $TMP_rownumero[0]++;
        $V0_numerosalida=$TMP_rownumero[0];
        $V0_numeroentrada="";
    }
    if ($V0_otroscontenidos!="") {
        $V0_contenido=$V0_otroscontenidos;
    }
    $V0_fechaalta=date("Y-m-d H:i:s");
}

if ($func=="detail" || $func=="print" || $func=="list") {
	$TMP.="<tr><td class=detail colspan=2>   ";
	$TMP.=" <a href='".$PHP_SELF."?V_dir=$V_dir&V_mod=$V_mod&menuoff=x&headeroff=x&footeroff=x&blocksoff=x&func=none&op=print&par0=$par0' target=_blank><img src='images/print.gif' border=0 title='Imprime Contenido' alt='Imprime Contenido'> Imprime Contenido</a>  ";
	$TMP.="  </td></tr>";
}

if ($op=="print") {
	include_once("modules/".$V_dir."/comunicados.imprime.php");
	comunicadosImprime($par0, "", "", "");
}

return $TMP;
?>
