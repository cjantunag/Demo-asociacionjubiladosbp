<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

$TMP="";

$fields[$findex[emailorigen]]->vdefault=_DEF_ADMINMAIL;

if ($func=="detail") {
	if ($V_lap!="Comunicados") {
		$TMP_URL=$PHP_SELF."?func=none&par0=$par0&dbname=$dbname&V_dir=$V_dir&V_mod=$V_mod&V_idmod=$V_idmod".$SESSION_SID."&V_lap=$V_lap&blocksoff=x&headeroff=x&footeroff=x&menuoff=x&subfunc=browse&op=duplicalista";
		$TMP.="<TR><TD class=detail><b>".$db->Record[grupo]."</b></TD><TD class=detail><div align=right>";
		$TMP.=" &nbsp; <a accesskey='Y' title='ALT+Y' href='javascript:RAD_OpenW(\"$TMP_URL\",600,400);'><img src='images/copy.gif' border=0 valign=-5> DUPLICA LISTA CON SUS DESTINATARIOS</A></div>";
		$TMP.="</TR>";
	} else {
		$TMP_URL=$PHP_SELF."?func=none&par0=$par0&dbname=$dbname&V_dir=$V_dir&V_mod=$V_mod&V_idmod=$V_idmod".$SESSION_SID."&V_lap=$V_lap&blocksoff=x&headeroff=x&footeroff=x&menuoff=x&subfunc=browse&op=enviaemailprueba";
		$TMP.="<TR><TD class=detail><b>".$db->Record[grupo]."</b></TD><TD class=detail><div align=right>";
		$TMP.=" &nbsp; <a accesskey='Y' title='ALT+Y' href='javascript:RAD_OpenW(\"$TMP_URL\",800,600);'><img src='images/email.gif' border=0 valign=-5> ENVIA EMAIL DE PRUEBA</A></div>";
		$TMP.="</TR>";
	}
}

if ($func=="detail" && $V_lap=="Destinatarios") {
	$TMP_URL1=$PHP_SELF."?func=none&par0=$par0&dbname=$dbname&V_dir=$V_dir&V_mod=$V_mod&V_idmod=$V_idmod".$SESSION_SID."&V_lap=$V_lap&blocksoff=x&headeroff=x&footeroff=x&menuoff=x&subfunc=browse&op=email";
	$TMP_URL2=$PHP_SELF."?func=none&par0=$par0&dbname=$dbname&V_dir=$V_dir&V_mod=$V_mod&V_idmod=$V_idmod".$SESSION_SID."&V_lap=$V_lap&blocksoff=x&headeroff=x&footeroff=x&menuoff=x&subfunc=browse&op=sel";
	$TMP_URL3=$PHP_SELF."?func=none&par0=$par0&dbname=$dbname&V_dir=$V_dir&V_mod=$V_mod&V_idmod=$V_idmod".$SESSION_SID."&V_lap=$V_lap&blocksoff=x&headeroff=x&footeroff=x&menuoff=x&subfunc=browse&op=del";
	$TMP.="<tr><td class=detail colspan=2><br><div align=center><form><input accesskey='X' title='ALT+X' type=button value=' AGREGA DIRECCIONES (a partir de lista propia) ' onClick='javascript:RAD_OpenW(\"$TMP_URL1\",900,700);'>";
	$TMP.=" &nbsp; <input accesskey='Y' title='ALT+Y' type=button value=' AGREGA USUARIOS REGISTRADOS A LA LISTA MEDIANTE FILTRO ' onClick='javascript:RAD_OpenW(\"$TMP_URL2\",900,700);'>";
	$TMP.=" &nbsp; <input accesskey='Z' title='ALT+Z' type=button value=' BORRA DESTINATARIOS DE LA LISTA ' onClick='javascript:RAD_OpenW(\"$TMP_URL3\",900,700);'></form></div><br></td></tr>";
}

if ($func=="detail" && $V_lap=="Comunicados") {
	$TMP_URL=$PHP_SELF."?func=none&par0=$par0&dbname=$dbname&V_dir=$V_dir&V_mod=$V_mod&V_idmod=$V_idmod".$SESSION_SID."&V_lap=$V_lap&blocksoff=x&headeroff=x&footeroff=x&menuoff=x&subfunc=browse&op=envia";
	$TMP_URL2=$PHP_SELF."?func=none&par0=$par0&dbname=$dbname&V_dir=$V_dir&V_mod=$V_mod&V_idmod=$V_idmod".$SESSION_SID."&V_lap=$V_lap&blocksoff=x&headeroff=x&footeroff=x&menuoff=x&subfunc=browse&op=gencarta";
	$TMP_URL3=$PHP_SELF."?func=none&par0=$par0&dbname=$dbname&V_dir=$V_dir&V_mod=$V_mod&V_idmod=$V_idmod".$SESSION_SID."&V_lap=$V_lap&blocksoff=x&headeroff=x&footeroff=x&menuoff=x&subfunc=browse&op=genfich";
	$TMP.="<tr><td class=detail colspan=2><br><div align=center><form><input accesskey='W' title='ALT+W' type=button value=' ENVIA EMAIL A DESTINATARIOS DE LA LISTA ' onClick='javascript:RAD_OpenW(\"$TMP_URL\",700,700);'>";
	//$TMP.=" &nbsp; <input accesskey='W' title='ALT+W' type=button value=' GENERA CARTAS IMPRESAS ' onClick='javascript:RAD_OpenW(\"$TMP_URL2\",700,700);'><br><br>";
	//$TMP.=" &nbsp; <input accesskey='W' title='ALT+W' type=button value=' GENERA FICHERO DE DIRECCIONES PARA CORREO POSTAL DE TODA LA LISTA ' onClick='javascript:RAD_OpenW(\"$TMP_URL3\",700,700);'>";
	$TMP.=" </form></div><br></td></tr>";
}

