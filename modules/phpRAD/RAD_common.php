<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}

if ($RAD_html5=="") $RAD_html5=_DEF_html5;

include_once("modules/phpRAD/RAD_common.lib.php");
/////////////////////////////////////////////////////////////////////////////////////
if (!function_exists("RAD_menu_on")) {
  //--------------------------------------------------------------------------------------
  function RAD_menu_on($text) {
	if (defined('_DEF_DIR_ROOT')) $dir_root = _DEF_DIR_ROOT;
	else $dir_root = "";
  
	$TMP_theme=getSessionVar("SESSION_theme");
	if (file_exists("themes/".$TMP_theme."/et0on.gif")) $TMP_imget0on="themes/".$TMP_theme."/et0on.gif";
	else $TMP_imget0on="images/et0on.gif";
	if (file_exists("themes/".$TMP_theme."/eton0.gif")) $TMP_imgeton0="themes/".$TMP_theme."/eton0.gif";
	else $TMP_imgeton0="images/eton0.gif";
	if (file_exists("themes/".$TMP_theme."/etbgon.gif")) $TMP_imgetbgon="themes/".$TMP_theme."/etbgon.gif";
	else $TMP_imgetbgon="images/etbgon.gif";
	if (ereg("PDA",$TMP_theme) || substr(_DEF_VERSION_RAD,0,1)>1) {
		return "<TD align=center nowrap class=menuon>$text</TD>\n";
	}
	$TMP="<td id='et0on'><img id='et0on' src='".$dir_root.$TMP_imget0on."'></td>\n";
	$TMP.="<TD align=center nowrap background='".$dir_root.$TMP_imgetbgon."' class=menuon>$text</TD>\n";
	$TMP.="<td id='eton0'><img id='eton0' src='".$dir_root.$TMP_imgeton0."'></td>\n";
	return $TMP;
  }
  //--------------------------------------------------------------------------------------
  function RAD_menu_off($text) {
	if (defined('_DEF_DIR_ROOT')) $dir_root = _DEF_DIR_ROOT;
	else $dir_root = "";
	
	$TMP_theme=getSessionVar("SESSION_theme");
	if (file_exists("themes/".$TMP_theme."/et0off.gif")) $TMP_imget0off="themes/".$TMP_theme."/et0off.gif";
	else $TMP_imget0off="images/et0off.gif";
	if (file_exists("themes/".$TMP_theme."/etoff0.gif")) $TMP_imgetoff0="themes/".$TMP_theme."/etoff0.gif";
	else $TMP_imgetoff0="images/etoff0.gif";
	if (file_exists("themes/".$TMP_theme."/etbgoff.gif")) $TMP_imgetbgoff="themes/".$TMP_theme."/etbgoff.gif";
	else $TMP_imgetbgoff="images/etbgoff.gif";
	if (ereg("PDA",$TMP_theme) || substr(_DEF_VERSION_RAD,0,1)>1) {
		return "<TD align=center nowrap class=menuoff>$text</TD>\n";
	}
	$TMP="<td id='et0off'><img id='et0off' src='".$dir_root.$TMP_imget0off."'></td>\n";
	$TMP.="<TD align=center nowrap background='".$dir_root.$TMP_imgetbgoff."' class=menuoff>$text</TD>\n";
	$TMP.="<td id='etoff0'><img id='etoff0' src='".$dir_root.$TMP_imgetoff0."'></td>\n";
	return $TMP;
  }
  //--------------------------------------------------------------------------------------
  function RAD_submenu_on($text) {
	$TMP_theme=getSessionVar("SESSION_theme");
	if (file_exists("themes/".$TMP_theme."/et0on.gif")) $TMP_imget0on="themes/".$TMP_theme."/et0on.gif";
	else $TMP_imget0on="images/et0on.gif";
	if (file_exists("themes/".$TMP_theme."/eton0.gif")) $TMP_imgeton0="themes/".$TMP_theme."/eton0.gif";
	else $TMP_imgeton0="images/eton0.gif";
	if (file_exists("themes/".$TMP_theme."/etbgon.gif")) $TMP_imgetbgon="themes/".$TMP_theme."/etbgon.gif";
	if (ereg("PDA",$TMP_theme) || substr(_DEF_VERSION_RAD,0,1)>1) {
		return "<TD align=center nowrap class=menuon>$text</TD>\n";
	}
	$TMP="<td id='subet0on'><img id='subet0on' src='".$TMP_imget0on."'></td>\n";
	$TMP.="<TD align=center nowrap background='".$TMP_imgetbgon."' class=submenuon>$text</TD>\n";
	$TMP.="<td id='subeton0'><img id='subeton0' src='".$TMP_imgeton0."'></td>\n";
	return $TMP;
  }
  //--------------------------------------------------------------------------------------
  function RAD_submenu_off($text) {	
	if (defined('_DEF_DIR_ROOT')) $dir_root = _DEF_DIR_ROOT;
	else $dir_root = "";
	
	$TMP_theme=getSessionVar("SESSION_theme");
	if (file_exists("themes/".$TMP_theme."/et0off.gif")) $TMP_imget0off="themes/".$TMP_theme."/et0off.gif";
	else $TMP_imget0off="images/et0off.gif";
	if (file_exists("themes/".$TMP_theme."/etoff0.gif")) $TMP_imgetoff0="themes/".$TMP_theme."/etoff0.gif";
	else $TMP_imgetoff0="images/etoff0.gif";
	if (file_exists("themes/".$TMP_theme."/etbgoff.gif")) $TMP_imgetbgoff="themes/".$TMP_theme."/etbgoff.gif";
	else $TMP_imgetbgon="images/etbgoff.gif";
	if (ereg("PDA",$TMP_theme) || substr(_DEF_VERSION_RAD,0,1)>1) {
		return "<TD align=center nowrap class=menuoff>$text</TD>\n";
	}
	$TMP="<td id='subet0off'><img id='subet0off' src='".$dir_root.$TMP_imget0off."'></td>\n";
	$TMP.="<TD align=center nowrap background='".$dir_root.$TMP_imgetbgoff."' class=submenuoff>$text</TD>\n";
	$TMP.="<td id='subetoff0'><img id='subetoff0' src='".$dir_root.$TMP_imgetoff0."'></td>\n";
	return $TMP;
  }
}
/////////////////////////////////////////////////////////////////////////////////////

