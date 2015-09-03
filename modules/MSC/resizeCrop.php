<?php
//error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

//-----------------------------------------------------------------------------------------------------------------------------
function resizeCrop($filename, $filenameOut, $width, $height) {
// $width = 200; $height = 300; // maximum height and width
$porcentaje_crop="16";
$escala="1";
$qualityJPG="75";
$typeout="gif";

    if ($filenameOut=="") {
	$A_x=explode(".",$filename);
	$TMP_ext=$A_x[count($A_x)-1];
	$filenameOut=substr($filename,0,strlen($filename)-1-strlen($TMP_ext)).".".$width."x".$height.".".$TMP_ext;
    }
    if (file_exists($filenameOut)) return $filenameOut; // ya estaba creado

    list($width_orig, $height_orig) = getimagesize($filename); // original dimension

    $scalewidth=1; $scaleheight=1;
    if ($width_orig>$width) $scalewidth=$width_orig/$width;
    if ($height=="") $height=$height_orig/$scalewidth;
    if ($height_orig>$height) $scaleheight=$height_orig/$height;
    $scale=min($scalewidth,$scaleheight);

    if ($width_orig<=$width && $height_orig<=$height) return $filename;

    $width_fin=round($width_orig/$scale);
    $height_fin=round($height_orig/$scale);
    if ($width>$width_fin) $width=$width_fin;
    if ($height>$height_fin) $height=$height_fin;
    //echo "reescala $filename de $width_orig x $height_orig con escala $scale a $width x $height. Primero reescala a ".round($width_orig/$scale)." x ".round($height_orig/$scale).".<br>";

    $iniwidth_orig=round(round($width_orig-$scale*$width)/2);
    $iniheight_orig=round(round($height_orig-$scale*$height)/2);
    //echo "crop desde $iniwidth_orig,$iniheight_orig a $width_orig,$height_orig para obtener ".($width_orig-$iniwidth_orig)." x ".($height_orig-$iniheight_orig)."<br>";

    $format = strtolower(substr(strrchr($filename,"."),1));
    switch($format) {
        case 'gif' :
                $image_orig = imagecreatefromgif($filename);
                $type="gif";
                break;
        case 'png' :
                $image_orig = imagecreatefrompng($filename);
                $type="png";
                break;
        case 'jpg' :
        case 'jpeg' :
                $image_orig = imagecreatefromjpeg($filename);
                $type="jpg";
                break;
        default :
                echo ("ERROR; UNSUPPORTED IMAGE TYPE ".$filename);
                return;
                break;
    }
/*
    if ($typeout!=$type) {
	$type=$typeout;
	$filename=substr($filename,0,strlen($filename)-3).$type;
    }
*/
    $image = imagecreatetruecolor($width, $height);
    if ($type!="jpg") {
        //imageantialias($image,true);
        imagealphablending($image, false);
        imagesavealpha($image,true);
        //$transparent = imagecolorallocatealpha($image, 255, 255, 255, 0);
        $transparent = imagecolorallocatealpha($image, 255, 255, 255, 127);
        imagefill($image, 0, 0, $transparent);
        //for($x=0;$x<$t_wd;$x++) {
        //      for($y=0;$y<$t_ht;$y++) {
        //              imageSetPixel( $image, $x, $y, $transparent );
        //      }
        //}
    }
    //if (is_admin()) echo "reescala $filename de $width_orig x $height_orig a $width x $height. Escala ancho=$scalewidth. EscalaAlto=$scaleheight. <br>Rescala con escala $scale a ".round($width_orig/$scale)." x ".round($height_orig/$scale).".<br>Copiando de desde $iniwidth_orig , $iniheight_orig hasta ".($width_orig-2*$iniwidth_orig)." , ".($height_orig-2*$iniheight_orig).".<br>";

    imagecopyresampled($image, $image_orig, 0, 0, $iniwidth_orig, $iniheight_orig, $width, $height, $width_orig-2*$iniwidth_orig, $height_orig-2*$iniheight_orig);
    ////imagecopyresized($image, $image_orig, 0, 0, $iniwidth_orig, $iniheight_orig, $width+2*$iniwidth_orig, $height+2*$iniheight_orig, $width_orig, $height_orig);

    $fp=fopen($filenameOut,"wb");
    fclose($fp);
    if ($type=="jpg") imagejpeg($image, $filenameOut, $qualityJPG);
    else if ($type=="png") imagepng($image, $filenameOut);
    else imagegif($image, $filenameOut);
    chmod($filenameOut, 0666);
    return $filenameOut;
}

?>
