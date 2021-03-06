<?
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

//ini_set("magic_quotes_gpc", 1);
//ini_set("magic_quotes_runtime", 0);

include_once ("mainfile.php");
include_once ("functions.php");
include_once ("modules/$V_dir/datosusuario.php");
$user=base64_decode($HTTP_SESSION_VARS["SESSION_user"]);
$lang=getSessionVar("SESSION_lang");

if ($func=="insert"||$func=="delete"||$func=="update") { $cmd="edit"; $id=$V0_id; }

if ($func == "search_js") {
	class finfo {
		var $name;
		var $title;
		var $length;
		var $ilength;
		var $type;
		var $dtype;
		var $extra;
		var $vdefault;
		var $vonchange;
		var $column;
		var $help;
		var $browsable;
		var $browsedit;
		var $fieldedit;
		var $readonly;
		var $searchable;
		var $orderby;
		var $canbenull;
	};

	$idx=0;
	$fields[$idx] = new finfo;
	$findex["idartparent"] = $idx;
	$fields[$idx] -> name = "idartparent";
	$fields[$idx] -> title = "P&aacute;gina Padre";
	$fields[$idx] -> length = "25";
	$fields[$idx] -> ilength = "25";
	$fields[$idx] -> type = "string";
	$fields[$idx] -> dtype = "popupdb";
	$fields[$idx] -> extra = "articulos:id,idseccion:nombre";
	$fields[$idx] -> vdefault = "";
	$fields[$idx] -> vonchange = "";
	$fields[$idx] -> column = "";
	$fields[$idx] -> help = "";
	$fields[$idx] -> browsable = true;
	$fields[$idx] -> browsedit = true;
	$fields[$idx] -> fieldedit = false;
	$fields[$idx] -> readonly = false;
	$fields[$idx] -> searchable = true;
	$fields[$idx] -> orderby = true;
	$fields[$idx] -> canbenull = true;
	if (!isset($dbname)) $dbname = "";
	if ($dbname=="") $dbname = _DEF_dbname;
	include_once("header.php");
	OpenTable();
	include_once("modules/phpRAD/RAD_common.php");
	include_once("modules/phpRAD/RAD_sql.php");
	include("modules/phpRAD/normalRAD.php");
	include_once("modules/phpRAD/RAD_js.php");
	CloseTable();
	include_once("footer.php");
	return;
}
if (!is_user()) $CondPublico=" AND publico!='0'";
switch($cmd) {
	case "print":
		imprimearticulo($id);
		break;
	case "edit":
		if (is_user()) editaarticulo($id,"");
		break;
	case "new":
		if (is_user()) nuevoarticulo($id);
		break;
	case "save":
		if (is_user()) {
			if ($borra!="") borraarticulo($id);
			else guardaarticulo($id);
		}
		break;
	default:
		include_once ("header.php");
		//OpenTable();
        OpenDiv("centro");
		if ($id!="") {
            if ($id=="29") $id="32";
            if ($id=="30") $id="40";
            if ($id=="37") $id="52";
            if ($id=="2") $id="7";
            if ($id=="5") $id="13";
            if ($id=="69") header('Location: /index.php?V_dir=WWWVEREA2&V_mod=showDistribuidores&op=instalador');
            if ($id=="78") header('Location: /index.php?V_dir=WWWVEREA2&V_mod=showDistribuidores&op=distribuidor');
            muestraarticulo($id, $page);
		} else if ($owner!="") {
            listaarticulos("","","",0);
		}else if ($secid!="") {
            listaarticulos($secid,"","",0);
		}else if ($secindex!="") {
            listaarticulosindex($secindex, $showindex, $lastindex, $maxchars);
		}else{
             listasecciones();
        }
		//CloseTable();
        CloseDiv();
if ($V_mod=="articulosVEREA" && $_GET[id]!="") {
    $TMP_estilo=RAD_lookup("articulos","estilo","id",$_GET[id]);
    if ($TMP_estilo=="0") {
        echo'<div id="img-menu"><img src="files/'.$dbname.'/portada tejas.jpg"></div>';
    } else if ($TMP_estilo=="1") {
        echo'<div id="img-menu"><img src="files/'.$dbname.'/portada tejados.jpg"></div>';
    }
}

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
	global $RAD_dbi, $user, $SESSION_SID, $owner, $secid, $CondPublico, $V_dir, $V_mod, $A_ART_LISTADOS, $id, $color, $headeroff, $footeroff;

        $TMP_lang=getSessionVar("SESSION_lang");

	if ($owner=="" && $TMP_idartparent=="" && $TMP_idlis=="") {
		echo "\n<div id=\"menusecundario\">";
		if (is_admin() || is_editor() || is_viewer()) $result = sql_query("select * from articulossecciones where id='$TMP_secid'", $RAD_dbi);
		else $result = sql_query("select * from articulossecciones where id='$TMP_secid' AND visible='1'", $RAD_dbi);
		list($secname,$image) = sql_fetch_row($result, $RAD_dbi);
		echo "$secname";
		echo "\n</div>";
	} else if ($owner!="" && $TMP_idartparent=="" && $TMP_idlis=="") {
		echo "\n<div id=\"menusecundario\">";
		echo "Mis P&aacute;ginas";
		echo "\n</div>";
	}
	if ($owner!="") {
		if ($TMP_secid=="") $TMP_cmdSQL="select * from articulos where autor='$user' $CondPublico";
		else $TMP_cmdSQL="select * from articulos where autor='$user' and idseccion='$TMP_secid' $CondPublico";
	} else {
		if (is_admin() || is_editor() || is_viewer()) $TMP_cmdSQL="select * from articulos where idseccion='$TMP_secid' $CondPublico";
		else $TMP_cmdSQL="select * from articulos where idseccion='$TMP_secid' AND visible='1' $CondPublico";
	}
	if ($TMP_idartparent>0) $TMP_cmdSQL.=" AND idartparent='".$TMP_idartparent."'";
	if ($TMP_idlis>0) $TMP_cmdSQL.=" AND id='".$TMP_idlis."'";
	$TMP_cmdSQL.=" order by idseccion,orden";

	//if ($TMP_idartparent=="" &&  $TMP_idlis=="") echo "\n<table border=0 cellpadding=0 cellspacing=0 width=100%>";
	$TMP_cont=0;
	$result = sql_query($TMP_cmdSQL, $RAD_dbi);
        while ($TMP_art=sql_fetch_array($result, $RAD_dbi)) {
                $TMP_id=$TMP_art[id];
                $TMP_secid=$TMP_art[idseccion];
                $idartparent=$TMP_art[idartparent];
                if ($TMP_art["nombre_".$TMP_lang]!="") $title=$TMP_art["nombre_".$TMP_lang];
                else $title=$TMP_art[nombre];
                $content=$TMP_art[contenido];
                $counter= $TMP_art[paginas];
                $autor=$TMP_art[autor];
		//if ($color=="style='background-color:white'") $color="style='background-color:#E0E0E0'";
		//else $color="style='background-color:white'";
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
		//if ($TMP_level>0) echo str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $TMP_level);
        if ($id==$TMP_id) $TMP_class="class=\"activo2\"";
        else $TMP_class="class=\"noactivo2\"";
            $TMP_hermano=RAD_lookup("articulos","idartparent","id",$id);
            if ($id==$idartparent) {
                echo "<li class=\"submenu\">";
                echo "<a href=\"index.php?V_dir=$V_dir&V_mod=$V_mod&headeroff=$headeroff&footeroff=$footeroff&id=$TMP_id$Xowner\" $TMP_class>&middot; $title </a>";
            } elseif ($TMP_id==$TMP_hermano && $TMP_level>="1") {
                echo "<li class=\"itemmenu\">";
                echo "<a href=\"index.php?V_dir=$V_dir&V_mod=$V_mod&headeroff=$headeroff&footeroff=$footeroff&id=$TMP_id$Xowner\" $TMP_class>&middot; $title </a>";
            } elseif ($id==$TMP_id && $TMP_level>="1") {
                if ($id>=15 && $id<=20) echo "<li class=\"submenu\">";
                else echo "<li class=\"itemmenu\">";
                echo "<a href=\"index.php?V_dir=$V_dir&V_mod=$V_mod&headeroff=$headeroff&footeroff=$footeroff&id=$TMP_id$Xowner\" $TMP_class>&middot; $title </a>";
            } elseif ($TMP_hermano==$idartparent && $TMP_level>="1") {
                if ($id>=15 && $id<=20) echo "<li class=\"submenu\">";
                else echo "<li class=\"itemmenu\">";
                echo "<a href=\"index.php?V_dir=$V_dir&V_mod=$V_mod&headeroff=$headeroff&footeroff=$footeroff&id=$TMP_id$Xowner\" $TMP_class>&middot; $title </a>";

            }
            echo "</li>";
//		if (is_admin() || is_editor()) echo "<td $color><a href=\"index.php?V_dir=$V_dir&V_mod=$V_mod&cmd=print&id=$TMP_id$Xowner\" target=_blank><img src=\"images/print.gif\" border=\"0\" Alt=\""._PRINT."\"> "._PRINT."</a></td>";
		//else echo "<td $color></td>";
/*
		if (is_admin() || is_editor() || ($autor==$user && $autor!="" && $user!="") )
			echo "<td $color><a href=\"javascript:RAD_OpenW('index.php?V_dir=$V_dir&V_mod=$V_mod&cmd=edit&id=$TMP_id&headeroff=x&footeroff=x".$SESSION_SID."');\"><img src=\"images/edit.gif\" border=\"0\" Alt=\""._EDIT."\"> "._EDIT."</a></td>";
		else
			echo "<td $color>&nbsp;</td>";
		echo "</tr>\n";
*/
		$TMP_level2=$TMP_level+1;
		    listaarticulos($TMP_secid,"",$TMP_id,$TMP_level2);
	}
	if ($TMP_idartparent>0 || $TMP_idlis>0) return;
	if ($TMP_cont==0 && $TMP_idartparent=="" && $TMP_idlis=="") echo "<tr><td $color> No hay art&iacute;culos en esta Secci&oacute;n.</td></tr>";

	if ($owner!="" || $secid!="") echo "<tr><td align=center> [ <a href=\"index.php?V_dir=$V_dir&V_mod=$V_mod\">"._SECTIONS." </a> ]</td></tr>";
	if (is_user()) echo "<tr><td align=right><a href=\"javascript:RAD_OpenW('index.php?V_dir=$V_dir&V_mod=$V_mod&cmd=new&id=".$SESSION_SID."&headeroff=x&footeroff=x');\"> <img src='images/new.gif' border='0' alt='nueva p&aacute;gina'> Crear Nuevo </a></td></tr>";
	if (is_admin() || is_editor() || is_viewer()) {
		$result = sql_query("select id, idseccion, nombre, contenido, paginas, autor, visible from articulos where visible!='1' order by orden", $RAD_dbi);
		while (list($TMP_id, $TMP_secid2, $title, $content, $counter, $autor, $visible) = sql_fetch_row($result, $RAD_dbi)) {
		if ($color=="style='background-color:white'") $color="style='background-color:#E8E8E0'";
		else $color="style='background-color:white'";
			$nombreautor=RAD_lookup("usuarios","nombre","usuario",$autor);
			//if ($id==$TMP_id) $title="<b>".$title."</b>";
			if (is_viewer()) echo "<tr><td $color align=left> ".$TMP_x."<a href=\"index.php?V_dir=$V_dir&V_mod=$V_mod&id=".$TMP_id.$SESSION_SID."\"> $title </a> [".$nombreautor."]";
			else echo "<tr><td $color align=left> ".$TMP_x."<a href=\"index.php?V_dir=$V_dir&V_mod=$V_mod&cmd=edit&id=".$TMP_id.$SESSION_SID."\"> <img src='images/edit.gif' border='0' alt='edita p&aacute;gina'> $title </a> [".$nombreautor."]";
			if ($visible !="1") echo " <b>No visible</b> ";
			echo "</td></tr> ";
		}
	}
	if ($TMP_idartparent=="" && $TMP_idlis=="") echo "\n</table>\n";
}
/////////////////////////////////////////////////////////////////////////////////////////
function listaarticulosindex($TMP_secid, $TMP_showindex, $TMP_lastindex, $TMP_maxchars) {
	global $PHP_SELF, $RAD_dbi, $SESSION_SID, $CondPublico, $V_dir, $V_mod, $show;

	if ($TMP_maxchars=="" || $TMP_maxchars==0) $TMP_maxchars=250;
	if (!$TMP_lastindex>0) $TMP_lastindex=1;
	if (!$TMP_showindex>0) $TMP_showindex=1;
	if ($TMP_showindex>$TMP_lastindex) $TMP_showindex=$TMP_lastindex;

	$result=sql_query("select * from articulossecciones where id='$TMP_secid'", $RAD_dbi);
	$row=sql_fetch_array($result, $RAD_dbi);
	$secname=$row[nombre];
	echo "<table class='content'><tr><th class='content'><a class='content' href='$PHP_SELF?V_dir=$V_dir&V_mod=$V_mod&secid=$TMP_secid".$SESSION_SID."'>$secname</a></th></tr></table>\n";
	$TMP_cmdSQL="select * from articulos where idseccion='$TMP_secid' AND visible='1' $CondPublico";
	$TMP_cmdSQL.=" order by fechaalta DESC,orden ASC LIMIT 0,$TMP_lastindex";

	$result = sql_query($TMP_cmdSQL, $RAD_dbi);
	$TMP_cont=0;
	while ($row = sql_fetch_array($result, $RAD_dbi)) {
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
			if (file_exists($TMP_fotominicont)) $TMP_icono="<img ALT='".$content."' ALIGN=LEFT BORDER=1 HSPACE=2 src=\"".$TMP_fotominicont."\">\n";
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
	global $lang, $RAD_dbi, $user, $SESSION_SID, $owner, $CondPublico, $V_dir, $V_mod, $cmd, $headeroff, $footeroff, $dbname, $showdocs, $noeditamaqueta;
	if ($dbname=="") $dbname=_DEF_dbname;

	if ($cmd=="slide") {
		echo "<div align=right>";
		$result = sql_query("select id, idseccion, nombre, contenido, paginas, autor from articulos where id=$id ".$CondPublico, $RAD_dbi);
		list($TMP_id, $secid, $title, $content, $counter, $autor) = sql_fetch_row($result, $RAD_dbi);
		$result2 = sql_query("select id, nombre from articulossecciones where id=$secid", $RAD_dbi);
		list($secid, $secname) = sql_fetch_row($result2, $RAD_dbi);
		$result = sql_query("select * from articulos where idseccion=$secid ".$CondPublico." ORDER BY orden", $RAD_dbi);
		$barra="";
		while($TMP_row=sql_fetch_array($result, $RAD_dbi)) {
			if ($TMP_row["nombre_".$lang]!="") $TMP_row["nombre"]=$TMP_row["nombre_".$lang];
			if ($TMP_row["contenido_".$lang]!="") $TMP_row["contenido"]=$TMP_row["contenido_".$lang];
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
			if ($id!=$TMP_id && $barra!="" && $post=="") $post=" <a href=\"index.php?V_dir=$V_dir&V_mod=$V_mod&cmd=slide&id=$TMP_id&headeroff=$headeroff&footeroff=$footeroff\"><img src=\"images/right.gif\" border=\"0\" alt=\"".$title."\" title=\"".$title."\"></a>";
			$ant="<a href=\"index.php?V_dir=$V_dir&V_mod=$V_mod&cmd=slide&id=$TMP_id&headeroff=$headeroff&footeroff=$footeroff\"><img src=\"images/left.gif\" border=\"0\" alt=\"".$title."\" title=\"".$title."\"></a>";
			$TMP_select.="<option value='".$TMP_id."'".$SELECTED."> ->$title </option>\n";
		}
		if ($post=="") $post=" <img src='images/pixel.gif' height=11 width=11>";
		echo "<nobr><form action='index.php' name=slide method='get'>$barra<input type=hidden name=PHPSESSID value='$PHPSESSID'>";
		echo "<input type=hidden name=V_dir value='$V_dir'><input type=hidden name=footeroff value='$footeroff'>";
		echo "<input type=hidden name=headeroff value='$headeroff'><input type=hidden name=V_mod value='$V_mod'>";
		echo "<input type=hidden name=cmd value='$cmd'>";
		echo "<select NAME=id SINGLE onChange='javascript:form.submit();' style='font-size:9pt'>".$TMP_select."</select>";
		echo $post."</form></p></nobr></div>";
	}

	if (is_admin() || is_editor() || is_viewer()) $result = sql_query("select * from articulos where id=$id ".$CondPublico, $RAD_dbi);
	else if ($owner!="") $result = sql_query("select * from articulos where id=$id AND autor='$user' ".$CondPublico, $RAD_dbi);
	else $result = sql_query("select * from articulos where id=$id AND visible!='0' ".$CondPublico, $RAD_dbi);
	$TMP_row=sql_fetch_array($result, $RAD_dbi);
	if ($TMP_row["nombre_".$lang]!="") $TMP_row["nombre"]=$TMP_row["nombre_".$lang];
	if ($TMP_row["contenido_".$lang]!="") $TMP_row["contenido"]=$TMP_row["contenido_".$lang];
	$id=$TMP_row["id"];
	$secid=$TMP_row["idseccion"];
	$tile=$TMP_row["nombre"];
	$content=$TMP_row["contenido"];
	$counter=$TMP_row["paginas"];
	$autor=$TMP_row["autor"];
	$documentos=$TMP_row["documentos"];
	$title=str_replace("\r\n\r\n", "<br>\r\n<br>\r\n", $title);
	$title=str_replace("\n\n", "<br>\n<br>\n", $title);
	if (!eregi("<p",$content) && !eregi("<br",$content) && !eregi("<table",$content)) {
		$content=str_replace("\r\n\r\n", "<br>\r\n<br>\r\n", $content);
		$content=str_replace("\n\n", "<br>\n<br>\n", $content);
	}
	$fotoautor=datosusuario($autor);

	if ($secid!="") {
	    $result2 = sql_query("select id, nombre from articulossecciones where id=$secid", $RAD_dbi);
	    list($secid, $secname) = sql_fetch_row($result2, $RAD_dbi);
	    $words = sizeof(explode(" ", $content));
	}
//	echo "<center><b>$title</b></center>";

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
		$next_page = "<a href=\"index.php?V_dir=$V_dir&V_mod=$V_mod&id=$id&page=$next_pagenumber\">"._NEXT." ($next_pagenumber/$pageno) <img src=\"images/right.gif\" border=\"0\" alt=\""._NEXT."\"></a>";
	}

	if($page <= 1) {
		$previous_page = "";
	} else {
		$previous_pagenumber = $page - 1;
		$previous_page = "<a href=\"index.php?V_dir=$V_dir&V_mod=$V_mod&id=$id&page=$previous_pagenumber\">"._PREVIOUS." ($previous_pagenumber/$pageno) <img src=\"images/left.gif\" border=\"0\" alt=\""._PREVIOUS."\"></a>";
	}

	if ($owner!="") $Xowner="&owner=$owner";

	$result = sql_query("select count(*) from articulos where idartparent='$id'", $RAD_dbi);
	$TMP_row = sql_fetch_array($result, $RAD_dbi);
	$numhijos=$TMP_row[0];
	$idpadre=primerPadre($id);
	if ($idpadre<1) $idpadre="";
	if ($idpadre!="" || $numhijos>0) {
	    if ($idpadre>0) {
echo OpenDiv("menusecundario");
echo OpenDiv("cuerpo-menu");
echo "<ul>";
            listaarticulos($secid,$idpadre,"",0);
echo "</ul>";
CloseDiv();
CloseDiv();
	    }else{
echo OpenDiv("menusecundario");
echo OpenDiv("cuerpo-menu");
echo "<ul>";
            listaarticulos($secid,$id,"",0);
echo "</ul>";
CloseDiv();
CloseDiv();
        }
	}

    	$result= sql_query("SELECT * FROM articulosbloques where idarticulo='".$id."' AND visible='1' ORDER BY orden ASC",$RAD_dbi);
        while ($TMP_bloques=sql_fetch_array($result)) {
            $TMP_bloque=1;
	    switch ($TMP_bloques[tipobloque]) {
	        case 0:
            if ($TMP_bloques[img]!="") {
                if ($TMP_bloques[borde]==1) {
                    OpenDiv("img-menu_borde");
                }else{
                    OpenDiv("img-menu_margin");
                }
                if ($lang!="spanish" && $TMP_bloques["img_".$lang]!="") echo '<img src="files/'.$dbname.'/'.$TMP_bloques["img_".$lang].'">';
                else echo '<img src="files/'.$dbname.'/'.$TMP_bloques[img].'">';
            }elseif ($TMP_bloques[contenido]!="") {
                OpenDiv("img-menu");
                if ($lang!="spanish" && $TMP_bloques["contenido_".$lang]!="") echo $TMP_bloques["contenido_".$lang];
                else echo $TMP_bloques["contenido"];
            }else{
                OpenDiv("img-menu");
            }
		    CloseDiv();
            if (is_admin() || is_editor() || ($autor==$user && $autor!="" && $user!="") ) {
                echo "&nbsp;|&nbsp;<a href=\"javascript:RAD_OpenW('index.php?V_dir=$V_dir&V_mod=admarticulosbloques&func=edit&noeditamaqueta=x&par0=$TMP_bloques[idbloque]&headeroff=x&footeroff=x".$SESSION_SID."');\"><img src=\"images/edit.gif\" border=\"0\" Alt=\""._EDIT."\"> "._EDIT." $TMP_bloques[nombre]</a>";
            }
		    break;
		case 1:
            if ($TMP_bloques[img]!="") {
                OpenDiv("texto-centro");
                if ($lang!="spanish" && $TMP_bloques["img_".$lang]!="") echo '<img src="files/'.$dbname.'/'.$TMP_bloques["img_".$lang].'">';
                else echo '<img src="files/'.$dbname.'/'.$TMP_bloques[img].'">';
            }elseif ($TMP_bloques[contenido]!="") {
                OpenDiv("texto-centro");
                if ($lang!="spanish" && $TMP_bloques["contenido_".$lang]!="") echo $TMP_bloques["contenido_".$lang];
                else echo $TMP_bloques["contenido"];
            }else{
                OpenDiv("texto-centro");
            }
            CloseDiv();
            if (is_admin() || is_editor() || ($autor==$user && $autor!="" && $user!="") ) {
                echo "&nbsp;|&nbsp;<a href=\"javascript:RAD_OpenW('index.php?V_dir=$V_dir&V_mod=admarticulosbloques&func=edit&noeditamaqueta=x&par0=$TMP_bloques[idbloque]&headeroff=x&footeroff=x".$SESSION_SID."');\"><img src=\"images/edit.gif\" border=\"0\" Alt=\""._EDIT."\"> "._EDIT." $TMP_bloques[nombre]</a>";
            }
		    break;
		case 2:
            if ($TMP_bloques[img]!="") {
                if ($TMP_bloques[borde]==1) OpenDiv("img-centroP_borde");
                else OpenDiv("img-centroP");
                if ($lang!="spanish" && $TMP_bloques["img_".$lang]!="") echo '<img src="files/'.$dbname.'/'.$TMP_bloques["img_".$lang].'">';
                else echo '<img src="files/'.$dbname.'/'.$TMP_bloques[img].'">';
            }elseif ($TMP_bloques[contenido]!="") {
                OpenDiv("img-centroP");
                if ($lang!="spanish" && $TMP_bloques["contenido_".$lang]!="") echo $TMP_bloques["contenido_".$lang];
                else echo $TMP_bloques["contenido"];
            }else{
                OpenDiv("img-centroP");
            }
            CloseDiv();
            if (is_admin() || is_editor() || ($autor==$user && $autor!="" && $user!="") ) {
                echo "&nbsp;|&nbsp;<a href=\"javascript:RAD_OpenW('index.php?V_dir=$V_dir&V_mod=admarticulosbloques&func=edit&noeditamaqueta=x&par0=$TMP_bloques[idbloque]&headeroff=x&footeroff=x".$SESSION_SID."');\"><img src=\"images/edit.gif\" border=\"0\" Alt=\""._EDIT."\"> "._EDIT." $TMP_bloques[nombre]</a>";
            }
		    break;
        case 3:
            if ($TMP_bloques[img]!="") {
                if ($TMP_bloques[borde]==1) OpenDiv("img-centroG_borde");
                else OpenDiv("img-centroG");
                if ($lang!="spanish" && $TMP_bloques["img_".$lang]!="") echo '<img src="files/'.$dbname.'/'.$TMP_bloques["img_".$lang].'">';
                else echo '<img src="files/'.$dbname.'/'.$TMP_bloques[img].'">';
            }elseif ($TMP_bloques["contenido"]!="") {
                OpenDiv("articulosG");
                if ($lang!="spanish" && $TMP_bloques["contenido_".$lang]!="") echo $TMP_bloques["contenido_".$lang];
                else echo $TMP_bloques["contenido"];
            }else{
                OpenDiv("img-centroG");
            }
            CloseDiv();
            if (is_admin() || is_editor() || ($autor==$user && $autor!="" && $user!="") ) {
                echo "&nbsp;|&nbsp;<a href=\"javascript:RAD_OpenW('index.php?V_dir=$V_dir&V_mod=admarticulosbloques&func=edit&noeditamaqueta=x&par0=$TMP_bloques[idbloque]&headeroff=x&footeroff=x".$SESSION_SID."');\"><img src=\"images/edit.gif\" border=\"0\" Alt=\""._EDIT."\"> "._EDIT." $TMP_bloques[nombre]</a>";
            }
            break;
        case 4:
            if($_GET[op]=="" && (eregi("medidas",$TMP_bloques[nombre]) || eregi("accesorios",$TMP_bloques[nombre]))) {
              break;
            }
            if ($_GET[op]!="" && eregi("obra",$TMP_bloques[nombre])) {
              break;
            }
            if ($_GET[op]=="medidas" && eregi("accesorios",$TMP_bloques[nombre])) {
              break;
            }elseif ($_GET[op]=="accesorios" && eregi("medidas",$TMP_bloques[nombre])) {
              break;
            }
            if ($TMP_bloques[img]!="") {
                if ($TMP_bloques[borde]==1) OpenDiv("img-centroG_borde");
                else OpenDiv("articulosG");
                if ($lang!="spanish" && $TMP_bloques["img_".$lang]!="") echo '<img src="files/'.$dbname.'/'.$TMP_bloques["img_".$lang].'">';
                else echo '<img src="files/'.$dbname.'/'.$TMP_bloques[img].'">';
            }elseif ($TMP_bloques[contenido]!="") {
                OpenDiv("articulosG");
                if ($lang!="spanish" && $TMP_bloques["contenido_".$lang]!="") echo $TMP_bloques["contenido_".$lang];
                else echo $TMP_bloques["contenido"];
            }else{
                OpenDiv("img-centroG");
            }
            CloseDiv();
            if (is_admin() || is_editor() || ($autor==$user && $autor!="" && $user!="") ) {
                echo "&nbsp;|&nbsp;<a href=\"javascript:RAD_OpenW('index.php?V_dir=$V_dir&V_mod=admarticulosbloques&func=edit&noeditamaqueta=x&par0=$TMP_bloques[idbloque]&headeroff=x&footeroff=x".$SESSION_SID."');\"><img src=\"images/edit.gif\" border=\"0\" Alt=\""._EDIT."\"> "._EDIT." $TMP_bloques[nombre]</a>";
            }
            break;
        case 5:
            OpenDiv("libre");
            if ($lang!="spanish" && $TMP_bloques["contenido_".$lang]!="") echo $TMP_bloques["contenido_".$lang];
            else echo $TMP_bloques["contenido"];
            CloseDiv();
            if (is_admin() || is_editor() || ($autor==$user && $autor!="" && $user!="") ) {
                echo "&nbsp;|&nbsp;<a href=\"javascript:RAD_OpenW('index.php?V_dir=$V_dir&V_mod=admarticulosbloques&func=edit&noeditamaqueta=x&par0=$TMP_bloques[idbloque]&headeroff=x&footeroff=x".$SESSION_SID."');\"><img src=\"images/edit.gif\" border=\"0\" Alt=\""._EDIT."\"> "._EDIT." $TMP_bloques[nombre]</a>";
            }
            break;
        case 6:
            OpenDiv("libreGrande");
            if ($lang!="spanish" && $TMP_bloques["contenido_".$lang]!="") echo $TMP_bloques["contenido_".$lang];
            else echo $TMP_bloques["contenido"];
            CloseDiv();
            if (is_admin() || is_editor() || ($autor==$user && $autor!="" && $user!="") ) {
                echo "&nbsp;|&nbsp;<a href=\"javascript:RAD_OpenW('index.php?V_dir=$V_dir&V_mod=admarticulosbloques&func=edit&noeditamaqueta=x&par0=$TMP_bloques[idbloque]&headeroff=x&footeroff=x".$SESSION_SID."');\"><img src=\"images/edit.gif\" border=\"0\" Alt=\""._EDIT."\"> "._EDIT." $TMP_bloques[nombre]</a>";
            }
            break;
		default:
		    OpenDiv();
		    echo $TMP_bloques[contenido];
            CloseDiv();
    if (is_admin() || is_editor() || ($autor==$user && $autor!="" && $user!="") ) {
        echo "&nbsp;|&nbsp;<a href=\"javascript:RAD_OpenW('index.php?V_dir=$V_dir&V_mod=admarticulosbloques&func=edit&noeditamaqueta=x&par0=$TMP_bloques[idbloque]&headeroff=x&footeroff=x".$SESSION_SID."');\"><img src=\"images/edit.gif\" border=\"0\" Alt=\""._EDIT."\"> "._EDIT." $TMP_bloques[nombre]</a>";
    }
		    break;
	    }
	}
	if ($TMP_bloque=="") echo $fotoautor.$contentpages[$arrayelement];

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
					$TMP_enlace.="<br><a target=_blank href='files/".$dbname."/".$files[$k]."'>".$files[$k]."</a>";
				} else if ($ext4=="mpeg") {
					$TMP_file=substr($files[$k],0,strlen($files[$k])-4);
					system("mencoder files/".$dbname."/".$files[$k]." -o files/".$dbname."/".$TMP_file."flv -of lavf -oac mp3lame -lameopts br=32 -af lavcresample=22050 -srate 22050 -ovc lavc -lavcopts vcodec=flv:vbitrate=340:autoaspect:mbd=2:trell:v4mv -vf scale=320:240 -lavfopts i_certify_that_my_video_stream_does_not_use_b_frames");
					//system("ffmpeg -i files/".$dbname."/".$files[$k]." files/".$dbname."/".$TMP_file."flv");
					$TMP_enlace.="<br><a target=_blank href='files/".$dbname."/".$files[$k]."'>".$files[$k]."</a>";
				} else {
					$TMP_otrosdocumentos.="<A HREF=\"files/".$dbname."/".RAD_urlencodeFich($files[$k])."\" TARGET=_blank>".$filename."</A><br>\n";
				}
				if ($ext=="flv"||$ext=="mpg"||$ext=="avi"||$ext=="mp3"||$ext4=="mpeg") {
					$TMP_visor="<center><object classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' codebase='http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0' width='320' height='240' align='center'>
<PARAM NAME=allowFlashAutoInstall VALUE=true>
<param name=Flashvars value='url=../../files/".$dbname."/".$TMP_file."flv'>
<param name='allowScriptAccess' value='sameDomain' />
<param name='movie' value='images/flash/320x240.swf' />
<param name='quality' value='high' />
<param name='bgcolor' value='white' />
<embed src='images/flash/320x240.swf' swLiveConnect='true' Flashvars='url=../../files/".$dbname."/".$TMP_file."flv' quality='high' bgcolor='white' width='320' height='240' align='center' allowScriptAccess='sameDomain' type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/go/getflashplayer' />
</object></center>
";
				}
			}
		}
	}
	if ($showdocs!="") $TMP_enlace=RAD_showfield("file","",$documentos);
	if ($TMP_enlace!="" || $TMP_visor!="") echo $TMP_enlace.$TMP_visor;
	if ($TMP_otrosdocumentos!="") echo "<table border=0 cellpadding=0 cellspacing=0 width=100%><tr><td colspan=2><hr size=1 noshade></td></tr><tr><td valign=top><b>Ficheros:</b></td><td>".$TMP_otrosdocumentos."</td></tr></table>";

	if ($owner!="") {
		$result = sql_query("select * from articulos where autor='$user' AND visible!='0' $CondPublico AND idartparent='$id' order by orden", $RAD_dbi);
	} else if (is_admin() || is_editor() || is_viewer()) {
		$result = sql_query("select * from articulos where idartparent='$id' order by orden", $RAD_dbi);
	} else {
		$result = sql_query("select * from articulos where idartparent='$id' AND visible!='0' $CondPublico order by orden", $RAD_dbi);
	}
	$arts="<ul>";
	while ($TMP_row = sql_fetch_array($result, $RAD_dbi)) {
		$arts.= "<li><a href=\"index.php?V_dir=$V_dir&V_mod=$V_mod&headeroff=$headeroff&footeroff=$footeroff&id=".$TMP_row[id]."$Xowner\"> ".$TMP_row[nombre]." </a></li>";
	}
	if ($arts!="<ul>") $arts.="</ul><br><br>";
	else $arts="";
