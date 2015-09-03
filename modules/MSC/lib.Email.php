<?php
if (eregi(basename(__FILE__), $PHP_SELF))  die ("Security Error ...");

//--------------------------------------------------------------------------------------------------------------
function F_SendMail($from, $fromname, $to, $bcc, $subject, $body, $altbody="", $adjuntos="", $html=false) {
	global $dbname;

	$subject=utf8_decode(html_entity_decode(utf8_decode($subject)));
	$body=utf8_decode(html_entity_decode(utf8_decode($body)));
	$altbody=utf8_decode(html_entity_decode($altbody));
	$subject.=" ";
	if ($dbname=="") $dbname=_DEF_dbname;

	if (defined('_DEF_SMTP')) {
		$mailer = "smtp";
		$SMTPHost = _DEF_SMTP;
		$SMTPAuth = true;
		$SMTPUser = _DEF_SMTP_USER;
		$SMTPPass = _DEF_SMTP_PASS;
	} else {
		$mailer = "mail";
		$SMTPHost = "";
		$SMTPAuth = false;
		$SMTPUser = "";
		$SMTPPass = "";
	}


	
	include_once("modules/phpmailer/class.phpmailer.php");  
	
	if (!ereg("@",$from)) {
		$from="=?ISO-8859-1?B?".base64_encode($from."  ")."?=";
	}
	$subject="=?ISO-8859-1?B?".base64_encode($subject."  ")."?=";

	$from=str_replace("\r","",$from); $from=str_replace("\n",",",$from);
	$to=str_replace("\r","",$to); $to=str_replace("\n",",",$to);
	
	$mail = new phpmailer();	
	$mail->PluginDir = "modules/phpmailer/";
	$mail->LE="\n";
	$mail->CharSet="ISO-8859-1";
	$mail->Mailer = $mailer;	
	
	$mail->From = $from;
	$mail->FromName = $fromname;
	$mail->AddReplyTo($from,$fromname);
	
	$destinatarios = explode(";",$to);
	foreach($destinatarios as $d) {
		if ($d=="") continue;
		$mail->AddAddress(trim($d),trim($d));
	}
	
	$mail->AddBCC=$bcc;
	$mail->Subject = $subject;
	
	if($mailer=="smtp"){	
		$mail->Host = $SMTPHost;		
		$mail->SMTPAuth = $SMTPAuth;
		if ($SMTPAuth==true) {		
			$mail->Username = $SMTPUser; 
			$mail->Password = $SMTPPass;
			$mail->SMTPSecure = "tls";
		}
	}
	//$mail->SMTPDebug = true;
	if ($html==true) $mail->AltBody = $altbody;
	else $mail->AltBody = $body;	
	$mail->MsgHTML($body);
	/*
	$mail->Body = $body;
	if ($html==true) $mail->AltBody = $altbody;
	else $mail->AltBody = $body;
	*/		
	if($adjuntos!="") {
		$A_adjuntos=explode("\n",$adjuntos);
		foreach($A_adjuntos as $adjunto) {
			if (trim($adjunto)=="") continue;			
			if(!file_exists($adjunto)) $adjunto="files/".$dbname."/".$adjunto;
			if(file_exists($adjunto)) $mail->AddAttachment($adjunto,basename($adjunto));			
		}
	}
	////echo "E-MAIL from=$from to=$to.<br>";
	if(!$mail->Send()) echo "Error al enviar E-MAIL de $from para $to.<br>";

	//$exito = $mail->Send();
	//if (!$exito) return ($mail->ErrorInfo);
 
	return "";
}
?>
