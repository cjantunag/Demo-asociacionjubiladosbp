<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");
// Busca las Noticias/Actividades

$MaxRegPerType=10;
if ($query!="") $search=$query;
if (trim($search)=="") {
	error(_DEF_NLSErrBusqueda);
	die();
}

$search=str_replace("'","\'",$search);
$search=str_replace(" ","%",$search);

include ("header.php");

echo "
<script>
document.getElementById('columna1').innerHTML='<h2><span>Buscar</span></h2>';
document.getElementById('ruta').innerHTML='<span>Buscar </span>';
</script>
<h1>Buscar</h1>
<h2>"._DEF_NLSResBusqueda.": <u>".str_replace("<", "&lt;", $search)."</u></h2>
<ul class='lista_ficha'>
";

	if ($search=="") die();

	if (file_exists("modules/".$V_dir."/common.app.php")) include ("modules/".$V_dir."/common.app.php");
	if ($defaultfilter!="") $tmpdefaultfilter=" AND ".$defaultfilter;

	$TMP_time = time();
	$TMP_url = "index.php?V_dir=MSC";

	$TMP_lang=getSessionVar("SESSION_lang");

///////// Contenidos
	$TMP_content2=""; $TMP_hayul2=false;
	$TMP_SQLcont.=" AND (tema LIKE '%".$search."%' OR contenido LIKE '%".$search."%' OR observaciones LIKE '%".$search."%')";
	if ($TMP_lang!="spanish" && $TMP_lang!="") {
		$TMP_SQLcont.=" AND ( tema_".$TMP_lang." LIKE '%".$search."%' OR contenido_".$TMP_lang." LIKE '%".$search."%' OR observaciones_".$TMP_lang." LIKE '%".$search."%')";
	}
	$TMP_old_idcat="";
	$TMP_result = sql_query("SELECT * FROM contenidos WHERE activo='1' AND fechapubli<'$TMP_time' $tmpdefaultfilter ".$TMP_SQLcont." ORDER BY idcat, orden, id", $RAD_dbi);
	$TMP_cont=0;
	while($TMP_row = sql_fetch_array($TMP_result, $RAD_dbi)) {
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
		if ($TMP_old_idcat!=$TMP_row[idcat]) {
			$TMP_result2 = sql_query("SELECT * FROM contenidos WHERE activo='1' $tmpdefaultfilter AND idcat='".$TMP_row[idcat]."'", $RAD_dbi);
			$TMP_row2 = sql_fetch_array($TMP_result2, $RAD_dbi);
			if (trim($TMP_row2["contenido_".$TMP_lang])!="") $TMP_row2[contenido]=$TMP_row2["contenido_".$TMP_lang];
			if (trim($TMP_row2["nombre_".$TMP_lang])!="") $TMP_row2[nombre]=$TMP_row2["nombre_".$TMP_lang];
			if (trim($TMP_row2["tema_".$TMP_lang])!="") $TMP_row2[tema]=$TMP_row2["tema_".$TMP_lang];
			$TMP_old_idcat=$TMP_row[idcat];

			$A_idcats=explode(",",$TMP_row[idcat].",");
			foreach($A_idcats as $TMP_idx=>$TMP_idcatx) if ($TMP_idcatx>0) $TMP_row[idcat]=$TMP_idcatx;

			$TMP_result3 = sql_query("SELECT * FROM categorias WHERE activo='1' AND id='".$TMP_row[idcat]."'".$TMP_SQLcat, $RAD_dbi);
			$TMP_row3 = sql_fetch_array($TMP_result3, $RAD_dbi);
			if (trim($TMP_row3["literal_".$TMP_lang])!="") $TMP_row3[literal]=$TMP_row3["literal_".$TMP_lang];

			$V_mod="shownews&idcat=".$TMP_row3[id];
			if ($TMP_row3[categoria]=="forum") $V_mod="forum&type=forum";
			if ($TMP_row3[categoria]=="dict") $V_mod="dict&type=dict";
			if ($TMP_row[idcat]=="3") $V_mod="shownews&V_idmod=62"; // noticias
			if ($TMP_row[idcat]=="4") $V_mod="shownews&V_idmod=121"; // actividades
			if ($TMP_row[idcat]=="5") $V_mod="shownews&V_idmod=124"; // documentos
			if ($TMP_row[idcat]=="7") $V_mod="shownews&V_idmod=122"; // videos
			if ($TMP_row[idcat]=="9") $V_mod="shownews&V_idmod=123"; // imagenes
			if ($TMP_row[idcat]=="10") $V_mod="shownews&V_idmod=125"; // enlaces
			$TMP_tema=$TMP_row3[tema]." ".$TMP_row3[literal];
			$TMP_idparent=$TMP_row2[id];

			if ($TMP_hayul2==true) $TMP_content2.="</ul>";
			$TMP_content2.="<li><a href=\"".$TMP_url."&V_mod=$V_mod\"><span class='entrada'>".$TMP_tema."</span></a><ul>";
			$TMP_hayul2=true;
			$TMP_cont=0;
		}
		if ($TMP_row[idcat]=="10") continue; // los enlaces no se muestran
		if ($TMP_cont==$MaxRegPerType) $TMP_content2.="<li>&nbsp;&nbsp;&nbsp;&nbsp; ... </li>";
		$TMP_cont++;
		if ($TMP_cont>$MaxRegPerType) continue;
		$TMP_content2.="<li><a href=\"".$TMP_url."&V_mod=$V_mod&idn=".$TMP_row[id]."\"><b>".$TMP_row["tema"]."</b></a></li>";
	}
	if ($TMP_hayul2==true) $TMP_content2.="</ul>";
