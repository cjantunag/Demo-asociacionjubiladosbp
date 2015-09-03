<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");


//--------------------------------------------------------------------------------
function comunicadosImprime($TMP_idcomunicado, $TMP_identidad, $TMP_idcontacto, $TMP_idproyecto) {
	global $RAD_dbi;

	$cmd="SELECT * FROM GIE_comunicados WHERE ";
	if ($TMP_identidad) $cmd.="identidad='$TMP_identidad'";
	else if ($TMP_idcontacto) $cmd.="idcontacto='$TMP_idcontacto'";
	else if ($TMP_idproyecto) $cmd.="idproyecto='$TMP_idproyecto'";
	else if (ereg(",",$TMP_idcomunicado)) $cmd.="idcomunicado IN ($TMP_idcomunicado)";
	else $cmd.="idcomunicado='$TMP_idcomunicado'";
	$TMP_res=sql_query($cmd, $RAD_dbi);
	$TMP_contenido=""; $TMP_numpag=0;
	while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
		$TMP_numpag++;
		$TMP_contenido.="<table style='margin:0px; padding:0px; text-align=center;";
		if ($TMP_numpag>1) $TMP_contenido.=" page-break-before:always;";
		$TMP_contenido.="'><tr><td>\n".$TMP_row[contenido]."\n</td></tr></table>\n";
	}

ob_end_clean();
ob_start();

?>
<html>
<title></title>
<STYLE type=text/css>
BODY, UNKNOW, TD, TH { FONT-SIZE: 12px; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; background-color:white; }
TH, B { FONT-WEIGHT: bold; }
H1 { FONT-WEIGHT: bold; FONT-SIZE: 14px; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; background-color:white; } 
TH, TD { vertical-align: top; }
</STYLE>
</HEAD>
<BODY bgcolor=white leftmargin=0 topmargin=0 marginheight=0 marginwidth=0>

<?php echo $TMP_contenido; ?>

<script>
window.print();
</script>
</body>
</html>
<?php die(); } ?>
