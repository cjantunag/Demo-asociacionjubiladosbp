<?
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

if(!isset($message)) 
    include("modules/$V_dir/header.inc.php");
else
    show_message($message);

$tables = sql_list_tables($db);
$num_tables=0;
while($row=sql_fetch_array($tables, $RAD_dbi)) { $A_tables[$num_tables]=$row[0]; $num_tables++; }

if($num_tables == 0) {
    echo $strNoTablesFound;
} else {
    $i = 0;
    
    echo "<table border=$cfgBorder>\n";
    echo "<th>$strTable</th>";
    echo "<th>$strRecords</th>";
    while($i < $num_tables) {
        $table = $A_tables[$i];
        $query = "?V_dir=$V_dir&V_mod=$V_mod&db=$db&table=$table&func=db_details".$SESSION_SID;
        $bgcolor = $cfgBgcolorOne;
        $i % 2  ? 0: $bgcolor = $cfgBgcolorTwo;
        ?>
           <tr bgcolor="<?php echo $bgcolor;?>">
           <td class=data><b><?php echo $table;?></b></td>
           <td align="right">&nbsp;<?php count_records($db,$table) ?></td>
         </tr>
        <?php
        $i++;
    }
    
    echo "</table>\n";
}

require ("modules/$V_dir/footer.inc.php");
?>
