<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}
if ($func == "insert" && $menunew == false) {
    $func="error";
}
//---------------------------------------------------------------------------
//------------------------- Insert new register
//---------------------------------------------------------------------------
if ($func == "insert") {
	$ok = true;
	$fieldnames = "";
	$fieldvalues = "";
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
	for ($i = 0; $i < $numf && $ok; $i++) {
		$fname = $fields[$i] -> name;
		$$fname=${"V0_".$fname};
		if ($fields[$i]->noinsert==true) continue;
  		if ($fields[$i] -> type == "uniqid") {
  			$parx_value_uniqid=${"V0_".$fname};
  			$parx_fname_uniqid=$fname;
  		}
		if ($fields[$i]->type == "function" && $fields[$i]->dtype == "function") {
			$TMP="";
			$RAD_numfield=$i;
			if (!file_exists("modules/$V_dir/".$fields[$i]->extra) && file_exists("modules/phpRAD/".$fields[$i]->extra)) {
				$TMP_funcDir="modules/phpRAD/"; // directorio por defecto de funciones comunes
			} else {
				$TMP_funcDir="modules/$V_dir/";
			}
			$TMP=include($TMP_funcDir.$fields[$i]->extra);
			$i=$RAD_numfield;
		        if ($TMP!==true && $TMP!="1") echo $TMP;
		} elseif ($fields[$i]->type == "function" && $fields[$i]->dtype == "geturl") {
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
		        echo $TMP;
		} elseif (($fields[$i]->readonly == true)&&($fields[$i]->vdefault!="")) {
			if ($fieldnames != "") $fieldnames .= ",";
			if ($fieldvalues != "") $fieldvalues .= ",";
			$fieldnames .= $fields[$i]->name;
			$fields[$i]->vdefault=evalVar($fields[$i]->vdefault);
			$fieldvalues .= tosql($fields[$i]->vdefault);
		} elseif ($fields[$i] -> dtype != "auto_increment") { // don't insert auto_increment fields
			$tname = $fields[$i] -> title;
			${"V0_".$fname."_year"}=trim(${"V0_".$fname."_year"});
			${"V0_".$fname."_month"}=trim(${"V0_".$fname."_month"});
			${"V0_".$fname."_day"}=trim(${"V0_".$fname."_day"});
			${"V0_".$fname."_hour"}=trim(${"V0_".$fname."_hour"});
			${"V0_".$fname."_min"}=trim(${"V0_".$fname."_min"});
			${"V0_".$fname."_sec"}=trim(${"V0_".$fname."_sec"});
			if (!$fields[$i]->canbenull&&substr($fields[$i]->dtype,0,4)=="date"&&!${"V0_".$fname."_year"}>0) ${"V0_".$fname."_year"}=date("Y");
			if ($fields[$i] -> dtype == "date") {
				if (${"V0_".$fname."_year"}=="" && ${"V0_".$fname."_month"}=="" && ${"V0_".$fname."_day"}=="") {
                                    $$fname==evalVar($fields[$i]->vdefault);
				} else {
                                    $$fname=${"V0_".$fname."_year"}."-".${"V0_".$fname."_month"}."-".${"V0_".$fname."_day"};
                                }
			}
			if ($fields[$i] -> dtype == "datetime") {
				if (${"V0_".$fname."_hour"}!="") {
					if (trim(${"V0_".$fname."_min"})=="") ${"V0_".$fname."_min"}=0;
					if (trim(${"V0_".$fname."_sec"})=="") ${"V0_".$fname."_sec"}=0;
				}
				if (${"V0_".$fname."_year"}=="" && ${"V0_".$fname."_month"}=="" && ${"V0_".$fname."_day"}==""
				    && ${"V0_".$fname."_hour"}=="" && ${"V0_".$fname."_min"}=="" && ${"V0_".$fname."_sec"}=="")
				    $$fname==evalVar($fields[$i]->vdefault);
				else {
	    			    $$fname=${"V0_".$fname."_year"}."-".${"V0_".$fname."_month"}."-".${"V0_".$fname."_day"};
				    $$fname.=" ".${"V0_".$fname."_hour"}.":".${"V0_".$fname."_min"}.":".${"V0_".$fname."_sec"};
				}
			}
			if ($fields[$i] -> dtype == "date" || $fields[$i] -> dtype == "datetime") {
				if (ereg("--",$$fname)||ereg("-00-",$$fname)||ereg("-00",$$fname)) $$fname="";
			}
			if ($fields[$i] -> dtype == "datetimeint") {
				if (${"V0_".$fname."_hour"}!="") {
					if (trim(${"V0_".$fname."_min"})=="") ${"V0_".$fname."_min"}=0;
					if (trim(${"V0_".$fname."_sec"})=="") ${"V0_".$fname."_sec"}=0;
				}
				if (${"V0_".$fname."_year"}=="" && ${"V0_".$fname."_month"}=="" && ${"V0_".$fname."_day"}==""
				    && ${"V0_".$fname."_hour"}=="" && ${"V0_".$fname."_min"}=="" && ${"V0_".$fname."_sec"}=="")
				    $tmp==evalVar($fields[$i]->vdefault);
				else {
				    $tmp=${"V0_".$fname."_year"}."-".${"V0_".$fname."_month"}."-".${"V0_".$fname."_day"};
				    $tmp.=" ".${"V0_".$fname."_hour"}.":".${"V0_".$fname."_min"}.":".${"V0_".$fname."_sec"};
				}
				$$fname=mktime(${"V0_".$fname."_hour"},${"V0_".$fname."_min"},${"V0_".$fname."_sec"},${"V0_".$fname."_month"},${"V0_".$fname."_day"},${"V0_".$fname."_year"});
			}
			if ($fields[$i] -> dtype == "dateint") {
				if (${"V0_".$fname."_year"}=="" && ${"V0_".$fname."_month"}=="" && ${"V0_".$fname."_day"}=="")
				    $tmp==evalVar($fields[$i]->vdefault);
				else {
				    $tmp=${"V0_".$fname."_year"}."-".${"V0_".$fname."_month"}."-".${"V0_".$fname."_day"};
				    $tmp.=" ".${"V0_".$fname."_hour"}.":".${"V0_".$fname."_min"}.":".${"V0_".$fname."_sec"};
				}
				$$fname=mktime("0","0","0",${"V0_".$fname."_month"},${"V0_".$fname."_day"},${"V0_".$fname."_year"});
			}
			if ($fields[$i] -> dtype == "datetimeinttext" || $fields[$i] -> dtype == "dateinttext" || $fields[$i] -> dtype == "datetimetext" || $fields[$i] -> dtype == "datetext") {
				if (trim(${"V0_".$fname."_date"})!="") ${"V0_".$fname}=trim(${"V0_".$fname."_date"});
				else if (trim(${"V0_".$fname})!="") ${"V0_".$fname."_date"}=trim(${"V0_".$fname});
				${"V0_".$fname."_date"}=str_replace(":","",${"V0_".$fname."_date"});
				${"V0_".$fname."_date"}=str_replace("/","",${"V0_".$fname."_date"});
				${"V0_".$fname."_date"}=str_replace("-","",${"V0_".$fname."_date"});
				${"V0_".$fname."_date"}=str_replace("  "," ",${"V0_".$fname."_date"});
				$TMP_elems=explode(" ",${"V0_".$fname."_date"});
				if (count($TMP_elems)>1) $TMP_date=$TMP_elems[0];
				else $TMP_date=${"V0_".$fname."_date"};
				$TMP_dateyear=substr($TMP_date,4);
				if (strlen($TMP_dateyear)==2) {
					if ($TMP_dateyear<70) $TMP_dateyear="20".$TMP_dateyear;
					else $TMP_dateyear="19".$TMP_dateyear;
				}
				if (strlen($TMP_dateyear)>4) $TMP_dateyear=substr($TMP_dateyear,0,4);
				$$fname=$TMP_dateyear."-".substr($TMP_date,2,2)."-".substr($TMP_date,0,2);
				${"V0_".$fname."_month"}=substr($TMP_date,2,2);
				${"V0_".$fname."_day"}=substr($TMP_date,0,2);
				${"V0_".$fname."_year"}=$TMP_dateyear;
				if ($fields[$i] -> dtype == "dateinttext") {
					$$fname=mktime("0","0","0",${"V0_".$fname."_month"},${"V0_".$fname."_day"},${"V0_".$fname."_year"});
				}
			}
			if ($fields[$i] -> dtype == "timetext" || $fields[$i] -> dtype == "datetimetext" || $fields[$i] -> dtype == "datetimeinttext" || $fields[$i] -> dtype == "timeinttex") {
				${"V0_".$fname."_time"}=trim(${"V0_".$fname."_time"});
				${"V0_".$fname."_time"}=str_replace(":","",${"V0_".$fname."_time"});
				${"V0_".$fname."_time"}=str_replace("/","",${"V0_".$fname."_time"});
				${"V0_".$fname."_time"}=str_replace("-","",${"V0_".$fname."_time"});
				${"V0_".$fname."_time"}=str_replace("  "," ",${"V0_".$fname."_time"});
				$TMP_elems=explode(" ",${"V0_".$fname."_time"});
				if (count($TMP_elems)>1) $TMP_time=$TMP_elems[1];
				else $TMP_time=${"V0_".$fname."_time"};
				if ($fields[$i] -> dtype =="datetimetext")
					$$fname.=" ";
				${"V0_".$fname."_hour"}=substr($TMP_time,0,2);
				${"V0_".$fname."_min"}=substr($TMP_time,2,2);
				${"V0_".$fname."_sec"}=substr($TMP_time,4,2);
				$$fname.=substr($TMP_time,0,2).":".substr($TMP_time,2,2).":".substr($TMP_time,4,2);
				if ($fields[$i] -> dtype =="timeinttext")
					$$fname=${"V0_".$fname."_hour"}*3600+${"V0_".$fname."_min"}*60+${"V0_".$fname."_sec"};
				if ($fields[$i] -> dtype =="datetimeinttext")
					$$fname=mktime(${"V0_".$fname."_hour"},${"V0_".$fname."_min"},${"V0_".$fname."_sec"},${"V0_".$fname."_month"},${"V0_".$fname."_day"},${"V0_".$fname."_year"});
			}
			if ($fields[$i] -> dtype == "timeint") {
				if (${"V0_".$fname."_hour"}!="") {
					if (trim(${"V0_".$fname."_min"})=="") ${"V0_".$fname."_min"}=0;
					if (trim(${"V0_".$fname."_sec"})=="") ${"V0_".$fname."_sec"}=0;
				}
				$$fname=${"V0_".$fname."_hour"}*3600+${"V0_".$fname."_min"}*60+${"V0_".$fname."_sec"};
			}
			if ($fields[$i] -> dtype == "time") {
				if (${"V0_".$fname."_hour"}!="") {
					if (trim(${"V0_".$fname."_min"})=="") ${"V0_".$fname."_min"}=0;
					if (trim(${"V0_".$fname."_sec"})=="") ${"V0_".$fname."_sec"}=0;
				}
				$$fname=${"V0_".$fname."_hour"}.":".${"V0_".$fname."_min"}.":".${"V0_".$fname."_sec"};
			}
			if ($fields[$i] -> dtype == "timeora") {
				if (${"V0_".$fname."_hour"}!="") {
					if (trim(${"V0_".$fname."_min"})=="") ${"V0_".$fname."_min"}=0;
					if (trim(${"V0_".$fname."_sec"})=="") ${"V0_".$fname."_sec"}=0;
				}
				$$fname=date("Y-m-d")." ".${"V0_".$fname."_hour"}.":".${"V0_".$fname."_min"}.":".${"V0_".$fname."_sec"};
			}
			if ($fields[$i] -> type == "md5") {
				$$fname=md5(${"V0_".$fname});
			}
			if ($fields[$i] -> type == "base64") {
				$$fname=base64_encode(${"V0_".$fname});
			}
			if ($fields[$i] -> dtype == "plistm" || $fields[$i] -> dtype == "plistdbm" || $fields[$i] -> dtype == "plistdbmtree" || $fields[$i] -> dtype == "checkboxm" || $fields[$i] -> dtype == "checkboxdbm") {
                                if ($fields[$i] -> dtype == "checkboxm") {
                                    $$fname=array_values($$fname);
                                }
				$tmp="";
				if (is_array($$fname)) {
					$TMP_numval=0;
					foreach($_REQUEST as $TMP_k=>$TMP_v) {
						if (is_array($TMP_v)) foreach($TMP_v as $TMP_k2=>$TMP_v2) if ($TMP_k=="V0_".$fname && $TMP_k2>$TMP_numval) $TMP_numval=$TMP_k2;
					}
					if ($TMP_numval<count($$fname)) $TMP_numval=count($$fname);
					$TMP_numval++;
					for($ki=0; $ki<$TMP_numval; $ki++) { $tmp.=${$fname}[$ki]; }
					$$fname=$tmp;
				}
			}
			if ($fields[$i] -> dtype == "checkbox") {
				$arr = split(":", $fields[$i] -> extra);
				if ($arr[0]=="") $arr[0]="0";
				if ($arr[1]=="") $arr[1]="1";
				if ($$fname == $arr[1]) $$fname = $arr[1];
				else $$fname = $arr[0];
			}
			if ($fields[$i] -> type == "crypt") {
				$$fname=crypt(${"V0_".$fname});
			}
			if (substr($fields[$i]->type,0,3) == "num" || substr($fields[$i]->dtype,0,3) == "num") {
				//$$fname=RAD_str2num(${$fname});	// esto ya se hace en el RAD_common
			}
			if ($$fname == "" && $tname!="" && !$fields[$i] -> canbenull && $fields[$i] -> dtype != "checkbox") {
				$ok = false;
				$func = "error";
//				$RAD_errorstr .= $i.$fields[$i] -> name.$fields[$i] -> title."$tname "._DEF_NLSCantBeNull." <A ACCESSKEY='N' TITLE='ALT+N' HREF=\"javascript:window.history.back()\">"._DEF_NLSTryAgain."</A>";
				if (!$V_rowsinsert>0) $RAD_errorstr .= "$tname "._DEF_NLSCantBeNull." <A ACCESSKEY='N' TITLE='ALT+N' HREF=\"javascript:window.history.back()\">"._DEF_NLSTryAgain."</A> ";
			} else {
				if ($fields[$i]->type == "function") {
    				} elseif ($fields[$i]->dtype=="image" || $fields[$i]->dtype=="file") {
					if (${"V_ACT_V0_".$fname} == "") ${"V_ACT_V0_".$fname}="leave";
					if ($fieldnames != "") $fieldnames .= ",";
					$fieldnames .= "$fname";
					$current_date = getdate();
					$FechaHoraSis=$current_date["year"]."-".$current_date["mon"]."-".$current_date["mday"]."-".$current_date["hours"]."-".$current_date["minutes"]."-".$current_date["seconds"];
					$TMP_user=".".base64_decode($HTTP_SESSION_VARS["SESSION_user"]);
					if (${"V0_".$fname."_name"}!="") {
						$nameFich=$FechaHoraSis.$TMP_user.".".${"V0_".$fname."_name"};
						$nameFich=str_replace(" ", "_", $nameFich);
						if (file_exists($nameFich)) {
							echo $NLSError." ".$nameFich.".";
							exit;
						} else {
							${"V0_".$fname."_name"}=RAD_nameSecure(${"V0_".$fname."_name"});
							list($TMP_fich,$nameFich)=RAD_nameDownload(${"V0_".$fname."_name"});
							if (!copy(${"V0_".$fname},$TMP_fich)) {
								RAD_logError("ERR: INSERT (copy) "._DEF_NLSError." ".$nameFich.".");
								echo $NLSError." ".$TMP_fich.".";
								exit;
							}
							chmod($TMP_fich,0644);
							$TMP_cont=1;
                                                        while(${"V0_".$fname.$TMP_cont."_name"}!="" && ${"V0_".$fname.$TMP_cont."_name"}!="none") {
                                                            ${"V0_".$fname.$TMP_cont."_name"}=RAD_nameSecure(${"V0_".$fname.$TMP_cont."_name"});
                                                            list($TMP_fich,$nameFich2)=RAD_nameDownload(${"V0_".$fname.$TMP_cont."_name"});
                                                            if (!copy(${"V0_".$fname.$TMP_cont},$TMP_fich)) {
                                                                RAD_logError("ERR: INSERT (copy) "._DEF_NLSError." ".$nameFich2.".");
                                                                echo $NLSError." ".$TMP_fich.".";
                                                                exit;
                                                            }
                                                            $nameFich.="\n".$nameFich2;
                                                            chmod($TMP_fich,0644);
                                                            $TMP_cont++;
                                                            if ($TMP_cont>100) break;
                                                        }
						}
						if (${"V_ACT_V0_".$fname} == "leave") {
							if ($fieldvalues != "") $fieldvalues .= ",";
							if (${"V_A_V0_".$fname}.$nameFich!="" && ${"V_A_V0_".$fname}.$nameFich!="\n") $fieldvalues .= tosql(${"V_A_V0_".$fname}.$nameFich."\n");
							else $fieldvalues .= tosql("");
						}
						if (${"V_ACT_V0_".$fname} == "write") {
							if ($fieldvalues != "") $fieldvalues .= ",";
							if ($nameFich!="" && $nameFich!="\n") $fieldvalues .= tosql($nameFich."\n");
							else $fieldvalues .= tosql("");
							$files = explode("\n", ${"V_A_V0_".$fname});
							if (count($files) >1) {
								for ($k = 0; $k < count($files); $k++) {
									$files[$k]=str_replace("\n", "", $files[$k]);
									$files[$k]=str_replace("\r", "", $files[$k]);
									if ($files[$k]!="") RAD_unlink($files[$k]);
								}
							} else {
								${"V_A_V0_".$fname}=str_replace("\n", "", ${"V_A_V0_".$fname});
								${"V_A_V0_".$fname}=str_replace("\r", "", ${"V_A_V0_".$fname});
								if (${"V_A_V0_".$fname}!="") RAD_unlink(${"V_A_V0_".$fname});
							}
						}
					} else {
						if (${"V_ACT_V0_".$fname} == "leave") {
							if ($fieldvalues != "") $fieldvalues .= ",";
							if (${"V_A_VO_".$fname}!="" && ${"V_A_VO_".$fname}!="\n") $fieldvalues .= tosql(${"V_A_VO_".$fname});
							else $fieldvalues .= tosql(${"V_A_VO_".$fname});
						}
					}
					if (${"V_ACT_V0_".$fname} == "clear") {
						if ($fieldvalues != "") $fieldvalues .= ",";
						$fieldvalues .= "''";
						$files = explode("\n", ${"V_A_V0_".$fname});
						if (count($files) >1) {
							for ($k = 0; $k < count($files); $k++) {
								$files[$k]=str_replace("\n", "", $files[$k]);
								$files[$k]=str_replace("\r", "", $files[$k]);
								if ($files[$k]!="") RAD_unlink($files[$k]);
							}
						} else {
							${"V_A_V0_".$fname}=str_replace("\n", "", ${"V_A_V0_".$fname});
							${"V_A_V0_".$fname}=str_replace("\r", "", ${"V_A_V0_".$fname});
							if (${"V_A_V0_".$fname}!="") RAD_unlink(${"V_A_V0_".$fname});
						}
					}
				} else {
					if ($fname!="") {
						if ($fieldnames != "") $fieldnames .= ",";
						if ($fieldvalues != "") $fieldvalues .= ",";
						$fieldnames .= "$fname";
						//if (ereg("db",$fields[$i]->dtype) && ($$fname=='0')) $fieldvalues .= "''";
						if ($$fname == "") $fieldvalues .= "''";
						else $fieldvalues .= tosql($$fname);
					}
				}
			}
		}
	}
	if ($ok) {
		$cmdSQL="INSERT INTO $tablename ($fieldnames) VALUES ($fieldvalues)";
		$TMP_tablename=substr($V_tablename,strlen(_DEF_TABLE_PREFIX));
		if (file_exists("modules/$V_dir/".$V_mod.".preinsert.php")) {
			$TMP="";
			$TMP=include ("modules/$V_dir/".$V_mod.".preinsert.php");
			if ($TMP!==true && $TMP!="1") echo $TMP;
		}
		if (file_exists("modules/$V_dir/".$V_mod.".presql.php")) {
			$TMP="";
			$TMP=include ("modules/$V_dir/".$V_mod.".presql.php");
			if ($TMP!==true && $TMP!="1") echo $TMP;
		}
		if ($V_mod!=$V_tablename) {
			if (file_exists("modules/$V_dir/".$V_tablename.".preinsert.php")) {
			    $TMP="";
			    $TMP=include ("modules/$V_dir/".$V_tablename.".preinsert.php");
			    if ($TMP!==true && $TMP!="1") echo $TMP;
			}
			if (file_exists("modules/$V_dir/".$V_tablename.".presql.php")) {
			    $TMP="";
			    $TMP=include ("modules/$V_dir/".$V_tablename.".presql.php");
			    if ($TMP!==true && $TMP!="1") echo $TMP;
			}
		}
		if ($TMP_tablename!=$V_tablename) {
			if (file_exists("modules/$V_dir/".$TMP_tablename.".preinsert.php")) {
				$TMP="";
				$TMP=include ("modules/$V_dir/".$TMP_tablename.".preinsert.php");
				if ($TMP!==true && $TMP!="1") echo $TMP;
			}
			if (file_exists("modules/$V_dir/".$TMP_tablename.".presql.php")) {
				$TMP="";
				$TMP=include ("modules/$V_dir/".$TMP_tablename.".presql.php");
				if ($TMP!==true && $TMP!="1") echo $TMP;
			}
		}
		$TMP_result=sql_query($cmdSQL, $RAD_dbi) ;
		if ($TMP_result) {
		    $ki=0;
		    $par0=sql_insert_id($RAD_dbi);
		    $TMP_loginsertid="";
		    for ($i = 0; $i < $numf; $i++) {
			if (!isset(${"idname$i"})) ${"idname$i"} = "";
			if (${"idname$i"} != "") {
//			    if ($fields[$findex[${"idname$i"}]]->dtype=="auto_increment") ${"par".$ki}=sql_insert_id($RAD_dbi);
//			    else ${"par".$ki}=${${"idname$i"}};
  			    if ($fields[$findex[${"idname$i"}]]->dtype=="auto_increment") {
				$TMP_parx=0;
  				if ($parx_value_uniqid!="" && $parx_fname_uniqid!="") $TMP_parx=RAD_lookup($tablename,$fields[$findex[${"idname$i"}]]->name,$parx_fname_uniqid,$parx_value_uniqid);
  				if ($TMP_parx>0) ${"par".$ki}=$TMP_parx;
  				else ${"par".$ki}=sql_insert_id($RAD_dbi);
  			    } else ${"par".$ki}=${${"idname$i"}};
			    $pars .= "&par".$ki."=".urldecode(${"par$ki"});
			    $TMP_loginsertid.="## ".$fields[$findex[${"idname$i"}]]->name."=".${"par$ki"}."\n";
			    $ki++;
			}
		    }
		    if ($TMP_loginsertid!="") RAD_printLog($TMP_loginsertid);
	 	    $TMP_tablename=substr($V_tablename,strlen(_DEF_TABLE_PREFIX));
		    if (file_exists("modules/$V_dir/".$V_mod.".postinsert.php")) {
			$TMP="";
			$TMP=include ("modules/$V_dir/".$V_mod.".postinsert.php");
			if ($TMP!==true && $TMP!="1") echo $TMP;
		    }
		    if (file_exists("modules/$V_dir/".$V_mod.".postsql.php")) {
			$TMP="";
			$TMP=include ("modules/$V_dir/".$V_mod.".postsql.php");
			if ($TMP!==true && $TMP!="1") echo $TMP;
		    }
		    if ($V_mod!=$V_tablename) {
			if (file_exists("modules/$V_dir/".$V_tablename.".postinsert.php")) {
				$TMP="";
				$TMP=include ("modules/$V_dir/".$V_tablename.".postinsert.php");
				if ($TMP!==true && $TMP!="1") echo $TMP;
			}
			if (file_exists("modules/$V_dir/".$V_tablename.".postsql.php")) {
				$TMP="";
				$TMP=include ("modules/$V_dir/".$V_tablename.".postsql.php");
				if ($TMP!==true && $TMP!="1") echo $TMP;
			}
		    }
		    if ($TMP_tablename!=$V_tablename && $TMP_tablename!=$V_mod) {
			if (file_exists("modules/$V_dir/".$TMP_tablename.".postinsert.php")) {
				$TMP="";
				$TMP=include ("modules/$V_dir/".$TMP_tablename.".postinsert.php");
				if ($TMP!==true && $TMP!="1") echo $TMP;
			}
			if (file_exists("modules/$V_dir/".$TMP_tablename.".postsql.php")) {
				$TMP="";
				$TMP=include ("modules/$V_dir/".$TMP_tablename.".postsql.php");
				if ($TMP!==true && $TMP!="1") echo $TMP;
			}
		    }
			$subfunc_RAD_subbrowse=false;
			for ($i = 0; $i < $numf; $i++) {
				if ($subfunc!="parentreload" && substr($fields[$i]->extra,0,13)=="RAD_subbrowse" && $fields[$i]->nodetail!=true) {
					$subfunc_RAD_subbrowse=true;
					break;
				}
			}
			if (($subfunc=="browse"||$subfunc=="parentreload") && $subfunc_RAD_subbrowse==false) {
				echo "<html><body><script type='text/javascript'>\n";
//				echo "window.opener.location.reload();\n";
			   if (_DEF_POPUP_MARGIN=="SUBMODAL") {
				echo "parent.location=parent.location;\n";
			   } else {
				echo "if (window.opener) {\n var urlOpener=window.opener.location.href;\n if (urlOpener.indexOf('#')>0) { urlOpener=urlOpener.substr(0,urlOpener.indexOf('#')); }\n window.opener.location.href=urlOpener;\n window.close(); }\n";
				echo "else { top.location.href=top.location.href; top.RAD_hideL(); }\n";
			   }
			   echo "\n</script></body></html>\n";
			   die;
			} else if ($subfunc=="browse"||$subfunc=="parentreload") {
			   echo "\n<script type='text/javascript'>\n";
			   if (_DEF_POPUP_MARGIN=="SUBMODAL") {
				echo "parent.setThreshold('window.location=window.location;');";
			   } else {
				echo "var urlOpener=window.opener.location.href;\n";
				echo "if (urlOpener.indexOf('#')>0) { urlOpener=urlOpener.substr(0,urlOpener.indexOf('#')); }\n";
				echo "window.opener.location.href=urlOpener;\n";
			   }
			   echo "</script>\n";
			}
			if ($RAD_errorstr != _DEF_NLSRecordInserted) $RAD_errorstr .= _DEF_NLSRecordInserted."";
			for ($i = 0; $i < $numf; $i++) {
			    if (substr($fields[$i]->extra,0,13)=="RAD_subbrowse" && !$VI_count>0) {
				    $defaultfunc="detail";
				    echo "<script>\n";
				    if ($RAD_errorstr != _DEF_NLSRecordInserted) {
					$RAD_errorstr=str_replace("'","/'",$RAD_errorstr);
					echo "alert('".$RAD_errorstr."');\n";
				    } else {
					$RAD_errorstr=str_replace("'","/'",$RAD_errorstr);
					echo "window.status='".$RAD_errorstr."';\n";
				    }
				    $TMP_URLROI=str_replace("&menuoff=x","",$URLROI);
				    echo "\ndocument.location.href=document.location.href+'?&V_dir=$V_dir&V_mod=$V_mod&func=detail&V_prevfunc=insert&".$pars.$TMP_URLROI."&".uniqid()."';\n</script>\n";
				    die;
			    }
			}
			$func=$defaultfunc;
			//if ($RAD_browsetype!="insert" && !($VI_count>0)) {
			if (!$V_rowsinsert>0 && !($VI_count>0)) {
			    echo "\n<script>\ndocument.location.href=document.location.href+'?V_dir=$V_dir&V_mod=$V_mod&func=".$func."&V_prevfunc=insert&".$pars.$URLROI."&".uniqid()."';\n</script>\n";
			    die;
			}
		} else {
			$func = "error";
			if ($cmdSQL!="") $RAD_errorstr .= " <pre>".$cmdSQL." [".sql_errorno($TMP_result)."] ".sql_error($TMP_result)."</pre>";
			//if ($cmdSQL!="") $RAD_errorstr .= " <! ".$cmdSQL." [".sql_errorno($TMP_result)."] ".sql_error($TMP_result)." >";
		}
	}
}
?>
