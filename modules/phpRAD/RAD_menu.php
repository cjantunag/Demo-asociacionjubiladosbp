<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}

if (defined('_DEF_DIR_ROOT')) $dir_root = _DEF_DIR_ROOT;
else $dir_root = "";

if ($titleNLS=="") $titleNLS=$title;
if ($RAD_NLS[$title]!="") $titleNLS=$RAD_NLS[$title];
//---------------------------------------------------------------------------
//------------------------- Help
//---------------------------------------------------------------------------
$TMP_info=""; 
$TMP_editinfo="";
$TMP_linkinfo=""; 
if (file_exists("modules/$V_dir/".$V_tablename.".help.php") || file_exists("modules/$V_dir/".$V_mod.".help.php")) {
	if (is_admin() || is_modulepermitted("", "phpGenRAD", "fileedit")) $TMP_editinfo=" <A ACCESSKEY='K' TITLE='ALT+K' TARGET=_BLANK HREF=\"$PHP_SELF?menuoff=x&headeroff=x&footeroff=x&V_dir=phpGenRAD&V_mod=fileedit&directory=modules/$V_dir&filename=$V_mod".".help.php".$SESSION_SID."\"><img border=0 src='".$dir_root."images/edit.gif' title='"._DEF_NLSModify." "._DEF_NLSHelp.". ALT+K'>"._DEF_NLSModify." "._DEF_NLSHelp."</A>\n";
	$TMP_linkinfo=$PHP_SELF."?menuoff=x&headeroff=x&footeroff=x&func=help".$URLROI.$urlidnames;
	if (_DEF_POPUP_MARGIN=="SUBMODAL") $TMP_linkinfo="<A style='font-weight:bold;' ACCESSKEY='A' TITLE='ALT+A "._DEF_NLSHelp.".' TARGET=_BLANK HREF=\"javascript:RAD_OpenW('".$TMP_linkinfo."', 800, 600);\">";
	else $TMP_linkinfo="<A style='font-weight:bold;' ACCESSKEY='A' TITLE='ALT+A "._DEF_NLSHelp.".' TARGET=_BLANK HREF=\"".$TMP_linkinfo."\">";
	$TMP_info=$TMP_linkinfo."<img border=0 src='".$dir_root."images/info.gif' valign=-2></A>";
	//$TMP_info.="\n".$TMP_editinfo;
}
if ($func!="" && $func!="browse" && $func!="edit" && $func!="new" && $func!="search" && $func!="searchform") $TMP_info="";
if ($func == "help") {
    if (_DEF_POPUP_MARGIN=="SUBMODAL") $TMP_close="closePop();";
    else $TMP_close="window.close();";
    echo "<body onblur='".$TMP_close."'><TABLE width=100% class=borde><TR><TH>".$titleNLS."</TH></TR></TABLE>";
    if (file_exists("modules/$V_dir/".$V_tablename.".help.php")) {
        $TMP=include ("modules/$V_dir/".$V_tablename.".help.php");
        if ($TMP!==true && $TMP!="1") echo $TMP;
    }
    else if (file_exists("modules/$V_dir/".$V_mod.".help.php")) {
        $TMP=include ("modules/$V_dir/".$V_mod.".help.php");
        if ($TMP!==true && $TMP!="1") echo $TMP;
    }
    echo "<br><br><a accesskey='Q' title='ALT+Q' href='javascript:".$TMP_close."'><img border=0 src='".$dir_root."images/close.gif'> Cerrar ventana</a> | $TMP_editinfo <br>";
    return;
}
if (ereg("<",$titleNLS) || $RAD_showiconhelp!="") $titleNLS.=" ".$TMP_info;
else $titleNLS=$TMP_linkinfo."<span class='title'>".$titleNLS."</span></a>";
//---------------------------------------------------------------------------
//------------------------- Application Table Menu
//---------------------------------------------------------------------------
$imgnames = "";
$urlidnames = "";
for ($i = 0; $i < $numf; $i++) {
	if (!isset(${"idname$i"})) ${"idname$i"} = "";
	if (${"idname$i"} != "") {
		if ($idnames == "") {
			$idnames = "${"idname$i"} = '" . ${"par$i"} . "'";
			$idn = "${"par$i"}";
		} else {
			$idnames .= " AND ${"idname$i"} = '" . ${"par$i"} . "'";
			$idn .= ", ${"par$i"}";
		}
		$imgnames .= "&id$i=${"par$i"}";
		$urlidnames .= "&par$i=".urlencode("${"par$i"}");
	}
}
if (is_admin() || is_modulepermitted("", "phpDBAdmin", "index")) {
    if ($_REQUEST[dbname]!="") $TMP_db="&db=".$_REQUEST[dbname];
    $TMP_link1="<a target=_blank href='index.php?&V_dir=phpDBAdmin&V_mod=index&func=tbl_properties&server=1&table=".$tablename.$TMP_db.$SESSION_SID."&dbname=$dbname'>&nbsp;&nbsp;</a>";
}
if (is_admin() && is_modulepermitted("", "phpDBAdmin", "index")) {
    if ($_REQUEST[dbname]!="") $TMP_db="&db=".$_REQUEST[dbname];
    $TMP_link1="<a target=_blank href='index.php?&V_dir=phpDBAdmin&V_mod=index&func=tbl_properties&server=1&table=".$tablename.$TMP_db.$SESSION_SID."&dbname=$dbname'>&nbsp;&nbsp;</a>";
} else if (is_admin() || is_modulepermitted("", "phpMyAdmin", "index")) {
    if ($_REQUEST[dbname]!="") $TMP_db="&db=".$_REQUEST[dbname];
    $TMP_link1="<a target=_blank href='index.php?&V_dir=phpMyAdmin&V_mod=index&func=tbl_properties&server=1&table=".$tablename.$TMP_db.$SESSION_SID."&dbname=$dbname'>&nbsp;&nbsp;</a>";
}

