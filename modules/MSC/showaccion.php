<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");
// Muestra la descripcion de una Accion

global $RAD_dbi, $id, $dbname, $blocksoff, $idaccion, $idaccioncategoria, $idin, $idout;
if ($dbname=="") $dbname=_DEF_dbname;

include_once("header.php");
$TMP_lang=getSessionVar("SESSION_lang");
$TMP_texto1=_DEF_NLSAcc1;
$TMP_texto2=_DEF_NLSAcc2;

global $TMP_secu, $V_dir;
include_once("modules/".$V_dir."/lib.ajax.php");

$TMP_optspais="";
$TMP_res=sql_query("SELECT * FROM paises order by pais", $RAD_dbi);
while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
        $TMP_pais=$TMP_row[pais];
        foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
        if (strtolower(substr($TMP_row[pais],0,4))=="espa") $TMP_optspais.="<option selected value='".str_replace("'","`",$TMP_pais)."'>".$TMP_row[pais]."</option>";
        else $TMP_optspais.="<option value='".str_replace("'","`",$TMP_pais)."'>".$TMP_row[pais]."</option>";
}


if ($op!="" && $idaccion>0) {
	//foreach($_REQUEST as $TMP_k=>$TMP_v) echo $TMP_k."=".$TMP_v."<br>";
	$TMP_res=sql_query("SELECT * FROM acciones where idaccion=".converttosql($idaccion), $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
	foreach($TMP_row as $TMP_k=>$TMP_v) {
	        if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
	}
	$TMP_accion=$TMP_row[accion];
        include_once("modules/".$V_dir."/lib.savereg.php");
        savereg("firmas","Firma recibida en la pagina WEB. ".$TMP_accion);
	$id=$idout;
	$TMP_res=sql_query("SELECT * FROM articulos where id='$idout'", $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
	foreach($TMP_row as $TMP_k=>$TMP_v) {
	        if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
	}
	foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
	$TMP_row[contenido]=str_replace("<!-- securimage -->",$TMP_secu,$TMP_row[contenido]);
	$TMP_row[contenido]=str_replace("<!-- idaccion -->",$idaccion,$TMP_row[contenido]);
	$TMP_row[contenido]=str_replace("<!-- nombre -->",$_REQUEST[nombre],$TMP_row[contenido]);
	$TMP_row[contenido]=str_replace("<!-- optspais -->",$TMP_optspais,$TMP_row[contenido]);
	echo "\n<h1>".$TMP_accion."</h1>\n".$TMP_row[contenido]."\n\n";
} else if ($idaccion>0) echo showAccion($idaccion);
else echo showAcciones($term);


include_once("footer.php");
return;
//---------------------------------------------------------------------------------------------------------------------------------
function showAccion($idaccion) {
	global $RAD_dbi, $dbname, $TMP_secu, $V_dir, $id, $idin;
	$TMP_lang=getSessionVar("SESSION_lang");

	$TMP_res=sql_query("SELECT * FROM acciones where idaccion=".converttosql($idaccion), $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
	$TMP_Gfecha=RAD_gregorianDate(date("Y-m-d"));
	$TMP_Gfechafin=RAD_gregorianDate($TMP_row[fechafin]);
	if (!$TMP_Gfechafin>0) $TMP_activo="1";
	else if ($TMP_Gfechafin>$TMP_Gfecha) $TMP_activo="1";
	else $TMP_activo="0";
	if ($TMP_activo=="1") {
		$TMP_class=' class="enmarcha"';
		$TMP_barra='barra_enmarcha';
	} else {
		$TMP_class='';
		$TMP_barra='barra';
	}
	//if ($TMP_activo=="1" && $term!="") continue;
	//if ($TMP_activo!="1" && $term=="") continue;
	$TMP_fecha=RAD_showDate($TMP_row[fechainicio]);
	$TMP_pend=$TMP_row[nummaxfirmas]-$TMP_row[numfirmas];
	$TMP_largo=207-round(($TMP_row[numfirmas]*207)/$TMP_row[nummaxfirmas]);
	if ($TMP_row[numfirmas]<1) $TMP_largo="208";
	$TMP_stylebarra=" style='background-position:-".$TMP_largo."px 0;'";
	if ($TMP_pend<0) $TMP_pend=0;
        $TMP_img=RAD_primerFich($TMP_row[imagen]);
	if ($TMP_img=="") {
		$TMP_res3=sql_query("SELECT * from contenidos where idaccion='".$TMP_row[idaccion]."' and idcat='5' order by fechapubli DESC", $RAD_dbi);
		$TMP_row3=sql_fetch_array($TMP_res3, $RAD_dbi);
		$TMP_img=RAD_primerFich($TMP_row3[imagenes]);
	}
	if ($TMP_img!="") {
		include_once("modules/".$V_dir."/resizeCrop.php");
		//$TMP_img3=resizeCrop("files/".$dbname."/".$TMP_img,"","207","120");
		$TMP_img3=resizeCrop("files/".$dbname."/".$TMP_img,"","156","151");
		list($anchoimg, $altoimg, $tipo, $resto)=getimagesize($TMP_img3);
		//echo "\n<br>Crop $TMP_img3 de ancho $anchoimg, $altoimg, $tipo<br>";
	}
	foreach($TMP_row as $TMP_k=>$TMP_v) {
	        if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
	}
	$A_x=explode("\n",$TMP_row[objetivos]."\n");
	$TMP_row[objetivos]="";
	foreach($A_x as $TMP_idx=>$TMP_lin) {
		if (trim($TMP_lin)=="") continue;
		$TMP_row[objetivos].="<li>".$TMP_lin."</li>\n";
	}
	$A_x=explode("\n",$TMP_row[beneficiarios]."\n");
	$TMP_row[beneficiarios]="";
	foreach($A_x as $TMP_idx=>$TMP_lin) {
		if (trim($TMP_lin)=="") continue;
		$TMP_row[beneficiarios].="<li>".$TMP_lin."</li>\n";
	}
	$A_x=explode("\n",$TMP_row[resultados]."\n");
	$TMP_row[resultados]="";
	foreach($A_x as $TMP_idx=>$TMP_lin) {
		if (trim($TMP_lin)=="") continue;
		$TMP_row[resultados].="<li>".$TMP_lin."</li>\n";
	}
	$A_x=explode("\n",$TMP_row[activs]."\n");
	$TMP_row[activs]="";
	foreach($A_x as $TMP_idx=>$TMP_lin) {
		if (trim($TMP_lin)=="") continue;
		$TMP_row[activs].="<li>".$TMP_lin."</li>\n";
	}
	if ($TMP_img3!="") {
		$TMP_imghtml='<img src="'.$TMP_img3.'">';
	} else {
		$TMP_imghtml='<br>';
	}
	$TMP='
<h1>'.$TMP_row[accion].'</h1>
<div id="pastilla">
          <ul'.$TMP_class.'>
            <li class="foto">'.$TMP_imghtml.'</li>
            <li class="contenido">
            <p class="titular"></p>
            <p>'.$TMP_row[resumen].'</p>
            </li>
          </ul>
</div>
<div class="fondo_gris">
<p class="parrafo">
'.$TMP_row[descripcion].'
</p>
<p class="parrafo">
'._DEF_NLSAccDesde.' <span class="negrita">'.$TMP_fecha.'</span><br>
'.RAD_numero($TMP_row[numfirmas],0).' '._DEF_NLSAccFirmas.'</p>
<div class="'.$TMP_barra.'50"'.$TMP_stylebarra.'></div>
<p class="necesitan"> '._DEF_NLSAccSenecesitan.' '.RAD_numero($TMP_pend,0).' '._DEF_NLSAccFirmasmas.'</p> 
</div>
';

$TMP_optspais="";
$TMP_res=sql_query("SELECT * FROM paises order by pais", $RAD_dbi);
while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
        $TMP_pais=$TMP_row[pais];
        foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
        if (strtolower(substr($TMP_row[pais],0,4))=="espa") $TMP_optspais.="<option selected value='".str_replace("'","`",$TMP_pais)."'>".$TMP_row[pais]."</option>";
        else $TMP_optspais.="<option value='".str_replace("'","`",$TMP_pais)."'>".$TMP_row[pais]."</option>";
}

	if ($TMP_activo=="1") { // formulario de firma
		$id=$idin;
		$TMP_res=sql_query("SELECT * FROM articulos where id='$idin'", $RAD_dbi);
		$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
		$TMP_row[contenido]=str_replace("<!-- securimage -->",$TMP_secu,$TMP_row[contenido]);
		$TMP_row[contenido]=str_replace("<!-- idaccion -->",$idaccion,$TMP_row[contenido]);
		$TMP_row[contenido]=str_replace("<!-- nombre -->",$_REQUEST[nombre],$TMP_row[contenido]);
		$TMP_row[contenido]=str_replace("<!-- optspais -->",$TMP_optspais,$TMP_row[contenido]);
		$TMP.="\n\n".$TMP_row[contenido]."\n\n";
	}

if (is_modulepermitted("", "MSC", "acciones")) $TMP="<a target=_blank href='index.php?V_dir=MSC&V_mod=acciones&func=edit&par0=$idaccion'>"._DEF_NLSEdit."</a><br>".$TMP;

return $TMP;
}

//---------------------------------------------------------------------------------------------------------------------------------
function showAcciones($term) {
	global $RAD_dbi, $dbname, $V_dir, $V_mod, $TMP_texto1, $TMP_texto2;

	$TMP_lang=getSessionVar("SESSION_lang");

	if ($term!="") $TMP_categoria=$TMP_texto2;
	else $TMP_categoria=$TMP_texto1;
	$TMP='
</div>
<div id="columna_ancha">
        <h1>'.$TMP_categoria.'</h1>
';
	if ($term=="") {
		$TMP_class=' class="enmarcha"';
		$TMP_barra='barra_enmarcha';
	} else {
		$TMP_class='';
		$TMP_barra='barra';
	}

	$TMP_Gfecha=RAD_gregorianDate(date("Y-m-d"));
	$TMP_res2=sql_query("SELECT * FROM acciones order by fechainicio DESC, accion ASC", $RAD_dbi);
	while($TMP_row2=sql_fetch_array($TMP_res2, $RAD_dbi)) {
		$TMP_Gfechafin=RAD_gregorianDate($TMP_row2[fechafin]);
		if (!$TMP_Gfechafin>0) $TMP_activo="1";
		else if ($TMP_Gfechafin>$TMP_Gfecha) $TMP_activo="1";
		else $TMP_activo="0";
		if ($TMP_activo=="1" && $term!="") continue;
		if ($TMP_activo!="1" && $term=="") continue;
		$TMP_fecha=RAD_showDate($TMP_row2[fechainicio]);
		$TMP_pend=$TMP_row2[nummaxfirmas]-$TMP_row2[numfirmas];
		$TMP_largo=200-round(($TMP_row2[numfirmas]*200)/$TMP_row2[nummaxfirmas]);
		$TMP_largo=207-round(($TMP_row2[numfirmas]*207)/$TMP_row2[nummaxfirmas]);
		if ($TMP_row2[numfirmas]<1) $TMP_largo="208";
		$TMP_stylebarra=" style='background-position:-".$TMP_largo."px 0;'";
		if ($TMP_pend<0) $TMP_pend=0;
        	$TMP_img=RAD_primerFich($TMP_row2[imagen]);
		$TMP_img3="";
		if ($TMP_img=="") {
			$TMP_res3=sql_query("SELECT * from contenidos where idaccion='".$TMP_row2[idaccion]."' and idcat='5' order by fechapubli DESC", $RAD_dbi);
			$TMP_row3=sql_fetch_array($TMP_res3, $RAD_dbi);
			$TMP_img=RAD_primerFich($TMP_row3[imagenes]);
		}
		if ($TMP_img!="") {
			include_once("modules/".$V_dir."/resizeCrop.php");
			$TMP_img3=resizeCrop("files/".$dbname."/".$TMP_img,"","156","151");
			//$TMP_img3=resizeCrop("files/".$dbname."/".$TMP_img,"","207","120");
			list($anchoimg, $altoimg, $tipo, $resto)=getimagesize($TMP_img3);
			//echo "\n<br>Crop $TMP_img3 de ancho $anchoimg, $altoimg, $tipo<br>";
		}
	        if ($TMP_row2["accion_".$TMP_lang]!="") $TMP_row2["accion"]=$TMP_row2["accion_".$TMP_lang];
	        if ($TMP_row2["resumen_".$TMP_lang]!="") $TMP_row2["resumen"]=$TMP_row2["resumen_".$TMP_lang];
		if ($TMP_img3!="") {
			$TMP_imghtml='<img src="'.$TMP_img3.'">';
		} else {
			$TMP_imghtml='<br>';
		}
		$TMP.='
	<div id="pastilla">
          <ul'.$TMP_class.'>
            <li class="foto">'.$TMP_imghtml.'</li>
            <li class="contenido">
            <p class="titular"><a href="'.$PHP_SELF.'?V_dir='.$V_dir.'&V_mod=showaccion&term='.$term.'&id='.$id.'&idaccion='.$TMP_row2[idaccion].'">'.$TMP_row2[accion].'</a></p>
            <p>'.$TMP_row2[resumen].'</p>
            </li>
            <li class="progreso">  
            <p class="parrafo">'._DEF_NLSAccDesde.' <span class="negrita">'.$TMP_fecha.'</span><br>
            '.RAD_numero($TMP_row2[numfirmas],0).' '._DEF_NLSAccFirmas.'</p>
            <div class="'.$TMP_barra.'50"'.$TMP_stylebarra.'></div>
           <p class="necesitan"> '._DEF_NLSAccSenecesitan.' '.RAD_numero($TMP_pend,0).' '._DEF_NLSAccFirmasmas.'</p> </li>
          </ul>
	</div>
';
	}

return $TMP;
}

?>
