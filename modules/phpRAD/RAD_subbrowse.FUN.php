<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();

}
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
$TMP_A2=explode(":",$TMP_file);
if (count($TMP_A2)>1) {
	$TMP_idmod=$TMP_A2[0];
	$TMP_file=$TMP_A2[1];
}
else $TMP_idmod="";
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
$TMP_fields=explode(",",$TMP_field0);
$TMP_V_roi2="";
if (count($TMP_fields)>1) {
    $TMP_field0=$TMP_fields[0];
    $TMP_field02=$TMP_fields[1];
    $TMP_fields=explode(",",$TMP_field1);
    if (count($TMP_fields)>1) {
	$TMP_field1=$TMP_fields[0];
	$TMP_field12=$TMP_fields[1];
    } else {
	$TMP_field1=$TMP_field0;
	$TMP_field12=$TMP_field02;
    }
    $TMP_id2=$db->Record["$TMP_field02"];
    $TMP_V_roi2="&V_roi2=".urlencode($TMP_field12."='".$TMP_id2."'");
}
$TMP_id=$db->Record["$TMP_field0"];

if (($func=="print" || $func=="browse") ||($func=="detail" && $subfunc=="list")) $TMP_func="print";
else $TMP_func="browse";
$TMP_DEF_URL=_DEF_URL_SUBBROWSE;
if ($TMP_DEF_URL=="" || $TMP_DEF_URL=="_DEF_URL_SUBBROWSE") $TMP_DEF_URL=_DEF_URL;
$TMP_New="";
if (!isset($RAD_subbrowseCont)) $RAD_subbrowseCont=0;
else $RAD_subbrowseCont++;
if ($func=="edit" || $func=="new" || $func=="detail") {
	if ($fields[$i]->browsedit && $subfunc!="list") { 
////    	    $TMP_URLfileNew=$TMP_DEF_URL."index.php?V_dir=$TMP_dir&V_mod=".$TMP_file."&menuoff=x&headeroff=x&footeroff=x&func=new&subfunc=browse&V_roi=".urlencode($TMP_field1."='".urlencode($TMP_id)."'").$SESSION_SID.$TMP_V_roi2;
////    	    $TMP_URLfileNew=$PHP_SELF."?V_dir=$TMP_dir&V_mod=".$TMP_file."&menuoff=x&headeroff=x&footeroff=x&func=new&subfunc=browse&V_roi=".urlencode($TMP_field1."='".urlencode($TMP_id)."'").$SESSION_SID.$TMP_V_roi2;
    	    $TMP_URLfileNew=$TMP_DEF_URL."index.php?V_dir=$TMP_dir&V_mod=".$TMP_file."&V_idmod=$TMP_idmod&menuoff=x&headeroff=x&footeroff=x&func=new&subfunc=browse&V_roi=".urlencode($TMP_field1."='".$TMP_id."'").$SESSION_SID.$TMP_V_roi2;
    	    $TMP_URLfileNew=$PHP_SELF."?V_dir=$TMP_dir&V_mod=".$TMP_file."&V_idmod=$TMP_idmod&menuoff=x&headeroff=x&footeroff=x&func=new&subfunc=browse&V_roi=".urlencode($TMP_field1."='".$TMP_id."'").$SESSION_SID.$TMP_V_roi2;
	    if ($dbname!="") $TMP_URLfileNew.="&dbname=".$dbname;
	    if ($SQL_DEBUG!="") $TMP_URLfileNew.="&SQL_DEBUG=".$SQL_DEBUG;

	    if ($RAD_subbrowseCont==0) $TMP_accesskey="U";
	    else if ($RAD_subbrowseCont==1) $TMP_accesskey="V";
	    else if ($RAD_subbrowseCont==2) $TMP_accesskey="W";
	    else if ($RAD_subbrowseCont==3) $TMP_accesskey="X";
	    else if ($RAD_subbrowseCont==4) $TMP_accesskey="Y";
	    else $TMP_accesskey="Z";
/*
	    if ($TMP_width>49 && $TMP_width<1201 && $TMP_height>49 && $TMP_height<1201 && _DEF_POPUP_MARGIN!="SUBMODAL") 
		$TMP_New="<a class=browse ACCESSKEY='".$TMP_accesskey."' TITLE='ALT+".$TMP_accesskey."' href='javascript:RAD_OpenW(\"".$TMP_URLfileNew."\",$TMP_width,$TMP_height);'><img border=0 src='images/new.gif' alt='"._DEF_NLSStringNew."'> "._DEF_NLSStringNew." </a>";
	    else $TMP_New="<a class=browse ACCESSKEY='".$TMP_accesskey."' TITLE='ALT+".$TMP_accesskey."' href=\"".$TMP_URLfileNew."\" target=_blank><img border=0 src='images/new.gif' alt='"._DEF_NLSStringNew."'> "._DEF_NLSStringNew." </a>";
*/
            //Se ha activado el RAD_openW para que cierre y actualice las ventanas que se abren en el subbrowse
            $TMP_New="<a class=browse ACCESSKEY='".$TMP_accesskey."' TITLE='ALT+".$TMP_accesskey."' href='javascript:RAD_OpenW(\"".$TMP_URLfileNew."\",$TMP_width,$TMP_height);'><img border=0 src='images/new.gif' alt='"._DEF_NLSStringNew."'> "._DEF_NLSStringNew." </a>";
	}
	$TMP_colspan=2;
	if ($func=="edit" && $V_colsedit>1) $TMP_colspan=$V_colsedit*2; 
	if ($func=="detail" && $V_colsdetail>1) $TMP_colspan=$V_colsdetail*2;
	if (is_admin() || is_modulepermitted("", "phpGenRAD", "index")) $TMP_linkadm="<a target=_blank href='$PHP_SELF?&V_dir=phpGenRAD&V_mod=indexRAD&V_submod=genform&modulesdir=$TMP_dir&project_file=".$TMP_file.".prj.php$SESSION_SID'>&nbsp;&nbsp;</a>";
	else if (is_modulepermitted("", "phpGenRAD", "minigenform")) $TMP_linkadm="<a target=_blank href='$PHP_SELF?&V_dir=phpGenRAD&V_mod=minigenform&modulesdir=$TMP_dir&project_file=".$TMP_file.".prj.php$SESSION_SID'>&nbsp;&nbsp;</a>";
	else $TMP_linkadm="";
	$TMP.= "<td colspan=$TMP_colspan class=detailtit>";
	$TMP.= "<table width=100% cellpadding=0 cellspacing=0 border=0 class='borde'><tr>";
	$TMP.="<script>
	function RAD_focusNextField".$RAD_subbrowseCont."(seln) {
 if (seln!='') selFieldName=seln;
 var selFieldName2='';
 if (selFieldName=='') return;
 if (!document.F".$RAD_subbrowseCont.") return;
 eval('tipovar=document.F".$RAD_subbrowseCont.".'+selFieldName+'.type;'); if(tipovar=='text') eval('document.F".$RAD_subbrowseCont.".'+selFieldName+'.blur();');
 for(var i=0;i<document.F".$RAD_subbrowseCont.".elements.length; i++) {
 if (document.F".$RAD_subbrowseCont."[i].name==selFieldName) {
   if (i<(document.F".$RAD_subbrowseCont.".elements.length-1)) if (document.F".$RAD_subbrowseCont."[i+1].name==selFieldName+'_literal') i++;
   if (selFieldName2=='') for (var j=i+1; j<document.F".$RAD_subbrowseCont.".elements.length; j++) { if (document.F".$RAD_subbrowseCont."[j].name.length>0 && document.F".$RAD_subbrowseCont."[j].type!='hidden' && (document.F".$RAD_subbrowseCont."[j].type!='button'||(document.F".$RAD_subbrowseCont."[j].type=='button'&&document.F".$RAD_subbrowseCont."[j].name=='Save'))) { k=j; selFieldName2=document.F".$RAD_subbrowseCont."[j].name; j=document.F".$RAD_subbrowseCont.".elements.length; } }
   if (selFieldName2=='') for (var j=0; j<i; j++) { if (document.F".$RAD_subbrowseCont."[j].name.length>0 && document.F".$RAD_subbrowseCont."[j].type!='hidden' && (document.F".$RAD_subbrowseCont."[j].type!='button'||(document.F".$RAD_subbrowseCont."[j].type=='button'&&document.F".$RAD_subbrowseCont."[j].name=='Save'))) { k=j; selFieldName2=document.F".$RAD_subbrowseCont."[j].name; j=document.F".$RAD_subbrowseCont.".elements.length; } }
   if (selFieldName2=='') for (var j=0; j<i; j++) { if (document.F".$RAD_subbrowseCont."[j].name.length>0 && document.F".$RAD_subbrowseCont."[j].type!='radio' && document.F".$RAD_subbrowseCont."[j].type!='hidden') { k=j; selFieldName2=document.F".$RAD_subbrowseCont."[j].name; j=document.F".$RAD_subbrowseCont.".elements.length; } }
   if (selFieldName2.length>0) if (selFieldName2==selFieldName) selFieldName2='';
   if (selFieldName2.length>0 && document.F".$RAD_subbrowseCont."[k].type=='radio') eval('document.F".$RAD_subbrowseCont.".'+selFieldName2+'[0].focus();');
   else if (selFieldName2.length>0 && document.F".$RAD_subbrowseCont."[k].type=='select-multiple') eval('document.F".$RAD_subbrowseCont.".ID_'+selFieldName2.substring(0,selFieldName2.length-2)+'.focus();');
   else if (selFieldName2.length>0) { eval('document.F".$RAD_subbrowseCont.".'+selFieldName2+'.focus();'); if (document.F".$RAD_subbrowseCont."[k].type=='text') eval('document.F".$RAD_subbrowseCont.".'+selFieldName2+'.select();'); }
   if (selFieldName2.length>0) selFieldName=selFieldName2;
   i=document.F".$RAD_subbrowseCont.".elements.length;
  }
 }
}
</script>";
	$TMP.= "<td width=5%> </td>";
	$TMP.= "<td width=10% align=right>&nbsp;".$TMP_New."&nbsp;</td>";
	$TMP.= "<th width=70% align=center class='title'>".$fields[$i]->title.$TMP_linkadm."</th><td width=15%> </td>";
	$TMP.= "</tr></table>";
} else $TMP.= "<td>";


