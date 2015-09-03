<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");
// Menu de enlaces a Noticias y Actividades por Temas

$content="";

global $showonlylinkwithconts, $RAD_dbi, $SESSION_SID, $idartnews, $V_dir, $V_mod, $V_idmod, $dbname, $idmodnews, $idcat, $idaccion, $idproyecto, $A_contidproyecto, $A_contidaccion, $A_contidarticulo, $A_contidmodulo;
if ($dbname!="" && $dbname!=_DEF_dbname) $xdbname="&dbname=$dbname";
$showonlylinkwithconts="x";

$TMP_idcat="";
if ($idcat!="") {
        $tmpdefaultfiltercat=" AND idcat='".$idcat."'";
        $TMP_result = sql_query("SELECT * FROM categorias WHERE id='$idcat'", $RAD_dbi);
        $TMP_row = sql_fetch_array($TMP_result, $RAD_dbi);
        $TMP_tipo=$TMP_row[literal];
} else {
        $TMP_result = sql_query("SELECT * FROM categorias WHERE categoria='news'", $RAD_dbi);
        while($TMP_row = sql_fetch_array($TMP_result, $RAD_dbi)) {
                $TMP_idcat.=",".$TMP_row["id"];
                if ($tmpdefaultfiltercat!="") $tmpdefaultfiltercat.=" OR ";
                $tmpdefaultfiltercat.="idcat='".$TMP_row["id"]."'";
                $TMP_tipo=$TMP_row[literal];
        }
        $TMP_idcat.=",";
        if ($tmpdefaultfiltercat!="") $tmpdefaultfiltercat=" AND (".$tmpdefaultfiltercat.")";
}
$result=sql_query("select * from articulos", $RAD_dbi);
while ($row=sql_fetch_array($result,$RAD_dbi)) {
	$A_idartpadre[$row[id]]=$row[idartparent];
}
if ($showonlylinkwithconts!="") { // muestra solo menus con noticias/actividades
   $A_contidproyecto=array(); $A_contidaccion=array(); $A_contidarticulo=array(); $A_contidmodulo=array();
   $cmdsql = "SELECT * FROM contenidos WHERE ".substr($tmpdefaultfiltercat,4);
   if ($idcat=="4") $cmdsql.="and (fechabaja is null or fechabaja='0' or fechabaja='' or fechabaja>'".time()."')";
   $TMP_result = sql_query($cmdsql, $RAD_dbi);
   while($TMP_row = sql_fetch_array($TMP_result, $RAD_dbi)) {
	if ($TMP_row[idarticulo]=="69") $TMP_row[idmodulo]="62"; // el modulo 62 de Noticias equivale al articulo 69
	if ($TMP_row[idarticulo]=="70") $TMP_row[idmodulo]="121"; // el modulo 121 de Actividades equivale al articulo 70
	$A_contidproyecto[$TMP_row[idproyecto]]="x";
	if ($TMP_row[idproyecto]>0) $A_contidmodulo["M116"]="x"; // los proyectos se muestran dentro del modulo 116
	$A_contidaccion[$TMP_row[idaccion]]="x";
	//if ($TMP_row[idaccion]>0) $A_contidarticulo["A48"]="x"; // los acciones de firma se se muestran dentro del articulo 48
	if ($TMP_row[idaccion]>0) $A_contidarticulo["A44"]="x"; // los acciones de firma se se muestran dentro del articulo 44
	$A_contidarticulo["A".$TMP_row[idarticulo]]="x";	
//	alert("A".$TMP_row[idarticulo]."*".$A_contidarticulo["A".$TMP_row[idarticulo]]);
	$TMP_idartpadre=$A_idartpadre[$TMP_row[idarticulo]];
	while($TMP_idartpadre>0) {
		if ($A_contidarticulo["A".$TMP_idartpadre]!="") break;
		$A_contidarticulo["A".$TMP_idartpadre]="x";	
//		alert("A".$TMP_idartpadre);
		$TMP_idartpadre=$A_idartpadre[$TMP_idartpadre];
	}
	$A_contidmodulo["M".$TMP_row[idmodulo]]="x";
   }
}

if (is_user() && _DEF_ADD_URLTIME=="1") $TMP_time="&time=".time();
else $TMP_time="";
if (!is_user()) $TMP_CondPublico=" AND publico!='0' ";
else $TMP_CondPublico="";

