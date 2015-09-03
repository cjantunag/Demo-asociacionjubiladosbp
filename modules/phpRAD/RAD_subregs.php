<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");
// Muestra registros de modulo hijo, permite consultar/editar/agregar registros hijos

global $RAD_dbi, $i, $fields, $V_dir, $V_mod, $V_idmod, $RAD_subregsnoadd;

$TMP_html="";
$A_v=array(); $A_tot=array(); 

// obtiene nombre de modulo a incluir lineas
$ext=substr($fields[$i]->name,strlen($fields[$i]->name)-3);
if ($ext!="php") {
	$SUB_prog=$fields[$i]->name.".php";
	$SUB_mod=$fields[$i]->name;
} else {
	$SUB_prog=$fields[$i]->name;
	$SUB_mod=substr($fields[$i]->name,0,strlen($fields[$i]->name)-4);
}

if ($V_lap!="x" && $V_lap!="" && $V_lap!=$fields[$i]->overlap) return ""; // Solo se ejecuta en su propia pestanha
if ($func!="new"&&$func!="detail"&&$func!="edit"&&$func!="insert"&&$func!="update") return; // solo se utiliza para estas funciones


// obtiene nombre de campos clave de modulos
$A_k=explode(":",$fields[$i]->vdefault.":");
if ($A_k[0]=="") $A_k[0]=$idname0;
if ($A_k[1]=="") $KSUB_idname0=$A_k[0];
else $KSUB_idname0=$A_k[1];

// obtiene campo uniqid que enlaza registro padre y sus hijos cuando todavia no esta creado el reg.padre
$UNIQIDfield="";
for($ki=0; $ki<$numf; $ki++) {
	if ($fields[$ki]->type == "uniqid") $UNIQIDfield=$fields[$ki]->name;
}
if ($UNIQIDfield=="") error("Modulo $V_mod requiere campo uniqid para poder utilizar RAD_subregs.");;

// conserva copia de variables de modulo padre
$P_fields=$fields;
$P_findex=$findex;
$P_numf=$numf;
$P_tablename=$tablename;
$P_dir=$V_dir;
$P_idmod=$V_idmod;
$P_mod=$V_mod;
$P_idname0=$idname0;
$P_idname1=$idname1;
$P_par0=$par0;
$P_par1=$par1;
$P_func=$func;
$SUB_dir=$V_dir;

// lee modulo a incluir lineas para obtener su tabla y campos a mostrar
$fields=array();
$findex=array();
$fp=fopen("modules/".$V_dir."/".$SUB_prog, "r");
while(!feof($fp)) {
	$TMP_reg=trim(fgets($fp));
	if (substr($TMP_reg,0,1)=="\$") {
		////$TMP_reg=str_replace("\$","\$SUB_",$TMP_reg);
		//$TMP_reg="\$SUB_".substr($TMP_reg,1);
		//echo $TMP_reg."<br>";
		eval($TMP_reg);
	}
}
fclose($fp);
$SUB_numf=$numf;

//--------------------------------------------------------------------------------------------------------
// Si es detail despues de update/insert modifica los registros hijos con el id del padre usando el campo uniqid
//--------------------------------------------------------------------------------------------------------
if ($P_func=="detail"&&$V_prevfunc!=""&&$V_prevfunc!="searchform") {
	$TMP_cmd="SeLECT * FROM ".$P_tablename." where ".$P_idname0."='$par0'";
	//if ($P_idname1!="") $TMP_cmd.=" AND ".$P_idname1."='".$par1."'";
	$TMP_res=sql_query($TMP_cmd, $RAD_dbi);
	$P_row=sql_fetch_array($TMP_res, $RAD_dbi); // registro padre

	$TMP_contpend=0;
	$TMP_resH=sql_query("SELECT * FROM ".$tablename." where ".$UNIQIDfield."='".$P_row[$UNIQIDfield]."'", $RAD_dbi);
	while($TMP_rowH=sql_fetch_array($TMP_resH, $RAD_dbi)) {
		if ($TMP_rowH[$P_idname0]!=$P_row[$P_idname0]) $TMP_contpend++;
	}
	$cmd="updATe ".$tablename." set ".$P_idname0."='".$P_row[$P_idname0]."' where ".$UNIQIDfield."='".$P_row[$UNIQIDfield]."'";
	if ($TMP_contpend>0 && $P_row[$UNIQIDfield]!="") sql_query($cmd, $RAD_dbi);
}

