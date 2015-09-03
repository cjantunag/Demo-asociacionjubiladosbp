<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}
if ($func == "search_js") {
    $searchfieldX=$searchfield;
    if ($findex[$searchfield]=="" && $findex[$searchfield]!="0") {
	$pos = strpos($searchfield,"_");
	$searchfieldX=substr($searchfield,$pos+1);
    }
    list($ptablename, $pfname, $pftitle, $pfilter, $pfieldparent, $porder, $pgroup) = dbdecode($fields[$findex[$searchfieldX]]);
    for ($ki=0; $ki<$numf; $ki++) {
	if ($fields[$ki]->extra!="" && $ki!=$findex[$searchfieldX]) {
	    if (substr($fields[$ki]->extra,0,strlen($ptablename)+1)==$ptablename.":") {
alert($ki.$fields[$ki]->name.substr($fields[$ki]->extra,0,strlen($ptablename)+1));
	    }
	}
    }
}
if (!isset($RAD_js_echo)) {
    $RAD_js_echo=true;
    if ($func == "search_js" || $subfunc=="search_js" || $subfunc=="browse") {
	echo "
<script>
var cierra=true;
if (document.all) ancho=document.body.clientWidth;
else ancho=window.innerWidth;
if (document.all) alto=document.body.clientHeight;
else alto=window.innerHeight;
var anchomax=screen.width;
var altomax=screen.height;
focusNextField=false;
";
	if ($func == "search_js") echo "window.moveTo((anchomax-ancho)/2,(altomax-alto)/2);\n";
	echo "window.onload = function() {
 document.onkeydown = captureKey;
 document.onkeyup = captureKey;
}
window.onbeforeunload = function() {
	if (focusNextField==false) {
//		focusNextField=true;
//		opener.RAD_focusNextField('".$searchfield."');
		window.focus();
	}
	return;
}
function salta(url) {
	self.moveTo(0,0);self.resizeTo(screen.availWidth,screen.availHeight);
	window.location.href=url;
}

function captureKey(e) {
	if (!e) {
		if(window.event) e = window.event;
		else return;
	}
	if (e['keyCode']==27) {
////	    window.opener.location.href=window.opener.location.href;
////	    window.close();
	    next();
	}
//	if (e['keyCode']>111 && e['keyCode']<124) return false;
}
</script>
";
    }

    if (strpos($V_roi,"'")>0) $TMP_V_roi="\"".$V_roi."\"";
    else $TMP_V_roi="'".$V_roi."'";

?>
<SCRIPT LANGUAGE="JAVASCRIPT">
function setText(field,fieldvalue,opt) {
	var numobj=-1;
	for (var i=0; i<document.forms.F.elements.length; i++) {
		if (document.forms.F[i].name == field) numobj=i;
	}
	if (numobj >-1) {
		if (opt == 1) document.forms.F[numobj].value=fieldvalue;
		else document.forms.F[numobj].value=document.forms.F[numobj].value+fieldvalue;
	}
}
function delereg() {
	dele=confirm("<?=_DEF_NLSAreYouSure?>");
	if (dele) {
		document.forms.FORM1.func.value="delete";
		document.forms.FORM1.submit(); }
}
var f1, f2, f3;
var numdosel=0;
function dosel(searchfield,search,res,param) {
    if (selFieldName!=searchfield && selFieldName!=search.name && selFieldName!=res.name) {
	numdosel++;
	if ((numdosel%2)==0) { // para evitar bucles en la vuelta del foco
		RAD_setselFieldName(searchfield);
		eval('document.forms.F.'+searchfield+'.focus();');
	} else return;
    }
    if (selFieldName==searchfield) RAD_setselFieldName(search.name);
    eval('val=document.forms.F.'+searchfield+'.value;');
    x=10; y=10;
    if (window.screen) { x=(screen.width-400)/2; y=(screen.height-500)/2; }
    searchpopup = window.open("<?=$PHP_SELF?>?func=search_js&searchfield=" + <?
if ($V_roi!="") {
?> escape(searchfield)+"&searchval="+escape(search.value)+"&param="+escape(param)+"&V_roi="+escape(<?=$TMP_V_roi?>)+"&lit="+escape(res.value)+"&vlit="+escape(res)+"&headeroff=x&footeroff=x&V_dir=<?=$V_dir?>&V_mod=<?=$V_mod?>&PHPSESSID=<?=$PHPSESSID?>","","width=400,height=500,screenx="+x+",screeny="+y+",scrollbars=yes");
<?
} else {
?> escape(searchfield)+"&searchval="+escape(search.value)+"&param="+escape(param)+"&lit="+escape(res.value)+"&vlit="+escape(res)+"&headeroff=x&footeroff=x&V_dir=<?=$V_dir?>&V_mod=<?=$V_mod?>&PHPSESSID=<?=$PHPSESSID?>","","width=400,height=500,screenx="+x+",screeny="+y+",scrollbars=yes");
<?
} ?>
	if (window.focus) { if (searchpopup) searchpopup.focus(); }
	f1 = search;
	f2 = res;
	f3 = search;
	for (var i=0; i<document.forms.F.elements.length; i++) {
	    if (document.forms.F[i] == res) {
		for (var j=i+1; j<document.forms.F.elements.length; j++) {
		    if (document.forms.F[j].type=='text') {
			f3=document.forms.F[j];
			j=document.forms.F.elements.length+1;
		    }
    		}
	    }
	}
}
function next() {
<?
    if ($subfunc=="browse") echo "	window.opener.location.href=window.opener.location.href;\n";
    else echo "	opener.RAD_focusNextField('".$searchfield."');\n	focusNextField=true;\n";
?>
	self.close();
}
function sel(txt,id) {
    txt=unescape(txt);
    id=unescape(id);
    opener.f1.value = id;
    opener.f2.value = txt;
    next();
}
function selm(txt,id) {
    txt=unescape(txt);    var tmp=','+id+',';
    id=unescape(id);
    var tmp2='*'+opener.f1.value;
    var posi=tmp2.indexOf(tmp);
    if (posi>0) {
	next();
	return;
    }
    opener.f1.value = opener.f1.value+','+id+',';
    opener.f2.value = opener.f2.value+txt+'\n';
}
</SCRIPT>
<?
}

