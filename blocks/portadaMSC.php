<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");
// Muestra en el centro de la portada los Proyectos en Marcha

global $RAD_dbi, $content, $dbname;
if ($dbname=="") $dbname=_DEF_dbname;
$TMP_lang=getSessionVar("SESSION_lang");

$A_x=explode(".",basename(__FILE__));
$TMP_fich=$A_x[0];
$TMP_result=sql_query("SELECT * from bloques where fichero='$TMP_fich'", $RAD_dbi);
$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
if ($TMP_row["contenido_".$TMP_lang]!="") $TMP_row["contenido"]=$TMP_row["contenido_".$TMP_lang];

if (is_modulepermitted("", "MSC", "proyectos")) $TMP_link=true;
else $TMP_link=false;
$TMP_linkedit="";
        
$content.=$TMP_row[contenido].'<div id="que_facemos">';
$TMP_result=sql_query("SELECT * from proyectos where (fechafin>'".date("Y-m-d")."' or fechafin='' or fechafin is null or fechafin like '0%') and portada='1' order by fechainicio DESC, proyecto ASC", $RAD_dbi);
while($TMP_row=sql_fetch_array($TMP_result, $RAD_dbi)) {
	foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
	if ($TMP_row[url]=="") $TMP_row[url]=$PHP_SELF."?V_dir=MSC&amp;V_mod=showproyecto&amp;idproyecto=".$TMP_row[idproyecto];
	if ($TMP_link==true) $TMP_linkedit="<br/><a target=_blank href='index.php?V_dir=MSC&amp;V_mod=proyectos&func=detail&par0=".$TMP_row[idproyecto]."'>"._DEF_NLSEdit."</a>";
	$TMP_img=RAD_primerFich($TMP_row[imagen]);
	if ($TMP_img=="") {
	$TMP_res2=sql_query("SELECT * from contenidos where idproyecto='".$TMP_row[idproyecto]."' and idcat='5' order by fechapubli DESC", $RAD_dbi);
	$TMP_row2=sql_fetch_array($TMP_res2, $RAD_dbi);
		$TMP_img=RAD_primerFich($TMP_row2[imagenes]);
	}
	$TMP_img3="";
        if ($TMP_img!="") {
                include_once("modules/MSC/resizeCrop.php");
                $TMP_img3=resizeCrop("files/".$dbname."/".$TMP_img,"","207","120");
                list($anchoimg, $altoimg, $tipo, $resto)=getimagesize($TMP_img3);
                //echo "\n<br>Crop $TMP_img3 de ancho $anchoimg, $altoimg, $tipo<br>";
        }
        if ($TMP_img3!="") {
		$TMP_imghtml='<img alt="Img '.str_replace("'","",$TMP_row[proyecto]).'" src="'.$TMP_img3.'">';
        } else {
		$TMP_imghtml="<br>";
	}
	$content.='
          <ul>
            <li class="imgContainer">'.$TMP_imghtml.'</li>
            <li class="titular"><a href="'.$TMP_row[url].'">'.$TMP_row[proyecto].'</a></li>
            <li>
              <p>'.$TMP_row[resumen].$TMP_linkedit.'</p>
            </li>
         </ul>
';
}
$content.='</div>';
return $content;
?>
