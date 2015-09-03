<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

global $RAD_dbi;

$TMP_res=sql_query("SELECT * FROM proyectos", $RAD_dbi);
while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
	$A_idproyecto[$TMP_row[proyecto]]=$TMP_row[idproyecto];
}

$TMP_res=sql_query("SELECT * FROM donativos where idproyecto is null or idproyecto='' or idproyecto='0'", $RAD_dbi);
while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
	$idproyecto=$A_idproyecto[$TMP_row[proyecto]];
	if ($idproyecto>0) {
		$cmd="UPDATE donativos set idproyecto='$idproyecto' where iddonativo='".$TMP_row[iddonativo]."'";
		//echo $cmd."<br>";
		sql_query($cmd, $RAD_dbi);
	}
}

return "";
?>