if ($newlang!="") $TMP_lang=$newlang;
else if ($HTTP_SESSION_VARS["SESSION_lang"]!="") $TMP_lang=$HTTP_SESSION_VARS["SESSION_lang"];

$URLNEWS=$PHP_SELF."?";
foreach($_REQUEST as $TMP_k=>$TMP_v) if ($TMP_k!="term" && $TMP_k!="idn" && $TMP_k!="idaccion" && $TMP_k!="idproyecto" && $TMP_k!="idproyectocategoria" && $TMP_k!="idmodnews" && $TMP_k!="idartnews" && substr($TMP_k,0,1)!="_" && substr($TMP_k,0,3)!="PHP" && $TMP_v!="" && $TMP_k!="pag" && $TMP_k!="id") $URLNEWS.=$TMP_k."=".$TMP_v."&";


$A_grupopadre=array(); 	// Lista de grupos padre iniciales de articulo o de modulos

$S_seccion=array(); 	// Lista de nombres de secciones para poner a los articulos (temporal)
$A_padreart=array();	// Articulo Padre si lo tiene (temporal)

global $A_id, $A_orden, $A_item, $A_link, $A_grupo, $A_padreseltiem, $A_selitem, $A_gruposelitem;
$A_orden=array(); 	// Lista de Orden de Items articulos y modulos dentro de un Grupo
$A_id=array(); 		// Lista de Id de Items articulos y modulos
$A_item=array(); 	// Lista de Items articulos y modulos
$A_link=array();	// Enlace del Item
$A_grupo=array();	// Lista de grupos
$A_selitem=array();	// Lista de Items seleccionados
$A_padreselitem=array();	// Lista de Items seleccionados
$A_gruposelitem=array();	// Lista de Grupos seleccionados

$selitemencontrado=""; 	// indicador de Item seleccionado ya encontrado
$selmodencontrado=""; 	// indicador de V_mod encontrado
////////////////////////////////////////////// ARTICULOS /////////////////////////////////////////////////////////
// Para modulo articulos el parametro id contiene el articulo mostrado
if ($idartnews!="") $TMP_artid=$idartnews;
else $TMP_artid=0;

// se leen las secciones o grupos de articulos
$cmdsql = "select * from articulossecciones where visible='1' order by orden ASC";
$result = sql_query($cmdsql, $RAD_dbi);
while ($row = sql_fetch_array($result,$RAD_dbi)) {
	if (trim($row["nombre_".$TMP_lang])!="") $row["nombre"]=$row["nombre_".$TMP_lang];
	if (trim($row[nombre])=="") continue;	// Seccion sin nombre
	$S_seccion[$row[id]]=$row[nombre];
	$A_grupopadre[$row[nombre]]=$row[orden]; 		// Grupo padre inicial
}

