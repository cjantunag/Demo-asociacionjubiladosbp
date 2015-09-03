<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}
if ($func == "edit" && $menuedit == false) {
    $func="error";
}
if ($func == "new" && $menunew == false) {
    $func="error";
}

//---------------------------------------------------------------------------
//------------------------- New/Edit
//---------------------------------------------------------------------------
$TMP_div="";
if (($func == "edit")||($func == "new")) {
	if ($V_colsedit=="") $V_colsedit=1;
	$idnames = "";
	$TMP_overlap=false;
	if($V_lap!="") {
		$TMP_lapencontrado=false;
		for ($i = 0; $i < $numf; $i++) {
			if(($V_lap==$fields[$i]->overlap||$V_lap=="x") && $fields[$i]->noedit!=true &&
				($fields[$i]->dtype!="function" || substr($fields[$i]->extra,0,13)!="RAD_subbrowse"))
				$TMP_lapencontrado=true;
		}
		if (!$TMP_lapencontrado) $V_lap="";
	}
	RAD_checkPars();
	$TMP_contVlap=0;
	for ($i = 0; $i < $numf; $i++) {
		if (!isset(${"idname$i"})) ${"idname$i"} = "";
		if (${"idname$i"} != "") {
			if ($idnames == "") $idnames = "${"idname$i"} = '" . urldecode(${"par$i"}) . "'";
			else $idnames .= " AND ${"idname$i"} = '" . urldecode(${"par$i"}) . "'";
		}
		if (($func == "new" && $fields[$i]->nonew==true) || ($func == "edit" && $fields[$i]->noedit==true)) continue;
		if($fields[$i]->overlap!=""  && substr($fields[$i]->extra,0,13)!="RAD_subbrowse") {
			if ($func=="edit") $TMP_overlap=true;
			if ($TMP_overlaptext[$fields[$i]->overlap]=="") {
				$TMP_contVlap++;
				$TMP_overlaptext[$fields[$i]->overlap]=$TMP_contVlap;
				if($V_lap=="" && !ereg(",".$TMP_contVlap."," , ",".$RAD_lapoff.",") && !ereg(",".$fields[$i]->overlap."," , ",".$RAD_lapoff.",")) $V_lap=$fields[$i]->overlap;
			}
		}
	}
	$tmpdefaultfilter="";
	if ($defaultfilter!="") {
		if ($idnames =="") $tmpdefaultfilter=" WHERE ".$defaultfilter;
		else $tmpdefaultfilter=" AND ".$defaultfilter;
	}
	if ($func!="new" || $par0!="") {
	  $cmdSQL="SELECT * FROM $tablename WHERE $idnames".$tmpdefaultfilter;
	  $TMP_initime=RAD_microtime();
	  if (_SQL_DEBUG!="0") echo $TMP_initime." SQL query: ".$cmdSQL;
	  if ($db -> query($cmdSQL)) {
		$TMP_diftime=RAD_microtime()-$TMP_initime;
		if (_SQL_DEBUG!="0") echo " [".substr($TMP_diftime,0,8)."]";
		$db -> next_record();
	  } else {
		$func = "error";
		$RAD_errorstr .= $cmdSQL.$db -> Error;
	  }
	}
}

