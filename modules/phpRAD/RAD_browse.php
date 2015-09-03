<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}


if ($RAD_browsetreeajax!="") include_once("modules/phpRAD/RAD_browsetreeajax.php");
else include_once("modules/phpRAD/RAD_browsetree.php");
//---------------------------------------------------------------------------
//------------------------- Browse/List/Search
//---------------------------------------------------------------------------
if (($subfunc == "list")||($subfunc == "report")||($func == "search" && $showall != "")) {
	$start=0;
	$limit=$found;
}
if ($func == "browse" && $menubrowse == false) {
    $func="error";
}
if ($func == "browsecalendar" && $menucalendar == false) {
    $func="error";
}
if ($subfunc == "list" && $menuprint == false) {
    $func="error";
}
if ($subfunc == "report" && $menureport == false) {
    $func="error";
}

$V_reportName=trim($V_reportName);
if (($V_reportName!=""||$V_idreportName!="")&&($func=="search")) {
	if ($V_idreportName!="") $TMP_reportName=RAD_lookup("impresos","impreso","idimpreso",$V_idreportName);
	if ($V_reportName!=$TMP_reportName && $V_reportName!="") $V_idreportName="";
	if ($V_reportName=="" && $TMP_reportName!="") $V_reportName=$TMP_reportName;
	for ($ki=0; $ki<$MaxSearchFields; $ki++) {
		if (${"operator".$ki}=="") {
			$fieldname=$_REQUEST["searchfield".$ki];
			${$fieldname.""}="";
			${$fieldname."_year"}=""; ${$fieldname."_month"}=""; ${$fieldname."_day"}="";
			${$fieldname."_hour"}=""; ${$fieldname."_min"}=""; ${$fieldname."_sec"}="";
			${$fieldname."TO_year"}=""; ${$fieldname."TO_month"}=""; ${$fieldname."TO_day"}="";
			${$fieldname."TO_hour"}=""; ${$fieldname."TO_min"}=""; ${$fieldname."TO_sec"}="";
			if ($_REQUEST["browsefield".$ki]=="" && $_REQUEST["totalfield".$ki]=="") $_REQUEST["searchfield".$ki]="";
		}
	}
	$TMP_URL="";
	foreach ($_REQUEST as $key=>$val) {
		if (is_array($val)) {
			for ($kj=0; $kj<count($val); $kj++) {
				if ($val[$kj]=="") continue;
				$TMP_URL.="&".$key."[".$kj."]=".urlencode($val[$kj]);
			}
			continue;
		}
		if ($key=="RAD_errorstr" || $key=="PHPSESSID" || $key=="PHPSESSID_last" || $key=="V_idreportName" || $key=="V_reportName") continue;
		if ($val=="") continue;
		$TMP_URL.="&".$key."=".urlencode($val);
	}
	$TMP_URL=$PHP_SELF."?".substr($TMP_URL,1);
	$TMP_user=base64_decode($HTTP_SESSION_VARS["SESSION_user"]);
	if ($V_idreportName=="") {
            // Compatibilidad con la existencia de campos de modulo y usuario para filtrar mejor los impresos de cada uno

            $TMP_insert=""; $TMP_campo=array();
	    $campos=sql_list_fields("impresos", $RAD_dbi);
	    while($columnas=sql_fetch_array($campos, $RAD,dbi)) $TMP_campo[]=$columnas[0];
            if (in_array('modulo', $TMP_campo)) $TMP_insert.=", modulo='".$V_mod."'";
            if (in_array('usuario', $TMP_campo)) $TMP_insert.=", usuario='".$TMP_user."'";

            $cmdImpreso="INSERT INTO impresos SET tipoimpreso=".converttosql($title).", impreso=".converttosql($V_reportName).", tabla='$V_tablename', tipodoc='$V_typePrint', url=".converttosql($TMP_URL).", observaciones='Informe generado por $TMP_user en ".date("Y-m-d H:i:s")."'".$TMP_insert;
            sql_query($cmdImpreso,$RAD_dbi);
	} else {
            $TMP_urlOld=RAD_lookup("impresos","url","idimpreso",$V_idreportName);
            if ($TMP_urlOld!=converttosql($TMP_URL)) {
            	$cmdImpreso="UPDATE impresos SET tipoimpreso=".converttosql($title).", impreso=".converttosql($V_reportName).", tabla='$V_tablename', tipodoc='$V_typePrint', url=".converttosql($TMP_URL).", observaciones='Informe generado por $TMP_user en ".date("Y-m-d H:i:s")."'".$TMP_insert." WHERE idimpreso='$V_idreportName'";
            	sql_query($cmdImpreso,$RAD_dbi);
            }

        }
}

echo "
<script>
function RAD_checkFields".$numsubbrowse."() {
".$RAD_precheckFields."
	return true;
}
var Vcheckuncheckdeletes".$numsubbrowse."=false; 
function checkuncheckdeletes".$numsubbrowse."(){
  if(Vcheckuncheckdeletes".$numsubbrowse."==true) {
    for(var i=0;i<document.F.elements.length; i++) {
      nombrecampo=document.F[i].name;
      if(nombrecampo.indexOf('_delete')!=-1){
        var object = eval('document.F['+i+']');
        object.checked = false;
      }
    }
    Vcheckuncheckdeletes".$numsubbrowse."=false;
  } else {
    for(var i=0;i<document.F.elements.length; i++) {
      nombrecampo=document.F[i].name;
      if(nombrecampo.indexOf('_delete')!=-1){
        var object = eval('document.F['+i+']');
        object.checked = true;
      }
    }
    Vcheckuncheckdeletes".$numsubbrowse."=true;
  }
}
</script>
";

if ($V_reportName!="" || $V_idreportName!="") {
	if ($V_reportName=="") $V_reportName=RAD_lookup("impresos","impreso","idimpreso",$V_idreportName);
	echo "\n<script>\ndocument.title=document.title+'. ".RAD_UTF_to_Unicode(str_replace("\n","",str_replace("\r","",RAD_convertHTML2TXT($V_reportName))))."';\n</script>\n<TABLE width=100% class=borde><TR><TH>".$V_reportName."</TH></TR></TABLE>";
}

