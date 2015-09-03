<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");
// Muestra registros de modulo hijo, permite consultar/editar/agregar registros hijos

global $RAD_dbi, $i, $fields, $V_dir, $V_mod, $V_idmod;

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

if ($V_lap!="" && $V_lap!=$fields[$i]->overlap) return ""; // Solo se ejecuta en su propia pestanha
if ($func!="edit"&&$func!="update") return; // solo se utiliza para agregar un registro

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
if ($UNIQIDfield=="") error("Modulo $V_mod requiere campo uniqid para poder utilizar RAD_subreg.");;

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
// Si es update crea el registro hijo con el id del padre usando el campo uniqid
//--------------------------------------------------------------------------------------------------------
if ($P_func=="update") {
	$TMP_cmd="SeLECT * FROM ".$P_tablename." where ".$P_idname0."='$par0'";
	//if ($P_idname1!="") $TMP_cmd.=" AND ".$P_idname1."='".$par1."'";
	$TMP_res=sql_query($TMP_cmd, $RAD_dbi);
	$P_row=sql_fetch_array($TMP_res, $RAD_dbi); // registro padre
	$cmd="upDATe ".$tablename." set ".$P_idname0."='".$P_row[$P_idname0]."' where ".$UNIQIDfield."='".$P_row[$UNIQIDfield]."'";
	sql_query($cmd, $RAD_dbi);

	// crea un registro a partir de campos VA_*
	$cmd=""; $numcmdfields=0;
	for($kfi=0; $kfi<$SUB_numf; $kfi++) {
		$TMP_fname=$fields[$kfi]->name;
		$TMP_dtype=$fields[$kfi]->dtype;
		if ($TMP_dtype=="datetext") {
			if ($_REQUEST["VA_".$TMP_fname."_date5"]!="") $_REQUEST["VA_".$TMP_fname]=$_REQUEST["VA_".$TMP_fname."_date5"];
			else $_REQUEST["VA_".$TMP_fname]=$_REQUEST["VA_".$TMP_fname."_date"];
		}
		if (substr($TMP_dtype,0,3)=="num") {
			$_REQUEST["VA_".$TMP_fname]=RAD_str2num($_REQUEST["VA_".$TMP_fname]);
		}
		if ($TMP_fname==$P_idname0) $_REQUEST["VA_".$TMP_fname]=$_REQUEST["V0_par0"];
		//if ($TMP_fname==$P_idname1) $_REQUEST["VA_".$TMP_fname]=$_REQUEST["V0".$P_idname1];
		if ($func=="update" && $fields[$kfi]->noupdate==true) continue;
		if ($func=="insert" && $fields[$kfi]->insert==true) continue;
		if ($_REQUEST["VA_".$TMP_fname]!=$TMP_row[$TMP_fname]) {
			$cmd.=", ".$TMP_fname."=".converttosql($_REQUEST["VA_".$TMP_fname]); // no estan todos los formatos de campos (OJO!!!! FALTA fechas, quitar decimales a NUM....)
			$numcmdfields++;
		}
	}
	if ($cmd!="") {
		$cmd.=", ".$UNIQIDfield."=".converttosql($_REQUEST["V0_".$UNIQIDfield]);
		if ($numcmdfields<2) $cmd=""; // no se graba un registro solo con la clave idname0
		else $cmd="inSeRt into ".$tablename." set ".substr($cmd,1);
	}
	if ($cmd!="") {
		//echo $cmd."<br>";
		sql_query($cmd, $RAD_dbi);
	}
//echo $func.$SUB_MOD.$i.$fields[$i]->name." PRUEBA ".$V_lap."*".$fields[$i]->overlap."*<br>";
//foreach($_REQUEST as $TMP_k=>$TMP_v) echo $TMP_k."=".$TMP_v."<br>";
//die();
}

