<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!-- Put your Google key below!!!! -->
<script src="http://maps.google.com/maps?file=api&v=1&key=ABQIAAAAHRAUiIUwHpxfgRpC6t2RlBR1aitd_Pbt0Clrv_l3m756RKKy0RRx9FnmATS6TW3OtymGa11VdG6DEg" type="text/javascript"></script>

<script>
var xml_file = "estadisticas.mapa.xml.php?IP=$IP";
var refresh = 0; // In seconds

var my_site = new GPoint (2.646418, 39.618251);
var cpoint = new GPoint (2.646418, 39.618251);

var hqpoint;
var baseicon;
var hqicon;
var map;
var hqmarker;
var map;

window.onload=drawMap;
function drawMap () {
  my_site = new GPoint (2.646418, 39.618251);
  hqpoint = my_site;
  baseicon = new GIcon ();
  baseicon.shadow = "http://labs.google.com/ridefinder/images/mm_20_shadow.png";
  baseicon.iconSize = new GSize (12, 20);
  baseicon.shadowSize = new GSize (22, 20);
  baseicon.iconAnchor = new GPoint (6, 20);
  baseicon.infoWindowAnchor = new GPoint (5, 1);

  var hqicon = new GIcon (baseicon);
  hqicon.image = "http://labs.google.com/ridefinder/images/mm_20_white.png";

  // Center the map
  map = new GMap (document.getElementById ("map"));
  map.addControl (new GLargeMapControl ());
  map.addControl (new GMapTypeControl ());
  map.centerAndZoom (cpoint, 15);


  hqmarker = new GMarker (hqpoint, hqicon);
  var hqhtml = "<small><b>http://mysite/<br />my site</b><br>";
  GEvent.addListener (hqmarker, "click", function () {
		      hqmarker.openInfoWindowHtml (hqhtml);
		      }
  );
  load ();
}

function cz (p, z) { map.centerAndZoom (p, z); }
// Creates a marker whose info window displays the given number
function createMarker (point, ip, city, country) {
  var icon = new GIcon (baseicon);
  if (ip == 1) color = 'green';
  else if (ip == 2) color = 'blue';
  else if (ip == 3) color = 'yellow';
  else if (ip < 5) color = 'orange';
  else if (ip < 8) color = 'red';
  else color = 'purple';
  icon.image = "http://labs.google.com/ridefinder/images/mm_20_" + color + ".png";

  var marker = new GMarker (point, icon);

  // Show this marker's index in the info window when it is clicked
  var msg = "<small><b>Number:</b> "+ip+"<br/><b>City:</b> "+city+"<br/><b>Country:</b> "+country+"</small>";

  GEvent.addListener (marker, "click", function () { marker.openInfoWindowHtml(msg); });
  return marker;
}

function load () {
  // Download the data in map.xml and load it on the map.
  var request = GXmlHttp.create ();
  var time;
  request.open ("GET", xml_file, true);
  request.onreadystatechange = function () {
    if (request.readyState == 4) {
	map.clearOverlays ();
	map.addOverlay (hqmarker);
	var xmlDoc = request.responseXML;
	var markers = xmlDoc.documentElement.getElementsByTagName ("marker");
	for (var i = 0; i < markers.length; i++) {
	    var point = new GPoint (parseFloat (markers[i].getAttribute ("lng")),
			  parseFloat (markers[i].getAttribute ("lat")));
	    var marker = createMarker (point, markers[i].getAttribute ("ip"),
				markers[i].getAttribute ("city"), markers[i].getAttribute ("country"));
	    map.addOverlay (marker);
	  }
      }
  }
  //hqmarker.openInfoWindowHtml(hqhtml);
  request.send (null);
  if((element = document.getElementById("clock"))) {
  	time = dat_time();
  	element.innerHTML=time;
  }
  if (refresh>0) window.setTimeout ("load()", refresh*1000);
}

function dat_time() {
   var d = new Date();
   var hour = d.getHours();
   var min = d.getMinutes();
   var sec = d.getSeconds();
   if (hour < 10) hour = "0" + hour;
   if (min < 10) min = "0" + min;
   if (sec < 10) sec = "0" + sec;
   var t = hour + ":" + min + ":" + sec;
   return t;
}

</script>
</head>
<body>

<div id="map" style="width: 800px; height: 600px"></div>

<div align="center"><small><b><span id="clock">00:00:00</span></b></small></div>

</body>
</html>

