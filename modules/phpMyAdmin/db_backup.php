<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

require("modules/$V_dir/header.inc.php");

$dbusername=$cfgServer['stduser'];
$dbpassword=$cfgServer['stdpass'];
if ($dbpassword!="") {
	$command ="mysqladmin create ".$db.$sufix." -u $dbusername -p $dbpassword ; ";
	$command.="mysqldump -u $dbusername -p $dbpassword $db | mysql -u $dbusername -p $dbpassword ".$db.$sufix;
} else {
	$command ="mysqladmin create ".$db.$sufix." -u $dbusername --password= ; ";
	$command.="mysqldump -u $dbusername --password= $db | mysql -u $dbusername --password= ".$db.$sufix;
}

system($command);

$TMP_URL=$PHP_SELF."?V_dir=$V_dir&V_mod=$V_mod&func=db_details&server=$server&db=$db$sufix";
$message = "$strDatabase <a href='$TMP_URL'>$db$sufix</a> $strHasBeenCreated";
echo $message;

?>
