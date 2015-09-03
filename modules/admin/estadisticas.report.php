<?
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: ../../index.php");
    die();
}

if (eregi($V_mod,basename(__FILE__)) || $V_mod=="statsreport") {
	require_once("mainfile.php");
	include_once("functions.php");
	include("header.php");
	OpenTable();
} else if ($func!="detail" || substr($V_lap,0,5)!="Estad") {
	return "";
}

$TMP_user = base64_decode($HTTP_SESSION_VARS["SESSION_user"]);
$user = $db->Record["usuario"];
if (!is_admin() || $owner!="") {
	$LitUser=RAD_username($TMP_user);
	$TMP_condition=" WHERE usuario='".$TMP_user."'";
	$TMP_andcondition=" AND usuario='".$TMP_user."'";
}
if ((is_admin()||!eregi(basename(__FILE__),$V_mod)) && $user!="") {
	$TMP_user=$user;
	$LitUser=RAD_username($TMP_user);
	$TMP_condition=" WHERE usuario='".$TMP_user."'";
	$TMP_andcondition=" AND usuario='".$TMP_user."'";
}

$waxix = array( ""._SUNDAY."",""._MONDAY."",""._TUESDAY."",""._WEDNESDAY."",""._THURSDAY."",""._FRIDAY."",""._SATURDAY."");
$toddate=time()-86400;
if(date("d")==1) {
    if(date("d")==31) { $xyd=30; }
    if(date("d")==30||date("m")==08||date("m")==1) { $xyd=31; }
    if(date("m")==03) {
        if(date("L")==true) { $xyd=29; }
        else { $xyd=28; }
    }
    $mtd=date("m")-1;
}
else {
    $xyd=date("d")-1;
    $mtd=date("m");
}
if(date("m")==1&&date("d")==1) {
    $yed=date("Y")-1;
    $mtd="12";
}
else { $yed=date("Y"); }
//if(strlen($xyd)==1){ $xyd="0".$xyd; }
//if(strlen($mtd)==1){ $mtd="0".$mtd; }
$yesdate=$xyd.$mtd.$yed;

$toddb=sql_query("SELECT * FROM estadisticas ".$TMP_condition,$RAD_dbi);
if($toddb) {
    $valX=sql_fetch_array($toddb,$RAD_dbi);
    $startdate=$valX[dia]."/".$valX[mes]."/".$valX[anho];
}
$toddb=sql_query("SELECT count(*), SUM(paginasvistas) FROM estadisticas ".$TMP_condition,$RAD_dbi);
if($toddb) {
    $valX=sql_fetch_array($toddb,$RAD_dbi);
    $visits=$valX[0];
    $total=$valX[1];
}

