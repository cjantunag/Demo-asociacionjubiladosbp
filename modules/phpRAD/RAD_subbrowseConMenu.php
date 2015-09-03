<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

//if ($func=="new" || $func=="delete" || $func=="insert" || $func=="update" || $func=="error") return;
if ($func=="edit" || $func=="new" || $func=="delete" || $func=="insert" || $func=="update" || $func=="error") return;
$TMP="";
global $subbrowseSID,$PHPSESSID, $RAD_subbrowseCont, $PHP_SELF;
//---------------------------------------------------------------------------
//------------------------- SubBrowse
//---------------------------------------------------------------------------
$TMP_A=explode("/",$fields[$i]->name);
if (count($TMP_A)>1) {
	$TMP_file=$TMP_A[1];
	$TMP_dir=$TMP_A[0];
} else {
	$TMP_file=$fields[$i]->name;
	$TMP_dir=$V_dir;
}
$TMP_width=$fields[$i]->length;
$TMP_height=$fields[$i]->ilength;
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
////    	    $TMP_URLfileNew=$TMP_DEF_URL."index.php?V_dir=$TMP_dir&V_mod=".$TMP_file."&menuoff=x&headeroff=x&footeroff=x&func=new&subfunc=browse&V_roi=".urlencode($TMP_field1."='".urlencode($TMP_id)."'").$SESSION_SID;
////    	    $TMP_URLfileNew=$PHP_SELF."?V_dir=$TMP_dir&V_mod=".$TMP_file."&menuoff=x&headeroff=x&footeroff=x&func=new&subfunc=browse&V_roi=".urlencode($TMP_field1."='".urlencode($TMP_id)."'").$SESSION_SID;
    	    $TMP_URLfileNew=$TMP_DEF_URL."index.php?V_dir=$TMP_dir&V_mod=".$TMP_file."&menuoff=x&headeroff=x&footeroff=x&func=new&subfunc=browse&V_roi=".urlencode($TMP_field1."='".$TMP_id."'").$SESSION_SID;
            $TMP_URLfileNew=$PHP_SELF."?V_dir=$TMP_dir&V_mod=".$TMP_file."&headeroff=x&footeroff=x&func=new&subfunc=browse&V_roi=".urlencode($TMP_field1."='".$TMP_id."'").$SESSION_SID;
	    if (!isset($RAD_subbrowseCont)) $RAD_subbrowseCont=0;
	    else $RAD_subbrowseCont++;
	    if ($RAD_subbrowseCont==0) $TMP_accesskey="U";
	    else if ($RAD_subbrowseCont==1) $TMP_accesskey="V";
	    else if ($RAD_subbrowseCont==2) $TMP_accesskey="W";
	    else if ($RAD_subbrowseCont==3) $TMP_accesskey="X";
	    else if ($RAD_subbrowseCont==4) $TMP_accesskey="Y";
	    else $TMP_accesskey="Z";
	    if ($TMP_width>49 && $TMP_width<1201 && $TMP_height>49 && $TMP_height<1201) 
		$TMP_New="<a ACCESSKEY='".$TMP_accesskey."' TITLE='ALT+".$TMP_accesskey."' href='javascript:RAD_OpenW(\"".$TMP_URLfileNew."\",$TMP_width,$TMP_height);'><img border=0 src='images/new.gif' alt='"._DEF_NLSStringNew."'> "._DEF_NLSStringNew." </a>";
	    else $TMP_New="<a ACCESSKEY='".$TMP_accesskey."' TITLE='ALT+".$TMP_accesskey."' href=\"".$TMP_URLfileNew."\" target=_blank><img border=0 src='images/new.gif' alt='"._DEF_NLSStringNew."'> "._DEF_NLSStringNew." </a>";
	}
	$TMP_colspan=2;
	if ($func=="edit" && $V_colsedit>1) $TMP_colspan=$V_colsedit*2;
	if ($func=="detail" && $V_colsdetail>1) $TMP_colspan=$V_colsdetail*2;
	if (is_admin() || is_modulepermitted("", "phpGenRAD", "index")) $TMP_linkadm="<a target=_blank href='$PHP_SELF?&V_dir=phpGenRAD&V_mod=indexRAD&V_submod=genform&modulesdir=$TMP_dir&project_file=".$TMP_file.".prj.php$SESSION_SID'>&nbsp;&nbsp;</a>";
	else if (is_modulepermitted("", "phpGenRAD", "minigenform")) $TMP_linkadm="<a target=_blank href='$PHP_SELF?&V_dir=phpGenRAD&V_mod=minigenform&modulesdir=$TMP_dir&project_file=".$TMP_file.".prj.php$SESSION_SID'>&nbsp;&nbsp;</a>";
	else $TMP_linkadm="";
	$TMP.= "<tr><td colspan=$TMP_colspan class=detailtit><table width=100% cellpadding=0 cellspacing=0 border=0><tr><td width=15%> </td><td width=70% align=center><b>".$fields[$i]->title.$TMP_linkadm;
	$TMP.= "</b></td><td width=15% align=right>".$TMP_New." </td></tr></table>";
} else $TMP.= "<td>";


