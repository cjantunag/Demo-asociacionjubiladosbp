<?php
include_once("mainfile.php");
global $blocksoff, $SESSION_blocks_right, $SESSION_blocks_left, $col;
$blocksoff="x";
$SESSION_blocks_right=0;
$SESSION_blocks_left=0;

$col=$_REQUEST["col"]; // Numero de Columnas a mostrar
if (trim($col)=="") $col=3;

if ($_REQUEST['headeroff']=="") $headeroff="";
include_once("header.php");

echo "<script type='text/javascript'>
function RAD_parent(url) { 
  if (window.opener) top.opener.location=url; 
  else location=url; 
}
</script>
";

OpenTable();

if ($_REQUEST['headeroff']=="") {
    echo "<table align=center width=60%>";
}else{
    echo "<table width=100%>";
}

global $RAD_dbi, $SESSION_SID, $profile, $HTTP_SESSION_VARS;

$NUM_grupo=0;
////////////////////////////////////////////////////////////////////////////////////
if (RAD_existTable("articulossecciones") && $noarts=="") {
	global $artid;
	if (!is_user()) $TMP_CondPublico=" AND (publico!='0' OR publico IS NULL)";

	// cogemos las secciones
	if (is_admin()) $cmdsql = "select id,nombre,color from articulossecciones order by orden ASC";
	else $cmdsql = "select id,nombre,color from articulossecciones where (visible!='0' OR visible IS NULL) order by orden ASC";
	$result = sql_query($cmdsql, $RAD_dbi);
	$TMP_cont=0;
	while (list($id,$nombre,$color) = sql_fetch_row($result,$RAD_dbi)) {
		$TMP_grupo=$nombre;
		if ($TMP_GRUPOS[$TMP_grupo]=="") {
			$NUM_grupo++;
			$TMP_GRUPOS[$TMP_grupo]=$NUM_grupo;
		}

		if ($color!="") $nombreTit="<font color='$color'><b>$nombre</b></font>";
		else $nombreTit="$nombre";

		// se cogen los articulos de cada seccion
		if (is_admin()) $sql ="select id, nombre, observaciones from articulos where idseccion='$id' $TMP_CondPublico order by orden ASC";
		else $sql ="select id, nombre, observaciones from articulos where idseccion='$id' and (visible='1' OR visible IS NULL) $TMP_CondPublico order by orden ASC";
		$result2 = sql_query($sql, $RAD_dbi);
		$TMP_content_art="";
		$TMP_cont=0;
		while (list($art,$titulo, $observaciones) = sql_fetch_row($result2,$RAD_dbi)) {
			$URLArt="index.php?V_dir=contents&V_mod=articulos&artid=".$art.$SESSION_SID;
			$TMP_content_art.="&nbsp;<a class=blockcab href='$URLArt'>".$titulo."</a>\n";
			$TMP_ITEM[$TMP_grupo."_".$TMP_cont]=$titulo;
			$TMP_ITEMgrupo[$TMP_grupo."_".$TMP_cont]=$TMP_grupo;
			$TMP_ITEMlink[$TMP_grupo."_".$TMP_cont]=$URLArt;
			$TMP_ITEMobs[$TMP_grupo."_".$TMP_cont]=$observaciones;
			$TMP_ITEMidmod[$TMP_grupo."_".$TMP_cont]="";
			$TMP_cont++;
		}
		if ($TMP_cont==0)	{
			unset($TMP_GRUPOS[$TMP_grupo]);
			$NUM_grupo--;
		}
	}
	$TMP_contArt=$TMP_cont;
}
////////////////////////////////////////////////////////////////////////////////////
	$TMP_sql_default="( "._DBF_M_VISIBLE."!='0' OR "._DBF_M_VISIBLE." IS NULL)";

	$TMP_result = sql_query("select "._DBF_M_GROUPMENU." from "._DBT_MODULES." WHERE ("._DBF_M_ITEMMENU." IS NULL OR "._DBF_M_ITEMMENU."='') AND ("._DBF_M_GROUPMENU." IS NOT NULL OR "._DBF_M_GROUPMENU."!='') AND $TMP_sql_default ORDER BY "._DBF_M_WEIGHT, $RAD_dbi);
	$cont=0;
	while(list($TMP_grupo) = sql_fetch_row($TMP_result, $RAD_dbi)) {
		$cont++;
		$TMP_ordenGrupo[$cont]=$TMP_grupo;
	}

	$TMP_result = sql_query("select "._DBF_M_GROUPMENU.", "._DBF_M_ITEMMENU.","._DBF_M_LINK.", "._DBF_M_DIR.", "._DBF_M_FILE.", "._DBF_M_PARAMETERS.", "._DBF_M_PROFILES.", "._DBF_M_IDMODULE.", observaciones from "._DBT_MODULES." WHERE "._DBF_M_ACTIVE."='1' AND ("._DBF_M_GROUPMENU." IS NOT NULL OR "._DBF_M_GROUPMENU."!='') AND $TMP_sql_default ORDER BY "._DBF_M_WEIGHT, $RAD_dbi);
	list($TMP_grupo_sel, $TMP_literal_sel, $TMP_link,$TMP_dirblock,$TMP_fileblock,$TMP_parsblock,$TMP_profiles,$TMP_idmod_sel) = sql_fetch_row($TMP_result, $RAD_dbi);

	$TMP_result = sql_query("select "._DBF_M_GROUPMENU.", "._DBF_M_ITEMMENU.","._DBF_M_LINK.", "._DBF_M_DIR.", "._DBF_M_FILE.", "._DBF_M_PARAMETERS.", "._DBF_M_PROFILES.", "._DBF_M_IDMODULE.", "._DBF_M_PUBLIC.", observaciones from "._DBT_MODULES." WHERE "._DBF_M_ACTIVE."='1' AND ("._DBF_M_GROUPMENU." IS NOT NULL AND "._DBF_M_GROUPMENU."!='') AND $TMP_sql_default ORDER BY "._DBF_M_WEIGHT, $RAD_dbi);
	$TMP_cont=0;
	while (list($TMP_grupo, $TMP_literal, $TMP_linkmodule,$TMP_dirblock,$TMP_fileblock,$TMP_parsblock,$TMP_profiles,$TMP_idmod,$TMP_public,$TMP_observaciones) = sql_fetch_row($TMP_result, $RAD_dbi)) {
		if (isset(${$TMP_grupo})) $TMP_grupo=${$TMP_grupo};
		if (isset(${$TMP_literal})) $TMP_literal=${$TMP_literal};
		if (defined($TMP_grupo)) $TMP_grupo=constant($TMP_grupo);
		if (defined($TMP_literal)) $TMP_literal=constant($TMP_literal);

		if (!is_modulepermitted($TMP_idmod,$TMP_dirblock,$TMP_fileblock)) continue;

		$TMP_pars="";
		if ($TMP_fileblock!="") $TMP_pars.="&V_mod=".$TMP_fileblock;
		if ($TMP_parsblock!="") $TMP_pars.="&".$TMP_parsblock;

		if ($TMP_GRUPOS[$TMP_grupo]=="") {
			$NUM_grupo++;
			$TMP_GRUPOS[$TMP_grupo]=$NUM_grupo;
		}

		if ($TMP_literal=="") continue;
		$TMP_linkmodule=trim($TMP_linkmodule);
		if ($TMP_linkmodules=="" && $TMP_dirblock=="") continue;
		$TMP_linkmodule=str_replace("\"","'",$TMP_linkmodule);
		if ($TMP_linkmodule=="") $TMP_linkTab="index.php?".$SESSION_SID."V_dir=$TMP_dirblock&V_idmod=$TMP_idmod$TMP_pars";
		else $TMP_linkTab=$TMP_linkmodule.$SESSION_SID.$TMP_pars;

		$TMP_ITEM[$TMP_grupo."_".$TMP_cont]=$TMP_literal;
		$TMP_ITEMgrupo[$TMP_grupo."_".$TMP_cont]=$TMP_grupo;
		$TMP_ITEMlink[$TMP_grupo."_".$TMP_cont]=$TMP_linkTab;
		$TMP_ITEMobs[$TMP_grupo."_".$TMP_cont]=$TMP_observaciones;
		$TMP_ITEMidmod[$TMP_grupo."_".$TMP_cont]=$TMP_idmod;
		$TMP_cont++;
	}
