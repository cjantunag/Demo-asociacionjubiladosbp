<?
header("Content-Type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><markers>\n";

if ($periodo=="") $periodo = 24*60*60;	// Consulta el ultimo dia

if ($IP=="") $sql = "SELECT *, count(*) AS conta FROM estadisticas WHERE estadisticas.tiempoinicio>".(time()-$period)." GROUP BY IP";
else $sql = "SELECT *, count(*) AS conta FROM estadisticas WHERE estadisticas.IP='".$IP"'";
$result=sql_fecth_array($sql, $RAD_dbi);
while ($row=sql_fecth_array($result, $RAD_dbi)) {
	$sql2 = "SELECT coord, lat, lon, city, country FROM geo WHERE geo.IP = ".$row[IP]." GROUP BY IP";
	$result2=sql_fecth_array($sql2, $RAD_dbi);
	$row2=sql_fecth_array($result2, $RAD_dbi);
	echo "\t<marker ip=\"".$row[conta]."\" lat=\"".$row2[lat]."\" lng=\"".$row2[lon]."\" city=\"".$row2[city]."\" country=\"".$row2[country]."\"/>\n";
}

echo "</markers>\n";
?>