// se leen los articulos 
$sql="select * from articulos where visible='1' $TMP_CondPublico order by orden ASC";
$result=sql_query($sql, $RAD_dbi);
while ($row=sql_fetch_array($result,$RAD_dbi)) {
	if ($showonlylinkwithconts!="" && $A_contidarticulo["A".$row[id]]=="") continue; // se ocultan articulos son noticias/actividades
	if (trim($row["nombre_".$TMP_lang])!="") $row["nombre"]=$row["nombre_".$TMP_lang];
	if (trim($row["url_seo_".$TMP_lang])!="") $row["url_seo"]=$row["url_seo_".$TMP_lang];
	if ($row[id]=="49") $URLArt=$URLNEWS."idartnews=39";
	else $URLArt=$URLNEWS."idartnews=".$row[id];
	//if ($row[contenido]=="" && $row[url]=="") $URLArt=""; // articulo vacio
	//if ($row[url_seo]!="") $URLArt=$row[url_seo];
	if ($URLArt=="") {
		$sql2="select * from articulos where idartparent='".$row[id]."' and visible='1' $TMP_CondPublico order by orden ASC";
		$result2=sql_query($sql2, $RAD_dbi);
		$row2=sql_fetch_array($result2,$RAD_dbi);
		if (trim($row2["url_seo_".$TMP_lang])!="") $row2["url_seo"]=$row2["url_seo_".$TMP_lang];
		$URLArt=$URLNEWS."idartnews=".$row2[id];
		//if ($row2[url_seo]!="") $URLArt=$row2[url_seo];
	}
	$TMP_nombregrupo=$S_seccion[$row[idseccion]];
	$A_orden[$TMP_nombregrupo]["A".$row[id]]=$row[orden];	// Orden del Item dentro del Grupo
	$A_id[$row[nombre]]="A".$row[id];			// Id del Item
	$A_item["A".$row[id]]=$row[nombre];			// Nombre del Item
	$A_link["A".$row[id]]=$URLArt;				// Enlace del Item
	$A_grupo["A".$row[id]]=$TMP_nombregrupo;		// Grupo padre del Item
	if ($row[idartparent]>0) $A_padreart[$row[id]]=$row[idartparent];
	if ($row[id]==$TMP_artid) {
		$selitemencontrado="A".$row[id];
		//echo "Enconcontrado A".$row[id]."<br>";
		$A_selitem["A".$row[id]]="X";			// Item Seleccionado
	}
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
global $TMP_esproyecto, $TMP_esaccion;
$TMP_esproyecto=false; $TMP_esaccion=false;
$result = sql_query("select * from modulos where activo='1' and visible='1' ORDER BY orden", $RAD_dbi);
while($row=sql_fetch_array($result, $RAD_dbi)) {
	if ($idmodnews==$row[idmodulo] && $row[fichero]=="showproyecto") $TMP_esproyecto=true;
	//if ($idmodnews==$row[idmodulo] && $row[fichero]=="showaccion") $TMP_esaccion=true;
	if (!verifyProfile($row[perfiles],$row[publico])) continue;
	if (trim($row["grupomenu_".$TMP_lang])!="") $row["grupomenu"]=$row["grupomenu_".$TMP_lang];
	if (trim($row["literalmenu_".$TMP_lang])!="") $row["literalmenu"]=$row["literalmenu_".$TMP_lang];
	if (trim($row["url_seo".$TMP_lang])!="") $row["url_seo"]=$row["url_seo".$TMP_lang];
	if (trim($row[grupomenu])=="") continue;
	if (trim($row[literalmenu])=="") {
		$A_grupopadre[$row[grupomenu]]=$row[orden]; 		// Grupo padre inicial
		continue;
	}
	//echo "Mod=".$row[grupomenu]."*".$row[idmodulo]."*".$row[orden]."+".$row[literalmenu].".$URLArt<br>";
	$TMP_pars="";
	if ($row[fichero]!="") $TMP_pars.="&amp;V_mod=".$row[fichero];
//	if ($TMP_parsblock!="") $TMP_pars.="&".$row[parametros];
	$URLMod=$URLNEWS."idmodnews=".$row[idmodulo];
	//if ($row[url_seo]!="") $URLMod=$row[url_seo];
	if ($row[fichero]=="" && $row[url_seo]=="") $URLMod="";		// Es un Grupo Padre-Hijo
	$TMP_nombregrupo=$S_seccion[$row[idseccion]];
	$A_orden[$row[grupomenu]]["M".$row[idmodulo]]=$row[orden];	// Orden del Item dentro del Grupo
	$A_id[$row[literalmenu]]="M".$row[idmodulo];			// Id del Item
	$A_item["M".$row[idmodulo]]=$row[literalmenu];		// Nombre del Item
	$A_link["M".$row[idmodulo]]=$URLMod;				// Enlace del Item
	$A_grupo["M".$row[idmodulo]]=$row[grupomenu];			// Grupo padre del Item
	//if ($row[idmodulo]==$idmodnews && $selitemencontrado=="") { 	// Si ya se hubiese encontrado en articulos se ignora como modulos
	if ($row[idmodulo]==$idmodnews) { 	// Aunque se hubiese encontrado en articulos prevalece el modulo
		$selitemencontrado="M".$row[idmodulo];
		//echo "Enconcontrado IDMOD".$row[idmodulo]."<br>";
		$A_selitem["M".$row[idmodulo]]="X";			// Item Seleccionado
	}
	//if ($row[fichero]==$V_mod && $selmodencontrado=="") { 	// Si ya se hubiese encontrado en modulos
	//	$selmodencontrado="M".$row[idmodulo];
		//echo "Enconcontrado M".$row[idmodulo]."<br>";
	//	$A_selitemmod["M".$row[idmodulo]]="X";			// Mod Seleccionado
	//}
}
if (!count($A_grupopadre)>0) return "";

// si no hay selitemencontrado busca el de V_mod
if ($selitemencontrado=="" && $selmodencontrado!="") {
	$selitemencontrado=$selmodencontrado;
	$A_selitem[$selitemencontrado]="X"; 
}

// busca la rama o grupos padre del item seleccionado
$TMP_cont=0;
if ($selitemencontrado!="") {
	$TMP_selitem=$selitemencontrado;
	$TMP_ruta="<span>".$A_item[$TMP_selitem]."</span>";
	while($TMP_selitem!="") {
		$TMP_selitemold=$TMP_selitem;
		$TMP_grupo=$A_grupo[$TMP_selitem];
		//echo "Grupo de $TMP_selitem es $TMP_grupo con id ";
		$A_gruposelitem[$TMP_grupo]="x";
		$TMP_selitem=$A_id[$TMP_grupo];
		if ($A_link[$TMP_selitem]!="") $TMP_ruta="".$TMP_grupo." &gt; ".$TMP_ruta;
		//if ($A_link[$TMP_selitem]!="") $TMP_ruta="<a href='".$A_link[$TMP_selitem]."'>".$TMP_grupo."</a> &gt; ".$TMP_ruta;
		else $TMP_ruta=$TMP_grupo." &gt; ".$TMP_ruta;
		//echo " $TMP_selitem <br>";
		/////if ($TMP_selitem!="") $A_selitem[$TMP_selitem]="X"; 
		if ($TMP_selitem!="") $A_padreselitem[$TMP_selitem]="X";  // para que no se marque rama padre en rojo
		$TMP_cont++;
		if ($TMP_selitemold==$TMP_selitem) $TMP_selitem="";
		if ($TMP_cont>1000) break;
	}
	$TMP_ruta='	<div id="ruta">'.$TMP_ruta.'</div>';
}
//$TMP_ruta='	<div id="ruta"><a href="#">'.$selitemencontrado.'</a></div>';

// Recorre grupos padres y sus grupos/item hijos y muestra el arbol 
// (opcional:si solo hay un Item en el Grupo no se abre arbol, solo se muestra rama de grupos e items seleccionados
$TMP_menus=""; 
asort($A_grupopadre); // Ordena Grupos Padre
foreach($A_grupopadre as $TMP_grupo=>$TMP_orden) {
	$TMP_menus.=bshmshowCab($TMP_grupo);
}

$A_x=explode(".",basename(__FILE__));
$TMP_fich=$A_x[0];
$TMP_result=sql_query("SELECT * from bloques where fichero='$TMP_fich'", $RAD_dbi);
$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
if ($TMP_row["contenido_".$TMP_lang]!="") $TMP_row["contenido"]=$TMP_row["contenido_".$TMP_lang];
$content.=$TMP_row["contenido"]; // agrega contenido de la definicion del bloque

$TMP_txt=$TMP_tipo." ".$TMP_row[contenido];
$content='
        <div class="franja_gris">'.$TMP_txt.'</div>
        <div id="menu_secundario">
          <ul>
'.$TMP_menus.'
          </ul>
        </div>
';

return $content;

//-----------------------------------------------------------------------------------------------
function bshmshowCab($TMP_grupo) {
	global $A_id, $A_orden, $A_item, $A_link, $A_grupo, $A_gruposelitem, $TMP_esaccion, $TMP_esproyecto, $showonlylinkwithconts;

	$TMP_linkgrupo=""; $TMP_menug="";
	foreach($A_orden[$TMP_grupo] as $TMP_id=>$TMP_orden) {
		$TMP_link=$A_link[$TMP_id];
		if ($TMP_linkgrupo=="") $TMP_linkgrupo=$TMP_link;
	}
	if ($A_gruposelitem[$TMP_grupo]!="") {
		$TMP_menug.="	<li class='nivel1'><span class='upperc'>".$TMP_grupo."</span>\n";
	} else {
		$TMP_menug.="	<li class='nivel1'><a href='".$TMP_linkgrupo."'><span class='upperc'>".$TMP_grupo."</span></a>\n";
	}
	if ($A_gruposelitem[$TMP_grupo]!="") {
		$TMP_menusinf=bshmshowGrupo($TMP_grupo,0);
		if ($TMP_esproyecto==true) $TMP_menusinf.=include("blocks/menuproyectosMSC.php");
		//if ($TMP_esaccion==true) $TMP_menusinf.=include("blocks/menuaccionesMSC.php");
		$TMP_menug.="\n	<ul>".$TMP_menusinf."	</ul>\n";
	} else {
		$TMP_menusinf=bshmshowGrupo($TMP_grupo,0);
		if ($TMP_id=="M116") $TMP_menusinf.=include("blocks/menuproyectosMSC.php"); // los proyectos se muestran el modulo 116
		//if ($TMP_menusinf=="") $TMP_menug.="[oculta]"; // oculta menu principal si no tiene hijos que mostrar
		if ($showonlylinkwithconts!="" && $TMP_menusinf=="") $TMP_menug=""; // oculta menu principal si no tiene hijos que mostrar
	}
	$TMP_menug.="</li>\n";
	return $TMP_menug;
}
//-----------------------------------------------------------------------------------------------
function bshmshowGrupo($TMP_grupo, $TMP_level) {
	global $A_id, $A_orden, $A_item, $A_link, $A_grupo, $A_padreseltiem, $A_selitem, $A_gruposelitem, $A_contidarticulo, $A_contidmodulo, $showonlylinkwithconts;

	$TMP_menu="";
	$TMP_linkgrupo="";
	$TMP_contmenus=0;
	foreach($A_orden[$TMP_grupo] as $TMP_id=>$TMP_orden) {	
		//if ($A_contidarticulo[$TMP_id]=="" && $TMP_id!="A44" && $A_contidmodulo[$TMP_id]=="") $TMP_menu.=$A_contidarticulo[$TMP_id]."*".$TMP_id."*".$TMP_grupo."[vacio]<br>"; // se ocultan articulos que no tengan noticias/actividades
		if ($showonlylinkwithconts!="" && $A_contidarticulo[$TMP_id]=="" && $TMP_id!="A44" && $A_contidmodulo[$TMP_id]=="") continue; // se ocultan articulos que no tengan noticias/actividades
		$TMP_menuhijo="";
		$TMP_contmenus++;
		$TMP_item=$A_item[$TMP_id];
		if ($TMP_item=="&nbsp;") continue; // si el item es espacio no se muestra
		$TMP_link=$A_link[$TMP_id];
		if ($TMP_linkgrupo=="") $TMP_linkgrupo=$TMP_link;
		////if (!count($A_orden[$TMP_item])>0) { // No es un Grupo Padre-Hijo, solo es un Item
			if ($A_gruposelitem[$TMP_item]!="" && $TMP_level>0) $TMP_menu.="\n		<li><a href='".$TMP_link."'>".$TMP_item."</a>";
			else if ($A_padreselitem[$TMP_id]!="") $TMP_menu.="\n		<li><a href='".$TMP_link."'>".$TMP_item."</a>";
			else if ($A_selitem[$TMP_id]!="" && $TMP_level>0) $TMP_menu.="\n		<li class='seccion'>".$TMP_item."";
			else if ($A_selitem[$TMP_id]!="" && $TMP_level==0) $TMP_menu.="\n		<li class='seccion'>".$TMP_item."";
			else if ($TMP_level==0) $TMP_menu.="\n	<li><a href='".$TMP_link."'>".$TMP_item."</a>";
			else $TMP_menu.="\n		<li><a href='".$TMP_link."'>".$TMP_item."</a>";
			if ($TMP_item!=$TMP_grupo) {
				if ($TMP_level<1) $TMP_menuhijo=bshmshowGrupo($TMP_item, $TMP_level+1);
				else if ($A_selitem[$TMP_id]!="") $TMP_menuhijo=bshmshowGrupo($TMP_item, $TMP_level+1);
				if ($TMP_id=="A44") $TMP_menuhijo=include("blocks/menuaccionesMSC.php");
			}
			//if ($TMP_level>0) $TMP_classul=" class='sin_linea' style='margin-top:0px; margin-left:-8px;'"; 
			if ($TMP_level>0) $TMP_classul=" class='sin_linea'"; 
			else if ($TMP_contmenus==count($A_orden[$TMP_grupo])) $TMP_classul=" class='sin_linea'"; 
			else $TMP_classul=""; 
			$TMP_classul=""; 
			if ($TMP_contmenus!=count($A_orden[$TMP_grupo]) || $TMP_menuhijo!="") {
				if ($TMP_level==0 || $TMP_menuhijo!="") $TMP_menu.="\n		<ul".$TMP_classul.">\n".$TMP_menuhijo."		</ul>\n";
			}
			$TMP_menu.="		</li>\n";
			if ($TMP_id=="A44" && $TMP_menuhijo=="") $TMP_menu="";
		////}
	}
//	if ($TMP_contmenus==1) $TMP_menu="";
	return $TMP_menu;
}
?>