//---------------------------------------------------------------------------
//------------------------- PopUp Search
//---------------------------------------------------------------------------
// OJO if ($V_roi!="") $WHERE=" WHERE ".$V_roi;

if (($subfunc=="browse"||$subfunc=="search_js") && $headeroff!="" && $menuoff!="") {
	echo "</HEAD>\n<BODY onkeypress='captureKey(event)' bgcolor=white>\n";
}

if ($func == "search_js") {
	if (file_exists($RAD_DirBase.$V_mod.".defaults.php")) {
		$TMP="";
		$TMP=include ($RAD_DirBase.$V_mod.".defaults.php");
		if ($TMP!==true && $TMP!="1") echo $TMP;
	}
	list($ptablename, $pfname, $pftitle, $pfilter, $pfieldparent, $porder, $pgroup) = dbdecode($fields[$findex[$searchfieldX]]);
	$arr = explode(",", $pfname);
	if ($arr[0]!="") $keyfield=$arr[0];
	else $keyfield=$pfname;
	$arr = explode(",", $pftitle);
	if ($arr[0]!="") {
	    $litfield=$arr[0];
	    $litfield2=$arr[1];
	    $litfield3=$arr[2];
	} else $litfield=$pfname;
	if (($fields[$findex[$searchfieldX]]->dtype =="bpopupdb"||$fields[$findex[$searchfieldX]]->dtype =="fbpopupdb") && $vlit!="") {
		$searchval="";
	}
	if (($fields[$findex[$searchfieldX]]->dtype =="fpopupdb"||$fields[$findex[$searchfieldX]]->dtype =="fbpopupdb") && $searchfieldX!="" && $searchval!="") {
	    if ($keyfield!="") $WHERE=" WHERE ".$keyfield." LIKE '".$searchval."%'";
	    else $WHERE=" WHERE ".$searchfieldX." LIKE '".$searchval."%'";
	    $cmdSQL="SELECT COUNT(*) FROM $ptablename".$WHERE;
	    if(_SQL_DEBUG!="0") echo RAD_microtime()." SQL (1)query: ".str_replace(",",", ",$cmdSQL)."<br>";
	    $TMP_result=sql_query($cmdSQL,$RAD_dbi);
	    $TMP_row=sql_fetch_array($TMP_result,$RAD_dbi);
	    if ($TMP_row[0]<1) {
		$WHERE="";
		$searchval="";
	    } else $found=$TMP_row[0];
	} else if (($fields[$findex[$searchfieldX]]->dtype =="bpopupdb"||$fields[$findex[$searchfieldX]]->dtype =="fbpopupdb") && $vlit!="") {
	    if ($searchval!="") {
		if ($keyfield!="") $WHERE=" WHERE ".$keyfield." LIKE '".$searchval."%'";
		else $WHERE=" WHERE ".$searchfieldX." LIKE '".$searchval."%'";
	    }
	    if ($WHERE=="") $WHERE=" WHERE $litfield LIKE '".$vlit."%'";
	    else $WHERE.=" AND $litfield LIKE '".$vlit."%'";
	    $cmdSQL="SELECT COUNT(*) FROM $ptablename".$WHERE;
	    if(_SQL_DEBUG!="0") echo RAD_microtime()." SQL (2a)query: ".str_replace(",",", ",$cmdSQL)."<br>";
	    $TMP_result=sql_query($cmdSQL,$RAD_dbi);
	    $TMP_row=sql_fetch_array($TMP_result,$RAD_dbi);
	    if ($TMP_row[0]<1 && ($fields[$findex[$searchfieldX]]->dtype =="fpopupdb"||$fields[$findex[$searchfieldX]]->dtype =="fbpopupdb")) {
		$WHERE="";
		$searchval="";
	    } else $found=$TMP_row[0];
	} else if ($searchfieldX!="" && $searchval!="") {
	    if ($keyfield!="") $WHERE=" WHERE ".$keyfield." LIKE '".$searchval."%'";
	    else $WHERE=" WHERE ".$searchfieldX." LIKE '".$searchval."%'";
	    $cmdSQL="SELECT COUNT(*) FROM $ptablename".$WHERE;
	    if(_SQL_DEBUG!="0") echo RAD_microtime()." SQL (2b)query: ".str_replace(",",", ",$cmdSQL)."<br>";
	    $TMP_result=sql_query($cmdSQL,$RAD_dbi);
	    $TMP_row=sql_fetch_array($TMP_result,$RAD_dbi);
	    if (($TMP_row[0]<1 && ($fields[$findex[$searchfieldX]]->dtype =="fpopupdb"||$fields[$findex[$searchfieldX]]->dtype =="fbpopupdb")) || ($TMP_row[0]<2)) {
		$WHERE="";
		$searchval="";
	    } else $found=$TMP_row[0];
	}
	if ($fields[$findex[$searchfieldX]]->dtype =="popupdbm") {
	    echo "</HEAD>\n<BODY onkeypress='captureKey(event)' onBlur='javascript:window.focus();' bgcolor=white>\n";
	    $TMP_TARGET="HREF";
	} else {
//IE no funciona bien    echo "</HEAD>\n<BODY onkeypress='captureKey(event)' onBlur='javascript:if (cierra) {next();}' bgcolor=white>\n";
//IE no funciona bien    $TMP_TARGET="onClick='javascript:cierra=false;document.location.href";
//	    echo "</HEAD>\n<BODY onkeypress='captureKey(event)' onUnLoad='javascript:if (focusNextField==false) opener.RAD_focusNextField(\"".$searchfield."\");' onLoad='javascript:window.focus();' bgcolor=white>\n";
	    echo "</HEAD>\n<BODY onkeypress='captureKey(event)' onLoad='javascript:window.focus();' bgcolor=white>\n";
	    $TMP_TARGET="HREF";
	}
	echo "<center><b><u>".$fields[$findex[$searchfieldX]]->title."</b></u></center>";
	$arrtmp[0]=""; $arrtmp[1]="";
	$arrtmp = explode(",",$pfname);
	if (!isset($arrtmp[1])) $arrtmp[1] = "";
	if ($WHERE=="" || $param!="" || $arrtmp[1]!="") {
	  if ($arrtmp[1]!="") {
	    $pfname=$arrtmp[0];
	    if ($searchval!="") {
		if ($WHERE=="") $WHERE=" WHERE ".$pfname." LIKE '".$searchval."%'";
		else $WHERE.=" AND ".$pfname." LIKE '".$searchval."%'";
	    }
	    if ($param!="") {
		if ($WHERE=="") $WHERE=" WHERE $arrtmp[1]='$param'";
		else $WHERE.=" AND $arrtmp[1]='$param'";
	    }
	    if (($fields[$findex[$searchfieldX]]->dtype =="bpopupdb"||$fields[$findex[$searchfieldX]]->dtype =="fbpopupdb") && $vlit!="") {
		if ($WHERE=="") $WHERE=" WHERE $litfield LIKE '".$vlit."%'";
		else $WHERE.=" AND $litfield LIKE '".$vlit."%'";
	    }
	    $cmdSQL="SELECT COUNT(*) FROM $ptablename".$WHERE;
	    if(_SQL_DEBUG!="0") echo RAD_microtime()." SQL (3)query: ".str_replace(",",", ",$cmdSQL)."<br>";
	    $TMP_result=sql_query($cmdSQL,$RAD_dbi);
	    $TMP_row=sql_fetch_array($TMP_result,$RAD_dbi);
	    if (($TMP_row[0]<1)||($TMP_row[0]==1 && $fields[$findex[$searchfieldX]]->dtype=="popupdb")) $WHERE=" WHERE $arrtmp[1]='$param'";
	    else $found=$TMP_row[0];
	  } else {
	    if ($searchval!="") {
		if ($WHERE=="") $WHERE=" WHERE ".$pfname." LIKE '".$searchval."%'";
		else $WHERE.=" AND ".$pfname." LIKE '".$searchval."%'";
	    }
	    if (($fields[$findex[$searchfieldX]]->dtype =="bpopupdb"||$fields[$findex[$searchfieldX]]->dtype =="fbpopupdb") && $vlit!="") {
		if ($WHERE=="") $WHERE=" WHERE $litfield LIKE '".$vlit."%'";
		else $WHERE.=" AND $litfield LIKE '".$vlit."%'";
	    }
	    $cmdSQL="SELECT COUNT(*) FROM $ptablename".$WHERE;
	    if(_SQL_DEBUG!="0") echo RAD_microtime()." SQL (4)query: ".str_replace(",",", ",$cmdSQL)."<br>";
	    $TMP_result=sql_query($cmdSQL,$RAD_dbi);
	    $TMP_row=sql_fetch_array($TMP_result,$RAD_dbi);
	    if (($TMP_row[0]<1)||($TMP_row[0]==1 && $fields[$findex[$searchfieldX]]->dtype=="popupdb")) $WHERE=" WHERE $arrtmp[1]='$param'";
	    else $found=$TMP_row[0];
	  }
	}
	if(_SQL_DEBUG!="0") echo RAD_microtime()." SQL (5)query: ".str_replace(",",", ",$cmdSQL)."<br>";
	if ($pfilter!="") {
		if ($WHERE=="") $WHERE=" WHERE ".$pfilter;
		else $WHERE.=" AND (".$pfilter.")";
	}
/////////////////////////////////////////////////////////////////
////	if (empty($found)) {
		$cmdSQL="SELECT COUNT(*) FROM $ptablename".$WHERE;
		$TMP_result=sql_query($cmdSQL,$RAD_dbi);
	        $TMP_row=sql_fetch_array($TMP_result,$RAD_dbi);
		$found=$TMP_row[0];
////	}
//	if ($found<1 && ($fields[$findex[$searchfieldX]]->dtype =="fpopupdb"||$fields[$findex[$searchfieldX]]->dtype =="fbpopupdb") && $searchfieldX!="" && $searchval!="") {
	if ($found<1 && ($fields[$findex[$searchfieldX]]->dtype =="fpopupdb"||$fields[$findex[$searchfieldX]]->dtype =="fbpopupdb") && $searchfieldX!="") {
//		if ($keyfield!="") $WHERE=" WHERE ".$keyfield." LIKE '".$searchval."%'";
//		else $WHERE=" WHERE ".$searchfieldX." LIKE '".$searchval."%'";
		$cmdSQL="SELECT COUNT(*) FROM $ptablename".$WHERE;
		$TMP_result=sql_query($cmdSQL,$RAD_dbi);
	        $TMP_row=sql_fetch_array($TMP_result,$RAD_dbi);
		$found=$TMP_row[0];
	}
	if ($limit == 0) $limit = $found;
//OJO	if ($limit == $rows_limit) $limit = $rows_limit*5;
	if ($fields[$findex[$searchfieldX]]->dtype =="fpopupdb"||$fields[$findex[$searchfieldX]]->dtype =="fbpopupdb") {
	    if ($keyfield==$litfield) {
		if ($litfield3!="") $searchfieldlit=urlencode($litfield2."+".$litfield3);
		else $searchfieldlit=urlencode($litfield2);
	    } else if ($litfield2!="") $searchfieldlit=urlencode($litfield."+".$litfield2);
	    else $searchfieldlit=urlencode($keyfield."+".$litfield);
	} else if ($litfield2!="") {
	    $searchfieldlit=urlencode($litfield."+".$litfield2);
	} else $searchfieldlit=urlencode($litfield);
	if (!isset($start)) $start = 0;
	if ($start=="") $start = 0;
	$url = "$PHP_SELF?func=$func&param=$param&searchfield=$searchfield&searchfieldlit=$searchfieldlit&PHPSESSID=$PHPSESSID";
	$url .= "&found=$found&orderby=$orderby&limit=$limit&start=";
	$tmp=$start+1;
	$position="$tmp-";
	$tmp=$start+$limit;
	if ($tmp>$found) $position.=$found;
	else $position.=$tmp;

	$prevpage = "";
	$nextpage = "";
	if ($start>0) {
		$prevpage .= "<A ACCESSKEY='1' TITLE='ALT+1' $TMP_TARGET=\"$url"."0".$tabURLROI."\";'>"._DEF_NLSStart."</A>";
		if (($start-10*$limit)>-1) {
			$prevpage = "<A ACCESSKEY='2' TITLE='ALT+2' $TMP_TARGET=\"$url".($start-10*$limit).$tabURLROI."\";'>"._DEF_NLSPageBefore."</A>";
		} else {
			if (($start-5*$limit)>-1) {
				$prevpage = "<A ACCESSKEY='2' TITLE='ALT+2' $TMP_TARGET=\"$url".($start-5*$limit).$tabURLROI."\";'>"._DEF_NLSPageBefore."</A>";
			}
		}
		$tmp = max($start-$limit,0);
		if ($tmp>0) $prevpage .= "<A ACCESSKEY='3' TITLE='ALT+3' $TMP_TARGET=\"$url".$tmp.$tabURLROI."\";'>"._DEF_NLSBefore." </A>";
	}
	if ($start + $limit < $found) {
		$nextpage = "<A ACCESSKEY='4' TITLE='ALT+4' $TMP_TARGET=\"$url".($start+$limit).$tabURLROI."\";'>"._DEF_NLSAfter."</A>";
		if (($start+10*$limit)<$found) {
			$nextpage .= "<A ACCESSKEY='5' TITLE='ALT+5' $TMP_TARGET=\"$url".($start+10*$limit).$tabURLROI."\";'>"._DEF_NLSPageAfter."</A>";
		} else {
			if (($start+5*$limit)<$found) {
				$nextpage .= "<A ACCESSKEY='5' TITLE='ALT+5' $TMP_TARGET=\"$url".($start+5*$limit).$tabURLROI."\";'>"._DEF_NLSPageAfter."</A>";
			}
		}
		if ($found>($start+2*$limit)) { $nextpage .= "<A ACCESSKEY='6' TITLE='ALT+6' $TMP_TARGET=\"$url".($found-$limit).$tabURLROI."\";'>"._DEF_NLSEnd."</A>"; }
		else if ($found>($start+$limit)) { $nextpage .= "<A ACCESSKEY='6' TITLE='ALT+6' $TMP_TARGET=\"$url".($start+$limit).$tabURLROI."\";'>"._DEF_NLSEnd."</A>"; }
	}

//	$RAD_menupages = "&nbsp;&nbsp;&nbsp;";
//	if ($limit> 0) {
//		for ($i = 0; $i <= (int)($found/$limit); $i++) {
//			$RAD_menupages .= "&nbsp;\n\t\t<A ACCESSKEY='0' TITLE='ALT+0' $TMP_TARGET=\"$url".($i*$limit).$tabURLROI."\";'>".($i+1)."</A>";
//		}
//	}
/////////////////////////////////////////////////////////////////
//	if ($found>5*$limit) {
	if ($found>$limit) {
		$arrpftitle = explode(",",$pftitle);
		if (count($arrpftitle)<2) $arrpftitle[0]=$pftitle;
		if ($fletter =="") {
			if ($searchval!="") $fletter=substr($searchval,0,1);
			else $fletter="a";
			$cmdSQL="SELECT ".$arrpftitle[0]." FROM $ptablename".$WHERE." ORDER BY ".$arrpftitle[0];
			if(_SQL_DEBUG!="0") echo RAD_microtime()." SQL (5a)query: ".str_replace(",",", ",$cmdSQL)."<br>";
			$TMP_result=sql_query($cmdSQL,$RAD_dbi);
			$TMP_row=sql_fetch_array($TMP_result,$RAD_dbi);
			if ($TMP_row[0]!="") $fletter=substr($TMP_row[0],0,1);
		}
		$limit = $found;
		$start=0;
		if ($fletter !="_") {

		if ($WHERE !="") $WHERE.=" AND ";
		else $WHERE=" WHERE ";
		$WHERE.=$arrpftitle[0]." LIKE '$fletter%'";
	}
	$position = "";
	$prevpage = "";
	$url.="0&searchval=".urlencode($searchval);

	$nextpage = "&nbsp;";
	$nextpage .= "<A ACCESSKEY='A' TITLE='ALT+A' $TMP_TARGET=\"$url"."&fletter=a".$tabURLROI."\";'> A </A>";
	$nextpage .= "<A ACCESSKEY='B' TITLE='ALT+B' $TMP_TARGET=\"$url"."&fletter=b".$tabURLROI."\";'> B </A>";
	$nextpage .= "<A ACCESSKEY='C' TITLE='ALT+C' $TMP_TARGET=\"$url"."&fletter=c".$tabURLROI."\";'> C </A>";
	$nextpage .= "<A ACCESSKEY='D' TITLE='ALT+D' $TMP_TARGET=\"$url"."&fletter=d".$tabURLROI."\";'> D </A>";
	$nextpage .= "<A ACCESSKEY='E' TITLE='ALT+E' $TMP_TARGET=\"$url"."&fletter=e".$tabURLROI."\";'> E </A>";
	$nextpage .= "<A ACCESSKEY='F' TITLE='ALT+F' $TMP_TARGET=\"$url"."&fletter=f".$tabURLROI."\";'> F </A>";
	$nextpage .= "<A ACCESSKEY='G' TITLE='ALT+G' $TMP_TARGET=\"$url"."&fletter=g".$tabURLROI."\";'> G </A>";
	$nextpage .= "<A ACCESSKEY='H' TITLE='ALT+H' $TMP_TARGET=\"$url"."&fletter=h".$tabURLROI."\";'> H </A>";
	$nextpage .= "<A ACCESSKEY='I' TITLE='ALT+I' $TMP_TARGET=\"$url"."&fletter=i".$tabURLROI."\";'> I </A>";
	$nextpage .= "<A ACCESSKEY='J' TITLE='ALT+J' $TMP_TARGET=\"$url"."&fletter=j".$tabURLROI."\";'> J </A>";

	$nextpage .= "<A ACCESSKEY='K' TITLE='ALT+K' $TMP_TARGET=\"$url"."&fletter=k".$tabURLROI."\";'> K </A>";
	$nextpage .= "<A ACCESSKEY='L' TITLE='ALT+L' $TMP_TARGET=\"$url"."&fletter=l".$tabURLROI."\";'> L </A>";
	$nextpage .= "<A ACCESSKEY='M' TITLE='ALT+M' $TMP_TARGET=\"$url"."&fletter=m".$tabURLROI."\";'> M </A>";
	$nextpage .= "<A ACCESSKEY='N' TITLE='ALT+N' $TMP_TARGET=\"$url"."&fletter=n".$tabURLROI."\";'> N </A>";
	$nextpage .= "<A ACCESSKEY='Ñ' TITLE='ALT+Ñ' $TMP_TARGET=\"$url"."&fletter=".urlencode(ñ).$tabURLROI."\";'> &Ntilde; </A>";
	$nextpage .= "<A ACCESSKEY='O' TITLE='ALT+O' $TMP_TARGET=\"$url"."&fletter=o".$tabURLROI."\";'> O </A>";
	$nextpage .= "<A ACCESSKEY='P' TITLE='ALT+P' $TMP_TARGET=\"$url"."&fletter=p".$tabURLROI."\";'> P </A>";
	$nextpage .= "<A ACCESSKEY='Q' TITLE='ALT+Q' $TMP_TARGET=\"$url"."&fletter=q".$tabURLROI."\";'> Q </A>";
	$nextpage .= "<A ACCESSKEY='R' TITLE='ALT+R' $TMP_TARGET=\"$url"."&fletter=r".$tabURLROI."\";'> R </A>";
	$nextpage .= "<A ACCESSKEY='S' TITLE='ALT+S' $TMP_TARGET=\"$url"."&fletter=s".$tabURLROI."\";'> S </A>";
	$nextpage .= "<A ACCESSKEY='T' TITLE='ALT+T' $TMP_TARGET=\"$url"."&fletter=t".$tabURLROI."\";'> T </A>";
	$nextpage .= "<A ACCESSKEY='U' TITLE='ALT+U' $TMP_TARGET=\"$url"."&fletter=u".$tabURLROI."\";'> U </A>";
	$nextpage .= "<A ACCESSKEY='V' TITLE='ALT+V' $TMP_TARGET=\"$url"."&fletter=v".$tabURLROI."\";'> V </A>";
	$nextpage .= "<A ACCESSKEY='W' TITLE='ALT+W' $TMP_TARGET=\"$url"."&fletter=w".$tabURLROI."\";'> W </A>";
	$nextpage .= "<A ACCESSKEY='X' TITLE='ALT+X' $TMP_TARGET=\"$url"."&fletter=x".$tabURLROI."\";'> X </A>";
	$nextpage .= "<A ACCESSKEY='Y' TITLE='ALT+Y' $TMP_TARGET=\"$url"."&fletter=y".$tabURLROI."\";'> Y </A>";
	$nextpage .= "<A ACCESSKEY='Z' TITLE='ALT+Z' $TMP_TARGET=\"$url"."&fletter=z".$tabURLROI."\";'> Z </A>&nbsp;";
	$nextpage .= "<A ACCESSKEY='0' TITLE='ALT+0' $TMP_TARGET=\"$url"."&fletter=0".$tabURLROI."\";'> 0 </A>";
	$nextpage .= "<A ACCESSKEY='1' TITLE='ALT+1' $TMP_TARGET=\"$url"."&fletter=1".$tabURLROI."\";'> 1 </A>";
	$nextpage .= "<A ACCESSKEY='2' TITLE='ALT+2' $TMP_TARGET=\"$url"."&fletter=2".$tabURLROI."\";'> 2 </A>";
	$nextpage .= "<A ACCESSKEY='3' TITLE='ALT+3' $TMP_TARGET=\"$url"."&fletter=3".$tabURLROI."\";'> 3 </A>";
	$nextpage .= "<A ACCESSKEY='4' TITLE='ALT+4' $TMP_TARGET=\"$url"."&fletter=4".$tabURLROI."\";'> 4 </A>";
	$nextpage .= "<A ACCESSKEY='5' TITLE='ALT+5' $TMP_TARGET=\"$url"."&fletter=5".$tabURLROI."\";'> 5 </A>";
	$nextpage .= "<A ACCESSKEY='6' TITLE='ALT+6' $TMP_TARGET=\"$url"."&fletter=6".$tabURLROI."\";'> 6 </A>";
	$nextpage .= "<A ACCESSKEY='7' TITLE='ALT+7' $TMP_TARGET=\"$url"."&fletter=7".$tabURLROI."\";'> 7 </A>";
	$nextpage .= "<A ACCESSKEY='8' TITLE='ALT+8' $TMP_TARGET=\"$url"."&fletter=8".$tabURLROI."\";'> 8 </A>";
	$nextpage .= "<A ACCESSKEY='9' TITLE='ALT+9' $TMP_TARGET=\"$url"."&fletter=9".$tabURLROI."\";'> 9 </A>";
	$nextpage .= "<A ACCESSKEY='_' TITLE='ALT+_' $TMP_TARGET=\"$url"."&fletter=_".$tabURLROI."\";'> [todos]</A>";
	}
/////////// Menu
	echo "<TABLE cellpadding=0 cellspacing=0 class=menu><TR>";
	if ($fields[$findex[$searchfieldX]]->dtype =="popupdbm")
		echo RAD_menu_off("<A ACCESSKEY='C' TITLE='ALT+C' HREF=\"javascript:next();\" class=menuoff>"._DEF_NLSClose."</A>");
	else
		echo RAD_menu_off("<A ACCESSKEY='C' TITLE='ALT+C' HREF=\"javascript:next();\" class=menuoff>"._DEF_NLSCancel."</A>");
	$TMP_mod="";
	$TMP_mods=explode("_",$ptablename);
	for ($ki=1; $ki<count($TMP_mods); $ki++) {
		if ($TMP_mod!="") $TMP_mod.="_";
		$TMP_mod=$TMP_mods[$ki];
	}
	$TMP_linksearch="";
	if ($TMP_mod!="") if (is_modulepermitted("", $V_dir, $TMP_mod)) $TMP_linksearch="'index.php?V_dir=$V_dir&V_mod=$TMP_mod&headeroff=X&footeroff=X&menuoff=&func=searchform&subfunc=search_js&searchfield=$searchfield&searchfieldlit=$searchfieldlit&V_roi=".urlencode($V_roi).$SESSION_SID."'";
	if (is_modulepermitted("", $V_dir, $ptablename)) $TMP_linksearch="'index.php?V_dir=$V_dir&V_mod=$ptablename&headeroff=X&footeroff=X&menuoff=&func=searchform&subfunc=search_js&searchfield=$searchfield&searchfieldlit=$searchfieldlit&V_roi=".urlencode($V_roi).$SESSION_SID."'";
	if ($TMP_linksearch!="") {
    	    echo RAD_menu_off("<A ACCESSKEY='B' TITLE='ALT+B' HREF=\"javascript:salta(".$TMP_linksearch.");\" class=menuoff>"._DEF_NLSSearch."</A>");
	}
	$TMP_linknew="";
	if ($TMP_mod!="") if (is_modulepermitted("", $V_dir, $TMP_mod)) $TMP_linknew="'index.php?V_dir=$V_dir&V_mod=$TMP_mod&headeroff=X&footeroff=X&menuoff=&func=new&subfunc=search_js&searchfield=$searchfield&searchfieldlit=$searchfieldlit&V_roi=".urlencode($V_roi).$SESSION_SID."'";
	if (is_modulepermitted("", $V_dir, $ptablename)) $TMP_linknew="'index.php?V_dir=$V_dir&V_mod=$ptablename&headeroff=X&footeroff=X&menuoff=&func=new&subfunc=search_js&searchfield=$searchfield&searchfieldlit=$searchfieldlit&V_roi=".urlencode($V_roi).$SESSION_SID."'";
	if ($TMP_linknew!="") {
	    echo RAD_menu_off("<A ACCESSKEY='N' TITLE='ALT+N' HREF=\"javascript:salta(".$TMP_linknew.");\" class=menuoff>"._DEF_NLSNew."</A>");
	}
	echo "</TR>\n</TABLE>\n";
/////////// Menu
	echo "<TABLE class=menu>\n";
	echo "<TR><TD class=menuoff>$position [$found]</TD><TD class=menuoff>$prevpage</TD><TD class=menuoff>$nextpage</TD></TR>";
	echo "</TABLE>\n";
	$menuoff = "x";
	switch($dbtype) {
		case "mysql":
		case "":
			if ($start=="") $start = 0;
				$limitstr = "$start,$limit";
				break;
		default:
			if ($start=="") $start = 0;
			$limitstr = "$limit,$start";
		}
	$pftitles = $pftitle;
	$arrpftitle = explode(",",$pftitle);
	if (count($arrpftitle)>1) {
		$pftitle=$arrpftitle[0];
	}
	if (trim($limit)!="") $limitstr="LIMIT ".$limitstr;
	else $limitstr="";
	$cmdSQL="SELECT $pfname, $pftitles FROM $ptablename".$WHERE." ORDER BY $pftitles $limitstr";
	if(_SQL_DEBUG!="0") echo RAD_microtime()." SQL (5b)query: ".str_replace(",",", ",$cmdSQL)."<br>";
	if ($TMP_result=sql_query($cmdSQL,$RAD_dbi)) {
		echo "<table cellpadding=0 cellspacing=4 width=98%>";
	        while($TMP_row=sql_fetch_array($TMP_result,$RAD_dbi)) {
			$id = $TMP_row[$pfname];
			$value = $TMP_row[$pftitle];
			$value=str_replace('"','`',$value);
			$value=str_replace('\'','`',$value);
			$value=str_replace("\n","",$value);
			$value=str_replace("\r","",$value);
			if ($arrpftitle[1]!="") $value2 = $TMP_row[$arrpftitle[1]];
			else $value2 = $TMP_row[$pftitles];
			$value2=str_replace('"','`',$value2);
			$value2=str_replace('\'','`',$value2);
			$value2=str_replace("\n","",$value2);
			$value2=str_replace("\r","",$value2);
			$id=rawurlencode($id);
			$value=rawurlencode($value);
			$value2=rawurlencode($value2);
			if ($fields[$findex[$searchfieldX]]->vdefaultx !="") {
//OJO no porque haya valor por defecto se permite solo este	if ($fields[$findex[$searchfieldX]]->vdefault !="") {
				echo "<script type='text/javascript'>";
				if ($fields[$findex[$searchfieldX]]->dtype =="popupdbm") {
					echo "selm('$value $value2','$id');";
				} else if ($fields[$findex[$searchfieldX]]->dtype =="fpopupdb"||$fields[$findex[$searchfieldX]]->dtype =="fbpopupdb") {
					echo "sel('$value2','$id');";

				} else {
					if ($value2==$value) echo "sel('$value2','$id');";
					else echo "sel('$value $value2','$id');";
				}
				echo "</SCRIPT>\n</HTML>";
				exit;
			} else {
				if ($TMP_class == "bgcolor=white") $TMP_class = "bgcolor=#F0F0F0";
				else $TMP_class = "bgcolor=white";
				if ($fields[$findex[$searchfieldX]]->dtype =="popupdbm") {
					echo "<tr><td $TMP_class><A HREF=\"javascript:selm('$value $value2','$id')\">".urldecode($value)."</A></td>";
					if (count($arrpftitle)>1) {
					    for ($ki=1; $ki<count($arrpftitle); $ki++) {
					        $TMP_value= $TMP_row[$arrpftitle[$ki]];
						$TMP_value=str_replace('"','`',$TMP_value);
					        $TMP_value=str_replace('\'','`',$TMP_value);
						echo "<td $TMP_class><A HREF=\"javascript:selm('$value $value2','$id')\">$TMP_value</A></td>";
					    }
					}
					echo "</tr>";
				} else if ($fields[$findex[$searchfieldX]]->dtype =="fpopupdb"||$fields[$findex[$searchfieldX]]->dtype =="fbpopupdb") {
					echo "<tr><td $TMP_class><A HREF=\"javascript:sel('$value2','$id')\">".urldecode($value)."</A></td>";
					if (count($arrpftitle)>1) {
					    for ($ki=1; $ki<count($arrpftitle); $ki++) {
					        $TMP_value= $TMP_row[$arrpftitle[$ki]];
						$TMP_value=str_replace('"','`',$TMP_value);
					        $TMP_value=str_replace('\'','`',$TMP_value);
						echo "<td $TMP_class><A HREF=\"javascript:sel('$value2','$id')\">$TMP_value</A></td>";
					    }
					}
					echo "</tr>";
				} else {
					if ($value2==$value) echo "<tr><td $TMP_class><A HREF=\"javascript:sel('$value','$id')\">".urldecode($value)."</A></td>";
					else echo "<tr><td $TMP_class><A HREF=\"javascript:sel('$value $value2','$id')\">".urldecode($value)."</A></td>";
					if (count($arrpftitle)>1) {
					    for ($ki=1; $ki<count($arrpftitle); $ki++) {
					        $TMP_value= $TMP_row[$arrpftitle[$ki]];
						$TMP_value=str_replace('"','`',$TMP_value);
					        $TMP_value=str_replace('\'','`',$TMP_value);
						if ($value2==$value) echo "<td $TMP_class><A HREF=\"javascript:sel('$value','$id')\">$TMP_value</A></td>";
						else echo "<td $TMP_class><A HREF=\"javascript:sel('$value $value2','$id')\">$TMP_value</A></td>";
					    }
					}
					echo "</tr>";
				}
			}
			if (($fields[$findex[$searchfieldX]]->dtype =="fpopupdb"||$fields[$findex[$searchfieldX]]->dtype =="fbpopupdb"||$fields[$findex[$searchfieldX]]->dtype =="bpopupdb")&& $found=="1") {
				echo "<script type='text/javascript'>";
				echo "sel('$value2','$id');";
				echo "</SCRIPT>\n</HTML>";
				exit;
			}
//			if (($fields[$findex[$searchfieldX]]->dtype =="popupdb")&& $found=="1") {
//				echo "<script type='text/javascript'>";
//				echo "sel('$value $value2','$id');";
//				echo "</SCRIPT>\n</HTML>";
//				exit;
//			}
		}
		echo "</table>";
	} else {
		$func = "error";
		$RAD_errorstr .= $findex[$searchfieldX]."** ".$cmdSQL." [".sql_errorno($TMP_result)."] ".sql_error($TMP_result);
	}
}
?>
