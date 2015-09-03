<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

// procesa directorio y subdirectorios para cambiar acentos y enhes. y busca caracteres raros

echo "\nRECORRE DIRs BUSCANDO ACENTOS Y CARACTERES RAROS EN LOS FICHEROS PHP: ";
echo " php cambiaacentos.php -muestra -cambia\n\n";

global $_SERVER;
if ($_SERVER["argc"]>1) {
        $muestra=($_SERVER["argv"][1]);
        $cambia=($_SERVER["argv"][2]);
}

$A_chars=array();
$A_fich=array();
$A_numchars=array();
global $A_chars, $A_numchars;
$A_chars[9]=" ";
$A_chars[10]=" ";
$A_chars[13]=" ";
for($k=32; $k<128; $k++) $A_chars[$k]=" ";

procesaDir(".");
echo "\n\n";
/*
ksort($A_chars);
foreach($A_chars as $TMP_k=>$TMP_v) {
	if ($TMP_k==189) continue;
	if ($TMP_k==191) continue;
	if ($TMP_k==239) continue;
	if ($TMP_v!=" ") echo "==>".$TMP_k." [".$A_numchars[$TMP_k]."]".chr($TMP_k)."\n".$TMP_v."\n\n";
}
*/

ksort($A_fich);
foreach($A_fich as $TMP_k=>$TMP_v) {
	echo "==>".$TMP_k." \n";
	if ($muestra!="") echo $TMP_v."\n\n";
}

/////////////////////////////////////////////////////////////////////////////////////
function procesaDir($dir) {
	global $A_chars, $A_numchars;
	$f=opendir($dir);
	$cont=0;
	while ($fn=readdir($f)) {
		if ($fn=="." || $fn=="..") continue;
		$cont++;
		if (is_dir($dir."/".$fn)) {
			//echo $cont." Procesa Directorio ".$dir."/".$fn." \n";
			//if ($dir!=".") procesaDir($dir."/".$fn);
			//else procesaDir($fn);
		} else {
			$ext=substr($fn,strlen($fn)-3);
			if ($ext=="php" || $ext=="sql") {
				//echo $cont." Procesa Fichero ".$dir."/".$fn." \n";
				if ($dir!=".") procesaFich($dir."/".$fn);
				else procesaFich($fn);
			}
		}
	}
}
/////////////////////////////////////////////////////////////////////////////////////
function procesaFich($fich) {
	global $A_fich, $A_chars, $A_numchars;
        if (!($fp=fopen($fich,"r"))) return false;
        $result=""; $result2="";
        while (!feof($fp)) { 
		$linea=fgets($fp,100000); 
		$result.=$linea; 
		$linea2=str_replace("x","x",$linea);
		if ($linea2!=$linea) {
			//echo " En ".$fich." cambia:\n ===>".$linea."\n ====<".$linea2."\n\n";
		}
		$result2.=$linea2; 
	}
	fclose($fp);
	for($k=0; $k<strlen($result); $k++) {
		if (ord($result[$k])==9) continue;
		if (ord($result[$k])==10) continue;
		if (ord($result[$k])==13) continue;
		//if ($A_chars[ord($result[$k])]!="") continue;
		if (ord($result[$k])>127 || ord($result[$k])<32) {
			$A_chars[ord($result[$k])].=$fich." ".substr($result,$k-10,20)."\n";
			$A_fich[$fich].=ord($result[$k])." ".substr($result,$k-10,20)."\n";
			$A_numchars[ord($result[$k])]++;
		}
	}
return;  // no cambia nada

        if ($result!=$result2) {
        	$fp=fopen($fich,"w");
		fwrite($fp,$result2);
		fclose($fp);
	}

        return;
}
?>
