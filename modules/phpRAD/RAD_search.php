<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}
if ($func == "searchform" && $menusearch == false) {
    $func="error";
}
global $SESSION_SID;
//---------------------------------------------------------------------------
//------------------------- Search
//---------------------------------------------------------------------------
if ($func == "searchform") {
	$TMP_user=base64_decode($HTTP_SESSION_VARS["SESSION_user"]);
	if (RAD_existTable("impresos") && $RAD_nosearchformimpresos=="") {
		if ($V_idreportName!="") {
			$TMP_result=sql_query("SELECT * FROM impresos WHERE idimpreso='$V_idreportName'",$RAD_dbi);
			$TMP_row=sql_fetch_array($TMP_result,$RAD_dbi);
			$TMP_row[url]=str_replace("?","&",$TMP_row[url]);
			$TMP_params=explode("&",$TMP_row[url]);
			$V_reportName=$TMP_row[impreso];
			for ($ki=0; $ki<$numf; $ki++) {
				if (ereg("&browsefield".$ki."=x&",$TMP_row[url])) $fields[$ki]->browsable=true;
				else $fields[$ki]->browsable=false;
			}
			if (count($TMP_params)>0) {
				for ($ki=0; $ki<count($TMP_params); $ki++) {
					$TMP_param=explode("=",$TMP_params[$ki]);
					if (count($TMP_param)==2) $REPORT_default[$TMP_param[0]]=urldecode($TMP_param[1]);
				}
			}
		} else {
            		// Compatibilidad con la existencia de campos de modulo y usuario para filtrar mejor los impresos de cada uno
			$TMP_where=""; $TMP_campo=array();
			$campos=sql_list_fields("impresos", $RAD_dbi);
			while($columnas=sql_fetch_array($campos, $RAD,dbi)) $TMP_campo[]=$columnas[0];
			if (in_array('modulo', $TMP_campo)) {
				$TMP_cmd="SELECT * FROM impresos WHERE (modulo='$V_mod' OR ((modulo='' or modulo IS NULL) AND tabla='".$V_tablename."'))";
			} else {
				$TMP_cmd="SELECT * FROM impresos WHERE tabla='".$V_tablename."'";
			}
			if (in_array('usuario', $TMP_campo)) $TMP_cmd.=" AND (usuario='".$TMP_user."' or usuario is null or usuario='')";
			//$TMP_cmd.=" AND (url IS NOT NULL AND url!='')";
			$TMP_URL=$PHP_SELF."?";
			foreach ($_REQUEST as $key=>$val) $TMP_URL.=$key."=".urlencode($val)."&";
			$TMP_cmd.=" ORDER BY impreso ASC";
			$TMP_result=sql_query($TMP_cmd,$RAD_dbi);
			echo "<div align=right>\n";
			$TMP_cont=0;
			while($TMP_row=sql_fetch_array($TMP_result,$RAD_dbi)) {
				if (trim($TMP_row[url])!="") {
					if ($subfunc=="report" && !ereg("&subfunc=report&",$TMP_row[url])) continue;
					if ($subfunc!="report" && ereg("&subfunc=report&",$TMP_row[url])) continue;
				}
				if (trim($TMP_row[tipodoc])=="" && trim($TMP_row[impreso])=="") continue;
				$TMP_cont++;
				if ($TMP_cont>1) echo "|&nbsp;";
				echo "<nowrap>";
				if (trim($TMP_row[url])!="") {
					$TMP_URLImpreso=$TMP_row[url];
					echo " <a href='".$TMP_URL."V_idreportName=".$TMP_row[idimpreso]."'><img src='images/edit.gif' border=0 alt=''></a>";
				} else {
					$TMP_URLImpreso=$TMP_URL."&V_typePrint=impreso&V_idimpreso=".$TMP_row[idimpreso]."&V_send=&V_save=x&dbname=$dbname";
				}
				echo " <a href='".$TMP_URLImpreso.$SESSION_SID."&V_idreportName=".$TMP_row[idimpreso]."'>".$TMP_row[impreso];
				if ($TMP_row[tipodoc]!="") echo " [".$TMP_row[tipodoc]."]";
				echo "</a>&nbsp;</nowrap>";
			}
			echo "\n</div>";
		}
	}
 	$FORMROI=RAD_delParamFORM($FORMROI, "bletter");
 	$FORMROI=RAD_delParamFORM($FORMROI, "V_prevfunc");
 	$FORMROI=RAD_delParamFORM($FORMROI, "orderby");
 	echo "<FORM autocomplete=off ACTION=$PHP_SELF METHOD=POST NAME=F>$FORMROI\n";
 	echo "<INPUT TYPE=HIDDEN NAME='V_prevfunc' VALUE='searchform'>";
	echo "<TABLE class=detail>\n";

	if ($subfunc=="report") echo "<tr class=detail><th class=detailtit>"._DEF_NLSShow."</th><th class=detailtit>"._DEF_NLSTotal."</th><th colspan=3 class=detailtit><center>"._DEF_NLSField."</center></th></tr>\n";
	if (file_exists("modules/$V_dir/common.defaults.php")) {
		$TMP=include ("modules/$V_dir/common.defaults.php");
		if ($TMP!==true && $TMP!="1") echo $TMP;
		$TMP="";
	}
	if ($filename!="") {
		if (file_exists("modules/$V_dir/".$filename.".defaults.php")) {
		    $TMP="";
		    $TMP=include ("modules/$V_dir/".$filename.".defaults.php");
		    if ($TMP!==true && $TMP!="1") echo $TMP;
		}
	} else {
		if (file_exists("modules/$V_dir/".$V_mod.".defaults.php")) {
		    $TMP="";
		    $TMP=include ("modules/$V_dir/".$V_mod.".defaults.php");
		    if ($TMP!==true && $TMP!="1") echo $TMP;
		}
	}
	$TMP_setfoco_primer_campo=false;
	for ($i=0; $i<$numf; $i++) {
		if ($subfunc=="report") {
			if ($fields[$i]->searchable) {
				echo "<TR class=detail>";
				echo "<TD class=detail><INPUT TYPE=checkbox NAME='browsefield".$i."' VALUE='x'";
				if ($fields[$i]->browsable) echo " checked";
				echo "></TD>";
				echo "<TD class=detail><INPUT TYPE=checkbox NAME='totalfield".$i."' VALUE='x'";
				if ($fields[$i]->browsable && substr($fields[$i]->dtype,0,3)=="num") echo " checked";
				echo "></TD>";
			}
		} else {
			if (($fields[$i]->searchable)&&($fields[$i]->dtype!="function")) echo "<TR class=detail>";
		}
		if (($fields[$i]->searchable)&&($fields[$i]->dtype=="function")) {
		    if (substr($fields[$i]->extra,0,13)=="RAD_subbrowse") continue;
		    if (!file_exists("modules/$V_dir/".$fields[$i]->extra) && file_exists("modules/phpRAD/".$fields[$i]->extra)) {
			$TMP_funcDir="modules/phpRAD/"; // directorio por defecto de funciones comunes
		    } else {
			$TMP_funcDir="modules/$V_dir/";
		    }
		    $TMP=include ($TMP_funcDir.$fields[$i]->extra);
		    if ($TMP!==true && $TMP!="1") echo $TMP;
		    if ($TMP=="") {
			echo "<INPUT TYPE=HIDDEN NAME='searchfield".$i."' VALUE='".$fields[$i] -> name."'>";
			echo "<TD class=detailtit>".$fields[$i] -> title.":</TD>";
			echo "<TD class=detail colspan=2></TD></TR>";
		    }
		    continue;
		}
		if ($fields[$i]->dtype=="file" || $fields[$i]->dtype=="image") $fields[$i]->dtype="stand";
		if (($fields[$i]->searchable)&&($fields[$i]->dtype!="function")) {
			echo "<INPUT TYPE=HIDDEN NAME='searchfield".$i."' VALUE='".$fields[$i] -> name."'>";
			echo "<TD class=detailtit>".$fields[$i] -> title.":</TD>";
			echo "<TD class=detail>";
			$TMP_onchange="if (this.value!=\"\") document.F.operator".$i.".selectedIndex=\"1\"; else document.F.operator".$i.".selectedIndex=\"0\";";
			if (in_array($fields[$i]->dtype,array('plistdb','plistdbtree','plist','rlist','rlistdb','checkbox','checkboxdb','num','numR','numD','numeric'))) $TMP_onchange="if (this.value!=\"\") document.F.operator".$i.".selectedIndex=\"1\"; else document.F.operator".$i.".selectedIndex=\"0\";";
			if (in_array($fields[$i]->dtype,array('fbpopupdb','fpopupdb'))) $TMP_onchange="document.F.operator".$i.".selectedIndex=\"1\";";
			if (in_array($fields[$i]->dtype,array('datetime','datetimeint','datetimeinttext','date'))) $TMP_onchange="if (this.value!=\"\") document.F.operator".$i.".selectedIndex=\"1\";";
			//if (trim($fields[$i]->vonchange)!="") $TMP_onchange.=$fields[$i]->vonchange;
//			if (substr($fields[$i]->dtype,0,5)=="plist") $fields[$i]->dtype="popup".substr($fields[$i]->dtype,5);
			$TMP_last_search="";
			$TMP_last_searchTO="";
			$TMP_last_searchop="";
			if ($reset!="") {
				setSessionVar("SESSION_srch_".$V_mod.$fields[$i]->name,"",0);
				setSessionVar("SESSION_srch_".$V_mod.$fields[$i]->name."TO","",0);
				setSessionVar("SESSION_srchop_".$V_mod.$fields[$i]->name,"",0);
			}
			if (_DEF_REMEMBER_LASTSEARCH=="1") {
				$TMP_last_search=getSessionVar("SESSION_srch_".$V_mod.$fields[$i]->name);
				$TMP_last_searchTO=getSessionVar("SESSION_srch_".$V_mod.$fields[$i]->name."TO");
				$TMP_last_searchop=getSessionVar("SESSION_srchop_".$V_mod.$fields[$i]->name);
			}
			if ($V_idreportName!="" && $REPORT_default["MaxSearchFields"]>0) {
				for ($ki=0; $ki<$REPORT_default["MaxSearchFields"]; $ki++) {
					if ($REPORT_default["operator".$ki]=="") continue;
					if ($REPORT_default["searchfield".$ki]!=$fields[$i]->name) continue;
					$TMP_last_searchop=$REPORT_default["operator".$ki];
					if (isset($REPORT_default[$fields[$i]->name."_year"])) $REPORT_default[$fields[$i]->name]=$REPORT_default[$fields[$i]->name."_year"]."-".$REPORT_default[$fields[$i]->name."_month"]."-".$REPORT_default[$fields[$i]->name."_day"];
					if (isset($REPORT_default[$fields[$i]->name."_year"]) && isset($REPORT_default[$fields[$i]->name."_hour"])) $REPORT_default[$fields[$i]->name].=" ";
					if (isset($REPORT_default[$fields[$i]->name."_hour"])) $REPORT_default[$fields[$i]->name].=$REPORT_default[$fields[$i]->name."_hour"].":".$REPORT_default[$fields[$i]->name."_min"].":".$REPORT_default[$fields[$i]->name."_sec"];
					$TMP_last_search=$REPORT_default[$fields[$i]->name.""];
					if (isset($REPORT_default[$fields[$i]->name."TO_year"])) $REPORT_default[$fields[$i]->name."TO"]=$REPORT_default[$fields[$i]->name."TO_year"]."-".$REPORT_default[$fields[$i]->name."TO_month"]."-".$REPORT_default[$fields[$i]->name."TO_day"];
					if (isset($REPORT_default[$fields[$i]->name."TO_year"]) && isset($REPORT_default[$fields[$i]->name."TO_hour"])) $REPORT_default[$fields[$i]->name."TO"].=" ";
					if (isset($REPORT_default[$fields[$i]->name."TO_hour"])) $REPORT_default[$fields[$i]->name."TO"].=$REPORT_default[$fields[$i]->name."TO_hour"].":".$REPORT_default[$fields[$i]->name."TO_min"].":".$REPORT_default[$fields[$i]->name."TO_sec"];
					$TMP_last_searchTO=$REPORT_default[$fields[$i]->name."TO"];
				}
			}
			if (${"searchop".$i}!="") $TMP_last_searchop=${"searchop".$i};
			if ($TMP_last_search!="") ${"searchvalue".$i}=$TMP_last_search;
			if ($TMP_last_searchTO!="") ${"searchvalueTO".$i}=$TMP_last_searchTO;
			if ($reset!="") {
			    ${"searchvalue".$i}="";
			    ${"searchvalueTO".$i}="";
			    ${"searchop".$i}="";
			    $TMP_last_searchop="";
			}
			$TMP_extra=$fields[$i]->extra;
			if ($fields[$i]->parmlistSFF!="") $TMP_extra.="|".$fields[$i]->parmlistSFF;
			if ($fields[$i]->vonblur!="") $TMP_onchange="onchange=".$TMP_onchange." onblur=".$fields[$i]->vonblur;
			if ($fields[$i]->dtype=="datetime") {
				${"searchvalue".$i}=str_replace("%","",${"searchvalue".$i});
				${"searchvalueTO".$i}=str_replace("%","",${"searchvalueTO".$i});
			}
			if ($fields[$i]->dtype=="texthtml"||$fields[$i]->dtype=="text") $TMP=RAD_editfield($fields[$i]->name, "stand", "255", "60", $TMP_extra, $TMP_onchange, true, ${"searchvalue".$i},"");
			else $TMP=RAD_editfield($fields[$i]->name, $fields[$i]->dtype, $fields[$i]->length, $fields[$i]->ilength, $TMP_extra, $TMP_onchange, true, ${"searchvalue".$i},"");
			if ($fields[$i]->type=="datetime" || $fields[$i]->type=="date")
				$TMP=" ".$TMP." [ ... ".RAD_editfield($fields[$i]->name."TO", $fields[$i]->dtype, $fields[$i]->length, $fields[$i]->ilength, $TMP_extra, $TMP_onchange, true, ${"searchvalueTO".$i},"")." ]";
			echo $TMP." </TD><TD class=detail>";
			RAD_searchoperator($fields[$i],$i,$TMP_last_searchop);
			echo "</TD>\n</TR>";
		} else {
			if ($subfunc=="report" && $fields[$i]->searchable) {
				echo "<TD class=detailtit>".$fields[$i] -> title."</b></TD>";
				echo "<TD colspan=2 class=detail>&nbsp;</TD>\n</TR>";
			}
		}
		if (!$TMP_setfoco_primer_campo) {
			//if (!eregi("popup", $fields[$i]->dtype)&&$fields[$i]->searchable && !eregi("date",$fields[$i]->dtype)) {
			if (substr($fields[$i]->dtype,0,5)!="popup" && $fields[$i]->searchable) {
				if (substr($fields[$i]->dtype,0,4)=="date") {
					if (eregi("text",$fields[$i]->dtype)) {
						if ($RAD_html5=="1") $TMP_inputfieldname=$fields[$i]->name."_date5";
						else $TMP_inputfieldname=$fields[$i]->name."_date";
					} else $TMP_inputfieldname=$fields[$i]->name."_day";
				} else if (substr($fields[$i]->dtype,0,4)=="time") {
					if (ereg("text",$fields[$i]->dtype)) $TMP_inputfieldname=$fields[$i]->name."_time";
					else $TMP_inputfieldname=$fields[$i]->name."_hour";
				} else if (substr($fields[$i]->dtype,0,6)=="bpopup") {
					$TMP_inputfieldname=$fields[$i]->name."_literal";
				} else $TMP_inputfieldname=$fields[$i]->name;
				echo "\n<script>\ndocument.F.".$TMP_inputfieldname.".focus();\nif (document.F.".$TMP_inputfieldname.".type=='text') document.F.".$TMP_inputfieldname.".select();\n</script>\n";
				$TMP_setfoco_primer_campo=true;
			}
		}
	}
	echo "</TABLE>\n";
	echo "<CENTER><INPUT TYPE=HIDDEN NAME=PHPSESSID VALUE='$PHPSESSID'>";
	if (RAD_existTable("impresos")) {
		if ($V_idreportName=="") echo _DEF_NLSSave." "._DEF_NLSCriteriaNew.": <input type=text size=20 name='V_reportName'> "._DEF_NLSReportNameNew."<br>";
		else if (trim($V_reportName)=="") echo _DEF_NLSModify." "._DEF_NLSCriteriaMod." criterio de seleccion <input type=text name='V_reportName' size=20 value='$V_reportName'><input type=hidden name='V_idreportName' value='$V_idreportName'> "._DEF_NLSReportNameMod."<br>";
		else echo _DEF_NLSModify." "._DEF_NLSCriteriaMod." <b>$V_reportName</b><input type=hidden name='V_reportName' value='$V_reportName'><input type=hidden name='V_idreportName' value='$V_idreportName'> "._DEF_NLSReportNameMod."<br>";
	}
	if ($subfunc!="report") {
		if ($showall=="") echo "<INPUT TYPE=checkbox NAME=showall VALUE='x'> "._DEF_NLSShowAll." ";
		else echo "<INPUT TYPE=checkbox NAME=showall VALUE='x' CHECKED> "._DEF_NLSShowAll." ";
	}
	$TMP_orderby="";
    	for ($i=0; $i<$numf; $i++) {
	    if ($fields[$i]->orderby && $fields[$i]->browsable) {
	    	$TMP_orderby.="<OPTION VALUE='".$fields[$i]->name."'";
		if ($orderby==$fields[$i]->name) $TMP_orderby.=" SELECTED";
		$TMP_orderby.=">".$fields[$i]->title."</OPTION>";
	    	$TMP_orderby.="<OPTION VALUE='".$fields[$i]->name." DESC'";
		if ($orderby==$fields[$i]->name && $desc=="x") $TMP_orderby.=" SELECTED";
		$TMP_orderby.=">".$fields[$i]->title." DESC</OPTION>";
	    }
	}
	if ($TMP_orderby!="" && $subfunc!="report") echo " | ";
	if ($TMP_orderby!="") echo _DEF_NLSSortBy." <SELECT NAME=orderby><OPTION VALUE=''></OPTION>".$TMP_orderby."</SELECT> &nbsp;";
	else echo "<INPUT TYPE=HIDDEN NAME=orderby VALUE='$orderby'>";
	echo "<INPUT TYPE=HIDDEN NAME=func VALUE='search'><INPUT TYPE=HIDDEN NAME=start VALUE='0'>";
	echo "<INPUT TYPE=HIDDEN NAME=subfunc VALUE='$subfunc'>";
	if ($subfunc=="report")
		echo "<INPUT TYPE=HIDDEN NAME=MaxSearchFields VALUE='$numf'><INPUT ACCESSKEY='S' TITLE='ALT+S' TYPE=SUBMIT class=button VALUE='"._DEF_NLSStringReport."'>";
	else
		echo "<INPUT TYPE=HIDDEN NAME=MaxSearchFields VALUE='$numf'><INPUT ACCESSKEY='S' TITLE='ALT+S' TYPE=SUBMIT class=button VALUE='"._DEF_NLSSearchButton."'>";
	echo " <INPUT ACCESSKEY='R' TITLE='ALT+R' TYPE=BUTTON OnClick=\"javascript:document.location=document.location.href+'&reset=x'\" class=button VALUE='"._DEF_NLSClearAll."'>";
	if ($searchclean=='X') {
		echo "<input type='hidden' name='V_typePrint' value=''>";
	} else {
		echo " "._DEF_NLSResultAs." : <input type=radio name=V_typePrint value='' checked> HTML &nbsp; <input type=radio name=V_typePrint value='CSV'> <img src='images/xls.gif'> Excel &nbsp;";
                if (_DEF_dbtype!="Oracle") echo "<input type=radio name=V_typePrint value='PDF'> PDF &nbsp;"; //Como esta funcion aun no est muy depurada lo oculto para aplicacioes de Libra
		// <input type=radio name=V_typePrint value='TXT'> TXT &nbsp;
                echo "<input type=hidden name=V_save value='x'>";
                echo "<input type=checkbox name=print value='x'> Imprimir &nbsp;";
	}
	echo "</FORM></CENTER>";
}
//---------------------------------------------------------------------------
function RAD_searchoperator($field,$num,$default)	{
if (_DEF_DefaultSearch!="" && _DEF_DefaultSearch!="_DEF_DefaultSearch" && $default=="") {
//    $default=_DEF_DefaultSearch;
}

echo "\n<SELECT NAME='operator".$num."'><OPTION VALUE=''></OPTION>\n";
if ($default!="") $TMP_SELECTED="SELECTED";
else $TMP_SELECTED="";
if ($default=="") {
    if (_DEF_DefaultSearch!="" && _DEF_DefaultSearch!="_DEF_DefaultSearch" && _DEF_DefaultSearch=="=" ) {
	$default=_DEF_DefaultSearch;
    }
}

if ($field -> type == "datetime" || $field -> type == "date") {
	if ($default=="FROMTO"||$default=="BETWEEN") echo "<OPTION VALUE='BETWEEN' $TMP_SELECTED>"._DEF_NLSFromTo."</OPTION>";
	if ($default=="=") echo "<OPTION VALUE='=' $TMP_SELECTED>"._DEF_NLSEqual."</OPTION>";
} else {
	if ($default=="LIKE") echo "<OPTION VALUE='LIKE' $TMP_SELECTED>"._DEF_NLSLike."</OPTION>";
	if ($default=="=") echo "<OPTION VALUE='=' $TMP_SELECTED>"._DEF_NLSEqual."</OPTION>";
	if ($default=="BEGIN") echo "<OPTION VALUE='BEGIN' $TMP_SELECTED>"._DEF_NLSBegin."</OPTION>";
	if ($default=="NOT LIKE") echo "<OPTION VALUE='NOT LIKE' $TMP_SELECTED>"._DEF_NLSNotLike."</OPTION>";
	if ($default=="!=") echo "<OPTION VALUE='!=' $TMP_SELECTED>"._DEF_NLSNotEqual."</OPTION>";

}
if (substr($field->type,0,3)=="num" || $field->type=="date") {
	if ($default==">") echo "<OPTION VALUE='>' $TMP_SELECTED>&gt;</OPTION>";
	if ($default=="<") echo "<OPTION VALUE='<' $TMP_SELECTED>&lt;</OPTION>";
	if ($default==">=") echo "<OPTION VALUE='>=' $TMP_SELECTED>&gt;=</OPTION>";
	if ($default=="<=") echo "<OPTION VALUE='<=' $TMP_SELECTED>&lt;=</OPTION>";
}

if (!$field -> type == "enum") {
	if ($default=="IN") echo "<OPTION VALUE='IN' $TMP_SELECTED>"._DEF_NLSIn."</OPTION>";
	if ($default=="NOT IN") echo "<OPTION VALUE='NOT IN' $TMP_SELECTED>"._DEF_NLSNotIn."</OPTION>";
}
if ($field -> canbenull) {
	if ($default=="IS NULL") echo "<OPTION VALUE='IS NULL' $TMP_SELECTED>"._DEF_NLSIsNull."</OPTION>";
	if ($default=="IS NOT NULL") echo "<OPTION VALUE='IS NOT NULL' $TMP_SELECTED>"._DEF_NLSIsNotNull."</OPTION>";
}

$TMP_SELECTED="";
if ($field -> type == "datetime" || $field -> type == "date") {
    if ($default!="FROMTO" && $default!="BETWEEN") echo "<OPTION VALUE='BETWEEN' $TMP_SELECTED>"._DEF_NLSFromTo."</OPTION>";
    if ($default!="=") echo "<OPTION VALUE='=' $TMP_SELECTED>"._DEF_NLSEqual."</OPTION>";
} else {
	//if (!ereg("plist",$field->dtype) && !ereg("rlist",$field->dtype) && !ereg("db",$field->dtype) && !ereg("num",$field->dtype)) 
		if ($default!="LIKE") echo "<OPTION VALUE='LIKE' $TMP_SELECTED>"._DEF_NLSLike."</OPTION>";
	if ($default!="=") {
	    if (_DEF_dbtype=="Oracle" && $field -> name=="ESTADO_EDICION") echo "<OPTION VALUE='=' SELECTED>"._DEF_NLSEqual."</OPTION>";
	    else echo "<OPTION VALUE='=' $TMP_SELECTED>"._DEF_NLSEqual."</OPTION>";
	}
	if ($default!="BEGIN") echo "<OPTION VALUE='BEGIN' $TMP_SELECTED>"._DEF_NLSBegin."</OPTION>";
	if ($default!="NOT LIKE") echo "<OPTION VALUE='NOT LIKE' $TMP_SELECTED>"._DEF_NLSNotLike."</OPTION>";
	if ($default!="!=") echo "<OPTION VALUE='!=' $TMP_SELECTED>"._DEF_NLSNotEqual."</OPTION>";
	if (substr($field->type,0,3)=="num" || $field->type=="date") {
	    if ($default!=">") echo "<OPTION VALUE='>' $TMP_SELECTED>&gt;</OPTION>";
	    if ($default!="<") echo "<OPTION VALUE='<' $TMP_SELECTED>&lt;</OPTION>";
	    if ($default!=">=") echo "<OPTION VALUE='>=' $TMP_SELECTED>&gt;=</OPTION>";
	    if ($default!="<=") echo "<OPTION VALUE='<=' $TMP_SELECTED>&lt;=</OPTION>";
	}
}
if (!$field -> type == "enum") {
    if ($default!="IN") echo "<OPTION VALUE='IN' $TMP_SELECTED>"._DEF_NLSIn."</OPTION>";
    if ($default!="NOT IN") echo "<OPTION VALUE='NOT IN' $TMP_SELECTED>"._DEF_NLSNotIn."</OPTION>";
}

/*
if ($field -> canbenull) {
    if ($default!="IS NULL") echo "<OPTION VALUE='IS NULL' $TMP_SELECTED>"._DEF_NLSIsNull."</OPTION>";
    if ($default!="IS NOT NULL") echo "<OPTION VALUE='IS NOT NULL' $TMP_SELECTED>"._DEF_NLSIsNotNull."</OPTION>";
}*/
echo "\n</SELECT>\n";
}
?>
