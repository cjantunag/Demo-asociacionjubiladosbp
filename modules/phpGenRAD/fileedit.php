<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

include_once ("modules/".$V_dir."/defaults.php");

if (!isset($funcion)) $funcion="";
//******************* Save
if ($funcion == "SAVE" || $funcion==_DEF_NLSSave) {
	$fp = fopen($fileToEdit,"w");
	chmod($fileToEdit, $cmask);
	fputs($fp,$to_edit);
	fclose($fp);
	$path = dirname($fileToEdit);
	if ($subfunc == "browse") {
		echo "\n<script>\ncsave=confirm(\""._DEF_NLSSaved.". Desea continuar editando el fichero?\");\n".
		     "if (csave) window.history.go(-1);\nelse window.close();\n</script>\n";
		return;
	} else {
		echo "\n<script>\ndocument.writeln(\"<html><body bgcolor=white></body></html>\");\n".
		     "alert(\""._DEF_NLSFile." "._DEF_NLSSaved."\");\nwindow.history.go(-1);\n".
		     "</script>\n";
	}
}

//******************* Cancel
if ($funcion == "CANCEL" || $funcion==_DEF_NLSCancelString) {
	if ($subfunc == "browse") {
		echo "\n<script>\nwindow.close();\n</script>\n";
		return;
	}
?>
<SCRIPT LANGUAGE="JavaScript">
	document.writeln("<html><body bgcolor=white></body></html>");
        alert("<?=_DEF_NLSStringEdit." "._DEF_NLSCanceled?>.");
//	document.location="index.php";
	window.history.go(-1);
</SCRIPT>
<?
}

include_once ("header.php");
OpenTable();

//******************* Edit
if (empty($funcion)) {
	$fileToEdit = $directory."/".$filename;
	if (!file_exists($fileToEdit)) {
	    $TMP_error=" <font style='font-weight: bold; color:red;'>("._DEF_NLSStringNew.")</font>";
//	    echo "<A HREF=javascript:window.history.back()>"._DEF_NLSBack."</A>";
//		$fp = fopen($fileToEdit,"w");
//		fclose($fp);
//	    exit;
	}

	echo "<FORM METHOD=POST><IMG BORDER=0 SRC=\"modules/".$V_dir."/logo.gif\" ALT=\"RAD\"> <b>"._DEF_NLSStringEdit." "._DEF_NLSFile." :</b> <INPUT SIZE=60 NAME=fileToEdit VALUE='$fileToEdit'> ";
	echo "<INPUT TYPE=SUBMIT ACCESSKEY='S' TITLE='ALT+S' NAME=funcion VALUE='"._DEF_NLSSave."'> <INPUT TYPE=SUBMIT ACCESSKEY='Q' TITLE='ALT+Q' NAME=funcion VALUE='"._DEF_NLSCancelString."'>";
	echo "<center>".$TMP_error."<HR noshade size=1>";
	echo "<INPUT TYPE=HIDDEN NAME=PHPSESSID VALUE='$PHPSESSID'>";
	echo "<INPUT TYPE=HIDDEN NAME=V_mod VALUE='$V_mod'>";
	echo "<INPUT TYPE=HIDDEN NAME=V_submod VALUE='$V_submod'>";
	echo "<INPUT TYPE=HIDDEN NAME=V_dir VALUE='$V_dir'>\n";
	echo "<TEXTAREA NAME=to_edit COLS=135 ROWS=36 wrap='virtual' style='font-family:courier; font-size:11px;'>";
	$len = 0;
	if (file_exists($fileToEdit)) {
		$fp = fopen($fileToEdit,"r");
		chmod($fileToEdit, $cmask);
		while (!feof($fp)) {
			$r = fgets($fp,9999);
			$len += strlen($r);
//			$r = str_replace("&", "&#38;", $r);
			echo htmlspecialchars($r);
		}
		fclose($fp);
	}
	if ($len == 0 && substr($fileToEdit,strlen($fileToEdit)-3)=="php") 
		echo "<?php
if (eregi(basename(__FILE__), \$PHP_SELF)) die (\"Security Error ...\");



return \"\";
?>
";
	echo "</TEXTAREA><BR>";
	echo "</FORM></p></center>";
    }

CloseTable();
include_once ("footer.php"); 

?>
