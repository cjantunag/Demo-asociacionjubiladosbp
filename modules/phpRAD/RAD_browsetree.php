<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}
//---------------------------------------------------------------------------
//------------------------- Browsetree
//---------------------------------------------------------------------------
if ($func == "browsetree") {
	global $RAD_classrow;
	if ($defaultfilter!="") $TMP_defaultfilter=" WHERE ".$defaultfilter." AND ($browsetreefieldparent IS NULL OR $browsetreefieldparent='' OR $browsetreefieldparent='0')";
	else $TMP_defaultfilter="WHERE ($browsetreefieldparent IS NULL OR $browsetreefieldparent='' OR $browsetreefieldparent='0')";

	$cmdSQL="SELECT count(*) FROM $tablename";
	if ($defaultfilter!="") $cmdSQL.=" WHERE ".$defaultfilter."";

	if (!$TMP_result=sql_query($cmdSQL, $RAD_dbi)) {
		$func = "error";
		$RAD_errorstr .= $cmdSQL.sql_error($TMP_result);
	} else {
		$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);

		if ($TMP_row[0]>$rows_limit*5 && $par0=="") $TMP_onlyparents=true;
		else $TMP_onlyparents=false;

	  echo "<CENTER><TABLE class=browse>\n"; 
	  echo "<TR>";
	  if ($browsetype=="line") echo "<th class=browse>#</TH>";
	  if ($browsetype=="editline") echo "<th class=browse>#</TH>";
	  for ($i = 0; $i < $numf; $i++) {
		if ($fields[$i]->title == "") continue;
		if ($fields[$i]->name==$browsetreefieldparent) continue;
		if ($fields[$i] -> browsable) echo "<th class=browse>".$fields[$i]->title."</th>";
	  }
	  echo "</tr>\n";
	  $numrow=1;

	  $par0padres=",";
	  $par0hijos=",";
	  if ($par0!="") {	// busca padres,abuelos... e hijos de par0
		$TMP_par0=$par0;
		do {
		$cmdSQL="SELECT * FROM $tablename WHERE $browsetreefield='$TMP_par0'";
		$TMP_result=sql_query($cmdSQL, $RAD_dbi);
		$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
		if ($TMP_row[$browsetreefieldparent]) $par0padres.=$TMP_row[$browsetreefieldparent].",";
		$TMP_par0=$TMP_row[$browsetreefieldparent];
		} while ($TMP_row[$browsetreefieldparent]!="");
		$cmdSQL="SELECT * FROM $tablename WHERE $browsetreefieldparent='$par0'";
		$TMP_result=sql_query($cmdSQL, $RAD_dbi);
		while($TMP_row=sql_fetch_array($TMP_result, $RAD_dbi)) $par0hijos.=$TMP_row[$browsetreefield].",";
	  }
	  $browsedit=false;
	  for ($i = 0; $i < $numf; $i++) if ($fields[$i] -> browsedit && $fields[$i]->extra!="RAD_subbrowse.php" && $fields[$i]->extra!="RAD_subbrowse") $browsedit=true;
	  echo "<FORM autocomplete=off ACTION=$PHP_SELF"; 
	  echo " METHOD=POST NAME=F ENCTYPE='multipart/form-data'>\n";
	  echo "<INPUT TYPE=HIDDEN NAME=PHPSESSID VALUE='$PHPSESSID'>\n".$FORMROI;
	  if (($browsedit||$browsetype=="insert"||$V_rowsinsert>0) && $browsetype=="deleteline") echo "<INPUT TYPE=HIDDEN NAME=func VALUE='delete'>\n";
	  else echo "<INPUT TYPE=HIDDEN NAME=func VALUE='update'>\n";
	  echo "<INPUT TYPE=HIDDEN NAME=dbname VALUE='$dbname'>\n";
	  //echo "<INPUT TYPE=HIDDEN NAME=found VALUE='$found'>\n";
	  if ($browsedit || $browsetype=="insert" || $V_rowsinsert>0) echo "<INPUT TYPE=HIDDEN NAME=subfunc VALUE='browsedit'>\n";
	  if ($func!="search") echo "<INPUT TYPE=HIDDEN NAME=RAD_browsetype VALUE='$browsetype'>\n";

	  RAD_amplia_browsetree(0,"");
//	  RAD_amplia_browsetree(0,"-*");

	  if ($RAD_textConfirmDelete=="") {
		$TMP_confirmsavedeletes="	savedele=confirm(\"\u00BFEst\u00E1 seguro de borrar los registros marcados y guardar los registros cambiados?\");\n        if (savedele) {\n";
		$TMP_confirmdeletes="	dele=confirm(\"\u00BFEst\u00E1 seguro de borrar los registros marcados?\");\n        if (dele) {\n";
		$TMP_confirmdelete="	dele=confirm(\"\u00BFEst\u00E1 seguro de borrar este registro?\");\n        if (dele) {\n";
	  } else {
		$TMP_confirmsavedeletes="	savedele=prompt(\"Para borrar los registros marcados y guardar los registros cambiados, teclee ".$RAD_textConfirmDelete."\");\n        if (savedele==\"".$RAD_textConfirmDelete."\") {\n";
		$TMP_confirmdeletes="	dele=prompt(\"Para borrar \"+numregdel+\" registros marcados, teclee ".$RAD_textConfirmDelete."\");\n        if (dele==\"".$RAD_textConfirmDelete."\") {\n";
		$TMP_confirmdelete="	dele=prompt(\"Para borrar este registro, teclee ".$RAD_textConfirmDelete."\");\n        if (dele==\"".$RAD_textConfirmDelete."\") {\n";
	  }

	  if ($func!="print" && $subfunc!="list" && (($browsedit && $numrow>1) || $browsetype=="insert" || $V_rowsinsert>0)) {
		echo "
<script type='text/javascript'>
function deleregs() {
        existe=false;
        for (var j=0; j<document.F.elements.length; j++) {
                varname=document.F.elements[j].name;
                varvalue=document.F.elements[j].checked;
                if (varname.indexOf('_delete')>0 && varvalue==true) existe=true;
        }
        if (existe==false) return;
".$TMP_confirmdeletes."
            document.forms.F.func.value='delete';
            document.forms.F.submit();
        }
}
function deleregx(numform) {
        var name='x';
".$TMP_confirmdelete."
                var numobj=-1;
                for (var i=0; i<document.forms.length; i++) {
//                      alert(i+' '+document.forms[i]);
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
        var name='x';
".$TMP_confirmsavedeletes."
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
function saveregs() {
        csave=confirm(\"\u00BFEst\u00E1 seguro de guardar los registros cambiados?\");
        if (csave) document.forms.F.submit();
}
</script>
";
		echo "\n<TABLE WIDTH=100%><TR><TD ALIGN=RIGHT><INPUT TYPE=BUTTON ";
		if (($browsedit || $browsetype=="insert" || $V_rowsinsert>0) && $browsetype=="deleteline") echo "onClick='javascript:savedeleregs();' ";
		else if ($browsetype=="deleteline") echo "onClick='javascript:deleregs();' ";
		else echo "onClick='javascript:saveregs();' ";
		echo "class=button NAME=Save ACCESSKEY='S' TITLE='ALT+S' VALUE="._DEF_NLSSave."></TD><TD ALIGN=LEFT>&nbsp;<INPUT TYPE=RESET class=button  ACCESSKEY='R' TITLE='ALT+R' VALUE="._DEF_NLSReset."></TD></TR></TABLE><INPUT TYPE=HIDDEN NAME=V_numrows VALUE='$numrow'></FORM>\n";
	  }
	  echo "</TABLE></CENTER>\n";
	}
}
//---------------------------------------------------------------------------
function RAD_amplia_browsetree($TMP_level, $TMP_par) {
	global $URLROI, $RAD_classrow, $numrow, $RAD_dbi, $numf, $tablename, $fields, $findex, $defaultfilter, $browsetreefieldparent, $browsetreefield, $browsetype, $filename, $V_dir, $V_mod, $defaultfunc;
	global $A_showedID, $par0, $par0padres, $par0hijos, $TMP_onlyparents, $db, $subbrowseSID, $browsedit, $FORMROI, $orderby, $PHP_SELF, $dbname;

	if ($subbrowseSID!="") $TMP_URLROI="&headeroff=x&footeroff=x&dbname=".$dbname;
	else $TMP_URLROI=$URLROI;


	if ($TMP_par=="") {
		if ($defaultfilter!="") $TMP_defaultfilter=" WHERE ".$defaultfilter." AND ($browsetreefieldparent IS NULL OR $browsetreefieldparent='' OR $browsetreefieldparent='0')";
		else $TMP_defaultfilter=" WHERE ($browsetreefieldparent IS NULL OR $browsetreefieldparent='' OR $browsetreefieldparent='0')";
		$cmdSQL="SELECT count(*) FROM $tablename ".$TMP_defaultfilter."";
		$V_TMP_result=sql_query($cmdSQL, $RAD_dbi);
		$V_TMP_row=sql_fetch_array($V_TMP_result, $RAD_dbi);
		if ($V_TMP_row[0]==0) {
			if ($defaultfilter!="") $TMP_defaultfilter=" WHERE ".$defaultfilter;
			else $TMP_defaultfilter="";
			if ($orderby=="") $orderby=$browsetreefieldparent;
			else $orderby=$browsetreefieldparent.", ".$orderby;
		}
	} else if ($TMP_par=="-*") {
		if ($defaultfilter!="") $TMP_defaultfilter=" WHERE ".$defaultfilter." ";
		else $TMP_defaultfilter="";
	} else {
		if ($defaultfilter!="") $TMP_defaultfilter=" WHERE ".$defaultfilter." AND $browsetreefieldparent='$TMP_par'";
		else $TMP_defaultfilter=" WHERE $browsetreefieldparent='$TMP_par'";
	}
	$cmdSQL="SELECT * FROM $tablename ".$TMP_defaultfilter."";
	if ($orderby!="") $cmdSQL.=" ORDER BY ".$orderby;
	$V_TMP_result=sql_query($cmdSQL, $RAD_dbi);
	while ($V_TMP_row=sql_fetch_array($V_TMP_result, $RAD_dbi)) {
		foreach($V_TMP_row as $TMP_key=>$TMP_val) $db->Record[$TMP_key]=$TMP_val;
		if ($A_showedID[$db->Record[$browsetreefield]]!="") continue;

		if ($par0!="") {
			if (	!ereg(",".$db->Record[$browsetreefield].",",$par0padres) &&
				!ereg(",".$db->Record[$browsetreefieldparent].",",$par0padres) &&
				!ereg(",".$db->Record[$browsetreefield].",",$par0hijos) &&
				$db->Record[$browsetreefield]!=$par0 &&
				$db->Record[$browsetreefieldparent]!="" &&
				$db->Record[$browsetreefieldparent]!="0") continue;
		}

		if ($TMP_onlyparents==true && $db->Record[$browsetreefieldparent]!="" && $db->Record[$browsetreefieldparent]!="0") continue;

		$A_showedID[$db->Record[$browsetreefield]]="x";
		$hiddenbrowsedit="";
		if ($RAD_classrow == "class=row1") $RAD_classrow = "class=row2";
		else $RAD_classrow = "class=row1";
		$urlidnames = "";
		$idnames = "";
		$TMP_line[$numrow]="<tr>\n";

		$TMP_iconlevel="";
		//$TMP_urllevel="";
		//$TMP_stringlevel="";
		$TMP_stringlevel=str_repeat("&nbsp;",$TMP_level*10);
		if ($subbrowseSID=="") {
			if ($par0==$db->Record[$browsetreefield] && $db->Record[$browsetreefield]>0) $TMP_urllevel.="<a name='parent".$db->Record[$browsetreefield]."' href='".$PHP_SELF."?func=browsetree&par0=".$db->Record[$browsetreefieldparent].$TMP_URLROI."#parent".$db->Record[$browsetreefield]."'>";
//			else if ($par0==$db->Record[$browsetreefieldparent]) $TMP_stringlevel.="<a href='".$PHP_SELF."?func=browsetree".$TMP_URLROI."'>";
			else $TMP_urllevel.="<a name='parent".$db->Record[$browsetreefield]."' href='".$PHP_SELF."?func=browsetree&par0=".$db->Record[$browsetreefield].$TMP_URLROI."#parent".$db->Record[$browsetreefield]."'>";
		}
		$cmdSQL2="SELECT count(*) FROM $tablename WHERE $browsetreefieldparent='".$db->Record[$browsetreefield]."'";
		$TMP_result2=sql_query($cmdSQL2, $RAD_dbi);
		$TMP_row2=sql_fetch_array($TMP_result2, $RAD_dbi);
		if ($TMP_row2[0]>0) {
			if ($subbrowseSID!="") $TMP_iconlevel.="<img src='images/menos.gif' border=0>";
			else if ($par0padres!="" && ereg(",".$db->Record[$browsetreefield].",",$par0padres)) $TMP_iconlevel.="<img src='images/menos.gif' border=0>";
			else if ($db->Record[$browsetreefield]==$par0) $TMP_iconlevel.="<img src='images/menos.gif' border=0>";
			else if ($TMP_onlyparents==false && $par0=="") $TMP_iconlevel.="<img src='images/menos.gif' border=0>";
			else $TMP_iconlevel.="<img src='images/mas.gif' border=0>";
		} else $TMP_iconlevel.="<img src='images/tr.gif' width=16 height=1 border=0>";
		if (trim($TMP_iconlevel)=='') $TMP_urllevel=str_replace("name='parent","name='parenthide",$TMP_urllevel);
		if ($subbrowseSID=="") $TMP_stringlevel.=$TMP_urllevel.$TMP_iconlevel."</a> ";
		if (file_exists("modules/$V_dir/common.defaults.php")) {
			$TMP=include ("modules/$V_dir/common.defaults.php");
			if ($TMP!==true && $TMP!="1") $TMP_line[$numrow].=$TMP;
		}
		if ($filename!="") {
			if (file_exists("modules/$V_dir/".$filename.".defaults.php"))
				$TMP=include ("modules/$V_dir/".$filename.".defaults.php");
				if ($TMP!==true && $TMP!="1") $TMP_line[$numrow].=$TMP;
		} else {
			if (file_exists("modules/$V_dir/".$V_mod.".defaults.php"))
				$TMP=include ("modules/$V_dir/".$V_mod.".defaults.php");
				if ($TMP!==true && $TMP!="1") $TMP_line[$numrow].=$TMP;
		}
                if ($numf==0) continue;
		for ($i = 0; $i < $numf; $i++) {
			global ${"idname$i"};
			if (${"idname$i"} != "") {
				$urlidnames .= "&par$i=".urlencode($db->Record[${"idname$i"}]);
				$idnames .= "&id$i=".$db->Record[${"idname$i"}];
				$hiddenbrowsedit .= "<input type=hidden name='V".$numrow."_par$i' value='".$db->Record[${"idname$i"}]."'>\n";
			}
		}
		if ($defaultfunc=="edit") $TMP_nextfunc="edit";
		else $TMP_nextfunc="detail";
		if ($subbrowseSID!="") {
			if ($RAD_width<1 || $RAD_width=="") $RAD_width=800;
			if ($RAD_height<1 || $RAD_height=="") $RAD_height=600;
			$htmllink="<A TITLE='Ver Registro ".($numrow)."' HREF='javascript:RAD_OpenW(\"".$PHP_SELF."?V_dir=$V_dir&V_mod=$V_mod&func=".$TMP_nextfunc.$urlidnames.$TMP_URLROI."&subfunc=browse\",$RAD_width,$RAD_height);'>";
		} else {
			$htmllink="<A TITLE='Ver Registro ".($numrow)."' HREF=\"".$PHP_SELF."?V_dir=$V_dir&V_mod=$V_mod&func=".$TMP_nextfunc.$urlidnames.$TMP_URLROI."\">";
		}
		$htmllinkend="</A>";
		if ($browsetype=="line") {
			$TMP_numreg=$numrow+$start;
			$TMP_line[$numrow].="<TD $RAD_classrow nowrap> ".$htmllink.$TMP_numreg.$htmllinkend." </TD>\n";
			$htmllink="";
			$htmllinkend="";
		}
		if ($browsetype=="editline") {
			$TMP_numreg=$numrow+$start;
			$hiddenidnames="";
			for ($xi = 0; $xi < $numf; $xi++) {
				if (${"idname$xi"} != "") {
					$val=$db->Record[${"idname$xi"}];
					$hiddenidnames .= "<input type=hidden name=par$xi value='".$val."'>\n";
				}
			}
			$TMP_line[$numrow].="<TD $RAD_classrow nowrap> <A HREF=\"".$PHP_SELF."?V_dir=$V_dir&V_mod=$V_mod&func=detail".$urlidnames.$TMP_URLROI."\"><img src='images/detail.gif' border=0 alt='Consulta'></A> ";
			$TMP_line[$numrow].=" <A TITLE='Ver Registro ".($numrow)."' HREF=\"".$PHP_SELF."?V_dir=$V_dir&V_mod=$V_mod&func=edit".$urlidnames.$TMP_URLROI."\"><img src='images/edit.gif' border=0 alt='Edita'></A> ";
			$TMP_line[$numrow].=" <A HREF=\"".$PHP_SELF."?V_dir=$V_dir&V_mod=$V_mod&func=new".$urlidnames.$TMP_URLROI."\"><img src='images/new.gif' border=0 alt='Duplica'></A> ";
			$TMP_line[$numrow].=" <a href=\"javascript:deleregx($TMP_numreg);\"><img src='images/delete.gif' border=0 alt='Borra'></a><FORM autocomplete=off NAME='FORM$TMP_numreg' ACTION=$PHP_SELF METHOD=POST><INPUT TYPE=HIDDEN NAME=numform VALUE='$TMP_numreg'><INPUT TYPE=HIDDEN NAME=func VALUE='delete'>\n".
				$FORMROI.$hiddenidnames;
			$TMP_line[$numrow].="</TD></FORM>\n";
		}
		$TMP_result = count($fields);
                for ($i = 0; $i < $numf; $i++) {
		  if ($fields[$i]->name==$browsetreefieldparent) continue;
		  if ($fields[$i]->dtype == "function" && $fields[$i]->type == "function") {
			$RAD_numfield=$i;
			if (!file_exists("modules/$V_dir/".$fields[$i]->extra) && file_exists("modules/phpRAD/".$fields[$i]->extra)) {
				$TMP_funcDir="modules/phpRAD/"; // directorio por defecto de funciones comunes
			} else {
				$TMP_funcDir="modules/$V_dir/";
			}
			if ($fields[$i] -> browsable) $TMP_line[$numrow].=include($TMP_funcDir.$fields[$i]->extra);
			$i=$RAD_numfield;
			continue;
                  }
		  if ($fields[$i]->type == "function" && $fields[$i]->dtype == "geturl") {
			if ($fields[$i] -> browsable) {
				$TMP_file = fopen($fields[$i]->extra, "r");
				if ($TMP_file) {
					$TMP_content = "";
					while (!feof($TMP_file)) {
        					$TMP_line = fgets($TMP_file, 512000);
					        $TMP_content = $TMP_content.$TMP_line;
					}
				}
				fclose($TMP_file);
				$TMP_line[$numrow].=$TMP_content;
			}
			continue;
                  }
		  if (($fields[$i] -> fieldedit || $func == "browsedit")&&($subfunc!="list")&&($subfunc!="report")) {
			$TMP_height=100;
			if (_DEF_POPUP_MARGIN=="SUBMODAL") $TMP_height+=80;
			$htmlEdit="<a href=\"javascript:RAD_OpenW('".$PHP_SELF."?V_dir=$V_dir&V_mod=$V_mod&func=edit&subfunc=browse&menuoff=x&footeroff=x&headeroff=x&lapoff=x&fieldedit=".$fields[$i]->name.$urlidnames.$TMP_URLROI."',600,".$TMP_height.");\">"._DEF_NLSBrowseEdit."</a>";
			$htmlEditEnd=""; 
		  } else { 
			$htmlEdit="";
			$htmlEditEnd="";
		  }
		  if ($fields[$i] -> browsable) {
			if($i==1 && $TMP_result>1 && $TMP_row2[0]>0){
				$TMP_line[$numrow].="<td $RAD_classrow nowrap>".$htmllink."<img src=images/edit.gif> ".$htmllinkend.$TMP_stringlevel.$htmlEdit;
				$TMP_stringlevel="";
				if (!$fields[$i] -> browsedit) $TMP_line[$numrow].=$TMP_urllevel;
			}else{
				$TMP_line[$numrow].="<td $RAD_classrow nowrap>".$htmllinkend.$TMP_stringlevel.$htmlEdit;
				$TMP_stringlevel="";
				if (!$fields[$i] -> browsedit) $TMP_line[$numrow].=$htmllink;
			}
			if ($fields[$i]->dtype == "function") {
				$RAD_numfield=$i;
				if (!file_exists("modules/$V_dir/".$fields[$i]->extra) && file_exists("modules/phpRAD/".$fields[$i]->extra)) {
					$TMP_funcDir="modules/phpRAD/"; // directorio por defecto de funciones comunes
				} else {
					$TMP_funcDir="modules/$V_dir/";
				}
				$TMP_line[$numrow].=include($TMP_funcDir.$fields[$i]->extra);
				$i=$RAD_numfield;
                  	} elseif ($fields[$i]->dtype == "geturl") {
				$TMP_file = fopen($fields[$i]->extra, "r");
				if ($TMP_file) {
					$TMP_content = "";
					while (!feof($TMP_file)) {
        					$TMP_line = fgets($TMP_file, 512000);
					        $TMP_content = $TMP_content.$TMP_line;
					}
				}
				fclose($TMP_file);
				$TMP_line[$numrow].=$TMP_content;
    			} elseif ($fields[$i] -> browsedit) {
    				$fvalue = $db->Record[$fields[$i]->name];
				$TMP_extra=$fields[$i]->extra;
				if ($fields[$i]->parmlistSFF!="") $TMP_extra.="|".$fields[$i]->parmlistSFF;
				if ($fields[$i]->vonblur!="") $fields[$i]->vonchange="onchange=".$fields[$i]->vonchange." onblur=".$fields[$i]->vonblur;
				$TMP_line[$numrow].=RAD_editfield($fields[$i]->name, $fields[$i]->dtype, $fields[$i]->length, $fields[$i]->ilength, $TMP_extra, $fields[$i]->vonchange, $fields[$i]->canbenull, $fvalue,"V".$numrow."_".$fields[$i]->name);
    			} elseif (($db->Record[$fields[$i]->name]) && ($fields[$i]->dtype=="image" ||
				$fields[$i] -> dtype == "date" ||
				$fields[$i] -> dtype == "datetext" ||
				$fields[$i] -> dtype == "datetime" ||
				$fields[$i] -> dtype == "datetimetext" ||
				$fields[$i] -> dtype == "dateint" ||
				$fields[$i] -> dtype == "dateinttext" ||
				$fields[$i] -> dtype == "datetimeint" ||
				$fields[$i] -> dtype == "datetimeinttext" ||
				$fields[$i] -> dtype == "time" ||
				$fields[$i] -> dtype == "timetext" ||
				$fields[$i] -> dtype == "timeint" ||
				$fields[$i] -> dtype == "timeinttext" ||
				$fields[$i] -> dtype == "email" ||
				$fields[$i] -> dtype == "http" ||
				$fields[$i] -> dtype=="bpopupdb" ||
				$fields[$i] -> dtype=="fbpopupdb" ||
				substr($fields[$i]->dtype,0,8)=="checkbox" ||
				$fields[$i] -> dtype=="fpopupdb" || $fields[$i]->dtype=="popupdb" || $fields[$i]->dtype=="plistdb" || $fields[$i]->dtype=="plistdbtree" || $fields[$i]->dtype=="rlistdb" ||
				$fields[$i]->dtype=="plistdbm" || $fields[$i]->dtype=="plistdbmtree" || $fields[$i]->dtype=="popupdbm" ||
        			$fields[$i]->dtype=="rlist" || $fields[$i]->dtype=="plist" || $fields[$i]->dtype=="plistm" ||
				$fields[$i] -> dtype == "text")) {
				$TMP_line[$numrow].=RAD_showfield($fields[$i]->dtype, $fields[$i]->extra, $db->Record[$fields[$i] -> name]);
        		} else {
				$TMP_line[$numrow].=RAD_showfield($fields[$i]->dtype, $fields[$i]->extra, $db->Record[$fields[$i] -> name]);
				//$val=$db->Record[$fields[$i] -> name];
				//$val=str_replace("<", "&lt;", $val);
				//$val=str_replace("\n", "<br>\n", $val);
				//$TMP_line[$numrow].=$val."&nbsp;";
            		}
			$TMP_line[$numrow].=$htmllinkend.$htmlEditEnd." </td>\n";
                  }
                }
                if ($browsedit) $TMP_line[$numrow].=$hiddenbrowsedit;
		$TMP_line[$numrow].="</tr>\n";
		echo $TMP_line[$numrow];
    		$numrow++;
		if ($TMP_onlyparents==false) RAD_amplia_browsetree(($TMP_level+1),$db->Record[$browsetreefield]);
	}
}
?>