if (is_admin() || is_modulepermitted("", "phpGenRAD", "index") || is_modulepermitted("", "phpGenRAD", "indexRAD")) {
    $TMP_link2="<a target=_blank href='$PHP_SELF?&V_dir=phpGenRAD&V_mod=indexRAD&V_submod=genform&modulesdir=$V_dir&dbname=$dbname&project_file=".$V_mod.".prj.php$SESSION_SID'>&nbsp;&nbsp;</a>";
} else if (is_modulepermitted("", "phpGenRAD", "minigenform")) {
    $TMP_link2="<a target=_blank href='$PHP_SELF?&V_dir=phpGenRAD&V_mod=minigenform&modulesdir=$V_dir&dbname=$dbname&project_file=".$V_mod.".prj.php$SESSION_SID'>&nbsp;&nbsp;</a>";
}
if ($menuoff!="" && $subbrowseSID=="" && $func!="search_js") {
    if ($func!="none") { 
	if ($V_typePrint!='CSV') {
		echo "<TABLE width=100% class=borde><TR><TH class=title>".$TMP_link1.$titleNLS.$TMP_link2."</TH></TR></TABLE>";
		if ($subbrowseSID=="" && $subfunc!="list" && $subfunc!="report") echo "<div style='font-weight:bold; text-align:center; color:maroon; border:0; margin:0; padding:0;' id='RAD_status'>".$RAD_status."</div>";
	}
    }
    if ($func!="" && $func!="browse" && $func!="detail" && $func!="edit" && $func!="delete" && $func!="new" && $func!="insert" && $func!="update" && $func!="help" && $func!="search" && $func!="search_js" && $func!="searchform") {
	if (file_exists("modules/$V_dir/common.defaults.php")) {
		$TMP=include ("modules/$V_dir/common.defaults.php");
		if ($TMP!==true && $TMP!="1") echo $TMP;
		$TMP="";
	}
	if (file_exists("modules/$V_dir/".$V_mod.".defaults.php")) {
		$TMP="";
		$TMP=include_once ("modules/$V_dir/".$V_mod.".defaults.php");
		if ($TMP!==true && $TMP!="1") echo $TMP;
		$TMP="";
	}
    }
    return;
}
if ($menuoff!="" && $subbrowseSID!="" && $rows_limit<$found && $found>0) {
	if ($menubrowse || !isset($menubrowse)) {
		echo "<TABLE class=menu><TR>";
		if ($func == "browse" || $func == "browsetree" || $func == "search") {
			//if ($func != "browsetree") $opcionesmenubrowse=" &nbsp; $position [$found] &nbsp;";
			if ($func != "browsetree") $opcionesmenubrowse=" &nbsp; $position [$found] ";
			$tabURLROI=str_replace("&menuoff=","&",$tabURLROI);
			//echo RAD_menu_on("<A ACCESSKEY='L' TITLE='ALT+L' HREF=\"$PHP_SELF?func=browse&orderby=$orderby&start=0$tabURLROI\" class=menuon>"._DEF_NLSListAll."</A>$opcionesmenubrowse");
		} else {
			echo RAD_menu_off("<A ACCESSKEY='L' TITLE='ALT+L' HREF=\"$PHP_SELF?func=browse&orderby=$orderby&start=0$tabURLROI\" class=menuoff>"._DEF_NLSListAll."</A>\n");
		}
		echo "</TR></TABLE>";
	}
}
if ($menuoff=="" && $func!="search_js") {
	if ($func == "browse" || $func == "search" || $func == "search_js") {
		$to=$start+$limit;
		if ($to>$found) $to=$found;
	}
	$TMP_link="";
	echo "<TABLE width=100% class=borde><TR><TH class=title>".$TMP_link1.$titleNLS.$TMP_link2."</TH></TR></TABLE>";
//	echo $RAD_menupages,"\n";
//	echo "<div class=menu>\n";
	echo "<TABLE class=menu><TR>";
	if (file_exists("modules/$V_dir/".$V_mod.".premenu.php")) {
        	$TMP=include ("modules/$V_dir/".$V_mod.".premenu.php");
        	if ($TMP!==true && $TMP!="1") echo $TMP;
    	}
	if ($menucalendar) {
		if ($func == "browsecalendar") {
//			$opcionesmenubrowsecalendar=" &nbsp; $position [$found] &nbsp; $prevpage &nbsp; $nextpage";
			$opcionesmenubrowsecalendar="";
			echo RAD_menu_on("<A ACCESSKEY='C' TITLE='ALT+C' HREF=\"$PHP_SELF?func=browsecalendar&orderby=$orderby&start=0$tabURLROI\" class=menuon>"._DEF_NLSCalendar."</A>$opcionesmenubrowsecalendar");
		} else {
			echo RAD_menu_off("<A ACCESSKEY='C' TITLE='ALT+C' HREF=\"$PHP_SELF?func=browsecalendar&orderby=$orderby&start=0$tabURLROI\" class=menuoff>"._DEF_NLSCalendar."</A>\n");
		}
	}
	if ($menutree) {
		if ($func == "browsetree") {
			if ($RAD_rows>0) echo RAD_menu_on("<A ACCESSKEY='X' TITLE='ALT+X' HREF=\"$PHP_SELF?func=browsetree&RAD_rows=&".$tabURLROI."\" class=menuoff>"._DEF_NLSTree."</A>\n");
			else echo RAD_menu_on("<A ACCESSKEY='X' TITLE='ALT+X' HREF=\"$PHP_SELF?func=browsetree&RAD_rows=".$found.$tabURLROI."\" class=menuoff>"._DEF_NLSTree."</A>\n");
			//if ($RAD_rows>0) echo RAD_menu_off("<A ACCESSKEY='X' TITLE='ALT+X' HREF=\"$PHP_SELF?func=browsetree&RAD_rows=&".$tabURLROI."\" class=menuoff><img src='images/menos.gif' border=0> "._DEF_NLSTree."</A>\n");
			//else echo RAD_menu_off("<A ACCESSKEY='X' TITLE='ALT+X' HREF=\"$PHP_SELF?func=browsetree&RAD_rows=".$found.$tabURLROI."\" class=menuoff><img src='images/mas.gif' border=0> "._DEF_NLSTree."</A>\n");
			//echo RAD_menu_on("<img src='images/mas.gif' border=0> "._DEF_NLSTree."\n");
		} else {
			echo RAD_menu_off("<A ACCESSKEY='X' TITLE='ALT+X' HREF=\"$PHP_SELF?func=browsetree$tabURLROI\" class=menuoff>"._DEF_NLSTree."</A>\n");
			//echo RAD_menu_off("<A ACCESSKEY='X' TITLE='ALT+X' HREF=\"$PHP_SELF?func=browsetree$tabURLROI\" class=menuoff><img src='images/mas.gif' border=0> "._DEF_NLSTree."</A>\n");
		}
	}
	if ($menubrowse || !isset($menubrowse)) {
		//if ($func == "browse" || $func == "browsetree" || $func == "search") {
		if ($func == "browse" || $func == "search") {
			if ($func != "browsetree")
				//$opcionesmenubrowse=" &nbsp; $position [$found] &nbsp; $prevpage &nbsp; $nextpage";
				$opcionesmenubrowse=" &nbsp; $position [$found] &nbsp; $prevpage &nbsp;".$nextpage;
			if ($func == "search" && $showall!="") $opcionesmenubrowse=" &nbsp; [$found] ";
			echo RAD_menu_on("<A ACCESSKEY='L' TITLE='ALT+L' HREF=\"$PHP_SELF?func=browse&orderby=$orderby&start=0$tabURLROI\" class=menuon>"._DEF_NLSListAll."</A>".$opcionesmenubrowse);
		} else {
			echo RAD_menu_off("<A ACCESSKEY='L' TITLE='ALT+L' HREF=\"$PHP_SELF?func=browse&orderby=$orderby&start=0$tabURLROI\" class=menuoff>"._DEF_NLSListAll."</A>\n");
		}
	}
	if ($menusearch || !isset($menusearch)) {
		if ($func == "searchform") {
			echo RAD_menu_on(""._DEF_NLSSearch."\n");
		} else {
			$TMP_par0="";
			if ($par0!="") $TMP_par0="&par0=$par0";
			echo RAD_menu_off("<A ACCESSKEY='B' TITLE='ALT+B' HREF=\"$PHP_SELF?func=searchform".$TMP_par0."$tabURLROI&orderby=$orderby\" class=menuoff>"._DEF_NLSSearch."</A>\n");
		}
	}
	if ($menunew || !isset($menunew)) {
		if ($func == "new") {
			echo RAD_menu_on(""._DEF_NLSNew."\n");
		} else {
			echo RAD_menu_off("<A ACCESSKEY='N' TITLE='ALT+N' HREF=\"$PHP_SELF?func=new$URLROI$urlidnames\" class=menuoff>"._DEF_NLSNew."</A>\n");
		}
	}
	if ($func == "browse" || $func == "browsetree" || $func == "search" || $func == "detail" || $func == "edit") {
		if ($func == "browse" || $func == "search") {
			//$paramForm="start=0&limit=$found&";
			$paramForm="start=0&";
			$paramForm.="func=$func&menuoff=x&headeroff=x&footeroff=x".URLparamInput("start","limit");
		} elseif ($func == "browsetree") {
			//$paramForm="start=0&limit=$found&browsetreefield=".$browsetreefield."&browsetreefieldparent=".$browsetreefieldparent."&";
			$paramForm="start=0&browsetreefield=".$browsetreefield."&browsetreefieldparent=".$browsetreefieldparent."&";
			$paramForm.="func=$func&menuoff=x&headeroff=x&footeroff=x".URLparamInput("start","limit");
		} else {
			$paramForm.="func=$func&menuoff=x&headeroff=x&footeroff=x".URLparamInput("","");
		}

		if ($menuprint || !isset($menuprint)) {
			if ($func=="edit") {
				echo RAD_menu_off("<A ACCESSKEY='P' TITLE='ALT+P' HREF=\"$PHP_SELF?func=detail&$URLROI$urlidnames&subfunc=list&menuoff=x&headeroff=x&footeroff=x\" class=menuoff target=_blank>"._DEF_NLSPrint."</A>\n");
				if ($menusend || !isset($menusend)) echo RAD_menu_off("<A ACCESSKEY='M' TITLE='ALT+M' HREF=\"javascript:RAD_OpenW('$PHP_SELF?func=detail&$URLROI$urlidnames&subfunc=sendlist&menuoff=x&headeroff=x&footeroff=x',800,550);\" class=menuoff>"._DEF_NLSSend."</A>\n");
			} else {
			//} elseif ($func=="detail") {
				if (_DEF_MaxRegsPrint!="_DEF_MaxRegsPrint" && _DEF_MaxRegsPrint>0 && $found>_DEF_MaxRegsPrint) {
					echo RAD_menu_off("<strike>"._DEF_NLSPrint."</strike>\n");
				} else {
					echo RAD_menu_off("<A ACCESSKEY='P' TITLE='ALT+P' HREF=\"$PHP_SELF?$paramForm$URLROI&subfunc=list\" class=menuoff target=_blank>"._DEF_NLSPrint."</A>\n");
				}
				if ($menusend || !isset($menusend)) echo RAD_menu_off("<A ACCESSKEY='M' TITLE='ALT+M' HREF=\"javascript:RAD_OpenW('$PHP_SELF?$paramForm$URLROI&subfunc=sendlist&menuoff=x&headeroff=x&footeroff=x',800,550);\" class=menuoff>"._DEF_NLSSend."</A>\n");
			}
		}
		if ( ($menureport) || (!isset($menureport)) ) {
			$TMP_par0="";
			if ($par0!="") $TMP_par0="&par0=$par0";
			echo RAD_menu_off("<A ACCESSKEY='T' TITLE='ALT+T' HREF=\"$PHP_SELF?func=searchform".$TMP_par0."&$URLROI&subfunc=report&menuoff=x&headeroff=x&footeroff=x\" class=menuoff target=_blank>"._DEF_NLSReport."</A>\n");
		}
	}
	if ($func == "detail") {
		if ($menuedit || !isset($menuedit))
			echo RAD_menu_off("<A ACCESSKEY='I' TITLE='ALT+I' HREF=\"$PHP_SELF?func=edit$URLROI$urlidnames\" class=menuoff>"._DEF_NLSEdit."</A>\n");
		if ($menudetail || !isset($menudetail)) echo RAD_menu_on(""._DEF_NLSDetail."\n");
		if ($menudelete || !isset($menudelete))
			echo RAD_menu_off("<A ACCESSKEY='H' TITLE='ALT+H' HREF=\"javascript:delereg();\" class=menuoff>"._DEF_NLSDelete."</A>\n");
			if ($RAD_textConfirmDelete=="") {
				$TMP_confirmdelete="    dele=confirm(\""._DEF_NLSAreYouSure."\");\n        if (dele) {\n";
			} else {
				$TMP_confirmdelete="    dele=prompt(\"Para borrar este registro, teclee ".$RAD_textConfirmDelete."\");\n        if (dele!=\"".$RAD_textConfirmDelete."\") alert(\"No se borra registro.\");\n        if (dele==\"".$RAD_textConfirmDelete."\") {\n";
			}
			echo '
<SCRIPT LANGUAGE="JAVASCRIPT">
function delereg() {
'.$TMP_confirmdelete.'
                document.forms.FORM1.func.value="delete";
                document.forms.FORM1.submit(); }
}
</SCRIPT>
';
	}
	if ($func == "edit") {
		if ($menuedit || !isset($menuedit)) echo RAD_menu_on(""._DEF_NLSEdit."\n");
		if ($menudetail || !isset($menudetail)) echo RAD_menu_off("<A ACCESSKEY='V' TITLE='ALT+V' HREF=\"$PHP_SELF?func=detail$URLROI$urlidnames\" class=menuoff>"._DEF_NLSDetail."</A>\n");
		if ($menudelete || !isset($menudelete)) echo RAD_menu_off("<A ACCESSKEY='H' TITLE='ALT+H' HREF=\"javascript:delereg();\" class=menuoff>"._DEF_NLSDelete."</A>\n");
	}
	$TMP_autoincrement="";
	for ($ki=0; $ki<$numf; $ki++) {
	    if ($fields[$ki]->type=="auto_increment") $TMP_autoincrement=$fields[$ki]->name;
	}
	if ($_REQUEST["orderby"]!="") {
	    $TMP_Aorderby=explode(",",str_replace(" ",",",trim($_REQUEST["orderby"])));
	    if (count($TMP_Aorderby)>1) $TMP_firstorderby=trim($TMP_Aorderby[0]);
	    else $TMP_firstorderby=trim($orderby);
	    if (substr($TMP_firstorderby,strlen($TMP_firstorderby)-1)==",") $TMP_firstorderby=substr($TMP_firstorderby,0,strlen($TMP_firstorderby)-1);
	} else $TMP_firstorderby="";
	if ($menucursor || !isset($menucursor)) {
	  if (($func == "edit" || $func == "detail") && ($fields[0]->type=="auto_increment") && ($subfunc!="list")) {
		//$TMP_defaultfilter="";
		if ($defaultfilter!="") $TMP_defaultfilter=" WHERE ".$defaultfilter;
		if ($TMP_autoincrement!="") {
		    $cmdSQL="SELECT MAX(".$fields[0]->name.") FROM $tablename ".$TMP_defaultfilter;
		    $TMP_result=sql_query($cmdSQL, $RAD_dbi);
		    if ($TMP_result) {
		    	$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
			$TMP_found = $TMP_row[0];
		    }
		} else {
		    $cmdSQL="SELECT COUNT(*) FROM $tablename ".$TMP_defaultfilter;
		    $TMP_result=sql_query($cmdSQL, $RAD_dbi);
		    if ($TMP_result) {
		    	$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
			$TMP_found = $TMP_row[0];
		    }
		}
		if ($orderby!="" && ($func=="edit" || $func=="detail")) {
			RAD_checkPars();
			$idnames="";
			for ($i = 0; $i < $numf; $i++) {
				if (!isset(${"idname$i"})) ${"idname$i"} = "";
				if (${"idname$i"} != "") {
					if ($idnames == "") $idnames = "${"idname$i"} = '" . urldecode(${"par$i"}) . "'";
					else $idnames .= " AND ${"idname$i"} = '" . urldecode(${"par$i"}) . "'";
				}
			}
			$tmpdefaultfilter="";
			if ($defaultfilter!="") {
				if ($idnames =="") $tmpdefaultfilter=" WHERE ".$defaultfilter;
				else $tmpdefaultfilter=" AND ".$defaultfilter;
			}
			if ($TMP_firstorderby!="") {
			   $cmdSQL="SELECT $TMP_firstorderby FROM $tablename WHERE $idnames".$tmpdefaultfilter;
			   $TMP_result=sql_query($cmdSQL, $RAD_dbi);
			   if ($TMP_result) {
				$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
				$TMP_val = $TMP_row[0];
			   }
			}
		}
		$url = "$PHP_SELF?V_dir=$V_dir&V_mod=$V_mod&V_idmod=$V_idmod&func=$func&dbname=$dbname".$SESSION_SID;
		if ($param!="") $url .= "&param=$param";
		if ($desc!="") $url .= "&desc=$desc";
		if ($dbname!=_DEF_dbname) $url.="&dbname=".$dbname;
		if ($bletter!="") $url.="&bletter=$bletter";
		//if ($found!="") $url.= "&found=$TMP_found";
		if ($orderby!="") $url.= "&orderby=$orderby";
		if ($limit!="") $url.= "&limit=$limit";
		if ($V_lap!="") $url.= "&V_lap=$V_lap";
		if ($V_roi!="") $url.= "&V_roi=".urlencode($V_roi);
		if ($headeroff!="") $url.= "&headeroff=".$headeroff;
		if ($menuoff!="") $url.= "&menuoff=".$menuoff;
		if ($footeroff!="") $url.= "&footeroff=".$footeroff;
		if ($blocksoff!="") $url.= "&blocksoff=".$blocksoff;
		$url.= "&start=";
		$TMP_parnext="";
		$TMP_parprev="";

		$TMP_defaultfilter="";
		$cmdSQL="";
		if ($defaultfilter!="") $TMP_defaultfilter=" AND ".$defaultfilter;
		if ($TMP_autoincrement!="" && !ereg(",",$TMP_autoincrement)) {
			$cmdSQL="SELECT ".$TMP_autoincrement." FROM $tablename WHERE ".$TMP_autoincrement.">".converttosql($par0)." $TMP_defaultfilter ";
			if ($dbtype=="oracle") $cmdSQL.=" AND ROWNUM>0 AND ROWNUM<=1 ";
			else $cmdSQL.="LIMIT 0,1";
		}
		if ($TMP_firstorderby!="" && !ereg(",",$TMP_firstorderby)) {
			$cmdSQL="SELECT * FROM $tablename WHERE ".$TMP_firstorderby.">".converttosql($TMP_val)." $TMP_defaultfilter ORDER BY $TMP_firstorderby ";
			if ($dbtype=="oracle") $cmdSQL.=" AND ROWNUM>0 AND ROWNUM<=1 ";
			else $cmdSQL.="LIMIT 0,1";
		}
		if ($cmdSQL!="") {
		  $TMP_result=sql_query($cmdSQL, $RAD_dbi);
		  $ki=0;
		  if ($TMP_result) {
			$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
			for ($i = 0; $i < $numf; $i++) {
				if (!isset(${"idname$i"})) ${"idname$i"} = "";
				if (${"idname$i"} != "" && $TMP_row[${"idname$i"}]!="") {
					$TMP_parnext.="&par".$ki."=".urlencode($TMP_row[${"idname$i"}]);
					$ki++;
				}
			}
		  }
		}
		$TMP_defaultfilter="";
		$cmdSQL="";
		if ($defaultfilter!="") $TMP_defaultfilter=" AND ".$defaultfilter;
		if ($TMP_autoincrement!="" && !ereg(",",$TMP_autoincrement)) {
			$cmdSQL="SELECT ".$TMP_autoincrement." FROM $tablename WHERE ".$TMP_autoincrement."<".converttosql($par0)." $TMP_defaultfilter ";
			if ($dbtype=="oracle") $cmdSQL.=" AND ROWNUM>0 AND ROWNUM<=1 ";
			else $cmdSQL.="LIMIT 0,1";
		}
		if ($TMP_firstorderby!="" && !ereg(",",$TMP_firstorderby)) {
			$cmdSQL="SELECT * FROM $tablename WHERE ".$TMP_firstorderby."<".converttosql($TMP_val)." $TMP_defaultfilter ";
			if ($dbtype=="oracle") $cmdSQL.=" AND ROWNUM>0 AND ROWNUM<=1 ORDER BY $TMP_firstorderby ";
			else $cmdSQL.="ORDER BY $TMP_firstorderby DESC LIMIT 0,1";
		}
		if ($cmdSQL!="") {
		  $TMP_result=sql_query($cmdSQL, $RAD_dbi);
		  $ki=0;
		  if ($TMP_result) {
			$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
			for ($i = 0; $i < $numf; $i++) {
				if (!isset(${"idname$i"})) ${"idname$i"} = "";
				if (${"idname$i"} != "" && $TMP_row[${"idname$i"}]!="") {
					$TMP_parprev.="&par".$ki."=".urlencode($TMP_row[${"idname$i"}]);
					$ki++;
				}
			}
		  }
		}
		if ($TMP_parnext!="") $TMP_nextpage="<a ACCESSKEY='U' TITLE='ALT+U' href='".$url.$TMP_parnext."'><img border=0 src='".$dir_root."images/right.gif' alt=''></a>";
		else $TMP_nextpage="&nbsp;";
		if ($TMP_parprev!="") $TMP_prevpage="<a ACCESSKEY='J' TITLE='ALT+J' href='".$url.$TMP_parprev."'><img border=0 src='".$dir_root."images/left.gif' alt=''></a>";
		else $TMP_prevpage="&nbsp;";
		if ($orderby!="") $opcionesmenuedit="$TMP_prevpage $TMP_nextpage";
		else $opcionesmenuedit=" $par0 [$TMP_found] $TMP_prevpage $TMP_nextpage ";
		if (($V_prevfunc!="searchform")&&($TMP_parnext!="" || $TMP_parprev!="")) echo RAD_menu_off("$opcionesmenuedit");
	  }
	}
	if ($menubackup) {
		$tmp=RAD_m_databaseConfigName();
		if ($func == "showdb") {
			echo RAD_menu_on("<A ACCESSKEY='K' TITLE='ALT+K' HREF=\"$PHP_SELF?menuoff=x&headeroff=x&footeroff=x&func=showdb$URLROI$urlidnames\" class=menuon>"._DEF_NLSBackup."</font></A>\n");
			if ($tmp!="") echo RAD_menu_on("<b>[ ".$tmp."] </b>&nbsp;\n");
		} else {
			echo RAD_menu_off("<A ACCESSKEY='K' TITLE='ALT+K' HREF=\"$PHP_SELF?menuoff=x&headeroff=x&footeroff=x&func=showdb$URLROI$urlidnames\" class=menuoff>"._DEF_NLSBackup."</A>\n");
			if ($tmp!="") echo RAD_menu_off("<b>[ ".$tmp."] </b>&nbsp;\n");
		}
	}
	$TMP_selectminisearch="";
	if (($menuminisearch==true) && ($func==""||$func=="browse"||$func=="search"||$func=="browsetree"||$func=="detail") && $subfunc!="list" && $subfunc!="report") { // minibuscador completo si hay menuminisearch
		for($i=0; $i<$numf; $i++) {
			if ($fields[$i]->name==$searchfield0) $TMP_selected=" SELECTED";
			else $TMP_selected="";
			if (!in_array($fields[$i]->dtype,array("num","bpopupdb","datetext","date","datetime","stand","text","email","http","auto_increment"))) continue;
			$TMP_title=html_entity_decode($fields[$i]->title, ENT_COMPAT, _CHARSET);
			if ($fields[$i]->searchable) $TMP_selectminisearch.="<option value='".$fields[$i]->name."'".$TMP_selected.">".RAD_substr($TMP_title,0,15)."</option>\n";
		}
		if ($TMP_selectminisearch!="") {
			$TMP_selectminisearch="<input type=hidden name='V_prevfunc' value='minisearch'><input type=hidden name=operator0 value='LIKE'><input value='".$_REQUEST[searchvalue0]."' name=searchvalue0 type=text size=6> <select id=searchfield0 name=searchfield0 onkeydown=\"javascript:if((event.keyCode==32||event.keyCode==37||event.keyCode==38||event.keyCode==39||event.keyCode==40)){RAD_simulateMouse(document.getElementById('searchfield0'),'mousedown');return false;}if(event.keyCode==13)this.onchange();\" onchange=\"javascript:if (checkMinisearch()) this.form.submit();\"><option value=''>... "._DEF_NLSSearchString."</option>\n".$TMP_selectminisearch."\n</select>\n";
			$TMP_selectminisearchform="<FORM style='border:0;margin:0; padding:0;' autocomplete=off ACTION=$PHP_SELF METHOD=GET NAME=MINISEARCH onsubmit='javascript:return checkMinisearch();'>".
				"<input type=hidden name=V_dir value='$V_dir'>".
				"<input type=hidden name=V_mod value='$V_mod'>".
				"<input type=hidden name=V_idmod value='$V_idmod'>".
				"<input type=hidden name=dbname value='$dbname'>".
				"<input type=hidden name=func value='search'>".
				"<input type=hidden name=subfunc value='$subfunc'>".
				"<input type=hidden name=MaxSearchFields value='2'>";
			if ($headeroff) $TMP_selectminisearchform.="<input type=hidden name=headeroff value='$headeroff'>";
			if ($footeroff) $TMP_selectminisearchform.="<input type=hidden name=footeroff value='$footeroff'>";
			if ($blocksoff) $TMP_selectminisearchform.="<input type=hidden name=blocksoff value='$blocksoff'>";
			$TMP_selectminisearchform.="".$TMP_selectminisearch."</form>\n<script>\n";
			$TMP_selectminisearchform.="function checkMinisearch() {\n if(document.MINISEARCH.searchvalue0.value=='') return false;\n if(document.MINISEARCH.searchfield0[document.MINISEARCH.searchfield0.selectedIndex].value=='') return false;\n return true;\n}\n</script>\n";
			echo RAD_menu_off($TMP_selectminisearchform);
		}
	}
	if (($TMP_selectminisearch=="") && ($func==""||$func=="browse"||$func=="search"||$func=="browsetree")) { // si no menuminisearch, mira browsesearch
		$TMP_numselectminisearch=0;
		for($i=0; $i<$numf; $i++) {
			if (!in_array($fields[$i]->dtype,array("stand","text","email","http","auto_increment"))) continue;
			if ($fields[$i]->browsesearch) {
				$TMP_selectminisearch.="<option value='".$fields[$i]->name."'>".substr($fields[$i]->title,0,15)."</option>\n";
				$TMP_hiddenminisearch.="<input type=hidden name=searchfield0 value='".$fields[$i]->name."'>";
				$TMP_numselectminisearch++;
			}
		}
		if ($TMP_selectminisearch!="") {
			if ($TMP_numselectminisearch==1) {
				$TMP_selectminisearch="<input type=hidden name='V_prevfunc' value='minisearch'><input type=hidden name=operator0 value='LIKE'><input name=searchvalue0 value='..."._DEF_NLSSearchString."' onclick='javascript:document.MINISEARCH.searchvalue0.value=\"\";' type=text size=8>\n".$TMP_hiddenminisearch."\n</select>\n";
			} else {
				$TMP_selectminisearch="<input type=hidden name='V_prevfunc' value='minisearch'><input type=hidden name=operator0 value='LIKE'><input name=searchvalue0 type=text size=6> <select name=searchfield0 onchange='javascript:this.form.submit();'><option value=''>...</option>\n".$TMP_selectminisearch."\n</select>\n";
			}
			$TMP_selectminisearchform="<FORM autocomplete=off ACTION=$PHP_SELF METHOD=GET NAME=MINISEARCH>";
			$TMP_selectminisearchform.="<input type=hidden name=V_dir value='$V_dir'>";
			$TMP_selectminisearchform.="<input type=hidden name=V_mod value='$V_mod'>";
			$TMP_selectminisearchform.="<input type=hidden name=V_idmod value='$V_idmod'>";
			$TMP_selectminisearchform.="<input type=hidden name=dbname value='$dbname'>";
			$TMP_selectminisearchform.="<input type=hidden name=func value='search'>";
			$TMP_selectminisearchform.="<input type=hidden name=subfunc value='$subfunc'>";
			$TMP_selectminisearchform.="<input type=hidden name=MaxSearchFields value='2'>";
			if ($headeroff) $TMP_selectminisearchform.="<input type=hidden name=headeroff value='$headeroff'>";
			if ($footeroff) $TMP_selectminisearchform.="<input type=hidden name=footeroff value='$footeroff'>";
			if ($blocksoff) $TMP_selectminisearchform.="<input type=hidden name=blocksoff value='$blocksoff'>";
			$TMP_selectminisearchform.=$TMP_selectminisearch;
			echo RAD_menu_off($TMP_selectminisearchform);
		}
	}
	if ($func=="detail" && $RAD_showimpresos==true && RAD_existTable("impresos")) {  
		$TMP_where=""; $TMP_campo=array();
		$campos=sql_list_fields("impresos", $RAD_dbi);
		while($columnas=sql_fetch_array($campos, $RAD,dbi)) $TMP_campo[]=$columnas[0];
		if (in_array('modulo', $TMP_campo)) {
			$TMP_cmd="SELECT * FROM impresos WHERE (modulo='$V_mod' OR ((modulo='' or modulo IS NULL) AND tabla='".$V_tablename."'))";
		} else {
			$TMP_cmd="SELECT * FROM impresos WHERE tabla='".$V_tablename."'";
		}
		if (in_array('usuario', $TMP_campo)) $TMP_cmd.=" AND (usuario='".$TMP_user."' or usuario is null or usuario='')";
		//$TMP_cmd.=" AND (url IS NOT NULL AND url!='')";
		$TMP_i="";
		$TMP_iresult=sql_query($TMP_cmd,$RAD_dbi);
		$TMP_iselect=" &nbsp; <input type=hidden name=func value='$func'><input type=hidden name=dbname value='$dbname'><input type=hidden name=par0 value='$par0'>";
		$TMP_iURLadm=$PHP_SELF."?func=browse&dbname=$dbname&V_dir=admin&V_mod=impresos";
		if (is_admin()) $TMP_iselect.="<a href='$TMP_iURLadm' target=_blank><img src='".$dir_root."images/print.png' valign=-4 alt='' title=''></a>\n";
		else $TMP_iselect.="<img src='".$dir_root."images/print.png' valign=-4 alt='' title=''>\n";
		$TMP_iselect.="<input type=hidden name=V_dir value='$V_dir'><input type=hidden name=V_mod value='$V_mod'><input type=hidden name=V_idmod value='$V_idmod'><input type=hidden name=V_typePrint value='impreso'><input type=hidden name=V_send value=''><input type=hidden name=V_save value='x'> &nbsp; <select name=V_idimpreso onchange='javascript:document.IMPRESOS.submit();document.IMPRESOS.V_idimpreso.selectedIndex=0;'>";
		$TMP_iselect.="<option value='' SELECTED> imprimir documento de ....</option>\n";
		$TMP_icont=0;
		while($TMP_irow=sql_fetch_array($TMP_iresult, $RAD_dbi)) {
			$TMP_icont++;
			$TMP_id=$TMP_irow[idimpreso];
			if (trim($TMP_irow[condiciones])!="") {
				$TMP_muestraimpreso=true;
				$cadena="\$TMP_muestraimpreso=".$TMP_irow[condiciones];
				//$cadena="\$TMP_muestraimpreso=".ereg_replace("\$","\\\$",$TMP_irow[condiciones]);
				eval($cadena);
				if ($TMP_muestraimpreso==false) continue;
			}
			$TMP_iURL=$PHP_SELF."?func=$func&dbname=$dbname&par0=$par0&V_dir=$V_dir&V_mod=$V_mod&V_idmod=$V_idmod&V_typePrint=impreso&V_idimpreso=$TMP_id&V_send=&V_save=x";
			$TMP_iURLadm=$PHP_SELF."?func=edit&dbname=$dbname&par0=$TMP_id&V_dir=admin&V_mod=impresos";
			if ($TMP_i!="") $TMP_i.=" &nbsp; |";
			if (is_admin()) $TMP_i.=" &nbsp; <a href='$TMP_iURLadm' target=_blank><img src='".$dir_root."images/print.png' valign=-4 alt='' title=''></a> <a href='$TMP_iURL'>".$TMP_irow[impreso]."</a>\n";
			else $TMP_i.=" &nbsp; <a href='$TMP_iURL'><img src='".$dir_root."images/print.png' valign=-4 alt='' title=''> ".$TMP_irow[impreso]."</a>\n";
			$TMP_iselect.="<option value='$TMP_id'>".$TMP_irow[impreso]."</option>\n";
		}
		$TMP_iselect.="</select>\n";
		if ($TMP_icont>1) echo RAD_menu_off("<form action='".$PHP_SELF."' method=GET target=_blank name=IMPRESOS>".$TMP_iselect."</td></form>");
		else if (strlen($TMP_i)>0) echo RAD_menu_off("&nbsp;".$TMP_i."&nbsp;");
	}
        if (file_exists("modules/$V_dir/".$V_mod.".postmenu.php")) {
                $TMP=include ("modules/$V_dir/".$V_mod.".postmenu.php");
                if ($TMP!==true && $TMP!="1") echo $TMP;
        }
	echo "</TR>\n</TABLE>\n";
	if ($menubrowse || !isset($menubrowse)) {
		if ($func == "browse" || $func == "search" || $func == "search_js") {
			$to=$start+$limit;
			if ($to>$found) $to=$found;
			$paramForm=FORMparamInput("limit","start")."<INPUT TYPE=HIDDEN NAME=func VALUE=$func><INPUT TYPE=SUBMIT class=button VALUE="._DEF_NLSListButton.">";
			if ($menujump != false) {
				echo "<TABLE CELLPADDDING=0 CELLSPACING=0 BORDER=0 WIDTH=100%><TR>";
				echo"<TD ALIGN=LEFT nowrap class=menuon><FORM autocomplete=off ACTION=$PHP_SELF METHOD=GET>&nbsp; $FORMROI<INPUT name=start type=text size=3 value=$start> - <INPUT name=limit type=text size=3 value=$to> $paramForm &nbsp;</FORM></TD>";
				echo "</TR></TABLE>\n";
			}
			if ($menubletter != false && $func!="search") {
				$TMP_url=$PHP_SELF."?V_dir=$V_dir&V_mod=$V_mod&V_idmod=$V_idmod&dbname=$dbname&func=$func&subfunc=$subfunc&orderby=$orderby&V_roi=".urlencode($V_roi).$SESSION_SID;
				if ($dbname!=_DEF_dbname) $TMP_url.="&dbname=".$dbname;
				echo "<TABLE CELLPADDDING=0 CELLSPACING=0 BORDER=0 WIDTH=100%><TR><TD ALIGN=LEFT nowrap class=menuon>";
				for ($k=30; $k<128; $k++) $TMP_letter[CHR($k)]=CHR($k);
				$Ubletter=strtoupper($bletter);
				if ($bletter!="" && $bletter!="_") $TMP_letter[$Ubletter]="<b>".$TMP_letter[$Ubletter]."</b>";
				echo " <A class='menuoff' ACCESSKEY='A' TITLE='ALT+A' HREF=\"$TMP_url"."&bletter=a\"> ".$TMP_letter["A"]." </A>";
				echo "<A class='menuoff' TITLE='' HREF=\"$TMP_url"."&bletter=b\"> ".$TMP_letter["B"]." </A>";
				echo "<A class='menuoff' ACCESSKEY='C' TITLE='ALT+C' HREF=\"$TMP_url"."&bletter=c\"> ".$TMP_letter["C"]." </A>";
				echo "<A class='menuoff' ACCESSKEY='D' TITLE='ALT+D' HREF=\"$TMP_url"."&bletter=d\"> ".$TMP_letter["D"]." </A>";
				echo "<A class='menuoff' ACCESSKEY='E' TITLE='ALT+E' HREF=\"$TMP_url"."&bletter=e\"> ".$TMP_letter["E"]." </A>";
				echo "<A class='menuoff' ACCESSKEY='F' TITLE='ALT+F' HREF=\"$TMP_url"."&bletter=f\"> ".$TMP_letter["F"]." </A>";
				echo "<A class='menuoff' ACCESSKEY='G' TITLE='ALT+G' HREF=\"$TMP_url"."&bletter=g\"> ".$TMP_letter["G"]." </A>";
				echo "<A class='menuoff' TITLE='' HREF=\"$TMP_url"."&bletter=h\"> ".$TMP_letter["H"]." </A>"; // no se permiten ACCESSKEY de las funciones de menu mas habituales
				echo "<A class='menuoff' TITLE='' HREF=\"$TMP_url"."&bletter=i\"> ".$TMP_letter["I"]." </A>";
				echo "<A class='menuoff' ACCESSKEY='J' TITLE='ALT+J' HREF=\"$TMP_url"."&bletter=j\"> ".$TMP_letter["J"]." </A>";
				echo "<A class='menuoff' ACCESSKEY='K' TITLE='ALT+K' HREF=\"$TMP_url"."&bletter=k\"> ".$TMP_letter["K"]." </A>";
				echo "<A class='menuoff' TITLE='' HREF=\"$TMP_url"."&bletter=l\"> ".$TMP_letter["L"]." </A>";
				echo "<A class='menuoff' ACCESSKEY='M' TITLE='ALT+M' HREF=\"$TMP_url"."&bletter=m\"> ".$TMP_letter["M"]." </A>";
				echo "<A class='menuoff' TITLE='' HREF=\"$TMP_url"."&bletter=n\"> ".$TMP_letter["N"]." </A>";
				if ($bletter=="ñ") echo "<A class='menuoff' ACCESSKEY='Ñ' TITLE='ALT+Ñ' HREF=\"$TMP_url"."&bletter=".urlencode(ñ)."\"> <b>&Ntilde;</b> </A>";
				else echo "<A class='menuoff' ACCESSKEY='Ñ' TITLE='ALT+Ñ' HREF=\"$TMP_url"."&bletter=".urlencode(ñ)."\"> &Ntilde; </A>";
				echo "<A class='menuoff' ACCESSKEY='O' TITLE='ALT+O' HREF=\"$TMP_url"."&bletter=o\"> ".$TMP_letter["O"]." </A>";
				echo "<A class='menuoff' TITLE='' HREF=\"$TMP_url"."&bletter=p\"> ".$TMP_letter["P"]." </A>";
				echo "<A class='menuoff' ACCESSKEY='Q' TITLE='ALT+Q' HREF=\"$TMP_url"."&bletter=q\"> ".$TMP_letter["Q"]." </A>";
				echo "<A class='menuoff' ACCESSKEY='R' TITLE='ALT+R' HREF=\"$TMP_url"."&bletter=r\"> ".$TMP_letter["R"]." </A>";
				echo "<A class='menuoff' ACCESSKEY='S' TITLE='ALT+S' HREF=\"$TMP_url"."&bletter=s\"> ".$TMP_letter["S"]." </A>";
				echo "<A class='menuoff' ACCESSKEY='T' TITLE='ALT+T' HREF=\"$TMP_url"."&bletter=t\"> ".$TMP_letter["T"]." </A>";
				echo "<A class='menuoff' ACCESSKEY='U' TITLE='ALT+U' HREF=\"$TMP_url"."&bletter=u\"> ".$TMP_letter["U"]." </A>";
				echo "<A class='menuoff' TITLE='' HREF=\"$TMP_url"."&bletter=v\"> ".$TMP_letter["V"]." </A>";
				echo "<A class='menuoff' ACCESSKEY='W' TITLE='ALT+W' HREF=\"$TMP_url"."&bletter=w\"> ".$TMP_letter["W"]." </A>";
				echo "<A class='menuoff' ACCESSKEY='X' TITLE='ALT+X' HREF=\"$TMP_url"."&bletter=x\"> ".$TMP_letter["X"]." </A>";
				echo "<A class='menuoff' ACCESSKEY='Y' TITLE='ALT+Y' HREF=\"$TMP_url"."&bletter=y\"> ".$TMP_letter["Y"]." </A>";
				echo "<A class='menuoff' ACCESSKEY='Z' TITLE='ALT+Z' HREF=\"$TMP_url"."&bletter=z\"> ".$TMP_letter["Z"]." </A>";
				echo "<A class='menuoff' ACCESSKEY='0' TITLE='ALT+0' HREF=\"$TMP_url"."&bletter=0\"> ".$TMP_letter["0"]." </A>";
				echo "<A class='menuoff' ACCESSKEY='1' TITLE='ALT+1' HREF=\"$TMP_url"."&bletter=1\"> ".$TMP_letter["1"]." </A>";
				echo "<A class='menuoff' ACCESSKEY='2' TITLE='ALT+2' HREF=\"$TMP_url"."&bletter=2\"> ".$TMP_letter["2"]." </A>";
				echo "<A class='menuoff' ACCESSKEY='3' TITLE='ALT+3' HREF=\"$TMP_url"."&bletter=3\"> ".$TMP_letter["3"]." </A>";
				echo "<A class='menuoff' ACCESSKEY='4' TITLE='ALT+4' HREF=\"$TMP_url"."&bletter=4\"> ".$TMP_letter["4"]." </A>";
				echo "<A class='menuoff' ACCESSKEY='5' TITLE='ALT+5' HREF=\"$TMP_url"."&bletter=5\"> ".$TMP_letter["5"]." </A>";
				echo "<A class='menuoff' ACCESSKEY='6' TITLE='ALT+6' HREF=\"$TMP_url"."&bletter=6\"> ".$TMP_letter["6"]." </A>";
				echo "<A class='menuoff' ACCESSKEY='7' TITLE='ALT+7' HREF=\"$TMP_url"."&bletter=7\"> ".$TMP_letter["7"]." </A>";
				echo "<A class='menuoff' ACCESSKEY='8' TITLE='ALT+8' HREF=\"$TMP_url"."&bletter=8\"> ".$TMP_letter["8"]." </A>";
				echo "<A class='menuoff' ACCESSKEY='9' TITLE='ALT+9' HREF=\"$TMP_url"."&bletter=9\"> ".$TMP_letter["9"]." </A>";
				if ($bletter=="" || $bletter=="_") echo "<A class='menuoff' ACCESSKEY='_' TITLE='ALT+_' HREF=\"$TMP_url"."&bletter=_\"> <b>[".strtolower(_DEF_NLSShowAll)."]</b></A>";
				else echo "<A class='menuoff' ACCESSKEY='_' TITLE='ALT+_' HREF=\"$TMP_url"."&bletter=_\"> [".strtolower(_DEF_NLSShowAll)."]</A>";
				echo "</TD></TR></TABLE>\n";
			}
		}
	}
