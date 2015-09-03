<?php
if (eregi(basename(__FILE__), $PHP_SELF))  die ("Security Error ...");

//--------------------------------------------------------------------------------------------------------------
function F_SendMail($from, $fromname, $to, $bcc, $subject, $body, $altbody="", $adjuntos="", $html=false, $mailer="mail", $SMTPHost="", $SMTPAuth=false, $SMTPUser="", $SMTPPass="") {
	global $dbname;
	if ($dbname=="") $dbname=_DEF_dbname;
	
	include_once("modules/phpmailer5.1/class.phpmailer.php");  
	
	if (!ereg("@",$from)) {
        $from="=?ISO-8859-1?B?".base64_encode($from."  ");
        $from=substr($from,0,strlen($from)-2)."?=";
	}
    $subject="=?ISO-8859-1?B?".base64_encode($subject."  ");
    $subject=substr($subject,0,strlen($subject)-2)."?=";

	$from=str_replace("\r","",$from); $from=str_replace("\n",",",$from);
	$to=str_replace("\r","",$to); $to=str_replace("\n",",",$to);
	
	$mail = new phpmailer();	
	$mail->PluginDir = "modules/phpmailer5.1/";
	$mail->LE="\n";
	$mail->CharSet="ISO-8859-1";
	$mail->Mailer = $mailer;	
	
	$mail->From = $from;
	$mail->FromName = $fromname;
	
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
		}
	}
	if (html==true) $mail->AltBody = $altbody;
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
			if(file_exists($adjunto)) $mail->AddAttachment($adjunto,basename($adjunto));			
		}
	}		
	
	$exito = $mail->Send();
	
	if(!$exito) {
		return ($mail->ErrorInfo);
	}else{
		return ("OK");
	} 
}
?>