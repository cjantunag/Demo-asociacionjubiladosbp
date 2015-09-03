<?php
//#!/usr/bin/php -q
//############################################################################################
//# Proceso que genera correo con el estado del servidor
//##0 1 * * * /usr/bin/php .../privado/emailstatus.php [-email=pruebas@gmail.com,otro@gmail.com] >>/tmp/emailstatus.log 2>&1
//## Si se pone sin parametros solo envia correo a administrador
//############################################################################################
global $_SERVER;
set_time_limit(3600);

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_DEPRECATED);

for ($ki=0; $ki<$_SERVER["argc"]; $ki++) {
	$opt=($_SERVER["argv"][$ki]);
	if (substr($opt,0,7)=="-email=") $email=substr($opt,7);
}

$TMP_fich="/tmp/emailstatus.log";

chdir("..");
require_once("sqlDB.php");
require_once("functions.php");
require_once("config.php");
if ($dbname=="") $dbname = _DEF_dbname;
if ($email=="") $email=_DEF_ADMINMAIL;
$RAD_dbi = sql_connect(_DEF_dbhost, _DEF_dbuname, _DEF_dbpass, $dbname);

$TMP_asunto="Status de ".html_entity_decode(_DEF_SITENAME,ENT_QUOTES,"ISO-8859-1");
$TMP_asunto="Status de ".html_entity_decode(_DEF_SITENAME,ENT_QUOTES,"UTF-8");
//for($ki=0; $ki<strlen($TMP_asunto); $ki++) echo $ki." ".$TMP_asunto[$ki]." ".ord($TMP_asunto[$ki])."\n";

system("find . -mtime -1 -ls > ".$TMP_fich);
system("grep 'op=NOTIF' /home/xxxx/logs/apache.log | grep ' /index.php' >> ".$TMP_fich);
system("df -k >> ".$TMP_fich);
system("mysqlcheck "._DEF_dbname." --user="._DEF_dbuname." --password="._DEF_dbpass." >> ".$TMP_fich);
$fp=fopen($TMP_fich,"r");
$TMP_cuerpo=fread($fp,filesize($TMP_fich));
fclose($fp);
$TMP_cuerpo=str_replace("//","",$TMP_cuerpo); // Elimina las barras de direcciones para evitar ser declarado como SPAM

$A_email=explode(",",$email.",");
foreach($A_email as $TMP_idx=>$TMP_email) {
	if ($TMP_email=="") continue;
	RAD_sendMail($TMP_email,"","",_DEF_ADMINMAIL,$TMP_asunto,$TMP_cuerpo,"");
	echo "Envia Correo a:".$TMP_email.". Con Asunto:".$TMP_asunto.". Y cuerpo:\n".$TMP_cuerpo."\n\n";
	echo "... Fin ".date("Y-m-d H:i:s")."\n";
}
system("rm ".$TMP_fich);

//function is_user() { return true; }
?>
