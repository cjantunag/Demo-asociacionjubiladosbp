<?php
if ($PHP_SELF=="") $PHP_SELF=getenv("SCRIPT_NAME");
if (eregi(basename(__FILE__), $PHP_SELF)) {
	include_once("mainfile.php");
	include_once("functions.php");
//	Header("Location: index.php");
//	die();
}

if ($numpass!="") $pass=${"pass_".$numpass};
$numpass=uniqid("");
global $PHPSESSID;

    if ($uname!="") {
	checkLogin();
	return;
    }

    if (eregi(basename(__FILE__), $PHP_SELF)) {
        $content .= "<html><head><title>Entrada de Usuario</title>\n";
        $content .= "<STYLE TYPE='TEXT/CSS'>\nBODY, TD, TH, INPUT {font-family:Verdana,Arial,Helvetica; font-size:12px;}\n</STYLE>\n</head>\n";
        $content .= "\n<body onBlur='window.focus()' bgcolor=white><br><br><br><br><br><br>\n";
    }
    $content .= "<form autocomplete=off name=LOGIN action='index.php' method='post'>\n<input type=hidden name=PHPSESSID value='$PHPSESSID'>\n";
    foreach ($_REQUEST as $TMP_key=>$TMP_val) {
	if (!is_array($TMP_val)) {
	    if (get_magic_quotes_gpc()) $TMP_val = stripslashes ($TMP_val);
	    if ($TMP_key=="V_dir") $TMP_key="V_dir2";
	    if ($TMP_key=="V_idmod") $TMP_key="V_idmod2";
	    if ($TMP_key=="V_mod") $TMP_key="V_mod2";
	    $content .="<input type=hidden name='$TMP_key' value='".urlencode($TMP_val)."'>\n";
	}
    }
    $content .= "<input type=hidden name=V_dir value='coremods'><input type=hidden name=V_mod value='usercontrol'>\n";
    $content .= "<input type=hidden name=passname value='$passname'>\n";
    $content .= "<input type=hidden name=numpass value='".$numpass."'>\n";
    $content .= "<center><table width=80% class=detail><tr><td align=right class=detailtit>";
    $content .= _NICKNAME.":</td><td class=detail><input type='text' name='uname' size='6' maxlength='25'></td></tr><tr><td align=right class=detailtit>";
    $content .= _PASSWORD.":</td><td class=detail><input type='password' name='pass_".$numpass."' size='6' maxlength='20'>";
    $content .= "<input type=hidden name=V_op value='login'></td></tr><tr><td colspan=2 align=center class=detail>";
    $content .= " <input type='submit' value='"._LOGIN."'>";
    $content .= " </td></tr></table></center></form>";
    $content .= "\n<script>\ndocument.LOGIN.uname.focus();\n</script>\n";
    if (eregi(basename(__FILE__), $PHP_SELF)) {
	$content .= " </body>\n</html>\n";
	echo $content;
    } else return $content;
