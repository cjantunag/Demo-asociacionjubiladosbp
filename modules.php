<?php
ini_set("short_open_tag", 1);

//ob_start("ob_gzhandler");
ob_start();
//error_reporting (E_ERROR);
global $RAD_device, $SESSION_blocks_right, $SESSION_blocks_left, $RAD_dbi, $HTTP_SESSION_VARS;

//if ($HTTP_POST_VARS["op"]=="download" || $HTTP_GET_VARS["op"]=="download") Header("Content-type: text/plain");
$TMP = explode(" ",microtime());
$SESSION_transtime= (double)($TMP[1]) + (double)($TMP[0]);

require_once("mainfile.php");
require_once("functions.php");
if ($op=="download") {
	$file=$HTTP_SESSION_VARS["SESSION_file".$file];
	if ($file=="") die();
	$mimeFile=trim(`file -ib $file`);
//	$mimeFile=mime_content_type(basename($file));
	if ($downloadtype!="") Header("Content-type: ".$downloadtype);
	else Header("Content-type: ".$mimeFile);
//	Header("Content-Transfer-Encoding: binary");
//	Header("Content-Disposition: attachment; filename=".basename($file).";");
	readfile($file);
	die();
}

$TMP_theme=getSessionVar("SESSION_theme");
if (ereg("PDA",$TMP_theme)) {
    $headeroff="";
    $footeroff="";
}

if (!isset($V_dir) && !isset($V_mod) && !isset($V_idmod)) { include_once("index.php"); return;}
//if (isset($V_dir) && $V_dir=="") error(_SECURITY_ERROR."(5)");

if ($V_idmod=="0") {	// solo para imprimir el menu
	include("header.php");
	include("footer.php");
	$TMP=ob_get_contents();
	ob_end_clean();
	echo $TMP;
	die();
}

