<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}
if ($func=="delete" && $menudelete == false) {
    $func="error";
}
//---------------------------------------------------------------------------
//------------------------- Delete
//---------------------------------------------------------------------------
if ($func=="delete") {
  for ($i = 0; $i < $numf; $i++) global ${"par$i"};
  if ($V_numrows=="") $TMP_V_numrows=1;
  else $TMP_V_numrows=$V_numrows;
  for ($ki = 0; $ki < $TMP_V_numrows; $ki++) {
        if ($V_numrows>0) {
    	    if (${"V".$ki."_delete"}!="") {
		for ($i = 0; $i < $numf; $i++) {
	    	    global ${"par$i"};
	    	    ${"par$i"}=${"V".$ki."_par$i"};
		}
		$TMP_result=RAD_checkAutSQLDelete();
		if ($TMP_result!="") {
			alert("No se puede borrar registro $ki por existir las siguientes relaciones: ".$TMP_result."");
			continue;
		}
	    } else continue;
	} else {
		$TMP_result=RAD_checkAutSQLDelete();
		if ($TMP_result!="") {
			alert("No se puede borrar este registro por existir las siguientes relaciones: ".$TMP_result."");
			continue;
		}
	}
	$idnames = "";
	for ($i = 0; $i < $numf; $i++) {
		if (${"idname$i"} != "") {
			if ($idnames == "") $idnames = "${"idname$i"} = '" . ${"par$i"} . "'";
			else $idnames .= " AND ${"idname$i"} = '" . ${"par$i"} . "'";
		}
	}
	$cmdSQL="SELECT * FROM $tablename WHERE $idnames";
	$TMP_result=sql_query($cmdSQL, $RAD_dbi);
	if ($TMP_result) {
		$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
	} else {
		$func = "error";
		$RAD_errorstr .= $cmdSQL." [".sql_errorno($TMP_result)."] ".sql_error($TMP_result);
	}

	if (function_exists("shmop_open")) {
		$TMP_user = base64_decode($HTTP_SESSION_VARS["SESSION_user"]);

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
		if ($TMP_shm_id) {
			$TMP_time2=0;
			$TMP_shm_dataread = shmop_read($TMP_shm_id, 0, 100);
			list($TMP_PHPSESSID2,$TMP_user2,$TMP_time2)=explode(".",$TMP_shm_dataread);
			if ($TMP_time2>0) if ($TMP_PHPSESSID2==$PHPSESSID && $TMP_user2==$TMP_user) shmop_delete($TMP_shm_id);
			else shmop_delete($TMP_shm_id);
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
		}
	} else {
		if (file_exists("modules/$V_dir/".$V_mod.".defaults.php")) {
		    $TMP="";
		    $TMP=include ("modules/$V_dir/".$V_mod.".defaults.php");
		    if ($TMP!==true && $TMP!="1") echo $TMP;
		}
	}
	for ($i = 0; $i < $numf; $i++) {
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
		if (($fields[$i]->dtype=="image" || $fields[$i]->dtype=="file") && $RAD_softDelete!="1") {
			$fname=$fields[$i]->name;
			$files = explode("\n", $TMP_row[$fields[$i]->name]);
			if (count($files) >1) {
				for ($k = 0; $k < count($files); $k++) {
					$files[$k]=str_replace("\n", "", $files[$k]);
					$files[$k]=str_replace("\r", "", $files[$k]);
					if ($files[$k]!="") RAD_unlink($files[$k]);
				}
			} else {
				$TMP_row[$fields[$i]->name]=str_replace("\n", "", $TMP_row[$fields[$i]->name]);
				$TMP_row[$fields[$i]->name]=str_replace("\r", "", $TMP_row[$fields[$i]->name]);
				if ($TMP_row[$fields[$i]->name]!="") RAD_unlink($TMP_row[$fields[$i]->name]);
			}
		}
	}
	if ($RAD_softDelete!="1") $cmdSQL="DELETE FROM $tablename WHERE $idnames";
	$TMP_tablename=substr($V_tablename,strlen(_DEF_TABLE_PREFIX));
	if (file_exists("modules/$V_dir/".$V_mod.".predelete.php")) {
		$TMP="";
		$TMP=include ("modules/$V_dir/".$V_mod.".predelete.php");
		if ($TMP!==true && $TMP!="1") echo $TMP;
	}
	if (file_exists("modules/$V_dir/".$V_mod.".presql.php")) {
		$TMP="";
		$TMP=include ("modules/$V_dir/".$V_mod.".presql.php");
		if ($TMP!==true && $TMP!="1") echo $TMP;
	}
	if ($V_mod!=$V_tablename) {
		if (file_exists("modules/$V_dir/".$V_tablename.".predelete.php")) {
		    $TMP="";
		    $TMP=include ("modules/$V_dir/".$V_tablename.".predelete.php");
		    if ($TMP!==true && $TMP!="1") echo $TMP;
		}
		if (file_exists("modules/$V_dir/".$V_tablename.".presql.php")) {
		    $TMP="";
		    $TMP=include ("modules/$V_dir/".$V_tablename.".presql.php");
		    if ($TMP!==true && $TMP!="1") echo $TMP;
		}
	}
	if ($TMP_tablename!=$V_tablename) {
		if (file_exists("modules/$V_dir/".$TMP_tablename.".predelete.php")) {
			$TMP="";
			$TMP=include ("modules/$V_dir/".$TMP_tablename.".predelete.php");
			if ($TMP!==true && $TMP!="1") echo $TMP;
		}
		if (file_exists("modules/$V_dir/".$TMP_tablename.".presql.php")) {
			$TMP="";
			$TMP=include ("modules/$V_dir/".$TMP_tablename.".presql.php");
			if ($TMP!==true && $TMP!="1") echo $TMP;
		}
	}
        if ($RAD_softDelete!="1") {
            $TMP_result=sql_query($cmdSQL, $RAD_dbi) ;
        }else{
            $cmdSQL="";
            $TMP_user=base64_decode(getSessionVar("SESSION_user"));
            for ($i = 0; $i < $numf; $i++) {
                if (${"idname$i"} != "") {
                    $cmdSQL.="UPDATE ".$tablename." SET ESTADO_EDICION='E', ID_ESTADO_EDICION='".${"par".$i}."', USUARIO_ESTADO_EDICION='".$TMP_user."', TIMESTAMP_ESTADO_EDICION='".date("Y-m-d H:i:s")."' WHERE ".${"idname".$i}."='".${"par".$i}."'";
                    $TMP_result=sql_query($cmdSQL, $RAD_dbi) ;
                }
            }
        }
	if ($TMP_result) {
		$TMP_tablename=substr($V_tablename,strlen(_DEF_TABLE_PREFIX));
		if (file_exists("modules/$V_dir/".$V_mod.".postdelete.php")) {
			$TMP="";
			$TMP=include ("modules/$V_dir/".$V_mod.".postdelete.php");
			if ($TMP!==true && $TMP!="1") echo $TMP;
		}
		if (file_exists("modules/$V_dir/".$V_mod.".postsql.php")) {
			$TMP="";
			$TMP=include ("modules/$V_dir/".$V_mod.".postsql.php");
			if ($TMP!==true && $TMP!="1") echo $TMP;
		}
		if ($V_mod!=$V_tablename) {
			if (file_exists("modules/$V_dir/".$V_tablename.".postdelete.php")) {
			    $TMP="";
			    $TMP=include ("modules/$V_dir/".$V_tablename.".postdelete.php");
			    if ($TMP!==true && $TMP!="1") echo $TMP;
			}
			if (file_exists("modules/$V_dir/".$V_tablename.".postsql.php")) {
			    $TMP="";
			    $TMP=include ("modules/$V_dir/".$V_tablename.".postsql.php");
			    if ($TMP!==true && $TMP!="1") echo $TMP;
			}
		}
		if ($TMP_tablename!=$V_tablename) {
			if (file_exists("modules/$V_dir/".$TMP_tablename.".postdelete.php")) {
				$TMP="";
				$TMP=include ("modules/$V_dir/".$TMP_tablename.".postdelete.php");
				if ($TMP!==true && $TMP!="1") echo $TMP;
			}
			if (file_exists("modules/$V_dir/".$TMP_tablename.".postsql.php")) {
				$TMP="";
				$TMP=include ("modules/$V_dir/".$TMP_tablename.".postsql.php");
				if ($TMP!==true && $TMP!="1") echo $TMP;
			}
		}

		if ($start>0) $start--;
		if ($defaultfunc=="delete") {
			$func = "error";
//			$RAD_errorstr .= _DEF_NLSRecordDeleted."\n<script type='text/javascript'>\nalert('"._DEF_NLSRecordDeleted."');\n</script>\n";
			$RAD_errorstr .= _DEF_NLSRecordDeleted."\n<script type='text/javascript'>\nwindow.status='"._DEF_NLSRecordDeleted."';\n</script>\n";
		} else {
//			if ($V_numrows>0 && $browsetype!="deleteline") $func="update";
			if ($V_numrows>0) $func="update";
			else $func=$defaultfunc;
		}
		if ($subfunc=="browse") {
			echo "\n<html>\n<body>\n<script type='text/javascript'>\n";
//			echo "window.opener.location.reload();\n";
		    if (_DEF_POPUP_MARGIN=="SUBMODAL") {
			echo "if (window.opener) { window.opener.location.href=window.opener.location.href; self.close(); }\n";
			echo "else { window.top.location.href=window.top.location.href; window.top.hidePopWin();}\n";
		    } else {
			echo "if (window.opener) {\n  var urlOpener=window.opener.location.href;\n";
			echo "  if (urlOpener.indexOf('#')>0) { urlOpener=urlOpener.substr(0,urlOpener.indexOf('#')); }\n";
			echo "  window.opener.location.href=urlOpener; window.close(); }\n";
			echo "else { top.location.href=top.location.href; top.RAD_hideL(); }\n";
		    }
		    echo "</script>\n</body>\n</html>\n";
		    die;
		}
	} else {
		$func = "error";
		$RAD_errorstr .= $cmdSQL." [".sql_errorno($TMP_result)."] ".sql_error($TMP_result);
	}
  }
// Falta enlazar con update si es browsedit

    if ($subbrowse!="" && $V_numrows>0 && $browsetype=="deleteline" && $subfunc!="browsedit") {
                echo "<html><body><script type='text/javascript'>\n";
                echo "window.history.back();\n";
                //else echo "window.location.href=window.location.href;\n";
                echo "</script></body></html>\n";
                die;
    }
    if (_DEF_POPUP_MARGIN!="SUBMODAL") {
        if ($V_numrows>0 && $browsetype=="deleteline" && $subfunc!="browsedit") {
                echo "<html><body><script type='text/javascript'>\n";
                if ($subbrowse!="") echo "window.history.back();\n";
                else echo "window.history.back();\n";
                //else echo "window.location.href=window.location.href;\n";
                echo "</script></body></html>\n";
                die;
        }
        if ($subfunc=="browsedit") $func="update";
    }else{
        if ($subfunc=="") {
            //$func="browse";
            $TMP_URLD=$PHP_SELF."?V_dir=$V_dir&V_mod=$V_mod&V_idmod=$V_idmod&func=browse&V_lap=$V_lap&headeroff=$headeroff&menuoff=$menuoff&footeroff=$footeroff";
            echo "<script>parent.location=parent.location; window.location='$TMP_URLD';</script>";
            die;
        }elseif($subfunc=="browse"){
            $TMP_URLD=$PHP_SELF."?V_dir=$V_dir&V_mod=$V_mod&V_idmod=$V_idmod&func=detail&par0=$par0&V_lap=$V_lap&headeroff=$headeroff&menuoff=$menuoff&footeroff=$footeroff";
            echo "<script>parent.location=parent.location; window.location='$TMP_URLD';</script>";
            die;
            //die("\n<script>\nwindow.top.opener.location.href=window.top.opener.location.href;\n</script>\n");
        }elseif($subfunc=="browsedit"){
            $func="update";
            ////$TMP_URLD=$PHP_SELF."?V_dir=$V_dir&V_mod=$V_mod&V_idmod=$V_idmod&func=detail&par0=$par0&V_lap=$V_lap&headeroff=$headeroff&menuoff=$menuoff&footeroff=$footeroff";
            ////echo "<script>history.back(-1);</script>";
            ////die;
        }else{
            //$func=$subfunc;
            $TMP_URLD=$PHP_SELF."?V_dir=$V_dir&V_mod=$V_mod&V_idmod=$V_idmod&func=$subfunc&par0=$par0&V_lap=$V_lap&headeroff=$headeroff&menuoff=$menuoff&footeroff=$footeroff";
            echo "<script>parent.location=parent.location; window.location='$TMP_URLD';</script>";
            die;

        }
    }
}
?>
