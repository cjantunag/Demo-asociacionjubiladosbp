<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}
if ($subfunc == "browsedit" && !$V_numrows>0) $subfunc="";

if ($func=="update" && $menuedit == false) {
    $func="error";
}

//---------------------------------------------------------------------------
//------------------------- Insert news registers from browse-insert
//---------------------------------------------------------------------------
//if ($func=="update" && $RAD_browsetype=="insert" && $VI_count>0) {
//if ($func=="update" && $V_rowsinsert>0 && $VI_count>0) {
if ($func=="update" && $VI_count>0) {
   $primero=true;
   for ($iv = 0; $iv < $VI_count; $iv++) {
	if (${"VIC".$iv."_changed"}!="") $changed=true;
	else $changed=false;
	for ($ivk = 0; $ivk < $numf; $ivk++) {
		$fname = $fields[$ivk] -> name;
		if($primero==true) {
			for ($ivj = 0; $ivj < $numf; $ivj++) {
    				if (${"idname$ivj"} != "") {
				    ${"XV0_par".$ivj}=${"V0_par".$ivj};
				}
			}
			${"XV0_".$fname}=${"V0_".$fname};
			${"XV0_".$fname."_date"}=${"V0_".$fname."_date"};
			${"XV0_".$fname."_time"}=${"V0_".$fname."_time"};
			${"XV0_".$fname."_year"}=${"V0_".$fname."_year"};
			${"XV0_".$fname."_month"}=${"V0_".$fname."_month"};
			${"XV0_".$fname."_day"}=${"V0_".$fname."_day"};
			${"XV0_".$fname."_hour"}=${"V0_".$fname."_hour"};
			${"XV0_".$fname."_min"}=${"V0_".$fname."_min"};
			${"XV0_".$fname."_sec"}=${"V0_".$fname."_sec"};
			${"XV0_".$fname."_name"}=${"V0_".$fname."_name"};
			${"XV_ACT_V0_".$fname}=${"V_ACT_V0_".$fname};
			${"XV0_".$fname."_cont"}=${"V0_".$fname."_cont"};
			if (${"V0_".$fname."_cont"}>0) {
			    for ($ki=1; $ki<=${"V0_".$fname."_cont"}; $ki++) {
				${"XV0_".$fname."_del".$ki}=${"V0_".$fname."_del".$ki};
			    }
			}
			${"XV_A_V0_".$fname}=${"V_A_V0_".$fname};
			$primero=false;
		}
		${"V0_".$fname}=${"VI".$iv."_".$fname};
		${"V0_".$fname."_date"}=${"VI".$iv."_".$fname."_date"};
		${"V0_".$fname."_time"}=${"VI".$iv."_".$fname."_time"};
		${"V0_".$fname."_year"}=${"VI".$iv."_".$fname."_year"};
		${"V0_".$fname."_month"}=${"VI".$iv."_".$fname."_month"};
		${"V0_".$fname."_day"}=${"VI".$iv."_".$fname."_day"};
		${"V0_".$fname."_hour"}=${"VI".$iv."_".$fname."_hour"};
		${"V0_".$fname."_min"}=${"VI".$iv."_".$fname."_min"};
		${"V0_".$fname."_sec"}=${"VI".$iv."_".$fname."_sec"};
		${"V0_".$fname."_name"}=${"VI".$iv."_".$fname."_name"};
		${"V_ACT_V0_".$fname}=${"V_ACT_VI".$iv."_".$fname};
		${"V0_".$fname."_cont"}=${"VI".$iv."_".$fname."_cont"};
		if (${"V0_".$fname."_cont"}>0) {
		    for ($ki=1; $ki<=${"V0_".$fname."_cont"}; $ki++) {
			${"V0_".$fname."_del".$ki}=${"VI".$iv."_".$fname."_del".$ki};
		    }
		}
		${"V_A_V0_".$fname}=${"V_A_VI".$iv."_".$fname};
		if ($fields[$findex[$fname]]->browsedit==true && $fields[$findex[$fname]]->readonly==false && $fields[$findex[$fname]]->dtype!="function" && ${"V0_".$fname}!="") $changed=true;
	}
	if ($changed==true) {
		$TMP_subfunc=$subfunc;
		$subfunc="";
		$func="insert";
		include("modules/phpRAD/RAD_insert.php");
		if ($func=="error") {
		    RAD_logError("ERR: BROWSEDIT INSERT ".$RAD_errorstr);
		    echo $RAD_errorstr."<br>";
		    $TMP_arr=explode("<",$RAD_errorstr);
		    alert($TMP_arr[0]);
		}
		$RAD_errorstr="";
		$subfunc=$TMP_subfunc;
	}
  }
  if($primero==false) {
	for ($ivj = 0; $ivj < $numf; $ivj++) {
		if (${"idname$ivj"} != "") {
		    ${"V0_par".$ivj}=${"XV0_par".$ivj};
		}
	}
	${"V0_".$fname}=${"XV0_".$fname};
	${"V0_".$fname."_date"}=${"XV0_".$fname."_date"};
	${"V0_".$fname."_time"}=${"XV0_".$fname."_time"};
	${"V0_".$fname."_year"}=${"XV0_".$fname."_year"};
	${"V0_".$fname."_month"}=${"XV0_".$fname."_month"};
	${"V0_".$fname."_day"}=${"XV0_".$fname."_day"};
	${"V0_".$fname."_hour"}=${"XV0_".$fname."_hour"};
	${"V0_".$fname."_min"}=${"XV0_".$fname."_min"};
	${"V0_".$fname."_sec"}=${"XV0_".$fname."_sec"};
	${"V0_".$fname."_name"}=${"XV0_".$fname."_name"};
	${"V_ACT_V0_".$fname}=${"XV_ACT_V0_".$fname};
	${"V0_".$fname."_cont"}=${"XV0_".$fname."_cont"};
	if (${"V0_".$fname."_cont"}>0) {
	    for ($ki=1; $ki<=${"V0_".$fname."_cont"}; $ki++) {
		${"V0_".$fname."_del".$ki}=${"XV0_".$fname."_del".$ki};
	    }
	}
	${"V_A_V0_".$fname}=${"XV_A_V0_".$fname};
  }
  $func="update";

}