//--------------------------------------------------------------------------------------------------------
// Si es update/insert modifica los registros existentes y agrega los registros nuevos
//--------------------------------------------------------------------------------------------------------
if (($P_func=="update"&&$V0_par0>0)||$P_func=="insert") {
	/*
	$TMP_cmd="SeLECT * FROM ".$P_tablename." where ".$P_idname0."='$V0_par0'";
	//if ($P_idname1!="") $TMP_cmd.=" AND ".$P_idname1."='".$V0_par1."'";
	$TMP_res=sql_query($TMP_cmd, $RAD_dbi);
	$P_row=sql_fetch_array($TMP_res, $RAD_dbi); // registro padre
	*/
	$TMP_numregs=1;
	foreach($_REQUEST as $TMP_k=>$TMP_v) {
		$A_ks=explode("_",$TMP_k);
		if (!count($A_ks)>1) continue;
		if (substr($A_ks[0],0,1)=="V") {
			$TMP_prenumregs=substr($A_ks[0],1);
			if (is_numeric($TMP_prenumregs)) if ($TMP_prenumregs>$TMP_numregs) $TMP_numregs=$TMP_prenumregs;
		}
	}
	$TMP_contregs=0;
//foreach($_REQUEST as $TMP_k=>$TMP_v) echo $TMP_k."=".$TMP_v.".<br>";
//die("PRUEBA");
	for($knr=1; $knr<($TMP_numregs+1); $knr++) { // recorre las lineas de registros
		if ($_REQUEST["V".$knr."_".$idname0]!="") {
			$TMP_cmd="SElECT * FROM ".$tablename." where ".$idname0."='".$_REQUEST["V".$knr."_".$idname0]."'";
			//if ($idname1!="") $TMP_cmd.=" AND ".$idname1."='".$_REQUEST["V".$knr."_".$idname0]."'";
			$TMP_res=sql_query($TMP_cmd, $RAD_dbi);
			$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi); // lee registro original para comprobar campos que han cambiado
		} else {
			$TMP_row=array();
		}
		$ki=1;
		if ($TMP_row[$idname0]=="") $func="insert";
		else $func="update";
		$cmd=""; $numcmdfields=0;
		for($kfi=0; $kfi<$SUB_numf; $kfi++) {
			$TMP_fname=$fields[$kfi]->name;
			$TMP_dtype=$fields[$kfi]->dtype;
			if ($TMP_dtype=="function") continue;
			if ($TMP_dtype=="datetext") {
				if ($_REQUEST["V".$knr."_".$TMP_fname."_date5"]!="") $_REQUEST["V".$knr."_".$TMP_fname]=$_REQUEST["V".$knr."_".$TMP_fname."_date5"];
				else $_REQUEST["V".$knr."_".$TMP_fname]=$_REQUEST["V".$knr."_".$TMP_fname."_date"];
			}
			if (substr($TMP_dtype,0,3)=="num") {
				$_REQUEST["V".$knr."_".$TMP_fname]=RAD_str2num($_REQUEST["V".$knr."_".$TMP_fname]);
			}
			if ($TMP_fname==$P_idname0) {
				if ($P_func=="new") $_REQUEST["V".$knr."_".$TMP_fname]=""; // al crear registro el idpadre es nulo
				else $_REQUEST["V".$knr."_".$TMP_fname]=$_REQUEST["V0_par0"]; // al modificar registro el idpadre es el del registro padre modificado
			}
			//if ($TMP_fname==$P_idname1) $_REQUEST["V".$knr."_".$TMP_fname]=$_REQUEST["V0".$P_idname1];
			if ($func=="update" && $fields[$kfi]->noupdate==true) continue;
			if ($func=="insert" && $fields[$kfi]->insert==true) continue;
			if ($_REQUEST["V".$knr."_".$TMP_fname]=="" && $func!="update" && $TMP_row[$TMP_fname]=="") continue; // no inserta campos vacios que en origen esten vacios
			if ($func=="insert") {
				$cmd.=", ".$TMP_fname."=".converttosql($_REQUEST["V".$knr."_".$TMP_fname]); // no estan todos los formatos de campos (OJO!!! FALTA fechas, ....)
				$numcmdfields++;
			} else if ($_REQUEST["V".$knr."_".$TMP_fname]!=$TMP_row[$TMP_fname]) {
				$cmd.=", ".$TMP_fname."=".converttosql($_REQUEST["V".$knr."_".$TMP_fname]); // no estan todos los formatos de campos (OJO!!! FALTA fechas, ....)
				$numcmdfields++;
			}
		}
		if ($cmd!="") {
			$cmd.=", ".$UNIQIDfield."=".converttosql($_REQUEST["V0_".$UNIQIDfield]);
			if ($func=="update") {
				$cmd="upDAte ".$tablename." set ".substr($cmd,1)." where ".$idname0."='".$_REQUEST["V".$knr."_".$idname0]."'";
				//if ($idname1!="") $cmd.=" AND ".$idname1."='".$_REQUEST["V".$knr."_".$idname0]."'";
			} else {
				if ($numcmdfields<2) $cmd=""; // no se graba un registro solo con la clave idname0
				else $cmd="inSErt into ".$tablename." set ".substr($cmd,1);
			}
		}
		if ($cmd!="") {
			$TMP_contregs++;
			//echo $cmd."<br>";
			sql_query($cmd, $RAD_dbi);
		}
	}
