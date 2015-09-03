<?php
// Parametros que recibe:
// image: nombre completo del fichero imagen (obligatorio). Si no se pasa recoge el ultimo de la sesion
// op=getImageInfo: solicita informacion de la imagen . Devuelve size, width y height
// op=zoom: magnifica con centro en X1,Y1

//error_reporting(E_ALL);

define("IMG_PATH","files/tmp"); // Temporal image directory
define("JPEG_QUALITY",80); // Define the jpeg quality

session_start();

define("VERSION", GetGDInfo()); // check GD version first
if(VERSION < 1) die("Invalid GD version!");

if (empty($_REQUEST['image'])) $_REQUEST['image']=$_SESSION['image'];
$image = $_REQUEST['image'];

if ($image=="")  die("image param missing"); 
if(!is_file($image)) die("nonexistent image file $image");

if ($numop=="") $numop=0;
$numop++;

if($op=="getImageInfo") {
	if (is_file($image)) {
		$ImgSize=getimagesize($image);
		echo $image."<br>Size=".filesize($image).".<br>Width=".$ImgSize[0].". Height=".$ImgSize[1];
	} else echo "Image ERROR $image";
	exit;
}


if(strtolower($op)=="zoom"){
	$TMP_image=basename($image);
	if ($numop>1) {
		$pos_sep=strpos($TMP_image, "_");
		if ($pos_sep>0) $TMP_image=substr($TMP_image,$pos_sep+1);
	}
	$dstimage=IMG_PATH."/".$numop."_".$TMP_image;
	ZoomImage($image, $X1, $Y1, $factor, $dstimage);
	$image=$dstimage;
}

$ImgSize=getimagesize($image);
$windowWidth=$ImgSize[0]+120;
$windowHeight=$ImgSize[0]+150;

echo "<html>
<head>
<title>Imagen</title>

<style type='text/css'>
#Imagen { position:relative; }
</style>

