<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");
// muestra los materiales relacionados de un Proyecto, Accion o Seccion Web

global $RAD_dbi, $content, $dbname, $idproyecto, $idaccion, $id, $V_mod, $V_idmod, $idartnoshow;
$content="";

if ($id=="77") $idaccion=""; // en la pagina de Gracias por Firmar Accion no se contempla la accion.

if (!$idaccion>0 && !$idproyecto>0 && !$id>0) return "";
if ($id>0 && ereg(",".$id.",",",".$idartnoshow.",")) return "";
if ($dbname=="") $dbname=_DEF_dbname;
$TMP_lang=getSessionVar("SESSION_lang");

$A_x=explode(".",basename(__FILE__));
$TMP_fich=$A_x[0];
$TMP_result=sql_query("SELECT * from bloques where fichero='$TMP_fich'", $RAD_dbi);
$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
$content=$TMP_row[contenido];

if (is_modulepermitted("", "MSC", "proyectos")) $TMP_link=true;
else $TMP_link=false;
$TMP_linkedit="";

$TMP_dirtheme="themes/".getSessionVar("SESSION_theme");
$TMP_imagenesbase='
<link href="'.$TMP_dirtheme.'/colorbox/colorbox.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="'.$TMP_dirtheme.'/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="'.$TMP_dirtheme.'/jquery.colorbox.js"></script>
<script>
        $(document).ready(function(){
                //Examples of how to assign the Colorbox event to elements
                $(".latgroup").colorbox({rel:\'latgroup\', transition:"fade"});
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
';

$TMP_result=sql_query("SELECT * from categorias order by orden", $RAD_dbi);
while($TMP_row=sql_fetch_array($TMP_result, $RAD_dbi)) {
	foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
	$A_lit[$TMP_row[id]]=$TMP_row[literal];
}

if (!$idproyecto>0 && !$idaccion>0) {
	//$TMP_result=sql_query("SELECT * from proyectos where idarticulo='$id'", $RAD_dbi);
	//$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
	//$idproyecto=$TMP_row[idproyecto];
	//$TMP_result=sql_query("SELECT * from acciones where idarticulo='$id'", $RAD_dbi);
	//$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
	//$idaccion=$TMP_row[idaccion];
}

if (!$idproyecto>0) $idproyecto=-1;
if (!$idaccion>0) $idaccion=-1;
if (!$id>0) $id=-1;
$TMP_Gfecha=RAD_gregorianDate(date("Y-m-d"));
$TMP_noticias=""; $TMP_actividades=""; $TMP_imagenes=""; $TMP_documentos=""; $TMP_videos=""; $TMP_enlaces="";
$TMP_contnoticias=0; $TMP_contactividades=0; $TMP_contimagenes=0; $TMP_contdocumentos=0; $TMP_contvideos=0; $TMP_contenlaces=0;
$cmd="SELECT * from contenidos where (idaccion='$idaccion' or idproyecto='$idproyecto' or idarticulo='$id') and fechapubli<".time()." and activo='1' order by fechapubli DESC, fechapubli DESC";
$cmd="SELECT * from contenidos where (idaccion='$idaccion' or idproyecto='$idproyecto' or idarticulo='$id') and activo='1' order by fechapubli DESC";
//if (is_admin()) echo $cmd."<br>";
$TMP_result=sql_query($cmd, $RAD_dbi);
while($TMP_row=sql_fetch_array($TMP_result, $RAD_dbi)) {
	foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
	if ($TMP_row[fechapubli]>time() && $TMP_row[idcat]!="4") continue;
	if ($TMP_row[fechacalendario]>time() && $TMP_row[idcat]=="4") continue;
        $TMP_doc="files/".$dbname."/".RAD_primerFich($TMP_row[documentos]);
	$TMP_doc2=$PHP_SELF."?V_dir=MSC&amp;V_mod=download&f=".RAD_primerFich($TMP_row[documentos]);
        $A_x=explode(".",$TMP_doc);
        $TMP_ext=strtoupper($A_x[count($A_x)-1]);
        $TMP_length=round(filesize($TMP_doc)/1024,0);

        ///if ($TMP_img!="") $content.='<img src="files/'.$dbname.'/'.$TMP_img.'" />';

        $TMP_row["tema"]=str_replace("\"", "?", $TMP_row["tema"]);
        $TMP_fecha=RAD_showdateint($TMP_row[fechapubli]);
	$TMP_img=RAD_primerFich($TMP_row[imagenes]);
	if ($TMP_img!="") {
		include_once("modules/MSC/resizeCrop.php");
		$TMP_img3=resizeCrop("files/".$dbname."/".$TMP_img,"","117","77");
		list($anchoimg, $altoimg, $tipo, $resto)=getimagesize($TMP_img3);
	}
	if ($TMP_row[ciudad]!="") $TMP_row[ciudad]=" | ".$TMP_row[ciudad];
	//if ($TMP_row[idcat]=="3" && $TMP_contnoticias<10) { // noticias
	if ($TMP_row[idcat]=="3") { // noticias
		$TMP_Gfechapubli=RAD_gregorianDate(date("Y-m-d",$TMP_row[fechapubli]));

		if (($TMP_Gfechapubli+31)<$TMP_Gfecha) continue; // solo se muestra noticias de hasta 31 dias
		if ($TMP_row[urls]=="") $TMP_row[urls]=$PHP_SELF."?V_dir=MSC&amp;V_mod=shownews&amp;V_idmod=62&amp;idn=".$TMP_row[id];
		if ($V_mod=="shownews") $TMP_row[urls].="&amp;V_idmod=".$V_idmod;
		$TMP_contnoticias++;
		$TMP_noticias.='<li class="nombre"><a href="'.$TMP_row[urls].'">'.$TMP_row[tema].'</a></li>';
		$TMP_noticias.='<li class="fecha">'.$TMP_fecha.' '.$TMP_row[ciudad].'</span></li>';
	}
	//if ($TMP_row[idcat]=="4" && $TMP_contactividades<10) { // actividades
	if ($TMP_row[idcat]=="4") { // actividades
		//$TMP_Gfechafin=RAD_gregorianDate($TMP_row[fechabaja]);
		//if (!$TMP_Gfechafin>0) $TMP_activo="1";
		//else if ($TMP_Gfechafin>$TMP_Gfecha) $TMP_activo="1";
		//else $TMP_activo="0";
		//if ($TMP_activo=="0") continue; // solo se muestran actividades activas
		if ($TMP_row[fechabaja]>0 && $TMP_row[fechabaja]<time()) continue;
		if ($TMP_row[urls]=="") $TMP_row[urls]=$PHP_SELF."?V_dir=MSC&amp;V_mod=shownews&amp;V_idmod=121&amp;idn=".$TMP_row[id];
		if ($V_mod=="shownews") $TMP_row[urls].="&amp;V_idmod=".$V_idmod;
		$TMP_fecha.=" - ".RAD_showdateint($TMP_row["fechabaja"]); // Actividad fecha inicio - fin
		$TMP_contactividades++;
		$TMP_actividades.='<li class="nombre"><a href="'.$TMP_row[urls].'">'.$TMP_row[tema].'</a></li>';
		$TMP_actividades.='<li class="fecha">'.$TMP_fecha.' '.$TMP_row[ciudad].'</span></li>';
	}
	if ($TMP_row[idcat]=="5") { // imagenes
		$TMP_contimagenes++;
		////if ($TMP_contimagenes<2) $TMP_imagenes='<li class="foto"><img src="'.$TMP_img3.'"></li>'.$TMP_imagenes;
		//$TMP_imagenes='<li class="foto"><img src="'.$TMP_img3.'"></li>';
                if ($TMP_contimagenes==1) {
			$TMP_imagenes.='<li class="foto">';
		}
		$TMP_imagenes.='<a href="files/'.$dbname.'/'.$TMP_img.'" class="group0" title="&copy; '.$TMP_row[contenido].' | '.$TMP_fecha.' | '.$TMP_row[tema].'">';
		$TMP_imageneslinks[$TMP_contimagenes]='<a href="files/'.$dbname.'/'.$TMP_img.'" class="group0" title="&copy; '.$TMP_row[contenido].' | '.$TMP_fecha.' | '.$TMP_row[tema].'">';
                if ($TMP_contimagenes==1) {
                        $TMP_imagenes.='<img src="'.$TMP_img3.'">';
                        $TMP_copy=$TMP_row[contenido];
                }
                $TMP_imagenes.='</a>';
                if ($TMP_contimagenes==1) {
			//$TMP_imagenes.='</li>';
		}
		$TMP_imagenultima='<li class="foto"><a href="files/'.$dbname.'/'.$TMP_img.'" class="group0" title="&copy; '.$TMP_row[contenido].' | '.$TMP_fecha.' | '.$TMP_row[tema].'"><img src="'.$TMP_img3.'"></a>';
	}
	//if ($TMP_row[idcat]=="7" && $TMP_contdocumentos<10) { // documentos
	if ($TMP_row[idcat]=="7") { // documentos
		$TMP_contdocumentos++;
		$TMP_documentos.='<li class="nombre"><a target=_blank href="'.$TMP_doc2.'">'.$TMP_row[tema].'</a></li>';
		$TMP_documentos.='<li class="fecha">'.$TMP_fecha.' | <a target=_blank href="'.$TMP_doc2.'">'.$TMP_ext.'</a> | <span>'.$TMP_length.' KB</span></li>';
	}
	//if ($TMP_row[idcat]=="9" && $TMP_contvideos<10) { // videos
	if ($TMP_row[idcat]=="9") { // videos
		$TMP_contvideos++;
		$TMP_videos.='<li class="nombre"><a target=_blank href="'.$TMP_row[urls].'">'.$TMP_row[tema].'</a></li>';
		$TMP_videos.='<li class="fecha">'.$TMP_fecha.' | <span>'.$TMP_row[observaciones].'</span></li>';
	}
	//if ($TMP_row[idcat]=="10" && $TMP_contenlaces<10) { // enlaces
	if ($TMP_row[idcat]=="10") { // enlaces
		$TMP_contenlaces++;
		$TMP_enlaces.='<li><a href="'.$TMP_row[urls].'">'.$TMP_row[contenido].'</a></li>';
	}
}
if ($TMP_contimagenes>0) {
	$TMP_imagenes=$TMP_imagenultima;
	for($ki=$TMP_contimagenes-1; $ki>0; $ki--) $TMP_imagenes.=$TMP_imageneslinks[$ki]."</a>";
	$TMP_imagenes.="</li>";
	$TMP_imagenes.='<li class="paginacion">1 de '.$TMP_contimagenes.'</li>';
}
if ($TMP_link==true) {
	$TMP_actividades.="<li><a target=_blank href='index.php?V_dir=MSC&amp;V_mod=actividades'>"._DEF_NLSEdit."</a></li>";
	$TMP_noticias.="<li><a target=_blank href='index.php?V_dir=MSC&amp;V_mod=noticias'>"._DEF_NLSEdit."</a></li>";
	$TMP_enlaces.="<li><a target=_blank href='index.php?V_dir=MSC&amp;V_mod=enlaces'>"._DEF_NLSEdit."</a></li>";
	$TMP_videos.="<li><a target=_blank href='index.php?V_dir=MSC&amp;V_mod=videos'>"._DEF_NLSEdit."</a></li>";
	$TMP_documentos.="<a target=_blank href='index.php?V_dir=MSC&amp;V_mod=documentos'>"._DEF_NLSEdit."</a>";
	$TMP_imagenes="<li><a target=_blank href='index.php?V_dir=MSC&amp;V_mod=imagenes'>"._DEF_NLSEdit."</a></li>".$TMP_imagenes;
}
$TMP_separador='';
if ($TMP_documentos!="") {
	$TMP_documentos='<div class="franja_gris">'.$A_lit[7].'</div> <! DOCS >
          <ul class="documentos">'.$TMP_documentos.'</ul>';
	$TMP_separador='<p class="linea"></p>';
}
if ($TMP_videos!="") {
	$TMP_videos=$TMP_separador.'<div class="franja_gris">'.$A_lit[9].'</div> <! VID >
          <ul class="videos">'.$TMP_videos.'</ul>';
	$TMP_separador='<p class="linea"></p>';
}
if ($TMP_imagenes!="") {
	$TMP_imagenes=$TMP_imagenesbase.$TMP_separador.'<div class="franja_gris">'.$A_lit[5].'</div> <! IMG >
          <ul class="otros">'.$TMP_imagenes.'</ul>';
	$TMP_separador='<p class="linea"></p>';
}
if ($TMP_noticias!="") {
	$TMP_noticias=$TMP_separador.'<div class="franja_gris">'.$A_lit[3].'</div> <! NOTIC >
          <ul class="otros">'.$TMP_noticias.'</ul>';
	$TMP_separador='<p class="linea"></p>';
}
if ($TMP_actividades!="") {
	$TMP_actividades=$TMP_separador.'<div class="franja_gris">'.$A_lit[4].'</div> <! ACTIV >
          <ul class="otros">'.$TMP_actividades.'</ul>';
	$TMP_separador='<p class="linea"></p>';
}
if ($TMP_enlaces!="") {
	$TMP_enlaces='<div class="lista_enlaces">
          <div class="franja_gris">'.$A_lit[10].'</div> <! ENL >
          <ul>'.$TMP_enlaces.'
          </ul>
        </div>
        <div class="img_flecha"></div>';
}
if ($TMP_noticias=="" && $TMP_actividades=="" && $TMP_imagenes!="") $TMP_imagenes.=$TMP_separador;

if (trim($TMP_documentos)=="" && trim($TMP_videos)=="" && trim($TMP_imagenes)=="" && trim($TMP_noticias)=="" && trim($TMP_actividades)=="" && trim($TMP_enlaces)=="") {
	$content="";
} else {
	// 3=Noticia, 4=Actividades, 5=Imagenes, 7=Documentos, 9=Videos, 10=Enlaces
	$content.='
        <div class="relacionados">
          '.$TMP_documentos.'
          '.$TMP_videos.'
          '.$TMP_imagenes.'
          '.$TMP_noticias.'
          '.$TMP_actividades.'
        </div>
          '.$TMP_enlaces.'
';
}
        
return $content;
?>
