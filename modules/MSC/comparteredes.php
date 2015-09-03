<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

// Parametro: $idn Noticia
global $idn, $RAD_dbi, $V_dir, $V_idmod, $PHP_SELF;

if (!file_exists("themes/MSC/compartir/facebook.png") && !file_exists("themes/MSC/compartir/twitter.png")) return "";

$TMP_URL="http://".$GLOBALS["HTTP_HOST"];
$TMP_URL=_DEF_URL;

$TMP_lang=getSessionVar("SESSION_lang");

$TMP_res=sql_query("SELECT * from contenidos where id=".converttosql($idn), $RAD_dbi);
$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
foreach($TMP_row as $TMP_k=>$TMP_v) if ($TMP_row[$TMP_k."_".$TMP_lang]!="") $TMP_row[$TMP_k]=$TMP_row[$TMP_k."_".$TMP_lang];
$TMP_img=RAD_primerFich($TMP_row[imagenes]);
if ($TMP_img!="") {
	$TMP_img=$TMP_URL."files/".$dbname."/".$TMP_img;
} else {
	$TMP_res2=sql_query("SELECT * from contenidos where idcat='5' and idpadre=".converttosql($TMP_row[id]), $RAD_dbi);
	$TMP_row2=sql_fetch_array($TMP_res2, $RAD_dbi);
	$TMP_img=RAD_primerFich($TMP_row2[imagenes]);
	if ($TMP_img!="") $TMP_img=$TMP_URL."files/".$dbname."/".$TMP_img;
}

if ($TMP_row[url_seo]!="") $TMP_URL.=$TMP_row[url_seo];
else $TMP_URL.=substr($PHP_SELF,1)."?V_dir=".$V_dir."&V_idmod=".$V_idmod."&idn=".$idn;
$TMP_title=RAD_convertHTML2TXT($TMP_row[tema]);
$TMP_breve=RAD_convertHTML2TXT($TMP_row[contenido]);

$TMP_script=""; $TMP_res="";

// Compartir Redes Sociales

if (file_exists("themes/MSC/compartir/facebook.png")) {
	$TMP_res.='<a href="javascript:comparteFacebook()" title="Compartir en Facebook"><img src="themes/MSC/compartir/facebook.png" width="16" height="16" alt="Compartir en Facebook"></a> ';
	$TMP_script.="
function comparteFacebook() {
    var intCenterX = ((screen.width-9)/2) - (600/2);
    var intCenterY = ((screen.height-57)/2) - (400/2);
    window.open('http://www.facebook.com/sharer.php?s=100&p[url]=".urlencode($TMP_URL)."&p[images][0]=".urlencode($TMP_img)."&p[title]=".urlencode($TMP_title)."&p[summary]=".urlencode($TMP_breve)."','FACEBOOK','directories=0,resizable=0,location=0,status=0,scrollbars=0,toolbar=0,menubar=0,width=600,height=400,screenX='+intCenterX+',screenY='+intCenterY+',left='+intCenterX+',top='+intCenterY);
}
";
}

if (file_exists("themes/MSC/compartir/twitter.png")) {
	$TMP_res.='<a href="javascript:comparteTwitter()" title="Compartir en Twitter"><img src="themes/MSC/compartir/twitter.png" width="16" height="16" alt="Compartir en Twitter"></a>';
	$TMP_script.="
function comparteTwitter(intId) {
    var intCenterX = ((screen.width-9)/2) - (600/2);
    var intCenterY = ((screen.height-57)/2) - (400/2);
    window.open('http://twitter.com/home?status=".urlencode($TMP_title." ".$TMP_URL)."','TWITTER','directories=0,resizable=0,location=0,status=0,scrollbars=0,toolbar=0,menubar=0,width=600,height=400,screenX='+intCenterX+',screenY='+intCenterY+',left='+intCenterX+',top='+intCenterY);
}
";
}

if ($TMP_script!="") $TMP_script="<script type='text/javascript'>".$TMP_script."</script>\n";

return "\n<div style='float:right;'>\n".$TMP_script.$TMP_res."\n</div>\n";

?>