//xx	echo $arts;

	//echo "<center>$previous_page &nbsp;&nbsp; $next_page &nbsp;&nbsp; ";
	// echo "[ <a href=\"index.php?V_dir=$V_dir&V_mod=$V_mod&secid=$secid\">"._BACKTO." $secname</a> | "
	// ."<a href=\"index.php?V_dir=$V_dir&V_mod=$V_mod\">"._SECINDEX."</a> ]";
	// echo "($words "._TOTALWORDS.")<br>($counter "._READS.") &nbsp;&nbsp;";

//	if ($cmd=="") echo "<a href=\"index.php?V_dir=$V_dir&V_mod=$V_mod&cmd=print&id=$id$Xowner\" target=_blank><img src=\"images/print.gif\" border=\"0\" Alt=\""._PRINT."\"> "._PRINT." </a>";
	if ($cmd=="print") echo "\n<script>\nwindow.print();\n</script>\n";

	$esmaqueta=false;
	if (ereg("<!--maqueta",$content)) $esmaqueta=true;

	if (is_admin() || is_editor() || ($autor==$user && $autor!="" && $user!="") ) {
		echo "&nbsp;|&nbsp;<a href=\"javascript:RAD_OpenW('index.php?V_dir=$V_dir&V_mod=admarticulos&func=edit&par0=$id&headeroff=x&footeroff=x".$SESSION_SID."');\"><img src=\"images/edit.gif\" border=\"0\" Alt=\""._EDIT."\"> "._EDIT." Articulo</a>";
		if ($esmaqueta==true && $noeditamaqueta=="") echo "&nbsp;|&nbsp;<a href=\"javascript:RAD_OpenW('index.php?V_dir=$V_dir&V_mod=admarticulos&func=edit&par0=$id&headeroff=x&footeroff=x".$SESSION_SID."');\"><img src=\"images/edit.gif\" border=\"0\" Alt=\""._EDIT." con Maqueta\"> "._EDIT." con Maqueta</a>";
	}