if ($V0_par0!="" && $par0=="") $par0=$V0_par0;

if (file_exists("modules/$V_dir/common.app.php")) include_once ("modules/$V_dir/common.app.php");
if (file_exists("modules/$V_dir/common.".$V_mod.".php")) include_once ("modules/$V_dir/common.".$V_mod.".php");
if ($V_mod!=$tablename) if (file_exists("modules/$V_dir/common.".$tablename.".php")) include_once ("modules/$V_dir/common.".$tablename.".php");
if (file_exists("modules/$V_dir/".$V_mod.".common.php")) include_once ("modules/$V_dir/".$V_mod.".common.php");
if ($V_mod!=$tablename)  if (file_exists("modules/$V_dir/".$tablename.".common.php")) include_once ("modules/$V_dir/".$tablename.".common.php");

if (file_exists("modules/".$V_dir."/language/lang-".$HTTP_SESSION_VARS["SESSION_lang"].".php")) {
	include("modules/".$V_dir."/language/lang-".$HTTP_SESSION_VARS["SESSION_lang"].".php");
}
if (file_exists("modules/".$V_dir."/language/lang-".$HTTP_SESSION_VARS["SESSION_lang"].".".$V_mod.".php")) {
	include("modules/".$V_dir."/language/lang-".$HTTP_SESSION_VARS["SESSION_lang"].".".$V_mod.".php");
}

if (_DEF_appname!="" && _DEF_appname!="_DEF_appname") {
	if (file_exists("modules/$V_dir/common.app."._DEF_appname.".php")) include_once ("modules/$V_dir/common.app."._DEF_appname.".php");
	if (file_exists("modules/$V_dir/common.".$V_mod."."._DEF_appname.".php")) include_once ("modules/$V_dir/common.".$V_mod."."._DEF_appname.".php");
	if ($V_mod!=$tablename) if (file_exists("modules/$V_dir/common.".$tablename."."._DEF_appname.".php")) include_once ("modules/$V_dir/common.".$tablename."."._DEF_appname.".php");
}

if (_DEF_appinstance!="" && _DEF_appinstance!="_DEF_appinstance") {
	if (file_exists("modules/$V_dir/common.app."._DEF_appinstance.".php")) include_once ("modules/$V_dir/common.app."._DEF_appinstance.".php");
}

if ($dbtype=="") $dbtype = strtolower(_DEF_dbtype);

