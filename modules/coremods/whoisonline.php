<?
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: ../../index.php");
    die();
}
	include_once("functions.php");

	include ("header.php");
	OpenTable();


	$TMP_past = time()-1800;

	$TMP_usercount=0;
	$TMP_anoncount=0;
	$TMP_result = sql_query("SELECT * FROM "._DBT_STATS." WHERE "._DBF_S_ENDTIME.">'$TMP_past'", $RAD_dbi);
	$TMP_users.="<ul>";
	while($TMP_who = sql_fetch_array($TMP_result, $RAD_dbi)) {
		if ($TMP_who[_DBF_S_USER]=="") $TMP_anoncount++;
		else {
			$TMP_usercount++;
			$TMP_userName=RAD_userName($TMP_who[_DBF_S_USER]);
			$TMP_users.=$TMP_usercount.". ".$TMP_userName." (".$TMP_who[_DBF_S_USER].")<br>";
		}
	}
	$TMP_users.="</ul>";
	echo $TMP_usercount." "._MEMBERS.".<br>".$TMP_users."<br>";
	echo $TMP_anoncount." "._GUESTS.".<br>";

	CloseTable();
	include ("footer.php"); 
?>

