<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}
////////////////////////////////////////////////////////////////////////////////////////////////
/* _DEF_dbtype = "MySQL"; */
/* _DEF_dbtype = "Oracle"; */
/* _DEF_dbtype = "mSQL"; */
/* _DEF_dbtype = "PostgreSQL"; */
/* _DEF_dbtype = "PostgreSQL_local";// When postmaster start without "-i" option. */
/* _DEF_dbtype = "ODBC"; */
/* _DEF_dbtype = "ODBC_Adabas"; */
/* _DEF_dbtype = "Interbase"; */
/* _DEF_dbtype = "Sybase"; */
////////////////////////////////////////////////////////////////////////////////////////////////
class ResultSet {
	var $result;
	var $total_rows;
	var $fetched_rows;
	function set_result( $res ) { $this->result = $res; }
	function get_result() { return $this->result; }
	function set_total_rows( $rows ) { $this->total_rows = $rows; }
	function get_total_rows() { return $this->total_rows; }
	function set_fetched_rows( $rows ) { $this->fetched_rows = $rows; }
	function get_fetched_rows() { return $this->fetched_rows; }
	function increment_fetched_rows() { $this->fetched_rows = $this->fetched_rows + 1; }
}
////////////////////////////////////////////////////////////////////////////////////////////////
// sql_connect($host, $user, $password, $db) returns the connection ID
////////////////////////////////////////////////////////////////////////////////////////////////
function sql_connect($host, $user, $password, $db) {
if(_SQL_DEBUG>0 && _SQL_DEBUG!="_SQL_DEBUG") echo "# sql_connect H=$host U=$user DB=$db  .";

switch (_DEF_dbtype) {
    case "MySQL":
	$RAD_dbi=mysql_connect($host, $user, $password);
	mysql_select_db($db);
	if (_DEF_sqlcharacter_set!="" && _DEF_sqlcharacter_set!="_DEF_sqlcharacter_set") sql_query("SET NAMES '"._DEF_sqlcharacter_set."'", $RAD_dbi);
	if(_SQL_DEBUG>0 && _SQL_DEBUG!="_SQL_DEBUG") echo $RAD_dbi."  .<br>\n";
	return $RAD_dbi;
    break;
    case "Oracle":
	$dbhost="//".$host."/".$db;
	$dbhost="".$host."/".$db;
	if (_DEF_sqlcharacter_set!="" && _DEF_sqlcharacter_set!="_DEF_sqlcharacter_set") $RAD_dbi=oci_connect($user, $password, $dbhost, _DEF_sqlcharacter_set);
	else $RAD_dbi=oci_connect($user, $password, $dbhost);
	if(_SQL_DEBUG>0 && _SQL_DEBUG!="_SQL_DEBUG") echo $RAD_dbi."  .<br>\n";
	return $RAD_dbi;
    break;
    case "mSQL":
	$RAD_dbi=msql_connect($host);
	msql_select_db($db);
	return $RAD_dbi;
    break;
    case "PostgreSQL":
	$RAD_dbi=pg_connect("host=$host user=$user password=$password port=5432 dbname=$db");
	return $RAD_dbi;
    break;
    case "PostgreSQL_local":
	$RAD_dbi=pg_connect("user=$user password=$password dbname=$db");
	return $RAD_dbi;
    break;
    case "ODBC":
	$RAD_dbi=odbc_connect($db,$user,$password);
	return $RAD_dbi;
    break;
    case "ODBC_Adabas":
	$RAD_dbi=odbc_connect($host.":".$db,$user,$password);
	return $RAD_dbi;
    break;
    case "Interbase":
	$RAD_dbi=ibase_connect($host.":".$db,$user,$password);
	return $RAD_dbi;
    break;
    case "Sybase":
	$RAD_dbi=sybase_connect($host, $user, $password);
	sybase_select_db($db,$RAD_dbi);
	return $RAD_dbi;
    break;
    default:
    break;
    }
}
////////////////////////////////////////////////////////////////////////////////////////////////
function sql_logout($id) {
switch (_DEF_dbtype) {
    case "MySQL":
	$RAD_dbi=@mysql_close($id);
	return $RAD_dbi;
    break;
    case "Oracle":
	$RAD_dbi=@oci_close($id);
	return $RAD_dbi;
    break;
    case "mSQL":
	$RAD_dbi=@msql_close($id);
	return $RAD_dbi;
    break;
    case "PostgreSQL":
    case "PostgreSQL_local":
	$RAD_dbi=@pg_close($id);
	return $RAD_dbi;
    break;
    case "ODBC":
    case "ODBC_Adabas":
	$RAD_dbi=@odbc_close($id);
	return $RAD_dbi;  
    break;
    case "Interbase":
	$RAD_dbi=@ibase_close($id);
	return $RAD_dbi;
    break;
    case "Sybase":
	$RAD_dbi=@sybase_close($id);
	return $RAD_dbi;
    break;
    default:
    break;
    }
}
////////////////////////////////////////////////////////////////////////////////////////////////
// sql_query($query, $id) executes an SQL statement, returns a result identifier
////////////////////////////////////////////////////////////////////////////////////////////////
function sql_query($query, $id) {
global $SQL_DEBUG, $dbname, $RAD_lastquery, $RAD_lastprintLog, $RAD_lastSQLerror;
$RAD_lastquery=$query;
RAD_checkSQLDelete($query);
if (function_exists("trigger_prequery")) $query=trigger_prequery($query);

if(_DEF_dbreadonly=="1") {
	$TMP_query=strtoupper(trim($query));
	if (substr($TMP_query,0,7)!="SELECT "&&substr($TMP_query,0,5)!="SHOW "&&substr($TMP_query,0,4)!="DESC"&&substr($TMP_query,0,9)!="ALTER SES"&&substr($TMP_query,0,6)!="BEGIN "&&substr($TMP_query,0,10)!="LOCK TABLE"&&substr($TMP_query,0,6)!="COMMIT"&&substr($TMP_query,0,4)!="SHOW"&&substr($TMP_query,0,4)!="SET ") {
		alert($dbname." READONLY DataBase");
		return $dbname." READONLY DataBase.";
	}
}

if ($query!=$RAD_lastprintLog && substr($query,0,24)!="INSERT INTO estadisticas") {
	$TMP_query=strtoupper(trim($query));
	if (substr($TMP_query,0,7)!="SELECT " && substr($TMP_query,0,5)!="SHOW " && substr($TMP_query,0,4)!="SET " && substr($TMP_query,0,4)!="DESC" && substr($TMP_query,0,9)!="ALTER SES" && substr($TMP_query,0,6)!="BEGIN " && substr($TMP_query,0,10)!="LOCK TABLE" && substr($TMP_query,0,6)!="COMMIT" && substr($TMP_query,0,4)!="SHOW") {
		if (substr($TMP_query,0,19)!="UPDATE ESTADISTICAS" && substr($TMP_query,0,24)!="INSERT INTO ESTADISTICAS" && substr($TMP_query,0,19)!="INSERT INTO RAD_log") {
			RAD_printLog($query);
		}
	}
}

$TMP_ini=RAD_microtime();
if(_SQL_DEBUG>0 && _SQL_DEBUG!="_SQL_DEBUG") {
	if (is_numeric($SQL_DEBUG)) $TMP_DEBUG=$id." ".$TMP_ini." SQL query: ".str_replace(",",", ",$query);
	else echo $id." ".$TMP_ini." SQL query: ".str_replace(",",", ",$query);
}
switch (_DEF_dbtype) {
    case "MySQL":
	$res=@mysql_query($query, $id);
	if($err=mysql_errno()) { 
		$traza=print_r(debug_backtrace(),true); 
		$traza=str_replace(">","_",$traza); 
		if (_SQL_DEBUG>0 && _SQL_DEBUG!="_SQL_DEBUG") echo "\n\n<pre>\n".$traza."\n\n".$RAD_lastquery." ".$err.mysql_error()."\n</pre>\n\n"; 
		RAD_logError("SQL ERR: ".$query."\n".$traza." ".$err.mysql_error()); // log every SQL error
		return ""; 
	}
    break;
    case "Oracle":
	$err['message']="";
	//if (strlen($query)>4010 && (strtoupper(substr(trim($query),0,7))=="INSERT " || strtouper(substr(trim($query),0,7))=="UPDATE ")) {
	if (strlen($query)>4010) {
		$err=sql_ocibigquery($query, $id);
	} else {
	  $res=oci_parse($id, $query);
	  if ($res) {
		//$r=oci_execute($res,OCI_DEFAULT);
		if (!oci_execute($res, OCI_COMMIT_ON_SUCCESS)) $err = oci_error($res);
	  } else $err = oci_error($res);
	}
	if($err['message']!="") RAD_printLog("#ERR: ".$err[code]." ".$err["offset"]." ".$err["sqltext"]." ".$err['message']);
	if($err['message']!="") { 
		$traza="Code: ".$err["code"]."\n";
		$traza.="Message: ".$err["message"]."\n";
		$traza.="Position: ".$err["offset"]."\n";
		$traza.="Statement: ".$err["sqltext"]."\n";
		$traza.="Error: ".$err['message']."\n";
		$traza.=print_r(debug_backtrace(),true);
		$traza=str_replace(">","_",$traza);
		$RAD_lastSQLerror=$traza;
		if (_SQL_DEBUG>0 && _SQL_DEBUG!="_SQL_DEBUG") echo "\n\n<! \n".$traza."\n\n >\n".$RAD_lastquery." ".$err['message']."\n\n";
		RAD_logError("SQL ERR: ".$query."\n".$traza); // log every SQL error
		return false;
	}
    break;
    case "mSQL":
	$res=@msql_query($query, $id);
    break;
    case "PostgreSQL":
    case "PostgreSQL_local":
	$res=pg_exec($id,$query);
	$result_set = new ResultSet;
	$result_set->set_result( $res );
	$result_set->set_total_rows( sql_num_rows( $result_set ) );
	$result_set->set_fetched_rows( 0 );
	$res=$result_set;
    break;
    case "ODBC":
    case "ODBC_Adabas":
	$res=@odbc_exec($id,$query);
    break;
    case "Interbase":
	$res=@ibase_query($id,$query);
    break;
    case "Sybase":
	$res=@sybase_query($query, $id);
    break;
    default:
    break;
    }
    if (function_exists("trigger_postquery")) trigger_postquery($query);
    $TMP_exec=RAD_microtime()-$TMP_ini;
    if(_SQL_DEBUG>0 && _SQL_DEBUG!="_SQL_DEBUG") {
	if (is_numeric($SQL_DEBUG)) {
		if ($TMP_exec>$SQL_DEBUG) echo $TMP_DEBUG."[".substr($TMP_exec,0,8)."]<br>\n";
	} else echo "[".substr($TMP_exec,0,8)."]<br>\n";
    }
    if ((_DEF_SQLDELAY_DEBUG>0 && $TMP_exec>_DEF_SQLDELAY_DEBUG)||($TMP_exec>1)) {
	$TMP_trace="";
	if (_DEF_SQLTRACE=="1") {
		$TMP_trace=print_r(debug_backtrace(),true); 
		$TMP_trace=str_replace("\n"," ",$TMP_trace); 
		$TMP_trace=str_replace("    "," ",$TMP_trace); 
		$TMP_trace=str_replace("   "," ",$TMP_trace); 
		$TMP_trace=str_replace("  "," ",$TMP_trace); 
		$TMP_trace="SQLTRACE: ".$TMP_trace." ".$err['message'].str_replace("\n","",$TMP_trace)."\n"; 
	}
	RAD_logError("SQL: [ delay ".substr($TMP_exec,0,5)."] ".$query."\n".$TMP_trace); // log command that delay more than 1 second/_DEF_SQLDELAY_DEBUG 
    }
    //if ($TMP_exec>1) RAD_logError("SQL: [ delay ".substr($TMP_exec,0,5)."] ".$query); // log every command that delay more than 1 second 
    if (substr($TMP_query,0,6)=="INSERT" && substr($TMP_query,0,24)!="INSERT INTO ESTADISTICAS" && substr($TMP_query,0,19)!="INSERT INTO RAD_log") {
	RAD_printLog("# id=".sql_insert_id($id));
    }
    return $res;
}
////////////////////////////////////////////////////////////////////////////////////////////////
// sql_error($res) returns the error string
////////////////////////////////////////////////////////////////////////////////////////////////
function sql_error($res) {
global $RAD_lastSQLerror;
switch (_DEF_dbtype) {
    case "MySQL":
        return mysql_error();
        //return mysql_error($res);
    break;
    case "Oracle":
	$err=oci_error($res);
	$ret=$err['message'];
	if ($ret=="") $ret=$RAD_lastSQLerror;
	return $ret;
    break;
    }
    return "";
}
////////////////////////////////////////////////////////////////////////////////////////////////
// sql_errorno($res) returns the error number
////////////////////////////////////////////////////////////////////////////////////////////////
function sql_errno($res) {
	return sql_errorno($res);
}
function sql_errorno($res) {
switch (_DEF_dbtype) {
    case "MySQL":
        return mysql_errno();
        //return mysql_errno($res);
    break;
    case "Oracle":
	$err=oci_error($res);
	$ret=$err['message'];
	return $ret;
    break;
    }

    return "";
}
////////////////////////////////////////////////////////////////////////////////////////////////
// sql_insert_id($link) returns the last id inserted
////////////////////////////////////////////////////////////////////////////////////////////////
function sql_insert_id($link) {
switch (_DEF_dbtype) {
    case "MySQL":
        return mysql_insert_id($link);
    break;
    }

    return "";
}
////////////////////////////////////////////////////////////////////////////////////////////////
// sql_affected_rows($res)
////////////////////////////////////////////////////////////////////////////////////////////////
function sql_affected_rows($res) {
switch (_DEF_dbtype) {
    case "MySQL":
    	return mysql_affected_rows($res);
    break;
    case "Oracle":
    	return ocirowcount($res);
	//return oci_num_rows($res);
    break;
    }

    return "";
}
////////////////////////////////////////////////////////////////////////////////////////////////
// sql_num_rows($res) given a result identifier, returns the number of affected rows
////////////////////////////////////////////////////////////////////////////////////////////////
function sql_num_rows($res) {
switch (_DEF_dbtype) {
    case "MySQL":
	$rows=mysql_num_rows($res);
	return $rows;
    break;
    case "Oracle":
	$rows=ocirowcount($res);
	//$rows=oci_num_rows($res);
	return $rows;
    break;
    case "mSQL":  
	$rows=msql_num_rows($res);
	return $rows;
    break;
    case "PostgreSQL":
    case "PostgreSQL_local":
	$rows=pg_numrows( $res->get_result() );
	return $rows;
    break;
    case "ODBC":
    case "ODBC_Adabas":
	$rows=odbc_num_rows($res);
	return $rows; 
    break;
    case "Interbase":
	echo "<BR>Error! PHP dosn't support ibase_numrows!<BR>";
	return $rows; 
    break;
    case "Sybase":
	$rows=sybase_num_rows($res);
	return $rows; 
    break;
    default:
    break;
    }
}
////////////////////////////////////////////////////////////////////////////////////////////////
// sql_list_fields($tab), returns the name of field's table
////////////////////////////////////////////////////////////////////////////////////////////////
function sql_list_fields($table, $RAD_dbi) {
switch (_DEF_dbtype) {
    case "MySQL":
	return sql_query("SHOW COLUMNS FROM ".$table, $RAD_dbi);
    break;
    case "Oracle":
	return sql_query("select COLUMN_NAME,DATA_TYPE,DATA_LENGTH,DATA_DEFAULT from all_tab_columns where table_name='".$table."' order by column_id", $RAD_dbi);
    break;
    }
}
////////////////////////////////////////////////////////////////////////////////////////////////
// sql_num_fields($res) given a result identifier, returns the number of field's row
////////////////////////////////////////////////////////////////////////////////////////////////
function sql_num_fields($res) {
switch (_DEF_dbtype) {
    case "MySQL":
	return mysql_num_fields($res);
    break;
    case "Oracle":
	return oci_num_fields($res);
    break;
    }
}
////////////////////////////////////////////////////////////////////////////////////////////////
// sql_field_name($resi, $num) given a result identifier and a field's number, returns the field's name
////////////////////////////////////////////////////////////////////////////////////////////////
function sql_field_name($res, $num) {
switch (_DEF_dbtype) {
    case "MySQL":
	return mysql_field_name($res, $num);
    break;
    case "Oracle":
	return oci_field_name($res, $num);
    break;
    }
}
////////////////////////////////////////////////////////////////////////////////////////////////
// sql_fetch_row(&$res,$row)
// given a result identifier, returns an array with the resulting row
// Needs also a row number for compatibility with PostgreSQL
////////////////////////////////////////////////////////////////////////////////////////////////
function sql_fetch_row(&$res, $nr) {
switch (_DEF_dbtype) {
    case "MySQL":
	$row = mysql_fetch_row($res);
	return $row;
    break;
    case "Oracle":
	//$row = oci_fetch_row($res, OCI_RETURN_NULLS);
	$row = oci_fetch_row($res, OCI_RETURN_LOBS+OCI_RETURN_NULLS+OCI_BOTH);
	if (is_array($row)) if(count($row)>0) foreach($row as $TMP_k=>$TMP_v) $row[strtolower($TMP_k)]=$row[$TMP_k];
	return $row;
    break;
    case "mSQL":
	$row = msql_fetch_row($res);
	return $row;
    break;
    case "PostgreSQL":
    case "PostgreSQL_local":
	if ( $res->get_total_rows() > $res->get_fetched_rows() ) {
		$row = pg_fetch_row($res->get_result(), $res->get_fetched_rows() );
		$res->increment_fetched_rows();
		return $row;
	} else {
		return false;
	}
    break;
    case "ODBC":
    case "ODBC_Adabas":
	$row = array();
	$cols = odbc_fetch_into($res, $nr, $row);
	return $row;
    break;
    case "Interbase":
	$row = ibase_fetch_row($res);
	return $row;
    break;
    case "Sybase":
	$row = sybase_fetch_row($res);
	return $row;
    break;
    default:
    break;
    }
}
////////////////////////////////////////////////////////////////////////////////////////////////
// sql_fetch_array($res,$row)
// given a result identifier, returns an associative array
// with the resulting row using field names and field numbers as keys.
// Needs also a row number for compatibility with PostgreSQL.
////////////////////////////////////////////////////////////////////////////////////////////////
function sql_fetch_array(&$res, $nr) {
global $RAD_lastquery;
switch (_DEF_dbtype) {
    case "MySQL":
	$row = array();
	$row = mysql_fetch_array($res);
	if($err=mysql_errno()) { 
		$traza=print_r(debug_backtrace(),true); 
		$traza=str_replace(">","_",$traza); 
		if (_SQL_DEBUG>0 && _SQL_DEBUG!="_SQL_DEBUG") echo "\n\n<pre>\n".$traza."\n\n".$RAD_lastquery." ".$err.mysql_error()."\n</pre>\n\n"; 
		RAD_logError("SQL ERR: ".$query."\n".$traza." ".$err.mysql_error()); // log every SQL error
		return ""; 
	}
	return $row;
    break;
    case "Oracle":
	$row = array();
	//$row = oci_fetch_array($res, OCI_RETURN_NULLS);
	$row = oci_fetch_array($res, OCI_RETURN_LOBS+OCI_RETURN_NULLS+OCI_BOTH);
	if (is_array($row)) if(count($row)>0) foreach($row as $TMP_k=>$TMP_v) $row[strtolower($TMP_k)]=$row[$TMP_k];
	return $row;
    break;
    case "mSQL":
	$row = array();
	$row = msql_fetch_array($res);
	return $row;
    break;
    case "PostgreSQL":
    case "PostgreSQL_local":
	if( $res->get_total_rows() > $res->get_fetched_rows() ) {
		$row = array();
		$row = pg_fetch_array($res->get_result(), $res->get_fetched_rows() );
		$res->increment_fetched_rows();
		return $row;
	} else {
		return false;
	}
    break;
/* ODBC doesn't have a native _fetch_array(), so we have to 
 * use a trick. Beware: this might cause HUGE loads! */
    case "ODBC":
	$row = array();
	$result = array();
	$result = odbc_fetch_row($res, $nr);
	$nf = odbc_num_fields($res); /* Field numbering starts at 1 */
	for($count=1; $count < $nf+1; $count++) {
		$field_name = odbc_field_name($res, $count);
		$field_value = odbc_result($res, $field_name);
		$row[$field_name] = $field_value;
	}
	return $row;
    break;
    case "ODBC_Adabas":
	$row = array();
	$result = array();
	$result = odbc_fetch_row($res, $nr);
	$nf = count($result)+2; /* Field numbering starts at 1 */
	for($count=1; $count < $nf; $count++) {
		$field_name = odbc_field_name($res, $count);
		$field_value = odbc_result($res, $field_name);
		$row[$field_name] = $field_value;
	}
	return $row;
    break;
    case "Interbase":
	$orow=ibase_fetch_object($res);
	$row=get_object_vars($orow);
	return $row;
    break;
    case "Sybase":
	$row = sybase_fetch_array($res);
	return $row;
    break;
    }
}
////////////////////////////////////////////////////////////////////////////////////////////////
// sql_fetch_assoc($res,$row)
// given a result identifier, returns an associative array
// with the resulting row using only field names as keys.
////////////////////////////////////////////////////////////////////////////////////////////////
function sql_fetch_assoc(&$res, $nr) {
global $RAD_lastquery;
switch (_DEF_dbtype) {
    case "MySQL":
	$row = array();
	$row = mysql_fetch_assoc($res);
	if($err=mysql_errno()) { $traza=print_r(debug_backtrace(),true); $traza=str_replace(">","_",$traza); echo "\n\n<pre>\n".$traza."\n\n".$RAD_lastquery." ".$err.mysql_error()."\n</pre>\n\n"; return ""; }
	return $row;
    break;
    case "Oracle":
	$row = array();
	//$row = oci_fetch_assoc($res, OCI_RETURN_NULLS);
	$row = oci_fetch_assoc($res, OCI_RETURN_LOBS+OCI_RETURN_NULLS+OCI_BOTH);
	if (is_array($row)) if(count($row)>0) foreach($row as $TMP_k=>$TMP_v) $row[strtolower($TMP_k)]=$row[$TMP_k];
	return $row;
    break;
    }
}
////////////////////////////////////////////////////////////////////////////////////////////////
function sql_fetch_object(&$res, $nr) {
switch (_DEF_dbtype) {
    case "MySQL":
	$row = mysql_fetch_object($res);
	if($row) return $row;
	else return false;
    break;
    case "Oracle":
	$row = oci_fetch_object($res);
	if($row) return $row;
	else return false;
    break;
    case "mSQL":
	$row = msql_fetch_object($res);
	if($row) return $row;
	else return false;
    break;
    case "PostgreSQL":
    case "PostgreSQL_local":
	if( $res->get_total_rows() > $res->get_fetched_rows() ) {
		$row = pg_fetch_object( $res->get_result(), $res->get_fetched_rows() );
		$res->increment_fetched_rows();
		if($row) return $row;
		else return false;
	} else {
		return false;
	}
    break;
    case "ODBC":
	$result = odbc_fetch_row($res, $nr);
	if(!$result) return false;
	$nf = odbc_num_fields($res); /* Field numbering starts at 1 */
      for($count=1; $count < $nf+1; $count++) {
            $field_name = odbc_field_name($res, $count);
            $field_value = odbc_result($res, $field_name);
            $row->$field_name = $field_value;
	}
	return $row;
    break;
    case "ODBC_Adabas":
	$result = odbc_fetch_row($res, $nr);
	if(!$result) return false;
	$nf = count($result)+2; /* Field numbering starts at 1 */
	for($count=1; $count < $nf; $count++) {
		$field_name = odbc_field_name($res, $count);
		$field_value = odbc_result($res, $field_name);
		$row->$field_name = $field_value;
	}
	return $row;
    break;
    case "Interbase":
	$orow = ibase_fetch_object($res);
	if($orow) {
		$arow=get_object_vars($orow);
		while(list($TMP_name,$key)=each($arow)) {
			$TMP_name=strtolower($TMP_name);
			$row->$TMP_name=$key;
		}
		return $row;
	}else return false;
    break;
    case "Sybase":
	$row = sybase_fetch_object($res);
	return $row;
    break;
    }
}
////////////////////////////////////////////////////////////////////////////////////////////////
function sql_list_tables($dbx) {
global $RAD_dbi;
switch (_DEF_dbtype) {
    case "MySQL":
	$tables = mysql_list_tables($dbx);
    break;
    case "Oracle":
	$tables=sql_query("SELECT TABLE_NAME FROM TABS ORDER BY TABLE_NAME",$RAD_dbi);
    break;
    }
return $tables;
}
////////////////////////////////////////////////////////////////////////////////////////////////
function sql_free_result($res) {
switch (_DEF_dbtype) {
    case "MySQL":
	$row = mysql_free_result($res);
	return $row;
    break;
    case "Oracle":
	$row = oci_free_statement($res);
	return $row;
    break;
    case "mSQL":
	$row = msql_free_result($res);
	return $row;
    break;
    case "PostgreSQL":
    case "PostgreSQL_local":
	$rows=pg_FreeResult( $res->get_result() );
	return $rows;
    break;
    case "ODBC":
    case "ODBC_Adabas":
	$rows=odbc_free_result($res);
	return $rows;
    break;
    case "Interbase":
	echo "<BR>Error! PHP dosen't support ibase_numrows!<BR>";
	return $rows;
    break;
    case "Sybase":
	$rows=sybase_free_result($res);
	return $rows;
    break;
    }
}
/////////////////////////////////////////////////////////////////////////////
function converttosql($value, $type="Text") {
	if($value == "") return "NULL";
	else {
		if($type == "Number") return doubleval($value);
		else {
			if(get_magic_quotes_gpc()) $value = stripslashes ($value);
			if (_DEF_scapeSQL!="" && _DEF_scapeSQL!="_DEF_scapeSQL") {
				$value = str_replace("'",_DEF_scapeSQL."'",$value);
			} else {
				$value = str_replace("'","\'",$value);
			}
			//$value = str_replace("'","''",$value);
			//$value = str_replace("\\","\\\\",$value);
			return "'".$value."'";
		}
	}
}
////////////////////////////////////////////////////////////////////////////////////////////////
// sql_ocibigquery($query, $id) executes a BIG SQL Oracle statement, returns a result identifier
////////////////////////////////////////////////////////////////////////////////////////////////
function sql_ocibigquery($sql, $id) {
// desmiembra las querys largas (>4000 bytes) y quita sus campos, sustituyendolos por variables OCI_Fxx y
// en esas variables almacena sus valores, para despues ponerlas en oci_bind_by_name 
	$pos1=strpos($sql,"'"); 
	$pos2=strpos($sql,'"'); 
	if ($pos1>0 && $pos2>0) { // mira si el separador de literales de campo es ' o "
		if ($pos1<$pos2) $TMP_sep="'";
		else $TMP_sep='"';
	} else {
		if ($pos1>0) $TMP_sep="'";
		else $TMP_sep='"';
	}
	$posini=strpos($sql,$TMP_sep);
	$sql2=substr($sql,0,$posini);  // sql2 almacena la query sql destripada. guarda el primer tramo hasta el separador 
	$resto=substr($sql,$posini+1); // resto de query sin destripar aun
	$numcampo=0;
	//echo "\nquery inicial=".$sql."\nseparador=".$TMP_sep."\n";
	while(strlen($resto)>0) { // va extrayendo campo a campo delimitado por el separador TMP_sep de la query que queda 
		$resto2=str_replace($TMP_sep.$TMP_sep,"__",$resto);  // quita los separadores escapados y guarda en resto2
		//$resto2=str_replace("\\".$TMP_sep,"__",$resto);
		$posfin=strpos($resto2,$TMP_sep); // busca donde acaba el primer campo de resto2
		$campo=substr($resto,0,$posfin); // recoge el literal del campo de resto
		global ${"OCI_F".$numcampo};
		$campo=str_replace($TMP_sep.$TMP_sep,$TMP_sep,$campo); // guarda valor del campo, desescapando el separador
		${"OCI_F".$numcampo}=$campo; // lo guarda en la variable OCI_Fxx para despues hacer el oci_bind_by_name
		$resto=substr($resto,$posfin+1); // salta al siguiente campo
		//echo "\nCampo=".$campo." Resto=".$resto."\n";
		$posini=strpos($resto,$TMP_sep); // busca el primer separador del siguiente campo
		$sepfields=substr($resto,0,$posini); // cadena que hay entre separador final de primer campo y primer separador del segundo campo
		if (substr(strtoupper(trim($sepfields)),0,6)=="WHERE ") { // si el separador de campo es WHERE entonces se acbaron los campos, y ya se acaba el desmembramiento
			$sql2.=":OCI_F".$numcampo.$resto;
			$numcampo++;
			break;
		}
		$sql2.=":OCI_F".$numcampo.$sepfields; // en la query desmembrada guarda el nombre de la variable en vez de valor de campo
		$numcampo++;
		if ($posini>0) { // si hay un siguiente separador
			$resto=substr($resto,$posini+1); // en resto guarda a partir del inicio del siguiente campo a tratar (bucle)
		} else {
			$sql2.=$resto; // si no hay siguiente separador, entonces se acabaron los campos a desmembrar, poniendo en la query obtenido el resto de la query original
			$resto="";
		}
		//echo "\n\nquery temporal=".$sql2."\ncampo=".$campo."\nresto=".$resto."\n";
	}
	//echo "\n\nquery final=".$sql2."\n";
	//for ($ki=0; $ki<$numcampo; $ki++) echo ":OCI_F".$ki."=".${"OCI_F".$ki}."\n";
	$res = oci_parse($id, $sql2); // ejecuta query obtenida
	if ($res) {
		for ($ki=0; $ki<$numcampo; $ki++) oci_bind_by_name($res, ':OCI_F'.$ki, ${"OCI_F".$ki}); // declara campos obtenidos en el desmembramiento
		//$r=oci_execute($res,OCI_DEFAULT);
		if (!oci_execute($res, OCI_COMMIT_ON_SUCCESS)) $err = oci_error($res);
	} else $err = oci_error($res);
	return $err;
}
?>