if ($subop=="ejemplo") {
	echo "<TABLE width=100% class=borde><TR><TH class=title>AGREGA DIRECCIONES EMAIL</TH></TR></TABLE>\n";
	echo "\n<table class=detail>\n";
	echo "<tr><td class=detailtit>Ejemplo 1:</td><td class=detail>pepe@micorreo.com, juan@dominio1.com, pedro@dominio2.org, ...<br><br></td></tr>";
	echo "<tr><td class=detailtit>Ejemplo 2:</td><td class=detail>pepe@micorreo.com<br>\njuan@dominio1.com<br>\npedro@dominio2.org<br>\n ...<br><br></td></tr>";
	echo "<tr><td class=detailtit>Ejemplo 3:<br><br>(Email es la ultima columna del CSV)</td><td class=detail>Jose Apellido1 Apellido2;pepe@micorreo.com;<br>\nEntidad 2;juan@dominio1.com;<br>\nApellido1 Apellido2, Pedro;pedro@dominio2.org;<br>\n ...<br><br></td></tr>";
	echo "<tr><td colspan=2><br><br><a href='javascript:history.back();'>Volver Atr&aacute;s</a><br><br></td></tr>";
	echo "\n</table>\n";
} else if ($op=="email" || $op=="consemail" || $op=="agregaemail") {
	echo "<form name='F' action='".$PHP_SELF."' method='POST'>";
	foreach($_REQUEST as $TMP_k=>$TMP_v) {
		if ($TMP_v=="") continue;
		if (!in_array($TMP_k,array("footeroff","PHPSESSID","par0","func","dbname","V_dir","V_mod","V_idmod","V_lap","blocksoff","headeroff","menuoff","footeroof","subfunc"))) continue;
		echo "<input type=hidden name='".$TMP_k."' value='".$TMP_v."'>";
	}
	if ($op=="email") echo "<input type=hidden name='op' value='consemail'>";
	if ($op=="consemail") echo "<input type=hidden name='op' value='agregaemail'>";
	$TMP_lista=RAD_lookup("GIE_comunicadosgrupos","grupo","idgrupo",$par0);
	echo "<TABLE width=100% class=borde><TR><TH class=title>AGREGA DIRECCIONES EMAIL</TH></TR></TABLE>\n";
	echo "\n<table class=detail>\n";
	echo "<tr><th class=browse colspan=4>Lista: $TMP_lista</th></tr>\n";
	if ($op=="email") {
		$TMP_URLejemplo=$PHP_SELF."?";
		foreach($_REQUEST as $TMP_k=>$TMP_v) {
			if ($TMP_v=="" || $TMP_k=="subop") continue;
			$TMP_URLejemplo.=$TMP_k."=".$TMP_v."&";
		}
		$TMP_URLejemplo.="subop=ejemplo";
		echo "<tr><td class=detailtit>Direcciones Email<br>(separadas por comas <br>o por saltos de l&iacute;nea)<br><br>Nombres y Direcciones Email<br>(separados por punto y coma -CSV-)<br><br><br><a href='".$TMP_URLejemplo."'><img border=0 valign=-2 src='images/info.gif'> Ejemplos</a></td><td class=detail><textarea name=V0_emails cols=80 rows=30></textarea></td></tr>\n";
		echo "<tr><td colspan=4><center><input accesskey='S' title='ALT+S' type=submit name='agregaemail' value=' CONSULTA PARA AGREGAR DIRECCIONES EMAIL '></center></td></tr>\n";
	} 
	if ($op=="consemail" || $op=="agregaemail")  {
		$TMP_res=sql_query("SELECT * FROM GIE_comunicadosmiembros where idgrupo='$par0'", $RAD_dbi);
		while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
			$TMP_email=trim($TMP_row[email]);
			$TMP_idcontacto=trim($TMP_row[idcontacto]);
			$A_yaagregado[$TMP_email]="x";
			$A_yaagregadoidcontacto[$TMP_idcontacto]="x";
		}
		echo "<tr><td class=detailtit width=150>Direcciones Email:</td><td class=detail>\n<table width=100% border=0 cellpadding=0 cellspacing=0>";
		if (!ereg(";",$V0_emails)) { // No es CSV
			$A_emails=explode("\n",str_replace(",","\n",$V0_emails."\n"));
		} else {
			$V0_emails=str_replace("\r","",$V0_emails);
			$A_emails=explode("\n",$V0_emails."\n");
		}
		$TMP_cont=1;
		$TMP_res=sql_query("SELECT * FROM GIE_contactos", $RAD_dbi);
		while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
			$TMP_email=trim($TMP_row[email]);
			if ($TMP_email=="") continue;
			$A_contacto[$TMP_email][$TMP_row[idcontacto]]=trim($TMP_row[nombre]." ".$TMP_row[apellidos]." ".$TMP_row[razonsocial]);
		}
		echo "<tr><td></td><th class=browse>Nombre</th><th class=browse>Email</th><th class=browse>Entidad</th></tr>\n";
		foreach($A_emails as $TMP_idx=>$TMP_email) {
			$TMP_email=trim($TMP_email);
			$TMP_nombre="";
			if (substr($TMP_email,strlen($TMP_email)-1)==";") $TMP_email=substr($TMP_email,0,strlen($TMP_email)-1);
			if (ereg(";",$TMP_email)) { // procede de CSV
				$A_tok=explode(";",$TMP_email);
				//for($ki=0; $ki<count($A_tok); $ki++) {
				//	if (trim($A_tok[$ki])=="") unset($A_tok[$ki]);
				//}
				for($ki=0; $ki<count($A_tok)-1; $ki++) {
					if ($TMP_nombre!="") $TMP_nombre.=";";
					$TMP_nombre.=$A_tok[$ki];
				}
				for($ki=count($A_tok); $ki>-1; $ki--) {
					$TMP_email=$A_tok[$ki]; // El Email es la ultima columna;
					if (ereg("@",$TMP_email)) break;
				}
			}
			if ($TMP_email=="") continue;
			if (!ereg("@",$TMP_email)) continue;
			if ($A_yaagregado[$TMP_email]!="") {
				if ($TMP_class==" class=row1") $TMP_class=" class=row2";
				else $TMP_class=" class=row1";
				echo "<tr><td".$TMP_class."></td><td".$TMP_class.">".$TMP_nombre."</td><td colspan=2".$TMP_class.">".$TMP_email." <b>ya existente en la lista</b></td></tr>\n";
				continue;
			}
			if ($A_repetido[$TMP_email]!="") {
				if ($TMP_class==" class=row1") $TMP_class=" class=row2";
				else $TMP_class=" class=row1";
				echo "<tr><td".$TMP_class."><td".$TMP_class.">".$TMP_nombre."</td></td><td colspan=2".$TMP_class.">".$TMP_email." <b>email repetido</b></td></tr>\n";
				continue;
			}
			if (count($A_contacto[$TMP_email])>0) foreach($A_contacto[$TMP_email] as $TMP_idcontacto=>$TMP_contacto) {
				if ($TMP_class==" class=row1") $TMP_class=" class=row2";
				else $TMP_class=" class=row1";
				echo "<tr><td".$TMP_class."></td><td".$TMP_class.">".$TMP_nombre."</td><td".$TMP_class.">".$TMP_email."</td><td".$TMP_class.">";
				echo "<input checked type=checkbox name='V0_email".$TMP_cont."' value='".urlencode($TMP_email)."'> ";
				echo "<input type=hidden name='V0_idcontacto".$TMP_cont."' value='".$TMP_idcontacto."'> ";
				echo $TMP_contacto."</td></tr>\n";
				$TMP_cont++;
			} else {
				if ($TMP_class==" class=row1") $TMP_class=" class=row2";
				else $TMP_class=" class=row1";
				echo "<tr><td".$TMP_class."><input checked type=checkbox name='V0_email".$TMP_cont."' value='".urlencode($TMP_email)."'><input type=hidden name='V0_nom".$TMP_cont."' value='".urlencode($TMP_nombre)."'></td><td".$TMP_class.">".$TMP_nombre."</td><td ".$TMP_class.">".$TMP_email."</td></tr>\n";
				$TMP_cont++;
			}
			$A_repetido[$TMP_email]="x";
		}
		echo "</table>\n";
		echo "<input type=hidden name='V0_nummax' value='".$TMP_cont."'> ";
		if ($op=="consemail") {
			if ($TMP_cont>1) echo "<tr><td colspan=4><b>".($TMP_cont-1)." reg.</b><center><input accesskey='S' title='ALT+S' type=submit name='agregaemail' value=' AGREGAR DIRECCIONES EMAIL '></center></td></tr>";
			else echo "<tr><td colspan=4><br><br><a acceskey='X' title='ALT+X' href='javascript:RAD_CloseW();'>Cerrar ventana</a><br><br></td></tr>";
		}
		if ($op=="agregaemail")  {
			$TMP_res=sql_query("SELECT * FROM paises", $RAD_dbi);
			while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
				$A_pais[$TMP_row[codpais]]=$TMP_row[pais];
			}
			$TMP_res=sql_query("SELECT * FROM provincias", $RAD_dbi);
			while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
				$A_prov[$TMP_row[codprovincia]]=$TMP_row[provincia];
			}
			$TMP_res=sql_query("SELECT * FROM municipios", $RAD_dbi);
			while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
				$A_muni[$TMP_row[idmunicipio]]=$TMP_row[municipio];
			}
			//echo "<tr><td class=detailtit>Direcciones Email:</td><td class=detail><table border=0 cellpadding=0 cellspacing=0>";
			$TMP_numagregados=0;
			for($ki=1; $ki<($V0_nummax+1); $ki++) {
				if ($_REQUEST["V0_email".$ki]!="") {
					$TMP_email=urldecode($_REQUEST["V0_email".$ki]);
					$TMP_nombre=urldecode($_REQUEST["V0_nom".$ki]);
					$TMP_idcontacto=$_REQUEST["V0_idcontacto".$ki];
					if ($A_yaagregado[$TMP_email]!="") continue;
					if ($A_yaagregadoidcontacto[$TMP_idcontacto]!="") continue;
					$TMP_direccion="";
					if ($TMP_idcontacto>0) {
						$TMP_res=sql_query("SELECT * FROM GIE_contactos where idcontacto='$TMP_id'", $RAD_dbi);
						$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
						$TMP_nombre=$TMP_row[nombre]." ".$TMP_row[apellidos];
						$TMP_direccion=$TMP_row[direccion];
						if (trim($TMP_row[codpostal])!="" || trim($TMP_row[poblacion])!="") $TMP_direccion.="\r\n".$TMP_row[codpostal]." ".trim($TMP_row[poblacion]);
						$TMP_pais=$A_pais[$TMP_row[codpais]];
						$TMP_provincia=$A_prov[$TMP_row[codprovincia]];
						$TMP_municipio=$A_muni[$TMP_row[idmunicipio]];
						if ($TMP_municipio!="") $TMP_direccion.="\r\n".$TMP_municipio;
						if ($TMP_provincia!="") $TMP_direccion.="\r\n".$TMP_provincia;
						if ($TMP_pais!="") $TMP_direccion.="\r\n".$TMP_pais;
					}
					//echo $TMP_email." ".$TMP_nombre."<br>";
					$cmd="INSERT INTO GIE_comunicadosmiembros SET idgrupo='$par0', idcontacto='$TMP_idcontacto', email=".converttosql($TMP_email).", nombre=".converttosql($TMP_nombre).", direccion=".converttosql($TMP_direccion).", activo='1', fechaalta='".date("Y-m-d H:i:s")."'";
					sql_query($cmd, $RAD_dbi);
					//echo $cmd."<br>";
					$TMP_numagregados++;
				}
			}
			//echo "</table></td></tr>\n";
			echo "<tr><td colspan=4><center>DIRECCIONES DE EMAIL AGREGADAS: <b> ".$TMP_numagregados."</b></center></td></tr>";
			echo "<tr><td colspan=4><br><br><a acceskey='X' title='ALT+X' href='javascript:RAD_CloseW();'>Cerrar ventana</a><br><br></td></tr>";
			if ($TMP_numagregados>0) {
				$TMP_res=sql_query("SELECT * FROM GIE_comunicadosgrupos where idgrupo='$par0'", $RAD_dbi);
				$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
				$TMP_filtro=$TMP_row[filtro].date("Y-m-d H:i:s")." Agregadas ".$TMP_numagregados." direcciones de Email\n";
				$cmd="UPDATE GIE_comunicadosgrupos set filtro=".converttosql($TMP_filtro)." where idgrupo='$par0'";
				sql_query($cmd, $RAD_dbi);
			}
		} 
		echo "</table>\n";
	} 
	CloseTable();
	echo "</form>";
}

