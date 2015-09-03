<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

global $SESSION_SID, $RAD_dbi, $idcat, $dbname, $PHP_SELF, $month, $V_dir, $V_mod, $V_idmod;
if ($dbname=="") $dbname=_DEF_dbname;

$TMP_lang=getSessionVar("SESSION_lang");

$TMP_idcat="";
if ($idcat!="") {
	$tmpdefaultfiltercat=" and idcat='".$idcat."'";
	$TMP_result = sql_query("SELECT * FROM categorias WHERE id='$idcat'", $RAD_dbi);
	$TMP_row = sql_fetch_array($TMP_result, $RAD_dbi);
	$TMP_tipo=$TMP_row[literal];
} else {
	$TMP_result = sql_query("SELECT * FROM categorias WHERE categoria='news'", $RAD_dbi);
	while($TMP_row = sql_fetch_array($TMP_result, $RAD_dbi)) {
		$TMP_idcat.=",".$TMP_row["id"];
		if ($tmpdefaultfiltercat!="") $tmpdefaultfiltercat.=" OR ";
		$tmpdefaultfiltercat.="idcat='".$TMP_row["id"]."'";
		$TMP_tipo=$TMP_row[literal];
	}
	$TMP_idcat.=",";
	if ($tmpdefaultfiltercat!="") $tmpdefaultfiltercat=" AND (".$tmpdefaultfiltercat.")";
}

$TMP_time=time();
$cmdsql = "SELECT * FROM contenidos where (activo='1' OR activo IS NULL) AND fechapubli<='$TMP_time' ".$tmpdefaultfiltercat." ";
if ($idcat=="4") $cmdsql="SELECT * FROM contenidos where (activo='1' OR activo IS NULL) AND fechacalendario<='$TMP_time' ".$tmpdefaultfiltercat." and (fechabaja is null or fechabaja='0' or fechabaja='' or fechabaja>'".time()."')";
$TMP_result = sql_query($cmdsql, $RAD_dbi);
$TMP_cont=0;
while($TMP_row=sql_fetch_array($TMP_result, $RAD_dbi)) {
	if ($idcat=="4") {
		$TMP_desde=date("Ym",$TMP_row["fechapubli"]);
		$TMP_hasta=date("Ym",$TMP_row["fechabaja"]);
		if ($TMP_hasta<$TMP_desde && $TMP_hasta>0 && $TMP_desde>0) {
			$TMP_hasta=date("Ym",$TMP_row["fechapubli"]);
			$TMP_desde=date("Ym",$TMP_row["fechabaja"]);
		}
		if (!$TMP_desde>0) continue;
		if (!$TMP_hasta>0) $TMP_hasta=$TMP_desde;
		$TMP_cont=0;
		for($ki=$TMP_desde; $ki<=$TMP_hasta; $ki++) {
			$TMP_fecha=substr($ki,0,4)."-".substr($ki,4,2);
			$A_f[$TMP_fecha]="X";
			$TMP_cont++;
			if ($TMP_cont>120) break;
		}
		continue;
	}
	$TMP_mhasta=date("m",$TMP_row["fechapubli"]);
	$TMP_ahasta=date("Y",$TMP_row["fechapubli"]);
	//if ($TMP_row[fechabaja]>0 && $TMP_row[fechabaja]<time()) continue; // no se muestran contenidos de baja
	$TMP_cont++;
	$TMP_fecha=date("Y-m",$TMP_row["fechapubli"]);
	$A_f[$TMP_fecha]="X";
}
$TMP_opts="\n";
$meses=array("",_DEF_NLSMonth01,_DEF_NLSMonth02,_DEF_NLSMonth03,_DEF_NLSMonth04,_DEF_NLSMonth05,_DEF_NLSMonth06,_DEF_NLSMonth07,_DEF_NLSMonth08,_DEF_NLSMonth09,_DEF_NLSMonth10,_DEF_NLSMonth11,_DEF_NLSMonth12);
if (count($A_f)>0) {
	krsort($A_f);
	foreach($A_f as $TMP_f=>$TMP_x) {
		$A_x=explode("-",$TMP_f);
		$TMP_ano=$A_x[0];
		$TMP_nummes=$A_x[1]*1;
		$TMP_mes=$meses[$TMP_nummes];
		if ($month==$TMP_f) $TMP_select=" SELECTED";
		else $TMP_select="";
		$TMP_opts.="<option".$TMP_select." value='$TMP_f'>$TMP_mes $TMP_ano </option>\n";
	}
}

$A_x=explode(".",basename(__FILE__));
$TMP_fich=$A_x[0];
$TMP_result=sql_query("SELECT * from bloques where fichero='$TMP_fich'", $RAD_dbi);
$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
if ($TMP_row["contenido_".$TMP_lang]!="") $TMP_row["contenido"]=$TMP_row["contenido_".$TMP_lang];
$content.=$TMP_row["contenido"]; // agrega contenido de la definicion del bloque

if ($V_mod=="shownews") $TMP_idmod=$V_idmod;
else $TMP_idmod="";
$TMP_txt=$TMP_tipo." ".$TMP_row[contenido];
$content='
        <div class="relacionados">
          <div class="franja_gris">'.$TMP_txt.'</div>
          <form action="'.$PHP_SELF.'" method="get" name="SELN">
            <input type=hidden name="V_dir" value="MSC">
            <input type=hidden name="V_mod" value="shownews">
            <input type=hidden name="V_idmod" value="'.$TMP_idmod.'">
            <select name="month" onChange=\'javascript:form.submit();\' class="select"><option value=""></option>'.$TMP_opts.'
            </select>
          </form>
        </div>
';

if ($TMP_cont==0) $content;

return $content;
?>
