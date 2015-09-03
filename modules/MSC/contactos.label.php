<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

global $RAD_dbi;

if ($op=="label") {
	ob_end_clean();
?>
<html>
<title>ETIQUETAS</title>
<STYLE type=text/css>
BODY, UNKNOW, TD, TH { FONT-SIZE: 14px; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; background-color:white; }
TH, B { FONT-WEIGHT: bold; background-color:white; } 
H1 { FONT-WEIGHT: bold; FONT-SIZE: 14px; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; background-color:white; }
TH, TD { vertical-align: top; }
TD.derecha { text-align: right; }
TH { FONT-WEIGHT: bold; FONT-SIZE: 14px; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; border: 1px black solid; margin:0px; padding:2px;}
</STYLE>
<BODY>
<?	if ($par0>0) {
		$WHERE="WHERE idcontacto='$par0'";
		if ($defaultfilter!="") $WHERE.=" AND ".$defaultfilter;
	} else {
		if ($defaultfilter!="") $WHERE.="WHERE ".$defaultfilter;
	}
	$cmd="SELECT * FROM $tablename ".$WHERE;
	$res=sql_query($cmd,$RAD_dbi);
	$cont=0;
	echo "\n<table width=900 border=0 cellpadding=21 cellspacing=0>\n";
	while($row=sql_fetch_array($res, $RAD_dbi)) {
		$direccion=str_replace("\n","",$row[domicilio]);
		$direccion=str_replace("\r","",$direccion);
		$res2=sql_query("SELECT * FROM provincias WHERE codprovincia='".$row[codprovincia]."'", $RAD_dbi);
		$row2=sql_fetch_array($res2, $RAD_dbi);
		$provincia=$row2[provincia];
		$res2=sql_query("SELECT * FROM municipios WHERE idmunicipio='".$row[idmunicipio]."'", $RAD_dbi);
		$row2=sql_fetch_array($res2, $RAD_dbi);
		$municipio=$row2[municipio];
		if ($municipio=="") $municipio=$provincia;

		if ($cont%2==0) {
			if ($cont>0) echo "</td></tr>\n";
			echo "<tr valign=top>\n<td width=50%>\n";
		} else echo "</td><td width=50%>\n";
		echo " <table height=100 width=450 style='border:1px #D0D0D0 solid;' cellpadding=8 cellspacing=0>\n";
		echo " <td width=100%>".$row[nombre]."<br>".$direccion."<br>";
		echo $row[codpostal]." ".$municipio."</td>";
		echo " </table>\n";
		$cont++;
	}
	if ($cont%2==1) echo "<td></td>\n";
	if ($cont>0) echo "</td></tr>\n";
	echo "</table>\n";
	echo "\n<script>\nwindow.print();\n</script>\n</html>\n";
	die();
}

?>