if ($op=="sel" || $op=="del") {
	echo "<form name='F' action='".$PHP_SELF."' method='POST'>";
	foreach($_REQUEST as $TMP_k=>$TMP_v) {
		if ($TMP_v=="") continue;
		if (!in_array($TMP_k,array("footeroff","PHPSESSID","par0","func","dbname","V_dir","V_mod","V_idmod","V_lap","blocksoff","headeroff","menuoff","footeroof","subfunc"))) continue;
		echo "<input type=hidden name='".$TMP_k."' value='".$TMP_v."'>";
	}
	if ($op=="sel") echo "<input type=hidden name='op' value='conssel'>";
	if ($op=="del") echo "<input type=hidden name='op' value='consdel'>";
	$TMP_lista=RAD_lookup("GIE_comunicadosgrupos","grupo","idgrupo",$par0);
	if ($op=="sel") echo "<TABLE width=100% class=borde><TR><TH class=title>AGREGA USUARIOS REGISTRADOS A LA LISTA MEDIANTE FILTRO</TH></TR></TABLE>";
	if ($op=="del") echo "<TABLE width=100% class=borde><TR><TH class=title>BORRA DESTINATARIOS DE LA LISTA MEDIANTE FILTRO</TH></TR></TABLE>";
	OpenTable();
	echo "\n<table class=detail>";
	echo "<tr><th class=browse colspan=4>Lista: $TMP_lista</th></tr>\n";
	$TMP_file="modules/".$V_dir."/contactos.prj.php";
	$fp=fopen($TMP_file,"r");
	$TMP_content = fread($fp, filesize($TMP_file));
	fclose($fp);
	$A_lin=explode("\n",$TMP_content."\n");
	$TMP_default="";
	$TMP_numfield=0;
	foreach($A_lin as $TMP_idx=>$TMP_lin) {
		$A_x=explode('"',$TMP_lin.'"');
		if (ereg("TITLE",$TMP_lin)) $TMP_tit=$A_x[1];
		if (ereg("NAME",$TMP_lin)) $TMP_name=$A_x[1];
		if ($RAD_NLS[$TMP_name]!="") $TMP_tit=$RAD_NLS[$TMP_name];
		if (ereg("LENGTH",$TMP_lin)) $TMP_length=$A_x[1];
		if (ereg("ILENGTH",$TMP_lin)) $TMP_ilength=$A_x[1];
		if (ereg("CANBENULL",$TMP_lin)) $TMP_canbenull=$A_x[1];
		if (ereg("TYPE",$TMP_lin)) $TMP_type=$A_x[1];
		if (ereg("DTYPE",$TMP_lin)) $TMP_dtype=$A_x[1];
		if (ereg("EXTRA",$TMP_lin)) $TMP_extra=$A_x[1];
		if ($EXTRA[$TMP_name]!="") $TMP_extra=$EXTRA[$TMP_name];
		if (ereg("HELP",$TMP_lin)) $TMP_help=$A_x[1];
		if (ereg("SEARCHABLE",$TMP_lin)) $TMP_search=$A_x[1];
		if ($TMP_name=="voluntario") $TMP_search="on";
		if ($TMP_name=="socio") $TMP_extra="1:Si";
		if ($TMP_name=="voluntario") $TMP_extra="1:Si";
		if ($TMP_name=="recibiravisos") $TMP_extra="1:Si";
		if ($TMP_name=="recibirinfo") $TMP_extra="1:Si";
		if ($TMP_name=="recibirboletin") $TMP_extra="1:Si";
		if ($TMP_name=="recibirmemoria") $TMP_extra="1:Si";
		if (ereg("ROWDETAIL",$TMP_lin) && $TMP_search=="on") {
			if ($TMP_ilength>30) $TMP_ilength=30;
			if ($TMP_dtype=="popupdb") $TMP_dtype="plistdb";
			if ($TMP_dtype=="text") $TMP_dtype="stand";
			if ($TMP_dtype=="datetime") {
				$TMP_dtype="date";
				$TMP_default="0";
			}
			if ($TMP_dtype=="plistdbm") $TMP_dtype="plistdb";
			if (!in_array($TMP_name,array("periodicidad","otracantidad","mediopago","domiciliacion","cantidad","observaciones","fechaalta","cif","dianacimiento","mesnacimiento","anonacimiento","otracantidad","titular","tipoidentificacion","numeroidentificacion","entidad","clave","oficina","dc","cuenta","tipovia","via","piso","op","identidad","codigo","subcodigomigracion","codigomigracion","identidadpadre","cuentacontable","cuentacontrapartida","cuentacontableproveedor","cuentacontableproveedorcontrapartida","conyuge","cuentacontablecliente","cuentacontableclientecontrapartida", "descuento", "idtiposervicioproveedor", "fechainieval","fechaeval","cuentacontrapartidaproveedor", "cuentacontableacreedor", "cuentacontrapartidaacreedor", "idtiposerviciocliente", "tipocif"))) {
				$TMP_sel=RAD_editfield("V_".$TMP_name, $TMP_dtype, $TMP_length, $TMP_ilength, $TMP_extra, "", true, $TMP_default,"");
// RAD_editfield($fieldname, $fdtype, $flength, $filength, $fextra="", $TMP_onChange="", $fcanbenull=true, $value = "", $fname ="",$formName="F", $TMP_onFocus="")
				if ($TMP_numfield%2==0) {
					if ($TMP_numfield>0) echo "</tr>\n";
					echo "\n<tr class=detail>";
				}
				echo "<td class=detailtit>".$TMP_tit."<input type=hidden name='VT_".$TMP_name."' value='".$TMP_tit."'></td><td class=detail>".$TMP_sel."</td>\n";
				$TMP_default="";
				$TMP_numfield++;
			}
		}
	}
	if ($TMP_numfield%2==1) echo "<td></td><td></td>";
	echo "</tr>\n";
	if ($op=="sel") echo "<tr><td colspan=4><center><input accesskey='S' title='ALT+S' type=submit name='agrega' value=' CONSULTA PARA AGREGAR '></td></tr>";
	if ($op=="del") {
		echo "<tr><td colspan=4><center>Si no teclea ning&uacute;n criterio se seleccionan todos los registros</td></tr>";
		echo "<tr><td colspan=4><center><input accesskey='S' title='ALT+S' type=submit name='borra' value=' CONSULTA PARA BORRAR '></td></tr>";
	}
	CloseTable();
	echo "</form>";
}

