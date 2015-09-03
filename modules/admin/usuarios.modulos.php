<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: ../index.php");
    die();
}
if ($func!="detail" || (!eregi("dulo",$V_lap)&&$V_lap!="Permisos"&&$V_lap!="Panel")) return "";

global $RAD_dbi, $SESSION_SID, $HTTP_SESSION_VARS, $show;
	$TMP_URL=$PHP_SELF."?V_dir=$V_dir&V_mod=$V_mod&func=$func&V_lap=$V_lap&par0=$par0".$SESSION_SID;
        $TMP_result="";
        if ($op=="nopermit") {
	    if (RAD_existTable(_DBT_MODSUSER)) {
                $cmdSQL="SELECT * FROM "._DBT_MODSUSER." WHERE "._DBF_MU_IDMOD."='$opidmod' AND "._DBF_MU_IDUSER."='$par0'";
                $TMP_result=sql_query($cmdSQL,$RAD_dbi);
                $TMP_row = sql_fetch_array($TMP_result, $RAD_dbi);
                if ($TMP_row[_DBF_MU_IDUSER]!="") {
                        $cmdSQL="DELETE FROM "._DBT_MODSUSER." WHERE "._DBF_MU_IDMOD."='$opidmod' AND "._DBF_MU_IDUSER."='$par0'";
                        RAD_printLog($cmdSQL);
                        sql_query($cmdSQL,$RAD_dbi);
                        $TMP_result="Permiso Borrado";
                } else {
            		$TMP_URL=$PHP_SELF."?".RAD_delParamURL($_SERVER[QUERY_STRING],"op");
                        alert("Este es un Permiso de Grupo. No puede ser borrado desde el Usuario");
                        echo "\n<script>\nwindow.location.href=\"$TMP_URL\";\n</script>\n";
                        die();
                }
	    } else {
		if (_DBF_M_MODULES!="_DBF_M_MODULES") {
            	    $cmdSQL="SELECT * FROM "._DBT_USERS." WHERE "._DBF_U_IDUSER."='$par0'";
            	    $TMP_result=sql_query($cmdSQL,$RAD_dbi);
            	    $TMP_row = sql_fetch_array($TMP_result, $RAD_dbi);
            	    if (ereg(",".$par0.",",$TMP_row[_DBF_U_MODULES])) {
            		$TMP=str_replace(",".$opidmod.",","",$TMP_row[_DBF_U_MODULES]);
                        $cmdSQL="UPDATE "._DBT_USERS." SET "._DBF_U_MODULES."='$TMP' WHERE "._DBF_M_IDUSER."='$par0'";
                        RAD_printLog($cmdSQL);
                        sql_query($cmdSQL,$RAD_dbi);
                        $TMP_result="Permiso Borrado";
            	    } else {
            		$TMP_URL=$PHP_SELF."?".RAD_delParamURL($_SERVER[QUERY_STRING],"op");
                        alert("Este es un Permiso de Grupo. No puede ser borrado desde el Usuario");
                        echo "\n<script>\nwindow.location.href=\"$TMP_URL\";\n</script>\n";
                        die();
		    }
                }
	    }
        }
        if ($op=="permit") {
	    if (RAD_existTable(_DBT_MODSUSER)) {
                $cmdSQL="SELECT * FROM "._DBT_MODSUSER." WHERE "._DBF_MU_IDMOD."='$opidmod' AND "._DBF_MU_IDUSER."='$par0'";
                $TMP_result=sql_query($cmdSQL,$RAD_dbi);
                $TMP_row = sql_fetch_array($TMP_result, $RAD_dbi);
                if ($TMP_row[_DBF_MU_IDUSER]=="") {
                        $cmdSQL="INSERT INTO "._DBT_MODSUSER." SET "._DBF_MU_IDMOD."='$opidmod', "._DBF_MU_IDUSER."='$par0'";
                        RAD_printLog($cmdSQL);
                        sql_query($cmdSQL,$RAD_dbi);
                        $TMP_result="Permiso Agregado";
                }
	    } else {
		if (_DBF_M_MODULES!="_DBF_M_MODULES") {
            	    $cmdSQL="SELECT * FROM "._DBT_USERS." WHERE "._DBF_U_IDUSER."='$par0'";
            	    $TMP_result=sql_query($cmdSQL,$RAD_dbi);
            	    $TMP_row = sql_fetch_array($TMP_result, $RAD_dbi);
            	    if (!ereg(",".$par0.",",$TMP_row[_DBF_M_MODULES])) {
            		$TMP=$TMP_row[_DBF_U_MODULES].",".$opidmod.",";
                        $cmdSQL="UPDATE "._DBT_USERS." SET "._DBF_U_MODULES."='$TMP' WHERE "._DBF_U_IDUSER."='$par0'";
                        RAD_printLog($cmdSQL);
                        sql_query($cmdSQL,$RAD_dbi);
                        $TMP_result="Permiso Agregado";
		    }
                }
	    }
        }
//        if ($TMP_result!="") {
        if ($op!="") {
                $TMP_URL=$PHP_SELF."?".RAD_delParamURL($_SERVER[QUERY_STRING],"op");
                echo "\n<script>\nwindow.status='$TMP_result';\nwindow.location.href=\"$TMP_URL\";\n</script>\n";
                die();
        }
        $TMP_mods=RAD_getUserProfile("",base64_decode($HTTP_SESSION_VARS["SESSION_user"]));
	setSessionVar("SESSION_idmods",$TMP_mods,0);
