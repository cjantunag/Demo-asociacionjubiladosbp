<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: ../../index.php");
    die();
}
// Parametros:
// id: id del articulo a mostrar
// showdocs: si no esta vacio indica que se muestran los enlaces a los docs
// secindex: seccion de articulos a mostrar en indice
// showindex: numero de articulos a mostrar en el indice (1 por defecto)
// lastindex: leer los ultimos lastindex (si showindex es menor a lastindex se eligen de forma aleatoria los showindex entre
// todos los lastindex (1 por defecto)
// maxchars: numero de caracteres de texto a mostrar en el index del articulo. Si se pone -1 se muestra todo el articulo (250 por defecto)
if ($id=="" && trim($artid)!="") $id=$artid;
$color="";

if (file_exists("modules/$V_dir/common.app.php")) include_once ("modules/$V_dir/common.app.php");
if (file_exists("modules/$V_dir/common.".$V_mod.".php")) include_once ("modules/$V_dir/common.".$V_mod.".php");
if (file_exists("modules/$V_dir/".$V_mod.".common.php")) include_once ("modules/$V_dir/".$V_mod.".common.php");
if (_DEF_appname!="" && _DEF_appname!="_DEF_appname") if (file_exists("modules/$V_dir/common.app."._DEF_appname.".php")) include_once ("modules/$V_dir/common.app."._DEF_appname.".php");

$user=base64_decode($HTTP_SESSION_VARS["SESSION_user"]);
$lang=getSessionVar("SESSION_lang");

if (!is_user()) $CondPublico=" AND publico!='0'";
//if (!is_admin() && !is_editor()) {
//	$CondPublico.=" AND fechapubli<'".time()."' and (fechabaja is null or fechabaja='' or fechabaja='0' or fechabaja>'".time()."')";
// No se activa, porque habria que tenerlo en cuenta en los menus tambien
//}