////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////
	echo "\n\n</td>";
        $width=floor(100/$col); for ($ki=1; $ki<$col; $ki++) echo "<td class=borde width='".$width."%'></td>\n";
	echo "</tr>\n";

	$NUM_grupo=0;
	$numMenus=0;
	if ($TMP_cont>0 || $TMP_contArt>0) {
		reset($TMP_GRUPOS);
		asort($TMP_GRUPOS, SORT_NUMERIC);
		foreach($TMP_GRUPOS as $TMP_grupo=>$TMP_NUM_grupo) {
			$NUM_item=0;
			foreach($TMP_ITEMgrupo as $TMP_grupomascont=>$TMP_grupoitem) {
				if ($TMP_grupoitem==$TMP_grupo) {
					$NUM_item++;
					$TMP_literal=$TMP_ITEM[$TMP_grupomascont];
					$TMP_linkTab=$TMP_ITEMlink[$TMP_grupomascont];
					$TMP_idmod=$TMP_ITEMidmod[$TMP_grupomascont];
				}
			}
//xx			if ($NUM_item==1) {
//xx				$NUM_item=0;
//xx				unset($TMP_ITEMgrupo[$TMP_grupomascont]);
//xx			}
//xx			else $TMP_linkTab="";
			if ($NUM_item==0) continue;

			if ($NUM_grupo%$col==0) echo "<tr><td valign=top>";
			else echo "<td valign=top>";
			$NUM_grupo++;
			themesidebox("<b>".$TMP_grupo."</b><br /><ul>","");

			$NUM_item=0;
			foreach($TMP_ITEMgrupo as $TMP_grupomascont=>$TMP_grupoitem) {
				if ($TMP_grupoitem==$TMP_grupo) {
					$NUM_item++;
					$TMP_literal=$TMP_ITEM[$TMP_grupomascont];
					$TMP_linkTab=$TMP_ITEMlink[$TMP_grupomascont];
					if (trim($TMP_ITEMobs[$TMP_grupomascont])!="") $TMP_literal.="&nbsp;<img src='images/info.gif' alt='".$TMP_ITEMobs[$TMP_grupomascont]."' title='".$TMP_ITEMobs[$TMP_grupomascont]."' border=0>";
					$TMP_idmod=$TMP_ITEMidmod[$TMP_grupomascont];
					themesidebox("","<li><a class=blockitem href='javascript:RAD_parent(\"".$TMP_linkTab."\");window.close();'>".$TMP_literal."</a></li>\n");
				}
			}
			unset($TMP_GRUPOS[$TMP_grupo]);
			echo "</ul></td>";
			if ($NUM_grupo%$col==0) echo "</tr>";
		}
		if ($NUM_grupo%$col!=0) echo "</tr>";
		echo "<tr><td class=borde colspan=$col></tr>";
	}
echo "</table>";
CloseTable();
$footeroff="x";
include_once("footer.php");

?>
