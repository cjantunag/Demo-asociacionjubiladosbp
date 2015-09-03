<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

global $PHPSESSID;

if (!isset($message)) 
   include("modules/$V_dir/header.inc.php");
else
   show_message($message);

?>

<?php
if ($server > 0) {
	// Don't display server info if $server==0 (no server selected)
	$res_version = mysql_query("SELECT Version() as version") or mysql_die();
	$row_version = mysql_fetch_array($res_version);
	echo "<b>MySQL $row_version[version] $strRunning " . $cfgServer['host'];
	if (!empty($cfgServer['port'])) echo ":" . $cfgServer['port'];
	echo "</b><br>\n";
}

if (($server > 0) && isset($mode) && ($mode == "reload")) {
     $result = mysql_query("FLUSH PRIVILEGES");
     if ($result != 0) {
       echo "<b>$strMySQLReloaded</b>";
     } else {
       echo "<b>$strReloadFailed</b>";
     }
}

echo "<ul>";

if(count($cfgServers) > 1) {
	echo "<li>";
	echo '<form action="index.php">';
	echo "<input type=hidden name=PHPSESSID value='$PHPSESSID'><input type=hidden name=V_dir value='$V_dir'><input type=hidden name=V_mod value='$V_mod'>";

	echo '<select name="server">';
	reset($cfgServers);
	while(list($key, $val) = each($cfgServers)) {
        if(!empty($val['host'])) {
            echo "<option value=\"$key\"";
            if(!empty($server) && ($server == $key)) echo " selected";
            echo ">";
            print(!empty($val['verbose']) ? $val['verbose'] :  $val['host']);
            if(!empty($val['port'])) echo ":" . $val['port'];
            if(!empty($val['only_db'])) echo " - ".$val['only_db'];
            echo "\n";
        }
    }
    echo '</select><input ACCESSKEY="S" TITLE="ALT+S" type="submit" value="'.$strGo.'"></form>';
}