$TMP_order=" ORDER BY "._DBF_M_WEIGHT.", "._DBF_M_GROUPMENU.", "._DBF_M_ITEMMENU;
$TMP_order=" ORDER BY "._DBF_M_WEIGHT.", "._DBF_M_GROUPMENU." DESC, "._DBF_M_ITEMMENU." DESC";
if (isset($V_dir) || isset($V_idmod)) {
	if ($V_idmod!="") {
		$TMP_sql = "select "._DBF_M_ACTIVE.", "._DBF_M_SHOWLEFT.", "._DBF_M_SHOWRIGHT.", "._DBF_M_PROFILES.", "._DBF_M_PUBLIC.", "._DBF_M_PARAMETERS.", "._DBF_M_DIR.", "._DBF_M_FILE.", "._DBF_M_IDMODULE." from "._DBT_MODULES." where "._DBF_M_IDMODULE."='$V_idmod'".$TMP_order;
		$TMP_sql = "select * from "._DBT_MODULES." where "._DBF_M_IDMODULE."='$V_idmod'".$TMP_order;
		$TMP_idmod=$V_idmod;
	} else if ($V_mod!="") {
		$TMP_sql = "select "._DBF_M_ACTIVE.", "._DBF_M_SHOWLEFT.", "._DBF_M_SHOWRIGHT.", "._DBF_M_PROFILES.", "._DBF_M_PUBLIC.", "._DBF_M_PARAMETERS.", "._DBF_M_DIR.", "._DBF_M_FILE.", "._DBF_M_IDMODULE." from "._DBT_MODULES." where "._DBF_M_DIR."='$V_dir' AND "._DBF_M_FILE."='$V_mod'".$TMP_order;
		$TMP_sql = "select * from "._DBT_MODULES." where "._DBF_M_DIR."='$V_dir' AND "._DBF_M_FILE."='$V_mod'".$TMP_order;
		$TMP_mod=$V_mod;
		$TMP_dir=$V_dir;
	} else {
		$TMP_sql = "select "._DBF_M_ACTIVE.", "._DBF_M_SHOWLEFT.", "._DBF_M_SHOWRIGHT.", "._DBF_M_PROFILES.", "._DBF_M_PUBLIC.", "._DBF_M_PARAMETERS.", "._DBF_M_DIR.", "._DBF_M_FILE.", "._DBF_M_IDMODULE." from "._DBT_MODULES." where "._DBF_M_DIR."='$V_dir'".$TMP_order;
		$TMP_sql = "select * from "._DBT_MODULES." where "._DBF_M_DIR."='$V_dir'".$TMP_order;
		$TMP_dir=$V_dir;
	}
	$TMP_result = sql_query($TMP_sql, $RAD_dbi);
	$TMP_permitted=false;
	$SESSION_REG_V_dir=$HTTP_SESSION_VARS["SESSION_REG_V_dir"];
	$SESSION_REG_V_mod=$HTTP_SESSION_VARS["SESSION_REG_V_mod"];
	$SESSION_REG_keyval=$HTTP_SESSION_VARS["SESSION_REG_keyval"];
	while($TMP_row = sql_fetch_array($TMP_result, $RAD_dbi)) {
		if ($RAD_device=="") $RAD_device=getSessionVar("SESSION_device");
		if (!ereg(",".$RAD_device.",",",".$TMP_row[device].",") && $TMP_row[device]!="" && $RAD_device!="") continue;
		if (in_array($TMP_row[_DBF_M_ACTIVE],array("S","N"))) $TMP_mod_active=($TMP_row[_DBF_M_ACTIVE]=='S')?1:0;
		else $TMP_mod_active=$TMP_row[_DBF_M_ACTIVE];
		$TMP_showleft=$TMP_row[_DBF_M_SHOWLEFT];
		$TMP_showright=$TMP_row[_DBF_M_SHOWRIGHT];
		$TMP_profiles=$TMP_row[_DBF_M_PROFILES];
		$TMP_public=$TMP_row[_DBF_M_PUBLIC];
		$TMP_params=$TMP_row[_DBF_M_PARAMETERS];
		$TMP_dir=$TMP_row[_DBF_M_DIR];
		$TMP_mod=$TMP_row[_DBF_M_FILE];
		$TMP_idmod=$TMP_row[_DBF_M_IDMODULE];

		if ($V_dir!=$SESSION_REG_V_dir || $V_mod!=$SESSION_REG_V_mod) {
			if ($TMP_mod_active==="0" && !is_admin()) continue; // Salta modulos no activos
		}
		if (is_modulepermitted($TMP_idmod, $TMP_dir, $TMP_mod) || is_admin()) {
			$TMP_permitted=true;
			break;
		}
	}
	if ($V_idmod!="" && $TMP_idmod!="" && $V_idmod!=$TMP_idmod) $V_idmod=$TMP_idmod;
	if ($V_mod!="" && $TMP_mod!="" && $V_mod!=$TMP_mod) $V_mod=$TMP_mod;

	if ($V_mod=="usercontrol" && $V_dir=="coremods" && ($V_op=="login"||$V_op=="logout")) { $TMP_showright="1"; $TMP_showleft="1"; $TMP_mod_active="1"; $TMP_permitted=true; } // RAD_ORACLE
	//if (substr($V_dir,0,5)=="libra" && $TMP_permitted==false) { $TMP_showright="1"; $TMP_showleft="1"; $TMP_mod_active="1"; $TMP_permitted=true; } // RAD_ORACLE (de momento todos los modulos del directorio libra permitidos
	//if (substr($V_dir,0,5)=="libra") { $TMP_mod_active="1"; $TMP_permitted=true; } // RAD_ORACLE (de momento todos los modulos del directorio libra permitidos

	if (!is_admin() && $TMP_permitted==false) {
	    if ($V_idmod>0 && $TMP_idmod>0 && $V_idmod!=$TMP_idmod) RAD_die(_ACCESSDENIED."... $V_idmod-$TMP_idmod $V_dir/$V_mod");
	    if ($V_mod!="" && $V_mod!=$TMP_mod) RAD_die(_ACCESSDENIED.".... $TMP_mod $V_idmod $V_dir/$V_mod");
	    if ($V_dir!="" && $V_dir!=$TMP_dir) RAD_die(_ACCESSDENIED."..... $TMP_dir $V_idmod $V_dir/$V_mod");
	}

	if ($V_dir=="" && $TMP_dir!="") $V_dir=$TMP_dir;
	if ($V_mod=="" && $TMP_mod!="") $V_mod=$TMP_mod;
	$V_idmod=$TMP_idmod;

	if ($TMP_params!="") {
		$TMP_par=explode("\n",$TMP_params);
		if (count($TMP_par)>1) eval($TMP_params);
		else {
			$TMP_par=explode("&",$TMP_params);
			if (count($TMP_par)==0) $TMP_par[0]=$TMP_params;
			if (count($TMP_par)>0) {
				for ($kki = 0; $kki < count($TMP_par); $kki++) {
					$TMP_var=explode("=",$TMP_par[$kki]);
					if ($TMP_var[0]!="") {
						global ${$TMP_var[0]};
						${$TMP_var[0]}=$TMP_var[1];
					}
				}
			}
		}
	}

	if ($TMP_showright!="") $SESSION_blocks_right=$TMP_showright;
	if ($TMP_showleft!="") $SESSION_blocks_left=$TMP_showleft;
	if ($TMP_showright=="") $SESSION_blocks_right=0;
	if ($TMP_showleft=="") $SESSION_blocks_left=0;
	if ($blocksoff!="") {
	       $SESSION_blocks_right=0;
	       $SESSION_blocks_left=0;
	}

	if ($TMP_permitted==false) {
	   if (!is_modulepermitted($TMP_idmod, $TMP_dir, $TMP_mod)&&!is_admin()) {
		$SESSION_REG_V_dir=$HTTP_SESSION_VARS["SESSION_REG_V_dir"];
		$SESSION_REG_V_mod=$HTTP_SESSION_VARS["SESSION_REG_V_mod"];
		$SESSION_REG_keyval=$HTTP_SESSION_VARS["SESSION_REG_keyval"];
		if ($V_dir==$SESSION_REG_V_dir && $V_mod==$SESSION_REG_V_mod) {
		    $par0=$SESSION_REG_keyval;
		    $TMP_mod_active='1';
		} else {
                    if ($xajax!='') {
                        ob_end_clean();
                        $sContentHeader = "Content-type: text/xml;";
                        header($sContentHeader);
                        $TMP_msg=_ACCESSDENIED.'<br>'._MODULEUSERS;
                        $TMP_msg=str_replace('<br>',"\n",$TMP_msg);
                        echo '<?xml version="1.0" encoding="utf-8" ?><xjx><cmd n="al"><![CDATA['.$TMP_msg.']]></cmd></xjx>';
                        die;
                    }
		    setSessionVar("SESSION_pagetitle", "- "._ACCESSDENIED."");
		    include("header.php");
		    title(_DEF_SITENAME.": "._ACCESSDENIED."");
		    OpenTable();
		    $TMP_err="<center><b>"._RESTRICTEDAREA."</b><!-- $V_idmod $V_dir/$V_mod -->";
		    if (!is_user()) $TMP_err.="<br><br>".""._MODULEUSERS."".""._GOHOME;
		    RAD_logError("ERR: ".$TMP_err);
	   	    echo $TMP_err;
		    CloseTable();
		    include("footer.php");
		    $TMP=ob_get_contents();
		    ob_end_clean();
		    echo $TMP;
		    die();
		}
	    }
	}
	if (($TMP_mod_active == '1') OR ($TMP_mod_active != '1' AND is_admin())) {
		if (!isset($V_mod)) { $V_mod="index"; }
		if ((ereg("\.\.",$V_dir) || ereg("\.\.",$V_mod) || ereg(":",$V_dir)  || ereg(":",$V_mod)) && ($V_mod!="index")) {
			$TMP_err=_SECURITY_ERROR."(3) [$V_dir/$V_mod]";
			RAD_logError("ERR: ".$TMP_err);
			error($TMP_err);
		} else {
		    if ($subfunc=="sendlist" || $V_subfunc=="sendlist") {
			include("header.php");
			title(_DEF_SITENAME);
			include_once("modules/phpRAD/RAD_send.php");
			include("footer.php");
			$TMP=ob_get_contents();
			ob_end_clean();
			echo $TMP;
			die();
		    } else {
			$RAD_cfg=$HTTP_SESSION_VARS["SESSION_cfg"];
			$dirlang=$HTTP_SESSION_VARS["SESSION_lang"];
			$TMP_lmodfile0="modules/$V_dir"."-".$dirlang."/$V_mod.$RAD_cfg.php";
			$TMP_lmodfile1="modules/$V_dir"."-".$dirlang."/$V_mod.php";
			$TMP_lmodfile2="modules/$V_dir"."-".$dirlang."/$V_mod.htm";
			$TMP_lmodfile3="modules/$V_dir"."-".$dirlang."/$V_mod.html";
			$TMP_lmodfile4="modules/$V_dir"."-".$dirlang."/$V_mod";
			$TMP_modfile0="modules/$V_dir/$V_mod.$RAD_cfg.php";
			$TMP_modfile1="modules/$V_dir/$V_mod.php";
			$TMP_modfile2="modules/$V_dir/$V_mod.htm";
			$TMP_modfile3="modules/$V_dir/$V_mod.html";
			$TMP_modfile4="modules/$V_dir/$V_mod";
			$TMP_modfile5="modules/"._DEF_app_V_dir."/$V_mod.php";
			$TMP_modfileMobile="modules/".$V_dir."/m.".$V_mod.".php";
			$TMP_modfile="";
			if (file_exists($TMP_modfileMobile) && preg_match("/JMOVIL/",_DEF_THEME) && substr($V_mod,0,2)!="m.") {
				$TMP_modfile=$TMP_modfileMobile;
			} else if (file_exists($TMP_lmodfile0)) {
				$TMP_modfile=$TMP_lmodfile0;
			} else if (file_exists($TMP_lmodfile1)) {
				$TMP_modfile=$TMP_lmodfile1;
			} else if (file_exists($TMP_lmodfile2)) {
				$TMP_modfile=$TMP_lmodfile2;
			} else if (file_exists($TMP_lmodfile3)) {
				$TMP_modfile=$TMP_lmodfile3;
			} else if (file_exists($TMP_lmodfile4)) {
				$TMP_modfile=$TMP_lmodfile4;
			} else if (file_exists($TMP_modfile0)) {
				$TMP_modfile=$TMP_modfile0;
			} else if (file_exists($TMP_modfile1)) {
				$TMP_modfile=$TMP_modfile1;
			} else if (file_exists($TMP_modfile2)) {
				$TMP_modfile=$TMP_modfile2;
			} else if (file_exists($TMP_modfile3)) {
				$TMP_modfile=$TMP_modfile3;
			} else if (file_exists($TMP_modfile4)) {
				$TMP_modfile=$TMP_modfile4;
			} else if (file_exists($TMP_modfile5)) {
				$TMP_modfile=$TMP_modfile4;
			} else {
				$TMP_err="Sorry, such file doesn't exist (".$TMP_modfile1.").";
				RAD_logError("ERR: ".$TMP_err);
				echo $TMP_err;
				$TMP=ob_get_contents();
				ob_end_clean();
				echo $TMP;
				die();
			}
			if ($TMP_modfile!="") {
				$TMP_modfile=str_replace(":","",$TMP_modfile);
				$TMP_modfile=str_replace("..","",$TMP_modfile);
				include($TMP_modfile);
			}
		    }
		}
	} else {
		include("header.php");
		OpenTable();
		$TMP_err=_MODULENOTACTIVE."<br><br>".""._GOBACK."</center><!-- $V_idmod $V_dir/$V_mod -->";
		RAD_logError("ERR: ".$TMP_err);
		echo $TMP_err;
		CloseTable();
		include("footer.php");
		$TMP=ob_get_contents();
		ob_end_clean();
		echo $TMP;
		die();
	}
} else if ($PHP_SELF==__FILE__) {
//    error(_SECURITY_ERROR."(0)");
	$TMP=ob_get_contents();
	ob_end_clean();
	echo "\n<script>\nwindow.history.back();\n</script>\n";
	die();
} else {
    error(_SECURITY_ERROR."(4)");
}

