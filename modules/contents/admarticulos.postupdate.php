<?
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}

if ($par0>0) {
	$TMP_result = sql_query("SELECT * FROM articulos WHERE id='".$par0."'", $RAD_dbi);
	$TMP_row = sql_fetch_array($TMP_result, $RAD_dbi);
	$A_docs=explode("\n",str_replace("\r","",$TMP_row[documentos]));
	$TMP_docs="";
	if (count($A_docs)>0) {
		foreach($A_docs as $TMP_idx=>$TMP_doc) {
			if (trim($TMP_doc=="")) continue;
			$TMP_docs.=$TMP_doc."\n";
			$ext=strtolower($TMP_doc,strlen($TMP_doc)-3);
			$ext4=strtolower($TMP_doc,strlen($TMP_doc)-3);
			if ($ext=="mpg" || $ext=="avi" || $ext4=="mpeg") {
// crea nuevo documento, convirtiendo mpeg o avi a flv
				if ($ext=="mpg" || $ext=="avi") {
					$TMP_doc2=substr($TMP_doc,0,strlen($TMP_doc)-3)."flv";
				} else {
					$TMP_doc2=substr($TMP_doc,0,strlen($TMP_doc)-4)."flv";
				}
				//system("ffmpeg $TMP_doc -flv -o $TMP_doc2");
				//$TMP_docs.=$TMP_doc2."\n";
			}
		}
		if ($TMP_docs!="") {
			$cmdUpdate = "UPDATE articulos SET documentos='".$TMP_docs."' WHERE id='".$par0."'";
			RAD_printLog($cmdUpdate);
			$TMP_result3 = sql_query($cmdUpdate, $RAD_dbi);
		}
	}
}

?>
