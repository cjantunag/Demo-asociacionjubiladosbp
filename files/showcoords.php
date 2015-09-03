<?php
global $_REQUEST;
$latitud=$_REQUEST[latitud];
$longitud=$_REQUEST[longitud];
$zoom=$_REQUEST[zoom];
$id=$_REQUEST[id];
$id=str_replace(" ","",$id); $id=str_replace(";","",$id); $id=str_replace("'","",$id); $id=str_replace('"',"",$id);
$key=$_REQUEST[key];
$key=str_replace(" ","",$key); $key=str_replace(";","",$key); $key=str_replace("'","",$key); $key=str_replace('"',"",$key);
$field=$_REQUEST[field];
$field=str_replace(" ","",$field); $field=str_replace(";","",$field); $field=str_replace("'","",$field); $field=str_replace('"',"",$field);
$table=$_REQUEST[table];
$table=str_replace(" ","",$table); $table=str_replace(";","",$table); $table=str_replace("'","",$table); $table=str_replace('"',"",$table);

require_once("../functions.php");
$dbname=$_REQUEST[db];
if ($dbname!="") define(_DEF_dbname,$dbname);
require_once("../config.php");
include_once("../sqlDB.php");
$RAD_dbi = sql_connect(_DEF_dbhost, _DEF_dbuname, _DEF_dbpass, _DEF_dbname);

if ($latitud=="") $latitud=40.229218;
if ($longitud=="") $longitud=-4.26269;
if ($zoom=="") $zoom=6;

$A_fields=explode(",",",".$field.","); $kj=0;
for($ki=0; $ki<count($A_fields); $ki++) { if (trim($A_fields[$ki])!="") {$fields[$kj]=$A_fields[$ki]; $kj++; } }
$res=@sql_query("SELECT * FROM $table WHERE $key='$id'",$RAD_dbi);
while($row=@sql_fetch_array($res,$RAD_dbi)) {
	$descripcion=trim($row[$fields[0]]);
	for ($ki=0; $ki<count($fields); $ki++) {
		if ($ki==0) $descripcioncompleta="<b>";
		else $descripcioncompleta.="<small>";
		$descripcioncompleta.=$row[$fields[$ki]];
		if ($ki==0) $descripcioncompleta.="</b> ";
		else $descripcioncompleta.="</small> ";
	}
	$descripcioncompleta=trim($descripcioncompleta);
	$marcas.="
	var point=new google.maps.LatLng(".$row[latitud].", ".$row[longitud].");
	var marker=new google.maps.Marker(position:point, map:map, icon:'http://labs.google.com/ridefinder/images/mm_20_red.png');
	google.maps.event.addListener(marker, 'click', function() {
	    var infowindow = new google.maps.InfoWindow({ content:'".$descripcioncompleta."' });
	    infowindow.open(map,marker);
	  });
";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1"/>
<title>Coordenadas de <?=$descripcion?></title>
<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
<script type="text/javascript">
//<![CDATA[
var map;
function onLoad() {
	//var myOptions = { zoom: <?=$zoom?>, center: new google.maps.LatLng(<?=$latitud?>,<?=$longitud?>), mapTypeId: google.maps.MapTypeId.ROADMAP, mapTypeControl: true, scaleControl: true, overviewMapControl: true, overviewMapControlOptions: { opened: true } }
	var myOptions = { zoom: <?=$zoom?>, center: new google.maps.LatLng(<?=$latitud?>,<?=$longitud?>), mapTypeControl: true, scaleControl: true, overviewMapControl: true, overviewMapControlOptions: { opened: true } }
	map = new google.maps.Map(document.getElementById("map"), myOptions);

<?=$marcas?>
}
//]]>
</script>
</head>
<body onload="onLoad()" marginwidth=0 marginheight=0 leftmargin=0 topmargin=0>
<center><font style='font-family:Arial,Verdana,Helvetica,Sans; font-size:16px; font-weight:bold;'>Localizador de Coordenadas</font><br>

<div id="map" style="margin: 0px; width: 750px; height: 550px; z-index:2;"></div>

</center>
</body>
</html>
