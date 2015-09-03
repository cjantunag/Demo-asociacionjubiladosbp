<?
global $PHP_SELF, $RAD_dbi;
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

if ($db=="") $db=_DEF_dbname;
$RAD_dbi=sql_connect(_DEF_dbhost, _DEF_dbuname, _DEF_dbpass, $db);

// Setting magic_quotes_runtime - do not change!
set_magic_quotes_runtime(0);
ini_set("magic_quotes_gpc", 1);
ini_set("magic_quotes_runtime", 0);

require_once("modules/$V_dir/spanish.inc.php");

$cfgManualBase = "http://www.sql.com/";

$cfgConfirm = true;

$cfgBorder      = "0";
$cfgThBgcolor  = "#D3DCE3";
$cfgBgcolorOne = "#CCCCCC";
$cfgBgcolorTwo = "#DDDDDD";
$cfgMaxRows = 30;
$cfgMaxInputsize = "300px";
$cfgOrder = "ASC";
$cfgShowBlob = true;
$cfgShowSQL = true;

$cfgColumnTypes = array(
   "TINYINT",
   "SMALLINT",
   "MEDIUMINT",
   "INT",
   "BIGINT",
   "FLOAT",
   "DOUBLE",
   "DECIMAL",
   "DATE",
   "DATETIME",
   "TIMESTAMP",
   "TIME",
   "YEAR",
   "CHAR",
   "VARCHAR",
   "TINYBLOB",
   "TINYTEXT",
   "TEXT",
   "BLOB",
   "MEDIUMBLOB",
   "MEDIUMTEXT",
   "LONGBLOB",
   "LONGTEXT",
   "ENUM",
   "SET");

$cfgFunctions = array(
   "ASCII",
   "CHAR",
   "SOUNDEX",
   "CURDATE",
   "CURTIME",
   "FROM_DAYS",
   "FROM_UNIXTIME",
   "NOW",
   "PASSWORD",
   "PERIOD_ADD",
   "PERIOD_DIFF",
   "TO_DAYS",
   "USER",
   "WEEKDAY",
   "RAND");

$cfgAttributeTypes = array(
   "",
   "BINARY",
   "UNSIGNED",
   "UNSIGNED ZEROFILL");

?>
