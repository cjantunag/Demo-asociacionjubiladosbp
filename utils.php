<?php
require_once("mainfile.php");

if ($formName=="") $formName="F";
if ($func=="cal" || $func=="calmul") {
	calendar($year,$month);
} else if ($func=="amp") {
	magnifica($field,$type);
} else if (isset($FCKeditor1)) {
//	$FCKeditor1=str_replace("\r","",$FCKeditor1);
//	$FCKeditor1=str_replace("\n","\\\\n",$FCKeditor1);
//	$FCKeditor1=str_replace("'","\\'",$FCKeditor1);
//	$FCKeditor1=str_replace('"','\\"',$FCKeditor1);
	$FCKeditor1=rawurlencode($FCKeditor1);
	echo "\n<script>\nvar field=opener.selFieldName;\nvar val=unescape('$FCKeditor1');\neval('opener.document.".$formName.".'+field+'.value=val');\nif (opener.restoreFCK) opener.restoreFCK(field);\nwindow.close();\n</script>\n";;
} else {
	foreach ($_REQUEST as $key=>$val) {
		echo $key."=".$val."<br>\n";
	}
}
//----------------------------------------------------------------------------------------
function calendar($ano,$mes) {
global $headeroff, $footeroff, $func, $field, $formName;
$headeroff="x";	
include_once ("header.php");
$TMP_result.=imprimeMes($ano, $mes);
if ($func=="cal") echo "
<script type='text/javascript'>
function setFieldCal(field1,field2,field3,ano,mes,dia) {
	for (var i=0; i<opener.document.".$formName.".elements.length; i++) {
		if (opener.document.".$formName."[i].name == field1) opener.document.".$formName."[i].value=ano;
		if (opener.document.".$formName."[i].name == field2) opener.document.".$formName."[i].value=mes;
		if (opener.document.".$formName."[i].name == field3) opener.document.".$formName."[i].value=dia;
	}
	window.close();
}
</script>
";
else echo "
<script type='text/javascript'>
function setFieldCal(field1,field2,field3,ano,mes,dia) {
  var fecha=dia+'-'+mes+'-'+ano;
  for (var i=0; i<opener.document.".$formName.".elements.length; i++) {
      if (opener.document.".$formName."[i].name == '".$field."') opener.document.".$formName."[i].value+=fecha+'\\n';
  }
}
</script>
";
echo $TMP_result;
if ($func=="calmul") echo "<br><br>&nbsp;<a href='javascript:window.close();'>Cerrar ventana</a>";
$footeroff="x";	
include_once ("footer.php"); 
}
//----------------------------------------------------------------------------------------
function imprimeMes($ano,$mes) {
global $PHP_SELF, $V_dir, $V_mod, $field, $func, $formName;

	$xmes=$mes; if (strlen($xmes)<2) $xmes="0".$xmes; if (strlen($xmes)<2) $xmes="0".$xmes;
	if (!(isset($ano))||($ano=="")) $ano=date("Y",mktime (0,0,0,date ("n"),1,date("Y")));
	if (!(isset($mes))||($mes=="")) $mes=date("n",mktime (0,0,0,date ("n"),1,date("Y")));
	if ($mes<1 || $mes>12) $mes=1;
	if ($mes=="1") {
		$mespost=$mes+1;
		$anopost=$ano;
		$mesprev=12;
		$anoprev=$ano-1;
	} else if ($mes=="12") {
		$mespost=1;
		$anopost=$ano+1;
		$mesprev=$mes-1;
		$anoprev=$ano;
	} else {
		$mespost=$mes+1;
		$anopost=$ano;
		$mesprev=$mes-1;
		$anoprev=$ano;
	}

	$anomas=$ano+1;
	$anomenos=$ano-1;
	$primer_dia_mes=date("w",mktime (0,0,0,$mes,1,$ano));
	if ($primer_dia_mes==0) {$primer_dia_mes=7;}  // porque empieza en domingo
	$dias_mes=date("d",mktime (0,0,0,$mes+1,0,$ano));

	$meses=array("",_DEF_NLSMonth01,_DEF_NLSMonth02,_DEF_NLSMonth03,_DEF_NLSMonth04,_DEF_NLSMonth05,_DEF_NLSMonth06,_DEF_NLSMonth07,_DEF_NLSMonth08,_DEF_NLSMonth09,_DEF_NLSMonth10,_DEF_NLSMonth11,_DEF_NLSMonth12);
	$nummes=$mes*1;
	$TMP_result.="<table width=100% border=0 cellpadding=0 cellspacing=1>";
	$TMP_result.="<tr align=center><td bgcolor=#006699 align=center>
		<a href=\"$PHP_SELF?formName=$formName&func=$func".$SESSION_SID."&V_dir=$V_dir&V_mod=$V_mod&month=$mes&year=$anomenos&field=$field\"><img src=\"images/flechaizq.gif\" border=0 width=6 height=17 align=TOP></a></td>
		<td colspan=5 bgcolor=#006699 nowrap align=center><b><font size=1 style='color:white'>".$ano."</b></font></td>
		<td bgcolor=#006699 align=center>
		<a href=\"$PHP_SELF?formName=$formName&func=$func".$SESSION_SID."&V_dir=$V_dir&V_mod=$V_mod&month=$mes&year=$anomas&field=$field\"><img src=\"images/flechader.gif\" border=0 width=6 height=17></a></td></tr>
	<tr align=center><td bgcolor=#006699 align=center>
		<a href=\"$PHP_SELF?formName=$formName&func=$func".$SESSION_SID."&V_dir=$V_dir&V_mod=$V_mod&month=$mesprev&year=$anoprev&field=$field\"><img src=\"images/flechaizq.gif\" border=0 width=6 height=17 align=TOP></a></td>
		<td colspan=5 bgcolor=#006699 nowrap align=center><font size=1 style='color:white'><b>".$meses[$nummes]."</b></font></td>
		<td bgcolor=#006699 align=center>
		<a href=\"$PHP_SELF?formName=$formName&func=$func".$SESSION_SID."&V_dir=$V_dir&V_mod=$V_mod&month=$mespost&year=$anopost&field=$field\"><img src=\"images/flechader.gif\" border=0 width=6 height=17></a></td></tr>
	<tr align=center>
		<td bgcolor=#ffcc66 width=14%><font size=1>L</font></td>
		<td bgcolor=#ffcc66 width=14%><font size=1>M</font></td>
		<td bgcolor=#ffcc66 width=14%><font size=1>X</font></td>
		<td bgcolor=#ffcc66 width=14%><font size=1>J</font></td>
		<td bgcolor=#ffcc66 width=14%><font size=1>V</font></td>
		<td bgcolor=#ffcc66 width=14%><font size=1>S</font></td>
		<td bgcolor=#ffcc66 width=14%><font size=1>D</font></td>
	</tr>";
	$TMP_result.="<tr align=center>";
	for ($i=1;$i<$primer_dia_mes;$i++) {
		$TMP_result.="<td bgcolor=#ffeebb>&nbsp;</td>";
	}
	for ($j=1;$i<$dias_mes+$primer_dia_mes;$i++,$j++) { // salto de semana
		$xdia=$j; if (strlen($xdia)<2) $xdia="0".$xdia; if (strlen($xdia)<2) $xdia="0".$xdia;
		if (!(($i-1)%7)&&$j>1) {
			$TMP_result.="</tr><tr align=center>";
		}
		$TMP_result.="<td bgcolor=white><a href=\"javascript:setFieldCal('".$field."_year','".$field."_month','".$field."_day',$ano,$mes,$j);\"><font size=1>".$j."</font></a><br><table width=100% border=0 cellpadding=0 cellspacing=1>";
		$TMP_result.="</table></td>";
	}
	while (($i-1)%7)  {	// restantes casillas
		$TMP_result.="<td bgcolor=#ffeebb><font size=1>&nbsp;</font></td>";
		$i++;
	}
	$TMP_result.="</tr></table>";
	return $TMP_result;
}

