<?php
if (eregi(basename(__FILE__), $PHP_SELF))  die ("Security Error ...");


$TMP_user=base64_decode($HTTP_SESSION_VARS["SESSION_user"]);
if (!is_admin()) {
    $defaultfilter="usuario='".$TMP_user."'";
}else{
    RAD_setFieldProperty("usuario","browsable=true,nodetail=false,nonew=false,noedit=false,noinsert=false,noupdate=false");
}

$campos = mysql_list_fields(_DEF_dbname, "impresos");
$columnas = mysql_num_fields($campos);
for ($i = 0; $i < $columnas; $i++) {$TMP_campo[] = mysql_field_name($campos, $i);}

if (in_array('modulo', $TMP_campo)) {
    RAD_setFieldProperty("modulo","browsable=true,nodetail=false,nonew=false,noedit=false,noinsert=false,noupdate=false");
}
if (in_array('usuario', $TMP_campo)) {
    RAD_setFieldProperty("usuario","nodetail=false,nonew=false,noedit=false,noinsert=false,noupdate=false");
}


return "";
?>
