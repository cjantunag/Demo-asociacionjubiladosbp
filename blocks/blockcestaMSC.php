<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

global $SESSION_SID, $PHP_SELF, $V_dir, $RAD_dbi, $PHPSESSID, $headeroff, $menuoff, $footeroff;

        $TMP_result = sql_query("SELECT * FROM GIE_ventas WHERE ventatipo='VIN' AND (cobrado='0' OR cobrado='' OR cobrado IS NULL) AND (procesado='1' OR procesado='0' OR procesado IS NULL) AND sesioncesta='$PHPSESSID' AND (refcliente='".base64_decode(getSessionVar("SESSION_user"))."' OR refcliente IS NULL OR refcliente='') ORDER BY fechaventa DESC", $RAD_dbi);
        $TMP_row = sql_fetch_array($TMP_result, $RAD_dbi);
        $TMP_idventa=$TMP_row[idventa];
        $TMP_IVA=$TMP_row[iva];
	if ($TMP_idventa>0 && $TMP_row[refcliente]=="" && is_user()) {
		$TMP_res2=sql_query("SELECT * FROM usuarios WHERE usuario='".base64_decode(getSessionVar("SESSION_user"))."'", $RAD_dbi);
        	$TMP_row2 = sql_fetch_array($TMP_res2, $RAD_dbi);
		$TMP_vals=", nombrecliente=".converttosql($TMP_row2[nombre]).", direccion=".converttosql($TMP_row2[domicilio]).", email=".converttosql($TMP_row2[email]).", telefono=".converttosql($TMP_row2[telefono]).", observaciones2='', codprovincia=".converttosql($TMP_row2[provincia]).", localidad=".converttosql($TMP_row2[localidad])."";
		sql_query("UPDATE GIE_ventas SET refcliente='".base64_decode(getSessionVar("SESSION_user"))."'".$TMP_vals." WHERE idventa='$TMP_idventa'", $RAD_dbi);
	}

	if (!$TMP_idventa>0) return "";

$A_x=explode(".",basename(__FILE__));
$TMP_fich=$A_x[0];
$TMP_result=sql_query("SELECT * from bloques where fichero='$TMP_fich'", $RAD_dbi);
$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
if ($TMP_row["contenido_".$TMP_lang]!="") $TMP_row["contenido"]=$TMP_row["contenido_".$TMP_lang];

	$TMP_content="<table class=browse><tr><th class=browse colspan=2>".$TMP_row[contenido]."</td></tr>";
	//$TMP_content.="<tr><td bgcolor='#FFFFFF' style='padding:0px; margin:0px; border:0px; width:200px;' colspan=2><a href='"._DEF_INDEX."?".$SESSION_SID."&amp;V_dir=MSC&amp;V_mod=cesta'>Cesta</a></td></tr>";
	$TMP_result2 = sql_query("SELECT * FROM GIE_ventasdetalle WHERE idventa='".$TMP_idventa."'", $RAD_dbi);
	$TMP_cont=0;
	$TMP_total=0;
	while($TMP_row2 = sql_fetch_array($TMP_result2, $RAD_dbi)) {
		$TMP_articulo=RAD_lookup("GIE_articulos","articulo","idarticulo",$TMP_row2["idarticulo"]);
		if (strlen($TMP_articulo)>20) $TMP_articulobreve=substr($TMP_articulo,0,17)."...";
		else $TMP_articulobreve=$TMP_articulo;
		if ($TMP_class == "class=row1") $TMP_class = "class=row2";
                else $TMP_class = "class=row1";
		$TMP_content.='<tr><td '.$TMP_class.'><b>&nbsp;'.$TMP_row2[unidades].'&nbsp;</b></td><td '.$TMP_class.'>'.$TMP_articulo.'</td>';
		//$TMP_content.="<td align=right $TMP_class>".$TMP_row2[precio]."</td><td align=right $TMP_class>".$TMP_row2[total]."</td>";
		$TMP_content.="</tr>";
		//$TMP_content.="<tr><td $TMP_class> <a href='"._DEF_INDEX."?".$SESSION_SID."V_dir=MSC&amp;V_mod=cesta'>- ".$TMP_articulobreve."</a></td></tr>";
		$TMP_cont++;
		$TMP_total+=$TMP_row2[total];
		if ($TMP_IVA!=0 && $TMP_row2[total]!=0) $TMP_totalIVA=floor($TMP_row2[total]*$TMP_IVA+0.5)/100;
		else $TMP_totalIVA=0;
		$TMP_sumaIVA+=$TMP_totalIVA;
		$TMP_tconIVA=$TMP_tconIVA+$TMP_totalIVA+$TMP_row2[total];
	}
	if ($TMP_cont>0) $TMP_content.="<tr><th class=browse colspan=2 style='text-align:right;'>Total : ".RAD_numero($TMP_total,2)." &nbsp;</th></tr>";
	//$TMP_content.="<tr><th class=browse colspan=3 align=right>IVA ($TMP_IVA%): </th><th class=browse>".RAD_numero($TMP_sumaIVA,2)." </th></tr><tr><th colspan=3 class=browse align=right>Total con IVA: </th><th class=browse>".RAD_numero($TMP_tconIVA,2)." </th></tr>";
	$TMP_content.='</table>';
	if ($TMP_cont==0) $TMP_content="";

	$content=$TMP_content;
	return "";

	
?>