$TMP_brline=array();
if ($func == "browse" || $func == "browsecalendar" || $func == "browsedit" || $func == "search") {
	$TMP_forms="";
//	if ($func == "search") $browsetype="";
	if ($subfunc=="report") {
	    $browsetype="";
            for ($i = 0; $i < $numf; $i++) $fields[$i] -> browsable=false;
	    if ($MaxSearchFields<$numf) $MaxSearchFields=$numf;
            for ($i = 0; $i < $MaxSearchFields; $i++) {
		if (${"browsefield".$i}!="" || ${"totalfield".$i}!="") {
		    $TMP_namefield=${"searchfield".$i};
		    $TMP_numfield=$findex[$TMP_namefield];
		    if ($TMP_numfield=="" && $TMP_numfield!=0) $TMP_numfield=$i;
		    $fields[$TMP_numfield] -> browsable=true;
		}
	    }
	}
	if ($V_prevfunc=="searchform") {
		$URLROI.="&V_prevfunc=".$V_prevfunc;
		$tabURLROI.="&V_prevfunc=".$V_prevfunc;
	}
	if ($func=="browsecalendar") {
	    $A_calendarfields=explode(",",$calendarfields.",");
	    $calendarfield=""; $calendarlitfield="";
	    if (count($A_calendarfields)>0) $calendarfield=trim($A_calendarfields[0]);
	    if (count($A_calendarfields)>1) $calendarlitfield=trim($A_calendarfields[1]);
	    $start=0; $limit=$found;
	    if (trim($calendarfield)!="") {
		if ($year=="") $year=date("Y");
		if ($month==="") $month=date("m");
		if ($month==0) {
			$TMP_fechaprev=$year-1; $TMP_fechaprev.="-12-31";
			$TMP_fechapost=$year+1; $TMP_fechapost.="-01-01";
		} else {
			$yearprev=$year; $yearpost=$year;
			$monthprev=$month-1; if ($monthprev<1) { $yearprev--; $monthprev="12"; }
			$monthpost=$month+1; if ($monthpost>12) { $yearpost++; $monthpost="01"; }
			if (strlen($monthprev)==1) $monthprev="0".$monthprev;
			if (strlen($monthpost)==1) $monthpost="0".$monthpost;
			$TMP_fechaprev=$yearprev."-".$monthprev."-28";
			$TMP_fechapost=$yearpost."-".$monthpost."-01";
		}
		$TMP_defaultfiltercal="(".$tablename.".".$A_calendarfields[0].">'".$TMP_fechaprev."' AND ".$tablename.".".$A_calendarfields[0]."<'".$TMP_fechapost."')";
		if ($defaultfilter=="") $defaultfilter=$TMP_defaultfiltercal;
		else $defaultfilter="(".$defaultfilter.") AND ".$TMP_defaultfiltercal;
	    }
	}
	global $RAD_classrow;

	if (ereg("-",$start)) $start=0;
	if ($func == "search" && $orderby == "") $orderby = $searchfield;
		switch($dbtype) {
			case "oracle":
				if ($start=="") $start = 0;
				$limitstr = ") A WHERE ROWNUM<=".($limit+$start).") WHERE RNUM>$start";
				$prelimitstr = "SELECT * FROM (SELECT A.*, ROWNUM RNUM FROM (";
				break;
			case "mysql":
				if ($start=="") $start = 0;
				$limitstr = "$start,$limit";
				break;
			default:
				if ($start=="") $start = 0;
				$limitstr = "$limit,$start";
		}
		$ASC="";
		if ($desc !="") $ASC=" DESC";
		if ($defaultfilter!="") $TMP_defaultfilter=" WHERE ".$defaultfilter;
		else $TMP_defaultfilter="";
		if ($menubletter !=false && $bletter!="" && $bletter!="_" && $orderby!="") {
			if ($TMP_defaultfilter!="") $TMP_defaultfilter.=" AND ";
			else $TMP_defaultfilter=" WHERE ";
			$TMP_orderby=$orderby; $TMP_tablename=$tablename;
			if (ereg("db",$fields[$findex[$orderby]]->dtype)) {
				list($ptablename, $pfname, $pftitle, $pfilter, $pfieldparent, $porder, $pgroup)=dbdecode($fields[$findex[$orderby]]);
				$farr=explode(",",$pftitle);
				if (count($farr)>0) $pftitle=$farr[0];
				if (trim($pftitle)!="" && trim($ptablename)!="") {
					$TMP_orderby=$pftitle;
					$TMP_tablename=$ptablename;
				}
			}
			$TMP_fieldorderby=str_replace(" DESC","",$TMP_orderby);
			$TMP_defaultfilter .= $TMP_tablename.".".$TMP_fieldorderby." LIKE '".$bletter."%'";
		}
		$TMP_groupby="";
		if ($RAD_groupby!="") $TMP_groupby=" GROUP BY ".$RAD_groupby;
		$TMP_orderby="";
		if ($RAD_orderby!="") $TMP_orderby=" GROUP BY ".$RAD_orderby;
		if ($orderby !="") {
			if (eregi("plistdb", $fields[$findex[$orderby]]->dtype) || eregi("rlistdb", $fields[$findex[$orderby]]->dtype) || eregi("popupdb", $fields[$findex[$orderby]]->dtype) || eregi("checkboxdb", $fields[$findex[$orderby]]->dtype)) {
//			if (eregi("plist", $fields[$findex[$orderby]]->dtype) || eregi("rlist", $fields[$findex[$orderby]]->dtype) || eregi("check", $fields[$findex[$orderby]]->dtype) || eregi("popup", $fields[$findex[$orderby]]->dtype)) {
//				$cmdSQL="SELECT * FROM $tablename ".$TMP_groupby.$TMP_defaultfilter." LIMIT $limitstr";
				list($ptablename, $pfname, $pftitle, $pfilter, $pfieldparent, $porder, $pgroup)=dbdecode($fields[$findex[$orderby]]);
				$farr=explode(",",$pftitle);
				if (count($farr)>0) $pftitle=$farr[0];
				$farr=explode(",",$pfname);
				if (count($farr)>0) $pfname=$farr[0];
//				if ($TMP_defaultfilter!="") $TMP_defaultfilter.=" AND ".$tablename.".".$pfname."=".$ptablename.".".$pfname;
//				$cmdSQL="SELECT $tablename.*, $ptablename.$pftitle FROM $tablename, $ptablename ".$TMP_defaultfilter.$TMP_groupby." ORDER BY $ptablename.$pftitle ".$ASC." LIMIT $limitstr";
				$cmdSQLJOIN=false;
				if ($tablename!=$ptablename) {
					//if ($TMP_defaultfilter!="") $TMP_defaultfilter="ON".substr($TMP_defaultfilter,6)." AND ".$tablename.".".$fields[$findex[$orderby]]->name."=".$ptablename.".".$pfname;
					//else $TMP_defaultfilter=" ON ".$tablename.".".$fields[$findex[$orderby]]->name."=".$ptablename.".".$pfname;
					$TMP_campo=array();
                                        if ($pfname=="codmunicipio" && $dbtype=="mysql") {
					    $campos=sql_list_fields($tablename, $RAD_dbi);
					    while($columnas=sql_fetch_array($campos, $RAD,dbi)) $TMP_campo[]=$columnas[0];
                                        }
					if ($TMP_defaultfilter!="") {
                                            if ($pfname=="codmunicipio" && in_array('codprovincia', $TMP_campo) && $dbtype=="mysql") { //En el JOIN con la tabla de municipios es necesario cuadrarlo con la provincia
                                                $TMP_defaultfilter="ON".substr($TMP_defaultfilter,6)." AND ".$tablename.".".$fields[$findex[$orderby]]->name."=".$ptablename.".".$pfname." AND ".$tablename.".codprovincia = ".$ptablename.".codprovincia";
                                            } else {
                                                $TMP_defaultfilter="ON".substr($TMP_defaultfilter,6)." AND ".$tablename.".".$fields[$findex[$orderby]]->name."=".$ptablename.".".$pfname;
                                            }
					} else {
                                            if ($pfname=="codmunicipio" && in_array('codprovincia', $TMP_campo) && $dbtype=="mysql") { //En el JOIN con la tabla de municipios es necesario cuadrarlo con la provincia
                                                $TMP_defaultfilter=" ON ".$tablename.".".$fields[$findex[$orderby]]->name."=".$ptablename.".".$pfname." AND ".$tablename.".codprovincia = ".$ptablename.".codprovincia";
                                            } else {
                                                $TMP_defaultfilter=" ON ".$tablename.".".$fields[$findex[$orderby]]->name."=".$ptablename.".".$pfname;
                                            }
                                        }
//					$cmdSQL="SELECT * FROM $ptablename JOIN $tablename ".$TMP_defaultfilter.$TMP_groupby." ORDER BY $ptablename.$pftitle ".$ASC." LIMIT $limitstr";
					$cmdSQL="SELECT ".$tablename.".* FROM $tablename JOIN $ptablename ".$TMP_defaultfilter;
					if ($dbtype=="oracle") $cmdSQL=$prelimitstr.$cmdSQL.$TMP_groupby." ORDER BY $ptablename.$pftitle ".$ASC.$limitstr;
					else $cmdSQL.=$TMP_groupby." ORDER BY $ptablename.$pftitle ".$ASC." LIMIT $limitstr";
					$cmdSQLJOIN=true;
				} else {
					$cmdSQL="SELECT * FROM $tablename ".$TMP_defaultfilter;
					if ($dbtype=="oracle") $cmdSQL=$prelimitstr.$cmdSQL.$TMP_groupby." ORDER BY $pftitle ".$ASC.$limitstr;
					else $cmdSQL.=$TMP_groupby." ORDER BY $pftitle ".$ASC." LIMIT $limitstr";
				}
			} else if ($fields[$findex[$orderby]]->dtype=="function") {
				if ($RAD_orderby!="") $TMP_RAD_orderby=" Order By ".$RAD_orderby.$ASC;
				$cmdSQL="SELECT * FROM $tablename ".$TMP_defaultfilter;
				if ($dbtype=="oracle") $cmdSQL=$prelimitstr.$cmdSQL.$TMP_groupby.$TMP_RAD_orderby.$limitstr;
				else $cmdSQL.=$TMP_groupby.$TMP_orderby.$TMP_RAD_orderby." LIMIT $limitstr";
			} else {
				if ($RAD_orderby!="") {
					$cmdSQL="SELECT * FROM $tablename ".$TMP_defaultfilter;
					if ($dbtype=="oracle") $cmdSQL=$prelimitstr.$cmdSQL.$TMP_groupby." ORDER BY $RAD_orderby ".$ASC.$limitstr;
					else $cmdSQL.=$TMP_groupby." ORDER BY $RAD_orderby ".$ASC." LIMIT $limitstr";
				} else {
					$cmdSQL="SELECT * FROM $tablename ".$TMP_defaultfilter;
					if ($dbtype=="oracle") $cmdSQL=$prelimitstr.$cmdSQL.$TMP_groupby." ORDER BY $orderby ".$ASC.$limitstr;
					else $cmdSQL.=$TMP_groupby." ORDER BY $orderby ".$ASC." LIMIT $limitstr";
				}
			}
		} else {
			$cmdSQL="SELECT * FROM $tablename ".$TMP_defaultfilter;
			if ($dbtype=="oracle") $cmdSQL=$prelimitstr.$cmdSQL.$TMP_orderby.$TMP_groupby.$limitstr;
			else $cmdSQL.=$TMP_orderby.$TMP_groupby." LIMIT $limitstr";
		}
		$TMP_initime=RAD_microtime();
		if ($db -> query($cmdSQL)) {
			$TMP_diftime=RAD_microtime()-$TMP_initime;
			if ($func!="browsecalendar") echo "<TABLE class=browse>\n";
			$browsedit=false;
			if ($V_bredit!="" && (is_admin()||$RAD_bredit!="")) {
				$i=$findex[$V_bredit];
				if ($fields[$i]->name==$V_bredit) {
					$TMP_bredit=getSessionVar("SESSION_bredit_".$V_mod.$fields[$i]->name);
					if ($TMP_bredit=="1") setSessionVar("SESSION_bredit_".$V_mod.$fields[$i]->name,"0");
					else setSessionVar("SESSION_bredit_".$V_mod.$fields[$i]->name,"1");
				}
				$TMP_URL=RAD_delParamURL($REQUEST_URI,"V_bredit");
				if (substr($TMP_URL,0,1)=="&") $TMP_URL=substr($TMP_URL,1);
				echo "<script>\ndocument.location.href=\"".$TMP_URL."\";\n</script>\n";
				die();
			}
			for ($i = 0; $i < $numf; $i++) {
				$TMP_bredit=getSessionVar("SESSION_bredit_".$V_mod.$fields[$i]->name);
                        	if ($TMP_bredit=="1") {
                                	$fields[$i]->browsedit=true;
                        	}
				if ($fields[$i]->browsable && $fields[$i]->browsedit && $subfunc != "list" && $subfunc != "report" && $fields[$i]->extra!="RAD_subbrowse.php" && $fields[$i]->extra!="RAD_subbrowse")
					$browsedit=true;
			}
		if ($func!="browsecalendar" && $subbrowseSID!="") {
			foreach($_REQUEST as $TMP_k=>$TMP_v) $TMP_dbg.="".$TMP_k."=".$TMP_v." - ";
			//echo "<TR><TD><TABLE cellpadding=0 cellspacing=0 class=menu>";
			$TMP_linklist="javascript:RAD_OpenW(\"".$PHP_SELF."?func=&subfunc=browse&headeroff=x&footeroff=x&dbname=FB&V_roi=".urlencode($V_roi)."&V_dir=$V_dir&V_mod=$V_mod&V_idmod=$V_idmod".$SESSION_SID."&subbrowse=x&blocksoff=x&V_prevdir=$V_prevdir&V_previdmod=$V_previdmod&V_prevmod=&V_prevmod&V_prevpar0=$V_prevpar0&subbrowseSID=$subbrowseSID&numsubbrowse=$numsubbrowse&RAD_width=$RAD_width&RAD_height=$RAD_height\",$RAD_width,$RAD_height);";
			//if ($opcionesmenubrowse!="") echo "<tr>".RAD_menu_on("<A ACCESSKEY='L' TITLE='ALT+L' href=\"".$TMP_linklist."\" class=menuon>"._DEF_NLSListAll."</A> $opcionesmenubrowse")."</tr>";
			//echo "</TABLE></TR>";
		}
		if (($func!="browsecalendar")&&($browsedit || $browsetype=="insert" || $V_rowsinsert>0 || $browsetype=="deleteline")) {
			echo "<FORM autocomplete=off ACTION=$PHP_SELF";
			echo " METHOD=POST NAME=F ENCTYPE='multipart/form-data'>\n";
			echo "<INPUT TYPE=HIDDEN NAME=PHPSESSID VALUE='$PHPSESSID'>\n".$FORMROI;
			if (($browsedit||$browsetype=="insert"||$V_rowsinsert>0) && $browsetype=="deleteline") echo "<INPUT TYPE=HIDDEN NAME=func VALUE='delete'>\n";
			else echo "<INPUT TYPE=HIDDEN NAME=func VALUE='update'>\n";
			//echo "<INPUT TYPE=HIDDEN NAME=found VALUE='$found'>\n";
			if ($browsedit || $browsetype=="insert" || $V_rowsinsert>0) echo "<INPUT TYPE=HIDDEN NAME=subfunc VALUE='browsedit'>\n";
			if ($func!="search") echo "<INPUT TYPE=HIDDEN NAME=RAD_browsetype VALUE='$browsetype'>\n";
		}
		$TMP_cab="<TR class=browse>";
	      if ($browsetype=="editline" || $browsetype=="line" || $browsetype=="insert") $TMP_cab.="<TH class=browse>#</TH>";
	      if ($browsetype=="deleteline") $TMP_cab.="<TH class=browse><img onclick='javascript:checkuncheckdeletes".$numsubbrowse."();'src='images/delete.gif' border=0 alt='"._DEF_NLSDeleteString."' title='"._DEF_NLSDeleteString."'></TH>";
	      if ($RAD_CONFIRM_SAVEREG=="") $TMP_confirm_savereg="\n<script type=text/javascript>\nfunction saveregs".$numsubbrowse."(elem) {\n      csave=confirm(\"\u00BFEst\u00E1 seguro de guardar los registros cambiados?\");\n  if (csave) { document.forms.F.submit();\n elem.disabled=true; \n }}\n</script>\n";
	      else $TMP_confirm_savereg="\n<script type=text/javascript>\nfunction saveregs".$numsubbrowse."(elem) { document.forms.F.submit(); elem.disabled=true;}\n</script>\n";

// funcion para duplicar una linea de insert en el fondo. No va bien porque no se enlaza adecuadamente en el DOM y da errores..
/*
function duplicarowinsert".$numsubbrowse."(trow){
	stringhtml=trow.innerHTML;
	nueva=trow.cloneNode(true);
	tabla=trow.parentNode;
	tabla.insertBefore(nueva,trow);
	newrow=document.forms.F.VI_count.value;
	dlastrow=parseInt(document.forms.F.VI_count.value)-1;
	lastrow=dlastrow.toString();
	stringhtml=stringhtml.replace(/VIC0_changed/g,'VIC' + newrow + '_changed');
	stringhtml=stringhtml.replace(/VI0_/g,'VI'+newrow+'_');
	nueva.innerHTML=stringhtml;
	document.forms.F.VI_count.value++;
}
*/

	      if ($RAD_textConfirmDelete=="") {
		$TMP_confirmdeletes="	dele=confirm(\"\u00BFEst\u00E1 seguro de borrar \"+numregdel+\" registros marcados?\");\n        if (dele) {\n";
		$TMP_confirmdelete="	dele=confirm(\"\u00BFEst\u00E1 seguro de borrar este registro?\");\n        if (dele) {\n";
	      } else {
		$TMP_confirmdeletes="	dele=prompt(\"Para borrar \"+numregdel+\" registros marcados, teclee ".$RAD_textConfirmDelete."\");\n        if (dele!=\"".$RAD_textConfirmDelete."\") alert(\"No se borran registros.\");\n        if (dele==\"".$RAD_textConfirmDelete."\") {\n";
		$TMP_confirmdelete="	dele=prompt(\"Para borrar este registro, teclee ".$RAD_textConfirmDelete."\");\n        if (dele!=\"".$RAD_textConfirmDelete."\") alert(\"No se borra registro.\");\n                if (dele==\"".$RAD_textConfirmDelete."\") {\n";
	      }
	      if (($func!="browsecalendar")&&($browsetype=="editline" || $browsetype=="deleteline")) echo "
<script type='text/javascript'>
function deleregs".$numsubbrowse."(elem) {
	var numregdel=0;
	for (var j=0; j<document.F.elements.length; j++) {
		varname=document.F.elements[j].name;
		varvalue=document.F.elements[j].checked;
		if (varname.indexOf('_delete')>0 && varvalue==true) numregdel++;
	}
	if (numregdel==0) {
		alert(\"Marque al menos un registro para borrar.\");
		return;
	}
".$TMP_confirmdeletes."
	    document.forms.F.func.value='delete';
	    document.forms.F.submit();
	    elem.disabled=true;
	}
}
function deleregx".$numsubbrowse."(elem,numform) {
	var name='x';
".$TMP_confirmdelete."
		var numobj=-1;
		for (var i=0; i<document.forms.length; i++) {
//			alert(i+' '+document.forms[i]);
			for (var j=0; j<document.forms[i].elements.length; j++) {
				if ((document.forms[i].elements[j].name=='numform')&&
				   (document.forms[i].elements[j].value==numform)) {
					document.forms[i].submit();
	    				elem.disabled=true;
				}
			}
		}
	}

}
function savedeleregs".$numsubbrowse."(elem) {
	var numregdel=0;
	var confirmstr=\"\u00BFEst\u00E1 seguro de guardar los registros cambiados?\"
	for (var j=0; j<document.F.elements.length; j++) {
		varname=document.F.elements[j].name;
		varvalue=document.F.elements[j].checked;
		if (varname.indexOf('_delete')>0 && varvalue==true) numregdel++;
	}
	if (numregdel>0) confirmstr=confirmstr + \"\\nOJO: Se van a borrar tambien \"+numregdel+\" registro(s) !\";
	savedele=confirm(confirmstr);
	var name='x';
	if (savedele) {
		var numobj=-1;
		for (var i=0; i<document.forms.length; i++) {
			for (var j=0; j<document.forms[i].elements.length; j++) {
				if ((document.forms[i].elements[j].name=='numform')&&
				   (document.forms[i].elements[j].value==numform)) {
					document.forms[i].submit();
				}
			}
		}
	}
	if (savedele) {
		document.forms.F.submit();
		elem.disabled=true;
	}
}
</script>
".$TMP_confirm_savereg;
	    else echo $TMP_confirm_savereg;
            for ($i = 0; $i < $numf; $i++) {
		if ($fields[$i]->title == "") continue;
		if ($fields[$i]->dtype=="function"&&$fields[$i]->type=="function"&&$fields[$i]->extra=="RAD_subbrowse.php"&&$fields[$i]->browsable) continue; // los RAD_subbrowse no se muestran aunque sean browasable, para ello usar RAD_browsesubbrowse
                if ($fields[$i] -> browsable) {
                    if ($fields[$i] -> orderby) {
			$urlASC="";
			if ($orderby == $fields[$i]->name) {
			    $orderbymark= _DEF_NLSDescending;
			    if ($desc == "") {
				$orderbymark= _DEF_NLSAscending;
				$urlASC="&desc=x"; }
			} else $orderbymark= "";
				if ($subfunc!="list" && $subfunc!="report") {
					$TMP_cab.="<TH class=browse>";
					if (is_admin()||$RAD_bredit!="") {
						if ($subbrowseSID!="" && $subbrowseshowparent=="") {
							if ($RAD_width<2 || $RAD_width=="") $RAD_width=800;
							if ($RAD_height<2 || $RAD_height=="") $RAD_height=600;
							$TMP_linkbredit="javascript:RAD_OpenW(\"".$PHP_SELF."?func=browse&subfunc=browse&V_bredit=".$fields[$i]->name.$URLROI."\",$RAD_width,$RAD_height);";
						} else {
							$TMP_linkbredit=$PHP_SELF."?func=browse&V_bredit=".$fields[$i]->name.$URLROI;
						}
						$TMP_cab.="<A HREF='".$TMP_linkbredit."'>&nbsp;</A>";
					}
					if ($subbrowseSID!="" && $numsubbrowse!='') {
						$TMP_subroi=urlencode("orderby=".$fields[$i]->name.$urlASC);
						$TMP_cab.="<A class=browse TITLE='Ordenar por ".$fields[$i] -> title."' style='cursor:pointer;' onclick=\"window.location=window.location+'&V_sub_".$numsubbrowse."_roi=".$TMP_subroi."';\" >".$orderbymark.$fields[$i]->title."</A> </TH>\n";
					} else {
						if (!ereg("&func=",$URLROIsearch)) $TMP_cab.="<A class=browse TITLE='Ordenar por ".$fields[$i] -> title."' HREF=\"$PHP_SELF?func=browse&orderby=".$fields[$i]->name.$urlASC.$tabURLROI."\">".$orderbymark.$fields[$i]->title."</A> </TH>\n";
						else $TMP_cab.="<A class=browse TITLE='Ordenar por ".$fields[$i] -> title."' HREF=\"$PHP_SELF?orderby=".$fields[$i]->name.$urlASC.$URLROIsearch."\">".$orderbymark.$fields[$i]->title."</A> </TH>\n";
					}
				} else {
					$TMP_cab.="<TH class=browse>".$orderbymark.$fields[$i] -> title."</TH>\n";
				}
		    } else {
			$TMP_cab.="<TH class=browse>".$fields[$i]->title."</TH>";
		    }
                }
            }
		$TMP_cab.="</TR>\n";
		if ($func!="browsecalendar") echo $TMP_cab;
		$TMP_linesinsert="";
                if ($subfunc!="list" && $subfunc!="report" && ($browsetype=="insert"||$V_rowsinsert>0)) {
			if ($V_rowsinsert=="") $V_rowsinsert=1;
			for($ki=0; $ki<$V_rowsinsert; $ki++) {
				if (ereg("class=row1",$RAD_classrow)) {
					$RAD_classrow="class=row2";
					$RAD_blurfocusrow.=" onfocus=\"document.getElementById('rowb').style.className='row3';\" onblur=\"document.getElementById('rowb".$numrow."').style.className='row2';\"";
					$RAD_classrowTR="class=row2 onmouseover=\"this.style.className='row3';\" onmouseout=\"this.style.className='row2';\"";
				} else {
					$RAD_classrow="class=row1";
					$RAD_blurfocusrow.=" onfocus=\"document.getElementById('rowb').style.className='row3';\" onblur=\"document.getElementById('rowb".$numrow."').style.className='row1';\"";
					$RAD_classrowTR="class=row1 onmouseover=\"this.style.className='row3';\" onmouseout=\"this.style.className='row1';\"";
				}
		    		if (file_exists("modules/$V_dir/common.defaults.php")) {
					$TMP=include ("modules/$V_dir/common.defaults.php");
					if ($TMP!==true && $TMP!="1") $TMP_brline[$numrow].=$TMP;
				}
				if ($filename!="") {
					if (file_exists("modules/$V_dir/".$filename.".defaults.php")) {
			    			$TMP=include ("modules/$V_dir/".$filename.".defaults.php");
			    			if ($TMP!==true && $TMP!="1") $TMP_brline[$numrow].=$TMP;
					}
		    		} else {
					if (file_exists("modules/$V_dir/".$V_mod.".defaults.php")) {
			    			$TMP=include ("modules/$V_dir/".$V_mod.".defaults.php");
			    			if ($TMP!==true && $TMP!="1") $TMP_brline[$numrow].=$TMP;
					}
		    		}
				$TMP_linesinsert.="<TR id='rowbi".$ki."' $RAD_classrowTR>\n<input type=hidden name='VIC".$ki."_changed' value=''>\n";
				if ($browsetype=="deleteline"||$browsetype=="editline"||$browsetype=="line"||$browsetype=="insert") $TMP_linesinsert.="<TD $RAD_classrow></TD>";
		                for ($i = 0; $i < $numf; $i++) {
					$fvalue="";
					$browsedit_insert=false;
					if ($fields[$i]->browsedit || $fields[$i]->fieldedit) $browsedit_insert=true;
					if ($fields[$i]->readonly==false && $fields[$i]->noedit==false && $fields[$i]->noupdate==false && $fields[$i]->noinsert==false && $fields[$i]->nonew==false) $browsedit_insert=true;
					if ($fields[$i]->browsable && ($browsedit_insert) && $fields[$i]->dtype!="function") {
					    $TMP_linesinsert.="<TD $RAD_classrow>";
					    if ($fields[$i]->dtype!="auto_increment") {
						$TMP_onchange="document.F.VIC".$ki."_changed.value=\"x\";".$fields[$i]->vonchange;
						//if ($fvalue=="") $fvalue=$fields[$i]->vdefault;
						if ($fvalue=="") $fvalue=evalVar($fields[$i]->vdefault);
						$TMP_extra=$fields[$i]->extra;
						if ($fields[$i]->parmlistSFF!="") $TMP_extra.="|".$fields[$i]->parmlistSFF;
						if ($fields[$i]->dtype=="num") $TMP_linesinsert.="<div class='RADclassnum' style='width:40px;text-align:right;'>";
						$TMP_linesinsert.=RAD_editfield($fields[$i]->name, $fields[$i]->dtype, $fields[$i]->length, $fields[$i]->ilength, $TMP_extra, $TMP_onchange, $fields[$i]->canbenull, $fvalue,"VI".$ki."_".$fields[$i]->name, "", $fields[$i]->vonfocus, $fields[$i]->vonblur);
						if ($fields[$i]->dtype=="num") $TMP_linesinsert.="</div>";
					    }
					    $TMP_linesinsert.="</TD>";
					} else if ($fields[$i]->browsable && $fields[$i]->dtype!="function") {
					    $TMP_linesinsert.="<TD $RAD_classrow>";
					    if ($fields[$i]->dtype!="auto_increment") {
						if ($fields[$i]->dtype=='date') { // fechas en browseinsert readonly no funcionan...
							$TMP_linesinsert.="<INPUT TYPE=HIDDEN NAME='VI".$ki."_".$fields[$i]->name."_day' VALUE=\"".date("d",strtotime($fields[$i]->vdefault))."\">";
							$TMP_linesinsert.="<INPUT TYPE=HIDDEN NAME='VI".$ki."_".$fields[$i]->name."_month' VALUE=\"".date("m",strtotime($fields[$i]->vdefault))."\">";
							$TMP_linesinsert.="<INPUT TYPE=HIDDEN NAME='VI".$ki."_".$fields[$i]->name."_year' VALUE=\"".date("Y",strtotime($fields[$i]->vdefault))."\">";
						} else {
							$TMP_linesinsert.="<INPUT TYPE=HIDDEN NAME='VI".$ki."_".$fields[$i]->name."' VALUE=\"".htmlspecialchars($fields[$i]->vdefault)."\">";
						}
					    }
					    $TMP_linesinsert.="</TD>";
					} else {
						if ($fields[$i]->dtype=="function" && $fields[$i]->browsable==true) $TMP_linesinsert.="<TD $RAD_classrow>&nbsp;</TD>";
						if ($fields[$i]->vdefault=="" && $fields[$i]->type=="uniqid") $fields[$i]->vdefault=uniqid(rand());
						$TMP_linesinsert.="<INPUT TYPE=HIDDEN NAME='VI".$ki."_".$fields[$i]->name."' VALUE=\"".htmlspecialchars($fields[$i]->vdefault)."\">";
					}
				}
				$TMP_linesinsert.="</TR>\n";
			}
			$TMP_linesinsert.="<INPUT TYPE=HIDDEN NAME=VI_count VALUE='$V_rowsinsert'>\n";
		}
	    $numrow=1;
            while ($db -> next_record() && !($rows_limit<0)) {
                $hiddenbrowsedit="";
		if (ereg("class=row1",$RAD_classrow)) {
			$RAD_classrow="class=row2";
			$RAD_blurfocusrow.=" onfocus=\"document.getElementById('rowb').style.className='row3';alert('".$numrow."');\" onblur=\"document.getElementById('rowb').style.className='row2';\"";
			$RAD_classrowTR="class=row2 onmouseover=\"this.style.className='row3';\" onmouseout=\"this.style.className='row2';\"";
		} else {
			$RAD_classrow="class=row1";
			$RAD_blurfocusrow.=" onfocus=\"document.getElementById('rowb').style.className='row3';\" onblur=\"document.getElementById('rowb').style.className='row1';\"";
			$RAD_classrowTR="class=row1 onmouseover=\"this.style.className='row3';\" onmouseout=\"this.style.className='row1';\"";
		}
                $urlidnames = "";
                $idnames = "";
/*
		if ($cmdSQLJOIN==true) { // Esto solo ocurre si no se pone SELECT tabla.* cuando hay JOIN
			$A_fields=array();
	                for ($i = 0; $i <sql_num_fields($db->Query_ID); $i++) {
				$TMP_fieldname=sql_field_name($db->Query_ID, $i);
				if ($A_fields[$TMP_fieldname]=="") {
					$A_fields[$TMP_fieldname]=$TMP_fieldname;
					$db->Record[$TMP_fieldname]=$db->Record[$i];
				}
			}
		}
*/
		$TMP_vacio=true;
		    if (file_exists("modules/$V_dir/common.defaults.php")) {
			$TMP=include ("modules/$V_dir/common.defaults.php");
			if ($TMP!==true && $TMP!="1") $TMP_brline[$numrow].=$TMP;
		    }
		    if ($filename!="") {
			if (file_exists("modules/$V_dir/".$filename.".defaults.php")) {
			    $TMP=include ("modules/$V_dir/".$filename.".defaults.php");
			    if ($TMP!==true && $TMP!="1") $TMP_brline[$numrow].=$TMP;
			}
		    } else {
			if (file_exists("modules/$V_dir/".$V_mod.".defaults.php")) {
			    $TMP=include ("modules/$V_dir/".$V_mod.".defaults.php");
			    if ($TMP!==true && $TMP!="1") $TMP_brline[$numrow].=$TMP;
			}
		    }
                for ($i = 0; $i < $numf; $i++) if ($db->Record[$i]!="") $TMP_vacio=false;
		if ($TMP_vacio) continue;
                for ($i = 0; $i < $numf; $i++) {
                    if (${"idname$i"} != "") {
                        $urlidnames .= "&par$i=".urlencode($db -> Record[${"idname$i"}]);
                        $idnames .= "&id$i=".$db -> Record[${"idname$i"}];
                        $hiddenbrowsedit .= "<input type=hidden name='V".$numrow."_par$i' value=\"".htmlspecialchars($db -> Record[${"idname$i"}])."\">\n";
                    }
                }
                if ($browsedit) $TMP_brline[$numrow].=$hiddenbrowsedit;
                if ($numf==0) continue;
		$TMP_brline[$numrow].="<TR id='rowb".$numrow."' $RAD_classrowTR>\n";
                if (($subfunc!="list" && $subfunc!="report")||($linkoff="")) {
			if ($defaultfunc=="edit") $TMP_nextfunc="edit";
			else $TMP_nextfunc="detail";
//			if ($defaultfunc=="detail") $TMP_nextfunc="detail";
//			else $TMP_nextfunc="edit";
			if ($subbrowseSID!="" && $subbrowseshowparent=="") {
			    if ($RAD_width<2 || $RAD_width=="") $RAD_width=800;
			    if ($RAD_height<2 || $RAD_height=="") $RAD_height=600;
                	    $htmllink="<A ".str_replace("rowb","rowb".$numrow,$RAD_blurfocusrow)." TITLE='|Reg. ".($numrow)."' HREF='javascript:RAD_OpenW(\"".$PHP_SELF."?func=".$TMP_nextfunc."&subfunc=browse".$urlidnames.$URLROI;
                	    $htmllinkend="</A>";
			} else {
                	    $htmllink="<A ".str_replace("rowb","rowb".$numrow,$RAD_blurfocusrow)." TITLE='|Reg. ".($numrow)."' HREF=\"".$PHP_SELF."?func=".$TMP_nextfunc.$urlidnames.$URLROI;
                	    $htmllinkend="</A>";
			}
			//if ((!$menudetail) && $TMP_nextfunc=="detail") $htmllink=''; $htmllinkend=''; // Parche para que si no tenemos browse el modulo sea un simple listado sin mas
		}
		    if ($browsetype=="line" || $browsetype=="insert") {
			$TMP_numreg=$numrow+$start;
			if (trim($htmllink)!="" && ($RAD_browselinkcolumn==""||$fields[$i]->name==$RAD_browselinkcolumn)) {
			    if ($subbrowseSID!="") $TMP_brline[$numrow].="<TD $RAD_classrow> ".$htmllink."\",$RAD_width,$RAD_height);'>".$TMP_numreg.$htmllinkend." </TD>\n";
			    else $TMP_brline[$numrow].="<TD $RAD_classrow> ".$htmllink."\">".$TMP_numreg.$htmllinkend." </TD>\n";
			} else $TMP_brline[$numrow].="<TD $RAD_classrow> ".$TMP_numreg." </TD>\n";
			$htmllink="";
			$htmllinkend="";
		    }
		    if ($browsetype=="deleteline") {
			$TMP_brline[$numrow].="<TD $RAD_classrow> <input type=checkbox value='x' name='V".$numrow."_delete'> </TD>\n";
            		for ($i = 0; $i < $numf; $i++) {
            		    if (${"idname$i"} != "") {
                    		$TMP_brline[$numrow].= "<input type=hidden name='V".$numrow."_par$i' value=\"".htmlspecialchars($db -> Record[${"idname$i"}])."\">\n";
                	    }
            		}
		    }
		    if ($browsetype=="editline") {
			$TMP_numreg=$numrow+$start;
			$hiddenidnames="";
			for ($xi = 0; $xi < $numf; $xi++) {
				if (${"idname$xi"} != "") {
					$val=$db -> Record[${"idname$xi"}];
					$hiddenidnames .= "<input type=hidden name=par$xi value=\"".htmlspecialchars($val)."\">\n";
				}
			}
			$TMP_brline[$numrow].="<TD $RAD_classrow nowrap>";
			if ($menudetail||!isset($menudetail)) $TMP_brline[$numrow].=" <A HREF=\"".$PHP_SELF."?func=detail".$urlidnames.$URLROI."\"><img src='images/detail.gif' border=0 alt='Consulta' title='Consulta'></A>";
			if ($menuedit||!isset($menuedit)) $TMP_brline[$numrow].=" <A HREF=\"".$PHP_SELF."?func=edit".$urlidnames.$URLROI."\"><img src='images/edit.gif' border=0 alt='Edita' title='Edita'></A> ";
			if ($menunew||!isset($menunew)) $TMP_brline[$numrow].=" <A HREF=\"".$PHP_SELF."?func=new".$urlidnames.$URLROI."\"><img src='images/new.gif' border=0 alt='Duplica' title='Duplica'></A> ";
			if ($menudelete||!isset($menudelete)) $TMP_brline[$numrow].=" <a href=\"javascript:if(RAD_checkFields".$numsubbrowse."())deleregx".$numsubbrowse."(elem,$TMP_numreg);\"><img src='images/delete.gif' border=0 alt='"._DEF_NLSDeleteString."' title='"._DEF_NLSDeleteString."'></a>";
			$TMP_forms.="\n<FORM autocomplete=off NAME='FB$TMP_numreg' ACTION=$PHP_SELF TARGET=_blank METHOD=GET><INPUT TYPE=HIDDEN NAME=numform VALUE='$TMP_numreg'><INPUT TYPE=HIDDEN NAME=func VALUE='delete'><INPUT TYPE=HIDDEN NAME=subfunc VALUE='browse'>\n".
				$FORMROI.$hiddenidnames."</FORM>";
			$TMP_brline[$numrow].="</TD>\n";
		    }
		    if ($RAD_nojumpdetail=="" && $func=="search" && $searchfield=="" && $subfunc!="report" && $db->num_rows()==1 && $dbtype!="oracle") { // num_rows no funciona bien en Oracle
			echo "<script>
			document.location.href='".$PHP_SELF."?func=".$TMP_nextfunc.$urlidnames.$URLROI."';
			</script>\n";
		    }
            	    for ($i = 0; $i < $numf; $i++) {
			if ($fields[$i]->dtype=="function"&&$fields[$i]->type=="function"&&$fields[$i]->extra=="RAD_subbrowse.php"&&$fields[$i]->browsable) continue; // los RAD_subbrowse no se muestran aunque sean browasable, para ello usar RAD_browsesubbrowse
			if ($fields[$i]->dtype == "function" && $fields[$i]->type == "function") {
				if ($fields[$i] -> browsable) {
					$RAD_numfield=$i;
					if (!file_exists("modules/$V_dir/".$fields[$i]->extra) && file_exists("modules/phpRAD/".$fields[$i]->extra)) {
						$TMP_funcDir="modules/phpRAD/"; // directorio por defecto de funciones comunes
					} else {
						$TMP_funcDir="modules/$V_dir/";
					}
					$TMP_valorderby=include($TMP_funcDir.$fields[$i]->extra);
					$i=$RAD_numfield;
					$TMP_brline[$numrow].=$TMP_valorderby;
					if ($i==$findex[$orderby]) {
					    $TMP_valorderby=RAD_convertHTML2CSV($TMP_valorderby);
					    $TMP_linelit[$numrow]=$TMP_valorderby;
					}
				}
				continue;
                	}
			if ($fields[$i]->type == "function" && $fields[$i]->dtype == "geturl") {
				if ($fields[$i] -> browsable) {
					$TMP_file = fopen($fields[$i]->extra, "r");
					if ($TMP_file) {
						$TMP_content = "";
						while (!feof($TMP_file)) {
       					 		$TMP_xline = fgets($TMP_file, 512000);
		        				$TMP_content = $TMP_content.$TMP_xline;
						}
					}
					fclose($TMP_file);
				$TMP_brline[$numrow].=$TMP_content;
				}
				continue;
                	}
                	if (($fields[$i] -> fieldedit || $func == "browsedit")&&($subfunc!="list")&&($subfunc!="report")) {
				$TMP_height=100;
				if (_DEF_POPUP_MARGIN=="SUBMODAL") $TMP_height+=80;
				$htmlEdit="<a href='javascript:RAD_OpenW(\"".$PHP_SELF."?func=edit&subfunc=browse&menuoff=x&footeroff=x&headeroff=x&lapoff=x&fieldedit=".$fields[$i]->name.$urlidnames.$URLROI."&V_lap=".urlencode($fields[$i]->overlap)."\",600,".$TMP_height.");'>"._DEF_NLSBrowseEdit."</a>";
				$htmlEditEnd="";
			} else {
			    $htmlEdit=""; $htmlEditEnd="";
			}
                    if (($subfunc=="report" && ${"totalfield".$i}!="") || 
                        ($RAD_showSumNumBrowse!="" && $fields[$i]->browsable==true && (substr($fields[$i]->dtype,0,3)=="num"||$fields[$i]->dtype=="checkbox"))) {  // los numericos visibles se totalizan
				global ${"Ttotalfield".$i};
				if (!isset(${"Ttotalfield".$i})) ${"Ttotalfield".$i}=0;
    				${"Ttotalfield".$i}+=$db->Record[$fields[$i]->name];
                    }
                    if ($fields[$i] -> browsable) {
			$TMP_brline[$numrow].="<TD $RAD_classrow>".$htmlEdit;
    			if (($fields[$i]->dtype != "function")&&($fields[$i] -> browsedit)&&($subfunc!="list")&&($subfunc!="report")) {
    				$fvalue = $db->Record[$fields[$i]->name];
				if ($fvalue=="") $fvalue=$fields[$i]->vdefault;
				$TMP_extra=$fields[$i]->extra;
				if ($fields[$i]->parmlistSFF!="") $TMP_extra.="|".$fields[$i]->parmlistSFF;
				if ($fields[$i]->dtype=="num") $TMP_brline[$numrow].="<div class='RADclassnum' style='width:40px;text-align:right;'>";
				$TMP_brline[$numrow].=RAD_editfield($fields[$i]->name, $fields[$i]->dtype, $fields[$i]->length, $fields[$i]->ilength, $TMP_extra, $fields[$i]->vonchange, $fields[$i]->canbenull, $fvalue,"V".$numrow."_".$fields[$i]->name, "", $fields[$i]->vonfocus, $fields[$i]->vonblur);
				if ($fields[$i]->dtype=="num") $TMP_brline[$numrow].="</div>";
			} else if (trim($htmllink)!="" && ($RAD_browselinkcolumn==""||$fields[$i]->name==$RAD_browselinkcolumn)) {
			    if ($subbrowseSID!="") $TMP_brline[$numrow].=str_replace("|Reg.",$fields[$i]->title." | Reg.",$htmllink)."&V_lap=".urlencode($fields[$i]->overlap)."\",$RAD_width,$RAD_height);'>";
			    else $TMP_brline[$numrow].=str_replace("|Reg.",$fields[$i]->title." | Reg.",$htmllink)."&V_lap=".urlencode($fields[$i]->overlap)."\">";
			}
                	if ($fields[$i]->dtype == "function") {
				$RAD_numfield=$i;
				if (!file_exists("modules/$V_dir/".$fields[$i]->extra) && file_exists("modules/phpRAD/".$fields[$i]->extra)) {
					$TMP_funcDir="modules/phpRAD/"; // directorio por defecto de funciones comunes
				} else {
					$TMP_funcDir="modules/$V_dir/";
				}
				$TMP_valorderby=include($TMP_funcDir.$fields[$i]->extra);
				$i=$RAD_numfield;
				$TMP_brline[$numrow].=$TMP_valorderby;
				if ($i==$findex[$orderby]) {
				    $TMP_valorderby=RAD_convertHTML2CSV($TMP_valorderby);
				    $TMP_linelit[$numrow]=$TMP_valorderby;
				}
                	} elseif ($fields[$i]->dtype == "geturl") {
				$TMP_file = fopen($fields[$i]->extra, "r");
				if ($TMP_file) {
					$TMP_content = "";
					while (!feof($TMP_file)) {
        					$TMP_linereg = fgets($TMP_file, 512000);
					        $TMP_content = $TMP_content.$TMP_linereg;
					}
				}
				fclose($TMP_file);
				$TMP_brline[$numrow].=$TMP_content;
    			} elseif (($subfunc!="list")&&($subfunc!="report")&&($fields[$i] -> browsedit)) {
    			} elseif ($fields[$i]->dtype=="image" && $db->Record[$fields[$i]->name]) {
			    if ($RAD_maxPixelsShowImg=="") $RAD_maxPixelsShowImg="50";
			    $files = explode("\n", $db->Record[$fields[$i]->name]);
			    if (count($files) >1) {
				for ($k = 0; $k < count($files); $k++) {
				    	$files[$k]=str_replace("\n", "", $files[$k]);
				    	$files[$k]=str_replace("\r", "", $files[$k]);
	    		    	    	if ($files[$k]!="") {
						//$TMP_brline[$numrow].="\n<IMG border=0 SRC=\"files/$dbname/".RAD_urlencodeFich($files[$k])."\"><br>\n";
			    			$TMP_brline[$numrow].=RAD_showfield($fields[$i]->dtype, $fields[$i]->extra, $files[$k]);
					}
				}
			    } else {
				$db->Record[$fields[$i]->name]=str_replace("\n", "", $db->Record[$fields[$i]->name]);
				$db->Record[$fields[$i]->name]=str_replace("\r", "", $db->Record[$fields[$i]->name]);
			      if ($db->Record[$fields[$i]->name]!="") {
					//$TMP_brline[$numrow].="\n<IMG border=0 SRC=\"files/$dbname/".RAD_urlencodeFich($db->Record[$fields[$i]->name])."\"><br>\n";
			    		$TMP_brline[$numrow].=RAD_showfield($fields[$i]->dtype, $fields[$i]->extra, $db->Record[$fields[$i]->name]);
				}
			    }
                        } elseif ($fields[$i] -> dtype == "file" && $db->Record[$fields[$i]->name]) {
			    $files = explode("\n", $db->Record[$fields[$i]->name]);
	    		    if (count($files) >1) {
				for ($k = 0; $k < count($files); $k++) {
				    $files[$k]=str_replace("\n", "", $files[$k]);
				    $files[$k]=str_replace("\r", "", $files[$k]);
					$pos=strpos($files[$k],".");
					$TMP_filename=substr($files[$k],$pos+1);
				    if ($files[$k]!="") {
						$TMP_brline[$numrow].="\n<a href=\"files/$dbname/".RAD_urlencodeFich($files[$k])."\" TARGET=_blank>".$TMP_filename."</a><br>\n";
					}
				}
			    } else {
				$db->Record[$fields[$i]->name]=str_replace("\n", "", $db->Record[$fields[$i]->name]);
				$db->Record[$fields[$i]->name]=str_replace("\r", "", $db->Record[$fields[$i]->name]);
				$pos=strpos($db->Record[$fields[$i]->name],".");
				$TMP_filename=substr($db->Record[$fields[$i]->name],$pos+1);
				if ($db->Record[$fields[$i]->name]!="") {
					$TMP_brline[$numrow].="\n<a href=\"files/$dbname/".RAD_urlencodeFich($db->Record[$fields[$i]->name])."\" TARGET=_blank>".$TMP_filename."</a>\n";
				}
			    }
//			} elseif ($fields[$i] -> dtype == "date" || $fields[$i] -> dtype == "datetext") {
//			    $TMP_brline[$numrow].=RAD_showDate($db->Record[$fields[$i]->name]);
//			} elseif ($fields[$i] -> dtype == "datetime" || $fields[$i] -> dtype == "datetimetext") {
//			    $TMP_brline[$numrow].=RAD_showDateTime($db->Record[$fields[$i]->name]);
//			} elseif ($fields[$i] -> dtype == "dateint" || $fields[$i] -> dtype == "dateinttext") {
//			    $TMP_brline[$numrow].=RAD_showDateInt($db->Record[$fields[$i]->name]);
//			} elseif ($fields[$i] -> dtype == "datetimeint" || $fields[$i] -> dtype == "datetimeinttext") {
//			    $TMP_brline[$numrow].=RAD_showDateTimeInt($db->Record[$fields[$i]->name]);
//			} elseif ($fields[$i] -> dtype == "timeint" || $fields[$i] -> dtype == "timeinttext") {
//			    $TMP_brline[$numrow].=RAD_showTimeInt($db->Record[$fields[$i]->name]);
//			} elseif ($fields[$i] -> dtype == "time" || $fields[$i] -> dtype == "timetext") {
//			    $TMP_brline[$numrow].=RAD_showTime($db->Record[$fields[$i]->name]);
//			} elseif ($fields[$i] -> dtype == "email") {
//                	    $TMP_brline[$numrow].="<a href='mailto:".$db -> Record[$fields[$i] -> name]."'>".$db -> Record[$fields[$i] -> name]."</a>";
//			} elseif ($fields[$i] -> dtype == "http") {
//            		    $TMP_brline[$numrow].="<a href='http://".$db -> Record[$fields[$i] -> name]."' TARGET=_blank>".$db -> Record[$fields[$i] -> name]."</a>";
//			} elseif ($fields[$i]->dtype=="fpopupdb" || $fields[$i]->dtype=="popupdb" || $fields[$i]->dtype=="fbpopupdb" || $fields[$i]->dtype=="bpopupdb" || $fields[$i]->dtype=="plistdb" || $fields[$i]->dtype=="plistdbtree" || $fields[$i]->dtype=="rlistdb") {
//			    $TMP_brline[$numrow].=RAD_showfield($fields[$i]->dtype, $fields[$i]->extra, $db -> Record[$fields[$i] -> name]);
//			} elseif ($fields[$i]->dtype=="plistdbm" || $fields[$i]->dtype=="plistdbmtree" || $fields[$i]->dtype=="popupdbm") {
//			    $TMP_brline[$numrow].=RAD_showfield($fields[$i]->dtype, $fields[$i]->extra, $db -> Record[$fields[$i] -> name]);
//        		} elseif ($fields[$i]->dtype=="rlist" || $fields[$i]->dtype=="plist" || $fields[$i]->dtype=="plistm") {
//			    $TMP_brline[$numrow].=RAD_showfield($fields[$i]->dtype, $fields[$i]->extra, $db -> Record[$fields[$i] -> name]);
        		} else {
			    $TMP_value=RAD_showfield($fields[$i]->dtype, $fields[$i]->extra, $db -> Record[$fields[$i] -> name]);
			    if (substr($fields[$i]->dtype,0,3)!="num" && $TMP_value=="" && $subfunc!="report") $TMP_value="_";
			    //if (substr($fields[$i]->dtype,0,3)!="num" && $TMP_value=="") $TMP_value="&nbsp;";
			    if (strlen($TMP_value)>255 && $subfunc!="list" && $subfunc!="report" && $fields[$i]->dtype!='checkboxdbm') {
					$TMP_value=RAD_convertHTML2TXT($TMP_value);
					if (strlen($TMP_value)>255) $TMP_value=substr($TMP_value,0,255)." ...";
			    }
//----- calcula enlace a funclink
				$TMP_linkvalue="";
				$value=$db -> Record[$fields[$i] -> name];
				$TMP_funclink=$fields[$i]->funclink;
				$A_TMP_funclink=explode("/",$TMP_funclink);
				if (count($A_TMP_funclink)>1) { $TMP_funclink=$A_TMP_funclink[1]; $TMP_dir=$A_TMP_funclink[0]; }
				else { $TMP_dir=$V_dir; }
				if ($TMP_funclink!="") if (!is_modulepermitted("", $TMP_dir, $TMP_funclink) && !is_admin()) $TMP_funclink="";
				if ($TMP_funclink=="") {
					$arrtmpx[0] = strpos($fname,"_");
			        	$arrtmpx[1] = substr($fname,$arrtmpx[0]+1);
					$TMP_funclink=$fields[$findex[$arrtmpx[1]]]->funclink;
					$A_TMP_funclink=explode("/",$TMP_funclink);
					if (count($A_TMP_funclink)>1) { $TMP_funclink=$A_TMP_funclink[1]; $TMP_dir=$A_TMP_funclink[0]; }
					else { $TMP_dir=$V_dir; }
				}
				if ($TMP_funclink!="" && $subfunc != "list" && $subfunc != "report") if (is_modulepermitted("", $TMP_dir, $TMP_funclink) || is_admin()) {
					if (in_array($fields[$i]->dtype,array("plistm","plistdbm","plistdbmtree","popupdbm","checkboxm","checkboxdbm"))){
					    // Campo multiple con modulo de enlace
					    $TMP_arr = explode('<br>',$TMP_value);

	    				    if (count($TMP_arr)>1) {
						$TMP_value="";
						foreach($TMP_arr as $TMP_idx=>$TMP_lin) $TMP_value.="<img onclick=\"javascript:RAD_OpenW('$PHP_SELF?V_dir=$TMP_dir&V_mod=$TMP_funclink&funclink=1&dbname=$dbname&headeroff=X&footeroff=X&menuoff=&func=detail&par0=%%1%%&subfunc=browse',800,600);\" src='images/lupa.gif'> ".$TMP_lin."<br>\n";
					    } else {
						if ($TMP_value!="" && $TMP_value!="_" && $TMP_value!="&nbsp;") $TMP_value="<img onclick=\"javascript:RAD_OpenW('$PHP_SELF?V_dir=$TMP_dir&V_mod=$TMP_funclink&funclink=1&dbname=$dbname&headeroff=X&footeroff=X&menuoff=&func=detail&par0=%%1%%&subfunc=browse',800,600);\" src='images/lupa.gif'> ".$TMP_value;
					    }

					    $TMP_cont=0;
					    $TMP_arr = explode('%%1%%',$TMP_value);
					    $TMP_value = "";
					    foreach (explode(',',$value) as $key=>$val){
						if (trim($val)=='') continue;
						$TMP_value.=$TMP_arr[$TMP_cont].$val;
						$TMP_cont++;
					    }
					    $TMP_value.=$TMP_arr[$TMP_cont];
					} else {
				    	    //$TMP_linkvalue="<a href=\"javascript:RAD_OpenW('$PHP_SELF?V_dir=$TMP_dir&V_mod=$TMP_funclink&funclink=1&dbname=$dbname&headeroff=X&footeroff=X&menuoff=&func=detail&par0=".urlencode($value)."&subfunc=browse&V_roi=".urlencode($V_roi)."',800,600);\">";
				    	    if ($value!="" && $TMP_value!="" && $TMP_value!="&nbsp;" && $TMP_value!="_") $TMP_linkvalue="<img onclick=\"javascript:RAD_OpenW('$PHP_SELF?V_dir=$TMP_dir&V_mod=$TMP_funclink&funclink=1&dbname=$dbname&headeroff=X&footeroff=X&menuoff=&func=detail&par0=".urlencode($value)."&subfunc=browse',800,600);\" src='images/lupa.gif'>";
					}
				}
				if ($TMP_linkvalue!="") {
					if ($RAD_browselinkcolumn==""||$fields[$i]->name==$RAD_browselinkcolumn) $TMP_brline[$numrow].=$htmllinkend;
					$TMP_brline[$numrow].=$htmlEditEnd.$TMP_linkvalue.$htmlEdit;
					if (trim($htmllink)!="" && ($RAD_browselinkcolumn==""||$fields[$i]->name==$RAD_browselinkcolumn)) {
						if ($subbrowseSID!="") $TMP_brline[$numrow].=str_replace("|Reg.",$fields[$i]->title." | Reg.",$htmllink)."&V_lap=".urlencode($fields[$i]->overlap)."\",$RAD_width,$RAD_height);'>";
						else $TMP_brline[$numrow].=str_replace("|Reg.",$fields[$i]->title." | Reg. ",$htmllink)."&V_lap=".urlencode($fields[$i]->overlap)."\">";
					}
					$TMP_linkvalue="";
				}
//----- fin calcula enlace a funclink
			    $TMP_brline[$numrow].=$TMP_value;
			    if ($calendarlitfield==$fields[$i]->name) {
				$VAL_calendarlitfield="";
				if ($TMP_value!="&nbsp;") $VAL_calendarlitfield=$TMP_value;
			    }
			//    $val=$db -> Record[$fields[$i] -> name];
			//    $val=str_replace("<", "&lt;", $val);
			//    $val=str_replace("\n", "<br>\n", $val);
			//    $TMP_brline[$numrow].=$val;
            		}
 			if ($RAD_browselinkcolumn==""||$fields[$i]->name==$RAD_browselinkcolumn) $TMP_brline[$numrow].=$htmllinkend;
 			$TMP_brline[$numrow].=$htmlEditEnd." </TD>\n";
			$TMP_linkvalue="";
                    } else {
//		    	$TMP_brline[$numrow].="<INPUT TYPE=HIDDEN NAME='V".$numrow."_".$fields[$i]->name."' VALUE='".$fields[$i]->vdefault."'>";
//		    	$TMP_brline[$numrow].="<INPUT TYPE=HIDDEN NAME='V".$numrow."_".$fields[$i]->name."' VALUE='".urlencode($db->Record[$fields[$i]->name])."'>";
		    	$TMP_brline[$numrow].="<INPUT TYPE=HIDDEN NAME='V".$numrow."_".$fields[$i]->name."' VALUE=\"".htmlspecialchars($db->Record[$fields[$i]->name])."\">";
                    }
                }
		$TMP_brline[$numrow].="</TR>\n";
		if ($func=="browsecalendar" && $calendarfields!="") {
		    $TMP_fecha=$db->Record[$calendarfield];
		    if ($calendarlitfield!="") {
			if (trim($VAL_calendarlitfield)!="") $TMP_lit=$VAL_calendarlitfield;
			else $TMP_lit=$db->Record[$calendarlitfield];
		    }
		    if (trim($TMP_lit)=="") $TMP_lit="*";
		    $xano=substr($TMP_fecha,0,4);
		    $xmes=substr($TMP_fecha,5,2);
		    $xdia=substr($TMP_fecha,8,2);
		    if ($subbrowseSID!="") $RAD_taskscalendar{$xdia.$xmes.$xano."24"}.="".str_replace("|Reg.",$fields[$i]->title." | Reg. ",$htmllink)."\",$RAD_width,$RAD_height);'>".$TMP_lit.$htmllinkend."<br/>\n";
		    else $RAD_taskscalendar{$xdia.$xmes.$xano."24"}.="".str_replace("|Reg.",$fields[$i]->title." | Reg. ",$htmllink)."\">".$TMP_lit.$htmllinkend."<br/>\n";
		}
		if (($findex[$orderby]>0) && (eregi("plist", $fields[$findex[$orderby]]->dtype) || eregi("rlist", $fields[$findex[$orderby]]->dtype) || eregi("check", $fields[$findex[$orderby]]->dtype) || eregi("popup", $fields[$findex[$orderby]]->dtype))) {
			$i=$findex[$orderby];
			$TMP_valorderby=RAD_showfield($fields[$i]->dtype, $fields[$i]->extra, $db -> Record[$fields[$i] -> name]);
			$TMP_linelit[$numrow]=$TMP_valorderby;
		}

    		$numrow++;
            }
/////////////////
		if (($func!="browsecalendar")&&($V_rowsinsertend=="")) echo $TMP_linesinsert;
		$TMP_contlin=0;
		if (count($TMP_linelit)>0) {
			if ($desc !="") {
				arsort($TMP_linelit,SORT_STRING);
			}else{
				asort($TMP_linelit,SORT_STRING);
			}
			reset($TMP_linelit);
			while(list($TMP_numrow,$TMP_lit)=each($TMP_linelit)) {
				$TMP_contlin++;
				if ($RAD_classrow == "class=row1") $RAD_classrow = "class=row2";
				else $RAD_classrow = "class=row1";
				$TMP_brline[$TMP_numrow]=str_replace("<TD class=row1>","<TD ".$RAD_classrow.">",$TMP_brline[$TMP_numrow]);
				$TMP_brline[$TMP_numrow]=str_replace("<TD class=row2>","<TD ".$RAD_classrow.">",$TMP_brline[$TMP_numrow]);
				if ($func!="browsecalendar") echo $TMP_brline[$TMP_numrow];
				unset ($TMP_brline[$TMP_numrow]);
				if (($func!="browsecalendar")&&($V_typePrint=="FPDF" || $V_typeSend=="FPDF")) {
					if ($TMP_contlin%40==0) echo "\n</TABLE>\n";
					if ($TMP_contlin%40==0) echo "\n\n<!--NewPage-->\n\n";	// Page-Break PDF
					if ($TMP_contlin%40==0) echo "\n<TABLE class=browse>\n".$TMP_cab;
				}
			}
		}
		if (count($TMP_brline)>0) {
			foreach($TMP_brline as $TMP_numrow=>$TMP_lit) {
				$TMP_contlin++;
				if ($func!="browsecalendar") echo $TMP_lit;
				if (($func!="browsecalendar")&&($V_typePrint=="FPDF" || $V_typeSend=="FPDF")) {
					if ($TMP_contlin%40==0) echo "\n</TABLE>\n";
					if ($TMP_contlin%40==0) echo "\n\n<!--NewPage-->\n\n";
					if ($TMP_contlin%40==0) echo "\n<TABLE class=browse>\n".$TMP_cab;
				}
			}
		}
		if (($func!="browsecalendar")&&($V_rowsinsertend!="")) echo $TMP_linesinsert;
/////////////////
		$TMP_reportTotalLine=false;
		for ($i = 0; $i < $numf; $i++) {
			if (isset(${"Ttotalfield".$i})) $TMP_reportTotalLine=true;
		}
		if (file_exists("modules/$V_dir/".$V_mod.".browseend.php")) {
		    $TMP_end=include ("modules/$V_dir/".$V_mod.".browseend.php");
		    echo $TMP_end;
		}
		if (($func!="browsecalendar")&&($TMP_reportTotalLine==true)) {
		    echo "<TR class=browse>";
		    if ($browsetype=="line" || $browsetype=="editline" || $browsetype=="insert" || $browsetype=="deleteline") echo "<TH class=browse> </TH>";
	            for ($i = 0; $i < $numf; $i++) {
			if ($fields[$i]->dtype=="function"&&$fields[$i]->type=="function"&&$fields[$i]->extra=="RAD_subbrowse.php"&&$fields[$i]->browsable) continue; // los RAD_subbrowse no se muestran aunque sean browasable, para ello usar RAD_browsesubbrowse
//			if ($subfunc=="report" && ${"browsefield".$i}!="")
//			if ($subfunc=="report" && $fields[$i]->browsable)
			if ($fields[$i]->browsable==true) { // muestra totales si hay numericos y definidos
	     	      		if (isset(${"Ttotalfield".$i})) {
					if ($fields[$i]->dtype=="checkbox") $TMP_num=RAD_showfield("num","4,0", ${"Ttotalfield".$i});
					else $TMP_num=RAD_showfield($fields[$i]->dtype, $fields[$i]->extra, ${"Ttotalfield".$i});
					echo "<TH class=browse><span id='TOT".$fields[$i]->name."'><b>".$TMP_num."</b></span></TH>";
	     	      		} else {
					echo "<TH class=browse>&nbsp;</TH>";
				}
			}
           	    }
		    echo "</TR>";
		}
//	    if ($func!="browsecalendar" && ($prevpage!=""||$nextpage!="")) echo "<TR><TD><TABLE cellpadding=0 cellspacing=0 class=menu><TR>".RAD_menu_on("$prevpage &nbsp;&nbsp;&nbsp; $nextpage")."</TD></TR></TABLE></TR>";
	    if ($func!="browsecalendar") echo "</TABLE>\n";
	    if (($subfunc == "list")||($subfunc == "report")||($func == "search" && $showall != "")) {
		echo " <b>".$TMP_contlin."</b>";
		//if ($subfunc=="list" || $subfunc=="report") echo "\n<script type='text/javascript'>\nwindow.print();\n</script>\n";
                if ($print=="x") echo "\n<script type='text/javascript'>\nwindow.print();\n</script>\n";
	    } else if ($func!="browsecalendar" && ($prevpage!=""||$nextpage!="")) {
		$TMP_litunder="";
		if ($subbrowseSID!='') {
			if ($orderby!='') $TMP_subroi1=urlencode("&orderby=".$orderby."&desc=".$desc);
			else $TMP_subroi1="";
			if ($start>0) {
				$TMP_subroi=urlencode("&start=0&limit=$limit");
				$TMP_litunder.="<a style='cursor:pointer;' onclick=\"window.location=window.location+'&V_sub_".$numsubbrowse."_roi=".$TMP_subroi1.$TMP_subroi."';\">"._DEF_NLSStart."</a>";
			}
			if ($jumps["prev"]!='') {
				$TMP_subroi=urlencode("&start=".$jumps["prev"]."&limit=$limit");
				$TMP_litunder.="<a style='cursor:pointer;' onclick=\"window.location=window.location+'&V_sub_".$numsubbrowse."_roi=".$TMP_subroi1.$TMP_subroi."';\">"._DEF_NLSBefore."</a>";
			}
			$TMP_litunder.="&nbsp; $opcionesmenubrowse &nbsp;";
			if ($jumps["next"]!='') {
				$TMP_subroi=urlencode("&start=".$jumps["next"]."&limit=$limit");
				$TMP_litunder.="<a style='cursor:pointer;' onclick=\"window.location=window.location+'&V_sub_".$numsubbrowse."_roi=".$TMP_subroi1.$TMP_subroi."';\">"._DEF_NLSAfter."</a>";
			}
			if ($jumps["end"]!='') {
				$TMP_subroi=urlencode("&start=".$jumps["end"]."&limit=$limit");
				$TMP_litunder.="<a style='cursor:pointer;' onclick=\"window.location=window.location+'&V_sub_".$numsubbrowse."_roi=".$TMP_subroi1.$TMP_subroi."';\">"._DEF_NLSEnd."</a>";
			}
		} else {
			$TMP_litunder="&nbsp; $prevpage &nbsp;&nbsp;&nbsp; $nextpage";
		}
		echo $TMP_litunder;
	    }
            if ($func!="browsecalendar" && $func!="print" && $subfunc!="report" && $subfunc!="list" && (($browsedit && $numrow>1) || $browsetype=="insert" || $V_rowsinsert>0)) {
		if (empty($RAD_browseditbuttons)){
			echo "\n<div style='text-align:center;padding:2;'><INPUT TYPE=BUTTON ";
			if (($browsedit||$browsetype=="insert"||$V_rowsinsert>0) && $browsetype=="deleteline") echo "onClick='javascript:if(RAD_checkFields".$numsubbrowse."())savedeleregs".$numsubbrowse."(this);' ";
			else if ($browsetype=="deleteline") echo "onClick='javascript:if(RAD_checkFields".$numsubbrowse."())deleregs".$numsubbrowse."();' ";
			else echo "onClick='javascript:if(RAD_checkFields".$numsubbrowse."())saveregs".$numsubbrowse."(this);' ";
			echo "class=button NAME=Save ACCESSKEY='S' TITLE='ALT+S' VALUE="._DEF_NLSSave.">&nbsp;&nbsp;<INPUT TYPE=RESET class=button  ACCESSKEY='R' TITLE='ALT+R' VALUE="._DEF_NLSClearAll."></div>";
		}
		echo "<INPUT TYPE=HIDDEN NAME=V_numrows VALUE='$numrow'></FORM>\n";
            } else if ($func!="browsecalendar" && $func!="print" && $subfunc!="report" && $subfunc!="list" && $browsetype=="deleteline") {
		echo "\n<div style='text-align:center;padding:2;'><INPUT TYPE=BUTTON onClick='javascript:if(RAD_checkFields".$numsubbrowse."())deleregs".$numsubbrowse."(this);' class=button NAME=Delete ACCESSKEY='S' TITLE='ALT+D' VALUE="._DEF_NLSDeleteString.">&nbsp;&nbsp;<INPUT TYPE=RESET class=button  ACCESSKEY='R' TITLE='ALT+R' VALUE="._DEF_NLSClearAll."></div><INPUT TYPE=HIDDEN NAME=V_numrows VALUE='$numrow'></FORM>\n";
            } else if ($func!="browsecalendar" && $func!="print" && $subfunc!="list" && $browsedit) echo "</FORM>\n";
	    if ($func!="browsecalendar" && $subfunc!="list" && $subfunc!="report") echo $TMP_forms;
	    if ($func=="browsecalendar") {
		$TMP_result=RAD_showcalendar($year,$month,$week,$day,"&dbname=$dbname&func=$func");
		echo $TMP_result;
	    }
	} else {
    	    $func = "error";
    	    $RAD_errorstr .= $cmdSQL.$db -> Error;
      }
    }
?>
