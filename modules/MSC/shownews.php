<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");
// Muestra las Noticias/Actividades

global $SESSION_SID, $RAD_dbi, $idcat, $dbname, $PHP_SELF, $id, $NLS_txtinfo, $NLS_volver, $NLS_archiv, $NLS_matrel, $idn;
if ($dbname=="") $dbname=_DEF_dbname;
$TMP_lang=getSessionVar("SESSION_lang");

$NLS_txtinfo=_DEF_NLSNewtxtinfo;
$NLS_volver=_DEF_NLSNewvolver;
if ($idcat=="7" || $idcat=="9" || $idcat=="10") $NLS_archiv=_DEF_NLSarchiv;
else $NLS_archiv=_DEF_NLSarchiva;
$NLS_matrel=_DEF_NLSNewmatrel;
$NLS_lugar=_DEF_NLSNewlugar;
$NLS_nohayvideos=_DEF_NLSNewnohayvideos;
$NLS_act=_DEF_NLSNewact;
$NLS_term=_DEF_NLSNewterm;
$NLS_accact=_DEF_NLSNewaccact;
$NLS_accter=_DEF_NLSNewaccter;
$NLS_fecha=_DEF_NLSNewfecha;
$NLS_y=_DEF_NLSNewy;
$NLS_tema=_DEF_NLSNewtema;
$NLS_clave=_DEF_NLSNewclave;

$TMP_idcat="";
if ($idcat!="") {
	$tmpdefaultfiltercat=" AND idcat='".$idcat."'";
} else {
	$TMP_result = sql_query("SELECT * FROM categorias WHERE categoria='news'", $RAD_dbi);
	while($TMP_row = sql_fetch_array($TMP_result, $RAD_dbi)) {
		$TMP_idcat.=",".$TMP_row["id"];
		if ($tmpdefaultfiltercat!="") $tmpdefaultfiltercat.=" OR ";
		$tmpdefaultfiltercat.="idcat='".$TMP_row["id"]."'";
	}
	$TMP_idcat.=",";
	if ($tmpdefaultfiltercat!="") $tmpdefaultfiltercat=" AND (".$tmpdefaultfiltercat.")";
}