//	echo $RAD_menupages,"\n";
//	echo "\n</div>\n";
        if (file_exists("modules/$V_dir/".$V_mod.".submenu.php")) {
                $TMP=include ("modules/$V_dir/".$V_mod.".submenu.php");
                if ($TMP!==true && $TMP!="1") echo $TMP;
        }
}
if ($subbrowseSID=="" && $subfunc!="list" && $subfunc!="report") echo "<div style='font-weight:bold; text-align:center; color:maroon; border:0; margin:0; padding:0;' id='RAD_status'>".$RAD_status."</div>";
if ($func!="" && $func!="browse" && $func!="browsecalendar" && $func!="detail" && $func!="edit" && $func!="delete" && $func!="new" && $func!="insert" && $func!="update" && $func!="help" && $func!="search" && $func!="search_js" && $func!="searchform") {
	if (file_exists("modules/$V_dir/common.defaults.php")) {
		$TMP=include ("modules/$V_dir/common.defaults.php");
		if ($TMP!==true && $TMP!="1") echo $TMP;
		$TMP="";
	}
	if (file_exists("modules/$V_dir/".$V_mod.".defaults.php")) {
		$TMP="";
		$TMP=include_once ("modules/$V_dir/".$V_mod.".defaults.php");
		if ($TMP!==true && $TMP!="1") echo $TMP;
		$TMP="";
	}
}
?>
