<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");
// Muestra las familias y articulos de la tienda
// Recibe como parametros:
// - idarticulo: articulo a mostrar
// - idfam: familia a mostrar
// - maxItems: numero maximo de articulos a mostrar
// - numcolumns: si numcolumns es 1 muestra el articulo en forma de linea, en vez de en dos o mas columnas
 
    $MaxCharsItem=100;
    $maxItems=100;

    global $dbname, $RAD_dbi, $id, $V_dir, $idfam, $SESSION_SID, $SESSION_them, $showartdefaultfilter;

    global $query;
    $query=str_replace("'","\'",$query);

    $TMP_lang=getSessionVar("SESSION_lang");

    if (file_exists("modules/".$V_dir."/common.app.php")) include_once ("modules/".$V_dir."/common.app.php");
    if (_DEF_appname!="" && _DEF_appname!="_DEF_appname") if (file_exists("modules/$V_dir/common.app."._DEF_appname.".php")) include_once ("modules/$V_dir/common.app."._DEF_appname.".php");
    if (_DEF_appinstance!="" && _DEF_appinstance!="_DEF_appinstance") if (file_exists("modules/$V_dir/common.app."._DEF_appinstance.".php")) include_once ("modules/$V_dir/common.app."._DEF_appinstance.".php");

    include_once("header.php");

    echo "<div class='articulo'>";

    if($TMP_file=@opendir("themes/articulos".$idfam)) $SESSION_theme="articulos".$idfam;

    $showartdefaultfilter="(muestratienda IS NULL OR muestratienda!='0')";
    $showartdefaultfilter.=" AND (fechabaja='' OR fechabaja IS NULL OR fechabaja LIKE '0000%') ";
    if ($lastid>0) $showartdefaultfilter.=" AND idarticulo<$lastid";
    if ($query!="") $showartdefaultfilter.=" AND articulo LIKE '%".str_replace(' ','%',$query)."%'";

    if ($dbname=="") $dbname = _DEF_dbname;
    if (is_admin() || is_editor()) $defaultfilter="";
    //if ($defaultfilter!="") $showartdefaultfilter.=" AND ".$defaultfilter;

	$TMP="";
	if ($idfam=="" && $id>0) $idfam=RAD_lookup("GIE_articulos", "idfamilia", "idarticulo", $id);
	if ($idfam=="" && $id=="") { // Si solo hay una primer familia es la que presenta por defecto
		$TMP_result = sql_query("SELECT * FROM GIE_articulosfamilias WHERE (idfamiliapadre='' OR idfamiliapadre IS NULL OR idfamiliapadre='0') AND (muestratienda!='0' OR muestratienda IS NULL)", $RAD_dbi);
		$TMP_row = sql_fetch_array($TMP_result, $RAD_dbi);
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
		if (sql_num_rows($TMP_result, $RAD_dbi)==1) $idfam=$TMP_row[idfamilia];
	}

	$TMP_result = sql_query("SELECT * FROM GIE_articulosfamilias WHERE (idfamiliapadre='' OR idfamiliapadre IS NULL OR idfamiliapadre='0') AND (muestratienda!='0' OR muestratienda IS NULL) ORDER BY familia", $RAD_dbi);
	$TMP_row = sql_fetch_array($TMP_result, $RAD_dbi);
	foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
	if (sql_num_rows($TMP_result, $RAD_dbi)>1) {
		$TMP='<h1><a href="'.$PHP_SELF.'?V_dir='.$V_dir.'&V_mod='.$V_mod.'">'._DEF_NLSTienda.'</a> ';
		if ($idfam!="") $TMP.=' &gt; ';
		$TMP.=''.padreFamilia($idfam);
		if ($id!="") $TMP.=" &gt; ".RAD_lookup("GIE_articulos","articulo","idarticulo",$id)."";
		$TMP.="</h1>";
	}
	if ($TMP=="") $TMP='<h1><a href="'.$PHP_SELF.'?V_dir='.$V_dir.'&V_mod='.$V_mod.'">'._DEF_NLSTienda.'</a> ';
	echo $TMP;

