<?php
if (eregi(basename(__FILE__), $PHP_SELF))  die ("Security Error ...");

$TMP="";
if ($RAD_showimpresos==true && RAD_existTable("impresos")) { 
	$TMP_where=""; $TMP_campo=array();
	$campos=sql_list_fields("impresos", $RAD_dbi);
	while($columnas=sql_fetch_array($campos, $RAD,dbi)) $TMP_campo[]=$columnas[0];
	if (in_array('modulo', $TMP_campo)) {
		$TMP_cmd="SELECT * FROM impresos WHERE (modulo='$V_mod' OR ((modulo='' or modulo IS NULL) AND tabla='".$V_tablename."'))";
	} else {
		$TMP_cmd="SELECT * FROM impresos WHERE tabla='".$V_tablename."'";
	}
	if (in_array('usuario', $TMP_campo)) $TMP_cmd.=" AND (usuario='".$TMP_user."' or usuario is null or usuario='')";
	//$TMP_cmd.=" AND (url IS NOT NULL AND url!='')";
	$TMP_i="";
	$TMP_iresult=sql_query($TMP_cmd, $RAD_dbi);
	//$TMP_iresult=sql_query("SELECT * FROM impresos ORDER BY impreso ASC",$RAD_dbi);
	$TMP_iselect=" &nbsp; <input type=hidden name=func value='$func'><input type=hidden name=dbname value='$dbname'><input type=hidden name=par0 value='$par0'>";
	$TMP_iURLadm=$PHP_SELF."?func=browse&dbname=$dbname&V_dir=admin&V_mod=impresos";
	if (is_admin()) $TMP_iselect.="<a href='$TMP_iURLadm' target=_blank><img src='images/print.png' alt='' title=''></a>\n";
	else $TMP_iselect.="<img src='images/print.png' alt='' title=''>\n";
	$TMP_iselect.="<input type=hidden name=V_dir value='$V_dir'><input type=hidden name=V_mod value='$V_mod'><input type=hidden name=V_idmod value='$V_idmod'><input type=hidden name=V_typePrint value='impreso'><input type=hidden name=V_send value=''><input type=hidden name=V_save value='x'> &nbsp; <select name=V_idimpreso onchange='javascript:document.IMPRESOS.submit();document.IMPRESOS.V_idimpreso.selectedIndex=0;'>";
	$TMP_iselect.="<option value='' SELECTED> imprimir documento de ....</option>\n";
	$TMP_icont=0;
	while($TMP_irow=sql_fetch_array($TMP_iresult, $RAD_dbi)) {
		$TMP_icont++;
		$TMP_id=$TMP_irow[idimpreso];
		if (trim($TMP_irow[condiciones])!="") {
			$TMP_muestraimpreso=true;
			$cadena="\$TMP_muestraimpreso=".$TMP_irow[condiciones];
			//$cadena="\$TMP_muestraimpreso=".ereg_replace("\$","\\\$",$TMP_irow[condiciones]);
			eval($cadena);
			if ($TMP_muestraimpreso==false) continue;
		}
		$TMP_iURL=$PHP_SELF."?func=$func&dbname=$dbname&par0=$par0&V_dir=$V_dir&V_mod=$V_mod&V_idmod=$V_idmod&V_typePrint=impreso&V_idimpreso=$TMP_id&V_send=&V_save=x";
		$TMP_iURLadm=$PHP_SELF."?func=edit&dbname=$dbname&par0=$TMP_id&V_dir=admin&V_mod=impresos";
		if ($TMP_i!="") $TMP_i.=" &nbsp; |";
		if (is_admin()) $TMP_i.=" &nbsp; <a href='$TMP_iURLadm' target=_blank><img src='images/print.png' alt='' title=''></a> <a href='$TMP_iURL'>".$TMP_irow[impreso]."</a>\n";
		else $TMP_i.=" &nbsp; <a href='$TMP_iURL'><img src='images/print.png' alt='' title=''> ".$TMP_irow[impreso]."</a>\n";
		$TMP_iselect.="<option value='$TMP_id'>".$TMP_irow[impreso]."</option>\n";
	}
	$TMP_iselect.="</select>\n";
	$TMP_class=$RAD_classrow;
	if ($func=="new" || $func=="detail" || $func=="edit") $TMP_class="class=detailtit colspan=2";
	if ($TMP_icont>1) $TMP="<form action='".$PHP_SELF."' method=GET target=_blank name=IMPRESOS><td $TMP_class nowrap>&nbsp;".$TMP_iselect."&nbsp;</td></form>";
	else $TMP="<td $TMP_class nowrap>&nbsp;".$TMP_i."&nbsp;</td>";
	if ($func=="new"||$func=="detail"||$func=="edit") {
		if ($TMP_icont>0) $TMP="<tr>".$TMP."</tr>";
		else $TMP="";
	}
}
return $TMP;
?>