$toddb=sql_query("SELECT count(*), SUM(paginasvistas) FROM estadisticas WHERE tiempoinicio >$toddate ".$TMP_andcondition,$RAD_dbi);
if($toddb) {
    $valtodayX=sql_fetch_array($toddb,$RAD_dbi);
    $visitstoday=$valtodayX[0];
    $valtoday=$valtodayX[1];
}
else { $valtoday=0; }
$yesdb=sql_query("SELECT * FROM estadisticas WHERE dia=$xyd AND mes=$mtd AND anho=$yed ".$TMP_andcondition,$RAD_dbi);
if($yesdb) {
    $valyesdayX=sql_fetch_array($yesdb,$RAD_dbi);
    $valyesday=$valyesdayX[hits];
}
else { $valyesday=0; }
$sumlex=0;
$hourbadhits=1000000000000;
for($saxx=0;$saxx<=23;$saxx++) {
    $htmpdb=sql_query("SELECT paginasvistas FROM estadisticas WHERE hora=$saxx ".$TMP_andcondition,$RAD_dbi);
    $TMProw=sql_fetch_array($htmpdb,$RAD_dbi);
    $lexhr[$saxx]=$TMProw[0];
    if ($lexhr[$saxx]=="") $lexhr[$saxx]=0;
    if($hourbesthits<$lexhr[$saxx]){
        $hourbesthits=$lexhr[$saxx];
        $hourbest=$saxx;
    }
    if($hourbadhits>$lexhr[$saxx]){
        $hourbadhits=$lexhr[$saxx];
        $hourbad=$saxx;
    }
    $sumlex=$sumlex+$lexhr[$saxx];
}
$sumlux=0;
$weekdaybadhits=1000000000000;
for($sbxx=0;$sbxx<=6;$sbxx++) {
    $htmpdbx=sql_query("SELECT paginasvistas FROM estadisticas WHERE diasemana=$sbxx ".$TMP_andcondition,$RAD_dbi);
    $TMProw=sql_fetch_array($htmpdbx,$RAD_dbi);
    $luxhr[$sbxx]=$TMProw[0];
    if ($luxhr[$sbxx]=="") $luxhr[$sbxx]=0;
    if($weekdaybesthits<$luxhr[$sbxx]){
        $weekdaybesthits=$luxhr[$sbxx];
        $weekdaybest=$sbxx;
    }
    if($weekdaybadhits>$luxhr[$sbxx]){
        $weekdaybadhits=$luxhr[$sbxx];
        $weekdaybad=$sbxx;
    }
    $sumlux=$sumlux+$luxhr[$sbxx];
}
$summon=0;
for($salmon=1;$salmon<=12;$salmon++) {
    $htmpdbs=sql_query("SELECT paginasvistas FROM estadisticas WHERE mes=$salmon ".$TMP_andcondition,$RAD_dbi);
    $TMProw=sql_fetch_array($htmpdbs,$RAD_dbi);
    $lexmon[$salmon]=$TMProw[0];
    $summon=$summon+$lexmon[$salmon];
}
$result = sql_query("select tipobrowser, sistemaop from estadisticas ".$TMP_condition." order by tipobrowser desc",$RAD_dbi);
if (!$result) { 
    $TMP_content.=sql_errno(). ": ".sql_error(). "<br>";
    exit();
}
while(list($type, $var) = sql_fetch_row($result,$RAD_dbi)) {
	if($type == "Netscape") $netscape[0] ++;
	elseif($type == "MSIE") $msie[0] ++;
	elseif($type == "Konqueror") $konqueror[0] ++;
	elseif($type == "Opera") $opera[0] ++;
	elseif($type == "Lynx") $lynx[0] ++;
	elseif($type == "Bot") $bot[0] ++;
	elseif(($type == "Other")) $b_other[0] ++;

	if($var == "Windows") $windows[0] ++;
	elseif($var == "Mac") $mac[0] ++;
	elseif($var == "Linux") $linux[0] ++;
	elseif($var == "FreeBSD") $freebsd[0] ++;
	elseif($var == "SunOS") $sunos[0] ++;
	elseif($var == "IRIX") $irix[0] ++;
	elseif($var == "BeOS") $beos[0] ++;
	elseif($var == "OS/2") $os2[0] ++;
	elseif($var == "AIX") $aix[0] ++;
	elseif(($type == "os") && ($var == "Other")) $os_other[0] ++;
}

$ThemeSel = $Default_Theme;
$TMP_content.="<table class=borde width=100%><tr><td><center><b>"._STATS_OF." "._DEF_SITENAME."</b> <u>".$LitUser."</u> </center>";
$TMP_content.="<br>"._STATS_DISTR." <i>$startdate</i>: <b>$total</b> ($visits "._STATS_VISITS.").<br>"._STATS_TODAY." <b>$valtoday</b> "._STATS_PAGES." ($visitstoday "._STATS_VISITS.").<br><b> ".$waxix[$weekdaybest]."</b> "._STATS_PLUS." <b>".$weekdaybesthits."</b> "._STATS_DISTRI.".<br><b>".$waxix[$weekdaybad]."</b> "._STATS_MINUS." <b>".$weekdaybadhits."</b> "._STATS_DISTRI.".<br>"._STATS_H_P." <b>$hourbest</b> "._STATS_WITH." <b>$hourbesthits</b> "._STATS_HITS.".<br>"._STATS_H_M." <b>$hourbad</b> "._STATS_WITH." <b>$hourbadhits</b> "._STATS_HITS.".";
$TMP_content.="</td></tr></table><br><br>";

