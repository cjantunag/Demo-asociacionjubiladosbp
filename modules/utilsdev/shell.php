<?
if (eregi(basename(__FILE__), $PHP_SELF)) {
	Header("Location: ../../index.php");
	die();
}

die("No Disponible en este servidor");

include_once("header.php");

if ($V_dir!="") {
	$URLRAD="V_dir=$V_dir&";
	$FORMRAD="<input type=hidden name='V_dir' value='$V_dir'>";
}    
if ($V_mod!="") {
	$URLRAD.="V_mod=$V_mod&";
	$FORMRAD.="<input type=hidden name='V_mod' value='$V_mod'>";
}
$URLRAD.=$SESSION_SID;
$FORMRAD.="<input type=hidden name='PHPSESSID' value='$PHPSESSID'>";

//$html_dir = exec("pwd");
$html_dir = getcwd();

/* First we check if there has been asked for a working directory. */
if ($change_dir!=$work_dir) {
	$work_dir = $change_dir;
	$command="";
}
if (isset($work_dir)) {
	chdir($work_dir);
//	$work_dir = exec("pwd");
	$work_dir = getcwd();
//	$command="";
} else {
	chdir($DOCUMENT_ROOT);
	$work_dir = $DOCUMENT_ROOT;
}

echo "<b>PHP Shell</b><br><form name='myform' action=\"$PHP_SELF\" method='get'>\n";
echo $FORMRAD;

$work_dir_splitted = explode("/", substr($work_dir, 1));
echo _DIRECTORY." <a href=\"$PHP_SELF?".$URLRAD."work_dir=" . urlencode($url) . "/&command=" . urlencode($command) . "\">root</a>/";
if ($work_dir_splitted[0] == "") {
	$work_dir = "/";  /* Root directory. */
} else {
	for ($i = 0; $i < count($work_dir_splitted); $i++) {
		$url .= "/".$work_dir_splitted[$i];
		echo "<a href=\"$PHP_SELF?".$URLRAD."work_dir=" . urlencode($url) . "&command=" . urlencode($command) . "\">$work_dir_splitted[$i]</a>/";
	}
}

echo "<input type=hidden name=work_dir value='$work_dir'>\n";
echo "&nbsp;&nbsp; "._SUBDIRECTORY.": <select name='change_dir' onChange='this.form.submit()'>\n";

$dir_handle = opendir($work_dir);
echo "<option value=\"$work_dir\" selected></option>\n";
/* Run through all the files and directories to find the dirs. */
while ($dir = readdir($dir_handle)) {
	if (is_dir($dir)) {
		if ($dir == ".") {
		} elseif ($dir == "..") {
		/* We have found the parent dir. We must be carefull if the parent directory is the root directory (/). */
			if (strlen($work_dir) == 1) {
			/* work_dir is only 1 charecter - it can only be / */
			} elseif (strrpos($work_dir, "/") == 0) {
			/* The last / in work_dir were the first charecter. This means that we have a top-level directory eg. /bin or /home etc... */
				echo "<option value=\"/\">Directorio Raiz</option>\n";
			} else {
			/* We do a little bit of string-manipulation to find the parent directory... Trust me - it works :-) */
				echo "<option value=\"". strrev(substr(strstr(strrev($work_dir), "/"), 1)) ."\">Directorio Padre</option>\n";
			}
		} else {
			if ($work_dir == "/") {
				$DIRS[$dir]="<option value=\"$work_dir$dir\">$dir</option>\n";
			} else {
				$DIRS[$dir]="<option value=\"$work_dir/$dir\">$dir</option>\n";
			}
		}
	}
}
closedir($dir_handle);
if (count($DIRS)>0) {
	sort($DIRS,SORT_STRING);
	for ($ki=0; $ki<count($DIRS); $ki++) echo $DIRS[$ki];
}
echo "</select><br>"._COMMAND." : <input type=text name='command' size=40 value='$command'> &nbsp; ";
echo _SHOW." <code>stderr</code> <input type=checkbox name=stderr> <input type=submit value='"._EXECUTE."'><br>";
echo _OUTPUT.":<br><textarea cols=80 rows=20 readonly>";

if ($command) {
	if ($stderr) {
		system($command . " 1> /tmp/output.txt 2>&1; cat /tmp/output.txt; rm /tmp/output.txt");
	} else {
		system($command);
	}
}
echo "</textarea></form>";

chdir($html_dir);

if ($html_dir!="") include_once($html_dir."/footer.php");
else include_once("footer.php");
?>
