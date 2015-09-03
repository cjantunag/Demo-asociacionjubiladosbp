<?php
/////////////////////////////////////////////////////////////////////////////////////////
function RAD_mailSMTP($from, $to, $subject, $message, $headers) {

/*  your configuration here  */
$smtpServer = _DEF_SMTP; //ip accepted as well
$username = _DEF_SMTP_USER; //the login for your smtp
$password = _DEF_SMTP_PASS; //the pass for your smtp

$port = "25"; // should be 25 by default
$timeout = "30"; //typical timeout. try 45 for slow servers
$localhost = "127.0.0.1"; //this seems to work always
$newLine = "\r\n"; //var just for nelines in MS
$secure = 0; //change to 1 if you need a secure connect
 
//connect to the host and port
$smtpConnect = fsockopen($smtpServer, $port, $errno, $errstr, $timeout);
$smtpResponse = fgets($smtpConnect, 4096);
if(empty($smtpConnect)) {
   $output = "Failed to connect: $smtpResponse";
   return $output;
} else {
   $logArray['connection'] = "Connected to: $smtpResponse";
}

//say HELO to our little friend
fputs($smtpConnect, "HELO $localhost". $newLine);
$smtpResponse = fgets($smtpConnect, 4096);
$logArray['heloresponse'] = "$smtpResponse";
//start a tls session if needed
if($secure) {
   fputs($smtpConnect, "STARTTLS". $newLine);
   $smtpResponse = fgets($smtpConnect, 4096);
   $logArray['tlsresponse'] = "$smtpResponse";
   //you have to say HELO again after TLS is started
   fputs($smtpConnect, "HELO $localhost". $newLine);
   $smtpResponse = fgets($smtpConnect, 4096);
   $logArray['heloresponse2'] = "$smtpResponse";
}

//request for auth login
fputs($smtpConnect,"AUTH LOGIN" . $newLine);
$smtpResponse = fgets($smtpConnect, 4096);
$logArray['authrequest'] = "$smtpResponse";

//send the username
fputs($smtpConnect, base64_encode($username) . $newLine);
$smtpResponse = fgets($smtpConnect, 4096);
$logArray['authusername'] = "$smtpResponse";

//send the password
fputs($smtpConnect, base64_encode($password) . $newLine);
$smtpResponse = fgets($smtpConnect, 4096);
$logArray['authpassword'] = "$smtpResponse";

//email from
fputs($smtpConnect, "MAIL FROM: $from" . $newLine);
$smtpResponse = fgets($smtpConnect, 4096);
$logArray['mailfromresponse'] = "$smtpResponse";

//email to
fputs($smtpConnect, "RCPT TO: $to" . $newLine);
$smtpResponse = fgets($smtpConnect, 4096);
$logArray['mailtoresponse'] = "$smtpResponse";

//the email
fputs($smtpConnect, "DATA" . $newLine);
$smtpResponse = fgets($smtpConnect, 4096);
$logArray['data1response'] = "$smtpResponse";

//construct headers
if ($headers=="") {
	$headers = "MIME-Version: 1.0" . $newLine;
	$headers .= "Content-type: text/html; charset=iso-8859-1" . $newLine;
	$headers .= "To: $to" . $newLine;
	$headers .= "From: $from" . $newLine;
}
$subject=html_entity_decode($subject, ENT_COMPAT, "ISO-8859-1");
//$subject="=?utf-8?B?".base64_encode(utf8_decode($subject)."  ");
////if(mb_detect_encoding($subject)=="UTF-8") $subject=utf8_decode($subject);
//if(ereg(chr(195),$subject)) $subject=utf8_decode($subject);
$subject="=?iso-8859-1?B?".base64_encode($subject."  ");
$subject=substr($subject,0,strlen($subject)-2)."?=";

//observe the . after the newline, it signals the end of message
//fputs($smtpConnect, "To: $to\r\nFrom: $from\r\nSubject: $subject\r\n$headers\r\n\r\n$message\r\n.\r\n");
fputs($smtpConnect, "To: $to\r\nSubject: $subject\r\n$headers\r\n\r\n$message\r\n.\r\n");
$smtpResponse = fgets($smtpConnect, 4096);
$logArray['data2response'] = "$smtpResponse";

// say goodbye
fputs($smtpConnect,"QUIT" . $newLine);
$smtpResponse = fgets($smtpConnect, 4096);
$logArray['quitresponse'] = "$smtpResponse";
$logArray['quitcode'] = substr($smtpResponse,0,3);
fclose($smtpConnect);
//a return value of 221 in $retVal["quitcode"] is a success
return($logArray);
}
?>
