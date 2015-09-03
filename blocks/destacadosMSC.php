<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

// Muestra documentos destacados idcat=7

$MaxDocs=99;

$content='
        <div class="documentos">';

$lang=getSessionVar("SESSION_lang");

if (is_modulepermitted("", "MSC", "documentos")) $TMP_link=true;
else $TMP_link=false;
$TMP_linkedit="";

global $SESSION_SID, $RAD_dbi, $idcatblock, $dbname, $PHP_SELF;
if ($dbname=="") $dbname=_DEF_dbname;

if ($idcatblock!="") {
	$tmpdefaultfiltercat=" AND idcat='".$idcatblock."'";
} else {
	$tmpdefaultfiltercat=" AND idcat IN ('7','9','10')"; // categoria por defecto de Documentos
}

$TMP_time=time();
$cmdsql = "SELECT * FROM contenidos where portada='1' and (activo='1' OR activo IS NULL) ".$tmpdefaultfiltercat." order by fechapubli DESC";
//$cmdsql = "SELECT * FROM contenidos where ".substr($tmpdefaultfiltercat,4)." order by fechapubli DESC";
$TMP_result = sql_query($cmdsql, $RAD_dbi);
$TMP_cont=0;
while(($TMP_row=sql_fetch_array($TMP_result, $RAD_dbi)) && ($TMP_cont<$MaxDocs)) {
	if ($TMP_row[fechapubli]>time() && $TMP_row[idcat]!="4") continue;
	if ($TMP_row[fechacalendario]>time() && $TMP_row[idcat]=="4") continue;
	$TMP_url="files/".$dbname."/".RAD_primerFich($TMP_row[documentos]);
	$A_x=explode(".",$TMP_url);
	$TMP_ext=strtoupper($A_x[count($A_x)-1]);
	$TMP_length=round(filesize($TMP_url)/1024,0);
	$TMP_doc2=$PHP_SELF."?V_dir=MSC&amp;V_mod=download&amp;f=".RAD_primerFich($TMP_row[documentos]);
	
        if ($TMP_row["tema_".$lang]!="") $TMP_row["tema"]=$TMP_row["tema_".$lang];
	$TMP_row["tema"]=str_replace("\"", "?", $TMP_row["tema"]);

	$TMP_fecha=RAD_showdateint($TMP_row["fechapubli"]);
// FALTA comprobar si es un Documento/Video/Enlace para mostrarlo como en Materiales Relacionados

	if ($TMP_link==true) {
		$TMP_linkedit="<li><a target=_blank href='index.php?V_dir=MSC&amp;V_mod=documentos&amp;func=edit&amp;par0=".$TMP_row[id]."'>"._DEF_NLSEdit."</a></li>";
	}
	$TMP_img=RAD_primerFich($TMP_row[imagenes]);
	//if ($TMP_img!="") $content.='<img src="files/'.$dbname.'/'.$TMP_img.'" />';
	if ($TMP_row[idcat]=="7") { // documentos
                $TMP_cont++;
		$content.='
          <ul>
            <li class="nombre"><a target=_blank href="'.$TMP_doc2.'">'.$TMP_row[tema].'</a></li>
            <li class="fecha">'.$TMP_fecha.'  | <a target=_blank href="'.$TMP_doc2.'">'.$TMP_ext.'</a> | <span>'.$TMP_length.' KB</span></li>
          </ul>';
	}
        if ($TMP_row[idcat]=="9") { // videos
                $TMP_cont++;
		$content.='
          <ul class="videos">
            <li class="nombre"><a target=_blank href="'.$TMP_row[urls].'">'.$TMP_row[tema].'</a></li>
            <li class="fecha">'.$TMP_fecha.' | <span>'.$TMP_row[observaciones].'</span></li>
          </ul>';
        }
        if ($TMP_row[idcat]=="10") { // enlaces
                $TMP_cont++;
		$content.='
          <ul>
            <li class="nombre"><a href="'.$TMP_row[urls].'">'.$TMP_row[contenido].'</a></li>
          </ul>';
        }
}
$content.='
        </div>
';


$A_x=explode(".",basename(__FILE__));
$TMP_fich=$A_x[0];
$TMP_result=sql_query("SELECT * from bloques where fichero='$TMP_fich'", $RAD_dbi);
$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
if ($TMP_row["contenido_".$TMP_lang]!="") $TMP_row["contenido"]=$TMP_row["contenido_".$TMP_lang];
//$content.=$TMP_row["contenido"]; // agrega contenido de la definicion del bloque

if ($TMP_cont==0) {
	$content="";
	return "";
}

return $content;
?>
