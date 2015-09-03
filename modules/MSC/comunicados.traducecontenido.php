<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");


//--------------------------------------------------------------------------------------------------------
function comunicadosTraduceContenido($TMP_contenido, $TMP_identidad, $TMP_idcontacto, $TMP_idproyecto) {
	global $RAD_dbi, $sesion;

	$TMP_monthNames = Array("", _JANUARY,_FEBRUARY,_MARCH,_APRIL,_MAY,_JUNE,_JULY,_AUGUST,_SEPTEMBER,_OCTOBER,_NOVEMBER,_DECEMBER);
	$TMP_mes=$TMP_monthNames[date("m")];

	$fecha=date("d")." de ".$TMP_mes." de ".date("Y");
	$usu=base64_decode(getSessionVar("SESSION_name"));
	$TMP_res=sql_query("SELECT * FROM paises", $RAD_dbi);
	while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
		$A_pais[$TMP_row[codpais]]=$TMP_row[pais];
	}
	$TMP_res=sql_query("SELECT * FROM provincias", $RAD_dbi);
	while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
		$A_prov[$TMP_row[codprovincia]]=$TMP_row[provincia];
	}
	$TMP_res=sql_query("SELECT * FROM municipios", $RAD_dbi);
	while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
		$A_muni[$TMP_row[idmunicipio]]=$TMP_row[municipio];
	}
	//$TMP_res=sql_query("SELECT * FROM GIE_tratamientos", $RAD_dbi);
	//while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
	//	$A_trat[$TMP_row[idtratamiento]]=$TMP_row[tratamiento];
	//}
	if ($TMP_idcontacto>0) {
		$TMP_res=sql_query("SELECT * FROM GIE_contactos where idcontacto='$TMP_idcontacto'", $RAD_dbi);
		$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
		$nombre=$TMP_row[nombre];
		$A_x=explode(",",$nombre);
		if (count($A_x)==2) {
			$nombreapellidos=trim($A_x[1])." ".trim($A_x[0]);
		} else {
			$nombreapellidos=$nombre;
		}
		$direccion=$TMP_row[domicilio];
		$sede=$TMP_row[sede];
		if (trim($TMP_row[codpostal])!="") $direccion.="\r\n".$TMP_row[codpostal];
		$pais=$A_pais[$TMP_row[codpais]];
		$poblacion=$TMP_row[poblacion];
		$provincia=$A_prov[$TMP_row[codprovincia]];
		$municipio=$A_muni[$TMP_row[idmunicipio]];
		if (trim($TMP_row[nif])!="") $condni="con DNI ".trim($TMP_row[nif]);
		else $condni="";

		$A_x=explode(",",$TMP_row[representante]);
		if (count($A_x)==2) $TMP_row[representante]=trim($A_x[1]." ".$A_x[0]);
		$representante=trim($TMP_row[representante]);
		if ($TMP_row[sexo]=="M") $vocalsexo="o";
		if ($TMP_row[sexo]=="F") $vocalsexo="a";
		if ($TMP_row[idtratamiento]>0) {
			$tratamiento=trim($A_trat[$TMP_row[idtratamiento]]);
		}
		$representante=$TMP_row[representante];
		if ($TMP_row[representante]=="" && trim($tratamiento)!="") {
			$nombre=trim($tratamiento." ".$nombre);
		} else {
			$representante=trim($tratamiento)." ".trim($TMP_row[representante]);
		}
	}
	$direccion=str_replace("\r","",$direccion);
	$direccion=str_replace("\n","<br>",$direccion);

	$TMP_contenido=str_replace("\$fecha",$fecha,$TMP_contenido);
	$TMP_contenido=str_replace("\$pais",$pais,$TMP_contenido);
	$TMP_contenido=str_replace("\$provincia",$provincia,$TMP_contenido);
	$TMP_contenido=str_replace("\$municipio",$municipio,$TMP_contenido);
	$TMP_contenido=str_replace("\$poblacion",$poblacion,$TMP_contenido);
	$TMP_contenido=str_replace("\$sede",$sede,$TMP_contenido);
	$TMP_contenido=str_replace("\$condni",$condni,$TMP_contenido);
	$TMP_contenido=str_replace("\$usuario",$usu,$TMP_contenido);
	$TMP_contenido=str_replace("\$entidad",$entidad,$TMP_contenido);
	$TMP_contenido=str_replace("\$nombreapellidos",$nombreapellidos,$TMP_contenido);
	$TMP_contenido=str_replace("\$nombre",$nombre,$TMP_contenido);
	$TMP_contenido=str_replace("\$tratamiento",$tratamiento,$TMP_contenido);
	$TMP_contenido=str_replace("\$vocalsexo",$vocalsexo,$TMP_contenido);
	$TMP_contenido=str_replace("\$direccion",$direccion,$TMP_contenido);
	$TMP_contenido=str_replace("\$sesion",$sesion,$TMP_contenido);
	//$TMP_contenido=utf8_encode($TMP_contenido);

	return $TMP_contenido;
}
?>
