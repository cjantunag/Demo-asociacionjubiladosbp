<?
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

require("modules/$V_dir/header.inc.php");

$dbtype=_DEF_dbtype;
if (strtolower($dbtype)!="mysql") {
	echo "Not implemented.....";
	return;
}

if (_DEF_dbpass!="" && _DEF_dbpass!="_DEF_dbpass") {
	$command ="mysqladmin create ".$db.$sufix." -u "._DEF_dbuname." -p "._DEF_dbpass." ; ";
	$command.="mysqldump -u "._DEF_dbuname." -p "._DEF_dbpass." $db | mysql -u "._DEF_dbuname." -p "._DEF_dbpass." ".$db.$sufix;
} else {
	$command ="mysqladmin create ".$db.$sufix." -u "._DEF_dbuname." --password= ; ";
	$command.="mysqldump -u "._DEF_dbuname." --password= $db | mysql -u "._DEF_dbuname." --password= ".$db.$sufix;
}

system($command);

$TMP_URL=$PHP_SELF."?V_dir=$V_dir&V_mod=$V_mod&func=db_details&db=$db$sufix";
$message = "$strDatabase <a href='$TMP_URL'>$db$sufix</a> $strHasBeenCreated";
echo $message;

?>