//--------------------------------------------------------------------------------------------------------
// Si es edit muestra campos de registro para agregar
//--------------------------------------------------------------------------------------------------------
if ($P_func=="edit") { // en realidad va a crear hijo nuevo en vez de editar registro padre
	$TMP_cmd="SeLECT * FROM ".$P_tablename." where ".$P_idname0."='$par0'";
	//if ($P_idname1!="") $TMP_cmd.=" AND ".$P_idname1."='".$par1."'";
	$TMP_res=sql_query($TMP_cmd, $RAD_dbi);
	$P_row=sql_fetch_array($TMP_res, $RAD_dbi);

	$TMP_inifieldname=""; $TMP_inifieldtype="";
	$V_idmod="";
	$V_mod=$SUB_mod;
	$TMP_tabla="";
	$func="new"; // evita que los common interpreten la funcion edit cuando en realidad estamos en un new
	//$TMP=include("modules/phpRAD/RAD_common.php");
	if (file_exists("modules/$V_dir/common.app.php")) include_once ("modules/$V_dir/common.app.php");
	if (file_exists("modules/$V_dir/common.".$V_mod.".php")) include_once ("modules/$V_dir/common.".$V_mod.".php");
	if ($V_mod!=$tablename) if (file_exists("modules/$V_dir/common.".$tablename.".php")) include_once ("modules/$V_dir/common.".$tablename.".php");
	if (file_exists("modules/$V_dir/".$V_mod.".common.php")) include_once ("modules/$V_dir/".$V_mod.".common.php");
	if ($V_mod!=$tablename)  if (file_exists("modules/$V_dir/".$tablename.".common.php")) include_once ("modules/$V_dir/".$tablename.".common.php");
	if (_DEF_appname!="" && _DEF_appname!="_DEF_appname") {
		if (file_exists("modules/$V_dir/common.app."._DEF_appname.".php")) include_once ("modules/$V_dir/common.app."._DEF_appname.".php");
		if (file_exists("modules/$V_dir/common.".$V_mod."."._DEF_appname.".php")) include_once ("modules/$V_dir/common.".$V_mod."."._DEF_appname.".php");
		if ($V_mod!=$tablename) if (file_exists("modules/$V_dir/common.".$tablename."."._DEF_appname.".php")) include_once ("modules/$V_dir/common.".$tablename."."._DEF_appname.".php");
	}
	if (_DEF_appinstance!="" && _DEF_appinstance!="_DEF_appinstance") {
		if (file_exists("modules/$V_dir/common.app."._DEF_appinstance.".php")) include_once ("modules/$V_dir/common.app."._DEF_appinstance.".php");
	}
	if ($TMP!==true && $TMP!="1") $TMP_tabla.=$TMP;

	if (substr($TMP_class,0,11)==" class=row1") $TMP_class=" class=row2";
	else $TMP_class=" class=row1";
	if (file_exists("modules/$V_dir/common.defaults.php")) {
		$TMP=include ("modules/$V_dir/common.defaults.php");
		if ($TMP!==true && $TMP!="1") $TMP_tabla.=$TMP;
	}
	if (file_exists("modules/$V_dir/".$V_mod.".defaults.php")) {
		$TMP=include ("modules/$V_dir/".$V_mod.".defaults.php");
		if ($TMP!==true && $TMP!="1") $TMP_tabla.=$TMP;
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
		$TMP_value=$fields[$zi]->vdefault;
		$TMP_help=$fields[$zi]->help;
		$TMP_formName="";
		$TMP_onFocus=$fields[$zi]->vonfocus;
       		if ($fields[$zi]->nonew==true) {
			$TMP_tabla_hidden.="<input type=hidden value='".$TMP_value."' name='VA_".$TMP_fname."'>";
			continue;
		}
		if ($fields[$findex[$TMP_fname]]->funclink!="") $fields[$findex[$TMP_fname]]->funclink.="&V_lap=".$V_lap;
		$findex["VA_".$TMP_fname]=$numf;
		$fields[$numf]=$fields[$findex[$TMP_fname]];
		$numf++;
		$TMP_input="<span id='IDVA_".$TMP_fname."'>";
       		if ($fields[$zi]->readonly==true) {
			$TMP_input.="<input type=hidden value='".$TMP_value."' name='VA_".$TMP_fname."'>";
			$TMP_input.=RAD_showfield($TMP_fdtype, $TMP_extra, $TMP_value);
			$TMP_input.="</span>";
			$TMP_input.="<span id='HIDVA_".$TMP_fname."'>".$TMP_help."</span>";
		} else {
			if ($RAD_html5=="") $RAD_html5=_DEF_html5;

			if ($TMP_inifieldname=="") {
				$TMP_inifieldname="VA_".$TMP_fname;
				$TMP_inifieldtype=$TMP_fdtype;
			}
			if ($TMP_fdtype=="date") {
				$TMP_inifieldname.="_date";
			} elseif ($TMP_fdtype=="datetext") {
				$TMP_inifieldname.="_date";
				if ($RAD_html5=="1") $TMP_inifieldname.="5";
			}
			$TMP_input.="".RAD_editfield('VA_'.$TMP_fname, $TMP_fdtype, $TMP_length, $TMP_ilength, $TMP_extra, $TMP_onChange, $TMP_canbenull, $TMP_value, 'VA_'.$TMP_fname, $TMP_formName, $TMP_onFocus)."";
			$TMP_input.="</span>";
			$TMP_input.="<span id='HIDVA_".$TMP_fname."'>".$TMP_help."</span>";
		}
               	$TMP_tabla.="<tr><td class=detailtit>";
		if (!$fields[$zi]->canbenull) $TMP_tabla.="<span style='color:red;font-weight:bold;'>*</span> ";
		$TMP_tabla.=$fields[$zi]->title.": </td><td class=detail>".$TMP_tabla_hidden.$TMP_input."</td></tr>";
		$TMP_tabla_hidden="";
        }
	$TMP_html.= "\n".$TMP_tabla."\n";
	if (substr($TMP_inifieldtype,0,4)=="date") {
		if (eregi("text",$TMP_inifieldtype)) {
			if ($RAD_html5=="1") $TMP_inifieldname=$TMP_inifieldname."_date5";
			else $TMP_inifieldname=$TMP_inifieldname."_date";
		} else $TMP_inifieldname=$TMP_inifieldname."_day";
	} else if (substr($TMP_inifieldtype,0,4)=="time") {
		if (ereg("text",$TMP_inifieldtype)) $TMP_inifieldname=$TMP_inifieldname."_time";
		else $TMP_inifieldname=$TMP_inifieldname."_hour";
	} else if (substr($TMP_inifieldtype,0,6)=="bpopup") {
		$TMP_inifieldname=$TMP_inifieldname."_literal";
	} else $TMP_inifieldname=$TMP_inifieldname;
	$TMP_html.="<script>\nRAD_setselFieldName('".$TMP_inifieldname."');\ndocument.F.".$TMP_inifieldname.".focus();\n</script>\n";
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
