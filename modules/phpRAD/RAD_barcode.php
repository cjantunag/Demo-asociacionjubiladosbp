<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

// if ($func != "detail" || $subfunc != "codbarr") return;

//--------------------------------------------------------------------------------------------------
// Formulario HTML que pide datos para imprimir Codigo de Barras de Registro
if ($op!="print") {
	echo "
<script>
function cambiaPapel() {
  numeti=document.F.numeti.value;
  primera=document.F.primera.value;
  if (document.F.tipopapel[0].checked) {
	if(parseInt(primera)>8) {
		document.F.primera.value=1;
	}
  }
  if (document.F.tipopapel[1].checked) {
	if(parseInt(primera)>16) {
		document.F.primera.value=1;
	}
  }
  primera=document.F.primera.value;
  ultima=parseInt(primera)+parseInt(numeti)-1;
  if (document.F.tipopapel[0].checked) {
	document.getElementById(\"P1\").style.borderColor=\"#ff0000\";
	document.F.numeti.disabled=false;
	document.F.primera.disabled=false;
	for(i=1; i<9; i++) {
		celda = document.getElementById(\"P1_\"+i);
		celda.style.borderColor=\"#ff0000\";
		if (i<primera) {
			celda.style.backgroundColor=\"#ffffff\";
		} else if (i>ultima) {
			celda.style.backgroundColor=\"#ffffff\";
		} else {
			celda.style.backgroundColor=\"#dddddd\";
		}
		celda.color=\"#404040\";
	}
	document.getElementById(\"P2\").style.borderColor=\"#f0f0f0\";
	for(i=1; i<17; i++) {
		celda = document.getElementById(\"P2_\"+i);
		celda.style.borderColor=\"#f0f0f0\";
		celda.style.backgroundColor=\"#ffffff\";
		celda.color=\"#c0c0c0\";
	}
	document.getElementById(\"P3\").style.borderColor=\"#f0f0f0\";
  }
  if (document.F.tipopapel[1].checked) {
	document.getElementById(\"P2\").style.borderColor=\"#ff0000\";
	document.F.numeti.disabled=false;
	document.F.primera.disabled=false;
	for(i=1; i<17; i++) {
		celda = document.getElementById(\"P2_\"+i);
		celda.style.borderColor=\"#ff0000\";
		if (i<primera) {
			celda.style.backgroundColor=\"#ffffff\";
		} else if (i>ultima) {
			celda.style.backgroundColor=\"#ffffff\";
		} else {
			celda.style.backgroundColor=\"#dddddd\";
		}
		celda.color=\"#404040\";
	}
	document.getElementById(\"P1\").style.borderColor=\"#f0f0f0\";
	for(i=1; i<9; i++) {
		celda = document.getElementById(\"P1_\"+i);
		celda.style.borderColor=\"#f0f0f0\";
		celda.style.backgroundColor=\"#ffffff\";
		celda.color=\"#c0c0c0\";
	}
	document.getElementById(\"P3\").style.borderColor=\"#f0f0f0\";
  }
  if (document.F.tipopapel[2].checked) {
	document.getElementById(\"P1\").style.borderColor=\"#f0f0f0\";
	//document.F.numeti.disabled=true;
	document.F.primera.disabled=true;
	for(i=1; i<9; i++) {
		celda = document.getElementById(\"P1_\"+i);
		celda.style.borderColor=\"#f0f0f0\";
		celda.style.backgroundColor=\"#ffffff\";
		celda.color=\"#c0c0c0\";
	}
	document.getElementById(\"P2\").style.borderColor=\"#f0f0f0\";
	for(i=1; i<17; i++) {
		celda = document.getElementById(\"P2_\"+i);
		celda.style.borderColor=\"#f0f0f0\";
		celda.style.backgroundColor=\"#ffffff\";
		celda.color=\"#c0c0c0\";
	}
	document.getElementById(\"P3\").style.borderColor=\"#ff0000\";
  }
}
function chgPapel(tipo,primera) {
	document.F.primera.value=primera;
	if (tipo==1) document.F.tipopapel[0].checked=8;
	if (tipo==2) document.F.tipopapel[1].checked=16;
	if (tipo==3) document.F.tipopapel[2].checked=1;
	cambiaPapel();
}
</script>
"; 
	echo "<form name=F action='".$PHP_SELF."' target=_blank method=GET>\n";
	foreach($_REQUEST as $TMP_k=>$TMP_v) {
		$TMP_v=str_replace("(","",$TMP_v);
		$TMP_v=str_replace(")","",$TMP_v);
		$TMP_v=str_replace("|","",$TMP_v);
		$TMP_v=str_replace("'","",$TMP_v);
		$TMP_v=str_replace('"',"",$TMP_v);
		if ($TMP_v=="") continue;
		if (!in_array($TMP_k,array("RAD_errorstr","menuoff","func","op","blocksoff","headeroff","footeroff")) && $TMP_v!="") 
			echo "<input type=hidden name='".$TMP_k."' value='".$TMP_v."'>\n";
	}
	echo "<input type=hidden name='func' value='".$func."'>\n";
	echo "<input type=hidden name='op' value='print'>\n";
	echo "<input type=hidden name='blocksoff' value='x'>\n";
	echo "<table class=detail>";
	echo "<tr class=browse><th class=browse colspan=2>IMPRESION DE CODIGO DE BARRAS DE REGISTRO</th></tr>";
	echo "<tr><td colspan=2 align=center>";

	echo "<br><table border=0 align=center width=400><tr><td width=180 align=center>";
	echo "<table id='P1' style='width:120px; border:solid 2px #d8d8d8; border-collapse:collapse; padding:0; margin:0'>";
	echo "<tr><td onclick='javascript:chgPapel(1,1);' id='P1_1' style='width:60px; height:33px; border:dotted 2px #d8d8d8; padding:0; margin:0; vertical-align:middle; text-align:center'>1</td>";
	echo "<td onclick='javascript:chgPapel(1,2);' id='P1_2' style='width:60px; height:33px; border:dotted 2px #d8d8d8; padding:0; margin:0; vertical-align:middle; text-align:center'>2</td></tr>";
	echo "<tr><td onclick='javascript:chgPapel(1,3);' id='P1_3' style='width:60px; height:33px; border:dotted 2px #d8d8d8; padding:0; margin:0; vertical-align:middle; text-align:center'>3</td>";
	echo "<td onclick='javascript:chgPapel(1,4);' id='P1_4' style='width:60px; height:33px; border:dotted 2px #d8d8d8; padding:0; margin:0; vertical-align:middle; text-align:center'>4</td></tr>";
	echo "<tr><td onclick='javascript:chgPapel(1,5);' id='P1_5' style='width:60px; height:33px; border:dotted 2px #d8d8d8; padding:0; margin:0; vertical-align:middle; text-align:center'>5</td>";
	echo "<td onclick='javascript:chgPapel(1,6);' id='P1_6' style='width:60px; height:33px; border:dotted 2px #d8d8d8; padding:0; margin:0; vertical-align:middle; text-align:center'>6</td></tr>";
	echo "<tr><td onclick='javascript:chgPapel(1,7);' id='P1_7' style='width:60px; height:33px; border:dotted 2px #d8d8d8; padding:0; margin:0; vertical-align:middle; text-align:center'>7</td>";
	echo "<td onclick='javascript:chgPapel(1,8);' id='P1_8' style='width:60px; height:33px; border:dotted 2px #d8d8d8; padding:0; margin:0; vertical-align:middle; text-align:center'>8</td></tr>";
	echo "</table></td><td width=180 align=center>";
	echo "<table id='P2' style='width:120px; border:solid 2px #d8d8d8; border-collapse:collapse; padding:0; margin:0'>";
	echo "<tr><td onclick='javascript:chgPapel(2,1);' id='P2_1' style='width:60px; height:12px; border:dotted 2px #d8d8d8; padding:0; margin:0; vertical-align:middle; text-align:center'>1</td>";
	echo "<td onclick='javascript:chgPapel(2,2);' id='P2_2' style='width:60px; height:12px; border:dotted 2px #d8d8d8; padding:0; margin:0; vertical-align:middle; text-align:center'>2</td></tr>";
	echo "<tr><td onclick='javascript:chgPapel(2,3);' id='P2_3' style='width:60px; height:12px; border:dotted 2px #d8d8d8; padding:0; margin:0; vertical-align:middle; text-align:center'>3</td>";
	echo "<td onclick='javascript:chgPapel(2,4);' id='P2_4' style='width:60px; height:12px; border:dotted 2px #d8d8d8; padding:0; margin:0; vertical-align:middle; text-align:center'>4</td></tr>";
	echo "<tr><td onclick='javascript:chgPapel(2,5);' id='P2_5' style='width:60px; height:12px; border:dotted 2px #d8d8d8; padding:0; margin:0; vertical-align:middle; text-align:center'>5</td>";
	echo "<td onclick='javascript:chgPapel(2,6);' id='P2_6' style='width:60px; height:12px; border:dotted 2px #d8d8d8; padding:0; margin:0; vertical-align:middle; text-align:center'>6</td></tr>";
	echo "<tr><td onclick='javascript:chgPapel(2,7);' id='P2_7' style='width:60px; height:12px; border:dotted 2px #d8d8d8; padding:0; margin:0; vertical-align:middle; text-align:center'>7</td>";
	echo "<td onclick='javascript:chgPapel(2,8);' id='P2_8' style='width:60px; height:12px; border:dotted 2px #d8d8d8; padding:0; margin:0; vertical-align:middle; text-align:center'>8</td></tr>";
	echo "<tr><td onclick='javascript:chgPapel(2,9);' id='P2_9' style='width:60px; height:12px; border:dotted 2px #d8d8d8; padding:0; margin:0; vertical-align:middle; text-align:center'>9</td>";
	echo "<td onclick='javascript:chgPapel(2,10);' onclick='javascript:chgPapel(2,1);' id='P2_10' style='width:60px; height:12px; border:dotted 2px #d8d8d8; padding:0; margin:0; vertical-align:middle; text-align:center'>10</td></tr>";
	echo "<tr><td onclick='javascript:chgPapel(2,11);' id='P2_11' style='width:60px; height:12px; border:dotted 2px #d8d8d8; padding:0; margin:0; vertical-align:middle; text-align:center'>11</td>";
	echo "<td onclick='javascript:chgPapel(2,12);' id='P2_12' style='width:60px; height:12px; border:dotted 2px #d8d8d8; padding:0; margin:0; vertical-align:middle; text-align:center'>12</td></tr>";
	echo "<tr><td onclick='javascript:chgPapel(2,13);' id='P2_13' style='width:60px; height:12px; border:dotted 2px #d8d8d8; padding:0; margin:0; vertical-align:middle; text-align:center'>13</td>";
	echo "<td onclick='javascript:chgPapel(2,14);' id='P2_14' style='width:60px; height:12px; border:dotted 2px #d8d8d8; padding:0; margin:0; vertical-align:middle; text-align:center'>14</td></tr>";
	echo "<tr><td onclick='javascript:chgPapel(2,15);' id='P2_15' style='width:60px; height:12px; border:dotted 2px #d8d8d8; padding:0; margin:0; vertical-align:middle; text-align:center'>15</td>";
	echo "<td onclick='javascript:chgPapel(2,16);' id='P2_16' style='width:60px; height:12px; border:dotted 2px #d8d8d8; padding:0; margin:0; vertical-align:middle; text-align:center'>16</td></tr>";
	echo "</table></td><td width=180 align=center>";
	echo "<table id='P3' style='width:120px; border:solid 2px #d8d8d8; border-collapse:collapse; padding:0; margin:0'>";
	echo "<tr><td onclick='javascript:chgPapel(3,1);' id='P2_1' style='width:120px; height:130px; border:dotted 2px #d8d8d8; padding:0; margin:0; vertical-align:middle; text-align:center'>SOBRE</td></tr>";
	echo "</table>";
	
	echo "</td></tr></table>";

	echo "<br></td></tr>";
	$TMP_seltipo=RAD_editfield("tipopapel", "rlist", 1, 1, "8:8 C&oacute;digos/Hoja,16:16 C&oacute;digos/Hoja,1:Tipo Sobre", "cambiaPapel();", true, "8", "tipopapel", "F", "");
	echo "<tr class=detail><td class=detailtit>Tipo Papel:</td><td class=detail>".$TMP_seltipo."</td></tr>";
	echo "<tr class=detail><td class=detailtit>Primer C&oacute;digo de Barras:</td><td class=detail><input onkeyUp='javascript:cambiaPapel();' type=text value='1' size=4 name=primera></td></tr>";
	echo "<tr class=detail><td class=detailtit>Num. Copias:</td><td class=detail><input onkeyUp='javascript:cambiaPapel();' type=text value='1' size=4 name=numeti></td></tr>";
	echo "<tr><td colspan=2 align=center><input type=submit value=' IMPRIMIR ' onclick='javascript:setTimeout(\"closePop()\",500);'></td></tr>";
	echo "</table>\n<script>\ndocument.F.primera.focus();\ncambiaPapel();\n</script>\n";
	echo "<p>Se debe configurar el navegador para imprimir con los m&aacute;rgenes a cero, sin t&iacute;tulos ni n&uacute;meros de p&aacute;gina, y con la escala al 100%. Antes de imprimir se recomienda visualizar como queda el resultado mediante la Vista preliminar.<br></p>";
}


//-------------------------------------------------------------------------------------------------------
// IMPRIME Codigo de Barras de Registro. Recibe los parametros:
// numeti= numero de codigos de barras repetidos, 1 por defecto
// primera= numero de primer codigo de barras a imprimir en la primera hoja
// tipopapel: 8 o 16 codigos de barras por hoja, o 1 sobre
if ($op=="print") {
	global $numeti, $primera, $tipopapel, $par0;
	
	if ($tipopapel!="1" && $tipopapel!="8" && $tipopapel!="16") $tipopapel="16";
	if (!is_numeric($primera)) $primera="1";
	if ($primera<1||$primera>$tipopapel) $primera="1";
	if (!is_numeric($numeti)) $numeti="1";
	if ($numeti>100) die("No se permiten imprimir mas de 100 Codigos de Barras");

// 1 pagina max A4 8.5in x 11in. 816px x 1056 px o  612pt x 792pt
// 1 pagina A4 con margenes normales 794px x 1123px o 595pt x 842pt a 96 dpi 
// 1in=2.54cm. 72pt=1in. 1px=0.75pt. 96px=1in (96dpi)

	$chrome=false;
	if (preg_match("/Chrom/",$_SERVER["HTTP_USER_AGENT"])) $chrome=true;

	if ($tipopapel=="8") { 		// A4 8 codigos de barra
		$altoetiqueta="272px";	// 264px o 153pt
		$anchoetiqueta="381px";	// 408px o 306pt
		$anchopagina="762px";	// 816px o 612pt
		$altopagina="1088px";	// 1056px o 792pt
		if ($chrome) { 		// Si es Chrome aumentar 1px los altos
			$altoetiqueta="287px";	// 264px o 153pt
		}
	} else if ($tipopapel=="1") { 	// Carta
		$altoetiqueta="320px";	// 320px o 240px
		$altopagina="320px";	// idem
		$anchoetiqueta="550px";	// 550px o 412pt
		$anchopagina="550px";	// idem
	} else {			// A4 16 codigos de barras
		$altoetiqueta="136px";	// 132px o 99pt	
		$anchoetiqueta="381px";	// 408px o 306pt
		$anchopagina="762px";	// 816px 0 612pt
		$altopagina="1088px";	// 1056px o 792pt
		if ($chrome) { 		// Si es Chrome aumentar 1px los altos
			$altoetiqueta="139px";	// 132px o 99pt	
		}
	}

	ob_end_clean();

// En Chrome BODY { margin-top:14px; margin-left:30px; } y aumenta 1 o 2 px a los altos
?>
<html>
<title>BARCODE</title>
<STYLE type=text/css>
<?
if ($tipopapel=="1") echo "
BODY, TD, TH { FONT-SIZE: 16px; FONT-FAMILY: Arial, Helvetica, sans-serif; color:black; background-color:white; margin:0 auto; padding:0; border:0; }
TH, B { FONT-WEIGHT: bold; } 
TR, TH, TD { vertical-align: top; border:0; margin:0 auto; padding:0; }
#navigation, #footer { display: none }
TABLE { border-collapse:collapse; border:0px solid black; margin:0 auto; padding:0; page-break-after: avoid }
@page { size: A4 portrait; width:210mm; height:297mm; margin-top:0cm; margin-bottom:0cm; margin-left:0cm; margin-right:0cm; 
    @top-left-corner { content: ''; border:0; }
    @top-right-corner { content: ''; border:0; }
    @bottom-right-corner { content: ''; border:0; }
    @bottom-left-corner { content: ''; border:0; }
}
";
else echo "
BODY, TD, TH { FONT-SIZE: 14px; FONT-FAMILY: Arial, Helvetica, sans-serif; color:black; background-color:white; margin:0 auto; padding:0; border:0; }
TH, B { FONT-WEIGHT: bold; } 
TR, TH, TD { vertical-align: top; border:0; margin:0 auto; padding:0; }
#navigation, #footer { display: none }
TABLE { border-collapse:collapse; border:0px solid black; margin:0 auto; padding:0; page-break-after: avoid }
@page { size: A4 portrait; width:210mm; height:297mm; margin-top:0cm; margin-bottom:0cm; margin-left:0cm; margin-right:0cm; 
    @top-left-corner { content: ''; border:0; }
    @top-right-corner { content: ''; border:0; }
    @bottom-right-corner { content: ''; border:0; }
    @bottom-left-corner { content: ''; border:0; }
}
";
if ($chrome) {
	echo "TABLE.pagina { margin-top:15px; margin-left:20px; }\n";
	if ($tipopapel=="16") $altoetiqueta=$altoetiqueta-3;
	if ($tipopapel=="8") $altoetiqueta=$altoetiqueta-15;
	$anchoetiqueta-=10;
	$paddingetiqueta="border:0px solid red;margin-left:20px;margin-top:15px;";
	$altoetiqueta-=15;
	$anchoetiqueta-=20;
} else {
	$paddingetiqueta="border:0px solid red;margin-left:20px;margin-top:15px;";
	$altoetiqueta-=15;
	$anchoetiqueta-=20;
}
?>
</STYLE>
<BODY>
<?

	$celda=1;
	$numpagina=1;
	//$pagina="<table class='pagina' style='page-break-after:always; height:".$altopagina."; width:".$anchopagina.";'>\n";
	$pagina="<table class='pagina' style='height:".$altopagina."; width:".$anchopagina.";'>\n";
	if ($primera>1) for($ki=1; $ki<$primera; $ki++) {
		if ($celda%2==1) { // las celdas impares abren nueva fila
			if ($celda>1) $pagina.="</tr>\n";
			$pagina.="<tr style='height:".$altoetiqueta."; width:".$anchoetiqueta.";'>\n";
		}
		$etiqueta="<table style='".$paddingetiqueta." height:".$altoetiqueta."; width:".$anchoetiqueta.";'><tr><td>&nbsp;</td></tr></table>\n";
		$pagina.="<td style='height:".$altoetiqueta."; width:".$anchoetiqueta.";'>\n".$etiqueta."</td>\n";
		$celda++;
	}

	$etiqueta="<table style='".$paddingetiqueta." height:".$altoetiqueta."; width:".$anchoetiqueta.";'><tr><td>\n"; // genera etiqueta
	global $RAD_BARCODE_STYLE, $RAD_BARCODE_WIDTH, $RAD_BARCODE_HEIGHT;
	$RAD_BARCODE_WIDTH=250; $RAD_BARCODE_HEIGHT=80;
	$etiqueta.="<img src='".RAD_IMGShortCut("","","")."' border=1>";
	if ($tipopapel=="1") $etiqueta.="<br><br>";
	$etiqueta.="</td></tr></table>\n";

	for($ki=0; $ki<$numeti; $ki++) {
		if ($celda>1 && ($celda%$tipopapel==1||$tipopapel==1)) { // empieza nueva pagina cada nueva(>1) primera celda
			$pagina.="\n<table class='pagina' style='page-break-before:always; height:".$altopagina."; width:".$anchopagina.";'>\n";
			$celda=1;
			$numpagina++;
		}
		if ($celda%2==1) { // las celdas impares abren nueva fila
			//if ($celda>1) $pagina.="</tr>\n";
			if ($celda>1 && $celda%$tipopapel!=0) $pagina.="</tr>\n"; // si la celda anterior no fue fin de pagina
			$pagina.="<tr style='height:".$altoetiqueta."; width:".$anchoetiqueta.";'>\n";
		}
		$pagina.="<td style='height:".$altoetiqueta."; width:".$anchoetiqueta.";'>\n".$etiqueta."</td>\n";
		//$pagina.="Celda ".$celda;
		if ($celda>1 && $celda%$tipopapel==0) { // ultima celda de pagina, cierra fila y pagina
			$pagina.="</tr></table>\n";
		}
		$celda++;
	}
	if ($celda%2==0 && $tipopapel!=1) {
		$pagina.="<td style='height:".$altoetiqueta."; width:".$anchoetiqueta.";'>&nbsp;</td>\n";  // si la celda anterior fue impar se pone la celda par
		$celda++;
	}
	if ($celda<$tipopapel) for($ki=$celda; $ki<$tipopapel+1; $ki++) {
		if ($celda%2==1) { // las celdas impares abren nueva fila
			if ($celda>1) $pagina.="</tr>\n";
			$pagina.="<tr style='height:".$altoetiqueta."; width:".$anchoetiqueta.";'>\n";
		}
		$etiqueta="<table style='height:".$altoetiqueta."; width:".$anchoetiqueta.";'><tr><td>&nbsp;</td></tr></table>\n";
		$pagina.="<td style='height:".$altoetiqueta."; width:".$anchoetiqueta.";'>\n".$etiqueta."</td>\n";
		$celda++;
	}
	if ($celda%$tipopapel!=1||$tipopapel==1) $pagina.="</tr></table>\n"; // si no fue antes fin de pagina se cierra fila y pagina

	echo $pagina;
	//echo "<script>\nwindow.print();\n</script>\n";
	echo "</BODY>\n</html>\n";
	die();
}
?>
