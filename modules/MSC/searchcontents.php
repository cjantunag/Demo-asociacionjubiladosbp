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

	//if ($search=="") die();
	if (strlen(utf8_decode($search))<3) {
		echo "<b>"._DEF_NLSErrSearch."</b>";
		include_once("footer.php");
		return;
	}

	if (file_exists("modules/".$V_dir."/common.app.php")) include ("modules/".$V_dir."/common.app.php");
	if ($defaultfilter!="") $tmpdefaultfilter=" AND ".$defaultfilter;

global $A_show, $TMP_contentproyectos, $TMP_contentacciones, $TMP_contentproductos, $TMP_contenttestimonios, $TMP_contentnoticias;
$A_show=array(); // Articulos "Axx" y modulos "Mxx" a mostrar
echo mapawebsearch($search);
echo '</ul>';

include_once("footer.php");
return;
//---------------------------------------------------------------------------------------------------------
function mapawebsearch($search) {
global $RAD_dbi, $SESSION_SID, $V_idmod, $V_dir, $V_mod, $dbname, $id;
global $A_show, $TMP_contentproyectos, $TMP_contentacciones, $TMP_contentproductos, $TMP_contenttestimonios, $TMP_contentnoticias;
if ($dbname!="" && $dbname!=_DEF_dbname) $xdbname="&dbname=$dbname";

if (is_user() && _DEF_ADD_URLTIME=="1") $TMP_time="&time=".time();
else $TMP_time="";
if (!is_user()) $TMP_CondPublico=" AND publico!='0' ";
else $TMP_CondPublico="";

$TMP_lang=getSessionVar("SESSION_lang");

$A_grupopadre=array(); 	// Lista de grupos padre iniciales de articulo o de modulos

$S_seccion=array(); 	// Lista de nombres de secciones para poner a los articulos (temporal)
$A_padreart=array();	// Articulo Padre si lo tiene (temporal)

global $A_id, $A_orden, $A_item, $A_link, $A_grupo;
$A_orden=array(); 	// Lista de Orden de Items articulos y modulos dentro de un Grupo
$A_id=array(); 		// Lista de Id de Items articulos y modulos
$A_item=array(); 	// Lista de Items articulos y modulos
$A_link=array();	// Enlace del Item
$A_grupo=array();	// Lista de grupos

////////////////////////////////////////////// ARTICULOS /////////////////////////////////////////////////////////
// Para modulo articulos el parametro id contiene el articulo mostrado
if ($id!="" && $V_mod=="articulos") $TMP_artid=$id;
else $TMP_artid=0;

// se leen las secciones o grupos de articulos
$cmdsql = "select * from articulossecciones where visible='1' order by orden ASC";
$result = sql_query($cmdsql, $RAD_dbi);
while ($row = sql_fetch_array($result,$RAD_dbi)) {
	foreach($row as $TMP_k=>$TMP_v) if ($row[$TMP_k."_".$TMP_lang]!="") $row[$TMP_k]=$row[$TMP_k."_".$TMP_lang];
	if (trim($row[nombre])=="") continue;	// Seccion sin nombre
	$S_seccion[$row[id]]=$row[nombre];
	$A_grupopadre[$row[nombre]]=$row[orden]; 		// Grupo padre inicial
}

// se leen los articulos 
$sql="select * from articulos where visible='1' $TMP_CondPublico order by orden ASC";
$result=sql_query($sql, $RAD_dbi);
while ($row=sql_fetch_array($result,$RAD_dbi)) {
	foreach($row as $TMP_k=>$TMP_v) if ($row[$TMP_k."_".$TMP_lang]!="") $row[$TMP_k]=$row[$TMP_k."_".$TMP_lang];
	$URLArt="index.php?V_dir=MSC&V_mod=showart&id=".$row[id].$SESSION_SID;
	if ($row[contenido]=="" && $row[url]=="") $URLArt=""; // articulo vacio
	if ($row[url_seo]!="") $URLArt=$row[url_seo];
	if ($URLArt=="") { // busca url del hijo si lo tiene
		$sql2="select * from articulos where idartparent='".$row[id]."' and visible='1' $TMP_CondPublico order by orden ASC";
		$result2=sql_query($sql2, $RAD_dbi);
		$row2=sql_fetch_array($result2,$RAD_dbi);
		foreach($row2 as $TMP_k=>$TMP_v) if ($row2[$TMP_k."_".$TMP_lang]!="") $row2[$TMP_k]=$row2[$TMP_k."_".$TMP_lang];
		$URLArt="index.php?V_dir=MSC&V_mod=showart&id=".$row2[id].$SESSION_SID;
		if ($row2[url_seo]!="") $URLArt=$row2[url_seo];
	}
	//if (!$row[idartparent]>0) {
		if ($row[id]=="48" || $row[id]=="49" || $row[id]=="50" || $row[id]=="44" || $row[id]=="43") {
			// se muestra siempre
		} else {
			if (!eregi($search,html_entity_decode($row[nombre],ENT_NOQUOTES,"UTF-8")) && !eregi($search,html_entity_decode($row[contenido],ENT_NOQUOTES,"UTF-8"))) continue;
		}
	//}
/////echo $row[id]." ".$row[idartparent]." ".$row[nombre]."<br>";
	$TMP_nombregrupo=$S_seccion[$row[idseccion]];
	$A_orden[$TMP_nombregrupo]["A".$row[id]]=$row[orden];	// Orden del Item dentro del Grupo
	$A_id[$row[nombre]]="A".$row[id];			// Id del Item
	$A_item["A".$row[id]]=$row[nombre];			// Nombre del Item
	$A_link["A".$row[id]]=$URLArt;				// Enlace del Item
	$A_grupo["A".$row[id]]=$TMP_nombregrupo;		// Grupo padre del Item
	if ($row[idartparent]>0) $A_padreart[$row[id]]=$row[idartparent];
	//echo "Art=".$TMP_nombregrupo."*".$row[id]."*".$A_orden[$TMP_nombregrupo]["A".$row[id]]."+".$row[nombre].".$URLArt<br>";
}

foreach($A_padreart as $TMP_id=>$TMP_idpadre) { // los articulos que sean hijos de otros articulos se les pone de grupo su articulo padre 
	$TMP_oldgrupo=$A_grupo["A".$TMP_id];
	$TMP_orden=$A_orden[$TMP_oldgrupo]["A".$TMP_id];
	$TMP_newgrupo=$A_item["A".$TMP_idpadre];
	//echo "Cambia idart $TMP_id del grupo $TMP_oldgrupo a $TMP_newgrupo <br>";
	if ($A_item["A".$TMP_idpadre]!="") {
		unset($A_orden[$TMP_oldgrupo]["A".$TMP_id]);
		$A_orden[$TMP_newgrupo]["A".$TMP_id]=$TMP_orden;
		$A_grupo["A".$TMP_id]=$A_item["A".$TMP_idpadre];
	}
}

////////////////////////////////////////////// MODULOS /////////////////////////////////////////////////////////
$result = sql_query("select * from modulos where activo='1' and visible='1' ORDER BY orden", $RAD_dbi);
while($row=sql_fetch_array($result, $RAD_dbi)) {
	if ($row[fichero]=="saveform") continue; // ignora formularios
	foreach($row as $TMP_k=>$TMP_v) if ($row[$TMP_k."_".$TMP_lang]!="") $row[$TMP_k]=$row[$TMP_k."_".$TMP_lang];
	if (!verifyProfile($row[perfiles],$row[publico])) continue;
	if (trim($row[grupomenu])=="") continue;
	if (trim($row[literalmenu])=="") {
		$A_grupopadre[$row[grupomenu]]=$row[orden]; 		// Grupo padre inicial
		continue;
	}
	//echo "Mod=".$row[grupomenu]."*".$row[idmodulo]."*".$row[orden]."+".$row[literalmenu].".$URLArt<br>";
	$TMP_pars="";
	if ($row[fichero]!="") $TMP_pars.="&V_mod=".$row[fichero];
//	if ($TMP_parsblock!="") $TMP_pars.="&".$row[parametros];
	$URLMod="index.php?V_dir=".$row[directorio]."&V_idmod=".$row[idmodulo]."$TMP_pars".$xdbname.$TMP_time.$SESSION_SID;
	if ($row[url_seo]!="") $URLMod=$row[url_seo];
	if ($row[fichero]=="" && $row[url_seo]=="") $URLMod="";		// Es un Grupo Padre-Hijo
	$TMP_nombregrupo=$S_seccion[$row[idseccion]];
	$A_orden[$row[grupomenu]]["M".$row[idmodulo]]=$row[orden];	// Orden del Item dentro del Grupo
	$A_id[$row[literalmenu]]="M".$row[idmodulo];			// Id del Item
	$A_item["M".$row[idmodulo]]=$row[literalmenu];		// Nombre del Item
	$A_link["M".$row[idmodulo]]=$URLMod;				// Enlace del Item
	$A_grupo["M".$row[idmodulo]]=$row[grupomenu];			// Grupo padre del Item
}
if (!count($A_grupopadre)>0) return "";

// Recorre grupos padres y sus grupos/item hijos y muestra el arbol 
// (opcional:si solo hay un Item en el Grupo no se abre arbol, solo se muestra rama de grupos e items seleccionados
$TMP_menus="";
asort($A_grupopadre); // Ordena Grupos Padre
foreach($A_grupopadre as $TMP_grupo=>$TMP_orden) {
	$TMP_linkgrupo="";
	if (count($A_orden[$TMP_grupo])>0) foreach($A_orden[$TMP_grupo] as $TMP_id=>$TMP_orden) {
		$TMP_link=$A_link[$TMP_id];
		if ($TMP_linkgrupo=="") $TMP_linkgrupo=$TMP_link;
	}
	$TMP_menuslat=showGrupoMapa($TMP_grupo,0,$search);
	if (ereg("showproyecto", $TMP_linkgrupo)) $TMP_menuslat.="".menuProyectos($search);
	if ($TMP_menuslat!="") $TMP_menus.="	<li><a href='".$TMP_linkgrupo."'><span class='entrada'>".$TMP_grupo."</span></a>\n	<ul>".$TMP_menuslat."	</ul>\n	</li>\n";
	//else $TMP_menus.="	<li><a href='".$TMP_linkgrupo."'><span class='entrada'>".$TMP_grupo."</span></a>\n	</li>\n"; // No se muestra si esta vacio
}

if ($TMP_menus=="") return _NO_CONTENTS;
return $TMP_menus;
}

