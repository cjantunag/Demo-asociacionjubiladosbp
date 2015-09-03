<?php

if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: ../../index.php");
    die();
}
//require_once("mainfile.php");
//include_once("functions.php");
$module_name = basename(dirname(__FILE__));
get_lang($module_name);
// RAD_ORACLE  $pass = strtolower($pass);
// RAD_ORACLE $uname = strtolower($uname);
$uname = trim($uname);
$uname = str_replace(" ","",$uname); // No se admiten espacios en el usuario (evita SQL_INJECTION)
$uname = str_replace("'","",$uname); // ni comilla simple
$uname = str_replace("\"","",$uname); // ni comilla doble
if ($numpass!="") $pass=${"pass_".$numpass};
if ($numpass!="") $passval=${"passval_".$numpass};
if ($_POST['empresa']) $empresa=$_POST['empresa'];
if ($pass_text!="" && $pass=="") $pass=$pass_text;

if ($V_op!="") $op=$V_op;
switch($op) {
    case "login":
	login($uname, $pass);
	break;
    case "reglogin":
	reglogin($table, $keyname, $keyval, $passname, $passval, $dir, $mod);
	break;
    case "getreglogin":
	getreglogin($table, $keyname, $passname, $dir, $mod);
	break;
    case "logout":
	logout();
	break;
    case "info":
	info();
	break;
    case "chgpass":
	chgpass();
	break;
    case "formchgpass":
	getchgpwd($uname,$caducada!='');
	break;
    case "chgtheme":
	chgtheme();
	break;
    case "none":
        break;
    default:
	info();
	break;
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////
function login($uname, $TMP_pwd) {
	global $RAD_newtheme, $SESSION_SID, $PHPSESSID, $remember, $RAD_dbi, $_REQUEST, $V_dir2, $V_idmod2, $V_nomd5, $pass, $uname, $empresa,$old_password,$new_password,$new_password2,$op2,$V_usuario2,$V_usuario2_name;

	$result=sql_query("select * from "._DBT_USERS." where "._DBF_U_USER."='$uname'", $RAD_dbi);
//	$result=sql_query("select * from "._DBT_USERS." where "._DBF_U_USER."='$uname' AND "._DBF_U_ACTIVE."!='0'", $RAD_dbi);
	$TMP_row=sql_fetch_array($result, $RAD_dbi);
	if (sql_num_rows($result, $RAD_dbi)!=1) {
		error(_INVALID_USER."(1)");
		die;
	}
	$pass=$TMP_row[_DBF_U_PASS];

	if($pass!=$TMP_pwd && $pass!=md5($TMP_pwd) && $pass!=md5($uname.$TMP_pwd) && crypt($TMP_pwd,$pass)!=$pass ) {
		error(_INVALID_USER."(2)");
		die;
	}
	
	if ($TMP_row[_DBF_U_OUTDATE]!='') {
		if (ereg("/",$TMP_row[_DBF_U_OUTDATE])) {
			$TMP_arr=explode("/",$TMP_row[_DBF_U_OUTDATE]);
			$TMP_str=$TMP_arr[2]."-".$TMP_arr[1]."-".$TMP_arr[0];
		}
		else $TMP_str=$TMP_row[_DBF_U_OUTDATE];
		$TMP_time=strtotime($TMP_str);
		if ($TMP_time>0) {
			if (time()>$TMP_time) {
				error(_INVALID_OUTOFDATEUSER);
				die;
			}
		}
	}

/*
	if (_DEF_MAX_USERFAILS>0 && _DEF_MAX_USERFAILS!="_DEF_MAX_USERFAILS") {
		if ($TMP_row[numfallos]>_DEF_MAX_USERFAILS) {
			error(_INVALID_USER."(12). Usuario Bloqueado");
			die;
		}
	}

	if(strtolower($pass)!=strtolower($TMP_pwd) && $pass!=md5($TMP_pwd) && $pass!=md5($uname.$TMP_pwd) && crypt($TMP_pwd,$pass)!=$pass ) {
		if (_DEF_MAX_USERFAILS>0 && _DEF_MAX_USERFAILS!="_DEF_MAX_USERFAILS") {
			if ($TMP_row[numfallos]=="") sql_query("update "._DBT_USERS." set numfallos='1' where "._DBF_U_USER."='$uname'", $RAD_dbi);
			else sql_query("update "._DBT_USERS." set numfallos=numfallos+1 where "._DBF_U_USER."='$uname'", $RAD_dbi);
		}
		error(_INVALID_USER."(2)");
		die;
	}
	if (_DEF_MAX_USERFAILS>0 && _DEF_MAX_USERFAILS!="_DEF_MAX_USERFAILS") {
		sql_query("update "._DBT_USERS." set numfallos='0' where "._DBF_U_USER."='$uname'", $RAD_dbi);
	}
*/

	$TMP_IP=getenv("REMOTE_ADDR");
	$TMP_CLIENT_IP=getenv("HTTP_CLIENT_IP");
	if (trim($TMP_row[IPdenegadas])!="") {
		$TMP_IPdenegadas=$TMP_row[IPdenegadas];
		$TMP_IPdenegadas=str_replace("*"," ",$TMP_IPdenegadas);
		$TMP_IPdenegadas=str_replace(","," ",$TMP_IPdenegadas);
		$TMP_IPdenegadas=str_replace("\n"," ",$TMP_IPdenegadas);
		$TMP_IPdenegadas=str_replace("\r"," ",$TMP_IPdenegadas);
		$A_IPdenegadas=explode(" ",$TMP_IPdenegadas." ");
		foreach($A_IPdenegadas as $idx=>$IPdenegada) {
			$IPdenegada=trim($IPdenegada); if ($IPdenegada=="") continue;
			if (substr($TMP_IP,0,strlen($IPdenegada))==$IPdenegada && $TMP_IP!="") {
				error("IP denegada");
				die;
			}
			if (substr($TMP_CLIENT_IP,0,strlen($IPdenegada))==$IPdenegada && $TMP_CLIENT_IP!="") {
				error("IP denegada");
				die;
			}
		}
	}
	if ($TMP_row[IPpermitidas]!="") {
		$TMP_permit=false;
		$TMP_IPpermitidas=$TMP_row[IPpermitidas];
		$TMP_IPpermitidas=str_replace("*"," ",$TMP_IPpermitidas);
		$TMP_IPpermitidas=str_replace(","," ",$TMP_IPpermitidas);
		$TMP_IPpermitidas=str_replace("\n"," ",$TMP_IPpermitidas);
		$TMP_IPpermitidas=str_replace("\r"," ",$TMP_IPpermitidas);
		$A_IPpermitidas=explode(" ",$TMP_IPpermitidas." ");
		foreach($A_IPpermitidas as $idx=>$IPpermitida) {
			$IPpermitida=trim($IPpermitida); if ($IPpermitida=="") continue;
			if (substr($TMP_IP,0,strlen($IPpermitida))==$IPpermitida && $TMP_IP!="") {
			    $TMP_permit=true;
			}
			if (substr($TMP_CLIENT_IP,0,strlen($IPpermitida))==$IPpermitida && $TMP_CLIENT_IP!="") {
			    $TMP_permit=true;
			}
		}
		if ($TMP_permit==false) {
			error("IP no permitida");
			die;
		}
	}

	if (_DEF_CHECK_USERCONNECTED=="1") {
		$TMP_past = time()-1800;
		$TMP_result = sql_query("SELECT * FROM "._DBT_STATS." WHERE "._DBF_S_USER."='$uname' AND "._DBF_S_ENDTIME.">'$TMP_past'", $RAD_dbi);
		$TMP_conectado=false;
		$TMP_IP=getenv("REMOTE_ADDR");
		$TMP_CLIENT_IP=getenv("HTTP_CLIENT_IP");
		while($TMP_who = sql_fetch_array($TMP_result, $RAD_dbi)) {
			if (ereg("-",$TMP_who[_DBF_S_SESSION])) continue;
			$TMP_SESSIONIP=$TMP_who[_DBF_S_IP];
			if ($TMP_SESSIONIP!="" && $TMP_SESSIONIP==$TMP_IP) continue;
			if ($TMP_SESSIONIP!="" && $TMP_SESSIONIP==$TMP_CLIENT_IP) continue;
			$TMP_conectado=true;
		}
		if ($TMP_conectado==true) {
			error("Usuario conectado en otro equipo. "._DBF_S_IP."=".$TMP_SESSIONIP);
			die;
		}
	}

	$TMP_language=$TMP_row[_DBF_U_LANGUAGE];
	if($TMP_row[_DBF_U_ADMIN]=="S") $TMP_row[_DBF_U_ADMIN]="1";
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
	if ($RAD_newtheme!="") $TMP_theme=$RAD_newtheme;
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

	if($pass == $TMP_pwd || $pass==md5($TMP_pwd) || $pass==md5($uname.$TMP_pwd) || crypt($TMP_pwd,$pass)==$pass ) {
		if(_DEF_SECURE_PASS=="1" && $pass!=md5($TMP_pwd) && $V_nomd5=="") sql_query("update "._DBT_USERS." set "._DBF_U_PASS."='".md5($TMP_pwd)."' where "._DBF_U_USER."='$uname'",$RAD_dbi);
		$TMP_b64_uname=base64_encode($uname);
		$TMP_b64_name=base64_encode($TMP_name);
		$TMP_b64_pass=base64_encode($TMP_pwd);
		
		// Buscar si tiene observaciones. 
		if ($TMP_row[_DBF_U_ALERTOBS]=='S') {
			if ($TMP_row[_DBF_U_OBSERVATIONS]!='') alert($TMP_row[_DBF_U_OBSERVATIONS]);
		}
		
		// Buscar si tiene la password forzada a cambiar:
		if ($TMP_row[_DBF_U_AUTOCHANGE]=='S') {
			echo "<script>window.location='".$PHP_SELF."?V_dir=coremods&V_mod=usercontrol&V_op=formchgpass&caducada=X&uname=$uname';</script>";
			die;
		}



		if ($TMP_row[_DBF_U_POP_PASSWORD]=="") $TMP_row[_DBF_U_POP_PASSWORD]=$TMP_pwd; // POP3 need clear password

		setSessionVar("SESSION_admin","$TMP_admin",0);
		setSessionVar("SESSION_user","$TMP_b64_uname",0);
		setSessionVar("SESSION_name","$TMP_b64_name",0);
		//setSessionVar("SESSION_pass","$TMP_b64_pass",0);
		setSessionVar("SESSION_profiles",$TMP_profiles,0);
		setSessionVar("SESSION_idmods",$TMP_mods,0);
		setSessionVar("SESSION_lang","$TMP_language",0);
		setSessionVar("SESSION_theme","$TMP_theme",0);
		setSessionVar("SESSION_popserver",$TMP_row[_DBF_U_POP_SERVER],0);
		setSessionVar("SESSION_smtpserver",$TMP_row[_DBF_U_SMTP_SERVER],0);
		setSessionVar("SESSION_popuser",$TMP_row[_DBF_U_POP_USER],0);
                setSessionVar("SESSION_poppassword",$TMP_row[_DBF_U_POP_PASSWORD],0);
                if ($empresa!="") setSessionVar("SESSION_empresa",$empresa,0);
		$TMP_cont=0;
		foreach ($TMP_row as $key=>$val) {
		    if ($key==$TMP_cont) {
			$TMP_cont++;
		    } else {
			if ($key!="clave" && $key!="usuario" && $key!="nombre") setSessionVar("SESSION_U_".$key,$val,0);
		    }
		}

		if ($V_usuario2!='') { // Usuarios web externos de libra
			$TMP_b64_usuario2=base64_encode($V_usuario2);
			setSessionVar("SESSION_user2",$TMP_b64_usuario2);
			setSessionVar("SESSION_name",base64_encode($V_usuario2_name));
		}

		if (_DEF_LEVEL_STAT!="0") {
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
//			    $TMP_numlastvisit=count($lastvisits)-2;
			    if ($TMP_numlastvisit!=-1) $lastvisit=$lastvisits[$TMP_numlastvisit];
			}
		}
		setSessionVar("SESSION_lastvisit",$lastvisit,0);
		$TMP_login=time();
		$TMP_loginDate=RAD_showDateTimeInt($TMP_login);
		setSessionVar("SESSION_timeLoginInit",$TMP_login,0);
		setSessionVar("SESSION_timeLoginInitDate",$TMP_loginDate,0);

		if ($remember!="") {
			setcookie("PHPSESSID", $PHPSESSID, time()+(60*60*24*365), dirname($PHP_SELF));
			setSessionVar("SESSION_remember",$remember,0);
		}

		unset($op);
		if (file_exists("modules/coremods/validauser.php")) {
			include_once("header.php");
			$TMP=include("modules/coremods/validauser.php");
			if ($TMP!="") echo $TMP;
			include_once("footer.php");
			exit;
		}

		if ($TMP_row[homepage]!="") {
			Header("Location: ".$TMP_row[homepage].$SESSION_SID);
			exit;
		}
		$TMP_homepage=homepageProfile($TMP_profiles);
		if (trim($V_dir2)!="" || trim($V_idmod2)!="") {
			$URL="";
			foreach ($_REQUEST as $TMP_key=>$TMP_val) {
        			if (!is_array($TMP_val) && $TMP_key!="V_dir" && $TMP_key!="V_mod" && $TMP_key!="V_idmod" && $TMP_key!="op" && 
				    substr($TMP_key,0,4)!="pass" && $TMP_key!="uname") {
                			if (get_magic_quotes_gpc()) $TMP_val = stripslashes ($TMP_val);
					if ($TMP_key=="V_dir2") $TMP_key="V_dir";
					if ($TMP_key=="V_idmod2") $TMP_key="V_idmod";
					if ($TMP_key=="V_mod2") $TMP_key="V_mod";
					$URL.=$TMP_key."=".urlencode($TMP_val)."&";
		        	}
			}
			Header("Location: index.php?".$URL);
		} else if ($TMP_homepage!="") {
			Header("Location: ".$TMP_homepage.$SESSION_SID);
		} else if (trim($lastvisit)!="" && _DEF_LASTVISIT=="1") {
			Header("Location: ".$PHP_SELF.trim($lastvisit).$SESSION_SID);
		} else {
			if (_DEF_DAYSCHANGEPASS!="" && _DEF_DAYSCHANGEPASS!="_DEF_DAYSCHANGEPASS") {
			    $TMP_f=_DBF_U_DATEPASS;
			    $TMP_dif=0;
			    if ($TMP_row[$TMP_f]!="--" && substr($TMP_row[$TMP_f],0,2)!="00" && $TMP_row[$TMP_f]!="") {
				$TMP_dif=floor((time()-strtotime($TMP_row[$TMP_f]))/(24*3600));
			    }
			    if ($TMP_row[$TMP_f]=="--" || substr($TMP_row[$TMP_f],0,2)=="00" || $TMP_row[$TMP_f]=="" || $TMP_dif>_DEF_DAYSCHANGEPASS) {
				alert("Han transcurrido mas de "._DEF_DAYSCHANGEPASS." dias desde su último cambio de clave.\n Deberia cambiar su clave en Mi Escritorio->Datos Usuario");
				echo "\n<script>document.location.href='index.php?".$SESSION_SID."&V_dir=coremods&V_mod=usercontrol&op=info&NO_CHG_THEME=x';\n</script>\n";
				die;
			    }
			}
			if ($SESSION_SID!="&") Header("Location: index.php?".$SESSION_SID);
			else Header("Location: index.php");
		}
	}
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////
function reglogin($table, $keyname, $keyval, $passname, $passval, $dir, $mod) {
	global $SESSION_SID, $PHPSESSID, $remember, $RAD_dbi, $func;

	if ($func=="") $TMP_func="edit";
	else $TMP_func=$func;
	$TMP_pwd = $pass;
	$cmdSQL="SELECT * FROM ".$table." WHERE $keyname='$keyval' AND $passname='$passval'";
	$result=sql_query($cmdSQL, $RAD_dbi);
	if (sql_num_rows($result, $RAD_dbi)!=1) {
		error(_INVALID_USER."(6)");
		die;
	}
	$TMP_row=sql_fetch_array($result, $RAD_dbi);

	if ($TMP_row[$keyname]!="") {
    	    setSessionVar("SESSION_REG_table",$table,0);
	    setSessionVar("SESSION_REG_keyname",$keyname,0);
	    setSessionVar("SESSION_REG_keyval",$keyval,0);
	    setSessionVar("SESSION_REG_passname",$passname,0);
	    setSessionVar("SESSION_REG_passval",$passval,0);
	    setSessionVar("SESSION_REG_V_dir",$dir,0);
	    setSessionVar("SESSION_REG_V_mod",$mod,0);

	    if ($remember!="") {
		setcookie("PHPSESSID", $PHPSESSID, time()+(60*60*24*365), dirname($PHP_SELF));
		setSessionVar("SESSION_remember",$remember,0);
	    }
	    unset($op);
	    if ($mod!="") {
		Header("Location: ".$PHP_SELF."?V_dir=$dir&V_mod=$mod&func=$TMP_func&par0=$keyval".$SESSION_SID);
		exit;
	    } else {
		if ($SESSION_SID!="&") Header("Location: index.php?".$SESSION_SID);
		else Header("Location: index.php");
	    }
	} else {
		error(_INVALID_USER."(7)");
		die;
	}
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////
function logout() {
    global $RAD_dbi, $PHPSESSID, $HTTP_SESSION_VARS, $uname, $usession;

	$TMP_user2=base64_decode(getSessionVar("SESSION_user2"));

	if ($uname!="" && $usession!="" && is_admin()) {
		$TMP_result=session_id($usession);
		session_start($usession);
		$TMP_result=session_id($usession);
	}

	$TMP_cfg = $HTTP_SESSION_VARS["SESSION_cfg"];
	setSessionVar("SESSION_user","",0);
	setSessionVar("SESSION_name","",0);
	setSessionVar("SESSION_admin","",0);
	setSessionVar("SESSION_profiles","",0);
	setSessionVar("SESSION_lang","",0);
	setSessionVar("SESSION_theme","",0);

	$TMP_session=session_id();
	$TMP_session_null=$TMP_session."-".time();
	// RAD_ORACLE  sql_query("update "._DBT_STATS." set "._DBF_S_SESSION."='".$TMP_session_null."' where "._DBF_S_SESSION."='$TMP_session'",$RAD_dbi);
	
	session_destroy();

 	$TMP_url="";
 	if ($TMP_cfg!="") $TMP_url.="&RAD_cfg=".$TMP_cfg;
 	if ($RAD_device!="") $TMP_url.="&RAD_device=".$RAD_device;
 	if ($RAD_newtheme!="") $TMP_url.="&RAD_newtheme=".$RAD_newtheme;

	if ($TMP_user2!='') Header("Location: index.php?V_dir=FUN&V_mod=LOGIN_PORTAL_ALUMNO");
	else if ($TMP_url!="") Header("Location: index.php?".substr($TMP_url,1));
        else if (_DEF_dbtype=="Oracle") Header("Location: index.php");

	$PHPSESSID="";
	include("header.php");
	OpenTable();
	echo "<center><b>"._YOUARELOGGEDOUT."</b></center>";
	CloseTable();
	include("footer.php");
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////
function info() {
    global $RAD_dbi, $PHPSESSID, $HTTP_SESSION_VARS, $FORMROI, $NO_CHG_THEME;
	$TMP_user = base64_decode($HTTP_SESSION_VARS["SESSION_user"]);
	$result=sql_query("select * from "._DBT_USERS." where "._DBF_U_USER."='$TMP_user'", $RAD_dbi);
	$TMP_row=sql_fetch_array($result, $RAD_dbi);
	if ($TMP_row[_DBF_U_USER]=="") {
		error(_INVALID_USER."(3)");
		die;
	}

	$pass=$TMP_row[_DBF_U_PASS];
	$TMP_language=$TMP_row[_DBF_U_LANGUAGE];
	if($TMP_row[_DBF_U_ADMIN]=="S") $TMP_row[_DBF_U_ADMIN]="1";
	$TMP_admin=$TMP_row[_DBF_U_ADMIN];
	$TMP_name=$TMP_row[_DBF_U_NAME];
        $TMP_urldirecta=$PHP_SELF."?V_dir=coremods&V_mod=usercontrol&uname=".$TMP_row[_DBF_U_USER]."&V_op=login&V_nomd5=x&pass=".$TMP_row[_DBF_U_PASS];
	$TMP_profiles=$TMP_row[_DBF_U_PROFILES];

	include("header.php");
	OpenTable();
	echo "<b>Datos de Usuario</b></td><td class=borde>&nbsp;</td></tr><tr><td align=right>"._UREALNAME." : </td><td> ".$TMP_row[_DBF_U_NAME]."</td></tr>";
	echo "<tr><td align=right>"._UUSERNAME." : </td><td>&nbsp;".$TMP_row[_DBF_U_USER]."</td></tr>";
	echo "<tr><td align=right>"._EMAIL." : </td><td>&nbsp;".$TMP_row[_DBF_U_EMAIL]."</td></tr>";
//	echo "<tr><td align=right>Idioma : </td><td>&nbsp;".$TMP_row[_DBF_U_LANGUAGE]."</td></tr>";
	echo "<tr><td align=right><a href='".$TMP_urldirecta."'> URL de Acceso Directo </a></td><td> (puede agregarla a su Libreta de Marcadores)</td></tr>";
//	echo "<tr><td colspan=2>"._DEF_URL.$TMP_urldirecta."</td></tr>";
	echo "<tr><td align=right>Tel&eacute;fono : </td><td>&nbsp;".$TMP_row[_DBF_U_PHONE]."</td></tr>";
	$TMP_prof=RAD_showfield("plistdbm",_DBT_PROFILES.":"._DBF_P_IDPROFILE.":"._DBF_P_PROFILE,$TMP_row[_DBF_U_PROFILES]);
//	$TMP_prof=str_replace("\n", "<br>\n", $TMP_prof);
	echo "<tr><td align=right valign=top>Permisos : </td><td>".$TMP_prof."&nbsp;</td></tr>";
  if ($NO_CHG_THEME=="") {
	echo "<tr><td class=borde colspan=2 align=left><b>Cambio de Apariencia</b></td></tr>";
	echo "<form action=\"index.php\" method=\"post\">\n<input type=hidden name=PHPSESSID value='$PHPSESSID'>\n";
	echo $FORMROI."<input type=hidden name=V_dir value='coremods'><input type=hidden name=V_mod value='usercontrol'>\n";

	$f = opendir(_DEF_DIRBASE."themes/");
	$fn = readdir($f);
	$TMP_themes="<option value=''></option>";
	$ki=0;
	while ($fn) {
		$fn = readdir($f);
		if (ereg("\.",$fn)) continue;
		if ($fn =="") continue;
		if (!is_dir(_DEF_DIRBASE."themes/".$fn)) continue;
		if($fn==$TMP_row[_DBF_U_THEME]) $TMP_selected=" SELECTED";
		else $TMP_selected="";
		$TMP_option="<option value='$fn'".$TMP_selected."> ".$fn." </option>";
		$DIRS[$fn]=$TMP_option;
		$ki++;
	}
	sort($DIRS);
	for ($ki=0; $ki<count($DIRS); $ki++) {
	    $TMP_themes.=$DIRS[$ki];
	}

	echo "<tr><td align=right> Aspecto : </td><td>";
	if ($TMP_themes=="") echo "<input type=text name=DEF_THEME value='"._DEF_THEME."'></td></tr>\n";
	else echo "<select name=newtheme>".$TMP_themes."</select></td></tr>\n";

	echo "<tr><td align=right>Idioma :</td><td>";
	$TMP_lang=RAD_editfield("newlanguage", "plist", 1, 1, "spanish:Español,galego:Galego,english:Inglés", "", true, $TMP_row[_DBF_U_LANGUAGE], "");
	echo $TMP_lang."</td></tr>\n";

	echo "<tr><td align=center colspan=2><input type=\"submit\" value=\"Cambia Apariencia\"><input type=hidden name=V_op value=\"chgtheme\"></form></p>\n";
  }
  if ($CHG_EMAIL=="") {
	echo "<tr><td class=borde colspan=2 align=left><b>Cambio de Email</b></td></tr>";
	echo "<tr><td align=right><form action=\"index.php\" method=\"post\">\n<input type=hidden name=PHPSESSID value='$PHPSESSID'>\n";
	echo $FORMROI."<input type=hidden name=V_dir value='coremods'><input type=hidden name=V_mod value='usercontrol'>\n";
	echo "Email : </td><td><input type=\"text\" name=\"email\" size=\"30\" maxlength=\"125\" value='".$TMP_row[email]."'></td></tr>\n";
	echo "<tr><td align=center colspan=2><input type=\"submit\" value=\"Cambia Email\"><input type=hidden name=V_op value=\"chgemail\"></form></p>\n";
  }
	echo "<tr><td class=borde colspan=2 align=left><b>Cambio de Clave</b></td></tr>";
	echo "<tr><td align=right><form action=\"index.php\" method=\"post\">\n<input type=hidden name=PHPSESSID value='$PHPSESSID'>\n";
	echo $FORMROI."<input type=hidden name=V_dir value='coremods'><input type=hidden name=V_mod value='usercontrol'>\n";
	echo "Clave Antigua :</td><td><input type=\"password\" name=\"oldpass\" size=\"6\" maxlength=\"25\"></td></tr>\n";
	echo "<tr><td align=right>Clave Nueva :</td><td><input type=\"password\" name=\"newpass\" size=\"6\" maxlength=\"25\"></td></tr>\n";
	echo "<tr><td align=right>Clave Nueva (repetida):</td><td><input type=\"password\" name=\"newpass2\" size=\"6\" maxlength=\"25\"></td></tr>\n";
	echo "<tr><td align=center colspan=2><input type=\"submit\" value=\"Cambia Clave\"><input type=hidden name=V_op value=\"chgpass\"></form></p>\n";

	CloseTable();
	include("footer.php");
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////
function chgpass() {
    global $RAD_dbi, $PHPSESSID, $newpass, $newpass2, $oldpass, $HTTP_SESSION_VARS,$uname;

	$TMP_session=session_id();
	
	if (MENU_PARAM!="" && MENU_PARAM!="MENU_PARAM") $MINLENGTHPWD=RAD_lookup('MENU_PARAM','LONGITUD_MIN_PASSWORD','ID',1);
        if (_DEF_MINLENGTHPWD && $MINLENGTHPWD=="") $MINLENGTHPWD=_DEF_MINLENGTHPWD;
        elseif ($MINLENGTHPWD=="") $MINLENGTHPWD=8;

	if ($newpass!=$newpass2) {
		error(_DEF_PASS_DONT_MATCH);
		die;
	}
	if ($newpass=="" || strlen($newpass)<$MINLENGTHPWD) {
		error(_DEF_PASS_TOO_SHORT);
		die;
	}
	if (ereg(" ",$newpass)) {
		error(_DEF_PASS_NO_BLANK);
		die;
	}
	$newpass=trim($newpass); $newpass2=trim($newpass2); $oldpass=trim($oldpass);
	if ($oldpass==$newpass) {
		error(_DEF_PASS_DIFFERENT);
		die;
	}
	$TMP_pwd = $oldpass;

	$TMP_user = base64_decode($HTTP_SESSION_VARS["SESSION_user"]);
	if ($TMP_user=="") $TMP_user=$uname;
	
	$TMP_cmd="select * from "._DBT_USERS." where "._DBF_U_USER."='$TMP_user'";
	$result=sql_query($TMP_cmd, $RAD_dbi);
	if (!$TMP_row=sql_fetch_array($result, $RAD_dbi)) {
		error(_INVALID_USER);
		die;
	}
	$pass=$TMP_row[_DBF_U_PASS];
	$TMP_observaciones=$TMP_row[observaciones];
	$TMP_oldpass=explode("\n",$TMP_observaciones."\n");
	for ($k=0; $k<count($TMP_oldpass); $k++) {
		if($TMP_oldpass[$k]==$newpass || $TMP_oldpass[$k]==md5($newpass) || crypt($newpass,$TMP_oldpass[$k])==$TMP_oldpass[$ki]) {
			error("No se permite repetir Claves Antiguas");
			die;
		}
	}
	$TMP_observaciones.="\n".md5($newpass);
	if($pass == $TMP_pwd || $pass==md5($TMP_pwd) || $pass==md5($uname.$TMP_pwd) || crypt($TMP_pwd,$pass)==$pass ) {
		if ($pass==md5($uname.$TMP_pwd) ) {
			$newpass=$uname.$newpass;
		}
		if (_DBF_U_AUTOCHANGE!="" && _DBF_U_AUTOCHANGE!="_DBF_U_AUTOCHANGE") $cmdSQL="update "._DBT_USERS." set "._DBF_U_PASS."='".md5($newpass)."', "._DBF_U_AUTOCHANGE."='N' where "._DBF_U_USER."='$TMP_user'";
                else $cmdSQL="update "._DBT_USERS." set "._DBF_U_PASS."='".md5($newpass)."' where "._DBF_U_USER."='$TMP_user'";
		RAD_printLog($cmdSQL);
		sql_query($cmdSQL,$RAD_dbi);
		
		$cmdSQL="update "._DBT_USERS." set "._DBF_U_DATEPASS."='".date("Y-m-d")."' where "._DBF_U_USER."='$TMP_user'";
		RAD_printLog($cmdSQL);
		sql_query($cmdSQL,$RAD_dbi);
		
		session_destroy();
		//setSessionVar("SESSION_pass",base64_encode($newpass),0);
		include("header.php");
		OpenTable();
		echo "<br><br><center><span style='font-weight:bold;color:red;font-size:1.4em;'>"._PASSWORDCHANGED."</span><br><br>"._GOHOME."</center>";
		CloseTable();
		include("footer.php");
	} else {
		error(_DEF_PASS_OLD_NOT_VALID);
		die;
	}
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////
function chgtheme() {
    global $RAD_dbi, $PHPSESSID, $newtheme, $newlanguage, $HTTP_SESSION_VARS;

	$TMP_session=session_id();

	$TMP_user = base64_decode($HTTP_SESSION_VARS["SESSION_user"]);
	$result=sql_query("select * from "._DBT_USERS." where "._DBF_U_USER."='$TMP_user'", $RAD_dbi);
	if (sql_num_rows($result, $RAD_dbi)!=1) {
		error(_INVALID_USER);
		die;
	}

	$cmdSQL="update "._DBT_USERS." set "._DBF_U_THEME."='".$newtheme."', "._DBF_U_LANGUAGE."='$newlanguage' where "._DBF_U_USER."='$TMP_user'";
	RAD_printLog($cmdSQL);
	sql_query($cmdSQL,$RAD_dbi);
	setSessionVar("SESSION_lang","$newlanguage",0);
	setSessionVar("SESSION_theme","$newtheme",0);

	include("header.php");
	OpenTable();
	echo "<center><b><br>Apariencia Cambiada</b></center><br>";

	CloseTable();
	include("footer.php");
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////
function chgemail() {
    global $RAD_dbi, $PHPSESSID, $email, $HTTP_SESSION_VARS;

	$TMP_session=session_id();

	$TMP_user = base64_decode($HTTP_SESSION_VARS["SESSION_user"]);
	$result=sql_query("select * from "._DBT_USERS." where "._DBF_U_USER."='$TMP_user'", $RAD_dbi);
	$TMP_row=sql_fetch_array($result, $RAD_dbi);
	if ($TMP_row[_DBF_U_USER]=="") {
		error(_INVALID_USER."(5)");
		die;
	}

	$cmdSQL="update "._DBT_USERS." set email=".converttosql($email)." where "._DBF_U_USER."='$TMP_user'";
	RAD_printLog($cmdSQL);
	sql_query($cmdSQL,$RAD_dbi);
	
	include("header.php");
	OpenTable();
	echo "<center><b><br>Email Cambiado</b></center><br>";

	CloseTable();
	include("footer.php");
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////
function homepageProfile($TMP_profiles) {
	global $RAD_dbi;
	if ($TMP_profiles=="") return "";
	$TMP_profile = explode(",", $TMP_profiles);
	for ($ki = 0; $ki < count($TMP_profile); $ki++) {
		if ($TMP_profile[$ki]!="") {
			$result=sql_query("select * from "._DBT_PROFILES." where "._DBF_P_IDPROFILE."='".$TMP_profile[$ki]."'", $RAD_dbi);
			$TMP_row=sql_fetch_array($result, $RAD_dbi);
			if ($TMP_row[_DBF_P_HOME]!="") return $TMP_row[_DBF_P_HOME];
		}
	}
	return "";
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////
function getchgpwd($uname='',$caducada=false) {
global $PHPSESSID, $func;

$numpass=uniqid("");
	if ($uname=='') $uname=base64_decode(getSessionVar("SESSION_user"));
	include("header.php");
	OpenTable();
	if ($caducada) $content = "<p style='color:red;font-weight:bold;font-size:1.4em;' align='center'><br><br>"._NEEDNEWPASS."</p>";
	else $content = "<p style='color:red;font-weight:bold;font-size:1.4em;' align='center'><br><br>"._NEEDNEWPASS2."</p>";
	$content .= "<center><br><form action='$TMP_action' method='post'>\n<input type=hidden name=PHPSESSID value='$PHPSESSID'>\n";
	$content .= "<input type=hidden name=V_dir value='coremods'><input type=hidden name=V_mod value='usercontrol'>\n";
	$content .= "<input type=hidden name=V_op value='chgpass'><input type=hidden name=uname value='$uname'>\n";
	$content .= "<table cellpadding=0 cellspacing=1 border=0><tr><td align=right>";
	$content .= _OLDPASSWORD.":</td><td><input type='password' name='oldpass' size='12' maxlength='20'></td></tr><tr><td align=right>";
	$content .= _NEWPASSWORD.":</td><td><input type='password' name='newpass' size='12' maxlength='20'></td></tr><tr><td align=right>";
	$content .= _NEWPASSWORD2.":</td><td><input type='password' name='newpass2' size='12' maxlength='20'></td></tr><tr><td align=right>";
	$content .= "<br><br></td></tr><tr><td colspan=2 align=center>";
	$content .= " <input type='submit' value='"._DEF_NLSSave."'>";
	$content .= " </td></tr></table></form><br></center>";
	$content;
	echo $content;

	CloseTable();
	include("footer.php");
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////
function getreglogin($table, $keyname, $passname, $dir, $mod) {
global $PHPSESSID, $func;

$numpass=uniqid("");

	include("header.php");
	OpenTable();
	$content = "<center><br><form action='$TMP_action' method='post'>\n<input type=hidden name=PHPSESSID value='$PHPSESSID'>\n";
	$content .= "<input type=hidden name=V_dir value='coremods'><input type=hidden name=V_mod value='usercontrol'>\n";
	$content .= "<input type=hidden name=dir value='$dir'><input type=hidden name=mod value='$mod'>\n";
	$content .= "<input type=hidden name=table value='$table'><input type=hidden name=keyname value='$keyname'>\n";
	$content .= "<input type=hidden name=func value='$func'>\n";
	$content .= "<input type=hidden name=passname value='$passname'>\n";
	$content .= "<input type=hidden name=numpass value='".$numpass."'>\n";
	$content .= "<table cellpadding=0 cellspacing=1 border=0><tr><td align=right>";
	$content .= _NICKNAME.":</td><td><input type='text' name='keyval' size='12' maxlength='25'></td></tr><tr><td align=right>";
	$content .= _PASSWORD.":</td><td><input type='password' name='passval_".$numpass."' size='12' maxlength='20'>";
	$content .= "<input type=hidden name=V_op value='reglogin'></td></tr><tr><td colspan=2 align=center>";
	$content .= " <input type='submit' value='"._LOGIN."'>";
	$content .= " <input type=checkbox name=remember> <img src='images/info.gif' alt='"._REMEMBERLOGIN."' title='"._REMEMBERLOGIN."'> ";
	$content .= " </td></tr></table></form><br></center>";
	$content;
	echo $content;

	CloseTable();
	include("footer.php");
}
?>
