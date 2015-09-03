<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

$content="";

global $RAD_dbi, $SESSION_SID, $V_idmod, $V_dir, $V_mod, $dbname, $id;
if ($dbname!="" && $dbname!=_DEF_dbname) $xdbname="&dbname=$dbname";

if (is_user() && _DEF_ADD_URLTIME=="1") $TMP_time="&time=".time();
else $TMP_time="";
if (!is_user()) $TMP_CondPublico=" AND publico!='0' ";
else $TMP_CondPublico="";

if ($newlang!="") $TMP_lang=$newlang;
else if ($HTTP_SESSION_VARS["SESSION_lang"]!="") $TMP_lang=$HTTP_SESSION_VARS["SESSION_lang"];

$A_grupopadre=array(); 	// Lista de grupos padre iniciales de articulo o de modulos

$S_seccion=array(); 	// Lista de nombres de secciones para poner a los articulos (temporal)
$A_padreart=array();	// Articulo Padre si lo tiene (temporal)

global $A_id, $A_orden, $A_item, $A_link, $A_grupo, $A_selitem, $A_gruposelitem;
$A_orden=array(); 	// Lista de Orden de Items articulos y modulos dentro de un Grupo
$A_id=array(); 		// Lista de Id de Items articulos y modulos
$A_item=array(); 	// Lista de Items articulos y modulos
$A_link=array();	// Enlace del Item
$A_grupo=array();	// Lista de grupos
$A_selitem=array();	// Lista de Items seleccionados
$A_gruposelitem=array();	// Lista de Grupos seleccionados

$selitemencontrado=""; 	// indicador de Item seleccionado ya encontrado
$selmodencontrado=""; 	// indicador de V_mod encontrado
////////////////////////////////////////////// ARTICULOS /////////////////////////////////////////////////////////
// Para modulo articulos el parametro id contiene el articulo mostrado
if ($id!="" && $V_mod=="articulos") $TMP_artid=$id;
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
$sql ="select * from articulos where visible='1' $TMP_CondPublico order by orden ASC";
$result = sql_query($sql, $RAD_dbi);
while ($row=sql_fetch_array($result,$RAD_dbi)) {
	if (trim($row["nombre_".$TMP_lang])!="") $row["nombre"]=$row["nombre_".$TMP_lang];
	if (trim($row["url_seo_".$TMP_lang])!="") $row["url_seo"]=$row["url_seo_".$TMP_lang];
	$URLArt="index.php?V_dir=contents&V_mod=articulos&id=".$row[id].$SESSION_SID;
	if ($row[url_seo]!="") $URLArt=$row[url_seo];
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
	//echo "Cambia $TMP_id del grupo $TMP_oldgrupo a $TMP_newgrupo <br>";
	$TMP_newgrupo=$A_item["A".$TMP_idpadre];
	if ($A_item["A".$TMP_idpadre]!="") {
		unset($A_orden[$TMP_oldgrupo]["A".$TMP_id]);
		$A_orden[$TMP_newgrupo]["A".$TMP_id]=$TMP_orden;
		$A_grupo["A".$TMP_id]=$A_item["A".$TMP_idpadre];
	}
}

////////////////////////////////////////////// MODULOS /////////////////////////////////////////////////////////
$result = sql_query("select * from modulos where activo='1' and visible='1' ORDER BY orden", $RAD_dbi);
while($row=sql_fetch_array($result, $RAD_dbi)) {
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
	if ($row[idmodulo]==$V_idmod && $selitemencontrado=="") { 	// Si ya se hubiese encontrado en articulos se ignora como modulos
		$selitemencontrado="M".$row[idmodulo];
		//echo "Enconcontrado IDMOD".$row[idmodulo]."<br>";
		$A_selitem["M".$row[idmodulo]]="X";			// Item Seleccionado
	}
	if ($row[fichero]==$V_mod && $selmodencontrado=="") { 	// Si ya se hubiese encontrado en modulos
		$selmodencontrado="M".$row[idmodulo];
		//echo "Enconcontrado M".$row[idmodulo]."<br>";
		$A_selitemmod["M".$row[idmodulo]]="X";			// Mod Seleccionado
	}
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
	while($TMP_selitem!="") {
		$TMP_grupo=$A_grupo[$TMP_selitem];
		//echo "Grupo de $TMP_selitem es $TMP_grupo con id ";
		$A_gruposelitem[$TMP_grupo]="x";
		$TMP_selitem=$A_id[$TMP_grupo];
		//echo " $TMP_selitem <br>";
		if ($TMP_selitem!="") $A_selitem[$TMP_selitem]="X"; 
		$TMP_cont++;
		if ($TMP_cont>1000) break;
	}
}