// Coge los permisos del usuario o grupo
	$TMP_PROFILE=RAD_getUserProfile("",$db->Record[_DBF_U_USER]);
//	$TMP_PROFILE=RAD_getUserProfile($par0,"");
 
	$TMP="<tr><td colspan=2> Muestra : ";
	$TMP.="\n<script>\nfunction change(status,opidmod) {\n document.location.href=document.location.href+'&op='+status+'&opidmod='+opidmod;\n}\n</script>\n";
	if ($show=="") $TMP.="<b><u>Todos</b></u> | <a href=\"$TMP_URL&show=P\">Permitidos</a> | <a href=\"$TMP_URL&show=N\">No Permitidos</a></td></tr>";
	if ($show=="N") $TMP.="<a href=\"$TMP_URL&show=\">Todos</a> | <a href=\"$TMP_URL&show=P\">Permitidos</a> | <b><u>No Permitidos</b></u></td></tr>";
	if ($show=="P") $TMP.="<a href=\"$TMP_URL&show=\">Todos</a> | <b><u>Permitidos</b></u> | <a href=\"$TMP_URL&show=N\">No Permitidos</a></td></tr>";
	$TMP.="<tr><td colspan=2><table class=browse><tr><th>Estado</th><th>Grupo</th><th>Item</th><th>Directorio</th><th>M&oacute;dulo</th></tr>";
	$TMP_result = sql_query("SELECT * FROM "._DBT_MODULES." ORDER BY "._DBF_M_GROUPMENU.","._DBF_M_WEIGHT.","._DBF_M_ITEMMENU, $RAD_dbi);
	while($TMP_row = sql_fetch_array($TMP_result, $RAD_dbi)) {
		if (ereg(",".$TMP_row[_DBF_M_IDMODULE].",",$TMP_PROFILE)) {
		  if ($TMP_row[_DBF_M_PUBLIC]!="1"&&_DBF_M_PUBLIC!="_DBF_M_PUBLIC") {
		    $estado="<font style='color:red;font-weight:bold;'>PERMITIDO</font>";
		    $estado="<font style='color:red;font-weight:bold;'>*</font><input type=checkbox name=opidmod value='".$TMP_row[_DBF_M_IDMODULE]."' checked onChange=\"javascript:change('nopermit',".$TMP_row[_DBF_M_IDMODULE].");\">";
		    $TMP_permitido=true;
		  } else {
		    $estado=" NO PERMITIDO";
		    $estado="<font style='color:navy;font-weight:bold;'>*</font><input type=checkbox name=opidmod value='".$TMP_row[_DBF_M_IDMODULE]."' onChange=\"javascript:change('permit',".$TMP_row[_DBF_M_IDMODULE].");\">";
		    $TMP_permitido=false;
		  }
		} else {
		  if ($TMP_row[_DBF_M_PUBLIC]=="1"&&_DBF_M_PUBLIC!="_DBF_M_PUBLIC") {
		    $estado="<font style='color:red;font-weight:bold;'>PERMITIDO</font>";
		    $estado="<font style='color:red;font-weight:bold;'>*</font><input type=checkbox name=opidmod value='".$TMP_row[_DBF_M_IDMODULE]."' checked onChange=\"javascript:change('nopermit',".$TMP_row[_DBF_M_IDMODULE].");\">";
		    $TMP_permitido=true;
		  } else {
		    $estado=" NO PERMITIDO";
		    $estado="<font style='color:navy;font-weight:bold;'>*</font><input type=checkbox name=opidmod value='".$TMP_row[_DBF_M_IDMODULE]."' onChange=\"javascript:change('permit',".$TMP_row[_DBF_M_IDMODULE].");\">";
		    $TMP_permitido=false;
		  }
		}
		if ($RAD_classrow == "class=row1") $RAD_classrow = "class=row2";
		else $RAD_classrow = "class=row1";
		if ($TMP_row[_DBF_M_ACTIVE]!="1" && _DBF_M_ACTIVE!="_DBF_M_ACTIVE") {
		    $estado.=" [INACTIVO] ";
//		    $TMP_permitido=false;
		}
		if ($TMP_row[_DBF_M_VISIBLE]!="1") $estado.=" [OCULTO] ";
		$TMP_URLmodulo="javascript:RAD_OpenW(\"$PHP_SELF?$SESSION_SID&V_dir=$V_dir&V_mod="._DBT_MODULES."&func=edit&subfunc=browse&headeroff=x&footeroff=x&par0=".$TMP_row[_DBF_M_IDMODULE]."\",800,600)'";
//		$estado="<a href='$TMP_URLmodulo'>"._DEF_NLSEdit."</a> ".$estado;
		if ($show=="" || ($show=="P"&&$TMP_permitido==true) || ($show=="N"&&$TMP_permitido==false)) 
		    $TMP.="<tr><td $RAD_classrow>".$estado."</td><td $RAD_classrow>".$TMP_row[_DBF_M_GROUPMENU]."</td><td $RAD_classrow>".$TMP_row[_DBF_M_ITEMMENU]."</td><td $RAD_classrow>".$TMP_row[_DBF_M_DIR]."</td><td $RAD_classrow><a href='$TMP_URLmodulo'><img src='images/edit.gif' border=0></a> ".$TMP_row[_DBF_M_FILE]."</td></tr>";
	}
	$TMP.="</table></td></tr>";

	return $TMP;
?>
