<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}
if ($func == "detail" && $menudetail == false) {
    $func="error";
}
//---------------------------------------------------------------------------
//------------------------- Detail
//---------------------------------------------------------------------------
$TMP_div="";
if ($func == "detail" && $subfunc == "barcode") {
	include_once("modules/phpRAD/RAD_barcode.php");
	return;
}
if ($func == "detail" || $func == "confirmdelete") {
	$TMP_idnames = "";
	$TMP_overlap=false;
	if ($subfunc=="list") $V_lap="";
	$idn = "";
	$urlidnames = "";
	if($V_lap!="") {
		$TMP_lapencontrado=false;
		for ($i = 0; $i < $numf; $i++) {
			if($V_lap==$fields[$i]->overlap||$V_lap=="x") $TMP_lapencontrado=true;
		}
		if (!$TMP_lapencontrado) $V_lap="";
	}
	RAD_checkPars();
	$TMP_contVlap=0;
	for ($i = 0; $i < $numf; $i++) {
		if($fields[$i]->overlap!="" && $fields[$i]->nodetail!=true) {
			if ($subfunc!="list") $TMP_overlap=true;
			if ($TMP_overlaptext[$fields[$i]->overlap]=="") {
				$TMP_contVlap++;
				$TMP_overlaptext[$fields[$i]->overlap]=$TMP_contVlap;
				if($V_lap=="" && !ereg(",".$TMP_contVlap."," , ",".$RAD_lapoff.",") && !ereg(",".$fields[$i]->overlap."," , ",".$RAD_lapoff.",")) $V_lap=$fields[$i]->overlap;
			}
		}
		if (${"idname$i"} != "") {
			if ($TMP_idnames == "") {
				$TMP_idnames = "${"idname$i"} = '" . ${"par$i"} . "'";
				$idn = "${"par$i"}";
			} else {
				$TMP_idnames .= " AND ${"idname$i"} = '" . ${"par$i"} . "'";
				$idn .= ", ${"par$i"}";
		}
			$urlidnames .= "&par$i=".urlencode("${"par$i"}");
			$hiddenidnames .= "<input type=hidden name=par$i value='".${"par$i"}."'>\n";
		}
	}
	$tmpdefaultfilter="";
	if ($defaultfilter!="") {
		if ($TMP_idnames =="") $tmpdefaultfilter=" WHERE ".$defaultfilter;
		else $tmpdefaultfilter=" AND ".$defaultfilter;
	}
	$cmdSQL="SELECT * FROM $tablename WHERE $TMP_idnames".$tmpdefaultfilter;
	$TMP_initime=RAD_microtime();
	if (_SQL_DEBUG!="0") echo $TMP_initime." SQL query: ".$cmdSQL;
	if ($db -> query($cmdSQL)) {
		$TMP_diftime=RAD_microtime()-$TMP_initime;
		if (_SQL_DEBUG!="0") echo " [".substr($TMP_diftime,0,8)."]";
		if ($db -> next_record()) {
			if ($subfunc=="search_js") {
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
		    	    $searchfieldlit=urldecode($searchfieldlit);
			    $TMP_arrf=explode(" ",$searchfieldlit);
			    $value = $db->Record[$TMP_arrf[0]];
			    $value=str_replace('"','`',$value);
			    $value=str_replace('\'','`',$value);
			    $value=str_replace("\n","",$value);
			    $value=str_replace("\r","",$value);
			    $value2= $db->Record[$TMP_arrf[1]];
			    $value2=str_replace('"','`',$value2);
			    $value2=str_replace('\'','`',$value2);
			    $value2=str_replace("\n","",$value2);
			    $value2=str_replace("\r","",$value2);
			    $value3= $db->Record[$searchfieldlit];
			    $value3=str_replace('"','`',$value3);
			    $value3=str_replace('\'','`',$value3);
			    $value3=str_replace("\n","",$value3);
			    $value3=str_replace("\r","",$value3);
			    $callback_func=getSessionVar("SESSION_RAD_".$searchfieldX."_jscallback");
			    if ($callback_func=='') $callback_func="sel";
			    if (count($TMP_arrf)>1) echo "\n<script>\n$callback_func('".$value." ".$value2." ','".$par0."');\n";
			    else echo "\n<script>\n$callback_func('".$value3."','".$par0."');\n";
			    //if (_DEF_POPUP_MARGIN=="SUBMODAL") echo "window.top.hidePopWin();\n";
			    //else echo "window.close();\n";
			    echo "next();\n";
			    echo "</script>\n";
//				echo "\n<script>\nsalto=history.length-1;\nhistory.go(-salto);\n</script>\n";
			    die();
			}
			if ($subfunc=="list" && _DEF_PRINT_SHORTCUT=="1" && $RAD_PRINT_SHORTCUT!="0") echo "<div style='position:absolute top:0px;left:0px; width:200px; height:50px; visibility:visible;'><a href='".RAD_IMGShortCut("","","")."'><img src='".RAD_IMGShortCut("","","")."' border=0></a>&nbsp;</div>";
			if ($subfunc!="list") echo "<FORM style='margin:0;' autocomplete=off NAME=FORM1 ACTION=$PHP_SELF METHOD=POST><INPUT TYPE=HIDDEN NAME=func VALUE='delete'>\n".
				"<INPUT TYPE=HIDDEN NAME=PHPSESSID VALUE='$PHPSESSID'>\n".
				$FORMROI.$hiddenidnames."</FORM>";
			if ($TMP_overlap && count($TMP_overlaptext)>0) {
			    echo '<div data-role="navbar">';
			    $TMP_contVlap=0;
			    $TMP_PARAMETROS_URL=RAD_delParamURL($GLOBALS["QUERY_STRING"],"V_lap");
			    $count=0;
			    foreach ($fields as $campo) {
                    if (ereg("RAD_subbrowse",$campo->extra)) $count++;
                    $TMP_PARAMETROS_URL=RAD_delParamURL($TMP_PARAMETROS_URL,"V_sub_".$count."_roi");
			    }
			    $TMP_PARAMETROS_URL=RAD_delParamURL($TMP_PARAMETROS_URL,"V_prevfunc");
			    if ($V_prevfunc!="") $TMP_PARAMETROS_URL.="&V_prevfunc=".urlencode($V_prevfunc);
			    $TMP_PARAMETROS_URL=str_replace("?&=","?",$TMP_PARAMETROS_URL);
			    $TMP_PARAMETROS_URL=str_replace("&=&","&",$TMP_PARAMETROS_URL);
			    echo "<ul>";
			    foreach ($TMP_overlaptext as $text=>$num) {
                    if ($text=="") $text=" ";
                    $TMP_contVlap++;
                    //if ($TMP_contVlap==_DEF_LapBreak) echo "</li><li>";
                    $TMP_url=$PHP_SELF."?".$TMP_PARAMETROS_URL."&V_lap=".urlencode($text);
                    if (strtolower($lapoff)!="x" && !ereg(",".$TMP_contVlap.",",",".$lapoff.",") &&
                    !ereg(",".$TMP_contVlap."," , ",".$RAD_lapoff.",") && !ereg(",".$V_lap."," , ",".$RAD_lapoff.",")) {
                        $textNLS=$text;
                        if ($RAD_NLS[$text]!="") $textNLS=$RAD_NLS[$text];
                        if (getSessionVar("SESSION_NLS".$text)!="") $textNLS=getSessionVar("SESSION_NLS".$text);
                        if ($text==$V_lap) echo '<li><a href="#" class="ui-btn-active">'.$textNLS.'</li>';
                        else if ($V_lap=="x") echo '<li><a href="'.$TMP_url.'">'.$textNLS.'</a></li>';
                        else echo '<li><a href="'.$TMP_url.'">'.$textNLS.'</a></li>';
                    }
			    }
			    $TMP_url=$PHP_SELF."?".$TMP_PARAMETROS_URL."&V_lap=x";
			    if (($RAD_expandlap==""||$RAD_expandlap=="1") && $TMP_contVlap>1 && $lapoff=="") {
                    if ($V_lap=="x") echo '<li><a href="#" class="ui-btn-active"><img src="images/expand.gif" style="width: 13px; height: 13px;"></a></li>';
                    else echo '<li><a href="'.$TMP_url.'"><img src="images/expand.gif" style="width: 13px; height: 13px;"></a></li>';
			    }
			    //if ($RAD_printbarcode!="") echo RAD_submenu_off("<a title='Imprime Codigo de Barras de Registro' class=submenuoff href='javascript:RAD_OpenW(\"".$PHP_SELF."?par0=$par0&dbname=$dbname&V_dir=$V_dir&V_idmod=$V_idmod&func=detail&subfunc=barcode&blocksoff=x&menuoff=x&headeroff=x&footeroff=x\",650,460);'><img border=0 style='vertical-align:middle;' src='images/print.png'> $par0 </a>\n");
			    echo "</ul></div>\n";
			}
			echo "<TABLE class=detail>\n";
			$inifield=0; $lastfield=$numf-1;
			if ($fieldedit !="" && $findex[$fieldedit]>0) {
				$inifield=$lastfield=$findex[$fieldedit];
				echo "<INPUT TYPE=HIDDEN NAME=fieldedit VALUE='".$fieldedit."'>\n";
			}
			if (file_exists("modules/$V_dir/common.defaults.php")) {
				$TMP=include ("modules/$V_dir/common.defaults.php");
				if ($TMP!==true && $TMP!="1") echo $TMP;
				$TMP="";
			}
			if ($filename!="") {
				if (file_exists($RAD_DirBase.$filename.".defaults.php")) {
				    $TMP="";
				    $TMP=include ($RAD_DirBase.$filename.".defaults.php");
		    		    if ($TMP!==true && $TMP!="1") echo $TMP;
				}
			} else {
				if (file_exists($RAD_DirBase.$V_mod.".defaults.php")) {
				    $TMP="";
				    $TMP=include ($RAD_DirBase.$V_mod.".defaults.php");
				    if ($TMP!==true && $TMP!="1") echo $TMP;
				}
			}
			$TMP_numcol=0;
			for ($i = $inifield; $i < $lastfield+1; $i++) {
				if ($fields[$i+1]->title==$fields[$i]->title && $fields[$i+1]->nodetail==false) $fields[$i+1]->title=""; // si el siguiente titulo es igual se pone en blanco para que salgan juntos
				if ($fields[$i]->type=="auto_increment" && $fields[$i]->browsable==false) continue;
				if ($subfunc!="list" && $fields[$i]->nodetail==true) continue;
				if ($subfunc=="list" && $fields[$i]->noprint==true) continue;
				if ($fields[$i]->type=="uniqid" && $fields[$i]->browsable==false) continue;
				else {
					$TMP=RAD_showfield($fields[$i]->dtype, $fields[$i]->extra, $db -> Record[$fields[$i] -> name]);
				}
				if ($fields[$i]->dtype == "function" && $fields[$i]->type == "function") {
                                	if ($subfunc!="list" && $fields[$i]->nodetail==true) continue;
                                	if ($subfunc=="list" && $fields[$i]->noprint==true) continue;
				    	$TMP="";
					$RAD_numfield=$i;
					if (!file_exists($RAD_DirBase.$fields[$i]->extra) && file_exists("modules/phpRAD/".$fields[$i]->extra)) {
						$TMP_funcDir="modules/phpRAD/"; // directorio por defecto de funciones comunes
					} else {
						$TMP_funcDir=$RAD_DirBase;
					}
					if($TMP_overlap && $subfunc!="list" && $fields[$i]->overlap!=$V_lap && $V_lap!="x") {
						if (substr($fields[$i]->extra,0,13)!="RAD_subbrowse") $TMP=include($TMP_funcDir.$fields[$i]->extra);
					} else $TMP=include($TMP_funcDir.$fields[$i]->extra);
					$i=$RAD_numfield;
					if($TMP_overlap && $subfunc!="list" && $fields[$i]->overlap!=$V_lap && $V_lap!="x") continue;
	    				if ($TMP!==true && $TMP!="1" && $TMP!="") {
	    					if ($TMP_numcol>0) echo "</TR>";
	    					echo $TMP;
						$TMP_numcol=0;
					}
					continue;
				}
				if ($fields[$i]->type == "function" && $fields[$i]->dtype == "geturl") {
                                	if ($subfunc!="list" && $fields[$i]->nodetail==true) continue;
                                	if ($subfunc=="list" && $fields[$i]->noprint==true) continue;
					if($TMP_overlap && $subfunc!="list" && $fields[$i]->overlap!=$V_lap && $V_lap!="x") {
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
					if($TMP_overlap && $subfunc!="list" && $fields[$i]->overlap!=$V_lap && $V_lap!="x") continue;
	    				echo $TMP;
					continue;
				}
				if($TMP_overlap && $subfunc!="list" && $fields[$i]->overlap!=$V_lap && $V_lap!="x") continue;
				if ($fields[$i]->browsedit || $fields[$i]->fieldedit) {
					$TMP_PARAMETROS_URL=RAD_delParamURL($URLROI,"headeroff");
					$TMP_PARAMETROS_URL=RAD_delParamURL($TMP_PARAMETROS_URL,"footeroff");
					$TMP_PARAMETROS_URL=RAD_delParamURL($TMP_PARAMETROS_URL,"menuoff");
					$TMP_height=100;
					if (_DEF_POPUP_MARGIN=="SUBMODAL") $TMP_height+=80;
//					$htmlEdit="<A HREF=\"".$PHP_SELF."?func=edit&dbname=$dbname&menuoff=x&footeroff=x&headeroff=x&fieldedit=".$fields[$i]->name.$urlidnames.$URLROI."\">"._DEF_NLSBrowseEdit." ";
					$TMP_URLROI=RAD_delParamURL($URLROI,"V_roi");
					$htmlEdit="<A class=detailtit HREF=\"javascript:RAD_OpenW('".$PHP_SELF."?func=edit&dbname=$dbname&subfunc=browse&menuoff=x&footeroff=x&headeroff=x&lapoff=x&fieldedit=".$fields[$i]->name.$urlidnames.$TMP_URLROI."',600,".$TMP_height.");\">"._DEF_NLSBrowseEdit." ";
					$htmlEditEnd="</A>"; } else { $htmlEdit=""; $htmlEditEnd=""; }
				if ($fields[$i]->coldetail!="") $TMP_div.= "<div id='D_F".$i."' style='position:absolute; z-index:6; visibility: visible; top:".$fields[$i]->rowdetail."; left:".$fields[$i]->coldetail.";'>\n<b>".$htmlEdit.$fields[$i]->title.$htmlEditEnd."</b>:";
				else {
				    if ($fields[$i]->title=="" && $fields[$i]->name!="") {
				   		// columna con titulo vacio que se muestra con la misma anterior
				    } else {
				    	if ($TMP_numcol>0 && ($TMP_numcol+$fields[$i]->V_colsdetail)>$V_colsdetail) {
						echo "</TR>";
				       	 	$TMP_numcol=0;
				    	}
				    }
				    if ($TMP_numcol==0) echo "<TR class=detail>";
				    if ($V_colsdetail>1) {
					$TMP_width1=30/$V_colsdetail;
					$TMP_width2=70/$V_colsdetail;
				    } else {
					$TMP_width1=30;
					$TMP_width2=70;
				    }
				    if ($fields[$i] -> title!="") {
				    	echo "<TD width=$TMP_width1% class=detailtit><span id='TIDV0_".$fields[$i]->name."'>".$htmlEdit.$fields[$i]->title.$htmlEditEnd."";
				    }
				    if ($fields[$i] -> title!="") {
				    	$TMP_colspan=1;
				    	if ($fields[$i] -> V_colsdetail>1) $TMP_colspan=($fields[$i] -> V_colsdetail*2)-1;
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
					if (!file_exists($RAD_DirBase.$fields[$i]->extra) && file_exists("modules/phpRAD/".$fields[$i]->extra)) {
						$TMP_funcDir="modules/phpRAD/"; // directorio por defecto de funciones comunes
					} else {
						$TMP_funcDir=$RAD_DirBase;
					}
					$TMP=include($TMP_funcDir.$fields[$i]->extra);
					$i=$RAD_numfield;
					if ($TMP!==true && $TMP!="1" && $TMP!="") {
	    					if ($TMP_numcol>0) echo "</TR>";
						if ($fields[$i]->coldetail!="") $TMP_div.= $TMP;
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
					if ($fields[$i]->coldetail!="") $TMP_div.= $TMP;
					else echo $TMP;
				} else {
					$TMP_linkvalue="";
					$value=$db -> Record[$fields[$i] -> name];
					$TMP_funclink=$fields[$i]->funclink;
					$A_TMP_funclink=explode("/",$TMP_funclink);
					if (count($A_TMP_funclink)>1) { $TMP_funclink=$A_TMP_funclink[1]; $TMP_dir=$A_TMP_funclink[0]; }
					else { $TMP_dir=$V_dir; }
					if ($TMP_funclink!="") if (is_modulepermitted("", $TMP_dir, $TMP_funclink) || is_admin()) {
						if (in_array($fields[$i]->dtype,array("plistm","plistdbm","plistdbmtree","popupdbm","checkboxm","checkboxdbm"))){
						    // Campo multiple con modulo de enlace
						    $TMP_arr = explode('<br>',$TMP);
		    				    if (count($TMP_arr)>0) $TMP = implode("</a><br>\n<a href=\"javascript:RAD_OpenW('$PHP_SELF?V_dir=$TMP_dir&V_mod=$TMP_funclink&funclink=1&dbname=$dbname&headeroff=X&footeroff=X&menuoff=&func=detail&par0=%%1%%&subfunc=browse',1,1);\">",$TMP_arr);
						    if ($TMP!="") $TMP = "<a href=\"javascript:RAD_OpenW('$PHP_SELF?V_dir=$TMP_dir&V_mod=$TMP_funclink&funclink=1&dbname=$dbname&headeroff=X&footeroff=X&menuoff=&func=detail&par0=%%1%%&subfunc=browse',1,1);\">".$TMP."</a>";
						    $TMP_cont=0;
						    $TMP_arr = explode('%%1%%',$TMP);
						    $TMP = "";
						    foreach (explode(',',$value) as $key=>$val){
							if (trim($val)=='') continue;
							$TMP.=$TMP_arr[$TMP_cont].$val;
							$TMP_cont++;
						    }
						    $TMP.=$TMP_arr[$TMP_cont];
						} else {
					    	    //$TMP_linkvalue="<a href=\"javascript:RAD_OpenW('$PHP_SELF?V_dir=$TMP_dir&V_mod=$TMP_funclink&funclink=1&dbname=$dbname&headeroff=X&footeroff=X&menuoff=&func=detail&par0=".urlencode($value)."&subfunc=browse&V_roi=".urlencode($V_roi)."',800,600);\">";
					    	    if ($value!="") $TMP_linkvalue="<a href=\"javascript:RAD_OpenW('$PHP_SELF?V_dir=$TMP_dir&V_mod=$TMP_funclink&funclink=1&dbname=$dbname&headeroff=X&footeroff=X&menuoff=&func=detail&par0=".urlencode($value)."&subfunc=browse',1,1);\">";
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
							$TMP_arr = explode('<br>',$TMP);
		    					if (count($TMP_arr)>0) $TMP = implode("</a>\n<br><a href=\"javascript:RAD_OpenW('$PHP_SELF?V_dir=$TMP_dir&V_mod=$TMP_funclink&funclink=1&dbname=$dbname&headeroff=X&footeroff=X&menuoff=&func=detail&par0=%%1%%&subfunc=browse',1,1);\">",$TMP_arr);
							if ($TMP!="") $TMP = "<a href=\"javascript:RAD_OpenW('$PHP_SELF?V_dir=$TMP_dir&V_mod=$TMP_funclink&funclink=1&dbname=$dbname&headeroff=X&footeroff=X&menuoff=&func=detail&par0=%%1%%&subfunc=browse',1,1);\">".$TMP."</a>";
							$TMP_cont=0;
							$TMP_arr = explode('%%1%%',$TMP);
							$TMP = "";
							foreach (explode(',',$value) as $key=>$val){
							    if (trim($val)=='') continue;
							    $TMP.=$TMP_arr[$TMP_cont].$val;
							    $TMP_cont++;
							}
							$TMP.=$TMP_arr[$TMP_cont];
						    } else {
						        //$TMP_linkvalue="<a href=\"javascript:RAD_OpenW('$PHP_SELF?V_dir=$TMP_dir&V_mod=$TMP_funclink&funclink=1&dbname=$dbname&headeroff=X&footeroff=X&menuoff=&func=detail&par0=".urlencode($value)."&subfunc=browse&V_roi=".urlencode($V_roi)."',800,600);\">";
							if ($value!="") $TMP_linkvalue="<a href=\"javascript:RAD_OpenW('$PHP_SELF?V_dir=$TMP_dir&V_mod=$TMP_funclink&funclink=1&dbname=$dbname&headeroff=X&footeroff=X&menuoff=&func=detail&par0=".urlencode($value)."&subfunc=browse',1,1);\">";
						    }
						}
					}

					if ($TMP_linkvalue!="") $TMP=$TMP_linkvalue.$TMP."</a>";
					if ($fields[$i]->coldetail!="") $TMP_div.= $TMP;
					else echo $TMP;
				}
				if ($fields[$i]->coldetail!="") $TMP_div.= "</span><span id='HIDV0_".$fields[$i]->name."'></span>\n";
				else {
					if (substr($fields[$i]->dtype,0,3)!="num") echo "&nbsp;";
					echo "</span><span id='HIDV0_".$fields[$i]->name."'></span>\n";
				}
				if ($fields[$i+1]->title!="" || $fields[$i+1]->name=="") { // si siguiente campo no es titulo vacio cierra columna
					if ($fields[$i]->coldetail!="") $TMP_div.= "</div>\n";
					else echo "</TD>\n";
					//$TMP_numcol++;
				}
				if ($TMP_numcol>=$V_colsdetail && $fields[$i+1]->title!="") {
				    echo "</TR>";
				    $TMP_numcol=0;
				}
			}
		} else {
			if ($subbrowseSID=="") {
				echo "<script type='text/javascript'>\n\n";
				if ($defaultfunc=="detail") $TMP_func="browse";
				else $TMP_func="";
				echo "document.location.href='".$PHP_SELF."?func=".$TMP_func."&V_dir=".$V_dir."&V_mod=".$V_mod."&dbname=".$dbname."&PHPSESSID=".$PHPSESSID."";
				if ($blocksoff!="") echo "&blocksoff=".$blocksoff;
				if ($footeroff!="") echo "&footeroff=".$footeroff;
				if ($headeroff!="") echo "&headeroff=".$headeroff;
				if ($menuoff!="") echo "&blocksoff=".$menuoff;
				echo "';\n</script>\n";
				die();
			}
		}
		echo "</TABLE>\n".$TMP_div;
	    if ($subfunc=="list") {
		$TMP_fdoc="";
// CONVENIO: Se supone que el ultimo campo de tipo file se utiliza para guardar las impresiones
		for ($ki = 0; $ki < $numf; $ki++) if ($fields[$ki]->dtype=="file") $TMP_fdoc=$fields[$ki]->name;
		RAD_saveHTML($tablename,$TMP_fdoc,$TMP_idnames.$tmpdefaultfilter);
		echo "<script type='text/javascript'>\nwindow.print();\n</script>\n";
	    }
	} else {
		$func = "error";
		$RAD_errorstr .= $cmdSQL.$db -> Error;
	}
}
?>