session_write_close();

$TMP_URLfile=$TMP_DEF_URL."index.php?V_dir=$TMP_dir&V_mod=".$TMP_file."&V_lap=".urlencode($V_lap)."&menuoff=x&headeroff=x&footeroff=x&subbrowseSID=".$PHPSESSID."&func=".$TMP_func."&RAD_width=".$TMP_width."&RAD_height=".$TMP_height."&V_roi=".urlencode($TMP_field1."='".$TMP_id."'");
if ($dbname!="") $TMP_URLfile.="&dbname=".$dbname;
if ($SQL_DEBUG!="") $TMP_URLfile.="&SQL_DEBUG=".$SQL_DEBUG;
$TMP_file = fopen($TMP_URLfile, "r");
if (!$TMP_file) {
	$TMP.= "Error al leer : ".$TMP_URLfile;
	RAD_logError("ERR: ".$TMP);
	return $TMP;
}
$TMP_content = "";
while (!feof($TMP_file)) {
	$TMP_line = fgets($TMP_file, 512000);
	$TMP_content = $TMP_content.$TMP_line;
}
fclose($TMP_file); 

if (_DEF_URL_SUBBROWSE!=_DEF_URL && _DEF_URL_SUBBROWSE!="") $TMP_content=str_replace(_DEF_URL_SUBBROWSE,_DEF_URL,$TMP_content);
////if ($PHP_SELF!="/index.php") $TMP_content=str_replace("/index.php",$PHP_SELF,$TMP_content);
$TMP_content = str_replace("delereg","delereg".$RAD_subbrowseCont."",$TMP_content);
$TMP_content = str_replace("saveregs(","saveregs".$RAD_subbrowseCont."(",$TMP_content);
$TMP_content = str_replace("document.F.","document.F".$RAD_subbrowseCont.".",$TMP_content);
$TMP_content = str_replace("document.forms.F.","document.forms.F".$RAD_subbrowseCont.".",$TMP_content);
$TMP_content = str_replace(" NAME=F "," NAME=F".$RAD_subbrowseCont." ",$TMP_content);
//$TMP_content = str_replace("RAD_OpenW(","RAD_OpenW".$i."(",$TMP_content);
//$TMP_content = str_replace("jsnull(","jsnull".$i."(",$TMP_content);
$TMP_content = str_replace("popUpCalendar(this,F","popUpCalendar(this,F".$RAD_subbrowseCont,$TMP_content);
$TMP_content = str_replace("openW(","openW".$i."(",$TMP_content);
$TMP_content = str_replace("setText(","setText".$i."(",$TMP_content);
$TMP_content = str_replace("next(","next".$i."(",$TMP_content);
$TMP_content = str_replace("sel(","sel".$i."(",$TMP_content);
$TMP_content = str_replace("selm(","selm".$i."(",$TMP_content);
$TMP_content = str_replace("delereg(","delereg".$i."(",$TMP_content);

$TMP_content = str_replace("</body>","",$TMP_content);
$TMP_content = str_replace("</BODY>","",$TMP_content);
$TMP_content = str_replace("</html>","",$TMP_content);
$TMP_content = str_replace("</HTML>","",$TMP_content);
// $TMP_content = str_replace("</FORM","<input type=hidden name=subfunc value='browse'></FORM",$TMP_content);
// $TMP_content = str_replace("<FORM","<FORM target=_blank",$TMP_content);
$TMP_content = str_replace("HREF=\"","TARGET=_blank HREF=\"",$TMP_content);
$TMP_content = str_replace("menuoff=x&orderby","menuoff=&subfunc=browse&orderby",$TMP_content);
$TMP.= $TMP_content;
$TMP_content="";
if (eregi("PDA",getSessionVar("SESSION_theme"))) $TMP = eregi_replace(" target=_blank","",$TMP);

$TMP.= "</td>";
if ($func=="edit" || $func=="new" || $func=="detail") $TMP.= "</tr>";
return $TMP;
?>
