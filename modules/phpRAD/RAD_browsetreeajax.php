<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

global $PHPSESSID, $PHP_SELF, $V_dir, $V_idmod, $V_mod, $RAD_dbi, $tablename, $browsetreefieldparent, $browsetreefield, $orderby, $numf, $fields, $findex, $browsefield, $defaultfilter;

	if ($func!="browsetree") return;

	for($ki=0; $ki<=$numf; $ki++) {
		//if ($fields[$ki]->browsable && $browsefield=="") {
		if ($fields[$ki]->dtype="stand" && $fields[$ki]->browsable) {
			if ($browsefield=="") {
				$browsefield=$fields[$ki]->name;
				$TMP_titlefield=$fields[$ki]->title;
			} else {
				$TMP_titlefield.=" / ".$fields[$ki]->title;
			}
		}
	}

	include_once("images/xajax/xajax.inc.php");
	$xajax = new xajax();
	$xajax->registerFunction("showTreeReg");
	$xajax->processRequests();
	$xajax->printJavascript();
	echo "
<style type='text/css'>
div.regtree:hover { background-color:#f4f4f4; }
</style>
";
	echo "<table class=browse><tr><th class=browse>".$TMP_titlefield."</th></tr><tr><td>".getTree("",0,2)."</td></tr></table>";

	return;
//----------------------------------------------------------------------------------------
function getTree($TMP_id, $TMP_level, $contrae) {
global $PHPSESSID, $PHP_SELF, $V_dir, $V_idmod, $V_mod, $RAD_dbi, $tablename, $browsetreefieldparent, $browsetreefield, $orderby, $numf, $fields, $findex, $browsefield, $defaultfilter;

	$TMP_URL=$PHP_SELF."?V_dir=$V_dir&V_idmod=$V_idmo&V_mod=$V_mod&func=detail";
	if ($headeroff!="") $TMP_URL.="&headeroff=$headeroff";
	if ($footeroff!="") $TMP_URL.="&footeroff=$footeroff";
	if ($blocksoff!="") $TMP_URL.="&blocksoff=$blocksoff";
	if ($menuoff!="") $TMP_URL.="&menuoff=$menuoff";
	$TMP_URL.="&par0=";

	$TMP_content="";
	if ($TMP_id=="") {
		$TMP_sql="SELECT * FROM $tablename WHERE ($browsetreefieldparent IS NULL OR $browsetreefieldparent='' OR $browsetreefieldparent='0')";
		if ($defaultfilter!="") $TMP_sql.=" AND (".$defaultfilter.")";
		if ($orderby!="") $TMP_sql.=" order by $orderby";
	} else {
		$TMP_prefix=str_repeat("&nbsp;",($TMP_level)*6);
		$TMP_sql="SELECT * FROM $tablename WHERE $browsetreefield='$TMP_id'";
		$TMP_res=sql_query($TMP_sql,$RAD_dbi);
		$TMP_row=sql_fetch_array($TMP_res,$RAD_dbi);
		$TMP_literal=$TMP_row[$browsefield.""];
		for($ki=0; $ki<=$numf; $ki++) {
			//if ($fields[$ki]->browsable) {
			if ($fields[$ki]->dtype="stand" && $fields[$ki]->browsable) {
				if ($browsefield!=$fields[$ki]->name) {
					$TMP_literal.=" / ".$TMP_row[$fields[$ki]->name.""];
				}
			}
		}
		//$TMP_literal=htmlentities($TMP_literal,ENT_NOQUOTES,_DEF_sqlcharacter_set);
		//$TMP_row[$browsefield.""]="<a href='".$TMP_URL.$TMP_row[$browsetreefield]."'>".htmlentities($TMP_row[$browsefield.""])."</a>";
		$TMP_linkdetail=" <a href='".$TMP_URL.$TMP_row[$browsetreefield]."' title='Consulta ".$TMP_row[$browsefield.""]."'><img src='images/detail.gif' border=0></a>";
		$TMP_prefix=$TMP_linkdetail.$TMP_prefix;

		$TMP_id=$TMP_row[$browsetreefield];
		$TMP_res2=sql_query("SELECT count(*) FROM $tablename WHERE ($browsetreefieldparent='$TMP_id')", $RAD_dbi);
		$TMP_row2=sql_fetch_array($TMP_res2, $RAD_dbi);
		if ($TMP_row2[0]>0) {
			if ($contrae=="1") $TMP_content.="\n<div class='regtree'>".$TMP_prefix."<a href='javascript:void();' onclick='javascript:xajax_showTreeReg($TMP_id,$TMP_level,2);'><img src='images/menos.gif'> ".$TMP_literal."</a></div>\n";
			else $TMP_content.="\n<div class='regtree'>".$TMP_prefix."<a href='javascript:void();' onclick='javascript:xajax_showTreeReg($TMP_id,$TMP_level,1);'><img src='images/mas.gif'> ".$TMP_literal."</a></div>\n";
		} else {
			$TMP_content.="\n<div class='regtree'>".$TMP_prefix."<img src='images/tr.gif' width=16 height=10> ".$TMP_literal."</div>\n";
		}
		if ($contrae=="2") return $TMP_content;
		$TMP_sql="SELECT * FROM $tablename WHERE $browsetreefieldparent='$TMP_id'";
		if ($defaultfilter!="") $TMP_sql.=" AND (".$defaultfilter.")";
		if ($orderby!="") $TMP_sql.=" order by $orderby";
	}
	$TMP_level2=$TMP_level+1;
	$TMP_res=sql_query($TMP_sql,$RAD_dbi);
	while ($TMP_row=sql_fetch_array($TMP_res,$RAD_dbi)) {
		$TMP_literal=$TMP_row[$browsefield.""];
		for($ki=0; $ki<=$numf; $ki++) {
			//if ($fields[$ki]->browsable) {
			if ($fields[$ki]->dtype="stand" && $fields[$ki]->browsable) {
				if ($browsefield!=$fields[$ki]->name) {
					$TMP_literal.=" / ".$TMP_row[$fields[$ki]->name.""];
				}
			}
		}
		$TMP_id=$TMP_row[$browsetreefield];
		//$TMP_literal=htmlentities($TMP_literal);
		//$TMP_row[$browsefield.""]="<a href='".$TMP_URL.$TMP_row[$browsetreefield]."'>".htmlentities($TMP_row[$browsefield.""])."</a>";
		$TMP_prefix=str_repeat("&nbsp;",($TMP_level+1)*6);
		$TMP_linkdetail=" <a href='".$TMP_URL.$TMP_row[$browsetreefield]."' title='Consultar ".$TMP_row[$browsefield.""]."'><img src='images/detail.gif' border=0></a>";
		$TMP_prefix=$TMP_linkdetail.$TMP_prefix;
		$TMP_res2=sql_query("SELECT count(*) FROM $tablename WHERE ($browsetreefieldparent='$TMP_id')", $RAD_dbi);
		$TMP_row2=sql_fetch_array($TMP_res2, $RAD_dbi);
		if ($TMP_row2[0]>0) $TMP_content.="\n<span id='Tree".$TMP_id."'><div class='regtree'>".$TMP_prefix."<a href='javascript:void();' onclick='javascript:xajax_showTreeReg($TMP_id,$TMP_level2,1);'><img src='images/mas.gif'> ".$TMP_literal."</a></div></span>\n";
		else $TMP_content.="\n<div class='regtree'>".$TMP_prefix."<img src='images/tr.gif' width=16 height=10> ".$TMP_literal."</div>\n";
	}
	return $TMP_content;
}
//----------------------------------------------------------------------------------------
function showTreeReg($id,$level,$contrae) {
global $PHPSESSID, $PHP_SELF, $V_dir, $V_idmod, $V_mod, $RAD_dbi;

	$res=getTree($id,$level,$contrae);
	ob_end_clean();
	$objResponse = new xajaxResponse();
	$objResponse->outputEntitiesOn();
	$objResponse->addAssign('Tree'.$id,'innerHTML',$res);

	return $objResponse;
}

?>
