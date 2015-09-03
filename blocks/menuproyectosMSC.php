<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");
// genera el menu lateral de Proyectos

global $RAD_dbi, $content, $dbname, $idproyecto, $idproyectocategoria, $term, $V_mod, $V_idmod, $idmodnews, $showonlylinkwithconts, $idcat;
$content=""; $content_ter=""; $content_enm="";
if ($dbname=="") $dbname=_DEF_dbname;
$TMP_lang=getSessionVar("SESSION_lang");

$TMP_texto1=_DEF_NLSMapProy1;
$TMP_texto2=_DEF_NLSMapProy2;

if ($showonlylinkwithconts!="") { // muestra solo menus con noticias/actividades
   $A_contidproyecto=array();
   $TMP_result = sql_query("SELECT * FROM contenidos WHERE idcat='$idcat'", $RAD_dbi);
   while($TMP_row = sql_fetch_array($TMP_result, $RAD_dbi)) {
        $A_contidproyecto[$TMP_row[idproyecto]]="x";
   }
}

$TMP_URLMENU="index.php?V_dir=MSC&amp;V_mod=showproyecto";
$TMP_classnivel1=" class='nivel1'";
if ($V_mod=="shownews") {
	$TMP_URLMENU="index.php?V_dir=MSC&amp;V_idmod=$V_idmod&amp;V_mod=".$V_mod."&amp;idmodnews=".$idmodnews;
	$TMP_classnivel1="";
}

