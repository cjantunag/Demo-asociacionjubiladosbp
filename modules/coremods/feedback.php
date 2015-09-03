<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: ../../index.php");
    die();
}
include("header.php");
//$module_name = basename(dirname(__FILE__));
//get_lang($module_name);

if ($_FILES["CV"]["tmp_name"]!="") $CV_name=$_FILES["CV"]["tmp_name"];
if ($_FILES["sender_file"]["tmp_name"]!="") {
	$sender_file=$_FILES["sender_file"]["tmp_name"];
	$sender_file_name=$_FILES["sender_file"]["name"];
}
if ($_FILES["file"]["tmp_name"]!="") {
	$sender_file=$_FILES["file"]["tmp_name"];
	$sender_file_name=$_FILES["file"]["name"];
}
if ($sender_file!="" && $op=="") $op="send";

if ($receiver_email!="") $subject = _DEF_SITENAME.". "._DEF_NLSReccomendUs;
else if ($CV_name!="") $subject = _DEF_SITENAME.". "._DEF_NLSSendCV;
else $subject = _DEF_SITENAME.". "._DEF_NLSFeedback;

$form_block = "<center><table border=0><tr><td colspan=2 align=center><b>"._DEF_SITENAME.": "._DEF_NLSFeedback."</b></td></tr>
    <FORM METHOD=post ENCTYPE='multipart/form-data' ACTION=\"index.php\"><input type=hidden name=V_dir value=coremods><input type=hidden name=V_mod value=feedback>
    <tr><td nowrap align=right><b>"._DEF_NLSName." : </b></td><td><INPUT type=text NAME=sender_name VALUE='$sender_name' SIZE=30></td></tr>";
    
 if ($conempresa!='') {
 	$form_block .= "<tr><td nowrap align=right><b>"._DEF_NLSEnterprise." : </b></td><td><INPUT type=text NAME=sender_enterprise VALUE='$sender_enterprise' SIZE=30></td></tr>
    <tr><td nowrap align=right><b>"._DEF_NLSPosition." : </b></td><td><INPUT type=text NAME=sender_position VALUE='$sender_position' SIZE=30></td></tr>";
}
    
 $form_block.="
    <tr><td nowrap align=right><b>"._DEF_NLSPhone." : </b></td><td><INPUT type=text NAME=sender_phone VALUE='$sender_phone' SIZE=30></td></tr>
    <tr><td nowrap align=right><b>"._DEF_NLSMail." : </b></td><td><INPUT type=text NAME=sender_email VALUE='$sender_email' SIZE=30></td></tr>
<tr><td nowrap align=right><b>"._DEF_NLSFile." : </b></td><td><INPUT type=file NAME=sender_file VALUE='$sender_file'></td></tr>
<tr><td nowrap align=right valign=top><b>"._DEF_NLSMessage.": </b></td><td><TEXTAREA NAME=message COLS=60 ROWS=10 WRAP=virtual>$message</TEXTAREA></td></tr>
    <tr><td colspan=2 align=center><INPUT type=hidden name=op value='send'><INPUT TYPE=submit NAME=submit VALUE='"._DEF_NLSSendFeedback."'></FORM></td></tr></table>";

//$form_block = "<script type=text/javascript>\nwindow.history.back();\n</script>\n";