//----------------------------------------------------------------------------------------
function magnifica($field,$type) {
global $headeroff, $footeroff, $REQUEST_URI, $formName;
$headeroff="x";	
include_once ("header.php");

echo "
<script src='images/editor/whizzywig.js' type='text/javascript'></script>
<script>
self.moveTo(0,0);
self.resizeTo(screen.availWidth,screen.availHeight);
window.onload = function() {
 document.onkeydown = captureKey;
 document.onkeyup = captureKey;
}
function captureKey(e) {
	if (!e) {
		if(window.event) e = window.event;
		else return;
	}
	if (e['keyCode']==27) {
	    window.close();
	}
}
</script>";

if ($type=="texthtmlrich"||$type=="texthtml") {


    include("images/ckeditor/ckeditor.php");
    $CKEditor = new CKEditor();
    $CKEditor->basePath = '/images/ckeditor/';
    $CKEditor->replaceAll();


	echo "<form name=X action='utils.php' method=post>
<input type=hidden name=fieldtextarea value='$field'>
<script type='text/javascript' src='templates/fckeditor.js'></script>
<script type='text/javascript'>
<!--
function FCKeditor_OnComplete( editorInstance ) {
        window.status = editorInstance.Description ;
}
var editorHtml = new FCKeditor( 'FCKeditor1' ) ;
editorHtml.BasePath = 'templates/';
editorHtml.Value = '';
for (var i=0; i<opener.document.".$formName.".elements.length; i++) {
	if (opener.document.".$formName."[i].name == '$field') {
		editorHtml.Value = opener.document.".$formName."[i].value;
	}
}
editorHtml.Create() ;

function setFieldHtml() {
     for (var i=0; i<opener.document.".$formName.".elements.length; i++) {
                if (opener.document.".$formName."[i].name == '$field') opener.document.".$formName."[i].value=editorHtml.Value;
        }
     window.close();
}
//-->
</script>
";
	echo "</form>";
	$footeroff="x";
	include_once ("footer.php");
	die();
}


echo "<table width=100% height=100%><form name=X style='margin:0px; padding:0px;'><tr height=16><td>\n";
if (substr($type,0,8)!="texthtml") {
	echo "&nbsp;<a accesskey='S' title='ALT+S' href=\"javascript:setField('".$field."',document.X.texta.value);\"><img border=0 alt='' src='images/save_as.gif'> "._DEF_NLSSave."</a>";
	$TMP_URL=RAD_delParamURL($REQUEST_URI,"type");
	if (substr($TMP_URL,0,1)=="&") $TMP_URL=substr($TMP_URL,1);
	$TMP_URL.=$TMP_URL."&type=texthtml";
	echo " | <a href='$TMP_URL'><img border=0 src='images/edit2.png'> Editor HTML</a>";
} else {
	echo "<script>\n menuprefix='&nbsp;<a accesskey='S' title='ALT+S' href=\"javascript:syncTextarea();setField(\'".$field."\',document.X.texta.value);\"><img border=0 title=\'"._DEF_NLSSave."\' alt=\'"._DEF_NLSSave."\' src=\'images/save_as.gif\'></a>&nbsp;';\n</script>\n";
        $TMP_URL=RAD_delParamURL($REQUEST_URI,"type");
        if (substr($TMP_URL,0,1)=="&") $TMP_URL=substr($TMP_URL,1);
        $TMP_URL.=$TMP_URL."&type=texthtmlrich";
        echo "<script>\n menuprefix+='<a href=\'$TMP_URL\'><img border=0 src=\'images/edit2.png\' alt=\'Editor Gr&aacute;fico\' title=\'Editor Gr&aacute;fico\'></a>&nbsp;';\n</script>\n";
}
if (substr($type,0,8)!="texthtml") echo "</td></tr><tr><td>";
echo "
<textarea NAME='texta' ID='texta' COLS=120 ROWS=35 style='width:100%; height:100%'>
</textarea>
<script type='text/javascript'>
document.X.texta.focus();
for (var i=0; i<opener.document.".$formName.".elements.length; i++) {
	if (opener.document.".$formName."[i].name == '$field') document.X.texta.value=opener.document.".$formName."[i].value;
}
";
if (substr($type,0,8)=="texthtml") echo "makeWhizzyWig('texta', 'all');\n";
echo "
</script>
<script type='text/javascript'>
function setField(field,val) {
	for (var i=0; i<opener.document.".$formName.".elements.length; i++) {
		if (opener.document.".$formName."[i].name == field) opener.document.".$formName."[i].value=val;
	}
	window.close();
}
</script>
</td></tr></form></table>
";

$footeroff="x";	
include_once ("footer.php"); 
}
?>
