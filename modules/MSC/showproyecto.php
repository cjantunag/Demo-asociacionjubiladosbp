<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");
// Muestra la descripcion de un Proyecto

global $RAD_dbi, $dbname, $blocksoff, $idproyecto, $idproyectocategoria;
if ($dbname=="") $dbname=_DEF_dbname;

if (file_exists("modules/".$V_dir."/common.app.php")) include_once ("modules/".$V_dir."/common.app.php");
if (_DEF_appname!="" && _DEF_appname!="_DEF_appname") if (file_exists("modules/$V_dir/common.app."._DEF_appname.".php")) include_once ("modules/$V_dir/common.app."._DEF_appname.".php");
if (_DEF_appinstance!="" && _DEF_appinstance!="_DEF_appinstance") if (file_exists("modules/$V_dir/common.app."._DEF_appinstance.".php")) include_once ("modules/$V_dir/common.app."._DEF_appinstance.".php");

include_once("header.php");

if ($idproyecto>0) echo showProyecto($idproyecto);
else echo "\n\n\n".showProyectos($idproyectocategoria,$term)."\n\n\n";

include_once("footer.php");
return;
//---------------------------------------------------------------------------------------------------------------------------------
function showProyecto($idproyecto) {
	global $RAD_dbi, $term, $dbname, $V_dir, $cmd, $noprint, $PHP_SELF;
	$TMP_lang=getSessionVar("SESSION_lang");

	$TMP_URL=$PHP_SELF."?";
	foreach($_REQUEST as $TMP_k=>$TMP_v) if ($TMP_v!="") $TMP_URL.=$TMP_k."=".$TMP_v."&";
	$TMP_URL.="headeroff=&footeroff=&blocksoff=x";
	if ($noprint=="" && $cmd!="print") $TMP_print="<br><br><center><a href='".$TMP_URL."&cmd=print' target=_blank>"._DEF_NLSPrint."</a></center>";
	else if ($cmd=="print") $TMP_print="\n<script>\nwindow.print();\n</script>\n";

	$TMP_res=sql_query("SELECT * FROM proyectos where idproyecto=".converttosql($idproyecto), $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
	$TMP_Gfechafin=RAD_gregorianDate($TMP_row[fechafin]);
	$TMP_Gfecha=RAD_gregorianDate(date("Y-m-d"));
	if (!$TMP_Gfechafin>0) $TMP_activo="1";
	else if ($TMP_Gfechafin>$TMP_Gfecha) $TMP_activo="1";
	else $TMP_activo="0";
	if ($TMP_activo=="1") $TMP_class=' class="enmarcha"';
        else $TMP_class='';
        $TMP_img=RAD_primerFich($TMP_row[imagen]);
	if ($TMP_img=="") {
		$TMP_res3=sql_query("SELECT * from contenidos where idproyecto='".$TMP_row[idproyecto]."' and idcat='5' order by fechapubli DESC", $RAD_dbi);
		$TMP_row3=sql_fetch_array($TMP_res3, $RAD_dbi);
		$TMP_img=RAD_primerFich($TMP_row3[imagenes]);
	}
	if ($TMP_img!="") {
		include_once("modules/".$V_dir."/resizeCrop.php");
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
	$TMP_fechafin=RAD_showDate($TMP_row[fechafin]);
	if (!substr($TMP_row[fechafin],0,1)>0) $TMP_fechafin="Actualmente";
	if ($TMP_img3!="") {
		$TMP_imghtml='<img src="'.$TMP_img3.'" />';
	} else {
		$TMP_imghtml='<br>';
	}
	$TMP='
<h1>'.$TMP_row[proyecto].'</h1>
<div id="ficha">
	<ul'.$TMP_class.'>
		<li class="imgContainer">'.$TMP_imghtml.'</li>
		<li class="titular">'.$TMP_row[resumen].'</li>
		<li><p>'._DEF_NLSProy1.':<span class="negrita"> '.RAD_showDate($TMP_row[fechainicio]).' - '.$TMP_fechafin.'</span></p></li>
	</ul>
</div>
<div class="articulo">
<p style="margin: 0px 0px 0px 0px; border: none; padding: 0px 0px 0px 10px;">'.$TMP_row[descripcion].'</p>
</div>';
/*
	<li><span class="entrada">&Aacute;mbito xeogr&aacute;fico:</span> <span class="negrita">'.$TMP_row[ambito].'</span></li>
	<li><span class="entrada">Financiadores:</span> <span class="negrita">'.$TMP_row[financiadores].'</span></li>
	<li><span class="entrada">Socios:</span><span class="negrita">'.$TMP_row[socios].'</span></li>
	<li><span class="entrada">Orzamento:</span> <span class="negrita">'.$TMP_row[presupuesto].'</li>
	<li><span class="entrada">Obxectivos:</span>
	<ul>'.$TMP_row[objetivos].'</ul>
	</li>
	<li><span class="entrada">Beneficiarios/as:</span>
	<ul>'.$TMP_row[beneficiarios].'</ul>
	</li>
	<li><span class="entrada">Resultados acadados:</span>
	<ul>'.$TMP_row[resultados].'</ul>
	</li>
	<li><span class="entrada">Actividades principais:</span>
	<ol>
	<ol>'.$TMP_row[activs].'</ol>
	</li>
</ul>
';
*/

if (is_modulepermitted("", "MSC", "proyectos")) $TMP="<a target=_blank href='index.php?V_dir=MSC&V_mod=proyectos&func=edit&par0=$idproyecto'>"._DEF_NLSEdit."</a><br>".$TMP;

return $TMP;
}

//---------------------------------------------------------------------------------------------------------------------------------
function showProyectos($idproyectocategoria,$term) {
	global $RAD_dbi, $dbname, $V_dir, $V_mod;

	$TMP_lang=getSessionVar("SESSION_lang");

	$TMP_res=sql_query("SELECT * FROM proyectoscategorias where idproyectocategoria='$idproyectocategoria'", $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
        if ($TMP_row["categoria_".$TMP_lang]!="") $TMP_row["categoria"]=$TMP_row["categoria_".$TMP_lang];
        if ($TMP_row["descripcion_".$TMP_lang]!="") $TMP_row["descripcion"]=$TMP_row["descripcion_".$TMP_lang];

	if ($term=="") $TMP_class=' class="enmarcha"';
        else $TMP_class=' class="terminados"';

	$TMP='
	</div>
	<div id="columna_ancha">
        <h1>'.$TMP_row[categoria].'</h1>
	<p class="parrafo">'.$TMP_row[descripcion].'</p>
        <div id="que_facemos">
';

	$TMP_Gfecha=RAD_gregorianDate(date("Y-m-d"));
	//$TMP_res2=sql_query("SELECT * FROM proyectos where idproyectocategoria='$idproyectocategoria' order by orden", $RAD_dbi);
	$TMP_res2=sql_query("SELECT * FROM proyectos where idproyectocategoria='$idproyectocategoria' order by fechainicio DESC, proyecto ASC", $RAD_dbi);
	while($TMP_row2=sql_fetch_array($TMP_res2, $RAD_dbi)) {
		$TMP_Gfechafin=RAD_gregorianDate($TMP_row2[fechafin]);
		if (!$TMP_Gfechafin>0) $TMP_activo="1";
		else if ($TMP_Gfechafin>$TMP_Gfecha) $TMP_activo="1";
		else $TMP_activo="0";
		if ($TMP_activo=="1" && $term!="") continue;
		if ($TMP_activo!="1" && $term=="") continue;
        	$TMP_img=RAD_primerFich($TMP_row2[imagen]);
		$TMP_img3="";
		if ($TMP_img=="") {
			$TMP_res3=sql_query("SELECT * from contenidos where idproyecto='".$TMP_row2[idproyecto]."' and idcat='5' order by fechapubli DESC", $RAD_dbi);
			$TMP_row3=sql_fetch_array($TMP_res3, $RAD_dbi);
			$TMP_img=RAD_primerFich($TMP_row3[imagenes]);
		}
		if ($TMP_img!="") {
			include_once("modules/".$V_dir."/resizeCrop.php");
			$TMP_img3=resizeCrop("files/".$dbname."/".$TMP_img,"","207","120");
			list($anchoimg, $altoimg, $tipo, $resto)=getimagesize($TMP_img3);
			//echo "\n<br>Crop $TMP_img3 de ancho $anchoimg, $altoimg, $tipo<br>";
		}
	        if ($TMP_row2["proyecto_".$TMP_lang]!="") $TMP_row2["proyecto"]=$TMP_row2["proyecto_".$TMP_lang];
	        if ($TMP_row2["resumen_".$TMP_lang]!="") $TMP_row2["resumen"]=$TMP_row2["resumen_".$TMP_lang];
		if ($TMP_img3!="") {
			$TMP_imghtml='<img src="'.$TMP_img3.'" />';
		} else {
			$TMP_imghtml='<br>';
		}
		$TMP.='
	  <ul'.$TMP_class.'>
            <li class="imgContainer">'.$TMP_imghtml.'</li>
            <li class="titular"><a href="index.php?V_dir='.$V_dir.'&V_mod='.$V_mod.'&term='.$term.'&idproyecto='.$TMP_row2[idproyecto].'">'.$TMP_row2[proyecto].'</a></li>
            <li><p>'.$TMP_row2[resumen].'</p></li>
          </ul>';
	}

	$TMP.='
	</div>
';
return $TMP;
}

?>
