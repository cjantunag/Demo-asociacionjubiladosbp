<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}
/////////////////////////////////////////////////////////////////////////////
function blocks($TMP_side) {
	global $RAD_device, $RAD_dbi, $HTTP_SESSION_VARS, $SESSION_blocks_left, $SESSION_blocks_right, $RAD_blocksonly, $SESSION_blocks_header, $SESSION_blocks_footer, $V_index, $id, $V_idmod, $V_mod;

	$TMP_lang=getSessionVar("SESSION_lang");
	if (strtolower($TMP_side) == "left") $TMP_side = "l";
	if (strtolower($TMP_side) == "right") $TMP_side = "r";
	if (strtolower($TMP_side) == "center") $TMP_side = "c";
	if (strtolower($TMP_side) == "header") $TMP_side = "h";
	if (strtolower($TMP_side) == "footer") $TMP_side = "f";

	//if ($SESSION_blocks_left==="0" && $TMP_side="l") return;
	//if ($SESSION_blocks_right==="0" && $TMP_side="r") return;
	if ($SESSION_blocks_left=="2" && $TMP_side="l") return;
	if ($SESSION_blocks_right=="2" && $TMP_side="r") return;
	if ($SESSION_blocks_header=="2" && $TMP_side=="h") return;
	if ($SESSION_blocks_footer=="2" && $TMP_side=="f") return;
	if ($SESSION_blocks_header==="0" && $TMP_side=="h") return;
	if ($SESSION_blocks_footer==="0" && $TMP_side=="f") return;
	if ($TMP_side=="r") $SESSION_blocks_right="0"; // Only once
	if ($TMP_side=="f") $SESSION_blocks_footer="0"; // Only once
	if ($TMP_side=="l") $SESSION_blocks_left="0"; // Only once
	if ($TMP_side=="h") $SESSION_blocks_header="0"; // Only once

	//$TMP_result = sql_query("select * from "._DBT_BLOCKS." where "._DBF_B_POSITION."='$TMP_side' AND "._DBF_B_ACTIVE."='1' AND ("._DBF_B_BLOCKFILE."!='stat' OR "._DBF_B_BLOCKFILE." IS NULL) ORDER BY "._DBF_B_WEIGHT." ASC", $RAD_dbi);
	$TMP_result = sql_query("select * from "._DBT_BLOCKS." where "._DBF_B_POSITION."='$TMP_side' AND "._DBF_B_ACTIVE."='1' ORDER BY "._DBF_B_WEIGHT." ASC", $RAD_dbi);
	$TMP_cont_blocks_home=0;
	if (_DEF_COLUMNS>1 && $TMP_side=="c") echo "<table cellpadding=0 cellspacing=0 width=100%>";
	while($TMP_row=sql_fetch_array($TMP_result, $RAD_dbi)) {
		if ($TMP_row[_DBF_B_BLOCKFILE]=="stat") continue;
		$TMP_mod=false; // por defecto no se admiten salvo que estén permitidos
		$TMP_art=false; 
		if ($TMP_row[idarticulos]!="" && $id>0) {
			if ($V_mod=="articulos" && ereg(",".$id.",",$TMP_row[idarticulos])) $TMP_art=true; // se admite en articulo
			if ($V_mod=="showart" && ereg(",".$id.",",$TMP_row[idarticulos])) $TMP_art=true; // se admite en articulo
		}
		if ($TMP_row[idmodulos]!="") if ($V_idmod>0 && ereg(",".$V_idmod.",",$TMP_row[idmodulos])) $TMP_mod=true; // se admite mod
		if ($TMP_row[home]=="1" && $V_idmod==0) $TMP_mod=true; // es portada
		if ($TMP_row[idarticulos]!="" && $TMP_row[idmodulos]=="" && $TMP_art==false) continue;
		if ($TMP_row[idarticulos]=="" && $TMP_row[idmodulos]!="" && $TMP_mod==false) continue;
		if ($TMP_row[idarticulos]!="" && $TMP_row[idmodulos]!="" && $TMP_art==false && $TMP_mod==false) continue;
		if ($TMP_row[home]!="1" && ($TMP_row[idmodulos]!=""||$TMP_row[idarticulos]!="") && $V_idmod==0) continue; // no es de portada
		if ($TMP_row[home]=="1") if ($TMP_row[idmodulos]=="" && $TMP_row[idarticulos]=="" && $V_idmod>0) continue; // es solo portada
		$TMP_profile=$TMP_row[_DBF_B_PROFILE];

		if (!ereg(",".$RAD_device.",",",".$TMP_row[device].",") && $TMP_row[device]!="" && $RAD_device!="") continue;
		if (_DBT_BLOCKSGROUP!="_DBT_BLOCKSGROUP") {
			$result2=sql_query("select * from "._DBT_BLOCKSGROUP." where "._DBF_BG_IDBLOCK."='".$TMP_row[_DBF_B_IDBLOCK]."'", $RAD_dbi);
			while($TMP_row2=sql_fetch_array($result2, $RAD_dbi)) {
				if ($TMP_row2[_DBF_BG_PROFILES]!="") {
					if ($TMP_profile!="") $TMP_profile.=",";
					$TMP_profile.=$TMP_row2[_DBF_BG_PROFILES];
				}
			}
		}

		$TMP_public=$TMP_row[_DBF_B_PUBLIC];
		if ($TMP_row[_DBF_B_NAME."_".$TMP_lang]!="") $TMP_title=$TMP_row[_DBF_B_NAME."_".$TMP_lang];
		else $TMP_title=$TMP_row[_DBF_B_NAME];
		if ($TMP_row[""._DBF_B_CONTENT."_".$TMP_lang]!="") $TMP_content=$TMP_row[""._DBF_B_CONTENT."_".$TMP_lang];
		else $TMP_content=$TMP_row[""._DBF_B_CONTENT];
		$TMP_url=$TMP_row[""._DBF_B_URL];
		$TMP_blockfile=$TMP_row[""._DBF_B_BLOCKFILE];
		$TMP_params=$TMP_row[""._DBF_B_PARAMETERS];
		if ($V_index!="1" && ereg("&noindex=",$TMP_params)) continue;
		$TMP_id=$TMP_row[""._DBF_B_IDBLOCK];
		if ($RAD_blocksonly!="") if ($TMP_blockfile!="") if (!ereg(",".$TMP_blockfile.",",$RAD_blocksonly)) continue;
		if (verifyProfile($TMP_profile,$TMP_public)) {
		    if (_DEF_COLUMNS>1 && $TMP_side=="c") {
			if ($TMP_cont_blocks_home==0) echo "<tr><td valign=top width=50%>";
			else if (($TMP_cont_blocks_home%_DEF_COLUMNS)==(_DEF_COLUMNS-1)) echo "</td><td valign=top width=50%>";
			else echo "</td></tr><tr><td valign=top width=50%>";
			$TMP_cont_blocks_home++;
		    }
		    render_blocks($TMP_side, $TMP_blockfile, $TMP_title, $TMP_content, $TMP_url, $TMP_params, $TMP_id);
		}
	}
	if (_DEF_COLUMNS>1 && $TMP_side=="c") {
	    if ($TMP_cont_blocks_home==0) echo "</table>";
	    else if (($TMP_cont_blocks_home%_DEF_COLUMNS)==(_DEF_COLUMNS-1)) echo "</td><td></td></tr></table>";
	    else echo "</td></tr></table>";
	}
	$TMP_cfg=getSessionVar("SESSION_cfg");
	if (file_exists("blocksWEB/blocks".$TMP_cfg.".".$TMP_side.".php")) { 
		global $content; 
		$content=""; 
		$TMP_content=include("blocksWEB/blocks".$TMP_cfg.".".$TMP_side.".php"); 
		if ($TMP_content!="" && $TMP_content!="1") $content=$TMP_content; 
		echo $content; 
	}  // RAD_ORACLE 
}
/////////////////////////////////////////////////////////////////////////////
function render_blocks($TMP_side, $TMP_blockfile, $TMP_blocktitle, $TMP_content, $TMP_url, $TMP_params, $TMP_id) {
	global $RAD_block_side, $blocktitle, $content, $RAD_dbi, $dbname, $HTTP_SESSION_VARS, $footer, $SESSION_SID;
	if ($footer=="DEBUG") echo RAD_microtime()." ".$TMP_blockfile." ".$TMP_params;
	$RAD_block_side=$TMP_side;
	$blockcache=""; // Parametro de un bloque que indica que su contenido se graba en fichero cache y se recupera de el si no ha pasado el tiempo en segundos indicado por este parametro
	if ($dbname!="" && $dbname!=_DEF_dbname) $xdbname="&dbname=$dbname";
	$TMP_link="";
	if ((is_modulepermitted("","admin","bloques")||is_admin()) && ($TMP_content!="" || ($TMP_blocktitle!="" && $TMP_blockfile==""))) $TMP_link.="...<a href='index.php?".SID."&V_dir=admin&V_mod=bloques&func=edit&par0=$TMP_id$xdbname&headeroff=x&footeroff=X&blocksoff=x' target=_blank><img src='images/edit.gif'>"._DEF_NLSModify."</a><br/>";
	else if ((is_modulepermitted("","libradm","bloques")) && ($TMP_content!="" || ($TMP_blocktitle!="" && $TMP_blockfile==""))) $TMP_link.="...<a href='index.php?".SID."&V_dir=libradm&V_mod=bloques&func=edit&par0=$TMP_id$xdbname&headeroff=x&footeroff=X&blocksoff=x' target=_blank><img src='images/edit.gif'>"._DEF_NLSModify."</a><br/>";
	else if ((is_modulepermitted("","LIBRA","MENUS")) && ($TMP_content!="" || ($TMP_blocktitle!="" && $TMP_blockfile==""))) $TMP_link.="...<a href='index.php?".SID."&V_dir=LIBRA&V_mod=MENUS&func=edit&par0=$TMP_id$xdbname&headeroff=x&footeroff=X&blocksoff=x' target=_blank><img src='images/edit.gif'>"._DEF_NLSModify."</a><br/>";
	if ($TMP_link!="") {
		$TMP_content=trim($TMP_content);
		$TMP_last=substr($TMP_content,strlen($TMP_content)-6);
		if (strtoupper($TMP_last)=="</DIV>") $TMP_content=substr($TMP_content,0,strlen($TMP_content)-6).$TMP_link.$TMP_last;
		else $TMP_content.=$TMP_link;
	}
	if ($TMP_url == "") {
		if ($TMP_blockfile != "") {
			$RAD_cfg=$HTTP_SESSION_VARS["SESSION_cfg"];
			if (file_exists("blocks/$TMP_blockfile.$RAD_cfg.php")) $TMP_blockfile=$TMP_blockfile.".".$RAD_cfg;
			$TMP_file = @file("blocks/".$TMP_blockfile.".php");
			if (!$TMP_file) {
				$TMP_file = @file("blocks/".$TMP_blockfile);
			} else {
				$TMP_blockfile.=".php";
			}
			if (!$TMP_file) {
				$TMP_content = _BLOCKPROBLEM." [$TMP_blockfile]";
			} else {
				if ($TMP_params!="") {
					$TMP_params=str_replace("\r", "", $TMP_params);
					$TMP_param=explode("\n",$TMP_params);
					if (count($TMP_param)>1) eval($TMP_params);
					else {
						$TMP_param=explode("&",$TMP_params);
						if (count($TMP_param)==0) $TMP_param[0]=$TMP_params;
						//$TMP_param[0]=$TMP_params;
						if (count($TMP_param)>0) {
							for ($kki = 0; $kki < count($TMP_param); $kki++) {
								$TMP_var=explode("=",$TMP_param[$kki]);
								if ($TMP_var[0]!="") {
									global ${$TMP_var[0]};
									${$TMP_var[0]}=$TMP_var[1];
								}
							}
						}
					}
				}
				$TMP_content=""; $content="";
				$blocktitle="";
				//-------------------------------------------------
				$TMP_retrievedcache=false;
				if ($blockcache>0) {
					if ($dbname!="") $TMP_dbname=$dbname;
					else $TMP_dbname=_DEF_dbname;
					$TMP_fich_blockcache="files/".$TMP_dbname."/".substr($TMP_blockfile,0,strlen($TMP_blockfile)-3).$TMP_id.".htm";  // Nombre del fichero cache del bloque
					if (file_exists($TMP_fich_blockcache)) {
						$TMP_dif=time()-filemtime($TMP_fich_blockcache);
						if ($TMP_dif<$blockcache) {
							if ($fp=@fopen($TMP_fich_blockcache,"r")) { /*$content="\n<! cached from $TMP_fich_blockcache >\n";*/ $TMP_retrievedcache=true; while(!feof($fp)){ $content.=fgets($fp,4096); } fclose($fp); }
						} else {
							unlink($TMP_fich_blockcache);
						}
					}
				}
				if ($TMP_retrievedcache==false) $TMP_result=include("blocks/$TMP_blockfile");
				//-------------------------------------------------
				if ($TMP_result!="1"&&$TMP_result!=true&&$TMP_result!=""&&$content=="") $content=$TMP_result;
				if ($blocktitle!="") {
					$TMP_blocktitle=$blocktitle;
					$blocktitle="";
				} 
				if ($content!="") {
					$TMP_content=$content;
					$content="";
				} else $TMP_content="";
				if ($blockcache>0) if (!file_exists($TMP_fich_blockcache)) if ($fp=@fopen($TMP_fich_blockcache,"w")) { fwrite($fp,$TMP_content); fclose($fp); }
			}
		}
	} else {
		$TMP_content="";
		$TMP_fp = fopen ($TMP_url,"r");
		if ($TMP_fp) while (!feof($TMP_fp)) {
			$TMP_content .= fgets($TMP_fp,9999);
		}
		fclose($TMP_fp);
	}
	$TMP_blocktitle=str_replace("modules.php?","modules.php?".$SESSION_SID,$TMP_blocktitle);
	$TMP_content=str_replace("modules.php?","modules.php?".$SESSION_SID,$TMP_content);
	$TMP_blocktitle=str_replace("index.php?","index.php?".$SESSION_SID,$TMP_blocktitle);
	$TMP_content=str_replace("index.php?","index.php?".$SESSION_SID,$TMP_content);
	if ($TMP_side == "h" || $TMP_side == "f") {
		echo $TMP_blocktitle.$TMP_content;
	} else if ($TMP_side == "c") {
		themecenterbox($TMP_blocktitle, $TMP_content,$TMP_id);
	} else {
		if ($TMP_content!="" || ($TMP_blocktitle!="" && $TMP_blockfile=="")) {
			themesidebox($TMP_blocktitle, $TMP_content);
		}
	}
}
/////////////////////////////////////////////////////////////////////////////
function themecenterbox($F_blocktitle, $F_content,$F_id="") {
	if (trim($F_blocktitle)=="" && trim($F_content)=="") return;
	OpenTable($F_id);
	if ($F_blocktitle!="") echo "<center><b>$F_blocktitle</b></center><br>";
	if ($F_content!="") echo $F_content;
	CloseTable();
}
?>