OpenTable();
if ($op == "mailform") {
	$to = _DEF_ADMINMAIL;
//	if ($email!="") $mailheaders = "From: ".$email." <".$email."> \n";
	if ($email!="") $mailheaders = "From: ".$email." \n";
	else $mailheaders = "From: ".$to." \n";
	if ($receiver_email!="") $to=$receiver_email;

	if ($subject=="") $subject="MailForm "._DEF_URL;

	foreach ($_REQUEST as $TMP_key=>$TMP_val) {
		if ($TMP_key=="V_dir"||$TMP_key=="V_mod"||$TMP_key=="op"||$TMP_key=="submit"||$TMP_key=="blocksoff") continue;
		$msg.=$TMP_key." = ".$TMP_val.".\r\n";
/*
		if (is_array($TMP_val)) { foreach ($TMP_val as $TMP_key2=>$TMP_val2) $msg.=$TMP_key."[".$TMP_key2."]=".$TMP_val."\r\n";
		} else if ($TMP_val!="") { $msg.=$TMP_key."=".$TMP_val."\r\n";
		} else { $msg.=$TMP_key."=\r\n"; }
*/
	}
	if ($_REQUEST["tabla"]=="articulos" && $_REQUEST["idreg"]!="") {
		$TMP_URLfile=_DEF_URL_SUBBROWSE.'index.php?V_dir=contents&V_mod=articulos&blocksoff=x&id='.$_REQUEST["idreg"];
		$TMP_URLfile.='&menuoff=x&headeroff=x&footeroff=x&subbrowseSID='.$PHPSESSID;
		$TMP_contenido=RAD_openURL($TMP_URLfile);
//		$TMP_result=sql_query("SELECT * FROM articulos WHERE id='".$_REQUEST["idreg"]."'",$RAD_dbi);
//		$TMP_row=sql_fetch_array($TMP_result);
//		$TMP_contenido=$TMP_row["contenido"];
		if ($TMP_contenido!="") {
			$_REQUEST["REFERER"]=getenv("HTTP_REFERER");
			foreach ($_REQUEST as $TMP_key=>$TMP_val) {
				if ($TMP_key=="V_dir"||$TMP_key=="V_mod"||$TMP_key=="op"||$TMP_key=="submit") continue;
				$TMP_contenido=eregi_replace("<textarea name=".$TMP_key,"<pre>".$TMP_val."</pre><! textarea",$TMP_contenido);
				$TMP_contenido=eregi_replace("name=".$TMP_key,"name=".$TMP_key." value='$TMP_val'",$TMP_contenido);
				$TMP_contenido=eregi_replace("name='".$TMP_key."'","name=".$TMP_key." value='$TMP_val'",$TMP_contenido);
				$TMP_contenido=eregi_replace("name=\"".$TMP_key."\"","name=".$TMP_key." value='$TMP_val'",$TMP_contenido);
			}
			$mailheaders.='MIME-Version: 1.0' . "\r\n";
			$mailheaders.='Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$msg=$TMP_contenido;
		}
	}

	mail($to, $subject, $msg, $mailheaders);
	echo "<b><center>"._DEF_NLSMessageSent."<BR>"._DEF_NLSThanks."</center></b>";
} else if ($op != "send") {
    echo $form_block;
} else {
    if ($sender_name == "") {
	$name_err = "<center><b><i>"._DEF_NLSName." "._DEF_NLSMandatory."</i></b></center><br>";
	$send = "no";
    } 
    if ($sender_email == "") {
	$email_err = "<center><b><i>"._DEF_NLSMail." "._DEF_NLSMandatory."</i></b></center><br>";
	$send = "no";
    } 
    if ($message == "") {
    	$message_err = "<center><b><i>"._DEF_NLSMessage." "._DEF_NLSMandatory."</i></b></center><br>";
	$send = "no";
    } 
    if ($send != "no") {
	if ($CV_name!="") {
		$TMP_fich=RAD_nameSecure($CV_name);
		list($TMP_fich,$TMP_fichbase)=RAD_nameDownload($TMP_fich);
		copy($CV,$TMP_fich);
	}
	if ($sender_file!="") {
		$TMP_fich=RAD_nameSecure($sender_file_name);
		list($TMP_fich,$TMP_fichbase)=RAD_nameDownload($TMP_fich);
		copy($sender_file,$TMP_fich);
	}
	$msg = _DEF_SITENAME."\n\n";
	$msg .= _DEF_NLSName." : $sender_name\n";
	if (trim($sender_enterprise)!='') $msg.= _DEF_NLSEnterprise.": ".$sender_enterprise."\n";
	if (trim($sender_position)!='') $msg.= _DEF_NLSPosition.": ".$sender_position."\n";
	$msg .= _DEF_NLSPhone." : $sender_phone\n";
	$msg .= _DEF_NLSMail." : $sender_email\n";
	$msg .= "$message\n\n";
	if ($CV_name!="") $msg .="\nCV recibido en <a href='"._DEF_URL.$TMP_fich."'> "._DEF_URL.$TMP_fich." </a>.\n\n";
	if ($sender_file!="") $msg .="\nFichero recibido en <a href='"._DEF_URL.$TMP_fich."'> "._DEF_URL.$TMP_fich." </a>.\n\n";
	$to = _DEF_ADMINMAIL;
	if ($receiver_email!="") $to=$receiver_email;
//	$mailheaders = "From: "._DEF_URL." <> \n";
	$mailheaders = "From: ".$sender_email." <> \n";
	$mailheaders .= "Reply-To: $sender_email\n\n";
	mail($to, $subject, $msg, $mailheaders);
	//echo $msg;
	if ($receiver_email!="") echo "<p><center>"._DEF_NLSMessageSent."</center></p>";
	else if ($CV_name!="") echo "<p><center>"._DEF_NLSCVSent."</center></p>";
	else echo "<p><center>"._DEF_NLSMessageSent."</center></p>";
	echo "<p><center>"._DEF_NLSThanks."</center></p>";
    } elseif ($send == "no") {
	OpenTable();
	echo "$name_err";
	echo "$email_err";
	echo "$message_err";
	CloseTable();
	echo "<br><br>";
	echo "$form_block";  
    } 
}

CloseTable();   
include("footer.php");

?>