$SESSION_transtime=RAD_microtime()-$SESSION_transtime;
if (_DEBUG || $footer=="DEBUG") {
	echo "<hr noshade size=1>TransTime:".$SESSION_transtime."<br>";
}
if ($SESSION_transtime>2) {
	$TMP_REQUEST_URI=$REQUEST_URI;
	if (count($HTTP_POST_VARS)>0) {
	    $TMP_REQUEST_URI.="?";
	    foreach ($HTTP_POST_VARS as $TMP_key=>$TMP_val) {
        	if (!is_array($TMP_val) && strlen($TMP_val)<30) $TMP_REQUEST_URI.="&".urlencode($TMP_key)."=".urlencode($TMP_val);
	    }
	}
	RAD_logError("EXEC: [ delay ".substr($SESSION_transtime,0,5)."] ".$TMP_REQUEST_URI); // log every page that delay more than 1 second
}
$TMP=ob_get_contents();
ob_end_clean();
$TMP_langlocal=substr(_DEF_LOCALE,0,2);
$TMP_newlang=getSessionVar("SESSION_locale");
//if ($TMP_langlocal!=$TMP_newlang && $TMP_newlang!="" && $TMP_langlocal!="") {
//	include_once("translate.php");
//	$TMP=translateLocale($TMP,$TMP_langlocal."-".$TMP_newlang);
//}

