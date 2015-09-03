<?
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");
global $db;
?>

<A HREF="index.php?V_dir=<?=$V_dir?>&V_mod=<?=$V_mod?><?php echo $SESSION_SID; ?>"><?php echo $strHome;?></A><br><hr noshade size=1>

<?php
	if(empty($dblist)) {
		if (strtolower(_DEF_dbtype)=="oracle") $dbs = sql_query("select * from user_tablespaces;", $RAD_dbi);
		$dbs = sql_query("SHOW DATABASES", $RAD_dbi);
		$num_dbs = sql_num_rows($dbs);
	} else {
		$num_dbs = count($dblist);
	}
//	$num_dbs=1;	// only RAD's database
//	if (substr($db,0,strlen(_DEF_dbname))==_DEF_dbname) {
//		$dbs[0]=$db;
//		$dblist[0]=$db;
//	} else {
//		$dbs[0]=_DEF_dbname;	// only RAD's database
//		$dblist[0]=_DEF_dbname;	// only RAD's database
//	}
	if ($db=="") $db=_DEF_dbname;
	for($i=0; $i<$num_dbs; $i++) {
		if (empty($dblist))
			$dbx = sql_dbname($dbs, $i);
		else
			$dbx = $dblist[$i];
		if (strtoupper(substr($dbx,0,strlen(_DEF_dbname)))!=strtoupper(_DEF_dbname)) continue;
		$j = $i + 2;
		$tables = sql_list_tables($dbx);
		$num_tables=0;
		while($row=sql_fetch_array($tables, $RAD_dbi)) { $A_tables[$num_tables]=$row[0]; $num_tables++; }
		if (strtoupper($db)!=strtoupper($dbx)) {
?>
      <a HREF="index.php?V_dir=<?=$V_dir?>&V_mod=<?=$V_mod?>&func=db_details<?php echo $SESSION_SID;?>&db=<?php echo $dbx;?>">
	<img src="modules/<?=$V_dir?>/images/plus.gif" border="0" alt="<?php echo $dbx;?>">
<?php echo $dbx." (".$num_tables.")";?>
      </a><br>
<?php
		} else {
?>
      <a HREF="index.php?V_dir=<?=$V_dir?>&V_mod=<?=$V_mod?>&func=db_details<?php echo $SESSION_SID;?>&db=<?php echo $dbx;?>">
	<img src="modules/<?=$V_dir?>/images/minus.gif" border="0" alt="<?php echo $dbx;?>">
<?php echo $dbx." (".$num_tables.")";?>
      </a><br>
<?php
		}
		$TMP_linkgen="";
		$TMP_Alinkgen="";
		if (is_modulepermitted("","phpGenRAD","index")||is_modulepermitted("","phpGenRAD","indexRAD")) $TMP_linkgen=$PHP_SELF."?V_dir=phpGenRAD&V_mod=indexRAD&V_submod=pregen".$SESSION_SID."&deftable=";
		//for($j=0; $j<$num_tables; $j++) {
		for($i=0; $i<$num_tables; $i++) {
			$tablex = $A_tables[$i];
			if ($TMP_linkgen!="") $TMP_Alinkgen="<a href='".$TMP_linkgen.$tablex."' target=_blank><img src='images/preferences.gif' title='Genera Modulo' alt='Genera Modulo'></a>";
			if (strtoupper($db)==strtoupper($dbx)) {
?>
	<nobr><?=$TMP_Alinkgen?><a href="index.php?V_dir=<?=$V_dir?>&V_mod=<?=$V_mod?>&func=tbl_relations&<?php echo $SESSION_SID;?>&db=<?php echo $dbx;?>&table=<?php echo urlencode($tablex);?>"><img src="images/collapse.gif" border="0" alt="Info: <?php echo $tablex;?>"></a><a href="index.php?V_dir=<?=$V_dir?>&V_mod=<?=$V_mod?>&func=sql&<?php echo $SESSION_SID;?>&db=<?php echo $dbx;?>&table=<?php echo urlencode($tablex);?>&sql_query=<?php echo urlencode("SELECT * FROM $tablex");?>&pos=0"><img src="modules/<?=$V_dir?>/images/browse.gif" border="0" alt="<?php echo $strBrowse.": ".$tablex;?>"></a> <a class="item" HREF="index.php?V_dir=<?=$V_dir?>&V_mod=<?=$V_mod?>&func=tbl_properties&<?php echo $SESSION_SID;?>&db=<?php echo $dbx;?>&table=<?php echo urlencode($tablex);?>"><?php echo $tablex;?></a></nobr><br>
<?php
			}
    		}
	}
?>