if($server > 0) {
    // Don't display server-related links if $server==0 (no server selected)
    if(empty($cfgServer['only_db'])) {
        if($cfgServer['adv_auth']) {
            if (empty($cfgServer['port'])) {
                $dbh = mysql_connect($cfgServer['host'],$cfgServer['stduser'],$cfgServer['stdpass']);
            } else {
                $dbh = mysql_connect($cfgServer['host'].":".$cfgServer['port'],$cfgServer['stduser'],$cfgServer['stdpass']);
            }

            $rs_usr=mysql_db_query("mysql","select * from user where User=\"".$cfgServer['user']."\"",$dbh);
            $result_usr=mysql_fetch_array($rs_usr);
            $rs_db=mysql_db_query("mysql","select * from db where User=\"".$cfgServer['user']."\"",$dbh);

            if(mysql_num_rows($rs_db)>0) $result_db=mysql_fetch_array($rs_db);

            if($result_usr['Create_priv']=='Y') $CREATE=TRUE;
            elseif(!empty($result_db) && $result_db['Create_priv']=='Y') $CREATE=TRUE;
            else $CREATE=FALSE;

$CREATE=FALSE;		// RAD
            if($CREATE) {
                ?>
                <li>
                <form method="post" action="index.php">
			<input type=hidden name=PHPSESSID value='<?=$PHPSESSID?>'>
			<input type=hidden name=V_dir value='<?=$V_dir?>'>
			<input type=hidden name=V_mod value='<?=$V_mod?>'>
			<input type=hidden name=server value='<?=$server?>'>
			<input type=hidden name=func value='db_create'>
                <?php echo $strCreateNewDatabase;?> <?php print show_docu("manual_Reference.html#Create_database");?><br><input type="Hidden" name="server" value="<?php echo $server; ?>"><input type="hidden" name="reload" value="true"><input type="text" name="db"><input ACCESSKEY='S' TITLE='ALT+S' type="submit" value="<?php echo $strCreate; ?>">
                </form>
                <?php
            }

            if($result_usr['References_priv']=='Y') {
                ?>
                <li><a href="?V_dir=<?=$V_dir?>&V_mod=<?=$V_mod?>&func=sql&server=<?php echo $server;?>&db=mysql&sql_query=<?php echo urlencode("SHOW STATUS").$SESSION_SID;?>">
                <?php echo $strMySQLShowStatus;?></a> <?php print show_docu("manual_Reference.html#Show");?>

                <li><a href="?V_dir=<?=$V_dir?>&V_mod=<?=$V_mod?>&func=sql&server=<?php echo $server;?>&db=mysql&sql_query=<?php echo urlencode("SHOW VARIABLES").$SESSION_SID;?>">
                <?php echo $strMySQLShowVars;?></a> <?php print show_docu("manual_Performance.html#Performance");
            }

            if($result_usr['Process_priv']=='Y') {
                ?>
                <li><a href="?V_dir=<?=$V_dir?>&V_mod=<?=$V_mod?>&func=sql&server=<?php echo $server;?>&db=mysql&sql_query=<?php echo urlencode("SHOW PROCESSLIST").$SESSION_SID;?>">
                <?php echo $strMySQLShowProcess;?></a> <?php print show_docu("manual_Reference.html#Show");
            }

            if($result_usr['Reload_priv']=='Y') {
                ?>
                <li>
                <a href="?V_dir=<?=$V_dir?>&V_mod=<?=$V_mod?>&func=&server=<?php echo $server.$SESSION_SID;?>&mode=reload"><?php echo $strReloadMySQL; ?></a> <?php print show_docu("manual_Reference.html#Flush");
            }
            ?>
            <li><a href="?V_dir=<?$V_dir?>&V_mod=<?=$V_mod?>&server=<?php echo $server.$SESSION_SID;?>&old_usr=<?php echo $PHP_AUTH_USER;?>" target="_top"><?php echo $strLogout; ?></a>
            <?php
        } else { //No AdvAuth
// RAD 	echo "<li><form method=post action=\"index.php\">
// RAD <input type=hidden name=PHPSESSID value='".$PHPSESSID."'>
// RAD <input type=hidden name=V_dir value='$V_dir'>
// RAD <input type=hidden name=V_mod value='$V_dir'>
// RAD <input type=hidden name=server value='".$server."'>
// RAD <input type=hidden name=func value='db_create'>
// RAD <input type=hidden name=reload value=true>";
// RAD 	echo $strCreateNewDatabase;
// RAD	print show_docu("manual_Reference.html#Create_database");
// RAD 	echo "<br><input type=text name=db><input type=submit value='$strCreate'></form>";
?>
            <li><a href="?V_dir=<?=$V_dir?>&V_mod=<?=$V_mod?>&func=sql&server=<?php echo $server.$SESSION_SID;?>&db=mysql&sql_query=<?php echo urlencode("SHOW STATUS");?>">
            <?php echo $strMySQLShowStatus;?></a> <?php print show_docu("manual_Reference.html#Show");?>
            <li><a href="?V_dir=<?=$V_dir?>&V_mod=<?=$V_mod?>&func=sql&server=<?php echo $server.$SESSION_SID;?>&db=mysql&sql_query=<?php echo urlencode("SHOW VARIABLES");?>">
            <?php echo $strMySQLShowVars;?></a> <?php print show_docu("manual_Performance.html#Performance");?>
            <li><a href="?V_dir=<?=$V_dir?>&V_mod=<?=$V_mod?>&func=sql&server=<?php echo $server.$SESSION_SID;?>&db=mysql&sql_query=<?php echo urlencode("SHOW PROCESSLIST");?>">
            <?php echo $strMySQLShowProcess;?></a> <?php print show_docu("manual_Reference.html#Show");?>
            <li>
            <a href="?V_dir=<?=$V_dir?>&V_mod=<?=$V_mod?>&func=&server=<?php echo $server.$SESSION_SID;?>&mode=reload"><?php echo $strReloadMySQL; ?></a> <?php print show_docu("manual_Reference.html#Flush");
        }
    }
}
?>
</ul>
<table border=0 cellpadding=0 cellspacing=0><tr><td align=right>
[phpMyAdmin based]
</td></tr></table>
<?
if(!get_magic_quotes_gpc()) print($strEnableMagicQuotes);
require ("modules/$V_dir/footer.inc.php");
?>