if ($total>0) {
	$bigxy=0;
	if($bigxy<$konqueror[0])$bigxy=$konqueror[0];
	if($bigxy<$msie[0])$bigxy=$msie[0];
	if($bigxy<$opera[0])$bigxy=$opera[0];
	if($bigxy<$netscape[0])$bigxy=$netscape[0];
	if($bigxy<$webtv[0])$bigxy=$webtv[0];
	if($bigxy<$lynx[0])$bigxy=$lynx[0];
	if($bigxy<$bot[0])$bigxy=$bot[0];
	if($bigxy<$b_other[0])$bigxy=$b_other[0];
	$konqueror[1]=substr($konqueror[0]*100/$visits,0,6);
	$msie[1]=substr($msie[0]*100/$visits,0,6);
	$opera[1]=substr($opera[0]*100/$visits,0,6);
	$netscape[1]=substr($netscape[0]*100/$visits,0,6);
	$webtv[1]=substr($webtv[0]*100/$visits,0,6);
	$lynx[1]=substr($lynx[0]*100/$visits,0,6);
	$bot[1]=substr($bot[0]*100/$visits,0,6);
	$b_other[1]=substr($b_other[0]*100/$visits,0,6);
	$TMP_content.="\n<table class=borde width=100%><tr><td>\n";
	$TMP_content.="<table cellspacing=\"0\" cellpadding=\"2\" border=\"0\" align=\"center\"><tr><td colspan=\"3\">\n";
	$TMP_content.="<center><b>"._BROWSERS."</b></center><br></td></tr>\n";
	if($msie[0]>0) $TMP_content.="<tr><td> MSIE: </td><td>".html_bar(round((100*$msie[0])/$bigxy,0),333,10,"#660000","#000000","white")."</td><td> $msie[1] % ($msie[0])</td></tr>\n";
	if($netscape[0]>0) $TMP_content.="<tr><td> Netscape: </td><td>".html_bar(round((100*$netscape[0])/$bigxy,0),333,10,"#660000","#000000","white")."</td><td> $netscape[1] % ($netscape[0])</td></tr>\n";
	if($opera[0]>0) $TMP_content.="<tr><td> Opera: </td><td>".html_bar(round((100*$opera[0])/$bigxy,0),333,10,"#660000","#000000","white")."</td><td> $opera[1] % ($opera[0])</td></tr>\n";
	if($lynx[0]>0) $TMP_content.="<tr><td> Lynx: </td><td>".html_bar(round((100*$lynx[0])/$bigxy,0),333,10,"#660000","#000000","white")."</td><td> $lynx[1] % ($lynx[0])</td></tr>\n";
	if($konqueror[0]>0) $TMP_content.="<tr><td> Konqueror: </td><td>".html_bar(round((100*$konqueror[0])/$bigxy,0),333,10,"#660000","#000000","white")."</td><td> $konqueror[1] % ($konqueror[0])</td></tr>\n";
	if($webtv[0]>0) $TMP_content.="<tr><td> WebTV: </td><td>".html_bar(round((100*$webtv[0])/$bigxy,0),333,10,"#660000","#000000","white")."</td><td> $webtv[1] % ($webtv[0])</td></tr>\n";
	if($bot[0]>0) $TMP_content.="<tr><td> ".translate("Search Engines").": </td><td>".html_bar(round((100*$bot[0])/$bigxy,0),333,10,"#660000","#000000","white")."</td><td> $bot[1] % ($bot[0])</td></tr>\n";
	if($b_other[0]>0) $TMP_content.="<tr><td> "._UNKNOWN.": </td><td>".html_bar(round((100*$b_other[0])/$bigxy,0),333,10,"#660000","#000000","white")."</td><td> $b_other[1] % ($b_other[0])</td></tr>\n";
	$TMP_content.="</table>";
	$TMP_content.="\n</td></tr></table>\n";
	$TMP_content.="<br><br>";

	$bigxy=0;
	if($bigxy<$linux[0])$bigxy=$linux[0];
	if($bigxy<$windows[0])$bigxy=$windows[0];
	if($bigxy<$mac[0])$bigxy=$mac[0];
	if($bigxy<$freebsd[0])$bigxy=$freebsd[0];
	if($bigxy<$sunos[0])$bigxy=$sunos[0];
	if($bigxy<$irix[0])$bigxy=$irix[0];
	if($bigxy<$beos[0])$bigxy=$beos[0];
	if($bigxy<$os2[0])$bigxy=$os2[0];
	if($bigxy<$aix[0])$bigxy=$aix[0];
	if($bigxy<$os_other[0])$bigxy=$os_other[0];
	$linux[1]=substr($linux[0]*100/$visits,0,6);
	$windows[1]=substr($windows[0]*100/$visits,0,6);
	$mac[1]=substr($mac[0]*100/$visits,0,6);
	$freebsd[1]=substr($freebsd[0]*100/$visits,0,6);
	$sunos[1]=substr($sunos[0]*100/$visits,0,6);
	$irix[1]=substr($irix[0]*100/$visits,0,6);
	$beos[1]=substr($beos[0]*100/$visits,0,6);
	$os2[1]=substr($os2[0]*100/$visits,0,6);
	$aix[1]=substr($aix[0]*100/$visits,0,6);
	$os_other[1]=substr($os_other[0]*100/$visits,0,6);
	$TMP_content.="\n<table class=borde width=100%><tr><td>\n";
	$TMP_content.="<table cellspacing=\"0\" cellpadding=\"2\" border=\"0\" align=\"center\"><tr><td colspan=\"3\">\n";
	$TMP_content.="<center><b>"._OPERATINGSYS."</b></center><br></td></tr>\n";
	if($windows[0]>0) $TMP_content.="<tr><td> Windows:</td><td>".html_bar(round((100*$windows[0])/$bigxy,0),333,10,"#0A5F89","#0A5F89","white")."</td><td> $windows[1] % ($windows[0])</td></tr>\n";
	if($linux[0]>0) $TMP_content.="<tr><td> Linux:</td><td>".html_bar(round((100*$linux[0])/$bigxy,0),333,10,"#0A5F89","#0A5F89","white")."</td><td> $linux[1] % ($linux[0])</td></tr>\n";
	if($mac[0]>0) $TMP_content.="<tr><td> Mac/PPC:</td><td>".html_bar(round((100*$mac[0])/$bigxy,0),333,10,"#0A5F89","#0A5F89","white")."</td><td> $mac[1] % ($mac[0])</td></tr>\n";
	if($freebsd[0]>0) $TMP_content.="<tr><td> FreeBSD:</td><td>".html_bar(round((100*$freebsd[0])/$bigxy,0),333,10,"#0A5F89","#0A5F89","white")."</td><td> $freebsd[1] % ($freebsd[0])</td></tr>\n";
	if($sunos[0]>0) $TMP_content.="<tr><td> SunOS:</td><td>".html_bar(round((100*$sunos[0])/$bigxy,0),333,10,"#0A5F89","#0A5F89","white")."</td><td> $sunos[1] % ($sunos[0])</td></tr>\n";
	if($irix[0]>0) $TMP_content.="<tr><td> IRIX:</td><td>".html_bar(round((100*$irix[0])/$bigxy,0),333,10,"#0A5F89","#0A5F89","white")."</td><td> $irix[1] % ($irix[0])</td></tr>\n";
	if($beos[0]>0) $TMP_content.="<tr><td> BeOS:</td><td>".html_bar(round((100*$beos[0])/$bigxy,0),333,10,"#0A5F89","#0A5F89","white")."</td><td> $beos[1] % ($beos[0])</td></tr>\n";
	if($os2[0]>0) $TMP_content.="<tr><td> OS/2:</td><td>".html_bar(round((100*$os2[0])/$bigxy,0),333,10,"#0A5F89","#0A5F89","white")."</td><td> $os2[1] % ($os2[0])</td></tr>\n";
	if($aix[0]>0) $TMP_content.="<tr><td> AIX:</td><td>".html_bar(round((100*$aix[0])/$bigxy,0),333,10,"#0A5F89","#0A5F89","white")."</td><td> $aix[1] % ($aix[0])</td></tr>\n";
	if($os_other[0]>0) $TMP_content.="<tr><td> "._UNKNOWN.":</td><td>".html_bar(round((100*$os_other[0])/$bigxy,0),333,10,"#0A5F89","#0A5F89","white")."</td><td> $os_other[1] % ($os_other[0])</td></tr>\n";
	$TMP_content.="</table>\n";
	$TMP_content.="\n</td></tr></table>\n";

	$TMP_content.= "<br><br>";
	$TMP_content.="\n<table class=borde width=100%><tr><td>\n";
	$TMP_content.="<table cellspacing=\"0\" cellpadding=\"2\" border=\"0\" align=\"center\"><tr><td colspan=\"3\">\n";
	$TMP_content.="<center>"._STATS_CLOCK."</center><br></td></tr>\n";
	$telixbiggest=0;
	for($telix=0;$telix<=23;$telix++)if($telixbiggest<$lexhr[$telix])$telixbiggest=$lexhr[$telix];
	if ($telixbiggest<1) $telixbiggest=1;
	$TMP_content.="<table cellspacing=0 cellpadding=3 border=0 align=center>";
	for($telix=0;$telix<=23;$telix++) {
		if($telix == 0) { $telixx = ""._STATS_MN.""; }
		elseif($telix > 0 && $telix < 10) { $telixx = "0$telix:00 am"; }
		elseif($telix > 9 && $telix < 12) { $telixx = "$telix:00 am"; }
		elseif($telix == 12) { $telixx = ""._STATS_MD.""; }
		elseif($telix > 12 && $telix < 22) { $telixx = $telix-12;$telixx = "0$telixx:00 pm"; }
		else { $telixx = $telix-12;$telixx = "$telixx:00 pm"; }
		$TMP_content.="<tr><td align=right>$telixx</td><td>".html_bar(round((100*$lexhr[$telix])/$telixbiggest,0),333,10,"#0000ff","#0000ff","white")."</td><td>".round((100*$lexhr[$telix])/$sumlex,2)."% ($lexhr[$telix])</td></tr>\n";
	}
	$TMP_content.="</table>\n";
	$TMP_content.="\n</td></tr></table>\n";

	$TMP_content.="<br><br>";
	$telixbiggest=0;
	for($telix=0;$telix<=6;$telix++)if($telixbiggest<$luxhr[$telix])$telixbiggest=$luxhr[$telix];
	if ($telixbiggest<1) $telixbiggest=1;
	$TMP_content.="\n<table class=borde width=100%><tr><td>\n";
	$TMP_content.="<table cellspacing=\"0\" cellpadding=\"2\" border=\"0\" align=\"center\"><tr><td colspan=\"3\">\n";
	$TMP_content.="<center><b>Días de la semana</b></center><br></td></tr>\n";
	if ($telixbiggest<1) $telixbiggest=1;
	if ($sumlux<1) $sumlux=1;
	for($telix2=1;$telix2<=6;$telix2++) { 
		$TMP_content.="<tr><td>$waxix[$telix2]:</td><td>".html_bar(round((100*$luxhr[$telix2])/$telixbiggest,0),333,10,"#008000","#008000","white")."</td><td>".round((100*$luxhr[$telix2])/$sumlux,2)."% ($luxhr[$telix2])</td></tr>\n"; }
	$telix2=0;
	if ($telixbiggest<1) $telixbiggest=1;
	if ($sumlux<1) $sumlux=1;
	$TMP_content.="<tr><td>$waxix[$telix2]:</td><td>".html_bar(round((100*$luxhr[$telix2])/$telixbiggest,0),333,10,"#008000","#008000","white")."</td><td>".round((100*$luxhr[$telix2])/$sumlux,2)."% ($luxhr[$telix2])</td></tr>\n";
	$TMP_content.="</table>\n";
	$TMP_content.="\n</td></tr></table>\n";
	$TMP_content.="<br><br>";

	$telixbiggest=0;
	for($telix=1;$telix<=12;$telix++)if($telixbiggest<$lexmon[$telix])$telixbiggest=$lexmon[$telix];
	if ($telixbiggest<1) $telixbiggest=1;
	$waxixy = array("",""._JANUARY."",""._FEBRUARY."",""._MARCH."",""._APRIL."",""._MAY."",""._JUNE."",""._JULY."",""._AUGUST."",""._SEPTEMBER."",""._OCTOBER."",""._NOVEMBER."",""._DECEMBER."");
	$TMP_content.="\n<table class=borde width=100%><tr><td>\n";
	$TMP_content.="<table cellspacing=\"0\" cellpadding=\"2\" border=\"0\" align=\"center\"><tr><td colspan=\"3\">\n";
	$TMP_content.="<center><b>"._STATS_M_Y."</b></center><br></td></tr>\n";
	if ($telixbiggest<1) $telixbiggest=1;
	if ($summon<1) $summon=1;
	for($telix4=1;$telix4<=12;$telix4++) { $TMP_content.="<tr><td>".$waxixy[$telix4].":</td><td>".html_bar(round((100*$lexmon[$telix4])/$telixbiggest,0),333,10,"#FF7D00","#FF7D00","white")."</td><td>".round((100*$lexmon[$telix4])/$summon,2)."% ($lexmon[$telix4])</td></tr>\n"; }
	$TMP_content.="</table>\n";
	$TMP_content.="\n</td></tr></table>\n";
	$TMP_content.="<br><br>";
}