/////////////////////////////////////////////////////////////////////////////////////////////////////
function checkLogin() {
global $SESSION_SID, $PHPSESSID, $remember, $RAD_dbi, $_REQUEST, $V_dir2, $V_idmod2, $uname, $pass, $empresa;

	$TMP_pwd = $pass;

	$result=sql_query("select * from "._DBT_USERS." where "._DBF_U_USER."='$uname' AND "._DBF_U_ACTIVE."!='0'", $RAD_dbi);
	if (sql_num_rows($result, $RAD_dbi)!=1) {
		echo "&result=Fail&errorMsg="._INVALID_USER;
		return;
	}
	$TMP_row=sql_fetch_array($result, $RAD_dbi);
	$pass=$TMP_row[_DBF_U_PASS];
	$TMP_language=$TMP_row[_DBF_U_LANGUAGE];
	$TMP_admin=$TMP_row[_DBF_U_ADMIN];
	$TMP_name=$TMP_row[_DBF_U_NAME];
	$TMP_profiles=$TMP_row[_DBF_U_PROFILES];
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
	if (trim($TMP_row[_DBF_U_THEME])!="") $TMP_theme=$TMP_row[_DBF_U_THEME];
	else $TMP_theme=getSessionVar("SESSION_theme");
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
	if($pass == $TMP_pwd || $pass==md5($TMP_pwd) || crypt($TMP_pwd,$pass)==$pass ) {
		// POP3 need clear password
		if($pass != $TMP_pwd) sql_query("update "._DBT_USERS." set "._DBF_U_PASS."='".$TMP_pwd."' where "._DBF_U_USER."='$uname'",$RAD_dbi);
		$TMP_b64_uname=base64_encode($uname);
		$TMP_b64_name=base64_encode($TMP_name);
		$TMP_b64_pass=base64_encode($TMP_pwd);

		setSessionVar("SESSION_admin","$TMP_admin",0);
		setSessionVar("SESSION_user","$TMP_b64_uname",0);
		setSessionVar("SESSION_name","$TMP_b64_name",0);
		setSessionVar("SESSION_pass","$TMP_b64_pass",0);
		setSessionVar("SESSION_profiles",$TMP_profiles,0);
		setSessionVar("SESSION_idmods",$TMP_mods,0);
		setSessionVar("SESSION_lang","$TMP_language",0);
		setSessionVar("SESSION_theme","$TMP_theme",0);
		setSessionVar("SESSION_popserver",$TMP_row[_DBF_U_POP_SERVER],0);
		setSessionVar("SESSION_smtpserver",$TMP_row[_DBF_U_SMTP_SERVER],0);
		setSessionVar("SESSION_popuser",$TMP_row[_DBF_U_POP_USER],0);
		setSessionVar("SESSION_poppassword",base64_encode($TMP_row[_DBF_U_POP_PASSWORD]),0);
		if ($empresa!="") setSessionVar("SESSION_empresa",$empresa,0);
		$TMP_cont=0;
		foreach ($TMP_row as $key=>$val) {
			if ($key==$TMP_cont) {
				$TMP_cont++;
			} else {
				if ($key!="clave" && $key!="usuario" && $key!="nombre") setSessionVar("SESSION_U_".$key,$val,0);
			}
		}
		$TMP_result = sql_query("SELECT * FROM "._DBT_STATS." WHERE "._DBF_S_USER."='$uname' ORDER BY "._DBF_S_ENDTIME." DESC", $RAD_dbi);
		$TMP_stat = sql_fetch_array($TMP_result, $RAD_dbi);
		setSessionVar("SESSION_lastsessiontimeint",$TMP_stat[tiempofin],0);
		$TMP_tiempofin=RAD_showDateTimeInt($TMP_stat[tiempofin]);
		setSessionVar("SESSION_lastsessiontime",$TMP_tiempofin,0);
		$lastvisits=explode("\n",$TMP_stat[urlsvisitadas]);
		$lastvisit="";
	        if (count($lastvisits)>0) {
			$TMP_numlastvisit=-1;
			for($ki=count($lastvisits); $ki>0; $ki--) {
				if ( ((ereg("V_dir=",$lastvisits[$ki]) && ereg("V_mod=",$lastvisits[$ki])) || ereg("V_idmod=",$lastvisits[$ki]))
				  && (ereg("&func=det",$lastvisits[$ki])||ereg("&func=br",$lastvisits[$ki])||ereg("&func=&",$lastvisits[$ki])) ) {
					$TMP_numlastvisit=$ki;
					$ki=0;
				}
			}
//			$TMP_numlastvisit=count($lastvisits)-2;
			if ($TMP_numlastvisit!=-1) $lastvisit=$lastvisits[$TMP_numlastvisit];
		}
		setSessionVar("SESSION_lastvisit",$lastvisit,0);
		$TMP_login=time();
		$TMP_loginDate=RAD_showDateTimeInt($TMP_login);
		setSessionVar("SESSION_timeLoginInit",$TMP_login,0);
		setSessionVar("SESSION_timeLoginInitDate",$TMP_loginDate,0);

		if ($remember!="") {
			setcookie("PHPSESSID", $PHPSESSID, time()+(60*60*24*365),$GLOBALS["SERVER_PORT"].dirname($PHP_SELF));
			setSessionVar("SESSION_remember",$remember,0);
		}

		echo "&result=Okay&message=Bienvenido";
		return;
	} else {
		echo "&result=Fail&errorMsg="._INVALID_USER;
		return;
	}
}
?>
