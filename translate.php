<?
//-----------------------------------------------------------------------------------------------------
function translateLocale($text, $tipoTraduccion) {

if (trim($text)=="") return "";

//Elimina espacios y parentesis del archivo, por el problema que causan en los sistemas UNIX
$archivo_name = ereg_replace("[ ()]","_",$archivo_name);

$encapsulaCaract=array( '\\'=>'((/))');
$convertApostrofe=array( '&apos;'=>'\'');
$convertHtml=array( '((/))'=> '\\' , '&AElig;' => 'Æ' , '&Aacute;' => 'Á' , '&Acirc;' => 'Â' , '&Agrave;' => 'À' , '&Aring;' => 'Å' , '&Atilde;' => 'Ã' , '&Auml;' => 'Ä' , '&Ccedil;' => 'Ç' , '&ETH;' => 'Ð' , '&Eacute;' => 'É' , '&Ecirc;' => 'Ê' , '&Egrave;' => 'È' , '&Euml;' => 'Ë' , '&Iacute;' => 'Í' , '&Icirc;' => 'Î' , '&Igrave;' => 'Ì' , '&Iuml;' => 'Ï' , '&Ntilde;' => 'Ñ' , '&Oacute;' =>'Ó' , '&Ocirc;' => 'Ô' , '&Ograve;' => 'Ò' , '&Oslash;' => 'Ø' , '&Otilde;' => 'Õ' , '&Ouml;' => 'Ö' , '&THORN;' => 'Þ' , '&Uacute;' => 'Ú' , '&Ucirc;' => 'Û' , '&Ugrave;' => 'Ù' , '&Uuml;' => 'Ü' , '&Yacute;' => 'Ý' , '&aacute;' => 'á' , '&acirc;' => 'â' , '&aelig;' => 'æ' , '&agrave;' => 'à' , '&aring;' => 'å' , '&atilde;' => 'ã' , '&auml;' => 'ä' , '&ccedil;' => 'ç' , '&eacute;' => 'é' , '&ecirc;' => 'ê' , '&egrave;' => 'è' , '&eth;' => 'ð' , '&euml;' => 'ë' , '&iacute;' => 'í' , '&icirc;' => 'î' , '&igrave;' => 'ì' , '&iuml;' => 'ï' , '&ntilde;' => 'ñ' , '&oacute;' => 'ó' , '&ocirc;' => 'ô' , '&ograve;' => 'ò' , '&oslash;' => 'ø' , '&otilde;' => 'õ' , '&ouml;' => 'ö' , '&szlig;' => 'ß' , '&thorn;' => 'þ' , '&uacute;' => 'ú' , '&ucirc;' => 'û' , '&ugrave;' => 'ù' , '&uuml;' => 'ü' , '&yuml;' => 'ÿ' , '&yacute;' => 'ý' , '<aa>' => '' ,'<acr>' => '' , '<al>' => '' , '<an>' => '' , '<ant>' => '' ,'<cni>' => '' , '<cnjadv>' => '' , '<cnjcoo>' => '' , '<cnjsub>' => '' , '<def>'  => '' , '<dem>' => '' , '<det>' => '' , '<detnt>' => '' , '<enc>' => '' , '<f>' => '' , '<fti>' => '' , '<fts>' => '' , '<ger>' => '' , '<ifi>' => '' , '<ij>' => '' , '<imp>' => '' , '<ind>' => '' , '<inf>' => '' , '<itg>' => '' , '<loc>' => '' , '<lpar>' => '' , '<lquest>' => '' , '<m>' => '' , '<mf>' => '' , '<n>' => '' , '<nn>' => '' , '<np>' => '' , '<nt>' => '' , '<num>' => '' , '<p1>' => '' , '<p2>' => '' , '<p3>' => '' , '<pii>' => '' , '<pis>' => '' , '<pl>' => '' , '<pos>' => '' , '<pp>' => '' , '<pr>' => '' , '<preadv>' => '' , '<predet>' => '' , '<pri>' => '' , '<prn>' => '' , '<pro>' => '' , '<prs>' => '' , '<ref>' => '' , '<rel>' => '' , '<rpar>' => '' , '<sent>' => '' , '<sg>' => '' , '<sp>' => '' , '<sup>' => '' , '<tn>' => '' , '<vaux>' => '' , '<vbhaver>' => '' , '<vblex>' => '' , '<vbmod>' => '' , '<vbser>'  => '' , '<adj>' => '' , '<aa\>' => '' ,'<acr\>' => '' , '<al\>' => '' , '<an\>' => '' , '<ant\>' => '' ,'<cni\>' => '' , '<cnjadv\>' => '' , '<cnjcoo\>' => '' , '<cnjsub\>' => '' , '<def\>'  => '' , '<dem\>' => '' , '<det\>' => '' , '<detnt\>' => '' , '<enc\>' => '' , '<f\>' => '' , '<fti\>' => '' , '<fts\>' => '' , '<ger\>' => '' , '<ifi\>' => '' , '<ij\>' => '' , '<imp\>' => '' , '<ind\>' => '' , '<inf\>' => '' , '<itg\>' => '' , '<loc\>' => '' , '<lpar\>' => '' , '<lquest\>' => '' , '<m\>' => '' , '<mf\>' => '' , '<n\>' => '' , '<nn\>' => '' , '<np\>' => '' , '<nt\>' => '' , '<num\>' => '' , '<p1\>' => '' , '<p2\>' => '' , '<p3\>' => '' , '<pii\>' => '' , '<pis\>' => '' , '<pl\>' => '' , '<pos\>' => '' , '<pp\>' => '' , '<pr\>' => '' , '<preadv\>' => '' , '<predet\>' => '' , '<pri\>' => '' , '<prn\>' => '' , '<pro\>' => '' , '<prs\>' => '' , '<ref\>' => '' , '<rel\>' => '' , '<rpar\>' => '' , '<sent\>' => '' , '<sg\>' => '' , '<sp\>' => '' , '<sup\>' => '' , '<tn\>' => '' , '<vaux\>' => '' , '<vbhaver\>' => '' , '<vblex\>' => '' , '<vbmod\>' => '' , '<vbser\>'  => '' , '<adj\>' => '' , '<adv\>'=> '' , '@n/*n' => '\n');

$text=utf8_decode($text); // Convert from UTF-8 to ISO-8859-1
$text=strtr($text, $encapsulaCaract);

//Nos interesa convertir el apostrofe cuando se trata de traducir idiomas donde su uso es fundamental
if ($tipoTraduccion == "en-ca" || $tipoTraduccion == "ca-es" || $tipoTraduccion== "ca-en")
   $text = strtr($text, $convertApostrofe);

$pag="/tmp/".uniqid("");

$fp = fopen($pag."_IN.html","w");
fputs($fp,$text);
fclose($fp);


$paqueteTraduccion=$tipoTraduccion;
switch ($tipoTraduccion)  {
   case "gl-es":
      $paqueteTraduccion="es-gl";
      break;
   case "pt-es":
      $paqueteTraduccion="es-pt";
      break;
   case "ca-en":
      $paqueteTraduccion="en-ca";
      break;
   case "ca-es":
      $paqueteTraduccion="es-ca";
      break;
}
exec("iconv -f iso-8859-1 -t utf-8 ".$pag."_IN.html -o ".$pag."_IN2.html"); 
 
$cmd="/usr/bin/apertium-translator /usr/share/apertium-$paqueteTraduccion $tipoTraduccion  htmlu < ".$pag."_IN2.html > ".$pag."_OUT2.html";
exec($cmd);
//echo "<br>".$cmd."<br>";
exec("iconv -f iso-8859-1 -t utf-8 ".$pag."_OUT2.html -o ".$pag."_OUT.html"); 

$fp = fopen($pag."_OUT.html","r") or die ("Error writing ".$pag."_OUT.html");
while (!feof($fp)) $textTransHTML .= fgetc($fp);
fclose($fp);

exec("rm ".$pag."_*");
return $textTransHTML;

/*
$textTransNOHTML =  strtr($textTransNOHTML,$convertHtml);
$fp = fopen($pag."_OUT2.html", "w") or die ("Error writing ".$pag."_OUT2.html");
fputs($fp, $textTransNOHTML);
fclose($fp);

//Se vuelve a convertir a UTF-8 mediante el comando con iconv
exec("iconv -f iso-8859-1 -t utf-8 ".$pag."_OUT2.html -o ".$pag."_OUT3.html"); 

$fp = fopen($pag."_OUT3.html","r") or die ("Error reading ".$pag."_OUT3.html");
while (!feof($fp)) $textTrans .= fgetc($fp);
fclose($fp);

exec("rm ".$pag."_*");

return $textTrans;
*/
}
?>