///////// 



///////// Proyectos
	$TMP_contentp=""; $TMP_hayul2=false;
	$TMP_SQLcont=" (proyecto LIKE '%".$search."%' OR resumen LIKE '%".$search."%' OR descripcion LIKE '%".$search."%')";
	if ($TMP_lang!="spanish" && $TMP_lang!="") {
		$TMP_SQLcont=" (proyecto_".$TMP_lang." LIKE '%".$search."%' OR resumen_".$TMP_lang." LIKE '%".$search."%' OR descripcion_".$TMP_lang." LIKE '%".$search."%')";
	}
	$TMP_result2 = sql_query("SELECT * FROM proyectoscategorias order by categoria", $RAD_dbi);
	while($TMP_row2 = sql_fetch_array($TMP_result2, $RAD_dbi)) {
	   $TMP_cont=0;
	   $TMP_result = sql_query("SELECT * FROM proyectos WHERE ".$TMP_SQLcont." and idproyectocategoria='".$TMP_row2[idproyectocategoria]."' ORDER BY proyecto", $RAD_dbi);
	   while($TMP_row = sql_fetch_array($TMP_result, $RAD_dbi)) {
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
		$V_mod="showproyecto&idcat=".$TMP_row2[idproyectocategoria];
		if ($TMP_cont==0) {
			if ($TMP_hayul2==true) $TMP_contentp.="</li></ul>\n";
			$TMP_contentp.="<li><a href=\"".$TMP_url."&V_mod=$V_mod\"><span class='entrada'>".$TMP_row2[categoria]."</span></a><ul>\n";
			$TMP_hayul2=true;
		}
		$TMP_cont++;
		if ($TMP_cont>$MaxRegPerType) continue;
		$TMP_contentp.=" <li><a href=\"".$TMP_url."&V_mod=$V_mod&idproyecto=".$TMP_row[idproyecto]."\"><b>".$TMP_row["proyecto"]."</b></a></li>\n";
	   }
	}
	if ($TMP_hayul2==true) {
		$TMP_contentp.="</li></ul>\n";
		$TMP_hayul2=false;
	}
///////// 




///////// Acciones
	$TMP_contentac=""; $TMP_hayul2=false;
	$TMP_SQLcont=" (accion LIKE '%".$search."%' OR resumen LIKE '%".$search."%' OR descripcion LIKE '%".$search."%')";
	if ($TMP_lang!="spanish" && $TMP_lang!="") {
		$TMP_SQLcont=" (accion_".$TMP_lang." LIKE '%".$search."%' OR resumen_".$TMP_lang." LIKE '%".$search."%' OR descripcion_".$TMP_lang." LIKE '%".$search."%')";
	}
	$TMP_cont=0;
	$TMP_result = sql_query("SELECT * FROM acciones WHERE ".$TMP_SQLcont." ORDER BY accion", $RAD_dbi);
	while($TMP_row = sql_fetch_array($TMP_result, $RAD_dbi)) {
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
		$V_mod="showaccion";
		$TMP_cont++;
		if ($TMP_cont>$MaxRegPerType) continue;
		$TMP_contentac.=" <li><a href=\"".$TMP_url."&V_mod=$V_mod&idaccion=".$TMP_row[idaccion]."\"><b>".$TMP_row["accion"]."</b></a></li>\n";
	}
	if ($TMP_hayul2==true) {
		$TMP_contentac.="</li></ul>\n";
		$TMP_hayul2=false;
	}
