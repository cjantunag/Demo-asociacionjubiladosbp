<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}

if (_CHARSET=="UTF-8") $vlit=utf8_encode($vlit);
if ($RAD_searchbeginlikepopupdb!="x") $RAD_searchbeginlikepopupdb="%";
else $RAD_searchbeginlikepopupdb="";
if ($func == "search_js") {
	if ($findex[$searchfield]=="" && $findex[$searchfield]!="0") {
		$arrtmpx = explode("_",$searchfield);
		if (!isset($arrtmpx[1])) $arrtmpx[1] = $searchfield;
		for ($ki=1; $ki<count($arrtmpx); $ki++) {
			if ($searchfieldX!="") $searchfieldX.="_";
			$searchfieldX.=$arrtmpx[$ki];
		}
	} else {
		$searchfieldX=$searchfield;
	}
}
if (!isset($RAD_js_echo)) {
    $RAD_js_echo=true;
    if ($func == "search_js" || $subfunc=="search_js" || $subfunc=="browse") {
	echo "
<script>
if (!opener) window.close();
var cierra=true;
if (document.all) ancho=document.body.clientWidth;
else ancho=window.innerWidth;
if (document.all) alto=document.body.clientHeight;
else alto=window.innerHeight;
var anchomax=screen.width;
var altomax=screen.height;
focusNextField=false;\n";
	if ($func == "search_js" && _DEF_POPUP_MARGIN!="SUBMODAL") echo "window.moveTo((anchomax-ancho)/2,(altomax-alto)/2);\n";
	echo "//window.onload = function() {
 document.onkeydown = captureKey;
 document.onkeyup = captureKey;
//}
window.status='Pulse Esc para salir.';
//window.onunload = function(){
window.onbeforeunload = function() {
	if (focusNextField==false) {
//		focusNextField=true;
//		opener.RAD_focusNextField('".$searchfield."');
		window.focus();
	}
	return;
}
function salta(url) {
";
	if (_DEF_POPUP_MARGIN=="SUBMODAL") echo "self.moveTo(0,0);self.resizeTo(screen.availWidth,screen.availHeight);\n";
        echo "window.moveTo(0,0);window.resizeTo(screen.width,screen.height);window.location.href=url;
}
";

echo "
var alerted=false;
function captureKey(e) {
	if (!e) {
		if(window.event) e = window.event;
		else return;
	}
	var keyInfo = String.fromCharCode(e.keyCode) + '';
	keyInfo += 'Event: ' + e.type + '';
	keyInfo += 'You pressed: ' + String.fromCharCode(e['keyCode']) + ' [Decimal: ' + e['keyCode'] + ']';
	keyInfo += 'ALT: ' + e['altKey'] + '';
	keyInfo += 'CTRL: ' + e['ctrlKey'] + '';
	keyInfo += 'SHIFT: ' + e['shiftKey'] + '';
	keyInfo += 'REPEAT: ' + e['repeat'] + '';
	keyInfo += 'WHICH: ' + e['which'];
//	alert(keyInfo);
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
if (typeof f1=="undefined") {
	var f1;
	var f2;
	var f3;
}
if (typeof numdosel=="undefined") var numdosel=0;
function dosel(searchfield,search,res,param) {
	f1 = search;
	f2 = res;
	f3 = search;
    if (selFieldName!=searchfield && selFieldName!=search.name && selFieldName!=res.name) {
	numdosel++;
	if (numdosel>0) { // para evitar bucles en la vuelta del foco antes tenia %2==0
		RAD_setselFieldName(searchfield);
		eval('document.forms.F.'+searchfield+'.focus();');
	} else return;
    }
    if (selFieldName==searchfield) RAD_setselFieldName(search.name);
    eval('val=document.forms.F.'+searchfield+'.value;');
    x=10; y=10;
    if (window.screen) { x=(screen.width-400)/2; y=(screen.height-500)/2; }
    searchpopup = RAD_OpenW("<?=$PHP_SELF?>?func=search_js&searchfield=" + <?
if ($V_roi!="") {
?> escape(searchfield)+"&searchval="+escape(search.value)+"&param="+escape(param)+"&V_roi="+escape(<?=$TMP_V_roi?>)+"&lit="+escape(res.value)+"&vlit="+escape(res)+"&headeroff=x&footeroff=x&V_dir=<?=$V_dir?>&V_mod=<?=$V_mod?>&dbname=<?=$dbname?>&PHPSESSID=<?=$PHPSESSID?>","","width=400,height=500,screenx="+x+",screeny="+y+",scrollbars=yes");
<?
} else {
?> escape(searchfield)+"&searchval="+escape(search.value)+"&param="+escape(param)+"&lit="+escape(res.value)+"&vlit="+escape(res)+"&headeroff=x&footeroff=x&V_dir=<?=$V_dir?>&V_mod=<?=$V_mod?>&dbname=<?=$dbname?>&PHPSESSID=<?=$PHPSESSID?>","","width=400,height=500,screenx="+x+",screeny="+y+",scrollbars=yes");
<?
} ?>
	if (window.focus) { if (searchpopup) searchpopup.focus(); }
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

var threshold;
function setThreshold(valor) {
	threshold=valor;
}
function doThreshold() {
	if (threshold!=null) eval (threshold);
	threshold=null;
}
function RAD_cleanLiteral(elem) {
        destino=elem.name.replace('_literal','');
	destinolit=elem;
        //if (elem.form[destino].value=='' && elem.value!='') setTimeout('destinolit.value=\'\'',100);
}
function next() {
    if (opener) {
<?
    if ($subfunc=="browse") {
?>
	if (window.opener) {
		var urlOpener=window.opener.location.href;
		if (urlOpener.indexOf('#')>0) { urlOpener=urlOpener.substr(0,urlOpener.indexOf('#')); }
		window.opener.location.href=urlOpener;
	} else {
		var urlOpener=window.top.location.href;
		if (urlOpener.indexOf('#')>0) { urlOpener=urlOpener.substr(0,urlOpener.indexOf('#')); }
		window.top.location.href=urlOpener;
	}
<?
    } else echo "	opener.RAD_focusNextField('".$searchfield."');\n	focusNextField=true;\n";
?>
	setTimeout('self.close();',200);
    } else {
<?
    if (_DEF_POPUP_MARGIN=="SUBMODAL") echo "	parent.setTimeout('RAD_focusNextField(\'".$searchfield."\');',50); focusNextField=true; parent.hidePopWin();\n";
    else echo "	parent.RAD_focusNextField('".$searchfield."'); focusNextField=true; parent.RAD_hideL();\n";
?>
    }
}
function closePop() {
	if (stringSTOP!=null) {
		if (stringSTOP!='') { 
			alert(stringSTOP); 
		} 
		return false; 
	} 
<? 
if ($func == "search_js") {
?>
	if (selFieldName!='') {
		parent.RAD_focusField('');
<?

if (function_exists("LR_leerMensajeIdioma")) {
	if (!$fields[$findex[$searchfieldX]]->canbenull) echo "alert('".LR_leerMensajeIdioma("CAMPO","NO_NULL","")."');\n";
}

?>
	}
<? 
}
?>
	parent.hidePopWin();
	return true;
}

function sel(txt,id) {
    txt=unescape(txt);
    id=unescape(id);
    stringSTOP=null;
    if (opener) {
	opener.f1.value = id;
	opener.f2.value = txt;
	if (txt.length>0) {
		opener.f2.readOnly = true;
		literal=opener.f1.name;
		opener.f1.form['B_'+literal].disabled=true;
	}
        if (opener.f2.onchange!=null) opener.f2.onchange();
    } else {
	parent.f1.value = id;
	parent.f2.value = txt;
	if (parent.f1.value!='') {
		parent.f2.readOnly=true;
		literal=parent.f1.name;
		parent.f1.form['B_'+literal].disabled=true;
	}
        if (parent.f2.onchange!=null) parent.f2.onchange();
    }
    next();
}
function selm(txt,id) {
    txt=unescape(txt);    var tmp=','+id+',';
    id=unescape(id);
    if (opener) {
	var tmp2='*'+opener.f1.value;
    } else if (window.top) {
	var tmp2='*'+window.top.f1.value;
    } else {
	var tmp2='*'+parent.top.f1.value;
    }
    var posi=tmp2.indexOf(tmp);
    if (posi>0) {
	next();
	return;
    }
    if (opener) {
	opener.f1.value = opener.f1.value+','+id+',';
	opener.f2.value = opener.f2.value+txt+'\n';
	if (opener.f1.value!='') {
		opener.f2.readOnly = true;
		literal=opener.f1.name;
		opener.f1.form['B_'+literal].disabled=true;
	}
	if (opener.f2.onchange!=null) opener.f2.onchange();
    } else if (window.top) {
	window.top.f1.value = window.top.f1.value+','+id+',';
	window.top.f2.value = window.top.f2.value+txt+'\n';
	if (window.top.f1.value!='') {
		window.top.f2.readOnly=true;
		literal=window.top.f1.name;
		window.top.f1.form['B_'+literal].disabled=true;
	}
	if (window.top.f2.onchange!=null) window.top.f2.onchange();
    } else {
	parent.f1.value = f1.value+','+id+',';
	parent.f2.value = f2.value+txt+'\n';
	if (parent.f1.value!='') {
		parent.f2.readOnly=true;
		literal=parent.f1.name;
		parent.f1.form['B_'+literal].disabled=true;
	}
	if (parent.f2.onchange!=null) parent.f2.onchange();
    }
}
</SCRIPT>
<?
}
//---------------------------------------------------------------------------
//------------------------- PopUp Search
//---------------------------------------------------------------------------
// OJO if ($V_roi!="") $WHERE=" WHERE ".$V_roi;

if (($subfunc=="browse"||$subfunc=="search_js") && $headeroff!="" && $menuoff!="") {
	echo "</HEAD>\n<BODY onkeypress='captureKey(event)' onLoad='javascript:window.focus();' bgcolor=white>\n";
}

if ($func == "search_js") {
	if ($fields[$findex[$searchfieldX]]->dtype =="popupdbm") {
	    echo "</HEAD>\n<BODY onkeypress='captureKey(event)' onBlur='javascript:window.focus();' bgcolor=white>\n";
	    $TMP_TARGET="HREF";
	} else {
//IE no funciona bien    echo "</HEAD>\n<BODY onkeypress='captureKey(event)' onBlur='javascript:if (cierra) {next();}' bgcolor=white>\n";
//IE no funciona bien    $TMP_TARGET="onClick='javascript:cierra=false;document.location.href";
//	    echo "</HEAD>\n<BODY onkeypress='captureKey(event)' onUnLoad='javascript:if (focusNextField==false) opener.RAD_focusNextField(\"".$searchfield."\");' onLoad='javascript:window.focus();' bgcolor=white>\n";
	    //11-sep-2007 echo "</HEAD>\n<BODY onBlur='javascript:this.focus();' onkeypress='captureKey(event)' onLoad='javascript:window.focus();' bgcolor=white>\n";
	    echo "</HEAD>\n<BODY onkeypress='captureKey(event)' onLoad='javascript:window.focus();' bgcolor=white>\n";
	    $TMP_TARGET="HREF";
	}
	//echo "<center><b><u>".$fields[$findex[$searchfieldX]]->title."</b></u></center>";
	echo "<table class=borde><tr><th>".$fields[$findex[$searchfieldX]]->title."</td></th></table>";
	if (file_exists("modules/$V_dir/common.defaults.php")) {
		$TMP=include ("modules/$V_dir/common.defaults.php");
		if ($TMP!==true && $TMP!="1") echo $TMP;
		$TMP="";
	}
	if (file_exists($RAD_DirBase.$V_mod.".defaults.php")) {
		$TMP="";
		$TMP=include ($RAD_DirBase.$V_mod.".defaults.php");
		if ($TMP!==true && $TMP!="1") echo $TMP;
	}
	if ($fields[$findex[$searchfieldX]]->dtype =="popupdbSFF") {
		include("modules/phpRAD/RAD_jsSFF.php");
		return;
	}
	if (trim($fields[$findex[$searchfieldX]]->query)!="" && trim($fields[$findex[$searchfieldX]]->showlistSFF)!="" && trim($fields[$findex[$searchfieldX]]->returnlistSFF)!="") {
		include("modules/phpRAD/RAD_jsSFF.php");
		return;
	}
	list($ptablename, $pfname, $pftitle, $pfilter, $pfieldparent, $porder, $pgroup) = dbdecode($fields[$findex[$searchfieldX]]);
	$arr = explode(",", $pfname);
	if ($newField!='') { $keyfield=$newField; $searchval="%".$searchval; }
	elseif ($arr[1]!="") $keyfield=$arr[1];
	elseif ($arr[0]!="") $keyfield=$arr[0];
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
	    if ($RAD_searchReplaceSpace!="") $TMP_searchval=str_replace(" ","%",$TMP_searchval);
	    else $TMP_searchval=$searchval;
	    if ($keyfield!="") {
		$WHERE=" WHERE ".$keyfield." LIKE '".$RAD_searchbeginlikepopupdb.$TMP_searchval."%'";
		$V_roitmp="$keyfield='$searchval'";
	    }
	    else $WHERE=" WHERE ".$searchfieldX." LIKE '".$RAD_searchbeginlikepopupdb.$TMP_searchval."%'";
	    $cmdSQL="SELECt COUNT(*) FROM $ptablename".$WHERE;
	    $TMP_result=sql_query($cmdSQL,$RAD_dbi);
	    $TMP_row=sql_fetch_array($TMP_result,$RAD_dbi);
	    if ($TMP_row[0]<1) {
		$WHERE="";
		$searchval="";
	    } else $found=$TMP_row[0];
	} else if (($fields[$findex[$searchfieldX]]->dtype =="popupdbtree") && $pfieldparent!='') {
	    $WHERE = " WHERE IFNULL(".$pfieldparent.",0)=0 ";
	    $cmdSQL="SELECT COUNT(*) FROM $ptablename".$WHERE;
	    $TMP_result=sql_query($cmdSQL,$RAD_dbi);
	    $TMP_row=sql_fetch_array($TMP_result,$RAD_dbi);
	    if ($TMP_row[0]<1) {
		$WHERE="";
		$searchval="";
	    } else $found=$TMP_row[0];

			if (_DEF_POPUP_MARGIN!="SUBMODAL") echo "<script>window.resizeTo(screen.availWidth,screen.availHeight);window.moveTo(0,0);</script>";
			require_once("images/xajax/xajax.inc.php");

			function expandTree($parentid,$table,$fieldid,$parentfield,$fieldstitle,$depth,$img,$order){
				global $RAD_dbi;
				ob_end_clean();
				if (ereg("_plus\.gif",$img)) { // Expand
					$TMP_cmd="SELECT $fieldid,$fieldstitle FROM $table WHERE $parentfield='$parentid'";
					if ($order!="") $TMP_cmd.=" ORDER BY $order";
					$TMP_res=sql_query($TMP_cmd,$RAD_dbi);
					$depth++;
					$count=1;
					while ($TMP_row=sql_fetch_row($TMP_res,$RAD_dbi)) {
						if ($count%2==0) $TMP_class="style='background-color:#F0F0F0;'";
						else $TMP_class="style='background-color:#FFFFFF;'";
						$count++;
						$id=$TMP_row[0];
						$res.="<div $TMP_class><span style='margin-left:".($depth*60)."px'>";
						$TMP_cmdaux="SELECT COUNT(*) FROM $table WHERE $parentfield='$id'";
						$TMP_cont=RAD_sqlUniqueResult($TMP_cmdaux);
						if ($TMP_cont>0)
							$res.="<img id='img_$id' src='images/nolines_plus.gif' style='cursor:pointer' onclick=\"javascript:xajax_expandTree('$id','$table','$fieldid','$parentfield','$fieldstitle',$depth,this.src,'$order');\">";
						else
							$res.="<img src='images/pixel.gif' width='18'>";
						$value=$TMP_row[1];
						if ($TMP_row[2]!='') $value.=" ".$TMP_row[2];

						$value=str_replace('"','`',$value);
						$value=str_replace('\'','`',$value);
						$value=str_replace("\n","",$value);
						$value=str_replace("\r","",$value);

						$anchor="<A HREF=\"javascript:sel('$value','$id')\">";
						$res.=$anchor.urldecode($TMP_row[1])."</A></span>";

						for ($i=2;$i<count($TMP_row);$i++) {
							if ($TMP_row[$i]!='')
								$res.="<span $TMP_class> - $anchor $TMP_row[$i] </a></span>";
						}

						$res.= "</div><div id='div_$id'></div>";
					}
					$src=str_replace("_plus","_minus",$img);
				}
				else { // Collapse
					$res="";
					$src=str_replace("_minus","_plus",$img);
				}
				$objResponse = new xajaxResponse();
				$objResponse->outputEntitiesOn();
				$objResponse->setCharEncoding("iso-8859-1");
				$objResponse->addAssign("div_".$parentid,"innerHTML",$res);
				$objResponse->addAssign("img_".$parentid,"src",$src);
				return $objResponse;
			}
	       	$xajax = new xajax();
	       	$xajax->registerFunction("expandTree");
    		$xajax->processRequests();
    		$xajax->printJavascript();


	} else if (($fields[$findex[$searchfieldX]]->dtype =="bpopupdb"||$fields[$findex[$searchfieldX]]->dtype =="fbpopupdb") && $vlit!="") {
	    if ($searchval!="") {
	        if ($RAD_searchReplaceSpace!="") $TMP_searchval=str_replace(" ","%",$TMP_searchval);
	        else $TMP_searchval=$searchval;
		if ($keyfield!="") {
			$WHERE=" WHERE ".$keyfield." LIKE '".$RAD_searchbeginlikepopupdb.$TMP_searchval."%'";
			$V_roitmp="$keyfield='$searchval'";
		}
		else $WHERE=" WHERE ".$searchfieldX." LIKE '".$RAD_searchbeginlikepopupdb.$TMP_searchval."%'";
	    }
	    if ($RAD_searchReplaceSpace!="") $TMP_vlit=str_replace(" ","%",$vlit);
	    else $TMP_vlit=$vlit;
	    if ($WHERE=="") $WHERE=" WHERE $litfield LIKE '".$RAD_searchbeginlikepopupdb.$TMP_vlit."%'";
	    else $WHERE.=" AND $litfield LIKE '".$RAD_searchbeginlikepopupdb.$TMP_vlit."%'";
	    if ($RAD_filterpopupdb!="") $WHERE=" WHERE ".$RAD_filterpopupdb;
	    $cmdSQL="SELEct COUNT(*) FROM $ptablename".$WHERE;
	    $TMP_result=sql_query($cmdSQL,$RAD_dbi);
	    $TMP_row=sql_fetch_array($TMP_result,$RAD_dbi);
	    if ($TMP_row[0]<1 && ($fields[$findex[$searchfieldX]]->dtype =="fpopupdb"||$fields[$findex[$searchfieldX]]->dtype =="fbpopupdb")) {
		$WHERE="";
		$searchval="";
	    } else $found=$TMP_row[0];
	} else if ($searchfieldX!="" && $searchval!="") {
	    if ($RAD_searchReplaceSpace!="") $TMP_searchval=str_replace(" ","%",$TMP_searchval);
	    else $TMP_searchval=$searchval;
	    if ($keyfield!="") {
		$WHERE=" WHERE ".$keyfield." LIKE '".$RAD_searchbeginlikepopupdb.$TMP_searchval."%'";
		$V_roitmp="$keyfield='$searchval'";
	    }
	    else $WHERE=" WHERE ".$searchfieldX." LIKE '".$RAD_searchbeginlikepopupdb.$TMP_searchval."%'";
	    if ($RAD_filterpopupdb!="") $WHERE=" WHERE ".$RAD_filterpopupdb;
	    $cmdSQL="SELect COUNT(*) FROM $ptablename".$WHERE;
	    $TMP_result=sql_query($cmdSQL,$RAD_dbi);
	    $TMP_row=sql_fetch_array($TMP_result,$RAD_dbi);
	    if (($TMP_row[0]<1 && ($fields[$findex[$searchfieldX]]->dtype =="fpopupdb"||$fields[$findex[$searchfieldX]]->dtype =="fbpopupdb" || $fields[$findex[$searchfieldX]]->dtype =="popupdbm"))) {
		$WHERE="";
		$searchval="";
	    } else $found=$TMP_row[0];
	}
	$arrtmp[0]=""; $arrtmp[1]=""; $arrtmp[2]="";
	$arrtmp = explode(",",$pfname);
	if ($arrtmp[0]!="") $pfname=$arrtmp[0];
	if (!isset($arrtmp[1])) $arrtmp[1] = "";
	if (!isset($arrtmp[2])) $arrtmp[2] = "";
	if ($WHERE=="" || ($param!="" && $arrtmp[1]!="")) {
	  if ($arrtmp[1]!="") {
	    if ($searchval!="") {
	        if ($RAD_searchReplaceSpace!="") $TMP_searchval=str_replace(" ","%",$TMP_searchval);
	        else $TMP_searchval=$searchval;
		if ($WHERE=="") $WHERE=" WHERE ".$pfname." LIKE '".$RAD_searchbeginlikepopupdb.$TMP_searchval."%'"; // where por defecto si esta vacio
		//else $WHERE.=" AND ".$pfname." LIKE '".$RAD_searchbeginlikepopupdb.$TMP_searchval."%'";
	    }
	    if ($param!="") {
		//if ($arrtmp[2]!='') $arrtmp[1]=$arrtmp[2];
		if ($WHERE=="") $WHERE=" WHERE $arrtmp[1]='$param'";
		else $WHERE.=" AND $arrtmp[1]='$param'";
		$V_roitmp="$arrtmp[1]='$param'";
	    }
	    if ($param1!="" && $arrtmp[2]!="") {
		if ($WHERE=="") $WHERE=" WHERE $arrtmp[2]='$param1'";
		else $WHERE.=" AND $arrtmp[2]='$param1'";
		$V_roitmp2="$arrtmp[2]='$param1'";
	    }
	    if (($fields[$findex[$searchfieldX]]->dtype =="bpopupdb"||$fields[$findex[$searchfieldX]]->dtype =="fbpopupdb") && $vlit!="") {
		if ($RAD_searchReplaceSpace!="") $TMP_vlit=str_replace(" ","%",$vlit);
		else $TMP_vlit=$vlit;
		if ($WHERE=="") $WHERE=" WHERE $litfield LIKE '".$RAD_searchbeginlikepopupdb.$TMP_vlit."%'";
		else $WHERE.=" AND $litfield LIKE '".$RAD_searchbeginlikepopupdb.$TMP_vlit."%'";
	    }
	    $cmdSQL="SElect COUNT(*) FROM $ptablename".$WHERE;
	    $TMP_result=sql_query($cmdSQL,$RAD_dbi);
	    $TMP_row=sql_fetch_array($TMP_result,$RAD_dbi);
	    if ($param!="" && ($TMP_row[0]<1)||($TMP_row[0]==1 && $fields[$findex[$searchfieldX]]->dtype=="popupdb")) {
		$WHERE=" WHERE $arrtmp[1]='$param'";
		$V_roitmp="$arrtmp[1]='$param'";
	    } else {
	        $found=$TMP_row[0];
	    }
	  } else {
	    if ($searchval!="") {
	        if ($RAD_searchReplaceSpace!="") $TMP_searchval=str_replace(" ","%",$TMP_searchval);
	        else $TMP_searchval=$searchval;
		if ($WHERE=="") $WHERE=" WHERE ".$pfname." LIKE '".$RAD_searchbeginlikepopupdb.$TMP_searchval."%'";
		else $WHERE.=" AND ".$pfname." LIKE '".$RAD_searchbeginlikepopupdb.$TMP_searchval."%'";
	    }
	    if (($fields[$findex[$searchfieldX]]->dtype =="bpopupdb"||$fields[$findex[$searchfieldX]]->dtype =="fbpopupdb") && $vlit!="") {
		if ($RAD_searchReplaceSpace!="") $TMP_vlit=str_replace(" ","%",$vlit);
		else $TMP_vlit=$vlit;
		if ($WHERE=="") $WHERE=" WHERE $litfield LIKE '".$RAD_searchbeginlikepopupdb.$TMP_vlit."%'";
		else $WHERE.=" AND $litfield LIKE '".$RAD_searchbeginlikepopupdb.$TMP_vlit."%'";
	    }
	    $cmdSQL="Select COUNT(*) FROM $ptablename".$WHERE;
	    $TMP_result=sql_query($cmdSQL,$RAD_dbi);
	    $TMP_row=sql_fetch_array($TMP_result,$RAD_dbi);
	    if ($param!="" && ($TMP_row[0]<1)||($TMP_row[0]==1 && $fields[$findex[$searchfieldX]]->dtype=="popupdb")) {
		$WHERE=" WHERE $arrtmp[1]='$param'";
		$V_roitmp="$arrtmp[1]='$param'";
	    }
	    else $found=$TMP_row[0];
	  }
	}
	if (trim($WHERE)=="WHERE =''") $WHERE="";
	if ($RAD_filterpopupdb!="") {
		if ($pfilter=="" || $pfilter==$RAD_filterpopupdb) $pfilter=$RAD_filterpopupdb;
		else $pfilter="(".$pfilter.") AnD (".$RAD_filterpopupdb.")";
	}
	if ($pfilter!="") {
		if ($WHERE=="") $WHERE=" WHERE ".$pfilter;
		else if ($WHERE!=" WHERE ".$pfilter) $WHERE.=" ANd (".$pfilter.")";
	}
	$TMP_group=""; $TMP_order="";
	if ($pgroup!="") $TMP_group=" GROUP BY ".$pgroup;
	if ($RAD_orderpopup!="") $TMP_order=" ORDER BY ".$RAD_orderpopup;
	else if ($porder!="") $TMP_order=" ORDER BY ".$porder;
/////////////////////////////////////////////////////////////////
////	if (empty($found)) {
		$cmdSQL="sELECT COUNT(*) FROM $ptablename".$WHERE;
		$TMP_result=sql_query($cmdSQL,$RAD_dbi);
	        $TMP_row=sql_fetch_array($TMP_result,$RAD_dbi);
		$found=$TMP_row[0];
////	}
//	if ($found<1 && ($fields[$findex[$searchfieldX]]->dtype =="fpopupdb"||$fields[$findex[$searchfieldX]]->dtype =="fbpopupdb") && $searchfieldX!="" && $searchval!="") {
	if ($found<1 && ($fields[$findex[$searchfieldX]]->dtype =="fpopupdb"||$fields[$findex[$searchfieldX]]->dtype =="fbpopupdb") && $searchfieldX!="") {
	        if ($RAD_searchReplaceSpace!="") $TMP_searchval=str_replace(" ","%",$TMP_searchval);
	        else $TMP_searchval=$searchval;
//		if ($keyfield!="") $WHERE=" WHERE ".$keyfield." LIKE '".$RAD_searchbeginlikepopupdb.$TMP_searchval."%'";
//		else $WHERE=" WHERE ".$searchfieldX." LIKE '".$RAD_searchbeginlikepopupdb.$TMP_searchval."%'";
		$cmdSQL="seLECT COUNT(*) FROM $ptablename".$WHERE;
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
	$url = "$PHP_SELF?func=$func&param=$param&searchfield=$searchfield&vlit=$vlit&searchfieldlit=$searchfieldlit&PHPSESSID=$PHPSESSID";
	if ($dbname!=_DEF_dbname) $url .= "&dbname=$dbname";
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
			$prevpage .= "<A ACCESSKEY='2' TITLE='ALT+2' $TMP_TARGET=\"$url".($start-10*$limit).$tabURLROI."\";'>"._DEF_NLSPageBefore."</A>";
		} else {
			if (($start-5*$limit)>-1) {
				$prevpage .= "<A ACCESSKEY='2' TITLE='ALT+2' $TMP_TARGET=\"$url".($start-5*$limit).$tabURLROI."\";'>"._DEF_NLSPageBefore."</A>";
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
		if ($found>($start+$limit)) { $nextpage .= "<A ACCESSKEY='6' TITLE='ALT+6' $TMP_TARGET=\"$url".($found-$limit).$tabURLROI."\";'>"._DEF_NLSEnd."</A>"; }
	}

//	$RAD_menupages = "&nbsp;&nbsp;&nbsp;";
//	if ($limit> 0) {
//		for ($i = 0; $i <= (int)($found/$limit); $i++) {
//			$RAD_menupages .= "&nbsp;\n\t\t<A ACCESSKEY='0' TITLE='ALT+0' $TMP_TARGET=\"$url".($i*$limit).$tabURLROI."\";'>".($i+1)."</A>";
//		}
//	}
/////////////////////////////////////////////////////////////////
	if ($RAD_menusearchoff!="") {
		$limit=$found;
	}
//	if ($found>5*$limit) {
	if ($found>$limit && _DEF_popABC==1) {
		$arrpftitle = explode(",",$pftitle);
		if (count($arrpftitle)<2) $arrpftitle[0]=$pftitle;
		if ($fletter =="") {
			if ($searchval!="") $fletter=substr($searchval,0,1);
			else $fletter="a";
			$cmdSQL="selECT ".$arrpftitle[0]." FROM $ptablename".$WHERE.$TMP_group." ORDER BY ".$arrpftitle[0];
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

        if ($nofilter!="x") {
            $tabURLROI=RAD_delParamURL($tabURLROI, "V_roi");
            $tabURLROI.="&V_roi=".str_replace("\'","",$_REQUEST[V_roi]);
        }else{
            $tabURLROI.="&nofilter=x";
            $tabURLROI=RAD_delParamURL($tabURLROI, "V_roi");
            $tabURLROI.="&V_roi=".str_replace("\'","",$_REQUEST[V_roi]);
        }

	$nextpage = "&nbsp;";
	$nextpage .= "<A class='menuoff' ACCESSKEY='A' TITLE='ALT+A' $TMP_TARGET=\"$url"."&fletter=a".$tabURLROI."\";'> A </A>";
	$nextpage .= "<A class='menuoff' ACCESSKEY='B' TITLE='ALT+B' $TMP_TARGET=\"$url"."&fletter=b".$tabURLROI."\";'> B </A>";
	$nextpage .= "<A class='menuoff' ACCESSKEY='C' TITLE='ALT+C' $TMP_TARGET=\"$url"."&fletter=c".$tabURLROI."\";'> C </A>";
	$nextpage .= "<A class='menuoff' ACCESSKEY='D' TITLE='ALT+D' $TMP_TARGET=\"$url"."&fletter=d".$tabURLROI."\";'> D </A>";
	$nextpage .= "<A class='menuoff' ACCESSKEY='E' TITLE='ALT+E' $TMP_TARGET=\"$url"."&fletter=e".$tabURLROI."\";'> E </A>";
	$nextpage .= "<A class='menuoff' ACCESSKEY='F' TITLE='ALT+F' $TMP_TARGET=\"$url"."&fletter=f".$tabURLROI."\";'> F </A>";
	$nextpage .= "<A class='menuoff' ACCESSKEY='G' TITLE='ALT+G' $TMP_TARGET=\"$url"."&fletter=g".$tabURLROI."\";'> G </A>";
	$nextpage .= "<A class='menuoff' ACCESSKEY='H' TITLE='ALT+H' $TMP_TARGET=\"$url"."&fletter=h".$tabURLROI."\";'> H </A>";
	$nextpage .= "<A class='menuoff' ACCESSKEY='I' TITLE='ALT+I' $TMP_TARGET=\"$url"."&fletter=i".$tabURLROI."\";'> I </A>";
	$nextpage .= "<A class='menuoff' ACCESSKEY='J' TITLE='ALT+J' $TMP_TARGET=\"$url"."&fletter=j".$tabURLROI."\";'> J </A>";

	$nextpage .= "<A class='menuoff' ACCESSKEY='K' TITLE='ALT+K' $TMP_TARGET=\"$url"."&fletter=k".$tabURLROI."\";'> K </A>";
	$nextpage .= "<A class='menuoff' ACCESSKEY='L' TITLE='ALT+L' $TMP_TARGET=\"$url"."&fletter=l".$tabURLROI."\";'> L </A>";
	$nextpage .= "<A class='menuoff' ACCESSKEY='M' TITLE='ALT+M' $TMP_TARGET=\"$url"."&fletter=m".$tabURLROI."\";'> M </A>";
	$nextpage .= "<A class='menuoff' ACCESSKEY='N' TITLE='ALT+N' $TMP_TARGET=\"$url"."&fletter=n".$tabURLROI."\";'> N </A>";
	$nextpage .= "<A class='menuoff' ACCESSKEY='Ñ' TITLE='ALT+Ñ' $TMP_TARGET=\"$url"."&fletter=".urlencode(ñ).$tabURLROI."\";'> &Ntilde; </A>";
	$nextpage .= "<A class='menuoff' ACCESSKEY='O' TITLE='ALT+O' $TMP_TARGET=\"$url"."&fletter=o".$tabURLROI."\";'> O </A>";
	$nextpage .= "<A class='menuoff' ACCESSKEY='P' TITLE='ALT+P' $TMP_TARGET=\"$url"."&fletter=p".$tabURLROI."\";'> P </A>";
	$nextpage .= "<A class='menuoff' ACCESSKEY='Q' TITLE='ALT+Q' $TMP_TARGET=\"$url"."&fletter=q".$tabURLROI."\";'> Q </A>";
	$nextpage .= "<A class='menuoff' ACCESSKEY='R' TITLE='ALT+R' $TMP_TARGET=\"$url"."&fletter=r".$tabURLROI."\";'> R </A>";
	$nextpage .= "<A class='menuoff' ACCESSKEY='S' TITLE='ALT+S' $TMP_TARGET=\"$url"."&fletter=s".$tabURLROI."\";'> S </A>";
	$nextpage .= "<A class='menuoff' ACCESSKEY='T' TITLE='ALT+T' $TMP_TARGET=\"$url"."&fletter=t".$tabURLROI."\";'> T </A>";
	$nextpage .= "<A class='menuoff' ACCESSKEY='U' TITLE='ALT+U' $TMP_TARGET=\"$url"."&fletter=u".$tabURLROI."\";'> U </A>";
	$nextpage .= "<A class='menuoff' ACCESSKEY='V' TITLE='ALT+V' $TMP_TARGET=\"$url"."&fletter=v".$tabURLROI."\";'> V </A>";
	$nextpage .= "<A class='menuoff' ACCESSKEY='W' TITLE='ALT+W' $TMP_TARGET=\"$url"."&fletter=w".$tabURLROI."\";'> W </A>";
	$nextpage .= "<A class='menuoff' ACCESSKEY='X' TITLE='ALT+X' $TMP_TARGET=\"$url"."&fletter=x".$tabURLROI."\";'> X </A>";
	$nextpage .= "<A class='menuoff' ACCESSKEY='Y' TITLE='ALT+Y' $TMP_TARGET=\"$url"."&fletter=y".$tabURLROI."\";'> Y </A>";
	$nextpage .= "<A class='menuoff' ACCESSKEY='Z' TITLE='ALT+Z' $TMP_TARGET=\"$url"."&fletter=z".$tabURLROI."\";'> Z </A>&nbsp;";
	$nextpage .= "<A class='menuoff' ACCESSKEY='0' TITLE='ALT+0' $TMP_TARGET=\"$url"."&fletter=0".$tabURLROI."\";'> 0 </A>";
	$nextpage .= "<A class='menuoff' ACCESSKEY='1' TITLE='ALT+1' $TMP_TARGET=\"$url"."&fletter=1".$tabURLROI."\";'> 1 </A>";
	$nextpage .= "<A class='menuoff' ACCESSKEY='2' TITLE='ALT+2' $TMP_TARGET=\"$url"."&fletter=2".$tabURLROI."\";'> 2 </A>";
	$nextpage .= "<A class='menuoff' ACCESSKEY='3' TITLE='ALT+3' $TMP_TARGET=\"$url"."&fletter=3".$tabURLROI."\";'> 3 </A>";
	$nextpage .= "<A class='menuoff' ACCESSKEY='4' TITLE='ALT+4' $TMP_TARGET=\"$url"."&fletter=4".$tabURLROI."\";'> 4 </A>";
	$nextpage .= "<A class='menuoff' ACCESSKEY='5' TITLE='ALT+5' $TMP_TARGET=\"$url"."&fletter=5".$tabURLROI."\";'> 5 </A>";
	$nextpage .= "<A class='menuoff' ACCESSKEY='6' TITLE='ALT+6' $TMP_TARGET=\"$url"."&fletter=6".$tabURLROI."\";'> 6 </A>";
	$nextpage .= "<A class='menuoff' ACCESSKEY='7' TITLE='ALT+7' $TMP_TARGET=\"$url"."&fletter=7".$tabURLROI."\";'> 7 </A>";
	$nextpage .= "<A class='menuoff' ACCESSKEY='8' TITLE='ALT+8' $TMP_TARGET=\"$url"."&fletter=8".$tabURLROI."\";'> 8 </A>";
	$nextpage .= "<A class='menuoff' ACCESSKEY='9' TITLE='ALT+9' $TMP_TARGET=\"$url"."&fletter=9".$tabURLROI."\";'> 9 </A>";
	$nextpage .= "<A class='menuoff' ACCESSKEY='_' TITLE='ALT+_' $TMP_TARGET=\"$url"."&fletter=_".$tabURLROI."\";'> [todos]</A>";
	}
/////////// Menu
   if ($RAD_menusearchoff=="") {
	echo "<TABLE cellpadding=0 cellspacing=0 class=menu><TR>";
	if ($fields[$findex[$searchfieldX]]->dtype =="popupdbm"){
		setSessionVar("SESSION_RAD_".$searchfieldX."_jscallback","selm");
		echo RAD_menu_off("<A ACCESSKEY='C' TITLE='ALT+C' HREF=\"javascript:next();\" class=menuoff>"._DEF_NLSClose."</A>");
	}
	else {
		setSessionVar("SESSION_RAD_".$searchfieldX."_jscallback","sel");
		echo RAD_menu_off("<A ACCESSKEY='C' TITLE='ALT+C' HREF=\"javascript:next();\" class=menuoff>"._DEF_NLSCancel."</A>");
	}
	$TMP_mod="";
	$TMP_mods=explode("_",$ptablename);
	for ($ki=1; $ki<count($TMP_mods); $ki++) {
		if ($TMP_mod!="") $TMP_mod.="_";
		$TMP_mod=$TMP_mods[$ki];
	}
	$TMP_linksearch="";
	$TMP_linknew="";
	if ($V_roitmp!='') $V_roitmp=urlencode($V_roitmp);
	else $V_roitmp=urlencode($V_roi);
	if (is_modulepermitted("", $V_dir, $ptablename)) {
		$TMP_linksearch="'index.php?V_dir=$V_dir&V_mod=$ptablename&dbname=$dbname&headeroff=X&footeroff=X&menuoff=&func=searchform&subfunc=search_js&searchfield=$searchfield&searchfieldlit=$searchfieldlit&V_roi=".urlencode($V_roitmp).$SESSION_SID."'";
		$TMP_linknew="'index.php?V_dir=$V_dir&V_mod=$ptablename&dbname=$dbname&headeroff=X&footeroff=X&menuoff=&func=new&subfunc=search_js&searchfield=$searchfield&searchfieldlit=$searchfieldlit&V_roi=".urlencode($V_roitmp).$SESSION_SID."'";
	}
	if ($TMP_mod!="" && $TMP_linksearch=="") {
		if (is_modulepermitted("", $V_dir, $TMP_mod)) {
			$TMP_linksearch="'index.php?V_dir=$V_dir&V_mod=$TMP_mod&dbname=$dbname&headeroff=X&footeroff=X&menuoff=&func=searchform&subfunc=search_js&searchfield=$searchfield&searchfieldlit=$searchfieldlit&V_roi=".urlencode($V_roitmp).$SESSION_SID."'";
			$TMP_linknew="'index.php?V_dir=$V_dir&V_mod=$TMP_mod&dbname=$dbname&headeroff=X&footeroff=X&menuoff=&func=new&subfunc=search_js&searchfield=$searchfield&searchfieldlit=$searchfieldlit&V_roi=".urlencode($V_roitmp).$SESSION_SID."'";
		}
	}
	if ($TMP_linksearch!="") {
	    $TMP_linksearch=str_replace("%27","",$TMP_linksearch);
    	    echo RAD_menu_off("<A ACCESSKEY='B' TITLE='ALT+B' HREF=\"javascript:salta(".$TMP_linksearch.");\" class=menuoff>"._DEF_NLSSearch."</A>");
	}
	if ($TMP_linknew!="") {
	    $TMP_linknew=str_replace("%27","",$TMP_linknew);
	    echo RAD_menu_off("<A ACCESSKEY='N' TITLE='ALT+N' HREF=\"javascript:salta(".$TMP_linknew.");\" class=menuoff>"._DEF_NLSNew."</A>");
	}
	echo "</TR>\n</TABLE>\n";
/////////// Menu
	echo "<TABLE class=menu>\n";
	echo "<TR><TD class=menuoff>$position [$found]</TD>";
	if ($prevpage!="") echo "<TD class=menuoff>$prevpage</TD>";
	if ($nextpage!="") echo "<TD class=menuoff>$nextpage</TD></TR>";
	echo "</TR>";
	echo "</TABLE>\n";
   }
	$menuoff = "x";
	switch($dbtype) {
		case "oracle":
			if ($start=="") $start = 0;
			$limitstr = " AND ROWNUM>$start AND ROWNUM<=$limit ";
			break;
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
	if ($dbtype=="mysql") if (trim($limit)!="") $limitstr="LIMIT ".$limitstr;
	else $limitstr="";
	if (trim($WHERE)=="WHERE =''") $WHERE="";

	$TMP_fields=str_replace(" ","",$pftitles);
	if (ereg(",".trim($pfname)."," , ",".$TMP_fields.",")) $TMP_fields=$pftitles;
	else $TMP_fields=$pfname.", ".$pftitles;
	$cmdSQL="seleCT ".$TMP_fields." FROM $ptablename".$WHERE;
	if ($dbtype=="oracle") {
		if ($WHERE=="" && $limitstr!="") $limitstr=" WHeRE ".substr($limiststr,4);
		$cmdSQL.=$limiststr.$TMP_group." ORDER BY $pftitles";
	} else {
		$cmdSQL.=$TMP_group;
		if ($RAD_orderpopup!="") $cmdSQL.=" ORDER BY ".$RAD_orderpopup;
		else $cmdSQL.=" ORDER BY $pftitles ";
		$cmdSQL.=" $limitstr";
	}
	if ($TMP_result=sql_query($cmdSQL,$RAD_dbi)) {
		echo "<table cellpadding=0 cellspacing=4 width=98%>";
	        while($TMP_row=sql_fetch_array($TMP_result,$RAD_dbi)) {
			$id = $TMP_row[$pfname];
//if (is_admin()) echo $id."*".$pfname."*".$cmdSQL."<br>";
			$value = $TMP_row[$pftitle];
			$value=str_replace('"','`',$value);
			$value=str_replace('\'','`',$value);
			$value=str_replace("\n","",$value);
			$value=str_replace("\r","",$value);
			$value2="";
			if ($arrpftitle[1]!="") {
				if ($fields[$findex[$searchfieldX]]->dtype=="popupdbm" && $arrpftitle[1]==$pftitle) {
					$value2="";
				} else {
					$fvalue2 = $arrpftitle[1];
					$value2 = $TMP_row[$arrpftitle[1]];
				}
			} else {
				if ($fields[$findex[$searchfieldX]]->dtype=="popupdbm" && $pftitles==$pftitle) {
					$value2="";
				} else {
					$fvalue2 = $pftitles;
					$value2 = $TMP_row[$pftitles];
				}
			}
			$value2=str_replace('"','`',$value2);
			$value2=str_replace('\'','`',$value2);
			$value2=str_replace("\n","",$value2);
			$value2=str_replace("\r","",$value2);
			$id=rawurlencode($id);
			$selvalue=rawurlencode(utf8_decode($value));
			$selvalue2=$value2;
			////// ?? $selvalue2=rawurlencode(utf8_decode($value2));
			if ($fields[$findex[$searchfieldX]]->vdefaultx !="") {
//OJO no porque haya valor por defecto se permite solo este	if ($fields[$findex[$searchfieldX]]->vdefault !="") {
				echo "<script type='text/javascript'>";
				if ($fields[$findex[$searchfieldX]]->dtype =="popupdbm") {
					echo "selm('$selvalue $selvalue2','$id');";
				} else if ($fields[$findex[$searchfieldX]]->dtype =="fpopupdb"||$fields[$findex[$searchfieldX]]->dtype =="fbpopupdb") {
					echo "sel('$selvalue2','$id');";
				} else {
					if ($value2==$value) echo "sel('$selvalue2','$id');";
					else echo "sel('$selvalue $selvalue2','$id');";
				}
				echo "</SCRIPT>\n</HTML>";
				exit;
			} else {
				if ($TMP_class == "style='background-color:#FFFFFF; padding-left:10px; padding-right:10px;'") $TMP_class = "style='background-color:#F0F0F0; padding-left:10px; padding-right:10px;'";
				else $TMP_class = "style='background-color:#FFFFFF; padding-left:10px; padding-right:10px;'";
				if ($fields[$findex[$searchfieldX]]->dtype =="popupdbm") {
					if ($RAD_popuplinkcolumn!="" && $RAD_popuplinkcolumn!=$searchfieldX) echo "<tr><td $TMP_class>".$value."</td>";
					else echo "<tr><td $TMP_class><A HREF=\"javascript:selm('$selvalue $selvalue2','$id')\">".$value."</A></td>";
					if (count($arrpftitle)>1) {
					    for ($ki=1; $ki<count($arrpftitle); $ki++) {
					        $TMP_value= $TMP_row[$arrpftitle[$ki]];
						$TMP_value=str_replace('"','`',$TMP_value);
					        $TMP_value=str_replace('\'','`',$TMP_value);
						if ($RAD_popuplinkcolumn!="" && $RAD_popuplinkcolumn!=$arrpftitle[$ki]) echo "<td $TMP_class>$TMP_value</td>";
						else echo "<td $TMP_class><A HREF=\"javascript:selm('$selvalue $selvalue2','$id')\">$TMP_value</A></td>";
					    }
					}
					echo "</tr>";
				} else if ($fields[$findex[$searchfieldX]]->dtype =="popupdbtree") {
					$TMP_cont_++;
					if ($TMP_cont_%2==1) $TMP_class = "style='background-color:#FFFFFF'";
					else  $TMP_class = "style='background-color:#F0F0F0'";
					echo "<div $TMP_class><span $TMP_class><img id='img_$id' src='images/nolines_plus.gif' style='cursor:pointer' onclick=\"javascript:xajax_expandTree('$id','$ptablename','$pfname','$pfieldparent','$pftitles',0,this.src,'$porder');\">";
					if ($RAD_popuplinkcolumn!="" && $RAD_popuplinkcolumn!=$fvalue2) {
						if ($value2==$value) echo "".$value."</span>";
						else echo "".$value."</span>";
					} else {
						if ($value2==$value) echo "<A HREF=\"javascript:sel('$selvalue','$id')\">".$value."</A></span>";
						else echo "<A HREF=\"javascript:sel('$selvalue $selvalue2','$id')\">".$value."</A></span>";
					}
					if (count($arrpftitle)>1) {
					    for ($ki=1; $ki<count($arrpftitle); $ki++) {
					        $TMP_value= $TMP_row[$arrpftitle[$ki]];
						$TMP_value=str_replace('"','`',$TMP_value);
					        $TMP_value=str_replace('\'','`',$TMP_value);
						if ($RAD_popuplinkcolumn!="" && $RAD_popuplinkcolumn!=$arrpftitle[$ki]) {
							if ($value2==$value) echo "<span $TMP_class> - $TMP_value</span>";
							else if ($value2!='')  echo "<span $TMP_class> - $TMP_value</span>";
						} else {
							if ($value2==$value) echo "<span $TMP_class> - <A HREF=\"javascript:sel('$selvalue','$id')\">$TMP_value</A></span>";
							else if ($value2!='')  echo "<span $TMP_class> - <A HREF=\"javascript:sel('$selvalue $selvalue2','$id')\">$TMP_value</A></span>";
						}
					    }
					}
					echo "</div><div id='div_$id'></div>";
					/*
					echo "<tr><td $TMP_class><img id='img_$id' src='images/nolines_plus.gif' style='cursor:pointer' onclick=\"javascript:xajax_expandTree('$id','$ptablename','$pfname','$pfieldparent','$pftitles',0,this.src);\">";
					if ($RAD_popuplinkcolumn!="" && $RAD_popuplinkcolumn!=$searchfieldX) {
						if ($value2==$value) echo "".$value."</td>";
						else echo "".$value."</td>";
					} else {
						if ($value2==$value) echo "<A HREF=\"javascript:sel('$selvalue','$id')\">".$value."</A></td>";
						else echo "<A HREF=\"javascript:sel('$selvalue $selvalue2','$id')\">".$value."</A></td>";
					}

					if (count($arrpftitle)>1) {
					    for ($ki=1; $ki<count($arrpftitle); $ki++) {
					        $TMP_value= $TMP_row[$arrpftitle[$ki]];
						$TMP_value=str_replace('"','`',$TMP_value);
					        $TMP_value=str_replace('\'','`',$TMP_value);
						if ($RAD_popuplinkcolumn!="" && $RAD_popuplinkcolumn!=$arrpftitle[$ki]) {
							if ($value2==$value) echo "<td $TMP_class>$TMP_value</td>";
							else echo "<td $TMP_class>$TMP_value</td>";
						} else {
							if ($value2==$value) echo "<td $TMP_class><A HREF=\"javascript:sel('$selvalue','$id')\">$TMP_value</A></td>";
							else echo "<td $TMP_class><A HREF=\"javascript:sel('$selvalue $selvalue2','$id')\">$TMP_value</A></td>";
						}
					    }
					}
					echo "</tr><tbody width=100% id='div_$id'></tbody>";
					*/
				} else if ($fields[$findex[$searchfieldX]]->dtype =="fpopupdb"||$fields[$findex[$searchfieldX]]->dtype =="fbpopupdb") {
					if ($RAD_popuplinkcolumn!="" && $RAD_popuplinkcolumn!=$searchfieldX) {
						echo "<tr><td $TMP_class>".$value."</td>";
					} else {
						echo "<tr><td $TMP_class><A HREF=\"javascript:sel('$selvalue2','$id')\">".$value."</A></td>";
					}
					if (count($arrpftitle)>1) {
					    for ($ki=1; $ki<count($arrpftitle); $ki++) {
					        $TMP_value= $TMP_row[$arrpftitle[$ki]];
						$TMP_value=str_replace('"','`',$TMP_value);
					        $TMP_value=str_replace('\'','`',$TMP_value);
						if ($RAD_popuplinkcolumn!="" && $RAD_popuplinkcolumn!=$arrpftitle[$ki]) {
							echo "<td $TMP_class>$TMP_value</td>";
						} else {
							echo "<td $TMP_class><A HREF=\"javascript:sel('$selvalue2','$id')\">$TMP_value</A></td>";
						}
					    }
					}
					echo "</tr>";
				} else {
					if ($RAD_popuplinkcolumn!="" && $RAD_popuplinkcolumn!=$searchfieldX) {
						if ($value2==$value) echo "<tr><td $TMP_class>".$value."</td>";
						else echo "<tr><td $TMP_class>".$value."</td>";
					} else {
						if ($value2==$value) echo "<tr><td $TMP_class><A HREF=\"javascript:sel('$selvalue','$id')\">".$value."</A></td>";
						else echo "<tr><td $TMP_class><A HREF=\"javascript:sel('$selvalue $selvalue2','$id')\">".$value."</A></td>";
					}
					if (count($arrpftitle)>1) {
					    for ($ki=1; $ki<count($arrpftitle); $ki++) {
					        $TMP_value= $TMP_row[$arrpftitle[$ki]];
						$TMP_value=str_replace('"','`',$TMP_value);
					        $TMP_value=str_replace('\'','`',$TMP_value);
						if ($RAD_popuplinkcolumn!="" && $RAD_popuplinkcolumn!=$arrpftitle[$ki]) {
							if ($value2==$value) echo "<td $TMP_class>$TMP_value</td>";
							else echo "<td $TMP_class>$TMP_value</td>";
						} else {
							if ($value2==$value) echo "<td $TMP_class><A HREF=\"javascript:sel('$selvalue','$id')\">$TMP_value</A></td>";
							else echo "<td $TMP_class><A HREF=\"javascript:sel('$selvalue $selvalue2','$id')\">$TMP_value</A></td>";
						}
					    }
					}
					echo "</tr>";
				}
			}
			if (($fields[$findex[$searchfieldX]]->dtype =="fpopupdb"||$fields[$findex[$searchfieldX]]->dtype =="fbpopupdb"||$fields[$findex[$searchfieldX]]->dtype =="bpopupdb")&& $found=="1") {
				echo "<script type='text/javascript'>";
				if ($fields[$findex[$searchfieldX]]->dtype =="bpopupdb" && $value!=$value2) echo "sel('$selvalue $selvalue2','$id');";
				else echo "sel('$selvalue2','$id');";
				echo "</SCRIPT>\n</HTML>";
				exit;
			}
//			if (($fields[$findex[$searchfieldX]]->dtype =="popupdb")&& $found=="1") {
//				echo "<script type='text/javascript'>";
//				echo "sel('$selvalue $selvalue2','$id');";
//				echo "</SCRIPT>\n</HTML>";
//				exit;
//			}
		}
		echo "</table><br><br>";
	} else {
		$func = "error";
		$RAD_errorstr .= $findex[$searchfieldX]."** ".$cmdSQL." [".sql_errorno($TMP_result)."] ".sql_error($TMP_result);
	}
}

?>
