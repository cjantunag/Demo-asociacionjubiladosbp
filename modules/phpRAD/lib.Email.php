<?php
if (eregi(basename(__FILE__), $PHP_SELF))  die ("Security Error ...");

//--------------------------------------------------------------------------------------------------------------
function F_SendMail($from,$to,$subject,$body,$adjuntos="",$html=true) {
	global $dbname;
	if ($dbname=="") $dbname=_DEF_dbname;

	if (!ereg("@",$from)) {
        	$from="=?ISO-8859-1?B?".base64_encode($from."  ")."?=";
	}
        $subject="=?ISO-8859-1?B?".base64_encode($subject."  ")."?=";

	$from=str_replace("\r","",$from); $from=str_replace("\n",",",$from);
	$to=str_replace("\r","",$to); $to=str_replace("\n",",",$to);

	$to=str_replace("\n",",",$to);
	include_once("modules/phpmailer/class.phpmailer.php");
	$mail=new PHPMailer();
	$mail->LE="\n";
	$mail->CharSet="iso-8859-1";
	$mail->From=$from;
	$mail->FromName=$from; 
	$mail->Subject=$subject;
	if ($html==true) $mail->MsgHTML($body);
	else $mail->AltBody=$body;
	$destinatarios = explode(";",$to);
	foreach($destinatarios as $d) $mail->AddAddress(trim($d),trim($d));
	if($adjuntos!="") {
		$A_adjuntos=explode("\n",$adjuntos);
		foreach($A_adjuntos as $adjunto) {
			if (trim($adjunto)=="") continue;
			$adjunto="files/".$dbname."/".$adjunto;
			if(file_exists($adjunto)) {
				$mail->AddAttachment($adjunto,basename($adjunto));
			}
		}
	}
	if(!$mail->Send()) echo "Error al enviar E-MAIL";
	return "";
}
?>
