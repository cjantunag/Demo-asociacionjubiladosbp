<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: ../index.php");
    die();
}
global $RAD_dbi, $V_block, $V_dir, $V_mod, $CAMPO, $searchfield0, $query, $PHPSESSID, $dbname, $headeroff, $footeroff, $menuoff, $blocksoff;

if (RAD_existtable("GIE_ejercicios")) {
	$TMP_res=sql_query("SELECT * FROM GIE_ejercicios ORDER BY ejercicio DESC", $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);
	$TMP_idejercicio=$TMP_row[idejercicio];
}

$TMP_cont=0;

$TMP_select="<select name=CAMPO onChange='javascript:salta(this[this.selectedIndex].value);'><option value=''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;buscar en...</option>";
$TMP_vals = "";
$TMP_vals.= "<input type=hidden name=idejercicio value='$TMP_idejercicio'>"; // campos necesarios para buscar en Mayor Ultimo Ejercicio
$TMP_vals.= "<input type=hidden name=muestraasiento value='x'>";

if ($_REQUEST[V_block]=="search" && $_REQUEST[V_dir]!="" && $_REQUEST[V_idmod]=="" && $_REQUEST[V_mod]=="") {
	alert("Selecciona Campo de Busqueda");
	if ($query!="") $TMP_query="?query=".urlencode($query);
	else $TMP_query="";
	die("\n<script>\nwindow.location='index.php".$TMP_query."';\n</script>\n");
}

$V_def_vdir="gi2";

	$TMP_select.="<option value=''>_GENERAL_</option>";
	////$TMP_select.="<optgroup label='__GENERAL__'>";

$TMP_vals .= "<input type=hidden name=email value=''>";
$TMP_vals .= "<input type=hidden name=telefono value=''>";
$TMP_vals .= "<input type=hidden name=cifnif value=''>";
$TMP_vals .= "<input type=hidden name=nombre value=''>";

if (is_modulepermitted("", "$V_def_vdir", "nombres")) {
	$TMP_cont++;
	$TMP_select.="<option style='background-color:#C0C0C0;'";
	if ($CAMPO=="nombres:nombre") $TMP_select.=" SELECTED";
	$TMP_select.=" value='nombres:nombre'>Nombre</option>";
}

if (is_modulepermitted("", "$V_def_vdir", "emails")) {
	$TMP_cont++;
	$TMP_select.="<option style='background-color:#C0C0C0;'";
	if ($CAMPO=="emails:email") $TMP_select.=" SELECTED";
	$TMP_select.=" value='emails:email'>Email</option>";
}

if (is_modulepermitted("", "$V_def_vdir", "telefonos")) {
	$TMP_cont++;
	$TMP_select.="<option style='background-color:#C0C0C0;'";
	if ($CAMPO=="telefonos:telefono") $TMP_select.=" SELECTED";
	$TMP_select.=" value='telefonos:telefono'>Tel&eacute;fono</option>";
}

if (is_modulepermitted("", "$V_def_vdir", "cifnif")) {
	$TMP_cont++;
	$TMP_select.="<option style='background-color:#C0C0C0;'";
	if ($CAMPO=="cifnif:cifnif") $TMP_select.=" SELECTED";
	$TMP_select.=" value='cifnif:cifnif'>CIF/NIF</option>";
}

