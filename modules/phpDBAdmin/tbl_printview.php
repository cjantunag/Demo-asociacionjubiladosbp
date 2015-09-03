<?
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

if(!isset($message))
    include_once("modules/$V_dir/header.inc.php");
else
    show_message($message);
    
unset($sql_query);
global $RAD_dbi;

$result = sql_query("SHOW KEYS FROM $table", $RAD_dbi);
$primary = "";

while($row = sql_fetch_array($result))
    if ($row["Key_name"] == "PRIMARY")
        $primary .= "$row[Column_name], ";

if (strtolower(_DEF_dbtype)=="oracle") $result=sql_query("DESCRIBE ".$table, $RAD_dbi);
else $result=sql_query("SHOW FIELDS FROM ".$table, $RAD_dbi);

?>
<table border=<?php echo $cfgBorder;?>>
<TR>
   <TH><?php echo $strField; ?></TH>
   <TH><?php echo $strType; ?></TH>
   <TH><?php echo $strAttr; ?></TH>
   <TH><?php echo $strNull; ?></TH>
   <TH><?php echo $strDefault; ?></TH>
   <TH><?php echo $strExtra; ?></TH>
</TR>

<?php
$i=0;

while($row= sql_fetch_array($result))
{
    $query = "db=$db&table=$table&subfunc=tbl_properties";
    $bgcolor = $cfgBgcolorOne;
    $i % 2  ? 0: $bgcolor = $cfgBgcolorTwo;
    $i++;
    ?>
         <tr bgcolor="<?php echo $bgcolor;?>">     
         <td><?php echo $row["Field"];?>&nbsp;</td>
         <td>   
    <?php 
	$Type = stripslashes($row["Type"]);
    $Type = eregi_replace("BINARY", "", $Type);
    $Type = eregi_replace("ZEROFILL", "", $Type);
    $Type = eregi_replace("UNSIGNED", "", $Type);
	echo $Type;
	?>&nbsp;</td>
         <td>
    <?php 
    $binary   = eregi("BINARY", $row["Type"], $test);
    $unsigned = eregi("UNSIGNED", $row["Type"], $test);
    $zerofill = eregi("ZEROFILL", $row["Type"], $test);
    $strAttribute="";
    if ($binary) 
        $strAttribute="BINARY";
    if ($unsigned) 
        $strAttribute="UNSIGNED";
    if ($zerofill) 
        $strAttribute="UNSIGNED ZEROFILL";  
	echo $strAttribute;
    $strAttribute="";
	?>
	 &nbsp;</td>
	 <td><?php if ($row["Null"] == "") { echo $strNo;} else {echo $strYes;}?>&nbsp;</td>
         <td><?php if(isset($row["Default"])) echo $row["Default"];?>&nbsp;</td>
         <td><?php echo $row["Extra"];?>&nbsp;</td>
         </tr>
    <?php
}
?>
</table>
<?php

$result = sql_query("SHOW KEYS FROM ".$table, $RAD_dbi);
if(sql_num_rows($result)>0)
{
    ?>
    <br>
    <table border=<?php echo $cfgBorder;?>>
      <tr>
      <th><?php echo $strKeyname; ?></th>
      <th><?php echo $strUnique; ?></th>
      <th><?php echo $strField; ?></th>
      </tr>
    <?php
    for($i=0 ; $i<sql_num_rows($result); $i++)
    {
        $row = sql_fetch_array($result);
        echo "<tr>";
        if($row["Key_name"] == "PRIMARY")
        {
            $sql_query = urlencode("ALTER TABLE ".$table." DROP PRIMARY KEY");
            $zero_rows = urlencode($strPrimaryKey." ".$strHasBeenDropped);
        }
        else
        {
            $sql_query = urlencode("ALTER TABLE ".$table." DROP INDEX ".$row["Key_name"]);
            $zero_rows = urlencode($strIndex." ".$row["Key_name"]." ".$strHasBeenDropped);
        }
          
        ?>
          <td><?php echo $row["Key_name"];?></td>
          <td><?php
        if($row["Non_unique"]=="0")
            echo $strYes;
        else
            echo $strNo;
        ?></td>
        <td><?php echo $row["Column_name"];?></td>
        <?php
        echo "</tr>";
    }
    print "</table>\n";
}

require_once("modules/$V_dir/footer.inc.php");
?>
