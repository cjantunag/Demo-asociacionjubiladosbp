<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

global $RAD_dbi;

//if (is_admin()) foreach($_REQUEST as $TMP_k=>$TMP_v) echo $TMP_k."=".$TMP_v."<br>";
if ($V_mod=="imagenes" && $V_prevmod=="acciones") $fields[$findex[imagenes]]->help="ancho m&iacute;nimo=156 p&iacute;xeles; alto m&iacute;nimo=151 p&iacute;xeles";

$TMP_lang=getSessionVar("SESSION_lang");
//foreach($_REQUEST as $TMP_k=>$TMP_v) echo $TMP_k."=".$TMP_v."<br>";
if ($V_prevmod=="proyectos" && $V_mod=="imagenes") $fields[$findex[imagenes]]->help.=". Ancho m&iacute;nimo 207 p&iacute;xeles. Alto m&iacute;nimo 151 p&iacute;xeles";
if($findex[idarticulo]>0 && $tablename!="GIE_ventasdetalle") {
	$TMP_opts="";
	$TMP_res=sql_query("SELECT * FROM articulossecciones order by orden", $RAD_dbi);
	while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
		if (trim($TMP_row["nombre_".$TMP_lang])!="") $TMP_row[nombre]=$TMP_row["nombre_".$TMP_lang];
		//$TMP_res2=sql_query("SELECT * FROM articulos where visible='1' and idseccion='".$TMP_row[id]."' order by orden", $RAD_dbi);
		$TMP_res2=sql_query("SELECT * FROM articulos where idseccion='".$TMP_row[id]."' order by orden", $RAD_dbi);
		while($TMP_row2=sql_fetch_array($TMP_res2, $RAD_dbi)) {
			if ($TMP_row2[orden]<0) continue;
			if (trim($TMP_row2["nombre_".$TMP_lang])!="") $TMP_row2[nombre]=$TMP_row2["nombre_".$TMP_lang];
			$TMP_sec=$TMP_row[nombre]." --> ".$TMP_row2[nombre];
			$TMP_sec=str_replace(":"," ",$TMP_sec);
			$TMP_sec=str_replace(","," ",$TMP_sec);
			$TMP_sec=str_replace("|"," ",$TMP_sec);
			$TMP_opts.=",".$TMP_row2[id].":".$TMP_sec;
		}
	}
	$fields[$findex[idarticulo]]->extra=substr($TMP_opts,1);
	$fields[$findex[idarticulo]]->dtype="plist";
}

?>
