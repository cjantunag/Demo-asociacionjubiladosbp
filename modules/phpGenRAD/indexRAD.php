<?
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: ../../index.php");
    die();
}

if ($V_submod=="pregen") {
	include_once("modules/".$V_dir."/pregen.php");
	return;
} else if ($V_submod=="fileedit") {
	include_once("modules/".$V_dir."/fileedit.php");
	return;
} else if ($V_submod=="genform") {
	$headeroff="x";
	$footeroff="x";
	$blocksoff="x";
	include_once("modules/".$V_dir."/genform.php");
	return;
} else if ($V_submod=="genmod") {
	include_once("modules/".$V_dir."/genmod.php");
	return;
} else if ($V_submod=="genapp") {
	include_once("modules/".$V_dir."/genapp.php");
	return;
}

include_once ("header.php");
OpenTable();

if ($modulesdir!="") $TMP_modulesdir=$modulesdir;
include_once ("modules/".$V_dir."/defaults.php");
if ($TMP_modulesdir!="") $modulesdir=$TMP_modulesdir;

$DIRBASE=_DEF_DIRBASE."modules/";

?>

<table border=0 cellpadding=0 cellspacing=0 width=100%><tr>
<td align=right width=95%><IMG SRC="modules/<?=$V_dir?>/logo.gif" ALT="RAD"></td>
</tr></table>
<HR noshade size=1>
<TABLE BORDER="0">
    <TR>
	<TD VALIGN="MIDDLE"><b>A.-</b></TD>
	<TD VALIGN="MIDDLE"><a href="index.php?V_dir=phpMyAdmin&V_mod=index<?=$SESSION_SID?>" target_blank>
		<?=_DEF_NLSCreate."/"._DEF_NLSModify." "._DEF_NLSDatabase."/"._DEF_NLSTable?></a> (phpMyAdmin)
	</TD>
    </TR>
    <TR>
	<TD VALIGN="MIDDLE"><b>B.-</b></TD>
	<TD VALIGN="MIDDLE">
		<A HREF="index.php?V_dir=<?=$V_dir?>&V_mod=<?=$V_mod?>&V_submod=pregen&opt=all<?=$SESSION_SID?>"><?=_DEF_NLSCreate." "._DEF_NLSApplication." "._DEF_NLSDatabase?></A>
	</TD>
    </TR>
    <TR>
	<TD VALIGN="MIDDLE"><b>C.-</b></TD>
	<TD VALIGN="MIDDLE">
		<A HREF="index.php?V_dir=<?=$V_dir?>&V_mod=<?=$V_mod?>&V_submod=pregen<?=$SESSION_SID?>"><?=_DEF_NLSCreate." "._DEF_NLSModule." "._DEF_NLSTable?></A>
	</TD>
    </TR>

<FORM ACTION="index.php" METHOD="GET" NAME=F1>
<INPUT TYPE="HIDDEN" NAME="PHPSESSID" VALUE="<?=$PHPSESSID?>">
<INPUT TYPE="HIDDEN" NAME="V_dir" VALUE="<?=$V_dir?>">
<INPUT TYPE="HIDDEN" NAME="V_mod" VALUE="<?=$V_mod?>">
<INPUT TYPE="HIDDEN" NAME="file" VALUE="<?=$file?>">
<INPUT TYPE="HIDDEN" NAME="modulesdir" VALUE="<?=$modulesdir?>">
</FORM>

    <TR>
	<TD VALIGN="MIDDLE"><b>D.-</b></TD>
	<FORM ACTION="index.php" METHOD="GET" NAME=F2>
<INPUT TYPE="HIDDEN" NAME="PHPSESSID" VALUE="<?=$PHPSESSID?>">
<INPUT TYPE="HIDDEN" NAME="V_dir" VALUE="<?=$V_dir?>">
<INPUT TYPE="HIDDEN" NAME="V_mod" VALUE="<?=$V_mod?>">
<INPUT TYPE="HIDDEN" NAME="V_submod" VALUE="genform">
	<TD VALIGN="MIDDLE">
		<?=_DEF_NLSModify." "._DEF_NLSModule?> :
		<SELECT NAME="modulesdir" onchange="document.F1.modulesdir.value=document.F2.modulesdir[document.F2.modulesdir.selectedIndex].value;document.F1.submit();">
		<option value=''></option>
<?
    if (is_dir($DIRBASE)) {
	$f = opendir($DIRBASE);
	$ki=0;
	while ($fn=readdir($f)) {
	    if (ereg("\.",$fn)) continue;
	    if ($fn =="") continue;
	    if (!is_dir($DIRBASE.$fn)) continue;
	    $Afn[$ki]=$fn;
	    $ki++;
	}
	sort($Afn,SORT_STRING);
	for ($ki=0; $ki<count($Afn); $ki++) {
	    $fn=$Afn[$ki];
	    if ($fn == $modulesdir) $selected=" SELECTED";
	    else $selected="";
	    echo "<OPTION VALUE='$fn' $selected>$fn</OPTION>\n";
	}
    } else {
	echo "Directorio erroneo : ".$DIRBASE;
    }
?>
		</SELECT>/ 
		<SELECT NAME="project_file">
<?
  if ($modulesdir!="") {
   if (file_exists($DIRBASE.$modulesdir)) {
    if (is_dir($DIRBASE.$modulesdir)) {
	$f = opendir($DIRBASE.$modulesdir);
	$ki=0;
	while ($fn=readdir($f)) {
	    if (!ereg("\.prj",$fn)) continue;
	    if ($fn =="") continue;
	    if (is_dir($DIRBASE.$fn)) continue;
	    $Bfn[$ki]=$fn;
	    $ki++;
	}
	sort($Bfn,SORT_STRING);
	for ($ki=0; $ki<count($Bfn); $ki++) {
	    $fn=$Bfn[$ki];
	    echo "<OPTION VALUE='$fn'>$fn</OPTION>\n";
	}
    } else {
	echo "<OPTION VALUE=''>Directorio erroneo : ".$modulesdir."</OPTION>\n";
    }
   }
  } else echo "<OPTION VALUE=''></OPTION>\n";
?>
		</SELECT>
<? if(count($Bfn)>0) { ?> 
		<INPUT TYPE="BUTTON" VALUE="<?=_DEF_NLSAccept?>" onclick="document.F2.target='_blank';document.F2.submit();">
<? } ?> 
	</TD>
	</FORM>
    </TR>
</TABLE>

<? include_once("modules/".$V_dir."/helpindex.".$language.".php");

CloseTable();
include_once ("footer.php"); 

?>