<script type='text/javascript'>
self.resizeTo('$windowWidth','$windowHeight');
var op='',lists='',XDown='',YDown='',XUp,YUp;
function mouseDown(evt) {
  if (!evt) evt = window.event;
  if (XDown!='') {
	XUp=evt.layerX; YUp=evt.layerY; submitForm();
  } else {
	XDown=evt.layerX; YDown=evt.layerY; submitForm();
  }
}
var esperaMove=0;
function mouseMove(evt) {
  if (esperaMove) return;
  esperaMove=1;
  setTimeout('esperaMove=0;', 5);
  setTimeout('hideLayer(\"ImgInfo\");', 3000);

  showLayer(\"ImgInfo\");
  if (!evt) evt = window.event;
  if (XDown!='') lists='['+XDown+' - '+YDown+'] ['+evt.layerX+' - '+evt.layerY+']';
  else lists='['+evt.layerX+' - '+evt.layerY+']';
  textImgInfo(evt.clientX,evt.clientY,lists);
}
function textImgInfo(x,y,text) {
 if (document.all) { // IE
   var ob = document.all.ImgInfo;
   ob.style.pixelLeft = x+5;
   ob.style.pixelTop = y+2;
 } else if (document.getElementById) { // firefox
   var ob = document.getElementById('ImgInfo');
   ob.style.left = x+5;
   ob.style.top = y+2;
 }
 ob.innerHTML = text;
}
function showLayer(object) {
    if (document.getElementById && document.getElementById(object) != null)
         node = document.getElementById(object).style.visibility='visible';
    else if (document.layers && document.layers[object] != null)
        document.layers[object].visibility = 'visible';
    else if (document.all)
        document.all[object].style.visibility = 'visible';
}
function hideLayer(object) {
    if (document.getElementById && document.getElementById(object) != null)
         node = document.getElementById(object).style.visibility='hidden';
    else if (document.layers && document.layers[object] != null)
        document.layers[object].visibility = 'hidden';
    else if (document.all)
         document.all[object].style.visibility = 'hidden';
}

function submitForm() {
    if (op=='zoom' && XDown!='') {
        document.forms.IMG.op.value=op;
        document.forms.IMG.factor.value='2';
        document.forms.IMG.X1.value=XDown;
        document.forms.IMG.Y1.value=YDown;
        document.forms.IMG.submit();
    }
}
</script>
<body bgcolor='#FFFFFF' style='margin:0px'>
<form name=IMG action='$PHP_SELF'>
<input type=hidden name=image value='$image'>
<input type=hidden name=op value=''>
<input type=hidden name=numop value='$numop'>
<input type=hidden name=factor value=''>
<input type=hidden name=X1 value=''><input type=hidden name=Y1 value=''>
<input type=hidden name=X2 value=''><input type=hidden name=Y2 value=''>
</form>
<br><ul>
<img src='images/lupa.gif' border=1 onclick='javascript:op=\"zoom\";XDown=\"\";'> <img src='images/edit.gif' border=1><br>
<img onFocus='showLayer(\"ImgInfo\");' onFocusOut='hideLayer(\"ImgInfo\")' onmousedown='mouseDown(event);' onmousemove='mouseMove(event);' src='".$image."?".time()."' border=0 alt='Imagen' name='Imagen' id='Imagen'>
</ul>
<div id='ImgInfo' style='position:absolute; top:0px; left:0px; font: 0.7em arial;'></font></div>
</body>
</html>";


///////////////////////////////////////////////////////////////////////////////////////////
function GetGDInfo(){
    if(function_exists("imagegd2")){
    	$gd_version = 2;
    } else {
	if(function_exists("imagecreatefromjpeg")) $gd_version = 1;
	else $gd_version = 0;
    }
    return $gd_version;
}
///////////////////////////////////////////////////////////////////////////////////////////
function ZoomImage($image, $x, $y, $factor, $imagedst){
	$mime = array( '1' => 'image/gif', '2' => 'image/jpeg', '3' => 'image/png', '6' => 'image/bmp' );
	$imginfo = getImageSize($image);
	$imginfo['mime'] = $mime[$imginfo[2]];
	switch ($imginfo['mime']) {
		case 'image/gif':
			$src_img = imageCreateFromGIF($image);
			break;
		case 'image/jpeg':
			$src_img = imageCreateFromJPEG($image);
			break;
		case 'image/png':
			$src_img = imageCreateFromPNG($image);
			break;
		case 'image/wbmp':
			$src_img = imageCreateFromWBMP($image);
			break;
		default:
			return "Error: unsupported image type $image.";
	}
	$src_h = $imginfo[1]; // source size
	$src_w = $imginfo[0];
	$dst_w = $src_w; // target size
	$dst_h = $src_h;
	$src_X1=$x-floor($dst_w/2); if ($src_X1<0) $src_X1=0;  // source initial position
	$src_Y1=$y-floor($dst_h/2); if ($src_Y1<0) $src_Y1=0;
	$src_X2=$src_X1+floor($dst_w/2); // source final position
	$src_Y2=$src_Y1+floor($dst_h/2);

	if(VERSION == 2) $dst_img = imagecreatetruecolor($dst_w, $dst_h);
	else $dst_img = imagecreate($dst_w, $dst_h);


	if(VERSION == 2) imagecopyresampled($dst_img,$src_img, 0, 0, $src_X1, $src_Y1, $dst_w, $dst_h, $src_X2, $src_Y2);
	else             imagecopyresized  ($dst_img,$src_img, 0, 0, $src_X1, $src_Y1, $dst_w, $dst_h, $src_X2, $src_Y2);
	imagejpeg($dst_img, $imagedst , JPEG_QUALITY);

	imageDestroy($src_img);
	imageDestroy($dst_img);
}
///////////////////////////////////////////////////////////////////////////////////////////
function addText($img, $str, $x, $y, $col) {
    if($img->font) {
        $colour = ImageColorAllocate($img, $col[0], $col[1], $col[2]);
        if(!imagettftext($img, $img->size, 0, $x, $y, $colour, $img->font, $str)) {
          errorImage("Error Drawing Text");
        }
    } else {
        $colour = ImageColorAllocate($img, $col[0], $col[1], $col[2]);
        Imagestring($img, 5, $x, $y, $str, $colour);
    }
}
function shadowText($img, $str, $x, $y, $col1, $col2, $offset=2) {
   addText($img, $str, $x, $y, $col1);
   addText($img, $str, $x-$offset, $y-$offset, $col2);
}
function addLine($img, $x1, $y1, $x2, $y2, $col) {
    $colour = ImageColorAllocate($img, $col[0], $col[1], $col[2]);
    ImageLine($img, $x1, $y1, $x2, $y2, $colour);
}
?>