if (eregi($V_mod,basename(__FILE__)) || $V_mod=="statsreport") {
	echo $TMP_content;
	CloseTable();
	include("footer.php");
	return;
} else return $TMP_content;

//////////////////////////////////////////////////////////////////////////////////////////////////////
function html_bar($pperc,$width=100,$height=10,$color="#000000",$bordercolor="#000000",$bgcolor="#ffffff") {
    $pperc=round($pperc,0);
    $ipperc=100-$pperc;
    if($pperc==100) { $what="<table width=$width height=$height border=1 cellspacing=0 cellpadding=0 style=\"border-width:1px;border-style:solid;border-color:$bordercolor\"><tr><td width=\"100%\" style=\"background-color:$color;border-style:solid;border-width:1px;border-color:$bgcolor\"></td></tr></table>"; }
    elseif($pperc==0) { $what="<table width=$width height=$height border=1 cellspacing=0 cellpadding=0 style=\"border-width:1px;border-style:solid;border-color:$bordercolor\"><tr><td width=\"100%\" style=\"background-color:$bgcolor;border-style:solid;border-width:1px;border-color:$bgcolor\"></td></tr></table>"; }
    else { $what="<table width=$width height=$height border=1 cellspacing=0 cellpadding=0 style=\"border-width:1px;border-style:solid;border-color:$bordercolor\"><tr><td width=\"$pperc%\" style=\"background-color:$color;border-style:solid;border-width:1px;border-color:$bgcolor\"></td><td width=\"$ipperc%\" style=\"background-color:$bgcolor;border-style:solid;border-width:1px;border-color:$bgcolor\"></td></tr></table>"; }
    return $what;
}
?>