if ($op=="conssel" || $op=="consdel") {
	$where=""; $filtro="";
	if ($V_particular!="" && $V_proyecto!="") {
		$filtro.=$VT_particular." parecido a '".$V_particular."' y ";
		$filtro.=$VT_proyecto." parecido a '".$V_proyecto."' y ";
		$X_emails="'X-X'";
		$TMP_cmd="SELECT email from donativos where particular='$V_particular' and proyecto='$V_proyecto'";
		if ($V_recibirinfo!="") {
			$filtro.=$VT_recibirinfo." igual a si y ";
			$TMP_cmd.=" and recibirinfo like '%".$V_recibirinfo."%'";
			$V_recibirinfo="";
		}
		$TMP_res=sql_query($TMP_cmd, $RAD_dbi);
		while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
			$X_emails.=",'".$TMP_row[0]."'";
		}
		$where.=" and email IN (".$X_emails.")";
		$V_particular=""; $V_proyecto="";
	}
	if ($V_voluntario!="" && $V_proyecto!="") {
		$filtro.=$VT_voluntario." igual a si y ";
		$filtro.=$VT_proyecto." parecido a '".$V_proyecto."' y ";
		$X_emails="'X-X'";
		$TMP_cmd="SELECT email from voluntarios where proyecto='$V_proyecto'";
		if ($V_recibirinfo!="") {
			$filtro.=$VT_recibirinfo." igual a si y ";
			$TMP_cmd.=" and recibirinfo like '%".$V_recibirinfo."%'";
			$V_recibirinfo="";
		}
		$TMP_res=sql_query($TMP_cmd, $RAD_dbi);
		while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
			$X_emails.=",'".$TMP_row[0]."'";
		}
		$where.=" and email IN (".$X_emails.")";
		$V_voluntario=""; $V_proyecto="";
	}
	if ($V_socio!="") {
		$filtro.=$VT_socio." igual a si y ";
		$X_emails="'X-X'";
		$TMP_res=sql_query("SELECT distinct(email) from socios", $RAD_dbi);
		while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
			$X_emails.=",'".$TMP_row[0]."'";
		}
		$where.=" and email IN (".$X_emails.")";
	}
	if ($V_voluntario!="") {
		$filtro.=$VT_voluntario." igual a si y ";
		$X_emails="'X-X'";
		$TMP_cmd="SELECT distinct(email) from voluntarios";
		$TMP_res=sql_query($TMP_cmd, $RAD_dbi);
		while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
			$X_emails.=",'".$TMP_row[0]."'";
		}
		$where.=" and email IN (".$X_emails.")";
	}
	if ($V_particular!="") {
		$filtro.=$VT_particular." parecido a '".$V_particular."' y ";
		$where.=" and particular like '%".$V_particular."%'";
	}
	if ($V_razon!="") {
		$V_razon=str_replace(" ","%",$V_razon);
		$filtro.=$VT_razon." parecido a '".$V_razon."' y ";
		$where.=" and razon like '%".$V_razon."%'";
	}
	if ($V_apellidos!="") {
		$V_apellidos=str_replace(" ","%",$V_apellidos);
		$filtro.=$VT_apellidos." parecido a '".$V_apellidos."' y ";
		$where.=" and apellidos like '%".$V_apellidos."%'";
	}
	if ($V_condicion!="") {
		$V_condicion=str_replace(" ","%",$V_condicion);
		$filtro.=$VT_condicion." parecido a '".$V_condicion."' y ";
		$where.=" and condicion like '%".$V_condicion."%'";
	}
	if ($V_sexo!="") {
		$filtro.=$VT_sexo." parecido a '".$V_sexo."' y ";
		$where.=" and sexo like '%".$V_sexo."%'";
	}
	if ($V_profesion!="") {
		$V_profesion=str_replace(" ","%",$V_profesion);
		$filtro.=$VT_profesion." parecido a '".$V_profesion."' y ";
		$where.=" and profesion like '%".$V_profesion."%'";
	}
	if ($V_proyecto!="") {
		$filtro.=$VT_proyecto." parecido a '".$V_proyecto."' y ";
		$X_emails="'X-X'";
		$TMP_cmd="SELECT distinct(email) from donativos where proyecto=".converttosql($V_proyecto);
		$TMP_cmd2="SELECT distinct(email) from voluntarios where proyecto=".converttosql($V_proyecto);
		if ($V_recibirinfo!="") {
			$filtro.=$VT_recibirinfo." igual a si y ";
			$TMP_cmd.=" and recibirinfo like '%".$V_recibirinfo."%'";
			$TMP_cmd2.=" and recibirinfo like '%".$V_recibirinfo."%'";
			$V_recibirinfo="";
		}
		$TMP_res=sql_query($TMP_cmd, $RAD_dbi);
		while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
			$X_emails.=",'".$TMP_row[0]."'";
		}
		$TMP_res=sql_query($TMP_cmd2, $RAD_dbi);
		while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
			$X_emails.=",'".$TMP_row[0]."'";
		}
		$where.=" and email IN (".$X_emails.")";
	}
	if ($V_idaccion!="") {
		$filtro.=$VT_idaccion." parecido a '".RAD_lookup("acciones","accion","idaccion",$V_idaccion)."' y ";
		$X_emails="'X-X'";
		$TMP_cmd="SELECT distinct(email) from firmas where idaccion='".$V_idaccion."'";
		if ($V_recibirinfo!="") {
			$filtro.=$VT_recibirinfo." igual a si y ";
			$TMP_cmd.=" and recibirinfo like '%".$V_recibirinfo."%'";
			$V_recibirinfo="";
		}
		$TMP_res=sql_query($TMP_cmd, $RAD_dbi);
		while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
			$X_emails.=",'".$TMP_row[0]."'";
		}
		$where.=" and email IN (".$X_emails.")";
	}
	if ($V_email!="") {
		$V_email=str_replace(" ","%",$V_email);
		$filtro.=$VT_email." parecido a '".$V_email."' y ";
		$where.=" and email like '%".$V_email."%'";
	}
	if ($V_codpostal!="") {
		$filtro.=$VT_codpostal." parecido a '".$V_codpostal."' y ";
		$where.=" and codpostal like '%".$V_codpostal."%'";
	}
	if ($V_poblacion!="") {
		$V_poblacion=str_replace(" ","%",$V_poblacion);
		$filtro.=$VT_poblacion." parecido a '".$V_poblacion."' y ";
		$where.=" and poblacion like '%".$V_poblacion."%'";
	}
	if ($V_provincia!="") {
		$filtro.=$VT_provincia." parecido a '".$V_provincia."' y ";
		$where.=" and provincia like '%".$V_provincia."%'";
	}
	if ($V_pais!="") {
		$filtro.=$VT_pais." parecido a '".$V_pais."' y ";
		$where.=" and pais like '%".$V_pais."%'";
	}
	if ($V_telefono!="") {
		$V_telefono=str_replace(" ","%",$V_telefono);
		$filtro.=$VT_telefono." parecido a '".$V_telefono."' y ";
		$where.=" and telefono like '%".$V_telefono."%'";
	}
	if ($V_idioma!="") {
		$V_idioma=str_replace(" ","%",$V_idioma);
		$filtro.=$VT_idioma." parecido a '".$V_idioma."' y ";
		$where.=" and idioma like '%".$V_idioma."%'";
	}
	if ($V_nombre!="") {
		$V_nombre=str_replace(" ","%",$V_nombre);
		$filtro.=$VT_nombre." parecido a '".$V_nombre."' y ";
		$where.=" and nombre like '%".$V_nombre."%'";
	}
	if ($V_periodicidad!="") {
		$filtro.=$VT_periodicidad." parecido a '".$V_periodicidad."' y ";
		$where.=" and periodicidad like '%".$V_periodicidad."%'";
	}
	if ($V_cantidad!="") {
		$V_cantidad=str_replace(" ","%",$V_cantidad);
		$filtro.=$VT_cantidad." parecido a '".$V_cantidad."' y ";
		$where.=" and cantidad like '%".$V_cantidad."%'";
	}
	if ($V_domiciliacion!="") {
		$filtro.=$VT_domiciliacion." parecido a '".$V_domiciliacion."' y ";
		$where.=" and domiciliacion like '%".$V_domiciliacion."%'";
	}
	if ($V_recibirinfo!="") {
		$filtro.=$VT_recibirinfo." igual a si y ";
		$where.=" and recibirinfo like '%".$V_recibirinfo."%'";
	}
	if ($V_recibirboletin!="") {
		$filtro.=$VT_recibirboletin." igual a si y ";
		$where.=" and recibirboletin like '%".$V_recibirboletin."%'";
	}
	if ($V_recibirmemoria!="") {
		$filtro.=$VT_recibirmemoria." igual a si y ";
		$where.=" and recibirmemoria like '%".$V_recibirmemoria."%'";
	}
	if ($V_recibiravisos!="") {
		$filtro.=$VT_recibiravisos." igual a si y ";
		$where.=" and recibiravisos like '%".$V_recibiravisos."%'";
	}
	if ($V_codprovincia!="") {
		$filtro.=$VT_codprovincia." igual a '".RAD_lookup("provincias","provincia","codprovincia",$V_codprovincia)."' y ";
		$where.=" and codprovincia='".$V_codprovincia."'";
	}
	if ($V_codpais!="") {
		$filtro.=$VT_codpais." igual a '".RAD_lookup("paises","pais","codpais",$V_codpais)."' y ";
		$where.=" and codpais='".$V_codpais."'";
	}
	if ($V_ididioma!="") {
		$filtro.=$VT_ididioma." igual a '".$V_ididioma."' y ";
		$where.=" and idioma='".$V_ididioma."'";
	}
	if ($V_observaciones!="") {
		$filtro.=$VT_observaciones." parecido a '".$V_observaciones."' y ";
		$where.=" and observaciones like '%".$V_observaciones."%'";
	}
	if ($V_fechaalta_year=="") {
		$V_fechaalta="%-";
		if ($V_fechaalta_month!="") $V_fechaalta.=$V_fechaalta_month."-";
		else $V_fechaalta.="%-";
		if ($V_fechaalta_day!="") $V_fechaalta.=$V_fechaalta_day;
		else $V_fechaalta.="%";
	} else {
		$V_fechaalta=$V_fechaalta_year."-";
		if ($V_fechaalta_month!="") $V_fechaalta.=$V_fechaalta_month."-";
		else $V_fechaalta.="%-";
		if ($V_fechaalta_day!="") $V_fechaalta.=$V_fechaalta_day;
		else $V_fechaalta.="%";
	}
	if ($V_fechaalta!="%-%-%") {
		$filtro.=$VT_fechaalta." parecido a '".$V_fechaalta."' y ";
		if (!ereg("%",$V_fechaalta)) $where.=" and fechaalta like '".$V_fechaalta."%'";
		else $where.=" and fechaalta like '".$V_fechaalta."'";
	}
	echo "<form action='".$PHP_SELF."' method='POST'>";
	foreach($_REQUEST as $TMP_k=>$TMP_v) {
		//echo $TMP_k."=".$TMP_v."<br>";
		if ($TMP_v=="") continue;
		if (!in_array($TMP_k,array("PHPSESSID","par0","func","dbname","V_dir","V_mod","V_idmod","V_lap","blocksoff","headeroff","menuoff","footeroof","subfunc"))) continue;
		echo "<input type=hidden name='".$TMP_k."' value='".$TMP_v."'>";
	}
	if ($op=="conssel") echo "<input type=hidden name='op' value='agrega'>";
	if ($op=="consdel") echo "<input type=hidden name='op' value='borra'>";
	$TMP_lista=RAD_lookup("GIE_comunicadosgrupos","grupo","idgrupo",$par0);
	if ($op=="conssel") echo "<TABLE width=100% class=borde><TR><TH class=title>AGREGA USUARIOS REGISTRADOS A LA LISTA MEDIANTE FILTRO</TH></TR></TABLE>";
	if ($op=="consdel") echo "<TABLE width=100% class=borde><TR><TH class=title>BORRA DESTINATARIOS DE LA LISTA MEDIANTE FILTRO</TH></TR></TABLE>";
	$TMP_num=0;
	//if ($op=="consdel" && $where=="") $where=" and 1";
	if ($where=="" && $op=="conssel") {
		$TMP_cont="<tr><td colspan=3><b>Selecciona un Criterio de Filtrado</b><br><br><a href='javascript:history.back();'>Volver Atr&aacute;s</a><br><br></td></tr>";
		OpenTable();
		echo "\n<table class=detail>";
		echo "<tr><th class=browse colspan=3>Lista: $TMP_lista</th></tr>";
		echo $TMP_cont;
		CloseTable();
	} else {
		$TMP_cont="<tr><th class=browse>Nombre</th><th class=browse>Email</th><th class=browse>Direcci&oacute;n</th></tr>";
		if ($op=="consdel" && $where=="") {
			$TMP_res=sql_query("SELECT * FROM GIE_comunicadosmiembros WHERE idgrupo='$par0'", $RAD_dbi);
			while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
				if ($TMP_class==" class=row1") $TMP_class=" class=row2";
				else $TMP_class=" class=row1";
				$TMP_cont.="<tr class=detail><td".$TMP_class."><input type=checkbox name='V_xi".$TMP_row[idmiembro]."' value='X' checked> &nbsp; ";
				$TMP_cont.=$TMP_row[nombre];
				if (trim($TMP_row[representante])!="") $TMP_cont.="<br>".$TMP_row[representante];
				if (trim($TMP_row[cargo])!="") $TMP_cont.=" [".$TMP_row[cargo]."]";
				$TMP_cont.="</td><td".$TMP_class.">".trim($TMP_row[email])."</td><td".$TMP_class.">";
				$TMP_cont.=$TMP_row[direccion];
				$TMP_cont.="</td></tr>";
				$TMP_num++;
			}
		} else {
			if ($op=="consdel"||$op=="conssel") {
				$TMP_res=sql_query("SELECT * FROM GIE_comunicadosmiembros WHERE idgrupo='$par0'", $RAD_dbi);
				while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
					$A_esdelgrupo[$TMP_row[identidad]]="X";
					$A_esdelgrupoc[$TMP_row[idcontacto]]="X";
				}
			}
			$TMP_res=sql_query("SELECT * FROM GIE_contactos WHERE ".substr($where,5), $RAD_dbi);
			while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
				if ($op=="consdel") {
					if ($A_esdelgrupoc[$TMP_row[idcontacto]]=="") continue; // no pertenece al grupo
				}
				if ($TMP_class==" class=row1") $TMP_class=" class=row2";
				else $TMP_class=" class=row1";
				$TMP_cont.="<tr class=detail><td".$TMP_class.">";
				if ($op=="conssel" && $A_esdelgrupoc[$TMP_row[idcontacto]]!="") $TMP_cont.=" &nbsp; ";
				else $TMP_cont.="<input type=checkbox name='V_idcontacto".$TMP_row[idcontacto]."' value='X' checked> &nbsp; ";
				$TMP_cont.=" ".$TMP_row[nombre];
				if (trim($TMP_row[apellidos])!="") $TMP_cont.=" ".$TMP_row[apellidos];
				if ($op=="conssel" && $A_esdelgrupoc[$TMP_row[idcontacto]]!="") $TMP_cont.=" [ya es destinatario] ";
				$TMP_cont.="</td><td".$TMP_class.">".trim($TMP_row[email])."</td><td".$TMP_class.">";
				$TMP_cont.=$TMP_row[direccion];
				$TMP_cont.="</td></tr>";
				$TMP_num++;
			}
		}
		OpenTable();
		echo "\n<table class=detail>";
		echo "<tr><th class=browse colspan=3>Lista: $TMP_lista</th></tr>";
		echo $TMP_cont;
		if ($TMP_num>0) {
			if ($op=="conssel") echo "<tr><td colspan=3> &nbsp; <b>$TMP_num</b> reg. <div align=center><input accesskey='S' title='ALT+S' type=submit name='agrega' value=' AGREGA '></div></td></tr>";
			if ($op=="consdel") echo "<tr><td colspan=3> &nbsp; <b>$TMP_num</b> reg. <div align=center><input accesskey='S' title='ALT+S' type=submit name='borra' value=' BORRA '></div></td></tr>";
		} else {
			echo "<tr><td colspan=3><b>SIN REGISTROS SELECCIONADOS</b></div><br><br><a href='javascript:history.back();'>Volver Atr&aacute;s</a><br><br></td></tr>";
		}
		if ($filtro=="") $filtro="TODOS LOS DESTINATARIOS  ";
		echo "<input type=hidden name='V_filtro' value='".urlencode(substr($filtro,0,strlen($filtro)-2))."'><br> FILTRO: <b>";
		echo substr($filtro,0,strlen($filtro)-2);
		echo "</b><br>";
		CloseTable();
	}
	echo "</form>";
}
if ($op=="agrega" || $op=="borra") {
	$TMP_cont=0;
	$TMP_res=sql_query("SELECT * FROM paises", $RAD_dbi);
	while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
		$A_pais[$TMP_row[codpais]]=$TMP_row[pais];
	}
	$TMP_res=sql_query("SELECT * FROM provincias", $RAD_dbi);
	while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
		$A_prov[$TMP_row[codprovincia]]=$TMP_row[provincia];
	}
	$TMP_res=sql_query("SELECT * FROM municipios", $RAD_dbi);
	while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
		$A_muni[$TMP_row[idmunicipio]]=$TMP_row[municipio];
	}
	foreach($_REQUEST as $TMP_k=>$TMP_v) {
		if (substr($TMP_k,0,12)=="V_idcontacto" && $TMP_v!="") {
			$TMP_idcontacto=substr($TMP_k,12);
			//echo $TMP_k."=".$TMP_v."<br>";
			$TMP_res=sql_query("SELECT * FROM GIE_contactos WHERE idcontacto='".$TMP_idcontacto."'", $RAD_dbi);
			$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
			$TMP_nombre=trim($TMP_row[nombre]." ".$TMP_row[apellidos]);
			$TMP_email=trim($TMP_row[email]);
			$TMP_direccion=$TMP_row[direccion];
			if (trim($TMP_row[codpostal])!="" || trim($TMP_row[poblacion])!="") $TMP_direccion.="\r\n".$TMP_row[codpostal]." ".trim($TMP_row[poblacion]);
			$TMP_pais=$A_pais[$TMP_row[codpais]];
			$TMP_provincia=$A_prov[$TMP_row[codprovincia]];
			$TMP_municipio=$A_muni[$TMP_row[idmunicipio]];
			if ($TMP_municipio!="") $TMP_direccion.="\r\n".$TMP_municipio;
			if ($TMP_provincia!="") $TMP_direccion.="\r\n".$TMP_provincia;
			if ($TMP_pais!="") $TMP_direccion.="\r\n".$TMP_pais;
			if ($op=="agrega") $cmd="INSERT INTO GIE_comunicadosmiembros SET idgrupo='$par0', idcontacto='$TMP_idcontacto', email=".converttosql($TMP_email).", nombre=".converttosql($TMP_nombre).", direccion=".converttosql($TMP_direccion).", activo='1', fechaalta='".date("Y-m-d H:i:s")."'";
			if ($op=="borra") $cmd="DELETE FROM GIE_comunicadosmiembros WHERE idgrupo='$par0' AND idcontacto='$TMP_idcontacto'";
			//echo $cmd."<br>";
			$TMP_cont++;
			sql_query($cmd, $RAD_dbi);
		} else if (substr($TMP_k,0,4)=="V_xi" && $TMP_v!="") {
			$TMP_id=substr($TMP_k,4);
			//echo $TMP_k."=".$TMP_v."<br>";
			$TMP_res=sql_query("SELECT * FROM GIE_comunicadosmiembros WHERE idmiembro='".$TMP_id."'", $RAD_dbi);
			$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
			$TMP_nombre=trim($TMP_row[nombre]);
			$TMP_email=trim($TMP_row[email]);
			$TMP_direccion=$TMP_row[direccion];
			if (trim($TMP_row[codpostal])!="" || trim($TMP_row[poblacion])!="") $TMP_direccion.="\r\n".$TMP_row[codpostal]." ".trim($TMP_row[poblacion]);
			$TMP_pais=$A_pais[$TMP_row[codpais]];
			$TMP_provincia=$A_prov[$TMP_row[codprovincia]];
			$TMP_municipio=$A_muni[$TMP_row[idmunicipio]];
			if ($TMP_municipio!="") $TMP_direccion.="\r\n".$TMP_municipio;
			if ($TMP_provincia!="") $TMP_direccion.="\r\n".$TMP_provincia;
			if ($TMP_pais!="") $TMP_direccion.="\r\n".$TMP_pais;
			if ($op=="borra") $cmd="DELETE FROM GIE_comunicadosmiembros WHERE idgrupo='$par0' AND idmiembro='$TMP_id'";
			//echo $cmd."<br>";
			$TMP_cont++;
			sql_query($cmd, $RAD_dbi);
		}
	}
	if ($op=="agrega") {
		$TMP_res=sql_query("SELECT * FROM GIE_comunicadosgrupos where idgrupo='$par0'", $RAD_dbi);
		$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
		$TMP_filtro=$TMP_row[filtro].date("Y-m-d H:i:s")." ".urldecode($V_filtro)."\n";
		$cmd="UPDATE GIE_comunicadosgrupos set filtro=".converttosql($TMP_filtro)." where idgrupo='$par0'";
		sql_query($cmd, $RAD_dbi);
		alert("Agregados $TMP_cont destinatarios a la lista");
	}
	if ($op=="borra") {
		$TMP_res=sql_query("SELECT count(*) from GIE_comunicadosmiembros WHERE idgrupo='$par0'", $RAD_dbi);
		$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
		//echo $cmd."<br>";
		if ($TMP_row[0]>0) {
			$TMP_res=sql_query("SELECT * FROM GIE_comunicadosgrupos where idgrupo='$par0'", $RAD_dbi);
			$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
			$TMP_filtro=$TMP_row[filtro].date("Y-m-d H:i:s")." BORRA ".urldecode($V_filtro)."\n";
			$cmd="UPDATE GIE_comunicadosgrupos set filtro=".converttosql($TMP_filtro)." where idgrupo='$par0'";
			sql_query($cmd, $RAD_dbi);
			alert("Borrados $TMP_cont destinatarios de la lista");
		} else {
			$cmd="UPDATE GIE_comunicadosgrupos set filtro='' where idgrupo='$par0'";
			sql_query($cmd, $RAD_dbi);
			alert("Borrados $TMP_cont destinatarios de la lista. Lista vacia");
		}
	}
	if (_DEF_POPUP_MARGIN=="SUBMODAL") {
		echo "\n<script>\n";
		echo "parent.location.reload();\n";
		echo "parent.hidePopWin();\n";
		echo "</script>\n";
	} else {
		echo "\n<script>\nif (window.opener) {var urlOpener=window.opener.location.href;\n if (urlOpener.indexOf('#')>0) { urlOpener=urlOpener.substr(0,urlOpener.indexOf('#')); }\n window.opener.location.href=urlOpener".$restUrl.";\n window.close(); }\n";
		echo "else { top.location.href=top.location.href".$restUrl."; top.RAD_hideL(); }\n</script>\n";
	}
}

