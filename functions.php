<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}
/////////////////////////////////////////////////////////////////////////////
function getSessionVar($ParamName) {
	global $sessionType, ${$ParamName}, $HTTP_COOKIE_VARS, $HTTP_SESSION_VARS, $_SESSION;
	$ParamValue = "";
	if(!isset($HTTP_SESSION_VARS)) $HTTP_SESSION_VARS=& $_SESSION;
	if (count($_SESSION)>0) foreach ($_SESSION as $TMP_key=>$TMP_val) { $HTTP_SESSION_VARS[$TMP_key]=$TMP_val; }
	if (isset($HTTP_SESSION_VARS["$ParamName"])) return $HTTP_SESSION_VARS["$ParamName"];
	if (isset($_SESSION["$ParamName"])) {
		$HTTP_SESSION_VARS["$ParamName"]=$_SESSION["$ParamName"];
		return $HTTP_SESSION_VARS["$ParamName"];
	}
	if ($sessionType=="cookie") {
		if (isset($HTTP_COOKIE_VARS[$ParamName])) return $HTTP_COOKIE_VARS[$ParamName];
	}
	return $ParamValue;
}
/////////////////////////////////////////////////////////////////////////////
function setSessionVar($ParamName, $ParamValue, $lifetime=0) {
	global ${$ParamName}, $HTTP_SESSION_VARS, $_SESSION;
	${$ParamName} = $ParamValue;
	$HTTP_SESSION_VARS["$ParamName"]=$ParamValue;
	$_SESSION["$ParamName"]=$ParamValue;
}
/////////////////////////////////////////////////////////////////////////////
function is_admin() {
	global $RAD_dbi;
	if (getSessionVar("SESSION_user")=="") { return 0; }
	if (getSessionVar("SESSION_admin")=="1") { return 1; }
	if (getSessionVar("SESSION_U_admin")=="1") { return 1; }
//	$TMP_user = base64_decode(getSessionVar("SESSION_user"));
//	$result=sql_query("select "._DBF_U_PASS." from "._DBT_USERS." where "._DBF_U_USER."='".$TMP_user."' AND "._DBF_U_ADMIN."='1'", $RAD_dbi);
//	list($TMP_pass)=sql_fetch_row($result, $RAD_dbi);
//	if($TMP_pass==getSessionVar("SESSION_pass") && getSessionVar("SESSION_pass")!="") {
//		return 1;
//	}
	return 0;
}
/////////////////////////////////////////////////////////////////////////////
function is_user() {
	global $RAD_dbi;
	if (getSessionVar("SESSION_user")=="") { return 0; }
	$TMP_user=base64_decode(getSessionVar("SESSION_user"));
	if ($TMP_user!="" && getSessionVar("SESSION_user")!="") { return 1; }
//	$result=sql_query("select "._DBF_U_PASS." from "._DBT_USERS." where "._DBF_U_USER."='".$TMP_user."'", $RAD_dbi);
//	list($TMP_pass)=sql_fetch_row($result, $RAD_dbi);
//	if($TMP_pass==getSessionVar("SESSION_pass")) {
//		return 1;
//	}
	return 0;
}
/////////////////////////////////////////////////////////////////////////////
function is_editor() {
	global $RAD_dbi, $V_mod;
	$TMP_userprofile=getSessionVar("SESSION_profiles");
	if (eregi(",edt,", $TMP_userprofile)) {
		return true;
	}
	if (eregi("forum", $V_mod) && eregi(",edf,", $TMP_userprofile)) {
		return true;
	}
	if (eregi("news", $V_mod) && eregi(",edn,", $TMP_userprofile)) {
		return true;
	}
	return false;
}
/////////////////////////////////////////////////////////////////////////////
function is_editor_propio() {
	global $RAD_dbi;
	$TMP_userprofile=getSessionVar("SESSION_profiles");
	if (eregi(",edp,", $TMP_userprofile)) {
		return true;
	}
	return false;
}
/////////////////////////////////////////////////////////////////////////////
function is_viewer() {
	global $RAD_dbi;
	$TMP_userprofile=getSessionVar("SESSION_profiles");
	if (eregi(",pro,", $TMP_userprofile)) return true;
	if (eregi(",coo,", $TMP_userprofile)) return true;
	if (eregi(",tut,", $TMP_userprofile)) return true;
	return false;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_unlink($fich) {
global $dbname;
    if (_DEF_KeepDelete=="1") {
      if(!file_exists("files/deleted")) {
        mkdir("files/deleted", 0777);
        copy("files/index.html","files/deleted/index.html");
      }
      if(!file_exists("files/deleted/".$dbname)) {
        mkdir("files/deleted/".$dbname, 0777);
        copy("files/index.html","files/deleted/".$dbname."/index.html");
      }
      $fich2=str_replace("/","_",$fich);
      rename("files/".$dbname."/".$fich, "files/deleted/".$dbname."/".$fich2);
      //system ("mv files/".$dbname."/".$fich." files/deleted/".$dbname."/".$fich);
    } else {
      @unlink ("files/".$dbname."/".$fich);
    }
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_delParamURL($URL, $param) {
    $URL=str_replace("?","?&", $URL);
    $arr = explode("&", $URL);
    if (count($arr)>0) {
        $URL="";
        for ($ki = 0; $ki < count($arr); $ki++) {
            $par = explode("=", $arr[$ki]);
            if ($par[0]!=$param) {
                if ($URL!="") $URL.="&";
                $URL.=$arr[$ki];
            }
        }
    }
    $URL = str_replace("&=&", "&", $URL);
    $URL=str_replace("?&","?", $URL);
    $URL=str_replace("?=","?", $URL);
    if (substr($URL,0,1)!="?" && substr($URL,0,1)!="&") $URL="&".$URL;
    return $URL;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_delParamFORM($FORMHIDDEN, $param) {
    $FORMHIDDEN=str_replace("name='".$param."'", "", $FORMHIDDEN);
    $FORMHIDDEN=str_replace("name=\"".$param."\"", "", $FORMHIDDEN);
    $FORMHIDDEN=str_replace("name=".$param." ", "", $FORMHIDDEN);
    return $FORMHIDDEN;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_delField($namefield) {
    global $fields, $findex;
    if (ereg(",",$namefield))
    $TMP_arr=explode(",",$namefield);
    else $TMP_arr=array($namefield);

    foreach ($TMP_arr as $namefield) {
      if ($findex[$namefield]!="") {
        $idx=$findex[$namefield];
        $fields[$idx] -> canbenull = true;
        $fields[$idx] -> browsable = false;
        $fields[$idx] -> browsedit = false;
        $fields[$idx] -> fieldedit = false;
        $fields[$idx] -> nonew = true;
        $fields[$idx] -> noedit = true;
        $fields[$idx] -> nodetail = true;
        $fields[$idx] -> noinsert = true;
        $fields[$idx] -> noupdate = true;
        $fields[$idx] -> noprint = true;
        $fields[$idx] -> searchable = false;
      }
    }
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_delLap($namelap,$exact=true) {
    global $fields, $findex;
    foreach ($fields as $v) {
	if ($exact) {
		if ($v->overlap==$namelap) RAD_delField($v->name);
	} else {
		if (ereg($namelap,$v->overlap)) RAD_delField($v->name);
	}
    }
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_editExtra($fieldname,$index,$newval,$append=false) {
    global $fields, $findex;

    if ($fields[$findex[$fieldname]]->extra!='') {
        $TMP_arr=explode(":",$fields[$findex[$fieldname]]->extra);
        if ($append) {
            $TMP_arr[$index].=$newval;
        }
        else {
            $TMP_arr[$index]=$newval;
        }
        $fields[$findex[$fieldname]]->extra=implode(":",$TMP_arr);
    }
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_addField($index,$name,$props) {
    global $numf, $fields, $findex;
    if ($index=="") $index=$numf;
    for ($ki=$numf; $ki>-1; $ki--) {
        if ($ki<$index) continue;
	if (count($fields[$ki])>0) foreach($fields[$ki] as $attr=>$val) {
		$fields[$ki+1]->$attr=$val;
		unset($fields[$ki]->$attr);
	}
	foreach($findex as $TMP_name=>$idx) {
		if ($idx==$ki) $findex[$TMP_name]++;
	}
    }
    $findex[$name]=$index;
    RAD_setFieldProperty($name,$props);
    $numf++;
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////
function RAD_userName($TMP_user) {
    global $RAD_dbi;
    $TMP_result=sql_query("select * from "._DBT_USERS." where "._DBF_U_USER."='$TMP_user'", $RAD_dbi);
    $TMP_row = sql_fetch_array($TMP_result, $RAD_dbi);
    return $TMP_row[_DBF_U_NAME];
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////
function RAD_groupName($TMP_group) {
    global $RAD_dbi;
    $TMP_result=sql_query("select * from "._DBT_PROFILES." where "._DBF_P_IDPROFILE."='$TMP_group'", $RAD_dbi);
    $TMP_row = sql_fetch_array($TMP_result, $RAD_dbi);
    return $TMP_row[_DBF_P_PROFILE];
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////
function RAD_getUserProfile($profile,$uname) {
    global $SESSION_SID, $PHPSESSID, $remember, $RAD_dbi, $_REQUEST, $V_dir2, $V_idmod2;

    if ($uname!="") {
        $result=sql_query("select * from "._DBT_USERS." where "._DBF_U_USER."='$uname'", $RAD_dbi);
//      $result=sql_query("select * from "._DBT_USERS." where "._DBF_U_USER."='$uname' AND "._DBF_U_ACTIVE."!='0'", $RAD_dbi);
        $TMP_row=sql_fetch_array($result, $RAD_dbi);
        $TMP_admin=$TMP_row[_DBF_U_ADMIN];
        $TMP_profiles=$TMP_row[_DBF_U_PROFILES];
        $TMP_mods=$TMP_row[_DBF_U_MODULES];
    }
    if ($profile!="") {
        if ($TMP_profiles!="") $TMP_profiles.=",";
        $TMP_profiles=$profile;
    }
    if (_DBT_USERSGROUP!="_DBT_USERSGROUP") {
        $result2=sql_query("select * from "._DBT_USERSGROUP." where "._DBF_UG_IDUSER."='".$TMP_row[_DBF_U_IDUSER]."'", $RAD_dbi);
            while($TMP_row2=sql_fetch_array($result2, $RAD_dbi)) {
        if ($TMP_row2[_DBF_UG_PROFILES]!="") {
            if ($TMP_profiles!="") $TMP_profiles.=",";
            $TMP_profiles.=$TMP_row2[_DBF_UG_PROFILES];
        }
        }
    }

    if ($TMP_profiles!="") $TMP_profiles=",".$TMP_profiles.",";
    $TMP_profs=explode(",",$TMP_profiles);
    for($ki=0; $ki<count($TMP_profs); $ki++) {
            if ($TMP_profs[$ki]!="") {
        if (_DBF_M_PROFILES!="_DBF_M_PROFILES") {
            $result2=sql_query("select * from "._DBT_MODULES." where "._DBF_M_PROFILES." LIKE '%,".$TMP_profs[$ki].",%'", $RAD_dbi);
                while($TMP_row2=sql_fetch_array($result2, $RAD_dbi)) {
            if ($TMP_row2[_DBF_M_IDMODULE]!="") {
                    if ($TMP_mods!="") $TMP_mods.=",";
                    $TMP_mods.=$TMP_row2[_DBF_M_IDMODULE];
            }
            }
        }
        if (_DBT_MODSGROUP!="_DBT_MODSGROUP") {
            $result2=sql_query("select * from "._DBT_MODSGROUP." where "._DBF_MG_PROFILES."='".$TMP_profs[$ki]."'", $RAD_dbi);
                while($TMP_row2=sql_fetch_array($result2, $RAD_dbi)) {
            if ($TMP_row2[_DBF_MG_IDMOD]!="") {
                    if ($TMP_mods!="") $TMP_mods.=",";
                    $TMP_mods.=$TMP_row2[_DBF_MG_IDMOD];
            }
            }
        }
        if($TMP_file=@opendir("themes/"._DEF_THEME.$TMP_profs[$ki])) $TMP_theme=_DEF_THEME.$TMP_profs[$ki];
        }
    }
    if (_DBT_MODSUSER!="_DBT_MODSUSER") {
        $result2=sql_query("select * from "._DBT_MODSUSER." where "._DBF_MU_IDUSER."='".$TMP_row[_DBF_U_IDUSER]."'", $RAD_dbi);
        while($TMP_row2=sql_fetch_array($result2, $RAD_dbi)) {
        if ($TMP_row2[_DBF_MU_IDMOD]!="") {
                if ($TMP_mods!="") $TMP_mods.=",";
            $TMP_mods.=$TMP_row2[_DBF_MU_IDMOD];
        }
        }
    }
    if ($TMP_mods!="") $TMP_mods=",".$TMP_mods.",";

    return $TMP_mods;
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////
function RAD_existTable($TMP_table) {
    global $RAD_dbi, $SESSION_NAMETABLES;
    $TMP_table=strtolower($TMP_table);
    if ($SESSION_NAMETABLES[$TMP_table]=="-1") return false;
    if ($SESSION_NAMETABLES[$TMP_table]!="") return true;
    if (_DEF_dbtype=="Oracle") $TMP_result=@sql_query("select TABLE_NAME from tabs", $RAD_dbi);
    else $TMP_result=@sql_query("SHOW tables", $RAD_dbi);
    while($TMP_row = sql_fetch_array($TMP_result, $RAD_dbi)) {
        $TMP_row[0]=strtolower($TMP_row[0]);
        $SESSION_NAMETABLES[$TMP_row[0]]=$TMP_row[0];
        if ($TMP_row[0] == $TMP_table) return true;
    }
    $SESSION_NAMETABLES[$TMP_table]="-1";
    return false;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_showText($value) {
    if (eregi("<br", $value)||eregi("<p", $value)||eregi("<table", $value)) return $value;
    $value=str_replace("\n","<br>\n",$value);
    return $value;

}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_showDateInt($value) {
    if ($value == "" || $value == "0" || $value == 0 || $value == -1) {
        //$value="0000-00-00 00:00:00";
        return "";
    } else {
        $current_date = getdate($value);
        $value=$current_date["year"]."-".$current_date["mon"]."-".$current_date["mday"];
        $value.=" ".$current_date["hours"].":".$current_date["minutes"].":".$current_date["seconds"];
    }

    $arr = explode(" ", $value);
    $valuedate=$arr[0];
    $valuetime=$arr[1];

    $TMP_cont.=RAD_showDate($valuedate);
    return $TMP_cont;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_showShortDateInt($value) {
    if ($value == "" || $value == "0" || $value == 0 || $value == -1) {
        //$value="0000-00-00 00:00:00";
        return "";
    } else {
        $current_date = getdate($value);
        $value=$current_date["year"]."-".$current_date["mon"]."-".$current_date["mday"];
        $value.=" ".$current_date["hours"].":".$current_date["minutes"].":".$current_date["seconds"];
    }

    $arr = explode(" ", $value);
    $valuedate=$arr[0];
    $valuetime=$arr[1];

    $TMP_cont.=RAD_showShortDate($valuedate);
    return $TMP_cont;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_showDateTimeInt($value) {
    if ($value == "" || $value == "0" || $value == 0 || $value == -1) {
        //$value="0000-00-00 00:00:00";
        return "";
    } else {
        $current_date = getdate($value);
        $value=$current_date["year"]."-".$current_date["mon"]."-".$current_date["mday"];
        $value.=" ".$current_date["hours"].":".$current_date["minutes"].":".$current_date["seconds"];
    }

    $arr = explode(" ", $value);
    $valuedate=$arr[0];
    $valuetime=$arr[1];

    $TMP_cont.=RAD_showDate($valuedate);
    $TMP_cont.="&nbsp;&nbsp;";
    $TMP_cont.=RAD_showTime($valuetime);
    return $TMP_cont;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_showShortDateTimeInt($value) {
    if ($value == "" || $value == "0" || $value == 0 || $value == -1) {
        //$value="0000-00-00 00:00:00";
        return "";
    } else {
        $current_date = getdate($value);
        $value=$current_date["year"]."-".$current_date["mon"]."-".$current_date["mday"];
        $value.=" ".$current_date["hours"].":".$current_date["minutes"].":".$current_date["seconds"];
    }

    $arr = explode(" ", $value);
    $valuedate=$arr[0];
    $valuetime=$arr[1];

    $TMP_cont.=RAD_showShortDate($valuedate);
    $TMP_cont.="&nbsp;&nbsp;";
    $TMP_cont.=RAD_showTime($valuetime);
    return $TMP_cont;
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////
function RAD_lookup($TMP_table,$TMP_field,$TMP_key,$TMP_val) {
    global $RAD_dbi;
    if ($TMP_val!="") $TMP_result=sql_query("select * from $TMP_table where $TMP_key='$TMP_val'", $RAD_dbi);
    else $TMP_result=sql_query("select * from $TMP_table where $TMP_key='' OR $TMP_key IS NULL", $RAD_dbi);
    $TMP_row = sql_fetch_array($TMP_result, $RAD_dbi);
    $lang=getSessionVar("SESSION_lang");
    if ($TMP_row[$TMP_field."_".$lang]!="") $TMP_row[$TMP_field]=$TMP_row[$TMP_field."_".$lang];
    $TMP_content=$TMP_row[$TMP_field];
    sql_free_result($TMP_result);
    return $TMP_content;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_showDateTime($value) {
    $arr = explode(" ", $value);
    $valuedate=$arr[0];
    $valuetime=$arr[1];
    $TMP_cont.=RAD_showDate($valuedate);
    $TMP_cont.="&nbsp;&nbsp;";
    $TMP_cont.=RAD_showTime($valuetime);
    return $TMP_cont;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_showShortDateTime($value) {
    $arr = explode(" ", $value);
    $valuedate=$arr[0];
    $valuetime=$arr[1];
    $TMP_cont.=RAD_showShortDate($valuedate);
    $TMP_cont.="&nbsp;&nbsp;";
    $TMP_cont.=RAD_showTime($valuetime);
    return $TMP_cont;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_showTimeInt($value) {
    if ($value == "" || $value == "0" || $value == 0 || $value == -1) {
        $value="00:00:00";
    } else {
        $TMP_hour=floor($value/3600);
        $TMP_min=floor(($value-$TMP_hour*3600)/60);
        $TMP_sec=$value-$TMP_hour*3600-$TMP_min*60;

        $value=$TMP_hour.":".$TMP_min.":".$TMP_sec;
    }
    $TMP_cont.=RAD_showTime($value);
    return $TMP_cont;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_showTime($value) {
    global $RAD_noSeconds;

    if (strlen($value)>8) { // Viene con fechaaa
        $TMP_arr=explode(" ",$value);
        $value=$TMP_arr[1];
    }

    $arr = explode(":", $value);
    $hour=$arr[0]; if (strlen($hour)==1) $hour="0".$hour;
    $min=$arr[1]; if (strlen($min)==1) $min="0".$min;
    $sec=$arr[2]; if (strlen($sec)==1) $sec="0".$sec;
    $TMP_cont.=$hour.":".$min;
    if ($RAD_noSeconds!="1") $TMP_cont.=":".$sec;
    if ($TMP_cont=="::" || $TMP_cont==":") $TMP_cont="";
    return $TMP_cont;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_showDate($value) {
    $arr = explode(" ", $value);
    if (count($arr)>1) $value=$arr[0];
    $arr = explode("-", $value);
    $TMP_year=$arr[0];
    $TMP_month=$arr[1]; if (strlen($TMP_month)==1) $TMP_month="0".$TMP_month;
    $nameMonth="_DEF_NLSMonth".$TMP_month;
    if (_DEF_TypeDate!="number") if (defined("$nameMonth")) $TMP_month=constant("$nameMonth");
    $TMP_day=$arr[2]; if (strlen($TMP_day)==1) $TMP_day="0".$TMP_day;
    $TMP_cont=$TMP_day."-".$TMP_month."-".$TMP_year;
    if (_DEF_TypeDate=="number") $TMP_cont=$TMP_day."/".$TMP_month."/".$TMP_year;
    if ($TMP_cont=="00-00-0000" || $TMP_cont=="--" || $TMP_cont=="//") $TMP_cont="";
    return $TMP_cont;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_showShortDate($value) {
    $arr = explode(" ", $value);
    if (count($arr)>1) $value=$arr[0];
    $arr = explode("-", $value);
    $TMP_year=$arr[0];
    $TMP_month=$arr[1]; if (strlen($TMP_month)==1) $TMP_month="0".$TMP_month;
    $nameMonth="_DEF_NLSMonth".$TMP_month;
    if (defined("$nameMonth")) $TMP_month=constant("$nameMonth");
    $TMP_day=$arr[2]; if (strlen($TMP_day)==1) $TMP_day="0".$TMP_day;
    $TMP_month=substr($TMP_month,0,3);
    $TMP_cont.=$TMP_day."-".$TMP_month."-".$TMP_year;
    return $TMP_cont;
}
/////////////////////////////////////////////////////////////////////////////////////
// fecha: AAAA-MM-DD
function RAD_gregorianDate($fecha) {
    $TMP_f=explode("-",$fecha);
    if (count($TMP_f)==3) {
        $fyear=$TMP_f[0];
        $fmonth=$TMP_f[1];
        $fday=$TMP_f[2];
    } else {
        $fyear=substr($fecha,0,4);
        $fmonth=substr($fecha,5,2);
        $fday=substr($fecha,8,2);
    }
    if ($fyear=="" && $fmonth=="" && $fday=="") return "";
    if ($fyear=="0000" && $fmonth=="00" && $fday=="00") return "0";

    if (function_exists("gregoriantojd")) { // si dispone de funcion gregoriana la usa, sino hace calculo propio
        $inc=0;
        if ($fday=="00"||$fday=="0") {
            $fday="01";
            $inc=-1;
        }
        $gfecha=gregoriantojd($fmonth*1,$fday*1,$fyear*1)+$inc;
        return $gfecha;
    }

    $mytime=mktime(0,0,0,$fmonth*1,$fday*1,$fyear*1);
    if ($mytime<0) return "";
    $mydate=getdate($mytime);
    $myday=$mydate['yday'];

//  $gregoriano=$myday;
    $gregoriano=($fyear*365)+$myday;
    return $gregoriano;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_julianDate($fecha) {
    if ($fecha>99999998 || $fecha<1) return "";

    if (function_exists("jdtogregorian") && $fecha>766900) { // si despone de funcion juliana y la fecha es superior a calculo propio
        $xfecha=jdtogregorian($fecha);
        $TMP_f=explode("/",$xfecha);
        if (count($TMP_f)==3) {
            $fyear=$TMP_f[2];
            $fmonth=$TMP_f[0]; if (strlen($fmonth)==1) $fmonth="0".$fmonth;
            $fday=$TMP_f[1]; if (strlen($fday)==1) $fday="0".$fday;
        } else {
            $fyear=substr($xfecha,6,4);
            $fmonth=substr($xfecha,3,2);
            $fday=substr($xfecha,0,2);
        }
        $juliana=$fyear."-".$fmonth."-".$fday;
        return $juliana;
    }

    $fyear=floor($fecha/365);
    $yday=$fecha-($fyear*365)+1;
//  if (($fyear-1)%4==0 && $yday==1) { $fyear--; $yday=366; } // bisiesto
    $mytime=mktime(0,0,0,1,$yday,$fyear);
    $mydate=getdate($mytime);
    $myday=$mydate['mday'];
    $mymonth=$mydate['mon'];

    $juliana=date("Y-m-d",$mytime);
    return $juliana;

//  $juliana=$fyear."-".$mymonth."-".$myday;
//  $juliana=$fyear."-".$yday;
//  return $juliana;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_daysinmonth($month, $year) {
    if ($month>0 && $month<13 && $year>1900) {
    $days=31;
        while(!checkdate($month, $days, $year)) $days--;
    } else $days=31;
    return $days;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_weekMonthYear2Date($week,$month,$year) {
    $numdiasmes=date("t",mktime (0,0,0,$month,1,$year));
    $numsem="1";
    $firstday=0;
    $lastday=0;
    for ($ki=1; $ki<($numdiasmes+1); $ki++) {
        $numdiasemana=date("w",mktime (0,0,0,$month,$ki,$year));
        if ($numdiasemana==1) { // lunes
            if ($numsem==$week && $firstday==0) $firstday=$ki;
        }
        if ($numdiasemana==0) { // domingo
            if ($numsem==$week) $lastday=$ki;
            $numsem++;
        }
    }
    if ($lastday==0) {
        $Glastday=RAD_gregorianDate($year."-".$month."-".$numdiasmes);
        for ($ki=$Glastday; $ki<($Glastday+8); $ki++) {
            $numdiasemana=date("w",strtotime(RAD_julianDate($ki)));
            if ($numdiasemana==0) { // domingo
                $Glastday=$ki;
                break;
            }
        }
        $weekdates['lastday'] = RAD_julianDate($Glastday);
    } else {
        if (strlen($lastday)<2) $lastday="0".$lastday;
        $weekdates['lastday'] = $year."-".$month."-".$lastday;
    }
    if ($firstday==0) {
        $Gfirstday=RAD_gregorianDate($year."-".$month."-01");
        for ($ki=$Gfirstday; $ki>($Gfirstday-8); $ki--) {
            $numdiasemana=date("w",strtotime(RAD_julianDate($ki)));
            if ($numdiasemana==1) { // lunes
                $Gfirstday=$ki;
                break;
            }
        }
        $weekdates['firstday'] = RAD_julianDate($Gfirstday);
    } else {
        if (strlen($firstday)<2) $firstday="0".$firstday;
        $weekdates['firstday'] = $year."-".$month."-".$firstday;
    }

    return $weekdates;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_week_to_dates($weekyear, $year){
    $timeone=mktime(0,0,0,1,1,$year);
    $dayone=date("w",$timeone)-1;
    if ($dayone==-1) $dayone=6;
//xx    $weeks=$weekyear;
    $weeks=$weekyear-1;
    $timeiniweek=$timeone+$weeks*7*24*60*60-$dayone*24*60*60;
    $timeendweek=$timeone+$weeks*7*24*60*60+(6-$dayone)*24*60*60;
    $weekdates['firstday'] = date("Y-m-d",$timeiniweek);
    $weekdates['lastday'] = date("Y-m-d",$timeendweek);

//  $searchdate = mktime(0,0,0,12,20,$year-1);  //We start some time into prev year
//  $searchdate = strtotime("+".($weekyear-1)." week",$searchdate);  //Then we advance $weekyear-1 weeks ahead
//  $found=false;
//  while ($found==false){
//      if (date("W",$searchdate) == $weekyear)
//          $found = true;
//      else
//          $searchdate = strtotime("+1 day",$searchdate);
//  }
//  $weekdates['firstday'] = date("Y-m-d",$searchdate);
//  $weekdates['lastday'] = date("Y-m-d",strtotime("+6 day",$searchdate));

    return $weekdates;
}

/////////////////////////////////////////////////////////////////////////////////////
function RAD_showcalendar($year,$month,$week,$day,$URLROI) {
global $weekyear;
    if ($year=="") $year=date("Y");
    if ($month=="") $month=date("m");
    if($day>0) {
        $dias=array(_SUNDAY, _MONDAY, _TUESDAY, _WEDNESDAY, _THURSDAY, _FRIDAY, _SATURDAY);
        $numdiasemana=date("w",mktime (0,0,0,$month,$day,$year));
        $nombredia=$dias[$numdiasemana];
        $TMP_result=RAD_showcalday($year,$month*1,$day*1,$URLROI);
        $fechaHoy=$nombredia.", ".$day." ".$TMP_namemonth." ".$year;
    } else if ($week>0||$weekyear>0) {
        $TMP_fechasinifin=RAD_weekMonthYear2Date($week,$month,$year);
        $TMP_diaini=substr($TMP_fechasinifin[firstday],8,2);
        $TMP_diafin=substr($TMP_fechasinifin[lastday],8,2);
        $TMP_result=RAD_showcalweek($year,$month*1,$week,$weekyear,$URLROI);
        $fechaHoy=$TMP_diaini."-".$TMP_diafin." ".$TMP_namemonth." ".$year;
    } else if($month>0) {
        $TMP_result=RAD_showcalmonth($year,$month*1,$week,$URLROI);
        $fechaHoy=$TMP_namemonth." ".$year;
    } else {
        $TMP_result=RAD_showcalyear($year,$URLROI);
        $fechaHoy=$year;
    }
    return $TMP_result;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_showcalyear($ano,$ROI) {
global $PHP_SELF, $V_dir, $V_mod, $V_idmod, $RAD_taskscalendar, $RAD_taskscalendarmin, $SESSION_SID;
    if($ano == 0 || $ano == "") { $ano = date("Y"); }
    $prev_ano = $ano-1;
    $next_ano = $ano+1;

    $TMP_RAD_taskscalendar0=$RAD_taskscalendar["0"];
    $RAD_taskscalendar["0"]="";
    $TMP_result.="<table width=100% cellpadding=0 cellspacing=1 border=0><tr><td align=left>\n";
    $TMP_result.="&nbsp;<a href=\"$PHP_SELF?".$SESSION_SID."V_dir=$V_dir&V_idmod=$V_idmod&V_mod=$V_mod&year=$prev_ano&month=0$ROI\">&lt; $prev_ano</a>";
    $TMP_result.="</td><td colspan=3 align=right><a href=\"$PHP_SELF?".$SESSION_SID."V_dir=$V_dir&V_idmod=$V_idmod&V_mod=$V_mod&year=$next_ano&month=0$ROI\">$next_ano &gt;</a>&nbsp;";
    $TMP_result.="</td></tr><tr><td valign=top>\n";
    $TMP_result.=RAD_showcalmonth($ano, 1, "", $ROI);
    $TMP_result.="</td><td valign=top>\n";
    $TMP_result.=RAD_showcalmonth($ano, 2, "", $ROI);
    $TMP_result.="</td><td valign=top>\n";
    $TMP_result.=RAD_showcalmonth($ano, 3, "", $ROI);
    $TMP_result.="</td><td valign=top>\n";
    $TMP_result.=RAD_showcalmonth($ano, 4, "", $ROI);
    $TMP_result.="</td></tr><tr><td valign=top>\n";
    $TMP_result.=RAD_showcalmonth($ano, 5, "", $ROI);
    $TMP_result.="</td><td valign=top>\n";
    $TMP_result.=RAD_showcalmonth($ano, 6, "", $ROI);
    $TMP_result.="</td><td valign=top>\n";
    $TMP_result.=RAD_showcalmonth($ano, 7, "", $ROI);
    $TMP_result.="</td><td valign=top>\n";
    $TMP_result.=RAD_showcalmonth($ano, 8, "", $ROI);
    $TMP_result.="</td></tr><tr><td valign=top>\n";
    $TMP_result.=RAD_showcalmonth($ano, 9, "", $ROI);
    $TMP_result.="</td><td valign=top>\n";
    $TMP_result.=RAD_showcalmonth($ano, 10, "", $ROI);
    $TMP_result.="</td><td valign=top>\n";
    $TMP_result.=RAD_showcalmonth($ano, 11, "", $ROI);
    $TMP_result.="</td><td valign=top>\n";
    $TMP_result.=RAD_showcalmonth($ano, 12, "", $ROI);

    $TMP_result.="</td></tr></table>\n";
    if ($TMP_RAD_taskscalendar0!="") {
    //$TMP_result="<table width=100% cellpadding=0 cellspacing=1 border=0><tr><td width=80% valign=top>".$TMP_result."</td><td valign=top>".$TMP_RAD_taskscalendar0."</td></tr></table>\n";
    $TMP_result=$TMP_result.$TMP_RAD_taskscalendar0;
    }
    return $TMP_result;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_showcalmonth($ano,$mes,$week,$ROI) {
global $PHP_SELF, $V_dir, $V_mod, $V_idmod, $RAD_taskscalendar, $RAD_taskscalendarmin, $RAD_showcalnohours, $SESSION_SID, $noshowlinkmonth, $noshowlinkday, $noshowlinkweek;
    if ($week>0) {
    $TMP=RAD_showcalweek($ano,$mes,$week,"",$ROI);
    return $TMP;
    }
    $TMP_RAD_taskscalendar0=$RAD_taskscalendar["0"];
    $RAD_taskscalendar["0"]="";
    if($mes == 0 || $mes == "") { $mes = date("m"); }
    $xmes=$mes; if (strlen($xmes)<2) $xmes="0".$xmes; if (strlen($xmes)<2) $xmes="0".$xmes;
    if (!(isset($ano))||($ano=="")) $ano=date("Y",mktime (0,0,0,date ("n"),1,date("Y")));
    if (!(isset($mes))||($mes=="")) $mes=date("n",mktime (0,0,0,date ("n"),1,date("Y")));
    if ($mes<1 || $mes>12) $mes=1;

    $weekprev="";
    $weekpost="";
    if ($mes=="1") {
        $mespost=$mes+1;
            $anopost=$ano;
        $mesprev=12;
        $anoprev=$ano-1;
    } else if ($mes=="12") {
        $mespost=1;
        $anopost=$ano+1;
        $mesprev=$mes-1;
        $anoprev=$ano;
    } else {
        $mespost=$mes+1;
        $anopost=$ano;
        $mesprev=$mes-1;
        $anoprev=$ano;
    }

    for ($kj=0; $kj<24; $kj++) { // pasa las tareas de minutos a las tareas de hora
    $xhora=$kj; if (strlen($xhora)==1) $xhora="0".$xhora;
    if (_DEF_CAL_MINUTES=="5"||_DEF_CAL_MINUTES=="10"||_DEF_CAL_MINUTES=="15"||_DEF_CAL_MINUTES=="20"||_DEF_CAL_MINUTES=="30") {
        $TMP_incr=_DEF_CAL_MINUTES;
        for ($kmin=0; $kmin<60; $kmin=$kmin+$TMP_incr) {
            $xmin=$kmin; if (strlen($xmin)==1) $xmin="0".$xmin;
            $RAD_taskscalendar{$xcalendardia.$xcalendarmes.$ano.$xhora}.=$RAD_taskscalendarmin{$xcalendardia.$xcalendarmes.$ano.$xhora.$xmin};
            $RAD_taskscalendarmin{$xcalendardia.$xcalendarmes.$ano.$xhora.$xmin}="";
        }
    }
    }

    $primer_dia_mes=date("w",mktime (0,0,0,$mes,1,$ano));
    if ($primer_dia_mes==0) {$primer_dia_mes=7;}  // porque empieza en domingo
    $dias_mes=date("d",mktime (0,0,0,$mes+1,0,$ano));

    $meses=array("",_DEF_NLSMonth01,_DEF_NLSMonth02,_DEF_NLSMonth03,_DEF_NLSMonth04,_DEF_NLSMonth05,_DEF_NLSMonth06,_DEF_NLSMonth07,_DEF_NLSMonth08,_DEF_NLSMonth09,_DEF_NLSMonth10,_DEF_NLSMonth11,_DEF_NLSMonth12);
    $nummes=$mes*1;
    $semana=1;
    if ($noshowlinkmonth!="") $TMP_result.="<table width=100% border=0 cellpadding=0 cellspacing=1><tr align=center><td></td>
    <td colspan=7 bgcolor=#006699 nowrap><font style='color:white'><b>".$meses[$nummes]."</font></b> <font style='color:white'><b>".$ano."</font></b></td></tr>\n";
    else $TMP_result.="<table width=100% border=0 cellpadding=0 cellspacing=1><tr align=center><td></td>\n<td bgcolor=#006699>
    <a href=\"$PHP_SELF?".$SESSION_SID."V_dir=$V_dir&V_idmod=$V_idmod&V_mod=$V_mod&week=$weekprev&month=$mesprev&year=$anoprev$ROI\"><img src=\"images/flechaizq.gif\" border=0 width=6 height=17 align=TOP></a></td>
    <td colspan=5 bgcolor=#006699 nowrap>".$TMP_litsemana."<a href=\"$PHP_SELF?".$SESSION_SID."V_dir=$V_dir&V_idmod=$V_idmod&V_mod=$V_mod&month=$mes&year=$ano$ROI\"><font style='color:white'><b>".$meses[$nummes]."</font></b></a> <a href=\"$PHP_SELF?V_dir=$V_dir&V_idmod=$V_idmod&V_mod=$V_mod&month=0&year=".$ano.$ROI.$SESSION_SID."\"><font style='color:white'><b>".$ano."</a></font></b></td>
    <td bgcolor=#006699 valign=top>
    <a href=\"$PHP_SELF?".$SESSION_SID."V_dir=$V_dir&V_idmod=$V_idmod&V_mod=$V_mod&week=$weekpost&month=$mespost&year=$anopost$ROI\"><img src=\"images/flechader.gif\" border=0 width=6 height=17></a></td></tr>\n";
    $TMP_result.="<tr align=center>
    <td></td>
    <td bgcolor=#ffcc66 width=15%>L</td>
    <td bgcolor=#ffcc66 width=15%>M</td>
    <td bgcolor=#ffcc66 width=15%>X</td>
    <td bgcolor=#ffcc66 width=15%>J</td>
    <td bgcolor=#ffcc66 width=15%>V</td>
    <td bgcolor=#ffcc66 width=10%>S</td>
    <td bgcolor=#ffcc66 width=10%>D</td>\n</tr>\n";

    if ($xdia=="") {
    $TMPnumsemana=date("W",strtotime("$ano-$xmes-01"));
    if (date("W",strtotime($ano."-01-01"))>1) $TMPnumsemana++; // Si el dia uno no empieza en la primera semana hay que sumar uno a la semana
    } else {
    $TMPnumsemana=date("W",strtotime("$ano-$xmes-$xdia"));
    if (date("W",strtotime($ano."-01-01"))>1) $TMPnumsemana++; // Si el dia uno no empieza en la primera semana hay que sumar uno a la semana
    }
    if ($TMPnumsemana>50 && $xmes=="01") $TMPnumsemana=1;
    $TMPnumsemana = "<span style='color:white;font-weight:bold'>" . sprintf("%02d",$TMPnumsemana) . "</span>";
    if ($noshowlinkweek!="") $TMP_result.="<tr align=center><td></td>\n";
    else $TMP_result.="<tr align=center><td bgcolor=#006699 valign=top><a href=\"$PHP_SELF?".$SESSION_SID."V_dir=$V_dir&V_idmod=$V_idmod&V_mod=$V_mod&month=$mes&year=$ano&week=$semana$ROI\"> $TMPnumsemana <img src=\"images/flechader.gif\" border=0 width=6 height=17></a></td>\n";

    for ($i=1;$i<$primer_dia_mes;$i++) {
    $TMP_result.="<td bgcolor=#ffeebb></td>";
    }
    for ($j=1;$i<$dias_mes+$primer_dia_mes;$i++,$j++) {
    // salto de semana
    $xdia=$j; if (strlen($xdia)<2) $xdia="0".$xdia; if (strlen($xdia)<2) $xdia="0".$xdia;
    $TMP_colordia="bgcolor=white";
    if ($FECHASHORA{$xdia.$xmes.$ano."F"}!="") $TMP_colordia="bgcolor=#ffcc66";
    if (!(($i)%7)&&$j>0) $TMP_colordia="bgcolor=#ffcc66";
    if (!(($i-1)%7)&&$j>1) {
        $semana++;
        if ($xdia=="") $TMPnumsemana=date("W",strtotime("$ano-$xmes-01"));
        else $TMPnumsemana=date("W",strtotime("$ano-$xmes-$xdia"));
        if (date("W",strtotime($ano."-01-01"))>1) $TMPnumsemana++; // Si el dia uno no empieza en la primera semana hay que sumar uno a la semana
        if ($TMPnumsemana>50 && $xmes=="01") $TMPnumsemana=1;
        $TMPnumsemana = "<span style='color:white;font-weight:bold'>" . sprintf("%02d",$TMPnumsemana) . "</span>";
        if ($noshowlinkweek!="") $TMP_result.="</tr>\n<tr align=center><td></td>";
        else $TMP_result.="</tr><tr align=center><td bgcolor=#006699 valign=top nowrap><a href=\"$PHP_SELF?".$SESSION_SID."V_dir=$V_dir&V_idmod=$V_idmod&V_mod=$V_mod&month=$mes&year=$ano&week=$semana$ROI\"> $TMPnumsemana <img src=\"images/flechader.gif\" border=0 width=6 height=17></a></td>\n";
    }
    if ($noshowlinkday!="") $TMP_result.="<td valign=top $TMP_colordia>".$j."<br>\n <table width=100% border=0 cellpadding=0 cellspacing=1 bgcolor=#f0f0f0>";
    else $TMP_result.="<td valign=top $TMP_colordia><a href=\"$PHP_SELF?".$SESSION_SID."V_dir=$V_dir&V_idmod=$V_idmod&V_mod=$V_mod&month=$mes&year=$ano&day=$j$ROI\">".$j."</a><br>\n <table width=100% border=0 cellpadding=0 cellspacing=1 bgcolor=#f0f0f0>";
    if ($RAD_showcalnohours==true) $TMP_result.="<tr><td colspan=7 nowrap $TMP_colordia>";
    for ($jj=0;$jj<24;$jj++) {
        $xhora=$jj; if (strlen($xhora)<2) $xhora="0".$xhora;
        if ($RAD_showcalnohours!=true && $RAD_taskscalendar{$xdia.$xmes.$ano.$xhora}!="") $TMP_result.="<tr><td colspan=7 nowrap $TMP_colordia>";
        $TMP_result.=$RAD_taskscalendar{$xdia.$xmes.$ano.$xhora};
        if ($RAD_showcalnohours!=true && $RAD_taskscalendar{$xdia.$xmes.$ano.$xhora}!="") $TMP_result.="</td></tr>\n";
    }
    if ($RAD_showcalnohours==true) $TMP_result.="</td></tr>\n";
    if ($RAD_taskscalendar{$xdia.$xmes.$ano."24"}!="") $TMP_result.="<tr><td colspan=7 nowrap $TMP_colordia>".$RAD_taskscalendar{$xdia.$xmes.$ano."24"}."</td></tr>\n";
    $TMP_result.="</table></td>\n";
    }
    while (($i-1)%7)  {
    // restantes casillas del mes sin dia
    $TMP_result.="<td bgcolor=#ffeebb></td>";
        $i++;
    }
    $TMP_result.="</tr></table>";
    if ($TMP_RAD_taskscalendar0!="") {
    //$TMP_result="<table width=100% cellpadding=0 cellspacing=1 border=0><tr><td width=80% valign=top>".$TMP_result."</td>\n<td valign=top>".$TMP_RAD_taskscalendar0."</td></tr></table>\n";
    $TMP_result=$TMP_result.$TMP_RAD_taskscalendar0;
    }
    return $TMP_result;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_showcalweek($ano,$mes,$week,$weekyear,$ROI) {
global $PHP_SELF, $V_dir, $V_mod, $V_idmod, $RAD_taskscalendar, $RAD_taskscalendarmin, $RAD_showcalnohours, $SESSION_SID, $noshowlinkmonth, $noshowlinkday, $noshowlinkweek;
global $semanacompleta;
    if ($semanacompleta!="" || _DEF_CAL_WEEKMONTH!="1") {
    $TMP_result=RAD_showcalweekmonths($ano,$mes,$week,$weekyear,$ROI); // muestra semana de uno o varios meses
    return $TMP_result;
    }

    if (!(isset($ano))||($ano=="")) $ano=date("Y",mktime (0,0,0,date ("n"),1,date("Y")));
    if (!(isset($mes))||($mes=="")||($mes==0)||($mes<1)||($mes>12)) $mes=date("n",mktime (0,0,0,date ("n"),1,date("Y")));

    if ($weekyear>0 && !$week>0) { // calcula el mes y semana a mostrar
    $TMP_weekdates=RAD_week_to_dates($weekyear, $ano);
    $TMP_firstday=$TMP_weekdates['firstday']; // En formato "Y-m-d"
    $TMP_lastday=$TMP_weekdates['lastday'];
    $mes=substr($TMP_firstday,5,2);
    $TMP_greglastday=RAD_gregorianDate($TMP_lastday);
    $TMP_gregfirstmonthday=RAD_gregorianDate($ano."-".$mes."-01");
    $week=0;
    for ($i=$TMP_gregfirstmonthday; $i<=$TMP_greglastday; $i++) { // Calcula la semana, contando los domingos desde el primer dia del mes
        $TMP_julday=RAD_julianDate($i);
        $TMP_numday=date("w",strtotime($TMP_julday)); // numero de dia de la semana
        if ($TMP_numday=="0") $week++; // cuenta los domingos. Si es un domingo salta de semana
    }
    }
    if (!$week>0) $week=1; // la primera si no la encuentra por error
    if (!$weekyear>0) { // calcula el numero de semana dentro del anho
    $dia="31";
    $semana=1;
    for ($i=1; $i<32; $i++) {
        if ($semana==$week) $dia=$i; // alcanzada el final de la semana se pone ese dia
        $TMP_numday=date("w",strtotime($ano."-".$mes."-".$i)); // numero de dia de la semana
        if ($TMP_numday=="0") $semana++; // cuenta los domingos. Si es un domingo salta de semana
    }
    $weekyear=date("W",strtotime($ano."-".$mes."-".$dia));
    if (date("W",strtotime($ano."-01-01"))>1) $weekyear++; // Si el dia uno no empieza en la primera semana hay que sumar uno a la semana
//  $TMP_numday=date("w",strtotime($ano."-".$mes."-".$dia));
//  if ($TMP_numday=="0") $weekyear--; // si el dia es Domingo se deja el numero de semana que devuelve "W"
    if ($weekyear>50 && ($mes=="01"||$mes="1")) $weekyear=1;
    }
    $TMP_weekdates=RAD_week_to_dates($weekyear, $ano);
    $TMP_firstday=$TMP_weekdates['firstday']; // En formato "Y-m-d"
    $TMP_lastday=$TMP_weekdates['lastday'];

    $TMP_RAD_taskscalendar0=$RAD_taskscalendar["0"];
    $RAD_taskscalendar["0"]="";
    $xmes=$mes; if (strlen($xmes)<2) $xmes="0".$xmes; if (strlen($xmes)<2) $xmes="0".$xmes;

    for ($ki=1; $ki<8; $ki++) { // buscamos primer domingo del mes (primera semana)
    $nomdia=date("w",mktime(0,0,0,$mes,$ki,$ano));
    if ($nomdia==0) $numdomingo=$ki;
    }
    $numlunes=$numdomingo+($week-1)*7-6; // lunes que se muestra (hipotetico)
    $numdomingo=$numdomingo+($week-1)*7; // domingo que se muestra (hipotetico)
    if ($week==1) $numdiasemana=$numdomingo;
    else $numdiasemana=$numlunes;
// Si $week vale uno se toma el Domingo como dia de la semana a mostrar, de lo contrario se toma el Lunes
    if (strlen($numdiasemana)==1) $numdiasemana="0".$numdiasemana;
    $xmes=$mes; if (strlen($xmes)==1) $xmes="0".$xmes;
    $fechadiasemana=$ano."/".$xmes."/".$numdiasemana;
    $Gfechadiasemana=RAD_gregorianDate($fechadiasemana);
    $weekpost=$week+1;
    $weekprev=$week-1;

    $Gfechadiasemanapost=$Gfechadiasemana+7;
    $fechapost=RAD_julianDate($Gfechadiasemanapost);
    $anopost=substr($fechapost,0,4);
    $mespost=substr($fechapost,5,2);
    if ($mespost!=$mes) $weekpost=1;

//OJO   $Gfechadiasemanaprev=$Gfechadiasemana-7;
    $Gfechadiasemanaprev=$Gfechadiasemana-1;
    $fechaprev=RAD_julianDate($Gfechadiasemanaprev);
    $anoprev=substr($fechaprev,0,4);
    $mesprev=substr($fechaprev,5,2);
    if ($weekprev=="0"&&$mesprev==$mes) $mesprev--;
    if ($mesprev!=$mes) {  // calculamos la ultima semana del mesprev
    $TMP_time=mktime(0,0,0,$mes,1,$ano)-2;
    $diaprev=date("d", $TMP_time);  // ultimo dia del mesprev
    for ($ki=1; $ki<8; $ki++) { // buscamos primer domingo del mesprev (primera semana)
        $nomdia=date("w",mktime(0,0,0,$mesprev,$ki,$ano));
        if ($nomdia==0) $numdomingoprev=$ki;
    }
    $weekprev=1;
    if ($diaprev>$numdomingoprev) $weekprev=(($diaprev-$numdomingoprev)/7)+1;
    if (floor($weekprev)!=$weekprev) $weekprev=floor($weekprev)+1;
    }

    $numsemana=date("W",strtotime($ano."-".$xmes."-".$numdiasemana));
    if (date("W",strtotime($ano."-01-01"))>1) $numsemana++; // Si el dia uno no empieza en la primera semana hay que sumar uno a la semana
    if ($numsemana>50 && $xmes=="01") $numsemana=1; // La primera semana del anho la pone como la ultima del anho anterior
    $TMP_litsemana="<font style='color:white'><b>Semana ".$numsemana.". </b></font>";
    if (_DEF_CAL_INI_HOUR!="" && _DEF_CAL_INI_HOUR!="_DEF_CAL_INI_HOUR") {
      $xcalendarmes=$mes; if (strlen($xcalendarmes)==1) $xcalendarmes="0".$xcalendarmes;
      if ($RAD_showcalnohours!=true) for ($ki=1; $ki<32; $ki++) {
        $xcalendardia=$ki; if (strlen($xcalendardia)==1) $xcalendardia="0".$xcalendardia;
        for ($kj=_DEF_CAL_INI_HOUR; $kj<_DEF_CAL_LAST_HOUR+1; $kj++) { // muestra las tareas de minutos dentro de la franja horario y en formato semana
        $xhora=$kj; if (strlen($xhora)==1) $xhora="0".$xhora;
        if (_DEF_CAL_MINUTES=="5"||_DEF_CAL_MINUTES=="10"||_DEF_CAL_MINUTES=="15"||_DEF_CAL_MINUTES=="20"||_DEF_CAL_MINUTES=="30") {
          $TMP_incr=_DEF_CAL_MINUTES;
          $TMP_filas="\n <table width=100% cellspacing='0' style='border-style:solid;border-color:black;border-width:1px;margin:0px'>\n";
              for ($kmin=0; $kmin<60; $kmin=$kmin+$TMP_incr) {
            $xmin=$kmin; if (strlen($xmin)==1) $xmin="0".$xmin;
            if ($xmin=="00") $TMP_URL="<a href=\"javascript:RAD_OpenW('$PHP_SELF?&V_dir=$V_dir&V_mod=tareascalendario&func=new&year=$ano&month=$xcalendarmes&day=$xcalendardia&subfunc=browse&headeroff=x&footeroff=x&menuoff=x&V_roi=$V_roi&V_horainicio=$xhora&V_mininicio=$xmin".$SESSION_SID."',800,400);\"><b>".$xhora."</b>:<small>".$xmin."</small></a>";
            else $TMP_URL="<a href=\"javascript:RAD_OpenW('$PHP_SELF?&V_dir=$V_dir&V_mod=tareascalendario&func=new&year=$ano&month=$xcalendarmes&day=$xcalendardia&subfunc=browse&headeroff=x&footeroff=x&menuoff=x&V_roi=$V_roi&V_horainicio=$xhora&V_mininicio=$xmin".$SESSION_SID."',800,400);\">&nbsp;&nbsp;&nbsp;<small>".$xmin."</small></a>";
            if ($xmin=="00") {
                $TMP_filas.=" <tr><td style='background:#E0E0E0;padding:2px'>";
                $RAD_taskscalendarmin{$xcalendardia.$xcalendarmes.$ano.$xhora."00"}.=$RAD_taskscalendar{$xcalendardia.$xcalendarmes.$ano.$xhora};
                $RAD_taskscalendar{$xcalendardia.$xcalendarmes.$ano.$xhora}="";
            } else $TMP_filas.=" <tr><td style='border-top-width:1px;border-top-color:#808080;border-top-style:solid;padding:2px;text-align:right'>";
            $TMP_filas.=" ".$TMP_URL."</td>";
            $TMP_filas.="<td bgcolor=white>".$RAD_taskscalendarmin{$xcalendardia.$xcalendarmes.$ano.$xhora.$xmin}."</td></tr>\n";
          }
          $TMP_filas.=" </table>\n";
          $RAD_taskscalendar{$xcalendardia.$xcalendarmes.$ano.$xhora}="<table cellpadding=0 cellspacing=0 border=0><tr><td valign=top>".$TMP_filas." </td></tr></table>\n";
              for ($kmin=0; $kmin<60; $kmin=$kmin+$TMP_incr) { // limpia los minutos ya mostrados
            $xmin=$kmin; if (strlen($xmin)==1) $xmin="0".$xmin;
            $RAD_taskscalendarmin{$xcalendardia.$xcalendarmes.$ano.$xhora.$xmin}="";
          }
        } else {
            $TMP_URL="<a href=\"javascript:RAD_OpenW('$PHP_SELF?&V_dir=$V_dir&V_mod=tareascalendario&func=new&year=$ano&month=$xcalendarmes&day=$xcalendardia&subfunc=browse&headeroff=x&footeroff=x&menuoff=x&V_roi=$V_roi&V_horainicio=$xhora".$SESSION_SID."',800,400);\">".$xhora."</a>";
            $RAD_taskscalendar{$xcalendardia.$xcalendarmes.$ano.$xhora}="<table cellpadding=0 cellspacing=0 border=0><tr><td>".$TMP_URL." </td>\n<td nowrap>".$RAD_taskscalendar{$xcalendardia.$xcalendarmes.$ano.$xhora}."</td>\n</tr></table>";
        }
        }
      }
    }

    for ($kj=0; $kj<24; $kj++) { // pasa las tareas de minutos a las tareas de hora
    $xhora=$kj; if (strlen($xhora)==1) $xhora="0".$xhora;
    if (_DEF_CAL_MINUTES=="5"||_DEF_CAL_MINUTES=="10"||_DEF_CAL_MINUTES=="15"||_DEF_CAL_MINUTES=="20"||_DEF_CAL_MINUTES=="30") {
        $TMP_incr=_DEF_CAL_MINUTES;
        for ($kmin=0; $kmin<60; $kmin=$kmin+$TMP_incr) {
            $xmin=$kmin; if (strlen($xmin)==1) $xmin="0".$xmin;
            $RAD_taskscalendar{$xcalendardia.$xcalendarmes.$ano.$xhora}.=$RAD_taskscalendarmin{$xcalendardia.$xcalendarmes.$ano.$xhora.$xmin};
            $RAD_taskscalendarmin{$xcalendardia.$xcalendarmes.$ano.$xhora.$xmin}="";
        }
    }
    }

    $primer_dia_mes=date("w",mktime (0,0,0,$mes,1,$ano));
    if ($primer_dia_mes==0) {$primer_dia_mes=7;}  // porque empieza en domingo
    $dias_mes=date("d",mktime (0,0,0,$mes+1,0,$ano));

    $meses=array("",_DEF_NLSMonth01,_DEF_NLSMonth02,_DEF_NLSMonth03,_DEF_NLSMonth04,_DEF_NLSMonth05,_DEF_NLSMonth06,_DEF_NLSMonth07,_DEF_NLSMonth08,_DEF_NLSMonth09,_DEF_NLSMonth10,_DEF_NLSMonth11,_DEF_NLSMonth12);
    $nummes=$mes*1;
    $semana=1;
    if ($noshowlinkmonth!="") $TMP_result.="<table width=100% border=0 cellpadding=0 cellspacing=1><tr align=center><td></td>
    <td colspan=7 bgcolor=#006699 nowrap><font style='color:white'><b>".$meses[$nummes]."</font></b> <font style='color:white'><b>".$ano."</font></b></td></tr>\n";
    else $TMP_result.="<table width=100% border=0 cellpadding=0 cellspacing=1><tr align=center><td></td>\n<td bgcolor=#006699>
    <a href=\"$PHP_SELF?".$SESSION_SID."V_dir=$V_dir&V_idmod=$V_idmod&V_mod=$V_mod&week=$weekprev&month=$mesprev&year=$anoprev$ROI\"><img src=\"images/flechaizq.gif\" border=0 width=6 height=17 align=TOP></a></td>
    <td colspan=5 bgcolor=#006699 nowrap>".$TMP_litsemana."<a href=\"$PHP_SELF?".$SESSION_SID."V_dir=$V_dir&V_idmod=$V_idmod&V_mod=$V_mod&month=$mes&year=$ano$ROI\"><font style='color:white'><b>".$meses[$nummes]."</font></b></a> <a href=\"$PHP_SELF?V_dir=$V_dir&V_idmod=$V_idmod&V_mod=$V_mod&month=0&year=".$ano.$ROI.$SESSION_SID."\"><font style='color:white'><b>".$ano."</a></font></b></td>
    <td bgcolor=#006699 valign=top>
    <a href=\"$PHP_SELF?".$SESSION_SID."V_dir=$V_dir&V_idmod=$V_idmod&V_mod=$V_mod&week=$weekpost&month=$mespost&year=$anopost$ROI\"><img src=\"images/flechader.gif\" border=0 width=6 height=17></a></td></tr>\n";
    $TMP_result.="<tr align=center>
    <td></td>
    <td bgcolor=#ffcc66 width=15%>L</td>
    <td bgcolor=#ffcc66 width=15%>M</td>
    <td bgcolor=#ffcc66 width=15%>X</td>
    <td bgcolor=#ffcc66 width=15%>J</td>
    <td bgcolor=#ffcc66 width=15%>V</td>
    <td bgcolor=#ffcc66 width=10%>S</td>
    <td bgcolor=#ffcc66 width=10%>D</td>\n</tr>\n";

    if ($week==$semana) {
    $TMP_result.="<tr align=center><td></td>";
    }
    for ($i=1;$i<$primer_dia_mes;$i++) {
    if ($week==$semana) $TMP_result.="<td bgcolor=#eeeebb></td>";  // rellena vacia la semana si no es del mismo mes, pero no deberia
    }
    for ($j=1;$i<$dias_mes+$primer_dia_mes;$i++,$j++) {
    // salto de semana
    $xdia=$j; if (strlen($xdia)<2) $xdia="0".$xdia; if (strlen($xdia)<2) $xdia="0".$xdia;
    $TMP_colordia="bgcolor=white";
    if ($FECHASHORA{$xdia.$xmes.$ano."F"}!="") $TMP_colordia="bgcolor=#ffcc66";
    if (!(($i)%7)&&$j>0) $TMP_colordia="bgcolor=#ffcc66";
    if (!(($i-1)%7)&&$j>1) {
        $semana++;
        if ($week==$semana) {
            $TMP_result.="<tr align=center><td></td>";
        }
    }
    if ($week==$semana) {
        if ($noshowlinkday!="") $TMP_result.="<td valign=top $TMP_colordia>".$j."<br>\n <table width=100% border=0 cellpadding=0 cellspacing=1 bgcolor=#f0f0f0>";
        else $TMP_result.="<td valign=top $TMP_colordia><a href=\"$PHP_SELF?".$SESSION_SID."V_dir=$V_dir&V_idmod=$V_idmod&V_mod=$V_mod&month=$mes&year=$ano&day=$j$ROI\">".$j."</a><br>\n <table width=100% border=0 cellpadding=0 cellspacing=1 bgcolor=#f0f0f0>";
        if ($RAD_showcalnohours==true) $TMP_result.="<tr><td colspan=7 nowrap $TMP_colordia>";
        for ($jj=0;$jj<24;$jj++) {
            $xhora=$jj; if (strlen($xhora)<2) $xhora="0".$xhora;
            if ($RAD_showcalnohours!=true && $RAD_taskscalendar{$xdia.$xmes.$ano.$xhora}!="") $TMP_result.="<tr><td colspan=7 nowrap $TMP_colordia>";
            $TMP_result.=$RAD_taskscalendar{$xdia.$xmes.$ano.$xhora};
            if ($RAD_showcalnohours!=true && $RAD_taskscalendar{$xdia.$xmes.$ano.$xhora}!="") $TMP_result.="</td></tr>\n";
        }
        if ($RAD_showcalnohours==true) $TMP_result.="</td></tr>\n";
        if ($RAD_taskscalendar{$xdia.$xmes.$ano."24"}!="") $TMP_result.="<tr><td colspan=7 nowrap $TMP_colordia>".$RAD_taskscalendar{$xdia.$xmes.$ano."24"}."</td></tr>\n";
        $TMP_result.="</table></td>\n";
    }
    }
    while (($i-1)%7)  {
    if ($week==$semana) $TMP_result.="<td bgcolor=#ffeebb></td>"; // restantes las restantes casillas si no es del mismo mes, pero no deberia
        $i++;
    }

    $TMP_result.="</tr></table>";
    if ($TMP_RAD_taskscalendar0!="") {
    //$TMP_result="<table width=100% cellpadding=0 cellspacing=1 border=0><tr><td width=80% valign=top>".$TMP_result."</td>\n<td valign=top>".$TMP_RAD_taskscalendar0."</td></tr></table>\n";
    $TMP_result=$TMP_result.$TMP_RAD_taskscalendar0;
    }
    return $TMP_result;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_showcalweekmonths($ano,$mes,$week,$weekyear,$ROI) {
global $PHP_SELF, $V_dir, $V_mod, $V_idmod, $RAD_taskscalendar, $RAD_taskscalendarmin, $RAD_showcalnohours, $SESSION_SID, $noshowlinkmonth, $noshowlinkday, $noshowlinkweek;

    $TMP_RAD_taskscalendar0=$RAD_taskscalendar["0"];
    $RAD_taskscalendar["0"]="";

    if (!(isset($ano))||($ano=="")) $ano=date("Y",mktime (0,0,0,date ("n"),1,date("Y")));
    if (!(isset($mes))||($mes=="")||($mes==0)||($mes<1)||($mes>12)) $mes=date("n",mktime (0,0,0,date ("n"),1,date("Y")));

    if ($weekyear>0 && !$week>0) { // calcula el mes y semana a mostrar
    $TMP_weekdates=RAD_week_to_dates($weekyear, $ano);
    $TMP_firstday=$TMP_weekdates['firstday']; // En formato "Y-m-d"
    $TMP_lastday=$TMP_weekdates['lastday'];
    $mes=substr($TMP_firstday,5,2);
    $TMP_greglastday=RAD_gregorianDate($TMP_lastday);
    $TMP_gregfirstmonthday=RAD_gregorianDate($ano."-".$mes."-01");
    $week=0;
    for ($i=$TMP_gregfirstmonthday; $i<=$TMP_greglastday; $i++) { // Calcula la semana, contando los domingos desde el primer dia del mes
        $TMP_julday=RAD_julianDate($i);
        $TMP_numday=date("w",strtotime($TMP_julday)); // numero de dia de la semana
        if ($TMP_numday=="0") $week++; // cuenta los domingos. Si es un domingo salta de semana
    }
    }
    if (!$week>0) $week=1; // la primera si no la encuentra por error
    if (!$weekyear>0) { // calcula el numero de semana dentro del anho
    $dia="31";
    $semana=1;
    for ($i=1; $i<32; $i++) {
        if ($semana==$week) $dia=$i; // alcanzada el final de la semana se pone ese dia
        $TMP_numday=date("w",strtotime($ano."-".$mes."-".$i)); // numero de dia de la semana
        if ($TMP_numday=="0") $semana++; // cuenta los domingos. Si es un domingo salta de semana
    }
    $weekyear=date("W",strtotime($ano."-".$mes."-".$dia));
    if (date("W",strtotime($ano."-01-01"))>1) $weekyear++; // Si el dia uno no empieza en la primera semana hay que sumar uno a la semana
//  $TMP_numday=date("w",strtotime($ano."-".$mes."-".$dia));
//  if ($TMP_numday=="0") $weekyear--; // si el dia es Domingo se deja el numero de semana que devuelve "W"
    if ($weekyear>50 && ($mes=="1"||$mes=="01")) $weekyear=1;
////    if ($weekyear==1 && $mes=="12") $weekyear=53; // correcion de calculo de semana anglosajona
    if ($weekyear==1 && $mes=="12") {
        $ano++; // correcion de calculo de semana anglosajona
        $mes="01";
    }
    }
    $TMP_weekdates=RAD_week_to_dates($weekyear, $ano);
    $TMP_firstday=$TMP_weekdates['firstday']; // En formato "Y-m-d"
    $TMP_lastday=$TMP_weekdates['lastday'];
    $mes_firstday=substr($TMP_firstday,5,2);
    $mes_lastday=substr($TMP_lastday,5,2);
    $TMP_gregfirstday=RAD_gregorianDate($TMP_firstday);
    $TMP_greglastday=RAD_gregorianDate($TMP_lastday);

    $TMP_gregpostday=$TMP_greglastday+1;
    $fechapost=RAD_julianDate($TMP_gregpostday);
    $anopost=substr($fechapost,0,4);
    $mespost=substr($fechapost,5,2);
    //if ($mespost=="01") $anopost++;
    $diapost=substr($fechapost,8,2);
    $weekpost=1;
    for($i=1; $i<=$diapost; $i++) {
    $TMP_numday=date("w",strtotime($anopost."-".$mespost."-".$i)); // numero de dia de la semana
    if ($TMP_numday=="0") $weekpost++; // cuenta los domingos. Si es un domingo salta de semana
    }

    $TMP_gregprevday=$TMP_gregfirstday-1;
    $fechaprev=RAD_julianDate($TMP_gregprevday);
    $anoprev=substr($fechaprev,0,4);
    $mesprev=substr($fechaprev,5,2);
    $diaprev=substr($fechaprev,8,2);
    $weekprev=1;
    for($i=1; $i<$diaprev; $i++) {
    $TMP_numday=date("w",strtotime($anoprev."-".$mesprev."-".$i)); // numero de dia de la semana
    if ($TMP_numday=="0") $weekprev++; // cuenta los domingos. Si es un domingo salta de semana
    }

    $mes1=""; $mes2="";
    $numsemana=$weekyear;
    $TMP_litsemana="<font style='color:white'><b>Semana ".$numsemana.". </b></font>";
    if (_DEF_CAL_INI_HOUR!="" && _DEF_CAL_INI_HOUR!="_DEF_CAL_INI_HOUR") {
      if ($RAD_showcalnohours!=true) for ($ki=$TMP_gregfirstday; $ki<=$TMP_greglastday; $ki++) {
        $TMP_fechapost=RAD_julianDate($ki);
        $mes=substr($TMP_fechapost,5,2); $dia=substr($TMP_fechapost,8,2);
        if ($mes1=="") $mes1=$mes; if ($mes1!=$mes) $mes2=$mes;
        $xcalendarmes=$mes; if (strlen($xcalendarmes)==1) $xcalendarmes="0".$xcalendarmes;
        $xcalendardia=$dia; if (strlen($xcalendardia)==1) $xcalendardia="0".$xcalendardia;
        for ($kj=_DEF_CAL_INI_HOUR; $kj<_DEF_CAL_LAST_HOUR+1; $kj++) { // muestra las tareas de minutos dentro de la franja horario y en formato semana
        $xhora=$kj; if (strlen($xhora)==1) $xhora="0".$xhora;
        if (_DEF_CAL_MINUTES=="5"||_DEF_CAL_MINUTES=="10"||_DEF_CAL_MINUTES=="15"||_DEF_CAL_MINUTES=="20"||_DEF_CAL_MINUTES=="30") {
          $TMP_incr=_DEF_CAL_MINUTES;
          $TMP_filas="\n <table width=100% cellspacing='0' style='border-style:solid;border-color:black;border-width:1px;margin:0px'>\n";
              for ($kmin=0; $kmin<60; $kmin=$kmin+$TMP_incr) {
            $xmin=$kmin; if (strlen($xmin)==1) $xmin="0".$xmin;
            if ($xmin=="00") $TMP_URL="<a href=\"javascript:RAD_OpenW('$PHP_SELF?&V_dir=$V_dir&V_mod=tareascalendario&func=new&year=$ano&month=$xcalendarmes&day=$xcalendardia&subfunc=browse&headeroff=x&footeroff=x&menuoff=x&V_roi=$V_roi&V_horainicio=$xhora&V_mininicio=$xmin".$SESSION_SID."',800,400);\"><b>".$xhora."</b>:<small>".$xmin."</small></a>";
            else $TMP_URL="<a href=\"javascript:RAD_OpenW('$PHP_SELF?&V_dir=$V_dir&V_mod=tareascalendario&func=new&year=$ano&month=$xcalendarmes&day=$xcalendardia&subfunc=browse&headeroff=x&footeroff=x&menuoff=x&V_roi=$V_roi&V_horainicio=$xhora&V_mininicio=$xmin".$SESSION_SID."',800,400);\">&nbsp;&nbsp;&nbsp;<small>".$xmin."</small></a>";
            if ($xmin=="00") {
                $TMP_filas.=" <tr><td style='background:#E0E0E0;padding:2px'>";
                $RAD_taskscalendarmin{$xcalendardia.$xcalendarmes.$ano.$xhora."00"}.=$RAD_taskscalendar{$xcalendardia.$xcalendarmes.$ano.$xhora};
                $RAD_taskscalendar{$xcalendardia.$xcalendarmes.$ano.$xhora}="";
            } else $TMP_filas.=" <tr><td style='border-top-width:1px;border-top-color:#808080;border-top-style:solid;padding:2px;text-align:right'>";
            $TMP_filas.=" ".$TMP_URL."</td>";
            $TMP_filas.="<td bgcolor=white>".$RAD_taskscalendarmin{$xcalendardia.$xcalendarmes.$ano.$xhora.$xmin}."</td></tr>\n";
          }
          $TMP_filas.=" </table>\n";
          $RAD_taskscalendar{$xcalendardia.$xcalendarmes.$ano.$xhora}="<table cellpadding=0 cellspacing=0 border=0><tr><td valign=top>".$TMP_filas." </td></tr></table>\n";
              for ($kmin=0; $kmin<60; $kmin=$kmin+$TMP_incr) { // limpia los minutos ya mostrados
            $xmin=$kmin; if (strlen($xmin)==1) $xmin="0".$xmin;
            $RAD_taskscalendarmin{$xcalendardia.$xcalendarmes.$ano.$xhora.$xmin}="";
          }
        } else {
            $TMP_URL="<a href=\"javascript:RAD_OpenW('$PHP_SELF?&V_dir=$V_dir&V_mod=tareascalendario&func=new&year=$ano&month=$xcalendarmes&day=$xcalendardia&subfunc=browse&headeroff=x&footeroff=x&menuoff=x&V_roi=$V_roi&V_horainicio=$xhora".$SESSION_SID."',800,400);\">".$xhora."</a>";
            $RAD_taskscalendar{$xcalendardia.$xcalendarmes.$ano.$xhora}="<table cellpadding=0 cellspacing=0 border=0><tr><td>".$TMP_URL." </td>\n<td nowrap>".$RAD_taskscalendar{$xcalendardia.$xcalendarmes.$ano.$xhora}."</td>\n</tr></table>";
        }
        }
        for ($kj=0; $kj<24; $kj++) { // pasa las tareas de minutos a las tareas de hora
        $xhora=$kj; if (strlen($xhora)==1) $xhora="0".$xhora;
        if (_DEF_CAL_MINUTES=="5"||_DEF_CAL_MINUTES=="10"||_DEF_CAL_MINUTES=="15"||_DEF_CAL_MINUTES=="20"||_DEF_CAL_MINUTES=="30") {
            $TMP_incr=_DEF_CAL_MINUTES;
            for ($kmin=0; $kmin<60; $kmin=$kmin+$TMP_incr) {
                $xmin=$kmin; if (strlen($xmin)==1) $xmin="0".$xmin;
                $RAD_taskscalendar{$xcalendardia.$xcalendarmes.$ano.$xhora}.=$RAD_taskscalendarmin{$xcalendardia.$xcalendarmes.$ano.$xhora.$xmin};
                $RAD_taskscalendarmin{$xcalendardia.$xcalendarmes.$ano.$xhora.$xmin}="";
            }
        }
        }
      }
    }

    if ($mes1=="") $mes1=$mes; if ($mes1!=$mes) $mes2=$mes;
    $meses=array("",_DEF_NLSMonth01,_DEF_NLSMonth02,_DEF_NLSMonth03,_DEF_NLSMonth04,_DEF_NLSMonth05,_DEF_NLSMonth06,_DEF_NLSMonth07,_DEF_NLSMonth08,_DEF_NLSMonth09,_DEF_NLSMonth10,_DEF_NLSMonth11,_DEF_NLSMonth12);
    $mes1=$mes1*1;  $mes2=$mes2*1;
    if ($mes1!=$mes2) {
    $xmes1=$mes1; if (strlen($xmes1)==1) $xmes1="0".$xmes1;
    $xmes2=$mes2; if (strlen($xmes2)==1) $xmes2="0".$xmes2;
    $LIT_meses=$meses[$mes1]."/".$meses[$mes2];
    $ALIT_meses="<a href=\"$PHP_SELF?".$SESSION_SID."V_dir=$V_dir&V_idmod=$V_idmod&V_mod=$V_mod&month=$xmes1&year=$ano$ROI\"><font style='color:white'><b>".$meses[$mes1]."</font></b></a>";
    $ALIT_meses.="<font style='color:white'><b> / </font></b><a href=\"$PHP_SELF?".$SESSION_SID."V_dir=$V_dir&V_idmod=$V_idmod&V_mod=$V_mod&month=$xmes2&year=$ano$ROI\"><font style='color:white'><b>".$meses[$mes2]."</font></b></a>";
    } else {
    $xmes1=$mes1; if (strlen($xmes1)==1) $xmes1="0".$xmes1;
    $LIT_meses=$meses[$mes1];
    $ALIT_meses="<a href=\"$PHP_SELF?".$SESSION_SID."V_dir=$V_dir&V_idmod=$V_idmod&V_mod=$V_mod&month=$xmes1&year=$ano$ROI\"><font style='color:white'><b>".$meses[$mes1]."</font></b></a>";
    }

    if ($noshowlinkmonth!="") $TMP_result.="<table width=100% border=0 cellpadding=0 cellspacing=1><tr align=center><td></td>
    <td colspan=7 bgcolor=#006699 nowrap><font style='color:white'><b>".$LIT_meses."</font></b> <font style='color:white'><b>".$ano."</font></b></td></tr>\n";
    else $TMP_result.="<table width=100% border=0 cellpadding=0 cellspacing=1><tr align=center><td></td>\n<td bgcolor=#006699>
    <a href=\"$PHP_SELF?".$SESSION_SID."V_dir=$V_dir&V_idmod=$V_idmod&V_mod=$V_mod&week=$weekprev&month=$mesprev&year=$anoprev$ROI\"><img src=\"images/flechaizq.gif\" border=0 width=6 height=17 align=TOP></a></td>
    <td colspan=5 bgcolor=#006699 nowrap>".$TMP_litsemana.$ALIT_meses." <a href=\"$PHP_SELF?V_dir=$V_dir&V_idmod=$V_idmod&V_mod=$V_mod&month=0&year=".$ano.$ROI.$SESSION_SID."\"><font style='color:white'><b>".$ano."</a></font></b></td>
    <td bgcolor=#006699 valign=top>
    <a href=\"$PHP_SELF?".$SESSION_SID."V_dir=$V_dir&V_idmod=$V_idmod&V_mod=$V_mod&week=$weekpost&month=$mespost&year=$anopost$ROI\"><img src=\"images/flechader.gif\" border=0 width=6 height=17></a></td></tr>\n";
    $TMP_result.="<tr align=center>
    <td></td>
    <td bgcolor=#ffcc66 width=15%>L</td>
    <td bgcolor=#ffcc66 width=15%>M</td>
    <td bgcolor=#ffcc66 width=15%>X</td>
    <td bgcolor=#ffcc66 width=15%>J</td>
    <td bgcolor=#ffcc66 width=15%>V</td>
    <td bgcolor=#ffcc66 width=10%>S</td>
    <td bgcolor=#ffcc66 width=10%>D</td>\n</tr>\n";
    $TMP_result.="<tr align=center><td></td>";
    for ($ki=$TMP_gregfirstday; $ki<=$TMP_greglastday; $ki++) {
    $TMP_fechapost=RAD_julianDate($ki);
    $xmes=substr($TMP_fechapost,5,2); $xdia=substr($TMP_fechapost,8,2); $dia=$xdia*1;
    $TMP_colordia="bgcolor=white";
    if ($FECHASHORA{$xdia.$xmes.$ano."F"}!="") $TMP_colordia="bgcolor=#ffcc66";
    if ($ki==$TMP_greglastday) $TMP_colordia="bgcolor=#ffcc66";
    if ($noshowlinkday!="") $TMP_result.="<td valign=top $TMP_colordia>".$dia."<br>\n <table width=100% border=0 cellpadding=0 cellspacing=1 bgcolor=#f0f0f0>";
    else $TMP_result.="<td valign=top $TMP_colordia><a href=\"$PHP_SELF?".$SESSION_SID."V_dir=$V_dir&V_idmod=$V_idmod&V_mod=$V_mod&month=$mes&year=$ano&day=$dia$ROI\">".$dia."</a><br>\n <table width=100% border=0 cellpadding=0 cellspacing=1 bgcolor=#f0f0f0>";
    if ($RAD_showcalnohours==true) $TMP_result.="<tr><td colspan=7 nowrap $TMP_colordia>";
    for ($jj=0;$jj<24;$jj++) {
        $xhora=$jj; if (strlen($xhora)<2) $xhora="0".$xhora;
        if ($RAD_showcalnohours!=true && $RAD_taskscalendar{$xdia.$xmes.$ano.$xhora}!="") $TMP_result.="<tr><td colspan=7 nowrap $TMP_colordia>";
        $TMP_result.=$RAD_taskscalendar{$xdia.$xmes.$ano.$xhora};
        if ($RAD_showcalnohours!=true && $RAD_taskscalendar{$xdia.$xmes.$ano.$xhora}!="") $TMP_result.="</td></tr>\n";
    }
    if ($RAD_showcalnohours==true) $TMP_result.="</td></tr>\n";
    if ($RAD_taskscalendar{$xdia.$xmes.$ano."24"}!="") $TMP_result.="<tr><td colspan=7 nowrap $TMP_colordia>".$RAD_taskscalendar{$xdia.$xmes.$ano."24"}."</td></tr>\n";
    $TMP_result.="</table></td>\n";
    }

    $TMP_result.="</tr></table>";
    if ($TMP_RAD_taskscalendar0!="") {
    //$TMP_result="<table width=100% cellpadding=0 cellspacing=1 border=0><tr><td width=80% valign=top>".$TMP_result."</td>\n<td valign=top>".$TMP_RAD_taskscalendar0."</td></tr></table>\n";
    $TMP_result=$TMP_result.$TMP_RAD_taskscalendar0;
    }
    return $TMP_result;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_showcalday($ano,$mes,$dia,$ROI) {
global $PHP_SELF, $V_dir, $V_mod, $V_idmod, $RAD_taskscalendar, $RAD_taskscalendarmin, $RAD_showcalnohours, $SESSION_SID;
    $xmes=$mes; if (strlen($xmes)<2) $xmes="0".$xmes; if (strlen($xmes)<2) $xmes="0".$xmes;
    $xdia=$dia; if (strlen($xdia)<2) $xdia="0".$xdia; if (strlen($xdia)<2) $xdia="0".$xdia;

    $TMP_RAD_taskscalendar0=$RAD_taskscalendar["0"];
    $RAD_taskscalendar["0"]="";
    if (!(isset($ano))||($ano=="")) $ano=date("Y",mktime (0,0,0,date ("n"),1,date("Y")));
    if (!(isset($mes))||($mes=="")) $mes=date("n",mktime (0,0,0,date ("n"),1,date("Y")));
    if (!(isset($dia))||($dia=="")) $dia=date("d",mktime (0,0,0,date ("n"),1,date("Y")));
    if ($mes<1 || $mes>12) $mes=1;

    $hoy=mktime(0,0,0,$mes,$dia,$ano);
    $ayer=$hoy-86400;
    $manana=$hoy+86400;
    $anopost=date("Y",$manana);
    $mespost=date("n",$manana);
    $diapost=date("d",$manana);
    $anoprev=date("Y",$ayer);
    $mesprev=date("n",$ayer);
    $diaprev=date("d",$ayer);

    $primer_dia_mes=date("w",mktime (0,0,0,$mes,1,$ano));
    if ($primer_dia_mes==0) {$primer_dia_mes=7;}  // porque empieza en domingo
    $dias_mes=date("d",mktime (0,0,0,$mes+1,0,$ano));

    $meses=array("",_DEF_NLSMonth01,_DEF_NLSMonth02,_DEF_NLSMonth03,_DEF_NLSMonth04,_DEF_NLSMonth05,_DEF_NLSMonth06,_DEF_NLSMonth07,_DEF_NLSMonth08,_DEF_NLSMonth09,_DEF_NLSMonth10,_DEF_NLSMonth11,_DEF_NLSMonth12);
    $nummes=$mes*1;

    $dias=array(_SUNDAY, _MONDAY, _TUESDAY, _WEDNESDAY, _THURSDAY, _FRIDAY, _SATURDAY);
    $numdiasemana=date("w",mktime (0,0,0,$nummes,$dia,$ano));
    $nombredia=$dias[$numdiasemana];

    $TMP_result.="<table width=100% border=0 cellpadding=0 cellspacing=1><tr align=center><td bgcolor=#006699>";
    $TMP_result.="<a href=\"$PHP_SELF?".$SESSION_SID."V_dir=$V_dir&V_idmod=$V_idmod&V_mod=$V_mod&month=$mesprev&year=$anoprev&day=$diaprev$ROI\"><img src=\"images/flechaizq.gif\" border=0 width=6 height=17 align=TOP></a></td>
    <td colspan=5 bgcolor=#006699 nowrap><font style='color:white'><b>".$nombredia.", ".$dia." </font><a href=\"$PHP_SELF?".$SESSION_SID."V_dir=$V_dir&V_idmod=$V_idmod&V_mod=$V_mod&month=$mes&year=$ano$ROI\"><font size=1 style='color:white'><b>".$meses[$nummes]."</b></a> </font><a href=\"$PHP_SELF?".$SESSION_SID."V_dir=$V_dir&V_idmod=$V_idmod&V_mod=$V_mod&month=0&year=$ano$ROI\"><font size=1 style='color:white'><b>".$ano."</a></b></font></td>
    <td bgcolor=#006699 valign=top>
    <a href=\"$PHP_SELF?".$SESSION_SID."V_dir=$V_dir&V_idmod=$V_idmod&V_mod=$V_mod&month=$mespost&year=$anopost&day=$diapost$ROI\"><img src=\"images/flechader.gif\" border=0 width=6 height=17></a></td></tr>\n";

    if (_DEF_CAL_INI_HOUR!="" && _DEF_CAL_INI_HOUR!="_DEF_CAL_INI_HOUR") {
      $xcalendarmes=$mes; if (strlen($xcalendarmes)==1) $xcalendarmes="0".$xcalendarmes;
      $xcalendardia=$dia; if (strlen($xcalendardia)==1) $xcalendardia="0".$xcalendardia;
      if ($RAD_showcalnohours!=true) for ($kj=_DEF_CAL_INI_HOUR; $kj<_DEF_CAL_LAST_HOUR+1; $kj++) {
        $xhora=$kj; if (strlen($xhora)==1) $xhora="0".$xhora;
//      $V_roi2=urlencode("horainicio='$xhora:00:00'");
        if (_DEF_CAL_MINUTES=="5"||_DEF_CAL_MINUTES=="10"||_DEF_CAL_MINUTES=="15"||_DEF_CAL_MINUTES=="20"||_DEF_CAL_MINUTES=="30") {
          $TMP_incr=_DEF_CAL_MINUTES;
          $TMP_filas="\n <table width=100% cellspacing='0' style='border-style:solid;border-color:black;border-width:1px;margin:0px'>\n";
              for ($kmin=0; $kmin<60; $kmin=$kmin+$TMP_incr) {
            $xmin=$kmin; if (strlen($xmin)==1) $xmin="0".$xmin;
            if ($xmin=="00") $TMP_URL="<a href=\"javascript:RAD_OpenW('$PHP_SELF?&V_dir=$V_dir&V_mod=tareascalendario&func=new&year=$ano&month=$xcalendarmes&day=$xcalendardia&subfunc=browse&headeroff=x&footeroff=x&menuoff=x&V_roi=$V_roi&V_horainicio=$xhora&V_mininicio=$xmin".$SESSION_SID."',800,400);\"><b>".$xhora."</b>:<small>".$xmin."</small></a>";
            else $TMP_URL="<a href=\"javascript:RAD_OpenW('$PHP_SELF?&V_dir=$V_dir&V_mod=tareascalendario&func=new&year=$ano&month=$xcalendarmes&day=$xcalendardia&subfunc=browse&headeroff=x&footeroff=x&menuoff=x&V_roi=$V_roi&V_horainicio=$xhora&V_mininicio=$xmin".$SESSION_SID."',800,400);\">&nbsp;&nbsp;&nbsp;<small>".$xmin."</small></a>";
            if ($xmin=="00") {
                $TMP_filas.=" <tr><td style='background:#E0E0E0;padding:2px'>";
                $RAD_taskscalendarmin{$xcalendardia.$xcalendarmes.$ano.$xhora."00"}.=$RAD_taskscalendar{$xcalendardia.$xcalendarmes.$ano.$xhora};
                $RAD_taskscalendar{$xcalendardia.$xcalendarmes.$ano.$xhora}="";
            } else $TMP_filas.=" <tr><td style='border-top-width:1px;border-top-color:#808080;border-top-style:solid;padding:2px;text-align:right'>";
            $TMP_filas.=" ".$TMP_URL."</td>";
            $TMP_filas.="<td bgcolor=white>".$RAD_taskscalendarmin{$xcalendardia.$xcalendarmes.$ano.$xhora.$xmin}."</td></tr>\n";
          }
          $TMP_filas.=" </table>\n";
          $RAD_taskscalendar{$xcalendardia.$xcalendarmes.$ano.$xhora}="<table cellpadding=0 cellspacing=0 border=0><tr><td valign=top>".$TMP_filas." </td></tr></table>\n";
        } else {
            $TMP_URL="<a href=\"javascript:RAD_OpenW('$PHP_SELF?&V_dir=$V_dir&V_mod=tareascalendario&func=new&year=$ano&month=$xcalendarmes&day=$xcalendardia&subfunc=browse&headeroff=x&footeroff=x&menuoff=x&V_roi=$V_roi&V_horainicio=$xhora".$SESSION_SID."',800,400);\">".$xhora."</a>";
            $RAD_taskscalendar{$xcalendardia.$xcalendarmes.$ano.$xhora}="<table cellpadding=0 cellspacing=0 border=0><tr><td>".$TMP_URL." </td>\n<td nowrap>".$RAD_taskscalendar{$xcalendardia.$xcalendarmes.$ano.$xhora}."</td>\n</tr></table>";
        }
    }
    for ($j=0;$j<24;$j++) {
        $hora=$j; if (strlen($hora)<2) $hora="0".$hora;
        if (_DEF_CAL_INI_HOUR!="" && _DEF_CAL_INI_HOUR!="_DEF_CAL_INI_HOUR" && $RAD_taskscalendar{$xdia.$xmes.$ano.$hora}=="") continue;
        $TMP_result.="<tr><td colspan=7 nowrap bgcolor=white>".$RAD_taskscalendar{$xdia.$xmes.$ano.$hora}."</td>\n</tr>\n";
    }
    } else {
    for ($j=0;$j<24;$j++) {
        $hora=$j; if (strlen($hora)<2) $hora="0".$hora;
        $TMP_result.="<tr><td colspan=7 nowrap bgcolor=white>".$hora." ".$RAD_taskscalendar{$xdia.$xmes.$ano.$hora}."</td></tr>\n";
    }
    }
    if ($RAD_taskscalendar{$xdia.$xmes.$ano."24"}!="") $TMP_result.="<tr><td colspan=7 nowrap bgcolor=white>".$RAD_taskscalendar{$xdia.$xmes.$ano."24"}."</td></tr>\n";
    $TMP_result.="</table>";
    if ($TMP_RAD_taskscalendar0!="") {
    //$TMP_result="<table width=100% cellpadding=0 cellspacing=1 border=0><tr><td width=80% valign=top>".$TMP_result."</td>\n<td valign=top>".$TMP_RAD_taskscalendar0."</td></tr></table>\n";
    $TMP_result=$TMP_result.$TMP_RAD_taskscalendar0;
    }
    return $TMP_result;
}

/////////////////////////////////////////////////////////////////////////////////////
function RAD_showfield($fdtype, $fextra="", $value) {
global $RAD_dbi, $dbname, $db, $V_typePrint, $RAD_maxPixelsShowImg;
    if (preg_match("/ajax/",$fdtype)) {
        include_once("functions_ajax.php");
        return RAD_showfieldajax($fdtype,$fextra,$value); // Delega en subfuncion de ajax
    }
    if ($RAD_maxPixelsShowImg>0) $TMP_maxPixelsShowImg=$RAD_maxPixelsShowImg;
    else $TMP_maxPixelsShowImg=320;

    if (preg_match("/date/",$fdtype) && substr($value,0,10)=="0000-00-00") return "";
    if ($fdtype=="image" || $fdtype=="file") {
        $files = explode("\n", trim($value));
        if (count($files) >1) {
            for ($k = 0; $k < count($files); $k++) {
                $files[$k]=str_replace("\n", "", $files[$k]);
                $files[$k]=str_replace("\r", "", $files[$k]);
                $TMP_pos=strpos($files[$k],".");
                $filenameprefix=substr($files[$k],0,$TMP_pos);
                $filenameprefix=str_replace("/","-",$filenameprefix);
                $Afilenameprefix=explode("-",$filenameprefix);
                if (count($Afilenameprefix)>5) {
                    $filenameprefix=$Afilenameprefix[2]."/".$Afilenameprefix[1]."/".$Afilenameprefix[0]." ".$Afilenameprefix[3].":".$Afilenameprefix[4].":".$Afilenameprefix[5];
                }
                $filename=substr($files[$k],$TMP_pos+1);
                if ($files[$k]!="") {
                    $TMP_filek=RAD_hideurlencodeFich($files[$k]);
                    if ($fdtype=="file") {
                        if ($TMP_cont!="") $TMP_cont.="<br>\n";
                        $TMP_cont.="<A HREF=\"".$TMP_filek."\" TARGET=_blank> [".$filenameprefix."] ".$filename."</A> ";
                    } elseif ($fdtype=="image") {
                        list($anchoimg, $altoimg, $tipo, $resto)=getimagesize("files/".$dbname."/".$files[$k]);
                        if ($fextra!="") {
                           $TMP_size=explode("x",$fextra);
                        }else{
                           $TMP_size[0]=$TMP_maxPixelsShowImg;
                           $TMP_size[1]=$TMP_maxPixelsShowImg;
                        }
                        if ($altoimg>$TMP_size[0] || $anchoimg>$TMP_size[1]) {
                            echo "\n<! Resize de ".$files[$k]." $anchoimg, $altoimg, $tipo>\n";
                            $TMP_fileresize=RAD_resizeImg("files/".$dbname."/".$files[$k], $TMP_size[0], $TMP_size[1]);
                            list($anchoimg, $altoimg, $tipo, $resto)=getimagesize($TMP_fileresize);
                            echo "<! a $TMP_fileresize $anchoimg, $altoimg, $tipo >\n";
                            $TMP_cont.="<A TARGET=_BLANK HREF=\"".$TMP_filek."\"><IMG SRC=\"".$TMP_fileresize."\"></A>\n";
                        } else {
                            $TMP_cont.="<IMG SRC=\"".$TMP_filek."\">\n";
                        }
                    }
                    if (_DEF_SendDoc=="1") $TMP_cont.=" <a href=\"javascript:RAD_OpenW('".$PHP_SELF."?func=none&menuoff=x&headeroff=x&footeroff=x&V_dir=".$V_dir."&V_mod=".$V_mod."&par0=0&dbname=".$dbname."&V_idmod=".$V_idmod."&subfunc=sendlist&doc=".rawurlencode($TMP_filek).$SESSION_SID."',800,600);\"><IMG SRC='images/mail.gif' BORDER=0 ALT='' TITLE=''></a>";
                }
            }
                        if ($fdtype=="file") {
                global $V_mod,$V_dir;
                $TMP_value=trim($value); $TMP_value=str_replace("\n","",$TMP_value); $TMP_value=str_replace("\r","",$TMP_value);
                            if ($TMP_value!="") $TMP_cont.= "<a href=\"javascript:RAD_OpenW('".$PHP_SELF."?V_dir=$V_dir&V_mod=$V_mod&V_typeZIP=X&V_value=".base64_encode($value)."');\" style='float:right;'><img src='images/save_as.gif' title='Descargar todos los documentos en ZIP'></a>";
                        }
        } else {
            $value=str_replace("\n", "", $value);
            $value=str_replace("\r", "", $value);
            $TMP_pos=strpos($value,".");
            $filenameprefix=substr($files[$k],0,$TMP_pos);
            $filenameprefix=str_replace("/","-",$filenameprefix);
            $Afilenameprefix=explode("-",$filenameprefix);
            if (count($Afilenameprefix)>5) {
                $filenameprefix=$Afilenameprefix[2]."/".$Afilenameprefix[1]."/".$Afilenameprefix[0]." ".$Afilenameprefix[3].":".$Afilenameprefix[4].":".$Afilenameprefix[5];
            }
            $filename=substr($value,$TMP_pos+1);
            if ($value!="") {
                $TMP_filek=RAD_hideurlencodeFich($value);
                if ($fdtype=="file") {
                    $TMP_cont.="<A HREF=\"".$TMP_filek."\" TARGET=_blank> [".$filenameprefix."] ".$filename."</A> \n";
                } else {
                    list($anchoimg, $altoimg, $tipo, $resto)=getimagesize("files/".$dbname."/".$value);
                    if ($altoimg>$TMP_maxPixelsShowImg || $anchoimg>$TMP_maxPixelsShowImg) {
                        echo "\n<! Resize de ".$value." $anchoimg, $altoimg, $tipo>\n";
                        $TMP_fileresize=RAD_resizeImg("files/".$dbname."/".$value, "$TMP_maxPixelsShowImg", "$TMP_maxPixelsShowImg");
                        list($anchoimg, $altoimg, $tipo, $resto)=getimagesize($TMP_fileresize);
                        echo "<! a $TMP_fileresize $anchoimg, $altoimg, $tipo >\n";
                        $TMP_cont.="<A TARGET=_BLANK HREF=\"".$TMP_filek."\"><IMG SRC=\"".$TMP_fileresize."\"></A>\n";
                    } else {
                        $TMP_cont.="<IMG SRC=\"".$TMP_filek."\">\n";
                    }
                }
            }
        }
    } elseif ($fdtype == "date" || $fdtype == "datetext") {
        $TMP_cont.=RAD_showDate($value);
    } elseif ($fdtype == "datetime" || $fdtype == "datetimetext") {
        $TMP_cont.=RAD_showDateTime($value);
    } elseif ($fdtype == "dateint" || $fdtype == "dateinttext") {
            $TMP_cont.=RAD_showDateInt($value);
    } elseif ($fdtype == "datetimeint" || $fdtype == "datetimeinttext") {
            $TMP_cont.=RAD_showDateTimeInt($value);
    } elseif ($fdtype == "time" || $fdtype == "timetext" || $fdtype == "timeora") {
        $TMP_cont.=RAD_showTime($value);
    } elseif ($fdtype == "timeint" || $fdtype == "timeinttext") {
        $TMP_cont.=RAD_showTimeInt($value);
    } elseif ($fdtype == "email") {
        if (_DEF_EMAIL_SERVER_TYPE=="ZCS") { //Zimbra
            $TMP_cont.="<a href='javascript:RAD_OpenW(\"http://"._DEF_EMAIL_SERVER."/zimbra/?view=compose&to=".urlencode($value).$SESSION_SID."\",958,600);'>".$value."</a>";
        }else{
            $TMP_emailuser=getSessionVar("SESSION_popuser");
            if ($TMP_emailuser!="" && is_modulepermitted("", "contents","mesg")) $TMP_cont.="<a href='javascript:RAD_OpenW(\"index.php?V_dir=contents&V_mod=editmesg&func=new&subfunc=browse&headeroff=x&footeroff=x&menuoff=x&emailto=".urlencode($value).$SESSION_SID."\",800,600);'>".$value."</a>";
            else $TMP_cont.="<a href='mailto:".$value."'>".$value."</a>";
        }
    } elseif ($fdtype == "http") {
        if (substr($value,0,5)=="http:" || substr($value,0,6)=="https:") $TMP_cont.="<a href='".$value."' TARGET=_blank>".$value."</a>";
        else $TMP_cont.="<a href='http://".$value."' TARGET=_blank>".$value."</a>";
    } elseif ($fdtype == "plistdbtree" || $fdtype == "plistdb" || $fdtype == "rlistdb" || $fdtype == "fpopupdb" || $fdtype == "bpopupdb" || $fdtype == "fbpopupdb" || $fdtype == "popupdb" || $fdtype == "popupdbtree" || $fdtype == "popupdbm" || $fdtype == "plistdbm"  || $fdtype == "plistdbmtree" || $fdtype == "checkboxdb" || $fdtype == "checkboxdbm") {
        if ($value=="") return "";
        $arr = explode(":", $fextra);
        $ptablename = $arr[0];
        $pfname = $arr[1];
        $pftitle = $arr[2];
        $pfilter = $arr[3];
        $porder = $arr[4]; if ($porder=="") $porder=$pftitle; if ($porder!="") $porder=" ORDER by ".$porder;
        $pgroup = $arr[6]; if ($pgroup!="") $pgroup=" GROUP BY ".$pgroup." ";
        $arrtmp[0]=""; $arrtmp[1]="";
        $arrtmp = explode(",",$pfname);
        if (!isset($arrtmp[1])) $arrtmp[1] = "";
        $WHERE="";
        if ($fdtype == "plistdbtree" || $fdtype == "plistdb" || $fdtype == "rlistdb" || $fdtype == "popupdb" || $fdtype == "popupdbtree" || $fdtype == "fpopupdb" || $fdtype == "bpopupdb" || $fdtype == "fbpopupdb" || $fdtype == "checkboxdb") {
            $WHERE=" WHERE ".$pfname."='".$value."'";
            if ($arrtmp[1]!="") {
            //if (isset($db -> Record[$arrtmp[0]])) $valor0=$db -> Record[$arrtmp[0]];
            //else
            $valor0=$value;
            $valor1=$db -> Record[$arrtmp[1]];
	    if ($valor1=="") $valor1=RAD_getField($arrtmp[1]);
            if ($arrtmp[2]!='') $arrtmp[1]=$arrtmp[2];
            if ($valor1!="") $WHERE=" WHERE $arrtmp[0]='$valor0' AND $arrtmp[1]='$valor1'";
            else $WHERE=" WHERE $arrtmp[0]='$valor0'";
            }
        }
        if ($pfilter!="") {
            if ($WHERE=="") $TMP_WHERE=" WHERE ".$pfilter;
            else $TMP_WHERE=$WHERE." AND (".$pfilter.")";
            $cmdSQL="SELECT * FROM $ptablename".$TMP_WHERE." ".$pgroup.$porder;
            $TMP_result = sql_query($cmdSQL, $RAD_dbi);
            $cmdSQLcount="SELECT count(*) FROM $ptablename".$TMP_WHERE." ".$pgroup.$porder;
            $TMP_resultc = sql_query($cmdSQLcount, $RAD_dbi);
            $TMP_rowc=sql_fetch_array($TMP_resultc, $RAD_dbi);
            $TMP_numrows = $TMP_rowc[0];
            if ($TMP_numrows>0) $WHERE=$TMP_WHERE; // Si con el filtro no se encuentran registros a mostrar se ignora el filtro
        }
        if ($pftitle) {
//          $cmdSQL="SELECT $pfname,$pftitle FROM $ptablename".$WHERE." GROUP BY $pftitle";
            $cmdSQL="SELECT $pfname,$pftitle FROM $ptablename".$WHERE." ".$pgroup.$porder;
            $cmdSQL="SELECT * FROM $ptablename".$WHERE." ".$pgroup.$porder;
            $TMP_result = sql_query($cmdSQL, $RAD_dbi);
        } else {
            if (strstr($pfname,'(')) {
                $cmdSQL="SELECT $pfname FROM $ptablename".$WHERE.$pgroup.$porder;
                $cmdSQL="SELECT * FROM $ptablename".$WHERE.$pgroup.$porder;
            } else {
//              $cmdSQL="SELECT $pfname FROM $ptablename".$WHERE." GROUP BY $pfname";
                $cmdSQL="SELECT $pfname FROM $ptablename".$WHERE." ".$pgroup.$porder;
                $cmdSQL="SELECT * FROM $ptablename".$WHERE." ".$pgroup.$porder;
            }
            $TMP_result = sql_query($cmdSQL, $RAD_dbi);
        }
        $tarrtmp[1] = "";
        $tarrtmp = explode(",",$pfname);
        if ($tarrtmp[1]!="") $pfname=$tarrtmp[0];
        $lang=getSessionVar("SESSION_lang");
        while($TMP_row = sql_fetch_array($TMP_result, $RAD_dbi)) {
            //if ($TMP_row[$pfname."_".$lang]!="") $TMP_row[$pfname]=$TMP_row[$pfname."_".$lang];
            if ($TMP_row[$pftitle."_".$lang]!="") $TMP_row[$pftitle]=$TMP_row[$pftitle."_".$lang];
            $tarrtmp[1] = "";
            $tarrtmp = explode(",",$pftitle);
            if ($tarrtmp[1]!="") {
                $pftitle1=$tarrtmp[0];
                $pftitle2=$tarrtmp[1];
                $pftitle3=$tarrtmp[2];
                $pftitle4=$tarrtmp[3];
                if ($TMP_row[$pftitle1."_".$lang]!="") $TMP_row[$pftitle1]=$TMP_row[$pftitle1."_".$lang];
                if ($TMP_row[$pftitle2."_".$lang]!="") $TMP_row[$pftitle2]=$TMP_row[$pftitle2."_".$lang];
                if ($TMP_row[$pftitle3."_".$lang]!="") $TMP_row[$pftitle3]=$TMP_row[$pftitle3."_".$lang];
                if ($TMP_row[$pftitle4."_".$lang]!="") $TMP_row[$pftitle4]=$TMP_row[$pftitle4."_".$lang];
                if ($fdtype == "plistdbtree" || $fdtype == "plistdb" || $fdtype == "rlistdb" || $fdtype == "popupdb" || $fdtype == "popupdbtree" || $fdtype == "fpopupdb" || $fdtype == "bpopupdb" || $fdtype == "fbpopupdb" || $fdtype == "checkboxdb") {
                    if ($TMP_row[$pfname]."" === $value."") {
                        $TMP_cont.=$TMP_row[$pftitle1];
                        if ($TMP_row[$pftitle2]!="") $TMP_cont.=" ".$TMP_row[$pftitle2];
                        if ($TMP_row[$pftitle3]!="") $TMP_cont.=" ".$TMP_row[$pftitle3];
                        if ($TMP_row[$pftitle4]!="") $TMP_cont.=" ".$TMP_row[$pftitle4];
                        $TMP_cont.=" \n";
                    }
                } else {
                    $Avalues["$TMP_row[$pfname]"]=$TMP_row[$pftitle1];
                    if ($TMP_row[$pftitle2]!="") $Avalues["$TMP_row[$pfname]"].=" ".$TMP_row[$pftitle2];
                    if ($TMP_row[$pftitle3]!="") $Avalues["$TMP_row[$pfname]"].=" ".$TMP_row[$pftitle3];
                    if ($TMP_row[$pftitle4]!="") $Avalues["$TMP_row[$pfname]"].=" ".$TMP_row[$pftitle4];
                }
            } else {
                if ($arrtmp[1] != "") $pfname=$arrtmp[0];
                if ($TMP_row[$pfname]."" === $value."") $literalpopupdb=$TMP_row[$pftitle];
                if ($fdtype == "plistdbtree" || $fdtype == "plistdb" || $fdtype == "rlistdb" || $fdtype == "popupdb" ||$fdtype == "popupdbtree" || $fdtype == "fpopupdb" || $fdtype == "bpopupdb" || $fdtype == "fbpopupdb" || $fdtype == "checkboxdb") {
                    if ($TMP_row[$pfname]."" === $value."") $TMP_cont.=$TMP_row[$pftitle]." \n";
                } else {
                    $Avalues["$TMP_row[$pfname]"]=trim($TMP_row[$pftitle]);
                }
            }
        }

        if ($fdtype == "popupdbm" || $fdtype == "plistdbm"  || $fdtype == "plistdbmtree" || $fdtype == "checkboxdbm") {
            $varrtmp = explode(",",",".$value.",");
            if ($fdtype!="checkboxdbm") {
              for ($ki = 0; $ki < count($varrtmp); $ki++) {
            if(trim($varrtmp[$ki])!="") {
                if ($Avalues[$varrtmp[$ki]]!="") {
                    if ($TMP_cont!="") $TMP_cont.="<br>\n";
                    if ($fdtype=="checkboxdbm") {
			if ($V_typePrint=="CSV") $TMP_cont.= $value;
			else $TMP_cont.="<img src='images/checked.gif' border=0> ";
		    }
                    $TMP_cont.=$Avalues[$varrtmp[$ki]];
                }
            }
              }
            } else {
            $varrtmp2=array();
            for ($ki = 0; $ki < count($varrtmp); $ki++) $varrtmp2[$varrtmp[$ki]]=$ki;
            foreach ($Avalues as $key=>$val) {
                if($varrtmp2[$key]!="") {
                    if ($TMP_cont!="") $TMP_cont.="<br>\n";
                    $TMP_cont.=" ".$val;
                }
//              else $TMP_cont.="<img src='images/check.gif' border=0> ";
//              $TMP_cont.=$val;
            }
            }
        }
    } elseif ($fdtype=="rlist" || $fdtype=="plist" || $fdtype=="plistm"  || $fdtype == "checkboxm") {

        if ($fextra =="") $fextra="0:No,1:Si";
        $ks = explode(",", $fextra);
        $tmp="";
        for ($ki = 0; $ki < count($ks); $ki++) {
            $kx = explode(":",$ks[$ki]);
            if($fdtype=="plistm"  || $fdtype=="checkboxm") {
                if(@eregi(",".$kx[0].",", ",".$value.",")) {
                    if ($tmp!="") $tmp.="<br>\n";
                    $tmp .= $kx[1]." ";
                }
            } else { if($kx[0]."" === $value."") $tmp = $kx[1]; }
        }
        if (!empty($tmp)) $TMP_cont.=$tmp;
        else $TMP_cont.=$value;
    } elseif (substr($fdtype,0,3)=="num") {
        $value=RAD_numero($value,$fextra);
        $TMP_width=40;
        if ($TMP_decimales[0]>6) $TMP_width=60;
        if ($TMP_decimales[0]>9) $TMP_width=80;
        if ($TMP_decimales[0]>12) $TMP_width=100;
        $TMP_cont.="<div class='RADclassnum' style='width:".$TMP_width."px;text-align:right;'>".$value."";
        if ($value=="") $TMP_cont.="&nbsp;";
        $TMP_cont.="</div>";
    } elseif ($fdtype=="password") {
        $TMP_cont.=str_repeat("*",strlen($value));
    } else if ($fdtype == "checkbox") {
                $arr = split(":", $fextra);
                if ($arr[1]=="") $arr[1]="1";
                if ($value."" === $arr[1]."") {
			if ($V_typePrint=="CSV") $TMP_cont.= $value;
			else $TMP_cont.="<img border=0 src='images/checked.gif'>";
		}
                else $TMP_cont.= "<img border=0 src='images/check.gif'>";

    } else {
//      $value=str_replace("<", "&lt;", $value);
//      $value=str_replace("\n", "<br>\n", $value);

        $value=RAD_showText($value);
        $TMP_cont.=$value;
    }

    return $TMP_cont;
}
/////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////
function RAD_inputDateText($TMP_name,$value,$type,$onChange,$formName="F",$extra,$onBlur="") {
global $RAD_html5, $func, $numsubbrowse;
    $current_date = getdate();
    if (strlen($current_date["mon"])<2) $current_date["mon"]="0".$current_date["mon"];
    if (strlen($current_date["mday"])<2) $current_date["mday"]="0".$current_date["mday"];
    if ($value === "") {
                $value=$current_date["year"]."-".$current_date["mon"]."-".$current_date["mday"];
    }
    $arr = explode("-", $value);
    $TMP_year=$arr[0];
    $TMP_month=$arr[1];
    $TMP_day=substr($arr[2],0,2);
    if (strlen($TMP_month)<2) $TMP_month="0".$TMP_month;
    if (strlen($TMP_day)<2) $TMP_day="0".$TMP_day;
    $value=$TMP_day.$TMP_month.$TMP_year;
    $value5=$TMP_year."-".$TMP_month."-".$TMP_day;
    if ($TMP_year=="0") $value="";

    if ($func!="searchform") $TMP_onkeydown="onKeyDown=\"javascript:if(event.keyCode==13) RAD_focusNextField('');\"";
    else $TMP_onkeydown="";
    if ($RAD_html5=="") $RAD_html5=_DEF_html5;
    if ($RAD_html5=="1") $TMP_cont.= "<INPUT TYPE=DATE value='$value5' name='".$TMP_name."_date5'";
    else $TMP_cont.= "<INPUT TYPE=TEXT value='$value' size=10 name='".$TMP_name."_date'";
    $TMP_cont.= " $TMP_onkeydown $onChange $onBlur>\n";

    if (file_exists("themes/".getSessionVar("SESSION_theme")."/calendar.gif")) $TMP_imgcal="themes/".getSessionVar("SESSION_theme")."/calendar.gif";
    else $TMP_imgcal="images/calendar.gif";
    if ($RAD_html5!="1") {
	$TMP_cont.= "<a href='javascript:RAD_jsnull();' onClick='javascript:popUpCalendar(this,F".$numsubbrowse.".".$TMP_name."_date,F".$numsubbrowse.".fnull,F".$numsubbrowse.".fnull,F".$numsubbrowse.".fnull,\"ddmmyyyy\");'><img class='iconcalendar' src='".$TMP_imgcal."' border=0 alt='calendar' style='vertical-align:middle;'></a>";

	if (!ereg("notoday",$extra)) $TMP_cont.= " <input type=button value='!' title='Hoy' alt='Hoy' onClick='javascript:document.forms.".$formName.".".$TMP_name."_date.value=\"".$current_date["mday"]."".$current_date["mon"]."".$current_date["year"]."\";'>";
	if (!ereg("noreset",$extra)) $TMP_cont.= " <input type=button value='[]' title='"._DEF_NLSReset."' alt='"._DEF_NLSReset."' onClick='javascript:document.forms.".$formName.".".$TMP_name."_date.value=\"$value\";'>";
    }
    return $TMP_cont;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_inputDate($TMP_name,$value,$type,$onChange,$formName="F",$extra,$onBlur="") {
global $func, $numsubbrowse,$RAD_defined_iyear,$RAD_defined_eyear,$RAD_date_notoday,$RAD_date_delimg;
    $current_date = getdate();
    if ($value == "") {
        $value=$current_date["year"]."-".$current_date["mon"]."-".$current_date["mday"];
    }
    $arr = explode("-", $value);
    $TMP_year=$arr[0];
    if ($TMP_year=="0") $TMP_year="";
    $TMP_month=$arr[1];
    $TMP_day=substr($arr[2],0,2);

        if ($func!="searchform") $TMP_onkeydown="onKeyDown=\"javascript:if(event.keyCode==13) RAD_focusNextField('');\" ";
        else $TMP_onkeydown="";

    if ($onChange=='') $onChange=" onChange=\"javascript:RAD_controlaFecha(this);\"; ";
    else {
        $onChange=str_replace("onChange=\"javascript:","onChange=\"javascript:RAD_controlaFecha(this);",$onChange);
        $onChange=str_replace("onChange='javascript:","onChange='javascript:RAD_controlaFecha(this);",$onChange);
    }

/*
        $TMP_cont.='<script>

                   function validaFecha(Fecha, Tipo) {
                       var Fecha= new String(Fecha)
                       var RealFecha= new Date()
                       if (Tipo=='fecha') {
                           var Anho= new String(Fecha.substring(Fecha.lastIndexOf("-")+1,Fecha.length))
                           var Mes= new String(Fecha.substring(Fecha.indexOf("-")+1,Fecha.lastIndexOf("-")))
                           var Dia= new String(Fecha.substring(0,Fecha.indexOf("-")))
                       }
                       if (Tipo=="anho") {
                           Anho = new String(Fecha)
                       }
                       if (Tipo=="mes") {
                           Mes = new String(Fecha)
                       }
                       if (Tipo=="dia") {
                           Dia = new String(Fecha)
                       }

                       if (isNaN(Anho) || Anho.length<4 || parseFloat(Anho)<1900){
                           alert(\'Año inválido\')
                           return false
                       }
                       if (isNaN(Mes) || parseFloat(Mes)<1 || parseFloat(Mes)>12){
                           alert(\'Mes inválido\')
                           return false
                       }
                       if (isNaN(Dia) || parseInt(Dia, 10)<1 || parseInt(Dia, 10)>31){
                           alert(\'Día inválido\')
                           return false
                       }
                       if (Mes==4 || Mes==6 || Mes==9 || Mes==11 || Mes==2) {
                           if (Mes==2 && Dia > 28 || Dia>30) {
                               alert(\'Día inválido\')
                               return false
                           }
                        }

                    }

        </script>';
*/
        $TMP_menosuno=0;
    $TMP_cont.= "<SELECT $TMP_onkeydown NAME='".$TMP_name."_day' onfocus=\"javascript:RAD_setselFieldName('".$TMP_name."_day');\" $onBlur $onChange>\n";
    if ($type =="0" || $value=="0") {
            $TMP_cont.= "<OPTION VALUE=''></OPTION>\n";
        }else{
            $TMP_menosuno=1;
        }
    for ($k=1;$k<32;$k++) {
        if ($k<10) $K="0".$k;
        else $K=$k;
        if ($k*1==$TMP_day*1) $SELECTED=" SELECTED";
        else $SELECTED="";
        $TMP_cont.= "<OPTION VALUE='$k'$SELECTED>$K</OPTION>\n"; }
    $TMP_cont.= "</SELECT>&nbsp;\n";
    $TMP_cont.= "<SELECT $TMP_onkeydown NAME='".$TMP_name."_month' onfocus=\"javascript:RAD_setselFieldName('".$TMP_name."_month');\" $onBlur $onChange>\n";
    if ($type =="0" || $value=="0") {
            $TMP_cont.= "<OPTION VALUE=''></OPTION>\n";
        }else{
            $TMP_menosuno=1;
        }
    for ($k=1;$k<13;$k++) {
        if ($k<10) $K="0".$k;
        else $K=$k;
        if ($k*1==$TMP_month*1) $SELECTED=" SELECTED";
        else $SELECTED="";
        $nameMonth="_DEF_NLSMonth".$K;
        if (defined("$nameMonth")) $K=constant("$nameMonth");
        $TMP_cont.= "<OPTION VALUE='$k'$SELECTED>".$K."</OPTION>\n"; }

    $TMP_cont.= "</SELECT>&nbsp;\n";
    if ($RAD_defined_iyear>0 && $RAD_defined_eyear>0 && $RAD_defined_iyear<=$RAD_defined_eyear) {
        $TMP_cont.="<SELECT $TMP_onkeydown NAME='".$TMP_name."_year' onfocus=\"javascript:RAD_setselFieldName('".$TMP_name."_year');\" $onBlur $onChange>\n";
        $TMP_cont.= "<OPTION VALUE=''></OPTION>\n";
        for ($k=$RAD_defined_iyear;$k<=$RAD_defined_eyear;$k++) {
            if ($TMP_year==$k) $SELECTED= " selected";
            else $SELECTED="";
            $TMP_cont.= "<OPTION VALUE='$k'$SELECTED>".$k."</OPTION>\n";
        }
        $TMP_cont.="</SELECT>&nbsp;\n";
    }
    else {
        $TMP_cont.= "<INPUT TYPE=TEXT $TMP_onkeydown SIZE=4 MAXLENGTH=4 NAME='".$TMP_name."_year' VALUE='".$TMP_year."' $onBlur $onChange> ";
    }
    $TMP_mon=$current_date["mon"]-$TMP_menosuno;
    $TMP_day=$current_date["mday"]-$TMP_menosuno;
    //Si se da el caso en que un campo fecha sea no null y cubierto por defecto a 0 al darle a la ! rellena con la fecha del mes y día anterior
        //if ($type !="0") { $TMP_mon--; $TMP_day--; }
    if (_DEF_NLSCalendarIcon!="" && _DEF_NLSCalendarIcon!="_DEF_NLSCalendarIcon") $TMP_imgcal=_DEF_NLSCalendarIcon;
    elseif (file_exists("themes/".getSessionVar("SESSION_theme")."/calendar.gif")) $TMP_imgcal="themes/".getSessionVar("SESSION_theme")."/calendar.gif";
    else $TMP_imgcal="images/calendar.gif";
    //if (!eregi("PDA",getSessionVar("SESSION_theme"))) $TMP_cont.= "<a href='javascript:RAD_jsnull();' onClick='javascript:window.open(\"utils.php?func=cal&field=".urlencode($TMP_name)."&formName=$formName\",\"_blank\",\"width=200,height=200,resizable=yes,scrollbars=yes,status=no,toolbar=no,menubar=yes,titlebar=no\");'></a>";

    $TMP_cont.= " <a href='javascript:RAD_jsnull();' onClick='javascript:popUpCalendar(this,F".$numsubbrowse.".fnull,F".$numsubbrowse.".".$TMP_name."_day,F".$numsubbrowse.".".$TMP_name."_month,F".$numsubbrowse.".".$TMP_name."_year,\"ddmmyyyy\");'><img class='iconcalendar' border=0 src=\"".$TMP_imgcal."\" alt=\"Calendario\" style=\"vertical-align:middle;\"></a>";

    if (!ereg("notoday",$extra) && $RAD_date_notoday=='')  {
        if (_DEF_NLSWandIcon!="" && _DEF_NLSWandIcon!="_DEF_NLSWandIcon") {
            $TMP_cont.= " <img src='"._DEF_NLSWandIcon."' border='0' style='cursor:pointer;vertical-align:middle' title='Hoy' alt='Hoy' onClick='javascript:document.forms.".$formName.".".$TMP_name."_year.value=\"".$current_date["year"]."\";document.forms.".$formName.".".$TMP_name."_month.selectedIndex=".$TMP_mon.";document.forms.".$formName.".".$TMP_name."_day.selectedIndex=".$TMP_day."; if (document.forms.".$formName.".".$TMP_name."_year.onchange!=null) document.forms.".$formName.".".$TMP_name."_year.onchange();'>";
        }
        else {
            $TMP_cont.= " <input type=button value='!' title='Hoy' alt='Hoy' onClick='javascript:document.forms.".$formName.".".$TMP_name."_year.value=\"".$current_date["year"]."\";document.forms.".$formName.".".$TMP_name."_month.selectedIndex=".$TMP_mon.";document.forms.".$formName.".".$TMP_name."_day.selectedIndex=".$TMP_day."; if (document.forms.".$formName.".".$TMP_name."_year.onchange!=null) document.forms.".$formName.".".$TMP_name."_year.onchange();'>";
        }
    }
    if (!ereg("noreset",$extra)) {
        if (_DEF_NLSCrossIcon!="" && _DEF_NLSCrossIcon!="_DEF_NLSCrossIcon") {
            $TMP_cont.= " <img src='"._DEF_NLSCrossIcon."' border='0' style='cursor:pointer;vertical-align:middle' title='"._DEF_NLSClearAll."' alt='"._DEF_NLSClearAll."' onClick='javascript:document.forms.".$formName.".".$TMP_name."_year.value=\"\";document.forms.".$formName.".".$TMP_name."_month.selectedIndex=0;document.forms.".$formName.".".$TMP_name."_day.selectedIndex=0;if (document.forms.".$formName.".".$TMP_name."_year.onchange!=null) document.forms.".$formName.".".$TMP_name."_year.onchange();'>";
        }
        else {
            $TMP_cont.= " <input type=button value='0' title='"._DEF_NLSClearAll."' alt='"._DEF_NLSClearAll."' onClick='javascript:document.forms.".$formName.".".$TMP_name."_year.value=\"\";document.forms.".$formName.".".$TMP_name."_month.selectedIndex=0;document.forms.".$formName.".".$TMP_name."_day.selectedIndex=0;if (document.forms.".$formName.".".$TMP_name."_year.onchange!=null) document.forms.".$formName.".".$TMP_name."_year.onchange();'>";
        }
    }
    return $TMP_cont;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_inputTime($TMP_name,$value, $type,$onChange,$formName="F",$onBlur="") {
global $func, $RAD_noSeconds, $RAD_gapMinutes;
    $current_date = getdate();
    if ($value == "" && $value!=0) {
        $value=$current_date["hours"].":".$current_date["minutes"].":".$current_date["seconds"];
    }

    if (strlen($value)>8) { // Viene con fechaaa
        $TMP_arr=explode(" ",$value);
        $value=$TMP_arr[1];
    }

    $arr = explode(":", $value);
    $hour=trim($arr[0]);
    $min=trim($arr[1]); if (strlen($min)==1) $min="0".$min;
    $sec=trim($arr[2]); if (strlen($sec)==1) $sec="0".$sec;

        if ($func!="searchform") $TMP_onkeydown="onKeyDown=\"javascript:if(event.keyCode==13) RAD_focusNextField('');\"";
        else $TMP_onkeydown="";
    $TMP_cont.= "\n<SELECT $TMP_onkeydown onFocus=\"javascript:RAD_setselFieldName('".$TMP_name."_hour');\" NAME='".$TMP_name."_hour' $onBlur $onChange>\n";
    $SELECTED="";
    if ($value=="0") {
        $SELECTED=" SELECTED";
        $hour=-1;
    }
    $adder=0;
    if ($type =="0") {
        $TMP_cont.= "<OPTION VALUE=''$SELECTED></OPTION>\n";
        $adder=1;
    }
    for ($k=0;$k<24;$k++) {
        if ($k<10) $K="0".$k;
        else $K=$k;
        if ($k*1==$hour*1) $SELECTED=" SELECTED";
        else $SELECTED="";
        $TMP_cont.= "<OPTION VALUE='$k'$SELECTED>$K</OPTION>\n"; }
    $TMP_cont.= "</SELECT>\n";
    $TMP_currenselecthour=$current_date["hours"]+$adder;
    if ($RAD_gapMinutes>0) {
        $TMP_cont.= "\n<SELECT $TMP_onkeydown onFocus=\"javascript:RAD_setselFieldName('".$TMP_name."_min');\" NAME='".$TMP_name."_min' $onBlur $onChange>\n";
        $SELECTED="";
        if ($value=="0") {
            $SELECTED=" SELECTED";
            $min=-1;
        }
        if ($type =="0") $TMP_cont.= "<OPTION VALUE=''$SELECTED></OPTION>\n";
        $TMP_Xcurrenselectmin=$current_date["minutes"];
        $TMP_tramo=0;
        for ($k=0;$k<60;$k=$k+$RAD_gapMinutes) {
            $TMP_tramo++;
            if ($k<10) $K="0".$k;
            else $K=$k;
            if ($k*1<=$TMP_Xcurrenselectmin*1 && ($k*1+$RAD_gapMinutes)>$TMP_Xcurrenselectmin*1) $TMP_currenselectmin=$TMP_tramo;
            if ($k*1<=$min*1 && ($k*1+$RAD_gapMinutes)>$min*1) $SELECTED=" SELECTED";
            else $SELECTED="";
            $TMP_cont.= "<OPTION VALUE='$k'$SELECTED>$K</OPTION>\n";
        }
        $TMP_currenselectmin-=(1-$adder);
        $TMP_cont.= "</SELECT>\n";
    } else {
        $TMP_cont.= "&nbsp;\n<INPUT onFocus=\"javascript:RAD_setselFieldName('".$TMP_name."_min');\" TYPE=TEXT $TMP_onkeydown SIZE=2 MAXLENGTH=2 NAME='".$TMP_name."_min' VALUE='".$min."' $onBlur $onChange>";
    }
    if ($RAD_noSeconds=="1") {
        $sec="00";
        $current_date["seconds"]="00";
    }
    if ($RAD_noSeconds!="1") $TMP_cont.= "&nbsp;\n<INPUT onFocus=\"javascript:RAD_setselFieldName('".$TMP_name."_sec');\" TYPE=TEXT $TMP_onkeydown SIZE=2 MAXLENGTH=2 NAME='".$TMP_name."_sec' VALUE='".$sec."' $onBlur $onChange>";
    else $TMP_cont.= "<INPUT TYPE=HIDDEN NAME='".$TMP_name."_sec' VALUE='".$sec."'>";
    if ($RAD_gapMinutes>0) $TMP_cont.= " <input type=button value='!' alt='Ahora' title='Ahora' onClick='javascript:document.forms.".$formName.".".$TMP_name."_hour.selectedIndex=".$TMP_currenselecthour.";document.forms.".$formName.".".$TMP_name."_min.selectedIndex=".$TMP_currenselectmin.";document.forms.".$formName.".".$TMP_name."_sec.value=\"".$current_date["seconds"]."\";'>";
    else $TMP_cont.= " <input type=button value='!' alt='Ahora' title='Ahora' onClick='javascript:document.forms.".$formName.".".$TMP_name."_hour.selectedIndex=".$TMP_currenselecthour.";document.forms.".$formName.".".$TMP_name."_min.value=\"".$current_date["minutes"]."\";document.forms.".$formName.".".$TMP_name."_sec.value=\"".$current_date["seconds"]."\";'>";
    $TMP_cont.= " <input type=button value='0' alt='"._DEF_NLSClearAll."' title='"._DEF_NLSClearAll."' onClick='javascript:document.forms.".$formName.".".$TMP_name."_hour.selectedIndex=0;document.forms.".$formName.".".$TMP_name."_min.value=\"\";document.forms.".$formName.".".$TMP_name."_sec.value=\"\";'>";
    return $TMP_cont;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_inputTimeText($TMP_name,$value, $type,$onChange,$formName="F",$onBlur="") {
global $func, $RAD_noSeconds;
    $current_date = getdate();
    if (strlen($current_date["hours"])<2) $current_date["hours"]="0".$current_date["hours"];
    if (strlen($current_date["minutes"])<2) $current_date["minutes"]="0".$current_date["minutes"];
    if (strlen($current_date["seconds"])<2) $current_date["seconds"]="0".$current_date["seconds"];
    if ($value == "" && $value!=0) {
        $value=$current_date["hours"].":".$current_date["minutes"].":".$current_date["seconds"];
    }
    $ahora=$current_date["hours"]."".$current_date["minutes"]."".$current_date["seconds"];

    $arr = explode(":", $value);
    $hour=trim($arr[0]); if (strlen($hour)==1) $hour="0".$hour;
    $min=trim($arr[1]); if (strlen($min)==1) $min="0".$min;
    $sec=trim($arr[2]); if (strlen($sec)==1) $sec="0".$sec;
    $value=$hour.$min;
    if ($RAD_noSeconds!="1") $value.=$sec;

    $SELECTED="";
        if ($func!="searchform") $TMP_onkeydown="onKeyDown=\"javascript:if(event.keyCode==13) RAD_focusNextField('');\"";
        else $TMP_onkeydown="";
    $TMP_cont.= "\n<INPUT TYPE=TEXT $TMP_onkeydown SIZE=6 onFocus=\"javascript:RAD_setselFieldName('".$TMP_name."_time');\" NAME='".$TMP_name."_time' $onBlur $onChange VALUE='$value'>\n";
    $TMP_cont.= " <input type=button value='!' alt='Ahora' title='Ahora' onClick='javascript:document.forms.".$formName.".".$TMP_name."_time.value=\"".$ahora."\";'>";
    $TMP_cont.= " <input type=button value='0' alt='"._DEF_NLSClearAll."' title='"._DEF_NLSClearAll."' onClick='javascript:document.forms.".$formName.".".$TMP_name."_time.value=\"\";'>";
    return $TMP_cont;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_inputDateInt($TMP_name,$value,$type, $onChange,$formName="F",$extra,$onBlur="") {
    if (substr($value,0,1)=="0") {
        $value="0000-00-00";
    } else if ($value == "" || $value == "0" || $value == 0 || $value == -1) {
        $current_date = getdate();
    } else {
        $current_date=getdate($value);
    }
    $value=$current_date["year"]."-".$current_date["mon"]."-".$current_date["mday"];
    $value.=" ".$current_date["hours"].":".$current_date["minutes"].":".$current_date["seconds"];
    $arr = explode(" ", $value);
    $valuedate=$arr[0];
    $valuetime=$arr[1];
    $TMP_cont.=RAD_inputDate($TMP_name,$valuedate,$type, $onChange,$formName,$extra,$onBlur);
    return $TMP_cont;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_inputDateIntText($TMP_name,$value,$type, $onChange,$formName="F",$extra,$onBlur="") {
    if ($value == "" || $value == "0" || $value == 0 || $value == -1) {
        $current_date = getdate();
    } else {
        $current_date=getdate($value);
    }
    $value=$current_date["year"]."-".$current_date["mon"]."-".$current_date["mday"];
    $value.=" ".$current_date["hours"].":".$current_date["minutes"].":".$current_date["seconds"];
    $arr = explode(" ", $value);
    $valuedate=$arr[0];
    $valuetime=$arr[1];
    $TMP_cont.=RAD_inputDateText($TMP_name,$valuedate,$type, $onChange,$formName,$extra,$onBlur);
    return $TMP_cont;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_inputDateTimeInt($TMP_name,$value,$type, $onChange,$formName="F",$extra,$onBlur="") {
    if (substr($value,0,1)=="0") {
        $value="0000-00-00";
    } else if ($value == "" || $value == "0" || $value == 0 || $value == -1) {
        $current_date = getdate();
        $value=$current_date["year"]."-".$current_date["mon"]."-".$current_date["mday"];
        $value.=" ".$current_date["hours"].":".$current_date["minutes"].":".$current_date["seconds"];
    } else {
        $current_date=getdate($value);
        $value=$current_date["year"]."-".$current_date["mon"]."-".$current_date["mday"];
        $value.=" ".$current_date["hours"].":".$current_date["minutes"].":".$current_date["seconds"];
    }
    $arr = explode(" ", $value);
    $valuedate=$arr[0];
    $valuetime=$arr[1];
    $TMP_cont.=RAD_inputDate($TMP_name,$valuedate,$type, $onChange,$formName,$extra,$onBlur);
    $TMP_cont.="&nbsp;&nbsp;";
    $TMP_cont.=RAD_inputTime($TMP_name,$valuetime,$type, $onChange,$formName,$onBlur);
    return $TMP_cont;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_inputDateTimeIntText($TMP_name,$value,$type, $onChange,$formName="F",$extra,$onBlur="") {
    if ($value == "" || $value == "0" || $value == 0 || $value == -1) {
        $current_date = getdate();
    } else {
        $current_date=getdate($value);
    }
    $value=$current_date["year"]."-".$current_date["mon"]."-".$current_date["mday"];
    $value.=" ".$current_date["hours"].":".$current_date["minutes"].":".$current_date["seconds"];
    $arr = explode(" ", $value);
    $valuedate=$arr[0];
    $valuetime=$arr[1];
    $TMP_cont.=RAD_inputDateText($TMP_name,$valuedate,$type, $onChange,$formName,$extra,$onBlur);
    $TMP_cont.="&nbsp;&nbsp;";
    $TMP_cont.=RAD_inputTimeText($TMP_name,$valuetime,$type, $onChange,$formName,$onBlur);
    return $TMP_cont;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_inputTimeInt($TMP_name,$value,$type, $onChange,$formName="F",$onBlur="") {
    if ($value == "") {
        $current_date = getdate();
        $valuetime=$current_date["hours"].":".$current_date["minutes"].":".$current_date["seconds"];
    } else {
        $TMP_hour=floor($value/3600);
        $TMP_min=floor(($value-$TMP_hour*3600)/60);
        $TMP_sec=$value-$TMP_hour*3600-$TMP_min*60;
        $valuetime=$TMP_hour.":".$TMP_min.":".$TMP_sec;
    }
    $TMP_cont.=RAD_inputTime($TMP_name,$valuetime,$type, $onChange,$formName,$onBlur);
    return $TMP_cont;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_inputTimeIntText($TMP_name,$value,$type, $onChange,$formName="F",$onBlur="") {
    if ($value == "") {
        $current_date = getdate();
        $valuetime=$current_date["hours"].":".$current_date["minutes"].":".$current_date["seconds"];
    } else {
        $TMP_hour=floor($value/3600);
        $TMP_min=floor(($value-$TMP_hour*3600)/60);
        $TMP_sec=$value-$TMP_hour*3600-$TMP_min*60;
        $valuetime=$TMP_hour.":".$TMP_min.":".$TMP_sec;
    }
    $TMP_cont.=RAD_inputTimeText($TMP_name,$valuetime,$type, $onChange,$formName,$onBlur);
    return $TMP_cont;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_inputDateTime($TMP_name,$value,$type, $onChange,$formName="F",$extra,$onBlur="") {
    if ($value == "") {
        $current_date = getdate();
        $value=$current_date["year"]."-".$current_date["mon"]."-".$current_date["mday"];
        $value.=" ".$current_date["hours"].":".$current_date["minutes"].":".$current_date["seconds"];
    }
    $arr = explode(" ", $value);
    $valuedate=$arr[0];
    if ($value=="0") $valuetime=0;
    else $valuetime=$arr[1];
    $TMP_cont.=RAD_inputDate($TMP_name,$valuedate,$type, $onChange,$formName,$extra,$onBlur);
    $TMP_cont.= "&nbsp;&nbsp;";
    $TMP_cont.=RAD_inputTime($TMP_name,$valuetime,$type, $onChange,$formName,$onBlur);
    return $TMP_cont;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_inputDateTimeText($TMP_name,$value,$type, $onChange,$formName="F",$extra,$onBlur="") {
    if ($value == "") {
        $current_date = getdate();
        $value=$current_date["year"]."-".$current_date["mon"]."-".$current_date["mday"];
        $value.=" ".$current_date["hours"].":".$current_date["minutes"].":".$current_date["seconds"];
    }
    $arr = explode(" ", $value);
    $valuedate=$arr[0];
    if ($value=="0") $valuetime=0;
    else $valuetime=$arr[1];
    $TMP_cont.=RAD_inputDateText($TMP_name,$valuedate,$type, $onChange,$formName,$extra,$onBlur);
    $TMP_cont.= "&nbsp;&nbsp;";
    $TMP_cont.=RAD_inputTimeText($TMP_name,$valuetime,$type, $onChange,$formName,$onBlur);
    return $TMP_cont;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_array2select($arr, $sel = "") {
    reset($arr);
    $sel="".$sel;
    while (list($key, $val)=each($arr)) {
        $key="".$key;
        $TMP_cont.= "<OPTION VALUE=\"$key\"";
        if($sel!="" && $key == $sel) $TMP_cont.= " SELECTED";
        $TMP_cont.= ">$val</OPTION>\n";
    }
    return $TMP_cont;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_array2selectMultiplecolor($arr, $arrlevel, $sel = "") {
    reset($arr);
    $sel="".$sel;
    while (list($key, $val)=each($arr)) {
        $key="".$key;
        $TMP_cont.= "<OPTION class='optlevel".$arrlevel[$key]."' VALUE=',$key,'";
        if(@eregi(",".$key.",", $sel)) $TMP_cont.= " SELECTED";
        $TMP_cont.= ">$val</OPTION>\n";
    }
    return $TMP_cont;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_array2selectcolor($arr, $arrlevel, $sel = "") {
    reset($arr);
    $sel="".$sel;
    while (list($key, $val)=each($arr)) {
        $key="".$key;
        $TMP_cont.= "<OPTION class='optlevel".$arrlevel[$key]."' VALUE=\"$key\"";
        if($sel!="" && $key == $sel) $TMP_cont.= " SELECTED";
        $TMP_cont.= ">$val</OPTION>\n";
    }
    return $TMP_cont;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_array2selectMultiple($arr, $sel = "") {
    reset($arr);
    while ( list( $key, $val ) = each($arr)) {
        $TMP_cont.= "<OPTION VALUE=',$key,'";
        if(@eregi(",".$key.",", $sel)) $TMP_cont.= " SELECTED";
        $TMP_cont.= ">$val</OPTION>\n";
    }
    return $TMP_cont;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_array2radio($arr,$TMP_name,$sel = "",$onChange,$onBlur="") {
global $func;
    while ( list( $key, $val ) = each($arr)) {
        if($sel!="" && $key == $sel) $select_v=" checked";
        else $select_v="";
            if ($func!="searchform") $TMP_onkeydown="onKeyDown=\"javascript:if(event.keyCode==13) RAD_focusNextField('');\"";
            else $TMP_onkeydown="";
        $TMP_cont.= "<input type=radio $TMP_onkeydown name=\"$TMP_name\" id=\"".$TMP_name.$key."\" value=\"$key\" ".$onBlur.$onChange.$select_v."><label for='".$TMP_name.$key."'>".$val."</label>\n";
    }
    return $TMP_cont;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_ampliatree($level,$cmdSQLtree, $pfieldparent, $value, $pfilterBase, $fdtype) {
global $RAD_dbi, $PHP_SELF, $V_dir, $V_mod, $func, $findex, $fields, $db, $arr, $arrlevel, $arrtmp, $pfname, $pftitle, $pftitle1, $pftitle2;
    if ($fdtype=="plistdbmtree") $TMP_sql=$cmdSQLtree." WHERE ($pfieldparent='".$value."' OR $pfieldparent LIKE '%,".$value.",%')";
    else $TMP_sql=$cmdSQLtree." WHERE $pfieldparent='".$value."'";
    if ($pfilterBase!="") $TMP_sql.=" AND (".$pfilterBase.")";
    $TMP_result = sql_query($TMP_sql, $RAD_dbi);
    $prefijo=str_repeat(" __ ",$level);
    $lang=getSessionVar("SESSION_lang");
    while($TMP_row = sql_fetch_array($TMP_result, $RAD_dbi)) {
    if ($TMP_row[$pftitle."_".$lang]!="") $TMP_row[$pftitle]=$TMP_row[$pftitle."_".$lang];
    if ($arrtmp[1]!="") {
        $pftitle1=$arrtmp[0];
        $pftitle2=$arrtmp[1];
        if ($TMP_row[$pftitle1."_".$lang]!="") $TMP_row[$pftitle1]=$TMP_row[$pftitle1."_".$lang];
        if ($TMP_row[$pftitle2."_".$lang]!="") $TMP_row[$pftitle2]=$TMP_row[$pftitle2."_".$lang];
        if ($TMP_row[$pfname]."" === $value."") $literalpopupdb=$TMP_row[$pftitle1]." ".$TMP_row[$pftitle2];
        if(@eregi(",".$TMP_row[$pfname].",", $value)) $literalpopupdbm.=$TMP_row[$pftitle1]." ".$TMP_row[$pftitle2]."\n";
        $arr[$TMP_row[$pfname]] = $prefijo.$TMP_row[$pftitle1]." ".$TMP_row[$pftitle2];
    } else {
        if ($TMP_row[$pfname]."" === $value."") $literalpopupdb=$TMP_row[$pftitle];
        if(@eregi(",".$TMP_row[$pfname].",", $value)) $literalpopupdbm.=$TMP_row[$pftitle]."\n";
        $arr[$TMP_row[$pfname]] = $prefijo.$TMP_row[$pftitle];
    }
    $arrlevel[$TMP_row[$pfname]] = $level;
    $TMP_level=$level+1;
    RAD_ampliatree($TMP_level,$cmdSQLtree,$pfieldparent,$TMP_row[$pfname],$pfilterBase, $fdtype);
    }
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_edfdplistdbtree($fieldname, $fdtype, $fextra="", $onChange="", $fcanbenull=true, $value = "", $fname ="", $formName="F", $TMP_onFocus, $onBlur="") {
global $RAD_dbi, $PHP_SELF, $V_dir, $V_mod, $func, $findex, $fields, $db, $arr, $arrlevel, $arrtmp, $pfname, $pftitle, $pftitle1, $pftitle2, $RAD_selectsize;

    if ($func!="searchform") {
	//if (ereg("plist",$fdtype)) $TMP_onkeydown="onKeyDown=\"javascript:if((event.keyCode==32||event.keyCode==37||event.keyCode==38||event.keyCode==39||event.keyCode==40)&&(this.size<2)){RAD_simulateMouse(document.getElementById('".$fname."'),'mousedown');return false;} if(event.keyCode==13) RAD_focusNextField('');\""; 
	if (ereg("plist",$fdtype)) $TMP_onkeydown="onKeyDown=\"javascript:if((event.keyCode==32||event.keyCode==37||event.keyCode==38||event.keyCode==39||event.keyCode==40)&&(this.size<2)){RAD_simulateMouse(document.getElementById('".$fname."'),'mousedown');return false;};\""; 
	else $TMP_onkeydown="onKeyDown=\"javascript:if(event.keyCode==13) RAD_focusNextField('');\"";
    } else $TMP_onkeydown="";
    if ($fdtype == "fpopupdb" || $fdtype == "fbpopupdb") $TMP_onkeydown="";

    $arr = explode(":", $fextra);
    $ptablename = $arr[0];
    $pfname = $arr[1];
    $pftitle = $arr[2];
    $pfilter = $arr[3]; $pfilterBase=$pfilter;
    $pfieldparent = $arr[4];
    $arrtmp[0]=""; $arrtmp[1]="";
    $arrtmp = explode(",",$pfname);
    $ORDER=" Order BY ".$pftitle;
    $WHERE="";
    $WHERE_NULL="";
    if ($arrtmp[1]!="") {
    $pfname=$arrtmp[0];
    $nameparam=$arrtmp[1];
    $indexparam=$findex["$nameparam"];
    if ($indexparam=="") $indexparam=$findex["V0_".$nameparam];
    $param=$db->Record[$fields[$indexparam]->name];
    if ($fname!=$fieldname) {
        $arrtmpx = explode("_",$fname);
        if (isset($arrtmpx[0])) $arrtmpx[0] .= "_";
        else $arrtmpx[0] = "";
    }
    $WHERE=" WHERE $arrtmp[1]='$param'";
    $WHERE_NULL=" WHERE ($arrtmp[1] IS NULL OR $arrtmp[1]='')";
    if ($fields[$indexparam]->dtype=="popupdb" || $fields[$indexparam]->dtype=="popupdbtree" || $fields[$indexparam]->dtype=="fpopupdb" || $fields[$indexparam]->dtype=="bpopupdb" || $fields[$indexparam]->dtype=="fbpopupdb") $nameparam="document.forms.".$formName.".".$arrtmpx[0].$nameparam.".value";
    else $nameparam="document.forms.".$formName.".".$arrtmpx[0].$nameparam."[document.forms.".$formName.".".$arrtmpx[0].$nameparam.".selectedIndex].value";
    }
    if ($pfieldparent != "") {
    if ($pfilter!="") $pfilter.=" AND ($pfieldparent IS NULL OR $pfieldparent='')";
    else $pfilter="$pfieldparent IS NULL OR $pfieldparent=''";
    }
    if ($pfilter!="") {
    if ($WHERE=="") $WHERE=" WHERE ".$pfilter;
    else $WHERE.=" AND (".$pfilter.")";
    if ($WHERE_NULL=="") $WHERE_NULL=" WHERE ".$pfilter;
    else $WHERE_NULL.=" AND (".$pfilter.")";
    }
    $cmdSQL="SELECT $pfname FROM $ptablename ".$WHERE." ".$ORDER;
    $cmdSQLcount="SELECT count(*) FROM $ptablename ".$WHERE." ".$ORDER;
    $TMP_resultc = sql_query($cmdSQLcount, $RAD_dbi);
    $TMP_rowc = sql_fetch_array($TMP_resultc, $RAD_dbi);
    $TMP_num_rows=$TMP_rowc[0];
    if ($TMP_num_rows==0) $WHERE=$WHERE_NULL;
    if ($pftitle) $cmdSQLtree="SELECT $pfname,$pftitle FROM $ptablename ";
    else $cmdSQLtree="SELECT $pfname FROM $ptablename";
    $cmdSQLtree="SELECT * FROM $ptablename";
    $cmdSQL=$cmdSQLtree.$WHERE." ".$ORDER;

    $TMP_result = sql_query($cmdSQL, $RAD_dbi);
    $arr = array();
    $arrlevel = array();
    $arrtmp = explode(",",$pftitle);
    if (!isset($arrtmp[1])) $arrtmp[1] = "";
    $lang=getSessionVar("SESSION_lang");
    while($TMP_row = sql_fetch_array($TMP_result, $RAD_dbi)) {
    if ($TMP_row[$pftitle."_".$lang]!="") $TMP_row[$pftitle]=$TMP_row[$pftitle."_".$lang];
    if ($arrtmp[1]!="") {
        $pftitle1=$arrtmp[0];
        $pftitle2=$arrtmp[1];
        if ($TMP_row[$pftitle1."_".$lang]!="") $TMP_row[$pftitle1]=$TMP_row[$pftitle1."_".$lang];
        if ($TMP_row[$pftitle2."_".$lang]!="") $TMP_row[$pftitle2]=$TMP_row[$pftitle2."_".$lang];
        if ($TMP_row[$pfname]."" === $value."") $literalpopupdb=$TMP_row[$pftitle1]." ".$TMP_row[$pftitle2];
        if(@eregi(",".$TMP_row[$pfname].",", $value)) $literalpopupdbm.=$TMP_row[$pftitle1]." ".$TMP_row[$pftitle2]."\n";
        $arr[$TMP_row[$pfname]] = $TMP_row[$pftitle1]." ".$TMP_row[$pftitle2];
    } else {
        if ($TMP_row[$pfname]."" === $value."") $literalpopupdb=$TMP_row[$pftitle];
        if(@eregi(",".$TMP_row[$pfname].",", $value)) $literalpopupdbm.=$TMP_row[$pftitle]."\n";
        $arr[$TMP_row[$pfname]] = $TMP_row[$pftitle];
    }
    $arrlevel[$TMP_row[$pfname]] = "0";
    if ($pfieldparent!="") RAD_ampliatree(1,$cmdSQLtree,$pfieldparent,$TMP_row[$pfname],$pfilterBase, $fdtype);
    }
    $TMP_cont.= "<SELECT id='$fname' $TMP_onkeydown onfocus=\"javascript:RAD_setselFieldName('".$fname."');".$TMP_onFocus."\" NAME=".$fname;
    if (count($arr)>5) $TMP_size=5; else $TMP_size=count($arr);
    if ($RAD_selectsize>0 && count($arr)>$RAD_selectsize) $TMP_size=$RAD_selectsize;
    if ($TMP_size<2) $TMP_size=2; else $TMP_size++;
    if ($fdtype == "plistdbmtree") $TMP_cont.= "[] MULTIPLE SIZE=".$TMP_size." ID='ID_".$fname."'";
    else $TMP_cont.= " SINGLE";
    $TMP_cont.= $onBlur.$onChange.">\n";
    if ($fcanbenull) { $TMP_cont.= "<OPTION></OPTION>\n"; }
    if($fdtype=="plistdbm"||$fdtype == "plistdbmtree") $TMP_cont.= RAD_array2selectMultiplecolor($arr, $arrlevel, $value);
    else $TMP_cont.= RAD_array2selectcolor($arr, $arrlevel, $value);
    $TMP_cont.= "</SELECT>\n";
    return $TMP_cont;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_editfield($fieldname, $fdtype, $flength, $filength, $fextra="", $TMP_onChange="", $fcanbenull=true, $value = "", $fname ="",$formName="F", $TMP_onFocus="", $TMP_onBlur="") {
global $RAD_plistnonull, $RAD_rellenaDecimales, $RAD_CKEditor, $RAD_dbi, $PHP_SELF, $V_dir, $V_mod, $V_roi, $V_idmod, $func, $findex, $fields, $db, $PHPSESSID, $dbname, $zindex, $RAD_OpenW_width, $RAD_OpenW_height, $RAD_selectsize;
    if ($RAD_OpenW_width=="") $RAD_OpenW_width="400";
    if ($RAD_OpenW_height=="") $RAD_OpenW_height="500";
    if ($zindex=="") $zindex=9999;
    $zindex--;
    if (preg_match("/ajax/i",$fdtype)) {
        include_once("functions_ajax.php");
        return RAD_editfieldajax($fieldname,$fdtype,$flength,$filength,$fextra,$TMP_onChange,$fcanbenull,$value,$fname,$formName,$TMP_onFocus); // Delega en subfuncion de ajax
    }
    $RAD_ckeditorconfigOld=", { filebrowserBrowseUrl : \"/images/ckeditor/filemanager/browser/default/browser.html?Connector=/images/ckeditor/filemanager/browser/default/connectors/php/connector.php\",
		filebrowserImageBrowseUrl : \"/images/ckeditor/filemanager/browser/default/browser.html?Type=Image&Connector=/images/ckeditor/filemanager/browser/default/connectors/php/connector.php\",
		filebrowserFlashBrowseUrl : \"/images/ckeditor/filemanager/browser/default/browser.html?Type=Flash&Connector=/images/ckeditor/filemanager/browser/default/connectors/php/connector.php\" }";
    $RAD_ckeditorconfig=", { filebrowserBrowseUrl : \"images/ckeditor/pfb/index.php?editor=ckeditor\",
		filebrowserImageBrowseUrl : \"images/ckeditor/pfb/index.php?editor=ckeditor&filter=image\",
		filebrowserFlashBrowseUrl : \"images/ckeditor/pfb/index.php?editor=ckeditor&filter=flash\" }";

    if (_DEF_NLSSearchIcon!="" && _DEF_NLSSearchIcon!="_DEF_NLSSearchIcon") {
        $imagenlupa=_DEF_NLSSearchIcon;
    }elseif (file_exists("themes/".getSessionVar("SESSION_theme")."/lupa.gif")) {
        $imagenlupa="themes/".getSessionVar("SESSION_theme")."/lupa.gif";
    }else{
        $imagenlupa="images/lupa.gif";
    }

    if (eregi("db",$fdtype) && eregi("popup",$fdtype) && $findex[$fieldname]=="") echo "\n\n<! El campo ".$fieldname." necesita ser definido en el modulo ".$V_dir."/".$V_mod." >\n\n";
    if ($formName=="") $formName="F";

    if (ereg("date",$fdtype)) {
            if (substr($value,0,10)=="0000-00-00") $value="0";
        }
    if (eregi("onblur=",$TMP_onChange)) {
        $TMP=$TMP_onChange;
        $posonChange=strpos($TMP,"onchange=");
        $posonBlur=strpos($TMP,"onblur=");
        if ($posonChange<$posonBlur) {
            $TMP_onChange=substr($TMP,$posonChange+9,$posonBlur-$posonChange-9);
            $TMP_onBlur=substr($TMP,$posonBlur+7);
        }
        if ($posonBlur<$posonChange) {
            $TMP_onBlur.=substr($TMP,$posonBlur+7,$posonChange-$posonBlur-7);
            $TMP_onChange=substr($TMP,$posonChange+9);
        }
    }
    $TMP_onBlur=str_replace("'",'"',$TMP_onBlur);

    if (ereg("popup",$fdtype)) {
	$arr = explode("|", $fextra);
	if (count($arr)>1) {
		$fextra=$arr[0];
		$parmlist=$arr[1];
	}
        if ($parmlist!="") {
            $parmlistitem=explode(",",$parmlist.",");
            $ki_cont=1;
            for($ki=0; $ki<count($parmlistitem); $ki++) {
                if (trim($parmlistitem[$ki])!="") {
                $URL_params.="'&param".$ki_cont."='+escape(document.".$formName.".V0_".$parmlistitem[$ki].".value)+";
                $ki_cont++;
                }
            }
        }
    }

    if ($func!="searchform") {
	//if (ereg("plist",$fdtype)) $TMP_onkeydown="onKeyDown=\"javascript:if((event.keyCode==32||event.keyCode==37||event.keyCode==38||event.keyCode==39||event.keyCode==40)&&(this.size<2)){RAD_simulateMouse(document.getElementById('".$fname."'),'mousedown');return false;} if(event.keyCode==13) RAD_focusNextField('');\""; 
	if (ereg("plist",$fdtype)) $TMP_onkeydown="onKeyDown=\"javascript:if((event.keyCode==32||event.keyCode==37||event.keyCode==38||event.keyCode==39||event.keyCode==40)&&(this.size<2)){RAD_simulateMouse(document.getElementById('".$fname."'),'mousedown');return false;};\""; 
	else $TMP_onkeydown="onKeyDown=\"javascript:if(event.keyCode==13) RAD_focusNextField('');\"";
    } else $TMP_onkeydown="";

    if (($fdtype=="plistm"||$fdtype=="checkboxm"||ereg("dbm",$fdtype)) && trim($value)!="") {
        if (substr($value,0,1)!=",") $value=",".$value;
        if (substr($value,strlen($value)-1)!=",") $value=$value.",";
    }

    if ($fname == "") $fname = $fieldname;

    if ($fdtype == "fpopupdb" || $fdtype == "fbpopupdb") $TMP_onChange .=";document.forms.".$formName.".".$fname."_literal.value=\"\";";

    $TMP_onChange=trim($TMP_onChange); 
    if ($TMP_onChange==";") $TMP_onChange="";
    if ($TMP_onChange!="") $onChange = " onChange='javascript:".str_replace("'",'"',$TMP_onChange).";' ";
    else $onChange = "";
    //if (ereg("plist",$fdtype)) $TMP_onBlur="this.size=1;".$TMP_onBlur;
    if ($TMP_onBlur !="") $onBlur = " onBlur='javascript:".$TMP_onBlur.";' ";

    if (eregi("db",$fdtype) && $findex[$fieldname]!=="") {
        if (trim($fields[$findex[$fieldname]]->funcnew)!="") $TMP_funcnew=$fields[$findex[$fieldname]]->funcnew;
        if ($TMP_funcnew!="") if (is_modulepermitted("", $V_dir, $TMP_funcnew)) {
            $arr = explode(":", $fextra);
            $arr2= explode(",",$arr[1]);
            if (strlen($arr2[1])>1) {
                $V_roitmp=$arr2[1]."='";
                $TMP_onclickn="javascript:if (document.forms.".$formName.".V0_".$arr2[1].".value>0)
                    RAD_OpenW('$PHP_SELF?V_dir=$V_dir&V_mod=$TMP_funcnew&headeroff=X&footeroff=X&menuoff=X&func=new&subfunc=browse&V_roi=".urlencode($V_roitmp)."'+document.forms.".$formName.".V0_".$arr2[1].".value+'\'',1,1);
                    else
                    RAD_OpenW('$PHP_SELF?V_dir=$V_dir&V_mod=$TMP_funcnew&headeroff=X&footeroff=X&menuoff=X&func=new&subfunc=browse',1,1);
                    ";
            }
            else {
                $TMP_onclickn="javascript:RAD_OpenW('$PHP_SELF?V_dir=$V_dir&V_mod=$TMP_funcnew&headeroff=X&footeroff=X&menuoff=X&func=new&subfunc=browse&V_roi=".urlencode($V_roitmp)."');";
            }
            $TMP_linknew=" <input type=button onClick=\"$TMP_onclickn\" value='"._DEF_NLSNewString."'>";
        } else {
            $arrtmpx[0] = strpos($fname,"_");
            $arrtmpx[1] = substr($fname,$arrtmpx[0]+1);
            if (trim($fields[$findex[$arrtmpx[1]]]->funcnew)!="") $TMP_funcnew=$fields[$findex[$arrtmpx[1]]]->funcnew;
            if ($TMP_funcnew!="") if (is_modulepermitted("", $V_dir, $TMP_funcnew))
            $TMP_linknew=" <input type=button onClick=\"javascript:RAD_OpenW('$PHP_SELF?V_dir=$V_dir&V_mod=$TMP_funcnew&headeroff=X&footeroff=X&menuoff=X&func=new&subfunc=browse&V_roi=".urlencode($V_roitmp)."',1,1);\" value='"._DEF_NLSNewString."'>";
        }
    }
    $TMP_linkvalue="";
    if ($findex[$fieldname]!=="") {
      $TMP_funclink=trim($fields[$findex[$fieldname]]->funclink);
      $A_TMP_funclink=explode("/",$TMP_funclink);
      if (count($A_TMP_funclink)>1) { $TMP_funclink=$A_TMP_funclink[1]; $TMP_dir=$A_TMP_funclink[0]; }
      else { $TMP_dir=$V_dir; }
      if ($TMP_funclink!="") if (is_modulepermitted("", $TMP_dir, $TMP_funclink) || is_admin()) {
        //$TMP_linkvalue="<a href=\"javascript:RAD_OpenW('$PHP_SELF?V_dir=$TMP_dir&V_mod=$TMP_funclink&headeroff=X&footeroff=X&menuoff=X&func=detail&subfunc=browse&V_roi=".urlencode($V_roi).$SESSION_SID;
        $TMP_linkvalue="<img onclick=\"javascript:RAD_OpenW('$PHP_SELF?V_dir=$TMP_dir&V_mod=$TMP_funclink&dbname=$dbname&headeroff=X&footeroff=X&menuoff=&func=detail&subfunc=browse".$SESSION_SID;
      } else {
        $arrtmpx[0] = strpos($fname,"_");
        $arrtmpx[1] = substr($fname,$arrtmpx[0]+1);
        $TMP_funclink=trim($fields[$findex[$arrtmpx[1]]]->funclink);
        $A_TMP_funclink=explode("/",$TMP_funclink);
        if (count($A_TMP_funclink)>1) { $TMP_funclink=$A_TMP_funclink[1]; $TMP_dir=$A_TMP_funclink[0]; }
        else { $TMP_dir=$V_dir; }
        if ($TMP_funclink!="") if (is_modulepermitted("", $TMP_dir, $TMP_funclink) || is_admin()) {
        //$TMP_linkvalue="<a href=\"javascript:RAD_OpenW('$PHP_SELF?V_dir=$TMP_dir&V_mod=$TMP_funclink&headeroff=X&footeroff=X&menuoff=&func=detail&subfunc=browse&V_roi=".urlencode($V_roi).$SESSION_SID;
        $TMP_linkvalue="<img onclick=\"javascript:RAD_OpenW('$PHP_SELF?V_dir=$TMP_dir&V_mod=$TMP_funclink&dbname=$dbname&headeroff=X&footeroff=X&menuoff=&func=detail&subfunc=browse".$SESSION_SID;
        }
      }
    }
    if ($TMP_linkvalue!="") {
        if (ereg("plist",$fdtype))
            $TMP_linkvalue.="&par0='+document.".$formName.".".$fname."[document.".$formName.".".$fname.".selectedIndex].value,1,1);\" src='".$imagenlupa."' border=0>";
        else if (ereg("rlist",$fdtype))
            $TMP_linkvalue.="&par0='+document.forms.".$formName.".elements.".$fname.".value,1,1);\" src='".$imagenlupa."' border=0>";
        else
            $TMP_linkvalue.="&par0='+document.".$formName.".".$fname.".value,1,1);\" src='".$imagenlupa."' border=0>";
    }

    if ($fdtype == "plistdbtree" || $fdtype == "plistdbmtree") {
        $TMP_cont=RAD_edfdplistdbtree($fieldname, $fdtype, $fextra, $onChange, $fcanbenull, $value, $fname, $formName, $TMP_onFocus, $onBlur);
        return "<nobr>".$TMP_cont.$TMP_linkvalue.$TMP_linknew."</nobr>";
    }
    if ($fdtype == "plistdb" || $fdtype == "rlistdb" || $fdtype == "popupdb" || $fdtype == "popupdbtree" || $fdtype == "fpopupdb" || $fdtype == "bpopupdb"  || $fdtype == "fbpopupdb" || $fdtype == "popupdbm" || $fdtype == "plistdbm" || $fdtype == "checkboxdbm" || $fdtype == "checkboxdb") {
        $arr = explode(":", $fextra);
        $ptablename = $arr[0];
        $pfname = $arr[1];
        $pftitle = $arr[2];
        $pfilter = $arr[3];
        $porder = $arr[4];
        if ($porder=="") $porder = $arr[2];
        $arrtmp[0]=""; $arrtmp[1]=""; $arrtmp[2]="";
        $arrtmp = explode(",",$pfname);
        $nameparam="''";
        $WHERE="";
        $WHERE_NULL="";
        if ($arrtmp[1]!="") {
            $pfname=$arrtmp[0];
            $nameparam=$arrtmp[1];

            if ($fname!=$fieldname) {
                $arrtmpx = explode("_",$fname);
                if (isset($arrtmpx[0])) $arrtmpx[0] .= "_";
                else $arrtmpx[0] = "";
            }

            $indexparam=$findex["$nameparam"];
            if ($indexparam=="") $indexparam=$findex[$arrtmpx[0].$nameparam];
            if ($indexparam!="") $param=$db->Record[$fields[$indexparam]->name];


            if ($arrtmp[2]!="") { // tercer valor en la posicion 1 del extra. El nombre del campo es el ultimo y el valor lo cojemos del 1.
            //  $nameparam=$arrtmp[2];
                $indexparam=$findex[$arrtmp[1]];
            //  $param=$db->Record[$fields[$indexparam]->name];
            }

            if ($param!='') { // BUG: 4/10/06 < Un municipio sin provincia no recupera el valor al ser editado otra vez. Agrego el IF
                $WHERE=" WHERE $nameparam='$param'";
                $WHERE_NULL=" WHERE ($nameparam IS NULL OR $nameparam='')";
                $WHERE_NULL="";
            }
            if ($indexparam!=""&&substr($fields[$indexparam]->dtype,0,5)=="plist") $nameparam="document.forms.".$formName.".".$arrtmpx[0].$nameparam."[document.forms.".$formName.".".$arrtmpx[0].$nameparam.".selectedIndex].value";
            else $nameparam="document.forms.".$formName.".".$arrtmpx[0].$nameparam.".value";
        }
        if ($value!=""||$fdtype=="plistdb"||$fdtype=="rlistdb"||$fdtype=="plistdbm"||$fdtype=="checkboxdbm") { // OJO: Coge valores solo si hay valor en el campo, o es un plistdb
          if ($pfilter!="") {
            if ($WHERE=="") $WHERE=" WHERE ".$pfilter;
            else $WHERE.=" AND (".$pfilter.")";
            if ($WHERE_NULL=="") $WHERE_NULL=" WHERE ".$pfilter;
            else $WHERE_NULL.=" AND (".$pfilter.")";
          }
          if (!strstr($pfname,'(') && $value!="" && ($fdtype=="popupdb"||$fdtype == "popupdbtree"||$fdtype=="fpopupdb"||$fdtype=="bpopupdb"||$fdtype=="fbpopupdb"||$fdtype=="popupdb")) {
            if ($WHERE=="") $WHERE=" WHERE ".$pfname."='".$value."'";
            else $WHERE.=" AND ".$pfname."='".$value."'";
            if ($WHERE_NULL=="") $WHERE_NULL=" WHERE ".$pfname."='".$value."'";
            else $WHERE_NULL.=" AND ".$pfname."='".$value."'";
          }
          if (!ereg("popupdb",$fdtype) || $value!="") {
            $TMP_resultc = sql_query("SELECT count(*) from $ptablename".$WHERE, $RAD_dbi);
            $TMP_rowc = sql_fetch_array($TMP_resultc, $RAD_dbi);
            $TMP_num_rows=$TMP_rowc[0];
            if ($TMP_num_rows==0) {
                $TMP_resultc = sql_query("SELECT count(*) from $ptablename".$WHERE_NULL, $RAD_dbi);
                $TMP_rowc = sql_fetch_array($TMP_resultc, $RAD_dbi);
                $TMP_num_rows=$TMP_rowc[0];
            $WHERE=$WHERE_NULL;
            }

            $ORDER="";
            if ($porder!="" && !ereg("\(",$porder)) $ORDER=" Order by $porder";
            if (ereg("popupdb",$fdtype) && $value=="") $ORDER="";
            if ($pftitle) {
            $cmdSQL="SELECT $pfname,$pftitle FROM $ptablename".$WHERE.$ORDER;
            $cmdSQL="SELECT * FROM $ptablename".$WHERE.$ORDER;
            } else {
            if (strstr($pfname,'(')) {
                $cmdSQL="SELECT $pfname FROM $ptablename".$WHERE.$ORDER;
            } else {
                $cmdSQL="SELECT $pfname FROM $ptablename".$WHERE.$ORDER;
                $cmdSQL="SELECT * FROM $ptablename".$WHERE.$ORDER;
            }
            }
            $cmdSQL2=$cmdSQL;

            $TMP_result = sql_query($cmdSQL, $RAD_dbi);
            if ($TMP_num_rows==0) $WHERE=$WHERE_NULL;
            //if ($TMP_num_rows>10000) { // FALTA controlar excesivos resultados que bloquean el navegador en los plistdb
            //  if ($fdtype=="plistdb") $fdtype="popupdb";
            //  if ($fdtype=="plistdbm") $fdtype="popupdbm";
            //}
            if ($value!="" && ereg("popupdb",$fdtype) && $TMP_num_rows==0) $WHERE=$WHERE_NULL;
//xx            if (($arrtmp[1]=="")&&($fdtype == "popupdb" || $fdtype == "popupdbtree" || $fdtype == "fpopupdb" || $fdtype == "bpopupdb" || $fdtype == "fbpopupdb" || $fdtype == "popupdbm")) $WHERE="";

            if ($pftitle) {
            $cmdSQL="SELECT $pfname,$pftitle FROM $ptablename".$WHERE.$ORDER;
            $cmdSQL="SELECT * FROM $ptablename".$WHERE.$ORDER;
            } else {
            if (strstr($pfname,'(')) {
                $cmdSQL="SELECT $pfname FROM $ptablename".$WHERE.$ORDER;
            } else {
                $cmdSQL="SELECT $pfname FROM $ptablename".$WHERE.$ORDER;
                $cmdSQL="SELECT * FROM $ptablename".$WHERE.$ORDER;
            }
            }
            $cmdSQL="SELECT * FROM $ptablename".$WHERE.$ORDER;

            if ($cmdSQL2!=$cmdSQL) $TMP_result = sql_query($cmdSQL, $RAD_dbi);
            $arr = array();
            $lang=getSessionVar("SESSION_lang");
            while($TMP_row = sql_fetch_array($TMP_result, $RAD_dbi)) {
            $arrtmp = explode(",",$pftitle);
            if (count($arrtmp)>0) {
                $TMP_literalpopupdb="";
                for ($k=0; $k<count($arrtmp); $k++) if ($TMP_row[$arrtmp[$k]."_".$lang]!="") $TMP_row["$arrtmp[$k]"]=$TMP_row[$arrtmp[$k]."_".$lang];
                for ($k=0; $k<count($arrtmp); $k++) $TMP_literalpopupdb.=$TMP_row["$arrtmp[$k]"]." ";
                $TMP_literalpopupdb=substr($TMP_literalpopupdb,0,-1);
                if ($TMP_row[$pfname]."" === $value."") $literalpopupdb=$TMP_literalpopupdb;
                $TMP_row[$pfname]=str_replace("?","",$TMP_row[$pfname]);
                if ($value!="") if(@eregi(",".$TMP_row[$pfname].",", $value)) $literalpopupdbm.=$TMP_literalpopupdb."\n";
                $arr[$TMP_row[$pfname]] = $TMP_literalpopupdb;
            } else {
                if ($TMP_row[$pftitle."_".$lang]!="") $TMP_row[$pftitle]=$TMP_row[$pftitle."_".$lang];
                if ($TMP_row[$pfname]."" === $value."") $literalpopupdb=$TMP_row[$pftitle];
                if ($value!="") if(@eregi(",".$TMP_row[$pfname].",", $value)) $literalpopupdbm.=$TMP_row[$pftitle]."\n";
                $arr[$TMP_row[$pfname]] = $TMP_row[$pftitle];
            }
            if ($filength<$flength && ($fdtype=="plistdb"||$fdtype=="plistdbm")) { if (strlen($arr[$TMP_row[$pfname]])>$flength) $arr[$TMP_row[$pfname]]=substr($arr[$TMP_row[$pfname]],0,$flength)."..."; }
            $arr[$TMP_row[$pfname]]=$arr[$TMP_row[$pfname]];
            }
          }
        } // OJO: Coge valores solo si hay valor en el campo
    }
    if ($fdtype == "plist" || $fdtype == "plistm") {
        $TMP_cont.= "<SELECT id='$fname' $TMP_onkeydown onfocus=\"javascript:RAD_setselFieldName('".$fname."');".$TMP_onFocus."\" NAME=".$fname;
	if (ereg("\|",$fextra)) $ks = explode("|", $fextra);
        else $ks = explode(",", $fextra);
        if (count($ks)>5) $TMP_size=5; else $TMP_size=count($ks);
	if ($RAD_selectsize>0 && count($ks)>$RAD_selectsize) $TMP_size=$RAD_selectsize;
        if ($TMP_size<2) $TMP_size=2; else $TMP_size++;
        if ($fdtype == "plistm") $TMP_cont.= "[] MULTIPLE SIZE=".$TMP_size." ID='ID_".$fname."'";
        else $TMP_cont.= " SINGLE";
        $TMP_cont.= $onBlur.$onChange.">\n";
        if ($fcanbenull || $RAD_plistnonull=="") { $TMP_cont.= "<OPTION></OPTION>\n"; }
        //$TMP_cont.= "<OPTION></OPTION>\n";  // Es mejor anhadir un campo vacio, el usuario suele elegir la opción por defecto y de esta manera se obliga a cubrir.
        if ($fextra =="") $fextra="0:No,1:Si";
        for ($ki = 0; $ki < count($ks); $ki++) {
            $kx = explode(":",$ks[$ki]);
            if($fdtype=="plistm") $tmpval=",".$kx[0].",";
            else $tmpval=$kx[0];
            $TMP_cont.= "<OPTION VALUE=\"$tmpval\"";
            if($fdtype=="plistm") { if(@eregi(",".$kx[0].",", $value)) $TMP_cont.= " SELECTED"; }
            else { if($value!="" && $kx[0]."" === $value."") $TMP_cont.= " SELECTED"; }
            $TMP_cont.= ">$kx[1]</OPTION>\n";
        }
        $TMP_cont.= "</SELECT>\n";
    } else if ($fdtype == "checkbox") {
        $arr = split(":", $fextra);
        if ($arr[1]=="") $arr[1]="1";
        $TMP_cont.= "<INPUT TYPE='CHECKBOX' NAME=".$fname;
        $TMP_cont.= " VALUE='".$arr[1]."'";
        if ($value."" === $arr[1]."") $TMP_cont.= " CHECKED";
        $TMP_cont.= $onBlur.$onChange.">\n";
    } else if ($fdtype == "checkboxm") {
        if ($fextra =="") $fextra="0:No,1:Si";
        $ks = explode(",", $fextra);
        for ($ki = 0; $ki < count($ks); $ki++) {
            $kx = explode(":",$ks[$ki]);
            $tmpval=",".$kx[0].",";
            $TMP_cont.= "<INPUT TYPE='CHECKBOX' NAME='".$fname."[".$ki."]'";
            $TMP_cont.= " VALUE='".$tmpval."'";
            $TMP_cont.= " ID='".$fname.$key.$ki."'";
            if(@eregi(",".$kx[0].",", $value)) $TMP_cont.= " CHECKED";
            $TMP_cont.= $onBlur.$onChange.">\n<label for='".$fname.$key.$ki."'>".$kx[1]."</label><br/>";
        }
    } else if ($fdtype == "checkboxdb" || $fdtype == "checkboxdbm") {
        if (count($arr)>0) {
            $ki=0;
            foreach ($arr as $key=>$lit) {
                $tmpval=",".$key.",";
                $TMP_cont.= "<INPUT TYPE='CHECKBOX' NAME='".$fname."[".$ki."]'";
                $TMP_cont.= " VALUE=',".$key.",'";
                $TMP_cont.= " ID='".$fname.$key.$ki."'";
                if(@eregi(",".$key.",", $value)) $TMP_cont.= " CHECKED";
                $TMP_cont.= $onBlur.$onChange.">\n<label for='".$fname.$key.$ki."'>".$lit."</label><br/>";
                $ki++;
            }
        }
    } else if ($fdtype == "plistdb" || $fdtype == "plistdbm") {
        $TMP_cont.= "<SELECT id='$fname' $TMP_onkeydown onfocus=\"javascript:RAD_setselFieldName('".$fname."');".$TMP_onFocus."\" NAME=".$fname;
        if (count($arr)>5) $TMP_size=5; else $TMP_size=count($arr);
	if ($RAD_selectsize>0 && count($arr)>$RAD_selectsize) $TMP_size=$RAD_selectsize;
        if ($TMP_size<2) $TMP_size=2; else $TMP_size++;
        if ($fdtype == "plistdbm") $TMP_cont.= "[] MULTIPLE SIZE=".$TMP_size." ID='ID_".$fname."'";
        else $TMP_cont.= " SINGLE";
        $TMP_cont.= $onBlur.$onChange.">\n";
        if ($fcanbenull) { $TMP_cont.= "<OPTION></OPTION>\n"; }
        //$TMP_cont.= "<OPTION></OPTION>\n"; // Es mejor anhadir un campo vacio, el usuario suele elegir la opcion por defecto y de esta manera se obliga a cubrir
        if($fdtype=="plistdbm") $TMP_cont.= RAD_array2selectMultiple($arr, $value);
        else $TMP_cont.= RAD_array2select($arr, $value);
        $TMP_cont.= "</SELECT>\n";
    } else if ($fdtype == "rlist") {
        if ($fextra =="") $fextra="0:No,1:Si";
	if (ereg("\|",$fextra)) $ks = explode("|", $fextra);
        else $ks = explode(",", $fextra);
        for ($ki = 0; $ki < count($ks); $ki++) {
            $kx = explode(":",$ks[$ki]);
            if($value!="" && $kx[0]."" === $value."") $select_v=" checked";
            else $select_v="";
            $TMP_cont.= "<input type=radio $TMP_onkeydown name=\"$fname\" id=\"".$fname.$kx[0]."\" value=\"$kx[0]\" $select_v $onBlur $onChange><label for='".$fname.$kx[0]."'>".$kx[1]."</label>\n";
        }
    } else if ($fdtype == "rlistdb") {
        $TMP_cont.= RAD_array2radio($arr, $fname, $value, $onChange, $onBlur);
    } else if (substr($fdtype,0,4) == "text") {
        if (!eregi("PDA",getSessionVar("SESSION_theme"))) {
          if ($fdtype == "texthtml") {
            //$TMP_cont.= "<script>\nvar FCK".$fname." = new FCKeditor ('".$fname."');\nFCK".$fname.".BasePath='templates/';\nFCK".$fname.".ReplaceTextarea();\n</script>\n";
          } else {
            if (!eregi("nohtml", $fextra)) {
                if ($RAD_CKEditor=="") $TMP_cont.= "<script>\nvar FCK".$fname." = new FCKeditor('".$fname."');\nFCK".$fname.".BasePath='templates/';\n</script>\n";
            }
            if (!eregi("nohtml", $fextra) || !eregi("nomag", $fextra)) {
                $TMP_cont.= "\n<span id='lupa".$fname."'>";
            }
            if (!eregi("nohtml", $fextra)) {
                if(file_exists("themes/".getSessionVar("SESSION_theme")."/edit2.png")) $imagenHTML="themes/".getSessionVar("SESSION_theme")."/edit2.png";
                else $imagenHTML="images/edit2.png";
                if ($RAD_CKEditor!="") $TMP_cont.= "<img onClick='javascript:CKEDITOR.replace(\"".$fname."\"".$RAD_ckeditorconfig.");document.getElementById(\"lupa".$fname."\").innerHTML=\"\";' src='".$imagenHTML."' border=0 title='HTML' alt='HTML'>";
                else $TMP_cont.= "<img onClick='javascript:FCK".$fname.".ReplaceTextarea();document.getElementById(\"lupa".$fname."\").innerHTML=\"\";' src='".$imagenHTML."' border=0 title='HTML' alt='HTML'>";
            }
            if (!eregi("nomag", $fextra)) {
                $TMP_cont.= "<img onClick='javascript:RAD_setselFieldName(\"$fname\");window.open(\"utils.php?func=amp&type=$fdtype&field=".urlencode($fname)."&formName=$formName\",\"_blank\",\"width=800,height=600,resizable=yes,scrollbars=yes,status=no,toolbar=no,menubar=yes,titlebar=no\");' src='".$imagenlupa."' border=0 title='"._DEF_NLSMagnifyString."' alt='"._DEF_NLSMagnifyString."'>";
            }
            if (!eregi("nohtml", $fextra) || !eregi("nomag", $fextra)) {
                $TMP_cont.="</span><br>\n";
            }
          }
        }
                $TMP_cont.="<script>function limitText(limitField, limitNum) {
                         if (limitField.value.length > limitNum) {
                             limitField.value = limitField.value.substring(0, limitNum);
                         }
                     }</script>";
        $TMP_cont.= "\n<TEXTAREA onFocus=\"javascript:RAD_setselFieldName('$fname');".$TMP_onFocus."\" NAME=".$fname." ";
        $TMP_width=480; $TMP_height=200;
        if ($filength != "") {
            $size = split("x", $filength);
            if (!isset($size[0])) $size[0] = 60;
            if (!isset($size[1])) $size[1] = 3;
            $cols = $size[0];
            $rows = $size[1];
            $TMP_width=$cols*6; if ($TMP_width<540) $TMP_width=540;
            $TMP_height=$rows*10; if ($TMP_height<100) $TMP_height=200;
            if ($rows < 1) { $rows = 3; }
            $TMP_cont.= "COLS=$cols ROWS=$rows";
        }
        $TMP_cont.= $onBlur.$onChange." ID=".$fname.">\n".htmlspecialchars($value)."</TEXTAREA>\n";
        if ($fdtype == "texthtml") {
	    if ($RAD_CKEditor!="") $TMP_cont.="\n<script type=\"text/javascript\">\nCKEDITOR.replace('".$fname."'".$RAD_ckeditorconfig.");\n</script>\n";
            else $TMP_cont.= "<script>\nvar FCK".$fname." = new FCKeditor ('".$fname."');\nFCK".$fname.".BasePath='templates/';\nFCK".$fname.".ReplaceTextarea();\n</script>\n";
        }
    } else if ($fdtype == "image" || $fdtype == "file") {
        $arr = split("\n", $value);
        if (count($arr)>0) {
            $TMP_contvalue=0;
            for ($ki=0; $ki<count($arr); $ki++) {
            if (trim($arr[$ki])!="") {
                $TMP_contvalue++;
                $TMP_pos=strpos($arr[$ki],".");
                $filenameprefix=substr($arr[$ki],0,$TMP_pos);
                $filenameprefix=str_replace("/","-",$filenameprefix);
                $Afilenameprefix=explode("-",$filenameprefix);
                if (count($Afilenameprefix)>5) {
                $filenameprefix=$Afilenameprefix[2]."/".$Afilenameprefix[1]."/".$Afilenameprefix[0]." ".$Afilenameprefix[3].":".$Afilenameprefix[4].":".$Afilenameprefix[5];
                }
                $filename=substr($arr[$ki],$TMP_pos+1);
                $TMP_value.="<a target=_blank href='files/$dbname/".$arr[$ki]."'>[".$filenameprefix."] ".$filename."</a> <input type=checkbox name='".$fname."_del".$TMP_contvalue."' value='$arr[$ki]'> "._DEF_NLSDeleteString."<br>";
            }
            }
        }
        $TMP_value=$TMP_value."<input type=hidden name='".$fname."_cont' value=$TMP_contvalue>";
        $iarr = array( "leave" => _NLSLeaveContent, "write" => _NLSOverwriteContent );
        if ($fcanbenull) { $iarr["clear"] = _NLSClearContent; }
        if ($TMP_onChange !="") $onChange = " onChange=\"javascript:if (this.value.length>0) agIF".$fname."();".$TMP_onChange.";this.onchange=function () { ".$TMP_onChange." }\" ";
        else $onChange = " onChange=\"javascript:if (this.value.length>0) agIF".$fname."();this.onchange=null;\" ";

        $TMP_limite=trim($fextra);
        if ($TMP_limite=='') $TMP_limite=99;
        if ($TMP_limite==0) $TMP_limite=1;
        if ($TMP_contvalue<$TMP_limite) {
            if (_DEF_upload_max_filesize!="" && _DEF_upload_max_filesize!="_DEF_upload_max_filesize") $TMP_upload_max_filesize=_DEF_upload_max_filesize;
            else $TMP_upload_max_filesize=get_cfg_var("upload_max_filesize");
            $TMP_cont.= "<INPUT class=button TYPE='FILE' NAME='".$fname."'".$onBlur.$onChange."> (<".$TMP_upload_max_filesize."b) <br><div id='F_".$fname."'></div>";
            $TMP_cont.= "\n<script language='JavaScript'>\nvar numF".$fname."=1;\n";
            $TMP_cont.= "var maxF".$fname."=".$TMP_limite.";";

            $TMP_cont.= "function agIF".$fname."() {\n if ((numF".$fname."+parseInt(document.forms.".$formName.".".$fname."_cont.value))>=maxF".$fname.") return;\n var elem=document.createElement('F_".$fname."'); elem.innerHTML='<input class=button ".$onBlur.$onChange."type=\"file\" name=\"".$fname."'+numF".$fname."+'\"> (<".$TMP_upload_max_filesize."b) <br>';\n document.getElementById('F_".$fname."').appendChild(elem);\n numF".$fname."++;\n}";
            $TMP_cont.= "\n</script>\n";
        }
//      if ($func!="new") {
//          $TMP_cont.= "<SELECT id='V_ACT_".$fname."' $TMP_onkeydown NAME='V_ACT_".$fname."'".$onBlur.$onChange.">";
//          $TMP_cont.= RAD_array2select($iarr);
//          $TMP_cont.= "</SELECT><pre>".$value."</pre><INPUT TYPE=HIDDEN NAME='V_A_".$fname."' VALUE='".$value."'>";
//      } else $TMP_cont.= "<INPUT TYPE=HIDDEN NAME='V_ACT_".$fname."' VALUE='leave'>";
        $TMP_cont.= "<INPUT TYPE=HIDDEN NAME='V_A_".$fname."' VALUE='".$value."'>".$TMP_value."<INPUT TYPE=HIDDEN NAME='V_ACT_".$fname."' VALUE='leave'>";
    } else if ($fdtype == "date") {
        if ($fcanbenull) $TMP_cont.=RAD_inputDate($fname,$value,"0",$onChange,$formName,$fextra,$onBlur);
        else $TMP_cont.=RAD_inputDate($fname,$value,"",$onChange,$formName,$fextra,$onBlur);
    } else if ($fdtype == "datetext") {
        if ($fcanbenull) $TMP_cont.=RAD_inputDateText($fname,$value,"0",$onChange,$formName,$fextra,$onBlur);
        else $TMP_cont.=RAD_inputDateText($fname,$value,"",$onChange,$formName,$fextra,$onBlur);
    } else if ($fdtype == "datetime") {
        if ($fcanbenull) $TMP_cont.=RAD_inputDateTime($fname,$value,"0",$onChange,$formName,$fextra,$onBlur);
        else $TMP_cont.=RAD_inputDateTime($fname,$value,"",$onChange,$formName,$fextra,$onBlur);
    } else if ($fdtype == "datetimetext") {
        if ($fcanbenull) $TMP_cont.=RAD_inputDateTimeText($fname,$value,"0",$onChange,$formName,$fextra,$onBlur);
        else $TMP_cont.=RAD_inputDateTimeText($fname,$value,"",$onChange,$formName,$fextra,$onBlur);
    } else if ($fdtype == "dateint") {
        if ($fcanbenull) $TMP_cont.=RAD_inputDateInt($fname,$value,"0",$onChange,$formName,$fextra,$onBlur);
        else $TMP_cont.=RAD_inputDateInt($fname,$value,"",$onChange,$formName,$fextra,$onBlur);
    } else if ($fdtype == "dateinttext") {
        if ($fcanbenull) $TMP_cont.=RAD_inputDateIntText($fname,$value,"0",$onChange,$formName,$fextra,$onBlur);
        else $TMP_cont.=RAD_inputDateIntText($fname,$value,"",$onChange,$formName,$fextra,$onBlur);
    } else if ($fdtype == "datetimeint") {
        if ($fcanbenull) $TMP_cont.=RAD_inputDateTimeInt($fname,$value,"0",$onChange,$formName,$fextra,$onBlur);
        else $TMP_cont.=RAD_inputDateTimeInt($fname,$value,"",$onChange,$formName,$fextra,$onBlur);
    } else if ($fdtype == "datetimeinttext") {
        if ($fcanbenull) $TMP_cont.=RAD_inputDateTimeIntText($fname,$value,"0",$onChange,$formName,$fextra,$onBlur);
        else $TMP_cont.=RAD_inputDateTimeIntText($fname,$value,"",$onChange,$formName,$fextra,$onBlur);
    } else if ($fdtype == "time" || $fdtype == "timeora") {
        if ($fcanbenull) $TMP_cont.=RAD_inputTime($fname,$value,"0",$onChange,$formName,$onBlur);
        else $TMP_cont.=RAD_inputTime($fname,$value,"",$onChange,$formName,$onBlur);
    } else if ($fdtype == "timetext") {
        if ($fcanbenull) $TMP_cont.=RAD_inputTimeText($fname,$value,"0",$onChange,$onBlur);
        else $TMP_cont.=RAD_inputTimeText($fname,$value,"",$onChange,$onBlur);
    } else if ($fdtype == "timeint") {
        if ($fcanbenull) $TMP_cont.=RAD_inputTimeInt($fname,$value,"0",$onChange,$formName,$onBlur);
        else $TMP_cont.=RAD_inputTimeInt($fname,$value,"",$onChange,$formName,$onBlur);
    } else if ($fdtype == "timeinttext") {
        if ($fcanbenull) $TMP_cont.=RAD_inputTimeIntText($fname,$value,"0",$onChange,$formName,$onBlur);
        else $TMP_cont.=RAD_inputTimeIntText($fname,$value,"",$onChange,$formName,$onBlur);
    } else {
        $TMP_readonly="";
        $TMP_disabled="";
        // Cambio el onkeydown pulsando enter por el blur. El mismo evento se mantiene llamando al blur.
        //if ($fdtype == "bpopupdb" || $fdtype == "fpopupdb" || $fdtype == "fbpopupdb") $TMP_onkeydown="onKeyDown=\"javascript:if(event.keyCode==120) blur();if(event.keyCode==13){setTimeout('RAD_focusNextField(\'\');',50);return false;}\" onBlur=\"RAD_setselFieldName('".$fname."_literal');RAD_dosel_".$fname."('".$fname."',document.forms.".$formName.".".$fname.",document.forms.".$formName.".".$fname."_literal,".$nameparam.");\"";
        if ($fdtype == "bpopupdb" || $fdtype == "fpopupdb" || $fdtype == "fbpopupdb") {
            $TMP_jsclear="{ document.forms.".$formName.".".$fname.".value='';document.forms.".$formName.".".$fname."_literal.value='';document.forms.".$formName.".".$fname."_literal.readOnly=false;document.forms.".$formName.".B_".$fname.".disabled=false;";
            if ($onChange!='') $TMP_jsclear.="document.forms.".$formName.".".$fname."_literal.onchange();";
            //if (!$fcanbenull) $TMP_jsclear.="alert('Debe introducir este campo');";
            $TMP_jsclear.="document.forms.".$formName.".".$fname."_literal.focus(); }";
            $TMP_onkeydown="onKeyDown=\"javascript:if((event.keyCode==27||event.keyCode==8||event.keyCode==46)&&this.form['B_".$fname."'].disabled) ".$TMP_jsclear." if((event.keyCode==120||event.keyCode==13)&&!this.form['B_".$fname."'].disabled) {RAD_setselFieldName('".$fname."_literal');RAD_dosel_".$fname."('".$fname."',document.forms.".$formName.".".$fname.",document.forms.".$formName.".".$fname."_literal,".$nameparam."); this.blur();}\" ";
            // NO SE DEBE LIMPIAR EL CAMPO AL PERDER EL FOCO $TMP_onkeydown.=" onBlur=\"javascript:RAD_cleanLiteral(this);\"";
	    if ($TMP_onChange !="") $onChange = " onChange='javascript:".$TMP_onChange.";";
	    else $onChange = " onChange='javascript:";
	    if ($nameparam=="''") $onChange.="RAD_setselFieldName(\"".$fname."_literal\");if (document.forms.".$formName.".".$fname."_literal.value!=\"\") RAD_dosel_".$fname."(\"".$fname."\",document.forms.".$formName.".".$fname.",document.forms.".$formName.".".$fname."_literal,\"\");RAD_cleanLiteral(this);'";
	    else $onChange.="RAD_setselFieldName(\"".$fname."_literal\");if (document.forms.".$formName.".".$fname."_literal.value!=\"\") RAD_dosel_".$fname."(\"".$fname."\",document.forms.".$formName.".".$fname.",document.forms.".$formName.".".$fname."_literal,".$nameparam.");RAD_cleanLiteral(this);'";
            // ESTO FALLA... if (!$fcanbenull) $TMP_onkeydown.=" currElem=this; setTimeout('if (currElem.value==\'\') { alert(\'Debe introducir este campo\'); currElem.focus(); }',100);\" ";
	    if ($value!='' && !ereg(":",$value)) {
                //$TMP_readonly=" readonly "; // al empezar a editar deja cambiar el campo sin necesidad de pulsar limpiar
                //$TMP_disabled=" disabled ";
            }
        }
        if ($fdtype=="stand" && $flength<40 && _DEF_JumpFieldLength=="1") {
            $onKeyUp = " onKeyUp='javascript:RAD_len".$fname."();' ";
            $TMP_cont.="\n<script>\nfunction RAD_len".$fname."(){\n  if(document.forms.".$formName.".$fname.value.length == $flength) RAD_focusNextField('".$fname."');\n}\n</script>\n";
        }
        if (substr($fdtype,0,3)=="num") {
	    $value=RAD_numero($value,$fextra);
	    if ($RAD_rellenaDecimales!="") $TMP_rellenaDecimales="var numif=RAD_rellenaDecimales(this.value,\"".$fextra."\");if(numif!=this.value)this.value=numif;";
	    else $TMP_rellenaDecimales="";
            if ($flength<40 && _DEF_JumpFieldLength=="1") {
                    $onBlur = " onBlur='javascript:var numif=RAD_validaEntero(this.value);if(numif!=this.value)this.value=numif;".$TMP_rellenaDecimales.";".$TMP_onBlur."'";
                    $onKeyUp = " style='text-align:right' onKeyUp='javascript:var numif=RAD_convertToNum(\" \"+this.value,\"".$fextra."\");if(numif!=thisvalue){this.value=numif;this.changed=true;}RAD_len".$fname."();' ";
                    $TMP_cont.="\n<script>\nfunction RAD_len".$fname."(){\n  if(document.forms.".$formName.".$fname.value.length == $flength) RAD_focusNextField('".$fname."');\n}\n</script>\n";
            } else {
                $onBlur = " onBlur='javascript:var numif=RAD_validaEntero(this.value);if(numif!=this.value)this.value=numif;".$TMP_rellenaDecimales.";".$TMP_onBlur."'";
                $onKeyUp = " style='text-align:right' onKeyUp='javascript:var numif=RAD_convertToNum(\" \"+this.value,\"".$fextra."\");if(numif!=this.value){this.value=numif;if(this.onchange!=null)this.onchange();}' ";
            }
	    if ($fdtype == "numD") $onKeyUp.= " DISABLED class=inputdisabled ";
	    if ($fdtype == "numR") $onKeyUp.= " READONLY class=inputreadonly ";
        }
        $TMP_cont.= "\n<INPUT onFocus=\"javascript:RAD_setselFieldName('$fname');".$TMP_onFocus."\" id='".$fname."' NAME='".$fname."'".$onBlur.$onChange." VALUE=\"".htmlspecialchars($value)."\"";
        if ($fdtype == "fpopupdb" || $fdtype == "fbpopupdb") $TMP_cont.= " SIZE='".$flength."' MAXLENGTH='".$flength."'";
        else $TMP_cont.= " SIZE='".$filength."' MAXLENGTH='".$flength."'";
        $literal="_literal";
        if ($fdtype == "password") $TMP_cont.= " TYPE=PASSWORD $TMP_onkeydown";
        if ($fdtype == "standD") $TMP_cont.= " DISABLED class=inputdisabled ";
        if ($fdtype == "standR") $TMP_cont.= " READONLY class=inputreadonly ";
        if ($fdtype != "popupdb" && $fdtype != "popupdbtree" && $fdtype != "bpopupdb" && $fdtype != "popupdbm") $TMP_cont.= $onKeyUp." TYPE=TEXT $TMP_onkeydown>\n";
        else {
            $fieldfname=$fname;
            $TMP_cont.= " TYPE=HIDDEN>";
        }
            if ($fdtype == "fpopupdb" || $fdtype == "fbpopupdb") {
            $flength=$filength;
            $fieldfname=$fname;
        }
            if ($fdtype == "popupdb" || $fdtype == "popupdbtree" || $fdtype == "fpopupdb") {
                $TMP_cont.= "<INPUT TYPE=TEXT $TMP_onkeydown NAME='".$fname."_literal'".$onBlur.$onChange." SIZE='".$filength."' MAXLENGTH='".$flength."' VALUE=\"".htmlspecialchars($literalpopupdb)."\" ";
//                 $TMP_cont.= "onfocus=\"javascript:RAD_setselFieldName('".$fieldfname."_literal');blur();RAD_dosel_".$fieldfname."('".$fieldfname."',document.forms.".$formName.".".$fname.",document.forms.".$formName.".".$fname.$literal.",".$nameparam.");".$TMP_onFocus."\"> ";
                $TMP_cont.= " onkeyup=\"javascript:document.forms.".$formName.".".$fname."_literal.value='';RAD_setselFieldName('".$fieldfname."_literal');RAD_dosel_".$fieldfname."('".$fieldfname."',document.forms.".$formName.".".$fname.",document.forms.".$formName.".".$fname.$literal.",".$nameparam.");\"";
                $TMP_cont.= " onclick=\"javascript:RAD_setselFieldName('".$fieldfname."_literal');RAD_dosel_".$fieldfname."('".$fieldfname."',document.forms.".$formName.".".$fname.",document.forms.".$formName.".".$fname.$literal.",".$nameparam.");\" ".$TMP_readonly."> ";
                $TMP_roi="?";
                $TMP_class='';
                if (_DEF_NLSSearchIcon!="" && _DEF_NLSSearchIcon!="_DEF_NLSSearchIcon") {
                    $TMP_roi=" <img src='"._DEF_NLSSearchIcon."' > ";
                    $TMP_class=" class='imagen' ";
                }
                $TMP_cont.= "<button type='button' ".$TMP_class." name='B_".$fname."' value='?' alt='"._DEF_NLSSearchString."' title='"._DEF_NLSSearchString."' onClick=\"javascript:RAD_setselFieldName('".$fieldfname."_literal');RAD_dosel_".$fieldfname."('".$fieldfname."',document.forms.".$formName.".".$fname.",document.forms.".$formName.".".$fname.$literal.",".$nameparam.");\" ".$TMP_disabled." >".$TMP_roi."</button>";
        }
            if ($fdtype == "bpopupdb" || $fdtype == "fbpopupdb") {
                $TMP_cont.= "<INPUT TYPE=TEXT autocomplete='off' $TMP_onkeydown NAME='".$fname."_literal'".$onBlur.$onChange." SIZE='".$filength."' MAXLENGTH='".$flength."' VALUE=\"".htmlspecialchars($literalpopupdb)."\" ".$TMP_readonly."> ";
                $TMP_roi="?";
                $TMP_class='';
                if (_DEF_NLSSearchIcon!="" && _DEF_NLSSearchIcon!="_DEF_NLSSearchIcon") {
                    $TMP_roi=" <img src='"._DEF_NLSSearchIcon."' > ";
                    $TMP_class=" class='imagen' ";
                }
                $TMP_cont.= "<button type='button' ".$TMP_class." name='B_".$fname."' value='?' alt='"._DEF_NLSSearchString."' title='"._DEF_NLSSearchString."' onClick=\"javascript:RAD_setselFieldName('".$fieldfname."_literal');RAD_dosel_".$fieldfname."('".$fieldfname."',document.forms.".$formName.".".$fname.",document.forms.".$formName.".".$fname.$literal.",".$nameparam.");\" ".$TMP_disabled." >".$TMP_roi."</button>";
        }
            if ($fdtype == "popupdbm") {
                if ($filength != "") {
                    $size = split("x", $filength);
                    if (!isset($size[0])) $size[0] = 60;
                    if (!isset($size[1])) $size[1] = 3;
                    $cols = $size[0];
                    $rows = $size[1];
                    if ($rows < 2) { $rows = 3; }
                }
                $TMP_roi="?";
                $TMP_class='';
                if (_DEF_NLSSearchIcon!="" && _DEF_NLSSearchIcon!="_DEF_NLSSearchIcon") {
                    $TMP_roi=" <img src='"._DEF_NLSSearchIcon."' > ";
                    $TMP_class=" class='imagen' ";
                }
                $TMP_cont.= "\n<TEXTAREA NAME='".$fname."_literal'".$onBlur.$onChange." COLS=".$cols." ROWS=".$rows." MAXLENGTH='".$flength."' ";
//              $TMP_cont.= "onfocus=\"javascript:RAD_setselFieldName('".$fieldfname."_literal');blur();RAD_dosel_".$fieldfname."('".$fieldfname."',document.forms.".$formName.".".$fname.",document.forms.".$formName.".".$fname.$literal.",".$nameparam.");".$TMP_onFocus."\">";
                $TMP_cont.= " onkeyup=\"javascript:RAD_setselFieldName('".$fieldfname."_literal');RAD_dosel_".$fieldfname."('".$fieldfname."',document.forms.".$formName.".".$fname.",document.forms.".$formName.".".$fname.$literal.",".$nameparam.");\"";
                $TMP_cont.= " onclick=\"javascript:RAD_setselFieldName('".$fieldfname."_literal');RAD_dosel_".$fieldfname."('".$fieldfname."',document.forms.".$formName.".".$fname.",document.forms.".$formName.".".$fname.$literal.",".$nameparam.");\">";
                $TMP_cont.= htmlspecialchars($literalpopupdbm)."</TEXTAREA>";

                $TMP_cont.= "<button type='button' ".$TMP_class." name='B_".$fname."' value='?' alt='"._DEF_NLSSearchString."' title='"._DEF_NLSSearchString."' onClick=\"javascript:RAD_setselFieldName('".$fieldfname."_literal');RAD_dosel_".$fieldfname."('".$fieldfname."',document.forms.".$formName.".".$fname.",document.forms.".$formName.".".$fname.$literal.",".$nameparam.");\">".$TMP_roi."</button>";
        }
        if ($fdtype=="popupdbSFF") {
            $TMP_cont.="\n<input type=button value='?' alt='"._DEF_NLSSearchString."' title='"._DEF_NLSSearchString."' onClick=\"javascript:RAD_OpenW('$PHP_SELF?V_dir=$V_dir&V_mod=$V_mod&headeroff=X&footeroff=X&menuoff=X&func=search_js&searchfield=$fname&dbname=$dbname&param='+escape(document.".$formName.".".$fname.".value)+".$URL_params."'$SESSION_SID',".$RAD_OpenW_width.",".$RAD_OpenW_height.");\">";
        }
        if ($fdtype=="fpopupdb") {
            $TMP_cont.="\n<input type=button name='CL_".$fname."' value='0' alt='"._DEF_NLSClearAll."' title='"._DEF_NLSClearAll."' onClick='javascript:document.forms.".$formName.".".$fname.".value=\"\";document.forms.".$formName.".".$fname."_literal.value=\"\";document.forms.".$formName.".".$fname.".focus();'>\n";
        }else if (eregi("popup", $fdtype) && $fdtype!="popupdbSFF") {
            $TMP_myOnclick="javascript:document.forms.".$formName.".".$fname.".value=\"\";document.forms.".$formName.".".$fname."_literal.value=\"\";document.forms.".$formName.".".$fname."_literal.readOnly=false;document.forms.".$formName.".B_".$fname.".disabled=false;";
            if ($onChange!='') $TMP_myOnclick.="document.forms.".$formName.".".$fname."_literal.onchange();";
            //if (!$fcanbenull) $TMP_myOnclick.="alert(\"Debe introducir este campo\");";
            $TMP_myOnclick.="document.forms.".$formName.".".$fname."_literal.focus(); ";
            $TMP_roi='0';
            $TMP_class='';
            if (_DEF_NLSCrossIcon!="" && _DEF_NLSCrossIcon!="_DEF_NLSCrossIcon") {
                $TMP_roi=" <img src='"._DEF_NLSCrossIcon."'> ";
                $TMP_class=" class='imagen' ";
            }
            $TMP_cont.="\n<button type='button' ".$TMP_class." name='CL_".$fname."' value='0' alt='"._DEF_NLSClearAll."' title='"._DEF_NLSClearAll."' onClick='".$TMP_myOnclick."'>".$TMP_roi."</button>\n";
        }
        if ($fieldfname!="") {
            $TMP_contp="\n<script type='text/javascript'>\nvar RAD_numdosel_".$fieldfname."=0;\nfunction RAD_dosel_".$fieldfname."(searchfield,search,res,param) {\n";
            $TMP_contp.="   f1=search;\n";
            $TMP_contp.="   f2=res;\n";
            $TMP_contp.="   f3=search;\n";
            $TMP_contp.="   if (document.forms.F.B_".$fieldfname.".disabled) return;\n";
            $TMP_contp.="   if (document.forms.F.".$fieldfname."_literal.readOnly) return;\n";
            $TMP_contp.="   RAD_numdosel_".$fieldfname."++; \n";
            $TMP_contp.="   setTimeout('RAD_numdosel_".$fieldfname."--',1000); \n";
            $TMP_contp.="   if (RAD_numdosel_".$fieldfname.">1) return;\n";
            $TMP_contp.="   if (selFieldName!=searchfield && selFieldName!=search.name && selFieldName!=res.name) { \n";
            $TMP_contp.="       if ((RAD_numdosel_".$fieldfname."%2)==0) {\n";
            $TMP_contp.="           RAD_setselFieldName(searchfield);\n";
            $TMP_contp.="           eval('document.forms.".$formName.".'+searchfield+'.focus()');\n";
            $TMP_contp.="       } else return;\n";
            $TMP_contp.="   }\n";
            $TMP_contp.="   eval('val=document.".$formName.".'+searchfield+'.value');\n";
	    $TMP_contp.="   x=".$RAD_OpenW_width.";\n";
	    $TMP_contp.="   y=".$RAD_OpenW_height.";\n";
	    $TMP_contp.="   if (window.screen){ if (x>screen.width) x=(screen.width-400)/2; if (y>screen.height) y=screen.height-400; if (x<60) { if (60>screen.width-100) x=screen.width-100; else x=60; } }\n";
            $TMP_contp.="   searchpopup = RAD_OpenW(\"".$PHP_SELF."?V_prevfunc=".$func."&func=search_js&dbname=".$dbname."&searchfield=\"+";
            if ($fdtype=="popupdbm") $TMP_contp.="escape(searchfield)+\"&searchval=&param=\"+escape(param)+\"&vlit=&\"+".$URL_params;
            else $TMP_contp.="escape(searchfield)+\"&searchval=\"+escape(search.value)+\"&param=\"+escape(param)+\"&vlit=\"+escape(res.value)+".$URL_params;

            //if ($V_roi!="") $TMP_cont.="\"&V_roi='+escape(\"$V_roi\")+"; (Lo agrego en la siguiente linea)
            $TMP_contp.="\"&headeroff=x&footeroff=x&V_dir=$V_dir&V_idmod=$V_idmod&V_mod=$V_mod&V_roi=$V_roi&PHPSESSID=$PHPSESSID\",x,y);\n";
//          $TMP_contp.="   searchpopup = window.open(\"index.php?&func=search_js&dbname=$dbname&searchfield=\" +\n";
//          $TMP_contp.="       escape(searchfield)+\"&param=\"+escape(param)+\"&headeroff=x&V_dir=".$V_dir."&V_idmod=$V_idmod&V_mod=".$V_mod."\",\"\",\"width=x,height=y,screenx=10,screeny=10,scrollbars=yes\");\n";
            $TMP_contp.="   if (window.focus) { if (searchpopup) searchpopup.focus(); }\n";
            $TMP_contp.="    for (var i=0; i<document.".$formName.".elements.length; i++) {\n";
            $TMP_contp.="      if (document.".$formName."[i] == res) {\n";
            $TMP_contp.="        for (var j=i+1; j<document.".$formName.".elements.length; j++) {\n";
            $TMP_contp.="//          if (document.".$formName."[j].type=='text' || document.".$formName."[j].type=='radio') {\n";
            $TMP_contp.="          if (document.".$formName."[j].name.length>0) {\n";
            $TMP_contp.="             f3=document.".$formName."[j];\n";
            $TMP_contp.="             j=document.".$formName.".elements.length+1;\n";
            $TMP_contp.="          }\n";
            $TMP_contp.="        }\n";
            $TMP_contp.="      }\n";
            $TMP_contp.="   }\n";
            $TMP_contp.="}\n</script>\n";
            $TMP_cont=$TMP_contp.$TMP_cont;
        }
    }
    return "<nobr>".$TMP_cont.$TMP_linkvalue.$TMP_linknew."</nobr>";
}
/////////////////////////////////////////////////////////////////////////////
function RAD_die($msg) {
    global $SCRIPT_NAME,$xajax;
    
        if ($xajax!='') {
             ob_end_clean();
             $sContentHeader = "Content-type: text/xml;";
             header($sContentHeader);
             $TMP_msg=_ACCESSDENIED.'<br>'._MODULEUSERS;
             $TMP_msg=str_replace('<br>',"\n",$TMP_msg);
             echo '<?xml version="1.0" encoding="utf-8" ?><xjx><cmd n="al"><![CDATA['.$TMP_msg.']]></cmd></xjx>';
             die;
        }
    
    $TMP_base=dirname($SCRIPT_NAME);
    if (strlen($TMP_base)>1) $TMP_base.="/";
    RAD_logError("DIE: ".$msg);
    echo $msg."\n<br><br><ul><a href='".$TMP_base."'>Volver a la Portada</a>";
    die();
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_logError($TMP_err) {
// Ej: RAD_logError("ERR: ".$errorstr);
global $RAD_nologerr, $dbname, $V_idmod, $V_dir, $V_mod;
    if ($RAD_nologerr!="") return;
    $TMP_IP=getenv("REMOTE_ADDR");
    $TMP_CLIENT_IP=getenv("HTTP_CLIENT_IP");
    if ($TMP_CLIENT_IP!="") $TMP_IP=$TMP_CLIENT_IP."/".$TMP_IP;
    $TMP_err=trim($TMP_err);
    $dbname=trim($dbname);
    if ($dbname=="") $dbname = _DEF_dbname;
    $TMP_user=base64_decode(getSessionVar("SESSION_user"));
    if ($TMP_user=="") $TMP_user="[no user]";
    $fileerr=_DEF_DIRBASE."privado/".$dbname.".err";
    $f = fopen($fileerr,"a+");
    $current_date = getdate();
    $FechaHoraSis=$current_date["year"]."-".$current_date["mon"]."-".$current_date["mday"]." ".$current_date["hours"].":".$current_date["minutes"].":".$current_date["seconds"];
    fputs($f,"# $FechaHoraSis. $TMP_user [$V_idmod] $TMP_IP [$V_dir/$V_mod]\n");
    fputs($f,$TMP_err.";\n");
    fclose($f);
    $TMP_time=time();
    if (@filesize($fileerr)>1000000) {
    clearstatcache();
    if (@filesize($fileerr)>1000000) {
            rename($fileerr, $fileerr.".".$current_date["year"].".".$current_date["mon"].".".$current_date["mday"].".".$current_date["hours"].".".$current_date["minutes"].".".$current_date["seconds"]);
            //system ("mv ".$fileerr." ".$fileerr.".".$current_date["year"].".".$current_date["mon"].".".$current_date["mday"].".".$current_date["hours"].".".$current_date["minutes"].".".$current_date["seconds"]);
    }
    }
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_printLog($cmdSQL) {
global $RAD_nologsql, $logsql, $dbname, $RAD_dbi, $RAD_DB_hist, $RAD_TAB_hist, $V_idmod, $V_dir, $V_mod, $RAD_lastprintLog;
    if (isset($logsql) && $logsql==false) return;
    if ($RAD_nologsql!="") return;
    $TMP_IP=getenv("REMOTE_ADDR");
    $TMP_CLIENT_IP=getenv("HTTP_CLIENT_IP");
    if ($TMP_CLIENT_IP!="") $TMP_IP=$TMP_CLIENT_IP."/".$TMP_IP;
    $RAD_lastprintLog=$cmdSQL;
    $cmdSQL=trim($cmdSQL);
    $dbname=trim($dbname);
    if ($dbname=="") $dbname = _DEF_dbname;
    $TMP_user=base64_decode(getSessionVar("SESSION_user"));
    if ($TMP_user=="") $TMP_user="[no user]";
    $filelog=_DEF_DIRBASE."privado/".$dbname.".log.sql";
    $TMP_time=time();
    $current_date = getdate();
    if (@filesize($filelog)>1000000) {
    clearstatcache();
    if (@filesize($filelog)>1000000) {
        rename($filelog, $filelog.".".$current_date["year"].".".$current_date["mon"].".".$current_date["mday"].".".$current_date["hours"].".".$current_date["minutes"].".".$current_date["seconds"]);
        //system ("mv ".$filelog." ".$filelog.".".$current_date["year"].".".$current_date["mon"].".".$current_date["mday"].".".$current_date["hours"].".".$current_date["minutes"].".".$current_date["seconds"]);
    }
    }
    $f = fopen($filelog,"a+");
    chmod($filelog, 0777);
    $FechaHoraSis=$current_date["year"]."-".$current_date["mon"]."-".$current_date["mday"]." ".$current_date["hours"].":".$current_date["minutes"].":".$current_date["seconds"];
    if (substr($cmdSQL,0,5)!="# id=") fputs($f,"# $FechaHoraSis. $TMP_user [$V_idmod] $TMP_IP [$V_dir/$V_mod] ".RAD_trace("")."\n");
    fputs($f,$cmdSQL.";\n");
    fclose($f);

    $TMP_SQL=$cmdSQL;
    $TMP_where="";
    if (strtoupper(substr($TMP_SQL,0,6))=="INSERT") { // INSERT INTO table SET xxxx=xxxx, ...;  INSERT INTO table VALUES (xxxx,xxxx,...);
    $TMP_operacion="I";
    $TMP_restoSQL=trim(substr($TMP_SQL,7));
    $TMP_restoSQL=trim(substr($TMP_restoSQL,5));    // se elimina INTO
    $TMP_pars=explode(" ", $TMP_restoSQL);
    $TMP_table=$TMP_pars[0];
    $TMP_restoSQL=trim(substr($TMP_restoSQL,strlen($TMP_table)));
    if (substr($TMP_restoSQL,strlen($TMP_restoSQL)-1)==";") $TMP_valores=substr($TMP_restoSQL,0,strlen($TMP_restoSQL)-1);
    else $TMP_valores=$TMP_restoSQL;
    if (strtoupper(substr($TMP_valores,0,3))=="SET") $TMP_valores=trim(substr($TMP_valores,4));
    else if (strtoupper(substr($TMP_valores,0,6))=="VALUES") $TMP_valores=trim(substr($TMP_valores,4));
    $TMP_where="";
    } else if (strtoupper(substr($TMP_SQL,0,6))=="DELETE") { // DELETE FROM table WHERE ...;
    $TMP_operacion="D";
    $TMP_restoSQL=trim(substr($TMP_SQL,7));
    $TMP_restoSQL=trim(substr($TMP_restoSQL,5));    // se elimina FROM
    $TMP_pars=explode(" ", $TMP_restoSQL);
    $TMP_table=$TMP_pars[0];
    $TMP_restoSQL=trim(substr($TMP_restoSQL,strlen($TMP_table)));

    $TMP_pars=explode("WHERE ", strtoupper($TMP_restoSQL));
    $TMP_last=count($TMP_pars)-1;
    $TMP_where=substr($TMP_restoSQL,strlen($TMP_restoSQL)-strlen($TMP_pars[$TMP_last]));
    $TMP_valores="";
    if ($TMP_where!="" && !ereg(" AND ",strtoupper($TMP_where)) && !ereg(" OR ",strtoupper($TMP_where)) && !ereg(" LIKE ",strtoupper($TMP_where)) ) $TMP_valores.=$TMP_where;
    } else if (strtoupper(substr($TMP_SQL,0,6))=="UPDATE") { // UPDATE table SET xxxx=xxxx, ... WHERE ...;
    $TMP_operacion="U";
    $TMP_restoSQL=trim(substr($TMP_SQL,7));
    $TMP_pars=explode(" ", $TMP_restoSQL);
    $TMP_table=$TMP_pars[0];

    $TMP_restoSQL=trim(substr($TMP_restoSQL,strlen($TMP_table)));

    $TMP_pars=explode("WHERE ", strtoupper($TMP_restoSQL));
    if (count($TMP_pars)>0) {
        $TMP_last=count($TMP_pars)-1;
        $TMP_where=substr($TMP_restoSQL,strlen($TMP_restoSQL)-strlen($TMP_pars[$TMP_last]));
        $TMP_restoSQL=substr($TMP_restoSQL,0,strlen($TMP_restoSQL)-strlen($TMP_pars[$TMP_last])-6);
        $TMP_valores=trim($TMP_restoSQL);
    } else {
        $TMP_valores=trim($TMP_restoSQL);
    }
    if (strtoupper(substr($TMP_valores,0,3))=="SET") $TMP_valores=trim(substr($TMP_valores,4));
    if ($TMP_where!="" && !ereg(" AND ",strtoupper($TMP_where)) && !ereg(" OR ",strtoupper($TMP_where)) && !ereg(" LIKE ",strtoupper($TMP_where)) ) $TMP_valores.=", ".$TMP_where;
    } else {
    $TMP_table="";
    $TMP_operacion="X";
    $TMP_valores=$TMP_SQL;
    $TMP_where="";
    }

//  Graba en base de datos de cambios historicos si existe
    if (_DEF_DBHIST_PREFIX!=""  && _DEF_DBHIST_PREFIX!="_DEF_DBHIST_PREFIX" && defined(_DEF_DBHIST_PREFIX)) {
    RAD_grabaBDHistorica($TMP_user,$V_idmod,$TMP_time,$TMP_table,$TMP_operacion,$TMP_valores,$TMP_where);
    $RAD_dbi=sql_connect(_DEF_dbhost, _DEF_dbuname, _DEF_dbpass, $dbname);
    }

//  Graba en tabla de log si existe
    if (RAD_existTable("RAD_log") && $TMP_table!="RAD_log") RAD_grabaLog($TMP_user,$V_idmod,$TMP_time,$TMP_table,$TMP_operacion,$TMP_valores,$TMP_where);

    return $cmdSQL;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_grabaBDHistorica($TMP_user,$TMP_idmod,$TMP_time,$TMP_table,$TMP_operacion,$TMP_valores,$TMP_where) {
global $dbname, $SESSION_NAMETABLES;
//###### Base de Datos de cambios historicos. Contiene una copia de cada tabla a las que se han agregado los campos siguientes:
//##   H_id int(12),
//##   H_fechahoraint int(11),
//##   H_fechahora datetime,
//##   H_operacion char(1),
//##   H_idmodulo int(11),
//##   H_usuario varchar(40),
//##   H_opwhere text,
//######

    if ($dbname!="" && substr($dbname,0,strlen(_DEF_dbname))==_DEF_dbname) $TMP_dbname=$dbname;
    else $TMP_dbname=_DEF_dbname;
    if ($SESSION_NAMETABLES[_DEF_DBHIST_PREFIX.$TMP_dbname."."]=="") {
        if (!$RAD_dbiH=sql_connect(_DEF_dbhost, _DEF_dbuname, _DEF_dbpass, $TMP_dbname)) return;
        $TMP_result=sql_query("SHOW DATABASES", $RAD_dbiH);
        while($TMP_row = sql_fetch_array($TMP_result, $RAD_dbiH)) {
//          $TMP_row[0]=strtolower($TMP_row[0]);
            $SESSION_NAMETABLES[$TMP_row[0]."."]=$TMP_row[0];
        }
    }
    if ($SESSION_NAMETABLES[_DEF_DBHIST_PREFIX.$TMP_dbname."."]=="") RAD_creaBDHistorica($TMP_dbname);  // No existe DB historica

    if ($SESSION_NAMETABLES[_DEF_DBHIST_PREFIX.$TMP_dbname.".".$TMP_table]=="") {
        if (!$RAD_dbiH=sql_connect(_DEF_dbhost, _DEF_dbuname, _DEF_dbpass, _DEF_DBHIST_PREFIX.$TMP_dbname)) return;
        $TMP_result=sql_query("Show TABLES", $RAD_dbiH);
        while($TMP_row = sql_fetch_array($TMP_result, $RAD_dbiH)) {
//          $TMP_row[0]=strtolower($TMP_row[0]);
            $SESSION_NAMETABLES[_DEF_DBHIST_PREFIX.$TMP_dbname.".".$TMP_row[0]]=$TMP_row[0];
        }
    }
    if ($SESSION_NAMETABLES[_DEF_DBHIST_PREFIX.$TMP_dbname.".".$TMP_table]=="") RAD_creaTBHistorica($TMP_dbname,$TMP_table);    // No existe tabla en DB historico

    $TMP_user=converttosql($TMP_user);
    $TMP_idmod=converttosql($TMP_idmod);
    $TMP_where=converttosql($TMP_where);
    $TMP_operacion=converttosql($TMP_operacion);
    $TMP_fechahora=date("Y-m-d H:i:s",$TMP_time);
    $TMP_fechahora=converttosql($TMP_fechahora);
    $TMP_time=converttosql($TMP_time);

    if (substr($TMP_valores,0,1)!="(") {
        $TMP_cmdSQL="INSERT INTO $TMP_table SET H_fechahoraint=$TMP_time, H_fechahora=$TMP_fechahora, H_operacion=$TMP_operacion, H_idmodulo=$TMP_idmod, ";
        $TMP_cmdSQL.="H_usuario=$TMP_user, H_opwhere=$TMP_where, $TMP_valores";
    } else {
        $TMP_cmdSQL="INSERT INTO $TMP_table VALUES ($TMP_time,$TMP_fechahora,$TMP_operacion,$TMP_idmod,";
        $TMP_cmdSQL.="$TMP_user,$TMP_where,".substr($TMP_valores,1);
    }
    if (!$RAD_dbiH=sql_connect(_DEF_dbhost, _DEF_dbuname, _DEF_dbpass, _DEF_DBHIST_PREFIX.$TMP_dbname)) return;

    $TMP_result = sql_query($TMP_cmdSQL, $RAD_dbiH);
//  echo "<br>Graba Historico "._DEF_DBHIST_PREFIX.$TMP_dbname." <br>".$TMP_cmdSQL."<br><br>".sql_errorno($TMP_result).sql_error($TMP_result)."<br><br>";
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_creaBDHistorica($TMP_dbname) {
global $RAD_dbi;
    if (!($result=sql_query("CREATE DATABASE "._DEF_DBHIST_PREFIX.$TMP_dbname, $RAD_dbi))) alert("1. "._DEF_NLSError." "._DEF_DBHIST_PREFIX.$TMP_dbname.": ".sql_error($result));
    if (!($result=sql_query("SHOW Tables", $RAD_dbi))) alert("1b. "._DEF_NLSError." "._DEF_DBHIST_PREFIX.$TMP_dbname.": ".sql_error($result));
    while($TMP_row = sql_fetch_array($TMP_result, $RAD_dbi)) {
        RAD_creaTBHistorica($TMP_dbname,$TMP_row[0]);
    }
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_creaTBHistorica($TMP_dbname,$TMP_table) {
global $RAD_dbi;
    $schema_create = "";
    $schema_create .= "CREATE TABLE $TMP_table (H_id int(12) NOT NULL auto_increment, H_fechahoraint int(11), H_fechahora datetime, H_operacion char(1), H_idmodulo int(11), H_usuario varchar(40), H_opwhere text,";

    $RAD_dbi2 = sql_connect(_DEF_dbhost, _DEF_dbuname, _DEF_dbpass, $TMP_dbname);
    if (!($result=sql_query("SHOW FIELDS FROM ".$TMP_table,$RAD_dbi2))) alert("2. "._DEF_NLSError." ".$TMP_table.": ".sql_error($result));
    while($row = sql_fetch_array($result, $RAD_dbi)) {
        $schema_create .= "   $row[Field] $row[Type]";
        if(isset($row["Default"]) && (!empty($row["Default"]) || $row["Default"] == "0"))
            $schema_create .= " DEFAULT '$row[Default]'";
        if($row["Null"] != "YES")
            $schema_create .= " NOT NULL";
        $row["Extra"]=str_replace("auto_increment","",$row["Extra"]);
        if($row["Extra"] != "")
            $schema_create .= " $row[Extra]";
        $schema_create .= ",\n";
    }
    $schema_create = ereg_replace(","."\n"."$", "", $schema_create);
    if (!($result=sql_query("SHOW KEYS FROM ".$TMP_table,$RAD_dbi2))) alert("3. "._DEF_NLSError." ".$table.": ".sql_error($result));
    while($row = sql_fetch_array($result,$RAD_dbi2)) {
        $kname=$row['Key_name'];

        if(($kname != "PRIMARY") && ($row['Non_unique'] == 0)) $kname="UNIQUE|$kname";
        if(!isset($index[$kname])) $index[$kname] = array();
        $index[$kname][] = $row['Column_name'];

    }
    $schema_create .= " , PRIMARY KEY (H_id)";
    if (count($index)>0) {
      while(list($x, $columns) = each($index)) {
//      if($x == "PRIMARY") $schema_create .= " , PRIMARY KEY (" . implode($columns, ", ") . ")";
        if($x == "PRIMARY") $schema_create .= " ";
        elseif (substr($x,0,6) == "UNIQUE") $schema_create .= " , UNIQUE ".substr($x,7)." (" . implode($columns, ", ") . ")";
        else $schema_create .= " , KEY $x (" . implode($columns, ", ") . ")";
      }
    }
    $schema_create .= ")";
    $schema_create=stripslashes($schema_create);
    $RAD_dbi2 = sql_connect(_DEF_dbhost, _DEF_dbuname, _DEF_dbpass, _DEF_DBHIST_PREFIX.$TMP_dbname);
    if (!($result=sql_query($schema_create ,$RAD_dbi2))) alert("4. "._DEF_NLSError." ".$TMP_table." . "._DEF_DBHIST_PREFIX.$TMP_dbname." : ".sql_error($result));
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_grabaLog($TMP_user,$TMP_idmod,$TMP_time,$TMP_table,$TMP_operacion,$TMP_valores,$TMP_where,$TMP_insert=0) {
global $RAD_dbi;

if ($TMP_insert==0) {
    $TMP_user=converttosql($TMP_user);
    $TMP_idmod=converttosql($TMP_idmod);
    $TMP_where=converttosql($TMP_where);
    $TMP_table=converttosql($TMP_table);
    $TMP_operacion=converttosql($TMP_operacion);
    $TMP_valores=converttosql($TMP_valores);
    $TMP_time=converttosql($TMP_time);

    $TMP_cmdSQL="INSERT INTO RAD_log SET usuario=$TMP_user, idmodulo=$TMP_idmod, tabla=$TMP_table, operacion=$TMP_operacion, ";
    $TMP_cmdSQL.="fechahoraint=".$TMP_time.", valores=".$TMP_valores.", opwhere=".$TMP_where;

    if ($TMP_operacion=="'I'") {
        $TMP_id=sql_insert_id($RAD_dbi);
        $TMP_cmdSQL.=", id='".$TMP_id."'";
    } else {
        $TMP=explode("=",$TMP_where);
        $TMP_id=str_replace("'","",$TMP[1]);
        $TMP_cmdSQL.=", id='".$TMP_id."'";
    }
}

if (_DEF_dblogname!="" && _DEF_dbtype=="MySQL") {
    if ($TMP_insert==1) {
        $RAD_dbi2=mysql_connect(_DEF_dbhost, _DEF_dbuname, _DEF_dbpass, true);
        mysql_select_db(_DEF_dblogname,$RAD_dbi2);
        mysql_select_db(_DEF_dbname, $RAD_dbi);
        $TMP_logs=getSessionVar("SESSION_radlog");
        foreach ($TMP_logs as $k => $log) {
            $TMP_result = sql_query($log, $RAD_dbi2);
        }
        setSessionVar("SESSION_radlog","");
    }else{
        $TMP_logs=getSessionVar("SESSION_radlog");
        $TMP_logs[]=$TMP_cmdSQL;
        setSessionVar("SESSION_radlog",$TMP_logs);
    }
}else{
    $TMP_result = sql_query($TMP_cmdSQL, $RAD_dbi);
}
//echo "<br><br>".$TMP_cmdSQL."<br><br>".sql_errorno($TMP_result).sql_error($TMP_result)."<br><br>";
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_cambiaAcentos($cadena) {
    $cadena=str_replace(chr(193), "A", $cadena);  // ISO-8859-1 o CP-1252
    $cadena=str_replace(iconv("ISO-8859-1", "UTF-8", chr(193)), "A", $cadena);
    $cadena=str_replace(chr(201), "E", $cadena);
    $cadena=str_replace(iconv("ISO-8859-1", "UTF-8", chr(201)), "E", $cadena);
    $cadena=str_replace(chr(205), "I", $cadena);
    $cadena=str_replace(iconv("ISO-8859-1", "UTF-8", chr(205)), "I", $cadena);
    $cadena=str_replace(chr(211), "O", $cadena);
    $cadena=str_replace(iconv("ISO-8859-1", "UTF-8", chr(211)), "O", $cadena);
    $cadena=str_replace(chr(218), "U", $cadena);
    $cadena=str_replace(iconv("ISO-8859-1", "UTF-8", chr(218)), "U", $cadena);
    $cadena=str_replace(chr(225), "a", $cadena);
    $cadena=str_replace(iconv("ISO-8859-1", "UTF-8", chr(225)), "a", $cadena);
    $cadena=str_replace(chr(233), "e", $cadena);
    $cadena=str_replace(iconv("ISO-8859-1", "UTF-8", chr(233)), "e", $cadena);
    $cadena=str_replace(chr(237), "i", $cadena);
    $cadena=str_replace(iconv("ISO-8859-1", "UTF-8", chr(237)), "i", $cadena);
    $cadena=str_replace(chr(243), "o", $cadena);
    $cadena=str_replace(iconv("ISO-8859-1", "UTF-8", chr(243)), "o", $cadena);
    $cadena=str_replace(chr(250), "u", $cadena);
    $cadena=str_replace(iconv("ISO-8859-1", "UTF-8", chr(250)), "u", $cadena);
    return $cadena;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_String2ASCII($cadena) { // solo permite ASCII
    $cadena=RAD_cambiaAcentos($cadena);
    $cadena=str_replace(chr(199), "C", $cadena);
    $cadena=str_replace(iconv("ISO-8859-1", "UTF-8", chr(199)), "C", $cadena);
    $cadena=str_replace(chr(209), "N", $cadena);
    $cadena=str_replace(iconv("ISO-8859-1", "UTF-8", chr(209)), "N", $cadena);
    $cadena=str_replace(chr(231), "c", $cadena);
    $cadena=str_replace(iconv("ISO-8859-1", "UTF-8", chr(231)), "c", $cadena);
    $cadena=str_replace(chr(241), "n", $cadena);
    $cadena=str_replace(iconv("ISO-8859-1", "UTF-8", chr(241)), "n", $cadena);
    $cadena=str_replace(array("\\", "¨", "º", "-", "~", "#", "@", "|", "!", "\"", "·", "$", "%", "&", "/", "(", ")", "?", "'", "¡",
             "¿", "[", "^", "`", "]", "+", "}", "{", "¨", "´", ">", "<", ";", ",", ":", "."), '', $cadena);
    $cadena2="";
    for($ki=0; $ki<strlen($cadena); $ki++) if (ord($cadena[$ki])<128) $cadena2.=$cadena[$ki];
    return $cadena2;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_nameFile($TMP_fich) {
    $TMP_fich=htmlentities(RAD_cambiaAcentos(trim($TMP_fich)));
    $TMP_fich=str_replace(" ","_",$TMP_fich);
    $TMP_fich=str_replace(",","_",$TMP_fich);
    $TMP_fich=str_replace("&","_",$TMP_fich);
    $TMP_fich=str_replace(";","",$TMP_fich);
    $TMP_fich=str_replace("(","",$TMP_fich);
    $TMP_fich=str_replace(")","",$TMP_fich);
    $TMP_fich=str_replace("[","",$TMP_fich);
    $TMP_fich=str_replace("]","",$TMP_fich);
    $TMP_fich=str_replace("'","",$TMP_fich);
    $TMP_fich=str_replace('"',"",$TMP_fich);
    $TMP_fich=str_replace("`","",$TMP_fich);
    $TMP_fich=str_replace("&#180;","",$TMP_fich);
    $TMP_fich=str_replace("|","",$TMP_fich);
    return $TMP_fich;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_nameSecure($TMP_fich) {

$TMP_fich=RAD_nameFile($TMP_fich);
$TMP_ext3=strtolower(substr($TMP_fich,strlen($TMP_fich)-3));
$TMP_ext=strtolower(substr($TMP_fich,strlen($TMP_fich)-4));
$TMP_ext5=strtolower(substr($TMP_fich,strlen($TMP_fich)-5));

if ($TMP_ext3==".pl" || $TMP_ext==".php" || $TMP_ext5==".phtml" || $TMP_ext==".inc") $TMP_fich.=".txt";
//if (eregi(".pl", $TMP_fich) || eregi(".php", $TMP_fich) || eregi(".phtml", $TMP_fich) || eregi(".inc", $TMP_fich)) $TMP_fich.=".txt";
return $TMP_fich;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_nameDownload($TMP_fich) {
global $dbname;

$TMP_current_date = getdate();

// se crea subdirectorio por mes
$TMP_SubDir=$TMP_current_date["year"]."-".$TMP_current_date["mon"];
if ($dbname!="") $TMP_SubDirBase="files/$dbname";
else $TMP_SubDirBase="files/"._DEF_dbname;
if(!file_exists($TMP_SubDirBase."/".$TMP_SubDir)) {
    mkdir($TMP_SubDirBase."/".$TMP_SubDir, 0777);
    copy($TMP_SubDirBase."/index.html",$TMP_SubDirBase."/".$TMP_SubDir."/index.html");
}

$TMP_FechaHoraSis=$TMP_current_date["year"]."-".$TMP_current_date["mon"]."/".$TMP_current_date["mday"]."-".$TMP_current_date["hours"]."-".$TMP_current_date["minutes"]."-".$TMP_current_date["seconds"];
// antiguo $TMP_FechaHoraSis=$TMP_current_date["year"]."-".$TMP_current_date["mon"]."-".$TMP_current_date["mday"]."-".$TMP_current_date["hours"]."-".$TMP_current_date["minutes"]."-".$TMP_current_date["seconds"];
$TMP_user=".".base64_decode(getSessionVar("SESSION_user"));
if ($TMP_user==".") $TMP_user="";
$TMP_nameFich=$TMP_FechaHoraSis.$TMP_user.".".basename($TMP_fich);
$TMP_nameFich=str_replace(" ", "_",$TMP_nameFich);

// OLD STYLE. NUEVO MAS CIFRADO
if (_DEF_UPLOAD_FILENAME_UNIQID=='1') {
    $TMP_arr=array_reverse(explode(".",$TMP_fich));
    $TMP_nameFich=FUN_id().".".$TMP_arr[0];
}

if ($dbname!="") return array("files/$dbname/".$TMP_nameFich,$TMP_nameFich);
else return array("files/"._DEF_dbname."/".$TMP_nameFich,$TMP_nameFich);
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_microtime() {
    $TMP = explode(" ",microtime());
    return (double)($TMP[1]) + (double)($TMP[0]);
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_redondeaEuros($total) {
    if ($total<0) {
        return $total;
    	// Desactivado porque en los calculos en numeros en negativo siempre se incrementaban en 1 centimo
        $tot=($total*100)-0.5;
    }else{
        $tot=($total*100)+0.5;
    }
    $tot=floor($tot);

    return ($tot/100);
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_primerFich($TMP) {
    if (trim($TMP)=="") return "";
    $TMP=str_replace("\r", "", $TMP);
    $TMP_fotos=explode("\n",$TMP);
    $TMP_foto="";
    if (!(count($TMP_fotos)>1)) $TMP_foto=$TMP;
    else {
        for ($ki=0; $ki<count($TMP_fotos); $ki++) {
            if ($TMP_fotos[$ki]!=""&&$TMP_fotos[$ki]!="\n"&&$TMP_foto=="") $TMP_foto=$TMP_fotos[$ki];
        }
    }
    $TMP_foto=str_replace("\n", "", $TMP_foto);
    return $TMP_foto;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_resizeImg($TMP_img, $ancho, $alto) {
    global $dbname;
    if (!file_exists($TMP_img)) return "";
    if (!function_exists("ImageCreate")) return $TMP_img;     // If gd is not supported
    if (is_dir($TMP_img)) return "";
    $TMP_fileimg=basename($TMP_img);
    $TMP_dir=substr($TMP_img,0,strlen($TMP_img)-strlen($TMP_fileimg));
    list($anchoimg, $altoimg, $tipo, $resto) = getimagesize ($TMP_dir.$TMP_fileimg);
    if (!$ancho>0) $ancho=10;
    $escalax=$anchoimg/$ancho;
    if (!$alto>0) $alto=10;
    $escalay=$altoimg/$alto;
    if ($escalax>$escalay) $escala=$escalax;
    else $escala=$escalay;
    if (!$escala>0) $escala=1;
    $ancho=round($anchoimg/$escala,0);
    $alto=round($altoimg/$escala,0);
    if (!file_exists($TMP_dir.$ancho."x".$alto."_".$TMP_fileimg)) {
    //$imgdest = @imagecreate($ancho, $alto);
    $imgdest = @imagecreatetruecolor($ancho, $alto);
    if ($tipo=="1") $imgorigen = @imagecreatefromgif($TMP_img);     // gif
    else if ($tipo=="2") $imgorigen = @imagecreatefromjpeg($TMP_img);   // jpg
    else if ($tipo=="3") $imgorigen = @imagecreatefrompng($TMP_img);    // png
    else return $TMP_dir.$TMP_fileimg;
    imagecopyresized($imgdest, $imgorigen, 0, 0, 0, 0, $ancho, $alto, $anchoimg, $altoimg);
    if ($tipo=="1") imagegif($imgdest, $TMP_dir.$ancho."x".$alto."_".$TMP_fileimg);         // gif
    else if ($tipo=="2") imagejpeg($imgdest, $TMP_dir.$ancho."x".$alto."_".$TMP_fileimg, 100);  // jpg
    else if ($tipo=="3") imagepng($imgdest, $TMP_dir.$ancho."x".$alto."_".$TMP_fileimg);        // png
    else imagejpeg($imgdest, $TMP_dir.$ancho."x".$alto."_".$TMP_fileimg, 100);
    }
    chmod($TMP_dir.$ancho."x".$alto."_".$TMP_fileimg, 0666);
    return $TMP_dir.$ancho."x".$alto."_".$TMP_fileimg;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_hideurlencodeFich($TMP) {
    global $SESSION_SID, $dbname;
    $TMP_f=RAD_urlencodeFich($TMP);
// Descomentar las siguientes lineas si se quieren esconder las imagenes y ficheros anexos
//  $tmpFile=uniqid("");
//  setSessionVar("SESSION_file".$tmpFile,"files/$dbname/".$TMP_f,0);
//  $tmp_URL="index.php?".$SESSION_SID."op=download&file=$tmpFile";
//  return $tmp_URL;
    return "files/".$dbname."/".$TMP_f;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_urlencodeFich($TMP) {
    global $SESSION_SID, $dbname;
    $TMP_f=rawurlencode($TMP);
    $TMP_f=str_replace("%2F","/",$TMP_f);
    return $TMP_f;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_barraHTML($longtotal,$desplazamiento,$longbarra,$colorborde,$colorfondo,$colorbarra,$altura) {
// p.e. RAD_barraHTML(600,50,400,"black","white","navy",12)
    if ($altura=="") $altura=1;
    $altura=$altura*1;
    if ($longtotal=="") $longtotal=1;
    $longtotal=$longtotal*1;
    if ($desplazamiento=="") $desplazamiento=0;
    $desplazamiento=$desplazamiento*1;
    if ($longbarra=="") $longbarra=0;
    $longbarra=$longbarra*1;
    $longresto=$longtotal-$desplazamiento-$longbarra;
    $TMP_content="<table width=$longtotal height=$altura bgcolor=$colorfondo style='border-width:1px;border-style:solid;border-color:$colorborde' cellspacing=1 cellpadding=0>
<tr><td width=$desplazamiento></td>
<td width=$longbarra style='background-color:$colorbarra;'></td>
<td width=$longresto></td>
</tr></table>";
    return $TMP_content;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_checkSQLDelete($cmdSQL) {
global $RAD_dbi, $RAD_DB_dep;
    if (_DEF_NoCheckSQLDelete==1) {
	return;
    }
    $cmdSQL=trim($cmdSQL);
    $UcmdSQL=strtoupper($cmdSQL);
    if (substr($UcmdSQL,0,7)!="DELETE ") return;

    $pos=strpos($UcmdSQL," WHERE ");
    $WHERE=substr($cmdSQL,$pos+7);
    $pos=strpos($UcmdSQL," FROM ");
    $TABLE=trim(substr($cmdSQL,$pos+6));
    $pos=strpos($TABLE," ");
    $TABLE=substr($TABLE,0,$pos);
    if (count($RAD_DB_dep[$TABLE])>0) {
        foreach ($RAD_DB_dep[$TABLE] as $FIELD=>$DEP) {
            $DEPS=explode(":",$DEP);
            $TABLEDEP=$DEPS[0];
            $FIELDDEP=$DEPS[1];
            $scmdSQL="SELECT * FROM ".$TABLE." WHERE ".$WHERE;
            $TMP_result=sql_query($scmdSQL, $RAD_dbi);
            while($TMP_row = sql_fetch_array($TMP_result, $RAD_dbi)) {
                $xcmdSQL="SELECT count(*) FROM ".$TABLEDEP." WHERE ".$FIELDDEP."=".converttosql($TMP_row[$FIELD]);
                $TMP_result2=sql_query($xcmdSQL, $RAD_dbi);
                $TMP_row2 = sql_fetch_array($TMP_result2, $RAD_dbi);
                if ($TMP_row2[0]>0) error("No se puede borrar registro $TMP_row[$FIELD] por existir dependencia con otro registro de tabla $TABLEDEP");
            }
        }
    }
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_checkPars() {
global $numf, $RAD_dbi, $tablename, $fields, $findex;
    $TMP_buscaOtrosPars=false;
    for ($i = 0; $i < $numf; $i++) {
        global ${"idname$i"}, ${"par$i"};
        if (isset(${"idname$i"}) && ${"idname$i"}!="") {
            if (${"par$i"}!="") {
                if ($WHERE!="") $WHERE.=" AND ";
                else $WHERE.=" WHERE ";
                $WHERE.=" ".${"idname$i"}."='".${"par$i"}."'";
            } else {
                $TMP_buscaOtrosPars=true;
            }
        }
    }
    $TMP_hayAutoInc=false;
    $TMP_hayMasNoAutoInc=false;
    for ($i = 0; $i < $numf; $i++) {
        if (isset(${"idname$i"}) && ${"idname$i"}!="") {
            if (${"par$i"}!="" && $fields[$findex[${"idname$i"}]]->dtype=="auto_increment") {
                $WHERE=" WHERE ".${"idname$i"}."='".${"par$i"}."'";
                $TMP_hayAutoInc=true;
            } else {
                $TMP_hayMasNoAutoInc=true;
            }
        }
    }
    if ($WHERE!="" && ($TMP_buscaOtrosPars || ($TMP_hayAutoInc && $TMP_hayMasNoAutoInc))) {
        $TMP_cmdSQL="SELECT COUNT(*) FROM ".$tablename.$WHERE;
        $TMP_result=sql_query($TMP_cmdSQL, $RAD_dbi);
        $TMP_row = sql_fetch_array($TMP_result, $RAD_dbi);
        if ($TMP_row[0]=="1") {
            $TMP_cmdSQL="SELECT * FROM ".$tablename.$WHERE;
            $TMP_result=sql_query($TMP_cmdSQL, $RAD_dbi);
            $TMP_row = sql_fetch_array($TMP_result, $RAD_dbi);
            for ($i = 0; $i < $numf; $i++) {
                if (isset(${"idname$i"}) && ${"idname$i"}!="") {
                    ${"par$i"}=$TMP_row[${"idname$i"}];
                }
            }
        }
    }

}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_numero($val,$decimales="",$TMP_decimalsep="",$TMP_milsep="") {
// El parametro decimales indica el numero de cifras decimales si viene un numero. Si viene espacio por defecto son 2 decimales
// Y si viene un numero una coma y otro numero la primera cifra indica el numero de digitos enteros y el segundo el numero de decimales
// Convierte a formato cadena un numero 999999.99 para ello requiere variables $RAD_decimalsep y $RAD_milsep, o parametros equivalentes, sino devuelve el mismo numero
global $RAD_decimalsep, $RAD_milsep;
if ($RAD_decimalsep=="" && $RAD_milsep=="") { $RAD_decimalsep="."; $RAD_milsep=""; } // VALORES POR DEFECTO
    $A_x=explode(",",$decimales);
    if (count($A_x)>1) $decimales=$A_x[1];
    if ($TMP_decimalsep=="") $TMP_decimalsep=$RAD_decimalsep;
    if ($TMP_milsep=="") $TMP_milsep=$RAD_milsep;

    if (_DEF_SHOW_NULL_AS_ZERO=="1") if (trim($val)=="") $val=0;
    if (trim($val)=="") return "";
    if ($decimales==="") $decimales=2;
    if ($TMP_decimalsep!="" || $TMP_milsep!="") {
	$TMP_val=number_format ($val, $decimales, $TMP_decimalsep, $TMP_milsep);
	if ($TMP_val=="-0,".str_pad("",$decimales,"0")) $TMP_val=number_format(0, $decimales, $TMP_decimalsep, $TMP_milsep); // number_format imprime a veces un -0.00
    } else {
	return $val;
	////$TMP_val=number_format($val, $decimales, ",", "."); // Se debe definir $RAD_decimalsep y $RAD_milsep para convertir a numero
	////if ($TMP_val=="-0,".str_pad("",$decimales,"0")) $TMP_val=number_format(0, $decimales, ",", ".");  // number_format imprime a veces un -0.00
    }
//   $TMP_decimal=$val-floor($val);
//   $TMP_val=floor().".".$val-floor($val);
    return $TMP_val;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_number_format($number, $decimals, $dec_point, $thousands_sep) {
    global $V_typePrint;
    if (strtoupper($V_typePrint)=="CSV") return $number;
    $number=str_replace(",",".",$number);
    $number = rtrim(sprintf('%f', $number), "0");
    if (fmod($number, 1) != 0) {                     
        $array_int_dec = explode('.', $number);
    } else {
        $array_int_dec= array(strlen($nummer), 0);
    }
    if ($array_int_dec[1]==0) $array_int_dec[1]="";
    if ($decimals=="") $decimals=strlen($array_int_dec[1]);
    //(strlen($array_int_dec[1]) < 2) ? ($decimals = 0) : ($decimals = strlen($array_int_dec[1]));
    return number_format($number, $decimals, $dec_point, $thousands_sep);
} 
/////////////////////////////////////////////////////////////////////////////////////
function RAD_str2num($TMP_num) { // las cifras en la base de datos se guardan sin separador de mil, y con punto decimal
    // convierte cadena de numeros a numeros con punto como separador decimal
    global $RAD_decimalsep, $RAD_milsep;
    if ($TMP_num=="") return $TMP_num;
    $haypunto=false; $TMP_numdecimales=0; $solonumeros=true;
    for($k=0; $k<strlen($TMP_num); $k++) {
	if(substr($TMP_num,$k,1)==".") {
		$haypunto=true;
		continue;
	}
	if(!is_numeric(substr($TMP_num,$k,1))) $solonumeros=false;
	if ($haypunto==true) $TMP_numdecimales++;
    }
    if ($solonumeros==true && $TMP_numdecimales<3) return $TMP_num; // ya esta en formato numerico con punto decimal
    if (substr($TMP_num,0,1)==","||substr($TMP_num,0,1)==".") $TMP_num="0".$TMP_num;
    if ($RAD_decimalsep!="" || $RAD_milsep!="") {
      $posmil=strpos($TMP_num,$RAD_milsep);
      $posdecimal=strpos($TMP_num,$RAD_decimalsep);
      if ($posmil>0 && $posdecimal>0) {
        if ($posdecimal<$posmil) {
            $TMP_num=str_replace($RAD_decimalsep,"",$TMP_num);
            $TMP_num=str_replace($RAD_milsep,".",$TMP_num);
        } else {
            $TMP_num=str_replace($RAD_milsep,"",$TMP_num);
            $TMP_num=str_replace($RAD_decimalsep,".",$TMP_num);
        }
      } else if ($posmil>0) {
	$TMP_num=str_replace($RAD_milsep,"",$TMP_num);
      } else if ($posdecimal>0) $TMP_num=str_replace($RAD_decimalsep,".",$TMP_num);
      if (_DEF_character_numeric!="" && _DEF_dbtype=="Oracle") {
        $poscoma=strpos($TMP_num,",");
        $pospunto=strpos($TMP_num,".");
        if (ereg(",",_DEF_character_numeric) && $pospunto>0) $TMP_num=str_replace(".",",",$TMP_num);
        if (ereg(",",_DEF_character_numeric) && $poscoma>0) $TMP_num=str_replace(",",".",$TMP_num);
      }
    } else {
      $poscoma=strpos($TMP_num,",");
      $pospunto=strpos($TMP_num,".");
      if ($poscoma>0 && $pospunto>0) {
        if ($pospunto<$poscoma) {
            $TMP_num=str_replace(".","",$TMP_num);
            $TMP_num=str_replace(",",".",$TMP_num);
        } else {
            $TMP_num=str_replace(",","",$TMP_num);
        }
      } else if ($poscoma>0) $TMP_num=str_replace(",",".",$TMP_num);
      if (_DEF_character_numeric!="" && _DEF_dbtype=="Oracle") {
        $poscoma=strpos($TMP_num,",");
        $pospunto=strpos($TMP_num,".");
        if (ereg(",",_DEF_character_numeric) && $pospunto>0) $TMP_num=str_replace(".",",",$TMP_num);
        if (ereg(",",_DEF_character_numeric) && $poscoma>0) $TMP_num=str_replace(",",".",$TMP_num);
      }
    }
    //if ($TMP_num=="." || $TMP_num=="," || $TMP_num=="-.") error("Caracter numerico invalido");
    return $TMP_num;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_GregorianToJD ($month,$day,$year) {
   return RAD_gregorianDate($year."-".$month."-".$day);
// modificado 2-ene-2009
   if ($month > 2) {
       $month = $month - 3;
   } else {
       $month = $month + 9;
       $year = $year - 1;
   }
   $c = floor($year / 100);
   $ya = $year - (100 * $c);
   $j = floor((146097 * $c) / 4);
   $j += floor((1461 * $ya)/4);
   $j += floor(((153 * $month) + 2) / 5);
   $j += $day + 1721119;
   return $j;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_JDToGregorian($julian) {
   $fecha=RAD_julianDate($julian);
   $A_tmp=explode("-",$fecha);
   $year=$A_tmp[0];
   $month=$A_tmp[1];
   $day=$A_tmp[2];
   return $day."-".$month."-".$year;
// modificado 2-ene-2009
   $julian = $julian - 1721119;
   $calc1 = 4 * $julian - 1;
   $year = floor($calc1 / 146097);
   $julian = floor($calc1 - 146097 * $year);
   $day = floor($julian / 4);
   $calc2 = 4 * $day + 3;
   $julian = floor($calc2 / 1461);
   $day = $calc2 - 1461 * $julian;
   $day = floor(($day + 4) / 4);
   $calc3 = 5 * $day - 3;
   $month = floor($calc3 / 153);
   $day = $calc3 - 153 * $month;
   $day = floor(($day + 5) / 5);
   $year = 100 * $year + $julian;

   if ($month < 10) {
       $month = $month + 3;
   }
   else {
       $month = $month - 9;
       $year = $year + 1;
   }
   return "$day-$month-$year";
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_inDate($TMP_date,$day,$week,$month,$year) {
// Comprueba si TMP_date (AAAA-MM-DD) esta dentro de la fecha year-month-day o year-month-week o year-month
    $TMP_datenum=RAD_gregorianDate($TMP_date);
    if ($day>0) {
        $TMP2_datenum=RAD_gregorianDate($year."-".$month."-".$day);
        if ($TMP2_datenum==$TMP_datenum) return true;
        else return false;
    }
    if ($week>0) {
        $TMP_dates2=RAD_weekMonthYear2Date($week,$month,$year);
        $TMP2_datenumA=RAD_gregorianDate($TMP_dates2['firstday']);
        $TMP2_datenumB=RAD_gregorianDate($TMP_dates2['lastday']);
        if ($TMP2_datenumA<=$TMP_datenum && $TMP2_datenumB>=$TMP_datenum) return true;
        else return false;
    }
    if ($month>0) {
        $TMP2_datenumA=RAD_gregorianDate($year."-".$month."-01");
        if ($month=="12") { $month="01"; $year++; }
        else $month++;
        $TMP2_datenumB=RAD_gregorianDate($year."-".$month."-01");
        if ($TMP2_datenumA<=$TMP_datenum && $TMP2_datenumB>$TMP_datenum) return true;
        else return false;
    }
    if ($year>0) {
        $TMP2_datenumA=RAD_gregorianDate($year."-01-01");
        $TMP2_datenumB=RAD_gregorianDate($year."-12-31");
        if ($TMP2_datenumA<=$TMP_datenum && $TMP2_datenumB>=$TMP_datenum) return true;
        else return false;
    }
    return false;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_IMGShortCut($TMP_idmod,$TMP_par0,$TMP_func) {
global $V_idmod, $par0, $func, $RAD_BARCODE_WIDTH, $RAD_BARCODE_HEIGHT;
    if ($TMP_idmod=="") $TMP_idmod=$V_idmod;
    if ($TMP_par0=="") $TMP_par0=$par0;
//  if ($TMP_func=="") $TMP_func=$func;
    if ($TMP_idmod=="") return "";

    if (!function_exists("ImageCreate")) return "images/tr.gif";    // If gd is not supported

        $TMP_cadena=$TMP_idmod."-".$TMP_par0."-".$TMP_func;
	$TMP_width="200";
	$TMP_height="50";
	if ($RAD_BARCODE_WIDTH>0) $TMP_width=$RAD_BARCODE_WIDTH;
	if ($RAD_BARCODE_HEIGHT>0) $TMP_height=$RAD_BARCODE_HEIGHT;
        $f=RAD_CodigoBarras($TMP_cadena,$TMP_width,$TMP_height);
        $size = getimagesize ($f);
        if ($size[0]>800) {
                $escala=900/$size[0];
                $ancho=$size[0]*$escala;
                $alto=$size[1]*$escala;
        } else {
                $ancho=$size[0];
                $alto=$size[1];
        }
//        $TMP=" <a href='$f' target=_blank><img src='$f' border=0 alt='Shortcut' width='$ancho' height='$alto'></a> ";
        $TMP=$f;
    return $TMP;
}
///////////////////////////////////////////////////////////////
function RAD_CodigoBarras($code, $width, $height) {
     return RAD_CodigoBarrasEstilo($code, $width, $height, "", "", "");
}
///////////////////////////////////////////////////////////////
function RAD_CodigoBarrasEstilo($code, $width, $height, $style, $type, $xres) {
	global $RAD_BARCODE_STYLE, $RAD_BARCODE_WIDTH, $RAD_BARCODE_HEIGHT; 
//define("BCS_BORDER"         ,    1);
//define("BCS_TRANSPARENT"    ,    2);
//define("BCS_ALIGN_CENTER"   ,    4);
//define("BCS_ALIGN_LEFT"     ,    8); //*
//define("BCS_ALIGN_RIGHT"    ,   16);
//define("BCS_IMAGE_JPEG"     ,   32);
//define("BCS_IMAGE_PNG"      ,   64); //*
//define("BCS_DRAW_TEXT"      ,  128);
//define("BCS_STRETCH_TEXT"   ,  256);
//define("BCS_REVERSE_COLOR"  ,  512);
//define("BCS_I25_DRAW_CHECK" , 2048); // For the I25 Only
//define("BCD_DEFAULT_BACKGROUND_COLOR", 0xFFFFFF);
//define("BCD_DEFAULT_FOREGROUND_COLOR", 0x000000);
//define("BCD_DEFAULT_STYLE"           , BCS_BORDER | BCS_ALIGN_CENTER | BCS_IMAGE_PNG);
//define("BCD_DEFAULT_WIDTH"           , 460);
//define("BCD_DEFAULT_HEIGHT"          , 120);
//define("BCD_DEFAULT_FONT"            ,   5);
//define("BCD_DEFAULT_XRES"            ,   2);
// Tipos: "I25" , "C128A" , "C128B" , "C128C"

  //$f="files/tmp/cb".urlencode($code).".png";
  if(!file_exists("files/"._DEF_dbname."/codbarras/")) {
        mkdir("files/"._DEF_dbname."/codbarras/", 0777);
        copy("files/index.html","files/"._DEF_dbname."/codbarras/index.html");
  }
  $f="files/"._DEF_dbname."/codbarras/cb".urlencode($code).".png";
//  if (file_exists($f)) return $f;

// If gd is not supported (windows)
  if (!function_exists("ImageCreate")) return "images/tr.gif";

  define (__TRACE_ENABLED__, false);
  define (__DEBUG_ENABLED__, false);

  require_once("modules/barcode/barcode.php");
  require_once("modules/barcode/i25object.php");
  require_once("modules/barcode/c39object.php");
  require_once("modules/barcode/c128aobject.php");
  require_once("modules/barcode/c128bobject.php");
  require_once("modules/barcode/c128cobject.php");

  if (!isset($width) || $width=="") $width=$RAD_BARCODE_WIDTH;
  if (!isset($height) || $height=="") $height=$RAD_BARCODE_HEIGHT;
  if (!isset($width) || $width=="") $width = BCD_DEFAULT_WIDTH;
  if (!isset($height) || $height=="") $height = BCD_DEFAULT_HEIGHT;
//  if (!isset($style))  $style   = BCD_DEFAULT_STYLE;
  //if (strlen($code)>10 && $width<200) $width=200;

//  if (!isset($xres))   $xres    = BCD_DEFAULT_XRES;
//  if (!isset($font))   $font    = BCD_DEFAULT_FONT;
//  if (!isset($style)) $style = "197";
//  if (!isset($style)) $style = "196";
  $TMP=_DEF_BARCODE_STYLE;
  if ($RAD_BARCODE_STYLE!="") $TMP=_DEF_BARCODE_STYLE;
  if ($TMP=="" || $TMP=="_DEF_BARCODE_STYLE") $def_style = "196";
  else $def_style = $TMP;
  if (!isset($style) || $style=="") $style = $def_style;
  if (!isset($xres) || $xres=="") $xres = "1";
  if (!isset($font) || $font=="") $font = "2";

  $escala="";
  if ($width<100 || $height<20) {
    $escalax=$width/100;
    $escalay=$height/20;
    $escala=min($escalax,$escalay);
//    $width=round($width/$escala);
//    $height=round($height/$escala);
  }
//  if ($width<100) $width=100;
//  if ($height<20) $height=20;

  switch ($type) {
    case "I25":     $obj = new I25Object($width, $height, $style, $code); break;
    case "C128A":   $obj = new C128AObject($width, $height, $style, $code); break;
    case "C128B":   $obj = new C128BObject($width, $height, $style, $code); break;

    case "C128C":   $obj = new C128CObject($width, $height, $style, $code); break;
    default:        $obj = new C128BObject($width, $height, $style, $code); break;
  }
  if ($obj) {
      $obj->SetFont($font);
      $obj->DrawObject($xres);
      $obj->SaveObject($f);
//      $obj->DestroyObject();
      unset($obj);
  }
  $f2=str_replace("%","",$f);
  if ($f2!=$f) {
	rename($f,$f2);
	$f=$f2;
  }
//  if ($escala!="") $f=RAD_resizeImg($f,round($width*$escala),round($height*$escala));
  return $f;
}
///////////////////////////////////////////////////////////////
function RAD_CodigoBarrasSimple($valor, $ancho, $alto) {
//$f=creaCodigoBarras("012345678901",400,100);
//  $size = getimagesize ($f);
//  $size = getimagesize ($f);
//  if ($size[0]>800) {
//  $escala=900/$size[0];
//  $ancho=$size[0]*$escala;
//  $alto=$size[1]*$escala;
//  } else {
//  $ancho=$size[0];
//  $alto=$size[1];
//  }
//  echo "<br><center><a href='$f' target=_blank><img src='$f' border=0 alt='Ampliar Imagen' width='$ancho' height='$alto'></a></center><br>";

  $f="files/tmp/cb".$valor.".png";
//  if (file_exists($f)) return $f;

// If gd is not supported (windows)
  if (!function_exists("ImageCreate")) return "images/tr.gif";

  $fino=1;  // ancho de las barritas
  $grueso=2.72; // Mejor para windows aunque lo ideal seria 3
///  $margen=5; // margen de las barras
  $margen=0;    // margen de las barras
  $tabcodbarra[0]="00110";
  $tabcodbarra[1]="10001";
  $tabcodbarra[2]="01001";
  $tabcodbarra[3]="11000";
  $tabcodbarra[4]="00101";
  $tabcodbarra[5]="10100";
  $tabcodbarra[6]="01100";
  $tabcodbarra[7]="00011";
  $tabcodbarra[8]="10010";
  $tabcodbarra[9]="01010";
  for($f1=9;$f1>=0;$f1--) {
    for($f2=9;$f2>=0;$f2--) {
        $f=($f1*10)+$f2;
        $TMP_texto="";
        for($i=1;$i<6;$i++) $TMP_texto.=substr($tabcodbarra[$f1],($i-1),1).substr($tabcodbarra[$f2],($i-1),1);

        $tabcodbarra[$f]=$TMP_texto;
    }
  }

  $img=ImageCreate($ancho,$alto);

  $negro=ImageColorAllocate($img, 0, 0, 0);
  $blanco=ImageColorAllocate($img, 255, 255, 255);
  $fondo=ImageColorAllocate($img, 212, 212, 212);

  ImageFilledRectangle($img, 0, 0, $ancho, $alto, $fondo);
// Inicio del codigo
  $pos=5;
  ImageFilledRectangle($img, 1,$pos,1,$alto-$margen,$negro);
  ImageFilledRectangle($img, 2,$pos,2,$alto-$margen,$blanco);
  ImageFilledRectangle($img, 3,$pos,3,$alto-$margen,$negro);
  ImageFilledRectangle($img, 4,$pos,4,$alto-$margen,$blanco);

  $texto=$valor;
  if((strlen($texto) % 2) <> 0) $texto = "0" . $texto;


  while (strlen($texto) > 0) {
    $i = round(substr($texto,0,2));
    $texto = substr($texto,strlen($texto)-(strlen($texto)-2),strlen($texto)-2);
    $f = $tabcodbarra[$i];
    for($i=1;$i<11;$i+=2) {
        if (substr($f,($i-1),1) == "0") $f1 = $fino;
        else $f1 = $grueso;
        ImageFilledRectangle($img, $pos,5,$pos-1+$f1,$alto-$margen,$negro);
        $pos = $pos + $f1;
        if (substr($f,$i,1) == "0") $f2 = $fino ;
        else $f2 = $grueso;
        ImageFilledRectangle($img, $pos,5,$pos-1+$f2,$alto-$margen,$blanco);
        $pos = $pos + $f2;
    }
  }

// Fin del codigo
  ImageFilledRectangle($img, $pos,5,$pos-1+$grueso,$alto-$margen,$negro);
  $pos=$pos+$grueso;
  ImageFilledRectangle($img, $pos,5,$pos-1+$fino,$alto-$margen,$blanco);
  $pos=$pos+$fino;
  ImageFilledRectangle($img, $pos,5,$pos-1+$fino,$alto-$margen,$negro);
  $pos=$pos+$fino;

//  header("Content-Type: image/png");
//  ImagePNG($img);

//  $f="files/tmp/".uniqid("cb").".png";
  imagepng($img,$f);
  flush();
  return $f;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_openURL($TMP_URLfile) {
$TMP_file = fopen($TMP_URLfile, "r");
if (!$TMP_file) {
    $TMP.= "Error al leer : ".$TMP_URLfile;
    RAD_logError("ERR: ".$TMP);
    return $TMP;
}
$TMP_content = "";
//$TMP_content.= $TMP_URLfile;
while (!feof($TMP_file)) {
    $TMP_line = fgets($TMP_file, 512000);
    $TMP_content = $TMP_content.$TMP_line;
}
fclose($TMP_file);
return $TMP_content;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_sendMail($V_to,$V_cc,$V_bcc,$V_from,$V_subject,$V_body,$V_file,$V_sqlroi='',$V_interno='') {
    global $dbname, $RAD_dbi, $dbname, $RAD_noechosendMail, $RAD_sendMailGIE_comunicados;
    if ($dbname=="") $dbname=_DEF_dbname;
    if ($V_to=="") {
        error("Se requiere destinatario de mensaje.");
        die;
    }
    if ($V_from=="") {
            $userFrom = base64_decode(getSessionVar("SESSION_user"));
        $server=_DEF_EMAIL_SERVER;

        if ($server!="") $userFrom=$userFrom."@".$server;
        $V_from=$userFrom;
    }
    if ($V_from=="") $V_from=_DEF_ADMINMAIL;
    $TMP_h="From: $V_from\n";

    if(trim($V_cc)!="") {
	//if (!ereg("<",$V_cc)) $V_cc="<".trim($V_cc).">";
	//if (ereg(";",$V_cc)) $V_cc=str_replace(";",">;<",$V_cc);
	//if (ereg(",",$V_cc)) $V_cc=str_replace(",",">;<",$V_cc);
	$TMP_h.="Cc: ".trim($V_cc)."\n";
    }

    if(trim($V_bcc)!="") {
	//if (!ereg("<",$V_bcc)) $V_bcc="<".trim($V_bcc).">";
	//if (ereg(";",$V_bcc)) $V_bcc=str_replace(";",">;<",$V_bcc);
	//if (ereg(",",$V_bcc)) $V_bcc=str_replace(",",">;<",$V_bcc);
	$TMP_h.="Bcc: ".trim($V_bcc)."\n";
    }

    $cmdSQL="";
    $TMP_autor= trim(base64_decode(getSessionVar("SESSION_user")));
    $TMP_result = sql_query("SELECT * FROM categorias WHERE categoria='mesg'", $RAD_dbi);
    $TMP_row = sql_fetch_array($TMP_result, $RAD_dbi);
    $TMP_idcat=$TMP_row[id];
    $TMP_email=$V_to;
    if ($V_cc!="") $TMP_email.=",".$V_cc;
    if ($V_bcc!="") $TMP_email.=",".$V_bcc;
    if ($RAD_sendMailGIE_comunicados!="") {
	$TMP_result = sql_query("SELECT * FROM GIE_entidades WHERE email='$V_to' and esentidad='1'", $RAD_dbi);
	$TMP_row = sql_fetch_array($TMP_result, $RAD_dbi);
	if (!$TMP_row[identidad]>0) {
		$TMP_result = sql_query("SELECT * FROM GIE_entidades WHERE email='$V_to'", $RAD_dbi);
		$TMP_row = sql_fetch_array($TMP_result, $RAD_dbi);
	}
	if ($TMP_row[identidad]>0) {
		$V_fileRAD=substr($V_file,7+strlen($dbname));
		$cmdSQL="INSERT INTO GIE_comunicados set identidad='".$TMP_row[identidad]."', fechaalta='".date("Y-m-d H:i:s")."', idusuario=".converttosql(getSessionVar("SESSION_U_idusuario")).", asunto=".converttosql($V_subject).", email=".converttosql($V_to).", emailorigen=".converttosql($V_from).", contenido=".converttosql($V_body).", fechaenvio='".date("Y-m-d H:i:s")."'";
		//$cmdSQL.=", documentos=".converttosql($V_fileRAD);
	}
    } else if ($TMP_idcat>0) {
	$cmdSQL="INSERT INTO contenidospriv SET idcat='".$TMP_idcat."', activo='1', fechapubli=".time().", fechaalta=".time().", autor=".converttosql($TMP_autor).", ";
	$cmdSQL.="email='".$TMP_email."', tema='".$V_subject."', contenido=".converttosql($V_body)."";
    }
    // MODIFICACION Sep'08 Multiples ficheros en radsendmail como array (retrocompatible)
    if (!is_array($V_file)) {
        if ($V_file=="") $V_arrayfile=array();
        else $V_arrayfile=array($V_file);
    } else $V_arrayfile=$V_file;
    if (count($V_arrayfile)>0) {
      $TMP_stringdocs.="";
      foreach ($V_arrayfile as $V_file) {
        if ($V_file=="") continue;
        $V_fileRAD=basename($V_file);
        $TMP_mes=date("m")*1;
        $TMP_SubDir=date("Y")."-".$TMP_mes;
        if(!file_exists(_DEF_DIRBASE."files/".$dbname."/".$TMP_SubDir)) {
            mkdir(_DEF_DIRBASE."files/".$dbname."/".$TMP_SubDir, 0777);
            copy(_DEF_DIRBASE."files/".$dbname."/index.html",_DEF_DIRBASE."files/".$dbname."/".$TMP_SubDir."/index.html");
        }
        copy($V_file,_DEF_DIRBASE."files/".$dbname."/".$TMP_SubDir."/".$V_fileRAD);
        if ($TMP_stringdocs!='') $TMP_stringdocs.="\n";
        $TMP_stringdocs.=$TMP_SubDir."/".$V_fileRAD;
      }
      if ($cmdSQL!="") $cmdSQL.=", documentos='".$TMP_stringdocs."'";
    }
    if ($V_sqlroi!='' && $cmdSQL!="") $cmdSQL.=", $V_sqlroi";
    //Para poder usar los correos internos en caso de que no funcione el correo saliente
    if ($V_interno==1) {
        $TMP_direcciones="";
        $TMP_cuentas=explode(",",$TMP_email);
        foreach($TMP_cuentas as $i => $k) {
            $k=trim($k);
            $TMP_emails=sql_query("SELECT usuario FROM usuarios WHERE email LIKE '%".$k."%'",$RAD_dbi);
            while ($TMP_usuarios=sql_fetch_array($TMP_emails,$RAD_dbi)) {
                if ($TMP_usuarios['usuario']=="") {
                    alert("No hay una relacion de la cuenta ".$k." con ningun usuario de la aplicacion");
                }else{
                    $TMP_destinatarios.=",".$TMP_usuarios['usuario'].",";
                }
            }
        }
        if ($cmdSQL!="") {
		$cmdSQL.=", destinatarios='".$TMP_destinatarios."'";
		sql_query($cmdSQL, $RAD_dbi);
	}
        return;
    }
    if ($cmdSQL!="") sql_query($cmdSQL, $RAD_dbi);

    if ((eregi("<body",$V_body) || eregi("<table",$V_body))
       && (ereg("style.css",$V_body) || ereg("style.php",$V_body))) { //incluye el contenido del estilo
        $TMP_filecss="themes/"._DEF_THEME."/style.php";
        if (!file_exists($TMP_filecss)) $TMP_filecss="themes/"._DEF_THEME."/style.css";
        ob_end_clean();
        echo "\n<STYLE type='text/css'>\n";
        if ($TMP_filecss=="themes/$SESSION_theme/style.php") include($TMP_filecss);
        else @readfile($TMP_filecss);
        echo "\n</STYLE>\n";
        $TMP=ob_get_contents();
        ob_end_clean();
        $V_body="<html><head>".$TMP."</head>".$V_body;
    }
    $TMP_charset="iso-8859-1"; // por defecto
    ////if (_CHARSET!="" && _CHARSET!="_CHARSET") $TMP_charset=strtolower(_CHARSET);
    //if(mb_detect_encoding($V_body)=="UTF-8") $V_body=utf8_decode($V_body);
    if(ereg(chr(195),$V_subject)) $V_subject=utf8_decode($V_subject);
    if(ereg(chr(195),$V_body)) $V_body=utf8_decode($V_body);
    if (count($V_arrayfile)>0) {
        $TMP_boundary = "----------" . md5(uniqid(time()));
        foreach ($V_arrayfile as $k=>$V_file) {
            if ($V_file=="") continue;
            // Limpieza de nombre de fichero
            $TMP_filename=basename($V_file);
            $TMP_arr=explode(".",$TMP_filename);
            if (count($TMP_arr)>=3 && ereg("-[0-9]+-[0-9]+-",$TMP_arr[0])) { // >3 fecha.usuario.nombre.pdf
               $TMP_arr2= array_slice($TMP_arr, 2);
            }
            else $TMP_arr2=$TMP_arr;
            $TMP_filename=implode(".",$TMP_arr2);
            if (!$fp = fopen($V_file, "rb")) {
                $fp = fopen("files/".$dbname."/".$V_file, "rb");
                $TMP_data = fread($fp, filesize("files/".$dbname."/".$V_file));
            } else {
                $TMP_data = fread($fp, filesize($V_file));
            } 
	    //if (mb_detect_encoding($TMP_data)=="UTF-8") $TMP_data=utf8_decode(utf8_encode($TMP_data));
	    //$TMP_data=str_replace('<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"','<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"',$TMP_data);
            $TMP_data = chunk_split(base64_encode($TMP_data));
            fclose($fp);
            if ($k==0) {
               $TMP_h .= "MIME-Version:1.0\n";
               $TMP_h .= "Content-Type: multipart/mixed;\n\tboundary=\"$TMP_boundary\"\n";
               $TMP_contenido = "--" . $TMP_boundary . "\n";
	       if (eregi("<body",$V_body) || eregi("<table",$V_body)) {
			$TMP_h .= "Content-type: text/html; charset=".$TMP_charset."\r\n";
	       } else {
			$TMP_contenido .= "Content-Type: text/plain; charset=".$TMP_charset."\n";
	       }
               $TMP_contenido .= "Content-Transfer-Encoding: quoted-printable\n";
               //$TMP_contenido .= "Content-Transfer-Encoding: 8bit\n";
               $TMP_contenido .= "Content-Disposition: inline\n\n";
            }
            else $TMP_contenido = '';

            $TMP_contenido .= $V_body."\n\n";
            if (ereg("\.pdf",basename($V_file))) $TMP_attachment_type="application/pdf";
            elseif (ereg("\.doc",basename($V_file))) $TMP_attachment_type="application/msword";
            elseif (ereg("\.odt",basename($V_file))) $TMP_attachment_type="application/vnd.oasis.opendocument.text";
            else $TMP_attachment_type="application/unknown";
            $TMP_contenido .= "--" . $TMP_boundary . "\n";
            $TMP_contenido .= "Content-Type: " . $TMP_attachment_type . "; name=\"" .$TMP_filename . "\"\n";
            $TMP_contenido .= "Content-Transfer-Encoding: base64\n";
            $TMP_contenido .= "Content-Disposition: attachment; filename=\"" .$TMP_filename . "\"\n\n";
            $TMP_contenido .= $TMP_data . "\n";
            $V_body = $TMP_contenido;
        }
        $V_body .= "\n--$TMP_boundary--\n\n";
    } else if (eregi("<body",$V_body) || eregi("<table",$V_body)) {
        $TMP_h .= 'MIME-Version: 1.0' . "\r\n";
	$TMP_h .= "Content-type: text/html; charset=".$TMP_charset."\r\n";
    }
    if (file_exists("RAD_mailSMTP.php") && _DEF_SMTP!="" && _DEF_SMTP!="_DEF_SMTP") {
        include_once("RAD_mailSMTP.php");
        //echo $V_body;
	$V_to=str_replace(",",";",$V_to);
        $result=RAD_mailSMTP($V_from, $V_to, $V_subject, $V_body, $TMP_h);
    } else if(!(mail($V_to,$V_subject,$V_body,$TMP_h))) {
        $errorstr = "An error occurred while sending email.";
        RAD_logError("ERR: ".$errorstr);
    }
//<script type='text/javascript' src='images/submodal/common.js'></script>
//<script type='text/javascript' src='images/submodal/submodal.js'></script>
    if ($RAD_noechosendMail=="") echo "
<script>
function RAD_CloseW(reloadparent){
 if (parent) {
  if (reloadparent!=false) parent.location.href=window.top.location.href;
  parent.hidePopWin();
 } else {
  if (reloadparent!=false) window.top.location.href=window.top.location.href;
  window.top.hidePopWin();
 }
}
</script>
<br><br><br><br><center>"._DEF_NLSMessageSent."<br><br><br> <a href='javascript:RAD_CloseW(false);'><img border=0 src='".$dir_root."images/close.gif'> "._DEF_NLSClose."</a>
";
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_fastconvertHTML2CSV($input) {
    $input=eregi_replace ("<table","<table",$input);
    $input=eregi_replace ("</table","</table",$input);
    $input=eregi_replace ("<tr","<tr",$input);
    $input=eregi_replace ("<td","<td",$input);
    $output=preg_replace ("/^(.*)\<table(.*?)\>|\<\/table\>(.*)$|\<tr(.*?)\>|\<td(.*?)\>|\"|\'/si",'',$input);
    $output2=explode ("</tr>",$output);    // cut one line string to array for each line
    $output='';
    $csv_lines='0';
    foreach ($output2 as $output3) {
        $output4=explode ("</td>",$output3);    // cut each line for next array (cell)
        foreach ($output4 as $output5) {
            $output.= "\"".$output5."\",";
        }
        $output=substr_replace($output,"",-1);    // replace last charcater "," in EOL
        $output.= "\r\n";
        $csv_lines++;
    }
    return ($output);
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_convertHTML2CSV($TMP) {
    global $V_CharCSV;
// Convierte tablas HTML a campos separados por comas.return $TMP;
    $SEP=";"; // separador de celdas
    //////$TMP = utf8_decode($TMP);
    $TMP = str_replace("&nbsp;", " ", $TMP);
    $TMP = str_replace("\r", " ", $TMP);
    $TMP = str_replace("\n", " ", $TMP);
/*
    $TMP = eregi_replace("</td>", "</TD>", $TMP);
    $TMP = eregi_replace("<td", "<TD", $TMP);
    $TMP = eregi_replace("</th>", "</TD>", $TMP);
    $TMP = eregi_replace("<th", "<TD", $TMP);
*/
    $TMP = str_ireplace("</td>", "</TD>", $TMP);
    $TMP = str_ireplace("<td", "<TD", $TMP);
    $TMP = str_ireplace("</th>", "</TD>", $TMP);
    $TMP = str_ireplace("<th", "<TD", $TMP);

    //$TMP = str_replace(",", ".", $TMP); // Convierte las comas en puntos decimales
    //$TMP = str_replace(".", ",", $TMP); // FALTA convertir bien los puntos decimales en comas
    $arr = explode("</TD>", $TMP);
    if (count($arr)>0) {
	$TMP="";
	for ($ki = 0; $ki < count($arr); $ki++) {
		$par = explode("<TD", $arr[$ki]);
		if (count($par)>1) {
			$arr[$ki]=strtoupper($arr[$ki]);
			if (eregi("</TR>", $arr[$ki])) $TMP.="\r\n";
			$ult=count($par)-1;
			// Elimina codigo HTML
			$par[$ult]=ereg_replace("<([^>]+)>", "", $par[$ult]);
			$restopar = explode(">", $par[$ult]);
			if (count($restopar)>0) {
				$ultr=count($restopar)-1;
				$ultr=1;
				$restopar[$ultr]=trim($restopar[$ultr]);
				if (is_numeric($restopar[$ultr])) $restopar[$ultr]=str_replace(".",",",$restopar[$ultr]);
    				if ($V_CharCSV=="UTF-8") $restopar[$ultr]=utf8_encode($restopar[$ultr]);
    				if ($V_CharCSV=="ISO-8859-1") $restopar[$ultr]=utf8_decode($restopar[$ultr]);
				$TMP_line=html_entity_decode($restopar[$ultr],ENT_QUOTES,$V_CharCSV).$SEP;
			} else {
				$par[$ult]=trim($par[$ult]);
				if (is_numeric($par[$ult])) $par[$ult]=str_replace(".",",",$par[$ult]);
    				if ($V_CharCSV=="UTF-8") $par[$ultr]=utf8_encode($par[$ultr]);
    				if ($V_CharCSV=="ISO-8859-1") $par[$ultr]=utf8_decode($par[$ultr]);
				$TMP_line=html_entity_decode($par[$ult],ENT_QUOTES,$V_CharCSV).$SEP;
			}
			$TMP.=$TMP_line;
		}
	}
    }
    return $TMP;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_convertHTML2TXT($TMP) {
// Convierte HTML a texto
    $TMP=eregi_replace("<tr>", "\r\n", $TMP); // Pone saltos de linea en filas de tabla HTML
    $TMP=ereg_replace("<([^>]+)>", " ", $TMP); // Elimina codigo HTML
    $TMP=strip_tags(html_entity_decode($TMP));
    return $TMP;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_convertXHTML2TXT($TMP) {
// Convierte XHTML a texto
$search = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript
               '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
               '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
               '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments including CDATA
);
$text = preg_replace($search, '', $document);
return $text;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_convertTXT2HTML($TMP) {
// Convierte texto a HTML
    if (eregi("<p",$TMP)||eregi("<font",$TMP)||eregi("<b",$TMP)||eregi("<t",$TMP)||eregi("<s",$TMP)) return $TMP; // ya es HTML
    $TMP=htmlentities($TMP);
    $TMP=str_replace("\n", "<br>\n", $TMP); // Pone saltos de linea
    return $TMP;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_backupDB($sourcedbname,$targetdbname) {
    global $RAD_dbi, $dbname;

    if (_DEF_dbtype=="MySQL") {
        $TMP_file="privado/".$sourcedbname."_".date("YmdHis").".sql";
        system("mysqldump --user="._DEF_dbuname." --password="._DEF_dbpass." ".$sourcedbname." > ".$TMP_file);
        system("mysqladmin --user="._DEF_dbuname." --password="._DEF_dbpass." create ".$targetdbname);
        system("mysql --user="._DEF_dbuname." --password="._DEF_dbpass." ".$targetdbname." < ".$TMP_file);
        system("rm ".$TMP_file);
    }
    mkdir ("files/".$targetdbname, 0777);
    system("cp -pR files/".$sourcedbname."/* files/".$targetdbname."/.");

    if (_DEF_dbtype=="MySQL") return;


    set_time_limit(0);
    $current_date = getdate();

    if ($targetdbname=="") $targetdbname=$sourcedbname.substr($current_date["year"],2,2).$current_date["yday"]."x".$current_date["hours"].$current_date["minutes"];
    //--------------------------------------------------------------------------------------
    // Create database
    //--------------------------------------------------------------------------------------
    $result = sql_query('CREATE DATABASE '.$targetdbname, $RAD_dbi) or RAD_die('1. '._DEF_NLSError.' '.$targetdbname.': '.sql_error($result));
    //--------------------------------------------------------------------------------------
    // Copy data tables
    //--------------------------------------------------------------------------------------
    $result = sql_query('Show Tables', $RAD_dbi) or RAD_die('2a. '._DEF_NLSError.' '.$targetdbname.': '.sql_error($result));
    $num_tables=0;
    while($row=sql_fetch_array($result,$RAD_dbi)) {
        $tables[$num_tables]=$row[0];
        $num_tables++;
    }
////    echo ". $NLSTable(s) :<br>\n";
    if($num_tables == 0) {
//      echo ".. <br>\n";
    } else {
        $i = 0;
        while($i < $num_tables) {
////            echo "....<b>".$i."</b>";
            $table = $tables[$i];
            RAD_dup_table_def($sourcedbname, $targetdbname, $table, $table);
            RAD_dup_table_content($sourcedbname, $targetdbname, $table, $table);
            $i++;
        }
    }
    if ($dbname!="") $RAD_dbi = sql_connect(_DEF_dbhost, _DEF_dbuname, _DEF_dbpass, $dbname);
    else $RAD_dbi = sql_connect(_DEF_dbhost, _DEF_dbuname, _DEF_dbpass, _DEF_dbname);
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_dup_table_def($sourcedb, $targetdb, $sourcetable, $targettable) {
    global $RAD_dbi;

////    echo " $sourcetable > $targettable .";
    $schema_create = "";
    $schema_create .= "CREATE TABLE $targettable (";
    $RAD_dbi2 = sql_connect(_DEF_dbhost, _DEF_dbuname, _DEF_dbpass, $sourcedb);
    $result = sql_query("SHOW FIELDS FROM ".$sourcetable,$RAD_dbi2) or RAD_die("2b. "._DEF_NLSError." ".$sourcetable.": ".sql_error($result));
    while($row = sql_fetch_array($result, $RAD_dbi2)) {
        $schema_create .= "   $row[Field] $row[Type]";

        if(isset($row["Default"]) && (!empty($row["Default"]) || $row["Default"] == "0"))
            $schema_create .= " DEFAULT '$row[Default]'";
        if($row["Null"] != "YES")
            $schema_create .= " NOT NULL";
        if($row["Extra"] != "")
            $schema_create .= " $row[Extra]";
        $schema_create .= ",\n";
    }
    $schema_create = ereg_replace(","."\n"."$", "", $schema_create);
    $result = sql_query("SHOW KEYS FROM ".$sourcetable,$RAD_dbi2) or RAD_die("3. "._DEF_NLSError." ".$sourcetable.": ".sql_error($result));
    while($row = sql_fetch_array($result,$RAD_dbi2)) {
        $kname=$row['Key_name'];
        if(($kname != "PRIMARY") && ($row['Non_unique'] == 0)) $kname="UNIQUE|$kname";
        if(!isset($index[$kname])) $index[$kname] = array();
        $index[$kname][] = $row['Column_name'];
    }
    if (count($index)>0) {
      while(list($x, $columns) = each($index)) {
        $schema_create .= ",\n";
        if($x == "PRIMARY") $schema_create .= "   PRIMARY KEY (" . implode($columns, ", ") . ")";
        elseif (substr($x,0,6) == "UNIQUE") $schema_create .= "   UNIQUE ".substr($x,7)." (" . implode($columns, ", ") . ")";
        else $schema_create .= "   KEY $x (" . implode($columns, ", ") . ")";
      }
    }
    $schema_create .= ")";
    $schema_create=stripslashes($schema_create);
    $RAD_dbi2 = sql_connect(_DEF_dbhost, _DEF_dbuname, _DEF_dbpass, $targetdb);
    RAD_printLog($schema_create);
    $result = sql_query($schema_create ,$RAD_dbi2) or RAD_die("4. "._DEF_NLSError." ".$targettable." . ".$targetdb." : ".sql_error($result));
//  echo "$schema_create <hr>";
    return;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_dup_table_content($sourcedb, $targetdb, $sourcetable, $targettable) {
    global $RAD_dbi;

    $cmd="INSERT INTO ".$targetdb.".".$targettable." SELECT * FROM ".$sourcedb.".".$sourcetable;
    RAD_printLog($cmd);
    sql_query($cmd, $RAD_dbi);
/*****
    $RAD_dbi2 = sql_connect(_DEF_dbhost, _DEF_dbuname, _DEF_dbpass, $sourcedb);

    $result = sql_query("SELECT * FROM ".$sourcetable,$RAD_dbi2) or RAD_die("5. "._DEF_NLSError." ".$sourcetable.": ".sql_error($result));
// echo _DEF_dbhost._DEF_dbpass._DEF_dbuname.$RAD_dbi2."Dup tabla ".$sourcetable." de ".$sourcedb." en ".$targetdb.$result.sql_error($result)."<br>";
    $i = 0;
    $RAD_dbi2 = sql_connect(_DEF_dbhost, _DEF_dbuname, _DEF_dbpass, $targetdb);
    while($row = sql_fetch_row($result, $RAD_dbi2)) {
//      set_time_limit(60);
        if(isset($GLOBALS["showcolumns"])) $schema_insert = "INSERT INTO $targettable VALUES (";
        else $schema_insert = "INSERT INTO $targettable VALUES (";
        for($j=0; $j<sql_num_fields($result);$j++) {
            if(!isset($row[$j])) $schema_insert .= " NULL,";
            elseif($row[$j] != "") $schema_insert .= " '".addslashes($row[$j])."',";
            else $schema_insert .= " '',";
        }
        $schema_insert = ereg_replace(",$", "", $schema_insert);
        $schema_insert .= ")";
        $sqlIns=htmlspecialchars(trim($schema_insert).";");
        $result2 = sql_query($sqlIns ,$RAD_dbi2) or RAD_die("6. "._DEF_NLSError." ".$targettable." $sqlIns: ".sql_error($result2));
        $i++;
//echo sql_error($result2).$sqlIns."<br>";
    }
*****/
    return (true);
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_validUploadFile($file) {
global $POSTACTION;
    $ext=strtolower(substr($file,strlen($file)-4));
    if ($ext==".php" || $ext=="phtm" || $ext=="phtml" || $ext=="php3" || $ext=="php4" || $ext=="php5" || $ext==".inc") {
        error("Extension de fichero ".$file." no permitida");
        die;
    }
    return "";
    if ($ext!=".rtf" && $ext!=".zip" && $ext!=".tgz" && $ext!=".tar" && $ext!=".doc" && $ext!=".gif" && $ext!=".jpg" && $ext!=".jpeg" && $ext!=".png" && $ext!=".htm" && $ext!="html" && $ext!=".xml" && $ext!=".txt" && $ext!=".swf" && $ext!=".css" && $ext!=".js" && $ext!=".pdf" && $ext!=".dot") {
        error("Extension de fichero ".$file." no permitida. Solo se permiten rtf, zip, tgz, tar, doc, gif, jpg, png, htm, txt, xml, swf, pdf, css y js");
        die;
    }
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_saveHTML($table,$field,$where) {
global $RAD_dbi;

  if (_DEF_SAVE_PRINT!="1") return;

  $TMP=ob_get_contents();
  ob_end_clean();
  ob_start();
  echo $TMP;

  $TMP=str_replace("window.print();","",$TMP);
  $TMP=str_replace("images/","../../../images/",$TMP);
  $TMP=str_replace("files/","../../../files/",$TMP);
  $TMP=str_replace("themes/","../../../themes/",$TMP);

  RAD_saveFileContent($table,$field,$where,"impresion.htm",$TMP);
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_copyFile($namefile, $file) {
    $namefile2=RAD_nameSecure($namefile);
    list($TMP_fichcompleto,$TMP_fich)=RAD_nameDownload($namefile2);
    copy($file,$TMP_fichcompleto);
    return $TMP_fich;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_saveFileContent($table,$field,$where,$namefile,$content) {
global $RAD_dbi;

  $TMP=$content;
  $TMP_filename=RAD_nameSecure($namefile);
  list($TMP_fichcompleto,$TMP_fich)=RAD_nameDownload($TMP_filename);
  $fp = fopen($TMP_fichcompleto,"w");
  fwrite($fp,$TMP);
  fclose($fp);
  chmod($TMP_fichcompleto, 0666);
  if (trim($table)!="" && trim($field)!="" && trim($where)!="" && trim($TMP_fich)!="") {
    $TMP_fich.="\n";
    $cmdSQL="SELECT count(*) FROM $table WHERE $where";
    $result=sql_query($cmdSQL,$RAD_dbi);
    $row=sql_fetch_array($result,$RAD_dbi);
    if ($row[0]==1) {
        $cmdSQL="SELECT $field FROM $table WHERE $where";
        $result=sql_query($cmdSQL,$RAD_dbi);
        $row=sql_fetch_array($result,$RAD_dbi);
        if (trim($row[0])!="") $TMP_fich=$row[0].$TMP_fich;
        $cmdSQL="UPDATE $table SET $field='$TMP_fich' WHERE $where";
        RAD_printLog($cmdSQL);
        sql_query($cmdSQL,$RAD_dbi);
    }
  }

  return $TMP_fichcompleto;
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_sustituyeVarPHP($literal) {
// Sustituye en un texto la expresion PHP &lt;?=$var?&gt; por el valor de la variable var.
        $literal="*".$literal;
        $pos=strpos($literal,"<"."?=");
        while($pos>0) {
                $prefijo=substr($literal,0,$pos);
                $resto=substr($literal,$pos+3);
                $pos=strpos($resto,"?".">");
                if ($pos>0) {
                        $sufijo=substr($resto,$pos+3);
                        $variable=substr($resto,1,$pos-1);
                        global ${$variable};
                        $valor=${$variable};
                        $valor=utf8_encode($valor);
                }
                $literal=$prefijo.$valor.$sufijo;
                $pos=strpos($literal,"<"."?=");
        }
        $pos=strpos($literal,"&lt;?=");
        while($pos>0) {
                $prefijo=substr($literal,0,$pos);
                $resto=substr($literal,$pos+6);
                $pos=strpos($resto,"?"."&gt;");
                if ($pos>0) {
                        $sufijo=substr($resto,$pos+5);
                        $variable=substr($resto,1,$pos-1);
                        global ${$variable};
                        $valor=${$variable};
                        $valor=utf8_encode($valor);
                }
                $literal=$prefijo.$valor.$sufijo;
                $pos=strpos($literal,"&lt;?=");
        }
        return substr($literal,1);
}
//////////////////////////////////////////////////////////////////////////////////
function RAD_setFieldProperty($fieldList,$propertyList) {
//>>      Establece las propiedades de los campo
//>>      Parametros:
//>>        - un o una lista de nombres de campos separados por comas,
//>>          a los que se les van a aplicar las propiedades indicadas
//>>        - un o una lista de pares "propiedad=valor", que determinan la
//>>          propiedad a modificar para cada campo indicado en el primer parametro.
//>>      Ejemplo:
//>>        RAD_setFieldPropery("campo1,campo2","readonly=true,canbenull=false")
//>>     equivale a las siguientes instrucciones
//>>        $field[$findex["campo1"]]->readonly=true;
//>>        $field[$findex["campo1"]]->canbenull=false;
//>>        $field[$findex["campo2"]]->readonly=true;
//>>        $field[$findex["campo2"]]->canbenull=false
//////////////////////////////////////////////////////////////////////////////////
    global $fields,$findex,$debug;
    if ($debug==1) return;
    $aFields = explode(",",$fieldList);
    $aProperties = explode(",",$propertyList);
    foreach ($aFields as $f) {
	foreach ($aProperties as $p) {
		if ($debug==2) echo("\$fields[\$findex['$f']]->$p;<br>");
		//if (trim($fields[$findex['$f']]->$p)=="") continue;
		eval("\$fields[\$findex['$f']]->$p;");
	}
    }
}
//////////////////////////////////////////////////////////////////////////////////
//>>   <RAD_debug> : agrega una linea al fichero de debug de la sesion
function RAD_debug($texto) {
//Las siguientes 2 variables deben coincidir en su definicion con las incluidas en debug.php
   $DEBUG_FILEPATH=_DEF_DIRBASE."files/tmp/";
   $DEBUG_CRLF = chr(13) . chr(10);

   if (getSessionVar("SESSION_debugFileName")=='')  {
      setSessionVar("SESSION_debugFileName",$DEBUG_FILEPATH  . "debug." . date('YmdHis') . ".log",0);
      $DEBUG_FILE_NAME = getSessionVar("SESSION_debugFileName");
   } else {
      $DEBUG_FILE_NAME = getSessionVar("SESSION_debugFileName");
   }
   $f = fopen($DEBUG_FILE_NAME,"a");
   fputs($f,$texto . $DEBUG_CRLF);
   fclose($f);
}
//////////////////////////////////////////////////////////////////////////////////
function RAD_debugClear() {
   $DEBUG_FILE_NAME = getSessionVar("SESSION_debugFileName");
   if ($DEBUG_FILE_NAME=='') return "";
   $f = fopen($DEBUG_FILE_NAME,"w");
   fclose($f);
}
//////////////////////////////////////////////////////////////////////////////////
function RAD_tree($TMP_URL, $TMP_table, $TMP_defaultfilter, $TMP_orderby, $TMP_showfields, $TMP_field, $TMP_fieldparent, $TMP_idreg) {
global $TMP_idregpadres, $TMP_idreghijos, $TMP_onlyparents;
global $numrow, $RAD_dbi;

    $numrow=0;
    $cmdSQL="SELECT count(*) FROM $TMP_table";
    if ($TMP_defaultfilter!="") $cmdSQL.=" WHERE ".$TMP_defaultfilter."";
    if (!$TMP_result=sql_query($cmdSQL, $RAD_dbi)) {
        $TMP_resultado .= $cmdSQL.sql_error($RAD_dbi);
        return $TMP_resultado;
    } else {
        $TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
        if ($TMP_row[0]>$rows_limit*5 && $TMP_idreg=="") $TMP_onlyparents=true;
        else $TMP_onlyparents=false;
    }

    $TMP_idregpadres=",";
    $TMP_idreghijos=",";
    if ($TMP_idreg!="") {   // busca padres,abuelos... e hijos de TMP_idreg
        $TMP_idreg2=$TMP_idreg;
        do {
        $cmdSQL="SELECT * FROM $TMP_table WHERE $TMP_field='$TMP_idreg2'";
        $TMP_result=sql_query($cmdSQL, $RAD_dbi);
        $TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
        if ($TMP_row[$TMP_fieldparent]) $TMP_idregpadres.=$TMP_row[$TMP_fieldparent].",";
        $TMP_idreg2=$TMP_row[$TMP_fieldparent];
        } while ($TMP_row[$TMP_fieldparent]!="");
        $cmdSQL="SELECT * FROM $TMP_table WHERE $TMP_fieldparent='$TMP_idreg'";
        $TMP_result=sql_query($cmdSQL, $RAD_dbi);
        while($TMP_row=sql_fetch_array($TMP_result, $RAD_dbi)) $TMP_idreghijos.=$TMP_row[$TMP_field].",";
    }
    $TMP_resultado.=RAD_showtree($TMP_URL, $TMP_table, $TMP_defaultfilter, $TMP_orderby, $TMP_showfields, $TMP_field, $TMP_fieldparent, $TMP_idreg, 0,"");
    return $TMP_resultado;
}
//////////////////////////////////////////////////////////////////////////////////
function RAD_showtree($TMP_URL, $TMP_table, $TMP_defaultfilter, $TMP_orderby, $TMP_showfields, $TMP_field, $TMP_fieldparent, $TMP_idreg, $TMP_level, $TMP_par) {
global $URLROI, $RAD_dbi, $V_dir, $V_mod, $func, $numrow, $TMP_AshowedID;
global $TMP_idregpadres, $TMP_idreghijos, $TMP_onlyparents;

    if ($TMP_par=="") {
        if ($TMP_defaultfilter!="") $TMP_defaultfilter2=" WHERE ".$TMP_defaultfilter." AND ($TMP_fieldparent IS NULL OR $TMP_fieldparent='' OR $TMP_fieldparent='0')";
        else $TMP_defaultfilter2="WHERE ($TMP_fieldparent IS NULL OR $TMP_fieldparent='' OR $TMP_fieldparent='0')";
    } else {
        if ($TMP_defaultfilter!="") $TMP_defaultfilter2=" WHERE ".$TMP_defaultfilter." AND $TMP_fieldparent='$TMP_par'";
        else $TMP_defaultfilter2="WHERE $TMP_fieldparent='$TMP_par'";
    }
    $cmdSQL="SELECT * FROM $TMP_table ".$TMP_defaultfilter2."";
    if ($TMP_orderby!="") $cmdSQL.="ORDER By ".$TMP_orderby;
    $TMP_result=sql_query($cmdSQL, $RAD_dbi);
    $lang=getSessionVar("SESSION_lang");
    while ($V_TMP_row=sql_fetch_array($TMP_result, $RAD_dbi)) {
        if ($TMP_AshowedID[$V_TMP_row[$TMP_field]]!="") continue;

        if ($TMP_idreg!="") {
            if (    !ereg(",".$V_TMP_row[$TMP_field].",",$TMP_idregpadres) &&
                !ereg(",".$V_TMP_row[$TMP_fieldparent].",",$TMP_idregpadres) &&
                !ereg(",".$V_TMP_row[$TMP_field].",",$TMP_idreghijos) &&
                $V_TMP_row[$TMP_field]!=$TMP_idreg &&
                $V_TMP_row[$TMP_fieldparent]!="" &&
                $V_TMP_row[$TMP_fieldparent]!="0") continue;
        }

        if ($TMP_onlyparents==true && $V_TMP_row[$TMP_fieldparent]!="" && $V_TMP_row[$TMP_fieldparent]!="0") continue;

        $TMP_AshowedID[$V_TMP_row[$TMP_field]]="x";

        $TMP_line[$numrow]="<TR>\n";
        if ($RAD_classrow == "class=row1") $RAD_classrow = "class=row2";
        else $RAD_classrow = "class=row1";

        $TMP_stringlevel=str_repeat("&nbsp;",$TMP_level*3);

        if ($TMP_idreg!=$V_TMP_row[$TMP_field]) $htmllink="<A TITLE='Ver Registro ".($numrow)."' HREF=\"".$PHP_SELF."?V_dir=$V_dir&V_mod=$V_mod&func=".$func."&par0=".$V_TMP_row[$TMP_field].$URLROI."\">";
        else $htmllink="<A TITLE='Ver Registro ".($numrow)."' HREF=\"".$PHP_SELF."?V_dir=$V_dir&V_mod=$V_mod&func=".$func.$URLROI."\">";

        $cmdSQL2="SELECT count(*) FROM $TMP_table WHERE $TMP_fieldparent='".$V_TMP_row[$TMP_field]."'";
        $TMP_result2=sql_query($cmdSQL2, $RAD_dbi);
        $TMP_row2=sql_fetch_array($TMP_result2, $RAD_dbi);
        if ($TMP_row2[0]>0) {
            if ($TMP_idregpadres!="" && ereg(",".$V_TMP_row[$TMP_field].",",$TMP_idregpadres)) $TMP_stringlevel.="<img src='images/menos.gif' border=0> ";
            else if ($V_TMP_row[$TMP_field]==$TMP_idreg) $TMP_stringlevel.="<img src='images/menos.gif' border=0> ";
            else if ($TMP_onlyparents==false && $TMP_idreg=="") $TMP_stringlevel.="<img src='images/menos.gif' border=0> ";
            else $TMP_stringlevel.="<img src='images/mas.gif' border=0> ";
        } else {
            $TMP_stringlevel.="<img src='images/tr.gif' width=16 height=1 border=0> ";
            $htmllink="";
        }

        $TMP_Ashowfields=explode(",",$TMP_showfields);
        if (count($TMP_Ashowfields)>0) for ($i = 0; $i < count($TMP_Ashowfields); $i++) {
            $TMP_f=$TMP_Ashowfields[$i];
            if ($TMP_row[$TMP_f."_".$lang]!="") $TMP_row[$TMP_f]=$TMP_row[$TMP_f."_".$lang];
            if ($i==0) {
                $TMP_line[$numrow].="<TD $RAD_classrow>";
                if ($htmllink!="") $TMP_line[$numrow].=$htmllink;
                $TMP_line[$numrow].=$TMP_stringlevel;
                if ($htmllink!="") $TMP_line[$numrow].="</A>";
                if ($TMP_URL!="") $TMP_line[$numrow].="<A HREF='".$TMP_URL."&par0=".$V_TMP_row[$TMP_field]."'>";
                $TMP_line[$numrow].=$V_TMP_row[$TMP_f];
                if ($TMP_URL!="") $TMP_line[$numrow].="</A>\n";
                $TMP_line[$numrow].="</TD>\n";
            } else $TMP_line[$numrow].="<TD $RAD_classrow>".$V_TMP_row[$TMP_f]."</TD>\n";
                }
        $TMP_line[$numrow].="</TR>\n";
        $TMP_resultado.= $TMP_line[$numrow];
            $numrow++;
        if ($TMP_onlyparents==false && $TMP_row2[0]>0) $TMP_resultado.=RAD_showtree($TMP_URL, $TMP_table, $TMP_defaultfilter, $TMP_orderby, $TMP_showfields, $TMP_field, $TMP_fieldparent, $TMP_idreg, ($TMP_level+1), $V_TMP_row[$TMP_field]);
    }
    return $TMP_resultado;
}
//////////////////////////////////////////////////////////////////////////////////
function RAD_dirsize($directory) {
   if (!is_dir($directory)) return -1;
   $size = 0;
   if ($DIR = opendir($directory)){
      while (($dirfile = readdir($DIR)) !== false){
         if (is_link($directory . '/' . $dirfile) || $dirfile == '.' || $dirfile == '..')
           continue;
         if (is_file($directory . '/' . $dirfile))
           $size += filesize($directory . '/' . $dirfile);
         else if (is_dir($directory . '/' . $dirfile)){
           $dirSize = RAD_dirsize($directory . '/' . $dirfile);
           if ($dirSize >= 0) $size += $dirSize;
           else return -1;
         }
      }
      closedir($DIR);
   }
   return $size;
}
//////////////////////////////////////////////////////////////////////////////////
function RAD_HTTP_Post($URL,$data, $referrer="", $timeout="") {
    $URL_Info=parse_url($URL);
    if(!isset($URL_Info["port"])) $URL_Info["port"]=80;

    if ($referrer=="") $referrer=$_SERVER["SCRIPT_URI"];

    foreach($data as $key=>$value) $values[]="$key=".urlencode($value);
    $data_string=implode("&",$values);

    // building POST-request:
    $request.="POST ".$URL_Info["path"]." HTTP/1.1\n";
    $request.="Host: ".$URL_Info["host"]."\n";
    $request.="Port: ".$URL_Info["port"]."\n";
    if ($URL_Info["user"]!="") $request.="Authorization: Basic ".base64_encode($URL_Info["user"].":".$URL_Info["pass"])."\n";
    $request.="User-Agent: POST-phpRAD\n";
    $request.="Referer: $referer\n";
    $request.="Content-type: application/x-www-form-urlencoded\n";
    $request.="Content-length: ".strlen($data_string)."\n";
    $request.="Connection: close\n";
    $request.="\n";
    $request.=$data_string."\n";
    if ($timeout=="") $timeout=30;
    if ($fp = fsockopen($URL_Info["host"],$URL_Info["port"], $errno, $errstr, $timeout)) {
        fputs($fp, $request);
        $result="";
        while(!feof($fp)) $result.=fgets($fp, 1024);
        fclose($fp);
    }
    if (_DEF_DEBUG_POST!="" && _DEF_DEBUG_POST!="_DEF_DEBUG_POST") {
        $fp=fopen("files/tmp/".uniqid("POST_"),"w");
        fwrite($fp,$request.$errno.$errstr."\n".$result);
        fclose($fp);
    }
    return $errno.$errstr."\n".$result;
}
//////////////////////////////////////////////////////////////////////////////////
function RAD_reloadURL($params) {
global $_SERVER, $SESSION_SID;

if ($params!="") if (substr($params,0,1)!="&") $params="&".$params;
$TMP_paramsdest=explode("&",$params);
if (count($TMP_paramsdest)==0 && $params!="") $TMP_paramsdest[0]=$params;

if ($_SERVER["REQUEST_METHOD"]=="GET") {
    $TMP_URL=$_SERVER["PHP_SELF"]."?";
    $TMP_params=explode("&",$_SERVER["QUERY_STRING"]);
    if (count($TMP_params)==0 && $_SERVER["QUERY_STRING"]!="") $TMP_params[0]=$_SERVER["QUERY_STRING"];
    if (count($TMP_params)>0) {
    for ($ki=0; $ki<count($TMP_params); $ki++) {
        $TMP_param=explode("=",$TMP_params[$ki]);
        if (count($TMP_param)>1) {
        $TMP_Wparam[$TMP_param[0]]=$TMP_param[1];   // pares de parametro valor
        }
    }
    }
    if (count($TMP_paramsdest)>0) {
    for ($ki=0; $ki<count($TMP_paramsdest); $ki++) {
        $TMP_param=explode("=",$TMP_paramsdest[$ki]);
        if (count($TMP_param)>1) {
        $TMP_Wparam[$TMP_param[0]]=urlencode($TMP_param[1]);    // pares de parametro valor
        }
    }
    }
    foreach ($TMP_Wparam as $TMP_key=>$TMP_val) {
    if ($TMP_val!="") $TMP_URL.="&".$TMP_key."=".$TMP_val;
    }
} else {
    $TMP_URL=dirname($_SERVER["PHP_SELF"]);
    $TMP_URL.="index.php?".$SESSION_SID.$params;
}
echo "\n<script>\ndocument.location.href='$TMP_URL';\n</script>\n";
die();
//ob_end_clean();
//Header("Location: ".$TMP_URL);
//die();
}

//////////////////////////////////////////////////////////////////////////////////
function RAD_doZIP($value){
    // Recibe el contenido en base64 de un campo de dtype='file' y realiza un zip con los archivos que contiene
    $files=base64_decode($value);
    if (trim($files)=='') return false;

    // Directorio Base donde se meten los zips
    $res = shell_exec('ls '._DEF_DIRBASE.'files/tmp');
    if ($res=='')
    if (system("mkdir "._DEF_DIRBASE.'files/tmp')===false) return false;

    $files = explode("\n",$files);
    $TMP_files = "";
    foreach ($files as $k=>$v) {
        if (trim($v)=='') continue;

        $v = str_replace("\r",'',$v);
        $v = str_replace("\n",'',$v);
        $TMP_files.= _DEF_DIRBASE."files/"._DEF_dbname."/$v ";
    }

    // Hacemos el zip, necesitamos el comando zip en el sistema, la opcion -j hace que meta todos los ficheros sin separar los directorios
    $ficherozip = "files/tmp/docs-".uniqid().".zip";
    if (system("zip -j "._DEF_DIRBASE."$ficherozip $TMP_files")===false) return false;

    /* Enviarlo */
    ob_end_clean();
    echo "<script>window.location='"._DEF_URL.$ficherozip."';</script>";

    return true;
}
///////////////////////////////////////////////////////////////
function RAD_mailSocket($smtpserver,$to,$subject,$message,$headers,$auth) {
global $GLOBAL;

if ( preg_match("/From:.*?[A-Za-z0-9\._%-]+\@[A-Za-z0-9\._%-]+.*/", $headers, $froms) ) {
     preg_match("/[A-Za-z0-9\._%-]+\@[A-Za-z0-9\._%-]+/", $froms[0], $fromarr);
     $from = $fromarr[0]; // from address
}

// Open an SMTP connection
  $cp = fsockopen ($smtpserver, "25", $errno, $errstr, 1);
  if (!$cp) return "Failed to even make a connection";
  $res=fgets($cp,256);
  if(substr($res,0,3) != "220") return "Failed to connect";

  fputs($cp, "HELO ".$smtpserver."\r\n"); // HELO
  $res=fgets($cp,256);
  if(substr($res,0,3) != "250") return "Failed to Introduce";

  if ($auth) {
        fputs($cp, "auth login\r\n"); // authentication
        $res=fgets($cp,256);
        if(substr($res,0,3) != "334") return "Failed to Initiate Authentication";
        fputs($cp, base64_encode($GLOBAL["SMTP_USERNAME"])."\r\n");
        $res=fgets($cp,256);
        if(substr($res,0,3) != "334") return "Failed to Provide Username for Authentication";
        fputs($cp, base64_encode($GLOBAL["SMTP_PASSWORD"])."\r\n");
        $res=fgets($cp,256);
        if(substr($res,0,3) != "235") return "Failed to Authenticate";
  }

  fputs($cp, "MAIL FROM: <$from>\r\n"); // MAIL FROM
  $res=fgets($cp,256);
  if(substr($res,0,3) != "250") return "MAIL FROM failed";

  fputs($cp, "RCPT TO: <$to>\r\n"); // RCPT TO
  $res=fgets($cp,256);
  if(substr($res,0,3) != "250") return "RCPT TO failed";

  fputs($cp, "DATA\r\n"); // DATA
  $res=fgets($cp,256);
  if(substr($res,0,3) != "354") return "DATA failed";

  // To:, From:, Subject:, Headers, blank line, Message, and finish period
  //fputs($cp, "To: $to\r\nFrom: $from\r\nSubject: $subject\r\n$headers\r\n\r\n$message\r\n.\r\n");
  fputs($cp, "To: $to\r\nSubject: $subject\r\n$headers\r\n\r\n$message\r\n.\r\n");
  $res=fgets($cp,256);
  if(substr($res,0,3) != "250") return "Message Body Failed";

  fputs($cp,"QUIT\r\n"); // QUIT
  $res=fgets($cp,256);
  if(substr($res,0,3) != "221") return "QUIT failed";

  return true;
}


//////////////////////////////////////////////////////////////////////////////////
function RAD_sqlUniqueResult($query)
{
    global $RAD_dbi;
    $TMP_result=sql_query($query, $RAD_dbi);
    @$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
    $TMP_content=$TMP_row[0];
    sql_free_result($TMP_result);
    return $TMP_content;
}
//////////////////////////////////////////////////////////////////////////////////
function RAD_sqlUniqueRow($query)
{
    global $RAD_dbi;
    $TMP_result=sql_query($query, $RAD_dbi);
    @$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
    sql_free_result($TMP_result);
    return $TMP_row;
}
//////////////////////////////////////////////////////////////////////////////////
function RAD_printDoc($TMP_idimpreso, $TMP_tabimpreso, $TMP_impreso) {
// TMP_tabimpreso: tabla donde se almacena el nombre del impreso. Por defecto "impresos"
// TMP_idimpreso: id del registro de la tabla anterior
// TMP_impreso: nombre de fichero de impreso (opcional), Si se especifica este fichero ya no se requieren los parametros anteriores
    global  $V_idimpreso, $V_tabimpreso, $V_impreso, $V_save;
    $V_save="x";
    $V_idimpreso=$TMP_idimpreso;
    $V_tabimpreso=$TMP_tabimpreso;
    $V_impreso=$TMP_impreso;
    include("impresion.php");
}
//////////////////////////////////////////////////////////////////////////////////
function RAD_genDoc($TMP_doc) {
    if (!file_exists($TMP_doc)) return "";
        $TMP_dir="/tmp/".uniqid("");
        mkdir($TMP_dir,0777);
        $cmd1="cd $TMP_dir; unzip "._DEF_DIRBASE.$TMP_doc." 2>&1 1>/dev/null";
        @system($cmd1);

        $TMP_file=$TMP_dir."/content.xml";
        $fp = fopen($TMP_file, "r");
        $TMP_content = fread($fp, filesize($TMP_file));
        fclose($fp);
    // Ahora hay que reemplazar o evaluar $TMP_content
        $TMP_content=RAD_sustituyeVarPHP($TMP_content); // de momento solo se sustituyen los mayor?=$var?menor
        $TMP_content2=str_replace("\"","\\\"",$TMP_content);
        eval("\$TMP_content3=\"".$TMP_content2."\";");  // evalua, aunque se podria reemplazar mejor
        if ($TMP_content3!="") $TMP_content=$TMP_content3;
        $fp = fopen($TMP_file,"w"); // reescribe content.xml
        fwrite($fp,$TMP_content);
        fclose($fp);

        $cmd2="cd $TMP_dir; zip -r ".$TMP_dir.".odt * 2>&1 1>/dev/null";
        system($cmd2);
        return $TMP_dir.".odt";
/* utilizar lineas siguientes para devolver el contenido fichero
    ob_end_clean();
    ob_start();
        $fp = fopen($TMP_dir.".odt", "r");
        $TMP_content = fread($fp, filesize($TMP_dir.".odt"));
        fclose($fp);
        $cmd3="cd /tmp; rm -rf $TMP_dir ".$TMP_dir.".odt";
        @system($cmd3);
        header('Content-Type: application/vnd.oasis.opendocument.text;');
        header("Content-Disposition: inline; filename=$TMP_doc\n");
        echo $TMP_content;
*/
}
//////////////////////////////////////////////////////////////////////////////////
function RAD_print_var($var) {
   if(is_string($var))
       return('"'.str_replace(array("\x00","\x0a","\x0d","\x1a","\x09"),array('\0','\n','\r','\Z','\t'),$var ).'"');
   else if(is_bool($var)) {
       if($var) return('true');
       else return('false');
   } else if(is_array($var)) {
       $result='array( ';
       $comma='';
       foreach($var as $key=>$val) {
           $result.=$comma.RAD_print_var($key).'=>'.RAD_print_var($val);
           $comma=', ';
       }
       $result.=' )';
       return($result);
   }
   return(var_export($var,true)); // anything else, just let php try to print it
}
//////////////////////////////////////////////////////////////////////////////////
function RAD_trace($msg) {
   //var_export(debug_backtrace()); return;    // this line shows what is going on underneath
   $trace=array_reverse(debug_backtrace());
   $func='';
   $TMP_DIRBASE=dirname(__FILE__)."/";
   $TMP_trace="";
   if ($msg!="") $TMP_trace.=$msg."\n";
   $TMP_cont=0;
   foreach($trace as $val) {
       $TMP_cont++;
       if ($TMP_cont==count($trace) && substr($val['file'],strlen($val['file'])-14)=="/functions.php") continue;
       if ($TMP_cont>1) $TMP_trace.=">";
       if (substr($val['file'],0,strlen($TMP_DIRBASE))==$TMP_DIRBASE) $val['file']=substr($val['file'],strlen($TMP_DIRBASE));
       $TMP_trace.=$val['file'].':'.$val['line'];
       if($func) $TMP_trace.=' function '.$func;
       if($val['function']=='include' ||
          $val['function']=='require' ||
          $val['function']=='include_once' ||
          $val['function']=='require_once')
           $func = '';
       else {
           $func=$val['function'];
           //$func=$val['function'].'(';
           if(isset($val['args'][0])) {
               //$func.=' ';
               $comma='';
               foreach($val['args'] as $val) {
                   //$func.=$comma.RAD_print_var($val);
                   $comma=', ';
               }
               //$func.=' ';
           }
           //$func.= ')';
       }
   }
   return $TMP_trace;
}
//////////////////////////////////////////////////////////////////////////////////
function RAD_moveField($fieldname,$offset) {
// Desplaza dentro del modulo,el campo $fieldname, $offset posiciones,
// modificando el orden de aparicion de los campos
   global $fields;
   if ($offset==0) return;
   $aCampos = array();
   $campoNombre = "";

   do {
      $oField = array_shift($fields);
      if ($oField->name==$fieldname) $salir = true;
      else array_push($aCampos,$oField);
   } while($salir==false);

   if ($offset<0) {
      $aAux = array();
      for ($i=0;$i<count($aCampos) + $offset;$i++)  array_push($aAux,array_shift($aCampos));
      array_push($aAux,$oField);
      foreach($aCampos as $f) array_push($aAux,$f);
      foreach($fields  as $f) array_push($aAux,$f);
      $fields=$aAux;
   } else {
       for($i=1;$i<=$offset;$i++) array_push($aCampos,array_shift($fields));
       array_push($aCampos,$oField);
       foreach($fields as $f) array_push($aCampos,$f);
       $fields=$aCampos;
   }
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_ImgHTML($TMP) {
    if (trim($TMP)=="") return "";
    $TMP=str_replace("'", '"', $TMP);
    $TMP_fotos=iExplode("<IMG"," ".$TMP);
    $TMP_foto="";
    if (count($TMP_fotos)>1)  {
        for ($ki=1; $ki<count($TMP_fotos); $ki++) {
            $TMP_fotosSrc=iExplode("src"," ".$TMP_fotos[$ki]);
            if (count($TMP_fotosSrc)>1)  {
                $TMP_fotosSrc[1]=str_replace("=","",$TMP_fotosSrc[1]);
                $TMP_fotosSrc[1]=trim($TMP_fotosSrc[1]);
                if (substr($TMP_fotosSrc[1],0,1)=='"') {
                    $TMP_fotosSrcFile=explode('"',$TMP_fotosSrc[1]);
                    $TMP_foto=$TMP_fotosSrcFile[1];
                } else {
                    $TMP_fotosSrcFile=explode(" ",$TMP_fotosSrc[1]);
                    $TMP_foto=$TMP_fotosSrcFile[0];
                }
                if (substr($TMP_foto,0,1)=="/") $TMP_foto=substr($TMP_foto,1);
                list($anchoimg, $altoimg, $tipo, $resto)=getimagesize(_DEF_DIRBASE.$TMP_foto);
                //if (is_admin()) echo "+".$anchoimg."-".$altoimg."*"._DEF_DIRBASE.$TMP_foto."*";
                if ($altoimg>10 && $anchoimg>10) return $TMP_foto;
            }
        }
    }
    return "";
}
//////////////////////////////////////////////////////////////////////////////////
function iExplode($Delimiter, $String, $Limit = '') {
    $Explode = array();
    $LastIni = 0;
    $Count   = 1;
    if (is_numeric($Limit) == false) $Limit = '';
    while ( false !== ( $Ini = stripos($String, $Delimiter, $LastIni) ) && ($Count < $Limit || $Limit == '')) {
        $Explode[] = substr($String, $LastIni, $Ini-$LastIni);
        $LastIni = $Ini+strlen($Delimiter);
        $Count++;
    }
    $Explode[] = substr($String, $LastIni);
    return $Explode;
}
//function stripos($haystack, $needle){
//    return strpos($haystack, stristr( $haystack, $needle ));
//}

//////////////////////////////////////////////////////////////////////////////////
function googleAnalytics ($codAnalytics) {

    preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $_SERVER['SERVER_NAME'], $regs);
    $domainname=$regs['domain'];
    echo <<<EOD

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '$codAnalytics']);
  _gaq.push(['_setDomainName', '$domainname']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

EOD;
}
//////////////////////////////////////////////////////////////////////////////////
function RAD_sortFields($arr_fields_ordnumbers,$debug = false) {
// Reordena el array $findex de los modulos automaticos de RAD
//  Parámetros:
//  $arr_fields_ordnumbers: array de conjuntos clave => valor con los campos (claves) en el orden que se quiere (valores).
//  Ejemplo de uso:
//      prototype_RAD_sortFields(array("tema" => 2, "observaciones" => 3))

    global $fields, $findex;
    $arr_findex = $findex;
    $arr_fields = $fields;
    foreach($arr_fields_ordnumbers as $field => $ordnumber) {
        $aux=array();//Array claves
        $aux2=array();//Array valores
        $b = false;
        $c = false;
        foreach($arr_findex as $f => $o) {
            if($f == $field) {
                if($c == true) {
                    $c = false;
                } else {
                    $b = true; //Activamos una bandera
                }
            } else {
                if($o == $ordnumber) {
                    //Si la bandera está activada es que habíamos localizado previamente al campo en una posición anterior a la nueva
                    if($b == true) {
                        $aux[]=$f; $aux2[] = $o - 1;
                        $aux[]=$field; $aux2[] = $o;
                        $b = false;
                    } else {
                        $aux[]=$field; $aux2[] = $o;
                        $aux[]=$f; $aux2[] = $o + 1;
                        $c = true;
                    }
                } else { //Resto de los campos que no coinciden con el campo que se pretende ordenar
                    if($b == true) {
                        $aux[]=$f; //Claves
                        $aux2[] = $o - 1; //Valores
                    } elseif($c == true) {
                        $aux[]=$f; //Claves
                        $aux2[] = $o + 1; //Valores
                    } else {
                        $aux[]=$f; //Claves
                        $aux2[] = $o; //Valores
                    }
                }
            }
        }
        $arr_findex = array_combine($aux, $aux2);
        $numCampos = count($arr_findex);
        $fields_copy = $arr_fields;
        foreach($arr_findex as $f => $o) { // Ahora modificamos el array $fields
            $fieldname = $arr_fields[$o]->name; //Obtenemos el nombre del campo en el objeto correspondiente a la posición $o en el array $fieldname
            if($f == $fieldname) { //Si coinciden, metemos el objeto actual en el array copia.
                $fields_copy[$o] = $arr_fields[$o];
            } else { // En caso contrario, buscamos el elemento que corresponda en el array original y lo metemos en el array copia.
                //Buscamos $f en $arr_fields, y lo metemos en la posición que le corresponde.
                for($i=0;$i<$numCampos;$i++) {
                    if($arr_fields[$i]->name == $f) {
                        $fields_copy[$o] = $arr_fields[$i];
                        break; //Rompemos el bucle for después de hallar lo que buscábamos.
                    }
                }
            }
        }
        $arr_fields = $fields_copy;
    }
    $findex = $arr_findex;
    $fields = $arr_fields;
}
//////////////////////////////////////////////////////////////////////////////////
function RAD_replaceSubbrowse($TMP_content) {
    global $RAD_subbrowseCont;

    if (_DEF_URL_SUBBROWSE!=_DEF_URL && _DEF_URL_SUBBROWSE!="") $TMP_content=str_replace(_DEF_URL_SUBBROWSE,_DEF_URL,$TMP_content);
    $TMP_content = str_replace("delereg","delereg".$RAD_subbrowseCont."",$TMP_content);
    $TMP_content = str_replace("RAD_dosel","RAD_dosel".$RAD_subbrowseCont."",$TMP_content);
    $TMP_content = str_replace("'numform'","'numform".$RAD_subbrowseCont."'",$TMP_content);
    $TMP_content = str_replace("saveregs(","saveregs".$RAD_subbrowseCont."(",$TMP_content);
    $TMP_content = str_replace("document.F.","document.F".$RAD_subbrowseCont.".",$TMP_content);
    $TMP_content = str_replace("document.F[","document.F".$RAD_subbrowseCont."[",$TMP_content);
    $TMP_content = str_replace("document.forms.F.","document.forms.F".$RAD_subbrowseCont.".",$TMP_content);
    $TMP_content = str_replace("document.forms.F[","document.forms.F".$RAD_subbrowseCont."[",$TMP_content);
    $TMP_content = str_replace(" NAME=F "," NAME=F".$RAD_subbrowseCont." ",$TMP_content);
//  $TMP_content = str_replace("RAD_OpenW(","RAD_OpenW".$RAD_subbrowseCont."(",$TMP_content);
//  $TMP_content = str_replace("jsnull(","jsnull".$RAD_subbrowseCont."(",$TMP_content);
////    $TMP_content = str_replace("popUpCalendar(this,F","popUpCalendar(this,F".$RAD_subbrowseCont,$TMP_content);
    $TMP_content = str_replace("openW(","openW".$RAD_subbrowseCont."(",$TMP_content);
    $TMP_content = str_replace("setText(","setText".$RAD_subbrowseCont."(",$TMP_content);
    $TMP_content = str_replace("next(","next".$RAD_subbrowseCont."(",$TMP_content);
    $TMP_content = str_replace("sel(","sel".$RAD_subbrowseCont."(",$TMP_content);
    $TMP_content = str_replace("selm(","selm".$RAD_subbrowseCont."(",$TMP_content);
    $TMP_content = str_replace("delereg(","delereg".$RAD_subbrowseCont."(",$TMP_content);
    $TMP_content = str_replace("</body>","",$TMP_content);
    $TMP_content = str_replace("</BODY>","",$TMP_content);
    $TMP_content = str_replace("</html>","",$TMP_content);
    $TMP_content = str_replace("</HTML>","",$TMP_content);
//  $TMP_content = str_replace("</FORM","<input type=hidden name=subfunc value='browse'></FORM",$TMP_content);
//  $TMP_content = str_replace("<FORM","<FORM target=_blank",$TMP_content);
    $TMP_content = str_replace("HREF=\"","TARGET=_blank HREF=\"",$TMP_content);
    $TMP_content = str_replace("menuoff=x&orderby","menuoff=&subfunc=browse&orderby",$TMP_content);
    $TMP_content = str_replace("&subbrowse=x&menuoff=x","&subbrowse=x&menuoff=",$TMP_content);
    if (substr($TMP_content,0,30)=="Sorry, such file doesn't exist") return "\n<! ".$TMP_content." >\n";
    if (eregi("PDA",getSessionVar("SESSION_theme"))) $TMP_content=eregi_replace(" target=_blank","",$TMP_content);
    return $TMP_content;
}
//////////////////////////////////////////////////////////////////////////////////
function RAD_mime_content_type($filename) {
    $mime_types = array(
        'txt' => 'text/plain', 'htm' => 'text/html', 'html' => 'text/html',
        'php' => 'text/html', 'css' => 'text/css', 'js' => 'application/javascript',
        'json' => 'application/json', 'xml' => 'application/xml',
        'swf' => 'application/x-shockwave-flash', 'flv' => 'video/x-flv',
        'png' => 'image/png', 'jpe' => 'image/jpeg', 'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg', 'gif' => 'image/gif', 'bmp' => 'image/bmp',
        'ico' => 'image/vnd.microsoft.icon', 'tiff' => 'image/tiff',
        'tif' => 'image/tiff', 'svg' => 'image/svg+xml',
        'svgz' => 'image/svg+xml', 'zip' => 'application/zip',
        'rar' => 'application/x-rar-compressed', 'exe' => 'application/x-msdownload',
        'msi' => 'application/x-msdownload', 'cab' => 'application/vnd.ms-cab-compressed',
        'mp3' => 'audio/mpeg', 'qt' => 'video/quicktime', 'mov' => 'video/quicktime',
        'pdf' => 'application/pdf', 'psd' => 'image/vnd.adobe.photoshop',
        'ai' => 'application/postscript', 'eps' => 'application/postscript',
        'ps' => 'application/postscript', 'doc' => 'application/msword',
        'rtf' => 'application/rtf', 'xls' => 'application/vnd.ms-excel',
        'ppt' => 'application/vnd.ms-powerpoint', 'ppt' => 'application/vnd.ms-powerpoint',
        'odt' => 'application/vnd.oasis.opendocument.text',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
    );
    $ext = strtolower(array_pop(explode('.',$filename)));
    if (array_key_exists($ext, $mime_types)) {
        return $mime_types[$ext];
    } else if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME);
        $mimetype = finfo_file($finfo, $filename);
        finfo_close($finfo);
        return $mimetype;
    } else {
        return 'application/octet-stream';
    }
}
//////////////////////////////////////////////////////////////////////////////////
function RAD_special_html($str){
// Convierte solo los caracteres especiales sin modificar las etiquetas HTML
    $todas = get_html_translation_table(HTML_ENTITIES, ENT_NOQUOTES);
    $etiquetas = get_html_translation_table(HTML_SPECIALCHARS, ENT_NOQUOTES);
    $TMP_caracteres = array_diff($todas, $etiquetas);
    $str = strtr($str, $TMP_caracteres);
    return $str;
}

//////////////////////////////////////////////////////////////////////////////////
function RAD_txtMonth($mes) {
        switch ($mes) {
                case 1:
                case "01":
                        return _JANUARY;
                        break;
                case 2:
                case "02":
                        return _FEBRUARY;
                        break;
                case 3:
                case "03":
                        return _MARCH;
                        break;
                case 4:
                case "04":
                        return _APRIL;
                        break;
                case 5:
                case "05":
                        return _MAY;
                        break;
                case 6:
                case "06":
                        return _JUNE;
                        break;
                case 7:
                case "07":
                        return _JULY;
                        break;
                case 8:
                case "08":
                        return _AUGUST;
                        break;
                case 9:
                case "09":
                        return _SEPTEMBER;
                        break;
                case 10:
                        return _OCTOBER;
                        break;
                case 11:
                        return _NOVEMBER;
                        break;
                case 12:
                        return _DECEMBER;
                        break;
                default:
                        return "";
                        break;
        }

}

//////////////////////////////////////////////////////////////////////////////////
function RAD_isMobile() {
    if (preg_match('/(alcatel|amoi|android|avantgo|blackberry|benq|cell|cricket|docomo|elaine|htc|iemobile|iphone|ipad|ipaq|ipod|j2me|java|midp|mini|mmp|mobi|mobile|motorola|nec-|nokia|palm|panasonic|philips|phone|sagem|sharp|sie-|smartphone|sony|symbian|t-mobile|telus|up\.browser|up\.link|vodafone|wap|webos|wireless|xda|xoom|zte)/i', $_SERVER['HTTP_USER_AGENT'])) {
        return true;
    }else{
        return false;
    }

}

//////////////////////////////////////////////////////////////////////////////////
function RAD_ajaxRegisterFunction($TMP_func) {
	global $A_RAD_ajaxRegisteredFunctions;
	if (!is_array($A_RAD_ajaxRegisteredFunctions)) $A_RAD_ajaxRegisteredFunctions=array();
	array_push($A_RAD_ajaxRegisteredFunctions, $TMP_func);
}

//////////////////////////////////////////////////////////////////////////////////
function RAD_substr($TMP_string,$TMP_ini,$TMP_length) { // al cortar cadenas de caracteres evita cortar el 195 caracter UTF-8
	$TMP_string=html_entity_decode($TMP_string);
	if (!$TMP_length>0) $TMP_string=substr($TMP_string,$TMP_ini);
	else $TMP_string=substr($TMP_string,$TMP_ini,$TMP_length);
	if (ord(substr($TMP_string,strlen($TMP_string)-1))==195) $TMP_string=substr($TMP_string,0,strlen($TMP_string)-1);
	return $TMP_string;
}

//////////////////////////////////////////////////////////////////////////////////
function RAD_UTF_to_Unicode($input) {
/*
< \u003C
> \u003E
¿ \u0022
¿ \u0027
© \u00A9
¿ \u20AC
&nbsp; \u00A0
*/
	$A_x=array("&"=>"\u0022",":"=>"\u003A", "?"=>"\u003F", "¿"=>"\u00BF", "ª"=>"\u00AA", "º"=>"\u00BA", "Á"=>"\u00C1", "á"=>"\u00E1", "É"=>"\u00C9", "é"=>"\u00E9", "Í"=>"\u00CD", "í"=>"\u00ED", "Ó"=>"\u00D3", "ó"=>"\u00F3", "Ú"=>"\u00DA", "ú"=>"\u00FA", "Ü"=>"\u00DC", "ü"=>"\u00FC", "Ñ"=>"\u00D1", "ñ"=>"\u00F1" );
	foreach($A_x as $TMP_char=>$TMP_uni) {
		$input=str_replace($TMP_char, $TMP_uni, $input);
		$input=str_replace(utf8_decode($TMP_char), $TMP_uni, $input);
	}
	return $input;
}
?>