// ---- muestra datos	
	$TMP_familia="";
	if ($idfam!="") {
		if ($TMP_familia=="") {
			if (ereg(",",$idfam)) {
				$A_idfam=explode(",",$idfam);
				$TMP_idfam="";
				for($ki=0; $ki<count($A_idfam); $ki++) {
					if ($A_idfam[$ki]=="") continue;
					if ($TMP_idfam!="") $TMP_idfam.=" OR ";
					$TMP_idfam.="idfamilia='".trim($A_idfam[$ki])."' OR idfamilia LIKE '%,".trim($A_idfam[$ki]).",%'";
				}
				$TMP_result = sql_query("SELECT * FROM GIE_articulosfamilias WHERE (".$TMP_idfam.") AND (muestratienda!='0' OR muestratienda IS NULL)", $RAD_dbi);
				while($TMP_row = sql_fetch_array($TMP_result, $RAD_dbi)) {
					foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
					if ($TMP_familia!="") $TMP_familia.=", ";
					$TMP_familia.=$TMP_row[familia];
				}
			} else {
				$TMP_result = sql_query("SELECT * FROM GIE_articulosfamilias WHERE (idfamilia='$idfam' OR idfamilia LIKE '%,$idfam,%') AND (muestratienda!='0' OR muestratienda IS NULL)", $RAD_dbi);
				$TMP_row = sql_fetch_array($TMP_result, $RAD_dbi);
				foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
				$TMP_familia.=$TMP_row[familia];
			}

		}
	}
	OpenTable();
	if ($query!="") {
		$TMP_content="<br />Criterio de b&uacute;squeda: <b>".htmlentities($query)."</b><br />"; // muestra articulos segun filtro showartdefaultfilter
		$TMP_content.=showArticulos(); // muestra articulos segun filtro showartdefaultfilter
		echo $TMP_content;
	} else if ($id=="") {
		$TMP_content=showFamilia($idfam);
		echo $TMP_content;
	} else {
		if ($id>0) $idfam=RAD_lookup("GIE_articulos", "idfamilia", "idarticulo", $id);
		if ($cmd!="print" && $idfam>0) $TMP_content=showFamilia($idfam);
                if ($idfam>0 || $id>0) $TMP_content.=showArticulo($id);
		echo $TMP_content;
	}
	CloseTable();
	if ($cmd=="print") echo "\n<script>\nwindow.print();\n</script>\n";

	echo "</div>";

	include_once ("footer.php");