///////// 




///////// Testimonios
	$TMP_contentte=""; $TMP_hayul2=false;
	$TMP_SQLcont=" (titular LIKE '%".$search."%' OR contenido LIKE '%".$search."%')";
	$TMP_SQLcont=" (nombre LIKE '%".$search."%' OR apellidos LIKE '%".$search."%')";
	if ($TMP_lang!="spanish" && $TMP_lang!="") {
		$TMP_SQLcont=" (nombre_".$TMP_lang." LIKE '%".$search."%' OR apellidos_".$TMP_lang." LIKE '%".$search."%')";
	}
	$TMP_cont=0;
	$TMP_result = sql_query("SELECT * FROM testimonios WHERE ".$TMP_SQLcont." ORDER BY titular", $RAD_dbi);
	while($TMP_row = sql_fetch_array($TMP_result, $RAD_dbi)) {
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
		$V_mod="showtest";
		$TMP_cont++;
		if ($TMP_cont>$MaxRegPerType) continue;
		$TMP_contentte.=" <li><a href=\"".$TMP_url."&V_mod=$V_mod&idt=".$TMP_row[idtestimonio]."\"><b>".$TMP_row["nombre"]." ".$TMP_row[apellidos]."</b></a></li>\n";
	}
	if ($TMP_hayul2==true) {
		$TMP_contentte.="</li></ul>\n";
		$TMP_hayul2=false;
	}
///////// 




///////// Productos/Articulos Tienda
	$TMP_contentart=""; $TMP_hayul2=false;
	$TMP_SQLcont=" (articulo LIKE '%".$search."%' OR descripcion LIKE '%".$search."%' OR observaciones LIKE '%".$search."%')";
	if ($TMP_lang!="spanish" && $TMP_lang!="") {
		$TMP_SQLcont=" (articulo_".$TMP_lang." LIKE '%".$search."%' OR descripcion_".$TMP_lang." LIKE '%".$search."%' OR observaciones_".$TMP_lang." LIKE '%".$search."%')";
	}
	$TMP_cont=0;
	$TMP_result = sql_query("SELECT * FROM GIE_articulos WHERE ".$TMP_SQLcont." ORDER BY articulo", $RAD_dbi);
	while($TMP_row = sql_fetch_array($TMP_result, $RAD_dbi)) {
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
		$V_mod="tienda";
		$TMP_cont++;
		if ($TMP_cont>$MaxRegPerType) continue;
		$TMP_contentart.=" <li><a href=\"".$TMP_url."&V_mod=$V_mod&id=".$TMP_row[idarticulo]."\"><b>".$TMP_row["articulo"]."</b></a></li>\n";
	}
	if ($TMP_hayul2==true) {
		$TMP_contentart.="</li></ul>\n";
		$TMP_hayul2=false;
	}
///////// 




