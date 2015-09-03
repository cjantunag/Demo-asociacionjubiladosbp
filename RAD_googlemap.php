<?php
global $PHP_SELF;
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

/////////////////////////////////////////////////////////////////////////////////////
function RAD_googlemap($key,$width,$height,$latcenter,$longcenter,$zoom,$nummap) {
/* ====== EXAMPLE =========
echo RAD_googlemap(....)."
<script type='text/javascript'>
addMarker(43.37361,-8.42651,0,document.getElementById('msgdiv_".$nummap."').innerHTML);
initialize_map();
</script>
<div id='msgdiv_".$nummap."' style='display:none;'>
<table width='100%' cellpadding='0' cellspacing='0' border='0'>
<tr><td style='font-weight:bold;font-size:12px;'>Title</td></tr>
<tr><td>Text html example</td></tr>
</table>
</div>";
  ====== EXAMPLE ========= */
	return "
<script src='http://maps.google.com/maps/api/js?sensor=false' type='text/javascript'></script>
<script type='text/javascript'>
var map_".$nummap.";
var baseIcon;
var map_num_markers = 0;
var map_ready = false;
var map_array_lat = new Array();
var map_array_lng = new Array();
var map_array_index_".$nummap." = new Array();
var map_array_msg = new Array();
var timer_".$nummap.";
function initialize_map() {
	if (GBrowserIsCompatible()) {
		var mapOptions = { zoom: ".$zoom.", center: new google.maps.LatLng(".$latcenter.", ".$longcenter.") }
		map_".$nummap." = new google.maps.Map(document.getElementById('idmap_".$nummap."'));
		baseIcon = new google.maps.Icon(
			urlshadow : 'http://www.google.com/mapfiles/shadow50.png';
			size : new google.maps.Size(20, 34);
			shadowSize : new google.maps.Size(37, 34);
			anchor : new google.maps.Point(9, 34);
			infoWindowAnchor : new google.maps.Point(9, 2);
		);
		map_ready = true;
	}
}
function createMarker(map, point, index, msg) {
	var letter = String.fromCharCode('A'.charCodeAt(0) + index);
	var marker = new google.maps.Marker(point, map, 'http://www.google.com/mapfiles/marker' + letter + '.png');
	google.maps.Event.addListener(marker, 'click', function() { marker.openInfoWindowHtml('<div style=\"width:300px;\">'+msg+'</div>'); });
	return marker;
}
function addMarker(lat,lng,index,msg) {
	clearTimeout(timer_".$nummap.");
	map_array_lat[map_num_markers] = lat;
	map_array_lng[map_num_markers] = lng;
	map_array_index_".$nummap."[map_num_markers] = index;
	map_array_msg[map_num_markers] = msg;
	map_num_markers ++;
	timer_".$nummap." = setTimeout('loadMarkers()',1000);
}
function loadMarkers() {
	if(map_ready == false) 	{
		timer_".$nummap." = setTimeout('loadMarkers()',1000);
	} else 	{
		clearTimeout(timer_".$nummap.");
		for(var i = 0;i<map_num_markers;i++) {
			var point = new google.maps.LatLng(map_array_lat[i],map_array_lng[i]);
			map_".$nummap.".addOverlay(createMarker(map_".$nummap.",point,map_array_index_".$nummap."[i],map_array_msg[i]));
		}
	}
}
</script>
<div id='idmap_".$nummap."' style='height:".$height."px; width:".$width."px;'></div>
";
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_googlemapstatic($key,$width,$height,$latcenter,$longcenter,$zoom,$nummapi,$markers) {
/* ====== EXAMPLE =========
$markers="43.373,-8.4265,reda";
echo RAD_googlemap(....)."
<script type='text/javascript'>
addMarker(43.37361130234503,-8.426513671875,0,'HTML Example<br>.....');
initialize_map();
</script>";
  ====== EXAMPLE ========= */
	return "
<script src='http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=true&amp;key=".$key."' type='text/javascript' charset='iso-8859-1'></script>
<script type='text/javascript'>
var map_".$nummap.";
var baseIcon;
var map_num_markers = 0;
var map_ready = false;
var map_array_lat = new Array();
var map_array_lng = new Array();
var map_array_index_".$nummap." = new Array();
var map_array_msg = new Array();
var timer_".$nummap.";
function initialize_map() {
	if (GBrowserIsCompatible()) {
		map_".$nummap." = new google.maps.Map(document.getElementById('idmap_".$nummap."'));
		map_".$nummap.".setCenter(new google.maps.LatLng(".$latcenter.", ".$longcenter."), ".$zoom.");
		map_".$nummap.".setUIToDefault();
		baseIcon = new google.maps.Icon(
			urlshadow : 'http://www.google.com/mapfiles/shadow50.png';
			size : new google.maps.Size(20, 34);
			shadowSize : new google.maps.Size(37, 34);
			anchor : new google.maps.Point(9, 34);
			infoWindowAnchor : new google.maps.Point(9, 2);
		);
		map_ready = true;
	}
}
function createMarker(map, point, index, msg) {
	var letter = String.fromCharCode('A'.charCodeAt(0) + index);
	var marker = new google.maps.Marker(point, map, 'http://www.google.com/mapfiles/marker' + letter + '.png');
	google.maps.Event.addListener(marker, 'click', function() { marker.openInfoWindowHtml('<div style=\"width:300px;\">'+msg+'</div>'); });
	return marker;
}
function addMarker(lat,lng,index,msg) {
	clearTimeout(timer_".$nummap.");
	map_array_lat[map_num_markers] = lat;
	map_array_lng[map_num_markers] = lng;
	map_array_index_".$nummap."[map_num_markers] = index;
	map_array_msg[map_num_markers] = msg;
	map_num_markers ++;
	timer_".$nummap." = setTimeout('loadMarkers()',1000);
}
function loadMarkers() {
	if(map_ready == false) {
		timer_".$nummap." = setTimeout('loadMarkers()',1000);
	} else {
		clearTimeout(timer_".$nummap.");
		for(var i = 0;i<map_num_markers;i++) {
			var point = new google.maps.LatLng(map_array_lat[i],map_array_lng[i]);
			map_".$nummap.".addOverlay(createMarker(map_".$nummap.",point,map_array_index_".$nummap."[i],map_array_msg[i]));
		}
	}
}
</script>
<img id='static_map_".$nummap."' src='http://maps.google.com/staticmap?center=".$latcenter.",".$longcenter."&zoom=".$zoom."&size=".$height."x".$width."&maptype=mobile&markers=".$markers."&key=".$key."' border='0' />
<div id='idmap_".$nummap."' style='display:none;'></div>
";	
}
?>