$SESSION_pagetitle=getSessionVar("SESSION_pagetitle");
if ($SESSION_pagetitle!="") {
	if (eregi("<TITLE>"._DEF_SITENAME." </TITLE>",$TMP)) $TMP=str_ireplace("<TITLE>"._DEF_SITENAME." </TITLE>","<TITLE>"._DEF_SITENAME." $SESSION_pagetitle</TITLE>\n",$TMP);
	else if (eregi("<TITLE>"._DEF_SITENAME."</TITLE>",$TMP)) $TMP=str_ireplace("<TITLE>"._DEF_SITENAME."</TITLE>","<TITLE>"._DEF_SITENAME." $SESSION_pagetitle</TITLE>",$TMP);
	setSessionVar("SESSION_pagetitle","",0);
}

// Elimina JavaScript del codigo HTML
if ($V_typePrint!="" || $V_typeSend!="") {
	if ($V_save=="" && $V_send=="") $V_save="x";
}
if ($V_typePrint!="" || $V_typeSend!="") {
    $TMP = preg_replace("'<script[^>]*?>.*?</script>'si", "", $TMP);
//    $TMP_posini=strpos(strtoupper($TMP),"<SCRIPT");
//    $TMP_posfin=strpos(strtoupper($TMP),"</SCRIPT>");
//    while($TMP_posini>0 && $TMP_posfin>0) {
//	$TMP=substr($TMP,0,$TMP_posini).substr($TMP,$TMP_posfin+9);
//	$TMP_posini=strpos(strtoupper($TMP),"<SCRIPT");
//	$TMP_posfin=strpos(strtoupper($TMP),"</SCRIPT");
//    }
}
if ((strtoupper($V_typePrint)=="" && $V_typeSend=="")||($V_save=="" && $V_send=="")) {
    //ob_start("ob_gzhandler");
    ob_start();
    //if (_CHARSET=="UTF-8") $TMP=utf8_encode($TMP);
    //if (_CHARSET=="ISO-8859-1") $TMP=utf8_decode($TMP);
    echo $TMP;
    ob_end_flush();
} else if ((strtoupper($V_typePrint)=="HTML"&& $V_save!="") || (strtoupper($V_typeSend)=="HTML" && $V_send!="")) {
    //if (_CHARSET=="UTF-8") $TMP=utf8_encode($TMP);
    //if (_CHARSET=="ISO-8859-1") $TMP=utf8_decode($TMP);
    $TMP=str_replace("window.print();","",$TMP);
    $TMP=str_replace("<noscript>", "<! ",$TMP);
    $TMP=str_replace("</noscript>", " >",$TMP);
    $tmpFile="files/tmp/".uniqid("").".htm";
    $fp = fopen($tmpFile,"w");
//    fwrite($fp,"<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\">\n");
    fwrite($fp,$TMP);
    fclose($fp);
    if ($V_save!="") {
/*
	session_cache_limiter("");
	$size=filesize($tmpFile);
	header("Cache-control: private\n");
	header("Content-Type: application/octet-stream\n");
	header("Content-Length: $size");
	if ($disposition=="") $disposition="inline";
	header("Content-Disposition: $disposition; filename=".uniqid("").".html\n");
	readfile($tmpFile);
*/
	//header("Location: ".$tmpFile);
	echo $TMP;
    } else {
	RAD_sendMail($V_to,$V_cc,$V_bcc,$V_from,$V_asunto,$V_mensaje,$tmpFile);
	echo "<script>\nalert('"._DEF_NLSMessageSent."');\nRAD_CloseW(false);\n</script>\n";
    }
    unlink($tmpFile);
//} else if ((strtoupper($V_typePrint)=="FPDF"&& $V_save!="") || (strtoupper($V_typeSend)=="FPDF" && $V_send!="")) {
} else if ((strtoupper($V_typePrint)=="PDF"&& $V_save!="") || (strtoupper($V_typeSend)=="PDF" && $V_send!="")) {
//	$TMP=preg_replace("'<STYLE([]+)/STYLE>'si", "", $TMP);	// elimina CSS
	$TMP=preg_replace("'<a([^>]+)>'si", "", $TMP);	// elimina enlaces
	$TMP=preg_replace("'</a>'si", "", $TMP);
	$TMP=preg_replace("'<input([^>]+)>'si", "", $TMP);
	$TMP=preg_replace("'<text([^>]+)>'si", "", $TMP);
	require_once('html2fpdfb.php');
	$pdf=new HTML2FPDF();
	$pdf->AddPage();
	$pdf->WriteHTML($TMP);
	$pdfFile="/tmp/".uniqid("").".pdf";
	$pdf->Output($pdfFile,"F");
	    if ($V_save!="") {
		session_cache_limiter("");
		$size=filesize($pdfFile);
		header("Cache-control: private\n");
		//header("Content-Type: application/octet-stream\n");
		header("Content-type: application/pdf\n");
		header("Content-transfer-encoding: binary\n");
		header("Content-Length: $size");
		if ($disposition=="") $disposition="inline";
		header('Content-Disposition: $disposition; filename="'.uniqid('').'.pdf"');
		readfile($pdfFile);
	    } else {
		RAD_sendMail($V_to,$V_cc,$V_bcc,$V_from,$V_asunto,$V_mensaje,$pdfFile);
		echo "<script>\nalert('"._DEF_NLSMessageSent."');\nRAD_CloseW(false);\n</script>\n";
	    }
//	require_once('html2pdf.php');
//	$pdf =& new createPDF( $TMP, _DEF_SITENAME, $REQUEST_URI, base64_decode($HTTP_SESSION_VARS["SESSION_user"]), time() );
//	$pdf =& new createPDF( html text to publish, article title, article URL, author name, time() );
//	$pdf->run();
////	require_once('fpdf2HTML.php');
////	$pdf=new FPDF2HTML();
////	$pdf->AddPage();
////	$pdf->SetTitle(_DEF_SITENAME);
////	$pdf->SetAuthor(base64_decode($HTTP_SESSION_VARS["SESSION_user"]));
////	$pdf->SetFont('Arial','',12);
////	$pdf->WriteHTML($TMP);
////	$pdf->Output();
} else if ((strtoupper($V_typePrint)=="PDF"&& $V_save!="") || (strtoupper($V_typeSend)=="PDF" && $V_send!="")) {
//	$TMP=preg_replace("'<a([^>]+)>'si", "", $TMP);	// elimina enlaces
//	$TMP=preg_replace("'</a>'si", "", $TMP);
	$TMP=preg_replace("'<input([^>]+)>'si", "", $TMP);
	$TMP=preg_replace("'<text([^>]+)>'si", "", $TMP);
	$tmpFile="/tmp/".uniqid("").".htm";
	$fp = fopen($tmpFile,"w");
//	fwrite($fp,"<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\">\n");
	fwrite($fp,$TMP);
	fclose($fp);
	require_once ('HTML_ToPDF.php');
	$defaultDomain = substr(_DEF_URL,7,strlen(_DEF_URL)-8);
	$pdfFile = substr($tmpFile,0,strlen($tmpFile)-4).".pdf";
	//$pdf = new HTML_ToPDF($tmpFile, $defaultDomain, $pdfFile);
	$pdf = new HTML_ToPDF($tmpFile, "", $pdfFile);
	$result = $pdf->convert();
//	if (PEAR::isError($result)) {
//		die($result->getMessage());
//	} else {
	    if ($V_save!="") {
		session_cache_limiter("");
		$size=filesize($pdfFile);
		header("Cache-control: private\n");
		//header("Content-Type: application/octet-stream\n");
		header("Content-type: application/pdf\n");
		header("Content-transfer-encoding: binary\n");
		header("Content-Length: $size");
		if ($disposition=="") $disposition="inline";
		header("Content-Disposition: $disposition; filename=".uniqid("").".pdf\n");
		readfile($pdfFile);
	    } else {
		RAD_sendMail($V_to,$V_cc,$V_bcc,$V_from,$V_asunto,$V_mensaje,$pdfFile);
		echo "<script>\nalert('"._DEF_NLSMessageSent."');\nRAD_CloseW(false);\n</script>\n";
	    }
//	}
} else if ((strtoupper($V_typePrint)=="CSV"&& $V_save!="") || (strtoupper($V_typeSend)=="CSV" && $V_send!="")) {
	$TMP=RAD_convertHTML2CSV($TMP);	// Convierte tablas HTML a campos separados por comas.
	$csvFile="files/tmp/".uniqid("").".csv";
	$fp = fopen($csvFile,"w");
	fwrite($fp,$TMP);
	fclose($fp);
	if ($V_save!="") {

		session_cache_limiter("");
		$size=filesize($csvFile);
		header("Cache-control: private\n");
		//header("Content-Type: application/octet-stream\n");
		header("Content-type: application/csv\n");
		header("Content-transfer-encoding: binary\n");
		header("Content-Length: $size");
		if ($disposition=="") $disposition="inline";
		header("Content-Disposition: $disposition; filename=".uniqid("").".csv\n");
		readfile($csvFile);

//		header("Location: ".$csvFile);
	} else {
	    RAD_sendMail($V_to,$V_cc,$V_bcc,$V_from,$V_asunto,$V_mensaje,$csvFile);
	    echo "<script>\nalert('"._DEF_NLSMessageSent."');\nRAD_CloseW(false);\n</script>\n";
	}
} else if ((strtoupper($V_typePrint)=="MP3"&& $V_save!="") || (strtoupper($V_typeSend)=="MP3" && $V_send!="")) {
	$pos=strpos(strtolower($TMP),"<body"); if ($pos>0) $TMP=substr($TMP,$pos);
	$TMP=RAD_convertHTML2TXT($TMP);
	$TMP=str_replace("\r","",$TMP); $TMP=str_replace(chr(160),"",$TMP); 
	$TMP=str_replace(chr(191),"",$TMP); $TMP=str_replace("://",": barrabarra ",$TMP);
	$A_x=explode("\n",$TMP."\n");
	$TMP="";
	foreach($A_x as $TMP_idx=>$TMP_lin) {
		$TMP_lin=str_replace("     "," ",$TMP_lin); 
		$TMP_lin=str_replace("    "," ",$TMP_lin); 
		$TMP_lin=trim(str_replace("  "," ",$TMP_lin)); 
		if ($TMP_lin=="") continue;
		$TMP.=$TMP_lin."\n";
	}
	$txtFile="/tmp/".uniqid("");
	$wavFile=$txtFile.".wav";
	$mp3File=$txtFile.".mp3";
	$txtFile.=".txt";
	$fp = fopen($txtFile,"w");
	fwrite($fp,$TMP);
	fclose($fp);
	@system('text2wave -eval "(voice_el_diphone)" -scale 20 '.$txtFile.' -o '.$wavFile.' ; lame '.$wavFile.' '.$mp3File.' 1>/dev/null ; rm '.$txtFile.' '.$wavFile);
	//@system('text2wave -scale 20 '.$txtFile.' -o '.$wavFile.' ; lame '.$wavFile.' '.$mp3File.' 1>/dev/null ; rm '.$txtFile.' '.$wavFile);
	ob_end_clean();
	if ($V_save!="") {
		session_cache_limiter("");
		$size=filesize($mp3File);
		header("Cache-control: private\n");
		//header("Content-Type: application/octet-stream\n");
		header("Content-type: audio/mp3\n");
		header("Content-transfer-encoding: binary\n");
		header("Content-Length: $size");
		if ($disposition=="") $disposition="inline";
		header("Content-Disposition: $disposition; filename=".uniqid("").".mp3\n");
		readfile($mp3File);
	} else {
	    RAD_sendMail($V_to,$V_cc,$V_bcc,$V_from,$V_asunto,$V_mensaje,$mp3File);
	    echo "<script>\nalert('"._DEF_NLSMessageSent."');\nRAD_CloseW(false);\n</script>\n";
	}
} else if ((strtoupper($V_typePrint)=="TXT"&& $V_save!="") || (strtoupper($V_typeSend)=="TXT" && $V_send!="")) {
	$pos=strpos(strtolower($TMP),"<body"); if ($pos>0) $TMP=substr($TMP,$pos);
	$TMP=RAD_convertHTML2TXT($TMP);
	$TMP=str_replace("\r","",$TMP);
	$TMP=str_replace("-"," - ",$TMP);
	$TMP=str_replace("     "," ",$TMP); $TMP=str_replace("\n\n\n\n\n","\n\n",$TMP);
	$TMP=str_replace("    "," ",$TMP); $TMP=str_replace("\n\n\n\n","\n\n",$TMP);
	$TMP=str_replace("  "," ",$TMP); $TMP=str_replace("\n\n\n","\n\n",$TMP);
	$TMP=str_replace("\n","\r\n",$TMP);
	$txtFile="files/tmp/".uniqid("").".txt";
	$fp = fopen($txtFile,"w");
	fwrite($fp,$TMP);
	fclose($fp);
	ob_end_clean();
	if ($V_save!="") {
/*
		session_cache_limiter("");
		$size=filesize($txtFile);
		header("Cache-control: private\n");
		//header("Content-Type: application/octet-stream\n");
		header("Content-type: text/txt\n");
		header("Content-transfer-encoding: binary\n");
		header("Content-Length: $size");
		if ($disposition=="") $disposition="inline";
		header("Content-Disposition: $disposition; filename=".uniqid("").".txt\n");
		readfile($txtFile);
*/
		header("Location: ".$txtFile);
	} else {
	    RAD_sendMail($V_to,$V_cc,$V_bcc,$V_from,$V_asunto,$V_mensaje,$txtFile);
	    echo "<script>\nalert('"._DEF_NLSMessageSent."');\nRAD_CloseW(false);\n</script>\n";
	}
} else if ((strtoupper($V_typeSend)=="FILE" && $V_send!="")) {
	RAD_sendMail($V_to,$V_cc,$V_bcc,$V_from,$V_asunto,$V_mensaje,$V_doc);
	echo "<script>\nalert('"._DEF_NLSMessageSent."');\nRAD_CloseW(false);\n</script>\n";
} else if (strtolower($V_typePrint)=="impreso" || strtolower($V_typeSend)=="impreso") {
	include("impresion.php");
}
?>
