<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

global $RAD_dbi;

$TMP_res=sql_query("SELECT * FROM proyectos", $RAD_dbi);
while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
	$A_idproyecto[$TMP_row[proyecto]]=$TMP_row[idproyecto];
}

$TMP_res=sql_query("SELECT * FROM voluntarios where idproyecto is null or idproyecto='' or idproyecto='0'", $RAD_dbi);
while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
	$idproyecto=$A_idproyecto[$TMP_row[proyecto]];
	if ($idproyecto>0) {
		$cmd="UPDATE voluntarios set idproyecto='$idproyecto' where idvoluntario='".$TMP_row[idvoluntario]."'";
		//echo $cmd."<br>";
		sql_query($cmd, $RAD_dbi);
	}
}

return "";
?>
