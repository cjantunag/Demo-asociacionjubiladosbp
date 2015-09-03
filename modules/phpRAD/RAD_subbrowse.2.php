<?
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}
//if ($func=="new" || $func=="delete" || $func=="insert" || $func=="update" || $func=="error") return;
if ($func=="edit" || $func=="new" || $func=="delete" || $func=="insert" || $func=="update" || $func=="error") return;
$TMP="";
global $subbrowseSID,$PHPSESSID;
//---------------------------------------------------------------------------
//------------------------- SubBrowse
//---------------------------------------------------------------------------
$TMP_file=$fields[$i]->name;
$TMP_fields=explode(":",$fields[$i]->vdefault);
if (count($TMP_fields)>1) {
    $TMP_field0=$TMP_fields[0];
    $TMP_field1=$TMP_fields[1];
} else {
    $TMP_field0=$fields[$i]->vdefault;
    $TMP_field1=$fields[$i]->vdefault;
}
$TMP_id=$db->Record["$TMP_field0"];

if (($func=="print" || $func=="browse") ||($func=="detail" && $subfunc=="list")) $TMP_func="print";
else $TMP_func="browse";
$TMP_DEF_URL=_DEF_URL_SUBBROWSE;
if ($TMP_DEF_URL=="" || $TMP_DEF_URL=="_DEF_URL_SUBBROWSE") $TMP_DEF_URL=_DEF_URL;
$TMP_New="";
if ($func=="edit" || $func=="new" || $func=="detail") {
	if ($fields[$i]->browsedit && $subfunc!="list") {
    	    $TMP_URLfileNew=_DEF_URL."index.php?V_dir=$V_dir&V_mod=".$TMP_file."&dbname=$dbname&menuoff=x&headeroff=x&footeroff=x&func=new&subfunc=browse&V_roi=".urlencode($TMP_field1."='".$TMP_id."'");
	    $TMP_New="<a ACCESSKEY='N' TITLE='ALT+N' href=\"".$TMP_URLfileNew."\" target=_blank>"._DEF_NLSNew."</a>";
	}
	$TMP.= "<tr><td colspan=2 class=detailtit><table width=100% cellpadding=0 cellspacing=0 border=0><tr><td width=15%> </td><td width=70% align=center><b>".$fields[$i]->title;
	$TMP.= "</b></td><td width=15% align=right>".$TMP_New." </td></tr></table>";
} else $TMP.= "<td>";


session_write_close();

$TMP_URLfile=$TMP_DEF_URL."index.php?V_dir=$V_dir&V_mod=".$TMP_file."&dbname=$dbname&V_lap=".urlencode($V_lap)."&menuoff=x&headeroff=x&footeroff=x&subbrowseSID=".$PHPSESSID."&func=".$TMP_func."&V_roi=".urlencode($TMP_field1."='".$TMP_id."'");
$TMP_file = fopen($TMP_URLfile, "r");
if (!$TMP_file) {
	$TMP.= "Error al leer : ".$TMP_URLfile;
	return $TMP;
}
$TMP_content = "";
while (!feof($TMP_file)) {
	$TMP_line = fgets($TMP_file, 512000);
	$TMP_content = $TMP_content.$TMP_line;
}
fclose($TMP_file); 

if (_DEF_URL_SUBBROWSE!=_DEF_URL && _DEF_URL_SUBBROWSE!="") $TMP_content=str_replace(_DEF_URL_SUBBROWSE,_DEF_URL,$TMP_content);
$TMP_content = str_replace("RAD_OpenW(","RAD_OpenW".$i."(",$TMP_content);
$TMP_content = str_replace("jsnull(","jsnull".$i."(",$TMP_content);
$TMP_content = str_replace("openW(","openW".$i."(",$TMP_content);
$TMP_content = str_replace("setText(","setText".$i."(",$TMP_content);
$TMP_content = str_replace("sel(","sel".$i."(",$TMP_content);
$TMP_content = str_replace("selm(","selm".$i."(",$TMP_content);
$TMP_content = str_replace("delereg(","delereg".$i."(",$TMP_content);

$TMP_content = str_replace("</body>","",$TMP_content);
$TMP_content = str_replace("</BODY>","",$TMP_content);
$TMP_content = str_replace("</html>","",$TMP_content);
$TMP_content = str_replace("</HTML>","",$TMP_content);
$TMP_content = str_replace("HREF=\"/","TARGET=_blank HREF=\"/",$TMP_content);
$TMP_content = str_replace("menuoff=x&orderby","menuoff=&subfunc=browse&orderby",$TMP_content);
$TMP_content = str_replace("</FORM","<input type=hidden name=subfunc value='browse'></FORM",$TMP_content);
$TMP_content = str_replace("<FORM","<FORM target=_blank",$TMP_content);
$TMP.= $TMP_content;
$TMP_content="";

$TMP.= "</td>";
if ($func=="edit" || $func=="new" || $func=="detail") $TMP.= "</tr>";
return $TMP;
?>