$A_x=explode(".",basename(__FILE__));
$TMP_fich=$A_x[0];
$TMP_result=sql_query("SELECT * from bloques where fichero='$TMP_fich'", $RAD_dbi);
$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
if ($TMP_row["contenido_".$TMP_lang]!="") $TMP_row["contenido"]=$TMP_row["contenido_".$TMP_lang];
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
	if ($TMP_row["categoria_".$TMP_lang]!="") $TMP_row["categoria"]=$TMP_row["categoria_".$TMP_lang];
	if ($TMP_row["url_seo_".$TMP_lang]!="") $TMP_row["url_seo"]=$TMP_row["url_seo_".$TMP_lang];
	$A_lit[$TMP_row[idproyectocategoria]]=$TMP_row[categoria];
	$TMP_enmarcha=""; $TMP_terminados=""; $TMP_estaramaenm=false; $TMP_estaramaterm=false; $TMP_primerenmarcha=0; $TMP_primerterminado=0;
	$TMP_result2=sql_query("SELECT * from proyectos where idproyectocategoria='".$TMP_row[idproyectocategoria]."' order by fechainicio DESC, proyecto ASC", $RAD_dbi);
	while($TMP_row2=sql_fetch_array($TMP_result2, $RAD_dbi)) {
		if ($showonlylinkwithconts!="") {
			if ($A_contidproyecto[$TMP_row2[idproyecto]]=="") continue; // los proyectos sin noticias/actividades no se muestran
		}
		$TMP_Gfechafin=RAD_gregorianDate($TMP_row2[fechafin]);
		if (!$TMP_Gfechafin>0) $TMP_activo="1";
		else if ($TMP_Gfechafin>$TMP_Gfecha) $TMP_activo="1";
		else $TMP_activo="0";
		if ($TMP_row2["proyecto_".$TMP_lang]!="") $TMP_row2["proyecto"]=$TMP_row2["proyecto_".$TMP_lang];
		if ($TMP_row2["url_seo_".$TMP_lang]!="") $TMP_row2["url_seo"]=$TMP_row2["url_seo_".$TMP_lang];
		$TMP_LINK=$TMP_URLMENU."&amp;idproyectocategoria=".$TMP_row2[idproyectocategoria]."&amp;idproyecto=".$TMP_row2[idproyecto];
		if ($TMP_row2["url_seo"]!="") $TMP_LINK=$TMP_row2["url_seo"];
		if ($TMP_activo=="1") {
			if ($TMP_row2[idproyecto]==$idproyecto) $TMP_estaramaenm=true;
			if ($term=="" && $TMP_row2[idproyectocategoria]==$idproyectocategoria) $TMP_estaramaenm=true;
			if (!$TMP_primerenmarcha>0) $TMP_primerenmarcha=$TMP_row2[idproyecto];
			if (!$TMP_primercatenm>0) {
				$TMP_primercatenm=$TMP_row2[idproyectocategoria];
				$TMP_linkprimercatenm=$TMP_row["url_seo"];
			}
			if ($TMP_row2[idproyecto]==$idproyecto) $TMP_proyecto=$TMP_row2[proyecto];
			if ($TMP_row2[idproyecto]==$idproyecto) $TMP_enmarcha.="<li class='seccion'>".$TMP_row2[proyecto]."</li>";
			else $TMP_enmarcha.="<li".$TMP_class."><a href='".$TMP_LINK."'>".$TMP_row2[proyecto]."</a></li>";
		} else {
			if ($TMP_row2[idproyecto]==$idproyecto) $TMP_estaramaterm=true;
			if ($term!="" && $TMP_row2[idproyectocategoria]==$idproyectocategoria) $TMP_estaramaterm=true;
			if (!$TMP_primerterminado>0) $TMP_primerterminado=$TMP_row2[idproyecto];
			if (!$TMP_primercatter>0) {
				$TMP_primercatter=$TMP_row2[idproyectocategoria];
				$TMP_linkprimercatter=$TMP_row["url_seo"];
			}
			if ($TMP_row2[idproyecto]==$idproyecto) $TMP_proyecto=$TMP_row2[proyecto];
			if ($TMP_row2[idproyecto]==$idproyecto) $TMP_terminados.="<li class='seccion'>".$TMP_row2[proyecto]."</li>";
			else {
				if (!ereg("\?",$TMP_LINK)) $TMP_LINK.="?";
				$TMP_terminados.="<li".$TMP_class."><a href='".$TMP_LINK."&term=x'>".$TMP_row2[proyecto]."</a></li>";
			}
		}
	}
	if (!$idproyecto>0 && !$idproyectocategoria>0) {
		$idproyectocategoria=$TMP_primercatenm;
		if (!$idproyectocategoria>0) $idproyectocategoria=$TMP_primercatenm;
		if ($idproyectocategoria>0 && $term=="") $TMP_estaramaenm=true;
		if ($idproyectocategoria>0 && $term!="") $TMP_estaramaterm=true;
	}
	if ($TMP_estaramaenm==false) {
		$TMP_enmarcha="";
	} else {
		if ($TMP_enmarcha!="") $TMP_enmarcha="<ul>".$TMP_enmarcha."</ul>";
	}
	if ($TMP_estaramaterm==false) {
		$TMP_terminados="";
	} else {
		if ($TMP_terminados!="") $TMP_terminados="<ul>".$TMP_terminados."</ul>";
	}
	if ($TMP_primerenmarcha>0) { // si hay algun proyecto se muestra la rama del arbol
		$TMP_LINK=$TMP_URLMENU."&amp;idproyectocategoria=".$TMP_row[idproyectocategoria];
		if ($TMP_row["url_seo"]!="") $TMP_LINK=$TMP_row["url_seo"];
		if ($TMP_row[idproyectocategoria]==$idproyectocategoria && $term=="") $TMP_categ=$TMP_row[categoria];
		//if ($TMP_row[idproyectocategoria]==$idproyectocategoria && $term=="") $content_enm.="		<li class='seccion'>".$TMP_row[categoria]."".$TMP_enmarcha."</li>\n";
		if ($TMP_row[idproyectocategoria]==$idproyectocategoria && $idproyecto=="" && $term=="") $content_enm.="		<li class='seccion'>".$TMP_row[categoria]."".$TMP_enmarcha."</li>\n";
		else $content_enm.="		<li><a href='".$TMP_LINK."'>".$TMP_row[categoria]."</a>".$TMP_enmarcha."</li>\n";
	} else {
		if ($TMP_enmarcha!="") $content_enm.="		<li>".$TMP_row2[categoria].$TMP_enmarcha."</li>\n";
	}
	if ($TMP_primerterminado>0) {
		if (!ereg("\?",$TMP_LINK)) $TMP_LINK.="?";
		$TMP_LINK=$TMP_URLMENU."&term=x&amp;idproyectocategoria=".$TMP_row[idproyectocategoria];
		if ($TMP_row["url_seo"]!="") $TMP_LINK=$TMP_row["url_seo"]."?term=x";
		if ($TMP_row[idproyectocategoria]==$idproyectocategoria && $term!="") $TMP_categ=$TMP_row[categoria];
		//if ($TMP_row[idproyectocategoria]==$idproyectocategoria && $term!="") $content_ter.="		<li class='seccion'>".$TMP_row[categoria]."".$TMP_terminados."</li>\n";
		if ($TMP_row[idproyectocategoria]==$idproyectocategoria && $idproyecto=="" && $term!="") $content_ter.="		<li class='seccion'>".$TMP_row[categoria]."".$TMP_terminados."</li>\n";
		else $content_ter.="		<li><a href='".$TMP_LINK."'>".$TMP_row[categoria]."</a>".$TMP_terminados."</li>\n";
	} else {
		if ($TMP_terminados!="") $content_ter.="		<li>".$TMP_row2[categoria]."-".$TMP_terminados."</li>\n";
	}
}
if ($content_enm!="") {
	$TMP_LINKCATENM=$TMP_URLMENU."&amp;idproyectocategoria=".$TMP_primercatenm;
	if ($TMP_linkprimercatenm!="") $TMP_LINKCATENM=$TMP_linkprimercatenm;
	$content_enm="
	<li".$TMP_classnivel1."><a href='".$TMP_LINKCATENM."'>".strtoupper($TMP_texto1)."</a>
                <ul>". $content_enm."
                </ul>
        </li>
";
}
if ($content_ter!="") {
	$TMP_LINKCATTER=$TMP_URLMENU."&amp;idproyectocategoria=".$TMP_primercatter;
	if ($TMP_linkprimercatter!="") $TMP_LINKCATTER=$TMP_linkprimercatter;
	$content_ter="
	<li".$TMP_classnivel1."><a href='".$TMP_LINKCATTER."'>".strtoupper($TMP_texto2)."</a>
                <ul>". $content_ter."
                </ul>
        </li>";
}
$content.="
	<div id='menu_secundario'>
	<ul>
	". $content_enm."
	". $content_ter."
	</ul>
	</div>
";	
if ($content_enm=="" && $content_ter=="") $content="";

if ($term=="") {
	$TMP_textoruta=$TMP_texto1;
} else {
	$TMP_textoruta=$TMP_texto2;
}
if ($TMP_categ=="") $TMP_categ=$A_lit[$TMP_idproyectocategoria];
if (!$idproyecto>0 && !$idproyectocategoria>0) $idproyectocategoria=$TMP_idproyectocategoria; // categoria por defecto (la primera)
if ($TMP_categ!="") {
	if ($TMP_proyecto!="") $TMP_textoruta.=" &gt; ".$TMP_categ;
	else $TMP_textoruta.=" &gt; <span>".$TMP_categ."</span>";
}
if ($TMP_proyecto!="") $TMP_textoruta.=" &gt; <span>".$TMP_proyecto."</span>";
if ($V_mod=="showproyecto") echo "
<script>
document.getElementById('ruta').innerHTML+='".$TMP_textoruta."';
</script>
";

return $content;
?>
