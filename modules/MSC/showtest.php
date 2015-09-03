<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");
// Muestra las Noticias/Actividades

global $SESSION_SID, $RAD_dbi, $dbname, $PHP_SELF, $idt, $NLS_txtinfo, $NLS_volver, $NLS_archiv, $NLS_matrel;
if ($dbname=="") $dbname=_DEF_dbname;

$TMP_lang=getSessionVar("SESSION_lang");

$NLS_txtinfo=_DEF_NLSTestxtinfo;
$NLS_volver=_DEF_NLSTesvolver;
$NLS_archiv=_DEF_NLSTesarchiv;
$NLS_matrel=_DEF_NLSTesmatrel;
$NLS_lugar=_DEF_NLSTeslugar;
$NLS_nohayvideos=_DEF_NLSTesnohayvideos;
$NLS_act=_DEF_NLSTesact;
$NLS_term=_DEF_NLSTesterm;
$NLS_fecha=_DEF_NLSTesfecha;
$NLS_y=_DEF_NLSTesy;
$NLS_tema=_DEF_NLSTestema;

include_once("header.php");

if ($idt>0) echo showTest($idt);
else echo showTests();

include_once("footer.php");
return;
//---------------------------------------------------------------------------------------------------------------------------------
function showTest($id) {
	global $RAD_dbi, $dbname, $V_dir, $NLS_volver, $NLS_archiv, $NLS_matrel, $NLS_lugar, $idproyecto, $V_idmod;

	$TMP_lang=getSessionVar("SESSION_lang");
	$TMP_dirtheme="themes/".getSessionVar("SESSION_theme");

	$TMP_res=sql_query("SELECT * FROM testimonios where visible='1' and fechaalta<='".date("Y-m-d H:i:s")."' and idtestimonio=".converttosql($id), $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
	$TMP_archivado=archivadoen($TMP_row[vinculacion]);
	foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
	$TMP_fecha=RAD_showDate($TMP_row["fechaalta"]);
	$TMP_lugar="";
	if ($TMP_row[idproyecto]>0) $TMP_padre=RAD_lookup("proyectos","proyecto","idproyecto",$TMP_row[idproyecto]);
	else if ($TMP_row[idarticulo]>0) $TMP_padre=RAD_lookup("articulos","tema","id",$TMP_row[idarticulo]);
	else if ($TMP_row[idmodulo]>0) $TMP_padre=RAD_lookup("modulos","literalmenu","idproyecto",$TMP_row[idmodulo]);
        $TMP_img=RAD_primerFich($TMP_row[imagen]);
	$TMP_icono='';
	if ($TMP_img!="") {
		include_once("modules/".$V_dir."/resizeCrop.php");
		//$TMP_img3=resizeCrop("files/".$dbname."/".$TMP_img,"","442","329");
		$TMP_img3=resizeCrop("files/".$dbname."/".$TMP_img,"","442",""); // 263
		list($anchoimg, $altoimg, $tipo, $resto)=getimagesize($TMP_img3);
		//echo "\n<br>Escala $TMP_img3 de ancho $anchoimg, $altoimg, $tipo<br>";
		$TMP_derechos=str_replace('"','',$TMP_row[derechos]);
		$TMP_tit=str_replace('"','',$TMP_row[titular]);
		$TMP_icono='<div class="foto"><img src="'.$TMP_img3.'"></div>';
		$TMP_icono='<div class="foto"><a href="files/'.$dbname.'/'.$TMP_img.'" class="group2 cboxElement" title="&copy; '.$TMP_derechos.'"><img src="'.$TMP_img3.'"></a></div>';
		$TMP_icono.='<p class="copyright"><span>&copy;</span> '.$TMP_row[derechos].'</p>';
	}

	$TMP_result2=sql_query("SELECT * from modulos where idmodulo='$V_idmod'", $RAD_dbi);
	$TMP_row2=sql_fetch_array($TMP_result2, $RAD_dbi);
	//$TMP_ruta="".$TMP_row2[grupomenu]." &gt; ".$TMP_row2[literalmenu]." ";
	foreach($TMP_row2 as $TMP_k=>$TMP_v) if ($TMP_row2[$TMP_k."_".$TMP_lang]!="") $TMP_row2[$TMP_k]=$TMP_row2[$TMP_k."_".$TMP_lang];
	if ($TMP_padre=="") $TMP_padre=$TMP_row2[literalmenu];
	if (trim($TMP_row[titular])!="") $TMP_row[titular]='"'.$TMP_row[titular].'"';
	if (trim($TMP_row[contenido])!="") {
		$TMP_row[contenido]=str_replace("\n","<br>\n",$TMP_row[contenido]);
		$TMP_row[contenido]='"'.$TMP_row[contenido].'"';
	}

	$TMP='
<link href="'.$TMP_dirtheme.'/colorbox/colorbox.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="'.$TMP_dirtheme.'/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="'.$TMP_dirtheme.'/jquery.colorbox.js"></script>
<script>
	$(document).ready(function(){
		//Examples of how to assign the Colorbox event to elements
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
        <div class="franja_azul">'.$TMP_row[nombre].' '.$TMP_row[apellidos].'</div>
        <div class="noticia">
          <p class="fecha">'.$TMP_fecha.' </p>
          <p class="entradilla">'.$TMP_row[titular].'</p>
'.$TMP_icono.'
          <p>'.$TMP_row[contenido].'</p>
          <p class="archivada">'.$NLS_archiv.':</p>
          <p class="ruta">'.$TMP_archivado.'</p>
        </div>
        <div class="volver"><a href="javascript:history.back();">'.$NLS_volver.'</a></div>
';

if (is_modulepermitted("", "MSC", "testimonios")) $TMP="<a target=_blank href='index.php?V_dir=MSC&V_mod=testimonios&func=edit&par0=$id'>"._DEF_NLSEdit."</a><br>".$TMP;

return $TMP;
}

//---------------------------------------------------------------------------------------------------------------------------------
function showTests() {
	global $id, $PHP_SELF, $RAD_dbi, $dbname, $V_dir, $V_idmod, $V_mod, $pag, $month, $NLS_txtinfo, $idproyecto, $term, $idproyectocategoria, $idartnews, $idmodnews, $NLS_nohayvideos, $NLS_archiv, $mes1, $mes2, $ano1, $ano2, $idtema, $clave;

	if ($idtema>0) $idartnews=$idtema;

	$MaxCharsItem=500;
	$MaxRegs=5;

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
	$where = " where visible='1' AND fechaalta<='".date("Y-m-d H:i:s",$TMP_time)."' ";
	if ($TMP_ini>0) $where.=" and fechaalta>='".date("Y-m-d H:i:s",$TMP_ini)."'";
	if ($TMP_fin>0) $where.=" and fechaalta<='".date("Y-m-d H:i:s",$TMP_fin)."'";
	if ($clave!="") $where.=" and (titular like ".converttosql("%".$clave."%")." or contenido like ".converttosql("%".$clave."%").")";
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
		$where.=" and fechaalta>='".date("Y-m-d H:i:s",$TMP_ini)."' and fechaalta<='".date("Y-m-d H:i:s",$TMP_fin)."'";
	}
	$TMP_result=sql_query("SELECT * from articulos where id='$id'", $RAD_dbi); // articulo que representa este modulo en el menu
	$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
	$TMP_result=sql_query("SELECT * from articulossecciones where id='".$TMP_row[idseccion]."'", $RAD_dbi); // articulo que representa este modulo en el menu
	$TMP_rows=sql_fetch_array($TMP_result, $RAD_dbi);
	foreach($TMP_rows as $TMP_k=>$TMP_v) if ($TMP_rows[$TMP_k."_".$TMP_lang]!="") $TMP_rows[$TMP_k]=$TMP_rows[$TMP_k."_".$TMP_lang];
	$TMP_result=sql_query("SELECT * from modulos where idmodulo='$V_idmod'", $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
	foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
	$TMP_ruta=$TMP_rows[nombre]." &gt; ".$TMP_row[grupomenu]." &gt; <span>".$TMP_row[literalmenu]."</span> ";
	$TMP_subruta="".$TMP_row[grupomenu]." &gt; ".$TMP_row[literalmenu]." ";
	$TMP_padre=$TMP_row[literalmenu];
	$TMP_postpadre=$TMP_row[observaciones];
	if ($idartnews>0) {
		$TMP_result = sql_query("SELECT * FROM articulos where id='$idartnews'", $RAD_dbi);
		$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
		$TMP_result2 = sql_query("SELECT * FROM articulossecciones where id='".$TMP_row[idseccion]."'", $RAD_dbi);
		$TMP_row2=sql_fetch_array($TMP_result2, $RAD_dbi);
		foreach($TMP_row2 as $TMP_k=>$TMP_v) if ($TMP_row2[$TMP_k."_".$TMP_lang]!="") $TMP_row2[$TMP_k]=$TMP_row2[$TMP_k."_".$TMP_lang];
		$TMP_padre=$TMP_row[tema];
		$TMP_subruta=$TMP_row2[nombre]." &gt; <span>".$TMP_row[nombre]."</span>";
		$TMP_ruta.=" &gt; ".$TMP_subruta;
		$where.=" and idarticulo='$idartnews'";
	} else if ($idproyectocategoria>0) {
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
		$TMP_subruta="".$TMP_padre." &gt; <span>".$TMP_row[literalmenu]."</span>";
		$TMP_ruta.=" &gt; ".$TMP_subruta;
	} else if ($idproyecto>0) {
		$TMP_result = sql_query("SELECT * FROM proyectos where idproyecto='$idproyecto'", $RAD_dbi);
		$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
		$TMP_padre=$TMP_row[proyecto];
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
		$TMP_result2 = sql_query("SELECT * FROM proyectoscategorias where idproyectocategoria='".$TMP_row[idproyectocategoria]."'", $RAD_dbi);
		$TMP_row2=sql_fetch_array($TMP_result2, $RAD_dbi);
		foreach($TMP_row2 as $TMP_k=>$TMP_v) if ($TMP_row2[$TMP_k."_".$TMP_lang]!="") $TMP_row2[$TMP_k]=$TMP_row2[$TMP_k."_".$TMP_lang];
		$where.=" and idproyecto='$idproyecto'";
		$TMP_subruta="".$TMP_row2[categoria]." &gt; <span>".$TMP_row[proyecto]."</span>";
		$TMP_ruta.=" &gt; ".$TMP_subruta;
	} else if ($idmodnews>0) {
		$TMP_result=sql_query("SELECT * from modulos where idmodulo='$idmodnews'", $RAD_dbi);
		$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
		$TMP_padre=$TMP_row["literalmenu"];
		$TMP_subruta="".$TMP_row[grupomenu]." &gt; <span>".$TMP_row[literalmenu]."</span>";
		$TMP_ruta.=" &gt; ".$TMP_subruta;
		$where.=" and idmodulo='$idmodnews'";
	}
	if ($idtema!="") $where.=" and vinculacion like '%".$idtema."%'";

	$order = " order by fechaalta DESC";
	$cmdsql = "SELECT count(*) FROM testimonios ".$where;
	$TMP_result = sql_query($cmdsql, $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
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
	$TMP_noticias="";
	$TMP_link=$PHP_SELF."?";
	foreach($_REQUEST as $TMP_k=>$TMP_v) if (substr($TMP_k,0,1)!="_" && substr($TMP_k,0,3)!="PHP" && $TMP_v!="" && $TMP_k!="id") $TMP_link.=$TMP_k."=".$TMP_v."&";
	$TMP_link.="idt=";
	$inireg=($pag-1)*$MaxRegs;
	$limit=" LIMIT ".$inireg.",".($inireg+$MaxRegs);
	$cmdsql = "SELECT * FROM testimonios ".$where.$order.$limit;
	$TMP_result = sql_query($cmdsql, $RAD_dbi);
	while($TMP_row=sql_fetch_array($TMP_result, $RAD_dbi)) {
		$TMP_archivado=archivadoen($TMP_row[vinculacion]);
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];

		$TMP_subruta=ruta($TMP_row[idarticulo],$TMP_row[idproyecto],$TMP_row[idmodulo]);

		$TMP_url="$PHP_SELF?V_dir=MSC&V_mod=showtest&idt=".$TMP_row[idtestimonio].$SESSION_SID;
	
		$TMP_row["titular"]=str_replace("\"", "?", $TMP_row["titular"]);
		//$TMP_row["contenido"]=str_replace("\"", "?", $TMP_row["contenido"]);
		$TMP_fecha=RAD_showDate($TMP_row["fechaalta"]);
		$TMP_new="<a href='".$TMP_url."'>".$TMP_row["titular"]."</a>";
		//if (strlen($TMP_row[contenido])>$MaxCharsItem) $TMP_row[contenido]=trim(substr($TMP_row[contenido],0,$MaxCharsItem))." ...";
		//$TMP_new.="".$TMP_row[contenido]."</a><br><br>";
		$TMP_new=str_replace("\n", "", $TMP_new);
		$TMP_new=str_replace("\r", "", $TMP_new);
        	$TMP_img=RAD_primerFich($TMP_row[imagen]);
		if ($TMP_img!="") {
			include_once("modules/".$V_dir."/resizeCrop.php");
			//$TMP_img3=resizeCrop("files/".$dbname."/".$TMP_img,"","442","329");
			$TMP_img3=resizeCrop("files/".$dbname."/".$TMP_img,"","442",""); // 263
			list($anchoimg, $altoimg, $tipo, $resto)=getimagesize($TMP_img3);
			//echo "\n<br>Escala $TMP_img3 de ancho $anchoimg, $altoimg, $tipo<br>";
		}
		if (trim($TMP_row[titular])!="") $TMP_row[titular]='"'.$TMP_row[titular].'"';
		if (trim($TMP_row[contenido])!="") {
			$TMP_row[contenido]=str_replace("\n","<br>\n",$TMP_row[contenido]);
			$TMP_row[contenido]='"'.$TMP_row[contenido].'"';
		}
		$TMP_icono='';
		if ($TMP_img!="") {
			$TMP_scriptcolorbox.='$(".igroup'.$TMP_row[idtestimonio].'").colorbox({rel:\'igroup'.$TMP_row[idtestimonio].'\', transition:"fade"});';
			$TMP_derechos=str_replace('"','',$TMP_row[derechos]);
			$TMP_tit=str_replace('"','',$TMP_row[titular]);
			$TMP_icono='<div class="foto"><img src="'.$TMP_img3.'"></div>';
			$TMP_icono='<div class="foto"><a href="files/'.$dbname.'/'.$TMP_img.'" class="igroup'.$TMP_row[idtestimonio].' cboxElement" title="&copy; '.$TMP_derechos.'"><img src="'.$TMP_img3.'"></a></div>';
			$TMP_icono.='<p class="copyright"><span>&copy;</span> '.$TMP_row[derechos].'</p>';
		}
		$TMP_noticias.='
        <div class="noticia">
          <p class="titular"><a href="'.$TMP_link.$TMP_row[idtestimonio].'">'.$TMP_row[nombre].' '.$TMP_row[apellidos].'</a></p>
          <p class="fecha">'.$TMP_fecha.'</p>
          <p>'.$TMP_row[titular].'</p>
'.$TMP_icono.'
          <p class="archivada">'.$NLS_archiv.':</p>
          <p class="ruta">'.$TMP_archivado.'</p>
          <div class="mas_info"><a href="'.$TMP_link.$TMP_row[idtestimonio].'">'.$NLS_txtinfo.'</a></div>
        </div>';
	}

	$TMP_filtro=filtro();
	$TMP='
<link href="'.$TMP_dirtheme.'/colorbox/colorbox.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="'.$TMP_dirtheme.'/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="'.$TMP_dirtheme.'/jquery.colorbox.js"></script>
<script>
	//$(document).ready(function(){$("a[rel=\'galeria\']").colorbox();})
	$(document).ready(function(){
		'.$TMP_scriptcolorbox.'
	}
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

if (is_modulepermitted("", "MSC", "testimonios")) $TMP="<a target=_blank href='index.php?V_dir=MSC&V_mod=testimonios&func=new'>"._DEF_NLSNew."</a><br>".$TMP;

return $TMP;
}

//---------------------------------------------------------------------------------------------------------------------------------
function archivadoen($TMP_vinculacion) {
	$TMP_litvinculacion="";
	$A_x=explode(",",$TMP_vinculacion.",");
	foreach($A_x as $TMP_idx=>$TMP_pref) {
		if (eregi("usuario", $TMP_pref)) $TMP_lit="Usuarios/as";
		else if (eregi("socio", $TMP_pref)) $TMP_lit="Socios/as";
		else if (eregi("donante", $TMP_pref)) $TMP_lit="Donantes";
		else if (eregi("voluntario", $TMP_pref)) $TMP_lit="Voluntarios/as";
		else if (eregi("cliente", $TMP_pref)) $TMP_lit="Clientes de la tienda";
		else if (eregi("firmante", $TMP_pref)) $TMP_lit="Firmantes de acciones";
		else if (eregi("trabajador", $TMP_pref)) $TMP_lit="Trabajadores/as";
		else if (eregi("colaborador", $TMP_pref)) $TMP_lit="Colaboradores/as";
		else $TMP_lit="";
		if ($TMP_lit!="") {
			if ($TMP_litvinculacion!="") $TMP_litvinculacion.="<br>";
			$TMP_litvinculacion.=$TMP_lit;
		}
	}
	return $TMP_litvinculacion;
}

//---------------------------------------------------------------------------------------------------------------------------------
function filtro() {
global $_REQUEST, $PHP_SELF, $dbname, $RAD_dbi, $V_dir, $V_mod, $idtema, $mes1, $mes2, $ano1, $ano2, $clave, $NLS_tema, $NLS_fecha, $NLS_y;

	$TMP_lang=getSessionVar("SESSION_lang");
	$meses=array("",_DEF_NLSMonth01,_DEF_NLSMonth02,_DEF_NLSMonth03,_DEF_NLSMonth04,_DEF_NLSMonth05,_DEF_NLSMonth06,_DEF_NLSMonth07,_DEF_NLSMonth08,_DEF_NLSMonth09,_DEF_NLSMonth10,_DEF_NLSMonth11,_DEF_NLSMonth12);

	$idarts="";
	$TMP_res=sql_query("SELECT distinct(idarticulo) FROM contenidos where idcat='$idcat'", $RAD_dbi);
	while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
		$idarts.=",".$TMP_row[0].",";
	}

	$TMP_res=sql_query("SELECT MAX(fechaalta) FROM testimonios where fechaalta<='".date("Y-m-d H:i:s")."' and visible='1'", $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
	$TMP_fechafin=RAD_showDate($TMP_row[0]);

	$TMP_res=sql_query("SELECT MIN(fechaalta) FROM testimonios where fechaalta>0 and fechaalta<='".date("Y-m-d H:i:s")."' and visible='1'", $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
	$TMP_fechaini=RAD_showDate($TMP_row[0]);

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
/*
	$TMP_res=sql_query("SELECT * FROM articulossecciones order by orden", $RAD_dbi);
	while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
		if (trim($TMP_row["nombre_".$TMP_lang])!="") $TMP_row[nombre]=$TMP_row["nombre_".$TMP_lang];
		$TMP_res2=sql_query("SELECT * FROM articulos where visible='1' and idseccion='".$TMP_row[id]."' order by orden", $RAD_dbi);
		while($TMP_row2=sql_fetch_array($TMP_res2, $RAD_dbi)) {
			if (!ereg(",".$TMP_row2[id].",",$idarts)) continue;
			if (trim($TMP_row2["nombre_".$TMP_lang])!="") $TMP_row2[nombre]=$TMP_row2["nombre_".$TMP_lang];
			$TMP_sec=$TMP_row[nombre]." --> ".$TMP_row2[nombre];
			$TMP_sec=str_replace(":"," ",$TMP_sec);
			$TMP_sec=str_replace(","," ",$TMP_sec);
			$TMP_opts.="<option";
			if ($idtema==$TMP_row2[id]) $TMP_opts.=" selected";
			$TMP_opts.=" value='".$TMP_row2[id]."'>".$TMP_sec."</option>";
		}
	}
*/
	$A_opts=array("Usuarios/as", "Socios/as", "Donantes", "Voluntarios/as", "Clientes de la tienda", "Firmantes de acciones", "Trabajadores/as", "Colaboradores/as");
	foreach($A_opts as $TMP_idx=>$TMP_val) {
		$TMP_pref=substr($TMP_val,0,5);
		$TMP_res=sql_query("SELECT count(*) FROM testimonios where vinculacion like '%".$TMP_pref."%' and fechaalta<='".date("Y-m-d H:i:s")."' and visible='1'", $RAD_dbi);
		$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
		if ($TMP_row[0]==0) continue;
		if ($idtema==$TMP_pref) $TMP_opts.="<option selected value='".$TMP_pref."'>".$TMP_val."</option>";
		else $TMP_opts.="<option value='".$TMP_pref."'>".$TMP_val."</option>";
	}

	$TMP='<form action="'.$PHP_SELF.'" method=POST name="F">
                <div class="formulario">
                  <label> '.$NLS_tema.'</label>
                  <select name="idtema" id="tema" class="select">'.$TMP_opts.'
                  </select>
                  <br>
                  <br>
                  <label>'.$NLS_fecha.'</label>'.$TMP_desde.'
                  <div class="txt">'.$NLS_y.'</div>'.$TMP_hasta.'
                  <input name="entrar" type="submit" class="boton_lupa" id="entrar" value="">
                </div>
';
	foreach($_REQUEST as $TMP_k=>$TMP_v) if ($TMP_k!="idtema" && $TMP_v!="" && $TMP_k!="tema" && $TMP_k!="clave" && $TMP_k!="mes1" && $TMP_k!="mes2" && $TMP_k!="ano1" && $TMP_k!="ano2" && $TMP_k!="entrar") $TMP.="<input type=hidden name=$TMP_k value=$TMP_v>";
	$TMP.="</form>\n";
	return $TMP;
}

//---------------------------------------------------------------------------------------------------------------------------------
function ruta($TMP_idarticulo,$TMP_idproyecto,$TMP_idmodulo) {
global $dbname, $RAD_dbi, $V_dir, $V_mod, $NLS_nohayvideos, $NLS_act, $NLS_term;
	$TMP_lang=getSessionVar("SESSION_lang");

	if ($TMP_idarticulo>0) {
		$TMP_result3 = sql_query("SELECT * FROM articulos where id='".$TMP_idarticulo."'", $RAD_dbi);
		$TMP_row3=sql_fetch_array($TMP_result3, $RAD_dbi);
		foreach($TMP_row3 as $TMP_k=>$TMP_v) if ($TMP_row3[$TMP_k."_".$TMP_lang]!="") $TMP_row3[$TMP_k]=$TMP_row3[$TMP_k."_".$TMP_lang];
		$TMP_result2 = sql_query("SELECT * FROM articulossecciones where id='".$TMP_row3[idseccion]."'", $RAD_dbi);
		$TMP_row2=sql_fetch_array($TMP_result2, $RAD_dbi);
		foreach($TMP_row2 as $TMP_k=>$TMP_v) if ($TMP_row2[$TMP_k."_".$TMP_lang]!="") $TMP_row2[$TMP_k]=$TMP_row2[$TMP_k."_".$TMP_lang];
		$TMP_subruta=$TMP_row2[nombre]." &gt; <span>".$TMP_row3[nombre]."</span>";
	} else if ($TMP_idproyecto>0) {
		$TMP_Gfecha=RAD_gregorianDate(date("Y-m-d"));
		$TMP_result = sql_query("SELECT * FROM modulos where fichero='showproyecto'", $RAD_dbi);
		$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
		$TMP_subruta="".$TMP_row[grupomenu]." &gt; ";
		$TMP_result3 = sql_query("SELECT * FROM proyectos where idproyecto='".$TMP_idproyecto."'", $RAD_dbi);
		$TMP_row3=sql_fetch_array($TMP_result3, $RAD_dbi);
                $TMP_Gfechafin=RAD_gregorianDate($TMP_row2[fechafin]);
                if (!$TMP_Gfechafin>0) $TMP_activo="1";
                else if ($TMP_Gfechafin>$TMP_Gfecha) $TMP_activo="1";
                else $TMP_activo="0";
		if ($TMP_activo=="0") $TMP_subruta.="".$NLS_term." &gt; ";
		else $TMP_subruta.="".$NLS_act." &gt; ";
		foreach($TMP_row3 as $TMP_k=>$TMP_v) if ($TMP_row3[$TMP_k."_".$TMP_lang]!="") $TMP_row3[$TMP_k]=$TMP_row3[$TMP_k."_".$TMP_lang];
		foreach($TMP_row3 as $TMP_k=>$TMP_v) if ($TMP_row3[$TMP_k."_".$TMP_lang]!="") $TMP_row3[$TMP_k]=$TMP_row3[$TMP_k."_".$TMP_lang];
		$TMP_result2 = sql_query("SELECT * FROM proyectoscategorias where idproyectocategoria='".$TMP_row3[idproyectocategoria]."'", $RAD_dbi);
		$TMP_row2=sql_fetch_array($TMP_result2, $RAD_dbi);
		foreach($TMP_row2 as $TMP_k=>$TMP_v) if ($TMP_row2[$TMP_k."_".$TMP_lang]!="") $TMP_row2[$TMP_k]=$TMP_row2[$TMP_k."_".$TMP_lang];
		$TMP_subruta.="".$TMP_row2[categoria]." &gt; <span>".$TMP_row3[proyecto]."</span>";
	} else if ($TMP_idmodulo>0) {
		$TMP_result3=sql_query("SELECT * from modulos where idmodulo='".$TMP_idmodulo."'", $RAD_dbi);
		$TMP_row3=sql_fetch_array($TMP_result3, $RAD_dbi);
		foreach($TMP_row3 as $TMP_k=>$TMP_v) if ($TMP_row3[$TMP_k."_".$TMP_lang]!="") $TMP_row3[$TMP_k]=$TMP_row3[$TMP_k."_".$TMP_lang];
		$TMP_subruta="".$TMP_row3[grupomenu]." &gt; <span>".$TMP_row3[literalmenu]."</span>";
	}
	return $TMP_subruta;
}
?>
