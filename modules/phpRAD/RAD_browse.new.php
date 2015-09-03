<?
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}

include_once("modules/phpRAD/RAD_browsetree.php");
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
		if ($key=="PHPSESSID" || $key=="PHPSESSID_last" || $key=="V_idreportName" || $key=="V_reportName") continue;
		if ($val=="") continue;
		$TMP_URL.="&".$key."=".urlencode($val);
	}
	$TMP_URL=$PHP_SELF."?".substr($TMP_URL,1);
	$TMP_user = base64_decode($HTTP_SESSION_VARS["SESSION_user"]);
	if ($V_idreportName=="") $cmdImpreso="INSERT INTO impresos SET tipoimpreso=".converttosql($title).", impreso=".converttosql($V_reportName).", tabla='$V_tablename', tipodoc='$V_typePrint', url=".converttosql($TMP_URL).", observaciones='Informe generado por $TMP_user en ".date("Y-m-d H:i:s")."'";
	else $cmdImpreso="UPDATE impresos SET tipoimpreso=".converttosql($title).", impreso=".converttosql($V_reportName).", tabla='$V_tablename', tipodoc='$V_typePrint', url=".converttosql($TMP_URL).", observaciones='Informe generado por $TMP_user en ".date("Y-m-d H:i:s")."' WHERE idimpreso='$V_idreportName'";
	RAD_printLog($cmdImpreso);
	sql_query($cmdImpreso,$RAD_dbi);
}
if ($V_reportName!="" || $V_idreportName!="") {
	if ($V_reportName=="") $V_reportName=RAD_lookup("impresos","impreso","idimpreso",$V_idreportName);
	echo "\n<script>\ndocument.title=document.title+'. ".str_replace("\n","",str_replace("\r","",RAD_convertHTML2TXT($V_reportName)))."';\n</script>\n<TABLE width=100% class=borde><TR><TH>".$V_reportName."</TH></TR></TABLE>";
}
$TMP_brline=array();
if ($func == "browse" || $func == "browsecalendar" || $func == "browsedit" || $func == "search") {
	$TMP_forms="";
//	if ($func == "search") $browsetype="";
	if ($subfunc=="report") {
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
	if ($func == "search" && $orderby == "") $orderby = $searchfield;

		switch($dbtype) {
			case "oracle":
				if ($start=="") $start = 0;
				$limitstr = ") A WHERE ROWNUM<=".($limit+$start).") WHERE RNUM>=$start";
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
			$TMP_defaultfilter .= $TMP_tablename.".".$TMP_orderby." LIKE '".$bletter."%'";
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
					if ($TMP_defaultfilter!="") $TMP_defaultfilter="ON".substr($TMP_defaultfilter,6)." AND ".$tablename.".".$fields[$findex[$orderby]]->name."=".$ptablename.".".$pfname;
					else $TMP_defaultfilter=" ON ".$tablename.".".$fields[$findex[$orderby]]->name."=".$ptablename.".".$pfname;
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
				$cmdSQL="SELECT * FROM $tablename ".$TMP_defaultfilter;
				if ($dbtype=="oracle") $cmdSQL=$prelimitstr.$cmdSQL.$TMP_groupby.$limitstr;
				else $cmdSQL.=$TMP_groupby." LIMIT $limitstr";
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
		if (_SQL_DEBUG!="0") echo $TMP_initime." SQL query: ".$cmdSQL;
		if ($db -> query($cmdSQL)) {
			$TMP_diftime=RAD_microtime()-$TMP_initime;
			if (_SQL_DEBUG!="0") echo " [".substr($TMP_diftime,0,8)."]";
			if ($func!="browsecalendar") echo "<CENTER><!*><TABLE class=browse>\n";
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
		$TMP_cab="<TR>";
	      if ($browsetype=="editline" || $browsetype=="line" || $browsetype=="insert") $TMP_cab.="<TH class=browse>#</TH>";
	      if ($browsetype=="deleteline") $TMP_cab.="<TH class=browse><img src='images/delete.gif' border=0 alt='"._DEF_NLSDeleteStringº."' title="._DEF_NLSDeleteString."''></TH>";
	      if ($RAD_CONFIRM_SAVEREG=="") $TMP_confirm_savereg="\n<script type=text/javascript>\nfunction saveregs() {\n      csave=confirm(\"¿Esta seguro de guardar los registros cambiados?\");\n  if (csave) document.forms.F.submit();\n}\n</script>\n";
	      else $TMP_confirm_savereg="\n<script type=text/javascript>\nfunction saveregs() { document.forms.F.submit(); }\n</script>\n";
	      if (($func!="browsecalendar")&&($browsetype=="editline" || $browsetype=="deleteline")) echo "
<script type='text/javascript'>
function deleregs() {
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
	dele=confirm(\"¿Esta seguro de borrar los \"+numregdel+\" registros marcados?\");
	if (dele) {
	    document.forms.F.func.value='delete';
	    document.forms.F.submit();
	}
}
function deleregx(numform) {
	dele=confirm(\"¿Esta seguro de borrar este registro?\");
	var name='x';
	if (dele) {
		var numobj=-1;
		for (var i=0; i<document.forms.length; i++) {
//			alert(i+' '+document.forms[i]);
			for (var j=0; j<document.forms[i].elements.length; j++) {
				if ((document.forms[i].elements[j].name=='numform')&&
				   (document.forms[i].elements[j].value==numform)) {
					document.forms[i].submit();
				}
			}
		}
	}

}
function savedeleregs() {
	savedele=confirm(\"¿Esta seguro de borrar los registros marcados y guardar los registros cambiados?\");
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
	if (savedele) document.forms.F.submit();
}
</script>
".$TMP_confirm_savereg;
	    else echo $TMP_confirm_savereg;
            for ($i = 0; $i < $numf; $i++) {
		if ($fields[$i]->title == "") continue;
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
						if ($subbrowseSID!="") {
							if ($RAD_width<2 || $RAD_width=="") $RAD_width=800;
							if ($RAD_height<2 || $RAD_height=="") $RAD_height=600;
							$TMP_linkbredit="javascript:RAD_OpenW(\"".$PHP_SELF."?func=browse&V_bredit=".$fields[$i]->name.$URLROI."\",$RAD_width,$RAD_height);";
						} else {
							$TMP_linkbredit=$PHP_SELF."?func=browse&V_bredit=".$fields[$i]->name.$URLROI;
						}
						$TMP_cab.="<A HREF='".$TMP_linkbredit."'>&nbsp;</A>";
					}
					$TMP_cab.="<A class=browse TITLE='Ordenar por ".$fields[$i] -> title."' HREF=\"$PHP_SELF?func=browse&orderby=".$fields[$i]->name.$urlASC.$tabURLROI."\">".$orderbymark.$fields[$i]->title."</A> </TH>\n";
				} else {
					$TMP_cab.="<TH class=browse>".$orderbymark.$fields[$i] -> title."</TH>\n";
				}
		    } else {
			$TMP_cab.="<TH class=browse>".$fields[$i]->title." </TH>";
		    }
                }
            }
		$TMP_cab.="</TR>\n";
		if ($func!="browsecalendar") echo $TMP_cab;
		$TMP_linesinsert="";
                if ($subfunc!="list" && $subfunc!="report" && ($browsetype=="insert"||$V_rowsinsert>0)) {
			if ($V_rowsinsert=="") $V_rowsinsert=1;
			for($ki=0; $ki<$V_rowsinsert; $ki++) {
				if ($RAD_classrow == "class=row1") $RAD_classrow = "class=row2";
				else $RAD_classrow = "class=row1";
				$TMP_linesinsert.="<input type=hidden name='VIC".$ki."_changed' value=''>\n<TR>\n";
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
						if ($fields[$i]->vonblur!="") $TMP_onchange="onchange=".$TMP_onchange." onblur=".$fields[$i]->vonblur;
						$TMP_linesinsert.=RAD_editfield($fields[$i]->name, $fields[$i]->dtype, $fields[$i]->length, $fields[$i]->ilength, $TMP_extra, $TMP_onchange, $fields[$i]->canbenull, $fvalue,"VI".$ki."_".$fields[$i]->name);
					    }
					    $TMP_linesinsert.="</TD>";
					} else if ($fields[$i]->browsable && $fields[$i]->dtype!="function") {
					    $TMP_linesinsert.="<TD $RAD_classrow>";
					    if ($fields[$i]->dtype!="auto_increment") {
						$TMP_linesinsert.="<INPUT TYPE=HIDDEN NAME='VI".$ki."_".$fields[$i]->name."' VALUE=\"".htmlspecialchars($fields[$i]->vdefault)."\">";
					    }
					    $TMP_linesinsert.="</TD>";
					} else {
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
                if ($RAD_classrow == "class=row1") $RAD_classrow = "class=row2";
		else $RAD_classrow = "class=row1";
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
			if ($TMP!=true && $TMP!="1") $TMP_brline[$numrow].=$TMP;
		    }
		    if ($filename!="") {
			if (file_exists("modules/$V_dir/".$filename.".defaults.php")) {
			    $TMP=include ("modules/$V_dir/".$filename.".defaults.php");
			    if ($TMP!=true && $TMP!="1") $TMP_brline[$numrow].=$TMP;
			}
		    } else {
			if (file_exists("modules/$V_dir/".$V_mod.".defaults.php")) {
			    $TMP=include ("modules/$V_dir/".$V_mod.".defaults.php");
			    if ($TMP!=true && $TMP!="1") $TMP_brline[$numrow].=$TMP;
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
		$TMP_brline[$numrow].="<! RAD_browse $numrow><TR $RAD_classrow>\n";
                if (($subfunc!="list" && $subfunc!="report")||($linkoff="")) {
			if ($defaultfunc=="edit") $TMP_nextfunc="edit";
			else $TMP_nextfunc="detail";
//			if ($defaultfunc=="detail") $TMP_nextfunc="detail";
//			else $TMP_nextfunc="edit";
			if ($subbrowseSID!="") {
			    if ($RAD_width<2 || $RAD_width=="") $RAD_width=800;
			    if ($RAD_height<2 || $RAD_height=="") $RAD_height=600;
                	    $htmllink="<A TITLE='Ver Registro ".($numrow)."' HREF='javascript:RAD_OpenW(\"".$PHP_SELF."?func=".$TMP_nextfunc.$urlidnames.$URLROI;
                	    $htmllinkend="</A>";
			} else {
                	    $htmllink="<A TITLE='Ver Registro ".($numrow)."' HREF=\"".$PHP_SELF."?func=".$TMP_nextfunc.$urlidnames.$URLROI;
                	    $htmllinkend="</A>";
			}
		}
		    if ($browsetype=="line" || $browsetype=="insert") {
			$TMP_numreg=$numrow+$start;
			if (trim($htmllink)!="") {
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
			if ($menudelete||!isset($menudelete)) $TMP_brline[$numrow].=" <a href=\"javascript:deleregx($TMP_numreg);\"><img src='images/delete.gif' border=0 alt='Borra' title='Borra'></a>";
			$TMP_forms.="\n<FORM autocomplete=off NAME='FB$TMP_numreg' ACTION=$PHP_SELF TARGET=_blank METHOD=GET><INPUT TYPE=HIDDEN NAME=numform VALUE='$TMP_numreg'><INPUT TYPE=HIDDEN NAME=func VALUE='delete'><INPUT TYPE=HIDDEN NAME=subfunc VALUE='browse'>\n".
				$FORMROI.$hiddenidnames."</FORM>";
			$TMP_brline[$numrow].="</TD>\n";
		    }
		    if ($func=="search" && $searchfield=="" && $subfunc!="report" && $db->num_rows()==1 && $dbtype!="oracle") { // num_rows no funciona bien en Oracle
			echo "<script>
			document.location.href='".$PHP_SELF."?func=".$TMP_nextfunc.$urlidnames.$URLROI."';
			</script>\n";
		    }
            	    for ($i = 0; $i < $numf; $i++) {
			if ($fields[$i]->dtype == "function" && $fields[$i]->type == "function") {
				if ($fields[$i] -> browsable) {
					$RAD_numfield=$i;
					$TMP_valorderby=include("modules/$V_dir/".$fields[$i]->extra);
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
				$htmlEdit="<a href=\"javascript:RAD_OpenW('".$PHP_SELF."?func=edit&subfunc=browse&menuoff=x&footeroff=x&headeroff=x&lapoff=x&fieldedit=".$fields[$i]->name.$urlidnames.$URLROI."&V_lap=".urlencode($fields[$i]->overlap)."',600,100);\">"._DEF_NLSBrowseEdit."</a>";
				$htmlEditEnd="";
			} else {
			    $htmlEdit=""; $htmlEditEnd="";
			}
                    if ($subfunc=="report" && ${"totalfield".$i}!="") {
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
				if ($fields[$i]->vonblur!="") $fields[$i]->vonchange="onchange=".$fields[$i]->vonchange." onblur=".$fields[$i]->vonblur;
				$TMP_brline[$numrow].=RAD_editfield($fields[$i]->name, $fields[$i]->dtype, $fields[$i]->length, $fields[$i]->ilength, $TMP_extra, $fields[$i]->vonchange, $fields[$i]->canbenull, $fvalue,"V".$numrow."_".$fields[$i]->name);
			}
			if (trim($htmllink)!="") {
			    if ($subbrowseSID!="") $TMP_brline[$numrow].=$htmllink."&V_lap=".urlencode($fields[$i]->overlap)."\",$RAD_width,$RAD_height);'>";
			    else $TMP_brline[$numrow].=$htmllink."&V_lap=".urlencode($fields[$i]->overlap)."\">";
			}
                	if ($fields[$i]->dtype == "function") {
				$RAD_numfield=$i;
				$TMP_valorderby=include("modules/$V_dir/".$fields[$i]->extra);
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
			    $files = explode("\n", $db->Record[$fields[$i]->name]);
			    if (count($files) >1) {
				for ($k = 0; $k < count($files); $k++) {
				    	$files[$k]=str_replace("\n", "", $files[$k]);
				    	$files[$k]=str_replace("\r", "", $files[$k]);
	    		    	    	if ($files[$k]!="") {
						$TMP_brline[$numrow].="\n<IMG border=0 SRC=\"files/$dbname/".RAD_urlencodeFich($files[$k])."\"><br>\n";
					}
				}
			    } else {
				$db->Record[$fields[$i]->name]=str_replace("\n", "", $db->Record[$fields[$i]->name]);
				$db->Record[$fields[$i]->name]=str_replace("\r", "", $db->Record[$fields[$i]->name]);
			      if ($db->Record[$fields[$i]->name]!="") {
					$TMP_brline[$numrow].="\n<IMG border=0 SRC=\"files/$dbname/".RAD_urlencodeFich($db->Record[$fields[$i]->name])."\"><br>\n";
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
			    if ($fields[$i]->dtype!="num" && $TMP_value=="") $TMP_value.="&nbsp;";
			    if (strlen($TMP_value)>255 && $subfunc!="list" && $subfunc!="report") $TMP_value=substr($TMP_value,0,255)." ...";
//----- calcula enlace a funclink
				$TMP_linkvalue="";
				$value=$db -> Record[$fields[$i] -> name];
				$TMP_funclink=$fields[$i]->funclink;
				$A_TMP_funclink=explode("/",$TMP_funclink);
				if (count($A_TMP_funclink)>1) { $TMP_funclink=$A_TMP_funclink[1]; $TMP_dir=$A_TMP_funclink[0]; }
				else { $TMP_dir=$V_dir; }
				if ($TMP_funclink!="") if (is_modulepermitted("", $TMP_dir, $TMP_funclink) || is_admin()) {
					if (in_array($fields[$i]->dtype,array("plistm","plistdbm","plistdbmtree","popupdbm","checkboxm","checkboxdbm"))){
					    // Campo multiple con modulo de enlace
					    $TMP_arr = explode('<br>',$TMP_value);
	    				    if (count($TMP_arr)>0) $TMP_value = implode("</a>\n<a href=\"javascript:RAD_OpenW('$PHP_SELF?V_dir=$TMP_dir&V_mod=$TMP_funclink&dbname=$dbname&headeroff=X&footeroff=X&menuoff=&func=detail&par0=%%1%%&subfunc=browse',800,600);\"><img src='images/lupa.gif'></a><br>\n",$TMP_arr);
					    if ($TMP_value!="") $TMP_value = "<a href=\"javascript:RAD_OpenW('$PHP_SELF?V_dir=$TMP_dir&V_mod=$TMP_funclink&dbname=$dbname&headeroff=X&footeroff=X&menuoff=&func=detail&par0=%%1%%&subfunc=browse',800,600);\">".$TMP_value."</a>";
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
				    	    //$TMP_linkvalue="<a href=\"javascript:RAD_OpenW('$PHP_SELF?V_dir=$TMP_dir&V_mod=$TMP_funclink&dbname=$dbname&headeroff=X&footeroff=X&menuoff=&func=detail&par0=".urlencode($value)."&subfunc=browse&V_roi=".urlencode($V_roi)."',800,600);\">";
				    	    if ($value!="") $TMP_linkvalue="<a href=\"javascript:RAD_OpenW('$PHP_SELF?V_dir=$TMP_dir&V_mod=$TMP_funclink&dbname=$dbname&headeroff=X&footeroff=X&menuoff=&func=detail&par0=".urlencode($value)."&subfunc=browse',800,600);\"><img src='images/lupa.gif'></a>";
					}
			        } else {
					$arrtmpx[0] = strpos($fname,"_");
			        	$arrtmpx[1] = substr($fname,$arrtmpx[0]+1);
					$TMP_funclink=$fields[$findex[$arrtmpx[1]]]->funclink;
					$A_TMP_funclink=explode("/",$TMP_funclink);
					if (count($A_TMP_funclink)>1) { $TMP_funclink=$A_TMP_funclink[1]; $TMP_dir=$A_TMP_funclink[0]; }
					else { $TMP_dir=$V_dir; }
					if ($TMP_funclink!="") if (is_modulepermitted("", $TMP_dir, $TMP_funclink) || is_admin()) {
					    if (in_array($fields[$i]->dtype,array("plistm","plistdbm","plistdbmtree","popupdbm","checkboxm","checkboxdbm"))){
						$TMP_arr = explode('<br>',$TMP_value);
	    					if (count($TMP_arr)>0) $TMP_value = implode("</a>\n<br><a href=\"javascript:RAD_OpenW('$PHP_SELF?V_dir=$TMP_dir&V_mod=$TMP_funclink&dbname=$dbname&headeroff=X&footeroff=X&menuoff=&func=detail&par0=%%1%%&subfunc=browse',800,600);\">",$TMP_arr);
						if ($TMP_value!="") $TMP_value .= "<a href=\"javascript:RAD_OpenW('$PHP_SELF?V_dir=$TMP_dir&V_mod=$TMP_funclink&dbname=$dbname&headeroff=X&footeroff=X&menuoff=&func=detail&par0=%%1%%&subfunc=browse',800,600);\"><img src='images/lupa.gif'></a>";
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
					        //$TMP_linkvalue="<a href=\"javascript:RAD_OpenW('$PHP_SELF?V_dir=$TMP_dir&V_mod=$TMP_funclink&dbname=$dbname&headeroff=X&footeroff=X&menuoff=&func=detail&par0=".urlencode($value)."&subfunc=browse&V_roi=".urlencode($V_roi)."',800,600);\">";
						if ($value!="") $TMP_linkvalue="<a href=\"javascript:RAD_OpenW('$PHP_SELF?V_dir=$TMP_dir&V_mod=$TMP_funclink&dbname=$dbname&headeroff=X&footeroff=X&menuoff=&func=detail&par0=".urlencode($value)."&subfunc=browse',800,600);\"><img src='images/lupa.gif'></a>";
					    }
					}
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
 			$TMP_brline[$numrow].=$htmllinkend.$htmlEditEnd.$TMP_linkvalue." </TD>\n";
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
		    if ($subbrowseSID!="") $RAD_taskscalendar{$xdia.$xmes.$xano."24"}.="".$htmllink."\",$RAD_width,$RAD_height);'>".$TMP_lit.$htmllinkend."<br/>\n";
		    else $RAD_taskscalendar{$xdia.$xmes.$xano."24"}.="".$htmllink."\">".$TMP_lit.$htmllinkend."<br/>\n";
		}
		if (eregi("plist", $fields[$findex[$orderby]]->dtype) || eregi("rlist", $fields[$findex[$orderby]]->dtype) || eregi("check", $fields[$findex[$orderby]]->dtype) || eregi("popup", $fields[$findex[$orderby]]->dtype)) {
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
			if ($desc !="") arsort($TMP_linelit,SORT_STRING);
			else asort($TMP_linelit,SORT_STRING);
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
		if (($func!="browsecalendar")&&($TMP_reportTotalLine==true)) {
		    echo "<TR $RAD_classrow>";
		    if ($browsetype=="line" || $browsetype=="editline" || $browsetype=="insert" || $browsetype=="deleteline") echo "<TD $RAD_classrow> </TD>";
	            for ($i = 0; $i < $numf; $i++) {
//			if ($subfunc=="report" && ${"browsefield".$i}!="")
			if ($subfunc=="report" && $fields[$i]->browsable)
	     	      	echo "<TD $RAD_classrow><b>".${"Ttotalfield".$i}."</b></TD>";
           	    }
		    echo "</TR>";
		}

//	    if ($func!="browsecalendar" && $opcionesmenubrowse!="") echo "<TR><TD><TABLE cellpadding=0 cellspacing=0 class=menu><TR>".RAD_menu_on("<A ACCESSKEY='L' TITLE='ALT+L' HREF=\"$PHP_SELF?func=browse&orderby=$orderby&start=0$tabURLROI\" class=menuon>"._DEF_NLSListAll."</A> $opcionesmenubrowse")."</TD></TR></TABLE></TR>";
//	    if ($func!="browsecalendar" && ($prevpage!=""||$nextpage!="")) echo "<TR><TD><TABLE cellpadding=0 cellspacing=0 class=menu><TR>".RAD_menu_on("$prevpage &nbsp;&nbsp;&nbsp; $nextpage")."</TD></TR></TABLE></TR>";
	    if ($func!="browsecalendar") echo "</TABLE></CENTER>\n";
	    if (($subfunc == "list")||($subfunc == "report")||($func == "search" && $showall != "")) {
		echo " <b>".$TMP_contlin."</b>";
		if ($subfunc=="list" || $subfunc=="report") echo "\n<script type='text/javascript'>\nwindow.print();\n</script>\n";
	    } else if ($func!="browsecalendar" && ($prevpage!=""||$nextpage!="")) echo "&nbsp; $prevpage &nbsp;&nbsp;&nbsp; $nextpage";
            if ($func!="browsecalendar" && $func!="print" && $subfunc!="list" && (($browsedit && $numrow>1) || $browsetype=="insert" || $V_rowsinsert>0)) {
		if (empty($RAD_browseditbuttons)){
			echo "\n<TABLE WIDTH=100%><TR><TD ALIGN=RIGHT><INPUT TYPE=BUTTON ";
			if (($browsedit||$browsetype=="insert"||$V_rowsinsert>0) && $browsetype=="deleteline") echo "onClick='javascript:savedeleregs();' ";
			else if ($browsetype=="deleteline") echo "onClick='javascript:deleregs();' ";
			else echo "onClick='javascript:saveregs();' ";
			echo "class=button NAME=Save ACCESSKEY='S' TITLE='ALT+S' VALUE="._DEF_NLSSave."></TD><TD ALIGN=LEFT>&nbsp;<INPUT TYPE=RESET class=button  ACCESSKEY='R' TITLE='ALT+R' VALUE="._DEF_NLSReset."></TD></TR></TABLE>";
		}
		echo "<INPUT TYPE=HIDDEN NAME=V_numrows VALUE='$numrow'></FORM>\n";
            } else if ($func!="browsecalendar" && $func!="print" && $subfunc!="list" && $browsetype=="deleteline") {
		echo "\n<TABLE WIDTH=100%><TR><TD ALIGN=RIGHT><INPUT TYPE=BUTTON onClick='javascript:deleregs();' class=button NAME=Delete ACCESSKEY='S' TITLE='ALT+D' VALUE="._DEF_NLSDeleteString."></TD><TD ALIGN=LEFT>&nbsp;<INPUT TYPE=RESET class=button  ACCESSKEY='R' TITLE='ALT+R' VALUE="._DEF_NLSReset."></TD></TR></TABLE><INPUT TYPE=HIDDEN NAME=V_numrows VALUE='$numrow'></FORM>\n";
            } else if ($func!="browsecalendar" && $func!="print" && $subfunc!="list" && $browsedit) echo "</FORM>\n";
	    if ($func!="browsecalendar" && $subfunc!="list" && $subfunc!="report") echo $TMP_forms;
	    if ($func=="browsecalendar") {
		$TMP_result=RAD_showcalendar($year,$month,$week,$day,"&dbname=$dbname&func=$func");
		echo $TMP_result;
	    }
	} else {
    	    $func = "error";
    	    $errorstr .= $cmdSQL.$db -> Error;
      }
    }
?>