if ($id>0) {
	$TMP_res=sql_query("SELECT * FROM articulos where id=".converttosql($id), $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
	foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
	if ($TMP_row[META_title]!="") setSessionVar("SESSION_pagetitle",$TMP_row[META_title],0); // pone titulo de pagina
	if ($TMP_row[META_description]!="") setSessionVar("SESSION_description",$TMP_row[META_description],0);
	if ($TMP_row[META_keywords]!="") setSessionVar("SESSION_keywords",$TMP_row[META_keywords],0);
}


switch($cmd) {
	case "print":
		imprimearticulo($id);
		break;
	default:
		include_once ("header.php");
		OpenTable();
		if ($id!="") muestraarticulo($id, $page);
		else if ($owner!="") listaarticulos("","","",0);
		else if ($secid!="") listaarticulos($secid,"","",0);
		else if ($secindex!="") listaarticulosindex($secindex, $showindex, $lastindex, $maxchars);
		else listasecciones();
		CloseTable();
		include_once("modules/".$V_dir."/lib.ajax.php");
		include_once ("footer.php");
	break;
}
if (function_exists("listasecciones")) return;
/////////////////////////////////////////////////////////////////////////////////////////
function listasecciones() {
	global $RAD_dbi;

	if (is_admin() || is_editor() || is_viewer()) $result = sql_query("select id, nombre, imagen from articulossecciones order by orden", $RAD_dbi);
	else $result = sql_query("select id, nombre, imagen from articulossecciones where visible='1' order by orden", $RAD_dbi);

	$count = 0;
	while (list($secid, $secname, $image) = sql_fetch_row($result, $RAD_dbi)) {
		listaarticulos($secid,"","",0);
	}
}
/////////////////////////////////////////////////////////////////////////////////////////
function listaarticulos($TMP_secid,$TMP_idlis,$TMP_idartparent,$TMP_level) {
	global $RAD_dbi, $user, $SESSION_SID, $owner, $secid, $CondPublico, $V_dir, $V_mod, $A_ART_LISTADOS, $id, $color, $headeroff, $footeroff, $noshowartstree, $noprint;
	
	if (defined('_DEF_DIR_ROOT')) $dir_root = _DEF_DIR_ROOT;
	else $dir_root = "";

	if ($owner=="" && $TMP_idartparent=="" && $TMP_idlis=="") {
		echo "\n<table border=0 cellpadding=0 cellspacing=0 align=center width=100%>";
		if (is_admin() || is_editor() || is_viewer()) $result = sql_query("select nombre, imagen from articulossecciones where id='$TMP_secid'", $RAD_dbi);
		else $result = sql_query("select nombre, imagen from articulossecciones where id='$TMP_secid'", $RAD_dbi);
		//else $result = sql_query("select nombre, imagen from articulossecciones where id='$TMP_secid' AND visible='1'", $RAD_dbi);
		list($secname,$image) = sql_fetch_row($result, $RAD_dbi);
		echo "<tr><th><b>$secname</b></th></tr>";
		echo "\n</table>";
	} else if ($owner!="" && $TMP_idartparent=="" && $TMP_idlis=="") {
		echo "\n<table border=0 cellpadding=0 cellspacing=0 align=center width=100%>";
		echo "<tr><th><b>Mis P&aacute;ginas</b></th></tr>";
		echo "\n</table>";
	}

	if ($owner!="") {
		if ($TMP_secid=="") $TMP_cmdSQL="select id, idseccion, idartparent, nombre, contenido, paginas, autor from articulos where autor='$user' $CondPublico";
		else $TMP_cmdSQL="select id, idseccion, idartparent, nombre, contenido, paginas, autor from articulos where autor='$user' and idseccion='$TMP_secid' $CondPublico";
	} else {
		if (is_admin() || is_editor() || is_viewer()) $TMP_cmdSQL="select id, idseccion, idartparent, nombre, contenido, paginas, autor from articulos where idseccion='$TMP_secid' $CondPublico";
		else $TMP_cmdSQL="select id, idseccion, idartparent, nombre, contenido, paginas, autor from articulos where idseccion='$TMP_secid' $CondPublico";
		//else $TMP_cmdSQL="select id, idseccion, idartparent, nombre, contenido, paginas, autor from articulos where idseccion='$TMP_secid' AND visible='1' $CondPublico";
	}
	if ($TMP_idartparent>0) $TMP_cmdSQL.=" AND idartparent='".$TMP_idartparent."'";
	if ($TMP_idlis>0) $TMP_cmdSQL.=" AND id='".$TMP_idlis."'";
	$TMP_cmdSQL.=" order by idseccion,orden";

	if ($noshowartstree!="") return;
	if ($TMP_idartparent=="" &&  $TMP_idlis=="") echo "\n<table border=0 cellpadding=0 cellspacing=0 width=100%>";
	$TMP_cont=0;
	$result = sql_query($TMP_cmdSQL, $RAD_dbi);
	while (list($TMP_id, $TMP_secid, $idartparent, $title, $content, $counter, $autor) = sql_fetch_row($result, $RAD_dbi)) {
		if ($color=="style='background-color:white'") $color="style='background-color:#E0E0E0'";
		else $color="style='background-color:white'";
		if ($A_ART_LISTADOS[$TMP_id]!="") continue;
		else $A_ART_LISTADOS[$TMP_id]=$TMP_id;
		$TMP_cont++;
		$title=str_replace("\r\n\r\n", "<br>\r\n<br>\r\n", $title);
		$title=str_replace("\n\n", "<br>\n<br>\n", $title);
		if (!eregi("<p",$content) && !eregi("<br",$content) && !eregi("<table",$content)) {
			$content=str_replace("\r\n\r\n", "<br>\r\n<br>\r\n", $content);
			$content=str_replace("\n\n", "<br>\n<br>\n", $content);
		}

		if ($owner!="") $Xowner="&owner=".$owner;
		echo "<tr><td $color nowrap>";
		if ($TMP_level>0) echo str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $TMP_level);
		if ($id==$TMP_id) $title="<b>".$title."</b>";
		echo "<a href=\"".$dir_root."index.php?V_dir=$V_dir&V_mod=$V_mod&headeroff=$headeroff&footeroff=$footeroff&id=$TMP_id$Xowner\">&middot; $title</a>";
		echo "</td>";
		if ($noprint=="") echo "<td $color><a href=\"".$dir_root."index.php?V_dir=$V_dir&V_mod=$V_mod&cmd=print&id=$TMP_id$Xowner\" target=_blank>"._DEF_NLSPrint."</a></td>";
		else echo "<td $color></td>";
		if (is_admin() || is_editor() || ($autor==$user && $autor!="" && $user!="") )
			echo "<td $color><a href=\"javascript:RAD_OpenW('".$dir_root."index.php?V_dir=$V_dir&V_mod=$V_mod&cmd=edit&id=$TMP_id&headeroff=x&footeroff=x".$SESSION_SID."');\"><img src=\"".$dir_root."images/edit.gif\" border=\"0\" Alt=\""._EDIT."\"> "._EDIT."</a></td>";
		else
			echo "<td $color>&nbsp;</td>";
		echo "</tr>\n";
		$TMP_level2=$TMP_level+1;
		listaarticulos($TMP_secid,"",$TMP_id,$TMP_level2);
	}
	if ($TMP_idartparent>0 || $TMP_idlis>0) return;
	if ($TMP_cont==0 && $TMP_idartparent=="" && $TMP_idlis=="") echo "<tr><td $color> No hay paginas en esta Seccion.</td></tr>";

	if ($owner!="" || $secid!="") echo "<tr><td align=center> [ <a href=\"".$dir_root."index.php?V_dir=$V_dir&V_mod=$V_mod\">"._SECTIONS." </a> ]</td></tr>";
	if (is_user()) echo "<tr><td align=right><a href=\"javascript:RAD_OpenW('".$dir_root."index.php?V_dir=$V_dir&V_mod=$V_mod&cmd=new&id=".$SESSION_SID."&headeroff=x&footeroff=x');\"> <img src='".$dir_root."images/new.gif' border='0' alt='nueva pagina'> Crear Nuevo </a></td></tr>";
	if (is_admin() || is_editor() || is_viewer()) {
		//$result = sql_query("select id, idseccion, nombre, contenido, paginas, autor, visible from articulos where visible!='1' order by orden", $RAD_dbi);
		$result = sql_query("select id, idseccion, nombre, contenido, paginas, autor, visible from articulos order by orden", $RAD_dbi);
		while (list($TMP_id, $TMP_secid2, $title, $content, $counter, $autor, $visible) = sql_fetch_row($result, $RAD_dbi)) {
		if ($color=="style='background-color:white'") $color="style='background-color:#E8E8E0'";
		else $color="style='background-color:white'";
			$nombreautor=RAD_lookup("usuarios","nombre","usuario",$autor);
			if ($id==$TMP_id) $title="<b>".$title."</b>";
			if (is_viewer()) echo "<tr><td $color align=left> ".$TMP_x."<a href=\"".$dir_root."index.php?V_dir=$V_dir&V_mod=$V_mod&id=".$TMP_id.$SESSION_SID."\"> $title </a> [".$nombreautor."]";
			else echo "<tr><td $color align=left> ".$TMP_x."<a href=\"".$dir_root."index.php?V_dir=$V_dir&V_mod=$V_mod&cmd=edit&id=".$TMP_id.$SESSION_SID."\"> <img src='".$dir_root."images/edit.gif' border='0' alt='edita pagina'> $title </a> [".$nombreautor."]";
			if ($visible !="1") echo " <b>No visible</b> ";
			echo "</td></tr> ";
		}
	}
	if ($TMP_idartparent=="" && $TMP_idlis=="") echo "\n</table>\n";
	return;
}
/////////////////////////////////////////////////////////////////////////////////////////
function listaarticulosindex($TMP_secid, $TMP_showindex, $TMP_lastindex, $TMP_maxchars) {
	global $PHP_SELF, $RAD_dbi, $SESSION_SID, $CondPublico, $V_dir, $V_mod, $show;

	$TMP_lang=getSessionVar("SESSION_lang");
	
	if (defined('_DEF_DIR_ROOT')) $dir_root = _DEF_DIR_ROOT;
	else $dir_root = "";

	if ($TMP_maxchars=="" || $TMP_maxchars==0) $TMP_maxchars=250;
	if (!$TMP_lastindex>0) $TMP_lastindex=1;
	if (!$TMP_showindex>0) $TMP_showindex=1;
	if ($TMP_showindex>$TMP_lastindex) $TMP_showindex=$TMP_lastindex;

	$result=sql_query("select * from articulossecciones where id='$TMP_secid'", $RAD_dbi);
	$row=sql_fetch_array($result, $RAD_dbi);
	$secname=$row[nombre];
	echo "<table class='content'><tr><th class='content'><a class='content' href='$PHP_SELF?V_dir=$V_dir&V_mod=$V_mod&secid=$TMP_secid".$SESSION_SID."'>$secname</a></th></tr></table>\n";
	//$TMP_cmdSQL="select * from articulos where idseccion='$TMP_secid' AND visible='1' $CondPublico";
	$TMP_cmdSQL="select * from articulos where idseccion='$TMP_secid' $CondPublico";
	$TMP_cmdSQL.=" order by fechaalta DESC,orden ASC LIMIT 0,$TMP_lastindex";

	$result = sql_query($TMP_cmdSQL, $RAD_dbi);
	$TMP_cont=0;
	while ($row = sql_fetch_array($result, $RAD_dbi)) {
		foreach($row as $TMP_k=>$TMP_v) if ($row[$TMP_k."_".$TMP_lang]!="") $row[$TMP_k]=$row[$TMP_k."_".$TMP_lang];
		$title=$row[nombre];
		$content=$row[contenido];
		$title=str_replace("\r\n\r\n", "<br>\r\n<br>\r\n", $title);
		$title=str_replace("\n\n", "<br>\n<br>\n", $title);
		if (!eregi("<p",$content) && !eregi("<br",$content) && !eregi("<table",$content)) {
			$content=str_replace("\r\n\r\n", "<br>\r\n<br>\r\n", $content);
			$content=str_replace("\n\n", "<br>\n<br>\n", $content);
		}
		$TMP_icono="";
		if ($TMP_maxchars>0) {
			$TMP_fotoregcont=RAD_ImgHTML($content);
			$TMP_fotominicont="";
			if (file_exists($TMP_fotoregcont) && $TMP_fotoregcont!="") {
				$TMP_pos=strrpos($TMP_fotoregcont,"/");
				$TMP_fotominicont=substr($TMP_fotoregcont,0,$TMP_pos+1)."x".substr($TMP_fotoregcont,$TMP_pos+1);
				if (!file_exists($TMP_fotominicont)) $TMP_fotominicont=RAD_resizeImg($TMP_fotoregcont,100,100);
			}
			$content=RAD_convertHTML2TXT($content);
			if (strlen($content)>$TMP_maxchars) $content=substr($content,0,$TMP_maxchars)." ...";
			if (file_exists($TMP_fotominicont)) $TMP_icono="<img ALT='".$content."' ALIGN=LEFT BORDER=1 HSPACE=2 src=\"".$dir_root.$TMP_fotominicont."\">\n";
			$content=$TMP_icono.$content;
		}
		$A_id[$TMP_cont]=$row[id];
		$A_title[$TMP_cont]=$title;
		$A_content[$TMP_cont]=$content;
		$TMP_cont++;

	}
	$lista="";
	for($k=0; $k<$TMP_showindex; $k++) {
		$idx=rand(0,count($A_id)-1);
		if (ereg(",".$idx.",",$lista)) {
			$k--;
			continue;
		}else{
			$lista.=",".$idx.",";
		}
		$id=$A_id[$idx];
		$title=$A_title[$idx];
		$content=$A_content[$idx];
		echo "\n<table class='content'><tr><td class='content'><a href='$PHP_SELF?V_dir=$V_dir&V_mod=$V_mod&secid=$TMP_secid&id=$id'>\n";
		if ($TMP_maxchars>0) echo "<b>$title</b><br>". $content."</a>";
		else echo $content."</a>";
		echo "</td></tr></table>\n";
	}
}
/////////////////////////////////////////////////////////////////////////////////////////
function muestraarticulo($id, $page) {
	global $lang, $RAD_dbi, $user, $SESSION_SID, $owner, $CondPublico, $V_dir, $V_mod, $cmd, $headeroff, $footeroff, $dbname, $showdocs, $noeditamaqueta, $PHPSESSID, $V_idmod, $_REQUEST, $noshowfiles, $noprint, $contenidopie, $noshowartstree;
	if ($dbname=="") $dbname=_DEF_dbname;

	$TMP_lang=getSessionVar("SESSION_lang");

	$TMP_dirtheme=getSessionVar("SESSION_theme");
	if (file_exists("files/"._DEF_dbname."/reload.png")) $TMP_ico="files/"._DEF_dbname."/reload.png";
	else if (file_exists("themes/".$TMP_dirtheme."/reload.png")) $TMP_ico="themes/".$TMP_dirtheme."/reload.png";
	else if (file_exists("files/"._DEF_dbname."/reload.gif")) $TMP_ico="files/"._DEF_dbname."/reload.gif";
	else if (file_exists("themes/".$TMP_dirtheme."/reload.gif")) $TMP_ico="themes/".$TMP_dirtheme."/reload.gif";
	else $TMP_ico="images/reload.gif";
	
	$TMP_secu="
<input type='text' name='captcha_code' size='10' maxlength='6'> 
<img id='captcha' src='".$dir_root."modules/securimage/securimage_show.php' alt='Imagen CAPTCHA' style='vertical-align:text-top;'>
<a href='#' onclick=\"document.getElementById('captcha').src='".$dir_root."modules/securimage/securimage_show.php?'+Math.random();return false\"><img src='".$TMP_ico."' title='Recarga Imagen' alt='Recarga Imagen' border=0 style='vertical-align:middle;'></a>";

	//global $TMP_secu;
	//include_once("modules/".$V_dir."/lib.secu.php");

	if (defined('_DEF_DIR_ROOT')) $dir_root = _DEF_DIR_ROOT;
	else $dir_root = "";

	if ($cmd=="slide") {
		echo "<div align=right>";
		$result = sql_query("select * from articulos where id=$id ".$CondPublico, $RAD_dbi);
		$TMP_row = sql_fetch_array($result, $RAD_dbi);
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
		$TMP_row[contenido]=str_replace("<!-- securimage -->",$TMP_secu,$TMP_row[contenido]);
		$TMP_row[contenido]=str_replace("< script","<script",$TMP_row[contenido]);
		$TMP_id = $TMP_row[id];
		$secid = $TMP_row[idseccion];
		$title = $TMP_row[nombre];
		$content = $TMP_row[contenido];
		$counter = $TMP_row[paginas];
		$autor = $TMP_row[autor];
		$url = $TMP_row[url];
		if (substr($url,0,9)=="index.php" || substr($url,0,11)=="modules".".php") $url=_DEF_URL_SUBBROWSE.$url."&subbrowseSID=".$PHPSESSID;
		$result2 = sql_query("select id, nombre from articulossecciones where id=$secid", $RAD_dbi);
		list($secid, $secname) = sql_fetch_row($result2, $RAD_dbi);
		$result = sql_query("select * from articulos where idseccion=$secid ".$CondPublico." ORDER BY orden", $RAD_dbi);
		$barra="";
		while($TMP_row=sql_fetch_array($result, $RAD_dbi)) {
			foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
			$TMP_row[contenido]=str_replace("<!-- securimage -->",$TMP_secu,$TMP_row[contenido]);
			$TMP_row[contenido]=str_replace("< script","<script",$TMP_row[contenido]);
			$TMP_id=$TMP_row["id"];
			$secid=$TMP_row["idseccion"];
			$tile=$TMP_row["nombre"];
			$content=$TMP_row["contenido"];
			$counter=$TMP_row["paginas"];
			$autor=$TMP_row["autor"];
			if ($TMP_select=="") $TMP_select="<option value='".$TMP_id."'".$SELECTED."> $secname </option>\n";
			if ($id==$TMP_id) {
				$barra=$ant." ";
//				$barra=$ant.$title;
				$SELECTED=" SELECTED";
			} else $SELECTED="";
			if ($id!=$TMP_id && $barra!="" && $post=="") $post=" <a href=\"".$dir_root."index.php?V_dir=$V_dir&V_mod=$V_mod&cmd=slide&id=$TMP_id&headeroff=$headeroff&footeroff=$footeroff\"><img src=\"".$dir_root."images/right.gif\" border=\"0\" alt=\"".$title."\" title=\"".$title."\"></a>";
			$ant="<a href=\"".$dir_root."index.php?V_dir=$V_dir&V_mod=$V_mod&cmd=slide&id=$TMP_id&headeroff=$headeroff&footeroff=$footeroff\"><img src=\"".$dir_root."images/left.gif\" border=\"0\" alt=\"".$title."\" title=\"".$title."\"></a>";
			$TMP_select.="<option value='".$TMP_id."'".$SELECTED."> ->$title </option>\n";
		}
		if ($post=="") $post=" <img src='".$dir_root."images/pixel.gif' height=11 width=11>";
		echo "<nobr><form action='index.php' name=slide method='get'>$barra<input type=hidden name=PHPSESSID value='$PHPSESSID'>";
		echo "<input type=hidden name=V_dir value='$V_dir'><input type=hidden name=footeroff value='$footeroff'>";
		echo "<input type=hidden name=headeroff value='$headeroff'><input type=hidden name=V_mod value='$V_mod'>";
		echo "<input type=hidden name=cmd value='$cmd'>";
		echo "<select NAME=id SINGLE onChange='javascript:form.submit();' style='font-size:9pt'>".$TMP_select."</select>";
		echo $post."</form></p></nobr></div>";
	}

	if (is_admin() || is_editor() || is_viewer()) $result = sql_query("select * from articulos where id=$id ".$CondPublico, $RAD_dbi);
	else if ($owner!="") $result = sql_query("select * from articulos where id=$id AND autor='$user' ".$CondPublico, $RAD_dbi);
	else $result = sql_query("select * from articulos where id=$id ".$CondPublico, $RAD_dbi);
	//else $result = sql_query("select * from articulos where id=$id AND visible!='0' ".$CondPublico, $RAD_dbi);
	$TMP_row=sql_fetch_array($result, $RAD_dbi);
	foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
	$TMP_row[contenido]=traduce($TMP_row[contenido]);
	$TMP_row[contenido]=str_replace("<!-- securimage -->",$TMP_secu,$TMP_row[contenido]);
	$TMP_row[contenido]=str_replace("< script","<script",$TMP_row[contenido]);
	//Si es un articulo que contiene a otro articulo => no mostrar el icono de print: variable que lo controlara
	if ($TMP_row['url'] != '' && strpos($TMP_row['url'],'V_mod=articulos') !== false && $TMP_row['contenido'] == '') $mostrar_imprime = false;
	else $mostrar_imprime = true;
	$id=$TMP_row["id"];
	$secid=$TMP_row["idseccion"];
	$tile=$TMP_row["nombre"];
	$content="";
	if ($contenidopie=="") $content=$TMP_row["contenido"];
	$counter=$TMP_row["paginas"];
	$autor=$TMP_row["autor"];
	$documentos=$TMP_row["documentos"];
	$url = $TMP_row[url];
	if (trim($url)!="") eval("\$TMP_url=\"".$url."\";");
	if (trim($TMP_url)!="") $content.="\n<! ".$TMP_url." >\n";
	if (substr($TMP_url,0,9)=="index.php" || substr($TMP_url,0,11)=="modules".".php") $TMP_url=_DEF_URL_SUBBROWSE.$TMP_url."&subbrowseSID=".$PHPSESSID;
	if (trim($TMP_url)!="") {
		$TMP_url=trim($TMP_url)."&V_prevmod=$V_mod&V_prevdir=$V_dir&V_previd=$id";
		//$TMP_url=trim($TMP_url)."&V_prevmod=$V_mod&V_prevdir=$V_dir&V_previdmod=$V_idmod&V_previd=$id";
		foreach($_REQUEST as $TMP_k=>$TMP_v) {
			if (ereg("PHPSESSID",$TMP_k)) continue;
			if (!ereg("".$TMP_k."=",$TMP_url)) $TMP_url.="&".$TMP_k."=".urlencode($TMP_v);
		}
		//$content.=trim($TMP_url);
		$content_URL=RAD_OpenURL(trim($TMP_url));
	}
	if (ereg("<! CONTENT_URL >",$content)) $content=str_replace("<! CONTENT_URL >",$content_URL,$content);
	else $content.=$content_URL;
	$title=str_replace("\r\n\r\n", "<br>\r\n<br>\r\n", $title);
	$title=str_replace("\n\n", "<br>\n<br>\n", $title);
	if (!eregi("<p",$content) && !eregi("<br",$content) && !eregi("<table",$content)) {
		$content=str_replace("\r\n\r\n", "<br>\r\n<br>\r\n", $content);
		$content=str_replace("\n\n", "<br>\n<br>\n", $content);
	}
	if ($contenidopie!="") $content.=$TMP_row["contenido"];

	if ($secid!="") {
	    $result2 = sql_query("select id, nombre from articulossecciones where id=$secid", $RAD_dbi);
	    list($secid, $secname) = sql_fetch_row($result2, $RAD_dbi);
	    $words = sizeof(explode(" ", $content));
	}
	echo " \n<div class='articulo'>\n<h1>".$TMP_row["nombre"]."</h1>\n";

	$contentpages = explode( "<!--pagebreak-->", $content );
	$pageno = count($contentpages);
	if ( $page=="" || $page < 1 ) $page = 1;
	if ( $page > $pageno ) $page = $pageno;
	$arrayelement = (int)$page;
	$arrayelement --;

	if ($pageno > 1) echo ""._PAGE.": $page/$pageno<br>";


	if (eregi("\\$"."fotoautor", $contentpages[$arrayelement])) {
		$contentpages[$arrayelement]=str_replace("\$fotoautor",$fotoautor,$contentpages[$arrayelement]);
        	$fotoautor="";
	}

//	echo $fotoautor.$contentpages[$arrayelement];

	if($page >= $pageno) {
		$next_page = "";
	} else {
		$next_pagenumber = $page + 1;
		$next_page = "<a href=\"".$dir_root."index.php?V_dir=$V_dir&V_mod=$V_mod&id=$id&page=$next_pagenumber\">"._NEXT." ($next_pagenumber/$pageno) <img src=\"".$dir_root."images/right.gif\" border=\"0\" alt=\""._NEXT."\"></a>";
	}

	if($page <= 1) {
		$previous_page = "";
	} else {
		$previous_pagenumber = $page - 1;
		$previous_page = "<a href=\"".$dir_root."index.php?V_dir=$V_dir&V_mod=$V_mod&id=$id&page=$previous_pagenumber\">"._PREVIOUS." ($previous_pagenumber/$pageno) <img src=\"".$dir_root."images/left.gif\" border=\"0\" alt=\""._PREVIOUS."\"></a>";
	}

	if ($owner!="") $Xowner="&owner=$owner";

	$result = sql_query("select count(*) from articulos where idartparent='$id'", $RAD_dbi);
	$TMP_row = sql_fetch_array($result, $RAD_dbi);
	$numhijos=$TMP_row[0];
	$idpadre=primerPadre($id);
	if ($idpadre<1) $idpadre="";
	if (($idpadre!="" || $numhijos>0) && _DEF_ARTICULOS_SINJERARQUIA != "1" && $noshowartstree=="") { // oculta listado de articulos cuando visualizamos un articulo que pertenece a otro.
	    OpenTable();
	    if ($idpadre>0) listaarticulos($secid,$idpadre,"",0);
	    else listaarticulos($secid,$id,"",0);
	    CloseTable();
	}

	if (is_artpermitted($id)==true) echo $fotoautor.$contentpages[$arrayelement];
	else echo "No posee permisos suficientes para poder visualizar este art&iacute;culo.";

	$TMP_enlace="";
	$TMP_visor="";
	$TMP_otrosdocumentos="";
	if (trim($documentos)!="") {
		$files = explode("\n", $documentos."\n\n");
		for ($k = 0; $k < count($files); $k++) {
			$files[$k]=str_replace("\n", "", $files[$k]);
			$files[$k]=str_replace("\r", "", $files[$k]);
			$pos=strpos($files[$k],".");
			$filename=substr($files[$k],$pos+1);
			if ($files[$k]!="") {
				$ext=strtolower(substr($files[$k],strlen($files[$k])-3));
				$ext4=strtolower(substr($files[$k],strlen($files[$k])-4));
				$TMP_file=substr($files[$k],0,strlen($files[$k])-3);
				if ($ext=="mpg"||$ext=="avi"||$ext=="mp3") { // usa ffmpeg o mencoder para convertir a flv
					system("mencoder files/".$dbname."/".$files[$k]." -o files/".$dbname."/".$TMP_file."flv -of lavf -oac mp3lame -lameopts br=32 -af lavcresample=22050 -srate 22050 -ovc lavc -lavcopts vcodec=flv:vbitrate=340:autoaspect:mbd=2:trell:v4mv -vf scale=320:240 -lavfopts i_certify_that_my_video_stream_does_not_use_b_frames");
					//system("ffmpeg -i files/".$dbname."/".$files[$k]." files/".$dbname."/".$TMP_file."flv");
					$TMP_enlace.="<br><a target=_blank href='".$dir_root."files/".$dbname."/".$files[$k]."'>".$files[$k]."</a>";
				} else if ($ext4=="mpeg") {
					$TMP_file=substr($files[$k],0,strlen($files[$k])-4);
					system("mencoder files/".$dbname."/".$files[$k]." -o files/".$dbname."/".$TMP_file."flv -of lavf -oac mp3lame -lameopts br=32 -af lavcresample=22050 -srate 22050 -ovc lavc -lavcopts vcodec=flv:vbitrate=340:autoaspect:mbd=2:trell:v4mv -vf scale=320:240 -lavfopts i_certify_that_my_video_stream_does_not_use_b_frames");
					//system("ffmpeg -i files/".$dbname."/".$files[$k]." files/".$dbname."/".$TMP_file."flv");
					$TMP_enlace.="<br><a target=_blank href='".$dir_root."files/".$dbname."/".$files[$k]."'>".$files[$k]."</a>";
				} else {
					$TMP_otrosdocumentos.="<a href=\"".$dir_root."files/".$dbname."/".RAD_urlencodeFich($files[$k])."\" TARGET=_blank>".$filename."</a><br />\n";
				}
				if ($ext=="flv"||$ext=="mpg"||$ext=="avi"||$ext=="mp3"||$ext4=="mpeg") {
					$TMP_visor="<center><object classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' codebase='http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0' width='320' height='240' align='center'>
<PARAM NAME=allowFlashAutoInstall VALUE=true>
<param name=Flashvars value='url=../../files/".$dbname."/".$TMP_file."flv'>
<param name='allowScriptAccess' value='sameDomain' />
<param name='movie' value='images/flash/320x240.swf' />
<param name='quality' value='high' />
<param name='bgcolor' value='white' />
<embed src='/images/flash/320x240.swf' swLiveConnect='true' Flashvars='url=../../files/".$dbname."/".$TMP_file."flv' quality='high' bgcolor='white' width='320' height='240' align='center' allowScriptAccess='sameDomain' type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/go/getflashplayer' />
</object></center>
";
				}
			}
		}
	}
	if ($showdocs!="") $TMP_enlace=RAD_showfield("file","",$documentos);
	if ($TMP_enlace!="" || $TMP_visor!="") echo $TMP_enlace.$TMP_visor;
	if ($noshowfiles=="" && $TMP_otrosdocumentos!="") echo "<table border=0 cellpadding=0 cellspacing=0 width=100%><tr><td colspan=2><hr size=1 noshade></td></tr><tr><td valign=top><b>Ficheros:</b></td><td>".$TMP_otrosdocumentos."</td></tr></table>";

	if ($owner!="") {
		$result = sql_query("select * from articulos where autor='$user' $CondPublico AND idartparent='$id' order by orden", $RAD_dbi);
		//$result = sql_query("select * from articulos where autor='$user' AND visible!='0' $CondPublico AND idartparent='$id' order by orden", $RAD_dbi);
	} else if (is_admin() || is_editor() || is_viewer()) {
		$result = sql_query("select * from articulos where idartparent='$id' order by orden", $RAD_dbi);
	} else {
		$result = sql_query("select * from articulos where idartparent='$id' $CondPublico order by orden", $RAD_dbi);
		//$result = sql_query("select * from articulos where idartparent='$id' AND visible!='0' $CondPublico order by orden", $RAD_dbi);
	}
	$arts="<ul>";
	while ($TMP_row = sql_fetch_array($result, $RAD_dbi)) {
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
		$arts.= "<li><a href=\"".$dir_root."index.php?V_dir=$V_dir&V_mod=$V_mod&headeroff=$headeroff&footeroff=$footeroff&id=".$TMP_row[id]."$Xowner\"> ".$TMP_row[nombre]." </a></li>";
	}
	if ($arts!="<ul>") $arts.="</ul><br><br>";
	else $arts="";
//xx	echo $arts;

	echo "<center>$previous_page &nbsp;&nbsp; $next_page &nbsp;&nbsp; ";
	// echo "[ <a href=\"index.php?V_dir=$V_dir&V_mod=$V_mod&secid=$secid\">"._BACKTO." $secname</a> | "
	// ."<a href=\"index.php?V_dir=$V_dir&V_mod=$V_mod\">"._SECINDEX."</a> ]";
	// echo "($words "._TOTALWORDS.")<br>($counter "._READS.") &nbsp;&nbsp;";

	//if ($cmd=="") echo "<a href=\"index.php?V_dir=$V_dir&V_mod=$V_mod&cmd=print&id=$id$Xowner\" target=_blank>"._DEF_NLSPrint." </a>";
	//if ($cmd=="print") echo "\n<script>\nwindow.print();\n</script>\n";

	$esmaqueta=false;
	if (ereg("<!--maqueta",$content)) $esmaqueta=true;

	if ($noprint=="")		
		if ($mostrar_imprime==true && $cmd=="") echo "<a href=\"".$dir_root."index.php?V_dir=$V_dir&V_mod=$V_mod&cmd=print&id=$id$Xowner\" target=_blank>"._DEF_NLSPrint."</a>";
        if ($cmd=="print") echo "\n<script>\nwindow.print();\n</script>\n";
	if (is_admin() || is_editor() || ($autor==$user && $autor!="" && $user!="")) {
		if ($noprint=="") echo "&nbsp;|&nbsp;";
		echo "<a href=\"javascript:RAD_OpenW('".$dir_root."index.php?V_dir=$V_dir&V_mod=admarticulos&cmd=edit&noeditamaqueta=x&par0=$id&func=edit&headeroff=x&footeroff=x".$SESSION_SID."');\"><img src=\"".$dir_root."images/edit.gif\" border=\"0\" Alt=\""._EDIT."\"> "._EDIT."</a>";
		if ($esmaqueta==true && $noeditamaqueta=="") echo "&nbsp;|&nbsp;<a href=\"".$dir_root."javascript:RAD_OpenW('".$dir_root."index.php?V_dir=$V_dir&V_mod=$V_mod&cmd=edit&id=$id&headeroff=x&footeroff=x".$SESSION_SID."');\"><img src=\"".$dir_root."images/edit.gif\" border=\"0\" Alt=\""._EDIT." con Maqueta\"> "._EDIT." con Maqueta</a>";
	}
	echo "<br></center></div>";

}
/////////////////////////////////////////////////////////////////////////////////////////
function primerPadre($id) {
global $RAD_dbi;
	$result = sql_query("select * from articulos where id='$id' order by orden", $RAD_dbi);
	$TMP_row = sql_fetch_array($result, $RAD_dbi);
	$idpadre=$TMP_row[idartparent];
	$TMP_cont=0;
	while($TMP_cont<100) {
		$TMP_cont++;
		$result = sql_query("select * from articulos where id='$idpadre' order by orden", $RAD_dbi);
		$TMP_row = sql_fetch_array($result, $RAD_dbi);
		if ($TMP_row[idartparent]>0) $idpadre=$TMP_row[idartparent];
		else $TMP_cont=100;
	}
	return $idpadre;
}
/////////////////////////////////////////////////////////////////////////////////////////
function imprimearticulo($id) {
	global $RAD_dbi, $HTTP_SESSION_VARS, $headeroff, $footeroff, $user, $owner, $CondPublico, $V_dir, $V_mod, $headeroff, $footeroff, $PHPSESSID, $noURL;

	$TMP_lang=getSessionVar("SESSION_lang");
	
	if (defined('_DEF_DIR_ROOT')) $dir_root = _DEF_DIR_ROOT;
	else $dir_root = "";

	$headeroff="x";
	$footeroff="x";
	include_once("header.php");

	$tema=$HTTP_SESSION_VARS["SESSION_theme"];
	if ($tema!="") $tema="<link rel=\"StyleSheet\" href=\"".$dir_root."themes/$tema/style.css\" TYPE=\"text/css\">";

	if (is_admin() || is_editor() || is_viewer()) $cmd = "select * from articulos where id=$id ".$CondPublico;
	else if ($owner!="") $cmd = "select * from articulos where id=$id AND autor='$user' ".$CondPublico;
	else $cmd = "select * from articulos where id=$id ".$CondPublico;
	//else $cmd = "select * from articulos where id=$id AND (visible!='0' OR visible IS NULL) ".$CondPublico;
	$result = sql_query($cmd, $RAD_dbi);
	$TMP_row = sql_fetch_array($result, $RAD_dbi);
	foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
	$TMP_row[contenido] = traduce($TMP_row[contenido]);
	$title = $TMP_row[nombre];
	$content = $TMP_row[contenido];
	$autor = $TMP_row[autor];
	$url = $TMP_row[url];
	if (substr($url,0,9)=="index.php" || substr($url,0,11)=="modules".".php") $url=_DEF_URL_SUBBROWSE.$url."&subbrowseSID=".$PHPSESSID;
	if (trim($url)!="") $content_URL=RAD_OpenURL($url);
	if (ereg("<! CONTENT_URL >",$content)) $content=str_replace("<! CONTENT_URL >",$content_URL,$content);
	else $content.=$content_URL;
	$title=str_replace("\r\n\r\n", "<br>\r\n<br>\r\n", $title);
	$title=str_replace("\n\n", "<br>\n<br>\n", $title);
	if (!eregi("<p",$content) && !eregi("<br",$content) && !eregi("<table",$content)) {
		$content=str_replace("\r\n\r\n", "<br>\r\n<br>\r\n", $content);
		$content=str_replace("\n\n", "<br>\n<br>\n", $content);
	}

	if ($owner!="") {
		$result = sql_query("select * from articulos where autor='$user' $CondPublico AND idartparent='$id' order by orden", $RAD_dbi);
		//$result = sql_query("select * from articulos where autor='$user' AND visible!='0' $CondPublico AND idartparent='$id' order by orden", $RAD_dbi);
	} else if (is_admin() || is_editor() || is_viewer()) {
		$result = sql_query("select * from articulos where idartparent='$id' order by orden", $RAD_dbi);
	} else {
		$result = sql_query("select * from articulos where idartparent='$id' $CondPublico order by orden", $RAD_dbi);
		//$result = sql_query("select * from articulos where idartparent='$id' AND visible!='0' $CondPublico order by orden", $RAD_dbi);
	}
	$arts="<ul>";
	while ($TMP_row = sql_fetch_array($result, $RAD_dbi)) {
		foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
		$arts.= "<li><a href=\"".$dir_root."index.php?V_dir=$V_dir&V_mod=$V_mod&headeroff=$headeroff&footeroff=$footeroff&id=".$TMP_row[id]."$Xowner\"> ".$TMP_row[nombre]." </a></li>";
	}
	if ($arts!="<ul>") $arts.="</ul><br><br>";
	else $arts="";
	$arts="";

	echo "<body bgcolor=\"#FFFFFF\" text=\"#000000\">
	<div class='articulo'>
	<h1>".$title."</h1>
		$content
		<br><br>";
	if ($noURL=="") echo "<center>".$arts._DEF_SITENAME."<br><a href=\""._DEF_URL."\">"._DEF_URL."</a><br>
		<a href=\""._DEF_URL."index.php?V_dir=$V_dir&V_mod=$V_mod&id=$id\">"._DEF_URL."index.php?V_dir=$V_dir&V_mod=$V_mod&id=$id</a></center>\n";

	echo "\n</div>\n<script>\nwindow.print();\n</script>\n";

	include_once("footer.php");
}
/////////////////////////////////////////////////////////////////////////////////////////
function traduce($contenido) {
	global $idproyecto, $RAD_dbi;
	$TMP_lang=getSessionVar("SESSION_lang");
	if ($idproyecto!="") {
		$TMP_res=sql_query("SELECT * FROM proyectos", $RAD_dbi);
		while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
			if ($TMP_row["proyecto"]!=$idproyecto && $TMP_row["proyecto_".$TMP_lang]!=$idproyecto) continue;
			foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
			$contenido=str_replace("<!-- proyecto -->",$TMP_row[proyecto],$contenido);
			$contenido=str_replace("<!-- textodonaciones -->",$TMP_row[textodonaciones],$contenido);
		}
	}
	return $contenido;
}
?>
