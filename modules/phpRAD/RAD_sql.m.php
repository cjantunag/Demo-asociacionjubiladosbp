<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}
//---------------------------------------------------------------------------
//------------------------- SQL DataBase Class
//---------------------------------------------------------------------------
    $db = new DBSql;
    $db -> Host = $hostname;
    $db -> User = $dbusername;
    $db -> Password = $dbpassword;
    $db -> Database = $dbname;
    $db -> Type = $dbtype;
    $db -> Debug = $verbose_queries;
    $db -> Halt_On_Error = "no";

    if (!isset($limit)) $limit = $rows_limit;
    if (!isset($func)) $func = "browse";
    if ($func=="search" || $func=="browsecalendar" || $func=="browse" || $func=="browsetree" || $func=="login") {
	if ($func == "search" && $RAD_sql_pass!=true) {
		$RAD_sql_pass=true;
		if (!($MaxSearchFields>0)) { $MaxSearchFields=$numf; }
		for ($i = 0; $i < $MaxSearchFields; $i++) {
			if (!isset(${"searchfield".$i})) ${"searchfield".$i} = "";
			$searchvalue="";
			if (is_array(${"searchvalue".$i})) {
				while(count(${"searchvalue".$i})>0) {
					$searchvalue.=array_pop(${"searchvalue".$i})."%";
				}
				if (strlen($searchvalue)>0) $searchvalue=substr($searchvalue,0,strlen($seachvalue)-1);
				unset(${"searchvalue".$i});
				${"searchvalue".$i}=$searchvalue;
			} else {
				if (!isset(${"searchvalue".$i})) {
					if (!isset(${${"searchfield".$i}})) ${"searchvalue".$i} = "";
					else ${"searchvalue".$i}=${${"searchfield".$i}};
					$searchvalue="";
					if (is_array(${"searchvalue".$i})) {
                        		        while(count(${"searchvalue".$i})>0) {
                                        		$searchvalue.=array_pop(${"searchvalue".$i})."%";
                                		}
                                		if (strlen($searchvalue)>0) $searchvalue=substr($searchvalue,0,strlen($seachvalue)-1);
                        	        	unset(${"searchvalue".$i});
        		                       	${"searchvalue".$i}=$searchvalue;
					} else $searchvalue=${"searchvalue".$i};
				} else {
					$searchvalue=${"searchvalue".$i}; 
					$searchvalueTO=${"searchvalueTO".$i}; 
				}
			}
			if (!isset(${"operator".$i})) ${"operator".$i} = "";

			$TMP_searchfield=${"searchfield".$i};
			$operator=${"operator".$i};
			if ($fields[$findex[$TMP_searchfield]]->type=="date" || $fields[$findex[$TMP_searchfield]]->type=="datetime" ||
			    $fields[$findex[$TMP_searchfield]]->type=="dateint" || $fields[$findex[$TMP_searchfield]]->type=="datetimeint") {
			    if (strlen(${${"searchfield".$i}."_day"})==1) ${${"searchfield".$i}."_day"}="0".${${"searchfield".$i}."_day"};
			    if (strlen(${${"searchfield".$i}."_month"})==1) ${${"searchfield".$i}."_month"}="0".${${"searchfield".$i}."_month"};
			    if (strlen(${${"searchfield".$i}."TO_day"})==1) ${${"searchfield".$i}."TO_day"}="0".${${"searchfield".$i}."TO_day"};
			    if (strlen(${${"searchfield".$i}."TO_month"})==1) ${${"searchfield".$i}."TO_month"}="0".${${"searchfield".$i}."TO_month"};
			    if ($operator=='BETWEEN') {
				if (${${"searchfield".$i}."_year"}!= '' && ${${"searchfield".$i}."_month"}!='00' && ${${"searchfield".$i}."_day"} != '00') 
					$searchvalue=${${"searchfield".$i}."_year"}."-".${${"searchfield".$i}."_month"}."-".${${"searchfield".$i}."_day"};
				if (${${"searchfield".$i}."TO_year"}!= '' && ${${"searchfield".$i}."TO_month"}!='00' && ${${"searchfield".$i}."TO_day"} != '00') 
					$searchvalueTO=${${"searchfield".$i}."TO_year"}."-".${${"searchfield".$i}."TO_month"}."-".${${"searchfield".$i}."TO_day"};
					
				if ($searchvalue=='' && $searchvalueTO=='') $operator=''; // Evita errores sql al poner entre y vaciar las dos fechas.
			    } else {
				    if (${${"searchfield".$i}."_year"}!="") $searchvalue=${${"searchfield".$i}."_year"};
				    else if (isset(${${"searchfield".$i}."_year"})) $searchvalue.="%";
				    if (${${"searchfield".$i}."_month"}>0) $searchvalue.="-".${${"searchfield".$i}."_month"};
				    else if (isset(${${"searchfield".$i}."_month"})) $searchvalue.="-%";
				    if (${${"searchfield".$i}."_day"}>0) $searchvalue.="-".${${"searchfield".$i}."_day"};
				    else if (isset(${${"searchfield".$i}."_day"})) $searchvalue.="-%";

				    if (${${"searchfield".$i}."TO_year"}!="") $searchvalueTO=${${"searchfield".$i}."TO_year"};
				    else if (isset(${${"searchfield".$i}."TO_year"})) $searchvalueTO.="%";
				    if (${${"searchfield".$i}."TO_month"}>0) $searchvalueTO.="-".${${"searchfield".$i}."TO_month"};
				    else if (isset(${${"searchfield".$i}."TO_month"})) $searchvalueTO.="-%";
				    if (${${"searchfield".$i}."TO_day"}>0) $searchvalueTO.="-".${${"searchfield".$i}."TO_day"};
				    else if (isset(${${"searchfield".$i}."TO_day"})) $searchvalueTO.="-%";
				}
			}
			if ($fields[$findex[$TMP_searchfield]]->type=="datetext" || $fields[$findex[$TMP_searchfield]]->type=="datetimetext" ||
			    $fields[$findex[$TMP_searchfield]]->type=="dateinttext" || $fields[$findex[$TMP_searchfield]]->type=="datetimeinttext") {
				${${"searchfield".$i}}=trim(${${"searchfield".$i}}); ${${"searchfield".$i}}=str_replace(":","",${${"searchfield".$i}});
				${${"searchfield".$i}}=str_replace("/","",${${"searchfield".$i}}); ${${"searchfield".$i}}=str_replace("-","",${${"searchfield".$i}});
				${${"searchfield".$i}}=str_replace("  "," ",${${"searchfield".$i}});
				$TMP_elems=explode(" ",${"searchfield".$i});
				if (count($TMP_elems)>1) $TMP_date=$TMP_elems[0];
				else $TMP_date=${"searchfield".$i};
				$TMP_dateyear=substr($TMP_date,4);
				if (strlen($TMP_dateyear)==2) {
					if ($TMP_dateyear<70) $TMP_dateyear="20".$TMP_dateyear;
					else $TMP_dateyear="19".$TMP_dateyear;
				}
				if (strlen($TMP_dateyear)>4) $TMP_dateyear=substr($TMP_dateyear,0,4);
				$searchvalue=$TMP_dateyear."-".substr($TMP_date,2,2)."-".substr($TMP_date,0,2);

				${${"searchfield".$i}."TO"}=trim(${${"searchfield".$i}."TO"}); ${${"searchfield".$i}."TO"}=str_replace(":","",${${"searchfield".$i}."TO"});
				${${"searchfield".$i}."TO"}=str_replace("/","",${${"searchfield".$i}."TO"}); ${${"searchfield".$i}."TO"}=str_replace("-","",${${"searchfield".$i}."TO"});
				${${"searchfield".$i}."TO"}=str_replace("  "," ",${${"searchfield".$i}."TO"});
				$TMP_elems=explode(" ",${${"searchfield".$i}."TO"});
				if (count($TMP_elems)>1) $TMP_date=$TMP_elems[0];
				else $TMP_date=${${"searchfield".$i}."TO"};
				$TMP_dateyear=substr($TMP_date,4);
				if (strlen($TMP_dateyear)==2) {
					if ($TMP_dateyear<70) $TMP_dateyear="20".$TMP_dateyear;
					else $TMP_dateyear="19".$TMP_dateyear;
				}
				if (strlen($TMP_dateyear)>4) $TMP_dateyear=substr($TMP_dateyear,0,4);
				$searchvalueTO=$TMP_dateyear."-".substr($TMP_date,2,2)."-".substr($TMP_date,0,2);
			}
			if ($fields[$findex[$TMP_searchfield]]->type=="timetext" || $fields[$findex[$TMP_searchfield]]->type=="datetimetext" ||
			    $fields[$findex[$TMP_searchfield]]->type=="timeinttext" || $fields[$findex[$TMP_searchfield]]->type=="datetimeinttext") {
				${${"searchfield".$i}}=trim(${${"searchfield".$i}}); ${${"searchfield".$i}}=str_replace(":","",${${"searchfield".$i}});
				${${"searchfield".$i}}=str_replace("/","",${${"searchfield".$i}}); ${${"searchfield".$i}}=str_replace("-","",${${"searchfield".$i}});
				${${"searchfield".$i}}=str_replace("  "," ",${${"searchfield".$i}});
				$TMP_elems=explode(" ",${"searchfield".$i});
				if (count($TMP_elems)>1) $TMP_time=$TMP_elems[1];
				if ($fields[$findex[$TMP_searchfield]]->type=="datetimetext" || $fields[$findex[$TMP_searchfield]]->type=="datetimeinttext") 
					$searchvalue.=" ";
				$searchvalue.=substr($TMP_time,0,2).":".substr($TMP_time,2,2).":".substr($TMP_time,4,2);

				${${"searchfield".$i}."TO"}=trim(${${"searchfield".$i}."TO"}); ${${"searchfield".$i}."TO"}=str_replace(":","",${${"searchfield".$i}."TO"});
				${${"searchfield".$i}."TO"}=str_replace("/","",${${"searchfield".$i}."TO"}); ${${"searchfield".$i}."TO"}=str_replace("-","",${${"searchfield".$i}."TO"});
				${${"searchfield".$i}."TO"}=str_replace("  "," ",${${"searchfield".$i}."TO"});
				$TMP_elems=explode(" ",${${"searchfield".$i}."TO"});
				if (count($TMP_elems)>1) $TMP_time=$TMP_elems[1];
				if ($fields[$findex[$TMP_searchfield]]->type=="datetimetext" || $fields[$findex[$TMP_searchfield]]->type=="datetimeinttext") 
					$searchvalueTO.=" ";
				$searchvalueTO.=substr($TMP_time,0,2).":".substr($TMP_time,2,2).":".substr($TMP_time,4,2);
			}
			if ($fields[$findex[$TMP_searchfield]]->type=="datetime") { $searchvalue.=" "; $searchvalueTO.=" "; }
			if ($fields[$findex[$TMP_searchfield]]->type=="time" || $fields[$findex[$TMP_searchfield]]->type=="datetime") {
			    if (strlen(${${"searchfield".$i}."_hour"})==1) ${${"searchfield".$i}."_hour"}="0".${${"searchfield".$i}."_hour"};
			    if (strlen(${${"searchfield".$i}."_min"})==1) ${${"searchfield".$i}."_min"}="0".${${"searchfield".$i}."_min"};
			    if (strlen(${${"searchfield".$i}."_sec"})==1) ${${"searchfield".$i}."_sec"}="0".${${"searchfield".$i}."_sec"};
//    			    $searchvalue.=${${"searchfield".$i}."_hour"}.":".${${"searchfield".$i}."_min"}.":".${${"searchfield".$i}."_sec"};
    			    if (${${"searchfield".$i}."_hour"}!="") $searchvalue.=${${"searchfield".$i}."_hour"};
    			    else if (isset(${${"searchfield".$i}."_hour"})) $searchvalue.="%";
    			    if (${${"searchfield".$i}."_min"}!="") $searchvalue.=":".${${"searchfield".$i}."_min"};
    			    else if (isset(${${"searchfield".$i}."_min"})) $searchvalue.=":%";
    			    if (${${"searchfield".$i}."_sec"}!="") $searchvalue.=":".${${"searchfield".$i}."_sec"};
    			    else if (isset(${${"searchfield".$i}."_sec"})) $searchvalue.=":%";

			    if (strlen(${${"searchfield".$i}."TO_hour"})==1) ${${"searchfield".$i}."TO_hour"}="0".${${"searchfield".$i}."TO_hour"};
			    if (strlen(${${"searchfield".$i}."TO_min"})==1) ${${"searchfield".$i}."TO_min"}="0".${${"searchfield".$i}."TO_min"};
			    if (strlen(${${"searchfield".$i}."TO_sec"})==1) ${${"searchfield".$i}."TO_sec"}="0".${${"searchfield".$i}."TO_sec"};
//    			    $searchvalueTO.=${${"searchfield".$i}."TO_hour"}.":".${${"searchfield".$i}."TO_min"}.":".${${"searchfield".$i}."TO_sec"};
    			    if (${${"searchfield".$i}."TO_hour"}!="") $searchvalueTO.=${${"searchfield".$i}."TO_hour"};
    			    else if (isset(${${"searchfield".$i}."TO_hour"})) $searchvalueTO.="%";
    			    if (${${"searchfield".$i}."TO_min"}!="") $searchvalueTO.=":".${${"searchfield".$i}."TO_min"};
    			    else if (isset(${${"searchfield".$i}."TO_min"})) $searchvalueTO.=":%";
    			    if (${${"searchfield".$i}."TO_sec"}!="") $searchvalueTO.=":".${${"searchfield".$i}."TO_sec"};
    			    else if (isset(${${"searchfield".$i}."TO_sec"})) $searchvalueTO.=":%";
			}
			if (ereg("datetime",$fields[$findex[$TMP_searchfield]]->type)) { $searchvalue=trim($searchvalue); $searchvalueTO=trim($searchvalueTO); }
			if (ereg("date",$fields[$findex[$TMP_searchfield]]->type) && strlen($searchvalue)==10 && $searchvalue==$searchvalueTO && $operator=="BETWEEN") $searchvalueTO=RAD_julianDate(RAD_gregorianDate($searchvalueTO)+1);
//			if ($TMP_searchfield!="" && $operator!="") setSessionVar("SESSION_srch_".$TMP_searchfield,$searchvalue,0);
			$TMP_searchvalue[$i]=$searchvalue; 
			$TMP_searchvalueTO[$i]=$searchvalueTO; 
			setSessionVar("SESSION_srch_".$V_mod.$TMP_searchfield,$searchvalue,0);
			setSessionVar("SESSION_srchop_".$V_mod.$TMP_searchfield,$operator,0);
			if ($operator=="") continue;
			if ($operator !="") {
			  	if (trim($defaultfilter)!="") {
			  		$defaultfilter = trim($defaultfilter);
			  		if (substr($defaultfilter,0,1)!="(") $defaultfilter="(".$defaultfilter.")";
			  	}
			}
			$TMP_type=$fields[$findex[$TMP_searchfield]]->dtype;
			if (($TMP_type=="plistm" || $TMP_type=="plistdbm" || $TMP_type=="plistdbmtree" || 
			    $TMP_type=="popupdbm" || $TMP_type=="rlistm" || $TMP_type=="rlistdbm" || $TMP_type=="checkboxm" || $TMP_type=="checkboxdbm") &&
			    ($operator=="LIKE" || $operator=="NOT LIKE" || $operator=="=" || $operator=="!=")) {
				$TMPA_vals=explode(",",$searchvalue.",");
				$TMP_cont=0;
				foreach($TMPA_vals as $TMP_idx=>$TMP_searchvalue) {
					if (trim($TMP_searchvalue)=="" || trim($TMP_searchvalue)=="%") continue;
					$TMP_searchvalue=",".$TMP_searchvalue.",";
					if ($TMP_cont==0) {
						if ($defaultfilter!="") $defaultfilter .= " AND ";
						$defaultfilter .= " (";
					}
					if ($operator=="=" || $operator=="!=") {
						if ($TMP_cont>0) $defaultfilter .= " OR ";
						$defaultfilter .= " $V_tablename.$TMP_searchfield";
						$defaultfilter .= " $operator'$TMP_searchvalue'";
					}
					if ($operator=="LIKE" || $operator=="NOT LIKE") {
						if ($TMP_cont>0) $defaultfilter .= " OR ";
						$defaultfilter .= " $V_tablename.$TMP_searchfield";
						$defaultfilter .= " $operator '%$TMP_searchvalue%'";
					}
					$TMP_cont++;
				}
				if ($TMP_cont>0) $defaultfilter .= ")";
			} else {
			   if ($TMP_type=="num") $searchvalue=RAD_str2num($searchvalue);
			   if (ereg("'",$searchvalue)) {
				//$searchvalue=addslashes($searchvalue);
				//$TMP_escape=1;
				$searchvalue=str_replace("'","''",$searchvalue);
			   }
			   if ($operator=="BEGIN") {
				if ($defaultfilter!="") $defaultfilter .= " AND ";
				$defaultfilter .= " $V_tablename.$TMP_searchfield LIKE '$searchvalue%'";
			   } else if ($operator=="IS NULL") {
				if ($defaultfilter!="") $defaultfilter .= " AND ";
				$defaultfilter .= "($V_tablename.$TMP_searchfield IS NULL OR $V_tablename.$TMP_searchfield='')";
			   } else if ($operator=="IS NOT NULL") {
				if ($defaultfilter!="") $defaultfilter .= " AND ";
				$defaultfilter .= "($V_tablename.$TMP_searchfield IS NOT NULL AND $V_tablename.$TMP_searchfield!='')";
			   } else if ($operator!="") {
				if ($defaultfilter!="") $defaultfilter .= " AND ";
				if (ereg("LIKE",$operator) && (_DEF_dbtype=="Oracle")) $defaultfilter .= " UPPER($V_tablename.$TMP_searchfield)";
				else $defaultfilter .= " $V_tablename.$TMP_searchfield";
		    		switch ($operator) {
					case ("="):
					case (">"):
					case (">="):
					case ("<"):
					case ("<="):
					case ("!="):
						$defaultfilter .= " $operator'$searchvalue'";
						break;
					case ("IN"):
					case ("NOT IN"):
						$defaultfilter .= " $operator ($searchvalue)";
						break;
					case ("LIKE"):
					case ("NOT LIKE"): {
						if (_DEF_dbtype=="Oracle") 
							$defaultfilter .= " $operator UPPER('%$searchvalue%')";
						else 
							$defaultfilter .= " $operator '%$searchvalue%'";
						}
						break;
					case ("LIKE OR EQUAL"):
						if (ereg("%",$searchvalue)) $defaultfilter .= " Like '$searchvalue'";
						else $defaultfilter .= " ='$searchvalue'";
						break;
					case ("IS NULL"):
					case ("IS NOT NULL"):
						$defaultfilter .= " $operator";
						break;
					case ("BETWEEN"):
						if ($searchvalue!='') {
							$defaultfilter .= " >='$searchvalue'";
							if ($searchvalueTO!='') $defaultfilter .= " AND $V_tablename.$TMP_searchfield ";
						}
						if ($searchvalueTO!='') $defaultfilter.= "<='$searchvalueTO' ";
						break;
					default:
						echo "Unknown search operator. Exiting.";
						exit;
				}
			   }
			   //if ($TMP_escape==1) $defaultfilter.=" ESCAPE '\'"; 
			}
		}
	}
	//if (empty($found)) {
	    $TMP_defaultfilter="";
	    if ($defaultfilter!="") $TMP_defaultfilter=" WHERE ".$defaultfilter;
	    $cmdSQL="SELECT Count(*) FROM $tablename ".$TMP_defaultfilter;
	    $TMP_initime=RAD_microtime();
	    if (_SQL_DEBUG!="0") echo $TMP_initime." SQL query: ".$cmdSQL;
	    if ($db -> query($cmdSQL) && $db -> next_record()) {
		$TMP_diftime=RAD_microtime()-$TMP_initime;
		if (_SQL_DEBUG!="0") echo " [".substr($TMP_diftime,0,8)."]";
		$found = $db -> Record[0];
		$db -> free();
	    } else {
		$func = "error";
		$errorstr .= $cmdSQL.$db -> Error;
	    }
	//}
	if ($limit < 1) $limit = $found;
	if (!isset($start)) $start = 0;
	if ($start=="") $start = 0;
	$url = "$PHP_SELF?func=$func";
	if ($param!="") $url .= "&param=$param";
	if ($desc!="") $url .= "&desc=$desc";
	if ($desc!="") $url .= "&PHPSESSID=$PHPSESSID";
	if ($dbname!=_DEF_dbname) $url .= "&dbname=$dbname";
	if ($func == "search") 
		for ($i = 0; $i < $MaxSearchFields; $i++) {
			$TMP_searchfield=${"searchfield".$i};
//			$searchvalue=${"searchvalue".$i};
			$searchvalue=$TMP_searchvalue[$i];
			$searchvalueTO=$TMP_searchvalueTO[$i];
			$operator=${"operator".$i};
	    		if ($operator!="") {
				$url .= "&searchfield".$i."=$TMP_searchfield&operator".$i."=".urlencode($operator)."&"."searchvalue".$i."=".urlencode($searchvalue);
				if ($searchvalueTO!="") $url .= "&searchvalueTO".$i."=".urlencode($searchvalueTO);
			}
		}
	if ($V_prevfunc!="") $url .= "&V_prevfunc=$V_prevfunc";
	//$url .= "&found=$found&orderby=$orderby&limit=$limit&start=";
	$url .= "&orderby=$orderby&limit=$limit&start=";
	if ($start=="0" || $start=="") {
		if ($found>0) $tmp="1";
		else $tmp="0";
	} else $tmp=$start+1;
	$position="$tmp-";
	$tmp=$start+$limit;
	if ($tmp>$found) $position.=$found;
	else $position.=$tmp;
	$jumps=array();
	$prevpage = "";
	$nextpage = "";
	if ($start>0) { 
	    //$prevpage = "<A ACCESSKEY='1' TITLE='ALT+1' HREF=\"$url"."0".$tabURLROI."\">"._DEF_NLSStart."</A>";
	    $jumps["start"]=0;
	    if (($start-10*$limit)>-1) {
	        //$prevpage .= '<a href='.$url.($start-10*$limit).$tabURLROI.' data-role="button" data-icon="arrow-l" data-theme="b">'._DEF_NLSPageBefore.'</a>';
		$jumps["prevm"]=($start-10*$limit);
	    } else {
	        if (($start-5*$limit)>-1) {
		    $prevpage .= '<a href="'.$url.($start-5*$limit).$tabURLROI.'" data-role="button" data-icon="arrow-l" data-theme="b"data-iconpos="notext">'._DEF_NLSPageBefore.'</a>';
		    $jumps["prevm"]=($start-5*$limit);
		}
	    }
	    $tmp = max($start-$limit,0);
	    if ($tmp>=0) {
            $prevpage .= '<a href="'.$url.$tmp.$tabURLROI.'" data-role="button" data-icon="arrow-l" data-theme="b" data-iconpos="notext">'._DEF_NLSBefore.' </a>';
            $jumps["prev"]=$tmp;
	    }
	}
	if ($start + $limit < $found) { 
	    $tmp = $start+$limit*2;
	    if ($tmp<$found) {
            $nextpage = '<a href="'.$url.($start+$limit).$tabURLROI.'" data-role="button" data-icon="arrow-r" data-theme="b" data-iconpos="notext">'._DEF_NLSAfter.'</a>';
            $jumps["next"]=$start+$limit;
	    }
	    if (($start+10*$limit)<$found) {
	        //$nextpage .= '<a href='.$url.($start+10*$limit).$tabURLROI.' data-role="button" data-icon="arrow-r" data-theme="b" data-iconpos="notext">'._DEF_NLSPageAfter.'</a>';
            $jumps["nextm"]=$start+10*$limit;
	    } else {
	        if (($start+5*$limit)<$found) {
		    //$nextpage .= '<a href='.$url.($start+5*$limit).$tabURLROI.' data-role="button" data-icon="arrow-r" data-theme="b">'._DEF_NLSPageAfter.'</a>';
		    $jumps["nextm"]=$start+5*$limit;
		}
	    }
	    if ($found>($start+2*$limit)) { 
            //$nextpage .= '<a href='.$url.($found-$limit).$tabURLROI.' data-role="button" data-icon="arrow-r" data-theme="b">'._DEF_NLSEnd.'</a>';
            $jumps["end"]=$found-$limit;
	     }
	    else if ($found>($start+$limit)) { 
            //$nextpage .= '<a href='.$url.($found-$limit).$tabURLROI.' data-role="button" data-icon="arrow-r" data-theme="b">'._DEF_NLSEnd.'</a>';
            $jumps["end"]=$start+$limit;
		}
	}

//	$RAD_menupages = "&nbsp;&nbsp;&nbsp;";
//	if ($limit> 0) {
//	    for ($i = 0; $i <= (int)($found/$limit); $i++) {
//		$RAD_menupages .= "&nbsp;\n\t\t<A ACCESSKEY='0' TITLE='ALT+0' HREF=\"$url".($i*$limit).$tabURLROI."\">".($i+1)."</A>";
//	    }
//	}
    }
?>