//-----------------------------------------------------------------------------------------------
function showGrupoMapa($TMP_grupo, $TMP_level, $search) {
	global $A_id, $A_orden, $A_item, $PHP_SELF, $A_link, $A_grupo;

	$TMP_menu="";
	$TMP_linkgrupo="";
	$TMP_contmenus=0;
	if (count($A_orden[$TMP_grupo])>0) foreach($A_orden[$TMP_grupo] as $TMP_id=>$TMP_orden) {	
		$TMP_menuhijo="";
		$TMP_contmenus++;
		$TMP_item=$A_item[$TMP_id];
///echo $TMP_grupo." ".$TMP_level." ".$TMP_id." ".$TMP_item."<br>";
		if ($TMP_item=="&nbsp;") continue; // si el item es espacio no se muestra
		$TMP_link=$A_link[$TMP_id];
		if ($TMP_id=="A44") $TMP_link=$PHP_SELF."?V_dir=MSC&V_mod=showaccion&term=";
		if ($TMP_id=="M126") $TMP_link=$PHP_SELF."?V_dir=MSC&V_mod=showtest";
		// no se muestran los siguientes modulos
		if ($TMP_id=="M128") continue;
		if ($TMP_id=="M120") continue;
		if ($TMP_id=="M121") continue;
		if ($TMP_id=="M122") continue;
		if ($TMP_id=="M123") continue;
		if ($TMP_id=="M124") continue;
		if ($TMP_id=="M125") continue;
		if ($TMP_id=="M153") continue;
		if ($TMP_id=="A43") { // Visita nuestra tienda
			$TMP_menunieto=searchProductos($search);
			if ($TMP_menunieto=="") continue;
		}
		if ($TMP_id=="M126") { // Apoya con tu firma
			$TMP_menunieto=searchTestimonios($search);
			if ($TMP_menunieto=="") continue;
		}
		if ($TMP_id=="A44") {
			$TMP_menuhijo=searchAcciones($search);
			if ($TMP_menuhijo=="") continue;
		}
		if ($TMP_linkgrupo=="") $TMP_linkgrupo=$TMP_link;
		////if (!count($A_orden[$TMP_item])>0) { // No es un Grupo Padre-Hijo, solo es un Item
			//if ($TMP_level<1) {
				if ($TMP_grupo!=$TMP_item) $TMP_menuhijo=showGrupoMapa($TMP_item, $TMP_level+1, $search);
			//}
			////if ($TMP_menuhijo=="" && $TMP_id=="A37") continue; // porque ser socio
			////if ($TMP_menuhijo=="" && $TMP_id=="A39") continue; // porque donar
			////if ($TMP_menuhijo=="" && $TMP_id=="A41") continue; // porque ser voluntario
			if ($TMP_menuhijo=="" && $TMP_id=="A48") continue; // hazte socio padre
			if ($TMP_menuhijo=="" && $TMP_id=="A49") continue; // haz un donativo padre
			if ($TMP_menuhijo=="" && $TMP_id=="A50") continue; // ser volunatario padre
			if ($TMP_level==0) $TMP_menu.="\n	<li><a href='".$TMP_link."'>".$TMP_item."</a>";
			else $TMP_menu.="\n		<li><a href='".$TMP_link."'>".$TMP_item."</a>";
			if ($TMP_item!=$TMP_grupo) {
				if ($TMP_level<1) {
					$TMP_menuhijo=showGrupoMapa($TMP_item, $TMP_level+1, $search);
				}
				if ($TMP_id=="A44") {
					$TMP_menuhijo=include("blocks/menuaccionesMSC.php");
					//$TMP_menuhijo=searchAcciones($search);
				}
				if ($TMP_id=="M62") {
					$TMP_menu="";
					$TMP_menuhijo=searchNoticias($search);
				}
				if ($TMP_id=="M127") {
					$TMP_menuhijo=searchTestimonios($search);
					if ($TMP_menuhijo=="") $TMP_menu="";
				}
				if ($TMP_id=="M147") {
					$TMP_menuhijo=searchProductos($search);
					if ($TMP_menuhijo=="") $TMP_menu="";
				}
			}
			//if ($TMP_level>0) $TMP_classul=" class='sin_linea' style='margin-top:0px; margin-left:-8px;'"; 
			if ($TMP_level>0) $TMP_classul=" class='sin_linea'"; 
			else if ($TMP_contmenus==count($A_orden[$TMP_grupo])) $TMP_classul=" class='sin_linea'"; 
			$TMP_classul=""; 
			$TMP_classul=""; 
			if ($TMP_contmenus!=count($A_orden[$TMP_grupo]) || $TMP_menuhijo!="") {
				if (trim($TMP_menuhijo)!="") $TMP_menu.="\n		<ul".$TMP_classul.">\n".$TMP_menuhijo."		</ul>\n";
			}
			if ($TMP_menu!="") $TMP_menu.="		</li>\n";
		////}
	}
//	if ($TMP_contmenus==1) $TMP_menu="";
	return $TMP_menu;
}
//---------------------------------------------------------------------------------------------------------
function menuProyectos($search) {
global $SESSION_SID, $id, $search;
global $RAD_dbi, $content, $dbname, $idproyecto, $idproyectocategoria, $term, $V_mod, $V_idmod, $idmodnews;
if ($dbname=="") $dbname=_DEF_dbname;
$TMP_lang=getSessionVar("SESSION_lang");

$TMP_texto1=_DEF_NLSMapProy1;
$TMP_texto2=_DEF_NLSMapProy2;

$TMP_URLMENU="index.php?V_dir=MSC&V_mod=showproyecto";
$TMP_classnivel1=" class='nivel1'";
$TMP_classnivel1="";
if ($V_mod=="shownews") {
	$TMP_URLMENU="index.php?V_dir=MSC&V_idmod=$V_idmod&V_mod=".$V_mod."&idmodnews=".$idmodnews;
	$TMP_classnivel1="";
}

$A_x=explode(".",basename(__FILE__));
$TMP_fich=$A_x[0];
$TMP_result=sql_query("SELECT * from bloques where fichero='$TMP_fich'", $RAD_dbi);
$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
$content=$TMP_row[contenido]; // prefijo html del bloque a mostrar

if (is_modulepermitted("", "MSC", "proyectos")) $TMP_link=true;
else $TMP_link=false;
$TMP_linkedit="";

$TMP_proyecto=""; $TMP_categ="";

if ($idproyecto>0) {
	$TMP_result=sql_query("SELECT * from proyectos where idproyecto='$idproyecto'", $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
	$TMP_idproyectocategoria=$TMP_row[idproyectocategoria];
}

$TMP_Gfecha=RAD_gregorianDate(date("Y-m-d"));
$TMP_primercatenm=0; $TMP_primercatter=0;
$TMP_result=sql_query("SELECT * from proyectoscategorias order by orden", $RAD_dbi);
while($TMP_row=sql_fetch_array($TMP_result, $RAD_dbi)) {
	foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
	$A_lit[$TMP_row[idproyectocategoria]]=$TMP_row[categoria];
	$TMP_enmarcha=""; $TMP_terminados=""; $TMP_estarama=false; $TMP_primerenmarcha=0; $TMP_primerterminado=0;
	$TMP_result2=sql_query("SELECT * from proyectos where idproyectocategoria='".$TMP_row[idproyectocategoria]."'", $RAD_dbi);
	while($TMP_row2=sql_fetch_array($TMP_result2, $RAD_dbi)) {
		foreach($TMP_row2 as $TMP_k=>$TMP_v) if ($TMP_row2[$TMP_k."_".$TMP_lang]!="") $TMP_row2[$TMP_k]=$TMP_row2[$TMP_k."_".$TMP_lang];
		if (!eregi($search,html_entity_decode($TMP_row2[proyecto],ENT_NOQUOTES,"UTF-8")) && !eregi($search,html_entity_decode($TMP_row2[resumen],ENT_NOQUOTES,"UTF-8")) && !eregi($search,html_entity_decode($TMP_row2[descripcion],ENT_NOQUOTES,"UTF-8"))) continue;
		$TMP_Gfechafin=RAD_gregorianDate($TMP_row2[fechafin]);
		if (!$TMP_Gfechafin>0) $TMP_activo="1";
		else if ($TMP_Gfechafin>$TMP_Gfecha) $TMP_activo="1";
		else $TMP_activo="0";
		if ($TMP_row2[idproyecto]==$idproyecto) $TMP_estarama=true;
		if ($TMP_row2[idproyectocategoria]==$idproyectocategoria) $TMP_estarama=true;
		if ($TMP_row2["proyecto_".$TMP_lang]!="") $TMP_row2["proyecto"]=$TMP_row2["proyecto_".$TMP_lang];
		if ($TMP_activo=="1") {
			if (!$TMP_primerenmarcha>0) $TMP_primerenmarcha=$TMP_row2[idproyecto];
			if (!$TMP_primercatenm>0) $TMP_primercatenm=$TMP_row2[idproyectocategoria];
			if ($TMP_row2[idproyecto]==$idproyecto) $TMP_proyecto=$TMP_row2[proyecto];
			if ($TMP_row2[idproyecto]==$idproyecto) $TMP_enmarcha.="<li>".$TMP_row2[proyecto]."</li>";
			else $TMP_enmarcha.="<li><a href='".$TMP_URLMENU."&idproyecto=".$TMP_row2[idproyecto]."'>".$TMP_row2[proyecto]."</a></li>";
		} else {
			if (!$TMP_primerterminado>0) $TMP_primerterminado=$TMP_row2[idproyecto];
			if (!$TMP_primercatter>0) $TMP_primercatter=$TMP_row2[idproyectocategoria];
			if ($TMP_row2[idproyecto]==$idproyecto) $TMP_proyecto=$TMP_row2[proyecto];
			if ($TMP_row2[idproyecto]==$idproyecto) $TMP_terminados.="<li>".$TMP_row2[proyecto]."</li>";
			else $TMP_terminados.="<li><a href='".$TMP_URLMENU."&idproyecto=".$TMP_row2[idproyecto]."'>".$TMP_row2[proyecto]."</a></li>";
		}
	}
	if (!$idproyecto>0 && !$idproyectocategoria>0) {
		$idproyectocategoria=$TMP_primercatenm;
		if (!$idproyectocategoria>0) $idproyectocategoria=$TMP_primercatenm;
		if ($idproyectocategoria>0) $TMP_estarama=true;
	}
	if ($TMP_estarama==false) {
		//$TMP_terminados="";
		//$TMP_enmarcha="";
	}
	if (trim($TMP_terminados)!="") $TMP_terminados="<ul>".$TMP_terminados."</ul>";
	if (trim($TMP_enmarcha)!="") $TMP_enmarcha="<ul>".$TMP_enmarcha."</ul>";
	if ($TMP_primerenmarcha>0) { // si hay algun proyecto se muestra la rama del arbol
		if ($TMP_row[idproyectocategoria]==$idproyectocategoria && $term=="") $TMP_categ=$TMP_row[categoria];
		//if ($TMP_row[idproyectocategoria]==$idproyectocategoria && $term=="") $content_enm.="		<li>".$TMP_row[categoria]."".$TMP_enmarcha."</li>\n";
		//else $content_enm.="		<li><a href='".$TMP_URLMENU."&idproyectocategoria=".$TMP_row[idproyectocategoria]."'>".$TMP_row[categoria]."</a>".$TMP_enmarcha."</li>\n";
		$content_enm.="		<li><a href='".$TMP_URLMENU."&idproyectocategoria=".$TMP_row[idproyectocategoria]."'>".$TMP_row[categoria]."</a>".$TMP_enmarcha."</li>\n";
	} else {
		if ($TMP_row2[categoria]!="" || $TMP_enmarcha!="") $content_enm.="		<li>".$TMP_row2[categoria].$TMP_enmarcha."</li>\n";
	}
	if ($TMP_primerterminado>0) {
		if ($TMP_row[idproyectocategoria]==$idproyectocategoria && $term!="") $TMP_categ=$TMP_row[categoria];
		//if ($TMP_row[idproyectocategoria]==$idproyectocategoria && $term!="") $content_ter.="		<li>".$TMP_row[categoria]."".$TMP_terminados."</li>\n";
		//else $content_ter.="		<li><a href='".$TMP_URLMENU."&term=x&idproyectocategoria=".$TMP_row[idproyectocategoria]."'>".$TMP_row[categoria]."</a>".$TMP_terminados."</li>\n";
		$content_ter.="		<li><a href='".$TMP_URLMENU."&term=x&idproyectocategoria=".$TMP_row[idproyectocategoria]."'>".$TMP_row[categoria]."</a>".$TMP_terminados."</li>\n";
	} else {
		if ($TMP_row2[categoria]!="" || $TMP_terminados!="") $content_ter.="		<li>".$TMP_row2[categoria].$TMP_terminados."</li>\n";
	}
}
if (trim($content_enm)!="") $content_enm="<ul>".$content_enm."</ul>";
if (trim($content_ter)!="") $content_ter="<ul>".$content_ter."</ul>";
if (trim($content_enm)!="" || trim($content_ter)!="") $content.="
	<ul>
";
if (trim($content_enm)!="") $content.="
	<li".$TMP_classnivel1."><a href='".$TMP_URLMENU."&idproyectocategoria=".$TMP_primercatenm."'>".$TMP_texto1."</a>
		".$content_enm."
	</li>
";
if (trim($content_ter)!="") $content.="
	<li".$TMP_classnivel1."><a href='".$TMP_URLMENU."&term=x&idproyectocategoria=".$TMP_primercatter."'>".$TMP_texto2."</a>
		".$content_ter."
	</li>
";
if (trim($content_enm)!="" || trim($content_ter)!="") $content.="
	</ul>
";	

if ($TMP_categ=="") $TMP_categ=$A_lit[$TMP_idproyectocategoria];
if (!$idproyecto>0 && !$idproyectocategoria>0) $idproyectocategoria=$TMP_idproyectocategoria; // categoria por defecto (la primera)

return $content;
}

//---------------------------------------------------------------------------------------------------------
function searchNoticias($search) {
global $SESSION_SID, $id, $MaxRegPerType;
global $RAD_dbi, $content, $dbname, $idproyecto, $idproyectocategoria, $term, $V_mod, $V_idmod, $idmodnews;
if ($dbname=="") $dbname=_DEF_dbname;
$TMP_lang=getSessionVar("SESSION_lang");

	$TMP_time = time();
	$TMP_url = $PHP_SELF."?V_dir=MSC";
	$TMP_lang=getSessionVar("SESSION_lang");

///////// Contenidos
	$TMP_contentnoticias=""; $TMP_hayul2=false;
	$TMP_old_idcat="";
	$TMP_result = sql_query("SELECT * FROM contenidos WHERE activo='1' AND fechapubli<'$TMP_time' $tmpdefaultfilter ORDER BY idcat, orden, id", $RAD_dbi);
	$TMP_cont=0;
	while($TMP_row = sql_fetch_array($TMP_result, $RAD_dbi)) { // barre contenidos por categorias
		if ($TMP_row[idcat]=="10" && $TMP_row[tema]=="") continue; // los enlaces sin tema se ignoran
		if ($TMP_row[idcat]=="10" && $TMP_row[idaccion]>0) continue; // los enlaces asociados a otra cosa se ignoran
		if ($TMP_row[idcat]=="10" && $TMP_row[idproyecto]>0) continue; // los enlaces asociados a otra cosa se ignoran
		if ($TMP_row[idcat]=="10" && $TMP_row[idpadre]>0) continue; // los enlaces asociados a otra cosa se ignoran
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
		if (!eregi($search,html_entity_decode($TMP_row[tema],ENT_NOQUOTES,"UTF-8")) && !eregi($search,html_entity_decode($TMP_row[contenido],ENT_NOQUOTES,"UTF-8")) && !eregi($search,html_entity_decode($TMP_row[observaciones],ENT_NOQUOTES,"UTF-8"))) continue;
		if ($TMP_old_idcat!=$TMP_row[idcat]) {
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
			$TMP_idparent=$TMP_row[id];

			if ($TMP_hayul2==true) $TMP_contentnoticias.="</ul>";
			$TMP_contentnoticias.="<li><a href=\"".$TMP_url."&V_mod=$V_mod\"><span class='entrada'>".$TMP_tema."</span></a><ul>";
			$TMP_hayul2=true;
			$TMP_cont=0;
		}
		if ($TMP_row[idcat]=="10") continue; // los enlaces no se muestran, solo su categoria
		if ($TMP_cont==$MaxRegPerType) $TMP_contentnoticias.="<li>&nbsp;&nbsp;&nbsp;&nbsp; ... </li>";
		$TMP_cont++;
		if ($TMP_cont>$MaxRegPerType) continue;
		$TMP_contentnoticias.="<li><a href=\"".$TMP_url."&V_mod=$V_mod&idn=".$TMP_row[id]."\"><b>".$TMP_row["tema"]."</b></a></li>";
	}
	if ($TMP_hayul2==true) $TMP_contentnoticias.="</ul>";
///////// 

	return $TMP_contentnoticias;
}

//---------------------------------------------------------------------------------------------------------
function searchAcciones($search) {
global $SESSION_SID, $id, $MaxRegPerType;
global $RAD_dbi, $content, $dbname, $idproyecto, $idproyectocategoria, $term, $V_mod, $V_idmod, $idmodnews;
if ($dbname=="") $dbname=_DEF_dbname;
$TMP_lang=getSessionVar("SESSION_lang");

	$TMP_time = time();
	$TMP_url = $PHP_SELF."?V_dir=MSC";
	$TMP_lang=getSessionVar("SESSION_lang");

///////// Acciones
	$TMP_contentacciones="";
	$TMP_cont=0;
	$TMP_result = sql_query("SELECT * FROM acciones ORDER BY accion", $RAD_dbi);
	while($TMP_row = sql_fetch_array($TMP_result, $RAD_dbi)) {
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
		if (!eregi($search,html_entity_decode($TMP_row[accion],ENT_NOQUOTES,"UTF-8")) && !eregi($search,html_entity_decode($TMP_row[resumen],ENT_NOQUOTES,"UTF-8")) && !eregi($search,html_entity_decode($TMP_row[descripcion],ENT_NOQUOTES,"UTF-8"))) continue;
		$TMP_mod="showaccion";
		$TMP_cont++;
		if ($TMP_cont>$MaxRegPerType) continue;
		$TMP_contentacciones.=" <li><a href=\"".$TMP_url."&V_mod=$TMP_mod&idaccion=".$TMP_row[idaccion]."\"><b>".$TMP_row["accion"]."</b></a></li>\n";
	}
	if ($TMP_hayul2==true) {
		$TMP_contentacciones.="</li></ul>\n";
		$TMP_hayul2=false;
	}
///////// 

	return $TMP_contentacciones;
}

//---------------------------------------------------------------------------------------------------------
function searchTestimonios($search) {
global $SESSION_SID, $id, $MaxRegPerType;
global $RAD_dbi, $content, $dbname, $idproyecto, $idproyectocategoria, $term, $V_mod, $V_idmod, $idmodnews;
if ($dbname=="") $dbname=_DEF_dbname;
$TMP_lang=getSessionVar("SESSION_lang");

	$TMP_time = time();
	$TMP_url = $PHP_SELF."?V_dir=MSC";
	$TMP_lang=getSessionVar("SESSION_lang");

///////// Testimonios
	$TMP_contenttestimonios="";
	$TMP_cont=0;
	$TMP_result = sql_query("SELECT * FROM testimonios ORDER BY titular", $RAD_dbi);
	while($TMP_row = sql_fetch_array($TMP_result, $RAD_dbi)) {
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
		if (!eregi($search,html_entity_decode($TMP_row[nombre],ENT_NOQUOTES,"UTF-8")) && !eregi($search,html_entity_decode($TMP_row[apellidos],ENT_NOQUOTES,"UTF-8"))) continue;
		$TMP_cont++;
		if ($TMP_cont>$MaxRegPerType) continue;
		$TMP_contenttestimonios.=" <li><a href=\"".$TMP_url."&V_mod=showtest&idt=".$TMP_row[idtestimonio]."\"><b>".$TMP_row["nombre"]." ".$TMP_row[apellidos]."</b></a></li>\n";
	}
///////// 
	return $TMP_contenttestimonios;
}

//---------------------------------------------------------------------------------------------------------
function searchProductos($search) {
global $SESSION_SID, $id, $MaxRegPerType;
global $RAD_dbi, $content, $dbname, $idproyecto, $idproyectocategoria, $term, $V_mod, $V_idmod, $idmodnews;
if ($dbname=="") $dbname=_DEF_dbname;
$TMP_lang=getSessionVar("SESSION_lang");

	$TMP_time = time();
	$TMP_url = $PHP_SELF."?V_dir=MSC";
	$TMP_lang=getSessionVar("SESSION_lang");

///////// Productos/Articulos Tienda
	$TMP_contentproductos=""; $TMP_hayul2=false;
	$TMP_cont=0;
	$TMP_result = sql_query("SELECT * FROM GIE_articulos articulo", $RAD_dbi);
	while($TMP_row = sql_fetch_array($TMP_result, $RAD_dbi)) {
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
		if (!eregi($search,html_entity_decode($TMP_row[articulo],ENT_NOQUOTES,"UTF-8")) && !eregi($search,html_entity_decode($TMP_row[descripcion],ENT_NOQUOTES,"UTF-8")) && !eregi($search,html_entity_decode($TMP_row[observaciones],ENT_NOQUOTES,"UTF-8"))) continue;
		$TMP_mod="tienda";
		$TMP_cont++;
		if ($TMP_cont>$MaxRegPerType) continue;
		$TMP_contentproductos.=" <li><a href=\"".$TMP_url."&V_mod=$TMP_mod&id=".$TMP_row[idarticulo]."\"><b>".$TMP_row["articulo"]."</b></a></li>\n";
	}
	if ($TMP_hayul2==true) {
		$TMP_contentproductos.="</li></ul>\n";
		$TMP_hayul2=false;
	}
///////// 

	return $TMP_contentproductos;
}



///////// Articulos
	if ($TMP_row[id]=="43" && $TMP_contentproductos!="") {
		$TMP_content.="<li><span class='entrada'>".$TMP_row["nombre"]."</span>";
		$TMP_content.="\n<ul>".$TMP_contentproductos."</ul>";
		$TMP_contentproductos="";
	} else if ($TMP_row[id]=="44" && $TMP_contentacciones!="") {
		$TMP_content.="<li><span class='entrada'>".$TMP_row["nombre"]."</span>";
		$TMP_content.="\n<ul>".$TMP_contentacciones."</ul>";
		$TMP_contentacciones="";
	} else {
		$TMP_content.="<li><a href=\"".$TMP_url."&V_mod=showart&artid=".$TMP_row[id]."\"><b>".$TMP_row["nombre"]."</b></a>";
	}
	if ($TMP_contentproyectos!="" && $TMP_rowx[id]=="2") { // proyectos
		if ($TMP_hayul==false) {
			$TMP_content.="<li><span class='entrada'>".$TMP_rowx["nombre"]."</span><ul>";
			$TMP_hayul=true;
		}
		$TMP_content.="\n".$TMP_contentproyectos;
	}
	if ($TMP_contentproductos!="" && $TMP_rowx[id]=="3") { // tienda
		if ($TMP_hayul==false) {
			$TMP_content.="<li><span class='entrada'>".$TMP_rowx["nombre"]."</span><ul>";
			$TMP_hayul=true;
		}
		$TMP_content.="\n<li><span class='entrada'>Tienda</span><ul>".$TMP_contentproductos."</ul></li>";
	}
	if ($TMP_contentacciones!="" && $TMP_rowx[id]=="3") { // acciones
		if ($TMP_hayul==false) {
			$TMP_content.="<li><span class='entrada'>".$TMP_rowx["nombre"]."</span><ul>";
			$TMP_hayul=true;
		}
		$TMP_content.="\n<li><span class='entrada'>Apoya con tu firma</span><ul>".$TMP_contentacciones."</ul></li>";
	}
	if ($TMP_contentte!="" && $TMP_rowx[id]=="3") { // testimonios
		if ($TMP_hayul==false) {
			$TMP_content.="<li><span class='entrada'>".$TMP_rowx["nombre"]."</span><ul>";
			$TMP_hayul=true;
		}
		$TMP_content.="\n<li><span class='entrada'>Testimonios</span><ul>".$TMP_contentte."</ul></li>";
	}
	if ($TMP_contentnoticias!="" && $TMP_rowx[id]=="6") { // noticias y demas
		if ($TMP_hayul==false) {
			$TMP_content.="<li><span class='entrada'>".$TMP_rowx["nombre"]."</span><ul>";
			$TMP_hayul=true;
		}
		$TMP_content.="\n".$TMP_contentnoticias;
	}
?>
