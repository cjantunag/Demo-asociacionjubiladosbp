<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

$MaxCharsItem=500;
if ($MaxNews=="") $MaxNews=99;
if ($DiasNoticias=="") $DiasNoticias=31;

$content='        <div id="avance">
          <div class="informate">&iexcl;INF&Oacute;RMATE!</div>
';

$TMP_lang=getSessionVar("SESSION_lang");

if (is_modulepermitted("", "MSC", "noticias")) $TMP_link=true;
else $TMP_link=false;
$TMP_linkedit="";

global $SESSION_SID, $RAD_dbi, $idcatblock, $dbname, $PHP_SELF;
if ($dbname=="") $dbname=_DEF_dbname;

$TMP_idcat="";
if ($idcatblock!="") {
	$tmpdefaultfiltercat=" AND idcat='".$idcatblock."'";
} else {
	$TMP_result = sql_query("SELECT * FROM categorias WHERE categoria='news'", $RAD_dbi);
	while($TMP_row = sql_fetch_array($TMP_result, $RAD_dbi)) {
		$TMP_idcat.=",".$TMP_row["id"];
		if ($tmpdefaultfiltercat!="") $tmpdefaultfiltercat.=" OR ";
		$tmpdefaultfiltercat.="idcat='".$TMP_row["id"]."'";
	}
	$TMP_idcat.=",";
	if ($tmpdefaultfiltercat!="") $tmpdefaultfiltercat=" AND (".$tmpdefaultfiltercat.")";
}

$TMP_time=time();
$cmdsql = "SELECT * FROM contenidos where portada='1' and (activo='1' OR activo IS NULL) ".$tmpdefaultfiltercat." order by fechapubli DESC";
$TMP_result = sql_query($cmdsql, $RAD_dbi);
$TMP_cont=0;
while(($TMP_row=sql_fetch_array($TMP_result, $RAD_dbi)) && ($TMP_cont<$MaxNews)) {
	if ($TMP_row[fechapubli]>time() && $TMP_row[idcat]!="4") continue;
	if ($TMP_row[fechacalendario]>time() && $TMP_row[idcat]=="4") continue;
	$TMP_url="$PHP_SELF?V_dir=MSC&amp;V_mod=shownews&amp;idn=".$TMP_row[id].$SESSION_SID;

	if ($TMP_row[fechabaja]>0 && $TMP_row[fechabaja]<time()) continue;
	if ($TMP_row[idcat]=="4") { // actividades
		if ($TMP_row[fechabaja]>0 && $TMP_row[fechabaja]<time()) continue; // Actividad caducada
		$TMP_url.="&amp;V_idmod=121";
	} else $TMP_url.="&amp;V_idmod=62";
	if ($TMP_row[idcat]=="3") { // noticias
		$TMP_fechapubliadmitida=$TMP_row[fechapubli]+$DiasNoticias*24*60*60;
		if ($TMP_fechapubliadmitida<time()) continue; // solo se muestra noticias de hasta 30 dias
	}

	
        if ($TMP_row["tema_".$TMP_lang]!="") $TMP_row["tema"]=$TMP_row["tema_".$TMP_lang];
	if ($TMP_row["contenido_".$TMP_lang]!="") $TMP_row["contenido"]=$TMP_row["contenido_".$TMP_lang];
	
	$TMP_row["tema"]=str_replace("\"", "?", $TMP_row["tema"]);
	//$TMP_row["contenido"]=str_replace("\"", "?", $TMP_row["contenido"]);
	$TMP_fecha=RAD_showdateint($TMP_row["fechapubli"]);
	////if ($TMP_row["fechacalendario"]>0) $TMP_fecha=RAD_showdateint($TMP_row["fechacalendario"]);

	$TMP_new="<a href='".$TMP_url."&amp;id=".$TMP_row[id]."'>".$TMP_row["tema"]."</a>";
	if ($TMP_row[idcat]=="4") $TMP_fecha.=" - ".RAD_showdateint($TMP_row["fechabaja"]); // Actividad fecha inicio - fin
	//if (strlen($TMP_row[contenido])>$MaxCharsItem) $TMP_row[contenido]=trim(substr($TMP_row[contenido],0,$MaxCharsItem))." ...";
	//$TMP_new.="".$TMP_row[contenido]."</a><br><br>";
	$TMP_new=str_replace("\n", "", $TMP_new);
	$TMP_new=str_replace("\r", "", $TMP_new);
	if ($TMP_row[ciudad]!="") $TMP_ciudad=" | ".$TMP_row[ciudad];
	else $TMP_ciudad="";

	if ($TMP_link==true) {
		if ($TMP_row[idcat]=="4") $TMP_linkedit="<br/><a target=_blank href='index.php?V_dir=MSC&amp;V_mod=actividades&func=edit&par0=".$TMP_row[id]."'>"._DEF_NLSEdit."</a>";
		else $TMP_linkedit="<br/><a target=_blank href='index.php?V_dir=MSC&amp;V_mod=noticias&func=edit&par0=".$TMP_row[id]."'>"._DEF_NLSEdit."</a>";
	}
	$TMP_img=RAD_primerFich($TMP_row[imagenes]);
	if ($TMP_img!="") $content.='<img src="files/'.$dbname.'/'.$TMP_img.'" />';
	$content.='
          <p class="titular">'.$TMP_new.'</p>
          <p class="fecha">'.$TMP_fecha.$TMP_ciudad.'</p>
          <p>'.$TMP_row[contenido].$TMP_linkedit.'</p>
          <p class="linea"></p>';
	$TMP_cont++;
}

$A_x=explode(".",basename(__FILE__));
$TMP_fich=$A_x[0];
$TMP_result=sql_query("SELECT * from bloques where fichero='$TMP_fich'", $RAD_dbi);
$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
if ($TMP_row["contenido_".$TMP_lang]!="") $TMP_row["contenido"]=$TMP_row["contenido_".$TMP_lang];
$content.=$TMP_row["contenido"]; // agrega contenido de la definicion del bloque


if ($TMP_cont==0) {
	$content="";
	return "";
}

return $TMP_content;
?>