if (is_modulepermitted("", "$V_def_vdir", "contactosentidades")) {
	$TMP_cont++;
	$TMP_select.="<option value=''>_CONTACTOS/ENTIDADES_</option>";
	////$TMP_select.="<optgroup label='__ENTIDAD__'>";

	$TMP_select.="<option style='background-color:#C0C0C0;'";
	if ($CAMPO=="contactosentidades:entidad") $TMP_select.=" SELECTED";
	$TMP_select.=" value='contactosentidades:entidad'>Entidad</option>";
	$TMP_vals .= "<input type=hidden name=entidad value=''>";

	$TMP_select.="<option style='background-color:#C0C0C0;'";
	if ($CAMPO=="contactosentidades:representante") $TMP_select.=" SELECTED";
	$TMP_select.=" value='contactosentidades:representante'>Representante</option>";
	$TMP_vals .= "<input type=hidden name=representante value=''>";

	$TMP_select.="<option style='background-color:#C0C0C0;'";
	if ($CAMPO=="contactosentidades:codigo") $TMP_select.=" SELECTED";
	$TMP_select.=" value='contactosentidades:codigo'>Cod.Entidad</option>";
	$TMP_vals .= "<input type=hidden name=codigo value=''>";

	$TMP_select.="<option style='background-color:#C0C0C0;'";
	if ($CAMPO=="contactosentidades:cif") $TMP_select.=" SELECTED";
	$TMP_select.=" value='contactosentidades:cif'>CIF/NIF</option>";

	$TMP_select.="<option style='background-color:#C0C0C0;'";
	if ($CAMPO=="contactosentidades:email") $TMP_select.=" SELECTED";
	$TMP_select.=" value='contactosentidades:email'>Email</option>";

	$TMP_select.="<option style='background-color:#C0C0C0;'";
	if ($CAMPO=="contactosentidades:telefono") $TMP_select.=" SELECTED";
	$TMP_select.=" value='contactosentidades:telefono'>Tel&eacute;fono</option>";

	////$TMP_select.="</optgroup>";
}

if (is_modulepermitted("", "$V_def_vdir", "proyectos")) {
	$TMP_cont++;
	$TMP_select.="<option value=''>_PROYECTOS_</option>";

	$TMP_select.="<option style='background-color:#C0C0C0;'";
	if ($CAMPO=="proyectos:proyecto") $TMP_select.=" SELECTED";
	$TMP_select.=" value='proyectos:proyecto'>Proyecto</option>";
	$TMP_vals .= "<input type=hidden name=proyecto value=''>";

	$TMP_select.="<option style='background-color:#C0C0C0;'";
	if ($CAMPO=="proyectos:codproyecto") $TMP_select.=" SELECTED";
	$TMP_select.=" value='proyectos:codproyecto'>Cod.Proyecto</option>";
	$TMP_vals .= "<input type=hidden name=codproyecto value=''>";
}

if (is_modulepermitted("", "$V_def_vdir", "ventasfactura")) {
	$TMP_cont++;
	$TMP_select.="<option value=''>_FACTURAS VENTA_</option>";
	$TMP_select.="<option style='background-color:#C0C0C0;'";
	if ($CAMPO=="ventasfactura:numalbaran") $TMP_select.=" SELECTED";
	$TMP_select.=" value='ventasfactura:numalbaran'>Num. Factura</option>";
	$TMP_vals .= "<input type=hidden name=numalbaran value=''>";
	$TMP_select.="<option style='background-color:#C0C0C0;'";
	if ($CAMPO=="ventasfactura:concepto") $TMP_select.=" SELECTED";
	$TMP_select.=" value='ventasfactura:concepto'>Concepto</option>";
	$TMP_vals .= "<input type=hidden name=concepto value=''>";
}

if (is_modulepermitted("", "$V_def_vdir", "comprasfactura")) {
	$TMP_cont++;
	$TMP_select.="<option value=''>_FACTURAS COMPRA_</option>";
	$TMP_select.="<option style='background-color:#C0C0C0;'";
	if ($CAMPO=="comprasfactura:numalbaran") $TMP_select.=" SELECTED";
	$TMP_select.=" value='comprasfactura:numalbaran'>Num. Factura</option>";
	$TMP_vals .= "<input type=hidden name=numalbaran value=''>";
	$TMP_select.="<option style='background-color:#C0C0C0;'";
	if ($CAMPO=="comprasfactura:concepto") $TMP_select.=" SELECTED";
	$TMP_select.=" value='comprasfactura:concepto'>Concepto</option>";
	$TMP_vals .= "<input type=hidden name=concepto value=''>";
}

if (is_modulepermitted("", "$V_def_vdir", "mayor")) {
	$TMP_cont++;
	$TMP_select.="<option value=''>_Libro MAYOR_</option>";
	$TMP_select.="<option style='background-color:#C0C0C0;'";
	if ($CAMPO=="mayor:cuentaVer") $TMP_select.=" SELECTED";
	$TMP_select.=" value='mayor:cuentaVer'>Cuenta</option>";
	$TMP_vals .= "<input type=hidden name=cuentaVer value=''>";
}
if ($TMP_cont==0) return "";

$TMP_select.="</select>";

