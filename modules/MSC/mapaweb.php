<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

global $SESSION_SID, $RAD_dbi, $dbname, $PHP_SELF;
if ($dbname=="") $dbname=_DEF_dbname;

include_once("header.php");

echo "
<script>
document.getElementById('columna1').innerHTML='<h2><span>MAPA WEB</span></h2>';
document.getElementById('ruta').innerHTML='<span>Mapa WEB </span>';
</script>
<h1>MAPA WEB</h1>
<ul class='lista_ficha'>
 <li><a href='index.php'><span class='entrada'>Inicio</span></a> </li>
";
echo mapaweb();
echo '</ul>';

include_once("footer.php");
return;
//---------------------------------------------------------------------------------------------------------
function mapaweb() {
global $RAD_dbi, $SESSION_SID, $V_idmod, $V_dir, $V_mod, $dbname, $id;
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
	if ($URLArt=="") {
		$sql2="select * from articulos where idartparent='".$row[id]."' and visible='1' $TMP_CondPublico order by orden ASC";
		$result2=sql_query($sql2, $RAD_dbi);
		$row2=sql_fetch_array($result2,$RAD_dbi);
		foreach($row2 as $TMP_k=>$TMP_v) if ($row2[$TMP_k."_".$TMP_lang]!="") $row2[$TMP_k]=$row2[$TMP_k."_".$TMP_lang];
		$URLArt="index.php?V_dir=MSC&V_mod=showart&id=".$row2[id].$SESSION_SID;
		if ($row2[url_seo]!="") $URLArt=$row2[url_seo];
	}
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
	foreach($A_orden[$TMP_grupo] as $TMP_id=>$TMP_orden) {
		$TMP_link=$A_link[$TMP_id];
		if ($TMP_linkgrupo=="") $TMP_linkgrupo=$TMP_link;
	}
	$TMP_menuslat=showGrupoMapa($TMP_grupo,0);
	if (ereg("showproyecto", $TMP_linkgrupo)) $TMP_menuslat.="".menuProyectos();
	if ($TMP_menuslat!="") $TMP_menus.="	<li><a href='".$TMP_linkgrupo."'><span class='entrada'>".$TMP_grupo."</span></a>\n	<ul>".$TMP_menuslat."	</ul>\n	</li>\n";
	else $TMP_menus.="	<li><a href='".$TMP_linkgrupo."'><span class='entrada'>".$TMP_grupo."</span></a>\n	</li>\n";
}

return $TMP_menus;
}

//-----------------------------------------------------------------------------------------------
function showGrupoMapa($TMP_grupo, $TMP_level) {
	global $A_id, $A_orden, $A_item, $PHP_SELF, $A_link, $A_grupo;

	$TMP_menu="";
	$TMP_linkgrupo="";
	$TMP_contmenus=0;
	foreach($A_orden[$TMP_grupo] as $TMP_id=>$TMP_orden) {	
		$TMP_menuhijo="";
		$TMP_contmenus++;
		$TMP_item=$A_item[$TMP_id];
		if ($TMP_item=="&nbsp;") continue; // si el item es espacio no se muestra
		$TMP_link=$A_link[$TMP_id];
		if ($TMP_id=="A44") $TMP_link=$PHP_SELF."?V_dir=MSC&V_mod=showaccion&term=";
		if ($TMP_id=="M126") $TMP_link=$PHP_SELF."?V_dir=MSC&V_mod=showtest";
		if ($TMP_linkgrupo=="") $TMP_linkgrupo=$TMP_link;
		////if (!count($A_orden[$TMP_item])>0) { // No es un Grupo Padre-Hijo, solo es un Item
			if ($TMP_level==0) $TMP_menu.="\n	<li><a href='".$TMP_link."'>".$TMP_item."</a>";
			else $TMP_menu.="\n		<li><a href='".$TMP_link."'>".$TMP_item."</a>";
			if ($TMP_item!=$TMP_grupo) {
				if ($TMP_level<1) $TMP_menuhijo=showGrupoMapa($TMP_item, $TMP_level+1);
				if ($TMP_id=="A44") $TMP_menuhijo=include("blocks/menuaccionesMSC.php");
			}
			//if ($TMP_level>0) $TMP_classul=" class='sin_linea' style='margin-top:0px; margin-left:-8px;'"; 
			if ($TMP_level>0) $TMP_classul=" class='sin_linea'"; 
			else if ($TMP_contmenus==count($A_orden[$TMP_grupo])) $TMP_classul=" class='sin_linea'"; 
			$TMP_classul=""; 
			$TMP_classul=""; 
			if ($TMP_contmenus!=count($A_orden[$TMP_grupo]) || $TMP_menuhijo!="") {
				if (trim($TMP_menuhijo)!="") $TMP_menu.="\n		<ul".$TMP_classul.">\n".$TMP_menuhijo."		</ul>\n";
			}
			$TMP_menu.="		</li>\n";
		////}
	}
//	if ($TMP_contmenus==1) $TMP_menu="";
	return $TMP_menu;
}
//---------------------------------------------------------------------------------------------------------
function menuProyectos() {
global $SESSION_SID, $id;
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
$content=$TMP_row[contenido];

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
		if ($TMP_row2[categoria]!="" || $TMP_enmarcha!="") $content_ter.="		<li>".$TMP_row2[categoria].$TMP_terminados."</li>\n";
	}
}
if (trim($content_enm)!="") $content_enm="<ul>".$content_enm."</ul>";
if (trim($content_ter)!="") $content_ter="<ul>".$content_ter."</ul>";
$content.="
	<ul>
	<li".$TMP_classnivel1."><a href='".$TMP_URLMENU."&idproyectocategoria=".$TMP_primercatenm."'>".$TMP_texto1."</a>
		".$content_enm."
	</li>
	<li".$TMP_classnivel1."><a href='".$TMP_URLMENU."&term=x&idproyectocategoria=".$TMP_primercatter."'>".$TMP_texto2."</a>
		".$content_ter."
	</li>
	</ul>
";	

if ($TMP_categ=="") $TMP_categ=$A_lit[$TMP_idproyectocategoria];
if (!$idproyecto>0 && !$idproyectocategoria>0) $idproyectocategoria=$TMP_idproyectocategoria; // categoria por defecto (la primera)

return $content;
}
?>