$A_languages=explode(",",_DEF_DBLANGUAGES);
$TMP_lang=$HTTP_SESSION_VARS["SESSION_lang"];
if (count($A_languages)>0 && $tablename!="" && $TMP_lang!="" &&  ($TMP_lang!=_DEF_LANGUAGE || _DEF_ALLCOLUMNSDBLANGUAGES=="2") ) {
	if ($dbtype=="oracle") $TMP_cmd = "SELECT * FROM USER_TAB_COLUMNS WHERE table_Name='".$tablename."'";
	else $TMP_cmd = " describe $tablename";

	$TMP_result = sql_query($TMP_cmd, $RAD_dbi);
	while($TMP_row = sql_fetch_array($TMP_result,$RAD_dbi)) {
// foreach ($TMP_row as $TMP_idx=>$TMP_val) echo "\n<! $TMP_idx = $TMP_val >";
 		if ($dbtype=="oracle") $TMP_campo=$TMP_row[1];
 		else $TMP_campo=$TMP_row[0];
		
		if ($findex[$TMP_campo]>0) continue;
		if (strlen($TMP_lang)>=strlen($TMP_campo)) continue;
		if (!strpos($TMP_campo,"_")>0) continue;
		$TMP_suf=substr($TMP_campo,strpos($TMP_campo,"_")+1);
		if ($TMP_suf=="") continue;
		$A_tmp=explode("_",$TMP_campo);
		$TMP_suf=$A_tmp[count($A_tmp)-1];
		
		if (strtoupper($TMP_suf)==strtoupper($TMP_lang) || (_DEF_ALLCOLUMNSDBLANGUAGES=="2" && ereg(",".$TMP_suf.",",","._DEF_DBLANGUAGES.","))) {
			$TMP_field=substr($TMP_campo,0,strlen($TMP_campo)-strlen($TMP_suf)-1);
			$TMP_numfield=$findex[$TMP_field];
			if ($TMP_numfield>0) {
				//if (_DEF_ALLCOLUMNSDBLANGUAGES!="1" || $func=="" || $func=="browse" || $func=="search") { // Cambia campo de idioma original por campo de nuevo idioma $TMP_suf en browse
				if (_DEF_ALLCOLUMNSDBLANGUAGES!="1" && _DEF_ALLCOLUMNSDBLANGUAGES!="2") { // Cambia campo de idioma original por campo de nuevo idioma $TMP_suf en browse
					$fields[$TMP_numfield]->name=$TMP_campo;
					$fields[$TMP_numfield]->title=$fields[$TMP_numfield]->title." [$TMP_suf]";
					$findex[$TMP_campo]=$TMP_numfield;
					unset($findex[$TMP_field]);
				} else { // Duplica campo de otro idioma en funcion detail edit insert update new
					$TMP_props="";
					foreach ($fields[$TMP_numfield] as $attr=>$val) {
						if ($attr=="name") $TMP_props.=$attr."=\"".$TMP_campo."\",";
						else if ($attr=="canbenull") $TMP_props.=$attr."=true,";
						else if ($attr=="title") $TMP_props.=$attr."=\"".$val." [".$TMP_suf."]\",";
						else if (is_string($val)) $TMP_props.=$attr."=\"".$val."\",";
						else if ($val==1) $TMP_props.=$attr."=true,";
						else $TMP_props.=$attr."=false,";
					}
					$TMP_props=substr($TMP_props,0,strlen($TMP_props)-1);
					RAD_addField($TMP_numfield+1, $TMP_campo, $TMP_props);
				}
			}
		}
	}
}

for ($i = 0; $i < $numf; $i++) { 
	if (getSessionVar("SESSION_NLS".$fields[$i]->title)!="") $fields[$i]->title=getSessionVar("SESSION_NLS".$fields[$i]->title);
	if (getSessionVar("SESSION_NLS".$fields[$i]->help)!="") $fields[$i]->help=getSessionVar("SESSION_NLS".$fields[$i]->help);
}

//include_once("functions.php");
for ($i = 0; $i < $numf; $i++) { 
	if (eregi("ajax",$fields[$i]->dtype)) {
		include_once("functions_ajax.php"); // Ajax: Incluye javascript en el head
		break;
	}
}

if ($hostname=="" || $dbtype=="") {
	$hostname = _DEF_dbhost;
	$dbusername = _DEF_dbuname;
	$dbpassword = _DEF_dbpass;
	$dbtype = strtolower(_DEF_dbtype);
}
if(_DEF_dbreadonly=="1") {
	$RAD_menunew="x";
	$RAD_menuedit="x";
	$RAD_menudelete="x";
	$RAD_menuinsert="x";
	$RAD_menuupdate="x";
	if ($func=="new" || $func=="insert" || $func=="edit" || $func=="update") error("DBReadOnly ".$dbname.""); 
}
if ($RAD_defaultfilter!="") {
	if ($defaultfilter=="") $defaultfilter=$RAD_defaultfilter;
	else $defaultfilter="(".$defaultfilter.") AND (".$RAD_defaultfilter.")";
}

if ($func!="edit" && $func!="detail" && $func!="update" && _DEF_REMEMBER_LAP!="1") $V_lap="";