if (($func == "new") || ($func == "edit")) {
	echo "<script LANGUAGE=\"JAVASCRIPT\">
var varField=new Array();
var nameField=new Array();
var lastField=0;
function RAD_checkFields(elem) {
var tmp='';
";
if ($RAD_precheckFields!="") echo $RAD_precheckFields;
echo "  var df=document.F;
  for(i=0; i<varField.length; i++) {
	p='';
	for(j=0; j<document.F.length; j++) {
	   if (document.F[j].type=='select-multiple' && document.F[j].name==varField[i]+'[]') {
		var numsel=0;
		p='x';
		for (k=0; k<document.F[j].length; k++) {
			if (document.F[j].options[k].selected == true) numsel++;
		}
		if (numsel==0) {
			try {
				eval('document.F[i].focus()');
			} catch (ieerror) {
				try {
					if(document.F[i] && document.F[i].style.display == '' && document.F[i].style.visibility == 'visible')
						document.F[i].focus();
				} catch (iereallyerror) {
					var MSIE = 'errors';
				}
			}
			alert(nameField[i]+' "._DEF_NLSMandatoryField."'+tmp);
			return;
		}
	   }
	}
	if (p=='') eval('p=df.'+varField[i]+'.value');
	if (p=='') {
		eval('tipo=df.'+varField[i]+'.type');
                if (tipo!='hidden') {
                    if (tipo=='radio') eval('df.'+varField[i]+'[0].focus()');
                    else eval('df.'+varField[i]+'.focus()');
		    alert(nameField[i]+' "._DEF_NLSMandatoryField."'+tmp);
                }else{
                    eval('df.'+varField[i]+'_literal.focus()');
		    alert(nameField[i]+' "._DEF_NLSMandatoryField."'+tmp);
                }
		return;
	}
  }
";
if (_DEF_ConfirmSave=="1") echo "  csave=confirm(\""._DEF_NLSSaveMessage."\");
  if (!csave) return;
";
echo "  df.submit();
  elem.disabled=true;
  return;
}
</script>
";
	echo "<FORM style='margin:0;' autocomplete=off NAME=FORM1 ACTION=$PHP_SELF METHOD=POST><INPUT TYPE=HIDDEN NAME=func VALUE='delete'>\n".
		"<INPUT TYPE=HIDDEN NAME=subfunc VALUE='$subfunc'>\n".
		$FORMROI;
	for ($i = 0; $i < $numf; $i++) {
		if (${"idname$i"} != "") echo "<INPUT TYPE=\"HIDDEN\" NAME=\"par$i\" VALUE=\"".htmlspecialchars(${"par$i"})."\">\n";
	}
	echo "</FORM>";
	if ($TMP_overlap && count($TMP_overlaptext)>0) {
		echo "<table cellpadding=0 cellspacing=0 class=submenu><tr>\n";
		$TMP_contVlap=0;
		$TMP_PARAMETROS_URL=RAD_delParamURL($GLOBALS["QUERY_STRING"],"V_lap");
		    foreach ($fields as $campo) {
			if (ereg("RAD_subbrowse",$campo->extra)) $count++;
			$TMP_PARAMETROS_URL=RAD_delParamURL($TMP_PARAMETROS_URL,"V_sub_".$count."_roi");
		    }
		$TMP_PARAMETROS_URL=RAD_delParamURL($TMP_PARAMETROS_URL,"V_prevfunc");
		if ($V_prevfunc!="") $TMP_PARAMETROS_URL.="&V_prevfunc=".urlencode($V_prevfunc);
		$TMP_PARAMETROS_URL=str_replace("?&=","?",$TMP_PARAMETROS_URL);
		$TMP_PARAMETROS_URL=str_replace("&=&","&",$TMP_PARAMETROS_URL);
		foreach ($TMP_overlaptext as $text=>$num) {
			if ($text=="") $text=" ";
			$TMP_contVlap++;
			$TMP_url=$PHP_SELF."?".$TMP_PARAMETROS_URL."&V_lap=".urlencode($text);
			if (strtolower($lapoff)!="x" && !ereg(",".$TMP_contVlap.",",",".$lapoff.",") &&
			   !ereg(",".$TMP_contVlap."," , ",".$RAD_lapoff.",") && !ereg(",".$V_lap."," , ",".$RAD_lapoff.",")) {
			    $textNLS=$text;
			    if ($RAD_NLS[$text]!="") $textNLS=$RAD_NLS[$text];
			    if (getSessionVar("SESSION_NLS".$text)!="") $textNLS=getSessionVar("SESSION_NLS".$text);
			    if ($text==$V_lap) echo RAD_submenu_on($textNLS);
			    else if ($V_lap=="x") echo RAD_submenu_on("<a class=submenuon ACCESSKEY='".$TMP_contVlap."' TITLE='ALT+".$TMP_contVlap."' href=\"".$TMP_url."\">".$textNLS."</a>\n");
			    else echo RAD_submenu_off("<a class=submenuoff ACCESSKEY='".$TMP_contVlap."' TITLE='ALT+".$TMP_contVlap."' href=\"".$TMP_url."\">".$textNLS."</a>\n");
			}
		}
		$TMP_url=$PHP_SELF."?".$TMP_PARAMETROS_URL."&V_lap=x";
		if (($RAD_expandlap==""||$RAD_expandlap=="1") && $TMP_contVlap>1 && $lapoff=="") {
		    //if ($V_lap=="x") echo RAD_submenu_on("<img src='images/expand.gif'>");
		    //else echo RAD_submenu_off("<a class=submenuoff ACCESSKEY='0' TITLE='ALT+0' href=\"".$TMP_url."\"><img src='images/expand.gif'></a>\n");
		    if ($V_lap=="x") echo RAD_submenu_on("<font style='font-weight:bold; font-size:1.5em;'>&#8660;</font>");
		    else echo RAD_submenu_off("<a class=submenuoff ACCESSKEY='0' TITLE='ALT+0' href=\"".$TMP_url."\"><font style='font-weight:bold; font-size:1.5em;'>&#8660;</font></a>\n");
		}
		echo "</tr></table>\n";
	}
	echo "<FORM autocomplete=off ACTION=$PHP_SELF";
	echo " METHOD=POST NAME=F ENCTYPE='multipart/form-data'>\n";
	echo $FORMROI;
	echo "<INPUT TYPE=HIDDEN NAME=subfunc VALUE='$subfunc'>\n";
	echo "<INPUT TYPE=HIDDEN NAME=func VALUE='";
	if ($func == "edit") echo "update";
	else echo "insert";
	echo "'>\n";
	$idnames = "";
	for ($i = 0; $i < $numf; $i++) {
		if (${"idname$i"} != "" && ($reset==""||$func=="edit") ) echo "<INPUT TYPE=\"HIDDEN\" NAME=\"V0_par$i\" VALUE=\"".htmlspecialchars(${"par$i"})."\">\n";
	}
	echo "<TABLE class=detail>\n";
	$inifield=0; $lastfield=$numf-1;
	if ($fieldedit !="") {
		$inifield=$lastfield=$findex[$fieldedit];
		echo "<INPUT TYPE=HIDDEN NAME=fieldedit VALUE='".$fieldedit."'>\n";
	}

	if (function_exists("shmop_open") && $func=="edit") {
		$TMP_user = base64_decode($HTTP_SESSION_VARS["SESSION_user"]);
		$TMP_IP=getenv("REMOTE_ADDR");
		$TMP_CLIENT_IP=getenv("HTTP_CLIENT_IP");
		if ($TMP_CLIENT_IP!="") $TMP_IP=$TMP_CLIENT_IP."/".$TMP_IP;

		$TMP_shm_datawrite=$PHPSESSID.".".$TMP_user.".".time().".".$TMP_IP;

		$TMP_shm_key=$dbname.".".$tablename.".".$par0;
		if ($par1!="") $TMP_shm_key.=".".$par1;
		if ($par2!="") $TMP_shm_key.=".".$par2;
		$TMP_shm_key=str_replace("/",".",$TMP_shm_key);
		$TMP_shm_key="/tmp/".$TMP_shm_key;
		if (!file_exists($TMP_shm_key)) {
			if($fp=@fopen($TMP_shm_key,"w")) fclose($fp);
			clearstatcache();
		}
		$TMP_shm_key=ftok($TMP_shm_key, 't');
		$TMP_shm_id=@shmop_open($TMP_shm_key, "w", 0644, 100);
		if (!$TMP_shm_id) $TMP_shm_id=@shmop_open($TMP_shm_key, "c", 0644, 100);
		if ($TMP_shm_id) {
			$TMP_shm_dataread = shmop_read($TMP_shm_id, 0, 100);
			list($TMP_PHPSESSID2,$TMP_user2,$TMP_time2, $TMP_IP2)=explode(".",$TMP_shm_dataread);
			if ($TMP_time2>0) if ($TMP_PHPSESSID2!=$PHPSESSID || $TMP_user2!=$TMP_user) if ((time()-$TMP_time2)<300) alert("El usuario $TMP_user2 desde la IP $TMP_IP2 está editando este mismo registro.");
			$TMP_shm_bytes_written = shmop_write($TMP_shm_id, $TMP_shm_datawrite, 0);
		}
	}
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
		    $TMP="";
		}
	} else {
		if (file_exists("modules/$V_dir/".$V_mod.".defaults.php")) {
		    $TMP="";
		    $TMP=include ("modules/$V_dir/".$V_mod.".defaults.php");
		    if ($TMP!==true && $TMP!="1") echo $TMP;
		    $TMP="";
		}
	}
	$TMP_setfoco_primer_campo=false;
	$TMP_numcol=0;
	if ($RAD_html5=="1") $TMP_date5="5"; // sufijo campo RAD_html5
	else $TMP_date5="";
	for ($i = $inifield; $i < $lastfield+1; $i++) {
		if ($fields[$i]->title=="") $TMP_fieldtitle=$fields[$i-1]->title;
		else $TMP_fieldtitle=$fields[$i]->title;
		$TMP_fieldtitle=RAD_UTF_to_Unicode(html_entity_decode($TMP_fieldtitle));
		if ($fields[$i+1]->title==$fields[$i]->title) $fields[$i+1]->title=""; // si el siguiente titulo es igual se pone en blanco para que salgan juntos
		if ($fields[$i]->name == "" && $fields[$i]->title == "") continue;
		if ($fields[$i]->V_colsedit=="") $fields[$i]->V_colsedit=1;
		if ($fields[$i]->dtype == "function" && $fields[$i]->type == "function") {
			if (($func == "new"&&$fields[$i]->nonew==true)||($func == "edit"&&$fields[$i]->noedit==true)) continue;
		    	$TMP="";
			$RAD_numfield=$i;
			if (!file_exists("modules/$V_dir/".$fields[$i]->extra) && file_exists("modules/phpRAD/".$fields[$i]->extra)) {
				$TMP_funcDir="modules/phpRAD/"; // directorio por defecto de funciones comunes
			} else {
				$TMP_funcDir="modules/$V_dir/";
			}
			if(!$TMP_overlap || $fields[$i]->overlap==$V_lap || $V_lap=="x") {
				if (substr($fields[$i]->extra,0,13)!="RAD_subbrowse") $TMP=include($TMP_funcDir.$fields[$i]->extra);
			} else {
				$TMP=include($TMP_funcDir.$fields[$i]->extra);
			}
			$i=$RAD_numfield;
			if(!$TMP_overlap || $fields[$i]->overlap==$V_lap || $V_lap=="x") {
				if ($TMP!==true && $TMP!="1" && $TMP!="") {
					if ($TMP_numcol>0) echo "</TR>";
					echo $TMP;
					$TMP_numcol=0;
				}
			}
		    	$TMP="";
			continue;
		}
		if ($fields[$i]->type == "function" && $fields[$i]->dtype == "geturl") {
			if (($func == "new"&&$fields[$i]->nonew==true)||($func == "edit"&&$fields[$i]->noedit==true)) continue;
			if(!$TMP_overlap || $fields[$i]->overlap==$V_lap || $V_lap=="x") {
				if (substr($fields[$i]->extra,0,13)!="RAD_subbrowse") {
					$TMP_file = fopen($fields[$i]->extra, "r");
					if ($TMP_file) {
						$TMP_content = "";
						while (!feof($TMP_file)) {
        						$TMP_line = fgets($TMP_file, 512000);
				        		$TMP_content = $TMP_content.$TMP_line;
						}
					}
					fclose($TMP_file);
					$TMP=$TMP_content;
				}
			} else {
				$TMP_file = fopen($fields[$i]->extra, "r");
				if ($TMP_file) {
					$TMP_content = "";
					while (!feof($TMP_file)) {
        					$TMP_line = fgets($TMP_file, 512000);
					        $TMP_content = $TMP_content.$TMP_line;
					}
				}
				fclose($TMP_file);
				$TMP=$TMP_content;
			}
			if(!$TMP_overlap || $fields[$i]->overlap==$V_lap || $V_lap=="x") echo $TMP;
		    	$TMP="";
			continue;
		}
		if ($fields[$i]->readonly==true && $func=="edit") $fields[$i]->vdefault=$db->Record[$fields[$i]->name];

		if (($func == "new" && $fields[$i]->nonew==true) || ($func == "edit" && $fields[$i]->noedit==true)) {
			if ($reset=="" || $fields[$i]->readonly==true || $fields[$i]->dtype=="file" || $fields[$i]->dtype=="image") {
			    $fvalue=$db->Record[$fields[$i]->name];
			    if (!isset($db->Record[$fields[$i]->name])) $fvalue=evalVar($fields[$i]->vdefault);
			} else $fvalue="";
			if (eregi("num", $fields[$i]->dtype)) $TMP_fvalue=RAD_numero($fvalue,$fields[$i]->extra); 
			else $TMP_fvalue=$fvalue;
			if ($fields[$i]->readonly==false) echo "<INPUT TYPE=HIDDEN NAME=V0_".$fields[$i]->name." VALUE=\"".htmlspecialchars($TMP_fvalue)."\">\n";
			if ($func != "edit" || $fields[$i] -> readonly == false) continue;
		}

		if($TMP_overlap && ($fields[$i]->overlap!=$V_lap && $V_lap!="x")) {
			if (eregi("num", $fields[$i]->dtype)) $TMP_fvalue=RAD_numero($db->Record[$fields[$i]->name],$fields[$i]->extra);
			else $TMP_fvalue=$db->Record[$fields[$i]->name];
			if ($func == "edit") echo "<INPUT TYPE=HIDDEN NAME=V0_".$fields[$i]->name." VALUE=\"".htmlspecialchars($TMP_fvalue)."\">\n";
			continue;
		}
		if ($fields[$i]->type == "uniqid") {
			$fields[$i]->dtype = "none";
			$fields[$i]->readonly = true;
			if ($func == "new") echo "<INPUT TYPE=HIDDEN NAME=V0_".$fields[$i]->name." VALUE=\"".htmlspecialchars(uniqid(rand()))."\">\n";
			if ($func == "edit") echo "<INPUT TYPE=HIDDEN NAME=V0_".$fields[$i]->name." VALUE=\"".htmlspecialchars($db->Record[$fields[$i]->name])."\">\n";
			continue;
		}
		if ($fields[$i]->dtype == "hidden") {
			if ($func == "new") echo "<INPUT TYPE=HIDDEN NAME=V0_".$fields[$i]->name." VALUE=\"".htmlspecialchars($fields[$i]->vdefault)."\">\n";
			if ($func == "edit") echo "<INPUT TYPE=HIDDEN NAME=V0_".$fields[$i]->name." VALUE=\"".htmlspecialchars($db->Record[$fields[$i]->name])."\">\n";
			continue;
		}
		if ($func == "edit" && $fields[$i]->dtype == "auto_increment") {
			echo "<INPUT TYPE=HIDDEN NAME=V0_".$fields[$i]->name." VALUE=\"".htmlspecialchars($db->Record[$fields[$i]->name])."\">\n";
		}
//		if ($fields[$i] -> dtype != "auto_increment" && $fields[$i] -> readonly == false ) {	// don't edit auto_increment fields or readonly
		if ($fields[$i] -> dtype != "auto_increment" && $fields[$i]->type != "uniqid") {	// don't edit auto_increment fields or readonly
			if ($fields[$i]->coledit!="") {
				$TMP_div.= "<div id='$numf' style='position:absolute; z-index:6; visibility: visible; top:".$fields[$i]->rowedit."; left:".$fields[$i]->coledit.";'>\n";
				if (!$fields[$i] -> canbenull) {
					if ($fields[$i]->dtype!="function" && substr($fields[$i]->dtype,0,4)!="auto" && substr($fields[$i]->dtype,0,4)!="date" && substr($fields[$i]->dtype,0,4)!="time") {
						if (eregi("popup", $fields[$i]->dtype) && $fields[$i]->dtype!="popupdbSFF")
						    $TMP_checkFIELD.= "<script>\nvarField[lastField]='V0_".$fields[$i]->name."';\nnameField[lastField]='".$TMP_fieldtitle."';\nlastField++;\nvarField[lastField]='V0_".$fields[$i]->name."_literal';\nnameField[lastField]='".$TMP_fieldtitle."';\nlastField++;\n</script>\n ";
						else $TMP_checkFIELD.= "<script>\nvarField[lastField]='V0_".$fields[$i]->name."';\nnameField[lastField]='".$TMP_fieldtitle."';\nlastField++;\n</script>\n ";
					}
					else if (substr($fields[$i]->dtype,0,8)=="datetext") $TMP_checkFIELD.= "<script>\nvarField[lastField]='V0_".$fields[$i]->name."_date".$TMP_date5."';\nnameField[lastField]='".$TMP_fieldtitle."';\nlastField++;\n</script>\n ";
					else if (substr($fields[$i]->dtype,0,4)=="date") $TMP_checkFIELD.= "<script>\nvarField[lastField]='V0_".$fields[$i]->name."_year';\nnameField[lastField]='".$TMP_fieldtitle."';\nlastField++;\n</script>\n ";
					else if (substr($fields[$i]->dtype,0,4)=="time") $TMP_checkFIELD.= "<script>\nvarField[lastField]='V0_".$fields[$i]->name."_hour';\nnameField[lastField]='".$TMP_fieldtitle."';\nlastField++;\n</script>\n ";
					$TMP_div.= "<span style='color:red;font-weight:bold;'>*</span> ";
				}
				$TMP_div.= $fields[$i] -> title;
//				if ($fields[$i] -> canbenull) $TMP_div.= "<BR>(",_DEF_NLSOptional.")";
				$TMP_div.= "</b>:";
			} else {
				if ($fields[$i]->title=="" && $fields[$i]->name!="") {
					// columna con titulo vacio que se muestra con la misma anterior
				} else {
					if ($TMP_numcol>0 && ($TMP_numcol+$fields[$i]->V_colsedit)>$V_colsedit) {
					    echo "</TR>";
					    $TMP_numcol=0;
					}
				}
				if ($TMP_numcol==0) echo "<TR id='IDR_".$fields[$i]->name."' class=detail>";
				if ($V_colsedit>1) {
					$TMP_width1=30/$V_colsedit;
					$TMP_width2=70/$V_colsedit;
				} else {
					$TMP_width1=30;
					$TMP_width2=70;
				}
				if ($fields[$i] -> title!="") {
					echo "<TD width=$TMP_width1% class=detailtit><span id='TIDV0_".$fields[$i]->name."'>\n";
				}
				if (!$fields[$i] -> canbenull) {
					if ($fields[$i]->dtype!="function" && substr($fields[$i]->dtype,0,4)!="auto" && substr($fields[$i]->dtype,0,4)!="date" && substr($fields[$i]->dtype,0,4)!="time")
						$TMP_checkFIELD.= "<script>\nvarField[lastField]='V0_".$fields[$i]->name."';\nnameField[lastField]='".$TMP_fieldtitle."';\nlastField++;\n</script>\n ";
					else if (substr($fields[$i]->dtype,0,8)=="datetext") $TMP_checkFIELD.= "<script>\nvarField[lastField]='V0_".$fields[$i]->name."_date".$TMP_date5."';\nnameField[lastField]='".$TMP_fieldtitle."';\nlastField++;\n</script>\n ";
					else if (substr($fields[$i]->dtype,0,4)=="date") $TMP_checkFIELD.= "<script>\nvarField[lastField]='V0_".$fields[$i]->name."_year';\nnameField[lastField]='".$TMP_fieldtitle."';\nlastField++;\n</script>\n ";
					else if (substr($fields[$i]->dtype,0,4)=="time") $TMP_checkFIELD.= "<script>\nvarField[lastField]='V0_".$fields[$i]->name."_hour';\nnameField[lastField]='".$TMP_fieldtitle."';\nlastField++;\n</script>\n ";
					echo "<span style='color:red;font-weight:bold;'>*</span> ";
				}
				if ($fields[$i] -> title!="") {
					echo $fields[$i] -> title;
//					if ($fields[$i] -> canbenull) echo "<BR>(",_DEF_NLSOptional.")";
					$TMP_colspan=1;
					if ($fields[$i] -> V_colsedit>1) $TMP_colspan=($fields[$i] -> V_colsedit*2)-1;
					$TMP_numcol+=$TMP_colspan;
					echo ":</span></TD>\n<TD class=detail";
					if ($TMP_colspan!=1) echo " COLSPAN=".$TMP_colspan;
					else echo " width=$TMP_width2% ";
					echo ">";
				}
				echo "<span id='IDV0_".$fields[$i]->name."'>";
			}
			if ($fields[$i]->dtype == "function") {
		    		$TMP="";
				$RAD_numfield=$i;
				if (!file_exists("modules/$V_dir/".$fields[$i]->extra) && file_exists("modules/phpRAD/".$fields[$i]->extra)) {
					$TMP_funcDir="modules/phpRAD/"; // directorio por defecto de funciones comunes
				} else {
					$TMP_funcDir="modules/$V_dir/";
				}
				$TMP=include($TMP_funcDir.$fields[$i]->extra);
				$i=$RAD_numfield;
				if ($TMP!==true && $TMP!="1" && $TMP!="") {
					if ($TMP_numcol>0) echo "</TR>";
					if ($fields[$i]->coledit!="") $TMP_div.= $TMP;
    					else echo $TMP;
					$TMP_numcol=0;
				}
			} else if ($fields[$i]->dtype == "geturl") {
				$TMP_file = fopen($fields[$i]->extra, "r");
				if ($TMP_file) {
					$TMP_content = "";
					while (!feof($TMP_file)) {
        					$TMP_line = fgets($TMP_file, 512000);
					        $TMP_content = $TMP_content.$TMP_line;
					}
				}
				fclose($TMP_file);
				$TMP=$TMP_content;
				if ($fields[$i]->coledit!="") $TMP_div.= $TMP;
    				else echo $TMP;
			} else {
				if ((($func == "edit")||($func == "new")) && ($reset=="" || $fields[$i]->readonly==true || $fields[$i]->dtype=="file" || $fields[$i]->dtype=="image")) $fvalue = $db->Record[$fields[$i]->name];
				else $fvalue = "";
				if (($fields[$i]->dtype=="file"||$fields[$i]->dtype=="image") && ($func == "new")) $fvalue = "";
				if ($fvalue == "" && ($reset==""||$fields[$i]->readonly==true)) {
					if ($func=="new" && $par0=="" && $fields[$i]->vdefault=="") $fvalue="";
					else $fvalue=evalVar($fields[$i]->vdefault);
				}
				if ($fields[$i] -> readonly == true) {
					if (eregi("num", $fields[$i]->dtype)) $TMP_fvalue=RAD_numero($fvalue,$fields[$i]->extra);
					else $TMP_fvalue=$fvalue;
					$TMP=RAD_showfield($fields[$i]->dtype, $fields[$i]->extra, $fvalue);
					//if ($fields[$i]->dtype=="date"||$fields[$i]->dtype=="datetext") $TMP.="<INPUT TYPE=HIDDEN NAME=V0_".$fields[$i]->name."_date VALUE=\"".htmlspecialchars($TMP_fvalue)."\">";
					//if ($fields[$i]->dtype=="date"||$fields[$i]->dtype=="datetext") $TMP.="<INPUT TYPE=HIDDEN NAME=V0_".$fields[$i]->name."_date5 VALUE=\"".htmlspecialchars($TMP_fvalue)."\">";
					$TMP.="<INPUT TYPE=HIDDEN NAME=V0_".$fields[$i]->name." VALUE=\"".htmlspecialchars($TMP_fvalue)."\"></span><span id='HIDV0_".$fields[$i]->name."'></span>";
					if ($fields[$i]->coledit!="") $TMP_div.= $TMP;
	    				else echo $TMP;
				} else {
					$TMP_extra=$fields[$i]->extra;
					if ($fields[$i]->parmlistSFF!="") $TMP_extra.="|".$fields[$i]->parmlistSFF;
					if ($fields[$i]->vonblur!="") $fields[$i]->vonchange="onchange=".$fields[$i]->vonchange." onblur=".$fields[$i]->vonblur;
					$TMP=RAD_editfield($fields[$i]->name, $fields[$i]->dtype, $fields[$i]->length, $fields[$i]->ilength, $TMP_extra, $fields[$i]->vonchange, $fields[$i]->canbenull, $fvalue,"V0_".$fields[$i]->name, "", $fields[$i]->vonfocus);
					if (eregi("popup", $fields[$i]->dtype) && $fields[$i]->dtype!="popupdbSFF" && !$fields[$i] -> canbenull)
					    $TMP_checkFIELD.= "<script>\nvarField[lastField]='V0_".$fields[$i]->name."_literal';\nnameField[lastField]='Literal de ".$TMP_fieldtitle."';\nlastField++;\n</script>\n ";
					if ($fields[$i]->coledit!="") $TMP_div.= $TMP."</span>\n<span id='HIDV0_".$fields[$i]->name."'>".$fields[$i]->help."</span>";
	    				else echo $TMP."</span><span id='HIDV0_".$fields[$i]->name."'>".$fields[$i]->help."</span>";
				}
				$TMP="";
			}
			if ($fields[$i+1]->title!="" || $fields[$i+1]->name=="") { // si siguiente campo no es titulo vacio cierra columna
				if ($fields[$i]->coledit!="") $TMP_div.= "</div>\n";
				else echo "</TD>\n";
				//$TMP_numcol++;
			}
			if ($TMP_numcol>$V_colsedit) {
			    echo "</TR>";
			    $TMP_numcol=0;
			}
		}
		if (!$TMP_setfoco_primer_campo) {
			if ((!$TMP_overlap || $fields[$i]->overlap==$V_lap || $V_lap=="x") &&
			   (($func != "new" || $fields[$i]->nonew==false) && ($func != "edit" || $fields[$i]->noedit==false)) &&
			   ($fields[$i]->readonly==false) &&
			    (!eregi("auto", $fields[$i]->dtype))&&
			   (eregi("date", $fields[$i]->dtype)||eregi("time", $fields[$i]->dtype)||eregi("plist", $fields[$i]->dtype)||eregi("checkbox", $fields[$i]->dtype)||eregi("stand", $fields[$i]->dtype)||eregi("num", $fields[$i]->dtype)||eregi("bpopup", $fields[$i]->dtype)||eregi("fpopup", $fields[$i]->dtype)||eregi("popupdbSFF", $fields[$i]->dtype))) {
				if (substr($fields[$i]->dtype,0,4)=="date") {
					if (eregi("text",$fields[$i]->dtype)) {
						$TMP_inputfieldname=$fields[$i]->name."_date".$TMP_date5;
					} else $TMP_inputfieldname=$fields[$i]->name."_day";
				} else if (substr($fields[$i]->dtype,0,4)=="time") {
					if (ereg("text",$fields[$i]->dtype)) $TMP_inputfieldname=$fields[$i]->name."_time";
					else $TMP_inputfieldname=$fields[$i]->name."_hour";
				} else if (substr($fields[$i]->dtype,0,6)=="bpopup") {
					$TMP_inputfieldname=$fields[$i]->name."_literal";
				} else $TMP_inputfieldname=$fields[$i]->name;
				echo "\n<script>\nfor(i=0;i<document.F.length;i++) { if (document.F[i].name=='V0_".$TMP_inputfieldname."'||document.F[i].name=='V0_".$TMP_inputfieldname."'+'[]') { if (document.F[i].type=='text') document.F[i].select(); else document.F[i].focus(); } }\n</script>\n";
				$TMP_setfoco_primer_campo=true;
			}
		}
	}
	echo "</TABLE>\n".$TMP_div;
	if ($RAD_editbuttons == ""){
		global $RAD_buttonDelete, $RAD_buttonDuplicate;
		echo "<table style='width:100%;'><tr><td style='text-align:center;'><INPUT TYPE=BUTTON onClick='RAD_checkFields(this);' class=button NAME=Save ACCESSKEY='S' TITLE='ALT+S' VALUE='"._DEF_NLSSave."' id='RAD_SAVEBUTTON'>\n";
		if ($RAD_buttonDuplicate!="") echo " &nbsp; &nbsp; <INPUT ACCESSKEY='X' TITLE='ALT+X' TYPE=BUTTON OnClick='javascript:document.F.func.value=\"insert\";RAD_checkFields(this);' class=button NAME=Duplicate ACCESSKEY='X' TITLE='ALT+X' VALUE='"._DEF_NLSDuplicate."'>\n";
		if ($RAD_buttonDelete!="") echo " &nbsp; &nbsp;  <INPUT ACCESSKEY='D' TITLE='ALT+D' TYPE=BUTTON OnClick='javascript:document.F.func.value=\"delete\";RAD_checkFields();' class=button NAME=Delete ACCESSKEY='D' TITLE='ALT+D' VALUE='"._DEF_NLSDeleteString."'>\n";
		echo " &nbsp; &nbsp; <INPUT ACCESSKEY='R' TITLE='ALT+R' TYPE=BUTTON OnClick=\"javascript:document.location=document.location.href+'&reset=x'\" class=button VALUE='"._DEF_NLSClearAll."'></td></tr></table>";
	} else echo $RAD_editbuttons;
	echo "</FORM>\n".$TMP_checkFIELD;
}
?>