// Recorre grupos padres y sus grupos/item hijos y muestra el arbol 
// (opcional:si solo hay un Item en el Grupo no se abre arbol, solo se muestra rama de grupos e items seleccionados
asort($A_grupopadre); // Ordena Grupos Padre
$TMP_menuscab=""; $TMP_menuslat="";
foreach($A_grupopadre as $TMP_grupo=>$TMP_orden) {
	list($TMP_menuscabx,$TMP_menuslatx)=showGrupo($TMP_grupo);
	$TMP_menuscab.=$TMP_menuscabx; $TMP_menuslat.=$TMP_menuslatx;
//	unset($TMP_GRUPO_content[$TMP_grupo]);
}

return array($TMP_menuscab,$TMP_menuslat);

//-----------------------------------------------------------------------------------------------
function showGrupo($TMP_grupo) {
	global $A_id, $A_orden, $A_item, $A_link, $A_grupo, $A_selitem, $A_gruposelitem;

	$TMP_menu="";
	$TMP_linkgrupo="";
	$TMP_contmenus=0;
	foreach($A_orden[$TMP_grupo] as $TMP_id=>$TMP_orden) {	
		$TMP_contmenus++;
		$TMP_item=$A_item[$TMP_id];
		$TMP_link=$A_link[$TMP_id];
		if ($TMP_linkgrupo=="") $TMP_linkgrupo=$TMP_link;
		if (!count($A_orden[$TMP_item])>0) { // No es un Grupo Padre-Hijo, solo es un Item
			if ($A_selitem[$TMP_id]!="") $TMP_menu.="<li class='subseleccion'>".$TMP_item."</li>\n";
			else $TMP_menu.="<li><a href='".$TMP_link."'>".$TMP_item."</a></li>\n";
		}
	}
//	if ($TMP_menu=="") return "";
	if ($TMP_contmenus==1) $TMP_menu="";
	$TMP_idgrupo=$A_grupo[$TMP_grupo];
	if ($A_gruposelitem[$TMP_grupo]!="") {
		$TMP_menug.="\n<li class='seleccion'><a href='".$TMP_linkgrupo."'>".$TMP_grupo."</a></li>\n";
		//$TMP_menug.="<ul class='submenu'>".$TMP_menu."</ul>\n";
	} else if (eregi("colab",$TMP_grupo)) {
		$TMP_menug.="\n<li class='seleccion'><a href='".$TMP_linkgrupo."'>".$TMP_grupo."</a></li>\n";
		//$TMP_menug.="<ul class='submenu'>".$TMP_menu."</ul>\n";
		$TMP_menu="";
	} else {
		$TMP_menug.="\n<li><a href='".$TMP_linkgrupo."'>".$TMP_grupo."</a></li>\n";
		$TMP_menu="";
	}
	return array($TMP_menug,$TMP_menu);
}
//-----------------------------------------------------------------------------------------------
/*
          <li><a href="#">Quen somos</a></li>
          <li><a href="#">Que facemos</a></li>
          <li><a href="#">Inf&oacute;rmate</a></li>
          <li class="seleccion"><a href="#">Colabora</a></li>
          <li><a href="#">Transparencia</a></li>
          <li><a href="#">Contacta</a></li>
*/
?>