if ($op=="envia" || $op=="genfich" || $op=="gencarta") {
	if ($op=="envia") {
		if ($conf!="") echo "<TABLE width=100% class=borde><TR><TH class=title>ENVIADO EMAIL A DESTINATARIOS DE LA LISTA</TH></TR></TABLE>";
		else echo "<TABLE width=100% class=borde><TR><TH class=title>ENVIA EMAIL A DESTINATARIOS DE LA LISTA</TH></TR></TABLE>";
	}
	if ($op=="genfich") {
		if ($conf!="") echo "<TABLE width=100% class=borde><TR><TH class=title>GENERADO FICHERO PARA CORREO POSTAL DE LOS DESTINATARIOS DE TODA LA LISTA</TH></TR></TABLE>";
		else echo "<TABLE width=100% class=borde><TR><TH class=title>GENERA FICHERO DE DIRECCIONES APARA CORREO POSTAL DE LOS DESTINATARIOS DE TODA LA LISTA</TH></TR></TABLE>";
	}
	if ($op=="gencarta") {
		if ($conf!="") echo "<TABLE width=100% class=borde><TR><TH class=title>GENERADA CARTA PARA DESTINATARIOS DE LA LISTA</TH></TR></TABLE>";
		else echo "<TABLE width=100% class=borde><TR><TH class=title>GENERA CARTA PARA DESTINATARIOS DE LA LISTA</TH></TR></TABLE>";
	}
	$TMP_res=sql_query("SELECT * FROM GIE_comunicadosgrupos WHERE idgrupo='$par0'", $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
	//$TMP_res2=sql_query("SELECT * FROM GIE_tratamientos", $RAD_dbi);
	//while($TMP_row2=sql_fetch_array($TMP_res2, $RAD_dbi)) {
	//	$A_trat[$TMP_row2[idtratamiento]]=$TMP_row2[tratamiento];
	//}
	$TMP_res2=sql_query("SELECT * FROM GIE_comunicados WHERE idgrupo='$par0'", $RAD_dbi);
	while($TMP_row2=sql_fetch_array($TMP_res2, $RAD_dbi)) {
		if ($TMP_row2[identidad]>0) $A_yaenviadoidentidad[$TMP_row2[identidad]]="x";
		if ($TMP_row2[idcontacto]>0) $A_yaenviadoidcontacto[$TMP_row2[idcontacto]]="x";
		if ($TMP_row2[emaildestino]!="") $A_yaenviadoemail[$TMP_row2[emaildestino]]="x";
	}
	$TMP_errores=""; $TMP_enviados=""; $TMP_conterr=0; $TMP_contenv=0; $TMP_sindir=""; $TMP_contsin=0; $TMP_correos=""; $TMP_cartas="";
	$TMP_res2=sql_query("SELECT * FROM GIE_comunicadosmiembros WHERE idgrupo='$par0'", $RAD_dbi);
	$TMP_numreg=0; 
	$TMP_numcarta=0;
	while($TMP_row2=sql_fetch_array($TMP_res2, $RAD_dbi)) {
		$nombre=""; $tratamiento="Sr./Sra."; $vocalsexo="o/a"; $direccion="";
		$A_x=explode(",",$TMP_row2[nombre]);
		if (count($A_x)==2) $TMP_row2[nombre]=$A_x[1]." ".$A_x[0];
		$TMP_row2[nombre]=trim($TMP_row2[nombre]);
		$nombre=$TMP_row2[nombre];
		if (trim($A_trat[$TMP_row2[idtratamiento]])!="") {
			$tratamiento=trim($A_trat[$TMP_row2[idtratamiento]]);
			$TMP_row2[nombre]=trim($A_trat[$TMP_row2[idtratamiento]]." ".$TMP_row2[nombre]);
		}
		if ($TMP_row2[idcontacto]>0) {
			$TMP_res3=sql_query("SELECT * FROM GIE_contactos WHERE idcontacto='".$TMP_row2[idcontacto]."'", $RAD_dbi);
			$TMP_row3=sql_fetch_array($TMP_res3, $RAD_dbi);
			$A_x=explode(",",$TMP_row3[representante]);
			if (count($A_x)==2) $TMP_row3[representante]=trim($A_x[1]." ".$A_x[0]);
			$TMP_row2[nombre]=$TMP_row3[nombre]." ".$TMP_row3[apellidos];
			if ($TMP_row3[sexo]=="M") $vocalsexo="o";
			if ($TMP_row3[sexo]=="F") $vocalsexo="a";
			if (trim($A_trat[$TMP_row3[idtratamiento]])!="") {
				$tratamiento=trim($A_trat[$TMP_row3[idtratamiento]]);
			}
			if ($TMP_row3[representante]=="" && trim($A_trat[$TMP_row3[idtratamiento]])!="") {
				$TMP_row2[nombre]=trim($A_trat[$TMP_row3[idtratamiento]]." ".$TMP_row2[nombre]);
			} else {
				$nombre=$TMP_row3[representante];
				$TMP_row3[representante]=trim($A_trat[$TMP_row3[idtratamiento]]." ".$TMP_row3[representante]);
			}
			if (trim($TMP_row2[direccion])=="") {
				$TMP_row2[direccion]=trim($TMP_row3[direccion]);
			}
		}
		$direccion=str_replace("\r","",$TMP_row2[direccion]);
		$direccion=str_replace("\n","<br>",$direccion);
		if (($A_yaenviadoidentidad[$TMP_row2[identidad]]!=""||$A_yaenviadoidcontacto[$TMP_row2[idcontacto]]!=""||$A_yaenviadoemail[$TMP_row2[email]]!="") && $op=="envia") {
			if ($op=="envia") {
				$TMP_errores.=$TMP_row2[nombre]." <b>".$TMP_row2[email]."</b><br>";
				$TMP_conterr++;
				continue;
			}
			if ($op=="genfich") {
				$TMP_errores.=$TMP_row2[nombre]."<br>";
				$TMP_conterr++;
				continue;
			}
		}
		if ($op=="envia") {
			if (trim($TMP_row2[email])=="") {
				$TMP_sindir.=$TMP_row2[nombre]."<br>";
				$TMP_contsin++;
				continue;
			}
			if ($TMP_class==" class=row1") $TMP_class=" class=row2";
			else $TMP_class=" class=row1";
			$TMP_enviados.="<tr><td".$TMP_class.">".$TMP_row2[nombre]."</td><td".$TMP_class.">".$TMP_row2[email]."</td></tr>\n";
			$TMP_contenv++;
		}
		if ($op=="genfich" || $op=="gencarta") {
			if ($op=="genfich" && trim($TMP_row2[direccion])=="") {
				$TMP_sindir.=$TMP_row2[nombre]."<br>";
				$TMP_contsin++;
				continue;
			}
			if ($TMP_class==" class=row1") $TMP_class=" class=row2";
			else $TMP_class=" class=row1";
			$TMP_enviados.="<tr><td".$TMP_class.">".$TMP_row2[nombre]."</td><td".$TMP_class.">".$TMP_row2[direccion]."</td></tr>\n";
			$TMP_direccion=str_replace("\r","",$TMP_row2[direccion]);
			$TMP_direccion=str_replace(";"," ",$TMP_direccion);
			$TMP_direccion=str_replace("\n",";",$TMP_direccion);
			$TMP_row3[cargo]=str_replace(";"," ",trim($TMP_row3[cargo]));

			if ($TMP_row3[representante]!="") $TMP_correos.=$TMP_row3[representante].";";
			if ($TMP_row3[cargo]!="") $TMP_correos.=$TMP_row3[cargo].";";
			$TMP_correos.=str_replace(";"," ",$TMP_row2[nombre]).";";
			$TMP_correos.=$TMP_direccion.";\r\n";
			$TMP_contenv++;
		}
		if ($op=="gencarta") {
			$TMP_contenido=str_replace("\$nombre",$nombre,$TMP_row[contenido]);
			$TMP_contenido=str_replace("\$fecha",$fecha,$TMP_contenido);
			$TMP_contenido=str_replace("\$pais",$pais,$TMP_contenido);
			$TMP_contenido=str_replace("\$provincia",$provincia,$TMP_contenido);
			$TMP_contenido=str_replace("\$municipio",$municipio,$TMP_contenido);
			$TMP_contenido=str_replace("\$poblacion",$poblacion,$TMP_contenido);
			$TMP_contenido=str_replace("\$sede",$sede,$TMP_contenido);
			$TMP_contenido=str_replace("\$usuario",$usuario,$TMP_contenido);
			$TMP_contenido=str_replace("\$entidad",$entidad,$TMP_contenido);
			$TMP_contenido=str_replace("\$tratamiento",$tratamiento,$TMP_contenido);
			$TMP_contenido=str_replace("\$vocalsexo",$vocalsexo,$TMP_contenido);
			$TMP_contenido=str_replace("\$direccion",$direccion,$TMP_contenido);
			$TMP_numcarta++;
			$TMP_cartas.="<table ";
			if ($TMP_numcarta>1) $TMP_cartas.="style='page-break-before:always !important;' ";
			$TMP_cartas.="border=0 height=100%><tr><td>".utf8_encode($TMP_contenido)."</td></tr></table>\n";
		} else {
			$TMP_asunto=str_replace("\$nombre",$TMP_row2[nombre],$TMP_row[asunto]);
			$TMP_contenido=str_replace("\$nombre",$TMP_row2[nombre],$TMP_row[contenido]);
			$TMP_contenido=str_replace("\$fecha",$fecha,$TMP_contenido);
			$TMP_contenido=str_replace("\$pais",$pais,$TMP_contenido);
			$TMP_contenido=str_replace("\$provincia",$provincia,$TMP_contenido);
			$TMP_contenido=str_replace("\$municipio",$municipio,$TMP_contenido);
			$TMP_contenido=str_replace("\$poblacion",$poblacion,$TMP_contenido);
			$TMP_contenido=str_replace("\$sede",$sede,$TMP_contenido);
			$TMP_contenido=str_replace("\$usuario",$usuario,$TMP_contenido);
			$TMP_contenido=str_replace("\$entidad",$entidad,$TMP_contenido);
			$TMP_contenido=str_replace("\$tratamiento",$tratamiento,$TMP_contenido);
			$TMP_contenido=str_replace("\$vocalsexo",$vocalsexo,$TMP_contenido);
			$TMP_contenido=str_replace("\$direccion",$direccion,$TMP_contenido);
		}
		if (trim($TMP_row[emailorigen])!="") $from=trim($TMP_row[emailorigen]);
		else $from=_DEF_ADMINMAIL;
		$cmd="INSERT into GIE_comunicados SET idgrupo='$par0', identidad='".$TMP_row2[identidad]."'";
		$cmd.=", idcontacto='".$TMP_row2[idcontacto]."'";
		if ($op=="genfich") {
			$cmd.=", direccion=".converttosql($TMP_row2[direccion]);
		}
		if ($op!="gencarta") {
			$cmd.=", asunto=".converttosql($TMP_asunto);
		}
		$cmd.=", idcomunicadotipo=".converttosql($TMP_row[idcomunicadotipo]);
		$cmd.=", idproyecto=".converttosql($TMP_row[idproyecto]);
		$cmd.=", idorden=".converttosql($TMP_row[idorden]);
		if ($op=="envia") {
			$cmd.=", emaildestino=".converttosql($TMP_row2[email]);
			$cmd.=", emailorigen=".converttosql($from);
		}
		$cmd.=", contenido=".converttosql($TMP_contenido);
		$cmd.=", fechaalta='".date("Y-m-d H:i:s")."'";
		$cmd.=", fechaenvio='".date("Y-m-d H:i:s")."'";
		$cmd.=", documentos=".converttosql($TMP_row[documentos]);
		//echo $cmd."<br>";
		$TMP_numreg++;
		if ($conf!="") {
			sql_query($cmd, $RAD_dbi);
			if ($op=="envia") {
				include_once("modules/".$V_dir."/lib.Email.php");
				F_SendMail($from,_DEF_ADMINMAILNAME,$TMP_row2[email],"",$TMP_asunto,$TMP_contenido,"",$TMP_row[documentos],true);
				sleep(1);
			}
		}
	}
	if ($conf=="" && $TMP_numreg>0) {
		echo "<form name=F action='".$PHP_SELF."' method=GET>";
		foreach($_REQUEST as $TMP_k=>$TMP_v) {
			if ($TMP_k=="conf") continue;
			echo "<input type=hidden name='$TMP_k' value='$TMP_v'>";
		}
		echo "<input type=hidden name='conf' value='X'>";
		echo "<center><input accesskey='S' title='ALT+S' type=submit name='agrega' value=' CONFIRMAR '></center><br>";
		echo "</form>";
	}
	$TMP_fich="";
	if ($conf!="" && $op=="gencarta" && $TMP_cartas!="") {
		if ($dbname=="") $dbname=_DEF_dbname;
		$TMP_fich="cartas.".date("Y.m.d.H.i.s").".".uniqid().".htm";
		$fp=fopen("files/".$dbname."/".$TMP_fich,"w");
		fwrite($fp, $TMP_cartas);
		fclose($fp);
		$TMP_fichcartas=$TMP_row[ficherocartas];
		if ($TMP_fichcartas!="") $TMP_fichcartas.="\n".$TMP_fich."\n";
		else $TMP_fichcartas=$TMP_fich."\n";
		$TMP_fichcartas=str_replace("\n\n","\n",$TMP_fichcartas);
		$cmd="UPDATE GIE_comunicadosgrupos SET ficherocartas='$TMP_fichcartas' WHERE idgrupo='$par0'";
		//echo $cmd."<br>";
		sql_query($cmd, $RAD_dbi);
	}
	if ($conf!="" && $op=="genfich" && $TMP_correos!="") {
		if ($dbname=="") $dbname=_DEF_dbname;
		$TMP_fich="correos.".date("Y.m.d.H.i.s").".".uniqid().".csv";
		$fp=fopen("files/".$dbname."/".$TMP_fich,"w");
		fwrite($fp, $TMP_correos);
		fclose($fp);
		$TMP_fichcorreos=$TMP_row[ficherocorreos];
		if ($TMP_fichcorreos!="") $TMP_fichcorreos.="\n".$TMP_fich."\n";
		else $TMP_fichcorreos=$TMP_fich."\n";
		$TMP_fichcorreos=str_replace("\n\n","\n",$TMP_fichcorreos);
		$cmd="UPDATE GIE_comunicadosgrupos SET ficherocorreos='$TMP_fichcorreos' WHERE idgrupo='$par0'";
		//echo $cmd."<br>";
		sql_query($cmd, $RAD_dbi);
	}
	if ($TMP_fich!="" && $op=="genfich") echo "<ul>Generado Fichero CSV para Correo Postal: <a href='files/".$dbname."/".$TMP_fich."'><b>".$TMP_fich."</b></a></ul>";
	if ($TMP_fich!="" && $op=="gencarta") echo "<ul>Generada Carta para Imprimir: <a target=_blank href='files/".$dbname."/".$TMP_fich."'><b>".$TMP_fich."</b></a></ul>";
	if ($TMP_enviados!="") echo "<table class=browse><th class=browse>Nombre</th><th class=browse>Email</th></tr>".$TMP_enviados."</table><b> $TMP_contenv reg. </b><br><br>";
	if ($TMP_sindir!="" && $op=="envia") echo "<h2>Sin Email</h2>".$TMP_sindir."<b> $TMP_contsin reg.</b><br><br>";
	if ($TMP_sindir!="" && $op=="genfich") echo "<h2>Sin Direccion Postal</h2>".$TMP_sindir."<b> $TMP_contsin reg.</b><br><br>";
	if ($TMP_errores!="") echo "<h2>Ya enviados</h2>".$TMP_errores."<b> $TMP_conterr reg.</b><br><br>";
	echo "&nbsp;<a acceskey='X' title='ALT+X' href='javascript:RAD_CloseW();'>Cerrar ventana</a><br><br>";
}

if ($op=="duplicalista") {
	echo "<form name='F' action='".$PHP_SELF."' method='POST'>";
	foreach($_REQUEST as $TMP_k=>$TMP_v) {
		if ($TMP_v=="" || $TMP_k=="conf" || $TMP_k=="V_nuevonombre") continue;
		echo "<input type=hidden name='".$TMP_k."' value='".$TMP_v."'>";
	}
	echo "<input type=hidden name='conf' value='X'>";
	$TMP_lista=RAD_lookup("GIE_comunicadosgrupos","grupo","idgrupo",$par0);
	echo "<TABLE width=100% class=borde><TR><TH class=title>DUPLICA LISTA CON SUS DESTINATARIOS</TH></TR></TABLE>";
	$TMP_res=sql_query("SELECT count(*) FROM GIE_comunicadosmiembros where idgrupo='$par0'", $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
	$TMP_numreg=$TMP_row[0];

	$A_campos=array();
	$TMP_res=sql_list_fields("GIE_comunicadosgrupos", $RAD_dbi);
	while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
		if ($TMP_row[Extra]=="auto_increment") continue;
		$A_campos[$TMP_row[Field]]="*";
	}
	$TMP_res=sql_query("SELECT * FROM GIE_comunicadosgrupos where idgrupo='$par0'", $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
	$cmd=""; $idgrupo="";
	$TMP_nombregrupo="";
	foreach($A_campos as $TMP_k=>$TMP_nada) {
		if ($TMP_k=="grupo") {
			if ($V_nuevonombre!="") $TMP_row[$TMP_k]=$V_nuevonombre;
			else $TMP_row[$TMP_k].=" ".date("Y-m-d H:i:s");
			$TMP_nombregrupo=$TMP_row[$TMP_k];
		}
		$cmd.=", ".$TMP_k."=".converttosql($TMP_row[$TMP_k]);
	}
	$cmd="INSERT INTO GIE_comunicadosgrupos set ".substr($cmd,1);
	//echo $cmd."<br>";
	if ($conf!="") {
		sql_query($cmd, $RAD_dbi);
		$idgrupo=sql_insert_id($RAD_dbi);
	}

	$A_campos=array();
	$TMP_res=sql_list_fields("GIE_comunicadosmiembros", $RAD_dbi);
	while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
		if ($TMP_row[Extra]=="auto_increment") continue;
		if ($TMP_row[Field]=="idgrupo") continue;
		$A_campos[$TMP_row[Field]]="*";
	}
	$TMP_res=sql_query("SELECT * FROM GIE_comunicadosmiembros where idgrupo='$par0'", $RAD_dbi);
	while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
		$cmd="";
		foreach($A_campos as $TMP_k=>$TMP_nada) {
			$cmd.=", ".$TMP_k."=".converttosql($TMP_row[$TMP_k]);
		}
		$cmd="INSERT INTO GIE_comunicadosmiembros set idgrupo='".$idgrupo."'".$cmd;
		//echo $cmd."<br>";
		if ($conf!="") {
			sql_query($cmd, $RAD_dbi);
		}
	}

	OpenTable();
	echo "\n<table class=detail>";
	echo "<tr><th class=browse colspan=4>Lista: $TMP_lista</th></tr>\n";
	echo "<tr><td colspan=4> <b>$TMP_numreg</b> Destinatarios</td></tr>";
	if ($conf=="") {
		echo "<tr><td colspan=4>Nuevo Nombre de la Lista: <input size=40 type=text name=V_nuevonombre value='".str_replace("'","",$TMP_nombregrupo)."'></td></tr>";
		echo "<tr><td colspan=4><center><input accesskey='S' title='ALT+S' type=submit name='agrega' value=' DUPLICA '></td></tr>";
	} else {
		echo "<tr><td colspan=4><center><b>DUPLICADA LISTA</b> como <a target=_blank href='".$PHP_SELF."?V_dir=".$V_dir."&V_mod=".$V_mod."&func=detail&par0=".$idgrupo."'><b>$TMP_nombregrupo</b></a></td></tr>";
	}
	CloseTable();
	echo "</form>";
}