@ini_set("session.use_trans_sid", 1);
@ini_set("session.use_cookies", 0);
//ini_set('session.use_cookies', 1);
switch ($seclevel) {
        case 'High':
            $lifetime = 0;
            break;
        case 'Medium':
            $lifetime = 8 * 86400; // more than a week
            break;
        case 'Low':
            // Session lasts unlimited number of days (25 years)
            $lifetime = 788940000;
            break;
    }
//ini_set('session.cookie_lifetime', $lifetime);
ini_set("session.name", "PHPSESSID");
ini_set("session.auto_start", 0);
//ini_set("session.gc_maxlifetime", 1800); // 30 minutes Inactivity timeout for user sessions --- Mirar session.php
ini_set("magic_quotes_gpc", 1);
ini_set("magic_quotes_runtime", 0);
ini_set("register_globals", 1);

if ( (!(headers_sent())) && ($func!="print") ) {
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
	header("Cache-Control: no-store, no-cache, private, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
}
if ($titleNLS=="") $titleNLS=$title;
if ($RAD_NLS[$title]!="") $titleNLS=$RAD_NLS[$title];
if (getSessionVar("SESSION_NLS".$title)!="") $titleNLS=getSessionVar("SESSION_NLS".$title);
if ($subbrowseSID=="") echo "<SCRIPT LANGUAGE=\"JavaScript\">\ndocument.title='".RAD_UTF_to_Unicode(str_replace("\n","",str_replace("\r","",RAD_convertHTML2TXT($titleNLS)))).". '+document.title;\n</SCRIPT>\n";
//echo "<SCRIPT LANGUAGE=\"JavaScript\">\ndocument.title=document.title+'. ".str_replace("\n","",str_replace("\r","",RAD_convertHTML2TXT($titleNLS)))."';\n</SCRIPT>\n";

if (isset($HTTP_POST_VARS['SESSION_user'])) $HTTP_POST_VARS['SESSION_user'] ="";
if (isset($HTTP_POST_VARS['SESSION_profiles'])) $HTTP_POST_VARS['SESSION_profiles'] ="";
if (isset($HTTP_POST_VARS['security'])) $HTTP_POST_VARS['security'] ="";
if (isset($HTTP_GET_VARS['SESSION_user'])) $HTTP_GET_VARS['SESSION_user'] ="";
if (isset($HTTP_GET_VARS['SESSION_profiles'])) $HTTP_GET_VARS['SESSION_profiles'] ="";
if (isset($HTTP_GET_VARS['security'])) $HTTP_GET_VARS['security'] ="";

if (!isset($func)) $func = "";
if ($RAD_defaultfunc!="") $defaultfunc = $RAD_defaultfunc;
if ($func=="detail" && $menudetail == false) { $func = "edit"; }
if ($func=="edit" && $menuedit == false) { $func = "print"; }
if ($func=="print") { $func = "browse"; $subfunc = "list"; $menuoff="x"; }
if (empty($defaultfunc)) $defaultfunc="browse";
if ($func=="") $func=$defaultfunc;
if ($func=="search_js") { $footeroff = "x"; $headeroff = "x"; }
if (($func=="search")&&($V_typePrint!=""||$V_typeSend!="")) { $footeroff="x"; $headeroff="x"; $blocksoff="x"; $menuoff="x"; }

if (!isset($start)) $start = "";
if (isset($RAD_rows)) $rows_limit=$RAD_rows;
if (isset($RAD_browsetype)) $browsetype=$RAD_browsetype;
if (isset($RAD_rowsinsert)) $V_rowsinsert=$RAD_rowsinsert;
if (isset($RAD_browsetype) && isset($RAD_rowsinsert)) {
	for($ki=0; $ki<count($findex); $ki++) {
		if ($fields[$findex[$ki]]->browsable==true) {
			if ($fields[$ki]->dtype!="auto_increment") $fields[$ki]->browsedit=true;
		}
	}
}
if (!isset($SID)) $SID = 'SID';

if ($dbname!="") {
    $URLROI.="&dbname=".urlencode($dbname);
    $FORMROI.="<input type=hidden name=dbname value='".$dbname."'>";
}
if ($searchfield!="") {
    $URLROI.="&searchfield=".urlencode($searchfield);
    $FORMROI.="<input type=hidden name=searchfield value='".urlencode($searchfield)."'>";
}
if ($searchfieldTO!="") {
    $URLROI.="&searchfieldTO=".urlencode($searchfieldTO);
    $FORMROI.="<input type=hidden name=searchfieldTO value='".urlencode($searchfieldTO)."'>";
}
if ($searchfieldlit!="") {
    $URLROI.="&searchfieldlit=".urlencode($searchfieldlit);
    $FORMROI.="<input type=hidden name=searchfieldlit value='".urlencode($searchfieldlit)."'>";
}
$TMP_A_prf=explode(",",getSessionVar("SESSION_profiles").",");
foreach($TMP_A_prf as $TMP_k=>$TMP_prf) {
	if ($TMP_prf=="") continue;
	if ($RAD_prfnonew!="" && ereg(",".$TMP_prf."," , ",".$RAD_prfnonew.",")) $RAD_menunew="x";
	if ($RAD_prfnoedit!="" && ereg(",".$TMP_prf."," , ",".$RAD_prfnoedit.",")) $RAD_menuedit="x";
	if ($RAD_prfnodelete!="" && ereg(",".$TMP_prf."," , ",".$RAD_prfnodelete.",")) $RAD_menudelete="x";
	if ($RAD_prfnodetail!="" && ereg(",".$TMP_prf."," , ",".$RAD_prfnodetail.",")) $RAD_menudetail="x";
	if ($RAD_prfnoninsert!="" && ereg(",".$TMP_prf."," , ",".$RAD_prfnoinsert.",")) $RAD_menuinsert="x";
	if ($RAD_prfnoupdate!="" && ereg(",".$TMP_prf."," , ",".$RAD_prfnoupdate.",")) $RAD_menuupdate="x";
	if ($RAD_prfnoprint!="" && ereg(",".$TMP_prf."," , ",".$RAD_prfnoprint.",")) $RAD_menuprint="x";
	if ($RAD_prfnosend!="" && ereg(",".$TMP_prf."," , ",".$RAD_prfnosend.",")) $RAD_menusend="x";
	if ($RAD_prfnobrowse!="" && ereg(",".$TMP_prf."," , ",".$RAD_prfnobrowse.",")) $RAD_menubrowse="x";
	if ($RAD_prfnocalendar!="" && ereg(",".$TMP_prf."," , ",".$RAD_prfnocalendar.",")) $RAD_menucalendar="x";
	if ($RAD_prfnobackup!="" && ereg(",".$TMP_prf."," , ",".$RAD_prfnobackup.",")) $RAD_menubackup="x";
	if ($RAD_prfnosearch!="" && ereg(",".$TMP_prf."," , ",".$RAD_prfnosearch.",")) $RAD_menusearch="x";
	if ($RAD_prfnominisearch!="" && ereg(",".$TMP_prf."," , ",".$RAD_prfnonimisearch.",")) $RAD_menuminisearch="x";
	if ($RAD_prfnoreport!="" && ereg(",".$TMP_prf."," , ",".$RAD_prfnoreport.",")) $RAD_menureport="x";
	if ($RAD_prfnojump!="" && ereg(",".$TMP_prf."," , ",".$RAD_prfnojump.",")) $RAD_menujump="x";
	if ($RAD_prfnocursor!="" && ereg(",".$TMP_prf."," , ",".$RAD_prfnocursor.",")) $RAD_menucursor="x";
	if ($RAD_prfnobletter!="" && ereg(",".$TMP_prf."," , ",".$RAD_prfnobltter.",")) $RAD_menubletter="x";
}
if ($RAD_menunew!="") $menunew=false;
if ($RAD_menuedit!="") $menuedit=false;
if ($RAD_menudelete!="") $menudelete=false;
if ($RAD_menudetail!="") $menudetail=false;
if ($RAD_menuinsert!="") $menuinsert=false;
if ($RAD_menuupdate!="") $menuupdate=false;
if ($RAD_menuprint!="") $menuprint=false;
if ($RAD_menusend!="") $menusend=false;
if ($RAD_menubrowse!="") $menubrowse=false;
if ($RAD_menucalendar!="") $menucalendar=false;
if ($RAD_menubackup!="") $menubackup=false;
if ($RAD_menusearch!="") $menusearch=false;
if ($RAD_menuminisearch!="") $menuminisearch=false;
if ($RAD_menureport!="") $menureport=false;
if ($RAD_menujump!="") $menujump=false;
if ($RAD_menucursor!="") $menucursor=false;
if ($RAD_menubletter!="") $menubletter=false;

global $V_tablename;
$V_tablename=$tablename; // nombre original de la tabla del modulo
if ($RAD_tablename!="") $tablename=$RAD_tablename; // RAD_tablename es para que el modulo trabaje sobre otra tabla similar

for ($i = 0; $i < $numf; $i++) {
	if ($RAD_fieldoff!="") if (ereg(",".$fields[$i]->name.",",",".$RAD_fieldoff.",")) RAD_delField($fields[$i]->name);
	if ($func=="new" && $fields[$i]->nonew==true) continue;
	if ($func=="edit" && $fields[$i]->noedit==true) continue;
	if ($func=="detail" && $fields[$i]->nodetail==true) continue;
	if (($func=="insert"||$func=="update") && ($fields[$i]->type=="num"||$fields[$i]->dtype=="num")) {
		${"V0_".$fields[$i]->name}=RAD_str2num(${"V0_".$fields[$i]->name});
		//if (!is_numeric(${"V0_".$fields[$i]->name})) ${"V0_".$fields[$i]->name}=RAD_str2num(${"V0_".$fields[$i]->name});
	}
	if ($fields[$i]->overlap=="") continue;
	if ($TMP_overlaptextc[$fields[$i]->overlap]=="") {
		$TMP_contVlap++;
		if (($V_lap=="" && !ereg(",".$TMP_contVlap."," , ",".$RAD_lapoff.",") && !ereg(",".$fields[$i]->overlap."," , ",".$RAD_lapoff.","))) $V_lap=$fields[$i]->overlap; 
		if ($V_lap==$TMP_contVlap) $V_lap=$fields[$i]->overlap; // Permit overlap number or string
		if (($V_lap==$fields[$i]->overlap) && ($V_lap!="") && 
			(ereg(",".$TMP_contVlap."," , ",".$RAD_lapoff.",")||(ereg(",".$V_lap."," , ",".$RAD_lapoff.",")))) 
			error("Pestaña $V_lap prohibida"); 
		$TMP_overlaptextc[$fields[$i]->overlap]=$TMP_contVlap;
	}
}

if ($V_lap!="") {
    $V_lap=urldecode($V_lap);
    $URLROI.="&V_lap=".urlencode($V_lap);
    $FORMROI.="<input type=hidden name=V_lap value='".urlencode($V_lap)."'>";
}
if ($lapoff!="") {
    $lapoff=urldecode($lapoff);
    $URLROI.="&lapoff=".urlencode($lapoff);
    $FORMROI.="<input type=hidden name=lapoff value='".urlencode($lapoff)."'>";
}
if ($V_posparent!="") {
    $URLROI.="&V_posparent=".urlencode($V_posparent);
    $FORMROI.="<input type=hidden name=V_posparent value='".urlencode($V_posparent)."'>";
}
if (substr(strtoupper($V_roi),0,5)=="WHERE") $V_roi=substr($V_roi,6);
if ($V_roi!="") {
	$URLROI.="&V_roi=".urlencode($V_roi);
	if (strpos($V_roi,"'")>0) $FORMROI.="<input type=hidden name=V_roi value=\"$V_roi\">";
	else $FORMROI.="<input type=hidden name=V_roi value='$V_roi'>";

	$TMP_defaultfilterya=false;
	$TMP_pos=strpos($V_roi,"=");
	if ($TMP_pos>0) {
	    $V_roi_fieldname=substr($V_roi,0,$TMP_pos);
	    $V_roi_value=substr($V_roi,$TMP_pos+1);
	    for ($i = 0; $i < $numf; $i++) {
    		if ($fields[$i]->name==$V_roi_fieldname) {
    		    $fields[$i] -> readonly = true;
    		    $fields[$i] -> browsable = false;
		    $V_roi_value = str_replace("'","",$V_roi_value);
		    if ($fields[$i] -> dtype == "plistm" 
			|| $fields[$i] -> dtype == "checkboxdbm" || $fields[$i] -> dtype == "checkboxm" 
			|| $fields[$i] -> dtype == "plistdbm" || $fields[$i] -> dtype == "popupm" 
			|| $fields[$i] -> dtype == "popupdbm" || $fields[$i] -> dtype == "plistdbmtree") {
				$TMP_V_roi_value=$V_roi_value;
				$V_roi_value=",".$V_roi_value.",";
				if ($func!="search" && $func!="search_js") {
					if (trim($defaultfilter)!="") $defaultfilter.=" AND ";
					//$defaultfilter=$tablename.".$V_roi_fieldname LiKE '%".$V_roi_value."%'";
					$defaultfilter.="(".$tablename.".$V_roi_fieldname LiKE '%".$V_roi_value."%' OR ".$tablename.".$V_roi_fieldname ='".$TMP_V_roi_value."')";
					$TMP_defaultfilterya=true;
				}
		    }
		    $fields[$i] -> vdefault = str_replace("\"","",$V_roi_value);
		    if ($fields[$i]->dtype=="plistdbm" || $fields[$i]->dtype=="checkboxdbm") $fields[$i] -> dtype = "popupdbm";
		    else if ($fields[$i]->dtype=="plistdb" || $fields[$i]->dtype=="checkboxdb") $fields[$i] -> dtype = "popupdb";
		}
	    }
	}
	if (!($TMP_defaultfilterya) && ($findex[$V_roi_fieldname]!="" || $findex[$V_roi_fieldname]=="0")) {
		if ($defaultfilter!="") $defaultfilter.=" AND ";
		$defaultfilter.=$tablename.".".$V_roi;
	}
}
if ($V_roi2!="") {
	$URLROI.="&V_roi2=".urlencode($V_roi2);
	if (strpos($V_roi2,"'")>0) $FORMROI.="<input type=hidden name=V_roi2 value=\"$V_roi2\">";
	else $FORMROI.="<input type=hidden name=V_roi2 value='$V_roi2'>";

	$TMP_defaultfilterya=false;
	$TMP_pos=strpos($V_roi2,"=");
	if ($TMP_pos>0) {
	    $V_roi2_fieldname=substr($V_roi2,0,$TMP_pos);
	    $V_roi2_value=substr($V_roi2,$TMP_pos+1);
	    for ($i = 0; $i < $numf; $i++) {
    		if ($fields[$i]->name==$V_roi2_fieldname) {
    		    $fields[$i] -> readonly = true;
    		    $fields[$i] -> browsable = false;
		    $V_roi2_value = str_replace("'","",$V_roi2_value);
		    if ($fields[$i] -> dtype == "plistm" 
			|| $fields[$i] -> dtype == "checkboxdbm" || $fields[$i] -> dtype == "checkboxm"
			|| $fields[$i] -> dtype == "plistdbm" || $fields[$i] -> dtype == "popupm" 
			|| $fields[$i] -> dtype == "popupdbm" || $fields[$i] -> dtype == "plistdbmtree") {
				$TMP_V_roi2_value=",".$V_roi2_value.",";
				$V_roi2_value=",".$V_roi2_value.",";
				if ($func!="search" && $func!="search_js") {
					if ($defaultfilter!="") $defaultfilter.=" AND ";
					//$defaultfilter=$tablename.".$V_roi2_fieldname LIKE '%".$V_roi2_value."%'";
					$defaultfilter.="(".$tablename.".$V_roi2_fieldname LIKE '%".$V_roi2_value."%' OR ".$tablename.".$V_roi2_fieldname ='".$TMP_V_roi2_value."')";
					$TMP_defaultfilterya=true;
				}
		    }
		    $fields[$i] -> vdefault = str_replace("\"","",$V_roi2_value);
		    if ($fields[$i]->dtype=="plistdbm" || $fields[$i]->dtype=="checkboxdbm") $fields[$i] -> dtype = "popupdbm";
		    else if ($fields[$i]->dtype=="plistdb" || $fields[$i]->dtype=="checkboxdb") $fields[$i] -> dtype = "popupdb";
		}
	    }
	}
	if (!($TMP_defaultfilterya) && ($findex[$V_roi2_fieldname]!="" || $findex[$V_roi2_fieldname]=="0")) {
		if ($defaultfilter!="") $defaultfilter.=" AND ";
		$defaultfilter.=$tablename.".".$V_roi2;
	}
}


if ($V_dir!="") {
	$URLROI.="&V_dir=".urlencode($V_dir);
	$FORMROI.="<input type=hidden name=V_dir value='".$V_dir."'>";
}
if ($V_mod!="") {
	$URLROI.="&V_mod=".urlencode($V_mod);
	$FORMROI.="<input type=hidden name=V_mod value='".$V_mod."'>";
}
if ($V_idmod!="") {
	$URLROI.="&V_idmod=".urlencode($V_idmod);
	$FORMROI.="<input type=hidden name=V_idmod value='".$V_idmod."'>";
}
if ($PHPSESSID!="") {
	$URLROI.=$SESSION_SID;
	$FORMROI.="<input type=hidden name=PHPSESSID value='".$PHPSESSID."'>";
}
if ($subbrowseSID!="") {
	$URLROI.="&subbrowse=x";
	$FORMROI.="<input type=hidden name=subbrowse value='x'>";
}
if ($menuoff!="") {
	if ($subbrowseSID=="") $URLROI.="&menuoff=".urlencode($menuoff);
	$FORMROI.="<input type=hidden name=menuoff value='".$menuoff."'>";
}
if ($blocksoff!="") {
	$URLROI.="&blocksoff=".urlencode($blocksoff);
	$FORMROI.="<input type=hidden name=blocksoff value='".$blocksoff."'>";
}
if ($subfunc!="") {
	$URLROI.="&subfunc=".urlencode($subfunc);
	$FORMROI.="<input type=hidden name=subfunc value='".$subfunc."'>";
}
if ($bletter!="") {
	$URLROI.="&bletter=".urlencode($bletter);
	$FORMROI.="<input type=hidden name=bletter value='".$bletter."'>";
}
$URLROIsearch=$URLROI;
if ($MaxSearchFields>0 && $func=="search") {
    $URLROIsearch.="&MaxSearchFields=".urlencode($MaxSearchFields);
    $URLROIsearch.="&func=search";
    for($ki=0; $ki<$MaxSearchFields; $ki++) {
	$TMP_fieldname=${"searchfield".$ki};
	if (${"operator".$ki}!="") $URLROIsearch.="&operator".$ki."=".urlencode(${"operator".$ki});
	if (${"searchvalue".$ki}!="") $URLROIsearch.="&searchvalue".$ki."=".urlencode(${"searchvalue".$ki});
	else if (${$TMP_fieldname}!="") $URLROIsearch.="&".$TMP_fieldname."=".urlencode(${$TMP_fieldname});
	if (${"searchfield".$ki}!="") $URLROIsearch.="&searchfield".$ki."=".urlencode(${"searchfield".$ki});
    }
}
$tabURLROI=$URLROI;
if ($orderby!="") {
    $URLROI.="&orderby=".urlencode($orderby);
    $FORMROI.="<input type=hidden name=orderby value='".$orderby."'>";
}
if ($start!="") {
	$URLROI.="&start=$start";
	$FORMROI.="<input type=hidden name=start value='".$start."'>";
}

//if (!isset($)) $ = "";
if (!isset($param)) $param = "";
if (!isset($par0)) $par0 = "";
if (!isset($idn)) $idn = "";
if (!isset($desc)) $desc = "";
if (!isset($menuoff)) $menuoff = "";
if (!isset($offset)) $offset = "";
if (!isset($paramForm)) $paramForm = "";
if (!isset($hiddenidnames)) $hiddenidnames = "";
if (!isset($fieldedit)) $fieldedit = "";
if (!isset($headeroff)) $headeroff = "";
if (!isset($subfunc)) $subfunc = "";
if (!isset($htmllink)) $htmllink = "";
if (!isset($htmllinkend)) $htmllinkend = "";
if (!isset($htmlEdit)) $htmlEdit = "";
if (!isset($htmlEditEnd)) $htmlEditEnd = "";
if (!isset($SESSION_RAD_count)) $SESSION_RAD_count = 0;


if ($func=="off" || $func=="logoff") {
	$SESSION_user="";
	$SESSION_profiles="";
	$SESSION_RAD_count=0;
	$HTTP_SESSION_VARS['SESSION_user'] ="";
	$HTTP_SESSION_VARS['SESSION_profiles'] ="";
	$HTTP_SESSION_VARS['SESSION_RAD_count'] ="";
	session_unregister("SESSION_user");
	session_unregister("SESSION_profiles");
	session_unregister("SESSION_RAD_count");
}
$SESSION_RAD_count++;


if ($security !="0") { 
	setSessionVar("SESSION_user",$SESSION_user,0);
	setSessionVar("SESSION_profiles",$SESSION_profiles,0);
	setSessionVar("SESSION_RAD_count",$SESSION_RAD_count,0);
	global $SESSION_user;
	global $SESSION_profiles;
	global $SESSION_RAD_count;
	check_security($security); 
}

// character that separate database backup names
$dbnameSeparator="_";

if ($RAD_textConfirmDelete=="" && _DEF_textConfirmDelete!="" && _DEF_textConfirmDelete!="_DEF_textConfirmDelete") $RAD_textConfirmDelete=_DEF_textConfirmDelete;

if ($func=="detail" || $func=="edit") {
	if (RAD_checkAutSQLDelete()!="") $menudelete=false;
}
/////if ($RAD_nocheckDBFields=="") RAD_checkDBFields();

//-------------------------------------------------------------------------------------------------
if ($V_prevfunc=="minisearch" && $operator0=="LIKE" && $findex[$searchfield0]>0) {
	if (eregi("db",$fields[$findex[$searchfield0]]->dtype)) {
		list($ptablename, $pfname, $pftitle, $pfilter, $pfieldparent, $porder, $pgroup)=dbdecode($fields[$findex[$searchfield0]]);
		$operator0="IN";
		$A_ftitle=explode(",",$pftitle.",");
		$A_fname=explode(",",$pfname.",");
		$TMP_cmd="SELECT ".$A_fname[0]." From $ptablename where ".$A_ftitle[0]." like '%".$searchvalue0."%'";
		if ($pfilter!="") $TMP_cmd.=" and ".$pfilter;
		$TMP_res=sql_query($TMP_cmd, $RAD_dbi);
		$searchvalue0="";
		while($TMP_row=sql_fetch_array($TMP_res, $RAD_dbi)) {
			if ($searchvalue0!="") $searchvalue0.=",";
			$searchvalue0.=$TMP_row[$A_fname[0]];
		}
		if ($searchvalue0=="") $searchvalue0="0";
	}
}
/////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////
?>
