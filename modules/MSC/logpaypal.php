<?php
//if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

$NODEBUG="";

if ($NODEBUG=="") {
	$TMP_deb="";
	$DEBUG_vars = Array('GET', 'POST', 'COOKIE', 'SERVER', 'ENV', 'REQUEST', 'SESSION');
	for($i=0; $i<sizeof($DEBUG_vars); $i++) {
		global ${"HTTP_{$DEBUG_vars[$i]}_VARS"};
		if(is_array(${"HTTP_{$DEBUG_vars[$i]}_VARS"})) {
			$TMP_deb.="<! ".$DEBUG_vars[$i]." >\n";
			foreach(${"HTTP_{$DEBUG_vars[$i]}_VARS"} as $var=>$value) {
				if(!is_array($var)) {
					$TMP_deb.="<! ".$var."=".$value." >\n";
				} else {
					foreach($var as $var2=>$value2) {
						if (is_array($var2)) {
							$TMP_deb.="<! ".$var."[";
							$TMP_deb.=print_r($var2,true);
							$TMP_deb.="]=".$value." >\n";
						} else {
							$TMP_deb.="<! ".$var."[".$var2."]=".$value." >\n";
						}
					}
				}
			}
		}
	}
	$tmpFile="../../files/tmp/paypal.".uniqid("");
	$fp = fopen($tmpFile,"w");
	fwrite($fp,$TMP_deb);
	fclose($fp);
}

// OJO: Se comprueba que el GET se hace desde Paypal
if (getenv("REMOTE_ADDR")=="195.76.9.187"
	 || getenv("REMOTE_ADDR")=="193.16.243.13"
	 || getenv("REMOTE_ADDR")=="195.76.9.117"
	 || getenv("REMOTE_ADDR")=="195.76.9.149"
	 || getenv("REMOTE_ADDR")=="193.16.243.173"
	 || getenv("REMOTE_ADDR")=="195.76.9.222"
	 || getenv("REMOTE_ADDR")=="www.paypal.com" ) {
		$sesioncesta=$TMP_row[sesioncesta]." ".uniqid();
		$cmdSQL2 = "UPDATE GIE_ventas SET sesioncesta='$sesioncesta', cobrado='1',fechacobro='".date("Y-m-d H:i:s")."' WHERE idventa='$idventa'";
		$TMP_result2 = sql_query($cmdSQL2, $RAD_dbi);
		$TMP_row[cobrado]="1";
} else {
	die();
}

?>
