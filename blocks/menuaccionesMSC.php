<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");
// genera el menu lateral de Acciones

global $RAD_dbi, $content, $dbname, $idaccion, $term, $V_mod, $V_idmod, $idartnews, $idmodnews, $showonlylinkwithconts, $idcat, $search;
if ($dbname=="") $dbname=_DEF_dbname;
$content="";
$TMP_lang=getSessionVar("SESSION_lang");

$TMP_texto1=_DEF_NLSAcc1;
$TMP_texto2=_DEF_NLSAcc2;

if ($showonlylinkwithconts!="") { // muestra solo menus con noticias/actividades
   $A_contidaccion=array();
   $TMP_result = sql_query("SELECT * FROM contenidos WHERE idcat='$idcat'", $RAD_dbi);
   while($TMP_row = sql_fetch_array($TMP_result, $RAD_dbi)) {
        $A_contidaccion[$TMP_row[idaccion]]="x";
   }
}

$TMP_URLMENU="index.php?V_dir=MSC&amp;V_mod=showaccion";
$TMP_classnivel1=" class='nivel1'";
$TMP_classnivel1="";
$TMP_class="";
if ($V_mod=="shownews") {
	$TMP_URLMENU="index.php?V_dir=MSC&amp;V_idmod=$V_idmod&amp;V_mod=".$V_mod."&amp;V_submod=showaccion&amp;idartnews=$idartnews&amp;idmodnews=".$idmodnews;
	$TMP_classnivel1="";
}

$A_x=explode(".",basename(__FILE__));
$TMP_fich=$A_x[0];
$TMP_result=sql_query("SELECT * from bloques where fichero='$TMP_fich'", $RAD_dbi);
$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
if ($TMP_row["contenido_".$TMP_lang]!="") $TMP_row["contenido"]=$TMP_row["contenido_".$TMP_lang];
$content=trim($TMP_row[contenido]);

if (is_modulepermitted("", "MSC", "acciones")) $TMP_link=true;
else $TMP_link=false;
$TMP_linkedit="";

$TMP_accion="";

if ($idaccion>0) {
	$TMP_result=sql_query("SELECT * from acciones where idaccion='$idaccion'", $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
}

$TMP_Gfecha=RAD_gregorianDate(date("Y-m-d"));
	$TMP_enmarcha=""; $TMP_terminados=""; $TMP_estarama=false; $TMP_primerenmarcha=0; $TMP_primerterminado=0;
	$TMP_result2=sql_query("SELECT * from acciones order by fechainicio DESC", $RAD_dbi);
	while($TMP_row2=sql_fetch_array($TMP_result2, $RAD_dbi)) {
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
		if ($search!="") if (!eregi($search,html_entity_decode($TMP_row2[accion])) && !eregi($search,html_entity_decode($TMP_row2[resumen])) && !eregi($search,html_entity_decode($TMP_row2[descripcion]))) continue;
              
		if ($showonlylinkwithconts!="") {
			if ($A_contidaccion[$TMP_row2[idaccion]]=="") continue; // las acciones sin noticias/actividades no se muestran
		}
		$TMP_Gfechafin=RAD_gregorianDate($TMP_row2[fechafin]);
		if (!$TMP_Gfechafin>0) $TMP_activo="1";
		else if ($TMP_Gfechafin>$TMP_Gfecha) $TMP_activo="1";
		else $TMP_activo="0";
		if ($TMP_row2[idaccion]==$idaccion) $TMP_estarama=true;
		if ($TMP_row2["accion_".$TMP_lang]!="") $TMP_row2["accion"]=$TMP_row2["accion_".$TMP_lang];
		if ($TMP_activo=="1") {
			if (!$TMP_primerenmarcha>0) $TMP_primerenmarcha=$TMP_row2[idaccion];
			if ($TMP_row2[idaccion]==$idaccion) $TMP_accion="".$TMP_row2[accion];
			if ($TMP_row2[idaccion]==$idaccion) $TMP_enmarcha.="<li><span class='seccion'>".$TMP_row2[accion]."</span></li>";
			else $TMP_enmarcha.="<li".$TMP_class."><a href='".$TMP_URLMENU."&amp;idaccion=".$TMP_row2[idaccion]."'>".$TMP_row2[accion]."</a></li>";
		} else {
			if (!$TMP_primerterminado>0) $TMP_primerterminado=$TMP_row2[idaccion];
			if ($TMP_row2[idaccion]==$idaccion) $TMP_accion="".$TMP_row2[accion];
			if ($TMP_row2[idaccion]==$idaccion) $TMP_terminados.="<li><span class='seccion'>".$TMP_row2[accion]."</span></li>";
			else {
				if (!ereg("\?",$TMP_URLMENU)) $TMP_sepURLMENU="?";
				else $TMP_sepURLMENU="";
				$TMP_terminados.="<li".$TMP_class."><a href='".$TMP_URLMENU.$TMP_sepURLMENU."&term=x&amp;idaccion=".$TMP_row2[idaccion]."'>".$TMP_row2[accion]."</a></li>";
			}
		}
	}
	if ($TMP_terminados!="") $TMP_terminados="<ul>".$TMP_terminados."</ul>";
	if ($TMP_enmarcha!="") $TMP_enmarcha="<ul>".$TMP_enmarcha."</ul>";
if ($TMP_enmarcha!="") {
   if ($idaccion=="" && $term=="" && ($V_mod=="showaccion"||$V_submod=="showaccion")) $content.="<li".$TMP_classnivel1."><span class='seccion'>".$TMP_texto1."</span>";
   else $content.="<li".$TMP_classnivel1."><a href='".$TMP_URLMENU."&'>".$TMP_texto1."</a>";
   $content.="
		".$TMP_enmarcha."
	</li>";
}
if ($TMP_terminados!="") {
   if ($idaccion=="" && $term!="" && ($V_mod=="showaccion"||$V_submod=="showaccion")) $content.="<li".$TMP_classnivel1."><span class='seccion'>".$TMP_texto2."</span>";
   else {
	if (!ereg("\?",$TMP_URLMENU)) $TMP_sepURLMENU="?";
	else $TMP_sepURLMENU="";
	$content.="<li".$TMP_classnivel1."><a href='".$TMP_URLMENU.$TMP_sepURLMENU."&term=x'>".$TMP_texto2."</a>";
   }
   $content.="
		". $TMP_terminados."
	</li>
";	
}
if ($term=="") {
	if ($TMP_accion=="") $TMP_textoruta.=" <span>";
	$TMP_textoruta.=$TMP_texto1;
	if ($TMP_accion=="") $TMP_textoruta.=" </span>";
} else {
	if ($TMP_accion=="") $TMP_textoruta=" <span>";
	$TMP_textoruta.=$TMP_texto2;
	if ($TMP_accion=="") $TMP_textoruta.=" </span>";
}
if ($TMP_accion!="") $TMP_textoruta.=" &gt; <span>".$TMP_accion."</span>";
if ($V_mod=="showaccion") $content.="
<script>
document.getElementById('ruta').innerHTML+=' &gt; ".$TMP_textoruta."';
</script>
";

return $content;
?>