//----------------------------------------------------------------------------------------
function showFamilia($idfam,$id="") {
global $SESSION_SID, $PHP_SELF, $V_dir, $V_mod, $RAD_dbi, $headeroff, $footeroff, $menuoff, $id, $dbname, $nocesta, $showartdefaultfilter;

	$TMP_lang=getSessionVar("SESSION_lang");

        for($ki=0; $ki<count($TMP_A); $ki++) if (trim($TMP_A[$ki])!="") $idfam=$TMP_A[$ki];
	$TMP="";

	if ($idfam=="") {
		$TMP_res = sql_query("SELECT * FROM GIE_articulosfamilias WHERE (idfamiliapadre='' OR idfamiliapadre IS NULL OR idfamiliapadre='0') AND (muestratienda!='0' OR muestratienda IS NULL) ORDER BY orden", $RAD_dbi);
	} else {
		$TMP_res = sql_query("SELECT * FROM GIE_articulosfamilias WHERE idfamilia='$idfam'", $RAD_dbi);
		$TMP_rowp = sql_fetch_array($TMP_res, $RAD_dbi);
		foreach($TMP_rowp as $TMP_k=>$TMP_v) if ($TMP_rowp[$TMP_k."_".$TMP_lang]!="") $TMP_rowp[$TMP_k]=$TMP_rowp[$TMP_k."_".$TMP_lang];
		if ($TMP_rowp[observaciones]) $TMP.=$TMP_rowp[observaciones];
		$TMP_res = sql_query("SELECT * FROM GIE_articulosfamilias WHERE idfamiliapadre='$idfam' AND (muestratienda!='0' OR muestratienda IS NULL) ORDER BY orden", $RAD_dbi);
	}
	$TMP_cont=0;
	$TMP.='<table style="width: 100%; margin-top:10px; margin-left:0px;">';
	while($TMP_row = sql_fetch_array($TMP_res, $RAD_dbi)) {	
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
        	if ($TMP_cont%2==0) $TMP.="<tr>";
		$TMP_cont++;
		if ($TMP_row[url_seo]=="") $TMP_row[url_seo]=$TMP_row[familia];		
		$TMP_row[url_seo]=str_replace(" ","-",$TMP_row[url_seo]);
		$TMP_row[url_seo]=limpiar_url($TMP_row[url_seo]);
		$TMP_icono=RAD_primerFich($TMP_row[icono]);
		$TMP_sql2 = "SELECT count(*) FROM GIE_articulos WHERE idfamilia='".$TMP_row[idfamilia]."' AND (muestratienda!='0' OR muestratienda IS NULL)";
		if ($showartdefaultfilter!="") $TMP_sql2.=" AND ".$showartdefaultfilter;
		$TMP_res2 = sql_query($TMP_sql2, $RAD_dbi);
		$TMP_row2 = sql_fetch_array($TMP_res2, $RAD_dbi);
		$TMP.='<td align=left>
			<a href="'.linkFamArt($TMP_row[idfamilia],"",$TMP_row[url_seo]).'">
			<img alt="'.$TMP_row["familia"].'" src="/files/'.$dbname.'/'.$TMP_icono.'" style="vertical-align: middle;" />
			<h3>'.$TMP_row[familia].'';
		if ($TMP_row2[0]>0) $TMP.=" [".$TMP_row2[0]."]";
		$TMP.="</h3></a><br>";
        	$TMP.="</td>";
        	if ($TMP_cont>0 && $TMP_cont%2==0) $TMP.="</tr>";
	}
        if ($TMP_cont>0 && $TMP_cont%2!=0) $TMP.="</tr>";
	if ($TMP_cont==0) $TMP.="<tr><td> </td></tr>";
        $TMP.="</table>";

	if ($id!="") return $TMP;

	if ($idfam>0) $TMP.=showArticulos();
	return $TMP;
}
//----------------------------------------------------------------------------------------
function showArticulos() {
global $SESSION_SID, $PHP_SELF, $dbname, $V_dir, $V_mod, $RAD_dbi, $headeroff, $footeroff, $menuoff, $SESSION_SID, $nocesta, $PHPSESSID, $showartdefaultfilter, $idfam, $HTTP_SESSION_VARS;

	$TMP_lang=getSessionVar("SESSION_lang");

	$TMP_dirtheme=$HTTP_SESSION_VARS[SESSION_theme];
	$TMP2="";
	if ($idfam!="") {
	    $TMP_sql = "SELECT * FROM GIE_articulos WHERE idfamilia='".$idfam."' AND (muestratienda!='0' OR muestratienda IS NULL)";
	} else {
	    $TMP_sql = "SELECT * FROM GIE_articulos WHERE ".$showartdefaultfilter;
	}
	$TMP_res = sql_query($TMP_sql." ORDER BY articulo DESC", $RAD_dbi);
	$TMP_cont=0;
	while($TMP_row = sql_fetch_array($TMP_res, $RAD_dbi)) {  
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
		if($TMP_cont==0) {
			$TMP2.="<table class=\"browse\">";
			$TMP2.="<tr><th class=browse> "._DEF_NLSCestaFoto." </th><th class=browse> "._DEF_NLSCestaArt."</th><th class=browse> "._DEF_NLSCestaPrecio."</th><th class=browse> "._DEF_NLSCestaComprar." </th></tr>";
		}  
		if ($showonlyparents!="") if ($TMP_row[idarticulopadre]>0) continue;
		$TMP_cont++;
		if ($TMP_class=="class=row1") $TMP_class="class=row2";
		else $TMP_class="class=row1";
		if ($TMP_row[totalventa] > 0) //Solo se puede actualizar el precio de venta a oferta si el de venta es mayor que 0.
		if ($TMP_row[preciooferta]>0) $TMP_row[totalventa]=$TMP_row[preciooferta];
		if ($TMP_row[url_seo]=="") $TMP_row[url_seo]=$TMP_row[articulo];
		$TMP_row[url_seo]=str_replace(" ","-",$TMP_row[url_seo]);
		$TMP_row[url_seo]=limpiar_url($TMP_row[url_seo]);
		$TMP_URL="<a href=\"".linkFamArt("",$TMP_row[idarticulo],$TMP_row[url_seo])."\">";
	        $TMP_URL2="<a href=\"".linkFamArt("",$TMP_row[idarticulo],$TMP_row[url_seo])."\">";
		$TMP2.="<tr><td ".$TMP_class.">".$TMP_URL."";
		$TMP_img=RAD_primerFich($TMP_row[icono]);
		if ($TMP_img!="") if (file_exists("files/".$dbname."/".$TMP_img)) $TMP_imgmini="files/".$dbname."/x".$TMP_img;
		if ($TMP_imgmini!="") if (!file_exists($TMP_imgmini)) $TMP_imgmini=RAD_resizeImg("files/".$dbname."/".$TMP_img,100,100);
		if ($TMP_img!="") $TMP2.="<img alt=\"".$TMP_row[articulo]."\" src='/$TMP_imgmini' style=\"border: 0px;\"/> ";
		//Recoger por separado el titulo del articulo y su descripcion
		if (strpos($TMP_row[articulo],'(')!=false){
			$articulo=substr($TMP_row[articulo],0,strpos($TMP_row[articulo],'('));		
			$descripcion=substr($TMP_row[articulo],strpos($TMP_row[articulo],'('));
		} else{
			$articulo=$TMP_row[articulo];
			$descripcion="";
		}		
		$TMP2.="</a></td><td ".$TMP_class.">".$TMP_URL.$articulo.'<br />'.$descripcion."</a></td>";
		if ($TMP_row[muestratienda]=="2") $TMP2.="<td ".$TMP_class.">".$TMP_URL2." A Consultar</a></td>";
		if (trim($TMP_row[cantidadminima])=="") $TMP_row[cantidadminima]="1";
		if ($TMP_row[totalventa]>0)	$TMP2.="<td nowrap ".$TMP_class."> ".$TMP_URL2.RAD_numero($TMP_row[totalventa],"2")." &euro; ("._DEF_NLSCestaIVAInc.")</a></td>";
		else $TMP2.="<td ".$TMP_class."> ".$TMP_URL2."No disponible</a></td>";
		if (($nocesta=="")&&($TMP_row[totalventa]>0)) $TMP2.="<td ".$TMP_class."><br><div style=\"text-align: center;\">
<input name='save' type='button' onclick='javascript:document.location.href=\"$PHP_SELF?$SESSION_SID&amp;V_dir=$V_dir&amp;V_mod=cesta&amp;op=add&amp;V0_ud=1&amp;id=".trim($TMP_row[idarticulo])."\";' class='boton' id='button' value='".strtoupper(_DEF_NLSCestaComprar)."'></div>";
		//if (($nocesta=="")&&($TMP_row[totalventa]>0)) $TMP2.="<td ".$TMP_class."><br><div style=\"text-align: right;\"><a href='$PHP_SELF?$SESSION_SID&amp;V_dir=$V_dir&amp;V_mod=cesta&amp;op=add&amp;V0_ud=1&amp;id=".trim($TMP_row[idarticulo])."'><img src=\"/themes/$TMP_dirtheme/ico_cesta.png\" alt=\"cesta\" /></a></div>";
		else $TMP2.="<td ".$TMP_class.">";
		if (is_admin()) {
			$TMP2.="<a href=\"javascript:RAD_OpenW('"._DEF_INDEX."?".$SESSION_SID."V_dir=$V_dir&amp;V_mod=articulos&amp;func=edit&amp;subfunc=browse&amp;menuoff=&amp;par0=".$TMP_row[idarticulo]."&amp;idfam=".$idfam."&amp;headeroff=x&amp;footeroff=x',640,400);\"> <img border=0 src='/images/edit.gif' />Modificar</a>";
		}
		$TMP2.="</td></tr>";
	}
	if($TMP_cont>0) $TMP2.="</table>";
	if ($TMP_cont==0 && $idfam!="") {
		////$TMP2.="No existen art&iacute;culos de este tipo";
	}
	return $TMP2;
}
//----------------------------------------------------------------------------------------
function padreFamilia($TMP_idfam) {
global $SESSION_SID, $PHP_SELF, $idfam, $V_dir, $V_mod, $RAD_dbi, $headeroff, $footeroff, $menuoff, $id;
    $TMP="";

    $TMP_lang=getSessionVar("SESSION_lang");

    if ($TMP_idfam!="") {
	$TMP_res = sql_query("SELECT * FROM GIE_articulosfamilias WHERE idfamilia='$TMP_idfam' AND (muestratienda!='0' OR muestratienda IS NULL) ORDER BY familia", $RAD_dbi);
	$TMP_row = sql_fetch_array($TMP_res, $RAD_dbi);
	foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
	if (!$TMP_row[idfamilia]>0) return "";
	if ($idfam!=$TMP_idfam || $id!="") {
	    if ($TMP_row[url_seo]=="") $TMP_row[url_seo]=$TMP_row[familia];
		$TMP_row[url_seo]=str_replace(" ","-",$TMP_row[url_seo]);
		$TMP_row[url_seo]=limpiar_url($TMP_row[url_seo]);
		$TMP.='<a href="'.linkFamArt($TMP_row[idfamilia],"",$TMP_row[url_seo]).'">'.$TMP_row[familia].'</a>';
	}else{
		$TMP.="".$TMP_row[familia]."";
	}
	$idfampadre=$TMP_row[idfamiliapadre];
	if ($idfampadre=="") {
            return $TMP;
	}else {
		$TMP2=padreFamilia($idfampadre);
		if (trim($TMP)!="" && trim($TMP2)!="") $TMP=$TMP2." &gt; ".$TMP;
		else if (trim($TMP2)!="") $TMP=$TMP2;
	}
    }
    return $TMP;
}
//----------------------------------------------------------------------------------------
function showArticulo($id) {
global $SESSION_SID, $PHP_SELF, $dbname, $V_dir, $V_mod, $RAD_dbi, $headeroff, $footeroff, $menuoff, $SESSION_SID, $nocesta, $PHPSESSID, $showartdefaultfilter, $HTTP_SESSION_VARS;

	$TMP_lang=getSessionVar("SESSION_lang");

	$current_url = "http".((!empty($_SERVER['HTTPS'])) ? "s" : "")."://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	if ($dbname=="") $dbname=_DEF_dbname;

	$TMP_dirtheme=$HTTP_SESSION_VARS[SESSION_theme];

	$TMP="";

	$TMP_sql = "SELECT * FROM GIE_articulos wheRE idarticulo='$id' ORDER BY articulo";
	if ($showartdefaultfilter!="") $TMP_sql.=" AND ".$showartdefaultfilter;
	$TMP_result = sql_query($TMP_sql, $RAD_dbi);
	$TMP_row = sql_fetch_array($TMP_result, $RAD_dbi);
	foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
	if (!$TMP_row[idarticulo]>0) return;
	if ($TMP_row[muestratienda]=="0") return;

	if (isset($TMP_row["clicks"])) {
		$clicks=$TMP_row[clicks];
		$nuevaVisita=true;
		$ahora=time();
		$A_clicks=explode("\n",$clicks);
		if (count($A_clicks)>0) {
			for ($ki=0; $ki<count($A_clicks); $ki++) {
				$TMP_Atoken=explode(" ",$A_clicks[$ki]);
				if (count($TMP_Atoken)>0) if ($TMP_Atoken[0]==$PHPSESSID && ($TMP_Atoken[1]+3600)>$ahora) { $nuevaVisita=false; $ki=count($A_clicks); } // si en menos de una hora a pulsado el mismo no es visita
			}
		}
		if ($nuevaVisita==true) {
			$TMP_IP=getenv("REMOTE_ADDR");
			$TMP_CLIENT_IP=getenv("HTTP_CLIENT_IP");
			if ($TMP_CLIENT_IP!="") $TMP_IP=$TMP_CLIENT_IP."/".$TMP_IP;
			if ($clicks!="") $clicks.="\n";
			$clicks.=$PHPSESSID." ".$ahora." ".$TMP_IP." ".date("Y-m-d H:i:s")." ".getenv("HTTP_REFERER");
			$TMP_result = sql_query("UPDATE GIE_articulos SET clicks='$clicks' WHERE idarticulo='$id'", $RAD_dbi);
		}
	}

	$idfamiliapadre=$TMP_row[idfamilia];
	if (substr($idfamiliapadre,0,1)==",") $idfamiliapadre=substr($idfamiliapadre,1); // elimina las comas de un multicampo
	if (substr($idfamiliapadre,strlen($idfamiliapadre)-1)==",") $idfamiliapadre=substr($idfamiliapadre,0,strlen($idfamiliapadre)-1);
	while ($idfamiliapadre>0) {
		$TMP_sql2 = "SELECT * FROM GIE_articulosfamilias WHERE idfamilia='$idfamiliapadre' AND (muestratienda!='0' OR muestratienda IS NULL) ORDER BY familia";
		$TMP_result2 = sql_query($TMP_sql2, $RAD_dbi);
		$TMP_row2 = sql_fetch_array($TMP_result2, $RAD_dbi);
		foreach($TMP_row2 as $TMP_k=>$TMP_v) if ($TMP_row2[$TMP_k."_".$TMP_lang]!="") $TMP_row2[$TMP_k]=$TMP_row2[$TMP_k."_".$TMP_lang];
		$TMP_Afamilias=explode(" ",$TMP_row2[familia]." * ");
		$TMP_familia=strtolower($TMP_Afamilias[0]);
		$idfamiliapadre=$TMP_row2[idfamiliapadre];
		if (substr($idfamiliapadre,0,1)==",") $idfamiliapadre=substr($idfamiliapadre,1);
		if (substr($idfamiliapadre,strlen($idfamiliapadre)-1)==",") $idfamiliapadre=substr($idfamiliapadre,0,strlen($idfamiliapadre)-1);
	}

        $TMP_FAM=RAD_lookup("GIE_articulosfamilias", "familia", "idfamilia", $TMP_row[idfamilia]);
        $TMP_FAMPADRE=RAD_lookup("GIE_articulosfamilias", "idfamiliapadre", "idfamilia", $TMP_row[idfamilia]);
        $TMP_LITFAMPADRE=RAD_lookup("GIE_articulosfamilias", "familia", "idfamilia", $TMP_FAMPADRE);

	if (is_admin()) {
		$TMP_linkAdm="<br /><br /> &nbsp; <a href=\"javascript:RAD_OpenW('"._DEF_INDEX."?".$SESSION_SID."V_dir=$V_dir&amp;V_mod=articulos&amp;func=edit&amp;subfunc=browse&amp;menuoff=&amp;par0=".$TMP_row[idarticulo]."&amp;idfam=".$idfam."&amp;headeroff=x&amp;footeroff=x',640,400);\"><img border=0 src='/images/edit.gif' alt='Editar' />Modificar</a>";
	} else {
		$TMP_linkAdm="";
	}

        $TMP.='<h3>'.$TMP_row[articulo]."</h3>";
        $TMP.='<table cellspacing="0" cellpadding="0" border="0" width="480">
                <tr>
                    <td align="left" width="200" valign="top">
                            <table cellspacing="0" cellpadding="0" border="0" width="200">
                                <tr>';
        $foto=RAD_urlencodeFich(RAD_primerFich($TMP_row[foto]));
        if ($foto=="") $foto=RAD_urlencodeFich(RAD_primerFich($TMP_row[icono]));
        $TMP.='<td><a href="/files/'.$dbname.'/'.$foto.'" rel="lightbox-"><img border="0" title="'.$TMP_row[articulo].'" alt="'.$TMP_row[articulo].'" src="/files/'.$dbname.'/'.$foto.'" /></a></td>
                                </tr>
                                <tr>
                                    <td>';
		if ($TMP_row[totalventa]>0) {
			if ($TMP_row[preciooferta]>0) $TMP.='<span>'._DEF_NLSCestaPrecioPVP.':&nbsp;&nbsp;&nbsp;'.RAD_numero($TMP_row[totalventa],"2").' &euro;</span>
            <br /><span>'._DEF_NLSCestaPrecioOferta.': '.RAD_numero($TMP_row[preciooferta],"2").' &euro;</span>';
			else $TMP.='<span>'._DEF_NLSCestaPrecioPVP.':&nbsp;&nbsp;&nbsp;'.RAD_numero($TMP_row[totalventa],"2").' &euro;</span>';
			if ($TMP_descuento!=0) $TMP.='<br><span>Dto. '.RAD_numero($TMP_descuento).' %</span>';
		} else $TMP.='<span>'._DEF_NLSCestaNoDisponible.'</span>';
		
	if ($TMP_row[unidad]!=""){
		$TMP_sql_uni = "SELECT descripcion FROM GIE_unidades WHERE unidad='$TMP_row[unidad]'";
		$TMP_result_uni = sql_query($TMP_sql_uni, $RAD_dbi);
		$TMP_row_uni = sql_fetch_array($TMP_result_uni, $RAD_dbi);
		$unidad_consumo=$TMP_row_uni["descripcion"];
	} 
	if ($TMP_row[unidad]!="") $TMP.='<br /><span>El precio indicado es por '.$unidad_consumo.'</span>';
	if (trim($TMP_row[cantidadminima])=="") $TMP_row[cantidadminima]="1";
	if ($TMP_row[cantidadminima]!=1) {
		$TMP.='<br /><span>Cantidad m&iacute;nima de pedido '.$TMP_row[cantidadminima];
		if ($TMP_row[unidad]!="") $TMP.=' '.$unidad_consumo;
		$TMP.='</span>';
	}
	$TMP.='<br /><span>'._DEF_NLSCestaPrecioConIVA.'</span></span>';
	$TMP.='</td></tr>';
	if ($TMP_row[totalventa]>0) $TMP.="<tr>
			<td align='right'>
<br>
<input name='save' type='button' onclick='javascript:document.location.href=\"$PHP_SELF?$SESSION_SID&amp;V_dir=$V_dir&amp;V_mod=cesta&amp;op=add&amp;V0_ud=1&amp;id=".trim($TMP_row[idarticulo])."\";' class='boton' id='button' value='".strtoupper(_DEF_NLSCestaComprar)."'>
		</td></tr>";
//<a href="'.$PHP_SELF.'?'.$SESSION_SID.'&amp;V_dir='.$V_dir.'&amp;V_mod=cesta&amp;op=add&amp;V0_ud=1&amp;id='.$id.'"><img src="/themes/'.$TMP_dirtheme.'/ico_cesta.png" alt="cesta" /></a>
	$TMP.='</table></td>
			<td align="left" width="30" valign="top"></td>
			<td align="left" width="250" valign="top" height="100"><b>Familia</b>: '.$TMP_FAM.'
			<br/><br />'.$TMP_row[descripcion].$TMP_linkAdm.'</td></tr></table>';
	if ($TMP_row[observaciones]!="") $TMP.='<div>'.$TMP_row[observaciones].'</div>';
	
	return $TMP;
}
//----------------------------------------------------------------------------------------
function galeriaFotos($TMP_fotos,$numcolumnas) {
// Si el numcolumnas es cero se muestra una sola foto y la galeria de fotos mini
global $TMP_contgaleriaFotos, $dbname;
$TMP_contgaleriaFotos++;
    $galeria="\n<table cellpadding=2 cellspacing=0 border=0><tr>";
    $numfotos=0;
    $A_TMP_fotos=explode("\n",$TMP_fotos);
    if (count($A_TMP_fotos)>0 && trim($TMP_fotos!="")) {
	for ($ki=0; $ki<count($A_TMP_fotos); $ki++) {
		$filename=$A_TMP_fotos[$ki];
		$filename=str_replace("\n","",$filename);
		$filename=str_replace("\r","",$filename);
		if (trim($filename)!="") {
			$filename="files/".$dbname."/".$filename;
			list($anchoimg, $altoimg, $tipo, $resto)=getimagesize($filename);
			$filename2=$filename;
			if ($altoimg>320 || $anchoimg>320) {
				$filename2=RAD_resizeImg($filename, "320", "320");
			}
			list($anchoimg, $altoimg, $tipo, $resto)=getimagesize($filename2);
			$fotomini=RAD_resizeImg($filename,50,50);
			$fotosmini.="<img BORDER=1 VSPACE=2 HSPACE=2 src=\"/".$fotomini."\" onmouseover='javascript:document.imggal".$TMP_contgaleriaFotos.".src=\"".$filename2."\";filetoshow=\"".$filename."\"'> ";
			//$galeria.="<a target=_blank href='$filename'>";
    			if ($numfor==0) $galeria.="\n<script>\n var filetoshow='".$filename."';\n</script>\n";
			if ($numcolumnas>0 || $numfotos==0) {
				$galeria.="<td><a onclick='javascript:window.open(\"\"+filetoshow);return false;'><img name=imggal".$TMP_contgaleriaFotos." BORDER=1 src=\"/".$filename2."\"";
				if ($numcolumnas>0) $galeria.=" onmouseover='javascript:filetoshow=\"".$filename."\"'";
				$galeria.="></a></td>\n";
			}
			$numfotos++;
			if ($numcolumnas>0) {
			    if ($numfotos>0 && $numfotos%$numcolumnas==0) $galeria.="</tr><tr>\n";
			}
		}
	}
    }
    if ($numfotos==0) $galeria="";
    else {
	$galeria.="</tr></table>";
	if (!$numcolumnas>0) $galeria=$fotosmini."<br />".$galeria;
    }
    return $galeria;
}
//----------------------------------------------------------------------------------------
function linkFamArt($TMP_idfam,$TMP_idart,$TMP_lit) {
	global $PHP_SELF, $V_dir, $V_mod, $headeroff, $footeroff, $menuoff, $blocksoff, $SEO_showfamilia, $SESSION_SID;
	if ($PHP_SELF=="") $PHP_SELF=_DEF_INDEX;
	//$TMP_link=$PHP_SELF.'?V_dir='.$V_dir.'&amp;V_mod='.$V_mod;
	$TMP_link=$PHP_SELF.'?V_dir='.$V_dir.'&amp;V_mod='.$V_mod;
	if ($TMP_idfam>0) $TMP_link.='&amp;idfam='.$TMP_idfam;
	if ($TMP_idart>0) $TMP_link.='&amp;id='.$TMP_idart;
	if ($SESSION_SID!="&" && $SESSION_SID!="&amp;") $TMP_link.=$SESSION_SID;	
	$TMP_lit=str_replace(" ","-",$TMP_lit);	
	
	if ($headeroff!="") $TMP_link.='&amp;headeroff='.$headeroff;
	if ($footeroff!="") $TMP_link.='&amp;footeroff='.$footeroff;
	if ($menuoff!="") $TMP_link.='&amp;menuoff='.$menuoff;
	if ($blocksoff!="") $TMP_link.='&amp;blocksoff='.$blocksoff;
	return $TMP_link;
}

//----------------------------------------------------------------------------------------
function limpiar_url($cadena){	
	//$caracteres_no_validos= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","Ñ","À","È","Ì","Ò","Ù","à","è","ì","ò","ù","ç","Ç","â","ê","î","ô","û","Â","Ê","Î","Ô","Û","ü","ö","Ö","ï","ä","ë","Ü","Ï","Ä","Ë","(",")");
	//$caracteres_validos= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E","","");
	$caracteres_no_validos= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","Ñ","À","È","Ì","Ò","Ù","à","è","ì","ò","ù","ç","Ç","â","ê","î","ô","û","Â","Ê","Î","Ô","Û","ü","ö","Ö","ï","ä","ë","Ü","Ï","Ä","Ë","(",")","/");
	$caracteres_validos= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E","","","-");
	$cadena=str_replace($caracteres_no_validos,$caracteres_validos,$cadena);	
	$cadena_out=$new_string = preg_replace("/[^a-zA-Z0-9-._\/\?\=;]/", "", $cadena);	
	return $cadena_out;
}
?>