//	echo "<br></center>";

    if ($id=="31" || $id=="38") {
        $TMP_thumbs="";
        $img=0;
        $result = sql_query("select * from galeria where activo='1' order by orden", $RAD_dbi);
        while ($TMP_row = sql_fetch_array($result, $RAD_dbi)) {
              $TMP_row[img]=str_replace("\n", "", $TMP_row[img]);
              $TMP_row[img]=str_replace("\r", "", $TMP_row[img]);
              if ($TMP_row[idgaleria]==$_GET[img]) {
                  OpenDiv("imgfoto");
                      $TMP_fileresize=RAD_resizeImg("files/".$dbname."/".$TMP_row[img], "440", "290");
                      echo '<img src="'.$TMP_fileresize.'" title="'.$TMP_row[title].'">';
                  CloseDiv();
                  OpenDiv("texto_galeria");
                  if ($TMP_row[modelo]!="") {
                        echo '<p><p align="left"><strong>Modelo de teja: </strong>'.$TMP_row[modelo].'</p>
<p align="left"><strong>Obra: </strong>'.$TMP_row[obra].'</p>
<p align="left"><strong>Lugar: </strong>'.$TMP_row[lugar].'</p>
<p align="left"><strong>Instalador: </strong>'.$TMP_row[instalador].'</p>
<p>&nbsp;&nbsp;</p>
<p><img width="16" height="17" src="/images/pdf.gif" alt="Ficha de Obra" /><a href="files/'.$dbname.'/'.$TMP_row[file].'"> Ficha de obra</a></p>
<p align="left">&nbsp;</p></p>';
		  }else{
                      	echo "<p>$TMP_row[txt]</p>";
                  }
                  CloseDiv();

              }elseif($_GET[img]==""){
                  if ($img<=0) {
                    $TMP_fileresize=RAD_resizeImg("files/".$dbname."/".$TMP_row[img], "440", "290");
                    OpenDiv("imgfoto");
                        echo '<img src="'.$TMP_fileresize.'" title="'.$TMP_row[title].'">';
                    CloseDiv();
                    OpenDiv("texto_galeria");
                  if ($TMP_row[modelo]!="") {
                        echo '<p><p align="left"><strong>Modelo de teja: </strong>'.$TMP_row[modelo].'</p>
<p align="left"><strong>Obra: </strong>'.$TMP_row[obra].'</p>
<p align="left"><strong>Lugar: </strong>'.$TMP_row[lugar].'</p>
<p align="left"><strong>Instalador: </strong>'.$TMP_row[instalador].'</p>
<p>&nbsp;&nbsp;</p>
<p><img width="16" height="17" src="/images/pdf.gif" alt="Ficha de Obra" /><a href="files/'.$dbname.'/'.$TMP_row[file].'"> Ficha de obra</a></p>
<p align="left">&nbsp;</p></p>';
                  }else{
                        echo "<p>$TMP_row[txt]</p>";
                  }
                    CloseDiv();
                      $img++;
                  }
              }
            $TMP_fileresize=RAD_resizeImg("files/".$dbname."/".$TMP_row[img], "60", "60");
            $TMP_thumbs.='<div id="imgthumb"><a href="index.php?V_dir='.$V_dir.'&V_mod='.$V_mod.'&id='.$id.'&img='.$TMP_row[idgaleria].'"><img src="'.$TMP_fileresize.'" title="'.$TMP_row[title].'"></a></div>';
          }
        OpenDiv("galeria");
            echo $TMP_thumbs;
        CloseDiv();



    }

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
	global $RAD_dbi, $HTTP_SESSION_VARS, $headeroff, $footeroff, $user, $owner, $CondPublico, $V_dir, $V_mod, $headeroff, $footeroff;

	$headeroff="x";
	$footeroff="x";
	include_once("header.php");

	$tema=$HTTP_SESSION_VARS["SESSION_theme"];
	if ($tema!="") $tema="<LINK REL=\"StyleSheet\" HREF=\"themes/$tema/style.css\" TYPE=\"text/css\">";

	if (is_admin() || is_editor() || is_viewer()) $result = sql_query("select nombre, contenido, autor from articulos where id=$id ".$CondPublico, $RAD_dbi);
	else if ($owner!="") $result = sql_query("select nombre, contenido, autor from articulos where id=$id AND autor='$user' ".$CondPublico, $RAD_dbi);
	else $result = sql_query("select nombre, contenido, autor from articulos where id=$id AND visible!='0' ".$CondPublico, $RAD_dbi);
	list($title, $content, $autor) = sql_fetch_row($result, $RAD_dbi);
	$title=str_replace("\r\n\r\n", "<br>\r\n<br>\r\n", $title);
	$title=str_replace("\n\n", "<br>\n<br>\n", $title);
	if (!eregi("<p",$content) && !eregi("<br",$content) && !eregi("<table",$content)) {
		$content=str_replace("\r\n\r\n", "<br>\r\n<br>\r\n", $content);
		$content=str_replace("\n\n", "<br>\n<br>\n", $content);
	}
	$fotoautor=datosusuario($autor);