$content = "<table border=0 cellpadding=0 cellspacing=0 width=100%>";
$content .= "<tr><td align=left nowrap>";
$content .= "<form style=\"border:0;margin:0;padding:0;\" name=FSRCH3S action=\"index.php\" method=\"post\" onsubmit=\"javascript:return comprueba(document.FSRCH3S.CAMPO[document.FSRCH3S.CAMPO.selectedIndex].value);\">";
if ($V_block=="search") $content .= "<input type=hidden name=V_dir value='".$V_dir."'><input type=hidden name=V_mod value='".$V_mod."'>";
else $content .= "<input type=hidden name=V_dir value=''><input type=hidden name=V_mod value=''>";
$content .= "<input type=hidden name=PHPSESSID value='$PHPSESSID'>";
$content .= "<input type=hidden name=func value='search'>";
$content .= "<input type=hidden name=dbname value='$dbname'>";
$content .= "<input type=hidden name=V_prevfunc value='searchform'>";
$content .= "<input type=hidden name=V_block value='search'>";
$content .= "<input type=hidden name=searchfield0 value='".$searchfield0."'>";
$content .= "<input type=hidden name=searchvalue0 value='".$searchvalue0."'>";
$content .= "<input type=hidden name=orderby value='".$searchfield0."'>";
$content .= "<input type=hidden name=operator0 value='LIKE'>";
$content .= "<input type=hidden name=headeroff value='$headeroff'>";
$content .= "<input type=hidden name=footeroff value='$footeroff'>";
$content .= "<input type=hidden name=menuoff value='$menuoff'>";
$content .= "<input type=hidden name=showall value='x'>";
$content .= "<input type=hidden name=blocksoff value='$blocksoff'>";
if ($query!="") $content .= "<input type=\"text\" name=\"query\" size=\"15\" value='".$query."'><br>";
else $content .= "<input type=\"text\" name=\"query\" size=\"15\" value=' registro ...' onclick='javascript:document.FSRCH3S.query.value=\"\";'><br>";
$content .= "&nbsp;".$TMP_select.$TMP_vals."&nbsp;</form>";
$content .= "</td></tr></table>";

$content .= "\n<script>
function comprueba(valor) { 
    if (document.FSRCH3S.query.value=='') {
	document.FSRCH3S.CAMPO.selectedIndex=0; 
	alert('Primero introduce el valor a buscar');
	return false;
    }
    if (valor=='') alert('Selecciona un Campo de Busqueda de los sombreados'); 
    else {
	document.FSRCH3S.searchvalue0.value=document.FSRCH3S.query.value;
	document.FSRCH3S.V_dir.value='$V_def_vdir'; 
	pos=valor.indexOf(':',0);
	if (pos > 0) {
	  modulo=valor.substring(0,pos);
	  campobusca=valor.substring(pos+1);
          if (campobusca=='' || modulo=='') {
	    alert('Selecciona un Campo de Busqueda de los sombreados.'); 
	    return false;
	  }
	  document.FSRCH3S.V_mod.value=modulo; 
	  document.FSRCH3S.searchfield0.value=campobusca; 
	  document.FSRCH3S.orderby.value=campobusca; 
	  eval('document.FSRCH3S.'+campobusca+'.value=document.FSRCH3S.query.value;');
	} else {
	  pos=valor.indexOf('&',0);
	  if (pos > 0) {
		modulo=valor.substring(0,pos);
		restoURL=valor.substring(pos+1);
		document.FSRCH3S.V_mod.value=modulo; 
		document.FSRCH3S.action=document.FSRCH3S.action+'&'+restoURL+'='+escape(document.FSRCH3S.query.value); 
		//alert('action='+document.FSRCH3S.action+'.CAMPO='+valor+'.modulo='+modulo+'.restourl='+restoURL);
	  } else {
	    alert('Opcion de Busqueda Incorrecta'); 
	    return false;
	  }
	}
	//alert('modulo='+modulo+'.campo busqueda='+campobusca);
	//alert(document.FSRCH3S.V_mod.value);
	return true;
    }
}
function salta(valor) { 
    if (comprueba(valor)) { 
	document.FSRCH3S.submit(); 
    }
}
//comprueba(document.FSRCH3S.CAMPO[document.FSRCH3S.CAMPO.selectedIndex].value);
</script>\n";

?>