///////// Articulos
   $TMP_content=""; $TMP_hayul=false;
   $TMP_old_idseccion="";
   $TMP_resultx = sql_query("SELECT * FROM articulossecciones order by orden", $RAD_dbi);
   while($TMP_rowx = sql_fetch_array($TMP_resultx, $RAD_dbi)) {
	$TMP_SQLart=" (nombre LIKE '%".$search."%' OR contenido LIKE '%".$search."%')";
	if ($TMP_lang!="spanish" && $TMP_lang!="") {
		$TMP_SQLart=" (nombre_".$TMP_lang." LIKE '%".$search."%' OR contenido_".$TMP_lang." LIKE '%".$search."%')";
	}
	$TMP_result = sql_query("SELECT * FROM articulos WHERE ".$TMP_SQLart." and idseccion='".$TMP_rowx[id]."' ORDER BY orden", $RAD_dbi);
	//$TMP_result = sql_query("SELECT * FROM articulos WHERE ".$TMP_SQLart." ORDER BY idseccion, orden", $RAD_dbi);
	$TMP_cont=0;
	$TMP_hayul=false;
	while($TMP_row = sql_fetch_array($TMP_result, $RAD_dbi)) {
		if ($TMP_row["visible"]!="1") continue;
		//if ($TMP_row["activo"]!="1") continue;
		if (trim($TMP_row["contenido_".$TMP_lang])!="") $TMP_row[contenido]=$TMP_row["contenido_".$TMP_lang];
		if (trim($TMP_row["nombre_".$TMP_lang])!="") $TMP_row[nombre]=$TMP_row["nombre_".$TMP_lang];
		if ($TMP_old_idseccion!=$TMP_row[idseccion]) {
			$TMP_result2 = sql_query("SELECT * FROM articulossecciones WHERE id='".$TMP_row[idseccion]."'", $RAD_dbi);
			$TMP_row2 = sql_fetch_array($TMP_result2, $RAD_dbi);
			if (trim($TMP_row2["nombre_".$TMP_lang])!="") $TMP_row2[nombre]=$TMP_row2["nombre_".$TMP_lang];
			$TMP_old_idseccion=$TMP_row[idseccion];
			if ($TMP_hayul==true) $TMP_content.="</ul>";
			$TMP_content.="<li><span class='entrada'>".$TMP_row2["nombre"]."</span><ul>";
			$TMP_hayul=true;
			$TMP_cont=0;
		}
		if ($TMP_cont==$MaxRegPerType) $TMP_content.="&nbsp;&nbsp;&nbsp;&nbsp; ... <br>";
		$TMP_cont++;
		if ($TMP_cont>$MaxRegPerType) continue;
		if ($TMP_row[id]=="43" && $TMP_contentart!="") {
			$TMP_content.="<li><span class='entrada'>".$TMP_row["nombre"]."</span>";
			$TMP_content.="\n<ul>".$TMP_contentart."</ul>";
			$TMP_contentart="";
		} else if ($TMP_row[id]=="44" && $TMP_contentac!="") {
			$TMP_content.="<li><span class='entrada'>".$TMP_row["nombre"]."</span>";
			$TMP_content.="\n<ul>".$TMP_contentac."</ul>";
			$TMP_contentac="";
		} else {
			$TMP_content.="<li><a href=\"".$TMP_url."&V_mod=showart&artid=".$TMP_row[id]."\"><b>".$TMP_row["nombre"]."</b></a>";
		}
		$TMP_content.="</li>";
	}
	if ($TMP_contentp!="" && $TMP_rowx[id]=="2") { // proyectos
		if ($TMP_hayul==false) {
			$TMP_content.="<li><span class='entrada'>".$TMP_rowx["nombre"]."</span><ul>";
			$TMP_hayul=true;
		}
		$TMP_content.="\n".$TMP_contentp;
	}
	if ($TMP_contentart!="" && $TMP_rowx[id]=="3") { // tienda
		if ($TMP_hayul==false) {
			$TMP_content.="<li><span class='entrada'>".$TMP_rowx["nombre"]."</span><ul>";
			$TMP_hayul=true;
		}
		$TMP_content.="\n<li><span class='entrada'>Tienda</span><ul>".$TMP_contentart."</ul></li>";
	}
	if ($TMP_contentac!="" && $TMP_rowx[id]=="3") { // acciones
		if ($TMP_hayul==false) {
			$TMP_content.="<li><span class='entrada'>".$TMP_rowx["nombre"]."</span><ul>";
			$TMP_hayul=true;
		}
		$TMP_content.="\n<li><span class='entrada'>Apoya con tu firma</span><ul>".$TMP_contentac."</ul></li>";
	}
	if ($TMP_contentte!="" && $TMP_rowx[id]=="3") { // testimonios
		if ($TMP_hayul==false) {
			$TMP_content.="<li><span class='entrada'>".$TMP_rowx["nombre"]."</span><ul>";
			$TMP_hayul=true;
		}
		$TMP_content.="\n<li><span class='entrada'>Testimonios</span><ul>".$TMP_contentte."</ul></li>";
	}
	if ($TMP_content2!="" && $TMP_rowx[id]=="6") { // noticias y demas
		if ($TMP_hayul==false) {
			$TMP_content.="<li><span class='entrada'>".$TMP_rowx["nombre"]."</span><ul>";
			$TMP_hayul=true;
		}
		$TMP_content.="\n".$TMP_content2;
	}
	if ($TMP_hayul==true) $TMP_content.="</ul>";
   }


if ($TMP_content=="") echo _NO_CONTENTS;
else echo $TMP_content;

echo '</ul>';
include ("footer.php"); 
?>
