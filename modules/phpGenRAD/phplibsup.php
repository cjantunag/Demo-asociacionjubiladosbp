<?
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: ../../index.php");
    die();
}
// Determine if particular engine has specific functions: database listing, table listing, row fields listing, sql type
	class DBInfo {
		var $name;
		var $filename;
		var $sup_dblist;
		var $sup_tablelist;
		var $sup_tableinfo;
		var $type_map;
		function canbenull($flags) { return 0; }
		function isprimarykey($flags) { return 0; }
		function typetr ($type, $flags) { return "string"; }
// This function is called if $DBInfo -> sup_dblist == "yes"
// It returns array of database names
		function list_databases($host, $user, $pass) {
			return false;
		}
	}
	
	class MySQL_DBInfo extends DBInfo {
		function canbenull($flags) {
			return eregi("not_null", $flags) ? 0 : 1;
		}
		function isautoincrement($flags) {
			return eregi("auto_increment", $flags) ? 1 : 0;
		}
		function typetr($type, $flags) {
			if (eregi("(int|float|double)", $type)) {
				return "num";
			} else if (eregi("(blob|text)", $type)) {
				return "blob";
			} else if (eregi("(datetime)", $type)) {
				return "datetime";
			} else if (eregi("(date)", $type)) {
				return "date";
			} else if (eregi("(time)", $type)) {
				return "time";
			} else if (eregi("enum", $flags)) {
				return "enum";
			}
			return "string";
		}
		function isprimarykey($flags) {
			return eregi("primary_key", $flags) ? 1 : 0;
		}
		function list_databases($host, $user, $pass) {
			mysql_connect($host, $user, $pass);
			$result = mysql_list_dbs();
			for ($i = 0; $i < mysql_num_rows($result); $i++) {
				$ret[] = mysql_dbname($result, $i);
			}
			return $ret;
		}
	}

	class Oracle_DBInfo extends DBInfo {
		function canbenull($flags) {
			return eregi("not_null", $flags) ? 0 : 1;
		}
		function isautoincrement($flags) {
			return eregi("auto_increment", $flags) ? 1 : 0;
		}
		function typetr($type, $flags) {
			if (eregi("(int|float|double)", $type)) {
				return "num";
			} else if (eregi("(blob|text)", $type)) {
				return "blob";
			} else if (eregi("(datetime)", $type)) {
				return "datetime";
			} else if (eregi("(date)", $type)) {
				return "date";
			} else if (eregi("(time)", $type)) {
				return "time";
			} else if (eregi("enum", $flags)) {
				return "enum";
			}
			return "string";
		}
		function isprimarykey($flags) {
			return eregi("primary_key", $flags) ? 1 : 0;
		}
	}

	$mysql = new MySQL_DBInfo;
	$mysql -> name = "MySQL";
	$mysql -> sup_dblist = "yes";
	$mysql -> sup_tablelist = "yes";
	$mysql -> sup_tableinfo = "yes";

	$pgsql = new DBInfo;
	$pgsql -> name = "PostgreSQL";
	$pgsql -> sup_dblist = "no";
	$pgsql -> sup_tablelist = "yes";
	$pgsql -> sup_tableinfo = "yes";

	$oracle = new Oracle_DBInfo;
	$oracle -> name = "Oracle";
	$oracle -> sup_dblist = "no";
	$oracle -> sup_tablelist = "yes";
	$oracle -> sup_tableinfo = "yes";
?>
