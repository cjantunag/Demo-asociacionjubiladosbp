<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

@set_time_limit(600);
$crlf="\n";

if(empty($asfile)) { 
    include_once("modules/$V_dir/header.inc.php");
    print "<pre>\n";
} else {
    include_once("modules/$V_dir/lib.inc.php");
    $ext = "sql";
    if($what == "csv")
        $ext = "csv";
    header("Content-disposition: filename=$table.$ext");
	header("Content-type: application/octetstream");
	header("Pragma: no-cache");
	header("Expires: 0");
	
    // doing some DOS-CRLF magic...
	$client=getenv("HTTP_USER_AGENT");
	if(ereg('[^(]*\((.*)\)[^)]*',$client,$regs)) 
    {
	    $os = $regs[1];
        // this looks better under WinX
        if (eregi("Win",$os)) 
            $crlf="\r\n";
	}
}

function my_handler($sql_insert) {
    global $crlf, $asfile;
    if(empty($asfile))
        echo htmlspecialchars("$sql_insert;$crlf");
    else
        echo "$sql_insert;$crlf";
 }
 
function my_csvhandler($sql_insert) {
    global $crlf, $asfile;
    if(empty($asfile))
        echo htmlspecialchars("$sql_insert;$crlf");
    else
        echo "$sql_insert;$crlf";
}

if($what != "csv") {
    print "# MySQL-Dump$crlf";
    print "# $strHost: " . $cfgServer['host'];
    if(!empty($cfgServer['port'])) 
        print ":" . $cfgServer['port'];
    print " $strDatabase: $db$crlf";
    print "# --------------------------------------------------------$crlf";
    print "$crlf#$crlf";
    print "# $strTableStructure '$table'$crlf";
    print "#$crlf$crlf";

    print get_table_def($db, $table, $crlf).";$crlf";

    if($what == "data" || $what == "data1") {
	    print "$crlf#$crlf";
        print "# $strDumpingData '$table'$crlf"; 
        print "#$crlf$crlf";

        get_table_content($db, $table, "my_handler", $what);
    }
} else { // $what != "csv"
	$result = mysql_db_query($db, "SHOW FIELDS FROM $table") or mysql_die();
	while($row= mysql_fetch_array($result)) { echo $row["Field"].$separator; }
	echo $row["Field"]."\n";
  	get_table_csv($db, $table, $separator, "my_csvhandler");
}

if(empty($asfile)) {
    print "</pre>\n";
    include_once("modules/$V_dir/footer.inc.php");
}
?>
