<?php
	if (eregi(basename(__FILE__), $PHP_SELF))  die ("Security Error ...");
		
	function limpiarURL($cadena){			
		$caracteres_no_validos= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","Ñ","À","È","Ì","Ò","Ù","à","è","ì","ò","ù","ç","Ç","â","ê","î","ô","û","Â","Ê","Î","Ô","Û","ü","ö","Ö","ï","ä","ë","Ü","Ï","Ä","Ë","(",")","/",".");
		$caracteres_validos= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E","","","-","");
		$cadena=str_replace($caracteres_no_validos,$caracteres_validos,$cadena);
		$cadena_out= preg_replace("/[^a-zA-Z0-9-._\/\?\=;]/", "", $cadena);
		$cadena_out = strtolower($cadena_out);
		return $cadena_out;
	}
	
?>