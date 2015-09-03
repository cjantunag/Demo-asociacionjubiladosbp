<?
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: ../../index.php");
    die();
}

class DBSql {
  /* public: connection parameters */
  var $Host     = "";
  var $Database = "";
  var $User     = "";
  var $Password = "";

  /* public: configuration parameters */
  var $Auto_Free     = 0;     // Set to 1 for automatic free_result()
  var $Debug         = 0;     // Set to 1 for debugging messages.
  var $Halt_On_Error = "yes"; // "yes" (halt with message), "no" (ignore errors quietly), "report" (ignore errror, but spit a warning)
  var $Seq_Table     = "db_sequence";

  /* public: result array and current row number */
  var $Record   = array();
  var $Row;

  /* public: current error number and error text */
  var $Errno    = 0;
  var $Error    = "";

  /* public: this is an api revision, not a CVS revision. */
  var $type     = "Oracle";
  var $revision = "1.2";

  /* private: link and query handles */
  var $Link_ID  = 0;
  var $Query_ID = 0;
  
  /* public: constructor */
  function DBSql($query = "") {
      $this->query($query);
  }

  /* public: some trivial reporting */
  function link_id() {
    return $this->Link_ID;
  }

  function query_id() {
    return $this->Query_ID;
  }

  /* public: connection management */
  function connect($Database = "", $Host = "", $User = "", $Password = "") {
    /* Handle defaults */
    if ("" == $Database)
      $Database = $this->Database;
    if ("" == $Host)
      $Host     = $this->Host;
    if ("" == $User)
      $User     = $this->User;
    if ("" == $Password)
      $Password = $this->Password;
     
    /* establish connection, select database */
    if ( 0 == $this->Link_ID ) {
    
      $this->Link_ID=oci_connect($User, $Password, "//".$Host."/".$Database);
      if (!$this->Link_ID) {
        $this->halt("connect($Host, $User, \$Password, //".$Host."/".$Database.") failed.");
        return 0;
      }
    }
    
    return $this->Link_ID;
  }

  /* public: discard the query result */
  function free() {
      oci_free_statement($this->Query_ID);
      $this->Query_ID = 0;
  }

  /* public: perform a query */
  function query($Query_String) {
    /* No empty queries, please, since PHP4 chokes on them. */
    if ($Query_String == "")
      /* The empty query string is passed on from the constructor,
       * when calling the class without a query, e.g. in situations
       * like these: '$db = new DBSql_Subclass;'
       */
      return 0;

    if (!$this->connect()) {
      return 0; /* we already complained in connect() about that. */
    };

    // New query, discard previous result.
    if ($this->Query_ID) $this->free();
    if ($this->Debug) printf("Debug: query = %s<br>\n", $Query_String);
    $this->Query_ID = oci_parse($this->Link_ID, $Query_String);
    if ($this->Query_ID) $r = oci_execute($this->Query_ID);
    $this->Row   = 0;
    $this->Errno = oci_error();
    $this->Error = oci_error();
    if (!$this->Query_ID) {
      $this->halt("Invalid SQL: ".$Query_String);
    }

    // Will return nada if it fails. That's fine.
    return $this->Query_ID;
  }

  /* public: walk result set */
  function next_record() {
    if (!$this->Query_ID) {
      $this->halt("next_record called with no query pending.");
      return 0;
    }

    $this->Record = oci_fetch_array($this->Query_ID, OCI_RETURN_LOBS+OCI_RETURN_NULLS+OCI_BOTH);
    $this->Row   += 1;
    $this->Errno  = oci_error();
    $this->Error  = oci_error();

    $stat = is_array($this->Record);
    if (!$stat && $this->Auto_Free) $this->free();
    return $stat;
  }

  /* public: evaluate the result (size, width) */
  function affected_rows() {
    return oci_num_rows($this->Link_ID);
  }

  function num_rows() {
    return oci_num_rows($this->Query_ID);
  }

  function num_fields() {
    return oci_num_fields($this->Query_ID);
  }

  /* public: shorthand notation */
  function nf() {
    return $this->num_rows();
  }

  function np() {
    print $this->num_rows();
  }

  function f($Name) {
    return $this->Record[$Name];
  }

  function p($Name) {
    print $this->Record[$Name];
  }

  /* public: return table metadata */
  function metadata($table='',$full=false) {
    $count = 0;
    $id    = 0;
    $res   = array();

    /* Due to compatibility problems with Table we changed the behavior
     * of metadata();
     * depending on $full, metadata returns the following values:
     * - full is false (default):
     * $result[]:
     *   [0]["table"]  table name
     *   [0]["name"]   field name
     *   [0]["type"]   field type
     *   [0]["len"]    field length
     *   [0]["flags"]  field flags
     * - full is true
     * $result[]:
     *   ["num_fields"] number of metadata records
     *   [0]["table"]  table name
     *   [0]["name"]   field name
     *   [0]["type"]   field type
     *   [0]["len"]    field length
     *   [0]["flags"]  field flags
     *   ["meta"][field name]  index of field named "field name"
     *   The last one is used, if you have a field name, but no index.
     *   Test:  if (isset($result['meta']['field'])) { ...
     */

    // if no $table specified, assume that we are working with a query result
    if ($table) {
      $this->connect();
      $id = $this->query("select * from ".$table);
      if (!$id) $this->halt("Metadata query failed.");
    } else {
      $id = $this->Query_ID; 
      if (!$id) $this->halt("No query specified.");
    }
    $count = oci_num_fields($id);
    if ($count>0) {
	for ($i=0; $i<$count; $i++) {
        	$res[$i]["table"] = $table;
        	$res[$i]["name"]  = oci_field_name  ($id, $i);
        	$res[$i]["type"]  = oci_field_type  ($id, $i); if ($res[$i]["type"]=="VARCHAR2") $res[$i]["type"]="VARCHAR";
        	$res[$i]["len"]   = oci_field_size   ($id, $i);
//		$res[$i]["flags"] = oci_field_flags ($id, $i);
        	$res["meta"][$res[$i]["name"]] = $i;
	}
    } else {
	$id=$this->query("SELECT * FROM user_tab_columns WHERE table_name='$table'");
	while($row=next_record()){
        	$res[$i]["table"] = $table;
        	$res[$i]["name"]  = $row["COLUMN_NAME"];
        	$res[$i]["type"]  = $row["DATA_TYPE"]; if ($res[$i]["type"]=="VARCHAR2") $res[$i]["type"]="VARCHAR";
        	$res[$i]["len"]   = $row["DATA_LENGTH"];
		$res[$i]["flags"] = $row["NULLABLE"];
        	$res["meta"][$res[$i]["name"]] = $i;
	}
    }
    
    // free the result only if we were called on a table
    if ($table) oci_free_statement($id);
    return $res;
  }

  /* private: error handling */
  function halt($msg) {
    $this->Error = oci_error($this->Link_ID);
    $this->Errno = oci_error($this->Link_ID);
    if ($this->Halt_On_Error == "no") return;
    $this->haltmsg($msg);
    if ($this->Halt_On_Error != "report") die("Session halted.");
  }

  function haltmsg($msg) {
    printf("Database error: %s\n", $msg);
    printf("SQL Error: %s (%s)\n",$this->Errno,$this->Error);
  }

  function table_names() {
    $this->query("select TABLE_NAME from TABS");
    $i=0;
    while ($info=oci_fetch_row($this->Query_ID)) {
      $return[$i]["table_name"]= $info[0];
      $return[$i]["tablespace_name"]=$this->Database;
      $return[$i]["database"]=$this->Database;
      $i++;
   }
   return $return;
  }
}
?>