$cmdSQL="";
//---------------------------------------------------------------------------
//------------------------- Update
// don't update if nothing change
//---------------------------------------------------------------------------
if ($func == "update") {
//	if ($subfunc == "browsedit") $numrows=$found;
	if ($subfunc == "browsedit") $numrows=$V_numrows;
	else $numrows=1;
	for ($numrow = 0; $numrow < $numrows; $numrow++) {
	    if ($subfunc=="browsedit" && $numrow==0) continue;
	    if (${"V".$numrow."_delete"}!="") continue;
	    // pasa al primer registro el registro numrow
	    for ($ivj = 0; $ivj < $numf; $ivj++) {
		if (${"idname$ivj"} != "") {
		    ${"V0_par".$ivj}=${"V".$numrow."_par".$ivj};
		}
	    }
	    for ($i = 0; $i < $numf; $i++) {
		$fname=$fields[$i]->name;
		${"V0_".$fname}=${"V".$numrow."_".$fname};
	        ${"V0_".$fname."_date"}=${"V".$numrow."_".$fname."_date"};
	        ${"V0_".$fname."_time"}=${"V".$numrow."_".$fname."_time"};
	        ${"V0_".$fname."_year"}=${"V".$numrow."_".$fname."_year"};
		${"V0_".$fname."_month"}=${"V".$numrow."_".$fname."_month"};
	        ${"V0_".$fname."_day"}=${"V".$numrow."_".$fname."_day"};
		${"V0_".$fname."_hour"}=${"V".$numrow."_".$fname."_hour"};
	        ${"V0_".$fname."_min"}=${"V".$numrow."_".$fname."_min"};
		${"V0_".$fname."_sec"}=${"V".$numrow."_".$fname."_sec"};
	        ${"V0_".$fname."_name"}=${"V".$numrow."_".$fname."_name"};
		${"V_ACT_V0_".$fname}=${"V_ACT_V".$numrow."_".$fname};
		${"V0_".$fname."_cont"}=${"V".$numrow."_".$fname."_cont"};
		if (!$fields[$i]->canbenull&&substr($fields[$i]->dtype,0,4)=="date"&&!${"V0_".$fname."_year"}>0) ${"V0_".$fname."_year"}=date("Y");
		if (${"V0_".$fname."_cont"}>0) {
    		    for ($ki=1; $ki<=${"V0_".$fname."_cont"}; $ki++) {
		        ${"V0_".$fname."_del".$ki}=${"V".$numrow."_".$fname."_del".$ki};
		    }
	        }
		${"V_A_V0_".$fname}=${"V_A_V".$numrow."_".$fname};
	    }
	    // pasa al primer registro el registro numrow
	    // FALTA: a partir de aqui ya no se deberia utilizar $Vnumrow_fname ni $fname solo $V0_fname igual que en
	    // RAD_insert. Habria que revisar funciones que utilicen el $numrow

		$idnames = "";
		for ($i = 0; $i < $numf; $i++) {
    			if (${"idname$i"} != "") {
			    if ($idnames == "") $idnames = "${"idname$i"} = '".urldecode(${"V0_par".$i}) . "'";
			    else $idnames .= " AND ${"idname$i"} = '".urldecode(${"V0_par".$i}) . "'";
			}
		}
		$changed=false;
		if (${"V0_par0"}!="" || ${par0}!="") {
			//if (${"V0_par0"}!="") $par0=${"V0_par0"};
			$cmdSQL="SELECT * FROM $tablename WHERE $idnames";
			$TMP_result=sql_query($cmdSQL, $RAD_dbi);
			if ($TMP_result) {
				if($TMP_row=sql_fetch_array($TMP_result, $RAD_dbi)) {
					for ($i = 0; $i < $numf; $i++) {
						${"VDB_".$fields[$i]->name}=$TMP_row[$fields[$i]->name];
					}
				}
			}
			for ($i = 0; $i < $numf; $i++) {
				${"par".$i}=${"V0_par".$i};
				$TMP_bredit=getSessionVar("SESSION_bredit_".$V_mod.$fields[$i]->name);
				if ($TMP_bredit=="1") $fields[$i]->browsedit=true;
				if ($subfunc == "browsedit" && $fields[$i]->browsedit==false) continue;
				if ($fields[$i]->readonly==true || $fields[$i]->noupdate==true) continue;
				$fname=$fields[$i]->name;
				global ${$fname}, ${"V0_".$fname};
				if ($fields[$i]->dtype!="plistm" && $fields[$i]->dtype!="plistdbm" && $fields[$i]->dtype!="plistdbmtree" && $fields[$i]->dtype!="checkboxm" && $fields[$i]->dtype!="checkboxdbm") ${$fname}=${"V0_".$fname}.""; 
				else ${$fname}=${"V0_".$fname};

				${$fname."_name"}=${"V0_".$fname."_name"};
				${"V_ACT_".$fname}=${"V_ACT_V0_".$fname};
				${"V_A_".$fname}=${"V_A_V0_".$fname};
				${"V0_".$fname."_date"}=trim(${"V0_".$fname."_date"});
				${"V0_".$fname."_time"}=trim(${"V0_".$fname."_time"});
				${"V0_".$fname."_year"}=trim(${"V0_".$fname."_year"});
				${"V0_".$fname."_month"}=trim(${"V0_".$fname."_month"});
				${"V0_".$fname."_day"}=trim(${"V0_".$fname."_day"});
				${"V0_".$fname."_hour"}=trim(${"V0_".$fname."_hour"});
				${"V0_".$fname."_min"}=trim(${"V0_".$fname."_min"});
				${"V0_".$fname."_sec"}=trim(${"V0_".$fname."_sec"});
				if ($fields[$i] -> dtype == "file" || $fields[$i] -> dtype == "image") {
					for ($ksi=1;$ksi<=${"V0_".$fname."_cont"};$ksi++) {
						if (${"V0_".$fname."_del".$ksi}!='') $changed=true;
					}
				}
				if ($fields[$i] -> dtype == "date" || $fields[$i] -> dtype == "dateint") {
					if (!isset(${"V0_".$fname."_year"})) continue;
					${$fname."_year"}=${"V0_".$fname."_year"};
					${$fname."_month"}=${"V0_".$fname."_month"};
					${$fname."_day"}=${"V0_".$fname."_day"};
					$changed=true;
				}
				if ($fields[$i] -> dtype == "datetime" || $fields[$i] -> dtype == "datetimeint") {
					if (!isset(${"V0_".$fname."_year"})) continue;
					${$fname."_year"}=${"V0_".$fname."_year"};
					${$fname."_month"}=${"V0_".$fname."_month"};
					${$fname."_day"}=${"V0_".$fname."_day"};
					${$fname."_hour"}=${"V0_".$fname."_hour"};
					${$fname."_min"}=${"V0_".$fname."_min"};
					${$fname."_sec"}=${"V0_".$fname."_sec"};
					$changed=true;
				}
				if ($fields[$i] -> dtype == "timeint") {
					if (!isset(${"V0_".$fname."_hour"})) continue;
					${$fname."_hour"}=${"V0_".$fname."_hour"};
					${$fname."_min"}=${"V0_".$fname."_min"};
					${$fname."_sec"}=${"V0_".$fname."_sec"};
					$changed=true;
				}
				if ($fields[$i] -> dtype == "time" || $fields[$i] -> dtype == "timeora") {
					if (!isset(${"V0_".$fname."_hour"})) continue;
//					if (!isset(${$fname."_hour"})) continue;
					${$fname."_hour"}=${"V0_".$fname."_hour"};
					${$fname."_min"}=${"V0_".$fname."_min"};
					${$fname."_sec"}=${"V0_".$fname."_sec"};
					$changed=true;
				}
				if ($fields[$i] -> dtype == "datetimeinttext" || $fields[$i] -> dtype == "dateinttext" || $fields[$i] -> dtype == "datetimetext" || $fields[$i] -> dtype == "datetext") {
					if (${"V0_".$fname."_date"}=="" && ${"V0_".$fname}!="") ${"V0_".$fname."_date"}=substr(${"V0_".$fname},8,2).substr(${"V0_".$fname},5,2).substr(${"V0_".$fname},0,4);
					${"V0_".$fname."_date"}=trim(${"V0_".$fname."_date"});
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
                                        $changed=true;
				}
				if ($fields[$i] -> dtype == "timetext" || $fields[$i] -> dtype == "datetimetext" || $fields[$i] -> dtype == "datetimeinttext" || $fields[$i] -> dtype == "timeinttex") {
					if (${"V0_".$fname."_time"}=="" && ${"V0_".$fname}!="") {
						$TMP_time="";
						$TMP_elems=explode(" ",${"V0_".$fname});
						if (count($TMP_elems)>1) $TMP_time=$TMP_elems[1];
						${"V0_".$fname."_time"}=substr($TMP_time,0,2).substr($TMP_time,3,2).substr($TMP_time,6,2);
					}
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
                                        $changed=true;
				}
				if ($fields[$i]->type == "function") $changed=true;
				if ($fields[$i]->dtype == "checkbox") $changed=true;
				if ($fields[$i]->dtype == "checkboxm" || $fields[$i]->dtype == "checkboxdbm") $changed=true;
				if ($fields[$i]->dtype == "plistm" || $fields[$i]->dtype == "plistdbm") $changed=true;
				if ((${"VDB_".$fname} != ${"V0_".$fname}) && (isset(${"V0_".$fname}))) {
					$changed=true;
					$func="update";
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
					}
					if ($fields[$i]->type == "function" && $fields[$i]->dtype == "geturl") {
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
					}
				}
				${"V0_".$fname}=${$fname};
			}
		}
		if ($changed) {
			$func="update";
//---------------------------------------------------------------------------
//*** Update One
//---------------------------------------------------------------------------
if ($func == "update") {
	if (function_exists("shmop_open")) {
		$TMP_user = base64_decode($HTTP_SESSION_VARS["SESSION_user"]);

		$TMP_shm_key=$dbname.".".$tablename.".".$par0;
		if ($par1!="") $TMP_shm_key.=".".$par1;
		if ($par2!="") $TMP_shm_key.=".".$par2;
		$TMP_shm_key=str_replace(",",".",$TMP_shm_key);
		$TMP_shm_key="/tmp/".$TMP_shm_key;
		if (!file_exists($TMP_shm_key)) {
			if($fp=@fopen($TMP_shm_key,"w")) fclose($fp);
			clearstatcache();
		}
		$TMP_shm_key=ftok($TMP_shm_key, 't');
		$TMP_shm_id=@shmop_open($TMP_shm_key, "w", 0644, 100);
		if ($TMP_shm_id) {
			$TMP_time2=0;
			$TMP_shm_dataread = shmop_read($TMP_shm_id, 0, 100);
			list($TMP_PHPSESSID2,$TMP_user2,$TMP_time2)=explode(".",$TMP_shm_dataread);
			if ($TMP_time2>0) if ($TMP_PHPSESSID2==$PHPSESSID && $TMP_user2==$TMP_user) shmop_delete($TMP_shm_id);
			else shmop_delete($TMP_shm_id);
		}
	}

	$ok = true;
	$fieldvalues = "";
	$sanames = "";
	$savalues = "";
	$inifield=0; $lastfield=$numf-1;
	if ($fieldedit !="") { $inifield=$lastfield=$findex[$fieldedit]; }
	if (file_exists("modules/$V_dir/common.defaults.php")) {
		$TMP=include ("modules/$V_dir/common.defaults.php");
		if ($TMP!==true && $TMP!="1") echo $TMP;
		$TMP="";
	}
	if ($filename!="") {
		$TMP="";
		if (file_exists("modules/$V_dir/".$filename.".defaults.php")) {
		    $TMP=include ("modules/$V_dir/".$filename.".defaults.php");
		    if ($TMP!==true && $TMP!="1") echo $TMP;
		}
	} else {
		if (file_exists("modules/$V_dir/".$V_mod.".defaults.php")) {
		    $TMP=include ("modules/$V_dir/".$V_mod.".defaults.php");
		    if ($TMP!==true && $TMP!="1") echo $TMP;
		}
	}
	for ($i = $inifield; $i < $lastfield+1; $i++) {
		if ($subfunc == "browsedit" && $fields[$i]->browsedit==false) continue;
		if ($fields[$i]->readonly==true || $fields[$i]->noupdate==true) continue;
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
		} elseif ($fields[$i] -> dtype != "auto_increment") {
			$tname = $fields[$i] -> title;
			$fname = $fields[$i] -> name;
// FALTA: esto es lo que se deberia usar siempre el V0_fname en vez del fname
//			${$fname}=${"V0_".$fname};
			if ($fields[$i] -> dtype == "date") {
				if (!isset(${$fname."_year"}) && !isset(${$fname})) continue;
				if (${$fname}=="") {
				  $$fname=${$fname."_year"}."-".${$fname."_month"}."-".${$fname."_day"};
                                }
			}
			if ($fields[$i] -> dtype == "datetime") {
				if (!isset(${$fname."_year"}) && !isset(${$fname})) continue;
				if (${$fname}=="") {
				  $$fname=${$fname."_year"}."-".${$fname."_month"}."-".${$fname."_day"};
				  if (${$fname."_hour"}!="") {
					if (trim(${$fname."_min"})=="") ${$fname."_min"}=0;
					if (trim(${$fname."_sec"})=="") ${$fname."_sec"}=0;
				  }
				  $$fname.=" ".${$fname."_hour"}.":".${$fname."_min"}.":".${$fname."_sec"};
				}
			}
			if ($fields[$i] -> dtype == "date" || $fields[$i] -> dtype == "datetime") {
				if (ereg("--",$$fname)||ereg("-00-",$$fname)||ereg("-00",$$fname)) $$fname="";
			}
			if ($fields[$i] -> dtype == "datetimeint") {
				if (!isset(${$fname."_year"}) && !isset(${$fname})) continue;
				if (${$fname}=="") {
				  $tmp=${$fname."_year"}."-".${$fname."_month"}."-".${$fname."_day"};
				  if (${$fname."_hour"}!="") {
					if (trim(${$fname."_min"})=="") ${$fname."_min"}=0;
					if (trim(${$fname."_sec"})=="") ${$fname."_sec"}=0;
				  }
				  $tmp.=" ".${$fname."_hour"}.":".${$fname."_min"}.":".${$fname."_sec"};
				  $$fname=mktime(${$fname."_hour"},${$fname."_min"},${$fname."_sec"},${$fname."_month"},${$fname."_day"},${$fname."_year"});
				}
			}
			if ($fields[$i] -> dtype == "dateint") {
				if (!isset(${$fname."_year"}) && !isset(${$fname})) continue;
				if (${$fname}=="") {
				  $tmp=${$fname."_year"}."-".${$fname."_month"}."-".${$fname."_day"};
				  $$fname=mktime("0","0","0",${$fname."_month"},${$fname."_day"},${$fname."_year"});
				}
			}
			if ($fields[$i] -> dtype == "timeint") {
				if (!isset(${$fname."_hour"}) && !isset(${$fname})) continue;
				if (${$fname}=="") {
				  if (${$fname."_hour"}!="") {
					if (trim(${$fname."_min"})=="") ${$fname."_min"}=0;
					if (trim(${$fname."_sec"})=="") ${$fname."_sec"}=0;
				  }
				  $$fname=${$fname."_hour"}*3600+${$fname."_min"}*60+${$fname."_sec"};
				}
			}
			if ($fields[$i] -> dtype == "time") {
				if (!isset(${$fname."_hour"}) && !isset(${$fname})) continue;
				if (${$fname}=="") {
				  if (${$fname."_hour"}!="") {
					if (trim(${$fname."_min"})=="") ${$fname."_min"}=0;
					if (trim(${$fname."_sec"})=="") ${$fname."_sec"}=0;
				  }
				  $$fname=${$fname."_hour"}.":".${$fname."_min"}.":".${$fname."_sec"};
				}
			}
			if ($fields[$i] -> dtype == "timeora") {
				if (!isset(${$fname."_hour"}) && !isset(${$fname})) continue;
				if (${$fname}=="") {
				  if (${$fname."_hour"}!="") {
					if (trim(${$fname."_min"})=="") ${$fname."_min"}=0;
					if (trim(${$fname."_sec"})=="") ${$fname."_sec"}=0;
				  }
				  $$fname=date("Y-m-d")." ".${$fname."_hour"}.":".${$fname."_min"}.":".${$fname."_sec"};
				}
			}
			if ($fields[$i] -> type == "md5") {
				if (${"VDB_".$fname} != $$fname) $$fname=md5(${$fname});
			}
			if ($fields[$i] -> type == "base64") {
				if (${"VDB_".$fname} != $$fname) $$fname=base64_encode(${$fname});
			}
			if ($fields[$i] -> type == "crypt") {
				if (${"VDB_".$fname} != $$fname) $$fname=crypt(${$fname});
			}
			if (substr($fields[$i]->type,0,3)=="num" || substr($fields[$i]->dtype,0,3)=="num") {
				//$$fname=RAD_str2num(${$fname});	// esto ya se hace en el RAD_common
			}
			if ($fields[$i] -> dtype == "plistm" || $fields[$i] -> dtype == "plistdbm" || $fields[$i] -> dtype == "plistdbmtree" || $fields[$i] -> dtype == "checkboxm" || $fields[$i] -> dtype == "checkboxdbm") {
                                if ($fields[$i] -> dtype == "checkboxm") {
                                    $$fname=array_values($$fname);
                                }
				if (is_array($$fname)) {
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
			}
			if ($$fname=="" && !$fields[$i]->canbenull && !$fields[$i]->readonly && $fields[$i]->name!="" && $fields[$i]->dtype!="checkbox") {
				$ok = false;
				$func = "error";
				$RAD_errorstr .= "$tname "._DEF_NLSCantBeNull." <A ACCESSKEY=\"N\" TITLE=\"ALT+N\" HREF=\"javascript:window.history.back()\">"._DEF_NLSTryAgain."</A>\n";
			} else {
				if ($fields[$i] -> dtype == "image" || $fields[$i] -> dtype == "file") {
					$current_date = getdate();
					$FechaHoraSis=$current_date["year"]."-".$current_date["mon"]."-".$current_date["mday"]."-".$current_date["hours"]."-".$current_date["minutes"]."-".$current_date["seconds"];
					$TMP_user=".".base64_decode($HTTP_SESSION_VARS["SESSION_user"]);
					if (${"V0_".$fname."_cont"}>0) {
					    for ($ki=1; $ki<=${"V0_".$fname."_cont"}; $ki++) {
						if (${"V0_".$fname."_del".$ki}!="") {
						    RAD_unlink(${"V0_".$fname."_del".$ki});
						    ${"V_A_".$fname}=str_replace(${"V0_".$fname."_del".$ki},"",${"V_A_".$fname});
						}
					    }
					}
					if (${$fname."_name"}!="") {
						$nameFich=$FechaHoraSis.$TMP_user.".".${"V0_".$fname."_name"};
						$nameFich=str_replace(" ", "_", $nameFich);
						if (file_exists($nameFich)) {
							RAD_logError("ERR: UPDATE "._DEF_NLSError." ".$nameFich.".");
							echo _DEF_NLSError." ".$nameFich.".";
							exit;
						} else {
							${"V0_".$fname."_name"}=RAD_nameSecure(${"V0_".$fname."_name"});
							list($TMP_fich,$nameFich)=RAD_nameDownload(${"V0_".$fname."_name"});
							if (!copy($$fname,$TMP_fich)) {
								RAD_logError("ERR: UPDATE (copy) "._DEF_NLSError." ".$nameFich.".");
								echo _DEF_NLSError." ".$TMP_fich.".\n<! ".$$fname." >\n";
								exit;
							}
							chmod($TMP_fich,0644);
							$TMP_cont=1;
                                                        while(${"V0_".$fname.$TMP_cont."_name"}!="" && ${"V0_".$fname.$TMP_cont."_name"}!="none") {
                                                            ${"V0_".$fname.$TMP_cont."_name"}=RAD_nameSecure(${"V0_".$fname.$TMP_cont."_name"});
                                                            list($TMP_fich,$nameFich2)=RAD_nameDownload(${"V0_".$fname.$TMP_cont."_name"});
                                                            if (!copy(${"V0_".$fname.$TMP_cont},$TMP_fich)) {
								RAD_logError("ERR: UPDATE (copy) "._DEF_NLSError." ".$nameFich2.".");
                                                                echo $NLSError." ".$TMP_fich.".\n<! ".${"V0_".$fname.$TMP_cont}." >\n";
                                                                exit;
                                                            }
                                                            $nameFich.="\n".$nameFich2;
                                                            chmod($TMP_fich,0644);
                                                            $TMP_cont++;
                                                            if ($TMP_cont>100) break;
                                                        }

						}
						if (${"V_ACT_".$fname} == "leave") {
							if ($fieldvalues != "") $fieldvalues .= ",";
							if (${"V_A_".$fname}!="") $fieldvalues .= $fname."=".tosql(str_replace("\n\n","\n",str_replace("\r","",${"V_A_".$fname}."\n".$nameFich."\n")));
							else $fieldvalues .= $fname."=".tosql(str_replace("\n\n","\n",str_replace("\r","",$nameFich."\n")));
						}
						if (${"V_ACT_".$fname} == "write") {
							if ($fieldvalues != "") $fieldvalues .= ",";
							if ($nameFich!="") $fieldvalues .= $fname."=".tosql(str_replace("\n\n","\n",str_replace("\r","",$nameFich."\n")));
							else $fieldvalues .= $fname."=".tosql("");
							$files = explode("\n", ${"V_A_".$fname});
							if (count($files) >1) {
								for ($k = 0; $k < count($files); $k++) {
									$files[$k]=str_replace("\n", "", $files[$k]);
									$files[$k]=str_replace("\r", "", $files[$k]);
									if ($files[$k]!="") RAD_unlink($files[$k]);
								}
							} else {
								${"V_A_".$fname}=str_replace("\n", "", ${"V_A_".$fname});
								${"V_A_".$fname}=str_replace("\r", "", ${"V_A_".$fname});
								if (${"V_A_".$fname}!="") RAD_unlink(${"V_A_".$fname});
							}
						}
					} else {
						if (${"V_ACT_".$fname} == "leave") {
							if ($fieldvalues != "") $fieldvalues .= ",";
							if (${"V_A_".$fname}!="") $fieldvalues .= $fname."=".tosql(str_replace("\n\n","\n",str_replace("\r","",${"V_A_".$fname}."\n")));
							else $fieldvalues .= $fname."=".tosql("");
						}
					}
					if (${"V_ACT_".$fname} == "clear") {
						if ($fieldvalues != "") $fieldvalues .= ",";
						$fieldvalues .= $fname."=''";
						$files = explode("\n", ${"V_A_".$fname});
						if (count($files) >1) {
							for ($k = 0; $k < count($files); $k++) {
								$files[$k]=str_replace("\n", "", $files[$k]);
								$files[$k]=str_replace("\r", "", $files[$k]);
								if ($files[$k]!="") RAD_unlink($files[$k]);
							}
						} else {
							${"V_A_".$fname}=str_replace("\n", "", ${"V_A_".$fname});
							${"V_A_".$fname}=str_replace("\r", "", ${"V_A_".$fname});
							if (${"V_A_".$fname}!="") RAD_unlink(${"V_A_".$fname});
						}
					}
				} else {
// 					if (!$fields[$i]->readonly && $fname!="") {
                    			if ($fname!="" && ${"VDB_".$fname}!="" && ${"VDB_".$fname}==${"V0_".$fname} && $RAD_onlyupdchgfields!="") $fields[$i]->readonly=true;
                    			if (!$fields[$i]->readonly && $fname!="" && (isset(${"V0_".$fname}) || isset($$fname))) {
						if ($fieldvalues != "") $fieldvalues .= ",";
						if ($sanames != "") $sanames .= ",";
						if ($savalues != "") $savalues .= ",";
						if ($fields[$i] -> dtype == "checkbox") {
							$arr = split(":", $fields[$i] -> extra);
							if ($arr[0]=="") $arr[0]="0";
							if ($arr[1]=="") $arr[1]="1";
							if ($$fname == $arr[1]) $$fname = $arr[1];
							else $$fname = $arr[0];
						}
						if (substr($fields[$i] -> dtype,0,4) == "text" || $fields[$i] -> dtype == "stand" || $fields[$i] -> dtype == "standR" || $fields[$i] -> dtype == "standD") {
//							$$fname = str_replace("'", "\'", $$fname);
						}
						if ($$fname=="") {
							$fieldvalues .= $fname."=NULL";
							$savalues .= "NULL";
						} else {
							$fieldvalues .= $fname."=".tosql($$fname);
							//$savalues .= "'".$$fname."'";
							$savalues .= tosql($$fname);
						}
					}
//					$sanames .= $fname;
				}
			}
		}
	}
}

if ($ok) {
	$idnames = "";
	for ($i = 0; $i < $numf; $i++) {
		if (${"idname$i"} != "") {
			if ($idnames == "") $idnames = "${"idname$i"} = '".urldecode(${"V0_par".$i}) . "'";
			else $idnames .= " AND ${"idname$i"} = '".urldecode(${"V0_par".$i}) . "'";
		}
	}
	$idnames = urldecode("$idnames");
	if (${"Save"} == "Save New") $cmdSQL="INSERT into $tablename ($sanames) VALUES ($savalues)";
	else $cmdSQL="UPDATE $tablename SET $fieldvalues WHERE $idnames";
	if ($fieldvalues=="") {
	    $cmdSQL="";
	} else {
		$TMP_tablename=substr($V_tablename,strlen(_DEF_TABLE_PREFIX));
		if (file_exists("modules/$V_dir/".$V_mod.".preupdate.php")) {
			$TMP="";
			$RAD_numfield=$i;
			$TMP=include ("modules/$V_dir/".$V_mod.".preupdate.php");
			$i=$RAD_numfield;
			if ($TMP!==true && $TMP!="1") echo $TMP;
		}
		if (file_exists("modules/$V_dir/".$V_mod.".presql.php")) {
			$TMP=""; $RAD_numfield=$i;
			$TMP=include ("modules/$V_dir/".$V_mod.".presql.php");
			$i=$RAD_numfield;
			if ($TMP!==true && $TMP!="1") echo $TMP;
		}
		if ($V_mod!=$V_tablename) {
			if (file_exists("modules/$V_dir/".$V_tablename.".preupdate.php")) {
			    $TMP="";
			    $TMP=include ("modules/$V_dir/".$V_tablename.".preupdate.php");
			    if ($TMP!==true && $TMP!="1") echo $TMP;
			}
			if (file_exists("modules/$V_dir/".$V_tablename.".presql.php")) {
			    $TMP="";
			    $TMP=include ("modules/$V_dir/".$V_tablename.".presql.php");
			    if ($TMP!==true && $TMP!="1") echo $TMP;
			}
		}
		if ($TMP_tablename!=$V_tablename) {
			if (file_exists("modules/$V_dir/".$TMP_tablename.".preupdate.php")) {
				$TMP="";
				$TMP=include ("modules/$V_dir/".$TMP_tablename.".preupdate.php");
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
			$TMP_tablename=substr($V_tablename,strlen(_DEF_TABLE_PREFIX));
			if (file_exists("modules/$V_dir/".$V_mod.".postupdate.php")) {
				$TMP="";
				$TMP=include ("modules/$V_dir/".$V_mod.".postupdate.php");
				if ($TMP!==true && $TMP!="1") echo $TMP;
			}
			if (file_exists("modules/$V_dir/".$V_mod.".postsql.php")) {
				$TMP="";
				$TMP=include ("modules/$V_dir/".$V_mod.".postsql.php");
				if ($TMP!==true && $TMP!="1") echo $TMP;
			}
			if ($V_mod!=$V_tablename) {
				if (file_exists("modules/$V_dir/".$V_tablename.".postupdate.php")) {
		    		    $TMP="";
				    $TMP=include ("modules/$V_dir/".$V_tablename.".postupdate.php");
		        	    if ($TMP!==true && $TMP!="1") echo $TMP;
				}
				if (file_exists("modules/$V_dir/".$V_tablename.".postsql.php")) {
		    		    $TMP="";
				    $TMP=include ("modules/$V_dir/".$V_tablename.".postsql.php");
		        	    if ($TMP!==true && $TMP!="1") echo $TMP;
				}
			}
			if ($TMP_tablename!=$V_tablename && $TMP_tablename!=$V_mod) {
				if (file_exists("modules/$V_dir/".$TMP_tablename.".postupdate.php")) {
					$TMP="";
					$TMP=include ("modules/$V_dir/".$TMP_tablename.".postupdate.php");
					if ($TMP!==true && $TMP!="1") echo $TMP;
				}
				if (file_exists("modules/$V_dir/".$TMP_tablename.".postsql.php")) {
					$TMP="";
					$TMP=include ("modules/$V_dir/".$TMP_tablename.".postsql.php");
					if ($TMP!==true && $TMP!="1") echo $TMP;
				}
			}
                        for ($i = 0; $i < $numf; $i++) {
                           if ($subfunc!="parentreload" && substr($fields[$i]->extra,0,13)=="RAD_subbrowse" && $fields[$i]->nodetail!=true) {
                                   $defaultfunc="detail";
                                   $noreload="x";
                           }
                        }
                        if ($fieldedit!="") $noreload="";
			if ($V_posparent!="") $restUrl="+'&#".$V_posparent."'";
			else $restUrl="";
			if (($subfunc=="browse"||$subfunc=="parentreload") && $noreload=="") {
				echo "<html>\n<body><script type='text/javascript'>\n";
//				echo "if (window.opener) window.opener.location.reload();\n
			   if (_DEF_POPUP_MARGIN=="SUBMODAL") {
				echo "parent.hidePopWin();\n";
				echo "parent.location.reload();\n";
			   } else {
				echo "if (window.opener) {var urlOpener=window.opener.location.href;\n if (urlOpener.indexOf('#')>0) { urlOpener=urlOpener.substr(0,urlOpener.indexOf('#')); }\n window.opener.location.href=urlOpener".$restUrl.";\n window.close(); }\n";
				echo "else { top.location.href=top.location.href".$restUrl."; top.RAD_hideL(); }\n";
			   }
			   echo "\n</script></body></html>\n";
			   die();
			} else if ($subfunc=="browse"||$subfunc=="parentreload") {
			   echo "\n<script type='text/javascript'>\n";
			   if (_DEF_POPUP_MARGIN=="SUBMODAL") {
				echo "parent.setThreshold('window.location=window.location;');";
			   } else {
				echo "var urlOpener=window.opener.location.href;\n";
				echo "if (urlOpener.indexOf('#')>0) { urlOpener=urlOpener.substr(0,urlOpener.indexOf('#')); }\n";
				echo "window.opener.location.href=urlOpener".$restUrl.";\n";
			   }
			   echo "</script>\n";
			}
			else if (strlen($V_roi)>2 && _DEF_POPUP_MARGIN=="SUBMODAL") {
				echo "<script>";
				echo "parent.setThreshold('window.location=window.location;');";
				echo "</script>";
			}
			if ($RAD_errorstr != _DEF_NLSRecordUpdated) $RAD_errorstr .= _DEF_NLSRecordUpdated;
			for ($i = 0; $i < $numf; $i++) {
			    if (${"par".$i} =="") ${"par".$i}=${"V0_par".$i};
			    if (${"par".$i} =="") ${"par".$i}=${"V_par".$i};
			    if (${"par".$i} !="") $pars.="&par$i=".${"par".$i};
			}
			if ($subfunc!="browsedit") {
			    echo "<script>\n";
			    if ($RAD_errorstr != _DEF_NLSRecordUpdated) {
				$RAD_errorstr=str_replace("'","/'",$RAD_errorstr);
				RAD_logError("ERR: UPDATE ".$RAD_errorstr);
				echo "alert('".str_replace("'","\'",$RAD_errorstr)."');\n";
			    } else {
				$RAD_errorstr=str_replace("'","/'",$RAD_errorstr);
				echo "window.status='".$RAD_errorstr."';\n";
			    }
			    $TMP_lap="";
//			    if ($V_lap!="") $TMP_lap="&V_lap=$V_lap";
			    echo "\ndocument.location.href=document.location.href+'?V_dir=$V_dir&V_mod=$V_mod&func=detail".$TMP_lap."&V_prevfunc=update$pars$URLROI&RAD_errorstr=".urlencode($RAD_errorstr)."';\n</script>\n";
			    die();
			}
		} else {
			$func = "error";
			if ($cmdSQL!="") $RAD_errorstr .= " <pre>".$cmdSQL." [".sql_errorno($TMP_result)."] ".sql_error($TMP_result)."</pre>";
			//if ($cmdSQL!="") $RAD_errorstr .= " <! ".$cmdSQL." [".sql_errorno($TMP_result)."] ".sql_error($TMP_result)." >";
		}
	}
} else {
	$func = "error";
	if ($cmdSQL!="") $RAD_errorstr .= " <pre>".$cmdSQL." [".sql_errorno($TMP_result)."] ".sql_error($TMP_result)."</pre>";
	//if ($cmdSQL!="") $RAD_errorstr .= " <! ".$cmdSQL." [".sql_errorno($TMP_result)."] ".sql_error($TMP_result)." >";
}
//---------------------------------------------------------------------------
//*** Update One
//---------------------------------------------------------------------------
		}
	}
	if ($subfunc=="browsedit" && ($VI_count=="" || $iv>($VI_count-1))) {
		if ($RAD_errorstr!="") {
			echo "<html>\n<body><script type='text/javascript'>\n";
			if ($RAD_errorstr != _DEF_NLSRecordUpdated) {
			    echo "alert('".str_replace("'","\'",$RAD_errorstr)."');\n";
			    RAD_logError("ERR: BROWSEDIT ".$RAD_errorstr);
			} else echo "window.status='".$RAD_errorstr."';\n";
			if ($subfunc=="browsedit") echo "var posget=document.referrer.indexOf('?')!=-1;\nif (posget) document.location.href=document.referrer+'&RAD_errorstr=".urlencode($RAD_errorstr)."';\nelse document.location.href=document.referrer+'?V_dir=".$V_dir."&V_mod=".$V_mod."&dbname=$dbname&RAD_errorstr=".urlencode($RAD_errorstr)."';\n";
			else echo "document.location.href=document.referrer+'?V_dir=$V_dir&V_mod=$V_mod&dbname=$dbname&RAD_errorstr=".urlencode($RAD_errorstr)."';\n";
//			echo "document.location.href=document.href;\n";
//			echo "document.location.reload();\n";
		} else {
			echo "<html>\n<body><script type='text/javascript'>\n";
			echo "window.history.go(-1);\n";
		}
		echo "</script></body></html>\n";
		die();
	}
	if ($func=="update") $func=$defaultfunc;
}
//---------------------------------------------------------------------------
//------------------------- Update
//---------------------------------------------------------------------------
?>