//	echo "<body bgcolor=\"#FFFFFF\" text=\"#000000\">
//		<table border=\"0\"><tr><td>
//		<table border=\"0\" width=\"640\" cellpadding=\"0\" cellspacing=\"1\" bgcolor=\"#000000\"><tr><td>
//		<table border=\"0\" width=\"640\" cellpadding=\"20\" cellspacing=\"1\" bgcolor=\"#FFFFFF\">
//		<tr><td>$content</td></tr></table></td></tr></table>
//		<br><br><center>"._DEF_SITENAME."<br><a href=\""._DEF_URL."\">"._DEF_URL."</a><br>
//		<a href=\""._DEF_URL."index.php?V_dir=$V_dir&V_mod=$V_mod&id=$id\">"._DEF_URL."index.php?V_dir=$V_dir&V_mod=$V_mod&id=$id</a></center>
//		</td></tr></table>\n";

	if (eregi("\\$"."fotoautor", $content)) {
		$content=str_replace("\$fotoautor",$fotoautor,$content);
		$fotoautor="";
	}

	if ($owner!="") {
		$result = sql_query("select * from articulos where autor='$user' AND visible!='0' $CondPublico AND idartparent='$id' order by orden", $RAD_dbi);
	} else if (is_admin() || is_editor() || is_viewer()) {
		$result = sql_query("select * from articulos where idartparent='$id' order by orden", $RAD_dbi);
	} else {
		$result = sql_query("select * from articulos where idartparent='$id' AND visible!='0' $CondPublico order by orden", $RAD_dbi);
	}
	$arts="<ul>";
	while ($TMP_row = sql_fetch_array($result, $RAD_dbi)) {
		$arts.= "<li><a href=\"index.php?V_dir=$V_dir&V_mod=$V_mod&headeroff=$headeroff&footeroff=$footeroff&id=".$TMP_row[id]."$Xowner\"> ".$TMP_row[nombre]." </a></li>";
	}
	if ($arts!="<ul>") $arts.="</ul><br><br>";
	else $arts="";
	$arts="";

	echo "<body bgcolor=\"#FFFFFF\" text=\"#000000\">
		$fotoautor
		$content
		<br><br><center>".$arts._DEF_SITENAME."<br><a href=\""._DEF_URL."\">"._DEF_URL."</a><br>
		<a href=\""._DEF_URL."index.php?V_dir=$V_dir&V_mod=$V_mod&id=$id\">"._DEF_URL."index.php?V_dir=$V_dir&V_mod=$V_mod&id=$id</a></center>\n";

	echo "\n<script>\nwindow.print();\n</script>\n";

	include_once("footer.php");

}
/////////////////////////////////////////////////////////////////////////////////////////
function nuevoarticulo($id) {
	global $RAD_dbi, $user, $SESSION_SID, $CondPublico, $V_dir, $V_mod;

	include_once("header.php");
	OpenTable();

	if (!is_user()) {
		echo "<b><center>Se necesita ser usuario para acceder a esta funci&oacute;n</b></center>";
		CloseTable();
		die;
	}
	if ($id=="") {
		echo "<b><center>Selecciona Maqueta para crear P&aacute;gina</b></center><br><ul><table border=0>";
		$result = sql_query("select * from articulos where visible='2' ".$CondPublico, $RAD_dbi);
		while($TMP_row = sql_fetch_array($result, $RAD_dbi)) {
			echo "<tr><td>".$TMP_row[nombre]."</td>";
			echo "<td><a href=\"index.php?V_dir=$V_dir&V_mod=$V_mod&id=".$TMP_row[id]."$SESSION_SID\">Ver Maqueta</a></td>";
			echo "<td><a href=\"javascript:RAD_OpenW('index.php?V_dir=$V_dir&V_mod=$V_mod&cmd=new&id=".$TMP_row[id].$SESSION_SID."&headeroff=x&footeroff=x');\"> <img src='images/new.gif' border='0' alt='nueva p&aacute;gina'> Crear Nueva P&aacute;gina </a></td></tr>";
		}
		echo "<tr><td>P&aacute;gina sin maqueta (contenido libre)</td>";
		echo "<td>&nbsp;</td>";
		echo "<td><a href=\"javascript:RAD_OpenW('index.php?V_dir=$V_dir&V_mod=$V_mod&cmd=edit&id=&headeroff=x&footeroff=x".$SESSION_SID."');\"> <img src='images/new.gif' border='0' alt='nueva p&aacute;gina'> Crear Nueva P&aacute;gina </a></td></tr>";
		echo "</table>";
	} else {
		editaarticulo("", $id);
	}

	CloseTable();
	include_once("footer.php");

}
/////////////////////////////////////////////////////////////////////////////////////////
function editaarticulo($id, $maquetaid) {
	global $RAD_dbi, $user, $dbname, $CondPublico, $V_dir, $V_mod, $cmd, $PHPSESSID, $PHP_SELF, $noeditamaqueta, $func;

	if ($dbname=="") $dbname=_DEF_dbname;
	include_once ("header.php");
	OpenTable();

	if (!is_user()) {
		echo "<b><center>Se necesita ser usuario para acceder a esta funci&oacute;n</b></center>";
		CloseTable();
		die;
	}

	$visible="0";
	$publico="1";
	$idartparent="";
	if ($id!="") {
		$result = sql_query("select * from articulos where id=$id ".$CondPublico, $RAD_dbi);
		$TMP_row = sql_fetch_array($result, $RAD_dbi);
		$id=$TMP_row[id];
		$idseccion=$TMP_row[idseccion];
		$title=$TMP_row[nombre];
		$content=$TMP_row[contenido];
		$visible=$TMP_row[visible];
		$publico=$TMP_row[publico];
		$orden=$TMP_row[orden];
		$autor=$TMP_row[autor];
		$idartparent=$TMP_row[idartparent];
		if (!is_admin() && !is_editor() && ($autor!=$user || $user=="") ) {
			echo "<b><center>Solo el autor puede editar la p&aacute;gina</b></center>";
			CloseTable();
			die;
		}
	} else if ($maquetaid!="") {
		$result = sql_query("select * from articulos where id=$maquetaid AND visible='2' ".$CondPublico, $RAD_dbi);
		$TMP_row = sql_fetch_array($result, $RAD_dbi);
		$id="";
		$idseccion="";
		$title=$TMP_row[nombre];
		$content=$TMP_row[contenido];
		$visible="1";
		$publico="0";
		$idartparent="";
		$orden="";
	}

	$esmaqueta=false;
	if (ereg("<!--maqueta",$content) && $noeditamaqueta=="") $esmaqueta=true;
//	if ($maquetaid=="" && $id>0 && ($cmd=="new" || $cmd=="edit")) {
	if ($func=="update") $esmaqueta=false;
	if ($esmaqueta==false && $maquetaid=="" && ($cmd=="new" || $cmd=="edit")) {
		global $HTTP_POST_FILES, $_REQUEST;
		if (count($_REQUEST)>0) {
		  foreach ($_REQUEST as $TMP_key=>$TMP_val) {
			if (!is_array($TMP_val)) {
				if ($V_mod!="filemanager" && $V_mod!="filemanager.php" && $V_mod!="personal" && $V_mod!="discovirtual" && $V_dir!="phpMyAdmin" && $V_dir!="phpGenRAD" && $V_dir!="admin") {
					$TMP_val = eregi_replace("<script", "< script", $TMP_val); // Para evitar Cross Site Scripting en modulos no filemanager de admin
					$TMP_val = eregi_replace("javascript:", "javascript :", $TMP_val); // Para evitar Cross Site Scripting en modulos no filemanager de admin
					$_REQUEST[$TMP_key]=$TMP_val;
				}
				${$TMP_key} = $TMP_val;
				if (get_magic_quotes_gpc()) ${$TMP_key} = stripslashes ($TMP_val);
				global ${$TMP_key};
			} else {
				foreach ($TMP_val as $TMP_key2=>$TMP_val2) {
					if (get_magic_quotes_gpc()) $TMP_val2 = stripslashes ($TMP_val2);
					${$TMP_key}[$TMP_key2] = $TMP_val2;
					global ${$TMP_key};
				}
			}
		  }
		}
		if (count($HTTP_POST_FILES)>0) {
			foreach ($HTTP_POST_FILES as $TMP_key=>$TMP_val) {
				if (is_array($TMP_val)) {
					foreach ($TMP_val as $TMP_key2=>$TMP_val2) {
						if (get_magic_quotes_gpc()) $TMP_val2 = stripslashes ($TMP_val2);
						if (trim($TMP_val2=="")) continue;
						global ${$TMP_key."_".$TMP_key2};
						${$TMP_key."_".$TMP_key2}=$TMP_val2;
						if ($TMP_key2=="error" && $TMP_val2>0 && $TMP_val2!=4) {
							error("Error al crear fichero en el servidor ($TMP_val2).");
						}
						if ($TMP_key2=="tmp_name") {
							global ${$TMP_key};
							${$TMP_key}=$TMP_val2;
						}
					}
				}
			}
		}
		global $V0_id, $par0, $func, $menuoff, $headeroff, $footeroff, $blocksoff, $RAD_buttonDelete, $RAD_buttonDuplicate, $subfunc, $_REQUEST;
		if ($func=="") $func=$cmd;
		if ($id>0) $par0=$id;
		if (!$par0>0 && $func=="edit") $func="new";
		$menuoff="x"; $headeroff="x"; $footeroff="x"; $blocksoff="x";
		$RAD_buttonDelete="x"; $RAD_buttonDuplicate="x"; $subfunc="browse";
		foreach($_REQUEST as $TMP_key=>$TMP_val) { if (get_magic_quotes_gpc()) $TMP_val=stripslashes ($TMP_val); global ${$TMP_key}; ${$TMP_key}=$TMP_val; }
		global $owner;
		$owner="x";
		include_once ("modules/$V_dir/admarticulos.common.php");
		include_once ("modules/$V_dir/admarticulos.php");
		return;
	}

	include_once ("functions.php");

	$formulario .= "<form action=\"index.php\" name=F METHOD=\"post\" ENCTYPE='multipart/form-data'>\n";
	$formulario .= "<input type=hidden name=PHPSESSID value='$PHPSESSID'>";
	$formulario .= "<input type=hidden name=V_dir value='$V_dir'>";
	$formulario .= "<input type=hidden name=V_mod value='$V_mod'>";
	$formulario .= "<input type=hidden name=cmd value='save'>";
	$formulario .= "<input type=hidden name=id value='$id'>";
	$formulario .= "<tr><TD width=30% class=detailtit>Secci&oacute;n : </td><TD class=detail>".RAD_editfield("V0_idseccion", "plistdb", 12, 12, "articulossecciones:id:nombre", "", false, $idseccion, "")."</td></tr>";
	$formulario .= "<tr><td class=detailtit>P&aacute;gina Padre : </td><td class=detail>".RAD_editfield("V0_idartparent", "popupdb", 45, 45, "articulos:id:nombre", "", true, $idartparent, "")."</td></tr>";
	$formulario .= "<tr><td class=detailtit>T&iacute;tulo : </td><td class=detail>".RAD_editfield("V0_title", "string", 64000, 60, "", "", true, $title, "")."</td></tr>";
	$formulario .= "<tr><td class=detailtit>Autor : </td><td class=detail>".$user."</td></tr>";
	if (is_editor_propio() || is_editor() || is_admin())
		$formulario .= "<tr><td class=detailtit>Visible : </td><td class=detail>".RAD_editfield("V0_visible", "rlist", 1, 1, "0:Oculto,1:Visible,2:Maqueta", "", false, $visible, "")."</td></tr>";
	$formulario .= "<tr><td class=detailtit>Orden : </td><td class=detail>".RAD_editfield("V0_orden", "string", 4, 4, "", "", true, $orden, "")."</td></tr>";
	$formulario .= "<tr><td class=detailtit>P&uacute;blico : </td><td class=detail>".RAD_editfield("V0_publico", "rlist", 1, 1, "1:Si,0:No", "", false, $publico, "")."</td></tr>";

	$formularioContent .= RAD_editfield("V0_content", "text", 64000, "120x50", "", "", true, $content, "");
	//$formularioContent .= RAD_editfield("V0_content", "texthtml", 64000, "120x50", "", "", true, $content, "");

	$formularioEnd .= "<center><input type=submit name=guarda value='"._DEF_NLSSave."' ACCESSKEY='S' TITLE='ALT+S'>";
	if ($id!="") $formularioEnd .= "&nbsp;&nbsp;<input type=submit name=duplica value='"._DEF_NLSDuplicate."' ACCESSKEY='X' TITLE='ALT+X'>";
	if ($id!="") $formularioEnd .= "&nbsp;&nbsp;<input type=submit name=borra value='"._DEF_NLSDeleteString."' ACCESSKEY='D' TITLE='ALT+D'>";
	$formularioEnd .= "&nbsp;&nbsp;<INPUT ACCESSKEY='R' TITLE='ALT+R' TYPE=RESET class=button VALUE='"._DEF_NLSClearAll."'>";
	$formularioEnd .= "</center></form></p>";

	echo "<table class=detail>".$formulario."<tr><td class=detail colspan=2>";
	if ($esmaqueta==true && $visible!='2') {
		echo "<input type=hidden name=maqueta value='1'><hr size=1 noshade>";
		editaMaqueta($content);
		//echo "<hr size=1 noshade>";
	} else {
		echo "<input type=hidden name=maqueta value='0'>";
		echo $formularioContent;
	}
	echo "</td></tr></table>".$formularioEnd;

	CloseTable();
	include_once ("footer.php");
}
/////////////////////////////////////////////////////////////////////////////////////////
function borraarticulo($id) {
	global $RAD_dbi, $user, $V0_idseccion, $V0_title, $V0_content, $V0_orden, $V0_publico, $V0_visible, $duplica, $maqueta;
	if ($id!="" && $user!="") {
		if (is_admin()) $cmdSQL = "DELETE FROM articulos WHERE id=$id";
		else $cmdSQL = "DELETE FROM articulos WHERE id=$id and autor='".$user."'";
		RAD_printLog($cmdSQL);
		$result = sql_query($cmdSQL, $RAD_dbi);

		echo "<script type=text/javascript>\nalert('P��&#189;��&#189;&#189;gina Borrada');\nwindow.opener.location.reload();\nwindow.close();\n</script>\n";
		die;
	}
}
/////////////////////////////////////////////////////////////////////////////////////////
function guardaarticulo($id) {
	global $RAD_dbi, $user, $V0_idseccion, $V0_idartparent, $V0_title, $V0_content, $V0_orden, $V0_publico, $V0_visible, $duplica, $maqueta, $CondPublico, $HTTP_SESSION_VARS;

//	include_once ("header.php");
//	OpenTable();
	if (!is_editor_propio() && !is_editor() && !is_admin()) $V0_visible='0';

	if ($maqueta=="1") $V0_content=componeMaqueta();
	if ($duplica!="") $id="";
	$TMP_user=base64_decode($HTTP_SESSION_VARS["SESSION_user"]);
	$TMP_user.=" [".RAD_lookup("usuarios","nombre","usuario",$TMP_user)."]";
	if ($id!="") {
		$result = sql_query("select * from articulos where id=$id ".$CondPublico, $RAD_dbi);
		$TMP_row = sql_fetch_array($result, $RAD_dbi);
		$autor=$TMP_row[autor];
		$oldvisible=$TMP_row[visible];
		$oldpublico=$TMP_row[publico];
		if (!is_user()) {
			echo "<b><center>Se necesita ser usuario para acceder a esta funci&oacute;n</b></center>";
			CloseTable();
			die;
		}
		if (!is_admin() && !is_editor() && ($autor!=$user || $user=="") ) {
			echo "<b><center>Solo el autor puede editar la p&aacute;gina</b></center>";
			CloseTable();
			die;
		}
		$cmdSQL="UPDATE articulos set idseccion='$V0_idseccion', idartparent='$V0_idartparent', nombre=".converttosql($V0_title).", contenido=".converttosql($V0_content).",visible=".converttosql($V0_visible).", orden=".converttosql($V0_orden).", publico=".converttosql($V0_publico)." where id='$id'";
		if (($oldvisible=="0"||$oldpublico=="0"||$oldvisible==""||$oldpublico=="") && ($V0_publico!="0" && $V0_visible!="0")) {
			$TMP_mensaje="El usuario $TMP_user ha modificado el articulo ($id) con Titulo '$V0_title' como visible y publico.\nPara verlo se puede visitar ";
			$TMP_mensaje.="<a href='"._DEF_URL."index.php?V_dir=contents&V_mod=articulos&id=$id'>";
			$TMP_mensaje.=_DEF_URL."index.php?V_dir=contents&V_mod=articulos&id=$id</a>\n";
			$TMP_mensaje=str_replace("'","\"",$TMP_mensaje);
			//RAD_sendMail(_DEF_ADMINMAIL,"","","","Articulo puesto como Visible y Publico",$TMP_mensaje,"");
		}
	} else {
		$cmdSQL="INSERT into articulos SET idseccion='$V0_idseccion', idartparent=".converttosql($V0_idartparent).", nombre=".converttosql($V0_title).", contenido=".converttosql($V0_content).", paginas='0', visible=".converttosql($V0_visible).", orden=".converttosql($V0_orden).", autor=".converttosql($user).", publico=".converttosql($V0_publico).", fechapubli=NULL , fechaalta=".converttosql(time()).", fechabaja=NULL, observaciones=NULL";
		$TMP_id=sql_insert_id($RAD_dbi);
		if ($V0_publico!="0" && $V0_visible!="0") {
			$TMP_mensaje="El usuario $TMP_user ha creado el articulo con Titulo '$V0_title' como visible y publico.\nPara verlo se puede visitar ";
			$TMP_mensaje.="<a href='"._DEF_URL."index.php?V_dir=contents&V_mod=articulos&id=$TMP_id'>";
			$TMP_mensaje.=_DEF_URL."index.php?V_dir=contents&V_mod=articulos&id=$TMP_id</a>\n";
			$TMP_mensaje=str_replace("'","\"",$TMP_mensaje);
			RAD_sendMail(_DEF_ADMINMAIL,"","","","Articulo puesto como Visible y Publico",$TMP_mensaje,"");
		}
	}
	$cmdSQL=RAD_printLog($cmdSQL);
	sql_query($cmdSQL, $RAD_dbi);
	echo "<script type=text/javascript>\nalert('P��&#189;��&#189;&#189;gina guardada');\nwindow.opener.location.reload();\nwindow.close();</script>\n";

//	CloseTable();
//	include_once ("footer.php");
}
/////////////////////////////////////////////////////////////////////////////////////////
function editamaqueta($content) {

$startvar="<!--start";
$endvar="<!--end";

$formulario="";
$i=0;
while(strlen($content)>0) {
	$posv=strpos($content, $startvar);
	$variable="";
	$prefijo="";
	$dimension="";
	if ($posv > -1) {
		$prefijo=substr($content,0,$posv);
		$content=substr($content,$posv);
		$finposv=strpos($content, ">");
		$dimension=trim(substr($content,9,$finposv-11));

		$prefijo.=substr($content,0,$finposv+1);
		$content=substr($content,$finposv+1);
		$posv=strpos($content, $endvar);
		if ($posv > -1) {
			$variable=substr($content,0,$posv);
			$content=substr($content,$posv);
		}

		if ($dimension=="") {
			$cols = 60;
			$rows = 20;
			$size="";
		}
		if ($dimension=="img") {	// Campo imagen
// Recoger fuente y permitir sustituirlo, y pedir tama��&#189;��&#189;&#189;o si es necesario
		    $src=extraeValor($variable, "src");
		    $width=extraeValor($variable, "width");
		    $height=extraeValor($variable, "height");

		    $variable=str_replace("valign", "vvlign", $variable);
		    $valign=extraeValor($variable, "vvlign");
		    $align=extraeValor($variable, "align");
		    $alt=extraeValor($variable, "alt");

		    $formulario.=$prefijo."<input type=hidden name=var$i value='".urlencode($prefijo)."'><input type=hidden name=tvar$i value=h>";
		    $i++;
		    if ($src!="") $formulario.="Imagen actual <a href='$src' target=_blank>$src</a>";
		    $formulario.=" Imagen nueva:<input type=file name=var$i><input type=hidden name=tvar$i value='i'> ";
		    $formulario.="<input type=hidden size=3 name=oldsrc$i value='$src'>";
		    $formulario.="Alto:<input type=text size=3 name=alto$i value='$height'> Ancho:<input type=text size=3 name=ancho$i value='$width'> ";
		    $formulario.="Align:<input type=text size=4 name=align$i value='$align'> Valign:<input type=text size=4 name=valign$i value='$valign'> ";
		    $formulario.="Texto:<input type=text size=10 name=alt$i value='$alt'>";
		    $i++;
		} else {
		    $posx=strpos($dimension, "x");
		    if ($posx>-1) {
			$size = split("x", $dimension);
			if (!isset($size[0])) $size[0] = 60;
			if (!isset($size[1])) $size[1] = 20;
			$cols = $size[0];
			$rows = $size[1];
			$size="";
		    } else {
			$size=(int)$dimension;
		    }
		    $formulario.=$prefijo."<input type=hidden name=var$i value='".urlencode($prefijo)."'><input type=hidden name=tvar$i value=h>";
		    $i++;
		    if ($size>0) $formulario.="<input type=text size=$size name=var$i value='".$variable."'><input type=hidden name=tvar$i value=t>";
		    else $formulario.="<textarea rows=$rows cols=$cols name=var$i>".$variable."</textarea><input type=hidden name=tvar$i value=t>";
		    $i++;
		}
	}

	if ($prefijo=="") {
		$prefijo=$content;
		$content="";
		$formulario.=$prefijo."<input type=hidden name=var$i value='".urlencode($prefijo)."'><input type=hidden name=tvar$i value=h>";
	}
} // endwhile

echo $formulario;
}
/////////////////////////////////////////////////////////////////////////////////////////
function componemaqueta() {
	global $HTTP_POST_VARS, $HTTP_GET_VARS, $user;

$i=0;
$content="";
while($HTTP_POST_VARS["tvar".$i]!="") {
	if ($HTTP_POST_VARS["tvar".$i]=="h") $content.=urldecode($HTTP_POST_VARS["var".$i]);
	if ($HTTP_POST_VARS["tvar".$i]=="t") $content.=$HTTP_POST_VARS["var".$i];
	if ($HTTP_POST_VARS["tvar".$i]=="i") {
		$content.="<img src='";
		global ${"var".$i."_name"},${"var".$i};
		$TMP_name=${"var".$i."_name"};
		$TMP_file=${"var".$i};
		if (${"var".$i."_name"} !=""  || ${"var".$i."_name"}!="") {
			$current_date = getdate();
			$TMP_name = RAD_nameSecure($TMP_name);
			$FechaHoraSis=$current_date["year"]."-".$current_date["mon"]."-".$current_date["mday"]."-".$current_date["hours"]."-".$current_date["minutes"]."-".$current_date["seconds"];
			$nameFich=$FechaHoraSis."_".$user."_".$TMP_name;
			$nameFich=str_replace(" ", "_", $nameFich);
			$content.="images/articulos/".$nameFich."'";
			if (!copy($TMP_file,"images/articulos/".$nameFich)) {
				echo "Error al grabar la imagen.";
				die;
			}
			chmod("images/articulos/".$nameFich,0644);
		} else {
			$content.=$HTTP_POST_VARS["oldsrc".$i]."'";
		}

		if ($HTTP_POST_VARS["ancho".$i]!="") $content.=" width='".$HTTP_POST_VARS["ancho".$i]."'";
		if ($HTTP_POST_VARS["alto".$i]!="") $content.=" height='".$HTTP_POST_VARS["alto".$i]."'";
		if ($HTTP_POST_VARS["align".$i]!="") $content.=" align='".$HTTP_POST_VARS["align".$i]."'";
		if ($HTTP_POST_VARS["valign".$i]!="") $content.=" valign='".$HTTP_POST_VARS["valign".$i]."'";
		if ($HTTP_POST_VARS["alt".$i]!="") $content.=" alt='".$HTTP_POST_VARS["alt".$i]."'";
		$content.=">";
	}
	$i++;
} // endwhile

return $content;
}
/////////////////////////////////////////////////////////////////////////////////////////
function extraeValor($variable,$cadena) {
	$valor="";
	$variablelower=strtolower($variable);
	$cadena=strtolower($cadena);
	$posx=strpos($variablelower, $cadena);
	if ($posx>-1) {
		$valor=substr($variable,$posx+strlen($cadena));
		$valor=str_replace("<", "", $valor);
		$valor=str_replace(">", "", $valor);
		$valor=str_replace("=", "", $valor);
		$valor=str_replace("'", "", $valor);
		$valor=str_replace("\"", "", $valor);
		$valor=trim($valor);
		$posx=strpos($valor." ", " ");
		$valor=substr($valor,0,$posx);
	}
	return trim($valor);
}
?>
