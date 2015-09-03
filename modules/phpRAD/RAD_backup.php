<?php
//---------------------------------------------------------------------------
//------------------------- backup of a complete Database and their files
//---------------------------------------------------------------------------
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}
if ($tableConfig=="") $tableConfig="config";

if ($func=="backup" && $menubackup == false) { 
    $func="error";
}

if (($func=="showDB")||($func=="showdb")) {
	set_time_limit(0);
	echo "<br><center><table border=0><tr><td align=center><b><u>"._DEF_NLSDatabase."</b></u><br><br></td></tr><tr><td><table border=0>\n";
	$result = sql_query('SHOW DATABASES', $RAD_dbi) or RAD_die(_DEF_NLSError.' : '.sql_error($result));
	while($row=sql_fetch_row($result, $RAD_dbi)) {

		$pos=strpos($dbname, $dbnameSeparator);
		if ($pos>0) $dbnamebase=substr($dbname,0,$pos);
		else $dbnamebase=$dbname; 

		if (substr($row[0],0,strlen($dbnamebase))==$dbnamebase) {
			$RAD_dbi2 = sql_connect(_DEF_dbhost, _DEF_dbuname, _DEF_dbpass, $row[0]);
			$cmdSQL2="SELECT * FROM ".$tableConfig;
			if ($result2 = sql_query($cmdSQL2, $RAD_dbi2)) {
				while($row2 = sql_fetch_array($result2, $RAD_dbi2)) {
					if ($fieldConfig!="") $tmp=$row2["$fieldConfig"];
					else $tmp="";
					echo "<tr><td><a href=\"".$PHP_SELF."?V_dir=$V_dir&V_mod=$V_mod&dbname=$row[0]&headeroff=$headeroff&footeroff=".$footeroff.$SESSION_SID."\">- ".$tmp."</a></td><td>".$row2[0]."</td><td>".$row2[1]."</td><td>".$row2[2]." [".$row[0]."]</td></tr>\n";
				}
			}
		}
	}
	echo "</table></td></tr></table></center>\n";
	echo "<br><br>&nbsp;<a href=\"".$PHP_SELF."?dbname=$dbname&V_dir=$V_dir&V_mod=$V_mod&func=backup&headeroff=$headeroff&footeroff=".$footeroff.$SESSION_SID."\">"._DEF_NLSCreate." "._DEF_NLSBackup." "._DEF_NLSDatabase."</a>\n";
}
if ($func=="backup" && $subfunc!="confbackup") {
	formbackupDB();
} else if ($func=="backup" && $subfunc=="confbackup") {
	$current_date = getdate();
	$pos=strpos($dbname, $dbnameSeparator);
	if ($pos>0) $targetdbname=substr($dbname,0,$pos);
	else $targetdbname=$dbname; 
	$targetdbname.=$dbnameSeparator.substr($current_date["year"],2,2).$current_date["yday"]."x".$current_date["hours"].$current_date["minutes"];
	echo "<br><b>$NLSDatabase $NLSBackup $targetdbname</b><br><br>\n";
	RAD_backupDB($dbname,$targetdbname);

	echo "<hr noshade size=1><a href=\"javascript:window.close();\">"._DEF_NLSClose."</a> "._DEF_NLSWindow." </body></html>";

	$dbname=$targetdbname;
	if (eregi("index.php",$PHP_SELF)) 
		echo "<br><br>&nbsp;<a href=\"".$PHP_SELF."?dbname=$dbname&V_dir=$V_dir&V_mod=$tableConfig&func=browse&headeroff=x&footeroff=x&menuoff=x".$SESSION_SID."\">"._DEF_NLSConfig." "._DEF_NLSBackup." "._DEF_NLSDatabase."</a>\n";
	else
		echo "<br><br>&nbsp;<a href=\"".$tableConfig.".php?dbname=$dbname&func=browse&headeroff=x&footeroff=x&menuoff=x".$SESSION_SID."\">"._DEF_NLSConfig." "._DEF_NLSBackup." "._DEF_NLSDatabase."</a>\n";

	if ($dbname!="") $RAD_dbi2 = sql_connect(_DEF_dbhost, _DEF_dbuname, _DEF_dbpass, $dbname);
	else $RAD_dbi2 = sql_connect(_DEF_dbhost, _DEF_dbuname, _DEF_dbpass, _DEF_dbname);
}
//--------------------------------------------------------------------------------------
function formbackupDB () {
	global $PHPSESSID, $dbname, $PHP_SELF, $V_dir, $V_mod;

?>
<script type='text/javascript'>
window.open("<?=$PHP_SELF?>?func=backup&V_dir=<?=$V_dir?>&V_mod=<?=$V_mod?>&menuoff=X&headeroff=X&footeroff=X&subfunc=confbackup&dbname=<?=$dbname?>&PHPSESSID=<?=$PHPSESSID?>","Backup","width=550,height=300,resizable=yes,scrollbars=yes,status=no,toolbar=no,menubar=no,titlebar=no");
document.location="<?=$PHP_SELF?>?func=&V_dir=<?=$V_dir?>&V_mod=<?=$V_mod?>&dbname=<?=$dbname?>&PHPSESSID=<?=$PHPSESSID?>";
</script>
<?
}
?>