if ($idn>0) {
	$TMP_res=sql_query("SELECT * FROM contenidos where id=".converttosql($idn), $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
        $TMP_img=RAD_primerFich($TMP_row[imagenes]);
        if ($TMP_img=="") { //RAD_convertHTML2TXT
		$TMP_res2=sql_query("SELECT * from contenidos where idcat='5' and idpadre=".converttosql($idn), $RAD_dbi);
		$TMP_row2=sql_fetch_array($TMP_res2, $RAD_dbi);
		$TMP_img=RAD_primerFich($TMP_row2[imagenes]);
		if ($TMP_img!="") $TMP_img='<meta property="og:image" content="'._DEF_URL.'files/'.$dbname.'/'.$TMP_img.'" />';
	}
	foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
	//if ($TMP_row[tema]!="") setSessionVar("SESSION_pagetitle",$TMP_row[tema].". "._DEF_SITENAME,0); // pone titulo y descriiption a pag.
	//if ($TMP_row[contenido]!="") setSessionVar("SESSION_description",str_replace("'","",$TMP_row[contenido]),0);
	setSessionVar("SESSION_addmetas",'<meta property="og:url" content="'._DEF_URL.'index.php?V_dir='.$V_dir.'&amp;V_mod='.$V_mod.'&amp;idn='.$idn.'" />
<meta property="og:title" content="'.str_replace('"','',RAD_convertHTML2TXT(html_entity_decode($TMP_row[tema],ENT_QUOTES,"UTF-8"))).'" />
<meta property="og:description" content="'.str_replace('"','',RAD_convertHTML2TXT(html_entity_decode($TMP_row[contenido],ENT_QUOTES,"UTF-8"))).'" />
'.$TMP_img.'
',0);
}


include_once("header.php");

if ($idcat=="10") echo showEnlaces();
else if ($idn>0) echo showNew($idn);
//else if ($idcat=="5") echo showImgs($tmpdefaultfiltercat);
else echo showNews($tmpdefaultfiltercat);

include_once("footer.php");
return;
//---------------------------------------------------------------------------------------------------------------------------------
function showEnlaces() {
	global $RAD_dbi, $dbname, $idcat, $V_dir, $NLS_volver, $NLS_archiv, $NLS_matrel, $NLS_lugar, $idproyecto, $idaccion, $V_idmod;

	$TMP_lang=getSessionVar("SESSION_lang");

	$TMP_conts="";
	$TMP_res=sql_query("SELECT * FROM contenidos where idcat='".$idcat."' order by contenido", $RAD_dbi);
	while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
		if ($TMP_row[fechapubli]>time() && $TMP_row[idcat]!="4") continue;
		if ($TMP_row[fechacalendario]>time() && $TMP_row[idcat]=="4") continue;
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
		$A_grupo[$TMP_row[tema]].="<li><a target=_blank href='".$TMP_row[urls]."'>".$TMP_row[contenido]."</a><br>".$TMP_row[observaciones]."</li>";
	}
	if(count($A_grupo)>0) {
		ksort($A_grupo);
		foreach($A_grupo as $TMP_grupo=>$TMP_links) {
			if (trim($TMP_grupo)=="") continue; // no se muestran enlaces sin Categoria
			$TMP_conts.='
          <li><span class="entrada">'.$TMP_grupo.'</span>
            <ul>'.$TMP_links.'
            </ul>
          </li>';
		}
	}
	$TMP_result2=sql_query("SELECT * from modulos where idmodulo='$V_idmod'", $RAD_dbi);
	$TMP_row2=sql_fetch_array($TMP_result2, $RAD_dbi);
	//$TMP_ruta="".$TMP_row2[grupomenu]." &gt; ".$TMP_row2[literalmenu]." ";
	foreach($TMP_row2 as $TMP_k=>$TMP_v) if ($TMP_row2[$TMP_k."_".$TMP_lang]!="") $TMP_row2[$TMP_k]=$TMP_row2[$TMP_k."_".$TMP_lang];
	$TMP_padre=$TMP_row2[literalmenu];
	$TMP='
        <h1>'.$TMP_padre.'</h1>
        <p class="destacado">'.$TMP_row2[observaciones].'</p>
        <ul class="lista_ficha">'.$TMP_conts.'
        </ul>
';

	if (is_modulepermitted("", "MSC", "enlaces")) $TMP="<a target=_blank href='index.php?V_dir=MSC&V_mod=enlaces&func=new'>"._DEF_NLSNew."</a><br>".$TMP;

	return $TMP;
}
//---------------------------------------------------------------------------------------------------------------------------------
function showNew($id) {
	global $RAD_dbi, $dbname, $idcat, $V_dir, $NLS_volver, $NLS_archiv, $NLS_matrel, $NLS_lugar, $idproyecto, $idaccion, $idartnews, $V_idmod;

	$TMP_lang=getSessionVar("SESSION_lang");
	$TMP_dirtheme="themes/".getSessionVar("SESSION_theme");

	$TMP_res=sql_query("SELECT * FROM contenidos where id=".converttosql($id)."", $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
	if ($TMP_row[fechapubli]>time() && $TMP_row[idcat]!="4") return "";
	if ($TMP_row[fechacalendario]>time() && $TMP_row[idcat]=="4") return "";
	$TMP_archivado=archivadoen($TMP_row[idarticulo],$TMP_row[idproyecto],$TMP_row[idmodulo],$TMP_row[idaccion]);
	foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
	$TMP_fecha=RAD_showdateint($TMP_row["fechapubli"]);
	$TMP_lugar="";
	if ($TMP_row[idcat]=="4") {
		$TMP_fecha=RAD_showdateint($TMP_row["fechapubli"]);
		$TMP_fecha.=" - ".RAD_showdateint($TMP_row["fechabaja"]); // Actividad fecha inicio - fin
		if (trim($TMP_row[lugar])!="") $TMP_lugar='<div class="destacado"><span>'.$NLS_lugar.':</span><br>
                    '.$TMP_row[lugar].'</div>';
	}
	if ($idproyecto>0) $TMP_padre=RAD_lookup("proyectos","proyecto","idproyecto",$idproyecto);
	else if ($idaccion>0) $TMP_padre=RAD_lookup("acciones","accion","idaccion",$idaccion);
	else if ($idartnews>0) $TMP_padre=RAD_lookup("articulos","tema","id",$idartnews);
	else if ($idmodnews>0) $TMP_padre=RAD_lookup("modulos","literalmenu","idmodulo",$idmodnews);
        $TMP_img=RAD_primerFich($TMP_row[imagen]);
	if ($TMP_img!="") {
		include_once("modules/".$V_dir."/resizeCrop.php");
		$TMP_img3=resizeCrop("files/".$dbname."/".$TMP_img,"","156","151");
		list($anchoimg, $altoimg, $tipo, $resto)=getimagesize($TMP_img3);
		//echo "\n<br>Escala $TMP_img3 de ancho $anchoimg, $altoimg, $tipo<br>";
	}
	if ($TMP_row[idcat]=="5") {
		$TMP_tema=""; $TMP_contimg=0;
		$TMP_icono=RAD_primerFich($TMP_row[imagenes]);
		if ($TMP_row[idarticulo]>0) $TMP_tema=RAD_lookup("articulos","nombre","id",$TMP_row[idarticulo]);
		if ($TMP_row[idproyecto]>0 && $TMP_tema=="") $TMP_tema=RAD_lookup("proyectos","proyecto","idproyecto",$TMP_row[idproyecto]);
		if ($TMP_row[idaccion]>0 && $TMP_tema=="") $TMP_tema=RAD_lookup("acciones","accion","idaccion",$TMP_row[idaccion]);
		//if ($A_temavisto[$TMP_tema]!="") continue;
		$A_temavisto[$TMP_tema]="X";
		if ($TMP_icono!="") {
			$TMP_contimg++;
			include_once("modules/".$V_dir."/resizeCrop.php");
			//$TMP_img3=resizeCrop("files/".$dbname."/".$TMP_icono,"","442","329");
			$TMP_img3=resizeCrop("files/".$dbname."/".$TMP_icono,"","442","");
			list($anchoimg, $altoimg, $tipo, $resto)=getimagesize($TMP_img3);
			//echo "\n<br>Crop $TMP_img3 de ancho $anchoimg, $altoimg, $tipo<br>";
			$TMP_imagenes='<div class="foto"><a href="files/'.$dbname.'/'.$TMP_icono.'" class="igroup'.$TMP_row[id].' cboxElement" title="&copy; '.$TMP_row[contenido].' | '.$TMP_fecha.' | '.$TMP_row[tema].'"><img src="'.$TMP_img3.'"></a>';
			// FALTA buscar otras imagenes del mismo tema sin poner su img y bloquear tema como ya visto. Hay que poner group0xxx
			$TMP_imagenes.='</div>';
		} else $TMP_imagenes="";
//	if (is_admin()) alert($TMP_row[idcat]."**".$TMP_imagenes);
	}

        $TMP_doc="files/".$dbname."/".RAD_primerFich($TMP_row[documentos]);
        $TMP_doc2=$PHP_SELF."?V_dir=MSC&V_mod=download&f=".RAD_primerFich($TMP_row[documentos]);
	$A_x=explode(".",$TMP_doc);
	$TMP_ext=strtoupper($A_x[count($A_x)-1]);
	$TMP_length=RAD_numero(round(filesize($TMP_doc)/1024,0),0)." KB";

	$TMP_result2=sql_query("SELECT * from modulos where idmodulo='$V_idmod'", $RAD_dbi);
	$TMP_row2=sql_fetch_array($TMP_result2, $RAD_dbi);
	//$TMP_ruta="".$TMP_row2[grupomenu]." &gt; ".$TMP_row2[literalmenu]." ";
	foreach($TMP_row2 as $TMP_k=>$TMP_v) if ($TMP_row2[$TMP_k."_".$TMP_lang]!="") $TMP_row2[$TMP_k]=$TMP_row2[$TMP_k."_".$TMP_lang];
	if ($TMP_padre=="") $TMP_padre=$TMP_row2[literalmenu];
	if ($TMP_row[ciudad]!="") $TMP_posfecha=" | ".$TMP_row[ciudad];
	else $TMP_posfecha="";

	list($TMP_imagenes,$TMP_enlaces,$TMP_videos,$TMP_relac)=relacionadosNew($id,"x");

	$TMP='
<link href="'.$TMP_dirtheme.'/colorbox/colorbox.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="'.$TMP_dirtheme.'/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="'.$TMP_dirtheme.'/jquery.colorbox.js"></script>
<script>
	$(document).ready(function(){
		//Examples of how to assign the Colorbox event to elements
		$(".igroup'.$id.'").colorbox({rel:\'igroup'.$id.'\', transition:"fade"});
		$(".group0").colorbox({rel:\'group0\', transition:"fade"});
		$(".group1").colorbox({rel:\'group1\'});
		$(".group2").colorbox({rel:\'group2\', transition:"fade"});
		$(".group3").colorbox({rel:\'group3\', transition:"none", width:"60%", height:"60%"});
		$(".group4").colorbox({rel:\'group4\', slideshow:true});
		$(".ajax").colorbox();
		$(".youtube").colorbox({iframe:true, innerWidth:640, innerHeight:390});
		$(".vimeo").colorbox({iframe:true, innerWidth:500, innerHeight:409});
		$(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});
		$(".inline").colorbox({inline:true, width:"50%"});
		$(".callbacks").colorbox({
			onOpen:function(){ alert(\'onOpen: colorbox is about to open\'); },
			onLoad:function(){ alert(\'onLoad: colorbox has started to load the targeted content\'); },
			onComplete:function(){ alert(\'onComplete: colorbox has displayed the loaded content\'); },
			onCleanup:function(){ alert(\'onCleanup: colorbox has begun the close process\'); },
			onClosed:function(){ alert(\'onClosed: colorbox has completely closed\'); }
		});
		$(\'.non-retina\').colorbox({rel:\'group5\', transition:\'none\'})
		$(\'.retina\').colorbox({rel:\'group5\', transition:\'none\', retinaImage:true, retinaUrl:true});
		//Example of preserving a JavaScript event for inline calls.
		$("#click").click(function(){ 
			$(\'#click\').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("Open this window again and this message will still be here.");
			return false;
		});
	});
</script>
        <h1>'.$TMP_padre.'</h1>
        <div class="volver"><a href="javascript:history.back();">'.$NLS_volver.'</a></div>
';

//----------- genera contenido de imagenes
		if ($TMP_row[idcat]=="5") {
			$TMP_tema=""; $TMP_contimg=0;
			$TMP_icono=RAD_primerFich($TMP_row[imagenes]);
			if ($TMP_icono=="") continue; // Si no hay foto no muestra nada
			if ($TMP_row[idarticulo]>0) $TMP_tema=RAD_lookup("articulos","nombre","id",$TMP_row[idarticulo]);
			if ($TMP_row[idproyecto]>0 && $TMP_tema=="") $TMP_tema=RAD_lookup("proyectos","proyecto","idproyecto",$TMP_row[idproyecto]);
			if ($TMP_row[idaccion]>0 && $TMP_tema=="") $TMP_tema=RAD_lookup("acciones","accion","idaccion",$TMP_row[idaccion]);
			//if ($A_temavisto[$TMP_tema]!="") continue;
			$A_temavisto[$TMP_tema]="X";
			if ($TMP_icono!="") {
				$TMP_contimg++;
				include_once("modules/".$V_dir."/resizeCrop.php");
				//$TMP_img3=resizeCrop("files/".$dbname."/".$TMP_icono,"","442","329");
				$TMP_img3=resizeCrop("files/".$dbname."/".$TMP_icono,"","442","");
				list($anchoimg, $altoimg, $tipo, $resto)=getimagesize($TMP_img3);
				//echo "\n<br>Crop $TMP_img3 de ancho $anchoimg, $altoimg, $tipo<br>";
				$TMP_imagenes='<div class="foto"><a href="files/'.$dbname.'/'.$TMP_icono.'" class="igroup'.$TMP_row[id].' cboxElement" title="&copy; '.$TMP_row[contenido].' | '.$TMP_fecha.' | '.$TMP_row[tema].'"><img src="'.$TMP_img3.'"></a>';
				// FALTA buscar otras imagenes del mismo tema sin poner su img y bloquear tema como ya visto. Hay que poner group0xxx
				$TMP_imagenes.='</div>';
			} else $TMP_imagenes="";
			$TMP.='
        <div class="noticia">
          <p class="titular">'.$TMP_row[tema].'</p>
          <p class="fecha">'.$TMP_fecha.'</p>
'.$TMP_imagenes.'
          <p class="copyright"><span>&copy;</span> '.$TMP_row[contenido].'</p>
          <p class="archivada">'.$NLS_archiv.':</p>
          <p class="ruta">'.$TMP_archivado.'</p>
        </div>
        <div class="volver"><a href="javascript:history.back();">'.$NLS_volver.'</a></div>
';
//--- fin de generacion de contenido de imagenes
		} else { // genera otros contenidos
			$TMP_compartir=include_once("modules/".$V_dir."/comparteredes.php");
			$TMP.='
        <div class="franja_azul">'.$TMP_row[tema].$TMP_compartir.'</div>
        <div class="noticia">
          <p class="fecha">'.$TMP_fecha.' '.$TMP_posfecha.'</p>
          <p class="entradilla">'.$TMP_row[contenido].'</p>
'.$TMP_imagenes.'
          <p>'.$TMP_row[observaciones].'</p>
'.$TMP_lugar.'
          <p class="archivada">'.$NLS_archiv.':</p>
          <p class="ruta">'.$TMP_archivado.'</p>
'.$TMP_relac.'
        </div>
        <div class="volver"><a href="javascript:history.back();">'.$NLS_volver.'</a></div>
';
		}

if ($idcat=="5" && is_modulepermitted("", "MSC", "imagenes")) $TMP="<a target=_blank href='index.php?V_dir=MSC&V_mod=imagenes&func=edit&par0=$id'>"._DEF_NLSEdit."</a><br>".$TMP;
else if ($idcat=="7" && is_modulepermitted("", "MSC", "documentos")) $TMP="<a target=_blank href='index.php?V_dir=MSC&V_mod=documentos&func=edit&par0=$id'>"._DEF_NLSEdit."</a><br>".$TMP;
else if ($idcat=="4" && is_modulepermitted("", "MSC", "actividades")) $TMP="<a target=_blank href='index.php?V_dir=MSC&V_mod=actividades&func=edit&par0=$id'>"._DEF_NLSEdit."</a><br>".$TMP;
else if ($idcat=="9" && is_modulepermitted("", "MSC", "videos")) $TMP="<a target=_blank href='index.php?V_dir=MSC&V_mod=videos&func=edit&par0=$id'>"._DEF_NLSEdit."</a><br>".$TMP;
else if (is_modulepermitted("", "MSC", "noticias")) $TMP="<a target=_blank href='index.php?V_dir=MSC&V_mod=noticias&func=edit&par0=$id'>"._DEF_NLSEdit."</a><br>".$TMP;

return $TMP;
}

//---------------------------------------------------------------------------------------------------------------------------------
function showNews($tmpdefaultfiltercat) {
	global $PHP_SELF, $RAD_dbi, $idcat, $dbname, $V_dir, $V_idmod, $V_mod, $pag, $month, $NLS_txtinfo, $idproyecto, $idaccion, $term, $idproyectocategoria, $idartnews, $idmodnews, $NLS_nohayvideos, $NLS_archiv, $mes1, $mes2, $ano1, $ano2, $idtema, $clave, $NLS_act, $NLS_term, $NLS_accact, $NLS_accter;

	if ($idtema*1>0) $idartnews=$idtema;
	if (substr($idtema,0,1)=="P") $idproyecto=substr($idtema,1);
	if (substr($idtema,0,1)=="X") $idaccion=substr($idtema,1);

	$MaxCharsItem=500;
	$MaxRegs=5;
	if ($idmodnews>0 && !$idproyectocategoria>0) {
		$TMP_mod=RAD_lookup("modulos","fichero","idmodulo",$idmodnews);
		if ($TMP_mod=="showproyecto") $idproyectocategoria=primercatproyecto();
	}

	$TMP_ini=0; $TMP_fin=0;
	if ($mes1>0 && $ano1>0) {
		if (strlen($mes1)==1) $TMP_mes1="0".$mes1;
		else $TMP_mes1=$mes1;
		$TMP_ini=strtotime($ano1."-".$TMP_mes1."-01");
	}
	if ($mes2>0 && $ano2>0) {
		$TMP_mes2=$mes2+1;
		if ($TMP_mes2>12) { $TMP_ano2=$ano2+1; $TMP_mes2="01"; }
		else { $TMP_ano2=$ano2; }
		if (strlen($TMP_mes2)==1) $TMP_mes2="0".$TMP_mes2;
		$TMP_fin=strtotime($TMP_ano2."-".$TMP_mes2."-01")-1;
	}

	$TMP_lang=getSessionVar("SESSION_lang");
	$TMP_dirtheme="themes/".getSessionVar("SESSION_theme");

	$TMP_time=time();
	$TMP_cont=0;
	if ($idcat=="4") {
		$where = " where (activo='1' OR activo IS NULL) AND fechacalendario<'$TMP_time' ".$tmpdefaultfiltercat;
		if ($TMP_ini>0) $where.=" and fechabaja>='$TMP_ini'";
		if ($TMP_fin>0) $where.=" and fechapubli<='$TMP_fin'";
	} else {
		$where = " where (activo='1' OR activo IS NULL)  AND fechapubli<'$TMP_time' ".$tmpdefaultfiltercat;
		if ($TMP_ini>0) $where.=" and fechapubli>='$TMP_ini'";
		if ($TMP_fin>0) $where.=" and fechapubli<='$TMP_fin'";
	}
	if ($clave!="") $where.=" and (tema like ".converttosql("%".$clave."%")." or contenido like ".converttosql("%".$clave."%").")";
	if ($month!="") {
		$A_x=explode("-",$month);
		$ano=$A_x[0];
		$mes=$A_x[1];
		$anop=$ano;
		$mesp=$mes+1;
		if ($mesp>12) {
			$mesp=1;
			$anop++;
		}
		if ($mesp<10) $mesp="0".$mesp;
		$TMP_fin=strtotime($anop."-".$mesp."-01 00:00:00")-1;
		$TMP_ini=strtotime($ano."-".$mes."-01 00:00:00");
		if ($idcat=="4") {
			$where.=" and fechabaja>='$TMP_ini' and fechapubli<='$TMP_fin'";
		} else {
			$where.=" and fechapubli>='$TMP_ini' and fechapubli<='$TMP_fin'";
		}
	}
	$TMP_result=sql_query("SELECT * from modulos where idmodulo='$V_idmod'", $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
	foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
	$TMP_ruta="".$TMP_row[grupomenu]." &gt; <span>".$TMP_row[literalmenu]."</span> ";
	$TMP_preruta="".$TMP_row[grupomenu]." &gt; ".$TMP_row[literalmenu]." ";
	$TMP_subruta="".$TMP_row[grupomenu]." &gt; <span>".$TMP_row[literalmenu]."</span>";
	$TMP_padre=$TMP_row[literalmenu];
	$TMP_postpadre=$TMP_row[observaciones];
	$TMP_Gfecha=RAD_gregorianDate(date("Y-m-d"));
	if ($idaccion>0) {
		$TMP_result = sql_query("SELECT * FROM acciones where idaccion='$idaccion'", $RAD_dbi);
		$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
		$TMP_Gfechafin=RAD_gregorianDate($TMP_row[fechafin]);
		if (!$TMP_Gfechafin>0) $TMP_activo="1";
		else if ($TMP_Gfechafin>$TMP_Gfecha) $TMP_activo="1";
		else $TMP_activo="0";
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
		$TMP_result2 = sql_query("SELECT * FROM articulossecciones where id='3'", $RAD_dbi);
		$TMP_row2=sql_fetch_array($TMP_result2, $RAD_dbi);
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
		$TMP_result3 = sql_query("SELECT * FROM articulos where id='44'", $RAD_dbi);
		$TMP_row3=sql_fetch_array($TMP_result3, $RAD_dbi);
		foreach($TMP_row3 as $TMP_k=>$TMP_v) if ($TMP_row3[$TMP_k."_".$TMP_lang]!="") $TMP_row3[$TMP_k]=$TMP_row3[$TMP_k."_".$TMP_lang];
		$TMP_padre=$TMP_row[accion];
		$TMP_subruta=$TMP_row2[nombre]." &gt; ".$TMP_row3[nombre]." &gt; ";
		if ($TMP_activo=="1") $TMP_subruta.="".$NLS_accact." &gt; ";
		else $TMP_subruta.="".$NLS_accter." &gt; ";
		$TMP_subruta.=" <span>".$TMP_row[accion]."</span>";
		$TMP_ruta=$TMP_preruta;
		$TMP_ruta.=" &gt; ".$TMP_subruta;
		$TMP_result = sql_query("SELECT * FROM acciones where idaccion='$idaccion'", $RAD_dbi);
		$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];

		$TMP_padre=$TMP_row[accion];
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
		$where.=" and idaccion='$idaccion'";
		$TMP_ruta=$TMP_preruta;
		$TMP_ruta.=" &gt; ".$TMP_subruta;
	} else if ($idproyecto>0) {
		$TMP_result = sql_query("SELECT * FROM proyectos where idproyecto='$idproyecto'", $RAD_dbi);
		$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
		$TMP_padre=$TMP_row[proyecto];
		$TMP_result2 = sql_query("SELECT * FROM proyectoscategorias where idproyectocategoria='".$TMP_row[idproyectocategoria]."'", $RAD_dbi);
		$TMP_row2=sql_fetch_array($TMP_result2, $RAD_dbi);
		foreach($TMP_row2 as $TMP_k=>$TMP_v) if ($TMP_row2[$TMP_k."_".$TMP_lang]!="") $TMP_row2[$TMP_k]=$TMP_row2[$TMP_k."_".$TMP_lang];
		$where.=" and idproyecto='$idproyecto'";
		$TMP_subruta="".$TMP_row2[categoria]." &gt; <span>".$TMP_row[proyecto]."</span>";
		$TMP_subruta=archivadoen("",$idproyecto,"","");
		$TMP_ruta=$TMP_preruta;
		$TMP_ruta.=" &gt; ".$TMP_subruta;
	} else if ($idproyectocategoria>0) {
		$TMP_result = sql_query("SELECT * FROM modulos where fichero='showproyecto'", $RAD_dbi);
		$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
		$TMP_subruta="".$TMP_row[grupomenu]." &gt; ";
		if ($term=="") $TMP_subruta.="".$NLS_act." &gt; ";
		else $TMP_subruta.="".$NLS_term." &gt; ";
		$TMP_result = sql_query("SELECT * FROM proyectoscategorias where idproyectocategoria='$idproyectocategoria'", $RAD_dbi);
		$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
	       	if ($TMP_row["categoria_".$TMP_lang]!="") $TMP_row["categoria"]=$TMP_row["categoria_".$TMP_lang];
		$TMP_padre=$TMP_row[categoria];
		$idproyectos="";
		$TMP_Gfecha=RAD_gregorianDate(date("Y-m-d"));
		$TMP_result = sql_query("SELECT * FROM proyectos where idproyectocategoria='$idproyectocategoria'", $RAD_dbi);
		while($TMP_row=sql_fetch_array($TMP_result, $RAD_dbi)) {
			foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
			$TMP_Gfechafin=RAD_gregorianDate($TMP_row[fechafin]);
			if (!$TMP_Gfechafin>0) $TMP_activo="1";
			else if ($TMP_Gfechafin>$TMP_Gfecha) $TMP_activo="1";
			else $TMP_activo="0";
			if ($term=="" && $TMP_activo=="0") continue;
			if ($term!="" && $TMP_activo=="1") continue;
			if ($idproyectos!="") $idproyectos.=",";
			$idproyectos.=$TMP_row[idproyecto];
		}
		if ($idproyectos=="") $where.=" and idproyecto='-1'";
		else $where.=" and idproyecto IN ($idproyectos)";
		//$TMP_subruta="".$TMP_padre." &gt; <span>".$TMP_row[literalmenu]."</span>";
		$TMP_subruta.=" <span> ".$TMP_padre." </span>";
		$TMP_ruta=$TMP_preruta;
		$TMP_ruta.=" &gt; ".$TMP_subruta;
	} else if ($idmodnews>0) {
		$TMP_result=sql_query("SELECT * from modulos where idmodulo='$idmodnews'", $RAD_dbi);
		$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
		$TMP_padre=$TMP_row["literalmenu"];
		$TMP_subruta="".$TMP_row[grupomenu]." &gt; <span>".$TMP_row[literalmenu]."</span>";
		$TMP_ruta=$TMP_preruta;
		$TMP_ruta.=" &gt; ".$TMP_subruta;
			// EXCEPCION //
		if ($idmodnews=="62") $where.=" and idarticulo='69'"; // el modulo 62 de Noticias equivale al articulo 69
		else if ($idmodnews=="121") $where.=" and idarticulo='70'"; // el modulo 121 de Actividades equivale al articulo 70
		else $where.=" and idmodulo='$idmodnews'";
	} else if ($idartnews>0) {
		$TMP_result = sql_query("SELECT * FROM articulos where id='$idartnews'", $RAD_dbi);
		$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
		$TMP_result2 = sql_query("SELECT * FROM articulossecciones where id='".$TMP_row[idseccion]."'", $RAD_dbi);
		$TMP_row2=sql_fetch_array($TMP_result2, $RAD_dbi);
		foreach($TMP_row2 as $TMP_k=>$TMP_v) if ($TMP_row2[$TMP_k."_".$TMP_lang]!="") $TMP_row2[$TMP_k]=$TMP_row2[$TMP_k."_".$TMP_lang];
		if ($TMP_row[idartparent]>0) {
			$TMP_resultp = sql_query("SELECT * FROM articulos where id='".$TMP_row[idartparent]."'", $RAD_dbi);
			$TMP_rowp=sql_fetch_array($TMP_resultp, $RAD_dbi);
			foreach($TMP_rowp as $TMP_k=>$TMP_v) if ($TMP_rowp[$TMP_k."_".$TMP_lang]!="") $TMP_rowp[$TMP_k]=$TMP_rowp[$TMP_k."_".$TMP_lang];
			$TMP_row2[nombre]=$TMP_row2[nombre]." &gt; ".$TMP_rowp[nombre];
		}
		$TMP_padre=$TMP_row[nombre];
		$TMP_subruta=$TMP_row2[nombre]." &gt; <span>".$TMP_row[nombre]."</span>";
		$TMP_ruta=$TMP_preruta;
		$TMP_ruta.=" &gt; ".$TMP_subruta;
		$where.=" and idarticulo='$idartnews'";
	}
	if ($idcat=="4") $where.=" and (fechabaja is null or fechabaja='0' or fechabaja='' or fechabaja>'".time()."') ";
	if ($idcat=="5" || $idcat=="7"||$idcat=="9") $where.=" and (idproyecto>0 or idaccion>0 or idarticulo>0)";
	//if ($idcat=="7"||$idcat=="9") $where.=" and (idpadre='' or idpadre is null or idpadre='0')";
//alert("Padre=".$TMP_padre.".subruta=".$TMP_subruta.".ruta=".$TMP_ruta);

	if ($idcat=="4") $order = " order by fechapubli DESC, fechabaja DESC";
	else $order = " order by fechapubli DESC";
	$cmdsql = "SELECT count(*) FROM contenidos ".$where;
//if (is_admin()) echo $idcat."*".$cmdsql."<br>";
	$TMP_result = sql_query($cmdsql, $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
	foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
	$numregs=$TMP_row[0];
	$numpags=ceil($numregs/$MaxRegs);
	if (!$pag>0) $pag="1";
	$pagbase=(floor(($pag-1)/5))*5;

	$TMP_link=$PHP_SELF."?";
	foreach($_REQUEST as $TMP_k=>$TMP_v) if (substr($TMP_k,0,1)!="_" && substr($TMP_k,0,3)!="PHP" && $TMP_v!="" && $TMP_k!="pag" && $TMP_k!="id") $TMP_link.=$TMP_k."=".$TMP_v."&";
	$TMP_link.="pag=";
 	$TMP_paginacion='       <ul id="paginacion">';
	if ($pagbase>0) {
		$TMP_pag=$pagbase;
		$TMP_paginacion.='          <li class="previous"><a href="'.$TMP_link.'1">&lt;</a></li> <li><a href="'.$TMP_link.$TMP_pag.'">...</a></li>';
	}
	for($ki=1; $ki<6; $ki++) {
		$TMP_pag=$pagbase+$ki;
		if ($TMP_pag>$numpags) continue;
		if ($TMP_pag==$pag) $TMP_paginacion.='          <li class="active">'.$TMP_pag.'</li>';
		else $TMP_paginacion.='          <li><a href="'.$TMP_link.$TMP_pag.'">'.$TMP_pag.'</a></li>';
	}
	$TMP_pag=$pagbase+6;
	if ($TMP_pag>$numpags) $TMP_pag=$pagbase+5;
	else $TMP_paginacion.='          <li class="next"><a href="'.$TMP_link.$TMP_pag.'">...</a></li> <li><a href="'.$TMP_link.$numpags.'">&gt;</a></li>';
 	$TMP_paginacion.='       </ul>';
	$TMP_noticias=""; $TMP_scriptcolorbox="";
	$TMP_link=$PHP_SELF."?";
	foreach($_REQUEST as $TMP_k=>$TMP_v) if (substr($TMP_k,0,1)!="_" && substr($TMP_k,0,3)!="PHP" && $TMP_v!="" && $TMP_k!="id") $TMP_link.=$TMP_k."=".$TMP_v."&";
	$TMP_link.="idn=";
	$inireg=($pag-1)*$MaxRegs;
	$limit=" LIMIT ".$inireg.",".($inireg+$MaxRegs);
	$limit=" LIMIT ".$inireg.",".$MaxRegs;
	$cmdsql = "SELECT * FROM contenidos ".$where.$order.$limit;
//if (is_admin()) echo $cmdsql."<br>";
	$TMP_result = sql_query($cmdsql, $RAD_dbi);
//echo $cmdsql."<br>";
	while($TMP_row=sql_fetch_array($TMP_result, $RAD_dbi)) {
		if ($idcat=="4" && $idproyectocategoria>0 && $term=="" && $TMP_row[fechabaja]>0 && $TMP_row[fechabaja]<time()) continue;
		if ($idcat=="4" && $idproyectocategoria>0 && $term!="" && $TMP_row[fechabaja]>0 && $TMP_row[fechabaja]>time()) continue;
		//if ($TMP_row[fechabaja]>0 && $TMP_row[fechabaja]<time()) continue; // no se muestran contenidos de baja
		$TMP_scriptcolorbox.='$(".igroup'.$TMP_row[id].'").colorbox({rel:\'igroup'.$TMP_row[id].'\', transition:"fade"});
';
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];

		$TMP_archivado=archivadoen($TMP_row[idarticulo],$TMP_row[idproyecto],$TMP_row[idmodulo],$TMP_row[idaccion]);

        	$TMP_doc="files/".$dbname."/".RAD_primerFich($TMP_row[documentos]);
        	$TMP_doc2=$PHP_SELF."?V_dir=MSC&V_mod=download&f=".RAD_primerFich($TMP_row[documentos]);
	        $A_x=explode(".",$TMP_doc);
		$TMP_ext=strtoupper($A_x[count($A_x)-1]);
		$TMP_length=RAD_numero(round(filesize($TMP_doc)/1024,0),0)." KB";

		$TMP_url="$PHP_SELF?V_dir=MSC&V_mod=shownews&idn=".$TMP_row[id].$SESSION_SID;
	
		$TMP_row["tema"]=str_replace("\"", "?", $TMP_row["tema"]);
		//$TMP_row["contenido"]=str_replace("\"", "?", $TMP_row["contenido"]);
		$TMP_fecha=RAD_showdateint($TMP_row["fechapubli"]);
		$TMP_new="<a href='".$TMP_url."&idn=".$TMP_row[id]."'>".$TMP_row["tema"]."</a>";
		if ($TMP_row[idcat]=="4") $TMP_fecha.=" - ".RAD_showdateint($TMP_row["fechabaja"]); // Actividad fecha inicio - fin
		//if (strlen($TMP_row[contenido])>$MaxCharsItem) $TMP_row[contenido]=trim(substr($TMP_row[contenido],0,$MaxCharsItem))." ...";
		//$TMP_new.="".$TMP_row[contenido]."</a><br><br>";
		$TMP_new=str_replace("\n", "", $TMP_new);
		$TMP_new=str_replace("\r", "", $TMP_new);
		if ($TMP_row[ciudad]!="") $TMP_posfecha=" | ".$TMP_row[ciudad];
		else $TMP_posfecha="";

		list($TMP_imagenes,$TMP_enlaces,$TMP_videos,$TMP_relac)=relacionadosNew($TMP_row[id],"");

		if ($TMP_videos==$NLS_nohayvideos) $TMP_videos="";
		$TMP_enlaces=""; // En la portada nunca se muestran enlaces

		if ($TMP_img!="") $content.='<img src="files/'.$dbname.'/'.$TMP_img.'" />';
//----------- genera contenido de contenido
		if ($idcat=="9") {
			$TMP_icono=RAD_primerFich($TMP_row[imagenes]);
			if ($TMP_icono!="") {
				include_once("modules/".$V_dir."/resizeCrop.php");
				//$TMP_img3=resizeCrop("files/".$dbname."/".$TMP_icono,"","442","329");
				$TMP_img3=resizeCrop("files/".$dbname."/".$TMP_icono,"","442","");
				list($anchoimg, $altoimg, $tipo, $resto)=getimagesize($TMP_img3);
				//echo "\n<br>Crop $TMP_img3 de ancho $anchoimg, $altoimg, $tipo<br>";
				$TMP_icono='<div class="foto"><a target=_blank href="'.$TMP_row[urls].' target=_blank"><img src="'.$TMP_img3.'"></a></div>';
			}
			$TMP_noticias.='
        <div class="noticia">
          <p class="titular"><a target=_blank href="'.$TMP_row[urls].'" target=_blank><img src="themes/'._DEF_THEME.'/ico_video.gif" width="11" height="11"> '.$TMP_row[tema].'</a></p>
          <p class="fecha">'.$TMP_fecha.' | <span>'.$TMP_row[observaciones].'</span> </p>
          <p>'.$TMP_row[contenido].'</p>'.$TMP_icono.'
          <p class="archivada">'.$NLS_archiv.':</p>
          <p class="ruta">'.$TMP_archivado.'</p>
        </div>';
		} else if ($idcat=="5") {
			$TMP_tema=""; $TMP_contimg=0;
			$TMP_icono=RAD_primerFich($TMP_row[imagenes]);
			if ($TMP_icono=="") continue; // Si no hay foto no muestra nada
			if ($TMP_row[idarticulo]>0) $TMP_tema=RAD_lookup("articulos","nombre","id",$TMP_row[idarticulo]);
			if ($TMP_row[idproyecto]>0 && $TMP_tema=="") $TMP_tema=RAD_lookup("proyectos","proyecto","idproyecto",$TMP_row[idproyecto]);
			if ($TMP_row[idaccion]>0 && $TMP_tema=="") $TMP_tema=RAD_lookup("acciones","accion","idaccion",$TMP_row[idaccion]);
			//if ($A_temavisto[$TMP_tema]!="") continue;
			$A_temavisto[$TMP_tema]="X";
			if ($TMP_icono!="") {
				$TMP_contimg++;
				include_once("modules/".$V_dir."/resizeCrop.php");
				//$TMP_img3=resizeCrop("files/".$dbname."/".$TMP_icono,"","442","329");
				$TMP_img3=resizeCrop("files/".$dbname."/".$TMP_icono,"","442","");
				list($anchoimg, $altoimg, $tipo, $resto)=getimagesize($TMP_img3);
				//echo "\n<br>Crop $TMP_img3 de ancho $anchoimg, $altoimg, $tipo<br>";
				$TMP_imagenes='<div class="foto"><a href="files/'.$dbname.'/'.$TMP_icono.'" class="igroup'.$TMP_row[id].' cboxElement" title="&copy; '.$TMP_row[contenido].' | '.$TMP_fecha.' | '.$TMP_row[tema].'"><img src="'.$TMP_img3.'"></a>';
				// FALTA buscar otras imagenes del mismo tema sin poner su img y bloquear tema como ya visto. Hay que poner group0xxx
				$TMP_imagenes.='</div>';
			} else $TMP_imagenes="";
			$TMP_noticias.='
        <div class="noticia">
          <p class="titular">'.$TMP_row[tema].'</p>
          <p class="fecha">'.$TMP_fecha.'</p>
'.$TMP_imagenes.'
          <p class="copyright"><span>&copy;</span> '.$TMP_row[contenido].'</p>
          <p class="archivada">'.$NLS_archiv.':</p>
          <p class="ruta">'.$TMP_archivado.'</p>
        </div>';
		} else if ($idcat=="7") {
			$TMP_noticias.='
        <div class="noticia">
          <p class="titular"><a href="'.$TMP_doc2.'"><img src="themes/'._DEF_THEME.'/ico_descarga.gif" width="11" height="11"> '.$TMP_row[tema].'</a></p>
          <p class="fecha">'.$TMP_fecha.' | <span><a href="'.$TMP_doc2.'">'.$TMP_ext.'</a></span> | <span>'.$TMP_length.'</span> </p>
          <p>'.$TMP_row[contenido].'</p>
          <p class="archivada">'.$NLS_archiv.':</p>
          <p class="ruta">'.$TMP_archivado.'</p>
        </div>';
		} else {
			$TMP_noticias.='
        <div class="noticia">
          <p class="titular"><a href="'.$TMP_link.$TMP_row[id].'">'.$TMP_row[tema].'</a></p>
          <p class="fecha">'.$TMP_fecha.$TMP_posfecha.' </p>
          <p>'.$TMP_row[contenido].'</p>
'.$TMP_imagenes.'
'.$TMP_videos.'
'.$TMP_enlaces.'
          <div class="mas_info"><a href="'.$TMP_link.$TMP_row[id].'">'.$NLS_txtinfo.'</a></div>
        </div>';
		}
//--- fin de generacion de contenido
	}

	if ($idcat=="3"||$idcat=="4"||$idcat=="5"||$idcat=="7"||$idcat=="9") $TMP_filtro=filtro();
	$TMP='
<link href="'.$TMP_dirtheme.'/colorbox/colorbox.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="'.$TMP_dirtheme.'/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="'.$TMP_dirtheme.'/jquery.colorbox.js"></script>
<script>
      $(document).ready(function(){$("a[rel=\'galeria\']").colorbox();})
	$(document).ready(function(){
		//Examples of how to assign the Colorbox event to elements
'.$TMP_scriptcolorbox.'
		$(".group0").colorbox({rel:\'group0\', transition:"fade"});
		$(".group1").colorbox({rel:\'group1\'});
		$(".group2").colorbox({rel:\'group2\', transition:"fade"});
		$(".group3").colorbox({rel:\'group3\', transition:"none", width:"60%", height:"60%"});
		$(".group4").colorbox({rel:\'group4\', slideshow:true});
		$(".ajax").colorbox();
		$(".youtube").colorbox({iframe:true, innerWidth:640, innerHeight:390});
		$(".vimeo").colorbox({iframe:true, innerWidth:500, innerHeight:409});
		$(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});
		$(".inline").colorbox({inline:true, width:"50%"});
		$(".callbacks").colorbox({
			onOpen:function(){ alert(\'onOpen: colorbox is about to open\'); },
			onLoad:function(){ alert(\'onLoad: colorbox has started to load the targeted content\'); },
			onComplete:function(){ alert(\'onComplete: colorbox has displayed the loaded content\'); },
			onCleanup:function(){ alert(\'onCleanup: colorbox has begun the close process\'); },
			onClosed:function(){ alert(\'onClosed: colorbox has completely closed\'); }
		});
		$(\'.non-retina\').colorbox({rel:\'group5\', transition:\'none\'})
		$(\'.retina\').colorbox({rel:\'group5\', transition:\'none\', retinaImage:true, retinaUrl:true});
		//Example of preserving a JavaScript event for inline calls.
		$("#click").click(function(){ 
			$(\'#click\').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("Open this window again and this message will still be here.");
			return false;
		});
	});
</script>
        <h1>'.$TMP_padre.'</h1>
'.$TMP_postpadre.'
'.$TMP_filtro.'
'.$TMP_paginacion.'
'.$TMP_noticias.'
        '.$TMP_paginacion.'
<script>
document.getElementById("ruta").innerHTML="'.$TMP_ruta.'";
</script>
';

if ($idcat=="5" && is_modulepermitted("", "MSC", "imagenes")) $TMP="<a target=_blank href='index.php?V_dir=MSC&V_mod=imagenes&func=new'>"._DEF_NLSNew."</a><br>".$TMP;
else if ($idcat=="7" && is_modulepermitted("", "MSC", "documentos")) $TMP="<a target=_blank href='index.php?V_dir=MSC&V_mod=documentos&func=new'>"._DEF_NLSNew."</a><br>".$TMP;
else if ($idcat=="4" && is_modulepermitted("", "MSC", "actividades")) $TMP="<a target=_blank href='index.php?V_dir=MSC&V_mod=actividades&func=new'>"._DEF_NLSNew."</a><br>".$TMP;
else if ($idcat=="9" && is_modulepermitted("", "MSC", "videos")) $TMP="<a target=_blank href='index.php?V_dir=MSC&V_mod=videos&func=new'>"._DEF_NLSNew."</a><br>".$TMP;
else if (is_modulepermitted("", "MSC", "noticias")) $TMP="<a target=_blank href='index.php?V_dir=MSC&V_mod=noticias&func=new'>"._DEF_NLSNew."</a><br>".$TMP;

return $TMP;
}

//---------------------------------------------------------------------------------------------------------------------------------
function showImgs($tmpdefaultfiltercat) {
	global $PHP_SELF, $RAD_dbi, $idcat, $dbname, $V_dir, $V_idmod, $V_mod, $pag, $month, $NLS_txtinfo, $idproyecto, $idaccion, $term, $idproyectocategoria, $idartnews, $idmodnews, $NLS_nohayvideos, $NLS_archiv, $mes1, $mes2, $ano1, $ano2, $idtema, $clave, $NLS_act, $NLS_term, $NLS_accact, $NLS_accter;

	if ($idtema*1>0) $idartnews=$idtema;
	if (substr($idtema,0,1)=="P") $idproyecto=substr($idtema,1);
	if (substr($idtema,0,1)=="X") $idaccion=substr($idtema,1);

	$MaxCharsItem=500;
	$MaxRegs=5;
	if ($idmodnews>0 && !$idproyectocategoria>0) {
		$TMP_mod=RAD_lookup("modulos","fichero","idmodulo",$idmodnews);
		if ($TMP_mod=="showproyecto") $idproyectocategoria=primercatproyecto();
	}

	$TMP_ini=0; $TMP_fin=0;
	if ($mes1>0 && $ano1>0) {
		if (strlen($mes1)==1) $TMP_mes1="0".$mes1;
		else $TMP_mes1=$mes1;
		$TMP_ini=strtotime($ano1."-".$TMP_mes1."-01");
	}
	if ($mes2>0 && $ano2>0) {
		$TMP_mes2=$mes2+1;
		if ($TMP_mes2>12) { $TMP_ano2=$ano2+1; $TMP_mes2="01"; }
		else { $TMP_ano2=$ano2; }
		if (strlen($TMP_mes2)==1) $TMP_mes2="0".$TMP_mes2;
		$TMP_fin=strtotime($TMP_ano2."-".$TMP_mes2."-01")-1;
	}

	$TMP_lang=getSessionVar("SESSION_lang");
	$TMP_dirtheme="themes/".getSessionVar("SESSION_theme");

	$TMP_time=time();
	$TMP_cont=0;
	$where = " where (activo='1' OR activo IS NULL)  AND fechapubli<'$TMP_time' ".$tmpdefaultfiltercat;
	if ($TMP_ini>0) $where.=" and fechapubli>='$TMP_ini'";
	if ($TMP_fin>0) $where.=" and fechapubli<='$TMP_fin'";
	if ($clave!="") $where.=" and (tema like ".converttosql("%".$clave."%")." or contenido like ".converttosql("%".$clave."%").")";
	if ($month!="") {
		$A_x=explode("-",$month);
		$ano=$A_x[0];
		$mes=$A_x[1];
		$anop=$ano;
		$mesp=$mes+1;
		if ($mesp>12) {
			$mesp=1;
			$anop++;
		}
		if ($mesp<10) $mesp="0".$mesp;
		$TMP_fin=strtotime($anop."-".$mesp."-01 00:00:00")-1;
		$TMP_ini=strtotime($ano."-".$mes."-01 00:00:00");
		$where.=" and fechapubli>='$TMP_ini' and fechapubli<='$TMP_fin'";
	}
	$TMP_result=sql_query("SELECT * from modulos where idmodulo='$V_idmod'", $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
	foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
	$TMP_ruta="".$TMP_row[grupomenu]." &gt; <span>".$TMP_row[literalmenu]."</span> ";
	$TMP_preruta="".$TMP_row[grupomenu]." &gt; ".$TMP_row[literalmenu]." ";
	$TMP_subruta="".$TMP_row[grupomenu]." &gt; <span>".$TMP_row[literalmenu]."</span>";
	$TMP_padre=$TMP_row[literalmenu];
	$TMP_postpadre=$TMP_row[observaciones];
	if ($idaccion>0) {
		$TMP_result = sql_query("SELECT * FROM articulos where id='$idartnews'", $RAD_dbi);
		$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
		$TMP_result2 = sql_query("SELECT * FROM articulossecciones where id='".$TMP_row[idseccion]."'", $RAD_dbi);
		$TMP_row2=sql_fetch_array($TMP_result2, $RAD_dbi);
		foreach($TMP_row2 as $TMP_k=>$TMP_v) if ($TMP_row2[$TMP_k."_".$TMP_lang]!="") $TMP_row2[$TMP_k]=$TMP_row2[$TMP_k."_".$TMP_lang];
		$TMP_padre=$TMP_row[nombre];
		$TMP_subruta=$TMP_row2[nombre]." &gt; ".$TMP_row[nombre]." ";
		$TMP_ruta=$TMP_preruta;
		$TMP_ruta.=" &gt; ".$TMP_subruta;
		$TMP_result = sql_query("SELECT * FROM acciones where idaccion='$idaccion'", $RAD_dbi);
		$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];

		$TMP_padre=$TMP_row[accion];
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
		$where.=" and idaccion='$idaccion'";
		$TMP_ruta=$TMP_preruta;
		$TMP_ruta.=" &gt; ".$TMP_subruta;
	} else if ($idproyecto>0) {
		$TMP_result = sql_query("SELECT * FROM proyectos where idproyecto='$idproyecto'", $RAD_dbi);
		$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
		$TMP_padre=$TMP_row[proyecto];
		$TMP_result2 = sql_query("SELECT * FROM proyectoscategorias where idproyectocategoria='".$TMP_row[idproyectocategoria]."'", $RAD_dbi);
		$TMP_row2=sql_fetch_array($TMP_result2, $RAD_dbi);
		foreach($TMP_row2 as $TMP_k=>$TMP_v) if ($TMP_row2[$TMP_k."_".$TMP_lang]!="") $TMP_row2[$TMP_k]=$TMP_row2[$TMP_k."_".$TMP_lang];
		$where.=" and idproyecto='$idproyecto'";
		$TMP_subruta="".$TMP_row2[categoria]." &gt; <span>".$TMP_row[proyecto]."</span>";
		$TMP_subruta=archivadoen("",$idproyecto,"","");
		$TMP_ruta=$TMP_preruta;
		$TMP_ruta.=" &gt; ".$TMP_subruta;
	} else if ($idproyectocategoria>0) {
		$TMP_result = sql_query("SELECT * FROM modulos where fichero='showproyecto'", $RAD_dbi);
		$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
		$TMP_subruta="".$TMP_row[grupomenu]." &gt; ";
		if ($term=="") $TMP_subruta.="".$NLS_act." &gt; ";
		else $TMP_subruta.="".$NLS_term." &gt; ";
		$TMP_result = sql_query("SELECT * FROM proyectoscategorias where idproyectocategoria='$idproyectocategoria'", $RAD_dbi);
		$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
	       	if ($TMP_row["categoria_".$TMP_lang]!="") $TMP_row["categoria"]=$TMP_row["categoria_".$TMP_lang];
		$TMP_padre=$TMP_row[categoria];
		$idproyectos="";
		$TMP_result = sql_query("SELECT * FROM proyectos where idproyectocategoria='$idproyectocategoria'", $RAD_dbi);
		while($TMP_row=sql_fetch_array($TMP_result, $RAD_dbi)) {
			foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
			if ($idproyectos!="") $idproyectos.=",";
			$idproyectos.=$TMP_row[idproyecto];
		}
		if ($idproyectos=="") $where.=" and idproyecto='-1'";
		else $where.=" and idproyecto IN ($idproyectos)";
		//$TMP_subruta="".$TMP_padre." &gt; <span>".$TMP_row[literalmenu]."</span>";
		$TMP_subruta.=" <span> ".$TMP_padre." </span>";
		$TMP_ruta=$TMP_preruta;
		$TMP_ruta.=" &gt; ".$TMP_subruta;
	} else if ($idmodnews>0) {
		$TMP_result=sql_query("SELECT * from modulos where idmodulo='$idmodnews'", $RAD_dbi);
		$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
		$TMP_padre=$TMP_row["literalmenu"];
		$TMP_subruta="".$TMP_row[grupomenu]." &gt; <span>".$TMP_row[literalmenu]."</span>";
		$TMP_ruta=$TMP_preruta;
		$TMP_ruta.=" &gt; ".$TMP_subruta;
		$where.=" and idmodulo='$idmodnews'";
	} else if ($idartnews>0) {
		$TMP_result = sql_query("SELECT * FROM articulos where id='$idartnews'", $RAD_dbi);
		$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
		$TMP_result2 = sql_query("SELECT * FROM articulossecciones where id='".$TMP_row[idseccion]."'", $RAD_dbi);
		$TMP_row2=sql_fetch_array($TMP_result2, $RAD_dbi);
		foreach($TMP_row2 as $TMP_k=>$TMP_v) if ($TMP_row2[$TMP_k."_".$TMP_lang]!="") $TMP_row2[$TMP_k]=$TMP_row2[$TMP_k."_".$TMP_lang];
		$TMP_padre=$TMP_row[nombre];
		$TMP_subruta=$TMP_row2[nombre]." &gt; <span>".$TMP_row[nombre]."</span>";
		$TMP_ruta=$TMP_preruta;
		$TMP_ruta.=" &gt; ".$TMP_subruta;
		$where.=" and idarticulo='$idartnews'";
	}

	$order = " order by fechapubli DESC";
	$cmdsql = "SELECT * FROM contenidos ".$where;
//if (is_admin()) echo $cmdsql."<br>";
	$TMP_result = sql_query($cmdsql, $RAD_dbi);
	$numregs=0;
	while($TMP_row=sql_fetch_array($TMP_result, $RAD_dbi)) {
		$TMP_icono=RAD_primerFich($TMP_row[imagenes]);
		if ($TMP_icono=="") continue;
		if ($TMP_row[idproyecto]>0) {
			$A_galeria[$TMP_row[idproyecto]].=$TMP_icono;
		} // Aqui lo deje porque es un lio
		$numregs++;
	}
	$numpags=ceil($numregs/$MaxRegs);
	if (!$pag>0) $pag="1";
	$pagbase=(floor(($pag-1)/5))*5;

	$TMP_link=$PHP_SELF."?";
	foreach($_REQUEST as $TMP_k=>$TMP_v) if (substr($TMP_k,0,1)!="_" && substr($TMP_k,0,3)!="PHP" && $TMP_v!="" && $TMP_k!="pag" && $TMP_k!="id") $TMP_link.=$TMP_k."=".$TMP_v."&";
	$TMP_link.="pag=";
 	$TMP_paginacion='       <ul id="paginacion">';
	if ($pagbase>0) {
		$TMP_pag=$pagbase;
		$TMP_paginacion.='          <li class="previous"><a href="'.$TMP_link.'1">&lt;</a></li> <li><a href="'.$TMP_link.$TMP_pag.'">...</a></li>';
	}
	for($ki=1; $ki<6; $ki++) {
		$TMP_pag=$pagbase+$ki;
		if ($TMP_pag>$numpags) continue;
		if ($TMP_pag==$pag) $TMP_paginacion.='          <li class="active">'.$TMP_pag.'</li>';
		else $TMP_paginacion.='          <li><a href="'.$TMP_link.$TMP_pag.'">'.$TMP_pag.'</a></li>';
	}
	$TMP_pag=$pagbase+6;
	if ($TMP_pag>$numpags) $TMP_pag=$pagbase+5;
	else $TMP_paginacion.='          <li class="next"><a href="'.$TMP_link.$TMP_pag.'">...</a></li> <li><a href="'.$TMP_link.$numpags.'">&gt;</a></li>';
 	$TMP_paginacion.='       </ul>';
	$TMP_noticias=""; $TMP_scriptcolorbox="";
	$TMP_link=$PHP_SELF."?";
	foreach($_REQUEST as $TMP_k=>$TMP_v) if (substr($TMP_k,0,1)!="_" && substr($TMP_k,0,3)!="PHP" && $TMP_v!="" && $TMP_k!="id") $TMP_link.=$TMP_k."=".$TMP_v."&";
	$TMP_link.="idn=";
	$inireg=($pag-1)*$MaxRegs;
	$limit=" LIMIT ".$inireg.",".($inireg+$MaxRegs);
	$limit=" LIMIT ".$inireg.",".$MaxRegs;
	$cmdsql = "SELECT * FROM contenidos ".$where.$order.$limit;
//if (is_admin()) echo $cmdsql."<br>";
	$TMP_result = sql_query($cmdsql, $RAD_dbi);
//echo $cmdsql."<br>";
	while($TMP_row=sql_fetch_array($TMP_result, $RAD_dbi)) {
		//if ($TMP_row[fechabaja]>0 && $TMP_row[fechabaja]<time()) continue; // no se muestran contenidos de baja
		$TMP_scriptcolorbox.='$(".igroup'.$TMP_row[id].'").colorbox({rel:\'igroup'.$TMP_row[id].'\', transition:"fade"});
';
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];

		$TMP_archivado=archivadoen($TMP_row[idarticulo],$TMP_row[idproyecto],$TMP_row[idmodulo],$TMP_row[idaccion]);

        	$TMP_doc="files/".$dbname."/".RAD_primerFich($TMP_row[documentos]);
        	$TMP_doc2=$PHP_SELF."?V_dir=MSC&V_mod=download&f=".RAD_primerFich($TMP_row[documentos]);
	        $A_x=explode(".",$TMP_doc);
		$TMP_ext=strtoupper($A_x[count($A_x)-1]);
		$TMP_length=RAD_numero(round(filesize($TMP_doc)/1024,0),0)." KB";

		$TMP_url="$PHP_SELF?V_dir=MSC&V_mod=shownews&idn=".$TMP_row[id].$SESSION_SID;
	
		$TMP_row["tema"]=str_replace("\"", "?", $TMP_row["tema"]);
		//$TMP_row["contenido"]=str_replace("\"", "?", $TMP_row["contenido"]);
		$TMP_fecha=RAD_showdateint($TMP_row["fechapubli"]);
		$TMP_new="<a href='".$TMP_url."&idn=".$TMP_row[id]."'>".$TMP_row["tema"]."</a>";
		$TMP_new=str_replace("\n", "", $TMP_new);
		$TMP_new=str_replace("\r", "", $TMP_new);
		if ($TMP_row[ciudad]!="") $TMP_posfecha=" | ".$TMP_row[ciudad];
		else $TMP_posfecha="";

		list($TMP_imagenes,$TMP_enlaces,$TMP_videos,$TMP_relac)=relacionadosNew($TMP_row[id],"");
		if ($TMP_videos==$NLS_nohayvideos) $TMP_videos="";
		if ($TMP_videos!="") $TMP_enlaces=$TMP_videos;

		if ($idcat=="5") {
			$TMP_tema=""; $TMP_contimg=0;
			$TMP_icono=RAD_primerFich($TMP_row[imagenes]);
			if ($TMP_icono=="") continue; // Si no hay foto no muestra nada
			if ($TMP_row[idarticulo]>0) $TMP_tema=RAD_lookup("articulos","nombre","id",$TMP_row[idarticulo]);
			if ($TMP_row[idproyecto]>0 && $TMP_tema=="") $TMP_tema=RAD_lookup("proyectos","proyecto","idproyecto",$TMP_row[idproyecto]);
			if ($TMP_row[idaccion]>0 && $TMP_tema=="") $TMP_tema=RAD_lookup("acciones","accion","idaccion",$TMP_row[idaccion]);
			//if ($A_temavisto[$TMP_tema]!="") continue;
			$A_temavisto[$TMP_tema]="X";
			if ($TMP_icono!="") {
				$TMP_contimg++;
				include_once("modules/".$V_dir."/resizeCrop.php");
				//$TMP_img3=resizeCrop("files/".$dbname."/".$TMP_icono,"","442","329");
				$TMP_img3=resizeCrop("files/".$dbname."/".$TMP_icono,"","442","");
				list($anchoimg, $altoimg, $tipo, $resto)=getimagesize($TMP_img3);
				//echo "\n<br>Crop $TMP_img3 de ancho $anchoimg, $altoimg, $tipo<br>";
				$TMP_imagenes='<div class="foto"><a href="files/'.$dbname.'/'.$TMP_icono.'" class="igroup'.$TMP_row[id].' cboxElement" title="&copy; '.$TMP_row[contenido].' | '.$TMP_fecha.' | '.$TMP_row[tema].'"><img src="'.$TMP_img3.'"></a>';
				// FALTA buscar otras imagenes del mismo tema sin poner su img y bloquear tema como ya visto. Hay que poner group0xxx
				$TMP_imagenes.='</div>';
			} else $TMP_imagenes="";
			$TMP_noticias.='
        <div class="noticia">
          <p class="titular">'.$TMP_tema.'</p>
	  <p class="paginacion">1 de '.$TMP_contimg.'</p>
'.$TMP_imagenes.'
          <p class="copyright"><span>&copy;</span> '.$TMP_row[contenido].'</p>
          <p class="archivada">'.$NLS_archiv.':</p>
          <p class="ruta">'.$TMP_archivado.'</p>
        </div>';
		} else if ($idcat=="7") {
			$TMP_noticias.='
        <div class="noticia">
          <p class="titular"><a href="'.$TMP_doc2.'"><img src="themes/'._DEF_THEME.'/ico_descarga.gif" width="11" height="11"> '.$TMP_row[tema].'</a></p>
          <p class="fecha">'.$TMP_fecha.' | <span><a href="'.$TMP_doc2.'">'.$TMP_ext.'</a></span> | <span>'.$TMP_length.'</span> </p>
          <p>'.$TMP_row[contenido].'</p>
          <p class="archivada">'.$NLS_archiv.':</p>
          <p class="ruta">'.$TMP_archivado.'</p>
        </div>';
		}
	}

	$TMP_filtro=filtro();
	$TMP='
<link href="'.$TMP_dirtheme.'/colorbox/colorbox.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="'.$TMP_dirtheme.'/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="'.$TMP_dirtheme.'/jquery.colorbox.js"></script>
<script>
      $(document).ready(function(){$("a[rel=\'galeria\']").colorbox();})
	$(document).ready(function(){
		//Examples of how to assign the Colorbox event to elements
'.$TMP_scriptcolorbox.'
		$(".group0").colorbox({rel:\'group0\', transition:"fade"});
		$(".group1").colorbox({rel:\'group1\'});
		$(".group2").colorbox({rel:\'group2\', transition:"fade"});
		$(".group3").colorbox({rel:\'group3\', transition:"none", width:"60%", height:"60%"});
		$(".group4").colorbox({rel:\'group4\', slideshow:true});
		$(".ajax").colorbox();
		$(".youtube").colorbox({iframe:true, innerWidth:640, innerHeight:390});
		$(".vimeo").colorbox({iframe:true, innerWidth:500, innerHeight:409});
		$(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});
		$(".inline").colorbox({inline:true, width:"50%"});
		$(".callbacks").colorbox({
			onOpen:function(){ alert(\'onOpen: colorbox is about to open\'); },
			onLoad:function(){ alert(\'onLoad: colorbox has started to load the targeted content\'); },
			onComplete:function(){ alert(\'onComplete: colorbox has displayed the loaded content\'); },
			onCleanup:function(){ alert(\'onCleanup: colorbox has begun the close process\'); },
			onClosed:function(){ alert(\'onClosed: colorbox has completely closed\'); }
		});
		$(\'.non-retina\').colorbox({rel:\'group5\', transition:\'none\'})
		$(\'.retina\').colorbox({rel:\'group5\', transition:\'none\', retinaImage:true, retinaUrl:true});
		//Example of preserving a JavaScript event for inline calls.
		$("#click").click(function(){ 
			$(\'#click\').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("Open this window again and this message will still be here.");
			return false;
		});
	});
</script>
        <h1>'.$TMP_padre.'</h1>
'.$TMP_postpadre.'
'.$TMP_filtro.'
'.$TMP_paginacion.'
'.$TMP_noticias.'
        '.$TMP_paginacion.'
<script>
document.getElementById("ruta").innerHTML="'.$TMP_ruta.'";
</script>
';

if ($idcat=="5" && is_modulepermitted("", "MSC", "imagenes")) $TMP="<a target=_blank href='index.php?V_dir=MSC&V_mod=imagenes&func=new'>"._DEF_NLSNew."</a><br>".$TMP;

return $TMP;
}

//---------------------------------------------------------------------------------------------------------------------------------
function filtro() {
global $_REQUEST, $PHP_SELF, $dbname, $RAD_dbi, $V_dir, $V_mod, $idcat, $idtema, $mes1, $mes2, $ano1, $ano2, $clave, $NLS_tema, $NLS_fecha, $NLS_y, $NLS_clave, $buscadornotema;

	$TMP_lang=getSessionVar("SESSION_lang");
	$meses=array("",_DEF_NLSMonth01,_DEF_NLSMonth02,_DEF_NLSMonth03,_DEF_NLSMonth04,_DEF_NLSMonth05,_DEF_NLSMonth06,_DEF_NLSMonth07,_DEF_NLSMonth08,_DEF_NLSMonth09,_DEF_NLSMonth10,_DEF_NLSMonth11,_DEF_NLSMonth12);

	$idarts=""; $idproys="";
	if ($idcat=="4") $TMP_res=sql_query("SELECT distinct(idarticulo) FROM contenidos where idcat='$idcat' and fechacalendario<'".time()."'", $RAD_dbi);
	else $TMP_res=sql_query("SELECT distinct(idarticulo) FROM contenidos where idcat='$idcat' and fechapubli<'".time()."'", $RAD_dbi);
	while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
		$idarts.=",".$TMP_row[0].",";
	}
	if ($idcat=="4") $TMP_res=sql_query("SELECT distinct(idproyecto) FROM contenidos where idcat='$idcat' and fechacalendario<'".time()."'", $RAD_dbi);
	else $TMP_res=sql_query("SELECT distinct(idproyecto) FROM contenidos where idcat='$idcat' and fechapubli<'".time()."'", $RAD_dbi);
	while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
		$idproys.=",".$TMP_row[0].",";
	}
	if ($idcat=="4") $TMP_res=sql_query("SELECT distinct(idaccion) FROM contenidos where idcat='$idcat' and fechacalendario<'".time()."'", $RAD_dbi);
	else $TMP_res=sql_query("SELECT distinct(idaccion) FROM contenidos where idcat='$idcat' and fechapubli<'".time()."'", $RAD_dbi);
	while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
		$idaccs.=",".$TMP_row[0].",";
	}

	$TMP_res=sql_query("SELECT MAX(fechapubli) FROM contenidos where idcat='$idcat' and fechapubli>0", $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
	if ($TMP_row[0]>time()) $TMP_row[0]=time();
	$TMP_fechafin=RAD_showdateint($TMP_row[0]);

	$TMP_res=sql_query("SELECT MIN(fechapubli) FROM contenidos where idcat='$idcat' and fechapubli>0", $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
	$TMP_fechaini=RAD_showdateint($TMP_row[0]);

	$TMP_mesini=substr($TMP_fechaini,3,2);
	$TMP_anoini=substr($TMP_fechaini,6,4);
	if (!$TMP_anoini>0) $TMP_anoini=date("Y");
	$TMP_mesfin=substr($TMP_fechafin,3,2);
	$TMP_anofin=substr($TMP_fechafin,6,4);
	if ($TMP_anofin<$TMP_anoini) $TMP_anofin=$TMP_anoini;

        $TMP_desde='<select name="mes1" id="mes1" class="mes"><option value=""></option>';
        for($ki=1; $ki<13; $ki++) {
                if ($ki*1==$mes1*1) $TMP_desde.="<option value='$ki' selected>".$meses[$ki]."</option>";
                else $TMP_desde.="<option value='$ki'>".$meses[$ki]."</option>";
        }
        $TMP_desde.='</select> <select name="ano1" id="ano1" class="ano"><option value=""></option>';
        for($ki=$TMP_anoini; $ki<$TMP_anofin+1; $ki++) {
                if ($ki*1==$ano1*1) $TMP_desde.="<option value='$ki' selected>".$ki."</option>";
                else $TMP_desde.="<option value='$ki'>".$ki."</option>";
        }
        $TMP_desde.='</select>';

        $TMP_hasta='<select name="mes2" id="mes2" class="mes"><option value=""></option>';
        for($ki=1; $ki<13; $ki++) {
                if ($ki*1==$mes2*1) $TMP_hasta.="<option value='$ki' selected>".$meses[$ki]."</option>";
                else $TMP_hasta.="<option value='$ki'>".$meses[$ki]."</option>";
        }
        $TMP_hasta.='</select> <select name="ano2" id="ano2" class="ano"><option value=""></option>';
        for($ki=$TMP_anoini; $ki<$TMP_anofin+1; $ki++) {
                if ($ki*1==$ano2*1) $TMP_hasta.="<option value='$ki' selected>".$ki."</option>";
                else $TMP_hasta.="<option value='$ki'>".$ki."</option>";
        }
        $TMP_hasta.='</select>';

	$TMP_opts="<option value=''></option>";
	$TMP_res=sql_query("SELECT * FROM articulossecciones order by orden", $RAD_dbi);
	while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
		//$TMP_res2=sql_query("SELECT * FROM articulos where visible='1' and idseccion='".$TMP_row[id]."' order by orden", $RAD_dbi);
		$TMP_res2=sql_query("SELECT * FROM articulos where idseccion='".$TMP_row[id]."' order by orden", $RAD_dbi);
		while($TMP_row2=sql_fetch_array($TMP_res2, $RAD_dbi)) {
			foreach($TMP_row2 as $TMP_k=>$TMP_v) if ($TMP_row2[$TMP_k."_".$TMP_lang]!="") $TMP_row2[$TMP_k]=$TMP_row2[$TMP_k."_".$TMP_lang];
			if (!ereg(",".$TMP_row2[id].",",$idarts)) continue;
			if (ereg(",".$TMP_row2[id].",",",".$buscadornotema.",")) continue;
			$TMP_sec=$TMP_row[nombre]." --> ".$TMP_row2[nombre];
			$TMP_sec=str_replace(":"," ",$TMP_sec);
			$TMP_sec=str_replace(","," ",$TMP_sec);
			$TMP_opts.="<option";
			if ($idtema==$TMP_row2[id]) $TMP_opts.=" selected";
			$TMP_opts.=" value='".$TMP_row2[id]."'>".$TMP_sec."</option>";
		}
		// En la seccion 2 estan los Proyectos.
		if ($TMP_row[id]=="2") {
		  $TMP_res2=sql_query("SELECT * FROM proyectos order by proyecto", $RAD_dbi);
		  while($TMP_row2=sql_fetch_array($TMP_res2, $RAD_dbi)) {
			foreach($TMP_row2 as $TMP_k=>$TMP_v) if ($TMP_row2[$TMP_k."_".$TMP_lang]!="") $TMP_row2[$TMP_k]=$TMP_row2[$TMP_k."_".$TMP_lang];
			if (!ereg(",".$TMP_row2[idproyecto].",",$idproys)) continue;
			if (trim($TMP_row2["proyecto_".$TMP_lang])!="") $TMP_row2[proyecto]=$TMP_row2["proyecto_".$TMP_lang];
			$TMP_sec=$TMP_row[nombre]." --> ".$TMP_row2[proyecto];
			$TMP_sec=str_replace(":"," ",$TMP_sec);
			$TMP_sec=str_replace(","," ",$TMP_sec);
			$TMP_opts.="<option";
			if ($idtema=="P".$TMP_row2[idproyecto]) $TMP_opts.=" selected";
			$TMP_opts.=" value='P".$TMP_row2[idproyecto]."'>".$TMP_sec."</option>";
		  }
		}
		// En la seccion 4 estan las Acciones.
		if ($TMP_row[id]=="3") {
		  $TMP_res2=sql_query("SELECT * FROM acciones order by accion", $RAD_dbi);
		  while($TMP_row2=sql_fetch_array($TMP_res2, $RAD_dbi)) {
			foreach($TMP_row2 as $TMP_k=>$TMP_v) if ($TMP_row2[$TMP_k."_".$TMP_lang]!="") $TMP_row2[$TMP_k]=$TMP_row2[$TMP_k."_".$TMP_lang];
			if (!ereg(",".$TMP_row2[idaccion].",",$idaccs)) continue;
			$TMP_sec=$TMP_row[nombre]." --> ".$TMP_row2[accion];
			$TMP_sec=str_replace(":"," ",$TMP_sec);
			$TMP_sec=str_replace(","," ",$TMP_sec);
			$TMP_opts.="<option";
			if ($idtema=="X".$TMP_row2[idaccion]) $TMP_opts.=" selected";
			$TMP_opts.=" value='X".$TMP_row2[idaccion]."'>".$TMP_sec."</option>";
		  }
		}
	}

	if (is_admin()) $TMP_method="GET";
	else $TMP_method="POST";
	$TMP='<form action="'.$PHP_SELF.'" method="'.$TMP_method.'" name="F">
                <div class="formulario">
                  <label> '.$NLS_tema.'</label>
                  <select name="idtema" id="tema" class="select">'.$TMP_opts.'
                  </select>
                  <br>
                  <br>
                  <label>Palabra/s clave</label>
                  <input type="text" name="clave" id="clave" value="'.$clave.'" class="textfield">
                  <br>
                  <br> 
                  <label>'.$NLS_fecha.'</label>'.$TMP_desde.'
                  <div class="txt">'.$NLS_y.'</div>'.$TMP_hasta.'
                  <input name="entrar" type="submit" class="boton_lupa" id="entrar" value="">
                </div>
';
	foreach($_REQUEST as $TMP_k=>$TMP_v) if ($TMP_k!="pag" && $TMP_v!="" && $TMP_k!="idtema" && $TMP_k!="clave" && $TMP_k!="mes1" && $TMP_k!="mes2" && $TMP_k!="ano1" && $TMP_k!="ano2" && $TMP_k!="entrar") $TMP.="<input type=hidden name=$TMP_k value=$TMP_v>";
	$TMP.="</form>\n";
	return $TMP;
}

//---------------------------------------------------------------------------------------------------------------------------------
function relacionadosNew($id,$TMP_todo) {
global $idcat, $dbname, $RAD_dbi, $V_dir, $V_mod, $NLS_nohayvideos, $NLS_matrel;
$TMP_lang=getSessionVar("SESSION_lang");

//if ($idcat=="3"||$idcat=="4") $TMP_todo="";

$TMP_content='';
$TMP_result=sql_query("SELECT * from categorias order by orden", $RAD_dbi);
while($TMP_row=sql_fetch_array($TMP_result, $RAD_dbi)) {
        if ($TMP_row["literal_".$TMP_lang]!="") $TMP_row["literal"]=$TMP_row["literal_".$TMP_lang];
        $A_lit[$TMP_row[id]]=$TMP_row[literal];
}

$TMP_result=sql_query("SELECT * from contenidos where id='$id'", $RAD_dbi);
$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];

$TMP_noticias=""; $TMP_actividades=""; $TMP_imagenes=""; $TMP_documentos=""; $TMP_videos=""; $TMP_enlaces="";
$TMP_contnoticias=0; $TMP_contactividades=0; $TMP_contimagenes=0; $TMP_contdocumentos=0; $TMP_contvideos=0; $TMP_contenlaces=0;
if ($TMP_row[idcat]=="4") $cmd="SELECT * from contenidos where fechapubli<'".time()."' and activo='1' and (idpadre='$id'";
else $cmd="SELECT * from contenidos where fechapubli<'".time()."' and activo='1' and (idpadre='$id'";
$cmd="SELECT * from contenidos where activo='1' and (idpadre='$id'";
if ($TMP_todo!="") {
	if ($TMP_row[idproyecto]>0) $cmd.=" or idproyecto='".$TMP_row[idproyecto]."'";
	if ($TMP_row[idaccion]>0) $cmd.=" or idaccion='".$TMP_row[idaccion]."'";
	if ($TMP_row[idarticulo]>0) $cmd.=" or idarticulo='".$TMP_row[idarticulo]."'";
	if ($TMP_row[idmodulo]>0) $cmd.=" or idmodulo='".$TMP_row[idmodulo]."'";
}
$TMP_result=sql_query($cmd.") order by fechapubli DESC", $RAD_dbi);
//if (is_admin()) echo($idcat."*".$cmd."<br>");
while($TMP_row=sql_fetch_array($TMP_result, $RAD_dbi)) {
	if ($TMP_row[idcat]=="4" && $TMP_row[fechacalendario]>time()) continue;
	if ($TMP_row[idcat]!="4" && $TMP_row[fechapubli]>time()) continue;
	if ($TMP_row[idcat]=="4" && $TMP_row[fechabaja]>0 && $TMP_row[fechabaja]<time()) continue; // no se muestran activs de baja
	if ($TMP_todo!="") {
		if ($TMP_row[idpadre]!=$id && $TMP_row[idcat]=="5") continue; // ignora imagenes,.... que no sean de la propia noticiai/actividad
		if ($TMP_row[idpadre]!=$id && $TMP_row[idcat]!=$idcat) continue; // ignora imagenes,.... que no sean de la propia noticiai/actividad
	}
	if ($TMP_row[id]==$id) continue; // ignora la misma
	foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
        $TMP_doc="files/".$dbname."/".RAD_primerFich($TMP_row[documentos]);
        $TMP_doc2=$PHP_SELF."?V_dir=MSC&V_mod=download&f=".RAD_primerFich($TMP_row[documentos]);
        $A_x=explode(".",$TMP_doc);
        $TMP_ext=strtoupper($A_x[count($A_x)-1]);
        $TMP_length=RAD_numero(round(filesize($TMP_doc)/1024,0),0)." KB";

        $TMP_row["tema"]=str_replace("\"", "?", $TMP_row["tema"]);
        $TMP_fecha=RAD_showdateint($TMP_row[fechapubli]);
	if ($TMP_row[idcat]=="4") $TMP_fecha.=" - ".RAD_showdateint($TMP_row["fechabaja"]);
	$TMP_img=RAD_primerFich($TMP_row[imagenes]);
/*
	if ($TMP_img!="") {
		include_once("modules/MSC/resizeCrop.php");
		$TMP_img3=resizeCrop("files/".$dbname."/".$TMP_img,"","117","77");
		list($anchoimg, $altoimg, $tipo, $resto)=getimagesize($TMP_img3);
	}
*/
	if ($TMP_row[ciudad]!="") $TMP_row[ciudad]=" | ".$TMP_row[ciudad];
	if ($TMP_row[idcat]=="3" && $TMP_contnoticias<99) { // noticias
		if ($TMP_row[urls]=="") $TMP_row[urls]=$PHP_SELF."?V_dir=$V_dir&V_mod=$V_mod&V_idmod=62&idn=".$TMP_row[id];
		$TMP_contnoticias++;
		$TMP_noticias.='<li class="relacionados_noticia"><a href="'.$TMP_row[urls].'">'.$TMP_row[tema].'</a></li>';
		$TMP_noticias.='<li class="relacionados_fecha">'.$TMP_fecha.' '.$TMP_row[ciudad].'</span></li>';
	}
	if ($TMP_row[idcat]=="4" && $TMP_contactividades<99) { // noticias
		if ($TMP_row[urls]=="") $TMP_row[urls]=$PHP_SELF."?V_dir=$V_dir&V_mod=$V_mod&V_idmod=121&idn=".$TMP_row[id];
		$TMP_contactividades++;
		$TMP_actividades.='<ul><li class="relacionados_noticia"><a href="'.$TMP_row[urls].'">'.$TMP_row[tema].'</a></li>';
		$TMP_actividades.='<li class="relacionados_fecha">'.$TMP_fecha.' '.$TMP_row[ciudad].'</span></li></ul>';
	}
	if ($TMP_row[idcat]=="5") { // imagenes
// FALTA ordenar imagenes al reves. Guardar en un array y despues leer al reves
		$TMP_contimagenes++;
		$TMP_imagenes.='            <a href="files/'.$dbname.'/'.$TMP_img.'" class="igroup'.$id.'" title="&copy; '.$TMP_row[contenido].' | '.$TMP_fecha.' | '.$TMP_row[tema].'">';
		include_once("modules/".$V_dir."/resizeCrop.php");
		//$TMP_img3=resizeCrop("files/".$dbname."/".$TMP_img,"","442","329");
		$TMP_img3=resizeCrop("files/".$dbname."/".$TMP_img,"","442","");
		list($anchoimg, $altoimg, $tipo, $resto)=getimagesize($TMP_img3);
		$TMP_copy=$TMP_row[contenido];
		if ($TMP_contimagenes==1) {
			//$TMP_imagenes.='<img src="files/'.$dbname.'/'.$TMP_img.'">';
			$TMP_imagenes.='<img src="'.$TMP_img3.'">';
		}
		$TMP_imagenes.='</a>
';
		$TMP_imageneslinks[$TMP_contimagenes]='<a href="files/'.$dbname.'/'.$TMP_img.'" class="igroup'.$id.'" title="&copy; '.$TMP_row[contenido].' | '.$TMP_fecha.' | '.$TMP_row[tema].'">';
		$TMP_imagenultima='<a href="files/'.$dbname.'/'.$TMP_img.'" class="igroup'.$id.'" title="&copy; '.$TMP_row[contenido].' | '.$TMP_fecha.' | '.$TMP_row[tema].'"><img src="'.$TMP_img3.'"></a>';
	}
	if ($TMP_row[idcat]=="7" && $TMP_contdocumentos<99) { // documentos
		$TMP_contdocumentos++;
		$TMP_documentos.='<li class="documento"><a href="'.$TMP_doc2.'">'.$TMP_row[tema].'</a></li>';
		$TMP_documentos.='<li class="archivo"><a href="'.$TMP_doc2.'">'.$TMP_ext.'</a> | <span>'.$TMP_length.' </span></li>';
	}
	if ($TMP_row[idcat]=="9" && $TMP_contvideos<99) { // videos
		$TMP_contvideos++;
		$TMP_videos.='<li class="nombre"><a target=_blank href="'.$TMP_row[urls].'">'.$TMP_row[tema].'</a></li>';
		//$TMP_videos.='<li class="fecha">'.$TMP_fecha.' | <span>'.$TMP_row[observaciones].'</span></li>';
		$TMP_videos.='<li class="fecha"><span>'.$TMP_row[observaciones].'</span></li>';
	}
	if ($TMP_row[idcat]=="10" && $TMP_contenlaces<99) { // enlaces
		$TMP_contenlaces++;
		$TMP_enlaces.='<li class="bullet"><a href="'.$TMP_row[urls].'">'.$TMP_row[contenido].'</a></li>';
	}
}
if ($TMP_contimagenes>0) {
	$TMP_imagenes=$TMP_imagenultima;
        for($ki=$TMP_contimagenes-1; $ki>0; $ki--) $TMP_imagenes.=$TMP_imageneslinks[$ki]."</a>";
	$TMP_imagenes='
          <p class="paginacion">1 de '.$TMP_contimagenes.'</p>
          <div class="foto">
'.$TMP_imagenes.'
          </div>
          <p class="copyright"><span>&copy;</span> '.$TMP_copy.'</p>
';
}
if ($TMP_contenlaces>0) $TMP_enlacesdiv='
          <div class="franja_gris">'.$A_lit[10].'</div> <! ENL >
          <ul>'.$TMP_enlaces.'
          </ul>
';
else $TMP_enlacesdiv="";
if ($TMP_contvideos>0) $TMP_videosdiv='
          <div class="franja_gris">'.$A_lit[9].'</div> <! VID >
          <ul class="videos">'.$TMP_videos.'</ul>
';
else $TMP_videosdiv=$NLS_nohayvideos;

if ($TMP_link==true) {
	$TMP_actividades.="<li><a target=_blank href='index.php?V_dir=MSC&V_mod=actividades'>"._DEF_NLSEdit."</a></li>";
	$TMP_noticias.="<li><a target=_blank href='index.php?V_dir=MSC&V_mod=noticias'>"._DEF_NLSEdit."</a></li>";
	$TMP_enlaces.="<li><a target=_blank href='index.php?V_dir=MSC&V_mod=enlaces'>"._DEF_NLSEdit."</a></li>";
	$TMP_videos.="<li><a target=_blank href='index.php?V_dir=MSC&V_mod=videos'>"._DEF_NLSEdit."</a></li>";
	$TMP_documentos.="<a target=_blank href='index.php?V_dir=MSC&V_mod=documentos'>"._DEF_NLSEdit."</a>";
}
if ($idcat=="4") $TMP_noticias=""; // En Actividades no se muestran Noticias

	// 3=Noticia, 4=Actividades, 5=Imagenes, 7=Documentos, 9=Videos, 10=Enlaces
if ($TMP_documentos!="") $TMP_content.='
          <div class="franja_gris">'.$A_lit[7].'</div> <! DOCS >
          <ul>'.$TMP_documentos.'</ul>';
if ($TMP_videos!="") $TMP_content.='
          <div class="franja_gris">'.$A_lit[9].'</div> <! VID >
          <ul class="videos">'.$TMP_videos.'</ul>';
if ($TMP_enlaces!="") $TMP_content.='
          <div class="franja_gris">'.$A_lit[10].'</div> <! ENL >
          <ul>'.$TMP_enlaces.'</ul>';
if ($TMP_noticias!="") $TMP_content.='
          <div class="franja_gris">'.$A_lit[3].'</div> <! NOTIC >
          <ul>'.$TMP_noticias.'</ul>';
if ($TMP_actividades!="") $TMP_content.='
          <div class="franja_gris">'.$A_lit[4].'</div> <! ACTIV >
          <ul>'.$TMP_actividades.'</ul>';
if (trim($TMP_content)!="") $TMP_content='<div class="franja_naranja">'.$NLS_matrel.'</div>'.$TMP_content;
        
return array($TMP_imagenes,$TMP_enlacesdiv,$TMP_videosdiv,$TMP_content);
}
//---------------------------------------------------------------------------------------------------------------------------------
function archivadoen($TMP_idarticulo,$TMP_idproyecto,$TMP_idmodulo,$TMP_idaccion) {
global $dbname, $RAD_dbi, $V_dir, $V_mod, $NLS_nohayvideos, $NLS_act, $NLS_term, $NLS_accact, $NLS_accter;
	$TMP_lang=getSessionVar("SESSION_lang");

	$TMP_subruta="";
	if ($TMP_idarticulo>0) {
		$TMP_result3 = sql_query("SELECT * FROM articulos where id='".$TMP_idarticulo."'", $RAD_dbi);
		$TMP_row3=sql_fetch_array($TMP_result3, $RAD_dbi);
		foreach($TMP_row3 as $TMP_k=>$TMP_v) if ($TMP_row3[$TMP_k."_".$TMP_lang]!="") $TMP_row3[$TMP_k]=$TMP_row3[$TMP_k."_".$TMP_lang];
		$TMP_result2 = sql_query("SELECT * FROM articulossecciones where id='".$TMP_row3[idseccion]."'", $RAD_dbi);
		$TMP_row2=sql_fetch_array($TMP_result2, $RAD_dbi);
		foreach($TMP_row2 as $TMP_k=>$TMP_v) if ($TMP_row2[$TMP_k."_".$TMP_lang]!="") $TMP_row2[$TMP_k]=$TMP_row2[$TMP_k."_".$TMP_lang];
		if ($TMP_row3[idartparent]>0) {
			$TMP_resultp = sql_query("SELECT * FROM articulos where id='".$TMP_row3[idartparent]."'", $RAD_dbi);
			$TMP_rowp=sql_fetch_array($TMP_resultp, $RAD_dbi);
			foreach($TMP_rowp as $TMP_k=>$TMP_v) if ($TMP_rowp[$TMP_k."_".$TMP_lang]!="") $TMP_rowp[$TMP_k]=$TMP_rowp[$TMP_k."_".$TMP_lang];
			$TMP_row2[nombre]=$TMP_row2[nombre]." &gt; ".$TMP_rowp[nombre];
		}
		$TMP_subruta=$TMP_row2[nombre]." &gt; <span>".$TMP_row3[nombre]."</span>";
	} 
	if ($TMP_idproyecto>0) {
		if ($TMP_subruta!="") $TMP_subruta.="<br>";
		$TMP_Gfecha=RAD_gregorianDate(date("Y-m-d"));
		$TMP_result = sql_query("SELECT * FROM modulos where fichero='showproyecto'", $RAD_dbi);
		$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
		$TMP_subruta.="".$TMP_row[grupomenu]." &gt; ";
		$TMP_result3 = sql_query("SELECT * FROM proyectos where idproyecto='".$TMP_idproyecto."'", $RAD_dbi);
		$TMP_row3=sql_fetch_array($TMP_result3, $RAD_dbi);
                $TMP_Gfechafin=RAD_gregorianDate($TMP_row3[fechafin]);
                if (!$TMP_Gfechafin>0) $TMP_activo="1";
                else if ($TMP_Gfechafin>$TMP_Gfecha) $TMP_activo="1";
                else $TMP_activo="0";
		if ($TMP_activo=="0") $TMP_subruta.="".$NLS_term." &gt; ";
		else $TMP_subruta.="".$NLS_act." &gt; ";
		foreach($TMP_row3 as $TMP_k=>$TMP_v) if ($TMP_row3[$TMP_k."_".$TMP_lang]!="") $TMP_row3[$TMP_k]=$TMP_row3[$TMP_k."_".$TMP_lang];
		$TMP_result2 = sql_query("SELECT * FROM proyectoscategorias where idproyectocategoria='".$TMP_row3[idproyectocategoria]."'", $RAD_dbi);
		$TMP_row2=sql_fetch_array($TMP_result2, $RAD_dbi);
		foreach($TMP_row2 as $TMP_k=>$TMP_v) if ($TMP_row2[$TMP_k."_".$TMP_lang]!="") $TMP_row2[$TMP_k]=$TMP_row2[$TMP_k."_".$TMP_lang];
		$TMP_subruta.=" ".$TMP_row2[categoria]." &gt; ";
		$TMP_subruta.=" <span>".$TMP_row3[proyecto]."</span>";
	} 
	if ($TMP_idaccion>0) {
		if ($TMP_subruta!="") $TMP_subruta.="<br>";
		$TMP_Gfecha=RAD_gregorianDate(date("Y-m-d"));
		$TMP_result = sql_query("SELECT * FROM articulos where id='44'", $RAD_dbi); // articulo del cual cuelgan acciones
		$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
		$TMP_result2 = sql_query("SELECT * FROM articulossecciones where id='".$TMP_row[idseccion]."'", $RAD_dbi); // 
		$TMP_row2=sql_fetch_array($TMP_result2, $RAD_dbi);
		foreach($TMP_row2 as $TMP_k=>$TMP_v) if ($TMP_row2[$TMP_k."_".$TMP_lang]!="") $TMP_row2[$TMP_k]=$TMP_row2[$TMP_k."_".$TMP_lang];
		$TMP_subruta.="".$TMP_row2[nombre]." &gt; ";
		$TMP_subruta.="".$TMP_row[nombre]." &gt; ";
		$TMP_result3 = sql_query("SELECT * FROM acciones where idaccion='".$TMP_idaccion."'", $RAD_dbi);
		$TMP_row3=sql_fetch_array($TMP_result3, $RAD_dbi);
                $TMP_Gfechafin=RAD_gregorianDate($TMP_row3[fechafin]);
                if (!$TMP_Gfechafin>0) $TMP_activo="1";
                else if ($TMP_Gfechafin>$TMP_Gfecha) $TMP_activo="1";
                else $TMP_activo="0";
		if ($TMP_activo=="0") $TMP_subruta.="".$NLS_accter." &gt; ";
		else $TMP_subruta.="".$NLS_accact." &gt; ";
		foreach($TMP_row3 as $TMP_k=>$TMP_v) if ($TMP_row3[$TMP_k."_".$TMP_lang]!="") $TMP_row3[$TMP_k]=$TMP_row3[$TMP_k."_".$TMP_lang];
		$TMP_subruta.=" <span>".$TMP_row3[accion]."</span>";
	} 
	if ($TMP_idmodulo>0) {
		if ($TMP_subruta!="") $TMP_subruta.="<br>";
		$TMP_result3=sql_query("SELECT * from modulos where idmodulo='".$TMP_idmodulo."'", $RAD_dbi);
		$TMP_row3=sql_fetch_array($TMP_result3, $RAD_dbi);
		foreach($TMP_row3 as $TMP_k=>$TMP_v) if ($TMP_row3[$TMP_k."_".$TMP_lang]!="") $TMP_row3[$TMP_k]=$TMP_row3[$TMP_k."_".$TMP_lang];
		$TMP_subruta.="".$TMP_row3[grupomenu]." &gt; <span>".$TMP_row3[literalmenu]."</span>";
	}
	return $TMP_subruta;
}
//-------------------------------------------------------------------------------------
function primercatproyecto() {
  global $RAD_dbi;
  $TMP_lang=getSessionVar("SESSION_lang");
  $TMP_primercatenm=0;
  $TMP_result=sql_query("SELECT * from proyectoscategorias order by orden", $RAD_dbi);
  while($TMP_row=sql_fetch_array($TMP_result, $RAD_dbi)) {
	foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
	$TMP_enmarcha=""; $TMP_primerenmarcha=0; 
	$TMP_result2=sql_query("SELECT * from proyectos where idproyectocategoria='".$TMP_row[idproyectocategoria]."'", $RAD_dbi);
	while($TMP_row2=sql_fetch_array($TMP_result2, $RAD_dbi)) {
		$TMP_Gfechafin=RAD_gregorianDate($TMP_row2[fechafin]);
		if (!$TMP_Gfechafin>0) $TMP_activo="1";
		else if ($TMP_Gfechafin>$TMP_Gfecha) $TMP_activo="1";
		else $TMP_activo="0";
		if ($TMP_activo=="1") {
			if (!$TMP_primercatenm>0) $TMP_primercatenm=$TMP_row2[idproyectocategoria];
		}
	}
	if ($TMP_primercatenm>0) break;
  }
  return $TMP_primercatenm;
}
?>
