<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");
// Manda email de prueba

global $RAD_dbi, $PHP_SELF, $V_dir, $dbname, $idemail, $_REQUEST;

$email=trim(str_replace("'","",$_REQUEST[email]));
$asunto="Prueba de Envio de Email";
$msg="Cuerpo del mensaje\nAbur....\n";
$email="jose.taibo@edisa.com";

include_once("modules/".$V_dir."/lib.Email.php");
F_SendMail(_DEF_ADMINMAIL,_DEF_ADMINMAILNAME,$email, "", $asunto, $msg, $msg, "", true);
echo "Enviado email de Prueba desde "._DEF_ADMINMAIL." ("._DEF_ADMINMAILNAME.") a ".$email.".";
return "";
?>