if ($op=="enviaemailprueba") {
	echo "<form name='F' action='".$PHP_SELF."' method='POST'>";
	foreach($_REQUEST as $TMP_k=>$TMP_v) {
		if ($TMP_v=="" || $TMP_k=="conf" || $TMP_k=="V_emailprueba") continue;
		echo "<input type=hidden name='".$TMP_k."' value='".$TMP_v."'>";
	}
	echo "<input type=hidden name='conf' value='X'>";
	$TMP_lista=RAD_lookup("GIE_comunicadosgrupos","grupo","idgrupo",$par0);
	echo "<TABLE width=100% class=borde><TR><TH class=title>ENVIA EMAIL DE PRUEBA</TH></TR></TABLE>";

	$TMP_res=sql_query("SELECT * FROM GIE_comunicadosgrupos where idgrupo='$par0'", $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);

	OpenTable();
	echo "\n<table class=detail>";
	if (trim($TMP_row[emailorigen])!="") $from=trim($TMP_row[emailorigen]);
	else $from=_DEF_ADMINMAIL;
	if ($conf=="") {
		echo "<tr><td>Para Email de Prueba:</td><td colspan=3><input size=40 type=text name=V_emailprueba value='"._DEF_ADMINMAIL."'></td></tr>";
		echo "<tr><td>De:</td><td colspan=3>".$from."</td></tr>";
		echo "<tr><td>Asunto:</td><td colspan=3>".$TMP_row[asunto]."</td></tr>";
		echo "<tr><td>Mensaje:</td><td colspan=3>".$TMP_row[contenido]."</td></tr>";
		echo "<tr><td>Adjuntos:</td><td colspan=3>".$TMP_row[documentos]."</td></tr>";
		echo "<tr><td colspan=4><br><center><input accesskey='S' title='ALT+S' type=submit name='agrega' value=' ENVIAR '></td></tr>";
	} else {
		echo "<tr><td>Para Email de Prueba:</td><td colspan=3>".$V_emailprueba."</td></tr>";
		echo "<tr><td>De:</td><td colspan=3>".$from."</td></tr>";
		echo "<tr><td>Asunto:</td><td colspan=3>".$TMP_row[asunto]."</td></tr>";
		echo "<tr><td>Mensaje:</td><td colspan=3>".$TMP_row[contenido]."</td></tr>";
		echo "<tr><td>Adjuntos:</td><td colspan=3>".$TMP_row[documentos]."</td></tr>";
		include_once("modules/".$V_dir."/lib.Email.php");
		F_SendMail($from,_DEF_ADMINMAILNAME,$V_emailprueba,"",$TMP_row[asunto],$TMP_row[contenido],"",$TMP_row[documentos],true);
		echo "<tr><td colspan=4><br><center><b>ENVIADO EMAIL DE PRUEBA A $V_emailprueba</b></td></tr>";
		echo "<tr><td colspan=4><br><br><a acceskey='X' title='ALT+X' href='javascript:RAD_CloseW(false);'>Cerrar ventana</a><br><br></td></tr>";
	}
	CloseTable();
	echo "</form>";
}


return $TMP."\n\n\n";
?>
