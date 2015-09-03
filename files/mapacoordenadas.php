<?php
global $_REQUEST, $latname, $longname, $latitud, $longitud, $latitudcenter, $longitudcenter, $zoom, $V_mod, $widthmap, $width, $height, $heightmap;
if ($_REQUEST[key]!="") $key=$_REQUEST[key];
if ($latname=="") $latname="latitud";
if ($longname=="") $longname="longitud";
if ($latitud=="") $latitud=$_REQUEST[latitud];
if ($latitud=="") $latitud=43;
if ($longitud=="") $longitud=-8;
if ($longitudcenter=="") $longitudcenter=$longitud;
if ($latitudcenter=="") $latitudcenter=$latitud;
if ($longitud=="") $longitud=$_REQUEST[longitud];
if ($widthmap=="" && $width>0) $widthmap=$width;
if ($widthmap=="") $widthmap=750;
if ($heightmap=="" && $height>0) $heightmap=$height;
if ($heightmap=="") $heightmap=550;
if ($zoom=="") $zoom=$_REQUEST[zoom];
if ($V_mod=="") $V_mod=$_REQUEST[V_mod];
if ($zoom=="") $zoom=8;
if (_GOOGLEMAP_KEY!="" && _GOOGLEMAP_KEY!="_GOOGLEMAP_KEY") $key=_GOOGLEMAP_KEY;
if ($key=="") {
	include_once("../config.php");
	if (_GOOGLEMAP_KEY!="" && _GOOGLEMAP_KEY!="_GOOGLEMAP_KEY") $key=_GOOGLEMAP_KEY;
}
?>
<? if ($V_mod=="") { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1"/>
<title>Localizador de Coordenadas. Latitud y Longitud</title>
<? } ?>
<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
<script type="text/javascript">
//<![CDATA[
var map;
function onLoad() {
	var myOptions = { zoom: <?=$zoom?>, center: new google.maps.LatLng(<?=$latitudcenter?>,<?=$longitudcenter?>), mapTypeId: google.maps.MapTypeId.ROADMAP, mapTypeControl: true, scaleControl: true, overviewMapControl: true, overviewMapControlOptions: { opened: true } }
	map = new google.maps.Map(document.getElementById("map"), myOptions);
}
//onMapClick = function() {
function onMapClick() {
  var center = map.getCenter();
//  document.getElementById("coord").innerHTML = "Lat./Long. Centro = "+center.lat()+" "+center.lng();
  document.COORD.longitud.value=center.lng();
  document.COORD.latitud.value=center.lat();
}
function guardaCoord() {
  if (window.opener && !window.opener.closed) {
	window.opener.document.F.V0_<?=$longname?>.value = document.COORD.longitud.value;
	window.opener.document.F.V0_<?=$latname?>.value = document.COORD.latitud.value;
	window.close();
  } else {
	if (opener) {
		opener.document.F.V0_<?=$longname?>.value = document.COORD.longitud.value;
		opener.document.F.V0_<?=$latname?>.value = document.COORD.latitud.value;
		window.close();
	} else {
		parent.document.F.V0_<?=$longname?>.value = document.COORD.longitud.value;
		parent.document.F.V0_<?=$latname?>.value = document.COORD.latitud.value;
		closePop();
	}
  }
}
function closePop() {
	parent.hidePopWin();
	return true;
}
//]]>
</script>
<? if ($V_mod!="x") { ?>
</head>
<body onload="onLoad()" marginwidth=0 marginheight=0 leftmargin=0 topmargin=0>
<? } else { ?>
<script type="text/javascript">
onLoad();
</script>
<? } ?>
<? if ($V_mod=="") { ?>
<center>
<font style='font-family:Arial,Verdana,Helvetica,Sans; font-size:16px; font-weight:bold;'>Localizador de Coordenadas</font><br>
<div id="coord" style="margin: 0px; width: 750px; height: 80px; z-index:1;">
<form name=COORD>
<table border=0 cellpadding=0 cellspacin=0><tr><td>
<font style='font-family:Arial,Verdana,Helvetica,Sans; font-size:11px;'>
1.- Desplazar el mapa al lugar deseado y pulsar dos veces sobre el punto del mapa con las coordenadas a capturar<br>
2.- <input type=button value='Captura Lat./Long. de Centro de Mapa' onClick='javascript:onMapClick();'>
Longitud:<input type=text size=12 name=longitud value='<?=$longitud?>'> Latitud:<input type=text size=12 name=latitud value='<?=$latitud?>'><br>
3.- <input type=button value='Guardar Latitud y Longitud' onClick='javascript:guardaCoord();'>
</font>
</td></tr></table>
</form>
</div>
</center>
<? } ?>
<center>
<div id="map" style="margin: 0px; width: <?=$widthmap?>px; height: <?=$heightmap?>px; z-index:2;"></div>
</center>
<? if ($V_mod=="") { ?>
</body>
</html>
<? } ?>