session_write_close();

$TMP_URLfile=$TMP_DEF_URL."index.php?V_dir=$TMP_dir&V_mod=".$TMP_file."&V_idmod=$TMP_idmod&V_lap=".urlencode($V_lap)."&menuoff=x&headeroff=x&footeroff=x&subbrowseSID=".$PHPSESSID."&numsubbrowse=$RAD_subbrowseCont&func=".$TMP_func."&RAD_width=".$TMP_width."&RAD_height=".$TMP_height."&V_roi=".urlencode($TMP_field1."='".$TMP_id."'").$TMP_V_roi2;
if ($dbname!="") $TMP_URLfile.="&dbname=".$dbname;
if ($SQL_DEBUG!="") $TMP_URLfile.="&SQL_DEBUG=".$SQL_DEBUG;
$RAD_cfg=getSessionVar("SESSION_cfg");
if ($RAD_cfg!="") $TMP_URLfile.="&RAD_cfg=".$RAD_cfg;
if (${"V_sub_".$RAD_subbrowseCont."_roi"}!='') {
	$TMP_URLfile.="&".${"V_sub_".$RAD_subbrowseCont."_roi"};
}
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
$TMP_content = str_replace("'numform'","'numform".$RAD_subbrowseCont."'",$TMP_content);
$TMP_content = str_replace("saveregs(","saveregs".$RAD_subbrowseCont."(",$TMP_content);
$TMP_content = str_replace("document.F.","document.F".$RAD_subbrowseCont.".",$TMP_content);
$TMP_content = str_replace("document.F[","document.F".$RAD_subbrowseCont."[",$TMP_content);
$TMP_content = str_replace("document.forms.F.","document.forms.F".$RAD_subbrowseCont.".",$TMP_content);
$TMP_content = str_replace(" NAME=F "," NAME=F".$RAD_subbrowseCont." ",$TMP_content);
//$TMP_content = str_replace("RAD_OpenW(","RAD_OpenW".$i."(",$TMP_content);
//$TMP_content = str_replace("jsnull(","jsnull".$i."(",$TMP_content);
////$TMP_content = str_replace("popUpCalendar(this,F","popUpCalendar(this,F".$RAD_subbrowseCont,$TMP_content);
$TMP_content = str_replace("openW(","openW".$RAD_subbrowseCont."(",$TMP_content);
$TMP_content = str_replace("setText(","setText".$RAD_subbrowseCont."(",$TMP_content);
$TMP_content = str_replace("next(","next".$RAD_subbrowseCont."(",$TMP_content);
$TMP_content = str_replace("sel(","sel".$RAD_subbrowseCont."(",$TMP_content);
$TMP_content = str_replace("selm(","selm".$RAD_subbrowseCont."(",$TMP_content);
$TMP_content = str_replace("delereg(","delereg".$RAD_subbrowseCont."(",$TMP_content);
$TMP_content = str_replace("RAD_focusNextField(","RAD_focusNextField".$RAD_subbrowseCont."(",$TMP_content);

$TMP_content = str_replace("</body>","",$TMP_content);
$TMP_content = str_replace("</BODY>","",$TMP_content);
$TMP_content = str_replace("</html>","",$TMP_content);
$TMP_content = str_replace("</HTML>","",$TMP_content);
// $TMP_content = str_replace("</FORM","<input type=hidden name=subfunc value='browse'></FORM",$TMP_content);
// $TMP_content = str_replace("<FORM","<FORM target=_blank",$TMP_content);
$TMP_content = str_replace("HREF=\"","TARGET=_blank HREF=\"",$TMP_content);
$TMP_content = str_replace("menuoff=x&orderby","menuoff=&subfunc=browse&orderby",$TMP_content);

if (substr($TMP_content,0,30)=="Sorry, such file doesn't exist") return "\n<! ".$TMP_content." >\n";
$TMP.= $TMP_content;
$TMP_content="";
if (eregi("PDA",getSessionVar("SESSION_theme"))) $TMP = eregi_replace(" target=_blank","",$TMP);

$TMP.= "</td>";
if ($func=="edit" || $func=="new" || $func=="detail") $TMP.= "</tr>";
return $TMP;
?>
