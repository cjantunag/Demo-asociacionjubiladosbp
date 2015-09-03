<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

@set_time_limit(600);
$crlf="\n";
if(empty($asfile)) {
    include("modules/$V_dir/header.inc.php");
    echo "<pre>\n";
} else {
    include_once("modules/$V_dir/lib.inc.php");
    header("Content-disposition: filename=$db.sql");
    header("Content-type: application/octetstream");
    header("Pragma: no-cache");
    header("Expires: 0");
   
    // doing some DOS-CRLF magic...
    $client = getenv("HTTP_USER_AGENT");
    if(ereg('[^(]*\((.*)\)[^)]*',$client,$regs)) {
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

$tables = mysql_list_tables($db);

$num_tables = @mysql_num_rows($tables);
if($num_tables == 0) {
    echo $strNoTablesFound;
} else {
    $i = 0;
    print "# MySQL-Dump$crlf";
    print "# $strHost: " . $cfgServer['host'];
    if(!empty($cfgServer['port'])) print ":" . $cfgServer['port'];

    print " $strDatabase: $db$crlf";
    while($i < $num_tables) { 
        $table = mysql_tablename($tables, $i);
        print $crlf;
        print "# --------------------------------------------------------$crlf";
        print "#$crlf";
        print "# $strTableStructure '$table'$crlf";
        print "#$crlf";
        print $crlf;
        echo get_table_def($db, $table, $crlf).";$crlf$crlf";
        if($what == "data" || $what == "data1") {
            print "#$crlf";
            print "# $strDumpingData '$table'$crlf";
            print "#$crlf";
            print $crlf;
            get_table_content($db, $table, "my_handler", $what);
        }
        $i++;
    }
}

if(empty($asfile)) {
    print "</pre>\n";
    include ("modules/$V_dir/footer.inc.php");
}
?>