//echo $func.$SUB_MOD.$i.$fields[$i]->name." PRUEBA ".$V_lap."*".$fields[$i]->overlap."*<br>";
//foreach($_REQUEST as $TMP_k=>$TMP_v) echo $TMP_k."=".$TMP_v."<br>";
//die();
}

//--------------------------------------------------------------------------------------------------------
// Si es edit/new/detail muestra registros para editar/agregar/consultar
//--------------------------------------------------------------------------------------------------------
$numeroLineas = 2; // Lineas por defecto para new

if (($P_func=="new"&&$par0>0)||$P_func=="edit"||$P_func=="detail") {
	$TMP_cmd="SeLECT * FROM ".$P_tablename." where ".$P_idname0."='$par0'";
	//if ($P_idname1!="") $TMP_cmd.=" AND ".$P_idname1."='".$par1."'";
	$TMP_res=sql_query($TMP_cmd, $RAD_dbi);
	$P_row=sql_fetch_array($TMP_res, $RAD_dbi);
	$TMP_cmd="SElECT * FROM ".$tablename." where ".$KSUB_idname0."='$par0'";
	$TMP_res=sql_query($TMP_cmd, $RAD_dbi);
	$ki=1;
	while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
		if ($P_func=="new") { // limpia valores de campos clave para insertar en vez de modificar los registros originales al duplicar
			$TMP_row[$idname0]="";
			$TMP_row[$idname1]="";
		}
		$A_par0[$ki]=$TMP_row[$idname0];
		$A_par1[$ki]=$TMP_row[$idname1];
		foreach($TMP_row as $TMP_k=>$TMP_v) {
			$A_field[$TMP_k]="x";
			$A_v[$TMP_k][$ki]=$TMP_v;
			$A_tot[$TMP_k]+=$TMP_v;
		}
		$ki++;
	}
	if ($P_func=="detail") $numeroLineas = $ki-1;
	else $numeroLineas = $ki+1;
}
if ($P_func=="new"||$P_func=="edit"||$P_func=="detail") {
	$V_idmod="";
	$V_mod=$SUB_mod;
	$TMP_tabla="";
	$func="browsedit"; // evita que los common interpreten la funcion edit/new/detail cuando en realidad estamos en un browse
	//$TMP=include("modules/phpRAD/RAD_common.php");
	if (file_exists("modules/$V_dir/common.app.php")) include ("modules/$V_dir/common.app.php");
	if (file_exists("modules/$V_dir/common.".$V_mod.".php")) include ("modules/$V_dir/common.".$V_mod.".php");
	if ($V_mod!=$tablename) if (file_exists("modules/$V_dir/common.".$tablename.".php")) include ("modules/$V_dir/common.".$tablename.".php");
	if (file_exists("modules/$V_dir/".$V_mod.".common.php")) include ("modules/$V_dir/".$V_mod.".common.php");
	if ($V_mod!=$tablename)  if (file_exists("modules/$V_dir/".$tablename.".common.php")) include ("modules/$V_dir/".$tablename.".common.php");
	if (_DEF_appname!="" && _DEF_appname!="_DEF_appname") {
		if (file_exists("modules/$V_dir/common.app."._DEF_appname.".php")) include ("modules/$V_dir/common.app."._DEF_appname.".php");
		if (file_exists("modules/$V_dir/common.".$V_mod."."._DEF_appname.".php")) include ("modules/$V_dir/common.".$V_mod."."._DEF_appname.".php");
		if ($V_mod!=$tablename) if (file_exists("modules/$V_dir/common.".$tablename."."._DEF_appname.".php")) include ("modules/$V_dir/common.".$tablename."."._DEF_appname.".php");
	}
	if (_DEF_appinstance!="" && _DEF_appinstance!="_DEF_appinstance") {
		if (file_exists("modules/$V_dir/common.app."._DEF_appinstance.".php")) include ("modules/$V_dir/common.app."._DEF_appinstance.".php");
	}
	if ($TMP!==true && $TMP!="1") $TMP_tabla.=$TMP;

	$TMP_tabla.="<table id='tablins".$i."' width=100% class=browse><tr>";
        //if ($P_func=="new"||$P_func=="edit") $TMP_tabla.="<td></td>";
        for ($ki=0; $ki<$SUB_numf; $ki++) {
        	if ($fields[$ki]->name==$KSUB_idname0) continue;
        	if ($fields[$ki]->browsable==true) $TMP_tabla.="<th class=browse>".$fields[$ki]->title."</th>";
	}
        $TMP_tabla.="</tr>";
	if ($RAD_subregsnoadd!="" && $P_func!="detail") $numeroLineas--;
	$dbRecord=array();
        for ($ki=1; $ki<$numeroLineas+1; $ki++) {
		foreach($A_field as $TMP_field=>$TMP_x) {
			$dbRecord[$TMP_field]=$db->Record[$TMP_field];
			$db->Record[$TMP_field]=$A_v[$TMP_field][$ki];
		}
		$par0=$A_par0[$ki];
		$par1=$A_par1[$ki];
		$numrow=$ki;
                $TMP_tabla.="<tr>";
		if (substr($TMP_class,0,11)==" class=row1") $TMP_class=" class=row2";
		else $TMP_class=" class=row1";
		$SUB_ki=$ki;
		if (file_exists("modules/$V_dir/common.defaults.php")) {
			$TMP=include ("modules/$V_dir/common.defaults.php");
			if ($TMP!==true && $TMP!="1") $TMP_tabla.=$TMP;
		}
		if (file_exists("modules/$V_dir/".$V_mod.".defaults.php")) {
			$TMP=include ("modules/$V_dir/".$V_mod.".defaults.php");
			if ($TMP!==true && $TMP!="1") $TMP_tabla.=$TMP;
		}
		$ki=$SUB_ki;
		$TMP_ki=$ki;
		if ($ki==$numeroLineas && $P_func!="detail") {
			$TMP_class.=" style='background-color:red; display:none;'"; // esta linea se agrega oculta para duplicar al insertar
			$TMP_ki="X";
		} else {
			$TMP_class.=" style=''";
		}
        	//if ($P_func=="new"||$P_func=="edit") $TMP_tabla.="<td></td>";

		if ($P_func=="detail") {
        		for ($zi=0; $zi<$SUB_numf; $zi++) {
       			 	if ($fields[$zi]->name==$KSUB_idname0) continue;
				$TMP_fname=$fields[$zi]->name;
				$TMP_fdtype=$fields[$zi]->dtype;
				$TMP_extra=$fields[$zi]->extra;
				$TMP_funclink=$fields[$zi]->funclink;
				if ($TMP_funclink!="" && $A_v[$TMP_fname][$ki]!="") {
					$TMP_dir=$V_dir;
					$TMP_link="<img onclick=\"javascript:RAD_OpenW('$PHP_SELF?V_dir=$TMP_dir&V_mod=$TMP_funclink&funclink=1&dbname=$dbname&headeroff=X&footeroff=X&menuoff=&func=detail&par0=".urlencode($A_v[$TMP_fname][$ki])."&subfunc=browse',800,600);\"><img src='images/lupa.gif'>";
				} else $TMP_link="";
       			 	if ($fields[$zi]->browsable==false) {
					$TMP_tabla_hidden.="<input TYPE=hidden value='".$A_v[$TMP_fname][$ki]."' name='V".$TMP_ki."_".$TMP_fname."'>";
					continue;
				}
				//RAD_showfield($fdtype, $fextra="", $value)
				$TMP_URL="javascript:RAD_OpenW(\"".$PHP_SELF."?func=detail&subfunc=browse&par0=".$A_v[$idname0][$ki]."&headeroff=x&footeroff=x&dbname=".$dbname."&V_dir=".$V_dir."&V_mod=".$V_mod.$SESSION_SID."\",800,600);";
				$TMP_input="<span id='IDV".$ki."_".$TMP_fname."'>".$TMP_link."<a href='".$TMP_URL."'>".RAD_showfield($TMP_fdtype, $TMP_extra, $A_v[$TMP_fname][$ki])."</a></span>";
                		$TMP_tabla.="<td".$TMP_class.">".$TMP_tabla_hidden.$TMP_input."</td>";
				$TMP_tabla_hidden="";
			}
		} else {
        		for ($zi=0; $zi<$SUB_numf; $zi++) { // busca la ultima columna a editar para agrega linea auto en foco
       			 	if ($fields[$zi]->name==$KSUB_idname0) continue;
       			 	if ($fields[$zi]->browsable==false) continue;
       			 	if ($fields[$zi]->readonly==true) continue;
       			 	//if ($fields[$zi]->dtype=="standR") continue;
       			 	if ($fields[$zi]->dtype=="standD") continue;
				$TMP_lastfield=$zi;
			}
        		for ($zi=0; $zi<$SUB_numf; $zi++) {
       			 	if ($fields[$zi]->name==$KSUB_idname0) continue;
				$TMP_fname=$fields[$zi]->name;
				$TMP_fdtype=$fields[$zi]->dtype;
				$TMP_extra=$fields[$zi]->extra;
				$TMP_ilength=$fields[$zi]->ilength;
				$TMP_length=$fields[$zi]->length;
				$TMP_onChange=$fields[$zi]->vonchange;	// 'xajax_chgField(this.name,this.value,"")'
				$TMP_canbenull=$fields[$zi]->canbenull;
				$TMP_value=$A_v[$TMP_fname][$TMP_ki];
				if ($TMP_value=="") $TMP_value=$fields[$zi]->vdefault;
				$TMP_formName="";
				$TMP_onFocus=$fields[$zi]->vonfocus;
				$TMP_onBlur=$fields[$zi]->vonblur;
				$TMP_funclink=$fields[$zi]->funclink;
				if ($TMP_funclink!="" && $A_v[$TMP_fname][$ki]!="") {
					$TMP_dir=$V_dir;
					$TMP_link="<img onclick=\"javascript:RAD_OpenW('$PHP_SELF?V_dir=$TMP_dir&V_mod=$TMP_funclink&funclink=1&dbname=$dbname&headeroff=X&footeroff=X&menuoff=&func=detail&par0=".urlencode($A_v[$TMP_fname][$ki])."&subfunc=browse',800,600);\"><img src='images/lupa.gif'>";
				} else $TMP_link="";
				if ($ki+2>$numeroLineas && $zi==$TMP_lastfield) {
					if ($RAD_subregsnoadd=="") $TMP_onFocus="if (parseFloat(this.value)>0||parseFloat(this.value)<0) if (V".$TMP_ki."_Xcont<2){addRowInnerHTML('tablins".$i."');V".$TMP_ki."_Xcont++;}".$fields[$zi]->vonfocus;
					else $TMP_onFocus="V".$TMP_ki."_Xcont++;".$fields[$zi]->vonfocus;
				}

       			 	if ($fields[$zi]->browsable==false) {
					if (substr($fields[$zi]->dtype,0,3)=="num") $TMP_val=RAD_numero($A_v[$TMP_fname][$ki],$fields[$zi]->extra);
					else $TMP_val=$A_v[$TMP_fname][$ki];
					$TMP_tabla_hidden.="<INPUT type=hidden value='".$TMP_val."' name='V".$TMP_ki."_".$TMP_fname."'>";
					continue;
				}

				if ($fields[$findex[$TMP_fname]]->funclink!="") $fields[$findex[$TMP_fname]]->funclink.="&V_lap=".$V_lap;
				$findex["V".$TMP_ki."_".$TMP_fname]=$numf;
				$fields[$numf]=$fields[$findex[$TMP_fname]];
				$numf++;

       			 	if ($fields[$zi]->readonly==true) {
					$TMP_URL="javascript:RAD_OpenW(\"".$PHP_SELF."?func=detail&subfunc=browse&par0=".$A_v[$idname0][$ki]."&headeroff=x&footeroff=x&dbname=".$dbname."&V_dir=".$V_dir."&V_mod=".$V_mod.$SESSION_SID."\",800,600);";
					$TMP_input="<span id='IDV".$ki."_".$TMP_fname."'>".$TMP_link."<a href='".$TMP_URL."'>".RAD_showfield($TMP_fdtype, $TMP_extra, $A_v[$TMP_fname][$ki])."</a></span>";
					$TMP_tabla_hidden.="<INPUT TYPE=hidden value='".$A_v[$TMP_fname][$ki]."' name='V".$TMP_ki."_".$TMP_fname."'>";
				} else {
					$TMP_input="<span id='IDV".$TMP_ki."_".$TMP_fname."'>".RAD_editfield('V'.$TMP_ki.'_'.$TMP_fname, $TMP_fdtype, $TMP_length, $TMP_ilength, $TMP_extra, $TMP_onChange, $TMP_canbenull, $TMP_value, 'V'.$TMP_ki.'_'.$TMP_fname, $TMP_formName, $TMP_onFocus, $TMP_onBlur)."</span>";
				}
                		$TMP_tabla.="<td".$TMP_class.">".$TMP_tabla_hidden.$TMP_input."</td>";
				$TMP_tabla_hidden="";
			}
		}
		$TMP_tabla.="</tr>";
		foreach($A_field as $TMP_field=>$TMP_x) {
			$db->Record[$TMP_field]=$dbRecord[$TMP_field];
		}
        }
	$TMP_tabla.="<tr>";
        //if ($P_func=="new"||$P_func=="edit") $TMP_tabla.="<td><a href='javascript:addRowInnerHTML(\"tablins".$i."\");'><img src='images/nolines_plus.gif' title='"._DEF_NLSNewString."'></a></td>";
	$TMP_numcol=0;
        for ($zi=0; $zi<$SUB_numf; $zi++) { // linea de Totales numericos
       	 	if ($fields[$zi]->name==$KSUB_idname0) continue;
       		if ($fields[$zi]->browsable==false) continue;
		$TMP_fname=$fields[$zi]->name;
		$TMP_tabla.="<th class=browse>";
        	if ($RAD_subregsnoadd=="" && $TMP_numcol==0 && ($P_func=="new"||$P_func=="edit")) $TMP_tabla.="<div style='float:left;'><a href='javascript:addRowInnerHTML(\"tablins".$i."\");'><img src='images/nolines_plus.gif' title='"._DEF_NLSNewString."'></a></div>";
		$TMP_tabla.="<span style='border:0px solid red;' id='HIDVT_".$TMP_fname."'>";
		$TMP_A=explode(",",$fields[$findex[$TMP_fname]]->extra);
		if (count($TMP_A)>1) $TMP_decimales=$TMP_A[1];
		else $TMP_decimales="";
		if (substr($fields[$findex[$TMP_fname]]->dtype,0,3)=="num") $TMP_tabla.=RAD_showfield($fields[$findex[$TMP_fname]]->dtype, $fields[$findex[$TMP_fname]]->extra, $A_tot[$TMP_fname]);
		$TMP_tabla.="</span></th>";
		$TMP_numcol++;
	}
	$TMP_tabla.="</tr>";
        $TMP_tabla.="</table>";
	$TMP_tabla.="
<script>
";
	for($kkk=1; $kkk<199; $kkk++) $TMP_tabla.="var V".$kkk."_Xcont=0; "; // se permiten hasta 199 lineas de registros
	$TMP_tabla.="
var numregs=".($numeroLineas-1).";
function addRowInnerHTML(tblId) {
  numregs++;
  var tblBody = document.getElementById(tblId).tBodies[0];
  var lastRow = tblBody.rows.length - 2;
  var cellsRow= tblBody.rows[lastRow].cells.length;
//alert('PRUEBA LastRow='+lastRow+'.INNERHTML='+tblBody.rows[lastRow].innerHTML);
  var newRow = tblBody.insertRow(lastRow);
//alert('PRUEBA LastRow='+lastRow+'.Cells='+cellsRow);
  for(i=0; i<cellsRow; i++) {
	cellHTML = tblBody.rows[lastRow+1].cells[i].innerHTML;
	while (cellHTML.indexOf('VX_')!= -1) cellHTML=cellHTML.replace('VX_','V'+lastRow+'_');
	newRow.insertCell(i).innerHTML = cellHTML;
	//if (i==0) alert('PRUEBA Cell i='+i+'.HTML='+cellHTML);
	if (lastRow%2) tblBody.rows[lastRow].cells[i].className='row1';
	else tblBody.rows[lastRow].cells[i].className='row2';
  }
  //newRow.innerHTML = '<tr>'+tblBody.rows[lastRow].innerHTML+'</tr>';
  //newRow.innerHTML = '<tr><td><input type=input/></td><td>****</td></tr>';
}
</script>
";

	$TMP_colspan=2;
	if (($P_func=="new"||$P_func=="edit") && $V_colsedit>1) $TMP_colspan=$V_colsedit*2;
	if ($P_func=="detail" && $V_colsdetail>1) $TMP_colspan=$V_colsdetail*2;
	if (is_admin() || is_modulepermitted("", "phpGenRAD", "index")) $TMP_linkadm="<a target=_blank href='$PHP_SELF?&V_dir=phpGenRAD&V_mod=indexRAD&V_submod=genform&modulesdir=$SUB_dir&project_file=".$SUB_mod.".prj.php$SESSION_SID'>&nbsp;&nbsp;</a>";
	else if (is_modulepermitted("", "phpGenRAD", "minigenform")) $TMP_linkadm="<a target=_blank href='$PHP_SELF?&V_dir=phpGenRAD&V_mod=minigenform&modulesdir=$SUB_dir&project_file=".$SUB_mod.".prj.php$SESSION_SID'>&nbsp;&nbsp;</a>";
	else $TMP_linkadm="";
	$TMP_html= "\n<tr class=subbrowse><td colspan=$TMP_colspan class=detailtit><div align=center><b>".$P_fields[$i]->title.$TMP_linkadm."</b></div>";
	$TMP_html.= "\n".$TMP_tabla."</b></div></td></tr>";
}

// devuelve valores de variables de modulo padre
$fields=$P_fields;
$findex=$P_findex;
$numf=$P_numf;
$tablename=$P_tablename;
$V_dir=$P_dir;
$V_idmod=$P_idmod;
$V_mod=$P_mod;
$idname0=$P_idname0;
$idname1=$P_idname1;
$par0=$P_par0;
$par1=$P_par1;
$func=$P_func;

return $TMP_html;
?>
